<script type="text/javascript" charset="iso-8859-1" src="/../../../infra_js/InfraUtil.js"></script>
<script type="text/javascript">

    var hdnIdMdLitControle = '<?= $hdnIdMdLitControle ?>';

    objLupaICDispositivoNormativo = null;
    objAutoCompletarICDispositivoNormativo = null;

    objAutoCompletarIDNDispositivoNormativo = null;
    objLupaIDNDispositivoNormativo = null;

    var objTabelaDocInstaurador = null;

    var objTabelaDI = null;
    var hdnListaDIIndicados = null;
    var arrhdnListaDIIndicados = null;
    var objTabelaInteressado = null;

    var objTabelaPS = null;
    var hdnListaPSIndicados = null;
    var arrhdnListaPSIndicados = null;

    //altura original do iframe
    var alturaOriginalStyle = window.parent.document.getElementById('ifrVisualizacao').style.height;
    var alturaOriginal = window.parent.document.getElementById('ifrVisualizacao').height;

    window.parent.document.getElementById('ifrVisualizacao').onunload = function () {
        window.parent.document.getElementById('ifrVisualizacao').style.height = alturaOriginalStyle + "px";
        window.parent.document.getElementById('ifrVisualizacao').height = alturaOriginal + "px";
        window.parent.document.getElementById('ifrVisualizacao').scrolling = "yes";
    };

    function autoResize(id) {
        var newheight = document.body.scrollHeight;
        var newwidth = document.body.scrollWidth;
        window.parent.document.getElementById(id).width = newwidth + "px";
        window.parent.document.getElementById(id).style.height = newheight + "px";
        window.parent.document.getElementById(id).height = newheight + "px";
        window.parent.document.getElementById(id).scrolling = "no";
    }
    function autoResizeSair(id) {
        window.parent.document.getElementById(id).style.height = alturaOriginalStyle + "px";
        window.parent.document.getElementById(id).height = alturaOriginal + "px";
        window.parent.document.getElementById(id).scrolling = "yes";
    }

    function enterValidarDocumento(e) {
        if(e && e.keyCode == 13) {
            document.getElementById('sbmValidarNumeroSei').onclick();
            return false;
        }
    }

    function inicializar() {
        showHide(false, 'classDispositivoNormativo');
        showHide(false, 'classCondutas');
        //valida se possui participante e se esta parametrizado para Apresenta Modal de Dados Complementares do Interessado
        buscarInteressadoValidacao();
        carregarComponenteDispositivoNormativoDN();
        carregarComponenteDispositivoNormativoCD();
        carregarDependenciaConduta();
        iniciarTabelaInteressado();

        if ('<?=$_GET['acao']?>' == 'gerir_tamanho_arquivo_peticionamento_cadastrar') {
            document.getElementById('txtNumeroSei').focus();
        } else {
            document.getElementById('btnCancelar').focus();
        }

        /*
         objTabelaPS.recarregar();
         //acoes
         if (objTabelaPS.hdn.value!=''){
         hdnListaPSIndicados = objTabelaPS.hdn.value;
         arrhdnListaPSIndicados = hdnListaPSIndicados.split('¥');

         //array
         if (arrhdnListaPSIndicados.length>0){
         for (i=0; i<arrhdnListaPSIndicados.length; i++) {
         hdnListaPSIndicado = arrhdnListaPSIndicados[i].split('±');
         objTabelaPS.adicionarAcoes(hdnListaPSIndicado[0],"<img onclick='javascript:alert()' title='Remover Sobrestamento' alt='Remover Sobrestamento' src='imagens/sei_remover_sobrestamento_processo_pequeno.gif' class='infraImg'/>",false,false);
         }
         }else{
         objTabelaPS.adicionarAcoes(hdnListaPSIndicados,"<img onclick='javascript:alert()' title='Remover Sobrestamento' alt='Remover Sobrestamento' src='imagens/sei_remover_sobrestamento_processo_pequeno.gif' class='infraImg'/>",false,false);
         }
         }
         */

        if (objTabelaDI.hdn.value != '') {
            objTabelaDI.recarregar();
            /*
             //acoes
             hdnListaDIIndicados = objTabelaDI.hdn.value;
             arrhdnListaDIIndicados = hdnListaDIIndicados.split('¥');

             //array
             if (arrhdnListaDIIndicados.length>0){
             for (i=0; i<arrhdnListaDIIndicados.length; i++) {
             hdnListaDIIndicados = arrhdnListaDIIndicados[i].split('±');
             objTabelaDI.adicionarAcoes(hdnListaDIIndicados[0], "", true, true);
             }
             }else{
             objTabelaDI.adicionarAcoes(hdnListaDIIndicados, "", true, true);
             }
             */
        }

        if (objTabelaPS.hdn.value != '') {
            objTabelaPS.recarregar();

            //acoes
            hdnListaPSIndicados = objTabelaPS.hdn.value;
            arrhdnListaPSIndicados = hdnListaPSIndicados.split('¥');

            //array
            if (arrhdnListaPSIndicados.length > 0) {
                for (i = 0; i < arrhdnListaPSIndicados.length; i++) {
                    hdnListaPSIndicado = arrhdnListaPSIndicados[i].split('±');
                    objTabelaPS.adicionarAcoes(hdnListaPSIndicado[0], "<img onclick=\"javascript:removerSobrestamentoProcesso('" + hdnListaPSIndicado[0] + "'," + hdnListaPSIndicado[1] + ")\" title='Remover Sobrestamento' alt='Remover Sobrestamento' src='imagens/sei_remover_sobrestamento_processo_pequeno.gif' class='infraImg'/>", false, false);
                }
            } else {
                hdnListaPSIndicado = hdnListaPSIndicados.split('±');
                objTabelaPS.adicionarAcoes(hdnListaPSIndicados, "<img Onclick=\"javascript:removerSobrestamentoProcesso('" + hdnListaPSIndicado[0] + "'," + hdnListaPSIndicado[1] + ")\" title='Remover Sobrestamento' alt='Remover Sobrestamento' src='imagens/sei_remover_sobrestamento_processo_pequeno.gif' class='infraImg'/>", false, false);
            }
        }

        //if (count($arrAcoesDownload)>0){
        //	  foreach(array_keys($arrAcoesDownload) as $id) {
        //	    objTabelaAnexos.adicionarAcoes('=$id','=$arrAcoesDownload[$id]');
        //	  }
        //	}

        // Registro Existente
        if (document.getElementById('txtNumeroSei').value != '') {
            document.getElementById('sbmValidarNumeroSei').onclick();
            document.getElementById('txtNumeroSei').value = '';
            document.getElementById('txtNumeroSei').onkeyup();

            //aparecer todos os fieldset do Litigioso
            var elements = document.getElementsByClassName('NumeroSEINaoValidado');
            var iLen = elements.length;
            while (iLen > 0) {
                elements[iLen - 1].className = elements[iLen - 1].className.replace('NumeroSEINaoValidado', 'NumeroSEIValidado');
                elements = document.getElementsByClassName('NumeroSEINaoValidado');
                iLen = elements.length;
            }
            mostrarTabelaPS(true);
        }

        //registro existente Processos a serem Sobrestados
        if(document.getElementById('txtNumeroSeiPS').value != ''){
            document.getElementById('sbmValidarNumeroSeiPS').onclick();
        }
        //infraEfeitoTabelas();

        //redimensiona o iframe
        //autoResize('ifrVisualizacao');
    }
    function sair() {
        //autoResizeSair('ifrVisualizacao');
    }

    function somenteNumeros(e) {
        var tecla = (window.event) ? event.keyCode : e.which;
        if ((tecla > 47 && tecla < 58))
            return true;
        else {
            if (tecla == 8 || tecla == 0)
                return true;
            else  return false;
        }
    }
    function showHide(show, classe) {
        classe = '.' + classe;
        objs = document.querySelectorAll(classe);

        acao = show ? 'inherit' : 'none';
        for (i = 0; i < objs.length; i++) {
            objs[i].style.display = acao;
        }
    }

    function validaData(campo) {
        if (infraValidaData(campo) == false) {
            campo.value = '';
        }
    }


    function carregarDependenciaConduta() {
        objAjaxIdConduta = new infraAjaxMontarSelectDependente('txtIDNDispNormat', 'selIDNCondutas', '<?=$strLinkAjaxDependConduta?>');
        objAjaxIdConduta.prepararExecucao = function () {
            document.getElementById('selIDNCondutas').innerHTML = '';
            //objAjaxIdConduta.processarResultado();
            return infraAjaxMontarPostPadraoSelect('null', '', 'null') + '&idDispositivoNormativo=' + document.getElementById('hdnIdIDNDispNormat').value;
        }
        objAjaxIdConduta.processarResultado = function () {
            if (document.getElementById('selIDNCondutas').options.length == 0 ||
                (document.getElementById('selIDNCondutas').options.length == 1 && document.getElementById("selIDNCondutas").options[document.getElementById("selIDNCondutas").selectedIndex].text == '')) {
                document.getElementById('selIDNCondutas').style.display = 'none';
                document.getElementById('lblIDNCondutas').style.display='none';
            } else {
                document.getElementById('selIDNCondutas').style.display = '';
                document.getElementById('lblIDNCondutas').style.display='';
            }

        }
    }
    function carregarComponenteDispositivoNormativoDN() {
        //New
        objLupaIDNDispositivoNormativo = new infraLupaText('txtIDNDispNormat', 'hdnIdIDNDispNormat', '<?=$strLinkDispNormatDNSelecao?>');

        objLupaIDNDispositivoNormativo.finalizarSelecao = function () {
            objAutoCompletarIDNDispositivoNormativo.selecionar(document.getElementById('hdnIdIDNDispNormat').value, document.getElementById('txtIDNDispNormat').value);
            objAjaxIdConduta.executar();
        }

        objAutoCompletarIDNDispositivoNormativo = new infraAjaxAutoCompletar('hdnIdIDNDispNormat', 'txtIDNDispNormat', '<?=$strLinkAjaxDispNormatDNAjax?>');
        objAutoCompletarIDNDispositivoNormativo.limparCampo = false;

        objAutoCompletarIDNDispositivoNormativo.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtIDNDispNormat').value;
        };

        objAutoCompletarIDNDispositivoNormativo.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
                document.getElementById('hdnIdIDNDispNormat').value = id;
                document.getElementById('txtIDNDispNormat').value = descricao;
                objAjaxIdConduta.executar();
            }
        }
        objAutoCompletarIDNDispositivoNormativo.selecionar('<?=$strIdDispositivoNormativo?>', '<?=PaginaSEI::getInstance()->formatarParametrosJavascript($strNome);?>');
    }
    function carregarComponenteDispositivoNormativoCD() {
        objLupaICDispositivoNormativo = new infraLupaText('txtICDispNormat', 'hdnIdICDispNormat', '<?=$strLinkDispNormatCDSelecao?>');

        objLupaICDispositivoNormativo.finalizarSelecao = function () {
            objAutoCompletarICDispositivoNormativo.selecionar(document.getElementById('hdnIdICDispNormat').value, document.getElementById('txtICDispNormat').value);
        }

        objAutoCompletarICDispositivoNormativo = new infraAjaxAutoCompletar('hdnIdICDispNormat', 'txtICDispNormat', '<?=$strLinkAjaxDispNormatCDAjax?>');
        objAutoCompletarICDispositivoNormativo.limparCampo = false;

        objAutoCompletarICDispositivoNormativo.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtICDispNormat').value + '&conduta=' + document.getElementById('selICCondutas').value;
        };

        objAutoCompletarICDispositivoNormativo.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
                document.getElementById('hdnIdIDNDispNormat').value = id;
                document.getElementById('txtICDispNormat').value = descricao;
            }
        }

        objAutoCompletarICDispositivoNormativo.selecionar('<?=$strIdUnidade?>', '<?=PaginaSEI::getInstance()->formatarParametrosJavascript($strNomeRemetente);?>');
    }


    function validarCampo(obj, event, tamanho) {
        if (!somenteNumeros(event)) {
            return somenteNumeros(event)
        } else {
            return infraMascaraTexto(obj, event, tamanho);
        }
    }
    function validarCampoProcessoSei(obj, event) {
        if (!somenteNumeros(event)) {
            return somenteNumeros(event)
        } else {
            //return infraMascaraProcessoSei(obj, event);
            return infraMascara(obj, event, '#####.######/####-##');
        }
    }
    function OnSubmitForm(formulario) {
        return validarCadastro(formulario);
    }
    function validarCadastro(formulario) {

        if (infraTrim(document.getElementById('hdnListaDocInstauradores').value) == '') {
            alert('Informe o valor para Documento Instaurador do Procedimento Litigioso.');
            document.getElementById('txtNumeroSei').focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtDtInstauracao').value) == '') {
            alert('Informe o "Data da Instauração".');
            document.getElementById('txtDtInstauracao').focus();
            return false;
        } else {
            var hoje = new Date();
            var dia = '0' + hoje.getDate();
            dia = dia.substr(dia.length - 2, 2)
            var mes = '0' + (hoje.getMonth() + 1);
            mes = mes.substr(mes.length - 2, 2)
            var ano = hoje.getFullYear();
            var dataHoje = dia + '/' + mes + '/' + ano;

            if (infraCompararDatas(document.getElementById('txtDtInstauracao').value, dataHoje) < 0) {
                // [MSG11]
                alert('Data da Instauração não deve ser superior à data atual.');
                return false;
            }

        }

        if (!validarInteressado()) {
            return false;
        }

        if (<?=$bolTipoProcedimentosSobrestadosGeracao?>==0)
        {
            if (infraTrim(document.getElementById('hdnListaDIIndicados').value) == '') {
                alert('Informe ao menos um Dispositivo Normativo');
                document.getElementById('rdIndicDisposNormativo').focus();
                return false;
            }
        }
        if(<?=$bolTipoProcedimentosSobrestadosGeracao?> == 1){
            if (infraTrim(document.getElementById('hdnListaPSIndicados').value) == '') {
                alert('Informe o "Processos a serem Sobrestados".');
                document.getElementById('txtNumeroSeiPS').focus();
                return false;
            }
        }

        return true;
    }
    var arrValidaSEI = new Array();
    var arrValidaSituacaoSEI = new Array();

    function validaSEI(numeroSEI, tipo='d') {

        if (tipo == 'd') {
            arrValidaSEI["IdDocumento"] = '';
            arrValidaSEI["NumeroSei"] = '';
            arrValidaSEI["Numero"] = '';
            arrValidaSEI["SiglaUnidadeGeradoraProtocolo"] = '';
            arrValidaSEI["NomeSerie"] = '';
            arrValidaSEI["GeracaoProtocolo"] = '';
            arrValidaSEI["SinInterno"] = '';
            arrValidaSEI["Assinatura"] = '';
        } else if (tipo == 'p') {
            arrValidaSEI["NomeTipoProcedimento"] = '';
            arrValidaSEI["IdProcedimento"] = '';
        }

        //vazio
        if (numeroSEI.length == 0) return false;

        // #EU4864 - INFRAAJAX - não encontrado método que retorna somente dados, sem componentes
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxValidarSEI ?>",
            //dataType: "json",
            dataType: "xml",
            async: false,
            data: {
                numeroSEI: numeroSEI,
                tipo: tipo,
                idTipoControleLitigioso: <?= $objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso() ?>
            },
            success: function (result) {
                if (tipo == 'd') {
                    arrValidaSEI["ProtocoloProcedimentoFormatado"] = $(result).find('ProtocoloProcedimentoFormatado').text();
                    arrValidaSEI["IdDocumento"] = $(result).find('IdDocumento').text();
                    arrValidaSEI["NumeroSei"] = $(result).find('NumeroSei').text();
                    arrValidaSEI["Numero"] = $(result).find('Numero').text();
                    arrValidaSEI["SiglaUnidadeGeradoraProtocolo"] = $(result).find('SiglaUnidadeGeradoraProtocolo').text();
                    arrValidaSEI["NomeSerie"] = $(result).find('NomeSerie').text();
                    //Documento Externo - data geração
                    arrValidaSEI["GeracaoProtocolo"] = $(result).find('GeracaoProtocolo').text();
                    arrValidaSEI["SinInterno"] = $(result).find('SinInterno').text();
                    //Documento Externo - data primeira assinatura
                    arrValidaSEI["Assinatura"] = $(result).find('Assinatura').text();
                } else if (tipo == 'p') {
                    arrValidaSEI["IdProcedimento"] = $(result).find('IdProcedimento').text();
                    arrValidaSEI["NomeTipoProcedimento"] = $(result).find('NomeTipoProcedimento').text();
                    //retornou mais de uma unidade
                    var unidade = [];
                    $(result).find('unidade').each(function () {
                        unidade[unidade.length] = $(this).text();
                    });
                    arrValidaSEI["unidade"] = unidade;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                //console.log(msgCommit);
            },
            complete: function (result) {
            }
        });

        return arrValidaSEI;

    }

    function validaSituacaoSEI(numeroSEI) {
        //vazio
        if (numeroSEI.length == 0) return false;

        arrValidaSituacaoSEI = new Array();

        // #EU4864 - INFRAAJAX - não encontrado método que retorna somente dados, sem componentes
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxValidarSituacaoSEI ?>",
            dataType: "xml",
            async: false,
            data: {
                numeroSEI: numeroSEI,
                idTipoControleLitigioso: <?= $objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso() ?>
            },
            success: function (result) {
                arrValidaSituacaoSEI["NumeroSei"] = $(result).find('NumeroSei').text();
                arrValidaSituacaoSEI["StrSinInstauracao"] = $(result).find('StrSinInstauracao').text();
                arrValidaSituacaoSEI["IdSerie"] = $(result).find('IdSerie').text();
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                console.log(msgCommit);
            },
            complete: function (result) {
            }
        });
        return arrValidaSituacaoSEI;

    }

    function preencheNumeroSei(campo, mensagemMostar=true) {
        var SEIvalido = false;
        var mensagem = '';
        var arrDtAssinatura;

        document.getElementById('divTabelaDocInstaurador').className = document.getElementById('divTabelaDocInstaurador').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
        document.getElementById('divTabelaDocInstaurador2').className = document.getElementById('divTabelaDocInstaurador2').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
        mostrarTabelaDI(false);
        mostrarTabelaPS(false);

        //[RN3]	O formulário completo somente será apresentado, caso os campos “Número SEI” e o “Tipo de Documentos” seja válido.
        validaSEI(campo.value, 'd');

        if ((arrValidaSEI["ProtocoloProcedimentoFormatado"] != '' && arrValidaSEI["ProtocoloProcedimentoFormatado"] != undefined)
            && (arrValidaSEI["ProtocoloProcedimentoFormatado"] != document.getElementById('ProtocoloProcedimentoFormatado').value)
        ) {
            mensagem = 'Numero SEI de outro procedimento.'
        }

        if (arrValidaSEI["NomeSerie"] != '' && arrValidaSEI["NomeSerie"] != undefined && mensagem == '') {

            validaSituacaoSEI(campo.value);

            //[RN2]	O campo “Número do SEI” deve aceitar somente :
            //      Numero SEI dos Tipos de Documentos associados à Situação marcada como “Instauradora” para o correspondente Tipo de Controle Litigioso.
            if (arrValidaSituacaoSEI["IdSerie"] != '' && arrValidaSituacaoSEI["IdSerie"] != undefined && !isNaN(arrValidaSituacaoSEI["IdSerie"]) && arrValidaSituacaoSEI["StrSinInstauracao"] == 'S') {

                //[RN4]	Caso o Número SEI informado seja de Documento Interno, somente será validado caso o mesmo já estiver sido assinado.
                if (arrValidaSEI["SinInterno"] == 'S') {
                    if (arrValidaSEI["Assinatura"] != '' && arrValidaSEI["Assinatura"] != undefined) {
                        SEIvalido = true;
                        arrDtAssinatura = arrValidaSEI["Assinatura"].split(" ");
                    } else {
                        //[MSG9] O Número do SEI informado não possui o documento interno assinado.
                        mensagem = 'Somente é permitido adicionar Documento gerado depois que for assinado.';
                    }
                    //[RN6]	Caso o “Número SEI” informado for de um Documento Externo, trás a Data do Documento indicada em seu cadastro, campo editável.
                } else {
                    SEIvalido = true;
                }

                if (SEIvalido == true) {

                    document.getElementById('sbmAdicionarNumeroSei').className = document.getElementById('sbmAdicionarNumeroSei').className.replace('NumeroSEINaoValidado', '');
                    //[RN3]	O formulário completo somente será apresentado, caso os campos “Número SEI” e o “Tipo de Documentos” seja válido.
                    //      habilitando
//                    var elements = document.getElementsByClassName('NumeroSEINaoValidado');
//                    var iLen = elements.length;
//                    while (iLen > 0) {
//                        elements[iLen - 1].className = elements[iLen - 1].className.replace('NumeroSEINaoValidado', 'NumeroSEIValidado');
//                        elements = document.getElementsByClassName('NumeroSEINaoValidado');
//                        iLen = elements.length;
//                    }
//                    if (objTabelaDocInstaurador.tbl.rows.length==2){
//                        objTabelaDocInstaurador.removerLinha(1);
//                    }

                    if (hdnIdMdLitControle!=''){
                        mostrarTabelaDI(true);
                    }else{
                        document.getElementById('divTabelaDocInstaurador').className = document.getElementById('divTabelaDocInstaurador').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
                        document.getElementById('divTabelaDocInstaurador2').className = document.getElementById('divTabelaDocInstaurador2').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
                        mostrarTabelaDI(false);
                    }

                    document.getElementById('hdnIdDocumento').value = arrValidaSEI["IdDocumento"];
                    document.getElementById('hdnNumeroSei').value = arrValidaSEI["NumeroSei"];
                    document.getElementById('hdnNumero').value = arrValidaSEI["Numero"];
                    document.getElementById('hdnUnidade').value = arrValidaSEI["SiglaUnidadeGeradoraProtocolo"];
                    document.getElementById('hdnData').value = arrValidaSEI["GeracaoProtocolo"];
                    document.getElementById('txtTipo').value = arrValidaSEI["NomeSerie"];

                    buscarInteressado();
                    return true;

                } else {
                    limparDocInstaurador();
                }
            }else{
                mensagem = 'Este documento não está vinculado ao Tipo de Controle deste processo na situação Instauração.';
            }
            //return SEIvalido;


        } else {
            //[MSG8] Número SEI inválido.
            mensagem = 'Número SEI inválido'
            limparDocInstaurador();
        }

        if (mensagem != '' && mensagemMostar) {
            alert(mensagem);
        }

        //[RN3]	O formulário completo somente será apresentado, caso os campos “Número SEI” e o “Tipo de Documentos” seja válido.
        //      desabilitando
        var elements = document.getElementsByClassName('NumeroSEIValidado');
        var iLen = elements.length;
        while (iLen > 0) {
            elements[iLen - 1].className = elements[iLen - 1].className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
            elements = document.getElementsByClassName('NumeroSEIValidado');
            iLen = elements.length;
        }
        if (objTabelaDocInstaurador.tbl.rows.length==2){
            objTabelaDocInstaurador.removerLinha(1);
        }
        document.getElementById('divTabelaDocInstaurador').className = document.getElementById('divTabelaDocInstaurador').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
        document.getElementById('divTabelaDocInstaurador2').className = document.getElementById('divTabelaDocInstaurador2').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
        mostrarTabelaDI(false);
        document.getElementById('txtTipo').value = '';
        document.getElementById('txtDtInstauracao').value = '';

        //[RN7]	Situação "Instauradora" - "Não exige Data da Intimação da Instauração do Procedimento" => "Data de Intimação" - obrigatório
//        document.getElementById('lblDtIntimacao').className = document.getElementById('lblDtIntimacao').className.replace('infraLabelObrigatorio', 'infraLabelOpcional');

    }
    function preencheNumeroSeiPS(campo) {
        var SEIvalido = false;
        var mensagem = '';
        var arrDtAssinatura;

        //[RN3]	O formulário completo somente será apresentado, caso os campos “Número SEI” e o “Tipo de Documentos” seja válido.
        validaSEI(campo.value, 'd');

        if (arrValidaSEI["NomeSerie"] != '' && arrValidaSEI["NomeSerie"] != undefined) {

            validaSituacaoSEI(campo.value);

            //[RN2]	O campo “Número do SEI” deve aceitar somente :
            //      Numero SEI dos Tipos de Documentos associados à Situação marcada como “Instauradora” para o correspondente Tipo de Controle Litigioso.
            if (arrValidaSituacaoSEI["IdSerie"] != '' && arrValidaSituacaoSEI["IdSerie"] != undefined && !isNaN(arrValidaSituacaoSEI["IdSerie"]) && arrValidaSituacaoSEI["StrSinInstauracao"] == 'S') {

                var txtDtSobrestamentoPS = '';

                //[RN4]	Caso o Número SEI informado seja de Documento Interno, somente será validado caso o mesmo já estiver sido assinado.
                if (arrValidaSEI["SinInterno"] == 'S') {
                    if (arrValidaSEI["Assinatura"] != '' && arrValidaSEI["Assinatura"] != undefined) {
                        arrDtAssinatura = arrValidaSEI["Assinatura"].split(" ");
                        if (arrDtAssinatura.length > 0) {
                            txtDtSobrestamentoPS = arrDtAssinatura[0];
                        } else {
                            txtDtSobrestamentoPS = arrValidaSEI["Assinatura"];
                        }
                        SEIvalido = true;
                    } else {
                        //[MSG9] O Número do SEI informado não possui o documento interno assinado.
                        mensagem = 'O Número do SEI informado não possui o documento interno assinado';
                    }
                    //[RN6]	Caso o “Número SEI” informado for de um Documento Externo, trás a Data do Documento indicada em seu cadastro, campo editável.
                } else {
                    txtDtSobrestamentoPS = arrValidaSEI["GeracaoProtocolo"];
                    SEIvalido = true;
                }

                if (SEIvalido == true) {
                    document.getElementById('hdnIdnumeroSeiPS').value = arrValidaSEI["IdDocumento"];
                    document.getElementById('hdnNumeroSeiPS').value = arrValidaSEI["NumeroSei"];
                    document.getElementById('txtNumeroSeiTipoPS').value = arrValidaSEI["NomeSerie"];
                    document.getElementById('txtDtSobrestamentoPS').value = txtDtSobrestamentoPS;
                } else {
                    document.getElementById('hdnIdnumeroSeiPS').value = "";
                    document.getElementById('hdnNumeroSeiPS').value = "";
                    document.getElementById('txtNumeroSeiTipoPS').value = "";
                    document.getElementById('txtDtSobrestamentoPS').value = "";
                }

            }

        } else {
            //[MSG8] Número SEI inválido.
            mensagem = 'Número SEI inválido'
            document.getElementById('hdnIdnumeroSeiPS').value = "";
            document.getElementById('hdnNumeroSeiPS').value = "";
            document.getElementById('txtNumeroSeiTipoPS').value = "";
            document.getElementById('txtDtSobrestamentoPS').value = "";
        }

        if (mensagem != '') {
            alert(mensagem);
        }

        return SEIvalido;

    }
    function preencheNumeroProcessoPS(campo) {
        validaSEI(campo.value, 'p');

        // procedimento existe
        if (arrValidaSEI["NomeTipoProcedimento"] != '' && arrValidaSEI["NomeTipoProcedimento"] != undefined) {
            document.getElementById('hdnIdProcedimentoPS').value = arrValidaSEI["IdProcedimento"];
            document.getElementById('txtNumeroProcessoTipoPS').value = arrValidaSEI["NomeTipoProcedimento"];
            document.getElementById('hdnNumeroProcessoPS').value = arrValidaSEI["NumeroSei"];
            if (Array.isArray(arrValidaSEI["unidade"])) {
                document.getElementById('hdnUnidadeProcessoPS').value = arrValidaSEI["unidade"].toString();
            } else {
                document.getElementById('hdnUnidadeProcessoPS').value = '';
            }
        } else {
            document.getElementById('hdnIdProcedimentoPS').value = '';
            document.getElementById('txtNumeroProcessoTipoPS').value = '';
            document.getElementById('hdnNumeroProcessoPS').value = '';
            document.getElementById('hdnUnidadeProcessoPS').value = '';
        }
    }


    // PS - funcionalidades
    objTabelaPS = new infraTabelaDinamica('tbProcessosSobrestados', 'hdnListaPSIndicados', false, false);
    objTabelaPS.gerarEfeitoTabela = true;
    objTabelaPS.inserirNoInicio = false;
    objTabelaPS.exibirMensagens = true;

    objTabelaPS.alterar = function (arr) {
        return false;
        // não existe alteração?
        ////document.getElementById('hdnListaPSIndicados').value = arr[0];
        //objTabelaPS.removerLinha(objTabelaPS.procuraLinha(arr[0]));
        //document.getElementById('txtNumeroSeiPS').value = arr[4];
        //document.getElementById('hdnNumeroSeiPS').value = arr[4];
        //document.getElementById('txtDtSobrestamentoPS').value = arr[3];
        //document.getElementById('txtNumeroProcessoPS').value = arr[1];
        //document.getElementById('hdnNumeroProcessoPS').value = arr[1];
        //document.getElementById('txtNumeroProcessoTipoPS').value = arr[2];
        //document.getElementById('hdnIdnumeroSeiPS').value = arr[2];
    };

    objTabelaPS.remover = function (arr) {
        if (objTabelaPS.tbl.rows.length==2){
            mostrarTabelaPS(false);
        }
        return true;
    }

    objTabelaPS.procuraLinha = function (id) {
        var qtd;
        var linha;
        qtd = document.getElementById('tbProcessosSobrestados').rows.length;
        for (i = 1; i < qtd; i++) {
            linha = document.getElementById('tbProcessosSobrestados').rows[i];
            if (linha.cells[0].innerText == id) {
                return i;
            }
        }
        return null;
    };

    
    function adicionarPS() {
        //var id = document.getElementById('txtNumeroProcessoPS').value+document.getElementById('txtNumeroSeiPS').value;
        var id = document.getElementById('txtNumeroProcessoPS').value;
        var Unidade = document.getElementById('hdnUnidadeProcedimento').value;
        var IdProcedimentoPS = document.getElementById('hdnIdProcedimentoPS').value;
        var numeroSeiPS = document.getElementById('txtNumeroSeiPS').value;
        var numeroSeiPSValidado = document.getElementById('hdnNumeroSeiPS').value;
        var dtSobrestamentoPS = document.getElementById('txtDtSobrestamentoPS').value;
        var numeroProcessoPS = document.getElementById('txtNumeroProcessoPS').value;
        var UnidadeProcessoPS = document.getElementById('hdnUnidadeProcessoPS').value;
        var UnidadeProcessoPSValidado = false;
        var arrUnidadeProcessoPS;
        var numeroProcessoTipoPS = document.getElementById('txtNumeroProcessoTipoPS').value;
        var IdnumeroSeiPS = document.getElementById('hdnIdnumeroSeiPS').value;

        if (Unidade == '' || UnidadeProcessoPS == '') {
            return false;
        } else {
            arrUnidadeProcessoPS = UnidadeProcessoPS.split(",");
            var qtdUnidadeProcessoPS = arrUnidadeProcessoPS.length;
            if (qtdUnidadeProcessoPS == 0 || qtdUnidadeProcessoPS > 1) {
                alert('O processo a ser sobrestado (' + UnidadeProcessoPS + ') deve estar aberto somente nesta unidade (' + Unidade + ')');
                return false;
            } else {
                //Por enquanto compara somente uma
                //for (i=0;i<qtdUnidadeProcessoPS;i++) {
                //if (arrUnidadeProcessoPS[i]==Unidade){
                if (arrUnidadeProcessoPS[0] == Unidade) {
                    UnidadeProcessoPSValidado = true;
                }
                //}
                if (!UnidadeProcessoPSValidado) {
                    alert('O processo a ser sobrestado (' + UnidadeProcessoPS + ') deve estar aberto somente nesta unidade (' + Unidade + ')');
                    return false;
                }
            }
        }

        if ((numeroProcessoPS == '' || numeroProcessoTipoPS == '' || dtSobrestamentoPS == '' || numeroSeiPS == '' || IdnumeroSeiPS == '')
            || (numeroSeiPS != numeroSeiPSValidado)) {
            return false;
        }

        var arrDadosPSValido = [];

        arrDadosPSValido[0] = id;
        arrDadosPSValido[1] = IdProcedimentoPS;
        arrDadosPSValido[2] = numeroProcessoPS;
        arrDadosPSValido[3] = numeroProcessoTipoPS;
        arrDadosPSValido[4] = dtSobrestamentoPS;
        arrDadosPSValido[5] = numeroSeiPS;
        arrDadosPSValido[6] = IdnumeroSeiPS;

        var bolPSCustomizado = hdnCustomizado;

        receberPS(arrDadosPSValido, bolPSCustomizado);
    }

    function receberPS(arrDadosPS, PSCustomizado) {
        var qtdPSIndicados = objTabelaPS.tbl.rows.length;
        objTabelaPS.adicionar([arrDadosPS[0],
            arrDadosPS[1],
            arrDadosPS[2],
            arrDadosPS[3],
            arrDadosPS[4],
            arrDadosPS[5],
            arrDadosPS[6],
            '']);
        //Linha adicionada, adiciona as ações
        if (qtdPSIndicados < objTabelaPS.tbl.rows.length) {
            if (PSCustomizado != "") {
                objTabelaPS.adicionarAcoes(arrDadosPS[0], '', false, true);
            }
        }

        mostrarTabelaPS(true);

        infraEfeitoTabelas();

    }

    function mostrarTabelaPS(opcao) {
        var qtdPSIndicados = objTabelaPS.tbl.rows.length;
        if (opcao){
            if (qtdPSIndicados>1){
                document.getElementById('tbProcessosSobrestados').style.display='';
            }
        }else{
            document.getElementById('tbProcessosSobrestados').style.display='none';
        }
    }
    
    // PS - funcionalidades - FIM


    // Infração - funcionalidades
    objTabelaDI = new infraTabelaDinamica('tbDispositivosInfrigidos', 'hdnListaDIIndicados', true, true);
    objTabelaDI.gerarEfeitoTabela = true;
    objTabelaDI.inserirNoInicio = false;
    objTabelaDI.exibirMensagens = true;

    objTabelaDI.alterar = function (arr) {
        document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value = arr[0];
//        objTabelaDI.removerLinha(objTabelaDI.procuraLinha(arr[1])); por causa da validação de registro vinculado.
        objTabelaDI.tbl.deleteRow(objTabelaDI.procuraLinha(arr[1]));
        objTabelaDI.atualizaHdn();
        if(arr[5] != ''){
            document.getElementById('rdIndicConduta').checked = true;
            changeInfracoes();
            document.getElementById('txtICDispNormat').value = arr[2]+' - '+arr[4];
            document.getElementById('hdnIdICDispNormat').value = arr[3];
            document.getElementById('selICCondutas').value = arr[5];
            document.getElementById('divDispositivoPorConduta').style.display = ''
        }else{
            document.getElementById('rdIndicDisposNormativo').checked = true;
            changeInfracoes();
            document.getElementById('txtIDNDispNormat').value = arr[2]+' - '+arr[4];
            document.getElementById('hdnIdIDNDispNormat').value = arr[3];
        }
    };

    objTabelaDI.remover = function (arr) {
        if(arr[0].toString().indexOf("novo_") == -1){
            var retorno = true;
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxExisteInfracao ?>",
                //dataType: "json",
                dataType: "xml",
                async: false,
                data: {
                    id_md_lit_rel_dis_nor_con_ctr: arr[0].toString()
                },
                success: function (result) {
                    if ($(result).find('existeInfracao').attr('value') == 'S') {
                        retorno = false;
                    }
                },
                error: function (msgError) {
                    msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                    alert(msgCommit);
                },
                complete: function (result) {
                }
            });
        }
        if(!retorno ){
            alert('A exclusão da infração não é permitida, pois existem registros vinculados!');
            return retorno;
        }

        if (objTabelaDI.tbl.rows.length==2){
            mostrarTabelaDI(false);
        }
        return true;
    }

    objTabelaDI.procuraLinha = function (id) {
        var qtd;
        var linha;
        qtd = objTabelaDI.tbl.rows.length;
        for (i = 1; i < qtd; i++) {
            linha = objTabelaDI.tbl.rows[i];
            if (linha.cells[1].innerText == id) {
                return i;
            }
        }
        return null;
    };


    function adicionarDI() {
        var id = '';
        var arrDI;
        var norma = '';
        var dispositivo = '';
        var dispositivoid = '';
        var conduta = '';
        var condutaid = '';
        var numRow = objTabelaDI.tbl.rows.length+1;
        var idDispositivoNormativoNormaCondutaControle = document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value == '' ? 'novo_'+numRow :document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value;

        // Dispositivo Normativo
        if (document.getElementById('rdIndicDisposNormativo').checked) {
            arrDI = document.getElementById('txtIDNDispNormat').value.split(' - ');

            if (arrDI.length > 1) {
                norma = arrDI[0];
                dispositivo = arrDI[1];
            }
            dispositivoid = document.getElementById("hdnIdIDNDispNormat").value;

            if(document.getElementById("selIDNCondutas").options.length > 1 && document.getElementById("selIDNCondutas").value == ''){
                alert('A conduta é obrigatório!');
                return false;
            }

            conduta = document.getElementById("selIDNCondutas").value !=''? document.getElementById("selIDNCondutas").options[document.getElementById("selIDNCondutas").selectedIndex].text:'';
            condutaid = document.getElementById("selIDNCondutas").value;
        // Conduta
        } else if (document.getElementById('rdIndicConduta').checked) {
            arrDI = document.getElementById('txtICDispNormat').value.split(' - ');
            if (arrDI.length == 2) {
                norma = arrDI[0];
                dispositivo = arrDI[1];
            }
            dispositivoid = document.getElementById("hdnIdICDispNormat").value;

            conduta = document.getElementById("selICCondutas").options[document.getElementById("selICCondutas").selectedIndex].text;
            condutaid = document.getElementById("selICCondutas").value;

            if(condutaid == ''){
                alert('A conduta é obrigatório!');
                return false;
            }
        }

        // Somente Dispositivo e Norma são obrigatórios
        if (norma == '' || dispositivo == '' || dispositivoid == '' /*|| conduta=='' || condutaid==''*/) {
            alert('O dispositivo normativo é obrigatório!');
            return false;
        }

        //verificar se a infração já existe na tabela
        var arrItens = objTabelaDI.obterItens();
        if(arrItens.length > 0){
            for(var i = 0; i < arrItens.length; i++){
                if(arrItens[i][3] == dispositivoid && arrItens[i][5] == condutaid){
                    alert('Essa infração já foi adicionado!');
                    return false;
                }

            }
        }

        var arrDadosDIValido = [];

        arrDadosDIValido[0] = idDispositivoNormativoNormaCondutaControle;
        arrDadosDIValido[1] = dispositivoid + '-' + condutaid;
        arrDadosDIValido[2] = norma;
        arrDadosDIValido[3] = dispositivoid;
        arrDadosDIValido[4] = dispositivo;
        arrDadosDIValido[5] = condutaid;
        arrDadosDIValido[6] = conduta;

        var bolDICustomizado = hdnCustomizado;


        receberDI(arrDadosDIValido, bolDICustomizado);
        document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value = '';
        document.getElementById('divDispositivoPorConduta').style.display = 'none';
    }

    function receberDI(arrDadosDI, DICustomizado) {
        var qtdPSIndicados = objTabelaDI.tbl.rows.length;
        objTabelaDI.adicionar([arrDadosDI[0],
            arrDadosDI[1],
            arrDadosDI[2],
            arrDadosDI[3],
            arrDadosDI[4],
            arrDadosDI[5],
            arrDadosDI[6],
            '']);

        //Linha adicionada, adiciona as ações
        //if (qtdPSIndicados < objTabelaDI.tbl.rows.length){
        //	objTabelaDI.adicionarAcoes(arrDadosDI[0], "", true, true);
        //}

        //Se existe registro, mostra tabela
        //if (objTabelaDI.tbl.rows.length>1){
        //	objTabelaDI.tbl.style.display='';
        //}

        //limpa os campos:
        document.getElementById('selICCondutas').value = '';
        document.getElementById('txtICDispNormat').value = '';
        document.getElementById('selIDNCondutas').value = '';
        document.getElementById("hdnIdIDNDispNormat").value = '';
        document.getElementById('txtIDNDispNormat').value = '';
        objAjaxIdConduta.executar();

        mostrarTabelaDI(true);

        infraEfeitoTabelas();

    }

    function mostrarTabelaDI(opcao) {
        var qtdDIIndicados = objTabelaDI.tbl.rows.length;
        if (opcao){
            if (qtdDIIndicados>1){
                document.getElementById('tbDispositivosInfrigidos').style.display='';
            }
        }else{
            document.getElementById('tbDispositivosInfrigidos').style.display='none';
        }
    }


    function changeInfracoes() {
        var dp = document.getElementsByName('rdInfracoes[]')[0].checked;

        //document.getElementById('divTabelaCondDN').style.display = 'none';

        if (dp) {
            showHide(true, 'classDispositivoNormativo');
            showHide(false, 'classCondutas');
        } else {
            showHide(false, 'classDispositivoNormativo');
            showHide(true, 'classCondutas');
        }
    }
    // Infração - funcionalidades - FIM


    //Doc Instaurador - funcionalidades
    objTabelaDocInstaurador = new infraTabelaDinamica('tbDocInstaurador', 'hdnListaDocInstauradores', true, true);
    objTabelaDocInstaurador.gerarEfeitoTabela = true;
    //objTabelaDocInstaurador.inserirNoInicio = false;
    //objTabelaDocInstaurador.exibirMensagens=true;

    objTabelaDocInstaurador.alterar = function (arr) {
        //objTabelaDocInstaurador.removerLinha(objTabelaDocInstaurador.procuraLinha(arr[0]));
        //document.getElementById('hdnListaDIIndicados').value = arr[0];
        document.getElementById('hdnIdDocumento').value = arr[1];
        document.getElementById('txtNumeroSei').value = arr[2];
        document.getElementById('hdnNumeroSei').value = arr[2];
        document.getElementById('hdnNumero').value = arr[3];
        document.getElementById('txtTipo').value = arr[4];
        document.getElementById('hdnUnidade').value = arr[6];
        document.getElementById('hdnData').value = arr[7];

        //Habilitando
        document.getElementById('txtNumeroSei').disabled = false;
        document.getElementById('sbmValidarNumeroSei').disabled = false;
        document.getElementById('sbmAdicionarNumeroSei').disabled = false;
    };

    objTabelaDocInstaurador.remover = function () {
        document.getElementById('txtDtInstauracao').value = '';
        return true;
    };

    objTabelaDocInstaurador.procuraLinha = function (id) {
        var qtd;
        var linha;
        qtd = objTabelaDocInstaurador.tbl.rows.length;
        for (i = 1; i < qtd; i++) {
            linha = objTabelaDocInstaurador.tbl.rows[i];
            if (linha.cells[0].innerText == id) {
                return i;
            }
        }
        return null;
    };

    function adicionarDocInstaurador() {
        //var id = document.getElementById('txtNumeroProcessoPS').value+document.getElementById('txtNumeroSeiPS').value;
        var IdDocumento = document.getElementById('hdnIdDocumento').value;
        var txtNumeroSei = document.getElementById('txtNumeroSei').value;
        var NumeroSei = document.getElementById('hdnNumeroSei').value;
        var Tipo = document.getElementById('txtTipo').value;
        var Numero = document.getElementById('hdnNumero').value;
        var Unidade = document.getElementById('hdnUnidade').value;
        var Data = document.getElementById('hdnData').value;

        if (NumeroSei == '' || Tipo == '' || Unidade == '' || Data == '') {
            return false;
        }

        //Validado
        if (txtNumeroSei != NumeroSei) {
            return false;
        }

        var arrDadosValido = [];

        arrDadosValido[0] = 1;
        arrDadosValido[1] = IdDocumento;
        arrDadosValido[2] = NumeroSei;
        arrDadosValido[3] = Numero;
        arrDadosValido[4] = Tipo;
        arrDadosValido[5] = Tipo + ' nº ' + Numero + ' (' + NumeroSei + ')';
        arrDadosValido[6] = Unidade;
        arrDadosValido[7] = Data;

        var bolCustomizado = hdnCustomizado;

        receberDocInstaurador(arrDadosValido, bolCustomizado);

        //Datas
        document.getElementById('txtDtInstauracao').value = Data;

        //Limpando
        limparDocInstaurador();

        //document.getElementById('divTabelaDocInstaurador').style.display = '';
        document.getElementById('divTabelaDocInstaurador').className = document.getElementById('divTabelaDocInstaurador').className.replace('NumeroSEINaoValidado', 'NumeroSEIValidado');
        document.getElementById('divTabelaDocInstaurador2').className = document.getElementById('divTabelaDocInstaurador2').className.replace('NumeroSEINaoValidado', 'NumeroSEIValidado');
        
        //Desabilitando
        document.getElementById('txtNumeroSei').disabled=true;
        document.getElementById('sbmValidarNumeroSei').disabled=true;
        document.getElementById('sbmAdicionarNumeroSei').disabled=true;

        //aparecer todos os fieldset do Litigioso
        var elements = document.getElementsByClassName('NumeroSEINaoValidado');
        var iLen = elements.length;
        while (iLen > 0) {
            elements[iLen - 1].className = elements[iLen - 1].className.replace('NumeroSEINaoValidado', 'NumeroSEIValidado');
            elements = document.getElementsByClassName('NumeroSEINaoValidado');
            iLen = elements.length;
        }

    }

    function receberDocInstaurador(arrDados, Customizado) {
        var qtd = objTabelaDocInstaurador.tbl.rows.length;
        objTabelaDocInstaurador.adicionar([arrDados[0],
            arrDados[1],
            arrDados[2],
            arrDados[3],
            arrDados[4],
            arrDados[5],
            arrDados[6],
            arrDados[7],
            '']);
        //Linha adicionada, adiciona as ações
        //if (qtd < objTabelaDocInstaurador.tbl.rows.length){
        //	objTabelaDocInstaurador.adicionarAcoes(arrDados[0], "", true, true);
        //}

        //Se existe registro, mostra tabela
        //if (objTabelaDocInstaurador.tbl.rows.length>1){
        //	objTabelaDocInstaurador.tbl.style.display='';
        //}

        infraEfeitoTabelas();

    }

    function limparDocInstaurador(NumeroSEILimpar=true) {
        document.getElementById('hdnIdDocumento').value = '';
        if (NumeroSEILimpar) {
            document.getElementById('txtNumeroSei').value = '';
        }
        document.getElementById('hdnNumeroSei').value = '';
        document.getElementById('txtTipo').value = '';
        document.getElementById('hdnNumero').value = '';
        document.getElementById('hdnUnidade').value = '';
        document.getElementById('hdnData').value = '';
    }

    function removerValidacaoDocInstaurador() {
        if (document.getElementById('txtNumeroSei').value != document.getElementById('hdnNumeroSei').value) {
            limparDocInstaurador(false);
        }
    }
    //Doc Instaurador - funcionalidades - FIM


    function removerSobrestamentoProcesso(id, IdProcedimentoPS) {
        if (confirm('Confirma remoção de sobrestamento do processo?')) {
            // #EU4864 - INFRAAJAX - não encontrado método que retorna somente dados, sem componentes
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxRemoverSobrestamento ?>",
                //dataType: "json",
                dataType: "xml",
                async: false,
                data: {
                    id_procedimento: IdProcedimentoPS
                },
                success: function (result) {
                    if ($(result).find('erros').find('erro').attr('descricao') != undefined) {
                        msgCommit = $(result).find('erros').find('erro').attr('descricao');
                    } else if ($(result).find('documento').find('mensagem').text() != undefined) {
                        msgCommit = $(result).find('documento').find('mensagem').text();
                        if ($(result).find('documento').find('mensagemtipo').text()=='sucesso'){
                            objTabelaPS.removerLinha(objTabelaPS.procuraLinha(id));
                        }
                    }
                    alert(msgCommit);
                },
                error: function (msgError) {
                    msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                    alert(msgCommit);
                },
                complete: function (result) {
                }
            });
        }
    }

    function iniciarTabelaInteressado() {
        objTabelaInteressado = new infraTabelaDinamica('tbInteressado', 'hdnTbInteressado', false, false);
        objTabelaInteressado.gerarEfeitoTabela = true;
        window.setInterval(verificarModalInteressado, 1000);

        objTabelaInteressado.remover = function (arr) {
            if (confirm('Deseja remover o Interessado: ' + arr[8] + '?')) {
                return true;
            }
        }

    }

    function adicionarInteressado(contato) {
        objTabelaInteressado.adicionar([
            contato.idContato,
            contato.urlAlterar,
            contato.staNatureza,
            contato.endereco,
            contato.bairro,
            contato.idCidade,
            contato.idUf,
            contato.nomeTipoContato,
            contato.nomeContato,
            contato.cpfCnpj,
            contato.paramModal
        ]);


        var acaoDadosComplementares = '';

        var dadoCompleto = contato.staNatureza != '' && contato.endereco != '' &&
            contato.bairro != '' && contato.idCidade != '' && contato.idUf != '' && contato.cpfCnpj != '' && contato.paramModal == 'S';

        if (dadoCompleto) {
            acaoDadosComplementares = "<img onclick=\"abrirModalDadosInteressado('" + contato.urlDadosComplementares + "')\" " +
                "style='width: 16px; height: 16px;' title='Dados Complementares do Interessado' src='imagens/juntar_documento.gif' class='infraImg'/>&nbsp;";
        }

        var acaoAlterar = "<img onclick=\"alterarInteressado('" + contato.idContato + "','" + contato.urlAlterar + "')\" " +
            "style='width: 16px; height: 16px;' title='Alterar Interessado' src='imagens/alterar.gif' class='infraImg'/>&nbsp;";

        objTabelaInteressado.adicionarAcoes(contato.idContato, acaoAlterar + acaoDadosComplementares, false, true);
    }

    function buscarInteressado(arrIdContato) {
        var hdnIdProcedimento = document.getElementById('hdnIdProcedimento');
        var hdnIdMdLitControle = document.getElementById('hdnIdMdLitControle');
        var hdnIdMdLitTipoControle = document.getElementById('hdnIdMdLitTipoControle');

        var divInteressados = document.getElementById('divInteressados');
        var hdnAbriuModalInteressado = document.getElementById('hdnAbriuModalInteressado');

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxBuscarInteressado ?>",
            dataType: "XML",
            beforeSend: function () {
                objTabelaInteressado.limpar();
                hdnAbriuModalInteressado.value = '0';
            },
            data: {
                idProcedimento: hdnIdProcedimento.value,
                idMdLitControle: hdnIdMdLitControle.value,
                idMdLitTipoControle: hdnIdMdLitTipoControle.value,
                arrIdContato: arrIdContato
            },
            success: function (r) {
                if ($('Contato', r).text() != '') {
                    divInteressados.style.display = '';
                    convertXmlToContato(r);
                }

            },
            error: function (err) {
                console.error("Erro ao processar o XML do SEI: " + err.responseText);
            }
        });
    }

    function buscarInteressadoValidacao() {
        var hdnIdProcedimento = document.getElementById('hdnIdProcedimento');
        var hdnIdMdLitControle = document.getElementById('hdnIdMdLitControle');
        var hdnIdMdLitTipoControle = document.getElementById('hdnIdMdLitTipoControle');


        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxBuscarInteressado ?>",
            dataType: "XML",
            data: {
                idProcedimento: hdnIdProcedimento.value,
                idMdLitControle: hdnIdMdLitControle.value,
                idMdLitTipoControle: hdnIdMdLitTipoControle.value,
            },
            success: function (r) {
                if ($('Contato', r).text() == '' &&  $('SinParamModal', r).text() == 'S') {
                    alert('É necessária a indicação de ao menos um Interessado de tipo diferente de: Órgãos, Sistemas e Temporário');
                    parent.document.getElementById('ifrVisualizacao').src = "<?= $strLinkAlteraProcesso?>";
                }

            },
            error: function (err) {
                console.error("Erro ao processar o XML do SEI: " + err.responseText);
            }
        });
    }

    function convertXmlToContato(r) {
        $('Contato', r).each(function () {
            var contato = {};
            contato.urlAlterar = $('UrlAlterar', this).text();
            contato.idContato = $('IdContato', this).text();
            contato.nomeTipoContato = $('NomeTipoContato', this).text();
            contato.nomeContato = $('NomeContato', this).text();
            contato.cpfCnpj = '';
            if ($('Cpf', this).text() != '') {
                contato.cpfCnpj = infraFormatarCpf($('Cpf', this).text());
            } else if ($('Cnpj', this).text() != '') {
                contato.cpfCnpj = infraFormatarCnpj($('Cnpj', this).text());
            }

            contato.urlDadosComplementares = $('UrlDadosComplementares', this).text();
            contato.staNatureza = $('StaNatureza', this).text();
            contato.endereco = $('Endereco', this).text();
            contato.bairro = $('Bairro', this).text();
            contato.idCidade = $('IdCidade', this).text();
            contato.idUf = $('IdUf', this).text();
            contato.paramModal = $('SinParamModal', r).text();
            adicionarInteressado(contato);
        });
    }

    function alterarInteressado(id, url) {
        var frm = document.getElementById('frmAlterarContato');
        var hdnContatoIdentificador = document.getElementById('hdnContatoIdentificador');
        var hdnContatoObject = document.getElementById('hdnContatoObject');
        var hdnAbriuModalInteressado = document.getElementById('hdnAbriuModalInteressado').value = '1';

        frm.action = url;
        hdnContatoObject.value = 'x';
        hdnContatoIdentificador.value = id;

        infraAbrirJanela(url,
            'alterarContato',
            780,
            600);
        frm.submit();
    }

    function abrirModalDadosInteressado(url) {
        infraAbrirJanela(url,
            'alterarContato',
            780,
            600);
    }

    function verificarModalInteressado() {
        var modalFundo = parent.document.getElementById('divInfraModalFundo');
        var hdnAbriuModalInteressado = document.getElementById('hdnAbriuModalInteressado');
        var modalInvisivel = modalFundo != null && modalFundo.getAttribute('style').indexOf('hidden') >= 0;

        if (modalInvisivel && hdnAbriuModalInteressado.value == '1') {
            var tbInteressado = document.getElementById('tbInteressado');
            var trs = tbInteressado.rows;

            var arrIdContato = [];
            for (var i = 1; i < trs.length; i++) {
                var tds = trs[i].getElementsByTagName('td');
                arrIdContato.push(tds[0].innerText.trim());
            }
            buscarInteressado(arrIdContato);
        }

    }

    function validarInteressado() {
        var tbInteressado = document.getElementById('tbInteressado');
        var trs = tbInteressado.rows;
        var valido = true;
        var msg = '';
        var msgDadoComplementar = '';
        var dados;
        for (var i = 1; i < trs.length; i++) {
            var tds = trs[i].getElementsByTagName('td');
            dados = [];
            if (tds[2].innerText.trim() == '') { //Natureza
                dados.push('Natureza');
                valido = false;
            }

            if (tds[3].innerText.trim() == '') { //Endereço
                dados.push('Endereço');
                valido = false;
            }

            if (tds[4].innerText.trim() == '') { //Bairro
                dados.push('Bairro');
                valido = false;
            }

            if (tds[4].innerText.trim() == '') { //Bairro
                dados.push('Cidade');
                valido = false;
            }

            if (tds[4].innerText.trim() == '') { //Cep
                dados.push('Cep');
                valido = false;
            }

            if (tds[9].innerText.trim() == '') { //CPF/CNPJ
                if (tds[2].innerText.trim() == 'J') {
                    dados.push('CNPJ');
                } else if (tds[2].innerText.trim() == 'F') {
                    dados.push('CPF');
                } else {
                    dados.push('CNPJ/CPF');
                }
                valido = false;
            }
            //caso a modal de Dados Complementares tenha sido configurada para apresentar, então informar os dados dela é obrigatório.
            if(tds[10].innerText.trim() == 'S' && document.getElementById('hdnIdMdLitControle').value == ''){
                var hdnTbDadoInteressado = document.getElementById('hdnTbDadoInteressado_'+tds[0].innerText.trim());
                if(hdnTbDadoInteressado == null || hdnTbDadoInteressado.value == ''){
                    msgDadoComplementar += '\n -'+ tds[8].innerText.trim();
                }
            }

            if(dados.length > 0){
                msg += 'Por favor preencha os dados do Interessado ' + tds[8].innerText.trim() + '\n';
                msg += ' - ' + dados.join('\n - ') + '\n\n';
            }
        }

        if (!valido) {
            alert(msg);
        }
        if(msgDadoComplementar != ''){
            msg = 'Por favor preencha os dados dados complementares do interessado: \n'+msgDadoComplementar;
            valido = false;
            alert(msg);
        }

        return valido;

    }

    function mostrarDispositivoPorConduta(){
        element = document.getElementById('selICCondutas');
        if(element.value == '' ||element.value == 'null' ){
            document.getElementById('divDispositivoPorConduta').style.display = 'none';
        }else{
            document.getElementById('divDispositivoPorConduta').style.display = '';
        }
    }

</script>