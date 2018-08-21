
<?if(0){?><script><?}?>
    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_lit_situacao_lancamento_consultar') {
            infraDesabilitarCamposAreaDados();
            $(".multipleSelect option").attr('disabled', true)
        }
        $(".multipleSelect").multipleSelect({
            filter: false,
            minimumCountSelected: 4,
            selectAll: false
        });

        if(document.getElementById('txtEnderecoWsdl').value != ''){
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
        }
        else {
            divIntegracao.style.display = 'none';
            divManual.style.display = 'block';
        }
    }

    function salvar() {
        var frm = document.getElementById('frmSituacaoCadastro');
        var rdoIntegracao = document.getElementById('rdoIntegracao');
        var rdoManual = document.getElementById('rdoManual');

        if (!rdoIntegracao.checked && !rdoManual.checked) {
            alert('Informe a Origem!');
            rdoIntegracao.focus();
            return;
        }

        if (rdoIntegracao.checked) {
            if (validarSituacaoLancamentoIntegracao()) {
                frm.submit();
            }
        } else {
            if (validarSituacaoLancamentoManual()) {
                frm.submit();
            }
        }

    }

    function cancelar() {
        location.href = "<?= $strUrlCancelar ?>";
    }

    function validarSituacaoLancamentoManual() {
        var txtCodigo = document.getElementById('txtCodigo');
        var txtDescricao = document.getElementById('txtDescricao');

        if (txtCodigo.value.trim() == '') {
            alert('Informe o campo Código!');
            txtCodigo.focus();
            return false;
        }

        if (txtDescricao.value.trim() == '') {
            alert('Informe o campo Descrição!');
            txtDescricao.focus();
            return false;
        }
        return true;
    }

    function validarSituacaoLancamentoIntegracao() {
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

        if(document.getElementById('tableWebServiceSituacaoLancamento') == null || document.getElementById('tableWebServiceSituacaoLancamento') == null){
            alert('Favor mapear a integração.');
            document.getElementById('btnMapear').focus();
            return false;
        }

        for(var i = 0; i < (document.getElementById('tableWebServiceSituacaoLancamento').rows.length-1); i++ ){
            for(var j = 0; j < (document.getElementById('tableWebServiceSituacaoLancamento').rows.length-1); j++ ){
                if(i==j) continue;
                if(document.getElementById('codigo_'+i).innerText == document.getElementById('codigo_'+j).innerText ){
                    var codigo = document.getElementById('codigo_'+j).innerText;
                    alert('O código '+codigo+' está duplicado no resultado do web-service. Favor verifique o mapeamento novamente.');
                    return false;
                }
            }
        }
        return true;
    }


    function validarWsdl() {
        var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        if (enderecoWsdl == '') {
            alert('Preenche o campo Endereço WSDL.');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxValidarWsdl ?>",
            dataType: "xml",
            data: {
                endereco_wsdl: enderecoWsdl,
            },
            beforeSend: function(){
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
                    var selectedValor = '<?= PaginaSEI::tratarHTML( $selOperacao );?>';

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
                    alert($(result).find('msg').text());
                    document.getElementById('gridOperacao').style.display = "none";
                }
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

    function abrirJanelaMapeamento() {
        var txtEnderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        var selOperacao = document.getElementById('selOperacao').value;

        if(txtEnderecoWsdl == ''){
            alert('Informe o Endereço do WSDL');
            return;
        }
        if( selOperacao == ''){
            alert('Informe a operação');
            return;
        }

        var url = '<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_lancamento_integracao_mapear&acao_origem=' . $_GET['acao'].'&id_md_lit_situacao_lancamento_integracao='.$idMdLitServicoIntegracao); ?>';
        var windowFeatures = "location=1,status=1,resizable=1,scrollbars=1";

        var modalForm = document.createElement("form");
        modalForm.target = "cadastrarMapeamento";
        modalForm.method = "POST"; // or "post" if appropriate
        modalForm.action = url;

        //adicionando o endereço do WSDL
        var enderecoInput = document.createElement("input");
        enderecoInput.type = "text";
        enderecoInput.name = "txtEnderecoWsdl";
        enderecoInput.value = txtEnderecoWsdl;
        modalForm.appendChild(enderecoInput);

        //adicionando o endereço da operação
        var operacaoInput = document.createElement("input");
        operacaoInput.type = "text";
        operacaoInput.name = "txtOperacao";
        operacaoInput.value = selOperacao;
        modalForm.appendChild(operacaoInput);
        modalForm.style.display = 'none';

        //adiciona no final da pagina
        document.body.appendChild(modalForm);

        var janela = infraAbrirJanela('',
            'cadastrarMapeamento',
            780,
            400,
            windowFeatures, //options
            true); //popUp
        if (janela) {
            modalForm.submit();
        } else {
            alert('Você deve permitir popups para o mapeamento funcionar.');
        }

    }
    function apagarMapear(){
        if(document.getElementById('tableParametroEntrada') != null || document.getElementById('tableParametroSaida') !=null){
            document.getElementById('tableParametroEntrada').remove();
            document.getElementById('tableParametroSaida').remove();
        }
    }

    function apagarOperacao(){
        var select = document.getElementById('selOperacao');
        select.options.length = 0;
        document.getElementById('gridOperacao').style.display = "none";
        apagarMapear();
    }
<?if(0){?></script><?}?>