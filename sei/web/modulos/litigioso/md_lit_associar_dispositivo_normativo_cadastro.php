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

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->verificarSelecao('md_lit_dispositivo_normativo_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $arrComandos = array();

    //DispositivoNormativo
    $strLinkDispositivoNormativoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_selecionar&tipo_selecao=2&id_object=objLupaDispositivoNormativo');
    $strLinkAjaxDispositivoNormativo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dispositivo_auto_completar');

    switch ($_GET['acao']) {

        case 'md_lit_associar_dispositivo_normativo_consultar':
            $strItensSelDispositivoNormativos = "";
            $strTitulo = 'Dispositivos Normativos Associados - ';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarDispositivoNormativoLitigioso" id="sbmCadastrarDispositivoNormativoLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tipo_controle_litigioso']))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
            //cadastrando associaçoes
            if (isset($_POST['hdnDispositivosNormativos'])) {

                try {

                    $objAssocDispositivoNormativoLitigiosoRN = new MdLitAssociarDispositivoNormativoRN();
                    $arrDispositivoNormativos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDispositivosNormativos']);

                    $idTipoControle = $_POST['hdnIdTipoControle'];

                    $objAssocDispositivoNormativoDTO = new MdLitAssociarDispositivoNormativoDTO();
                    $objAssocDispositivoNormativoDTO->retTodos();
                    $objAssocDispositivoNormativoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControle']);
                    $listaObjAssocDispositivoNormativoDTO = $objAssocDispositivoNormativoLitigiosoRN->listar($objAssocDispositivoNormativoDTO);

                    foreach ($listaObjAssocDispositivoNormativoDTO as $chave => $objAssocDispositivoNormativo){
                        if(in_array($objAssocDispositivoNormativo->getNumIdDispositivoNormativoLitigioso(), $arrDispositivoNormativos)){
                            unset($listaObjAssocDispositivoNormativoDTO[$chave]);
                            $chaveArray = array_search($objAssocDispositivoNormativo->getNumIdDispositivoNormativoLitigioso(), $arrDispositivoNormativos);
                            unset($arrDispositivoNormativos[$chaveArray]);
                        }
                    }

                    $objAssocDispositivoNormativoLitigiosoRN->validarExclusao($arrDispositivoNormativos, $_POST['hdnIdTipoControle']);
                    //SET DISPOSITIVOS
                    $arrObjAssocDispositivoNormativoDTO = array();


                    foreach ($arrDispositivoNormativos as $objDispositivo){
                        $objAssocDispositivoNormativoDTO = new MdLitAssociarDispositivoNormativoDTO();
                        $objAssocDispositivoNormativoDTO->setNumIdDispositivoNormativoLitigioso($objDispositivo);
                        $objAssocDispositivoNormativoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControle']);
                        array_push($arrObjAssocDispositivoNormativoDTO, $objAssocDispositivoNormativoDTO);
                    }

                    // Cadastro - remove os relacionamentos atuais, adiciona os novos
                    if($listaObjAssocDispositivoNormativoDTO) {
                        $objMdLitRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                        $objMdLitRelDispositivoNormativoTipoControleDTO->setNumIdTipoControle($_POST['hdnIdTipoControle']);
                        $objMdLitRelDispositivoNormativoTipoControleDTO->setNumIdDispositivoNormativo(InfraArray::converterArrInfraDTO($listaObjAssocDispositivoNormativoDTO, 'IdDispositivoNormativoLitigioso'), InfraDTO::$OPER_IN);
                        $objAssocDispositivoNormativoLitigiosoRN->excluirRelacionamentos($objMdLitRelDispositivoNormativoTipoControleDTO);
                    }
                    $objAssocDispositivoNormativoLitigiosoRN->cadastrar($arrObjAssocDispositivoNormativoDTO);
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?&acao=md_lit_tipo_controle_listar'));
                    die;

                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }

            } else {
                //obter dispositivos já associados ao tipo de controle selecionado
                $idTipoControle = $_GET['id_tipo_controle_litigioso'];

                $objAssocDispositivoNormativoDTO = new MdLitAssociarDispositivoNormativoDTO();
                $objAssocDispositivoNormativoDTO->retTodos();
                $objAssocDispositivoNormativoDTO->retStrNorma();
                $objAssocDispositivoNormativoDTO->retStrDispositivo();
                $objAssocDispositivoNormativoDTO->retStrSinAtivoDispositivo();

                $objAssocDispositivoNormativoDTO->setNumIdTipoControleLitigioso($idTipoControle);
                $objAssocDispositivoNormativoDTO->setStrSinAtivoDispositivo('S');

                $objAssocDispositivoNormativoLitigiosoRN = new MdLitAssociarDispositivoNormativoRN();
                $arrItens = $objAssocDispositivoNormativoLitigiosoRN->listar($objAssocDispositivoNormativoDTO);

                foreach ($arrItens as $item) {
                    $strItensSelDispositivoNormativos .= "<option value='" . $item->getNumIdDispositivoNormativoLitigioso() . "'>" . PaginaSEI::tratarHTML($item->getStrNorma()) . " - " . PaginaSEI::tratarHTML($item->getStrDispositivo()) . "</option>";
                }

            }
            //chamando o objeto do MdLitTipoControleRN
            $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
            $objTipoControleLitigiosoDTO->retTodos();
            $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControle);


            $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
            $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);

            $strTitulo .= PaginaSEI::tratarHTML($objTipoControleLitigiosoDTO->getStrSigla());

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
require_once("md_lit_associar_dispositivo_normativo_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmdispositivoNormativoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao_origem'])) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>
        <div class="row">
            <div class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblDescricaoDispositivoNormativo" for="txtDispositivoNormativo" accesskey="q"
                       class="infraLabelObrigatorio">Dispositivos Normativos associados:</label>

                <input type="text" id="txtDispositivoNormativo" name="txtDispositivoNormativo"
                       class="infraText form-control"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                <div class="form-group">
                    <div class="input-group">
                        <select id="selDescricaoDispositivoNormativo" name="selDescricaoDispositivoNormativo" size="30"
                                multiple="multiple" class="infraSelect form-select">
                            <?= $strItensSelDispositivoNormativos ?>
                        </select>
                        <div class="botoes">
                            <img id="imgLupaDispositivoNormativo" onclick="objLupaDispositivoNormativo.selecionar(992,600);"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                alt="Localizar DispositivoNormativo Associada"
                                title="Localizar Dispositivo Normativo Associado" class="infraImg"/>

                            <img id="imgExcluirDispositivoNormativo" onclick="objLupaDispositivoNormativo.remover();"
                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                alt="Remover DispositivoNormativos Associadas"
                                title="Remover Dispositivos Normativos Associados" class="infraImg"/>
                        </div>
                        <input type="hidden" id="hdnIdDispositivoNormativo" name="hdnIdDispositivoNormativo"
                            value="<?= $_POST['hdnIdDispositivoNormativo'] ?>"/>
                        <input type="hidden" id="hdnDispositivosNormativos" name="hdnDispositivosNormativos"
                            value="<?= $_POST['hdnDispositivosNormativos'] ?>"/>
                        <input type="hidden" id="hdnIdTipoControle" name="hdnIdTipoControle"
                            value="<?php echo isset($_GET['id_tipo_controle_litigioso']) ? $_GET['id_tipo_controle_litigioso'] : $_POST['hdnIdTipoControle']; ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?
require_once("md_lit_associar_dispositivo_normativo_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
