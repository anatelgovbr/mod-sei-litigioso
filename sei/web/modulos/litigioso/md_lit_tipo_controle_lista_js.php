<script type="text/javascript">
    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_tipo_controle_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            document.getElementById('btnFechar').focus();
        }
        infraEfeitoTabelas();
    }

    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("O controle de processo litigioso correspondente ser� desativado e os usu�rios n�o conseguir�o cadastrar processos nesse tipo de controle e nenhum outro registro pertinente. \n\n Confirma a desativa��o do Tipo de Controle Litigioso \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoControleLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Controle Litigioso selecionado.');
            return;
        }
        if (confirm("O controle de processo litigioso correspondente ser� desativado e os usu�rios n�o conseguir�o cadastrar processos nesse tipo de controle e nenhum outro registro pertinente. \n\n Confirma a desativa��o dos Tipos de Controles Litigiosos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoControleLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }
    <? } ?>

    function acaoReativar(id, desc) {
        if (confirm("Confirma a reativa��o do Tipo de Controle Litigioso \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoControleLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Controle Litigioso selecionado.');
            return;
        }
        if (confirm("Confirma a reativa��o dos Tipos de Controles Litigiosos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoControleLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("Esta opera��o pode ser demorada. \n\n Confirma a exclus�o do Tipo de Controle Litigioso \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoControleLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Controle Litigioso selecionado.');
            return;
        }
        if (confirm("Esta opera��o pode ser demorada. \n\n Confirma a exclus�o dos Tipos de Controles Litigiosos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoControleLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }


    <? } ?>

    function pesquisar() {
        document.getElementById("frmTipoControleLitigiosoLista").submit();
    }
</script>
