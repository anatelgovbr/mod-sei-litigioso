<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitServicoIntegracaoINT extends InfraINT {

    public static function montarSelectNomeIntegracao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
        $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
        $objMdLitServicoIntegracaoDTO->retNumIdMdLitServicoIntegracao();
        $objMdLitServicoIntegracaoDTO->retStrNomeIntegracao();

        $objMdLitServicoIntegracaoDTO->setOrdStrNomeIntegracao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
        $arrObjMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->listar($objMdLitServicoIntegracaoDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitServicoIntegracaoDTO, 'IdMdLitServicoIntegracao', 'NomeIntegracao');
    }

    public static function montarTabelaParamSaida(MdLitSoapClienteRN $objMdLitSoapClienteRN, $operacao, $objMdLitServicoIntegracaoDTO = null){
        $versaoSoap = $_POST['versaoSoap'] ?: $objMdLitServicoIntegracaoDTO->getStrVersaoSoap();
        $objMdLitSoapClienteRN->setSoapVersion($versaoSoap);
        $arrParametroSaida = $objMdLitSoapClienteRN->getParamsOutput($operacao);
        $strResultadoParamSaida = '';


        //tabela de dados de saída
        $numRegistrosParametroSaida = count($arrParametroSaida);
        if($numRegistrosParametroSaida > 0){
            $strSumarioTabela = 'Tabela de configuração dos dados de saída do web-service.';
            $strCaptionTabela = 'Dados de saída';

            $strResultadoParamSaida .= '<table width="100%" id="tableParametroSaida" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultadoParamSaida .= '<tr>';

            $strResultadoParamSaida .= '<th class="infraTh" width="10%">&nbsp;Dados de saída&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="30%">&nbsp;Campo de destino no SEI&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="5%">&nbsp;Chave única da integração&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '</tr>' . "\n";
            $strCssTr = '';

            for ($i = 0; $i < $numRegistrosParametroSaida; $i++) {
                $selected = null;
                if($objMdLitServicoIntegracaoDTO){
                    if($objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo() == $arrParametroSaida[$i])
                        $selected = MdLitServicoIntegracaoRN::$CODIGO;
                    elseif($objMdLitServicoIntegracaoDTO->getStrMapeamentoSigla() == $arrParametroSaida[$i])
                        $selected = MdLitServicoIntegracaoRN::$SIGLA;
                    elseif($objMdLitServicoIntegracaoDTO->getStrMapeamentoDescricao() == $arrParametroSaida[$i])
                        $selected = MdLitServicoIntegracaoRN::$DESCRICAO;
                    elseif($objMdLitServicoIntegracaoDTO->getStrMapeamentoSituacao() == $arrParametroSaida[$i])
                        $selected = MdLitServicoIntegracaoRN::$SITUACAO;
                    elseif($objMdLitServicoIntegracaoDTO->getStrMapeamentoSituacao() == $arrParametroSaida[$i])
                        $selected = MdLitServicoIntegracaoRN::$SITUACAO;
                }
                $strItensSelCampoDestino = MdLitServicoINT::montarSelectCampoDestino($selected);
                $idLinha = $i;

                $strCssTr = '<tr id="paramSaidaTable_' . $idLinha . '" class="infraTrClara">';
                $checked = $objMdLitServicoIntegracaoDTO ?($arrParametroSaida[$i] == $objMdLitServicoIntegracaoDTO->getStrChaveUnica() ? 'checked="checked"' : '' ): '';
                $disable = $checked == ''? 'disabled="disabled"': '';

                $strResultadoParamSaida .= $strCssTr;
                $strResultadoParamSaida .= "<td id='campo_$idLinha>";
                $strResultadoParamSaida .= "<input type='hidden' name='hdnArrayDadosSaida[$i]' value='{$arrParametroSaida[$i]}' />";
                $strResultadoParamSaida .= PaginaSEI::tratarHTML($arrParametroSaida[$i]);
                $strResultadoParamSaida .= "</td>";
                $strResultadoParamSaida .= "<td align='center'><select class='form-control' id='campoDestino_$idLinha' name='campoDestino[$arrParametroSaida[$i]]' onchange='mudarcampoDestino(this)' style='width: 80%;'>{$strItensSelCampoDestino}</select></td>";
                $strResultadoParamSaida .= "<td align='center'><div class='infraRadioDiv'><input type='radio'name='chaveUnicaDadosSaida' value='{$arrParametroSaida[$i]}' $checked id='chaveUnicaDadosSaida_{$idLinha}' $disable class='infraRadioInput'><label class='infraRadioLabel' for='chaveUnicaDadosSaida_{$idLinha}'></div></label></td>";

                $strResultadoParamSaida .= '</tr>' . "\n";
            }
            $strResultadoParamSaida .= '</table>';
        }

        return array('strResultado' => $strResultadoParamSaida,'numRegistros'=> $numRegistrosParametroSaida );
    }

    public static function montarTabelaServicoIntegracao(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO){

        $objMdLitSoapClientRN = new MdLitSoapClienteRN($objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl(), 'wsdl');
        $objMdLitSoapClientRN->setSoapVersion($objMdLitServicoIntegracaoDTO->getStrVersaoSoap());
        $arrResultadoWebService = $objMdLitSoapClientRN->call($objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl(), array('soap_version'=>SOAP_1_2,'cache_wsdl' => WSDL_CACHE_NONE));

        $arrResultadoWebService = $arrResultadoWebService['listaTipoServico'];
        $strResultado = '';

        //Tabela de resultado do web-service de listar serviço
        $numRegistros = count($arrResultadoWebService);
        if($numRegistros > 0){
            $strSumarioTabela = 'Tabela de resultado do web-service de listar serviço';
            $strCaptionTabela = 'Serviços';

            $strResultado .= '<table width="99%" id="tableWebServiceServico" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<tr>';

            $strResultado .= '<th class="infraTh" width="5%">&nbsp;Código&nbsp;</th>' . "\n";
            $strResultado .= '<th class="infraTh" width="5%">&nbsp;Sigla&nbsp;</th>' . "\n";
            $strResultado .= '<th class="infraTh" width="20%">&nbsp;Descrição&nbsp;</th>' . "\n";
            $strResultado .= '</tr>' . "\n";
            $strCssTr = '';

            for ($i = 0; $i < $numRegistros; $i++) {

                $idLinha = $i;

                $strCssTr = '<tr id="paramSaidaTable_' . $idLinha . '" class="infraTrClara">';
                $disable = 'disabled="disabled"';
                $checked = '';

                $strResultado .= $strCssTr;
                // Código
                $strResultado .= "<td id='codigo_$idLinha'>";
                $strResultado .= $objMdLitServicoIntegracaoDTO->isSetStrMapeamentoCodigo() ? PaginaSEI::tratarHTML($arrResultadoWebService[$i][$objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()]): '';
                $strResultado .= "</td>";

                // Sigla
                $strResultado .= "<td id='sigla_$idLinha'>";
                $strResultado .= $objMdLitServicoIntegracaoDTO->isSetStrMapeamentoSigla() ? PaginaSEI::tratarHTML($arrResultadoWebService[$i][$objMdLitServicoIntegracaoDTO->getStrMapeamentoSigla()]): '';
                $strResultado .= "</td>";

                // descricao
                $strResultado .= "<td id='descricao_$idLinha'>";
                $strResultado .= $objMdLitServicoIntegracaoDTO->isSetStrMapeamentoDescricao() ? (PaginaSEI::tratarHTML($arrResultadoWebService[$i][$objMdLitServicoIntegracaoDTO->getStrMapeamentoDescricao()])): '';
                $strResultado .= "</td>";

                $strResultado .= '</tr>' . "\n";
            }
            $strResultado .= '</table>';
        }

        return array('strResultado' => $strResultado,'numRegistros'=> $numRegistros );

    }
}
?>
