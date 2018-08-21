<?php

/**
 * ANATEL
 *
 * 18/04/2018 - criado por ellyson.silva
 *
 * Vers�o do Gerador de C�digo: 1.41.0
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
    $strUrlReativar  = SessaoSEI::getInstance()->assinarLink($strUrl . 'reativar&acao_origem=' . $_GET['acao']);
    $strUrlExcluir   = SessaoSEI::getInstance()->assinarLink($strUrl . 'excluir&acao_origem=' . $_GET['acao']);
    $strUrlNovo      = SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
    $strUrlFechar    = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);
    $strUrlAcaoForm  = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']);

    $strTitulo = 'Lista de Situa��es do Lan�amento de Cr�dito';

    switch ($_GET['acao']) {

        case 'md_lit_situacao_lancamento_listar':


            break;

        case 'md_lit_situacao_lancamento_desativar':
            $arrStrIds             = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdLitSituacaoLancamentoDTO = array();

            foreach ($arrStrIds as $strId) {
                $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($strId);
                $arrObjMdLitSituacaoLancamentoDTO[] = $objMdLitSituacaoLancamentoDTO;
            }
            $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
            $objMdLitSituacaoLancamentoRN->desativar($arrObjMdLitSituacaoLancamentoDTO);

            break;


        case 'md_lit_situacao_lancamento_reativar':
            $arrStrIds             = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdLitSituacaoLancamentoDTO = array();

            foreach ($arrStrIds as $strId) {
                $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($strId);
                $arrObjMdLitSituacaoLancamentoDTO[] = $objMdLitSituacaoLancamentoDTO;
            }
            $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
            $objMdLitSituacaoLancamentoRN->reativar($arrObjMdLitSituacaoLancamentoDTO);

            break;

        case 'md_lit_situacao_lancamento_excluir':
            $arrStrIds             = PaginaSEI::getInstance()->getArrStrItensSelecionados();
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
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    }

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}


//Verifica se ? a??o Selecionar
$bolSelecionar = $_GET['acao'] == 'md_lit_situacao_lancamento_selecionar';


//Bot�es de a��o do topo
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


//Configura��o da Pagina��o
PaginaSEI::getInstance()->prepararOrdenacao($objMdLitSituacaoLancamentoDTO, 'Codigo', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdLitSituacaoLancamentoDTO);


$arrObjMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->listar($objMdLitSituacaoLancamentoDTO);

PaginaSEI::getInstance()->processarPaginacao($objMdLitSituacaoLancamentoDTO);
$numRegistros = count($arrObjMdLitSituacaoLancamentoDTO);


//Tabela de resultado.
if ($numRegistros > 0) {

    $strResultado .= '<table width="99%" class="infraTable" summary="Situa��o de Lan�amentos">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Situa��o de Lan�amentos', $numRegistros);
    $strResultado .= '</caption>';

    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';

    $strResultado .= '<th class="infraTh" width="10%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitSituacaoLancamentoDTO, 'C�digo', 'Codigo', $arrObjMdLitSituacaoLancamentoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="35%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitSituacaoLancamentoDTO, 'Descri��o', 'Nome', $arrObjMdLitSituacaoLancamentoDTO);
    $strResultado .= '</th>';
    if(!$bolSelecionar) {
        $strResultado .= '<th class="infraTh" width="10%">';
        $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitSituacaoLancamentoDTO, 'Origem', 'StaOrigem', $arrObjMdLitSituacaoLancamentoDTO);
        $strResultado .= '</th>';

        $strResultado .= '<th class="infraTh" width="20%">';
        $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitSituacaoLancamentoDTO, 'Nome da Integra�ao', 'NomeIntegracao', $arrObjMdLitSituacaoLancamentoDTO);
        $strResultado .= '</th>';
    }

    $strResultado .= '<th class="infraTh" width="15%">A��es</th>';
    $strResultado .= '</tr>';


    $strCssTr = '<tr class="infraTrEscura">';

    for ($i = 0; $i < $numRegistros; $i++) {

        $strId             = $arrObjMdLitSituacaoLancamentoDTO[$i]->getNumIdMdLitSituacaoLancamento();
        $strCodigo         = $arrObjMdLitSituacaoLancamentoDTO[$i]->getNumCodigo();
        $strNome           = $arrObjMdLitSituacaoLancamentoDTO[$i]->getStrNome();
        $strOrigem         = $arrObjMdLitSituacaoLancamentoDTO[$i]->getStrStaOrigem() == 'M' ? 'Manual' : 'Integra��o';
        $strNomeIntegracao = $arrObjMdLitSituacaoLancamentoDTO[$i]->getStrNomeIntegracao();

        $strNomeParametro   = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdLitSituacaoLancamentoDTO[$i]->getStrNome());
        $bolRegistroAtivo      = $arrObjMdLitSituacaoLancamentoDTO[$i]->getStrSinAtivo() == 'S';

        $strCssTr     = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
        $strResultado .= $strCssTr;

        $strResultado .= '<td align="center" valign="top">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strNome);
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strCodigo);
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($strNome);
        $strResultado .= '</td>';

        if(!$bolSelecionar){
            $strResultado .= '<td>';
            $strResultado .= PaginaSEI::tratarHTML($strOrigem);
            $strResultado .= '</td>';

            $strResultado .= '<td>';
            $strResultado .= PaginaSEI::tratarHTML($strNomeIntegracao);
            $strResultado .= '</td>';
        }


        $strResultado .= '<td align="center">';

        if (!$bolSelecionar) {

            //A��o Consultar
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink($strUrl . 'consultar&acao_origem=' . $_GET['acao'] . '&id_md_lit_situacao=' . $strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Situa��o de Lan�amento" alt="Consultar Situa��o de Lan�amento" class="infraImg" /></a>&nbsp;';

            if ($arrObjMdLitSituacaoLancamentoDTO[$i]->getStrStaOrigem() == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL && $bolRegistroAtivo) {
                //A��o Alterar
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink($strUrl . 'alterar&acao_origem=' . $_GET['acao'] . '&id_md_lit_situacao=' . $strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Situa��o de Lan�amento" alt="Alterar  Situa��o de Lan�amento" class="infraImg" /></a>&nbsp;';
            }elseif ($arrObjMdLitSituacaoLancamentoDTO[$i]->getStrStaOrigem() == MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO && $bolRegistroAtivo) {
                //A��o Alterar
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink($strUrl . 'alterar&acao_origem=' . $_GET['acao'] . '&id_md_lit_situacao=' . $strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/litigioso/imagens/integracao.png" style="height: 0.9em;" title="Alterar Situa��o de Lan�amento" alt="Alterar Situa��o de Lan�amento" class="infraImg" /></a>&nbsp;';
            }

            if ($bolRegistroAtivo) {
                //A��o Desativar
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="desativar(\'' . $strId . '\',\'' . $strNomeParametro . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Situa��o de Lan�amento" alt="Desativar Situa��o de Lan�amento" class="infraImg" /></a>&nbsp;';
            } else {
                //A��o Reativar
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="reativar(\'' . $strId . '\',\'' . $strNomeParametro . '\', \''.SessaoSEI::getInstance()->assinarLink($strUrl . "reativar&acao_origem=" . $_GET["acao"].PaginaSEI::getInstance()->montarAncora($strId)).'\')" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Situa��o de Lan�amento" alt="Reativar Situa��o de Lan�amento" class="infraImg" /></a>&nbsp;';
            }

            if($arrObjMdLitSituacaoLancamentoDTO[$i]->getStrStaOrigem() == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL){
                //A��o Excluir
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="excluir(\'' . $strId . '\',\'' . $strNomeParametro . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Situa��o de Lan�amento" alt="Excluir Situa��o de Lan�amento" class="infraImg" /></a>&nbsp;';
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

require_once 'md_lit_css_geral.php';

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
//PaginaSEI::getInstance()->abrirJavaScript(); ?>
    <script>
        function inicializar() {
            addEventoEnter();
            if ('<?= $_GET['acao'] ?>' == 'md_lit_situacao_lancamento_selecionar') {
                infraReceberSelecao();
                document.getElementById('btnFecharSelecao').focus();
            } else {
                infraEfeitoTabelas();
            }
        }

        function pesquisar() {
            document.getElementById('frmSituacaoLancamentoLista').action = '<?= $strUrlAcaoForm ?>';
            document.getElementById('frmSituacaoLancamentoLista').submit();
        }

        function desativar(id, desc) {
            if (confirm("Confirma desativa��o da Situa��o de Lan�amento \"" + desc + "\"?")) {
                document.getElementById('hdnInfraItemId').value = id;
                document.getElementById('frmSituacaoLancamentoLista').action = '<?= $strUrlDesativar ?>';
                document.getElementById('frmSituacaoLancamentoLista').submit();
            }
        }

        function reativar(id, desc, url) {
            if (confirm("Confirma reativa��o da Situa��o de Lan�amento \"" + desc + "\"?")) {
                document.getElementById('hdnInfraItemId').value = id;
                document.getElementById('frmSituacaoLancamentoLista').action = url;
                document.getElementById('frmSituacaoLancamentoLista').submit();
            }
        }

        function excluir(id, desc) {
            if (confirm("Confirma exclus�o da Situa��o de Lan�amento \"" + desc + "\"?")) {
                document.getElementById('hdnInfraItemId').value = id;
                document.getElementById('frmSituacaoLancamentoLista').action = '<?= $strUrlExcluir ?>';
                document.getElementById('frmSituacaoLancamentoLista').submit();
            }
        }

        function novo() {
            location.href = "<?= $strUrlNovo ?>";
        }

        function imprimir() {
            infraImprimirTabela();
        }

        function fechar() {
            location.href = "<?= $strUrlFechar ?>";
        }

        function addEventoEnter() {
            document.addEventListener("keypress", function (evt) {
                var key_code = evt.keyCode ? evt.keyCode :
                    evt.charCode ? evt.charCode :
                        evt.which ? evt.which : void 0;

                if (key_code == 13) {
                    pesquisar();
                }

            });
        }

    </script>

<?php //PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmSituacaoLancamentoLista" method="post" action="<?= $strUrlAcaoForm ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('auto'); ?>

        <div class="grid grid_1">
            <label id="lblCodigo" for="txtCodigo" class="infraLabelOpcional">
                C�digo:
            </label>
            <input type="text" id="txtCodigo" name="txtCodigo" maxlength="10" value="<?= $_POST['txtCodigo'] ?>">
        </div>
        <div class="grid grid_5">
            <label id="lblNome" for="txtNome" class="infraLabelOpcional">
                Descri��o:
            </label>
            <input type="text" id="txtNome" name="txtNome" maxlength="100"
                   value="<?= $_POST['txtNome'] ?>">
        </div>
        <?php if(!$bolSelecionar){?>
        <div class="grid grid_3">
            <label id="lblOrigem" for="selOrigem" class="infraLabelOpcional">
                Origem:
            </label>
            <select id="selOrigem" name="selOrigem" onchange="pesquisar()">
                <option value="">&nbsp;</option>
                <option value="<?= MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL ?>"
                    <?= $_POST['selOrigem'] == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL ? 'selected="selected"' : '' ?>>
                    Manual
                </option>
                <option value="<?= MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO ?>"
                    <?= $_POST['selOrigem'] == MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO ? 'selected="selected"' : '' ?>>
                    Integra��o
                </option>
            </select>
        </div>
        <div class="grid grid_5">
            <label id="lblNomeIntegracao" for="txtNomeIntegracao" class="infraLabelOpcional">
                Nome da Integra��o:
            </label>
            <input type="text" id="txtNomeIntegracao" name="txtNomeIntegracao"
                   value="<?= $_POST['txtNomeIntegracao'] ?>">
        </div>
        <?php } ?>
        <div class="clear"></div>

        <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

