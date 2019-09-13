<?
    /**
     * ANATEL
     *
     * 20/05/2016 - criado por alan.campos@castgroup.com.br - CAST
     *
     */

    $strLinkValidarEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_especie_decisao');

    try {
        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();

        //////////////////////////////////////////////////////////////////////////////
        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->limpar();
        //////////////////////////////////////////////////////////////////////////////

        SessaoSEI::getInstance()->validarLink();

        PaginaSEI::getInstance()->verificarSelecao('md_lit_tipo_decisao_selecionar');

        SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

        $objTipoDecisaoDTO = new MdLitTipoDecisaoDTO();

        $strDesabilitar = '';

        $arrComandos = array();

        switch ($_GET['acao']) {
            case 'md_lit_tipo_decisao_cadastrar':
                $strTitulo     = 'Novo Tipo de Decisão';
                $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEspecies" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '#ID-' . $_GET['id_tipo_procedimento'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


                $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso(null);
                $objTipoDecisaoDTO->setStrNome($_POST['txtNome']);
                $objTipoDecisaoDTO->setStrDescricao($_POST['txaDescricao']);

                $arrObjEspecieDecisaoDTO = array();
                $arrEspecies             = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnEspecies']);

                for ($x = 0; $x < count($arrEspecies); $x++) {
                    $objRelTipoEspecieDecisaoLitigiosoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                    $objRelTipoEspecieDecisaoLitigiosoDTO->setNumIdEspecieDecisaoLitigioso($arrEspecies[$x]);
                    array_push($arrObjEspecieDecisaoDTO, $objRelTipoEspecieDecisaoLitigiosoDTO);
                }

                $objTipoDecisaoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoDTO);

                $objTipoDecisaoDTO->setStrSinAtivo('S');

                if (isset($_POST['sbmCadastrarEspecies'])) {
                    try {
                        $objTipoDecisaoRN  = new MdLitTipoDecisaoRN();
                        $objTipoDecisaoDTO = $objTipoDecisaoRN->cadastrar($objTipoDecisaoDTO);
                        PaginaSEI::getInstance()->setStrMensagem('Tipo de Decisão cadastrado com sucesso.');
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_decisao_litigioso=' . $objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso() . '#ID-' . $objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso()));
                        die;
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                }
                break;

            case 'md_lit_tipo_decisao_alterar':
                $strTitulo     = 'Alterar Tipo de Decisão';
                $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';


                if (isset($_GET['id_tipo_decisao_litigioso'])) {
                    $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($_GET['id_tipo_decisao_litigioso']);
                    $objTipoDecisaoDTO->retTodos();
                    $objTipoDecisaoRN  = new MdLitTipoDecisaoRN();
                    $objTipoDecisaoDTO = $objTipoDecisaoRN->consultar($objTipoDecisaoDTO);
                    if ($objTipoDecisaoDTO == null) {
                        throw new InfraException("Registro não encontrado.");
                    }

                    $objRelEspecieDecisaoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                    $objRelEspecieDecisaoDTO->retTodos();
                    $objRelEspecieDecisaoDTO->retStrNomeEspecie();
                    $objRelEspecieDecisaoDTO->setNumIdTipoDecisaoLitigioso($_GET['id_tipo_decisao_litigioso']);

                    PaginaSEI::getInstance()->prepararOrdenacao($objRelEspecieDecisaoDTO, 'NomeEspecie', InfraDTO::$TIPO_ORDENACAO_ASC);

                    $objRelTipoEspecieDecisaoLitigiosoRN = new MdLitRelTipoEspecieDecisaoRN();
                    $arrEspecies                         = $objRelTipoEspecieDecisaoLitigiosoRN->listar($objRelEspecieDecisaoDTO);

                    $objTipoDecisaoDTO->setArrObjRelEspecieLitigiosoDTO($arrEspecies);



                    $strItensSelEspecies = "";
                    for ($x = 0; $x < count($arrEspecies); $x++) {
                        $strItensSelEspecies .= "<option value='" . $arrEspecies[$x]->getNumIdEspecieDecisaoLitigioso() . "'>" . PaginaSEI::tratarHTML($arrEspecies[$x]->getStrNomeEspecie()) . "</option>";

                    }

                } else {

                    $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($_POST['hdnIdTipoDecisaoLitigioso']);
                    $objTipoDecisaoDTO->setStrNome($_POST['txtNome']);
                    $objTipoDecisaoDTO->setStrDescricao($_POST['txaDescricao']);

                    //Set Especies
                    $arrObjEspecieDecisaoDTO = array();
                    $arrEspecies             = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnEspecies']);


                    for ($x = 0; $x < count($arrEspecies); $x++) {
                        $objRelTipoEspecieDecisaoLitigiosoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                        $objRelTipoEspecieDecisaoLitigiosoDTO->setNumIdEspecieDecisaoLitigioso($arrEspecies[$x]);
                        array_push($arrObjEspecieDecisaoDTO, $objRelTipoEspecieDecisaoLitigiosoDTO);
                    }

                    $objTipoDecisaoDTO->setArrObjRelEspecieLitigiosoDTO($arrObjEspecieDecisaoDTO);

                }


                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '#ID-' . $objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso() . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


                if (isset($_POST['sbmAlterarTipoDecisaoLitigioso'])) {
                    try {
                        $objTipoDecisaoRN = new MdLitTipoDecisaoRN();

                        $objTipoDecisaoDTO = $objTipoDecisaoRN->alterar($objTipoDecisaoDTO);

                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso())));
                        die;
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                }

                break;

            case 'md_lit_tipo_decisao_consultar':
                $strTitulo      = "Consultar Tipo de Decisão";
                $arrComandos[]  = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '#ID-' . $_GET['id_tipo_decisao_litigioso'] . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
                $strDesabilitar = 'disabled="disabled"';
                if (isset($_GET['id_tipo_decisao_litigioso'])) {
                    $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($_GET['id_tipo_decisao_litigioso']);
                    $objTipoDecisaoDTO->retTodos();
                    $objTipoDecisaoRN  = new MdLitTipoDecisaoRN();
                    $objTipoDecisaoDTO = $objTipoDecisaoRN->consultar($objTipoDecisaoDTO);

                    if ($objTipoDecisaoDTO == null) {
                        throw new InfraException("Registro não encontrado.");
                    }

                    $objRelTipoEspecieDecisaoLitigiosoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                    $objRelTipoEspecieDecisaoLitigiosoDTO->retTodos();
                    $objRelTipoEspecieDecisaoLitigiosoDTO->retStrNomeEspecie();
                    $objRelTipoEspecieDecisaoLitigiosoDTO->setNumIdTipoDecisaoLitigioso($_GET['id_tipo_decisao_litigioso']);

                    PaginaSEI::getInstance()->prepararOrdenacao($objRelTipoEspecieDecisaoLitigiosoDTO, 'NomeEspecie', InfraDTO::$TIPO_ORDENACAO_ASC);

                    $objRelTipoEspecieDecisaoLitigiosoRN = new MdLitRelTipoEspecieDecisaoRN();
                    $arrEspecies                         = $objRelTipoEspecieDecisaoLitigiosoRN->listar($objRelTipoEspecieDecisaoLitigiosoDTO);

                    $objTipoDecisaoDTO->setArrObjRelEspecieLitigiosoDTO($arrEspecies);

                    $strItensSelEspecies = "";
                    for ($x = 0; $x < count($arrEspecies); $x++) {
                        $strItensSelEspecies .= "<option value='" . $arrEspecies[$x]->getNumIdEspecieDecisaoLitigioso() . "'>" . $arrEspecies[$x]->getStrNomeEspecie() . "</option>";
                    }

                }

                break;

            default:
                throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        }

        $strLinkEspeciesSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_especie_decisao_selecionar&tipo_selecao=2&id_object=objLupaEspecies');
        $strLinkAjaxEspecies    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_especie_decisao_auto_completar');

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());


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

    #lblNome {position:absolute;left:0%;top:0%;}
    #txtNome {position:absolute;left:0%;top:40%;width:74.5%;}

    #lblDescricao {position:absolute;left:0%;top:0%;}
    #txaDescricao {position:absolute;left:0%;top:30%;width:74.5%;}

    #lblEspecies {position:absolute;left:0%;top:5%;}
    #txtEspecies {position:absolute;left:0%;top:20%;width:74.5%;}
    #selEspecies {position:absolute;left:0%;top:40%;width:75%;}
    #imgLupaEspecies {position:absolute;left:76%;top:40%;}
    #imgExcluirEspecies {position:absolute;left:76%;top:60%;}
    #imgEspeciesAcima {position:absolute;left:76%;top:60%;}
    #imgEspeciesAbaixo {position:absolute;left:79%;top:60%;}


    #fldNivelAcessoPermitido {position:absolute;left:0%;top:5%;height:75%;width:33.5%;}
    #divSinSigilosoPermitido {position:absolute;left:30%;top:30%;}
    #divSinRestritoPermitido {position:absolute;left:30%;top:50%;}
    #divSinPublicoPermitido {position:absolute;left:30%;top:70%;}

    #fldNivelAcessoSugestao {position:absolute;left:40%;top:5%;height:75%;width:33.5%;}
    #divOptSigilosoSugestao {position:absolute;left:30%;top:30%;}
    #divOptRestritoSugestao {position:absolute;left:30%;top:50%;}
    #divOptPublicoSugestao {position:absolute;left:30%;top:70%;}

    #divGrauSigilo {<?= $strDisplayGrauSigilo ?>}
    #lblGrauSigilo {position:absolute;left:0%;top:0%;}
    #selGrauSigilo {position:absolute;left:0%;top:40%;width:35%;}


<?
    PaginaSEI::getInstance()->fecharStyle();
    PaginaSEI::getInstance()->montarJavaScript();
    PaginaSEI::getInstance()->abrirJavaScript();
?>
    //<script>
    var objLupaEspecies = null;
    var objAutoCompletarEspecies = null;
    var objAjaxHipoteseLegal = null;

    function inicializar(){
    if ('<?= $_GET['acao'] ?>'=='md_lit_tipo_decisao_cadastrar'){
    document.getElementById('txtNome').focus();
    } else if ('<?= $_GET['acao'] ?>'=='md_lit_tipo_decisao_consultar'){
    infraDesabilitarCamposAreaDados();
    return;
    }

    objAutoCompletarEspecies = new infraAjaxAutoCompletar('hdnIdEspecies','txtEspecies','<?= $strLinkAjaxEspecies ?>');
    objAutoCompletarEspecies.limparCampo = true;
    objAutoCompletarEspecies.tamanhoMinimo = 3;
    objAutoCompletarEspecies.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtEspecies').value;
    };

    objAutoCompletarEspecies.processarResultado = function(id,descricao,complemento){
        if (id!=''){
            var options = document.getElementById('selEspecies').options;
            var arrEspecies = new Array();

            for(var i=0;i < options.length;i++){
                if (options[i].value == id){
                    alert('Espécie já consta na lista.');
                    break;
                }

                //recupera os ids ja vinculados
                arrEspecies.push(options[i].value);
            }

            arrEspecies.push(id);
            var objValidado = validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies);
            if(objValidado.valid == false){
                alert(objValidado.mensagem);
                $('[name="txtEspecies"]').val('');
                return false;
            }

            if (i==options.length){
                for(i=0;i < options.length;i++){
                    options[i].selected = false;
                }
                var desc = $("<pre>").html(descricao).text();
                opt = infraSelectAdicionarOption(document.getElementById('selEspecies'),desc,id);
                objLupaEspecies.atualizar();
                opt.selected = true;
            }

            document.getElementById('txtEspecies').value = '';
            document.getElementById('txtEspecies').focus();
        }
    };

    objLupaEspecies = new infraLupaSelect('selEspecies','hdnEspecies','<?= $strLinkEspeciesSelecao ?>');


    formatarExibicaoNivelAcesso();

    infraEfeitoTabelas();
    }

    function OnSubmitForm() {

    if (validarForm()){
    return true;
    }
    return false;
    }

    function validarForm() {

    if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
    }

    if(validarTiposGestaoMulta() == false){
        return false;
    }
    /*
    if (infraTrim(document.getElementById('txaDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txaDescricao').focus();
    return false;
    }
    */

    return true;
    }

    function formatarExibicaoNivelAcesso(){

    if (!document.getElementById('chkSinSigilosoPermitido').checked){
    document.getElementById('optSigilosoSugestao').checked = false;
    document.getElementById('optSigilosoSugestao').disabled = true;
    document.getElementById('spnSigilosoSugestao').disabled = true;
    }else{
    document.getElementById('optSigilosoSugestao').disabled = false;
    document.getElementById('spnSigilosoSugestao').disabled = false;
    }

    if (!document.getElementById('chkSinRestritoPermitido').checked){
    document.getElementById('optRestritoSugestao').checked = false;
    document.getElementById('optRestritoSugestao').disabled = true;
    document.getElementById('spnRestritoSugestao').disabled = true;
    }else{
    document.getElementById('optRestritoSugestao').disabled = false;
    document.getElementById('spnRestritoSugestao').disabled = false;
    }

    if (!document.getElementById('chkSinPublicoPermitido').checked){
    document.getElementById('optPublicoSugestao').checked = false;
    document.getElementById('optPublicoSugestao').disabled = true;
    document.getElementById('spnPublicoSugestao').disabled = true;
    }else{
    document.getElementById('optPublicoSugestao').disabled = false;
    document.getElementById('spnPublicoSugestao').disabled = false;
    }

    }

    function alterarNivelAcessoSugerido(){

<? if ($numHabilitarGrauSigilo) { ?>
    if (document.getElementById('optSigilosoSugestao').checked){
    document.getElementById('divGrauSigilo').style.display='block';
    }else{
    document.getElementById('selGrauSigilo').options[0].selected = true;
    document.getElementById('divGrauSigilo').style.display='none';
    }
<? } ?>

<? if ($numHabilitarHipoteseLegal) { ?>
    objAjaxHipoteseLegal.executar();
<? } ?>
    }


    function validarTiposGestaoMulta(){
        var options = document.getElementById('selEspecies').options;
        var arrEspecies = new Array();

        for(var i=0;i < options.length;i++){
            arrEspecies.push(options[i].value);
        }

        var objValidado = validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies);
        if(objValidado.valid == false){
            var menssagem = "Não é possivel associar Espécies de Decisão com Indicação de Multa de tipos distintos (Gestão por Integração e Apenas Indicação de Valor).\n" +
                "\n" +
                "Revise as Espécies de Decisão abaixo listadas que está pretendendo associar:\n\n";


            //mostrar a lista de especies conflitantes
            var arrEspeciesDto = recuperarEspecies(arrEspecies);
            var especies = '';
            $.each(arrEspeciesDto, function(key, value) {
                //se tem tipo de indicação de multa entra na lista
                if(parseInt($(value).find('StaTipoIndicacaoMulta').text()) == $(value).find('StaTipoIndicacaoMulta').text()){
                    especies += ' - '+ $(value).find('Nome').text() +' \n';
                }
            });

            menssagem = menssagem + especies;
            alert(menssagem);
            $('[name="txtEspecies"]').val('');
            return false;
        }

        return true;
    }

    function recuperarEspecies(arrEspecies){
        var dados;
        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_recuperar_especie_decisao') ?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId':arrEspecies},
            success: function (data) {
                if($(data).find('MdLitEspecieDecisaoDTO').length > 0){
                    dados = $(data).find('MdLitEspecieDecisaoDTO');
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o validação do do SEI: " + msgError.responseText;
                console.log(msgCommit);
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

        return dados;
    }

    /**
     * Validação para nao permitir que um tipo de decisão tenha associada especies com tipo de miltas diferentes, por integracao e por valor
     * @param arrEspecies
     * @returns {boolean}
     */
    function validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies) {
        var valid = true;

        $.ajax({
            type: "POST",
            url: "<?= $strLinkValidarEspecieDecisao ?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId': arrEspecies},
            success: function (data) {
                if ($(data).find('resultado').text() == '0') {
                    valid = false;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o validação do do SEI: " + msgError.responseText;
                console.log(msgCommit);
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

        return {'valid': valid, 'mensagem': "Não é possivel associar Espécies de Decisão com Indicação de Multa de tipos distintos (Gestão por Integração e Apenas Indicação de Valor).\n" +
            "\nEscolha Espécies de Decisão diferentes ou entre em contato com o Gestor do SEI para dúvidas a respeito."
        };
    }

    //</script>
<?
    PaginaSEI::getInstance()->fecharJavaScript();
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTipoProcedimentoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div id="divNome" class="infraAreaDados" style="height:4.5em;">
            <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span
                    class="infraTeclaAtalho">N</span>ome:</label>
            <input <?= $strDesabilitar; ?> type="text" id="txtNome" name="txtNome" class="infraText"
                                           value="<?= PaginaSEI::tratarHTML($objTipoDecisaoDTO->getStrNome()); ?>"
                                           onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        </div>

        <div id="divDescricao" class="infraAreaDados" style="height:6em;">
            <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelOpcional"><span
                    class="infraTeclaAtalho">D</span>escrição:</label>
            <textarea <?= $strDesabilitar; ?> id="txaDescricao" name="txaDescricao" rows="2" class="infraTextarea"
                                              onkeypress="return infraLimitarTexto(this,event,250);"
                                              tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= $objTipoDecisaoDTO->getStrDescricao(); ?></textarea>
        </div>

        <div id="divEspecies" class="infraAreaDados" style="height:12em;">
            <label id="lblEspecies" for="selEspecies" accesskey="" class="infraLabelOpcional">Espécies de Decisão
                associadas:</label>
            <input <?= $strDesabilitar; ?> type="text" id="txtEspecies" name="txtEspecies" class="infraText"
                                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            <select <?= $strDesabilitar; ?> id="selEspecies" name="selEspecies" size="4" multiple="multiple"
                                            class="infraSelect">
                <?= $strItensSelEspecies ?>
            </select>
            <img id="imgLupaEspecies" onclick="objLupaEspecies.selecionar(700,500);" src="/infra_css/imagens/lupa.gif"
                 alt="Localizar Espécies de Decisão" title="Localizar Espécies de Decisão" class="infraImg"/>
            <img id="imgExcluirEspecies" onclick="objLupaEspecies.remover();" src="/infra_css/imagens/remover.gif"
                 alt="Remover Espécies de Decisão" title="Remover Espécies de Decisão" class="infraImg"/>
            <input type="hidden" id="hdnIdEspecies" name="hdnIdEspecies" value=""/>
        </div>


        <input type="hidden" id="hdnIdTipoDecisaoLitigioso" name="hdnIdTipoDecisaoLitigioso"
               value="<?= $objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso(); ?>"/>
        <input type="hidden" id="hdnEspecies" name="hdnEspecies" value="<?= $_POST['hdnEspecies'] ?>"/>

        <?
            PaginaSEI::getInstance()->montarAreaDebug();
        ?>
    </form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>