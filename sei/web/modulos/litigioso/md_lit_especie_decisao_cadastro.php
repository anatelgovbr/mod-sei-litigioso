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

        $arrComandos             = array();
        $strLinkObrigacaoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_obrigacao_selecionar&tipo_selecao=2&id_object=objLupaObrigacao');
        $strLinkAjaxObrigacao    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_obrigacao_auto_completar');
        $strLinkValidarCadastroIntegracao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_cadastro_integracao_multa');
        $strLinkValidarAlteracaoIntegracao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_alteracao_integracao_multa');

        switch ($_GET['acao']) {
            case 'md_lit_especie_decisao_cadastrar':

                $strTitulo = 'Nova Esp�cie de Decis�o';

                $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEspecieDecisaoLitigioso" id="sbmCadastrarEspecieDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

                $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso(null);
                $objEspecieDecisaoLitigiosoDTO->setStrNome($_POST['txtNome']);
                $objEspecieDecisaoLitigiosoDTO->setStrSinGestaoMulta($_POST['gestaoMulta'] != null ? 'S' : 'N');
                $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoPrazo($_POST['indPrazo'] != null ? 'S' : 'N');
                $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoObrigacoes($_POST['IndObr'] != null ? 'S' : 'N');
                $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoRessarcimentoValor($_POST['IndRessarcimento'] != null ? 'S' : 'N');

                if($_POST['gestaoMulta'] == 'S'){
                    $objEspecieDecisaoLitigiosoDTO->setStrStaTipoIndicacaoMulta($_POST['tipoMulta']);
                    $MdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
                    $responseValicacao = $MdLitEspecieDecisaoRN->validarIntegracaoMulta($_POST);
                    if($responseValicacao->success == false){
                        $incompletas = '';
                        foreach ($responseValicacao->integracaoIncompleta as $integracaoIncompleta){
                            $incompletas .= "{$integracaoIncompleta->get('Nome')}\n";
                        }
                        $objInfraException = new InfraException();
                        $objInfraException->lancarValidacao("O Mapeamento das Integra��es com o Sistema de Arrecada��o n�o".
                            "foram realizados. Antes de definir que a Gest�o de Multa deve ocorrer por meio de Integra��o, acesse Administra��o >> Controle de".
                            "Processo Litigioso >> Mapeamento das Integra��es e insira os Mapeamentos de Integra��es das funcionalidades abaixo: \n\n".
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
                            $arrObrigacoes                    = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnObrigacao']);

                            for ($x = 0; $x < count($arrObrigacoes); $x++) {
                                $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                                $objRelEspecieDecisaoObrigacaoDTO->setNumIdObrigacaoLitigioso($arrObrigacoes[$x]);
                                array_push($arrObjEspecieDecisaoObrigacaoDTO, $objRelEspecieDecisaoObrigacaoDTO);
                            }

                            $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoObrigacaoDTO);
                        } else {
                            $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO(array());
                        }
                        //Fim Obriga��es

                        $objEspecieDecisaoLitigiosoRN  = new MdLitEspecieDecisaoRN();
                        $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->cadastrar($objEspecieDecisaoLitigiosoDTO);
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_especie_decisao_litigioso=' . $objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso() . PaginaSEI::getInstance()->montarAncora($objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso())));
                        die;
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                }
                break;

            case 'md_lit_especie_decisao_alterar':
                $strTitulo      = 'Alterar Esp�cie de Decis�o';
                $arrComandos[]  = '<button type="submit" accesskey="S" name="sbmAlterarEspecieDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
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
                    $objEspecieDecisaoLitigiosoRN  = new MdLitEspecieDecisaoRN();
                    $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->consultar($objEspecieDecisaoLitigiosoDTO);

                    if ($objEspecieDecisaoLitigiosoDTO == null) {
                        throw new InfraException("Registro n�o encontrado.");
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
                        $arrObrigacoes                   = $objRelEspecieDecisaoObrigacaoRN->listar($objRelEspecieDecisaoObrigacaoDTO);

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
                    $objEspecieDecisaoLitigiosoDTO->setStrSinIndicacaoRessarcimentoValor($_POST['IndRessarcimento'] != null ? 'S' : 'N');

                    //se houver gestao de multa
                    if($_POST['gestaoMulta'] == 'S'){

                        //aplica a gest�o de acordo com o informado
                        $objEspecieDecisaoLitigiosoDTO->setStrStaTipoIndicacaoMulta($_POST['tipoMulta'] == 1
                            ? MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO
                            : MdLitEspecieDecisaoDTO::$TIPO_MULTA_INDICACAO_VALOR
                        );

                        $responseValicacao = $MdLitEspecieDecisaoRN->validarIntegracaoMulta($_POST);
                        if($responseValicacao->success == false){
                            $objInfraException = new InfraException();
                            $incompletas = '';
                            foreach ($responseValicacao->integracaoIncompleta as $integracaoIncompleta){
                                $incompletas .= "{$integracaoIncompleta->get('Nome')}\n";
                            }

                            $objInfraException->lancarValidacao("O Mapeamento das Integra��es com o Sistema de Arrecada��o n�o".
                                    "foram realizados. Antes de definir que a Gest�o de Multa deve ocorrer por meio de Integra��o, acesse Administra��o >> Controle de".
                                    "Processo Litigioso >> Mapeamento das Integra��es e insira os Mapeamentos de Integra��es das funcionalidades abaixo: \n\n".
                                    $incompletas
                            );
                        }

                        $arrData = [
                                'hdnIdEspecieDecisaoLitigioso' => $_POST['hdnIdEspecieDecisaoLitigioso'],
                                //inverte para verificar se havia alguma decis�o cadastrado com o tipo que foi mudado
                                'tipoMulta' => $data['tipoMulta'] == MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO
                                    ? MdLitEspecieDecisaoDTO::$TIPO_MULTA_INDICACAO_VALOR
                                    : MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO
                        ];

                        //valida��o para altera�� do tipo de multa
                        $MdLitEspecieDecisaoDTO = $MdLitEspecieDecisaoRN->getEspecieDecisoesById(['arrEspeciesId' => [$arrData['hdnIdEspecieDecisaoLitigioso']]]);
                        $tipoMulta = $MdLitEspecieDecisaoDTO[0]->get('StaTipoIndicacaoMulta');
                        if($tipoMulta != $arrData['tipoMulta'] && $MdLitEspecieDecisaoRN->existeDecisaoCadastradaParaTipoMulta($arrData)){
                            $objInfraException = new InfraException();
                            $objInfraException->lancarValidacao("N�o � possivel modificar o tipo de indica��o de ".
                            "multa pois ja existem decis�es cadastradas com o tipo anterior. \n".
                            "Caso esta mudan�a seja essencial desative esta 'Esp�cie de Decis�o', crie uma nova e vincule ao processo");
                        }

                    } else {
                        $objEspecieDecisaoLitigiosoDTO->setStrStaTipoIndicacaoMulta(null);
                    }


                    //Set Obrigacoes
                    if ($objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                        $arrObjEspecieDecisaoObrigacaoDTO = array();
                        $arrObrigacoes                    = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnObrigacao']);

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
                $strTitulo     = 'Consultar Esp�cie de Decis�o';
                $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_especie_decisao_litigioso']))) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
                if (isset($_GET['id_especie_decisao_litigioso'])) {
                    $objEspecieDecisaoLitigiosoDTO->setNumIdEspecieLitigioso($_GET['id_especie_decisao_litigioso']);
                    $objEspecieDecisaoLitigiosoDTO->retTodos();
                    $objEspecieDecisaoLitigiosoRN  = new MdLitEspecieDecisaoRN();
                    $objEspecieDecisaoLitigiosoDTO = $objEspecieDecisaoLitigiosoRN->consultar($objEspecieDecisaoLitigiosoDTO);

                    if ($objEspecieDecisaoLitigiosoDTO == null) {
                        throw new InfraException("Registro n�o encontrado.");
                    }

                    $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                    $objRelEspecieDecisaoObrigacaoDTO->retTodos();
                    $objRelEspecieDecisaoObrigacaoDTO->retStrNomeObrigacao();
                    $objRelEspecieDecisaoObrigacaoDTO->setNumIdEspecieDecisaoLitigioso($_GET['id_especie_decisao_litigioso']);

                    PaginaSEI::getInstance()->prepararOrdenacao($objRelEspecieDecisaoObrigacaoDTO, 'NomeObrigacao', InfraDTO::$TIPO_ORDENACAO_ASC);

                    $objRelEspecieDecisaoObrigacaoRN = new MdLitRelEspecieDecisaoObrigacaoRN();
                    $arrObrigacoes                   = $objRelEspecieDecisaoObrigacaoRN->listar($objRelEspecieDecisaoObrigacaoDTO);

                    $objEspecieDecisaoLitigiosoDTO->setArrObjRelEspecieLitigiosoDTO($arrObrigacoes);

                    $strItensSelObrigacoes = "";
                    for ($x = 0; $x < count($arrObrigacoes); $x++) {
                        $strItensSelObrigacoes .= "<option value='" . $arrObrigacoes[$x]->getNumIdObrigacaoLitigioso() . "'>" . $arrObrigacoes[$x]->getStrNomeObrigacao() . "</option>";
                    }

                }
                break;

            default:
                throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
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
?>
#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:6%;width:50%;}

#fldNovaEspDecisao {height:10%; border:none; }

#txtObrigacao {width: 50%; left:0%; margin-top:2px;}
#selDescricaoObrigacao {width: 75%; left:0%; margin-top:2px; }


#imgLupaObrigacao {position:absolute;left:75.5%;top:35%;}
#imgExcluirObrigacao  {position:absolute;left:75.3%;top:55%;}

<?
    include_once "md_lit_css_geral.php";

    PaginaSEI::getInstance()->fecharStyle();
    PaginaSEI::getInstance()->montarJavaScript();
    PaginaSEI::getInstance()->abrirJavaScript();
?>


<?
    PaginaSEI::getInstance()->fecharJavaScript();
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmEsp�cie de Decis�oCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('40em');
    ?>

    <fieldset id="fldNovaEspDecisao">
        <div>
            <label id="lblNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Esp�cie de Decis�o:</label>
            <input type="text" id="txtNome" name="txtNome" class="infraText"
                   value="<?= PaginaSEI::tratarHTML($objEspecieDecisaoLitigiosoDTO->getStrNome()); ?>"
                   onkeypress="return infraMascaraTexto(this,event, 50);" maxlength="50"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        </div>
    </fieldset>
    <div style="clear:both;">&nbsp;</div>

    <!--  Fieldset Resultado da Decis�o -->
    <fieldset id="fieldsetResultadoDecisao" class="infraFieldset">
        <legend class="infraLegend">&nbsp;Resultado da Decis�o&nbsp;</legend>
        <div>
            <!-- Gest�o de Multa  -->
            <?php
                $checkedGM = $objEspecieDecisaoLitigiosoDTO->getStrSinGestaoMulta() == 'S' ? 'checked=checked' : '';
                ($relTipoControleResult || $isExisteDecisaoCasdatrada) ? $multaDisabeld = 'disabled="disabled"' : '';
            ?>
            <label class="infraLabelCheckbox">
                <input <?php echo $checkedGM, " ", $multaDisabeld ?> type="checkbox" class="infraCheckbox resultDecisao" name="gestaoMulta"
                                                                     id="gestaoMulta" value="S">
                Indica��o de Multa
                <span class="tooltipAjuda"
                    <?= PaginaSEI::montarTitleTooltip('Habilite a Indica��o de Multa  para permitir o Usu�rio atribuir valores de Multa ao selecionar esta Esp�cie de Decis�o no Cadastro de Decis�o.') ?>>
                </span>
            </label>
            <br>
            <div id="opcoesMulta" style="margin-left: 3em; display: none;">
                <label for="integracao" class="radio-label">
                    <?php $indicacaoIntegracaoCheck = $objEspecieDecisaoLitigiosoDTO->getStrStaTipoIndicacaoMulta() == MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO ? 'checked': ''?>
                    <input type="radio" name="tipoMulta" id="integracao" value="1" <?php echo $indicacaoIntegracaoCheck, " ", $multaDisabeld;?> >
                    Gest�o por Integra��o
                    <span class="tooltipAjuda"
                        <?= PaginaSEI::montarTitleTooltip('Selecione a op��o Gest�o por Integra��o caso a Indica��o de Multa deva ocorrer por meio de Integra��o com o Sistema de Arrecada��o. \n\n
                            Ao selecionar esta op��o � necess�rio realizar o Mapeamento das Integra��es com o Sistema de Arrecada��o em Administra��o >> Controle de Processos Litigiosos ') ?>>
                    </span>
                </label><br>
                <label for="indicacao" class="radio-label">
                    <?php $idicacaoValorCheck =  $objEspecieDecisaoLitigiosoDTO->getStrStaTipoIndicacaoMulta() == MdLitEspecieDecisaoDTO::$TIPO_MULTA_INDICACAO_VALOR ? 'checked': ''?>
                    <input type="radio" name="tipoMulta" id="indicacao" value="2" <?php echo $idicacaoValorCheck," ", $multaDisabeld ?>>
                    Apenas Indica��o de Valor
                    <span class="tooltipAjuda"
                        <?= PaginaSEI::montarTitleTooltip('Selecione a op��o Apenas Indica��o de Valor caso a Indica��o de Multa n�o deva ocorrer por meio de Integra��o com o Sistema de Arrecada��o e os valores definidos no Cadastro da Decis�o devam ser mantidos somente no SEI.') ?>>
                    </span>
                </label>
            </div>

            <!-- Indica��o de Prazo -->
            <?php $checkedIP = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoPrazo() == 'S' ? 'checked=checked' : ''; ?>
            <label class="infraLabelCheckbox">
                <input <?php echo $checkedIP; ?> type="checkbox" class="infraCheckbox resultDecisao" name="indPrazo"
                     id="indPrazo" value="S">
                Indica��o de Prazo em Dias
                <span class="tooltipAjuda"
                    <?= PaginaSEI::montarTitleTooltip('Habilite a Indica��o de Prazo para permitir o Usu�rio definir o prazo ao selecionar esta Esp�cie de Decis�o no Cadastro de Decis�o.') ?>>
                </span>
            </label>
            <br/>

            <!-- Indica��o de Obriga��es -->
            <?php $checkedIO = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S' ? 'checked=checked' : ''; ?>
            <label class="infraLabelCheckbox">
                <input <?php echo $checkedIO; ?> type="checkbox" class="infraCheckbox resultDecisao" name="IndObr"
                    id="IndObr" value="S" onchange="showObrigacoes();">
                Indica��o de Obriga��es
                <span class="tooltipAjuda"
                    <?= PaginaSEI::montarTitleTooltip('Habilite a Indica��o de Obriga��es para permitir o Usu�rio definir as obriga��es ao selecionar esta Esp�cie de Decis�o no Cadastro de Decis�o.') ?>>
                </span>
            </label>
            <br>

            <!-- Indica��o de Ressarcimento -->
            <?php  $checkedIRes = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoRessarcimentoValor() == 'S' ? 'checked=checked' : ''; ?>
            <label class="infraLabelCheckbox">
                <input <?php echo $checkedIRes; ?> type="checkbox" class="infraCheckbox" name="IndRessarcimento"
                    id="IndRessarcimento" value="S">
                Indica��o de Ressarcimento de Valor
                <span class="tooltipAjuda"
                    <?= PaginaSEI::montarTitleTooltip('Habilite a op��o Indica��o de Ressarcimento de Valor para permitir o Usu�rio definir o Valor de Ressarcimento ao selecionar esta Esp�cie de Decis�o no Cadastro de Decis�o.') ?>>
                </span>
            </label>

        </div>
    </fieldset>
    <div style="clear:both;">&nbsp;</div>
    <!--  Componente de Obriga��es Associadas -->
    <?php $displayObrigacoes = $objEspecieDecisaoLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S' ? 'height:11.5em; display: inherit' : 'height:11.5em; display: none'; ?>
    <div id="obrigacoesAssociadas" class="infraAreaDados" style="<?php echo $displayObrigacoes; ?>">

        <label id="lblDescricaoObrigacao" for="txtObrigacao" accesskey="q" class="infraLabelObrigatorio">Obriga��es
            Associadas:</label>
        <br/>
        <input type="text" id="txtObrigacao" name="txtObrigacao" class="infraText"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        <select id="selDescricaoObrigacao" name="selDescricaoObrigacao" size="4" multiple="multiple"
                class="infraSelect">
            <?= $strItensSelObrigacoes ?>
        </select>

        <img id="imgLupaObrigacao" onclick="objLupaObrigacao.selecionar(700,500);" src="/infra_css/imagens/lupa.gif"
             alt="Localizar Obriga��o Associada"
             title="Localizar Obriga��o Associada" class="infraImg"/>

        <img id="imgExcluirObrigacao" onclick="objLupaObrigacao.remover();" src="/infra_css/imagens/remover.gif"
             alt="Remover Obriga��es Associadas"
             title="Remover Obriga��es Associadas" class="infraImg"/>

        <input type="hidden" id="hdnIdObrigacao" name="hdnIdObrigacao" value="<?= $_POST['hdnIdObrigacao'] ?>"/>
        <input type="hidden" id="hdnObrigacao" name="hdnObrigacao" value="<?= $_POST['hdnObrigacao'] ?>"/>

    </div>
    <!--  Fim Componente Obriga��es Associadas  -->

    <input type="hidden" id="hdnIdEspecieDecisaoLitigioso" name="hdnIdEspecieDecisaoLitigioso"
           value="<?= $objEspecieDecisaoLitigiosoDTO->getNumIdEspecieLitigioso(); ?>"/>
    <?
        PaginaSEI::getInstance()->fecharAreaDados();
    ?>
</form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>

<script type="text/javascript">
    $(function(){
        showTipoMulta();

        $('[name="gestaoMulta"]').change(function(){
            if(validarAlteracaoIndicacaoMulta() ==false){
                $('[name="gestaoMulta"]').prop('checked', 'checked').prop('readonly', 'readonly');
                $('[name="tipoMulta"]').not(":checked").prop('readonly', 'readonly');
            }

            showTipoMulta();
        });

        $('[name="tipoMulta"]').change(function(){
            if(validarAlteracaoIndicacaoMulta() == false){
                $('[name="gestaoMulta"]').prop('checked', 'checked').prop('readonly', 'readonly');
                $('[name="tipoMulta"]').not(":checked").prop('checked', 'checked').prop('readonly', 'readonly');
            }

            showTipoMulta();
        });
    });


    function inicializar() {
        carregarComponenteObrigacao();
        if ('<?=$_GET['acao']?>' == 'md_lit_especie_decisao_cadastrar') {
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_especie_decisao_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();
    }

    function showTipoMulta(){
        if($('[name="gestaoMulta"]').is(':checked')){
            $('#opcoesMulta').show();
        } else{
            $('#opcoesMulta').hide();
            $('[name="tipoMulta"]').prop('checked', false);
        }
    }

    function showObrigacoes() {
        document.getElementById('obrigacoesAssociadas').style.display = "none";
        document.getElementById('IndObr').checked ? document.getElementById('obrigacoesAssociadas').style.display = "inherit" : document.getElementById('obrigacoesAssociadas').style.display = "none";
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe a Esp�cie de Decis�o.');
            document.getElementById('txtNome').focus();
            return false;
        }

        var indObrigacao = document.getElementById('IndObr').checked;
        if (indObrigacao) {
            //tipos de controle associados  

            var optionsObrigacao = document.getElementById('selDescricaoObrigacao').options;

            if (optionsObrigacao.length == 0) {
                alert('Informe ao menos uma obriga��o associada.');
                document.getElementById('selDescricaoObrigacao').focus();
                return false;
            }
        }

        if($('[name="gestaoMulta"]:checked').length > 0 && $('[name="tipoMulta"]:checked').length == 0) {
                alert('Informe o tipo de indica��o de multa.');
                return false;
        }

        if(validarMultaPorIntegracao() == false){
            return false;
        }

        if(validarAlteracaoIndicacaoMulta() == false){
            return false;
        }

        return true;
    }

    function validarMultaPorIntegracao() {
        var valid = true;
        if ($('[name="gestaoMulta"]:checked').length > 0 &&
            $('[name="tipoMulta"]:checked').val() == <?php echo MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO ?>) {
                $.ajax({
                    type: "POST",
                    url: "<?= $strLinkValidarCadastroIntegracao ?>",
                    dataType: "xml",
                    async: false,
                    data: {'tipoMulta':  $('[name="tipoMulta"]:checked').val()},
                    success: function (data) {
                        if ($(data).find('success').text() != '1') {
                            teste = data;

                            var integracoes = '';
                            $(data).find('integracoesIncompletas').children().each(function(key, element){
                                integracoes += ""+$(element).text()+"\n";
                            });

                            alert("O Mapeamento das Integra��es com o Sistema de Arrecada��o n�o " +
                                "foram realizados. Antes de definir que a Gest�o de Multa deve ocorrer " +
                                "por meio de Integra��o, acesse Administra��o >> Controle de " +
                                "Processo Litigioso >> Mapeamento das Integra��es e insira os " +
                                "Mapeamentos de Integra��es das funcionalidades abaixo:\n" +
                                "\n" +
                                integracoes);
                            valid = false;
                        }
                    },
                    error: function (msgError) {
                        msgCommit = "Erro ao processar o valida��o do do SEI: " + msgError.responseText;
                        console.log(msgCommit);
                    },
                    complete: function (result) {
                        infraAvisoCancelar();
                    }
                });
        }
        return valid;
    }

    function validarAlteracaoIndicacaoMulta() {
        var valid = true;
        var notChecked = $('[name="tipoMulta"]').not(":checked").parent().text().trim();
        var checked = $('[name="tipoMulta"]:checked').parent().text().trim();

        //caso seja desmacado direto o checkbox de indica��o de multa
        if($('[name="gestaoMulta"]').is(':checked') == false){
            //usa o radio marcado
            var idTipoMulta = $('[name="tipoMulta"]:checked').val();
            var mensagem = "N�o � poss�vel desabilitar a op��o Indica��o de Multa para esta Esp�cie de Decis�o pois j� existem desi��es com multas cadastradas. \n"+
                "Caso seja necess�rio esta mudan�a desative esta Esp�cie de Decis�o e crie uma nova.";
        } else{
            //usa o radio desmarcado
            var idTipoMulta = $('[name="tipoMulta"]').not(":checked").val();
            var mensagem = "N�o � possivel modificar a op��o Indica��o de Multa para "+checked+" pois ja existem Decis�es cadastradas com o tipo "+notChecked+". \n "+
                "Caso seja necess�rio esta mudan�a, desative esta Esp�cie de Decis�o e crie uma nova.";
        }

        if ('md_lit_especie_decisao_alterar' == '<?php echo $_GET['acao'] ?>') {
                $.ajax({
                    type: "POST",
                    url: "<?= $strLinkValidarAlteracaoIntegracao ?>",
                    dataType: "xml",
                    async: false,
                    data: {'tipoMulta': idTipoMulta, 'hdnIdEspecieDecisaoLitigioso': $('[name="hdnIdEspecieDecisaoLitigioso"]').val()},
                    success: function (data) {
                        if ($(data).find('resultado').text() == '1') {
                            alert(mensagem);
                            valid = false;
                        }
                    },
                    error: function (msgError) {
                        msgCommit = "Erro ao processar o valida��o do do SEI: " + msgError.responseText;
                        console.log(msgCommit);
                    },
                    complete: function (result) {
                        infraAvisoCancelar();
                    }
                });
        }
        return valid;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function carregarComponenteObrigacao() {

        objAutoCompletarObrigacao = new infraAjaxAutoCompletar('hdnIdObrigacao', 'txtObrigacao', '<?=$strLinkAjaxObrigacao?>');
        objAutoCompletarObrigacao.limparCampo = true;
        objAutoCompletarObrigacao.tamanhoMinimo = 3;
        objAutoCompletarObrigacao.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtObrigacao').value;
        };

        objAutoCompletarObrigacao.processarResultado = function (id, nome, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricaoObrigacao').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Obriga��o j� consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoObrigacao'), nome, id);

                    objLupaObrigacao.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtObrigacao').value = '';
                document.getElementById('txtObrigacao').focus();

            }
        };

        objLupaObrigacao = new infraLupaSelect('selDescricaoObrigacao', 'hdnObrigacao', '<?=$strLinkObrigacaoSelecao?>');

    }

</script>