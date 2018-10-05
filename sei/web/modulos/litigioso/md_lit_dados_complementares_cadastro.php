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

    $strLinkAjaxEstado = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=busca_estados_auto_completar_todos');
    $strLinkEstadoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=uf_selecionar&tipo_selecao=2&id_object=objLupaEstado');

    $strLinkAjaxCidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=busca_cidades_auto_completar_todos');
    $strLinkCidadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cidade_selecionar&tipo_selecao=2&id_object=objLupaCidade');

    $strLinkAjaxConsultarIntegracao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dado_complementar_consulta');
    $strLinkAjaxlistarDadoComplementar = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dado_complementar_listar');

    $strLinkEstadoSelecaoNaoOutorgado = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=uf_selecionar&tipo_selecao=2&id_object=objLupaEstadoNaoOutorgado');
    $strLinkCidadeSelecaoNaoOutorgado = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cidade_selecionar&tipo_selecao=2&id_object=objLupaCidadeNaoOutorgado');

    $objContatoRN = new ContatoRN();
    $objContatoDTO = new ContatoDTO();
    $objContatoDTO->retTodos();
    $objContatoDTO->setNumIdContato($_GET['id_contato']);

    $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
    $contatoCpfCnpj = $objContatoDTO->getDblCpf() ? $objContatoDTO->getDblCpf() : $objContatoDTO->getDblCnpj();

    $checkedRdoOutorgadaSim = $_POST['rdoOutorgada'] == 'S'? 'checked="checked"': '';
    $checkedRdoOutorgadaNao = $_POST['rdoOutorgada'] == 'N'? 'checked="checked"': '';

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}
//echo "<pre>";print_r($arrObjMdLitParametrizarInteressadoDTO);exit;
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
    <form id="frmMdPetIntimacaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_tp_controle=' .$_GET['id_tp_controle']. '&id_contato=' .$_GET['id_contato']) ?>" autocomplete = "false">
        <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            PaginaSEI::getInstance()->abrirAreaDados(null); ?>

        <?php foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>

        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CNPJ_CPF
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                        && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N'){ ?>
                <label><?=$objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:  <?=$objContatoDTO->getStrNome()." - ".InfraUtil::formatarCpfCnpj($contatoCpfCnpj) ?></label>
                <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaCpf" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                <br> <br>
            <?php } //endif CNPJ/CPF ?>

            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$OUTORGA){ ?>
                <?php
                $readonlyRdoOutorgada = null;
                if($objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'N'){
                    $readonlyRdoOutorgada = 'disabled="disabled"';
                    $checkedRdoOutorgadaSim = 'checked="checked"';
                }
                ?>
                <label id="lblOutorga" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'N'?'Outorgada' :$objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>&nbsp;
                <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaOutorgada" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> <br>
                <div id="divOptOutorgada" class="infraDivRadio">
                    <label class="infraLabelRadio"><input type="radio" name="rdoOutorgada" <?=$readonlyRdoOutorgada?> <?=$checkedRdoOutorgadaSim ?>  id="optOutorgadaSim" onclick="outorgada(this)" value="S" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />Sim</label>
                    <label class="infraLabelRadio"><input type="radio" name="rdoOutorgada"  <?=$readonlyRdoOutorgada?> <?=$checkedRdoOutorgadaNao ?> id="optOutorgadaNao" onclick="outorgada(this)" value="N" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?= $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ? 'onchange="consultarNumero()"': '' ?> campo-mapeado="<?=$objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado()?>" />Não</label> <br/>
                </div>
                <? break; ?>
            <?php } //endifOutorga ?>

        <?php } //endForeach ?>
        <div id="outorgada">

            <?php
            $fieldsSetTagAberto = false;
            foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>


            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CNPJ_CPF
                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>


                <?php if(!$fieldsSetTagAberto){
                    $fieldsSetTagAberto = true;?>
                    <fieldset id="fldInfoComplementares">
                        <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informações Complementares </legend>
                        <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            Consultar
                        </button>
                <?}?>

                <label><?=$objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:  <?=$objContatoDTO->getStrNome()." - ".InfraUtil::formatarCpfCnpj($contatoCpfCnpj) ?></label>
                <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaCpf" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                <br> <br>
            <?php } //endif CNPJ/CPF ?>

                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>

                    <?php if(!$fieldsSetTagAberto){
                        $fieldsSetTagAberto = true;?>
                        <fieldset id="fldInfoComplementares">
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informações Complementares </legend>
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                    <?}?>

                    <div id="divNumero" class="infraAreaDados" >
                        <label id="lblNumero" for="txtNumero" accesskey="E" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?> ><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>: </label>
                        <input type="text" id="txtNumero" name="txtNumero" class="infraText" onkeypress="return infraMascaraTexto(this,event);" onchange="desabilitarCampos(this)" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaNumero" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <br>
                    </div>

                    <? continue; ?>
                <?php } //endif$NUMERO ?>
                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>

                    <?php if(!$fieldsSetTagAberto){
                        $fieldsSetTagAberto = true;?>
                        <fieldset id="fldInfoComplementares">
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informações Complementares </legend>
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                    <?}?>

                    <div id="divServicos" class="infraAreaDados" style="height:11em;">
                        <label id="lblServicos" for="txtServicos" accesskey="I" class="infraLabelOpcional"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                        <input type="text" id="txtServicos" name="txtServicos" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaServicos" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <select id="selServicos" name="selServicos" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                            <?=$strItensSelServicos?>
                        </select>
                        <div id="divOpcoesServicos">
                            <img id="imgSelecionarServicos" onclick="objLupaServicos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Serviços" alt="Selecionar Serviços" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                            <img id="imgRemoverServicos" onclick="objLupaServicos.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Serviços" title="Remover Serviços" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                        </div>
                    </div>
                    <? continue; ?>
                <?php } //endif$SERVICO ?>

                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>

                    <?php if(!$fieldsSetTagAberto){
                        $fieldsSetTagAberto = true;?>
                        <fieldset id="fldInfoComplementares">
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informações Complementares </legend>
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                    <?}?>
                    <fieldset id="fldModalidadesOutorga">
                        <legend id="lgdModalidadesOutorga" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?><? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?></legend>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optAutorizacao" name="rdoOutorga[]" onchange="desabilitarTxtNumero(document.getElementById('fldModalidadesOutorga'))" value="1" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Autorização</label><br>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optConsessao" name="rdoOutorga[]" onchange="desabilitarTxtNumero(document.getElementById('fldModalidadesOutorga'))" value="2" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Consessão</label>
                    </fieldset>

                    <? continue; ?>
                <?php } //endif$MODALIDADE ?>

                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ABRANGENCIA
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>

                    <?php if(!$fieldsSetTagAberto){
                        $fieldsSetTagAberto = true;?>
                        <fieldset id="fldInfoComplementares">
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informações Complementares </legend>
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                    <?}?>

                    <fieldset id="fldAbrangencia">
                        <legend  id="lgdAbrangencia" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?><? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?></legend>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optNacional" name="rdoAbrangencia[]" onchange="desabilitarTxtNumero(document.getElementById('fldAbrangencia'))" value="1" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Nacional</label><br>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optRegional" name="rdoAbrangencia[]" onchange="desabilitarTxtNumero(document.getElementById('fldAbrangencia'))" value="2" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Regional</label><br>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optEstadual" name="rdoAbrangencia[]" onchange="desabilitarTxtNumero(document.getElementById('fldAbrangencia'))" value="3" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Estadual</label>
                    </fieldset>
                    <br>

                    <? continue; ?>
                <?php } //endif$ABRANGENCIA ?>
                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ESTADO
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>

                    <?php if(!$fieldsSetTagAberto){
                        $fieldsSetTagAberto = true;?>
                        <fieldset id="fldInfoComplementares">
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informações Complementares </legend>
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                    <?}?>

                    <div id="divEstado" class="infraAreaDados" style="height:11em;">
                        <label id="lblEstado" for="txtEstado" accesskey="I" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                        <input type="text" id="txtEstado" name="txtEstado" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" autocomplete="false" role="presentation" />
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaEstado" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <select id="selEstado" name="selEstado" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                            <?=$strItensSelEstado?>
                        </select>
                        <div id="divOpcoesEstado">
                            <img id="imgSelecionarEstado" onclick="objLupaEstado.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Estado" alt="Selecionar Estado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                            <img id="imgRemoverEstado" onclick="objLupaEstado.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Estado" title="Remover Estado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                        </div>
                    </div>

                    <? continue; ?>
                <?php } //endif$ESTADO ?>
                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CIDADE
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>

                    <?php if(!$fieldsSetTagAberto){
                        $fieldsSetTagAberto = true;?>
                        <fieldset id="fldInfoComplementares">
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informações Complementares </legend>
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                    <?}?>

                    <div id="divCidade" class="infraAreaDados" style="height:11em;">
                        <label id="lblCidade" for="txtCidade" accesskey="I" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                        <input type="text" id="txtCidade" name="txtCidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" autocomplete="off" />
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaCidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <select id="selCidade" name="selCidade" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                            <?=$strItensSelCidade?>
                        </select>
                        <div id="divOpcoesCidade">
                            <img id="imgSelecionarCidade" onclick="objLupaCidade.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Cidade" alt="Selecionar Cidade" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                            <img id="imgRemoverCidade" onclick="objLupaCidade.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Cidade" title="Remover Cidade" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                        </div>
                    </div>

                    <? continue; ?>
                <?php } //endif$CIDADE ?>

            <?php } //endforeach fieldset Informações Complementares?>

            <?php if($fieldsSetTagAberto){?>
                </fieldset>
            <?}?>

            <div id="dvDadosComplementaresConsulta">
                <table id="tblDadosComplementaresConsultas" width="100%" class="infraTable">
                    <tr>
                        <th width="1%" class="infraTh" style="text-align:center"></th>
                        <th class="infraTh" style="display: none">ID Serviços</th>
                        <th class="infraTh" style="display: none">ID Modalidades</th>
                        <th class="infraTh" style="display: none">ID Abrangencias</th>
                        <th class="infraTh" style="display: none">ID Estados</th>
                        <th class="infraTh" style="display: none">ID Cidades</th>
                        <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>

                            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>
                                <th class="infraTh" width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO){?>
                                <th class="infraTh" style="display: none">Número</th>
                                <? continue;} ?>

                            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>
                                <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO){?>
                                <th class="infraTh" style="display: none">Serviço</th>
                                <? continue;} ?>

                            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>
                                <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE){?>
                                <th class="infraTh" style="display: none">Modalidade de Outorga</th>
                                <? continue;} ?>

                            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ABRANGENCIA
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>
                                <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ABRANGENCIA){?>
                                <th class="infraTh" style="display: none">Abrangências</th>
                                <? continue;} ?>
                        <? } //endForeach table ?>
                    </tr>
                </table>
            </div>

            <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>
                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N' ){ ?>

                    <div id="divNumero" class="infraAreaDados">
                        <label id="lblNumero" for="txtNumero" accesskey="E" class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S'? 'infraLabelObrigatorio':'infraLabelOpcional'  ?>" ><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>: </label>
                        <input type="text" id="txtNumero" name="txtNumero" class="infraText"  onkeypress="return infraMascaraTexto(this,event, <?= $objMdLitParametrizarInteressadoDTO->getNumTamanho()?> );" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaNumero" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <br>
                    </div>

                    <? continue; ?>
                <?php } //endif$NUMERO ?>
                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N' ){ ?>

                    <div id="divServicos" class="infraAreaDados" style="height:11em;">
                        <label id="lblServicos" for="txtServicos" accesskey="I" class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S'? 'infraLabelObrigatorio':'infraLabelOpcional'  ?>"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                        <input type="text" id="txtServicos" name="txtServicos" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaServicos" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <select id="selServicos" name="selServicos" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                            <?=$strItensSelServicos?>
                        </select>
                        <div id="divOpcoesServicos">
                            <img id="imgSelecionarServicos" onclick="objLupaServicos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Serviços" alt="Selecionar Serviços" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                            <img id="imgRemoverServicos" onclick="objLupaServicos.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Serviços" title="Remover Serviços" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                        </div>
                    </div>
                    <? continue; ?>
                <?php } //endif$SERVICO ?>

                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N' ){ ?>

                    <fieldset id="fldModalidadesOutorga">
                        <legend id="lgdModalidadesOutorga" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?><? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> </legend>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optAutorizacao" name="rdoOutorga[]" value="1" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Autorização</label><br>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optConsessao" name="rdoOutorga[]" value="2" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Consessão</label>
                    </fieldset>

                    <? continue; ?>
                <?php } //endif$MODALIDADE ?>

                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ABRANGENCIA
                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N' ){ ?>

                    <fieldset id="fldAbrangencia">
                        <legend id="lgdAbrangencia" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?><? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaAbrangencia" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> </legend>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optNacional" name="rdoAbrangencia[]" value="1" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Nacional</label><br>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optRegional" name="rdoAbrangencia[]" value="2" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Regional</label><br>
                        <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optEstadual" name="rdoAbrangencia[]" value="3" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Estadual</label>
                    </fieldset>
                    <br>

                    <? continue; ?>
                <?php } //endif$ABRANGENCIA ?>
                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ESTADO
                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N' ){ ?>


                    <div id="divEstado" class="infraAreaDados" style="height:11em;">
                        <label id="lblEstado" for="txtEstado" accesskey="I" class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S'? 'infraLabelObrigatorio':'infraLabelOpcional'  ?>" ><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                        <input type="text" id="txtEstado" name="txtEstado" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" autocomplete="off" />
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaEstado" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <select id="selEstado" name="selEstado" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                            <?=$strItensSelEstado?>
                        </select>
                        <div id="divOpcoesEstado">
                            <img id="imgSelecionarEstado" onclick="objLupaEstado.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Estados" alt="Selecionar Estados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                            <img id="imgRemoverEstado" onclick="objLupaEstado.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Estados" title="Remover Estados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                        </div>
                    </div>

                    <? continue; ?>
                <?php } //endif$ESTADO ?>
                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CIDADE
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N' ){ ?>

                    <div id="divCidade" class="infraAreaDados" style="height:11em;">
                        <label id="lblCidade" for="txtCidade" accesskey="I" class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S'? 'infraLabelObrigatorio':'infraLabelOpcional'?>"  ><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                        <input type="text" id="txtCidade" name="txtCidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" autocomplete="false"  />
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaCidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <select id="selCidade" name="selCidade" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                            <?=$strItensSelCidade?>
                        </select>
                        <div id="divOpcoesCidade">
                            <img id="imgSelecionarCidade" onclick="objLupaCidade.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Cidades" alt="Selecionar Cidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                            <img id="imgRemoverCidade" onclick="objLupaCidade.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Cidades" title="Remover Cidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                        </div>
                    </div>

                    <? continue; ?>
                <?php } //endif$CIDADE ?>

            <?php } //endforeach getStrSinCampoMapeado não mapeado?>


                <button type="button" id="sbmGravarUsuario" accesskey="A" name="sbmGravarUsuario" class="infraButton" value="Adicionar" onclick="adicionarDadosComplementares()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Adicionar
                </button>
            </div><? //enddiv#outorgada ?>
            <br>
        <div id="naoOutorgada">

            <fieldset id="fldInfoComplementares">
                <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informações Complementares </legend>

                <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>


                    <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CNPJ_CPF
                        && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'){ ?>
                        <label><?=$objMdLitParametrizarInteressadoDTO->getStrLabelCampo() ?>:  <?=$objContatoDTO->getStrNome()." - ".InfraUtil::formatarCpfCnpj($contatoCpfCnpj) ?></label>
                        <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaCpf" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                        <br> <br>

                        <div id="dvDadosComplementaresConsultaNaoOutorgado">
                            <table id="tblDadosComplementaresConsultasNaoOutorgado" width="100%" class="infraTable">
                                <tr>
                                    <th width="1%" class="infraTh" style="text-align:center"></th>
                                    <th class="infraTh" style="display: none">ID Serviços</th>
                                    <th class="infraTh" style="display: none">ID Modalidades</th>
                                    <th class="infraTh" style="display: none">ID Abrangencias</th>
                                    <th class="infraTh" style="display: none">ID Estados</th>
                                    <th class="infraTh" style="display: none">ID Cidades</th>
                                    <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>

                                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>
                                            <th class="infraTh" width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                            <? break; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO){?>
                                            <th class="infraTh" style="display: none">Número</th>
                                            <? break;} ?>
                                    <? } //endForeach table ?>
                                </tr>
                            </table>
                        </div>
                        <br>
            </fieldset>

                    <?php } ?>

                    <? if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO 
                       && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'){ ?>
                        <div id="divServicosNaoOutorga" class="infraAreaDados" style="height:11em;">
                            <label id="lblServicosNaoOutorgado" for="txtServicosNaoOutorga" accesskey="I" class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S'? 'infraLabelObrigatorio':'infraLabelOpcional' ?>"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                            <input type="text" id="txtServicosNaoOutorga" name="txtServicosNaoOutorga" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                            <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaServicosNaoOutorga" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                            <select id="selServicosNaoOutorga" name="selServicosNaoOutorga" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                                <?=$strItensSelServicos?>
                            </select>
                            <div id="divOpcoesServicosNaoOutorga">
                                <img id="imgSelecionarServicosNaoOutorga" onclick="objLupaServicosNaoOutorga.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Serviços" alt="Selecionar Serviços" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                                <img id="imgRemoverServicosNaoOutorga" onclick="objLupaServicosNaoOutorga.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Serviços" title="Remover Serviços" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                            </div>
                        </div>
                        <? continue ?>
                    <? } //finaliza o serviço ?>


                    <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE 
                          && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                        <fieldset id="fldModalidadesNaoOutorgada">
                            <legend id="lgdModalidadesNaoOutorga" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?><? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaModalidadeNaoOutorga" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> </legend>
                            <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optAutorizacaoNaoOutorga" name="rdoModalidadeNaoOutorga[]" value="1" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Autorização</label><br>
                            <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optConsessaoNaoOutorga" name="rdoModalidadeNaoOutorga[]" value="2" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Consessão</label>
                        </fieldset>

                        <? continue; ?>
                    <?php } //endif$MODALIDADE Nao outorgado ?>

                    <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ABRANGENCIA
                        && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'){ ?>

                        <fieldset id="fldAbrangenciaNaoOutorgado">
                            <legend id="lgdAbrangenciaNaoOutorgado" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?><? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaAbrangenciaNaoOutorgado" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> </legend>
                            <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optNacionalNaoOutorgado" name="rdoAbrangenciaNaoOutorgado[]" value="1" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Nacional</label><br>
                            <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optRegionalNaoOutorgado" name="rdoAbrangenciaNaoOutorgado[]" value="2" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Regional</label><br>
                            <label <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> <input type="checkbox" id="optEstadualNaoOutorgado" name="rdoAbrangenciaNaoOutorgado[]" value="3" class="infraCheckbox" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/> Estadual</label>
                        </fieldset>
                        <br>

                        <? continue; ?>
                    <?php } //endif$ABRANGENCIA não outorgado ?>
                    <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ESTADO
                        && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'){ ?>


                        <div id="divEstadoNaoOutorgado" class="infraAreaDados" style="height:11em;">
                            <label id="lblEstadoNaoOutorgado" for="txtEstadoNaoOutorgado" class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S'? 'infraLabelObrigatorio':'infraLabelOpcional'  ?>" ><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                            <input type="text" id="txtEstadoNaoOutorgado" name="txtEstadoNaoOutorgado" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" autocomplete="false" />
                            <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaEstadoNaoOutorgado" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                            <select id="selEstadoNaoOutorgado" name="selEstadoNaoOutorgado" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                                <?=$strItensSelEstado?>
                            </select>
                            <div id="divOpcoesEstadoNaoOutorgado">
                                <img id="imgSelecionarEstadoNaoOutorgado" onclick="objLupaEstadoNaoOutorgado.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Estados" alt="Selecionar Estados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                                <img id="imgRemoverEstadoNaoOutorgado" onclick="objLupaEstadoNaoOutorgado.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Estados" title="Remover Estados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                            </div>
                        </div>

                        <? continue; ?>
                    <?php } //endif$ESTADO NaoOutorgado ?>
                    <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CIDADE
                        && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>

                        <div id="divCidadeNaoOutorgado" class="infraAreaDados" style="height:11em;">
                            <label id="lblCidadeNaoOutorgado" for="txtCidadeNaoOutorgado" accesskey="I" class="<? echo $objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S'? 'infraLabelObrigatorio':'infraLabelOpcional'?>"  ><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>:</label>
                            <input type="text" id="txtCidadeNaoOutorgado" name="txtCidadeNaoOutorgado" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" autocomplete="off" />
                            <? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaCidadeNaoOutorgado" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?>
                            <select id="selCidadeNaoOutorgado" name="selCidadeNaoOutorgado" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
                                <?=$strItensSelCidade?>
                            </select>
                            <div id="divOpcoesCidadeNaoOutorgado">
                                <img id="imgSelecionarCidadeNaoOutorgado" onclick="objLupaCidadeNaoOutorgado.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Cidades" alt="Selecionar Cidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                                <img id="imgRemoverCidadeNaoOutorgado" onclick="objLupaCidadeNaoOutorgado.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Cidades" title="Remover Cidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                            </div>
                        </div>

                        <? continue; ?>
                    <?php } //endif$CIDADE NaoOutorgado ?>
                <? } ?>

                <button type="button" id="AdicionarNaoOutorgado" name="AdicionarNaoOutorgado" class="infraButton" value="Adicionar" onclick="adicionarDadosComplementaresNaoOutorgado()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Adicionar
                </button>
        </div>

        <div id="dvTblDadosComplementares">
            <table id="tblDadosComplementares" width="100%" class="infraTable">
                <tr>
                    <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO){?>
                            <th class="infraTh" style="display: none">Número</th>
                            <? continue;} ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO){?>
                            <th class="infraTh" style="display: none">Serviço</th>
                            <? continue;} ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE){?>
                            <th class="infraTh" style="display: none">Modalidade de Outorga</th>
                            <? continue;} ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ABRANGENCIA
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ABRANGENCIA){?>
                            <th class="infraTh" style="display: none">Abrangências</th>
                            <? continue;} ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ESTADO
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$ESTADO){?>
                            <th class="infraTh" style="display: none">Estados</th>
                            <? continue;} ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CIDADE
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CIDADE){?>
                            <th class="infraTh" style="display: none">Cidades</th>
                            <? continue;} ?>
                    <? } //endForeach table ?>
                    <th class="infraTh" style="display: none">ID Serviços</th>
                    <th class="infraTh" style="display: none">ID Modalidades</th>
                    <th class="infraTh" style="display: none">ID Abrangencias</th>
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
        <input type="hidden" id="hdnIdMdLitDadoInteressadoNaoOutorgado" name="hdnIdMdLitDadoInteressadoNaoOutorgado" value="" />
        <input type="hidden" id="hdnNumeroNaoOutorgado" name="hdnNumeroNaoOutorgado" value="" />
        <input type="hidden" id="hdnIdContato" name="hdnIdContato" class="infraText" value="<?= $objContatoDTO->getNumIdContato() ?>" />
        <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" class="infraText" value="<?= $_GET['id_procedimento'] ?>" />
        <input type="hidden" id="hdnIdMdLitControle" name="hdnIdMdLitControle" class="infraText" value="<?= $_GET['id_md_lit_controle'] ?>" />
        <input type="hidden" id="hdnIdMdLitTipoControle" name="hdnIdMdLitTipoControle" class="infraText" value="<?= $_GET['id_tp_controle'] ?>" />
        <input type="hidden" id="hdnCpfCnpj" name="hdnCpfCnpj" class="infraText" value="<?= $contatoCpfCnpj ?>" />
        <input type="hidden" id="hdnIdServicos" name="hdnIdServicos" class="infraText" value="" />
        <input type="hidden" id="hdnServicos" name="hdnServicos" value="<?=$_POST['hdnServicos']?>" />
        <input type="hidden" id="hdnIdServicosNaoOutorga" name="hdnIdServicosNaoOutorga" class="infraText" value="" />
        <input type="hidden" id="hdnServicosNaoOutorga" name="hdnServicosNaoOutorga" value="<?=$_POST['hdnServicosNaoOutorga']?>" />
        <input type="hidden" id="hdnIdEstado" name="hdnIdEstado" class="infraText" value="" />
        <input type="hidden" id="hdnEstado" name="hdnEstado" value="<?=$_POST['hdnEstado']?>" />
        <input type="hidden" id="hdnEstadoNaoOutorgado" name="hdnEstadoNaoOutorgado" value="<?=$_POST['hdnEstadoNaoOutorgado']?>" />
        <input type="hidden" id="hdnIdEstadoNaoOutorgado" name="hdnIdEstadoNaoOutorgado" value="" />
        <input type="hidden" id="hdnIdCidade" name="hdnIdCidade" class="infraText" value="" />
        <input type="hidden" id="hdnCidade" name="hdnCidade" value="<?=$_POST['hdnCidade']?>" />
        <input type="hidden" id="hdnCidadeNaoOutorgado" name="hdnCidadeNaoOutorgado" value="<?=$_POST['hdnCidadeNaoOutorgado']?>" />
        <input type="hidden" id="hdnIdCidadeNaoOutorgada" name="hdnIdCidadeNaoOutorgada" value="" />
        <input type="hidden" id="hdnListaDadosComplementares" name="hdnListaDadosComplementares" value="<?=$_POST['hdnListaDadosComplementares']?>" />
        <input type="hidden" id="hdnListaDadosComplementaresConsultas" name="hdnListaDadosComplementaresConsultas" value="<?=$_POST['hdnListaDadosComplementaresConsultas']?>" />
        <input type="hidden" id="hdnListaDadosComplementaresConsultasNaoOutorgado" name="hdnListaDadosComplementaresConsultasNaoOutorgado" value="<?=$_POST['hdnListaDadosComplementaresConsultasNaoOutorgado']?>" />

        <? PaginaSEI::getInstance()->fecharAreaDados(); ?>
        <? PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>

    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
