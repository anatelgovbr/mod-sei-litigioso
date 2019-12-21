<?php

/**
 * @since  18/04/2017
 * @author André Luiz <andre.luiz@castgroup.com.br>
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEIExterna::getInstance()->validarPermissao($_GET['acao']);

    $strUrlCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_listar&id_servico=' . $_GET['id_servico']
                                                            . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_servico']));


    $strAcaoForm            = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_servico=' . $_GET['id_servico']);
    $strLinkAjaxValidarWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_servico_busca_operacao_wsdl');


    $arrComandos = array();


    $strToolTipLblInt = 'Caso exista sistema próprio de cadastro e controle de lista de Serviços outorgados pela instituição. Somente permitirá finalizar o cadastro após o Mapeamento dos campos.';
    $strToolTipLblMan = 'Caso não exista sistema próprio de cadastro e controle da lista de Serviços outorgados pela instituição. O código do serviço deve ser único, mesmo entre os Serviços cadastrados manualmente ou por integração.';


    $idMdLitServico   = $_GET['id_servico'];
    $rdoOrigem        = '';
    $txtCodigo        = '';
    $txtSigla         = '';
    $txtDescricao     = '';
    $idMdLitServicoIntegracao = '';

    switch ($_GET['acao']) {

        case 'md_lit_servico_cadastrar':
            $strTitulo = 'Cadastro de Serviços';

            $arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" onclick="salvar()" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

            $rdoOrigem              = $_POST['rdoOrigem'];
            $txtCodigo              = $_POST['txtCodigo'];
            $txtSigla               = $_POST['txtSigla'];
            $txtDescricao           = $_POST['txtDescricao'];
            // valor do input do integracao
            $selOperacao            = $_POST['selOperacao'];
            $txtEnderecoWsdl        = $_POST['txtEnderecoWsdl'];
            $txtIntegracao          = $_POST['txtIntegracao'];
            $tipoWs                 = $_POST['tipoWs'];
            $versaoSoap             = $_POST['versaoSoap'];


            if (isset($_POST['hdnSalvarServico'])) {

                if($_POST['rdoOrigem'] == MdLitServicoRN::$STA_ORIGEM_MANUAL){
                    $objMdLitServicoDTO = new MdLitServicoDTO();
                    $objMdLitServicoDTO->setStrStaOrigem(MdLitServicoRN::$STA_ORIGEM_MANUAL);
                    $objMdLitServicoDTO->setStrCodigo($_POST['txtCodigo']);
                    $objMdLitServicoDTO->setStrSigla($_POST['txtSigla']);
                    $objMdLitServicoDTO->setStrDescricao($_POST['txtDescricao']);
                    $objMdLitServicoDTO->setStrSinAtivo('S');

                    $objMdLitServicoRN  = new MdLitServicoRN();
                    $objMdLitServicoDTO = $objMdLitServicoRN->cadastrar($objMdLitServicoDTO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_listar' . '&acao_origem=' . $_GET['acao'] .
                            PaginaSEI::getInstance()->montarAncora($objMdLitServicoDTO->getNumIdMdLitServico())));
                }elseif($_POST['rdoOrigem'] == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO){

                    $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                    $arrMapeamento                = json_decode($_POST['hdnMapeamentoJson']);
                    $objMdLitServicoIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                    $objMdLitServicoIntegracaoDTO->setStrNomeIntegracao($_POST['txtIntegracao']);
                    $objMdLitServicoIntegracaoDTO->setStrOperacaoWsdl($_POST['selOperacao']);
                    $objMdLitServicoIntegracaoDTO->setStrTipoClienteWs($_POST['tipoWs']);
                    $objMdLitServicoIntegracaoDTO->setStrVersaoSoap($_POST['versaoSoap']);
                    $objMdLitServicoIntegracaoDTO->setArrModalidade($_POST['selModalidade']);
                    $objMdLitServicoIntegracaoDTO->setArrAbrangencia($_POST['selAbrangencia']);
                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao('');

                    if(count($arrMapeamento) > 0){
                        foreach ($arrMapeamento as $mapeamento) {

                            switch ($mapeamento->value) {
                                case MdLitServicoIntegracaoRN::$CODIGO:
                                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoCodigo($mapeamento->name);
                                    break;
                                case MdLitServicoIntegracaoRN::$DESCRICAO:
                                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoDescricao($mapeamento->name);
                                    break;
                                case MdLitServicoIntegracaoRN::$SIGLA:
                                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoSigla($mapeamento->name);
                                    break;
                                case MdLitServicoIntegracaoRN::$SITUACAO:
                                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao($mapeamento->name);
                                    break;
                            }
                            if ($mapeamento->chaveUnica) {
                                $objMdLitServicoIntegracaoDTO->setStrChaveUnica($mapeamento->name);
                            }
                        }
                    }

                    $objMdLitServicoIntegracaoRN  = new MdLitServicoIntegracaoRN();
                    $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->cadastrar($objMdLitServicoIntegracaoDTO);



                    //responsavel por selecionar os itens que foram salvos
                    $objMdLitServicoDTO = new MdLitServicoDTO();
                    $objMdLitServicoRN = new MdLitServicoRN();
                    $objMdLitServicoDTO->retNumIdMdLitServico();
                    $objMdLitServicoDTO->setNumIdMdLitServicoIntegracao($objMdLitServicoIntegracaoDTO->getNumIdMdLitServicoIntegracao());
                    $arrObjMdLitServicoDTO = $objMdLitServicoRN->listar($objMdLitServicoDTO);

                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_listar' . '&acao_origem=' . $_GET['acao'].
                            PaginaSEI::getInstance()->montarAncora(InfraArray::converterArrInfraDTO($arrObjMdLitServicoDTO, 'IdMdLitServico'))));
                }
                die;

            }
            break;

        case 'md_lit_servico_alterar':
            $strTitulo = 'Alterar Serviço';

            $arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" onclick="salvar()" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

            if (isset($_POST['hdnSalvarServico'])) {

                if($_POST['rdoOrigem'] == MdLitServicoRN::$STA_ORIGEM_MANUAL) {
                    $rdoOrigem = MdLitServicoRN::$STA_ORIGEM_MANUAL;
                    $txtCodigo = $_POST['txtCodigo'];
                    $txtSigla = $_POST['txtSigla'];
                    $txtDescricao = $_POST['txtDescricao'];


                    $objMdLitServicoDTO = new MdLitServicoDTO();
                    $objMdLitServicoDTO->setNumIdMdLitServico($_POST['hdnIdMdLitServico']);
                    $objMdLitServicoDTO->setStrStaOrigem(MdLitServicoRN::$STA_ORIGEM_MANUAL);
                    $objMdLitServicoDTO->setStrCodigo($_POST['txtCodigo']);
                    $objMdLitServicoDTO->setStrSigla($_POST['txtSigla']);
                    $objMdLitServicoDTO->setStrDescricao($_POST['txtDescricao']);
                    $objMdLitServicoDTO->setStrSinAtivo('S');
                    $objMdLitServicoDTO->setArrModalidade($_POST['chkModalidade']);
                    $objMdLitServicoDTO->setArrAbrangencia($_POST['chkAbrangencia']);

                    $objMdLitServicoRN = new MdLitServicoRN();
                    $objMdLitServicoRN->alterar($objMdLitServicoDTO);
                    header('Location: ' . $strUrlCancelar);
                    die;
                }elseif($_POST['rdoOrigem'] == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO) {
                    $idMdLitServicoIntegracao = $_POST['hdnIdMdLitServicoIntegracao'];
                    $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
                    $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                    $objMdLitServicoIntegracaoDTO->retTodos(true);
                    $objMdLitServicoIntegracaoDTO->setNumIdMdLitServicoIntegracao($idMdLitServicoIntegracao);

                    $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->consultar($objMdLitServicoIntegracaoDTO);

                    $arrMapeamento                = json_decode($_POST['hdnMapeamentoJson']);
                    $objMdLitServicoIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                    $objMdLitServicoIntegracaoDTO->setStrNomeIntegracao($_POST['txtIntegracao']);
                    $objMdLitServicoIntegracaoDTO->setStrOperacaoWsdl($_POST['selOperacao']);
                    $objMdLitServicoIntegracaoDTO->setStrTipoClienteWs($_POST['tipoWs']);
                    $objMdLitServicoIntegracaoDTO->setStrVersaoSoap($_POST['versaoSoap']);
                    $objMdLitServicoIntegracaoDTO->setArrModalidade($_POST['selModalidade']);
                    $objMdLitServicoIntegracaoDTO->setArrAbrangencia($_POST['selAbrangencia']);
                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao('');

                    if($arrMapeamento){
                        foreach ($arrMapeamento as $mapeamento) {

                            switch ($mapeamento->value) {
                                case MdLitServicoIntegracaoRN::$CODIGO:
                                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoCodigo($mapeamento->name);
                                    break;
                                case MdLitServicoIntegracaoRN::$DESCRICAO:
                                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoDescricao($mapeamento->name);
                                    break;
                                case MdLitServicoIntegracaoRN::$SIGLA:
                                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoSigla($mapeamento->name);
                                    break;
                                case MdLitServicoIntegracaoRN::$SITUACAO:
                                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao($mapeamento->name);
                                    break;
                            }
                            if ($mapeamento->chaveUnica) {
                                $objMdLitServicoIntegracaoDTO->setStrChaveUnica($mapeamento->name);
                            }
                        }
                    }

                    $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->alterar($objMdLitServicoIntegracaoDTO);

                    //responsavel por selecionar os itens que foram salvos
                    $objMdLitServicoDTO = new MdLitServicoDTO();
                    $objMdLitServicoRN = new MdLitServicoRN();
                    $objMdLitServicoDTO->retNumIdMdLitServico();
                    $objMdLitServicoDTO->setNumIdMdLitServicoIntegracao($idMdLitServicoIntegracao);
                    $arrObjMdLitServicoDTO = $objMdLitServicoRN->listar($objMdLitServicoDTO);

                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_listar' . '&acao_origem=' . $_GET['acao'].
                            PaginaSEI::getInstance()->montarAncora(InfraArray::converterArrInfraDTO($arrObjMdLitServicoDTO, 'IdMdLitServico'))));

                }

            }

            $objMdLitServicoDTO = new MdLitServicoDTO();
            $objMdLitServicoDTO->setNumIdMdLitServico($idMdLitServico);
            $objMdLitServicoDTO->setStrSinAtivo('S');
            $objMdLitServicoDTO->retTodos(true);
            $objMdLitServicoRN  = new MdLitServicoRN();
            $objMdLitServicoDTO = $objMdLitServicoRN->consultar($objMdLitServicoDTO);

            if (!$objMdLitServicoDTO) {
                throw new InfraException('Serviço não encontrado!');
            }

            $rdoOrigem = $objMdLitServicoDTO->getStrStaOrigem();

            if ($rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL) {

                //Campos Serviço Manual
                $txtCodigo    = $objMdLitServicoDTO->getStrCodigo();
                $txtSigla     = $objMdLitServicoDTO->getStrSigla();
                $txtDescricao = $objMdLitServicoDTO->getStrDescricao();

            }elseif( !empty($objMdLitServicoDTO->getNumIdMdLitServicoIntegracao()) ){

                $idMdLitServicoIntegracao = $objMdLitServicoDTO->getNumIdMdLitServicoIntegracao();
                $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
                $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                $objMdLitServicoIntegracaoDTO->retTodos(true);
                $objMdLitServicoIntegracaoDTO->setNumIdMdLitServicoIntegracao($idMdLitServicoIntegracao);

                $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->consultar($objMdLitServicoIntegracaoDTO);

                if (!$objMdLitServicoIntegracaoDTO) {
                    throw new InfraException('Serviço por integração não encontrado!');
                }

                $selOperacao = $objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl();
                $txtEnderecoWsdl = $objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl();
                $txtIntegracao = $objMdLitServicoIntegracaoDTO->getStrNomeIntegracao();
                $tipoWs = $objMdLitServicoIntegracaoDTO->getStrTipoClienteWs();
                $versaoSoap = $objMdLitServicoIntegracaoDTO->getStrVersaoSoap();
            }

            break;


        case 'md_lit_servico_consultar':
            $strTitulo = 'Consultar Serviço';

            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

            $idMdLitServico = $_GET['id_servico'];

            $objMdLitServicoDTO = new MdLitServicoDTO();
            $objMdLitServicoDTO->setNumIdMdLitServico($idMdLitServico);
//            $objMdLitServicoDTO->setStrSinAtivo('S');
            $objMdLitServicoDTO->retTodos(true);
            $objMdLitServicoRN  = new MdLitServicoRN();
            $objMdLitServicoDTO = $objMdLitServicoRN->consultar($objMdLitServicoDTO);

            if (!$objMdLitServicoDTO) {
                throw new InfraException('Serviço não encontrado!');
            }

            $rdoOrigem = $objMdLitServicoDTO->getStrStaOrigem();

            if ($rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL) {

                //Campos Serviço Manual
                $txtCodigo    = $objMdLitServicoDTO->getStrCodigo();
                $txtSigla     = $objMdLitServicoDTO->getStrSigla();
                $txtDescricao = $objMdLitServicoDTO->getStrDescricao();



            }elseif( !empty($objMdLitServicoDTO->getNumIdMdLitServicoIntegracao()) ){

                $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
                $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                $objMdLitServicoIntegracaoDTO->retTodos(true);
                $objMdLitServicoIntegracaoDTO->setNumIdMdLitServicoIntegracao($objMdLitServicoDTO->getNumIdMdLitServicoIntegracao());

                $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->consultar($objMdLitServicoIntegracaoDTO);

                if (!$objMdLitServicoIntegracaoDTO) {
                    throw new InfraException('Serviço por integração não encontrado!');
                }

                $selOperacao            = $objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl();
                $txtEnderecoWsdl        = $objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl();
                $txtIntegracao          = $objMdLitServicoIntegracaoDTO->getStrNomeIntegracao();
                $tipoWs                 = $objMdLitServicoIntegracaoDTO->getStrTipoClienteWs();
                $versaoSoap             = $objMdLitServicoIntegracaoDTO->getStrVersaoSoap();
            }

            break;


        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
            break;
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

if (isset($_POST['hdnMapeamentoJson']) && !empty($_POST['hdnMapeamentoJson'])) {
    $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
    $arrMapeamento                = json_decode($_POST['hdnMapeamentoJson']);
    $objMdLitServicoIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
    $objMdLitServicoIntegracaoDTO->setStrOperacaoWsdl($_POST['selOperacao']);
    $objMdLitServicoIntegracaoDTO->setStrTipoClienteWs($_POST['tipoWs']);
    $objMdLitServicoIntegracaoDTO->setStrVersaoSoap($_POST['versaoSoap']);

    foreach ($arrMapeamento as $mapeamento) {

        switch ($mapeamento->value) {
            case MdLitServicoIntegracaoRN::$CODIGO:
                $objMdLitServicoIntegracaoDTO->setStrMapeamentoCodigo($mapeamento->name);
                break;
            case MdLitServicoIntegracaoRN::$DESCRICAO:
                $objMdLitServicoIntegracaoDTO->setStrMapeamentoDescricao($mapeamento->name);
                break;
            case MdLitServicoIntegracaoRN::$SIGLA:
                $objMdLitServicoIntegracaoDTO->setStrMapeamentoSigla($mapeamento->name);
                break;
            case MdLitServicoIntegracaoRN::$SITUACAO:
                $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao($mapeamento->name);
                break;
        }
        if ($mapeamento->chaveUnica) {
            $objMdLitServicoIntegracaoDTO->setStrChaveUnica($mapeamento->name);
        }
    }
}
try{
    if($objMdLitServicoIntegracaoDTO)
        $strResultadoTabelaServicoIntegracao = MdLitServicoIntegracaoINT::montarTabelaServicoIntegracao($objMdLitServicoIntegracaoDTO);

}catch (Exception $e){var_dump($e);exit;
    $exception = new InfraException();
    $exception->adicionarValidacao('Não foi possível carregar o web-service.');
    PaginaSEI::getInstance()->processarExcecao($exception);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

require_once 'md_lit_css_geral.php';
?>

    label.checkbox-label input[type=checkbox]{
    position: relative;
    vertical-align: middle;
    bottom: 1px;
    }

    label.radio-label input[type=radio]{
    position: relative;
    vertical-align: middle;
    bottom: 2px;
    }
    div#divInfraAreaTabela {margin-top: 20px;float:left;}
    div#divInfraAreaDados {overflow: visible;}
    #btnValidar{margin: 19px 0px 0 13px;}
    #gridOperacao{display:none}
    table td div.ms-parent.infraSelect.multipleSelect{font-size: 0.9em;}
<?php if($_GET['acao'] == 'md_lit_servico_consultar'){  ?>
    #btnValidar, #btnMapeamento{display: none}
<?php } ?>

<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
#PaginaSEI::getInstance()->abrirJavaScript();
?>
    <script>
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

            if(document.getElementById('tableWebServiceServico') == null || document.getElementById('tableWebServiceServico') == null){
                alert('Favor mapear a integração.');
                document.getElementById('btnMapear').focus();
                return false;
            }

            for(var i = 0; i < (document.getElementById('tableWebServiceServico').rows.length-1); i++ ){
                for(var j = 0; j < (document.getElementById('tableWebServiceServico').rows.length-1); j++ ){
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
            var consultar  = <?php echo $_GET['acao'] != 'md_lit_servico_consultar' ? 'true' : 'false'; ?>//;
            var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
            var tipoWs = $('[name="tipoWs"]:checked').val();

            var versaoSoap = $('[name="versaoSoap"]').val();
            if(consultar){
                versaoSoap = $('[name="versaoSoap"]').not(':disabled').val();
            }

            if (enderecoWsdl == '') {
                alert('Preencher o campo Endereço WSDL.');
                return false;
            }

            if(tipoWs != 'SOAP' || versaoSoap == undefined){
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
    </script>

<?php
#PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmServicoCadastro" method="post" action="<?= $strAcaoForm ?>">
        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

        <?php PaginaSEI::getInstance()->abrirAreaDados('220px'); ?>
        <div class="grid grid_8">
            <fieldset class="infraFieldset">
                <legend class="infraLegend">&nbsp;Origem&nbsp;</legend>
                <div class="grid grid_6">

                    <div class="grid grid_3">
                        <label for="rdoIntegracao" name="lblIntegracao" class="radio-label infraLabelRadio">
                            <input type="radio" name="rdoOrigem" id="rdoIntegracao"
                                   value="<?= MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ?>" onchange="changeOrigem();"
                                <?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ? "checked='checked'" : '' ?>>
                            Integração
                        </label>
                        <span class="tooltipAjuda" <?= PaginaSEI::montarTitleTooltip($strToolTipLblInt) ?> ></span>
                    </div>

                    <div class="grid grid_3">
                        <label for="rdoManual" name="lblManual" class="radio-label infraLabelRadio">
                            <input type="radio" name="rdoOrigem" id="rdoManual"
                                   value="<?= MdLitServicoRN::$STA_ORIGEM_MANUAL ?>" onchange="changeOrigem();"
                                <?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL ? "checked='checked'" : '' ?>>
                            Manual
                        </label>
                        <span class="tooltipAjuda" <?= PaginaSEI::montarTitleTooltip($strToolTipLblMan) ?> ></span>
                    </div>

                </div>
            </fieldset>
        </div>


        <div class="" id="divIntegracao"
             style="display:<?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ? 'block' : 'none' ?>">
            <?php
            //Get Integração
            require_once 'md_lit_servico_cadastro_integracao.php';

            PaginaSEI::getInstance()->montarAreaTabela($strResultadoTabelaServicoIntegracao['strResultado'], $strResultadoTabelaServicoIntegracao['numRegistros']);
            ?>
        </div>

        <div class="" id="divManual"
             style="display:<?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL ? 'block' : 'none' ?>">
            <div class="clear-margin-3"></div>
            <div class="grid grid_7-8">
                <div class="grid grid_4">
                    <label class="infraLabelObrigatorio" id="lblCodigo" for="txtCodigo">Código:</label>
                    <input type="text" id="txtCodigo" name="txtCodigo" maxlength="10" value="<?= $txtCodigo ?>"
                           onkeypress="return infraMascaraTexto(this,event,10);"/>
                </div>
                <div class="clear-margin-2"></div>
                <div class="grid grid_4">
                    <label class="infraLabelObrigatorio" id="lblSigla" for="txtSigla">Sigla:</label>
                    <input type="text" id="txtSigla" name="txtSigla" maxlength="10" value="<?= $txtSigla ?>"
                           onkeypress="return infraMascaraTexto(this,event,10);"/>
                </div>
                <div class="clear-margin-2"></div>
                <div class="grid grid_7-8">
                    <label class="infraLabelObrigatorio" id="lblDescricao" for="txtDescricao">Descrição:</label>
                    <input type="text" id="txtDescricao" name="txtDescricao" maxlength="100"
                           value="<?= $txtDescricao ?>"
                           onkeypress="return infraMascaraTexto(this,event,100);"/>
                </div>
                <div class="clear-margin-2"></div>
            </div>
        </div>
        <input type="hidden" id="hdnSalvarServico" name="hdnSalvarServico" value=""/>
        <input type="hidden" id="hdnIdMdLitServico" name="hdnIdMdLitServico" value="<?= $idMdLitServico ?>"/>
        <input type="hidden" id="hdnIdMdLitServico" name="hdnIdMdLitServicoIntegracao" value="<?= $idMdLitServicoIntegracao ?>"/>

        <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
    </form>

<?php PaginaSEI::getInstance()->fecharBody(); ?>
<?php PaginaSEI::getInstance()->fecharHtml(); ?>