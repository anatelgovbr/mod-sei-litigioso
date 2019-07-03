<div class="clear-margin-3"></div>
<?php $strToolTipEnd = '<<a descri��o do texto de ajuda ser� elaborado ap�s o servi�o estar dispon�vel no barramento, pois teremos verificado todas as valida��es necess�rias>>' ?>
<div class="grid grid_8">
    <div class="grid grid_8">
        <label class="infraLabelObrigatorio" id="lblIntegracao" for="txtIntegracao">Nome da Integra��o:</label>
        <input type="text" id="txtIntegracao" name="txtIntegracao" value="<?= $txtIntegracao ?>" />
    </div>
    <div class="clear-margin-2"></div>
    <div class="grid grid_11">
        <div class="grid grid_8">
            <label class="infraLabelObrigatorio" id="lblEndereco" for="txtEndereco">Endere�o WSDL:</label>
            <input type="text" id="txtEnderecoWsdl" name="txtEnderecoWsdl" onchange="apagarOperacao()" value="<?= $txtEnderecoWsdl ?>" />
        </div>
        <div class="grid grid_3">
            <button class="infraButton" name="btnValidar" type="button" id="btnValidar" onclick="validarWsdl()">Validar</button>
        </div>
    </div>
    <div class="clear-margin-2"></div>
    <div class="grid grid_11" id="gridOperacao">
        <div class="grid grid_8">
            <label class="infraLabelObrigatorio" id="lbloperacao" for="txtOperacao">Opera��o:</label>
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

        if(txtEnderecoWsdl == ''){
            alert('Informe o Endere�o do WSDL');
            return;
        }
        if( selOperacao == ''){
            alert('Informe a opera��o');
            return;
        }

        var url = '<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_integracao_mapear&acao_origem=' . $_GET['acao'].'&id_md_lit_servico_integracao='.$idMdLitServicoIntegracao); ?>';
        var windowFeatures = "location=1,status=1,resizable=1,scrollbars=1";

        var modalForm = document.createElement("form");
        modalForm.target = "cadastrarMapeamento";
        modalForm.method = "POST"; // or "post" if appropriate
        modalForm.action = url;

        //adicionando o endere�o do WSDL
        var enderecoInput = document.createElement("input");
        enderecoInput.type = "text";
        enderecoInput.name = "txtEnderecoWsdl";
        enderecoInput.value = txtEnderecoWsdl;
        modalForm.appendChild(enderecoInput);

        //adicionando o endere�o da opera��o
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
            alert('Voc� deve permitir popups para o mapeamento funcionar.');
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