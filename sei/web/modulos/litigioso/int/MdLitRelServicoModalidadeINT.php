<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelServicoModalidadeINT extends InfraINT {

  public static function montarSelectIdMdLitModalidade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitServico='', $numIdMdLitModalidade=''){
    $objMdLitRelServicoModalidadeDTO = new MdLitRelServicoModalidadeDTO();
    $objMdLitRelServicoModalidadeDTO->retNumIdMdLitServico();
    $objMdLitRelServicoModalidadeDTO->retNumIdMdLitModalidade();
    $objMdLitRelServicoModalidadeDTO->retNumIdMdLitModalidade();

    if ($numIdMdLitServico!==''){
      $objMdLitRelServicoModalidadeDTO->setNumIdMdLitServico($numIdMdLitServico);
    }

    if ($numIdMdLitModalidade!==''){
      $objMdLitRelServicoModalidadeDTO->setNumIdMdLitModalidade($numIdMdLitModalidade);
    }

    $objMdLitRelServicoModalidadeDTO->setOrdNumIdMdLitModalidade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelServicoModalidadeRN = new MdLitRelServicoModalidadeRN();
    $arrObjMdLitRelServicoModalidadeDTO = $objMdLitRelServicoModalidadeRN->listar($objMdLitRelServicoModalidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelServicoModalidadeDTO, 'array('IdMdLitServico','IdMdLitModalidade')', 'IdMdLitModalidade');
  }
}
?>