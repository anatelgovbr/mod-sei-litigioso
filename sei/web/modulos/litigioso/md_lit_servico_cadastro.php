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


    $strAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_servico=' . $_GET['id_servico']);
    $strLinkAjaxValidarWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_servico_busca_operacao_wsdl');


    $arrComandos = array();


    $strToolTipLblInt = 'Caso exista sistema próprio de cadastro e controle de lista de Serviços outorgados pela instituição. Somente permitirá finalizar o cadastro após o Mapeamento dos campos.';
    $strToolTipLblMan = 'Caso não exista sistema próprio de cadastro e controle da lista de Serviços outorgados pela instituição. O código do serviço deve ser único, mesmo entre os Serviços cadastrados manualmente ou por integração.';


    $idMdLitServico = $_GET['id_servico'];
    $rdoOrigem = '';
    $txtCodigo = '';
    $txtSigla = '';
    $txtDescricao = '';
    $idMdLitServicoIntegracao = '';
    $isValidouEnderecoWsdl = true;

    switch ($_GET['acao']) {

        case 'md_lit_servico_cadastrar':
            $strTitulo = 'Cadastro de Serviços';

            $arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" onclick="salvar()" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

            $rdoOrigem = $_POST['rdoOrigem'];
            $txtCodigo = $_POST['txtCodigo'];
            $txtSigla = $_POST['txtSigla'];
            $txtDescricao = $_POST['txtDescricao'];
            // valor do input do integracao
            $selOperacao = $_POST['selOperacao'];
            $txtEnderecoWsdl = $_POST['txtEnderecoWsdl'];
            $txtIntegracao = $_POST['txtIntegracao'];
            $tipoWs = $_POST['tipoWs'];
            $versaoSoap = $_POST['versaoSoap'];


            if (isset($_POST['hdnSalvarServico'])) {

                if ($_POST['rdoOrigem'] == MdLitServicoRN::$STA_ORIGEM_MANUAL) {
                    $objMdLitServicoDTO = new MdLitServicoDTO();
                    $objMdLitServicoDTO->setStrStaOrigem(MdLitServicoRN::$STA_ORIGEM_MANUAL);
                    $objMdLitServicoDTO->setStrCodigo($_POST['txtCodigo']);
                    $objMdLitServicoDTO->setStrSigla($_POST['txtSigla']);
                    $objMdLitServicoDTO->setStrDescricao($_POST['txtDescricao']);
                    $objMdLitServicoDTO->setStrSinAtivo('S');

                    $objMdLitServicoRN = new MdLitServicoRN();
                    $objMdLitServicoDTO = $objMdLitServicoRN->cadastrar($objMdLitServicoDTO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_listar' . '&acao_origem=' . $_GET['acao'] .
                            PaginaSEI::getInstance()->montarAncora($objMdLitServicoDTO->getNumIdMdLitServico())));
                } elseif ($_POST['rdoOrigem'] == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO) {

                    $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                    $arrMapeamento = json_decode($_POST['hdnMapeamentoJson']);
                    $objMdLitServicoIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                    $objMdLitServicoIntegracaoDTO->setStrNomeIntegracao($_POST['txtIntegracao']);
                    $objMdLitServicoIntegracaoDTO->setStrOperacaoWsdl($_POST['selOperacao']);
                    $objMdLitServicoIntegracaoDTO->setStrTipoClienteWs($_POST['tipoWs']);
                    $objMdLitServicoIntegracaoDTO->setStrVersaoSoap($_POST['versaoSoap']);
                    $objMdLitServicoIntegracaoDTO->setArrModalidade($_POST['selModalidade']);
                    $objMdLitServicoIntegracaoDTO->setArrAbrangencia($_POST['selAbrangencia']);
                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao('');

                    if (count($arrMapeamento) > 0) {
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

                    $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
                    $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->cadastrar($objMdLitServicoIntegracaoDTO);


                    //responsavel por selecionar os itens que foram salvos
                    $objMdLitServicoDTO = new MdLitServicoDTO();
                    $objMdLitServicoRN = new MdLitServicoRN();
                    $objMdLitServicoDTO->retNumIdMdLitServico();
                    $objMdLitServicoDTO->setNumIdMdLitServicoIntegracao($objMdLitServicoIntegracaoDTO->getNumIdMdLitServicoIntegracao());
                    $arrObjMdLitServicoDTO = $objMdLitServicoRN->listar($objMdLitServicoDTO);

                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_listar' . '&acao_origem=' . $_GET['acao'] .
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

                if ($_POST['rdoOrigem'] == MdLitServicoRN::$STA_ORIGEM_MANUAL) {
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
                } elseif ($_POST['rdoOrigem'] == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO) {
                    $idMdLitServicoIntegracao = $_POST['hdnIdMdLitServicoIntegracao'];
                    $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
                    $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                    $objMdLitServicoIntegracaoDTO->retTodos(true);
                    $objMdLitServicoIntegracaoDTO->setNumIdMdLitServicoIntegracao($idMdLitServicoIntegracao);

                    $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->consultar($objMdLitServicoIntegracaoDTO);

                    $arrMapeamento = json_decode($_POST['hdnMapeamentoJson']);
                    $objMdLitServicoIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                    $objMdLitServicoIntegracaoDTO->setStrNomeIntegracao($_POST['txtIntegracao']);
                    $objMdLitServicoIntegracaoDTO->setStrOperacaoWsdl($_POST['selOperacao']);
                    $objMdLitServicoIntegracaoDTO->setStrTipoClienteWs($_POST['tipoWs']);
                    $objMdLitServicoIntegracaoDTO->setStrVersaoSoap($_POST['versaoSoap']);
                    $objMdLitServicoIntegracaoDTO->setArrModalidade($_POST['selModalidade']);
                    $objMdLitServicoIntegracaoDTO->setArrAbrangencia($_POST['selAbrangencia']);
                    $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao('');

                    if ($arrMapeamento) {
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

                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_servico_listar' . '&acao_origem=' . $_GET['acao'] .
                            PaginaSEI::getInstance()->montarAncora(InfraArray::converterArrInfraDTO($arrObjMdLitServicoDTO, 'IdMdLitServico'))));

                }

            }

            $objMdLitServicoDTO = new MdLitServicoDTO();
            $objMdLitServicoDTO->setNumIdMdLitServico($idMdLitServico);
            $objMdLitServicoDTO->setStrSinAtivo('S');
            $objMdLitServicoDTO->retTodos(true);
            $objMdLitServicoRN = new MdLitServicoRN();
            $objMdLitServicoDTO = $objMdLitServicoRN->consultar($objMdLitServicoDTO);

            if (!$objMdLitServicoDTO) {
                throw new InfraException('Serviço não encontrado!');
            }

            $rdoOrigem = $objMdLitServicoDTO->getStrStaOrigem();

            if ($rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL) {

                //Campos Serviço Manual
                $txtCodigo = $objMdLitServicoDTO->getStrCodigo();
                $txtSigla = $objMdLitServicoDTO->getStrSigla();
                $txtDescricao = $objMdLitServicoDTO->getStrDescricao();

            } elseif (!empty($objMdLitServicoDTO->getNumIdMdLitServicoIntegracao())) {

                $idMdLitServicoIntegracao = $objMdLitServicoDTO->getNumIdMdLitServicoIntegracao();
                $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
                $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                $objMdLitServicoIntegracaoDTO->retTodos(true);
                $objMdLitServicoIntegracaoDTO->setNumIdMdLitServicoIntegracao($idMdLitServicoIntegracao);

                $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->consultar($objMdLitServicoIntegracaoDTO);

                if (!$objMdLitServicoIntegracaoDTO) {
                    throw new InfraException('Serviço por integração não encontrado!');
                }

                $selOperacao     = isset($_POST['hdnMapeamentoJson']) ? $_POST['selOperacao']     : $objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl();
                $txtEnderecoWsdl = isset($_POST['hdnMapeamentoJson']) ? $_POST['txtEnderecoWsdl'] : $objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl();
                $txtIntegracao   = isset($_POST['hdnMapeamentoJson']) ? $_POST['txtIntegracao']   : $objMdLitServicoIntegracaoDTO->getStrNomeIntegracao();
                $tipoWs          = isset($_POST['hdnMapeamentoJson']) ? $_POST['tipoWs']          : $objMdLitServicoIntegracaoDTO->getStrTipoClienteWs();
                $versaoSoap      = isset($_POST['hdnMapeamentoJson']) ? $_POST['versaoSoap']      : $objMdLitServicoIntegracaoDTO->getStrVersaoSoap();
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
            $objMdLitServicoRN = new MdLitServicoRN();
            $objMdLitServicoDTO = $objMdLitServicoRN->consultar($objMdLitServicoDTO);

            if (!$objMdLitServicoDTO) {
                throw new InfraException('Serviço não encontrado!');
            }

            $rdoOrigem = $objMdLitServicoDTO->getStrStaOrigem();

            if ($rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL) {

                //Campos Serviço Manual
                $txtCodigo = $objMdLitServicoDTO->getStrCodigo();
                $txtSigla = $objMdLitServicoDTO->getStrSigla();
                $txtDescricao = $objMdLitServicoDTO->getStrDescricao();


            } elseif (!empty($objMdLitServicoDTO->getNumIdMdLitServicoIntegracao())) {

                $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
                $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                $objMdLitServicoIntegracaoDTO->retTodos(true);
                $objMdLitServicoIntegracaoDTO->setNumIdMdLitServicoIntegracao($objMdLitServicoDTO->getNumIdMdLitServicoIntegracao());

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


        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
            break;
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

if (isset($_POST['hdnMapeamentoJson']) && !empty($_POST['hdnMapeamentoJson'])) {
    $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
    $arrMapeamento = json_decode($_POST['hdnMapeamentoJson']);
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
try {
    if ($objMdLitServicoIntegracaoDTO)
        $strResultadoTabelaServicoIntegracao = MdLitServicoIntegracaoINT::montarTabelaServicoIntegracao($objMdLitServicoIntegracaoDTO);

} catch (Exception $e) {
    $isValidouEnderecoWsdl = false;
    $exception = new InfraException();
    $exception->adicionarValidacao("Não foi possível carregar o web-service: {$objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl()}.
                                   Retorno da requisição SOAP: {$e->getMessage()}");
    PaginaSEI::getInstance()->processarExcecao($exception);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_servico_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmServicoCadastro" method="post" action="<?= $strAcaoForm ?>">
        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <fieldset id="fieldSetOrigem" class="infraFieldset form-control">
                    <legend class="infraLegend">&nbsp;Origem&nbsp;</legend>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group">
                                <label for="rdoIntegracao" name="lblIntegracao" class="radio-label infraLabelRadio">
                                    <input type="radio" name="rdoOrigem" id="rdoIntegracao" class="infraRadio"
                                        value="<?= MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ?>" onchange="changeOrigem();"
                                        <?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ? "checked='checked'" : '' ?> />
                                    Integração
                                </label>
                                <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($strToolTipLblInt, 'Ajuda') ?>
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <img border="0" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                        class="infraImgModulo"/>
                                </a>
                                <br />
                                <label for="rdoManual" name="lblManual" class="radio-label infraLabelRadio">
                                    <input type="radio" name="rdoOrigem" id="rdoManual" class="infraRadio"
                                        value="<?= MdLitServicoRN::$STA_ORIGEM_MANUAL ?>" onchange="changeOrigem();"
                                        <?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL ? "checked='checked'" : '' ?> />
                                    Manual
                                </label>
                                <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($strToolTipLblMan, 'Ajuda') ?>
                                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <img border="0" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                        class="infraImgModulo"/>
                                </a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="mt-1" id="divIntegracao"
             style="display:<?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ? 'block' : 'none' ?>">
            <div class="row">
                <?php
                    //Get Integração
                    require_once 'md_lit_servico_cadastro_integracao.php';

                    PaginaSEI::getInstance()->montarAreaTabela($strResultadoTabelaServicoIntegracao['strResultado'], $strResultadoTabelaServicoIntegracao['numRegistros']);
                ?>
            </div>
        </div>
        <div class="mt-1" id="divManual"
             style="display:<?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL ? 'block' : 'none' ?>">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblCodigo" for="txtCodigo">Código:</label>
                        <input type="text" id="txtCodigo" name="txtCodigo" class="infraText form-control" maxlength="10" value="<?= $txtCodigo ?>"
                            onkeypress="return infraMascaraTexto(this,event,10);"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblSigla" for="txtSigla">Sigla:</label>
                        <input type="text" id="txtSigla" name="txtSigla" maxlength="10" value="<?= $txtSigla ?>" class="infraText form-control"
                            onkeypress="return infraMascaraTexto(this,event,10);"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblDescricao" for="txtDescricao">Descrição:</label>
                        <input type="text" id="txtDescricao" name="txtDescricao" maxlength="100" class="infraText form-control"
                            value="<?= $txtDescricao ?>"
                            onkeypress="return infraMascaraTexto(this,event,100);"/>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="hdnSalvarServico" name="hdnSalvarServico" value=""/>
        <input type="hidden" id="hdnIdMdLitServico" name="hdnIdMdLitServico" value="<?= $idMdLitServico ?>"/>
        <input type="hidden" id="hdnIdMdLitServico" name="hdnIdMdLitServicoIntegracao" value="<?= $idMdLitServicoIntegracao ?>"/>
        <input type="hidden" id="hdnValidouEnderecoWsdl" value="<?= $isValidouEnderecoWsdl ? 'S' : 'N' ?>">
    </form>

<?php
require_once("md_lit_servico_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
