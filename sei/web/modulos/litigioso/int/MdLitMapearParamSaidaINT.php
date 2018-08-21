<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMapearParamSaidaINT extends InfraINT {

  public static function montarSelectIdMdLitIntegracao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitIntegracao='', $numIdMdLitNomeFuncional=''){
    $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();
    $objMdLitMapearParamSaidaDTO->retNumIdMdLitMapearParamSaida();
    $objMdLitMapearParamSaidaDTO->retNumIdMdLitIntegracao();

    if ($numIdMdLitIntegracao!==''){
      $objMdLitMapearParamSaidaDTO->setNumIdMdLitIntegracao($numIdMdLitIntegracao);
    }

    if ($numIdMdLitNomeFuncional!==''){
      $objMdLitMapearParamSaidaDTO->setNumIdMdLitNomeFuncional($numIdMdLitNomeFuncional);
    }

    $objMdLitMapearParamSaidaDTO->setOrdNumIdMdLitIntegracao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitMapearParamSaidaRN = new MdLitMapearParamSaidaRN();
    $arrObjMdLitMapearParamSaidaDTO = $objMdLitMapearParamSaidaRN->listar($objMdLitMapearParamSaidaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitMapearParamSaidaDTO, 'IdMdLitMapearParamSaida', 'IdMdLitIntegracao');
  }
    public static function montarTabelaParamSaida(MdLitSoapClienteRN $objMdLitSoapClienteRN, $idMdLitFuncionalidade, $operacao, $idMdLitIntegracao){
        $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();
        $arrParametroSaida = $objMdLitSoapClienteRN->getParamsOutput($operacao);
        $strResultadoParamSaida = '';
        $arrObjMdLitMapearParamSaidaDTO = array();

        if(!empty($idMdLitIntegracao)){
            $objMdLitMapearParamSaidaRN = new MdLitMapearParamSaidaRN();
            $objMdLitMapearParamSaidaDTO->retTodos();

            $objMdLitMapearParamSaidaDTO->setNumIdMdLitIntegracao($idMdLitIntegracao);
            $objMdLitMapearParamSaidaDTO->setOrdStrCampo(InfraDTO::$TIPO_ORDENACAO_ASC);

            $arrObjMdLitMapearParamSaidaDTO = $objMdLitMapearParamSaidaRN->listar($objMdLitMapearParamSaidaDTO);
        }

        //tabela de dados de saída
        $numRegistrosParametroSaida = count($arrParametroSaida);
        if($numRegistrosParametroSaida > 0){
            $strSumarioTabela = 'Tabela de configuração dos dados de saída do web-service.';
            $strCaptionTabela = 'Dados de saída';

            $strResultadoParamSaida .= '<table width="90%" id="tableParametroSaida" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultadoParamSaida .= '<tr>';

            $strResultadoParamSaida .= '<th class="infraTh" width="20%">&nbsp;Dados de Saída no Webservice&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="20%">&nbsp;Campo de Destino no SEI&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="5%">&nbsp;Chave Única da Integração&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '</tr>' . "\n";
            $strCssTr = '';

            for ($i = 0; $i < $numRegistrosParametroSaida; $i++) {
                if ($idMdLitFuncionalidade == 1) {
                    $strItensSelNomeFuncional = MdLitNomeFuncionalINT::montarSelectNome('null', '&nbsp;', null);
                }else{
                    $strItensSelNomeFuncional = MdLitCampoIntegracaoINT::montarSelectCampoIntergracao('null', '&nbsp;', $idMdLitFuncionalidade, 'S');
                }

                $idLinha = $i;

                $strCssTr = '<tr id="paramSaidaTable_' . $idLinha . '" class="infraTrClara">';
                $disable = 'disabled="disabled"';
                $checked = '';

                if(count($arrObjMdLitMapearParamSaidaDTO)> 0){
                    for ($j = 0; $j < count($arrObjMdLitMapearParamSaidaDTO); $j++){
                        if($arrObjMdLitMapearParamSaidaDTO[$j]->getStrCampo() == $arrParametroSaida[$i] ){
                            $disable = '';
                            if ($idMdLitFuncionalidade == 1) {
                                $strItensSelNomeFuncional = MdLitNomeFuncionalINT::montarSelectNome('null', '&nbsp;', $arrObjMdLitMapearParamSaidaDTO[$j]->getNumIdMdLitNomeFuncional());
                            }else{
                                $strItensSelNomeFuncional = MdLitCampoIntegracaoINT::montarSelectCampoIntergracao('null', '&nbsp;', $idMdLitFuncionalidade, 'S', $arrObjMdLitMapearParamSaidaDTO[$j]->getNumIdMdLitCampoIntegracao());
                            }
                            $checked = $arrObjMdLitMapearParamSaidaDTO[$j]->getStrChaveUnica() == 'S'?'checked="checked"': '';
                        }
                    }
                }

                $strResultadoParamSaida .= $strCssTr;
                $strResultadoParamSaida .= "<td id='campo_$idLinha>";
                $strResultadoParamSaida .= "<input type='hidden' name='hdnArrayDadosSaida[$i]' value='{$arrParametroSaida[$i]}' />";
                $strResultadoParamSaida .= PaginaSEI::tratarHTML($arrParametroSaida[$i]);
                $strResultadoParamSaida .= "</td>";
                $strResultadoParamSaida .= "<td align='center'><select id='nomeFuncionalDadosSaida_$idLinha' name='nomeFuncionalDadosSaida[$arrParametroSaida[$i]]' onchange='mudarNomeFuncionalDadosSaida(this)' style='width: 80%;'>{$strItensSelNomeFuncional}</select></td>";
                $strResultadoParamSaida .= "<td align='center'><input type='radio'name='chaveUnicaDadosSaida' value='{$arrParametroSaida[$i]}' $checked id='chaveUnicaDadosSaida_{$idLinha}' $disable> </td>";

                $strResultadoParamSaida .= '</tr>' . "\n";
            }
            $strResultadoParamSaida .= '</table>';
        }

        return array('strResultado' => $strResultadoParamSaida,'numRegistros'=> $numRegistrosParametroSaida );
    }
}
?>