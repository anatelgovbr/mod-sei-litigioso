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
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objMdLitServicoDTO = new MdLitServicoDTO();
                            $objMdLitServicoRN = new MdLitServicoRN();
                            $arrObjMdLitServicoDTO = self::buscarDTOUtilWS($objMdLitServicoDTO, $objMdLitServicoRN, array('Codigo', 'Sigla', 'Descricao'), utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]));
                            if (!$arrObjMdLitServicoDTO)
                                return '<erros><erro descricao="O serviço \'' . ($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) . '\' recuperado pelo web-service não foi cadastrado no SEI"></erro></erros>';

                            $arrItensTabela = self::gerarItensTabela($arrObjMdLitServicoDTO , 'IdMdLitServico', 'Descricao');
                            $arrResultadoParametrizado[$key]['id_servico'] = $arrItensTabela['id'];
                            $arrResultadoParametrizado[$key]['nome_servico'] = $arrItensTabela['nome'];
                        }

                        break;
                    case MdLitNomeFuncionalRN::$ABRANGENCIA:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objMdLitAbrangenciaDTO = new MdLitAbrangenciaDTO();
                            $objMdLitAbrangenciaRN = new MdLitAbrangenciaRN();
                            $arrObjMdLitAbrangenciaDTO = self::buscarDTOUtilWS($objMdLitAbrangenciaDTO, $objMdLitAbrangenciaRN, array('Nome'), $resultado[$objMdLitParamEntradaDTO->getStrCampo()]);

                            if (!$arrObjMdLitAbrangenciaDTO){
                                continue;
                            }

                            $arrItensTabela = self::gerarItensTabela($arrObjMdLitAbrangenciaDTO , 'IdMdLitAbrangencia', 'Nome');
                            $arrResultadoParametrizado[$key]['id_abrangencia'] = $arrItensTabela['id'];
                            $arrResultadoParametrizado[$key]['nome_abrangencia'] = $arrItensTabela['nome'];
                        }
                        break;
                    case MdLitNomeFuncionalRN::$MODALIDADE:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
                            $objMdLitModalidadeRN = new MdLitModalidadeRN();
                            $arrObjMdLitModalidadeDTO = self::buscarDTOUtilWS($objMdLitModalidadeDTO, $objMdLitModalidadeRN, array('Nome'), $resultado[$objMdLitParamEntradaDTO->getStrCampo()]);

                            if (!$arrObjMdLitModalidadeDTO)
                                continue;


                            $arrItensTabela = self::gerarItensTabela($arrObjMdLitModalidadeDTO , 'IdMdLitModalidade', 'Nome');
                            $arrResultadoParametrizado[$key]['id_modalidade'] = $arrItensTabela['id'];
                            $arrResultadoParametrizado[$key]['nome_modalidade'] = $arrItensTabela['nome'];
                        }
                        break;
                    case MdLitNomeFuncionalRN::$CNPJ_CPF:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $arrResultadoParametrizado[$key]['cnpj_cpf'] = utf8_decode($resultado[$objMdLitParamEntradaDTO->getStrCampo()]);
                        }
                        break;
                    case MdLitNomeFuncionalRN::$ESTADO:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {

                            $objUfDTO = new UfDTO();
                            $objUfRN = new UfRN();
                            $arrObjUfDTO = self::buscarDTOUtilWS($objUfDTO, $objUfRN, array('Sigla', 'Nome'), $resultado[$objMdLitParamEntradaDTO->getStrCampo()]);

                            if (!$arrObjUfDTO)
                                continue;


                            $arrItensTabela = self::gerarItensTabela($arrObjUfDTO , 'IdUf', 'Nome');
                            $arrResultadoParametrizado[$key]['id_estado'] = $arrItensTabela['id'];
                            $arrResultadoParametrizado[$key]['nome_estado'] = $arrItensTabela['nome'];
                        }
                        break;
                    case MdLitNomeFuncionalRN::$CIDADE:
                        if($resultado[$objMdLitParamEntradaDTO->getStrCampo()]) {
                            $objCidadeDTO = new CidadeDTO();
                            $objCidadeRN = new CidadeRN();
                            $arrObjCidadeDTO = self::buscarDTOUtilWS($objCidadeDTO, $objCidadeRN, array('Nome'), $resultado[$objMdLitParamEntradaDTO->getStrCampo()]);

                            if (!$arrObjCidadeDTO)
                                continue;


                            $arrItensTabela = self::gerarItensTabela($arrObjCidadeDTO , 'IdCidade', 'Nome');
                            $arrResultadoParametrizado[$key]['id_cidade'] = $arrItensTabela['id'];
                            $arrResultadoParametrizado[$key]['nome_cidade'] = $arrItensTabela['nome'];
                        }
                        break;
                }

            }
        }
        return self::gerarXMLItensArr($arrResultadoParametrizado);
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

        //listar estado não usa o padrão de metodos listar
        if($objRN instanceof UfRN){
            return $objRN->listarRN0401($objDTO);
        }

        //listar cidade não usa o padrão de metodos listar
        if($objRN instanceof CidadeRN){
            return $objRN->listarRN0410($objDTO);
        }

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
                    $arrResultado[$key]['id_servico'] .= isset($arrResultado[$key]['id_servico']) ? ',€ '.$objMdLitRelNumInterServicoDTO->getNumIdMdLitServico() : $objMdLitRelNumInterServicoDTO->getNumIdMdLitServico();
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
                    $arrResultado[$key]['id_modalidade'] .= isset($arrResultado[$key]['id_modalidade']) ? ',€ '.$objMdLitRelNumInterModaliDTO->getNumIdMdLitModalidade() : $objMdLitRelNumInterModaliDTO->getNumIdMdLitModalidade();
                }
            }

            //abrangencia
            $objMdLitRelNumInterAbrangDTO = new MdLitRelNumInterAbrangDTO();
            $objMdLitRelNumInterAbrangDTO->retStrNomeMdLitAbrangencia();
            $objMdLitRelNumInterAbrangDTO->retNumIdMdLitAbrangencia();
            $objMdLitRelNumInterAbrangDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

            $objMdLitRelNumInterAbrangRN = new MdLitRelNumInterAbrangRN();
            $arrObjMdLitRelNumInterAbrangenciaDTO = $objMdLitRelNumInterAbrangRN->listar($objMdLitRelNumInterAbrangDTO);

            if($arrObjMdLitRelNumInterAbrangenciaDTO){
                foreach ($arrObjMdLitRelNumInterAbrangenciaDTO as $objMdLitRelNumInterAbrangDTO) {
                    $arrResultado[$key]['nome_abrangencia'] .= isset($arrResultado[$key]['nome_abrangencia']) ? ',<br> '.$objMdLitRelNumInterAbrangDTO->getStrNomeMdLitAbrangencia() : $objMdLitRelNumInterAbrangDTO->getStrNomeMdLitAbrangencia();
                    $arrResultado[$key]['id_abrangencia'] .= isset($arrResultado[$key]['id_abrangencia']) ? ',€ '.$objMdLitRelNumInterAbrangDTO->getNumIdMdLitAbrangencia() : $objMdLitRelNumInterAbrangDTO->getNumIdMdLitAbrangencia();
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
                    $arrResultado[$key]['id_estado'] .= isset($arrResultado[$key]['id_estado']) ? ',€ '.$objMdLitRelNumInterEstadoDTO->getNumIdUf() : $objMdLitRelNumInterEstadoDTO->getNumIdUf();
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
                    $arrResultado[$key]['id_cidade'] .= isset($arrResultado[$key]['id_cidade']) ? ',€ '.$objMdLitRelNumInterCidadeDTO->getNumIdCidade() : $objMdLitRelNumInterCidadeDTO->getNumIdCidade();
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