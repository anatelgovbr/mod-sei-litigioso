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

        public static function montarSelectCampoDestino($arrParametroSaida, $objMdLitServicoIntegracaoDTO, $itemSei)
        {
            $chaveUnica = false;
            $str = '<option  value=""> </option>';
            foreach ($arrParametroSaida as $key=>$arrayParam) {
                if(is_array($arrayParam)) {
                    ksort($arrayParam);
                    foreach ($arrayParam as $chave => $item) {
                        $codigoSelect = "";
                        if($objMdLitServicoIntegracaoDTO) {
                            if(MdLitServicoIntegracaoRN::$CODIGO == $itemSei)
                                $codigoSelect = $objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo();
                            elseif(MdLitServicoIntegracaoRN::$SIGLA == $itemSei)
                                $codigoSelect = $objMdLitServicoIntegracaoDTO->getStrMapeamentoSigla();
                            elseif(MdLitServicoIntegracaoRN::$DESCRICAO == $itemSei)
                                $codigoSelect = $objMdLitServicoIntegracaoDTO->getStrMapeamentoDescricao();
                            elseif(MdLitServicoIntegracaoRN::$SITUACAO == $itemSei)
                                $codigoSelect = $objMdLitServicoIntegracaoDTO->getStrMapeamentoSituacao();

                            if($objMdLitServicoIntegracaoDTO->getStrChaveUnica() == $codigoSelect) {
                                $chaveUnica = true;
                            }
                        }
                        if(is_array($item)){
                            foreach($item as $chaveItem => $value) {
                                $chaveFormatada = $key . " - " . $chave . " - " . $chaveItem;
                                $value = $chave . " - " . $chaveItem;
                            }
                        } else {
                            $chaveFormatada = $key . " - " . $item;
                            $value = $item;
                        }
                        $selected = $codigoSelect == $chaveFormatada ? 'selected=selected' : '';
                        $str .= '<option '.$selected.' value="'.$value.'"> '.$chaveFormatada.' </option>';
                    }
                } else {
                    $codigoSelect = "";
                    if($objMdLitServicoIntegracaoDTO) {
                        if(MdLitServicoIntegracaoRN::$CODIGO == $itemSei)
                            $codigoSelect = $objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo();
                        elseif(MdLitServicoIntegracaoRN::$SIGLA == $itemSei)
                            $codigoSelect = $objMdLitServicoIntegracaoDTO->getStrMapeamentoSigla();
                        elseif(MdLitServicoIntegracaoRN::$DESCRICAO == $itemSei)
                            $codigoSelect = $objMdLitServicoIntegracaoDTO->getStrMapeamentoDescricao();
                        elseif(MdLitServicoIntegracaoRN::$SITUACAO == $itemSei)
                            $codigoSelect = $objMdLitServicoIntegracaoDTO->getStrMapeamentoSituacao();

                        if($objMdLitServicoIntegracaoDTO->getStrChaveUnica() == $codigoSelect) {
                            $chaveUnica = true;
                        }
                    }
                    $selected = $codigoSelect == $arrayParam ? 'selected=selected' : '';
                    $str .= '<option '.$selected.' value="'.$arrayParam.'"> '.$arrayParam.' </option>';
                }
            }
            return array('select' => $str,'chaveUnica'=> $chaveUnica );
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
                    $xml .= "<msg>O endereço WSDL não é uma URL válida.</msg>\n";
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
                    $xml .= "<msg>Não existe operação.</msg>\n";
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
                $xml .= "<msg>Erro na conexão SOAP: {$e->getMessage()}</msg>\n";
            }

            $xml .= '</operacoes>';
            return $xml;
        }

    }
