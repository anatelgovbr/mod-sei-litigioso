<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRelDecisLancamentINT extends InfraINT {

  public static function montarSelectIdMdLitDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDecisao=''){
    $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
    $objMdLitRelDecisLancamentDTO->retNumIdMdLitDecisao();
    $objMdLitRelDecisLancamentDTO->retNumIdMdLitLancamento();
    $objMdLitRelDecisLancamentDTO->retNumIdMdLitDecisao();

    if ($numIdMdLitDecisao!==''){
      $objMdLitRelDecisLancamentDTO->setNumIdMdLitDecisao($numIdMdLitDecisao);
    }

    $objMdLitRelDecisLancamentDTO->setOrdNumIdMdLitDecisao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();
    $arrObjMdLitRelDecisLancamentDTO = $objMdLitRelDecisLancamentRN->listar($objMdLitRelDecisLancamentDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelDecisLancamentDTO, 'array("IdMdLitDecisao","IdMdLitLancamento")', 'IdMdLitDecisao');
  }
}
?>