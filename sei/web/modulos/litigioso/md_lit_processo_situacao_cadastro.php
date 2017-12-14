<?
/**
 * ANATEL
 *
 * 22/06/2017 - criado por jaqueline.mendes - CAST
 *
 */

try {

    require_once dirname(__FILE__).'/../../SEI.php';
    session_start();
    SessaoSEI::getInstance()->validarLink();
    PaginaSEI::getInstance()->verificarSelecao('md_lit_processo_situacao_selecionar');
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    //Add Vars
    $arrComandos     = array();
    $strTitulo       = 'Controle Litigioso - Cadastro de Situa��es';
    $idDocumento     = array_key_exists('id_doc', $_GET)? $_GET['id_doc'] : $_POST['hdnIdDocumento'];
    $idDocNumeroSei  = $_POST['txtNumeroSei'] != '' ? $_POST['hdnIdDocumentoNumeroSei'] : $idDocumento;
    $idProcedimento  = array_key_exists('id_procedimento', $_GET)? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
    $idTpControle    = array_key_exists('id_tipo_controle', $_GET) ? $_GET['id_tipo_controle'] : $_POST['hdnIdTipoControle'];

    //Var para controlar se o acesso foi feito pelo processo ou pelos documentos -- No processo n�o permite edi��o.
    // 0 - false / 1 - true
    $openProcesso    = is_null($idDocumento) ? '1' : '0';

    $strTitulo       = $openProcesso? 'Controle Litigioso - Consulta de Situa��es' : $strTitulo;
    // ======================= INICIO ACOES PHP DA PAGINA
    require_once 'md_lit_processo_situacao_acoes.php';
    // ======================= FIM ACOES PHP DA PAGINA

} catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

// ======================= INICIO CSS
require_once ('md_lit_css_geral.php');
require_once('md_lit_processo_situacao_cadastro_css.php');
// ======================= FIM CSS

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();

// ======================= INICIO JS PADR�O
require_once('md_lit_processo_situacao_cadastro_js.php');

//Fieldset Situa�es
require_once('md_lit_processo_situacao_cadastro_bloco_situacoes_js.php');

//Fieldset Decis�es
require_once('md_lit_processo_situacao_cadastro_bloco_decisoes_js.php');

//Fieldset Gest�o de Multa
require_once('md_lit_processo_situacao_cadastro_bloco_multa_js.php');

// ======================= FIM JS PADR�O

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmSituacaoProcessoCadastrar" method="post" onsubmit="return OnSubmitForm()" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
<?

PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
PaginaSEI::getInstance()->abrirAreaDados('100%');

// ======================= INICIO Bloco Inicial
require_once('md_lit_processo_situacao_cadastro_bloco_inicial.php');
// ======================= FIM Bloco Inicial

// ======================= INICIO Bloco Situa��es
require_once('md_lit_processo_situacao_cadastro_bloco_situacoes.php');
// ======================= FIM Bloco Situa��es

// ======================= INICIO Bloco Decis�es
require_once('md_lit_processo_situacao_cadastro_bloco_decisoes.php');
// ======================= FIM Bloco Decis�es

// ======================= INICIO Bloco Gest�o de Multa
require_once('md_lit_processo_situacao_cadastro_bloco_multa.php');
// ======================= FIM Bloco Gest�o de Multa
?>

<?php
PaginaSEI::getInstance()->fecharAreaDados();
?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
