<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/04/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class MdLitReincidenAntecedenINT extends InfraINT {

  public static function montarSelectIdMdLitReincidenAnteceden($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
    $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();
    $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();

    $objMdLitReincidenAntecedenDTO->setOrdNumIdMdLitReincidenAnteceden(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
    $arrObjMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->listar($objMdLitReincidenAntecedenDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitReincidenAntecedenDTO, 'IdMdLitReincidenAnteceden', 'IdMdLitReincidenAnteceden');
  }
}
