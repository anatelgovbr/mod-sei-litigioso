<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelServicoAbrangenINT extends InfraINT {

  public static function montarSelectIdMdLitAbrangencia($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitServico='', $numIdMdLitAbrangencia=''){
    $objMdLitRelServicoAbrangenDTO = new MdLitRelServicoAbrangenDTO();
    $objMdLitRelServicoAbrangenDTO->retNumIdMdLitServico();
    $objMdLitRelServicoAbrangenDTO->retNumIdMdLitAbrangencia();
    $objMdLitRelServicoAbrangenDTO->retNumIdMdLitAbrangencia();

    if ($numIdMdLitServico!==''){
      $objMdLitRelServicoAbrangenDTO->setNumIdMdLitServico($numIdMdLitServico);
    }

    if ($numIdMdLitAbrangencia!==''){
      $objMdLitRelServicoAbrangenDTO->setNumIdMdLitAbrangencia($numIdMdLitAbrangencia);
    }

    $objMdLitRelServicoAbrangenDTO->setOrdNumIdMdLitAbrangencia(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelServicoAbrangenRN = new MdLitRelServicoAbrangenRN();
    $arrObjMdLitRelServicoAbrangenDTO = $objMdLitRelServicoAbrangenRN->listar($objMdLitRelServicoAbrangenDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelServicoAbrangenDTO, 'array('IdMdLitServico','IdMdLitAbrangencia')', 'IdMdLitAbrangencia');
  }
}
?>