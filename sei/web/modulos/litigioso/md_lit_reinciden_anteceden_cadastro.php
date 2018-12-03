<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 23/04/2018
 * Time: 17:46
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';
    require_once dirname(__FILE__).'/../../../../infra/infra_php/vendor/autoload.php';

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
                                onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']).'\'"
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
    $objEditorRN->validarTagsCriticas(array('png','jpeg' ), $_POST['txtOrientacoes']);
    $objEditorRN->validarTagsCriticas(array('png','jpeg' ), $_POST['txtAnteOrientacoes']);
   // header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_reinciden_anteceden_alterar' . '&acao_origem=' . $_GET['acao']));


    if ($bolAlterar) {
        $_POST['idReincidencia'] = $idReincidencia;
        $_POST['idAntecedencia'] = $idAntecedente;

        $mdLitReincidenAntecedenRN->alterarReincidenciaAntecedentes($_POST);
    } else {

        $mdLitReincidenAntecedenRN->cadastrarReincidenciaAntecedentes($_POST);
    }
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

//JavaScript
require_once 'md_lit_reinciden_anteceden_cadastro_js.php';

//CSS
require_once 'md_lit_reinciden_anteceden_cadastro_css.php';

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmReincidenciaAntecedenteCadastro" method="post" onsubmit="return onSubmit();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&äcao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('auto');
        ?>
        <div id="divReincidencia">

            <fieldset id="fieldRein">
                <legend class="infraLegendObrigatorio">Reincidências Específicas</legend>
                <label id="lblPrazoRein" name="lblPrazoRein" for="txtPrazoRein" class="infraLabelObrigatorio">Prazo em
                    anos: </label>
                <input type="number" class="infraText" id="txtPrazoRein"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" name="txtPrazoRein"
                       value="<?= $prazoRein ?>">
                <div>
                    <label id="lblDecRein" name="lblDecRein" class="infraLabelObrigatorio">Tipos de Decisão: </label>
                    <input type="text" id="txtDecRein" class="infraText"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" name="txtDecRein" value=""/>
                    <input type="hidden" id="hdnTipoDeciRein" class="infraText" name="hdnTipoDeciRein" value=""/>
                    <input type="hidden" id="hdnIdTipoDeciRein" class="infraText" name="hdnIdTipoDeciRein" value=""/>
                    <select style="float: left;" id="selDecRein" name="selDecRein" size="5"
                            multiple="multiple" class="infraSelect">
                        <?= $strItensDecisaoRein; ?>
                    </select>

                    <img id="imgLupaTipoDecisaoRein"
                         onclick="objLupaDecisaoReicidencia.selecionar(700,500);"
                         src="/infra_css/imagens/lupa.gif"
                         alt="Localizar Tipo de Decisão"
                         title="Localizar Tipo de Decisão" class="infraImg"/>

                    <img id="imgExcluirTipoDecisaoRein" onclick="objLupaDecisaoReicidencia.remover();"
                         src="/infra_css/imagens/remover.gif"
                         alt="Remover Tipos de Decisão"
                         title="Remover Tipos de Decisão" class="infraImg"/>
                </div>
                <label id="lblOrienRein" name="lblOrienRein" for="" class="infraLabelObrigatorio">Orientações: </label>
                <div id="orientacoes">
                    <?php require_once 'md_lit_reicidencia_orientacao.php' ?>

                </div>

            </fieldset>
        </div>

        <div id="divAntecedente" style="margin-top: 3%">
            <fieldset id="fieldAntec">
                <legend class="infraLegendObrigatorio">Antecedentes</legend>
                <label id="lblPrazoAntec" name="lblPrazoAntec" for="txtPrazoAntec" class="infraLabelObrigatorio">Prazo
                    em
                    anos: </label>
                <input type="number" class="infraText" id="txtPrazoAntec"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>" name="txtPrazoAntec"
                       value="<?= $prazoAnte ?>">
                <div>
                    <label id="lblDecAntec" name="lblDecAntec" class="infraLabelObrigatorio">Tipos de Decisão: </label>
                    <input type="text" id="txtDecAntec" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                           class="infraText" name="txtDecAntec" value=""/>
                    <input type="hidden" id="hdnTipoDeciAnte" class="infraText" name="hdnTipoDeciAnte" value=""/>
                    <input type="hidden" id="hdnIdTipoDeciAnte" class="infraText" name="hdnIdTipoDeciAnte" value=""/>
                    <select style="float: left;" id="selDecAntec" name="selDecAntec" size="5"
                            multiple="multiple" class="infraSelect">
                        <?= $strItensDecisaoAnte; ?>
                    </select>

                    <img id="imgLupaTipoDecisaoAntec"
                         onclick="objLupaDecisaoAntecedente.selecionar(700,500)"
                         src="/infra_css/imagens/lupa.gif"
                         alt="Localizar Tipo de Decisão"
                         title="Localizar Tipo de Decisão" class="infraImg"/>

                    <img id="imgExcluirTipoDecisaoAntec" onclick="objLupaDecisaoAntecedente.remover();"
                         src="/infra_css/imagens/remover.gif"
                         alt="Remover Tipos de Decisão"
                         title="Remover Tipos de Decisão" class="infraImg"/>
                </div>
                <label id="lblOrienAntec" name="lblOrienAntec" for=""
                       class="infraLabelObrigatorio">Orientações: </label>

                <?php  require_once 'md_lit_antecedencia_orientacao.php' ?>
            </fieldset>
        </div>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

        ?>
    </form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();


