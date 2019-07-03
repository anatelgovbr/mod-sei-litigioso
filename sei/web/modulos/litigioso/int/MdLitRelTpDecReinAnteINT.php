<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/04/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class MdLitRelTpDecReinAnteINT extends InfraINT {

  public static function montarSelectIdMdLitReincidenAnteceden($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitTipoDecisao='', $numIdMdLitReincidenAnteceden=''){
    $objMdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
    $objMdLitRelTpDecReinAnteDTO->retNumIdMdLitReincidenAnteceden();

    if ($numIdMdLitTipoDecisao!==''){
      $objMdLitRelTpDecReinAnteDTO->setNumIdMdLitTipoDecisao($numIdMdLitTipoDecisao);
    }

    if ($numIdMdLitReincidenAnteceden!==''){
      $objMdLitRelTpDecReinAnteDTO->setNumIdMdLitReincidenAnteceden($numIdMdLitReincidenAnteceden);
    }

    $objMdLitRelTpDecReinAnteDTO->setOrdNumIdMdLitReincidenAnteceden(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();
    $arrObjMdLitRelTpDecReinAnteDTO = $objMdLitRelTpDecReinAnteRN->listar($objMdLitRelTpDecReinAnteDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelTpDecReinAnteDTO, '', 'IdMdLitReincidenAnteceden');
  }
}
