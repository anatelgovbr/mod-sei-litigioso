<script type="text/javascript">
    function isValidSelecao() {
        var arrEspecies = new Array();
        $('.infraCheckbox:checked').each(function (key, element) {
            arrEspecies.push($(element).val());
        });

        //dispara a fun��o da janela que originou a abertura do componente
        objValidado = window.opener.validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies);
        if (objValidado.valid == false) {
            alert(objValidado.mensagem);
            return false;
        }
    }

    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_especie_decisao_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            document.getElementById('btnFechar').focus();
        }
        infraEfeitoTabelas();
    }

    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id, desc) {
        if (confirm("Confirma desativa��o da Esp�cie de Decis�o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Esp�cie de Decis�o selecionada.');
            return;
        }
        if (confirm("Confirma a desativa��o das Esp�cies de Decis�o selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }
    <? } ?>

    function acaoReativar(id, desc) {
        if (confirm("Confirma reativa��o da Esp�cie de Decis�o  \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Especie selecionado.');
            return;
        }
        if (confirm("Confirma a reativa��o das Especies selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclus�o da Esp�cie de Decis�o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Esp�cie de Decis�o selecionada..');
            return;
        }
        if (confirm("Confirma a exclus�o das Especies selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }
    <? } ?>
</script>
