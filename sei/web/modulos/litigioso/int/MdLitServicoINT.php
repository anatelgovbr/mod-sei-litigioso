<?php
    /**
     * ANATEL
     *
     * 01/03/2017 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitServicoINT extends InfraINT
    {

        public static function montarSelectCampoDestino($codigoSelect = '')
        {
            $arrDados =[
                'C�digo' => MdLitServicoIntegracaoRN::$CODIGO,
                'Descri��o' => MdLitServicoIntegracaoRN::$DESCRICAO,
                'Sigla' => MdLitServicoIntegracaoRN::$SIGLA,
                'Situa��o' => MdLitServicoIntegracaoRN::$SITUACAO];
            $str = '<option  value=""> </option>';;
            foreach($arrDados as  $key => $dado){
                $selected = $codigoSelect == $dado ? 'selected=selected' : '';
                $str .= '<option '.$selected.' value="'.$dado.'"> '.$key.' </option>';
            }

            return $str;
        }

        
        public static function montarRadioMapCampos($id, $checked = false)
        {
            $strChecked = $checked ? 'checked = checked' : '';
            
            $html = '<input '.$strChecked.' type="radio" name="'.$id.'" id="'.$id.'">';

            return $html;
        }

        public static function autoCompletarServicosAtivos($strPalavrasPesquisa)
        {
            $objServicoDTO = new MdLitServicoDTO();
            $objServicoDTO->retTodos();

            $objServicoDTO->setStrDescricao('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $objServicoDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objServicoDTO->setStrSinAtivo('S');

            $objServicoRN      = new MdLitServicoRN();
            $arrObjServicoDTO = $objServicoRN->listar($objServicoDTO);

            return $arrObjServicoDTO;
        }

        public static function montarXMLBuscarOperacaoWSDL($data){
            $enderecoWSDL = $data['endereco_wsdl'];
            $xml = "";
            $xml .= "<operacoes>\n";
            try{

                if (!filter_var($enderecoWSDL, FILTER_VALIDATE_URL) || !InfraUtil::isBolUrlValida($enderecoWSDL)) {
                    $xml .= "<success>false</success>\n";
                    $xml .= "<msg>O endere�o WSDL n�o � uma URL v�lida.</msg>\n";
                    $xml .= "</operacoes>\n";
                    return $xml;
                }

                if($data['tipoWs'] != 'SOAP'){
                    throw new Exception('O tipo de cliente informado deve ser do tipo SOAP.');
                }

                $client = new MdLitSoapClienteRN($data['endereco_wsdl'], 'wsdl');
                $client->setSoapVersion($data['versaoSoap']);
                $operacaoArr = $client->getFunctions();

                if(empty($operacaoArr)){
                    $xml .= "<success>false</success>\n";
                    $xml .= "<msg>N�o existe opera��o.</msg>\n";
                    $xml .= "</operacoes>\n";
                    return $xml;
                }

                $xml .= "<success>true</success>\n";
                asort($operacaoArr);
                foreach ($operacaoArr as $key=>$operacao){
                    $xml .= "<operacao key='{$key}'>{$operacao}</operacao>\n";
                }

            }catch(Exception $e){
                $xml = "<operacoes>\n";
                $xml .= "<success>false</success>\n";
                $xml .= "<msg>Erro na conex�o SOAP: {$e->getMessage()}</msg>\n";
            }

            $xml .= '</operacoes>';
            return $xml;
        }

    }