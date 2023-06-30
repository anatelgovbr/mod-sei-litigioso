<script type="text/javascript">

    //======================= INICIANDO BLOCO JAVASCRIPT DA PAGINA =======================================
    var objLupaTipoDocumento = null;
    var objAutoCompletarTipoDocumento = null;

    function inicializar() {

        objAutoCompletarTipoDocumento = new infraAjaxAutoCompletar('hdnIdSerie', 'txtSerie', '<?=$strLinkAjaxTipoDocumento?>');
        objAutoCompletarTipoDocumento.limparCampo = true;
        objAutoCompletarTipoDocumento.tamanhoMinimo = 3;
        objAutoCompletarTipoDocumento.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtSerie').value;
        };

        objAutoCompletarTipoDocumento.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricao').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Tipo de Documento já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selDescricao'), descricao, id);

                    objLupaTipoDocumento.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtSerie').value = '';
                document.getElementById('txtSerie').focus();

            }
        };

        objLupaTipoDocumento = new infraLupaSelect('selDescricao', 'hdnTipoDocumento', '<?=$strLinkTipoDocumentoSelecao?>');

        if ('<?=$_GET['acao']?>' == 'md_lit_situacao_cadastrar') {
            document.getElementById('selFase').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_situacao_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }

        infraEfeitoTabelas();

    } //fim funcao inicializar

    function validarCadastro() {

        if (infraTrim(document.getElementById('selFase').value) == '') {
            alert('Informe a Fase.');
            document.getElementById('selFase').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome da Situação.');
            document.getElementById('txtNome').focus();
            return false;
        }

        //tipo de documento associado
        var optionsTipoDocs = document.getElementById('selDescricao').options;

        if (optionsTipoDocs.length == 0) {
            alert('Informe ao menos um tipo de documento associado.');
            document.getElementById('selDescricao').focus();
            return false;
        }

        return true;

    }

    function OnSubmitForm() {
        return validarCadastro();
    }
</script>