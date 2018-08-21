<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitDadoInteressadoINT extends InfraINT {

  public static function montarSelectIdContato($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitControle='',  $numIdMdLitNumeroInteressado=null ){
    $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
    $objMdLitDadoInteressadoDTO->retTodos(true);
    $objMdLitDadoInteressadoDTO->retNumIdMdLitDadoInteressado();
    $objMdLitDadoInteressadoDTO->retNumIdContato();

    if ($numIdMdLitControle!==''){
      $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($numIdMdLitControle);
    }

//      if ($numNumeroInteressado!==''){
//          $objMdLitDadoInteressadoDTO->setStrNumero($numNumeroInteressado);
//      }

    $objMdLitDadoInteressadoDTO->setOrdStrNomeContatoParticipante(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
    $arrObjMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->listar($objMdLitDadoInteressadoDTO);
    $arrObjMdLitDadoInteressadoDTO = self::retirarDuplicado($arrObjMdLitDadoInteressadoDTO,'IdContato');

      if($numIdMdLitNumeroInteressado != null && $strValorItemSelecionado == ''){
          $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
          $objMdLitNumeroInteressadoDTO->retTodos(false);
          $objMdLitNumeroInteressadoDTO->setNumMaxRegistrosRetorno(1);
          $objMdLitNumeroInteressadoDTO->setNumIdMdLitNumeroInteressado($numIdMdLitNumeroInteressado);

          $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
          $objMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoRN->consultar($objMdLitNumeroInteressadoDTO);

          $strValorItemSelecionado = $objMdLitNumeroInteressadoDTO ? $objMdLitNumeroInteressadoDTO->getNumIdMdLitDadoInteressado() : null;
      }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitDadoInteressadoDTO, 'IdMdLitDadoInteressado', 'NomeContatoParticipante');
  }

    public static function montarSelectNumero($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDadoInteressado=''){
        $objMdLitRelNumInterServicoDTO = new MdLitRelNumInterServicoDTO();
        $objMdLitRelNumInterServicoDTO->retNumIdMdLitNumeroInteressado();
        $objMdLitRelNumInterServicoDTO->retNumIdContatoMdLitDadoInteressado();
        $objMdLitRelNumInterServicoDTO->retStrNumeroMdLitNumeroInteressado();
        $objMdLitRelNumInterServicoDTO->retNumIdMdLitServico();
        $objMdLitRelNumInterServicoDTO->retStrDescricaoMdLitServico();
        $objMdLitRelNumInterServicoDTO->retNumIdMdLitDadoInteressadoMdLitNumeroInteressado();

        $objMdLitRelNumInterServicoDTO->setNumIdMdLitDadoInteressadoMdLitNumeroInteressado($numIdMdLitDadoInteressado);

        $objMdLitRelNumInterServicoDTO->setOrdStrDescricaoMdLitServico(InfraDTO::$TIPO_ORDENACAO_ASC);
        
        $objMdLitRelNumInterServicoRN = new MdLitRelNumInterServicoRN();
        $arrObjMdLitDadoInteressadoDTO = $objMdLitRelNumInterServicoRN->listar($objMdLitRelNumInterServicoDTO);

        foreach ($arrObjMdLitDadoInteressadoDTO as $objMdLitRelNumInterServicoDTO){
            $strNumero = $objMdLitRelNumInterServicoDTO->getStrNumeroMdLitNumeroInteressado();
            if(!$strNumero){
                $strNumero = 'N�mero a ser gerado';
            }
            $objMdLitRelNumInterServicoDTO->setStrNumeroComServico($strNumero.' - '. $objMdLitRelNumInterServicoDTO->getStrDescricaoMdLitServico());
        }

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitDadoInteressadoDTO, 'IdMdLitNumeroInteressado', 'NumeroComServico');
    }

    public static function retirarDuplicado($arrObjDto, $atributoComparador){
        $valorAnterior = null;
        $retorno = array();
        foreach ($arrObjDto as $objDto){
            if($valorAnterior != $objDto->get($atributoComparador)){
                $valorAnterior = $objDto->get($atributoComparador);
                $retorno[] = $objDto;
            }
        }
        return $retorno;
    }

    public static function montarSelectEstados($strPalavrasPesquisa){

        $objUfDTO = new UfDTO();
        $objUfDTO->retNumIdUf();
        $objUfDTO->retStrSigla();
        $objUfDTO->retStrNome();
        $objUfDTO->setNumIdPais(76);
        $objUfDTO->setStrNome('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
        $objUfDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objUfRN = new UfRN();
        $arrObjUfDTO = $objUfRN->listarRN0401($objUfDTO);
        foreach($arrObjUfDTO as $i => $objUfDTO){
            $arrObjUfDTO[$i]->setStrNome($objUfDTO->getStrNome() . ' (' . $objUfDTO->getStrSigla() . ')');
        }
        return $arrObjUfDTO;
    }

    public static function montarSelectCidades($strPalavrasPesquisa,  $arrSiglaUf = array(), $numIdPais = 76){
        $objCidadeDTO = new CidadeDTO();
        $objCidadeDTO->retNumIdCidade();
        $objCidadeDTO->retStrNome();
        $objCidadeDTO->setStrNome('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
        $objCidadeDTO->setNumIdPais($numIdPais);

        if ($numIdPais==76) {
            $objCidadeDTO->setNumTipoFkUf(InfraDTO::$TIPO_FK_OBRIGATORIA);
        }

        $objCidadeDTO->setOrdStrSinCapital(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objCidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        if ($arrSiglaUf!==''){
            $objCidadeDTO->setOrdStrSiglaUf(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objCidadeDTO->setNumIdUf($arrSiglaUf, InfraDTO::$OPER_IN);
        }

        $objCidadeRN = new CidadeRN();
        $arrObjCidadeDTO = $objCidadeRN->listarRN0410($objCidadeDTO);

        return $arrObjCidadeDTO;
    }

    public static function dadoComplementarConsulta($filtro){
        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoDTO->retTodos();
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade(MdLitIntegracaoRN::$DADOS_COMPL_INTERESSADO_CONSULTA);
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultar($objMdLitIntegracaoDTO);

        $objMdLitParamEntradaRN = new MdLitMapearParamEntradaRN();
        $objMdLitParamEntradaDTO = new MdLitMapearParamEntradaDTO();
        $objMdLitParamEntradaDTO->retTodos();
        $objMdLitParamEntradaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
        $arrObjMdLitParamEntradaDTO = $objMdLitParamEntradaRN->listar($objMdLitParamEntradaDTO);

        $objMdLitParamSaidaRN = new MdLitMapearParamSaidaRN();
        $objMdLitParamSaidaDTO = new MdLitMapearParamSaidaDTO();
        $objMdLitParamSaidaDTO->retTodos();
        $objMdLitParamSaidaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
        $arrObjMdLitParamSaidaDTO = $objMdLitParamSaidaRN->listar($objMdLitParamSaidaDTO);

        $filtroWS = array();

        foreach ($arrObjMdLitParamEntradaDTO as $objMdLitParamEntradaDTO){
            switch ($objMdLitParamEntradaDTO->getNumIdMdLitNomeFuncional()){
                case MdLitNomeFuncionalRN::$NUMERO:
                    $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = isset($filtro['numero']) ? $filtro['numero'] : null;
                    break;
                case MdLitNomeFuncionalRN::$SERVICO:
                    if(isset($filtro['servico'])){
                        $filtro['servico'] = PaginaSEI::getInstance()->getArrItensTabelaDinamica($filtro['servico']);
                        foreach ($filtro['servico'] as $servico){
                            $objMdLitServicoDTO = new MdLitServicoDTO();
                            $objMdLitServicoRN = new MdLitServicoRN();
                            $objMdLitServicoDTO->retTodos();
                            $objMdLitServicoDTO->setNumIdMdLitServico($servico[0]);
                            $objMdLitServicoDTO = $objMdLitServicoRN->consultar($objMdLitServicoDTO);

                            $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()][] = $objMdLitServicoDTO->getStrCodigo();

                        }
                    }
                    break;
                case MdLitNomeFuncionalRN::$OUTORGA:
                    if(isset($filtro['outorga'])){
                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $filtro['outorga'];
                    }
                    break;
                case MdLitNomeFuncionalRN::$MODALIDADE:
                    $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $filtro['modalidade'];
                    break;
                case MdLitNomeFuncionalRN::$ABRANGENCIA:
                    $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $filtro['abrangencia'];
                    break;
                case MdLitNomeFuncionalRN::$CIDADE:
                    if(isset($filtro['cidades'])) {
                        $filtro['cidades'] = PaginaSEI::getInstance()->getArrItensTabelaDinamica($filtro['cidades']);
                        $arrCidade = array();
                        foreach ($filtro['cidades'] as $cidade){
                            $arrCidade[] =  $cidade[1];
                        }
                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $arrCidade;
                    }
                    break;
                case MdLitNomeFuncionalRN::$ESTADO:
                    if(isset($filtro['estados'])) {
                        $filtro['estados'] = PaginaSEI::getInstance()->getArrItensTabelaDinamica($filtro['estados']);
                        $arrEstados = array();
                        $idEstadosArr = InfraArray::simplificarArr($filtro['estados'], 0);

                        $ufDto = new UfDTO();
                        $ufDto->retTodos(false);
                        $ufDto->setNumIdUf($idEstadosArr, InfraDTO::$OPER_IN);

                        $ufRN = new UfRN();
                        $estadosArr = $ufRN->listarRN0401($ufDto);

                        if(count($estadosArr)){
                            foreach ($estadosArr as $estadoDTO){
                                $arrEstados[] = $estadoDTO->getStrNome();
                            }
                            $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $arrEstados;
                        }
                    }
                    break;
                case MdLitNomeFuncionalRN::$CNPJ_CPF:

                    $strNumero = InfraUtil::retirarFormatacao($filtro['cpf_cnpj']);
                    if (trim($strNumero) == '') {
                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = '';
                    }
                    if (strlen($strNumero) > 11) {
                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = str_pad($strNumero,  14, '0',STR_PAD_LEFT);
                    } else {
                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = str_pad($strNumero,  11, '0',STR_PAD_LEFT);
                    }
                    break;
            }
        }
        $objMdlitSoapClient = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(), 'wsdl');

        $err = $objMdlitSoapClient->getError();
        if($err)
            return '<erros><erro descricao="'.$err.'"></erro></erros>';

        $objMdlitSoapClient->soap_defencoding = 'UTF-8';
        $objMdlitSoapClient->decode_utf8 = false;
        $arrResultado = $objMdlitSoapClient->call($objMdLitIntegracaoDTO->getStrOperacaWsdl(), array('filtro' => $filtroWS));

        $err = $objMdlitSoapClient->getError();
        if($err)
            return '<erros><erro descricao="'.$err.'"></erro></erros>';

        if(empty($arrResultado) && $filtro['outorga'] == 'N'){
            return self::mensagemValidacaoNaoOutorgado($filtro);
        }elseif(empty($arrResultado)){
            return '<erros><erro descricao="N�o existe resultado com os filtros informados no webservice."></erro></erros>';
        }

        if(!is_array($arrResultado['return'][0]))
            $arrResultado['return'] = [$arrResultado['return']];

        $arrResultadoParametrizado = array();
        foreach($arrResultado['return'] as $key => $resultado){
            foreach ($arrObjMdLitParamSaidaDTO as $objMdLitParamEntradaDTO){
                switch ($objMdLitParamEntradaDTO->getNumIdMdLitNomeFuncional()){
                    case MdLitNomeFuncionalRN::$NUMERO:
                        $arrResultadoParametrizado[$key]['numero'] = $resultado[$objMdLitParamEntradaDTO->getStrCampo()];
                        break;
                    case MdLitNomeFuncionalRN::$SERVICO:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objMdLitServicoDTO = new MdLitServicoDTO();
                            $objMdLitServicoRN = new MdLitServicoRN();
                            $objMdLitServicoDTO = self::buscarDTOUtilWS($objMdLitServicoDTO, $objMdLitServicoRN, array('Codigo', 'Sigla', 'Descricao'), utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]));
                            if (!$objMdLitServicoDTO)
                                return '<erros><erro descricao="O servi�o \'' . utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) . '\' recuperado pelo web-service n�o foi cadastrado no SEI"></erro></erros>';

                            $arrResultadoParametrizado[$key]['id_servico'] = $objMdLitServicoDTO->getNumIdMdLitServico();
                            $arrResultadoParametrizado[$key]['nome_servico'] = $objMdLitServicoDTO->getStrDescricao();
                        }

                        break;
                    case MdLitNomeFuncionalRN::$ABRANGENCIA:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objMdLitAbrangenciaDTO = new MdLitAbrangenciaDTO();
                            $objMdLitAbrangenciaRN = new MdLitAbrangenciaRN();
                            $objMdLitAbrangenciaDTO = self::buscarDTOUtilWS($objMdLitAbrangenciaDTO, $objMdLitAbrangenciaRN, array('IdMdLitAbrangencia', 'Nome'), utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]));

                            if (!$objMdLitAbrangenciaDTO)
                                return '<erros><erro descricao="a abrang�ncia \'' . utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) . '\' recuperado pelo web-service n�o foi cadastrado no SEI"></erro></erros>';

                            $arrResultadoParametrizado[$key]['id_abrangencia'] = $objMdLitAbrangenciaDTO->getNumIdMdLitAbrangencia();
                            $arrResultadoParametrizado[$key]['nome_abrangencia'] = $objMdLitServicoDTO->getStrNome();
                        }
                        break;
                    case MdLitNomeFuncionalRN::$MODALIDADE:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
                            $objMdLitModalidadeRN = new MdLitModalidadeRN();
                            $objMdLitModalidadeDTO = self::buscarDTOUtilWS($objMdLitModalidadeDTO, $objMdLitModalidadeRN, array('IdMdLitModalidade', 'Nome'), utf8_decode(current($resultado[$objMdLitParamEntradaDTO->getStrCampo()])));

                            if (!$objMdLitModalidadeDTO)
                                return '<erros><erro descricao="a modalidade \'' . utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) . '\' recuperado pelo web-service n�o foi cadastrado no SEI"></erro></erros>';

                            $arrResultadoParametrizado[$key]['id_modalidade'] = $objMdLitModalidadeDTO->getNumIdMdLitModalidade();
                            $arrResultadoParametrizado[$key]['nome_modalidade'] = $objMdLitModalidadeDTO->getStrNome();
                        }
                        break;
                    case MdLitNomeFuncionalRN::$CNPJ_CPF:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $arrResultadoParametrizado[$key]['cnpj_cpf'] = utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                        }
                        break;
                    case MdLitNomeFuncionalRN::$ESTADO:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $arrResultadoParametrizado[$key]['estado'] = utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                        }
                        break;
                    case MdLitNomeFuncionalRN::$CIDADE:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $arrResultadoParametrizado[$key]['cidade'] = utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                        }
                        break;
                }

            }
        }
        return self::gerarXMLItensArr($arrResultadoParametrizado);
    }

    private static function mensagemValidacaoNaoOutorgado($filtro){
        $nomeLabelOutorga = null;
        $nomeLabelNumero = null;
        //retornando o contato para a msg de exce��o
        $objContatoRN = new ContatoRN();
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->retTodos();
        $objContatoDTO->setNumIdContato($filtro['id_contato']);

        $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
        $contatoCpfCnpj = $objContatoDTO->getDblCpf() ? $objContatoDTO->getDblCpf() : $objContatoDTO->getDblCnpj();

        //retornando os nomes das labels para montar msg de exce��o
        $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();
        $objMdLitParametrizarInteressadoDTO->retTodos(true);
        $objMdLitParametrizarInteressadoDTO->setOrdNumIdMdLitNomeFuncional(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitParametrizarInteressadoDTO->setNumIdMdLitTipoControle($filtro['id_tp_controle']);
        $objMdLitParametrizarInteressadoDTO->setNumIdMdLitNomeFuncional(array(MdLitNomeFuncionalRN::$NUMERO, MdLitNomeFuncionalRN::$OUTORGA), InfraDTO::$OPER_IN);
        $objMdLitParametrizarInteressadoRN = new MdLitParametrizarInteressadoRN();
        $arrObjMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->listar($objMdLitParametrizarInteressadoDTO);

        foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){
            if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO ){
                $nomeLabelNumero = $objMdLitParametrizarInteressadoDTO->getStrLabelCampo();
            }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$OUTORGA){
                $nomeLabelOutorga = $objMdLitParametrizarInteressadoDTO->getStrLabelCampo();
            }
        }

        return '<erros><erro descricao="O Interessado '.$objContatoDTO->getStrNome().' - '.InfraUtil::formatarCpfCnpj($contatoCpfCnpj).' n�o possui '.$nomeLabelNumero.' gerado. Este '.$nomeLabelNumero.' ser� gerado no momento do Lan�amento de Multa porventura aplicada."></erro></erros>';
    }

    private static function buscarDTOUtilWS(InfraDTO $objDTO, InfraRN $objRN, $criterio, $txtFiltro){
        $objDTO->retTodos();
        $operadoresAtributos = array();
        $operadoresLogico    = array();
        $valoresAtributos    = array();
        foreach ($criterio as $item) {
            $operadoresAtributos[]  = InfraDTO::$OPER_IGUAL;
            $operadoresLogico[]     = InfraDTO::$OPER_LOGICO_OR;
            $valoresAtributos[]     = $txtFiltro;
        }
        unset($operadoresLogico[count($operadoresLogico)-1]);

        $objDTO->adicionarCriterio($criterio,
            $operadoresAtributos,
            $valoresAtributos,
            $operadoresLogico);

        return $objRN->consultar($objDTO);

//        $return = array();
//        foreach ($arrObj as $objDTO){
//            foreach ($objDTO->getArrAtributos() as $atributo) {
//                $return[$atributo[1]] = isset($return[$atributo[1]])? $atributo[2].', '.$atributo[2]: $atributo[2];
//            }
//        }
//        var_dump($arrObj);exit;
    }

    public static function gerarXMLItensArr($arr){
        $xml = '';
        $xml .= "<itens>\n";
        if ($arr !== null ){
            foreach($arr as $value){
                $xml .= '<item ';
                foreach ($value as $keyAtributo => $valueAtributo ){
                    $xml .= InfraString::formatarXML($keyAtributo).'="'.InfraString::formatarXML($valueAtributo).'" ';
                }
                $xml .= "/>\n";
            }
        }
        $xml .= '</itens>';
        return $xml;
    }

    public static function buscarDadoComplementarListarXml($idProcedimento, $idContato, $idMdLitControle){
        $arrResultado = array();

        $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
        $objMdLitDadoInteressadoDTO->retTodos(true);
        $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);
        $objMdLitDadoInteressadoDTO->setNumIdContato($idContato);
        $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);
        $objMdLitDadoInteressadoDTO->setNumMaxRegistrosRetorno(1);

        $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
        $objMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->consultar($objMdLitDadoInteressadoDTO);

        if(!$objMdLitDadoInteressadoDTO){
            return self::gerarXMLItensArr($arrResultado);
        }

        $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
        $objMdLitNumeroInteressadoDTO->retTodos(true);
        $objMdLitNumeroInteressadoDTO->setNumIdMdLitDadoInteressado($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado());

        $objMdlitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
        $arrObjMdLitNumeroInteressadoDTO = $objMdlitNumeroInteressadoRN->listar($objMdLitNumeroInteressadoDTO);

        foreach ($arrObjMdLitNumeroInteressadoDTO as $key=>$objMdLitNumeroInteressadoDTO) {

            $arrResultado[$key]['numero']       = $objMdLitNumeroInteressadoDTO->getStrNumero();
            $arrResultado[$key]['id_md_lit_numero_interessado']    = $objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado();
            $arrResultado[$key]['id_contato']    = $idContato;
            $arrResultado[$key]['outorgado']    = $objMdLitDadoInteressadoDTO->getStrSinOutorgado();
            //servico
            $objMdLitRelNumInterServicoDTO = new MdLitRelNumInterServicoDTO();
            $objMdLitRelNumInterServicoDTO->retNumIdMdLitServico();
            $objMdLitRelNumInterServicoDTO->retStrDescricaoMdLitServico();
            $objMdLitRelNumInterServicoDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

            $objMdLitRelNumInterServicoRN = new MdLitRelNumInterServicoRN();
            $arrObjMdLitRelNumInterServicoDTO = $objMdLitRelNumInterServicoRN->listar($objMdLitRelNumInterServicoDTO);

            if($arrObjMdLitRelNumInterServicoDTO){
                foreach ($arrObjMdLitRelNumInterServicoDTO as $objMdLitRelNumInterServicoDTO) {
                    $arrResultado[$key]['nome_servico'] .= isset($arrResultado[$key]['nome_servico']) ? ',<br> '.$objMdLitRelNumInterServicoDTO->getStrDescricaoMdLitServico() : $objMdLitRelNumInterServicoDTO->getStrDescricaoMdLitServico();
                    $arrResultado[$key]['id_servico'] .= isset($arrResultado[$key]['id_servico']) ? ',� '.$objMdLitRelNumInterServicoDTO->getNumIdMdLitServico() : $objMdLitRelNumInterServicoDTO->getNumIdMdLitServico();
                }
            }

            //modalidade
            $objMdLitRelNumInterModaliDTO = new MdLitRelNumInterModaliDTO();
            $objMdLitRelNumInterModaliDTO->retStrNomeMdLitModalidade();
            $objMdLitRelNumInterModaliDTO->retNumIdMdLitModalidade();
            $objMdLitRelNumInterModaliDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

            $objMdLitRelNumInterModaliRN = new MdLitRelNumInterModaliRN();
            $arrObjMdLitRelNumInterModaliDTO = $objMdLitRelNumInterModaliRN->listar($objMdLitRelNumInterModaliDTO);

            if($arrObjMdLitRelNumInterModaliDTO){
                foreach ($arrObjMdLitRelNumInterModaliDTO as $objMdLitRelNumInterModaliDTO) {
                    $arrResultado[$key]['nome_modalidade'] .= isset($arrResultado[$key]['nome_modalidade']) ? ',<br> '.$objMdLitRelNumInterModaliDTO->getStrNomeMdLitModalidade() : $objMdLitRelNumInterModaliDTO->getStrNomeMdLitModalidade();
                    $arrResultado[$key]['id_modalidade'] .= isset($arrResultado[$key]['id_modalidade']) ? ',� '.$objMdLitRelNumInterModaliDTO->getNumIdMdLitModalidade() : $objMdLitRelNumInterModaliDTO->getNumIdMdLitModalidade();
                }
            }

            //abrangencia
            $objMdLitRelNumInterAbrangDTO = new MdLitRelNumInterAbrangDTO();
            $objMdLitRelNumInterAbrangDTO->retStrNomeMdLitAbrangencia();
            $objMdLitRelNumInterAbrangDTO->retNumIdMdLitAbrangencia();
            $objMdLitRelNumInterAbrangDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

            $objMdLitRelNumInterAbrangRN = new MdLitRelNumInterAbrangRN();
            $arrObjMdLitRelNumInterServicoDTO = $objMdLitRelNumInterAbrangRN->listar($objMdLitRelNumInterAbrangDTO);

            if($arrObjMdLitRelNumInterModaliDTO){
                foreach ($arrObjMdLitRelNumInterServicoDTO as $objMdLitRelNumInterAbrangDTO) {
                    $arrResultado[$key]['nome_abrangencia'] .= isset($arrResultado[$key]['nome_abrangencia']) ? ',<br> '.$objMdLitRelNumInterAbrangDTO->getStrNomeMdLitAbrangencia() : $objMdLitRelNumInterAbrangDTO->getStrNomeMdLitAbrangencia();
                    $arrResultado[$key]['id_abrangencia'] .= isset($arrResultado[$key]['id_abrangencia']) ? ',� '.$objMdLitRelNumInterAbrangDTO->getNumIdMdLitAbrangencia() : $objMdLitRelNumInterAbrangDTO->getNumIdMdLitAbrangencia();
                }
            }

            //Estado
            $objMdLitRelNumInterEstadoDTO = new MdLitRelNumInterEstadoDTO();
            $objMdLitRelNumInterEstadoDTO->retStrNomeEstado();
            $objMdLitRelNumInterEstadoDTO->retNumIdUf();
            $objMdLitRelNumInterEstadoDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

            $objMdLitRelNumInterEstadoRN = new MdLitRelNumInterEstadoRN();
            $arrobjMdLitRelNumInterEstadoDTO = $objMdLitRelNumInterEstadoRN->listar($objMdLitRelNumInterEstadoDTO);

            if($arrobjMdLitRelNumInterEstadoDTO){
                foreach ($arrobjMdLitRelNumInterEstadoDTO as $objMdLitRelNumInterEstadoDTO) {
                    $arrResultado[$key]['nome_estado'] .= isset($arrResultado[$key]['nome_estado']) ? ',<br> '.$objMdLitRelNumInterEstadoDTO->getStrNomeEstado() : $objMdLitRelNumInterEstadoDTO->getStrNomeEstado();
                    $arrResultado[$key]['id_estado'] .= isset($arrResultado[$key]['id_estado']) ? ',� '.$objMdLitRelNumInterEstadoDTO->getNumIdUf() : $objMdLitRelNumInterEstadoDTO->getNumIdUf();
                }
            }

            //Cidade
            $objMdLitRelNumInterCidadeDTO = new MdLitRelNumInterCidadeDTO();
            $objMdLitRelNumInterCidadeDTO->retStrNomeCidade();
            $objMdLitRelNumInterCidadeDTO->retNumIdCidade();
            $objMdLitRelNumInterCidadeDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

            $objMdLitRelNumInterCidadeRN = new MdLitRelNumInterCidadeRN();
            $arrobjMdLitRelNumInterCidadeDTO = $objMdLitRelNumInterCidadeRN->listar($objMdLitRelNumInterCidadeDTO);

            if($arrobjMdLitRelNumInterCidadeDTO){
                foreach ($arrobjMdLitRelNumInterCidadeDTO as $objMdLitRelNumInterCidadeDTO) {
                    $arrResultado[$key]['nome_cidade'] .= isset($arrResultado[$key]['nome_cidade']) ? ',<br> '.$objMdLitRelNumInterCidadeDTO->getStrNomeCidade() : $objMdLitRelNumInterCidadeDTO->getStrNomeCidade();
                    $arrResultado[$key]['id_cidade'] .= isset($arrResultado[$key]['id_cidade']) ? ',� '.$objMdLitRelNumInterCidadeDTO->getNumIdCidade() : $objMdLitRelNumInterCidadeDTO->getNumIdCidade();
                }
            }

            //buscar Lancamento
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->retTodos(false);
            $objMdLitLancamentoDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

            $objMdLitLancamentoRN = new MdLitLancamentoRN();
            $arrResultado[$key]['contar_lancamento'] = $objMdLitLancamentoRN->contar($objMdLitLancamentoDTO);
        }//var_dump($arrResultado);exit;
        return self::gerarXMLItensArr($arrResultado);
    }

}
?>