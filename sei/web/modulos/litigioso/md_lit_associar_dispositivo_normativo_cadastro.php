<?
    /**
     * ANATEL
     *
     * 15/02/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    try {

        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();

        SessaoSEI::getInstance()->validarLink();

        PaginaSEI::getInstance()->verificarSelecao('md_lit_dispositivo_normativo_selecionar');

        SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

        $arrComandos = array();

        //DispositivoNormativo
        $strLinkDispositivoNormativoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_selecionar&tipo_selecao=2&id_object=objLupaDispositivoNormativo');
        $strLinkAjaxDispositivoNormativo    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dispositivo_auto_completar');

        switch ($_GET['acao']) {

            case 'md_lit_associar_dispositivo_normativo_consultar':
                $strItensSelDispositivoNormativos = "";
                $strTitulo                        = 'Dispositivos Normativos Associados - ';

                $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarDispositivoNormativoLitigioso" id="sbmCadastrarDispositivoNormativoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
                //cadastrando associaçoes
                if (isset($_POST['hdnDispositivosNormativos'])) {

                    try {

                        $objAssocDispositivoNormativoLitigiosoRN = new MdLitAssociarDispositivoNormativoRN();
                        $arrDispositivoNormativos                = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDispositivosNormativos']);


                        $objAssocDispositivoNormativoLitigiosoRN->validarExclusao($arrDispositivoNormativos, $_POST['hdnIdTipoControle']);
                        //SET DISPOSITIVOS
                        $arrObjAssocDispositivoNormativoDTO = array();


                        for ($x = 0; $x < count($arrDispositivoNormativos); $x++) {
                            $objAssocDispositivoNormativoDTO = new MdLitAssociarDispositivoNormativoDTO();
                            $objAssocDispositivoNormativoDTO->setNumIdDispositivoNormativoLitigioso($arrDispositivoNormativos[$x]);
                            $objAssocDispositivoNormativoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControle']);
                            array_push($arrObjAssocDispositivoNormativoDTO, $objAssocDispositivoNormativoDTO);
                        }

                        // Cadastro - remove os relacionamentos atuais, adiciona os novos
                        $relacionamentoDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                        $relacionamentoDTO->setNumIdTipoControle($_POST['hdnIdTipoControle']);
                        $objAssocDispositivoNormativoLitigiosoRN->excluirRelacionamentos($relacionamentoDTO);
                        $objAssocDispositivoNormativoLitigiosoRN->cadastrar($arrObjAssocDispositivoNormativoDTO);
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?&acao=md_lit_tipo_controle_listar'));
                        die;

                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }

                } else {
                    //obter dispositivos já associados ao tipo de controle selecionado
                    $idTipoControle = $_GET['id_tipo_controle_litigioso'];

                    $objAssocDispositivoNormativoDTO = new MdLitAssociarDispositivoNormativoDTO();
                    $objAssocDispositivoNormativoDTO->retTodos();
                    $objAssocDispositivoNormativoDTO->retStrNorma();
                    $objAssocDispositivoNormativoDTO->retStrDispositivo();
                    $objAssocDispositivoNormativoDTO->retStrSinAtivoDispositivo();

                    $objAssocDispositivoNormativoDTO->setNumIdTipoControleLitigioso($idTipoControle);
                    $objAssocDispositivoNormativoDTO->setStrSinAtivoDispositivo('S');

                    $objAssocDispositivoNormativoLitigiosoRN = new MdLitAssociarDispositivoNormativoRN();
                    $arrItens                                = $objAssocDispositivoNormativoLitigiosoRN->listar($objAssocDispositivoNormativoDTO);

                    foreach ($arrItens as $item) {
                        $strItensSelDispositivoNormativos .= "<option value='" . $item->getNumIdDispositivoNormativoLitigioso() . "'>" . PaginaSEI::tratarHTML($item->getStrNorma()) . " - " . PaginaSEI::tratarHTML($item->getStrDispositivo()) . "</option>";
                    }

                }
                //chamando o objeto do MdLitTipoControleRN
                $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                $objTipoControleLitigiosoDTO->retTodos();
                $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControle);


                $objTipoControleLitigiosoRN  = new MdLitTipoControleRN();
                $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);

                $strTitulo .= PaginaSEI::tratarHTML($objTipoControleLitigiosoDTO->getStrSigla());

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

#lblDispositivo {position:absolute;left:0%;top:0%;width:50%;}
#txtDispositivo {position:absolute;left:0%;top:40%;width:50%;}

#lblDescricaoDispositivo {position:absolute;left:0%;top:0%;width:50%;}
#txtDescricaoDispositivo {position:absolute;left:0%;top:20%;width:75%;}

#lblDescricaoDispositivoNormativo {position:absolute;left:0%;top:0%;width:50%;}
#txtDispositivoNormativo {position:absolute;left:0%;top:5%;width:50%;}
#selDescricaoDispositivoNormativo {position:absolute;left:0%;top:10%;width:75%;}

#imgLupaDispositivoNormativo {position:absolute;left:76%;top:10%;}
#imgExcluirDispositivoNormativo {position:absolute;left:76%;top:15%;}

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
<form id="frmdispositivoNormativoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao_origem'])) ?>">
    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('55.5em');
    ?>

    <!--  Componente DispositivoNormativo  -->
    <div id="divUn1" class="infraAreaDados" style="height:51.5em;">

        <label id="lblDescricaoDispositivoNormativo" for="txtDispositivoNormativo" accesskey="q"
               class="infraLabelObrigatorio">Dispositivos Normativos associados:</label>

        <input type="text" id="txtDispositivoNormativo" name="txtDispositivoNormativo" class="infraText"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        <select id="selDescricaoDispositivoNormativo" name="selDescricaoDispositivoNormativo" size="30"
                multiple="multiple" class="infraSelect">
            <?= $strItensSelDispositivoNormativos ?>
        </select>

        <img id="imgLupaDispositivoNormativo" onclick="objLupaDispositivoNormativo.selecionar(700,500);"
             src="/infra_css/imagens/lupa.gif"
             alt="Localizar DispositivoNormativo Associada"
             title="Localizar Dispositivo Normativo Associado" class="infraImg"/>

        <img id="imgExcluirDispositivoNormativo" onclick="objLupaDispositivoNormativo.remover();"
             src="/infra_css/imagens/remover.gif"
             alt="Remover DispositivoNormativos Associadas"
             title="Remover Dispositivos Normativos Associados" class="infraImg"/>

        <input type="hidden" id="hdnIdDispositivoNormativo" name="hdnIdDispositivoNormativo"
               value="<?= $_POST['hdnIdDispositivoNormativo'] ?>"/>
        <input type="hidden" id="hdnDispositivosNormativos" name="hdnDispositivosNormativos"
               value="<?= $_POST['hdnDispositivosNormativos'] ?>"/>
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
    var objLupaDispositivoNormativo = null;
    var objAutoCompletarDispositivoNormativo = null;

    function inicializar() {

        carregarComponenteDispositivo();
        document.getElementById('btnCancelar').focus();
        infraEfeitoTabelas();
    }

    function carregarComponenteDispositivo() {

        objAutoCompletarDispositivoNormativo = new infraAjaxAutoCompletar('hdnIdDispositivoNormativo', 'txtDispositivoNormativo', '<?=$strLinkAjaxDispositivoNormativo?>');
        objAutoCompletarDispositivoNormativo.limparCampo = true;

        objAutoCompletarDispositivoNormativo.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtDispositivoNormativo').value;
        };

        objAutoCompletarDispositivoNormativo.processarResultado = function (id, descricao, complemento) {

            if (id != '') {

                var options = document.getElementById('selDescricaoDispositivoNormativo').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {

                        if (options[i].value == id) {
                            alert('Dispositivo Normativo já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    var texto = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoDispositivoNormativo'), texto, id);
                    objLupaDispositivoNormativo.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtDispositivoNormativo').value = '';
                document.getElementById('txtDispositivoNormativo').focus();

            }
        };

        objLupaDispositivoNormativo = new infraLupaSelect('selDescricaoDispositivoNormativo', 'hdnDispositivosNormativos', '<?=$strLinkDispositivoNormativoSelecao?>');

    }

    function validarCadastro() {

        //dispositivos normativos
        var optionsDispositivoNormativo = document.getElementById('selDescricaoDispositivoNormativo').options;

        if (optionsDispositivoNormativo.length == 0) {

            if (confirm("Nenhum Dispositivo Normativo foi associado. Deseja salvar o registro?")) {
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