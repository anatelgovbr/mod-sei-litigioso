<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 05/09/2018 - criado por ellyson.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRelDecisaoUfINT extends InfraINT {

  public static function montarSelectIdMdLitDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDecisao='', $numIdUf=''){
    $objMdLitRelDecisaoUfDTO = new MdLitRelDecisaoUfDTO();
    $objMdLitRelDecisaoUfDTO->retNumIdMdLitDecisao();
    $objMdLitRelDecisaoUfDTO->retNumIdUf();
    $objMdLitRelDecisaoUfDTO->retNumIdMdLitDecisao();

    if ($numIdMdLitDecisao!==''){
      $objMdLitRelDecisaoUfDTO->setNumIdMdLitDecisao($numIdMdLitDecisao);
    }

    if ($numIdUf!==''){
      $objMdLitRelDecisaoUfDTO->setNumIdUf($numIdUf);
    }

    $objMdLitRelDecisaoUfDTO->setOrdNumIdMdLitDecisao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelDecisaoUfRN = new MdLitRelDecisaoUfRN();
    $arrObjMdLitRelDecisaoUfDTO = $objMdLitRelDecisaoUfRN->listar($objMdLitRelDecisaoUfDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelDecisaoUfDTO, array('IdMdLitDecisao','IdUf'), 'IdMdLitDecisao');
  }
}
