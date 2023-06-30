<?
/**
 * ANATEL
 *
 * 20/05/2016 - criado por alan.campos@castgroup.com.br - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_lit_tipo_decisao_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    PaginaSEI::getInstance()->salvarCamposPost(array('txtTipoDecisao'));

    switch ($_GET['acao']) {
        case 'md_lit_tipo_decisao_excluir':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjTipoDecisaoLitigiosoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objTipoDecisaoLitigiosoDTO = new MdLitTipoDecisaoDTO();
                    $objTipoDecisaoLitigiosoDTO->setNumIdTipoDecisaoLitigioso($arrStrIds[$i]);
                    $arrObjTipoDecisaoLitigiosoDTO[] = $objTipoDecisaoLitigiosoDTO;
                }
                $objTipoDecisaoLitigiosoRN = new MdLitTipoDecisaoRN();
                $objTipoDecisaoLitigiosoRN->excluir($arrObjTipoDecisaoLitigiosoDTO);
                PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;


        case 'md_lit_tipo_decisao_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjTipoDecisaoLitigiosoDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objTipoDecisaoLitigiosoDTO = new MdLitTipoDecisaoDTO();
                    $objTipoDecisaoLitigiosoDTO->setNumIdTipoDecisaoLitigioso($arrStrIds[$i]);
                    $arrObjTipoDecisaoLitigiosoDTO[] = $objTipoDecisaoLitigiosoDTO;
                }
                $objTipoDecisaoLitigiosoRN = new MdLitTipoDecisaoRN();
                $objTipoDecisaoLitigiosoRN->desativar($arrObjTipoDecisaoLitigiosoDTO);
                PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_lit_tipo_decisao_reativar':
            $strTitulo = 'Reativar Tipo de Decisão';
            if ($_GET['acao_confirmada'] == 'sim') {
                try {
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjTipoDecisaoLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objTipoDecisaoLitigiosoDTO = new MdLitTipoDecisaoDTO();
                        $objTipoDecisaoLitigiosoDTO->setNumIdTipoDecisaoLitigioso($arrStrIds[$i]);
                        $arrObjTipoDecisaoLitigiosoDTO[] = $objTipoDecisaoLitigiosoDTO;
                    }

                    $objTipoDecisaoLitigiosoRN = new MdLitTipoDecisaoRN();
                    $objTipoDecisaoLitigiosoRN->reativar($arrObjTipoDecisaoLitigiosoDTO);
                    PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . '&id_tipo_decisao_litigioso=' . $arrStrIds[0]));
                die;
            }
            break;

        case 'md_lit_tipo_decisao_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Decisão', 'Selecionar Tipos de Decisão');

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'md_lit_tipo_decisao_cadastrar') {
                if (isset($_GET['id_tipo_procedimento'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tipo_procedimento']);
                }
            }
            break;

        case 'md_lit_tipo_decisao_listar':
            $strTitulo = 'Tipos de Decisão';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();

    $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    if ($_GET['acao'] == 'md_lit_tipo_decisao_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    if ($_GET['acao'] == 'md_lit_tipo_decisao_listar' /*|| $_GET['acao'] == 'md_lit_tipo_decisao_selecionar'*/) {
        $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_decisao_cadastrar');
        if ($bolAcaoCadastrar) {
            $arrComandos[] = '<button type="button" accesskey="S" id="btnNovo" value="EspecieDecisao" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_especie_decisao_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton">E<span class="infraTeclaAtalho">s</span>pécies de Decisão</button>';
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
        }
    }

    $objTipoDecisaoLitigiosoDTO = new MdLitTipoDecisaoDTO();
    $objTipoDecisaoLitigiosoDTO->retTodos();

    if ($_GET['acao'] == 'md_lit_tipo_decisao_reativar') {
        //Lista somente inativos
        $objTipoDecisaoLitigiosoDTO->setBolExclusaoLogica(false);
        $objTipoDecisaoLitigiosoDTO->setStrSinAtivo('N');
    }

    if ($_GET['acao'] == 'md_lit_tipo_decisao_selecionar') {
        $objTipoDecisaoLitigiosoDTO->setStrSinAtivo('S');
    }

    $strNomeTipoDecisaoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtTipoDecisao');
    if (trim($strNomeTipoDecisaoPesquisa) != '') {
        $objTipoDecisaoLitigiosoDTO->setStrNome('%' . trim($strNomeTipoDecisaoPesquisa . '%'), InfraDTO::$OPER_LIKE);
    }

    PaginaSEI::getInstance()->prepararOrdenacao($objTipoDecisaoLitigiosoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objTipoDecisaoLitigiosoDTO, 200);

    $objTipoDecisaoLitigiosoRN = new MdLitTipoDecisaoRN();
    $objTipoDecisaoLitigiosoDTO->setBolExclusaoLogica(false);
    $arrObjTipoDecisaoLitigiosoDTO = $objTipoDecisaoLitigiosoRN->listar($objTipoDecisaoLitigiosoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objTipoDecisaoLitigiosoDTO);
    $numRegistros = count($arrObjTipoDecisaoLitigiosoDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        if ($_GET['acao'] == 'md_lit_tipo_decisao_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoImprimir = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
        } else if ($_GET['acao'] == 'md_lit_tipo_decisao_reativar') {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_decisao_reativar');
            $bolAcaoConsultar = false;
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_decisao_excluir');
            $bolAcaoDesativar = false;
        } else {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_decisao_reativar');
            $bolAcaoAdicionar = SessaoSEI::getInstance()->verificarPermissao('tipo_decisao_litigioso_adicionar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_decisao_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_decisao_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_decisao_excluir');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_decisao_desativar');
        }

        if ($bolAcaoDesativar) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="T" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_desativar&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoReativar) {
            $bolCheck = true;
            $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
        }

        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_excluir&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoImprimir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="Imprimir" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        $strResultado = '';

        if ($_GET['acao'] != 'md_lit_tipo_decisao_reativar') {
            $strSumarioTabela = 'Tabela de Tipos de Decisão.';
            $strCaptionTabela = 'Tipos de Decisão';
        } else {
            $strSumarioTabela = 'Tabela de Tipos de Decisão Inativos.';
            $strCaptionTabela = 'Tipos de Decisão Inativos';
        }

        $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n"; //70
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objTipoDecisaoLitigiosoDTO, 'Tipos de Decisão', 'Nome', $arrObjTipoDecisaoLitigiosoDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            if ($_GET['acao_origem'] == 'md_lit_tipo_decisao_reativar' && $_GET['id_tipo_decisao_litigioso'] == $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso()) {
                $strCssTr = '<tr class="infraTrAcessada">';
            } else {

                if ($arrObjTipoDecisaoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                } else {
                    $strCssTr = '<tr class="trVermelha">';
                }

            }


            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso(), $arrObjTipoDecisaoLitigiosoDTO[$i]->getStrNome()) . '</td>';
            }

            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjTipoDecisaoLitigiosoDTO[$i]->getStrNome()) .
                '</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso());

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_decisao_litigioso=' . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg?'.Icone::VERSAO.'" title="Consultar Tipo de Decisão" alt="Consultar Tipo de Decisão" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_decisao_litigioso=' . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg?'.Icone::VERSAO.'" title="Alterar Tipo de Decisão" alt="Alterar Tipo de Decisão" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar && $arrObjTipoDecisaoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="#ID-' . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso() . '"  onclick="acaoDesativar(\'' . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso() . '\',\'' . PaginaSEI::tratarHTML($arrObjTipoDecisaoLitigiosoDTO[$i]->getStrNome()) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg?'.Icone::VERSAO.'" title="Desativar Tipo de Decisão" alt="Desativar Tipo de Decisão" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar && $arrObjTipoDecisaoLitigiosoDTO[$i]->getStrSinAtivo() == 'N') {
                $strResultado .= '<a href="#ID-' . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso() . '"  onclick="acaoReativar(\'' . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso() . '\',\'' . PaginaSEI::tratarHTML($arrObjTipoDecisaoLitigiosoDTO[$i]->getStrNome()) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg?'.Icone::VERSAO.'" title="Reativar Tipo de Decisão" alt="Reativar Tipo de Decisão" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="#ID-' . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso() . '"  onclick="acaoExcluir(\'' . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso() . '\',\'' . PaginaSEI::tratarHTML($arrObjTipoDecisaoLitigiosoDTO[$i]->getStrNome()) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg?'.Icone::VERSAO.'" title="Excluir Tipo de Decisão" alt="Excluir Tipo de Decisão" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'md_lit_tipo_decisao_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    } else {
        //$arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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
require_once("md_lit_tipo_decisao_lista_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTipoDecisaoLitigiosoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row linha">
            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <label id="lblTipoDecisao" for="txtTipoDecisao" class="infraLabelOpcional">
                    Tipos de Decisão:</label>
                <input type="text" id="txtTipoDecisao" name="txtTipoDecisao" value="<?= $strNomeTipoDecisaoPesquisa ?>"
                       class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <?
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
require_once("md_lit_tipo_decisao_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>