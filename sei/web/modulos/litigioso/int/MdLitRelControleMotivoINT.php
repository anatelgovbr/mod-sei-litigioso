<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/04/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelControleMotivoINT extends InfraINT {

  public static function montarSelectIdMdLitControle($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitControle='', $numIdMdLitMotivo=''){
    $objMdLitRelControleMotivoDTO = new MdLitRelControleMotivoDTO();
    $objMdLitRelControleMotivoDTO->retNumIdMdLitControle();
    $objMdLitRelControleMotivoDTO->retNumIdMdLitMotivo();
    $objMdLitRelControleMotivoDTO->retNumIdMdLitControle();

    if ($numIdMdLitControle!==''){
      $objMdLitRelControleMotivoDTO->setNumIdMdLitControle($numIdMdLitControle);
    }

    if ($numIdMdLitMotivo!==''){
      $objMdLitRelControleMotivoDTO->setNumIdMdLitMotivo($numIdMdLitMotivo);
    }

    $objMdLitRelControleMotivoDTO->setOrdNumIdMdLitControle(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelControleMotivoRN = new MdLitRelControleMotivoRN();
    $arrObjMdLitRelControleMotivoDTO = $objMdLitRelControleMotivoRN->listar($objMdLitRelControleMotivoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelControleMotivoDTO, 'array('.IdMdLitControle.','.IdMdLitMotivo.')', 'IdMdLitControle');
  }
}
