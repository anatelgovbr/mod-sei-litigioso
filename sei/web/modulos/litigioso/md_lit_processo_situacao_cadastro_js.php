
<script type="text/javascript">

var objTabelaDinamicaSituacao = null;
var objTabelaDinamicaDecisao  = null;
var isTpSitDecisoria          = false;
var isTpSitRecursal           = false;
var isTpSitLivre              = false;
var isAlterarSit              = false;
var isAlterarRegNovo          = false;
var isDadosFinalizadosAlt     = false;
var registroBancoExcluido     = false;
var adicionouSit              = false;

    function inicializar(){
        inicializarGrids();

        //Fieldset Situa��o
        carregarDependenciaFasesSituacao();
        manterComboFasesAtualizada();

        //Geral
        bloquearTelaOpenProcesso();
    }

    function inicializarGrids(){
       inicializarGridSituacao();
       inicializarGridDecisao();
       carregarDependenciaMulta();
    }

function OnSubmitForm() {
    if (!verificarCondicionaisSituacao()) {
        return false;
    }
    if(!verificarCondicionaisDecisao()){
        return false;
    }
    if(!verificarCondicionaisMulta()){
        return false;
    }

    var salvar = confirm('Ap�s os dados serem salvos, a altera��o da Situa��o adicionada ser� poss�vel apenas pelo Gestor do Controle. Confirma a opera��o?');
    if(salvar){
        //exibe o aviso pois o servi�o pode deixar a requisi��o lenta
        infraExibirAviso(false);
        return true;
    }
    return false;
}

function bloquearTelaOpenProcesso(){
    var hdnOpenProcesso = document.getElementById('hdnOpenProcesso').value;
    var objs            = document.getElementsByClassName('bloquearTelaProcesso');

    if(hdnOpenProcesso == '1'){
        for (var i = 0; i < objs.length; i++) {
            objs[i].disabled = true;
        }
    }
}


</script>

