<?
    /**
     * ANATEL
     *
     * 15/02/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    $strLinkAjaxValidarSEI           = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_documento_detalhe');
    $strLinkAjaxValidarSituacaoSEI   = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_documento_verificar_situacao');
    $strLinkAjaxRemoverSobrestamento = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_processo_litigioso_remover_sobrestamento');
    $strLinkDocInstaurador           = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_cadastro_completo&id_procedimento=' . $_GET['id_procedimento']);
    $strLinkAjaxBuscarInteressado    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_interessado_processo');
    $strLinkAjaxExisteInfracao       = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_processo_litigioso_existe_infracao');
    $strLinkAlteraProcesso           = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento=' . $_GET['id_procedimento'].'&arvore=1');
    $strLinkAjaxConsultarDispositivo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dispositivo_consultar');

    $strMsgTooltipInteressados = 'Caso queira adicionar Interessados, acesse o cadastro do Processo para alterar.';

    try {
        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();
        SessaoSEI::getInstance()->validarLink();

        //para que a pagina seja exibida sem os menus lateral e do topo
        PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

        //SessaoSEI::getInstance()->validarPermissao($_GET['acao']);


        $strDesabilitar = '';

        $arrComandos = array();

        $bolOperacao = 'i';

        //FORM - envio
        $hdnListaDocInstauradores = $_REQUEST['hdnListaDocInstauradores'];
        $hdnListaDIIndicados      = $_REQUEST['hdnListaDIIndicados'];
        $hdnListaPSIndicados      = $_REQUEST['hdnListaPSIndicados'];
        $txtDtInstauracao         = $_REQUEST['txtDtInstauracao'];

        //FORM - envio Processos a serem Sobrestados
        $txtNumeroSeiPS           = $_REQUEST['txtNumeroSeiPS'];
        $txtNumeroSeiTipoPS       = $_REQUEST['txtNumeroSeiTipoPS'];
        $txtDtSobrestamentoPS     = $_REQUEST['txtDtSobrestamentoPS'];
        $hdnIdnumeroSeiPS         = $_REQUEST['hdnIdnumeroSeiPS'];

        $idProcedimento = $_REQUEST['idProcedimento'];

        
        switch ($_GET['acao']) {

            case 'md_lit_processo_cadastro_cadastrar':
            case 'md_lit_processo_cadastro_completo':
            case 'md_lit_processo_cadastro_consultar':

                if (count($_POST) > 0) {

                    try {

                        // CONTROLE LITIGIOSO
                        $objControleLitigiosoRN  = new MdLitControleRN();
                        $objControleLitigiosoDTO = $objControleLitigiosoRN->cadastrarCompleto($_POST);
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento . '&atualizar_arvore=1&id_documento=' . $objControleLitigiosoDTO->getDblIdDocumento()));
                        die;


                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                }

                //montando o form
                $strTitulo = 'Cadastrar em Controle Litigioso';

                $idProcedimento = $_GET['id_procedimento'];
                $idUnidade      = $_GET['infra_unidade_atual'];

                $procedimentoDTO = new ProcedimentoDTO();
                $procedimentoDTO->retTodos();
                $procedimentoDTO->retStrProtocoloProcedimentoFormatado();
                $procedimentoDTO->retStrSiglaUnidadeGeradoraProtocolo();

                $procedimentoDTO->setDblIdProcedimento($idProcedimento);

                $procedimentoRN  = new ProcedimentoRN();
                $procedimentoDTO = $procedimentoRN->consultarRN0201($procedimentoDTO);

                $ProtocoloProcedimentoFormatado = $procedimentoDTO->getStrProtocoloProcedimentoFormatado();
                $UnidadeProcedimento            = $procedimentoDTO->getStrSiglaUnidadeGeradoraProtocolo();


                //MdLitRelTipoControleTipoProcedimentoDTO
                $ObjRelTipoControleLitigiosoTipoProcedimentoRN  = new MdLitRelTipoControleTipoProcedimentoRN();
                $ObjRelTipoControleLitigiosoTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
                $ObjRelTipoControleLitigiosoTipoProcedimentoDTO->retTodos();
                $ObjRelTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoProcedimento($procedimentoDTO->getNumIdTipoProcedimento());
                $ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO = $ObjRelTipoControleLitigiosoTipoProcedimentoRN->listar($ObjRelTipoControleLitigiosoTipoProcedimentoDTO);



                //id_tipo_procedimento

                $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                $objTipoControleLitigiosoDTO->retTodos();
                $cArrObjRelTipoControleLitigiosoTipoProcedimentoDTO = is_array($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO) ? count($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO) : 0;
                if($cArrObjRelTipoControleLitigiosoTipoProcedimentoDTO > 0){
                    //id_MdLitRelTipoControleTipoProcedimentoDTO
                    $idMdRelTipoCntroleTipoProcedimento = $ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO[0]->getNumIdTipoControleLitigioso();

                    $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO[0]->getNumIdTipoControleLitigioso());
                }else{
                    /*
                     * Configurar um tipo de controle com um tipo de processo > Criar um Controle Litigioso com o processo do Tipo configurado >
                     * Voltar no Tipo de Controle e excluir o tipo de processo utilizado no Controle.
                     * Para os Controles já criados, o ícone deve continuar aparecendo, permitindo a manutenção dos dados.
                    */
                    $objMdLitControleDTO = new MdLitControleDTO();
                    $objMdLitControleDTO->retTodos();
                    $objMdLitControleDTO->setDblIdProcedimento($procedimentoDTO->getDblIdProcedimento());

                    $objMdLitControleRN = new MdLitControleRN();
                    $objMdLitControleDTO = $objMdLitControleRN->consultar($objMdLitControleDTO);

                    $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($objMdLitControleDTO->getNumIdMdLitTipoControle());
                    $idMdRelTipoCntroleTipoProcedimento = $objMdLitControleDTO->getNumIdMdLitTipoControle();
                }

                $objTipoControleLitigiosoRN  = new MdLitTipoControleRN();
                $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);
                $strNomeTipoControle = $objTipoControleLitigiosoDTO->getStrSigla();

                //Links Componentes
                //id tp controle
                $idTpControle = $objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso();

                //Dispositivo Normativo - Fieldset Dispositivo Normativo
                $strLinkDispNormatDNSelecao  = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_selecionar&tipo_selecao=1&filtro=' . $idTpControle . '&id_object=objLupaIDNDispositivoNormativo');
                $strLinkAjaxDispNormatDNAjax = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dispositivo_auto_completar&filtro=' . $idTpControle);

                // Dispositivo Normativo - Fieldset Conduta
                $strLinkDispNormatCDSelecao  = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_selecionar&tipo_selecao=2&filtro=' . $idTpControle . '&id_object=objLupaICDispositivoNormativo');
                $strLinkAjaxDispNormatCDAjax = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dispositivo_auto_completar&filtro=' . $idTpControle);

                //Conduta - Link Combo Dependência (Indicar por Dispositivo Normativo)
                $strLinkAjaxDependConduta = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_conduta_montar_select');

                //Conduta - Select em tela
                $strItensSelConduta = MdLitCondutaINT::montarSelectConduta(null, null, null, null, $idTpControle);

                // Link Motivos
                $strLinkMotivosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_motivo_selecionar&tipo_selecao=2&id_object=objLupaMotivos&idTipoControle='.$idMdRelTipoCntroleTipoProcedimento);
                $strLinkAjaxMotivos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=motivo_auto_completar');

            // TIPO DE CONTROLE LITIGIOSO - GERAR SOBRESTADOS
                // TELA : Alterar Tipo de Controle Litigioso
                // OPÇÃO: Pode sobrestar a tramitação de outros processos
                // [RN8] Caso seja um Tipo de Controle Litigioso normal (que não gera Sobrestamento de outros processos),
                //       deve ser obrigatório informar pelo menos um dispositivo.
                // [RN9] Caso seja um Tipo de Controle Litigioso que gera Sobrestamento de outros Processos, ter um
                //       dispositivo associado torna-se opcional.
                // [RN16] As informações de Processos a serem Sobrestados somente serão apresentadas caso o
                //        Tipo de Controle Litigioso for um tipo de controle que pode sobrestar outros processos...

                //tipos de processos sobrestados relacionados
                $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO = new MdLitRelTipoControleTipoProcedimentoSobrestadoDTO();
                $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->retTodos();

                //$objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
                $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoControleLitigioso($idTpControle);

                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();
                $arrTipoProcedimentosSobrestados                         = $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN->listar($objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);
                $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO($arrTipoProcedimentosSobrestados);

                // 0 - Não pode Gerar Sobrestados
                $bolTipoProcedimentosSobrestadosGeracao = count($arrTipoProcedimentosSobrestados);


                // RECUPERANDO DO BD


                // DOCUMENTO INSTAURADOR

                // ID Doc Instaurador
                // Documento ID
                // Documento Formatado
                // Documento Numero
                // Documento Tipo
                // Documento
                // Unidade
                // Data

                $objControleLitigiosoDTO = new MdLitControleDTO();
                $objControleLitigiosoDTO->retTodos();
//		$objControleLitigiosoDTO->retStrProtocoloFormatado();
//		$objControleLitigiosoDTO->retStrNumero();
//		$objControleLitigiosoDTO->retStrSerie();
                $objControleLitigiosoDTO->setDblIdProcedimento($_GET['id_procedimento']);
                $objControleLitigiosoRN  = new MdLitControleRN();
                $objControleLitigiosoDTO = $objControleLitigiosoRN->consultar($objControleLitigiosoDTO);

                $hdnIdMdLitControle = '';
                if (is_object($objControleLitigiosoDTO)) {

                    $hdnIdMdLitControle = $objControleLitigiosoDTO->getNumIdControleLitigioso();

                    //DOCUMENTO
                    $objDocumentoDTO = new DocumentoDTO();
                    $objDocumentoDTO->setDblIdDocumento($objControleLitigiosoDTO->getDblIdDocumento());
                    $objDocumentoDTO->retDblIdDocumento();
                    $objDocumentoDTO->retStrNumero();
                    $objDocumentoDTO->retStrNomeSerie();
                    $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
                    $objDocumentoDTO->retStrDescricaoUnidadeGeradoraProtocolo();
                    $objDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
                    $objDocumentoDTO->retArrObjAssinaturaDTO();
                    $objDocumentoDTO->retDtaGeracaoProtocolo();


                    $objDocumentoRN  = new DocumentoRN();
                    $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

                    //ASSINATURA
                    $dataDocumento    = '';

                    $objAssinaturaDTO = new AssinaturaDTO();
                    $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
                    $objAssinaturaDTO->retDthAberturaAtividade();
                    $objAssinaturaRN     = new AssinaturaRN();
                    $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);
                    if (count($arrObjAssinaturaDTO) > 0) {
                        $dataDocumento = explode(' ', $arrObjAssinaturaDTO[0]->getDthAberturaAtividade());
                        $dataDocumento = $dataDocumento[0];
                    }

                    if(!$dataDocumento){
                        $dataDocumento = $objDocumentoDTO->getDtaGeracaoProtocolo();
                    }

                    $arr[] = array(
                        1
                        , $objControleLitigiosoDTO->getDblIdDocumento()
                        , $objDocumentoDTO->getStrProtocoloDocumentoFormatado()
                        , $objDocumentoDTO->getStrNumero()
                        , $objDocumentoDTO->getStrNomeSerie()
                        , $objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero() . ' (' . $objDocumentoDTO->getStrProtocoloDocumentoFormatado() . ')'
                        , '<a alt="'.$objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo().'" title="'.$objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo().'" class="ancoraSigla" > '.$objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo().'</a>'
                        , $dataDocumento
                    );

                    // AÇÕES - serão tratadas no .js
                    //$arrAcoesDownload[$objAnexoDTO->getNumIdAnexo()] = '<a href="'.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDownload.'&id_anexo='.$objAnexoDTO->getNumIdAnexo())).'" target="_blank"><img src="imagens/download.gif" title="Baixar Anexo" alt="Baixar Anexo" class="infraImg" /></a> ';

                    $hdnListaDocInstauradores = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arr);
                    $txtDtInstauracao         = $objControleLitigiosoDTO->getDtaDataInstauracao();
                    // DOCUMENTO INSTAURADOR - FIM

                    // DOCUMENTOS INFRIGIDOS
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retTodos();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrDispositivo();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrNorma();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrUrlDispositivo();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrDescricaoDispositivo();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retNumIdCondutaLitigioso();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrConduta();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retDtaInfracaoEspecifica();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retDtaInfracaoPeriodoInicial();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retDtaInfracaoPeriodoFinal();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrStaInfracaoData();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdControleLitigioso($objControleLitigiosoDTO->getNumIdControleLitigioso());

                    $objRelDispositivoNormativoCondutaControleLitigiosoRN     = new MdLitRelDispositivoNormativoCondutaControleRN();
                    $arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO = $objRelDispositivoNormativoCondutaControleLitigiosoRN->listar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

                    $arr = null;
                    foreach ($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO as $objRelDispositivoNormativoCondutaControleLitigioso) {
                        $norma = $objRelDispositivoNormativoCondutaControleLitigioso->getStrNorma();
                        $dispositivo = $objRelDispositivoNormativoCondutaControleLitigioso->getStrDispositivo();
                        if($objRelDispositivoNormativoCondutaControleLitigioso->getStrUrlDispositivo() != ''){
                            $norma = '<a href="'.PaginaSEI::tratarHTML($objRelDispositivoNormativoCondutaControleLitigioso->getStrUrlDispositivo()).'" style="font-size: inherit !important;" target="_blank" title="Acesse a Norma">'.PaginaSEI::tratarHTML($objRelDispositivoNormativoCondutaControleLitigioso->getStrNorma()).'</a>';
                        }
                        if($objRelDispositivoNormativoCondutaControleLitigioso->getStrDescricaoDispositivo() != ''){
                            $descricao = str_replace('±', '&plusmn;', $objRelDispositivoNormativoCondutaControleLitigioso->getStrDescricaoDispositivo());
                            $dispositivo = '<span style="font-size: inherit !important;" title="'.PaginaSEI::tratarHTML($descricao).'">'.PaginaSEI::tratarHTML($dispositivo).'</span>';
                        }
                        $conduta = $objRelDispositivoNormativoCondutaControleLitigioso->getStrConduta() ? $objRelDispositivoNormativoCondutaControleLitigioso->getStrConduta() : '';
                        $arr[] = array(
                            $objRelDispositivoNormativoCondutaControleLitigioso->getNumIdDispositivoNormativoNormaCondutaControle(),
                            $objRelDispositivoNormativoCondutaControleLitigioso->getNumIdDispositivoNormativoLitigioso() . '-' . $objRelDispositivoNormativoCondutaControleLitigioso->getNumIdCondutaLitigioso()
                            , $norma
                            , $objRelDispositivoNormativoCondutaControleLitigioso->getNumIdDispositivoNormativoLitigioso()
                            , $dispositivo
                            , $objRelDispositivoNormativoCondutaControleLitigioso->getNumIdCondutaLitigioso()
                            , $conduta
                            , $objRelDispositivoNormativoCondutaControleLitigioso->getDtaInfracao()
                            , $objRelDispositivoNormativoCondutaControleLitigioso->getDtaInfracaoEspecifica()
                            , $objRelDispositivoNormativoCondutaControleLitigioso->getDtaInfracaoPeriodoInicial()
                            , $objRelDispositivoNormativoCondutaControleLitigioso->getDtaInfracaoPeriodoFinal()
                            , $objRelDispositivoNormativoCondutaControleLitigioso->getStrStaInfracaoData()
                        );
                    }
                    $hdnListaDIIndicados = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arr);
                    // DOCUMENTOS INFRIGIDOS - FIM


                    // SOBRESTAMENTOS

                    // SOBRESTAMENTOS - CORE
                    // Número SEI do documento de determinação:
                    // Tipo:
                    // Data do Sobrestamento:
                    // Número do Processo a ser Sobrestado:
                    // Tipo:

                    // Excluir Sobrestamento Litigioso sem Sobrestamento CORE
                    if ($_GET['id_procedimento'] != '') {
                        //SOBRESTAMENTO - CORE - montando existentes
                        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
                        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);
                        $objRelProtocoloProtocoloDTO->retTodos();
                        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($_GET['id_procedimento']);

                        $objRelProtocoloProtocoloRN     = new RelProtocoloProtocoloRN();
                        $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

                        $arrRelProtocoloProtocolo2 = array();
                        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
                            $arrRelProtocoloProtocolo2 [] = $objRelProtocoloProtocoloDTO->getDblIdProtocolo2();
                        }

                        //SOBRESTAMENTO - LITIGIOSO - removendo aqueles que não estão no SOBRESTAMENTO - CORE
                        $objRelProtocoloProtocoloLitigiosoDTO = new MdLitRelProtocoloProtocoloDTO();
                        $objRelProtocoloProtocoloLitigiosoDTO->retTodos();

                        if (count($arrRelProtocoloProtocolo2) == 0) {
                            $objRelProtocoloProtocoloLitigiosoDTO->adicionarCriterio(array('IdProtocolo1'), array(InfraDTO::$OPER_IGUAL), array($_GET['id_procedimento']));
                        } else {
                            $objRelProtocoloProtocoloLitigiosoDTO->adicionarCriterio(array('IdProtocolo1', 'IdProtocolo2'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_NOT_IN), array($_GET['id_procedimento'], $arrRelProtocoloProtocolo2), array(InfraDTO::$OPER_LOGICO_AND));
                        }

                        $objRelProtocoloProtocoloLitigiosoRN  = new MdLitRelProtocoloProtocoloRN();
                        $objRelProtocoloProtocoloLitigiosoDTO = $objRelProtocoloProtocoloLitigiosoRN->listar($objRelProtocoloProtocoloLitigiosoDTO);

                        $objRelProtocoloProtocoloLitigiosoRN = new MdLitRelProtocoloProtocoloRN();
                        $objRelProtocoloProtocoloLitigiosoRN->excluir($objRelProtocoloProtocoloLitigiosoDTO);
                    }


                    // SOBRESTAMENTOS - CONTROLE LITIGIOSO
                    $objRelProtocoloProtocoloLitigiosoDTO = new MdLitRelProtocoloProtocoloDTO();
                    $objRelProtocoloProtocoloLitigiosoDTO->retTodos();
                    $objRelProtocoloProtocoloLitigiosoDTO->retStrProtocoloFormatadoDocumento();
                    $objRelProtocoloProtocoloLitigiosoDTO->retStrProtocoloFormatadoProcedimento2();
                    $objRelProtocoloProtocoloLitigiosoDTO->retStrTipoProcedimento2();
                    $objRelProtocoloProtocoloLitigiosoDTO->setNumIdControleLitigioso($objControleLitigiosoDTO->getNumIdControleLitigioso());

                    $objRelProtocoloProtocoloLitigiosoRN     = new MdLitRelProtocoloProtocoloRN();
                    $arrObjRelProtocoloProtocoloLitigiosoDTO = $objRelProtocoloProtocoloLitigiosoRN->listar($objRelProtocoloProtocoloLitigiosoDTO);

                    $arr = null;
                    foreach ($arrObjRelProtocoloProtocoloLitigiosoDTO as $objRelProtocoloProtocoloLitigioso) {
                        $DthDtaSobrestamento = explode(' ', $objRelProtocoloProtocoloLitigioso->getDthDtaSobrestamento());
                        $arr[]               = array(
                            $objRelProtocoloProtocoloLitigioso->getStrProtocoloFormatadoProcedimento2()
                            , $objRelProtocoloProtocoloLitigioso->getDblIdProtocolo2()
                            , $objRelProtocoloProtocoloLitigioso->getStrProtocoloFormatadoProcedimento2()
                            , $objRelProtocoloProtocoloLitigioso->getStrTipoProcedimento2()
                            , $DthDtaSobrestamento[0]
                            , $objRelProtocoloProtocoloLitigioso->getStrProtocoloFormatadoDocumento()
                            , $objRelProtocoloProtocoloLitigioso->getDblIdDocumento()
                        );

                        $txtNumeroSeiPS           = $objRelProtocoloProtocoloLitigioso->getStrProtocoloFormatadoDocumento();
                        $hdnIdnumeroSeiPS         = $objRelProtocoloProtocoloLitigioso->getDblIdDocumento();
                    }
                    $hdnListaPSIndicados = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arr);
                    // SOBRESTAMENTOS - CONTROLE LITIGIOSO - FIM


                    // RECUPERANDO DO BD - FIM
                    $txtNumeroSei = $objDocumentoDTO->getStrProtocoloDocumentoFormatado();

                    //CONSULTAR os motivos
                    $objmdLitRelControleMotivoDTO = new MdLitRelControleMotivoDTO();
                    $objmdLitRelControleMotivoDTO->retTodos();
                    $objmdLitRelControleMotivoDTO->setNumIdMdLitControle($hdnIdMdLitControle);

                    $objmdLitRelControleMotivoRN = new MdLitRelControleMotivoRN();
                    $arrMotivos = $objmdLitRelControleMotivoRN->listar( $objmdLitRelControleMotivoDTO );

                   // $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoMotivoDTO( $arrMotivos );

                    $strItensSelMotivos = "";
                    $objMotivoRN = new MdLitMotivoRN();

                    for($x = 0;$x<count($arrMotivos);$x++){

                        $objMotivoDTO = new MdLitMotivoDTO();
                        $objMotivoDTO->retNumIdMdLitMotivo();
                        $objMotivoDTO->retStrDescricao();

                        $objMotivoDTO->setNumIdMdLitMotivo($arrMotivos[$x]->getNumIdMdLitMotivo());
                        $objMotivoDTO = $objMotivoRN->consultar( $objMotivoDTO );

                        if( $objMotivoDTO != null && is_object( $objMotivoDTO ) ) {
                            $strItensSelMotivos .= "<option value='" . $objMotivoDTO->getNumIdMdLitMotivo() .  "'>" . $objMotivoDTO->getStrDescricao() . "</option>";
                        }
                    }
                }
                $objMdLitRelTpControlMotiDTO = new MdLitRelTpControlMotiDTO();
                $objMdLitRelTpControlMotiDTO->retTodos(false);
                $objMdLitRelTpControlMotiDTO->setNumIdMdLitTipoControle($idTpControle);

                $objMdLitRelTpControlMotiRN = new MdLitRelTpControlMotiRN();
                $existeMotivo = $objMdLitRelTpControlMotiRN->contar($objMdLitRelTpControlMotiDTO);

                $bolOperacao = 'a';

                break;

            default:
                throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        }


    } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
    }

    if($_GET['acao'] != 'md_lit_processo_cadastro_consultar'){
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarDispositivoNormativoLitigioso" id="sbmCadastrarDispositivoNormativoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
    }
$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento=' . $_GET['id_procedimento'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';