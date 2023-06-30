<script type="text/javascript">

    var objTabelaDinamicaSituacao = null;
    var objTabelaDinamicaDecisao = null;
    var isTpSitDecisoria = false;
    var isTpSitRecursal = false;
    var isTpSitLivre = false;
    var isAlterarSit = false;
    var isAlterarRegNovo = false;
    var isDadosFinalizadosAlt = false;
    var registroBancoExcluido = false;
    var adicionouSit = false;

    function inicializar() {
        inicializarGrids();

        //Fieldset Situação
        carregarDependenciaFasesSituacao();
        manterComboFasesAtualizada();

        //Geral
        bloquearTelaOpenProcesso();
    }

    function inicializarGrids() {
        inicializarGridSituacao();
        inicializarGridDecisao();
        if (typeof carregarDependenciaMulta != "undefined")
            carregarDependenciaMulta();
    }

    function OnSubmitForm() {
        if (infraJanelaModal != null) {
            infraJanelaModal.close();
        }
        infraFecharJanelaModal();
        if (typeof verificarCondicionaisSituacao != "undefined" && !verificarCondicionaisSituacao()) {
            return false;
        }
        if (typeof verificarCondicionaisDecisao != "undefined" && !verificarCondicionaisDecisao()) {
            return false;
        }//verifica primeiro se o fieldset foi carregado (existe condicional para carregar o JS)
        if (typeof verificarCondicionaisMulta != "undefined" && !verificarCondicionaisMulta()) {
            return false;
        }

        //exibe o aviso pois o web-service pode deixar a requisição lenta
        document.getElementById('txtDtConstituicao').disabled = false;
        document.getElementById('chkHouveConstituicao').disabled = false;
        document.getElementById('chkReducaoRenuncia').disabled =  habilitarDescontoDecorrente();
        infraExibirAviso(false);
        return true;
    }

    function bloquearTelaOpenProcesso() {
        var hdnOpenProcesso = document.getElementById('hdnOpenProcesso').value;
        var objs = document.getElementsByClassName('bloquearTelaProcesso');

        if (hdnOpenProcesso == '1') {
            for (var i = 0; i < objs.length; i++) {
                objs[i].disabled = true;
            }
        }
    }

    function modalParametrizarSituacao(element) {
        infraAbrirJanela('<?= $strLinkModalParametrizarSituacao ?>', 'janelaParametrizarSituacao', 1200, 700);
    }
</script>

