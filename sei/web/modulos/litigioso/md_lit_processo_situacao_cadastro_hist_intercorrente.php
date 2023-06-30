<?php
/**
 * ANATEL
 *
 * 22/07/2017 - criado por jaqueline.mendes@castgroup.com.br - CAST
 *
 */
require_once dirname(__FILE__) . '/../../SEI.php';
SessaoSEI::getInstance()->validarLink();
SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

$arrComandos = array();
$idProcedimento = $_GET['id_procedimento'];
$objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();

switch ($_GET['acao']) {
    case 'md_lit_processo_situacao_cadastro_hist_int_listar':
        try {
            $strTitulo = 'Histórico de Datas de Prescrição Intercorrente';

            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="infraFecharJanelaModal();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';


        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        break;
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
}

$objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
$objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
$objMdLitProcessoSituacaoDTO->setDtaIntercorrente(null, InfraDTO::$OPER_DIFERENTE);


//Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdLitProcessoSituacaoDTO, 'Inclusao', InfraDTO::$TIPO_ORDENACAO_DESC);
PaginaSEI::getInstance()->prepararPaginacao($objMdLitProcessoSituacaoDTO);
PaginaSEI::getInstance()->processarPaginacao($objMdLitProcessoSituacaoDTO);

$arrObjs = $objMdLitProcessoSituacaoRN->getListaHistoricoIntQuinq($objMdLitProcessoSituacaoDTO);

$numRegistros = count($arrObjs);

//Tabela de resultado.
if ($numRegistros > 0) {
    $strResultado .= '<table width="99%" class="infraTable" summary="Histórico de Datas de Prescrição Intercorrente">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Histórico de Datas de Prescrição Intercorrente', $numRegistros);
    $strResultado .= '</caption>';

    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';

    $strResultado .= '<th class="infraTh" width="13%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitProcessoSituacaoDTO, 'Documento', 'ProtocoloFormatadoDocumento', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitProcessoSituacaoDTO, 'Fase', 'NomeFase', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitProcessoSituacaoDTO, 'Situação', 'NomeSituacao', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitProcessoSituacaoDTO, 'Data da Operação', 'Inclusao', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitProcessoSituacaoDTO, 'Usuário', 'NomeUsuario', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitProcessoSituacaoDTO, 'Unidade', 'SiglaUnidade', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="11%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitProcessoSituacaoDTO, 'Prescrição Intercorrente', 'Intercorrente', $arrObjs);
    $strResultado .= '</th>';


    $strCssTr = '<tr class="infraTrEscura">';

    for ($i = 0; $i < $numRegistros; $i++) {

        $strId = $arrObjs[$i]->getDblIdDocumento();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjs[$i]->getStrProtocoloFormatadoDocumento());
        $bolRegistroAtivo = $arrObjs[$i]->getStrSinAtivo() == 'S';
        $arrDtFormatada = explode(' ', $arrObjs[$i]->getDthInclusao());
        $dtFormatada = $arrDtFormatada[0];

        $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
        $strResultado .= $strCssTr;

        $strResultado .= '<td align="center" valign="top">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDescricao);
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getStrProtocoloFormatadoDocumento());
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeFase());
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeSituacao());
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= $dtFormatada;
        $strResultado .= '</td>';

        $strResultado .= '<td align="center">';
        $strResultado .= '<a alt="' . $arrObjs[$i]->getStrNomeUsuario() . '" title="' . $arrObjs[$i]->getStrNomeUsuario() . '" class="ancoraSigla"> ' . $arrObjs[$i]->getStrSiglaUsuario() . ' </a>';
        $strResultado .= '</td>';

        $strResultado .= '<td align="center">';
        $strResultado .= '<a alt="' . $arrObjs[$i]->getStrDescricaoUnidade() . '" title="' . $arrObjs[$i]->getStrDescricaoUnidade() . '" class="ancoraSigla"> ' . $arrObjs[$i]->getStrSiglaUnidade() . ' </a>';
        $strResultado .= '</td>';


        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getDtaIntercorrente());
        $strResultado .= '</td>';

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
?>



<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload=""');

?>
<form action="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&id_procedimento=' . $_GET['id_procedimento']); ?>"
      method="post" id="frmMdLitHistoricoIntercorrente" name="frmMdLitHistoricoIntercorrente">

    <?php
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>
<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
