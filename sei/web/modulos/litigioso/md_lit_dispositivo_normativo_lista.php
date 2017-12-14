<?
    /**
     * ANATEL
     *
     * 16/02/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */
    try {
        require_once dirname(__FILE__) . '/../../SEI.php';

        session_start();


        SessaoSEI::getInstance()->validarLink();

        PaginaSEI::getInstance()->prepararSelecao('md_lit_dispositivo_normativo_selecionar');

        SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

        $filtro = '';
        switch ($_GET['acao']) {

            case 'md_lit_dispositivo_normativo_excluir':

                try {

                    $arrStrIds                              = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjDispositivoNormativoLitigiosoDTO = array();
                    $objDispositivoNormativoLitigiosoRN     = new MdLitDispositivoNormativoRN();

                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objDispositivoNormativoLitigiosoDTO = new MdLitDispositivoNormativoDTO();
                        $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($arrStrIds[$i]);
                        $objDispositivoNormativoLitigiosoRN->removerRelacionamentos($objDispositivoNormativoLitigiosoDTO);
                        array_push($arrObjDispositivoNormativoLitigiosoDTO, $objDispositivoNormativoLitigiosoDTO);
                    }

                    $objDispositivoNormativoLitigiosoRN->excluir($arrObjDispositivoNormativoLitigiosoDTO);

                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;

            case 'md_lit_dispositivo_normativo_desativar':
                try {
                    $arrStrIds                              = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjDispositivoNormativoLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objDispositivoNormativoLitigiosoDTO = new MdLitDispositivoNormativoDTO();
                        $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($arrStrIds[$i]);
                        $arrObjDispositivoNormativoLitigiosoDTO[] = $objDispositivoNormativoLitigiosoDTO;
                    }
                    $objDispositivoNormativoLitigiosoRN = new MdLitDispositivoNormativoRN();
                    $objDispositivoNormativoLitigiosoRN->desativar($arrObjDispositivoNormativoLitigiosoDTO);
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;

            case 'md_lit_dispositivo_normativo_reativar':

                $strTitulo = 'Reativar Dispositivos Normativos';

                if ($_GET['acao_confirmada'] == 'sim') {

                    try {
                        $arrStrIds                              = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                        $arrObjDispositivoNormativoLitigiosoDTO = array();
                        for ($i = 0; $i < count($arrStrIds); $i++) {
                            $objDispositivoNormativoLitigiosoDTO = new MdLitDispositivoNormativoDTO();
                            $objDispositivoNormativoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($arrStrIds[$i]);
                            $arrObjDispositivoNormativoLitigiosoDTO[] = $objDispositivoNormativoLitigiosoDTO;
                        }
                        $objDispositivoNormativoLitigiosoRN = new MdLitDispositivoNormativoRN();
                        $objDispositivoNormativoLitigiosoRN->reativar($arrObjDispositivoNormativoLitigiosoDTO);
                        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                    die;
                }
                break;

            case 'md_lit_dispositivo_normativo_selecionar':

                $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Dispositivo Normativo', 'Selecionar Dispositivos Normativos');

                $filtro = $_GET['filtro'];
                //Se cadastrou alguem
                if ($_GET['acao_origem'] == 'md_lit_dispositivo_normativo_cadastrar') {
                    if (isset($_GET['id_dispositivo_normativo_litigioso'])) {
                        PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_dispositivo_normativo_litigioso']);
                    }
                }
                break;

            case 'md_lit_dispositivo_normativo_listar':

                $strLinkConsultar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_listar&acao_origem=' . $_GET['acao_origem']);
                $strTitulo        = 'Dispositivos Normativos ';
                break;

            default:
                throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        }

        $bolAcaoReativarTopo  = false;
        $bolAcaoExcluirTopo   = false;
        $bolAcaoDesativarTopo = false;
        $bolAcaoCadastrar     = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_cadastrar');

        //BOTOES TOPO DA PAGINA
        if ($_GET['acao'] == 'md_lit_dispositivo_normativo_selecionar') {
            //DENTRO DO POP UP
            $bolAcaoReativarTopo  = false;
            $bolAcaoExcluirTopo   = false;
            $bolAcaoDesativarTopo = false;

            if ($filtro != '') {
                $bolAcaoCadastrar = false;
            }
        }

        $arrComandos = array();

        $arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" value="btnPesquisar" onclick="submitFormPesquisa()" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

        if ($_GET['acao'] != 'md_lit_dispositivo_normativo_selecionar') {
            $arrComandos[] = '<button type="button" accesskey="o" id="btnCondutas" value="btnCondutas" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton">C<span class="infraTeclaAtalho">o</span>ndutas</button>';
        }

        if ($_GET['acao'] == 'md_lit_dispositivo_normativo_selecionar') {
            $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
        }

        if ($bolAcaoCadastrar) {
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_cadastrar&acao_origem=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
        }


        $txtConduta     = $_POST ['txtConduta'];
        $txtNorma       = $_POST ['txtNorma'];
        $txtDispositivo = $_POST['txtDispositivo'];
        $selICCondutas  = $_POST ['selICCondutas'];
        $filtro         = $_GET['filtro'];
        $Ativo          = "";

        if ($_GET['acao'] == 'md_lit_dispositivo_normativo_selecionar') {
            $Ativo = "S";
        }

        $objDispositivoNormativoLitigiosoDTO = MdLitDispositivoNormativoINT::listarDispositivos($txtConduta, $txtNorma, $txtDispositivo, $selICCondutas, $filtro, $Ativo);

        PaginaSEI::getInstance()->prepararOrdenacao($objDispositivoNormativoLitigiosoDTO, 'Norma', InfraDTO::$TIPO_ORDENACAO_ASC);
        PaginaSEI::getInstance()->prepararPaginacao($objDispositivoNormativoLitigiosoDTO, 200);

        $objDispositivoNormativoLitigiosoRN     = new MdLitDispositivoNormativoRN();
        $arrObjDispositivoNormativoLitigiosoDTO = $objDispositivoNormativoLitigiosoRN->listar($objDispositivoNormativoLitigiosoDTO);

        PaginaSEI::getInstance()->processarPaginacao($objDispositivoNormativoLitigiosoDTO);


        $numRegistros = count($arrObjDispositivoNormativoLitigiosoDTO);

        if ($numRegistros > 0) {

            $bolCheck = false;

            if ($_GET['acao'] == 'md_lit_dispositivo_normativo_reativar') {

                $bolAcaoReativar  = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_reativar');
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_consultar');
                $bolAcaoAlterar   = false;
                $bolAcaoImprimir  = true;
                $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_excluir');
                $bolAcaoDesativar = false;

            } else {


                if ($_GET['acao'] == 'md_lit_dispositivo_normativo_selecionar') {
                    //DENTRO DO POP UP
                    $bolCheck         = true;
                    $bolAcaoReativar  = false;
                    $bolAcaoConsultar = $filtro == '' ? SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_consultar') : false;
                    $bolAcaoAlterar   = $filtro == '' ? SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_alterar') : false;
                    $bolAcaoImprimir  = false;
                    $bolAcaoExcluir   = $filtro == '' ? SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_excluir') : false;
                    $bolAcaoDesativar = $filtro == '' ? SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_desativar') : false;

                } else {
                    //FORA DO POP UP
                    $bolAcaoReativar  = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_reativar');
                    $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_consultar');
                    $bolAcaoAlterar   = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_alterar');
                    $bolAcaoImprimir  = true;
                    $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_excluir');
                    $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_dispositivo_normativo_desativar');
                }


            }

            if ($_GET['acao'] == 'md_lit_dispositivo_normativo_selecionar') {

                if ($bolAcaoDesativarTopo) {
                    $bolCheck         = true;
                    $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_desativar&acao_origem=' . $_GET['acao']);
                }

                if ($bolAcaoReativarTopo) {
                    $bolCheck        = true;
                    $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
                }

                if ($bolAcaoExcluirTopo) {
                    $bolCheck       = true;
                    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_excluir&acao_origem=' . $_GET['acao']);
                }

            } else {

                if ($bolAcaoDesativar) {
                    $bolCheck         = true;
                    $arrComandos[]    = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
                    $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_desativar&acao_origem=' . $_GET['acao']);
                }

                if ($bolAcaoReativar) {
                    $bolCheck        = true;
                    $arrComandos[]   = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
                    $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
                }

                if ($bolAcaoExcluir) {
                    $bolCheck       = true;
                    $arrComandos[]  = '<button type="button" accesskey="X" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">E<span class="infraTeclaAtalho">x</span>cluir</button>';
                    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_excluir&acao_origem=' . $_GET['acao']);
                }

            }

            $strResultado = '';

            if ($_GET['acao'] != 'md_lit_dispositivo_normativo_reativar') {
                $strSumarioTabela = 'Tabela de Dispositivo Normativos.';
                $strCaptionTabela = 'Dispositivos Normativos';
            } else {
                $strSumarioTabela = 'Tabela de Dispositivo Normativos Inativos.';
                $strCaptionTabela = 'Dispositivos Normativos Inativos';
            }

            $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
            $strResultado .= '<tr>';

            if ($bolCheck) {
                $strResultado .= '<th class="infraTh" valign="middle" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            }

            if ($_GET['acao'] == 'md_lit_dispositivo_normativo_selecionar') {
                $strResultado .= '<th class="infraTh" valign="middle" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objDispositivoNormativoLitigiosoDTO, 'Norma', 'Norma', $arrObjDispositivoNormativoLitigiosoDTO) . '</th>' . "\n";
            } else {
                $strResultado .= '<th class="infraTh" valign="middle" width="20%">' . PaginaSEI::getInstance()->getThOrdenacao($objDispositivoNormativoLitigiosoDTO, 'Norma', 'Norma', $arrObjDispositivoNormativoLitigiosoDTO) . '</th>' . "\n";
            }

            $strResultado .= '<th class="infraTh" valign="middle">' . PaginaSEI::getInstance()->getThOrdenacao($objDispositivoNormativoLitigiosoDTO, 'Dispositivo', 'Dispositivo', $arrObjDispositivoNormativoLitigiosoDTO) . '</th>' . "\n";
            $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objDispositivoNormativoLitigiosoDTO, 'Condutas', 'Dispositivo', $arrObjDispositivoNormativoLitigiosoDTO) . '</th>' . "\n";
            $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objDispositivoNormativoLitigiosoDTO, 'Tipos de Controle Litigioso', 'Dispositivo', $arrObjDispositivoNormativoLitigiosoDTO) . '</th>' . "\n";

            if ($_GET['acao'] == 'md_lit_dispositivo_normativo_selecionar') {
                $strResultado .= '<th class="infraTh" width="25%">Ações</th>' . "\n";
            } else {
                $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
            }

            $strResultado .= '</tr>' . "\n";
            $strCssTr = '';

            for ($i = 0; $i < $numRegistros; $i++) {

                //Conduta
                $strCondutas                          = '';
                $objRelDispositivoNormativoCondutaRN  = new MdLitRelDispositivoNormativoCondutaRN();
                $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
                $objRelDispositivoNormativoCondutaDTO->retTodos();
                $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($arrObjDispositivoNormativoLitigiosoDTO[$i]->getNumIdDispositivoNormativoLitigioso());
                $arrObjRelDispositivoNormativoCondutaDTO = $objRelDispositivoNormativoCondutaRN->listar($objRelDispositivoNormativoCondutaDTO);

                foreach ($arrObjRelDispositivoNormativoCondutaDTO as $objDTO) {
                    $objCondutaLitigiosoRN  = new MdLitCondutaRN();
                    $objCondutaLitigiosoDTO = new MdLitCondutaDTO();
                    $objCondutaLitigiosoDTO->retTodos();
                    $objCondutaLitigiosoDTO->setNumIdCondutaLitigioso($objDTO->getNumIdConduta());

                    $objCondutaLitigiosoDTO = $objCondutaLitigiosoRN->consultar($objCondutaLitigiosoDTO);

                    $nomeConduta = '';
                    $nomeConduta = $objCondutaLitigiosoDTO != null ? $objCondutaLitigiosoDTO->getStrNome() : '';
                    $nomeConduta = $nomeConduta != '' ? PaginaSEI::tratarHTML($nomeConduta) . ' <br/> ' : '';

                    //$strCondutas .= PaginaSEI::tratarHTML( $nomeConduta );
                    $strCondutas .= $nomeConduta;
                }


                //Tipo de Controle
                $strTiposControle                          = '';
                $objRelDispositivoNormativoTipoControleRN  = new MdLitRelDispositivoNormativoTipoControleRN();
                $objRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                $objRelDispositivoNormativoTipoControleDTO->retTodos();
                $objRelDispositivoNormativoTipoControleDTO->setNumIdDispositivoNormativo($arrObjDispositivoNormativoLitigiosoDTO[$i]->getNumIdDispositivoNormativoLitigioso());
                $arrObjRelDispositivoNormativoTipoControleDTO = $objRelDispositivoNormativoTipoControleRN->listar($objRelDispositivoNormativoTipoControleDTO);


                foreach ($arrObjRelDispositivoNormativoTipoControleDTO as $objDTO) {
                    $objTipoControleLitigiosoRN  = new MdLitTipoControleRN();
                    $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                    $objTipoControleLitigiosoDTO->retTodos();
                    $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($objDTO->getNumIdTipoControle());

                    $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);

                    $siglaTipoControle = '';
                    $siglaTipoControle = $objTipoControleLitigiosoDTO != null ? $objTipoControleLitigiosoDTO->getStrSigla() : '';
                    $siglaTipoControle = $siglaTipoControle != '' ? PaginaSEI::tratarHTML($siglaTipoControle) . ' <br/> ' : '';

                    $strTiposControle .= $siglaTipoControle;
                }

                if ($arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                } else {
                    $strCssTr = '<tr class="trVermelha">';
                }

                $strResultado .= $strCssTr;

                if ($bolCheck) {
                    $strResultado .= '<td valign="middle">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjDispositivoNormativoLitigiosoDTO[$i]->getNumIdDispositivoNormativoLitigioso(), $arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrNorma() . ' - ' . $arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrDispositivo()) . '</td>';
                }

                if ($arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrUrl() == null
                    || $arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrUrl() == ""
                ) {
                    $strResultado .= '<td valign="middle" style="word-break:break-all">' . PaginaSEI::tratarHTML($arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrNorma()) . '</td>';
                } else {
                    $strResultado .= '<td valign="middle" style="word-break:break-all"><a title="Acesse a Norma" target="_blank" style="font-size: inherit !important;" href="' . $arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrUrl() . '">'
                        . PaginaSEI::tratarHTML($arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrNorma()) . '</a></td>';
                }

                $strResultado .= '<td valign="middle" title="' . PaginaSEI::tratarHTML($arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrDescricao()) . '" style="word-break:break-all">'
                    . PaginaSEI::tratarHTML($arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrDispositivo()) . '</td>';

                $strResultado .= '<td style="word-break:break-all">' . $strCondutas . '</td>';
                $strResultado .= '<td>' . $strTiposControle . '</td>';
                $strResultado .= '<td align="center">';

                $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjDispositivoNormativoLitigiosoDTO[$i]->getNumIdDispositivoNormativoLitigioso());

                if ($bolAcaoConsultar) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_dispositivo_normativo_litigioso=' . $arrObjDispositivoNormativoLitigiosoDTO[$i]->getNumIdDispositivoNormativoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Dispositivo Normativo" alt="Consultar Dispositivo Normativo" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoAlterar) {

                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_dispositivo_normativo_litigioso=' . $arrObjDispositivoNormativoLitigiosoDTO[$i]->getNumIdDispositivoNormativoLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Dispositivo Normativo" alt="Alterar Dispositivo Normativo" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                    $strId        = $arrObjDispositivoNormativoLitigiosoDTO[$i]->getNumIdDispositivoNormativoLitigioso();
                    $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrNorma(), true));
                }

                if ($bolAcaoDesativar && $arrObjDispositivoNormativoLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Dispositivo Normativo" alt="Desativar Dispositivo Normativo" class="infraImg" /></a>&nbsp;';
                } else if ($bolAcaoReativar) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Dispositivo Normativo" alt="Reativar Dispositivo Normativo" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoExcluir) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Dispositivo Normativo" alt="Excluir Dispositivo Normativo" class="infraImg" /></a>&nbsp;';
                }

                $strResultado .= '</td></tr>' . "\n";
            }
            $strResultado .= '</table>';
        }

        if ($bolAcaoImprimir) {
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Fechar" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        if ($_GET['acao'] == 'md_lit_dispositivo_normativo_selecionar') {
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        } else {
            //$arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        }

        $strItensSelTipoProcesso = MdLitTipoControleINT::montarSelectSigla(null, null, $_POST['selTipoControleLitigioso']);

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
<?
    PaginaSEI::getInstance()->fecharStyle();
    PaginaSEI::getInstance()->montarJavaScript();
    PaginaSEI::getInstance()->abrirJavaScript();
?>

    function inicializar(){
    if ('<?= $_GET['acao'] ?>'=='md_lit_dispositivo_normativo_selecionar'){
    // 2- Indicar por Conduta
    if ('<?= $_GET['tipo_selecao'] ?>'=='2' && window.opener.document.getElementById('selICCondutas')!=null){
    if (window.opener.document.getElementById('selICCondutas').value!='' && document.getElementById('selICCondutas').value==''){
    document.getElementById('selICCondutas').value=window.opener.document.getElementById('selICCondutas').value;
    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }
    }
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
    }else{
    document.getElementById('btnFechar').focus();
    }
    infraEfeitoTabelas();
    }

    function submitFormPesquisa(){
    //alert('Teste');
    document.getElementById('frmDispositivoNormativoLitigiosoLista').action='<?= $strLinkConsultar ?>';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }

<? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id,desc){

    <? $strAcao = $_GET['acao']; ?>
    var acao = '<?= $strAcao ?>';

    if ( acao =='md_lit_dispositivo_normativo_selecionar'){

    /*
    Na linha de cada registro, na ação de Desativar e Excluir , aplicar regra adicional que checa se o item foi previamente selecionado. 
    Se tiver sido, exibir a seguinte:    "Não é permitido desativar ou excluir item já selecionado. Caso deseje efetivar a
    operação, antes retorne à tela anterior para remover a seleção."
    */

    var arrElem = document.getElementsByClassName("infraCheckbox");

    for (var i =0; i < arrElem.length; i++){

    var nomeId = 'chkInfraItem' + i;
    var item = document.getElementById( nomeId );

    //se o valor bater e o campo estiver marcado, aplicar a regra
    if ( item.value == id ) {

    var valorMarcado = item.checked;
    var valorDisabled = item.disabled;

    if( ( valorDisabled || valorDisabled == 'disabled' ) && ( valorMarcado || valorMarcado == 'checked') ){
    alert("Não é permitido desativar ou excluir item já selecionado. Caso deseje efetivar a operação, antes retorne à tela anterior para remover a seleção.");
    return;
    }
    }

    }

    }

    if (confirm("Confirma desativação do Dispositivo Normativo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmDispositivoNormativoLitigiosoLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }
    }

    function acaoDesativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Dispositivo Normativo selecionado.');
    return;
    }
    if (confirm("Confirma a desativação dos Dispositivos Normativos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }
    }
<? } ?>

    function acaoReativar(id,desc){
    if (confirm("Confirma reativação do Dispositivo Normativo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmDispositivoNormativoLitigiosoLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }
    }

    function acaoReativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Dispositivo Normativo selecionado.');
    return;
    }
    if (confirm("Confirma a reativação dos Dispositivos Normativos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }
    }

<? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id,desc){

    <? $strAcao = $_GET['acao']; ?>
    var acao = '<?= $strAcao ?>';

    if ( acao =='md_lit_dispositivo_normativo_selecionar'){

    /*
    Na linha de cada registro, na ação de Desativar e Excluir , aplicar regra adicional que checa se o item foi previamente selecionado. 
    Se tiver sido, exibir a seguinte:    "Não é permitido desativar ou excluir item já selecionado. Caso deseje efetivar a
    operação, antes retorne à tela anterior para remover a seleção."
    */

    var arrElem = document.getElementsByClassName("infraCheckbox");

    for (var i =0; i < arrElem.length; i++){

    var nomeId = 'chkInfraItem' + i;
    var item = document.getElementById( nomeId );

    //se o valor bater e o campo estiver marcado, aplicar a regra
    if ( item.value == id ) {

    var valorMarcado = item.checked;
    var valorDisabled = item.disabled;

    if( ( valorDisabled || valorDisabled == 'disabled' ) && ( valorMarcado || valorMarcado == 'checked') ){
    alert("Não é permitido desativar ou excluir item já selecionado. Caso deseje efetivar a operação, antes retorne à tela anterior para remover a seleção.");
    return;
    }
    }

    }

    }

    if (confirm("Confirma exclusão do Dispositivo Normativo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmDispositivoNormativoLitigiosoLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }
    }

    function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Dispositivo Normativo selecionado.');
    return;
    }
    if (confirm("Confirma a exclusão dos Dispositivos Normativos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmDispositivoNormativoLitigiosoLista').submit();
    }
    }
<? } ?>


<?
    PaginaSEI::getInstance()->fecharJavaScript();
?>

    <style type="text/css">

        #lblNorma {
            position: absolute;
            left: 0%;
            top: 0%;
            width: 25%;
        }

        #txtNorma {
            position: absolute;
            left: 0%;
            top: 40%;
            width: 25%;
        }

        #lblDispositivo {
            position: absolute;
            left: 27%;
            top: 0%;
            width: 19%;
        }

        #txtDispositivo {
            position: absolute;
            left: 27%;
            top: 40%;
            width: 19%;
        }

        #lblConduta {
            position: absolute;
            left: 48%;
            top: 0%;
            width: 25%;
        }

        #txtConduta {
            position: absolute;
            left: 48%;
            top: 40%;
            width: 25%;
        }

        #lblTipoControleLitigioso {
            position: absolute;
            left: 74%;
            top: 0%;
            width: 25%;
        }

        #selTipoControleLitigioso {
            position: absolute;
            left: 74%;
            top: 40%;
            width: 25%;
        }

    </style>

<?php
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmDispositivoNormativoLitigiosoLista" method="post">
        <?
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>

        <div style="height:4.5em; margin-top: 11px;" class="infraAreaDados" id="divInfraAreaDados">

            <label id="lblNorma" for="txtNorma" class="infraLabelOpcional">Norma:</label>
            <input type="text" id="txtNorma" name="txtNorma" class="infraText" value="<?= $_POST['txtNorma'] ?>"
                   maxlength="15" tabindex="502">

            <label id="lblDispositivo" for="txtDispositivo"
                   class="infraLabelOpcional">Dispositivo:</label>
            <input type="text" id="txtDispositivo" name="txtDispositivo" class="infraText"
                   value="<?= $_POST['txtDispositivo'] ?>" maxlength="50" tabindex="503">

            <label id="lblConduta" for="txtConduta" class="infraLabelOpcional">Conduta:</label>
            <input type="text" id="txtConduta" name="txtConduta" class="infraText" value="<?= $_POST['txtConduta'] ?>"
                   maxlength="50" tabindex="504">


            <?php
                $showCombo = $_GET['acao'] !== 'md_lit_dispositivo_normativo_selecionar' && $filtro !== '' ? true : false;

                if ($showCombo) {
                    ?>
                    <label id="lblTipoControleLitigioso" for="selTipoControleLitigioso" name="lblTipoControleLitigioso"
                           class="infraLabelOpcional">Tipo de Controle Litigioso:</label>
                    <select id="selTipoControleLitigioso" name="selTipoControleLitigioso"
                            onchange="submitFormPesquisa()">
                        <option></option>
                        <?= $strItensSelTipoProcesso ?>
                    </select>
                <?php } ?>

            <input type="hidden" id="selICCondutas" name="selICCondutas" value="<?= $_POST['selICCondutas'] ?>">

            <input type="submit" style="visibility: hidden;"/>

        </div>

        <?
            PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>