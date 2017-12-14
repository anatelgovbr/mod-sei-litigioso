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
$objMdLitHistLancamentoRN = new MdLitHistoricLancamentoRN();

switch($_GET['acao']) {

    case 'md_lit_historic_lancamento_listar':
        try {
            $strTitulo = 'Histórico de Lançamentos';

            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="window.close();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';


        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        break;
    default:
        throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
}

$params = array();
$params['idProcedimento'] = $idProcedimento;
$params['idLancamento'] = $_GET['id_lancamento'];

$dados = $objMdLitHistLancamentoRN->getDadosHistoricoLancamento($params);
$objMdLitHistLancamentoDTO = $dados[0];

//Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdLitHistLancamentoDTO, 'Inclusao', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdLitHistLancamentoDTO);
PaginaSEI::getInstance()->processarPaginacao($objMdLitHistLancamentoDTO);

$titulo = 'Orientações:';
$texto = '- As informações apresentadas estão atualizadas, de acordo com a data da consulta.';

$arrObjs = $dados[1];

$numRegistros = count($arrObjs);

//Tabela de resultado.
if ($numRegistros > 0) {
    $strResultado .= '<table width="99%" class="infraTable" summary="Histórico de Lançamentos">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Histórico de Boletos', $numRegistros);
    $strResultado .= '</caption>';

    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';

    $strResultado .= '<th class="infraTh" width="13%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Boleto', 'Sequencial', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Tipo', 'TipoLancamento', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Situação', 'NomeSituacao', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Data da Operação', 'Inclusao', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Usuário', 'NomeUsuario', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Unidade', 'SiglaUnidade', $arrObjs);
    $strResultado .= '</th>';

    $strCssTr = '<tr class="infraTrEscura">';

    for ($i = 0; $i < $numRegistros; $i++) {
        $tipoLancamento = $objMdLitHistLancamentoRN->formatarTipoLancamento($arrObjs[$i]->getStrTipoLancamento());

        $strId            = $arrObjs[$i]->getNumIdMdLitHistoricLancamento();
        $strDescricao     = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjs[$i]->getStrSequencial());
        $arrDtFormatada   = explode(' ',$arrObjs[$i]->getDthInclusao());
        $dtFormatada      = $arrDtFormatada[0];
        $strSuspenso      = $arrObjs[$i]->getStrSinSuspenso() == 'S' ? ' (suspenso)': '';

        $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        $strResultado .= '<td align="center" valign="top">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDescricao);
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= $objMdLitHistLancamentoRN->formatarSequencialLink($arrObjs[$i]->getStrSequencial(), $arrObjs[$i]->getStrLinkBoleto());
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($tipoLancamento);
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeSituacao().$strSuspenso);
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= $dtFormatada;
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeUsuario());
        $strResultado .= '</td>';

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjs[$i]->getStrSiglaUnidade());
        $strResultado .= '</td>';
    }

    $strResultado .= '</table>';

}


PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>



<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload=""');

?>
<form action="<?php /*echo SessaoSEI::getInstance()->assinarLink('controlador_externo.php?acao=md_pet_intimacao_usu_ext_confirmar_aceite&id_procedimento='.$_GET['id_procedimento'].'&id_acesso_externo='.$_GET['id_acesso_externo'].'&id_documento='.$_GET['id_documento']);*/ ?>" method="post" id="frmMdLitHistoricoQuinquenal" name="frmMdLitHistoricoQuinquenal"/>

    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <?php PaginaSEI::getInstance()->abrirAreaDados('') ?>
    <div id="divOrientacoes">
        <div id="divTitulo">
            <label id="lblTitulo"><?php echo $titulo ?></label>
        </div>

        <div id="divInformacoes">
          <label style="margin-left:70px" id="lblInformacoes"><?php echo $texto ?></label>
        </div>
    </div>

<?php
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>
<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
