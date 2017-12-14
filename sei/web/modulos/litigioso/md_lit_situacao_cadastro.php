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

        $arrComandos                 = array();
        $strLinkTipoDocumentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=serie_selecionar&tipo_selecao=2&id_object=objLupaTipoDocumento');
        $strLinkAjaxTipoDocumento    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_serie_auto_completar');

        switch ($_GET['acao']) {

            case 'md_lit_situacao_cadastrar':

                $strTitulo        = 'Nova Situação';
                $arrComandos[]    = '<button type="submit" accesskey="S" name="sbmCadastrarSituacaoLitigioso" id="sbmCadastrarSituacaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $arrComandos[]    = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_situacao_litigioso=' . $_GET['id_situacao_litigioso'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

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
                        #$objSituacaoLitigiosoDTO->setStrSinSuspensiva('N');
                        #$objSituacaoLitigiosoDTO->setStrSinLivre('N');

                        $objSituacaoLitigiosoDTO->setStrNome($_POST['txtNome']);

                        $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                        $idTipoControle         = $_GET['id_tipo_processo_litigioso'];
                        $objSituacaoLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControle);

                        //adicionar os relacionamentos ao DTO
                        $arrRelacionamento = array();

                        //percorre itens enviados do formulario e os adiciona ao array
                        $arrTiposDocsSelecionados = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoDocumento']);
                        $objSituacaoLitigiosoDTO->setArrObjRelSituacaoLitigiosoSerieDTO(array());
                        $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->cadastrarComTipoControle(array('objSituacaoLitigiosoDTO'=>$objSituacaoLitigiosoDTO, 'idTipoControle'=>$idTipoControle));
                        $idSituacaoLitigioso     = $objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso();

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

                            $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->alterarComTipoControle(array('objSituacaoLitigiosoDTO'=>$objSituacaoLitigiosoDTO, 'idTipoControle'=>$idTipoControle));
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

                $strTitulo        = 'Alterar Situação';
                $arrComandos[]    = '<button type="submit" accesskey="S" name="sbmAlterarSituacaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $strDesabilitar   = 'disabled="disabled"';

                if (isset($_GET['id_situacao_litigioso'])) {

                    //====================== inicio Consultar Tipos de Documentos

                    //consultar as unidades relacionadas
                    $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
                    $objSituacaoLitigiosoSerieDTO->retTodos();
                    $objSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($_GET['id_situacao_litigioso']);

                    $objRelSituacaoLitigiosoSerieRN = new MdLitRelSituacaoSerieRN();
                    $arrTipoDocs                    = $objRelSituacaoLitigiosoSerieRN->listar($objSituacaoLitigiosoSerieDTO);
                    $objSituacaoLitigiosoDTO->setArrObjRelSituacaoLitigiosoSerieDTO($arrTipoDocs);

                    $strItensSel = "";
                    $objSerieRN  = new SerieRN();

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
                    $objSituacaoLitigiosoRN  = new MdLitSituacaoRN();
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

                            $objSituacaoLitigiosoRN->alterarComTipoControle(array('objSituacaoLitigiosoDTO'=>$objSituacaoLitigiosoDTO, 'idTipoControle'=>$idTipoControle));
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

                $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($_GET['id_situacao_litigioso']);
                $objSituacaoLitigiosoDTO->setBolExclusaoLogica(false);
                $objSituacaoLitigiosoDTO->retTodos();
                $objSituacaoLitigiosoRN  = new MdLitSituacaoRN();
                $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO);

                $objFaseDTO = new MdLitFaseDTO();
                $objFaseDTO->retTodos();
                $objFaseDTO->setNumIdFaseLitigioso($objSituacaoLitigiosoDTO->getNumIdFaseLitigioso());
                $objFaseRN  = new MdLitFaseRN();
                $objFaseDTO = $objFaseRN->consultar($objFaseDTO);

                $strItensSelFase = MdLitFaseINT::montarSelectNome(null, null, $objSituacaoLitigiosoDTO->getNumIdFaseLitigioso(), $objFaseDTO->getNumIdTipoControleLitigioso());
                $arrComandos[]   = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $objFaseDTO->getNumIdTipoControleLitigioso() . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_fase_litigioso']))) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

                $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
                $objSituacaoLitigiosoSerieDTO->retTodos();
                $objSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso());

                $objRelSituacaoLitigiosoSerieRN = new MdLitRelSituacaoSerieRN();
                $arrTipoDocs                    = $objRelSituacaoLitigiosoSerieRN->listar($objSituacaoLitigiosoSerieDTO);
                $objSituacaoLitigiosoDTO->setArrObjRelSituacaoLitigiosoSerieDTO($arrTipoDocs);

                $strItensSel = "";
                $objSerieRN  = new SerieRN();

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
?>
#lblFase {position:absolute;left:0%;top:0%;width:50%;}
#selFase {position:absolute;left:0%;top:6%;width:50%;}
#lblNome {position:absolute;left:0%;top:14%;width:50%;}
#txtNome {position:absolute;left:0%;top:20%;width:50%;}
#lblDescricao {position:absolute;left:0%;top:30%;}
#txtDescricao {position:absolute;left:0%;top:35%;width:75%;}
#txtSerie {position:absolute;left:0%;top:36%;width:50%;}
#selDescricao {position:absolute;left:0%;top:43.5%;width:75%;}

/* ANTIGA #selDescricao {position:absolute;left:0%;top:50%;width:50%;} */

#imgLupaTipoDocumento {position:absolute;left:76%;top:45%;}
#imgExcluirTipoDocumento {position:absolute;left:76%;top:55%;}
#imgTipoDocumentoAcima {position:absolute;left:51%;top:50%;}
#imgTipoDocumentoAbaixo {position:absolute;left:54%;top:50%;}

/* Add Campos Estória EU4863 PCT8  */
#lblSituacaoPadrao {position:absolute;left:2%;top:72%;}


<?
    PaginaSEI::getInstance()->fecharStyle();
    PaginaSEI::getInstance()->montarJavaScript();
    PaginaSEI::getInstance()->abrirJavaScript();
?>


<?
    PaginaSEI::getInstance()->fecharJavaScript();
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmFaseCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('30em');
    ?>

    <label id="lblFase" for="selFase" accesskey="f" class="infraLabelObrigatorio">Fase:</label>
    <select id="selFase" name="selFase" class="infraText" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
        <option></option>
        <?= $strItensSelFase ?>
    </select>

    <label id="lblNome" for="txtNome" accesskey="f" class="infraLabelObrigatorio">Nome da Situação:</label>

    <input type="text" id="txtNome" name="txtNome" class="infraText"
           value="<?= PaginaSEI::tratarHTML($objSituacaoLitigiosoDTO->getStrNome()) ?>"
           onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

    <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">Tipo de Documento
        associado:</label>

    <input type="text" id="txtSerie" name="txtSerie" class="infraText"
           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

    <select id="selDescricao" name="selDescricao" size="4" multiple="multiple" class="infraSelect">
        <?= $strItensSel ?>
    </select>

    <img id="imgLupaTipoDocumento" onclick="objLupaTipoDocumento.selecionar(700,500);" src="/infra_css/imagens/lupa.gif"
         alt="Selecionar Tipo de Documento"
         title="Selecionar Tipo de Documento" class="infraImg"/>

    <img id="imgExcluirTipoDocumento" onclick="objLupaTipoDocumento.remover();" src="/infra_css/imagens/remover.gif"
         alt="Remover Tipo de Documento Selecionado"
         title="Remover Tipo de Documento Selecionado" class="infraImg"/>

    <input type="hidden" id="hdnIdTipoDocumento" name="hdnIdTipoDocumento" value=""/>

    <input type="hidden" id="hdnIdSituacaoLitigioso" name="hdnIdSituacaoLitigioso"
           value="<?= $objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso(); ?>"/>
    <input type="hidden" id="hdnIdTipoControleLitigioso" name="hdnIdTipoControleLitigioso"
           value="<?= $_GET['id_tipo_processo_litigioso']; ?>"/>
    <input type="hidden" id="hdnTipoDocumento" name="hdnTipoDocumento" value="<?= $_POST['hdnTipoDocumento'] ?>"/>
    <input type="hidden" id="hdnIdTipoDocumento" name="hdnIdTipoDocumento" value="<?= $_POST['hdnIdTipoDocumento'] ?>"/>
    <input type="hidden" id="hdnIdSerie" name="hdnIdSerie" value="<?= $_POST['hdnIdSerie'] ?>"/>
    <? PaginaSEI::getInstance()->fecharAreaDados(); ?>
</form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>
<script type="text/javascript">

    //======================= INICIANDO BLOCO JAVASCRIPT DA PAGINA =======================================
    var objLupaTipoDocumento = null;
    var objAutoCompletarTipoDocumento = null;

    function inicializar() {

        objAutoCompletarTipoDocumento = new infraAjaxAutoCompletar('hdnIdSerie', 'txtSerie', '<?=$strLinkAjaxTipoDocumento?>');
        objAutoCompletarTipoDocumento.limparCampo = true;

        objAutoCompletarTipoDocumento.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtSerie').value;
        };

        objAutoCompletarTipoDocumento.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricao').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert('Tipo de Documento já consta na lista.');
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selDescricao'), descricao, id);

                    objLupaTipoDocumento.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtSerie').value = '';
                document.getElementById('txtSerie').focus();

            }
        };

        objLupaTipoDocumento = new infraLupaSelect('selDescricao', 'hdnTipoDocumento', '<?=$strLinkTipoDocumentoSelecao?>');

        if ('<?=$_GET['acao']?>' == 'md_lit_situacao_cadastrar') {
            document.getElementById('selFase').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_situacao_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }

        infraEfeitoTabelas();

    } //fim funcao inicializar

    function validarCadastro() {

        if (infraTrim(document.getElementById('selFase').value) == '') {
            alert('Informe a Fase.');
            document.getElementById('selFase').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome da Situação.');
            document.getElementById('txtNome').focus();
            return false;
        }

        //tipo de documento associado
        var optionsTipoDocs = document.getElementById('selDescricao').options;

        if (optionsTipoDocs.length == 0) {
            alert('Informe ao menos um tipo de documento associado.');
            document.getElementById('selDescricao').focus();
            return false;
        }

        return true;

    }

    function OnSubmitForm() {
        return validarCadastro();
    }
</script>