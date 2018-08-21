<?php

    class LitigiosoIntegracao extends SeiIntegracao
    {

        public function getNome()
        {
            return 'Litigioso';
        }

        public function getVersao()
        {
            return '1.0.0';
        }

        public function getInstituicao()
        {
            return 'ANATEL (Projeto Colaborativo no Portal do SPB)';
        }

        public function processarControlador($strAcao)
        {
            switch ($strAcao) {

                case 'md_lit_tipo_decisao_excluir' :
                case 'md_lit_tipo_decisao_desativar' :
                case 'md_lit_tipo_decisao_reativar' :
                case 'md_lit_tipo_decisao_listar' :
                case 'md_lit_tipo_decisao_selecionar' :
                    require_once dirname(__FILE__) . '/md_lit_tipo_decisao_lista.php';

                    return true;

                case 'md_lit_tipo_decisao_cadastrar' :
                case 'md_lit_tipo_decisao_alterar' :
                case 'md_lit_tipo_decisao_consultar' :
                    require_once dirname(__FILE__) . '/md_lit_tipo_decisao_cadastro.php';

                    return true;


                case 'md_lit_especie_decisao_excluir' :
                case 'md_lit_especie_decisao_desativar' :
                case 'md_lit_especie_decisao_reativar' :
                case 'md_lit_especie_decisao_listar' :
                case 'md_lit_especie_decisao_selecionar' :
                    require_once dirname(__FILE__) . '/md_lit_especie_decisao_lista.php';

                    return true;

                case 'md_lit_especie_decisao_cadastrar' :
                case 'md_lit_especie_decisao_alterar' :
                case 'md_lit_especie_decisao_consultar' :
                    require_once dirname(__FILE__) . '/md_lit_especie_decisao_cadastro.php';

                    return true;

                case 'md_lit_tipo_controle_excluir' :
                case 'md_lit_tipo_controle_desativar' :
                case 'md_lit_tipo_controle_reativar' :
                case 'md_lit_tipo_controle_listar' :
                case 'md_lit_tipo_controle_selecionar' :
                    require_once dirname(__FILE__) . '/md_lit_tipo_controle_lista.php';

                    return true;

                case 'md_lit_tipo_controle_cadastrar' :
                case 'md_lit_tipo_controle_alterar' :
                case 'md_lit_tipo_controle_consultar' :
                    require_once dirname(__FILE__) . '/md_lit_tipo_controle_cadastro.php';

                    return true;

                // parametrizar situacao
                case 'md_lit_situacao_parametrizar' :
                case 'md_lit_situacao_desativar':
                case 'md_lit_situacao_reativar':
                case 'md_lit_situacao_excluir':
                case 'md_lit_situacao_visualizar_parametrizar':
                    require_once dirname(__FILE__) . '/md_lit_situacao_parametrizar.php';

                    return true;

                case 'md_lit_situacao_cadastrar' :
                case 'md_lit_situacao_alterar' :
                case 'md_lit_situacao_consultar':
                    require_once dirname(__FILE__) . '/md_lit_situacao_cadastro.php';

                    return true;

                case 'md_lit_situacao_orientacao' :
                    require_once dirname(__FILE__) . '/md_lit_situacao_orientacao.php';

                    return true;

                // fase
                case 'md_lit_fase_cadastrar' :
                case 'md_lit_fase_alterar' :
                case 'md_lit_fase_consultar' :
                    require_once dirname(__FILE__) . '/md_lit_fase_cadastro.php';

                    return true;

                case 'md_lit_fase_listar' :
                case 'md_lit_fase_desativar' :
                case 'md_lit_fase_reativar' :
                case 'md_lit_fase_excluir' :
                    require_once dirname(__FILE__) . '/md_lit_fase_lista.php';

                    return true;

                // Associar Dispositivo Normativo
                case 'md_lit_associar_dispositivo_normativo_consultar' :
                case 'md_lit_associar_dispositivo_normativo_listar' :
                case 'md_lit_associar_dispositivo_normativo_reativar' :
                case 'md_lit_associar_dispositivo_normativo_desativar' :
                case 'md_lit_associar_dispositivo_normativo_excluir' :
                case 'md_lit_associar_dispositivo_normativo_cadastrar' :
                    require_once dirname(__FILE__) . '/md_lit_associar_dispositivo_normativo_cadastro.php';

                    return true;

                // Associar Tipo Decisao
                case 'md_lit_tipo_controle_tipo_decisao_consultar' :
                case 'md_lit_tipo_controle_tipo_decisao_listar' :
                case 'md_lit_tipo_controle_tipo_decisao_reativar' :
                case 'md_lit_tipo_controle_tipo_decisao_desativar' :
                case 'md_lit_tipo_controle_tipo_decisao_excluir' :
                case 'md_lit_tipo_controle_tipo_decisao_cadastrar' :
                    require_once dirname(__FILE__) . '/md_lit_tipo_controle_tipo_decisao_cadastro.php';

                    return true;

                // Dispositivo Normativo
                case 'md_lit_dispositivo_normativo_selecionar' :
                case 'md_lit_dispositivo_normativo_listar' :
                case 'md_lit_dispositivo_normativo_reativar' :
                case 'md_lit_dispositivo_normativo_desativar' :
                case 'md_lit_dispositivo_normativo_excluir':
                    require_once dirname(__FILE__) . '/md_lit_dispositivo_normativo_lista.php';

                    return true;

                case 'md_lit_dispositivo_normativo_consultar' :
                case 'md_lit_dispositivo_normativo_alterar':
                case 'md_lit_dispositivo_normativo_cadastrar' :
                    require_once dirname(__FILE__) . '/md_lit_dispositivo_normativo_cadastro.php';

                    return true;

                // Conduta
                case 'md_lit_conduta_selecionar' :
                case 'md_lit_conduta_listar' :
                case 'md_lit_conduta_reativar':
                case 'md_lit_conduta_desativar':
                case 'md_lit_conduta_excluir':
                    require_once dirname(__FILE__) . '/md_lit_conduta_lista.php';

                    return true;

                case 'md_lit_conduta_cadastrar' :
                case 'md_lit_conduta_alterar' :
                case 'md_lit_conduta_consultar':
                    require_once dirname(__FILE__) . '/md_lit_conduta_cadastro.php';

                    return true;

                //OBRIGAÇÃO
                case 'md_lit_obrigacao_excluir':
                case 'md_lit_obrigacao_desativar':
                case 'md_lit_obrigacao_reativar':
                case 'md_lit_obrigacao_listar':
                case 'md_lit_obrigacao_selecionar':
                    require_once dirname(__FILE__) . '/md_lit_obrigacao_lista.php';

                    return true;
                case 'md_lit_obrigacao_cadastrar':
                case 'md_lit_obrigacao_alterar':
                case 'md_lit_obrigacao_consultar':
                    require_once dirname(__FILE__) . '/md_lit_obrigacao_cadastro.php';

                    return true;

                //EU 4864
                //EU 4864
                // tela 2 "Cadastro em Controle Litigioso"
                case 'md_lit_processo_validar_numero_sei':
                    require_once dirname(__FILE__) . '/md_lit_processo_validar_numero_sei.php';

                    return true;

                //tela 3 "CADASTRAR PROCESSO DE CONTROLE LITIGIOSO – FORMULARIO COMPLETO"
                case 'md_lit_processo_cadastro_completo':
                case 'md_lit_processo_cadastro_cadastrar':
                case 'md_lit_processo_cadastro_consultar':
                    require_once dirname(__FILE__) . '/md_lit_processo_cadastro_completo.php';

                    return true;

                //Serviços
                case 'md_lit_servico_listar':
                case 'md_lit_servico_desativar':
                case 'md_lit_servico_reativar':
                case 'md_lit_servico_excluir':
                case 'md_lit_servico_selecionar':
                    require_once dirname(__FILE__) . '/md_lit_servico_lista.php';
                    return true;

                case 'md_lit_servico_cadastrar':
                case 'md_lit_servico_alterar':
                case 'md_lit_servico_consultar':
                    require_once dirname(__FILE__) . '/md_lit_servico_cadastro.php';
                    return true;

                case 'md_lit_servico_integracao_mapear':
                    require_once dirname(__FILE__) . '/md_lit_servico_cadastro_integracao_mapeamento.php';
                    return true;

                //Integração
                case 'md_lit_integracao_listar':
                case 'md_lit_integracao_desativar':
                case 'md_lit_integracao_reativar':
                case 'md_lit_integracao_excluir':
                    require_once dirname(__FILE__) . '/md_lit_integracao_lista.php';
                    return true;

                case 'md_lit_integracao_cadastrar':
                    require_once dirname(__FILE__) . '/md_lit_integracao_cadastro.php';
                    return true;

                case 'md_lit_integracao_alterar':
                    require_once dirname(__FILE__) . '/md_lit_integracao_cadastro.php';
                    return true;

                case 'md_lit_integracao_consultar':
                    require_once dirname(__FILE__) . '/md_lit_integracao_cadastro.php';
                    return true;

                case 'md_lit_parametrizar_interessado_cadastrar':
                    require_once dirname(__FILE__) . '/md_lit_param_interessado_cadastro.php';
                    return true;

                case 'md_lit_dados_complementares_cadastrar':
                    require_once dirname( __FILE__ ) . '/md_lit_dados_complementares_cadastro.php';
                    return true;

                case 'md_lit_processo_situacao_cadastrar':
                    require_once dirname(__FILE__).'/md_lit_processo_situacao_cadastro.php';
                    return true;

                case 'md_lit_decisao_cadastrar':
                    require_once dirname(__FILE__).'/md_lit_decisao_cadastro.php';
                    return true;

                case 'md_lit_decisao_historico':
                    require_once dirname(__FILE__).'/md_lit_decisao_historico.php';
                    return true;

                case 'md_lit_processo_situacao_cadastro_hist_int_listar':
                    require_once dirname(__FILE__).'/md_lit_processo_situacao_cadastro_hist_intercorrente.php';
                    return true;

                case 'md_lit_processo_situacao_cadastro_hist_quin_listar':
                    require_once dirname(__FILE__).'/md_lit_processo_situacao_cadastro_hist_quinquenal.php';
                    return true;

                case 'md_lit_multa_justificativa':
                    require_once dirname(__FILE__).'/md_lit_multa_justificativa.php';
                    return true;

                case 'md_lit_historic_lancamento_listar':
                    require_once dirname(__FILE__).'/md_lit_processo_situacao_cadastro_hist_lancamento.php';
                    return true;

                case 'md_lit_multa_cancelar':
                    require_once dirname(__FILE__).'/md_lit_multa_cancelar.php';
                    return true;

                /*
                 * EU9390 Reincidência Específicas e Antecedentes
                 */
                case 'md_lit_reinciden_anteceden_alterar':
                case 'md_lit_reinciden_anteceden_cadastrar':

                    require_once dirname(__FILE__).'/md_lit_reinciden_anteceden_cadastro.php';
                    return true;

                    /*
                     * EU17475 Manter Motivo
                     */
                case 'md_lit_motivo_listar':
                case 'md_lit_motivo_selecionar':
                case 'md_lit_motivo_desativar':
                case 'md_lit_motivo_excluir':
                case 'md_lit_motivo_reativar':
                    require_once dirname(__FILE__).'/md_lit_motivo_lista.php';
                    return true;

                case 'md_lit_motivo_cadastrar':
                case 'md_lit_motivo_consultar':
                case 'md_lit_motivo_alterar':
                    require_once dirname(__FILE__).'/md_lit_motivo_cadastro.php';
                    return true;

                case 'md_lit_situacao_lancamento_listar':
                case 'md_lit_situacao_lancamento_reativar':
                case 'md_lit_situacao_lancamento_excluir':
                case 'md_lit_situacao_lancamento_selecionar':
                case 'md_lit_situacao_lancamento_desativar':
                    require_once dirname(__FILE__).'/md_lit_situacao_lancamento_lista.php';
                    return true;

                case 'md_lit_situacao_lancamento_cadastrar':
                case 'md_lit_situacao_lancamento_alterar':
                case 'md_lit_situacao_lancamento_consultar':
                    require_once dirname(__FILE__).'/md_lit_situacao_lancamento_cadastro.php';
                    return true;

                case 'md_lit_situacao_lancamento_integracao_mapear':
                    require_once dirname(__FILE__) . '/md_lit_situacao_lancamento_integracao_mapeamento.php';
                    return true;
            }

            return false;

        }

        public function processarControladorAjax($strAcaoAjax)
        {
            $xml = null;

            switch ($strAcaoAjax) {

                case 'md_lit_unidade_auto_completar':
                    $arrObjUnidadeDTO = UnidadeINT::autoCompletarUnidades($_POST['palavras_pesquisa'], true, '');
                    $xml              = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUnidadeDTO, 'IdUnidade', 'Sigla');
                    break;

                case 'md_lit_tipo_procedimento_auto_completar':
                    $arrObjTipoProcedimentoDTO = TipoProcedimentoINT::autoCompletarTipoProcedimento($_POST['palavras_pesquisa']);
                    $xml                       = InfraAjax::gerarXMLItensArrInfraDTO($arrObjTipoProcedimentoDTO, 'IdTipoProcedimento', 'Nome');
                    break;

                case 'usuario_auto_completar':
                    $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuarios(null, $_POST['palavras_pesquisa'], false, false);
                    $xml              = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Nome');
                    break;

                case 'md_lit_serie_auto_completar':
                    $arrObjSerieDTO = MdLitSerieINT::autoCompletarSeries($_POST['palavras_pesquisa']);
                    $xml            = InfraAjax::gerarXMLItensArrInfraDTO($arrObjSerieDTO, 'IdSerie', 'Nome');
                    break;

                case 'md_lit_dispositivo_auto_completar':
                    // nenhum parâmetro pode ser passado vazio.
                    $txtNorma = $_POST['palavras_pesquisa'] ? $_POST['palavras_pesquisa'] : "NULL";
                    $filtro   = $_GET['filtro'] ? $_GET['filtro'] : "";
                    if (isset($_POST['conduta'])){
                        $conduta = $_POST['conduta'] ? $_POST['conduta'] : '0';
                    }

                    $objDispositivoNormativoLitigiosoDTO = MdLitDispositivoNormativoINT::listarDispositivos('', $txtNorma, '', $conduta, $filtro, '');
                    $objDispositivoNormativoLitigiosoRN = new MdLitDispositivoNormativoRN();
                    $arrObjDispositivoNormativoLitigiosoDTO = $objDispositivoNormativoLitigiosoRN->listar($objDispositivoNormativoLitigiosoDTO);

                    // Norma - Dispositivo
                    foreach ($arrObjDispositivoNormativoLitigiosoDTO as $DispositivoNormativoLitigiosoDTO) {
                        $DispositivoNormativoLitigiosoDTO->setStrNorma($DispositivoNormativoLitigiosoDTO->getStrNorma() . ' - ' . $DispositivoNormativoLitigiosoDTO->getStrDispositivo());
                    }

                    $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjDispositivoNormativoLitigiosoDTO, 'IdDispositivoNormativoLitigioso', 'Norma');
                    break;

                case 'md_lit_tipo_decisao_auto_completar':
                    $arrObjDTO = MdLitTipoDecisaoINT::autoCompletarDecisao($_POST['palavras_pesquisa']);
                    $xml       = InfraAjax::gerarXMLItensArrInfraDTO($arrObjDTO, 'IdTipoDecisaoLitigioso', 'Nome');
                    break;

                case 'md_lit_conduta_auto_completar':
                    $arrObjCondutaDTO = MdLitCondutaINT::autoCompletarCondutasAtivas($_POST['palavras_pesquisa']);
                    $xml              = InfraAjax::gerarXMLItensArrInfraDTO($arrObjCondutaDTO, 'IdCondutaLitigioso', 'Nome');
                    break;

                case 'md_lit_tipo_controle_auto_completar':
                    $arrObjTipoControleDTO = MdLitTipoControleINT::autoCompletarTipoControleAtivos($_POST['palavras_pesquisa']);
                    $xml                   = InfraAjax::gerarXMLItensArrInfraDTO($arrObjTipoControleDTO, 'IdTipoControleLitigioso', 'Sigla');
                    break;

                case 'md_lit_obrigacao_auto_completar':
                    $arrObjObrigacaoDTO = MdLitObrigacaoINT::autoCompletarObrigacoesAtivas($_POST['palavras_pesquisa']);
                    $xml                = InfraAjax::gerarXMLItensArrInfraDTO($arrObjObrigacaoDTO, 'IdObrigacaoLitigioso', 'Nome');
                    break;

                case 'md_lit_especie_decisao_auto_completar':
                    $arrObjEspecieDecisaoDTO = MdLitEspecieDecisaoINT::autoCompletarEspeciesDecisaoAtivas($_POST['palavras_pesquisa']);
                    $xml                     = InfraAjax::gerarXMLItensArrInfraDTO($arrObjEspecieDecisaoDTO, 'IdEspecieLitigioso', 'Nome');
                    break;

                case 'md_lit_conduta_montar_select':
                    $arrObjCondutaDTO = MdLitCondutaINT::montarSelectConduta(null, null, null, $_POST['idDispositivoNormativo']);
                    $xml              = InfraAjax::gerarXMLSelect($arrObjCondutaDTO);
                    break;

                case 'md_lit_documento_detalhe':
                    $xml = MdLitControleINT::montarXMLDocumentoProcedimentoDetalhe($_POST['numeroSEI'], $_POST['idTipoControleLitigioso'], $_POST['tipo']);
                    break;

                case 'md_lit_documento_verificar_situacao':
                    $xml = MdLitControleINT::montarXMLDocumentoVerificarSituacao($_POST['numeroSEI'], $_POST['idTipoControleLitigioso']);
                    break;

                case 'md_lit_processo_litigioso_remover_sobrestamento':
                    $xml = MdLitControleINT::montarXMLRemoverSobrestamento($_POST['id_procedimento']);
                    break;

                case 'md_lit_integracao_busca_operacao_wsdl':
                    $xml      = MdLitIntegracaoINT::montarXMLBuscarOperacaoWSDL($_POST['endereco_wsdl']);
                    break;

                case 'md_lit_servico_busca_operacao_wsdl':
                    $xml      = MdLitServicoINT::montarXMLBuscarOperacaoWSDL($_POST['endereco_wsdl']);
                    break;

                case 'md_lit_interessado_processo':
                    $idProcedimento      = $_POST['idProcedimento'];
                    $idMdLitControle     = $_POST['idMdLitControle'];
                    $idMdLitTipoControle = $_POST['idMdLitTipoControle'];
                    $arrIdContato        = isset($_POST['arrIdContato']) ? $_POST['arrIdContato'] : array();
                    $xml                 = MdLitControleINT::montarXmlInteressadoProcesso($idProcedimento, $idMdLitControle, $idMdLitTipoControle, $arrIdContato);
                    break;

                case 'busca_servicos_auto_completar_todos':
                    $arrObjServicoDTO = MdLitServicoINT::autoCompletarServicosAtivos($_POST['palavras_pesquisa']);
                    $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjServicoDTO, 'IdMdLitServico', 'Descricao');
                    break;

                case 'busca_estados_auto_completar_todos':
                    $arrObjEstadosDTO = MdLitDadoInteressadoINT::montarSelectEstados($_POST['palavras_pesquisa']);
                    $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjEstadosDTO, 'IdUf', 'Nome');
                    break;

                case 'busca_cidades_auto_completar_todos':
                    $arrObjEstadosDTO = MdLitDadoInteressadoINT::montarSelectCidades($_POST['palavras_pesquisa'], PaginaSEI::getInstance()->getArrValuesSelect($_POST['idsUf']));
                    $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjEstadosDTO, 'IdCidade', 'Nome');
                    break;

                case 'md_lit_dado_complementar_consulta':
                    $xml = MdLitDadoInteressadoINT::dadoComplementarConsulta($_POST);
                    break;

                case 'md_lit_validar_numero_sei_situacao':
                    $xml = MdLitProcessoSituacaoINT::validarNumeroSei($_POST['numeroSei'], $_POST['idTipoControle'], $_POST['idProcedimento'], $_POST['idDocAlterar']);
                    break;

                case 'md_lit_verificar_vinculo_situacao_doc':
                    $xml = MdLitProcessoSituacaoINT::validarVinculoNumeroSeiSituacao($_POST['numeroSei'], $_POST['idTipoControle'], $_POST['idProcedimento'], $_POST['idSituacao']);
                    break;

                case 'md_lit_dado_complementar_listar':
                    $xml = MdLitDadoInteressadoINT::buscarDadoComplementarListarXml($_POST['id_procedimento'], $_POST['id_contato'], $_POST['id_md_lit_controle']);
                    break;

                case 'md_lit_situacoes_filtrar_select':
                    $arrObjSituacaoDTO = MdLitSituacaoINT::montarSelectSituacaoPorFase($_POST['idFase'], $_POST['idSerie']);
                    $xml               = InfraAjax::gerarXMLSelect($arrObjSituacaoDTO);
                    break;

                case 'md_lit_especie_decisao_montar_select':
                    $arrObjEspecieDecisaoDTO = MdLitEspecieDecisaoINT::montarSelectEspecieDecisao('null', '', $_POST['id_md_lit_tipo_decisao'], null);
                    $xml              = InfraAjax::gerarXMLSelect($arrObjEspecieDecisaoDTO);
                    break;

                case 'md_lit_carregar_especie_decisao':
                    $xml = MdLitEspecieDecisaoINT::carregarEspecieDecisao($_POST['id_md_lit_especie_decisao']);
                    break;

                case 'md_lit_carregar_dados_situacao':
                    $xml = MdLitSituacaoINT::carregarDadosSituacao($_POST['idSituacao'], $_POST['idProcedimento'], $_POST['idTpControle']);
                    break;

                case 'md_lit_verificar_vinculo':
                    $xml = MdLitSituacaoINT::verificarVinculo($_POST['idMdLitSituacao']);
                    break;

                case 'md_lit_buscar_numero':
                    $xmlSelect = MdLitDadoInteressadoINT::montarSelectNumero('null', '%20', '', $_POST['id_interessado']);
                    $xml = InfraAjax::gerarXMLSelect($xmlSelect);
                    break;

                case 'md_lit_consulta_extrato_multa':
                    $arrDecisao = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['valor_decisao']);
                    $resultXml = MdLitLancamentoINT::consultaExtratoMulta($arrDecisao, $_POST['id_procedimento'], $_POST['id_md_lit_lancamento']);
                    $xml = $resultXml;
                    break;

                case 'md_lit_integracao_buscar_tipo_controle':
                    $resultHtml = MdLitIntegracaoINT::montarTabelaCodigoReceita();
                    $xml        = $resultHtml;
                    break;

                case 'md_lit_processo_litigioso_existe_infracao':
                    $resultHtml = MdLitDecisaoINT::existeInfracao($_POST['id_md_lit_rel_dis_nor_con_ctr']);
                    $xml        = $resultHtml;
                    break;

                case 'md_lit_dispositivo_consultar':
                    $xml = MdLitDispositivoNormativoINT::consultaDispositivo($_POST['id_md_lit_disp_normat']);
                    $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($xml);
                    break;

                case 'motivo_auto_completar':
                    $dto = MdLitMotivoINT::consultarMotivoAjax($_POST);
                    $xml = InfraAjax::gerarXMLItensArrInfraDTO($dto, 'IdMdLitMotivo', 'Descricao');
                    break;
            }

            return $xml;

        }

        public function montarBotaoProcesso(ProcedimentoAPI $objProcedimentoAPI)
        {
            if(!SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_consultar') && $objProcedimentoAPI->getCodigoAcesso() > 0)
                return array();

            $strAcoesProcedimento = null;
            $arrRetorno           = null;
            $strBotao             = null;
            $idProcedimento       = $objProcedimentoAPI->getIdProcedimento();
            $idUnidade            = $_GET['infra_unidade_atual'];

            $procedimentoDTO = new ProcedimentoDTO();
            $procedimentoDTO->retTodos();
            $procedimentoDTO->setDblIdProcedimento($idProcedimento);

            $procedimentoRN  = new ProcedimentoRN();
            $procedimentoDTO = $procedimentoRN->consultarRN0201($procedimentoDTO);

            $objMdLitControleDTO = new MdLitControleDTO();
            $objMdLitControleDTO->retTodos();
            $objMdLitControleDTO->setDblIdProcedimento($idProcedimento);

            $objMdLitControleRN = new MdLitControleRN();
            $arrObjMdLitControleDTO = $objMdLitControleRN->listar($objMdLitControleDTO);

            //MdLitRelTipoControleTipoProcedimentoDTO
            $ObjRelTipoControleLitigiosoTipoProcedimentoRN  = new MdLitRelTipoControleTipoProcedimentoRN();
            $ObjRelTipoControleLitigiosoTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
            $ObjRelTipoControleLitigiosoTipoProcedimentoDTO->retTodos();
            $ObjRelTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoProcedimento($procedimentoDTO->getNumIdTipoProcedimento());
            $ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO = $ObjRelTipoControleLitigiosoTipoProcedimentoRN->listar($ObjRelTipoControleLitigiosoTipoProcedimentoDTO);
            //id_tipo_procedimento
            /**
             * "Configurar um tipo de controle com uma Unidade > Criar um Controle Litigioso com a Unidade do Tipo configurado > Voltar no Tipo de Controle e excluir a Unidade utilizada no Controle.
             *  O ícone não deve mais ser apresentado, nem mesmo aos Controles já criados anteriormente."
             */
            if(count($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO) > 0){
                $arrIdTipoControleLitigioso = InfraArray::converterArrInfraDTO($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO, 'IdTipoControleLitigioso');
            }elseif(count($arrObjMdLitControleDTO) > 0){
                $arrIdTipoControleLitigioso = InfraArray::converterArrInfraDTO($arrObjMdLitControleDTO, 'IdMdLitTipoControle');
            }else{
                return null;
            }

            //MdLitRelTipoControleUnidadeDTO
            $ObjRelTipoControleLitigiosoUnidadeRN  = new MdLitRelTipoControleUnidadeRN();
            $ObjRelTipoControleLitigiosoUnidadeDTO = new MdLitRelTipoControleUnidadeDTO();
            $ObjRelTipoControleLitigiosoUnidadeDTO->retTodos();
            $ObjRelTipoControleLitigiosoUnidadeDTO->setNumIdUnidade($idUnidade);
            $ObjRelTipoControleLitigiosoUnidadeDTO->setNumIdTipoControleLitigioso($arrIdTipoControleLitigioso, InfraDTO::$OPER_IN);
            $ArrObjRelTipoControleLitigiosoUnidadeDTO = $ObjRelTipoControleLitigiosoUnidadeRN->listar($ObjRelTipoControleLitigiosoUnidadeDTO);
            //id_unidade

            //RN1 - botão “Cadastrar/Alterar Controle Litigioso” somente será apresentado caso o tipo de processo e unidade,
            //estejam associados ao tipo de controle litigioso.
            if (count($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO) > 0 && count($ArrObjRelTipoControleLitigiosoUnidadeDTO) > 0 && $objProcedimentoAPI->getSinAberto() == 'S') {

                //$strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_validar_numero_sei&id_procedimento='.$idProcedimento);
                $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_cadastro_completo&id_procedimento=' . $idProcedimento);

                $imgIcone             = "modulos/litigioso/imagens/cadastro-ctrl-litigioso.svg";
                $title                = "Cadastrar/Alterar Controle Litigioso";
                $strAcoesProcedimento = '<a href="' . $strLink . '" class="botaoSEI"><img class="infraCorBarraSistema" src="' . $imgIcone . '" alt="' . $title . '" title="' . $title . '"></a>';

                // ou se já possuir algum controle litigioso já cadastrado
            } elseif(count($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO) == 0 && count($arrObjMdLitControleDTO) > 0 && count($ArrObjRelTipoControleLitigiosoUnidadeDTO) > 0 && $objProcedimentoAPI->getSinAberto() == 'S') {

                $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_cadastro_completo&id_procedimento=' . $idProcedimento);

                $imgIcone             = "modulos/litigioso/imagens/balanca_add.svg";
                $title                = "Cadastrar/Alterar Controle Litigioso";
                $strAcoesProcedimento = '<a href="' . $strLink . '" class="botaoSEI"><img class="infraCorBarraSistema" src="' . $imgIcone . '" alt="' . $title . '" title="' . $title . '"></a>';

            }elseif(count($arrObjMdLitControleDTO) > 0){

                //ação se o processo não estiver aberto será somente de consulta
                $imgIcone             = "modulos/litigioso/imagens/cadastro-ctrl-litigioso.svg";
                $title                = 'Consultar Controle Litigioso';
                $strLink              = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_cadastro_consultar&id_procedimento=' . $objProcedimentoAPI->getIdProcedimento());

                $strAcoesProcedimento = '<a href="' . $strLink . '" class="botaoSEI"><img class="infraCorBarraSistema" src="' . $imgIcone . '" alt="' . $title . '" title="' . $title . '"></a>';

            }

            //Add botão no processo quando já existe Situação Cadastrada
            $existePermissao = SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_listar');

            if($existePermissao) {
                $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
                $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
                $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
                $objMdLitProcessoSituacaoDTO->retDblIdProcedimento();
                $existeCadastro = $objMdLitProcessoSituacaoRN->contar($objMdLitProcessoSituacaoDTO) > 0;

                $existePermiConsulta = SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_consultar');
                if ($existeCadastro && $existePermiConsulta) {
                    $idTpControle = $objMdLitProcessoSituacaoRN->retornaTipoControle($objProcedimentoAPI);
                    if (!is_null($idTpControle)) {
                        $strBotao = $objMdLitProcessoSituacaoRN->addBotaoProcessoSituacao(false, $idTpControle, $objProcedimentoAPI->getIdProcedimento());
                    }elseif (count($arrIdTipoControleLitigioso) == 1){
                        $strBotao = $objMdLitProcessoSituacaoRN->addBotaoProcessoSituacao(false, $arrIdTipoControleLitigioso[0], $objProcedimentoAPI->getIdProcedimento());
                    }
                }
            }

            if(!is_null($strAcoesProcedimento) && !is_null($strBotao)){
                $arrRetorno = array($strAcoesProcedimento, $strBotao);
            }

            if(!is_null($strAcoesProcedimento) && is_null($strBotao)){
                $arrRetorno = array($strAcoesProcedimento);
            }

            if(is_null($strAcoesProcedimento) && !is_null($strBotao)){
                $arrRetorno = array($strBotao);
            }

            return $arrRetorno;

        }

        public function montarBotaoDocumento(ProcedimentoAPI $objProcedimentoAPI, $arrObjDocumentoAPI)
        {
            /*
             * Estando em Processo já cadastrado em Controle Litigioso e em tipos de documentos associados a Situações, aparece o botão "Controle Litigioso - Cadastro de Situações".
             * */

            $isExibirBtnSit = false;
            $idDocumento = false;
            $idTpControle = false;
            $arrBotoes = array();

            //Start Rns
            $objMdLitSerieRN = new MdLitRelSituacaoSerieRN();
            $objMdLitSituacaoRN = new MdLitSituacaoRN();
            $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();

            $existePermissao = SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_listar');
            $existePermiConsulta = SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_consultar');

            if ($existePermissao && $existePermiConsulta && $objProcedimentoAPI->getSinAberto() == 'S' && $objProcedimentoAPI->getCodigoAcesso() > 0) {
                $idTpControle = $objMdLitProcessoSituacaoRN->retornaTipoControle($objProcedimentoAPI);

                if (!is_null($idTpControle)) {
                    $objMdLitSituacaoDTO = new MdLitSituacaoDTO();

                    $objMdLitSituacaoDTO->retNumIdSituacaoLitigioso();
                    $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($idTpControle);

                    $countIsSituacao = $objMdLitSituacaoRN->contar($objMdLitSituacaoDTO) > 0;

                    foreach ($arrObjDocumentoAPI as $objDocumentoAPI) {
                        //Busca todas as situações dos tipos de controle que possuem esse processo vinculado
                        if ($countIsSituacao) {
                            $idsSituacao = InfraArray::converterArrInfraDTO($objMdLitSituacaoRN->listar($objMdLitSituacaoDTO), 'IdSituacaoLitigioso');
                            $idDocumento = $objDocumentoAPI->getIdDocumento();
                            //Verifica se alguma das situações possui esse documento vinculado


                            $objMdLitSerieDTO = new MdLitRelSituacaoSerieDTO();
                            $objMdLitSerieDTO->setNumIdSituacaoLitigioso($idsSituacao, InfraDTO::$OPER_IN);
                            $objMdLitSerieDTO->setNumIdSerie($objDocumentoAPI->getIdSerie());
                            $isExibirBtnSit = $objMdLitSerieRN->contar($objMdLitSerieDTO) > 0;
                        }

                        if ($isExibirBtnSit && $idDocumento) {

                            $objMdLitControleRN = new MdLitControleRN();
                            $existeCadastro = $objMdLitControleRN->existeCadastroControle($idDocumento);

                            if ($existeCadastro) {
                                $strBotao = $objMdLitProcessoSituacaoRN->addBotaoProcessoSituacao($idDocumento, $idTpControle, $objProcedimentoAPI->getIdProcedimento());
                                $arrBotoes[$idDocumento][] = $strBotao;
                            }
                        }

                    }
                }
            }

            return $arrBotoes;
        }

        public function montarIconeControleProcessos($arrObjProcedimentoAPI){
            if(SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_consultar')){
                $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
                $arrParam = $objMdLitProcessoSituacaoRN->montarIconeProcesso($arrObjProcedimentoAPI);

                $objMdLitTipoControleRN = new MdLitTipoControleRN();
                $arrParam = $objMdLitTipoControleRN->montarIconeControleProcessoSinalizarPendencia($arrObjProcedimentoAPI, $arrParam);


                return $arrParam;
            }
        }

        public function montarIconeAcompanhamentoEspecial($arrObjProcedimentoAPI){
            if(SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_consultar')) {
                $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
                $arrParam = $objMdLitProcessoSituacaoRN->montarIconeProcesso($arrObjProcedimentoAPI);

                $objMdLitTipoControleRN = new MdLitTipoControleRN();
                $arrParam = $objMdLitTipoControleRN->montarIconeControleProcessoSinalizarPendencia($arrObjProcedimentoAPI, $arrParam);

                return $arrParam;
            }
        }

        public function montarIconeProcesso(ProcedimentoAPI $objProcedimentoAPI){

            $arrObjArvoreAcaoItemAPI = array();

            if (SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_consultar') && $objProcedimentoAPI->getCodigoAcesso() > 0 ) {

                $dblIdProcedimento          = $objProcedimentoAPI->getIdProcedimento();
                $diferencaEntreDias         = '';
                $titulo                     = 'Controle Litigioso: ';
                $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();

                $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
                $objMdLitProcessoSituacaoDTO->retTodos(true);
                $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($dblIdProcedimento);
                $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_DESC);
                $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
                $objMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->consultar($objMdLitProcessoSituacaoDTO);
                if(!$objMdLitProcessoSituacaoDTO){
                    $objMdLitTipoControleRN = new MdLitTipoControleRN();
                    $arrParam = $objMdLitTipoControleRN->montarIconeProcessoSinalizarPendencia($objProcedimentoAPI, $arrObjArvoreAcaoItemAPI);
                    return $arrParam;
                }

                //buscando a intimação da instauração( a intimação da instauração será sempre a primeira situação de intimação depois da instauração)
                $objMdLitProcessoSituacaoIntimacaoInstauracao = $objMdLitProcessoSituacaoRN->consultarPrimeiraIntimacao($objProcedimentoAPI->getIdProcedimento());

                if($objMdLitProcessoSituacaoIntimacaoInstauracao){
                    $diferencaEntreDias = MdLitProcessoSituacaoINT::diferencaEntreDias($objMdLitProcessoSituacaoIntimacaoInstauracao->getDtaData());

                    if($diferencaEntreDias > 1){
                        $diferencaEntreDias = '\n Tempo desde a Intimação: ' . $diferencaEntreDias . ' dias';
                    }else{
                        $diferencaEntreDias = '\n Tempo desde a Intimação: ' . $diferencaEntreDias . ' dia';
                    }
                }
                $titulo .= $objMdLitProcessoSituacaoDTO->getStrSiglaTipoControleLitigioso().'\n';
                $tipoSituacao = $objMdLitProcessoSituacaoDTO->getStrTipoSituacao();
                $tipoSituacao = $tipoSituacao['nome'] != '' ? " ({$tipoSituacao['nome']})" : '';
                $titulo         .= 'Fase: '. $objMdLitProcessoSituacaoDTO->getStrNomeFase(). ' \n\n Situação: '. $objMdLitProcessoSituacaoDTO->getStrNomeSituacao() . $tipoSituacao.' \n ' . $diferencaEntreDias;

                $objArvoreAcaoItemAPI = new ArvoreAcaoItemAPI();
                $objArvoreAcaoItemAPI->setTipo('MD_LIT_PROCESSO');
                $objArvoreAcaoItemAPI->setId('MD_LIT_PROC_' . $dblIdProcedimento);
                $objArvoreAcaoItemAPI->setIdPai($dblIdProcedimento);
                $objArvoreAcaoItemAPI->setTitle($titulo);
                $objArvoreAcaoItemAPI->setIcone('modulos/litigioso/imagens/scales.png');

                $objArvoreAcaoItemAPI->setTarget(null);
                $objArvoreAcaoItemAPI->setHref('#');

                $objArvoreAcaoItemAPI->setSinHabilitado('S');

                $arrObjArvoreAcaoItemAPI[] = $objArvoreAcaoItemAPI;
            }


            return $arrObjArvoreAcaoItemAPI;
        }

        public function excluirDocumento(DocumentoAPI $objDocumentoAPI){

            if (SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_consultar')) {
                $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
                $objMdLitProcessoSituacaoDTO->retTodos();
                $objMdLitProcessoSituacaoDTO->retStrNomeFase();
                $objMdLitProcessoSituacaoDTO->retStrNomeSituacao();
                $objMdLitProcessoSituacaoDTO->setDblIdDocumento($objDocumentoAPI->getIdDocumento());
                $objMdLitProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
                $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);

                $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
                $objMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->consultar($objMdLitProcessoSituacaoDTO);

                if($objMdLitProcessoSituacaoDTO){
                    $msg = 'Não é permitido excluir este documento, pois ele está relacionado com a Fase/Situação '.$objMdLitProcessoSituacaoDTO->getStrNomeFase().'/'.$objMdLitProcessoSituacaoDTO->getStrNomeSituacao().' no Módulo de Controle Litigioso.';
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao($msg);
                    $objInfraException->lancarValidacoes();
                }
            }
            return null;
        }

        public function cancelarDocumento(DocumentoAPI $objDocumentoAPI){

            if (SessaoSEI::getInstance()->verificarPermissao('md_lit_processo_situacao_consultar')) {
                $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
                $objMdLitProcessoSituacaoDTO->retTodos();
                $objMdLitProcessoSituacaoDTO->retStrNomeFase();
                $objMdLitProcessoSituacaoDTO->retStrNomeSituacao();
                $objMdLitProcessoSituacaoDTO->setDblIdDocumento($objDocumentoAPI->getIdDocumento());
                $objMdLitProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
                $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);

                $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
                $objMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->consultar($objMdLitProcessoSituacaoDTO);

                if($objMdLitProcessoSituacaoDTO){
                    $msg = 'Não é permitido cancelar este documento, pois ele está relacionado com a Fase/Situação '.$objMdLitProcessoSituacaoDTO->getStrNomeFase().'/'.$objMdLitProcessoSituacaoDTO->getStrNomeSituacao().' no Módulo de Controle Litigioso.';
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao($msg);
                    $objInfraException->lancarValidacoes();
                }
            }
            return null;
        }

        public function excluirContato($arrObjContatoAPI)
        {
;
            if (SessaoSEI::getInstance()->verificarPermissao('md_lit_dado_interessado_listar')) {
                $arrIdContato = array();
                foreach ($arrObjContatoAPI as $objContatoAPI){
                    $arrIdContato[] = $objContatoAPI->getIdContato();
                }

                $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
                $objMdLitDadoInteressadoDTO->retTodos(false);
                $objMdLitDadoInteressadoDTO->setNumIdContato($arrIdContato, InfraDTO::$OPER_IN);

                $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
                $arrObjMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->listar($objMdLitDadoInteressadoDTO);

                if(count($arrObjMdLitDadoInteressadoDTO)){
                    $msg = 'Não é permitido excluir este Contato, pois ele é utilizado pelo Módulo Litigioso.';
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao($msg);
                    $objInfraException->lancarValidacoes();
                }

            }
            return parent::excluirContato($arrObjContatoAPI);
        }

    }