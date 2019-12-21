<div class="clear-margin-3"></div>

<div class="grid grid_13">
    <fieldset class="infraFieldset" id="fieldsetMulta" style="<?php echo (!$blnDecisaoMultaIntegracao || $strGridDecisao == '' && !$objMdLitLancamentoDTO) ?'display:none': ''?>">
        <legend class="infraLegend">Gest�o de Multa</legend>
        <div class="clear-margin-1"></div>

        <div class="grid grid_10 botoesGestaoMulta">

            <?php if($isVincularLancamento){?>
            <!-- Vincular Lan�amento -->
            <button id="btnVincularLancamento" type="button" name="btnVincularLancamento" value="Vincular Lan�amento"
                    style="display: none;" class="infraButton btnLancamento" onclick="abrirModalVincularLancamento(this)"
                    data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_vincular_lancamento&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO); ?>">Vincular Lan�amento
            </button>
            <?php } ?>

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
         Saldo Devedor Atualizado:
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

        <div style="float: left;width: 450px" id="divDataGestaoMulta">
            <!--Data do Decurso do Prazo para Defesa -->
            <div class="grid grid_8">
                <div class="grid grid_6">
                    <label class="infraLabelObrigatorio" id="lblDtDecursoPrazo" name="lblDtDecursoPrazo" for="txtDtDecursoPrazo">Data do
                        Decurso do Prazo para Defesa:</label>
                </div>

                <div class="grid grid_3">
                    <input class="campoData" onchange="verificarMudancaMulta();" type="text" id="txtDtDecursoPrazo" name="txtDtDecursoPrazo" disabled="disabled"
                           onkeypress="return infraMascara(this, event, '##/##/####');" value="<?= $dataDecursoPrazoDefesa ?>" data-valor-antigo="<?= $dataDecursoPrazoDefesa ?>"/>
                    <a style="margin-left: 5px;" id="btAjudaDtDecursoPrazo" <?=PaginaSEI::montarTitleTooltip('A Data do Decurso do Prazo para Defesa � calculada automaticamente a partir da Data da Intima��o da Instaura��o, em quantidade de dias previamente definida na Parametriza��o de Situa��es, n�o podendo ser alterada.')?>
                       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                        <img id="imgAjudaDtDecursoPrazo" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg imgAjudaCtrlProcLit"/>
                    </a>
                </div>

            </div>

            <div class="clear-margin-1"></div>

            <!--Data da Decis�o de Aplica��o de Multa -->
            <div class="grid grid_8">
                <div class="grid grid_6">
                    <label class="infraLabelObrigatorio" id="lblDtDecisaoAplicacaoMulta" name="lblDtDecisaoAplicacaoMulta" for="txtDecisaoAplicacaoMulta">Data da Decis�o de Aplica��o da Multa:</label>
                </div>

                <div class="grid grid_3">
                    <input onchange="verificarMudancaMulta();return validarFormatoData(this)" class="campoData" type="text" id="txtDecisaoAplicacaoMulta" name="txtDecisaoAplicacaoMulta"
                           onkeypress="return infraMascara(this, event, '##/##/####');" value="<?= $dataDecisaoAplicacaoMulta ?>"/>
                    <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                         title="Selecionar Data da Decis�o de Aplica��o da Multa" alt="Selecionar Data da Decis�o de Aplica��o da Multa"
                         class="infraImg"
                         onclick="infraCalendario('txtDecisaoAplicacaoMulta',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                    <a style="margin-left: 5px;" id="btAjudaDtDecisaoAplicacaoMulta" <?=PaginaSEI::montarTitleTooltip('Deve informar a data correspondente � primeira Situa��o Decis�ria que aplicou Multa. \n \n O sistema faz uma sugest�o autom�tica que deve ser conferida e ajustada se for o caso.')?>
                       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                        <img id="imgAjudaDtDecisaoAplicacaoMulta" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg imgAjudaCtrlProcLit"/>
                    </a>
                </div>

            </div>


            <!--Data da Intima��o da Decis�o de Aplica��o de Multa -->
            <div class="grid grid_8">
                <div class="grid grid_6">
                    <label class="infraLabelOpcional" id="lblDtIntimacaoAplMulta" name="lblDtIntimacaoAplMulta" for="txtDtIntimacaoAplMulta">Data da Intima��o da Decis�o de Aplica��o da Multa:</label>
                </div>

                <div class="grid grid_3" id="divDtaIntimacaoAplMulta">
                    <input onchange="armazenarDataIntimacaoMulta(this); verificarMudancaMulta();return validarFormatoData(this);" class="campoData" type="text" id="txtDtIntimacaoAplMulta" name="txtDtIntimacaoAplMulta"
                           onkeypress="return infraMascara(this, event, '##/##/####');" value=""/>
                    <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                         title="Selecionar Data da Intima��o da Decis�o de Aplica��o da Multa" alt="Selecionar Data da Intima��o da Decis�o de Aplica��o da Multa"
                         class="infraImg"
                         onclick="infraCalendario('txtDtIntimacaoAplMulta',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                    <a style="margin-left: 5px;" id="btAjudaDtIntimacaoAplMulta" <?=PaginaSEI::montarTitleTooltip('Deve informar a data correspondente � Intima��o da primeira Situa��o Decis�ria que aplicou Multa. Esta data n�o � de informa��o obrigat�ria no in�cio, mas deve ser informada assim que confirmada a Intima��o, para realiza��o da Retifica��o pertinente junto ao sistema de arrecada��o.\n \n O sistema faz uma sugest�o autom�tica que deve ser conferida e ajustada se for o caso.')?>
                       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                        <img id="imgAjudaDtIntimacaoAplMulta" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg imgAjudaCtrlProcLit"/>
                    </a>
                </div>

            </div>
            <!--Data de Vencimento -->
            <div class="grid grid_8">
                <div class="grid grid_6">
                    <label class="infraLabelObrigatorio" id="lblDtVencimento" name="lblDtVencimento" for="txtDtVencimento">Data de Vencimento:</label>
                </div>

                <div class="grid grid_3">
                    <input onchange="verificarMudancaMulta();return validarFormatoData(this);" class="campoData" type="text" id="txtDtVencimento" name="txtDtVencimento"
                           onkeypress="return infraMascara(this, event, '##/##/####');" value="<?= $dataVencimento ?>"/>
                    <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                         title="Selecionar Data de Vencimento" alt="Selecionar Data de Vencimento"
                         class="infraImg"
                         onclick="infraCalendario('txtDtVencimento',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>

                    <a style="margin-left: 5px;" id="btAjudaDtVencimento" <?=PaginaSEI::montarTitleTooltip('Deve ser informada a Data de Vencimento para o pagamento da Multa. \n \n O sistema faz uma sugest�o autom�tica a partir do que foi sugerido para o campo de Data da Decis�o de Aplica��o da Multa acrescido de 40 dias, mas que deve ser conferida e ajustada se for o caso.')?>
                       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                        <img id="imgAjudaDtVencimento" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg imgAjudaCtrlProcLit"/>
                    </a>
                </div>

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

            <!--Numero do interessado -->
            <div class="grid grid_8" id="div-numero" style="display: none">
                <div class="grid grid_6">
                    <label class="infraLabelObrigatorio" id="lbNumeroInteressado" name="lblNumeroInteressado" for="txtDtVencimento">N�mero de Complemento do Interessado:</label>
                </div>

                <div class="grid grid_6">
                    <select class="infraSelect" name="selNumeroInteressado" id="selNumeroInteressado" data-id-dado-interessado="<?= $objMdLitLancamentoDTO? $objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado():'' ?>"  <?= $objMdLitLancamentoDTO? 'disabled="disabled"':''  ?>>
                    </select>

                </div>

            </div>
        </div>
        <div style="float:left;width: 350px" id="divHouveConstituicao">
            <!-- Houve constitui��o -->
            <div class="grid grid_5" id="divHouveConstituicaoChk">
                <input type="checkbox" name="chkHouveConstituicao" id="chkHouveConstituicao" value="S" onchange="houveConstituicao(this);verificarMudancaMulta()">
                <label class="infraLabelOpcional" id="lblHouveConstituicao" name="lblHouveConstituicao" for="chkHouveConstituicao">Houve constitui��o definitiva do cr�dito?</label>
                <a style="margin-left: 5px;" id="btAjudaHouveConstituicao" <?=PaginaSEI::montarTitleTooltip('A Constitui��o Definitiva do Cr�dito ficar� dispon�vel somente a partir do cadastro da Situa��o de Tr�nsito em Julgado (Conclusiva).')?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img id="imgAjudaHouveConstituicao" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg imgAjudaCtrlProcLit"/>
                </a>
            </div>

            <!--Data da Constitui��o -->
            <div class="grid grid_4 nao-tem-constituicao">
                <div class="grid grid_4">
                    <label class="infraLabelObrigatorio" id="lblDtConstituicao" name="lblDtConstituicao" for="txtDtConstituicao">Data da Constitui��o Definitiva:</label>
                </div>

                <div class="grid grid_3">
                    <input  disabled="disabled" class="campoData" type="text" id="txtDtConstituicao" name="txtDtConstituicao"
                           onkeypress="return infraMascara(this, event, '##/##/####');" value="<?=$dtaConstituicaoDefinitiva?>"/>

                    <a style="margin-left: 5px;" id="btAjudaDtConstDef" <?=PaginaSEI::montarTitleTooltip('Corresponde � Data do Tr�nsito em Julgado indicada na Situa��o Conclusiva, que � replicada automaticamente para este campo.')?>
                       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                        <img id="btAjudaDtConstDef" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg imgAjudaCtrlProcLit"/>
                    </a>
                </div>

            </div>


            <!--Data da Intima��o da Constitui��o -->
            <div class="grid grid_4  nao-tem-constituicao">
                <div class="grid grid_5">
                    <label class="infraLabelObrigatorio" id="lblDtIntimacaoConstituicao" name="lblDtIntimacaoConstituicao" for="txtDtIntimacaoConstituicao">Data da Intima��o da Constitui��o Definitiva:</label>
                </div>

                <div class="grid grid_4">
                    <input style="width: 52%" type="text" id="txtDtIntimacaoConstituicao" name="txtDtIntimacaoConstituicao" onchange="return validarFormatoData(this)"
                           onkeypress="return infraMascara(this, event, '##/##/####');" value="<?=$dtaIntimacaoDefinitiva?>"/>
                    <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                         title="Selecionar Data da Intima��o da Constitui��o Definitiva" alt="Selecionar Data da Intima��o da Constitui��o Definitiva"
                         class="infraImg"
                         onclick="infraCalendario('txtDtIntimacaoConstituicao',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                    <a style="margin-left: 5px;" id="btAjudaDtIntConstDef" <?=PaginaSEI::montarTitleTooltip('Corresponde � Data do Tr�nsito em Julgado indicada na Situa��o Conclusiva, que � replicada automaticamente para este campo. \n \n Somente em casos excepcionais, como tr�nsito em julgado em �ltima inst�ncia, esta data da intima��o poder� ser distinta da Data do Tr�nsito em Julgado.')?>
                       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                        <img id="imgAjudaDtIntConstDef" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg imgAjudaCtrlProcLit"/>
                    </a>

                </div>

            </div>

            <!-- Redu��o pela ren�ncia ao direito de recorrer -->
            <div class="grid grid_5-5  nao-tem-constituicao" style="width: 348px;">
                <input type="checkbox" name="chkReducaoRenuncia" id="chkReducaoRenuncia" value="S" >
                <label class="infraLabelOpcional" id="lblReducaoRenuncia" name="lblReducaoRenuncia" for="chkReducaoRenuncia">Desconto decorrente da ren�ncia ao direito de recorrer</label>
                <a style="margin-left: 5px;" id="btAjudaHouveConstituicao" <?=PaginaSEI::montarTitleTooltip('Esta op��o deve ser marcada somente se o Interessado apresentou formalmente e tenha sido aceito pedido de Ren�ncia ao Direito de Recorrer, obtendo dessa forma a redu��o no valor da Multa, conforme regulamenta��o.\n \n Ap�s a Constitui��o Definitiva ser realizada, apenas o Gestor do Controle Litigioso poder� efetivar corre��es materiais.')?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img id="imgAjudaHouveConstituicao" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg imgAjudaCtrlProcLit"/>
                </a>

                <button id="btnConstituirDefinitivamente" type="button" name="btnConstituirDefinitivamente" value="Constituir Definitivamente"
                        onclick="abrirModalConstituirDefinitivamente(this)" style="display: none;"
                        data-url="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_multa_justificativa&id_procedimento='.$idProcedimento.'&id_md_lit_funcionalidade='.MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO.'&constituir_definitivamente=1'); ?>"
                        class="infraButton">Constituir Definitivamente
                </button>
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
        <input type="hidden" name="hdnDtDecursoPrazo" id="hdnDtDecursoPrazo" value="<?=$dataDecursoPrazoDefesa?>" />
        <input type="hidden" name="hdnTbVincularLancamento" id="hdnTbVincularLancamento" value="" />

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

        <input type="hidden" name="hdnNumInteressado" value="<?= $numInteressado ?>">

</fieldset>
</div>