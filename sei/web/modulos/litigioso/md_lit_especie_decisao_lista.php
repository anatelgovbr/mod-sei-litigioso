<?
/**
 * ANATEL
 *
 * 20/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST
 *
 */


try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_lit_especie_decisao_selecionar');

    $arrIdsEspecieLitigiosoReativarDTO = array();

    switch ($_GET['acao']) {
        case 'md_lit_especie_decisao_excluir':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjEspecieLitigiosoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objEspecieLitigiosoDTO = new MdLitEspecieDecisaoDTO();
                    $objEspecieLitigiosoDTO->setNumIdEspecieLitigioso($arrStrIds[$i]);
                    $arrObjEspecieLitigiosoDTO[] = $objEspecieLitigiosoDTO;
                }

                $objEspecieLitigiosoRN = new MdLitEspecieDecisaoRN();
                $objEspecieLitigiosoRN->excluir($arrObjEspecieLitigiosoDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');

            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_especie_decisao_litigioso=' . $_GET['id_especie_decisao_litigioso'] . '&acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_lit_especie_decisao_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjEspecieLitigiosoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objEspecieLitigiosoDTO = new MdLitEspecieDecisaoDTO();
                    $objEspecieLitigiosoDTO->setNumIdEspecieLitigioso($arrStrIds[$i]);
                    $objEspecieLitigiosoDTO->setStrSinAtivo('N');
                    $arrObjEspecieLitigiosoDTO[] = $objEspecieLitigiosoDTO;
                }
                $objEspecieLitigiosoRN = new MdLitEspecieDecisaoRN();
                $objEspecieLitigiosoRN->desativar($arrObjEspecieLitigiosoDTO);
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_especie_decisao_litigioso=' . $_GET['id_especie_decisao_litigioso'] . '&acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_lit_especie_decisao_reativar':

            $strTitulo = 'Reativar Especies';

            if ($_GET['acao_confirmada'] == 'sim') {

                try {
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjEspecieLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objEspecieLitigiosoDTO = new MdLitEspecieDecisaoDTO();
                        $objEspecieLitigiosoDTO->setNumIdEspecieLitigioso($arrStrIds[$i]);
                        $objEspecieLitigiosoDTO->setStrSinAtivo('N');
                        $arrObjEspecieLitigiosoDTO[] = $objEspecieLitigiosoDTO;
                    }
                    $objEspecieLitigiosoRN = new MdLitEspecieDecisaoRN();
                    $objEspecieLitigiosoRN->reativar($arrObjEspecieLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }

                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_especie_decisao_litigioso=' . $arrStrIds[0] . '&acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;

        case 'md_lit_especie_decisao_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Esp�cie', 'Selecionar Esp�cies');

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'md_lit_especie_decisao_cadastrar') {
                if (isset($_GET['id_especie_decisao_litigioso'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_especie_decisao_litigioso']);
                }
            }
            break;

        case 'md_lit_especie_decisao_listar':

            $strTitulo = 'Esp�cies de Decis�o';
            break;

        default:
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    }

    $arrComandos = array();
    if ($_GET['acao'] == 'md_lit_especie_decisao_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    $bolAcaoPesquisar = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_listar');
    if ($bolAcaoPesquisar) {
        $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
    }

    $bolAcaoObrigacoes = SessaoSEI::getInstance()->verificarPermissao('md_lit_obrigacao_listar');
    if ($bolAcaoObrigacoes && $_GET['acao'] != 'md_lit_especie_decisao_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="O" id="btnObrigacoes" value="Obriga��es" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'"  class="infraButton"><span class="infraTeclaAtalho">O</span>briga��es</button>';
    }

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_cadastrar');
    if ($bolAcaoCadastrar && $_GET['acao'] != 'md_lit_especie_decisao_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Nova" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_especie_decisao_cadastrar&acao_origem=' . $_GET['acao'] . '&id_especie_decisao_litigioso=' . $_GET['id_especie_decisao_litigioso'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }


    $objEspecieLitigiosoDTO = new MdLitEspecieDecisaoDTO();


    $objEspecieLitigiosoDTO->retNumIdEspecieLitigioso();
    $objEspecieLitigiosoDTO->retStrNome();
    $objEspecieLitigiosoDTO->retStrSinAtivo();

    if ($_GET['acao'] == 'md_lit_especie_decisao_selecionar') {
        $objEspecieLitigiosoDTO->setStrSinAtivo('S');
    }

    PaginaSEI::getInstance()->prepararOrdenacao($objEspecieLitigiosoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objEspecieLitigiosoDTO, 200);


    $objEspecieLitigiosoRN = new MdLitEspecieDecisaoRN();

    if ($_POST['txtEspecieDecisao']) {
        $objEspecieLitigiosoDTO->setStrNome('%' . $_POST ['txtEspecieDecisao'] . '%', InfraDTO::$OPER_LIKE);
    }

    $arrObjEspecieLitigiosoDTO = $objEspecieLitigiosoRN->listar($objEspecieLitigiosoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objEspecieLitigiosoDTO);
    $numRegistros = count($arrObjEspecieLitigiosoDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        if ($_GET['acao'] == 'md_lit_especie_decisao_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoImprimir = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
            $bolValidarTiposMulta = true;
        } else if ($_GET['acao'] == 'md_lit_especie_decisao_reativar') {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_reativar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_consultar');
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_excluir');
            $bolAcaoDesativar = false;
        } else {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_excluir');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_especie_decisao_desativar');
        }

        if ($bolAcaoDesativar) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?id_especie_decisao_litigioso=' . $_GET['id_especie_decisao_litigioso'] . '&acao=md_lit_especie_decisao_desativar&acao_origem=' . $_GET['acao']);
        }

        $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_especie_decisao_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');

        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?id_especie_decisao_litigioso=' . $_GET['id_especie_decisao_litigioso'] . '&acao=md_lit_especie_decisao_excluir&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoImprimir) {
            $arrComandos[] = '<button type="button" accesskey="F" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        $strResultado = '';

        if ($_GET['acao'] != 'md_lit_especie_decisao_reativar') {
            $strSumarioTabela = 'Tabela de Esp�cies de Decis�o.';
            $strCaptionTabela = 'Esp�cies de Decis�o';
        } else {
            $strSumarioTabela = 'Tabela de Especies Inativos.';
            $strCaptionTabela = 'Esp�cies Inativos';
        }

        $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objEspecieLitigiosoDTO, 'Esp�cie de Decis�o', 'Nome', $arrObjEspecieLitigiosoDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="15%">A��es</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            if ($_GET['acao_origem'] == 'md_lit_especie_decisao_reativar' && $_GET['id_especie_decisao_litigioso'] == $arrObjEspecieLitigiosoDTO[$i]->getNumIdEspecieLitigioso()) {
                $strCssTr = '<tr class="infraTrAcessada">';
            } else {
                if ($arrObjEspecieLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                } else {
                    $strCssTr = '<tr class="trVermelha">';
                }
            }

            $strResultado .= $strCssTr;

            if ($bolCheck) {
                if ($bolValidarTiposMulta) {
                    //concatena  o evento ao click ao componente para validar a selec��o
                    $strAtributos = 'onclick="return isValidSelecao()"';
                }
                $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjEspecieLitigiosoDTO[$i]->getNumIdEspecieLitigioso(), $arrObjEspecieLitigiosoDTO[$i]->getStrNome(), 'N', 'Infra', $strAtributos) . '</td>';
            }
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjEspecieLitigiosoDTO[$i]->getStrNome()) . '</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjEspecieLitigiosoDTO[$i]->getNumIdEspecieLitigioso());

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_especie_decisao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_especie_decisao_litigioso=' . $arrObjEspecieLitigiosoDTO[$i]->getNumIdEspecieLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Esp�cie de Decis�o" alt="Consultar Esp�cie de Decis�o" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {

                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_especie_decisao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_especie_decisao_litigioso=' . $arrObjEspecieLitigiosoDTO[$i]->getNumIdEspecieLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Esp�cie de Decis�o" alt="Alterar Esp�cie de Decis�o" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjEspecieLitigiosoDTO[$i]->getNumIdEspecieLitigioso();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjEspecieLitigiosoDTO[$i]->getStrNome(), true));
            }

            if ($bolAcaoDesativar && $arrObjEspecieLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\', \'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Esp�cie de Decis�o" alt="Desativar Esp�cie de Decis�o" class="infraImg" /></a>&nbsp;';
            } else if ($bolAcaoDesativar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\', \'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Esp�cie de Decis�o" alt="Reativar Esp�cie de Decis�o" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Esp�cie de Decis�o" alt="Excluir Esp�cie de Decis�o" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'md_lit_especie_decisao_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    } else {
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_especie_decisao_litigioso=' . $_GET['id_especie_decisao_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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
require_once("md_lit_especie_decisao_lista_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmEspecieLitigiosoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row linha">
            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <label id="lblEspecieDecisao" for="txtEspecieDecisao" accesskey="S" class="infraLabelOpcional">Esp�cie
                    de
                    Decis�o:</label>
                <input type="text" id="txtEspecieDecisao" name="txtEspecieDecisao" class="infraText form-control"
                       value="<?php echo isset($_POST['txtEspecieDecisao']) ? $_POST['txtEspecieDecisao'] : '' ?>"
                       tabindex="502">
            </div>
        </div>
        <?php
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
require_once("md_lit_especie_decisao_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>