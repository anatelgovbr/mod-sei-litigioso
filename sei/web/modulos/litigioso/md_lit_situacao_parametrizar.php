<?
/**
 * ANATEL
 *
 * 02/02/2016 - criado por jaqueline.mendes - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('md_lit_situacao_selecionar');


    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    $ajaxUrlVerificarVinculo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_verificar_vinculo');

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_lit_situacao_excluir':
            try {
                $arrStrIds                  = $_POST['hdnInfraItemId'];
                $arrObjSituacaoLitigiosoDTO = array();

                if (is_array($arrStrIds)) {

                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                        $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds[$i]);
                        $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;
                    }

                } else {

                    $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                    $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds);
                    $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;
                }

                $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                $objSituacaoLitigiosoRN->excluirComTipoControle($arrObjSituacaoLitigiosoDTO, $_GET['id_tipo_processo_litigioso']);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao']));
            die;

        case 'md_lit_situacao_desativar':
            try {
                $arrStrIds = $_POST['hdnInfraItemId'];

                $arrObjSituacaoLitigiosoDTO = array();


                if (is_array($arrStrIds)) {
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                        $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds[$i]);
                        $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;
                    }
                } else {
                    $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                    $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds);

                    $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;
                }

                $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                $objSituacaoLitigiosoRN->desativarComTipoControle($arrObjSituacaoLitigiosoDTO, $_GET['id_tipo_processo_litigioso']);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao']));
            die;


        case 'md_lit_situacao_reativar':

            $strTitulo = 'Reativar Tipos de Controles Litigiosos';

            if ($_GET['acao_confirmada'] == 'sim') {

                $objInfraException      = new InfraException();
                $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                try {
                    $arrStrIds                  = $_POST['hdnInfraItemId'];
                    $arrObjSituacaoLitigiosoDTO = array();
                    if (is_array($arrStrIds)) {
                        for ($i = 0; $i < count($arrStrIds); $i++) {
                            $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                            $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds[$i]);
                            $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;
                        }
                    } else {
                        $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                        $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds);
                        $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;

                    }
                    if (array_key_exists(0, $arrObjSituacaoLitigiosoDTO)) {
                        $objConsultaSituacaoLitigiosoDTO = $arrObjSituacaoLitigiosoDTO[0];
                        $objConsultaSituacaoLitigiosoDTO->retStrSinAtivoFase();
                        $objConsultaSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->consultar($objConsultaSituacaoLitigiosoDTO);

                        if ($objConsultaSituacaoLitigiosoDTO->getStrSinAtivoFase() == 'N') {

                            $objInfraException->adicionarValidacao('Esta Situação não pode ser reativada tendo em vista que a fase associada está desativada.
                                                                    \nAcesse o botão "Fases" nesta tela para avaliar a reativação da Fase.');
                        }
                    }

                    $objInfraException->lancarValidacoes();


                    $objSituacaoLitigiosoRN->reativarComTipoControle($arrObjSituacaoLitigiosoDTO, $_GET['id_tipo_processo_litigioso']);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao']));
                die;
            }
            break;

        case 'md_lit_situacao_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Situação', 'Selecionar Situação');

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'md_lit_situacao_cadastrar') {
                if (isset($_GET['id_situacao_litigioso'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_situacao_litigioso']);
                }
            }
            break;

        case 'md_lit_situacao_visualizar_parametrizar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Consultar Parametrização de Situação', 'Consultar Parametrização de Situação');
            PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

            $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
            $objTipoControleLitigiosoDTO->retTodos();
            $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
            $objTipoControleLitigiosoRN  = new MdLitTipoControleRN();
            $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);

            $sigla = $objTipoControleLitigiosoDTO->getStrSigla() ? $objTipoControleLitigiosoDTO->getStrSigla() : '';

            $strTitulo = 'Consultar Parametrização de Situação - ' . PaginaSEI::tratarHTML($sigla);

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'md_lit_situacao_cadastrar') {
                if (isset($_GET['id_situacao_litigioso'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_situacao_litigioso']);
                }
            }
            $arrComandos[] = '<button type="button" id="btnOrientacoes" value="Orientações" onclick="modalOrientacoes()" class="infraButton">Orientações</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="window.close()" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            break;

        case 'md_lit_situacao_parametrizar':
            $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
            $objTipoControleLitigiosoDTO->retTodos();
            $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
            $objTipoControleLitigiosoRN  = new MdLitTipoControleRN();
            $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);

            $sigla = $objTipoControleLitigiosoDTO->getStrSigla() ? $objTipoControleLitigiosoDTO->getStrSigla() : '';

            $strTitulo = 'Parametrizar Situações - ' . PaginaSEI::tratarHTML($sigla);

            //checar se estou executando operação para salvar ordem OU para salvar demais dados de parametrização
            if (isset($_POST['hdnIdAlteracaoOrdem']) && $_POST['hdnIdAlteracaoOrdem'] != "") {

                $objSituacaoLitigiosoRN           = new MdLitSituacaoRN();
                $arrObjSituacaoLitigiosoDTOSalvar = array();


                $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                $objSituacaoLitigiosoDTO->retTodos();
                $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso((int)$_POST['hdnIdAlteracaoOrdem']);
                $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO);

                $ordemAtual               = $objSituacaoLitigiosoDTO->getNumOrdem();
                $objSituacaoLitigiosoDTO2 = new MdLitSituacaoDTO();

                if ($_POST['hdnIdAlteracaoOrdenacaoContraria'] != '0') {
                    $objSituacaoLitigiosoDTO2->retTodos();
                    $objSituacaoLitigiosoDTO2->setNumIdSituacaoLitigioso($_POST['hdnIdAlteracaoOrdenacaoContraria']);
                    $objSituacaoLitigiosoDTO2 = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO2);
                }


                //checando se a situacao fornecida esta subindo ou descendo de posição
                //sobe a posição do item
                if ($_POST['hdnOperacaoOrdem'] == "sobe") {
                    //incrementa o valor existente no campo ordem
                    $ordem = (int)$ordemAtual;

                    if (!is_null($objSituacaoLitigiosoDTO2)) {
                        $objSituacaoLitigiosoDTO2->setNumOrdem($ordem);
                    }
                    $objSituacaoLitigiosoDTO->setNumOrdem($ordemAtual - 1);

                } //desce a posição do item
                else {
                    if ($_POST['hdnOperacaoOrdem'] == "desce") {

                        if (!is_null($objSituacaoLitigiosoDTO2)) {

                            $ordem = (int)$ordemAtual;
                            $objSituacaoLitigiosoDTO2->setNumOrdem($ordem);
                        }
                        $objSituacaoLitigiosoDTO->setNumOrdem($ordemAtual + 1);

                    }
                }

                if (!is_null($objSituacaoLitigiosoDTO2)) {
                    array_push($arrObjSituacaoLitigiosoDTOSalvar, $objSituacaoLitigiosoDTO2);
                }

                array_push($arrObjSituacaoLitigiosoDTOSalvar, $objSituacaoLitigiosoDTO);
                $objSituacaoLitigiosoRN->parametrizarComTipoDeControle($arrObjSituacaoLitigiosoDTOSalvar, $_GET['id_tipo_processo_litigioso']);

            } else {
                if (isset($_POST['hdnCadastrarSituacaoLitigioso']) && $_POST['hdnCadastrarSituacaoLitigioso'] = 'S') {

                    $arrObjSituacaoLitigiosoDTOSalvar = array();

                    $idsObjsTela            = json_decode($_POST['hdnArraySitLitigioso']);
                    $objSituacaoLitigiosoRN = new MdLitSituacaoRN();

                    $contInst = 0;
                    $contDef  = 0;
                    $contConc = 0;
                    $idInst   = false;
                    $idDef    = false;
                    $idConc   = false;

                    //Excluir;
                    if ($_POST['hdnIdExclusao'] != '') {
                        $arrStrIds = explode(',', $_POST['hdnIdExclusao']);
                        for ($i = 0; $i < count($arrStrIds); $i++) {
                            $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                            $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds[$i]);
                            $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;
                        }
                        $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                        $objSituacaoLitigiosoRN->excluirComTipoControle($arrObjSituacaoLitigiosoDTO, $_GET['id_tipo_processo_litigioso']);
                        //Fim da Exclusao;
                    }


                    //Desativar;
                    if ($_POST['hdnIdDesativar'] != '') {
                        $arrStrIds = explode(',', $_POST['hdnIdDesativar']);
                        for ($i = 0; $i < count($arrStrIds); $i++) {
                            $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                            $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds[$i]);
                            $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;
                        }
                        $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                        $objSituacaoLitigiosoRN->desativarComTipoControle($arrObjSituacaoLitigiosoDTO, $_GET['id_tipo_processo_litigioso']);
                        //Fim Desativar;
                    }


                    //Ativar;
                    if ($_POST['hdnIdReativar'] != '') {
                        $arrStrIds = explode(',', $_POST['hdnIdReativar']);
                        for ($i = 0; $i < count($arrStrIds); $i++) {
                            $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                            $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrStrIds[$i]);
                            $arrObjSituacaoLitigiosoDTO[] = $objSituacaoLitigiosoDTO;
                        }
                        $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                        $objSituacaoLitigiosoRN->reativarComTipoControle($arrObjSituacaoLitigiosoDTO, $_GET['id_tipo_processo_litigioso']);
                        //Fim Ativar;
                    }


                    foreach ($idsObjsTela as $key => $value) {
                        $objSituacaoLitigiosoDTO3 = new MdLitSituacaoDTO();

                        $objSituacaoLitigiosoDTO3->retTodos();
                        $objSituacaoLitigiosoDTO3->setNumIdSituacaoLitigioso($value);

                        $objSituacaoLitigiosoDTO3 = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO3);

                        $instauracaoSin = (isset($_POST['instauracao']) && $_POST['instauracao'] == $value) ? 'S' : 'N';
                        $intimacaoSin   = isset($_POST['intimacao_' . $value]) ? 'S' : 'N';
                        $decisoriaSin   = isset($_POST['decisoria_' . $value]) ? 'S' : 'N';
                        $defesaSin      = (isset($_POST['defesa']) && $_POST['defesa'] == $value) ? 'S' : 'N';
                        $sinAlegacoes   = isset($_POST['alegacoes_'. $value]) ? 'S': 'N';
                        $recursalSin    = isset($_POST['recursal_' . $value]) ? 'S' : 'N';
                        $conclusivaSin  = (isset($_POST['conclusiva']) && $_POST['conclusiva'] == $value) ? 'S' : 'N';
                        $sinObrigatoria = isset($_POST['obrigatoria_'.$value]) ? 'S' : 'N';
                        $sinOpcional    = isset($_POST['opcional_' . $value]) ? 'S' : 'N';
                        $ordem          = $_POST['ordem_' . $value];

                        $objSituacaoLitigiosoDTO3->setStrSinInstauracao($instauracaoSin);
                        $objSituacaoLitigiosoDTO3->setStrSinIntimacao($intimacaoSin);
                        $objSituacaoLitigiosoDTO3->setStrSinDecisoria($decisoriaSin);
                        $objSituacaoLitigiosoDTO3->setStrSinDefesa($defesaSin);
                        $objSituacaoLitigiosoDTO3->setStrSinRecursal($recursalSin);
                        $objSituacaoLitigiosoDTO3->setStrSinConclusiva($conclusivaSin);
                        $objSituacaoLitigiosoDTO3->setStrSinOpcional($sinOpcional);
                        $objSituacaoLitigiosoDTO3->setStrSinAlegacoes($sinAlegacoes);
                        $objSituacaoLitigiosoDTO3->setNumOrdem($ordem);

                        //A situação so pode ser obrigatoria caso a situação seja livre.
                        if($objSituacaoLitigiosoRN->verificaSeSituacaoLivre($objSituacaoLitigiosoDTO3)){
                            $objSituacaoLitigiosoDTO3->setStrSinObrigatoria($sinObrigatoria);
                        } else{
                            $objSituacaoLitigiosoDTO3->setStrSinObrigatoria('N');
                        }

                        //Se a aleção foi marcada obrigatoriamente o intimação tambem deve ser
                        if($sinAlegacoes == 'S'){
                            $intimacaoSin =  'S';
                        }

                        if ($objSituacaoLitigiosoDTO3->getStrSinAtivo() === 'N') {
                            if ($objSituacaoLitigiosoDTO3->getStrSinInstauracao() === 'S') {
                                $contInst++;
                                $idInst = $value;
                            }
                            if ($objSituacaoLitigiosoDTO3->getStrSinDefesa() === 'S') {
                                $contDef++;
                                $idDef = $value;
                            }
                            if ($objSituacaoLitigiosoDTO3->getStrSinConclusiva() === 'S') {
                                $contConc++;
                                $idConc = $value;
                            }

                        } else {
                            $objSituacaoLitigiosoDTO3->setNumPrazo($_POST['prazo_' . $value]);

                            if ($instauracaoSin === 'S') {
                                $contInst++;
                            }
                            if ($defesaSin === 'S') {
                                $contDef++;
                            }
                            if ($conclusivaSin === 'S') {
                                $contConc++;
                            }
                        }

                        $arrObjSituacaoLitigiosoDTOSalvar[$value] = $objSituacaoLitigiosoDTO3;
                    }


                    if ($contConc > 1) {
                        $objSituacaoLitigiosoDTO4 = new MdLitSituacaoDTO();
                        $objSituacaoLitigiosoDTO4->setNumIdSituacaoLitigioso($value);
                        $objSituacaoLitigiosoDTO4->setStrSinConclusiva('N');
                        unset($arrObjSituacaoLitigiosoDTOSalvar[$value]);
                        $arrObjSituacaoLitigiosoDTOSalvar[$value] = $objSituacaoLitigiosoDTO4;
                    }
                    if ($contDef > 1) {
                        $objSituacaoLitigiosoDTO5 = new MdLitSituacaoDTO();
                        $objSituacaoLitigiosoDTO5->setNumIdSituacaoLitigioso($value);
                        $objSituacaoLitigiosoDTO5->setStrSinDefesa('N');
                        unset($arrObjSituacaoLitigiosoDTOSalvar[$value]);
                        $arrObjSituacaoLitigiosoDTOSalvar[$value] = $objSituacaoLitigiosoDTO5;
                    }
                    if ($contInst > 1) {
                        $objSituacaoLitigiosoDTO6 = new MdLitSituacaoDTO();
                        $objSituacaoLitigiosoDTO6->setNumIdSituacaoLitigioso($value);
                        $objSituacaoLitigiosoDTO6->setStrSinInstauracao('N');
                        unset($arrObjSituacaoLitigiosoDTOSalvar[$value]);
                        $arrObjSituacaoLitigiosoDTOSalvar[$value] = $objSituacaoLitigiosoDTO6;
                    }


                    try {
                        $objSituacaoLitigiosoRN->parametrizarComTipoDeControle($arrObjSituacaoLitigiosoDTOSalvar, $_GET['id_tipo_processo_litigioso']);
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }

                }
            }

            $bolAcaoFase = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_listar');
            if ($bolAcaoFase) {
                $arrComandos[] = '<button type="button" accesskey="A" id="btnFase" value="Fase" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_fase_listar&acao_origem=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton">F<span class="infraTeclaAtalho">a</span>ses</button>';
            }
            $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_cadastrar');
            if ($bolAcaoCadastrar) {
                $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Nova Situação" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_cadastrar&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova Situação</button>';
            }

            $arrComandos[] = '<button type="button" id="btnOrientacoes" value="Orientações" onclick="modalOrientacoes()" class="infraButton">Orientações</button>';

            $arrComandos[] = '<button type="button" accesskey="S" name="sbmCadastrarSituacaoLitigioso" id="sbmCadastrarSituacaoLitigioso" onclick="salvar()" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();

    $objSituacaoLitigiosoDTO->retStrSinInstauracao();
    $objSituacaoLitigiosoDTO->retStrSinIntimacao();
    $objSituacaoLitigiosoDTO->retStrSinDecisoria();
    $objSituacaoLitigiosoDTO->retStrSinDefesa();
    $objSituacaoLitigiosoDTO->retStrSinRecursal();
    $objSituacaoLitigiosoDTO->retStrSinConclusiva();
    $objSituacaoLitigiosoDTO->retStrSinOpcional();
    $objSituacaoLitigiosoDTO->retStrSinAtivo();
    $objSituacaoLitigiosoDTO->retNumPrazo();
    $objSituacaoLitigiosoDTO->retNumIdSituacaoLitigioso();
    $objSituacaoLitigiosoDTO->retStrNome();
    $objSituacaoLitigiosoDTO->retNumIdFaseLitigioso();
    $objSituacaoLitigiosoDTO->retStrSinObrigatoria();
    $objSituacaoLitigiosoDTO->retStrSinAlegacoes();

    $objSituacaoLitigiosoDTO->retStrNomeFase();

    PaginaSEI::getInstance()->prepararOrdenacao($objSituacaoLitigiosoDTO, 'Ordem', InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSituacaoLitigiosoRN = new MdLitSituacaoRN();

    $idTipoControle = $_GET['id_tipo_processo_litigioso'];
    $objSituacaoLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControle);
    $arrObjSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->listarComTipoDeControle($objSituacaoLitigiosoDTO, $idTipoControle);

    $numRegistros = count($arrObjSituacaoLitigiosoDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        if ($_GET['acao'] == 'md_lit_situacao_selecionar' || $_GET['acao'] == 'md_lit_situacao_visualizar_parametrizar' ) {
            $bolAcaoReativar  = false;
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_consultar');
            $bolAcaoExcluir   = false;
            $bolAcaoDesativar = false;
            $bolCheck         = true;
        } else {
            if ($_GET['acao'] == 'md_lit_situacao_reativar') {
                $bolAcaoReativar  = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_reativar');
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_consultar');
                $bolAcaoAlterar   = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_alterar');
                $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_excluir');
                $bolAcaoDesativar = false;
            } else {
                $bolAcaoReativar  = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_reativar');
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_consultar');
                $bolAcaoAlterar   = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_alterar');
                $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_excluir');
                $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_situacao_desativar');
            }
        }


        if ($bolAcaoDesativar) {
            $bolCheck         = true;
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?id_situacao_litigioso=' . $_GET['id_situacao_litigioso'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=md_lit_situacao_desativar&acao_origem=' . $_GET['acao']);
        }

        $bolCheck        = true;
        $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_reativar&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');


        if ($bolAcaoExcluir) {
            $bolCheck       = true;
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_excluir&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao']);
        }


        $strResultado = '';

        $strCaptionTabela = 'Situações';

        $strResultado .= '<table width="99%" class="infraTable" id=tbSituacao name=tbSituacao summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<thead>';
        $strResultado .= '<tr>';


        $strResultado .= '<th class="infraTh" style="display: none">Ordem</th>' . "\n";
        $strResultado .= '<th class="infraTh" style="min-width: 60px;"><span style="font-size: 1em; font-weight: bold;text-align: center;">Fase</span></th>' . "\n";
        $strResultado .= '<th class="infraTh" style="min-width: 80px">Situação</th>' . "\n";
        $strResultado .= '<th class="infraTh"><label class="labelTH">&nbsp;Ordem&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh" name="rdInstConcl[]"><label class="labelTH">&nbsp;Instauração&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh"><label class="labelTH">&nbsp;Intimação&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh"><label class="labelTH">&nbsp;Defesa&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh"><label class="labelTH">&nbsp;Alegações&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh"><label class="labelTH">&nbsp;Decisão&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh"><label class="labelTH">&nbsp;Recurso&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh" name="rdInstConcl[]"><label class="labelTH">&nbsp;Conclusão&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh"><label class="labelTH">&nbsp;Opcional&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh"><label class="labelTH">&nbsp;Obrigatória&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh" align="center"> <label class="labelTH" >&nbsp;Prazo (dias)&nbsp;</label></th>' . "\n";
        $strResultado .= '<th class="infraTh" style="min-width: 60px;">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strResultado .= '</thead>';
        $strCssTr     = '';

        for ($i = 0; $i < $numRegistros; $i++) {
            $idLinha = $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso();

            if ($arrObjSituacaoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                if ($_GET['id_situacao_litigioso'] == $idLinha) {
                    $strCssTr = ($strCssTr == '<tr data-linha="'.$idLinha.'" id="sitLitTable_' . $idLinha . '" name="sitLitTable_' . $idLinha . '" class="infraTrAcessada">') ?
                        '<tr data-linha="'.$idLinha.'" id="sitLitTable_' . $idLinha . '" name="sitLitTable_' . $idLinha . '" class="infraTrClara">' :
                        '<tr data-linha="'.$idLinha.'" id="sitLitTable_' . $idLinha . '" name="sitLitTable_' . $idLinha . '" class="infraTrAcessada">';
                } else {
                    $strCssTr = (($i + 2) % 2) ? '<tr data-linha="'.$idLinha.'" id="sitLitTable_' . $idLinha . '" name="sitLitTable_' . $idLinha . '" class="infraTrClara">' :
                        '<tr data-linha="'.$idLinha.'" id="sitLitTable_' . $idLinha . '" name="sitLitTable_' . $idLinha . '" class="infraTrClara">';
                }
            } else {
                $strCssTr = '<tr data-linha="'.$idLinha.'" id="sitLitTable_' . $idLinha . '" name="sitLitTable_' . $idLinha . '" class="trVermelha">';
            }

            $idAnterior     = $i - 1;
            $idPosterior    = $i + 1;
            $idObjAnterior  = isset($arrObjSituacaoLitigiosoDTO[$idAnterior]) ? $arrObjSituacaoLitigiosoDTO[$idAnterior]->getNumIdSituacaoLitigioso() : 0;
            $idObjPosterior = isset($arrObjSituacaoLitigiosoDTO[$idPosterior]) ? $arrObjSituacaoLitigiosoDTO[$idPosterior]->getNumIdSituacaoLitigioso() : 0;

            $totalRegistros        = count($arrObjSituacaoLitigiosoDTO);
            $boolDesabilitarSubir  = $i === 0 ? '1' : '0';
            $boolDesabilitarDescer = ($totalRegistros - 1) === $i ? '1' : '0';

            $strImagem = '';
            if($_GET['acao'] != 'md_lit_situacao_visualizar_parametrizar' ){
                $strImagem = '<a onclick="moverAcima(this)"><img src="'
                    . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/seta_acima.gif" style="margin: 2px -7px 12px 2px;" /></a>';

                $strImagem .= '<a onclick="moverAbaixo(this)" "><img src="'
                    . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/seta_abaixo.gif" /> </a>';
            }

            $instauracao = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinInstauracao() === 'S' ? 'checked="checked"' : '';
            $intimacao   = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinIntimacao() === 'S' ? 'checked="checked"' : '';
            $decisoria   = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinDecisoria() === 'S' ? 'checked="checked"' : '';
            $defesa     = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinDefesa() === 'S' ? 'checked="checked"' : '';
            $recursal   = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinRecursal() === 'S' ? 'checked="checked"' : '';
            $conclusiva = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinConclusiva() === 'S' ? 'checked="checked"' : '';
            $disabled   = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinAtivo() === 'N' ? 'disabled="disabled"' : '';
            $opcional   = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinOpcional() === 'S' ? 'checked="checked"' : '';
            $obrigatoria   = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinObrigatoria() === 'S' ? 'checked="checked"' : '';
            $alegacoes   = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinAlegacoes() === 'S' ? 'checked="checked"' : '';
            $readOnlyPrazo = $arrObjSituacaoLitigiosoDTO[$i]->getStrSinDefesa() === 'S' ? '' : 'readonly';

            $strResultado .= $strCssTr;
            $idSituacao = $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso();
            $strResultado .= '<td style="display: none"><input type="hidden" value="' . ($i + 1) . '" name="ordem_' . $idSituacao . '"> </td>';
            $strResultado .= '<td style="word-break:break-all" class="fase">' . PaginaSEI::tratarHTML($arrObjSituacaoLitigiosoDTO[$i]->getStrNomeFase()) . '</td>';
            $strResultado .= '<td style="word-break:break-all" class="situacao">' . PaginaSEI::tratarHTML($arrObjSituacaoLitigiosoDTO[$i]->getStrNome()) . '</td>';
            $strResultado .= '<td align="center">' . $strImagem . '</td>';
            $strResultado .= '<td align="center" title="Instauração"><input class="instauracao" ' . $instauracao . ' ' . $disabled . ' type="radio" onchange="controlarRadios(this)" name="instauracao" data-linha="'.$idSituacao.'" value="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Intimação"><input class="intimacao" type="checkbox" ' . $intimacao . ' ' . $disabled . ' onchange="controlarRadios(this)" name="intimacao_' . $idSituacao . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Defesa"><input class="defesa" ' . $defesa . ' ' . $disabled . ' type="radio" onchange="controlarRadios(this)" name="defesa" data-linha="'.$idSituacao.'"  value="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Alegações"><input class="alegacoes" type="checkbox"' . $alegacoes . ' ' . $disabled . ' onmouseup="selecionarAlegacoes('.$idSituacao.')" onchange="selecionarAlegacoes('.$idSituacao.')" name="alegacoes_' . $idSituacao . '" data-linha="'.$idSituacao.'"  value="'.$idSituacao.'"> </td>';
            $strResultado .= '<td align="center" title="Decisão"><input class="decisoria" type="checkbox"' . $decisoria . ' ' . $disabled . ' onchange="controlarRadios(this)" name="decisoria_' . $idSituacao . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Recurso"><input class="recursal" type="checkbox"' . $recursal . ' ' . $disabled . ' onchange="controlarRadios(this)" name="recursal_' . $idSituacao . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Conclusão"><input class="conclusiva" type="radio" ' . $conclusiva . ' ' . $disabled . '  class="conclusao" name="conclusiva" onchange="controlarRadios(this)" data-linha="'.$idSituacao.'"  value="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Opcional"><input class="opcional" type="checkbox"' . $opcional . ' ' . $disabled . ' onclick="selecionarOpcional(this)" name="opcional_' . $idSituacao . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Obrigatória"><input class="obrigatoria" type="checkbox"' . $obrigatoria . ' ' . $disabled . ' onclick="selecionarObrigatoria('.$idSituacao.')" name="obrigatoria_'.$idSituacao.'" data-linha="'.$idSituacao.'"> </td>';
            $strResultado .= '<td align="center" title="Prazo (dias)"> <input class="prazo" maxlength="3" size="1" ' . $disabled . ' '.$readOnlyPrazo.'   onkeypress="return SomenteNumero(event)"  name="prazo_' . $idSituacao . '" type="text"  value="' . PaginaSEI::tratarHTML($arrObjSituacaoLitigiosoDTO[$i]->getNumPrazo()) . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $idSituacao);

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&id_situacao_litigioso=' . $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Situação" alt="Consultar Situação" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&id_situacao_litigioso=' . $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Situação" alt="Alterar Situação" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId        = $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjSituacaoLitigiosoDTO[$i]->getStrNome()));
            }

            if ($bolAcaoDesativar && $arrObjSituacaoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(this,\'' . $strId . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Situação" alt="Desativar Situação" class="infraImg"  id="imgDesativar_' . $strId . '"/></a>&nbsp;';
            } elseif($bolAcaoReativar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(this,\'' . $strId . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Situação" alt="Reativar Situação" class="infraImg" /></a>&nbsp;';
            }


            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(this,\'' . $strId . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Situação" alt="Excluir Situação" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";

        }
        $strResultado .= '</table>';
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
#frmSituacaoLitigiosoLista ol { counter-reset: item; padding-left: 10px; }
#frmSituacaoLitigiosoLista li{ display: block }
.labelTH{width: 67%;float: left;    font-size: 1em;font-weight: bold;text-align: center;color: #000;}
.infraImg.imagemAjuda{float: right;width: 20%;}
#frmSituacaoLitigiosoLista li:before { content: counters(item, ".") " "; counter-increment: item }
th a img.infraImg{position: relative;top: 0px;}
th label.labelTH{position: relative;top: 0px;float: unset;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
?>
<script>
    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(el, id) {
        var descricao = $('#tbSituacao tr[data-linha="'+id+'"] td.situacao').text();
        if (confirm("Confirma desativação da Situação \"" + descricao + "\"?")) {
            verificarVinculo(id).success(function (r) {
                var possuiVinculo = $(r).find('Vinculo').text();
                if (possuiVinculo) {
                    alert('A desativação da situação não é permitida, pois existem registros vinculados');
                } else {
                    desativarTr(el, id);
                }
            });
        }
    }


    <? } ?>

    function acaoReativar(el, id) {
        var descricao = $('#tbSituacao tr[data-linha="'+id+'"] td.situacao').text();;
        if (confirm("Confirma reativação da Situação \"" + descricao + "\"?")) {
            ativarTr(el, id);
        }
    }


    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(el, id) {
        var descricao = $('#tbSituacao tr[data-linha="'+id+'"] td.situacao').text();
        if (confirm("Confirma exclusão da Situação \"" + descricao + "\"?")) {
            verificarVinculo(id).success(function (r) {
                var possuiVinculo = $(r).find('Vinculo').text();
                if (possuiVinculo) {
                    alert('A exclusão da situação não é permitida, pois existem registros vinculados');
                } else {
                    // excluirTr(tr, id);
                    excluirTr(id);
                }
            });
        }
    }


    <? } ?>

</script>
<?
#PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmSituacaoLitigiosoLista" method="post"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

    <?php PaginaSEI::getInstance()->abrirAreaDados();
    echo $strHtmlSigilosos;
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>

    <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

    <input type="hidden" name="hdnArraySitLitigioso" id="hdnArraySitLitigioso" value=""/>
    <input type="hidden" name="hdnIdAlteracaoOrdem" id="hdnIdAlteracaoOrdem" value=""/>
    <input type="hidden" name="hdnOperacaoOrdem" id="hdnOperacaoOrdem" value=""/>
    <input type="hidden" name="hdnIdAlteracaoOrdenacaoContraria" id="hdnIdAlteracaoOrdenacaoContraria" value=""/>
    <input type="hidden" name="hdnCadastrarSituacaoLitigioso" id="hdnCadastrarSituacaoLitigioso" value=""/>
    <input type="hidden" name="hdnIdExclusao" id="hdnIdExclusao" value=""/>
    <input type="hidden" name="hdnIdDesativar" id="hdnIdDesativar" value=""/>
    <input type="hidden" name="hdnIdReativar" id="hdnIdReativar" value=""/>

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>

<script type="text/javascript">

    var arrIdExclusao = [];
    var arrIdDesativar = [];
    var arrIdReativar = [];



    function inicializar() {
        validarCampos();

        if ('<?= $_GET['acao'] ?>'=='md_lit_situacao_visualizar_parametrizar'){
            //desabilitando somente os inputs da tabela, o infraDesabilitarCamposDiv remove a imagem de consultar situação
            var el,els, e = 0;
            els = document.getElementById('divInfraAreaTabela').getElementsByTagName('input');
            while (el = els.item(e++)){
                if (el.type != 'hidden'){
                    if (INFRA_IE > 0){
                        el.disabled=true;
                    }else{
                        if (el.type == 'checkbox' || el.type == 'radio'){
                            el.disabled=true;
                        }else{
                            el.readOnly = true;
                        }
                    }
                }
            }

        }
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome da Fase.');
            document.getElementById('txtNome').focus();
            return false;
        }

        return true;
    }

    function preencherHddIds() {

        //nao estou alterando a ordem, estou salvando apenas os demais dados de parametrizaçao
        document.getElementById("hdnIdAlteracaoOrdem").value = '';
        document.getElementById("hdnOperacaoOrdem").value = '';
        var hdnIdExclusao = document.getElementById('hdnIdExclusao');
        var hdnIdDesativar = document.getElementById('hdnIdDesativar');
        var hdnIdReativar = document.getElementById('hdnIdReativar');

        hdnIdExclusao.value = arrIdExclusao;
        hdnIdDesativar.value = arrIdDesativar;
        hdnIdReativar.value = arrIdReativar;


        var objs = $("tr[id^='sitLitTable']");
        var arrayIds = new Array();


        for (i = 0; i < objs.length; i++) {
            idHtml = objs[i].id;
            idBanco = idHtml.split('_')[1];
            arrayIds.push(idBanco);
        }


        document.getElementById("hdnArraySitLitigioso").value = JSON.stringify(arrayIds);
    }

    function salvar() {
        var frm = document.getElementById('frmSituacaoLitigiosoLista')
        var hdnCadastrarSituacaoLitigioso = document.getElementById('hdnCadastrarSituacaoLitigioso');
        if (validarParametrizacao()) {
            preencherHddIds();
            hdnCadastrarSituacaoLitigioso.value = 'S';
            frm.submit();
        }
    }

    function SomenteNumero(e) {
        var tecla = (window.event) ? event.keyCode : e.which;
        if ((tecla > 47 && tecla < 58)) return true;
        else {
            if (tecla == 8 || tecla == 0) return true;
            else return false;
        }
    }


    function validarParametrizacao() {

        if ($('[name="tbSituacao"]').length == 0) {
            return false;
        }
        var instauracao = false, intimacao = false,
            defesa = false, decisoria = false,
            intimacaoDecisao = false, recurso = false,
            decisaoRecurso = false,
            conclusao = false, temIntimacaoDaConclusiva = false,
            prazoDefesa = false, prazoAlegacoes = false;


        var salvar = true;

        $('#tbSituacao tr[data-linha]').each(function (key, elementoTr) {
            var linha = $(elementoTr ).data('linha');
            var linhaLivre = isSituacaoLivre(linha);

            if(!linhaLivre) {
                if (!instauracao) {
                    if ($('input.instauracao[data-linha="'+linha+'"]').prop('checked') == false){
                        return false;
                    }

                    instauracao = true;
                    return true;
                }

                if($('input.alegacoes[data-linha="'+linha+'"]').prop('checked') == true){
                    //verifica se o prazo foi informado
                    if($('input.prazo[data-linha="'+linha+'"]').val() == ''){
                        prazoAlegacoes = true;
                    }
                }

                if (!intimacao) {
                    if ($('input.intimacao[data-linha="'+linha+'"]').prop('checked') == false){
                        return false;
                    }

                    intimacao = true;
                    return true;
                }

                if ($('input.defesa[data-linha="'+linha+'"]').prop('checked') == true){
                    defesa = true;
                    if($('input.prazo[data-linha="'+linha+'"]').val() == ''){
                        prazoDefesa = true;
                    }

                    return true;
                }

                //No fluxo, após a defesa não deve ser obrigatória somente a decisão, mas pode ser intimação também pois a decisão pode ser feita posteriormente
                if (!decisoria) {
                     if ($('input.intimacao[data-linha="'+linha+'"]').prop('checked') == true){
                         return true;
                     }

                    if($('input.decisoria[data-linha="'+linha+'"]').prop('checked') == false){
                        return false;
                    } else {
                        decisoria = true;
                        return true;
                    }
                }

                if (!intimacaoDecisao) {
                    if($('input.intimacao[data-linha="'+linha+'"]').prop('checked') == false){
                        return false;
                    } else {
                        intimacaoDecisao = true;
                        recurso = false;
                        return true;
                    }
                }

                if (intimacaoDecisao) {
                    if(!recurso){
                        if ($('input.recursal[data-linha="'+linha+'"]').prop('checked') == true) {
                            recurso = true;
                            decisaoRecurso = false;
                            return true;
                        }
                    }

                    if(!conclusao) {
                        if($('input.conclusiva[data-linha="'+linha+'"]').prop('checked') == true){
                            //No fluxo, a Situação de Conclusão deverá ser antecedida SEMPRE por uma Intimação, ou seja, não deverá existir uma conclusão, sem que tenha existido uma Intimação imediatamente anterior.
                            var linhaAnterior = $('#tbSituacao tr[data-linha="'+linha+'"]').prev().data('linha');

                            if (!isSituacaoLivre(linhaAnterior) && $('input.intimacao[data-linha="'+linhaAnterior+'"]').prop('checked') == true) {
                                temIntimacaoDaConclusiva = true;
                            }

                            conclusao = true;
                            return true;
                        }
                    }
                }

                if($('input.decisoria[data-linha="'+linha+'"]').prop('checked') == true){
                    var linhaPosterior = $('#tbSituacao tr[data-linha="'+linha+'"]').next().data('linha');
                    if($('input.intimacao[data-linha="'+linhaPosterior+'"]').prop('checked') == false){
                        intimacaoDecisao = false;
                    }
                }

                //No fluxo, após o recurso não deve ser obrigatória somente a decisão, mas pode ser intimação também pois a decisão pode ser feita posteriormente.
                if (recurso) {
                    if ($('input.intimacao[data-linha="'+linha+'"]').prop('checked') == true) {
                        return true;
                    }
                    if ($('input.decisoria[data-linha="'+linha+'"]').prop('checked') == false) {
                        return false;
                    } else {
                        decisaoRecurso = true;
                        intimacaoDecisao = false;
                        return true;
                    }
                }

            }

        });

        //msgs
        if (!instauracao) {
            alert('A Situação marcada como Instauração deverá ser a primeira a acontecer. ');
            salvar = false;
            return;
        }

        if (instauracao && !intimacao) {
            alert('Após a Instauração é obrigatória a existência de ao menos uma Situação de Intimação. ' +
                'Para inserir Situações que antecedam a Intimação, elas devem estar sem marcação de parâmetros especiais.');
            salvar = false;
            return;
        }

        if (!decisoria) {
            alert('Após a Intimação da Instauração e/ou a apresentação da Defesa é obrigatória a existência de ao menos uma ' +
                'Decisão, mesmo que uma Intimação a anteceda. Para inserir Situações que antecedam a Decisão, elas devem estar sem marcação.');
            salvar = false;
            return;
        }

        if (!intimacaoDecisao) {
            alert('Após qualquer Decisão é obrigatória a existência de ao menos uma Intimação. ' +
                'Para inserir Situações que antecedam a Intimação, elas devem estar sem marcação de parâmetros especiais.');
            salvar = false;
            return;
        }

        if ((!conclusao && !recurso) || !temIntimacaoDaConclusiva) {
            alert('A Conclusão só pode ser realizada caso tenha existido uma Intimação imediatamente anterior a ela. Para inserir Situações entre a Conclusão e a Intimação, elas devem estar sem marcação de parâmetros especiais.');
            salvar = false;
            return;
        }

        if (recurso && !decisaoRecurso) {
            alert('Após cada Recurso é obrigatória a existência de ao menos uma Decisão, mesmo que uma Intimação a anteceda. ' +
                'Para inserir Situações que antecedam a Decisão, elas devem ser do tipo Intimação, ou estar sem marcação de parâmetros especiais.');
            salvar = false;
            return;
        }

        if(prazoDefesa){
            alert('O prazo é obrigatório para a situção marcada como Defesa.');
            salvar = false;
            return;
        }

        if(prazoAlegacoes){
            alert('O prazo é obrigatório para a situção marcada como Alegações.');
            salvar = false;
            return;
        }

        return salvar;

    }

    function controlarRadios(element){
        var linha = $(element).data('linha');

        //desabilitando o checkbox Opcional
        $('input.opcional[data-linha="'+linha+'"]').prop('checked', false);

        if ($('#tbSituacao tr[data-linha="'+linha+'"]').hasClass('trVermelha')) {
            limparLinha(linha);
            return;
        }

        var checkar = false;
        if ($(element).prop('checked')) {
            checkar = true;
        }

        if($('input.alegacoes[data-linha="'+linha+'"]').prop('checked') == true && $('input.intimacao[data-linha="'+linha+'"]').prop('checked') == false){
            $('input.intimacao[data-linha="'+linha+'"]').prop('checked', true);
            alert('Não é possível desmarcar a Intimação desta Situação, pois ela está parametrizada como Alegações e por esse motivo a Intimação deve obrigatoriamente estar parametrizada');
            return false;
        }

        //garante que só vai checar 1 por linha
        $('input[data-linha="'+linha+'"]:checked').prop('checked', false);

        $(element).prop('checked', checkar);

        validarCampos();
    }

    function controlarImputPrazo(){
        //var table = document.getElementById('tbSituacao');
        if ($('[name="tbSituacao"]').length == 0) {
            return false;
        }

        $('#tbSituacao tr[data-linha]').each(function (key, elementoTr) {
            var linha = $(elementoTr).data('linha');

            if($('input.defesa[data-linha="'+linha+'"]').prop('checked') == true || $('input.alegacoes[data-linha="'+linha+'"]').prop('checked') == true){
                $('input.prazo[data-linha="'+linha+'"]').prop('readonly', false).show();
            }else{
                $('input.prazo[data-linha="'+linha+'"]').prop('readonly', true).val('').hide();

            }

        });

    }

    function controlarCheckboxOpcional(){
        // var table = document.getElementById('tbSituacao');
        if ($('[name="tbSituacao"]').length == 0) {
            return false;
        }

        var primeiraIntimacao = true;
        $('#tbSituacao tr[data-linha]').each(function (key, elementoTr) {
            var linha = $(elementoTr).data('linha');

            //verificar se opcional pode ser checked
            //so pode se for defesa ou recursal ou decisão ou a partir da segunda intimação
            if( $('input.defesa[data-linha="'+linha+'"]').prop('checked') == true || $('input.recursal[data-linha="'+linha+'"]').prop('checked') == true) {
                $('input.opcional[data-linha="' + linha + '"]').prop('disabled', false).prop('checked', true);
                //alegacoes deve ter intimação e não pode ser opcional
             }else if($('input.alegacoes[data-linha="'+linha+'"]').prop('checked') == true){
                 $('input.opcional[data-linha="'+linha+'"]').prop('disabled', true).prop('checked', false);
            }else if(!primeiraIntimacao && $('input.intimacao[data-linha="'+linha+'"]').prop('checked') == true || $('input.decisoria[data-linha="'+linha+'"]').prop('checked') == true){
                $('input.opcional[data-linha="'+linha+'"]').prop('disabled', false);
            }else{
                $('input.opcional[data-linha="'+linha+'"]').prop('disabled', true).prop('checked', false);
            }

            //passou da primeira intimação
            if($('input.intimacao[data-linha="'+linha+'"]').prop('checked') == true){
                primeiraIntimacao = false;
            }
        });

    }
    function retornaProximaTr(tr) {
        if (tr == null) {
            return false;
        }
        var proximaTr = tr.nextSibling;
        while (proximaTr != null && proximaTr.nodeType != null && proximaTr.nodeType != 1) {
            proximaTr = proximaTr.nextSibling;
        }
        return proximaTr;
    }

    function retornaTrAnterior(tr) {
        if (tr == null) {
            return false;
        }
        var trAnterior = tr.previousSibling;
        while (trAnterior != null && trAnterior.nodeType != null && trAnterior.nodeType != 1) {
            trAnterior = trAnterior.previousSibling;
        }
        return trAnterior;
    }

    function moverAbaixo(el) {
        var tr = el.parentNode.parentNode;
        var tb = tr.parentNode;
        var proximaTr1 = retornaProximaTr(tr);
        var proximaTr2 = retornaProximaTr(proximaTr1)
        if (proximaTr2) {
            reordenarTr(tr, proximaTr1)
            tb.insertBefore(tr, proximaTr2);
        }
    }

    function moverAcima(el) {
        var tr = el.parentNode;
        while (tr != null) {
            if (tr.nodeName == 'TR') {
                break;
            }
            tr = tr.parentNode;
        }
        var tb = tr.parentNode;
        var trAnterior = retornaTrAnterior(tr);
        if (trAnterior) {
            reordenarTr(trAnterior, tr);
            tb.insertBefore(tr, trAnterior);
        }
    }

    function reordenarTr(tr, proximaTr) {
        var td1 = tr.getElementsByTagName('td');
        var ordem1 = td1[0].children[0];

        var td2 = proximaTr.getElementsByTagName('td');
        var ordem2 = td2[0].children[0];

        var ordemAux = ordem1.value;
        ordem1.value = ordem2.value;
        ordem2.value = ordemAux;
    }

    function excluirTr(id) {
        arrIdExclusao.push(id);
        $('#tbSituacao tr[data-linha="'+id+'"]').remove();
        // tr.remove();
    }

    function desativarTr(element, id) {
        var img = $(element).children();
        $(element).click(function () {
            acaoReativar(element, id);
        });

        img.attr('src', '/infra_css/imagens/reativar.gif');
        img.attr('title', 'Reativar Situação');
        $('#tbSituacao tr[data-linha="'+id+'"]').attr('class', 'trVermelha');
        limparLinha(id);
        habilitarDesabilitarLinha(id, true);

        //remover o id do array arrIdReativar
        var idx = arrIdReativar.indexOf(id);
        if (idx !== -1) {
            arrIdReativar.splice(idx, 1);
        }
        arrIdDesativar.push(id);

    }

    function ativarTr(element, id) {
        var img = $(element).children();
        $(element).click(function () {
            acaoDesativar(element, id);
        });

        img.attr('src', '/infra_css/imagens/desativar.gif');
        img.attr('title', 'Desativar Situação');
        $('#tbSituacao tr[data-linha="'+id+'"]').attr('class', 'infraTrClara');
        limparLinha(id);
        habilitarDesabilitarLinha(id, false);

        //remover o id do array arrIdReativar
        var idx = arrIdDesativar.indexOf(id);
        if (idx !== -1) {
            arrIdDesativar.splice(idx, 1);
        }
        arrIdReativar.push(id);

    }

    function limparLinha(idLinha) {
        $('input[data-linha="'+idLinha+'"]').prop('checked', false);
        $('input[data-linha="'+idLinha+'"][type="text"]').val('');
    }

    function habilitarDesabilitarLinha(linha, disabled) {
        $('input[data-linha="'+linha+'"]').prop('disabled', disabled);
    }

    function verificarVinculo(id) {
        return $.ajax({
            url: '<?=$ajaxUrlVerificarVinculo?>',
            method: 'POST',
            data: {idMdLitSituacao: id},
            dataType: 'XML'
        });
    }

    function modalOrientacoes(){
        infraAbrirJanela('<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_orientacao') ?>',
            'orientacoes',
            780,
            580);
    }

    function selecionarOpcional(element){
        var linha = $(element).data('linha');
        if($('input.defesa[data-linha="'+linha+'"]').prop('checked')){
            alert("A situação defesa e obrigatório ser opcional!");
            $(element).prop('checked',true);
        }
        if($('input.recursal[data-linha="'+linha+'"]').prop('checked')){
            alert("A situação recurso e obrigatório ser opcional!");
            $(element).prop('checked',true);
        }
        selecionarObrigatoria(linha);

        return true;
    }

    /**
     * Situação livre é caraterizado quando não ha nenhuma sinalização de instrução, Intimação, defesa, decisão, recurso ou conclusão
     * @param idLinha
     * @returns {boolean}
     */
    function isSituacaoLivre(idLinha){
        var qtdPrenchido = $('tr#sitLitTable_'+idLinha+'> td > input:checked').not('[name="obrigatoria_'+idLinha+'"], [type="hidden"]').length;

        if(qtdPrenchido > 0){
            return false;
        }

        return true;
    }

    function selecionarObrigatoria(linha){
        if(!isSituacaoLivre(linha)){
            $('input.obrigatoria[data-linha="'+linha+'"]').prop('checked', false).prop('disabled', true);
        } else{
            $('input.obrigatoria[data-linha="'+linha+'"]').prop('disabled', false);
            if($('input.obrigatoria[data-linha="'+linha+'"]').prop('checked')){
                $('input[type="radio"][data-linha="'+linha+'"], input[type="checkbox"][data-linha="'+linha+'"]').not('input[data-linha="'+linha+'"].obrigatoria').prop('disabled', 'true');
            } else {
                $('input[type="radio"][data-linha="'+linha+'"], input[type="checkbox"][data-linha="'+linha+'"]').not('input[data-linha="'+linha+'"].obrigatoria, input[data-linha="'+linha+'"].opcional').prop('disabled', false)
            }
        }
    }

    function validarCheckboxObrigatoria() {
        $('[name^="obrigatoria_"]').each(function(key, element){
            var linha = $(element).data('linha');
            selecionarObrigatoria(linha);
        });
    }

    function selecionarAlegacoes(linha){
        //caso a alegação esteja marcada marca a intimação tambem
        if($('input.alegacoes[data-linha="'+linha+'"]').prop('checked') == true){
            //limpa a linha pra garantir
              limparLinha(linha);
            //marca a alegação e a intimação
            $('input.alegacoes[data-linha="'+linha+'"]').prop('checked', true);
            $('input.intimacao[data-linha="'+linha+'"]').prop('checked', true);
            $('input.opcional[data-linha="'+linha+'"]').prop('checked', false).prop('disabled', true);
        }

        validarCampos();

    }

    function validarCampos(){
        controlarCheckboxOpcional();

        //revalida o campo obrigatoria
        validarCheckboxObrigatoria();

        controlarImputPrazo();
    }


</script>