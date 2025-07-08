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
        $arrMontarSelect = array();
        $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
        $objMdLitNumeroInteressadoDTO->retNumIdMdLitNumeroInteressado();
        $objMdLitNumeroInteressadoDTO->retStrNumero();

        $objMdLitNumeroInteressadoDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);

        $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
        $arrObjMdLitDadoInteressadoDTO = $objMdLitNumeroInteressadoRN->listar($objMdLitNumeroInteressadoDTO);

        if(count($arrObjMdLitDadoInteressadoDTO)){
            $objMdLitRelNumInterServicoDTO = new MdLitRelNumInterServicoDTO();
            $objMdLitRelNumInterServicoDTO->retNumIdMdLitNumeroInteressado();
            $objMdLitRelNumInterServicoDTO->retNumIdMdLitServico();
            $objMdLitRelNumInterServicoDTO->retStrDescricaoMdLitServico();
            $objMdLitRelNumInterServicoDTO->setOrdStrDescricaoMdLitServico(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitRelNumInterServicoDTO->setNumIdMdLitNumeroInteressado(InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO,'IdMdLitNumeroInteressado'), InfraDTO::$OPER_IN);

            $objMdLitNumeroInteressadoRN = new MdLitRelNumInterServicoRN();
            $arrObjMdLitRelNumInterServicoDTO = $objMdLitNumeroInteressadoRN->listar($objMdLitRelNumInterServicoDTO);

            foreach ($arrObjMdLitDadoInteressadoDTO as $key=>$objMdLitDadoInteressadoDTO){
                $strNumero = $objMdLitDadoInteressadoDTO->getStrNumero();
                if(!$strNumero){
                    $strNumero = 'Número a ser gerado';
                }
                if(count($arrObjMdLitRelNumInterServicoDTO)){
                    $arrObjMdLitRelNumInterServicoDTOFiltro = InfraArray::filtrarArrInfraDTO($arrObjMdLitRelNumInterServicoDTO, 'IdMdLitNumeroInteressado',$objMdLitDadoInteressadoDTO->getNumIdMdLitNumeroInteressado());

                    if(count($arrObjMdLitRelNumInterServicoDTOFiltro)){
                        foreach ($arrObjMdLitRelNumInterServicoDTOFiltro as $objMdLitRelNumInterServicoDTO){
                            $arrServico = array();
                            $arrServico['atributoDescricao']    = $strNumero.' - '. $objMdLitRelNumInterServicoDTO->getStrDescricaoMdLitServico();
                            $arrServico['atributoChave']        = $objMdLitDadoInteressadoDTO->getNumIdMdLitNumeroInteressado();
                            $arrMontarSelect[] = $arrServico;
                        }
                        continue;
                    }

                }
                $arrMontarSelect[]= array('atributoDescricao' => $strNumero, 'atributoChave' =>$objMdLitDadoInteressadoDTO->getNumIdMdLitNumeroInteressado() );
            }

        }

        return self::montarSelectArrayComposto($strValorItemSelecionado, $arrMontarSelect);
    }

    public static function montarSelectArrayComposto($varValorItemSelecionado, $arr){

        $strRet =InfraINT::montarItemSelect('null','',$varValorItemSelecionado == '' || $varValorItemSelecionado == null? true : false);

        foreach($arr as $atributo){
            $bolSelecionado = false;
            if($varValorItemSelecionado !== null && $varValorItemSelecionado == $atributo['atributoChave'] ){
                $bolSelecionado = true;
            }

            $strRet .= InfraINT::montarItemSelect($atributo['atributoChave'],$atributo['atributoDescricao'],$bolSelecionado);
        }
        return $strRet;
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

        $filtroWS = self::mapearParamentroEntrada($objMdLitIntegracaoDTO,$filtro);
        $objMdlitSoapClient = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()]);

        $err = false;
        if($err) {
            return '<erros><erro descricao="' . $err . '"></erro></erros>';
        }

        if($filtroWS && $filtroWS['numeroServicos']){
            $filtroWS['numeroServicos'] = implode(',', $filtroWS['numeroServicos']);
        }

        $arrResultado = $objMdlitSoapClient->execOperacao( $objMdLitIntegracaoDTO->getStrOperacaWsdl() , ['filtro' => $filtroWS] );

        $err = is_array($arrResultado) && isset( $arrResultado['suc'] ) && $arrResultado['suc'] === false;
        if ( $err ) {
            // Define a mensagem de erro
            $txtErr = "Ocorreu um erro ao conectar com a operação ({$objMdLitIntegracaoDTO->getStrOperacaWsdl()}). <br> {$arrResultado['msg']}";

            // Escapa a mensagem para ser usada com segurança dentro de um atributo XML
            $descricaoEscapada = htmlspecialchars($txtErr, ENT_QUOTES | ENT_XML1, 'ISO-8859-1');

            // Retorna o XML com a sintaxe correta do atributo
            return '<erros><erro descricao="'.$descricaoEscapada.'"></erro></erros>';
        }

        if(empty($arrResultado) && $filtro['outorga'] == 'N'){
            return self::mensagemValidacaoNaoOutorgado($filtro);
        }elseif(empty($arrResultado)){
            return '<erros><erro descricao="Não existe resultado com os filtros informados no webservice."></erro></erros>';
        }

        if(!is_array($arrResultado['return'][0]))
            $arrResultado['return'] = [$arrResultado['return']];

        $arrResultadoParametrizado = self::mapearParamentroSaida($objMdLitIntegracaoDTO,$arrResultado);

        //removendo valores duplicados de um array multi-dimensional, o web-service esta trazendo valores com resultados duplicados
        if(is_array($arrResultadoParametrizado)) {
            $arrResultadoParametrizado = array_map("unserialize", array_unique(array_map("serialize", $arrResultadoParametrizado)));

            return self::gerarXMLItensArr($arrResultadoParametrizado);
        }
        return $arrResultadoParametrizado;
    }

    private static function mapearParamentroEntrada(MdLitIntegracaoDTO $objMdLitIntegracaoDTO,$filtro){
        $objMdLitParamEntradaRN = new MdLitMapearParamEntradaRN();
        $objMdLitParamEntradaDTO = new MdLitMapearParamEntradaDTO();
        $objMdLitParamEntradaDTO->retTodos();
        $objMdLitParamEntradaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
        $arrObjMdLitParamEntradaDTO = $objMdLitParamEntradaRN->listar($objMdLitParamEntradaDTO);
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

                    $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
                    $objMdLitModalidadeRN = new MdLitModalidadeRN();
                    $arrObjMdLitModalidadeDTO = self::buscarDTOUtilWS($objMdLitModalidadeDTO, $objMdLitModalidadeRN, array('IdMdLitModalidade'), $filtro['modalidade']);

                    if(count($arrObjMdLitModalidadeDTO) > 0){
                        $strFiltroModalidade = '';
                        foreach ($arrObjMdLitModalidadeDTO as $objMdLitModalidadeDTO){
                            if($strFiltroModalidade != '')
                                $strFiltroModalidade .= ',';

                            $strFiltroModalidade .= utf8_encode($objMdLitModalidadeDTO->getStrNome());
                        }
                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $strFiltroModalidade;
                    }

                    break;
                case MdLitNomeFuncionalRN::$TIPO_OUTORGA:
                    $objMdLitAdmTipoOutorDTO = new MdLitAdmTipoOutorDTO();
                    $objMdLitAdmTipoOutorRN = new MdLitAdmTipoOutorRN();
                    $arrObjMdLitAdmTipoOutorDTO = self::buscarDTOUtilWS($objMdLitAdmTipoOutorDTO, $objMdLitAdmTipoOutorRN, array('IdMdLitAdmTipoOutor'), $filtro['abrangencia']);

                    if(count($arrObjMdLitAdmTipoOutorDTO) > 0){
                        $strFiltroAbrangencia = '';
                        foreach ($arrObjMdLitAdmTipoOutorDTO as $objMdLitAdmTipoOutorDTO){
                            if($strFiltroAbrangencia != '')
                                $strFiltroAbrangencia .= ',';

                            $strFiltroAbrangencia .= utf8_encode($objMdLitAdmTipoOutorDTO->getStrNome());
                        }
                        $filtroWS[$objMdLitParamEntradaDTO->getStrCampo()] = $strFiltroAbrangencia;
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
        return $filtroWS;

    }

    private static function mapearParamentroSaida(MdLitIntegracaoDTO $objMdLitIntegracaoDTO,$arrResultado){

        $objMdLitParamSaidaRN = new MdLitMapearParamSaidaRN();
        $objMdLitParamSaidaDTO = new MdLitMapearParamSaidaDTO();
        $objMdLitParamSaidaDTO->retTodos();
        $objMdLitParamSaidaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
        $arrObjMdLitParamSaidaDTO = $objMdLitParamSaidaRN->listar($objMdLitParamSaidaDTO);
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
                            $arrObjMdLitServicoDTO = self::buscarDTOUtilWS($objMdLitServicoDTO, $objMdLitServicoRN, array('Codigo', 'Sigla', 'Descricao'), $resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                            if (!$arrObjMdLitServicoDTO)
                                return '<erros><erro descricao="O serviço \'' . $resultado[$objMdLitParamEntradaDTO->getStrCampo()] . '\' recuperado pelo web-service não foi cadastrado no SEI"></erro></erros>';

                            $arrItensTabela = self::gerarItensTabela($arrObjMdLitServicoDTO , 'IdMdLitServico', 'Descricao');
                            $arrResultadoParametrizado[$key]['id_servico'] = $arrItensTabela['id'];
                            $arrResultadoParametrizado[$key]['nome_servico'] = $arrItensTabela['nome'];
                        }

                        break;
                    case MdLitNomeFuncionalRN::$TIPO_OUTORGA:

                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objMdLitAdmTipoOutorDTO = new MdLitAdmTipoOutorDTO();
                            $objMdLitAdmTipoOutorRN = new MdLitAdmTipoOutorRN();

                            $arrObjMdLitAdmTipoOutorDTO = self::buscarDTOUtilWS($objMdLitAdmTipoOutorDTO, $objMdLitAdmTipoOutorRN, array('Nome'), $resultado[$objMdLitParamEntradaDTO->getStrCampo()]);

                            if (!$arrObjMdLitAdmTipoOutorDTO){
                                $objMdLitAdmTipoOutorDTO = new MdLitAdmTipoOutorDTO();
                                $objMdLitAdmTipoOutorDTO->setStrNome($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                                $objMdLitAdmTipoOutorDTO->setStrSinAtivo('S');
                                $objMdLitAdmTipoOutorDTO = $objMdLitAdmTipoOutorRN->cadastrar($objMdLitAdmTipoOutorDTO);

                                $arrObjMdLitAdmTipoOutorDTO = array($objMdLitAdmTipoOutorDTO);
                            }

                            $arrItensTabela = self::gerarItensTabela($arrObjMdLitAdmTipoOutorDTO , 'IdMdLitAdmTipoOutor', 'Nome');
                            $arrResultadoParametrizado[$key]['id_tipo_outorga'] = $arrItensTabela['id'];
                            $arrResultadoParametrizado[$key]['nome_tipo_outorga'] = $arrItensTabela['nome'];
                        }
                        break;
                    case MdLitNomeFuncionalRN::$MODALIDADE:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
                            $objMdLitModalidadeRN = new MdLitModalidadeRN();
                            $arrObjMdLitModalidadeDTO = self::buscarDTOUtilWS($objMdLitModalidadeDTO, $objMdLitModalidadeRN, array('Nome'), $resultado[$objMdLitParamEntradaDTO->getStrCampo()]);

                            if (!$arrObjMdLitModalidadeDTO){
                                $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
                                $objMdLitModalidadeDTO->setStrNome($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                                $objMdLitModalidadeDTO->setStrSinAtivo('S');
                                $objMdLitModalidadeDTO = $objMdLitModalidadeRN->cadastrar($objMdLitModalidadeDTO);

                                $arrObjMdLitModalidadeDTO = array($objMdLitModalidadeDTO);
                            }


                            $arrItensTabela = self::gerarItensTabela($arrObjMdLitModalidadeDTO , 'IdMdLitModalidade', 'Nome');
                            $arrResultadoParametrizado[$key]['id_modalidade'] = $arrItensTabela['id'];
                            $arrResultadoParametrizado[$key]['nome_modalidade'] = $arrItensTabela['nome'];
                        }
                        break;
                    case MdLitNomeFuncionalRN::$CNPJ_CPF:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $arrResultadoParametrizado[$key]['cnpj_cpf'] = $resultado[$objMdLitParamEntradaDTO->getStrCampo()];
                        }
                        break;
                }

            }
        }
        return $arrResultadoParametrizado;
    }

    private static function gerarItensTabela($arrObj, $idAtributo, $nomeAtributo){
        $id = null;
        $nome = null;
        foreach ($arrObj as $objDTO){
            if($id)
                $id .= ',€ ';

            $id .= $objDTO->get($idAtributo);

            if($nome)
                $nome .= ',<br> ';

            $nome .= $objDTO->get($nomeAtributo);

        }
        return ['id'=> $id, 'nome'=> $nome];

    }

    private static function mensagemValidacaoNaoOutorgado($filtro){
        $nomeLabelOutorga = null;
        $nomeLabelNumero = null;
        //retornando o contato para a msg de exceção
        $objContatoRN = new ContatoRN();
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->retTodos();
        $objContatoDTO->setNumIdContato($filtro['id_contato']);

        $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
        $contatoCpfCnpj = $objContatoDTO->getDblCpf() ? $objContatoDTO->getDblCpf() : $objContatoDTO->getDblCnpj();

        //retornando os nomes das labels para montar msg de exceção
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

        return '<erros><erro descricao="O Interessado '.$objContatoDTO->getStrNome().' - '.InfraUtil::formatarCpfCnpj($contatoCpfCnpj).' não possui '.$nomeLabelNumero.' gerado. Este '.$nomeLabelNumero.' será gerado no momento do Lançamento de Multa porventura aplicada."></erro></erros>';
    }

    private static function buscarDTOUtilWS(InfraDTO $objDTO, InfraRN $objRN, $criterio, $txtFiltro){
        $objDTO->retTodos();
        $operadoresAtributos = array();
        $operadoresLogico    = array();
        $valoresAtributos    = array();

        if (is_array($txtFiltro)) {
            $txtFiltro = InfraArray::valoresArrayRecursivo($txtFiltro);
            $txtFiltro = self::to_utf8($txtFiltro);

        } else {
            $txtFiltro = array($txtFiltro);
        }


        foreach ($criterio as $item) {
            $operadoresAtributos[]  = InfraDTO::$OPER_IN;
            $operadoresLogico[]     = InfraDTO::$OPER_LOGICO_OR;
            $valoresAtributos[]     = $txtFiltro;
        }
        unset($operadoresLogico[count($operadoresLogico)-1]);

        $objDTO->adicionarCriterio($criterio,
            $operadoresAtributos,
            $valoresAtributos,
            $operadoresLogico);

        return $objRN->listar($objDTO);

    }

    public static function to_utf8($data)
    {
        if (is_array($data)) {
            $out = array();
            foreach ($data as $key => $value) {
                $out[self::to_utf8($key)] = self::to_utf8($value);
            }

            return $out;
        } elseif(is_string($data)) {
            return utf8_decode($data);
        }
        return $data;
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

        $countArrObjMdLitNumeroInteressadoDTO = is_array($arrObjMdLitNumeroInteressadoDTO) ? count($arrObjMdLitNumeroInteressadoDTO) : 0;

        if($countArrObjMdLitNumeroInteressadoDTO > 0){

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
                        $arrResultado[$key]['id_servico'] .= isset($arrResultado[$key]['id_servico']) ? ', '.$objMdLitRelNumInterServicoDTO->getNumIdMdLitServico() : $objMdLitRelNumInterServicoDTO->getNumIdMdLitServico();
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
                        $arrResultado[$key]['id_modalidade'] .= isset($arrResultado[$key]['id_modalidade']) ? ', '.$objMdLitRelNumInterModaliDTO->getNumIdMdLitModalidade() : $objMdLitRelNumInterModaliDTO->getNumIdMdLitModalidade();
                    }
                }

                //abrangencia
                $objMdLitRelNumInterAbrangDTO = new MdLitRelNumInterTpOutorDTO();
                $objMdLitRelNumInterAbrangDTO->retStrNomeTipoOutorga();
                $objMdLitRelNumInterAbrangDTO->retNumIdMdLitAdmTipoOutor();
                $objMdLitRelNumInterAbrangDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

                $objMdLitRelNumInterTpOutorRN = new MdLitRelNumInterTpOutorRN();
                $arrObjMdLitRelNumInterAbrangenciaDTO = $objMdLitRelNumInterTpOutorRN->listar($objMdLitRelNumInterAbrangDTO);

                if($arrObjMdLitRelNumInterAbrangenciaDTO){
                    foreach ($arrObjMdLitRelNumInterAbrangenciaDTO as $objMdLitRelNumInterAbrangDTO) {
                        $arrResultado[$key]['nome_tipo_outorga'] .= isset($arrResultado[$key]['nome_tipo_outorga']) ? ',<br> '.$objMdLitRelNumInterAbrangDTO->getStrNomeTipoOutorga() : $objMdLitRelNumInterAbrangDTO->getStrNomeTipoOutorga();
                        $arrResultado[$key]['id_tipo_outorga'] .= isset($arrResultado[$key]['id_tipo_outorga']) ? ', '.$objMdLitRelNumInterAbrangDTO->getNumIdMdLitAdmTipoOutor() : $objMdLitRelNumInterAbrangDTO->getNumIdMdLitAdmTipoOutor();
                    }
                }


                //buscar Lancamento
                $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
                $objMdLitLancamentoDTO->retTodos(false);
                $objMdLitLancamentoDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

                $objMdLitLancamentoRN = new MdLitLancamentoRN();
                $arrResultado[$key]['contar_lancamento'] = $objMdLitLancamentoRN->contar($objMdLitLancamentoDTO);
            }
        }
        return self::gerarXMLItensArr($arrResultado);
    }

}
?>