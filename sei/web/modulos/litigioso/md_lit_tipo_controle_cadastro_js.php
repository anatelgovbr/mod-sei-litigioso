<script type="text/javascript">
    <?php
    //variaveis para campos de selecao multipla
    $strLinkUnidadesSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidades');
    $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_unidade_auto_completar');

    $strLinkTipoProcessosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcessos');
    $strLinkAjaxTipoProcesso = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_tipo_procedimento_auto_completar');

    $strLinkTipoProcessosSobrestadosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcessosSobrestados');
    $strLinkAjaxTipoProcessoSobrestado = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_tipo_procedimento_auto_completar');

    $strLinkGestoresSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_selecionar&tipo_selecao=2&id_object=objLupaGestores');
    $strLinkAjaxGestor = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');

    $strLinkMotivosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_motivo_selecionar&tipo_selecao=2&id_object=objLupaMotivos');
    $strLinkAjaxMotivos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=motivo_auto_completar');
    ?>

    //variaveis globais - declarar fora do escopo das funçoes da pagina
    var objLupaUnidades = null;
    var objAutoCompletarUnidade = null;

    var objLupaGestores = null;
    var objAutoCompletarGestor = null;

    var objLupaMotivos = null;
    var objAutoCompletarMotivos = null;

    var objLupaTipoProcessos = null;
    var objAutoCompletarTipoProcesso = null;

    var objLupaTipoProcessosSobrestados = null;
    var objAutoCompletarTipoProcessoSobrestado = null;

    function inicializar() {

        if ('<?=$_GET['acao']?>' == 'md_lit_tipo_controle_cadastrar') {
            document.getElementById('txtSigla').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_tipo_controle_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }

        // ================= INICIO - JS para selecao de gestores =============================

        objAutoCompletarGestor = new infraAjaxAutoCompletar('hdnIdGestor', 'txtGestor', '<?=$strLinkAjaxGestor?>');
        objAutoCompletarGestor.limparCampo = true;
        objAutoCompletarGestor.tamanhoMinimo = 3;
        objAutoCompletarGestor.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtGestor').value;
        };

        objAutoCompletarGestor.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selGestores').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Gestor já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    //alert(descricao);
                    //alert(complemento);
                    //strDescricaoFull = descricao + '(' + complemento + ')';
                    //alert( strDescricaoFull );
                    opt = infraSelectAdicionarOption(document.getElementById('selGestores'), descricao, id);

                    objLupaGestores.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtGestor').value = '';
                document.getElementById('txtGestor').focus();

            }
        };

        objLupaGestores = new infraLupaSelect('selGestores', 'hdnGestores', '<?=$strLinkGestoresSelecao?>');

        // ================= FIM - JS para selecao de gestores ================================

        // ================= INICIO - JS para selecao de Motivos ==============================

        objAutoCompletarMotivos = new infraAjaxAutoCompletar('hdnIdMotivos', 'txtMotivos', '<?=$strLinkAjaxMotivos?>');
        objAutoCompletarMotivos.limparCampo = true;
        objAutoCompletarMotivos.tamanhoMinimo = 3;
        objAutoCompletarMotivos.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtMotivos').value;
        };

        objAutoCompletarMotivos.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selMotivos').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Motivo já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selMotivos'), $("<pre>").html(descricao).text(), id);

                    objLupaMotivos.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtMotivos').value = '';
                document.getElementById('txtMotivos').focus();

            }
        };

        objLupaMotivos = new infraLupaSelect('selMotivos', 'hdnMotivos', '<?=$strLinkMotivosSelecao?>');

        // ================= FIM - JS para selecao de motivos =================================

        // =================== INICIO - JS para selecao de tipos de processos associados

        objAutoCompletarTipoProcesso = new infraAjaxAutoCompletar('hdnIdTipoProcesso', 'txtTipoProcesso', '<?=$strLinkAjaxTipoProcesso?>');
        objAutoCompletarTipoProcesso.limparCampo = true;
        objAutoCompletarTipoProcesso.tamanhoMinimo = 3;
        objAutoCompletarTipoProcesso.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtTipoProcesso').value;
        };


        objAutoCompletarTipoProcesso.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selTipoProcessos').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Tipo de Processo já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selTipoProcessos'), descricao, id);

                    objLupaTipoProcessos.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtTipoProcesso').value = '';
                document.getElementById('txtTipoProcesso').focus();

            }
        };

        objLupaTipoProcessos = new infraLupaSelect('selTipoProcessos', 'hdnTipoProcessos', '<?=$strLinkTipoProcessosSelecao?>');

        // =================== FIM - JS para selecao de tipos de processos associados

        // ================== INICIO - JS para selecao de unidades
        objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade', 'txtUnidade', '<?=$strLinkAjaxUnidade?>');
        objAutoCompletarUnidade.limparCampo = true;
        objAutoCompletarUnidade.tamanhoMinimo = 3;
        objAutoCompletarUnidade.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtUnidade').value;
        };

        objAutoCompletarUnidade.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selUnidades').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Unidade já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selUnidades'), descricao, id);

                    objLupaUnidades.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtUnidade').value = '';
                document.getElementById('txtUnidade').focus();

            }
        };

        objLupaUnidades = new infraLupaSelect('selUnidades', 'hdnUnidades', '<?=$strLinkUnidadesSelecao?>');

        // ================== FIM - JS para selecao de unidades

        // =================== INICIO - JS para selecao de tipos de processos sobrestados

        objAutoCompletarTipoProcessoSobrestado = new infraAjaxAutoCompletar('hdnIdTipoProcessoSobrestado', 'txtTipoProcessoSobrestado', '<?=$strLinkAjaxTipoProcessoSobrestado?>');
        objAutoCompletarTipoProcessoSobrestado.limparCampo = true;
        objAutoCompletarTipoProcessoSobrestado.tamanhoMinimo = 3;
        objAutoCompletarTipoProcessoSobrestado.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtTipoProcessoSobrestado').value;
        };

        objAutoCompletarTipoProcessoSobrestado.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selTipoProcessosSobrestados').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Tipo de Processo Sobrestado já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selTipoProcessosSobrestados'), descricao, id);

                    objLupaTipoProcessosSobrestados.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtTipoProcessoSobrestado').value = '';
                document.getElementById('txtTipoProcessoSobrestado').focus();

            }
        };

        objLupaTipoProcessosSobrestados = new infraLupaSelect('selTipoProcessosSobrestados', 'hdnTipoProcessosSobrestados', '<?=$strLinkTipoProcessosSobrestadosSelecao?>');

        // =================== FIM - JS para selecao de tipos de processos sobrestados

        infraEfeitoTabelas();

    }

    function selecionarChkSobrestar() {

        //alert('Selecionar tipo de processo sobrestado');
        //divTiposProcessosSobrestados
        //chkPodeSobrestar

        var checkboxSobrestar = document.getElementById("chkPodeSobrestar");
        var divSobrestar = document.getElementById("divTiposProcessosSobrestadosCampos");
        if (checkboxSobrestar.checked) {
            //mostra os campos na tela
            divSobrestar.style.display = 'block';
            //divSobrestar.style.visibility = 'show';
        } else {
            //oculta os campos na tela
            divSobrestar.style.display = 'none';
            //divSobrestar.style.visibility = 'hidden';
            objLupaTipoProcessosSobrestados.limpar();
        }

    }

    function validarFormatoData(obj) {
        var validar = infraValidarData(obj, false);
        if (!validar) {
            alert('Data Inválida!');
            obj.value = '';
        }
    }

    function validarCadastro() {

        if (infraTrim(document.getElementById('txtSigla').value) == '') {
            alert('Informe a sigla.');
            document.getElementById('txtSigla').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txaDescricao').value) == '') {
            alert('Informe a descrição.');
            document.getElementById('txaDescricao').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtDtCorte').value) == '') {
            alert('Informe a data de corte.');
            document.getElementById('txtDtCorte').focus();
            return false;
        }
        //alert('teste');

        var checkboxSobrestar = document.getElementById("chkPodeSobrestar");
        var optionsGestores = document.getElementById('selGestores').options;
        var optionsMotivos = document.getElementById('selMotivos').options;
        var optionsProcessos = document.getElementById('selTipoProcessos').options;
        var optionsUnidades = document.getElementById('selUnidades').options;
        var optionsSobrestados = document.getElementById('selTipoProcessosSobrestados').options;

        if (optionsGestores.length == 0) {
            alert('Informe ao menos um gestor.');
            document.getElementById('selGestores').focus();
            return false;
        }

        if (optionsProcessos.length == 0) {
            alert('Informe ao menos um tipo de processo associado.');
            document.getElementById('selTipoProcessos').focus();
            return false;
        }

        if (optionsUnidades.length == 0) {
            alert('Informe ao menos uma unidade.');
            document.getElementById('selUnidades').focus();
            return false;
        }

        if (checkboxSobrestar.checked && optionsSobrestados.length == 0) {
            alert('Informe ao menos um tipo de processo sobrestado.');
            document.getElementById('selTipoProcessosSobrestados').focus();
            return false;
        }

        //alert('teste 2');

        return true;
    }

    function OnSubmitForm() {

        ret = validarCadastro();

        return ret;
    }

</script>
