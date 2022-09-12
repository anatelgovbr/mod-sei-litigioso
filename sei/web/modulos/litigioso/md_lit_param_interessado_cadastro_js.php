<script type="text/javascript">

    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_lit_parametrizar_interessado_cadastrar') {
            document.getElementById('optSinParamModalComplInteresSim').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_param_interessado_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }

        infraEfeitoTabelas();
        configurarTabelaDados();
    }

    function validarCadastro() {
        if (!document.getElementById('optSinParamModalComplInteresSim').checked && !document.getElementById('optSinParamModalComplInteresNao').checked) {
            alert('Escolha uma opção apresenta modal de dados complementares do interessado.');
            document.getElementById('optSinParamModalComplInteresSim').focus();
            return false;
        }

        if (document.getElementById('optSinParamModalComplInteresSim').checked) {
            var elementExibeChecked = false;
            for (var i = 1; i < 10; i++) {
                var elementExibe = document.getElementById('chkSinExibe_' + i);
                if (elementExibe == null) {
                    break;
                }
                if (elementExibe.checked) {
                    elementExibeChecked = true;
                    var nomeFuncional = infraTrim(document.getElementById('nome_funcional_' + i).textContent);
                    if (document.getElementById('txtLabelCampo_' + i) != null && document.getElementById('txtLabelCampo_' + i).value == '') {
                        alert('O label do campo da linha "' + nomeFuncional + '" é obrigatório');
                        return false;
                    }
                    if (document.getElementById('txtTamanho_' + i) != null && document.getElementById('txtTamanho_' + i).value == '') {
                        alert('O tamanho da linha "' + nomeFuncional + '" é obrigatório');
                        return false;
                    } else if (document.getElementById('txtTamanho_' + i) != null && parseInt(document.getElementById('txtTamanho_' + i).value) == 0) {
                        alert('O tamanho da linha "' + nomeFuncional + '" precisa ser maior que zero!');
                        return false;

                    }
                }
            }

            if (!elementExibeChecked) {
                alert('Ao menos um campo deve ser marcado como Exibe.');
                return false;
            }

        }
        document.getElementById('chkSinExibe_1').disabled = false;
        document.getElementById('chkSinObrigatorio_1').disabled = false;
        document.getElementById('chkSinExibe_2').disabled = false;
        document.getElementById('chkSinObrigatorio_2').disabled = false;
        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function configurarTabelaDados() {
        document.getElementById('divInfraAreaTabela').style.visibility = 'hidden';
        if (document.getElementById('optSinParamModalComplInteresSim').checked) {
            document.getElementById('divInfraAreaTabela').style.visibility = 'visible';
        } else if (document.getElementById('optSinParamModalComplInteresNao').checked) {
            document.getElementById('divInfraAreaTabela').style.visibility = 'hidden';
        }
    }

    //função responsavel por limpar e disabilitar os inputs da linha da tabela
    // Element e o checkbox dentro da td da table
    function campoTabelaDisabilidado(element) {
        if (!element.checked) {
            var row = document.getElementById('tableDadosComplementarInteressado').rows[element.parentNode.parentNode.parentNode.rowIndex];
            //td "Obrigatório?"
            row.cells[2].children[0].disabled = true;
            row.cells[2].children[0].checked = false;

            //td "label do campo"
            row.cells[3].children[0].disabled = true;
            row.cells[3].children[0].value = '';

            //td "Tamanho"
            if (row.cells[4].children[0]) {
                row.cells[4].children[0].disabled = true;
                row.cells[4].children[0].value = '';
            }

            //td "Descrição da ajuda"
            row.cells[5].children[0].disabled = true;
            row.cells[5].children[0].value = '';
        } else {
            var row = document.getElementById('tableDadosComplementarInteressado').rows[element.parentNode.parentNode.parentNode.rowIndex];

            //td "Obrigatório?"
            row.cells[2].children[0].disabled = false;

            //td "label do campo"
            row.cells[3].children[0].disabled = false;

            //td "Tamanho"
            if (row.cells[4].children[0]) {
                row.cells[4].children[0].disabled = false;
            }

            //td "Descrição da ajuda"
            row.cells[5].children[0].disabled = false;

        }
    }


    function SomenteNumero(e) {
        var tecla = (window.event) ? event.keyCode : e.which;
        if ((tecla > 47 && tecla < 58)) return true;
        else {
            if (tecla == 8 || tecla == 0) return true;
            else return false;
        }
    }

</script>