<?
/**
 * ANATEL
 *
 * 21/07/2017 - criado por ellyson.silva - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();

    $strLinkAjaxComboEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_especie_decisao_montar_select');
    $strLinkAjaxEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_carregar_especie_decisao');
//    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    //////////////////////////////////////////////////////////////////////////////
//    InfraDebug::getInstance()->setBolLigado(false);
//    InfraDebug::getInstance()->setBolDebugInfra(false);
//    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    $strParametros = '';
    if (isset($_GET['arvore'])) {
        PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
        $strParametros .= '&arvore=' . $_GET['arvore'];
    }

    $arrComandos        = array();
    $idMdLitControle    = null;
    $bolCadastro        = false;
    $arrTabela          = array();

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    switch ($_GET['acao']) {
        case 'md_lit_decisao_historico':
            $strTitulo = 'Histórico Geral de Decisões';

            $arrComandos[] = '<button type="button" accesskey="I" name="sbmImprimir" value="Imprimir" onclick="window.print()" class="infraButton noprint"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Fechar" onclick="window.close();" class="infraButton noprint"><span class="infraTeclaAtalho">F</span>echar</button>';
            $idMdLitControle = $_GET['id_md_lit_controle'];


            $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retTodos();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrDispositivo();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrNorma();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retNumIdCondutaLitigioso();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrConduta();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdControleLitigioso($idMdLitControle);

            $objRelDispositivoNormativoCondutaControleLitigiosoRN     = new MdLitRelDispositivoNormativoCondutaControleRN();
            $arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO = $objRelDispositivoNormativoCondutaControleLitigiosoRN->listar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);
            $arrObjMdLitDecisaoDTO = array();

            if(count($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO)){
                foreach ($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO as $objRelDispositivoNormativoCondutaControleLitigiosoDTO){
                    $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
                    $objMdLitDecisaoDTO->retNumIdMdLitDecisao();
                    $objMdLitDecisaoDTO->retStrProtocoloFormatadoDocumento();
                    $objMdLitDecisaoDTO->retDtaInclusao();
                    $objMdLitDecisaoDTO->retStrNomeUsuario();
                    $objMdLitDecisaoDTO->retStrSiglaUnidade();
                    $objMdLitDecisaoDTO->retStrNomeMdLitTipoDecisao();
                    $objMdLitDecisaoDTO->retStrNomeMdLitEspecieDecisao();
                    $objMdLitDecisaoDTO->retDblMulta();
                    $objMdLitDecisaoDTO->retStrNomeMdLitObrigacao();
                    $objMdLitDecisaoDTO->retNumPrazo();
                    $objMdLitDecisaoDTO->retDblValorMultaSemIntegracao();
                    $objMdLitDecisaoDTO->retDblValorRessarcimento();
                    $objMdLitDecisaoDTO->setNumIdMdLitRelDisNorConCtr($objRelDispositivoNormativoCondutaControleLitigiosoDTO->getNumIdDispositivoNormativoNormaCondutaControle());

                    $objMdLitDecisaoRN = new MdLitDecisaoRN();
                    $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listar($objMdLitDecisaoDTO);
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setArrObjMdLitDecisao($arrObjMdLitDecisaoDTO);
                }
            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}
$strTbCadastroDecisao = '';
$numRegistros = count($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO);
if($numRegistros > 0){

    $strCaptionTabela = 'Lista de Infrações selecionadas';


    foreach ($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO as $idLinha =>$objRelDispositivoNormativoCondutaControleLitigiosoDTO){
        $strTbCadastroDecisao .= '<table width="98%" id="tableDadosComplementarInteressado" class="infraTable" summary="' . $strCaptionTabela . '">' . "\n";
        $strTbCadastroDecisao .= '<tr class="infraTrEscura">';
        $strTbCadastroDecisao .= '<th class="tabela-th" colspan="10">'.PaginaSEI::tratarHTML($objRelDispositivoNormativoCondutaControleLitigiosoDTO->getStrInfracao()).'</th>';
        $strTbCadastroDecisao .= '</tr>';

        $strTbCadastroDecisao .= '<tr>';
        $strTbCadastroDecisao .= '<th class="infraTh" width="10%">&nbsp;Documento&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh">&nbsp;Data&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh" width="20%">&nbsp;Usuário&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh" width="5%">&nbsp;Unidade&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh" width="20%">&nbsp;Decisão&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh" width="25%">&nbsp;Espécie da Decisão&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh" width="10%">&nbsp;Multa&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh">&nbsp;Valor Ressarcimento&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh" width="25%">&nbsp;Obrigação&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '<th class="infraTh" width="5%">&nbsp;Prazo&nbsp;</th>' . "\n";
        $strTbCadastroDecisao .= '</tr>' . "\n";

        foreach ($objRelDispositivoNormativoCondutaControleLitigiosoDTO->getArrObjMdLitDecisao() as $objMdLitDecisao) {
            $idDispositivoNormativoNormaCondutaControle = $objRelDispositivoNormativoCondutaControleLitigiosoDTO->getNumIdDispositivoNormativoNormaCondutaControle();
            $strCssTr = '<tr id="CadastroDecisaoTable_' . $idLinha . '" class="infraTrClara">';
            $strTbCadastroDecisao .= $strCssTr;
            $strTbCadastroDecisao .= "<td id='td-infracao{$idLinha}'>";
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getStrProtocoloFormatadoDocumento());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td>";
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getDtaInclusao());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td>";
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getStrNomeUsuario());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td align='center'>";
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getStrSiglaUnidade());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td>";
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getStrNomeMdLitTipoDecisao());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td>";
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getStrNomeMdLitEspecieDecisao());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td align='right'>";
            $strTbCadastroDecisao .= ($objMdLitDecisao->getDblMulta() == '' && !$objMdLitDecisao->getDblValorMultaSemIntegracao()) ? '' : 'R$ ';
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getDblMulta() ?: $objMdLitDecisao->getDblValorMultaSemIntegracao());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td align='right'>";
            $strTbCadastroDecisao .= $objMdLitDecisao->getDblValorRessarcimento()? 'R$ ': '';
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getDblValorRessarcimento());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td>";
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getStrNomeMdLitObrigacao());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "<td align='center'>";
            $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objMdLitDecisao->getNumPrazo());
            $strTbCadastroDecisao .= "</td>";
            $strTbCadastroDecisao .= "</tr> \n";
        }

    }
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
//require_once ('md_lit_css_geral.php');
?>
@media print{
.noprint{
    display:none;
    }
}

th.tabela-th{font-size: 1em;font-weight: bold;color: #fff;background-color: #909090;border-spacing: 0;padding: 10px;}
table.infraTable{margin-bottom: 10px;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if(0){?><script><?}?>
    function inicializar(){

    }

    function OnSubmitForm(){
        if(!validar())
            return false;

    }
<? if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>
<form id="frmCadastroDecisao" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_lit_controle='.$_GET['id_md_lit_controle'] ) ?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);?>
    <?php PaginaSEI::getInstance()->abrirAreaDados(null); ?>

        <div id="div-tabela-historico">
        <?
        echo $strTbCadastroDecisao;
        ?>
        </div>
    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
