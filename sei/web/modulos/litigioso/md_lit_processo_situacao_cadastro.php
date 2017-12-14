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
    $strTitulo       = 'Controle Litigioso - Cadastro de Situações';
    $idDocumento     = array_key_exists('id_doc', $_GET)? $_GET['id_doc'] : $_POST['hdnIdDocumento'];
    $idDocNumeroSei  = $_POST['txtNumeroSei'] != '' ? $_POST['hdnIdDocumentoNumeroSei'] : $idDocumento;
    $idProcedimento  = array_key_exists('id_procedimento', $_GET)? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
    $idTpControle    = array_key_exists('id_tipo_controle', $_GET) ? $_GET['id_tipo_controle'] : $_POST['hdnIdTipoControle'];

    //Var para controlar se o acesso foi feito pelo processo ou pelos documentos -- No processo não permite edição.
    // 0 - false / 1 - true
    $openProcesso    = is_null($idDocumento) ? '1' : '0';

    $strTitulo       = $openProcesso? 'Controle Litigioso - Consulta de Situações' : $strTitulo;
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

// ======================= INICIO JS PADRÃO
require_once('md_lit_processo_situacao_cadastro_js.php');

//Fieldset Situaões
require_once('md_lit_processo_situacao_cadastro_bloco_situacoes_js.php');

//Fieldset Decisões
require_once('md_lit_processo_situacao_cadastro_bloco_decisoes_js.php');

//Fieldset Gestão de Multa
require_once('md_lit_processo_situacao_cadastro_bloco_multa_js.php');

// ======================= FIM JS PADRÃO

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

// ======================= INICIO Bloco Situações
require_once('md_lit_processo_situacao_cadastro_bloco_situacoes.php');
// ======================= FIM Bloco Situações

// ======================= INICIO Bloco Decisões
require_once('md_lit_processo_situacao_cadastro_bloco_decisoes.php');
// ======================= FIM Bloco Decisões

// ======================= INICIO Bloco Gestão de Multa
require_once('md_lit_processo_situacao_cadastro_bloco_multa.php');
// ======================= FIM Bloco Gestão de Multa
?>

<?php
PaginaSEI::getInstance()->fecharAreaDados();
?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
