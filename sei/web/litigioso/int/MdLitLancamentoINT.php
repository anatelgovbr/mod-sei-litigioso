<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitLancamentoINT extends InfraINT {

  public static function montarSelectIdMdLitLancamento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitSituacaoLancamento='', $numIdUsuario='', $numIdUnidade=''){
    $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
    $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
    $objMdLitLancamentoDTO->retNumIdMdLitLancamento();

    if ($numIdMdLitSituacaoLancamento!==''){
      $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento($numIdMdLitSituacaoLancamento);
    }

    if ($numIdUsuario!==''){
      $objMdLitLancamentoDTO->setNumIdUsuario($numIdUsuario);
    }

    if ($numIdUnidade!==''){
      $objMdLitLancamentoDTO->setNumIdUnidade($numIdUnidade);
    }

    $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitLancamentoRN = new MdLitLancamentoRN();
    $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitLancamentoDTO, 'IdMdLitLancamento', 'IdMdLitLancamento');
  }

  public static function consultaExtratoMulta($arrDecisao, $idProcedimento, $idmdLitLancamento = null){

      $source = array('.', ',');
      $replace = array('', '.');
      $xml = '';
      $creditoNaoLancado            = 0;
      $creditoLancado               = 0;
      $desconto                     = 0;
      $arrecadado                   = 0;
      $ultimoPagamento              = '';
      $devedorAtualizado            = 0;
      $constituidoDefinitivamente   = 0;
      $multaAplicada                = 0;
      $isCancelar                   = 1;
      //data
      $dtDecisaoAplicacaoMulta      = '';
      $dtVencimento                 = '';
      $dtConstituicao               = '';
      $dtIntimacaoConstituicao      = '';
      $dtIntimacaoDecisaoAplicacaoMulta      = '';
      $sinConstituicaoDefinitiva    = '';
      $sinRenunciaRecorrer          = '';
      $isNovoLancamento             = 'N';
      $sinExisteMajoracao           = 'N';
      $corSituacao                  = 'black';
      //Url Modal Histórico de Lançamento
      $strLinkModalHistLanc              = InfraString::formatarXML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_historic_lancamento_listar&id_procedimento='.$idProcedimento.'&id_lancamento='.$idmdLitLancamento));
      $valorTotaMulta = 0;
      $sinExibeCancelamento = 'N';
      $sinSuspenso = 'N';
      $idDadoInteressado = null;
      $idNumeroInteressado = null;

      $objMdLitLancamentoRN = new MdLitLancamentoRN();

      $countArrDecisao = is_array($arrDecisao) ? count($arrDecisao) : 0;

      if($countArrDecisao > 0){
          foreach ($arrDecisao as $decisao){
              $valorMulta = str_replace($source, $replace, $decisao[4]);
              $creditoNaoLancado = bcadd($creditoNaoLancado, $valorMulta, 2);

              if(preg_match('/^novo_/', $decisao[0])){
                  $isNovoLancamento = 'S';
              }else{
                  $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
                  $objMdLitDecisaoRN = new MdLitDecisaoRN();
                  $objMdLitDecisaoDTO->setNumIdMdLitDecisao($decisao[0]);
                  $objMdLitDecisaoDTO->retNumIdMdLitEspecieDecisao();
                  $objMdLitDecisaoDTO->retDblMulta();
                  $objMdLitDecisaoDTO = $objMdLitDecisaoRN->consultar($objMdLitDecisaoDTO);

                  if(is_object($objMdLitDecisaoDTO)){
                      if( $objMdLitDecisaoDTO->getNumIdMdLitEspecieDecisao() != $decisao[3] && $decisao[4] != '')
                          $isNovoLancamento = 'S';
                  }

              }

              if($valorMulta == '')
                  $valorMulta = 0;
              $valorTotalMulta += $valorMulta;

          }
          $multaAplicada = $creditoNaoLancado;

      }
      if($idmdLitLancamento){
          $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
          $objMdLitSituacaoLancamentoDTO->retTodos(false);

          $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
          if($objMdLitSituacaoLancamentoRN->contar($objMdLitSituacaoLancamentoDTO) == 0){
              throw new InfraException('Ocorreu erro na consulta do crédito junto ao Sistema de Arrecadação, em razão da falta de parametrização da lista de Situações do Lançamento de Crédito na Administração do SEI.<br /> Entre em contato com a gestão do SEI no seu órgão para verificar o problema.');
          }

          $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
          $objMdLitLancamentoDTO->retTodos(false);
          $objMdLitLancamentoDTO->retStrCorSituacao();
          $objMdLitLancamentoDTO->retNumIdMdLitDadoInteressadoMdLitNumero();
          $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
          $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idmdLitLancamento);

          $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);
          $idNumeroInteressado = $objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado();
          $idDadoInteressado = $objMdLitLancamentoDTO->getNumIdMdLitDadoInteressadoMdLitNumero();

          //Verificar se tem decisão para esse lançamento
          $temDecisaoLancamento = 'N';
          $objMdLitControleDTO = new MdLitControleDTO();
          $objMdLitControleDTO->setDblIdProcedimento($idProcedimento);
          $objMdLitControleDTO->retNumIdMdLitTipoControle();
          $objMdLitControleDTO = (new MdLitControleRN())->consultar($objMdLitControleDTO);
          $temDecisaoLancamento = (new MdLitProcessoSituacaoRN())->verificaSeHouveDecisaoMulta($idProcedimento, $objMdLitControleDTO->getNumIdMdLitTipoControle(), $idmdLitLancamento) == true ? 'S' : 'N';

          //calculando o valor lançado e o não lançado e a multa aplicada
          $creditoLancado = InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento());
          $totalCreditoLancado = $objMdLitLancamentoRN->valorLancadoPorProcedimento($idProcedimento);
          $creditoNaoLancado = bcsub($creditoNaoLancado, $totalCreditoLancado, 2);
          $multaAplicada = bcadd($creditoLancado,$creditoNaoLancado, 2);
          $sinSuspenso = $objMdLitLancamentoDTO->getStrSinSuspenso();

          $arrecadado = $objMdLitLancamentoDTO->getDblVlrPago()? InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrPago()): 0;
          $devedorAtualizado = $objMdLitLancamentoDTO->getDblVlrSaldoDevedor() ? InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrSaldoDevedor()): 0;

          $desconto = $objMdLitLancamentoDTO->getDblVlrDesconto() ? InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrDesconto()) : 0;

          $ultimoPagamento = $objMdLitLancamentoDTO->getDtaUltimoPagamento();

          $objMdLitCancelaLancRN = new MdLitCancelaLancamentoRN();
          $isCancelar = $objMdLitCancelaLancRN->existeCancelamentoLancamento($idmdLitLancamento) ? '1' : '0';

          //datas
          $dtDecisaoAplicacaoMulta          = $objMdLitLancamentoDTO->getDtaDecisao();
          $dtIntimacaoDecisaoAplicacaoMulta = $objMdLitLancamentoDTO->getDtaIntimacao();


          //DECURSO PRAZO
          $dtDecursoPrazoRecurso            = $objMdLitLancamentoDTO->getDtaDecursoPrazoRecurso();
          $dtVencimento                     = $objMdLitLancamentoDTO->getDtaVencimento();
          $dtConstituicao                   = $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva();
          $dtIntimacaoConstituicao          = $objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva();
          $sinConstituicaoDefinitiva        = $objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva();
          $sinRenunciaRecorrer              = $objMdLitLancamentoDTO->getStrSinRenunciaRecorrer();
          $corSituacao                      = $objMdLitLancamentoDTO->getStrCorSituacao();

          if($objMdLitLancamentoDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL || $objMdLitLancamentoDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL_REDUCAO){
              if($objMdLitLancamentoRN->existeLancamentoMajorado($idProcedimento)){
                  $sinExisteMajoracao = 'S';
              }
          }
          if($sinConstituicaoDefinitiva == 'S'){
              $constituidoDefinitivamente = $totalCreditoLancado;
          }

      }else{
          $totalCreditoLancado = $objMdLitLancamentoRN->valorLancadoPorProcedimento($idProcedimento);
          $creditoNaoLancado = bcsub($creditoNaoLancado, $totalCreditoLancado, 2);
          $multaAplicada = bcadd($creditoLancado,$creditoNaoLancado, 2);

          $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
          $objMdLitSituacaoLancamentoDTO->retTodos(false);

          $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
          $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);

          $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
          $objMdLitLancamentoDTO->retTodos(false);
          $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
          if($objMdLitSituacaoLancamentoDTO){
              $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
          }

          $lancamentoContar = $objMdLitLancamentoRN->contar($objMdLitLancamentoDTO);
          if($lancamentoContar == 0){
              $isNovoLancamento = 'S';
          }

      }

      $sinExisteMajoracao = ($creditoNaoLancado > 0) ? 'S' : 'N';

      if($creditoNaoLancado < 0 && ($creditoLancado + $creditoNaoLancado) <= 0 ){
          $sinExibeCancelamento = 'S';
      }

      if($idmdLitLancamento){
          $arrObjMdLitLancamentoSecundarioDTO = (new MdLitLancamentoRN)->retornaArrObjLancamentoSecundario($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
          $lancamentoFracionado = (!empty($arrObjMdLitLancamentoSecundarioDTO) || $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial()) ? 'true' : 'false';
          $idLancamentoInicial = $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial() ? $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial() : $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
      }

      $idSituacaoDecisao = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumIdSituacaoDecisao() : null;
      $idSituacaoIntimacao = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumIdSituacaoIntimacao() : null;
      $idSituacaoRecurso = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumIdSituacaoRecurso() : null;
      $dtApresentacaoRecurso = $idmdLitLancamento ? $objMdLitLancamentoDTO->getDtaApresentacaoRecurso() : null;
      $dtDecisaoDefinitiva = $idmdLitLancamento ? $objMdLitLancamentoDTO->getDtaDecisaoDefinitiva() : null;
      $dtPrazoDefesa = $idmdLitLancamento ? $objMdLitLancamentoDTO->getDtaPrazoDefesa() : null;
      $selDocumento = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumIdMdLitSituacaoDecisaoDefin() : null;
      $txtSituacaoDocOrigem = $idmdLitLancamento ? self::montarNomeSituacaoDocOrigem($objMdLitLancamentoDTO->getNumIdSituacaoDecisao()) : null;
      $prazoDefesa = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumPrazoSituacaoDefesa() : null;
      $tpPrazoDefesa = $idmdLitLancamento ? $objMdLitLancamentoDTO->getStrTipoPrazoDefesa() : null;
      $prazoRecurso = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumPrazoSituacaoRecurso() : null;
      $tpPrazoRecurso = $idmdLitLancamento ? $objMdLitLancamentoDTO->getStrTipoPrazoRecurso() : null;
      $numeroInteressado = $idmdLitLancamento ? $objMdLitLancamentoDTO->getStrNumeroInteressado() : null;

      //ATUALIZAR DATA DO DECURSO DO PRAZO PARA DEFESA
      $dados['idProcedimento'] = $idProcedimento;
      $dados['objMdLitLancamentoDTO'] = $idmdLitLancamento ? $objMdLitLancamentoDTO : null;
      $strComboDataDecursoPrazoDefesa = (new MdLitProcessoSituacaoRN())->montarSelectDataDecursoPrazoDefesa($dados);
      $htmlOptionDtDecursoPrazo = $strComboDataDecursoPrazoDefesa['htmlOption'];
      $htmlOptionDtDecursoPrazo = htmlspecialchars($htmlOptionDtDecursoPrazo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

      //ATUALIZAR DATA DO DECURSO DO PRAZO PARA RECURSO
      $htmlOptionDtDecursoPrazoRecurso = MdLitProcessoSituacaoINT::recuperarParaEdicaoComboBoxDataDecursoPrazoRecurso($objMdLitLancamentoDTO, $dtIntimacaoDecisaoAplicacaoMulta);

      $xml .= "<dados>\n";
      $xml .= "<isCancelar>".$isCancelar ."</isCancelar>\n";
      $xml .= "<multaAplicada>".InfraUtil::formatarDin($multaAplicada)."</multaAplicada>\n";
      $xml .= "<creditoNaoLancado>".InfraUtil::formatarDin($creditoNaoLancado)."</creditoNaoLancado>\n";
      $xml .= "<creditoLancado>".InfraUtil::formatarDin($creditoLancado)."</creditoLancado>\n";
      $xml .= "<desconto>".InfraUtil::formatarDin($desconto)."</desconto>\n";
      $xml .= "<arrecadado>".InfraUtil::formatarDin($arrecadado)."</arrecadado>\n";
      $xml .= "<ultimoPagamento>".$ultimoPagamento."</ultimoPagamento>\n";
      $xml .= "<devedorAtualizado>".InfraUtil::formatarDin($devedorAtualizado)."</devedorAtualizado>\n";
      $xml .= "<constituidoDefinitivamente>".InfraUtil::formatarDin($constituidoDefinitivamente)."</constituidoDefinitivamente>\n";
      //datas
      $xml .= "<dtDecisaoAplicacaoMulta>".$dtDecisaoAplicacaoMulta."</dtDecisaoAplicacaoMulta>\n";
      $xml .= "<dtIntimacaoDecisaoAplicacaoMulta>".$dtIntimacaoDecisaoAplicacaoMulta."</dtIntimacaoDecisaoAplicacaoMulta>\n";
      $xml .= "<dtDecursoPrazoRecurso>".$dtDecursoPrazoRecurso."</dtDecursoPrazoRecurso>\n";
      $xml .= "<dtVencimento>".$dtVencimento."</dtVencimento>\n";
      $xml .= "<dtConstituicao>".$dtConstituicao."</dtConstituicao>\n";
      $xml .= "<dtIntimacaoConstituicao>".$dtIntimacaoConstituicao."</dtIntimacaoConstituicao>\n";
      $xml .= "<sinConstituicaoDefinitiva>".$sinConstituicaoDefinitiva."</sinConstituicaoDefinitiva>\n";
      $xml .= "<sinRenunciaRecorrer>".$sinRenunciaRecorrer."</sinRenunciaRecorrer>\n";
      $xml .= "<isNovoLancamento>".$isNovoLancamento."</isNovoLancamento>\n";
      $xml .= "<urlHistoricoLancamento>$strLinkModalHistLanc</urlHistoricoLancamento>\n";
      $xml .= "<sinExisteMajorado>$sinExisteMajoracao</sinExisteMajorado>\n";
      $xml .= "<corSituacao>$corSituacao</corSituacao>\n";
      $xml .= "<totalCreditoLancado>".InfraUtil::formatarDin($totalCreditoLancado)."</totalCreditoLancado>\n";
      $xml .= "<valorTotalMulta>".InfraUtil::formatarDin($valorTotalMulta)."</valorTotalMulta>\n";
      $xml .= "<sinExibeCancelamento>$sinExibeCancelamento</sinExibeCancelamento>\n";
      $xml .= "<sinExisteMajoracao>$sinExisteMajoracao</sinExisteMajoracao>\n";
      $xml .= "<sinSuspenso>$sinSuspenso</sinSuspenso>\n";
      $xml .= "<sinTemDecisaoLancamento>$temDecisaoLancamento</sinTemDecisaoLancamento>\n";
      $xml .= "<idInteressado>$idDadoInteressado</idInteressado>\n";
      $xml .= "<idNumeroInteressado>$idNumeroInteressado</idNumeroInteressado>\n";
      $xml .= "<numeroInteressado>$numeroInteressado</numeroInteressado>\n";
      $xml .= "<idSituacaoDecisao>$idSituacaoDecisao</idSituacaoDecisao>\n";
      $xml .= "<idSituacaoIntimacao>$idSituacaoIntimacao</idSituacaoIntimacao>\n";
      $xml .= "<idSituacaoRecurso>$idSituacaoRecurso</idSituacaoRecurso>\n";
      $xml .= "<dtApresentacaoRecurso>$dtApresentacaoRecurso</dtApresentacaoRecurso>\n";
      $xml .= "<dtDecisaoDefinitiva>$dtDecisaoDefinitiva</dtDecisaoDefinitiva>\n";
      $xml .= "<dtPrazoDefesa>$dtPrazoDefesa</dtPrazoDefesa>\n";
      $xml .= "<selDocumento>$selDocumento</selDocumento>\n";
      $xml .= "<txtSituacaoDocOrigem>$txtSituacaoDocOrigem</txtSituacaoDocOrigem>\n";
      $xml .= "<htmlOptionDtDecursoPrazo>$htmlOptionDtDecursoPrazo</htmlOptionDtDecursoPrazo>\n";
      $xml .= "<htmlOptionDtDecursoPrazoRecurso>$htmlOptionDtDecursoPrazoRecurso</htmlOptionDtDecursoPrazoRecurso>\n";
      $xml .= "<prazoDefesa>$prazoDefesa</prazoDefesa>\n";
      $xml .= "<tpPrazoDefesa>$tpPrazoDefesa</tpPrazoDefesa>\n";
      $xml .= "<prazoRecurso>$prazoRecurso</prazoRecurso>\n";
      $xml .= "<tpPrazoRecurso>$tpPrazoRecurso</tpPrazoRecurso>\n";
      $xml .= "<hdnLancamentoFracionado>$lancamentoFracionado</hdnLancamentoFracionado>\n";
    //   $xml .= "<hdnLancamentoIsSecundario>$lancamentoIsSecundario</hdnLancamentoIsSecundario>\n";
      $xml .= "<hdnIdLancamentoInicial>$idLancamentoInicial</hdnIdLancamentoInicial>\n";
      $xml .= "</dados>";

      return $xml;
  }

  public static function montarNomeSituacaoDocOrigem($idSituacao){
      $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
      $arrObjMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
      $arrObjMdLitProcessoSituacaoDTO->setNumIdMdLitProcessoSituacao($idSituacao);
      $arrObjMdLitProcessoSituacaoDTO->setStrSinDecisoriaSit('S');
      $arrObjMdLitProcessoSituacaoDTO->retStrProtocoloFormatadoDocumento();
      $arrObjMdLitProcessoSituacaoDTO->retStrNomeSerie();
      $arrObjMdLitProcessoSituacaoDTO->retStrNomeSituacao();
      $arrObjMdLitProcessoSituacaoDTO->retStrNomeFase();

      $objMdLitProcessoSituacao = $objMdLitProcessoSituacaoRN->consultar($arrObjMdLitProcessoSituacaoDTO);

      $strRet = "{$objMdLitProcessoSituacao->getStrProtocoloFormatadoDocumento()} {$objMdLitProcessoSituacao->getStrNomeSerie()}";
      $strRet .= " - {$objMdLitProcessoSituacao->getStrNomeSituacao()} ";
      $strRet .= "({$objMdLitProcessoSituacao->getStrNomeFase()})";

      return $strRet;
  }

  public static function montarSelectCreditosProcesso($numIdProcedimento, $strValorItemSelecionado = null){

      $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
      $objMdLitSituacaoLancamentoDTO->retTodos(false);

      $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
      $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);

      $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
      $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
      $objMdLitLancamentoDTO->retTodos(false);

      $objMdLitLancamentoDTO->setDblIdProcedimento($numIdProcedimento);
      $objMdLitLancamentoDTO->setOrdStrTipoLancamento(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);
      if($objMdLitSituacaoLancamentoDTO){
          $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
      }

      $objMdLitLancamentoRN = new MdLitLancamentoRN();
      $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);
      $strRet = '';
      $arrIdMdLitLancamento = [];
      if (count($arrObjMdLitLancamentoDTO)>0){
          $seqMajorado = 0;
          $seqPrincipal = 0;
          $mapMajorado = array();
          $seqInternaMajoradoPorRaiz = array();
          $seqInternaPrincipalFracionadoPorRaiz = array();
          $mapIdxPrincipalPorRaiz = array();

          //MAPA COMPLETO (SEM FILTRO DE SITUACAO) PARA PERMITIR CAMINHAR A CADEIA
          //id_md_lit_lancamento_inicial MESMO QUANDO A RAIZ ESTIVER CANCELADA
          $objMdLitLancamentoTodosDTO = new MdLitLancamentoDTO();
          $objMdLitLancamentoTodosDTO->retNumIdMdLitLancamento();
          $objMdLitLancamentoTodosDTO->retTodos(false);
          $objMdLitLancamentoTodosDTO->setDblIdProcedimento($numIdProcedimento);
          $arrObjMdLitLancamentoTodosDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoTodosDTO);

          $arrLancamentosPorId = array();
          foreach ($arrObjMdLitLancamentoTodosDTO as $objMdLitLancamentoTodosItemDTO) {
              $arrLancamentosPorId[$objMdLitLancamentoTodosItemDTO->getNumIdMdLitLancamento()] = $objMdLitLancamentoTodosItemDTO;
          }

          $arrObjMdLitLancamentoExibiveis = array();
          foreach($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){
              $idLancamento = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();

              if ((float)$objMdLitLancamentoDTO->getDblVlrLancamento() <= 0) {
                  continue;
              }

              $arrObjMdLitLancamentoExibiveis[] = $objMdLitLancamentoDTO;
              $arrIdMdLitLancamento[] = $idLancamento;
          }

          if ((is_null($strValorItemSelecionado) || !in_array($strValorItemSelecionado, $arrIdMdLitLancamento)) && count($arrIdMdLitLancamento) > 0) {
              $strValorItemSelecionado = $arrIdMdLitLancamento[0];
          }

          usort($arrObjMdLitLancamentoExibiveis, function ($objLancamentoA, $objLancamentoB) use ($arrLancamentosPorId) {
              return self::compararLancamentoExibicao($objLancamentoA, $objLancamentoB, $arrLancamentosPorId);
          });

          $qtdPrincipais = 0;
          foreach($arrObjMdLitLancamentoExibiveis as $objLancamentoExibivelDTO){
              if ($objLancamentoExibivelDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL
                  && !$objLancamentoExibivelDTO->getNumIdMdLitLancamentoInicial()) {
                  $qtdPrincipais++;
              }
          }
          $exibeNumeracaoPrincipal = $qtdPrincipais > 1;

          foreach($arrObjMdLitLancamentoExibiveis as $objMdLitLancamentoDTO){
              $selected = $objMdLitLancamentoDTO->getNumIdMdLitLancamento() == $strValorItemSelecionado ? 'selected="selected"': '';

              $idxMajorado = null;
              $sufixoMajorado = null;
              $idxPrincipal = null;
              $idxPrincipalFracionado = null;

              if ($objMdLitLancamentoDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL) {
                  if (!$objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial()) {
                      $seqPrincipal++;
                      $idxPrincipal = $seqPrincipal;
                      $mapIdxPrincipalPorRaiz[$objMdLitLancamentoDTO->getNumIdMdLitLancamento()] = $idxPrincipal;
                  } else {
                      $idRaizPrincipal = self::getIdRaizLancamento($objMdLitLancamentoDTO, $arrLancamentosPorId);
                      if (!isset($seqInternaPrincipalFracionadoPorRaiz[$idRaizPrincipal])) {
                          $seqInternaPrincipalFracionadoPorRaiz[$idRaizPrincipal] = 0;
                      }
                      $seqInternaPrincipalFracionadoPorRaiz[$idRaizPrincipal]++;
                      $idxPrincipalFracionado = $seqInternaPrincipalFracionadoPorRaiz[$idRaizPrincipal];
                      $idxPrincipal = isset($mapIdxPrincipalPorRaiz[$idRaizPrincipal]) ? $mapIdxPrincipalPorRaiz[$idRaizPrincipal] : null;
                  }
              }

              if ($objMdLitLancamentoDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO) {
                  $idRaizMajorado = self::getIdRaizLancamento($objMdLitLancamentoDTO, $arrLancamentosPorId);
                  if (!isset($mapMajorado[$idRaizMajorado])) {
                      $seqMajorado++;
                      $mapMajorado[$idRaizMajorado] = $seqMajorado;
                      $seqInternaMajoradoPorRaiz[$idRaizMajorado] = 0;
                  }
                  $idxMajorado = $mapMajorado[$idRaizMajorado];

                  if ($objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial()) {
                      $seqInternaMajoradoPorRaiz[$idRaizMajorado]++;
                      $sufixoMajorado = $seqInternaMajoradoPorRaiz[$idRaizMajorado];
                  }
              }

              $strRet .= "<option value='{$objMdLitLancamentoDTO->getNumIdMdLitLancamento()}' {$selected} >".self::montarNomeTipoLancamentoExibicao($objMdLitLancamentoDTO, $idxMajorado, $sufixoMajorado, $idxPrincipal, $exibeNumeracaoPrincipal, $idxPrincipalFracionado)."</option>";

          }
      }

      return array('opcoes' => $strRet, 'selecionado' => $strValorItemSelecionado, 'todosIdsLancamento' => $arrIdMdLitLancamento);
  }

  private static function montarNomeTipoLancamentoExibicao(
      $objMdLitLancamentoDTO,
      $idxMajorado,
      $sufixoMajorado,
      $idxPrincipal,
      $exibeNumeracaoPrincipal,
      $idxPrincipalFracionado = null
  )
  {
      switch ($objMdLitLancamentoDTO->getStrTipoLancamento()) {
          case MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL:
              $ordemPrincipal = ($exibeNumeracaoPrincipal && !is_null($idxPrincipal)) ? ' ' . $idxPrincipal : '';
              if (!is_null($idxPrincipalFracionado)) {
                  return 'Principal'.$ordemPrincipal.' (Fracionado '.$idxPrincipalFracionado.'): R$ '.InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento()));
              }
              return 'Principal'.$ordemPrincipal.' (R$ '.InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento())).')';
          case MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL_REDUCAO:
              return 'Principal - Redução (R$ '.InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento())).')';
          case MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO:
              $ordemMajorado = is_null($idxMajorado) ? '' : ' ' . $idxMajorado;
              if (!is_null($sufixoMajorado)) {
                  return 'Majorado'.$ordemMajorado.' (Fracionado '.$sufixoMajorado.'): R$ '.InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento()));
              }
              return 'Majorado'.$ordemMajorado.' (R$ '.InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento())).')';
      }

      return '';
  }

  private static function compararLancamentoExibicao($objLancamentoA, $objLancamentoB, $arrLancamentosPorId)
  {
      $ordemTipoA = self::getOrdemTipoLancamentoExibicao($objLancamentoA);
      $ordemTipoB = self::getOrdemTipoLancamentoExibicao($objLancamentoB);
      if ($ordemTipoA != $ordemTipoB) {
          return $ordemTipoA < $ordemTipoB ? -1 : 1;
      }

      if ($objLancamentoA->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO &&
          $objLancamentoB->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO) {
          $idRaizA = self::getIdRaizLancamento($objLancamentoA, $arrLancamentosPorId);
          $idRaizB = self::getIdRaizLancamento($objLancamentoB, $arrLancamentosPorId);
          if ($idRaizA != $idRaizB) {
              return $idRaizA < $idRaizB ? -1 : 1;
          }

          $profundidadeA = self::getProfundidadeLancamento($objLancamentoA, $arrLancamentosPorId);
          $profundidadeB = self::getProfundidadeLancamento($objLancamentoB, $arrLancamentosPorId);
          if ($profundidadeA != $profundidadeB) {
              return $profundidadeA < $profundidadeB ? -1 : 1;
          }
      }

      return $objLancamentoA->getNumIdMdLitLancamento() < $objLancamentoB->getNumIdMdLitLancamento() ? -1 : 1;
  }

  private static function getOrdemTipoLancamentoExibicao($objMdLitLancamentoDTO)
  {
      if ($objMdLitLancamentoDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL ||
          $objMdLitLancamentoDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL_REDUCAO) {
          return 1;
      }

      if ($objMdLitLancamentoDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO) {
          return 2;
      }

      return 9;
  }

  private static function getProfundidadeLancamento($objMdLitLancamentoDTO, $arrLancamentosPorId)
  {
      $profundidade = 0;
      $idPai = $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial();
      $arrVisitados = array($objMdLitLancamentoDTO->getNumIdMdLitLancamento() => true);

      while ($idPai && isset($arrLancamentosPorId[$idPai]) && !isset($arrVisitados[$idPai])) {
          $profundidade++;
          $arrVisitados[$idPai] = true;
          $idPai = $arrLancamentosPorId[$idPai]->getNumIdMdLitLancamentoInicial();
      }

      return $profundidade;
  }

  private static function getIdRaizLancamento($objMdLitLancamentoDTO, $arrLancamentosPorId)
  {
      $idRaiz = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
      $idPai = $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial();
      $arrVisitados = array($idRaiz => true);

      while ($idPai && isset($arrLancamentosPorId[$idPai]) && !isset($arrVisitados[$idPai])) {
          $arrVisitados[$idPai] = true;
          $idRaiz = $idPai;
          $idPai = $arrLancamentosPorId[$idPai]->getNumIdMdLitLancamentoInicial();
      }

      return $idRaiz;
  }

  public static function getLancamentos($idProcedimento)  {
      $MdLitLancamentoDTO = new MdLitLancamentoDTO();
      $MdLitLancamentoDTO->ret('IdMdLitLancamento');
      $MdLitLancamentoDTO->set('IdProcedimento', $idProcedimento);

      $MdLitLancamentoRn = new MdLitLancamentoRN();
      $arrLancamentoDTO = $MdLitLancamentoRn->listar($MdLitLancamentoDTO);

      return $arrLancamentoDTO;
  }

  public static function consultarUltimoLancamento($idProcedimento)
  {
      $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
      $objMdLitSituacaoLancamentoDTO->retTodos(false);

      $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
      $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);

      $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
      $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
      $objMdLitLancamentoDTO->retTodos(false);

      $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
      $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
      $objMdLitLancamentoDTO->setNumIdMdLitLancamentoInicial(null, InfraDTO::$OPER_IGUAL);
      if($objMdLitSituacaoLancamentoDTO){
          $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
      }

      $objMdLitLancamentoRN = new MdLitLancamentoRN();
      $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

      return $objMdLitLancamento ? $objMdLitLancamento->getNumIdMdLitLancamento() : null;
  }

  public static function obterLinkModalFracionarMultas($idLancamento)
  {
      $url = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_fracionar_multas&id_lancamento=' . $idLancamento);
      $url = htmlspecialchars($url, ENT_XML1, 'UTF-8');
      $xml  = "<resultado>\n";
      $xml .= "<url>$url</url>\n";
      $xml .= "</resultado>";

      return $xml;
  }

  public static function consultarDecisoesProcesso($idLancamento)
  {

      $objMdLitLancamentoDTO = new MdLitLancamentoDTO;
      $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento);
      $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
      $objMdLitLancamentoDTO->retNumIdMdLitLancamentoInicial();
      $objMdLitLancamentoDTO = (new MdLitLancamentoRN)->consultar($objMdLitLancamentoDTO);

      $idLancamentoInicial = $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial() ? $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial() : $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
      $idLancamentoSecundario = $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial() ? $objMdLitLancamentoDTO->getNumIdMdLitLancamento() : null;

      return array(
          'inicial' => self::decisoesLancamento($idLancamentoInicial),
          'secundario' => $idLancamentoSecundario ? self::decisoesLancamento($idLancamentoSecundario) : '',
          'id_lancamento_inicial' => $idLancamentoInicial,
          'id_lancamento_secundario' => $idLancamentoSecundario ? $idLancamentoSecundario : ''
      );
  }

  private static function decisoesLancamento($idLancamento)
  {
      $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
      $objMdLitRelDecisLancamentDTO->retNumIdMdLitDecisao();
      $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($idLancamento);

      $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();
      $arrRelDecisLancamento = $objMdLitRelDecisLancamentRN->listar($objMdLitRelDecisLancamentDTO);
      $arrIdDecisoes = InfraArray::converterArrInfraDTO($arrRelDecisLancamento, 'IdMdLitDecisao');

      $arrObjMdLitDecisaoDTO = self::recuperarDecisoesParaFracionamento($arrIdDecisoes);

      return self::prepararOptionFracionamento($arrObjMdLitDecisaoDTO);
  }

  public static function calcularValorDecisao($objMdLitDecisaoDTO)
  {
      $valorMulta = str_replace(',', '.', $objMdLitDecisaoDTO->getDblMulta());
      $valorMulta = floatval($valorMulta);
      $valorMultaAnterior = self::buscarValoresDecisoesAnteriores($objMdLitDecisaoDTO);
      $valorMultaAnterior = str_replace(',', '.', $valorMultaAnterior);
      $valorMultaAnterior = floatval($valorMultaAnterior);
      return $valorMulta - $valorMultaAnterior;
  }

  private static function buscarValoresDecisoesAnteriores($objMdLitDecisaoDTO)
  {
      $idOrigem = $objMdLitDecisaoDTO->getNumIdMdLitDecisaoOrigem();
      if (!$idOrigem) {
          return number_format(0, 2, ',', '');
      }

      // SE A DECISAO DE ORIGEM NAO ESTA MAIS VINCULADA A NENHUM LANCAMENTO (FOI REDISTRIBUIDA/
      // ABSORVIDA POR OUTRO BOLETO), SEU VALOR JA NAO E CONTADO EM NENHUM LUGAR - A DECISAO ATUAL
      // DEVE REPRESENTAR O VALOR CHEIO, NAO SO A DIFERENCA EM RELACAO A ORIGEM.
      $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
      $objMdLitRelDecisLancamentDTO->setNumIdMdLitDecisao($idOrigem);
      $objMdLitRelDecisLancamentDTO->setNumMaxRegistrosRetorno(1);
      $objMdLitRelDecisLancamentDTO->retNumIdMdLitLancamento();
      $arrObjMdLitRelDecisLancamentDTO = (new MdLitRelDecisLancamentRN())->listar($objMdLitRelDecisLancamentDTO);
      if (empty($arrObjMdLitRelDecisLancamentDTO)) {
          return number_format(0, 2, ',', '');
      }

      $objMdLitDecisaoOrigemDTO = new MdLitDecisaoDTO();
      $objMdLitDecisaoOrigemDTO->setNumIdMdLitDecisao($idOrigem);
      $objMdLitDecisaoOrigemDTO->retDblMulta();
      $objMdLitDecisaoOrigemDTO = (new MdLitDecisaoRN())->consultar($objMdLitDecisaoOrigemDTO);

      if (!$objMdLitDecisaoOrigemDTO || $objMdLitDecisaoOrigemDTO->getDblMulta() === null || $objMdLitDecisaoOrigemDTO->getDblMulta() === '') {
          return number_format(0, 2, ',', '');
      }

      return number_format(floatval(str_replace(',', '.', $objMdLitDecisaoOrigemDTO->getDblMulta())), 2, ',', '');
  }
//   private static function decisoesLancamentoFilho($idLancamento)
//   {
//       $options = '';
//       $objMdLitLancamentoDTO = new MdLitLancamentoDTO;
//       $objMdLitLancamentoDTO->setNumIdMdLitLancamentoInicial($idLancamento);
//       $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
//       $objMdLitLancamentoDTO = (new MdLitLancamentoRN)->consultar($objMdLitLancamentoDTO);

//       if($objMdLitLancamentoDTO){

//           $objMdLitCancelaLancDTO = new MdLitCancelaLancamentoDTO();
//           $objMdLitCancelaLancDTO->setNumIdMdLitLancamento($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
//           $objMdLitCancelaLancDTO->retNumIdMdLitCancelaLancamento();
//           $objMdLitCancelaLancDTO = (new MdLitCancelaLancamentoRN)->consultar($objMdLitCancelaLancDTO);

//           if(!$objMdLitCancelaLancDTO){
//               $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
//               $objMdLitRelDecisLancamentDTO->retNumIdMdLitDecisao();
//               $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
//               $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();
//               $arrRelDecisLancamento = $objMdLitRelDecisLancamentRN->listar($objMdLitRelDecisLancamentDTO);
//               $arrIdDecisoes = InfraArray::converterArrInfraDTO($arrRelDecisLancamento, 'IdMdLitDecisao');
              
//               $arrObjMdLitDecisaoDTO = self::recuperarDecisoesParaFracionamento($arrIdDecisoes);
//               $options = self::prepararOptionFracionamento($arrObjMdLitDecisaoDTO);
//           }

//       }
//       return $options;
//   }

  private static function recuperarDecisoesParaFracionamento($arrIdDecisoes)
  {
        if (empty($arrIdDecisoes)) {
            return array();
        }

        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        $objMdLitDecisaoDTO->retTodos(false);
        $objMdLitDecisaoDTO->retNumIdMdLitDecisao();
        $objMdLitDecisaoDTO->retNumIdMdLitDecisaoOrigem();
        $objMdLitDecisaoDTO->retNumIdMdLitRelDisNorConCtr();
        $objMdLitDecisaoDTO->retDblMulta();
        $objMdLitDecisaoDTO->retStrNorma();
        $objMdLitDecisaoDTO->retStrDispositivo();
        $objMdLitDecisaoDTO->retStrDescricaoDispNorm();
        $objMdLitDecisaoDTO->setNumIdMdLitDecisao($arrIdDecisoes, InfraDTO::$OPER_IN);
        $objMdLitDecisaoDTO->setOrdNumIdMdLitDecisao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdLitDecisaoRN = new MdLitDecisaoRN();
        $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listar($objMdLitDecisaoDTO);

        // QUANDO A MESMA NORMA/DISPOSITIVO (id_md_lit_rel_dis_nor_con_ctr) TEM MAIS DE UMA
        // DECISAO VINCULADA AO MESMO LANCAMENTO (EX.: DECISAO ORIGINAL + DECISAO RECURSAL QUE
        // A REVISA), MANTEM SO A MAIS RECENTE (MAIOR ID) DE CADA GRUPO. NAO USA sin_ultima_decisao
        // COMO FILTRO DIRETO NA CONSULTA PORQUE ESSA FLAG NAO E ATRIBUIDA DE FORMA CONFIAVEL EM
        // TODOS OS FLUXOS DE CRIACAO DE DECISAO (DECISOES NOVAS DE UMA UNICA SITUACAO PODEM FICAR
        // COM sin_ultima_decisao='N' MESMO SENDO AS UNICAS/VIGENTES).
        $arrMaisRecentePorGrupo = array();
        foreach ($arrObjMdLitDecisaoDTO as $objDecisaoItemDTO) {
            $idGrupo = $objDecisaoItemDTO->getNumIdMdLitRelDisNorConCtr();
            if (!isset($arrMaisRecentePorGrupo[$idGrupo]) || $objDecisaoItemDTO->getNumIdMdLitDecisao() > $arrMaisRecentePorGrupo[$idGrupo]->getNumIdMdLitDecisao()) {
                $arrMaisRecentePorGrupo[$idGrupo] = $objDecisaoItemDTO;
            }
        }

        // SO DESCARTA POR multa NULA DEPOIS DE ESCOLHIDA A DECISAO MAIS RECENTE DE CADA GRUPO:
        // SE FOSSE ANTES, UMA DECISAO RECURSAL VIGENTE COM MULTA NULA (EX.: RECURSO PROVIDO, SEM
        // COBRANCA PARA O ARTIGO) SERIA IGNORADA NA DISPUTA E A DECISAO ANTIGA/SUPERADA DO MESMO
        // ARTIGO ENTRARIA NA SOMA POR SER A UNICA CANDIDATA RESTANTE NO GRUPO.
        $arrRetorno = array();
        foreach ($arrMaisRecentePorGrupo as $objDecisaoVigenteDTO) {
            if (InfraString::isBolVazia($objDecisaoVigenteDTO->getDblMulta())) {
                continue;
            }
            $arrRetorno[] = $objDecisaoVigenteDTO;
        }

        return $arrRetorno;
  }

  private static function prepararOptionFracionamento($arrObjMdLitDecisaoDTO)
  {
      $options = '';

      foreach ($arrObjMdLitDecisaoDTO as $objMdLitDecisaoDTO) {
          $valor = self::calcularValorDecisao($objMdLitDecisaoDTO);
          $valorData = number_format((float)$valor, 2, ".", "");
          if ((float)$valorData == 0.0) {
              continue;
          }
          $valor = number_format((float)$valor, 2, ',', '.');
          $strDescricaoDispNorm = $objMdLitDecisaoDTO->getStrDescricaoDispNorm();
          $options .= "<option value=\"{$objMdLitDecisaoDTO->getNumIdMdLitDecisao()}\" data-valor=\"{$valorData}\" title=\"{$strDescricaoDispNorm}\">{$objMdLitDecisaoDTO->getStrNorma()} ({$objMdLitDecisaoDTO->getStrDispositivo()}) - R$ {$valor}</option>";
      }

      return $options;
  }

  public static function prepararFracionarMultas($dados)
  {
      self::validarFacionamentoMultas($dados);
      try {
          (new MdLitLancamentoRN)->fracionarMultas($dados);
        
      } catch (Exception $e) {
          throw $e;
      }
  }

  private static function validarFacionamentoMultas($dados)
  {
      if (!isset($dados['id_lancamento_inicial']) || $dados['id_lancamento_inicial'] === '' || !is_numeric($dados['id_lancamento_inicial'])) {
          throw new Exception('id_lancamento_principal invalido.');
      }
        
      if (isset($dados['id_lancamento_secundario']) && $dados['id_lancamento_secundario'] !== '' && !is_numeric($dados['id_lancamento_secundario'])) {
          throw new Exception('id_lancamento_secundario invalido.');
      }
        
      if (!isset($dados['inicial']) || !is_array($dados['inicial']) || count($dados['inicial']) === 0) {
          throw new Exception('inicial invalido.');
      }

  }
    
}
?>
