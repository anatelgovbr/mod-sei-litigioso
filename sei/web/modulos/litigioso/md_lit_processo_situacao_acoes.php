<?php
/**
 * ANATEL
 *
 * 22/06/2017 - criado por jaqueline.mendes - CAST
 *
 */
//InfraDebug::getInstance()->setBolLigado(true);
//InfraDebug::getInstance()->setBolDebugInfra(false);
//InfraDebug::getInstance()->limpar();

//Init Vars
$txtSituacao     = '';
$strGridSituacao = '';
$strGridDecisao  = '';
$objMdLitControleDTO     = null;
$objMdLitTipoControleDTO = null;
$diferencaEntreDias = '';
$urlCadastrarDecisao = '';
$urlHistoricoDecisao = '';
$idSerie           = '';
$dtUltimaSituacao  = '';
$dtIntercorrente   = '';
$dtQuinquenal      = '';
$strIsGestor       = '';
//inputs fieldset multa
$dataDecisaoAplicacaoMulta  = null;
$dataDecursoPrazoDefesa     = null;
$dataIntimacaoDecisao       = null;
$dataVencimento             = null;
$numInteressado             = null;
$txtDtConstituicao          = null;
$txtDtIntimacaoConstituicao = null;
$dtaConstituicaoDefinitiva  = null;
$dtaIntimacaoDefinitiva     = null;
$idMdLitProcessoSituacaoPrimeiraIntimacao = null;
$idSituacaoConclusivaParametrizada = '';

//Rns
$objMdLitTipoControleRN     = new MdLitTipoControleRN();
$objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
$objMdLitPermissaoLitRN     = new MdLitPermissaoLitigiosoRN();
$objMdLitCancelaLancRN      = new MdLitCancelaLancamentoRN();
$objMdLitLancamentoRN       = new MdLitLancamentoRN();


// Url Ajax Carregar Dependencia Fases x Situações
$strLinkAjaxFasesSituacao    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_situacoes_filtrar_select');

//URL Ajax Validar se o Documento Possui essa situação
$strUrlAjaxValidarSituacao   = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_vinculo_situacao_doc');

//verifica se existe dependencias de lancamentos para cancelar
$strVerificarDependencias   = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_dependencias_lancamentos_situacao');

//verifica se existe dependencias de decisoes preenchidaspara cancelar
$strVerificarDependenciasDecisoesPreenchidas   = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_dependencias_decisoes_situacao');

//URL Ajax Validar Número SEI
$strUrlAjaxNumeroSei              = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_numero_sei_situacao');

//Url Ajax Combo Situação
$strUrlAjaxChangeSituacao         = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_carregar_dados_situacao');

//Url Modal Intercorrente
$strLinkModalDtIntercorrente      = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_situacao_cadastro_hist_int_listar&id_procedimento='.$idProcedimento);

//Url Modal Quinquenal
$strLinkModalDtQuinquenal         = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_situacao_cadastro_hist_quin_listar&id_procedimento='.$idProcedimento);

//Url Modal combo Numero do complemento do interessado
$strLinkComboNumeroInteressado    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_buscar_numero');

//URL consulta extrato da multa
$strLinkAjaxConsultarExtratoMulta = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_consulta_extrato_multa');

//Url Modal Histórico de Lançamento
$strLinkModalHistLanc              = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_historic_lancamento_listar&id_procedimento='.$idProcedimento);

//Url Modal Cancelar Lançamento
$strLinkModalCancelarLancamento    = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_cancelar&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO);


switch($_GET['acao']) {

    case 'md_lit_processo_situacao_cadastrar':
        $objMdLitDecisaoRN = new MdLitDecisaoRN();

        if($openProcesso != '1') {
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarSituacao" id="sbmCadastrarSituacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        }

        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento=' . $_GET['id_procedimento'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

        if (isset($_POST['sbmCadastrarSituacao'])) {
            try {
                //começa a cadastrar
                $objMdLitProcessoSituacaoRN->processarCadastrar($_POST);
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento . '&atualizar_arvore=1&id_documento=' . $idDocumento));
                die;
            }catch (Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }

        //Verifica Situações que possuem Vinculo com Decisão
        $arrVincDecisaoSit = $objMdLitDecisaoRN->verificarVinculoSituacaoDecisao($idProcedimento);

        //Preencher Grid Situação
        $arrGridSitu = $objMdLitProcessoSituacaoRN->retornaDadosSituacoesCadastradas(array($idProcedimento, $idTpControle));

        //Data da última situação conclusiva cadastrada
        $dtUltimaSitConclusiva = $objMdLitProcessoSituacaoRN->buscarDtSituacaoConclusiva($idProcedimento);
        $dtaConstituicaoDefinitiva = $dtUltimaSitConclusiva;
        $dtaIntimacaoDefinitiva    = $dtUltimaSitConclusiva;
        
      //  $arrGridSit = array();
        $strGridSituacao = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGridSitu);
        
        //Preencher Grid Decisão
        $strGridDecisao = $objMdLitDecisaoRN->retornaDadosDecisaoCadastradas($idProcedimento);

        $blnDecisaoMultaIntegracao = false;
        $objMdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
        if($strGridDecisao != '') {
            foreach ($strGridDecisao as $arrDesicao) {
                $arrEspeciesDecisao['arrEspeciesId'][] = $arrDesicao[3];
            }

            $arrTipoEspecieDesicao = InfraArray::converterArrInfraDTO($objMdLitEspecieDecisaoRN->getEspecieDecisoesById($arrEspeciesDecisao), 'StaTipoIndicacaoMulta');
            //flag para saber se a decisao possui multa por integracao
            $blnDecisaoMultaIntegracao = in_array(MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO, $arrTipoEspecieDesicao);
        }

        $strGridDecisao = PaginaSEI::getInstance()->gerarItensTabelaDinamica($strGridDecisao);

        $objMdLitTipoControleDTO = $objMdLitTipoControleRN->getObjTipoControlePorId($idTpControle);

        $objMdLitControleDTO = new MdLitControleDTO();
        $objMdLitControleDTO->retTodos();
        $objMdLitControleDTO->setDblIdProcedimento($idProcedimento);

        $objMdLitControleRN = new MdLitControleRN();
        $objMdLitControleDTO = $objMdLitControleRN->consultar($objMdLitControleDTO);

        if($objMdLitControleDTO){
            $urlCadastrarDecisao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_decisao_cadastrar&id_md_lit_controle='.$objMdLitControleDTO->getNumIdControleLitigioso().'&id_procedimento='.$idProcedimento);
            $urlHistoricoDecisao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_decisao_historico&id_md_lit_controle='.$objMdLitControleDTO->getNumIdControleLitigioso());
            $urlModalReincidenciaEspecifica = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_modal_relatorio_reincidencia&id_md_lit_controle='.$objMdLitControleDTO->getNumIdControleLitigioso().'&id_procedimento='.$idProcedimento);
            $urlModalAntecedente = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_modal_relatorio_antecedente&id_md_lit_controle='.$objMdLitControleDTO->getNumIdControleLitigioso().'&id_procedimento='.$idProcedimento);
        }

        //buscando a intimação da instauração( a intimação da instauração será sempre a primeira situação de intimação depois da instauração)
        $objMdLitProcessoSituacaoIntimacaoInstauracao = $objMdLitProcessoSituacaoRN->consultarPrimeiraIntimacao($idProcedimento);
        if($objMdLitProcessoSituacaoIntimacaoInstauracao)
            $diferencaEntreDias = MdLitProcessoSituacaoINT::diferencaEntreDias($objMdLitProcessoSituacaoIntimacaoInstauracao->getDtaData());

        $dadosSituacao = $objMdLitProcessoSituacaoRN->buscarDadosDocumento(array($idDocumento));

        $erro =  array_key_exists('erro', $dadosSituacao) && $dadosSituacao['erro'] == true;
        
        if($erro){
            $dadosSituacao = array();
        }
        
        $dadosUndUs   = $objMdLitProcessoSituacaoRN->getDadosUnidadeUsuarioLogado();

        $strSelFases   = MdLitFaseINT::montarSelectNomeDisplayNone(null, null, null, $idTpControle);
        $idSerie       = array_key_exists('idSerie', $dadosSituacao) ? $dadosSituacao['idSerie'] : '';

        $dtUltimaSituacao   =  $objMdLitProcessoSituacaoRN->getDtUltimaSituacao($idProcedimento);
        $dtMenorIntercorret =  $objMdLitProcessoSituacaoRN->getDtMenorInterQuinquenal(array($idProcedimento, true));
        $dtMenorQuinquenal  =  $objMdLitProcessoSituacaoRN->getDtMenorInterQuinquenal(array($idProcedimento, false));

        //Get datas Intercorrente e Quinquenal
        $dtIntercorrente   = $objMdLitProcessoSituacaoRN->getDtIntercorrenteQuinquenal(array($idProcedimento, true));
        $dtQuinquenal      = $objMdLitProcessoSituacaoRN->getDtIntercorrenteQuinquenal(array($idProcedimento, false));

        //Get Gestor
        $isGestor = $objMdLitPermissaoLitRN->isUsuarioGestorTipoControleDTO(array(SessaoSEI::getInstance()->getNumIdUsuario(), $idTpControle));
        $strIsGestor = $isGestor ? '1' : '0';
        $isExcluirSituacao = SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_excluir');
        $isAlterarSituacao = SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_alterar');

        $isIntInstauracao = $objMdLitProcessoSituacaoRN->getIsInstauracaoIntimacaoPorTipoControle(array($idTpControle, $idProcedimento));

        $strComboCreditoProcesso = MdLitLancamentoINT::montarSelectCreditosProcesso($idProcedimento);

        //lançamento do crédito
        $idLancamentoSelecionado = null;

        //Botão "Vincular Lancamento" deve ser exibido somente se o checkbox "Habilitar Funcionalidade de Vinculação de Lançamento
        // Pré Existente" estiver ativo no mapeamento da integração sob a funcionalidade "Arrecadação Consulta Lançamento"

        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoDTO->retTodos();
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);
        $objMdLitIntegracaoDTO->setStrSinAtivo('S');
        $objMdLitIntegracaoDTO->setNumMaxRegistrosRetorno(1);

        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultar($objMdLitIntegracaoDTO);

        $isVincularLancamento = false;
        if($objMdLitIntegracaoDTO && $objMdLitIntegracaoDTO->getStrSinVincularLancamento() == 'S'){
            $isVincularLancamento = true;
        }

        try {
            $objMdLitLancamentoDTO = $objMdLitLancamentoRN->atualizarLancamento($idProcedimento, $idLancamentoSelecionado);
        }catch (Exception $e){
            PaginaSEI::getInstance()->processarExcecao($e);
        }

        //combo interessado
        $numInteressado = $objMdLitLancamentoDTO ? $objMdLitLancamentoDTO->getStrNumeroInteressado(): null;
        $numIdMdLitNumeroInteressado = $objMdLitLancamentoDTO ? $objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado(): null;
        $strComboInteressado = MdLitDadoInteressadoINT::montarSelectIdContato('null', '&nbsp;', '', $objMdLitControleDTO->getNumIdControleLitigioso(), $numIdMdLitNumeroInteressado);



        //recuperar a ultima decisao
        $dataDecursoPrazoDefesa = $objMdLitProcessoSituacaoRN->buscarDataDecursoPrazoDefesa($idProcedimento);
        $objMdLitProcessoSituacaoDecisoriaDTO = $objMdLitProcessoSituacaoRN->buscarUltimaSituacaoDecisoria($idProcedimento);

        if($objMdLitLancamentoDTO){
            $dataDecisaoAplicacaoMulta  = $objMdLitLancamentoDTO->getDtaDecisao();
            $dataVencimento             = $objMdLitLancamentoDTO->getDtaVencimento();
            $dataDecursoPrazoDefesa     = $objMdLitLancamentoDTO->getDtaPrazoDefesa();

            $dtaConstituicaoDefinitiva  = $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva();
            $dtaIntimacaoDefinitiva     = $objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva();

        }elseif($objMdLitProcessoSituacaoDecisoriaDTO) {
            $dataDecisaoAplicacaoMulta = $objMdLitProcessoSituacaoDecisoriaDTO->getDtaData();
            $dataVencimento = InfraData::calcularData(40, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dataDecisaoAplicacaoMulta);
        }

        //retorna parametros de entrada mapeados para funcionalidade de RetificarLançamento
        $objMdLitMapearParamEntradaDTO    = new MdLitMapearParamEntradaDTO();
        $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
        $objMdLitMapearParamEntradaDTO->retTodos();

        $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao(MdLitMapearParamEntradaRN::$ID_PARAM_INTEGRACAO['RETIFICAR_LANCAMENTO']);
        $objMdLitMapearParamEntradaDTO->setOrdStrCampo(InfraDTO::$TIPO_ORDENACAO_ASC);
        $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);
        $arrObjMdLitMapearParamEntrada = $arrObjMdLitMapearParamEntradaDTO;

        //retorna todos os campos mapeados para entrda no webservice
        $arrCampoMapeaParamEntrada = array();

        if(count($arrObjMdLitMapearParamEntrada) > 0){
            foreach( $arrObjMdLitMapearParamEntrada as $paramEntrada){
                array_push($arrCampoMapeaParamEntrada, $paramEntrada->getStrCampo());
            }
        }

        //buscar a primeira intimação para alterar a situação
        $objMdLitProcessoSituacaoPrimeiraIntimacaoDTO = $objMdLitProcessoSituacaoRN->consultarPrimeiraIntimacao($idProcedimento);
        if($objMdLitProcessoSituacaoPrimeiraIntimacaoDTO)
            $idMdLitProcessoSituacaoPrimeiraIntimacao = $objMdLitProcessoSituacaoPrimeiraIntimacaoDTO->getNumIdMdLitProcessoSituacao();

    break;

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        break;
    //endregion
}
//Url Modal Parametrizar Situacao
$strLinkModalParametrizarSituacao    = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_visualizar_parametrizar&id_tipo_processo_litigioso='.$idTpControle);

$objMdLitSituacaoDTO = new MdLitSituacaoDTO();
$objMdLitSituacaoDTO->retTodos();
$objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($idTpControle);
$objMdLitSituacaoDTO->setStrSinConclusiva('S');
$objMdLitSituacaoDTO->setNumMaxRegistrosRetorno(1);

$objMdLitSituacaoRN = new MdLitSituacaoRN();
$objMdLitSituacaoDTO = $objMdLitSituacaoRN->consultar($objMdLitSituacaoDTO);
$idSituacaoConclusivaParametrizada = $objMdLitSituacaoDTO ? $objMdLitSituacaoDTO->getNumIdSituacaoLitigioso(): '';
