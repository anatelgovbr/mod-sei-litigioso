<?php if(0){?><script><?}?>
    var hdnTbDecisao = '';
    function inicializar(){

        <?if ($bolCadastro){ ?>

            var isMudanca = "<?= $bolHouveMudanca ?>";
            var valueNovo = '<?=$arrTabela?>';
            var obj = window.opener.document.getElementById('hdnTbDecisao');
            if(obj.value != ''){
                if(!isMudanca){
                    if(!confirm('Não ocorreu alteração das Decisões anteriores. A Situação Decisória atual mantém as Decisões anterior?')){
                        window.location = window.location.href;
                        return;
                    }
                }else{
                    if(!confirm('Ocorreram alterações das Decisões anteriores. Confirma alteração?')){
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

        //@todo melhorar condição, se a situação for nova msm se a ultima situação cadastrada for decisoria irá desabilitidar o cadastro
        // ou se não for a ultima situação cadastrada decisoria e a situação nova não for decisorio irá desabilitar tudo
        if( window.opener.document.getElementById('hdnIsGestor').value == 0 && (verificarSituacaoDecisaoNovo() && document.getElementById('hdnIdUltimaSituacaoDecisoria').value != '' || (document.getElementById('hdnIdUltimaSituacaoDecisoria').value == '' && (window.opener.document.getElementById('hdnErroSituacao').value == 1 || !window.opener.isTpSitDecisoria)))){
            document.getElementById('sbmCadastrarDecisao').style.display = 'none';
            infraDesabilitarCamposDiv(document.getElementById('divInfraAreaGlobal'));
        }

    }

    function validarTiposGestaoMulta(){
        var arrIdEspcecieDecisao = new Array();
        var currentElement = '';
        $('.especie-decisao').each(function(key, element){
             currentElement = element;
             if($(element).val() != null){
                arrIdEspcecieDecisao.push($(element).val());
             }
        });

        var objValidado = validarEspecieDecisaoGestaoMultasDiferentes(arrIdEspcecieDecisao);
        if(objValidado.valid == false){
            alert(objValidado.mensagem);
            $(currentElement).val(null);
            return false;
        }

        return true;
    }

    /**
     * Validação para nao permitir que um tipo de decisão tenha associada especies com tipo de miltas diferentes, por integracao e por valor
     * @param arrEspecies
     * @returns {boolean}
     */
    function validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies) {
        var valid = true;

        $.ajax({
            type: "POST",
            url: "<?php echo SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_especie_decisao');?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId': arrEspecies},
            success: function (data) {
                if ($(data).find('resultado').text() == '0') {
                    valid = false;
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
        return {'valid': valid, 'mensagem': "Não é possivel selecionar Espécies de Decisão com Indicação de Multa de tipos distintos (Gestão por Integração e Apenas Indicação de Valor) para o mesmo processo.\n" +
            "\nEscolha novamente as Espécies de Decisão para Indicação de Multa em conformidade com as novas parametrizações da Administração do SEI ou entre em contato com o Gestor do Controle Litigioso para dúvidas a respeito."};
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
                            //valor ressarcimento
                            table.rows[j].children[3].children[0].value = arrItens[i][5];
                            //input prazo
                            table.rows[j].children[5].children[0].value = arrItens[i][7] != 'null'? arrItens[i][7] :'';
                            //combo obrigacao
                            table.rows[j].children[4].children[0].value = arrItens[i][6];
                            if(!isSituacaoDecisaoNovo){
                                //id_decisao hidden
                                table.rows[j].children[0].children[2].value = arrItens[i][0];
                                //input id_usuario
                                table.rows[j].children[5].children[1].value = arrItens[i][8];
                                //input id_unidade
                                table.rows[j].children[5].children[2].value = arrItens[i][9];
                                //input data
                                table.rows[j].children[5].children[3].value = arrItens[i][13];
                                //input nome_usuario
                                table.rows[j].children[5].children[4].value = arrItens[i][14];
                                //input sigla_unidade
                                table.rows[j].children[5].children[5].value = arrItens[i][15];
                                //input sin_cadastro_parcial
                                 //table.rows[j].children[5].children[6].value = arrItens[i][18];
                            }
                        }else{
                            if(arrItens[i][16] == 'N'){
                                //checkbox localidade Nacional
                                table.rows[j].children[1].children[0].checked = true;
                                changeLocalidades(table.rows[j].children[1].children[0], false);
                            }else if(arrItens[i][16] == 'U'){
                                //checkbox localidade UF
                                table.rows[j].children[1].children[2].checked = true;
                                changeLocalidades(table.rows[j].children[1].children[2], true);

                                //select de UF
                                var selectUF = table.rows[j].children[1].children[4].children[0];
                                var arrUf =  arrItens[i][17].split('#');

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
                            table.rows[j].children[7].children[0].value = arrItens[i][7] != 'null' ? arrItens[i][7] : '';
                            //valor ressarcimento
                            table.rows[j].children[5].children[0].value = arrItens[i][5];
                            //combo obrigacao
                            table.rows[j].children[6].children[0].value = arrItens[i][6];
                            if(!isSituacaoDecisaoNovo){
                                //id_decisao hidden
                                table.rows[j].children[0].children[1].value = arrItens[i][0];
                                //input id_usuario
                                table.rows[j].children[7].children[1].value = arrItens[i][8];
                                //input id_unidade
                                table.rows[j].children[7].children[2].value = arrItens[i][9];
                                //input data
                                table.rows[j].children[7].children[3].value = arrItens[i][13];
                                //input nome_usuario
                                table.rows[j].children[7].children[4].value = arrItens[i][14];
                                //input sigla_unidade
                                table.rows[j].children[7].children[5].value = arrItens[i][15];
                                //input sin_cadastro_parcial
                                // table.rows[j].children[7].children[6].value = arrItens[i][18];
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
        var objSel, objMulta, objValorRessarcimento, objObrigacaoSelect, objPrazo;
        if(element.parentNode.parentNode.childNodes.length == 6){
            objSel              = element.parentNode.parentNode.childNodes[1].childNodes[0];
            objMulta            = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objValorRessarcimento  = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[5].childNodes[0];
        }else if(element.parentNode.parentNode.childNodes.length == 8){
            objSel              = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objMulta            = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objValorRessarcimento = element.parentNode.parentNode.childNodes[5].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[6].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[7].childNodes[0];
        }
        infraSelectLimpar(objObrigacaoSelect);
        infraSelectLimpar(objSel);
        objMulta.value = '';
        objValorRessarcimento.value = '';
        objPrazo.value = '';
        objMulta.style.display = 'none';
        objValorRessarcimento.style.display = 'none';
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

    function carregarTipoDecisao(element, valorSelecionado, flagCarregarDesativados){
        element.value = valorSelecionado;
        if(element.value != '' && flagCarregarDesativados != false){
            return;
        }

        //define se o combo ira carregar ou não com dados desativados na parametrizacao apos o evento change
        if(flagCarregarDesativados == false){
            flagCarregarDesativados = false;
        } else{
            flagCarregarDesativados = true;
        }

        //se o valor for selecionado e não existir no combo faz uma nova requisição retornando o valor não parametrizado
        infraSelectLimpar(element);
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxComboTipoDecisao ?>",
            async: false,
            data: {
                id_md_lit_tipo_controle: document.getElementById('hdnIdMdLitTipoControle').value,
                id_md_lit_tipo_decisao: valorSelecionado,
                flagCarregarDesativados: flagCarregarDesativados
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
        if(validarTiposGestaoMulta() == false){
            return false;
        };
        var objMulta            = null;
        var objValorRessarcimento = null;
        var objObrigacaoSelect  = null;
        var objPrazo            = null;

        if(element.parentNode.parentNode.childNodes.length == 6){
            objMulta            = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objValorRessarcimento = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[5].childNodes[0];
        }else if(element.parentNode.parentNode.childNodes.length == 8){
            objMulta            = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objValorRessarcimento = element.parentNode.parentNode.childNodes[5].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[6].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[7].childNodes[0];
        }

        objMulta.style.display = 'none';
        objMulta.value = '';
        objValorRessarcimento.style.display = 'none';
        objValorRessarcimento.value = '';
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
                        $('.multa').show();
                    }

                    if($(result).find('SinIndicacaoRessarcimentoValor').text() == 'S'){
                        objValorRessarcimento.style.display = '';
                        $('.ressarcimento').show();
                    }

                    if($(result).find('SinIndicacaoObrigacoes').text() == 'S'){
                        objObrigacaoSelect.style.display = '';
                        $('.obrigacoes').show();
                    }
                    infraSelectLimpar(objObrigacaoSelect);
                    if($(result).find('SinIndicacaoPrazo').text() == 'S'){
                        infraSelectAdicionarOption(objObrigacaoSelect,'','null');
                        objPrazo.style.display = '';
                        $('.prazo').show();
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
        showHideCamposDinamicos();
    }

    function refreshEspecieAtivos(element){
        var valorEspecieSelecionado = $(element)[0].value;
        var tipoDecisao = $(element).parent().prev()[0].childNodes[0];
        carregarComboEspecieDecisao(tipoDecisao, valorEspecieSelecionado);
        //element[0].value = valorEspecieSelecionado;

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
        var cell6 = row.insertCell(5);

        element.parentNode.rowSpan += 1;
        element.parentNode.nextElementSibling.rowSpan += 1;
        row.className = 'infraTrClara';
        cell1.style.textAlign = 'center';
        cell2.style.textAlign = 'center';

        cell3.className = 'multa';
        cell3.style.textAlign = 'center';
        cell3.style.display = 'none';

        cell4.className = 'ressarcimento';
        cell4.style.textAlign = 'center';
        cell4.style.display = 'none';

        cell5.style.textAlign = 'center';
        cell5.className = 'obrigacoes';
        cell5.style.display = 'none';

        cell6.className = 'prazo';
        cell6.style.textAlign = 'center';
        cell6.style.display = 'none';

        nomeLinha += '.'+element.parentNode.rowSpan;

        var optionTipoDecisao = document.getElementById("tipo_decisao_0").innerHTML;
        var cell1Html = '';
        cell1Html += '<select name="decisao['+nomeLinha+'][id_md_lit_tipo_decisao]" onchange="carregarComboEspecieDecisao(this); carregarTipoDecisao(this, $(this).val(), false)" style="width: 100%">';
        cell1Html += optionTipoDecisao;

        cell1.innerHTML = cell1Html+'</select>'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id]" value="'+valueId+'">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id_decisao]" value="" /> ';
        cell2.innerHTML = '<select class="especie-decisao" name="decisao['+nomeLinha+'][id_md_lit_especie_decisao]" onchange="refreshEspecieAtivos(this); carregarEspecieDecisao(this)" style="width: 100%;display: none"></select>';
        cell3.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][multa]" onkeypress="return infraMascaraDinheiro(this,event,2,12);" style="width: 90%;display: none">';
        cell4.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][valor_ressarcimento]" onkeypress="return infraMascaraDinheiro(this,event,2,12);" style="width: 90%;display: none">';
        cell5.innerHTML = '<select name="decisao['+nomeLinha+'][id_md_lit_obrigacao]" style="width: 100%;display: none"></select>';
        cell6.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][prazo]" style="width: 90%;display: none" onkeypress="return infraMascaraNumero(this,event,16);">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][id_usuario]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][id_unidade]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][data]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][nome_usuario]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][sigla_unidade]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][sin_cadastro_parcial]">';

        showHideCamposDinamicos();

        return row.rowIndex;
    }

    function showHideCamposDinamicos(){
        var showMulta = false;
        var showRessarcimento = false;
        var showPrazo = false;
        var showObrigacoes = false;

        $('.multa input').each(function(key, element){
            if($(element).css('display') != 'none'){
                showMulta = true;
                return false;
            }
        });

        $('.ressarcimento input').each(function(key, element){
            if($(element).css('display') != 'none'){
                showRessarcimento = true;
                return false;
            }
        });

        $('.obrigacoes select').each(function(key, element){
            if($(element).css('display') != 'none'){
                showObrigacoes = true;
                return false;
            }
        });

        $('.prazo input').each(function(key, element){
            if($(element).css('display') != 'none'){
                showPrazo = true;
                return false;
            }
        });


        if(showMulta) { $('.multa').show(); } else { $('.multa').hide(); }
        if(showRessarcimento) { $('.ressarcimento').show(); } else { $('.ressarcimento').hide(); }
        if(showObrigacoes) { $('.obrigacoes').show(); } else { $('.obrigacoes').hide(); }
        if(showPrazo) { $('.prazo').show(); } else { $('.prazo').hide(); }
    }

    function removerLinha(element){
        var table = document.getElementById("tableDadosComplementarInteressado");
        var indexUltimaLinha = element.parentNode.rowSpan + element.parentNode.parentNode.rowIndex - 1;
        table.deleteRow(indexUltimaLinha);
        //removendo o rowspan da coluna infração
        element.parentNode.rowSpan -= 1;
        //removendo o rowspan da coluna localidade
        element.parentNode.nextSibling.rowSpan -= 1;
        if(element.parentNode.rowSpan < 2){
            element.style.display = 'none';
        }

        showHideCamposDinamicos();
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
            strRow=strRow.join('±');
            str.push(strRow);
        }
    }

    function validar(){
        var numRows=document.getElementById("tableDadosComplementarInteressado").rows.length;
        var table = document.getElementById("tableDadosComplementarInteressado");
        var infracaoParcial=false;

        for (var i=1;i<numRows;i++){
//             verificar se a linha da tabela foi mesclado e se é a primeira opção entra no 1°if.
            if(table.rows[i].cells.length == 8){
                infracao            = table.rows[i].cells[0].childNodes[1].textContent;
                var tipoDecisao     = table.rows[i].cells[2].childNodes[0];
                var especieDecisao  = table.rows[i].cells[3].childNodes[0];
                var multa           = table.rows[i].cells[4].childNodes[0];
                var ressarcimento   = table.rows[i].cells[5].childNodes[0];
                var obrigacao       = table.rows[i].cells[6].childNodes[0];
                var prazo           = table.rows[i].cells[7].childNodes[0];
                var chkNacional     = table.rows[i].cells[1].childNodes[1];
                var chkUF           = table.rows[i].cells[1].childNodes[4];
                var selUF           = table.rows[i].cells[1].childNodes[6].childNodes[0];
                var cadastroParcial = table.rows[i].cells[7].childNodes[6];
            }else if(table.rows[i].cells.length == 6){
                var tipoDecisao     = table.rows[i].cells[0].childNodes[0];
                var especieDecisao  = table.rows[i].cells[1].childNodes[0];
                var multa           = table.rows[i].cells[2].childNodes[0];
                var ressarcimento   = table.rows[i].cells[3].childNodes[0];
                var obrigacao       = table.rows[i].cells[4].childNodes[0];
                var prazo           = table.rows[i].cells[5].childNodes[0];
                var cadastroParcial = table.rows[i].cells[5].childNodes[6];
            }
            if(table.rows[i].cells.length == 8 && !chkNacional.checked && !chkUF.checked){
                infracaoParcial = true;
            }else if(table.rows[i].cells.length == 8 && chkUF.checked && (selUF.value == '' || selUF.value == 'null')){
                infracaoParcial = true;
            }

            if(tipoDecisao.value != 'null' && tipoDecisao.style.display == ''){

                if((especieDecisao.value == 'null' || especieDecisao.value == '') && especieDecisao.style.display == ''){
                    infracaoParcial = true;
                }

                if((multa.value == 'null' || multa.value == '' || multa.value == '0,00') && multa.style.display == '' ){
                    infracaoParcial = true;
                }

                // if((ressarcimento.value == 'null' || ressarcimento.value == '' || ressarcimento.value == '0,00') && ressarcimento.style.display == '' ){
                //     infracaoParcial = true;
                // }

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
            msg = "Foi identificado que ainda existem infrações sem Decisão cadastrada. Posteriormente, para prosseguir com o cadastro de novas Situações ou a Gestão de Multa, ainda será necessário finalizar o Cadastro das Decisões.";
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