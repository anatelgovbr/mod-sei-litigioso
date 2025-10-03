<?
/**
 * ANATEL
 *
 * 23/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_lit_especie_decisao_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objEspecieDecisaoLitigiosoDTO = new MdLitEspecieDecisaoDTO();

    $strDesabilitar = '';

    $arrComandos = array();
    $strLinkObrigacaoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_selecionar&tipo_selecao=2&id_object=objLupaObrigacao');
    $strLinkAjaxObrigacao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_obrigacao_auto_completar');
    $strLinkValidarCadastroIntegracao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_cadastro_integracao_multa');
    $strLinkValidarAlteracaoIntegracao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_alteracao_integracao_multa');

    switch ($_GET['acao']) {
        case 'md_lit_especie_decisao_cadastrar':

            $strTitulo = 'Nova Espécie de Decisão';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEspecieDecisaoLitigioso" id="sbmCadastrarEspecieDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso(null);
            $objEspecieDecisaoLitigiosoDTO->setStrNome($_POST['txtNome']);
            $objEspecieDecisaoLitigiosoDTO->setStrSinGestaoMulta($_POST['gestaoMulta'] != null ? 'S' : 'N');
            $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoPrazo($_POST['indPrazo'] != null ? 'S' : 'N');
            $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoObrigacoes($_POST['IndObr'] != null ? 'S' : 'N');
            $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoValor($_POST['IndRessarcimento'] != null ? 'S' : 'N');

            if ($_POST['gestaoMulta'] == 'S') {
                $objEspecieDecisaoLitigiosoDTO->setStrStaTipoIndicacaoMulta($_POST['tipoMulta']);
                $MdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
                $responseValicacao = $MdLitEspecieDecisaoRN->validarIntegracaoMulta($_POST);
                if ($responseValicacao->success == false) {
                    $incompletas = '';
                    foreach ($responseValicacao->integracaoIncompleta as $integracaoIncompleta) {
                        $incompletas .= "{$integracaoIncompleta->get('Nome')}\n";
                    }
                    $objInfraException = new InfraException();
                    $objInfraException->lancarValidacao("O Mapeamento das Integrações com o Sistema de Arrecadação não" .
                        "foram realizados. Antes de definir que a Gestão de Multa deve ocorrer por meio de Integração, acesse Administração >> Controle de" .
                        "Processo Litigioso >> Mapeamento das Integrações e insira os Mapeamentos de Integrações das funcionalidades abaixo: \n\n" .
                        $incompletas
                    );
                }
            } else {
                $objEspecieDecisaoLitigiosoDTO->setStrStaTipoIndicacaoMulta(null);
            }


            if (isset($_POST['sbmCadastrarEspecieDecisaoLitigioso'])) {
                try {

                    //Set Obrigacoes
                    if ($objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                        $arrObjEspecieDecisaoObrigacaoDTO = array();
                        $arrObrigacoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnObrigacao']);

                        for ($x = 0; $x < count($arrObrigacoes); $x++) {
                            $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                            $objRelEspecieDecisaoObrigacaoDTO->setNumIdObrigacaoLitigioso($arrObrigacoes[$x]);
                            array_push($arrObjEspecieDecisaoObrigacaoDTO, $objRelEspecieDecisaoObrigacaoDTO);
                        }

                        $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoObrigacaoDTO);
                    } else {
                        $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO(array());
                    }
                    //Fim Obrigações

                    $objEspecieDecisaoLitigiosoRN = new MdLitEspecieDecisaoRN();
                    $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->cadastrar($objEspecieDecisaoLitigiosoDTO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_especie_decisao_litigioso=' . $objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso() . PaginaSEI::getInstance()->montarAncora($objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_especie_decisao_alterar':
            $strTitulo = 'Alterar Espécie de Decisão';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarEspecieDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            $strDesabilitar = 'disabled="disabled"';

            $mdLitRelTipoControleRN = new MdLitRelTipoControleTipoDecisaoRN();
            $mdLitRelTipoControleDto = new MdLitRelTipoControleTipoDecisaoDTO();
            $mdLitRelTipoControleDto->ret('IdMdLitEspecieDecisao');
            $mdLitRelTipoControleDto->set('IdMdLitEspecieDecisao', $_GET['id_especie_decisao_litigioso']);

            //consulta se a especie ja esta relacionada com o tipo de controle litigioso
            $relTipoControleResult = $mdLitRelTipoControleRN->listar($mdLitRelTipoControleDto);

            $MdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();

            if (isset($_GET['id_especie_decisao_litigioso'])) {
                $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso($_GET['id_especie_decisao_litigioso']);

                $objEspecieDecisaoLitigiosoDTO->retTodos();
                $objEspecieDecisaoLitigiosoRN = new MdLitEspecieDecisaoRN();
                $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->consultar($objEspecieDecisaoLitigiosoDTO);

                if ($objEspecieDecisaoLitigiosoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

                $arrData = [
                    'hdnIdEspecieDecisaoLitigioso' => $objEspecieDecisaoLitigiosoDTO->get('IdEspecieLitigioso'),
                    'tipoMulta' => $objEspecieDecisaoLitigiosoDTO->get('StaTipoIndicacaoMulta')
                ];

                //verifica se existe decisao cadastrada com o tipo de multa salvo para bloquear os campos
                $isExisteDecisaoCasdatrada = $MdLitEspecieDecisaoRN->existeDecisaoCadastradaParaTipoMulta($arrData);

                if ($objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                    $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                    $objRelEspecieDecisaoObrigacaoDTO->retTodos();
                    $objRelEspecieDecisaoObrigacaoDTO->retStrNomeObrigacao();
                    $objRelEspecieDecisaoObrigacaoDTO->setNumIdEspecieDecisaoLitigioso($_GET['id_especie_decisao_litigioso']);

                    PaginaSEI::getInstance()->prepararOrdenacao($objRelEspecieDecisaoObrigacaoDTO, 'NomeObrigacao', InfraDTO::$TIPO_ORDENACAO_ASC);


                    $objRelEspecieDecisaoObrigacaoRN = new MdLitRelEspecieDecisaoObrigacaoRN();
                    $arrObrigacoes = $objRelEspecieDecisaoObrigacaoRN->listar($objRelEspecieDecisaoObrigacaoDTO);

                    $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObrigacoes);

                    $strItensSelObrigacoes = "";
                    for ($x = 0; $x < count($arrObrigacoes); $x++) {
                        $strItensSelObrigacoes .= "<option value='" . $arrObrigacoes[$x]->getNumIdObrigacaoLitigioso() . "'>" . $arrObrigacoes[$x]->getStrNomeObrigacao() . "</option>";
                    }
                } else {
                    $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO(array());
                }
            } else {
                $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso($_POST['hdnIdEspecieDecisaoLitigioso']);
                $objEspecieDecisaoLitigiosoDTO->setStrNome($_POST['txtNome']);
                $objEspecieDecisaoLitigiosoDTO->setStrSinGestaoMulta($_POST['gestaoMulta'] != null ? 'S' : 'N');
                $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoPrazo($_POST['indPrazo'] != null ? 'S' : 'N');
                $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoObrigacoes($_POST['IndObr'] != null ? 'S' : 'N');
                $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoValor($_POST['IndRessarcimento'] != null ? 'S' : 'N');

                //se houver gestao de multa
                if ($_POST['gestaoMulta'] == 'S') {

                    //aplica a gestão de acordo com o informado
                    $objEspecieDecisaoLitigiosoDTO->setStrStaTipoIndicacaoMulta($_POST['tipoMulta'] == 1
                        ? MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO
                        : MdLitEspecieDecisaoDTO::$TIPO_MULTA_INDICACAO_VALOR
                    );

                    $responseValicacao = $MdLitEspecieDecisaoRN->validarIntegracaoMulta($_POST);
                    if ($responseValicacao->success == false) {
                        $objInfraException = new InfraException();
                        $incompletas = '';
                        foreach ($responseValicacao->integracaoIncompleta as $integracaoIncompleta) {
                            $incompletas .= "{$integracaoIncompleta->get('Nome')}\n";
                        }

                        $objInfraException->lancarValidacao("O Mapeamento das Integrações com o Sistema de Arrecadação não" .
                            "foram realizados. Antes de definir que a Gestão de Multa deve ocorrer por meio de Integração, acesse Administração >> Controle de" .
                            "Processo Litigioso >> Mapeamento das Integrações e insira os Mapeamentos de Integrações das funcionalidades abaixo: \n\n" .
                            $incompletas
                        );
                    }

                    $arrData = [
                        'hdnIdEspecieDecisaoLitigioso' => $_POST['hdnIdEspecieDecisaoLitigioso'],
                        //inverte para verificar se havia alguma decisão cadastrado com o tipo que foi mudado
                        'tipoMulta' => $data['tipoMulta'] == MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO
                            ? MdLitEspecieDecisaoDTO::$TIPO_MULTA_INDICACAO_VALOR
                            : MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO
                    ];

                    //validação para alteraçõ do tipo de multa
                    $MdLitEspecieDecisaoDTO = $MdLitEspecieDecisaoRN->getEspecieDecisoesById(['arrEspeciesId' => [$arrData['hdnIdEspecieDecisaoLitigioso']]]);
                    $tipoMulta = $MdLitEspecieDecisaoDTO[0]->get('StaTipoIndicacaoMulta');
                    if ($tipoMulta != $arrData['tipoMulta'] && $MdLitEspecieDecisaoRN->existeDecisaoCadastradaParaTipoMulta($arrData)) {
                        $objInfraException = new InfraException();
                        $objInfraException->lancarValidacao("Não é possivel modificar o tipo de indicação de " .
                            "multa pois ja existem decisões cadastradas com o tipo anterior. \n" .
                            "Caso esta mudança seja essencial desative esta 'Espécie de Decisão', crie uma nova e vincule ao processo");
                    }

                } else {
                    $objEspecieDecisaoLitigiosoDTO->setStrStaTipoIndicacaoMulta(null);
                }


                //Set Obrigacoes
                if ($objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                    $arrObjEspecieDecisaoObrigacaoDTO = array();
                    $arrObrigacoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnObrigacao']);

                    for ($x = 0; $x < count($arrObrigacoes); $x++) {
                        $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                        $objRelEspecieDecisaoObrigacaoDTO->setNumIdObrigacaoLitigioso($arrObrigacoes[$x]);
                        array_push($arrObjEspecieDecisaoObrigacaoDTO, $objRelEspecieDecisaoObrigacaoDTO);
                    }

                    $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoObrigacaoDTO);
                } else {
                    $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO(array());
                }
            }

            if (isset($_POST['sbmAlterarEspecieDecisaoLitigioso'])) {
                try {
                    $objEspecieDecisaoLitigiosoRN = new MdLitEspecieDecisaoRN();

                    $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->alterar($objEspecieDecisaoLitigiosoDTO);
                    //
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_especie_decisao_consultar':
            $strTitulo = 'Consultar Espécie de Decisão';
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_especie_decisao_litigioso']))) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            if (isset($_GET['id_especie_decisao_litigioso'])) {
                $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso($_GET['id_especie_decisao_litigioso']);
                $objEspecieDecisaoLitigiosoDTO->retTodos();
                $objEspecieDecisaoLitigiosoRN = new MdLitEspecieDecisaoRN();
                $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->consultar($objEspecieDecisaoLitigiosoDTO);

                if ($objEspecieDecisaoLitigiosoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

                $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                $objRelEspecieDecisaoObrigacaoDTO->retTodos();
                $objRelEspecieDecisaoObrigacaoDTO->retStrNomeObrigacao();
                $objRelEspecieDecisaoObrigacaoDTO->setNumIdEspecieDecisaoLitigioso($_GET['id_especie_decisao_litigioso']);

                PaginaSEI::getInstance()->prepararOrdenacao($objRelEspecieDecisaoObrigacaoDTO, 'NomeObrigacao', InfraDTO::$TIPO_ORDENACAO_ASC);

                $objRelEspecieDecisaoObrigacaoRN = new MdLitRelEspecieDecisaoObrigacaoRN();
                $arrObrigacoes = $objRelEspecieDecisaoObrigacaoRN->listar($objRelEspecieDecisaoObrigacaoDTO);

                $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObrigacoes);

                $strItensSelObrigacoes = "";
                for ($x = 0; $x < count($arrObrigacoes); $x++) {
                    $strItensSelObrigacoes .= "<option value='" . $arrObrigacoes[$x]->getNumIdObrigacaoLitigioso() . "'>" . $arrObrigacoes[$x]->getStrNomeObrigacao() . "</option>";
                }

            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
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
require_once("md_lit_especie_decisao_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmEspécie de DecisãoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('40em');
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Espécie de
                        Decisão:</label>
                    <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($objEspecieDecisaoLitigiosoDTO->getStrNome()); ?>"
                            onkeypress="return infraMascaraTexto(this,event, 50);" maxlength="50"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <fieldset id="fieldsetResultadoDecisao" class="infraFieldset form-control">
                    <legend class="infraLegend">&nbsp;Resultado da Decisão&nbsp;</legend>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <?php
                            $checkedGM = $objEspecieDecisaoLitigiosoDTO->getStrSinGestaoMulta() == 'S' ? 'checked=checked' : '';
                            ($relTipoControleResult || $isExisteDecisaoCasdatrada) ? $multaDisabeld = 'disabled="disabled"' : '';
                            ?>
                            <div class="form-check">
                                <label class="infraLabelCheckbox">
                                    <input <?php echo $checkedGM, " ", $multaDisabeld ?> type="checkbox"
                                                                                            class="infraCheckbox resultDecisao"
                                                                                            name="gestaoMulta"
                                                                                            id="gestaoMulta" value="S">
                                    Indicação de Multa
                                    <a id="btAjuda" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                        <?= PaginaSEI::montarTitleTooltip('Habilite a Indicação de Multa  para permitir o Usuário atribuir valores de Multa ao selecionar esta Espécie de Decisão no Cadastro de Decisão.', 'Ajuda') ?>>
                                        <img border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImgModulo"/>
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="opcoesMulta" style="display: none;">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-check">
                                <label for="integracao" class="radio-label">
                                    <?php $indicacaoIntegracaoCheck = $objEspecieDecisaoLitigiosoDTO->getStrStaTipoIndicacaoMulta() == MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO ? 'checked' : '' ?>
                                    <input type="radio" name="tipoMulta" id="integracao" class="infraRadio"
                                            value="1" <?php echo $indicacaoIntegracaoCheck, " ", $multaDisabeld; ?> >
                                    Gestão por Integração
                                    <a id="btAjuda" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                        <?= PaginaSEI::montarTitleTooltip('Selecione a opção Gestão por Integração caso a Indicação de Multa deva ocorrer por meio de Integração com o Sistema de Arrecadação. \n\n
                                    Ao selecionar esta opção é necessário realizar o Mapeamento das Integrações com o Sistema de Arrecadação em Administração >> Controle de Processos Litigiosos ', 'Ajuda') ?>>
                                        <img border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImgModulo"/>
                                    </a>
                                </label><br>
                                <label for="indicacao" class="radio-label">
                                    <?php $idicacaoValorCheck = $objEspecieDecisaoLitigiosoDTO->getStrStaTipoIndicacaoMulta() == MdLitEspecieDecisaoDTO::$TIPO_MULTA_INDICACAO_VALOR ? 'checked' : '' ?>
                                    <input type="radio" name="tipoMulta" id="indicacao" class="infraRadio"
                                            value="2" <?php echo $idicacaoValorCheck, " ", $multaDisabeld ?>>
                                    Apenas Indicação de Valor
                                    <a id="btAjuda" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                        <?= PaginaSEI::montarTitleTooltip('Selecione a opção Apenas Indicação de Valor caso a Indicação de Multa não deva ocorrer por meio de Integração com o Sistema de Arrecadação e os valores definidos no Cadastro da Decisão devam ser mantidos somente no SEI.', 'Ajuda') ?>>
                                        <img border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImgModulo"/>
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-check">
                                <label class="infraLabelCheckbox">
                                    <?php $checkedIP = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoPrazo() == 'S' ? 'checked=checked' : ''; ?>
                                    <input <?php echo $checkedIP; ?> type="checkbox"
                                                                        class="infraCheckbox resultDecisao"
                                                                        name="indPrazo"
                                                                        id="indPrazo" value="S">
                                    Indicação de Prazo em Dias
                                    <a id="btAjuda" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                        <?= PaginaSEI::montarTitleTooltip('Habilite a Indicação de Prazo para permitir o Usuário definir o prazo ao selecionar esta Espécie de Decisão no Cadastro de Decisão.') ?>>
                                        <img border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImgModulo"/>
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <?php $checkedIO = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S' ? 'checked=checked' : ''; ?>
                            <div class="form-check">
                                <label class="infraLabelCheckbox">
                                    <input <?php echo $checkedIO; ?> type="checkbox"
                                                                        class="infraCheckbox resultDecisao"
                                                                        name="IndObr"
                                                                        id="IndObr" value="S"
                                                                        onchange="showObrigacoes();">
                                    Indicação de Obrigações
                                    <a id="btAjuda" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                        <?= PaginaSEI::montarTitleTooltip('Habilite a Indicação de Obrigações para permitir o Usuário definir as obrigações ao selecionar esta Espécie de Decisão no Cadastro de Decisão.', 'Ajuda') ?>>
                                        <img border="0"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                class="infraImgModulo"/>
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <?php $checkedIRes = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoValor() == 'S' ? 'checked=checked' : ''; ?>
                            <div class="form-check">
                                <label class="infraLabelCheckbox">
                                    <input <?php echo $checkedIRes; ?> type="checkbox" class="infraCheckbox"
                                                                        name="IndRessarcimento"
                                                                        id="IndRessarcimento" value="S">
                                    Indicação de Valor
                                    <a id="btAjuda" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                        <?= PaginaSEI::montarTitleTooltip('Habilite a opção Indicação Valor para permitir o Usuário definir o Valor ao selecionar esta Espécie de Decisão no Cadastro de Decisão.', 'Ajuda') ?>>
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
        <?php $displayObrigacoes = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S' ? 'height:11.5em; display: inherit' : 'height:11.5em; display: none'; ?>
        <br>
        <div id="obrigacoesAssociadas" style="<?php echo $displayObrigacoes; ?>">
            <div class="row">
                <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                    <label id="lblDescricaoObrigacao" for="txtObrigacao" accesskey="q"
                            class="infraLabelObrigatorio">Obrigações
                        Associadas:</label>
                    <input type="text" id="txtObrigacao" name="txtObrigacao" class="infraText form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                    <div class="input-group mb-3">
                        <select id="selDescricaoObrigacao" name="selDescricaoObrigacao" size="8"
                                multiple="multiple"
                                class="infraSelect form-select">
                            <?= $strItensSelObrigacoes ?>
                        </select>
                        <div class="botoes">
                            <img id="imgLupaObrigacao" onclick="objLupaObrigacao.selecionar(700,500);"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                    alt="Localizar Obrigação Associada"
                                    title="Localizar Obrigação Associada" class="infraImg"/>

                            <img id="imgExcluirObrigacao" onclick="objLupaObrigacao.remover();"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                    alt="Remover Obrigações Associadas"
                                    title="Remover Obrigações Associadas" class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnIdObrigacao" name="hdnIdObrigacao"
                                value="<?= $_POST['hdnIdObrigacao'] ?>"/>
                        <input type="hidden" id="hdnObrigacao" name="hdnObrigacao"
                                value="<?= $_POST['hdnObrigacao'] ?>"/>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="hdnIdEspecieDecisaoLitigioso" name="hdnIdEspecieDecisaoLitigioso"
               value="<?= $objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso(); ?>"/>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
    </form>
<?
require_once("md_lit_especie_decisao_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
