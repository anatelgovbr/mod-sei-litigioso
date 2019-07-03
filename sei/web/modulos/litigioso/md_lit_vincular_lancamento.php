<?
/**
 * ANATEL
 *
 * 02/08/2017 - criado por ellyson.silva - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
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
    $bolHouveMudanca    = true;
    $idUltimaSituacaoDecisoria = null;
    $txtJustificativaPadrao    = '';
    $strResultado       = '';
    $numIdentificacaoLancamento = null;
    $dtaVencimentoMulta = null;
    $vlTotalMulta = null;
    $dtaDecisaoMulta = null;

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    switch ($_GET['acao']) {
        case 'md_lit_vincular_lancamento':

            $strTitulo = 'Vincular Lançamento Pré Existente';
            $txtJustificativaPadrao = 'Vincular Lançamento Pré Existente com base no Processo nº ';

            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

            if(isset($_POST['sbmValidar'])){
                $numIdentificacaoLancamento = $_POST['txtIdentificacaoLancamento'];
                $numIdMdLitNumeroInteressado = $_POST['hdnIdNumeroComplementar'];

                $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
                $objMdLitNumeroInteressadoDTO->retTodos();
                $objMdLitNumeroInteressadoDTO->setNumIdMdLitNumeroInteressado($numIdMdLitNumeroInteressado);

                $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
                $objMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoRN->consultar($objMdLitNumeroInteressadoDTO);

                if(!$objMdLitNumeroInteressadoDTO || empty($objMdLitNumeroInteressadoDTO->getStrNumero())){
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao("Selecione o Numéro do Interessado!");
                    $objInfraException->lancarValidacoes();
                }

                //identificando que o lançamento já está vinculado com outro lancamento
                $objMdLitLancamentoDTOExiste = new MdLitLancamentoDTO();
                $objMdLitLancamentoDTOExiste->retTodos();
                $objMdLitLancamentoDTOExiste->retStrProtocoloProcedimentoFormatado();
                $objMdLitLancamentoDTOExiste->setStrSequencial($numIdentificacaoLancamento);
                $objMdLitLancamentoDTOExiste->setStrNumeroInteressado($objMdLitNumeroInteressadoDTO->getStrNumero());
                $objMdLitLancamentoDTOExiste->setNumMaxRegistrosRetorno(1);

                $objMdLitLancamentoRN = new MdLitLancamentoRN();
                $objMdLitLancamentoDTOExiste = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTOExiste);


                if(!$objMdLitLancamentoDTOExiste) {

                    $objMdLitConsultarLancamentoRN = new MdLitConsultarLancamentoRN();
                    $objMdLitLancamentoDTO = $objMdLitConsultarLancamentoRN->vincularLancamento($objMdLitNumeroInteressadoDTO->getStrNumero(), $numIdentificacaoLancamento);

                    $dtaVencimentoMulta = $_POST['hdnDtVencimento'];
                    $vlTotalMulta = $_POST['hdnVlTotalMulta'];
                    $dtaDecisaoMulta = $_POST['hdnDecisaoAplicacaoMulta'];

                    if ($objMdLitLancamentoDTO) {
                        $strResultado .= '<table width="99%" id="tbLancamento" class="infraTable" summary="Lançamento">';
                        $strResultado .= '<caption class="infraCaption">';
                        //                    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Lançamento', 1);
                        $strResultado .= '</caption>';
                        $strResultado .= '<tr>';

                        $strResultado .= '<th class="infraTh" width="13%">';
                        $strResultado .= 'Boleto';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%">';
                        $strResultado .= 'Código da Receita';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%">';
                        $strResultado .= 'Data de Vencimento';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%">';
                        $strResultado .= 'Total de Multa Aplicada';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%">';
                        $strResultado .= 'Valor do Desconto';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%">';
                        $strResultado .= 'Valor Arrecadado';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%">';
                        $strResultado .= 'Saldo Devedor Atualizado';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%">';
                        $strResultado .= 'Data da Decisão de Aplicação da Multa';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%" >';
                        $strResultado .= 'Situação';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" width="15%">';
                        $strResultado .= 'Data da Constituição Definitiva';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" style="display: none">';
                        $strResultado .= 'Id Situacao';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" style="display: none">';
                        $strResultado .= 'Link do Boleto';
                        $strResultado .= '</th>';

                        $strResultado .= '<th class="infraTh" style="display: none">';
                        $strResultado .= 'Numero de Interessado';
                        $strResultado .= '</th>';

                        $strResultado .= '</tr>';

                        $strCssTr = '<tr class="infraTrEscura">';

                        $objMdLitHistLancamentoRN = new MdLitHistoricLancamentoRN();
                        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($objMdLitLancamentoDTO->getStrSequencial());

                        $strResultado .= '<tr class="infraTrClara">';

                        $strResultado .= '<td><div>';
                        $strResultado .= $objMdLitHistLancamentoRN->formatarSequencialLink($objMdLitLancamentoDTO->getStrSequencial(), $objMdLitLancamentoDTO->getStrLinkBoleto());
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div>';
                        $strResultado .= PaginaSEI::tratarHTML($objMdLitLancamentoDTO->getStrCodigoReceita());
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div>';
                        $strResultado .= $objMdLitLancamentoDTO->getDtaVencimento();
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div style="text-align: right">';
                        $strResultado .= InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento()), 2);
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div style="text-align: right">';
                        $strResultado .= InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrDesconto()), 2);
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div style="text-align: right">';
                        $strResultado .= $objMdLitLancamentoDTO->isSetDblVlrPago() ? InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrPago()), 2) : '0,00';
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div style="text-align: right">';
                        $strResultado .= InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrSaldoDevedor()), 2);
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div style="text-align: center">';
                        $strResultado .= $objMdLitLancamentoDTO->isSetDtaDecisao() ? $objMdLitLancamentoDTO->getDtaDecisao() : null;
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div>';
                        $strResultado .= $objMdLitLancamentoDTO->getStrNomeSituacao();//getStrNomeSituacao
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td><div>';
                        $strResultado .= $objMdLitLancamentoDTO->isSetDtaConstituicaoDefinitiva() ? $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva() : null;
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td style="display: none"><div>';
                        $strResultado .= $objMdLitLancamentoDTO->getNumIdMdLitSituacaoLancamento();
                        $strResultado .= '</div></td>';


                        $strResultado .= '<td style="display: none"><div>';
                        $strResultado .= $objMdLitLancamentoDTO->getStrLinkBoleto();
                        $strResultado .= '</div></td>';

                        $strResultado .= '<td style="display: none"><div>';
                        $strResultado .= $objMdLitLancamentoDTO->getStrNumeroInteressado();
                        $strResultado .= '</div></td>';

                        $strResultado .= '</tr>';
                    }

                    $strResultado .= '</table>';
                }

            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    if($_GET['id_procedimento']){
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);
        $objProtocoloDTO->retTodos(false);
        $objProtocoloDTO->setNumMaxRegistrosRetorno(1);

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
        $numProcesso = $objProtocoloDTO->getStrProtocoloFormatado();

        $txtJustificativaPadrao .= $numProcesso;
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();?>
.infraText{width:95%}
#tbLancamento{margin-top: 20px;}
<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if(0){?><script><?}?>
    function inicializar(){
        recuperarNumeroComplementar();
        document.getElementById('hdnDecisaoAplicacaoMulta').value = window.opener.document.getElementById('txtDecisaoAplicacaoMulta').value;
        document.getElementById('hdnVlTotalMulta').value = window.opener.document.getElementById('hdnVlTotalMulta').value;
        document.getElementById('hdnDtVencimento').value = window.opener.document.getElementById('txtDtVencimento').value;
        var bolValorDiferente = false;

        <?php if($objMdLitLancamentoDTO && $objMdLitLancamentoDTO->isSetDblVlrLancamento()){ ?>
            var valor = '<?php echo InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento()),2)?>';
            console.log(valor,document.getElementById('hdnVlTotalMulta').value );
            bolValorDiferente = valor != document.getElementById('hdnVlTotalMulta').value;
        <?php } ?>

        if( window.opener.document.getElementById('selCreditosProcesso').options.length > 1 && bolValorDiferente){
            document.getElementById('sbmConfirmar').remove();
            alert('Não foi possível realizar a vinculação do lançamento pré existente em razão de divergência entre o valor retornado do Sistema de Arrecadação e o constante nas decisões deste processo (que possui múltiplas multas indicadas).\n' +
                '\n\n' +
                'Antes, ajustes os valores de multas constantes nas decisões para que fiquem em conformidade com o lançamento a ser vinculado.');
            return;
        }
        if(window.opener.objTabelaDinamicaDecisao.obterItens().length > 1 && bolValorDiferente){
            document.getElementById('sbmConfirmar').remove();
            alert('Não foi possível realizar a vinculação do lançamento pré existente em razão de divergência entre o valor retornado do Sistema de Arrecadação e o constante nas decisões deste processo (que possui múltiplas multas indicadas).\n' +
                '\n\n' +
                'Antes, ajustes os valores de multas constantes nas decisões para que fiquem em conformidade com o lançamento a ser vinculado.');
            return;
        }


    }

    function validar(){
        if(document.getElementById('txtIdentificacaoLancamento').value == ''){
            alert('A Identificação do Lançamento é obrigatória!');
            return false;
        }

        return true;
    }

    function recuperarNumeroComplementar(){
        var selectNumeroInteressado = window.opener.document.getElementById('selNumeroInteressado');

        for(var i = 0; i <= selectNumeroInteressado.options.length; i++){
            if(selectNumeroInteressado.options[i].selected){
                document.getElementById('txtNumeroComplementar').value = selectNumeroInteressado.options[i].text;
                document.getElementById('hdnIdNumeroComplementar').value = selectNumeroInteressado.options[i].value;
                document.getElementById('txtNumeroComplementar').setAttribute('title', document.getElementById('txtNumeroComplementar').value);
                break;
            }
        }
    }

    function OnSubmitForm(){
        if(!validar())
            return false;

        // window.opener.document.getElementById('hdnJustificativaLancamento').value = document.getElementById('txtJustificativaLancamento').value;
        // window.opener.document.getElementById('hdnIdMdLitFuncionalidade').value = document.getElementById('hdnIdMdLitFuncionalidade').value;
        // window.opener.document.getElementById('sbmCadastrarSituacao').click();
        // window.close();
        // return false;

    }

    function confirmarVinculacao(){debugger;
        if( document.getElementById('chkSinVincularLancamento') != null && !document.getElementById('chkSinVincularLancamento').checked){
            alert("Marque o checkbox de ciência da sobrecrita das informações divergentes antes de acionar o botão Confirmar a Vinculação.");
            document.getElementById('chkSinVincularLancamento').focus();
            return false;
        }
        objTabelaLancamento = new infraTabelaDinamica('tbLancamento', 'hdntbLancamento', false, false);
        objTabelaLancamento.atualizaHdn();

        window.opener.document.getElementById('hdnTbVincularLancamento').value = objTabelaLancamento.hdn.value;
        window.opener.document.getElementById('sbmCadastrarSituacao').click();
        window.close();
        return false;
    }
    <? if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>
<form id="frmCadastroJustificativaLancamento" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_lit_funcionalidade='.$_GET['id_md_lit_funcionalidade'].'&id_procedimento='.$_GET['id_procedimento'] ) ?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);?>
    <?php PaginaSEI::getInstance()->abrirAreaDados(null); ?>
    <div style="
    width: 300px;
    float: left;
" >
        <input type="hidden" name="hdnVlTotalMulta" id="hdnVlTotalMulta" value="" />
        <input type="hidden" name="hdnDecisaoAplicacaoMulta" id="hdnDecisaoAplicacaoMulta" value="" />
        <input type="hidden" name="hdnDtVencimento" id="hdnDtVencimento" value="" />
        <label class="infraLabelObrigatorio" >
            Número de Complemento do Interessado:
        </label>
        <input tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" type="text" name="txtNumeroComplementar" id="txtNumeroComplementar" class="infraText" title="" disabled="disabled">
        <input type="hidden" name="hdnIdNumeroComplementar" id="hdnIdNumeroComplementar" value="">
    </div>
    <div style="
    width: 180px;
    float: left;
">
        <label class="infraLabelObrigatorio" >
            Identificação do Lançamento:
        </label>
        <input tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" type="text" name="txtIdentificacaoLancamento" id="txtIdentificacaoLancamento" value="<?=$numIdentificacaoLancamento?>" class="infraText">
    </div>
    <div style="width: 90px;float: left;">
        <button tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" style="margin-top: 18px;" type="submit" accesskey="V" name="sbmValidar" id="sbmValidar" value="Validar" class="infraButton"><span class="infraTeclaAtalho">V</span>alidar</button>
    </div>

    <input type="hidden" name="hdnIdMdLitFuncionalidade" id="hdnIdMdLitFuncionalidade" value="<?= $_GET['id_md_lit_funcionalidade'] ?>">
    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
    <?php
    if($strResultado && !$objMdLitLancamentoDTOExiste) {
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, 1);
    ?>
        <?php if(($objMdLitLancamentoDTO->isSetDtaDecisao() && InfraData::compararDatas($objMdLitLancamentoDTO->getDtaDecisao(), $dtaDecisaoMulta) != 0) || InfraData::compararDatas($objMdLitLancamentoDTO->getDtaVencimento(), $dtaVencimentoMulta) != 0 || InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento()),2) != $vlTotalMulta){?>
            <div class="infraDivRadio" style="margin-top: 13px;">
                <input type="checkbox"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" name="chkSinCienteVincular" id="chkSinVincularLancamento" class="infraCheckbox" value="S" >
                <span>
                <label  for="chkSinVincularLancamento" class="infraCheckbox" style="float:none">Ciente que as informações abaixo pertencentes ao lançamento serão sobrescritas no SEI, conforme as informações apresentadas na tabela acima.<br> A sobrescrita ocorrerá ao acionar o botão "Confirmar Vinculação" abaixo.</label>
                </span>

            </div>
            <?php if($objMdLitLancamentoDTO->isSetDtaDecisao() && InfraData::compararDatas($objMdLitLancamentoDTO->getDtaDecisao(), $dtaDecisaoMulta) != 0){?>
                <div>
                    <label class="infraLabelObrigatorio">Data da Constituição Definitiva:</label> <label><?php echo $objMdLitLancamentoDTO->isSetDtaConstituicaoDefinitiva()?$objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva(): null; ?></label>
                </div>
            <?php }?>
            <?php if($objMdLitLancamentoDTO->isSetDtaDecisao() && InfraData::compararDatas($objMdLitLancamentoDTO->getDtaDecisao(), $dtaDecisaoMulta) != 0){?>
                <div>
                    <label class="infraLabelObrigatorio">Data da Decisão de Aplicação da Multa:</label> <label> <?php echo $dtaDecisaoMulta ?></label>
                </div>
            <?php } ?>
            <?php if(InfraData::compararDatas($objMdLitLancamentoDTO->getDtaVencimento(), $dtaVencimentoMulta) != 0){?>
                <div>
                    <label class="infraLabelObrigatorio">Data de Vencimento:</label> <label> <?php echo $dtaVencimentoMulta;?></label>
                </div>
            <?php } ?>
            <?php if(InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento()),2) != $vlTotalMulta){?>
                <div>
                    <label class="infraLabelObrigatorio">Valor Total:</label> <label> R$ <?php echo $vlTotalMulta?></label>
                </div>
            <?php }?>
        <?php }?>

        <input type="hidden" name="hdntbLancamento" id="hdntbLancamento">
        <div align="center" style="text-align: center">
            <button tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  style="margin-top: 18px;" type="submit" name="sbmConfirmar" id="sbmConfirmar" onclick="return confirmarVinculacao()" value="Confirmar" class="infraButton">Confirmar Vinculação</button>
        </div>
    <?php }elseif ($objMdLitLancamentoDTOExiste){?>

    <div  style="margin-top: 13px;display: inline-block;">
        <label style="color: #ff0000;">Este lançamento já está vinculado ao processo sob o número <?=$objMdLitLancamentoDTOExiste->getStrProtocoloProcedimentoFormatado()?>. Não é possível vincular lançamentos em mais de um
            processo ao mesmo tempo.</label>
    </div>
    <?php }?>

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
