<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/04/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

try {
    require_once dirname(__FILE__) . '/../../SEI.php';


  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('md_lit_motivo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtDescricao'));

  require_once dirname ( __FILE__ ) .'/md_lit_motivo_lista_inicializacao.php';

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?if(0){?><style><?}?>
    #lblDescricao{position: absolute; margin-left: 0%}
    #txtDescricao{position: absolute; margin-left: 0%;margin-top: 15px;width: 44%}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once dirname ( __FILE__ ) .'/md_lit_motivo_lista_js.php';
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMdLitMotivoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblDescricao" name="txtDescricao" value="Descricao" class="infraLabelOpcional">Descrição:</label>
    <input id="txtDescricao" value="<?=$txtDescricao?>" class="infraText" name="txtDescricao"/>
    <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  <input type="hidden" id="hdnTpControle" name="hdnTpControle" class="infraText" value="<?=$idTipoControle?>">
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
