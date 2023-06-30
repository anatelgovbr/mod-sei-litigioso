<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/03/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitParametrizarInteressadoINT extends InfraINT {

  public static function montarSelectSinExibe($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitTipoControle='', $numIdMdLitNomeFuncional=''){
    $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();
    $objMdLitParametrizarInteressadoDTO->retNumIdMdLitParametrizarInteressado();
    $objMdLitParametrizarInteressadoDTO->retStrSinExibe();

    if ($numIdMdLitTipoControle!==''){
      $objMdLitParametrizarInteressadoDTO->setNumIdMdLitTipoControle($numIdMdLitTipoControle);
    }

    if ($numIdMdLitNomeFuncional!==''){
      $objMdLitParametrizarInteressadoDTO->setNumIdMdLitNomeFuncional($numIdMdLitNomeFuncional);
    }

    $objMdLitParametrizarInteressadoDTO->setOrdStrSinExibe(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitParametrizarInteressadoRN = new MdLitParamInteressadoRN();
    $arrObjMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->listar($objMdLitParametrizarInteressadoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $objMdLitParametrizarInteressadoDTO, 'IdMdLitParametrizarInteressado', 'SinExibe');
  }
}
?>