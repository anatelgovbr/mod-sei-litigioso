bt<?
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

        PaginaSEI::getInstance()->prepararSelecao('md_lit_fase_selecionar');

        SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

        switch ($_GET['acao']) {
            case 'md_lit_fase_excluir':
                try {
                    $arrStrIds              = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjFaseLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objFaseLitigiosoDTO = new MdLitFaseDTO();
                        $objFaseLitigiosoDTO->setNumIdFaseLitigioso($arrStrIds[$i]);
                        $objFaseLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
                        $arrObjFaseLitigiosoDTO[] = $objFaseLitigiosoDTO;
                    }

                    $objFaseLitigiosoRN = new MdLitFaseRN();
                    $objFaseLitigiosoRN->excluir($arrObjFaseLitigiosoDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;

            case 'md_lit_fase_desativar':
                try {
                    $arrStrIds              = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjFaseLitigiosoDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objFaseLitigiosoDTO = new MdLitFaseDTO();
                        $objFaseLitigiosoDTO->setNumIdFaseLitigioso($arrStrIds[$i]);
                        $objFaseLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
                        $arrObjFaseLitigiosoDTO[] = $objFaseLitigiosoDTO;
                    }
                    $objFaseLitigiosoRN = new MdLitFaseRN();
                    $objFaseLitigiosoRN->desativar($arrObjFaseLitigiosoDTO);

                    $idTipoControle = $_GET['id_tipo_processo_litigioso'];

                    $arrDados = array($arrObjFaseLitigiosoDTO, $idTipoControle, true);
                    $objFaseLitigiosoRN->controlarStatusSituacao($arrDados);    

                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                die;

            case 'md_lit_fase_reativar':

                $strTitulo = 'Reativar Fases';

                if ($_GET['acao_confirmada'] == 'sim') {

                    try {
                        $arrStrIds              = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                        $arrObjFaseLitigiosoDTO = array();
                        for ($i = 0; $i < count($arrStrIds); $i++) {
                            $objFaseLitigiosoDTO = new MdLitFaseDTO();
                            $objFaseLitigiosoDTO->setNumIdFaseLitigioso($arrStrIds[$i]);
                            $objFaseLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
                            $arrObjFaseLitigiosoDTO[] = $objFaseLitigiosoDTO;
                        }
                        $objFaseLitigiosoRN = new MdLitFaseRN();
                        $objFaseLitigiosoRN->reativar($arrObjFaseLitigiosoDTO);

                        $idTipoControle = $_GET['id_tipo_processo_litigioso'];

                        $arrDados = array($arrObjFaseLitigiosoDTO, $idTipoControle, false);
                        $objFaseLitigiosoRN->controlarStatusSituacao($arrDados);

                        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                    } catch (Exception $e) {
                        PaginaSEI::getInstance()->processarExcecao($e);
                    }
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
                    die;
                }
                break;

            case 'md_lit_fase_selecionar':
                $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Fase', 'Selecionar Fases');

                //Se cadastrou alguem
                if ($_GET['acao_origem'] == 'md_lit_fase_cadastrar') {
                    if (isset($_GET['id_fase_litigioso'])) {
                        PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_fase_litigioso']);
                    }
                }
                break;

            case 'md_lit_fase_listar':
                $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
                $objTipoControleLitigiosoDTO->retTodos();
                $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
                $objTipoControleLitigiosoRN  = new MdLitTipoControleRN();
                $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);

                $sigla = $objTipoControleLitigiosoDTO->getStrSigla() ? $objTipoControleLitigiosoDTO->getStrSigla() : '';

                $strTitulo = 'Fases - Tipo de Controle Litigioso: ' . PaginaSEI::tratarHTML($sigla);
                break;

            default:
                throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        }

        $arrComandos = array();
        if ($_GET['acao'] == 'md_lit_fase_selecionar') {
            $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
        }

        $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_cadastrar');
        if ($bolAcaoCadastrar) {
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Nova" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_fase_cadastrar&acao_origem=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_retorno=' . $_GET['acao'])) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
        }

        $objFaseLitigiosoDTO = new MdLitFaseDTO();


        $objFaseLitigiosoDTO->retNumIdFaseLitigioso();
        $objFaseLitigiosoDTO->retStrNome();
        $objFaseLitigiosoDTO->retStrDescricao();
        $objFaseLitigiosoDTO->retStrSinAtivo();

        PaginaSEI::getInstance()->prepararOrdenacao($objFaseLitigiosoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
        PaginaSEI::getInstance()->prepararPaginacao($objFaseLitigiosoDTO);

        if (isset($_GET['id_tipo_processo_litigioso'])) {
            $objFaseLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
        }

        if (isset($_POST['id_tipo_processo_litigioso'])) {
            $objFaseLitigiosoDTO->setNumIdTipoControleLitigioso($_POST['id_tipo_processo_litigioso']);
        }

        $objFaseLitigiosoRN = new MdLitFaseRN();

        //echo "FASEEEEEEEEE <br/><br/><br/><br/>";
        //print_r($objFaseLitigiosoDTO); die();

        $arrObjFaseLitigiosoDTO = $objFaseLitigiosoRN->listar($objFaseLitigiosoDTO);

        PaginaSEI::getInstance()->processarPaginacao($objFaseLitigiosoDTO);
        $numRegistros = count($arrObjFaseLitigiosoDTO);

        if ($numRegistros > 0) {

            $bolCheck = false;

            if ($_GET['acao'] == 'md_lit_fase_selecionar') {
                $bolAcaoReativar  = false;
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_consultar');
                $bolAcaoAlterar   = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_alterar');
                $bolAcaoImprimir  = false;
                $bolAcaoExcluir   = false;
                $bolAcaoDesativar = false;
                $bolCheck         = true;
            } else if ($_GET['acao'] == 'md_lit_fase_reativar') {
                $bolAcaoReativar  = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_reativar');
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_consultar');
                $bolAcaoAlterar   = false;
                $bolAcaoImprimir  = true;
                $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_excluir');
                $bolAcaoDesativar = false;
            } else {
                $bolAcaoReativar  = false;
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_consultar');
                $bolAcaoAlterar   = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_alterar');
                $bolAcaoImprimir  = true;
                $bolAcaoExcluir   = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_excluir');
                $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_fase_desativar');
            }

            if ($bolAcaoDesativar) {
                $bolCheck         = true;
                $arrComandos[]    = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
                $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=md_lit_fase_desativar&acao_origem=' . $_GET['acao']);
            }

            $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=md_lit_fase_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');

            if ($bolAcaoExcluir) {
                $bolCheck       = true;
                $arrComandos[]  = '<button type="button" accesskey="x" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">E<span class="infraTeclaAtalho">x</span>cluir</button>';
                $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=md_lit_fase_excluir&acao_origem=' . $_GET['acao']);
            }

            $strResultado = '';

            if ($_GET['acao'] != 'fase_litigiosoreativar') {
                $strSumarioTabela = 'Tabela de Fases.';
                $strCaptionTabela = 'Fases';
            } else {
                $strSumarioTabela = 'Tabela de Fases Inativos.';
                $strCaptionTabela = 'Fases Inativos';
            }

            $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
            $strResultado .= '<tr>';
            if ($bolCheck) {
                $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            }
            $strResultado .= '<th class="infraTh" width="30%">' . PaginaSEI::getInstance()->getThOrdenacao($objFaseLitigiosoDTO, 'Nome', 'Nome', $arrObjFaseLitigiosoDTO) . '</th>' . "\n";
            $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objFaseLitigiosoDTO, 'Descrição', 'Descricao', $arrObjFaseLitigiosoDTO) . '</th>' . "\n";
            $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
            $strResultado .= '</tr>' . "\n";
            $strCssTr = '';
            for ($i = 0; $i < $numRegistros; $i++) {

                if ($arrObjFaseLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                } else {
                    $strCssTr = '<tr class="trVermelha">';
                }

                $strResultado .= $strCssTr;

                if ($bolCheck) {
                    $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjFaseLitigiosoDTO[$i]->getNumIdFaseLitigioso(), $arrObjFaseLitigiosoDTO[$i]->getStrNome()) . '</td>';
                }
                $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjFaseLitigiosoDTO[$i]->getStrNome()) . '</td>';
                $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjFaseLitigiosoDTO[$i]->getStrDescricao()) . '</td>';
                $strResultado .= '<td align="center">';

                $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjFaseLitigiosoDTO[$i]->getNumIdFaseLitigioso());

                if ($bolAcaoConsultar) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=md_lit_fase_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_fase_litigioso=' . $arrObjFaseLitigiosoDTO[$i]->getNumIdFaseLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Fase" alt="Consultar Fase" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoAlterar) {

                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=md_lit_fase_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_fase_litigioso=' . $arrObjFaseLitigiosoDTO[$i]->getNumIdFaseLitigioso())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Fase" alt="Alterar Fase" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                    $strId        = $arrObjFaseLitigiosoDTO[$i]->getNumIdFaseLitigioso();
                    $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(PaginaSEI::tratarHTML($arrObjFaseLitigiosoDTO[$i]->getStrNome(), true));
                }

                if ($bolAcaoDesativar && $arrObjFaseLitigiosoDTO[$i]->getStrSinAtivo() == 'S') {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\', \'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Fase" alt="Desativar Fase" class="infraImg" /></a>&nbsp;';
                } else {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Fase" alt="Reativar Fase" class="infraImg" /></a>&nbsp;';
                }

                if ($bolAcaoExcluir) {
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Fase" alt="Excluir Fase" class="infraImg" /></a>&nbsp;';
                }

                $strResultado .= '</td></tr>' . "\n";
            }
            $strResultado .= '</table>';
        }
        if ($_GET['acao'] == 'fase_litigioso_selecionar') {
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
        } else {
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'])) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
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
<?
    PaginaSEI::getInstance()->fecharStyle();
    PaginaSEI::getInstance()->montarJavaScript();
    PaginaSEI::getInstance()->abrirJavaScript();
?>

    function inicializar(){
    if ('<?= $_GET['acao'] ?>'=='md_lit_fase_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
    }else{
    document.getElementById('btnFechar').focus();
    }
    infraEfeitoTabelas();
    }

<? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id,desc){
    if (confirm("Confirma desativação da Fase \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFaseLitigiosoLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmFaseLitigiosoLista').submit();
    }
    }

    function acaoDesativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma fase selecionada.');
    return;
    }
    if (confirm("Confirma a desativação das Fases selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFaseLitigiosoLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmFaseLitigiosoLista').submit();
    }
    }
<? } ?>

    function acaoReativar(id,desc){
    if (confirm("Confirma reativação da Fase \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFaseLitigiosoLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmFaseLitigiosoLista').submit();
    }
    }

    function acaoReativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Fase selecionado.');
    return;
    }
    if (confirm("Confirma a reativação das Fases selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFaseLitigiosoLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmFaseLitigiosoLista').submit();
    }
    }

<? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id,desc){
    if (confirm("Confirma exclusão da Fase \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFaseLitigiosoLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmFaseLitigiosoLista').submit();
    }
    }

    function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma fase selecionada.');
    return;
    }
    if (confirm("Confirma a exclusão das Fases selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFaseLitigiosoLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmFaseLitigiosoLista').submit();
    }
    }
<? } ?>

<?
    PaginaSEI::getInstance()->fecharJavaScript();
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmFaseLitigiosoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">

        <?
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>