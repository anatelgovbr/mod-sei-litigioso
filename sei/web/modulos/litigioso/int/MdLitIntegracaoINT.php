<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
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
                $xml .= "<msg>O endereço WSDL não é uma URL válida.</msg>\n";
                $xml .= "</operacoes>\n";
                return $xml;
            }

            if($data['tipoWs'] != 'SOAP'){
                throw new Exception('O tipo de cliente informado deve ser do tipo SOAP.');
            }

            $client = new MdLitSoapClienteRN($enderecoWSDL, 'wsdl');
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

    public static function montarTabelaCodigoReceita($idMdLitMapearParamEntrada = null)
    {

        $xml                     = ' ';
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
            $xml = '<div style="padding-top: 2px; padding-bottom: 2px; width: 80%">';
            $xml .= '<table width="100%" align="center" class="infraTable table-striped">';
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
}
