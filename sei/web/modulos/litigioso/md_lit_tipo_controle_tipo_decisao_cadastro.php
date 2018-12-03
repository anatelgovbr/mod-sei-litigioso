<?
    /**
     * ANATEL
     *
     * 01/06/2016 - criado por alan.campos@castgroup.com.br - CAST
     *
     */

    try {

        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();

        SessaoSEI::getInstance()->validarLink();

        PaginaSEI::getInstance()->verificarSelecao('md_lit_tipo_decisao_selecionar');

        SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

        $arrComandos = array();

        //TipoDecisao
        $strLinkTipoDecisaoSelecao       = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_decisao_selecionar&tipo_selecao=2&id_object=objLupaTipoDecisao');
        $strLinkAjaxComboEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_especie_decisao_montar_select');

        switch ($_GET['acao']) {

            case 'md_lit_tipo_controle_tipo_decisao_consultar':

                //INICIO ALTERAÇÃO

                $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                $objTipoControleLitigiosoRN = new MdLitTipoControleRN();

                $objTipoControleLitigiosoDTO->retStrSigla();
                $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_controle_litigioso']);
                $arrObjTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->listar($objTipoControleLitigiosoDTO);


                //FIM ALTERAÇÂO

                $strItensSelTipoDecisaoLitigioso = "";

                foreach ($arrObjTipoControleLitigiosoDTO as $objTipoControleLitigiosoDTO) {
                    $valor = $objTipoControleLitigiosoDTO->getStrSigla();
                }

                $strTitulo                       = 'Associar Tipos de Decisão - ' . PaginaSEI::tratarHTML($valor);


                $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoDecisaoLitigioso" id="sbmCadastrarTipoDecisaoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
                //cadastrando associaçoes
                if (isset($_POST['hdnTbTiposDecisao'])) {

                    try {

                        $objAssocTipoDecisaoLitigiosoRN = new MdLitRelTipoControleTipoDecisaoRN();
                        $arrTipoDecisao                 = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbTiposDecisao']);

                        //SET DECISOES
                        $arrObjAssocTipoDecisaoDTO = array();

                        for ($x = 0; $x < count($arrTipoDecisao); $x++) {

                            $objAssocTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                            $objAssocTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($arrTipoDecisao[$x][1]);
                            $objAssocTipoDecisaoDTO->setNumIdMdLitEspecieDecisao($arrTipoDecisao[$x][2]);
                            $objAssocTipoDecisaoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControle']);
                            array_push($arrObjAssocTipoDecisaoDTO, $objAssocTipoDecisaoDTO);
                        }

                        // Cadastro - remove os relacionamentos atuais, adiciona os novos
                        $objAssocTipoDecisaoLitigiosoRN->cadastrar($arrObjAssocTipoDecisaoDTO);
                        //header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?&acao=md_lit_tipo_controle_listar'));
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_POST['hdnIdTipoControle'])));
                        die;

                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }

                } else {
                    //obter tipos de decisão já associados ao tipo de controle selecionado
                    $idTipoControle = $_GET['id_tipo_controle_litigioso'];

                    $objAssocTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                    $objAssocTipoDecisaoDTO->retTodos();
                    $objAssocTipoDecisaoDTO->retStrNome();
                    $objAssocTipoDecisaoDTO->retStrSinAtivoDecisao();
                    $objAssocTipoDecisaoDTO->retStrNomeEspecieDecisao();

                    $objAssocTipoDecisaoDTO->setNumIdTipoControleLitigioso($idTipoControle);
                    $objAssocTipoDecisaoDTO->setStrSinAtivoDecisao('S');

                    PaginaSEI::getInstance()->prepararOrdenacao($objAssocTipoDecisaoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);

                    $objAssocTipoDecisaoLitigiosoRN = new MdLitRelTipoControleTipoDecisaoRN();
                    $arrItens                       = $objAssocTipoDecisaoLitigiosoRN->listar($objAssocTipoDecisaoDTO);


                    $strItensSelTipoDecisao = MdLitTipoDecisaoINT::montarSelectTipoDecisao('null', '&nbsp;', '');
                    $arrItensTbEspecieDecisao = array();
                    $objMdLitDecisaoRN = new MdLitDecisaoRN();

                    foreach ($arrItens as $key => $objAssocTipoDecisaoDTO){
                        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
                        $objMdLitDecisaoDTO->retNumIdMdLitTipoDecisao();
                        $objMdLitDecisaoDTO->retNumIdMdLitEspecieDecisao();
                        $objMdLitDecisaoDTO->retNumIdMdLitTipoControleMdLitProcessoSituacao();
                        $objMdLitDecisaoDTO->setNumIdMdLitTipoDecisao($objAssocTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso());
                        $objMdLitDecisaoDTO->setNumIdMdLitEspecieDecisao($objAssocTipoDecisaoDTO->getNumIdMdLitEspecieDecisao());
                        $objMdLitDecisaoDTO->setNumIdMdLitTipoControleMdLitProcessoSituacao($objAssocTipoDecisaoDTO->getNumIdTipoControleLitigioso());

                        $arrItensTbEspecieDecisao[$key][] = $objAssocTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso().'-'.$objAssocTipoDecisaoDTO->getNumIdMdLitEspecieDecisao();
                        $arrItensTbEspecieDecisao[$key][] = $objAssocTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso();
                        $arrItensTbEspecieDecisao[$key][] = $objAssocTipoDecisaoDTO->getNumIdMdLitEspecieDecisao();
                        $arrItensTbEspecieDecisao[$key][] = $objAssocTipoDecisaoDTO->getStrNome();
                        $arrItensTbEspecieDecisao[$key][] = $objAssocTipoDecisaoDTO->getStrNomeEspecieDecisao();
                        $arrItensTbEspecieDecisao[$key][] = $objMdLitDecisaoRN->contar($objMdLitDecisaoDTO);

                    }

                    $hdnTbTiposDecisao = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrItensTbEspecieDecisao);
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

#lblTipoDecisaoLitigioso {position:absolute;left:0%;top:0%;width:50%;}
#selDescricaoTipoDecisaoLitigioso{position:absolute;left:0%;top:5%;width:50%;}

#lblEspecieDecisao{position:absolute;left:0%;top:10%;width:50%;}
#selEspecieDecisaoLitigioso{position:absolute;left:0%;top:15%;width:50%;}

#addTipoDecisao{position:absolute;left:51%;top:15%;}
#tableTiposDecisao{position:absolute;left:0%;top:22%;}

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
<form id="frmTipoDecisaoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao_origem'])) ?>">
    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('530px');
    ?>

    <!--  Componente TipoDecisao  -->
    <div id="divUn1" class="infraAreaDados" style="height:530px">

        <label id="lblDescricaoTipoDecisao" for="txtTipoDecisao" accesskey="q" class="infraLabelObrigatorio">Tipo de
            Decisão:</label>
        <select id="selDescricaoTipoDecisaoLitigioso" name="selDescricaoTipoDecisaoLitigioso"  class="infraSelect">
            <?= $strItensSelTipoDecisao ?>
        </select>

        <label id="lblEspecieDecisao" for="txtTipoDecisao" class="infraLabelObrigatorio">Espécies de Decisão:</label>
        <select id="selEspecieDecisaoLitigioso" name="selEspecieDecisaoLitigioso" class="infraSelect">
        </select>


        <table width="99%" class="infraTable" summary="Lista de Tipos de Decisão" id="tableTiposDecisao">

            <caption class="infraCaption">
                <?= PaginaSEI::getInstance()->gerarCaptionTabela('Lista de Tipos de Decisão', 0) ?>
            </caption>

            <tr>
                <th class="infraTh" style="display: none">ID</th>
                <th class="infraTh" style="display: none">ID Tipo de Decisao</th>
                <th class="infraTh" style="display: none">ID Especie de decisao</th>
                <th class="infraTh">Tipo de Decisão</th>
                <th class="infraTh">Espécies de Decisão</th>
                <th class="infraTh" style="display: none">Contar Decisão</th>
                <th class="infraTh" style="width: 60px;">Ações</th>
            </tr>
        </table>

        <!-- Hidden Relacionado à tabela -->
        <input type="hidden" name="hdnTbTiposDecisao" id="hdnTbTiposDecisao" value="<?php echo $hdnTbTiposDecisao?>"/>

        <button type="button" id='addTipoDecisao' class="infraButton" onclick="adicionarTipoDecisao()">Adicionar</button>
        <input type="hidden" id="hdnIdTipoDecisaoLitigioso" name="hdnIdTipoDecisaoLitigioso"
               value="<?= $_POST['hdnIdTipoDecisaoLitigioso'] ?>"/>
        <input type="hidden" id="hdnIdTipoControle" name="hdnIdTipoControle"
               value="<?php echo isset($_GET['id_tipo_controle_litigioso']) ? $_GET['id_tipo_controle_litigioso'] : $_POST['hdnIdTipoControle']; ?>"/>

    </div>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>

</form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>

<script type="text/javascript">
    var objTabelaAssociacao = null;
    function inicializar() {
        document.getElementById('btnCancelar').focus();
        infraEfeitoTabelas();
        buscarComboEspecieDecisao();
        objTabelaAssociacao = new infraTabelaDinamica('tableTiposDecisao', 'hdnTbTiposDecisao', false, true);
        objTabelaAssociacao.gerarEfeitoTabela = true;
        objTabelaAssociacao.inserirNoInicio = false;
        objTabelaAssociacao.exibirMensagens = true;

    }


    function validarCadastro() {

        var optionsTipoDecisao = document.getElementById('selDescricaoTipoDecisao').options;

        if (optionsTipoDecisao.length == 0) {

            if (confirm("Nenhum Tipo de Decisão foi associado. Deseja salvar o registro?")) {
                return true;
            } else {
                return false;
            }

        } else {
            return true;
        }

    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function buscarComboEspecieDecisao(){

        //Ajax para carregar as especie de decisao de acordo com o tipo de decisao
        objAjaxComboEspecieDecisao = new infraAjaxMontarSelectDependente('selDescricaoTipoDecisaoLitigioso', 'selEspecieDecisaoLitigioso', '<?=$strLinkAjaxComboEspecieDecisao?>');

        objAjaxComboEspecieDecisao.prepararExecucao = function () {
            if (document.getElementById('selDescricaoTipoDecisaoLitigioso').value != 'null' || document.getElementById('selDescricaoTipoDecisaoLitigioso').value != '') {
                return infraAjaxMontarPostPadraoSelect('null', '', 'null') +'&id_md_lit_tipo_decisao=' + document.getElementById('selDescricaoTipoDecisaoLitigioso').value;
            }

            return false;
        };

        objAjaxComboEspecieDecisao.processarResultado = function(){
            var itensTabela = objTabelaAssociacao.obterItens();
            for(var i = 0; i < itensTabela.length; i++){
                if(document.getElementById('selDescricaoTipoDecisaoLitigioso').value == itensTabela[i][1]){
                    removerOptionSelect(document.getElementById('selEspecieDecisaoLitigioso'),itensTabela[i][2] );
                }
            }

        };
    }

    function removerOptionSelect(idSelect, valueOption){
        idSelect = infraGetElementById(idSelect);
        for(var i = 0; i < idSelect.options.length; i++){
            if(idSelect.options[i].value == valueOption){
                idSelect.options[i].remove()
            }
        }
    }

    function getNomeSelectSelecionado(idSelect) {
        idSelect = infraGetElementById(idSelect);
        for(var i = 0; i < idSelect.options.length; i++){
            if(idSelect.options[i].value == idSelect.value){
                return idSelect.options[i].text;
            }
        }
    }

    function adicionarTipoDecisao(){
        var idTipoDecisao = document.getElementById('selDescricaoTipoDecisaoLitigioso').value;
        var idEspecieDecisao = document.getElementById('selEspecieDecisaoLitigioso').value;
        var nomeTipoDecisao = getNomeSelectSelecionado(document.getElementById('selDescricaoTipoDecisaoLitigioso'));
        var nomeEspecieDecisao = getNomeSelectSelecionado(document.getElementById('selEspecieDecisaoLitigioso'));

        if(document.getElementById('selDescricaoTipoDecisaoLitigioso').value == '' || document.getElementById('selDescricaoTipoDecisaoLitigioso').value == 'null'){
            alert('Selecione o Tipo de Decisão');
            return;
        }

        if(document.getElementById('selEspecieDecisaoLitigioso').value == '' || document.getElementById('selEspecieDecisaoLitigioso').value == 'null'){
            alert('Selecione a Espécies de Decisão');
            return;
        }

        objTabelaAssociacao.adicionar([
            idTipoDecisao+'-'+idEspecieDecisao,
            idTipoDecisao,
            idEspecieDecisao,
            nomeTipoDecisao,
            nomeEspecieDecisao,
            0
        ]);
        objAjaxComboEspecieDecisao.executar();
    }

</script>