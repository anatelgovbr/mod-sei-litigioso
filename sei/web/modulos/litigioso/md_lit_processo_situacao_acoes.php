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

      //  $arrGridSit = array();
        $strGridSituacao = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGridSitu);
        
        //Preencher Grid Decisão
        $strGridDecisao = $objMdLitDecisaoRN->retornaDadosDecisaoCadastradas($idProcedimento);
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

        try {
            $objMdLitLancamentoDTO = $objMdLitLancamentoRN->atualizarLancamento($idProcedimento, $idLancamentoSelecionado);
        }catch (Exception $e){
            PaginaSEI::getInstance()->processarExcecao($e);
        }

        //combo interessado
        $numInteressado = $objMdLitLancamentoDTO ? $objMdLitLancamentoDTO->getStrNumeroInteressado(): null;
        $strComboInteressado = MdLitDadoInteressadoINT::montarSelectIdParticipante('null', '&nbsp;', '', $objMdLitControleDTO->getNumIdControleLitigioso(), '', $numInteressado);



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