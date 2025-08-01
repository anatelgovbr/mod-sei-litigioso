<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitProcessoSituacaoINT extends InfraINT {

  public static function montarSelectIdMdLitProcessoSituacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitSituacao='', $dblIdDocumento='', $dblIdProcedimento='', $numIdMdLitTipoControle=''){
    $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
    $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
    $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();

    if ($numIdMdLitSituacao!==''){
      $objMdLitProcessoSituacaoDTO->setNumIdMdLitSituacao($numIdMdLitSituacao);
    }

    if ($dblIdDocumento!==''){
      $objMdLitProcessoSituacaoDTO->setDblIdDocumento($dblIdDocumento);
    }

    if ($dblIdProcedimento!==''){
      $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($dblIdProcedimento);
    }

    if ($numIdMdLitTipoControle!==''){
      $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($numIdMdLitTipoControle);
    }

    if ($strValorItemSelecionado!=null){
      $objMdLitProcessoSituacaoDTO->setBolExclusaoLogica(false);
      $objMdLitProcessoSituacaoDTO->adicionarCriterio(array('SinAtivo','IdMdLitProcessoSituacao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
    $arrObjMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->listar($objMdLitProcessoSituacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitProcessoSituacaoDTO, 'IdMdLitProcessoSituacao', 'IdMdLitProcessoSituacao');
  }

    /**
     * Recupera os lancamentos vinculados a situa��o informada
     *
     * @param $data
     * @return array
     * @throws InfraException
     */
    public static function verificarDependenciasSituacaoComLancamentos($data)
    {
        $mdLitSituacaoDto = new MdLitProcessoSituacaoDTO();
        $mdLitSituacaoDto->ret('IdProcedimento');
        $mdLitSituacaoDto->set('IdProcedimento', $data['id_procedimento']);
        $mdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $mdLitProcessoSituacaoRN->listar($mdLitSituacaoDto);

        $instanciaBanco = BancoSEI::getInstance();
        //recupera somente os lancamentos vinculados a situa��o informada pois o sistema vincula os lancamentos anteriores a cada nova situa��o de decisao
        $arr = $instanciaBanco->consultarSql("select distinct
                            dl.id_md_lit_lancamento
                        from md_lit_processo_situacao ps
                                 inner join md_lit_decisao d on d.id_md_lit_processo_situacao = ps.id_md_lit_processo_situacao
                                 inner join md_lit_rel_decis_lancament dl on dl.id_md_lit_decisao = d.id_md_lit_decisao
                                 inner join md_lit_lancamento l on l.id_procedimento = ps.id_procedimento
                        where ps.id_md_lit_processo_situacao =  " . (int)$data['id_processo_situacao'] . "
                        and not exists(select * from md_lit_cancela_lancamento lc where lc.id_md_lit_lancamento =  dl.id_md_lit_lancamento)
                        and ps.id_procedimento = " . (int)$data['id_procedimento'] . "
                        -- menos os que estao relacionados a outras decisoes no mesmo processo
                        and dl.id_md_lit_lancamento not in (
                            select distinct
                                dl.id_md_lit_lancamento
                            from md_lit_processo_situacao ps
                                  inner join md_lit_decisao d on d.id_md_lit_processo_situacao = ps.id_md_lit_processo_situacao
                                  inner join md_lit_rel_decis_lancament dl on dl.id_md_lit_decisao = d.id_md_lit_decisao
                                  inner join md_lit_lancamento l on l.id_procedimento = ps.id_procedimento
                            where ps.id_md_lit_processo_situacao <  " . (int)$data['id_processo_situacao'] . "
                            and ps.id_procedimento = " . (int)$data['id_procedimento'] . "
                        )");

        $arrIdLancamentos = [];
        $lista = [];
        if($arr){
            foreach ($arr as $idLancamento){
                $arrIdLancamentos[]= $idLancamento['id_md_lit_lancamento'];
            }

            //recupera de forma detalhada os lancamentos para informar ao usuario quais devem ser cancelados
            $mdLitLancamentoRn = new MdLitLancamentoRN();
            $mdLitLancamentoDto = new MdLitLancamentoDTO();
            $mdLitLancamentoDto->ret('IdMdLitLancamento');
            $mdLitLancamentoDto->ret('TipoLancamento');
            $mdLitLancamentoDto->ret('Sequencial');
            $mdLitLancamentoDto->ret('Decisao');
            $mdLitLancamentoDto->ret('Vencimento');
            $mdLitLancamentoDto->ret('VlrLancamento');
            $mdLitLancamentoDto->setNumIdMdLitLancamento($arrIdLancamentos, InfraDTO::$OPER_IN);
            $lista = $mdLitLancamentoRn->listar($mdLitLancamentoDto);
        }

        return $lista;
    }

    /**
     * Verifica se existem dependencias de decis�es a situa��o informada
     *
     * @param $data
     * @return array
     * @throws InfraException
     */
    public static function verificarDependenciasSituacaoComDecisoes($data)
    {
        /*
        $mdLitSituacaoDto = new MdLitProcessoSituacaoDTO();
        $mdLitSituacaoDto->ret('IdProcedimento');
        $mdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $mdLitProcessoSituacaoRN->listar($mdLitSituacaoDto);
        */

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql("select d.*
            from md_lit_processo_situacao ps
            inner join md_lit_decisao d on d.id_md_lit_processo_situacao = ps.id_md_lit_processo_situacao
            where ps.id_md_lit_processo_situacao = " . (int)$data['id_processo_situacao'] . "
            and d.sin_ativo = 'S' -- decisoes ativas
            and (d.valor is not null
                or d.multa is not null
                or d.id_md_lit_especie_decisao is not null
                or d.id_md_lit_tipo_decisao is not null
                or d.prazo is not null
                or d.valor_multa_sem_integracao is not null
                or d.id_md_lit_obrigacao is not null
                )
            order by ps.id_md_lit_processo_situacao desc;"
        );

        return $arr;
    }

  public static function diferencaEntreDias($data1, $data2 = null){
      if(!$data2)
          $data2 = InfraData::getStrDataAtual();

      $data1 = InfraData::getTimestamp($data1);
      $data2 = InfraData::getTimestamp($data2);
      $dias =($data2 - $data1 ) / 86400;

      return (int)$dias;
  }

  public static function validarNumeroSei($numeroSei, $idTpControle, $idProcedimento, $idDocAlterar){
    $xml = '';
    $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
    $dados = $objMdLitProcessoSituacaoRN->buscarDadosDocumento(array(false, $numeroSei, $idTpControle, $idProcedimento, $idDocAlterar));
    $erro  = $dados['erro'] == false ? '0' : '1';

    $xml  = '<Dados>';
    $xml .= '<Erro>' . $erro. '</Erro>';
    $xml .= '<Msg>' . $dados['msg'] . '</Msg>';

    if ($dados['erro'] == false) {
      $xml .= '<NumeroSei>' . $dados['numeroSei'] . '</NumeroSei>';
      $xml .= '<DtDocumento>' . $dados['dtDocumento'] . '</DtDocumento>';
      $xml .= '<TipoDocumento>' . $dados['tipoDocumento'] . '</TipoDocumento>';
      $xml .= '<IdDocumentoNumeroSei>'.$dados['idDocumento'].'</IdDocumentoNumeroSei>';
      $xml .= '<IdSerieNumeroSei>'.$dados['idSerie'].'</IdSerieNumeroSei>';
      $xml .= '<UrlDocumento>'.htmlentities($dados['urlValidada']).'</UrlDocumento>';
      $xml .= '<TituloDoc>'.$dados['tituloDoc'].'</TituloDoc>';
    }

    $xml .= '</Dados>';

    return $xml;
  }
  
  /*
   * Realiza as valida��es convencionais e verifica os vinculos do documento inserido com a situa��o salva anteriormente
   * */
  public static function validarVinculoNumeroSeiSituacao($numeroSei, $idTpControle, $idProcedimento, $idSituacao)
  {

    $xml = '';
    $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
    $dados = $objMdLitProcessoSituacaoRN->buscarDadosDocumento(array(false, $numeroSei, false, $idProcedimento, '0'));

    if ($dados['erro'] == false) {
      $existeVinculo = $objMdLitProcessoSituacaoRN->verificarVinculoSituacaoDocumento(array($idSituacao, $dados['idSerie']));

      if (!$existeVinculo) {
        $dados['erro'] = true;
        $dados['msg'] = 'Este documento n�o est� vinculado a est� Situa��o.';
      }
    }

    $erro = $dados['erro'] == false ? '0' : '1';

    $xml = '<Dados>';
    $xml .= '<Erro>' . $erro . '</Erro>';
    $xml .= '<Msg>' . $dados['msg'] . '</Msg>';

    if ($dados['erro'] == false) {
      $xml .= '<NumeroSei>' . $dados['numeroSei'] . '</NumeroSei>';
      $xml .= '<DtDocumento>' . $dados['dtDocumento'] . '</DtDocumento>';
      $xml .= '<TipoDocumento>' . $dados['tipoDocumento'] . '</TipoDocumento>';
      $xml .= '<IdDocumentoNumeroSei>' . $dados['idDocumento'] . '</IdDocumentoNumeroSei>';
      $xml .= '<IdSerieNumeroSei>' . $dados['idSerie'] . '</IdSerieNumeroSei>';
      $xml .= '<UrlDocumento>'.htmlentities($dados['urlValidada']).'</UrlDocumento>';
      $xml .= '<TituloDoc>'.$dados['tituloDoc'].'</TituloDoc>';
    }

    $xml .= '</Dados>';

    return $xml;

  }

  public static function montarSelectDocumento($idProcedimento)
  {

      $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
      $arrObjMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
      $arrObjMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
      $arrObjMdLitProcessoSituacaoDTO->setStrSinDecisoriaSit('S');
      $arrObjMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
      $arrObjMdLitProcessoSituacaoDTO->retDtaData();
      $arrObjMdLitProcessoSituacaoDTO->retStrProtocoloFormatadoDocumento();
      $arrObjMdLitProcessoSituacaoDTO->retStrNomeSituacao();
      $arrObjMdLitProcessoSituacaoDTO->retStrNomeFase();
      $arrObjMdLitProcessoSituacaoDTO->retStrNomeSerie();

      $arrObjMdLitProcessoSituacao = $objMdLitProcessoSituacaoRN->listar($arrObjMdLitProcessoSituacaoDTO);

      $strRet = "<option value=''></option>";
      foreach($arrObjMdLitProcessoSituacao as $objMdLitProcessoSituacao){
          $strRet .= "<option value='{$objMdLitProcessoSituacao->getNumIdMdLitProcessoSituacao()}'>";
          $strRet .= "{$objMdLitProcessoSituacao->getStrProtocoloFormatadoDocumento()} {$objMdLitProcessoSituacao->getStrNomeSerie()}";
          $strRet .= " - {$objMdLitProcessoSituacao->getStrNomeSituacao()} ";
          $strRet .= "({$objMdLitProcessoSituacao->getStrNomeFase()})</option>";
      }

      return $strRet;
  }

    public static function recuperarDataSituacao($idSituacao)
    {
        if (empty($idSituacao)){
            return '';
        }
        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->setNumIdMdLitProcessoSituacao($idSituacao);
        $objMdLitProcessoSituacaoDTO->retDtaData();

        $objMdLitProcessoSituacao = $objMdLitProcessoSituacaoRN->consultar($objMdLitProcessoSituacaoDTO);
        return $objMdLitProcessoSituacao->getDtaData();
    }
    public static function recuperarLancamentoVinculadoSituacao($idSituacao)
    {
      $xml = '';

      $objMdLitLancamentoRN = new MdLitLancamentoRN();
      $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
      $objMdLitLancamentoDTO->setNumIdSituacaoDecisao($idSituacao);
      $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
      $objMdLitLancamentoDTO->retDtaDecisao();
      $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

      if ($objMdLitLancamento) {
        $idLancamento = $objMdLitLancamento->getNumIdMdLitLancamento();
        $dtaLancamento = $objMdLitLancamento->getDtaDecisao();
        $xml .= "<idLancamento>".$idLancamento ."</idLancamento>\n";
        $xml .= "<dtaDecisao>$dtaLancamento</dtaDecisao>\n";
      }

      return $xml;
    }

    public static function recuperarLancamentoVinculadoSituacaoIntimacao($idSituacao)
    {
      $xml = '';

      $objMdLitLancamentoRN = new MdLitLancamentoRN();
      $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
      $objMdLitLancamentoDTO->setNumIdSituacaoIntimacao($idSituacao);
      $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
      $objMdLitLancamentoDTO->retDtaIntimacao();
      $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

      if ($objMdLitLancamento) {
        $idLancamento = $objMdLitLancamento->getNumIdMdLitLancamento();
        $dtaLancamento = $objMdLitLancamento->getDtaIntimacao();
        $xml .= "<idLancamento>".$idLancamento ."</idLancamento>\n";
        $xml .= "<dtaIntimacao>$dtaLancamento</dtaIntimacao>\n";
      }

      return $xml;
    }

    public static function recuperarLancamentoVinculadoSituacaoIntimacaoRecurso($idSituacao)
        {
          $xml = '';

          $objMdLitLancamentoRN = new MdLitLancamentoRN();
          $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
          $objMdLitLancamentoDTO->setNumIdSituacaoIntimacao($idSituacao);
          $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
          $objMdLitLancamentoDTO->retDtaIntimacao();
          $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

          if ($objMdLitLancamento) {
            $idLancamento = $objMdLitLancamento->getNumIdMdLitLancamento();
            $xml .= "<idLancamento>".$idLancamento ."</idLancamento>\n";
          }

          return $xml;
        }

    public static function recuperarLancamentoVinculadoSituacaoRecurso($idSituacao)
    {
      $xml = '';

      $objMdLitLancamentoRN = new MdLitLancamentoRN();
      $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
      $objMdLitLancamentoDTO->setNumIdSituacaoRecurso($idSituacao);
      $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
      $objMdLitLancamentoDTO->retDtaApresentacaoRecurso();
      $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

      if ($objMdLitLancamento) {
        $idLancamento = $objMdLitLancamento->getNumIdMdLitLancamento();
        $dtaLancamento = $objMdLitLancamento->getDtaApresentacaoRecurso();
        $xml .= "<idLancamento>".$idLancamento ."</idLancamento>\n";
        $xml .= "<dtaRecurso>$dtaLancamento</dtaRecurso>\n";
      }

      return $xml;
    }


    public static function recuperarLancamentoSemDataIntimacao($idProcedimento)
    {
        $xml = '';

        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
        $objMdLitLancamentoDTO->retDtaIntimacao();
        $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        if ($objMdLitLancamento && empty($objMdLitLancamento->getDtaIntimacao())) {
          $idLancamento = $objMdLitLancamento->getNumIdMdLitLancamento();
          $xml .= "<idLancamento>".$idLancamento ."</idLancamento>\n";
        }

        return $xml;
    }

    public static function recuperarLancamentoSemDataRecurso($idProcedimento)
    {
        $xml = '';

        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
        $objMdLitLancamentoDTO->retDtaApresentacaoRecurso();

        $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        if ($objMdLitLancamento && empty($objMdLitLancamento->getDtaApresentacaoRecurso())) {
          $idLancamento = $objMdLitLancamento->getNumIdMdLitLancamento();
          $xml .= "<idLancamento>".$idLancamento ."</idLancamento>\n";
        }

        return $xml;
    }

    public static function verificarPrimeiraIntimacao($idProcedimento, $idSituacao, $novaDataSituacao)
    {
        $xml = '';

        $MdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $objMdLitProcessoSituacaoDTOPrimeiraIntimacao = $MdLitProcessoSituacaoRN->consultarPrimeiraIntimacao($idProcedimento);

        if($objMdLitProcessoSituacaoDTOPrimeiraIntimacao->getNumIdMdLitProcessoSituacao() == $idSituacao){
            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->retTodos(false);
            $objMdLitSituacaoDTO->setStrSinDefesa('S');
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitProcessoSituacaoDTOPrimeiraIntimacao->getNumIdMdLitTipoControle());
            $objMdLitSituacaoDTO->setNumMaxRegistrosRetorno(1);

            $objMdLitSituacaoRN = new MdLitSituacaoRN();
            $objMdLitSituacaoDTO = $objMdLitSituacaoRN->consultar($objMdLitSituacaoDTO);

            if ($objMdLitSituacaoDTO && $objMdLitSituacaoDTO->getNumPrazo())
               $novadata = InfraData::calcularData($objMdLitSituacaoDTO->getNumPrazo(), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $novaDataSituacao);

          $xml .= "<novaData>".$novadata ."</novaData>\n";
        }

        return $xml;
    }

    public static function verificarRelacaoLancamento($idProcedimento, $idSituacao, $tipoSituacao)
    {
        $xml = '';

        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitLancamentoDTO->retNumIdMdLitLancamento();

        switch ($tipoSituacao) {

        case 'Intima��o':
          $objMdLitLancamentoDTO->setNumIdSituacaoIntimacao($idSituacao);
          break;

        case 'Decis�ria':
          $objMdLitLancamentoDTO->setNumIdSituacaoDecisao($idSituacao);
          break;

        case 'Recursal':
          $objMdLitLancamentoDTO->setNumIdSituacaoRecurso($idSituacao);
          break;

        }

        $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);
        $idLancamento = $objMdLitLancamento ? $objMdLitLancamento->getNumIdMdLitLancamento() : null;
        $xml .= "<idLancamento>".$idLancamento ."</idLancamento>\n";

        return $xml;
    }

    public static function calcularDataDecursoPrazoRecurso($idProcedimento, $strDtBase)
    {
        $xml = '<resultado></resultado>';
        $dados = self::montarComboBoxDataDecursoPrazoRecurso($idProcedimento, $strDtBase);

        if ($dados) {
            $xml  = "<resultado>\n";
            $xml .= "<htmlOption>$dados</htmlOption>\n";
            $xml .= "</resultado>";
        }
        return $xml;
    }

    public static function montarComboBoxDataDecursoPrazoRecurso($idProcedimento, $strDtBase)
    {
        $htmlOption = '';
        $objUltimaSituacaoProcedimentoDTO = new MdLitProcessoSituacaoDTO();
        $objUltimaSituacaoProcedimentoDTO->retTodos();
        $objUltimaSituacaoProcedimentoDTO->setDblIdProcedimento($idProcedimento);
        $objUltimaSituacaoProcedimentoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objUltimaSituacaoProcedimentoDTO->setNumMaxRegistrosRetorno(1);

        $objUltimaSituacaoProcedimentoDTO = (new MdLitProcessoSituacaoRN())->consultar($objUltimaSituacaoProcedimentoDTO);

        $objLitSituacaoRN = new MdLitSituacaoRN();

        $objSitDecisaoDTO = new MdLitSituacaoDTO();
        $objSitDecisaoDTO->setNumIdSituacaoLitigioso($objUltimaSituacaoProcedimentoDTO->getNumIdMdLitSituacao());
        $objSitDecisaoDTO->retNumIdSituacaoLitigioso();
        $objSitDecisaoDTO->retNumOrdem();
        $objSitDecisaoDTO->setStrSinDecisoria('S');
        $objSitDecisaoDTO = $objLitSituacaoRN->consultar($objSitDecisaoDTO);

        if ($objSitDecisaoDTO) {

            $objSitRecursoDTO = new MdLitSituacaoDTO();
            $objSitRecursoDTO->setNumIdTipoControleLitigioso($objUltimaSituacaoProcedimentoDTO->getNumIdMdLitTipoControle());
            $objSitRecursoDTO->setStrSinRecursal('S');
            $objSitRecursoDTO->setNumOrdem($objSitDecisaoDTO->getNumOrdem(), InfraDTO::$OPER_MAIOR);
            $objSitRecursoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objSitRecursoDTO->setNumMaxRegistrosRetorno(1);
            $objSitRecursoDTO->retStrPrazo();
            $objSitRecursoDTO->retStrTipoPrazo();
            $objSitRecursoDTO = $objLitSituacaoRN->consultar($objSitRecursoDTO);

            $dados = self::montarHtmlPrazo($strDtBase, $objSitRecursoDTO->getStrPrazo(), $objSitRecursoDTO->getStrTipoPrazo(), null);

            $htmlOption = htmlspecialchars($dados, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
        return $htmlOption;
    }

    public static function recuperarParaEdicaoComboBoxDataDecursoPrazoRecurso($objMdLitLancamentoDTO, $dtIntimacaoDecisaoAplicacaoMulta)
    {
        $htmlOption = '';
        if($objMdLitLancamentoDTO && $dtIntimacaoDecisaoAplicacaoMulta && $objMdLitLancamentoDTO->getNumIdSituacaoDecisao()){

            $objDecisaoDTO = new MdLitProcessoSituacaoDTO();
            $objDecisaoDTO->setNumIdMdLitProcessoSituacao($objMdLitLancamentoDTO->getNumIdSituacaoDecisao());
            $objDecisaoDTO->retNumIdMdLitProcessoSituacao();
            $objDecisaoDTO->retNumIdMdLitSituacao();
            $objDecisaoDTO = (new MdLitProcessoSituacaoRN())->consultar($objDecisaoDTO);

            $objLitSituacaoRN = new MdLitSituacaoRN();
            $objSitDecisaoDTO = new MdLitSituacaoDTO();
            $objSitDecisaoDTO->setNumIdSituacaoLitigioso($objDecisaoDTO->getNumIdMdLitSituacao());
            $objSitDecisaoDTO->retNumIdTipoControleLitigioso();
            $objSitDecisaoDTO->retNumOrdem();
            $objSitDecisaoDTO->setStrSinDecisoria('S');
            $objSitDecisaoDTO = $objLitSituacaoRN->consultar($objSitDecisaoDTO);

            if ($objSitDecisaoDTO) {

                $objSitRecursoDTO = new MdLitSituacaoDTO();
                $objSitRecursoDTO->setNumIdTipoControleLitigioso($objSitDecisaoDTO->getNumIdTipoControleLitigioso());
                $objSitRecursoDTO->setStrSinRecursal('S');
                $objSitRecursoDTO->setNumOrdem($objSitDecisaoDTO->getNumOrdem(), InfraDTO::$OPER_MAIOR);
                $objSitRecursoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
                $objSitRecursoDTO->setNumMaxRegistrosRetorno(1);
                $objSitRecursoDTO->retStrPrazo();
                $objSitRecursoDTO->retStrTipoPrazo();
                $objSitRecursoDTO = $objLitSituacaoRN->consultar($objSitRecursoDTO);
                if($objSitRecursoDTO){
                    $dados = self::montarHtmlPrazo($dtIntimacaoDecisaoAplicacaoMulta, $objSitRecursoDTO->getStrPrazo(), $objSitRecursoDTO->getStrTipoPrazo(), null);

                    $htmlOption = htmlspecialchars($dados, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                }
            }
        }

        return $htmlOption;
    }

    public static function montarHtmlPrazo($dataInicial, $prazo, $tipoPrazo, $dataSelecionada = null)
    {
        $return = null;

        $arrayPrazos = explode(',', $prazo);
        if(count($arrayPrazos) != 1) {
            $return = '<option value=""></option>';
        }
        foreach ($arrayPrazos as $prazo) {

            if ($tipoPrazo == MdLitSituacaoRN::$DIAS_CORRIDOS) {
                $data = InfraData::calcularData($prazo, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dataInicial);
                $option = $data . ' (prazo ' . $prazo . ' dias Corridos)';
            }

            if ($tipoPrazo == MdLitSituacaoRN::$DIAS_UTEIS) {
                $data = self::calcularPrazoDiasUteis($dataInicial, $prazo);
                $option = $data . ' (prazo ' . $prazo . ' dias �teis)';
            }

            $isSelected = ($dataSelecionada == $data) ? 'selected' : '';
            $return .= '<option value="' . $data . '" '.$isSelected.' prazo-recurso="'.$prazo.'" tp-prazo-recurso="'. $tipoPrazo .'">' . $option . '</option>';
        }

        return $return;
    }

    public static function calcularPrazoDiasUteis($dtPrazoInicial, $diasPrazo)
    {
        self::removerTimeDate($dtPrazoInicial);

        $qtdDiasUteis = 0;

        $arrFeriados = self::recuperarFeriados($dtPrazoInicial);

        while ($qtdDiasUteis < $diasPrazo) {
            $dtPrazoInicial = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dtPrazoInicial);
            $diaSemana = InfraData::obterDescricaoDiaSemana($dtPrazoInicial);

            if ($diaSemana != 's�bado' && $diaSemana != 'domingo' && !in_array($dtPrazoInicial, $arrFeriados)) {
                $qtdDiasUteis++;
            }
        }

        return $dtPrazoInicial;
    }

    private static function removerTimeDate(&$strData){
        $countDate  = strlen($strData);
        $isDateTime = $countDate > 10 ? true : false;
        if($isDateTime){
            $arrData = explode(" ",$strData);
            $strData = $arrData[0];
        }
    }

    public static function recuperarFeriados($strDataInicial)
    {
        $numIdOrgao = SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual();

        if (is_null($numIdOrgao)){
            $objOrgaoDTO = new OrgaoDTO();
            $objOrgaoDTO->retNumIdOrgao();
            $objOrgaoDTO->setBolExclusaoLogica(false);
            $objOrgaoDTO->adicionarCriterio(array('SinAtivo','Sigla'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaOrgaoSistema')),InfraDTO::$OPER_LOGICO_AND);

            $objOrgaoRN = new OrgaoRN();
            $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);
            $numIdOrgao = !is_null($arrObjOrgaoDTO) && count($arrObjOrgaoDTO) > 0 ? current($arrObjOrgaoDTO)->getNumIdOrgao() : null;
        }

        $arrFeriados  = array();

        $objFeriadoRN = new FeriadoRN();
        $objFeriadoDTO = new FeriadoDTO();
        $objFeriadoDTO->retDtaFeriado();
        $objFeriadoDTO->retStrDescricao();

        if($numIdOrgao != ''){
            $objFeriadoDTO->adicionarCriterio(array('IdOrgao','IdOrgao'),
              array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
              array(null,$numIdOrgao),
              array(InfraDTO::$OPER_LOGICO_OR));
        }else{
            $objFeriadoDTO->setNumIdOrgao(null);
        }

        $objFeriadoDTO->setDtaFeriado($strDataInicial, InfraDTO::$OPER_MAIOR_IGUAL);
        $objFeriadoDTO->setOrdDtaFeriado(InfraDTO::$TIPO_ORDENACAO_ASC);

        $arrObjFeriadoDTO = $objFeriadoRN->listar($objFeriadoDTO);

        if(!empty($arrObjFeriadoDTO))
        {
            $arrFeriados = InfraArray::converterArrInfraDTO($arrObjFeriadoDTO, 'Feriado');
        }

        return $arrFeriados;
    }
}
?>