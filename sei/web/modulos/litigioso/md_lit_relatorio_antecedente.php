<?php
/**
 * ANATEL
 *
 * 23/11/2018 - criado por renato.monteiro
 *
 */

if ($_GET['acao'] == "md_lit_modal_relatorio_antecedente") {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
}

require_once dirname(__FILE__) . '/../../SEI.php';
session_start();

SessaoSEI::getInstance()->validarLink();
SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

$arrComandos = array();
$arrItens = array();
$hdnInteressado = null;
$msgInicialização = '';
$objMdLitDecisaoDTO = new MdLitDecisaoDTO();


try {


    $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
    $objMdLitReincidenAntecedenDTO->retNumPrazo();
    $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();
    $objMdLitReincidenAntecedenDTO->retStrOrientacao();
    $objMdLitReincidenAntecedenDTO->setStrTipo(MdLitReincidenAntecedenRN::$TIPO_ANTECEDENTE);

    $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
    $objMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->consultar($objMdLitReincidenAntecedenDTO);


    PaginaSEI::getInstance()->prepararOrdenacao($objMdLitDecisaoDTO, 'ProtocoloFormatadoProcedimento', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objMdLitDecisaoDTO, 200);
    PaginaSEI::getInstance()->processarPaginacao($objMdLitDecisaoDTO);

    if ($objMdLitReincidenAntecedenDTO) {

        $filtroDataCorte = InfraData::getStrDataAtual();
        $objMdLitDecisaoDTO->setDtaTransitoJulgado($filtroDataCorte);

        $objMdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
        $objMdLitRelTpDecReinAnteDTO->retNumIdRelMdLitTipoDecisao();
        $objMdLitRelTpDecReinAnteDTO->retStrNomeTipoDecisao();
        $objMdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden());

        $objMdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();
        $arrObjMdLitRelTpDecReinAnteDTO = $objMdLitRelTpDecReinAnteRN->listar($objMdLitRelTpDecReinAnteDTO);

        if (count($arrObjMdLitRelTpDecReinAnteDTO)) {
            $arrIdRelMdLitTipoDecisao = InfraArray::converterArrInfraDTO($arrObjMdLitRelTpDecReinAnteDTO, 'IdRelMdLitTipoDecisao');
            $objMdLitDecisaoDTO->setNumIdMdLitTipoDecisao($arrIdRelMdLitTipoDecisao, InfraDTO::$OPER_IN);
            $objMdLitReincidenAntecedenDTO->setArrObjMdLitRelTpDecReinAnteDTO($arrObjMdLitRelTpDecReinAnteDTO);
        } else {
            PaginaSEI::getInstance()->setStrMensagem('Cadastro incompleto no cadastro do Antecedente na administração do módulo!', InfraPagina::$TIPO_MSG_AVISO);
        }
    } else {
        PaginaSEI::getInstance()->setStrMensagem('Não possui o cadastro do Antecedente na administração do módulo!', InfraPagina::$TIPO_MSG_AVISO);
    }

    switch ($_GET['acao']) {
        case 'md_lit_relatorio_antecedente':

            $strTitulo = 'Antecedentes';
            $arrComandos[] = '<button type="button" onclick="submitFormPesquisa()" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="X" id="btnExportarExcel" onclick="exportarExcel()" class="infraButton">E<span class="infraTeclaAtalho">x</span>portar Excel </button>';
            $arrComandos[] = '<button type="button" onclick="limparFiltros()" accesskey="L" id="btnLimpar" value="Limpar Critérios" class="infraButton"><span class="infraTeclaAtalho">L</span>impar Critérios</button>';
            $captionTabela = "Antecedentes";

            if (count($_POST)) {
                if (!empty($_POST['txtDtCorte'])) {
                    $filtroDataCorte = $_POST['txtDtCorte'];
                    $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS, $filtroDataCorte);
                    $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
                    $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);
                }

                if (!empty($_POST['hdnInteressados'])) {
                    $hdnInteressado = $_POST['hdnInteressados'];
                    $arrInteressados = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnInteressados']);
                    $arrIdContato = InfraArray::simplificarArr($arrInteressados, 0);

                    $objEntradaListarContatosAPI = new ContatoDTO();
                    $objEntradaListarContatosAPI->retNumIdContato();
                    $objEntradaListarContatosAPI->retDblCpf();
                    $objEntradaListarContatosAPI->retDblCnpj();
                    $objEntradaListarContatosAPI->setNumIdContato($arrIdContato, InfraDTO::$OPER_IN);

                    $objSeiRN = new ContatoRN();
                    $arrObjContato = $objSeiRN->listarRN0325($objEntradaListarContatosAPI);
                    $arrCnpjContato = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjContato, 'Cnpj'), null);
                    $arrCpfContato = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjContato, 'Cpf'), null);

                    if (count($arrCpfContato) && count($arrCnpjContato)) {
                        $objMdLitDecisaoDTO->adicionarCriterio(array('CpfContato', 'CnpjContato'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN), array($arrCpfContato, $arrCnpjContato), InfraDTO::$OPER_LOGICO_OR);
                    } elseif (count($arrCpfContato)) {
                        $objMdLitDecisaoDTO->setStrCpfContato($arrCpfContato, InfraDTO::$OPER_IN);
                    } elseif (count($arrCnpjContato)) {
                        $objMdLitDecisaoDTO->setStrCnpjContato($arrCnpjContato, InfraDTO::$OPER_IN);
                    }

                    if (count($arrCpfContato) == 0 && count($arrCnpjContato) == 0) {
                        $objMdLitDecisaoDTO->setStrCnpjContato(null);
                        $objMdLitDecisaoDTO->setStrCpfContato(null);
                        $msgInicialização = "Os interessados selecionados não possui CPNJ/CPF";
                    }
                }
                //não adicionar no metodo listarRelatorio pois vai  dar erro na ação md_lit_relatorio_reincidencia com o distinct
                $objMdLitDecisaoDTO->retStrNomeMdLitTipoDecisao();
                $objMdLitDecisaoDTO->retStrNomeMdLitEspecieDecisao();

                $objMdLitDecisaoRN = new MdLitDecisaoRN();
                $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listarRelatorio($objMdLitDecisaoDTO);

                if ($arrObjMdLitDecisaoDTO != null) {

                    $arrDecisao = InfraArray::converterArrInfraDTO($arrObjMdLitDecisaoDTO, 'NomeMdLitTipoDecisao');
                    $arrEspecie = InfraArray::converterArrInfraDTO($arrObjMdLitDecisaoDTO, 'NomeMdLitEspecieDecisao');
                    $arrProcesso = InfraArray::converterArrInfraDTO($arrObjMdLitDecisaoDTO, 'ProtocoloFormatadoProcedimento');
                    $arrObjMdLitDecisaoDTO = InfraArray::distinctArrInfraDTO($arrObjMdLitDecisaoDTO, "ProtocoloFormatadoProcedimento");

                }

            }

            $showComboDeci = true;
            break;

        case 'md_lit_modal_relatorio_antecedente':

            $strTitulo = 'Antecedentes';
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="infraFecharJanelaModal();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $captionTabela = "Antecedentes";


            $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
            $objMdLitDadoInteressadoDTO->retNumIdMdLitDadoInteressado();
            $objMdLitDadoInteressadoDTO->retDblCnpj();
            $objMdLitDadoInteressadoDTO->retDblCpf();
            $objMdLitDadoInteressadoDTO->setDblControleIdProcedimento($_GET['id_procedimento']);

            $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
            $arrObjMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->listar($objMdLitDadoInteressadoDTO);

            $arrCnpjContato = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'Cnpj'), null);
            $arrCpfContato = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'Cpf'), null);

            if (count($arrCpfContato) && count($arrCnpjContato)) {
                $objMdLitDecisaoDTO->adicionarCriterio(array('CpfContato', 'CnpjContato'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN), array($arrCpfContato, $arrCnpjContato), InfraDTO::$OPER_LOGICO_OR);
            } elseif (count($arrCpfContato)) {
                $objMdLitDecisaoDTO->setStrCpfContato($arrCpfContato, InfraDTO::$OPER_IN);
            } elseif (count($arrCnpjContato)) {
                $objMdLitDecisaoDTO->setStrCnpjContato($arrCnpjContato, InfraDTO::$OPER_IN);
            }

            //pré-filtro infrações
            $objMdLitRelDispositivoNormativoCondutaControleDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
            $objMdLitRelDispositivoNormativoCondutaControleDTO->retTodos(false);
            $objMdLitRelDispositivoNormativoCondutaControleDTO->setNumIdControleLitigioso($_GET['id_md_lit_controle']);

            $objMdLitRelDispositivoNormativoCondutaControleRN = new MdLitRelDispositivoNormativoCondutaControleRN();
            $arrObjMdLitRelDispositivoNormativoCondutaControleDTO = $objMdLitRelDispositivoNormativoCondutaControleRN->listar($objMdLitRelDispositivoNormativoCondutaControleDTO);
            $arrIdConduta = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjMdLitRelDispositivoNormativoCondutaControleDTO, 'IdCondutaLitigioso'), null);


            $filtroDataCorte = MdLitRelatorioReincidenteAntecedenteINT::dataAntigaInfracao($arrObjMdLitRelDispositivoNormativoCondutaControleDTO);
            $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS, $filtroDataCorte);

            $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
            $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);
            //Fim pré-filtro infrações

            //não adicionar no metodo listarRelatorio pois vai  dar erro na ação md_lit_relatorio_reincidencia com o distinct
            $objMdLitDecisaoDTO->retStrNomeMdLitTipoDecisao();
            $objMdLitDecisaoDTO->retStrNomeMdLitEspecieDecisao();

            $objMdLitDecisaoRN = new MdLitDecisaoRN();
            $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listarRelatorio($objMdLitDecisaoDTO);


            $showFunc = true;

            if ($arrObjMdLitDecisaoDTO != null) {
                $arrDecisao = InfraArray::converterArrInfraDTO($arrObjMdLitDecisaoDTO, 'NomeMdLitTipoDecisao');
                $arrEspecie = InfraArray::converterArrInfraDTO($arrObjMdLitDecisaoDTO, 'NomeMdLitEspecieDecisao');
                $arrProcesso = InfraArray::converterArrInfraDTO($arrObjMdLitDecisaoDTO, 'ProtocoloFormatadoProcedimento');
                $arrObjMdLitDecisaoDTO = InfraArray::distinctArrInfraDTO($arrObjMdLitDecisaoDTO, "ProtocoloFormatadoProcedimento");
            }


            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
    PaginaSEI::getInstance()->processarPaginacao($objMdLitDecisaoDTO);
    //Retornando novo Array

    $numRegistros = ($arrObjMdLitDecisaoDTO) ? count($arrObjMdLitDecisaoDTO) : 0;

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}


if ($numRegistros > 0) {
    $strResultado .= '<table width="100%" class="infraTable" summary="Antecedentes">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela($captionTabela, $numRegistros);
    $strResultado .= '</caption>';

    $strResultado .= '<tr>';

    if ($showFunc) {
        $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    }
    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Processo', 'ProtocoloFormatadoProcedimento', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Interessado', 'NomeContato', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= 'CNPJ/CPF';
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Tipo de Decisão', 'NomeMdLitTipoDecisao', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';


    $strResultado .= '<th class="infraTh">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Espécie de Decisão', 'NomeMdLitEspecieDecisao', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" width="15%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Data Trânsito em Julgado', 'TransitoJulgado', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';


    $strCssTr = '<tr class="infraTrEscura">';


    for ($i = 0; $i < $numRegistros; $i++) {

        $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        if ($showFunc) {

            $strResultado .= '<td align="center" valign="top">';
            $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $arrObjMdLitDecisaoDTO[$i]->getStrProtocoloFormatadoProcedimento());
            $strResultado .= '</td>';
        }

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrProtocoloFormatadoProcedimento());//Processo
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';

        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrNomeContato());//Interessado

        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        if (!empty($arrObjMdLitDecisaoDTO[$i]->getStrCpfContato())) {

            $strResultado .= PaginaSEI::tratarHTML(InfraUtil::formatarCpfCnpj($arrObjMdLitDecisaoDTO[$i]->getStrCpfContato()));//CNFJ/CPF

        } else {

            $strResultado .= PaginaSEI::tratarHTML(InfraUtil::formatarCpfCnpj($arrObjMdLitDecisaoDTO[$i]->getStrCnpjContato()));//CNFJ/CPF

        }
        $strResultado .= '</td>';

        //For para Tipo de Decisão

        $strResultado .= '<td  style="word-break:break-all">';
        for ($m = 0; $m < count($arrProcesso); $m++) {

            if ($arrObjMdLitDecisaoDTO[$i]->getStrProtocoloFormatadoProcedimento() == $arrProcesso[$m]) {

                $strResultado .= PaginaSEI::tratarHTML($arrDecisao[$m]) . "<br>";

            }
        }
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all" align="center">';

        //For para Espécie de Decisão

        for ($e = 0; $e < count($arrProcesso); $e++) {

            if ($arrObjMdLitDecisaoDTO[$i]->getStrProtocoloFormatadoProcedimento() == $arrProcesso[$e]) {

                $strResultado .= PaginaSEI::tratarHTML($arrEspecie[$e]) . "<br>";
            }
        }
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getDtaTransitoJulgado());
        $strResultado .= '</td>';

        $strResultado .= '</tr>';

    }


    $strResultado .= '</table>';

}

$strLinkAjaxInteressados = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_RI1225');
$strLinkInteressados = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=2&id_object=objLupaInteressados');
$strUrlExcel = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_relatorio_antecedente_exp_excel&acao_origem=' . $_GET['acao'] . '&excel=1'));

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
$objEditorRN = new EditorRN();
echo $objEditorRN->montarCssEditor(0);
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_relatorio_antecedente_css.php");
PaginaSEI::getInstance()->fecharHead();
$inicializar = is_null($showComboDeci) ? '' : 'onload="inicializar();"';
PaginaSEI::getInstance()->abrirBody($strTitulo , $inicializar);
?>

    <form id="frmTipoControleLitigiosoLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_lit_controle=' . $_GET['id_md_lit_controle'] . '&id_procedimento=' . $_GET['id_procedimento'])) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

        ?>
        <?php if ($showComboDeci) { ?>
            <div class="row linha">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row linha">
                        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <label id="lblInteressados" for="selInteressados" accesskey="" for="txtInteressados"
                                   class="infraLabelObrigatorio">Interessado:
                                <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     name="ajuda"
                                     onmouseover="return infraTooltipMostrar('A pesquisa pode ser realizada digitando o nome ou CPF e CNPJ. Para que a pesquisa retorne Antecedentes, é necessario que no cadastro do interessado consta seu CPF ou CNPJ.', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImgModulo">
                            </label>
                            <input type="text" id="txtInteressados" name="txtInteressados"
                                   class="infraText form-control"
                                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                            <div class="input-group mb-3">
                                <select id="selInteressados" name="selInteressados" size="4" multiple="multiple"
                                        class="infraSelect">
                                </select>
                                <div id="divOpcoesInteressados" class="botoes">
                                    <img id="imgLupaInteressados" onclick="objLupaInteressados.selecionar(700,500);"
                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg"
                                         alt="Selecionar Interessado" title="Selecionar Interessado" class="infraImg"/>
                                    <br>
                                    <img id="imgExcluirInteressados" onclick="objLupaInteressados.remover();"
                                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg"
                                         alt="Remover Interessado Selecionado" title="Remover Interessado Selecionado"
                                         class="infraImg"/>
                                </div>
                            </div>
                            <input type="hidden" name="hdnIdInteressados" id="hdnIdInteressados">
                            <input type="hidden" name="hdnInteressados" id="hdnInteressados"
                                   value="<?= $hdnInteressado ?>">
                        </div>
                    </div>
                    <div class="row linha">
                        <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
                            <label id="lblDtCorte" name="lblDtCorte" for="txtDtCorte" class="infraLabelObrigatorio">Data
                                de
                                Corte:
                                <img align="top" id="imgAjuda"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                     name="ajuda"
                                     onmouseover="return infraTooltipMostrar('Quando a data de corte for informada e opção de pesquisa acionada, serão apresentados os processos com o CPF e CNPJ dos interessados indicados e com situação conclusiva (Trânsito em Julgado).', 'Ajuda');"
                                     onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImgModulo">
                            </label>
                            <div class="input-group mb-3">
                                <input type="text" id="txtDtCorte" name="txtDtCorte"
                                       onchange="return validarFormatoData(this)"
                                       onkeypress="return infraMascara(this, event,'##/##/####')"
                                       class="infraText form-control"
                                       value="<?= PaginaSEI::tratarHTML($filtroDataCorte); ?>"
                                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg' ?>"
                                     id="imgCalDthCorte"
                                     title="Selecionar Data/Hora Inicial" alt="Selecionar Data de Corte"
                                     class="infraImg"
                                     onclick="infraCalendario('txtDtCorte',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                            </div>
                        </div>
                    </div>
                    <div class="row linha">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <iframe id="ifrOrientacaoHTML" onload="resizeIFrameOrientacao()" name=ifrOrientacaoHTML style="width:100%"
                                    frameborder="0" marginheight="0" marginwidth="0"
                                    src="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_antecedencia_reincidencia_orientacao&id_md_lit_reinciden_anteceden=' . $objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden()) ?>"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        <? } ?>

        <? if ($showFunc) { ?>

            <div class="row linha">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <iframe id="ifrOrientacaoHTML" onload="resizeIFrameOrientacao()" name=ifrOrientacaoHTML style="width:100%;"
                        frameborder="0" marginheight="0" marginwidth="0"
                        src="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_antecedencia_reincidencia_orientacao&id_md_lit_reinciden_anteceden=' . $objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden()) ?>"></iframe>                    
                </div>
            </div>
        <? } ?>

        <?

        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);

        if ($showComboDeci) {
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        }
        ?>

    </form>
<?php
require_once("md_lit_relatorio_antecedente_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>