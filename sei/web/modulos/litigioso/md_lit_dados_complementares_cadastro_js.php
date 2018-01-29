<script type="text/javascript">

    var objLupaServicos = null;
    var objAutoCompletarServicos = null;
    var objLupaServicosNaoOutorga = null;
    var objTblDadosComplementares = null;
    var objTblDadosComplementaresConsulta = null;

    function inicializar(){

        infraOcultarMenuSistemaEsquema();
        <? if(isset($campos[0]) && $campos[0]->getStrSinExibe() == 'S'){ ?>
            document.getElementById('outorgada').style.display = "none";
        <? } ?>
        document.getElementById('outorgada').style.display = "none";
        document.getElementById('naoOutorgada').style.display = "none";
        document.getElementById('dvDadosComplementaresConsulta').style.display = "none";
        document.getElementById('dvTblDadosComplementares').style.display = "none";

        //Servicos
        if(document.getElementById('selServicos') != null) {
            objLupaServicos = new infraLupaSelect('selServicos', 'hdnServicos', '<?=$strLinkServicoSelecao?>');
            objAutoCompletarServicos = new infraAjaxAutoCompletar('hdnIdServicos', 'txtServicos', '<?=$strLinkAjaxServico?>');
            objAutoCompletarServicos.limparCampo = true;
            objAutoCompletarServicos.prepararExecucao = function () {
                return 'palavras_pesquisa=' + document.getElementById('txtServicos').value;
            };
            objAutoCompletarServicos.processarResultado = function (id, descricao, complemento) {
                if (id != '') {
                    objLupaServicos.adicionar(id, descricao, document.getElementById('txtServicos'));

                    if(verificarPaiFieldSet(document.getElementById('selServicos')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                        if(document.getElementById('hdnServicos').value == ''){
                            document.getElementById('txtNumero').disabled = false;
                        }else{
                            document.getElementById('txtNumero').disabled = true;
                            document.getElementById('txtNumero').value = '';
                        }
                    }
                }
            };
            objLupaServicos.finalizarRemocao = function(item){
                if(verificarPaiFieldSet(document.getElementById('selServicos')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                    if(document.getElementById('hdnServicos').value == ''){
                        document.getElementById('txtNumero').disabled = false;
                    }else{
                        document.getElementById('txtNumero').disabled = true;
                        document.getElementById('txtNumero').value = '';
                    }
                }
            }
            objLupaServicos.finalizarSelecao = function(){
                if(verificarPaiFieldSet(document.getElementById('selServicos')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                    if(document.getElementById('hdnServicos').value == ''){
                        document.getElementById('txtNumero').disabled = false;
                    }else{
                        document.getElementById('txtNumero').disabled = true;
                        document.getElementById('txtNumero').value = '';
                    }
                }
            }
        }

        //Servicos N�o outorgado
        if(document.getElementById('selServicosNaoOutorga') != null) {
            objLupaServicosNaoOutorga = new infraLupaSelect('selServicosNaoOutorga', 'hdnServicosNaoOutorga', '<?=$strLinkServicoNaoOutorgadaSelecao?>');
            objAutoCompletarServicosNaoOutorga = new infraAjaxAutoCompletar('hdnIdServicosNaoOutorga', 'txtServicosNaoOutorga', '<?=$strLinkAjaxServico?>');
            objAutoCompletarServicosNaoOutorga.limparCampo = true;
            objAutoCompletarServicosNaoOutorga.prepararExecucao = function () {
                return 'palavras_pesquisa=' + document.getElementById('txtServicosNaoOutorga').value;
            };
            objAutoCompletarServicosNaoOutorga.processarResultado = function (id, descricao, complemento) {
                if (id != '') {
                    objLupaServicosNaoOutorga.adicionar(id, descricao, document.getElementById('txtServicosNaoOutorga'));
                }
            };
        }

        //Estados
        if(document.getElementById('selEstado') != null) {
            objLupaEstado = new infraLupaSelect('selEstado', 'hdnEstado', '<?=$strLinkEstadoSelecao?>');
            objAutoCompletarEstado = new infraAjaxAutoCompletar('hdnIdEstado', 'txtEstado', '<?=$strLinkAjaxEstado?>');
            objAutoCompletarEstado.limparCampo = true;
            objAutoCompletarEstado.prepararExecucao = function () {
                return 'palavras_pesquisa=' + document.getElementById('txtEstado').value;
            };
            objAutoCompletarEstado.processarResultado = function (id, descricao, complemento) {
                if (id != '') {
                    objLupaEstado.adicionar(id, descricao, document.getElementById('txtEstado'));

                    if(verificarPaiFieldSet(document.getElementById('selEstado')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                        if(document.getElementById('hdnEstado').value == ''){
                            document.getElementById('txtNumero').disabled = false;
                        }else{
                            document.getElementById('txtNumero').disabled = true;
                            document.getElementById('txtNumero').value = '';
                        }
                    }
                }
            };
            objLupaEstado.finalizarRemocao = function(item){
                if(verificarPaiFieldSet(document.getElementById('selEstado')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                    if(document.getElementById('hdnEstado').value == ''){
                        document.getElementById('txtNumero').disabled = false;
                    }else{
                        document.getElementById('txtNumero').disabled = true;
                        document.getElementById('txtNumero').value = '';
                    }
                }
            }
            objLupaEstado.finalizarSelecao = function(){
                if(verificarPaiFieldSet(document.getElementById('selEstado')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                    if(document.getElementById('hdnEstado').value == ''){
                        document.getElementById('txtNumero').disabled = false;
                    }else{
                        document.getElementById('txtNumero').disabled = true;
                        document.getElementById('txtNumero').value = '';
                    }
                }
            }
        }


        //Cidades
        if(document.getElementById('selCidade') != null){
            objLupaCidade = new infraLupaSelect('selCidade','hdnCidade','<?=$strLinkCidadeSelecao?>');
            objAutoCompletarCidade = new infraAjaxAutoCompletar('hdnIdCidade','txtCidade','<?=$strLinkAjaxCidade?>');
            objAutoCompletarCidade.limparCampo = true;
            objAutoCompletarCidade.prepararExecucao = function(){
                return 'palavras_pesquisa='+document.getElementById('txtCidade').value+'&idsUf='+document.getElementById('hdnEstado').value;
            };
            objAutoCompletarCidade.processarResultado = function(id,descricao,complemento){
                if (id!=''){
                    objLupaCidade.adicionar(id,descricao,document.getElementById('txtCidade'));

                    if(verificarPaiFieldSet(document.getElementById('selCidade')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                        if(document.getElementById('hdnCidade').value == ''){
                            document.getElementById('txtNumero').disabled = false;
                        }else{
                            document.getElementById('txtNumero').disabled = true;
                            document.getElementById('txtNumero').value = '';
                        }
                    }
                }
            };
            objLupaCidade.finalizarRemocao = function(item){
                if(verificarPaiFieldSet(document.getElementById('selCidade')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                    if(document.getElementById('hdnCidade').value == ''){
                        document.getElementById('txtNumero').disabled = false;
                    }else{
                        document.getElementById('txtNumero').disabled = true;
                        document.getElementById('txtNumero').value = '';
                    }
                }
            }
            objLupaCidade.finalizarSelecao = function(){
                if(verificarPaiFieldSet(document.getElementById('selCidade')) && verificarPaiFieldSet(document.getElementById('txtNumero')) ){
                    if(document.getElementById('hdnCidade').value == ''){
                        document.getElementById('txtNumero').disabled = false;
                    }else{
                        document.getElementById('txtNumero').disabled = true;
                        document.getElementById('txtNumero').value = '';
                    }
                }
            }
        }

        //pegar do fieldset do interessado na tela de controle litigioso
        var idContato = document.getElementById('hdnIdContato').value;
        var hdnTbDadoInteressado = eval('window.opener.hdnTbDadoInteressado_'+idContato);
        if(typeof hdnTbDadoInteressado != 'undefined'){
            document.getElementById('hdnListaDadosComplementares').value = hdnTbDadoInteressado.value;
            if(hdnTbDadoInteressado.getAttribute('outorgado') == 'S'){
                document.getElementById('optOutorgadaSim').checked = true;
            }else if(hdnTbDadoInteressado.getAttribute('outorgado') == 'N'){
                document.getElementById('optOutorgadaNao').checked = true;
            }
        }

        //tabela dados complementares
        objTblDadosComplementares = new infraTabelaDinamica('tblDadosComplementares', 'hdnListaDadosComplementares', false, true);
        objTblDadosComplementares.gerarEfeitoTabela = true;
        objTblDadosComplementares.inserirNoInicio = false;
        objTblDadosComplementares.exibirMensagens = true;

        //tabela dados complementares consulta
        objTblDadosComplementaresConsulta = new infraTabelaDinamica('tblDadosComplementaresConsultas', 'hdnListaDadosComplementaresConsultas', false, false);
        objTblDadosComplementaresConsulta.gerarEfeitoTabela = false;
        objTblDadosComplementaresConsulta.inserirNoInicio = false;
        objTblDadosComplementaresConsulta.exibirMensagens = false;

        infraEfeitoTabelas();


        if(document.getElementById('optOutorgadaSim').checked){
            document.getElementById('outorgada').style.display = "block";

            if(objTblDadosComplementares.hdn.value != '')
                document.getElementById('dvTblDadosComplementares').style.display = "block";
        }else if(document.getElementById('optOutorgadaNao').checked){
            document.getElementById('naoOutorgada').style.display = "block";
            var arrDadosComplementar = objTblDadosComplementares.obterItens();
            if(arrDadosComplementar[0][10] == 'N'){
                var arrNomeServico  = arrDadosComplementar[0][1].split(",<br> ");
                var arrIdServico    = arrDadosComplementar[0][4].split(",<br> ");
                for(var i = 0; i <arrNomeServico.length; i++ ){
                    objLupaServicosNaoOutorga.adicionar(arrIdServico[i],arrNomeServico[i] );
                }
                objTblDadosComplementares.limpar();
            }
        }

        consultarCadastro();

    }

    function consultarCadastro(){

        var objConsultarNumero = new Object();
        objConsultarNumero.id_procedimento   = document.getElementById('hdnIdProcedimento').value;
        objConsultarNumero.id_contato        = document.getElementById('hdnIdContato').value;
        objConsultarNumero.id_md_lit_controle  = document.getElementById('hdnIdMdLitControle').value;

        if(objConsultarNumero.IdMdLitControle == '' || objTblDadosComplementares.hdn.value != ''){
            return;
        }

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxlistarDadoComplementar ?>",
//            dataType: "xml",
            data: objConsultarNumero,
            success: function (result) {
                objTblDadosComplementaresConsulta.limpar();
                if($(result).find('erro').length > 0){
                    alert($(result).find('erro').attr('descricao'));
                    return;
                }

                var objDadosComplementares = [];
                objDadosComplementares[0] = null;//N�mero
                objDadosComplementares[1] = null;//nomeServico
                objDadosComplementares[2] = null;//nomeModalidade
                objDadosComplementares[3] = null;//nomeAbrangencia
                objDadosComplementares[4] = null;//nomeEstado
                objDadosComplementares[5] = null;//nomeCidade
                objDadosComplementares[6] = null;//idServico
                objDadosComplementares[7] = null;//idModalidade
                objDadosComplementares[8] = null;//idAbrangencia
                objDadosComplementares[9] = null;//idEstado
                objDadosComplementares[10] = null;//idCidade
                objDadosComplementares[11] = null;//idContato
                objDadosComplementares[12] = null;//outorga
                objDadosComplementares[13] = null;//idMdLitDadoInteressado

                var count = 0;

                $.each($(result).find('item'), function(key, value){
                    if($(this).attr('id_servico') != undefined)
                        objDadosComplementares[6] = $(this).attr('id_servico');

                    if($(this).attr('nome_servico') != undefined)
                        objDadosComplementares[1] = $(this).attr('nome_servico');

                    if($(this).attr('numero') != undefined)
                        objDadosComplementares[0] = $(this).attr('numero');

                    if($(this).attr('nome_modalidade') != undefined)
                        objDadosComplementares[2] = $(this).attr('nome_modalidade');

                    if($(this).attr('id_modalidade') != undefined)
                        objDadosComplementares[7] = $(this).attr('id_modalidade');

                    if($(this).attr('nome_abrangencia') != undefined)
                        objDadosComplementares[3] = $(this).attr('nome_abrangencia');

                    if($(this).attr('id_abrangencia') != undefined)
                        objDadosComplementares[8] = $(this).attr('id_abrangencia');

                    if($(this).attr('id_cidade') != undefined)
                        objDadosComplementares[10] = $(this).attr('id_cidade');

                    if($(this).attr('id_estado') != undefined)
                        objDadosComplementares[9] = $(this).attr('id_estado');

                    if($(this).attr('id_contato') != undefined)
                        objDadosComplementares[11] = $(this).attr('id_contato');

                    if($(this).attr('outorgado') != undefined)
                        objDadosComplementares[12] = $(this).attr('outorgado');

                    if($(this).attr('id_md_lit_dado_interessado') != undefined)
                        objDadosComplementares[13] = $(this).attr('id_md_lit_dado_interessado');

                    if($(this).attr('nome_cidade') != undefined)
                        objDadosComplementares[5] = $(this).attr('nome_cidade');

                    if($(this).attr('nome_estado') != undefined){
                        objDadosComplementares[4] = $(this).attr('nome_estado');

                    }

                    if($(this).attr('outorgado') == 'S'){
                        document.getElementById('optOutorgadaSim').checked = true;
                        document.getElementById('outorgada').style.display = "block";
                    }else if($(this).attr('outorgado') == 'N'){
                        document.getElementById('optOutorgadaNao').checked = true;
                        document.getElementById('naoOutorgada').style.display = "block";
                    }

                    objTblDadosComplementares.adicionar(objDadosComplementares, false);
                    infraEfeitoTabelas();
                    count++;
                });

                if(document.getElementById('optOutorgadaNao').checked) {
                    var arrDadosComplementar = objTblDadosComplementares.obterItens();
                    var arrNomeServico = arrDadosComplementar[0][1].split(",<br> ");
                    var arrIdServico = arrDadosComplementar[0][6].split(",� ");
                    document.getElementById('hdnIdMdLitDadoInteressadoNaoOutorgado').value = arrDadosComplementar[0][13];
                    document.getElementById('hdnNumeroNaoOutorgado').value = arrDadosComplementar[0][0];
                    for (var i = 0; i < arrNomeServico.length; i++) {
                        objLupaServicosNaoOutorga.adicionar(arrIdServico[i], arrNomeServico[i]);
                    }
                    objTblDadosComplementares.limpar();
                }else if(document.getElementById('optOutorgadaSim').checked){
                    document.getElementById('dvTblDadosComplementares').style.display = "block";
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                console.log(msgCommit);
            }
        });
    }

    function OnSubmitForm(formulario) {
        objTblDadosComplementares.atualizaHdn();
        if(!validarCadastro(formulario)){
            return false;
        }

        if(document.getElementById('optOutorgadaNao').checked){
            var idServico   = getOptionsId(document.getElementById('selServicosNaoOutorga'));
            var nomeServico = getOptionsString(document.getElementById('selServicosNaoOutorga')) ;
            var idContato       = document.getElementById('hdnIdContato').value;
            var outorga         = 'N';
            var idMdLitDadoComplementar = document.getElementById('hdnIdMdLitDadoInteressadoNaoOutorgado').value;
            var numero = document.getElementById('hdnNumeroNaoOutorgado').value == ''? null : document.getElementById('hdnNumeroNaoOutorgado').value;

            objTblDadosComplementares.limpar();
            objTblDadosComplementares.adicionar([
                numero,
                nomeServico,
                null,
                null,
                null,
                null,
                idServico,
                null,
                null,
                null,
                null,
                idContato,
                outorga,
                idMdLitDadoComplementar], true);
        }

        var idContato = document.getElementById('hdnIdContato').value;
        var hdnTbDadoInteressado = eval('window.opener.hdnTbDadoInteressado_'+idContato);
        var checkedOutorgado = document.getElementById('optOutorgadaSim').checked? 'S':'N';

        if(typeof hdnTbDadoInteressado == 'undefined'){
            var hdnTbDadoInteressado = document.createElement("input");
            hdnTbDadoInteressado.setAttribute("type", "hidden");
            hdnTbDadoInteressado.setAttribute("name", "hdnTbDadoInteressado[]");
            hdnTbDadoInteressado.setAttribute("id", "hdnTbDadoInteressado_"+idContato);
            hdnTbDadoInteressado.setAttribute("value", objTblDadosComplementares.hdn.value);
            hdnTbDadoInteressado.setAttribute("outorgado", checkedOutorgado);
            window.opener.divInteressados.appendChild(hdnTbDadoInteressado);

            window.close();
            return false;
        }

        hdnTbDadoInteressado.setAttribute("outorgado", checkedOutorgado);
        hdnTbDadoInteressado.value = objTblDadosComplementares.hdn.value;
        window.close();
        return false;
    }
    function validarCadastro(formulario) {
        if(!document.getElementById('optOutorgadaSim').checked && !document.getElementById('optOutorgadaNao').checked ){
            var nomeCampo = infraTrim(document.getElementById('lblOutorga').textContent).replace(':', '');
            alert('Selecione a op��o de '+nomeCampo);
            return false;
        }else if(document.getElementById('optOutorgadaSim').checked){
            if(objTblDadosComplementares.tbl.rows.length <= 1){
                alert('Adicione ao menos um dado complementar do interessado!');
                return false;
            }
        }else if(document.getElementById('optOutorgadaNao').checked){
            if(document.getElementById('hdnServicosNaoOutorga') != null && document.getElementById('hdnServicosNaoOutorga').value == ''){
                alert('Selecione ao menos um servi�o!');
                return false;
            }
        }

        return true;
    }


    function outorgada(input){
        if(input.value == 'S'){
            document.getElementById('outorgada').style.display = "block";
            document.getElementById('naoOutorgada').style.display = "none";
            objLupaServicosNaoOutorga.limpar();
        }else{
            document.getElementById('outorgada').style.display = "none";
            document.getElementById('naoOutorgada').style.display = "block";
            objTblDadosComplementares.limpar();
            document.getElementById('dvTblDadosComplementares').style.display = "none";
        }

    }

    function adicionarDadosComplementares(){
        if(document.getElementById('optOutorgadaSim').checked){
            if( !verificarPaiFieldSet(document.getElementById('txtNumero')) && document.getElementById('txtNumero') != null && document.getElementById('lblNumero').className == 'infraLabelObrigatorio' && infraTrim(document.getElementById('txtNumero').value) == ''){
                var nomeCampo = infraTrim(document.getElementById('lblNumero').textContent).replace(':', '');
                alert("O "+nomeCampo+" � obrigat�rio!");
                return false;
            }

            if(!verificarPaiFieldSet(document.getElementById('txtServicos')) && document.getElementById('txtServicos') != null && document.getElementById('lblServicos').className == 'infraLabelObrigatorio' && infraTrim(document.getElementById('hdnServicos').value) == '' ){
                var nomeCampo = infraTrim(document.getElementById('lblServicos').textContent).replace(':', '');
                alert("selecione ao menos um "+nomeCampo+"!");
                return false;
            }

            if(!verificarPaiFieldSet(document.getElementById('optAutorizacao')) && document.getElementById('optAutorizacao') != null && document.getElementById('optAutorizacao').parentNode.className == 'infraLabelObrigatorio'){
                if(!document.getElementById('optAutorizacao').checked && !document.getElementById('optConsessao').checked){
                    var nomeCampo = infraTrim(document.getElementById('lgdModalidadesOutorga').textContent).replace(':', '');
                    alert("selecione ao menos uma "+nomeCampo+"!");
                    return false;
                }
            }

            if(!verificarPaiFieldSet(document.getElementById('optNacional')) && document.getElementById('optNacional') != null && document.getElementById('optNacional').parentNode.className == 'infraLabelObrigatorio'){
                if(!document.getElementById('optNacional').checked && !document.getElementById('optRegional').checked && !document.getElementById('optEstadual').checked){
                    var nomeCampo = infraTrim(document.getElementById('lgdAbrangencia').textContent).replace(':', '');
                    alert("selecione ao menos uma "+nomeCampo+"!");
                    return false;
                }
            }

            if(!verificarPaiFieldSet(document.getElementById('txtEstado')) && document.getElementById('txtEstado') != null && document.getElementById('lblEstado').className == 'infraLabelObrigatorio' && infraTrim(document.getElementById('hdnEstado').value) == '' ){
                var nomeCampo = infraTrim(document.getElementById('lblEstado').textContent).replace(':', '');
                alert("selecione ao menos um "+nomeCampo+"!");
                return false;
            }

            if(!verificarPaiFieldSet(document.getElementById('txtCidade')) && document.getElementById('txtCidade') != null && document.getElementById('lblCidade').className == 'infraLabelObrigatorio' && infraTrim(document.getElementById('hdnCidade').value) == '' ){
                var nomeCampo = infraTrim(document.getElementById('lblCidade').textContent).replace(':', '');
                alert("selecione ao menos um "+nomeCampo+"!");
                return false;
            }
            if(document.getElementById('fldInfoComplementares') != null && objTblDadosComplementaresConsulta.tbl.rows.length <= 1){
                alert("Consultar as informa��es complementares antes de adicionar!");
                return false;
            }

            var numero      = document.getElementById('txtNumero') == null? null: document.getElementById('txtNumero').value;
            var idServico   = getOptionsId(document.getElementById('selServicos'));
            var nomeServico = getOptionsString(document.getElementById('selServicos')) ;
            var idEstado    = getOptionsId(document.getElementById('selEstado'));
            var idCidade    = getOptionsId(document.getElementById('selCidade'));
            var nomeEstado  = getOptionsString(document.getElementById('selEstado'));
            var nomeCidade  = getOptionsString(document.getElementById('selCidade'));
            // sair do padr�o pois n�o imaginei forma de pegar o radio dinamico
            var nomeModalidade  = document.getElementById('fldModalidadesOutorga') ? getChecboxString(document.getElementById('fldModalidadesOutorga').getElementsByTagName('input')): '';
            var idModalidade    = document.getElementById('fldModalidadesOutorga') ? getChecboxId(document.getElementById('fldModalidadesOutorga').getElementsByTagName('input')): '';
            var nomeAbrangencia = document.getElementById('fldAbrangencia') ? getChecboxString(document.getElementById('fldAbrangencia').getElementsByTagName('input')): '';
            var idAbrangencia   = document.getElementById('fldAbrangencia') ? getChecboxId(document.getElementById('fldAbrangencia').getElementsByTagName('input')): '';
            var idContato       = document.getElementById('hdnIdContato').value;
            var outorga         = 'S';

            document.getElementById('dvTblDadosComplementares').style.display = "block";

            if(objTblDadosComplementaresConsulta.tbl.rows.length > 1){
                var inputCheckbox = document.getElementById('dvDadosComplementaresConsulta').getElementsByTagName('input');
                for(var i = 0;i < inputCheckbox.length; i++){
                    if(document.getElementById('dvDadosComplementaresConsulta').getElementsByTagName('input')[i].checked){
                        var arrResultado = objTblDadosComplementaresConsulta.obterItens().reverse()[i];

                        idServico       = arrResultado[1] == 'null'? idServico :  arrResultado[1];
                        idModalidade    = arrResultado[2] == 'null' ? idModalidade : arrResultado[2];
                        idAbrangencia   = arrResultado[3] == 'null' ? idAbrangencia :arrResultado[3];
                        idEstado        = arrResultado[4] == 'null' ? idEstado :arrResultado[4];
                        idCidade        = arrResultado[5] == 'null' ? idCidade :arrResultado[5];
                        numero          = arrResultado[6] == 'null' ? numero :arrResultado[6];
                        nomeServico     = arrResultado[7] == 'null' ? nomeServico :arrResultado[7];
                        nomeModalidade  = arrResultado[8] == 'null' ? nomeModalidade :arrResultado[8];
                        nomeAbrangencia = arrResultado[9] == 'null' ? nomeAbrangencia :arrResultado[9];
//                        nomeEstado      = nomeEstado;
//                        nomeCidade      = nomeCidade;

                        objTblDadosComplementares.adicionar([
                            numero,
                            nomeServico,
                            nomeModalidade,
                            nomeAbrangencia,
                            nomeEstado,
                            nomeCidade,
                            idServico,
                            idModalidade,
                            idAbrangencia,
                            idEstado,
                            idCidade,
                            idContato,
                            outorga,
                            null], true);
                    }
                }
                return;
            }

            objTblDadosComplementares.adicionar([
                numero,
                nomeServico,
                nomeModalidade,
                nomeAbrangencia,
                nomeEstado,
                nomeCidade,
                idServico,
                idModalidade,
                idAbrangencia,
                idEstado,
                idCidade,
                idContato,
                outorga,
                null], true);

            infraEfeitoTabelas();

            //limpando os campos
            objLupaServicos.limpar();
            objLupaCidade.limpar();
            objLupaEstado.limpar();
            document.getElementById('txtNumero').value = '';
        }
    }

    function getOptionsString(element){
        if(element == null)
            return null;
        var nome = '';
        for(var i=0; i < element.options.length; i++){
            nome += nome == ''? element.options[i].textContent : ',<br> '+element.options[i].textContent;
        }
        return nome;
    }

    function getOptionsId(element){
        if(element == null)
            return null;
        var id = '';
        for(var i=0; i < element.options.length; i++){
            id += id == ''? element.options[i].value : ',� '+element.options[i].value;
        }
        return id;
    }

    function getChecboxString(element){
        if(element == null)
            return null;
        var nome = '';
        for(var i=0; i < element.length; i++){
            if(element[i].checked){
                nome += nome == ''? element[i].nextSibling.textContent : ',<br> '+element[i].nextSibling.textContent;
            }
        }
        return nome;
    }

    function getChecboxId(element){
        if(element == null)
            return null;
        var nome = '';
        for(var i=0; i < element.length; i++){
            if(element[i].checked){
                nome += nome == ''? element[i].value : ',� '+element[i].value;
            }
        }
        return nome;
    }

    function getChecboxIdArr(element){
        if(element == null)
            return null;
        var arr = [];
        for(var i=0; i < element.length; i++){
            if(element[i].checked){
                arr.push(element[i].value);
            }
        }
        return arr;
    }
    function verificarPaiFieldSet(element){
        if(element == null){
            return false;
        }
        while (element.parentNode){
            if(element.parentNode.id == 'fldInfoComplementares'){
                return true;
            }else{
                element = element.parentNode;
            }
        }
        return false;
    }

    function consultarNumero(){
        var objConsultarNumero = new Object();
        if(verificarPaiFieldSet(document.getElementById('txtNumero')) && document.getElementById('txtNumero').value != ''){
            objConsultarNumero.numero = document.getElementById('txtNumero').value;
        }
        if(verificarPaiFieldSet(document.getElementById('txtServicos')) && document.getElementById('hdnServicos').value != ''){
            objConsultarNumero.servico = document.getElementById('hdnServicos').value;
        }
        if(verificarPaiFieldSet(document.getElementById('txtEstado')) && document.getElementById('hdnEstado').value != ''){
            objConsultarNumero.estados = document.getElementById('hdnEstado').value;
        }
        if(verificarPaiFieldSet(document.getElementById('txtCidade')) && document.getElementById('hdnCidade').value != ''){
            objConsultarNumero.cidades = document.getElementById('hdnCidade').value;
        }
        if(verificarPaiFieldSet(document.getElementById('fldAbrangencia')) && getChecboxIdArr(document.getElementsByName('rdoAbrangencia[]')).length != 0 ){
            objConsultarNumero.abrangencia = getChecboxIdArr(document.getElementsByName('rdoAbrangencia[]'));
        }
        if(verificarPaiFieldSet(document.getElementById('fldModalidadesOutorga')) && getChecboxIdArr(document.getElementsByName('rdoOutorga[]')).length != 0 ){
            objConsultarNumero.modalidade = getChecboxIdArr(document.getElementsByName('rdoOutorga[]'));
        }

        objConsultarNumero.cpf_cnpj = document.getElementById('hdnCpfCnpj').value;

        if(JSON.stringify(objConsultarNumero) === JSON.stringify({})){
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxConsultarIntegracao ?>",
//            dataType: "xml",
            data: objConsultarNumero,
            beforeSend: function(){
                infraExibirAviso(false);
            },
            success: function (result) {
                objTblDadosComplementaresConsulta.limpar();
                if($(result).find('erro').length > 0){
                    alert($(result).find('erro').attr('descricao'));
                    return;
                }

                var objDadosComplementares = [];
                objDadosComplementares[0] = null;//checkbox
                objDadosComplementares[1] = null;//ID Servi�os
                objDadosComplementares[2] = null;//ID Modalidades
                objDadosComplementares[3] = null;//ID Abrangencias
                objDadosComplementares[4] = null;//ID Estados
                objDadosComplementares[5] = null;//ID Cidades
                objDadosComplementares[6] = null;//N�mero
                objDadosComplementares[7] = null;//Servi�o
                objDadosComplementares[8] = null;//Modalidade de Outorga
                objDadosComplementares[9] = null;//Abrang�ncias
                var count = 0;

                $.each($(result).find('item'), function(key, value){
                    objDadosComplementares[0] = '<input type="checkbox" onclick="toggleSelecionarTabela(this)" id="chkInfraItem'+count+'" name="chkInfraItem'+count+'" class="infraCheckbox" value="'+count+'">';
                    if($(this).attr('id_servico') != undefined)
                        objDadosComplementares[1] = $(this).attr('id_servico');

                    if($(this).attr('nome_servico') != undefined)
                        objDadosComplementares[7] = $(this).attr('nome_servico');

                    if($(this).attr('numero') != undefined)
                        objDadosComplementares[6] = $(this).attr('numero');

                    objTblDadosComplementaresConsulta.adicionar(objDadosComplementares, false);
                    count++;
                });

                document.getElementById('dvDadosComplementaresConsulta').style.display = "block";
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

    function toggleSelecionarTabela(element){
        if(element.checked){
            infraFormatarTrMarcada(element.parentNode.parentNode.parentNode);
        }else{
            infraFormatarTrDesmarcada(element.parentNode.parentNode.parentNode);
        }
    }

    function desabilitarCampos(element){
        if(element.value == ''){
            infraHabilitarCamposDiv(document.getElementById('fldInfoComplementares'));
        }else{
            infraDesabilitarCamposDiv(document.getElementById('fldInfoComplementares'));
            infraHabilitarCamposDiv(element.parentNode);
        }

    }

    function desabilitarTxtNumero(element){
        var checboxString = getChecboxString(element.getElementsByTagName('input'));
        if(checboxString == ''){
            document.getElementById('txtNumero').disabled = false;
        }else{
            document.getElementById('txtNumero').disabled = true;
            document.getElementById('txtNumero').value = '';
        }
    }
</script>