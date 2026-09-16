<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitCancelaLancamentoINT extends InfraINT {

  public static function montarSelectIdMdLitCancelaLancamento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitLancamento=''){
    $objMdLitCancelaLancamentoDTO = new MdLitCancelaLancamentoDTO();
    $objMdLitCancelaLancamentoDTO->retNumIdMdLitCancelaLancamento();
    $objMdLitCancelaLancamentoDTO->retNumIdMdLitCancelaLancamento();

    if ($numIdMdLitLancamento!==''){
      $objMdLitCancelaLancamentoDTO->setNumIdMdLitLancamento($numIdMdLitLancamento);
    }

    $objMdLitCancelaLancamentoDTO->setOrdNumIdMdLitCancelaLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitCancelaLancamentoRN = new MdLitCancelaLancamentoRN();
    $arrObjMdLitCancelaLancamentoDTO = $objMdLitCancelaLancamentoRN->listar($objMdLitCancelaLancamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitCancelaLancamentoDTO, 'IdMdLitCancelaLancamento', 'IdMdLitCancelaLancamento');
  }

  public static function montarSelectMotivoCancelarLancamento(){
      $objMdLitCancelaLancamentoRN = new MdLitCancelaLancamentoRN();

      $arrMotivoCancelar = $objMdLitCancelaLancamentoRN->montarMotivoCancelamento();
    
      $strRet = "<option value='null'></option>\n";
      if (count($arrMotivoCancelar)>0){

          foreach($arrMotivoCancelar as $key => $motivoCancelar){
              $strRet .= "<option value='{$motivoCancelar['id_motivo']}'>{$motivoCancelar['descricao']}</option>\n";
          }
      }
      return $strRet;
  }
}
?>