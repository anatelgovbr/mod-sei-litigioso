<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
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
     * Recupera os lancamentos vinculados a situação informada
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
        //recupera somente os lancamentos vinculados a situação informada pois o sistema vincula os lancamentos anteriores a cada nova situação de decisao
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
                            where ps.id_md_lit_processo_situacao <>  " . (int)$data['id_processo_situacao'] . "
                            and ps.id_procedimento = " . (int)$data['id_procedimento'] . "
                        );");

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
     * Verifica se existem dependencias de decisões a situação informada
     *
     * @param $data
     * @return array
     * @throws InfraException
     */
    public static function verificarDependenciasSituacaoComDecisoes($data)
    {
        $mdLitSituacaoDto = new MdLitProcessoSituacaoDTO();
        $mdLitSituacaoDto->ret('IdProcedimento');
        $mdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $mdLitProcessoSituacaoRN->listar($mdLitSituacaoDto);

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql("select d.*
            from md_lit_processo_situacao ps
            inner join md_lit_decisao d on d.id_md_lit_processo_situacao = ps.id_md_lit_processo_situacao
            where ps.id_md_lit_processo_situacao = " . (int)$data['id_processo_situacao'] . "
            and d.sin_ativo = 'S' -- decisoes ativas
            and (d.valor_ressarcimento is not null
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
   * Realiza as validações convencionais e verifica os vinculos do documento inserido com a situação salva anteriormente
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
        $dados['msg'] = 'Este documento não está vinculado a está Situação.';
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
}
?>