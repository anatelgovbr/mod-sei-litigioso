<script type="text/javascript">
    function inicializar() {
        infraEfeitoTabelas(true);
    }

    function onSubmit(){
    }

    function acaoDesativar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("O tipo de informa��o ser� desativado e os usu�rios n�o conseguir�o utilizar em nenhum outro cadastro de Processo Litigioso. \n\n Confirma a desativa��o do Tipo de Informa��o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    function acaoAtivar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("O Tipo de Informa��o ser� Ativado e os usu�rios poder�o utilizar no cadastro de Processo Litigioso. \n\n Confirma a Ativa��o do Tipo de Informa��o \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Informa��o selecionado.');
            return;
        }
        if (confirm("Os Tipos de Informa��o selecionados ser�o desativados. \n\n Confirma a desativa��o das Informa��es Adicionais?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    function acaoAtivacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Informa��o selecionado.');
            return;
        }
        if (confirm("Os Tipos de Informa��o selecionados ser�o ativados. \n\n Confirma a ativa��o das Informa��es Adicionais?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    // FUN��O PARA TIPO DE INFORMA��O
    function excluirTipoInformacao(IdMdLitTpInforAd){

        if (confirm('Tem certeza que deseja excluir esse Tipo de Informa��o?')) {
            document.getElementById('hdnInfraItemId').value = IdMdLitTpInforAd;
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }

    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Informa��o selecionado.');
            return;
        }
        if (confirm("Esta opera��o ir� excluir todas Informa��es Adicionais selecionadas. \n\n Confirma a exclus�o das Informa��es Adicionais selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    function pesquisar() {
        document.getElementById("frmTipoInformacaoAdicional").submit();
    }

</script>
