<script type="text/javascript">
    function isValidSelecao() {
        var arrEspecies = new Array();
        $('.infraCheckbox:checked').each(function (key, element) {
            arrEspecies.push($(element).val());
        });

        //dispara a função da janela que originou a abertura do componente
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
        if (confirm("Confirma desativação da Espécie de Decisão \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Espécie de Decisão selecionada.');
            return;
        }
        if (confirm("Confirma a desativação das Espécies de Decisão selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }
    <? } ?>

    function acaoReativar(id, desc) {
        if (confirm("Confirma reativação da Espécie de Decisão  \"" + desc + "\"?")) {
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
        if (confirm("Confirma a reativação das Especies selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        if (confirm("Confirma exclusão da Espécie de Decisão \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Espécie de Decisão selecionada..');
            return;
        }
        if (confirm("Confirma a exclusão das Especies selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmEspecieLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmEspecieLitigiosoLista').submit();
        }
    }
    <? } ?>
</script>
