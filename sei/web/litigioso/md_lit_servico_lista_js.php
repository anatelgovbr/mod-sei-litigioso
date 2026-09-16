<script type="text/javascript">
    function inicializar() {
        addEventoEnter();
        if ('<?= $_GET['acao'] ?>' == 'md_lit_servico_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            infraEfeitoTabelas();
        }
    }

    function pesquisar() {
        document.getElementById('frmServicoLista').action = '<?= $strUrlAcaoForm ?>';
        document.getElementById('frmServicoLista').submit();
    }

    function desativar(id, desc) {
        if (confirm("Confirma desativação do Serviço \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmServicoLista').action = '<?= $strUrlDesativar ?>';
            document.getElementById('frmServicoLista').submit();
        }
    }

    function reativar(id, desc, url) {
        if (confirm("Confirma reativação do Serviço \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmServicoLista').action = url;
            document.getElementById('frmServicoLista').submit();
        }
    }

    function excluir(id, desc) {
        if (confirm("Confirma exclusão do Serviço \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmServicoLista').action = '<?= $strUrlExcluir ?>';
            document.getElementById('frmServicoLista').submit();
        }
    }

</script>
