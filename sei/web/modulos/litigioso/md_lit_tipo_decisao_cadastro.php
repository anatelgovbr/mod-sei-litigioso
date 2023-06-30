<?
/**
 * ANATEL
 *
 * 20/05/2016 - criado por alan.campos@castgroup.com.br - CAST
 *
 */

$strLinkValidarEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_especie_decisao');

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(false);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_lit_tipo_decisao_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objTipoDecisaoDTO = new MdLitTipoDecisaoDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_lit_tipo_decisao_cadastrar':
            $strTitulo = 'Novo Tipo de Decisão';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEspecies" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '#ID-' . $_GET['id_tipo_procedimento'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


            $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso(null);
            $objTipoDecisaoDTO->setStrNome($_POST['txtNome']);
            $objTipoDecisaoDTO->setStrDescricao($_POST['txaDescricao']);

            $arrObjEspecieDecisaoDTO = array();
            $arrEspecies = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnEspecies']);

            for ($x = 0; $x < count($arrEspecies); $x++) {
                $objRelTipoEspecieDecisaoLitigiosoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                $objRelTipoEspecieDecisaoLitigiosoDTO->setNumIdEspecieDecisaoLitigioso($arrEspecies[$x]);
                array_push($arrObjEspecieDecisaoDTO, $objRelTipoEspecieDecisaoLitigiosoDTO);
            }

            $objTipoDecisaoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoDTO);

            $objTipoDecisaoDTO->setStrSinAtivo('S');

            if (isset($_POST['sbmCadastrarEspecies'])) {
                try {
                    $objTipoDecisaoRN = new MdLitTipoDecisaoRN();
                    $objTipoDecisaoDTO = $objTipoDecisaoRN->cadastrar($objTipoDecisaoDTO);
                    PaginaSEI::getInstance()->setStrMensagem('Tipo de Decisão cadastrado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_decisao_litigioso=' . $objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso() . '#ID-' . $objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso()));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_tipo_decisao_alterar':
            $strTitulo = 'Alterar Tipo de Decisão';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';


            if (isset($_GET['id_tipo_decisao_litigioso'])) {
                $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($_GET['id_tipo_decisao_litigioso']);
                $objTipoDecisaoDTO->retTodos();
                $objTipoDecisaoRN = new MdLitTipoDecisaoRN();
                $objTipoDecisaoDTO = $objTipoDecisaoRN->consultar($objTipoDecisaoDTO);
                if ($objTipoDecisaoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

                $objRelEspecieDecisaoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                $objRelEspecieDecisaoDTO->retTodos();
                $objRelEspecieDecisaoDTO->retStrNomeEspecie();
                $objRelEspecieDecisaoDTO->setNumIdTipoDecisaoLitigioso($_GET['id_tipo_decisao_litigioso']);

                PaginaSEI::getInstance()->prepararOrdenacao($objRelEspecieDecisaoDTO, 'NomeEspecie', InfraDTO::$TIPO_ORDENACAO_ASC);

                $objRelTipoEspecieDecisaoLitigiosoRN = new MdLitRelTipoEspecieDecisaoRN();
                $arrEspecies = $objRelTipoEspecieDecisaoLitigiosoRN->listar($objRelEspecieDecisaoDTO);

                $objTipoDecisaoDTO->setArrObjRelEspecieLitigiosoDTO($arrEspecies);


                $strItensSelEspecies = "";
                for ($x = 0; $x < count($arrEspecies); $x++) {
                    $strItensSelEspecies .= "<option value='" . $arrEspecies[$x]->getNumIdEspecieDecisaoLitigioso() . "'>" . PaginaSEI::tratarHTML($arrEspecies[$x]->getStrNomeEspecie()) . "</option>";

                }

            } else {

                $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($_POST['hdnIdTipoDecisaoLitigioso']);
                $objTipoDecisaoDTO->setStrNome($_POST['txtNome']);
                $objTipoDecisaoDTO->setStrDescricao($_POST['txaDescricao']);

                //Set Especies
                $arrObjEspecieDecisaoDTO = array();
                $arrEspecies = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnEspecies']);


                for ($x = 0; $x < count($arrEspecies); $x++) {
                    $objRelTipoEspecieDecisaoLitigiosoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                    $objRelTipoEspecieDecisaoLitigiosoDTO->setNumIdEspecieDecisaoLitigioso($arrEspecies[$x]);
                    array_push($arrObjEspecieDecisaoDTO, $objRelTipoEspecieDecisaoLitigiosoDTO);
                }

                $objTipoDecisaoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoDTO);

            }


            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '#ID-' . $objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso() . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


            if (isset($_POST['sbmAlterarTipoDecisaoLitigioso'])) {
                try {
                    $objTipoDecisaoRN = new MdLitTipoDecisaoRN();

                    $objTipoDecisaoDTO = $objTipoDecisaoRN->alterar($objTipoDecisaoDTO);

                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            break;

        case 'md_lit_tipo_decisao_consultar':
            $strTitulo = "Consultar Tipo de Decisão";
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '#ID-' . $_GET['id_tipo_decisao_litigioso'] . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $strDesabilitar = 'disabled="disabled"';
            if (isset($_GET['id_tipo_decisao_litigioso'])) {
                $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($_GET['id_tipo_decisao_litigioso']);
                $objTipoDecisaoDTO->retTodos();
                $objTipoDecisaoRN = new MdLitTipoDecisaoRN();
                $objTipoDecisaoDTO = $objTipoDecisaoRN->consultar($objTipoDecisaoDTO);

                if ($objTipoDecisaoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

                $objRelTipoEspecieDecisaoLitigiosoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                $objRelTipoEspecieDecisaoLitigiosoDTO->retTodos();
                $objRelTipoEspecieDecisaoLitigiosoDTO->retStrNomeEspecie();
                $objRelTipoEspecieDecisaoLitigiosoDTO->setNumIdTipoDecisaoLitigioso($_GET['id_tipo_decisao_litigioso']);

                PaginaSEI::getInstance()->prepararOrdenacao($objRelTipoEspecieDecisaoLitigiosoDTO, 'NomeEspecie', InfraDTO::$TIPO_ORDENACAO_ASC);

                $objRelTipoEspecieDecisaoLitigiosoRN = new MdLitRelTipoEspecieDecisaoRN();
                $arrEspecies = $objRelTipoEspecieDecisaoLitigiosoRN->listar($objRelTipoEspecieDecisaoLitigiosoDTO);

                $objTipoDecisaoDTO->setArrObjRelEspecieLitigiosoDTO($arrEspecies);

                $strItensSelEspecies = "";
                for ($x = 0; $x < count($arrEspecies); $x++) {
                    $strItensSelEspecies .= "<option value='" . $arrEspecies[$x]->getNumIdEspecieDecisaoLitigioso() . "'>" . $arrEspecies[$x]->getStrNomeEspecie() . "</option>";
                }

            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $strLinkEspeciesSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_especie_decisao_selecionar&tipo_selecao=2&id_object=objLupaEspecies');
    $strLinkAjaxEspecies = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_especie_decisao_auto_completar');

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());


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

    #divGrauSigilo {<?= $strDisplayGrauSigilo ?>}
    #lblGrauSigilo {position:absolute;left:0%;top:0%;}
    #selGrauSigilo {position:absolute;left:0%;top:40%;width:35%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_tipo_decisao_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTipoProcedimentoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span
                                class="infraTeclaAtalho">N</span>ome:</label>
                    <input <?= $strDesabilitar; ?> type="text" id="txtNome" name="txtNome" class="infraText form-control"
                                                value="<?= PaginaSEI::tratarHTML($objTipoDecisaoDTO->getStrNome()); ?>"
                                                onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelOpcional"><span
                                class="infraTeclaAtalho">D</span>escrição:</label>
                    <textarea <?= $strDesabilitar; ?> id="txaDescricao" name="txaDescricao" rows="3"
                                                    class="infraTextArea form-control"
                                                    onkeypress="return infraLimitarTexto(this,event,250);"
                                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= $objTipoDecisaoDTO->getStrDescricao(); ?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblEspecies" for="selEspecies" accesskey="" class="infraLabelOpcional">Espécies de Decisão
                    associadas:</label>
                <input <?= $strDesabilitar; ?> type="text" id="txtEspecies" name="txtEspecies"
                                               class="infraText form-control"
                                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <select <?= $strDesabilitar; ?> id="selEspecies" name="selEspecies" size="8" multiple="multiple"
                                                        class="infraSelect form-control">
                            <?= $strItensSelEspecies ?>
                        </select>
                        <div class="botoes">
                            <img id="imgLupaEspecies" onclick="objLupaEspecies.selecionar(700,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Localizar Espécies de Decisão" title="Localizar Espécies de Decisão"
                                class="infraImg"/>
                            <img id="imgExcluirEspecies" onclick="objLupaEspecies.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Espécies de Decisão" title="Remover Espécies de Decisão" class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnIdEspecies" name="hdnIdEspecies" value=""/>
                    </div>
                </div>
            </div>
        </div>
        <div id="divNome" class="infraAreaDados" style="height:4.5em;">

        </div>

        <div id="divDescricao" class="infraAreaDados" style="height:6em;">

        </div>

        <div id="divEspecies" class="infraAreaDados" style="height:12em;">


        </div>


        <input type="hidden" id="hdnIdTipoDecisaoLitigioso" name="hdnIdTipoDecisaoLitigioso"
               value="<?= $objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso(); ?>"/>
        <input type="hidden" id="hdnEspecies" name="hdnEspecies" value="<?= $_POST['hdnEspecies'] ?>"/>

        <?
        PaginaSEI::getInstance()->montarAreaDebug();
        ?>
    </form>
<?
require_once("md_lit_tipo_decisao_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
