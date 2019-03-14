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

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    switch ($_GET['acao']) {
        case 'md_lit_multa_justificativa':

            //Switch case para definir o titulo da pagina conforme o id funcionalidade
            switch ($_GET['id_md_lit_funcionalidade']){
                case MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO:
                    $strTitulo = 'Incluir Lançamento';
                    $txtJustificativaPadrao = 'Multa aplicada com base no Processo nº ';
                    break;
                case MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO:
                    $strTitulo = 'Retificar Lançamento';
                    if(isset($_GET['constituir_definitivamente']) && $_GET['constituir_definitivamente'] == 1){
                        $strTitulo .= ' - Constituição Definitiva';
                    }
                    $txtJustificativaPadrao = 'Multa retificada com base no Processo nº ';
                    break;
                case MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO:
                    $strTitulo = 'Suspender Exigibilidade em Razão de Recurso';
                    $txtJustificativaPadrao = 'Multa com exigibilidade suspensa em razão de Recurso com base no Processo nº ';
                    break;
                case MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_RECURSO:
                    $strTitulo = 'Cancelar Recurso e Restabelecer Exigibilidade';
                    $txtJustificativaPadrao = 'Multa com exigibilidade restabelecida em razão de cancelamento de Recurso com base no Processo nº ';
                    break;
                case MdLitIntegracaoRN::$ARRECADACAO_DENEGAR_RECURSO:
                    $strTitulo = 'Denegar Recurso e Restabelecer Exigibilidade';
                    $txtJustificativaPadrao = 'Multa com exigibilidade restabelecida em razão de denegação de Recurso com base no Processo nº ';
                    break;
                default:
                    $strTitulo = '';
                    $txtJustificativaPadrao = '';
                    break;
            }

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarDecisao" id="sbmCadastrarDecisao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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

        $txtJustificativaPadrao.= $numProcesso;
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
        if(document.getElementById('txtJustificativaLancamento').value == ''){
            alert('A Justificativa é obrigatória!');
            return false;
        }

        return true;
    }


    function OnSubmitForm(){
        if(!validar())
            return false;

        window.opener.document.getElementById('hdnJustificativaLancamento').value = document.getElementById('txtJustificativaLancamento').value;
        window.opener.document.getElementById('hdnIdMdLitFuncionalidade').value = document.getElementById('hdnIdMdLitFuncionalidade').value;
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
    <div>
        <label class="infraLabelObrigatorio" >
            Justificativa:
        </label>
        <textarea rows="8" style="width: 100%" name="txtJustificativaLancamento"  onkeypress="return infraLimitarTexto(this,event,250);" maxlength="250" id="txtJustificativaLancamento" disabled="disabled" ><?= $txtJustificativaPadrao ?></textarea>
    </div>

    <input type="hidden" name="hdnIdMdLitFuncionalidade" id="hdnIdMdLitFuncionalidade" value="<?= $_GET['id_md_lit_funcionalidade'] ?>">
    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
