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

    PaginaSEI::getInstance()->verificarSelecao('md_lit_fase_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objFaseLitigiosoDTO = new MdLitFaseDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_lit_fase_cadastrar':

            $strTitulo = 'Nova Fase';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarFaseLitigioso" id="sbmCadastrarFaseLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objFaseLitigiosoDTO->setNumIdFaseLitigioso(null);
            $objFaseLitigiosoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControleLitigioso']);
            $objFaseLitigiosoDTO->setStrNome($_POST['txtNome']);
            $objFaseLitigiosoDTO->setStrDescricao($_POST['txtDescricao']);

            if (isset($_POST['sbmCadastrarFaseLitigioso'])) {
                try {
                    $objFaseLitigiosoRN = new MdLitFaseRN();
                    $objFaseLitigiosoDTO = $objFaseLitigiosoRN->cadastrar($objFaseLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Os dados cadastrados foram salvos com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_POST['hdnIdTipoControleLitigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_fase_litigioso=' . $objFaseLitigiosoDTO->getNumIdFaseLitigioso() . PaginaSEI::getInstance()->montarAncora($objFaseLitigiosoDTO->getNumIdFaseLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_fase_alterar':
            $strTitulo = 'Alterar Fase';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarFaseLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_fase_litigioso'])) {

                $objFaseLitigiosoDTO->setNumIdFaseLitigioso($_GET['id_fase_litigioso']);
                $objFaseLitigiosoDTO->retTodos();
                $objFaseLitigiosoRN = new MdLitFaseRN();
                $objFaseLitigiosoDTO = $objFaseLitigiosoRN->consultar($objFaseLitigiosoDTO);

                if ($objFaseLitigiosoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

            } else {

                $objFaseLitigiosoDTO->setNumIdFaseLitigioso($_POST['hdnIdFaseLitigioso']);
                $objFaseLitigiosoDTO->setStrNome($_POST['txtNome']);
                $objFaseLitigiosoDTO->setStrDescricao(trim($_POST['txtDescricao']));
                $objFaseLitigiosoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControleLitigioso']);

            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objFaseLitigiosoDTO->getNumIdFaseLitigioso()))) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarFaseLitigioso'])) {
                try {
                    $objFaseLitigiosoRN = new MdLitFaseRN();
                    $objFaseLitigiosoRN->alterar($objFaseLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Os dados foram alterados com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_POST['hdnIdTipoControleLitigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objFaseLitigiosoDTO->getNumIdFaseLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_fase_consultar':
            $strTitulo = 'Consultar Fase';
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_fase_litigioso']))) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
            $objFaseLitigiosoDTO->setNumIdFaseLitigioso($_GET['id_fase_litigioso']);
            $objFaseLitigiosoDTO->setBolExclusaoLogica(false);
            $objFaseLitigiosoDTO->retTodos();
            $objFaseLitigiosoRN = new MdLitFaseRN();
            $objFaseLitigiosoDTO = $objFaseLitigiosoRN->consultar($objFaseLitigiosoDTO);
            if ($objFaseLitigiosoDTO === null) {
                throw new InfraException("Registro não encontrado.");
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_fase_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmFaseCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Nome da
                        Fase:</label>
                    <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($objFaseLitigiosoDTO->getStrNome()); ?>"
                            onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">Descrição:</label>
                    <textarea type="text" id="txtDescricao" rows="3" name="txtDescricao"
                                class="infraText form-control"
                                onkeypress="return infraMascaraTexto(this,event,250);"
                                maxlength="250"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= PaginaSEI::tratarHTML($objFaseLitigiosoDTO->getStrDescricao()); ?></textarea>
                </div>
            </div>
        </div>
        <input type="hidden" id="hdnIdFaseLitigioso" name="hdnIdFaseLitigioso"
               value="<?= $objFaseLitigiosoDTO->getNumIdFaseLitigioso(); ?>"/>
        <input type="hidden" id="hdnIdTipoControleLitigioso" name="hdnIdTipoControleLitigioso"
               value="<?= $_GET['id_tipo_processo_litigioso']; ?>"/>

    </form>
<?
require_once("md_lit_fase_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
