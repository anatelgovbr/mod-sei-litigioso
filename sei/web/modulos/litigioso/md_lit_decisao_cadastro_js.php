<?php if(0){?><script><?}?>
    var hdnTbDecisao = '';
    function inicializar(){

        <?if ($bolCadastro){ ?>

            var isMudanca = "<?= $bolHouveMudanca ?>";
            var valueNovo = '<?=$arrTabela?>';
            var obj = window.opener.document.getElementById('hdnTbDecisao');
            if(obj.value != ''){
                if(!isMudanca){
                    if(!confirm('N�o ocorreu altera��o das Decis�es anteriores. A Situa��o Decis�ria atual mant�m as Decis�es anterior?')){
                        window.location = window.location.href;
                        return;
                    }
                }else{
                    if(!confirm('Ocorreram altera��es das Decis�es anteriores. Confirma altera��o?')){
                        window.location = window.location.href;
                        return;
                    }
                }

            }

            obj.value = valueNovo;
            window.opener.objTabelaDinamicaDecisao.recarregar();
            window.opener.document.getElementById('tbDecisao').parentNode.style.display = '';
            if(typeof window.opener.carregarDependenciaMulta != 'undefined'){
                window.opener.removerOptionVazio(window.opener.document.getElementById('selCreditosProcesso'));
                window.opener.consultarExtratoMulta();
            }
            window.close();
            return;

        <?}?>

        $("select.infraSelect.multipleSelect").multipleSelect({
            filter: false,
            minimumCountSelected: 1,
            selectAll: false,
        }).parent('div').hide();

        hdnTbDecisao = window.opener.document.getElementById('hdnTbDecisao');
        if(hdnTbDecisao.value != ''){
            montaResultado();
        }

        //@todo melhorar condi��o, se a situa��o for nova msm se a ultima situa��o cadastrada for decisoria ir� desabilitidar o cadastro
        // ou se n�o for a ultima situa��o cadastrada decisoria e a situa��o nova n�o for decisorio ir� desabilitar tudo
        if( window.opener.document.getElementById('hdnIsGestor').value == 0 && (verificarSituacaoDecisaoNovo() && document.getElementById('hdnIdUltimaSituacaoDecisoria').value != '' || (document.getElementById('hdnIdUltimaSituacaoDecisoria').value == '' && (window.opener.document.getElementById('hdnErroSituacao').value == 1 || !window.opener.isTpSitDecisoria)))){
            document.getElementById('sbmCadastrarDecisao').style.display = 'none';
            infraDesabilitarCamposDiv(document.getElementById('divInfraAreaGlobal'));
        }

    }

    function verificarSituacaoDecisaoNovo(){
        var arrSituacaoItens = window.opener.objTabelaDinamicaSituacao.obterItens();

        if(!window.opener.isUltimaSituacaoDecisoria()){
            return false;
        }

        if(arrSituacaoItens.length > 0){
            for(var i = 0; i < arrSituacaoItens.length; i++ ){
                if(arrSituacaoItens[i][1] == 'N')
                    return true;
            }
        }
        return false;
    }

    function montaResultado(){
        var arrItens = window.opener.objTabelaDinamicaDecisao.obterItens();
        if(arrItens.length > 0){
            var idAnterior = 0;
            var isSituacaoDecisaoNovo = verificarSituacaoDecisaoNovo();
            document.getElementById('hdnTbDecisaoAntigo').value = hdnTbDecisao.value;
            for(var i = 0; i < arrItens.length; i++ ){
                var tamanhoTR = document.getElementById('tableDadosComplementarInteressado').rows.length;
                for (var j = 1;j < tamanhoTR; j++ ){
                    var table = document.getElementById('tableDadosComplementarInteressado');
                    if(arrItens[i][1] == table.rows[j].children[0].getElementsByTagName('input')[0].value){
                        if(idAnterior == arrItens[i][1]){

                            j = incluirLinha(table.rows[j].children[0].children[2]);
                            //combo tipo decisao
                            var selectTipoDecisao = table.rows[j].children[0].children[0];
                            carregarTipoDecisao(selectTipoDecisao,arrItens[i][2]);
                            // combo especie decisao
                            carregarComboEspecieDecisao(selectTipoDecisao, arrItens[i][3]);
                            //input multa
                            table.rows[j].children[2].children[0].value = arrItens[i][4];
                            //input prazo
                            table.rows[j].children[4].children[0].value = arrItens[i][6] != 'null'? arrItens[i][6] :'';
                            //combo obrigacao
                            table.rows[j].children[3].children[0].value = arrItens[i][5];
                            if(!isSituacaoDecisaoNovo){
                                //id_decisao hidden
                                table.rows[j].children[0].children[2].value = arrItens[i][0];
                                //input id_usuario
                                table.rows[j].children[4].children[1].value = arrItens[i][7];
                                //input id_unidade
                                table.rows[j].children[4].children[2].value = arrItens[i][8];
                                //input data
                                table.rows[j].children[4].children[3].value = arrItens[i][12];
                                //input nome_usuario
                                table.rows[j].children[4].children[4].value = arrItens[i][13];
                                //input sigla_unidade
                                table.rows[j].children[4].children[5].value = arrItens[i][14];
                                //input sin_cadastro_parcial
                                // table.rows[j].children[4].children[6].value = arrItens[i][1];
                            }
                        }else{
                            if(arrItens[i][15] == 'N'){
                                //checkbox localidade Nacional
                                table.rows[j].children[1].children[0].checked = true;
                                changeLocalidades(table.rows[j].children[1].children[0], false);
                            }else if(arrItens[i][15] == 'U'){
                                //checkbox localidade UF
                                table.rows[j].children[1].children[2].checked = true;
                                changeLocalidades(table.rows[j].children[1].children[2], true);

                                //select de UF
                                var selectUF = table.rows[j].children[1].children[4].children[0];
                                var arrUf =  arrItens[i][16].split('#');

                                $(selectUF).multipleSelect("setSelects", arrUf);

                            }
                            //combo tipo decisao
                            var selectTipoDecisao = table.rows[j].children[2].children[0];
                            carregarTipoDecisao(selectTipoDecisao,arrItens[i][2]);
                            // combo especie decisao
                            carregarComboEspecieDecisao(selectTipoDecisao, arrItens[i][3]);
                            //input multa
                            table.rows[j].children[4].children[0].value = arrItens[i][4];
                            //input prazo
                            table.rows[j].children[6].children[0].value = arrItens[i][6] != 'null' ? arrItens[i][6] : '';
                            //combo obrigacao
                            table.rows[j].children[5].children[0].value = arrItens[i][5];
                            if(!isSituacaoDecisaoNovo){
                                //id_decisao hidden
                                table.rows[j].children[0].children[1].value = arrItens[i][0];
                                //input id_usuario
                                table.rows[j].children[6].children[1].value = arrItens[i][7];
                                //input id_unidade
                                table.rows[j].children[6].children[2].value = arrItens[i][8];
                                //input data
                                table.rows[j].children[6].children[3].value = arrItens[i][12];
                                //input nome_usuario
                                table.rows[j].children[6].children[4].value = arrItens[i][13];
                                //input sigla_unidade
                                table.rows[j].children[6].children[5].value = arrItens[i][14];
                                //input sin_cadastro_parcial
                                // table.rows[j].children[6].children[6].value = arrItens[i][14];
                            }
                        }
                        idAnterior = arrItens[i][1];
                        break;
                    }
                }

            }
        }
    }

    function carregarComboEspecieDecisao(element, valorSelecionado){
        var objSel, objMulta, objObrigacaoSelect, objPrazo;
        if(element.parentNode.parentNode.childNodes.length == 5){
            objSel              = element.parentNode.parentNode.childNodes[1].childNodes[0];
            objMulta            = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[4].childNodes[0];
        }else if(element.parentNode.parentNode.childNodes.length == 7){
            objSel              = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objMulta            = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[5].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[6].childNodes[0];
        }
        infraSelectLimpar(objObrigacaoSelect);
        infraSelectLimpar(objSel);
        objMulta.value = '';
        objPrazo.value = '';
        objMulta.style.display = 'none';
        objPrazo.style.display = 'none';
        objObrigacaoSelect.style.display = 'none';
        objSel.style.display = 'none';
        if(element.value != 'null'){
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxComboEspecieDecisao ?>",
                async: false,
                data: {id_md_lit_tipo_decisao: element.value,
                    id_md_lit_tipo_controle:document.getElementById('hdnIdMdLitTipoControle').value,
                    id_md_lit_especie_decisao:valorSelecionado},
                success: function (result) {
                    if($(result).find('erro').length > 0){
                        alert($(result).find('erro').attr('descricao'));
                        return;
                    }

                    infraSelectLimpar(objSel);
                    $.each($(result).find('option'), function(key, value){
                        var id = $(value).val();
                        var texto = $(value).text() == '%20' ? '': $(value).text();
                        infraSelectAdicionarOption(objSel,texto,id);
                        if($(value).attr("selected")=="selected"){
                            infraSelectSelecionarItem(objSel,id);
                            carregarEspecieDecisao(objSel);
                        }else if(valorSelecionado == id){
                            infraSelectSelecionarItem(objSel,id);
                            carregarEspecieDecisao(objSel);
                        }

                    });
                    if(objSel.options.length > 0)
                        objSel.style.display = '';

                }
            });
        }else{
            infraSelectLimpar(objSel);
            objSel.style.display = 'none';
        }
    }

    function carregarTipoDecisao(element, valorSelecionado){
        element.value = valorSelecionado;
        if(element.value != ''){
            return;
        }

        //se o valor for selecionado e n�o existir no combo faz uma nova requisi��o retornando o valor n�o parametrizado
        infraSelectLimpar(element);
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxComboTipoDecisao ?>",
            async: false,
            data: {
                id_md_lit_tipo_controle: document.getElementById('hdnIdMdLitTipoControle').value,
                id_md_lit_tipo_decisao: valorSelecionado

            },
            success: function (result) {

                if($(result).find('erro').length > 0){
                    alert($(result).find('erro').attr('descricao'));
                    return;
                }

                infraSelectLimpar(element);
                $.each($(result).find('option'), function(key, value){
                    var id = $(value).val();
                    var texto = $(value).text() == '%20' ? '': $(value).text();
                    infraSelectAdicionarOption(element,texto,id);
                    if($(value).attr("selected")=="selected"){
                        infraSelectSelecionarItem(element,id);
                    }else if(valorSelecionado == id){
                        infraSelectSelecionarItem(element,id);
                        carregarEspecieDecisao(element);
                    }

                });
                if(element.options.length > 0)
                    element.style.display = '';
            }
        });

    }
    function carregarEspecieDecisao(element){
        var objMulta            = null;
        var objObrigacaoSelect  = null;
        var objPrazo            = null;

        if(element.parentNode.parentNode.childNodes.length == 5){
            objMulta            = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[4].childNodes[0];
        }else if(element.parentNode.parentNode.childNodes.length == 7){
            objMulta            = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[5].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[6].childNodes[0];
        }

        objMulta.style.display = 'none';
        objMulta.value = '';
        objPrazo.style.display = 'none';
        objPrazo.value = '';
        objObrigacaoSelect.style.display = 'none';
        infraSelectLimpar(objObrigacaoSelect);
        if(element.value != 'null'){
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxEspecieDecisao ?>",
                dataType: "xml",
                async: false,
                data: {id_md_lit_especie_decisao: element.value},
                success: function (result) {
                    if($(result).find('erro').length > 0){
                        alert($(result).find('erro').attr('descricao'));
                        return;
                    }
                    if($(result).find('SinGestaoMulta').text() == 'S'){
                        objMulta.style.display = '';
                    }

                    if($(result).find('SinIndicacaoObrigacoes').text() == 'S'){
                        objObrigacaoSelect.style.display = '';
                    }
                    infraSelectLimpar(objObrigacaoSelect);
                    if($(result).find('SinIndicacaoPrazo').text() == 'S'){
                        infraSelectAdicionarOption(objObrigacaoSelect,'','null');
                        objPrazo.style.display = '';
                    }

                    $.each($(result).find('obrigacao'), function(key, value){

                        var id = $(this).find('IdObrigacaoLitigioso').text();
                        var texto = $(value).find('NomeObrigacao').text();
                        infraSelectAdicionarOption(objObrigacaoSelect,texto,id);
                        if($(value).attr("selected")=="selected"){
                            infraSelectSelecionarItem(objObrigacaoSelect,id);
                        }
                    });
                }
            });
        }
    }

    function incluirLinha(element){
        var table = document.getElementById("tableDadosComplementarInteressado");
        var nomeLinha = element.getAttribute('nome-linha');
        var valueId   = element.getAttribute('value');
        element.nextSibling.style.display = '';

        var row = table.insertRow(element.parentNode.parentNode.rowIndex + element.parentNode.rowSpan);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);

        element.parentNode.rowSpan += 1;
        element.parentNode.nextElementSibling.rowSpan += 1;
        row.className = 'infraTrClara';
        cell1.style.textAlign = 'center';
        cell2.style.textAlign = 'center';
        cell3.style.textAlign = 'center';
        cell4.style.textAlign = 'center';
        cell5.style.textAlign = 'center';
        nomeLinha += '.'+element.parentNode.rowSpan;

        var optionTipoDecisao = document.getElementById("tipo_decisao_0").innerHTML;
        var cell1Html = '';
        cell1Html += '<select name="decisao['+nomeLinha+'][id_md_lit_tipo_decisao]" onchange="carregarComboEspecieDecisao(this)" style="width: 100%">';
        cell1Html += optionTipoDecisao;

        cell1.innerHTML = cell1Html+'</select>'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id]" value="'+valueId+'">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id_decisao]" value="" /> ';
        cell2.innerHTML = '<select name="decisao['+nomeLinha+'][id_md_lit_especie_decisao]" onchange="carregarEspecieDecisao(this)" style="width: 100%;display: none"></select>';
        cell3.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][multa]" onkeypress="return infraMascaraDinheiro(this,event,2,12);" style="width: 90%;display: none">';
        cell4.innerHTML = '<select name="decisao['+nomeLinha+'][id_md_lit_obrigacao]" style="width: 100%;display: none"></select>';
        cell5.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][prazo]" style="width: 90%;display: none" onkeypress="return infraMascaraNumero(this,event,16);">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][id_usuario]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][id_unidade]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][data]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][nome_usuario]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][sigla_unidade]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][sin_cadastro_parcial]">';

        return row.rowIndex;
    }

    function removerLinha(element){debugger;
        var table = document.getElementById("tableDadosComplementarInteressado");
        var indexUltimaLinha = element.parentNode.rowSpan + element.parentNode.parentNode.rowIndex - 1;
        table.deleteRow(indexUltimaLinha);
        //removendo o rowspan da coluna infra��o
        element.parentNode.rowSpan -= 1;
        //removendo o rowspan da coluna localidade
        element.parentNode.nextSibling.rowSpan -= 1;
        if(element.parentNode.rowSpan < 2){
            element.style.display = 'none';
        }
    }

    function atualizarHdn(){

        var numRows=document.getElementById("tableDadosComplementarInteressado").rows.length;
        var cells,str=[],strRow, i,i2,numColunas=document.getElementById("tableDadosComplementarInteressado").rows[0].cells.length;
        for (i=1;i<numRows;i++){
            cells=document.getElementById("tableDadosComplementarInteressado").rows[i].getElementsByTagName('td');
            strRow=[];
            for (i2=0;i2<numColunas;i2++) {
                var input = cells[i2].getElementsByTagName('input').length > 0 ? cells[i2].getElementsByTagName('input') : cells[i2].getElementsByTagName('select');
                strRow.push(input[0].value);
            }
            strRow=strRow.join('�');
            str.push(strRow);
        }
    }

    function validar(){
        var numRows=document.getElementById("tableDadosComplementarInteressado").rows.length;
        var table = document.getElementById("tableDadosComplementarInteressado");
        var infracaoParcial=false;

        for (var i=1;i<numRows;i++){
//             verificar se a linha da tabela foi mesclado e se � a primeira op��o entra no 1�if.
            if(table.rows[i].cells.length == 7){
                infracao            = table.rows[i].cells[0].childNodes[1].textContent;
                var tipoDecisao     = table.rows[i].cells[2].childNodes[0];
                var especieDecisao  = table.rows[i].cells[3].childNodes[0];
                var multa           = table.rows[i].cells[4].childNodes[0];
                var obrigacao       = table.rows[i].cells[5].childNodes[0];
                var prazo           = table.rows[i].cells[6].childNodes[0];
                var chkNacional     = table.rows[i].cells[1].childNodes[1];
                var chkUF           = table.rows[i].cells[1].childNodes[4];
                var selUF           = table.rows[i].cells[1].childNodes[6].childNodes[0];
                var cadastroParcial = table.rows[i].cells[6].childNodes[6];
            }else if(table.rows[i].cells.length == 5){
                var tipoDecisao     = table.rows[i].cells[0].childNodes[0];
                var especieDecisao  = table.rows[i].cells[1].childNodes[0];
                var multa           = table.rows[i].cells[2].childNodes[0];
                var obrigacao       = table.rows[i].cells[3].childNodes[0];
                var prazo           = table.rows[i].cells[4].childNodes[0];
                var cadastroParcial = table.rows[i].cells[4].childNodes[6];
            }
            if(table.rows[i].cells.length == 7 && !chkNacional.checked && !chkUF.checked){
                infracaoParcial = true;
            }else if(table.rows[i].cells.length == 7 && chkUF.checked && (selUF.value == '' || selUF.value == 'null')){
                infracaoParcial = true;
            }

            if(tipoDecisao.value != 'null' && tipoDecisao.style.display == ''){

                if((especieDecisao.value == 'null' || especieDecisao.value == '') && especieDecisao.style.display == ''){
                    infracaoParcial = true;
                }

                if((multa.value == 'null' || multa.value == '' || multa.value == '0,00') && multa.style.display == '' ){
                    infracaoParcial = true;
                }

                if((obrigacao.value == 'null' || obrigacao.value == '')  && obrigacao.style.display == ''){
                    infracaoParcial = true;
                }

                if((prazo.value == 'null' || prazo.value == '')  && prazo.style.display == ''){
                    infracaoParcial = true;
                }
            }else{
                infracaoParcial = true;
            }

            if(infracaoParcial){
                cadastroParcial.value = 'S';
            }else{
                cadastroParcial.value = 'N';
            }


        }


        if(infracaoParcial){
            msg = "Foi identificado que ainda existem infra��es sem Decis�o cadastrada. Posteriormente, para prosseguir com o cadastro de novas Situa��es ou a Gest�o de Multa, ainda ser� necess�rio finalizar o Cadastro das Decis�es.";
            alert(msg);
        }
        return true;
    }


    function OnSubmitForm(){
        if(!validar())
            return false;

    }

    function changeLocalidades(element, isUf){
        var elementSelectUF = document.getElementById(element.getAttribute('data-id-select-uf'));

        if(isUf){
            elementSelectUF.style.display = '';
        }else{
            elementSelectUF.style.display = 'none';
            elementSelectUF.value = 'null';
        }
    }
<? if(0){?></script><?}?>