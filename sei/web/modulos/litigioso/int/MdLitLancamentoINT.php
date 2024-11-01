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

      $idSituacaoDecisao = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumIdSituacaoDecisao() : null;
      $idSituacaoIntimacao = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumIdSituacaoIntimacao() : null;
      $idSituacaoRecurso = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumIdSituacaoRecurso() : null;
      $dtApresentacaoRecurso = $idmdLitLancamento ? $objMdLitLancamentoDTO->getDtaApresentacaoRecurso() : null;
      $dtDecisaoDefinitiva = $idmdLitLancamento ? $objMdLitLancamentoDTO->getDtaDecisaoDefinitiva() : null;
      $dtPrazoDefesa = $idmdLitLancamento ? $objMdLitLancamentoDTO->getDtaPrazoDefesa() : null;
      $selDocumento = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumIdMdLitSituacaoDecisaoDefin() : null;
      $txtSituacaoDocOrigem = $idmdLitLancamento ? (new MdLitLancamentoINT())->montarNomeSituacaoDocOrigem($objMdLitLancamentoDTO->getNumIdSituacaoDecisao()) : null;
      $prazoDefesa = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumPrazoSituacaoDefesa() : null;
      $tpPrazoDefesa = $idmdLitLancamento ? $objMdLitLancamentoDTO->getStrTipoPrazoDefesa() : null;
      $prazoRecurso = $idmdLitLancamento ? $objMdLitLancamentoDTO->getNumPrazoSituacaoRecurso() : null;
      $tpPrazoRecurso = $idmdLitLancamento ? $objMdLitLancamentoDTO->getStrTipoPrazoRecurso() : null;


      //ATUALIZAR DATA DO DECURSO DO PRAZO PARA DEFESA
      $dados['idProcedimento'] = $idProcedimento;
      $dados['objMdLitLancamentoDTO'] = $idmdLitLancamento ? $objMdLitLancamentoDTO : null;
      $strComboDataDecursoPrazoDefesa = (new MdLitProcessoSituacaoRN())->montarSelectDataDecursoPrazoDefesa($dados);
      $htmlOptionDtDecursoPrazo = $strComboDataDecursoPrazoDefesa['htmlOption'];
      $htmlOptionDtDecursoPrazo = htmlspecialchars($htmlOptionDtDecursoPrazo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

      //ATUALIZAR DATA DO DECURSO DO PRAZO PARA RECURSO
      $htmlOptionDtDecursoPrazoRecurso = (new MdLitProcessoSituacaoINT())->recuperarParaEdicaoComboBoxDataDecursoPrazoRecurso($objMdLitLancamentoDTO, $dtIntimacaoDecisaoAplicacaoMulta);

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
          foreach($arrObjMdLitLancamentoDTO as $key => $objMdLitLancamentoDTO){
              if(is_null($strValorItemSelecionado) && $key == 0){
                  $strValorItemSelecionado = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
              }
              $arrIdMdLitLancamento[] = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
              $selected = $objMdLitLancamentoDTO->getNumIdMdLitLancamento() == $strValorItemSelecionado ? 'selected="selected"': '';
              $strRet .= "<option value='{$objMdLitLancamentoDTO->getNumIdMdLitLancamento()}' {$selected} >{$objMdLitLancamentoDTO->getStrNomeTipoLancamentoComValor($key)}</option>";

          }
      }

      return array('opcoes' => $strRet, 'selecionado' => $strValorItemSelecionado, 'todosIdsLancamento' => $arrIdMdLitLancamento);
  }

  public static function getLancamentos($idProcedimento)
  {
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
      if($objMdLitSituacaoLancamentoDTO){
          $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
      }

      $objMdLitLancamentoRN = new MdLitLancamentoRN();
      $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

      return $objMdLitLancamento ? $objMdLitLancamento->getNumIdMdLitLancamento() : null;
  }

}
?>