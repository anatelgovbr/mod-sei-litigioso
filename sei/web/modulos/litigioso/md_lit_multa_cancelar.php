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

            $strTitulo = 'Cancelar Lançamento';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCancelarLancamento" id="sbmCancelarLancamento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            $strComboMotivoCancelar = MdLitCancelaLancamentoINT::montarSelectMotivoCancelarLancamento();

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
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
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>
<form id="frmCadastroCancelarLancamento" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] .'&id_procedimento='.$_GET['id_procedimento'] ) ?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);?>
    <?php PaginaSEI::getInstance()->abrirAreaDados(null); ?>

        <div class="row mb-3">
            <div class="col-sm-10 col-md-9 col-lg-8">
                <label class="infraLabelObrigatorio"> Motivo do Cancelamento: </label>
                <select name="selMotivoCancelar" id="selMotivoCancelar" class="infraSelect form-control">
                    <?= $strComboMotivoCancelar ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <label class="infraLabelObrigatorio" > Justificativa do Cancelamento: </label>
                <textarea rows="8" onkeypress="return infraLimitarTexto(this,event,250);"
                        maxlength="250" class="form-control"
                        name="txtJustificativaCancelamento"
                        id="txtJustificativaCancelamento"></textarea>
            </div>
        </div>

        <input type="hidden" name="hdnIdMdLitFuncionalidade" id="hdnIdMdLitFuncionalidade" value="<?= $_GET['id_md_lit_funcionalidade'] ?>">
    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
</form>

<script type="text/javascript">
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

        window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('hdnIdMotivoCancelamento').value      = document.getElementById('selMotivoCancelar').value;
        window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('hdnJustificativaCancelamento').value = document.getElementById('txtJustificativaCancelamento').value;
        window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('hdnIdMdLitFuncionalidade').value     = document.getElementById('hdnIdMdLitFuncionalidade').value;
        window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('hdnTxtMotivoCancelamento').value     = optionsSelMotivo[optionsSelMotivo.selectedIndex].text;
        window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('sbmCadastrarSituacao').click();
        $(window.top.document).find('div[id^=divInfraSparklingModalClose]').click();

        return false;
    }
    $(document).ready(function() {
        $('#btnFechar').click(function() {
            $(window.top.document).find('div[id^=divInfraSparklingModalClose]').click();
        });
    });
</script>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
