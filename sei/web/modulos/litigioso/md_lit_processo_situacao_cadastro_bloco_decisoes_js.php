
<script type="text/javascript">
    function abrirModalCadastarDecisao() {
        infraAbrirJanela('<?= $urlCadastrarDecisao ?>',
            'CadastrarDecisao',
            1200,
            600);
    }

    function inicializarGridDecisao(){
        objTabelaDinamicaDecisao = new infraTabelaDinamica('tbDecisao', 'hdnTbDecisao', false, false);
        objTabelaDinamicaDecisao.gerarEfeitoTabela = true;


        objTabelaDinamicaDecisao.verificarCadastroParcial = function(){
            var sinParcial = false;
            var arrDecisao = objTabelaDinamicaDecisao.obterItens();

            for(var i = 0; i < arrDecisao.length;i++){
                if(arrDecisao[i][17] == 'S'){
                    sinParcial = true;
                    break;
                }
            }

            return sinParcial;
        }

        if(objTabelaDinamicaDecisao.verificarCadastroParcial()){
            bloquearTelaParaAdicao(true, true);
            alert('Foi identificado que ainda existem infra��es sem Decis�o cadastrada. Para prosseguir com o cadastro de novas Situa��es ou a Gest�o de Multa, antes � necess�rio complementar o Cadastro das Decis�es e salvar.');
        }
    }
    function abrirModalHistoricoDecisao(){
        infraAbrirJanela('<?= $urlHistoricoDecisao ?>',
            'HistoricoDecisao',
            780,
            600);
    }

    function verificarCondicionaisDecisao(){
        if(document.getElementById('hdnErroSituacao').value == 0 && isTpSitDecisoria && !verificarDecisaoNovo()){
            alert('cadastre ao menos uma Infra��o na modal de Cadastro de Decis�es!');
            return false;
        }
        return true;
    }

    function verificarDecisaoNovo(){
        var arrDecisaoItens = objTabelaDinamicaDecisao.obterItens();
        if(arrDecisaoItens.length > 0){
            for(var i = 0; i < arrDecisaoItens.length; i++ ){
                if(arrDecisaoItens[i][0].indexOf("novo_") == 0)
                    return true;
            }
        }
        return false;
    }


</script>

