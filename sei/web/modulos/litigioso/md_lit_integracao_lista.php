<?php

/**
 * @since  23/02/2017
 * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();

PaginaSEI::getInstance()->prepararSelecao('md_lit_integracao_selecionar');

#URL Base
$strUrl = 'controlador.php?acao=md_lit_integracao_';

#URL das Actions
$strUrlDesativar = SessaoSEI::getInstance()->assinarLink($strUrl . 'desativar&acao_origem=' . $_GET['acao']);
$strUrlReativar = SessaoSEI::getInstance()->assinarLink($strUrl . 'reativar&acao_origem=' . $_GET['acao']);
$strUrlExcluir = SessaoSEI::getInstance()->assinarLink($strUrl . 'excluir&acao_origem=' . $_GET['acao']);
$strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao']);
$strUrlNovo = SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
$strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);

$strTitulo = 'Mapeamento das Integrações';

switch ($_GET['acao']) {

    #region Desativar
    case 'md_lit_integracao_desativar':
        try {

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
                $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($arrStrIds[$i]);
                $arrObjMdLitIntegracao[] = $objMdLitIntegracaoDTO;
            }
            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitIntegracaoRN->desativar($arrObjMdLitIntegracao);

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
        break;
    #endregion

    #region Reativar
    case 'md_lit_integracao_reativar':

        try {
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $id = reset($arrStrIds);
            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
                $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($arrStrIds[$i]);
                $arrObjMdLitIntegracao[] = $objMdLitIntegracaoDTO;
            }
            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitIntegracaoRN->reativar($arrObjMdLitIntegracao);

            PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($id)));
        die;

        break;

    #endregion

    #region Excluir
    case 'md_lit_integracao_excluir':
        try {

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();

            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
                $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($arrStrIds[$i]);
                $arrObjMdLitIntegracao[] = $objMdLitIntegracaoDTO;
            }

            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitIntegracaoRN->excluir($arrObjMdLitIntegracao);

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
        break;
    #endregion

    #region Selecionar
    case 'md_lit_integracao_selecionar':
        $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Integração', 'Selecionar Integração');
        $strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'selecionar&acao_origem=' . $_GET['acao']);

        break;
    #endregion

    #region Listar
    case 'md_lit_integracao_listar':


        break;
    #endregion

    #region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    #endregion
}

#Verifica se é ação Selecionar
$bolSelecionar = $_GET['acao'] == 'md_lit_integracao_selecionar';


#Botões de ação do topo
$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                    <span class="infraTeclaAtalho">P</span>esquisar
                              </button>';
if (!$bolSelecionar) {
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" onclick="novo()" class="infraButton">
                                    <span class="infraTeclaAtalho">N</span>ovo
                              </button>';

    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="imprimir()" class="infraButton">
                                    <span class="infraTeclaAtalho">I</span>mprimir
                              </button>';
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';
} else {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton">
                                    <span class="infraTeclaAtalho">T</span>ransportar
                            </button>';

    $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">
                                    <span class="infraTeclaAtalho">F</span>echar
                            </button>';
}


#Consulta
$objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
$objMdLitIntegracaoDTO->retTodos(true);
$objMdLitIntegracaoDTO->retStrNomeMdLitFuncionalidade();
$objMdLitIntegracaoDTO->setBolExclusaoLogica(false);

if (isset ($_POST ['txtNome']) && trim($_POST ['txtNome']) != '') {
    $objMdLitIntegracaoDTO->setStrNome('%' . $_POST ['txtNome'] . '%', InfraDTO::$OPER_LIKE);
}

if (isset ($_POST ['selFuncionalidade']) && trim($_POST ['selFuncionalidade']) != '' && trim($_POST ['selFuncionalidade']) != 'null') {
    $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($_POST ['selFuncionalidade'], InfraDTO::$OPER_IGUAL);
}

$objMdLitIntegracaoRN = new MdLitIntegracaoRN();

#Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdLitIntegracaoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararOrdenacao($objMdLitIntegracaoDTO, 'NomeMdLitFuncionalidade', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdLitIntegracaoDTO);


$arrObjMdLitIntegracao = $objMdLitIntegracaoRN->listar($objMdLitIntegracaoDTO);


PaginaSEI::getInstance()->processarPaginacao($objMdLitIntegracaoDTO);
$numRegistros = count($arrObjMdLitIntegracao);

#Tabela de resultado.
if ($numRegistros > 0) {

    $strResultado .= '<table width="99%" class="infraTable" summary="Integrações">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Integrações', $numRegistros);
    $strResultado .= '</caption>';
    #Cabeçalho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strResultado .= '<th class="infraTh" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objMdLitIntegracaoDTO, 'Nome', 'Nome', $arrObjMdLitIntegracao) . '</th>';
    $strResultado .= '<th class="infraTh" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objMdLitIntegracaoDTO, 'Funcionalidade', 'NomeMdLitFuncionalidade', $arrObjMdLitIntegracao) . '</th>';
    $strResultado .= '<th class="infraTh" width="auto">' . PaginaSEI::getInstance()->getThOrdenacao($objMdLitIntegracaoDTO, 'Tipo Cliente WS', 'TipoClienteWs', $arrObjMdLitIntegracao) . '</th>';
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>';
    $strResultado .= '</tr>';

    #Linhas

    $strCssTr = '<tr class="infraTrEscura">';

    for ($i = 0; $i < $numRegistros; $i++) {

        #vars
        $strId = $arrObjMdLitIntegracao[$i]->getNumIdMdLitIntegracao();
        $strNomeIntegracao = $arrObjMdLitIntegracao[$i]->getStrNome();
        $strNomeIntegracaoParametro = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdLitIntegracao[$i]->getStrNome());
        $bolRegistroAtivo = $arrObjMdLitIntegracao[$i]->getStrSinAtivo() == 'S';

        $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
        $strResultado .= $strCssTr;

        #Linha Checkbox
        $strResultado .= '<td align="center" valign="top">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strNomeIntegracao);
        $strResultado .= '</td>';

        #Linha Nome
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strNomeIntegracao);
        $strResultado .= '</td>';

        #Linha funcionalidade
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitIntegracao[$i]->getStrNomeMdLitFuncionalidade());
        $strResultado .= '</td>';

        //Tipo cliente WS
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitIntegracao[$i]->getStrTipoClienteWs());
        $strResultado .= '</td>';

        $strResultado .= '<td align="center">';

        #Ação Consulta
        if (!$bolSelecionar) {
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink($strUrl . 'consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_lit_integracao=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Integração" alt="Consultar Integração" class="infraImg" /></a>&nbsp;';

            #Ação Alterar
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink($strUrl . 'alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_md_lit_integracao=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Integração" alt="Alterar Integração" class="infraImg" /></a>&nbsp;';

            #Ação Desativar
            if ($bolRegistroAtivo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="desativar(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeIntegracao) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Integração" alt="Desativar Integração" class="infraImg" /></a>&nbsp;';
            }

            #Ação Reativar
            if (!$bolRegistroAtivo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="reativar(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeIntegracao) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Integração" alt="Reativar Integração" class="infraImg" /></a>&nbsp;';
            }

            #Ação Excluir
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="excluir(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeIntegracao) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Integração" alt="Excluir Integração" class="infraImg" /></a>&nbsp;';
        } else {
            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $strId);
        }
        $strResultado .= '</td>';
        $strResultado .= '</tr>';

    }
    $strResultado .= '</table>';
}
$comboFuncionalidade = MdLitFuncionalidadeINT::montarSelectNome('null', '', $_POST['selFuncionalidade'],null, false);

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

require_once 'md_lit_css_geral.php';

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript(); ?>
<?if(0){?><script><?}?>
    function inicializar() {
        infraEfeitoTabelas();
    }

    function pesquisar(){
    document.getElementById('frmIntegracaoLista').action='<?= $strUrlPesquisar ?>';
    document.getElementById('frmIntegracaoLista').submit();
    }

    function desativar(id, desc) {
    if (confirm("Confirma desativação do Integração \"" + desc + "\"?")) {
    document.getElementById('hdnInfraItemId').value = id;
    document.getElementById('frmIntegracaoLista').action = '<?= $strUrlDesativar ?>';
    document.getElementById('frmIntegracaoLista').submit();
    }
    }

    function reativar(id, desc){
    if (confirm("Confirma reativação do Integração \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmIntegracaoLista').action='<?= $strUrlReativar ?>';
    document.getElementById('frmIntegracaoLista').submit();
    }
    }

    function excluir(id, desc){
    if (confirm("Confirma exclusão do Integração \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmIntegracaoLista').action='<?= $strUrlExcluir ?>';
    document.getElementById('frmIntegracaoLista').submit();
    }
    }

    function novo(){
    location.href="<?= $strUrlNovo ?>";
    }

    function imprimir(){
    infraImprimirTabela();
    }

    function fechar(){
    location.href="<?= $strUrlFechar ?>";
    }
    <?if(0){?></script><?}?>
<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmIntegracaoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('auto');
        ?>


        <div class="grid grid_5">
            <label id="txtNome" for="txtNome" class="infraLabelOpcional">
                Nome:
            </label>
            <input type="text" id="txtNome" name="txtNome" value="<?= $_POST['txtNome'] ?>">
        </div>

        <div class="grid grid_7">
            <label id="lblFuncionalidade" for="selFuncionalidade" class="infraLabelOpcional">
                Funcionalidade:
            </label>
            <select id="selFuncionalidade" name="selFuncionalidade"  onchange="this.form.submit();"   >
                <?= $comboFuncionalidade?>
            </select>
        </div>

        <?php
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

