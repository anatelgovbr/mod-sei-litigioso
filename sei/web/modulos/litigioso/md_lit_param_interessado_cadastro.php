<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/03/2017 - criado por Ellyson de Jesus Silva
 *
 * Versão do Gerador de Código: 1.40.0
 *
 * Versão no SVN: $Id$
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_lit_param_interessado_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    PaginaSEI::getInstance()->salvarCamposPost(array('selMdLitTipoControle', 'selMdLitNomeFuncional'));

    $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'md_lit_parametrizar_interessado_cadastrar':
            $strTitulo = 'Parametrizar Dados Complementares do Interessado - ';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdLitParamInteressado" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tipo_processo_litigioso']))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            //chamando o objeto do MdLitTipoControleRN
            $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
            $objTipoControleLitigiosoRN = new MdLitTipoControleRN();

            //consulta do tipo de controle
            $objTipoControleLitigiosoDTO->retTodos();
            $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
            $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);

            if (isset($_POST['sbmCadastrarMdLitParamInteressado'])) {

                $objTipoControleLitigiosoDTO->setStrSinParamModalComplInteressado($_POST['rdoSinParamModalComplInteres']);

                $arrObjParametrizarInteressadoDTOSalvar = array();
                $bolEditar = false;

                //populando o $arrObjParametrizarInteressadoDTOSalvar que foram salvo
                foreach ($_POST['hdnArrayIdMdLitNomeFuncional'] as $IdMdLitNomeFuncional) {
                    $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();

                    $objMdLitParametrizarInteressadoDTO->setStrSinExibe(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinExibe_' . $IdMdLitNomeFuncional]));
                    $objMdLitParametrizarInteressadoDTO->setStrSinObrigatorio(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinObrigatorio_' . $IdMdLitNomeFuncional]));
                    $objMdLitParametrizarInteressadoDTO->setStrLabelCampo($_POST['txtLabelCampo_' . $IdMdLitNomeFuncional]);
                    $objMdLitParametrizarInteressadoDTO->setNumTamanho($_POST['txtTamanho_' . $IdMdLitNomeFuncional]);
                    $objMdLitParametrizarInteressadoDTO->setStrDescricaoAjuda($_POST['txtDescricaoAjuda_' . $IdMdLitNomeFuncional]);
                    $objMdLitParametrizarInteressadoDTO->setStrSinCampoMapeado($_POST['hdnSinCampoMapeado_' . $IdMdLitNomeFuncional]);
                    $objMdLitParametrizarInteressadoDTO->setNumIdMdLitNomeFuncional($IdMdLitNomeFuncional);
                    $objMdLitParametrizarInteressadoDTO->setNumIdMdLitTipoControle($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                    $objMdLitParametrizarInteressadoDTO->setNumIdMdLitParamInteressado($_POST['IdMdLitParamInteressado_' . $IdMdLitNomeFuncional]);
                    if (!empty($_POST['IdMdLitParamInteressado_' . $IdMdLitNomeFuncional]))
                        $bolEditar = true;

                    $arrObjParametroSalvar['MdLitParametrizarInteressadoDTO'][] = $objMdLitParametrizarInteressadoDTO;
                }

                try {

                    //populando o array com o tipo de processo
                    $arrObjParametroSalvar['MdLitTipoControleDTO'] = $objTipoControleLitigiosoDTO;
                    $objMdLitParametrizarInteressadoRN = new MdLitParametrizarInteressadoRN();

                    if ($bolEditar && $_POST['rdoSinParamModalComplInteres'] == 'N') {
                        // se modificar o SinParamModalComplInteres para Não os registro do ParametrizarInteressado será excluido
                        $objMdLitParametrizarInteressadoRN->excluirMultiplos($arrObjParametroSalvar);
                    } elseif ($bolEditar) {
                        $objMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->alterarMultiplos($arrObjParametroSalvar);
                    } else {
                        $objMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->cadastrarMultiplos($arrObjParametroSalvar);
                    }

                    PaginaSEI::getInstance()->setStrMensagem('Os dados foram parametrizados com sucesso!', InfraPagina::$TIPO_MSG_AVISO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_parametrizar_interessado_cadastrar&acao_origem=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso()));
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }

            }

            $sigla = $objTipoControleLitigiosoDTO->getStrSigla() ? $objTipoControleLitigiosoDTO->getStrSigla() : '';

            $strSubTitulo = 'Tipo de Controle Litigioso: ' . PaginaSEI::tratarHTML($sigla);
            $strTitulo .= PaginaSEI::tratarHTML($sigla);
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    //começo da tabela
    $objMdLitParametrizarInteressadoRN = new MdLitParametrizarInteressadoRN();
    $arrObjMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->listarPorTipoControle($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());


    $objNomeFuncionaRN = new MdLitNomeFuncionalRN();

    $numRegistros = count($arrObjMdLitParametrizarInteressadoDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        $strResultado = '';

        $strCaptionTabela = 'Situações';

        $strResultado .= '<table width="99%" id="tableDadosComplementarInteressado" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';

        $strResultado .= '<th class="infraTh" width="20%">&nbsp;Nome funcional&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="3%">&nbsp;Exibe?&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="3%">&nbsp;Obrigatório?&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="20%" style="min-width: 200px">&nbsp;Label do campo&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">&nbsp;Tamanho&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="35%" style="min-width: 300px">&nbsp;Descrição da ajuda&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">&nbsp;Campo mapeado&nbsp;</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            $idLinha = $arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitNomeFuncional();

            if ($_GET['id_md_lit_nome_funcional'] == $idLinha) {
                $strCssTr = ($strCssTr == '<tr id="paramInteressadoTable_' . $idLinha . '" name="paramInteressadoTable_' . $idLinha . '" class="infraTrAcessada">') ? '<tr id="paramInteressadoTable_' . $idLinha . '" name="paramInteressadoTable_' . $idLinha . '" class="infraTrEscura">' : '<tr id="paramInteressadoTable_' . $idLinha . '" name="paramInteressadoTable_' . $idLinha . '" class="infraTrAcessada">';
            } else {
                $strCssTr = '<tr id="paramInteressadoTable_' . $idLinha . '" name="paramInteressadoTable_' . $idLinha . '" class="infraTrClara">';
            }

            $totalRegistros = count($arrObjMdLitParametrizarInteressadoDTO);

            $exibe = $arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinExibe() === 'S' ? 'checked="checked"' : '';
            $obrigatorio = $arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinObrigatorio() === 'S' ? 'checked="checked"' : '';
            $disabled = $arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinExibe() !== 'S' ? 'disabled="disabled"' : '';
            $numIdMdLitNomeFuncional = $arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitNomeFuncional();
            $sinCampoMapeado = $arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinCampoMapeado() == 'S' ? 'Sim' : 'Não';

            if ($arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$CNPJ_CPF || $arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$OUTORGA) {
                $exibe = 'checked="checked" disabled="disabled" ';
                $obrigatorio = 'checked="checked" disabled="disabled"';
                $disabled = '';
            }

            $strResultado .= $strCssTr;
            $strResultado .= "<td id='nome_funcional_{$numIdMdLitNomeFuncional}'>";
            $strResultado .= "<input type='hidden' name='hdnArrayIdMdLitNomeFuncional[]' value='{$numIdMdLitNomeFuncional}' /> <input type='hidden' name='IdMdLitParamInteressado_{$numIdMdLitNomeFuncional}' value='{$arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitParamInteressado()}' /> ";
            $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitParametrizarInteressadoDTO[$i]->getStrNomeMdLitNomeFuncional());
            $strResultado .= "</td>";
            $strResultado .= "<td align='center'><input type='checkbox' onchange='campoTabelaDisabilidado(this)' name='chkSinExibe_{$numIdMdLitNomeFuncional}' class='infraCheckbox exibe'  id='chkSinExibe_{$numIdMdLitNomeFuncional}' {$exibe}> </input></td>";
            $strResultado .= "<td align='center'><input type='checkbox' {$disabled} name='chkSinObrigatorio_{$numIdMdLitNomeFuncional}' class='obrigatorio infraCheckbox'  id='chkSinObrigatorio_{$numIdMdLitNomeFuncional}' $obrigatorio > </input></td>";
            $strResultado .= "<td align='center'> <input type='text' class='form-control' {$disabled} maxlength='25' name='txtLabelCampo_{$numIdMdLitNomeFuncional}'  id='txtLabelCampo_{$numIdMdLitNomeFuncional}' value='" . PaginaSEI::tratarHTML($arrObjMdLitParametrizarInteressadoDTO[$i]->getStrLabelCampo()) . "'  /> </td>";
            $strResultado .= $arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO ? "<td align='center'> <input {$disabled} type='text' class='form-control' maxlength='3' onkeypress='return SomenteNumero(event)' name='txtTamanho_{$numIdMdLitNomeFuncional}' id='txtTamanho_{$numIdMdLitNomeFuncional}' value='" . PaginaSEI::tratarHTML($arrObjMdLitParametrizarInteressadoDTO[$i]->getNumTamanho()) . "' /> </td>" : "<td></td>";
            $strResultado .= "<td align='center'><textarea id='txtDescricaoAjuda_{$numIdMdLitNomeFuncional}' name='txtDescricaoAjuda_{$numIdMdLitNomeFuncional}' rows='3' class='infraTextArea form-control' onkeypress='return infraLimitarTexto(this,event,250);' {$disabled}>".PaginaSEI::tratarHTML($arrObjMdLitParametrizarInteressadoDTO[$i]->getStrDescricaoAjuda())." </textarea></td>";
            $strResultado .= "<td align='center'> <input type='hidden' name='hdnSinCampoMapeado_{$numIdMdLitNomeFuncional}' id='chkSinCampoMapeado_{$numIdMdLitNomeFuncional}' value='{$arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinCampoMapeado()}' /> {$sinCampoMapeado} </td>";

            $strResultado .= '</tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    //fim da tabela

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_param_interessado_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>

    <form id="frmMdLitParamInteressadoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <p><label class="infraLabel"><?= $strSubTitulo ?></label></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <label id="lblSinParamModalComplInteres" for="lblSinParamModalComplInteres" accesskey=""
                       class="infraLabelObrigatorio">Apresenta Modal de Dados Complementares do Interessado:
                    <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>" name="ajuda"
                         id="imgSinParamModalComplInteres" <?= PaginaSEI::montarTitleTooltip('Este parâmetro define se a modal de Dados Complementares de Interessado será apresentada para os Usuários no cadastro dos processos no Controle Litigioso. Esta opção deve ser marcada caso o órgão queira fazer a Gestão de Multas por integração.', 'Ajuda') ?>
                         class="infraImgModulo"/>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
                <div id="divSinParamModalComplInteresSim" class="infraDivRadio">
                    <input type="radio" name="rdoSinParamModalComplInteres" onclick="configurarTabelaDados();"
                           id="optSinParamModalComplInteresSim" <?= PaginaSEI::getInstance()->setRadio($objTipoControleLitigiosoDTO->getStrSinParamModalComplInteressado(), 'S') ?>
                           class="infraRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <span id="spnSinParamModalComplInteresSim"><label id="lblSinParamModalComplInteresSim"
                                                                      for="optSinParamModalComplInteresSim"
                                                                      class="infraLabelRadio">Sim</label></span>
                </div>
                <div id="divSinParamModalComplInteresNao" class="infraDivRadio">
                    <input type="radio" name="rdoSinParamModalComplInteres" onclick="configurarTabelaDados();"
                           id="optSinParamModalComplInteresNao" <?= PaginaSEI::getInstance()->setRadio($objTipoControleLitigiosoDTO->getStrSinParamModalComplInteressado(), 'N') ?>
                           class="infraRadio" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <span id="spnSinParamModalComplInteresNao"><label id="lblSinParamModalComplInteresNao"
                                                                      for="optSinParamModalComplInteresNao"
                                                                      class="infraLabelRadio">Não</label></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <?
                PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
                ?>
            </div>
        </div>
    </form>
<?
require_once("md_lit_param_interessado_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
