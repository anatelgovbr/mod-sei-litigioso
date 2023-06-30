<script type="text/javascript">
    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_lit_fase_cadastrar') {
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_fase_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome da Fase.');
            document.getElementById('txtNome').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtDescricao').value) == '') {
            alert('Informe a Descrição.');
            document.getElementById('txtDescricao').focus();
            return false;
        }

        return true;
    }


    function OnSubmitForm() {
        return validarCadastro();
    }

</script>