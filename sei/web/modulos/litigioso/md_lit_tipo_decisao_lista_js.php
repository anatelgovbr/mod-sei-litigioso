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
        if (confirm("Confirma desativa��o do Tipo de Decis�o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Decis�o selecionado.');
            return;
        }
        if (confirm("Confirma a desativa��o dos Tipos de Decis�o selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoReativar) { ?>
    function acaoReativar(id, desc) {
        if (confirm("Confirma reativa��o do Tipo de Decis�o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Decis�o selecionado.');
            return;
        }
        if (confirm("Confirma reativa��o dos Tipos de Decis�o selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclus�o do Tipo de Decis�o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Decis�o selecionado.');
            return;
        }
        if (confirm("Confirma exclus�o dos Tipos de Decis�o selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoDecisaoLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoDecisaoLitigiosoLista').submit();
        }
    }
    <? } ?>

</script>