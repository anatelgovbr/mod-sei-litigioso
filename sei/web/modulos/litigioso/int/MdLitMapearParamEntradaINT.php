<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMapearParamEntradaINT extends InfraINT {

  public static function montarSelectIdMdLitIntegracao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitIntegracao='', $numIdMdLitNomeFuncional=''){
    $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();
    $objMdLitMapearParamEntradaDTO->retNumIdMdLitMapearParamEntrada();
    $objMdLitMapearParamEntradaDTO->retNumIdMdLitIntegracao();

    if ($numIdMdLitIntegracao!==''){
      $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($numIdMdLitIntegracao);
    }

    if ($numIdMdLitNomeFuncional!==''){
      $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional($numIdMdLitNomeFuncional);
    }

    $objMdLitMapearParamEntradaDTO->setOrdNumIdMdLitIntegracao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
    $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitMapearParamEntradaDTO, 'IdMdLitMapearParamEntrada', 'IdMdLitIntegracao');
  }

  public static function montarTabelaParamEntrada($objMdLitSoapClienteRN, $idMdLitFuncionalidade, $operacao, $idMdLitIntegracao = null){
      $objMdLitMapearParamEntradaDTO    = new MdLitMapearParamEntradaDTO();
      $arrParametroEntrada              = $objMdLitSoapClienteRN->getParamsInput($operacao);

        foreach ($arrParametroEntrada as $indice=>$valor) {
            if(is_array($valor)) {
                ksort($valor);
                foreach ($valor as $chave => $item) {
                    if(is_array($item)){
                        foreach($item as $chaveItem => $value) {
                            $parametroEntradaCorreto[$chaveItem] = $indice . " - " . $chave . " - " . $chaveItem;
                        }
                    } else {
                        $parametroEntradaCorreto[$chave] = $indice." - ".$item;
                    }
                }
            } else {
                $parametroEntradaCorreto[$valor] = $valor;
            }
        }

      $strResultadoParamEntrada         = '';
      $arrObjMdLitMapearParamEntradaDTO = array();

      if(!empty($idMdLitIntegracao)){
          $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
          $objMdLitMapearParamEntradaDTO->retTodos();

          $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($idMdLitIntegracao);
          $objMdLitMapearParamEntradaDTO->setOrdStrCampo(InfraDTO::$TIPO_ORDENACAO_ASC);

          $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);
      }

        $numRegistrosParametroEntrada = count($arrParametroEntrada);
        if($numRegistrosParametroEntrada > 0){
            $strSumarioTabela = 'Tabela de configura��o dos dados de entrada do web-service.';
            $strCaptionTabela = 'Dados de entrada';

            $strResultadoParamEntrada .= '<table width="100%" id="tableParametroEntrada" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultadoParamEntrada .= '<tr>';
            $strResultadoParamEntrada .= '<th class="infraTh" width="35%" style="min-width: 402px">&nbsp;Campo de Origem no SEI&nbsp;</th>' . "\n";
            $strResultadoParamEntrada .= '<th class="infraTh" width="45%" style="min-width: 261px">&nbsp;Dados de Entrada no Webservice&nbsp;</th>' . "\n";

            $strResultadoParamEntrada .= '<th class="infraTh" width="15%">&nbsp;Chave �nica da Integra��o&nbsp;</th>' . "\n";
            $strResultadoParamEntrada .= '</tr>' . "\n";
            $strCssTr = '';
            if($idMdLitFuncionalidade == 1) {
                $strItensSelNomeFuncional = MdLitNomeFuncionalINT::montarSelectNome('null', '&nbsp;', null);
            }else{
                $strItensSelNomeFuncional = MdLitCampoIntegracaoINT::montarSelectCampoIntergracao('null', '&nbsp;', $idMdLitFuncionalidade, 'E');
            }
            $i = 0;
            foreach ($strItensSelNomeFuncional as $campoOrigemEntrada) {
                $tabelaCodigoReceita = '';
                $entradaParametroSalvo = null;
                $chaveUnica = null;
                $campoOrigemEntradaIdMdLitId = null;
                $campoOrigemEntradaIdMdLitCampo = null;
                if($idMdLitFuncionalidade == 1) {
                    $campoOrigemEntradaIdMdLitId = $campoOrigemEntrada->get("IdMdLitNomeFuncional");
                    $campoOrigemEntradaIdMdLitCampo = $campoOrigemEntrada->get("Nome");

                } else {
                    $campoOrigemEntradaIdMdLitId = $campoOrigemEntrada->get("IdMdLitCampoIntegracao");
                    $campoOrigemEntradaIdMdLitCampo = $campoOrigemEntrada->get("NomeCampo");
                }

                foreach($arrObjMdLitMapearParamEntradaDTO as $parametrosSalvos) {
                    $parametrosSalvosIdMdLitId = null;

                    if($idMdLitFuncionalidade == 1) {
                        $parametrosSalvosIdMdLitId = $parametrosSalvos->get("IdMdLitNomeFuncional");
                    } else {
                        $parametrosSalvosIdMdLitId = $parametrosSalvos->get("IdMdLitCampoIntegracao");
                    }
                    if($parametrosSalvosIdMdLitId) {
                        if($parametrosSalvosIdMdLitId == $campoOrigemEntradaIdMdLitId) {
                            $entradaParametroSalvo = $parametrosSalvos->get("Campo");
                            if(($campoOrigemEntradaIdMdLitId == 5) && ($campoOrigemEntradaIdMdLitCampo == "C�digo da Receita")) {
                                $tabelaCodigoReceita = MdLitIntegracaoINT::montarTabelaCodigoReceita($parametrosSalvos->get("IdMdLitMapearParamEntrada"));
                            }
                        }
                    }
                    if($parametrosSalvos->get('ChaveUnica') == "S") {
                        $chaveUnica = $parametrosSalvosIdMdLitId;
                    }
                }

                $itensSelectParamEntrada = MdLitCampoIntegracaoINT::montarSelectArray('null','&nbsp;', $entradaParametroSalvo, $parametroEntradaCorreto);
                $strCssTr = '<tr id="paramEntradaTable_' . $i . '" class="infraTrClara">';

                if($campoOrigemEntradaIdMdLitId == $chaveUnica) {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
                if($entradaParametroSalvo == null) {
                    $disable = 'disabled="disabled"';
                } else {
                    $disable = '';
                }

                $class = '';
                if(in_array($campoOrigemEntrada->getNumIdMdLitCampoIntegracao(), MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO_CAMPOS_OBRIGRATORIOS)){
                    $class = '" class="font-weight-bold"';
                }

                $strResultadoParamEntrada .= $strCssTr;
                $strResultadoParamEntrada .= "<td id='campo_".$i."'>";
                $strResultadoParamEntrada .= "<input type='hidden' name='hdnArrayDadosEntrada[".$i."]' value='{$arrParametroEntrada[$campoOrigemEntradaIdMdLitId]}' />";
                $strResultadoParamEntrada .= "<label ".$class.">".PaginaSEI::tratarHTML($campoOrigemEntradaIdMdLitCampo)."</label>";
                $strResultadoParamEntrada.= $tabelaCodigoReceita."</td>";
                $strResultadoParamEntrada .= "<td align='center'><select id='nomeFuncionalDadosEntrada_".$i."' class='infraSelect form-control' name='nomeFuncionalDadosEntrada[".$campoOrigemEntradaIdMdLitId."]' onchange='mudarNomeFuncionalDadosEntrada(this), verificarCodigoReceita(this)' style='width: 80%;'>{$itensSelectParamEntrada}</select>";
                $strResultadoParamEntrada .= "</td>";
                $strResultadoParamEntrada .= "<td align='center'><input type='radio' class='infraRadio' name='chaveUnicaDadosEntrada' id='chaveUnicaDadosEntrada_".$i."' value='".$campoOrigemEntradaIdMdLitId."' $disable $checked> </td>";

                $strResultadoParamEntrada .= '</tr>' . "\n";
                $i++;
            }
          $strResultadoParamEntrada .= '</table>';
      }

      return array('strResultado' => $strResultadoParamEntrada,'numRegistros'=> $numRegistrosParametroEntrada );
  }

}
?>
