
<script type="text/javascript">

    var INCLUIR_DEBITO  = 1;
    var RETIFICAR_DEBITO = 2;
    var SUSPENDER_LANCAMENTO = 3;
    var CANCELAR_LANCAMENTO = 3;
    var objAjaxFistel;

    function carregarDependenciaMulta(){

        objAjaxFistel = new infraAjaxMontarSelectDependente('selInteressado', 'selFistel', '<?=$strLinkComboFistel?>');

        objAjaxFistel.prepararExecucao = function(){
            document.getElementById('selFistel').innerHTML = "";
            document.getElementById('div-fistel').style.display = '';
            return 'id_interessado='+document.getElementById('selInteressado').value+'&id_procedimento='+document.getElementById('hdnIdProcedimento').value;
        };
        objAjaxFistel.processarResultado = function(itCnt){
            if(document.getElementById('selFistel').getAttribute('data-numfistel') != ''){
                selecionarPorNome('selFistel', document.getElementById('selFistel').getAttribute('data-numfistel'));
            }
            if(document.getElementById('selFistel').options.length == 2){
                document.getElementById('selFistel').options[1].selected = true;
            }
        };
        consultarExtratoMulta();

        if(document.getElementById('selInteressado').options.length == 2){
            document.getElementById('selInteressado').options[1].selected = true;
        }
        if(document.getElementById('selInteressado').value != 'null'){
            window.setTimeout(function(){objAjaxFistel.executar()}, 1000);
        }

        houveConstituicao(document.getElementById('chkHouveConstituicao'));

    }

    function selecionarPorNome(select,valor){

        var sel = infraGetElementById(select);
        for (var i=0; i<sel.length; i++) {
            if (sel.options[i].text == valor) {
                sel.options[i].selected = true;
                return;
            }
        }
    }

    function houveConstituicao(element){

        if(element.checked){
            //aparecer todos os campos do houve constituição
            var elements = document.getElementsByClassName('nao-tem-constituicao');
            var iLen = elements.length;
            while (iLen > 0) {
                elements[iLen - 1].className = elements[iLen - 1].className.replace('nao-tem-constituicao', 'tem-constituicao');
                elements = document.getElementsByClassName('nao-tem-constituicao');
                iLen = elements.length;
            }
        }else{
            //aparecer todos os campos do houve constituição
            var elements = document.getElementsByClassName('tem-constituicao');
            var iLen = elements.length;
            while (iLen > 0) {
                elements[iLen - 1].className = elements[iLen - 1].className.replace('tem-constituicao', 'nao-tem-constituicao');
                elements = document.getElementsByClassName('tem-constituicao');
                iLen = elements.length;
            }
        }
    }

    function consultarExtratoMulta(){
        var valueDecisao = objTabelaDinamicaDecisao.hdn.value;
        document.getElementById('btnRetificarLancamento').style.display = 'none';
        document.getElementById('btnIncluirLancamento').style.display = 'none';
        document.getElementById('btnCancelarLancamento').style.display = 'none';
        document.getElementById('btnSuspenderLancamento').style.display = 'none';
        document.getElementById('btnDenegarRecurso').style.display = 'none';
        document.getElementById('btnCancelarRecurso').style.display = 'none';

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

                    //botão historico
                    document.getElementById('btnHistoricoLancamento').setAttribute('data-url', $(result).find('urlHistoricoLancamento').text());

                    var creditoNaoLancado = $(result).find('creditoNaoLancado').text().replace('.','').replace(',', '.');

                    if($(result).find('creditoNaoLancado').text() != '0,00' && $(result).find('isNovoLancamento').text() == 'S' && document.getElementById('selCreditosProcesso').value == ''){


                        document.getElementById('btnIncluirLancamento').style.display = parseFloat(creditoNaoLancado) > 0 ? '': 'none';
                    }else if(creditoNaoLancado > 0 && $(result).find('isNovoLancamento').text() == 'S'){
                        //Novo Boleto da Diferença a Maior (combo de crédito à parte, com datas próprias)
                        if(!optionExiste('',document.getElementById('selCreditosProcesso')))
                            infraSelectAdicionarOption(document.getElementById('selCreditosProcesso'), 'Majorado (R$ '+$(result).find('creditoNaoLancado').text() +')', '');

                        infraSelectSelecionarItem(document.getElementById('selCreditosProcesso'), '');

                        //recarregar o fieldset para atualizar os valores da multa
                        window.setTimeout(function(){consultarExtratoMulta()}, 500);
                        return;
                    }else if(creditoNaoLancado < 0 && $(result).find('isNovoLancamento').text() == 'S' && $(result).find('creditoLancado').text() != '0,00'){
                        document.getElementById('btnRetificarLancamento').style.display = '';
                    }

                    //Se não existe Crédito Lançado
                    if($(result).find('creditoLancado').text() != '0,00' && $(result).find('isCancelar').text() == '0' && document.getElementById('hdnSinSuspenso').value == 'N'){
                        document.getElementById('btnCancelarLancamento').style.display = '';
                    }

                    //retificar o lançamento somente se for gestor
                    if(document.getElementById('selCreditosProcesso').value != '' && document.getElementById('hdnIsGestor').value == '1' && document.getElementById('hdnSinSuspenso').value != 'S' && $(result).find('creditoLancado').text() != '0,00' && $(result).find('creditoNaoLancado').text() != '0,00' ){
                        document.getElementById('btnRetificarLancamento').style.display = '';
                    }
                    if(document.getElementById('hdnSinSuspenso').value == 'S'){
                        document.getElementById('btnDenegarRecurso').style.display = '';
                        document.getElementById('btnCancelarRecurso').style.display = '';
                    }

                    if(document.getElementById('selCreditosProcesso').options.length > 1){
                        document.getElementById('divCreditoProcesso').style.display = '';
                    }

                    //mostrar e checked o sinConstituicaoDefinitiva
                    if($(result).find('sinConstituicaoDefinitiva').text() == 'S'){
                        document.getElementById('chkHouveConstituicao').checked = true;
                        houveConstituicao(document.getElementById('chkHouveConstituicao'));
                        document.getElementById('chkHouveConstituicao').disabled = true;
                    }else{
                        document.getElementById('chkHouveConstituicao').checked = false;
                        houveConstituicao(document.getElementById('chkHouveConstituicao'));
                        document.getElementById('chkHouveConstituicao').disabled = false;
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

        if(document.getElementById('selFistel').value == '' || document.getElementById('selFistel').value == 'null' ){
            alert('Selecione o fistel!');
            objAjaxFistel.executar();
            return false;
        }

        infraAbrirJanela(url,
            'JustificativaLancamento',
            680,
            250);
    }

    function abrirModalConstituirDefinitivamente(element){
        var url = element.getAttribute('data-url');

        if(document.getElementById('selFistel').value == '' || document.getElementById('selFistel').value == 'null' ){
            alert('Selecione o fistel!');
            objAjaxFistel.executar();
            return false;
        }

        if(document.getElementById('txtDtConstituicao').value == ''){
            alert('Informe a Data da Constituição!');
            return false;
        }

        if(document.getElementById('txtDtIntimacaoConstituicao').value == ''){
            alert('informe a Data da Intimação da Constituição!');
            return false;
        }

        infraAbrirJanela(url,
            'JustificativaLancamento',
            680,
            250);

    }

    function mostraBotaoContituirDefinitivamente(element){
        if(document.getElementById('chkReducaoRenuncia').checked){
            document.getElementById('btnConstituirDefinitivamente').style.display = '';
        }else{
            document.getElementById('btnConstituirDefinitivamente').style.display = 'none';
        }
    }

    function abrirModalHistoricoLancamento(){
        infraAbrirJanela(document.getElementById('btnHistoricoLancamento').getAttribute('data-url'),'janelaHistoricoLancamento',900,400);
    }

    function abrirModalCancelarLancamento(){

        if (document.getElementById('selFistel').value == '' || document.getElementById('selFistel').value == 'null') {
            alert('Selecione o fistel!');
            objAjaxFistel.executar();
            return false;
        } else {
            infraAbrirJanela('<?=$strLinkModalCancelarLancamento?>', 'janelaHistoricoLancamento', 900, 400);
        }

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
            alert('Foram identificados valores da Gestão de Multa pendentes de atualização no SIGEC. Verifique se a ação para atualização dos valores foi acionada.');
            return false;
        }

        return true;
    }

    function calcularData(){

        if(document.getElementById('txtDecisaoAplicacaoMulta').value != '' || document.getElementById('txtDtVencimento').value != ''){
            return;
        }
        var objSituacao = objTabelaDinamicaSituacao.obterItens();

        // a ultima data da decisão cadastrada
        for(var i =0; i <  objSituacao.length; i++){
            if(objSituacao[i][1] == "N" && objSituacao[i][17] == 'Decisoria'){
                var dtaDecisaoAplicacaoMulta = objSituacao[i][11];
                //calcula a ultima data da decisão cadastrada  é adiciona 40 dias
                var dtaVencimento = infraCalcularDataDias(dtaDecisaoAplicacaoMulta, 40);

                document.getElementById('txtDecisaoAplicacaoMulta').value = dtaDecisaoAplicacaoMulta;
                document.getElementById('txtDtVencimento').value = dtaVencimento;
                break;
            }

        }
    }

</script>

