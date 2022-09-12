<script type="text/javascript">
    function inicializar() {
        infraEfeitoTabelas();
    }

    function pesquisar() {
        document.getElementById('frmIntegracaoLista').action = '<?= $strUrlPesquisar ?>';
        document.getElementById('frmIntegracaoLista').submit();
    }

    function desativar(id, desc) {
        if (confirm("Confirma desativação do Integração \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmIntegracaoLista').action = '<?= $strUrlDesativar ?>';
            document.getElementById('frmIntegracaoLista').submit();
        }
    }

    function reativar(id, desc) {
        if (confirm("Confirma reativação do Integração \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmIntegracaoLista').action = '<?= $strUrlReativar ?>';
            document.getElementById('frmIntegracaoLista').submit();
        }
    }

    function excluir(id, desc) {
        if (confirm("Confirma exclusão do Integração \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmIntegracaoLista').action = '<?= $strUrlExcluir ?>';
            document.getElementById('frmIntegracaoLista').submit();
        }
    }

    function novo() {
        location.href = "<?= $strUrlNovo ?>";
    }

    function imprimir() {
        infraImprimirTabela();
    }

    function fechar() {
        location.href = "<?= $strUrlFechar ?>";
    }
</script>