<script type="text/javascript">
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

        if (document.getElementById('txtEnderecoWsdl').value != '') {
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

        if(validarSituacaoAgendamentoMarcada()){
            alert('� obrigat�rio marcar pelo menos uma Situa��o para Utilizar no Agendamento de consulta ao financeiro.');
            return;
        }

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

    function validarSituacaoAgendamentoMarcada(){
        var formulario = document.getElementById("frmSituacaoCadastro");
        var dados = new FormData(formulario);
        var valido = false;

        dados.forEach(function(valor, chave){
            if (chave.includes('sinUtilizarAgendamento')) {
                valido = true;
            }
        });
        return !valido;
    }

    function cancelar() {
        location.href = "<?= $strUrlCancelar ?>";
    }

    function validarSituacaoLancamentoManual() {
        var txtCodigo = document.getElementById('txtCodigo');
        var txtDescricao = document.getElementById('txtDescricao');

        if (txtCodigo.value.trim() == '') {
            alert('Informe o campo C�digo!');
            txtCodigo.focus();
            return false;
        }

        if (txtDescricao.value.trim() == '') {
            alert('Informe o campo Descri��o!');
            txtDescricao.focus();
            return false;
        }
        return true;
    }

    function validarSituacaoLancamentoIntegracao() {
        if (infraTrim(document.getElementById('txtIntegracao').value) == '') {
            alert('Informe o campo do nome da integra��o!');
            txtCodigo.focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtEnderecoWsdl').value) == '') {
            alert('Informe o campo do endere�o WSDL!');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }
        if (infraTrim(document.getElementById('selOperacao').value) == '') {
            alert('Informe o campo da opera��o!');
            document.getElementById('selOperacao').focus();
            return false;
        }

        if (document.getElementById('tableWebServiceSituacaoLancamento') == null || document.getElementById('tableWebServiceSituacaoLancamento') == null) {
            alert('Favor mapear a integra��o.');
            document.getElementById('btnMapear').focus();
            return false;
        }

        for (var i = 0; i < (document.getElementById('tableWebServiceSituacaoLancamento').rows.length - 1); i++) {
            for (var j = 0; j < (document.getElementById('tableWebServiceSituacaoLancamento').rows.length - 1); j++) {
                if (i == j) continue;
                if (document.getElementById('codigo_' + i).innerText == document.getElementById('codigo_' + j).innerText) {
                    var codigo = document.getElementById('codigo_' + j).innerText;
                    alert('O c�digo ' + codigo + ' est� duplicado no resultado do web-service. Favor verifique o mapeamento novamente.');
                    return false;
                }
            }
        }
        return true;
    }


    function validarWsdl() {
        var consultar = <?php echo $_GET['acao'] != 'md_lit_situacao_lancamento_consultar' ? 'true' : 'false'; ?>//;
        var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        var tipoWs = $('[name="tipoWs"]:checked').val();
        var versaoSoap = $('[name="versaoSoap"]').val();
        if (consultar) {
            versaoSoap = $('[name="versaoSoap"]').not(':disabled').val();
        }

        if (enderecoWsdl == '') {
            alert('Preencher o campo Endere�o WSDL.');
            return false;
        }

        if (tipoWs != 'SOAP' || versaoSoap == undefined) {
            alert('Paraaaaa validar este servi�o informe o Tipo de Cliente WS como SOAP e sua Vers�o Soap');
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

                } else {
                    alert($(result).find('msg').text());
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

    function abrirJanelaMapeamento() {
        var txtEnderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        var selOperacao = document.getElementById('selOperacao').value;
        var tipoWs = $('[name="tipoWs"]').val();
        var versaoSoap = $('[name="versaoSoap"]').val();

        if (txtEnderecoWsdl == '') {
            alert('Informe o Endere�o do WSDL');
            return;
        }
        if (selOperacao == '') {
            alert('Informe a opera��o');
            return;
        }

        var url = '<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_lancamento_integracao_mapear&acao_origem=' . $_GET['acao'] . '&id_md_lit_situacao_lancamento_integracao=' . $idMdLitSituacaoIntegracao); ?>';

        // Para passar parametro para uma modal, chamar a funcao abaixo sem a url e configurar
        // o form com os inputs de acordo o restante do c�digo ap�s a fun��o infraAbrirJanelaModal()
        infraAbrirJanelaModal('',850,550);

        var modalForm = document.createElement("form");
        modalForm.target = 'modal-frame';
        modalForm.action = url;
        modalForm.method = "POST";

        //adicionando o endere�o do WSDL
        var enderecoInput = document.createElement("input");
        enderecoInput.type = "hidden";
        enderecoInput.name = "txtEnderecoWsdl";
        enderecoInput.value = txtEnderecoWsdl;
        modalForm.appendChild(enderecoInput);

        //adicionando o endere�o da opera��o
        var operacaoInput = document.createElement("input");
        operacaoInput.type = "hidden";
        operacaoInput.name = "txtOperacao";
        operacaoInput.value = selOperacao;
        modalForm.appendChild(operacaoInput);

        var tipoWsInput = document.createElement("input");
        tipoWsInput.type = "hidden";
        tipoWsInput.name = "tipoWs";
        tipoWsInput.value = tipoWs;
        modalForm.appendChild(tipoWsInput);

        var versaoSoapInput = document.createElement("input");
        versaoSoapInput.type = "hidden";
        versaoSoapInput.name = "versaoSoap";
        versaoSoapInput.value = versaoSoap;
        modalForm.appendChild(versaoSoapInput);

        document.body.appendChild(modalForm);

        modalForm.submit();
    }

    function apagarMapear() {
        if (document.getElementById('tableParametroEntrada') != null || document.getElementById('tableParametroSaida') != null) {
            document.getElementById('tableParametroEntrada').remove();
            document.getElementById('tableParametroSaida').remove();
        }
    }

    function apagarOperacao() {
        var select = document.getElementById('selOperacao');
        select.options.length = 0;
        apagarMapear();
    }
</script>

