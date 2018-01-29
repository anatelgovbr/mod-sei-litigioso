<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitDadoInteressadoINT extends InfraINT {

  public static function montarSelectIdParticipante($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitControle='', $numIdParticipante='', $numNumeroInteressado=null ){
    $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
    $objMdLitDadoInteressadoDTO->retTodos(true);
    $objMdLitDadoInteressadoDTO->retNumIdMdLitDadoInteressado();
    $objMdLitDadoInteressadoDTO->retNumIdParticipante();

    if ($numIdMdLitControle!==''){
      $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($numIdMdLitControle);
    }

    if ($numIdParticipante!==''){
      $objMdLitDadoInteressadoDTO->setNumIdParticipante($numIdParticipante);
    }

//      if ($numNumeroInteressado!==''){
//          $objMdLitDadoInteressadoDTO->setStrNumero($numNumeroInteressado);
//      }

    $objMdLitDadoInteressadoDTO->setOrdStrNomeContatoParticipante(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
    $arrObjMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->listar($objMdLitDadoInteressadoDTO);
    $arrObjMdLitDadoInteressadoDTO = self::retirarDuplicado($arrObjMdLitDadoInteressadoDTO,'IdParticipante');

      if($numNumeroInteressado != null && $strValorItemSelecionado == ''){
          $arrValorSelecionado = InfraArray::filtrarArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'Numero',$numNumeroInteressado);
          $strValorItemSelecionado = count($arrValorSelecionado) ? $arrValorSelecionado[0]->getNumIdParticipante(): '';
      }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitDadoInteressadoDTO, 'IdParticipante', 'NomeContatoParticipante');
  }

    public static function montarSelectNumero($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdParticipante=''){
        $objMdLitRelDadoInterServicoDTO = new MdLitRelDadoInterServicoDTO();
        $objMdLitRelDadoInterServicoDTO->retNumIdMdLitDadoInteressado();
        $objMdLitRelDadoInterServicoDTO->retNumIdParticipanteMdLitDadoInteressado();
        $objMdLitRelDadoInterServicoDTO->retStrNumeroMdLitDadoInteressado();
        $objMdLitRelDadoInterServicoDTO->retNumIdMdLitServico();
        $objMdLitRelDadoInterServicoDTO->retStrDescricaoMdLitServico();

        $objMdLitRelDadoInterServicoDTO->setNumIdParticipanteMdLitDadoInteressado($numIdParticipante);

        $objMdLitRelDadoInterServicoDTO->setOrdStrDescricaoMdLitServico(InfraDTO::$TIPO_ORDENACAO_ASC);
        
        $objMdLitRelDadoInterServicoRN = new MdLitRelDadoInterServicoRN();
        $arrObjMdLitDadoInteressadoDTO = $objMdLitRelDadoInterServicoRN->listar($objMdLitRelDadoInterServicoDTO);

        foreach ($arrObjMdLitDadoInteressadoDTO as $objMdLitRelDadoInterServicoDTO){
            $objMdLitRelDadoInterServicoDTO->setStrNumeroComServico($objMdLitRelDadoInterServicoDTO->getStrNumeroMdLitDadoInteressado().' - '. $objMdLitRelDadoInterServicoDTO->getStrDescricaoMdLitServico());
        }

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitDadoInteressadoDTO, 'IdMdLitDadoInteressado', 'NumeroComServico');
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
                        $filtro['servico'] = $filtro['servico'][0];//todo retirar essa linha quando web-service estiver completo
                        $objMdLitServicoDTO = new MdLitServicoDTO();
                        $objMdLitServicoRN = new MdLitServicoRN();
                        $objMdLitServicoDTO->retTodos();
                        $objMdLitServicoDTO->setNumIdMdLitServico($filtro['servico'][0]);
                        $objMdLitServicoDTO = $objMdLitServicoRN->consultar($objMdLitServicoDTO);

                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $objMdLitServicoDTO->getStrCodigo();
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
                    if(isset($filtro['cidades'])) {
                        $filtro['estados'] = PaginaSEI::getInstance()->getArrItensTabelaDinamica($filtro['estados']);
                        $arrEstados = array();
                        foreach ($filtro['estados'] as $estado){
                            $arrEstados[] =  $estado[1];
                        }
                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $arrEstados;
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

        if(empty($arrResultado)){
            return '<erros><erro descricao="Não existe resultado com os filtros informados no webservice."></erro></erros>';
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

                        $objMdLitServicoDTO = new MdLitServicoDTO();
                        $objMdLitServicoRN = new MdLitServicoRN();
                        $objMdLitServicoDTO = self::buscarDTOUtilWS($objMdLitServicoDTO, $objMdLitServicoRN, array('Codigo', 'Sigla', 'Descricao'), utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]));
                        if(!$objMdLitServicoDTO)
                            return '<erros><erro descricao="O serviço \''. utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]).'\' recuperado pelo web-service não foi cadastrado no SEI"></erro></erros>';

                        $arrResultadoParametrizado[$key]['id_servico'] = $objMdLitServicoDTO->getNumIdMdLitServico();
                        $arrResultadoParametrizado[$key]['nome_servico'] = $objMdLitServicoDTO->getStrDescricao();

                        break;
                    case MdLitNomeFuncionalRN::$ABRANGENCIA:
                        $objMdLitAbrangenciaDTO = new MdLitAbrangenciaDTO();
                        $objMdLitAbrangenciaRN = new MdLitAbrangenciaRN();
                        $objMdLitAbrangenciaDTO = self::buscarDTOUtilWS($objMdLitAbrangenciaDTO, $objMdLitAbrangenciaRN, array('IdMdLitAbrangencia', 'Nome'), utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]));

                        if(!$objMdLitAbrangenciaDTO)
                            return '<erros><erro descricao="a abrangência \''. utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]).'\' recuperado pelo web-service não foi cadastrado no SEI"></erro></erros>';

                        $arrResultadoParametrizado[$key]['id_abrangencia'] = $objMdLitAbrangenciaDTO->getNumIdMdLitAbrangencia();
                        $arrResultadoParametrizado[$key]['nome_abrangencia'] = $objMdLitServicoDTO->getStrNome();
                        break;
                    case MdLitNomeFuncionalRN::$MODALIDADE:
                        $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
                        $objMdLitModalidadeRN = new MdLitModalidadeRN();
                        $objMdLitModalidadeDTO = self::buscarDTOUtilWS($objMdLitModalidadeDTO, $objMdLitModalidadeRN, array('IdMdLitModalidade', 'Nome'), utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]));

                        if(!$objMdLitModalidadeDTO)
                            return '<erros><erro descricao="a modalidade \''. utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]).'\' recuperado pelo web-service não foi cadastrado no SEI"></erro></erros>';

                        $arrResultadoParametrizado[$key]['id_modalidade'] = $objMdLitModalidadeDTO->getNumIdMdLitModalidade();
                        $arrResultadoParametrizado[$key]['nome_modalidade'] = $objMdLitModalidadeDTO->getStrNome();
                        break;
                    case MdLitNomeFuncionalRN::$CNPJ_CPF:
                        $arrResultadoParametrizado[$key]['cnpj_cpf'] = utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                        break;
                    case MdLitNomeFuncionalRN::$ESTADO:
                        $arrResultadoParametrizado[$key]['estado'] = utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                        break;
                    case MdLitNomeFuncionalRN::$CIDADE:
                        $arrResultadoParametrizado[$key]['cidade'] = utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                        break;
                }

            }
        }
        return self::gerarXMLItensArr($arrResultadoParametrizado);
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
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdParticipante();
        $objParticipanteDTO->retNumIdContato();
        $objParticipanteDTO->setDblIdProtocolo($idProcedimento);
        $objParticipanteDTO->setNumIdContato($idContato);
        $objParticipanteDTO->setStrStaParticipacao(array(ParticipanteRN::$TP_INTERESSADO), InfraDTO::$OPER_IN);

        $objParticipanteRN     = new ParticipanteRN();
        $objParticipanteDTO    = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

        $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
        $objMdLitDadoInteressadoDTO->retTodos(true);
        $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);
        $objMdLitDadoInteressadoDTO->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());
        $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);

        $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
        $arrObjMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->listar($objMdLitDadoInteressadoDTO);

        foreach ($arrObjMdLitDadoInteressadoDTO as $key=>$objMdLitDadoInteressadoDTO) {

            $arrResultado[$key]['numero']       = $objMdLitDadoInteressadoDTO->getStrNumero();
            $arrResultado[$key]['outorgado']    = $objMdLitDadoInteressadoDTO->getStrSinOutorgado();
            $arrResultado[$key]['id_md_lit_dado_interessado']    = $objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado();
            $arrResultado[$key]['id_contato']    = $objParticipanteDTO->getNumIdContato();

            //servico
            $objMdLitRelDadoInterServicoDTO = new MdLitRelDadoInterServicoDTO();
            $objMdLitRelDadoInterServicoDTO->retNumIdMdLitServico();
            $objMdLitRelDadoInterServicoDTO->retStrDescricaoMdLitServico();
            $objMdLitRelDadoInterServicoDTO->setNumIdMdLitDadoInteressado($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado());

            $objMdLitRelDadoInterServicoRN = new MdLitRelDadoInterServicoRN();
            $arrObjMdLitRelDadoInterServicoDTO = $objMdLitRelDadoInterServicoRN->listar($objMdLitRelDadoInterServicoDTO);

            if($arrObjMdLitDadoInteressadoDTO){
                foreach ($arrObjMdLitRelDadoInterServicoDTO as $objMdLitRelDadoInterServicoDTO) {
                    $arrResultado[$key]['nome_servico'] .= isset($arrResultado[$key]['nome_servico']) ? ',<br> '.$objMdLitRelDadoInterServicoDTO->getStrDescricaoMdLitServico() : $objMdLitRelDadoInterServicoDTO->getStrDescricaoMdLitServico();
                    $arrResultado[$key]['id_servico'] .= isset($arrResultado[$key]['id_servico']) ? ',€ '.$objMdLitRelDadoInterServicoDTO->getNumIdMdLitServico() : $objMdLitRelDadoInterServicoDTO->getNumIdMdLitServico();
                }
            }

            //modalidade
            $objMdLitRelDadoInterModaliDTO = new MdLitRelDadoInterModaliDTO();
            $objMdLitRelDadoInterModaliDTO->retStrNomeMdLitModalidade();
            $objMdLitRelDadoInterModaliDTO->retNumIdMdLitModalidade();
            $objMdLitRelDadoInterModaliDTO->setNumIdMdLitDadoInteressado($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado());

            $objMdLitRelDadoInterModaliRN = new MdLitRelDadoInterModaliRN();
            $arrObjMdLitRelDadoInterModaliDTO = $objMdLitRelDadoInterModaliRN->listar($objMdLitRelDadoInterModaliDTO);

            if($arrObjMdLitRelDadoInterModaliDTO){
                foreach ($arrObjMdLitRelDadoInterModaliDTO as $objMdLitRelDadoInterModaliDTO) {
                    $arrResultado[$key]['nome_modalidade'] .= isset($arrResultado[$key]['nome_modalidade']) ? ',<br> '.$objMdLitRelDadoInterModaliDTO->getStrNomeMdLitModalidade() : $objMdLitRelDadoInterModaliDTO->getStrNomeMdLitModalidade();
                    $arrResultado[$key]['id_modalidade'] .= isset($arrResultado[$key]['id_modalidade']) ? ',€ '.$objMdLitRelDadoInterModaliDTO->getNumIdMdLitModalidade() : $objMdLitRelDadoInterModaliDTO->getNumIdMdLitModalidade();
                }
            }

            //abrangencia
            $objMdLitRelDadoInterAbrangDTO = new MdLitRelDadoInterAbrangDTO();
            $objMdLitRelDadoInterAbrangDTO->retStrNomeMdLitAbrangencia();
            $objMdLitRelDadoInterAbrangDTO->retNumIdMdLitAbrangencia();
            $objMdLitRelDadoInterAbrangDTO->setNumIdMdLitDadoInteressado($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado());

            $objMdLitRelDadoInterAbrangRN = new MdLitRelDadoInterAbrangRN();
            $arrObjMdLitRelDadoInterServicoDTO = $objMdLitRelDadoInterAbrangRN->listar($objMdLitRelDadoInterAbrangDTO);

            if($arrObjMdLitRelDadoInterModaliDTO){
                foreach ($arrObjMdLitRelDadoInterServicoDTO as $objMdLitRelDadoInterAbrangDTO) {
                    $arrResultado[$key]['nome_abrangencia'] .= isset($arrResultado[$key]['nome_abrangencia']) ? ',<br> '.$objMdLitRelDadoInterAbrangDTO->getStrNomeMdLitAbrangencia() : $objMdLitRelDadoInterAbrangDTO->getStrNomeMdLitAbrangencia();
                    $arrResultado[$key]['id_abrangencia'] .= isset($arrResultado[$key]['id_abrangencia']) ? ',€ '.$objMdLitRelDadoInterAbrangDTO->getNumIdMdLitAbrangencia() : $objMdLitRelDadoInterAbrangDTO->getNumIdMdLitAbrangencia();
                }
            }

            //Estado
            $objMdLitRelDadoInterEstadoDTO = new MdLitRelDadoInterEstadoDTO();
            $objMdLitRelDadoInterEstadoDTO->retStrNomeEstado();
            $objMdLitRelDadoInterEstadoDTO->retNumIdUf();
            $objMdLitRelDadoInterEstadoDTO->setNumIdMdLitDadoInteressado($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado());

            $objMdLitRelDadoInterEstadoRN = new MdLitRelDadoInterEstadoRN();
            $arrobjMdLitRelDadoInterEstadoDTO = $objMdLitRelDadoInterEstadoRN->listar($objMdLitRelDadoInterEstadoDTO);

            if($arrobjMdLitRelDadoInterEstadoDTO){
                foreach ($arrobjMdLitRelDadoInterEstadoDTO as $objMdLitRelDadoInterEstadoDTO) {
                    $arrResultado[$key]['nome_estado'] .= isset($arrResultado[$key]['nome_estado']) ? ',<br> '.$objMdLitRelDadoInterEstadoDTO->getStrNomeEstado() : $objMdLitRelDadoInterEstadoDTO->getStrNomeEstado();
                    $arrResultado[$key]['id_estado'] .= isset($arrResultado[$key]['id_estado']) ? ',€ '.$objMdLitRelDadoInterEstadoDTO->getNumIdUf() : $objMdLitRelDadoInterEstadoDTO->getNumIdUf();
                }
            }

            //Cidade
            $objMdLitRelDadoInterCidadeDTO = new MdLitRelDadoInterCidadeDTO();
            $objMdLitRelDadoInterCidadeDTO->retStrNomeCidade();
            $objMdLitRelDadoInterCidadeDTO->retNumIdCidade();
            $objMdLitRelDadoInterCidadeDTO->setNumIdMdLitDadoInteressado($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado());

            $objMdLitRelDadoInterCidadeRN = new MdLitRelDadoInterCidadeRN();
            $arrobjMdLitRelDadoInterCidadeDTO = $objMdLitRelDadoInterCidadeRN->listar($objMdLitRelDadoInterCidadeDTO);

            if($arrobjMdLitRelDadoInterCidadeDTO){
                foreach ($arrobjMdLitRelDadoInterCidadeDTO as $objMdLitRelDadoInterCidadeDTO) {
                    $arrResultado[$key]['nome_cidade'] .= isset($arrResultado[$key]['nome_cidade']) ? ',<br> '.$objMdLitRelDadoInterCidadeDTO->getStrNomeCidade() : $objMdLitRelDadoInterCidadeDTO->getStrNomeCidade();
                    $arrResultado[$key]['id_cidade'] .= isset($arrResultado[$key]['id_cidade']) ? ',€ '.$objMdLitRelDadoInterCidadeDTO->getNumIdCidade() : $objMdLitRelDadoInterCidadeDTO->getNumIdCidade();
                }
            }
        }
        return self::gerarXMLItensArr($arrResultado);
    }

}
?>