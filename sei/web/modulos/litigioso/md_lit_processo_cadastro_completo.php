<?
/**
 * ANATEL
 *
 * 15/02/2016 - criado por jaqueline.mendes@cast.com.br - CAST
 *
 */
//inclusao de conteudos JavaScript adicionais
require_once('md_lit_processo_cadastro_completo_inicializacao.php');

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once('md_lit_processo_cadastro_completo_css.php');
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();" onunload="sair();"');
?>
    <form id="frmCadastroProcesso" method="post" onsubmit="return OnSubmitForm();"
          action="<?
          echo PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_cadastro_cadastrar&id_procedimento=' . $idProcedimento));
          ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>

        <div class="linha">
            <label class="infraLabelObrigatorio">Tipo de Controle Litigioso:</label>
            <label> <?= $strNomeTipoControle ?></label>
            <input type=hidden name=idProcedimento id=idProcedimento value='<?= $idProcedimento ?>'>
            <input type=hidden name=ProtocoloProcedimentoFormatado id=ProtocoloProcedimentoFormatado
                   value='<?= $ProtocoloProcedimentoFormatado ?>'>
            <input type=hidden name=hdnUnidadeProcedimento id=hdnUnidadeProcedimento
                   value='<?= $UnidadeProcedimento ?>'>
        </div>

        <div class="linha">
            <!--  DOCUMENTO INSTAURADOR DO PROCEDIMENTO LITIGIOSO -->
            <fieldset id="fieldsetDocInstaurador" class="infraFieldset form-control"
                      onkeypress="return enterValidarDocumento(event)">
                <legend class="infraLegend">&nbsp;Documento Instaurador do Procedimento Litigioso&nbsp;</legend>
                <div class="row linha">
                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                        <label id="lblNumeroSei" for="txtNumeroSei" class="infraLabelObrigatorio">Número SEI:</label>
                        <div class="input-group mb-3">
                            <input type="text" id="txtNumeroSei" name="txtNumeroSei"
                                   class="infraText form-control"
                                   value="<?php echo $txtNumeroSei ?>"
                                   onkeyup="removerValidacaoDocInstaurador()" maxlength="11"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                            <button type="button" name="sbmValidarNumeroSei" id="sbmValidarNumeroSei"
                                    value="Validar"
                                    class="infraButton sbmValidarNumeroSei"
                                    onclick="javascript:preencheNumeroSei(document.getElementById('txtNumeroSei'));"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Validar
                            </button>
                            <input type="hidden" id="hdnIdDocumento" name="hdnIdDocumento">
                            <input type="hidden" id="hdnNumeroSei" name="hdnNumeroSei">
                            <input type="hidden" id="hdnNumero" name="hdnNumero">
                            <input type="hidden" id="hdnUnidade" name="hdnUnidade">
                            <input type="hidden" id="hdnData" name="hdnData">
                            <input type="hidden" id="hdnDataAssinatura" name="hdnDataAssinatura">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">
                        <label id="lblTipo" for="txtTipo" class="infraLabelObrigatorio">Tipo:</label>
                        <div class="input-group mb-3" style="margin-top: -4px;">
                            <input type="text" id="txtTipo" name="txtTipo" class="infraText form-control" value=""
                                   readonly
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                            <button type="button" name="sbmAdicionarNumeroSei" onclick="adicionarDocInstaurador();"
                                    id="sbmAdicionarNumeroSei" value="Adicionar"
                                    class="infraButton NumeroSEINaoValidado NumeroSEIAdicionar"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Adicionar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row linha NumeroSEINaoValidado" id="divTabelaDocInstaurador">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <input type="hidden" name="hdnIdDocInstauradorCadastrado" id="hdnIdDocInstauradorCadastrado"
                               value=""/>
                        <input type="hidden" name="hdnListaDocInstauradores" id="hdnListaDocInstauradores"
                               value="<?= $hdnListaDocInstauradores ?>"/>
                        <table id="tbDocInstaurador" class="infraTable" width="100%" align="left"
                               summary="Lista de Documento Instaurador">
                            <tr>
                                <th class="infraTh" style="display: none;">ID Doc Instaurador</th>
                                <th class="infraTh" style="display: none;">Documento ID</th>
                                <th class="infraTh" style="display: none;">Documento Formatado</th>
                                <th class="infraTh" style="display: none;">Documento Numero</th>
                                <th class="infraTh" style="display: none;">Documento Tipo</th>
                                <th class="infraTh" align="center" width="30%" id="tdDescDocumento">Documento</th>
                                <th class="infraTh" align="center" width="40%" id="tdDescUnidade">Unidade</th>
                                <th class="infraTh" align="center" width="10%" id="tdDescData">Data</th>
                                <th class="infraTh" width="10%" align="center">Ações</th>
                            </tr>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div style="clear:both;" class="infraAreaTabela NumeroSEINaoValidado"
                     id="divTabelaDocInstaurador2">&nbsp;
                </div>

                <div class="row NumeroSEINaoValidado">
                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-2">
                        <label id="lblDtInstauracao" class="infraLabelObrigatorio" for="txtDtInstauracao">Data da
                            Instauração:</label>
                        <div class="input-group mb-3">
                            <input id="txtDtInstauracao" class="infraText form-control" type="text"
                                   onkeypress="return infraMascara(this, event, '##/##/####');"
                                   onblur="validaData(this);" value="<?= $txtDtInstauracao ?>"
                                   name="txtDtInstauracao"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <img id="imgDtInstauracao" class="infraImg"
                                 onclick="infraCalendario('txtDtInstauracao',this,false,null);"
                                 alt="Selecionar Data da Instauração" title="Selecionar Data da Instauração"
                                 src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>">
                        </div>
                    </div>
                </div>
                <div class="linha"></div>
            </fieldset>
            <!--  DOCUMENTO INSTAURADOR DO PROCEDIMENTO LITIGIOSO - FIM -->
        </div>


        <div class="linha">
            <!--  INTERESSADOS -->
            <div class="rowFieldSet">
                <input type="hidden" id="hdnTbInteressado" name="hdnTbInteressado"/>
                <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?= $idProcedimento ?>"/>
                <input type="hidden" id="hdnAbriuModalInteressado" name="hdnAbriuModalInteressado"/>
                <input type="hidden" id="hdnIdMdLitControle" name="hdnIdMdLitControle"
                       value="<?= $hdnIdMdLitControle ?>"/>
                <input type="hidden" id="hdnIdMdLitTipoControle" name="hdnIdMdLitTipoControle"
                       value="<?= $idTpControle ?>"/>
                <!-- input falso, para a modal de alteracao de contato seja executado e fechada -->
                <input type="text" id="x" name="x" style="display: none" value=""/>

                <fieldset id="fieldsetDocInstaurador" class="infraFieldset NumeroSEINaoValidado form-control">
                    <legend class="infraLegend">
                        &nbsp;Interessados&nbsp;<span
                                class="tooltipAjuda" <?= PaginaSEI::montarTitleTooltip($strMsgTooltipInteressados, 'Ajuda') ?> ></span>
                    </legend>
                    <br/>
                    <table class="infraTable table" summary="Interessados" id="tbInteressado">
                        <tr>
                            <th class="infraTh" style="display: none;">ID Contato</th>
                            <th class="infraTh" style="display: none;">URl Contato</th>
                            <th class="infraTh" style="display: none;">Natureza</th>
                            <th class="infraTh" style="display: none;">Endereço</th>
                            <th class="infraTh" style="display: none;">Bairro</th>
                            <th class="infraTh" style="display: none;">Cidade</th>
                            <th class="infraTh" width="25%">Tipo de Contato</th>
                            <th class="infraTh" width="30%">Interessado</th>
                            <th class="infraTh" width="35%" align="center">CNPJ/CPF</th>
                            <th class="infraTh" style="display: none;">Sin param modal</th>
                            <th class="infraTh" style="display: none;">Contar Lancamento</th>
                            <th class="infraTh" style="display: none;">CEP</th>
                            <th class="infraTh" width="10%">Ações</th>
                        </tr>
                        <tbody>
                        </tbody>
                    </table>
                </fieldset>

                <div id="divInteressados">
                    <!-- HDNs -->
                </div>
                <!--  FIM INTERESSADOS -->
            </div>
        </div>
        <!--  FIM INTERESSADOS -->

        <div class="linha">
            <!--  INTERESSADOS -->
            <div class="rowFieldSet">
                <fieldset id="fieldsetInfracoes" class="infraFieldset NumeroSEINaoValidado form-control">
                    <legend class="infraLegend ">&nbsp;&nbsp;Infrações&nbsp;</legend>
                    <!--  Indicar por dispositivo normativo -->
                    <div class="row linha">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 linha">
                            <input onclick="changeInfracoes();" type="radio" name="rdInfracoes[]"
                                   id="rdIndicDisposNormativo" onkeypress="return enterAdicionarInfracao(event)"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                   class="infraRadio form-control">
                            <label id="lblIndicDisposNormativo" class="infraLabelRadio" for="rdIndicDisposNormativo">Indicar
                                por Dispositivo Normativo</label>
                        </div>
                    </div>

                    <div class="classDispositivoNormativo my-2">
                        <!--  Dispositivo Normativo -->
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label id="lblIDNDispositivoNormativo" class="infraLabelObrigatorio"
                                           for="selIDNDispositivoNormativo">Dispositivo Normativo:</label>
                                    <div class="input-group">
                                        <input type="text" id="txtIDNDispNormat" name="txtIDNDispNormat"
                                               class="infraText form-control"
                                               value="<?php echo $idnDispositivoNormativo; ?>"
                                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                        <input type="hidden" id="hdnIdIDNDispNormat" name="hdnIdIDNDispNormat"
                                               value="<?php echo $IdIDNDispNormat ?>"/>
                                        <div class="botoes">
                                            <img id="imgLupaIDNDispNorma"
                                                 onclick="objLupaIDNDispositivoNormativo.selecionar(1000,600);"
                                                 src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                                 alt="Selecionar Dispositivo Normativo"
                                                 title="Selecionar Dispositivo Normativo" class="infraImg"
                                                 tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label id="lblIDNCondutas" class="infraLabelObrigatorio"
                                           for="selIDNCondutas" style="display: none">Conduta:</label>
                                    <select id="selIDNCondutas" name="selIDNCondutas" style="display: none"
                                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                            class="infraSelect form-control">
                                        <?= $strItensSelIDNCondutas ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div onkeypress="return enterAdicionarInfracao(event)"
                                 class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label id="lblDtaInfracaoPorDispositivo" class="infraLabelObrigatorio">Data da
                                    Infração</label>

                                <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 mx-n3 linha">
                                    <input onclick="changeDataInfracoes();" type="radio" name="rdDataInfracoes[]"
                                           id="rdDataInfracaoEspecificaPorDispositivo"
                                           onkeypress="return enterAdicionarInfracao(event)"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                           class="infraRadio form-control">
                                    <label id="lblDataInfracaoEspecificaPorDispositivo" class="infraLabelRadio"
                                           for="rdDataInfracaoEspecificaPorDispositivo">Data Especifica:</label>
                                </div>
                                <div id="conteudoDataInfracaoEspecificaPorDispositivo" class="linha mx-4">
                                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                        <div class="input-group">
                                            <input id="txtDtaInfracaoPorDispositivo" name="txtDtaInfracaoPorDispositivo"
                                                   type="text" class="infraText form-control"
                                                   onkeypress="return infraMascara(this, event, '##/##/####');"
                                                   autocomplete="off"
                                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">

                                            <img id="imgDtaInfracaoPorDispositivo" class="infraImg"
                                                 onclick="infraCalendario('txtDtaInfracaoPorDispositivo',this,false,null);"
                                                 alt="Selecionar Data da Infração" title="Selecionar Data da Infração"
                                                 src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mx-n3 linha">
                                    <input onclick="changeDataInfracoes();" type="radio" name="rdDataInfracoes[]"
                                           id="rdDataInfracaoPeriodoPorDispositivo"
                                           onkeypress="return enterAdicionarInfracao(event)"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                           class="infraRadio form-control">
                                    <label id="lblDataInfracaoPeriodoPorDispositivo" class="infraLabelRadio"
                                           for="rdDataInfracaoPeriodoPorDispositivo">Período:</label>
                                </div>
                                <div id="conteudoDataInfracaoPeriodoPorDispositivo">
                                    <div class="row mx-4 linha">
                                        <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">
                                            <div class="input-group mb-3">
                                                <input id="txtDtaInfracaoInicialPorDispositivo"
                                                       name="txtDtaInfracaoPorDispositivo" type="text"
                                                       class="infraText form-control"
                                                       onkeypress="return infraMascara(this, event, '##/##/####');"
                                                       autocomplete="off"
                                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">

                                                <img id="imgDtaInfracaoInicialPorDispositivo" class="infraImg"
                                                     onclick="infraCalendario('txtDtaInfracaoInicialPorDispositivo',this,false,null);"
                                                     alt="Selecionar Data da Infração Inicial"
                                                     title="Selecionar Data da Infração Inicial"
                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>">
                                            </div>
                                        </div>
                                        <div align="center">
                                            <label class="infraLabelOpcional">a</label>
                                        </div>
                                        <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">
                                            <div class="input-group mb-3">
                                                <input id="txtDtaInfracaoFinalPorDispositivo"
                                                       name="txtDtaInfracaoPorDispositivo" type="text"
                                                       class="infraText form-control"
                                                       onkeypress="return infraMascara(this, event, '##/##/####');"
                                                       autocomplete="off"
                                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">

                                                <img id="imgDtaInfracaoFinalPorDispositivo" class="infraImg"
                                                     onclick="infraCalendario('txtDtaInfracaoFinalPorDispositivo',this,false,null);"
                                                     alt="Selecionar Data da Infração"
                                                     title="Selecionar Data da Infração Final"
                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row linha">
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <button type="button" onclick="adicionarDI();" name="sbmIDNAdicionar"
                                                id="sbmIDNAdicionar"
                                                value="Adicionar" class="infraButton"
                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Adicionar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  Indicar por conduta -->
                    <div class="row linha">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 linha">
                            <div>
                                <input onclick="changeInfracoes();" type="radio" name="rdInfracoes[]"
                                       id="rdIndicConduta"
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                       onkeypress="return enterAdicionarInfracao(event)"
                                       class="infraRadio form-control">
                                <label id="lblIndicConduta" class="infraLabelRadio" for="rdIndicConduta">Indicar por
                                    Conduta</label>
                            </div>
                            <div class="classCondutas">
                                <!--  Conduta -->

                                <div class="row linha">
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label class="infraLabelObrigatorio">Conduta:</label>
                                        <select id="selICCondutas" name="selICCondutas"
                                                onchange="mostrarDispositivoPorConduta()"
                                                onkeypress="return enterAdicionarInfracao(event)"
                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                                class="infraSelect form-control">
                                            <?= $strItensSelConduta ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6" id="divDispositivoPorConduta"
                                         style="display: none">
                                        <label id="lblICDispNormat"
                                               class="infraLabelObrigatorio" for="txtICDispNormat">Dispositivo
                                            Normativo:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" id="txtICDispNormat" name="txtICDispNormat"
                                                   class="infraText form-control"
                                                   value="<?php echo $nomeTipoProcesso; ?>"
                                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                            <input type="hidden" id="hdnIdICDispNormat" name="hdnIdICDispNormat"
                                                   value="<?php echo $idTipoProcesso ?>"/>
                                            <img id="imgLupaTipoProcesso"
                                                 onclick="objLupaICDispositivoNormativo.selecionar(1000,700);"
                                                 src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                                 alt="Selecionar Dispositivo Normativo"
                                                 title="Selecionar Dispositivo Normativo" class="infraImg"
                                                 tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                        </div>
                                    </div>
                                </div>


                                <div class="row linha my-1">
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <label id="lblDtaInfracaoPorConduta" class="infraLabelObrigatorio">Data da
                                            Infração</label>
                                        <div class="linha">
                                            <input onclick="changeDataInfracoes();" type="radio"
                                                   name="rdDataInfracoes[]"
                                                   id="rdDataInfracaoEspecificaPorConduta"
                                                   class="infraRadio form-control"
                                                   onkeypress="return enterAdicionarInfracao(event)"
                                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            <label id="lblDataInfracaoEspecificaPorConduta" class="infraLabelRadio"
                                                   for="rdDataInfracaoEspecificaPorConduta">Data Especifica:</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row linha" onkeypress="return enterAdicionarInfracao(event)">
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row mx-4"
                                             id="conteudoDataInfracaoEspecificaPorConduta">
                                            <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">
                                                <div class="input-group">
                                                    <input id="txtDtaInfracaoPorConduta" name="txtDtaInfracaoPorConduta"
                                                           type="text"
                                                           class="infraText form-control"
                                                           onkeypress="return infraMascara(this, event, '##/##/####');"
                                                           autocomplete="off"
                                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">

                                                    <img id="imgDtaInfracaoPorConduta" class="infraImg"
                                                         onclick="infraCalendario('txtDtaInfracaoPorConduta',this,false,null);"
                                                         alt="Selecionar Data da Infração"
                                                         title="Selecionar Data da Infração"
                                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <input onclick="changeDataInfracoes();" type="radio" name="rdDataInfracoes[]"
                                               id="rdDataInfracaoPeriodoPorConduta" class="infraRadio form-control"
                                               onkeypress="return enterAdicionarInfracao(event)"
                                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <label id="lblDataInfracaoPeriodoPorConduta" class="infraLabelRadio"
                                               for="rdDataInfracaoPeriodoPorConduta">Período:</label>
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                         id="conteudoDataInfracaoPeriodoPorConduta">
                                        <div class="row mx-4 linha">
                                            <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">
                                                <div class="input-group mb-3">
                                                    <input id="txtDtaInfracaoInicialPorConduta"
                                                           name="txtDtaInfracaoPorConduta" type="text"
                                                           class="infraText form-control"
                                                           onkeypress="return infraMascara(this, event, '##/##/####');"
                                                           autocomplete="off"
                                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">

                                                    <img id="imgDtaInfracaoInicialPorConduta" class="infraImg"
                                                         onclick="infraCalendario('txtDtaInfracaoInicialPorConduta',this,false,null);"
                                                         alt="Selecionar Data da Infração Inicial"
                                                         title="Selecionar Data da Infração Inicial"
                                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>">
                                                </div>
                                            </div>
                                            <div align="center">
                                                <label class="infraLabelOpcional">a</label>
                                            </div>
                                            <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">
                                                <div class="input-group mb-3">
                                                    <input id="txtDtaInfracaoFinalPorConduta"
                                                           name="txtDtaInfracaoPorConduta" type="text"
                                                           class="infraText form-control"
                                                           onkeypress="return infraMascara(this, event, '##/##/####');"
                                                           autocomplete="off"
                                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">

                                                    <img id="imgDtaInfracaoFinalPorConduta" class="infraImg"
                                                         onclick="infraCalendario('txtDtaInfracaoFinalPorConduta',this,false,null);"
                                                         alt="Selecionar Data da Infração"
                                                         title="Selecionar Data da Infração Final"
                                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row linha">
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <button type="button" onclick="adicionarDI();" name="sbmICAdicionar"
                                                    id="sbmICAdicionar"
                                                    value="Adicionar" class="infraButton"
                                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                Adicionar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="hdnIdDispositivoNormativoNormaCondutaControle"
                           id="hdnIdDispositivoNormativoNormaCondutaControle" value="">
                    <input type="hidden" name="hdnIdsInfracoesExcluidas" id="hdnIdsInfracoesExcluidas">

                    <div class="row linha">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 linha">
                            <input type="hidden" name="hdnCustomizado" id="hdnCustomizado" value=""/>
                            <input type="hidden" name="hdnIdDICadastrado" id="hdnIdDICadastrado" value=""/>
                            <input type="hidden" name="hdnListaDIIndicados" id="hdnListaDIIndicados"
                                   value='<?= $hdnListaDIIndicados ?>'/>
                            <table name="tbDispositivosInfrigidos" id="tbDispositivosInfrigidos"
                                   class="infraTable table"
                                   style="display: none"
                                   align="left" summary="Lista de Dispositivos Infringidos">
                                <caption
                                        class="infraCaption"><?= PaginaSEI::getInstance()->gerarCaptionTabela("Dispositivos Infringidos", 0) ?></caption>
                                <tr>
                                    <th class="infraTh" width="1%" style="display: none;">ID</th>
                                    <th class="infraTh" width="1%" style="display: none;">ID Dispositivo Infringido</th>
                                    <th class="infraTh" width="35" id="tdDescNorma">Norma</th>
                                    <th class="infraTh" width="1%" style="display: none;">ID Dispositivo</th>
                                    <th class="infraTh" width="17%" id="tdDescDispositivo">Dispositivo</th>
                                    <th class="infraTh" width="1%" style="display: none;">ID Conduta</th>
                                    <th class="infraTh" width="35%" id="tdDescConduta">Conduta</th>
                                    <th class="infraTh" width="18%" id="tdDtaInfracao">Data da Infração</th>
                                    <th class="infraTh" width="1%" style="display: none;">Data da Infração Especifica
                                    </th>
                                    <th class="infraTh" width="1%" style="display: none;">Data da Infraçãp periodo
                                        inicial
                                    </th>
                                    <th class="infraTh" width="1%" style="display: none;">Data da Infração periodo final
                                    </th>
                                    <th class="infraTh" width="1%" style="display: none;">Status da infração data</th>
                                    <th class="infraTh" width="10%" align="center"> Ações</th>
                                </tr>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="linha">
            <div class="rowFieldSet">
                <!-- MOTIVOS -->
                <?php if ($existeMotivo || $strItensSelMotivos): ?>
                    <div class="NumeroSEINaoValidado">
                        <fieldset class="infraFieldset form-control" id="fieldMotivo">
                            <legend class="infraLegend">&nbsp;Motivos para Instauração&nbsp;</legend>

                            <div class="row linha">
                                <div class="col-9 col-sm-10 col-md-7 col-lg-6 col-xl-6">
                                    <label id="lblMotivos" for="selMotivos" accesskey=""
                                           class="<?php echo $existeMotivo ? 'infraLabelObrigatorio' : 'infraLabelOpcional' ?>">Motivos:
                                    </label>
                                    <input type="text" id="txtMotivos" name="txtMotivos" class="infraText form-control"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                </div>
                            </div>

                            <div class="row linha">
                                <div class="col-sm-12 col-md-9 col-lg-8 col-xl-7">
                                    <div class="input-group mb-3">
                                        <select id="selMotivos" name="selMotivos" size="6" multiple="multiple"
                                                class="infraSelect">
                                            <?= $strItensSelMotivos ?>
                                        </select>
                                        <div class="botoes">
                                            <div id="divOpcoesMotivos">
                                                <img id="imgLupaMotivos" onclick="objLupaMotivos.selecionar(900,600);"
                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                                     alt="Selecionar Motivos" title="Selecionar Motivos"
                                                     class="infraImg"/>
                                                <br>
                                                <img id="imgExcluirMotivos" onclick="objLupaMotivos.remover();"
                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/remover.svg?<?= Icone::VERSAO ?>"
                                                     alt="Remover Motivo Selecionado" title="Remover Motivo Selecionado"
                                                     class="infraImg"/>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" id="hdnIdMotivos" name="hdnIdMotivos" value=""/>
                                </div>
                            </div>
                            <div style="clear:both;">&nbsp;</div>
                        </fieldset>
                    </div>
                <?php endif; ?>
                <!-- FIM MOTIVOS -->
            </div>
        </div>
        <!--  PROCESSOS A SEREM SOBRESTADOS -->


        <!--  INFRAÇÕES -->
        <div>
            <?php
            if ($bolTipoProcedimentosSobrestadosGeracao > 0) {
                echo '<fieldset id="fieldsetProcessos" class="infraFieldset NumeroSEINaoValidado form-control">';
            } else {
                echo '<fieldset id="fieldsetProcessos" class="infraFieldset form-contrl" style="display:none!important">';
            }
            ?>
            <legend class="infraLegend">&nbsp;Processos a serem Sobrestados</legend>
            <div style="clear:both;">&nbsp;</div>
            <div style=" float: left; width: 100%;">
                <div style=" float: left; width: 35%;">
                    <label id="lblNumeroSeiPS" for="txtNumeroSeiPS" class="infraLabelObrigatorio">Número SEI do
                        documento de determinação:</label>
                </div>
                <div style=" float: left; width: 40%;">
                    <label id="lblNumeroSeiTipoPS" for="txtNumeroSeiTipoPS" class="infraLabelObrigatorio">Tipo:</label>
                </div>
                <div style=" float: left; width: 25%;">
                    <label id="lblDtSobrestamentoPS" class="infraLabelObrigatorio" for="txtDtSobrestamentoPS">Data do
                        Sobrestamento:</label>
                </div>
            </div>
            <div style=" float: left; width: 100%;">
                <div style=" float: left; width: 35%;">
                    <input type="text" id="txtNumeroSeiPS" name="txtNumeroSeiPS" class="infraText" maxlength="11"
                           value="<?= $txtNumeroSeiPS ?>"
                           onkeypress=" return enterValidarDocumentoSobrestado(event)"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    &nbsp;
                    <button type="button" id="sbmValidarNumeroSeiPS" name="sbmValidarNumeroSeiPS" value="Validar"
                            class="infraButton"
                            onclick="javascript:preencheNumeroSeiPS(document.getElementById('txtNumeroSeiPS'));"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Validar
                    </button>
                    <input type="hidden" id="hdnNumeroSeiPS" name="hdnNumeroSeiPS" value="<?= $txtNumeroSeiPS ?>"/>
                    <input type="hidden" id="hdnIdnumeroSeiPS" name="hdnIdnumeroSeiPS"
                           value="<?= $hdnIdnumeroSeiPS ?>"/>
                </div>
                <div style=" float: left; width: 40%;">
                    <input type="text" id="txtNumeroSeiTipoPS" name="txtNumeroSeiTipoPS" class="infraText"
                           value="<?= $txtNumeroSeiTipoPS ?>"
                           onkeypress="enterAdicionarSobrestado(event)" readonly
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
                <div style=" float: left; width: 25%;">
                    <input id="txtDtSobrestamentoPS" class="infraText" type="text"
                           onkeypress="return infraMascara(this, event, '##/##/####') && enterAdicionarSobrestado(event);"
                           onblur="validaData(this);"
                           value="<?= $txtDtSobrestamentoPS ?>" name="txtDtSobrestamentoPS"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <img id="imgDtSobrestamentoPS" class="infraImg"
                         onclick="infraCalendario('txtDtSobrestamentoPS',this,false,null);"
                         alt="Selecionar Data/Hora Inicial" title="Selecionar Data/Hora Inicial"
                         src="/infra_css/imagens/calendario.gif">
                </div>
            </div>
            <div style="clear:both;">&nbsp;</div>
            <div style=" float: left; width: 100%;">
                <div style=" float: left; width: 35%;">
                    <label id="lblNumeroProcessoPS" for="txtNumeroProcessoPS" class="infraLabelObrigatorio">Número do
                        Processo a ser Sobrestado:</label>
                </div>
                <div style=" float: left; width: 40%;">
                    <label id="lblNumeroProcessoTipoPS" for="txtNumeroProcessoTipoPS"
                           class="infraLabelObrigatorio">Tipo:</label>
                </div>
                <div style=" float: left; width: 25%;">
                    <label class="infraLabelObrigatorio">&nbsp;</label>
                </div>
            </div>
            <div style=" float: left; width: 100%;">
                <div style=" float: left; width: 35%;">
                    <input type="text" id="txtNumeroProcessoPS" name="txtNumeroProcessoPS" class="infraText"
                           maxlength="20" value=""
                           onkeypress="if(infraGetCodigoTecla(event)==13){ return enterValidarProcessoSobrestado(event);}else{return infraMascara(this, event, '#####.######/####-##');}"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    &nbsp;
                    <button type="button" id="sbmValidarNumeroProcessoPS" name="sbmValidarNumeroProcessoPS"
                            value="Validar" class="infraButton"
                            onclick="javascript:preencheNumeroProcessoPS(document.getElementById('txtNumeroProcessoPS'));"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Validar
                    </button>
                    <input type="hidden" id="hdnNumeroProcessoPS" name="hdnNumeroProcessoPS" value=""/>
                    <input type="hidden" id="hdnIdProcedimentoPS" name="hdnIdProcedimentoPS">
                    <input type="hidden" id="hdnUnidadeProcessoPS" name="hdnUnidadeProcessoPS">
                </div>
                <div style=" float: left; width: 40%;">
                    <input type="text" id="txtNumeroProcessoTipoPS" name="txtNumeroProcessoTipoPS" class="infraText"
                           onkeypress="return enterAdicionarSobrestado(event)" value="" readonly
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
                <div style=" float: left; width: 25%;">
                    <button type="button" name="sbmAdicionarNumeroSeiPS" onclick="adicionarPS();"
                            id="sbmAdicionarNumeroSei" value="Adicionar" class="infraButton"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Adicionar
                    </button>
                </div>
            </div>
            <?php //onkeypress="return validarCampo(this, event, this.maxLength)"  ?>

            <div style="clear:both;">&nbsp;</div>

            <div style=" float: left; width: 100%;">
                <input type="hidden" name="hdnListaPSIndicados" id="hdnListaPSIndicados"
                       value="<?= $hdnListaPSIndicados ?>"/>
                <table id="tbProcessosSobrestados" class="infraTable" width="100%" align="left"
                       summary="Lista de Processos a serem Sobrestados">
                    <caption
                            class="infraCaption"><?= PaginaSEI::getInstance()->gerarCaptionTabela("Processos a serem Sobrestados", 0) ?></caption>
                    <tr>
                        <th class="infraTh" style="display: none;">ID Processo Sobrestado</th>
                        <th class="infraTh" style="display: none;">ID Processo</th>
                        <th class="infraTh" width="30%" id="tdNumeroProcessoPS">Processo</th>
                        <th class="infraTh" width="40%" id="tdNumeroProcessoTipoPS">Tipo</th>
                        <th class="infraTh" width="10%" id="tdDtSobrestamentoPS">Data do Sobrestamento</th>
                        <th class="infraTh" width="10%" id="tdNumeroSeiTipoPS">Documento</th>
                        <th class="infraTh" style="display: none;">ID Documento</th>
                        <th class="infraTh" width="10%" align="center"> Ações</th>
                    </tr>
                    <tbody></tbody>
                </table>
            </div>

            </fieldset>
        </div>
        <!--  PROCESSOS A SEREM SOBRESTADOS - FIM-->

        <!--INFORMAÇOES ADICIONAIS-->
        <? if($arrOpcoesCampoAdicionais != '<option value=""></option>') { ?>
        <div id="divInformacoesComplementares" class="linha">
            <div class="rowFieldSet">
                <fieldset class="infraFieldset form-control NumeroSEINaoValidado" id="fieldInfoAdd">
                    <legend class="infraLegend">&nbsp;Informações Adicionais&nbsp;</legend>

                    <div class="row">
                        <div class="col-12">
                            <table width="100%">
                                <td width="31.5%">
                                    <div class="campo-adicional col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <label id="lblSelCampo" for="lblSelCampo" >Selecione o Tipo de Informação Adicional:</label>
                                        <div class="input-group mb-4">
                                            <select id="selCampo"
                                                    name="selCampo"
                                                    class="infraSelect form-control">
                                                <? echo $arrOpcoesCampoAdicionais; ?>
                                            </select>
                                    </div>
                                </td>
                                <td width="68.5%">
                                    <button type="button" name="sbmSelecionarCampo" id="sbmSelecionarCampo"
                                            value="Selecionar"
                                            class="infraButton sbmValidarNumeroSei"
                                            style="margin-left: -10px"
                                            onclick="adicionarCampo()">Adicionar
                                    </button>
                                </td>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="tbCamposSelecionados" class="infraTable">
                                <caption
                                        class="infraCaption">Atualizado por: <? echo $atualizadoPor; ?></caption>
                                <tr>
                                    <th class="infraTh" width="94%"> Campos de Informações Adicionais </th>
                                    <th class="infraTh" width="6%">Ações</th>
                                </tr>
                                <?
                                    // EXIBE AS OPÇÕES CADASTRADAS NA TELA
                                    echo $arrTabelaCamposAdicionais;

                                    // CASO NÃO TENHA OPÇÕES CADASTRADAS EXIBE MENSAGEM
                                    if($arrTabelaCamposAdicionais == '') {
                                        ?>
                                        <tr id="nenhumCampo">
                                            <td colspan="4" style="text-align: center">Nenhum campo adicionado</td>
                                        </tr>
                                        <?
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="campoOrganizador" name="campoOrganizador" value=""/>
                </fieldset>

                <!-- FIM MOTIVOS -->
            </div>
        </div>
        <? } ?>
        <!--  PROCESSOS A SEREM SOBRESTADOS -->

        <?
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
        <input type="hidden" id="hdnMotivos" name="hdnMotivos" value="<?= $_POST['hdnMotivos'] ?>"/>
    </form>

    <form target="alterarContato" method="post" id="frmAlterarContato">
        <input type="hidden" name="hdnContatoIdentificador" id="hdnContatoIdentificador"/>
        <input type="hidden" name="hdnContatoObject" id="hdnContatoObject"/>
        <input type="hidden" id="hdnMotivos" name="hdnMotivos" value="<?= $_POST['hdnMotivos'] ?>"/>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

//inclusao de conteudos JavaScript adicionais
require_once('md_lit_processo_cadastro_completo_js.php');
