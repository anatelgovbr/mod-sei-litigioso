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
            $strTitulo = 'Hist�rico de Lan�amentos';

            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="infraFecharJanelaModal();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            //'<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="window.close();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';


        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        break;
    default:
        throw new InfraException("A��o '".$_GET['acao']."' n�o reconhecida.");
}

$params = array();
$params['idProcedimento'] = $idProcedimento;
$params['idLancamento'] = $_GET['id_lancamento'];

$objMdLitHistLancamentoDTO = new MdLitHistoricLancamentoDTO();
$objMdLitHistLancamentoDTO->setDblIdProcedimento($idProcedimento);
$objMdLitHistLancamentoDTO->retNumIdMdLitHistoricLancamento();
$objMdLitHistLancamentoDTO->retStrTipoLancamento();
$objMdLitHistLancamentoDTO->retStrNomeSituacao();
$objMdLitHistLancamentoDTO->retStrSequencial();
$objMdLitHistLancamentoDTO->retStrNomeSituacao();
$objMdLitHistLancamentoDTO->retDthInclusao();
$objMdLitHistLancamentoDTO->retDtaUltimoPagamento();
$objMdLitHistLancamentoDTO->retStrLinkBoleto();
$objMdLitHistLancamentoDTO->retStrNomeUsuario();
$objMdLitHistLancamentoDTO->retStrSiglaUsuario();
$objMdLitHistLancamentoDTO->retStrSiglaUnidade();
$objMdLitHistLancamentoDTO->retStrDescricaoUnidade();
$objMdLitHistLancamentoDTO->retStrSinSuspenso();
$objMdLitHistLancamentoDTO->retDblVlrDesconto();
$objMdLitHistLancamentoDTO->retDblVlrPago();
$objMdLitHistLancamentoDTO->retStrIntegracaoNome();
$objMdLitHistLancamentoDTO->retNumIdMdLitIntegracao();
$objMdLitHistLancamentoDTO->retDblVlrLancamento();

$objMdLitHistLancamentoDTO->setNumIdMdLitLancamento($_GET['id_lancamento']);



//Configura��o da Pagina��o
PaginaSEI::getInstance()->prepararOrdenacao($objMdLitHistLancamentoDTO, 'Inclusao', InfraDTO::$TIPO_ORDENACAO_DESC);
PaginaSEI::getInstance()->prepararPaginacao($objMdLitHistLancamentoDTO);
PaginaSEI::getInstance()->processarPaginacao($objMdLitHistLancamentoDTO);

$titulo = 'Orienta��es:';

$arrObjs = $objMdLitHistLancamentoRN->listar($objMdLitHistLancamentoDTO);

$numRegistros = count($arrObjs);

//Tabela de resultado.
if ($numRegistros > 0) {
    $strResultado .= '<table width="100%" class="infraTable" summary="Hist�rico de Lan�amentos">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Hist�rico de Lan�amentos', $numRegistros);
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
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Situa��o', 'NomeSituacao', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Data da Opera��o', 'Inclusao', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Data �ltimo Pagamento', 'UltimoPagamento', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Usu�rio', 'NomeUsuario', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Unidade', 'SiglaUnidade', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Credito Lan�ado ', 'VlrLancamento', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Valor Arrecadado ', 'VlrPago', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Desconto  ', 'VlrDesconto', $arrObjs);
    $strResultado .= '</th>';

    $strCssTr = '<tr class="infraTrEscura">';

    $primeiroRegistro = true;
    for ($i = 0; $i < $numRegistros; $i++) {
        $tipoLancamento = $objMdLitHistLancamentoRN->formatarTipoLancamento($arrObjs[$i]->getStrTipoLancamento());

        $strId            = $arrObjs[$i]->getNumIdMdLitHistoricLancamento();
        $strDescricao     = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjs[$i]->getStrSequencial());
        $arrDtFormatada   = explode(' ',$arrObjs[$i]->getDthInclusao());
        $dtFormatada      = $arrDtFormatada[0];
        $strSuspenso      = $arrObjs[$i]->getStrSinSuspenso() == 'S' ? ' (suspenso)': '';

        $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        $strResultado .= '<td align="center" valign="center">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDescricao);
        $strResultado .= '</td>';

        $strResultado .= '<td>';
            if($primeiroRegistro){
                $strResultado .= $objMdLitHistLancamentoRN->formatarSequencialLink($arrObjs[$i]->getStrSequencial(), $arrObjs[$i]->getStrLinkBoleto());
                $primeiroRegistro = false;
            }else{
                $strResultado .= '<span style="padding:0 .5em 0 .5em; font-size: .875rem;" >'.$arrObjs[$i]->getStrSequencial().'</span>';
            }
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
        $strResultado .= $arrObjs[$i]->getDtaUltimoPagamento();
        $strResultado .= '</td>';

        $strResultado .= '<td align="center">';
        if($arrObjs[$i]->getStrNomeUsuario()){
            $strResultado .= '<a alt="'.$arrObjs[$i]->getStrNomeUsuario().'" title="'.$arrObjs[$i]->getStrNomeUsuario().'" class="ancoraSigla"> '.$arrObjs[$i]->getStrSiglaUsuario().' </a>';
        }else{
            $strResultado .= 'Integra��o - '.$arrObjs[$i]->getStrIntegracaoNome();
        }
        $strResultado .= '</td>';

        $strResultado .= '<td align="center">';
        if($arrObjs[$i]->getStrDescricaoUnidade()){
            $strResultado .= '<a alt="'.$arrObjs[$i]->getStrDescricaoUnidade().'" title="'.$arrObjs[$i]->getStrDescricaoUnidade().'" class="ancoraSigla"> '.$arrObjs[$i]->getStrSiglaUnidade().' </a>';
        }else{
            $strResultado .= ' - ';
        }

        $strResultado .= '</td>';

        $strResultado .= '<td align="right">';
        $strResultado .=  InfraUtil::formatarDin(InfraUtil::prepararDbl($arrObjs[$i]->getDblVlrLancamento()),2);
        $strResultado .= '</td>';

        $strResultado .= '<td align="right">';
        $strResultado .=  InfraUtil::formatarDin(InfraUtil::prepararDbl($arrObjs[$i]->getDblVlrPago()),2);
        $strResultado .= '</td>';

        $strResultado .= '<td align="right">';
        $strResultado .= InfraUtil::formatarDin(InfraUtil::prepararDbl($arrObjs[$i]->getDblVlrDesconto()),2);
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload=""');

?>
<form action="" method="post" id="frmMdLitHistoricoQuinquenal" name="frmMdLitHistoricoQuinquenal"/>

    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <?php PaginaSEI::getInstance()->abrirAreaDados('') ?>
    <div id="divOrientacoes">
        <div id="divTitulo">
            <label id="lblTitulo" class="infraLabelOpcional"><strong><?php echo $titulo ?></strong></label>
        </div>

        <ul style="list-style-type: disc; padding-left: 25px; font-size: .846rem;">
            <li> Somente � poss�vel acessar o Boleto sobre �ltimo registro do Hist�rico, para que seja apresentado sempre atualizado. </li>
            <li> S�o apresentadas novas linhas no Hist�rico somente quando ocorre atualiza��es sobre o Cr�dito Lan�ado, Desconto, pagamento ou modifica��o da situa��o do lan�amento. </li>
        </ul>
    </div>

<?php
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>

<script>
    // Adiciona a class "infraLabelOpcional" quando n�o retorna nenhum registro na grid
    // para ficar na mesma formata��o das labels da infra
    if( $('#divInfraAreaTabela').find('table').length == 0 ){
        $('#divInfraAreaPaginacaoSuperior').hide();
        $('#divInfraAreaTabela').parent().parent().addClass('mt-2');
        $('#divInfraAreaTabela > label').addClass('infraLabelOpcional'); 
    }else{
        if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
        }
    }

    $('.ancoraPadraoAzul').css({
        'font-size': '.875rem'
    });
</script>
<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
