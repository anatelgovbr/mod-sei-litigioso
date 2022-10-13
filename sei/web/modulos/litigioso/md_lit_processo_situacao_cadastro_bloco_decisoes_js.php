<script type="text/javascript">
    function abrirModalCadastarDecisao() {
        infraAbrirJanela('<?= $urlCadastrarDecisao ?>',
            'CadastrarDecisao',
            1200,
            600);
    }

    function inicializarGridDecisao() {
        objTabelaDinamicaDecisao = new infraTabelaDinamica('tbDecisao', 'hdnTbDecisao', false, false);
        objTabelaDinamicaDecisao.gerarEfeitoTabela = true;


        objTabelaDinamicaDecisao.verificarCadastroParcial = function () {
            var sinParcial = false;
            var arrDecisao = objTabelaDinamicaDecisao.obterItens();

            for (var i = 0; i < arrDecisao.length; i++) {
                if (arrDecisao[i][18] == 'S') {
                    sinParcial = true;
                    break;
                }
            }

            return sinParcial;
        }

        if (objTabelaDinamicaDecisao.verificarCadastroParcial()) {
            //bloquearTelaParaAdicao(true, true);
            alert('Foi identificado que ainda existem infrações sem Decisão cadastrada. Posteriormente, para prosseguir com o cadastro de novas Situações ou a Gestão de Multa, ainda será necessário finalizar o Cadastro das Decisões.');
        }
    }

    function abrirModalHistoricoDecisao() {
        infraAbrirJanelaModal('<?= $urlHistoricoDecisao ?>',
            1200,
            600);
    }

    function verificarCondicionaisDecisao() {
        if (document.getElementById('hdnErroSituacao').value == 0 && isTpSitDecisoria && !verificarDecisaoNovo()) {
            alert('cadastre ao menos uma Infração na modal de Cadastro de Decisões!');
            return false;
        }
        return true;
    }

    function verificarDecisaoNovo() {
        var arrDecisaoItens = objTabelaDinamicaDecisao.obterItens();
        if (arrDecisaoItens.length > 0) {
            for (var i = 0; i < arrDecisaoItens.length; i++) {
                if (arrDecisaoItens[i][0].indexOf("novo_") == 0)
                    return true;
            }
        }
        return false;
    }

    function abrirModalReincidenciaEspecífica() {
        infraAbrirJanelaModal('<?= $urlModalReincidenciaEspecifica ?>',
            1200,
            600);
    }

    function abrirModalAntecedente() {
        infraAbrirJanelaModal('<?= $urlModalAntecedente ?>',
            1200,
            600);
    }


</script>

