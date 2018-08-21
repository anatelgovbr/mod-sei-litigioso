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


    public static function montarRadioCancelamento($sinCancelamento)
    {
        $sinCancelamento = $sinCancelamento? $sinCancelamento : 'N';
        $arrRadio = array('S'=>'Sim', 'N'=>'Nao');

        $strRadio = '<div>';

        foreach ($arrRadio as $strValue=>$strNome) {
            $strChecked = $strValue == $sinCancelamento ? "checked='checked'" : '';
            $strRadio .= '<label class="radio-label infraLabelRadio" for="rdoSinCancelamento_' . $strValue . '">';
            $strRadio .= '<input type ="radio" name="rdoSinCancelamento" value="' . $strValue . '" id="rdoSinCancelamento_' . $strValue . '"' . $strChecked . '  />';
            $strRadio .= $strNome . '</label>';
        }
        $strRadio .= '</div>';
        return $strRadio;
    }

    public static function montarSelectCor($strValorItemSelecionado = 'black'){
        $arrCores = array('blue'=>'Azul', 'black'=>'Preto', 'green'=>'Verde', 'red'=>'Vermelho');
        return parent::montarSelectArray('&nbsp','&nbsp;',$strValorItemSelecionado, $arrCores);
    }
}
?>