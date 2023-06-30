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

    PaginaSEI::getInstance()->verificarSelecao('md_lit_conduta_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objCondutaLitigiosoDTO = new MdLitCondutaDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_lit_conduta_cadastrar':

            $strTitulo = 'Nova Conduta';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCondutaLitigioso" id="sbmCadastrarCondutaLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso(null);
            $objCondutaLitigiosoDTO->setStrNome($_POST['txtNome']);

            if (isset($_POST['sbmCadastrarCondutaLitigioso'])) {
                try {
                    $objCondutaLitigiosoRN = new MdLitCondutaRN();
                    $objCondutaLitigiosoDTO = $objCondutaLitigiosoRN->cadastrar($objCondutaLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Os dados cadastrados foram salvos com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_POST['hdnIdTipoControleLitigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_conduta_litigioso=' . $objCondutaLitigiosoDTO->getNumIdCondutaLitigioso() . PaginaSEI::getInstance()->montarAncora($objCondutaLitigiosoDTO->getNumIdCondutaLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_conduta_alterar':
            $strTitulo = 'Alterar Conduta';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarCondutaLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_conduta_litigioso'])) {

                $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso($_GET['id_conduta_litigioso']);
                $objCondutaLitigiosoDTO->retTodos();
                $objCondutaLitigiosoRN = new MdLitCondutaRN();
                $objCondutaLitigiosoDTO = $objCondutaLitigiosoRN->consultar($objCondutaLitigiosoDTO);

                if ($objCondutaLitigiosoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

            } else {

                $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso($_POST['hdnIdCondutaLitigioso']);
                $objCondutaLitigiosoDTO->setStrNome($_POST['txtNome']);

            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objCondutaLitigiosoDTO->getNumIdCondutaLitigioso()))) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarCondutaLitigioso'])) {
                try {
                    $objCondutaLitigiosoRN = new MdLitCondutaRN();
                    $objCondutaLitigiosoRN->alterar($objCondutaLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Os dados foram alterados com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_POST['hdnIdTipoControleLitigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objCondutaLitigiosoDTO->getNumIdCondutaLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_conduta_consultar':
            $strTitulo = 'Consultar Conduta';
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_conduta_litigioso']))) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso($_GET['id_conduta_litigioso']);
            $objCondutaLitigiosoDTO->setBolExclusaoLogica(false);
            $objCondutaLitigiosoDTO->retTodos();
            $objCondutaLitigiosoRN = new MdLitCondutaRN();
            $objCondutaLitigiosoDTO = $objCondutaLitigiosoRN->consultar($objCondutaLitigiosoDTO);
            if ($objCondutaLitigiosoDTO === null) {
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
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
<form id="frmCondutaCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
            <div class="form-group">
                <label id="lblNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Conduta:</label>
                <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                    value="<?= PaginaSEI::tratarHTML($objCondutaLitigiosoDTO->getStrNome()); ?>"
                    onkeypress="return infraMascaraTexto(this,event,500);" maxlength="500"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

                <input type="hidden" id="hdnIdCondutaLitigioso" name="hdnIdCondutaLitigioso"
                    value="<?= $objCondutaLitigiosoDTO->getNumIdCondutaLitigioso(); ?>"/>
            </div>
        </div>
    </div>
</form>
<?
require_once("md_lit_conduta_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>

