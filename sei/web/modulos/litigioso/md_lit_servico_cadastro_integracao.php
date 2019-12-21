<div class="clear-margin-3"></div>
<?php $strToolTipEnd = '<<a descrição do texto de ajuda será elaborado após o serviço estar disponível no barramento, pois teremos verificado todas as validações necessárias>>' ?>
<div class="grid grid_8">
    <div class="grid grid_8">
        <label class="infraLabelObrigatorio" id="lblIntegracao" for="txtIntegracao">Nome da Integração:</label>
        <input type="text" id="txtIntegracao" name="txtIntegracao" value="<?= $txtIntegracao ?>" />
    </div>
    <div class="clear-margin-2"></div>
    <div class="clear"></div>
    <div class="grid grid_3">
        <label id="lbltipoWs" for="tipoWs" class="infraLabelObrigatorio">
            Tipo Cliente WS:
        </label>
        <div class="clear"></div>

        <input type="radio" name="tipoWs" value="SOAP" checked>
        <span>
            <label for="tipoWs" class="infraLabelCheckbox">
                SOAP
            </label>
        </span>
<!--         O campo com  apção rest deve ser desabilidado quando houver suporte -->
<!--        <input type="radio" name="tipoWs" value="REST" disabled>-->
<!--        <span>-->
<!--            <label for="tipoWs" class="infraLabelCheckbox">-->
<!--                REST-->
<!--            </label>-->
<!--        </span>-->
    </div>
    <div class="grid grid_2 soap">
        <label id="lbltipoWs" for="tipoWs" class="infraLabelObrigatorio">
            Versão SOAP:
        </label>
        <div class="clear"></div>
        <select id="versaoSoap" name="versaoSoap">
            <option value="1.2" <?= $versaoSoap == '1.2' ? 'selected' : '' ?>>1.2</option>
            <option value="1.1" <?= $versaoSoap == '1.1' ? 'selected' : '' ?>>1.1</option>
        </select>
    </div>
    <div class="clear-margin-2"></div>
    <div class="grid grid_11">
        <div class="grid grid_8">
            <label class="infraLabelObrigatorio" id="lblEndereco" for="txtEndereco">Endereço WSDL:</label>
            <input type="text" id="txtEnderecoWsdl" name="txtEnderecoWsdl" onchange="apagarOperacao()" value="<?= $txtEnderecoWsdl ?>" />
        </div>
        <div class="grid grid_3">
            <button class="infraButton" name="btnValidar" type="button" id="btnValidar" onclick="validarWsdl()">Validar</button>
        </div>
    </div>
    <div class="clear-margin-2"></div>
    <div class="grid grid_11" id="gridOperacao">
        <div class="grid grid_8">
            <label class="infraLabelObrigatorio" id="lbloperacao" for="txtOperacao">Operação:</label>
            <select id="selOperacao" name="selOperacao" onchange="apagarMapear()" />
            </select>
        </div>
        <div class="grid grid_3">
            <span <?= PaginaSEI::montarTitleTooltip($strToolTipEnd) ?> class="tooltipAjuda tooltipAjudaInput"></span>
            <button class="infraButton" type="button" name="btnMapeamento" id="btnMapeamento"
                    onclick="abrirJanelaMapeamento();">Mapeamento
            </button>
        </div>
    </div>
</div>
<input type="hidden" id="hdnMapeamentoJson" name="hdnMapeamentoJson" value='<?= $_POST['hdnMapeamentoJson'] ?>'/>

<?php PaginaSEI::getInstance()->abrirJavaScript(); ?>
<?if(0){?><script><?}?>
    function abrirJanelaMapeamento() {
        var txtEnderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        var selOperacao = document.getElementById('selOperacao').value;
        var tipoWs = $('[name="tipoWs"]').val();
        var versaoSoap = $('[name="versaoSoap"]').val();

        if(txtEnderecoWsdl == ''){
            alert('Informe o Endereço do WSDL');
            return;
        }
        if( selOperacao == ''){
            alert('Informe a operação');
            return;
        }

        var url = '<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_integracao_mapear&acao_origem=' . $_GET['acao'].'&id_md_lit_servico_integracao='.$idMdLitServicoIntegracao); ?>';
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

        var tipoWsInput = document.createElement("input");
        tipoWsInput.type = "text";
        tipoWsInput.name = "tipoWs";
        tipoWsInput.value = tipoWs;
        modalForm.appendChild(tipoWsInput);
        modalForm.style.display = 'none';

        var versaoSoapInput = document.createElement("input");
        versaoSoapInput.type = "text";
        versaoSoapInput.name = "versaoSoap";
        versaoSoapInput.value = versaoSoap;
        modalForm.appendChild(versaoSoapInput);
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
<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>