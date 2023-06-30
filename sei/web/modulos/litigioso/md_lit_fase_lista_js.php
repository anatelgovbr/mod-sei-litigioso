<script type="text/javascript">
    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_fase_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            document.getElementById('btnFechar').focus();
        }
        infraEfeitoTabelas();
    }

    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id, desc) {
        if (confirm("Confirma desativação da Fase \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmFaseLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmFaseLitigiosoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma fase selecionada.');
            return;
        }
        if (confirm("Confirma a desativação das Fases selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmFaseLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmFaseLitigiosoLista').submit();
        }
    }
    <? } ?>

    function acaoReativar(id, desc) {
        if (confirm("Confirma reativação da Fase \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmFaseLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmFaseLitigiosoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Fase selecionado.');
            return;
        }
        if (confirm("Confirma a reativação das Fases selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmFaseLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmFaseLitigiosoLista').submit();
        }
    }

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclusão da Fase \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmFaseLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmFaseLitigiosoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma fase selecionada.');
            return;
        }
        if (confirm("Confirma a exclusão das Fases selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmFaseLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmFaseLitigiosoLista').submit();
        }
    }
    <? } ?>
</script>