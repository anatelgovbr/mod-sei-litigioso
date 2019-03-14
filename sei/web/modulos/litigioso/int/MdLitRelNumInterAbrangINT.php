<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class MdLitRelNumInterAbrangINT extends InfraINT {

  public static function montarSelectIdMdLitDadoInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDadoInteressado='', $numIdMdLitAbrangencia=''){
    $objMdLitRelNumInterAbrangDTO = new MdLitRelNumInterAbrangDTO();
    $objMdLitRelNumInterAbrangDTO->retNumIdMdLitDadoInteressado();
    $objMdLitRelNumInterAbrangDTO->retNumIdMdLitAbrangencia();
    $objMdLitRelNumInterAbrangDTO->retNumIdMdLitDadoInteressado();

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitRelNumInterAbrangDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    if ($numIdMdLitAbrangencia!==''){
      $objMdLitRelNumInterAbrangDTO->setNumIdMdLitAbrangencia($numIdMdLitAbrangencia);
    }

    $objMdLitRelNumInterAbrangDTO->setOrdNumIdMdLitDadoInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelNumInterAbrangRN = new MdLitRelNumInterAbrangRN();
    $arrObjMdLitRelNumInterAbrangDTO = $objMdLitRelNumInterAbrangRN->listar($objMdLitRelNumInterAbrangDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelNumInterAbrangDTO, array('IdMdLitDadoInteressado','IdMdLitAbrangencia'), 'IdMdLitDadoInteressado');
  }
}
?>