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
    
</script>