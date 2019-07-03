<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/07/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class MdLitMapeaParamValorINT extends InfraINT {

  public static function montarSelectIdMdLitMapeaParamValor($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitMapeaParamEntrada=''){
    $objMdLitMapeaParamValorDTO = new MdLitMapeaParamValorDTO();
    $objMdLitMapeaParamValorDTO->retNumIdMdLitMapeaParamValor();
    $objMdLitMapeaParamValorDTO->retNumIdMdLitMapeaParamValor();

    if ($numIdMdLitMapeaParamEntrada!==''){
      $objMdLitMapeaParamValorDTO->setNumIdMdLitMapeaParamEntrada($numIdMdLitMapeaParamEntrada);
    }

    $objMdLitMapeaParamValorDTO->setOrdNumIdMdLitMapeaParamValor(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitMapeaParamValorRN = new MdLitMapeaParamValorRN();
    $arrObjMdLitMapeaParamValorDTO = $objMdLitMapeaParamValorRN->listar($objMdLitMapeaParamValorDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitMapeaParamValorDTO, 'IdMdLitMapeaParamValor', 'IdMdLitMapeaParamValor');
  }
}
?>