<script type="text/javascript">
    var objLupaConduta = null;
    var objAutoCompletarConduta = null;
    var objLupaTipoControle = null;
    var objAutoCompletarTipoControle = null;
    var objAutoCompletarRevogarDispositivo = null;
    var objLupaRevogarDispositivo = null;

    function inicializar() {

        carregarComponenteConduta();
        carregarComponenteTipoControle();
        carregarComponenteRevogado();


        if ('<?=$_GET['acao']?>' == 'md_lit_dispositivo_normativo_cadastrar') {
            document.getElementById('txtNorma').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_dispositivo_normativo_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();


    }

    function isValidURL() {
        var str = document.getElementById('txtUrlNome').value;
        str = str.trim();

        if ((str) != '') {
            var urlexp = new RegExp('^(http|https):[\/]{2,2}([A-Za-z0-9-_]{1,63}\\.)+([A-Za-z]{2,6})*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$');

            if (!urlexp.test(str)) {
                alert('URL da Norma inválido.');
                return false;
            }

        }

        return true;

    }


    function carregarComponenteTipoControle() {

        objAutoCompletarTipoControle = new infraAjaxAutoCompletar('hdnIdTipoControle', 'txtTipoControle', '<?=$strLinkAjaxTipoControle?>');
        objAutoCompletarTipoControle.limparCampo = true;
        objAutoCompletarTipoControle.tamanhoMinimo = 3;
        objAutoCompletarTipoControle.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtTipoControle').value;
        };

        objAutoCompletarTipoControle.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricaoTipoControle').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Tipo de Controle já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    var desc = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoTipoControle'), desc, id);

                    objLupaTipoControle.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtTipoControle').value = '';
                document.getElementById('txtTipoControle').focus();

            }
        };

        objLupaTipoControle = new infraLupaSelect('selDescricaoTipoControle', 'hdnTipoControle', '<?=$strLinkTipoControleSelecao?>');

    }

    function carregarComponenteConduta() {

        objAutoCompletarConduta = new infraAjaxAutoCompletar('hdnIdConduta', 'txtConduta', '<?=$strLinkAjaxConduta?>');
        objAutoCompletarConduta.limparCampo = true;
        objAutoCompletarConduta.tamanhoMinimo = 3;
        objAutoCompletarConduta.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtConduta').value;
        };

        objAutoCompletarConduta.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricaoConduta').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Conduta já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }
                    var desc = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoConduta'), desc, id);

                    objLupaConduta.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtConduta').value = '';
                document.getElementById('txtConduta').focus();

            }
        };

        objLupaConduta = new infraLupaSelect('selDescricaoConduta', 'hdnConduta', '<?=$strLinkCondutaSelecao?>');

    }

    function carregarComponenteRevogado() {

        objAutoCompletarRevogarDispositivo = new infraAjaxAutoCompletar('hdnIdRevogarDispositivo', 'txtRevogarDispositivo', '<?=$strLinkAjaxDispositivoNormativo?>');
        objAutoCompletarRevogarDispositivo.limparCampo = true;
        objAutoCompletarRevogarDispositivo.tamanhoMinimo = 3;
        objAutoCompletarRevogarDispositivo.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtRevogarDispositivo').value;
        };

        objAutoCompletarRevogarDispositivo.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selRevogarDispositivo').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Dispositivo normativo já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }
                    var desc = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selRevogarDispositivo'), desc, id);

                    objLupaRevogarDispositivo.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtRevogarDispositivo').value = '';
                document.getElementById('txtRevogarDispositivo').focus();

            }
        };

        objLupaRevogarDispositivo = new infraLupaSelect('selRevogarDispositivo', 'hdnRevogarDispositivo', '<?=$strLinkDispositivoNormativoSelecao?>');

    }


    function validarCadastro() {

        if (infraTrim(document.getElementById('txtNorma').value) == '') {
            alert('Informe a Norma.');
            document.getElementById('txtNorma').focus();
            return false;
        }

        //dispositivo
        if (infraTrim(document.getElementById('txtDispositivo').value) == '') {
            alert('Informe o Dispositivo.');
            document.getElementById('txtDispositivo').focus();
            return false;
        }

        //descricao do dispositivo
        if (infraTrim(document.getElementById('txtDescricaoDispositivo').value) == '') {
            alert('Informe a Descrição do Dispositivo.');
            document.getElementById('txtDescricaoDispositivo').focus();
            return false;
        }

        if (infraTrim(document.getElementById('hdnConduta').value) == '') {
            alert('É obrigatório associar pelo menos uma conduta ao dispositivo normativo!');
            document.getElementById('txtDescricaoDispositivo').focus();
            return false;
        }

        if (!isValidURL()) {
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

</script>