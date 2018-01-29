<?
/**
* ANATEL
*
* 19/01/2016 - criado por marcelo.bezerra - CAST
*
*/

try {
  
  require_once dirname(__FILE__).'/../../SEI.php';
  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();
  PaginaSEI::getInstance()->verificarSelecao('md_lit_tipo_controle_selecionar');
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  // ======================= INICIO ACOES PHP DA PAGINA
  //script contendo açoes realizadas nesta pagina conforme contexto (se for inserçaõ, update etc..)
  //atens ficava na propria pagina mas foi externalizado para uma pagina separada
  //para facilitar manutenção da aplicação
  require_once 'md_lit_tipo_controle_cadastro_acoes.php';
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
// arquivo contendo os blocos de CSS da pagina
// antes estava na propria página, mas é melhor remover para um arquivo externo 
// para facilitar manutenção da aplicação
require_once('md_lit_tipo_controle_cadastro_css.php');
// ======================= FIM CSS

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

//variaveis para campos de selecao multipla
$strLinkUnidadesSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidades');
$strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_unidade_auto_completar');

$strLinkTipoProcessosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcessos');
$strLinkAjaxTipoProcesso = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_tipo_procedimento_auto_completar');

$strLinkTipoProcessosSobrestadosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcessosSobrestados');
$strLinkAjaxTipoProcessoSobrestado = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_tipo_procedimento_auto_completar');

$strLinkGestoresSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_selecionar&tipo_selecao=2&id_object=objLupaGestores');
$strLinkAjaxGestor = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');

// ======================= INICIO JS
//arquivo contendo os blocos de JavaScript da pagina
//antes estava na propria página, mas é melhor remover para um arquivo para facilitar manutenção da aplicação
require_once('md_lit_tipo_controle_cadastro_js.php');
// ======================= FIM JS

PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTipoControleLitigiosoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('68em');
?>
  <div class="infraAreaDados" style="height:4.5em;"> 
    <label id="lblNome" for="txtSigla" accesskey="p" class="infraLabelObrigatorio">Sigla:</label>
    <input type="text" id="txtSigla" name="txtSigla" class="infraText" 
          value="<?= PaginaSEI::tratarHTML( $objTipoControleLitigiosoDTO->getStrSigla() );?>" onkeypress="return infraMascaraTexto(this,event,50);" 
          maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>
  
  <div class="infraAreaDados" style="height:7.5em;"> 
    <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">Descrição:</label>
    <textarea id="txaDescricao" name="txtDescricao" rows="3" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" 
    tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= PaginaSEI::tratarHTML( $objTipoControleLitigiosoDTO->getStrDescricao() ) ;?></textarea>
  </div>
  
  <!--  GESTORES -->
  <div id="divGestores" class="infraAreaDados" style="height:11.5em;"> 
    
    <label id="lblGestores" for="selGestores" accesskey="" class="infraLabelObrigatorio">Gestores:</label>
    <input type="text" id="txtGestor" name="txtGestor" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <select id="selGestores" name="selGestores" size="4" multiple="multiple" class="infraSelect">
    	<?=$strItensSelGestores?>
    </select>
    <div id="divOpcoesGestores">
        <img id="imgLupaGestores" onclick="objLupaGestores.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Gestor" title="Selecionar Gestor" class="infraImg" />

        <a id="btAjuda" <?=PaginaSEI::montarTitleTooltip('Para o funcionamento correto deste parâmetro, no SIP deve ser concedido o Perfil “Gestor de Controle Litigioso” aos usuários selecionados.')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <img border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/>
        </a>
        <br>
        <img id="imgExcluirGestores" onclick="objLupaGestores.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Gestor Selecionado" title="Remover Gestor Selecionado" class="infraImg" />
    </div>
    <input type="hidden" id="hdnIdGestor" name="hdnIdGestor" value="" />
  
  </div>
  
  <!-- TIPOS DE PROCESSOS ASSOCIADOS  -->
  <div id="divTiposProcessos" class="infraAreaDados" style="height:11.5em;"> 
    
    <label id="lblTipoProcessos" for="selTipoProcessos" accesskey="" class="infraLabelObrigatorio">Tipos de Processos:</label>
    <input type="text" id="txtTipoProcesso" name="txtTipoProcesso" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <select id="selTipoProcessos" name="selTipoProcessos" size="4" multiple="multiple" class="infraSelect">
    	<?=$strItensSelTipoProcessos?>
    </select>
    <div id="divOpcoesTipoProcessos">
        <img id="imgLupaTipoProcessos" onclick="objLupaTipoProcessos.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Tipo de Processo" title="Selecionar Tipo de Processo" class="infraImg" />
        <br>
        <img id="imgExcluirTipoProcessos" onclick="objLupaTipoProcessos.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Tipo de Processo Selecionado" title="Remover Tipo de Processo Selecionado" class="infraImg" />
    </div>
    <input type="hidden" id="hdnIdTipoProcesso" name="hdnIdTipoProcesso" value="" />
  
  </div>
  
  <!--  UNIDADES ASSOCIADAS -->
  <div id="divUnidades" class="infraAreaDados" style="height:11.5em;"> 
    
    <label id="lblUnidades" for="selUnidades" accesskey="" class="infraLabelObrigatorio">Unidades:</label>
    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <select id="selUnidades" name="selUnidades" size="4" multiple="multiple" class="infraSelect">
    	<?=$strItensSelUnidades?>
    </select>
    <div id="divOpcoesUnidades">
        <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
        <br>
        <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
    </div>
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="" />
  
  </div>
  
   <!-- TIPOS DE PROCESSOS SOBRESTADOS  -->
  <div id="divTiposProcessosSobrestados" class="infraAreaDados" style="height:18em;"> 
    
    <!--  
    <div id="divSinAssinaturaPublicacao" class="infraDivCheckbox infraAreaDados" style="height:2.5em;">  
    <input type="checkbox" id="chkSinAssinaturaPublicacao" name="chkSinAssinaturaPublicacao" class="infraCheckbox" checked="checked" tabindex="512">
    <label id="lblSinAssinaturaPublicacao" for="chkSinAssinaturaPublicacao" accesskey="" class="infraLabelCheckbox">Permitir publicação apenas para documentos assinados</label>
    </div>
    -->
    
    <div id="divChk" class="infraDivCheckbox infraAreaDados">
    
	    <? if( $strItensSelTipoProcessosSobrestados != null && $strItensSelTipoProcessosSobrestados != "") { ?>      
	       <input type="checkbox" checked="checked" name="chkPodeSobrestar" class="infraCheckbox" onchange="selecionarChkSobrestar()" id="chkPodeSobrestar" />
	    <? } else { ?>
	       <input type="checkbox" name="chkPodeSobrestar" onchange="selecionarChkSobrestar()" class="infraCheckbox" id="chkPodeSobrestar" />
	    <? } ?>
	         
	    <label id="lblTipoProcessosChkSobrestados1" for="chkPodeSobrestar" accesskey="" class="infraLabel">        
	      Pode sobrestar a tramitação de outros processos
	    </label>
    
    </div>
    
    <? if( $strItensSelTipoProcessosSobrestados != null && $strItensSelTipoProcessosSobrestados != "") { ?>
       <div id="divTiposProcessosSobrestadosCampos">
    <? } else { ?>
       <div id="divTiposProcessosSobrestadosCampos" style="display: none;">
    <? } ?>
        
	<label id="lblTipoProcessosSobrestados2" for="selTipoProcessosSobrestados" accesskey="" class="infraLabelObrigatorio">
	   Tipos de Processos que podem ser sobrestados:
	</label>
	    
	<input type="text" id="txtTipoProcessoSobrestado" name="txtTipoProcessoSobrestado" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
	    
	    <select id="selTipoProcessosSobrestados" name="selTipoProcessosSobrestados" size="4" multiple="multiple" class="infraSelect">
	    	<?=$strItensSelTipoProcessosSobrestados?>
	    </select>
        <div id="divOpcoesSobrestados">
            <img id="imgLupaTipoProcessosSobrestados" onclick="objLupaTipoProcessosSobrestados.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Tipo de Processo" title="Selecionar Tipo de Processo" class="infraImg" />
            <br>
            <img id="imgExcluirTipoProcessosSobrestados" onclick="objLupaTipoProcessosSobrestados.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Tipo de Processo Selecionado" title="Remover Tipo de Processo Selecionado" class="infraImg" />
        </div>
	    <input type="hidden" id="hdnIdTipoProcessoSobrestado" name="hdnIdTipoProcessoSobrestado" value="" />
	    
    </div>
  
  </div>
    
  <input type="hidden" id="hdnIdTipoControleLitigioso" name="hdnIdTipoControleLitigioso" value="<?=$objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso();?>" />
  <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?=$_POST['hdnUnidades']?>" />
  <input type="hidden" id="hdnTipoProcessos" name="hdnTipoProcessos" value="<?=$_POST['hdnTipoProcessos']?>" />
  <input type="hidden" id="hdnGestores" name="hdnGestores" value="<?=$_POST['hdnGestores']?>" />
  <input type="hidden" id="hdnTipoProcessosSobrestados" name="hdnTipoProcessosSobrestados" value="<?=$_POST['hdnTipoProcessosSobrestados']?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>