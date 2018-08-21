
<script type="text/javascript">

    var INCLUIR_DEBITO  = 1;
    var RETIFICAR_DEBITO = 2;
    var SUSPENDER_LANCAMENTO = 3;
    var CANCELAR_LANCAMENTO = 3;
    var objAjaxNumeroInteressado;

    function carregarDependenciaMulta(){

        objAjaxNumeroInteressado = new infraAjaxMontarSelectDependente('selInteressado', 'selNumeroInteressado', '<?=$strLinkComboNumeroInteressado?>');

        objAjaxNumeroInteressado.prepararExecucao = function(){
            document.getElementById('selNumeroInteressado').innerHTML = "";
            document.getElementById('div-numero').style.display = '';
            return 'id_interessado='+document.getElementById('selInteressado').value+'&id_procedimento='+document.getElementById('hdnIdProcedimento').value;
        };
        objAjaxNumeroInteressado.processarResultado = function(itCnt){
            if(document.getElementById('selNumeroInteressado').getAttribute('data-id-dado-interessado') != ''){
                infraSelectSelecionarItem('selNumeroInteressado', document.getElementById('selNumeroInteressado').getAttribute('data-id-dado-interessado'));
            }
            if(document.getElementById('selNumeroInteressado').options.length == 2){
                document.getElementById('selNumeroInteressado').options[1].selected = true;
            }
        };
        consultarExtratoMulta();

        if(document.getElementById('selInteressado').options.length == 2){
            document.getElementById('selInteressado').options[1].selected = true;
        }
        if(document.getElementById('selInteressado').value != 'null'){
            window.setTimeout(function(){objAjaxNumeroInteressado.executar()}, 1000);
        }

        houveConstituicao(document.getElementById('chkHouveConstituicao'));

    }

    function selecionarPorNome(select,valor){
        var sel = document.getElementById(select);
        for (var i=0; i<sel.options.length; i++) {
            if ( sel.options[i].text != '' && sel.options[i].text.indexOf(valor)) {
                sel.options[i].selected = true;
                return;
            }
        }
    }

    function houveConstituicao(element){

        if(element.checked){
            //aparecer todos os campos do houve constitui��o
            var elements = document.getElementsByClassName('nao-tem-constituicao');
            var iLen = elements.length;
            while (iLen > 0) {
                elements[iLen - 1].className = elements[iLen - 1].className.replace('nao-tem-constituicao', 'tem-constituicao');
                elements = document.getElementsByClassName('nao-tem-constituicao');
                iLen = elements.length;
            }
        }else{
            //aparecer todos os campos do houve constitui��o
            var elements = document.getElementsByClassName('tem-constituicao');
            var iLen = elements.length;
            while (iLen > 0) {
                elements[iLen - 1].className = elements[iLen - 1].className.replace('tem-constituicao', 'nao-tem-constituicao');
                elements = document.getElementsByClassName('tem-constituicao');
                iLen = elements.length;
            }
        }
        mostraBotaoContituirDefinitivamente();
    }

    function consultarExtratoMulta(){
        var valueDecisao = objTabelaDinamicaDecisao.hdn.value;
        document.getElementById('btnRetificarLancamento').style.display = 'none';
        document.getElementById('btnIncluirLancamento').style.display = 'none';
        document.getElementById('btnCancelarLancamento').style.display = 'none';
        document.getElementById('btnSuspenderLancamento').style.display = 'none';
        document.getElementById('btnDenegarRecurso').style.display = 'none';
        document.getElementById('btnCancelarRecurso').style.display = 'none';
        document.getElementById('divHouveConstituicaoChk').style.display = '';
        document.getElementById('btnConstituirDefinitivamente').style.display = '';

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxConsultarExtratoMulta ?>",
//            dataType: "xml",
            data: {
                valor_decisao: valueDecisao,
                id_procedimento: document.getElementById('hdnIdProcedimento').value,
                id_md_lit_lancamento: document.getElementById('selCreditosProcesso').value
            },
            beforeSend: function(){
                infraExibirAviso(false);
            },
            success: function (result) {

                if($(result).find('erro').length > 0){
                    alert($(result).find('erro').attr('descricao'));
                    return;
                }

                if($(result).find('dados').length > 0){
                    document.getElementById('lblVlTotalMulta').innerHTML        = "R$ "+$(result).find('multaAplicada').text();
                    document.getElementById('lblVlCreditoNaoLancado').innerHTML = "R$ "+$(result).find('creditoNaoLancado').text();
                    document.getElementById('lblVlCreditoLancado').innerHTML    = "R$ "+$(result).find('creditoLancado').text();
                    document.getElementById('lblVlDesconto').innerHTML          = "R$ "+$(result).find('desconto').text();
                    document.getElementById('lblVlArrecadado').innerHTML        = "R$ "+$(result).find('arrecadado').text();
                    document.getElementById('lblVlDtUltimoPag').innerHTML       = $(result).find('ultimoPagamento').text();
                    document.getElementById('lblVlSaldoDevAtualizado').innerHTML = "R$ "+$(result).find('devedorAtualizado').text();
                    document.getElementById('lblVlCredConstDef').innerHTML      = "R$ "+$(result).find('constituidoDefinitivamente').text();

                    document.getElementById('hdnVlTotalMulta').value        = $(result).find('multaAplicada').text();
                    document.getElementById('hdnVlCreditoNaoLancado').value = $(result).find('creditoNaoLancado').text();
                    document.getElementById('hdnVlCreditoLancado').value    = $(result).find('creditoLancado').text();
                    document.getElementById('hdnVlDesconto').value          = $(result).find('desconto').text();
                    document.getElementById('hdnVlArrecadado').value        = $(result).find('arrecadado').text();
                    document.getElementById('hdnVlDtUltimoPagamento').value  = $(result).find('ultimoPagamento').text();
                    document.getElementById('hdnVlSaldoDevAtualizado').value = $(result).find('devedorAtualizado').text();
                    document.getElementById('hdnVlCredConstituidoDef').value = $(result).find('constituidoDefinitivamente').text();

                    //datas
                    document.getElementById('txtDecisaoAplicacaoMulta').value = $(result).find('dtDecisaoAplicacaoMulta').text();
                    document.getElementById('txtDtIntimacaoAplMulta').value = $(result).find('dtIntimacaoDecisaoAplicacaoMulta').text();
                    document.getElementById('txtDtVencimento').value = $(result).find('dtVencimento').text();
                    document.getElementById('txtDtConstituicao').value = $(result).find('dtConstituicao').text();
                    document.getElementById('txtDtIntimacaoConstituicao').value = $(result).find('dtIntimacaoConstituicao').text();
                    document.getElementById('txtDtIntimacaoAplMulta').setAttribute('data-valor-antigo', $(result).find('dtIntimacaoDecisaoAplicacaoMulta').text());
                    document.getElementById('txtDtVencimento').setAttribute('data-valor-antigo', $(result).find('dtVencimento').text());
                    document.getElementById('txtDtConstituicao').setAttribute('data-valor-antigo', $(result).find('dtConstituicao').text());
                    document.getElementById('txtDtIntimacaoConstituicao').setAttribute('data-valor-antigo', $(result).find('dtIntimacaoConstituicao').text());
                    document.getElementById('txtDecisaoAplicacaoMulta').setAttribute('data-valor-antigo', $(result).find('dtDecisaoAplicacaoMulta').text());

                    //bot�o historico
                    document.getElementById('btnHistoricoLancamento').setAttribute('data-url', $(result).find('urlHistoricoLancamento').text());

                    var creditoNaoLancado   = $(result).find('creditoNaoLancado').text().replace('.','').replace(',', '.');
                    var totalMultaAplicado  = $(result).find('multaAplicada').text().replace('.','').replace(',', '.');

                    if($(result).find('creditoNaoLancado').text() != '0,00' && $(result).find('isNovoLancamento').text() == 'S' && document.getElementById('selCreditosProcesso').value == ''){


                        document.getElementById('btnIncluirLancamento').style.display = parseFloat(creditoNaoLancado) > 0 ? '': 'none';
                    }else if(creditoNaoLancado > 0 && $(result).find('isNovoLancamento').text() == 'S'){
                        //Novo Boleto da Diferen�a a Maior (combo de cr�dito � parte, com datas pr�prias)
                        if(!optionExiste('',document.getElementById('selCreditosProcesso')))
                            infraSelectAdicionarOption(document.getElementById('selCreditosProcesso'), 'Majorado (R$ '+$(result).find('creditoNaoLancado').text() +')', '');

                        infraSelectSelecionarItem(document.getElementById('selCreditosProcesso'), '');

                        //recarregar o fieldset para atualizar os valores da multa
                        window.setTimeout(function(){consultarExtratoMulta()}, 500);
                        return;
                    }else if(creditoNaoLancado < 0 && $(result).find('isNovoLancamento').text() == 'S' && $(result).find('creditoLancado').text() != '0,00'){
                        document.getElementById('btnRetificarLancamento').style.display = '';

                    }

                    document.getElementById('selCreditosProcesso').setAttribute('sin-existe-majorado', $(result).find('sinExisteMajorado').text());

                    //Se n�o existe Cr�dito Lan�ado
                    if($(result).find('creditoLancado').text() != '0,00' && parseFloat(creditoNaoLancado) < 0 && parseFloat(totalMultaAplicado) <= 0 && $(result).find('isCancelar').text() == '0' && document.getElementById('hdnSinSuspenso').value == 'N'){
                        document.getElementById('btnCancelarLancamento').style.display = '';
                    }

                    //a multa n�o pode ser retificado se ela for zerado
                    if(parseFloat(totalMultaAplicado) <= 0){
                        document.getElementById('btnRetificarLancamento').style.display = 'none';
                    }

                    //retificar o lan�amento somente se for gestor
                    if(parseFloat(totalMultaAplicado) > 0 && document.getElementById('selCreditosProcesso').value != '' && document.getElementById('hdnSinSuspenso').value != 'S' && $(result).find('creditoLancado').text() != '0,00' && $(result).find('creditoNaoLancado').text() != '0,00' ){
                        document.getElementById('btnRetificarLancamento').style.display = '';
                    }
                    if(document.getElementById('hdnSinSuspenso').value == 'S'){
                        document.getElementById('btnDenegarRecurso').style.display = '';
                        document.getElementById('btnCancelarRecurso').style.display = '';
                    }

                    if(document.getElementById('selCreditosProcesso').options.length > 1){
                        document.getElementById('divCreditoProcesso').style.display = '';
                    }

                    //se o bot�o de incluir lan�amento estiver aparecendo ent�o o houve constitui��o definitiva n�o pode aparecer
                    if(document.getElementById('btnIncluirLancamento').style.display == ''){
                        document.getElementById('divHouveConstituicao').style.display = 'none';
                    }

                    //mostrar e checked o sinConstituicaoDefinitiva
                    if($(result).find('sinConstituicaoDefinitiva').text() == 'S'){
                        document.getElementById('chkHouveConstituicao').checked = true;
                        document.getElementById('chkHouveConstituicao').setAttribute('data-valor-antigo', 'S');
                        houveConstituicao(document.getElementById('chkHouveConstituicao'));
                    }else{
                        document.getElementById('chkHouveConstituicao').checked = false;
                        document.getElementById('chkHouveConstituicao').setAttribute('data-valor-antigo', 'N');
                        houveConstituicao(document.getElementById('chkHouveConstituicao'));
                    }

                    //mostrar e checked o sinRenunciaRecorrer
                    if($(result).find('sinRenunciaRecorrer').text() == 'S'){
                        document.getElementById('chkReducaoRenuncia').checked = true;
                        document.getElementById('chkReducaoRenuncia').disabled = true;
                    }else{
                        document.getElementById('chkReducaoRenuncia').checked = false;
                        document.getElementById('chkReducaoRenuncia').disabled = false;
                    }
                    calcularData();

                    //mostrar e checked o sinRenunciaRecorrer
                    if($(result).find('corSituacao').text() != ''){
                        document.getElementById('lblSaldoDevAtualizado').style.color = $(result).find('corSituacao').text();
                        document.getElementById('lblVlSaldoDevAtualizado').style.color = $(result).find('corSituacao').text();
                    }else{
                        document.getElementById('lblSaldoDevAtualizado').style.color = 'black';
                        document.getElementById('lblVlSaldoDevAtualizado').style.color = 'black';
                    }
                }

                var txtConst    = document.getElementById('txtDtConstituicao');
                var txtIntConst = document.getElementById('txtDtIntimacaoConstituicao');
                var dtPadrao    = document.getElementById('hdnDtSituacaoConclusiva');

                if(txtConst.value == ''){
                    txtConst.value = dtPadrao.value;
                }

                if(txtIntConst.value == ''){
                    txtIntConst.value = dtPadrao.value;
                }

                if(isUltimaSituacaoDecisoria() || document.getElementById('hdnIsGestor').value == 1){
                    infraHabilitarCamposDivMulta(document.getElementById('divDataGestaoMulta'));
                }else{
                    infraDesabilitarCamposDivMulta(document.getElementById('divDataGestaoMulta'));
                }

                if(document.getElementById('selCreditosProcesso').value != ''){
                    document.getElementById('selInteressado').disabled = true;
                    document.getElementById('selNumeroInteressado').disabled = true;
                }

                if(!isExisteSituacaoConclusiva()){
                    infraDesabilitarCamposDivMulta(document.getElementById('divHouveConstituicao'));
                    document.getElementById('btnConstituirDefinitivamente').style.display = 'none';
                }else if(document.getElementById('hdnIsGestor').value == 0 && document.getElementById('chkHouveConstituicao').checked){
                    infraDesabilitarCamposDivMulta(document.getElementById('divHouveConstituicao'));
                    document.getElementById('btnConstituirDefinitivamente').style.display = 'none';
                }else{
                    infraHabilitarCamposDivMulta(document.getElementById('divHouveConstituicao'));
                    document.getElementById('btnConstituirDefinitivamente').style.display = '';
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                console.log(msgCommit);
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
    }

    function abrirModalJustificativaLancamento(element){
        var url = element.getAttribute('data-url');

        if(document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null' ){
            alert('Selecione o n�mero de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }

        if(document.getElementById('txtDecisaoAplicacaoMulta').value == '' ){
            alert('A Data da Decis�o de Aplica��o da Multa � de preenchimento obrigat�rio.');
            return false;
        }

        if(document.getElementById('txtDtVencimento').value == ''){
            alert('A Data de Vencimento � de preenchimento obrigat�rio.');
            return false;
        }

        infraAbrirJanela(url,
            'JustificativaLancamento',
            680,
            280);
    }

    function abrirModalConstituirDefinitivamente(element){
        var url = element.getAttribute('data-url');
        var txtConst = document.getElementById('txtDtConstituicao');
        var txtIntConst = document.getElementById('txtDtIntimacaoConstituicao');

        if(document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null' ){
            alert('Selecione o n�mero de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }

        if(infraTrim(txtConst.value == '')){
            alert('Informe a Data da Constitui��o Definitiva!');
            return false;
        }

        if(infraTrim(txtIntConst.value == '')){
            alert('informe a Data da Intima��o da Constitui��o Definitiva!');
            return false;
        }

        var dtConstDef     = retornarDate(txtConst.value)
        var dtIntimConstDef = retornarDate(txtIntConst.value);

        if (dtIntimConstDef > dtConstDef) {
            alert('Data da Constitui��o Definitiva n�o pode ser menor que a Data da Intima��o da Constitui��o Definitiva!');
            return false;
        }

        infraAbrirJanela(url,
            'JustificativaLancamento',
            680,
            250);

    }


    function retornarDate(hdnDtDefault){
        var arrDtEntrada = hdnDtDefault.split('/');
        var data = new Date();

        data.setDate(arrDtEntrada[0]);
        data.setMonth(arrDtEntrada[1] - 1);
        data.setFullYear(arrDtEntrada[2]);

        return data;

    }

    function validarFormatoData(obj){
        var validar = infraValidarData(obj, false);
        if(!validar){
            alert('Data Inv�lida!');
          obj.value = '';
        }
    }

    function mostraBotaoContituirDefinitivamente(element){
        if(document.getElementById('chkHouveConstituicao').checked){
            document.getElementById('btnConstituirDefinitivamente').style.display = '';
        }else{
            document.getElementById('btnConstituirDefinitivamente').style.display = 'none';
        }
    }

    function abrirModalHistoricoLancamento(){
        infraAbrirJanela(document.getElementById('btnHistoricoLancamento').getAttribute('data-url'),'janelaHistoricoLancamento',900,400);
    }

    function abrirModalCancelarLancamento(){

        if (document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null') {
            alert('Selecione o n�mero de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }
        if(document.getElementById('selCreditosProcesso').getAttribute('sin-existe-majorado') == 'S'){
            alert('Existe cr�ditos do processo majorado, verifique se n�o � o caso de um Retifica��o ou de que se deve primeiramente cancelar o Lan�amento Majorado.');
            return false;
        }

        infraAbrirJanela('<?=$strLinkModalCancelarLancamento?>', 'janelaHistoricoLancamento', 900, 400);

    }

    function mudarCreditosProcesso(element){
        consultarExtratoMulta();
    }

    /**
     * Return se um option existe em um <select> object
     * @param {String} valor Um string representando o valor do option
     * @param {Object} element Um Select object
     */
    function optionExiste ( valor, element )
    {
        var optionExists = false,
            optionsLength = element.length;

        while ( optionsLength-- )
        {
            if ( element.options[ optionsLength ].value === valor )
            {
                optionExists = true;
                break;
            }
        }
        return optionExists;
    }

    function removerOptionVazio ( element )
    {
        for (var i = 0; i < element.options.length; i++) {
            if(element.options[i].value == '')
                element.options[i] = null;
        }
    }

    function verificarCondicionaisMulta(){
        if(document.getElementById('hdnVlCreditoNaoLancado').value != '0,00' && document.getElementById('hdnJustificativaLancamento').value == '' && document.getElementById('hdnIdMdLitFuncionalidade').value == '' ){
            alert('Foram identificados valores da Gest�o de Multa pendentes de atualiza��o no SISTEMA DE ARRECADA��O. Verifique se a a��o para atualiza��o dos valores foi acionada.');
            return false;
        }
        // se nao estiver mostrando o fieldset de multa n�o ter� valida��o
        if(document.getElementById('fieldsetMulta').style.display == 'none'){
            return true;
        }
        if(document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('txtDecisaoAplicacaoMulta').value) == ''){
            alert('Informe a data da decis�o de aplica��o da multa.');
            document.getElementById('txtDecisaoAplicacaoMulta').focus();
            return false;
        }
        if(document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('txtDtVencimento').value) == ''){
            alert('Informe a data de vencimento.');
            document.getElementById('txtDtVencimento').focus();
            return false;
        }
        if(document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('txtDtIntimacaoAplMulta').value) != '' && infraCompararDatas(document.getElementById('txtDecisaoAplicacaoMulta').value, document.getElementById('txtDtIntimacaoAplMulta').value) < 0){
            alert('A data da intima��o da decis�o de aplica��o da multa deve ser igual ou maior que a Data da decis�o de aplica��o da multa.');
            document.getElementById('txtDtIntimacaoAplMulta').focus();
            return false;
        }

        return true;
    }

    function calcularData(){
        if(document.getElementById('txtDecisaoAplicacaoMulta').value != '' || document.getElementById('txtDtVencimento').value != ''){
            return;
        }
        var objSituacao = objTabelaDinamicaSituacao.obterItens();

        // a ultima data da decis�o cadastrada
        var i = objSituacao.length - 1;
        if(infraRetirarAcentos(objSituacao[i][17].infraReplaceAll(' ', '').infraReplaceAll('(', '').infraReplaceAll(')', '')) == 'Decisoria' && (objSituacao[i][1] == "N" || document.getElementById('selCreditosProcesso').options.length == 0)){
            var dtaDecisaoAplicacaoMulta = objSituacao[i][11];
            //calcula a ultima data da decis�o cadastrada  � adiciona 40 dias
            var dtaVencimento = infraCalcularDataDias(dtaDecisaoAplicacaoMulta, 40);
            document.getElementById('txtDecisaoAplicacaoMulta').value = dtaDecisaoAplicacaoMulta;
            document.getElementById('txtDtVencimento').value = dtaVencimento;
        }

    }

    function verificarMudancaMulta(){
        var mostrarBotaoRetificar = false;
        if( document.getElementById('txtDecisaoAplicacaoMulta').value !=  document.getElementById('txtDecisaoAplicacaoMulta').getAttribute('data-valor-antigo')){
            mostrarBotaoRetificar = true;
        }
        if( document.getElementById('txtDtVencimento').value !=  document.getElementById('txtDtVencimento').getAttribute('data-valor-antigo')){
            mostrarBotaoRetificar = true;
        }
        if( document.getElementById('txtDtDecursoPrazo').value !=  document.getElementById('txtDtDecursoPrazo').getAttribute('data-valor-antigo')){
            mostrarBotaoRetificar = true;
        }
        if( document.getElementById('txtDtIntimacaoAplMulta').value !=  document.getElementById('txtDtIntimacaoAplMulta').getAttribute('data-valor-antigo')){
            mostrarBotaoRetificar = true;
        }
        if(!document.getElementById('chkHouveConstituicao').checked && document.getElementById('chkHouveConstituicao').getAttribute('data-valor-antigo') == 'S'){
            document.getElementById('txtDtIntimacaoConstituicao').value = '';
            document.getElementById('chkReducaoRenuncia').checked = false;
            mostrarBotaoRetificar = true;
        }

        if(mostrarBotaoRetificar && document.getElementById('selCreditosProcesso').value != ''){
            document.getElementById('btnRetificarLancamento').style.display = '';
        }else if(document.getElementById('hdnVlCreditoNaoLancado').value == '0,00'){
            document.getElementById('btnRetificarLancamento').style.display = 'none';
        }

    }

    function infraHabilitarCamposDivMulta(div){
        infraHabilitarCamposDiv(div);
        e = 0;
        els = div.getElementsByTagName('img');
        while (el = els.item(e++)){
            if(el.className.match(/imgAjudaCtrlProcLit/) == null) {
                el.style.display = '';
            }
        }
    }

    function infraDesabilitarCamposDivMulta(div){
        infraDesabilitarCamposDiv(div);
        e = 0;
        els = div.getElementsByTagName('img');
        while (el = els.item(e++)){
            if(el.className.match(/imgAjudaCtrlProcLit/) == null){
                el.style.display='none';
            }
        }
    }

</script>

