<script type="text/javascript">
    var objTabelaAssociacao = null;
    function inicializar() {
        document.getElementById('btnCancelar').focus();
        infraEfeitoTabelas();
        buscarComboEspecieDecisao();
        objTabelaAssociacao = new infraTabelaDinamica('tableTiposDecisao', 'hdnTbTiposDecisao', false, true);
        objTabelaAssociacao.gerarEfeitoTabela = true;
        objTabelaAssociacao.inserirNoInicio = false;
        objTabelaAssociacao.exibirMensagens = true;

    }


    function validarCadastro() {

        var optionsTipoDecisao = document.getElementById('selDescricaoTipoDecisao').options;

        if (optionsTipoDecisao.length == 0) {

            if (confirm("Nenhum Tipo de Decisão foi associado. Deseja salvar o registro?")) {
                return true;
            } else {
                return false;
            }

        } else {
            return true;
        }

    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function buscarComboEspecieDecisao(){

        //Ajax para carregar as especie de decisao de acordo com o tipo de decisao
        objAjaxComboEspecieDecisao = new infraAjaxMontarSelectDependente('selDescricaoTipoDecisaoLitigioso', 'selEspecieDecisaoLitigioso', '<?=$strLinkAjaxComboEspecieDecisao?>');

        objAjaxComboEspecieDecisao.prepararExecucao = function () {
            if (document.getElementById('selDescricaoTipoDecisaoLitigioso').value != 'null' || document.getElementById('selDescricaoTipoDecisaoLitigioso').value != '') {
                return infraAjaxMontarPostPadraoSelect('null', '', 'null') +'&id_md_lit_tipo_decisao=' + document.getElementById('selDescricaoTipoDecisaoLitigioso').value;
            }

            return false;
        };

        objAjaxComboEspecieDecisao.processarResultado = function(){
            var itensTabela = objTabelaAssociacao.obterItens();
            for(var i = 0; i < itensTabela.length; i++){
                if(document.getElementById('selDescricaoTipoDecisaoLitigioso').value == itensTabela[i][1]){
                    removerOptionSelect(document.getElementById('selEspecieDecisaoLitigioso'),itensTabela[i][2] );
                }
            }

        };
    }

    function removerOptionSelect(idSelect, valueOption){
        idSelect = infraGetElementById(idSelect);
        for(var i = 0; i < idSelect.options.length; i++){
            if(idSelect.options[i].value == valueOption){
                idSelect.options[i].remove()
            }
        }
    }

    function getNomeSelectSelecionado(idSelect) {
        idSelect = infraGetElementById(idSelect);
        for(var i = 0; i < idSelect.options.length; i++){
            if(idSelect.options[i].value == idSelect.value){
                return idSelect.options[i].text;
            }
        }
    }

    function adicionarTipoDecisao(){
        var idTipoDecisao = document.getElementById('selDescricaoTipoDecisaoLitigioso').value;
        var idEspecieDecisao = document.getElementById('selEspecieDecisaoLitigioso').value;
        var nomeTipoDecisao = getNomeSelectSelecionado(document.getElementById('selDescricaoTipoDecisaoLitigioso'));
        var nomeEspecieDecisao = getNomeSelectSelecionado(document.getElementById('selEspecieDecisaoLitigioso'));
        var arrIdEspecieDecisao = new Array();

        if(document.getElementById('selDescricaoTipoDecisaoLitigioso').value == '' || document.getElementById('selDescricaoTipoDecisaoLitigioso').value == 'null'){
            alert('Selecione o Tipo de Decisão');
            return;
        }

        if(document.getElementById('selEspecieDecisaoLitigioso').value == '' || document.getElementById('selEspecieDecisaoLitigioso').value == 'null'){
            alert('Selecione a Espécies de Decisão');
            return;
        }


        var arrItens = objTabelaAssociacao.obterItens();
        var i;
        for(i = 0; i < arrItens.length; i++){
            arrIdEspecieDecisao.push(arrItens[i][2]);
        }

        arrIdEspecieDecisao.push(idEspecieDecisao);

        var objResult = validarEspecieDecisaoGestaoMultasDiferentes(arrIdEspecieDecisao);
        if(objResult.valid == false){
            alert(objResult.mensagem);
            return false;
        }

        objTabelaAssociacao.adicionar([
            idTipoDecisao+'-'+idEspecieDecisao,
            idTipoDecisao,
            idEspecieDecisao,
            nomeTipoDecisao,
            nomeEspecieDecisao,
            0
        ]);
        objAjaxComboEspecieDecisao.executar();
    }

    function validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies) {
        var valid = true;

        $.ajax({
            type: "POST",
            url: "<?php echo SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_especie_decisao');?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId': arrEspecies},
            success: function (data) {
                if ($(data).find('resultado').text() == '0') {
                    valid = false;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o validação do do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
        return {'valid': valid, 'mensagem': "Não é possivel associar Espécies de Decisão com Indicação de Multa de tipos distintos (Gestão por Integração e Apenas Indicação de Valor). \n" +
                "\nEscolha Espécies de Decisão diferentes ou entre em contato com o Gestor do SEI para dúvidas a respeito"};
    }

</script>