<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 30/04/2018 - criado por jhon.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelTpControlMotiINT extends InfraINT {

  public static function montarSelectIdMdLitTipoControle($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitTipoControle='', $numIdMdLitMotivo=''){
    $objMdLitRelTpControlMotiDTO = new MdLitRelTpControlMotiDTO();
    $objMdLitRelTpControlMotiDTO->retNumIdMdLitTipoControle();
    $objMdLitRelTpControlMotiDTO->retNumIdMdLitMotivo();
    $objMdLitRelTpControlMotiDTO->retNumIdMdLitTipoControle();

    if ($numIdMdLitTipoControle!==''){
      $objMdLitRelTpControlMotiDTO->setNumIdMdLitTipoControle($numIdMdLitTipoControle);
    }

    if ($numIdMdLitMotivo!==''){
      $objMdLitRelTpControlMotiDTO->setNumIdMdLitMotivo($numIdMdLitMotivo);
    }

    $objMdLitRelTpControlMotiDTO->setOrdNumIdMdLitTipoControle(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelTpControlMotiRN = new MdLitRelTpControlMotiRN();
    $arrObjMdLitRelTpControlMotiDTO = $objMdLitRelTpControlMotiRN->listar($objMdLitRelTpControlMotiDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelTpControlMotiDTO, 'array('.IdMdLitTipoControle.','.IdMdLitMotivo.')', 'IdMdLitTipoControle');
  }
}
