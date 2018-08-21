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

      $objMdLitLancamentoRN = new MdLitLancamentoRN();

      if(count($arrDecisao)){
          foreach ($arrDecisao as $decisao){
              $valorMulta = str_replace($source, $replace, $decisao[4]);
              $creditoNaoLancado = bcadd($creditoNaoLancado, $valorMulta, 2);

              if(preg_match('/^novo_/', $decisao[0])){
                  $isNovoLancamento = 'S';
              }

          }
          $multaAplicada = $creditoNaoLancado;

      }
      if($idmdLitLancamento){

          $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
          $objMdLitLancamentoDTO->retTodos(false);
          $objMdLitLancamentoDTO->retStrCorSituacao();
          $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
          $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idmdLitLancamento);

          $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

          //calculando o valor lançado e o não lançado e a multa aplicada
          $creditoLancado = InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento());
          $totalCreditoLancado = $objMdLitLancamentoRN->valorLancadoPorProcedimento($idProcedimento);
          $creditoNaoLancado = bcsub($creditoNaoLancado, $totalCreditoLancado, 2);
          $multaAplicada = bcadd($creditoLancado,$creditoNaoLancado, 2);

          $arrecadado = $objMdLitLancamentoDTO->getDblVlrPago()? InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrPago()): 0;
          $devedorAtualizado = $objMdLitLancamentoDTO->getDblVlrSaldoDevedor() ? InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrSaldoDevedor()): 0;

          $desconto = $objMdLitLancamentoDTO->getDblVlrDesconto() ? InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrDesconto()) : 0;

          $ultimoPagamento = $objMdLitLancamentoDTO->getDtaUltimoPagamento();
        

          $objMdLitCancelaLancRN = new MdLitCancelaLancamentoRN();
          $isCancelar = $objMdLitCancelaLancRN->existeCancelamentoLancamento($idmdLitLancamento) ? '1' : '0';

          //datas
          $dtDecisaoAplicacaoMulta          = $objMdLitLancamentoDTO->getDtaDecisao();
          $dtIntimacaoDecisaoAplicacaoMulta = $objMdLitLancamentoDTO->getDtaIntimacao();
          $dtVencimento                     = $objMdLitLancamentoDTO->getDtaVencimento();
          $dtConstituicao                   = $objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva();
          $dtIntimacaoConstituicao          = $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva();
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
      $xml .= "<dtVencimento>".$dtVencimento."</dtVencimento>\n";
      $xml .= "<dtConstituicao>".$dtConstituicao."</dtConstituicao>\n";
      $xml .= "<dtIntimacaoConstituicao>".$dtIntimacaoConstituicao."</dtIntimacaoConstituicao>\n";
      $xml .= "<sinConstituicaoDefinitiva>".$sinConstituicaoDefinitiva."</sinConstituicaoDefinitiva>\n";
      $xml .= "<sinRenunciaRecorrer>".$sinRenunciaRecorrer."</sinRenunciaRecorrer>\n";
      $xml .= "<isNovoLancamento>".$isNovoLancamento."</isNovoLancamento>\n";
      $xml .= "<urlHistoricoLancamento>$strLinkModalHistLanc</urlHistoricoLancamento>\n";
      $xml .= "<sinExisteMajorado>$sinExisteMajoracao</sinExisteMajorado>\n";
      $xml .= "<corSituacao>$corSituacao</corSituacao>\n";
      $xml .= "</dados>";

      return $xml;
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
      $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);
      if($objMdLitSituacaoLancamentoDTO){
          $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
      }

      $objMdLitLancamentoRN = new MdLitLancamentoRN();
      $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);
      $strRet = '';

      if (count($arrObjMdLitLancamentoDTO)>0){

          foreach($arrObjMdLitLancamentoDTO as $key => $objMdLitLancamentoDTO){
              $selected = $objMdLitLancamentoDTO->getNumIdMdLitLancamento() == $strValorItemSelecionado ? 'selected="selected"': '';
              $strRet .= "<option value='{$objMdLitLancamentoDTO->getNumIdMdLitLancamento()}' {$selected} >{$objMdLitLancamentoDTO->getStrNomeTipoLancamentoComValor()}</option>";
          }
      }
      return $strRet;
  }
}
?>