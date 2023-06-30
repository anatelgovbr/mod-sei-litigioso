<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitSituacaoLancamIntINT extends InfraINT {

    public static function montarSelectNomeIntegracao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
        $objMdLitSituacaoLancamIntDTO = new MdLitSituacaoLancamIntDTO();
        $objMdLitSituacaoLancamIntDTO->retNumIdMdLitSituacaoLancamInt();
        $objMdLitSituacaoLancamIntDTO->retStrNomeIntegracao();

        $objMdLitSituacaoLancamIntDTO->setOrdStrNomeIntegracao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdLitSituacaoLancamIntRN = new MdLitSituacaoLancamIntRN();
        $arrObjMdLitSituacaoLancamIntDTO = $objMdLitSituacaoLancamIntRN->listar($objMdLitSituacaoLancamIntDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitSituacaoLancamIntDTO, 'IdMdLitSituacaoLancamInt', 'NomeIntegracao');
    }

    public static function montarSelectCampoDestino($arrParametroSaida, $objMdLitSituacaoLancamIntDTO, $itemSei)
    {
        $chaveUnica = false;
        $str = '<option  value=""> </option>';
        foreach ($arrParametroSaida as $key=>$arrayParam) {
            if(is_array($arrayParam)) {
                ksort($arrayParam);
                foreach ($arrayParam as $chave => $item) {
                    $codigoSelect = "";
                    if($objMdLitSituacaoLancamIntDTO) {
                        if(MdLitServicoIntegracaoRN::$CODIGO == $itemSei)
                            $codigoSelect = $objMdLitSituacaoLancamIntDTO->getStrMapeamentoCodigo();
                        elseif(MdLitServicoIntegracaoRN::$DESCRICAO == $itemSei)
                            $codigoSelect = $objMdLitSituacaoLancamIntDTO->getStrMapeamentoDescricao();

                        if($objMdLitSituacaoLancamIntDTO->getStrChaveUnica() == $codigoSelect) {
                            $chaveUnica = true;
                        }
                    }
                    if(is_array($item)){
                        foreach($item as $chaveItem => $valor) {
                            $chaveFormatada = $key . " - " . $chave . " - " . $chaveItem;
                            $value = $chave . " - " . $chaveItem;
                            $selected = $codigoSelect == $value ? 'selected=selected' : '';
                            $str .= '<option '.$selected.' value="'.$value.'"> '.$chaveFormatada.' </option>';
                        }
                    } else {
                        $chaveFormatada = $key . " - " . $item;
                        $value = $item;
                        $selected = $codigoSelect == $value ? 'selected=selected' : '';
                        $str .= '<option '.$selected.' value="'.$value.'"> '.$chaveFormatada.' </option>';
                    }
                }
            } else {
                $codigoSelect = "";
                if($objMdLitSituacaoLancamIntDTO) {
                    if(MdLitServicoIntegracaoRN::$CODIGO == $itemSei)
                        $codigoSelect = $objMdLitSituacaoLancamIntDTO->getStrMapeamentoCodigo();
                    elseif(MdLitServicoIntegracaoRN::$DESCRICAO == $itemSei)
                        $codigoSelect = $objMdLitSituacaoLancamIntDTO->getStrMapeamentoDescricao();

                    if($objMdLitSituacaoLancamIntDTO->getStrChaveUnica() == $codigoSelect) {
                        $chaveUnica = true;
                    }
                }
                $selected = $codigoSelect == $arrayParam ? 'selected=selected' : '';
                $str .= '<option '.$selected.' value="'.$arrayParam.'"> '.$arrayParam.' </option>';
            }
        }

        return array('select' => $str,'chaveUnica'=> $chaveUnica );
    }

    public static function montarTabelaParamSaida(MdLitSoapClienteRN $objMdLitSoapClienteRN, $operacao, $objMdLitSituacaoLancamIntDTO = null){
        $versaoSoap = $_POST['versaoSoap'] ?: $objMdLitSituacaoLancamIntDTO->getStrVersaoSoap();
        $objMdLitSoapClienteRN->setSoapVersion($versaoSoap);
        $arrParametroSaida = $objMdLitSoapClienteRN->getParamsOutput($operacao);
        $strResultadoParamSaida = '';

        //tabela de dados de sa�da
        $numRegistrosParametroSaida = count($arrParametroSaida);
        if($numRegistrosParametroSaida > 0){
            $strSumarioTabela = 'Tabela de configura��o dos dados de sa�da do web-service.';
            $strCaptionTabela = 'Dados de sa�da';

            $strResultadoParamSaida .= '<table width="100%" id="tableParametroSaida" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultadoParamSaida .= '<tr>';
            $strResultadoParamSaida .= '<th class="infraTh" width="40%">&nbsp;Campo de destino no SEI&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="50%">&nbsp;Dados de sa�da&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="10%">&nbsp;Chave �nica da integra��o&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '</tr>' . "\n";
            $strCssTr = '';

            $arrDados =[
                'C�digo' => MdLitSituacaoLancamIntRN::$CODIGO,
                'Descri��o' => MdLitSituacaoLancamIntRN::$DESCRICAO];

            $i = 0;
            foreach ($arrDados as $key => $dado) {
                $strItensSelCampoDestino = MdLitSituacaoLancamIntINT::montarSelectCampoDestino($arrParametroSaida, $objMdLitSituacaoLancamIntDTO, $dado);
                $idLinha = $i;

                $strCssTr = '<tr id="paramSaidaTable_' . $idLinha . '" class="infraTrClara">';
                $checked = $strItensSelCampoDestino['chaveUnica'] ? 'checked="checked"' : '';
                $disable = $checked == ''? 'disabled="disabled"': '';

                $strResultadoParamSaida .= $strCssTr;
                $strResultadoParamSaida .= "<td id='campo_$idLinha>";
                $strResultadoParamSaida .= "<input type='hidden' name='hdnArrayDadosSaida[$i]' value='{$arrParametroSaida[$dado]}' />";
                $strResultadoParamSaida .= $key;
                $strResultadoParamSaida .= "</td>";
                $strResultadoParamSaida .= "<td align='center'><select  class='form-control' id='campoDestino_$idLinha' name='campoDestino[$dado]' onchange='mudarcampoDestino(this)'>{$strItensSelCampoDestino['select']}</select></td>";
                $strResultadoParamSaida .= "<td align='center'><div class='infraRadioDiv'><input type='radio'name='chaveUnicaDadosSaida' value='{$key}' $checked id='chaveUnicaDadosSaida_{$idLinha}' $disable class='infraRadioInput'><label class='infraRadioLabel' for='chaveUnicaDadosSaida_{$idLinha}'></div></label></td>";

                $strResultadoParamSaida .= '</tr>' . "\n";
                $i++;
            }
            $strResultadoParamSaida .= '</table>';
        }

        return array('strResultado' => $strResultadoParamSaida,'numRegistros'=> $numRegistrosParametroSaida );
    }

    public static function montarTabelaSituacaoLancamentoIntegracao(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO){

        $objMdLitSoapClientRN = new MdLitSoapClienteRN($objMdLitSituacaoLancamIntDTO->getStrEnderecoWsdl(), 'wsdl');
        $objMdLitSoapClientRN->setSoapVersion($objMdLitSituacaoLancamIntDTO->getStrVersaoSoap());
        $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
        $arrResultadoWebService = $objMdLitSoapClientRN->enviarDados($objMdLitSituacaoLancamIntDTO->getStrOperacaoWsdl(), array());

        $arrResultadoWebService = $arrResultadoWebService['return'];
        $strResultado = '';

        //Tabela de resultado do web-service de listar servi�o
        $numRegistros = count($arrResultadoWebService);
        if($numRegistros > 0){
            $strSumarioTabela = 'Tabela de resultado do web-service de listar de situa��o de lan�amento';
            $strCaptionTabela = 'Situa��o de Lan�amento';

            $strResultado .= '<table width="100%" id="tableWebServiceSituacaoLancamento" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<tr>';

            $strResultado .= '<th class="infraTh" width="5%">&nbsp;C�digo&nbsp;</th>' . "\n";
            $strResultado .= '<th class="infraTh" width="25%">&nbsp;Descri��o&nbsp;</th>' . "\n";
            $strResultado .= '<th class="infraTh" width="10%">&nbsp;Situa��o de Cancelamento&nbsp;</th>' . "\n";
            $strResultado .= '<th class="infraTh" width="10%">&nbsp;Cor&nbsp;</th>' . "\n";
            $strResultado .= '</tr>' . "\n";
            $strCssTr = '';

            $strOptionCor = MdLitSituacaoLancamentoINT::montarSelectCor();
            for ($i = 0; $i < $numRegistros; $i++) {
                $sinCancelado = '';
                if($objMdLitSituacaoLancamIntDTO->isSetNumIdMdLitSituacaoLancamInt() && $objMdLitSituacaoLancamIntDTO->getNumIdMdLitSituacaoLancamInt()){
                    $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                    $objMdLitSituacaoLancamentoDTO->retTodos();
                    $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancInt($objMdLitSituacaoLancamIntDTO->getNumIdMdLitSituacaoLancamInt());
                    $objMdLitSituacaoLancamentoDTO->setNumCodigo($arrResultadoWebService[$i][$objMdLitSituacaoLancamIntDTO->getStrChaveUnica()]);
                    $objMdLitSituacaoLancamentoDTO->setStrSinAtivoIntegracao('S');

                    $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultar($objMdLitSituacaoLancamentoDTO);

                    if($objMdLitSituacaoLancamentoDTO && $objMdLitSituacaoLancamentoDTO->getStrSinCancelamento() == 'S'){
                        $sinCancelado = " checked='checked' ";
                    }

                    if($objMdLitSituacaoLancamentoDTO){
                        $strOptionCor = MdLitSituacaoLancamentoINT::montarSelectCor($objMdLitSituacaoLancamentoDTO->getStrCorSituacao());
                    }

                }

                $idLinha = $i;

                $strCssTr = '<tr id="paramSaidaTable_' . $idLinha . '" class="infraTrClara">';
                $disable = 'disabled="disabled"';

                $strResultado .= $strCssTr;
                // C�digo
                $strResultado .= "<td id='codigo_$idLinha' style='text-align:center '>";
                $strResultado .= $objMdLitSituacaoLancamIntDTO->isSetStrMapeamentoCodigo() ? PaginaSEI::tratarHTML($arrResultadoWebService[$i][$objMdLitSituacaoLancamIntDTO->getStrMapeamentoCodigo()]): '';
                $strResultado .= "</td>";

                // descricao
                $strResultado .= "<td id='descricao_$idLinha'>";
                $strResultado .= $objMdLitSituacaoLancamIntDTO->isSetStrMapeamentoDescricao() ? (PaginaSEI::tratarHTML(utf8_decode($arrResultadoWebService[$i][$objMdLitSituacaoLancamIntDTO->getStrMapeamentoDescricao()]))): '';
                $strResultado .= "</td>";

                $codigo = trim($arrResultadoWebService[$i][$objMdLitSituacaoLancamIntDTO->getStrChaveUnica()]);
                // Situa��o de Cancelamento
                $strResultado .= "<td id='cancelamento_$idLinha' style='text-align: center;'>";
                $strResultado .= "<input class='infraRadio' type='radio' {$sinCancelado} name='rdoSinCancelamentoIntegracao' id='rdoSinCancelamento_$i' value='$codigo'  >";
                $strResultado .= "</td>";

                // Cor
                $strResultado .= "<td id='cor_$idLinha'>";
                $strResultado .= "<select id='selCor_$i' name='selCores[{$codigo}]'  style='width: 80%;' class='infraSelect form-control'>";
                $strResultado .= $strOptionCor;
                $strResultado .= "</select>";
                $strResultado .= "</td>";

                $strResultado .= '</tr>' . "\n";
            }
            $strResultado .= '</table>';
        }

        return array('strResultado' => $strResultado,'numRegistros'=> $numRegistros );

    }
}
?>
