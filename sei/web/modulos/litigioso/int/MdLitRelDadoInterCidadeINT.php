<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterCidadeINT extends InfraINT {

  public static function montarSelectIdMdLitDadoInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdCidade='', $numIdMdLitDadoInteressado=''){
    $objMdLitRelDadoInterCidadeDTO = new MdLitRelDadoInterCidadeDTO();
    $objMdLitRelDadoInterCidadeDTO->retNumIdCidade();
    $objMdLitRelDadoInterCidadeDTO->retNumIdMdLitDadoInteressado();
    $objMdLitRelDadoInterCidadeDTO->retNumIdMdLitDadoInteressado();

    if ($numIdCidade!==''){
      $objMdLitRelDadoInterCidadeDTO->setNumIdCidade($numIdCidade);
    }

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitRelDadoInterCidadeDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    $objMdLitRelDadoInterCidadeDTO->setOrdNumIdMdLitDadoInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelDadoInterCidadeRN = new MdLitRelDadoInterCidadeRN();
    $arrObjMdLitRelDadoInterCidadeDTO = $objMdLitRelDadoInterCidadeRN->listar($objMdLitRelDadoInterCidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelDadoInterCidadeDTO, 'array('IdCidade','IdMdLitDadoInteressado')', 'IdMdLitDadoInteressado');
  }
}
?>