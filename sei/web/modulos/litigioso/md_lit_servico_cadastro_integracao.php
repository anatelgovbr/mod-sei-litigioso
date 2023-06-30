<?php $strToolTipEnd = '<<a descrição do texto de ajuda será elaborado após o serviço estar disponível no barramento, pois teremos verificado todas as validações necessárias>>' ?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <label class="infraLabelObrigatorio" id="lblIntegracao" for="txtIntegracao">Nome da Integração:</label>
                <input type="text" id="txtIntegracao" name="txtIntegracao" class="infraText form-control"
                    value="<?= $txtIntegracao ?>"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-5 col-xl-4">
            <div class="form-group">
                <label id="lbltipoWs" for="tipoWs" class="infraLabelObrigatorio">
                    Tipo Cliente WS:
                </label>
                <br/>
                <label for="tipoWs" class="infraLabelCheckbox">
                    <input type="radio" name="tipoWs" class="infraRadio" id="tipoWs" value="SOAP" checked> SOAP
                </label>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5">
            <div class="form-group">
                <label id="lbltipoWs" for="tipoWs" class="infraLabelObrigatorio">
                    Versão SOAP:
                </label>
                <select id="versaoSoap" name="versaoSoap" class="infraSelect form-control">
                    <option value="1.2" <?= $versaoSoap == '1.2' ? 'selected' : '' ?>>1.2</option>
                    <option value="1.1" <?= $versaoSoap == '1.1' ? 'selected' : '' ?>>1.1</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <label class="infraLabelObrigatorio" id="lblEndereco" for="txtEndereco">Endereço WSDL:</label>
                <div class="input-group mb-3">
                    <input type="text" id="txtEnderecoWsdl" name="txtEnderecoWsdl" class="infraText form-control"
                        onchange="apagarOperacao()"
                        value="<?= $txtEnderecoWsdl ?>"/>
                    <button class="infraButton" name="btnValidar" type="button" id="btnValidar" onclick="validarWsdl()">
                        Validar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <label class="infraLabelObrigatorio" id="lbloperacao" for="txtOperacao">Operação:
                    <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($strToolTipEnd, 'Ajuda') ?>
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img border="0" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                            class="infraImgModulo"/>
                    </a>
                </label>
                <div class="input-group mb-3">
                    <select id="selOperacao" name="selOperacao" onchange="apagarMapear()" class="infraSelect form-control">
                    </select>
                    <button class="infraButton" type="button" name="btnMapeamento" id="btnMapeamento"
                            onclick="abrirJanelaMapeamento();">Mapeamento
                    </button>
                </div>
            </div>
        </div>
        <div class="grid grid_11" id="gridOperacao">
            <div class="grid grid_8">
            </div>
            <div class="grid grid_3">
                <span <?= PaginaSEI::montarTitleTooltip($strToolTipEnd) ?> class="tooltipAjuda tooltipAjudaInput"></span>
            </div>
        </div>
        <input type="hidden" id="hdnMapeamentoJson" name="hdnMapeamentoJson" value='<?= $_POST['hdnMapeamentoJson'] ?>'/>
    </div>
<?php PaginaSEI::getInstance()->abrirJavaScript(); ?>
<? if (0){ ?>
    <script><?}?>
        function abrirJanelaMapeamento() {
            var txtEnderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
            var selOperacao = document.getElementById('selOperacao').value;
            var tipoWs = $('[name="tipoWs"]').val();
            var versaoSoap = $('[name="versaoSoap"]').val();

            if (txtEnderecoWsdl == '') {
                alert('Informe o Endereço do WSDL');
                return;
            }
            if (selOperacao == '') {
                alert('Informe a operação');
                return;
            }

            var url = '<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_integracao_mapear&acao_origem=' . $_GET['acao'] . '&id_md_lit_servico_integracao=' . $idMdLitServicoIntegracao); ?>';
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
                600,
                windowFeatures, //options
                true); //popUp
            if (janela) {
                modalForm.submit();
            } else {
                alert('Você deve permitir popups para o mapeamento funcionar.');
            }

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
            document.getElementById('gridOperacao').style.display = "none";
            apagarMapear();
        }
        <? if (0){ ?></script><? } ?>
<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>
