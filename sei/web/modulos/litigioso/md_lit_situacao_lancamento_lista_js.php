<script type="text/javascript">
    function inicializar() {
        addEventoEnter();
        if ('<?= $_GET['acao'] ?>' == 'md_lit_situacao_lancamento_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            infraEfeitoTabelas();
        }
    }

    function pesquisar() {
        document.getElementById('frmSituacaoLancamentoLista').action = '<?= $strUrlAcaoForm ?>';
        document.getElementById('frmSituacaoLancamentoLista').submit();
    }

    function desativar(id, desc) {
        if (confirm("Confirma desativação da Situação de Lançamento \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmSituacaoLancamentoLista').action = '<?= $strUrlDesativar ?>';
            document.getElementById('frmSituacaoLancamentoLista').submit();
        }
    }

    function reativar(id, desc, url) {
        if (confirm("Confirma reativação da Situação de Lançamento \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmSituacaoLancamentoLista').action = url;
            document.getElementById('frmSituacaoLancamentoLista').submit();
        }
    }

    function excluir(id, desc) {
        if (confirm("Confirma exclusão da Situação de Lançamento \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmSituacaoLancamentoLista').action = '<?= $strUrlExcluir ?>';
            document.getElementById('frmSituacaoLancamentoLista').submit();
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