<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 14/03/2017 - criado por pedro.cast
 *
 * Vers�o do Gerador de C�digo: 1.40.0
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
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
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

    $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
    $contatoCpfCnpj = $objContatoDTO->getDblCpf() ? str_pad($objContatoDTO->getDblCpf(), 11, '0', STR_PAD_LEFT) : str_pad($objContatoDTO->getDblCnpj(), 14, '0', STR_PAD_LEFT);

    $checkedRdoOutorgadaSim = $_POST['rdoOutorgada'] == 'S' ? 'checked="checked"': '';
    $checkedRdoOutorgadaNao = $_POST['rdoOutorgada'] == 'N' ? 'checked="checked"': '';

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
                    <label class="infraLabelRadio"><input type="radio" name="rdoOutorgada"  <?=$readonlyRdoOutorgada?> <?=$checkedRdoOutorgadaNao ?> id="optOutorgadaNao" onclick="outorgada(this)" value="N" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?= $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ? 'onchange="consultarNumero()"': '' ?> campo-mapeado="<?=$objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado()?>" />N�o</label> <br/>
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
                        <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informa��es Complementares </legend>
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
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informa��es Complementares </legend>
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
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informa��es Complementares </legend>
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
                            <img id="imgSelecionarServicos" onclick="objLupaServicos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Servi�os" alt="Selecionar Servi�os" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                            <img id="imgRemoverServicos" onclick="objLupaServicos.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Servi�os" title="Remover Servi�os" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
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
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informa��es Complementares </legend>
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                    <?}?>
                    <fieldset id="fldModalidadesOutorga">
                        <legend id="lgdModalidadesOutorga" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>> &nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?> &nbsp;<? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?></legend>
                        <?php echo MdLitModalidadeINT::montarCheckboxModalidade($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), 'onchange="desabilitarTxtNumero(document.getElementById(\'fldModalidadesOutorga\'))"') ?>
                    </fieldset>

                    <? continue; ?>
                <?php } //endif$MODALIDADE ?>

                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                    && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                    && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>

                    <?php if(!$fieldsSetTagAberto){
                        $fieldsSetTagAberto = true;?>
                        <fieldset id="fldInfoComplementares">
                            <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informa��es Complementares </legend>
                            <button type="button" id="sbmConsultarNumero" onclick="consultarNumero()" accesskey="A" name="sbmConsultarNumero" class="infraButton" value="Consultar" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                Consultar
                            </button>
                    <?}?>

                    <fieldset id="fldAbrangencia">
                        <legend  id="lgdAbrangencia" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>&nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>&nbsp;<? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?></legend>
                        <?php echo MdLitAdmTipoOutorINT::montarCheckboxTipoOutorga($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), 'onchange="desabilitarTxtNumero(document.getElementById(\'fldAbrangencia\'))"') ?>
                    </fieldset>
                    <br>

                    <? continue; ?>
                <?php } //endif$TIPO_OUTORGA ?>

            <?php } //endforeach fieldset Informa��es Complementares?>

            <?php if($fieldsSetTagAberto){?>
                </fieldset>
            <?}?>

            <div id="dvDadosComplementaresConsulta">
                <table id="tblDadosComplementaresConsultas" width="100%" class="infraTable">
                    <tr>
                        <th width="1%" class="infraTh" style="text-align:center"></th>
                        <th class="infraTh" style="display: none">ID Servi�os</th>
                        <th class="infraTh" style="display: none">ID Modalidades</th>
                        <th class="infraTh" style="display: none">ID Tipo de Outorga</th>
                        <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>

                            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>
                                <th class="infraTh" width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO){?>
                                <th class="infraTh" style="display: none">N�mero</th>
                                <? continue;} ?>

                            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S' ){ ?>
                                <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO){?>
                                <th class="infraTh" style="display: none">Servi�o</th>
                                <? continue;} ?>

                            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>
                                <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE){?>
                                <th class="infraTh" style="display: none">Modalidade de Outorga</th>
                                <? continue;} ?>

                            <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>
                                <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA){?>
                                <th class="infraTh" style="display: none">Tipo de Outorga</th>
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
                            <img id="imgSelecionarServicos" onclick="objLupaServicos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Servi�os" alt="Selecionar Servi�os" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                            <img id="imgRemoverServicos" onclick="objLupaServicos.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Servi�os" title="Remover Servi�os" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                        </div>
                    </div>
                    <? continue; ?>
                <?php } //endif$SERVICO ?>

                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N' ){ ?>
                    <fieldset id="fldModalidadesOutorga">
                        <legend id="lgdModalidadesOutorga" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>&nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>&nbsp;<? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaModalidade" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> </legend>
                        <?php echo MdLitModalidadeINT::montarCheckboxModalidade($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio()) ?>
                    </fieldset>

                    <? continue; ?>
                <?php } //endif$MODALIDADE ?>

                <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'N' ){ ?>

                    <fieldset id="fldAbrangencia">
                        <legend id="lgdAbrangencia" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>&nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>&nbsp;<? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaAbrangencia" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> </legend>
                        <?php echo MdLitAdmTipoOutorINT::montarCheckboxTipoOutorga($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio()) ?>
                    </fieldset>
                    <br>

                    <? continue; ?>
                <?php } //endif$TIPO_OUTORGA ?>

            <?php } //endforeach getStrSinCampoMapeado n�o mapeado?>


            <div style="float: left;width: 100%;">
                <button type="button" id="sbmGravarUsuario" accesskey="A" name="sbmGravarUsuario" class="infraButton" value="Adicionar" onclick="adicionarDadosComplementares()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    Adicionar
                </button>
            </div>
            </div><? //enddiv#outorgada ?>
            <br>
        <div id="naoOutorgada">

            <fieldset id="fldInfoComplementares">
                <legend class="infraLegend" class="infraLabelObrigatorio" id="legendNumero" > Informa��es Complementares </legend>

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
                                    <th class="infraTh" style="display: none">ID Servi�os</th>
                                    <th class="infraTh" style="display: none">ID Modalidades</th>
                                    <th class="infraTh" style="display: none">ID Tipo de Outorga</th>
                                    <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>

                                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'
                                            && $objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado() == 'S'){ ?>
                                            <th class="infraTh" width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                                            <? break; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO){?>
                                            <th class="infraTh" style="display: none">N�mero</th>
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
                                <img id="imgSelecionarServicosNaoOutorga" onclick="objLupaServicosNaoOutorga.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/lupa.gif" title="Selecionar Servi�os" alt="Selecionar Servi�os" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br>
                                <img id="imgRemoverServicosNaoOutorga" onclick="objLupaServicosNaoOutorga.remover();" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif" alt="Remover Servi�os" title="Remover Servi�os" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
                            </div>
                        </div>
                        <? continue ?>
                    <? } //finaliza o servi�o ?>


                    <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE 
                          && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                        <fieldset id="fldModalidadesNaoOutorgada">
                            <legend id="lgdModalidadesNaoOutorga" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>&nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>&nbsp;<? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaModalidadeNaoOutorga" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> </legend>
                            <?php echo MdLitModalidadeINT::montarCheckboxModalidade($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), '', 'rdoModalidadeNaoOutorga[]', 'chkNaoOutorgadoTipoModalidade_') ?>
                        </fieldset>

                        <? continue; ?>
                    <?php } //endif$MODALIDADE Nao outorgado ?>

                    <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                        && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S'){ ?>

                        <fieldset id="fldAbrangenciaNaoOutorgado">
                            <legend id="lgdAbrangenciaNaoOutorgado" class="infraLegend" <? if($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio() == 'S') { ?> class="infraLabelObrigatorio" <? } ?>>&nbsp;<?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?>&nbsp;<? if($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda() != '') { ?><img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgAjudaAbrangenciaNaoOutorgado" <?= PaginaSEI::montarTitleTooltip($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()) ?> class="infraImg"/> <? } ?> </legend>
                            <?php echo MdLitAdmTipoOutorINT::montarCheckboxTipoOutorga($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio(), array(), '', 'rdoAbrangenciaNaoOutorgado[]', 'chkNaoOutorgadoTipoOutorga_') ?>
                        </fieldset>
                        <br>

                        <? continue; ?>
                    <?php } //endif$TIPO_OUTORGA n�o outorgado ?>
                <? } ?>

                <div style="float: left;width: 100%;margin: 10px 0">
                    <button type="button" id="AdicionarNaoOutorgado" name="AdicionarNaoOutorgado" class="infraButton" value="Adicionar" onclick="adicionarDadosComplementaresNaoOutorgado()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        Adicionar
                    </button>
                </div>
        </div>

        <div id="dvTblDadosComplementares">
            <table id="tblDadosComplementares" width="100%" class="infraTable">
                <tr>
                    <? foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO){ ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="5%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO){?>
                            <th class="infraTh" style="display: none">N�mero</th>
                            <? continue;} ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$SERVICO){?>
                            <th class="infraTh" style="display: none">Servi�o</th>
                            <? continue;} ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$MODALIDADE){?>
                            <th class="infraTh" style="display: none">Modalidade de Outorga</th>
                            <? continue;} ?>

                        <?php if($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA
                            && $objMdLitParametrizarInteressadoDTO->getStrSinExibe() == 'S' ){ ?>
                            <th class="infraTh" width="10%"><?= $objMdLitParametrizarInteressadoDTO->getStrLabelCampo()?></th>
                            <? continue; }elseif($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$TIPO_OUTORGA){?>
                            <th class="infraTh" style="display: none">Tipo de Outorga</th>
                            <? continue;} ?>
                    <? } //endForeach table ?>
                    <th class="infraTh" style="display: none">Estados</th>
                    <th class="infraTh" style="display: none">Cidades</th>
                    <th class="infraTh" style="display: none">ID Servi�os</th>
                    <th class="infraTh" style="display: none">ID Modalidades</th>
                    <th class="infraTh" style="display: none">ID Tipo de outorga</th>
                    <th class="infraTh" style="display: none">ID Estados</th>
                    <th class="infraTh" style="display: none">ID Cidades</th>
                    <th class="infraTh" style="display: none">ID Contato</th>
                    <th class="infraTh" style="display: none">outorga</th>
                    <th class="infraTh" style="display: none">ID Numero interessado</th>
                    <th class="infraTh" style="display: none">Quantidade Lancamento</th>
                    <th class="infraTh" width="1%">A��es</th>
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
