<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterCidadeINT extends InfraINT {

  public static function montarSelectIdMdLitDadoInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdCidade='', $numIdMdLitDadoInteressado=''){
    $objMdLitRelNumInterCidadeDTO = new MdLitRelNumInterCidadeDTO();
    $objMdLitRelNumInterCidadeDTO->retNumIdCidade();
    $objMdLitRelNumInterCidadeDTO->retNumIdMdLitDadoInteressado();
    $objMdLitRelNumInterCidadeDTO->retNumIdMdLitDadoInteressado();

    if ($numIdCidade!==''){
      $objMdLitRelNumInterCidadeDTO->setNumIdCidade($numIdCidade);
    }

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitRelNumInterCidadeDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    $objMdLitRelNumInterCidadeDTO->setOrdNumIdMdLitDadoInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelNumInterCidadeRN = new MdLitRelNumInterCidadeRN();
    $arrObjMdLitRelNumInterCidadeDTO = $objMdLitRelNumInterCidadeRN->listar($objMdLitRelNumInterCidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelNumInterCidadeDTO, array('IdCidade','IdMdLitDadoInteressado'), 'IdMdLitDadoInteressado');
  }
}
?>