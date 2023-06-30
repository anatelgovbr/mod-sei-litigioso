<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitHistoricLancamentoINT extends InfraINT {

  public static function montarSelectIdMdLitHistoricLancamento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitSituacaoLancamento='', $numIdMdLitLancamento='', $numIdUsuario='', $numIdUnidade=''){
    $objMdLitHistoricLancamentoDTO = new MdLitHistoricLancamentoDTO();
    $objMdLitHistoricLancamentoDTO->retNumIdMdLitHistoricLancamento();
    $objMdLitHistoricLancamentoDTO->retNumIdMdLitHistoricLancamento();

    if ($numIdMdLitSituacaoLancamento!==''){
      $objMdLitHistoricLancamentoDTO->setNumIdMdLitSituacaoLancamento($numIdMdLitSituacaoLancamento);
    }

    if ($numIdMdLitLancamento!==''){
      $objMdLitHistoricLancamentoDTO->setNumIdMdLitLancamento($numIdMdLitLancamento);
    }

    if ($numIdUsuario!==''){
      $objMdLitHistoricLancamentoDTO->setNumIdUsuario($numIdUsuario);
    }

    if ($numIdUnidade!==''){
      $objMdLitHistoricLancamentoDTO->setNumIdUnidade($numIdUnidade);
    }

    $objMdLitHistoricLancamentoDTO->setOrdNumIdMdLitHistoricLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitHistoricLancamentoRN = new MdLitHistoricLancamentoRN();
    $arrObjMdLitHistoricLancamentoDTO = $objMdLitHistoricLancamentoRN->listar($objMdLitHistoricLancamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitHistoricLancamentoDTO, 'IdMdLitHistoricLancamento', 'IdMdLitHistoricLancamento');
  }
}
?>