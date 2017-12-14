<div class="clear-margin-3"></div>

<div class="grid grid_13">
    <fieldset class="infraFieldset" id="fieldsetMulta" style="<?php echo $strGridDecisao == '' && !$objMdLitLancamentoDTO ?'display:none': ''?>">
        <legend class="infraLegend">Gest�o de Multa</legend>
        <div class="clear-margin-1"></div>

        <div class="grid grid_10 botoesGestaoMulta">

            <!-- Incluir Lan�amento -->
            <button id="btnIncluirLancamento" type="button" name="btnIncluirLancamento" value="Incluir Lan�amento"
                    style="display: none;" class="infraButton btnLancamento" onclick="abrirModalJustificativaLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO); ?>">Incluir Lan�amento
            </button>

            <!-- Cancelar Lan�amento -->
            <button id="btnCancelarLancamento" name="btnCancelarLancamento" value="Cancelar Lan�amento"
                    style="display: none;" onclick="abrirModalCancelarLancamento()" type="button"   class="infraButton btnLancamento">Cancelar Lan�amento
            </button>

            <!-- Retificar Lan�amento -->
            <button id="btnRetificarLancamento" name="btnRetificarLancamento" type="button"
                    onclick="abrirModalJustificativaLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO); ?>"
                    value="Retificar Lan�amento"
                    style="display: none;" class="infraButton btnLancamento">Retificar Lan�amento
            </button>

            <!-- Suspender Lan�amento -->
            <button id="btnSuspenderLancamento" name="btnSuspenderLancamento" value="Suspender Lan�amento" type="button"
                    onclick="abrirModalJustificativaLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO); ?>"
                    style="display: none;" class="infraButton btnLancamento">Suspender em Raz�o de Recurso
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

            <!-- Hist�rico de Lan�amento -->
            <button id="btnHistoricoLancamento" name="btnHistoricoLancamento" value="Hist�rico de Lan�amentos"
                    onclick="abrirModalHistoricoLancamento()" type="button" class="infraButton btnLancamento">Hist�rico de Lan�amentos
            </button>
        </div>

<!--  Cr�ditos do Processo -->
<div class="grid grid_5" style="display: none;" id="divCreditoProcesso">
    <label id="lblCreditoProcesso" name="lblCreditoProcesso"> Cr�ditos do Processo: </label>
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

    <!--Cr�dito n�o Lan�ado: -->
    <div class="grid grid_4">
        <label id="lblCreditoNaoLancado" name="lblCreditoNaoLancado" class="infraLabelOpcional">
            Cr�dito n�o Lan�ado:
        </label>
    </div>
    <div class="grid grid_3">
        <label id="lblVlCreditoNaoLancado" name="lblVlCreditoNaoLancado" class="infraLabelOpcional">
        R$ 0,00
         </label>
    </div>

    <div class="clear-margin-1"></div>

    <!--Cr�dito Lancado: -->
    <div class="grid grid_4">
        <label id="lblCreditoLancado" name="lblCreditoLancado" class="infraLabelOpcional">
            Cr�dito Lan�ado:
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

    <!-- Data do �ltimo pagamento -->
    <div class="grid grid_4">
        <label id="lblDtUltimoPag" name="lblDtUltimoPag" class="infraLabelOpcional">
            Data do �ltimo Pagamento:
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

    <!-- Cr�dito Constitu�do Definitivamente  -->
    <div class="grid grid_4">
        <label id="lblCredConstDef" name="lblCredConstDef" class="infraLabelOpcional">
            Cr�dito Constitu�do Definitivamente:
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

<!-- Houve constitui��o -->
            <div class="grid grid_5">
                    <input type="checkbox" name="chkHouveConstituicao" id="chkHouveConstituicao" value="S" onchange="houveConstituicao(this)">
                    <label class="infraLabelOpcional" id="lblHouveConstituicao" name="lblHouveConstituicao" for="chkHouveConstituicao">Houve constitui��o definitiva do cr�dito?</label>
            </div>

        <div class="clear-margin-1"></div>

        <!--Data da Decis�o de Aplica��o de Multa -->
        <div class="grid grid_8">
            <div class="grid grid_6">
                <label class="infraLabelObrigatorio" id="lblDtDecisaoAplicacaoMulta" name="lblDtDecisaoAplicacaoMulta" for="txtDecisaoAplicacaoMulta">Data da Decis�o de Aplica��o da Multa:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDecisaoAplicacaoMulta" name="txtDecisaoAplicacaoMulta"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value="<?= $dataDecisaoAplicacaoMulta ?>"/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" 
                     title="Selecionar Data da Decis�o de Aplica��o da Multa" alt="Selecionar Data da Decis�o de Aplica��o da Multa"
                     class="infraImg"
                     onclick="infraCalendario('txtDecisaoAplicacaoMulta',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>

        </div>

        <!--Data da Constitui��o -->
        <div class="grid grid_4 nao-tem-constituicao">
            <div class="grid grid_3">
                <label class="infraLabelObrigatorio" id="lblDtConstituicao" name="lblDtConstituicao" for="txtDtConstituicao">Data da Constitui��o:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDtConstituicao" name="txtDtConstituicao"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value="<?=$dtaConstituicaoDefinitiva?>"/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" 
                     title="Selecionar Data da Constitui��o" alt="Selecionar Data da Constitui��o"
                     class="infraImg"
                     onclick="infraCalendario('txtDtConstituicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>

        </div>

        <!--Data da Intima��o da Decis�o de Aplica��o de Multa -->
        <div class="grid grid_8">
            <div class="grid grid_6">
                <label class="infraLabelOpcional" id="lblDtIntimacaoAplMulta" name="lblDtIntimacaoAplMulta" for="txtDtIntimacaoAplMulta">Data da Intima��o da Decis�o de Aplica��o da Multa:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDtIntimacaoAplMulta" name="txtDtIntimacaoAplMulta"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value=""/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" 
                     title="Selecionar Data da Intima��o da Decis�o de Aplica��o da Multa" alt="Selecionar Data da Intima��o da Decis�o de Aplica��o da Multa"
                     class="infraImg"
                     onclick="infraCalendario('txtDtIntimacaoAplMulta',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>

        </div>

        <!--Data da Intima��o da Constitui��o -->
        <div class="grid grid_4  nao-tem-constituicao">
            <div class="grid grid_4">
                <label class="infraLabelObrigatorio" id="lblDtIntimacaoConstituicao" name="lblDtIntimacaoConstituicao" for="txtDtIntimacaoConstituicao">Data da Intima��o da Constitui��o:</label>
            </div>

            <div class="grid grid_3">
                <input class="campoData" type="text" id="txtDtIntimacaoConstituicao" name="txtDtIntimacaoConstituicao"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value="<?=$dtaIntimacaoDefinitiva?>"/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" 
                     title="Selecionar Data da Intima��o da Constitui��o" alt="Selecionar Data da Intima��o da Constitui��o"
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

        <!-- Redu��o pela ren�ncia ao direito de recorrer -->
        <div class="grid grid_5  nao-tem-constituicao">
            <input type="checkbox" name="chkReducaoRenuncia" id="chkReducaoRenuncia" value="S" onclick="mostraBotaoContituirDefinitivamente(this)" >
            <label class="infraLabelOpcional" id="lblReducaoRenuncia" name="lblReducaoRenuncia" for="chkReducaoRenuncia">Redu��o pela ren�ncia ao direito de recorer</label>

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

        <!-- Hidden id da Funcionalidade que est� sendo manipulada -->
        <input type="hidden" name="hdnIdMdLitFuncionalidade" id="hdnIdMdLitFuncionalidade" value="" />

        <!-- Hiddens para Lan�amento -->
        <input type="hidden" name="hdnJustificativaLancamento" id="hdnJustificativaLancamento" value=""  />

        <!-- Hiddens para Cancelamento -->
        <input type="hidden" name="hdnIdMotivoCancelamento" id="hdnIdMotivoCancelamento" value=""  />
        <input type="hidden" name="hdnJustificativaCancelamento" id="hdnJustificativaCancelamento" value=""  />
        <input type="hidden" name="hdnTxtMotivoCancelamento" id="hdnTxtMotivoCancelamento" value=""  />

        <!-- Hidden suspenso para as situa��es recursal -->
        <input type="hidden" name="hdnSinSuspenso" id="hdnSinSuspenso" value="<?= $objMdLitLancamentoDTO? $objMdLitLancamentoDTO->getStrSinSuspenso():'' ?>" />

        <input type="hidden" name="hdnNumFistel" value="<?= $numFistel ?>">

</fieldset>
</div>