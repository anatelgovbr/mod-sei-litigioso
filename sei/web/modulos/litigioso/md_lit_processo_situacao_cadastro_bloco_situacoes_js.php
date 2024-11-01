<script type="text/javascript">

    var validarDataIntimacaoMulta = '';

    function validarDocumento(idDocAlterar) {
        if (isDadosFinalizadosAlt) {
            validarDocumentoAlterar();
        } else {
            validarDocumentoCadastro(idDocAlterar);
        }
    }

    function validarDocumentoAlterar() {
        var txtNumeroSei = document.getElementById('txtNumeroSei');

        if (txtNumeroSei.value != '') {
            var paramsAjax = {
                numeroSei: txtNumeroSei.value,
                idTipoControle: document.getElementById('hdnIdTipoControle').value,
                idProcedimento: document.getElementById('hdnIdProcedimento').value,
                idSituacao: document.getElementById('selSituacoes').value
            };

            $.ajax({
                url: '<?=$strUrlAjaxValidarSituacao?>',
                type: 'POST',
                dataType: 'XML',
                data: paramsAjax,
                success: function (r) {
                    if ($(r).find('Erro').text() == '1') {
                        alert($(r).find('Msg').text());
                        txtNumeroSei.value = '';
                    } else {
                        document.getElementById('txtNumeroSei').value = $(r).find('NumeroSei').text();
                        document.getElementById('txtDataDocumento').value = $(r).find('DtDocumento').text();
                        document.getElementById('hdnDtDocumento').value = $(r).find('DtDocumento').text();
                        document.getElementById('txtTipoDocumento').value = $(r).find('TipoDocumento').text();
                        document.getElementById('hdnIdSerieDocNumeroSei').value = $(r).find('IdSerieNumeroSei').text();
                        document.getElementById('hdnIdDocumentoNumeroSei').value = $(r).find('IdDocumentoNumeroSei').text();
                        document.getElementById('hdnUrlDocumento').value = $(r).find('UrlDocumento').text();
                        document.getElementById('hdnTituloDoc').value = $(r).find('TituloDoc').text();
                    }
                }
            })
        } else {
            alert('Preencha o Número SEI.');
            txtNumeroSei.focus();
        }
    }

    function validarDocumentoCadastro(idDocAlterar) {
        var txtNumeroSei = document.getElementById('txtNumeroSei');

        if (txtNumeroSei.value != '') {
            var paramsAjax = {
                idDocAlterar: idDocAlterar,
                numeroSei: txtNumeroSei.value,
                idTipoControle: document.getElementById('hdnIdTipoControle').value,
                idProcedimento: document.getElementById('hdnIdProcedimento').value
            };

            $.ajax({
                url: '<?=$strUrlAjaxNumeroSei?>',
                type: 'POST',
                dataType: 'XML',
                data: paramsAjax,
                success: function (r) {
                    if ($(r).find('Erro').text() == '1') {
                        alert($(r).find('Msg').text());
                        txtNumeroSei.value = '';
                    } else {
                        document.getElementById('txtNumeroSei').value = $(r).find('NumeroSei').text();
                        document.getElementById('txtDataDocumento').value = $(r).find('DtDocumento').text();
                        document.getElementById('hdnDtDocumento').value = $(r).find('DtDocumento').text();
                        document.getElementById('txtTipoDocumento').value = $(r).find('TipoDocumento').text();
                        document.getElementById('hdnIdSerieDocNumeroSei').value = $(r).find('IdSerieNumeroSei').text();
                        document.getElementById('hdnIdDocumentoNumeroSei').value = $(r).find('IdDocumentoNumeroSei').text();
                        document.getElementById('hdnUrlDocumento').value = $(r).find('UrlDocumento').text();
                        document.getElementById('hdnTituloDoc').value = $(r).find('TituloDoc').text();
                        manterComboFasesAtualizada();
                        if (idDocAlterar == '0') {
                            document.getElementById('txtDtTipoSituacao').value = $(r).find('DtDocumento').text();
                        }
                    }
                }
            })
        } else {
            alert('Preencha o Número SEI.');
            txtNumeroSei.focus();
        }
    }

    function limparNumeroSei() {
        document.getElementById("hdnIdDocumentoNumeroSei").value = '';
        document.getElementById("hdnIdSerieDocNumeroSei").value = '';
        document.getElementById('txtTipoDocumento').value = '';
        document.getElementById('txtDataDocumento').value = '';
    }

    function changeNumeroSei() {
        if (isDadosFinalizadosAlt) {
            limparNumeroSei();
        } else {
            limparCamposRelacionados();
        }
    }

    function enterValidarDocumento(e) {
        if (e && e.keyCode == 13) {
            validarDocumento('0');
            return false;
        }
    }

    function limparCamposRelacionados() {
        limparNumeroSei();
        limparFaseSituacao();
        limparCamposRelacionadosSituacao();
    }

    function valitarPermissaoAlteracao(arr) {
        var isValid = true;
        if (document.getElementById('selCreditosProcesso').disabled == true){
            var tipo_situacao = arr[17].trim().replace(/\(|\)/g, '');
            $.ajax({
                type: "POST",
                url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_ligacao_lancamento') ?>",
                dataType: "xml",
                async: false,
                data: {
                    id_procedimento: $('#hdnIdProcedimento').val(),
                    id_processo_situacao: arr[0],
                    tipo_situacao: tipo_situacao
                },
                success: function (result) {
                    var idLancamento = $(result).find('idLancamento').text();
                    if (idLancamento && document.getElementById('selCreditosProcesso').value != idLancamento) {
                        alert('Existem alterações no lançamento selecionado que impede alterações nessa situação. É necessário salvar as alterações atuais para depois alterar a situação desejada.');
                        isValid = false;
                    }
                },
                error: function (msgError) {
                    msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                }
            });
        }
        return !isValid;
    }

    function limparFaseSituacao() {
        var selFases = document.getElementById('selFases');
        selFases.value = '';
        document.getElementById('selSituacoes').innerHTML = "";
        manterComboFasesAtualizada();
    }

    function carregarDependenciaFasesSituacao() {
        //Ajax para carregar as situações de acordo com as Fases
        objAjaxFasesSit = new infraAjaxMontarSelectDependente('selFases', 'selSituacoes', '<?=$strLinkAjaxFasesSituacao?>');

        objAjaxFasesSit.prepararExecucao = function () {
            var isValidoDependencia = validarCamposObrigatoriosDependencia();
            if (isValidoDependencia) {
                limparCamposRelacionadosSituacao();
                return infraAjaxMontarPostPadraoSelect('null', '', 'null') + '&idSerie=' + document.getElementById('hdnIdSerieDocNumeroSei').value + '&idFase=' + document.getElementById('selFases').value;
            }

            return false;
        }

        objAjaxFasesSit.processarResultado = function () {
            var atr = document.createAttribute("class");
        }
    }

    function limparCamposRelacionadosSituacao() {
        document.getElementById("divCamposExtraJudicial").style.display = 'none';
        document.getElementById("divDataTipoSituacao").style.display = 'none';
// //        document.getElementById("divMainPrescricao").style.display = 'none';
        document.getElementById('divRdsPrescricao').style.display = 'none';
// //        document.getElementById("divsDatasBdPrescricao").style.display = 'none';
        document.getElementById('divsDatasPrescricao').style.display = 'none';
        document.getElementById("chkDpExtraJudicial").checked = false;
        document.getElementById("lblDtaTipoSituacao").innerHTML = '';
        document.getElementById("txtSituacao").style.color = '';
        document.getElementById("divBtnAdicionar").style.display = 'none';
        document.getElementById("btnAdicionar").disabled = false;
        document.getElementById("rdNaoAlteraPrescricao").checked = false;
        document.getElementById("rdAlteraPrescricao").checked = false;
        document.getElementById("txtSituacao").innerHTML = '';
        document.getElementById("divTxtSituacao").style.display = '';
        // document.getElementById('divDatasSelectPrescricao').style.display = 'none';

        limparEsconderCamposCheckedExtraJudicial();
    }

    function limparEsconderCamposCheckedExtraJudicial() {
        document.getElementById("txtValor").value = '';
        document.getElementById("divValor").style.display = 'none';
        document.getElementById("txtDtDeposito").value = '';
        document.getElementById("divDtDeposito").style.display = 'none';
        document.getElementById('divDataTipoSituacao').style.marginTop = '';

    }

    function changeSituacoes() {

        limparCamposRelacionadosSituacao();
        var idSituacao = document.getElementById('selSituacoes').value.split('|')[0];

        var paramsAjax = {
            idSituacao: idSituacao,
            idProcedimento: document.getElementById("hdnIdProcedimento").value,
            idTpControle: document.getElementById('hdnIdTipoControle').value
        };

        $.ajax({
            url: '<?=$strUrlAjaxChangeSituacao?>',
            type: 'POST',
            dataType: 'XML',
            data: paramsAjax,
            success: function (r) {
                //Get Dados Ajax
                var erro = $.trim($(r).find('Erro').text());
                var msgExibicao = $.trim($(r).find('MsgExibicao').text());
                var nomeLabel = $.trim($(r).find('NomeLabel').text());
                var tipoSituacao = $.trim($(r).find('TipoSituacao').text());
                var sinPrimeiraIntimacao = $.trim($(r).find('SinPrimeiraIntimacao').text());
                var sinConslusiva = $.trim($(r).find('Conclusiva').text());
                validarDataIntimacaoMulta = $.trim($(r).find('ValidarDataIntimacaoMulta').text());

                var lblDtaTipoSituacao = document.getElementById('lblDtaTipoSituacao');
                var divDataTipoSituacao = document.getElementById('divDataTipoSituacao');

                lblDtaTipoSituacao.innerHTML = nomeLabel;
                divDataTipoSituacao.style.display = '';

                isTpSitDecisoria = tipoSituacao == 'Decisoria' ? true : false;
                isTpSitLivre = tipoSituacao == 'Livre' ? true : false;

                if (isTpSitDecisoria) {
                    document.getElementById("txtDtTipoSituacao").disabled = true;
                }

                prepararCamposRecursal(tipoSituacao);
                if (erro == '1') {
//                    document.getElementById('divsDatasBdPrescricao').style.display = 'none';
                    document.getElementById('divsDatasPrescricao').style.display = 'none';
                    document.getElementById("divBtnAdicionar").style.display = 'none';
                    document.getElementById("btnAdicionar").disabled = true;
                    document.getElementById("txtSituacao").style.color = 'red';
                } else {
                    if (sinPrimeiraIntimacao == 'S') {
                        //se for a primeira intimação irá alterar a prescrição
                        document.getElementById('rdAlteraPrescricao').checked = true;
                        changePrescricao(document.getElementById('rdAlteraPrescricao'), false);
                        document.getElementById("divBtnAdicionar").style.display = '';
                        document.getElementById("btnAdicionar").disabled = false;
                    } else if ('<?= $idMdLitProcessoSituacaoPrimeiraIntimacao ?>' != '') {
                        document.getElementById('divsDatasPrescricao').style.display = '';
//                        document.getElementById('divsDatasBdPrescricao').style.display = '';
//                        document.getElementById("divMainPrescricao").style.display = '';
                        document.getElementById('divRdsPrescricao').style.display = '';
                        document.getElementById('rdNaoAlteraPrescricao').checked = true;
                        changePrescricao(document.getElementById('rdNaoAlteraPrescricao'), false);
                    }

                    document.getElementById('divBtnAdicionar').style.display = '';
                    document.getElementById("btnAdicionar").disabled = false;
                }

                document.getElementById('hdnStrSituacao').value = tipoSituacao;
                document.getElementById("hdnErroSituacao").value = erro;
                document.getElementById('txtSituacao').innerHTML = msgExibicao;
                document.getElementById('hdnSinConslusiva').value = sinConslusiva;
            }
        })
    }

    function prepararCamposRecursal(tipoSituacao) {
        if (tipoSituacao == 'Recursal') {
            isTpSitRecursal = true;
            document.getElementById('divDataTipoSituacao').style.marginTop = "6px";
            var divCamposExtraJudicial = document.getElementById("divCamposExtraJudicial");
            divCamposExtraJudicial.style.display = '';
        } else {
            isTpSitRecursal = false;
        }
    }

    function validarCamposObrigatoriosDependencia() {
        var valNumeroSei = document.getElementById('txtNumeroSei').value;
        var valTpDoc = document.getElementById('txtTipoDocumento').value;

        if (valNumeroSei == '') {
            alert('Preencha o Número SEI.');
            document.getElementById('selFases').value = '';
            return false;
        } else {
            if (valTpDoc == '') {
                alert('Valide o Número SEI');
                document.getElementById('selFases').value = '';
                return false;
            }
        }

        return true;
    }

    function manterComboFasesAtualizada() {
        var txtTipoDocumento = document.getElementById('txtTipoDocumento');
        var valorDisplay = txtTipoDocumento.value != '' ? '' : 'none';

        var elementos = document.getElementsByClassName('optSelFase');
        for (var i = 0; i < elementos.length; i++) {
            elementos[i].style.display = valorDisplay;
        }
    }

    function checkedDpExtraJudicial(el) {
        limparEsconderCamposCheckedExtraJudicial();
        document.getElementById('divDataTipoSituacao').style.marginTop = "6px";
        var isChecked = el.checked == true;
        if (isChecked) {
            document.getElementById("divValor").style.display = '';
            document.getElementById("divDtDeposito").style.display = '';
        }
    }

    function changePrescricao(el, chamadaAlt) {
        var objTxtInt = document.getElementById('txtDtIntercorrente');
        var objTxtQuinq = document.getElementById('txtDtQuinquenal');

        var hdnSinInst = isAlterarSit ? document.getElementById('hdnSinInstauracaoAlt').value : document.getElementById('hdnSinInstauracao').value;

        document.getElementById("divBtnAdicionar").style.display = '';
        document.getElementById("btnAdicionar").disabled = false;

        var vlRadio = el.value;

        if (!chamadaAlt) {
            var dtDefault = document.getElementById('hdnDtUltimaSituacao').value;
            objTxtInt.value = objTxtInt.value == '' ? dtDefault : objTxtInt.value;
            objTxtQuinq.value = objTxtQuinq.value == '' ? dtDefault : objTxtQuinq.value;
        }


        if (vlRadio == '1' && hdnSinInst != '0') {
            document.getElementById('divDatasSelectPrescricao').style.display = '';
        } else {
            document.getElementById('divDatasSelectPrescricao').style.display = 'none';
        }

    }

    function somarAnosData(el, anos) {

        var objDataAtual = new Date();
        objDataAtual.setFullYear(objDataAtual.getFullYear() + parseInt(anos));
        var strData = dataAtualFormatada(objDataAtual);
        var idCampo = el.getAttribute("campoRef");

        document.getElementById(idCampo).value = strData;
    }

    function dataAtualFormatada(objData) {
        var dia = objData.getDate();
        if (dia.toString().length == 1)
            dia = "0" + dia;
        var mes = objData.getMonth() + 1;
        if (mes.toString().length == 1)
            mes = "0" + mes;
        var ano = objData.getFullYear();
        return dia + "/" + mes + "/" + ano;
    }

    function validoCamposObrigatoriosSituacao() {

        // Só existe possibilidade desses fluxos na alteração
        var txtNumeroSei = $.trim(document.getElementById('txtNumeroSei').value);
        if (txtNumeroSei == '') {
            alert("O Número SEI é de preenchimento obrigatório.");
            return false;
        }


        var tipoDocumento = $.trim(document.getElementById('txtTipoDocumento').value);
        if (tipoDocumento == '') {
            alert("Para realizar a alteração é necessário validar o documento adicionado!");
            return false;
        }

        //Verificando a Data Base para Todas Situações
        var txtDtTipoSituacao = $.trim(document.getElementById('txtDtTipoSituacao').value);
        if (txtDtTipoSituacao == '') {
            var lblDinamica = document.getElementById("lblDtaTipoSituacao").innerHTML;
            var msgDinamica = 'A Data ' + lblDinamica + ' é de preenchimento obrigatório.';
            alert(msgDinamica);
            return false;
        }


        //Quando a situação é Recursal
        var isCheckedExJud = document.getElementById('chkDpExtraJudicial').checked;

        if (isCheckedExJud && isTpSitRecursal) {
            var txtValor = $.trim(document.getElementById("txtValor").value);
            var txtDtDeposito = $.trim(document.getElementById('txtDtDeposito').value);

            if (txtValor == '') {
                alert('O Valor do Depósito é de preenchimento obrigatório.');
                return false;
            }

            if (txtDtDeposito == '') {
                alert('A Data do Depósito é de preenchimento obrigatório.');
                return false;
            }
        }

        //Quando existe alteração de prescrição
        var isCheckedRdAltera = document.getElementById('rdAlteraPrescricao').checked;
        var dtsHide = document.getElementById('divDatasSelectPrescricao').style.display == 'none';

        if (isCheckedRdAltera && !dtsHide) {
            var txtIntercorrente = $.trim(document.getElementById('txtDtIntercorrente').value);
            var txtQuinquenal = $.trim(document.getElementById('txtDtQuinquenal').value);
            var hdnSinInst = document.getElementById('hdnSinInstauracao').value;

            if (txtIntercorrente == '' && txtQuinquenal == '' && hdnSinInst != '0') {
                alert('Para alterar a prescrição é necessário preencher pelo menos uma Data.');
                return false;
            }

            var hdnDtDefaultInt = document.getElementById('hdnDtPrimSitInter').value;
            var hdnDtDefaultQuin = document.getElementById('hdnDtPrimSitQuiq').value;
            var dtDefaultInt = retornarDate(hdnDtDefaultInt);
            var dtDefaultQuin = retornarDate(hdnDtDefaultQuin);

            //Verificando datas quando existe alteração de prescrição
            if (txtIntercorrente != '') {
                var dtInter = retornarDate(txtIntercorrente);
                if (dtInter < dtDefaultInt) {
                    alert("Data Intercorrente não pode ser menor que a Data da Primeira Intimação ou menor do histórico de Datas Intercorrentes.");
                    return false;
                }
            }

            if (txtQuinquenal != '') {
                var dtQuinq = retornarDate(txtQuinquenal);
                if (dtQuinq < dtDefaultQuin) {
                    alert("Data Quinquenal não pode ser menor que a Data da Primeira Intimação ou menor do histórico de Datas Quinquenais.");
                    return false;
                }
            }

            var houveMotivicacao = false;
            if (txtQuinquenal != '' && txtQuinquenal != document.getElementById('txtDtQuinquenal').getAttribute('data-valor-antigo')) {
                houveMotivicacao = true;
            } else if (txtIntercorrente != '' && txtIntercorrente != document.getElementById('txtDtIntercorrente').getAttribute('data-valor-antigo')) {
                houveMotivicacao = true;
            }
            if (!houveMotivicacao) {
                alert('As datas de alteração de prescrição Intercorrente e Quinquenal não podem ser iguais as últimas datas cadastradas.');
                return false;
            }
        }
        if (document.getElementById('hdnIdProcessoSituacao').value == '<?= $idMdLitProcessoSituacaoPrimeiraIntimacao ?>') {
            var addSituacao = confirm('Após os dados serem salvos, a alteração da Situação da intimação da instauração será recalculado as datas Intercorrente e Quinquenal baseado na data da intimação. Confirma a operação?');
            if (!addSituacao) {
                return false;
            }
        }

        if (document.getElementById('hdnSinSuspenso') && document.getElementById('hdnSinConslusiva')) {
            var hdnSinSuspenso = document.getElementById('hdnSinSuspenso').value;
            var hdnSinConclusiva = document.getElementById('hdnSinConslusiva').value;

            if(hdnSinSuspenso == 'S' && hdnSinConclusiva == 'S'){
                alert('A situação Formalização do Trânsito em Julgado Administrativo (Conclusiva) não pode ser incluída, pois está relacionada a uma Suspensão de Lançamento em Razão de Recurso. \n\nAntes de incluir esta Situação deve primeiro Cancelar ou Denegar o Recurso.');
                return false;
            }
        }

        return true;
    }

    function retornarDate(hdnDtDefault) {
        var arrDtEntrada = hdnDtDefault.split('/');
        var data = new Date();

        data.setDate(arrDtEntrada[0]);
        data.setMonth(arrDtEntrada[1] - 1);
        data.setFullYear(arrDtEntrada[2]);

        return data;

    }

    function addSituacao() {

        var tpInclusao = isAlterarSit && !isAlterarRegNovo ? 'A' : 'N';

        // NAO PERMITE QUE SEJA INCLUIDO UMA SITUACAO CASTO TENHA UMA PENDENCIA DE RETIFICACAO NA GESTAO DE MULTA
        if (!isAlterarRegNovo && tpInclusao == 'N' && document.getElementById('btnRetificarLancamento').style.display != 'none'){
            alert('Não é possível adicionar uma situação com pendências de retificação na gestão de multa.');
            return;
        }

        //Caso a seja a intimação posterior a decisao que aplicou a multa armazena o valor
        var paramsAjax = {
            idSituacao: document.getElementById('selSituacoes').value,
            idProcedimento: document.getElementById("hdnIdProcedimento").value,
            idTpControle: document.getElementById('hdnIdTipoControle').value
        };

        if (validoCamposObrigatoriosSituacao()) {

            //Init vars
            if (!isAlterarSit) {
                adicionouSit = true;
            }

            //Caso a seja a intimação posterior a decisao que aplicou a multa armazena o valor
            if (validarDataIntimacaoMulta == '1' && $('[name="txtDtTipoSituacao"]').val() != '') {
                //adiciona a data da intimação que aplicou multa (intimação apos a decisao) para uso na validacoes
                $('[name="hdnIntimacaoPosDecisao"]').val($('[name="txtDtTipoSituacao"]').val());
            } else {
                $('[name="hdnIntimacaoPosDecisao"]').val('');
            }

            var hdnStrIsGestor = document.getElementById('hdnIsGestor').value;
            var hdnIsExcluirSituacao = '<?= $isExcluirSituacao ? 1 : 0 ?>';

            var isRemoverTabela = hdnStrIsGestor == '1' && hdnIsExcluirSituacao == '1' ? false : true;

            //Set Campos Alteração
            var idAlteracao = document.getElementById('hdnIdProcessoSituacao').value;

            //Zerando Campo do Id do Alterar
            document.getElementById('hdnIdProcessoSituacao').value = '0';

            //Set Vars
            var hdnIdDocumento = document.getElementById('hdnIdDocumentoNumeroSei');

            //Pega o Id Certo (Existem dois sels, um pra alteração, outro pra inserção)
            var selFases = retornaSelFasesCorreto();
            var selSituacoes = document.getElementById("selSituacoes");
            var prazo = selSituacoes.value.split('|')[1];
            var dataAtual = infraDataAtual();
            var checkedAltPrecr = document.getElementById('rdAlteraPrescricao').checked;

            //Preenchendo valores do Recursal
            var vlTxtValor = isTpSitRecursal ? document.getElementById("txtValor").value : '';
            var vlDtDepos = isTpSitRecursal ? document.getElementById("txtDtDeposito").value : '';
            var chkDpExtraJudicial = isTpSitRecursal ? document.getElementById("chkDpExtraJudicial").checked ? 'S' : 'N' : '';

            //Preenchendo Valores das Datas para alterar prescrição ou não
            var txtIntercorr = checkedAltPrecr ? document.getElementById("txtDtIntercorrente").value : '';
            var txtQuinquenal = checkedAltPrecr ? document.getElementById("txtDtQuinquenal").value : '';

            //Variáveis Uteis
            var ordemMaior = objTabelaDinamicaSituacao.verificaOrdemMaior();
            var ordemAdd = parseInt(ordemMaior) + 1;
            var ordemAlterar = document.getElementById('hdnOrdemAtual').value != '' ? document.getElementById('hdnOrdemAtual').value : ordemAdd;
            var novaOrdem = isAlterarSit ? ordemAlterar : ordemAdd;
            var sinPrescricao = document.getElementById('rdAlteraPrescricao').checked ? '1' : '0';
            var idTabela = document.getElementById('hdnIdMainTabela').value;
            var hdnSinInst = isAlterarSit ? document.getElementById('hdnSinInstauracaoAlt').value : document.getElementById('hdnSinInstauracao').value;
            var linkDocumento = addLinkExibicaoDocumento(document.getElementById("txtNumeroSei").value);
            var addBranco;
            var nomeUsuario = document.getElementById('hdnNomeUsuario').value;

            // Preencher data de apresentação do Recurso
            var idSituacaoSelecionada = document.getElementById('selSituacoes').value;

            if("<?= $idLancamentoSelecionado ?>"){
                var idLancamento = "<?= $idLancamentoSelecionado ?>";

                // caso seja alteração
                var idProcessoSituacao = null;
                if (idAlteracao) {
                    idProcessoSituacao = idAlteracao;
                }

            }

            var arrLinha = [
                idTabela,
                tpInclusao,
                document.getElementById('hdnIdProcedimento').value,
                selFases.value,
                selSituacoes.value.split('|')[0],
                document.getElementById("hdnIdUsuario").value,
                document.getElementById("hdnIdUnidade").value,
                txtIntercorr,
                txtQuinquenal,
                addBranco,
                document.getElementById("txtTipoDocumento").value,
                document.getElementById("txtDtTipoSituacao").value,
                selFases.options[selFases.selectedIndex].text,
                selSituacoes.options[selSituacoes.selectedIndex].text,
                dataAtual,
                nomeUsuario,
                document.getElementById('hdnNomeUnidade').value,
                document.getElementById('hdnStrSituacao').value,
                vlTxtValor,
                vlDtDepos,
                idAlteracao,
                novaOrdem,
                sinPrescricao,
                document.getElementById('lblDtaTipoSituacao').innerHTML,
                hdnIdDocumento.value,
                hdnSinInst,
                document.getElementById("txtNumeroSei").value,
                document.getElementById("hdnUrlDocumento").value,
                chkDpExtraJudicial,
                prazo
            ];

            objTabelaDinamicaSituacao.recarregar();
            objTabelaDinamicaSituacao.adicionar(arrLinha);
            montarIconeRemover();
            //document.getElementById("linkDoc").innerHTML = linkDocumento;
            objTabelaDinamicaSituacao.adicionarAcoes(idTabela, "", false, isRemoverTabela);

            //Controlar a exibição do Sel Fases de Forma correta
            controlarExibicaoCorretaSelFases(false);

            document.getElementById('hdnVlDepExtraJud').value = vlTxtValor;
            document.getElementById('hdnDtDepExtraJud').value = vlDtDepos;
            document.getElementById("divTbSituacao").style.display = '';
            document.getElementById('tbSituacao').style.display = '';
            document.getElementById('txtNumeroSei').value = '';
            document.getElementById('divDatasSelectPrescricao').style.display = 'none';
            limparCamposRelacionados();
            bloquearTelaParaAdicao(true, false);

            //Se for alteração é necessário limpar os campos que ficam bloqueados nessa ação.
            if (isAlterarSit) {
                limparCombosSituacaoFasesPosAlteracao();
            }

            //Zerando label padrão do tipo de Situação
            document.getElementById('lblDtaTipoSituacao').innerHTML = '';

            //Zerar Vars de Controle
            isAlterarSit = false;
            isAlterarRegNovo = false;
            isDadosFinalizadosAlt = false;
            document.getElementById('hdnIdDocumentoAlterado').value = '';

            //Zerando o Hidden de Ordem, pois já foi utilizado nesta ação
            document.getElementById('hdnOrdemAtual').value = '';

            //Zerando o id principal pois o mesmo deve ficar sempre com 0 preparado para inserções
            // 0 para inserção e o id da tabela para alterações
            document.getElementById('hdnIdMainTabela').value = '0';

            //mostrando os fieldset de decisões e de gestão de multa quanto a situação for do tipo decisorio
            if (isTpSitDecisoria && document.getElementById('hdnErroSituacao').value == 0 && document.getElementById('hdnTbDecisao').value == '') {
                document.getElementById('fieldsetDecisao').style.display = '';
            }

            //Corrrigindo o problema do core do Sei que não aceita HTML para alteração (função remover XML)
            var row = objTabelaDinamicaSituacao.procuraLinha(idTabela);
            atualizarTodosLinks(row);
            document.getElementById('tbSituacao').rows[row].cells[9].innerHTML = '<div style="text-align:center;">' + linkDocumento + '</div>';
            document.getElementById('tbSituacao').rows[row].cells[15].innerHTML = '<div style="text-align:center;">' + nomeUsuario + '</div>';
            document.getElementById('tbSituacao').rows[row].cells[16].innerHTML = '<div style="text-align:center;">' + document.getElementById('hdnNomeUnidade').value + '</div>';

            if (document.getElementById('fieldsetMulta') != null) {
                if (!isTpSitDecisoria && document.getElementById('hdnErroSituacao').value == 0) {
                    document.getElementById('btnCancelarLancamento').style.display = 'none';
                }
                consultarExtratoMulta();
            }

            //Botão apresentado apenas se a última situação cadastrada for do tipo Recursal(suspender recurso no fieldset multa).
            if (adicionouSit && isTpSitRecursal && !document.getElementById('chkReducaoRenuncia').checked && document.getElementById('hdnSinSuspenso').value == 'N') {
                document.getElementById('btnSuspenderLancamento').style.display = '';
            }
            switch (tpInclusao){
                case 'A':
                    var tipoSituacao = arrLinha[17];
                    switch (tipoSituacao){
                        case 'Decisória':
                            verificarAlteracoesGestaoMultaDecisao(idTabela, document.getElementById("txtDtTipoSituacao").value);
                            break;
                        case 'Intimação':
                            verificarAlteracoesGestaoMultaIntimacao(idTabela, document.getElementById("txtDtTipoSituacao").value);
                            verificarAlteracoesGestaoMultaIntimacaoDataDecursoPrazoDefesa(arrLinha);
                            break;
                        case 'Recursal':
                            verificarAlteracoesGestaoMultaRecurso(idTabela, document.getElementById("txtDtTipoSituacao").value);
                            break;
                    }
                    break
                case 'N':
                    verificarAlteracoesGestaoMultaNovo(arrLinha);
                    verificarInclusaoRecursoData();
                    break
            }

            replicarDataParaFieldsetGestaoMulta();
            removerBotaoExcluirTela();
            adicionarExcluirGrid()
        }
    }

    function verificarInclusaoRecursoData() {
        if (document.getElementById('fieldsetMulta') != null) {
            if (isTpSitRecursal && document.getElementById('hdnErroSituacao').value == 0) {
                var idProcedimento = document.getElementById('hdnIdProcedimento').value;
                $.ajax({
                    type: "POST",
                    url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_lancamento_para_recurso') ?>",
                    dataType: "xml",
                    async: false,
                    data: {'idProcedimento': idProcedimento},
                    success: function (data) {
                        if ($(data).find('idLancamento').text()) {
                            document.getElementById('selCreditosProcesso').value = $(data).find('idLancamento').text();
                            document.getElementById('hdnCreditosProcesso').value = $(data).find('idLancamento').text();
                            consultarExtratoMulta();
                            document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                            document.getElementById('apresentacao-recurso').style.display = '';
                            document.getElementById('txtDtApresentacaoRecurso').value = document.getElementById('txtDtTipoSituacao').value;
                            document.getElementById('hdnDtApresentacaoRecurso').value = document.getElementById('txtDtTipoSituacao').value;
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
    }

    function replicarDataParaFieldsetGestaoMulta() {
        var isConclusiva = $.trim(document.getElementById('hdnStrSituacao').value) == 'Conclusiva';
        var isVisibleFieldMulta = false;
        if (document.getElementById('fieldsetMulta') != null) {
            isVisibleFieldMulta = document.getElementById('fieldsetMulta').style.display != "none";
        }

        if (isConclusiva && isVisibleFieldMulta) {
            var valorDt = document.getElementById('txtDtTipoSituacao').value;
            document.getElementById('txtDtConstituicao').value = valorDt;
            document.getElementById('hdnDtSituacaoConclusiva').value = valorDt;
        }

    }

    function atualizarTodosLinks(row) {
        var tbSituacaoLinhas = $('#tbSituacao tr');

        for (var i = 1; i <= tbSituacaoLinhas.length; i++) {
            if (i != row) {
                //Linhas em Jquery por necessidade.
                var url = tbSituacaoLinhas.eq(i).find('td:eq(27)').text();
                var strLink = "window.open('" + url + "')";
                var txtNumeroSei = tbSituacaoLinhas.eq(i).find('td:eq(26)').text();
                var tituloDocumento = tbSituacaoLinhas.eq(i).find('td:eq(10)').text();
                var html = '<div style="text-align:center;"><a title="' + tituloDocumento + '" style="" class="ancoraPadraoAzul" onclick ="' + strLink + '"> ' + txtNumeroSei + ' </a></div>';
                tbSituacaoLinhas.eq(i).find('td:eq(9)').html(html);
            } else {
                tbSituacaoLinhas.eq(i).removeClass('infraTrClara');
                tbSituacaoLinhas.eq(i).removeClass('infraTrEscura');
                tbSituacaoLinhas.eq(i).addClass('infraTrAcessada');
            }
        }
    }

    function addLinkExibicaoDocumento(txtNumeroSei) {
        var url = document.getElementById('hdnUrlDocumento').value

        var strLink = "window.open('" + url + "')";
        var tituloDocumento = document.getElementById('hdnTituloDoc').value;
        var html = '<a title="' + tituloDocumento + '" class="ancoraPadraoAzul" onclick ="' + strLink + '"> ' + txtNumeroSei + ' </a>';

        return html;
    }

    function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function retornaSelFasesCorreto() {
        var idFases = isAlterarSit ? 'selFasesAlteracao' : 'selFases';
        var selFases = document.getElementById(idFases);

        if ($.trim(selFases.value) == '') {
            var idFasesPreench = !isAlterarSit ? 'selFasesAlteracao' : 'selFases';
            selFases = document.getElementById(idFasesPreench);
        }

        return selFases;
    }

    function limparCombosSituacaoFasesPosAlteracao() {
        document.getElementById('selFases').disabled = false;
        document.getElementById('selSituacoes').innerHTML = '';
        document.getElementById('selSituacoes').disabled = false;
    }

    //Se existir Inserção permite o bloqueio da tela
    //Se não existir, não permite
    function permiteBloqueioTela(isAlterar) {
        if (isAlterar) {
            return true;
        }

        var existeInsercao = objTabelaDinamicaSituacao.verificaSeExisteInsercao();

        if (existeInsercao) {
            return true;
        }

        return false;
    }

    function bloquearTelaParaAdicao(bloquear, isAlterar) {

        //Se for alterar, desbloqueia a tela, se não verifica a existencia de alguma inserção
        // para bloquear os desbloquear a tela
        var isContinue = permiteBloqueioTela(isAlterar);

        if (isContinue) {
            var objs = document.getElementsByClassName('campoFieldsetSituacao');

            if (bloquear) {
                document.getElementById("divBtnAdicionar").style.display = 'none';
                document.getElementById("btnAdicionar").disabled = true;
            }

            for (var i = 0; i < objs.length; i++) {
                objs[i].disabled = bloquear;
            }
        }

    }

    function validarVinculoDecisao(idProcSituacao) {

        //verificar os lancamentos associados a situaca e não cancelados
        if (validarDependenciasDeLancamentosNaoCancelados(idProcSituacao) == false) {
            return false;
        }

        //verficiar as dependencias de decisões com a situacao
        if (validarDependenciasDeDecisoesPreenchidas(idProcSituacao) == false) {
            return false;
        }
        return true;
    }

    function validarDependenciasDeLancamentosNaoCancelados(idProcSituacao) {
        var valid = true;
        $.ajax({
            type: "POST",
            url: "<?= $strVerificarDependencias ?>",
            dataType: "xml",
            async: false,
            data: {
                id_procedimento: $('#hdnIdProcedimento').val(),
                id_processo_situacao: idProcSituacao
            },
            success: function (result) {
                var dtoResult = $(result).find('MdLitLancamentoDTO');
                var lancamentos = '';
                if (dtoResult.length > 0) {
                    $.each(dtoResult, function (key, value) {
                        var tipoLancamento = 'Principal';
                        if ($(value).find('TipoLancamento').text().trim() == 'M') {
                            tipoLancamento = 'Majorado'
                        }

                        lancamentos += 'Sequencial: ' + $(value).find('Sequencial').text() + '\n';
                        lancamentos += 'Tipo de lançamento: ' + tipoLancamento + '\n';
                        lancamentos += 'Valor do lançamento: ' + $(value).find('VlrLancamento').text() + '\n';
                        lancamentos += 'Vencimento: ' + $(value).find('Vencimento').text() + '\n';
                        lancamentos += 'Data da decisão: ' + $(value).find('Decisao').text() + '\n';
                        lancamentos += '\n';
                    });

                    alert('Antes de excluir a Situação de Decisão, é necessário efetivar o Cancelamento do(s) Lançamento(s).\n\n' +
                        lancamentos +
                        'Para efetivar o Cancelamento do Lançamento, em Gestão de Multa acione o botão Cancelar.');
                    valid = false;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                valid = false;
            }
        });

        return valid;
    }

    function validarDependenciasDeDecisoesPreenchidas(idProcSituacao) {
        var valid = true;
        $.ajax({
            type: "POST",
            url: "<?= $strVerificarDependenciasDecisoesPreenchidas ?>",
            dataType: "xml",
            async: false,
            data: {
                id_procedimento: $('#hdnIdProcedimento').val(),
                id_processo_situacao: idProcSituacao
            },
            success: function (result) {
                var boolResult = $(result).find('resultado').text();
                if (boolResult == 1) {
                    if (confirm('Ao realizar a exclusão da Situação de Decisão todas as decisões vinculadas a ela tambem serão excluídas.\n\n Deseja seguir com esta operação?')) {
                        valid = true;
                    } else {
                        valid = false;
                    }
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                valid = false;
            }
        });

        return valid;
    }

    function validarOrdemExclusao(ordemEx) {
        var ordemMaior = objTabelaDinamicaSituacao.verificaOrdemMaior();

        if (Number.isInteger(ordemEx) && ordemMaior != ordemEx) {
            alert('A exclusão das Situações devem obdecer as ordens parametrizadas na Administração.');
            return false;
        }

        return true;
    }

    function controlarTelaRemover() {
        if (registroBancoExcluido) {
            isAlterarRegNovo = false;
            isAlterarSit = false;
            isDadosFinalizadosAlt = false;
            controlarExibicaoCorretaSelFases(false);
            limparCamposRelacionados();
            document.getElementById("txtNumeroSei").value = '';
            document.getElementById("hdnIdDocumentoAlterado").value = '';
            bloquearTelaParaAdicao(true, true);
        } else {
            bloquearTelaParaAdicao(false, true);
        }
    }

    /**
     * deixa habilitado somente a opção de excluir para o primeiro item da lista de situações
     */
    function montarIconeRemover() {
        $('img[src="/infra_css/svg/remover.svg?<?= Icone::VERSAO ?>"]:gt(0)').remove()
    }

    function inicializarGridSituacao() {
        var hdnStrIsGestor = document.getElementById('hdnIsGestor').value;
        var hdnOpenProcesso = document.getElementById('hdnOpenProcesso').value;
        var hdnIsAlterarSituacao = '<?= $isAlterarSituacao ? 1 : 0 ?>';
        var hdnIsExcluirSituacao = '<?= $isExcluirSituacao ? 1 : 0 ?>';
        var alterar = hdnStrIsGestor == '1' && hdnOpenProcesso != '1' && hdnIsAlterarSituacao == '1' ? true : false;
        var excluir = hdnStrIsGestor == '1' && hdnOpenProcesso != '1' && hdnIsExcluirSituacao == '1' ? true : false;

        objTabelaDinamicaSituacao = new infraTabelaDinamica('tbSituacao', 'hdnTbSituacoes', alterar, excluir);
        objTabelaDinamicaSituacao.gerarEfeitoTabela = true;

        //tratamento para exibir o icone excluir
        montarIconeRemover();

        objTabelaDinamicaSituacao.remover = function (arr) {
            var possuiDecisao = document.getElementById('hdnProcessoSitIsDecisao').value == '1' ? true : false;
            var isRecursal = $('#hdnStrUltimaSituacao').val() == '(Recursal)' ? true : false;
            var strNomeUltimaSituacao = $('#hdnStrNomeUltimaSituacao').val();
            var existeSuspensao = document.getElementById('hdnExisteSuspensao').value;
            var tipoItemExcl = arr[1][0];
            isAlterarSit = false;
            isAlterarRegNovo = false;

            //Se o registro excluido for do banco ou de alteração então tudo deve ser bloqueado
            if (tipoItemExcl == 'B' || tipoItemExcl == 'A') {
                registroBancoExcluido = true;
            } else {
                registroBancoExcluido = false;
                adicionouSit = false;
            }

            var qtd = document.getElementById('tbSituacao').rows.length;

            //Se gestor
            if (hdnStrIsGestor == '1') {
                if (validarVinculoDecisao(arr[0]) && validarOrdemExclusao(arr[21])) {
                    controlarTelaRemover();
                    if (registroBancoExcluido) {
                        if(isRecursal && existeSuspensao == 'true' ){
                            alert('A situação ' + strNomeUltimaSituacao + ' não pode ser excluída, pois está relacionada a uma Suspensão de Lançamento em Razão de Recurso. \n\nAntes de excluir esta Situação deve primeiro Cancelar ou Denegar os Recursos existentes que tenham Suspendido Lançamento.');
                            return false;
                        } else {
                            alert('Após realizar a exclusão, é necessário salvar os dados para adicionar novos registros.');
                        }
                    } else {
                        //Se não for registro do banco excluído verificar se deve esconder os dados de decisão
                        if (!possuiDecisao) {
                            document.getElementById('fieldsetDecisao').style.display = 'none';
                            document.getElementById('fieldsetMulta').style.display = 'none';
                        }
                    }

                    if (qtd == 2) {
                        document.getElementById('tbSituacao').style.display = 'none';
                    }
                    return true;
                } else {
                    return false;
                }


            } else {
                //Se não for gestor
                if (qtd == 2) {
                    document.getElementById('tbSituacao').style.display = 'none';
                }

                if (!possuiDecisao) {
                    document.getElementById('fieldsetDecisao').style.display = 'none';
                    document.getElementById('fieldsetMulta').style.display = 'none';
                }

                controlarTelaRemover();
                bloquearTelaParaAdicao(false, false);
                return true;
            }

        }

        objTabelaDinamicaSituacao.verificarTabelaVazia = function () {
            var qtd = document.getElementById('tbSituacao').rows.length;

            if (qtd == 1) {
                return true;
            } else {
                return false;
            }
        }

        objTabelaDinamicaSituacao.verificaOrdemMaior = function () {
            var ordemLinha = 0;
            var tbTabelaDinamicaSit = document.getElementById('tbSituacao');
            for (var i = 1; i < tbTabelaDinamicaSit.rows.length; i++) {
                var ordemAtual = tbTabelaDinamicaSit.rows[i].cells[21].innerText.trim();
                if (parseInt(ordemLinha) < parseInt(ordemAtual)) {
                    ordemLinha = ordemAtual;
                }
            }

            return ordemLinha;
        };

        objTabelaDinamicaSituacao.verificaSeExisteInsercao = function () {

            var tbTabelaDinamicaSit = document.getElementById('tbSituacao');
            for (var i = 1; i < tbTabelaDinamicaSit.rows.length; i++) {
                var tipoAcao = tbTabelaDinamicaSit.rows[i].cells[1].innerText.trim();

                if (tipoAcao == 'N') {
                    return true;
                }
            }

            return false;
        };

        objTabelaDinamicaSituacao.procuraLinha = function (id) {
            var qtd;
            var linha;
            qtd = document.getElementById('tbSituacao').rows.length;

            for (i = 1; i < qtd; i++) {
                linha = document.getElementById('tbSituacao').rows[i];
                var valorLinha = linha.cells[0].innerText;
                if (id) {
                    id = $.trim(id);
                    if (valorLinha == id) {
                        return i;
                    }
                } else {
                    return i;
                }

            }
            return null;
        };

        objTabelaDinamicaSituacao.alterar = function (arr) {

            if (valitarPermissaoAlteracao(arr)){
                return;
            }

            limparCamposRelacionados();

            if (isAlterarSit) {
                isDadosFinalizadosAlt = false;
            }

            //Set Campo Main Alteração
            isAlterarSit = true;

            //Quando um registro novo sofrer alteração
            isAlterarRegNovo = arr[1] == 'N' ? true : false;

            //Setta o valor do Id da tabela
            document.getElementById('hdnIdMainTabela').value = arr[0];

            //Setta a situação que está sendo utilizada no Hidden para controle
            document.getElementById('hdnStrSituacao').value = arr[17];

            //Get Tipo Situação
            var tipoSituacao = verificaSituacao();

            document.getElementById('hdnStrSituacao').value = tipoSituacao;
            //Controlar Exibicao do Sel Fases (Existem dois, um pra alteração, outro pra inserção,
            // pois os dados são manipulados de formas diferentes
            controlarExibicaoCorretaSelFases(true);

            //Exibe o campo da data Principal e setta a label correta
            document.getElementById('divDataTipoSituacao').style.display = '';
            document.getElementById('lblDtaTipoSituacao').innerHTML = arr[23];

            //Setta os Campos Simples
            document.getElementById('hdnIdDocumentoNumeroSei').value = arr[24];
            document.getElementById('txtNumeroSei').value = arr[26];
            document.getElementById('txtTipoDocumento').value = arr[10];
            document.getElementById('txtDataDocumento').value = arr[11];
            document.getElementById('txtDataDocumento').value = arr[11];
            document.getElementById('hdnIdProcessoSituacao').value = arr[20];
            document.getElementById("hdnUrlDocumento").value = arr[26];

            // Valida o Documento e faz os processos iniciais
            validarDocumento(arr[24]);

            //Desbloqueia a tela
            bloquearTelaParaAdicao(false, true);

            // Preparar os selects que são bloqueados
            addOption(arr[3], arr[12], 'selFasesAlteracao');
            addOption(arr[4], arr[13], 'selSituacoes');

            //Setta se exibe ou não as datas da alteração de prescrição
            document.getElementById('hdnSinInstauracaoAlt').value = arr[25];

            //Realiza o controle de Prescrição
            if ('<?= $idMdLitProcessoSituacaoPrimeiraIntimacao ?>' != '' && document.getElementById('hdnIdProcessoSituacao').value > '<?= $idMdLitProcessoSituacaoPrimeiraIntimacao ?>') {
                controlarExibicaoPrescricaoAlterar(arr);
            }

            document.getElementById('divBtnAdicionar').style.display = '';
            document.getElementById("btnAdicionar").disabled = false;
            //Situação Recursal
            if (tipoSituacao == 'Recursal') {
                controlarExibicaoRecursalAlteracao(tipoSituacao, arr);
            }

            //Setta a data do documento salva anteriormente
            document.getElementById('txtDtTipoSituacao').value = arr[11];
            verificarBloqueioCampoDataDecisao();

            //Guarda o campo Ordem
            document.getElementById('hdnOrdemAtual').value = arr[20];

            //Dados prontos para alterar
            isDadosFinalizadosAlt = true;

            //Add documento
            document.getElementById('hdnIdDocumentoAlterado').value = arr[24];

        }

        objTabelaDinamicaSituacao.removerLinhaGrid = function () {
            var tbTabelaDinamicaSit = document.getElementById('tbSituacao');

            var tpInclusao = tbTabelaDinamicaSit.rows[1].cells[1].innerText;
            objTabelaDinamicaSituacao.removerLinha(1);

            if (tpInclusao == 'N') {
                adicionarExcluirGrid()
            }
        }

        document.getElementById('txtNumeroSei').focus();
        removerBotaoExcluirTela();
        adicionarExcluirGrid()

    }

    function verificarBloqueioCampoDataDecisao() {
        var paramsAjax = {
            idSituacao: document.getElementById('selSituacoes').value,
            idProcedimento: document.getElementById("hdnIdProcedimento").value,
            idTpControle: document.getElementById('hdnIdTipoControle').value
        };

        $.ajax({
            url: '<?=$strUrlAjaxChangeSituacao?>',
            type: 'POST',
            dataType: 'XML',
            data: paramsAjax,
            async: false,
            success: function (r) {
                var tipoSituacao = $.trim($(r).find('TipoSituacao').text());
                isTpSitDecisoria = tipoSituacao == 'Decisoria' ? true : false;
                if (isTpSitDecisoria) {
                    document.getElementById("txtDtTipoSituacao").disabled = true;
                }
            }
        })
    }

    function removerBotaoExcluirTela() {

        var hdnStrIsGestor = document.getElementById('hdnIsGestor').value;
        if (hdnStrIsGestor == '1' ? true : false) {
            var tbTabelaDinamicaSit = document.getElementById('tbSituacao');
            for (var i = 1; i < tbTabelaDinamicaSit.rows.length; i++) {
                var icone = tbTabelaDinamicaSit.rows[i].cells[31].querySelector("[title='Remover Item']");
                if(icone){
                    icone.parentNode.removeChild(icone);
                }
            }
        }
    }

    function adicionarExcluirGrid() {
        var hdnStrIsGestor = document.getElementById('hdnIsGestor').value;
        var hdnOpenProcesso = document.getElementById('hdnOpenProcesso').value;
        var hdnIsExcluirSituacao = '<?= $isExcluirSituacao ? 1 : 0 ?>';
        var excluir = hdnStrIsGestor == '1' && hdnOpenProcesso != '1' && hdnIsExcluirSituacao == '1' ? true : false;

        if (excluir) {
            var tbTabelaDinamicaSit = document.getElementById('tbSituacao');
            const image = document.createElement("img");
            image.src = "/infra_css/svg/remover.svg";
            image.title= "Remover Item";
            image.className= "infraImg";
            image.onclick = function() {
                var idSituacao = tbTabelaDinamicaSit.rows[1].cells[0].textContent;
                objTabelaDinamicaSituacao.removerLinhaGrid(1);
                acoesConjungadasBotaoExcluirSituacao(idSituacao);
            };

            tbTabelaDinamicaSit.rows[1].cells[31].appendChild(image);
        }
    }

    // ações que são acionadas quando é excluída uma situação
    function acoesConjungadasBotaoExcluirSituacao(idSituacao){

        //Busca um lancamento relacionado com a situacao de intimacao para excluir as datas na gestao de multa
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_alteracao_dt_intimacao_recurso') ?>",
            dataType: "xml",
            async: false,
            data: {'idSituacao': idSituacao},
            success: function (data) {
                if ($(data).find('idLancamento').text()) {
                    document.getElementById('hdnCreditosProcesso').value = $(data).find('idLancamento').text();
                    document.getElementById('selCreditosProcesso').value = $(data).find('idLancamento').text();
                    document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
                    document.getElementById('txtDtIntimacaoAplMulta').value = '';
                    document.getElementById('hdnDtIntimacaoAplMulta').value = '';
                    document.getElementById('selDtDecursoPrazoRecurso').value = '';
                    document.getElementById('hdnDtDecursoPrazoRecurso').value = '';

                    //Bloquear e remover opções do campo Data do Decurso do Prazo para Recurso
                    var $select = $('#selDtDecursoPrazoRecurso');
                    $select.empty();
                    $select.prop('disabled', true);

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

        //Busca um lancamento relacionado com a apresentação de recuro
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_alteracao_dt_recurso') ?>",
            dataType: "xml",
            async: false,
            data: {'idSituacao': idSituacao},
            success: function (data) {
                if ($(data).find('idLancamento').text()) {
                    document.getElementById('hdnCreditosProcesso').value = $(data).find('idLancamento').text();
                    document.getElementById('selCreditosProcesso').value = $(data).find('idLancamento').text();
                    document.getElementById('txtDtApresentacaoRecurso').value = '';
                    document.getElementById('hdnDtApresentacaoRecurso').value = '';
                    document.getElementById('apresentacao-recurso').style.display = 'none';
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

        if (<?= $ultimaSituacaoConclusiva?>) {
            document.getElementById('hdnCreditosProcesso').value = document.getElementById('selCreditosProcesso').value;
            document.getElementById('selCreditosProcesso').setAttribute('disabled', 'disabled');
            document.getElementById('chkHouveConstituicao').checked  = false;
            document.getElementById('chkReducaoRenuncia').checked  = false;
            document.getElementById('chkHouveConstituicao').value = 'N';
            document.getElementById('selDocumento').value = '';
            document.getElementById('txtDtDecisaoDefinitiva').value = '';
            document.getElementById('hdnDtDecisaoDefinitiva').value = '';
            document.getElementById('txtDtConstituicao').value = '';
            document.getElementById('txtDtIntimacaoConstituicao').value = '';

            //desaparecer todos os campos do houve constituição
            var elements = document.getElementsByClassName('tem-constituicao');
            var iLen = elements.length;
            while (iLen > 0) {
                elements[iLen - 1].className = elements[iLen - 1].className.replace('tem-constituicao', 'nao-tem-constituicao');
                elements = document.getElementsByClassName('tem-constituicao');
                iLen = elements.length;
            }
            document.getElementById('divtxtDtConstituicao').style.display = 'none';
            verificarMudancaMulta();
        }

    }

    function controlarExibicaoCorretaSelFases(isAlteracao) {
        if (isAlteracao) {
            document.getElementById('divSelFasesInicial').style.display = 'none';
            document.getElementById('divSelFasesAlteracao').style.display = '';
        } else {
            document.getElementById('divSelFasesInicial').style.display = '';
            document.getElementById('divSelFasesAlteracao').style.display = 'none';
        }
    }

    function controlarExibicaoPrescricaoAlterar(arr) {
        var txtIntercorrente = $.trim(arr[7]);
        var txtQuinquenal = $.trim(arr[8]);
        var alteraPrescr = arr[22] == '1' ? true : false;
        var idCampo = '';
        var isInstauracao = arr[25];

        if (alteraPrescr) {
            document.getElementById('rdAlteraPrescricao').checked = true;
            idCampo = 'rdAlteraPrescricao';
        } else {
            document.getElementById('rdNaoAlteraPrescricao').checked = true;
            idCampo = 'rdNaoAlteraPrescricao';
        }

        var campo = document.getElementById(idCampo);

//        document.getElementById('divsDatasBdPrescricao').style.display = '';
//        document.getElementById("divMainPrescricao").style.display = '';
        document.getElementById('divRdsPrescricao').style.display = '';
        document.getElementById("hdnErroSituacao").value = '0';
        document.getElementById('divsDatasPrescricao').style.display = '';

        if (alteraPrescr && isInstauracao == '1') {
            document.getElementById('txtDtIntercorrente').value = txtIntercorrente;
            document.getElementById('txtDtQuinquenal').value = txtQuinquenal;
        } else {
            document.getElementById('txtDtIntercorrente').value = '';
            document.getElementById('txtDtQuinquenal').value = '';
        }

        changePrescricao(campo, true);
    }

    function controlarExibicaoRecursalAlteracao(tipoSituacao, arr) {
        prepararCamposRecursal(tipoSituacao);

        var txtValor = arr[18];
        var dtDeposi = arr[19];

        if (txtValor != '' && dtDeposi != '') {
            document.getElementById('chkDpExtraJudicial').checked = true;
            checkedDpExtraJudicial(document.getElementById('chkDpExtraJudicial'));
            document.getElementById('txtValor').value = txtValor;
            document.getElementById('txtDtDeposito').value = dtDeposi;
        }
    }

    function verificaSituacao() {
        var hdnTipoSituacao = document.getElementById('hdnStrSituacao').value;
        var tipoSituacaoForm = $.trim(hdnTipoSituacao.replace(/[()]/g, ' '));

        return tipoSituacaoForm;
    }

    function addOption(id, nome, idSelected) {
        var htmlOption = '<option value="' + id + '"> ' + nome + '</option>';
        document.getElementById(idSelected).innerHTML = '';
        document.getElementById(idSelected).innerHTML = htmlOption;
        document.getElementById(idSelected).disabled = true;
    }

    function abrirModalDtIntercorrente() {
        infraAbrirJanelaModal('<?=$strLinkModalDtIntercorrente?>', 900, 400, 'location=0,status=1,resizable=1,scrollbars=1');
    }

    function abrirModalDtQuinquenal() {
        infraAbrirJanelaModal('<?=$strLinkModalDtQuinquenal?>', 900, 400, 'location=0,status=1,resizable=1,scrollbars=1');
    }

    function verificarCondicionaisSituacao() {
        var tabelaVazia = objTabelaDinamicaSituacao.verificarTabelaVazia();

        if (isTpSitDecisoria && document.getElementById('hdnErroSituacao').value == 0 && document.getElementById('hdnTbDecisao').value == '' && adicionouSit) {
            alert('É obrigatório adicionar ao menos uma decisão.');
            return false;
        }

        return true;
    }

    function isUltimaSituacaoDecisoria() {
        var hdnSituacaoIsDecisao = document.getElementById("hdnSituacaoIsDecisao").value;
        var arrObjTabelaSituacao = objTabelaDinamicaSituacao.obterItens();
        var decisoria = false;

        if (isTpSitDecisoria && document.getElementById('hdnErroSituacao').value == 0) {
            return true;
        }

        if (hdnSituacaoIsDecisao != '') {

            var ret = JSON.parse(hdnSituacaoIsDecisao);
            var idUltimaSituacao = arrObjTabelaSituacao[objTabelaDinamicaSituacao.obterItens().length - 1][0];

            for (i in ret) {
                if (ret[i] == idUltimaSituacao) {
                    decisoria = true;
                    break;
                }
            }
        }
        return decisoria;
    }

    function isExisteSituacaoConclusiva() {
        var hdnSituacaoIsDecisao = document.getElementById("hdnSituacaoIsDecisao").value;
        var arrObjTabelaSituacao = objTabelaDinamicaSituacao.obterItens();
        var bolConclusiva = false;

        if (hdnSituacaoIsDecisao != '') {

            var idSituacaoParametrizada = '<?php echo $idSituacaoConclusivaParametrizada ?>';
            for (var i = 0; i < arrObjTabelaSituacao.length; i++) {
                //o id_md_lit_situacao na parametrização é conclusiva
                if (idSituacaoParametrizada == arrObjTabelaSituacao[i][4]) {
                    bolConclusiva = true;
                }
            }
        }
        return bolConclusiva;
    }

</script>

