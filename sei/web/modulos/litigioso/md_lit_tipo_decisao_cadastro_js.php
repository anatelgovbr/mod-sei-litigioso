<script type="text/javascript">
    var objLupaEspecies = null;
    var objAutoCompletarEspecies = null;
    var objAjaxHipoteseLegal = null;

    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_tipo_decisao_cadastrar') {
            document.getElementById('txtNome').focus();
        } else if ('<?= $_GET['acao'] ?>' == 'md_lit_tipo_decisao_consultar') {
            infraDesabilitarCamposAreaDados();
            return;
        }

        objAutoCompletarEspecies = new infraAjaxAutoCompletar('hdnIdEspecies', 'txtEspecies', '<?= $strLinkAjaxEspecies ?>');
        objAutoCompletarEspecies.limparCampo = true;
        objAutoCompletarEspecies.tamanhoMinimo = 3;
        objAutoCompletarEspecies.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtEspecies').value;
        };

        objAutoCompletarEspecies.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
                var options = document.getElementById('selEspecies').options;
                var arrEspecies = new Array();

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Esp�cie j� consta na lista.');
                        break;
                    }

                    //recupera os ids ja vinculados
                    arrEspecies.push(options[i].value);
                }

                arrEspecies.push(id);
                var objValidado = validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies);
                if (objValidado.valid == false) {
                    alert(objValidado.mensagem);
                    $('[name="txtEspecies"]').val('');
                    return false;
                }

                if (i == options.length) {
                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }
                    var desc = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selEspecies'), desc, id);
                    objLupaEspecies.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtEspecies').value = '';
                document.getElementById('txtEspecies').focus();
            }
        };

        objLupaEspecies = new infraLupaSelect('selEspecies', 'hdnEspecies', '<?= $strLinkEspeciesSelecao ?>');


        formatarExibicaoNivelAcesso();

        infraEfeitoTabelas();
    }

    function OnSubmitForm() {

        if (validarForm()) {
            return true;
        }
        return false;
    }

    function validarForm() {

        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome.');
            document.getElementById('txtNome').focus();
            return false;
        }

        if (validarTiposGestaoMulta() == false) {
            return false;
        }
        /*
        if (infraTrim(document.getElementById('txaDescricao').value)=='') {
        alert('Informe a Descri��o.');
        document.getElementById('txaDescricao').focus();
        return false;
        }
        */

        return true;
    }

    function formatarExibicaoNivelAcesso() {

        if (!document.getElementById('chkSinSigilosoPermitido').checked) {
            document.getElementById('optSigilosoSugestao').checked = false;
            document.getElementById('optSigilosoSugestao').disabled = true;
            document.getElementById('spnSigilosoSugestao').disabled = true;
        } else {
            document.getElementById('optSigilosoSugestao').disabled = false;
            document.getElementById('spnSigilosoSugestao').disabled = false;
        }

        if (!document.getElementById('chkSinRestritoPermitido').checked) {
            document.getElementById('optRestritoSugestao').checked = false;
            document.getElementById('optRestritoSugestao').disabled = true;
            document.getElementById('spnRestritoSugestao').disabled = true;
        } else {
            document.getElementById('optRestritoSugestao').disabled = false;
            document.getElementById('spnRestritoSugestao').disabled = false;
        }

        if (!document.getElementById('chkSinPublicoPermitido').checked) {
            document.getElementById('optPublicoSugestao').checked = false;
            document.getElementById('optPublicoSugestao').disabled = true;
            document.getElementById('spnPublicoSugestao').disabled = true;
        } else {
            document.getElementById('optPublicoSugestao').disabled = false;
            document.getElementById('spnPublicoSugestao').disabled = false;
        }

    }

    function alterarNivelAcessoSugerido() {

        <? if ($numHabilitarGrauSigilo) { ?>
        if (document.getElementById('optSigilosoSugestao').checked) {
            document.getElementById('divGrauSigilo').style.display = 'block';
        } else {
            document.getElementById('selGrauSigilo').options[0].selected = true;
            document.getElementById('divGrauSigilo').style.display = 'none';
        }
        <? } ?>

        <? if ($numHabilitarHipoteseLegal) { ?>
        objAjaxHipoteseLegal.executar();
        <? } ?>
    }


    function validarTiposGestaoMulta() {
        var options = document.getElementById('selEspecies').options;
        var arrEspecies = new Array();

        for (var i = 0; i < options.length; i++) {
            arrEspecies.push(options[i].value);
        }

        var objValidado = validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies);
        if (objValidado.valid == false) {
            var menssagem = "N�o � possivel associar Esp�cies de Decis�o com Indica��o de Multa de tipos distintos (Gest�o por Integra��o e Apenas Indica��o de Valor).\n" +
                "\n" +
                "Revise as Esp�cies de Decis�o abaixo listadas que est� pretendendo associar:\n\n";


            //mostrar a lista de especies conflitantes
            var arrEspeciesDto = recuperarEspecies(arrEspecies);
            var especies = '';
            $.each(arrEspeciesDto, function (key, value) {
                //se tem tipo de indica��o de multa entra na lista
                if (parseInt($(value).find('StaTipoIndicacaoMulta').text()) == $(value).find('StaTipoIndicacaoMulta').text()) {
                    especies += ' - ' + $(value).find('Nome').text() + ' \n';
                }
            });

            menssagem = menssagem + especies;
            alert(menssagem);
            $('[name="txtEspecies"]').val('');
            return false;
        }

        return true;
    }

    function recuperarEspecies(arrEspecies) {
        var dados;
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_recuperar_especie_decisao') ?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId': arrEspecies},
            success: function (data) {
                if ($(data).find('MdLitEspecieDecisaoDTO').length > 0) {
                    dados = $(data).find('MdLitEspecieDecisaoDTO');
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o valida��o do do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

        return dados;
    }

    /**
     * Valida��o para nao permitir que um tipo de decis�o tenha associada especies com tipo de miltas diferentes, por integracao e por valor
     * @param arrEspecies
     * @returns {boolean}
     */
    function validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies) {
        var valid = true;

        $.ajax({
            type: "POST",
            url: "<?= $strLinkValidarEspecieDecisao ?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId': arrEspecies},
            success: function (data) {
                if ($(data).find('resultado').text() == '0') {
                    valid = false;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o valida��o do do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

        return {
            'valid': valid,
            'mensagem': "N�o � possivel associar Esp�cies de Decis�o com Indica��o de Multa de tipos distintos (Gest�o por Integra��o e Apenas Indica��o de Valor).\n" +
                "\nEscolha Esp�cies de Decis�o diferentes ou entre em contato com o Gestor do SEI para d�vidas a respeito."
        };
    }

</script>