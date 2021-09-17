
<script type="text/javascript">

    var INCLUIR_DEBITO  = 1;
    var RETIFICAR_DEBITO = 2;
    var SUSPENDER_LANCAMENTO = 3;
    var CANCELAR_LANCAMENTO = 3;
    var objAjaxNumeroInteressado;

    /**
     * Função utilizada no change do campo "Data da Intimação da Decisão de Aplicação da Multa" num campo hidden par considerar
     * as alterações do lancamento ao alterar
     * @param element
     */
    function armazenarDataIntimacaoMulta(element){
            var index = $('[name="selCreditosProcesso"]').val();
            var value = $(element).val();
            if($('.hdnDtaIntimacaoDecisaoMulta[data-id="'+index+'"]').length > 0){
                $('.hdnDtaIntimacaoDecisaoMulta[data-id="'+index+'"]').val(value);
            } else {
                $('#divDadosIntimacaoAplicaocaoMulta').append('<input type="hidden" name="hdnDtaIntimacaoDecisaoMulta['+index+']" class="hdnDtaIntimacaoDecisaoMulta" data-id="'+index+'"value="'+value+'"/>');
            }
    }

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
            }else if(document.getElementById('selNumeroInteressado').options.length == 2){
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
        mostraBotaoContituirDefinitivamente();
    }

    function consultarExtratoMulta(){
        //se o tipo da multa for por indicação de valor e nao houver ignora as validações e exibição do fieldset de multa
        if(isTipoMultaIndicacaoValor() == true && existeLancamentoProcedimento() == false){
            document.getElementById('fieldsetMulta').style.display = 'none';
            return;
        }

        var valueDecisao = objTabelaDinamicaDecisao.hdn.value;
        document.getElementById('btnRetificarLancamento').style.display = 'none';
        document.getElementById('btnIncluirLancamento').style.display = 'none';
        mostrarEsconderElemento(document.getElementById('btnVincularLancamento'),'none');
        document.getElementById('btnCancelarLancamento').style.display = 'none';
        document.getElementById('btnSuspenderLancamento').style.display = 'none';
        document.getElementById('btnDenegarRecurso').style.display = 'none';
        document.getElementById('btnCancelarRecurso').style.display = 'none';
        document.getElementById('divHouveConstituicaoChk').style.display = '';
        document.getElementById('btnConstituirDefinitivamente').style.display = '';

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxConsultarExtratoMulta ?>",
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
                    alert($(result).find('erro').attr('descricao').replace('<br />', '\n\n'));
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
                    document.getElementById('txtDtDecursoPrazoRecurso').value = $(result).find('dtDecursoPrazoRecurso').text();
                    document.getElementById('txtDtVencimento').value = $(result).find('dtVencimento').text();
                    document.getElementById('txtDtConstituicao').value = $(result).find('dtConstituicao').text();
                    document.getElementById('txtDtIntimacaoConstituicao').value = $(result).find('dtIntimacaoConstituicao').text();
                    document.getElementById('txtDtIntimacaoAplMulta').setAttribute('data-valor-antigo', $(result).find('dtIntimacaoDecisaoAplicacaoMulta').text());
                    document.getElementById('txtDtVencimento').setAttribute('data-valor-antigo', $(result).find('dtVencimento').text());
                    document.getElementById('txtDtConstituicao').setAttribute('data-valor-antigo', $(result).find('dtConstituicao').text());
                    document.getElementById('txtDtIntimacaoConstituicao').setAttribute('data-valor-antigo', $(result).find('dtIntimacaoConstituicao').text());
                    document.getElementById('txtDecisaoAplicacaoMulta').setAttribute('data-valor-antigo', $(result).find('dtDecisaoAplicacaoMulta').text());

                    //botão historico
                    document.getElementById('btnHistoricoLancamento').setAttribute('data-url', $(result).find('urlHistoricoLancamento').text());

                    var creditoNaoLancado      = $(result).find('creditoNaoLancado').text().infraReplaceAll('.','').replace(',', '.');
                    var creditoLancado         = $(result).find('creditoLancado').text().infraReplaceAll('.','').replace(',', '.');
                    var totalMultaAplicado     = $(result).find('multaAplicada').text().infraReplaceAll('.','').replace(',', '.');
                    var isNovoLancamento       = $(result).find('isNovoLancamento').text();
                    var isCancelar             = $(result).find('isCancelar ').text();
                    var sinExibeCancelamento   = $(result).find('sinExibeCancelamento').text();
                    var sinExisteMajoracao     = $(result).find('sinExisteMajoracao').text();
                    var selCreditosProcesso    = document.getElementById('selCreditosProcesso').value;
                    var hdnSinSuspenso         = document.getElementById('hdnSinSuspenso').value;
                    var sinIsUltimaSitIntimacao = <?= $isSituacaoIntimacao ? 'true' : 'false'?>;

                    //mostrar o fieldset de multa
                    if(creditoNaoLancado != '0.00' || totalMultaAplicado != '0.00'){
                        document.getElementById('fieldsetMulta').style.display = '';
                    }else{
                        document.getElementById('fieldsetMulta').style.display = 'none';
                    }

                    if(sinExibeCancelamento == 'N' && creditoNaoLancado != '0.00' && isNovoLancamento == 'S' &&  selCreditosProcesso == ''){
                        document.getElementById('btnIncluirLancamento').style.display = parseFloat(creditoNaoLancado) > 0 ? '': 'none';
                        var estilo = parseFloat(creditoNaoLancado) > 0 ? '': 'none';

                        mostrarEsconderElemento(document.getElementById('btnVincularLancamento'),estilo);

                    }else if(sinExibeCancelamento == 'N' && sinExisteMajoracao == 'S' && creditoNaoLancado > 0 && isNovoLancamento == 'S'){
                        //Novo Boleto da Diferença a Maior (combo de crédito à parte, com datas próprias)
                        if(!optionExiste('',document.getElementById('selCreditosProcesso')))
                            infraSelectAdicionarOption(document.getElementById('selCreditosProcesso'), 'Majorado (R$ '+$(result).find('creditoNaoLancado').text() +')', '');

                        infraSelectSelecionarItem(document.getElementById('selCreditosProcesso'), '');

                        //recarregar o fieldset para atualizar os valores da multa
                        window.setTimeout(function(){consultarExtratoMulta()}, 500);
                        return;
                    }else if( ( sinExisteMajoracao == 'N' || (sinExisteMajoracao == 'S' && isNovoLancamento == 'N')) && sinExibeCancelamento == 'N' && creditoLancado != '0.00' && creditoNaoLancado != '0.00' ){
                        document.getElementById('btnRetificarLancamento').style.display = '';
                    }

                    if(sinExibeCancelamento == 'N'){
                        document.getElementById('selCreditosProcesso').setAttribute('sin-existe-majorado', $(result).find('sinExisteMajorado').text());
                    }

                    //Se não existe Crédito Lançado
                    if(sinExibeCancelamento == 'S' && creditoLancado != '0.00' && isCancelar == '0' && hdnSinSuspenso == 'N'){
                        document.getElementById('btnCancelarLancamento').style.display = '';
                    }

                    //a multa não pode ser retificado se ela for zerado
                    if(parseFloat(totalMultaAplicado) <= 0){
                        document.getElementById('btnRetificarLancamento').style.display = 'none';
                    }

                    if(hdnSinSuspenso == 'S'){
                        document.getElementById('btnDenegarRecurso').style.display = '';
                        document.getElementById('btnCancelarRecurso').style.display = '';
                    }

                    if(document.getElementById('selCreditosProcesso').options.length > 1){
                        document.getElementById('divCreditoProcesso').style.display = '';
                    }

                    //se o botão de incluir lançamento estiver aparecendo então o houve constituição definitiva não pode aparecer
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
                        document.getElementById('chkReducaoRenuncia').setAttribute('data-valor-antigo', 'S');
                    }else{
                        document.getElementById('chkReducaoRenuncia').checked = false;
                        document.getElementById('chkReducaoRenuncia').disabled = false;
                        document.getElementById('chkReducaoRenuncia').setAttribute('data-valor-antigo', 'N');
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

                //replicação automatica da "data da intimação da decisao que aplicou a multa"
                if($('[name="hdnIntimacaoPosDecisao"]').val() != ''){

                    //recupera o lancamento exibido
                    var idLancamento = $('[name="selCreditosProcesso"]').val();
                    /** se a Data da Intimação da Decisão de Aplicação da Multa foi alterada alternando os lancamentos usa esta data
                     * senão utiliza a data da intimação
                    */
                    if($('.hdnDtaIntimacaoDecisaoMulta[data-id="'+idLancamento+'"]').length > 0) {
                        var dataIntimacao = $('.hdnDtaIntimacaoDecisaoMulta[data-id="'+idLancamento+'"]').val()
                        $('[name="txtDtIntimacaoAplMulta"]').val(dataIntimacao);
                    } else if($('[name="txtDtIntimacaoAplMulta"]').val() == ''){
                        var dataIntimacao = $('[name="hdnIntimacaoPosDecisao"]').val();
                        $('[name="txtDtIntimacaoAplMulta"]').val(dataIntimacao);
                    }

                    //seta estilo para o campo como obrigatorio
                    calcularDecursoPrazoRecurso();
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
                    infraDesabilitarCamposDivMulta(document.getElementById('divtxtDtConstituicao'));

                }else{
                    infraDesabilitarCamposDivMulta(document.getElementById('divDataGestaoMulta'));
                    infraHabilitarCamposDivMulta(document.getElementById('divDtaIntimacaoAplMulta'));
                    infraHabilitarCamposDivMulta(document.getElementById('divDtaDecursoPrazoRecurso'));
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
                    document.getElementById('chkReducaoRenuncia').disabled = false;
                }else{
                    infraHabilitarCamposDivMulta(document.getElementById('divHouveConstituicao'));
                    document.getElementById('btnConstituirDefinitivamente').style.display = '';
                    infraHabilitarCamposDivMulta(document.getElementById('divDataGestaoMulta'));
                    infraHabilitarCamposDivMulta(document.getElementById('divtxtDtConstituicao'));
                }

                if(document.getElementById('txtDtConstituicao').getAttribute('data-valor-antigo') != '' && document.getElementById('hdnIsGestor').value == 0){
                    infraDesabilitarCamposDivMulta(document.getElementById('divtxtDtConstituicao'));
                }

                if(document.getElementById('hdnStrSituacao').value == 'Intimacao' ||
                    (document.getElementById('hdnStrSituacao').value == '' && sinIsUltimaSitIntimacao)){
                    $('#lblDtIntimacaoAplMulta').addClass('infraLabelObrigatorio');
                    $('#lblDtDecursoPrazoRecurso').addClass('infraLabelObrigatorio');
                } else {
                    $('#lblDtIntimacaoAplMulta').removeClass('infraLabelObrigatorio');
                    $('#lblDtDecursoPrazoRecurso').removeClass('infraLabelObrigatorio');
                }

                //por não haver promisses isso é necessario para replicar as datas da situação para multa
                replicarDataParaFieldsetGestaoMulta();
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

        // if(objTabelaDinamicaDecisao.verificarCadastroParcial()){
        //     alert("Foi identificado que ainda existem infrações sem Decisão cadastrada. Posteriormente, para prosseguir com o cadastro de novas Situações ou a Gestão de Multa, ainda será necessário finalizar o Cadastro das Decisões.");
        //     return;
        // }

        if(document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null' ){
            alert('Selecione o número de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }

        if(document.getElementById('txtDecisaoAplicacaoMulta').value == '' ){
            alert('A Data da Decisão de Aplicação da Multa é de preenchimento obrigatório.');
            return false;
        }

        if(document.getElementById('txtDtVencimento').value == ''){
            alert('A Data de Vencimento é de preenchimento obrigatório.');
            return false;
        }

        infraAbrirJanela(url,
            'JustificativaLancamento',
            680,
            280);
    }


    function abrirModalVincularLancamento(element){
        var url = element.getAttribute('data-url');

        // if(objTabelaDinamicaDecisao.verificarCadastroParcial()){
        //     alert("Foi identificado que ainda existem infrações sem Decisão cadastrada. Posteriormente, para prosseguir com o cadastro de novas Situações ou a Gestão de Multa, ainda será necessário finalizar o Cadastro das Decisões.");
        //     return;
        // }

        if(document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null' ){
            alert('Selecione o número de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }

        infraAbrirJanela(url,
            'JustificativaLancamento',
            880,
            480);
    }
    function abrirModalConstituirDefinitivamente(element){
        var url = element.getAttribute('data-url');
        var txtConst = document.getElementById('txtDtConstituicao');
        var txtIntConst = document.getElementById('txtDtIntimacaoConstituicao');

        if(document.getElementById('selNumeroInteressado').value == '' || document.getElementById('selNumeroInteressado').value == 'null' ){
            alert('Selecione o número de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }

        if(infraTrim(txtConst.value == '')){
            alert('Informe a Data da Constituição Definitiva!');
            return false;
        }

        if(infraTrim(txtIntConst.value == '')){
            alert('Informe a Data da Intimação da Decisão Definitiva!');
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
            alert('Data Inválida!');
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
            alert('Selecione o número de complemento do interessado!');
            objAjaxNumeroInteressado.executar();
            return false;
        }
        if(document.getElementById('selCreditosProcesso').getAttribute('sin-existe-majorado') == 'S'){
            alert('Existe créditos do processo majorado, verifique se não é o caso de um Retificação ou de que se deve primeiramente cancelar o Lançamento Majorado.');
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
        // if(objTabelaDinamicaDecisao.verificarCadastroParcial()){
        //     return true;
        // }

        //se o tipo da multa for por indicacao de valor não valida o lancamento da multa
        if(isTipoMultaIndicacaoValor() && existeLancamentoProcedimento() == false){
            return true;
        }

        if(document.getElementById('hdnVlCreditoNaoLancado').value != '0,00' && document.getElementById('hdnJustificativaLancamento').value == '' && infraTrim(document.getElementById('hdnIdMdLitFuncionalidade').value == '') && document.getElementById('hdnTbVincularLancamento').value == '' ){
            alert('Foram identificados valores da Gestão de Multa pendentes de atualização no SISTEMA DE ARRECADAÇÃO. Verifique se a ação para atualização dos valores foi acionada.');
            return false;
        }
        // se nao estiver mostrando o fieldset de multa não terá validação
        if(document.getElementById('fieldsetMulta').style.display == 'none'){
            return true;
        }
        if(document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('txtDecisaoAplicacaoMulta').value) == ''){
            alert('Informe a data da decisão de aplicação da multa.');
            document.getElementById('txtDecisaoAplicacaoMulta').focus();
            return false;
        }
        if(document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('txtDtVencimento').value) == ''){
            alert('Informe a data de vencimento.');
            document.getElementById('txtDtVencimento').focus();
            return false;
        }
        if(document.getElementById('hdnVlTotalMulta').value != '0,00' && infraTrim(document.getElementById('txtDtIntimacaoAplMulta').value) != '' && infraCompararDatas(document.getElementById('txtDecisaoAplicacaoMulta').value, document.getElementById('txtDtIntimacaoAplMulta').value) < 0){
            alert('A data da intimação da decisão de aplicação da multa deve ser igual ou maior que a Data da decisão de aplicação da multa.');
            document.getElementById('txtDtIntimacaoAplMulta').focus();
            return false;
        }
        //validação no submit do campo Data da Intimação da Decisão de Aplicação da Multa 
        if(($('[name="hdnIntimacaoPosDecisao"]').val() != '' && $('[name="hdnIntimacaoPosDecisao"]').val() != undefined)) {
            if(infraTrim(document.getElementById('txtDtIntimacaoAplMulta').value) == '' ){
                alert('A Data da Intimação da Decisão de Aplicação da Multa é de preenchimento obrigatório.');
                document.getElementById('txtDtIntimacaoAplMulta').focus();
                return false;
            }
            if(infraTrim(document.getElementById('txtDtDecursoPrazoRecurso').value) == '' ){
                alert('A Data do Decurso do Prazo para Recurso é de preenchimento obrigatório.');
                document.getElementById('txtDtDecursoPrazoRecurso').focus();
                return false;
            }
        } 
        if($('#lblDtIntimacaoAplMulta').hasClass('infraLabelObrigatorio') 
                && infraTrim(document.getElementById('txtDtIntimacaoAplMulta').value) == '' ){
            alert('A Data da Intimação da Decisão de Aplicação da Multa é de preenchimento obrigatório.');
            document.getElementById('txtDtIntimacaoAplMulta').focus();
            return false;
        }

        if($('#lblDtDecursoPrazoRecurso').hasClass('infraLabelObrigatorio') && infraTrim(document.getElementById('txtDtDecursoPrazoRecurso').value) == '' ){
            alert('A Data do Decurso do Prazo para Recurso é de preenchimento obrigatório.');
            document.getElementById('txtDtDecursoPrazoRecurso').focus();
            return false;
        }

        return true;
    }

    function isTipoMultaIndicacaoValor(){
        var flagMultaIntegracao = false;
         var arrDecisao = objTabelaDinamicaDecisao.obterItens();
         var arrEspecies = new Array();
         for(var i = 0; i < arrDecisao.length;i++){
             if(arrDecisao[i][3] != 'null'){
                 //armazena o id das especies de decisão para validar a multa com ou sem integração
                 arrEspecies.push(arrDecisao[i][3]);
             }
         }

         if(arrEspecies.length == 0){
             return false;
         }

        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_recuperar_especie_decisao') ?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId':arrEspecies},
            success: function (data) {
                $.each($(data).find('MdLitEspecieDecisaoDTO'), function(key, value) {
                    if($(value).find('StaTipoIndicacaoMulta').text() == "<?=MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO ?>"){
                        flagMultaIntegracao = true;
                    }

                });
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o validação do do SEI: " + msgError.responseText;
                console.log(msgCommit);
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

        //se for multa por integração retorna pra cair na validação
        if(flagMultaIntegracao){
            return false;
        }

        //se for multa por indicação de valor não precisa validar o lancamento do valor por integração
        return true;

    }

    function existeLancamentoProcedimento(){
        var idProcedimento = document.getElementById('hdnIdProcedimento').value;
        var existeLancamento = false;
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_recuperar_lancamentos_procedimento') ?>",
            dataType: "xml",
            async: false,
            data: {'idProcedimento': idProcedimento},
            success: function (data) {
                if($(data).find('MdLitLancamentoDTO').length > 0) {
                    existeLancamento = true;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o validação do do SEI: " + msgError.responseText;
                console.log(msgCommit);
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

        return existeLancamento;
    }

    function calcularData(){
        if(document.getElementById('txtDecisaoAplicacaoMulta').value != '' || document.getElementById('txtDtVencimento').value != ''){
            return;
        }
        var objSituacao = objTabelaDinamicaSituacao.obterItens();

        // a ultima data da decisão cadastrada
        var i = objSituacao.length - 1;
        if(infraRetirarAcentos(objSituacao[i][17].infraReplaceAll(' ', '').infraReplaceAll('(', '').infraReplaceAll(')', '')) == 'Decisoria' && (objSituacao[i][1] == "N" || document.getElementById('selCreditosProcesso').options.length == 0)){
            var dtaDecisaoAplicacaoMulta = objSituacao[i][11];
            //calcula a ultima data da decisão cadastrada  é adiciona 40 dias
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
        if( document.getElementById('txtDtIntimacaoConstituicao').value !=  document.getElementById('txtDtIntimacaoConstituicao').getAttribute('data-valor-antigo') &&
            document.getElementById('txtDtIntimacaoConstituicao').getAttribute('campo-mapea-param-entrada') == 'S'){
            mostrarBotaoRetificar = true;
        }
        // if( document.getElementById('txtDtIntimacaoAplMulta').value !=  document.getElementById('txtDtIntimacaoAplMulta').getAttribute('data-valor-antigo')){
        //     mostrarBotaoRetificar = true;
        // }
        if(!document.getElementById('chkHouveConstituicao').checked && document.getElementById('chkHouveConstituicao').getAttribute('data-valor-antigo') == 'S'){
            document.getElementById('txtDtIntimacaoConstituicao').value = '';
            document.getElementById('chkReducaoRenuncia').checked = false;
            mostrarBotaoRetificar = true;
        }
        if(
            document.getElementById('chkReducaoRenuncia').checked &&
            document.getElementById('chkHouveConstituicao').getAttribute('data-valor-antigo') == 'S' &&
            document.getElementById('chkReducaoRenuncia').getAttribute('data-valor-antigo') == 'N'
        ){
            mostrarBotaoRetificar = true;
        }
        if(
            !document.getElementById('chkReducaoRenuncia').checked  &&
            document.getElementById('chkHouveConstituicao').getAttribute('data-valor-antigo') == 'S' &&
            document.getElementById('chkReducaoRenuncia').getAttribute('data-valor-antigo') == 'S'
            
        ){
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

    function mostrarEsconderElemento(element, estilo){
        if(element){
            element.style.display = estilo;
        }
    }

    function calcularDecursoPrazoRecurso() {
        const dtDecisaoAplMulta = $('#txtDtIntimacaoAplMulta').val();
        const $txtDtDecursoPrazoRecurso = $('#txtDtDecursoPrazoRecurso');
        let strDtDecursoPrazoRecurso = '';
        if($txtDtDecursoPrazoRecurso.val() == ''){
            if(dtDecisaoAplMulta){
                $.ajax({
                    url: '<?= $strLinkAjaxCalcularDataDecurso ?>',
                    type: 'POST',
                    data: { 'dtDecisaoAplMulta': dtDecisaoAplMulta,
                            'idTpControle' :  '<?=$idTpControle?>',
                            'idProcedimento' :'<?=$idProcedimento?>'
                            },
                    async: false,
                    success: function (response) {
                        strDtDecursoPrazoRecurso = $(response).find('resultado').text();
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

            $txtDtDecursoPrazoRecurso.val(strDtDecursoPrazoRecurso);
        }
    }
</script>

