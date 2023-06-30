<script type="text/javascript">
    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_conduta_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
            var idObject = document.getElementById('hdnInfraSelecaoIdObject');
            var obj = eval('window.opener.' + idObject.value);
            if (obj.id_dispositivo != null && document.getElementById('hdnIdDispositivo').value == '') {
                document.getElementById('hdnIdDispositivo').value = obj.id_dispositivo;
                document.getElementById('frmCondutaLitigiosoLista').submit();
            }
        } else {
            document.getElementById('btnFechar').focus();
        }
        infraEfeitoTabelas();
    }

    function filtrarCondutas() {
        document.getElementById('frmCondutaLitigiosoLista').action = '<?= $strLinkPesquisar ?>';
        document.getElementById('frmCondutaLitigiosoLista').submit();
    }


    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id, desc) {

        <? $strAcao = $_GET['acao']; ?>
        var acao = '<?= $strAcao ?>';

        if (acao == 'md_lit_conduta_selecionar') {

            /*
            Na linha de cada registro, na ação de Desativar e Excluir , aplicar regra adicional que checa se o item foi previamente selecionado. 
            Se tiver sido, exibir a seguinte:    "Não é permitido desativar ou excluir item já selecionado. Caso deseje efetivar a
            operação, antes retorne à tela anterior para remover a seleção."
            */

            var arrElem = document.getElementsByClassName("infraCheckbox");

            for (var i = 0; i < arrElem.length; i++) {

                var nomeId = 'chkInfraItem' + i;
                var item = document.getElementById(nomeId);

                //se o valor bater e o campo estiver marcado, aplicar a regra
                if (item.value == id) {

                    var valorMarcado = item.checked;
                    var valorDisabled = item.disabled;

                    if ((valorDisabled || valorDisabled == 'disabled') && (valorMarcado || valorMarcado == 'checked')) {
                        alert("Não é permitido desativar ou excluir item já selecionado. Caso deseje efetivar a operação, antes retorne à tela anterior para remover a seleção.");
                        return;
                    }
                }

            }

        }

        if (confirm("Confirma desativação da Conduta \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmCondutaLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmCondutaLitigiosoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Conduta selecionada.');
            return;
        }
        if (confirm("Confirma a desativação das Condutas selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmCondutaLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmCondutaLitigiosoLista').submit();
        }
    }
    <? } ?>

    function acaoReativar(id, desc) {
        if (confirm("Confirma reativação da Conduta \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmCondutaLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmCondutaLitigiosoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Conduta selecionada.');
            return;
        }
        if (confirm("Confirma a reativação das Condutas selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmCondutaLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmCondutaLitigiosoLista').submit();
        }
    }

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {

        <? $strAcao = $_GET['acao']; ?>
        var acao = '<?= $strAcao ?>';

        if (acao == 'md_lit_conduta_selecionar') {

            /*
            Na linha de cada registro, na ação de Desativar e Excluir , aplicar regra adicional que checa se o item foi previamente selecionado. 
            Se tiver sido, exibir a seguinte:    "Não é permitido desativar ou excluir item já selecionado. Caso deseje efetivar a
            operação, antes retorne à tela anterior para remover a seleção."
            */

            var arrElem = document.getElementsByClassName("infraCheckbox");

            for (var i = 0; i < arrElem.length; i++) {

                var nomeId = 'chkInfraItem' + i;
                var item = document.getElementById(nomeId);

                //se o valor bater e o campo estiver marcado, aplicar a regra
                if (item.value == id) {

                    var valorMarcado = item.checked;
                    var valorDisabled = item.disabled;

                    if ((valorDisabled || valorDisabled == 'disabled') && (valorMarcado || valorMarcado == 'checked')) {
                        alert("Não é permitido desativar ou excluir item já selecionado. Caso deseje efetivar a operação, antes retorne à tela anterior para remover a seleção.");
                        return;
                    }
                }

            }

        }

        if (confirm("Confirma exclusão da Conduta \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmCondutaLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmCondutaLitigiosoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhuma Conduta selecionada.');
            return;
        }
        if (confirm("Confirma a exclusão das Condutas selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmCondutaLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmCondutaLitigiosoLista').submit();
        }
    }
    <? } ?>
</script>