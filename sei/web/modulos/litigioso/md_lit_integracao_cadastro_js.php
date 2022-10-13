<?php
$strLinkAjaxValidarWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_integracao_busca_operacao_wsdl');
$strLinkAjaxBuscarTipoControle = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_integracao_buscar_tipo_controle');
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('[name="tipoWs"]').click(function () {
            validarTipoClienteWS()
        });
    });

    function validarTipoClienteWS() {
        if ($('[name="tipoWs"][value="SOAP"]').prop('checked')) {
            $('.soap').show()
            $('[name="versaoSoap"]').prop('disabled', false);
        } else {
            $('.soap').hide();
            $('[name="txtEnderecoWsdl"]').val('').trigger('change');
            $('[name="versaoSoap"]').prop('disabled', true);
        }
    }

    function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_lit_integracao_cadastrar') {
            document.getElementById('txtNome').focus();
            validarTipoClienteWS();
        } else if ('<?= $_GET['acao'] ?>' == 'md_lit_integracao_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
            validarTipoClienteWS();
        }
        infraEfeitoTabelas();
        if (document.getElementById('txtEnderecoWsdl').value != '')
            validarWsdl();
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome.');
            document.getElementById('txtNome').focus();
            return false;
        } else if (document.getElementById('txtNome').value.length > 30) {
            alert('Tamanho do campo excedido (máximo 30 caracteres).');
            document.getElementById('txtNome').focus();
            return false;
        }
        if (infraTrim(document.getElementById('selFuncionalidade').value) == 'null') {
            alert('Selecione a funcionalidade.');
            document.getElementById('selFuncionalidade').focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtEnderecoWsdl').value) == '') {
            alert('Informe o endereço WSDL.');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }
        if (infraTrim(document.getElementById('selOperacao').value) == '') {
            alert('Selecione a operação.');
            document.getElementById('selOperacao').focus();
            return false;
        }
        if (document.getElementById('tableParametroEntrada') == null && document.getElementById('tableParametroSaida') == null) {
            alert('Favor mapear a integração.');
            document.getElementById('btnMapear').focus();
            return false;
        }


        //Gerar Número Fistel
        if (document.getElementById('selFuncionalidade').value == 10) {
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosEntrada.push(value.value)
                }
            });


            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if (!infraInArray(77, valoresDadosSaida)) {
                msgSaida += "- Número de Complemento do Interessado\n";
            }


            //Saida
            if (msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
            if (rdChaveUnicaDadosSaidaVazio) {
                alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }

            return true;
        }
        //Fim Gerar Número Fistel


        //Cancelar Lançamento
        if (document.getElementById('selFuncionalidade').value == 4) {
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if (!infraInArray(28, valoresDadosEntrada)) {
                msgEntrada += "- Motivo de Cancelamento\n";
            }
            if (!infraInArray(26, valoresDadosEntrada)) {
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(27, valoresDadosEntrada)) {
                msgEntrada += "- Sequencial\n";
            }


            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if (!infraInArray(33, valoresDadosSaida)) {
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(34, valoresDadosSaida)) {
                msgSaida += "- Sequencial\n";
            }

            //Entrada e Saída
            if (msgEntrada != '' && msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" + msgEntrada + " \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            //Entrada
            if (msgEntrada != '') {
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgEntrada);
                return false;
            }

            //Saida
            if (msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            if (chaveIntegracaoValidacao() == false) {
                return false;
            }

            return true;
        }
        //Fim Cancelar Lançamento


        //Cancelar Recurso
        if (document.getElementById('selFuncionalidade').value == 8) {
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if (!infraInArray(68, valoresDadosEntrada)) {
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(70, valoresDadosEntrada)) {
                msgEntrada += "- Observação\n";
            }
            if (!infraInArray(69, valoresDadosEntrada)) {
                msgEntrada += "- Sequencial\n";
            }


            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if (!infraInArray(72, valoresDadosSaida)) {
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(73, valoresDadosSaida)) {
                msgSaida += "- Sequencial\n";
            }

            //Entrada e Saída
            if (msgEntrada != '' && msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" + msgEntrada + " \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            //Entrada
            if (msgEntrada != '') {
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgEntrada);
                return false;
            }

            //Saida
            if (msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            if (chaveIntegracaoValidacao() == false) {
                return false;
            }

            return true;
        }
        //Fim Cancelar Recurso


        //Denegar Recurso
        if (document.getElementById('selFuncionalidade').value == 7) {
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if (!infraInArray(62, valoresDadosEntrada)) {
                msgEntrada += "- Data Denegação\n";
            }
            if (!infraInArray(60, valoresDadosEntrada)) {
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(61, valoresDadosEntrada)) {
                msgEntrada += "- Sequencial\n";
            }


            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if (!infraInArray(66, valoresDadosSaida)) {
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(67, valoresDadosSaida)) {
                msgSaida += "- Sequencial\n";
            }

            //Entrada e Saída
            if (msgEntrada != '' && msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" + msgEntrada + " \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            //Entrada
            if (msgEntrada != '') {
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgEntrada);
                return false;
            }

            //Saida
            if (msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            if (chaveIntegracaoValidacao() == false) {
                return false;
            }

            return true;
        }
        //Fim Denegar Recurso


        //Incluir Lançamento
        if (document.getElementById('selFuncionalidade').value == 2) {
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if (!infraInArray(78, valoresDadosEntrada)) {
                msgEntrada += "- CNPJ/CPF\n";
            }
            if (!infraInArray(2, valoresDadosEntrada)) {
                msgEntrada += "- Data de aplicação da Sanção\n";
            }
            if (!infraInArray(3, valoresDadosEntrada)) {
                msgEntrada += "- Data de Vencimento\n";
            }
            if (!infraInArray(1, valoresDadosEntrada)) {
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(6, valoresDadosEntrada)) {
                msgEntrada += "- Número do Processo\n";
            }
            if (!infraInArray(4, valoresDadosEntrada)) {
                msgEntrada += "- Valor Total\n";
            }


            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if (!infraInArray(12, valoresDadosSaida)) {
                msgSaida += "- Link para Boleto\n";
            }
            if (!infraInArray(14, valoresDadosSaida)) {
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(11, valoresDadosSaida)) {
                msgSaida += "- Sequencial\n";
            }

            //Entrada e Saída
            if (msgEntrada != '' && msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" + msgEntrada + " \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            //Entrada
            if (msgEntrada != '') {
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgEntrada);
                return false;
            }

            //Saida
            if (msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            if (chaveIntegracaoValidacao() == false) {
                return false;
            }

            return true;
        }
        //Fim Incluir Lançamento


        //Motivo Cancelamento
        if (document.getElementById('selFuncionalidade').value == 9) {
            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msg = '';
            if (!infraInArray(75, valoresDadosSaida)) {
                msg += "- Descrição Motivo\n";
            }
            if (!infraInArray(74, valoresDadosSaida)) {
                msg += "- ID Motivo\n";
            }

            if (msg != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msg);
                return false;
            }

            var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
            if (rdChaveUnicaDadosSaidaVazio) {
                alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }

            return true;
        }
        //Fim Motivo Cancelamento


        //Retificação do Lançamento
        if (document.getElementById('selFuncionalidade').value == 5) {
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if (!infraInArray(37, valoresDadosEntrada)) {
                msgEntrada += "- Data de Aplicação da Sanção\n";
            }
            if (!infraInArray(38, valoresDadosEntrada)) {
                msgEntrada += "- Data de Vencimento\n";
            }
            if (!infraInArray(43, valoresDadosEntrada)) {
                msgEntrada += "- Justificativa Lançamento\n";
            }
            if (!infraInArray(36, valoresDadosEntrada)) {
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(42, valoresDadosEntrada)) {
                msgEntrada += "- Número do Processo\n";
            }
            if (!infraInArray(47, valoresDadosEntrada)) {
                msgEntrada += "- Sequencial\n";
            }
            if (!infraInArray(39, valoresDadosEntrada)) {
                msgEntrada += "- Valor Total\n";
            }


            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if (!infraInArray(51, valoresDadosSaida)) {
                msgSaida += "- Link para Boleto\n";
            }
            if (!infraInArray(50, valoresDadosSaida)) {
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(49, valoresDadosSaida)) {
                msgSaida += "- Sequencial\n";
            }

            //Entrada e Saída
            if (msgEntrada != '' && msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" + msgEntrada + " \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            //Entrada
            if (msgEntrada != '') {
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgEntrada);
                return false;
            }

            //Saida
            if (msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            if (chaveIntegracaoValidacao() == false) {
                return false;
            }

            return true;
        }
        //Fim Retificação do Lançamento


        //suspender Lançamento
        if (document.getElementById('selFuncionalidade').value == 6) {
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if (!infraInArray(57, valoresDadosEntrada)) {
                msgEntrada += "- Data da Suspensão\n";
            }
            if (!infraInArray(52, valoresDadosEntrada)) {
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(53, valoresDadosEntrada)) {
                msgEntrada += "- Sequencial\n";
            }


            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if (!infraInArray(58, valoresDadosSaida)) {
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(59, valoresDadosSaida)) {
                msgSaida += "- Sequencial\n";
            }

            //Entrada e Saída
            if (msgEntrada != '' && msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" + msgEntrada + " \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            //Entrada
            if (msgEntrada != '') {
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgEntrada);
                return false;
            }

            //Saida
            if (msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            if (chaveIntegracaoValidacao() == false) {
                return false;
            }

            return true;
        }
        //Fim Suspender


        //Consultar Lançamento
        if (document.getElementById('selFuncionalidade').value == 3) {
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if (!infraInArray(15, valoresDadosEntrada)) {
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(16, valoresDadosEntrada)) {
                msgEntrada += "- Sequencial\n";
            }


            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function (key, value) {
                if (value.value != 'null') {
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if (!infraInArray(84, valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked) {
                msgSaida += "- Código da Receita\n";
            }
            if (!infraInArray(85, valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked) {
                msgSaida += "- Data Constituição Definitiva\n";
            }
            if (!infraInArray(80, valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked) {
                msgSaida += "- Data da Decisão de Aplicação da Multa\n";
            }
            if (!infraInArray(81, valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked) {
                msgSaida += "- Data de Vencimento\n";
            }
            if (!infraInArray(21, valoresDadosSaida)) {
                msgSaida += "- Data do Último Pagamento\n";
            }
            if (!infraInArray(83, valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked) {
                msgSaida += "- Identificação do Lançamento\n";
            }
            if (!infraInArray(24, valoresDadosSaida)) {
                msgSaida += "- Link para Boleto\n";
            }
            if (!infraInArray(82, valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked) {
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if (!infraInArray(18, valoresDadosSaida)) {
                msgSaida += "- Situação Lançamento\n";
            }
            if (!infraInArray(79, valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked) {
                msgSaida += "- Valor da Receita Inicial\n";
            }
            if (!infraInArray(25, valoresDadosSaida)) {
                msgSaida += "- Valor Atualizado\n";
            }
            if (!infraInArray(23, valoresDadosSaida)) {
                msgSaida += "- Valor do Desconto\n";
            }
            if (!infraInArray(22, valoresDadosSaida)) {
                msgSaida += "- Valor Total do Pagamento\n";
            }

            //Entrada e Saída
            if (msgEntrada != '' && msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" + msgEntrada + " \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }

            //Entrada
            if (msgEntrada != '') {
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgEntrada);
                return false;
            }

            //Saida
            if (msgSaida != '') {
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n " + msgSaida);
                return false;
            }


            if (chaveIntegracaoValidacao() == false) {
                return false;
            }

            return true;
        } else if (document.getElementById('selFuncionalidade').value == 1) {
            var selectNomeFuncionalDadosEntradaVazio = true;
            var rdChaveUnicaDadosEntradaVazio = true;
            var selectNomeFuncionalDadosSaidaVazio = true;
            var rdChaveUnicaDadosSaidaVazio = true;

            for (var i = 0; i < (document.getElementById('tableParametroEntrada').rows.length - 1); i++) {

                if (infraTrim(document.getElementById('nomeFuncionalDadosEntrada_' + i).value) != 'null') {
                    selectNomeFuncionalDadosEntradaVazio = false;
                }
                if (document.getElementById('chaveUnicaDadosEntrada_' + i).checked) {
                    rdChaveUnicaDadosEntradaVazio = false;
                }

            }
            for (var i = 0; i < (document.getElementById('tableParametroSaida').rows.length - 1); i++) {

                if (infraTrim(document.getElementById('nomeFuncionalDadosSaida_' + i).value) != 'null') {
                    selectNomeFuncionalDadosSaidaVazio = false;
                }
                if (document.getElementById('chaveUnicaDadosSaida_' + i).checked) {
                    rdChaveUnicaDadosSaidaVazio = false;
                }
            }

            if (selectNomeFuncionalDadosEntradaVazio) {
                alert('Informe ao menos um campo de destino no SEI na tabela de dados de entrada.');
                document.getElementById('tableParametroEntrada').scrollIntoView()
                return false;
            }
            if (rdChaveUnicaDadosEntradaVazio) {
                alert('Informe ao menos uma chave única da integração na tabela de dados de entrada.');
                document.getElementById('tableParametroEntrada').scrollIntoView()
                return false;
            }
            if (selectNomeFuncionalDadosSaidaVazio) {
                alert('Informe ao menos um campo de destino no SEI na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }
            if (rdChaveUnicaDadosSaidaVazio) {
                alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }
        } else {

            //Dados Entrada
            if (document.getElementById('tableParametroEntrada') != null) {
                var qtdOptionEntrada = $('[name^="nomeFuncionalDadosEntrada"]')[0].options.length - 1;
                var qtdOptionEntradaSelecionado = $('[name^="nomeFuncionalDadosEntrada"] option[value!="null"]:selected').length;
                if (qtdOptionEntrada > 0) {
                    if (qtdOptionEntrada != qtdOptionEntradaSelecionado) {
                        alert('Todos os campos do parâmetro de entrada devem ser mapeados');
                        document.getElementById('tableParametroEntrada').scrollIntoView()
                        return false;
                    }
                }

                var rdChaveUnicaDadosEntradaVazio = $('[name="chaveUnicaDadosEntrada"]:checked').length == 0;
                if (rdChaveUnicaDadosEntradaVazio) {
                    alert('Informe ao menos uma chave única da integração na tabela de dados de entrada.');
                    document.getElementById('tableParametroEntrada').scrollIntoView()
                    return false;
                }
            }

            if (document.getElementById('tableParametroSaida') != null) {
                //Dados Saida
                var qtdOptionSaida = $('[name^="nomeFuncionalDadosSaida"]')[0].options.length - 1;
                var qtdOptionSaidaSelecionado = $('[name^="nomeFuncionalDadosSaida"] option[value!="null"]:selected').length;
                if (qtdOptionSaida > 0) {
                    if (qtdOptionSaida != qtdOptionSaidaSelecionado) {
                        alert('Todos os campos do parâmetro de saida devem ser mapeados');
                        document.getElementById('tableParametroSaida').scrollIntoView()
                        return false;
                    }
                }
                var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
                if (rdChaveUnicaDadosSaidaVazio) {
                    alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
                    document.getElementById('tableParametroSaida').scrollIntoView()
                    return false;
                }
            }

        }
        document.getElementById('frmIntegracaoCadastro').submit();
    }

    function cancelar() {
        location.href = "<?= $strUrlCancelar ?>";
    }

    function chaveIntegracaoValidacao() {
        var total = $('[name^="nomeFuncionalDadosEntrada"]')[0].options.length - 1;

        var rdChaveUnicaDadosEntradaVazio = $('[name="chaveUnicaDadosEntrada"]:checked').length == 0;
        if (rdChaveUnicaDadosEntradaVazio) {
            alert('Informe ao menos uma chave única da integração na tabela de dados de entrada.');
            document.getElementById('tableParametroEntrada').scrollIntoView()
            return false;
        }

        var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
        if (rdChaveUnicaDadosSaidaVazio) {
            alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
            document.getElementById('tableParametroSaida').scrollIntoView()
            return false;
        }

    }

    function validarWsdl() {
        var consultar = <?php echo $_GET['acao'] != 'md_lit_integracao_consultar' ? 'true' : 'false'; ?>//;
        var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        var tipoWs = $('[name="tipoWs"]:checked').val();

        var versaoSoap = $('[name="versaoSoap"]').val();
        if (consultar) {
            versaoSoap = $('[name="versaoSoap"]').not(':disabled').val();
        }

        if (enderecoWsdl == '') {
            alert('Preencher o campo Endereço WSDL.');
            return false;
        }

        if (tipoWs != 'SOAP' || versaoSoap == undefined) {
            alert('Para validar este serviço informe o Tipo de Cliente WS como SOAP e sua Versão Soap');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxValidarWsdl ?>",
            dataType: "xml",
            data: {
                endereco_wsdl: enderecoWsdl, tipoWs: tipoWs, versaoSoap: versaoSoap
            },
            beforeSend: function () {
                infraExibirAviso(false);
            },
            success: function (result) {
                var select = document.getElementById('selOperacao');
                //limpar todos os options
                select.options.length = 0;

                if ($(result).find('success').text() == 'true') {
                    var opt = document.createElement('option');
                    opt.value = '';
                    opt.innerHTML = '';
                    select.appendChild(opt);
                    var selectedValor = '<?= PaginaSEI::tratarHTML($objMdLitIntegracaoDTO->getStrOperacaWsdl());?>';

                    $.each($(result).find('operacao'), function (key, value) {
                        var opt = document.createElement('option');
                        opt.value = $(value).text();
                        opt.innerHTML = $(value).text();
                        if ($(value).text() == selectedValor)
                            opt.selected = true;
                        select.appendChild(opt);
                    });

                    document.getElementById('gridOperacao').style.display = "block";
                } else {
                    alert($(result).find('msg').text());
                    document.getElementById('gridOperacao').style.display = "none";
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

    }

    function mapear() {
        if ($('[name="tipoWs"]:not(:disabled):checked').val() != 'SOAP') {
            alert('Para mapear as integrações informe a opção SOAP.');
            return false;
        }
        if (infraTrim(document.getElementById('txtEnderecoWsdl').value) == '') {
            alert('Informe o Endereço do WSDL.');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }

        if (infraTrim(document.getElementById('selFuncionalidade').value) == 'null') {
            alert('Informe a Funcionalidade.');
            document.getElementById('selFuncionalidade').focus();
            return false;
        }

        if (infraTrim(document.getElementById('selOperacao').value) == '') {
            alert('Informe a Operação.');
            document.getElementById('selOperacao').focus();
            return false;
        }
        document.getElementById('hdnAcaoIntegracao').value = 'mapear';
        document.getElementById('frmIntegracaoCadastro').submit();
    }

    function mudarNomeFuncionalDadosEntrada(element) {
        limparTabelaCodigoReceita(element);
        if (element.value != 'null') {
            var row = document.getElementById('tableParametroEntrada').rows[element.parentNode.parentNode.rowIndex];
            row.cells[2].children[0].children[0].disabled = false;
            for (var i = 0; i < document.getElementById('tableParametroEntrada').rows.length; i++) {
                if (i != 0 && i != element.parentNode.parentNode.rowIndex) {
                    var select = document.getElementById('nomeFuncionalDadosEntrada_' + (i - 1));
                    for (var j = 0; j < select.options.length; j++) {
                        if (select.value == element.value) {
                            select.value = 'null';
                            mudarNomeFuncionalDadosEntrada(select);
                            break;
                        }
                    }
                }
            }

        } else {
            var row = document.getElementById('tableParametroEntrada').rows[element.parentNode.parentNode.rowIndex];
            row.cells[2].children[0].children[0].disabled = true;
            row.cells[2].children[0].children[0].checked = false;
        }
    }

    function mudarNomeFuncionalDadosSaida(element) {
        if (element.value != 'null') {
            var row = document.getElementById('tableParametroSaida').rows[element.parentNode.parentNode.rowIndex];
            row.cells[2].children[0].children[0].disabled = false;
            for (var i = 0; i < document.getElementById('tableParametroSaida').rows.length; i++) {
                if (i != 0 && i != element.parentNode.parentNode.rowIndex) {
                    var select = document.getElementById('nomeFuncionalDadosSaida_' + (i - 1));
                    for (var j = 0; j < select.options.length; j++) {
                        if (select.value == element.value) {
                            select.value = 'null';
                            mudarNomeFuncionalDadosSaida(select);
                            break;
                        }
                    }
                }
            }

        } else {
            var row = document.getElementById('tableParametroSaida').rows[element.parentNode.parentNode.rowIndex];
            row.cells[2].children[0].children[0].disabled = true;
            row.cells[2].children[0].children[0].checked = false;
        }
    }

    function OnSubmitForm() {
        ret = validarCadastro();
        return ret;
    }

    function apagarMapear() {
        if (document.getElementById('tableParametroEntrada') != null) {
            document.getElementById('tableParametroEntrada').remove();
        }
        if (document.getElementById('tableParametroSaida') != null) {
            document.getElementById('tableParametroSaida').remove();
        }
    }

    function apagarOperacao() {
        var select = document.getElementById('selOperacao');
        select.options.length = 0;
        document.getElementById('gridOperacao').style.display = "none";
        apagarMapear();
        document.getElementById('divSinVincularLancamento').style.display = 'none';
        document.getElementById('chkSinVincularLancamento').checked = false;

        if (document.getElementById('selFuncionalidade').value == '<?php echo MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO?>') {
            document.getElementById('divSinVincularLancamento').style.display = '';
        }
    }

    function verificarCodigoReceita(selCampoDestino) {
        var selFuncionalidade = document.getElementById('selFuncionalidade');
        var td = selCampoDestino.parentNode;
        limparTabelaCodigoReceita(selCampoDestino);
        if (selFuncionalidade.value == 2) {
            if (selCampoDestino.value == 5) {
                montarTabelaCodigoReceita(td);
            }
        }
    }

    function montarTabelaCodigoReceita(td) {
        $.ajax({
            url: '<?=$strLinkAjaxBuscarTipoControle?>',
            dataType: 'HTML',
            type: 'POST',
            success: function (r) {
                td.insertAdjacentHTML('beforeend', r);
            }
        });
    }

    function limparTabelaCodigoReceita(el) {
        var td = el.parentNode;
        var div = td.getElementsByTagName('div')[0];
        if (div != null) {
            div.remove();
        }
    }
</script>
