<script type="text/javascript">
    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_tipo_decisao_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            //document.getElementById('btnFechar').focus();
            setTimeout("document.getElementById('btnFechar').focus()", 50);
        }

        infraEfeitoTabelas();
    }

    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id, desc) {
        if (confirm("Confirma desativação do Tipo de Decisão \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Decisão selecionado.');
            return;
        }
        if (confirm("Confirma a desativação dos Tipos de Decisão selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoReativar) { ?>
    function acaoReativar(id, desc) {
        if (confirm("Confirma reativação do Tipo de Decisão \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Decisão selecionado.');
            return;
        }
        if (confirm("Confirma reativação dos Tipos de Decisão selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclusão do Tipo de Decisão \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Decisão selecionado.');
            return;
        }
        if (confirm("Confirma exclusão dos Tipos de Decisão selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }
    <? } ?>

</script>