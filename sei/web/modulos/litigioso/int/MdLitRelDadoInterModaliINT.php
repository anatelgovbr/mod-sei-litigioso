<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterModaliINT extends InfraINT {

  public static function montarSelectIdMdLitDadoInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDadoInteressado='', $numIdMdLitModalidade=''){
    $objMdLitRelDadoInterModaliDTO = new MdLitRelDadoInterModaliDTO();
    $objMdLitRelDadoInterModaliDTO->retNumIdMdLitDadoInteressado();
    $objMdLitRelDadoInterModaliDTO->retNumIdMdLitModalidade();
    $objMdLitRelDadoInterModaliDTO->retNumIdMdLitDadoInteressado();

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitRelDadoInterModaliDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    if ($numIdMdLitModalidade!==''){
      $objMdLitRelDadoInterModaliDTO->setNumIdMdLitModalidade($numIdMdLitModalidade);
    }

    $objMdLitRelDadoInterModaliDTO->setOrdNumIdMdLitDadoInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelDadoInterModaliRN = new MdLitRelDadoInterModaliRN();
    $arrObjMdLitRelDadoInterModaliDTO = $objMdLitRelDadoInterModaliRN->listar($objMdLitRelDadoInterModaliDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelDadoInterModaliDTO, 'array('IdMdLitDadoInteressado','IdMdLitModalidade')', 'IdMdLitDadoInteressado');
  }
}
?>