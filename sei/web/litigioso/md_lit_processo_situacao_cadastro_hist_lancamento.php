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
            $arrComandos[] = '<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="infraFecharJanelaModal();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            //'<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="window.close();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';


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



//Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdLitHistLancamentoDTO, 'Inclusao', InfraDTO::$TIPO_ORDENACAO_DESC);
PaginaSEI::getInstance()->prepararPaginacao($objMdLitHistLancamentoDTO);
PaginaSEI::getInstance()->processarPaginacao($objMdLitHistLancamentoDTO);

$titulo = 'Orientações:';

$arrObjs = $objMdLitHistLancamentoRN->listar($objMdLitHistLancamentoDTO);

$numRegistros = count($arrObjs);

//Tabela de resultado.
if ($numRegistros > 0) {
    $strResultado .= '<table width="100%" class="infraTable" summary="Histórico de Lançamentos">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Histórico de Lançamentos', $numRegistros);
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
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Data Último Pagamento', 'UltimoPagamento', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Usuário', 'NomeUsuario', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Unidade', 'SiglaUnidade', $arrObjs);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitHistLancamentoDTO, 'Credito Lançado ', 'VlrLancamento', $arrObjs);
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
            $strResultado .= 'Integração - '.$arrObjs[$i]->getStrIntegracaoNome();
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
            <li> Somente é possível acessar o Boleto sobre último registro do Histórico, para que seja apresentado sempre atualizado. </li>
            <li> São apresentadas novas linhas no Histórico somente quando ocorre atualizações sobre o Crédito Lançado, Desconto, pagamento ou modificação da situação do lançamento. </li>
        </ul>
    </div>

<?php
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>

<script>
    // Adiciona a class "infraLabelOpcional" quando não retorna nenhum registro na grid
    // para ficar na mesma formatação das labels da infra
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
