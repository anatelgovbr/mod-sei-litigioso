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
        if (confirm("Confirma desativa��o do Servi�o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmServicoLista').action = '<?= $strUrlDesativar ?>';
            document.getElementById('frmServicoLista').submit();
        }
    }

    function reativar(id, desc, url) {
        if (confirm("Confirma reativa��o do Servi�o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmServicoLista').action = url;
            document.getElementById('frmServicoLista').submit();
        }
    }

    function excluir(id, desc) {
        if (confirm("Confirma exclus�o do Servi�o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmServicoLista').action = '<?= $strUrlExcluir ?>';
            document.getElementById('frmServicoLista').submit();
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

    function addEventoEnter() {
        document.addEventListener("keypress", function (evt) {
            var key_code = evt.keyCode ? evt.keyCode :
                evt.charCode ? evt.charCode :
                    evt.which ? evt.which : void 0;

            if (key_code == 13) {
                pesquisar();
            }

        });
    }

</script>
