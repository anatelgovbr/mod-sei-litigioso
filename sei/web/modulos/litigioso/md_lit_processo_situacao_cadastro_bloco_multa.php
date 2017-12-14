<div class="clear-margin-3"></div>

<div class="grid grid_13">
    <fieldset class="infraFieldset" id="fieldsetMulta" style="<?php echo $strGridDecisao == '' && !$objMdLitLancamentoDTO ?'display:none': ''?>">
        <legend class="infraLegend">Gestão de Multa</legend>
        <div class="clear-margin-1"></div>

        <div class="grid grid_10 botoesGestaoMulta">

            <!-- Incluir Lançamento -->
            <button id="btnIncluirLancamento" type="button" name="btnIncluirLancamento" value="Incluir Lançamento"
                    style="display: none;" class="infraButton btnLancamento" onclick="abrirModalJustificativaLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO); ?>">Incluir Lançamento
            </button>

            <!-- Cancelar Lançamento -->
            <button id="btnCancelarLancamento" name="btnCancelarLancamento" value="Cancelar Lançamento"
                    style="display: none;" onclick="abrirModalCancelarLancamento()" type="button"   class="infraButton btnLancamento">Cancelar Lançamento
            </button>

            <!-- Retificar Lançamento -->
            <button id="btnRetificarLancamento" name="btnRetificarLancamento" type="button"
                    onclick="abrirModalJustificativaLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO); ?>"
                    value="Retificar Lançamento"
                    style="display: none;" class="infraButton btnLancamento">Retificar Lançamento
            </button>

            <!-- Suspender Lançamento -->
            <button id="btnSuspenderLancamento" name="btnSuspenderLancamento" value="Suspender Lançamento" type="button"
                    onclick="abrirModalJustificativaLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO); ?>"
                    style="display: none;" class="infraButton btnLancamento">Suspender em Razão de Recurso
            </button>

            <!-- Denegar Recurso -->
            <button id="btnDenegarRecurso" name="btnDenegarRecurso" value="Denegar Recurso" type="button"
                    onclick="abrirModalJustificativaLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_DENEGAR_RECURSO); ?>"
                    style="display: none;" class="infraButton btnLancamento">Denegar Recurso
            </button>

            <!-- Cancelar Recurso -->
            <button id="btnCancelarRecurso" name="btnCancelarRecurso" value="Cancelar Recurso" type="button"
                    onclick="abrirModalJustificativaLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_RECURSO); ?>"
                    style="display: none;" class="infraButton btnLancamento">Cancelar Recurso
            </button>

            <!-- Histórico de Lançamento -->
            <button id="btnHistoricoLancamento" name="btnHistoricoLancamento" value="Histórico de Lançamentos"
                    onclick="abrirModalHistoricoLancamento()" type="button" class="infraButton btnLancamento">Histórico de Lançamentos
            </button>
        </div>

<!--  Créditos do Processo -->
<div class="grid grid_5" style="display: none;" id="divCreditoProcesso">
    <label id="lblCreditoProcesso" name="lblCreditoProcesso"> Créditos do Processo: </label>
    <select id="selCreditosProcesso" name="selCreditosProcesso" onchange="mudarCreditosProcesso(this)">
        <?= $strComboCreditoProcesso ?>
    </select>
</div>

<div class="clear-margin-3"></div>

<div class="grid grid_12">
    <!--Total da Multa Aplicada -->
    <div class="grid grid_4">
        <label class="infraLabelOpcional" id="lblTotalMulta" name="lblTotalMulta">
            Total de Multa Aplicada:
        </label>
    </div>
    <div class="grid grid_3">
        <label id="lblVlTotalMulta" name="lblVlTotalMulta" class="infraLabelOpcional">
        R$ 0,00
        </label>
    </div>

    <div class="clear-margin-1"></div>

    <!--Crédito não Lançado: -->
    <div class="grid grid_4">
        <label id="lblCreditoNaoLancado" name="lblCreditoNaoLancado" class="infraLabelOpcional">
            Crédito não Lançado:
        </label>
    </div>
    <div class="grid grid_3">
        <label id="lblVlCreditoNaoLancado" name="lblVlCreditoNaoLancado" class="infraLabelOpcional">
        R$ 0,00
         </label>
    </div>

    <div class="clear-margin-1"></div>

    <!--Crédito Lancado: -->
    <div class="grid grid_4">
        <label id="lblCreditoLancado" name="lblCreditoLancado" class="infraLabelOpcional">
            Crédito Lançado:
        </label>
    </div>

    <div class="grid grid_3">
        <label id="lblVlCreditoLancado" name="lblVlCreditoLancado" class="infraLabelOpcional">
            R$ 0,00
        </label>
    </div>

    <div class="clear-margin-1"></div>

    <!-- Desconto -->
    <div class="grid grid_4">
        <label id="lblDesconto" name="lblDesconto" class="infraLabelOpcional">
            Desconto:
        </label>
    </div>

    <div class="grid grid_3">
        <label id="lblVlDesconto" name="lblVlDesconto" class="infraLabelOpcional">
            R$ 0,00
        </label>
    </div>

    <div class="clear-margin-1"></div>

    <!-- Arrecadado -->
    <div class="grid grid_4">
        <label id="lblArrecadado" name="lblArrecadado" class="infraLabelOpcional">
            Arrecadado:
        </label>
    </div>

    <div class="grid grid_3">
        <label id="lblVlArrecadado" name="lblVlArrecadado" class="infraLabelOpcional">
            R$ 0,00
        </label>
    </div>

    <div class="clear-margin-1"></div>

    <!-- Data do último pagamento -->
    <div class="grid grid_4">
        <label id="lblDtUltimoPag" name="lblDtUltimoPag" class="infraLabelOpcional">
            Data do último Pagamento:
        </label>
    </div>

    <div class="grid grid_3">
        <label id="lblVlDtUltimoPag" name="lblVlDtUltimoPag" class="infraLabelOpcional">

        </label>
    </div>


    <div class="clear-margin-1"></div>

    <!-- Salvo devedor atualizado  -->
    <div class="grid grid_4">
        <label id="lblSaldoDevAtualizado" name="lblSaldoDevAtualizado" class="infraLabelOpcional">
         Salvo Devedor Atualizado:
        </label>
    </div>

    <div class="grid grid_3">
        <label id="lblVlSaldoDevAtualizado" name="lblVlSaldoDevAtualizado" class="infraLabelOpcional">
            R$ 0,00
        </label>
    </div>

    <div class="clear-margin-1"></div>

    <!-- Crédito Constituído Definitivamente  -->
    <div class="grid grid_4">
        <label id="lblCredConstDef" name="lblCredConstDef" class="infraLabelOpcional">
            Crédito Constituído Definitivamente:
        </label>
    </div>

    <div class="grid grid_3">
        <label id="lblVlCredConstDef" name="lblVlCredConstDef" class="infraLabelOpcional">
            R$ 0,00
        </label>
    </div>
</div>

 <div class="clear-margin-5"></div>

        <!--Data do Decurso do Prazo para Defesa -->
        <div class="grid grid_8">
            <div class="grid grid_6">
                <label class="infraLabelObrigatorio" id="lblDtDecursoPrazo" name="lblDtDecursoPrazo" for="txtDtDecursoPrazo">Data do
                    Decurso do Prazo para Defesa:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDtDecursoPrazo" name="txtDtDecursoPrazo" disabled="disabled"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value="<?= $dataDecursoPrazoDefesa ?>"/>
            </div>

        </div>

<!-- Houve constituição -->
            <div class="grid grid_5">
                    <input type="checkbox" name="chkHouveConstituicao" id="chkHouveConstituicao" value="S" onchange="houveConstituicao(this)">
                    <label class="infraLabelOpcional" id="lblHouveConstituicao" name="lblHouveConstituicao" for="chkHouveConstituicao">Houve constituição definitiva do crédito?</label>
            </div>

        <div class="clear-margin-1"></div>

        <!--Data da Decisão de Aplicação de Multa -->
        <div class="grid grid_8">
            <div class="grid grid_6">
                <label class="infraLabelObrigatorio" id="lblDtDecisaoAplicacaoMulta" name="lblDtDecisaoAplicacaoMulta" for="txtDecisaoAplicacaoMulta">Data da Decisão de Aplicação da Multa:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDecisaoAplicacaoMulta" name="txtDecisaoAplicacaoMulta"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value="<?= $dataDecisaoAplicacaoMulta ?>"/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" 
                     title="Selecionar Data da Decisão de Aplicação da Multa" alt="Selecionar Data da Decisão de Aplicação da Multa"
                     class="infraImg"
                     onclick="infraCalendario('txtDecisaoAplicacaoMulta',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>

        </div>

        <!--Data da Constituição -->
        <div class="grid grid_4 nao-tem-constituicao">
            <div class="grid grid_3">
                <label class="infraLabelObrigatorio" id="lblDtConstituicao" name="lblDtConstituicao" for="txtDtConstituicao">Data da Constituição:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDtConstituicao" name="txtDtConstituicao"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value="<?=$dtaConstituicaoDefinitiva?>"/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" 
                     title="Selecionar Data da Constituição" alt="Selecionar Data da Constituição"
                     class="infraImg"
                     onclick="infraCalendario('txtDtConstituicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>

        </div>

        <!--Data da Intimação da Decisão de Aplicação de Multa -->
        <div class="grid grid_8">
            <div class="grid grid_6">
                <label class="infraLabelOpcional" id="lblDtIntimacaoAplMulta" name="lblDtIntimacaoAplMulta" for="txtDtIntimacaoAplMulta">Data da Intimação da Decisão de Aplicação da Multa:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDtIntimacaoAplMulta" name="txtDtIntimacaoAplMulta"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value=""/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" 
                     title="Selecionar Data da Intimação da Decisão de Aplicação da Multa" alt="Selecionar Data da Intimação da Decisão de Aplicação da Multa"
                     class="infraImg"
                     onclick="infraCalendario('txtDtIntimacaoAplMulta',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>

        </div>

        <!--Data da Intimação da Constituição -->
        <div class="grid grid_4  nao-tem-constituicao">
            <div class="grid grid_4">
                <label class="infraLabelObrigatorio" id="lblDtIntimacaoConstituicao" name="lblDtIntimacaoConstituicao" for="txtDtIntimacaoConstituicao">Data da Intimação da Constituição:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDtIntimacaoConstituicao" name="txtDtIntimacaoConstituicao"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value="<?=$dtaIntimacaoDefinitiva?>"/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" 
                     title="Selecionar Data da Intimação da Constituição" alt="Selecionar Data da Intimação da Constituição"
                     class="infraImg"
                     onclick="infraCalendario('txtDtIntimacaoConstituicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>

        </div>

        <!--Data de Vencimento -->
        <div class="grid grid_8">
            <div class="grid grid_6">
                <label class="infraLabelObrigatorio" id="lblDtVencimento" name="lblDtVencimento" for="txtDtVencimento">Data de Vencimento:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDtVencimento" name="txtDtVencimento"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value="<?= $dataVencimento ?>"/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                     title="Selecionar Data de Vencimento" alt="Selecionar Data de Vencimento"
                     class="infraImg"
                     onclick="infraCalendario('txtDtVencimento',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>

        </div>

        <!-- Redução pela renúncia ao direito de recorrer -->
        <div class="grid grid_5  nao-tem-constituicao">
            <input type="checkbox" name="chkReducaoRenuncia" id="chkReducaoRenuncia" value="S" onclick="mostraBotaoContituirDefinitivamente(this)" >
            <label class="infraLabelOpcional" id="lblReducaoRenuncia" name="lblReducaoRenuncia" for="chkReducaoRenuncia">Redução pela renúncia ao direito de recorer</label>

            <button id="btnConstituirDefinitivamente" type="button" name="btnConstituirDefinitivamente" value="Constituir Definitivamente"
                    onclick="abrirModalConstituirDefinitivamente(this)" style="display: none;"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO); ?>"
                    class="infraButton">Constituir Definitivamente
            </button>
        </div>

        <!--Interessado -->
        <div class="grid grid_8">
            <div class="grid grid_6">
                <label class="infraLabelObrigatorio" id="lblInteressado" name="lblInteressado" for="txtDtVencimento">Interessado:</label>
            </div>

            <div class="grid grid_6">
                <select class="infraSelect" name="selInteressado" id="selInteressado" <?= $objMdLitLancamentoDTO? 'disabled="disabled"':''  ?> >
                    <?= $strComboInteressado ?>
                </select>
            </div>

        </div>

        <!--Fistel -->
        <div class="grid grid_8" id="div-fistel" style="display: none">
            <div class="grid grid_6">
                <label class="infraLabelObrigatorio" id="lbFistel" name="lblFistel" for="txtDtVencimento">Fistel:</label>
            </div>

            <div class="grid grid_6">
                <select class="infraSelect" name="selFistel" id="selFistel" data-numFistel="<?= $numFistel ?>"  <?= $objMdLitLancamentoDTO? 'disabled="disabled"':''  ?>>
                </select>

            </div>

        </div>

        <!-- Hiddens -->
        <input type="hidden" name="hdnVlTotalMulta" id="hdnVlTotalMulta" value=""  />
        <input type="hidden" name="hdnVlCreditoNaoLancado" id="hdnVlCreditoNaoLancado" value=""  />
        <input type="hidden" name="hdnVlCreditoLancado" id="hdnVlCreditoLancado" value=""  />
        <input type="hidden" name="hdnVlDesconto" id="hdnVlDesconto" value=""  />
        <input type="hidden" name="hdnVlArrecadado" id="hdnVlArrecadado" value=""  />
        <input type="hidden" name="hdnVlDtUltimoPagamento" id="hdnVlDtUltimoPagamento" value=""  />
        <input type="hidden" name="hdnVlSaldoDevAtualizado" id="hdnVlSaldoDevAtualizado"  value="" />
        <input type="hidden" name="hdnVlCredConstituidoDef" id="hdnVlCredConstituidoDef" value=""  />

        <!-- Hidden id da Funcionalidade que está sendo manipulada -->
        <input type="hidden" name="hdnIdMdLitFuncionalidade" id="hdnIdMdLitFuncionalidade" value="" />

        <!-- Hiddens para Lançamento -->
        <input type="hidden" name="hdnJustificativaLancamento" id="hdnJustificativaLancamento" value=""  />

        <!-- Hiddens para Cancelamento -->
        <input type="hidden" name="hdnIdMotivoCancelamento" id="hdnIdMotivoCancelamento" value=""  />
        <input type="hidden" name="hdnJustificativaCancelamento" id="hdnJustificativaCancelamento" value=""  />
        <input type="hidden" name="hdnTxtMotivoCancelamento" id="hdnTxtMotivoCancelamento" value=""  />

        <!-- Hidden suspenso para as situações recursal -->
        <input type="hidden" name="hdnSinSuspenso" id="hdnSinSuspenso" value="<?= $objMdLitLancamentoDTO? $objMdLitLancamentoDTO->getStrSinSuspenso():'' ?>" />

        <input type="hidden" name="hdnNumFistel" value="<?= $numFistel ?>">

</fieldset>
</div>