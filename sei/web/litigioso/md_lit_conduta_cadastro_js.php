<script type="text/javascript">
    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_lit_conduta_cadastrar') {
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_conduta_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe a Conduta.');
            document.getElementById('txtNome').focus();
            return false;
        }


        return true;
    }


    function OnSubmitForm() {
        return validarCadastro();
    }

</script>