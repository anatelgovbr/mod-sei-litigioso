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

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_lit_situacao_consultar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();

    $strDesabilitar = '';

    $arrComandos = array();
    $strLinkTipoDocumentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=serie_selecionar&tipo_selecao=2&id_object=objLupaTipoDocumento');
    $strLinkAjaxTipoDocumento = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_serie_auto_completar');

    switch ($_GET['acao']) {

        case 'md_lit_situacao_cadastrar':

            $strTitulo = 'Nova Situação';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarSituacaoLitigioso" id="sbmCadastrarSituacaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_situacao_litigioso=' . $_GET['id_situacao_litigioso'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso(null);
            $objSituacaoLitigiosoDTO->setStrNome("");
            $numIdFase = $_POST['selFase'];

            if ($numIdFase != '') {
                $objSituacaoLitigiosoDTO->setNumIdFaseLitigioso($numIdFase);
            } else {
                $objSituacaoLitigiosoDTO->setNumIdFaseLitigioso(null);
            }

            $strItensSelFase = MdLitFaseINT::montarSelectNome(null, null, $_POST['selFase'], $_GET['id_tipo_processo_litigioso']);

            if (isset($_POST['sbmCadastrarSituacaoLitigioso'])) {

                try {


                    $objSituacaoLitigiosoDTO->setStrSinAtivo('S');
                    $objSituacaoLitigiosoDTO->setStrSinInstauracao('N');
                    $objSituacaoLitigiosoDTO->setStrSinConclusiva('N');
                    $objSituacaoLitigiosoDTO->setStrSinIntimacao('N');
                    $objSituacaoLitigiosoDTO->setStrSinDefesa('N');
                    $objSituacaoLitigiosoDTO->setStrSinRecursal('N');
                    $objSituacaoLitigiosoDTO->setStrSinDecisoria('N');
                    $objSituacaoLitigiosoDTO->setStrSinOpcional('N');

                    $objSituacaoLitigiosoDTO->setStrNome($_POST['txtNome']);

                    $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                    $idTipoControle = $_GET['id_tipo_processo_litigioso'];
                    $objSituacaoLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControle);

                    //adicionar os relacionamentos ao DTO
                    $arrRelacionamento = array();

                    //percorre itens enviados do formulario e os adiciona ao array
                    $arrTiposDocsSelecionados = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoDocumento']);
                    $objSituacaoLitigiosoDTO->setArrObjRelSituacaoLitigiosoSerieDTO(array());
                    $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->cadastrarComTipoControle(array('objSituacaoLitigiosoDTO' => $objSituacaoLitigiosoDTO, 'idTipoControle' => $idTipoControle));
                    $idSituacaoLitigioso = $objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso();

                    //depois de cadastrar, set a PK no campo ordem, visa garantir que o registro recem-inserido será o ultimo da lista
                    if ($objSituacaoLitigiosoDTO != null) {
                        $objSituacaoLitigiosoDTO->setNumOrdem($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso());

                        for ($x = 0; $x < count($arrTiposDocsSelecionados); $x++) {

                            $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
                            $objSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso());
                            $objSituacaoLitigiosoSerieDTO->setNumIdSerie($arrTiposDocsSelecionados[$x]);
                            array_push($arrRelacionamento, $objSituacaoLitigiosoSerieDTO);
                        }

                        $objSituacaoLitigiosoDTO->setArrObjRelSituacaoLitigiosoSerieDTO($arrRelacionamento);

                        $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->alterarComTipoControle(array('objSituacaoLitigiosoDTO' => $objSituacaoLitigiosoDTO, 'idTipoControle' => $idTipoControle));
                    }
                    PaginaSEI::getInstance()->adicionarMensagem('Os dados cadastrados foram salvos com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_POST['hdnIdTipoControleLitigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_situacao_litigioso=' . $idSituacaoLitigioso . PaginaSEI::getInstance()->montarAncora($idSituacaoLitigioso)));
                    die;

                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            break;

        case 'md_lit_situacao_alterar':

            $strTitulo = 'Alterar Situação';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarSituacaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_situacao_litigioso'])) {

                //====================== inicio Consultar Tipos de Documentos

                //consultar as unidades relacionadas
                $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
                $objSituacaoLitigiosoSerieDTO->retTodos();
                $objSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($_GET['id_situacao_litigioso']);

                $objRelSituacaoLitigiosoSerieRN = new MdLitRelSituacaoSerieRN();
                $arrTipoDocs = $objRelSituacaoLitigiosoSerieRN->listar($objSituacaoLitigiosoSerieDTO);
                $objSituacaoLitigiosoDTO->setArrObjRelSituacaoLitigiosoSerieDTO($arrTipoDocs);

                $strItensSel = "";
                $objSerieRN = new SerieRN();

                for ($x = 0; $x < count($arrTipoDocs); $x++) {

                    $objSerieDTO = new SerieDTO();
                    $objSerieDTO->retNumIdSerie();
                    $objSerieDTO->retStrNome();

                    $objSerieDTO->setNumIdSerie($arrTipoDocs[$x]->getNumIdSerie());
                    $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

                    $strItensSel .= "<option value='" . $objSerieDTO->getNumIdSerie() . "'>" . $objSerieDTO->getStrNome() . "</option>";
                }

                //==================== Fim Consultar tipos de documentos

                $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($_GET['id_situacao_litigioso']);
                $objSituacaoLitigiosoDTO->retTodos();
                $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO);

                $strItensSelFase = MdLitFaseINT::montarSelectNome(null, null, $objSituacaoLitigiosoDTO->getNumIdFaseLitigioso(), $_GET['id_tipo_processo_litigioso']);

                if ($objSituacaoLitigiosoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }

            } else {
                $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($_POST['hdnIdSituacaoLitigioso']);
                $objSituacaoLitigiosoDTO->setNumIdFaseLitigioso($_POST['selFase']);
                $objSituacaoLitigiosoDTO->setStrNome($_POST['txtNome']);

            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_situacao_litigioso=' . $_GET['id_situacao_litigioso'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso()))) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarSituacaoLitigioso'])) {

                $idTipoControle = $_GET['id_tipo_processo_litigioso'];

                try {

                    $objSituacaoLitigiosoRN = new MdLitSituacaoRN();

                    $objSituacaoLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControle);

                    $valido = $objSituacaoLitigiosoRN->validarCampos($objSituacaoLitigiosoDTO);

                    //REMOVER TODOS OS RELACIONAMENTOS
                    if ($valido) {
                        $objSituacaoLitigiosoRN->removerRelacionamentos($objSituacaoLitigiosoDTO);

                        //READICIONAR TODOS OS RELACIONAMENTOS
                        //adicionar os relacionamentos ao DTO
                        $arrRelacionamento = array();

                        //percorre itens enviados do formulario e os adiciona ao array
                        $arrTiposDocsSelecionados = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoDocumento']);

                        for ($x = 0; $x < count($arrTiposDocsSelecionados); $x++) {
                            $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
                            $objSituacaoLitigiosoSerieDTO->setNumIdSerie($arrTiposDocsSelecionados[$x]);
                            array_push($arrRelacionamento, $objSituacaoLitigiosoSerieDTO);
                        }

                        $objSituacaoLitigiosoDTO->setArrObjRelSituacaoLitigiosoSerieDTO($arrRelacionamento);


                        $strItensSelFase = MdLitFaseINT::montarSelectNome(null, null, $objSituacaoLitigiosoDTO->getNumIdFaseLitigioso(), $_GET['id_tipo_processo_litigioso']);

                        $objSituacaoLitigiosoRN->alterarComTipoControle(array('objSituacaoLitigiosoDTO' => $objSituacaoLitigiosoDTO, 'idTipoControle' => $idTipoControle));
                        PaginaSEI::getInstance()->adicionarMensagem('Os dados foram alterados com sucesso.');
                    }
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_situacao_litigioso=' . $objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso() . '&id_tipo_processo_litigioso=' . $idTipoControle . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso())));
                    die;

                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;
        case 'md_lit_situacao_consultar':

            $strTitulo = 'Consultar Situação';
            if ($_GET['acao_origem'] == 'md_lit_situacao_visualizar_parametrizar') {
                PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
            }

            $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($_GET['id_situacao_litigioso']);
            $objSituacaoLitigiosoDTO->setBolExclusaoLogica(false);
            $objSituacaoLitigiosoDTO->retTodos();
            $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
            $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO);

            $objFaseDTO = new MdLitFaseDTO();
            $objFaseDTO->retTodos();
            $objFaseDTO->setNumIdFaseLitigioso($objSituacaoLitigiosoDTO->getNumIdFaseLitigioso());
            $objFaseRN = new MdLitFaseRN();
            $objFaseDTO = $objFaseRN->consultar($objFaseDTO);

            $strItensSelFase = MdLitFaseINT::montarSelectNome(null, null, $objSituacaoLitigiosoDTO->getNumIdFaseLitigioso(), $objFaseDTO->getNumIdTipoControleLitigioso());
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_situacao_litigioso=' . $objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso() . '&id_tipo_processo_litigioso=' . $objFaseDTO->getNumIdTipoControleLitigioso() . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso()))) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

            $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
            $objSituacaoLitigiosoSerieDTO->retTodos();
            $objSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso());

            $objRelSituacaoLitigiosoSerieRN = new MdLitRelSituacaoSerieRN();
            $arrTipoDocs = $objRelSituacaoLitigiosoSerieRN->listar($objSituacaoLitigiosoSerieDTO);
            $objSituacaoLitigiosoDTO->setArrObjRelSituacaoLitigiosoSerieDTO($arrTipoDocs);

            $strItensSel = "";
            $objSerieRN = new SerieRN();

            for ($x = 0; $x < count($arrTipoDocs); $x++) {

                $objSerieDTO = new SerieDTO();
                $objSerieDTO->retNumIdSerie();
                $objSerieDTO->retStrNome();

                $objSerieDTO->setNumIdSerie($arrTipoDocs[$x]->getNumIdSerie());
                $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

                $strItensSel .= "<option value='" . $objSerieDTO->getNumIdSerie() . "'>" . $objSerieDTO->getStrNome() . "</option>";
            }

            if ($objSituacaoLitigiosoDTO === null) {
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
require_once("md_lit_situacao_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmFaseCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblFase" for="selFase" accesskey="f" class="infraLabelObrigatorio">Fase:</label>
                    <select id="selFase" name="selFase" class="infraText form-control"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <option></option>
                        <?= $strItensSelFase ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Nome da
                        Situação:</label>

                    <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                            value="<?= PaginaSEI::tratarHTML($objSituacaoLitigiosoDTO->getStrNome()) ?>"
                            onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">Tipo de
                    Documento
                    associado:</label>

                <input type="text" id="txtSerie" name="txtSerie" class="infraText form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="input-group">
                    <select id="selDescricao" name="selDescricao" size="8" multiple="multiple"
                            class="infraSelect">
                        <?= $strItensSel ?>
                    </select>
                    <div class="botoes">
                        <img id="imgLupaTipoDocumento" onclick="objLupaTipoDocumento.selecionar(700,500);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Selecionar Tipo de Documento"
                                title="Selecionar Tipo de Documento" class="infraImg"/>

                        <img id="imgExcluirTipoDocumento" onclick="objLupaTipoDocumento.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover Tipo de Documento Selecionado"
                                title="Remover Tipo de Documento Selecionado" class="infraImg"/>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="hdnIdTipoDocumento" name="hdnIdTipoDocumento" value=""/>

        <input type="hidden" id="hdnIdSituacaoLitigioso" name="hdnIdSituacaoLitigioso"
               value="<?= $objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso(); ?>"/>
        <input type="hidden" id="hdnIdTipoControleLitigioso" name="hdnIdTipoControleLitigioso"
               value="<?= $_GET['id_tipo_processo_litigioso']; ?>"/>
        <input type="hidden" id="hdnTipoDocumento" name="hdnTipoDocumento" value="<?= $_POST['hdnTipoDocumento'] ?>"/>
        <input type="hidden" id="hdnIdTipoDocumento" name="hdnIdTipoDocumento"
               value="<?= $_POST['hdnIdTipoDocumento'] ?>"/>
        <input type="hidden" id="hdnIdSerie" name="hdnIdSerie" value="<?= $_POST['hdnIdSerie'] ?>"/>
    </form>
<?
require_once("md_lit_situacao_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
