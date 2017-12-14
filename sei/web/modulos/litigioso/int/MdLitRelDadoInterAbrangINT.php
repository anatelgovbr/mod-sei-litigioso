<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class MdLitRelDadoInterAbrangINT extends InfraINT {

  public static function montarSelectIdMdLitDadoInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDadoInteressado='', $numIdMdLitAbrangencia=''){
    $objMdLitRelDadoInterAbrangDTO = new MdLitRelDadoInterAbrangDTO();
    $objMdLitRelDadoInterAbrangDTO->retNumIdMdLitDadoInteressado();
    $objMdLitRelDadoInterAbrangDTO->retNumIdMdLitAbrangencia();
    $objMdLitRelDadoInterAbrangDTO->retNumIdMdLitDadoInteressado();

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitRelDadoInterAbrangDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    if ($numIdMdLitAbrangencia!==''){
      $objMdLitRelDadoInterAbrangDTO->setNumIdMdLitAbrangencia($numIdMdLitAbrangencia);
    }

    $objMdLitRelDadoInterAbrangDTO->setOrdNumIdMdLitDadoInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelDadoInterAbrangRN = new MdLitRelDadoInterAbrangRN();
    $arrObjMdLitRelDadoInterAbrangDTO = $objMdLitRelDadoInterAbrangRN->listar($objMdLitRelDadoInterAbrangDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelDadoInterAbrangDTO, 'array('IdMdLitDadoInteressado','IdMdLitAbrangencia')', 'IdMdLitDadoInteressado');
  }
}
?>