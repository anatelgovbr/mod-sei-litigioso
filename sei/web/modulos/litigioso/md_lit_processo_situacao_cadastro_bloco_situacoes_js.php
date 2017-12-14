<script type="text/javascript">

    function validarDocumento(idDocAlterar){
        if(isDadosFinalizadosAlt){
           validarDocumentoAlterar();
        }else{
            validarDocumentoCadastro(idDocAlterar);
        }
    }

    function validarDocumentoAlterar(){
        var txtNumeroSei = document.getElementById('txtNumeroSei');

        if (txtNumeroSei.value != '') {
            var paramsAjax = {
                numeroSei: txtNumeroSei.value,
                idTipoControle: document.getElementById('hdnIdTipoControle').value,
                idProcedimento: document.getElementById('hdnIdProcedimento').value,
                idSituacao    : document.getElementById('selSituacoes').value
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
                        document.getElementById('txtNumeroSei').value            = $(r).find('NumeroSei').text();
                        document.getElementById('txtDataDocumento').value        = $(r).find('DtDocumento').text();
                        document.getElementById('hdnDtDocumento').value          = $(r).find('DtDocumento').text();
                        document.getElementById('txtTipoDocumento').value        = $(r).find('TipoDocumento').text();
                        document.getElementById('hdnIdSerieDocNumeroSei').value  = $(r).find('IdSerieNumeroSei').text();
                        document.getElementById('hdnIdDocumentoNumeroSei').value = $(r).find('IdDocumentoNumeroSei').text();
                        document.getElementById('hdnUrlDocumento').value         = $(r).find('UrlDocumento').text();
                        document.getElementById('hdnTituloDoc').value            = $(r).find('TituloDoc').text();
                    }
                }
            })
        } else {
            alert('Preencha o N�mero SEI.');
            txtNumeroSei.focus();
        }
    }

    function validarDocumentoCadastro(idDocAlterar) {
        var txtNumeroSei = document.getElementById('txtNumeroSei');

        if (txtNumeroSei.value != '') {
            var paramsAjax = {
                idDocAlterar : idDocAlterar,
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
                        document.getElementById('txtDataDocumento').value        = $(r).find('DtDocumento').text();
                        document.getElementById('hdnDtDocumento').value          = $(r).find('DtDocumento').text();
                        document.getElementById('txtTipoDocumento').value        = $(r).find('TipoDocumento').text();
                        document.getElementById('hdnIdSerieDocNumeroSei').value  = $(r).find('IdSerieNumeroSei').text();
                        document.getElementById('hdnIdDocumentoNumeroSei').value = $(r).find('IdDocumentoNumeroSei').text();
                        document.getElementById('hdnUrlDocumento').value         = $(r).find('UrlDocumento').text();
                        document.getElementById('hdnTituloDoc').value            = $(r).find('TituloDoc').text();
                        manterComboFasesAtualizada();
                        if(idDocAlterar == '0'){
                            document.getElementById('txtDtTipoSituacao').value = $(r).find('DtDocumento').text();
                        }
                    }
                }
            })
        } else {
            alert('Preencha o N�mero SEI.');
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
        if(e && e.keyCode == 13) {
            validarDocumento('0');
            return false;
        }
    }

    function limparCamposRelacionados(){
        limparNumeroSei();
        limparFaseSituacao();
        limparCamposRelacionadosSituacao();
    }

    function limparFaseSituacao(){
        var selFases = document.getElementById('selFases');
        selFases.value = '';
        document.getElementById('selSituacoes').innerHTML = "";
        manterComboFasesAtualizada();
    }

    function carregarDependenciaFasesSituacao() {
        //Ajax para carregar as situa��es de acordo com as Fases
        objAjaxFasesSit = new infraAjaxMontarSelectDependente('selFases', 'selSituacoes', '<?=$strLinkAjaxFasesSituacao?>');

        objAjaxFasesSit.prepararExecucao = function () {
            var isValidoDependencia = validarCamposObrigatoriosDependencia();
            if (isValidoDependencia) {
                limparCamposRelacionadosSituacao();
                return infraAjaxMontarPostPadraoSelect('null', '', 'null') + '&idSerie='+ document.getElementById('hdnIdSerieDocNumeroSei').value +'&idFase=' + document.getElementById('selFases').value;
            }

            return false;
        }

        objAjaxFasesSit.processarResultado = function(){
            var atr = document.createAttribute("class");
        }
    }

    function limparCamposRelacionadosSituacao(){
        document.getElementById("divCamposExtraJudicial").style.display = 'none';
        document.getElementById("divDataTipoSituacao").style.display = 'none';
        document.getElementById("divMainPrescricao").style.display = 'none';
        document.getElementById("divsDatasBdPrescricao").style.display = 'none';
        document.getElementById('divsDatasPrescricao').style.display = 'none';
        document.getElementById("chkDpExtraJudicial").checked = false;
        document.getElementById("lblDtaTipoSituacao").innerHTML = '';
        document.getElementById("txtSituacao").style.color = '';
        document.getElementById("divBtnAdicionar").style.display = 'none';
        document.getElementById("rdNaoAlteraPrescricao").checked = false;
        document.getElementById("rdAlteraPrescricao").checked = false;
        document.getElementById("txtSituacao").innerHTML = '';
        document.getElementById("divTxtSituacao").style.display = '';
        document.getElementById('divDatasSelectPrescricao').style.display = 'none';

        limparEsconderCamposCheckedExtraJudicial();
    }

    function limparEsconderCamposCheckedExtraJudicial(){
        document.getElementById("txtValor").value = '';
        document.getElementById("divValor").style.display = 'none';
        document.getElementById("txtDtDeposito").value = '';
        document.getElementById("divDtDeposito").style.display = 'none';
        document.getElementById('divDataTipoSituacao').style.marginTop = '';

    }

    function changeSituacoes() {
        limparCamposRelacionadosSituacao();
        var selSituacoes = document.getElementById('selSituacoes');

        var paramsAjax = {
            idSituacao: selSituacoes.value,
            idProcedimento : document.getElementById("hdnIdProcedimento").value,
            idTpControle   : document.getElementById('hdnIdTipoControle').value
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
                
                var lblDtaTipoSituacao = document.getElementById('lblDtaTipoSituacao');
                var divDataTipoSituacao = document.getElementById('divDataTipoSituacao');

                lblDtaTipoSituacao.innerHTML = nomeLabel;
                divDataTipoSituacao.style.display = '';

                isTpSitDecisoria = tipoSituacao == 'Decisoria' ? true : false;
                isTpSitLivre     = tipoSituacao == 'Livre' ? true : false;

                prepararCamposRecursal(tipoSituacao);

                if (erro == '1') {
                    document.getElementById('divsDatasBdPrescricao').style.display = 'none';
                    document.getElementById('divsDatasPrescricao').style.display = 'none';
                    document.getElementById("divBtnAdicionar").style.display = 'none';
                    document.getElementById("txtSituacao").style.color = 'red';
                } else {
                    if(sinPrimeiraIntimacao == 'S'){
                        //se for a primeira intima��o ir� alterar a prescri��o
                        document.getElementById('rdAlteraPrescricao').checked = true;
                        changePrescricao(document.getElementById('rdAlteraPrescricao'), false);
                        document.getElementById("divBtnAdicionar").style.display = '';
                    }else{
                        document.getElementById('divsDatasPrescricao').style.display = '';
                        document.getElementById("divMainPrescricao").style.display = '';
                    }
                }

                document.getElementById('hdnStrSituacao').value = tipoSituacao;
                document.getElementById("hdnErroSituacao").value = erro;
                document.getElementById('txtSituacao').innerHTML = msgExibicao;
            }
        })
    }

    function prepararCamposRecursal(tipoSituacao){
        if (tipoSituacao == 'Recursal') {
            isTpSitRecursal = true;
            document.getElementById('divDataTipoSituacao').style.marginTop = "6px";
            var divCamposExtraJudicial = document.getElementById("divCamposExtraJudicial");
            divCamposExtraJudicial.style.display = '';
        }else{
            isTpSitRecursal = false;
        }
    }

    function validarCamposObrigatoriosDependencia(){
        var valNumeroSei = document.getElementById('txtNumeroSei').value;
        var valTpDoc     = document.getElementById('txtTipoDocumento').value;

        if(valNumeroSei == ''){
            alert('Preencha o N�mero SEI.');
            document.getElementById('selFases').value = '';
            return false;
        }else{
            if(valTpDoc == ''){
                alert('Valide o N�mero SEI');
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

    function checkedDpExtraJudicial(el){
        limparEsconderCamposCheckedExtraJudicial();
        document.getElementById('divDataTipoSituacao').style.marginTop = "6px";
        var isChecked = el.checked == true;
        if(isChecked){
            document.getElementById("divValor").style.display = '';
            document.getElementById("divDtDeposito").style.display = '';
        }
    }


    function changePrescricao(el, chamadaAlt) {
        var objTxtInt   = document.getElementById('txtDtIntercorrente');
        var objTxtQuinq = document.getElementById('txtDtQuinquenal');

        var hdnSinInst = isAlterarSit ? document.getElementById('hdnSinInstauracaoAlt').value : document.getElementById('hdnSinInstauracao').value;

        document.getElementById("divBtnAdicionar").style.display = '';

        var vlRadio = el.value;

        if(!chamadaAlt){
            var dtDefault     = document.getElementById('hdnDtUltimaSituacao').value;
            objTxtInt.value   = dtDefault;
            objTxtQuinq.value = dtDefault;
        }


        if (vlRadio == '1' && hdnSinInst != '0') {
            document.getElementById('divDatasSelectPrescricao').style.display = '';
            document.getElementById('divsDatasBdPrescricao').style.display = '';
        } else {
            document.getElementById('divDatasSelectPrescricao').style.display = 'none';
            document.getElementById('divsDatasBdPrescricao').style.display = 'none';
        }

    }

    function somarAnosData(el, anos){

        var objDataAtual = new Date();
        objDataAtual.setFullYear(objDataAtual.getFullYear() + parseInt(anos));
        var strData = dataAtualFormatada(objDataAtual);
        var idCampo = el.getAttribute("campoRef");

        document.getElementById(idCampo).value = strData;
    }

    function dataAtualFormatada(objData){
        var dia = objData.getDate();
        if (dia.toString().length == 1)
            dia = "0"+dia;
        var mes = objData.getMonth()+1;
        if (mes.toString().length == 1)
            mes = "0"+mes;
        var ano = objData.getFullYear();
        return dia+"/"+mes+"/"+ano;
    }

    function validoCamposObrigatoriosSituacao(){

        // S� existe possibilidade desses fluxos na altera��o
        var txtNumeroSei = $.trim(document.getElementById('txtNumeroSei').value);
        if(txtNumeroSei == ''){
            alert("O N�mero SEI � de preenchimento obrigat�rio.");
            return false;
        }


        var tipoDocumento = $.trim(document.getElementById('txtTipoDocumento').value);
        if(tipoDocumento == ''){
            alert("Para realizar a altera��o � necess�rio validar o documento adicionado!");
            return false;
        }

        //Verificando a Data Base para Todas Situa��es
        var txtDtTipoSituacao = $.trim(document.getElementById('txtDtTipoSituacao').value);
        if(txtDtTipoSituacao == ''){
            var lblDinamica = document.getElementById("lblDtaTipoSituacao").innerHTML;
            var msgDinamica = 'A Data ' + lblDinamica + ' � de preenchimento obrigat�rio.';
            alert(msgDinamica);
            return false;
        }


        //Quando a situa��o � Recursal
        var isCheckedExJud = document.getElementById('chkDpExtraJudicial').checked;

        if(isCheckedExJud && isTpSitRecursal){
            var txtValor = $.trim(document.getElementById("txtValor").value);
            var txtDtDeposito = $.trim(document.getElementById('txtDtDeposito').value);

            if(txtValor == ''){
                alert('O Valor do Dep�sito � de preenchimento obrigat�rio.');
                return false;
            }

            if(txtDtDeposito == ''){
                alert('A Data do Dep�sito � de preenchimento obrigat�rio.');
                return false;
            }
        }

        //Quando existe altera��o de prescri��o
        var isCheckedRdAltera = document.getElementById('rdAlteraPrescricao').checked;
        var dtsHide           = document.getElementById('divDatasSelectPrescricao').style.display == 'none';

        if(isCheckedRdAltera && !dtsHide){
            var txtIntercorrente = $.trim(document.getElementById('txtDtIntercorrente').value);
            var txtQuinquenal    = $.trim(document.getElementById('txtDtQuinquenal').value);
            var hdnSinInst       = document.getElementById('hdnSinInstauracao').value;

            if(txtIntercorrente == '' && txtQuinquenal == '' && hdnSinInst != '0'){
                alert('Para alterar a prescri��o � necess�rio preencher pelo menos uma Data.');
                return false;
            }

            var hdnDtDefaultInt  = document.getElementById('hdnDtPrimSitInter').value;
            var hdnDtDefaultQuin = document.getElementById('hdnDtPrimSitQuiq').value;
            var dtDefaultInt     = retornarDate(hdnDtDefaultInt);
            var dtDefaultQuin    = retornarDate(hdnDtDefaultQuin);

            //Verificando datas quando existe altera��o de prescri��o
            if (txtIntercorrente != '')
            {
                var dtInter = retornarDate(txtIntercorrente);
                if (dtInter < dtDefaultInt) {
                    alert("Data Intercorrente n�o pode ser menor que a Data da Primeira Intima��o ou menor do hist�rico de Datas Intercorrentes.");
                    return false;
                }
            }

            if (txtQuinquenal != '')
            {
                var dtQuinq = retornarDate(txtQuinquenal);
                if (dtQuinq < dtDefaultQuin) {
                    alert("Data Quinquenal n�o pode ser menor que a Data da Primeira Intima��o ou menor do hist�rico de Datas Quinquenais.");
                    return false;
                }
            }
        }



        return true;
    }

    function retornarDate(hdnDtDefault){
        var arrDtEntrada = hdnDtDefault.split('/');
        var data = new Date();

        data.setDate(arrDtEntrada[0]);
        data.setMonth(arrDtEntrada[1] - 1);
        data.setFullYear(arrDtEntrada[2]);

        return data;

    }


    function addSituacao() {

        var tpInclusao = isAlterarSit && !isAlterarRegNovo ? 'A' : 'N';

        if (validoCamposObrigatoriosSituacao()) {

            //Init vars
            if (!isAlterarSit) {
                adicionouSit = true;
            }

            var hdnStrIsGestor  = document.getElementById('hdnIsGestor').value;
            var isRemoverTabela = hdnStrIsGestor == '1' ? false : true;

            //Set Campos Altera��o
            var idAlteracao = document.getElementById('hdnIdProcessoSituacao').value;

            //Zerando Campo do Id do Alterar
            document.getElementById('hdnIdProcessoSituacao').value = '0';

            //Set Vars
            var hdnIdDocumento  = document.getElementById('hdnIdDocumentoNumeroSei');

            //Pega o Id Certo (Existem dois sels, um pra altera��o, outro pra inser��o)
            var selFases         = retornaSelFasesCorreto();
            var selSituacoes    = document.getElementById("selSituacoes");
            var dataAtual        = infraDataAtual();
            var checkedAltPrecr  =  document.getElementById('rdAlteraPrescricao').checked;

            //Preenchendo valores do Recursal
            var vlTxtValor    = isTpSitRecursal  ? document.getElementById("txtValor").value : '';
            var vlDtDepos     = isTpSitRecursal  ? document.getElementById("txtDtDeposito").value : '';

            //Preenchendo Valores das Datas para alterar prescri��o ou n�o
            var txtIntercorr  =  checkedAltPrecr ? document.getElementById("txtDtIntercorrente").value : '';
            var txtQuinquenal =  checkedAltPrecr ? document.getElementById("txtDtQuinquenal").value : '';

            //Vari�veis Uteis
            var ordemMaior    = objTabelaDinamicaSituacao.verificaOrdemMaior();
            var ordemAdd      = parseInt(ordemMaior)  + 1;
            var ordemAlterar  = document.getElementById('hdnOrdemAtual').value != '' ? document.getElementById('hdnOrdemAtual').value : ordemAdd;
            var novaOrdem     = isAlterarSit  ? ordemAlterar : ordemAdd;
            var sinPrescricao = document.getElementById('rdAlteraPrescricao').checked ? '1' : '0';
            var idTabela      = document.getElementById('hdnIdMainTabela').value;
            var hdnSinInst    = isAlterarSit ? document.getElementById('hdnSinInstauracaoAlt').value : document.getElementById('hdnSinInstauracao').value;
            var linkDocumento = addLinkExibicaoDocumento(document.getElementById("txtNumeroSei").value);
            var addBranco;

            var arrLinha = [
                idTabela,
                tpInclusao,
                document.getElementById('hdnIdProcedimento').value,
                selFases.value,
                selSituacoes.value,
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
                document.getElementById('hdnNomeUsuario').value,
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
                document.getElementById("hdnUrlDocumento").value
            ];

            objTabelaDinamicaSituacao.recarregar();
            objTabelaDinamicaSituacao.adicionar(arrLinha);
            //document.getElementById("linkDoc").innerHTML = linkDocumento;
            objTabelaDinamicaSituacao.adicionarAcoes(idTabela, "", false, isRemoverTabela);

            //Controlar a exibi��o do Sel Fases de Forma correta
            controlarExibicaoCorretaSelFases(false);

            document.getElementById('hdnVlDepExtraJud').value = vlTxtValor;
            document.getElementById('hdnDtDepExtraJud').value = vlDtDepos;
            document.getElementById("divTbSituacao").style.display = '';
            document.getElementById('tbSituacao').style.display = '';
            document.getElementById('txtNumeroSei').value = '';
            document.getElementById('divDatasSelectPrescricao').style.display = 'none';
            limparCamposRelacionados();
            bloquearTelaParaAdicao(true, false);

            //Se for altera��o � necess�rio limpar os campos que ficam bloqueados nessa a��o.
            if(isAlterarSit){
                limparCombosSituacaoFasesPosAlteracao();
            }

            //Zerando label padr�o do tipo de Situa��o
            document.getElementById('lblDtaTipoSituacao').innerHTML = '';

            //Zerar Vars de Controle
            isAlterarSit          = false;
            isAlterarRegNovo      = false;
            isDadosFinalizadosAlt = false;
            document.getElementById('hdnIdDocumentoAlterado').value = '';

            //Zerando o Hidden de Ordem, pois j� foi utilizado nesta a��o
            document.getElementById('hdnOrdemAtual').value = '';

            //Zerando o id principal pois o mesmo deve ficar sempre com 0 preparado para inser��es
            // 0 para inser��o e o id da tabela para altera��es
            document.getElementById('hdnIdMainTabela').value = '0';

            //mostrando os fieldset de decis�es e de gest�o de multa quanto a situa��o for do tipo decisorio
            if(isTpSitDecisoria && document.getElementById('hdnErroSituacao').value == 0 && document.getElementById('hdnTbDecisao').value == ''){
                document.getElementById('fieldsetDecisao').style.display = '';
                document.getElementById('fieldsetMulta').style.display = '';
            }

            //Corrrigindo o problema do core do Sei que n�o aceita HTML para altera��o (fun��o remover XML)
           var row = objTabelaDinamicaSituacao.procuraLinha(idTabela);
           atualizarTodosLinks(row);
           document.getElementById('tbSituacao').rows[row].cells[9].innerHTML = '<div style="text-align:center;">' + linkDocumento + '</div>';

            //Bot�o apresentado apenas se a �ltima situa��o cadastrada for do tipo Recursal(suspender recurso no fieldset multa).
            if(adicionouSit && isTpSitRecursal && !document.getElementById('chkReducaoRenuncia').checked){
                document.getElementById('btnSuspenderLancamento').style.display = '';
            }
        }
    }

    function atualizarTodosLinks(row) {
        var tbSituacaoLinhas = $('#tbSituacao tr');

        for (var i = 1; i <= tbSituacaoLinhas.length; i++) {
            if (i != row) {
                //Linhas em Jquery por necessidade.
                var url             = tbSituacaoLinhas.eq(i).find('td:eq(27)').text();
                var strLink         = "window.open('" + url + "')";
                var txtNumeroSei    = tbSituacaoLinhas.eq(i).find('td:eq(26)').text();
                var tituloDocumento = tbSituacaoLinhas.eq(i).find('td:eq(10)').text();
                var html            = '<div style="text-align:center;"><a title="' + tituloDocumento + '" style="font-size:12.4px" class="ancoraPadraoAzul" onclick ="' + strLink + '"> ' + txtNumeroSei + ' </a></div>';
                tbSituacaoLinhas.eq(i).find('td:eq(9)').html(html);
            }else{
                tbSituacaoLinhas.eq(i).removeClass('infraTrClara');
                tbSituacaoLinhas.eq(i).removeClass('infraTrEscura');
                tbSituacaoLinhas.eq(i).addClass('infraTrAcessada');
            }
        }
    }

    function addLinkExibicaoDocumento(txtNumeroSei){
        var url     = document.getElementById('hdnUrlDocumento').value

        var strLink = "window.open('" + url + "')";
        var tituloDocumento = document.getElementById('hdnTituloDoc').value;
        var html = '<a title="'+ tituloDocumento +'" style="font-size:12.4px" class="ancoraPadraoAzul" onclick ="' + strLink + '"> ' + txtNumeroSei + ' </a>';

        return html;
    }

    function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function retornaSelFasesCorreto(){
        var idFases    = isAlterarSit ? 'selFasesAlteracao' : 'selFases';
        var selFases   = document.getElementById(idFases);

        if($.trim(selFases.value) == ''){
            var idFasesPreench = !isAlterarSit ? 'selFasesAlteracao' : 'selFases';
            selFases   = document.getElementById(idFasesPreench);
        }

        return selFases;
    }

    function limparCombosSituacaoFasesPosAlteracao(){
        document.getElementById('selFases').disabled = false;
        document.getElementById('selSituacoes').innerHTML = '';
        document.getElementById('selSituacoes').disabled = false;
    }

    //Se existir Inser��o permite o bloqueio da tela
    //Se n�o existir, n�o permite
    function permiteBloqueioTela(isAlterar){
        if(isAlterar){
            return true;
        }

        var existeInsercao = objTabelaDinamicaSituacao.verificaSeExisteInsercao();

        if(existeInsercao){
            return true;
        }

       return false;
    }

    function bloquearTelaParaAdicao(bloquear, isAlterar) {

        //Se for alterar, desbloqueia a tela, se n�o verifica a existencia de alguma inser��o
        // para bloquear os desbloquear a tela
        var isContinue = permiteBloqueioTela(isAlterar);

        if (isContinue) {
            var objs = document.getElementsByClassName('campoFieldsetSituacao');

            if (bloquear) {
                document.getElementById("divBtnAdicionar").style.display = 'none';
            }

            for (var i = 0; i < objs.length; i++) {
                objs[i].disabled = bloquear;
            }
        }

    }
    
    function validarVinculoDecisao(idProcSituacao){
        var hdnSituacaoIsDecisao = document.getElementById("hdnSituacaoIsDecisao").value;

        if(hdnSituacaoIsDecisao != ''){

        var ret = JSON.parse(hdnSituacaoIsDecisao);

        for (i in ret) {
          if(ret[i] == idProcSituacao){
              alert('A exclus�o da situa��o n�o � permitida, pois existem registros vinculados.');
              return false;
          }
        }
        }
        return true;
    }

    function validarOrdemExclusao(ordemEx){
        var ordemMaior = objTabelaDinamicaSituacao.verificaOrdemMaior();

       if(ordemMaior != ordemEx){
           alert('A exclus�o das Situa��es devem obdecer as ordens parametrizadas na Administra��o.');
           return false;
       }

        return true;
    }

    function controlarTelaRemover(){
        if(registroBancoExcluido){
            isAlterarRegNovo = false;
            isAlterarSit = false;
            isDadosFinalizadosAlt = false;
            controlarExibicaoCorretaSelFases(false);
            limparCamposRelacionados();
            document.getElementById("txtNumeroSei").value = '';
            document.getElementById("hdnIdDocumentoAlterado").value = '';
            bloquearTelaParaAdicao(true, true);
        }else{
            bloquearTelaParaAdicao(false, true);
        }
    }

    function inicializarGridSituacao(){
        var hdnStrIsGestor  = document.getElementById('hdnIsGestor').value;
        var hdnOpenProcesso = document.getElementById('hdnOpenProcesso').value;
        var alterar         = hdnStrIsGestor == '1' && hdnOpenProcesso != '1' ? true : false;

        objTabelaDinamicaSituacao = new infraTabelaDinamica('tbSituacao', 'hdnTbSituacoes', alterar, alterar);
        objTabelaDinamicaSituacao.gerarEfeitoTabela = true;

        objTabelaDinamicaSituacao.remover = function (arr) {
            var possuiDecisao = document.getElementById('hdnProcessoSitIsDecisao') == '1' ? true : false;
            var tipoItemExcl  = arr[1][0];
            isAlterarSit      = false;
            isAlterarRegNovo  = false;

        //Se o registro excluido for do banco ou de altera��o ent�o tudo deve ser bloqueado
            if(tipoItemExcl == 'B' || tipoItemExcl == 'A'){
                 registroBancoExcluido = true;
            }else{
                registroBancoExcluido = false;
                adicionouSit = false;
            }

            var qtd = document.getElementById('tbSituacao').rows.length;

            //Se gestor
            if (hdnStrIsGestor == '1')
            {
                if(validarVinculoDecisao(arr[0]) && validarOrdemExclusao(arr[21]))
                {
                    controlarTelaRemover();
                    if(registroBancoExcluido){
                            alert('Ap�s realizar a exclus�o, � necess�rio salvar os dados para adicionar novos registros.');
                    }else{
                        //Se n�o for registro do banco exclu�do verificar se deve esconder os dados de decis�o
                        if(!possuiDecisao){
                            document.getElementById('fieldsetDecisao').style.display = 'none';
                            document.getElementById('fieldsetMulta').style.display = 'none';
                        }
                    }

                    if (qtd == 2) {
                        document.getElementById('tbSituacao').style.display = 'none';
                    }
                    return true;
                }else{
                    return false;
                }


            } else
            {
                //Se n�o for gestor
                if (qtd == 2) {
                    document.getElementById('tbSituacao').style.display = 'none';
                }

                if(!possuiDecisao){
                    document.getElementById('fieldsetDecisao').style.display = 'none';
                    document.getElementById('fieldsetMulta').style.display = 'none';
                }
                
                controlarTelaRemover();
                bloquearTelaParaAdicao(false, false);
                return true;
            }
        }

        objTabelaDinamicaSituacao.verificarTabelaVazia = function (){
            var qtd = document.getElementById('tbSituacao').rows.length;

            if (qtd == 1) {
                return true;
            }else{
                return false;
            }
        }

        objTabelaDinamicaSituacao.verificaOrdemMaior = function () {
            var ordemLinha  = 0;
            var tbTabelaDinamicaSit = document.getElementById('tbSituacao');
            for (var i = 1; i < tbTabelaDinamicaSit.rows.length; i++) {
               var ordemAtual = tbTabelaDinamicaSit.rows[i].cells[21].innerText.trim();
                if(ordemLinha < ordemAtual){
                    ordemLinha = ordemAtual;
                }
            }

            return ordemLinha;
        };

        objTabelaDinamicaSituacao.verificaSeExisteInsercao = function () {

            var tbTabelaDinamicaSit = document.getElementById('tbSituacao');
            for (var i = 1; i < tbTabelaDinamicaSit.rows.length; i++) {
                var tipoAcao = tbTabelaDinamicaSit.rows[i].cells[1].innerText.trim();

                if(tipoAcao == 'N'){
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


        objTabelaDinamicaSituacao.alterar = function(arr){

            limparCamposRelacionados();

            if(isAlterarSit){
                isDadosFinalizadosAlt = false;
            }
            
            //Set Campo Main Altera��o
            isAlterarSit = true;

            //Quando um registro novo sofrer altera��o
            isAlterarRegNovo = arr[1] == 'N' ? true : false;
            
            //Setta o valor do Id da tabela
            document.getElementById('hdnIdMainTabela').value = arr[0];

            //Setta a situa��o que est� sendo utilizada no Hidden para controle
            document.getElementById('hdnStrSituacao').value = arr[17];

            //Get Tipo Situa��o
            var tipoSituacao = verificaSituacao();

            //Controlar Exibicao do Sel Fases (Existem dois, um pra altera��o, outro pra inser��o,
            // pois os dados s�o manipulados de formas diferentes
            controlarExibicaoCorretaSelFases(true);

            //Exibe o campo da data Principal e setta a label correta
            document.getElementById('divDataTipoSituacao').style.display = '';
            document.getElementById('lblDtaTipoSituacao').innerHTML = arr[23];

            //Setta os Campos Simples
            document.getElementById('hdnIdDocumentoNumeroSei').value = arr[24];
            document.getElementById('txtNumeroSei').value            = arr[26];
            document.getElementById('txtTipoDocumento').value        = arr[10];
            document.getElementById('txtDataDocumento').value        = arr[11];
            document.getElementById('txtDataDocumento').value        = arr[11];
            document.getElementById('hdnIdProcessoSituacao').value   = arr[20];
            document.getElementById("hdnUrlDocumento").value         = arr[26];

            // Valida o Documento e faz os processos iniciais
            validarDocumento(arr[24]);

            //Desbloqueia a tela
            bloquearTelaParaAdicao(false, true);

            // Preparar os selects que s�o bloqueados
            addOption(arr[3], arr[12], 'selFasesAlteracao');
            addOption(arr[4], arr[13], 'selSituacoes');

            //Setta se exibe ou n�o as datas da altera��o de prescri��o
            document.getElementById('hdnSinInstauracaoAlt').value = arr[25];

            //Realiza o controle de Prescri��o
            controlarExibicaoPrescricaoAlterar(arr);

            //Situa��o Recursal
            if(tipoSituacao == 'Recursal'){
                controlarExibicaoRecursalAlteracao(tipoSituacao, arr);
            }

            //Setta a data do documento salva anteriormente
            document.getElementById('txtDtTipoSituacao').value = arr[11];

            //Guarda o campo Ordem
            document.getElementById('hdnOrdemAtual').value = arr[20];

            //Dados prontos para alterar
            isDadosFinalizadosAlt = true;

            //Add documento
            document.getElementById('hdnIdDocumentoAlterado').value = arr[24];

        }

    }

    function controlarExibicaoCorretaSelFases(isAlteracao){
        if(isAlteracao){
            document.getElementById('divSelFasesInicial').style.display = 'none';
            document.getElementById('divSelFasesAlteracao').style.display = '';
        }else{
            document.getElementById('divSelFasesInicial').style.display = '';
            document.getElementById('divSelFasesAlteracao').style.display = 'none';
        }
    }

    function controlarExibicaoPrescricaoAlterar(arr){
        var txtIntercorrente = $.trim(arr[7]);
        var txtQuinquenal    = $.trim(arr[8]);
        var alteraPrescr     = arr[22] == '1' ? true : false;
        var idCampo          = '';
        var isInstauracao     = arr[25];

        if(alteraPrescr){
            document.getElementById('rdAlteraPrescricao').checked = true;
            idCampo = 'rdAlteraPrescricao';
        }else{
            document.getElementById('rdNaoAlteraPrescricao').checked = true;
            idCampo = 'rdNaoAlteraPrescricao';
        }

        var campo = document.getElementById(idCampo);

        document.getElementById('divsDatasBdPrescricao').style.display = '';
        document.getElementById("divMainPrescricao").style.display = '';
        document.getElementById("hdnErroSituacao").value = '0';
        document.getElementById('divsDatasPrescricao').style.display = '';

       if(alteraPrescr && isInstauracao == '1'){
            document.getElementById('txtDtIntercorrente').value = txtIntercorrente;
            document.getElementById('txtDtQuinquenal').value = txtQuinquenal;
       }else{
           document.getElementById('txtDtIntercorrente').value = '';
           document.getElementById('txtDtQuinquenal').value = '';
       }

        changePrescricao(campo, true);
    }

    function controlarExibicaoRecursalAlteracao(tipoSituacao, arr){
        prepararCamposRecursal(tipoSituacao);

        var txtValor = arr[18];
        var dtDeposi = arr[19];

        if(txtValor != '' && dtDeposi != ''){
            document.getElementById('chkDpExtraJudicial').checked =  true;
            checkedDpExtraJudicial(document.getElementById('chkDpExtraJudicial'));
            document.getElementById('txtValor').value = txtValor;
            document.getElementById('txtDtDeposito').value = dtDeposi;
        }
    }

    function verificaSituacao(){
        var hdnTipoSituacao = document.getElementById('hdnStrSituacao').value;
        var tipoSituacaoForm = $.trim(hdnTipoSituacao.replace(/[()]/g, ' '));

        return tipoSituacaoForm;
    }

    function addOption(id, nome, idSelected){
        var htmlOption = '<option value="'+id+'"> '+nome+'</option>';
        document.getElementById(idSelected).innerHTML = '';
        document.getElementById(idSelected).innerHTML = htmlOption;
        document.getElementById(idSelected).disabled = true;
    }


    function abrirModalDtIntercorrente(){
        infraAbrirJanela('<?=$strLinkModalDtIntercorrente?>','janelaDtIntercorrente',900,400,'location=0,status=1,resizable=1,scrollbars=1');
    }

    function abrirModalDtQuinquenal(){
        infraAbrirJanela('<?=$strLinkModalDtQuinquenal?>','janelaDtIntercorrente',900,400,'location=0,status=1,resizable=1,scrollbars=1');
    }

    function verificarCondicionaisSituacao(){
        var tabelaVazia = objTabelaDinamicaSituacao.verificarTabelaVazia();

        if(tabelaVazia){
            alert('� obrigat�rio vincular ao menos uma situa��o.');
            return false;
        }

        if(isTpSitDecisoria && document.getElementById('hdnErroSituacao').value == 0 && document.getElementById('hdnTbDecisao').value == '' && adicionouSit){
            alert('� obrigat�rio adicionar ao menos uma decis�o.');
            return false;
        }

        return true;
    }



</script>
