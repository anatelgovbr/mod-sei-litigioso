<?
/**
 * ANATEL
 *
 * 21/08/2017 - criado por ellyson.silva - CAST
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

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    switch ($_GET['acao']) {
        case 'md_lit_multa_cancelar':

            $strTitulo = 'Cancelar Lan�amento';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCancelarLancamento" id="sbmCancelarLancamento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            $strComboMotivoCancelar = MdLitCancelaLancamentoINT::montarSelectMotivoCancelarLancamento();

            break;

        default:
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
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
PaginaSEI::getInstance()->abrirStyle();
//require_once ('md_lit_css_geral.php');
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if(0){?><script><?}?>
    function inicializar(){

    }

    function validar(){
        if(document.getElementById('selMotivoCancelar').value == 'null'){
            alert('Informe o Motivo do Cancelamento.');
            return false;
        }

        if(document.getElementById('txtJustificativaCancelamento').value == ''){
            alert('Informe a Justificativa do Cancelamento.');
            return false;
        }

        return true;
    }


    function OnSubmitForm(){
        if(!validar()){
            return false;
        }
        var optionsSelMotivo = document.getElementById('selMotivoCancelar').options;

        window.opener.document.getElementById('hdnIdMotivoCancelamento').value      = document.getElementById('selMotivoCancelar').value;
        window.opener.document.getElementById('hdnJustificativaCancelamento').value = document.getElementById('txtJustificativaCancelamento').value;
        window.opener.document.getElementById('hdnIdMdLitFuncionalidade').value     = document.getElementById('hdnIdMdLitFuncionalidade').value;
        window.opener.document.getElementById('hdnTxtMotivoCancelamento').value     = optionsSelMotivo[optionsSelMotivo.selectedIndex].text;
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
<form id="frmCadastroCancelarLancamento" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] .'&id_procedimento='.$_GET['id_procedimento'] ) ?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);?>
    <?php PaginaSEI::getInstance()->abrirAreaDados(null); ?>
    <div>
        <label class="infraLabelObrigatorio" >
            Motivo do Cancelamento:
        </label>
        <select name="selMotivoCancelar" id="selMotivoCancelar" class="infraSelect" ><?= $strComboMotivoCancelar ?></select>
    </div>
    <div>
        <label class="infraLabelObrigatorio" >
            Justificativa do Cancelamento:
        </label>
        <textarea rows="8" style="width: 100%" name="txtJustificativaCancelamento" id="txtJustificativaCancelamento" ></textarea>
    </div>

    <input type="hidden" name="hdnIdMdLitFuncionalidade" id="hdnIdMdLitFuncionalidade" value="<?= $_GET['id_md_lit_funcionalidade'] ?>">
    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
