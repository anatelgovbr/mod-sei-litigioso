<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/03/2017 - criado por Ellyson de Jesus Silva
 *
 * Versão do Gerador de Código: 1.40.0
 *
 * Versão no SVN: $Id$
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();


    $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_desativar&acao_origem=' . $_GET['acao'] . ' &id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']);
    $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_ativar&acao_origem=' . $_GET['acao'] . ' &id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']);
    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_excluir&acao_origem=' . $_GET['acao'] . ' &id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']);

    $arrComandos = array();
    $arrComandos[] = '<button type="button" onclick="pesquisar()" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
    $arrComandos[] = '<button type="button" accesskey="T" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
    $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoAtivacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
    $arrComandos[] = '<button type="button" accesskey="X" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">E<span class="infraTeclaAtalho">x</span>cluir</button>';
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_cadastrar&id_tipo_controle_litigioso='.$_GET['id_tipo_controle_litigioso'] . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora('') . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';


    switch ($_GET['acao']) {
        case 'md_lit_tipo_controle_info_adicionais_listar':

            $strTitulo = 'Tipos de Informação Adicional';

            $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
            $objMdLitTpInfoAdDTO->setNumIdMdLitTpControle($_GET['id_tipo_controle_litigioso']);

            if ($_POST['hdnIdMdLitTpControle']) {
                $objMdLitTpInfoAdDTO->setNumIdMdLitTpControle($_POST['hdnIdMdLitTpControle']);
                if (isset($_POST['txtNomeTpInfoAdd']) && $_POST['txtNomeTpInfoAdd'] != "") {
                    $objMdLitTpInfoAdDTO->setStrNome('%' . $_POST['txtNomeTpInfoAdd'] . '%', InfraDTO::$OPER_LIKE);
                }
            }

            $objMdLitTpInfoAdDTO->retTodos();
            $arrObjMdLitTpInfoAdDTO = (new MdLitTpInfoAdRN())->listar($objMdLitTpInfoAdDTO);

            $arrDados = MdLitTpInfoAdINT::montarTabelaListagemTipoInformacao($arrObjMdLitTpInfoAdDTO);

            break;

        case 'md_lit_tipo_controle_info_adicionais_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                MdLitTpInfoAdINT::desativarTipoInformacao($arrStrIds);
            } catch (Exception $e) {
                (new InfraException())->lancarValidacao("Falha ao dasetivar Tipo de Informação.");
            }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_listar&acao_origem=' . $_GET['acao']. '&id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']));
            die;

        case 'md_lit_tipo_controle_info_adicionais_ativar':

            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                MdLitTpInfoAdINT::ativarTipoInformacao($arrStrIds);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_listar&acao_origem=' . $_GET['acao']. '&id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']));
                die;
            }

        case 'md_lit_tipo_controle_info_adicionais_excluir':

            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                MdLitTpInfoAdINT::excluirTipoInformacao($arrStrIds);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_listar&acao_origem=' . $_GET['acao']. '&id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']));
            } catch (Exception $e) {
                $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
                $objMdLitTpInfoAdDTO->setNumIdMdLitTpControle($_GET['id_tipo_controle_litigioso']);
                $objMdLitTpInfoAdDTO->retTodos();
                $arrObjMdLitTpInfoAdDTO = (new MdLitTpInfoAdRN())->listar($objMdLitTpInfoAdDTO);

                $arrDados = MdLitTpInfoAdINT::montarTabelaListagemTipoInformacao($arrObjMdLitTpInfoAdDTO);
                (new InfraException())->lancarValidacao("Não é possível excluir esse Tipo de Informação Adicional pois existe campos vinculado a ele sendo utilizado nos Cadastros de Processo Litigioso.");
                die;
            }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_listar&acao_origem=' . $_GET['acao']. '&id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']));
            die;

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
require_once("md_lit_tipo_controle_info_adicionais_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>

<form id="frmTipoInformacaoAdicional" method="post"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">

    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>

    <div class="row linha">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <div class="form-group">
                        <label id="lblNomeTpInfoAdd" for="txtNomeTpInfoAdd" class="infraLabelOpcional">Tipo de Informação:</label>
                        <input type="text" id="txtNomeTpInfoAdd" name="txtNomeTpInfoAdd" class="infraText form-control"
                               value="<?= $_POST['txtNomeTpInfoAdd'] ?>"
                               maxlength="50" tabindex="502">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?
        PaginaSEI::getInstance()->montarAreaTabela($arrDados['table'], $arrDados['qtd']);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    <input type="hidden" id="hdnIdMdLitTpControle" name="hdnIdMdLitTpControle" value="<? echo $_GET['id_tipo_controle_litigioso'] ? $_GET['id_tipo_controle_litigioso'] : $_POST['hdnIdMdLitTpControle']; ?>"/>
    <input type="submit" style="visibility: hidden"/>
</form>
<?
require_once("md_lit_tipo_controle_info_adicionais_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
