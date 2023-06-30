<script type="text/javascript">
    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_obrigacao_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            document.getElementById('btnFechar').focus();
        }
        infraEfeitoTabelas();
    }

    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id, desc) {
        if (confirm("Confirma desativação do Obrigação \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Obrigação selecionada.');
            return;
        }
        if (confirm("Confirma desativação das Obrigações selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoReativar) { ?>
    function acaoReativar(id, desc) {
        if (confirm("Confirma reativação da Obrigação \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Obrigação selecionada.');
            return;
        }
        if (confirm("Confirma reativação das Obrigações selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclusão do Obrigação \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Obrigação selecionada.');
            return;
        }
        if (confirm("Confirma exclusão das Obrigações selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }
    <? } ?>
</script>