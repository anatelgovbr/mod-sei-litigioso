<script type="text/javascript">
    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_dispositivo_normativo_selecionar') {
            // 2- Indicar por Conduta
            if ('<?= $_GET['tipo_selecao'] ?>' == '2' && window.opener.document.getElementById('selICCondutas') != null) {
                if (window.opener.document.getElementById('selICCondutas').value != '' && document.getElementById('selICCondutas').value == '') {
                    document.getElementById('selICCondutas').value = window.opener.document.getElementById('selICCondutas').value;
                    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
                }
            }
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            document.getElementById('btnFechar').focus();
        }
        infraEfeitoTabelas();

        // Adiciona a class "infraLabelOpcional" quando não retorna nenhum registro na grid
        // para ficar na mesma formatação das labels da infra
        if( $('#divInfraAreaTabela').find('table').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
            $('#divInfraAreaTabela').parent().parent().addClass('mt-2');
            $('#divInfraAreaTabela > label').addClass('infraLabelOpcional'); 
        }else{
            if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
                $('#divInfraAreaPaginacaoSuperior').hide();
            }
        }
    }

    function submitFormPesquisa() {
        //alert('Teste');
        document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkConsultar ?>';
        document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }

    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id, desc) {

        <? $strAcao = $_GET['acao']; ?>
        var acao = '<?= $strAcao ?>';

        if (acao == 'md_lit_dispositivo_normativo_selecionar') {

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
        var desc = $("<pre>").html(desc).text();
        if (confirm("Confirma desativação do Dispositivo Normativo \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Dispositivo Normativo selecionado.');
            return;
        }
        if (confirm("Confirma a desativação dos Dispositivos Normativos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
        }
    }
    <? } ?>

    function acaoReativar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("Confirma reativação do Dispositivo Normativo \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
        }
    }

    function acaoReativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Dispositivo Normativo selecionado.');
            return;
        }
        if (confirm("Confirma a reativação dos Dispositivos Normativos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
        }
    }

    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id, desc) {
        var desc2 = $("<pre>").html(desc).text();
        <? $strAcao = $_GET['acao']; ?>
        var acao = '<?= $strAcao ?>';

        if (acao == 'md_lit_dispositivo_normativo_selecionar') {

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

        if (confirm("Confirma exclusão do Dispositivo Normativo \"" + desc2 + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
        }
    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Dispositivo Normativo selecionado.');
            return;
        }
        if (confirm("Confirma a exclusão dos Dispositivos Normativos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
        }
    }
    <? } ?>
</script>