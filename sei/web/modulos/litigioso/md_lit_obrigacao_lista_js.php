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
        if (confirm("Confirma desativa��o do Obriga��o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Obriga��o selecionada.');
            return;
        }
        if (confirm("Confirma desativa��o das Obriga��es selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoReativar) { ?>
    function acaoReativar(id, desc) {
        if (confirm("Confirma reativa��o da Obriga��o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Obriga��o selecionada.');
            return;
        }
        if (confirm("Confirma reativa��o das Obriga��es selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclus�o do Obriga��o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Obriga��o selecionada.');
            return;
        }
        if (confirm("Confirma exclus�o das Obriga��es selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmObrigacaoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmObrigacaoLista').submit();
        }
    }
    <? } ?>
</script>