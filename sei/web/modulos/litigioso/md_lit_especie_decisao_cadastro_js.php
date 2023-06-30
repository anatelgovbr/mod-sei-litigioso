<script type="text/javascript">
    $(function(){
        showTipoMulta();

        $('[name="gestaoMulta"]').change(function(){
            if(validarAlteracaoIndicacaoMulta() ==false){
                $('[name="gestaoMulta"]').prop('checked', 'checked').prop('readonly', 'readonly');
                $('[name="tipoMulta"]').not(":checked").prop('readonly', 'readonly');
            }

            showTipoMulta();
        });

        $('[name="tipoMulta"]').change(function(){
            if(validarAlteracaoIndicacaoMulta() == false){
                $('[name="gestaoMulta"]').prop('checked', 'checked').prop('readonly', 'readonly');
                $('[name="tipoMulta"]').not(":checked").prop('checked', 'checked').prop('readonly', 'readonly');
            }

            showTipoMulta();
        });
    });


    function inicializar() {
        carregarComponenteObrigacao();
        if ('<?=$_GET['acao']?>' == 'md_lit_especie_decisao_cadastrar') {
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_especie_decisao_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();
    }

    function showTipoMulta(){
        if($('[name="gestaoMulta"]').is(':checked')){
            $('#opcoesMulta').show();
        } else{
            $('#opcoesMulta').hide();
            $('[name="tipoMulta"]').prop('checked', false);
        }
    }

    function showObrigacoes() {
        document.getElementById('obrigacoesAssociadas').style.display = "none";
        document.getElementById('IndObr').checked ? document.getElementById('obrigacoesAssociadas').style.display = "inherit" : document.getElementById('obrigacoesAssociadas').style.display = "none";
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe a Espécie de Decisão.');
            document.getElementById('txtNome').focus();
            return false;
        }

        var indObrigacao = document.getElementById('IndObr').checked;
        if (indObrigacao) {
            //tipos de controle associados

            var optionsObrigacao = document.getElementById('selDescricaoObrigacao').options;

            if (optionsObrigacao.length == 0) {
                alert('Informe ao menos uma obrigação associada.');
                document.getElementById('selDescricaoObrigacao').focus();
                return false;
            }
        }

        if($('[name="gestaoMulta"]:checked').length > 0 && $('[name="tipoMulta"]:checked').length == 0) {
            alert('Informe o tipo de indicação de multa.');
            return false;
        }

        if(validarMultaPorIntegracao() == false){
            return false;
        }

        if(validarAlteracaoIndicacaoMulta() == false){
            return false;
        }

        return true;
    }

    function validarMultaPorIntegracao() {
        var valid = true;
        if ($('[name="gestaoMulta"]:checked').length > 0 &&
            $('[name="tipoMulta"]:checked').val() == <?php echo MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO ?>) {
            $.ajax({
                type: "POST",
                url: "<?= $strLinkValidarCadastroIntegracao ?>",
                dataType: "xml",
                async: false,
                data: {'tipoMulta':  $('[name="tipoMulta"]:checked').val()},
                success: function (data) {
                    if ($(data).find('success').text() != '1') {
                        teste = data;

                        var integracoes = '';
                        $(data).find('integracoesIncompletas').children().each(function(key, element){
                            integracoes += ""+$(element).text()+"\n";
                        });

                        alert("O Mapeamento das Integrações com o Sistema de Arrecadação não " +
                            "foram realizados. Antes de definir que a Gestão de Multa deve ocorrer " +
                            "por meio de Integração, acesse Administração >> Controle de " +
                            "Processo Litigioso >> Mapeamento das Integrações e insira os " +
                            "Mapeamentos de Integrações das funcionalidades abaixo:\n" +
                            "\n" +
                            integracoes);
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
        }
        return valid;
    }

    function validarAlteracaoIndicacaoMulta() {
        var valid = true;
        var notChecked = $('[name="tipoMulta"]').not(":checked").parent().text().trim();
        var checked = $('[name="tipoMulta"]:checked').parent().text().trim();

        //caso seja desmacado direto o checkbox de indicação de multa
        if($('[name="gestaoMulta"]').is(':checked') == false){
            //usa o radio marcado
            var idTipoMulta = $('[name="tipoMulta"]:checked').val();
            var mensagem = "Não é possível desabilitar a opção Indicação de Multa para esta Espécie de Decisão pois já existem desições com multas cadastradas. \n"+
                "Caso seja necessário esta mudança desative esta Espécie de Decisão e crie uma nova.";
        } else{
            //usa o radio desmarcado
            var idTipoMulta = $('[name="tipoMulta"]').not(":checked").val();
            var mensagem = "Não é possivel modificar a opção Indicação de Multa para "+checked+" pois ja existem Decisões cadastradas com o tipo "+notChecked+". \n "+
                "Caso seja necessário esta mudança, desative esta Espécie de Decisão e crie uma nova.";
        }

        if ('md_lit_especie_decisao_alterar' == '<?php echo $_GET['acao'] ?>') {
            $.ajax({
                type: "POST",
                url: "<?= $strLinkValidarAlteracaoIntegracao ?>",
                dataType: "xml",
                async: false,
                data: {'tipoMulta': idTipoMulta, 'hdnIdEspecieDecisaoLitigioso': $('[name="hdnIdEspecieDecisaoLitigioso"]').val()},
                success: function (data) {
                    if ($(data).find('resultado').text() == '1') {
                        alert(mensagem);
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
        }
        return valid;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function carregarComponenteObrigacao() {

        objAutoCompletarObrigacao = new infraAjaxAutoCompletar('hdnIdObrigacao', 'txtObrigacao', '<?=$strLinkAjaxObrigacao?>');
        objAutoCompletarObrigacao.limparCampo = true;
        objAutoCompletarObrigacao.tamanhoMinimo = 3;
        objAutoCompletarObrigacao.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtObrigacao').value;
        };

        objAutoCompletarObrigacao.processarResultado = function (id, nome, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricaoObrigacao').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Obrigação já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoObrigacao'), nome, id);

                    objLupaObrigacao.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtObrigacao').value = '';
                document.getElementById('txtObrigacao').focus();

            }
        };

        objLupaObrigacao = new infraLupaSelect('selDescricaoObrigacao', 'hdnObrigacao', '<?=$strLinkObrigacaoSelecao?>');

    }

</script>