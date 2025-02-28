<script type="text/javascript" charset="iso-8859-1" src="/../../../infra_js/InfraUtil.js"></script>
<script type="text/javascript">

    var hdnIdMdLitControle = '<?= $hdnIdMdLitControle ?>';
    var arrIdsExcluidosInflacao = new Array();
    var objLupaMotivos = null;
    var objAutoCompletarMotivos = null;

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

    //function para impedir o enter de submit o formulrio
    function enterValidarDocumento(e) {
        if (e && e.keyCode == 13) {
            document.getElementById('sbmValidarNumeroSei').onclick();
            return false;
        }
    }

    function enterAdicionarInfracao(e) {
        if (e && e.keyCode == 13) {
            adicionarDI();
            return false;
        }
    }

    function enterValidarDocumentoSobrestado(e) {
        if (e && e.keyCode == 13) {
            preencheNumeroSeiPS(document.getElementById('txtNumeroSeiPS'));
            return false;
        }
    }

    function enterValidarProcessoSobrestado(e) {
        if (e && e.keyCode == 13) {
            preencheNumeroProcessoPS(document.getElementById('txtNumeroProcessoPS'));
            return false;
        }
    }

    function enterAdicionarSobrestado(e) {
        if (e && e.keyCode == 13) {
            adicionarPS();
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
         arrhdnListaPSIndicados = hdnListaPSIndicados.split('');

         //array
         if (arrhdnListaPSIndicados.length>0){
         for (i=0; i<arrhdnListaPSIndicados.length; i++) {
         hdnListaPSIndicado = arrhdnListaPSIndicados[i].split('');
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
             arrhdnListaDIIndicados = hdnListaDIIndicados.split('');

             //array
             if (arrhdnListaDIIndicados.length>0){
             for (i=0; i<arrhdnListaDIIndicados.length; i++) {
             hdnListaDIIndicados = arrhdnListaDIIndicados[i].split('');
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
            arrhdnListaPSIndicados = hdnListaPSIndicados.split('');

            //array
            if (arrhdnListaPSIndicados.length > 0) {
                for (i = 0; i < arrhdnListaPSIndicados.length; i++) {
                    hdnListaPSIndicado = arrhdnListaPSIndicados[i].split('');
                    objTabelaPS.adicionarAcoes(hdnListaPSIndicado[0], "<img onclick=\"javascript:removerSobrestamentoProcesso('" + hdnListaPSIndicado[0] + "'," + hdnListaPSIndicado[1] + ")\" title='Remover Sobrestamento' alt='Remover Sobrestamento' src='imagens/sei_remover_sobrestamento_processo_pequeno.gif' class='infraImg'/>", false, false);
                }
            } else {
                hdnListaPSIndicado = hdnListaPSIndicados.split('');
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
        } else {
            document.getElementById('txtNumeroSei').focus();
        }

        //registro existente Processos a serem Sobrestados
        if (document.getElementById('txtNumeroSeiPS').value != '') {
            document.getElementById('sbmValidarNumeroSeiPS').onclick();
        }
        //infraEfeitoTabelas();

        //redimensiona o iframe
        //autoResize('ifrVisualizacao');
        if ('<?=$_GET['acao']?>' == 'md_lit_processo_cadastro_consultar') {
            infraDesabilitarCamposDiv(document.getElementById('frmCadastroProcesso'))
        }

        aplicarMultipleSelectConfig();
        organizarCamposPorLinha();
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
            else return false;
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
                document.getElementById('lblIDNCondutas').style.display = 'none';
            } else {
                document.getElementById('selIDNCondutas').style.display = '';
                document.getElementById('lblIDNCondutas').style.display = '';
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
        objAutoCompletarIDNDispositivoNormativo.tamanhoMinimo = 3;
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
        objAutoCompletarICDispositivoNormativo.tamanhoMinimo = 3;
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
        if (!validarInformacoesAdicionais()) {
            return false;
        }
        if (validarCadastro(formulario)) {
            //exibe o aviso pois o servio pode deixar a requisio lenta
            addArrayInflacaoExcluidaNoHidden();

            infraExibirAviso(false);
            return true;
        }
        return false;
    }

    function addArrayInflacaoExcluidaNoHidden() {
        if (arrIdsExcluidosInflacao.length > 0) {
            var arrJson = JSON.stringify(arrIdsExcluidosInflacao);
            document.getElementById('hdnIdsInfracoesExcluidas').value = arrJson;
        }
    }

    function validarCadastro(formulario) {

        if (infraTrim(document.getElementById('hdnListaDocInstauradores').value) == '') {
            alert('Informe o valor para Documento Instaurador do Procedimento Litigioso.');
            document.getElementById('txtNumeroSei').focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtDtInstauracao').value) == '') {
            alert('Informe o "Data da Instaurao".');
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
                alert('Data da Instaurao no deve ser superior  data atual.');
                return false;
            }

        }

        if (!validarInteressado()) {
            return false;
        }

//        if (<?//=$bolTipoProcedimentosSobrestadosGeracao?>//==0)
//        {
        if (infraTrim(document.getElementById('hdnListaDIIndicados').value) == '') {
            alert('Informe ao menos um Dispositivo Normativo');
            document.getElementById('rdIndicDisposNormativo').focus();
            return false;
        }
//        }
//        if(<?//=$bolTipoProcedimentosSobrestadosGeracao?>// == 1){
//            if (infraTrim(document.getElementById('hdnListaPSIndicados').value) == '') {
//                alert('Informe o "Processos a serem Sobrestados".');
//                document.getElementById('txtNumeroSeiPS').focus();
//                return false;
//            }
//        }
        if(document.getElementById('selMotivos') != null) {
            var optionsMotivos = document.getElementById('selMotivos').options;
            if (optionsMotivos != null && optionsMotivos.length == 0 && document.getElementById('lblMotivos').className.match(/infraLabelOpcional/) == null) {
                alert('Informe ao menos um Motivo para Instaurao.');
                document.getElementById('selMotivos').focus();
                return false;
            }
        }

        if(validarCamposEmDuplicidade()){
            return false;
        }

        return true;
    }

    var arrValidaSEI = new Array();
    var arrValidaSituacaoSEI = new Array();

    function validaSEI(numeroSEI, tipo = 'd') {

        if (tipo == 'd') {
            arrValidaSEI["IdDocumento"] = '';
            arrValidaSEI["NumeroSei"] = '';
            arrValidaSEI["Numero"] = '';
            arrValidaSEI["SiglaUnidadeGeradoraProtocolo"] = '';
            arrValidaSEI["NomeSerie"] = '';
            arrValidaSEI["GeracaoProtocolo"] = '';
            arrValidaSEI["SinInterno"] = '';
            arrValidaSEI["Assinatura"] = '';
            arrValidaSEI['DescricaoUnidadeGeradoraProtocolo'] = '';
        } else if (tipo == 'p') {
            arrValidaSEI["NomeTipoProcedimento"] = '';
            arrValidaSEI["IdProcedimento"] = '';
        }

        //vazio
        if (numeroSEI.length == 0) return false;

        // #EU4864 - INFRAAJAX - no encontrado mtodo que retorna somente dados, sem componentes
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
                    //Documento Externo - data gerao
                    arrValidaSEI["GeracaoProtocolo"] = $(result).find('GeracaoProtocolo').text();
                    arrValidaSEI["SinInterno"] = $(result).find('SinInterno').text();
                    //Documento Externo - data primeira assinatura
                    arrValidaSEI["Assinatura"] = $(result).find('Assinatura').text();
                    arrValidaSEI['DescricaoUnidadeGeradoraProtocolo'] = $(result).find('DescricaoUnidadeGeradoraProtocolo').text();
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

        // #EU4864 - INFRAAJAX - no encontrado mtodo que retorna somente dados, sem componentes
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
            },
            complete: function (result) {
            }
        });
        return arrValidaSituacaoSEI;

    }

    function preencheNumeroSei(campo, mensagemMostar = true) {
        var SEIvalido = false;
        var mensagem = '';
        var arrDtAssinatura;

        document.getElementById('divTabelaDocInstaurador').className = document.getElementById('divTabelaDocInstaurador').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
        document.getElementById('divTabelaDocInstaurador2').className = document.getElementById('divTabelaDocInstaurador2').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
        mostrarTabelaDI(false);
        mostrarTabelaPS(false);

        //[RN3]	O formulrio completo somente ser apresentado, caso os campos Nmero SEI e o Tipo de Documentos seja vlido.
        validaSEI(campo.value, 'd');

        if ((arrValidaSEI["ProtocoloProcedimentoFormatado"] != '' && arrValidaSEI["ProtocoloProcedimentoFormatado"] != undefined)
            && (arrValidaSEI["ProtocoloProcedimentoFormatado"] != document.getElementById('ProtocoloProcedimentoFormatado').value)
        ) {
            mensagem = 'Numero SEI de outro procedimento.'
        }

        if (arrValidaSEI["NomeSerie"] != '' && arrValidaSEI["NomeSerie"] != undefined && mensagem == '') {

            validaSituacaoSEI(campo.value);

            //[RN2]	O campo Nmero do SEI deve aceitar somente :
            //      Numero SEI dos Tipos de Documentos associados  Situao marcada como Instauradora para o correspondente Tipo de Controle Litigioso.
            if (arrValidaSituacaoSEI["IdSerie"] != '' && arrValidaSituacaoSEI["IdSerie"] != undefined && !isNaN(arrValidaSituacaoSEI["IdSerie"]) && arrValidaSituacaoSEI["StrSinInstauracao"] == 'S') {

                //[RN4]	Caso o Nmero SEI informado seja de Documento Interno, somente ser validado caso o mesmo j estiver sido assinado.
                if (arrValidaSEI["SinInterno"] == 'S') {
                    if (arrValidaSEI["Assinatura"] != '' && arrValidaSEI["Assinatura"] != undefined) {
                        SEIvalido = true;
                        arrDtAssinatura = arrValidaSEI["Assinatura"].split(" ");
                    } else {
                        //[MSG9] O Nmero do SEI informado no possui o documento interno assinado.
                        mensagem = 'Somente  permitido adicionar Documento gerado depois que for assinado.';
                    }
                    //[RN6]	Caso o Nmero SEI informado for de um Documento Externo, trs a Data do Documento indicada em seu cadastro, campo editvel.
                } else {
                    SEIvalido = true;
                }

                if (SEIvalido == true) {

                    document.getElementById('sbmAdicionarNumeroSei').className = document.getElementById('sbmAdicionarNumeroSei').className.replace('NumeroSEINaoValidado', '');
                    document.getElementById('sbmAdicionarNumeroSei').style.display = '';

                    if (hdnIdMdLitControle != '') {
                        mostrarTabelaDI(true);
                    } else {
                        document.getElementById('divTabelaDocInstaurador').className = document.getElementById('divTabelaDocInstaurador').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
                        document.getElementById('divTabelaDocInstaurador2').className = document.getElementById('divTabelaDocInstaurador2').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
                        mostrarTabelaDI(false);
                    }

                    document.getElementById('hdnIdDocumento').value = arrValidaSEI["IdDocumento"];
                    document.getElementById('hdnNumeroSei').value = arrValidaSEI["NumeroSei"];
                    document.getElementById('hdnNumero').value = arrValidaSEI["Numero"];
                    document.getElementById('hdnUnidade').value = '<a alt="' + arrValidaSEI['DescricaoUnidadeGeradoraProtocolo'] + '" title="' + arrValidaSEI['DescricaoUnidadeGeradoraProtocolo'] + '" class="ancoraSigla" > ' + arrValidaSEI["SiglaUnidadeGeradoraProtocolo"] + '</a>';
                    document.getElementById('hdnData').value = arrValidaSEI["GeracaoProtocolo"];
                    document.getElementById('txtTipo').value = arrValidaSEI["NomeSerie"];
                    document.getElementById('hdnDataAssinatura').value = arrDtAssinatura && arrDtAssinatura.length > 0 ? arrDtAssinatura[0] : '';

                    buscarInteressado();
                    return true;

                } else {
                    limparDocInstaurador();
                }
            } else {
                mensagem = 'Este documento n�o est� vinculado ao Tipo de Controle deste processo na situ��o Instaura��o.';
            }


        } else {
            //[MSG8] N�mero SEI inv�lido.
            mensagem = 'N�mero SEI inv�lido'
            limparDocInstaurador();
        }

        if (mensagem != '' && mensagemMostar) {
            alert(mensagem);
        }

        //[RN3]	O formulrio completo somente ser apresentado, caso os campos Nmero SEI e o Tipo de Documentos seja vlido.
        //      desabilitando
        var elements = document.getElementsByClassName('NumeroSEIValidado');
        var iLen = elements.length;
        while (iLen > 0) {
            elements[iLen - 1].className = elements[iLen - 1].className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
            elements = document.getElementsByClassName('NumeroSEIValidado');
            iLen = elements.length;
        }
        if (objTabelaDocInstaurador.tbl.rows.length == 2) {
            objTabelaDocInstaurador.removerLinha(1);
        }
        document.getElementById('divTabelaDocInstaurador').className = document.getElementById('divTabelaDocInstaurador').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
        document.getElementById('divTabelaDocInstaurador2').className = document.getElementById('divTabelaDocInstaurador2').className.replace('NumeroSEIValidado', 'NumeroSEINaoValidado');
        mostrarTabelaDI(false);
        document.getElementById('txtTipo').value = '';
        document.getElementById('txtDtInstauracao').value = '';

        //[RN7]	Situao "Instauradora" - "No exige Data da Intimao da Instaurao do Procedimento" => "Data de Intimao" - obrigatrio
//        document.getElementById('lblDtIntimacao').className = document.getElementById('lblDtIntimacao').className.replace('infraLabelObrigatorio', 'infraLabelOpcional');

    }

    function preencheNumeroSeiPS(campo) {
        var SEIvalido = false;
        var mensagem = '';
        var arrDtAssinatura;

        //[RN3]	O formulrio completo somente ser apresentado, caso os campos Nmero SEI e o Tipo de Documentos seja vlido.
        validaSEI(campo.value, 'd');

        if (arrValidaSEI["NomeSerie"] != '' && arrValidaSEI["NomeSerie"] != undefined) {

            validaSituacaoSEI(campo.value);

            //[RN2]	O campo Nmero do SEI deve aceitar somente :
            //      Numero SEI dos Tipos de Documentos associados  Situao marcada como Instauradora para o correspondente Tipo de Controle Litigioso.
            if (arrValidaSituacaoSEI["IdSerie"] != '' && arrValidaSituacaoSEI["IdSerie"] != undefined && !isNaN(arrValidaSituacaoSEI["IdSerie"]) && arrValidaSituacaoSEI["StrSinInstauracao"] == 'S') {

                var txtDtSobrestamentoPS = '';

                //[RN4]	Caso o Nmero SEI informado seja de Documento Interno, somente ser validado caso o mesmo j estiver sido assinado.
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
                        //[MSG9] O Nmero do SEI informado no possui o documento interno assinado.
                        mensagem = 'O Nmero do SEI informado no possui o documento interno assinado';
                    }
                    //[RN6]	Caso o Nmero SEI informado for de um Documento Externo, trs a Data do Documento indicada em seu cadastro, campo editvel.
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
            //[MSG8] N�mero SEI inv�lido.
            mensagem = 'N�mero SEI inv�lido'
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
        // no existe alterao?
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
        if (objTabelaPS.tbl.rows.length == 2) {
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
        //Linha adicionada, adiciona as aes
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
        if (opcao) {
            if (qtdPSIndicados > 1) {
                document.getElementById('tbProcessosSobrestados').style.display = '';
            }
        } else {
            document.getElementById('tbProcessosSobrestados').style.display = 'none';
        }
    }

    // PS - funcionalidades - FIM

    // Infrao - funcionalidades
    objTabelaDI = new infraTabelaDinamica('tbDispositivosInfrigidos', 'hdnListaDIIndicados', true, true);
    objTabelaDI.gerarEfeitoTabela = true;
    objTabelaDI.inserirNoInicio = false;
    objTabelaDI.exibirMensagens = false;

    objTabelaDI.alterar = function (arr) {
        document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value = arr[0];

        if (arr[5] != '' && arr[5] != 'null') {
            document.getElementById('rdIndicConduta').checked = true;
            changeInfracoes();
            document.getElementById('txtICDispNormat').value = arr[2].replace(/<.*?>/g, '') + ' - ' + arr[4].replace(/<\/?span[^>]*>/g, '');
            document.getElementById('hdnIdICDispNormat').value = arr[3];
            document.getElementById('selICCondutas').value = arr[5];
            document.getElementById('divDispositivoPorConduta').style.display = '';
            document.getElementById('txtDtaInfracaoPorConduta').value = arr[7];

            if (arr[11] == 'P') {
                document.getElementById('rdDataInfracaoPeriodoPorConduta').checked = true;
                changeDataInfracoes();
                document.getElementById('txtDtaInfracaoInicialPorConduta').value = arr[9];
                document.getElementById('txtDtaInfracaoFinalPorConduta').value = arr[10];
            } else if (arr[11] == 'E') {
                document.getElementById('rdDataInfracaoEspecificaPorConduta').checked = true;
                changeDataInfracoes();
                document.getElementById('txtDtaInfracaoPorConduta').value = arr[8];
            }
        } else {
            document.getElementById('rdIndicDisposNormativo').checked = true;
            changeInfracoes();
            document.getElementById('txtIDNDispNormat').value = arr[2].replace(/<.*?>/g, '') + ' - ' + arr[4].replace(/<\/?span[^>]*>/g, '');
            document.getElementById('hdnIdIDNDispNormat').value = arr[3];
            document.getElementById('txtDtaInfracaoPorDispositivo').value = arr[7];

            if (arr[11] == 'P') {
                document.getElementById('rdDataInfracaoPeriodoPorDispositivo').checked = true;
                changeDataInfracoes();
                document.getElementById('txtDtaInfracaoInicialPorDispositivo').value = arr[9];
                document.getElementById('txtDtaInfracaoFinalPorDispositivo').value = arr[10];
            } else if (arr[11] == 'E') {
                document.getElementById('rdDataInfracaoEspecificaPorDispositivo').checked = true;
                changeDataInfracoes();
                document.getElementById('txtDtaInfracaoPorDispositivo').value = arr[8];
            }
        }
    };

    objTabelaDI.lerCelula = function (celula) {
        var ret = null;
        var div = celula.getElementsByTagName('div');
        if (div.length == 0) {
            ret = celula.innerText;
        } else {
            ret = div[0].innerText;
        }
        return ret.infraReplaceAll('<br>', '<br />');
    };

    objTabelaDI.remover = function (arr) {
        var retorno = true;
        if (arr[0].toString().indexOf("novo_") == -1) {
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
        if (!retorno) {
            alert('A excluso da infrao no  permitida, pois existem registros vinculados!');
            return retorno;
        }

        if (objTabelaDI.tbl.rows.length == 2) {
            mostrarTabelaDI(false);
        }

        addIdExcluidoHidden(arr[0]);
        return true;
    }

    function addIdExcluidoHidden(id) {

        if (id.indexOf('novo') == -1) {
            arrIdsExcluidosInflacao.push(id);
        }
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
        var numRow = objTabelaDI.tbl.rows.length + 1;
        var idDispositivoNormativoNormaCondutaControle = document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value == '' ? 'novo_' + numRow : document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value;
        var dtaInfracao, dtaInfracaoEspecifica, dtaInfracaoInicial, dtaInfracaoFinal, staInfracaoData = null;

        // Dispositivo Normativo
        if (document.getElementById('rdIndicDisposNormativo').checked) {
            arrDI = document.getElementById('txtIDNDispNormat').value.split(' - ');

            if (arrDI.length > 1) {
                norma = arrDI[0];
                dispositivo = arrDI[1];
            }
            dispositivoid = document.getElementById("hdnIdIDNDispNormat").value;

            if (document.getElementById("selIDNCondutas").options.length > 1 && document.getElementById("selIDNCondutas").value == '') {
                alert('A conduta � obrigat�rio!');
                return false;
            }


            //data da infrao
            if (document.getElementById('rdDataInfracaoEspecificaPorDispositivo').checked) {
                staInfracaoData = '<?php echo MdLitRelDispositivoNormativoCondutaControleRN::$TA_ESPECIFICA?>';
                dtaInfracao = document.getElementById('txtDtaInfracaoPorDispositivo').value;
                dtaInfracaoEspecifica = document.getElementById('txtDtaInfracaoPorDispositivo').value;

                if (dtaInfracaoEspecifica == '') {
                    alert('A data da infrao  obrigatrio!');
                    return false;
                }
                if (!infraValidarData(document.getElementById('txtDtaInfracaoPorDispositivo'), false)) {
                    alert('A data da infrao  invlida!');
                    document.getElementById('txtDtaInfracaoPorDispositivo').value = '';
                    document.getElementById('txtDtaInfracaoPorDispositivo').focus();
                    return false;
                }
                if (infraCompararDatas(dtaInfracaoEspecifica, infraDataAtual()) < 0) {
                    alert('A data da infrao especifica  maior que a data atual!');
                    document.getElementById('txtDtaInfracaoPorDispositivo').focus();
                    return false;
                }
            } else if (document.getElementById('rdDataInfracaoPeriodoPorDispositivo').checked) {
                staInfracaoData = '<?php echo MdLitRelDispositivoNormativoCondutaControleRN::$TA_PERIODO?>';
                dtaInfracaoInicial = document.getElementById('txtDtaInfracaoInicialPorDispositivo').value;
                dtaInfracaoFinal = document.getElementById('txtDtaInfracaoFinalPorDispositivo').value;

                if (dtaInfracaoInicial == '' || dtaInfracaoFinal == '') {
                    alert('A data do periodo inicial e final  obrigatrio!');
                    return false;
                }

                if (infraCompararDatas(dtaInfracaoInicial, dtaInfracaoFinal) < 0) {
                    alert('Perodo de datas invlido.');
                    document.getElementById('txtDtaInfracaoInicialPorDispositivo').focus();
                    return false;
                }

                if (!infraValidarData(document.getElementById('txtDtaInfracaoInicialPorDispositivo'), false)) {
                    alert('A data do periodo inicial  Invlida!');
                    document.getElementById('txtDtaInfracaoInicialPorDispositivo').value = '';
                    document.getElementById('txtDtaInfracaoInicialPorDispositivo').focus();
                    return false;
                }

                if (!infraValidarData(document.getElementById('txtDtaInfracaoFinalPorDispositivo'), false)) {
                    alert('A data do periodo final  Invlida!');
                    document.getElementById('txtDtaInfracaoFinalPorDispositivo').value = '';
                    document.getElementById('txtDtaInfracaoFinalPorDispositivo').focus();
                    return false;
                }

                if (infraCompararDatas(dtaInfracaoInicial, infraDataAtual()) < 0) {
                    alert('A data do periodo inicial  maior que a data atual!');
                    document.getElementById('txtDtaInfracaoInicialPorDispositivo').focus();
                    return false;
                }

                if (infraCompararDatas(dtaInfracaoFinal, infraDataAtual()) < 0) {
                    alert('A data do periodo final  maior que a data atual!');
                    document.getElementById('txtDtaInfracaoFinalPorDispositivo').focus();
                    return false;
                }
                dtaInfracao = dtaInfracaoInicial + ' a ' + dtaInfracaoFinal;
            } else {
                alert('Selecione o tipo da data de infrao');
                return false;
            }


            conduta = document.getElementById("selIDNCondutas").value != '' ? document.getElementById("selIDNCondutas").options[document.getElementById("selIDNCondutas").selectedIndex].text : '';
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

            if (condutaid == '') {
                alert('A conduta  obrigatrio!');
                return false;
            }

            //data da infrao
            if (document.getElementById('rdDataInfracaoEspecificaPorConduta').checked) {
                staInfracaoData = '<?php echo MdLitRelDispositivoNormativoCondutaControleRN::$TA_ESPECIFICA?>';
                dtaInfracao = document.getElementById('txtDtaInfracaoPorConduta').value;
                dtaInfracaoEspecifica = document.getElementById('txtDtaInfracaoPorConduta').value;

                if (dtaInfracaoEspecifica == '') {
                    alert('A data da infrao  obrigatrio!');
                    return false;
                }
                if (!infraValidarData(document.getElementById('txtDtaInfracaoPorConduta'), false)) {
                    alert('A data da infrao  invlida!');
                    document.getElementById('txtDtaInfracaoPorConduta').value = '';
                    document.getElementById('txtDtaInfracaoPorConduta').focus();
                    return false;
                }
                if (infraCompararDatas(dtaInfracaoEspecifica, infraDataAtual()) < 0) {
                    alert('A data da infrao especifica  maior que a data atual!');
                    document.getElementById('txtDtaInfracaoPorConduta').focus();
                    return false;
                }

            } else if (document.getElementById('rdDataInfracaoPeriodoPorConduta').checked) {
                staInfracaoData = '<?php echo MdLitRelDispositivoNormativoCondutaControleRN::$TA_PERIODO?>';
                dtaInfracaoInicial = document.getElementById('txtDtaInfracaoInicialPorConduta').value;
                dtaInfracaoFinal = document.getElementById('txtDtaInfracaoFinalPorConduta').value;

                if (dtaInfracaoInicial == '' || dtaInfracaoFinal == '') {
                    alert('A data do periodo inicial e final  obrigatrio!');
                    return false;
                }

                if (infraCompararDatas(dtaInfracaoInicial, dtaInfracaoFinal) < 0) {
                    alert('Perodo de datas invlido.');
                    document.getElementById('txtDtaInfracaoFinalPorConduta').focus();
                    return false;
                }

                if (!infraValidarData(document.getElementById('txtDtaInfracaoFinalPorConduta'), false)) {
                    alert('A data do periodo final  Invlida!');
                    document.getElementById('txtDtaInfracaoFinalPorConduta').value = '';
                    document.getElementById('txtDtaInfracaoFinalPorConduta').focus();
                    return false;
                }

                if (!infraValidarData(document.getElementById('txtDtaInfracaoInicialPorConduta'), false)) {
                    alert('A data do periodo inicial  Invlida!');
                    document.getElementById('txtDtaInfracaoInicialPorConduta').value = '';
                    document.getElementById('txtDtaInfracaoInicialPorConduta').focus();
                    return false;
                }

                if (infraCompararDatas(dtaInfracaoInicial, infraDataAtual()) < 0) {
                    alert('A data do periodo inicial  maior que a data atual!');
                    document.getElementById('txtDtaInfracaoInicialPorConduta').focus();
                    return false;
                }

                if (infraCompararDatas(dtaInfracaoFinal, infraDataAtual()) < 0) {
                    alert('A data do periodo final  maior que a data atual!');
                    document.getElementById('txtDtaInfracaoFinalPorConduta').focus();
                    return false;
                }

                dtaInfracao = dtaInfracaoInicial + ' a ' + dtaInfracaoFinal;
            } else {
                alert('Selecione o tipo da data de infrao');
                return false;
            }

        }

        // Somente Dispositivo e Norma so obrigatrios
        if (norma == '' || dispositivo == '' || dispositivoid == '' /*|| conduta=='' || condutaid==''*/) {
            alert('O dispositivo normativo  obrigatrio!');
            return false;
        }

        if (dtaInfracao == '') {
            alert('A data da infrao  obrigatrio!');
            return false;
        }

        //verificar se a infrao j existe na tabela
        var arrItens = objTabelaDI.obterItens();
        if (arrItens.length > 0) {
            for (var i = 0; i < arrItens.length; i++) {
                if (arrItens[i][3] == dispositivoid
                    && (arrItens[i][5] == condutaid || (arrItens[i][5] == 'null' && condutaid == ''))
                    && document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value != arrItens[i][0]) {
                    alert('Essa infrao j foi adicionado!');
                    return false;
                }

            }
        }

        var obj = consultarDispositivoNormativo(dispositivoid, norma, dispositivo);
        var arrDadosDIValido = [];

        arrDadosDIValido[0] = idDispositivoNormativoNormaCondutaControle;
        arrDadosDIValido[1] = dispositivoid + '-' + condutaid;
        arrDadosDIValido[2] = norma;
        arrDadosDIValido[3] = dispositivoid;
        arrDadosDIValido[4] = dispositivo;
        arrDadosDIValido[5] = condutaid;
        arrDadosDIValido[6] = conduta;
        arrDadosDIValido[7] = dtaInfracao;
        arrDadosDIValido[8] = dtaInfracaoEspecifica;
        arrDadosDIValido[9] = dtaInfracaoInicial;
        arrDadosDIValido[10] = dtaInfracaoFinal;
        arrDadosDIValido[11] = staInfracaoData;

        var bolDICustomizado = hdnCustomizado;

        receberDI(arrDadosDIValido, bolDICustomizado);

        var row = objTabelaDI.procuraLinha(arrDadosDIValido[1]);

        //@todo Corrrigindo o problema do core(tabela dinmica) do Sei que no aceita tag HTML para alterao (funo remover XML)
        document.getElementById('tbDispositivosInfrigidos').rows[row].cells[2].innerHTML = '<div>' + obj.norma + '</div>';
        document.getElementById('tbDispositivosInfrigidos').rows[row].cells[4].innerHTML = '<div>' + obj.dispositivo + '</div>';
        document.getElementById('hdnIdDispositivoNormativoNormaCondutaControle').value = '';
        document.getElementById('divDispositivoPorConduta').style.display = 'none';
    }

    function consultarDispositivoNormativo(dispositivoid, norma, dispositivo) {

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxConsultarDispositivo ?>",
            //dataType: "json",
            dataType: "xml",
            async: false,
            data: {
                id_md_lit_disp_normat: dispositivoid
            },
            success: function (result) {
                var url = $(result).find('[nome="Url"]').text();
                var descricao = $(result).find('[nome="Descricao"]').text();

                if (url != '') {
                    norma = '<a href="' + url + '" style="font-size: inherit !important;" target="_blank" title="Acesse a Norma">' + norma + '</a>';
                }
                if (descricao != '') {
                    dispositivo = '<span style="font-size: inherit !important;" title="' + descricao + '">' + dispositivo + '</span>';
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                alert(msgCommit);
            }
        });
        var obj = {norma: norma, dispositivo: dispositivo};
        return obj;

    }

    function receberDI(arrDadosDI, DICustomizado) {
        objTabelaDI.atualizaHdn();
        var qtdPSIndicados = objTabelaDI.tbl.rows.length;
        objTabelaDI.adicionar([arrDadosDI[0],
            arrDadosDI[1],
            arrDadosDI[2],
            arrDadosDI[3],
            arrDadosDI[4],
            arrDadosDI[5],
            arrDadosDI[6],
            arrDadosDI[7],
            arrDadosDI[8],
            arrDadosDI[9],
            arrDadosDI[10],
            arrDadosDI[11],
            '']);

        //Linha adicionada, adiciona as aes
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
        document.getElementById('txtDtaInfracaoPorDispositivo').value = '';
        document.getElementById('txtDtaInfracaoPorConduta').value = '';
        document.getElementById('rdDataInfracaoPeriodoPorDispositivo').checked = false;
        document.getElementById('rdDataInfracaoEspecificaPorDispositivo').checked = false;
        document.getElementById('rdDataInfracaoEspecificaPorConduta').checked = false;
        document.getElementById('rdDataInfracaoPeriodoPorConduta').checked = false;
        objAjaxIdConduta.executar();
        changeDataInfracoes();
        mostrarTabelaDI(true);

        infraEfeitoTabelas();

    }

    function mostrarTabelaDI(opcao) {

        var qtdDIIndicados = objTabelaDI.tbl.rows.length;
        if (opcao) {
            if (qtdDIIndicados > 1) {
                document.getElementById('tbDispositivosInfrigidos').style.display = '';
            }
        } else {
            document.getElementById('tbDispositivosInfrigidos').style.display = 'none';
        }
    }

    function changeInfracoes() {
        var dp = document.getElementsByName('rdInfracoes[]')[0].checked;

        //datas de infrao escondendo da tela
        document.getElementById('conteudoDataInfracaoEspecificaPorDispositivo').style.display = 'none';
        document.getElementById('conteudoDataInfracaoPeriodoPorDispositivo').style.display = 'none';
        document.getElementById('conteudoDataInfracaoEspecificaPorConduta').style.display = 'none';
        document.getElementById('conteudoDataInfracaoPeriodoPorConduta').style.display = 'none';

        //tirando os valores das datas de infraes
        document.getElementById('txtDtaInfracaoInicialPorConduta').value = '';
        document.getElementById('txtDtaInfracaoFinalPorConduta').value = '';
        document.getElementById('txtDtaInfracaoPorConduta').value = '';
        document.getElementById('txtDtaInfracaoInicialPorDispositivo').value = '';
        document.getElementById('txtDtaInfracaoFinalPorDispositivo').value = '';
        document.getElementById('txtDtaInfracaoPorDispositivo').value = '';

        //document.getElementById('divTabelaCondDN').style.display = 'none';

        if (dp) {
            showHide(true, 'classDispositivoNormativo');
            showHide(false, 'classCondutas');
        } else {
            showHide(false, 'classDispositivoNormativo');
            showHide(true, 'classCondutas');
        }
    }

    // Infrao - funcionalidades - FIM

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
        document.getElementById('sbmAdicionarNumeroSei').style.display = '';
    };

    objTabelaDocInstaurador.remover = function () {
        document.getElementById('txtDtInstauracao').value = '';

        //Desabilitando
        document.getElementById('txtNumeroSei').disabled = false;
        document.getElementById('sbmValidarNumeroSei').disabled = false;
        document.getElementById('sbmAdicionarNumeroSei').style.display = 'none';

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
        objTabelaDocInstaurador.hdn.value = objTabelaDocInstaurador.hdn.value.replace(/<\/?[^>]+(>|$)/g, "");
        document.getElementById('hdnListaDocInstauradores').value = objTabelaDocInstaurador.hdn.value.replace(/<\/?[^>]+(>|$)/g, "");

        if (document.getElementById('tbDocInstaurador').rows.length > 1) {
            var htmlUnidade = document.getElementById('tbDocInstaurador').rows[1].cells[6].innerHTML.replace(/<\/?[^>]+(>|$)/g, "");
            document.getElementById('tbDocInstaurador').rows[1].cells[6].innerHTML = '<div style="text-align:center">' + htmlUnidade + '</div>';
        }
        arrDadosValido[0] = 1;
        arrDadosValido[1] = IdDocumento;
        arrDadosValido[2] = NumeroSei;
        arrDadosValido[3] = Numero;
        arrDadosValido[4] = Tipo;
        arrDadosValido[5] = Tipo + ' ' + Numero + ' (' + NumeroSei + ')';
        arrDadosValido[6] = Unidade.replace(/<\/?[^>]+(>|$)/g, "");
        arrDadosValido[7] = Data;

        var bolCustomizado = hdnCustomizado;

        receberDocInstaurador(arrDadosValido, bolCustomizado);

        //Datas
        var dtaAssinatura = document.getElementById('hdnDataAssinatura').value;
        document.getElementById('txtDtInstauracao').value = dtaAssinatura != '' ? dtaAssinatura : Data;

        //Limpando
        limparDocInstaurador();

        //document.getElementById('divTabelaDocInstaurador').style.display = '';
        document.getElementById('divTabelaDocInstaurador').className = document.getElementById('divTabelaDocInstaurador').className.replace('NumeroSEINaoValidado', 'NumeroSEIValidado');
        document.getElementById('divTabelaDocInstaurador2').className = document.getElementById('divTabelaDocInstaurador2').className.replace('NumeroSEINaoValidado', 'NumeroSEIValidado');

        //Desabilitando
        document.getElementById('txtNumeroSei').disabled = true;
        document.getElementById('sbmValidarNumeroSei').disabled = true;
        document.getElementById('sbmAdicionarNumeroSei').style.display = 'none';

        //aparecer todos os fieldset do Litigioso
        var elements = document.getElementsByClassName('NumeroSEINaoValidado');
        var iLen = elements.length;
        while (iLen > 0) {
            elements[iLen - 1].className = elements[iLen - 1].className.replace('NumeroSEINaoValidado', 'NumeroSEIValidado');
            elements = document.getElementsByClassName('NumeroSEINaoValidado');
            iLen = elements.length;
        }

        if (document.getElementById('tbDocInstaurador').rows.length > 1) {
            document.getElementById('tbDocInstaurador').rows[1].cells[6].innerHTML = '<div style="text-align:center">' + Unidade + '</div>';
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
            arrDados[7]]);
        //Linha adicionada, adiciona as aes
        //if (qtd < objTabelaDocInstaurador.tbl.rows.length){
        //	objTabelaDocInstaurador.adicionarAcoes(arrDados[0], "", true, true);
        //}

        //Se existe registro, mostra tabela
        //if (objTabelaDocInstaurador.tbl.rows.length>1){
        //	objTabelaDocInstaurador.tbl.style.display='';
        //}

        infraEfeitoTabelas();

    }

    function limparDocInstaurador(NumeroSEILimpar = true) {
        document.getElementById('hdnIdDocumento').value = '';
        if (NumeroSEILimpar) {
            document.getElementById('txtNumeroSei').value = '';
        }
        document.getElementById('hdnNumeroSei').value = '';
        document.getElementById('txtTipo').value = '';
        document.getElementById('hdnNumero').value = '';
        document.getElementById('hdnUnidade').value = '';
        document.getElementById('hdnData').value = '';
        document.getElementById('hdnDataAssinatura').value = '';
    }

    function removerValidacaoDocInstaurador() {
        if (document.getElementById('txtNumeroSei').value != document.getElementById('hdnNumeroSei').value) {
            limparDocInstaurador(false);
            document.getElementById('sbmAdicionarNumeroSei').style.display = 'none';
        }
    }

    //Doc Instaurador - funcionalidades - FIM

    function removerSobrestamentoProcesso(id, IdProcedimentoPS) {
        if (confirm('Confirma remoo de sobrestamento do processo?')) {
            // #EU4864 - INFRAAJAX - no encontrado mtodo que retorna somente dados, sem componentes
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
                        if ($(result).find('documento').find('mensagemtipo').text() == 'sucesso') {
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
            if (objTabelaInteressado.obterItens().length == 1) {
                alert(" necess�rio ao menos um Interessado");
                return false;
            }

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
            contato.nomeTipoContato,
            contato.nomeContato,
            contato.cpfCnpj,
            contato.paramModal,
            contato.contarLancamento,
            contato.cep,
            contato.idUf
        ]);


        var acaoDadosComplementares = '';

        var dadoCompleto = contato.staNatureza != '' && contato.endereco != '' &&
            contato.bairro != '' && contato.idCidade != '' && contato.idUf != '' && contato.cpfCnpj != '' && contato.paramModal == 'S';

        if (dadoCompleto) {
            acaoDadosComplementares = "<img onclick=\"abrirModalDadosInteressado('" + contato.urlDadosComplementares + "')\" " +
                " style='width: 24px' title='Dados Complementares do Interessado' src='modulos/litigioso/imagens/svg/dado_complementar.svg?<?= Icone::VERSAO ?>' class='infraImg'/>&nbsp;<input type='hidden' id='hdnContatoPossuiDadoComplementa_" + contato.idContato + "' value='" + contato.contatoPossuiDadoComplementar + "'>";
        }

        var acaoAlterar = "<img onclick=\"alterarInteressado('" + contato.idContato + "','" + contato.urlAlterar + "')\" " +
            " title='Alterar Interessado' src='<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/alterar.svg' class='infraImg'/>&nbsp;";

        var mostrarExcluir = true;
        if (contato.contarLancamento > 0) {
            mostrarExcluir = false;
        }
        objTabelaInteressado.adicionarAcoes(contato.idContato, acaoAlterar + acaoDadosComplementares, false, mostrarExcluir);

        if ('<?=$_GET['acao']?>' == 'md_lit_processo_cadastro_consultar') {
            infraDesabilitarCamposDiv(document.getElementById('frmCadastroProcesso'));
        }
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
                if ($('Contato', r).text() == '') {
                    alert('Necess�ria a indic�o de ao menos um Interessado de tipo diferente de: �rg�os, Sistemas e Tempor�rio');
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
            contato.paramModal = $('SinParamModal', r).text();
            contato.contarLancamento = $('ContarLancamento', this).text();
            contato.contatoPossuiDadoComplementar = $('ContatoPossuiDadoComplementar', this).text();
            contato.cep = $('cep', this).text();
            contato.idUf = $('IdUf', this).text();
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
        infraAbrirJanelaModal(url,
            1024,
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

        // caso n�o tenha registro de interessado j� retorna n�o v�lido com alert.
        if (!trs[1]) {
            alert("Necess�rio ao menos um Interessado");
            return false;
        }

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

            if (tds[3].innerText.trim() == '') { //Endereo
                dados.push('Endereo');
                valido = false;
            }

            if (tds[4].innerText.trim() == '') { //Bairro
                dados.push('Bairro');
                valido = false;
            }

            if (tds[5].innerText.trim() == '') { //Cidade
                dados.push('Cidade');
                valido = false;
            }

            if (tds[11].innerText.trim() == '') { //Cep
                dados.push('Cep');
                valido = false;
            }

            if (tds[8].innerText.trim() == '') { //CPF/CNPJ
                if (tds[2].innerText.trim() == 'J') {
                    dados.push('CNPJ');
                } else if (tds[2].innerText.trim() == 'F') {
                    dados.push('CPF');
                } else {
                    dados.push('CNPJ/CPF');
                }
                valido = false;
            }

            if (dados.length > 0) {
                msg += 'Por favor preencha os dados do Interessado ' + tds[8].innerText.trim() + '\n';
                msg += ' - ' + dados.join('\n - ') + '\n\n';
            } else {
                if (document.getElementById('hdnContatoPossuiDadoComplementa_' + tds[0].innerText.trim())){
                    var hdnContatoPossuiDadoComplementar = document.getElementById('hdnContatoPossuiDadoComplementa_' + tds[0].innerText.trim()).value;
                    //caso a modal de Dados Complementares tenha sido configurada para apresentar, ento informar os dados dela  obrigatrio.
                    if (tds[9].innerText.trim() == 'S' && hdnContatoPossuiDadoComplementar == 0) {
                        var hdnTbDadoInteressado = document.getElementById('hdnTbDadoInteressado_' + tds[0].innerText.trim());
                        if (hdnTbDadoInteressado == null || hdnTbDadoInteressado.value == '') {
                            msgDadoComplementar += '\n -' + tds[7].innerText.trim();
                        }
                    }
                }
            }
        }

        if (!valido) {
            alert(msg);
        } else if (msgDadoComplementar != '') {
            msg = 'Por favor preencha os dados dados complementares do interessado: \n' + msgDadoComplementar;
            valido = false;
            alert(msg);
        }

        return valido;

    }

    function mostrarDispositivoPorConduta() {
        objLupaICDispositivoNormativo.remover();
        element = document.getElementById('selICCondutas');
        if (element.value == '' || element.value == 'null') {
            document.getElementById('divDispositivoPorConduta').style.display = 'none';
        } else {
            document.getElementById('divDispositivoPorConduta').style.display = '';
        }
    }

    function changeDataInfracoes() {
        document.getElementById('txtDtaInfracaoInicialPorConduta').value = '';
        document.getElementById('txtDtaInfracaoFinalPorConduta').value = '';
        document.getElementById('txtDtaInfracaoPorConduta').value = '';
        document.getElementById('txtDtaInfracaoInicialPorDispositivo').value = '';
        document.getElementById('txtDtaInfracaoFinalPorDispositivo').value = '';
        document.getElementById('txtDtaInfracaoPorDispositivo').value = '';

        document.getElementById('conteudoDataInfracaoPeriodoPorDispositivo').style.display = 'none';
        document.getElementById('conteudoDataInfracaoEspecificaPorDispositivo').style.display = 'none';
        document.getElementById('conteudoDataInfracaoPeriodoPorConduta').style.display = 'none';
        document.getElementById('conteudoDataInfracaoEspecificaPorConduta').style.display = 'none';

        if (document.getElementById('rdDataInfracaoEspecificaPorDispositivo').checked) {
            document.getElementById('conteudoDataInfracaoEspecificaPorDispositivo').style.display = '';
        } else if (document.getElementById('rdDataInfracaoPeriodoPorDispositivo').checked) {
            document.getElementById('conteudoDataInfracaoPeriodoPorDispositivo').style.display = '';
        } else if (document.getElementById('rdDataInfracaoEspecificaPorConduta').checked) {
            document.getElementById('conteudoDataInfracaoEspecificaPorConduta').style.display = '';
        } else if (document.getElementById('rdDataInfracaoPeriodoPorConduta').checked) {
            document.getElementById('conteudoDataInfracaoPeriodoPorConduta').style.display = '';
        }
    }

    if (document.getElementById('hdnIdMotivos') != null) {
        objAutoCompletarMotivos = new infraAjaxAutoCompletar('hdnIdMotivos', 'txtMotivos', '<?=$strLinkAjaxMotivos?>');
        objAutoCompletarMotivos.limparCampo = true;
        objAutoCompletarMotivos.tamanhoMinimo = 3;
        objAutoCompletarMotivos.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtMotivos').value + '&idTipoControle=<?=$idMdRelTipoCntroleTipoProcedimento?>';
        };

        objAutoCompletarMotivos.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selMotivos').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Motivo j consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selMotivos'), descricao, id);
                    objLupaMotivos.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtMotivos').value = '';
                document.getElementById('txtMotivos').focus();

            }
        };

        objLupaMotivos = new infraLupaSelect('selMotivos', 'hdnMotivos', '<?=$strLinkMotivosSelecao?>');
    }

    // ADICIONAR UM CAMPO NIVEL 1 NA GRID
    function adicionarCampo() {

        if(verificarSeJaEstaNaGrid()){
            document.getElementById("selCampo").value = null;
            return;
        }

        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_consultar_campo') ?>",
            dataType: "xml",
            data: { idTipoInformacao: document.getElementById("selCampo").value },
            async: false,
            success: function (result) {
                // Selecionar a tabela
                var tabela = document.getElementById('tbCamposSelecionados');

                // Remover a linha com o ID "nenhumaOpcao1", se existir
                var linhaNenhumCampo = document.getElementById('nenhumCampo');
                if (linhaNenhumCampo) {
                    linhaNenhumCampo.parentNode.removeChild(linhaNenhumCampo);
                }

                var arrCampos = $(result).find('HtmlCampos').html();
                var labelTpInfor = $(result).find('HtmlTipoInformacao').html();
                if (arrCampos) {
                    // Inserir uma nova linha na tabela
                    var novaLinha = tabela.insertRow();

                    // Criar uma c�lula que conter� todos os campos adicionais em um cont�iner flex�vel
                    var primeiraCelula = novaLinha.insertCell();
                    primeiraCelula.innerHTML = '<div class="label-campo-adicional">' + labelTpInfor + '</div><div class="campo-adicional-container">' + arrCampos + '</div>';

                    // Criar a c�lula para a��es
                    var acoesNovaLinha = novaLinha.insertCell();
                    acoesNovaLinha.style.textAlign = 'center';

                    // Criar o �cone de excluir
                    var img = document.createElement('img');
                    img.src = '<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/remover.svg';
                    img.title = 'Excluir Informa��o Adicional';
                    img.alt = 'Excluir Informa��o Adicional';
                    img.className = 'infraImg';
                    img.style.marginTop = '20px';
                    img.setAttribute('onclick', 'excluirCampo(this)');
                    acoesNovaLinha.appendChild(img);

                    // Limpar a sele��o do campo
                    document.getElementById("selCampo").value = null;

                    // Aplicar a configura��o do multiple select
                    aplicarMultipleSelectConfig();
                } else {
                    console.error("Nenhum campo adicional encontrado no XML.");
                }
                aplicarMultipleSelectConfig();
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                valid = false;
                return false;
            }
        });
        organizarCamposPorLinha();
    }

    function verificarSeJaEstaNaGrid() {
        var idCampo = document.getElementById("selCampo").value;

        var hasField = $('#tbCamposSelecionados div[data-id-campo-tp-controle]').filter(function() {
            return $(this).attr('data-id-campo-tp-controle') === idCampo;
        }).length > 0;

        if (hasField) {
            alert('Este Campo j� foi adicionado.');
            return true;
        }

        return false;

    }

    // APAGA UMA LINHA DA TABELA COM TODOS CAMPOS
    function excluirCampo(element){
        var linha = element.closest('tr');
        if (linha) {
            linha.parentNode.removeChild(linha);
        }
        verificarLinhasTabela();
        organizarCamposPorLinha();
    }

    // VERIFICAR E ATUALIZAR TABELA SE N�O EXISTIR REGISTRO
    function verificarLinhasTabela() {
        // Selecionar a tabela e o corpo da tabela
        var tabela = document.getElementById('tbCamposSelecionados');
        var tbody = tabela.getElementsByTagName('tbody')[0];

        // Contar o n�mero de linhas (excluindo o cabe�alho)
        var numLinhas = tbody.getElementsByTagName('tr').length - 1; // -1 para excluir o cabe�alho

        // Verificar se o n�mero de linhas � menor que 1
        if (numLinhas < 1) {
            // Remover a linha "Nenhum campo adicionado" se j� existir
            var nenhumCampo = document.getElementById('nenhumCampo');
            if (nenhumCampo) {
                tbody.removeChild(nenhumCampo);
            }

            // Criar a nova linha
            var novaLinha = document.createElement('tr');
            novaLinha.id = 'nenhumCampo';

            // Criar a c�lula e definir suas propriedades
            var novaCelula = document.createElement('td');
            novaCelula.colSpan = 4; // Assumindo que a tabela tem 4 colunas
            novaCelula.style.textAlign = 'center';
            novaCelula.textContent = 'Nenhum campo adicionado';

            // Adicionar a c�lula � linha
            novaLinha.appendChild(novaCelula);

            // Adicionar a linha ao corpo da tabela
            tbody.appendChild(novaLinha);
        }
    }

    // FUN��O PARA QUE QUANDO FOR ALTERADO UMA OP��O NO FORMULARIO APAGA OS NIVEIS POSTEIRES E CARREGA O CAMPO NOVO COM FILHOS SE EXISTIR
    function mudarOpcaoComboBox(element){
        var valorAntigo = $(element).attr('data-old-value');
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_consultar_campo_dependente_opcao') ?>",
            dataType: "xml",
            data: { idOpcao: element.value },
            async: false,
            success: function (result) {
                var novoCampoHtml = $(result).find('resultado').html();
                $(element).closest('.campos-info-add').after(novoCampoHtml);
                $(element).attr('data-old-value', element.value);
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                valid = false;
                return false;
            }
        });
        verificarCampoFilhoParaRemover(element, valorAntigo);
        organizarCamposPorLinha()
    }

    function verificarCampoFilhoParaRemover(element, idOpcao) {

        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_consultar_campo_dependente_para_remover') ?>",
            dataType: "xml",
            data: { idOpcao },
            async: false,
            success: function (result) {
                $(result).find('idCampoAdd').each(function() {
                    var idCampoAdd = $(this).text();
                    var campoParaRemover = $(element).closest('.campo-adicional-container').find('[data-id-campo-add="' + idCampoAdd + '"]').closest('.campos-info-add');

                    if (campoParaRemover.length > 0) {
                        var selectElement = campoParaRemover.find('select[data-id-campo-add="' + idCampoAdd + '"]');
                        if (selectElement.length > 0) {
                            var valorSelecionado = selectElement.val();
                            verificarCampoFilhoParaRemover(selectElement[0], valorSelecionado);
                        }
                        campoParaRemover.remove();
                    }
                });
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                valid = false;
                return false;
            }
        });
    }

    function aplicarMultipleSelectConfig(){
        var selects = document.querySelectorAll('#tbCamposSelecionados select[multiple="multiple"]');
        selects.forEach(function(selectElement) {
            if (selectElement.id) {
                $("#" + selectElement.id).multipleSelect({
                    filter: false,
                    minimumCountSelected: 1,
                    selectAll: true
                });
            }
        });

        // AJUSTES DE POSICIONAMENTO
        document.querySelectorAll('.ms-parent.infraSelect.multipleSelect.form-control').forEach(function(div) {
            div.style.width = '100%';
            div.style.height = '30px';
            div.style.borderRadius = '3px';
        });

        document.querySelectorAll('.ms-choice').forEach(function(div) {
            div.style.height = '4px';
        });

        document.querySelectorAll('.ms-drop.bottom').forEach(function(div) {
            div.style.marginLeft = '-12px';
        });
    }

    function validarCamposEmDuplicidade() {
        var linhas = document.querySelectorAll('#tbCamposSelecionados tbody tr');
        var valoresLinhas = [];
        var count = 0;

        for (var i = 0; i < linhas.length; i++) {
            var linha = linhas[i];
            var campos = linha.querySelectorAll('[data-id-campo-add]');
            var row = '';

            campos.forEach(function(campo) {
                row += campo.getAttribute("data-id-campo-add").trim();
                if (campo.tagName === 'INPUT' || (campo.tagName === 'SELECT' && !campo.multiple)) {
                    row += campo.value.trim();
                } else if (campo.tagName === 'SELECT' && campo.multiple) {
                    // Para campo multiselect, concatena todos os valores selecionados
                    var selectedOptions = Array.from(campo.selectedOptions).map(option => option.value.trim());
                    row += selectedOptions.join(',');
                } else {
                    row += campo.innerText.trim();
                }
            });

            // Verifica se a linha est� vazia (sem campos)
            if (row.trim() === '') {
                continue;
            }

            if (valoresLinhas.includes(row.trim())) {
                alert('Os dados da linha ' + (count + 1) + ' est�o repetidos.'); // Adiciona 1 ao count para ajuste no �ndice da linha
                return true;
            } else {
                valoresLinhas.push(row.trim());
            }
            count++;
        }
        return false;
    }

    function organizarCamposPorLinha() {
        var linhas = document.querySelectorAll('#tbCamposSelecionados tbody tr');
        var organizador = [];

        linhas.forEach(function(linha, index) {
            var campos = linha.querySelectorAll('[data-id-campo-add]');
            var idsCampos = [];

            campos.forEach(function(campo) {
                idsCampos.push(campo.getAttribute('id'));
            });

            organizador.push(idsCampos);
        });

        var campoOrganizador = document.getElementById('campoOrganizador');
        campoOrganizador.value = JSON.stringify(organizador);
    }

    function validarInformacoesAdicionais() {

        var isValid = true;

        var params = {
            campos: coletarCamposGrid(),
            id_procedimento: document.getElementById('idProcedimento').value,
            campoOrganizador: document.getElementById('campoOrganizador').value
        };

        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_preenchiemnto_campos') ?>",
            dataType: "xml",
            data: params,
            async: false,
            success: function (result) {
                var msg = $(result).find('msg').text();
                if(msg != ''){
                    alert(msg);
                    isValid = false;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                valid = false;
                return false;
            }
        });


        return isValid;
    }

    function coletarCamposGrid() {
        var dados = {};

        $('#tbCamposSelecionados').find('input, select').each(function() {
            var input = $(this);
            var name = input.attr('name');
            var value = input.val();

            if (name) {
                dados[name] = value;
            }
        });

        return dados;
    }

</script>
