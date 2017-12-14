<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterServicoINT extends InfraINT {

  public static function montarSelectIdMdLitDadoInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDadoInteressado='', $numIdMdLitServico=''){
    $objMdLitRelDadoInterServicoDTO = new MdLitRelDadoInterServicoDTO();
    $objMdLitRelDadoInterServicoDTO->retNumIdMdLitDadoInteressado();
    $objMdLitRelDadoInterServicoDTO->retNumIdMdLitServico();
    $objMdLitRelDadoInterServicoDTO->retNumIdMdLitDadoInteressado();

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitRelDadoInterServicoDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    if ($numIdMdLitServico!==''){
      $objMdLitRelDadoInterServicoDTO->setNumIdMdLitServico($numIdMdLitServico);
    }

    $objMdLitRelDadoInterServicoDTO->setOrdNumIdMdLitDadoInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelDadoInterServicoRN = new MdLitRelDadoInterServicoRN();
    $arrObjMdLitRelDadoInterServicoDTO = $objMdLitRelDadoInterServicoRN->listar($objMdLitRelDadoInterServicoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelDadoInterServicoDTO, 'array('IdMdLitDadoInteressado','IdMdLitServico')', 'IdMdLitDadoInteressado');
  }
}
?>