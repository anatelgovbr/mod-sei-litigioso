<?
    /**
     * ANATEL
     *
     * 13/07/2016 - criado por marcelo.bezerra@cast.com.br - CAST
     *
     */

    try {

        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();

        //para que a pagina seja exibida sem os menus lateral e do topo
        PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

        //////////////////////////////////////////////////////////////////////////////
        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->limpar();
        //////////////////////////////////////////////////////////////////////////////

        SessaoSEI::getInstance()->validarLink();

        //=====================================================
        //INICIO - VARIAVEIS PRINCIPAIS E LISTAS DA PAGINA
        //=====================================================

        //obter o tipo de controle litigioso relacionado para exibir na tela
        $strNomeTipoControle = "texte";

        //=====================================================
        //FIM - VARIAVEIS PRINCIPAIS E LISTAS DA PAGINA
        //=====================================================

        //se for fornecido o numero do SEI validar as informações necessárias
        if (isset($_POST['txtNumeroSEI'])) {

            //aplicando RN 2, 3 e 4
            // [RN2]	O campo “Número do SEI” deve aceitar somente Numero SEI dos Tipos de Documentos associados à Situação marcada
            // como “Instauradora” para o correspondente Tipo de Controle Litigioso.
            // [RN3]	O formulário completo somente será apresentado, caso os campos “Número SEI” e o “Tipo de Documentos” seja válido.
            // [RN4]	Caso o Número SEI informado seja de Documento Interno, somente será validado caso o mesmo já estiver sido assinado.


        }

        switch ($_GET['acao']) {

            case 'md_lit_processo_validar_numero_sei':
                $idProcedimento = $_GET['id_procedimento'];
                $idUnidade      = $_GET['infra_unidade_atual'];

                //Setta o Obj Procedimento
                $objProcedimentoDTO = new ProcedimentoDTO();
                $objProcedimentoDTO->retTodos();
                $objProcedimentoDTO->setDblIdProcedimento($idProcedimento);

                $procedimentoRN     = new ProcedimentoRN();
                $objProcedimentoDTO = $procedimentoRN->consultarRN0201($objProcedimentoDTO);

                //Setta o Relacionamento de Tipo de Controle com Tipo de Processo  - MdLitRelTipoControleTipoProcedimentoDTO
                $ObjRelTipoControleLitigiosoTipoProcedimentoRN  = new MdLitRelTipoControleTipoProcedimentoRN();
                $ObjRelTipoControleLitigiosoTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
                $ObjRelTipoControleLitigiosoTipoProcedimentoDTO->retTodos();
                $ObjRelTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
                $ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO = $ObjRelTipoControleLitigiosoTipoProcedimentoRN->listar($ObjRelTipoControleLitigiosoTipoProcedimentoDTO);
//  		echo '<pre>';
//  		print_r($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO);

                //"TIPO DE CONTROLE LITIGIOSO"
                $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO[0]->getNumIdTipoControleLitigioso()) .
                $objTipoControleLitigiosoDTO->retTodos();

                $objTipoControleLitigiosoRN  = new MdLitTipoControleRN();
                $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);
//  		echo '<pre>';
//  		print_r($objTipoControleLitigiosoDTO);

                //Retorna o nome do "TIPO DE CONTROLE LITIGIOSO" do processo acessado
                $strNomeTipoControle = $objTipoControleLitigiosoDTO->getStrSigla();

                if (isset($_POST['txtNumeroSEI'])) {
                    // TODO VERIFICAR FORMATO?

                    // NUMERO SEI - pesquisa
                    $objDocumentoDTO = new DocumentoDTO();
                    $objDocumentoDTO->setStrProtocoloDocumentoFormatado($_POST['txtNumeroSEI']);
                    $objDocumentoDTO->retStrNumero();
                    $objDocumentoDTO->retNumIdSerie();
                    $objDocumentoRN  = new DocumentoRN();
                    $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

                    // NUMERO SEI - EXISTE
                    if (count($objDocumentoDTO) > 0) {
                        // SITUAÇÃO - SOMENTE UMA INSTAURAÇÃO
                        $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                        $objSituacaoLitigiosoDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                        $objSituacaoLitigiosoDTO->setStrSinInstauracao('S');
                        $objSituacaoLitigiosoDTO->retTodos();
                        $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                        //$arrObjSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->listarComTipoDeControle($objSituacaoLitigiosoDTO, $objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                        $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO);

                        // SITUAÇÃO - EXISTE
                        if (count($objSituacaoLitigiosoDTO) > 0) {
                            // RELAÇÃO SITUAÇÃO X TIPO DOCUMENTO
                            $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
                            $objSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso());
                            $objSituacaoLitigiosoSerieDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
                            $objSituacaoLitigiosoSerieDTO->retTodos();

                            $objRelSituacaoLitigiosoSerieRN  = new MdLitRelSituacaoSerieRN();
                            $arrObjRelSituacaoLitigiosoSerie = $objRelSituacaoLitigiosoSerieRN->listar($objSituacaoLitigiosoSerieDTO);

                            // RELAÇÃO SITUAÇÃO X TIPO DOCUMENTO - EXISTE
                            if (count($arrObjRelSituacaoLitigiosoSerie) > 0) {
                                echo 'encontrei';
                            }
                        }
                    }
                }


                $strTitulo = 'Cadastrar em Controle Litigioso';
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
?>
<style type="text/css">
    #field1 {
        height: auto;
        width: 96%;
        margin-bottom: 11px;
    }

    .sizeFieldset {
        height: auto;
        width: 86%;
    }

    .fieldsetClear {
        border: none !important;
    }
</style>
<?php
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
    $urlBaseLink   = "";
    $arrComandos   = array();
    $arrComandos[] = '<button type="button" accesskey="S" name="btnSalvar" value="Salvar" onclick="abrirPeticionar()" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
    $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Cancelar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
?>
<form id="frmNumeroSeiCadastro" method="post"
      action="<?
          //echo PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_cadastro_completo&id_procedimento='.$_GET['id_procedimento']));
          echo PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_processo_validar_numero_sei&id_procedimento=' . $_GET['id_procedimento']));
      ?>">
    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('auto');
    ?>
    <p>


        <label style="font-weight: bold;">Tipo de Controle Litigioso:</label>
        <label> <?= $strNomeTipoControle ?></label>
    </p>
    <fieldset id="field1" class="infraFieldset sizeFieldset">
        <legend class="infraLegend">&nbsp; Documento instaurador do Procedimento Litigioso &nbsp;</legend>
        <label style="font-weight: bold;"> Número SEI: </label><br/>
        <input type="text" name="txtNumeroSEI" maxlength="50" id="txtNumeroSEI" style="width: 20%" class="infraText"
               value="<?= $_POST['txtNumeroSEI'] ?>"/>
        <button type="button" name="btnValidar" value="Validar" onclick="validar()" class="infraText">Validar</button>
    </fieldset>

</form>

<?
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>
<script type="text/javascript">

    function validar() {

        document.getElementById('frmNumeroSeiCadastro').submit();

        //window.location.href = linkProcesso;
    }

    function validarFormulario() {

        //valida campo especificação


        var textoEspecificacao = document.getElementById("txtNumeroSEI").value;

        if (textoEspecificacao == '') {
            alert('Informe a especificação.');
            document.getElementById("txtNumeroSEI").focus();

            return false;
        }

        return true;
    }

    function inicializar() {

        infraEfeitoTabelas();

    }

</script>