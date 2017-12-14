
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

        //Fieldset Situação
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

    var salvar = confirm('Após os dados serem salvos, a alteração da Situação adicionada será possível apenas pelo Gestor do Controle. Confirma a operação?');
    if(salvar){
        //exibe o aviso pois o serviço pode deixar a requisição lenta
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

