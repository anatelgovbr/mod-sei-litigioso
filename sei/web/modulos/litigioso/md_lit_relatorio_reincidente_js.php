<script type="text/javascript">
    var objLupaInteressados = null;
    var objAutoCompletarInteressados = null;

    var objLupaDispositivos = null;
    var objAutoCompletarDispositivos = null;

    var objLupaConduta = null;
    var objAutoCompletarConduta = null;

    function submitFormPesquisa() {

        if (validarPesquisaReincidencia()) {
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }

    function validarPesquisaReincidencia() {
        if (objLupaInteressados != null && objLupaInteressados.hdn.value == '') {
            alert("Informe ao menos um interessado.");
            return false;
        }

        if (document.getElementById('txtDtCorte') != null && document.getElementById('txtDtCorte').value == '') {
            alert("Informe a data de corte.");
            return false;
        }

        if (document.getElementById('hdnDispositivo') != null && document.getElementById('hdnDispositivo').value == '') {
            alert("Informe o Dispositivo Normativo.");
            return false;
        }

        if (document.getElementById('hdnConduta') != null && document.getElementById('hdnConduta').value == '') {
            alert("Informe a Conduta.");
            return false;
        }
        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){ ?>
        verificarDispositivoConduta();
        if (document.getElementById('hdnDispositivoConduta') != null && document.getElementById('hdnDispositivoConduta').value == '') {
            alert("Nos filtros existe Dispositivo Normativo sem ter a seleção de pelo menos uma Conduta correspondente. Conforme as regras de definição de infração de mesma natureza, é necessário selecionar Dispositivo Normativo e Conduta correspondentes.");
            return false;
        }
        <?php } ?>

        return true;
    }

    function inicializar() {
        <?php if($msgInicializacao){?>
        alert("<?=$msgInicializacao?>");
        <?php }?>
        <?php if($mostrarFiltro){ ?>
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

        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO || $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){ ?>
        //inicio da configuração do dispositivo
        objAutoCompletarDispositivos = new infraAjaxAutoCompletar('hdnIdDispositivo', 'txtDispositivo', '<?=$strLinkAjaxDispositivos?>');
        objAutoCompletarDispositivos.limparCampo = false;

        objAutoCompletarDispositivos.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtDispositivo').value;
        };

        objAutoCompletarDispositivos.processarResultado = function (id, descricao, complemento) {

            descricao = $("<pre>").html(descricao).text();
            if (id != '') {
                var options = document.getElementById('selDispositivo').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Dispositivo Normativo já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selDispositivo'), descricao, id);

                    objLupaDispositivos.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtDispositivo').value = '';
                document.getElementById('txtDispositivo').focus();

            }
        };

        objLupaDispositivos = new infraLupaSelect('selDispositivo', 'hdnDispositivo', '<?=$strLinkDispositivos?>');
        <?php } ?>


        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$CONDUTA){ ?>
        //inicio da configuração da conduta
        objAutoCompletarConduta = new infraAjaxAutoCompletar('hdnIdConduta', 'txtConduta', '<?=$strLinkAjaxConduta?>');
        objAutoCompletarConduta.limparCampo = false;
        objAutoCompletarConduta.tamanhoMinimo = 3;
        objAutoCompletarConduta.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtConduta').value;
        };

        objAutoCompletarConduta.processarResultado = function (id, descricao, complemento) {

            descricao = $("<pre>").html(descricao).text();
            if (id != '') {
                var options = document.getElementById('selConduta').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Conduta já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selConduta'), descricao, id);

                    objLupaConduta.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtConduta').value = '';
                document.getElementById('txtConduta').focus();

            }
        };

        objLupaConduta = new infraLupaSelect('selConduta', 'hdnConduta', '<?=$strLinkConduta?>');


        <?php }?>

        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){ ?>
        //inicio da configuração se estiver parametrizado conduta e dispositivo aonde a conduta depende dos dispositivos selecionados
        objAutoCompletarConduta = new infraAjaxAutoCompletar('hdnIdConduta', 'txtConduta', '<?=$strLinkAjaxConduta?>');
        objAutoCompletarConduta.limparCampo = false;
        objAutoCompletarConduta.prepararExecucao = function () {
            if (document.getElementById('hdnDispositivo').value != '') {
                var arrSelectDispositivo = getSelectValues(document.getElementById('selDispositivo'));
                return 'palavras_pesquisa=' + document.getElementById('txtConduta').value + '&' + parameterizeArray('id_dispositivo', arrSelectDispositivo);
            }
            alert('Informe ao menos um Dispositivo Normativo');
            return false;
        };

        objAutoCompletarConduta.processarResultado = function (id, descricao, complemento) {

            descricao = $("<pre>").html(descricao).text();
            if (id != '') {
                var options = document.getElementById('selConduta').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Conduta já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selConduta'), descricao, id);

                    objLupaConduta.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtConduta').value = '';
                document.getElementById('txtConduta').focus();

            }
        };

        objLupaConduta = new infraLupaSelect('selConduta', 'hdnConduta', '<?=$strLinkConduta?>');
        objLupaConduta.validarSelecionar = function () {
            if (document.getElementById('hdnDispositivo').value != '') {
                objLupaConduta.id_dispositivo = getSelectValues(document.getElementById('selDispositivo'));
                return true;
            }
            alert('Informe ao menos um Dispositivo Normativo');
            return false;
        };
        objLupaDispositivos.processarRemocao = function (temp) {
            verificarDispositivoConduta();
            for (var i = 0; i < temp.length; i++) {
                var idDispositivo = temp[i].value + "±";
                if (document.getElementById('hdnDispositivoConduta').value.indexOf(idDispositivo) > -1) {
                    alert('Este Dispositivo Normativo possui Conduta selecionada no filtro abaixo. Remova primeiro a Conduta correspondente para poder remover o Dispositivo Normativo neste filtro.');
                    return false;
                }
            }
            return true;
        };


        <?php }?>

        <?php } ?>
    }

    function parameterizeArray(key, arr) {
        arr = arr.map(encodeURIComponent);
        return key + '[]=' + arr.join('&' + key + '[]=');
    }

    function getSelectValues(select) {
        var result = [];
        var options = select && select.options;
        var opt;

        for (var i = 0, iLen = options.length; i < iLen; i++) {
            opt = options[i];
            result.push(opt.value || opt.text);
        }
        return result;
    }

    function limparFiltros() {
        objLupaInteressados.limpar();
        objLupaConduta.limpar();
        objLupaDispositivos.limpar();
        document.getElementById('txtDtCorte').value = '';
        document.getElementById('selCondutaEspecifica').value = 'null';
    }

    function validarFormatoData(obj) {
        var validar = infraValidarData(obj, false);
        if (!validar) {
            alert('Data Inválida!');
            obj.value = '';
        }
    }

    function exportarExcel() {
        if (validarPesquisaReincidencia()) {
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

    function verificarDispositivoConduta() {
        document.getElementById('hdnDispositivoConduta').value = '';
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxAssociarDispositivoConduta ?>",
            //dataType: "json",
            dataType: "xml",
            async: false,
            data: {
                hdnDispositivo: document.getElementById('hdnDispositivo').value,
                hdnConduta: document.getElementById('hdnConduta').value
            },
            success: function (result) {
                var valDispositivoConduta = '';
                $.each($(result).find('item'), function (key, value) {
                    var idDispositivo = $(this).attr('id_dispositivo');
                    var idConduta = $(this).attr('id_conduta');

                    if (key > 0) {
                        valDispositivoConduta += '¥';
                    }
                    valDispositivoConduta += idDispositivo + '±' + idConduta;
                });
                document.getElementById('hdnDispositivoConduta').value = valDispositivoConduta;
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            },
            complete: function (result) {
            }
        });
    }

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