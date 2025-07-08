<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 03/04/2017 - criado por Ellyson de Jesus Silva
 * 25/02/2025 - atualizado por Gustavo Camelo
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitSoapClienteRN extends BeSimple\SoapClient\SoapClient {

    public $strUrlWsdl = null;

    function __construct($enderecoWSDL, $options = [] ){
        ini_set("default_socket_timeout", "5");
        ini_set("soap.wsdl_cache_enabled", "0");

        $arrOptions = [
            'trace'		                   => true,
            'exceptions'                   => true,
            'encoding'	                   => 'ISO-8859-1',
            'cache_wsdl'                   => WSDL_CACHE_NONE,
            'soap_version'                 => SOAP_1_2,
            'resolve_wsdl_remote_includes' => true
        ];

        // informa a versao do soap
        if ( !empty( $options) ) {
            foreach ( $options as $k => $v ) {
                if ( $k == 'soap_version' )
                    $arrOptions[$k] = $v == '1.1' ? SOAP_1_1 : SOAP_1_2;
                else
                    $arrOptions[$k] = $v;
            }
        }

        $this->strUrlWsdl = $enderecoWSDL;

        parent::__construct($enderecoWSDL,$arrOptions);
    }

    public function retornaArrayOperacoes() {
        $arrOperacao = $this->__getFunctions();
        $arrMetodos  = [];

        // trata o nome da operacao para retornar somente o valor necessario
        foreach ( $arrOperacao as $key => $operacao ) {
            $array = explode(' ', substr($operacao, 0, strpos($operacao, '(')));
            $arrMetodos[] = end($array);
        }

        // ordena de forma crescente
        asort( $arrMetodos );

        // remove duplicidade
        $arrMetodos = array_unique( $arrMetodos );

        return $arrMetodos;
    }

    public function execOperacao($strOperacao,$montarParametroEntrada = []) {
        try {
            if ( ! InfraWS::isBolServicoExiste($this, $strOperacao))
                return ['suc' => false , 'msg' => "Não existe ou não foi encontrado a operação: $strOperacao."];

            $arrResultado = $this->{$strOperacao}($montarParametroEntrada);

            $arrResultado = MdLitIntegracaoINT::object_to_array($arrResultado);

            return $arrResultado;
        } catch ( SoapFault $s ) {
            $err = $this->trataSoapFaul( $s );
            LogSEI::getInstance()->gravar( $err , InfraLog::$INFORMACAO );
            return ['suc' => false , 'msg' => $err];
        }
    }

    private function parseSoapClientTypes(array $types) {
        $parsed = [];

        foreach ($types as $type) {
            if (preg_match('/^struct\s+(\w+)\s*\{\s*(.*?)\s*\}$/s', $type, $matches)) {
                $typeName = $matches[1];
                $fieldsBlock = $matches[2];

                $fields = [];
                foreach (explode(";\n", $fieldsBlock) as $line) {
                    $line = trim($line);
                    if (!$line) continue;

                    if (preg_match('/(\w+)\s+(\w+)/', $line, $fmatch)) {
                        $fieldType = $fmatch[1];
                        $fieldName = $fmatch[2];
                        $fields[$fieldName] = $fieldType;
                    }
                }

                $parsed[$typeName] = [
                    'fields' => $fields
                ];
            }
        }

        return $parsed;
    }

    private function parseComplexTypesWithExtensionBase(string $wsdlUrl) {
        $dom = new DOMDocument();
        $dom->load($wsdlUrl);

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace("xsd", "http://www.w3.org/2001/XMLSchema");

        $types = [];

        $query = "//xsd:complexType";
        foreach ($xpath->query($query) as $complexType) {
            $name = $complexType->getAttribute('name');
            if (!$name) continue;

            $base = null;
            $fields = [];

            $extension = $xpath->query("xsd:complexContent/xsd:extension", $complexType)->item(0);
            if ($extension instanceof DOMElement) {
                $base = $extension->getAttribute('base');

                foreach ($xpath->query("xsd:sequence/xsd:element", $extension) as $el) {
                    $fields[$el->getAttribute('name')] = $el->getAttribute('type');
                }
            } else {
                // Caso não haja extensão (tipo comum)
                foreach ($xpath->query("xsd:sequence/xsd:element", $complexType) as $el) {
                    $fields[$el->getAttribute('name')] = $el->getAttribute('type');
                }
            }

            $types[$name] = [
                'base' => $base,
                'fields' => $fields
            ];
        }

        return $types;
    }

    public function getParametrosEntradaSaidaWsdl() {
        $soapTypes = $this->parseSoapClientTypes( $this->__getTypes() );
        $wsdlTypes = $this->parseComplexTypesWithExtensionBase( $this->strUrlWsdl );

        // Unir informações
        foreach ($wsdlTypes as $typeName => $wsdlInfo) {
            if (!isset($soapTypes[$typeName])) {
                $soapTypes[$typeName] = $wsdlInfo;
            } else {
                // Se já existe no SoapClient, vamos tentar adicionar o base
                $soapTypes[$typeName]['base'] = $wsdlInfo['base'] ?? null;
            }
        }
        return $soapTypes;
    }

    /* Verifica se o tipo retornado é um tipo ou realmente o nome */
    public function _verificaTipoDadosWebService($tipo){
        $isTipo = false;
        $arrTipos = ['string', 'boolean', 'long', 'int', 'decimal', 'dateTime', 'short'];
        if ( in_array($tipo, $arrTipos) ) $isTipo = true;
        return $isTipo;
    }

    /**
     * Detecta o encode do array informado
     * @param $arrParams
     * @param string $toEncode
     * @return mixed
     */
    public function convertEncondig(&$params, $toEncode='UTF-8')
    {
        try {
            if (is_array($params)) {
                foreach ($params as $key => $value) {
                    if(is_array($value)){
                        $params[$key] = $this->convertEncondig($value, $toEncode);
                        continue;
                    }
                    //detecta o encode que a aplicação esta enviando
                    $fromEncoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);
                    //converte para encode informado
                    if ($toEncode != $fromEncoding) {
                        $params[$key] = mb_convert_encoding($value, $toEncode, $fromEncoding);
                    }
                }
            } else {
                $fromEncoding = mb_detect_encoding($params, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);
                //converte para encode informado
                if ($toEncode != $fromEncoding) {
                    $params = mb_convert_encoding($params, $toEncode, $fromEncoding);
                }
            }

            return $params;
        } catch (Exception $e){
            throw new InfraException("Erro ao converter os parametros do webservice.\n" . $e->getMessage());
        }
    }

    public function trataSoapFaul( $objSoapFault ) {
        $msgFalha = 'Retorno da requisição SOAP: ';
        if ( $objSoapFault->getMessage() ) {
            return $msgFalha . $objSoapFault->getMessage();
        } else if ( !empty( $this->__getLastResponse() ) ) {
            $arrResp = $this->getSoapFaultString( $this->__getLastResponse() );
            return $msgFalha . mb_convert_encoding( $arrResp['faultstring'] , 'UTF-8' );
        } else {
            return $msgFalha . 'Não Identificada';
        }
    }

    private function getSoapFaultString($xmlString) {
        $xml = new XMLReader();
        $xml->XML($xmlString);
        $arrRetorno = [
            'faultstring' => null
        ];

        while ( $xml->read() ) {
            if ( $xml->nodeType == XMLReader::ELEMENT ) {
                // Elemento encontrado
                if ( key_exists( $xml->name , $arrRetorno ) ) {
                    $arrRetorno[$xml->name] = $xml->readString();
                }
            } elseif ( $xml->nodeType == XMLReader::END_ELEMENT ) {
                // Elemento finalizado
            }
        }
        return $arrRetorno;
    }

    /* ****************************************************************************************************************
    ************************* Os métodos abaixo são relacionados ao uso das Integrações *******************************
    **************************************************************************************************************** */

    public function enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, $nomeArrPrincipal = false){
        try{
            if ( $nomeArrPrincipal ) {
                $montarParametroEntrada = [$nomeArrPrincipal => $montarParametroEntrada];
            }

            $arrResultado = $this->execOperacao($objMdLitIntegracaoDTO->getStrOperacaWsdl(), $montarParametroEntrada);

            $err = ( is_array($arrResultado) && ( isset($arrResultado['suc']) && $arrResultado['suc'] === false ) )
                    ? $arrResultado['msg']
                    : null;

            if ( $err ) {

                if($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade() == MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO){
                    ( new InfraException() )->lancarValidacao('Não foi possível a comunicação com o Webservice da Arrecadação. Contate o Gestor do Controle.', null,new Exception($err));
                }

                $err = "Ocorreu erro ao conectar com a operação({$objMdLitIntegracaoDTO->getStrOperacaWsdl()})\n" . $err;
                $this->execDebugLog( $err );
                throw new InfraException($err);
            }

        }catch (Exception $e){
            $this->execDebugLog( $e->getMessage() );
            throw new InfraException("Não foi possível realizar a integração com o Sistema de Arrecadação.\n" . $e->getMessage());
        }

        if( $arrResultado && count($arrResultado) > 0 ) {
            return $arrResultado;
        }

        return false;
    }

    public function enviarDados($strOperacaoWsdl, $montarParametroEntrada, $nomeArrPrincipal = false){
        try{

            if( $nomeArrPrincipal ){
                $montarParametroEntrada = array($nomeArrPrincipal => $montarParametroEntrada);
            }

            $arrResultado = $this->execOperacao($strOperacaoWsdl, $montarParametroEntrada);

            if ( $arrResultado && count($arrResultado) > 0 ) {
                return $this->convertEncondig($arrResultado);
            }

            return false;

        }catch (Exception $e){
            $this->execDebugLog( $e->getMessage() );
            throw new InfraException('Ocorreu erro ao executar o serviço de lançamento. ', $e->getMessage() );
        }
    }

    private function execDebugLog( $strLog ) {
        // liga
        InfraDebug::getInstance()->setBolLigado(true);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        InfraDebug::getInstance()->limpar();

        InfraDebug::getInstance()->gravar( $strLog );

        // salva
        LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

        // limpa e fecha
        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        InfraDebug::getInstance()->limpar();
    }
}
