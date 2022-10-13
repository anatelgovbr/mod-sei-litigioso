<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <fieldset class="infraFieldset form-control" id="fieldsetMulta"
                  style="<?php echo (!$blnDecisaoMultaIntegracao || $strGridDecisao == '' && !$objMdLitLancamentoDTO) ? 'display:none' : '' ?>">
            <legend class="infraLegend">Gestão de Multa</legend>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                    <?php if ($isVincularLancamento) { ?>
                        <!-- Vincular Lançamento -->
                        <button id="btnVincularLancamento" type="button" name="btnVincularLancamento"
                                value="Vincular Lançamento"
                                style="display: none;" class="infraButton btnLancamento"
                                onclick="abrirModalVincularLancamento(this)"
                                data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_vincular_lancamento&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO); ?>">
                            Vincular Lançamento
                        </button>
                    <?php } ?>

                    <!-- Incluir Lançamento -->
                    <button id="btnIncluirLancamento" type="button" name="btnIncluirLancamento"
                            value="Incluir Lançamento"
                            style="display: none;" class="infraButton btnLancamento"
                            onclick="abrirModalJustificativaLancamento(this)"
                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO); ?>">
                        Incluir Lançamento
                    </button>

                    <!-- Cancelar Lançamento -->
                    <button id="btnCancelarLancamento" name="btnCancelarLancamento" value="Cancelar Lançamento"
                            style="display: none;" onclick="abrirModalCancelarLancamento()" type="button"
                            class="infraButton btnLancamento">Cancelar Lançamento
                    </button>

                    <!-- Retificar Lançamento -->
                    <button id="btnRetificarLancamento" name="btnRetificarLancamento" type="button"
                            onclick="retificarLancamento(this)"
                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO); ?>"
                            value="Retificar Lançamento"
                            style="display: none;" class="infraButton btnLancamento">Retificar Lançamento
                    </button>

                    <!-- Suspender Lançamento -->
                    <button id="btnSuspenderLancamento" name="btnSuspenderLancamento" value="Suspender Lançamento"
                            type="button"
                            onclick="suspenderEmRazaoRecurso(this)"
                            data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento=' . $idProcedimento . '&id_md_lit_funcionalidade=' . MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO); ?>"
                            style="display: none;" class="infraButton btnLancamento">Suspender em Razão de Recurso
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

                    <!-- Histórico de Lançamento -->
                    <button id="btnHistoricoLancamento" name="btnHistoricoLancamento" value="Histórico de Lançamentos"
                            onclick="abrirModalHistoricoLancamento()" type="button" class="infraButton btnLancamento">
                        Histórico de Lançamentos
                    </button>
                </div>
            </div>
            <!--  Créditos do Processo -->
            <div class="row" style="display: none;" id="divCreditoProcesso">
                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
                    <label id="lblCreditoProcesso" name="lblCreditoProcesso"> Créditos do Processo: </label>
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
            <!--Crédito não Lançado: -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblCreditoNaoLancado" name="lblCreditoNaoLancado" class="infraLabelOpcional">
                        Crédito não Lançado:
                    </label>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblVlCreditoNaoLancado" name="lblVlCreditoNaoLancado" class="infraLabelOpcional">
                        R$ 0,00
                    </label>
                </div>
            </div>
            <!--Crédito Lancado: -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblCreditoLancado" name="lblCreditoLancado" class="infraLabelOpcional">
                        Crédito Lançado:
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
            <!-- Data do último pagamento -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblDtUltimoPag" name="lblDtUltimoPag" class="infraLabelOpcional">
                        Data do último Pagamento:
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
            <!-- Crédito Constituído Definitivamente  -->
            <div class="row linha">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3">
                    <label id="lblCredConstDef" name="lblCredConstDef" class="infraLabelOpcional">
                        Crédito Constituído Definitivamente:
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
                    <div id="divDataGestaoMulta">
                        <div class="row linha">
                            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtDecursoPrazo" name="lblDtDecursoPrazo"
                                        for="txtDtDecursoPrazo">Data do Decurso do Prazo para Defesa:</label>
                                    <div class="input-group mb-3">
                                        <input class="campoData infraText" onchange="verificarMudancaMulta();"
                                            type="text"
                                            id="txtDtDecursoPrazo"
                                            name="txtDtDecursoPrazo"
                                            onkeypress="return infraMascara(this, event, '##/##/####');"
                                            value="<?= $dataDecursoPrazoDefesa ?>"
                                            data-valor-antigo="<?= $dataDecursoPrazoDefesa ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data do Decurso do Prazo para Defesa"
                                            alt="Selecionar Data do Decurso do Prazo para Defesa"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtDecursoPrazo',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>

                                        <a style="margin-left: 5px;; margin-top: 6px;"
                                        id="btAjudaDtDecursoPrazo" <?= PaginaSEI::montarTitleTooltip('A Data do Decurso do Prazo para Defesa é calculada automaticamente a partir da Data da Intimação da Instauração, em quantidade de dias previamente definida na Parametrização de Situações.  \n \n O sistema faz uma sugestão automática que deve ser conferida e ajustada se for o caso.', 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <img id="imgAjudaDtDecursoPrazo" border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImg imgAjudaCtrlProcLit"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data da Decisão de Aplicação de Multa -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtDecisaoAplicacaoMulta"
                                        name="lblDtDecisaoAplicacaoMulta" for="txtDecisaoAplicacaoMulta">Data da Decisão
                                        de
                                        Aplicação da Multa:</label>
                                    <div class="input-group mb-3">
                                        <input onchange="verificarMudancaMulta();return validarFormatoData(this)"
                                            class="campoData infraText"
                                            type="text" id="txtDecisaoAplicacaoMulta" name="txtDecisaoAplicacaoMulta"
                                            onkeypress="return infraMascara(this, event, '##/##/####');"
                                            value="<?= $dataDecisaoAplicacaoMulta ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data da Decisão de Aplicação da Multa"
                                            alt="Selecionar Data da Decisão de Aplicação da Multa"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDecisaoAplicacaoMulta',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                        <a style="margin-left: 5px;; margin-top: 6px;"
                                        id="btAjudaDtDecisaoAplicacaoMulta" <?= PaginaSEI::montarTitleTooltip('Deve informar a data correspondente à primeira Situação Decisória que aplicou Multa. \n \n O sistema faz uma sugestão automática que deve ser conferida e ajustada se for o caso.', 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <img id="imgAjudaDtDecisaoAplicacaoMulta" border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImg imgAjudaCtrlProcLit"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data da Intimação da Decisão de Aplicação de Multa -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDtIntimacaoAplMulta"
                                        name="lblDtIntimacaoAplMulta"
                                        for="txtDtIntimacaoAplMulta">Data da Intimação da Decisão de Aplicação da
                                        Multa:</label>
                                    <div class="input-group mb-3" id="divDtaIntimacaoAplMulta">
                                        <input onchange="armazenarDataIntimacaoMulta(this); verificarMudancaMulta(); calcularDecursoPrazoRecurso(); return validarFormatoData(this);"
                                            class="campoData infraText" type="text"
                                            id="txtDtIntimacaoAplMulta"
                                            name="txtDtIntimacaoAplMulta"
                                            onkeypress="return infraMascara(this, event, '##/##/####');" value=""/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data da Intimação da Decisão de Aplicação da Multa"
                                            alt="Selecionar Data da Intimação da Decisão de Aplicação da Multa"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtIntimacaoAplMulta',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                        <a style="margin-left: 5px;; margin-top: 6px;"
                                        id="btAjudaDtIntimacaoAplMulta" <?= PaginaSEI::montarTitleTooltip('Deve informar a data correspondente à Intimação da Situação Decisória que aplicou a Multa. Esta data não é de informação obrigatória no início, mas passa a ser obrigatória ao marcar a Situação de Intimação correspondente à Decisão, inclusive exigindo Retificação de Lançamento se este campo estiver integrado com o sistema de arrecadação. \n \n O sistema faz uma sugestão automática que deve ser conferida e ajustada se for o caso.', 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <img id="imgAjudaDtIntimacaoAplMulta" border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImg imgAjudaCtrlProcLit"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data do Decurso do Prazo para Recurso -->
                        <div class="row" id="divDtaDecursoPrazoRecurso">
                            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelOpcional" id="lblDtDecursoPrazoRecurso"
                                        name="lblDtDecursoPrazoRecurso"
                                        for="txtDtDecursoPrazoRecurso">Data do Decurso do Prazo para Recurso:</label>
                                    <div class="input-group mb-3">
                                        <input onchange="return validarFormatoData(this);"
                                            class="campoData infraText"
                                            type="text"
                                            id="txtDtDecursoPrazoRecurso" name="txtDtDecursoPrazoRecurso"
                                            onkeypress="return infraMascara(this, event, '##/##/####');" value=""/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data do Decurso do Prazo para Recurso"
                                            alt="Selecionar Data do Decurso do Prazo para Recurso"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtDecursoPrazoRecurso',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                        <a style="margin-left: 5px;; margin-top: 6px;"
                                        id="btAjudaDtDecursoPrazoRecurso" <?= PaginaSEI::montarTitleTooltip('Deve informar a data referente ao Decurso do Prazo para Recurso, a contar da Data da Intimação da Decisão de Aplicação da Multa correspondente. Esta data não é de informação obrigatória no início, mas passa a ser obrigatória ao marcar a Situação de Intimação correspondente à Decisão, inclusive exigindo Retificação de Lançamento se este campo estiver integrado com o sistema de arrecadação.\n \n O sistema faz uma sugestão automática que deve ser conferida e ajustada se for o caso.', 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <img id="imgAjudaDtDecursoPrazoRecurso" border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImg imgAjudaCtrlProcLit"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data de Vencimento -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtVencimento" name="lblDtVencimento"
                                        for="txtDtVencimento">Data de Vencimento:</label>
                                    <div class="input-group mb-3">
                                        <input onchange="verificarMudancaMulta();return validarFormatoData(this);"
                                            class="campoData infraText"
                                            type="text" id="txtDtVencimento" name="txtDtVencimento"
                                            onkeypress="return infraMascara(this, event, '##/##/####');"
                                            value="<?= $dataVencimento ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data de Vencimento" alt="Selecionar Data de Vencimento"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtVencimento',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>

                                        <a style="margin-left: 5px;; margin-top: 6px;"
                                        id="btAjudaDtVencimento" <?= PaginaSEI::montarTitleTooltip('Deve ser informada a Data de Vencimento para o pagamento da Multa. \n \n O sistema faz uma sugestão automática a partir do que foi sugerido para o campo de Data da Decisão de Aplicação da Multa acrescido de 40 dias, mas que deve ser conferida e ajustada se for o caso.', 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <img id="imgAjudaDtVencimento" border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImg imgAjudaCtrlProcLit"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Interessado -->
                        <div class="row">
                            <div class="col-sm-12 col-md-11 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblInteressado" name="lblInteressado"
                                        for="txtDtVencimento">Interessado:</label>
                                    <select class="infraSelect form-control" name="selInteressado"
                                            id="selInteressado" <?= $objMdLitLancamentoDTO ? 'disabled="disabled"' : '' ?> >
                                        <?= $strComboInteressado ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--Numero do interessado -->
                        <div class="row" id="div-numero">
                            <div class="col-sm-12 col-md-11 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lbNumeroInteressado"
                                        name="lblNumeroInteressado"
                                        for="txtDtVencimento">Número de Complemento do Interessado:</label>
                                    <select class="infraSelect form-control" name="selNumeroInteressado"
                                            id="selNumeroInteressado"
                                            data-id-dado-interessado="<?= $objMdLitLancamentoDTO ? $objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado() : '' ?>" <?= $objMdLitLancamentoDTO ? 'disabled="disabled"' : '' ?>>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div id="divHouveConstituicao">
                        <!-- Houve constituição -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divHouveConstituicaoChk">
                                <div class="form-group">
                                    <br>
                                    <input type="checkbox" name="chkHouveConstituicao" id="chkHouveConstituicao" value="S" class="infraCheckbox"
                                        onchange="houveConstituicao(this);verificarMudancaMulta()">
                                    <label class="infraLabelOpcional" id="lblHouveConstituicao" name="lblHouveConstituicao"
                                        for="chkHouveConstituicao">Houve constituição definitiva do crédito?</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                    id="btAjudaHouveConstituicao" <?= PaginaSEI::montarTitleTooltip('A Constituição Definitiva do Crédito ficará disponível somente a partir do cadastro da Situação de Trânsito em Julgado (Conclusiva).', 'Ajuda') ?>
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img id="imgAjudaHouveConstituicao" border="0"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                            class="infraImg imgAjudaCtrlProcLit"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--Data da Constituição -->
                        <div class="row tem-constituicao" id="divtxtDtConstituicao" style="display: none">
                            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtConstituicao" name="lblDtConstituicao"
                                        for="txtDtConstituicao">Data da Constituição Definitiva:</label>
                                    <div class="input-group mb-3">
                                        <input class="campoData infraText type="text" id="txtDtConstituicao"
                                            name="txtDtConstituicao"
                                            onkeypress="return infraMascara(this, event, '##/##/####');"
                                            value="<?= $dtaConstituicaoDefinitiva ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data da Constituição Definitiva"
                                            alt="Selecionar Data da Constituição Definitiva"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtConstituicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                        <a style="margin-left: 5px;; margin-top: 6px;"
                                        id="btAjudaDtConstDef" <?= PaginaSEI::montarTitleTooltip('Deve informar a data correspondente à Constituição Definitiva, que refere-se ao dia seguinte à finalização do prazo de 30 dias concedido para pagamento após a intimação da decisão final ou da sua publicação oficial. \n \n O sistema faz uma sugestão automática que deve ser conferida e ajustada se for o caso.', 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <img id="btAjudaDtConstDef" border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImg imgAjudaCtrlProcLit"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Data da Intimação da Constituição -->
                        <div class="row nao-tem-constituicao">
                            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-8">
                                <div class="form-group">
                                    <label class="infraLabelObrigatorio" id="lblDtIntimacaoConstituicao"
                                        name="lblDtIntimacaoConstituicao" for="txtDtIntimacaoConstituicao">Data da Intimação da
                                        Decisão Definitiva:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="txtDtIntimacaoConstituicao"
                                            name="txtDtIntimacaoConstituicao"
                                            class="campoData infraText"
                                            onchange="verificarMudancaMulta();return validarFormatoData(this);"
                                            onkeypress="return infraMascara(this, event, '##/##/####');"
                                            value="<?= $dtaIntimacaoDefinitiva ?>"
                                            campo-mapea-param-entrada="<?= in_array('dataIntimacaoDecisaoDefinitiva', $arrCampoMapeaParamEntrada) ? 'S' : 'N' ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                            title="Selecionar Data da Intimação da Constituição Definitiva"
                                            alt="Selecionar Data da Intimação da Constituição Definitiva"
                                            class="infraImg"
                                            onclick="infraCalendario('txtDtIntimacaoConstituicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                        <a style="margin-left: 5px;; margin-top: 6px;"
                                        id="btAjudaDtIntConstDef" <?= PaginaSEI::montarTitleTooltip('Corresponde à Data do Trânsito em Julgado indicada na Situação Conclusiva, que é replicada automaticamente para este campo. \n \n Somente em casos excepcionais, como trânsito em julgado em última instância, esta data da intimação poderá ser distinta da Data do Trânsito em Julgado.', 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <img id="imgAjudaDtIntConstDef" border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImg imgAjudaCtrlProcLit"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Redução pela renúncia ao direito de recorrer -->
                        <div class="row nao-tem-constituicao">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <br>
                                    <input type="checkbox" name="chkReducaoRenuncia" id="chkReducaoRenuncia" value="S" class="infraCheckbox"
                                        onchange="verificarMudancaMulta()">
                                    <label class="infraLabelOpcional" id="lblReducaoRenuncia" name="lblReducaoRenuncia"
                                        for="chkReducaoRenuncia">Desconto decorrente da renúncia ao direito de recorrer</label>
                                    <a style="margin-left: 5px;; margin-top: 6px;"
                                    id="btAjudaHouveConstituicao" <?= PaginaSEI::montarTitleTooltip('Esta opção deve ser marcada somente se o Interessado apresentou formalmente e tenha sido aceito pedido de Renúncia ao Direito de Recorrer, obtendo dessa forma a redução no valor da Multa, conforme regulamentação.\n \n Após a Constituição Definitiva ser realizada, apenas o Gestor do Controle Litigioso poderá efetivar correções materiais.', 'Ajuda') ?>
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
<input type="hidden" name="hdnDtDecursoPrazo" id="hdnDtDecursoPrazo"
       value="<?= $dataDecursoPrazoDefesa ?>"/>
<input type="hidden" name="hdnTbVincularLancamento" id="hdnTbVincularLancamento" value=""/>
<input type="hidden" name="hdnStrUltimaSituacao" id="hdnStrUltimaSituacao" value="<?= trim($strUltimaSituacao); ?>"/>
<input type="hidden" name="hdnStrNomeUltimaSituacao" id="hdnStrNomeUltimaSituacao" value="<?= trim($strNomeUltimaSituacao); ?>"/>
<input type="hidden" name="hdnExisteSuspensao" id="hdnExisteSuspensao" value="<?= trim($existeSuspencao); ?>"/>

<!-- Hidden id da Funcionalidade que está sendo manipulada -->
<input type="hidden" name="hdnIdMdLitFuncionalidade" id="hdnIdMdLitFuncionalidade" value=""/>

<!-- Hiddens para Lançamento -->
<input type="hidden" name="hdnJustificativaLancamento" id="hdnJustificativaLancamento" value=""/>

<!-- Hiddens para Cancelamento -->
<input type="hidden" name="hdnIdMotivoCancelamento" id="hdnIdMotivoCancelamento" value=""/>
<input type="hidden" name="hdnJustificativaCancelamento" id="hdnJustificativaCancelamento" value=""/>
<input type="hidden" name="hdnTxtMotivoCancelamento" id="hdnTxtMotivoCancelamento" value=""/>

<!-- Hidden suspenso para as situações recursal -->
<input type="hidden" name="hdnSinSuspenso" id="hdnSinSuspenso"
       value="<?= $objMdLitLancamentoDTO ? $objMdLitLancamentoDTO->getStrSinSuspenso() : '' ?>"/>

<input type="hidden" name="hdnNumInteressado" value="<?= $numInteressado ?>">

<!-- Hidden para verificar o valor original das multas -->
<input type="hidden" name="hdnVlOriginalMultas" id="hdnVlOriginalMultas" value=""/>

<!-- Hidden para permitir suspender em razão de recuro-->
<input type="hidden" name="hdnSuspRazRecurso" id="hdnSuspRazRecurso" value=""/>
<input type="hidden" name="btnCancelarLancamentoAtivo" id="btnCancelarLancamentoAtivo" value=""/>

<!-- Hidden para retificar lan?amento-->
<input type="hidden" name="hdnCreditosProcesso" id="hdnCreditosProcesso" value=""/>
