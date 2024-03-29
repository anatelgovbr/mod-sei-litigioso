<?
/**
 * ANATEL
 *
 * 24/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
 *
 */


try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();


    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_lit_conduta_selecionar');


    switch ($_GET['acao']) {
        case 'md_lit_conduta_excluir':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjCondutaLitigiosoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objCondutaLitigiosoDTO = new MdLitCondutaDTO();
                    $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso($arrStrIds[$i]);
                    $arrObjCondutaLitigiosoDTO[] = $objCondutaLitigiosoDTO;
                }

                $objCondutaLitigiosoRN = new MdLitCondutaRN();
                $objCondutaLitigiosoRN->excluir($arrObjCondutaLitigiosoDTO);

            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_lit_conduta_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjCondutaLitigiosoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objCondutaLitigiosoDTO = new MdLitCondutaDTO();
                    $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso($arrStrIds[$i]);
                    $objCondutaLitigiosoDTO->setStrSinAtivo('S');
                    $arrObjCondutaLitigiosoDTO[] = $objCondutaLitigiosoDTO;
                }
                $objCondutaLitigiosoRN = new MdLitCondutaRN();
                $objCondutaLitigiosoRN->desativar($arrObjCondutaLitigiosoDTO);
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_lit_conduta_reativar':

            $strTitulo = 'Reativar Condutas';

            if ($_GET['acao_confirmada'] == 'sim') {

                try {
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjCondutaLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objCondutaLitigiosoDTO = new MdLitCondutaDTO();
                        $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso($arrStrIds[$i]);
                        $objCondutaLitigiosoDTO->setStrSinAtivo('S');
                        $arrObjCondutaLitigiosoDTO[] = $objCondutaLitigiosoDTO;
                    }
                    $objCondutaLitigiosoRN = new MdLitCondutaRN();
                    $objCondutaLitigiosoRN->reativar($arrObjCondutaLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Opera��o realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;

        case 'md_lit_conduta_selecionar':

            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Conduta', 'Selecionar Condutas');
            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'md_lit_conduta_cadastrar') {
                if (isset($_GET['id_conduta_litigioso'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_conduta_litigioso']);
                }
            }
            break;

        case 'md_lit_conduta_listar':

            $strTitulo = 'Dispositivos Normativos - Condutas';
            break;

        default:
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    }

    $bolAcaoReativarTopo = false;
    $bolAcaoExcluirTopo = false;
    $bolAcaoDesativarTopo = false;
    $hdnIdDispositivo = $_POST['hdnIdDispositivo'] ? $_POST['hdnIdDispositivo'] : '';

    //BOTOES TOPO DA PAGINA
    if ($_GET['acao'] == 'md_lit_conduta_selecionar') {

        //DENTRO DO POP UP
        $bolAcaoReativarTopo = false;
        $bolAcaoExcluirTopo = false;
        $bolAcaoDesativarTopo = false;

    }

    $arrComandos = array();

    $strLinkPesquisar = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=md_lit_dispositivo_normativo_listar'));
    $arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" value="Pesquisar" onclick="filtrarCondutas();" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    if ($_GET['acao'] == 'md_lit_conduta_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    //  if ($_GET['acao'] != 'md_lit_conduta_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_cadastrar');
    //   }

    if ($bolAcaoCadastrar) {
        $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Nova" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }

    $objCondutaLitigiosoDTO = new MdLitCondutaDTO();
    $objCondutaLitigiosoDTO->retTodos();

    PaginaSEI::getInstance()->prepararOrdenacao($objCondutaLitigiosoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objCondutaLitigiosoDTO, 100);

    $objCondutaLitigiosoRN = new MdLitCondutaRN();

    if (isset ($_POST ['txtConduta']) && $_POST ['txtConduta'] != '') {
        //aplicando a pesquisa em estilo LIKE
        $objCondutaLitigiosoDTO->setStrNome('%' . $_POST ['txtConduta'] . '%', InfraDTO::$OPER_LIKE);
    }

    if ($_GET['acao'] == 'md_lit_conduta_selecionar') {
        $objCondutaLitigiosoDTO->setStrSinAtivo('S');
    }

    if ($hdnIdDispositivo != '') {
        $arrIdDispositivo = explode(',', $hdnIdDispositivo);

        $objRelDispositivoNormativoCondutaRN = new MdLitRelDispositivoNormativoCondutaRN ();
        $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO ();

        $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($arrIdDispositivo, InfraDTO::$OPER_IN);
        $objRelDispositivoNormativoCondutaDTO->retNumIdConduta();

        $arrObjRelDispositivoNormativoCondutaDTO = $objRelDispositivoNormativoCondutaRN->listar($objRelDispositivoNormativoCondutaDTO);
        if (count($arrObjRelDispositivoNormativoCondutaDTO)) {
            $arrIdConduta = InfraArray::converterArrInfraDTO($arrObjRelDispositivoNormativoCondutaDTO, 'IdConduta');

            $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso($arrIdConduta, InfraDTO::$OPER_IN);
        }
    }

    $arrObjCondutaLitigiosoDTO = $objCondutaLitigiosoRN->listar($objCondutaLitigiosoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objCondutaLitigiosoDTO);
    $numRegistros = count($arrObjCondutaLitigiosoDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        if ($_GET['acao'] == 'md_lit_conduta_selecionar') {
            $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_cadastrar');;
            $bolAcaoReativar = false;
            $bolAcaoConsultar = false;
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_alterar');
            $bolAcaoImprimir = false;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_excluir');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_desativar');
            $bolAcaoImprimir = false;
            $bolCheck = true;

        } else {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_reativar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_excluir');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_conduta_desativar');
        }


        if ($_GET['acao'] == 'md_lit_conduta_selecionar') {

            if ($bolAcaoDesativarTopo) {
                $bolCheck = true;
                $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_desativar&acao_origem=' . $_GET['acao']);

            }

            if ($bolAcaoReativarTopo) {
                $bolCheck = true;
                $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
            }

            if ($bolAcaoExcluirTopo) {
                $bolCheck = true;
                $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_excluir&acao_origem=' . $_GET['acao']);
            }

        } else {

            if ($bolAcaoDesativar) {
                $bolCheck = true;
                $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_desativar&acao_origem=' . $_GET['acao']);
                $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
            }

            if ($bolAcaoReativar) {
                $bolCheck = true;
                $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
                $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
            }

            if ($bolAcaoExcluir) {
                $bolCheck = true;
                $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_excluir&acao_origem=' . $_GET['acao']);
                $arrComandos[] = '<button type="button" accesskey="x" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">E<span class="infraTeclaAtalho">x</span>cluir</button>';
            }

        }

        $strResultado = '';

        if ($_GET['acao'] != 'md_lit_conduta_reativar') {
            $strSumarioTabela = 'Tabela de Condutas.';
            $strCaptionTabela = 'Condutas';
        } else {
            $strSumarioTabela = 'Tabela de Condutas Inativos.';
            $strCaptionTabela = 'Condutas Inativos';
        }

        $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';

        //Coluna Checkbox
        if ($bolCheck) {

            if ($_GET['acao'] == 'md_lit_conduta_selecionar') {
                $strResultado .= '<th class="infraTh" align="center" width="4%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            } else {
                $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            }

        }

        //Coluna Nome
        if ($_GET['acao'] == 'md_lit_conduta_selecionar') {
            $strResultado .= '<th class="infraTh" width="72%">' . PaginaSEI::getInstance()->getThOrdenacao($objCondutaLitigiosoDTO, 'Nome', 'Nome', $arrObjCondutaLitigiosoDTO) . '</th>' . "\n";
        } else {
            $strResultado .= '<th class="infraTh" width="86%">' . PaginaSEI::getInstance()->getThOrdenacao($objCondutaLitigiosoDTO, 'Nome', 'Nome', $arrObjCondutaLitigiosoDTO) . '</th>' . "\n";
        }

        //coluna A��es
        if ($_GET['acao'] == 'md_lit_conduta_selecionar') {
            $strResultado .= '<th class="infraTh" width="25px">A��es</th>' . "\n";
        } else {
            $strResultado .= '<th class="infraTh" width="20px">A��es</th>' . "\n";
        }

        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            if ($arrObjCondutaLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            } else {
                $strCssTr = '<tr class="trVermelha">';
            }

            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td align="center" valign="middle">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjCondutaLitigiosoDTO[$i]->getNumIdCondutaLitigioso(), $arrObjCondutaLitigiosoDTO[$i]->getStrNome()) . '</td>';
            }
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjCondutaLitigiosoDTO[$i]->getStrNome());
            '</td>';
            $strResultado .= '<td align="center">';
            //$arrObjCondutaLitigiosoDTO[$i]->getNumIdCondutaLitigioso()
            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjCondutaLitigiosoDTO[$i]->getNumIdCondutaLitigioso());

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_conduta_litigioso=' . $arrObjCondutaLitigiosoDTO[$i]->getNumIdCondutaLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg?'.Icone::VERSAO.'" title="Consultar Conduta" alt="Consultar Conduta" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {

                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_conduta_litigioso=' . $arrObjCondutaLitigiosoDTO[$i]->getNumIdCondutaLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg?'.Icone::VERSAO.'" title="Alterar Conduta" alt="Alterar Conduta" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjCondutaLitigiosoDTO[$i]->getNumIdCondutaLitigioso();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjCondutaLitigiosoDTO[$i]->getStrNome(), true));
            }

            if ($bolAcaoDesativar && $arrObjCondutaLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg?'.Icone::VERSAO.'" title="Desativar Conduta" alt="Desativar Conduta" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar && $arrObjCondutaLitigiosoDTO[$i]->getStrSinAtivo() == 'N') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg?'.Icone::VERSAO.'" title="Reativar Conduta" alt="Reativar Conduta" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg?'.Icone::VERSAO.'" title="Excluir Conduta" alt="Excluir Conduta" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }

    if ($bolAcaoImprimir) {
        $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Fechar" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    if ($_GET['acao'] == 'md_lit_conduta_selecionar') {
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
require_once("md_lit_conduta_lista_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmCondutaLitigiosoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <div class="form-group">
                    <label id="lblConduta" for="txtConduta" accesskey="S" class="infraLabelOpcional">Conduta:</label>
                    <input type="text" id="txtConduta" name="txtConduta" class="infraText form-control"
                        value="<?php echo isset($_POST['txtConduta']) ? $_POST['txtConduta'] : '' ?>" maxlength="500"
                        tabindex="502">
                </div>
            </div>
        </div>
        <input type="hidden" id="hdnIdDispositivo" name="hdnIdDispositivo" value="<?= $hdnIdDispositivo ?>"/>
        <?php
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
require_once("md_lit_conduta_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
