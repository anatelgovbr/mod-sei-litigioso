<script type="text/javascript">
    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_lit_motivo_cadastrar') {
            document.getElementById('txtDescricao').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_motivo_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas(true);
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtDescricao').value) == '') {
            alert('Informe a descrição.');
            document.getElementById('txtDescricao').focus();
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }
</script>
