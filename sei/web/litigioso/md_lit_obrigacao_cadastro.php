<?
/**
 * ANATEL
 *
 * 16/05/2016 - criado por CAST
 *
 * Versão do Gerador de Código:
 *
 * Versão no CVS:
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_lit_obrigacao_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objObrigacaoLitigiosoDTO = new MdLitObrigacaoDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_lit_obrigacao_cadastrar':
            $strTitulo = 'Nova Obrigação';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarObrigacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso(null);
            $objObrigacaoLitigiosoDTO->setStrNome($_POST['txtNome']);
            $objObrigacaoLitigiosoDTO->setStrDescricao($_POST['txaDescricao']);
            $objObrigacaoLitigiosoDTO->setStrSinAtivo('S');

            if (isset($_POST['sbmCadastrarObrigacao'])) {
                try {
                    $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
                    $objObrigacaoLitigiosoDTO = $objObrigacaoLitigiosoRN->cadastrar($objObrigacaoLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Obrigação "' . $objObrigacaoLitigiosoDTO->getStrNome() . '" cadastrada com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_obrigacao_litigioso=' . $objObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso() . PaginaSEI::getInstance()->montarAncora($objObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_obrigacao_alterar':
            $strTitulo = 'Alterar Obrigação';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarObrigacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_obrigacao_litigioso'])) {
                $objObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso($_GET['id_obrigacao_litigioso']);
                $objObrigacaoLitigiosoDTO->retTodos();
                $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
                $objObrigacaoLitigiosoDTO = $objObrigacaoLitigiosoRN->consultar($objObrigacaoLitigiosoDTO);
                if ($objObrigacaoLitigiosoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }
            } else {
                $objObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso($_POST['hdnIdObrigacaoLitigioso']);
                $objObrigacaoLitigiosoDTO->setStrNome($_POST['txtNome']);
                $objObrigacaoLitigiosoDTO->setStrDescricao($_POST['txaDescricao']);
                $objObrigacaoLitigiosoDTO->setStrSinAtivo('S');
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso()))) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarObrigacao'])) {
                try {
                    $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
                    $objObrigacaoLitigiosoRN->alterar($objObrigacaoLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Obrigação Legal "' . $objObrigacaoLitigiosoDTO->getStrNome() . '" alterada com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_obrigacao_consultar':
            $strTitulo = 'Consultar Obrigação';
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_obrigacao_litigioso']))) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso($_GET['id_obrigacao_litigioso']);
            $objObrigacaoLitigiosoDTO->setBolExclusaoLogica(false);
            $objObrigacaoLitigiosoDTO->retTodos();
            $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
            $objObrigacaoLitigiosoDTO = $objObrigacaoLitigiosoRN->consultar($objObrigacaoLitigiosoDTO);
            if ($objObrigacaoLitigiosoDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


    if ($_GET['acao'] == 'md_lit_obrigacao_consultar') {
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
require_once("md_lit_obrigacao_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmObrigacaoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span
                                class="infraTeclaAtalho">N</span>ome:</label>
                    <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($objObrigacaoLitigiosoDTO->getStrNome()); ?>"
                            onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelObrigatorio"><span
                                class="infraTeclaAtalho">D</span>escrição:</label>
                    <textarea id="txaDescricao" name="txaDescricao" rows="4" class="infraTextArea form-control"
                                onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250"
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= $objObrigacaoLitigiosoDTO->getStrDescricao(); ?></textarea>
                </div>
            </div>
        </div>
        <input type="hidden" id="hdnIdObrigacaoLitigioso" name="hdnIdObrigacaoLitigioso"
               value="<?= $objObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso(); ?>"/>

    </form>
<?
require_once("md_lit_obrigacao_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
