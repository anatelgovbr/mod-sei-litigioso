<script type="text/javascript">
    function abrirModalCadastarDecisao() {
        infraAbrirJanelaModal('<?= $urlCadastrarDecisao ?>',
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
        if (document.getElementById('hdnErroSituacao').value == 0 && isTpSitDecisoria && !verificarDecisaoNovo() && (document.getElementById('hdnIdMdLitFuncionalidade').value != <?php echo MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO ?>)) {
            alert('cadastre ao menos uma Infração na modal de Cadastro de Decisões!');
            return false;
        }

        if (document.getElementById('fieldsetDecisao').style.display != 'none' && !verificarDecisaoPendenteCadastro()) {
            alert('É necessário cadastrar as decisões de todas infrações antes de salvar.');
            return false;
        }

        if (
            !verificaUltimaSituacaoDecisao() &&
            document.getElementById('hdnIdMdLitFuncionalidade').value &&
            document.getElementById('hdnIdMdLitFuncionalidade').value != <?php echo MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO ?>  &&
            !verificarPreenchimentoDecisaoIncompleto()
        ) {
            document.getElementById('hdnIdMdLitFuncionalidade').value = null;
            alert('É necessário completar o preenchimento das decisões antes de salvar.');
            return false;
        }

        return true;
    }

    function verificaUltimaSituacaoDecisao(){
        const situacao = $('#tbSituacao tr').eq(1).find('td:eq(17)').text();
        const novaSituacao = situacao.replace('(','').replace(')','').replace('ó','o');
        if (novaSituacao == 'Decisoria'){
            return true;
        }
        return false;
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

    function verificarDecisaoPendenteCadastro() {
        var arrDecisao = objTabelaDinamicaDecisao.obterItens();
        var arrIdInfracoesDecisao = [];

        for (var i = 0; i < arrDecisao.length; i++) {
            if (!arrIdInfracoesDecisao.includes(arrDecisao[i][1])) {
                arrIdInfracoesDecisao.push(arrDecisao[i][1])
            }
        }
        var countIdInfracoesDecisoes = arrIdInfracoesDecisao.length;
        var countIdInfracoesProcesso = '<?= count($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO) ?>'
        if (countIdInfracoesDecisoes != countIdInfracoesProcesso) {
            return false;
        }
        return true;
    }

    function verificarPreenchimentoDecisaoIncompleto(){

        var valueNovo = document.getElementById('hdnTbDecisao').value;
        var arrayRetorno = processarItemListas(valueNovo);

        for(linhas = 0; linhas < arrayRetorno.length; linhas++){
            if(arrayRetorno[linhas][18] == 'S'){
                return false;
            }
        }

        return true;
    }

    function processarItemListas(linhaString){
        var linhas = linhaString.split("¥");
        var arrayRetorno = [];
        for (linha = 0; linha < linhas.length; linha++) {
            var itens = linhas[linha].split("±");
            var arrayParcial = [];
            for (item = 0; item < itens.length; item++){
                arrayParcial.push(itens[item]);

            }
            arrayRetorno.push(arrayParcial);
        }
        return arrayRetorno;
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

