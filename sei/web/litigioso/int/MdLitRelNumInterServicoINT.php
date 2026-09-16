<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterServicoINT extends InfraINT {

  public static function montarSelectIdMdLitDadoInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDadoInteressado='', $numIdMdLitServico=''){
    $objMdLitRelNumInterServicoDTO = new MdLitRelNumInterServicoDTO();
    $objMdLitRelNumInterServicoDTO->retNumIdMdLitNumeroInteressado();
    $objMdLitRelNumInterServicoDTO->retNumIdMdLitServico();
    $objMdLitRelNumInterServicoDTO->retNumIdMdLitDadoInteressado();

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitRelNumInterServicoDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    if ($numIdMdLitServico!==''){
      $objMdLitRelNumInterServicoDTO->setNumIdMdLitServico($numIdMdLitServico);
    }

    $objMdLitRelNumInterServicoDTO->setOrdNumIdMdLitDadoInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelNumInterServicoRN = new MdLitRelNumInterServicoRN();
    $arrObjMdLitRelNumInterServicoDTO = $objMdLitRelNumInterServicoRN->listar($objMdLitRelNumInterServicoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelNumInterServicoDTO, array('IdMdLitDadoInteressado','IdMdLitServico'), 'IdMdLitDadoInteressado');
  }
}
?>