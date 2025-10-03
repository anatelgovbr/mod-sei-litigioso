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
        $arrParametroSaidaAux = [];
        $arrParametroSaida    = [];

        MdLitMapearParamSaidaINT::trataDadosSemAssinaturaSaida($objMdLitSoapClienteRN,$operacao,$arrParametroSaidaAux);

        foreach ( $arrParametroSaidaAux['listaTipoServico'][0] as $campo => $valor ) {
            $arrParametroSaida[] = $campo;
        }

        $strResultadoParamSaida = '';

        //tabela de dados de saída
        $numRegistrosParametroSaida = count($arrParametroSaida);
        if($numRegistrosParametroSaida > 0){
            $strSumarioTabela = 'Tabela de configuração dos dados de saída do web-service.';
            $strCaptionTabela = 'Dados de saída';

            $strResultadoParamSaida .= '<table width="100%" id="tableParametroSaida" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultadoParamSaida .= '<tr>';
            $strResultadoParamSaida .= '<th class="infraTh" width="40%">&nbsp;Campo de destino no SEI&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '<th class="infraTh" width="50%">&nbsp;Dados de saída&nbsp;</th>' . "\n";

            $strResultadoParamSaida .= '<th class="infraTh" width="10%">&nbsp;Chave única da integração&nbsp;</th>' . "\n";
            $strResultadoParamSaida .= '</tr>' . "\n";
            $strCssTr = '';

            $arrDados =[
                'Código' => MdLitServicoIntegracaoRN::$CODIGO,
                'Descrição' => MdLitServicoIntegracaoRN::$DESCRICAO,
                'Sigla' => MdLitServicoIntegracaoRN::$SIGLA,
                'Situação' => MdLitServicoIntegracaoRN::$SITUACAO];

            $i = 0;
            foreach ($arrDados as $key => $dado) {
                $strItensSelCampoDestino = MdLitServicoINT::montarSelectCampoDestino($arrParametroSaida, $objMdLitServicoIntegracaoDTO, $dado);
                $idLinha = $i;

                $strCssTr = '<tr id="paramSaidaTable_' . $idLinha . '" class="infraTrClara">';

                $checked = $strItensSelCampoDestino['chaveUnica'] ? 'checked="checked"' : '';
                $disable = $checked == ''? 'disabled="disabled"': '';


                $strResultadoParamSaida .= $strCssTr;
                $strResultadoParamSaida .= "<td id='campo_$idLinha>";
                $strResultadoParamSaida .= "<input type='hidden' name='hdnArrayDadosSaida[$dado]' value='{$key}' />";
                $strResultadoParamSaida .= $key;
                $strResultadoParamSaida .= "</td>";
                $strResultadoParamSaida .= "<td align='center'><select class='form-select' id='campoDestino_$idLinha' name='campoDestino[$dado]' onchange='mudarcampoDestino(this)' style='width: 80%;'>{$strItensSelCampoDestino['select']}</select></td>";
                $strResultadoParamSaida .= "<td align='center'><div class='infraRadioDiv'><input type='radio'name='chaveUnicaDadosSaida' value='{$key}' $checked id='chaveUnicaDadosSaida_{$idLinha}' $disable class='infraRadioInput'><label class='infraRadioLabel' for='chaveUnicaDadosSaida_{$idLinha}'></div></label></td>";

                $strResultadoParamSaida .= '</tr>' . "\n";
                $i++;
            }
            $strResultadoParamSaida .= '</table>';
        }

        return array('strResultado' => $strResultadoParamSaida,'numRegistros'=> $numRegistrosParametroSaida );
    }

    public static function montarTabelaServicoIntegracao(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO){
        $objMdLitSoapClientRN = new MdLitSoapClienteRN( $objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl() , [
                'soap_version' => $objMdLitServicoIntegracaoDTO->getStrVersaoSoap(),
                'encoding'     => 'UTF-8'
            ]
        );

        $arrResultadoWebService = [];
        MdLitMapearParamSaidaINT::trataDadosSemAssinaturaSaida($objMdLitSoapClientRN,$objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl(),$arrResultadoWebService);
        $arrResultadoWebService = $arrResultadoWebService['listaTipoServico'] ?? [];
        $strResultado = '';

        //Tabela de resultado do web-service de listar serviço
        $numRegistros = $arrResultadoWebService ? count($arrResultadoWebService) : 0;
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
