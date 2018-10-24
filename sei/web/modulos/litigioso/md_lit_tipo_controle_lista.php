<?
    /**
     * ANATEL
     *
     * 19/01/2016 - criado por marcelo.bezerra - CAST
     *
     */

    try {
        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();

        SessaoSEI::getInstance()->validarLink();

        PaginaSEI::getInstance()->prepararSelecao('md_lit_tipo_controle_selecionar');

        SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

        //checar se usuario é administrador ou nao para aplicar as devidas restrições de permissões
        $permissaoRN        = new MdLitPermissaoLitigiosoRN();
        $isAdministradorSEI = $permissaoRN->isAdm();
        //se quiser simular um usuario nao administrador nesta tela,
        //precisa descomentar a linha abaixo
        //$isAdministradorSEI = false;

        switch ($_GET['acao']) {
            case 'md_lit_tipo_controle_excluir':
                try {
                    $arrStrIds                      = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjTipoControleLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                        $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($arrStrIds[$i]);
                        $arrObjTipoControleLitigiosoDTO[] = $objTipoControleLitigiosoDTO;
                    }
                    $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
                    $objTipoControleLitigiosoRN->excluir($arrObjTipoControleLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;

            case 'md_lit_tipo_controle_desativar':
                try {
                    $arrStrIds                      = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjTipoControleLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                        $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($arrStrIds[$i]);
                        $arrObjTipoControleLitigiosoDTO[] = $objTipoControleLitigiosoDTO;
                    }
                    $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
                    $objTipoControleLitigiosoRN->desativar($arrObjTipoControleLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;

            case 'md_lit_tipo_controle_reativar':

                $strTitulo = 'Reativar Tipos de Controles Litigiosos';

                if ($_GET['acao_confirmada'] == 'sim') {

                    try {
                        $arrStrIds                      = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                        $arrObjTipoControleLitigiosoDTO = array();
                        for ($i = 0; $i < count($arrStrIds); $i++) {
                            $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                            $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($arrStrIds[$i]);
                            $arrObjTipoControleLitigiosoDTO[] = $objTipoControleLitigiosoDTO;
                        }
                        $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
                        $objTipoControleLitigiosoRN->reativar($arrObjTipoControleLitigiosoDTO);
                        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                    die;
                }
                break;

            case 'md_lit_tipo_controle_selecionar':
                $bolAcaoCadastrar = false;
                $strTitulo        = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Controle Litigioso', 'Selecionar Tipos de Controles Litigiosos');

                //Se cadastrou alguem
                if ($_GET['acao_origem'] == 'md_lit_tipo_controle_cadastrar') {
                    if (isset($_GET['id_tipo_processo_litigioso'])) {
                        PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tipo_processo_litigioso']);
                    }
                }
                break;

            case 'md_lit_tipo_controle_listar':
                $strTitulo = 'Tipos de Controles Litigiosos';
                break;

            default:
                throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        }

        $bolAcaoReativarTopo  = false;
        $bolAcaoExcluirTopo   = false;
        $bolAcaoDesativarTopo = false;

        //BOTOES TOPO DA PAGINA
        if ($_GET['acao'] == 'md_lit_tipo_controle_selecionar') {

            //DENTRO DO POP UP
            $bolAcaoReativarTopo  = false;
            $bolAcaoExcluirTopo   = false;
            $bolAcaoDesativarTopo = false;

        }

        $arrComandos = array();
        if ($_GET['acao'] == 'md_lit_tipo_controle_selecionar') {
            $arrComandos[] = '<button type="button" onclick="pesquisar()" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
        }

        if ($_GET['acao'] != 'md_lit_tipo_controle_selecionar') {

            $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_cadastrar');

            $arrComandos[] = '<button type="button" onclick="pesquisar()" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

            if ($bolAcaoCadastrar && $isAdministradorSEI) {
                $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
            }
        }

        $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
        $objTipoControleLitigiosoDTO->retNumIdTipoControleLitigioso();
        $objTipoControleLitigiosoDTO->retStrSigla();
        $objTipoControleLitigiosoDTO->retStrDescricao();
        $objTipoControleLitigiosoDTO->retStrSinAtivo();
        $objTipoControleLitigiosoDTO->retStrSinParamModalComplInteressado();

        if ($_GET['acao'] == 'md_lit_tipo_controle_selecionar') {
            $objTipoControleLitigiosoDTO->setStrSinAtivo('S');
        }

        PaginaSEI::getInstance()->prepararOrdenacao($objTipoControleLitigiosoDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
        PaginaSEI::getInstance()->prepararPaginacao($objTipoControleLitigiosoDTO);

        $objTipoControleLitigiosoRN = new MdLitTipoControleRN();

        if (isset($_POST['txtSigla']) && $_POST['txtSigla'] != "") {
            $objTipoControleLitigiosoDTO->setStrSigla('%' . $_POST['txtSigla'] . '%', InfraDTO::$OPER_LIKE);
        }

        if (isset($_POST['txtDescricao']) && $_POST['txtDescricao'] != "") {
            $objTipoControleLitigiosoDTO->setStrDescricao('%' . $_POST['txtDescricao'] . '%', InfraDTO::$OPER_LIKE);
        }

        $arrObjTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->listar($objTipoControleLitigiosoDTO);

        PaginaSEI::getInstance()->processarPaginacao($objTipoControleLitigiosoDTO);
        $numRegistros = count($arrObjTipoControleLitigiosoDTO);

        if ($numRegistros > 0) {

            $bolCheck = false;

            if ($_GET['acao'] == 'md_lit_tipo_controle_selecionar') {
                $bolAcaoImprimir      = false;
                $bolAcaoReativar      = true;
                $bolAcaoConsultar     = true;
                $bolAcaoAlterar       = true;
                $bolAcaoDesativar     = true;
                $bolAcaoExcluir       = true;
                $bolAcaoReativarTopo  = false;
                $bolAcaoExcluirTopo   = false;
                $bolAcaoDesativarTopo = false;
                $bolCheck             = true;

            } else if ($_GET['acao'] == 'md_lit_tipo_controle_reativar') {

                $bolAcaoReativar  = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_reativar');
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_consultar');
                $bolAcaoAlterar   = false;
                $bolAcaoImprimir  = true;
                $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_excluir');
                $bolAcaoDesativar = false;
            } else {

                $bolAcaoReativar  = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_reativar');
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_consultar');
                $bolAcaoAlterar   = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_alterar');
                $bolAcaoImprimir  = true;
                $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_excluir');
                $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_tipo_controle_desativar');
            }


            if ($_GET['acao'] == 'md_lit_tipo_controle_selecionar') {

                if ($bolAcaoDesativarTopo) {
                    $bolCheck         = true;
                    $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_desativar&acao_origem=' . $_GET['acao']);

                }

                if ($bolAcaoReativarTopo) {
                    $bolCheck        = true;
                    $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
                }

                if ($bolAcaoExcluirTopo) {
                    $bolCheck       = true;
                    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_excluir&acao_origem=' . $_GET['acao']);
                }

            } else {

                if ($bolAcaoDesativar && $isAdministradorSEI) {
                    $bolCheck         = true;
                    $arrComandos[]    = '<button type="button" accesskey="T" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
                    $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_desativar&acao_origem=' . $_GET['acao']);
                }

                if ($bolAcaoReativar && $isAdministradorSEI) {
                    $bolCheck        = true;
                    $arrComandos[]   = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
                    $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
                }

                if ($bolAcaoExcluir && $isAdministradorSEI) {
                    $bolCheck       = true;
                    $arrComandos[]  = '<button type="button" accesskey="X" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">E<span class="infraTeclaAtalho">x</span>cluir</button>';
                    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_excluir&acao_origem=' . $_GET['acao']);
                }

            }

            $strResultado = '';

            if ($_GET['acao'] != 'md_lit_tipo_controle_reativar') {
                $strSumarioTabela = 'Tabela de Tipos de Controles Litigiosos.';
                $strCaptionTabela = 'Tipos de Controles Litigiosos';
            } else {
                $strSumarioTabela = 'Tabela de Tipos de Controles Litigiosos Inativos.';
                $strCaptionTabela = 'Tipos de Controles Litigiosos Inativos';
            }

            $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
            $strResultado .= '<tr>';
            if ($bolCheck) {
                $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            }
            $strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objTipoControleLitigiosoDTO, 'Sigla', 'Sigla', $arrObjTipoControleLitigiosoDTO) . '</th>' . "\n";
            $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objTipoControleLitigiosoDTO, 'Descrição', 'Descricao', $arrObjTipoControleLitigiosoDTO) . '</th>' . "\n";

            if ($_GET['acao'] == 'md_lit_tipo_controle_selecionar') {
                $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
            } else {
                $strResultado .= '<th class="infraTh" width="25%">Ações</th>' . "\n";
            }

            $strResultado .= '</tr>' . "\n";
            $strCssTr = '';

            for ($i = 0; $i < $numRegistros; $i++) {

                if ($arrObjTipoControleLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                } else {
                    $strCssTr = '<tr class="trVermelha">';
                }

                $strResultado .= $strCssTr;

                if ($bolCheck) {
                    $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso(), PaginaSEI::tratarHTML($arrObjTipoControleLitigiosoDTO[$i]->getStrSigla())) . '</td>';
                }
                $strResultado .= '<td style="word-break:break-all">' . PaginaSEI::tratarHTML($arrObjTipoControleLitigiosoDTO[$i]->getStrSigla()) . '</td>';
                $strResultado .= '<td style="word-break:break-all">' . PaginaSEI::tratarHTML($arrObjTipoControleLitigiosoDTO[$i]->getStrDescricao()) . '</td>';
                $strResultado .= '<td align="center">';

                $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());

                $siglaTipoControle = PaginaSEI::tratarHTML($arrObjTipoControleLitigiosoDTO[$i]->getStrSigla());
                //link para acesso ao associar
                if ($_GET['acao'] != 'md_lit_tipo_controle_selecionar') {

                    //link para parametrizar dados complementares do interessado
                    if(empty($arrObjTipoControleLitigiosoDTO[$i]->getStrSinParamModalComplInteressado())) {
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_parametrizar_interessado_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensLocal() . '/sei_servicos_vazio.gif" title="Parametrizar Dados Complementares - '.$siglaTipoControle.'" alt="Parametrizar Dados Complementares - '.$siglaTipoControle.'" class="infraImg" /></a>&nbsp;';
                    }else{
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_parametrizar_interessado_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensLocal() . '/sei_servicos.gif" title="Parametrizar Dados Complementares - '.$siglaTipoControle.'" alt="Parametrizar Dados Complementares - '.$siglaTipoControle.'" class="infraImg" /></a>&nbsp;';
                    }

                    $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_tipo_decisao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_litigioso=' . $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/litigioso/imagens/associar.png" title="Associar Tipos de Decisão - '.$siglaTipoControle.'" alt="Associar Tipos de Decisão - '.$siglaTipoControle.'" class="infraImg" /></a>&nbsp;';

                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_associar_dispositivo_normativo_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_litigioso=' . $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/litigioso/imagens/livro.png" title="Dispositivos Normativos Associados - '.$siglaTipoControle.'" alt="Dispositivos Normativos Associados - '.$siglaTipoControle.'" class="infraImg" /></a>&nbsp;';

                    //link para a tela de parametrizar situacoes
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_parametrizar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensLocal() . '/sei_valores.gif" title="Parametrizar Situações - '.$siglaTipoControle.'" alt="Parametrizar Situações - '.$siglaTipoControle.'" class="infraImg" /></a>&nbsp;';

                }


                if ($bolAcaoConsultar) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Tipo de Controle Litigioso" alt="Consultar Tipo de Controle Litigioso" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoAlterar && $isAdministradorSEI) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Tipo de Controle Litigioso" alt="Alterar Tipo de Controle Litigioso" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                    $strId        = $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso();
                    $strDescricao = PaginaSEI::tratarHTML($arrObjTipoControleLitigiosoDTO[$i]->getStrSigla(), true);
                }

                if ($bolAcaoDesativar && $arrObjTipoControleLitigiosoDTO[$i]->getStrSinAtivo() == 'S' && $isAdministradorSEI) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Tipo de Controle Litigioso" alt="Desativar Tipo de Controle Litigioso" class="infraImg" /></a>&nbsp;';
                } else if ($isAdministradorSEI && $bolAcaoReativar) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Tipo de Controle Litigioso" alt="Reativar Tipo de Controle Litigioso" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoExcluir && $isAdministradorSEI) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Tipo de Controle Litigioso" alt="Excluir Tipo de Controle Litigioso" class="infraImg" /></a>&nbsp;';
                }

                $strResultado .= '</td></tr>' . "\n";
            }
            $strResultado .= '</table>';
        }
        if ($_GET['acao'] == 'md_lit_tipo_controle_selecionar') {
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        } else {
            //$arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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

    #lblSigla {position:absolute;left:0px;top:0%;width:230px;}
    #txtSigla {position:absolute;left:0px;top:40%;width:230px;}

    #lblDescricao {position:absolute;left:250px;top:0%;width:230px;}
    #txtDescricao {position:absolute;left:250px;top:40%;width:230px;}

<?
    PaginaSEI::getInstance()->fecharStyle();
    PaginaSEI::getInstance()->montarJavaScript();
    PaginaSEI::getInstance()->abrirJavaScript();
?>

    function inicializar(){
    if ('<?= $_GET['acao'] ?>'=='md_lit_tipo_controle_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
    }else{
    document.getElementById('btnFechar').focus();
    }
    infraEfeitoTabelas();
    }

<? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id,desc){
    var desc = $("<pre>").html(desc).text();
    if (confirm("O controle de processo litigioso correspondente será desativado e os usuários não conseguirão cadastrar processos nesse tipo de controle e nenhum outro registro pertinente. \n\n Confirma a desativação do Tipo de Controle Litigioso \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoControleLitigiosoLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmTipoControleLitigiosoLista').submit();
    }
    }

    function acaoDesativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Controle Litigioso selecionado.');
    return;
    }
    if (confirm("O controle de processo litigioso correspondente será desativado e os usuários não conseguirão cadastrar processos nesse tipo de controle e nenhum outro registro pertinente. \n\n Confirma a desativação dos Tipos de Controles Litigiosos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoControleLitigiosoLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmTipoControleLitigiosoLista').submit();
    }
    }
<? } ?>

    function acaoReativar(id,desc){
    if (confirm("Confirma a reativação do Tipo de Controle Litigioso \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoControleLitigiosoLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmTipoControleLitigiosoLista').submit();
    }
    }

    function acaoReativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Controle Litigioso selecionado.');
    return;
    }
    if (confirm("Confirma a reativação dos Tipos de Controles Litigiosos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoControleLitigiosoLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmTipoControleLitigiosoLista').submit();
    }
    }

<? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id,desc){
    var desc = $("<pre>").html(desc).text();
    if (confirm("Esta operação pode ser demorada. \n\n Confirma a exclusão do Tipo de Controle Litigioso \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoControleLitigiosoLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmTipoControleLitigiosoLista').submit();
    }
    }

    function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Controle Litigioso selecionado.');
    return;
    }
    if (confirm("Esta operação pode ser demorada. \n\n Confirma a exclusão dos Tipos de Controles Litigiosos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoControleLitigiosoLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmTipoControleLitigiosoLista').submit();
    }
    }


<? } ?>

    function pesquisar(){
    document.getElementById("frmTipoControleLitigiosoLista").submit();
    }

<?
    PaginaSEI::getInstance()->fecharJavaScript();
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTipoControleLitigiosoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <?
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>

        <div style="height:4.5em; margin-top: 11px;" class="infraAreaDados" id="divInfraAreaDados">

            <label id="lblSigla" for="txtSigla" class="infraLabelOpcional">Sigla:</label>
            <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?= $_POST['txtSigla'] ?>"
                   maxlength="50" tabindex="502">

            <label id="lblDescricao" for="txtDescricao" class="infraLabelOpcional">Descrição:</label>
            <input type="text" id="txtDescricao" name="txtDescricao" class="infraText"
                   value="<?= $_POST['txtDescricao'] ?>" maxlength="250" tabindex="503">

        </div>
        <input type="submit" style="visibility: hidden"/>

        <?
            PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>