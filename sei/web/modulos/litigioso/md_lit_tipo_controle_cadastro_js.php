//variaveis globais - declarar fora do escopo das fun�oes da pagina
var objLupaUnidades = null;
var objAutoCompletarUnidade = null;

var objLupaGestores = null;
var objAutoCompletarGestor = null;

var objLupaTipoProcessos = null;
var objAutoCompletarTipoProcesso = null;

var objLupaTipoProcessosSobrestados = null;
var objAutoCompletarTipoProcessoSobrestado = null;

function inicializar(){
  
  if ('<?=$_GET['acao']?>'=='md_lit_tipo_controle_cadastrar'){
    document.getElementById('txtSigla').focus();
  } else if ('<?=$_GET['acao']?>'=='md_lit_tipo_controle_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  
    // ================= INICIO - JS para selecao de gestores =============================
    
    objAutoCompletarGestor = new infraAjaxAutoCompletar('hdnIdGestor','txtGestor','<?=$strLinkAjaxGestor?>');
	objAutoCompletarGestor.limparCampo = true;
	
	objAutoCompletarGestor.prepararExecucao = function(){
	    return 'palavras_pesquisa='+document.getElementById('txtGestor').value;
	};
	  
	objAutoCompletarGestor.processarResultado = function(id,descricao,complemento){
	    
	    if (id!=''){
	      var options = document.getElementById('selGestores').options;
	      
	      for(var i=0;i < options.length;i++){
	        if (options[i].value == id){
	          alert('Gestor j� consta na lista.');
	          break;
	        }
	      }
	      
	      if (i==options.length){
	      
	        for(i=0;i < options.length;i++){
	         options[i].selected = false; 
	        }
	        
	        //alert(descricao);
	        //alert(complemento);
	        //strDescricaoFull = descricao + '(' + complemento + ')';
	        //alert( strDescricaoFull );
	        opt = infraSelectAdicionarOption(document.getElementById('selGestores'), descricao ,id);
	        
	        objLupaGestores.atualizar();
	        
	        opt.selected = true;
	      }
	                  
	      document.getElementById('txtGestor').value = '';
	      document.getElementById('txtGestor').focus();
	      
	    }
	  };

   objLupaGestores = new infraLupaSelect('selGestores','hdnGestores','<?=$strLinkGestoresSelecao?>'); 
    
    // ================= FIM - JS para selecao de gestores ================================

   // =================== INICIO - JS para selecao de tipos de processos associados
   
   objAutoCompletarTipoProcesso = new infraAjaxAutoCompletar('hdnIdTipoProcesso','txtTipoProcesso','<?=$strLinkAjaxTipoProcesso?>');
	objAutoCompletarTipoProcesso.limparCampo = true;
	
	objAutoCompletarTipoProcesso.prepararExecucao = function(){
	    return 'palavras_pesquisa='+document.getElementById('txtTipoProcesso').value;
	};
	  
	objAutoCompletarTipoProcesso.processarResultado = function(id,descricao,complemento){
	    
	    if (id!=''){
	      var options = document.getElementById('selTipoProcessos').options;
	      
	      for(var i=0;i < options.length;i++){
	        if (options[i].value == id){
	          alert('Tipo de Processo j� consta na lista.');
	          break;
	        }
	      }
	      
	      if (i==options.length){
	      
	        for(i=0;i < options.length;i++){
	         options[i].selected = false; 
	        }
	      
	        opt = infraSelectAdicionarOption(document.getElementById('selTipoProcessos'),descricao,id);
	        
	        objLupaTipoProcessos.atualizar();
	        
	        opt.selected = true;
	      }
	                  
	      document.getElementById('txtTipoProcesso').value = '';
	      document.getElementById('txtTipoProcesso').focus();
	      
	    }
	  };

   objLupaTipoProcessos = new infraLupaSelect('selTipoProcessos','hdnTipoProcessos','<?=$strLinkTipoProcessosSelecao?>'); 
   
   // =================== FIM - JS para selecao de tipos de processos associados  
  
	// ================== INICIO - JS para selecao de unidades
	objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
	objAutoCompletarUnidade.limparCampo = true;
	
	objAutoCompletarUnidade.prepararExecucao = function(){
	    return 'palavras_pesquisa='+document.getElementById('txtUnidade').value;
	};
	  
	objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
	    
	    if (id!=''){
	      var options = document.getElementById('selUnidades').options;
	      
	      for(var i=0;i < options.length;i++){
	        if (options[i].value == id){
	          alert('Unidade j� consta na lista.');
	          break;
	        }
	      }
	      
	      if (i==options.length){
	      
	        for(i=0;i < options.length;i++){
	         options[i].selected = false; 
	        }
	      
	        opt = infraSelectAdicionarOption(document.getElementById('selUnidades'),descricao,id);
	        
	        objLupaUnidades.atualizar();
	        
	        opt.selected = true;
	      }
	                  
	      document.getElementById('txtUnidade').value = '';
	      document.getElementById('txtUnidade').focus();
	      
	    }
	  };

   objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkUnidadesSelecao?>'); 
  
  // ================== FIM - JS para selecao de unidades
  
  // =================== INICIO - JS para selecao de tipos de processos sobrestados
   
   objAutoCompletarTipoProcessoSobrestado = new infraAjaxAutoCompletar('hdnIdTipoProcessoSobrestado','txtTipoProcessoSobrestado','<?=$strLinkAjaxTipoProcessoSobrestado?>');
	objAutoCompletarTipoProcessoSobrestado.limparCampo = true;
	
	objAutoCompletarTipoProcessoSobrestado.prepararExecucao = function(){
	    return 'palavras_pesquisa='+document.getElementById('txtTipoProcessoSobrestado').value;
	};
	  
	objAutoCompletarTipoProcessoSobrestado.processarResultado = function(id,descricao,complemento){
	    
	    if (id!=''){
	      var options = document.getElementById('selTipoProcessosSobrestados').options;
	      
	      for(var i=0;i < options.length;i++){
	        if (options[i].value == id){
	          alert('Tipo de Processo Sobrestado j� consta na lista.');
	          break;
	        }
	      }
	      
	      if (i==options.length){
	      
	        for(i=0;i < options.length;i++){
	         options[i].selected = false; 
	        }
	      
	        opt = infraSelectAdicionarOption(document.getElementById('selTipoProcessosSobrestados'),descricao,id);
	        
	        objLupaTipoProcessosSobrestados.atualizar();
	        
	        opt.selected = true;
	      }
	                  
	      document.getElementById('txtTipoProcessoSobrestado').value = '';
	      document.getElementById('txtTipoProcessoSobrestado').focus();
	      
	    }
	  };

   objLupaTipoProcessosSobrestados = new infraLupaSelect('selTipoProcessosSobrestados','hdnTipoProcessosSobrestados','<?=$strLinkTipoProcessosSobrestadosSelecao?>'); 
   
   // =================== FIM - JS para selecao de tipos de processos sobrestados
  
  infraEfeitoTabelas();
  
}

function selecionarChkSobrestar(){
   
   //alert('Selecionar tipo de processo sobrestado');
   //divTiposProcessosSobrestados
   //chkPodeSobrestar
   
   var checkboxSobrestar = document.getElementById("chkPodeSobrestar");
   var divSobrestar = document.getElementById("divTiposProcessosSobrestadosCampos");
   
   if( checkboxSobrestar.checked ){
      //mostra os campos na tela
      divSobrestar.style.display = 'block';
      //divSobrestar.style.visibility = 'show';
   } else {
      //oculta os campos na tela
      divSobrestar.style.display = 'none';
      //divSobrestar.style.visibility = 'hidden';
	  objLupaTipoProcessosSobrestados.limpar();
   }
   
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtSigla').value)=='') {
    alert('Informe a sigla.');
    document.getElementById('txtSigla').focus();
    return false;
  }
  
  if (infraTrim(document.getElementById('txaDescricao').value)=='') {
    alert('Informe a descri��o.');
    document.getElementById('txaDescricao').focus();
    return false;
  }
  
  //alert('teste');
     
  var checkboxSobrestar = document.getElementById("chkPodeSobrestar");
  var optionsGestores = document.getElementById('selGestores').options;
  var optionsProcessos = document.getElementById('selTipoProcessos').options;
  var optionsUnidades = document.getElementById('selUnidades').options;
  var optionsSobrestados = document.getElementById('selTipoProcessosSobrestados').options;
  
  if( optionsGestores.length == 0 ){
    alert('Informe ao menos um gestor.');
    document.getElementById('selGestores').focus();
    return false;
  }
  
  if( optionsProcessos.length == 0 ){
    alert('Informe ao menos um tipo de processo associado.');
    document.getElementById('selTipoProcessos').focus();
    return false;    
  }
  
  if( optionsUnidades.length == 0 ){
    alert('Informe ao menos uma unidade.');
    document.getElementById('selUnidades').focus();
    return false;
  }
  
  if( checkboxSobrestar.checked && optionsSobrestados.length == 0 ){
    alert('Informe ao menos um tipo de processo sobrestado.');
    document.getElementById('selTipoProcessosSobrestados').focus();
    return false;
  }
  
  //alert('teste 2');
      
  return true;
}

function OnSubmitForm() {
  
  ret = validarCadastro();
  
  return ret;
}