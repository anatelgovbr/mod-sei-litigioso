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

        // Adiciona a class "infraLabelOpcional" quando n�o retorna nenhum registro na grid
        // para ficar na mesma formata��o das labels da infra
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
            Na linha de cada registro, na a��o de Desativar e Excluir , aplicar regra adicional que checa se o item�foi previamente selecionado.�
            Se tiver sido, exibir a seguinte:� � "N�o � permitido desativar ou excluir item j� selecionado. Caso deseje efetivar a
            opera��o, antes retorne � tela anterior para remover a sele��o."
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
                        alert("N�o � permitido desativar ou excluir item j� selecionado. Caso deseje efetivar a opera��o, antes retorne � tela anterior para remover a sele��o.");
                        return;
                    }
                }

            }

        }
        var desc = $("<pre>").html(desc).text();
        if (confirm("Confirma desativa��o do Dispositivo Normativo \"" + desc + "\"?")) {
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
        if (confirm("Confirma a desativa��o dos Dispositivos Normativos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
        }
    }
    <? } ?>

    function acaoReativar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("Confirma reativa��o do Dispositivo Normativo \"" + desc + "\"?")) {
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
        if (confirm("Confirma a reativa��o dos Dispositivos Normativos selecionados?")) {
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
            Na linha de cada registro, na a��o de Desativar e Excluir , aplicar regra adicional que checa se o item�foi previamente selecionado.�
            Se tiver sido, exibir a seguinte:� � "N�o � permitido desativar ou excluir item j� selecionado. Caso deseje efetivar a
            opera��o, antes retorne � tela anterior para remover a sele��o."
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
                        alert("N�o � permitido desativar ou excluir item j� selecionado. Caso deseje efetivar a opera��o, antes retorne � tela anterior para remover a sele��o.");
                        return;
                    }
                }

            }

        }

        if (confirm("Confirma exclus�o do Dispositivo Normativo \"" + desc2 + "\"?")) {
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
        if (confirm("Confirma a exclus�o dos Dispositivos Normativos selecionados?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
        }
    }
    <? } ?>
</script>