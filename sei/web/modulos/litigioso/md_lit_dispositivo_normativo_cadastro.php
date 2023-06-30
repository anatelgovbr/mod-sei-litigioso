<?
/**
 * ANATEL
 *
 * 15/02/2016 - criado por jaqueline.mendes@cast.com.br - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
//        InfraDebug::getInstance()->setBolLigado(true);
//        InfraDebug::getInstance()->setBolDebugInfra(true);
//        InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_lit_dispositivo_normativo_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objDispositivoNormativoLitigiosoDTO = new MdLitDispositivoNormativoDTO();

    $strDesabilitar = '';

    $arrComandos = array();
    //Conduta
    $strLinkCondutaSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_selecionar&tipo_selecao=2&id_object=objLupaConduta');
    $strLinkAjaxConduta = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_conduta_auto_completar');

    //Tipo de Controle
    $strLinkTipoControleSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_selecionar&tipo_selecao=2&id_object=objLupaTipoControle');
    $strLinkAjaxTipoControle = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_tipo_controle_auto_completar');

    //dispositivo normativo
    $strLinkDispositivoNormativoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_selecionar&tipo_selecao=2&id_object=objLupaRevogarDispositivo');
    $strLinkAjaxDispositivoNormativo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dispositivo_auto_completar');

    $revogadoPor = null;


    switch ($_GET['acao']) {
        case 'md_lit_dispositivo_normativo_cadastrar':

            $strTitulo = 'Novo Dispositivo Normativo';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarDispositivoNormativoLitigioso" id="sbmCadastrarDispositivoNormativoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso(null);
            $objDispositivoNormativoLitigiosoDTO->setStrNorma($_POST['txtNorma']);
            $objDispositivoNormativoLitigiosoDTO->setStrUrl($_POST['txtUrlNome']);
            $objDispositivoNormativoLitigiosoDTO->setStrDispositivo($_POST['txtDispositivo']);
            $objDispositivoNormativoLitigiosoDTO->setStrDescricao($_POST['txtDescricaoDispositivo']);

            if (isset($_POST['sbmCadastrarDispositivoNormativoLitigioso'])) {
                try {
                    //SET CONDUTAS
                    $arrObjDispositivoNormativoCondutaDTO = array();
                    $arrCondutas = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnConduta']);

                    for ($x = 0; $x < count($arrCondutas); $x++) {
                        $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
                        $objRelDispositivoNormativoCondutaDTO->setNumIdConduta($arrCondutas[$x]);
                        array_push($arrObjDispositivoNormativoCondutaDTO, $objRelDispositivoNormativoCondutaDTO);
                    }

                    $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoCondutaDTO($arrObjDispositivoNormativoCondutaDTO);


                    //SET TIPOS DE CONTORLE LITIGIOSO
                    $arrObjDispositivoNormativoTipoControleDTO = array();
                    $arrTiposControle = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoControle']);

                    for ($x = 0; $x < count($arrTiposControle); $x++) {
                        $objRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                        $objRelDispositivoNormativoTipoControleDTO->setNumIdTipoControle($arrTiposControle[$x]);
                        array_push($arrObjDispositivoNormativoTipoControleDTO, $objRelDispositivoNormativoTipoControleDTO);
                    }

                    $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoTipoControleDTO($arrObjDispositivoNormativoTipoControleDTO);


                    //set dipositivo normativo revogado
                    $arrObjDispositivoNormativoRevogadoDTO = array();
                    $arrDispositivoRevogado = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnRevogarDispositivo']);

                    for ($x = 0; $x < count($arrDispositivoRevogado); $x++) {
                        $objMdLitRelDispositivoNormativoRevogadoDTO = new MdLitRelDispositivoNormativoRevogadoDTO();
                        $objMdLitRelDispositivoNormativoRevogadoDTO->setNumIdMdLitDispositivoNormativoRevogado($arrDispositivoRevogado[$x]);
                        array_push($arrObjDispositivoNormativoRevogadoDTO, $objMdLitRelDispositivoNormativoRevogadoDTO);
                    }

                    $objDispositivoNormativoLitigiosoDTO->setArrObjRelMdLitRelDispositivoNormativoRevogadoDTO($arrObjDispositivoNormativoRevogadoDTO);

                    // Cadastro
                    $objDispositivoNormativoLitigiosoRN = new MdLitDispositivoNormativoRN();
                    $objDispositivoNormativoLitigiosoDTO = $objDispositivoNormativoLitigiosoRN->cadastrar($objDispositivoNormativoLitigiosoDTO);

                    PaginaSEI::getInstance()->adicionarMensagem('Os dados cadastrados foram salvos com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_dispositivo_normativo_litigioso=' . $objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso() . PaginaSEI::getInstance()->montarAncora($objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;
        case 'md_lit_dispositivo_normativo_alterar':
            $strTitulo = 'Alterar dispositivo Normativo';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterardispositivoNormativoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_dispositivo_normativo_litigioso'])) {

                $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($_GET['id_dispositivo_normativo_litigioso']);
                $objDispositivoNormativoLitigiosoDTO->retTodos();
                $objDispositivoNormativoLitigiosoRN = new MdLitDispositivoNormativoRN();
                $objDispositivoNormativoLitigiosoDTO = $objDispositivoNormativoLitigiosoRN->consultar($objDispositivoNormativoLitigiosoDTO);

                // Consultar Relacionamento com Conduta
                $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
                $objRelDispositivoNormativoCondutaDTO->retTodos();
                $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($_GET['id_dispositivo_normativo_litigioso']);


                $objRelDispositivoNormativoCondutaRN = new MdLitRelDispositivoNormativoCondutaRN();
                $arrCondutas = $objRelDispositivoNormativoCondutaRN->listar($objRelDispositivoNormativoCondutaDTO);

                $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoCondutaDTO($arrCondutas);

                $strItensSelCondutas = "";
                $objCondutaRN = new MdLitCondutaRN();

                for ($x = 0; $x < count($arrCondutas); $x++) {

                    $objCondutaDTO = new MdLitCondutaDTO();
                    $objCondutaDTO->retNumIdCondutaLitigioso();
                    $objCondutaDTO->retStrNome();

                    $objCondutaDTO->setNumIdCondutaLitigioso($arrCondutas[$x]->getNumIdConduta());
                    $objCondutaDTO = $objCondutaRN->consultar($objCondutaDTO);

                    $strItensSelCondutas .= "<option value='" . $objCondutaDTO->getNumIdCondutaLitigioso() . "'>" . PaginaSEI::tratarHTML($objCondutaDTO->getStrNome()) . "</option>";
                }


                //Consultar Relacionamento com Tipo de Controle
                $objRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                $objRelDispositivoNormativoTipoControleDTO->retTodos();
                $objRelDispositivoNormativoTipoControleDTO->setNumIdDispositivoNormativo($_GET['id_dispositivo_normativo_litigioso']);


                $objRelDispositivoNormativoTipoControleRN = new MdLitRelDispositivoNormativoTipoControleRN();
                $arrTiposControle = $objRelDispositivoNormativoTipoControleRN->listar($objRelDispositivoNormativoTipoControleDTO);

                $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoTipoControleDTO($arrTiposControle);

                $strItensSelTiposControle = "";
                $objTipoControleRN = new MdLitTipoControleRN();

                for ($x = 0; $x < count($arrTiposControle); $x++) {

                    $objTipoControleDTO = new MdLitTipoControleDTO();
                    $objTipoControleDTO->retNumIdTipoControleLitigioso();
                    $objTipoControleDTO->retStrSigla();

                    $objTipoControleDTO->setNumIdTipoControleLitigioso($arrTiposControle[$x]->getNumIdTipoControle());
                    $objTipoControleDTO = $objTipoControleRN->consultar($objTipoControleDTO);

                    $strItensSelTiposControle .= "<option value='" . $objTipoControleDTO->getNumIdTipoControleLitigioso() . "'>" . PaginaSEI::tratarHTML($objTipoControleDTO->getStrSigla()) . "</option>";
                }

                //Consultar dispositivos normativos revogados por esse dispositivo
                $strItensSelRevogarDispositivo = "";
                $strItensSelRevogarDispositivo = MdLitRelDispositivoNormativoRevogadoINT::montarItemSelecionado($_GET['id_dispositivo_normativo_litigioso']);

                if ($objDispositivoNormativoLitigiosoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

            } else {

                $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($_POST['hdnIdDispositivoNormativoLitigioso']);
                $objDispositivoNormativoLitigiosoDTO->setStrNorma($_POST['txtNorma']);
                $objDispositivoNormativoLitigiosoDTO->setStrUrl($_POST['txtUrlNome']);
                $objDispositivoNormativoLitigiosoDTO->setStrDispositivo($_POST['txtDispositivo']);
                $objDispositivoNormativoLitigiosoDTO->setStrDescricao($_POST['txtDescricaoDispositivo']);


                $objDispositivoNormativoLitigiosoRN = new MdLitDispositivoNormativoRN();

                //REMOVER TODOS OS RELACIONAMENTOS
                $objDispositivoNormativoLitigiosoRN->removerRelacionamentos($objDispositivoNormativoLitigiosoDTO);

                //Add Conduta

                //SET CONDUTAS
                $arrObjDispositivoNormativoCondutaDTO = array();
                $arrCondutas = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnConduta']);

                for ($x = 0; $x < count($arrCondutas); $x++) {
                    $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
                    $objRelDispositivoNormativoCondutaDTO->setNumIdConduta($arrCondutas[$x]);
                    array_push($arrObjDispositivoNormativoCondutaDTO, $objRelDispositivoNormativoCondutaDTO);
                }

                $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoCondutaDTO($arrObjDispositivoNormativoCondutaDTO);

                //SET TIPO DE CONTROLE
                $arrObjDispositivoNormativoTipoControleDTO = array();
                $arrTiposControle = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoControle']);

                for ($x = 0; $x < count($arrTiposControle); $x++) {
                    $objRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                    $objRelDispositivoNormativoTipoControleDTO->setNumIdTipoControle($arrTiposControle[$x]);
                    array_push($arrObjDispositivoNormativoTipoControleDTO, $objRelDispositivoNormativoTipoControleDTO);
                }

                $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoTipoControleDTO($arrObjDispositivoNormativoTipoControleDTO);

                //set dipositivo normativo revogado
                $arrObjDispositivoNormativoRevogadoDTO = array();
                $arrDispositivoRevogado = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnRevogarDispositivo']);

                for ($x = 0; $x < count($arrDispositivoRevogado); $x++) {
                    $objMdLitRelDispositivoNormativoRevogadoDTO = new MdLitRelDispositivoNormativoRevogadoDTO();
                    $objMdLitRelDispositivoNormativoRevogadoDTO->setNumIdMdLitDispositivoNormativoRevogado($arrDispositivoRevogado[$x]);
                    array_push($arrObjDispositivoNormativoRevogadoDTO, $objMdLitRelDispositivoNormativoRevogadoDTO);
                }

                $objDispositivoNormativoLitigiosoDTO->setArrObjRelMdLitRelDispositivoNormativoRevogadoDTO($arrObjDispositivoNormativoRevogadoDTO);


            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso()))) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterardispositivoNormativoLitigioso'])) {
                try {
                    $objDispositivoNormativoLitigiosoRN = new MdLitDispositivoNormativoRN();
                    $objDispositivoNormativoLitigiosoRN->alterar($objDispositivoNormativoLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Os dados foram alterados com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_POST['hdnIdTipoControleLitigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso())));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'md_lit_dispositivo_normativo_consultar':
            $strTitulo = 'Consultar Dispositivo Normativo';
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_dispositivo_normativo_litigioso']))) . '\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($_GET['id_dispositivo_normativo_litigioso']);
            $objDispositivoNormativoLitigiosoDTO->setBolExclusaoLogica(false);
            $objDispositivoNormativoLitigiosoDTO->retTodos();
            $objDispositivoNormativoLitigiosoRN = new MdLitDispositivoNormativoRN();
            $objDispositivoNormativoLitigiosoDTO = $objDispositivoNormativoLitigiosoRN->consultar($objDispositivoNormativoLitigiosoDTO);

            // Consultar Relacionamento com Conduta
            $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
            $objRelDispositivoNormativoCondutaDTO->retTodos();
            $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($_GET['id_dispositivo_normativo_litigioso']);


            $objRelDispositivoNormativoCondutaRN = new MdLitRelDispositivoNormativoCondutaRN();
            $arrCondutas = $objRelDispositivoNormativoCondutaRN->listar($objRelDispositivoNormativoCondutaDTO);

            $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoCondutaDTO($arrCondutas);

            $strItensSelCondutas = "";
            $objCondutaRN = new MdLitCondutaRN();

            for ($x = 0; $x < count($arrCondutas); $x++) {

                $objCondutaDTO = new MdLitCondutaDTO();
                $objCondutaDTO->retNumIdCondutaLitigioso();
                $objCondutaDTO->retStrNome();

                $objCondutaDTO->setNumIdCondutaLitigioso($arrCondutas[$x]->getNumIdConduta());
                $objCondutaDTO = $objCondutaRN->consultar($objCondutaDTO);

                $strItensSelCondutas .= "<option value='" . $objCondutaDTO->getNumIdCondutaLitigioso() . "'>" . PaginaSEI::tratarHTML($objCondutaDTO->getStrNome()) . "</option>";
            }


            //Consultar Relacionamento com Tipo de Controle
            $objRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
            $objRelDispositivoNormativoTipoControleDTO->retTodos();
            $objRelDispositivoNormativoTipoControleDTO->setNumIdDispositivoNormativo($_GET['id_dispositivo_normativo_litigioso']);


            $objRelDispositivoNormativoTipoControleRN = new MdLitRelDispositivoNormativoTipoControleRN();
            $arrTiposControle = $objRelDispositivoNormativoTipoControleRN->listar($objRelDispositivoNormativoTipoControleDTO);

            $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoTipoControleDTO($arrTiposControle);

            $strItensSelTiposControle = "";
            $objTipoControleRN = new MdLitTipoControleRN();

            for ($x = 0; $x < count($arrTiposControle); $x++) {

                $objTipoControleDTO = new MdLitTipoControleDTO();
                $objTipoControleDTO->retNumIdTipoControleLitigioso();
                $objTipoControleDTO->retStrSigla();

                $objTipoControleDTO->setNumIdTipoControleLitigioso($arrTiposControle[$x]->getNumIdTipoControle());
                $objTipoControleDTO = $objTipoControleRN->consultar($objTipoControleDTO);

                $strItensSelTiposControle .= "<option value='" . $objTipoControleDTO->getNumIdTipoControleLitigioso() . "'>" . PaginaSEI::tratarHTML($objTipoControleDTO->getStrSigla()) . "</option>";
            }

            //Consultar dispositivos normativos revogados por esse dispositivo
            $strItensSelRevogarDispositivo = "";
            $strItensSelRevogarDispositivo = MdLitRelDispositivoNormativoRevogadoINT::montarItemSelecionado($_GET['id_dispositivo_normativo_litigioso']);

            //revogadoPor
            $revogadoPor = MdLitRelDispositivoNormativoRevogadoINT::montarRevogadoPor($_GET['id_dispositivo_normativo_litigioso']);;

            if ($objDispositivoNormativoLitigiosoDTO === null) {
                throw new InfraException("Registro não encontrado.");
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
require_once("md_lit_dispositivo_normativo_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
<form id="frmdispositivoNormativoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <label id="lblNorma" for="txtNorma" accesskey="f" class="infraLabelObrigatorio">Norma:</label>
                <input type="text" id="txtNorma" name="txtNorma" class="infraText form-control"
                        value="<?= PaginaSEI::tratarHTML($objDispositivoNormativoLitigiosoDTO->getStrNorma()); ?>"
                        onkeypress="return infraMascaraTexto(this,event,150);"
                        maxlength="150" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <label id="lblUrlNome" for="txtUrlNome" accesskey="f" class="infraLabel">URL da Norma:</label>
                <input type="text" id="txtUrlNome" name="txtUrlNome" class="infraText form-control"
                        value="<?= PaginaSEI::tratarHTML($objDispositivoNormativoLitigiosoDTO->getStrUrl()); ?>"
                        onkeypress="return infraMascaraTexto(this,event,2083);" maxlength="2083"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <label id="lblDispositivo" for="txtDispositivo" accesskey="f"
                        class="infraLabelObrigatorio">Dispositivo:</label>
                <input type="text" id="txtDispositivo" name="txtDispositivo" class="infraText form-control"
                        value="<?= PaginaSEI::tratarHTML($objDispositivoNormativoLitigiosoDTO->getStrDispositivo()); ?>"
                        onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <label id="lblDescricaoDispositivo" for="txtDescricaoDispositivo" accesskey="q"
                        class="infraLabelObrigatorio">Descrição
                    do Dispositivo:</label>
                <textarea type="text" id="txtDescricaoDispositivo" rows="3" name="txtDescricaoDispositivo"
                            class="infraText form-control"
                            onkeypress="return infraMascaraTexto(this,event,2000);"
                            maxlength="2000"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?php ?><?= PaginaSEI::tratarHTML($objDispositivoNormativoLitigiosoDTO->getStrDescricao()); ?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
            <label id="lblDescricaoConduta" for="txtConduta" accesskey="q" class="infraLabelObrigatorio">Condutas
                associadas:</label>
            <input type="text" id="txtConduta" name="txtConduta" class="infraText form-control"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <div class="input-group mb-3">
                    <select id="selDescricaoConduta" name="selDescricaoConduta" size="8" multiple="multiple"
                            class="infraSelect form-control">
                        <?= $strItensSelCondutas ?>
                    </select>
                    <div class="botoes">
                        <img id="imgLupaConduta" onclick="objLupaConduta.selecionar(700,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Localizar Conduta Associada"
                                title="Localizar Conduta Associada" class="infraImg"/>

                        <img id="imgExcluirConduta" onclick="objLupaConduta.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Condutas Associadas"
                                title="Remover Condutas Associadas" class="infraImg"/>
                    </div>
                    <input type="hidden" id="hdnIdConduta" name="hdnIdConduta"
                            value="<?= $_POST['hdnIdConduta'] ?>"/>
                    <input type="hidden" id="hdnConduta" name="hdnConduta" value="<?= $_POST['hdnConduta'] ?>"/>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
            <label id="lblDescricaoTipoControleLitigioso" for="txtTipoControle" accesskey="q"
                    class="infraLabel">Tipos
                de Controle Litigioso associados:</label>
            <input type="text" id="txtTipoControle" name="txtTipoControle" class="infraText form-control"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <div class="input-group mb-3">
                    <select id="selDescricaoTipoControle" name="selDescricaoTipoControle" size="8"
                            multiple="multiple"
                            class="infraSelect form-control">
                        <?= $strItensSelTiposControle ?>
                    </select>
                    <div class="botoes">
                        <img id="imgLupaTipoControleLitigioso" onclick="objLupaTipoControle.selecionar(900,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Localizar Tipo de Controle Associado"
                                title="Localizar Tipo de Controle Associado" class="infraImg"/>

                        <img id="imgExcluirTipoControleLitigioso" onclick="objLupaTipoControle.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Tipo de Controle Associados"
                                title="Remover Tipo de Controle Associados" class="infraImg"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
            <label id="lblRevogarDispositivo" for="txtRevogarDispositivo" class="infraLabel">Revogar
                dispositivo:</label>

            <input type="text" id="txtRevogarDispositivo" name="txtRevogarDispositivo"
                    class="infraText form-control"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
            <div class="form-group">
                <div class="input-group mb-3">
                    <select id="selRevogarDispositivo" name="selRevogarDispositivo" size="8" multiple="multiple"
                            class="infraSelect form-control">
                        <?= $strItensSelRevogarDispositivo ?>
                    </select>
                    <div class="botoes">
                        <img id="imgLupaRevogarDispositivo" onclick="objLupaRevogarDispositivo.selecionar(900,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Localizar Tipo de Controle Associado"
                                title="Localizar Tipo de Controle Associado" class="infraImg"/>
                        <img id="imgExcluirRevogarDispositivo" onclick="objLupaRevogarDispositivo.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Dispositivos Revogados"
                                title="Remover Dispositivos Revogados" class="infraImg"/>
                    </div>
                    <input type="hidden" id="hdnIdRevogarDispositivo" name="hdnIdRevogarDispositivo"
                            value="<?= $_POST['hdnIdRevogarDispositivo'] ?>"/>
                    <input type="hidden" id="hdnRevogarDispositivo" name="hdnRevogarDispositivo"
                            value="<?= $_POST['hdnRevogarDispositivo'] ?>"/>
                </div>
            </div>
        </div>
    </div>
    <?php if ($revogadoPor) { ?>
        <div class="row linha">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <label id="lblRevogadoPor" for="txtRevogadoPor" class="infraLabel">Revogado por:</label>
                <input type="text" value="<?= $revogadoPor ?>" class="infraText form-control" id="txtRevogadoPor"/>
            </div>
        </div>
    <?php } ?>

    <input type="hidden" id="hdnIdTipoControle" name="hdnIdTipoControle" value="<?= $_POST['hdnIdTipoControle'] ?>"/>
    <input type="hidden" id="hdnTipoControle" name="hdnTipoControle" value="<?= $_POST['hdnTipoControle'] ?>"/>
    <input type="hidden" id="hdnIdDispositivoNormativoLitigioso" name="hdnIdDispositivoNormativoLitigioso"
           value="<?= $objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso(); ?>"/>

</form>
<?
require_once("md_lit_dispositivo_normativo_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>

