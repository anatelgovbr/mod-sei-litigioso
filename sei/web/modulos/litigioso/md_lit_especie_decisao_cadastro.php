<?
    /**
     * ANATEL
     *
     * 23/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST
     *
     */

    try {
        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();

        SessaoSEI::getInstance()->validarLink();

        PaginaSEI::getInstance()->verificarSelecao('md_lit_especie_decisao_selecionar');

        SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

        $objEspecieDecisaoLitigiosoDTO = new MdLitEspecieDecisaoDTO();

        $strDesabilitar = '';

        $arrComandos             = array();
        $strLinkObrigacaoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_selecionar&tipo_selecao=2&id_object=objLupaObrigacao');
        $strLinkAjaxObrigacao    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_obrigacao_auto_completar');

        switch ($_GET['acao']) {
            case 'md_lit_especie_decisao_cadastrar':

                $strTitulo = 'Nova Espécie de Decisão';

                $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEspecieDecisaoLitigioso" id="sbmCadastrarEspecieDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

                $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso(null);
                $objEspecieDecisaoLitigiosoDTO->setStrNome($_POST['txtNome']);
                $objEspecieDecisaoLitigiosoDTO->setStrSinGestaoMulta($_POST['gestaoMulta'] != null ? 'S' : 'N');
                $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoPrazo($_POST['indPrazo'] != null ? 'S' : 'N');
                $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoObrigacoes($_POST['IndObr'] != null ? 'S' : 'N');


                if (isset($_POST['sbmCadastrarEspecieDecisaoLitigioso'])) {
                    try {

                        //Set Obrigacoes
                        if ($objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                            $arrObjEspecieDecisaoObrigacaoDTO = array();
                            $arrObrigacoes                    = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnObrigacao']);

                            for ($x = 0; $x < count($arrObrigacoes); $x++) {
                                $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                                $objRelEspecieDecisaoObrigacaoDTO->setNumIdObrigacaoLitigioso($arrObrigacoes[$x]);
                                array_push($arrObjEspecieDecisaoObrigacaoDTO, $objRelEspecieDecisaoObrigacaoDTO);
                            }

                            $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoObrigacaoDTO);
                        } else {
                            $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO(array());
                        }
                        //Fim Obrigações

                        $objEspecieDecisaoLitigiosoRN  = new MdLitEspecieDecisaoRN();
                        $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->cadastrar($objEspecieDecisaoLitigiosoDTO);
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_especie_decisao_litigioso=' . $objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso() . PaginaSEI::getInstance()->montarAncora($objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso())));
                        die;
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                }
                break;

            case 'md_lit_especie_decisao_alterar':
                $strTitulo      = 'Alterar Espécie de Decisão';
                $arrComandos[]  = '<button type="submit" accesskey="S" name="sbmAlterarEspecieDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $strDesabilitar = 'disabled="disabled"';

                if (isset($_GET['id_especie_decisao_litigioso'])) {
                    $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso($_GET['id_especie_decisao_litigioso']);

                    $objEspecieDecisaoLitigiosoDTO->retTodos();
                    $objEspecieDecisaoLitigiosoRN  = new MdLitEspecieDecisaoRN();
                    $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->consultar($objEspecieDecisaoLitigiosoDTO);

                    if ($objEspecieDecisaoLitigiosoDTO == null) {
                        throw new InfraException("Registro não encontrado.");
                    }
                    if ($objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                        $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                        $objRelEspecieDecisaoObrigacaoDTO->retTodos();
                        $objRelEspecieDecisaoObrigacaoDTO->retStrNomeObrigacao();
                        $objRelEspecieDecisaoObrigacaoDTO->setNumIdEspecieDecisaoLitigioso($_GET['id_especie_decisao_litigioso']);

                        PaginaSEI::getInstance()->prepararOrdenacao($objRelEspecieDecisaoObrigacaoDTO, 'NomeObrigacao', InfraDTO::$TIPO_ORDENACAO_ASC);


                        $objRelEspecieDecisaoObrigacaoRN = new MdLitRelEspecieDecisaoObrigacaoRN();
                        $arrObrigacoes                   = $objRelEspecieDecisaoObrigacaoRN->listar($objRelEspecieDecisaoObrigacaoDTO);

                        $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObrigacoes);

                        $strItensSelObrigacoes = "";
                        for ($x = 0; $x < count($arrObrigacoes); $x++) {
                            $strItensSelObrigacoes .= "<option value='" . $arrObrigacoes[$x]->getNumIdObrigacaoLitigioso() . "'>" . $arrObrigacoes[$x]->getStrNomeObrigacao() . "</option>";
                        }
                    } else {
                        $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO(array());
                    }
                } else {
                    $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso($_POST['hdnIdEspecieDecisaoLitigioso']);
                    $objEspecieDecisaoLitigiosoDTO->setStrNome($_POST['txtNome']);
                    $objEspecieDecisaoLitigiosoDTO->setStrSinGestaoMulta($_POST['gestaoMulta'] != null ? 'S' : 'N');
                    $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoPrazo($_POST['indPrazo'] != null ? 'S' : 'N');
                    $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoObrigacoes($_POST['IndObr'] != null ? 'S' : 'N');


                    //Set Obrigacoes
                    if ($objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                        $arrObjEspecieDecisaoObrigacaoDTO = array();
                        $arrObrigacoes                    = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnObrigacao']);

                        for ($x = 0; $x < count($arrObrigacoes); $x++) {
                            $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                            $objRelEspecieDecisaoObrigacaoDTO->setNumIdObrigacaoLitigioso($arrObrigacoes[$x]);
                            array_push($arrObjEspecieDecisaoObrigacaoDTO, $objRelEspecieDecisaoObrigacaoDTO);
                        }

                        $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoObrigacaoDTO);
                    } else {
                        $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO(array());
                    }
                }

                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

                if (isset($_POST['sbmAlterarEspecieDecisaoLitigioso'])) {
                    try {
                        $objEspecieDecisaoLitigiosoRN = new MdLitEspecieDecisaoRN();

                        $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->alterar($objEspecieDecisaoLitigiosoDTO);
                        //
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso())));
                        die;
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                }
                break;

            case 'md_lit_especie_decisao_consultar':
                $strTitulo     = 'Consultar Espécie de Decisão';
                $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_especie_decisao_litigioso']))) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
                if (isset($_GET['id_especie_decisao_litigioso'])) {
                    $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso($_GET['id_especie_decisao_litigioso']);
                    $objEspecieDecisaoLitigiosoDTO->retTodos();
                    $objEspecieDecisaoLitigiosoRN  = new MdLitEspecieDecisaoRN();
                    $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->consultar($objEspecieDecisaoLitigiosoDTO);

                    if ($objEspecieDecisaoLitigiosoDTO == null) {
                        throw new InfraException("Registro não encontrado.");
                    }

                    $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                    $objRelEspecieDecisaoObrigacaoDTO->retTodos();
                    $objRelEspecieDecisaoObrigacaoDTO->retStrNomeObrigacao();
                    $objRelEspecieDecisaoObrigacaoDTO->setNumIdEspecieDecisaoLitigioso($_GET['id_especie_decisao_litigioso']);

                    PaginaSEI::getInstance()->prepararOrdenacao($objRelEspecieDecisaoObrigacaoDTO, 'NomeObrigacao', InfraDTO::$TIPO_ORDENACAO_ASC);

                    $objRelEspecieDecisaoObrigacaoRN = new MdLitRelEspecieDecisaoObrigacaoRN();
                    $arrObrigacoes                   = $objRelEspecieDecisaoObrigacaoRN->listar($objRelEspecieDecisaoObrigacaoDTO);

                    $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObrigacoes);

                    $strItensSelObrigacoes = "";
                    for ($x = 0; $x < count($arrObrigacoes); $x++) {
                        $strItensSelObrigacoes .= "<option value='" . $arrObrigacoes[$x]->getNumIdObrigacaoLitigioso() . "'>" . $arrObrigacoes[$x]->getStrNomeObrigacao() . "</option>";
                    }

                }
                break;

            default:
                throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        }


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
?>
#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:6%;width:50%;}

#fldNovaEspDecisao {height:10%; border:none; }

#txtObrigacao {width: 50%; left:0%; margin-top:2px;}
#selDescricaoObrigacao {width: 75%; left:0%; margin-top:2px; }


#imgLupaObrigacao {position:absolute;left:75.5%;top:35%;}
#imgExcluirObrigacao  {position:absolute;left:75.3%;top:55%;}

<?
    PaginaSEI::getInstance()->fecharStyle();
    PaginaSEI::getInstance()->montarJavaScript();
    PaginaSEI::getInstance()->abrirJavaScript();
?>


<?
    PaginaSEI::getInstance()->fecharJavaScript();
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmEspécie de DecisãoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('30em');
    ?>

    <fieldset id="fldNovaEspDecisao">
        <div>
            <label id="lblNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Espécie de Decisão:</label>
            <input type="text" id="txtNome" name="txtNome" class="infraText"
                   value="<?= PaginaSEI::tratarHTML($objEspecieDecisaoLitigiosoDTO->getStrNome()); ?>"
                   onkeypress="return infraMascaraTexto(this,event, 50);" maxlength="50"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        </div>
    </fieldset>
    <div style="clear:both;">&nbsp;</div>

    <!--  Fieldset Resultado da Decisão -->
    <fieldset id="fieldsetResultadoDecisao" class="infraFieldset">
        <legend class="infraLegend">&nbsp;Resultado da Decisão&nbsp;</legend>
        <div>
            <!-- Gestão de Multa  -->
            <?php $checkedGM = $objEspecieDecisaoLitigiosoDTO->getStrSinGestaoMulta() == 'S' ? 'checked=checked' : ''; ?>
            <label class="infraLabelCheckbox">
            <input <?php echo $checkedGM; ?> type="checkbox" class="infraCheckbox resultDecisao" name="gestaoMulta"
                                             id="gestaoMulta" value="S">
            Gestão de Multa</label>
            <br/>

            <!-- Indicação de Prazo -->
            <?php $checkedIP = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoPrazo() == 'S' ? 'checked=checked' : ''; ?>
            <label class="infraLabelCheckbox">
            <input <?php echo $checkedIP; ?> type="checkbox" class="infraCheckbox resultDecisao" name="indPrazo"
                                             id="indPrazo" value="S">
            Indicação de Prazo</label>
            <br/>

            <!-- Indicação de Obrigações -->
            <?php $checkedIO = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S' ? 'checked=checked' : ''; ?>
            <label class="infraLabelCheckbox">
            <input <?php echo $checkedIO; ?> type="checkbox" class="infraCheckbox resultDecisao" name="IndObr"
                                             id="IndObr" value="S" onchange="showObrigacoes();">
            Indicação de Obrigações</label>

        </div>
    </fieldset>
    <div style="clear:both;">&nbsp;</div>
    <!--  Componente de Obrigações Associadas -->
    <?php $displayObrigacoes = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S' ? 'height:11.5em; display: inherit' : 'height:11.5em; display: none'; ?>
    <div id="obrigacoesAssociadas" class="infraAreaDados" style="<?php echo $displayObrigacoes; ?>">

        <label id="lblDescricaoObrigacao" for="txtObrigacao" accesskey="q" class="infraLabelObrigatorio">Obrigações
            Associadas:</label>
        <br/>
        <input type="text" id="txtObrigacao" name="txtObrigacao" class="infraText"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        <select id="selDescricaoObrigacao" name="selDescricaoObrigacao" size="4" multiple="multiple"
                class="infraSelect">
            <?= $strItensSelObrigacoes ?>
        </select>

        <img id="imgLupaObrigacao" onclick="objLupaObrigacao.selecionar(700,500);" src="/infra_css/imagens/lupa.gif"
             alt="Localizar Obrigação Associada"
             title="Localizar Obrigação Associada" class="infraImg"/>

        <img id="imgExcluirObrigacao" onclick="objLupaObrigacao.remover();" src="/infra_css/imagens/remover.gif"
             alt="Remover Obrigações Associadas"
             title="Remover Obrigações Associadas" class="infraImg"/>

        <input type="hidden" id="hdnIdObrigacao" name="hdnIdObrigacao" value="<?= $_POST['hdnIdObrigacao'] ?>"/>
        <input type="hidden" id="hdnObrigacao" name="hdnObrigacao" value="<?= $_POST['hdnObrigacao'] ?>"/>

    </div>
    <!--  Fim Componente Obrigações Associadas  -->

    <input type="hidden" id="hdnIdEspecieDecisaoLitigioso" name="hdnIdEspecieDecisaoLitigioso"
           value="<?= $objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso(); ?>"/>
    <?
        PaginaSEI::getInstance()->fecharAreaDados();
    ?>
</form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>

<script type="text/javascript">
    function inicializar() {
        carregarComponenteObrigacao();
        if ('<?=$_GET['acao']?>' == 'md_lit_especie_decisao_cadastrar') {
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_especie_decisao_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();
    }

    function showObrigacoes() {
        document.getElementById('obrigacoesAssociadas').style.display = "none";
        document.getElementById('IndObr').checked ? document.getElementById('obrigacoesAssociadas').style.display = "inherit" : document.getElementById('obrigacoesAssociadas').style.display = "none";
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe a Espécie de Decisão.');
            document.getElementById('txtNome').focus();
            return false;
        }

        objsResultadoDecisao = document.getElementsByClassName("resultDecisao");
        validoRD = false;

        for (var i = 0; i < objsResultadoDecisao.length; i++) {
            if (objsResultadoDecisao[i].checked === true) {
                validoRD = true;
            }
        }

        if (!validoRD) {
            alert('Informe o Resultado da Decisão.');
            document.getElementById('gestaoMulta').focus();
            return false;
        }

        var indObrigacao = document.getElementById('IndObr').checked;
        if (indObrigacao) {
            //tipos de controle associados  

            var optionsObrigacao = document.getElementById('selDescricaoObrigacao').options;

            if (optionsObrigacao.length == 0) {
                alert('Informe ao menos uma obrigação associada.');
                document.getElementById('selDescricaoObrigacao').focus();
                return false;
            }
        }

        return true;
    }


    function OnSubmitForm() {
        return validarCadastro();
    }

    function carregarComponenteObrigacao() {

        objAutoCompletarObrigacao = new infraAjaxAutoCompletar('hdnIdObrigacao', 'txtObrigacao', '<?=$strLinkAjaxObrigacao?>');
        objAutoCompletarObrigacao.limparCampo = true;

        objAutoCompletarObrigacao.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtObrigacao').value;
        };

        objAutoCompletarObrigacao.processarResultado = function (id, nome, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricaoObrigacao').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Obrigação já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoObrigacao'), nome, id);

                    objLupaObrigacao.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtObrigacao').value = '';
                document.getElementById('txtObrigacao').focus();

            }
        };

        objLupaObrigacao = new infraLupaSelect('selDescricaoObrigacao', 'hdnObrigacao', '<?=$strLinkObrigacaoSelecao?>');

    }

</script>