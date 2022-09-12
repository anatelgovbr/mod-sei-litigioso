<?php

/**
 * ANATEL
 *
 * 18/04/2018 - criado por ellyson.silva
 *
 * Versão do Gerador de Código: 1.41.0
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_lit_situacao_lancamento_selecionar');

    //URL Base
    $strUrl = 'controlador.php?acao=md_lit_situacao_lancamento_';

    //URL das Actions
    $strUrlDesativar = SessaoSEI::getInstance()->assinarLink($strUrl . 'desativar&acao_origem=' . $_GET['acao']);
    $strUrlReativar = SessaoSEI::getInstance()->assinarLink($strUrl . 'reativar&acao_origem=' . $_GET['acao']);
    $strUrlExcluir = SessaoSEI::getInstance()->assinarLink($strUrl . 'excluir&acao_origem=' . $_GET['acao']);
    $strUrlNovo = SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
    $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);
    $strUrlAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']);

    $strTitulo = 'Lista de Situações do Lançamento de Crédito';

    switch ($_GET['acao']) {

        case 'md_lit_situacao_lancamento_listar':


            break;

        case 'md_lit_situacao_lancamento_desativar':
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdLitSituacaoLancamentoDTO = array();

            foreach ($arrStrIds as $strId) {
                $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($strId);
                $objMdLitSituacaoLancamentoDTO->setStrSinAtivo('N');
                $arrObjMdLitSituacaoLancamentoDTO[] = $objMdLitSituacaoLancamentoDTO;
            }
            $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
            $objMdLitSituacaoLancamentoRN->desativar($arrObjMdLitSituacaoLancamentoDTO);

            break;


        case 'md_lit_situacao_lancamento_reativar':
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdLitSituacaoLancamentoDTO = array();

            foreach ($arrStrIds as $strId) {
                $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($strId);
                $objMdLitSituacaoLancamentoDTO->setStrSinAtivo('S');
                $arrObjMdLitSituacaoLancamentoDTO[] = $objMdLitSituacaoLancamentoDTO;
            }
            $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
            $objMdLitSituacaoLancamentoRN->reativar($arrObjMdLitSituacaoLancamentoDTO);

            break;

        case 'md_lit_situacao_lancamento_excluir':
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdLitSituacaoLancamentoDTO = array();

            foreach ($arrStrIds as $strId) {
                $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($strId);
                $arrObjMdLitSituacaoLancamentoDTO[] = $objMdLitSituacaoLancamentoDTO;
            }
            $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
            $objMdLitSituacaoLancamentoRN->excluir($arrObjMdLitSituacaoLancamentoDTO);

            break;
        case 'md_lit_situacao_lancamento_selecionar':
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}


//Verifica se ? a??o Selecionar
$bolSelecionar = $_GET['acao'] == 'md_lit_situacao_lancamento_selecionar';


//Botões de ação do topo
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


//Consulta
$objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
$objMdLitSituacaoLancamentoDTO->retTodos(true);
$objMdLitSituacaoLancamentoDTO->setStrSinAtivoIntegracao('S');

//Filtros
if (isset($_POST['txtCodigo']) && trim($_POST['txtCodigo']) != '') {
    $objMdLitSituacaoLancamentoDTO->setStrCodigo(trim('%' . $_POST['txtCodigo'] . '%'), InfraDTO::$OPER_LIKE);
}

if (isset($_POST['selOrigem']) && trim($_POST['selOrigem']) != '') {
    $objMdLitSituacaoLancamentoDTO->setStrStaOrigem(trim($_POST['selOrigem']));

}

if (isset($_POST['txtNomeIntegracao']) && trim($_POST['txtNomeIntegracao']) != '') {
    $objMdLitSituacaoLancamentoDTO->setStrNomeIntegracao(trim('%' . $_POST['txtNomeIntegracao'] . '%'), InfraDTO::$OPER_LIKE);
    $objMdLitSituacaoLancamentoDTO->setStrStaOrigem(MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO);
}


$objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();


//Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdLitSituacaoLancamentoDTO, 'Codigo', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdLitSituacaoLancamentoDTO);


$arrObjMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->listar($objMdLitSituacaoLancamentoDTO);

PaginaSEI::getInstance()->processarPaginacao($objMdLitSituacaoLancamentoDTO);
$numRegistros = count($arrObjMdLitSituacaoLancamentoDTO);


//Tabela de resultado.
if ($numRegistros > 0) {

    $strResultado .= '<table width="99%" class="infraTable" summary="Situação de Lançamentos">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Situação de Lançamentos', $numRegistros);
    $strResultado .= '</caption>';

    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';

    $strResultado .= '<th class="infraTh" width="10%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitSituacaoLancamentoDTO, 'Código', 'Codigo', $arrObjMdLitSituacaoLancamentoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="35%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitSituacaoLancamentoDTO, 'Descrição', 'Nome', $arrObjMdLitSituacaoLancamentoDTO);
    $strResultado .= '</th>';
    if (!$bolSelecionar) {
        $strResultado .= '<th class="infraTh" width="10%">';
        $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitSituacaoLancamentoDTO, 'Origem', 'StaOrigem', $arrObjMdLitSituacaoLancamentoDTO);
        $strResultado .= '</th>';

        $strResultado .= '<th class="infraTh" width="20%">';
        $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitSituacaoLancamentoDTO, 'Nome da Integraçao', 'NomeIntegracao', $arrObjMdLitSituacaoLancamentoDTO);
        $strResultado .= '</th>';
    }

    $strResultado .= '<th class="infraTh" width="15%">Ações</th>';
    $strResultado .= '</tr>';


    $strCssTr = '<tr class="infraTrEscura">';

    for ($i = 0; $i < $numRegistros; $i++) {

        $strId = $arrObjMdLitSituacaoLancamentoDTO[$i]->getNumIdMdLitSituacaoLancamento();
        $strCodigo = $arrObjMdLitSituacaoLancamentoDTO[$i]->getNumCodigo();
        $strNome = $arrObjMdLitSituacaoLancamentoDTO[$i]->getStrNome();
        $strOrigem = $arrObjMdLitSituacaoLancamentoDTO[$i]->getStrStaOrigem() == 'M' ? 'Manual' : 'Integração';
        $strNomeIntegracao = $arrObjMdLitSituacaoLancamentoDTO[$i]->getStrNomeIntegracao();

        $strNomeParametro = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdLitSituacaoLancamentoDTO[$i]->getStrNome());
        $bolRegistroAtivo = $arrObjMdLitSituacaoLancamentoDTO[$i]->getStrSinAtivo() == 'S';

        $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
        $strResultado .= $strCssTr;

        $strResultado .= '<td align="center" valign="middle">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strNome);
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strCodigo);
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($strNome);
        $strResultado .= '</td>';

        if (!$bolSelecionar) {
            $strResultado .= '<td>';
            $strResultado .= PaginaSEI::tratarHTML($strOrigem);
            $strResultado .= '</td>';

            $strResultado .= '<td>';
            $strResultado .= PaginaSEI::tratarHTML($strNomeIntegracao);
            $strResultado .= '</td>';
        }


        $strResultado .= '<td align="center">';

        if (!$bolSelecionar) {

            //Ação Consultar
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink($strUrl . 'consultar&acao_origem=' . $_GET['acao'] . '&id_md_lit_situacao=' . $strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Situação de Lançamento" alt="Consultar Situação de Lançamento" class="infraImg" /></a>&nbsp;';

            if ($arrObjMdLitSituacaoLancamentoDTO[$i]->getStrStaOrigem() == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL && $bolRegistroAtivo) {
                //Ação Alterar
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink($strUrl . 'alterar&acao_origem=' . $_GET['acao'] . '&id_md_lit_situacao=' . $strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Situação de Lançamento" alt="Alterar  Situação de Lançamento" class="infraImg" /></a>&nbsp;';
            } elseif ($arrObjMdLitSituacaoLancamentoDTO[$i]->getStrStaOrigem() == MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO && $bolRegistroAtivo) {
                //Ação Alterar
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink($strUrl . 'alterar&acao_origem=' . $_GET['acao'] . '&id_md_lit_situacao=' . $strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/litigioso/imagens/png/integracao.png" title="Alterar Situação de Lançamento" alt="Alterar Situação de Lançamento" class="infraImgModulo" /></a>&nbsp;';
            }

            if ($bolRegistroAtivo) {
                //Ação Desativar
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="desativar(\'' . $strId . '\',\'' . $strNomeParametro . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Situação de Lançamento" alt="Desativar Situação de Lançamento" class="infraImg" /></a>&nbsp;';
            } else {
                //Ação Reativar
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="reativar(\'' . $strId . '\',\'' . $strNomeParametro . '\', \'' . SessaoSEI::getInstance()->assinarLink($strUrl . "reativar&acao_origem=" . $_GET["acao"] . PaginaSEI::getInstance()->montarAncora($strId)) . '\')" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Situação de Lançamento" alt="Reativar Situação de Lançamento" class="infraImg" /></a>&nbsp;';
            }

            if ($arrObjMdLitSituacaoLancamentoDTO[$i]->getStrStaOrigem() == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL) {
                //Ação Excluir
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="excluir(\'' . $strId . '\',\'' . $strNomeParametro . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Situação de Lançamento" alt="Excluir Situação de Lançamento" class="infraImg" /></a>&nbsp;';
            }

        } else {
            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $strId);
        }
        $strResultado .= '</td>';
        $strResultado .= '</tr>';

    }
    $strResultado .= '</table>';
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
require_once("md_lit_situacao_lancamento_lista_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmSituacaoLancamentoLista" method="post" action="<?= $strUrlAcaoForm ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                <div class="form-group">
                    <label id="lblCodigo" for="txtCodigo" class="infraLabelOpcional">
                        Código:
                    </label>
                    <input type="text" id="txtCodigo" name="txtCodigo" class="infraText form-control" maxlength="10"
                            value="<?= $_POST['txtCodigo'] ?>">
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                <div class="form-group">
                    <label id="lblNome" for="txtNome" class="infraLabelOpcional">
                        Descrição:
                    </label>
                    <input type="text" id="txtNome" name="txtNome" maxlength="100" class="infraText form-control"
                            value="<?= $_POST['txtNome'] ?>">
                </div>
            </div>
            <?php if (!$bolSelecionar) { ?>
                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="lblOrigem" for="selOrigem" class="infraLabelOpcional">
                            Origem:
                        </label>
                        <select id="selOrigem" name="selOrigem" onchange="pesquisar()"
                                class="infraSelect form-control">
                            <option value="">&nbsp;</option>
                            <option value="<?= MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL ?>"
                                <?= $_POST['selOrigem'] == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL ? 'selected="selected"' : '' ?>>
                                Manual
                            </option>
                            <option value="<?= MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO ?>"
                                <?= $_POST['selOrigem'] == MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO ? 'selected="selected"' : '' ?>>
                                Integração
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                    <div class="form-group">
                        <label id="lblNomeIntegracao" for="txtNomeIntegracao" class="infraLabelOpcional">
                            Nome da Integração:
                        </label>
                        <input type="text" id="txtNomeIntegracao" name="txtNomeIntegracao"
                                class="infraText form-control"
                                value="<?= $_POST['txtNomeIntegracao'] ?>">
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
require_once("md_lit_situacao_lancamento_lista_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

