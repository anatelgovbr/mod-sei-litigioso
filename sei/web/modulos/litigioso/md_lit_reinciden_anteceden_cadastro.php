<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 23/04/2018
 * Time: 17:46
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'md_lit_reinciden_anteceden_cadastrar':
        case 'md_lit_reinciden_anteceden_alterar':
            $strTitulo = 'Reincidências Específicas e Antecedentes';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarReincidenciaAntecedente" value="Salvar"
                              class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar"
                                onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\'"
                                 class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            break;
    }


    $bolAlterar = false;

    $prazoRein = "";
    $orientacaoRein = "";
    $idReincidencia = "";
    $prazoAnte = "";
    $orientacaoAnte = "";
    $idAntecedente = "";
    $strItensDecisaoRein = "";
    $strItensDecisaoAnte = "";
//valor default e a radio de conduta
    $rdoRegraReincidencia = MdLitReincidenAntecedenRN::$CONDUTA;

    $mdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
    $mdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();

    $mdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();
    $mdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();


    $mdLitReincidenAntecedenDTO->retTodos();
    $arrmdLitReincidenAntecedenDTO = $mdLitReincidenAntecedenRN->listar($mdLitReincidenAntecedenDTO);

    if (count($arrmdLitReincidenAntecedenDTO) > 0) {

        $bolAlterar = true;
        foreach ($arrmdLitReincidenAntecedenDTO as $mdLitReincidenAnteceden) {
            if ($mdLitReincidenAnteceden->getStrTipo() == MdLitReincidenAntecedenRN::$TIPO_REINCIDENCIA) {
                $prazoRein = $mdLitReincidenAnteceden->getNumPrazo();
                $orientacaoRein = $mdLitReincidenAnteceden->getStrOrientacao();
                $idReincidencia = $mdLitReincidenAnteceden->getNumIdMdLitReincidenAnteceden();
                $rdoRegraReincidencia = $mdLitReincidenAnteceden->getStrTipoRegraReincidencia();

            }
            if ($mdLitReincidenAnteceden->getStrTipo() == MdLitReincidenAntecedenRN::$TIPO_ANTECEDENTE) {
                $prazoAnte = $mdLitReincidenAnteceden->getNumPrazo();
                $orientacaoAnte = $mdLitReincidenAnteceden->getStrOrientacao();
                $idAntecedente = $mdLitReincidenAnteceden->getNumIdMdLitReincidenAnteceden();

            }

        }

        $mdLitRelTpDecReinAnteDTO->retTodos();
        $mdLitRelTpDecReinAnteDTO->retStrNomeTipoDecisao();
        $arrMdLitRelTpDecReinAnteDTO = $mdLitRelTpDecReinAnteRN->listar($mdLitRelTpDecReinAnteDTO);

        foreach ($arrMdLitRelTpDecReinAnteDTO as $mdLitRelTpDecReinAnte) {

            if ($mdLitRelTpDecReinAnte->getNumIdRelMdLitReincidenAnteceden() == $idReincidencia) {
                $strItensDecisaoRein .= '<option value=' . $mdLitRelTpDecReinAnte->getNumIdRelMdLitTipoDecisao() .
                    '>' . PaginaSEI::tratarHTML($mdLitRelTpDecReinAnte->getStrNomeTipoDecisao()) . '</option>';
            }
            if ($mdLitRelTpDecReinAnte->getNumIdRelMdLitReincidenAnteceden() == $idAntecedente) {
                $strItensDecisaoAnte .= '<option value=' . $mdLitRelTpDecReinAnte->getNumIdRelMdLitTipoDecisao() .
                    '>' . PaginaSEI::tratarHTML($mdLitRelTpDecReinAnte->getStrNomeTipoDecisao()) . '</option>';
            }

        }

    }


    if (isset($_POST['sbmCadastrarReincidenciaAntecedente'])) {

        $objEditorRN = new EditorRN();
        $objEditorRN->validarTagsCriticas(array('png', 'jpeg'), $_POST['txtOrientacoes']);
        $objEditorRN->validarTagsCriticas(array('png', 'jpeg'), $_POST['txtAnteOrientacoes']);
        // header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_reinciden_anteceden_alterar' . '&acao_origem=' . $_GET['acao']));


        if ($bolAlterar) {
            $_POST['idReincidencia'] = $idReincidencia;
            $_POST['idAntecedencia'] = $idAntecedente;

            $mdLitReincidenAntecedenRN->alterarReincidenciaAntecedentes($_POST);
        } else {

            $mdLitReincidenAntecedenRN->cadastrarReincidenciaAntecedentes($_POST);
        }
        PaginaSEI::getInstance()->adicionarMensagem("Os dados foram salvos com sucesso.", PaginaSEI::$TIPO_MSG_AVISO);
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_reinciden_anteceden_alterar' . '&acao_origem=' . $_GET['acao']));

    }

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

$strLinkDecisaoSelecaoAntecedente = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_selecionar&tipo_selecao=2&id_object=objLupaDecisaoAntecedente');
$strLinkDecisaoSelecaoReicidencia = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_selecionar&tipo_selecao=2&id_object=objLupaDecisaoReicidencia');
$strLinkAjaxAutoComplDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_tipo_decisao_auto_completar');
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
require_once("md_lit_reinciden_anteceden_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmReincidenciaAntecedenteCadastro" method="post" onsubmit="return onSubmit();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&äcao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <fieldset id="fieldRein" class="infraFieldset form-control">
                    <legend class="infraLegend">Reincidências Específicas</legend>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                            <div class="form-group">
                                <label id="lblPrazoRein" name="lblPrazoRein" for="txtPrazoRein"
                                    class="infraLabelObrigatorio">Prazo
                                    em
                                    Anos: <img align="top" id="imgAjuda"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                            name="ajuda"
                                            onmouseover="return infraTooltipMostrar('Prazo em Anos que será utilizado na pesquisa retroativa por processos Transitados em Julgados, para que, em conjunto com os demais parâmetros definidos nesta tela e sobre o Interessado correspondente, liste os processos em que ocorreu Reincidência Específica.', 'Ajuda');"
                                            onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                            class="infraImgModulo">
                                </label>
                                <input type="number" class="infraText form-control" id="txtPrazoRein" maxlength="2"
                                    onkeypress='return infraMascaraNumero(this,event,2);' min="0"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" name="txtPrazoRein"
                                    value="<?= $prazoRein ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                            <label id="lblDecRein" name="lblDecRein" class="infraLabelObrigatorio">
                                Tipos de Decisão: <img align="top" id="imgAjuda"
                                                       src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                       name="ajuda"
                                                       onmouseover="return infraTooltipMostrar('Somente será considerada Reincidência Específica se nos processos Transitados em Julgado localizados pela pesquisa retroativa constar decisões dos Tipos de Decisão indicados neste campo.', 'Ajuda');"
                                                       onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                                       class="infraImgModulo">
                            </label>
                            <input type="text" id="txtDecRein" class="infraText form-control"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" name="txtDecRein"
                                   value=""/>
                            <input type="hidden" id="hdnTipoDeciRein" class="infraText" name="hdnTipoDeciRein"
                                   value=""/>
                            <input type="hidden" id="hdnIdTipoDeciRein" class="infraText" name="hdnIdTipoDeciRein"
                                   value=""/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                            <div class="form-group">
                                <div class="input-group  mb-3">
                                    <select style="float: left;" id="selDecRein" name="selDecRein" size="8"
                                            multiple="multiple" class="infraSelect form-control">
                                        <?= $strItensDecisaoRein; ?>
                                    </select>
                                    <div id="divOpcoesTipoDecisao" class="botoes">
                                        <img id="imgLupaTipoDecisaoRein"
                                            onclick="objLupaDecisaoReicidencia.selecionar(700,500);"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                            alt="Localizar Tipo de Decisão"
                                            title="Localizar Tipo de Decisão" class="infraImg"/>
                                        <br>
                                        <img id="imgExcluirTipoDecisaoRein" onclick="objLupaDecisaoReicidencia.remover();"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                            alt="Remover Tipos de Decisão"
                                            title="Remover Tipos de Decisão" class="infraImg"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                            <div class="form-group">
                                <label id="lblRegraReincidencia" name="lblRegraReincidencia" class="infraLabelObrigatorio">
                                    Definição de Infração de Mesma Natureza:
                                    <img align="top"
                                        id="imgAjuda"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                        name="ajuda"
                                        onmouseover="return infraTooltipMostrar(' Somente será considerada Reincidência Específica se nos processos Transitados em Julgado localizados pela pesquisa retroativa, conforme Prazo em Anos e decisão dos Tipos de Decisão acima configurados, conste infração de mesma natureza, de acordo com a opção indicada neste campo.', 'Ajuda');"
                                        onmouseout="return infraTooltipOcultar();"
                                        alt="Ajuda" class="infraImgModulo">
                                </label>
                                <br/>
                                <label id="lblConduta" class="infraLabelRadio"
                                    for="rdConduta">
                                    <input class="infraRadio" type="radio" name="rd_regra_reincidencia"
                                        value="<?= MdLitReincidenAntecedenRN::$CONDUTA ?>" <?= $rdoRegraReincidencia == MdLitReincidenAntecedenRN::$CONDUTA ? 'checked="checked"' : '' ?>
                                        id="rdConduta"/>
                                    Mesma Conduta</label>
                                <br/>
                                <label id="lblDispositivo" class="infraLabelRadio" for="rdDispositivo">
                                    <input class="infraRadio" type="radio" name="rd_regra_reincidencia"
                                        value="<?= MdLitReincidenAntecedenRN::$DISPOSITIVO ?>" <?= $rdoRegraReincidencia == MdLitReincidenAntecedenRN::$DISPOSITIVO ? 'checked="checked"' : '' ?>
                                        id="rdDispositivo"/>
                                    Mesmo Dispositivo Normativo</label>
                                <br/>
                                <label id="lblDispositivoConduta" class="infraLabelRadio" for="rdDispositivoConduta">
                                    <input class="infraRadio" type="radio"
                                        value="<?= MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA ?>"
                                        name="rd_regra_reincidencia" <?= $rdoRegraReincidencia == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA ? 'checked="checked"' : '' ?>
                                        id="rdDispositivoConduta"/>
                                    Mesmo Dispositivo Normativo e Conduta</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label id="lblOrienAntec" name="lblOrienAntec" for=""
                                   class="infraLabelObrigatorio">
                                Orientações: <img align="top" id="imgAjuda"
                                                  src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                  name="ajuda"
                                                  onmouseover="infraTooltipMostrar('O texto aqui configurado será apresentado para os Usuários, acima da listagem que o sistema recuperar sobre Antecedentes, para inclusão de orientações adicionais ou acesso a base de dados legada. \n \n No texto podem ser utilizadas as variáveis a seguir: @prazo_anos_antecedente@, @tipos_decisao_antecedente@', 'Ajuda');document.getElementById('divInfraTooltip').style.maxWidth = '360px';"
                                                  onmouseout="infraTooltipOcultar();document.getElementById('divInfraTooltip').style.maxWidth = '200px';"
                                                  alt="Ajuda" class="infraImgModulo">
                            </label>
                            <?php require_once 'md_lit_reicidencia_orientacao.php' ?>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <fieldset id="fieldAntec" class="infraFieldset form-control">
                    <legend class="infraLegend">Antecedentes</legend>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                            <div class="form-group">
                                <label id="lblPrazoAntec" name="lblPrazoAntec" for="txtPrazoAntec" class="infraLabelObrigatorio">
                                    Prazo em Anos: <img align="top" id="imgAjuda"
                                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                        name="ajuda"
                                                        onmouseover="return infraTooltipMostrar('Prazo em Anos que será utilizado na pesquisa retroativa por processos Transitados em Julgados, para que, em conjunto com os demais parâmetros definidos nesta tela e sobre o Interessado correspondente, liste os processos em que ocorreu Antecedente.', 'Ajuda');"
                                                        onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                                        class="infraImgModulo">
                                </label>
                                <input type="number" class="infraText form-control" id="txtPrazoAntec" maxlength="2"
                                    onkeypress='return infraMascaraNumero(this,event,2);' min="0"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" name="txtPrazoAntec"
                                    value="<?= $prazoAnte ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                            <label id="lblDecAntec" name="lblDecAntec" class="infraLabelObrigatorio">
                                Tipos de Decisão: <img align="top" id="imgAjuda"
                                                       src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                       name="ajuda"
                                                       onmouseover="return infraTooltipMostrar('Somente será considerado Antecedente se nos processos Transitados em Julgado localizados pela pesquisa retroativa constar decisões dos Tipos de Decisão indicados neste campo.', 'Ajuda');"
                                                       onmouseout="return infraTooltipOcultar();" alt="Ajuda"
                                                       class="infraImgModulo">
                            </label>
                            <input type="text" id="txtDecAntec" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                   class="infraText form-control" name="txtDecAntec" value=""/>
                            <input type="hidden" id="hdnTipoDeciAnte" class="infraText" name="hdnTipoDeciAnte" value=""/>
                            <input type="hidden" id="hdnIdTipoDeciAnte" class="infraText" name="hdnIdTipoDeciAnte" value=""/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <select style="float: left;" id="selDecAntec" name="selDecAntec" size="8"
                                            multiple="multiple" class="infraSelect form-control">
                                        <?= $strItensDecisaoAnte; ?>
                                    </select>

                                    <div id="divOpcoesTipoDecisaoAnt" class="botoes">
                                        <img id="imgLupaTipoDecisaoAntec"
                                            onclick="objLupaDecisaoAntecedente.selecionar(700,500)"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                            alt="Localizar Tipo de Decisão"
                                            title="Localizar Tipo de Decisão" class="infraImg"/>
                                        <br>
                                        <img id="imgExcluirTipoDecisaoAntec" onclick="objLupaDecisaoAntecedente.remover();"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                            alt="Remover Tipos de Decisão"
                                            title="Remover Tipos de Decisão" class="infraImg"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <label id="lblOrienAntec" name="lblOrienAntec" for=""
                                   class="infraLabelObrigatorio">
                                Orientações: <img align="top" id="imgAjuda"
                                                  src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                                  name="ajuda"
                                                  onmouseover="infraTooltipMostrar('O texto aqui configurado será apresentado para os Usuários, acima da listagem que o sistema recuperar sobre Antecedentes, para inclusão de orientações adicionais ou acesso a base de dados legada. \n \n No texto podem ser utilizadas as variáveis a seguir: @prazo_anos_antecedente@, @tipos_decisao_antecedente@', 'Ajuda');document.getElementById('divInfraTooltip').style.maxWidth = '360px';"
                                                  onmouseout="infraTooltipOcultar();document.getElementById('divInfraTooltip').style.maxWidth = '200px';"
                                                  alt="Ajuda" class="infraImgModulo">
                            </label>
                            <?php require_once 'md_lit_antecedencia_orientacao.php' ?>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <?
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
require_once("md_lit_reinciden_anteceden_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();


