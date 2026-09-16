<script type="text/javascript">
    function inicializar() {
        infraEfeitoTabelas(true);
    }

    function onSubmit(){
    }

    function acaoDesativar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("O Campo Adicional será desativado e os usuários não conseguirão utilizar em nenhum outro cadastro de Processo Litigioso. \n\n Confirma a desativação do Campo Adicional \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmCamposAdd').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmCamposAdd').submit();
        }
    }

    function acaoAtivar(id, desc) {
        var desc = $("<pre>").html(desc).text();
        if (confirm("O Campo Adicional será Ativado e os usuários poderão utilizar no cadastro de Processo Litigioso. \n\n Confirma a Ativação do Campo Adicional \"" + desc + "\"?")) {
            document.getElementById('hdnInfraItemId').value = id;
            document.getElementById('frmCamposAdd').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmCamposAdd').submit();
        }
    }

    function acaoDesativacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Campo Adicional selecionado.');
            return;
        }
        if (confirm("Os Campos Adicionais selecionados serão desativados. \n\n Confirma a desativação dos Campos Adicionais?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmCamposAdd').action = '<?= $strLinkDesativar ?>';
            document.getElementById('frmCamposAdd').submit();
        }
    }

    function acaoAtivacaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Campo Adicional selecionado.');
            return;
        }
        if (confirm("Os Campos Adicionais selecionados serão ativados. \n\n Confirma a ativação dos Campos Adicionais?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmCamposAdd').action = '<?= $strLinkReativar ?>';
            document.getElementById('frmCamposAdd').submit();
        }
    }

    // FUNÇÃO PARA TIPO DE INFORMAÇÃO
    function excluirCampo(IdMdLitCampoAdd){

        if (confirm('Tem certeza que deseja excluir esse Campo Adicional?')) {
            document.getElementById('hdnInfraItemId').value = IdMdLitCampoAdd;
            document.getElementById('frmCamposAdd').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmCamposAdd').submit();
        }

    }

    function acaoExclusaoMultipla() {
        if (document.getElementById('hdnInfraItensSelecionados').value == '') {
            alert('Nenhum Campo Adicional selecionado.');
            return;
        }
        if (confirm("Esta operação irá excluir todos Campos Adicionais selecionadas. \n\n Confirma a exclusão dos Campos Adicionais selecionadas?")) {
            document.getElementById('hdnInfraItemId').value = '';
            document.getElementById('frmCamposAdd').action = '<?= $strLinkExcluir ?>';
            document.getElementById('frmCamposAdd').submit();
        }
    }

    function montarArray() {
        const arrayResultante = {};
        const linhas = document.querySelectorAll('tbody tr');

        linhas.forEach(linha => {
            const inputHidden = linha.querySelector('td input[type="hidden"]');
            if (inputHidden) {
                const valor = inputHidden.value;
                const name = inputHidden.name.replace('ordem_', '');
                arrayResultante[valor] = name;
            }
        });

        return arrayResultante;
    }

    // SALVAR ORDENAÇÃO
    function salvarOrdenacao(){
        let params =  { ordemCampos : montarArray() }

        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_campos_add_salvar_ordem') ?>",
            dataType: "xml",
            data: params,
            async: false,
            success: function (result) {

            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                valid = false;
            }
        });
    }

    function pesquisar() {
        document.getElementById("frmCamposAdd").submit();
    }

</script>
