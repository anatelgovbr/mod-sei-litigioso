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
        $strLinkAjaxConduta    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_conduta_auto_completar');

        //Tipo de Controle
        $strLinkTipoControleSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_selecionar&tipo_selecao=2&id_object=objLupaTipoControle');
        $strLinkAjaxTipoControle    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_tipo_controle_auto_completar');

        //dispositivo normativo
        $strLinkDispositivoNormativoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_selecionar&tipo_selecao=2&id_object=objLupaRevogarDispositivo');
        $strLinkAjaxDispositivoNormativo    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dispositivo_auto_completar');

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
                        $arrCondutas                          = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnConduta']);

                        for ($x = 0; $x < count($arrCondutas); $x++) {
                            $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
                            $objRelDispositivoNormativoCondutaDTO->setNumIdConduta($arrCondutas[$x]);
                            array_push($arrObjDispositivoNormativoCondutaDTO, $objRelDispositivoNormativoCondutaDTO);
                        }

                        $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoCondutaDTO($arrObjDispositivoNormativoCondutaDTO);


                        //SET TIPOS DE CONTORLE LITIGIOSO
                        $arrObjDispositivoNormativoTipoControleDTO = array();
                        $arrTiposControle                          = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoControle']);

                        for ($x = 0; $x < count($arrTiposControle); $x++) {
                            $objRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                            $objRelDispositivoNormativoTipoControleDTO->setNumIdTipoControle($arrTiposControle[$x]);
                            array_push($arrObjDispositivoNormativoTipoControleDTO, $objRelDispositivoNormativoTipoControleDTO);
                        }

                        $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoTipoControleDTO($arrObjDispositivoNormativoTipoControleDTO);


                        //set dipositivo normativo revogado
                        $arrObjDispositivoNormativoRevogadoDTO = array();
                        $arrDispositivoRevogado                = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnRevogarDispositivo']);

                        for ($x = 0; $x < count($arrDispositivoRevogado); $x++) {
                            $objMdLitRelDispositivoNormativoRevogadoDTO = new MdLitRelDispositivoNormativoRevogadoDTO();
                            $objMdLitRelDispositivoNormativoRevogadoDTO->setNumIdMdLitDispositivoNormativoRevogado($arrDispositivoRevogado[$x]);
                            array_push($arrObjDispositivoNormativoRevogadoDTO, $objMdLitRelDispositivoNormativoRevogadoDTO);
                        }

                        $objDispositivoNormativoLitigiosoDTO->setArrObjRelMdLitRelDispositivoNormativoRevogadoDTO($arrObjDispositivoNormativoRevogadoDTO);

                        // Cadastro
                        $objDispositivoNormativoLitigiosoRN  = new MdLitDispositivoNormativoRN();
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
                $strTitulo      = 'Alterar dispositivo Normativo';
                $arrComandos[]  = '<button type="submit" accesskey="S" name="sbmAlterardispositivoNormativoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
                $strDesabilitar = 'disabled="disabled"';

                if (isset($_GET['id_dispositivo_normativo_litigioso'])) {

                    $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($_GET['id_dispositivo_normativo_litigioso']);
                    $objDispositivoNormativoLitigiosoDTO->retTodos();
                    $objDispositivoNormativoLitigiosoRN  = new MdLitDispositivoNormativoRN();
                    $objDispositivoNormativoLitigiosoDTO = $objDispositivoNormativoLitigiosoRN->consultar($objDispositivoNormativoLitigiosoDTO);

                    // Consultar Relacionamento com Conduta
                    $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
                    $objRelDispositivoNormativoCondutaDTO->retTodos();
                    $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($_GET['id_dispositivo_normativo_litigioso']);


                    $objRelDispositivoNormativoCondutaRN = new MdLitRelDispositivoNormativoCondutaRN();
                    $arrCondutas                         = $objRelDispositivoNormativoCondutaRN->listar($objRelDispositivoNormativoCondutaDTO);

                    $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoCondutaDTO($arrCondutas);

                    $strItensSelCondutas = "";
                    $objCondutaRN        = new MdLitCondutaRN();

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
                    $arrTiposControle                         = $objRelDispositivoNormativoTipoControleRN->listar($objRelDispositivoNormativoTipoControleDTO);

                    $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoTipoControleDTO($arrTiposControle);

                    $strItensSelTiposControle = "";
                    $objTipoControleRN        = new MdLitTipoControleRN();

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
                    $arrCondutas                          = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnConduta']);

                    for ($x = 0; $x < count($arrCondutas); $x++) {
                        $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
                        $objRelDispositivoNormativoCondutaDTO->setNumIdConduta($arrCondutas[$x]);
                        array_push($arrObjDispositivoNormativoCondutaDTO, $objRelDispositivoNormativoCondutaDTO);
                    }

                    $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoCondutaDTO($arrObjDispositivoNormativoCondutaDTO);

                    //SET TIPO DE CONTROLE
                    $arrObjDispositivoNormativoTipoControleDTO = array();
                    $arrTiposControle                          = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoControle']);

                    for ($x = 0; $x < count($arrTiposControle); $x++) {
                        $objRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                        $objRelDispositivoNormativoTipoControleDTO->setNumIdTipoControle($arrTiposControle[$x]);
                        array_push($arrObjDispositivoNormativoTipoControleDTO, $objRelDispositivoNormativoTipoControleDTO);
                    }

                    $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoTipoControleDTO($arrObjDispositivoNormativoTipoControleDTO);

                    //set dipositivo normativo revogado
                    $arrObjDispositivoNormativoRevogadoDTO = array();
                    $arrDispositivoRevogado                = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnRevogarDispositivo']);

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
                $strTitulo     = 'Consultar Dispositivo Normativo';
                $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_dispositivo_normativo_litigioso']))) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
                $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($_GET['id_dispositivo_normativo_litigioso']);
                $objDispositivoNormativoLitigiosoDTO->setBolExclusaoLogica(false);
                $objDispositivoNormativoLitigiosoDTO->retTodos();
                $objDispositivoNormativoLitigiosoRN  = new MdLitDispositivoNormativoRN();
                $objDispositivoNormativoLitigiosoDTO = $objDispositivoNormativoLitigiosoRN->consultar($objDispositivoNormativoLitigiosoDTO);

                // Consultar Relacionamento com Conduta
                $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
                $objRelDispositivoNormativoCondutaDTO->retTodos();
                $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($_GET['id_dispositivo_normativo_litigioso']);


                $objRelDispositivoNormativoCondutaRN = new MdLitRelDispositivoNormativoCondutaRN();
                $arrCondutas                         = $objRelDispositivoNormativoCondutaRN->listar($objRelDispositivoNormativoCondutaDTO);

                $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoCondutaDTO($arrCondutas);

                $strItensSelCondutas = "";
                $objCondutaRN        = new MdLitCondutaRN();

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
                $arrTiposControle                         = $objRelDispositivoNormativoTipoControleRN->listar($objRelDispositivoNormativoTipoControleDTO);

                $objDispositivoNormativoLitigiosoDTO->setArrObjRelDispositivoNormativoTipoControleDTO($arrTiposControle);

                $strItensSelTiposControle = "";
                $objTipoControleRN        = new MdLitTipoControleRN();

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
?>

#lblNorma {position:absolute;left:0%;top:0%;width:50%;}
#txtNorma {position:absolute;left:0%;top:40%;width:50%;}

#lblUrlNome {position:absolute;left:0%;top:0%;width:50%;}
#txtUrlNome {position:absolute;left:0%;top:40%;width:50%;}

#lblDispositivo {position:absolute;left:0%;top:0%;width:50%;}
#txtDispositivo {position:absolute;left:0%;top:40%;width:50%;}

#lblDescricaoDispositivo {position:absolute;left:0%;top:0%;width:50%;}
#txtDescricaoDispositivo {position:absolute;left:0%;top:20%;width:75%;}

#lblDescricaoConduta {position:absolute;left:0%;top:0%;width:50%;}
#txtConduta {position:absolute;left:0%;top:15%;width:50%;}
#selDescricaoConduta {position:absolute;left:0%;top:35%;width:75%;}

#imgLupaConduta {position:absolute;left:76%;top:35%;}
#imgExcluirConduta {position:absolute;left:76%;top:55%;}

#lblDescricaoTipoControleLitigioso {position:absolute;left:0%;top:0%;width:50%;}
#txtTipoControle {position:absolute;left:0%;top:15%;width:50%;}
#selDescricaoTipoControle{position:absolute;left:0%;top:35%;width:75%;}

#imgLupaTipoControleLitigioso {position:absolute;left:76%;top:35%;}
#imgExcluirTipoControleLitigioso {position:absolute;left:76%;top:55%;}

#lblRevogarDispositivo {position:absolute;left:0%;top:0%;width:50%;}
#txtRevogarDispositivo {position:absolute;left:0%;top:15%;width:50%;}
#selRevogarDispositivo {position:absolute;left:0%;top:35%;width:75%;}

#imgLupaRevogarDispositivo {position:absolute;left:76%;top:35%;}
#imgExcluirRevogarDispositivo {position:absolute;left:76%;top:55%;}

#lblRevogadoPor {position:absolute;left:0%;top:0%;width:50%;}
#txtRevogadoPor {position:absolute;left:0%;top:40%;width:50%;}

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
<form id="frmdispositivoNormativoCadastro" method="post" onsubmit="return OnSubmitForm();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('62.5em');
    ?>
    <div class="infraAreaDados" style="height:4.5em;">

        <label id="lblNorma" for="txtNorma" accesskey="f" class="infraLabelObrigatorio">Norma:</label>
        <input type="text" id="txtNorma" name="txtNorma" class="infraText"
               value="<?= PaginaSEI::tratarHTML($objDispositivoNormativoLitigiosoDTO->getStrNorma()); ?>"
               onkeypress="return infraMascaraTexto(this,event,150);"
               maxlength="150" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

    </div>

    <div class="infraAreaDados" style="height:4.5em;">

        <label id="lblUrlNome" for="txtUrlNome" accesskey="f" class="infraLabel">URL da Norma:</label>
        <input type="text" id="txtUrlNome" name="txtUrlNome" class="infraText"
               value="<?= PaginaSEI::tratarHTML($objDispositivoNormativoLitigiosoDTO->getStrUrl()); ?>"
               onkeypress="return infraMascaraTexto(this,event,2083);" maxlength="2083"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

    </div>

    <div class="infraAreaDados" style="height:4.5em;">

        <label id="lblDispositivo" for="txtDispositivo" accesskey="f" class="infraLabelObrigatorio">Dispositivo:</label>
        <input type="text" id="txtDispositivo" name="txtDispositivo" class="infraText"
               value="<?= PaginaSEI::tratarHTML($objDispositivoNormativoLitigiosoDTO->getStrDispositivo()); ?>"
               onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

    </div>

    <div class="infraAreaDados" style="height:8.5em;">

        <label id="lblDescricaoDispositivo" for="txtDescricaoDispositivo" accesskey="q" class="infraLabelObrigatorio">Descrição
            do Dispositivo:</label>
	  <textarea type="text" id="txtDescricaoDispositivo" rows="3" name="txtDescricaoDispositivo" class="infraText"
                onkeypress="return infraMascaraTexto(this,event,2000);"
                maxlength="2000"
                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?php ?><?= PaginaSEI::tratarHTML($objDispositivoNormativoLitigiosoDTO->getStrDescricao()); ?></textarea>

    </div>

    <!--  Componente Conduta  -->
    <div id="divUn1" class="infraAreaDados" style="height:11.5em;">

        <label id="lblDescricaoConduta" for="txtConduta" accesskey="q" class="infraLabelObrigatorio">Condutas associadas:</label>

        <input type="text" id="txtConduta" name="txtConduta" class="infraText"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        <select id="selDescricaoConduta" name="selDescricaoConduta" size="4" multiple="multiple" class="infraSelect">
            <?= $strItensSelCondutas ?>
        </select>

        <img id="imgLupaConduta" onclick="objLupaConduta.selecionar(700,500);" src="/infra_css/imagens/lupa.gif"
             alt="Localizar Conduta Associada"
             title="Localizar Conduta Associada" class="infraImg"/>

        <img id="imgExcluirConduta" onclick="objLupaConduta.remover();" src="/infra_css/imagens/remover.gif"
             alt="Remover Condutas Associadas"
             title="Remover Condutas Associadas" class="infraImg"/>

        <input type="hidden" id="hdnIdConduta" name="hdnIdConduta" value="<?= $_POST['hdnIdConduta'] ?>"/>
        <input type="hidden" id="hdnConduta" name="hdnConduta" value="<?= $_POST['hdnConduta'] ?>"/>

    </div>

    <!--  Componente Tipo de Controle Litigioso Associado  -->
    <div id="divUn2" class="infraAreaDados" style="height:11.5em;">

        <label id="lblDescricaoTipoControleLitigioso" for="txtTipoControle" accesskey="q" class="infraLabel">Tipos
            de Controle Litigioso associados:</label>

        <input type="text" id="txtTipoControle" name="txtTipoControle" class="infraText"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        <select id="selDescricaoTipoControle" name="selDescricaoTipoControle" size="4" multiple="multiple"
                class="infraSelect">
            <?= $strItensSelTiposControle ?>
        </select>

        <img id="imgLupaTipoControleLitigioso" onclick="objLupaTipoControle.selecionar(700,500);"
             src="/infra_css/imagens/lupa.gif"
             alt="Localizar Tipo de Controle Associado"
             title="Localizar Tipo de Controle Associado" class="infraImg"/>

        <img id="imgExcluirTipoControleLitigioso" onclick="objLupaTipoControle.remover();"
             src="/infra_css/imagens/remover.gif"
             alt="Remover Tipo de Controle Associados"
             title="Remover Tipo de Controle Associados" class="infraImg"/>

    </div>

    <!--  Componente Revogar dispositivo  -->
    <div id="divRevogarDispositivo" class="infraAreaDados" style="height:11.5em;">

        <label id="lblRevogarDispositivo" for="txtRevogarDispositivo" class="infraLabel">Revogar dispositivo:</label>

        <input type="text" id="txtRevogarDispositivo" name="txtRevogarDispositivo" class="infraText"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        <select id="selRevogarDispositivo" name="selRevogarDispositivo" size="4" multiple="multiple"
                class="infraSelect">
            <?= $strItensSelRevogarDispositivo ?>
        </select>

        <img id="imgLupaRevogarDispositivo" onclick="objLupaRevogarDispositivo.selecionar(700,500);"
             src="/infra_css/imagens/lupa.gif"
             alt="Localizar Tipo de Controle Associado"
             title="Localizar Tipo de Controle Associado" class="infraImg"/>

        <img id="imgExcluirRevogarDispositivo" onclick="objLupaRevogarDispositivo.remover();"
             src="/infra_css/imagens/remover.gif"
             alt="Remover Dispositivos Revogados"
             title="Remover Dispositivos Revogados" class="infraImg"/>

        <input type="hidden" id="hdnIdRevogarDispositivo" name="hdnIdRevogarDispositivo" value="<?= $_POST['hdnIdRevogarDispositivo'] ?>"/>
        <input type="hidden" id="hdnRevogarDispositivo" name="hdnRevogarDispositivo" value="<?= $_POST['hdnRevogarDispositivo'] ?>"/>
    </div>

    <?php if($revogadoPor){?>
        <div id="divRevogadoPor" class="infraAreaDados" style="height:4.5em;">
            <label id="lblRevogadoPor" for="txtRevogadoPor" class="infraLabel">Revogado por:</label>
            <input type="text" value="<?= $revogadoPor ?>" id="txtRevogadoPor" />

        </div>
    <?php }?>

    <input type="hidden" id="hdnIdTipoControle" name="hdnIdTipoControle" value="<?= $_POST['hdnIdTipoControle'] ?>"/>
    <input type="hidden" id="hdnTipoControle" name="hdnTipoControle" value="<?= $_POST['hdnTipoControle'] ?>"/>

    <input type="hidden" id="hdnIdDispositivoNormativoLitigioso" name="hdnIdDispositivoNormativoLitigioso"
           value="<?= $objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso(); ?>"/>
    <?
        PaginaSEI::getInstance()->fecharAreaDados();
    ?>
<?php //PaginaSEI::getInstance()->montarAreaDebug(); ?>
</form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>

<script type="text/javascript">
    var objLupaConduta = null;
    var objAutoCompletarConduta = null;
    var objLupaTipoControle = null;
    var objAutoCompletarTipoControle = null;
    var objAutoCompletarRevogarDispositivo = null;
    var objLupaRevogarDispositivo = null;

    function inicializar() {

        carregarComponenteConduta();
        carregarComponenteTipoControle();
        carregarComponenteRevogado();


        if ('<?=$_GET['acao']?>' == 'md_lit_dispositivo_normativo_cadastrar') {
            document.getElementById('txtNorma').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_lit_dispositivo_normativo_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();


    }

    function isValidURL() {
        var str = document.getElementById('txtUrlNome').value;
        str = str.trim();

        if ((str) != '') {
            var urlexp = new RegExp('^(http|https):[\/]{2,2}([A-Za-z0-9-_]{1,63}\\.)+([A-Za-z]{2,6})*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$');

            if (!urlexp.test(str)) {
                alert('URL da Norma inválido.');
                return false;
            }

        }

        return true;

    }


    function carregarComponenteTipoControle() {

        objAutoCompletarTipoControle = new infraAjaxAutoCompletar('hdnIdTipoControle', 'txtTipoControle', '<?=$strLinkAjaxTipoControle?>');
        objAutoCompletarTipoControle.limparCampo = true;

        objAutoCompletarTipoControle.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtTipoControle').value;
        };

        objAutoCompletarTipoControle.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricaoTipoControle').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Tipo de Controle já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    var desc = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoTipoControle'), desc, id);

                    objLupaTipoControle.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtTipoControle').value = '';
                document.getElementById('txtTipoControle').focus();

            }
        };

        objLupaTipoControle = new infraLupaSelect('selDescricaoTipoControle', 'hdnTipoControle', '<?=$strLinkTipoControleSelecao?>');

    }

    function carregarComponenteConduta() {

        objAutoCompletarConduta = new infraAjaxAutoCompletar('hdnIdConduta', 'txtConduta', '<?=$strLinkAjaxConduta?>');
        objAutoCompletarConduta.limparCampo = true;

        objAutoCompletarConduta.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtConduta').value;
        };

        objAutoCompletarConduta.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selDescricaoConduta').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Conduta já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }
                    var desc = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selDescricaoConduta'), desc, id);

                    objLupaConduta.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtConduta').value = '';
                document.getElementById('txtConduta').focus();

            }
        };

        objLupaConduta = new infraLupaSelect('selDescricaoConduta', 'hdnConduta', '<?=$strLinkCondutaSelecao?>');

    }

    function carregarComponenteRevogado() {

        objAutoCompletarRevogarDispositivo = new infraAjaxAutoCompletar('hdnIdRevogarDispositivo', 'txtRevogarDispositivo', '<?=$strLinkAjaxDispositivoNormativo?>');
        objAutoCompletarRevogarDispositivo.limparCampo = true;

        objAutoCompletarRevogarDispositivo.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtRevogarDispositivo').value;
        };

        objAutoCompletarRevogarDispositivo.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selRevogarDispositivo').options;

                if (options != null) {
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == id) {
                            alert('Dispositivo normativo já consta na lista.');
                            break;
                        }
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }
                    var desc = $("<pre>").html(descricao).text();
                    opt = infraSelectAdicionarOption(document.getElementById('selRevogarDispositivo'), desc, id);

                    objLupaRevogarDispositivo.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtRevogarDispositivo').value = '';
                document.getElementById('txtRevogarDispositivo').focus();

            }
        };

        objLupaRevogarDispositivo = new infraLupaSelect('selRevogarDispositivo', 'hdnRevogarDispositivo', '<?=$strLinkDispositivoNormativoSelecao?>');

    }


    function validarCadastro() {

        if (infraTrim(document.getElementById('txtNorma').value) == '') {
            alert('Informe a Norma.');
            document.getElementById('txtNorma').focus();
            return false;
        }

        //dispositivo
        if (infraTrim(document.getElementById('txtDispositivo').value) == '') {
            alert('Informe o Dispositivo.');
            document.getElementById('txtDispositivo').focus();
            return false;
        }

        //descricao do dispositivo
        if (infraTrim(document.getElementById('txtDescricaoDispositivo').value) == '') {
            alert('Informe a Descrição do Dispositivo.');
            document.getElementById('txtDescricaoDispositivo').focus();
            return false;
        }

        if(infraTrim(document.getElementById('hdnConduta').value) == ''){
            alert('É obrigatório associar pelo menos uma conduta ao dispositivo normativo!');
            document.getElementById('txtDescricaoDispositivo').focus();
            return false;
        }

        if (!isValidURL()) {
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

</script>