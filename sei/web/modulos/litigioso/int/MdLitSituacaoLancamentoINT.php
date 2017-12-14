<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitSituacaoLancamentoINT extends InfraINT {

  public static function montarSelectIdMdLitSituacaoLancamento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
    $objMdLitSituacaoLancamentoDTO->retNumIdMdLitSituacaoLancamento();
    $objMdLitSituacaoLancamentoDTO->retNumIdMdLitSituacaoLancamento();

    $objMdLitSituacaoLancamentoDTO->setOrdNumIdMdLitSituacaoLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
    $arrObjMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->listar($objMdLitSituacaoLancamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitSituacaoLancamentoDTO, 'IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento');
  }
}
?>