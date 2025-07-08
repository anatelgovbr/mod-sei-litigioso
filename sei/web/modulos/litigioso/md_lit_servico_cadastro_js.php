<script type="text/javascript">
    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_lit_servico_consultar') {
            infraDesabilitarCamposAreaDados();
            $(".multipleSelect option").attr('disabled', true)
        }
        $(".multipleSelect").multipleSelect({
            filter: false,
            minimumCountSelected: 4,
            selectAll: false
        });

        if (document.getElementById('txtEnderecoWsdl').value != '' && document.getElementById('hdnValidouEnderecoWsdl').value == 'S' ) {
            validarWsdl();
        }
        // scrool estava ficando grande pois esta com os campos select como multiselect
        document.getElementById('divInfraAreaTela').style.height = 0;
        infraProcessarResize();
    }

    function changeOrigem() {
        var rdoIntegracao = document.getElementById('rdoIntegracao').checked;
        var divIntegracao = document.getElementById('divIntegracao');
        var divManual = document.getElementById('divManual');

        if (rdoIntegracao) {
            divIntegracao.style.display = 'block';
            divManual.style.display = 'none';
        } else {
            divIntegracao.style.display = 'none';
            divManual.style.display = 'block';
        }
    }

    function salvar() {
        var frm = document.getElementById('frmServicoCadastro');
        var rdoIntegracao = document.getElementById('rdoIntegracao');
        var rdoManual = document.getElementById('rdoManual');

        if (!rdoIntegracao.checked && !rdoManual.checked) {
            alert('Informe a Origem!');
            rdoIntegracao.focus();
            return;
        }

        if (rdoIntegracao.checked) {
            if (validarServicoIntegracao()) {
                frm.submit();
            }
        } else {
            if (validarServicoManual()) {
                frm.submit();
            }
        }

    }

    function cancelar() {
        location.href = "<?= $strUrlCancelar ?>";
    }

    function validarServicoManual() {
        var txtCodigo = document.getElementById('txtCodigo');
        var txtSigla = document.getElementById('txtSigla');
        var txtDescricao = document.getElementById('txtDescricao');
        var chkModalidade = document.getElementsByName('chkModalidade[]');
        var chkAbrangencia = document.getElementsByName('chkAbrangencia[]');

        if (txtCodigo.value.trim() == '') {
            alert('Informe o campo Código!');
            txtCodigo.focus();
            return false;
        }

        if (txtSigla.value.trim() == '') {
            alert('Informe o campo Sigla!');
            txtSigla.focus();
            return false;
        }

        if (txtDescricao.value.trim() == '') {
            alert('Informe o campo Descrição!');
            txtDescricao.focus();
            return false;
        }

        return true;
    }

    function validarServicoIntegracao() {
        if (infraTrim(document.getElementById('txtIntegracao').value) == '') {
            alert('Informe o campo do nome da integração!');
            txtCodigo.focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtEnderecoWsdl').value) == '') {
            alert('Informe o campo do endereço WSDL!');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }
        if (infraTrim(document.getElementById('selOperacao').value) == '') {
            alert('Informe o campo da operação!');
            document.getElementById('selOperacao').focus();
            return false;
        }

        if (document.getElementById('tableWebServiceServico') == null || document.getElementById('tableWebServiceServico') == null) {
            alert('Favor mapear a integração.');
            document.getElementById('btnMapear').focus();
            return false;
        }

        for (var i = 0; i < (document.getElementById('tableWebServiceServico').rows.length - 1); i++) {
            for (var j = 0; j < (document.getElementById('tableWebServiceServico').rows.length - 1); j++) {
                if (i == j) continue;
                if (document.getElementById('codigo_' + i).innerText == document.getElementById('codigo_' + j).innerText) {
                    var codigo = document.getElementById('codigo_' + j).innerText;
                    alert('O código ' + codigo + ' está duplicado no resultado do web-service. Favor verifique o mapeamento novamente.');
                    return false;
                }
            }
        }


        return true;
    }


    function validarWsdl() {
        var consultar = <?php echo $_GET['acao'] != 'md_lit_servico_consultar' ? 'true' : 'false'; ?>//;
        var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        var tipoWs = $('[name="tipoWs"]:checked').val();

        var versaoSoap = $('[name="versaoSoap"]').val();
        if (consultar) {
            versaoSoap = $('[name="versaoSoap"]').not(':disabled').val();
        }

        if (enderecoWsdl == '') {
            alert('Preencher o campo Endereço WSDL.');
            return false;
        }

        if (tipoWs != 'SOAP' || versaoSoap == undefined) {
            alert('Para validar este serviço informe o Tipo de Cliente WS como SOAP e sua Versão SOAP');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxValidarWsdl ?>",
            dataType: "xml",
            data: {
                endereco_wsdl: enderecoWsdl, tipoWs: tipoWs, versaoSoap: versaoSoap
            },
            beforeSend: function () {
                infraExibirAviso(false);
            },
            success: function (result) {
                var select = document.getElementById('selOperacao');
                //limpar todos os options
                select.options.length = 0;

                if ($(result).find('success').text() == 'true') {
                    var opt = document.createElement('option');
                    opt.value = '';
                    opt.innerHTML = '';
                    select.appendChild(opt);
                    var selectedValor = '<?= PaginaSEI::tratarHTML($selOperacao);?>';

                    $.each($(result).find('operacao'), function (key, value) {
                        var opt = document.createElement('option');
                        opt.value = $(value).text();
                        opt.innerHTML = $(value).text();
                        if ($(value).text() == selectedValor)
                            opt.selected = true;
                        select.appendChild(opt);
                    });

                    document.getElementById('gridOperacao').style.display = "block";
                } else {
                    let msgErr = "Não foi possível realizar a validação do Endereço WSDL informado.<br> " + $(result).find('msg').text();
                    exibirAlert( msgErr );
                    document.getElementById('gridOperacao').style.display = "none";
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
    }

    function exibirAlert(msg,tipoMsg='danger'){
        let divMsg = '<div class="alert alert-'+ tipoMsg +' alert-dismissible fade show" style="font-size:.875rem; top:0.25rem; margin-bottom: 14px !important; width:100%; margin:0 auto;" role="alert">'
                        + msg +
                        '<button type="button" class="close media h-100" data-dismiss="alert" aria-label="Fechar Mensagem" aria-labelledby="divInfraMsg0">'+
                        '<span aria-hidden="true" class="align-self-center"><b>X</b></span>'+
                        '</button>'+
                    '</div>';

        $('#divInfraBarraComandosSuperior').after( divMsg );

        scrollTela('divInfraBarraLocalizacao');
    }

    function scrollTela(idEle , top = 80){
        // scroll barra de rolagem automatica
        var nivel = document.getElementById( idEle ).offsetTop + top;
        divInfraMoverTopo = document.getElementById("divInfraAreaTelaD");
        $( divInfraMoverTopo ).animate( { scrollTop: nivel } , 600 );
    }
</script>
