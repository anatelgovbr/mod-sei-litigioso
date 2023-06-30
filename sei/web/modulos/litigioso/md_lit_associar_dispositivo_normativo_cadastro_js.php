<script type="text/javascript">
    var objLupaDispositivoNormativo = null;
    var objAutoCompletarDispositivoNormativo = null;

    function inicializar() {

        carregarComponenteDispositivo();
        document.getElementById('btnCancelar').focus();
        infraEfeitoTabelas();
    }

    function carregarComponenteDispositivo() {

        objAutoCompletarDispositivoNormativo = new infraAjaxAutoCompletar('hdnIdDispositivoNormativo', 'txtDispositivoNormativo', '<?=$strLinkAjaxDispositivoNormativo?>');
        objAutoCompletarDispositivoNormativo.limparCampo = true;
        objAutoCompletarDispositivoNormativo.tamanhoMinimo = 3;
        objAutoCompletarDispositivoNormativo.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtDispositivoNormativo').value;
        };

        objAutoCompletarDispositivoNormativo.processarResultado = function (id, descricao, complemento) {

            if (id != '') {

                var options = document.getElementById('selDescricaoDispositivoNormativo').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {

                        if (options[i].value == id) {
                            alert('Dispositivo Normativo já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    var texto = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoDispositivoNormativo'), texto, id);
                    objLupaDispositivoNormativo.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtDispositivoNormativo').value = '';
                document.getElementById('txtDispositivoNormativo').focus();

            }
        };

        objLupaDispositivoNormativo = new infraLupaSelect('selDescricaoDispositivoNormativo', 'hdnDispositivosNormativos', '<?=$strLinkDispositivoNormativoSelecao?>');

    }

    function validarCadastro() {

        //dispositivos normativos
        var optionsDispositivoNormativo = document.getElementById('selDescricaoDispositivoNormativo').options;

        if (optionsDispositivoNormativo.length == 0) {

            if (confirm("Nenhum Dispositivo Normativo foi associado. Deseja salvar o registro?")) {
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

</script>