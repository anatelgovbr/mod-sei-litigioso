<?
/**
 * ANATEL
 *
 * 20/05/2016 - criado por alan.campos@castgroup.com.br - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    $idMdLitTpInforAd = $_GET['id_tp_info_ad'] ? $_GET['id_tp_info_ad'] : $_POST['hdnIdMdLitTpInforAd'];
    $idTipoControleLitigioso = $_GET['id_tipo_controle_litigioso'] ? $_GET['id_tipo_controle_litigioso'] : $_POST['hdnIdTipoControleLitigioso'];
    $IdMdLitCamposAd = '';
    $arrDadosCampos = "";
    $strNomeTipoInformacao =MdLitTpInfoAdINT::recuperarNome($idMdLitTpInforAd);

    $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
    $objMdLitCamposAdDTO->setNumIdMdLitCamposAd(null);
    $objMdLitCamposAdDTO->setStrNome(null);
    $objMdLitCamposAdDTO->setStrAjuda(null);
    $objMdLitCamposAdDTO->setNumIdMdLitCamposAd(null);
    $objMdLitCamposAdDTO->setStrCampoTipo(null);
    $objMdLitCamposAdDTO->setNumIdMdLitCamposAdSel(null);
    $objMdLitCamposAdDTO->setStrSinObrigatorio(null);
    $objMdLitCamposAdDTO->setStrValorMinimo(null);
    $objMdLitCamposAdDTO->setStrValorMaximo(null);
    $objMdLitCamposAdDTO->setNumTamanho(null);
    $objMdLitCamposAdDTO->setStrSinDocExterno(null);

    $opcoesTipo = MdLitCamposAdINT::montarSelectTipoInput(null);
    $opcoesDependencia = MdLitCamposAdSelINT::montarSelectDependencia($idMdLitTpInforAd, $objMdLitCamposAdDTO);

    $strCheckNenhuma = '';
    $strCheckAtualFuturo = '';
    $strCheckAtualPassado = '';
    $strCheckFuturo = '';
    $strCheckPassado = '';
    $strCheckIntervalo = '';

    $arrComandos = array();
    $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCampoAdd" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
    $arrComandos[] = '<button type="button" id="btnVotlar" accesskey="C" name="btnVoltar" value="Voltar" onclick="location.href=\'' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_litigioso=' . $idTipoControleLitigioso . '&id_tp_info_ad='. $idMdLitTpInforAd)) . '\';" class="infraButton">Fecha</button>';



    switch ($_GET['acao']) {
        case 'md_lit_campos_add_cadastrar':

            $strTitulo = 'Novo Campo de Informação Adicional';

            try {
                if($_POST['sbmCadastrarCampoAdd']){
                    MdLitCamposAdINT::salvarRegistro($_POST);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_listar&acao_origem=' . $_GET['acao']. '&id_tp_info_ad='. $_POST['hdnIdMdLitTpInforAd']. '&id_tipo_controle_litigioso=' . $idTipoControleLitigioso));
                    break;
                }

            } catch (Exception $e) {
                $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
                $objMdLitCamposAdDTO->setStrNome($_POST['txtNome']);
                $objMdLitCamposAdDTO->setStrAjuda($_POST['txaAjuda']);
                $objMdLitCamposAdDTO->setStrCampoTipo($_POST['selTipo']);
                $opcoesTipo = MdLitCamposAdINT::montarSelectTipoInput($_POST['selTipo']);
                $rowsOpcoes = MdLitCamposAdSelINT::reMontarTabelaListagemOpcoesComboBox($_POST['opcoesSelect']);
                (new InfraException())->lancarValidacao($e->getMessage());
            }

            break;

        case 'md_lit_campos_add_alterar':

            $strTitulo = 'Alterar Campo de Informação Adicional';

            try {

                if($_POST['sbmCadastrarCampoAdd']){
                    $retorno= MdLitCamposAdINT::alterarRegistro($_POST);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_listar&acao_origem=' . $_GET['acao']. '&id_tp_info_ad='. $_POST['hdnIdMdLitTpInforAd']. '&id_tipo_controle_litigioso=' . $idTipoControleLitigioso));
                    break;
                }

                $objMdLitCamposAdDTO = MdLitCamposAdINT::recuperarCampo($_GET['id_campo_adicional']);
                $IdMdLitCamposAd = $_GET['id_campo_adicional'];
                $opcoesTipo = MdLitCamposAdINT::montarSelectTipoInput($objMdLitCamposAdDTO->getStrCampoTipo());
                $rowsOpcoes = MdLitCamposAdSelINT::montarTabelaListagemOpcoesComboBox($objMdLitCamposAdDTO->getNumIdMdLitCamposAd());
                $opcoesDependencia = MdLitCamposAdSelINT::montarSelectDependencia($idMdLitTpInforAd, $objMdLitCamposAdDTO);

                if ($objMdLitCamposAdDTO->getStrCampoTipo()==MdLitCamposAdINT::$DATA){
                    if ($objMdLitCamposAdDTO->getStrValorMinimo()==null && $objMdLitCamposAdDTO->getStrValorMaximo()==null) {
                        $strCheckNenhuma = 'checked="checked"';
                    }else if ($objMdLitCamposAdDTO->getStrValorMinimo()=='@HOJE@' && $objMdLitCamposAdDTO->getStrValorMaximo()=='@FUTURO@') {
                        $strCheckAtualFuturo = 'checked="checked"';
                    }else if ($objMdLitCamposAdDTO->getStrValorMinimo()=='@PASSADO@' && $objMdLitCamposAdDTO->getStrValorMaximo()=='@HOJE@') {
                        $strCheckAtualPassado = 'checked="checked"';
                    }else if ($objMdLitCamposAdDTO->getStrValorMinimo()=='@AMANHA@' && $objMdLitCamposAdDTO->getStrValorMaximo()=='@FUTURO@') {
                        $strCheckFuturo = 'checked="checked"';
                    }else if ($objMdLitCamposAdDTO->getStrValorMinimo()=='@PASSADO@' && $objMdLitCamposAdDTO->getStrValorMaximo()=='@ONTEM@') {
                        $strCheckPassado = 'checked="checked"';
                    }else {
                        $strCheckIntervalo = 'checked="checked"';
                    }
                }
            } catch (Exception $e) {
                (new InfraException())->lancarValidacao($e->getMessage());
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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_campo_adicional_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>
    <form id="frmCampoAdd" method="post" style="margin-top: 30px" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])) ?>">
        <? PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div id="divMsg">
            <div class="alert alert-danger" role="alert">
                <label></label>
            </div>
        </div>
        <div class="row infraAreaDados">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label id="lblTipoInformacao" class="infraLabelObrigatorio">Tipo de Informação Adicional:</label>
                            <input type="text" id="txtTipoInformacao" name="txtTipoInformacao" class="infraText form-control infraReadOnly" readonly="readonly"
                                   value="<?= PaginaSEI::tratarHTML($strNomeTipoInformacao)?>"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label id="lblRelacionamento"
                                   class=""
                                   for="selRelelacionamento">Dependente de opção:</label>
                            <select id="selRelelacionamento"
                                    name="selRelelacionamento"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                    class="infraSelect form-select">
                                <?= $opcoesDependencia ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio">Nome:</label>
                            <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                                   value="<?= PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrNome()); ?>"
                                   onkeypress="return infraMascaraTexto(this,event,30);" maxlength="30"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label id="lblAjuda" for="txaAjuda" accesskey="N" class="infraLabelOpcional">Texto de Ajuda:</label>
                            <textarea id="txaAjuda"
                                      name="txaAjuda"
                                      rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'3':'4'?>"
                                      onkeypress="return infraLimitarTexto(this,event,250);"
                                      class="infraTextarea form-control"
                                      tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrAjuda());?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label id="lblTipo"
                                   class="infraLabelObrigatorio"
                                   for="selTipo">Tipo do campo:</label>
                            <select id="selTipo"
                                    name="selTipo"
                                    data-valor-antigo="<?= PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrCampoTipo()); ?>"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                    onchange="selecionarTipo(false)"
                                    class="infraSelect form-select">
                                <?= $opcoesTipo ?>
                            </select>
                        </div>
                    </div>
                    <div id="divTamanho" class="col-3">
                        <div class="form-group">
                            <label id="lblTamanho" for="txtTamanho" class="infraLabelObrigatorio">Tamanho:</label>
                            <input type="text" id="txtTamanho" name="txtTamanho" class="infraText form-control" value="<?=PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getNumTamanho())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                        </div>
                    </div>
                    <div class="col-3" id="divDocExterno" class="infraDivCheckbox" style="margin-top: 26px; display: none">
                        <input type="checkbox" id="chkSinDocExterno" name="chkSinDocExterno" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objMdLitCamposAdDTO->getStrSinDocExterno())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                        <label id="lblSinDocExterno" for="chkSinDocExterno" class="infraLabelCheckbox">Aceitar Documento Externo</label>
                    </div>
                    <div class="col-3" id="divSinObrigatorio" class="infraDivCheckbox" style="margin-top: 26px">
                        <input type="checkbox" id="chkSinObrigatorio" name="chkSinObrigatorio" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objMdLitCamposAdDTO->getStrSinObrigatorio())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                        <label id="lblSinObrigatorio" for="chkSinObrigatorio" class="infraLabelCheckbox">Obrigatório</label>
                    </div>
                </div>
                <div class="row" id="fieldSetData" style="display: none">
                    <div class="col-12">
                        <fieldset id="fldData" class="infraFieldset">
                            <legend class="infraLegend">Validação</legend>

                            <div id="divOptDataNenhuma" class="infraDivRadio">
                                <div class="col-6">
                                    <input type="radio" name="rdoValidacaoData" id="optDataNenhuma" value="N" <?=$strCheckNenhuma?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
                                    <label for="optDataNenhuma" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Nenhuma</label>
                                </div>
                            </div>

                            <div id="divOptDataAtualFuturo" class="infraDivRadio">
                                <div class="col-12">
                                    <input type="radio" name="rdoValidacaoData" id="optDataAtualFuturo" value="F" <?=$strCheckAtualFuturo?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
                                    <label for="optDataAtualFuturo" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Data atual ou futuro</label>
                                </div>
                            </div>

                            <div id="divOptDataAtualPassado" class="infraDivRadio">
                                <div class="col-12">
                                    <input type="radio" name="rdoValidacaoData" id="optDataAtualPassado" value="P" <?=$strCheckAtualPassado?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
                                    <label for="optDataAtualPassado" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Data atual ou passado</label>
                                </div>
                            </div>

                            <div id="divOptDataFuturo" class="infraDivRadio">
                                <div class="col-12">
                                    <input type="radio" name="rdoValidacaoData" id="optDataFuturo" value="F" <?=$strCheckFuturo?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
                                    <label for="optDataFuturo" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Futuro</label>
                                </div>
                            </div>

                            <div id="divOptDataPassado" class="infraDivRadio">
                                <div class="col-12">
                                    <input type="radio" name="rdoValidacaoData" id="optDataPassado" value="P" <?=$strCheckPassado?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
                                    <label for="optDataPassado" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Passado</label>
                                </div>
                            </div>

                            <div id="divOptDataIntervalo" class="infraDivRadio col-12">
                                <input type="radio" name="rdoValidacaoData" id="optDataIntervalo" value="I" <?=$strCheckIntervalo?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
                                <label for="optDataIntervalo" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"> Intervalo</label>

                                <div id="divDtIniFim" style="display: none">
                                    <div class="form-group">
                                        <label id="lblDataInicial" for="txtDataInicial" class="infraLabelObrigatorio">Data Inicial:</label>
                                        <input type="text" id="txtDataInicial" name="txtDataInicial" class="infraText form-control"
                                               value="<?=PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrValorMinimo())?>"
                                               width="10" onkeypress="return infraMascaraData(this, event)"
                                               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                                    </div>
                                    <div class="form-group" style="margin-left: 35px">
                                        <label id="lblDataFinal" for="txtDataFinal" class="infraLabelObrigatorio">Data Final:</label>
                                        <input type="text" id="txtDataFinal" name="txtDataFinal" class="infraText form-control"
                                               value="<?=PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrValorMaximo())?>"
                                               width="10" onkeypress="return infraMascaraData(this, event)"
                                               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                </div>
                <div class="row" id="vlrMaxMin" style="display: none">
                    <div class="col-6">
                        <div class="form-group">
                            <label id="lblValorMinimo" for="txtValorMinimo" >Valor Mínimo:</label>
                            <input type="text" id="txtValorMinimo" name="txtValorMinimo" class="infraText form-control"
                                   value="<?=PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrValorMinimo())?>"/>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label id="lblValorMaximo" for="txtValorMaximo" >Valor Máximo:</label>
                            <input type="text" id="txtValorMaximo" name="txtValorMaximo" class="infraText form-control"
                                   value="<?=PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrValorMaximo())?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="divOpcoesCombo" style="display: <? echo ($objMdLitCamposAdDTO->getStrCampoTipo() == MdLitCamposAdINT::$COMBO_BOX || $objMdLitCamposAdDTO->getStrCampoTipo() == MdLitCamposAdINT::$MULTIPLA_SELECAO) ? '': 'none' ?>">
                    <div class="row">
                        <div class="col-8">
                            <label id="lblNomeSelect" for="lblNomeSelect" class="infraLabelObrigatorio">Opções do Combo box:</label>
                            <div class="input-group mb-3">
                                <input type="text" id="txtNomeSelect" name="txtNomeSelect" class="infraText form-control input-adicionar-opcoes" value="" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="503">
                                <input type="hidden" name="idNomeSelect" id="idNomeSelect" value="">
                                <button type="button" name="sbmAdicionarOpcao" id="sbmAdicionarOpcao" value="Adicionar" class="infraButton sbmAdicionarOpcao" onclick="adicionarOpcao()" tabindex="504">Adicionar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <table width="100%" id="tbOpcoesSelect" class="infraTable" summary="Tabela de opções do Combo Box">
                                <tr>
                                    <th class="infraTh"> Nome </th>
                                    <th class="infraTh" width="30%">Ações</th>
                                </tr>
                                <?
                                    // EXIBE AS OPÇÕES CADASTRADAS NA TELA
                                    echo $rowsOpcoes;

                                    // CASO NÃO TENHA OPÇÕES CADASTRADAS EXIBE MENSAGEM
                                    if($rowsOpcoes == '') {
                                        ?>
                                        <tr id="nenhumaOpcao">
                                            <td style="text-align: center" colspan="2">Nenhuma opção cadastrada</td>
                                        </tr>
                                        <?
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <input type="hidden" name="hdnIdMdLitTpInforAd" id="hdnIdMdLitTpInforAd" value="<? echo $idMdLitTpInforAd; ?>">
        <input type="hidden" name="hdnIdMdLitCamposAd" id="hdnIdMdLitCamposAd" value="<? echo $IdMdLitCamposAd;?>">
        <input type="hidden" name="hdnIdTipoControleLitigioso" id="hdnIdTipoControleLitigioso" value="<? echo $idTipoControleLitigioso;?>">
        <input type="hidden" name="opcoesSelect" id="opcoesSelect"  value="">
    </form>
<?
require_once("md_lit_campo_adicional_cadastro_js.php");

PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
