<?
/**
 * ANATEL
 *
 * 16/05/2016 - criado por CAST
 *
 * Vers�o do Gerador de C�digo:
 *
 * Vers�o no CVS:
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_lit_obrigacao_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    //Nome da funcionalidade
    $funcionalidade = "Obriga��o";

    switch ($_GET['acao']) {
        case 'md_lit_obrigacao_excluir':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjObrigacaoLitigiosoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objObrigacaoLitigiosoDTO = new MdLitObrigacaoDTO();
                    $objObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso($arrStrIds[$i]);
                    $arrObjObrigacaoLitigiosoDTO[] = $objObrigacaoLitigiosoDTO;
                }
                $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
                $objObrigacaoLitigiosoRN->excluir($arrObjObrigacaoLitigiosoDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;


        case 'md_lit_obrigacao_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjObrigacaoLitigiosoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objObrigacaoLitigiosoDTO = new MdLitObrigacaoDTO();
                    $objObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso($arrStrIds[$i]);
                    $objObrigacaoLitigiosoDTO->setStrSinAtivo('N');
                    $arrObjObrigacaoLitigiosoDTO[] = $objObrigacaoLitigiosoDTO;
                }
                $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
                $objObrigacaoLitigiosoRN->desativar($arrObjObrigacaoLitigiosoDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_lit_obrigacao_reativar':
            $strTitulo = 'Reativar Obriga��o';
            if ($_GET['acao_confirmada'] == 'sim') {
                try {
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjObrigacaoLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objObrigacaoLitigiosoDTO = new MdLitObrigacaoDTO();
                        $objObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso($arrStrIds[$i]);
                        $objObrigacaoLitigiosoDTO->setStrSinAtivo('S');
                        $arrObjObrigacaoLitigiosoDTO[] = $objObrigacaoLitigiosoDTO;
                    }
                    $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
                    $objObrigacaoLitigiosoRN->reativar($arrObjObrigacaoLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_obrigacao_litigioso=' . $arrStrIds[0] . '&acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;


        case 'md_lit_obrigacao_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Obriga��o', 'Selecionar Obriga��es');

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'md_lit_obrigacao_alterar') {
                if (isset($_GET['id_obrigacao_litigioso'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_obrigacao_litigioso']);
                }
            }
            break;

        case 'md_lit_obrigacao_listar':
            $strTitulo = 'Obriga��es';
            break;

        default:
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    }

    $arrComandos = array();

    $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    if ($_GET['acao'] == 'md_lit_obrigacao_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    if ($_GET['acao'] == 'md_lit_obrigacao_listar') {
        $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_alterar');
        if ($bolAcaoCadastrar) {
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Nova" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
        }
    }

    $objObrigacaoLitigiosoDTO = new MdLitObrigacaoDTO();
    $objObrigacaoLitigiosoDTO->retNumIdObrigacaoLitigioso();
    $objObrigacaoLitigiosoDTO->retStrNome();
    $objObrigacaoLitigiosoDTO->retStrSinAtivo();

    $objObrigacaoLitigiosoDTO->setBolExclusaoLogica(false);

    if (isset($_POST['txtNomeObrigacaoPesquisa'])) {
        //$strNomeObrigacaoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtNomeObrigacaoPesquisa');
        $strNomeObrigacaoPesquisa = $_POST['txtNomeObrigacaoPesquisa'];
    } else {
        $strNomeObrigacaoPesquisa = "";
    }

    if (trim($strNomeObrigacaoPesquisa) != '') {
        $objObrigacaoLitigiosoDTO->setStrNome('%' . trim($strNomeObrigacaoPesquisa . '%'), InfraDTO::$OPER_LIKE);
    }

    if ($_GET['acao'] == 'md_lit_obrigacao_selecionar') {
        $objObrigacaoLitigiosoDTO->setStrSinAtivo('S');
    }

    PaginaSEI::getInstance()->prepararOrdenacao($objObrigacaoLitigiosoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, true);
    PaginaSEI::getInstance()->prepararPaginacao($objObrigacaoLitigiosoDTO, 200);

    $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
    $arrObjObrigacaoLitigiosoDTO = $objObrigacaoLitigiosoRN->listar($objObrigacaoLitigiosoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objObrigacaoLitigiosoDTO);
    $numRegistros = count($arrObjObrigacaoLitigiosoDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        if ($_GET['acao'] == 'md_lit_obrigacao_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = false;
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
        } else if ($_GET['acao'] == 'md_lit_obrigacao_reativar') {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_reativar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_consultar');
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_excluir');
            $bolAcaoDesativar = false;
        } else {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_reativar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_excluir');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_desativar');
        }


        if ($bolAcaoDesativar) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_desativar&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoReativar) {
            $bolCheck = true;
            $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
        }


        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_excluir&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoImprimir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="Imprimir" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        $strResultado = '';

        if ($_GET['acao'] != 'md_lit_obrigacao_reativar') {
            $strSumarioTabela = 'Tabela de Obriga��es.';
            $strCaptionTabela = 'Obriga��es';
        } else {
            $strSumarioTabela = 'Tabela de Obriga��es Inativas.';
            $strCaptionTabela = 'Obriga��es Inativas';
        }

        $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objObrigacaoLitigiosoDTO, 'Obriga��o', 'Nome', $arrObjObrigacaoLitigiosoDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="15%">A��es</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            if ($_GET['acao_origem'] == 'md_lit_obrigacao_reativar' && $_GET['id_obrigacao_litigioso'] == $arrObjObrigacaoLitigiosoDTO[$i]->getNumIdObrigacaoLitigioso()) {
                $strCssTr = '<tr class="infraTrAcessada">';
            } else {
                if ($arrObjObrigacaoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                } else {
                    $strCssTr = '<tr class="trVermelha">';
                }
            }

            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjObrigacaoLitigiosoDTO[$i]->getNumIdObrigacaoLitigioso(), PaginaSEI::tratarHTML($arrObjObrigacaoLitigiosoDTO[$i]->getStrNome())) . '</td>';
            }
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjObrigacaoLitigiosoDTO[$i]->getStrNome()) . '</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjObrigacaoLitigiosoDTO[$i]->getNumIdObrigacaoLitigioso());

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_obrigacao_litigioso=' . $arrObjObrigacaoLitigiosoDTO[$i]->getNumIdObrigacaoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Obriga��o" alt="Consultar Obriga��o" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_obrigacao_litigioso=' . $arrObjObrigacaoLitigiosoDTO[$i]->getNumIdObrigacaoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Obriga��o" alt="Alterar Obriga��o" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjObrigacaoLitigiosoDTO[$i]->getNumIdObrigacaoLitigioso();
                $strDescricao = PaginaSEI::tratarHTML($arrObjObrigacaoLitigiosoDTO[$i]->getStrNome());
            }

            if ($bolAcaoDesativar && $arrObjObrigacaoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Obriga��o" alt="Desativar ' . $funcionalidade . '" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar && $arrObjObrigacaoLitigiosoDTO[$i]->getStrSinAtivo() == 'N') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Obriga��o" alt="Reativar ' . $funcionalidade . '" class="infraImg" /></a>&nbsp;';
            }


            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Obriga��o" alt="Excluir ' . $funcionalidade . '" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'md_lit_obrigacao_selecionar') {

        $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    } else {
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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
require_once("md_lit_obrigacao_lista_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmObrigacaoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row linha">
            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <label id="lblNomeObrigacaoPesquisa" accesskey="o" for="txtNomeObrigacaoPesquisa"
                       class="infraLabelOpcional"><span class="infraTeclaAtalho">O</span>briga��o:</label>
                <input type="text" id="txtNomeObrigacaoPesquisa" name="txtNomeObrigacaoPesquisa"
                       value="<?= $strNomeObrigacaoPesquisa ?>" class="infraText form-control"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <?
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
require_once("md_lit_obrigacao_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>