<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';
require_once dirname(__FILE__).'/../lib/nusoap/nusoap.php';

class MdLitSoapClienteRN extends nusoap_client{

	protected $wsdl;

	protected $options;
	
	function __construct($endpoint,$wsdl = false,$proxyhost = false,$proxyport = false,$proxyusername = false, $proxypassword = false, $timeout = 0, $response_timeout = 30, $portName = ''){
        ini_set('default_socket_timeout', 6000);
        ini_set("soap.wsdl_cache_enabled", "0");

        $this->wsdl = $wsdl;
        parent::nusoap_client($endpoint,$wsdl,$proxyhost,$proxyport,$proxyusername, $proxypassword, $timeout, $response_timeout, $portName);
    }

    public function getFunctions(){
        $functions = array();

        if ($this->endpointType == 'wsdl' && is_null($this->wsdl)) {
            $this->loadWSDL();
            if ($this->getError())
            return false;
        }
        //escrevendo nome de cada opera�ao disponivel
        foreach($this->operations as $op){
        $functions[] =  $op['name']; //nome da opera�ao
        }
        return $functions;
    }

    public function getParamsInput($nameOperations, $recursivo = false)
    {
        $operations = $this->getOperationData($nameOperations);
        $complexTypes = $this->wsdl->schemas[$this->wsdl->namespaces['tns']][0]->complexTypes;
        $outputArr = array();

        if ($recursivo) {
            $returnType = $nameOperations;
        } else {
            if (!$operations) {
                throw new InfraException('Nome da opera��o n�o existe.');
            }

            $nameType = $this->getEntidadePorUrlWSDL($operations['input']['parts']['parameters']);

            if (!$nameType){
                $nameType = key($operations['input']['parts']);
            }

            if (!$complexTypes[$nameType]['elements']) {
                return $outputArr;
            }


            $returnType = current($complexTypes[$nameType]['elements']);
            $returnType = $this->getEntidadePorUrlWSDL($returnType['type']);
            $returnType = $this->_verificaTipoDadosWebService($returnType, $nameType);
        }

        if (!empty($complexTypes[$returnType]['elements'])) {


            foreach ($complexTypes[$returnType]['elements'] as $nome => $elementArr) {
                $outputArr[] = $nome;
            }
        }

        if (array_key_exists('extensionBase', $complexTypes[$returnType])) {
            $returnType2 = $this->getEntidadePorUrlWSDL($complexTypes[$returnType]['extensionBase']);
            $outputArr2 = $this->getParamsInput($returnType2, true);

            if (count($outputArr2) > 0) {
                $outputArr = array_merge($outputArr, $outputArr2);
                sort($outputArr);
            }
        }

        return $outputArr;
    }

    /*
     * Verifica se o tipo retornado � um tipo ou realmente o nome.
     * */
    private function _verificaTipoDadosWebService($returnType, $nameType){
        $isTipo = false;
        $arrTipos = array('string', 'boolean', 'long', 'int', 'decimal', 'dateTime', 'short');

       if(in_array($returnType, $arrTipos)){
           $isTipo = true;
       }

        $retorno = $isTipo ? $nameType : $returnType;

        return $retorno;
    }


    public function getParamsOutput($nameOperations){
        $operations     = $this->getOperationData($nameOperations);
        $complexTypes   = $this->wsdl->schemas[$this->wsdl->namespaces['tns']][0]->complexTypes;
        $outputArr     = array();


        if(!$operations)
            throw new InfraException('Nome da opera��o n�o existe.');

        /**
         * @todo if para tratar o web-service da ANATEL de servi�o aonde o wsdl n�o possui assinatura de output
         */
        if(empty($operations['output']['parts'])){
            $resp = $this->call($nameOperations, array());
            if($this->responseData === false){
                $objInfraException = new InfraException();
                $objInfraException->adicionarValidacao('N�o foi poss�vel comunica��o com o servidor.');
                $objInfraException->lancarValidacoes();
            }

            if(!$resp){
                $objInfraException = new InfraException();
                $objInfraException->adicionarValidacao('N�o possui resposta do web-service.');
                $objInfraException->lancarValidacoes();
            }

            foreach ($resp['listaTipoServico'][0] as $campo => $valor){
                $outputArr[] = $campo;
            }
            return $outputArr;
        }

        $nameType       = $this->getEntidadePorUrlWSDL($operations['output']['parts']['parameters']);
        if(!$nameType)
            $nameType       = key($operations['output']['parts']);

        $returnType     = $this->getEntidadePorUrlWSDL($complexTypes[$nameType]['elements']['return']['type']);
        if($complexTypes[$returnType]['elements']){
            foreach ($complexTypes[$returnType]['elements'] as $nome => $elementArr){
                $outputArr[] = $nome;
            }
        }
        return $outputArr;
    }

    private function getEntidadePorUrlWSDL($urlWSDL){
        $urlWSDL = strrchr($urlWSDL, ':');
        if(!$urlWSDL) return null;

        return preg_replace('/[^a-z0-9]/i','',$urlWSDL);
    }
    /**
     * xml2array() will convert the given XML text to an array in the XML structure.
     * Link: http://www.bin-co.com/php/scripts/xml2array/
     * Arguments : $contents - The XML text
     *                $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
     *                $priority - Can be 'tag' or 'attribute'. This will change the way the resulting array sturcture. For 'tag', the tags are given more importance.
     * Return: The parsed XML in an array form. Use print_r() to see the resulting array structure.
     * Examples: $array =  xml2array(file_get_contents('feed.xml'));
     *              $array =  xml2array(file_get_contents('feed.xml', 1, 'attribute'));
     */
    public function xml2array($contents, $get_attributes=1, $priority = 'tag') {
        if(!$contents) return array();

        if(!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if(!$xml_values) return;//Hmm...

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference

        //Go through the tags.
        $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if(isset($value)) {
                if($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if(isset($attributes) and $get_attributes) {
                foreach($attributes as $attr => $val) {
                    if($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;
                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag.'_'.$level] = 1;

                    $current = &$current[$tag];

                } else { //There was another element with the same tag name

                    if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                        $repeated_tag_index[$tag.'_'.$level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag.'_'.$level] = 2;

                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                    }
                    $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

                } else { //If taken, put all things inside a list(array)
                    if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                        if($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag.'_'.$level]++;

                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag.'_'.$level] = 1;
                        if($priority == 'tag' and $get_attributes) {
                            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                                $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                                unset($current[$tag.'_attr']);
                            }

                            if($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                    }
                }

            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }

        return($xml_array);
    }


    public function enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, $nomeArrPrincipal = false){
        $arrResultado = array();

        try{
            $err = $this->getError();

            if($err){
                throw new InfraException($err);
            }

            $this->soap_defencoding = 'ISO-8859-1';
            $this->decode_utf8 = false;
            if($nomeArrPrincipal){
                $montarParametroEntrada = array($nomeArrPrincipal => $montarParametroEntrada);
           }

            //@todo retirar quanto verificar a configura��o do wso2 da anatel
            $this->forceEndpoint = 'http://sistemasnetds/services/financeiroService.financeiroServiceHttpsSoap11Endpoint';
            $this->persistentConnection = false;
            $arrResultado = $this->call($objMdLitIntegracaoDTO->getStrOperacaWsdl(), $montarParametroEntrada);
           
            $err = $this->getError();

            if($err){

                if($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade() == MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO){
                    $exception = new InfraException();
                    $exception->lancarValidacao('N�o foi poss�vel a comunica��o com o Webservice da Arrecada��o. Contate o Gestor do Controle.', null,new Exception($err));
                }
                InfraDebug::getInstance()->setBolLigado(true);
                InfraDebug::getInstance()->setBolDebugInfra(false);
                InfraDebug::getInstance()->limpar();
                InfraDebug::getInstance()->gravar($this->request);
                throw new InfraException('Ocorreu erro ao conectar com a opera��o('.$objMdLitIntegracaoDTO->getStrOperacaWsdl().').'.$err);
            }

        }catch (Exception $e){
            throw new InfraException('Ocorreu erro ao executar o servi�o de lan�amento. ', $e );
        }

        if(count($arrResultado) > 0) {
            return $arrResultado;
        }

        return false;
    }


}