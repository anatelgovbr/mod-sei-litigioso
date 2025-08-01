<div class="row mt-2 mb-2">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <fieldset class="infraFieldset form-control" id="fieldsetMulta"
                  style="<?php echo (!$blnDecisaoMultaIntegracao || $strGridDecisao == '' && !$objMdLitLancamentoDTO) ? 'display:none' : '' ?>">
            <legend class="infraLegend">Gest�o de Multa</legend>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                    <?php if ($isVincularLancamento) { ?>
                        <!-- Vincular Lan�amento -->
                        <button id="btnVincularLancamento" type="button" name="btnVincularLancamento"
                                value="Vincular Lan�amento"
                                style="display: none;" class="infraButton btnLancamento"
                                onclick="abrirModalVincularLancamento(this)"
                                data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_vincular_lancamento&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO); ?>">
                            Vincular Lan�amento
                        </button>
                    <?php } ?>

                    <!-- Incluir Lan�amento -->
                    <button id="btnIncluirLancamento" type="button" name="btnIncluirLancamento"
                            value="Incluir Lan�amento"
                            style="display: none;" class="infraButton btnLancamento"
                            onclick="abrirModalJustificativaLancamento(this)"
                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO); ?>">
                        Incluir Lan�amento
                    </button>

                    <!-- Cancelar Lan�amento -->
                    <button id="btnCancelarLancamento" name="btnCancelarLancamento" value="Cancelar Lan�amento"
                            style="display: none;" onclick="abrirModalCancelarLancamento()" type="button"
                            class="infraButton btnLancamento">Cancelar Lan�amento
                    </button>

                    <!-- Retificar Lan�amento -->
                    <button id="btnRetificarLancamento" name="btnRetificarLancamento" type="button"
                            onclick="retificarLancamento(this)"
                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO); ?>"
                            value="Retificar Lan�amento"
                            style="display: none;" class="infraButton btnLancamento">Retificar Lan�amento
                    </button>

                    <!-- Suspender Lan�amento -->
                    <button id="btnSuspenderLancamento" name="btnSuspenderLancamento" value="Suspender Lan�amento"
                            type="button"
                            onclick="suspenderEmRazaoRecurso(this)"
                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO); ?>"
                            style="display: none;" class="infraButton btnLancamento">Suspender em Raz�o de Recurso
                    </button>

                    <!-- Denegar Recurso -->
                    <button id="btnDenegarRecurso" name="btnDenegarRecurso" value="Denegar Recurso" type="button"
                            onclick="denegarRecurso(this)"
                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_DENEGAR_RECURSO); ?>"
                            style="display: none;" class="infraButton btnLancamento">Denegar Recurso
                    </button>

                    <!-- Cancelar Recurso -->
                    <button id="btnCancelarRecurso" name="btnCancelarRecurso" value="Cancelar Recurso" type="button"
                            onclick="cancelarRecurso(this)"
                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_RECURSO); ?>"
                            style="display: none;" class="infraButton btnLancamento">Cancelar Recurso
                    </button>

                    <!-- Hist�rico de Lan�amento -->
                    <button id="btnHistoricoLancamento" name="btnHistoricoLancamento" value="Hist�rico de Lan�amentos"
                            onclick="abrirModalHistoricoLancamento()" type="button" class="infraButton btnLancamento">
                        Hist�rico de Lan�amentos
                    </button>
                </div>
            </div>
            <!--  Cr�ditos do Processo -->
            <div class="row" style="display: none;" id="divCreditoProcesso">
                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblCreditoProcesso" name="lblCreditoProcesso"> Cr�ditos do Processo: </label>
                    <select class="infraSelect form-control" id="selCreditosProcesso" name="selCreditosProcesso"
                            onchange="mudarCreditosProcesso(this)">
                        <?= $strComboCreditoProcesso ?>
                    </select>
                </div>
            </div>
            <!--Total da Multa Aplicada -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label class="infraLabelOpcional" id="lblTotalMulta" name="lblTotalMulta">
                        Total de Multa Aplicada:
                    </label>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                    <label id="lblVlTotalMulta" name="lblVlTotalMulta" class="infraLabelOpcional">
                        R$ 0,00
                    </label>
                </div>
            </div>
            <!--Cr�dito n�o Lan�ado: -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblCreditoNaoLancado" name="lblCreditoNaoLancado" class="infraLabelOpcional">
                        Cr�dito n�o Lan�ado:
                    </label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblVlCreditoNaoLancado" name="lblVlCreditoNaoLancado" class="infraLabelOpcional">
                        R$ 0,00
                    </label>
                </div>
            </div>
            <!--Cr�dito Lancado: -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblCreditoLancado" name="lblCreditoLancado" class="infraLabelOpcional">
                        Cr�dito Lan�ado:
                    </label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblVlCreditoLancado" name="lblVlCreditoLancado" class="infraLabelOpcional">
                        R$ 0,00
                    </label>
                </div>
            </div>
            <!-- Desconto -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblDesconto" name="lblDesconto" class="infraLabelOpcional">
                        Desconto:
                    </label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblVlDesconto" name="lblVlDesconto" class="infraLabelOpcional">
                        R$ 0,00
                    </label>
                </div>
            </div>
            <!-- Arrecadado -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblArrecadado" name="lblArrecadado" class="infraLabelOpcional">
                        Arrecadado:
                    </label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblVlArrecadado" name="lblVlArrecadado" class="infraLabelOpcional">
                        R$ 0,00
                    </label>
                </div>
            </div>
            <!-- Data do �ltimo pagamento -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblDtUltimoPag" name="lblDtUltimoPag" class="infraLabelOpcional">
                        Data do �ltimo Pagamento:
                    </label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblVlDtUltimoPag" name="lblVlDtUltimoPag" class="infraLabelOpcional">

                    </label>
                </div>
            </div>
            <!-- Salvo devedor atualizado  -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblSaldoDevAtualizado" name="lblSaldoDevAtualizado" class="infraLabelOpcional">
                        Saldo Devedor Atualizado:
                    </label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblVlSaldoDevAtualizado" name="lblVlSaldoDevAtualizado" class="infraLabelOpcional">
                        R$ 0,00
                    </label>
                </div>
            </div>
            <!-- Cr�dito Constitu�do Definitivamente  -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblCredConstDef" name="lblCredConstDef" class="infraLabelOpcional">
                        Cr�dito Constitu�do Definitivamente:
                    </label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblVlCredConstDef" name="lblVlCredConstDef" class="infraLabelOpcional">
                        R$ 0,00
                    </label>
                </div>
            </div>
            <br>
            <div class="row linha">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <!--Data do Decurso do Prazo para Defesa -->
                    <div>
                        <div class="row">
                            <div class="col-sm-12 col-md-11 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtDecursoPrazo" name="lblDtDecursoPrazo"
                                           for="txtDtDecursoPrazo">Data do Decurso do Prazo para Defesa:</label>

                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtDecursoPrazo" <?= PaginaSEI::montarTitleTooltip('A  Data do Decurso do Prazo para Defesa � calculada automaticamente a partir da Data da Intima��o da Instaura��o, em quantidade de dias previamente definida na Parametriza��o de Situa��es para situa��o do tipo Defesa.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtDecursoPrazo" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <select class="infraSelect form-control" name="selDtDecursoPrazo" onchange="atualizarHiddenDtDecursoPrazo();"
                                            id="selDtDecursoPrazo" <?= $strCombodataDecursoPrazoDefesa['disabled'] ? 'disabled' : '' ?> >
                                        <?= $strCombodataDecursoPrazoDefesa['htmlOption'] ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="divDataGestaoMulta">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDocumentoAplicacaoMulta"
                                           name="lblDocumentoAplicacaoMulta" for="txtDocumentoAplicacaoMulta">Documento da Decis�o de Aplica��o da Multa:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDocumentoAplicacaoMulta" <?= PaginaSEI::montarTitleTooltip('Este campo informa o n�mero SEI do Documento da Decis�o de Aplica��o da Multa, e deve se referir sempre � primeira decis�o de m�rito v�lida do Processo.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtDecisaoAplicacaoMulta" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3">
                                        <input class="campoData infraText campoDisable"
                                               type="text" id="txtSituacaoDocOrigem" name="txtSituacaoDocOrigem"
                                               value="" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Data da Decis�o de Aplica��o de Multa -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDtDecisaoAplicacaoMulta"
                                        name="lblDtDecisaoAplicacaoMulta" for="txtDecisaoAplicacaoMulta">Data da Decis�o
                                        de
                                        Aplica��o da Multa:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtDecisaoAplicacaoMulta" <?= PaginaSEI::montarTitleTooltip('A Data da Decis�o de Aplica��o da Multa corresponde a Data de Assinatura do Documento Decis�rio.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtDecisaoAplicacaoMulta" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3">
                                        <input onchange="verificarMudancaMulta();return validarFormatoData(this)"
                                            class="campoData infraText campoDisable"
                                            type="text" id="txtDecisaoAplicacaoMulta" name="txtDecisaoAplicacaoMulta"
                                            onkeypress="return infraMascara(this, event, '##/##/####');"
                                            value="<?= $dataDecisaoAplicacaoMulta ?>" disabled
                                            campo-mapea-param-entrada="<?= in_array('dataAplicacaoSancao', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data da Intima��o da Decis�o de Aplica��o de Multa -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDtIntimacaoAplMulta"
                                        name="lblDtIntimacaoAplMulta"
                                        for="txtDtIntimacaoAplMulta">Data da Intima��o da Decis�o de Aplica��o da
                                        Multa:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtIntimacaoAplMulta" <?= PaginaSEI::montarTitleTooltip('Corresponde � data da Intima��o da Decis�o de aplica��o de Multa e exigir� a Retifica��o de Lan�amento se este campo estiver integrado com o sistema de arrecada��o. \n \n O sistema faz uma sugest�o autom�tica que deve ser conferida e ajustada,  se for o caso, a partir da Lista de Situa��es, na coluna A��es, por meio do bot�o Alterar Item.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtIntimacaoAplMulta" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3" id="divDtaIntimacaoAplMulta">
                                        <input onchange="armazenarDataIntimacaoMulta(this); verificarMudancaMulta(); return validarFormatoData(this);"
                                            class="campoData infraText campoDisable" type="text"
                                            id="txtDtIntimacaoAplMulta"
                                            name="txtDtIntimacaoAplMulta"
                                            onkeypress="return infraMascara(this, event, '##/##/####');" value="" disabled
                                            campo-mapea-param-entrada="<?= in_array('dataNotificacaoInicial', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data do Decurso do Prazo para Recurso -->
                        <div class="row" id="divDtaDecursoPrazoRecurso">
                            <div class="col-sm-12 col-md-11 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDtDecursoPrazoRecurso"
                                           name="lblDtDecursoPrazoRecurso"
                                           for="txtDtDecursoPrazoRecurso">Data do Decurso do Prazo para Recurso:</label>

                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtDecursoPrazoRecurso" <?= PaginaSEI::montarTitleTooltip('A Data do Decurso do Prazo para Recurso � calculada automaticamente, a contar da Data da Intima��o da Decis�o de Aplica��o da Multa correspondente, em quantidade de dias previamente definida na Parametriza��o de Situa��es, e exigir� a Retifica��o de Lan�amento se este campo estiver integrado com o sistema de arrecada��o.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtDecursoPrazoRecurso" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <select class="infraSelect form-control" name="selDtDecursoPrazoRecurso" onchange="atualizarHiddenDtDecursoPrazoRecurso();"
                                            id="selDtDecursoPrazoRecurso">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="divDtaDecursoPrazoRecurso" style="display: none">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDtDecursoPrazoRecurso"
                                        name="lblDtDecursoPrazoRecurso"
                                        for="txtDtDecursoPrazoRecurso">Data do Decurso do Prazo para Recurso:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtDecursoPrazoRecurso" <?= PaginaSEI::montarTitleTooltip('A Data do Decurso do Prazo para Recurso � calculada automaticamente, a contar da Data da Intima��o da Decis�o de Aplica��o da Multa correspondente, em quantidade de dias previamente definida na Parametriza��o de Situa��es, e exigir� a Retifica��o de Lan�amento se este campo estiver integrado com o sistema de arrecada��o.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtDecursoPrazoRecurso" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3">
                                        <input onchange="return validarFormatoData(this);verificarMudancaMulta();"
                                            class="campoData infraText campoDisable"
                                            type="text"
                                            id="txtDtDecursoPrazoRecurso" name="txtDtDecursoPrazoRecurso"
                                            onkeypress="return infraMascara(this, event, '##/##/####');" value=""
                                            campo-mapea-param-entrada="<?= in_array('dataDecursoPrazoRecursoImpugnacao', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>" disabled/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data de Vencimento -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtVencimento" name="lblDtVencimento"
                                        for="txtDtVencimento">Data de Vencimento:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtVencimento" <?= PaginaSEI::montarTitleTooltip('A Data de Vencimento para o pagamento da Multa deve ser informada neste campo. \n \n O sistema faz uma sugest�o autom�tica a partir do que foi sugerido para o campo de Data da Decis�o de Aplica��o da Multa acrescido de 40 dias, mas que deve ser conferida e ajustada se for o caso.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtVencimento" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3">
                                        <input onchange="verificarMudancaMulta(); return validarFormatoData(this);"
                                            class="campoData infraText"
                                            type="text" id="txtDtVencimento" name="txtDtVencimento"
                                            onkeypress="return infraMascara(this, event, '##/##/####');"
                                            value="<?= $dataVencimento ?>"
                                            campo-mapea-param-entrada="<?= in_array('dataVencimento', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data de Vencimento" alt="Selecionar Data de Vencimento"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtVencimento',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Data de Apresenta��o do Recurso -->
                        <div class="row" id="apresentacao-recurso" style="<?= $dtaApresentacaoRecurso ? '' : 'display: none' ?> ">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDtApresentacaoRecurso"
                                           name="lblDtApresentacaoRecurso" for="txtDtApresentacaoRecurso">Data de Apresenta��o do Recurso:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtIntConstDef" <?= PaginaSEI::montarTitleTooltip('A Data de Apresenta��o do Recurso � a data do protocolo do Documento no �rg�o e exigir� a Retifica��o de Lan�amento se este campo estiver integrado com o sistema de arrecada��o. \n \n A altera��o desta data � poss�vel a partir da Lista de Situa��es, na coluna A��es, por meio do bot�o Alterar Item.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtIntConstDef" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3">
                                        <input onchange="verificarMudancaMulta()"
                                               class="campoData infraText campoDisable"
                                               type="text"
                                               id="txtDtApresentacaoRecurso"
                                               name="txtDtApresentacaoRecurso"
                                               onkeypress="return infraMascara(this, event, '##/##/####');"
                                               value="" disabled
                                               campo-mapea-param-entrada="<?= in_array('dataApresRecursoImpugnacao', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Interessado -->
                        <div class="row">
                            <div class="col-sm-12 col-md-11 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblInteressado" name="lblInteressado"
                                        for="txtInteressado">Interessado:</label>
                                    <select class="infraSelect form-control" name="selInteressado"
                                            id="selInteressado" <?= $objMdLitLancamentoDTO ? 'disabled="disabled"' : '' ?> >
                                        <?= $strComboInteressado ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--Numero do interessado -->
                            <div class="row" id="div-numero">
                                <div class="col-sm-12 col-md-11 col-lg-9 col-xl-8" style="<?php echo $objMdLitTipoControleDTO->getStrSinParamModalComplInteressado() == 'N' ? 'display:none' : ''; ?>">
                                    <div class="form-group">
                                        <label class="infraLabelObrigatorio" id="lbNumeroInteressado"
                                            name="lblNumeroInteressado"
                                            for="txtNumeroInteressado">N�mero de Complemento do Interessado:</label>
                                        <select onchange="verificarMudancaMulta()"
                                                class="infraSelect form-control"
                                                name="selNumeroInteressado"
                                                id="selNumeroInteressado"
                                                data-id-dado-interessado="<?= $objMdLitLancamentoDTO ? $objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado() : '' ?>" <?= $objMdLitLancamentoDTO ? 'disabled="disabled"' : '' ?>
                                                campo-mapea-param-entrada="<?= in_array('fistel', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                        </select>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div id="divHouveConstituicao">
                        <!-- Houve constitui��o -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divHouveConstituicaoChk">
                                <div class="form-group">
                                    <br>
                                    <input type="checkbox" name="chkHouveConstituicao" id="chkHouveConstituicao" value="S" class="infraCheckbox"
                                        onchange="houveConstituicao(this);verificarMudancaMulta()">
                                    <label class="infraLabelOpcional" id="lblHouveConstituicao" name="lblHouveConstituicao"
                                        for="chkHouveConstituicao">Houve constitui��o definitiva do cr�dito?</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                    id="btAjudaHouveConstituicao" <?= PaginaSEI::montarTitleTooltip('A Constitui��o Definitiva do Cr�dito ficar� dispon�vel somente a partir do cadastro da Situa��o de Tr�nsito em Julgado (Conclusiva).', 'Ajuda') ?>
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaHouveConstituicao" border="0"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                            class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--Documento Aplica��o San��o -->
                        <div class="row nao-tem-constituicao">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDocumento" name="lblDocumento"
                                           for="txtDocumento">Documento da Decis�o Definitiva:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtIntConstDef" <?= PaginaSEI::montarTitleTooltip('Neste campo deve ser selecionado o documento referente � Decis�o Definitiva de Aplica��o da Multa.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtIntConstDef" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <select class="infraSelect form-control" name="selDocumento"
                                            id="selDocumento" <?= $objMdLitLancamentoDTO ? 'disabled="disabled"' : '' ?>
                                            onchange="atualizarDataDecisaoDefinitiva()"
                                            campo-mapea-param-entrada="<?= in_array('numDocOrigem', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>">
                                        <?= $strComboDocumento ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!--Data da Decis�o Definitiva -->
                        <div class="row nao-tem-constituicao">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDtDecisaoDefinitiva"
                                           name="lblDtDecisaoDefinitiva" for="txtDtDecisaoDefinitiva">Data da
                                        Decis�o Definitiva:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtIntConstDef" <?= PaginaSEI::montarTitleTooltip('A Data da Decis�o Definitiva � a data informada no cadastro da Situa��o referente � Decis�o Definitiva de Aplica��o da Multa. \n \n � importante conferir se o campo Data da Decis�o Definitiva informa a data correta antes de constituir definitivamente o cr�dito. Caso seja necess�rio, a altera��o desta data � poss�vel a partir da Lista de Situa��es, na coluna A��es, por meio do bot�o Alterar Item. Essa altera��o exigir� a Retifica��o de Lan�amento se este campo estiver integrado com o sistema de arrecada��o.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtIntConstDef" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3">
                                        <input onchange="verificarMudancaMulta()"
                                               class="campoData infraText campoDisable"
                                               type="text" id="txtDtDecisaoDefinitiva"
                                               name="txtDtDecisaoDefinitiva"
                                               onkeypress="return infraMascara(this, event, '##/##/####');"
                                               value="<?= $dtaDecisaoDefinitiva ?>" disabled
                                               campo-mapea-param-entrada="<?= in_array('dataDecisaoDefinitiva', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data da Constitui��o -->
                        <div class="row tem-constituicao" id="divtxtDtConstituicao" style="display: none">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtConstituicao" name="lblDtConstituicao"
                                        for="txtDtConstituicao">Data da Constitui��o Definitiva:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtConstDef" <?= PaginaSEI::montarTitleTooltip('Deve informar a data correspondente � Constitui��o Definitiva, que refere-se ao dia seguinte � finaliza��o do prazo de 30 dias concedido para pagamento ap�s a intima��o da decis�o definitiva. \n \n O sistema faz uma sugest�o autom�tica que deve ser conferida e ajustada se for o caso.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="btAjudaDtConstDef" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3">
                                        <input onchange="verificarMudancaMulta()"
                                               class="campoData infraText"
                                               type="text" id="txtDtConstituicao"
                                               name="txtDtConstituicao"
                                               onkeypress="return infraMascara(this, event, '##/##/####');"
                                               value="<?= $dtaConstituicaoDefinitiva ?>"
                                               campo-mapea-param-entrada="<?= in_array('dataConstituicao', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data da Constitui��o Definitiva"
                                            alt="Selecionar Data da Constitui��o Definitiva"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtConstituicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data da Intima��o da Constitui��o -->
                        <div class="row nao-tem-constituicao">
                            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtIntimacaoConstituicao"
                                        name="lblDtIntimacaoConstituicao" for="txtDtIntimacaoConstituicao">Data da Intima��o da
                                        Decis�o Definitiva:</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                       id="btAjudaDtIntConstDef" <?= PaginaSEI::montarTitleTooltip('Corresponde � data da Intima��o da Decis�o Definitiva de aplica��o de Multa. \n \n Caso seja necess�rio alterar essa data, ser� exigida nova Constitui��o Definitiva ou a Retifica��o de Lan�amento, se este campo estiver integrado com o sistema de arrecada��o.', 'Ajuda') ?>
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaDtIntConstDef" border="0"
                                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                             class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <div class="input-group mb-3">
                                        <input type="text" id="txtDtIntimacaoConstituicao"
                                            name="txtDtIntimacaoConstituicao"
                                            class="campoData infraText"
                                            onchange="verificarMudancaMulta();return validarFormatoData(this);"
                                            onkeypress="return infraMascara(this, event, '##/##/####');"
                                            value="<?= $dtaIntimacaoDefinitiva ?>"
                                            campo-mapea-param-entrada="<?= in_array('dataIntimacaoDecisaoDefinitiva', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data da Intima��o da Constitui��o Definitiva"
                                            alt="Selecionar Data da Intima��o da Constitui��o Definitiva"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtIntimacaoConstituicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Redu��o pela ren�ncia ao direito de recorrer -->
                        <div class="row nao-tem-constituicao">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <br>
                                    <input type="checkbox" name="chkReducaoRenuncia" id="chkReducaoRenuncia" value="S" class="infraCheckbox"
                                        onchange="verificarMudancaMulta()"
                                        campo-mapea-param-entrada="<?= in_array('desconto', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                    <label class="infraLabelOpcional" id="lblReducaoRenuncia" name="lblReducaoRenuncia"
                                        for="chkReducaoRenuncia">Desconto decorrente da ren�ncia ao direito de recorrer</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                    id="btAjudaHouveConstituicao" <?= PaginaSEI::montarTitleTooltip('Esta op��o deve ser marcada somente se o Interessado apresentou formalmente e tenha sido aceito pedido de Ren�ncia ao Direito de Recorrer, obtendo dessa forma a redu��o no valor da Multa, conforme regulamenta��o. \n \n Ap�s a Constitui��o Definitiva ser realizada, apenas o Gestor do Controle Litigioso poder� efetivar corre��es materiais.', 'Ajuda') ?>
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaHouveConstituicao" border="0"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                            class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                    <br />
                                    <br>
                                    <br>
                                    <button id="btnConstituirDefinitivamente" type="button" name="btnConstituirDefinitivamente"
                                            value="Constituir Definitivamente"
                                            onclick="abrirModalConstituirDefinitivamente(this)" style="display: none;"
                                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO . '&constituir_definitivamente=1'); ?>"
                                            class="infraButton">Constituir Definitivamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<!-- Hiddens -->
<input type="hidden" name="hdnVlTotalMulta" id="hdnVlTotalMulta" value=""/>
<input type="hidden" name="hdnVlCreditoNaoLancado" id="hdnVlCreditoNaoLancado" value=""/>
<input type="hidden" name="hdnVlCreditoLancado" id="hdnVlCreditoLancado" value=""/>
<input type="hidden" name="hdnVlDesconto" id="hdnVlDesconto" value=""/>
<input type="hidden" name="hdnVlArrecadado" id="hdnVlArrecadado" value=""/>
<input type="hidden" name="hdnVlDtUltimoPagamento" id="hdnVlDtUltimoPagamento" value=""/>
<input type="hidden" name="hdnVlSaldoDevAtualizado" id="hdnVlSaldoDevAtualizado" value=""/>
<input type="hidden" name="hdnVlCredConstituidoDef" id="hdnVlCredConstituidoDef" value=""/>
<input type="hidden" name="hdnDtDecursoPrazo" id="hdnDtDecursoPrazo" value="<?= $strCombodataDecursoPrazoDefesa['hdnDtDecursoPrazo']   ?>"/>
<input type="hidden" name="hdnTbVincularLancamento" id="hdnTbVincularLancamento" value=""/>
<input type="hidden" name="hdnStrUltimaSituacao" id="hdnStrUltimaSituacao" value="<?= trim($strUltimaSituacao); ?>"/>
<input type="hidden" name="hdnStrNomeUltimaSituacao" id="hdnStrNomeUltimaSituacao" value="<?= trim($strNomeUltimaSituacao); ?>"/>
<input type="hidden" name="hdnExisteSuspensao" id="hdnExisteSuspensao" value="<?= trim($existeSuspencao); ?>"/>
<input type="hidden" name="hdnPrazoDefesa" id="hdnPrazoDefesa" value=""/>
<input type="hidden" name="hdnTpPrazoDefesa" id="hdnTpPrazoDefesa" value=""/>
<input type="hidden" name="hdnPrazoRecurso" id="hdnPrazoRecurso" value=""/>
<input type="hidden" name="hdnTpPrazoRecurso" id="hdnTpPrazoRecurso" value=""/>

<!-- Hidden id da Funcionalidade que est� sendo manipulada -->
<input type="hidden" name="hdnIdMdLitFuncionalidade" id="hdnIdMdLitFuncionalidade" value=""/>

<!-- Hiddens para Lan�amento -->
<input type="hidden" name="hdnJustificativaLancamento" id="hdnJustificativaLancamento" value=""/>

<!-- Hiddens para Cancelamento -->
<input type="hidden" name="hdnIdMotivoCancelamento" id="hdnIdMotivoCancelamento" value=""/>
<input type="hidden" name="hdnJustificativaCancelamento" id="hdnJustificativaCancelamento" value=""/>
<input type="hidden" name="hdnTxtMotivoCancelamento" id="hdnTxtMotivoCancelamento" value=""/>

<!-- Hidden suspenso para as situa��es recursal -->
<input type="hidden" name="hdnSinSuspenso" id="hdnSinSuspenso"
       value="<?= $objMdLitLancamentoDTO ? $objMdLitLancamentoDTO->getStrSinSuspenso() : '' ?>"/>

<input type="hidden" id="hdnNumInteressado" name="hdnNumInteressado" value="<?= $numInteressado ?>">

<!-- Hidden para verificar o valor original das multas -->
<input type="hidden" name="hdnVlOriginalMultas" id="hdnVlOriginalMultas" value=""/>

<!-- Hidden para permitir suspender em raz�o de recuro-->
<input type="hidden" name="hdnSuspRazRecurso" id="hdnSuspRazRecurso" value=""/>
<input type="hidden" name="btnCancelarLancamentoAtivo" id="btnCancelarLancamentoAtivo" value=""/>

<!-- Hidden para retificar lancamento-->
<input type="hidden" name="hdnCreditosProcesso" id="hdnCreditosProcesso" value=""/>
<input type="hidden" name="hdnDtDecisaoDefinitiva" id="hdnDtDecisaoDefinitiva" value=""/>
<input type="hidden" name="hdnDtApresentacaoRecurso" id="hdnDtApresentacaoRecurso" value=""/>
<input type="hidden" name="hdnDecisaoAplicacaoMulta" id="hdnDecisaoAplicacaoMulta" value=""/>
<input type="hidden" name="hdnDtIntimacaoAplMulta" id="hdnDtIntimacaoAplMulta" value=""/>
<input type="hidden" name="hdnIdSituacaoDecisao" id="hdnIdSituacaoDecisao" value=""/>
<input type="hidden" name="hdnIdSituacaoIntimacao" id="hdnIdSituacaoIntimacao" value=""/>
<input type="hidden" name="hdnIdSituacaoRecurso" id="hdnIdSituacaoRecurso" value=""/>
<input type="hidden" name="hdnDtDecursoPrazoRecurso" id="hdnDtDecursoPrazoRecurso" value=""/>