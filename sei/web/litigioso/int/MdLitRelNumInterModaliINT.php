<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterModaliINT extends InfraINT {

  public static function montarSelectIdMdLitDadoInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDadoInteressado='', $numIdMdLitModalidade=''){
    $objMdLitRelNumInterModaliDTO = new MdLitRelNumInterModaliDTO();
    $objMdLitRelNumInterModaliDTO->retNumIdMdLitDadoInteressado();
    $objMdLitRelNumInterModaliDTO->retNumIdMdLitModalidade();
    $objMdLitRelNumInterModaliDTO->retNumIdMdLitDadoInteressado();

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitRelNumInterModaliDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    if ($numIdMdLitModalidade!==''){
      $objMdLitRelNumInterModaliDTO->setNumIdMdLitModalidade($numIdMdLitModalidade);
    }

    $objMdLitRelNumInterModaliDTO->setOrdNumIdMdLitDadoInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelNumInterModaliRN = new MdLitRelNumInterModaliRN();
    $arrObjMdLitRelNumInterModaliDTO = $objMdLitRelNumInterModaliRN->listar($objMdLitRelNumInterModaliDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelNumInterModaliDTO, array('IdMdLitDadoInteressado','IdMdLitModalidade'), 'IdMdLitDadoInteressado');
  }
}
?>