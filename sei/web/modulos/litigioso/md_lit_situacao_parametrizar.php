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
        $strResultado .= '<th class="infraTh" style="min-width: 101px;"><span style="font-size: 1em; font-weight: bold;text-align: center;">Fase</span></th>' . "\n";
        $strResultado .= '<th class="infraTh" style="min-width: 218px">Situação</th>' . "\n";
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
        $strResultado .= '<th class="infraTh" style="min-width: 120px;">Ações</th>' . "\n";
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
                    . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/mover_acima.svg?'.Icone::VERSAO.'" style="margin: 2px -7px 12px 2px;" /></a>';

                $strImagem .= '<a onclick="moverAbaixo(this)" "><img src="'
                    . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/mover_abaixo.svg?'.Icone::VERSAO.'" /> </a>';
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

            $vItemPreenchidoDesativar = '0';
            if($instauracao || $intimacao || $defesa || $alegacoes || $decisoria || $recursal || $conclusiva || $opcional || $obrigatoria){
                $vItemPreenchidoDesativar = '1';
            }

            $strResultado .= $strCssTr;
            $idSituacao = $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso();
            $strResultado .= '<td style="display: none"><input type="hidden" value="' . ($i + 1) . '" name="ordem_' . $idSituacao . '"><input type="hidden" value="' . ($vItemPreenchidoDesativar) . '" name="vItemPreenchidoDesativar_' . $idSituacao . '" id="vItemPreenchidoDesativar_' . $idSituacao . '"> </td>';
            $strResultado .= '<td class="fase">' . PaginaSEI::tratarHTML($arrObjSituacaoLitigiosoDTO[$i]->getStrNomeFase()) . '</td>';
            $strResultado .= '<td class="situacao">' . PaginaSEI::tratarHTML($arrObjSituacaoLitigiosoDTO[$i]->getStrNome()) . '</td>';
            $strResultado .= '<td align="center">' . $strImagem . '</td>';
            $strResultado .= '<td align="center" title="Instauração"><input id="instauracao_' . $idLinha . '" class="instauracao infraRadio" ' . $instauracao . ' ' . $disabled . ' type="radio" onchange="controlarRadios(this)" name="instauracao" data-linha="'.$idSituacao.'" value="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Intimação"><input id="intimacao_' . $idLinha . '" class="intimacao infraCheckbox" type="checkbox" ' . $intimacao . ' ' . $disabled . ' onchange="controlarRadios(this)" name="intimacao_' . $idSituacao . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Defesa"><input id="defesa_' . $idLinha . '" class="defesa infraRadio" ' . $defesa . ' ' . $disabled . ' type="radio" onchange="controlarRadios(this)" name="defesa" data-linha="'.$idSituacao.'"  value="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Alegações"><input id="alegacoes_' . $idLinha . '" class="alegacoes infraCheckbox" type="checkbox"' . $alegacoes . ' ' . $disabled . ' onmouseup="selecionarAlegacoes('.$idSituacao.')" onchange="selecionarAlegacoes('.$idSituacao.')" name="alegacoes_' . $idSituacao . '" data-linha="'.$idSituacao.'"  value="'.$idSituacao.'"> </td>';
            $strResultado .= '<td align="center" title="Decisão"><input id="decisoria_' . $idLinha . '" class="decisoria infraCheckbox" type="checkbox"' . $decisoria . ' ' . $disabled . ' onchange="controlarRadios(this)" name="decisoria_' . $idSituacao . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Recurso"><input id="recursal_' . $idLinha . '" class="recursal infraCheckbox" type="checkbox"' . $recursal . ' ' . $disabled . ' onchange="controlarRadios(this)" name="recursal_' . $idSituacao . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Conclusão"><input id="conclusiva_' . $idLinha . '" class="conclusiva infraRadio" type="radio" ' . $conclusiva . ' ' . $disabled . '  class="conclusao" name="conclusiva" onchange="controlarRadios(this)" data-linha="'.$idSituacao.'"  value="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Opcional"><input id="opcional_' . $idLinha . '" class="opcional infraCheckbox" type="checkbox"' . $opcional . ' ' . $disabled . ' onclick="selecionarOpcional(this)" name="opcional_' . $idSituacao . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center" title="Obrigatória"><input id="obrigatoria_' . $idLinha . '" class="obrigatoria infraCheckbox" type="checkbox"' . $obrigatoria . ' ' . $disabled . ' onclick="selecionarObrigatoria('.$idSituacao.')" name="obrigatoria_'.$idSituacao.'" data-linha="'.$idSituacao.'"> </td>';
            $strResultado .= '<td align="center" title="Prazo (dias)"> <input class="prazo infraText form-control" maxlength="3" size="1" ' . $disabled . ' '.$readOnlyPrazo.'   onkeypress="return SomenteNumero(event)"  name="prazo_' . $idSituacao . '" type="text"  value="' . PaginaSEI::tratarHTML($arrObjSituacaoLitigiosoDTO[$i]->getNumPrazo()) . '" data-linha="'.$idSituacao.'"></td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $idSituacao);

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&id_situacao_litigioso=' . $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg?'.Icone::VERSAO.'" title="Consultar Situação" alt="Consultar Situação" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&id_situacao_litigioso=' . $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg?'.Icone::VERSAO.'" title="Alterar Situação" alt="Alterar Situação" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId        = $arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjSituacaoLitigiosoDTO[$i]->getStrNome()));
            }

            if ($bolAcaoDesativar && $arrObjSituacaoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg?'.Icone::VERSAO.'" title="Desativar Situação" alt="Desativar Situação" class="infraImg"  id="imgDesativar_' . $strId . '" onclick="acaoDesativar(this,\'' . $strId . '\',1);"/></a>&nbsp;';
            } elseif($bolAcaoReativar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg?'.Icone::VERSAO.'" title="Reativar Situação" alt="Reativar Situação" class="infraImg" id="imgReativar_' . $strId . '" onclick="acaoReativar(this,\'' . $strId . '\',1);"/></a>&nbsp;';
            }


            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(this,\'' . $strId . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg?'.Icone::VERSAO.'" title="Excluir Situação" alt="Excluir Situação" class="infraImg" /></a>&nbsp;';
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_situacao_parametrizar_js.php");
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
require_once("md_lit_situacao_parametrizar_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
