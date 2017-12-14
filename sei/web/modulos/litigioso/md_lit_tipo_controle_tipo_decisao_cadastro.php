<?
    /**
     * ANATEL
     *
     * 01/06/2016 - criado por alan.campos@castgroup.com.br - CAST
     *
     */

    try {

        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();

        SessaoSEI::getInstance()->validarLink();

        PaginaSEI::getInstance()->verificarSelecao('md_lit_tipo_decisao_selecionar');

        SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

        $arrComandos = array();

        //TipoDecisao
        $strLinkTipoDecisaoSelecao       = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_selecionar&tipo_selecao=2&id_object=objLupaTipoDecisao');
        $strLinkAjaxTipoDecisaoLitigioso = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_tipo_decisao_auto_completar');

        switch ($_GET['acao']) {

            case 'md_lit_tipo_controle_tipo_decisao_consultar':
                $strItensSelTipoDecisaoLitigioso = "";
                $strTitulo                       = 'Associar Tipos de Decisão - Tipo de Controle Litigioso: ' . $_GET['sigla_tipo_controle_litigioso'];

                $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoDecisaoLitigioso" id="sbmCadastrarTipoDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
                //cadastrando associaçoes
                if (isset($_POST['hdnTipoDecisaoLitigioso'])) {

                    try {

                        $objAssocTipoDecisaoLitigiosoRN = new MdLitRelTipoControleTipoDecisaoRN();
                        $arrTipoDecisao                 = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoDecisaoLitigioso']);

                        //SET DECISOES
                        $arrObjAssocTipoDecisaoDTO = array();

                        for ($x = 0; $x < count($arrTipoDecisao); $x++) {

                            $objAssocTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                            $objAssocTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($arrTipoDecisao[$x]);
                            $objAssocTipoDecisaoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControle']);
                            array_push($arrObjAssocTipoDecisaoDTO, $objAssocTipoDecisaoDTO);
                        }

                        // Cadastro - remove os relacionamentos atuais, adiciona os novos

                        $relacionamentoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                        $relacionamentoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControle']);
                        $objAssocTipoDecisaoLitigiosoRN->excluirRelacionamentos($relacionamentoDTO);
                        $objAssocTipoDecisaoLitigiosoRN->cadastrar($arrObjAssocTipoDecisaoDTO);
                        //header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?&acao=md_lit_tipo_controle_listar'));
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_POST['hdnIdTipoControle'])));
                        die;

                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }

                } else {
                    //obter tipos de decisão já associados ao tipo de controle selecionado
                    $idTipoControle = $_GET['id_tipo_controle_litigioso'];

                    $objAssocTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                    $objAssocTipoDecisaoDTO->retTodos();
                    $objAssocTipoDecisaoDTO->retStrNome();
                    $objAssocTipoDecisaoDTO->retStrSinAtivoDecisao();

                    $objAssocTipoDecisaoDTO->setNumIdTipoControleLitigioso($idTipoControle);
                    $objAssocTipoDecisaoDTO->setStrSinAtivoDecisao('S');

                    PaginaSEI::getInstance()->prepararOrdenacao($objAssocTipoDecisaoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);

                    $objAssocTipoDecisaoLitigiosoRN = new MdLitRelTipoControleTipoDecisaoRN();
                    $arrItens                       = $objAssocTipoDecisaoLitigiosoRN->listar($objAssocTipoDecisaoDTO);


                    foreach ($arrItens as $item) {
                        $strItensSelTipoDecisao .= "<option value='" . $item->getNumIdTipoDecisaoLitigioso() . "'>" . $item->getStrNome() . "</option>";
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

#lblTipoDecisaoLitigioso {position:absolute;left:0%;top:0%;width:50%;}
#txtTipoDecisaoLitigioso {position:absolute;left:0%;top:40%;width:50%;}

#lblDescricaoTipoDecisaoLitigioso {position:absolute;left:0%;top:0%;width:50%;}
#txtDescricaoTipoDecisaoLitigioso {position:absolute;left:0%;top:20%;width:75%;}

#lblDescricaoTipoDecisaoLitigioso {position:absolute;left:0%;top:0%;width:50%;}
#txtTipoDecisaoLitigioso {position:absolute;left:0%;top:15%;width:50%;}
#selDescricaoTipoDecisaoLitigioso{position:absolute;left:0%;top:35%;width:75%;}

#imgLupaTipoDecisao {position:absolute;left:76%;top:35%;}
#imgExcluirTipoDecisao {position:absolute;left:76%;top:55%;}

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
<form id="frmTipoDecisaoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao_origem'])) ?>">
    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('15.5em');
    ?>

    <!--  Componente TipoDecisao  -->
    <div id="divUn1" class="infraAreaDados" style="height:11.5em;">

        <label id="lblDescricaoTipoDecisao" for="txtTipoDecisao" accesskey="q" class="infraLabelObrigatorio">Tipos de
            Decisão associados:</label>

        <input type="text" id="txtTipoDecisaoLitigioso" name="txtTipoDecisaoLitigioso" class="infraText"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        <select id="selDescricaoTipoDecisaoLitigioso" name="selDescricaoTipoDecisaoLitigioso" size="4"
                multiple="multiple" class="infraSelect">
            <?= $strItensSelTipoDecisao ?>
        </select>

        <img id="imgLupaTipoDecisao" onclick="objLupaTipoDecisao.selecionar(700,500);" src="/infra_css/imagens/lupa.gif"
             alt="Localizar Tipos de Decisão"
             title="Localizar Tipos de Decisão" class="infraImg"/>

        <img id="imgExcluirTipoDecisao" onclick="objLupaTipoDecisao.remover();" src="/infra_css/imagens/remover.gif"
             alt="Remover Tipos de Decisão Associados"
             title="Remover Tipos de Decisão Associados" class="infraImg"/>

        <input type="hidden" id="hdnIdTipoDecisaoLitigioso" name="hdnIdTipoDecisaoLitigioso"
               value="<?= $_POST['hdnIdTipoDecisaoLitigioso'] ?>"/>
        <input type="hidden" id="hdnTipoDecisaoLitigioso" name="hdnTipoDecisaoLitigioso"
               value="<?= $_POST['hdnTipoDecisaoLitigioso'] ?>"/>
        <input type="hidden" id="hdnIdTipoControle" name="hdnIdTipoControle"
               value="<?php echo isset($_GET['id_tipo_controle_litigioso']) ? $_GET['id_tipo_controle_litigioso'] : $_POST['hdnIdTipoControle']; ?>"/>

    </div>

    <?
        PaginaSEI::getInstance()->fecharAreaDados();
    ?>
</form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>

<script type="text/javascript">
    var objLupaTipoDecisao = null;
    var objAutoCompletarTipoDecisao = null;

    function inicializar() {

        carregarComponenteDecisao();
        document.getElementById('btnCancelar').focus();
        infraEfeitoTabelas();
    }

    function carregarComponenteDecisao() {

        objAutoCompletarTipoDecisao = new infraAjaxAutoCompletar('hdnIdTipoDecisaoLitigioso', 'txtTipoDecisaoLitigioso', '<?=$strLinkAjaxTipoDecisaoLitigioso?>');
        objAutoCompletarTipoDecisao.limparCampo = true;

        objAutoCompletarTipoDecisao.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtTipoDecisaoLitigioso').value;
        };

        objAutoCompletarTipoDecisao.processarResultado = function (id, descricao, complemento) {

            if (id != '') {

                var options = document.getElementById('selDescricaoTipoDecisaoLitigioso').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Tipo de Decisão já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }


                    var texto = descricao;
                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoTipoDecisaoLitigioso'), texto, id);
                    objLupaTipoDecisao.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtTipoDecisaoLitigioso').value = '';
                document.getElementById('txtTipoDecisaoLitigioso').focus();

            }
        };

        objLupaTipoDecisao = new infraLupaSelect('selDescricaoTipoDecisaoLitigioso', 'hdnTipoDecisaoLitigioso', '<?=$strLinkTipoDecisaoSelecao?>');

    }

    function validarCadastro() {

        var optionsTipoDecisao = document.getElementById('selDescricaoTipoDecisao').options;

        if (optionsTipoDecisao.length == 0) {

            if (confirm("Nenhum Tipo de Decisão foi associado. Deseja salvar o registro?")) {
                return true;
            } else {
                return false;
            }

        } else {
            return true;
        }

    }

    function OnSubmitForm() {
        return validarCadastro();
    }

</script>