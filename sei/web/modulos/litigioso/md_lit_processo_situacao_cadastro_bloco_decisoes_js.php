
<script type="text/javascript">
    function abrirModalCadastarDecisao() {
        infraAbrirJanela('<?= $urlCadastrarDecisao ?>',
            'CadastrarDecisao',
            1080,
            600);
    }

    function inicializarGridDecisao(){
        objTabelaDinamicaDecisao = new infraTabelaDinamica('tbDecisao', 'hdnTbDecisao', false, false);
        objTabelaDinamicaDecisao.gerarEfeitoTabela = true;
    }
    function abrirModalHistoricoDecisao(){
        infraAbrirJanela('<?= $urlHistoricoDecisao ?>',
            'HistoricoDecisao',
            780,
            600);
    }

    function verificarCondicionaisDecisao(){
        if(document.getElementById('hdnErroSituacao').value == 0 && isTpSitDecisoria && !verificarDecisaoNovo()){
            alert('cadastre ao menos uma Infração na modal de Cadastro de Decisões!');
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

