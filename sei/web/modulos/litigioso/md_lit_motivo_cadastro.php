<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/04/2018 - criado por jhon.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_lit_motivo_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdLitMotivoDTO = new MdLitMotivoDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_lit_motivo_cadastrar':
            $strTitulo = 'Cadastro de Motivo para Instauração';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdLitMotivo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdLitMotivoDTO->setNumIdMdLitMotivo(null);
            $objMdLitMotivoDTO->setStrDescricao($_POST['txtDescricao']);
            $objMdLitMotivoDTO->setStrSinAtivo('S');

            if (isset($_POST['sbmCadastrarMdLitMotivo'])) {
                try {
                    $objMdLitMotivoRN = new MdLitMotivoRN();
                    $objMdLitMotivoDTO = $objMdLitMotivoRN->cadastrar($objMdLitMotivoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('motivo "' . $objMdLitMotivoDTO->getNumIdMdLitMotivo() . '" cadastrado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_lit_motivo=' . $objMdLitMotivoDTO->getNumIdMdLitMotivo() . PaginaSEI::getInstance()->montarAncora($objMdLitMotivoDTO->getNumIdMdLitMotivo())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_motivo_alterar':
            $strTitulo = 'Alterar Motivo para Instauração';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdLitMotivo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_md_lit_motivo'])) {
                $objMdLitMotivoDTO->setNumIdMdLitMotivo($_GET['id_md_lit_motivo']);
                $objMdLitMotivoDTO->retTodos();
                $objMdLitMotivoRN = new MdLitMotivoRN();
                $objMdLitMotivoDTO = $objMdLitMotivoRN->consultar($objMdLitMotivoDTO);
                if ($objMdLitMotivoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }
            } else {
                $objMdLitMotivoDTO->setNumIdMdLitMotivo($_POST['hdnIdMdLitMotivo']);
                $objMdLitMotivoDTO->setStrDescricao($_POST['txtDescricao']);
                $objMdLitMotivoDTO->setStrSinAtivo('S');
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objMdLitMotivoDTO->getNumIdMdLitMotivo())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarMdLitMotivo'])) {
                try {
                    $objMdLitMotivoRN = new MdLitMotivoRN();
                    $objMdLitMotivoRN->alterar($objMdLitMotivoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('motivo "' . $objMdLitMotivoDTO->getNumIdMdLitMotivo() . '" alterado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objMdLitMotivoDTO->getNumIdMdLitMotivo())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_motivo_consultar':
            $strTitulo = 'Consultar Motivo para Instauração';
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_md_lit_motivo'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
            $objMdLitMotivoDTO->setNumIdMdLitMotivo($_GET['id_md_lit_motivo']);
            $objMdLitMotivoDTO->setBolExclusaoLogica(false);
            $objMdLitMotivoDTO->retTodos();
            $objMdLitMotivoRN = new MdLitMotivoRN();
            $objMdLitMotivoDTO = $objMdLitMotivoRN->consultar($objMdLitMotivoDTO);
            if ($objMdLitMotivoDTO === null) {
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
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_motivo_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmMdLitMotivoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
                    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($objMdLitMotivoDTO->getStrDescricao()); ?>"
                            onkeypress="return infraMascaraTexto(this,event,150);" maxlength="150"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>

        <input type="hidden" id="hdnIdMdLitMotivo" name="hdnIdMdLitMotivo"
               value="<?= $objMdLitMotivoDTO->getNumIdMdLitMotivo(); ?>"/>
    </form>
<?
require_once("md_lit_motivo_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
