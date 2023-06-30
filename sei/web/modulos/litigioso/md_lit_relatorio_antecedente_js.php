<script type="text/javascript">
    <?php if($showComboDeci){ ?>
    var objLupaInteressados = null;
    var objAutoCompletarInteressados = null;

    function submitFormPesquisa() {

        if (validarPesquisaAntecedente()) {
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }

    function validarPesquisaAntecedente() {
        if (objLupaInteressados.hdn.value == '') {
            alert("Selecione ao menos um interessado!");
            return false;
        }

        if (document.getElementById('txtDtCorte').value == '') {
            alert("Informe a data de corte.");
            return false;
        }
        return true;
    }

    function inicializar() {

        <?php if($msgInicialização){?>
        alert("<?=$msgInicialização?>");
        <?php }?>

        objAutoCompletarInteressados = new infraAjaxAutoCompletar('hdnIdInteressados', 'txtInteressados', '<?=$strLinkAjaxInteressados?>');
        objAutoCompletarInteressados.limparCampo = true;
        objAutoCompletarInteressados.tamanhoMinimo = 3;
        objAutoCompletarInteressados.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtInteressados').value;
        };

        objAutoCompletarInteressados.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
                var options = document.getElementById('selInteressados').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Interessado já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selInteressados'), descricao, id);

                    objLupaInteressados.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtInteressados').value = '';
                document.getElementById('txtInteressados').focus();

            }
        };
        objLupaInteressados = new infraLupaSelect('selInteressados', 'hdnInteressados', '<?=$strLinkInteressados?>');

    }

    function limparFiltros() {
        objLupaInteressados.limpar();
        document.getElementById('txtDtCorte').value = '';

    }

    function exportarExcel() {
        if (validarPesquisaAntecedente()) {
            document.getElementById('divInfraAreaTela').style.height = '100%';
            var UrlAntiga = document.getElementById('frmTipoControleLitigiosoLista').action;
            var urlExcel = '<?= $strUrlExcel ?>';
            document.getElementById('frmTipoControleLitigiosoLista').action = urlExcel;
            document.getElementById('frmTipoControleLitigiosoLista').target = '_blank';
            document.getElementById('frmTipoControleLitigiosoLista').submit();


            document.getElementById('frmTipoControleLitigiosoLista').action = UrlAntiga;
            document.getElementById('frmTipoControleLitigiosoLista').removeAttribute('target');
        }
    }


    function validarFormatoData(obj) {
        var validar = infraValidarData(obj, false);
        if (!validar) {
            alert('Data Inválida!');
            obj.value = '';
        }
    }
    <?php } ?>
    function resizeIFrameOrientacao() {
        var id = 'ifrOrientacaoHTML';
        var ifrm = document.getElementById(id);
        ifrm.style.visibility = 'hidden';
        ifrm.style.height = "10px";

        var doc = ifrm.contentDocument ? ifrm.contentDocument : ifrm.contentWindow.document;
        doc = doc || document;
        var body = doc.body, html = doc.documentElement;

        var width = Math.max(body.scrollWidth, body.offsetWidth,
            html.clientWidth, html.scrollWidth, html.offsetWidth);
        ifrm.style.width = '100%';

        var height = Math.max(body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight);
        ifrm.style.height = height + 'px';

        ifrm.style.visibility = 'visible';
    }

    // Adiciona a class "infraLabelOpcional" quando não retorna nenhum registro na grid
    // para ficar na mesma formatação das labels que retornam dados referentes a tempo
    if( $('#divInfraAreaTabela').find('table').length == 0 ){
        $('#divInfraAreaPaginacaoSuperior').hide();
        $('#divInfraAreaTabela').addClass('mt-2');
        $('#divInfraAreaTabela > label').addClass('infraLabelOpcional'); 
    }else{
        if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
        }
    }
</script>