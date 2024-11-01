<script type="text/javascript">

    var INCLUIR_DEBITO = 1;
    var RETIFICAR_DEBITO = 2;
    var SUSPENDER_LANCAMENTO = 3;
    var CANCELAR_LANCAMENTO = 3;
    var objAjaxNumeroInteressado;

    /**
     * Função utilizada no change do campo "Data da Intimação da Decisão de Aplicação da Multa" num campo hidden par considerar
     * as alterações do lancamento ao alterar
     * @param element
     */
    function armazenarDataIntimacaoMulta(element) {
        var index = $('[name="selCreditosProcesso"]').val();
        var value = $(element).val();
        if ($('.hdnDtaIntimacaoDecisaoMulta[data-id="' + index + '"]').length > 0) {
            $('.hdnDtaIntimacaoDecisaoMulta[data-id="' + index + '"]').val(value);
        } else {
            $('#divDadosIntimacaoAplicaocaoMulta').append('<input type="hidden" name="hdnDtaIntimacaoDecisaoMulta[' + index + ']" class="hdnDtaIntimacaoDecisaoMulta" data-id="' + index + '"value="' + value + '"/>');
        }
    }

    function carregarDependenciaMulta(idNumeroInteressado) {

        objAjaxNumeroInteressado = new infraAjaxMontarSelectDependente('selInteressado', 'selNumeroInteressado', '<?=$strLinkComboNumeroInteressado?>');

        objAjaxNumeroInteressado.prepararExecucao = function () {
            document.getElementById('selNumeroInteressado').innerHTML = "";
            document.getElementById('div-numero').style.display = '';
            return 'id_interessado=' + document.getElementById('selInteressado').value + '&id_procedimento=' + document.getElementById('hdnIdProcedimento').value;
        };
        objAjaxNumeroInteressado.processarResultado = function (itCnt) {
            if (document.getElementById('selNumeroInteressado').getAttribute('data-id-dado-interessado') != '') {
                infraSelectSelecionarItem('selNumeroInteressado', document.getElementById('selNumeroInteressado').getAttribute('data-id-dado-interessado'));
            } else if (document.getElementById('selNumeroInteressado').options.length == 2) {
                document.getElementById('selNumeroInteressado').options[1].selected = true;
            }
        };

        // caso seja para recarregar na tela interessado
        if(idNumeroInteressado){
            document.getElementById('selNumeroInteressado').value = idNumeroInteressado;
        }else{
            consultarExtratoMulta();
        }

        if (document.getElementById('selInteressado').options.length == 2) {
            document.getElementById('selInteressado').options[1].selected = true;
        }
        if (document.getElementById('selInteressado').value != 'null') {
            window.setTimeout(function () {
                objAjaxNumeroInteressado.executar()
            }, 1000);
        }

        houveConstituicao(document.getElementById('chkHouveConstituicao'));

    }

    function selecionarPorNome(select, valor) {
        var sel = document.getElementById(select);
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].text != '' && sel.options[i].text.indexOf(valor)) {
                sel.options[i].selected = true;
                return;
            }
        }
    }

    function houveConstituicao(element) {

        if (element.checked) {
            //aparecer todos os campos do houve constituição
            var elements = document.getElementsByClassName('nao-tem-constituicao');
            var iLen = elements.length;
            while (iLen > 0) {
                elements[iLen - 1].className = elements[iLen - 1].className.replace('nao-tem-constituicao', 'tem-constituicao');
                elements = document.getElementsByClassName('nao-tem-constituicao');
                iLen = elements.length;
            }
            document.getElementById('divtxtDtConstituicao').style.display = '';
        } else {
            //aparecer todos os campos do houve constituição
            var elements = document.getElementsByClassName('tem-constituicao');
            var iLen = elements.length;
            while (iLen > 0) {
                elements[iLen - 1].className = elements[iLen - 1].className.replace('tem-constituicao', 'nao-tem-constituicao');
                elements = document.getElementsByClassName('tem-constituicao');
                iLen = elements.length;
            }
            document.getElementById('divtxtDtConstituicao').style.display = 'none';
        }
        mostraBotaoContituirDefinitivamente();
    }

    function consultarExtratoMulta() {

        //se o tipo da multa for por indicação de valor e nao houver ignora as validações e exibição do fieldset de multa
        if (isTipoMultaIndicacaoValor() == true && existeLancamentoProcedimento() == false) {
            document.getElementById('fieldsetMulta').style.display = 'none';
            return;
        }

        var valueDecisao = objTabelaDinamicaDecisao.hdn.value;
        document.getElementById('btnRetificarLancamento').style.display = 'none';
        document.getElementById('btnIncluirLancamento').style.display = 'none';
        mostrarEsconderElemento(document.getElementById('btnVincularLancamento'), 'none');
        document.getElementById('btnCancelarLancamento').style.display = 'none';
        document.getElementById('btnSuspenderLancamento').style.display = 'none';
        document.getElementById('btnDenegarRecurso').style.display = 'none';
        document.getElementById('btnCancelarRecurso').style.display = 'none';
        document.getElementById('divHouveConstituicaoChk').style.display = '';
        document.getElementById('btnConstituirDefinitivamente').style.display = '';
        document.getElementById('hdnSuspRazRecurso').value = '';
        document.getElementById('btnCancelarLancamentoAtivo').value = '';
        document.getElementById('txtDtApresentacaoRecurso').value = '';
        document.getElementById('hdnDtApresentacaoRecurso').value = '';
        document.getElementById('apresentacao-recurso').style.display = 'none';
        document.getElementById('hdnCreditosProcesso').value = document.getElementById('selCreditosProcesso').value;

        document.getElementById('selDocumento').value = '';
        document.getElementById('txtDtDecisaoDefinitiva').value = '';
        document.getElementById('hdnDtDecisaoDefinitiva').value = '';
        document.getElementById('txtDtConstituicao').value = '';
        document.getElementById('txtDtIntimacaoConstituicao').value = '';
        document.getElementById('chkReducaoRenuncia').checked = false;
        $('#lblInteressado').addClass('infraLabelObrigatorio');
        $('#lbNumeroInteressado').addClass('infraLabelObrigatorio');

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxConsultarExtratoMulta ?>",
            async: false,
            data: {
                valor_decisao: valueDecisao,
                id_procedimento: document.getElementById('hdnIdProcedimento').value,
                id_md_lit_lancamento: document.getElementById('selCreditosProcesso').value
            },
            beforeSend: function () {
                infraExibirAviso(false);
            },
            success: function (result) {

                if ($(result).find('erro').length > 0) {
                    alert($(result).find('erro').attr('descricao').replace('<br />', '\n\n'));
                    return;
                }

                if ($(result).find('dados').length > 0) {
                    document.getElementById('lblVlTotalMulta').innerHTML = "R$ " + $(result).find('multaAplicada').text();
                    document.getElementById('lblVlCreditoNaoLancado').innerHTML = "R$ " + $(result).find('creditoNaoLancado').text();
                    document.getElementById('lblVlCreditoLancado').innerHTML = "R$ " + $(result).find('creditoLancado').text();
                    document.getElementById('lblVlDesconto').innerHTML = "R$ " + $(result).find('desconto').text();
                    document.getElementById('lblVlArrecadado').innerHTML = "R$ " + $(result).find('arrecadado').text();
                    document.getElementById('lblVlDtUltimoPag').innerHTML = $(result).find('ultimoPagamento').text();
                    document.getElementById('lblVlSaldoDevAtualizado').innerHTML = "R$ " + $(result).find('devedorAtualizado').text();
                    document.getElementById('lblVlCredConstDef').innerHTML = "R$ " + $(result).find('constituidoDefinitivamente').text();

                    document.getElementById('hdnVlTotalMulta').value = $(result).find('multaAplicada').text();
                    document.getElementById('hdnVlCreditoNaoLancado').value = $(result).find('creditoNaoLancado').text();
                    document.getElementById('hdnVlCreditoLancado').value = $(result).find('creditoLancado').text();
                    document.getElementById('hdnVlDesconto').value = $(result).find('desconto').text();
                    document.getElementById('hdnVlArrecadado').value = $(result).find('arrecadado').text();
                    document.getElementById('hdnVlDtUltimoPagamento').value = $(result).find('ultimoPagamento').text();
                    document.getElementById('hdnVlSaldoDevAtualizado').value = $(result).find('devedorAtualizado').text();
                    document.getElementById('hdnVlCredConstituidoDef').value = $(result).find('constituidoDefinitivamente').text();

                    //datas
                    document.getElementById('txtDecisaoAplicacaoMulta').value = $(result).find('dtDecisaoAplicacaoMulta').text();
                    document.getElementById('txtDecisaoAplicacaoMulta').setAttribute('data-valor-antigo', $(result).find('dtDecisaoAplicacaoMulta').text());

                    document.getElementById('hdnDecisaoAplicacaoMulta').value = $(result).find('dtDecisaoAplicacaoMulta').text();

                    document.getElementById('txtDtIntimacaoAplMulta').value = $(result).find('dtIntimacaoDecisaoAplicacaoMulta').text();
                    document.getElementById('hdnDtIntimacaoAplMulta').value = $(result).find('dtIntimacaoDecisaoAplicacaoMulta').text();
                    document.getElementById('txtDtVencimento').value = $(result).find('dtVencimento').text();
                    document.getElementById('txtDtConstituicao').value = $(result).find('dtConstituicao').text();
                    document.getElementById('txtDtConstituicao').setAttribute('data-valor-antigo', $(result).find('dtConstituicao').text());
                    document.getElementById('txtDtIntimacaoConstituicao').value = $(result).find('dtIntimacaoConstituicao').text();
                    document.getElementById('txtDtIntimacaoAplMulta').setAttribute('data-valor-antigo', $(result).find('dtIntimacaoDecisaoAplicacaoMulta').text());
                    document.getElementById('hdnDtIntimacaoAplMulta').setAttribute('data-valor-antigo', $(result).find('dtIntimacaoDecisaoAplicacaoMulta').text());
                    document.getElementById('txtDtVencimento').setAttribute('data-valor-antigo', $(result).find('dtVencimento').text());
                    document.getElementById('txtDtIntimacaoConstituicao').setAttribute('data-valor-antigo', $(result).find('dtIntimacaoConstituicao').text());
                    document.getElementById('hdnDecisaoAplicacaoMulta').setAttribute('data-valor-antigo', $(result).find('dtDecisaoAplicacaoMulta').text());
                    document.getElementById('hdnIdSituacaoDecisao').value = $(result).find('idSituacaoDecisao').text();
                    document.getElementById('hdnIdSituacaoIntimacao').value = $(result).find('idSituacaoIntimacao').text();
                    document.getElementById('hdnIdSituacaoRecurso').value = $(result).find('idSituacaoRecurso').text();
                    document.getElementById('selDocumento').value = $(result).find('selDocumento').text();
                    document.getElementById('txtSituacaoDocOrigem').value = $(result).find('txtSituacaoDocOrigem').text();
                    document.getElementById('hdnPrazoDefesa').value = $(result).find('prazoDefesa').text();
                    document.getElementById('hdnTpPrazoDefesa').value = $(result).find('tpPrazoDefesa').text();
                    document.getElementById('hdnPrazoRecurso').value = $(result).find('prazoRecurso').text();
                    document.getElementById('hdnTpPrazoRecurso').value = $(result).find('tpPrazoRecurso').text();

                    //Data de apresentação do recurso
                    document.getElementById('txtDtApresentacaoRecurso').value = $(result).find('dtApresentacaoRecurso').text();
                    document.getElementById('hdnDtApresentacaoRecurso').value = $(result).find('dtApresentacaoRecurso').text();
                    document.getElementById('txtDtApresentacaoRecurso').setAttribute('data-valor-antigo', $(result).find('dtApresentacaoRecurso').text());
                    if ($(result).find('dtApresentacaoRecurso').text() != '') {
                        document.getElementById('apresentacao-recurso').style.display = '';
                    }

                    //interessado
                    if($(result).find('idInteressado').text() && document.getElementById('selInteressado').value != $(result).find('idInteressado').text()){
                        document.getElementById('selInteressado').value = $(result).find('idInteressado').text();
                        carregarDependenciaMulta($(result).find('idNumeroInteressado').text());
                    }

                    //Data decisao definitiva
                    if ($(result).find('dtDecisaoDefinitiva').text() != '') {
                        document.getElementById('txtDtDecisaoDefinitiva').value = $(result).find('dtDecisaoDefinitiva').text();
                        document.getElementById('hdnDtDecisaoDefinitiva').value = $(result).find('dtDecisaoDefinitiva').text();
                        document.getElementById('txtDtDecisaoDefinitiva').setAttribute('data-valor-antigo', $(result).find('dtDecisaoDefinitiva').text());
                    }

                    //botão historico
                    document.getElementById('btnHistoricoLancamento').setAttribute('data-url', $(result).find('urlHistoricoLancamento').text());

                    document.getElementById('hdnCreditosProcesso').value = document.getElementById('selCreditosProcesso').value;

                    var creditoNaoLancado = $(result).find('creditoNaoLancado').text().infraReplaceAll('.', '').replace(',', '.');
                    var creditoLancado = $(result).find('creditoLancado').text().infraReplaceAll('.', '').replace(',', '.');
                    var totalMultaAplicado = $(result).find('multaAplicada').text().infraReplaceAll('.', '').replace(',', '.');
                    var isNovoLancamento = $(result).find('isNovoLancamento').text();
                    var isCancelar = $(result).find('isCancelar ').text();
                    var sinExibeCancelamento = $(result).find('sinExibeCancelamento').text();
                    var sinExisteMajoracao = $(result).find('sinExisteMajoracao').text();
                    var selCreditosProcesso = document.getElementById('selCreditosProcesso').value;
                    var hdnSinSuspenso = document.getElementById('hdnSinSuspenso').value;
                    var hdnSinSuspensoLancamento = $(result).find('sinSuspenso').text();
                    var sinIsUltimaSitIntimacao = <?= $isSituacaoIntimacao ? 'true' : 'false'?>;
                    var sinTemDesicaoLancamento = $(result).find('sinTemDecisaoLancamento').text();
                    var sinIsHouveDecisaoMulta = <?= $isDecisaoMulta ? 'true' : 'false'?>;

                    //mostrar o fieldset de multa
                    if (creditoNaoLancado != '0.00' || totalMultaAplicado != '0.00') {
                        document.getElementById('fieldsetMulta').style.display = '';
                    } else {
                        document.getElementById('fieldsetMulta').style.display = 'none';
                    }

                    if (sinExibeCancelamento == 'N' && creditoNaoLancado != '0.00' && isNovoLancamento == 'S' && selCreditosProcesso == '') {
                        document.getElementById('btnIncluirLancamento').style.display = parseFloat(creditoNaoLancado) > 0 ? '' : 'none';
                        var estilo = parseFloat(creditoNaoLancado) > 0 ? '' : 'none';

                        mostrarEsconderElemento(document.getElementById('btnVincularLancamento'), estilo);

                    } else if (sinExibeCancelamento == 'N' && sinExisteMajoracao == 'S' && creditoNaoLancado > 0 && isNovoLancamento == 'S') {
                        //Novo Boleto da Diferença a Maior (combo de crédito à parte, com datas próprias)
                        if (!optionExiste('', document.getElementById('selCreditosProcesso')))
                            // verifica quantidade de registro para identificar majorado na tela
                            var qtde = 0;
                            Array.from(document.querySelectorAll('#selCreditosProcesso option')).forEach(option => {
                                qtde++;
                            });
                            infraSelectAdicionarOption(document.getElementById('selCreditosProcesso'), 'Majorado ' + qtde + ' (R$ ' + $(result).find('creditoNaoLancado').text() + ')', '');

                        infraSelectSelecionarItem(document.getElementById('selCreditosProcesso'), '');
                        //recarregar o fieldset para atualizar os valores da multa
                        window.setTimeout(function () {
                            consultarExtratoMulta()
                        }, 500);
                        return;
                    } else if ((sinExisteMajoracao == 'N' || (sinExisteMajoracao == 'S' && isNovoLancamento == 'N')) && sinExibeCancelamento == 'N' && creditoLancado != '0.00' && creditoNaoLancado != '0.00') {
                        document.getElementById('btnRetificarLancamento').style.display = '';
                        document.getElementById('hdnSuspRazRecurso').value = 'S';
                    }

                    if (sinExibeCancelamento == 'N') {
                        document.getElementById('selCreditosProcesso').setAttribute('sin-existe-majorado', $(result).find('sinExisteMajorado').text());
                    }

                    //Se não existe Crédito Lançado
                    if (sinExibeCancelamento == 'S' && creditoLancado != '0.00' && isCancelar == '0' && (hdnSinSuspenso == 'N' || hdnSinSuspensoLancamento == 'N')) {
                        if ('<?=$idUltimoLancamento?>' != document.getElementById('selCreditosProcesso').value) {
                            document.getElementById('selCreditosProcesso').value = '<?=$idUltimoLancamento?>';
                            document.getElementById('hdnCreditosProcesso').value = '<?=$idUltimoLancamento?>';
                            document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                            consultarExtratoMulta();
                        }
                        document.getElementById('btnCancelarLancamento').style.display = '';
                        document.getElementById('btnCancelarLancamentoAtivo').value = 'S';
                    }

                    //Se não existe Crédito Lançado
                    if (isNovoLancamento == 'N' && creditoNaoLancado != '0.00') {
                        if ('<?=$idUltimoLancamento?>' != document.getElementById('selCreditosProcesso').value) {
                            document.getElementById('selCreditosProcesso').value = '<?=$idUltimoLancamento?>';
                            document.getElementById('hdnCreditosProcesso').value = '<?=$idUltimoLancamento?>';
                            document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                            consultarExtratoMulta();
                        }
                    }

                    //a multa não pode ser retificado se ela for zerado
                    if (parseFloat(totalMultaAplicado) <= 0) {
                        document.getElementById('btnRetificarLancamento').style.display = 'none';
                    }

                    if (hdnSinSuspensoLancamento == 'S') {
                        document.getElementById('btnDenegarRecurso').style.display = '';
                        document.getElementById('btnCancelarRecurso').style.display = '';
                    }

                    //Botão apresentado apenas se a última situação cadastrada for do tipo Recursal(suspender recurso no fieldset multa).
                    if (((adicionouSit && isTpSitRecursal && !document.getElementById('chkReducaoRenuncia').checked) || document.getElementById('hdnStrUltimaSituacao').value == '(Recursal)') && hdnSinSuspensoLancamento == 'N' && isNovoLancamento != 'S') {
                        document.getElementById('btnSuspenderLancamento').style.display = '';
                    }

                    if (document.getElementById('selCreditosProcesso').options.length > 1) {
                        document.getElementById('divCreditoProcesso').style.display = '';
                    }

                    //se o botão de incluir lançamento estiver aparecendo então o houve constituição definitiva não pode aparecer
                    if (document.getElementById('btnIncluirLancamento').style.display == '') {
                        document.getElementById('divHouveConstituicao').style.display = 'none';
                    }

                    //mostrar e checked o sinConstituicaoDefinitiva
                    if ($(result).find('sinConstituicaoDefinitiva').text() == 'S') {
                        document.getElementById('chkHouveConstituicao').checked = true;
                        document.getElementById('chkHouveConstituicao').setAttribute('data-valor-antigo', 'S');
                        houveConstituicao(document.getElementById('chkHouveConstituicao'));
                    } else {
                        document.getElementById('chkHouveConstituicao').checked = false;
                        document.getElementById('chkHouveConstituicao').setAttribute('data-valor-antigo', 'N');
                        houveConstituicao(document.getElementById('chkHouveConstituicao'));
                    }

                    //mostrar e checked o sinRenunciaRecorrer
                    if ($(result).find('sinRenunciaRecorrer').text() == 'S') {
                        document.getElementById('chkReducaoRenuncia').checked = true;
                        document.getElementById('chkReducaoRenuncia').disabled = true;
                        document.getElementById('chkReducaoRenuncia').setAttribute('data-valor-antigo', 'S');
                    } else {
                        document.getElementById('chkReducaoRenuncia').checked = false;
                        document.getElementById('chkReducaoRenuncia').disabled = habilitarDescontoDecorrente();
                        document.getElementById('chkReducaoRenuncia').setAttribute('data-valor-antigo', 'N');
                    }
                    calcularData();

                    //mostrar e checked o sinRenunciaRecorrer
                    if ($(result).find('corSituacao').text() != '') {
                        document.getElementById('lblSaldoDevAtualizado').style.color = $(result).find('corSituacao').text();
                        document.getElementById('lblVlSaldoDevAtualizado').style.color = $(result).find('corSituacao').text();
                    } else {
                        document.getElementById('lblSaldoDevAtualizado').style.color = 'black';
                        document.getElementById('lblVlSaldoDevAtualizado').style.color = 'black';
                    }
                }

                if (isUltimaSituacaoDecisoria() || document.getElementById('hdnIsGestor').value == 1) {
                    infraHabilitarCamposDivMulta(document.getElementById('divDataGestaoMulta'));
                    infraDesabilitarCamposDivMulta(document.getElementById('divtxtDtConstituicao'));

                } else {
                    infraHabilitarCamposDivMulta(document.getElementById('divDtaIntimacaoAplMulta'));
                    infraHabilitarCamposDivMulta(document.getElementById('divDtaDecursoPrazoRecurso'));
                }

                if (document.getElementById('selCreditosProcesso').value != '') {
                    document.getElementById('selInteressado').disabled = true;
                    document.getElementById('selNumeroInteressado').disabled = true;
                    $('#lblInteressado').removeClass('infraLabelObrigatorio');
                    $('#lbNumeroInteressado').removeClass('infraLabelObrigatorio');
                }

                if (!isExisteSituacaoConclusiva()) {
                    infraDesabilitarCamposDivMulta(document.getElementById('divHouveConstituicao'));
                    document.getElementById('btnConstituirDefinitivamente').style.display = 'none';
                } else if (document.getElementById('hdnIsGestor').value == 0 && document.getElementById('chkHouveConstituicao').checked) {
                    infraDesabilitarCamposDivMulta(document.getElementById('divHouveConstituicao'));
                    document.getElementById('btnConstituirDefinitivamente').style.display = 'none';
                    document.getElementById('chkReducaoRenuncia').disabled =  habilitarDescontoDecorrente();
                } else {
                    infraHabilitarCamposDivMulta(document.getElementById('divHouveConstituicao'));
                    document.getElementById('chkReducaoRenuncia').disabled = habilitarDescontoDecorrente();
                    document.getElementById('btnConstituirDefinitivamente').style.display = '';
                    infraHabilitarCamposDivMulta(document.getElementById('divDataGestaoMulta'));
                    infraHabilitarCamposDivMulta(document.getElementById('divtxtDtConstituicao'));
                }

                if (document.getElementById('txtDtConstituicao').getAttribute('data-valor-antigo') != '' && document.getElementById('hdnIsGestor').value == 0) {
                    infraDesabilitarCamposDivMulta(document.getElementById('divtxtDtConstituicao'));
                }

                // se for um novo lancamento desabilita o campo até que a inclusao seja realizada
                if (isNovoLancamento == 'S')
                {
                    document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                }

                //por não haver promisses isso é necessario para replicar as datas da situação para multa
                replicarDataParaFieldsetGestaoMulta();

                var txtConst = document.getElementById('txtDtConstituicao');
                var txtIntConst = document.getElementById('txtDtIntimacaoConstituicao');
                var dtPadrao = document.getElementById('hdnDtSituacaoConclusiva');

                if (txtConst.value == '') {
                    txtConst.value = dtPadrao.value;
                }

                if (txtIntConst.value == '') {
                    txtIntConst.value = dtPadrao.value;
                }

                atualizarComboBoxPrazoDefesa(result);
                atualizarComboBoxDtDecursoPrazoRecurso(result);
                atualizarHiddenDtDecursoPrazo()
                atualizarHiddenDtDecursoPrazoRecurso()

            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
    }

    function atualizarComboBoxPrazoDefesa(result){
        document.getElementById('hdnDtDecursoPrazo').value = $(result).find('dtPrazoDefesa').text();

        var $select = $('#selDtDecursoPrazo');
        $select.empty();

        var options = $(result).find('htmlOptionDtDecursoPrazo').text();
        options = options.replace(/ï¿½teis/g, 'úteis');

        $select.append(options);
        if ($select.find('option').length === 2) {
            $select.find('option').eq(1).prop('selected', true);
            $select.prop('disabled', true);
        } else {
            $select.prop('disabled', false);
        }
        verificarExistenciaPrazoDefesaCombo();
    }

    function atualizarComboBoxDtDecursoPrazoRecurso(result){
        var $select = $('#selDtDecursoPrazoRecurso');
        $select.empty();

        var options = $(result).find('htmlOptionDtDecursoPrazoRecurso').text();

        options = options.replace(/ï¿½teis/g, 'úteis');

        $select.append(options);
        if ($select.find('option').length === 2) {
            $select.find('option').eq(1).prop('selected', true);
            $select.prop('disabled', true);
        } else {
            $select.prop('disabled', false);
        }

        document.getElementById('selDtDecursoPrazoRecurso').value = $(result).find('dtDecursoPrazoRecurso').text();
        document.getElementById('hdnDtDecursoPrazoRecurso').value = $(result).find('dtDecursoPrazoRecurso').text();
        document.getElementById('selDtDecursoPrazoRecurso').setAttribute('data-valor-antigo', $(result).find('dtDecursoPrazoRecurso').text());

        verificarExistenciaPrazoRecursoCombo()

        var selectElement = document.getElementById("selDtDecursoPrazoRecurso");
        var optionsCount = selectElement.options.length;

        if (optionsCount === 0) {
            selectElement.disabled = true;
        } else if (optionsCount === 1) {
            selectElement.selectedIndex = 0;
            selectElement.disabled = true;
        } else {
            selectElement.disabled = false;
        }
    }

    function verificarExistenciaPrazoDefesaCombo() {
        const selectElement = document.getElementById('selDtDecursoPrazo');
        const prazoDefesa = document.getElementById('hdnPrazoDefesa').value; // valor do prazo retornado do serviço
        const tpPrazoDefesa = document.getElementById('hdnTpPrazoDefesa').value; // tipo de prazo retornado do serviço (U - úteis, C - corridos)
        const selectedValue = document.getElementById('hdnDtDecursoPrazo').value; // valor da data retornada pelo serviço

        let found = false;

        // Verifica se a opção com o valor retornado do serviço já existe no select
        for (let i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value === selectedValue) {
                found = true;
                break;
            }
        }

        // Se não encontrou, cria a nova opção e seleciona
        if (!found && selectedValue) { // Apenas cria a opção se selectedValue não for vazio
            const newOptionText = `${selectedValue}`;
            const newOption = new Option(newOptionText, selectedValue);
            newOption.setAttribute('prazo-defesa', prazoDefesa);
            newOption.setAttribute('tp-prazo-defesa', tpPrazoDefesa);
            selectElement.add(newOption); // Adiciona a nova opção
            selectElement.value = selectedValue; // Seleciona a nova opção
        } else if (found) {
            // Se a opção já existe, apenas seleciona ela
            selectElement.value = selectedValue;
        }
    }


    function verificarExistenciaPrazoRecursoCombo() {
        const selectElement = document.getElementById('selDtDecursoPrazoRecurso');
        const prazoRecurso = document.getElementById('hdnPrazoRecurso').value; // valor do prazo retornado do serviço
        const tpPrazoRecurso = document.getElementById('hdnTpPrazoRecurso').value; // tipo de prazo retornado do serviço (U - úteis, C - corridos)
        const selectedValue = document.getElementById('hdnDtDecursoPrazoRecurso').value; // valor da data retornada pelo serviço

        let found = false;

        // Verifica se a opção com o valor retornado do serviço já existe no select
        for (let i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value === selectedValue) {
                found = true;
                break;
            }
        }

        // Se não encontrou, cria a nova opção e seleciona
        if (!found && selectedValue) {  // Verifica se selectedValue não é vazio
            const newOptionText = `${selectedValue}`;
            const newOption = new Option(newOptionText, selectedValue);
            newOption.setAttribute('prazo-recurso', prazoRecurso);
            newOption.setAttribute('tp-prazo-recurso', tpPrazoRecurso);
            selectElement.add(newOption); // Adiciona a nova opção
            selectElement.value = selectedValue; // Seleciona a nova opção
        } else if (found) {
            // Se a opção já existe, apenas seleciona ela
            selectElement.value = selectedValue;
        }
    }

    function suspenderEmRazaoRecurso(element){
        //Quando ocorrer uma alteração da decisão não será possivel suspender em razão de recurso que não seja o ultimo lancamento, antes é necessario executar a ação no ultimo lançamento
        //Para atender essa regra, identificamos se houve alteração no cadastro da decisão a partir do valor do crédito não lançado
        if (
            (document.getElementById('hdnSuspRazRecurso').value == 'S' || document.getElementById('btnCancelarLancamentoAtivo').value == 'S') &&
            document.getElementById('lblVlCreditoNaoLancado').innerText != 'R$ 0,00'
        ) {
            var msg = 'Não é possível Suspender em Razão de Recuro sobre o Crédito ' + $("#selCreditosProcesso option:selected").text() + ', pois ocorreu alteração de Decisão que, antes, é necessário acionar ações sobre o crédito ' + $("#selCreditosProcesso  option[value="+'<?=$idUltimoLancamento?>'+"]").text() + '.';
            alert(msg);
            return;
        }

        abrirModalJustificativaLancamento(element);
    }

    function denegarRecurso(element){
        //Quando ocorrer uma alteração da decisão não será possivel denegar recurso que não seja o ultimo lancamento, antes é necessario executar a ação no ultimo lançamento
        //Para atender essa regra, identificamos se houve alteração no cadastro da decisão a partir do valor do crédito não lançado
        if ('<?=$idUltimoLancamento?>' != document.getElementById('selCreditosProcesso').value && document.getElementById('lblVlCreditoNaoLancado').innerText != 'R$ 0,00') {
            var msg = 'Não é possível Denegar Recuros sobre o Crédito ' + $("#selCreditosProcesso option:selected").text() + ', pois ocorreu alteração de Decisão que, antes, é necessário acionar ações sobre o crédito ' + $("#selCreditosProcesso  option[value="+'<?=$idUltimoLancamento?>'+"]").text() + '.';
            alert(msg);
            return;
        }
        abrirModalJustificativaLancamento(element);
    }

    function cancelarRecurso(element){
        //Quando ocorrer uma alteração da decisão não será possivel cancelar em razão de recurso que não seja o ultimo lancamento, antes é necessario executar a ação no ultimo lançamento
        //Para atender essa regra, identificamos se houve alteração no cadastro da decisão a partir do valor do crédito não lançado
        if ('<?=$idUltimoLancamento?>' != document.getElementById('selCreditosProcesso').value && document.getElementById('lblVlCreditoNaoLancado').innerText != 'R$ 0,00') {
            var msg = 'Não é possível Cancelar Recuros sobre o Crédito ' + $("#selCreditosProcesso option:selected").text() + ', pois ocorreu alteração de Decisão que, antes, é necessário acionar ações sobre o crédito ' + $("#selCreditosProcesso  option[value="+'<?=$idUltimoLancamento?>'+"]").text() + '.';
            alert(msg);
            return;
        }
        abrirModalJustificativaLancamento(element);
    }

    function retificarLancamento(element){
        //Quando ocorrer uma alteração da decisão não será retificar recurso que não seja o ultimo lancamento, antes é necessario executar a ação no ultimo lançamento
        //Para atender essa regra, identificamos se houve alteração no cadastro da decisão a partir do valor do crédito não lançado
        if ('<?=$idUltimoLancamento?>' != document.getElementById('selCreditosProcesso').value && document.getElementById('lblVlCreditoNaoLancado').innerText != 'R$ 0,00') {
            var msg = 'Não é possível Retificar Lançamento sobre o Crédito ' + $("#selCreditosProcesso option:selected").text() + ', pois ocorreu alteração de Decisão que, antes, é necessário acionar ações sobre o crédito ' + $("#selCreditosProcesso  option[value="+'<?=$idUltimoLancamento?>'+"]").text() + '.';
            alert(msg);
            return;
        }
        abrirModalJustificativaLancamento(element);
    }

    function abrirModalJustificativaLancamento(element) {
        var url = element.getAttribute('data-url');
        var dadosComplementares = '<?php echo $objMdLitTipoControleDTO->getStrSinParamModalComplInteressado()?>';

        // if(objTabelaDinamicaDecisao.verificarCadastroParcial()){
        //     alert("Foi identificado que ainda existem infrações sem Decisão cadastrada. Posteriormente, para prosseguir com o cadastro de novas Situações ou a Gestão de Multa, ainda será necessário finalizar o Cadastro das Decisões.");
        //     return;
        // }

        if ((document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null') && dadosComplementares == 'S') {
            alert('Selecione o número de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }

        if (document.getElementById('txtDecisaoAplicacaoMulta').value == '') {
            alert('A Data da Decisão de Aplicação da Multa é de preenchimento obrigatório.');
            return false;
        }

        if (document.getElementById('txtDtVencimento').value == '') {
            alert('A Data de Vencimento é de preenchimento obrigatório.');
            return false;
        }

        if (!document.getElementById('hdnDtDecursoPrazo').value ) {
            alert('A Data do Decurso do Prazo para Defesa é obrigatória. Preencha antes de prosseguir.');
            return false;
        }

        infraAbrirJanelaModal(url,
            880,
            480);
    }


    function abrirModalVincularLancamento(element) {
        var url = element.getAttribute('data-url');
        var dadosComplementares = '<?php echo $objMdLitTipoControleDTO->getStrSinParamModalComplInteressado()?>';

        // if(objTabelaDinamicaDecisao.verificarCadastroParcial()){
        //     alert("Foi identificado que ainda existem infrações sem Decisão cadastrada. Posteriormente, para prosseguir com o cadastro de novas Situações ou a Gestão de Multa, ainda será necessário finalizar o Cadastro das Decisões.");
        //     return;
        // }
        if ((document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null') && dadosComplementares == 'S') {
            alert('Selecione o número de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }

        infraAbrirJanelaModal(url,
            1024,
            600);
    }

    function abrirModalConstituirDefinitivamente(element) {
        var url = element.getAttribute('data-url');
        var txtConst = document.getElementById('txtDtConstituicao');
        var txtIntConst = document.getElementById('txtDtIntimacaoConstituicao');
        var dadosComplementares = '<?php echo $objMdLitTipoControleDTO->getStrSinParamModalComplInteressado()?>';

        document.getElementById('hdnDtDecisaoDefinitiva').value = document.getElementById('txtDtDecisaoDefinitiva').value

        if ((document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null') && dadosComplementares == 'S') {
            alert('Selecione o número de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }

        if (infraTrim(txtConst.value == '')) {
            alert('Informe a Data da Constituição Definitiva!');
            return false;
        }

        if (infraTrim(txtIntConst.value == '')) {
            alert('Informe a Data da Intimação da Decisão Definitiva!');
            return false;
        }

        if (document.getElementById('txtDtDecisaoDefinitiva').value == '') {
            alert('O Documento da Decisão de Aplicação da Multa é de preenchimento obrigatório.');
            return false;
        }
        if (infraTrim(document.getElementById('txtDtDecisaoDefinitiva').value) == '') {
            alert('A Data da Decisão Definitiva é de preenchimento obrigatório.');
            return false;
        }

        infraAbrirJanelaModal(url,
            680,
            300);

    }

    function retornarDate(hdnDtDefault) {
        var arrDtEntrada = hdnDtDefault.split('/');
        var data = new Date();

        data.setDate(arrDtEntrada[0]);
        data.setMonth(arrDtEntrada[1] - 1);
        data.setFullYear(arrDtEntrada[2]);

        return data;

    }

    function validarFormatoData(obj) {
        var validar = infraValidarData(obj, false);
        if (!validar) {
            alert('Data Inválida!');
            obj.value = '';
        }
    }

    function mostraBotaoContituirDefinitivamente(element) {
        if (document.getElementById('chkHouveConstituicao').checked) {
            document.getElementById('btnConstituirDefinitivamente').style.display = '';
        } else {
            document.getElementById('btnConstituirDefinitivamente').style.display = 'none';
        }
    }

    function abrirModalHistoricoLancamento() {
        //infraAbrirJanela(document.getElementById('btnHistoricoLancamento').getAttribute('data-url'), 'janelaHistoricoLancamento', 900, 400);
        infraAbrirJanelaModal(document.getElementById('btnHistoricoLancamento').getAttribute('data-url'), 1200, 700);
    }

    function abrirModalCancelarLancamento() {

        if ('<?=$idUltimoLancamento?>' != document.getElementById('selCreditosProcesso').value) {
            var msg = 'Não é possível Cancelar Lançamento sobre o Crédito ' + $("#selCreditosProcesso option:selected").text() + ', pois ocorreu alteração de Decisão que, antes, é necessário acionar ações sobre o crédito ' + $("#selCreditosProcesso  option[value="+'<?=$idUltimoLancamento?>'+"]").text() + '.';
            alert(msg);
            return;
        }

        var dadosComplementares = '<?php echo $objMdLitTipoControleDTO->getStrSinParamModalComplInteressado()?>';
        if ((document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null')&& dadosComplementares == 'S') {
            alert('Selecione o número de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }
        if (document.getElementById('selCreditosProcesso').getAttribute('sin-existe-majorado') == 'S') {
            alert('Existe créditos do processo majorado, verifique se não é o caso de um Retificação ou de que se deve primeiramente cancelar o Lançamento Majorado.');
            return false;
        }

        infraAbrirJanelaModal('<?=$strLinkModalCancelarLancamento?>', 900, 400);

    }

    function mudarCreditosProcesso(element) {
        consultarExtratoMulta();
    }

    /**
     * Return se um option existe em um <select> object
     * @param {String} valor Um string representando o valor do option
     * @param {Object} element Um Select object
     */
    function optionExiste(valor, element) {
        var optionExists = false,
            optionsLength = element.length;

        while (optionsLength--) {
            if (element.options[optionsLength].value === valor) {
                optionExists = true;
                break;
            }
        }
        return optionExists;
    }

    function removerOptionVazio(element) {
        for (var i = 0; i < element.options.length; i++) {
            if (element.options[i].value == '')
                element.options[i] = null;
        }
    }

    function verificarCondicionaisMulta() {

        //se o tipo da multa for por indicacao de valor não valida o lancamento da multa
        if (isTipoMultaIndicacaoValor() && existeLancamentoProcedimento() == false) {
            return true;
        }

        // se nao estiver mostrando o fieldset de multa não terá validação
        if (document.getElementById('fieldsetMulta').style.display == 'none') {
            return true;
        }
        if (document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('txtDecisaoAplicacaoMulta').value) == '') {
            alert('Informe a data da decisão de aplicação da multa.');
            document.getElementById('txtDecisaoAplicacaoMulta').focus();
            return false;
        }
        if (document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('txtDtVencimento').value) == '') {
            alert('Informe a data de vencimento.');
            document.getElementById('txtDtVencimento').focus();
            return false;
        }
        if (document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('hdnDtIntimacaoAplMulta').value) != '' && infraCompararDatas(document.getElementById('txtDecisaoAplicacaoMulta').value, document.getElementById('hdnDtIntimacaoAplMulta').value) < 0) {
            alert('A data da intimação da decisão de aplicação da multa deve ser igual ou maior que a Data da decisão de aplicação da multa.');
            document.getElementById('hdnDtIntimacaoAplMulta').focus();
            return false;
        }

        if(
            document.getElementById('hdnIdMdLitFuncionalidade').value &&
            document.getElementById('txtDtIntimacaoAplMulta').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('hdnJustificativaLancamento').value == '' &&
            document.getElementById('txtDtIntimacaoAplMulta').value != document.getElementById('txtDtIntimacaoAplMulta').getAttribute('data-valor-antigo')
        ){
            alert('Foram identificados informações da Gestão de Multa pendentes de atualização no SISTEMA DE ARRECADAÇÃO. Verifique se a ação para retificar o lançamento foi acionada.');
            return false;
        }

        if(
            document.getElementById('hdnIdMdLitFuncionalidade').value &&
            document.getElementById('hdnJustificativaLancamento').value &&
            document.getElementById('txtDtApresentacaoRecurso').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('hdnJustificativaLancamento').value == '' &&
            document.getElementById('txtDtApresentacaoRecurso').value != document.getElementById('txtDtApresentacaoRecurso').getAttribute('data-valor-antigo')
        ){
            alert('Foram identificados informações da Gestão de Multa pendentes de atualização no SISTEMA DE ARRECADAÇÃO. Verifique se a ação para retificar o lançamento foi acionada.');
            return false;
        }

        if(
            document.getElementById('hdnIdMdLitFuncionalidade').value &&
            document.getElementById('chkHouveConstituicao').checked == true &&
            document.getElementById('txtDtDecisaoDefinitiva').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('hdnJustificativaLancamento').value == '' &&
            document.getElementById('txtDtDecisaoDefinitiva').value != document.getElementById('txtDtDecisaoDefinitiva').getAttribute('data-valor-antigo')
        ){
            alert('Foram identificados informações da Gestão de Multa pendentes de atualização no SISTEMA DE ARRECADAÇÃO. Verifique se a ação para retificar o lançamento foi acionada.');
            return false;
        }

        // regra do botao retificar quando altera data da intimação da decisão definitiva
        if(
            document.getElementById('hdnIdMdLitFuncionalidade').value &&
            document.getElementById('chkHouveConstituicao').checked == true &&
            document.getElementById('txtDtIntimacaoConstituicao').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('hdnJustificativaLancamento').value == '' &&
            document.getElementById('txtDtIntimacaoConstituicao').value != document.getElementById('txtDtDecisaoDefinitiva').getAttribute('data-valor-antigo')
        ){
            alert('Foram identificados informações da Gestão de Multa pendentes de atualização no SISTEMA DE ARRECADAÇÃO. Verifique se a ação para retificar o lançamento foi acionada.');
            return false;
        }

        if(
            document.getElementById('hdnIdMdLitFuncionalidade').value &&
            document.getElementById('chkHouveConstituicao').checked == true &&
            document.getElementById('txtDtConstituicao').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('hdnJustificativaLancamento').value == '' &&
            document.getElementById('txtDtConstituicao').value != document.getElementById('txtDtConstituicao').getAttribute('data-valor-antigo')
        ){
            alert('Foram identificados informações da Gestão de Multa pendentes de atualização no SISTEMA DE ARRECADAÇÃO. Verifique se a ação para retificar o lançamento foi acionada.');
            return false;
        }

        if(
            document.getElementById('hdnIdMdLitFuncionalidade').value &&
            document.getElementById('txtDtApresentacaoRecurso').value &&
            document.getElementById('txtDtApresentacaoRecurso').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('hdnJustificativaLancamento').value == '' &&
            document.getElementById('txtDtApresentacaoRecurso').value != document.getElementById('txtDtApresentacaoRecurso').getAttribute('data-valor-antigo')
        ){
            alert('Foram identificados informações da Gestão de Multa pendentes de atualização no SISTEMA DE ARRECADAÇÃO. Verifique se a ação para retificar o lançamento foi acionada.');
            return false;
        }

        if(
            document.getElementById('hdnIdMdLitFuncionalidade').value &&
            document.getElementById('txtDecisaoAplicacaoMulta').value &&
            document.getElementById('txtDecisaoAplicacaoMulta').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('hdnJustificativaLancamento').value == '' &&
            document.getElementById('txtDecisaoAplicacaoMulta').value != document.getElementById('txtDecisaoAplicacaoMulta').getAttribute('data-valor-antigo')
        ){
            alert('Foram identificados informações da Gestão de Multa pendentes de atualização no SISTEMA DE ARRECADAÇÃO. Verifique se a ação para retificar o lançamento foi acionada.');
            return false;
        }

        if (document.getElementById('txtDtIntimacaoAplMulta').value && validarDataVencimentoLancamento()) {
            alert('A Data de Vencimento inserida não é válida, somente é permitida Data de Vencimento com diferença mínima de 30 dias após a Data de Intimação da Decisão que aplicou a Multa.');
            return false;
        }

        if (!document.getElementById('hdnDtDecursoPrazo').value ) {
            alert('A Data do Decurso do Prazo para Defesa é obrigatória. Preencha antes de prosseguir.');
            return false;
        }

        return true;
    }

    function validarDataVencimentoLancamento() {
        var dtaIntimacao = document.getElementById('txtDtIntimacaoAplMulta').value;
        var dtaPermitida = infraCalcularDataDias(dtaIntimacao, 30).split("/");
        var datePermitida = new Date(dtaPermitida[2], dtaPermitida[1] - 1, dtaPermitida[0]);

        var dtaVencimento = document.getElementById('txtDtVencimento').value.split("/");
        var dateValidade = new Date(dtaVencimento[2], dtaVencimento[1] - 1, dtaVencimento[0]);

        if (dateValidade >= datePermitida) {
            return false;
        }
        return true;
    }

    function isTipoMultaIndicacaoValor() {
        var flagMultaIntegracao = false;
        var arrDecisao = objTabelaDinamicaDecisao.obterItens();
        var arrEspecies = new Array();
        for (var i = 0; i < arrDecisao.length; i++) {
            if (arrDecisao[i][3] != 'null') {
                //armazena o id das especies de decisão para validar a multa com ou sem integração
                arrEspecies.push(arrDecisao[i][3]);
            }
        }

        if (arrEspecies.length == 0) {
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_recuperar_especie_decisao') ?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId': arrEspecies},
            success: function (data) {
                $.each($(data).find('MdLitEspecieDecisaoDTO'), function (key, value) {
                    if ($(value).find('StaTipoIndicacaoMulta').text() == "<?=MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO ?>") {
                        flagMultaIntegracao = true;
                    }

                });
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o validação do do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

        //se for multa por integração retorna pra cair na validação
        if (flagMultaIntegracao) {
            return false;
        }

        //se for multa por indicação de valor não precisa validar o lancamento do valor por integração
        return true;

    }

    function existeLancamentoProcedimento() {
        var idProcedimento = document.getElementById('hdnIdProcedimento').value;
        var existeLancamento = false;
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_recuperar_lancamentos_procedimento') ?>",
            dataType: "xml",
            async: false,
            data: {'idProcedimento': idProcedimento},
            success: function (data) {
                if ($(data).find('MdLitLancamentoDTO').length > 0) {
                    existeLancamento = true;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o validação do do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

        return existeLancamento;
    }


    function verificarAlteracoesGestaoMultaDecisao(idSituacao, dtaSituacao){
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_alteracao_dt_decisao') ?>",
            dataType: "xml",
            async: false,
            data: {'idSituacao': idSituacao},
            success: function (data) {
                if ($(data).find('idLancamento').text()) {
                    document.getElementById('selCreditosProcesso').removeAttribute('disabled');
                    if ($(data).find('dtaDecisao').text() != dtaSituacao) {
                        document.getElementById('selCreditosProcesso').value = $(data).find('idLancamento').text();
                        document.getElementById('hdnCreditosProcesso').value = $(data).find('idLancamento').text();
                        document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                        consultarExtratoMulta();
                        document.getElementById('txtDecisaoAplicacaoMulta').value = dtaSituacao;
                        document.getElementById('hdnDecisaoAplicacaoMulta').value = dtaSituacao;
                        verificarMudancaMulta();
                    }
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar verificação.";
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
    }

    function verificarAlteracoesGestaoMultaIntimacao(idSituacao, dtaSituacao){
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_alteracao_dt_intimacao') ?>",
            dataType: "xml",
            async: false,
            data: {'idSituacao': idSituacao},
            success: function (data) {
                if ($(data).find('idLancamento').text()) {
                    document.getElementById('selCreditosProcesso').removeAttribute('disabled');
                    if ($(data).find('dtaIntimacao').text() != dtaSituacao) {
                        document.getElementById('selCreditosProcesso').value = $(data).find('idLancamento').text();
                        document.getElementById('hdnCreditosProcesso').value = $(data).find('idLancamento').text();
                        document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                        consultarExtratoMulta();
                        document.getElementById('txtDtIntimacaoAplMulta').value = dtaSituacao;
                        document.getElementById('hdnDtIntimacaoAplMulta').value = dtaSituacao;
                        verificarNecessidadeAlteracaoDataValidade(dtaSituacao);
                        calcularDecursoPrazoRecurso(idSituacao);
                        verificarMudancaMulta();
                    }
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar verificação.";
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
    }

    function verificarNecessidadeAlteracaoDataValidade(){
        var novaDtaVencimentoApartirIntimacao = document.getElementById('txtDtIntimacaoAplMulta').value;
        novaDtaVencimentoApartirIntimacao = infraCalcularDataDias(novaDtaVencimentoApartirIntimacao, 30);

        var novaDtaVencimento = novaDtaVencimentoApartirIntimacao.split("/");
        var novaDtaVencimento = new Date(novaDtaVencimento[2], novaDtaVencimento[1] - 1, novaDtaVencimento[0]);

        var antigaDtaVencimento = document.getElementById('txtDtVencimento').value.split("/");
        var antigaDtaVencimento = new Date(antigaDtaVencimento[2], antigaDtaVencimento[1] - 1, antigaDtaVencimento[0]);

        if (antigaDtaVencimento < novaDtaVencimento) {
            document.getElementById('txtDtVencimento').value = novaDtaVencimentoApartirIntimacao;
        }
    }

    function verificarAlteracoesGestaoMultaRecurso(idSituacao, dtaSituacao){
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_alteracao_dt_recurso') ?>",
            dataType: "xml",
            async: false,
            data: {'idSituacao': idSituacao},
            success: function (data) {
                if ($(data).find('idLancamento').text()) {
                    document.getElementById('selCreditosProcesso').removeAttribute('disabled');
                    if ($(data).find('dtaRecurso').text() != dtaSituacao) {
                        document.getElementById('selCreditosProcesso').value = $(data).find('idLancamento').text();
                        document.getElementById('hdnCreditosProcesso').value = $(data).find('idLancamento').text();
                        document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                        consultarExtratoMulta();
                        document.getElementById('txtDtApresentacaoRecurso').value = document.getElementById('txtDtTipoSituacao').value;
                        document.getElementById('hdnDtApresentacaoRecurso').value = document.getElementById('txtDtTipoSituacao').value;
                        verificarMudancaMulta();
                    }
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar verificação.";
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
    }

    function verificarAlteracoesGestaoMultaIntimacaoDataDecursoPrazoDefesa(arrLinha){
        var idProcedimento = document.getElementById('hdnIdProcedimento').value;
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_alteracao_dt_primeira_intimacao') ?>",
            dataType: "xml",
            async: false,
            data: {
                'idSituacao': arrLinha[0],
                'idProcedimento': idProcedimento,
                'novaDataSituacao': arrLinha[11]
            },
            success: function (data) {
                if ($(data).find('novaData').text()) {
                    document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                    document.getElementById('selDtDecursoPrazo').value = $(data).find('novaData').text();
                    document.getElementById('hdnDtDecursoPrazo').value = $(data).find('novaData').text();
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar verificação.";
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
    }

    function verificarAlteracoesGestaoMultaNovo(arrLinha){
        if (arrLinha[17] == 'Intimacao') {
            var idProcedimento = document.getElementById('hdnIdProcedimento').value;

            $.ajax({
                type: "POST",
                url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_lancamento_para_intimacao') ?>",
                dataType: "xml",
                async: false,
                data: {'idProcedimento': idProcedimento},
                success: function (data) {
                    if ($(data).find('idLancamento').text()) {
                        document.getElementById('selCreditosProcesso').value = $(data).find('idLancamento').text();
                        document.getElementById('hdnCreditosProcesso').value = $(data).find('idLancamento').text();
                        consultarExtratoMulta();
                        document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                        document.getElementById('txtDtIntimacaoAplMulta').value = arrLinha[11];
                        document.getElementById('hdnDtIntimacaoAplMulta').value = arrLinha[11];
                        //seta estilo para o campo como obrigatorio
                        calcularDecursoPrazoRecurso(null);
                        verificarMudancaMulta();
                    }
                },
                error: function (msgError) {
                    msgCommit = "Erro ao processar verificação.";
                },
                complete: function (result) {
                    infraAvisoCancelar();
                }
            });
        }
    }

    function calcularData() {
        var objSituacao = objTabelaDinamicaSituacao.obterItens();
        var i = objSituacao.length - 1;
        if (
            infraRetirarAcentos(objSituacao[i][17].infraReplaceAll(' ', '').infraReplaceAll('(', '').infraReplaceAll(')', '')) == 'Decisoria' &&
            document.getElementById('hdnIdSituacaoDecisao').value == ''
        ) {
            var dtaDecisaoAplicacaoMulta = objSituacao[i][11];
            //calcula a ultima data da decisão cadastrada  é adiciona 40 dias
            var dtaVencimento = infraCalcularDataDias(dtaDecisaoAplicacaoMulta, 40);
            document.getElementById('txtDecisaoAplicacaoMulta').value = dtaDecisaoAplicacaoMulta;
            document.getElementById('hdnDecisaoAplicacaoMulta').value = dtaDecisaoAplicacaoMulta;
            document.getElementById('txtDtVencimento').value = dtaVencimento;
            document.getElementById('txtSituacaoDocOrigem').value = objSituacao[i][26] + " " + objSituacao[i][10] + " - " + objSituacao[i][13];
            verificarMudancaMulta();


        }
    }

    function verificarMudancaMulta() {
        var mostrarBotaoRetificar = false;

        // DATA DA DECISAO DE APLICACAO DA MULTA
        if(
            document.getElementById('txtDecisaoAplicacaoMulta').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('txtDecisaoAplicacaoMulta').value != document.getElementById('txtDecisaoAplicacaoMulta').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }

        // DATA DA INTIMACAO DA DECISAO DE APLICACAO DA MULTA
        if(
            document.getElementById('txtDtIntimacaoAplMulta').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('txtDtIntimacaoAplMulta').value != document.getElementById('txtDtIntimacaoAplMulta').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }
        // DATA DO DECURSO DO PRAZO PARA RECURSO
        if(
            document.getElementById('hdnDtDecursoPrazoRecurso').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('hdnDtDecursoPrazoRecurso').value != document.getElementById('hdnDtDecursoPrazoRecurso').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }

        //DATA DE VENCIMENTO
        if(
            document.getElementById('txtDtVencimento').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('txtDtVencimento').value != document.getElementById('txtDtVencimento').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }

        //DATA DE APRESENTACAO DE RECURSO
        if(
            document.getElementById('txtDtApresentacaoRecurso').value &&
            document.getElementById('txtDtApresentacaoRecurso').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('txtDtApresentacaoRecurso').value != document.getElementById('txtDtApresentacaoRecurso').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }

        //NÚMERO DE COMPLEMENTO DO INTERESSADO
        if(
            document.getElementById('selNumeroInteressado').value &&
            document.getElementById('selNumeroInteressado').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('selNumeroInteressado').value != document.getElementById('selNumeroInteressado').getAttribute('data-id-dado-interessado')
        ){
            mostrarBotaoRetificar = true;
        }

        //DATA DA DECISAO DEFINITIVA
        if(
            document.getElementById('txtDtDecisaoDefinitiva').value &&
            document.getElementById('txtDtDecisaoDefinitiva').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('txtDtDecisaoDefinitiva').value != document.getElementById('txtDtDecisaoDefinitiva').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }

        //DATA DA CONSTITUICAO DEFINITIVA
        if(
            document.getElementById('txtDtConstituicao').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('txtDtConstituicao').value != document.getElementById('txtDtConstituicao').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }

        //DATA DA INTIMACAO DA DECISAO DEFINITIVA
        if(
            document.getElementById('txtDtIntimacaoConstituicao').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('txtDtIntimacaoConstituicao').value != document.getElementById('txtDtIntimacaoConstituicao').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }

        //DESCONTO DECORRENTE DA RENUNCIA AO DIREITO DE RECORRER
        var recucaoRenunciaValorAntigo = false;
        if(document.getElementById('chkReducaoRenuncia').getAttribute('data-valor-antigo') == 'S'){
            recucaoRenunciaValorAntigo = true;
        }
        if(
            document.getElementById('chkReducaoRenuncia').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('chkReducaoRenuncia').checked != recucaoRenunciaValorAntigo
        ){
            mostrarBotaoRetificar = true;
        }

        if (
            document.getElementById('hdnVlCreditoNaoLancado').value != '0,00' &&
            document.getElementById('btnCancelarLancamento').style.display == 'none'
        ){
            mostrarBotaoRetificar = true;
        }

        //
        if(
            document.getElementById('txtDtApresentacaoRecurso').getAttribute('campo-mapea-param-entrada') == 'S' &&
            document.getElementById('txtDtApresentacaoRecurso').value != document.getElementById('txtDtApresentacaoRecurso').getAttribute('data-valor-antigo')
        ){
            mostrarBotaoRetificar = true;
        }

        if (mostrarBotaoRetificar && document.getElementById('selCreditosProcesso').value != '') {
            document.getElementById('btnRetificarLancamento').style.display = '';
        }else{
            document.getElementById('btnRetificarLancamento').style.display = 'none';
        }

    }

    function infraHabilitarCamposDivMulta(div) {
        infraHabilitarCamposDiv(div);
        e = 0;
        els = div.getElementsByTagName('img');
        while (el = els.item(e++)) {
            if (el.className.match(/imgAjudaCtrlProcLit/) == null) {
                el.style.display = '';
            }
        }
    }

    function infraDesabilitarCamposDivMulta(div) {
        infraDesabilitarCamposDiv(div);
        e = 0;
        els = div.getElementsByTagName('img');
        while (el = els.item(e++)) {
            if (el.className.match(/imgAjudaCtrlProcLit/) == null) {
                el.style.display = 'none';
            }
        }
    }

    function mostrarEsconderElemento(element, estilo) {
        if (element) {
            element.style.display = estilo;
        }
    }

    function calcularDecursoPrazoRecurso(idSituacao = null) {
        const dtDecisaoAplMulta = $('#hdnDtIntimacaoAplMulta').val();

        if (dtDecisaoAplMulta) {
            $.ajax({
                url: '<?= $strLinkAjaxCalcularDataDecurso ?>',
                type: 'POST',
                data: {
                    'dtDecisaoAplMulta': dtDecisaoAplMulta,
                    'idTpControle': '<?=$idTpControle?>',
                    'idProcedimento': '<?=$idProcedimento?>',
                    'idSituacao': idSituacao
                },
                async: false,
                success: function (result) {
                    //ATUALIZAR INPUT DATA DO DECUROS DO PRAZO E O HIDDEN
                    document.getElementById('hdnDtDecursoPrazoRecurso').value = $(result).find('hdnDtDecursoPrazoRecurso').text();

                    var $select = $('#selDtDecursoPrazoRecurso');
                    $select.empty();

                    var options = $(result).find('htmlOption').text();
                    options = options.replace(/ï¿½teis/g, 'úteis');

                    $select.append(options);
                    if ($select.find('option').length === 2) {
                        $select.find('option').eq(1).prop('selected', true);
                        $select.prop('disabled', true);
                    } else {
                        $select.prop('disabled', false);
                    }
                },
                error: function (e) {
                    if ($(e.responseText).find('MensagemValidacao').text()) {
                        //inicializarCamposPadroesProcesso();
                        alert($(e.responseText).find('MensagemValidacao').text());
                    }
                    console.error('Erro ao processar o XML do SEI: ' + e.responseText);
                }
            });
        }
    }

    function atualizarDataDecisaoDefinitiva() {
        $('#txtDtDecisaoDefinitiva').val('');
        $('#hdnDtDecisaoDefinitiva').val('');
        $.ajax({
            url: '<?= $strLinkAjaxAtualizarDataDecisaoDefinitiva ?>',
            type: 'POST',
            data: {
                'idProcessoSituacao': $('#selDocumento').val()
            },
            async: false,
            success: function (response) {
                $dataDocumento = $(response).find('resultado').text();
                $('#txtDtDecisaoDefinitiva').val($dataDocumento);
                $('#hdnDtDecisaoDefinitiva').val($dataDocumento);
                verificarMudancaMulta();
            },
            error: function (e) {
                if ($(e.responseText).find('MensagemValidacao').text()) {
                    //inicializarCamposPadroesProcesso();
                    alert($(e.responseText).find('MensagemValidacao').text());
                }
                console.error('Erro ao processar o XML do SEI: ' + e.responseText);
            }
        });
    }

    function habilitarDescontoDecorrente(){
        var tbSituacaoLinhas = $('#tbSituacao tr');

        for (var i = 1; i <= tbSituacaoLinhas.length; i++) {
            var SinRecursalSit = tbSituacaoLinhas.eq(i).find('td:eq(29)').text();
            if (SinRecursalSit == 'S') {
                return true;
            }
        }
        return false;
    }

    function atualizarHiddenDtDecursoPrazo(){
        document.getElementById('hdnDtDecursoPrazo').value = document.getElementById('selDtDecursoPrazo').value;

        var selectElement = document.getElementById("selDtDecursoPrazo");

        if (selectElement.selectedOptions.length > 0) {
            var selectedOption = selectElement.selectedOptions[0];
            document.getElementById('hdnPrazoDefesa').value = selectedOption.getAttribute('prazo-defesa')
            document.getElementById('hdnTpPrazoDefesa').value = selectedOption.getAttribute('tp-prazo-defesa')
        }
    }

    function atualizarHiddenDtDecursoPrazoRecurso(){
        document.getElementById('hdnDtDecursoPrazoRecurso').value = document.getElementById('selDtDecursoPrazoRecurso').value;

        var selectElement = document.getElementById("selDtDecursoPrazoRecurso");

        if (selectElement.selectedOptions.length > 0) {
            var selectedOption = selectElement.selectedOptions[0];
            document.getElementById('hdnPrazoRecurso').value = selectedOption.getAttribute('prazo-recurso')
            document.getElementById('hdnTpPrazoRecurso').value = selectedOption.getAttribute('tp-prazo-recurso')
        }

    }

</script>

