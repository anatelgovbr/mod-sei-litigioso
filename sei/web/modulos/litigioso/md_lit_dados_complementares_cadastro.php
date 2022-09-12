<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/03/2017 - criado por pedro.cast
 *
 * Versão do Gerador de Código: 1.40.0
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
//    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    //////////////////////////////////////////////////////////////////////////////
//    InfraDebug::getInstance()->setBolLigado(false);
//    InfraDebug::getInstance()->setBolDebugInfra(false);
//    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    $strParametros = '';
    if (isset($_GET['arvore'])) {
        PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
        $strParametros .= '&arvore=' . $_GET['arvore'];
    }

    $arrComandos = array();

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    switch ($_GET['acao']) {
        case 'md_lit_dados_complementares_cadastrar':
            $strTitulo = 'Dados Complementares do Interessado';

            $arrComandos[] = '<button type="button" accesskey="S" name="sbmCadastrarDadosInteressado" value="Salvar" onclick="OnSubmitForm()" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


            $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();
            $objMdLitParametrizarInteressadoDTO->retTodos(true);
            $objMdLitParametrizarInteressadoDTO->setOrdNumIdMdLitNomeFuncional(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objMdLitParametrizarInteressadoDTO->setNumIdMdLitTipoControle($_GET['id_tp_controle']);
            $objMdLitParametrizarInteressadoRN = new MdLitParametrizarInteressadoRN();
            $arrObjMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->listar($objMdLitParametrizarInteressadoDTO);
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    //Inits
    $strLinkAjaxServico = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=busca_servicos_auto_completar_todos');
    $strLinkServicoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_selecionar&tipo_selecao=2&id_object=objLupaServicos');
    $strLinkServicoNaoOutorgadaSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_selecionar&tipo_selecao=2&id_object=objLupaServicosNaoOutorga');

    $strLinkAjaxConsultarIntegracao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dado_complementar_consulta');
    $strLinkAjaxlistarDadoComplementar = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dado_complementar_listar');


    $objContatoRN = new ContatoRN();
    $objContatoDTO = new ContatoDTO();
    $objContatoDTO->retTodos();
    $objContatoDTO->setNumIdContato($_GET['id_contato']);
    $objContatoDTO->setBolExclusaoLogica(false);

    $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
    $contatoCpfCnpj = $objContatoDTO->getDblCpf() ? str_pad($objContatoDTO->getDblCpf(), 11, '0', STR_PAD_LEFT) : str_pad($objContatoDTO->getDblCnpj(), 14, '0', STR_PAD_LEFT);

    $checkedRdoOutorgadaSim = $_POST['rdoOutorgada'] == 'S' ? 'checked="checked"' : '';
    $checkedRdoOutorgadaNao = $_POST['rdoOutorgada'] == 'N' ? 'checked="checked"' : '';

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

require_once 'md_lit_dados_complementares_cadastro_css.php';

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();

require_once 'md_lit_dados_complementares_cadastro_js.php';

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>
<form id="frmMdPetIntimacaoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_tp_controle=' . $_GET['id_tp_controle'] . '&id_contato=' . $_GET['id_contato']) ?>"
      autocomplete="false">
    <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

    <?php foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) { ?>

        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CNPJ_CPF
            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N') { ?>
            <label><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>: <?= $objContatoDTO->getStrNome() . " - " . InfraUtil::formatarCpfCnpj($contatoCpfCnpj) ?></label>
            <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img
                src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda"
                id="imgAjudaCpf" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                class="infraImg"/> <? } ?>
            <br> <br>
        <?php } //endif CNPJ/CPF ?>

        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$OUTORGA) { ?>
            <?php
            $readonlyRdoOutorgada = null;
            if ($objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'N') {
                $readonlyRdoOutorgada = 'disabled="disabled"';
                $checkedRdoOutorgadaSim = 'checked="checked"';
            }
            ?>
            <label id="lblOutorga" <? if ($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'N' ? 'Outorgada' : $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:</label>&nbsp;
            <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <img border="0" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                         class="infraImgModulo"/>
                </a>
            <? } ?> <br>
            <div id="divOptOutorgada" class="infraDivRadio">
                <label class="infraLabelRadio"><input type="radio"
                                                      name="rdoOutorgada" <?= $readonlyRdoOutorgada ?> <?= $checkedRdoOutorgadaSim ?>
                                                      id="optOutorgadaSim" onclick="outorgada(this)" value="S"
                                                      class="infraRadio form-control"
                                                      tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>Sim</label>
                <label class="infraLabelRadio"><input type="radio"
                                                      name="rdoOutorgada" <?= $readonlyRdoOutorgada ?> <?= $checkedRdoOutorgadaNao ?>
                                                      id="optOutorgadaNao" onclick="outorgada(this)" value="N"
                                                      class="infraRadio form-control"
                                                      tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" <?= $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ? 'onchange="consultarNumero()"' : '' ?>
                                                      campo-mapeado="<?= $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() ?>"/>Não</label>
                <br/>
            </div>
            <? break; ?>
        <?php } //endifOutorga ?>

    <?php } //endForeach ?>
    <div id="outorgada">
        <br>
        <br>
        <?php
        $fieldsSetTagAberto = false;
        foreach ($arrObjMdLitParametrizarInteressadoDTO

        as $objMdLitParametrizarInteressadoDTO){ ?>


        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CNPJ_CPF
        && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
        && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>


        <?php if (!$fieldsSetTagAberto){
        $fieldsSetTagAberto = true; ?>
        <div>
            <div class="rowFieldSet">
                <fieldset id="fldInfoComplementares" class="form-control">
                    <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero"> Informações
                        Complementares
                    </legend>
                    <div class="row linha">

                        <? } ?>
                        <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10">
                            <label><?= trim($objMdLitParametrizarInteressadoDTO->getStrLabelCampo()) ?>: <?= $objContatoDTO->getStrNome() . " - " . InfraUtil::formatarCpfCnpj($contatoCpfCnpj) ?></label>
                            <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <img border="0"
                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                         class="infraImgModulo"/>
                                </a>
                            <? } ?>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A"
                                    name="sbmConsultarNumero" class="infraButton" value="Consultar"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                        </div>
                    </div>

                    <?php } //endif CNPJ/CPF
                    ?>

                    <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>

                    <?php if (!$fieldsSetTagAberto){
                    $fieldsSetTagAberto = true; ?>
                    <div class="linha">
                        <div class="rowFieldSet">
                            <fieldset id="fldInfoComplementares" class="form-control">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero">
                                        Informações Complementares
                                    </legend>
                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                        <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()"
                                                accesskey="A" name="sbmConsultarNumero" class="infraButton"
                                                value="Consultar"
                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                            Consultar
                                        </button>
                                    </div>
                                </div>
                                <? } ?>

                                <div class="row linha" id="divNumero">
                                    <div class="col-sm-8 col-md-7 col-lg-5 col-xl-5">
                                        <label id="lblNumero" for="txtNumero"
                                               accesskey="E" <? if ($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?> ><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:</label>
                                        <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>

                                            <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                <img border="0"
                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                     class="infraImgModulo"/>
                                            </a>
                                        <? } ?>
                                        <div>
                                            <input type="text" id="txtNumero" name="txtNumero"
                                                   class="infraText form-control"
                                                   onkeypress="return infraMascaraTexto(this,event);"
                                                   onchange="desabilitarCampos(this)"
                                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                        </div>
                                    </div>
                                </div>

                                <? continue; ?>
                                <?php } //endif$NUMERO
                                ?>
                                <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>

                                <?php if (!$fieldsSetTagAberto){
                                $fieldsSetTagAberto = true; ?>
                                <div class="linha">
                                    <div class="rowFieldSet">
                                        <fieldset id="fldInfoComplementares" class="form-control">
                                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero">
                                                Informações Complementares
                                            </legend>
                                            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                                <button type="button" id="sbmConsultarNumero"
                                                        onclick="consultarNumero()" accesskey="A"
                                                        name="sbmConsultarNumero" class="infraButton" value="Consultar"
                                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                    Consultar
                                                </button>
                                            </div>
                                            <? } ?>

                                            <div id="divServicos">
                                                <div class="row">
                                                    <div class="col-sm-8 col-md-7 col-lg-5 col-xl-5">
                                                        <label id="lblServicos" for="txtServicos" accesskey="I"
                                                               class="infraLabelOpcional"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:</label>
                                                        <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                            <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                                <img border="0"
                                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                                     class="infraImgModulo"/>
                                                            </a>
                                                        <? } ?>
                                                        <div>
                                                            <input type="text" id="txtServicos" name="txtServicos"
                                                                   class="infraText form-control"
                                                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-10 col-xl-8">
                                                        <div class="input-group mb-3">
                                                            <select id="selServicos" name="selServicos"
                                                                    class="infraSelect form-control" multiple="multiple"
                                                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                                <?= $strItensSelServicos ?>
                                                            </select>
                                                            <div class="botoes">
                                                                <img id="imgSelecionarServicos"
                                                                     onclick="objLupaServicos.selecionar(700,500);"
                                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg"
                                                                     title="Selecionar Serviços"
                                                                     alt="Selecionar Serviços" class="infraImg"
                                                                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/><br>
                                                                <img id="imgRemoverServicos"
                                                                     onclick="objLupaServicos.remover();"
                                                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/remover.svg"
                                                                     alt="Remover Serviços" title="Remover Serviços"
                                                                     class="infraImg"
                                                                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <? continue; ?>
                                            <?php } //endif$SERVICO
                                            ?>

                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>

                                            <?php if (!$fieldsSetTagAberto){
                                            $fieldsSetTagAberto = true; ?>
                                            <div class="linha">
                                                <div class="rowFieldSet">
                                                    <div id="fldInfoComplementares">
                                                        <legend class="infraLegend" class="infraLabelObrigatorio"
                                                                id="legendNumero"> Informações Complementares
                                                        </legend>
                                                        <button type="button" id="sbmConsultarNumero"
                                                                onclick="consultarNumero()" accesskey="A"
                                                                name="sbmConsultarNumero" class="infraButton"
                                                                value="Consultar"
                                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                            Consultar
                                                        </button>
                                                        <? } ?>
                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5">
                                                                <fieldset id="fldModalidadesOutorga"
                                                                          class="form-control">
                                                                    <legend id="lgdModalidadesOutorga"
                                                                            class="infraLegend" <? if ($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>
                                                                        &nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>
                                                                        &nbsp;
                                                                        <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                                            <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                                                                                 name="ajuda"
                                                                                 id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                                 class="infraImg"/>
                                                                        <? } ?>
                                                                    </legend>
                                                                    <?php echo MdLitModalidadeINT::montarCheckboxModalidade($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), 'onchange="desabilitarTxtNumero(document.getElementById(\'fldModalidadesOutorga\'))"') ?>
                                                                </fieldset>
                                                            </div>

                                                            <? continue; ?>
                                                            <?php } //endif$MODALIDADE
                                                            ?>

                                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>

                                                            <?php if (!$fieldsSetTagAberto){
                                                            $fieldsSetTagAberto = true; ?>
                                                            <div>
                                                                <div class="rowFieldSet">
                                                                    <div id="fldInfoComplementares"
                                                                         class="form-control">
                                                                        <legend class="infraLegend"
                                                                                class="infraLabelObrigatorio"
                                                                                id="legendNumero"> Informações
                                                                            Complementares
                                                                        </legend>
                                                                        <button type="button" id="sbmConsultarNumero"
                                                                                onclick="consultarNumero()"
                                                                                accesskey="A" name="sbmConsultarNumero"
                                                                                class="infraButton" value="Consultar"
                                                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                                            Consultar
                                                                        </button>
                                                                        <? } ?>
                                                                        <div class="col-sm-6 col-md-6 col-lg-5 col-xl-3">
                                                                            <fieldset id="fldAbrangencia"
                                                                                      class="form-control">
                                                                                <legend id="lgdAbrangencia"
                                                                                        class="infraLegend" <? if ($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>
                                                                                    &nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>
                                                                                    &nbsp;
                                                                                    <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                                                                                             name="ajuda"
                                                                                             id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                                             class="infraImg"/>
                                                                                    <? } ?>
                                                                                </legend>
                                                                                <?php echo MdLitAdmTipoOutorINT::montarCheckboxTipoOutorga($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), 'onchange="desabilitarTxtNumero(document.getElementById(\'fldAbrangencia\'))"') ?>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                    <? continue; ?>
                                                                    <?php } //endif$TIPO_OUTORGA
                                                                    ?>

                                                                    <?php } //endforeach fieldset Informações Complementares?>

                                                                    <?php if ($fieldsSetTagAberto){ ?>
                                        </fieldset>
                                        <? } ?>

                                        <div id="dvDadosComplementaresConsulta">
                                            <table id="tblDadosComplementaresConsultas" width="100%" class="infraTable">
                                                <tr>
                                                    <th width="1%" class="infraTh" style="text-align:center"></th>
                                                    <th class="infraTh" style="display: none">ID Serviços</th>
                                                    <th class="infraTh" style="display: none">ID Modalidades</th>
                                                    <th class="infraTh" style="display: none">ID Tipo de Outorga</th>
                                                    <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) { ?>

                                                        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S') { ?>
                                                            <th class="infraTh"
                                                                width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                                            <? continue;
                                                        } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO) { ?>
                                                            <th class="infraTh" style="display: none">Número</th>
                                                            <? continue;
                                                        } ?>

                                                        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S') { ?>
                                                            <th class="infraTh"
                                                                width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                                            <? continue;
                                                        } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO) { ?>
                                                            <th class="infraTh" style="display: none">Serviço</th>
                                                            <? continue;
                                                        } ?>

                                                        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S') { ?>
                                                            <th class="infraTh"
                                                                width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                                            <? continue;
                                                        } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE) { ?>
                                                            <th class="infraTh" style="display: none">Modalidade de
                                                                Outorga
                                                            </th>
                                                            <? continue;
                                                        } ?>

                                                        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S') { ?>
                                                            <th class="infraTh"
                                                                width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                                            <? continue;
                                                        } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA) { ?>
                                                            <th class="infraTh" style="display: none">Tipo de Outorga
                                                            </th>
                                                            <? continue;
                                                        } ?>

                                                    <? } //endForeach table ?>
                                                </tr>
                                            </table>
                                        </div>

                                        <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) { ?>
                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N') { ?>

                                                <div id="divNumero" class="infraAreaDados">
                                                    <label id="lblNumero" for="txtNumero" accesskey="E"
                                                           class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S' ? 'infraLabelObrigatorio' : 'infraLabelOpcional' ?>"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:</label>
                                                    <input type="text" id="txtNumero" name="txtNumero" class="infraText"
                                                           onkeypress="return infraMascaraTexto(this,event, <?= $objMdLitParametrizarInteressadoDTO->getNumTamanho() ?> );"
                                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                                    <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                        <img
                                                                src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                                                                name="ajuda"
                                                                id="imgAjudaNumero" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                class="infraImg"/> <? } ?>
                                                    <br>
                                                </div>

                                                <? continue; ?>
                                            <?php } //endif$NUMERO ?>
                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N') { ?>

                                                <div id="divServicos" class="infraAreaDados">
                                                    <label id="lblServicos" for="txtServicos" accesskey="I"
                                                           class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S' ? 'infraLabelObrigatorio' : 'infraLabelOpcional' ?>"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:</label>
                                                    <input type="text" id="txtServicos" name="txtServicos"
                                                           class="infraText"
                                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                                    <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                        <img
                                                                src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                                                                name="ajuda"
                                                                id="imgAjudaServicos" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                class="infraImg"/> <? } ?>
                                                    <select id="selServicos" name="selServicos" class="infraSelect"
                                                            multiple="multiple"
                                                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                        <?= $strItensSelServicos ?>
                                                    </select>
                                                    <div id="divOpcoesServicos">
                                                        <img id="imgSelecionarServicos"
                                                             onclick="objLupaServicos.selecionar(700,500);"
                                                             src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/lupa.gif"
                                                             title="Selecionar Serviços" alt="Selecionar Serviços"
                                                             class="infraImg"
                                                             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/><br>
                                                        <img id="imgRemoverServicos"
                                                             onclick="objLupaServicos.remover();"
                                                             src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/remover.gif"
                                                             alt="Remover Serviços" title="Remover Serviços"
                                                             class="infraImg"
                                                             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                                    </div>
                                                </div>
                                                <? continue; ?>
                                            <?php } //endif$SERVICO ?>


                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N') { ?>

                                                <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5">
                                                    <fieldset id="fldModalidadesOutorga" class="form-control">
                                                        <legend id="lgdModalidadesOutorga"
                                                                class="infraLegend" <? if ($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>
                                                            &nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>
                                                            &nbsp;<? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                                <img
                                                                        src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                                                                        name="ajuda"
                                                                        id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                        class="infraImg"/> <? } ?></legend>
                                                        <?php echo MdLitModalidadeINT::montarCheckboxModalidade($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), 'onchange="desabilitarTxtNumero(document.getElementById(\'fldModalidadesOutorga\'))"') ?>
                                                    </fieldset>
                                                </div>

                                                <? continue; ?>
                                            <?php } //endif$MODALIDADE ?>

                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N') { ?>

                                                <div class="col-sm-6 col-md-6 col-lg-5 col-xl-3">
                                                    <fieldset id="fldAbrangencia" class="form-control">
                                                        <legend id="lgdAbrangencia"
                                                                class="infraLegend" <? if ($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>
                                                            &nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>
                                                            &nbsp;<? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                                <img
                                                                        src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                                                                        name="ajuda"
                                                                        id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                        class="infraImg"/> <? } ?></legend>
                                                        <?php echo MdLitAdmTipoOutorINT::montarCheckboxTipoOutorga($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), 'onchange="desabilitarTxtNumero(document.getElementById(\'fldAbrangencia\'))"') ?>
                                                    </fieldset>
                                                </div>


                                                <? continue; ?>
                                            <?php } //endif$TIPO_OUTORGA ?>

                                        <?php } //endforeach getStrSinCampoMapeado não mapeado?>


                                        <div id="btnSbmGrarusuario">
                                            <button type="button" id="sbmGravarUsuario" accesskey="A"
                                                    name="sbmGravarUsuario" class="infraButton" value="Adicionar"
                                                    onclick="adicionarDadosComplementares()"
                                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                Adicionar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        </div><? //enddiv#outorgada ?>
                        <br>
                        <div id="naoOutorgada">
                            <div class="linha">
                                <div class="rowFieldSet">
                                    <fieldset id="fldInfoComplementares" class="form-control">
                                        <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero">
                                            Informações Complementares
                                        </legend>
                                        <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) { ?>
                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CNPJ_CPF
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S') { ?>
                                                <label><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>: <?= $objContatoDTO->getStrNome() . " - " . InfraUtil::formatarCpfCnpj($contatoCpfCnpj) ?></label>
                                                <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                                                        name="ajuda" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(),'Ajuda') ?> />

                                                <? } ?>

                                                <br> <br>

                                                <div id="dvDadosComplementaresConsultaNaoOutorgado">
                                                    <table id="tblDadosComplementaresConsultasNaoOutorgado" width="100%"
                                                           class="infraTable">
                                                        <tr>
                                                            <th width="1%" class="infraTh"
                                                                style="text-align:center"></th>
                                                            <th class="infraTh" style="display: none">ID Serviços</th>
                                                            <th class="infraTh" style="display: none">ID Modalidades
                                                            </th>
                                                            <th class="infraTh" style="display: none">ID Tipo de
                                                                Outorga
                                                            </th>
                                                            <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) { ?>

                                                                <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                                                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S') { ?>
                                                                    <th class="infraTh"
                                                                        width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                                                    <? break;
                                                                } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO) { ?>
                                                                    <th class="infraTh" style="display: none">Número
                                                                    </th>
                                                                    <? break;
                                                                } ?>
                                                            <? } //endForeach table ?>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <br>

                                            <?php } ?>

                                            <? if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S') { ?>
                                                <div id="divServicosNaoOutorga">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-7 col-lg-5 col-xl-5">
                                                            <label id="lblServicosNaoOutorgado"
                                                                   for="txtServicosNaoOutorga" accesskey="I"
                                                                   class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S' ? 'infraLabelObrigatorio' : 'infraLabelOpcional' ?>"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:</label>
                                                            <? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>

                                                                <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                                    <img border="0"
                                                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg"
                                                                         class="infraImgModulo"/>
                                                                </a>

                                                            <? } ?>
                                                            <div>
                                                                <input type="text" id="txtServicosNaoOutorga"
                                                                       name="txtServicosNaoOutorga"
                                                                       class="infraText form-control"
                                                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-8">
                                                            <div class="input-group mb-3">
                                                                <select id="selServicosNaoOutorga"
                                                                        name="selServicosNaoOutorga"
                                                                        class="infraSelect form-control"
                                                                        multiple="multiple"
                                                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                                                    <?= $strItensSelServicos ?>
                                                                </select>
                                                                <div class="botoes">
                                                                    <img id="imgSelecionarServicosNaoOutorga"
                                                                         onclick="objLupaServicosNaoOutorga.selecionar(700,500);"
                                                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/pesquisar.svg"
                                                                         title="Selecionar Serviços"
                                                                         alt="Selecionar Serviços" class="infraImg"
                                                                         tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/><br>
                                                                    <img id="imgRemoverServicosNaoOutorga"
                                                                         onclick="objLupaServicosNaoOutorga.remover();"
                                                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/remover.svg"
                                                                         alt="Remover Serviços" title="Remover Serviços"
                                                                         class="infraImg"
                                                                         tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <? continue ?>
                                            <? } //finaliza o serviço ?>


                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S') { ?>
                                                <div class="row">
                                                <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5">
                                                    <fieldset id="fldModalidadesNaoOutorgada" class="form-control">
                                                        <legend id="lgdModalidadesNaoOutorga"
                                                                class="infraLegend" <? if ($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>
                                                            &nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>
                                                            &nbsp;<? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                                <img
                                                                        src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                                                                        name="ajuda"
                                                                        id="imgAjudaModalidadeNaoOutorga" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                        class="infraImg"/> <? } ?> </legend>
                                                        <?php echo MdLitModalidadeINT::montarCheckboxModalidade($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), '', 'rdoModalidadeNaoOutorga[]', 'chkNaoOutorgadoTipoModalidade_') ?>
                                                    </fieldset>
                                                </div>

                                                <? continue; ?>
                                            <?php } //endif$MODALIDADE Nao outorgado ?>

                                            <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S') { ?>
                                                <div class="col-sm-6 col-md-6 col-lg-5 col-xl-3">
                                                    <fieldset id="fldAbrangenciaNaoOutorgado" class="form-control">
                                                        <legend id="lgdAbrangenciaNaoOutorgado"
                                                                class="infraLegend" <? if ($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>
                                                            &nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>
                                                            &nbsp;<? if ($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?>
                                                                <img
                                                                        src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif"
                                                                        name="ajuda"
                                                                        id="imgAjudaAbrangenciaNaoOutorgado" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda(), 'Ajuda') ?>
                                                                        class="infraImg"/> <? } ?> </legend>
                                                        <?php echo MdLitAdmTipoOutorINT::montarCheckboxTipoOutorga($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), '', 'rdoAbrangenciaNaoOutorgado[]', 'chkNaoOutorgadoTipoOutorga_') ?>
                                                    </fieldset>
                                                </div>
                                                </div>

                                                <? continue; ?>
                                            <?php } //endif$TIPO_OUTORGA não outorgado ?>
                                        <? } ?>
                                    </fieldset>
                                </div>
                            </div>


                            <div class="linha">
                                <button type="button" id="AdicionarNaoOutorgado" name="AdicionarNaoOutorgado"
                                        class="infraButton" value="Adicionar"
                                        onclick="adicionarDadosComplementaresNaoOutorgado()"
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    Adicionar
                                </button>
                            </div>
                        </div>

                        <div id="dvTblDadosComplementares">
                            <table id="tblDadosComplementares" width="100%" class="infraTable">
                                <tr>
                                    <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) { ?>

                                        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S') { ?>
                                            <th class="infraTh"
                                                width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                            <? continue;
                                        } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO) { ?>
                                            <th class="infraTh" style="display: none">Número</th>
                                            <? continue;
                                        } ?>

                                        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S') { ?>
                                            <th class="infraTh"
                                                width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                            <? continue;
                                        } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO) { ?>
                                            <th class="infraTh" style="display: none">Serviço</th>
                                            <? continue;
                                        } ?>

                                        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S') { ?>
                                            <th class="infraTh"
                                                width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                            <? continue;
                                        } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE) { ?>
                                            <th class="infraTh" style="display: none">Modalidade de Outorga</th>
                                            <? continue;
                                        } ?>

                                        <?php if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S') { ?>
                                            <th class="infraTh"
                                                width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?></th>
                                            <? continue;
                                        } elseif ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA) { ?>
                                            <th class="infraTh" style="display: none">Tipo de Outorga</th>
                                            <? continue;
                                        } ?>
                                    <? } //endForeach table ?>
                                    <th class="infraTh" style="display: none">Estados</th>
                                    <th class="infraTh" style="display: none">Cidades</th>
                                    <th class="infraTh" style="display: none">ID Serviços</th>
                                    <th class="infraTh" style="display: none">ID Modalidades</th>
                                    <th class="infraTh" style="display: none">ID Tipo de outorga</th>
                                    <th class="infraTh" style="display: none">ID Estados</th>
                                    <th class="infraTh" style="display: none">ID Cidades</th>
                                    <th class="infraTh" style="display: none">ID Contato</th>
                                    <th class="infraTh" style="display: none">outorga</th>
                                    <th class="infraTh" style="display: none">ID Numero interessado</th>
                                    <th class="infraTh" style="display: none">Quantidade Lancamento</th>
                                    <th class="infraTh" width="1%">Ações</th>
                                </tr>
                            </table>
                        </div>
                        <input type="hidden" id="hdnIdMdLitDadoInteressadoNaoOutorgado"
                               name="hdnIdMdLitDadoInteressadoNaoOutorgado" value=""/>
                        <input type="hidden" id="hdnNumeroNaoOutorgado" name="hdnNumeroNaoOutorgado" value=""/>
                        <input type="hidden" id="hdnIdContato" name="hdnIdContato" class="infraText"
                               value="<?= $objContatoDTO->getNumIdContato() ?>"/>
                        <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" class="infraText"
                               value="<?= $_GET['id_procedimento'] ?>"/>
                        <input type="hidden" id="hdnIdMdLitControle" name="hdnIdMdLitControle" class="infraText"
                               value="<?= $_GET['id_md_lit_controle'] ?>"/>
                        <input type="hidden" id="hdnIdMdLitTipoControle" name="hdnIdMdLitTipoControle" class="infraText"
                               value="<?= $_GET['id_tp_controle'] ?>"/>
                        <input type="hidden" id="hdnCpfCnpj" name="hdnCpfCnpj" class="infraText"
                               value="<?= $contatoCpfCnpj ?>"/>
                        <input type="hidden" id="hdnIdServicos" name="hdnIdServicos" class="infraText" value=""/>
                        <input type="hidden" id="hdnServicos" name="hdnServicos" value="<?= $_POST['hdnServicos'] ?>"/>
                        <input type="hidden" id="hdnIdServicosNaoOutorga" name="hdnIdServicosNaoOutorga"
                               class="infraText" value=""/>
                        <input type="hidden" id="hdnServicosNaoOutorga" name="hdnServicosNaoOutorga"
                               value="<?= $_POST['hdnServicosNaoOutorga'] ?>"/>
                        <input type="hidden" id="hdnListaDadosComplementares" name="hdnListaDadosComplementares"
                               value="<?= $_POST['hdnListaDadosComplementares'] ?>"/>
                        <input type="hidden" id="hdnListaDadosComplementaresConsultas"
                               name="hdnListaDadosComplementaresConsultas"
                               value="<?= $_POST['hdnListaDadosComplementaresConsultas'] ?>"/>
                        <input type="hidden" id="hdnListaDadosComplementaresConsultasNaoOutorgado"
                               name="hdnListaDadosComplementaresConsultasNaoOutorgado"
                               value="<?= $_POST['hdnListaDadosComplementaresConsultasNaoOutorgado'] ?>"/>

                        <? PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
