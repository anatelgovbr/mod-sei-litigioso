<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitIntegracaoINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitFuncionalidade=''){
    $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
    $objMdLitIntegracaoDTO->retNumIdMdLitIntegracao();
    $objMdLitIntegracaoDTO->retStrNome();

    if ($numIdMdLitFuncionalidade!==''){
      $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($numIdMdLitFuncionalidade);
    }

    if ($strValorItemSelecionado!=null){
      $objMdLitIntegracaoDTO->setBolExclusaoLogica(false);
      $objMdLitIntegracaoDTO->adicionarCriterio(array('SinAtivo','IdMdLitIntegracao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdLitIntegracaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
    $arrObjMdLitIntegracaoDTO = $objMdLitIntegracaoRN->listar($objMdLitIntegracaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitIntegracaoDTO, 'IdMdLitIntegracao', 'Nome');
  }

    public static function montarXMLBuscarOperacaoWSDL($data){
        $enderecoWSDL = $data['endereco_wsdl'];
        $xml = "";
        $xml .= "<operacoes>\n";
        try{

            if (!filter_var($enderecoWSDL, FILTER_VALIDATE_URL) || !InfraUtil::isBolUrlValida( $enderecoWSDL, FILTER_VALIDATE_URL )) {
                $xml .= "<success>false</success>\n";
                $xml .= "<msg>O endere�o WSDL n�o � uma URL v�lida.</msg>\n";
                $xml .= "</operacoes>\n";
                return $xml;
            }

            if ( $data['tipoWs'] != 'SOAP' ) {
                throw new Exception('O tipo de cliente informado deve ser do tipo SOAP.');
            }

            $client = new MdLitSoapClienteRN( $enderecoWSDL , ['soap_version' => $data['versaoSoap']] );
            $operacaoArr = $client->retornaArrayOperacoes();

            if(empty($operacaoArr)){
                $xml .= "<success>false</success>\n";
                $xml .= "<msg>N�o existe opera��o.</msg>\n";
                $xml .= "</operacoes>\n";
                return $xml;
            }

            $xml .= "<success>true</success>\n";

            // cria a string xml com as operacoes
            foreach ( $operacaoArr as $key => $operacao ) {
                $xml .= "<operacao key='{$key}'>{$operacao}</operacao>\n";
            }

            $xml .= '</operacoes>';
            return $xml;

        }catch(Exception $e){
            return "<operacoes><sucess><msg> Erro Soap: ".$e->getMessage()."</msg></sucess></operacoes>";
        }
    }

    public static function montarTabelaCodigoReceita($idMdLitMapearParamEntrada = null)
    {

        $xml = '';
        $objMdLitTipoControleDTO = new MdLitTipoControleDTO();
        $objMdLitTipoControleDTO->setStrSinAtivo('S');
        if ($idMdLitMapearParamEntrada != null) {
            $objMdLitTipoControleDTO->setNumIdMdLitMapearParamEntrada($idMdLitMapearParamEntrada);
            $objMdLitTipoControleDTO->retTodos(true);
        }else{
            $objMdLitTipoControleDTO->retTodos();
        }
        $objMdLitTipoControleRN     = new MdLitTipoControleRN();
        $arrObjMdLitTipoControleDTO = $objMdLitTipoControleRN->listar($objMdLitTipoControleDTO);

        if (!empty($arrObjMdLitTipoControleDTO)) {
            $xml = '<div style="padding-top: 2px; padding-bottom: 2px; width: 100%">';
            $xml .= '<table width="100%" align="center" class="infraTable table-striped" id="tbTpCtrlReceita">';
            $xml .= '<tr>';
            $xml .= '<th style="display: none;">ID</th>';
            $xml .= '<th class="infraTh" width="40%">Tipo de Controle</th>';
            $xml .= '<th class="infraTh" width="60%">Receita</th>';
            $xml .= '</tr>';

            foreach ($arrObjMdLitTipoControleDTO as $key => $objMdLitTipoControleDTO) {
                $id           = $objMdLitTipoControleDTO->getNumIdTipoControleLitigioso();
                $sigla        = $objMdLitTipoControleDTO->getStrSigla();
                $valorDefault = $objMdLitTipoControleDTO->isSetStrValorDefault() ? $objMdLitTipoControleDTO->getStrValorDefault() : '';

                $xml .= '<tr class="infraTrClara">';
                $xml .= '<td style="display: none">';
                $xml .= '<input type="text" class="form-control" name="tpControle[' . $key . '][idMdLitTipoControle]" value="' . $id . '"/>';
                $xml .= '</td>';
                $xml .= '<td>';
                $xml .= '<span>' . $sigla . '</span>';
                $xml .= '</td>';
                $xml .= '<td>';
                $xml .= '<input type="text"  class="form-control" name="tpControle[' . $key . '][valorDefault]" value="' .$valorDefault.'"/>';
                $xml .= '</td>';
                $xml .= '</tr>';
            }

            $xml .= '</table></div>';
        }
        return $xml;

    }

    public static function object_to_array( $object ) {
        $result = (array) $object;
        foreach( $result as &$value ) {
            if ( $value instanceof stdClass || $value instanceof SimpleXMLElement )
                $value = self::object_to_array( $value );

            if ( is_array( $value ) ) {
                foreach ( $value as $k => $v ) {
                    if ( $v instanceof stdClass || $v instanceof SimpleXMLElement )
                        $value[$k] = self::object_to_array( $v );
                }
            }
        }
        return $result;
    }
}
