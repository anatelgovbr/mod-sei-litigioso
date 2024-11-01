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

    $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_desativar&acao_origem=' . $_GET['acao'] . ' &id_tp_info_ad='. $_GET['id_tp_info_ad']. '&id_tipo_controle_litigioso=' . $_GET['id_tipo_controle_litigioso']);
    $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_ativar&acao_origem=' . $_GET['acao'] . ' &id_tp_info_ad='. $_GET['id_tp_info_ad']. '&id_tipo_controle_litigioso=' . $_GET['id_tipo_controle_litigioso']);
    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_excluir&acao_origem=' . $_GET['acao'] . ' &id_tp_info_ad='. $_GET['id_tp_info_ad']. '&id_tipo_controle_litigioso=' . $_GET['id_tipo_controle_litigioso']);

    $arrComandos = array();
    $arrComandos[] = '<button type="button" accesskey="T" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
    $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoAtivacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
    $arrComandos[] = '<button type="button" accesskey="X" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">E<span class="infraTeclaAtalho">x</span>cluir</button>';
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_cadastrar&id_tp_info_ad='.$_GET['id_tp_info_ad'] . '&acao_origem=' . $_GET['acao']. '&id_tipo_controle_litigioso=' . $_GET['id_tipo_controle_litigioso'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_listar&acao_origem=md_lit_tipo_controle_info_adicionais_listar&id_tipo_controle_litigioso=' . $_GET['id_tipo_controle_litigioso']) . PaginaSEI::getInstance()->montarAncora('') . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

    $strNomeTipoInformacao =MdLitTpInfoAdINT::recuperarNome($_GET['id_tp_info_ad']);

    switch ($_GET['acao']) {
        case 'md_lit_campos_add_listar':

            $strTitulo = 'Campos de Informação Adicional';
            $arrDados = MdLitCamposAdINT::montarTabelaListagemCamposAdicionais($_GET['id_tp_info_ad'], $_GET['id_tipo_controle_litigioso']);

            break;

        case 'md_lit_campos_add_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                MdLitCamposAdINT::desativarCampoAdd($arrStrIds);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_listar&acao_origem=' . $_GET['acao']. '&id_tp_info_ad='. $_GET['id_tp_info_ad']. '&id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']));
            die;

        case 'md_lit_campos_add_ativar':

            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                MdLitCamposAdINT::ativarCampoAdd($arrStrIds);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                $strTitulo = 'Campos de Informação Adicional';
                $arrDados = MdLitCamposAdINT::montarTabelaListagemCamposAdicionais($_GET['id_tp_info_ad'], $_GET['id_tipo_controle_litigioso']);
                (new InfraException())->lancarValidacao($e->getMessage());
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_listar&acao_origem=' . $_GET['acao']. '&id_tp_info_ad='. $_GET['id_tp_info_ad']. '&id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso']));
            die;

        case 'md_lit_campos_add_excluir':

            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                MdLitCamposAdINT::excluirCampoInfoAdd($arrStrIds);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                $strTitulo = 'Campos de Informação Adicional';
                $arrDados = MdLitCamposAdINT::montarTabelaListagemCamposAdicionais($_GET['id_tp_info_ad'], $_GET['id_tipo_controle_litigioso']);
                (new InfraException())->lancarValidacao($e->getMessage());
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
require_once("md_lit_campos_add_lista_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>

<form id="frmCamposAdd" method="post"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label id="lblTipoInformacao" class="infraLabelObrigatorio">Tipo de Informação Adicional:</label>
                <input type="text" id="txtTipoInformacao" name="txtTipoInformacao" class="infraText form-control infraReadOnly" readonly="readonly"
                       value="<?= PaginaSEI::tratarHTML($strNomeTipoInformacao)?>"/>
            </div>
        </div>
    </div>


    <? PaginaSEI::getInstance()->montarAreaTabela($arrDados['table'], $arrDados['qtd']); ?>

    <input type="submit" style="visibility: hidden"/>
</form>
<?
require_once("md_lit_campos_add_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
