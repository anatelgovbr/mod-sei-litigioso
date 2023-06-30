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
    public static function montarTabelaParamSaida(MdLitSoapClienteRN $objMdLitSoapClienteRN, $idMdLitFuncionalidade, $operacao, $idMdLitIntegracao, MdLitIntegracaoDTO $objMdLitIntegracaoDTO = null){
        $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();
        $versaoSoap = $_POST['versaoSoap'] ?: $objMdLitIntegracaoDTO->getStrVersaoSoap();
        $objMdLitSoapClienteRN->setSoapVersion($versaoSoap);
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

            $strResultadoParamSaida .= '<table width="100%" id="tableParametroSaida" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultadoParamSaida .= '<tr>';
            $strResultadoParamSaida .= '<th class="infraTh" width="35%" style="min-width: 402px">&nbsp;Campo de Destino no SEI&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="45%" style="min-width: 261px">&nbsp;Dados de Saída no Webservice&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="15%">&nbsp;Chave Única da Integração&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '</tr>' . "\n";
            $strCssTr = '';

            if ($idMdLitFuncionalidade == 1) {
                $strItensSelNomeFuncional = MdLitNomeFuncionalINT::montarSelectNome('null', '&nbsp;', null);
            }else{
                $strItensSelNomeFuncional = MdLitCampoIntegracaoINT::montarSelectCampoIntergracao('null', '&nbsp;', $idMdLitFuncionalidade, 'S');
            }

            $i = 0;
            foreach ($strItensSelNomeFuncional as $campoOrigemSaida) {
                $saidaParametroSalvo = null;
                $chaveUnica = null;
                $campoOrigemSaidaIdMdLitId = null;
                $campoOrigemSaidaIdMdLitCampo = null;

                if($idMdLitFuncionalidade == 1) {
                    $campoOrigemSaidaIdMdLitId = $campoOrigemSaida->get("IdMdLitNomeFuncional");
                    $campoOrigemSaidaIdMdLitCampo = $campoOrigemSaida->get("Nome");
                } else {
                    $campoOrigemSaidaIdMdLitId = $campoOrigemSaida->get("IdMdLitCampoIntegracao");
                    $campoOrigemSaidaIdMdLitCampo = $campoOrigemSaida->get("NomeCampo");
                }

                foreach($arrObjMdLitMapearParamSaidaDTO as $parametrosSalvos) {
                    $parametrosSalvosIdMdLitCampo = null;
                    $parametrosSalvosIdMdLitCampo = $parametrosSalvos->get("Campo");
                    if($idMdLitFuncionalidade == 1) {
                        $parametrosSalvosIdMdLitId = $parametrosSalvos->get("IdMdLitNomeFuncional");
                    } else {
                        $parametrosSalvosIdMdLitId = $parametrosSalvos->get("IdMdLitCampoIntegracao");
                    }

                    if($parametrosSalvosIdMdLitCampo) {
                        if($campoOrigemSaidaIdMdLitId == $parametrosSalvosIdMdLitId) {
                            $saidaParametroSalvo = $parametrosSalvos->get("Campo");
                        }
                    }
                    if($parametrosSalvos->get('ChaveUnica') == "S") {
                        $chaveUnica = $parametrosSalvosIdMdLitId;
                    }
                }
                $strItensSelCampoDestino = MdLitMapearParamSaidaINT::montarSelectCampoDestino($arrParametroSaida, $saidaParametroSalvo);

                $idLinha = $i;

                $strCssTr = '<tr id="paramSaidaTable_' . $idLinha . '" class="infraTrClara">';

                if($campoOrigemSaidaIdMdLitId == $chaveUnica) {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
                if($saidaParametroSalvo == null) {
                    $disable = 'disabled="disabled"';
                } else {
                    $disable = '';
                }

                $class = '';
                if(in_array($campoOrigemSaida->getNumIdMdLitCampoIntegracao(), MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO_CAMPOS_OBRIGRATORIOS)){
                    $class = '" class="font-weight-bold"';
                }

                $strResultadoParamSaida .= $strCssTr;
                $strResultadoParamSaida .= "<td id='campo_$idLinha>";
                $strResultadoParamSaida .= "<input type='hidden' name='hdnArrayDadosSaida[$i]' value='{$arrParametroSaida[$campoOrigemSaidaIdMdLitId]}' />";
                $strResultadoParamSaida .= "<label ".$class.">".PaginaSEI::tratarHTML($campoOrigemSaidaIdMdLitCampo)."</label>";
                $strResultadoParamSaida .= "</td>";
                $strResultadoParamSaida .= "<td align='center'><select class='infraSelect form-control' id='nomeFuncionalDadosSaida_$idLinha' name='nomeFuncionalDadosSaida[".$campoOrigemSaidaIdMdLitId."]' onchange='mudarNomeFuncionalDadosSaida(this)' style='width: 80%;'>{$strItensSelCampoDestino}</select></td>";
                $strResultadoParamSaida .= "<td align='center'><input type='radio' class='infraRadio' name='chaveUnicaDadosSaida' value='".$campoOrigemSaidaIdMdLitId."' $checked id='chaveUnicaDadosSaida_{$idLinha}' $disable> </td>";

                $strResultadoParamSaida .= '</tr>' . "\n";
                $i++;
            }
        }
        $strResultadoParamSaida .= '</table>';

        return array('strResultado' => $strResultadoParamSaida,'numRegistros'=> $numRegistrosParametroSaida );
    }
    public static function montarSelectCampoDestino($arrParametroSaida, $saidaParametroSalvo)
        {
            ksort($arrParametroSaida);
            $chaveUnica = false;
            $str = '<option  value="null">&nbsp;</option>';
            foreach ($arrParametroSaida as $key=>$arrayParam) {
                  if(is_array($arrayParam)) {
                        ksort($arrayParam);
                        foreach ($arrayParam as $chave => $item) {
                            if(is_array($item)){
                                foreach($item as $chaveItem => $value) {
                                    $chaveFormatada = $key . " - " . $chave . " - " . $chaveItem;
                                    $value = $chave . " - " . $chaveItem;
                                }
                            } else {
                                $chaveFormatada = $key . " - " . $item;
                                $value = $item;
                            }
                            $selected = $saidaParametroSalvo == $value ? 'selected=selected' : '';
                            $str .= '<option '.$selected.' value="'.$value.'"> '.$chaveFormatada.' </option>';
                      }
                    } else {
                        $selected = $saidaParametroSalvo == $arrayParam ? 'selected=selected' : '';
                        $str .= '<option '.$selected.' value="'.$arrayParam.'"> '.$arrayParam.' </option>';
                    }
                }
            return $str;
        }
}
?>
