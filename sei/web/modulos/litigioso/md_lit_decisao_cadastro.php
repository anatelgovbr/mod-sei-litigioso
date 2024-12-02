<?
/**
 * ANATEL
 *
 * 11/07/2017 - criado por ellyson.silva - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();

    $strLinkAjaxComboEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_rel_especie_decisao_montar_select');
    $strLinkAjaxComboTipoDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_rel_tipo_decisao_montar_select');
    $strLinkAjaxEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_carregar_especie_decisao');
//    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    //////////////////////////////////////////////////////////////////////////////
//    InfraDebug::getInstance()->setBolLigado(false);
//    InfraDebug::getInstance()->setBolDebugInfra(false);
//    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    $strParametros = '';
    if (isset($_GET['arvore'])) {
        PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
        $strParametros .= '&arvore=' . $_GET['arvore'];
    }

    $arrComandos = array();
    $idMdLitControle = null;
    $bolCadastro = false;
    $arrTabela = array();
    $bolHouveMudanca = true;
    $idUltimaSituacaoDecisoria = null;

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    switch ($_GET['acao']) {
        case 'md_lit_decisao_cadastrar':
            $strTitulo = 'Cadastro de Decisões';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarDecisao" id="sbmCadastrarDecisao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar Decisões</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $idMdLitControle = $_GET['id_md_lit_controle'];

            $objMdLitControleDTO = new MdLitControleDTO();
            $objMdLitControleDTO->retTodos(false);
            $objMdLitControleDTO->setNumIdControleLitigioso($idMdLitControle);

            $objMdLitControleRN = new MdLitControleRN();
            $objMdLitControleDTO = $objMdLitControleRN->consultar($objMdLitControleDTO);


            $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retTodos();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrDispositivo();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrNorma();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retNumIdCondutaLitigioso();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrConduta();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdControleLitigioso($idMdLitControle);

            $objRelDispositivoNormativoCondutaControleLitigiosoRN = new MdLitRelDispositivoNormativoCondutaControleRN();
            $arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO = $objRelDispositivoNormativoCondutaControleLitigiosoRN->listar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

            if (count($_POST)) {
                $arrTabela = MdLitDecisaoINT::gerarItensTabelaDinamicaForm($_POST['decisao']);
                $hdnTbDecisaoAntigo = $_POST['hdnTbDecisaoAntigo'];
                $bolCadastro = true;
                if ($hdnTbDecisaoAntigo != '') {
                    $arrTbDecisaoAntigo = PaginaSEI::getInstance()->getArrItensTabelaDinamica($hdnTbDecisaoAntigo);
                    $arrTbDecisaoNovo = PaginaSEI::getInstance()->getArrItensTabelaDinamica($arrTabela);

                    if (count($arrTbDecisaoAntigo) == count($arrTbDecisaoNovo)) {
                        $bolHouveMudanca = false;
                    }

                    if (!$bolHouveMudanca) {
                        for ($i = 0; $i < count($arrTbDecisaoAntigo); $i++) {
                            if ($arrTbDecisaoAntigo[$i][1] != $arrTbDecisaoNovo[$i][1] ||
                                $arrTbDecisaoAntigo[$i][2] != $arrTbDecisaoNovo[$i][2] ||
                                $arrTbDecisaoAntigo[$i][3] != $arrTbDecisaoNovo[$i][3] ||
                                $arrTbDecisaoAntigo[$i][4] != $arrTbDecisaoNovo[$i][4] ||
                                $arrTbDecisaoAntigo[$i][5] != $arrTbDecisaoNovo[$i][5] ||
                                $arrTbDecisaoAntigo[$i][6] != $arrTbDecisaoNovo[$i][6] ||
                                $arrTbDecisaoAntigo[$i][7] != $arrTbDecisaoNovo[$i][7] ||
                                $arrTbDecisaoAntigo[$i][16] != $arrTbDecisaoNovo[$i][16] ||
                                $arrTbDecisaoAntigo[$i][17] != $arrTbDecisaoNovo[$i][17] ||
                                $arrTbDecisaoAntigo[$i][18] != $arrTbDecisaoNovo[$i][18]
                            )
                                $bolHouveMudanca = true;
                        }
                    }
                }
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}
$strTbCadastroDecisao = '';
$numRegistros = count($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO);
if ($numRegistros > 0) {

    $strCaptionTabela = 'Lista de Infrações selecionadas';

    $strTbCadastroDecisao .= '<table width="100%" id="tableDadosComplementarInteressado" class="infraTable" summary="' . $strCaptionTabela . '">' . "\n";
    $strTbCadastroDecisao .= '<caption class="infraCaption" style="display: none">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strTbCadastroDecisao .= '<tr>';

    $strTbCadastroDecisao .= '<th class="infraTh" width="15%">&nbsp;Infração&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh" width="8%;"style="min-width: 80px;">&nbsp;Localidade&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh" width="20%">&nbsp;Tipo de Decisão&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh" width="20%">&nbsp;Espécie de Decisão&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh multa" style="display: none;" width="10%">&nbsp;Multa&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh ressarcimento" width="10%" style="display: none;">&nbsp;Valor&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh obrigacoes" style="display:none;" width="15%">&nbsp;Obrigação&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh prazo" style="display: none" width="5%">&nbsp;Prazo em Dias&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '</tr>' . "\n";
    $strComboTipoDecosao = MdLitTipoDecisaoINT::montarSelectTipoDecisaoPorTipoControle('null', '&nbsp;', '', $objMdLitControleDTO->getNumIdMdLitTipoControle());

    foreach ($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO as $idLinha => $objRelDispositivoNormativoCondutaControleLitigiosoDTO){

        $idDispositivoNormativoNormaCondutaControle = $objRelDispositivoNormativoCondutaControleLitigiosoDTO->getNumIdDispositivoNormativoNormaCondutaControle();
        $strCssTr = '<tr id="CadastroDecisaoTable_' . $idLinha . '" class="infraTrClara">';
        $strTbCadastroDecisao .= $strCssTr;
        $strTbCadastroDecisao .= "<td id='td-infracao{$idLinha}'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][id]' value='{$idDispositivoNormativoNormaCondutaControle}' /> ";
        $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objRelDispositivoNormativoCondutaControleLitigiosoDTO->getStrInfracao());
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][id_decisao]' value='' /> ";
        $strTbCadastroDecisao .= '<img id="img_mais_' . $idLinha . '"  value="' . $idDispositivoNormativoNormaCondutaControle . '"  nome-linha="idDispositivoNormativo_' . $idLinha . '" src="/infra_css/imagens/mais.gif" title="Adicionar Item" class="infraImg" style="width: 14px; height: 14px;" onclick="incluirLinha(this)">';
        $strTbCadastroDecisao .= '<img id="img__menos_' . $idLinha . '" src="/infra_css/imagens/menos.gif" title="Remover Item" class="infraImg" style="width: 14px; height: 14px;display:none" onclick="removerLinha(this)">';
        $strTbCadastroDecisao .= "</td>";

        $strTbCadastroDecisao .= "<td>";
        $strTbCadastroDecisao .= "
                        <input onclick='changeLocalidades(this, false);' type='radio' name='decisao[idDispositivoNormativo_{$idLinha}][localidade]'
                               id='rdDispositivoNormativo_localidade_{$idLinha}' value ='N' class='infraRadio' tabindex='" . PaginaSEI::getInstance()->getProxTabDados() . "' data-id-select-uf='divUf_{$idLinha}' >" .
            "<label id='lblRdIdNacional_{$idLinha}' class='infraLabelRadio  lblRdNacional' for='rdDispositivoNormativo_localidade_{$idLinha}'>Nacional</label><br>";
        $strTbCadastroDecisao .= "
                        <input onclick='changeLocalidades(this, true);' type='radio' name='decisao[idDispositivoNormativo_{$idLinha}][localidade]'
                               id='rdDispositivoNormativo_uf_{$idLinha}'  value ='U' class='infraRadio' tabindex='" . PaginaSEI::getInstance()->getProxTabDados() . "' data-id-select-uf='divUf_{$idLinha}'>" .
            "<label id='lblRdIdUf_{$idLinha}' class='infraLabelRadio' for='rdDispositivoNormativo_uf_{$idLinha}'>UF</label>";
        $strTbCadastroDecisao .= "<div id='divUf_{$idLinha}' style='display: none; padding-top: 10px' ><select id='selUf_{$idLinha}' name='decisao[idDispositivoNormativo_{$idLinha}][id_uf][]' class='infraSelect multipleSelect form-control' multiple='multiple' style='width: 100%;display: none'>";
        $strTbCadastroDecisao .= UfINT::montarSelectSiglaRI0416(null, null, '');
        $strTbCadastroDecisao .= "</td>";

        $strTbCadastroDecisao .= "<td align='center'>";
        $strTbCadastroDecisao .= "<select id='tipo_decisao_{$idLinha}' name='decisao[idDispositivoNormativo_{$idLinha}][id_md_lit_tipo_decisao]' style='width: 100%;' class='infraSelect form-control' onchange='carregarComboEspecieDecisao(this); carregarTipoDecisao(this, $(this).val(), false)'>";
        $strTbCadastroDecisao .= $strComboTipoDecosao;
        $strTbCadastroDecisao .= "</select></td>";

        $strTbCadastroDecisao .= "<td align='center'><select class='especie-decisao form-control infraSelect' id='id_md_lit_especie_decisao_{$idLinha}' class='infraSelect form-control' name='decisao[idDispositivoNormativo_{$idLinha}][id_md_lit_especie_decisao]' onchange='refreshEspecieAtivos(this); carregarEspecieDecisao(this)' style='width: 100%;display: none'></select></td>";
        $strTbCadastroDecisao .= "<td align='center' class='multa' style='display: none;'><input id='multa_{$idLinha}' onkeypress='return infraMascaraDinheiro(this,event,2,12);' type='text' name='decisao[idDispositivoNormativo_{$idLinha}][multa]' class='infraText form-control' style='width: 90%;display: none' decisao_valor_antigo=''></td>";
        $strTbCadastroDecisao .= "<td align='center' class='ressarcimento' style='display: none;'><input id='valor_ressarcimento_{$idLinha}' onkeypress='return infraMascaraDinheiro(this,event,2,12);' type='text' name='decisao[idDispositivoNormativo_{$idLinha}][valor_ressarcimento]' class='infraText form-control' style='width: 90%; display: none'></td>";
        $strTbCadastroDecisao .= "<td align='center' class='obrigacoes' style='display: none;'><select class='infraSelect form-control' id='id_md_lit_obrigacao_{$idLinha}' name='decisao[idDispositivoNormativo_{$idLinha}][id_md_lit_obrigacao]' style='width: 100%;display: none'></select></td>";
        $strTbCadastroDecisao .= "<td align='center' class='prazo' style='display: none;'>";
        $strTbCadastroDecisao .= "<input id='prazo_{$idLinha}' type='text' class='infraText form-control'  onkeypress='return infraMascaraNumero(this,event,16);' name='decisao[idDispositivoNormativo_{$idLinha}][prazo]' style='width: 90%;display: none'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][id_usuario]'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][id_unidade]'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][data]'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][nome_usuario]'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][sigla_unidade]'>";
        $strTbCadastroDecisao .= "<input type='hidden' id='decisao_idDispositivoNormativo_{$idLinha}_sin_cadastro_parcial'  name='decisao[idDispositivoNormativo_{$idLinha}][sin_cadastro_parcial]' value='S' >";
        $strTbCadastroDecisao .= "</td>";

        $strTbCadastroDecisao .= "</tr> \n";

    }

    $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
    $objMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->buscarUltimaSituacaoDecisoria($_GET['id_procedimento']);

    if ($objMdLitProcessoSituacaoDTO)
        $idUltimaSituacaoDecisoria = $objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao();

}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
//require_once ('md_lit_css_geral.php');
?>
    p.bloco-orientacao{
    color: #000;
    margin: 0;
    line-height: 1.5em;
    font-size: 1.2em;
    }
    .lblRdNacional{display: inline-block;}
    .margem-bottom10{margin-bottom: 10px !important;}
    .ms-choice{border: none !important;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();



PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>
    <form id="frmCadastroDecisao" method="post" onsubmit="OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_lit_controle=' . $_GET['id_md_lit_controle'] . '&id_procedimento=' . $_GET['id_procedimento']) ?>">
        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <?php PaginaSEI::getInstance()->abrirAreaDados(null); ?>
        <input type="hidden" id="hdnTbDecisaoAntigo" name="hdnTbDecisaoAntigo" value="<?= $strTbDecisaoAntigo; ?>">

        <div class="row linha">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <p class="bloco-orientacao margem-bottom10">Orientações: Alterações sobre Decisões anteriores devem estar
                    relacionadas a nova Situação Decisória.</p>
            </div>
        </div>
        <?
        PaginaSEI::getInstance()->montarAreaTabela($strTbCadastroDecisao, $numRegistros);
        ?>
        <input type="hidden" name="hdnIdUltimaSituacaoDecisoria" id="hdnIdUltimaSituacaoDecisoria"
               value="<?= $idUltimaSituacaoDecisoria ?>"/>
        <input type="hidden" name="hdnIdMdLitTipoControle" id="hdnIdMdLitTipoControle"
               value="<?= $objMdLitControleDTO->getNumIdMdLitTipoControle() ?>"/>
    </form>
<?
require_once('md_lit_decisao_cadastro_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
