<?php

/**
 * ANATEL
 *
 * 18/04/2018 - criado por ellyson.silva
 *
 * Versão do Gerador de Código: 1.41.0
 */
try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $strUrlCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_lancamento_listar&id_md_lit_situacao=' . $_GET['id_md_lit_situacao']
        . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_servico']));


    $strAcaoForm = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_lit_situacao=' . $_GET['id_md_lit_situacao']);
    $strLinkAjaxValidarWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_servico_busca_operacao_wsdl');


    $arrComandos = array();


    $strToolTipLblInt = 'Caso exista sistema próprio de cadastro e controle da lista de Situações do Lançamento de Crédito pela instituição. Somente permitirá finalizar o cadastro após o Mapeamento dos campos.';
    $strToolTipLblMan = 'Caso não exista sistema próprio de cadastro e controle da lista de Situações do Lançamento de Crédito pela instituição. O código da situação deve ser único, mesmo entre as Situações cadastradas manualmente ou por integração.';


    $idMdLitSituacao = $_GET['id_md_lit_situacao'];
    $rdoOrigem = '';
    $txtCodigo = '';
    $txtDescricao = '';
    $idMdLitSituacaoIntegracao = '';

    switch ($_GET['acao']) {

        case 'md_lit_situacao_lancamento_cadastrar':
            $strTitulo = 'Cadastro de Situações do Lançamento de Crédito';

            $arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" onclick="salvar()" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

            $rdoOrigem = $_POST['rdoOrigem'];
            $txtCodigo = $_POST['txtCodigo'];
            $txtDescricao = $_POST['txtDescricao'];
            $rdoSinCancelamento = $_POST['rdoSinCancelamento'] ? $_POST['rdoSinCancelamento'] : 'N';
            $selCor = $_POST['selCor'];
            // valor do input do integracao
            $selOperacao = $_POST['selOperacao'];
            $txtEnderecoWsdl = $_POST['txtEnderecoWsdl'];
            $txtIntegracao = $_POST['txtIntegracao'];
            $txtTipoWs = $_POST['tipoWs'];
            $txtVersaoSoap = $_POST['versaoSoap'];

            if (!empty($rdoSinCancelamento))
                $strRadioCancelamento = MdLitSituacaoLancamentoINT::montarRadioCancelamento($rdoSinCancelamento);

            if (isset($_POST['hdnSalvarSituacao'])) {

                if ($_POST['rdoOrigem'] == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL) {
                    $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                    $objMdLitSituacaoLancamentoDTO->setStrStaOrigem(MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL);
                    $objMdLitSituacaoLancamentoDTO->setNumCodigo($_POST['txtCodigo']);
                    $objMdLitSituacaoLancamentoDTO->setStrNome($_POST['txtDescricao']);
                    $objMdLitSituacaoLancamentoDTO->setStrSinAtivo('S');
                    $objMdLitSituacaoLancamentoDTO->setStrSinAtivoIntegracao('S');
                    $objMdLitSituacaoLancamentoDTO->setStrCorSituacao($_POST['selCor']);
                    $objMdLitSituacaoLancamentoDTO->setStrSinCancelamento($_POST['rdoSinCancelamento']);

                    $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
                    $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->cadastrar($objMdLitSituacaoLancamentoDTO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_lancamento_listar' . '&acao_origem=' . $_GET['acao'] .
                            PaginaSEI::getInstance()->montarAncora($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento())));
                } elseif ($_POST['rdoOrigem'] == MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO) {

                    $objMdLitSituacaoLancamIntDTO = new MdLitSituacaoLancamIntDTO();
                    $arrMapeamento = json_decode($_POST['hdnMapeamentoJson']);
                    $objMdLitSituacaoLancamIntDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                    $objMdLitSituacaoLancamIntDTO->setStrNomeIntegracao($_POST['txtIntegracao']);
                    $objMdLitSituacaoLancamIntDTO->setStrOperacaoWsdl($_POST['selOperacao']);
                    $objMdLitSituacaoLancamIntDTO->setNumSituacaoCancelamento($_POST['rdoSinCancelamentoIntegracao']);
                    $objMdLitSituacaoLancamIntDTO->setArrCoresSelecionados($_POST['selCores']);
                    $objMdLitSituacaoLancamIntDTO->setStrTipoClienteWs($_POST['tipoWs']);
                    $objMdLitSituacaoLancamIntDTO->setStrVersaoSoap($_POST['versaoSoap']);

                    if (count($arrMapeamento) > 0) {
                        foreach ($arrMapeamento as $mapeamento) {

                            switch ($mapeamento->value) {
                                case MdLitSituacaoLancamIntRN::$CODIGO:
                                    $objMdLitSituacaoLancamIntDTO->setStrMapeamentoCodigo($mapeamento->name);
                                    break;
                                case MdLitSituacaoLancamIntRN::$DESCRICAO:
                                    $objMdLitSituacaoLancamIntDTO->setStrMapeamentoDescricao($mapeamento->name);
                                    break;
                            }
                            if ($mapeamento->chaveUnica) {
                                $objMdLitSituacaoLancamIntDTO->setStrChaveUnica($mapeamento->name);
                            }
                        }
                    }

                    $objMdLitSituacaoLancamIntRN = new MdLitSituacaoLancamIntRN();
                    $objMdLitSituacaoLancamIntDTO = $objMdLitSituacaoLancamIntRN->cadastrar($objMdLitSituacaoLancamIntDTO);

                    //responsavel por selecionar os itens que foram salvos
                    $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                    $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
                    $objMdLitSituacaoLancamentoDTO->retNumIdMdLitSituacaoLancamento();
                    $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancInt($objMdLitSituacaoLancamIntDTO->getNumIdMdLitSituacaoLancamInt());
                    $arrObjMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->listar($objMdLitSituacaoLancamentoDTO);

                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_lancamento_listar' . '&acao_origem=' . $_GET['acao'] .
                            PaginaSEI::getInstance()->montarAncora(InfraArray::converterArrInfraDTO($arrObjMdLitSituacaoLancamentoDTO, 'IdMdLitSituacaoLancamento'))));
                }
                die;

            }
            break;

        case 'md_lit_situacao_lancamento_alterar':
            $strTitulo = 'Alterar Situações do Lançamento de Crédito';

            $arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" onclick="salvar()" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

            if (isset($_POST['hdnSalvarSituacao'])) {

                if ($_POST['rdoOrigem'] == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL) {
                    $rdoOrigem = MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL;
                    $txtCodigo = $_POST['txtCodigo'];
                    $txtDescricao = $_POST['txtDescricao'];
                    $selCor = $_POST['selCor'];
                    $rdoSinCancelamento = $_POST['rdoSinCancelamento'];

                    $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                    $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($_POST['hdnIdMdLitSituacao']);
                    $objMdLitSituacaoLancamentoDTO->setStrStaOrigem(MdLitServicoRN::$STA_ORIGEM_MANUAL);
                    $objMdLitSituacaoLancamentoDTO->setNumCodigo($_POST['txtCodigo']);
                    $objMdLitSituacaoLancamentoDTO->setStrNome($_POST['txtDescricao']);
                    $objMdLitSituacaoLancamentoDTO->setStrSinAtivo('S');
                    $objMdLitSituacaoLancamentoDTO->setStrSinAtivoIntegracao('S');
                    $objMdLitSituacaoLancamentoDTO->setStrSinCancelamento($_POST['rdoSinCancelamento']);
                    $objMdLitSituacaoLancamentoDTO->setStrCorSituacao($_POST['selCor']);

                    $obMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
                    $obMdLitSituacaoLancamentoRN->alterar($objMdLitSituacaoLancamentoDTO);
                    header('Location: ' . $strUrlCancelar);
                    die;
                } elseif ($_POST['rdoOrigem'] == MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO) {
                    $idMdLitSituacaoIntegracao = $_POST['hdnIdMdLitSituacaoIntegracao'];
                    $objMdLitSituacaoLancamIntRN = new MdLitSituacaoLancamIntRN();
                    $objMdLitSituacaoLancamIntDTO = new MdLitSituacaoLancamIntDTO();
                    $objMdLitSituacaoLancamIntDTO->retTodos(true);
                    $objMdLitSituacaoLancamIntDTO->setNumIdMdLitSituacaoLancamInt($idMdLitSituacaoIntegracao);

                    $objMdLitSituacaoLancamIntDTO = $objMdLitSituacaoLancamIntRN->consultar($objMdLitSituacaoLancamIntDTO);

                    $arrMapeamento = json_decode($_POST['hdnMapeamentoJson']);
                    $objMdLitSituacaoLancamIntDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                    $objMdLitSituacaoLancamIntDTO->setStrNomeIntegracao($_POST['txtIntegracao']);
                    $objMdLitSituacaoLancamIntDTO->setStrOperacaoWsdl($_POST['selOperacao']);
                    $objMdLitSituacaoLancamIntDTO->setNumSituacaoCancelamento($_POST['rdoSinCancelamentoIntegracao']);
                    $objMdLitSituacaoLancamIntDTO->setArrCoresSelecionados($_POST['selCores']);
                    $objMdLitSituacaoLancamIntDTO->setStrTipoClienteWs($_POST['tipoWs']);
                    $objMdLitSituacaoLancamIntDTO->setStrVersaoSoap($_POST['versaoSoap']);
                    $objMdLitSituacaoLancamIntDTO->setArrSinUtilizarAgendamento($_POST['sinUtilizarAgendamento']);

                    if ($arrMapeamento) {
                        foreach ($arrMapeamento as $mapeamento) {

                            switch ($mapeamento->value) {
                                case MdLitSituacaoLancamIntRN::$CODIGO:
                                    $objMdLitSituacaoLancamIntDTO->setStrMapeamentoCodigo($mapeamento->name);
                                    break;
                                case MdLitSituacaoLancamIntRN::$DESCRICAO:
                                    $objMdLitSituacaoLancamIntDTO->setStrMapeamentoDescricao($mapeamento->name);
                                    break;
                            }
                            if ($mapeamento->chaveUnica) {
                                $objMdLitSituacaoLancamIntDTO->setStrChaveUnica($mapeamento->name);
                            }
                        }
                    }

                    $objMdLitSituacaoLancamIntDTO = $objMdLitSituacaoLancamIntRN->alterar($objMdLitSituacaoLancamIntDTO);

                    //responsavel por selecionar os itens que foram salvos
                    $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                    $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
                    $objMdLitSituacaoLancamentoDTO->retNumIdMdLitSituacaoLancamento();
                    $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancInt($idMdLitSituacaoIntegracao);
                    $arrObjMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->listar($objMdLitSituacaoLancamentoDTO);

                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_lancamento_listar' . '&acao_origem=' . $_GET['acao'] .
                            PaginaSEI::getInstance()->montarAncora(InfraArray::converterArrInfraDTO($arrObjMdLitSituacaoLancamentoDTO, 'IdMdLitSituacaoLancamento'))));

                }

            }

            $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
            $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($idMdLitSituacao);
            $objMdLitSituacaoLancamentoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoLancamentoDTO->retTodos(true);
            $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
            $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultar($objMdLitSituacaoLancamentoDTO);

            if (!$objMdLitSituacaoLancamentoDTO) {
                throw new InfraException('Situação não encontrado!');
            }

            $rdoOrigem = $objMdLitSituacaoLancamentoDTO->getStrStaOrigem();

            if ($rdoOrigem == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL) {

                //Campos Situação Manual
                $txtCodigo = $objMdLitSituacaoLancamentoDTO->getNumCodigo();
                $txtDescricao = $objMdLitSituacaoLancamentoDTO->getStrNome();
                $selCor = $objMdLitSituacaoLancamentoDTO->getStrCorSituacao();
                $rdoSinCancelamento = $objMdLitSituacaoLancamentoDTO->getStrSinCancelamento();

            } elseif (!empty($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancInt())) {

                $idMdLitSituacaoIntegracao = $objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancInt();
                $objMdLitSituacaoLancamIntRN = new MdLitSituacaoLancamIntRN();
                $objMdLitSituacaoLancamIntDTO = new MdLitSituacaoLancamIntDTO();
                $objMdLitSituacaoLancamIntDTO->retTodos(true);
                $objMdLitSituacaoLancamIntDTO->setNumIdMdLitSituacaoLancamInt($idMdLitSituacaoIntegracao);

                $objMdLitSituacaoLancamIntDTO = $objMdLitSituacaoLancamIntRN->consultar($objMdLitSituacaoLancamIntDTO);

                if (!$objMdLitSituacaoLancamIntDTO) {
                    throw new InfraException('Situação do lançamento por integração não encontrado!');
                }

                $selOperacao = $objMdLitSituacaoLancamIntDTO->getStrOperacaoWsdl();
                $txtEnderecoWsdl = $objMdLitSituacaoLancamIntDTO->getStrEnderecoWsdl();
                $txtIntegracao = $objMdLitSituacaoLancamIntDTO->getStrNomeIntegracao();
                $txtTipoWs = $objMdLitSituacaoLancamIntDTO->getStrTipoClienteWs();
                $txtVersaoSoap = $objMdLitSituacaoLancamIntDTO->getStrVersaoSoap();
            }

            break;


        case 'md_lit_situacao_lancamento_consultar':
            $strTitulo = 'Consultar Situações do Lançamento de Crédito';

            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

            $idMdLitSituacao = $_GET['id_md_lit_situacao'];

            $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
            $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($idMdLitSituacao);
            $objMdLitSituacaoLancamentoDTO->retTodos(true);
            $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
            $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultar($objMdLitSituacaoLancamentoDTO);

            if (!$objMdLitSituacaoLancamentoDTO) {
                throw new InfraException('Situação não encontrado!');
            }

            $rdoOrigem = $objMdLitSituacaoLancamentoDTO->getStrStaOrigem();

            if ($rdoOrigem == MdLitSituacaoLancamentoRN::$STA_ORIGEM_MANUAL) {

                //Campos Serviço Manual
                $txtCodigo = $objMdLitSituacaoLancamentoDTO->getNumCodigo();
                $txtDescricao = $objMdLitSituacaoLancamentoDTO->getStrNome();
                $selCor = $objMdLitSituacaoLancamentoDTO->getStrCorSituacao();
                $rdoSinCancelamento = $objMdLitSituacaoLancamentoDTO->getStrSinCancelamento();

            } elseif (!empty($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancInt())) {

                $idMdLitSituacaoIntegracao = $objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancInt();
                $objMdLitSituacaoLancamIntRN = new MdLitSituacaoLancamIntRN();
                $objMdLitSituacaoLancamIntDTO = new MdLitSituacaoLancamIntDTO();
                $objMdLitSituacaoLancamIntDTO->retTodos(true);
                $objMdLitSituacaoLancamIntDTO->setNumIdMdLitSituacaoLancamInt($idMdLitSituacaoIntegracao);

                $objMdLitSituacaoLancamIntDTO = $objMdLitSituacaoLancamIntRN->consultar($objMdLitSituacaoLancamIntDTO);

                if (!$objMdLitSituacaoLancamIntDTO) {
                    throw new InfraException('Situação do lançamento por integração não encontrado!');
                }

                $selOperacao = $objMdLitSituacaoLancamIntDTO->getStrOperacaoWsdl();
                $txtEnderecoWsdl = $objMdLitSituacaoLancamIntDTO->getStrEnderecoWsdl();
                $txtIntegracao = $objMdLitSituacaoLancamIntDTO->getStrNomeIntegracao();
                $txtTipoWs = $objMdLitSituacaoLancamIntDTO->getStrTipoClienteWs();
                $txtVersaoSoap = $objMdLitSituacaoLancamIntDTO->getStrVersaoSoap();
            }

            break;


        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
            break;
    }


    $strRadioCancelamento = MdLitSituacaoLancamentoINT::montarRadioCancelamento($rdoSinCancelamento);


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
    $strRadioCancelamento = MdLitSituacaoLancamentoINT::montarRadioCancelamento($rdoSinCancelamento);
}


if (isset($_POST['hdnMapeamentoJson']) && !empty($_POST['hdnMapeamentoJson'])) {
    $objMdLitSituacaoLancamIntDTO = new MdLitSituacaoLancamIntDTO();
    $arrMapeamento = json_decode($_POST['hdnMapeamentoJson']);
    $objMdLitSituacaoLancamIntDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
    $objMdLitSituacaoLancamIntDTO->setStrOperacaoWsdl($_POST['selOperacao']);
    $objMdLitSituacaoLancamIntDTO->setStrTipoClienteWs($_POST['tipoWs']);
    $objMdLitSituacaoLancamIntDTO->setStrVersaoSoap($_POST['versaoSoap']);

    $txtIntegracao = $_POST['txtIntegracao'];
    $selOperacao = $_POST['selOperacao'];
    $txtEnderecoWsdl = $_POST['txtEnderecoWsdl'];
    $txtTipoWs = $_POST['tipoWs'];
    $txtVersaoSoap = $_POST['versaoSoap'];

    foreach ($arrMapeamento as $mapeamento) {

        switch ($mapeamento->value) {
            case MdLitSituacaoLancamIntRN::$CODIGO:
                $objMdLitSituacaoLancamIntDTO->setStrMapeamentoCodigo($mapeamento->name);
                break;
            case MdLitSituacaoLancamIntRN::$DESCRICAO:
                $objMdLitSituacaoLancamIntDTO->setStrMapeamentoDescricao($mapeamento->name);
                break;
        }
        if ($mapeamento->chaveUnica) {
            $objMdLitSituacaoLancamIntDTO->setStrChaveUnica($mapeamento->name);
        }
    }
}
try {
    if ($objMdLitSituacaoLancamIntDTO)
        $strResultadoTabelaSituacaoLancamentoIntegracao = MdLitSituacaoLancamIntINT::montarTabelaSituacaoLancamentoIntegracao($objMdLitSituacaoLancamIntDTO);

} catch (Exception $e) {
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once('md_lit_situacao_lancamento_cadastro_css.php');
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmSituacaoCadastro" method="post" action="<?= $strAcaoForm ?>">
        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <fieldset class="infraFieldset form-control">
                    <legend class="infraLegend">&nbsp;Origem&nbsp;</legend>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group">
                                <label for="rdoIntegracao" name="lblIntegracao" class="radio-label infraLabelRadio">
                                    <input type="radio" name="rdoOrigem" id="rdoIntegracao" class="infraRadio"
                                            value="<?= MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ?>"
                                            onchange="changeOrigem();"
                                        <?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ? "checked='checked'" : '' ?>>
                                    Integração

                                    <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($strToolTipLblInt, 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImgModulo"/>
                                    </a>
                                </label>
                                <br/>
                                <label for="rdoManual" name="lblManual" class="radio-label infraLabelRadio">
                                    <input type="radio" name="rdoOrigem" id="rdoManual" class="infraRadio"
                                            value="<?= MdLitServicoRN::$STA_ORIGEM_MANUAL ?>"
                                            onchange="changeOrigem();"
                                        <?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL ? "checked='checked'" : '' ?>>
                                    Manual
                                    <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($strToolTipLblMan, 'Ajuda') ?>
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                        <img border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImgModulo"/>
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="" id="divIntegracao"
                style="display:<?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_INTEGRACAO ? 'block' : 'none' ?>">
            <?php $strToolTipEnd = 'A seleção da Operação para poder Mapear os campos de Origem e Destino somente é possível depois que validar o Endereço WSDL acima.' ?>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblIntegracao" for="txtIntegracao">Nome da
                            Integração:</label>
                        <input type="text" id="txtIntegracao" class="infraText form-control" name="txtIntegracao"
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
                            <input type="radio" class="infraRadio" name="tipoWs"
                                    value="SOAP" <?= $txtTipoWs != 'REST' ? 'checked' : ''; ?> >
                            SOAP
                        </label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5">
                    <div class="form-group">
                        <label id="lbltipoWs" for="tipoWs" class="infraLabelObrigatorio">
                            Versão SOAP:
                        </label>
                        <select id="versaoSoap" name="versaoSoap" class="infraSelect form-control">
                            <option value="1.2" <?= PaginaSEI::tratarHTML($txtVersaoSoap) == '1.2' ? 'selected' : ''; ?>>
                                1.2
                            </option>
                            <option value="1.1" <?= PaginaSEI::tratarHTML($txtVersaoSoap) == '1.1' ? 'selected' : ''; ?>>
                                1.1
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblEndereco" for="txtEndereco">Endereço
                            WSDL:</label>
                        <div class="input-group mb-3">
                            <input type="text" id="txtEnderecoWsdl" name="txtEnderecoWsdl"
                                    onchange="apagarOperacao()"
                                    class="infraText form-control"
                                    value="<?= $txtEnderecoWsdl ?>"/>
                            <button class="infraButton" name="btnValidar" type="button" id="btnValidar"
                                    onclick="validarWsdl()">Validar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="gridOperacao">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <label class="infraLabelObrigatorio" id="lbloperacao" for="txtOperacao">Operação:
                        <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($strToolTipEnd, 'Ajuda') ?>
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <img border="0"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                    class="infraImgModulo"/>
                        </a>
                    </label>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <select id="selOperacao" name="selOperacao" class="infraSelect form-control"
                                    onchange="apagarMapear()"/>
                            </select>
                            <button class="infraButton" type="button" name="btnMapeamento" id="btnMapeamento"
                                    onclick="abrirJanelaMapeamento();">Mapeamento
                            </button>
                        </div>
                        <input type="hidden" id="hdnMapeamentoJson" name="hdnMapeamentoJson"
                                value='<?= $_POST['hdnMapeamentoJson'] ?>'/>
                    </div>
                </div>
            </div>
            <?php
            //Get Integração
            PaginaSEI::getInstance()->montarAreaTabela($strResultadoTabelaSituacaoLancamentoIntegracao['strResultado'], $strResultadoTabelaSituacaoLancamentoIntegracao['numRegistros']);
            ?>
        </div>
        <div class="" id="divManual"
                style="display:<?= $rdoOrigem == MdLitServicoRN::$STA_ORIGEM_MANUAL ? 'block' : 'none' ?>">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblCodigo" for="txtCodigo">Código:</label>
                        <input type="text" id="txtCodigo" name="txtCodigo" class="infraText form-control"
                                maxlength="10"
                                value="<?= $txtCodigo ?>"
                                onkeypress="return infraMascaraNumero(this, event, 10)"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblDescricao" for="txtDescricao">Descrição:</label>
                        <input type="text" id="txtDescricao" name="txtDescricao" maxlength="100"
                                class="infraText form-control"
                                value="<?= PaginaSEI::tratarHTML($txtDescricao); ?>"
                                onkeypress="return infraMascaraTexto(this,event,100);"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblDescricao" for="txtDescricao">Cor para
                            Apresentação
                            da Situação:</label>
                        <select id="selCor" name="selCor" class="infraSelect form-control">
                            <?= MdLitSituacaoLancamentoINT::montarSelectCor($selCor) ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="form-group">
                        <label class="infraLabelObrigatorio" id="lblDescricao" for="txtDescricao">Situação de
                            Cancelamento?</label>
                        <?= $strRadioCancelamento ?>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="hdnSalvarSituacao" name="hdnSalvarSituacao" value=""/>
        <input type="hidden" id="hdnIdMdLitSituacao" name="hdnIdMdLitSituacao" value="<?= $idMdLitSituacao ?>"/>
        <input type="hidden" id="hdnIdMdLitSituacaoIntegracao" name="hdnIdMdLitSituacaoIntegracao"
               value="<?= $idMdLitSituacaoIntegracao ?>"/>
    </form>
<?php
require_once('md_lit_situacao_lancamento_cadastro_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
