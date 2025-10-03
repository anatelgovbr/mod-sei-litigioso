<?
/**
 * ANATEL
 *
 * 19/01/2016 - criado por marcelo.bezerra - CAST
 *
 */

try {

    require_once dirname(__FILE__) . '/../../SEI.php';
    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();
    PaginaSEI::getInstance()->verificarSelecao('md_lit_tipo_controle_selecionar');
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    // ======================= INICIO ACOES PHP DA PAGINA
    //script contendo açoes realizadas nesta pagina conforme contexto (se for inserçaõ, update etc..)
    //atens ficava na propria pagina mas foi externalizado para uma pagina separada
    //para facilitar manutenção da aplicação
    require_once 'md_lit_tipo_controle_cadastro_acoes.php';
    // ======================= FIM ACOES PHP DA PAGINA

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

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
require_once('md_lit_tipo_controle_cadastro_css.php');
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTipoControleLitigiosoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblNome" for="txtSigla" accesskey="p" class="infraLabelObrigatorio">Sigla:</label>
                    <input type="text" id="txtSigla" name="txtSigla" class="infraText form-control"
                        value="<?= PaginaSEI::tratarHTML($objTipoControleLitigiosoDTO->getStrSigla()); ?>"
                        onkeypress="return infraMascaraTexto(this,event,50);"
                        maxlength="50" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblDescricao" for="txtDescricao" accesskey="q"
                        class="infraLabelObrigatorio">Descrição:</label>
                    <textarea id="txaDescricao" name="txtDescricao" rows="4" class="infraTextArea form-control"
                            onkeypress="return infraLimitarTexto(this,event,250);"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= PaginaSEI::tratarHTML($objTipoControleLitigiosoDTO->getStrDescricao()); ?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblDtCorte" name="lblDtCorte" for="txtDtCorte" class="infraLabelObrigatorio">Data de
                        Corte: <a id="btAjudaDth" <?= PaginaSEI::montarTitleTooltip('Serve para definir a data a partir da qual os processos gerados dos tipos indicados serão considerados pendentes de Cadastro no
Módulo Litigioso. A data de referência será a Data de Autuação de cada processo. Contudo, não haverá impedimento de cadastro de
processos com Data de Autuação anterior à Data de Corte.', 'Ajuda') ?>
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <img id="imgAjudaDth" border="0"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                class="infraImgModulo"/>
                        </a></label>
                    <div class="input-group">
                        <input type="text" id="txtDtCorte" name="txtDtCorte"
                            onchange="return validarFormatoData(this)"
                            onkeypress="return infraMascara(this, event,'##/##/####')" class="infraText"
                            value="<?= PaginaSEI::tratarHTML($objTipoControleLitigiosoDTO->getDtaDtaCorte()); ?>"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg?'.Icone::VERSAO ?>"
                            id="imgCalDthCorte" title="Selecionar Data/Hora Inicial" alt="Selecionar Data de Corte"
                            class="infraImg"
                            onclick="infraCalendario('txtDtCorte',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblGestores" for="selGestores" accesskey=""
                        class="infraLabelObrigatorio">Gestores:</label>
                        <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip('Para o funcionamento correto deste parâmetro, no SIP deve ser concedido o Perfil “Gestor de Controle Litigioso” aos usuários selecionados.', 'Ajuda') ?>
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                <img border="0"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                    class="infraImg"/>
                            </a>
                <input type="text" id="txtGestor" name="txtGestor" class="infraText form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <div class="input-group">
                        <select id="selGestores" name="selGestores" size="8" multiple="multiple"
                                class="infraSelect form-select">
                            <?= $strItensSelGestores ?>
                        </select>
                        <div class="botoes">
                            <img id="imgLupaGestores" onclick="objLupaGestores.selecionar(700,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Selecionar Gestor" title="Selecionar Gestor"
                                class="infraImg"/>

                            <br>
                            <img id="imgExcluirGestores" onclick="objLupaGestores.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Gestor Selecionado" title="Remover Gestor Selecionado"
                                class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnIdGestor" name="hdnIdGestor" value=""/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblMotivos" for="selMotivos" accesskey="" class="infraLabelOpcional">Motivos para
                    Instauração:</label>
                <input type="text" id="txtMotivos" name="txtMotivos" class="infraText form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <div class="input-group">
                        <select id="selMotivos" name="selMotivos" size="8" multiple="multiple" class="infraSelect form-select">
                            <?= $strItensSelMotivos ?>
                        </select>
                        <div class="botoes">
                            <img id="imgLupaMotivos" onclick="objLupaMotivos.selecionar();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Selecionar Motivos" title="Selecionar Motivos" class="infraImg"/>
                            <br>
                            <img id="imgExcluirMotivos" onclick="objLupaMotivos.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Motivo Selecionado" title="Remover Motivo Selecionado"
                                class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnIdMotivos" name="hdnIdMotivos" value=""/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblTipoProcessos" for="selTipoProcessos" accesskey="" class="infraLabelObrigatorio">Tipos
                    de
                    Processos:</label>
                <input type="text" id="txtTipoProcesso" name="txtTipoProcesso" class="infraText form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <div class="input-group">
                        <select id="selTipoProcessos" name="selTipoProcessos" size="8" multiple="multiple"
                                class="infraSelect form-select">
                            <?= $strItensSelTipoProcessos ?>
                        </select>
                        <div class="botoes">
                            <img id="imgLupaTipoProcessos" onclick="objLupaTipoProcessos.selecionar(700,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Selecionar Tipo de Processo"
                                title="Selecionar Tipo de Processo" class="infraImg"/>
                            <br>
                            <img id="imgExcluirTipoProcessos" onclick="objLupaTipoProcessos.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Tipo de Processo Selecionado"
                                title="Remover Tipo de Processo Selecionado" class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnIdTipoProcesso" name="hdnIdTipoProcesso" value=""/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblUnidades" for="selUnidades" accesskey=""
                        class="infraLabelObrigatorio">Unidades:</label>
                <input type="text" id="txtUnidade" name="txtUnidade" class="infraText form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <div class="input-group">
                        <select id="selUnidades" name="selUnidades" size="8" multiple="multiple"
                                class="infraSelect form-select">
                            <?= $strItensSelUnidades ?>
                        </select>
                        <div class="botoes">
                            <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Selecionar Unidade" title="Selecionar Unidade"
                                class="infraImg"/>
                            <br>
                            <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada"
                                class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value=""/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <? if ($strItensSelTipoProcessosSobrestados != null && $strItensSelTipoProcessosSobrestados != "") { ?>
                        <input type="checkbox" checked="checked" name="chkPodeSobrestar" class="infraCheckbox"
                            onchange="selecionarChkSobrestar()" id="chkPodeSobrestar"/>
                    <? } else { ?>
                        <input type="checkbox" name="chkPodeSobrestar" onchange="selecionarChkSobrestar()"
                            class="infraCheckbox" id="chkPodeSobrestar"/>
                    <? } ?>

                    <label id="lblTipoProcessosChkSobrestados1" for="chkPodeSobrestar" accesskey=""
                        class="infraLabel">
                        Pode sobrestar a tramitação de outros processos
                    </label>
                </div>
            </div>
        </div>
        <?
        if ($strItensSelTipoProcessosSobrestados != null && $strItensSelTipoProcessosSobrestados != "") {
            $visibilidade = "display: block;";
        } else {
            $visibilidade = "display: none;";
        }

        ?>
        <div id="divTiposProcessosSobrestadosCampos" style="<?= $visibilidade; ?>">
            <div class="row">
                <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                    <label id="lblTipoProcessosSobrestados2" for="selTipoProcessosSobrestados" accesskey=""
                            class="infraLabelObrigatorio">
                        Tipos de Processos que podem ser sobrestados:
                    </label>

                    <input type="text" id="txtTipoProcessoSobrestado" name="txtTipoProcessoSobrestado"
                            class="infraText form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <div class="input-group">
                            <select id="selTipoProcessosSobrestados" name="selTipoProcessosSobrestados" size="8"
                                    multiple="multiple" class="infraSelect form-select">
                                <?= $strItensSelTipoProcessosSobrestados ?>
                            </select>
                            <div class="botoes">
                                <img id="imgLupaTipoProcessosSobrestados"
                                    onclick="objLupaTipoProcessosSobrestados.selecionar(700,500);"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                    alt="Selecionar Tipo de Processo"
                                    title="Selecionar Tipo de Processo" class="infraImg"/>
                                <br>
                                <img id="imgExcluirTipoProcessosSobrestados"
                                    onclick="objLupaTipoProcessosSobrestados.remover();"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                    alt="Remover Tipo de Processo Selecionado"
                                    title="Remover Tipo de Processo Selecionado"
                                    class="infraImg"/>
                            </div>
                            <input type="hidden" id="hdnIdTipoProcessoSobrestado" name="hdnIdTipoProcessoSobrestado"
                                value=""/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="hdnIdTipoControleLitigioso" name="hdnIdTipoControleLitigioso"
               value="<?= $objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso(); ?>"/>
        <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?= $_POST['hdnUnidades'] ?>"/>
        <input type="hidden" id="hdnTipoProcessos" name="hdnTipoProcessos"
               value="<?= $_POST['hdnTipoProcessos'] ?>"/>
        <input type="hidden" id="hdnGestores" name="hdnGestores" value="<?= $_POST['hdnGestores'] ?>"/>
        <input type="hidden" id="hdnMotivos" name="hdnMotivos" value="<?= $_POST['hdnMotivos'] ?>"/>
        <input type="hidden" id="hdnTipoProcessosSobrestados" name="hdnTipoProcessosSobrestados"
               value="<?= $_POST['hdnTipoProcessosSobrestados'] ?>"/>

    </form>
<?
PaginaSEI::getInstance()->fecharAreaDados();
require_once('md_lit_tipo_controle_cadastro_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
