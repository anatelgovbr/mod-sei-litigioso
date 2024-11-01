<script type="text/javascript">
    function inicializar() {
        infraEfeitoTabelas(true);
    }

    function onSubmit(){
    }

    function acaoDesativar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("O tipo de informação será desativado e os usuários não conseguirão utilizar em nenhum outro cadastro de Processo Litigioso. \n\n Confirma a desativação do Tipo de Informação \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    function acaoAtivar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("O Tipo de Informação será Ativado e os usuários poderão utilizar no cadastro de Processo Litigioso. \n\n Confirma a Ativação do Tipo de Informação \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Informação selecionado.');
            return;
        }
        if (confirm("Os Tipos de Informação selecionados serão desativados. \n\n Confirma a desativação das Informações Adicionais?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    function acaoAtivacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Informação selecionado.');
            return;
        }
        if (confirm("Os Tipos de Informação selecionados serão ativados. \n\n Confirma a ativação das Informações Adicionais?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    // FUNÇÃO PARA TIPO DE INFORMAÇÃO
    function excluirTipoInformacao(IdMdLitTpInforAd){

        if (confirm('Tem certeza que deseja excluir esse Tipo de Informação?')) {
            document.getElementById('hdnInfraItemId').value = IdMdLitTpInforAd;
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }

    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Tipo de Informação selecionado.');
            return;
        }
        if (confirm("Esta operação irá excluir todas Informações Adicionais selecionadas. \n\n Confirma a exclusão das Informações Adicionais selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmTipoInformacaoAdicional').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmTipoInformacaoAdicional').submit();
        }
    }

    function pesquisar() {
        document.getElementById("frmTipoInformacaoAdicional").submit();
    }

</script>
