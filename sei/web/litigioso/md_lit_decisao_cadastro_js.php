<script type="text/javascript">

    setTimeout(function(){
        processando();
    },0);

    var hdnTbDecisao = '';
    var relacaoDecisaoLancamentoPai = {};
    var telaPai      = window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao");
    function inicializar(){

        <?if ($bolCadastro){ ?>

        var isMudanca = "<?= $bolHouveMudanca ?>";
        var valueNovo = '<?=$arrTabela?>';
        var obj = telaPai.contentWindow.document.getElementById('hdnTbDecisao');
        var arrayRetorno = processarItemListas(valueNovo);
        var situacaoParcial = false;

        for(linhas = 0; linhas < arrayRetorno.length; linhas++){
            if(arrayRetorno[linhas][18] == 'S'){
                situacaoParcial = true;
            }
        }
        if(situacaoParcial) {
            alert('Foi identificado que ainda existem infrações sem Decisão cadastrada. Posteriormente, para prosseguir com o cadastro de novas Situações ou a Gestão de Multa, ainda será necessário finalizar o Cadastro das Decisões.');
        }
        var lancamentosAlteradosDecisao = <?= json_encode(array_values($arrLancamentosAlteradosDecisao)) ?>;
        registrarResultadoLancamentosAlteradosDecisao(lancamentosAlteradosDecisao);
        obj.value = valueNovo;

        if(obj.value != ''){
            if(!isMudanca){
                if(!confirm('Não ocorreu alteração das Decisões anteriores. A Situação Decisória atual mantém as Decisões anterior?')){
                    window.location = window.location.href;
                    return;
                }
            }else{
                if(!verificarSituacaoDecisaoNovo()){
                    if(!confirm('Ocorreram alterações das Decisões anteriores. Confirma alteração?')){
                        window.location = window.location.href;
                        return;
                    }
                }
            }

        }

        window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.objTabelaDinamicaDecisao.recarregar();
        window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('tbDecisao').parentNode.style.display = '';
        if(typeof window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.carregarDependenciaMulta != 'undefined'){
            window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.removerOptionVazio(window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('selCreditosProcesso'));
            if(typeof window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.selecionarLancamentoAlteradoDecisao != 'undefined'){
                window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.selecionarLancamentoAlteradoDecisao();
            }
            window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.consultarExtratoMulta();
        }

        $(window.top.document).find('div[id^=divInfraSparklingModalClose]').click();

        return;

        <?}?>

        $('#hdnTbDecisaoAntigo').val(window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById("hdnTbDecisao").value);
        $("select.infraSelect.multipleSelect").multipleSelect({
            filter: false,
            minimumCountSelected: 1,
            selectAll: false,
        }).parent('div').hide();

        hdnTbDecisao = telaPai.contentWindow.document.getElementById("hdnTbDecisao");
        if(hdnTbDecisao.value != ''){
            montaResultado();
        }

        //@todo melhorar condição, se a situação for nova msm se a ultima situação cadastrada for decisoria irá desabilitidar o cadastro
        // ou se não for a ultima situação cadastrada decisoria e a situação nova não for decisorio irá desabilitar tudo
        if( window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('hdnIsGestor').value == 0 && (verificarSituacaoDecisaoNovo() && document.getElementById('hdnIdUltimaSituacaoDecisoria').value != '' || (document.getElementById('hdnIdUltimaSituacaoDecisoria').value == '' && (window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('hdnErroSituacao').value == 1 || !window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.isTpSitDecisoria)))){
            document.getElementById('sbmCadastrarDecisao').style.display = 'none';
            infraDesabilitarCamposDiv(document.getElementById('divInfraAreaGlobal'));
        }
        infraAvisoCancelar();
        obterRelacaoDecisaoLancamento();
        monitorarMultaLancamento();
    }

    function exibirBotaoCancelarAviso(){

        var div = document.getElementById('divInfraAvisoFundo');

        if (div!=null && div.style.visibility == 'visible'){

            var botaoCancelar = document.getElementById('btnInfraAvisoCancelar');

            if (botaoCancelar != null){
                botaoCancelar.style.display = 'block';
            }
        }
    }

    function exibirAvisoEditor(){

        var divFundo = document.getElementById('divInfraAvisoFundo');

        if (divFundo==null){
            divFundo = infraAviso(false, 'Processando...');
        }else{
            document.getElementById('btnInfraAvisoCancelar').style.display = 'none';
            document.getElementById('imgInfraAviso').src='/infra_css/imagens/aguarde.gif';
        }

        if (INFRA_IE==0 || INFRA_IE>=7){
            divFundo.style.position = 'fixed';
        }

        var divAviso = document.getElementById('divInfraAviso');

        divAviso.style.top = Math.floor(infraClientHeight()/3) + 'px';
        divAviso.style.left = Math.floor((infraClientWidth()-200)/2) + 'px';
        divAviso.style.width = '200px';
        divAviso.style.border = '1px solid black';

        divFundo.style.width = screen.width*2 + 'px';
        divFundo.style.height = screen.height*2 + 'px';
        divFundo.style.visibility = 'visible';

    }

    function processando() {

        exibirAvisoEditor();
        timeoutExibirBotao = self.setTimeout('exibirBotaoCancelarAviso()',30000);

        if (INFRA_IE>0) {
            window.tempoInicio=(new Date()).getTime();
        } else {
            console.time('s');
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

    function verificarSituacaoDecisaoNovo(){
        var arrSituacaoItens = window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.objTabelaDinamicaSituacao.obterItens();

        if(!window.top.document.getElementById("ifrConteudoVisualizacao").contentWindow.document.getElementById("ifrVisualizacao").contentWindow.isUltimaSituacaoDecisoria()){
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
        const arrItens = telaPai.contentWindow.objTabelaDinamicaDecisao.obterItens();
        if( arrItens.length > 0 ){
            var idAnterior = 0;
            var isSituacaoDecisaoNovo = verificarSituacaoDecisaoNovo();

            if( telaPai.contentWindow.document.getElementById('hdnVlOriginalMultas').value == '' ){
                telaPai.contentWindow.document.getElementById('hdnVlOriginalMultas').value = hdnTbDecisao.value;
            }

            let table            = document.getElementById('tableDadosComplementarInteressado');
            let idxElemTableGrid = 0;
            for ( let j = 1 ; j < table.rows.length ; j++  ) {
                for ( let i = 0 ; i < arrItens.length ; i++ ) {
                    if ( table.rows.length > 0 ) {
                        // se loop for de infração igual ao anterior
                        if ( arrItens[i][1] == table.rows[j].children[0].getElementsByTagName('input')[0].value ) {
                            if ( idAnterior == arrItens[i][1] ) {
                                j = incluirLinha(table.rows[j].children[0].children[3]);
                                //combo tipo decisao
                                var selectTipoDecisao = table.rows[j].children[0].children[0]
                                carregarTipoDecisao(selectTipoDecisao, arrItens[i][2]);
                                // combo especie decisao
                                carregarComboEspecieDecisao(selectTipoDecisao, arrItens[i][3]);
                                //input multa
                                table.rows[j].children[2].children[0].value = arrItens[i][4];
                                //valor ressarcimento
                                table.rows[j].children[3].children[0].value = arrItens[i][5];
                                //input prazo
                                table.rows[j].children[5].children[0].value = arrItens[i][7] != 'null' ? arrItens[i][7] : '';
                                //combo obrigacao
                                table.rows[j].children[4].children[0].value = arrItens[i][6];
                                if (!isSituacaoDecisaoNovo) {
                                    //id_decisao hidden
                                    table.rows[j].children[0].children[2].value = arrItens[i][0];
                                    if (typeof arrItens[i][20] != 'undefined') {
                                        table.rows[j].children[0].children[3].value = arrItens[i][20] ? arrItens[i][20] : '';
                                    } else {
                                        table.rows[j].children[0].children[3].value = '';
                                    }
                                    if (typeof arrItens[i][19] != 'undefined') {
                                        table.rows[j].children[0].children[4].value = arrItens[i][19] ? arrItens[i][19] : 'null';
                                    }
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
                                } else {
                                    table.rows[j].children[0].children[2].value = '';
                                    table.rows[j].children[0].children[3].value = arrItens[i][0];
                                    if (typeof arrItens[i][19] != 'undefined') {
                                        table.rows[j].children[0].children[4].value = arrItens[i][19] ? arrItens[i][19] : 'null';
                                    }
                                }
                            } else {
                                let elem = null;
                                if (arrItens[i][16] == 'N') {
                                    //checkbox localidade Nacional
                                    elem = document.querySelector('#rdDispositivoNormativo_localidade_' + idxElemTableGrid);
                                    elem.checked = true;
                                    changeLocalidades(elem, false);
                                } else if (arrItens[i][16] == 'U') {
                                    //checkbox localidade U
                                    elem = document.querySelector('#rdDispositivoNormativo_uf_' + idxElemTableGrid);
                                    elem.checked = true;
                                    changeLocalidades(elem, true);
                                    var selectUF = document.querySelector('#selUf_' + idxElemTableGrid);
                                    var arrUf = arrItens[i][17].split('#');
                                    $(selectUF).multipleSelect("setSelects", arrUf);
                                }

                                //combo tipo decisao
                                var selectTipoDecisao = table.rows[j].children[2].children[0];
                                setTimeout(carregarTipoDecisao(selectTipoDecisao, arrItens[i][2]), 500);
                                // combo especie decisao
                                setTimeout(carregarComboEspecieDecisao(selectTipoDecisao, arrItens[i][3]), 500);
                                //input multa
                                if (arrItens[i][4]) {
                                    table.rows[j].children[4].children[0].value = arrItens[i][4];
                                }
                                //input prazo
                                table.rows[j].children[7].children[0].value = arrItens[i][7] != 'null' ? arrItens[i][7] : '';
                                //valor ressarcimento
                                table.rows[j].children[5].children[0].value = arrItens[i][5];
                                //combo obrigacao
                                table.rows[j].children[6].children[0].value = arrItens[i][6];
                                if (!isSituacaoDecisaoNovo) {
                                    //id_decisao hidden
                                    table.rows[j].children[0].children[1].value = arrItens[i][0];
                                    if (typeof arrItens[i][20] != 'undefined') {
                                        table.rows[j].children[0].children[2].value = arrItens[i][20] ? arrItens[i][20] : '';
                                    } else {
                                        table.rows[j].children[0].children[2].value = '';
                                    }
                                    if (typeof arrItens[i][19] != 'undefined') {
                                        table.rows[j].children[0].children[3].value = arrItens[i][19] ? arrItens[i][19] : 'null';
                                    }
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
                                } else {
                                    table.rows[j].children[0].children[1].value = '';
                                    table.rows[j].children[0].children[2].value = arrItens[i][0];
                                    if (typeof arrItens[i][19] != 'undefined') {
                                        table.rows[j].children[0].children[3].value = arrItens[i][19] ? arrItens[i][19] : 'null';
                                    }
                                }
                                idxElemTableGrid++;
                            }
                            idAnterior = arrItens[i][1];
                        }
                    } else {
                        setTimeout(montaResultado, 200);
                    }
                }
            }
        }
    }

    function carregarComboEspecieDecisao(element, valorSelecionado){
        var objSel, objMulta, objValor, objObrigacaoSelect, objPrazo;
        if(element.parentNode.parentNode.childNodes.length == 6){
            objSel              = element.parentNode.parentNode.childNodes[1].childNodes[0];
            objMulta            = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objValor  = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[5].childNodes[0];
        }else if(element.parentNode.parentNode.childNodes.length == 8){
            objSel              = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objMulta            = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objValor = element.parentNode.parentNode.childNodes[5].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[6].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[7].childNodes[0];
        }
        infraSelectLimpar(objObrigacaoSelect);
        infraSelectLimpar(objSel);
        objMulta.value = '';
        objValor.value = '';
        objPrazo.value = '';
        objMulta.style.display = 'none';
        objValor.style.display = 'none';
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
        var objValor = null;
        var objObrigacaoSelect  = null;
        var objPrazo            = null;

        if(element.parentNode.parentNode.childNodes.length == 6){
            objMulta            = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objValor = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[5].childNodes[0];
        }else if(element.parentNode.parentNode.childNodes.length == 8){
            objMulta            = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objValor = element.parentNode.parentNode.childNodes[5].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[6].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[7].childNodes[0];
        }

        objMulta.style.display = 'none';
        objMulta.value = '';
        objValor.style.display = 'none';
        objValor.value = '';
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

                    if($(result).find('SinIndicacaoValor').text() == 'S'){
                        objValor.style.display = '';
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
        cell1Html += '<select name="decisao['+nomeLinha+'][id_md_lit_tipo_decisao]" class="infraSelect form-control" onchange="carregarComboEspecieDecisao(this); carregarTipoDecisao(this, $(this).val(), false)" style="width: 100%">';
        cell1Html += optionTipoDecisao;

        cell1.innerHTML = cell1Html+'</select>'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id]" value="'+valueId+'">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id_decisao]" value="" /> <input type="hidden" name="decisao['+nomeLinha+'][id_decisao_origem]" value="" /> <input type="hidden" name="decisao['+nomeLinha+'][id_lancamento]" value="null" /> ';
        cell2.innerHTML = '<select class="especie-decisao infraSelect form-control" name="decisao['+nomeLinha+'][id_md_lit_especie_decisao]" onchange="refreshEspecieAtivos(this); carregarEspecieDecisao(this)" style="width: 100%;display: none"></select>';
        cell3.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][multa]" name="decisao['+nomeLinha+'][multa]" class="infraText form-control" onkeypress="return infraMascaraDinheiro(this,event,2,12);" style="width: 90%;display: none" decisao_valor_antigo="">';
        cell4.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][valor_ressarcimento]" class="infraText form-control" onkeypress="return infraMascaraDinheiro(this,event,2,12);" style="width: 90%;display: none">';
        cell5.innerHTML = '<select name="decisao['+nomeLinha+'][id_md_lit_obrigacao]" class="infraSelect form-control" style="width: 100%;display: none"></select>';
        cell6.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][prazo]" class="infraText form-control" style="width: 90%;display: none" onkeypress="return infraMascaraNumero(this,event,16);">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id_usuario]">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id_unidade]">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][data]">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][nome_usuario]">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][sigla_unidade]">'+
            '<input type="hidden" id="decisao_'+nomeLinha+'_sin_cadastro_parcial" name="decisao['+nomeLinha+'][sin_cadastro_parcial]">';

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
        var idTr = '';
        var digito = 0;
        var rowspan = 0;
        for (var i=1;i<numRows;i++){
//             verificar se a linha da tabela foi mesclado e se é a primeira opção entra no 1°if.
            infracaoParcial=false;
            if(table.rows[i].cells.length == 8){
                infracao            = table.rows[i].cells[0].childNodes[1].textContent;
                var tipoDecisao     = table.rows[i].cells[2].childNodes[0];
                var especieDecisao  = table.rows[i].cells[3].childNodes[0];
                var multa           = table.rows[i].cells[4].childNodes[0];
                var ressarcimento   = table.rows[i].cells[5].childNodes[0];
                var obrigacao       = table.rows[i].cells[6].childNodes[0];
                var prazo           = table.rows[i].cells[7].childNodes[0];
                //var chkNacional     = document.getElementById('rdDispositivoNormativo_localidade_'+(i-1))
                //var chkUF           = document.getElementById('rdDispositivoNormativo_uf_'+(i-1))
                var chkNacional     = $(table.rows[i].cells[1]).find('input[type=radio][value="N"]')[0];
                var chkUF           = $(table.rows[i].cells[1]).find('input[type=radio][value="U"]')[0];
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

                if((ressarcimento.value == 'null' || ressarcimento.value == '' || ressarcimento.value == '0,00') && ressarcimento.style.display == '' ){
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

            var posicao = '';
            if(table.rows[i].id != ''){
                idTr = table.rows[i].id;
                digito = 0;
            }
            var arrPosicao = idTr.split('_');
            posicao = arrPosicao[1];
            rowspan = $('#CadastroDecisaoTable_' + posicao + ' > td#td-infracao' + posicao).prop('rowspan');
            if(rowspan > 1){
                if(digito == 0){
                    digito = 2;
                } else if(digito > 0 && digito <= rowspan){
                    posicao = posicao + "." + digito;
                    digito = digito+1;
                }

            }

            if(infracaoParcial){
                cadastroParcial.value = 'S';
                document.getElementById('decisao_idDispositivoNormativo_' + posicao + '_sin_cadastro_parcial').value = 'S';
            }else{
                cadastroParcial.value = 'N';
                document.getElementById('decisao_idDispositivoNormativo_' + posicao + '_sin_cadastro_parcial').value = 'N';
            }
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
            if(elementSelectUF != null){
                elementSelectUF.style.display = '';
            }
        }else{
            if(elementSelectUF != null) {
                elementSelectUF.style.display = 'none';
                elementSelectUF.value = 'null';
            }
        }
    }

    // TODO refatorar para usar a versão do Infra JS
    function processarItemListas(linhaString){
        var linhas = linhaString.split("¥");
        var arrayRetorno = [];
        for (linha = 0; linha < linhas.length; linha++) {
            var itens = linhas[linha].split("±");
            var arrayParcial = [];
            for (item = 0; item < itens.length; item++){
                arrayParcial.push(itens[item]);

            }
            arrayRetorno.push(arrayParcial);
        }
        return arrayRetorno;
    }

    function obterRelacaoDecisaoLancamento(){
        relacaoDecisaoLancamentoPai = {};
        var docPai = window.top.document
            .getElementById("ifrConteudoVisualizacao").contentWindow.document
            .getElementById("ifrVisualizacao").contentWindow.document;

        var tbDecisao = docPai.getElementById('tbDecisao');
        if (tbDecisao) {
            var linhas = tbDecisao.getElementsByTagName('tr');
            for (var i = 1; i < linhas.length; i++) {
                var tds = linhas[i].getElementsByTagName('td');
                if (!tds || tds.length < 20) {
                    continue;
                }

                var idDecisao = (tds[0].textContent || '').trim();
                var idLancamento = (tds[19].textContent || '').trim();
                var idDecisaoOrigem = tds.length > 20 ? (tds[20].textContent || '').trim() : '';

                if (idDecisao && idLancamento && idLancamento !== 'null') {
                    relacaoDecisaoLancamentoPai[idDecisao] = idLancamento;
                }
                if (idDecisaoOrigem && idLancamento && idLancamento !== 'null') {
                    relacaoDecisaoLancamentoPai[idDecisaoOrigem] = idLancamento;
                }
            }
        }

        listarMultasComIdDecisao();
    }

    function listarMultasComIdDecisao() {
        var tabela = document.getElementById("tableDadosComplementarInteressado");

        if (!tabela) return;

        var inputsMulta = tabela.querySelectorAll("input[name$='[multa]']");
        inputsMulta.forEach(function(inputMulta){
            var tr = inputMulta.closest("tr");
            var inputLancamento = tr ? tr.querySelector("input[name$='[id_lancamento]']") : null;
            var inputIdDecisao = tr ? tr.querySelector("input[name$='[id_decisao]']") : null;
            var inputIdDecisaoOrigem = tr ? tr.querySelector("input[name$='[id_decisao_origem]']") : null;
            var idDecisao = inputIdDecisao ? inputIdDecisao.value : '';
            var idDecisaoOrigem = inputIdDecisaoOrigem ? inputIdDecisaoOrigem.value : '';
            var idLancamento = inputLancamento ? inputLancamento.value : 'null';
            if (idDecisaoOrigem && relacaoDecisaoLancamentoPai[idDecisaoOrigem]) {
                idLancamento = relacaoDecisaoLancamentoPai[idDecisaoOrigem];
            } else if (idDecisao && relacaoDecisaoLancamentoPai[idDecisao]) {
                idLancamento = relacaoDecisaoLancamentoPai[idDecisao];
            }
            if (inputLancamento) {
                inputLancamento.value = idLancamento || 'null';
            }
            inputMulta.setAttribute('decisao_valor_antigo', inputMulta.value);
            inputMulta.setAttribute('id_lancamento', idLancamento || 'null');
        });

    }
    
    $(document).ready(function() {
        $('#sbmCadastrarDecisao').on('click', function(){
            registrarResultadoLancamentosAlteradosDecisao(obterLancamentosAlterados());
        });
        $('#frmCadastroDecisao').on('submit', function(){
            registrarResultadoLancamentosAlteradosDecisao(obterLancamentosAlterados());
        });
        $('#btnCancelar').click(function() {
            $(window.top.document).find('div[id^=divInfraSparklingModalClose]').click();
        });
    });   

    function obterLancamentosAlterados(){
        var ids = {};
        var valoresOriginais = obterValoresOriginaisDecisao();

        $("input[name$='[multa]']").each(function(){
            var tr = this.closest('tr');
            var inputIdDecisao = tr ? tr.querySelector("input[name$='[id_decisao]']") : null;
            var inputIdDecisaoOrigem = tr ? tr.querySelector("input[name$='[id_decisao_origem]']") : null;
            var inputLancamento = tr ? tr.querySelector("input[name$='[id_lancamento]']") : null;
            var idDecisao = inputIdDecisao ? inputIdDecisao.value : '';
            var idDecisaoOrigem = inputIdDecisaoOrigem ? inputIdDecisaoOrigem.value : '';
            var idComparacao = idDecisao || idDecisaoOrigem;
            var idLanc = inputLancamento ? inputLancamento.value : $(this).attr('id_lancamento');
            var valorOriginal = valoresOriginais[idComparacao];

            if ((!idLanc || idLanc === 'null') && valorOriginal && valorOriginal.idLancamento) {
                idLanc = valorOriginal.idLancamento;
            }

            if (!idLanc || idLanc === 'null') {
                return;
            }

            var antigo = valorOriginal ? valorOriginal.valor : normalizarValorMonetario($(this).attr('decisao_valor_antigo'));
            var atual = normalizarValorMonetario($(this).val());
            if (atual !== antigo) {
                ids[idLanc] = true;
            }
        });
        return Object.keys(ids);
    }

    function obterLancamentosAlteradosTabela(valorNovo, valorOriginal){
        var ids = {};
        var valoresOriginais = obterValoresOriginaisDecisaoTabela(valorOriginal);
        var linhas = processarItemListas(valorNovo || '');

        for (var i = 0; i < linhas.length; i++) {
            var idComparacao = linhas[i][0];
            if (idComparacao && idComparacao.indexOf('novo_') === 0 && linhas[i][20]) {
                idComparacao = linhas[i][20];
            }

            var valorOriginalLinha = valoresOriginais[idComparacao];
            var antigo = valorOriginalLinha ? valorOriginalLinha.valor : 0;
            var atual = normalizarValorMonetario(linhas[i][4]);

            if (atual === antigo) {
                continue;
            }

            var idLanc = linhas[i][19] || 'null';
            if ((!idLanc || idLanc === 'null') && valorOriginalLinha) {
                idLanc = valorOriginalLinha.idLancamento;
            }

            if (idLanc && idLanc !== 'null') {
                ids[idLanc] = true;
            }
        }

        return Object.keys(ids);
    }

    function obterValoresOriginaisDecisaoTabela(valorReferencia){
        var valoresOriginais = {};
        if (!valorReferencia) {
            return valoresOriginais;
        }

        var linhas = processarItemListas(valorReferencia);
        for (var i = 0; i < linhas.length; i++) {
            if (linhas[i][0]) {
                valoresOriginais[linhas[i][0]] = {
                    valor: normalizarValorMonetario(linhas[i][4]),
                    idLancamento: linhas[i][19] || 'null'
                };
            }
        }
        return valoresOriginais;
    }

    function registrarResultadoLancamentosAlteradosDecisao(lancamentosAlterados){
        if (lancamentosAlterados.length > 1) {
            registrarLancamentoAlteradoDecisao('');
            ocultarDiv();
        } else {
            limparRetificacaoDupla();
            registrarLancamentoAlteradoDecisao(lancamentosAlterados.length === 1 ? lancamentosAlterados[0] : '');
        }
    }
    function obterValoresOriginaisDecisao(){
        var valoresOriginais = {};
        var valorReferencia = obterTabelaOriginalMultas();
        if (valorReferencia == '') {
            return valoresOriginais;
        }

        var linhas = processarItemListas(valorReferencia);
        for (var i = 0; i < linhas.length; i++) {
            if (linhas[i][0]) {
                valoresOriginais[linhas[i][0]] = {
                    valor: normalizarValorMonetario(linhas[i][4]),
                    idLancamento: linhas[i][19] || 'null'
                };
            }
        }
        return valoresOriginais;
    }

    function obterTabelaOriginalMultas(){
        var hdnVlOriginalMultas = telaPai.contentWindow.document.getElementById('hdnVlOriginalMultas');
        if (hdnVlOriginalMultas && hdnVlOriginalMultas.value != '') {
            return hdnVlOriginalMultas.value;
        }

        var hdnTbDecisaoAntigo = document.getElementById('hdnTbDecisaoAntigo');
        return hdnTbDecisaoAntigo ? hdnTbDecisaoAntigo.value : '';
    }

    function normalizarValorMonetario(valor){
        valor = valor || '';
        valor = String(valor).replace(/[^0-9,.-]/g, '');

        if (valor.indexOf(',') >= 0) {
            valor = valor.replace(/\./g, '').replace(',', '.');
        }

        var numero = parseFloat(valor);
        if (isNaN(numero)) {
            numero = 0;
        }

        return Math.round(numero * 100);
    }

    function registrarLancamentoAlteradoDecisao(idLanc){
        var docPai = window.top.document
        .getElementById("ifrConteudoVisualizacao").contentWindow.document
        .getElementById("ifrVisualizacao").contentWindow.document;
        var hdnLancamentoAlteradoDecisao = docPai.getElementById('hdnLancamentoAlteradoDecisao');
        if (hdnLancamentoAlteradoDecisao) {
            hdnLancamentoAlteradoDecisao.value = idLanc || '';
        }
    }

    function selecionarCreditoProcesso(idLanc){

        if (!idLanc) {
            return;
        }
        var docPai = window.top.document
        .getElementById("ifrConteudoVisualizacao").contentWindow.document
        .getElementById("ifrVisualizacao").contentWindow.document;
        var sel = docPai.getElementById('selCreditosProcesso');
        if (!sel) {
            return;
        }
        sel.value = idLanc;
        sel.setAttribute('disabled', 'disabled');
        if (typeof $(sel).change === 'function') {
            $(sel).change();
        }
    }

    function ocultarDiv(){
        var docPai = window.top.document
            .getElementById("ifrConteudoVisualizacao").contentWindow.document
            .getElementById("ifrVisualizacao").contentWindow.document;

        var divDetalhes = docPai.getElementById('detalhesLancamento');
        if (divDetalhes) {
            divDetalhes.style.display = 'none';
        }
        var divMsg = docPai.getElementById('msgRetificacaoDupla');
        if (divMsg) {
            divMsg.style.display = '';
        }
        var btnSalvarLancamentos = docPai.getElementById('btnSalvarLancamentos');
        if (btnSalvarLancamentos) {
            btnSalvarLancamentos.style.display = '';
        }
        var hdnRetificacaoDupla = docPai.getElementById('hdnRetificacaoDupla');
        if (hdnRetificacaoDupla) {
            hdnRetificacaoDupla.value = 'true';
        }
        var hdn = docPai.getElementById('hdnLancamentoFracionado');
        if (hdn) {
            hdn.value = 'true';
        }
        if (typeof docPai.defaultView.verificarExibicaoDivLancamentoFracionado === 'function') {
            docPai.defaultView.verificarExibicaoDivLancamentoFracionado();
        } else {
            var botoes = [
                'btnVincularLancamento',
                'btnIncluirLancamento',
                'btnCancelarLancamento',
                'btnRetificarLancamento',
                'btnSuspenderLancamento',
                'btnDenegarRecurso',
                'btnCancelarRecurso',
                'btnConstituirDefinitivamente'
            ];
            for (var i = 0; i < botoes.length; i++) {
                var botao = docPai.getElementById(botoes[i]);
                if (botao) {
                    botao.style.display = 'none';
                }
            }
        }
    }

    function limparRetificacaoDupla(){
        var docPai = window.top.document
            .getElementById("ifrConteudoVisualizacao").contentWindow.document
            .getElementById("ifrVisualizacao").contentWindow.document;

        var hdnRetificacaoDupla = docPai.getElementById('hdnRetificacaoDupla');
        if (hdnRetificacaoDupla) {
            hdnRetificacaoDupla.value = '';
        }
        var divMsg = docPai.getElementById('msgRetificacaoDupla');
        if (divMsg) {
            divMsg.style.display = 'none';
        }
        var btnSalvarLancamentos = docPai.getElementById('btnSalvarLancamentos');
        if (btnSalvarLancamentos) {
            btnSalvarLancamentos.style.display = 'none';
        }
        var divDetalhes = docPai.getElementById('detalhesLancamento');
        if (divDetalhes) {
            divDetalhes.style.display = '';
        }
    }

    function monitorarMultaLancamento(){
        window.idLancamentoAlterado = '';
        window.houveAlteracao2Lancamentos = false;
    }            

</script>
