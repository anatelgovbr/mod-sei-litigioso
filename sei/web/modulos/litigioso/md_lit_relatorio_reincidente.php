<?php
/**
 * ANATEL
 *
 * 23/11/2018 - criado por renato.monteiro
 *
 */

require_once dirname(__FILE__) . '/../../SEI.php';
session_start();

//////////////////////////////////////////////////////////////////////////////
//InfraDebug::getInstance()->setBolLigado(true);
//InfraDebug::getInstance()->setBolDebugInfra(true);
//InfraDebug::getInstance()->limpar();
//////////////////////////////////////////////////////////////////////////////

SessaoSEI::getInstance()->validarLink();
SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

$arrComandos = array();
$arrItens = array();
$objMdLitDecisaoDTO = new MdLitDecisaoDTO();
$hdnInteressado = null;
$hdnDispositivo = null;
$hdnConduta = null;
$selConduta = null;
$msgInicializacao = '';
$mostrarFiltro = true;
$strUrlFormularioRelatorio = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']);

try {

// recuperando a parametrização da reincidência
    $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
    $objMdLitReincidenAntecedenDTO->retNumPrazo();
    $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();
    $objMdLitReincidenAntecedenDTO->retStrOrientacao();
    $objMdLitReincidenAntecedenDTO->retStrTipoRegraReincidencia();
    $objMdLitReincidenAntecedenDTO->setStrTipo(MdLitReincidenAntecedenRN::$TIPO_REINCIDENCIA);

    $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
    $objMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->consultar($objMdLitReincidenAntecedenDTO);


    PaginaSEI::getInstance()->prepararOrdenacao($objMdLitDecisaoDTO, 'Norma', InfraDTO::$TIPO_ORDENACAO_ASC, true);
    $objMdLitDecisaoDTO->setOrdStrDispositivo(InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objMdLitDecisaoDTO, 200);

    if ($objMdLitReincidenAntecedenDTO) {
        //filtrando conforme a parametrização do módulo
        $filtroDataCorte = InfraData::getStrDataAtual();
        $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS, $filtroDataCorte);
        $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
        $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);

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
            PaginaSEI::getInstance()->setStrMensagem('Cadastro incompleto no cadastro da Reincidência Específica na administração do módulo!', InfraPagina::$TIPO_MSG_AVISO);
        }
    } else {
        PaginaSEI::getInstance()->setStrMensagem('Não possui o cadastro da Reincidência Específica na administração do módulo!', InfraPagina::$TIPO_MSG_AVISO);
    }

    switch ($_GET['acao']) {
        case 'md_lit_relatorio_reincidencia':

            $strTitulo = 'Reincidências Específicas';
            $arrComandos[] = '<button type="button" onclick="submitFormPesquisa()" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="X" id="btnExportarExcel" onclick="exportarExcel()" class="infraButton">E<span class="infraTeclaAtalho">x</span>portar Excel </button>';
            $arrComandos[] = '<button type="button" onclick="limparFiltros()" accesskey="L" id="btnLimpar" value="Limpar Critérios" class="infraButton"><span class="infraTeclaAtalho">L</span>impar Critérios</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $captionTabela = "Reincidências Específicas";

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
                    $objEntradaListarContatosAPI->retStrCnpj();
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
                        $msgInicializacao = "Os interessados selecionados não possui CPNJ/CPF";
                    }
                }
                if (!empty($_POST['hdnDispositivo']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO) {
                    $hdnDispositivo = $_POST['hdnDispositivo'];
                    $arrDispositivos = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnDispositivo']);
                    $arrIdDispositivos = InfraArray::simplificarArr($arrDispositivos, 0);

                    $objMdLitDecisaoDTO->setNumIdDispositivoNormativoMdLitRelDisNorConCtr($arrIdDispositivos, InfraDTO::$OPER_IN);
                }

                if (!empty($_POST['hdnConduta']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$CONDUTA) {
                    $hdnConduta = $_POST['hdnConduta'];
                    $arrConduta = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnConduta']);
                    $arrIdConduta = InfraArray::simplificarArr($arrConduta, 0);

                    $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($arrIdConduta, InfraDTO::$OPER_IN);
                }
                if (!empty($_POST['hdnDispositivoConduta']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA) {
                    $hdnConduta = $_POST['hdnConduta'];
                    $hdnDispositivo = $_POST['hdnDispositivo'];
                    $arrDispositivoConduta = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnDispositivoConduta']);

                    $arrNomeCriterioDispositivoNormativo = array();
                    foreach ($arrDispositivoConduta as $key => $dispositivoConduta) {
                        $nomeCriterio = 'criterioDispositivoConduta' . $key;
                        $arrNomeCriterioDispositivoNormativo[] = $nomeCriterio;
                        $objMdLitDecisaoDTO->adicionarCriterio(array('IdCondutaMdLitRelDisNorConCtr', 'IdDispositivoNormativoMdLitRelDisNorConCtr'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array($dispositivoConduta[1], $dispositivoConduta[0]), InfraDTO::$OPER_LOGICO_AND, $nomeCriterio);
                    }
                    if (count($arrNomeCriterioDispositivoNormativo) > 1) {
                        $objMdLitDecisaoDTO->agruparCriterios($arrNomeCriterioDispositivoNormativo, array_fill(0, count($arrNomeCriterioDispositivoNormativo) - 1, InfraDTO::$OPER_LOGICO_OR));
                    }

                }

                if (!empty($_POST['selCondutaEspecifica']) && $_POST['selCondutaEspecifica'] != 'null') {
                    $selConduta = $_POST['selCondutaEspecifica'];
                    $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($_POST['selCondutaEspecifica']);
                }

                if (!empty($_POST['selDispositivoEspecifica']) && $_POST['selDispositivoEspecifica'] != 'null') {
                    $selDispositivo = $_POST['selDispositivoEspecifica'];
                    $objMdLitDecisaoDTO->setNumIdDispositivoNormativoMdLitRelDisNorConCtr($_POST['selDispositivoEspecifica']);
                }

                $objMdLitDecisaoRN = new MdLitDecisaoRN();
                $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listarRelatorio($objMdLitDecisaoDTO);

            }

            break;
        case 'md_lit_modal_relatorio_reincidencia':
            $strTitulo = 'Reincidências Específicas';
            $captionTabela = "Reincidências Específicas";
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="infraFecharJanelaModal();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            //buscando o contato do processo
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

            //buscando a conduta do controle litigioso
            $objMdLitRelDispositivoNormativoCondutaControleDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
            $objMdLitRelDispositivoNormativoCondutaControleDTO->retTodos(false);
            $objMdLitRelDispositivoNormativoCondutaControleDTO->setNumIdControleLitigioso($_GET['id_md_lit_controle']);

            $objMdLitRelDispositivoNormativoCondutaControleRN = new MdLitRelDispositivoNormativoCondutaControleRN();
            $arrObjMdLitRelDispositivoNormativoCondutaControleDTO = $objMdLitRelDispositivoNormativoCondutaControleRN->listar($objMdLitRelDispositivoNormativoCondutaControleDTO);

            $objMdLitReincidenAntecedenRN->adicionarFiltroInfracaoMesmaNatureza($objMdLitReincidenAntecedenDTO, $objMdLitDecisaoDTO, $arrObjMdLitRelDispositivoNormativoCondutaControleDTO);

            //adicionando os filtros
            $filtroDataCorte = MdLitRelatorioReincidenteAntecedenteINT::dataAntigaInfracao($arrObjMdLitRelDispositivoNormativoCondutaControleDTO);
            $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS, $filtroDataCorte);
            $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
            $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);

            if (!empty($_POST['selCondutaEspecifica']) && $_POST['selCondutaEspecifica'] != 'null') {
                $selConduta = $_POST['selCondutaEspecifica'];
                $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($_POST['selCondutaEspecifica']);
            }

            if (!empty($_POST['selDispositivoEspecifica']) && $_POST['selDispositivoEspecifica'] != 'null') {
                $selDispositivo = $_POST['selDispositivoEspecifica'];
                $objMdLitDecisaoDTO->setNumIdDispositivoNormativoMdLitRelDisNorConCtr($_POST['selDispositivoEspecifica']);
            }

            $mostrarFiltro = false;
            //colocando a pagina sem menu e titulo inicial
            PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
            $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS, $filtroDataCorte);
            $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
            $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);
            $objMdLitDecisaoRN = new MdLitDecisaoRN();
            $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listarRelatorio($objMdLitDecisaoDTO);

            $strUrlFormularioRelatorio = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $_GET['id_procedimento'] . '&id_md_lit_controle=' . $_GET['id_md_lit_controle']);
            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
    //Fim teste
    $numRegistros = ($arrObjMdLitDecisaoDTO) ? count($arrObjMdLitDecisaoDTO) : 0; // DTO Combo

    $strComboConduta = MdLitDecisaoINT::montarSelectCondutaPorArrMdLitDecisao('null', 'Todos', $selConduta, $arrObjMdLitDecisaoDTO);
    $strComboDispositivo = MdLitDecisaoINT::montarSelectDispositivoPorArrMdLitDecisao('null', 'Todos', $selDispositivo, $arrObjMdLitDecisaoDTO);
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}


PaginaSEI::getInstance()->processarPaginacao($objMdLitDecisaoDTO);
$strResultado = '';
//Tabela de resultado.
if ($numRegistros > 0) {
    $strResultado .= '<table width="100%" id="tableRelatorioReincidencia" class="infraTable" summary="Reincidência Específicas">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela($captionTabela, $numRegistros);
    $strResultado .= '</caption>';

    if ($_GET['acao'] == 'md_lit_modal_relatorio_reincidencia') {
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    }

    $strResultado .= '<th class="infraTh" style="width:170px">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Processo', 'ProtocoloFormatadoProcedimento', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" style="width:170px">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Interessado', 'NomeContato', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" style="width:130px;">';
    $strResultado .= 'CNPJ/CPF';
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" style="width: 20%">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Norma', 'Norma', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" style="width:90px;">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Dispositivo', 'Dispositivo', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    if ($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() != MdLitReincidenAntecedenRN::$DISPOSITIVO) {
        $strResultado .= '<th class="infraTh" style="width: 20%">';
        $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Conduta', 'Conduta', $arrObjMdLitDecisaoDTO);
        $strResultado .= '</th>';
    }

    $strResultado .= '<th class="infraTh" style="width:120px;">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Data Trânsito em Julgado', 'TransitoJulgado', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';


    $strCssTr = '<tr class="infraTrEscura">';

    $arrNorma = array();


    for ($i = 0; $i < $numRegistros; $i++) {

        $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        if ($_GET['acao'] == 'md_lit_modal_relatorio_reincidencia') {
            $strResultado .= '<td align="center" valign="top">';
            $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, null, $arrObjMdLitDecisaoDTO[$i]->getStrProtocoloFormatadoProcedimento());
            $strResultado .= '</td>';
        }

        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrProtocoloFormatadoProcedimento());//Processo
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';

        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrNomeContato());//Interessado

        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        if (!empty($arrObjMdLitDecisaoDTO[$i]->getStrCpfContato())) { //Mudar

            $strResultado .= PaginaSEI::tratarHTML(InfraUtil::formatarCpfCnpj($arrObjMdLitDecisaoDTO[$i]->getStrCpfContato()));//CNFJ/CPF

        } else {

            $strResultado .= PaginaSEI::tratarHTML(InfraUtil::formatarCpfCnpj($arrObjMdLitDecisaoDTO[$i]->getStrCnpjContato()));//CNFJ/CPF

        }
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrNorma());
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrDispositivo());
        $strResultado .= '</td>';

        if ($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() != MdLitReincidenAntecedenRN::$DISPOSITIVO) {
            $strResultado .= '<td  style="word-break:break-all">';
            $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrConduta());
            $strResultado .= '</td>';
        }


        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->isSetDtaTransitoJulgado() ? $arrObjMdLitDecisaoDTO[$i]->getDtaTransitoJulgado() : null); //Data do Transito em julgado
        $strResultado .= '</td>';

        $strResultado .= '</tr>';
    }


    $strResultado .= '</table>';


}

//Links para uso com AJAX
$strLinkAjaxInteressados = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_RI1225');
$strLinkInteressados = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=2&id_object=objLupaInteressados');
$strLinkAjaxDispositivos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_dispositivo_auto_completar');
$strLinkDispositivos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dispositivo_normativo_selecionar&tipo_selecao=2&id_object=objLupaDispositivos');
$strLinkAjaxConduta = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_conduta_auto_completar');
$strLinkConduta = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_conduta_selecionar&tipo_selecao=2&id_object=objLupaConduta');
$strLinkAjaxAssociarDispositivoConduta = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_associar_dispositivo_conduta');

$strUrlExcel = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_relatorio_reincidencia_exp_excel&acao_origem=' . $_GET['acao'] . '&excel=1'));;

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
require_once("md_lit_relatorio_reincidente_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>
<form id="frmTipoControleLitigiosoLista" method="post" onsubmit="return validarPesquisaReincidencia();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML($strUrlFormularioRelatorio) ?>">

    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <?php if ($mostrarFiltro) { ?>
    <!--  Interessados -->
    <div class="row linha">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="row linha">
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label id="lblInteressados" for="selInteressados" accesskey="" for="txtInteressados"
                           class="infraLabelObrigatorio">Interessado:
                        <img align="top" id="imgAjuda"
                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>" name="ajuda"
                             onmouseover="return infraTooltipMostrar('A pesquisa pode ser realizada digitando o nome ou CPF e CNPJ. Para que a pesquisa retorne Reincidência, é necessário que no cadastro do Interessado consta seu CPF ou CNPJ.\n \n Caso necessário, pode realizar uma pesquisa avançada clicando no ícone de lupa abaixo.', 'Ajuda');"
                             onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImgModulo">
                    </label>
                    <input type="text" id="txtInteressados" name="txtInteressados" class="infraText form-control"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                    <div class="input-group mb-3">
                        <select id="selInteressados" name="selInteressados" size="4" multiple="multiple"
                                class="infraSelect form-select">
                        </select>
                        <div id="divOpcoesInteressados">
                            <img id="imgLupaInteressados" onclick="objLupaInteressados.selecionar(700,500);"
                                 src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                 alt="Selecionar Interessado"
                                 title="Selecionar Interessado"
                                 class="infraImg"/>
                            <br>
                            <img id="imgExcluirInteressados" onclick="objLupaInteressados.remover();"
                                 src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                 alt="Remover Interessado Selecionado"
                                 title="Remover Interessado Selecionado" class="infraImg"/>
                        </div>
                    </div>
                    <input type="hidden" name="hdnIdInteressados" id="hdnIdInteressados">
                    <input type="hidden" name="hdnInteressados" id="hdnInteressados" value="<?= $hdnInteressado ?>">
                </div>
            </div>
            <div class="row linha">
                <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
                    <label id="lblDtCorte" name="lblDtCorte" for="txtDtCorte" class="infraLabelObrigatorio">Data de
                        Corte:
                        <img align="top" id="imgAjuda"
                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg?<?= Icone::VERSAO ?>" name="ajuda"
                             onmouseover="return infraTooltipMostrar('Quando a data de corte for informada e opção de pesquisa acionada, serão apresentados os processos com o CPF ou CNPJ dos interessados indicados e com situação conclusiva (Trânsito em Julgado).', 'Ajuda');"
                             onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImgModulo">
                    </label>
                    <div class="input-group mb-3">
                        <input type="text" id="txtDtCorte" name="txtDtCorte" onchange="return validarFormatoData(this)"
                               onkeypress="return infraMascara(this, event,'##/##/####')" class="infraText form-control"
                               value="<?= PaginaSEI::tratarHTML($filtroDataCorte); ?>"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/calendario.svg?'.Icone::VERSAO ?>"
                             id="imgCalDthCorte" title="Selecionar Data/Hora Inicial" alt="Selecionar Data de Corte"
                             class="infraImg"
                             onclick="infraCalendario('txtDtCorte',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                    </div>
                </div>
            </div>
            <?php if ($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO || $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA) { ?>
                <div class="row linha" style="margin-left: 1%;">
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <label id="lblDispositivo" accesskey="" for="rdFiltroDispositivo" class="infraLabelObrigatorio">Dispositivo
                            Normativo:</label>
                        <input type="text" id="txtDispositivo" name="txtDispositivo" class="infraText form-control"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                        <div class="input-group mb-3">
                            <select id="selDispositivo" name="selDispositivo" size="4" multiple="multiple"
                                    class="infraSelect form-select">
                            </select>
                            <div id="divOpcoesDispositivo" class="botoes">
                                <img id="imgLupaDispositivo" onclick="objLupaDispositivos.selecionar(700,500);"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/pesquisar.svg?<?= Icone::VERSAO ?>"
                                     alt="Selecionar Dispositivo Normativo"
                                     title="Selecionar Dispositivo Normativo" class="infraImg"/>
                                <br>
                                <img id="imgExcluirDispositivo" onclick="objLupaDispositivos.remover();"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                     alt="Remover Dispositivo Normativo Selecionado"
                                     title="Remover Dispositivo Normativo Selecionado" class="infraImg"/>
                            </div>
                        </div>
                        <input type="hidden" name="hdnIdDispositivo" id="hdnIdDispositivo">
                        <input type="hidden" name="hdnDispositivo" id="hdnDispositivo"
                               value="<?= PaginaSEI::tratarHTML($hdnDispositivo) ?>">
                    </div>
                </div>
            <?php } ?>
            <?php if ($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$CONDUTA || $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA) { ?>
                <div class="row linha" style="margin-left: 1%;">
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <label id="lblConduta" accesskey="" for="rdFiltroConduta"
                               class="infraLabelObrigatorio">Conduta:</label>
                        <input type="text" id="txtConduta" name="txtConduta" class="infraText form-control"
                               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                        <div class="input-group mb-3">
                            <select id="selConduta" name="selConduta" size="4" multiple="multiple" class="infraSelect form-select">
                            </select>
                            <div id="divOpcoesConduta" class="botoes">
                                <img id="imgLupaConduta" onclick="objLupaConduta.selecionar(700,500);"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/lupa.svg?<?= Icone::VERSAO ?>"
                                     alt="Selecionar Conduta" title="Selecionar Conduta"
                                     class="infraImg"/>
                                <br>
                                <img id="imgExcluirConduta" onclick="objLupaConduta.remover();"
                                     src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/remover.svg?<?= Icone::VERSAO ?>"
                                     alt="Remover Conduta Selecionado" title="Remover Conduta Selecionado"
                                     class="infraImg"/>
                            </div>
                        </div>
                        <input type="hidden" name="hdnIdConduta" id="hdnIdConduta">
                        <input type="hidden" name="hdnConduta" id="hdnConduta"
                               value="<?= PaginaSEI::tratarHTML($hdnConduta) ?>">
                    </div>
                </div>
            <?php } ?>
            <?php } ?>
            <input type="hidden" name="hdnDispositivoConduta" id="hdnDispositivoConduta" value="">
            <div class="row linha">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <iframe id=ifrOrientacaoHTML onload="resizeIFrameOrientacao()" name=ifrOrientacaoHTML
                            style="width:100%;" frameborder="0" marginheight="0" marginwidth="0"
                            src="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_antecedencia_reincidencia_orientacao&id_md_lit_reinciden_anteceden=' . $objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden()) ?>"></iframe>
                </div>
            </div>
        </div>
    </div>
    <? if ($strComboConduta) { ?>
        <div class="row linha" style="margin-left: 1%;">
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <label id="lblDispositivoEspecifica" for="selDispositivoEspecifica" name="lblDispositivoEspecifica"
                       class="infraLabelOpcional">Dispositivo Normativo:</label>
                <select id="selDispositivoEspecifica" style="width:98%" name="selDispositivoEspecifica"
                        class="infraSelect form-select"
                        onchange="submitFormPesquisa()">
                    <?php echo $strComboDispositivo ?>

                </select>
            </div>
        </div>
        <?php if ($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() != MdLitReincidenAntecedenRN::$DISPOSITIVO) { ?>
            <div class="row linha" style="margin-left: 1%;">
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label id="lblCondutaEspecifica" for="selCondutaEspecifica" name="lblCondutaEspecifica"
                           class="infraLabelOpcional">Conduta:</label>
                    <select id="selCondutaEspecifica" name="selCondutaEspecifica" class="infraSelect form-select"
                            onchange="submitFormPesquisa()">
                        <?php echo $strComboConduta ?>
                    </select>
                </div>
            </div>
        <?php } ?>
    <? } ?>

    <div style="padding: 8px;">
        <?php
            PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>        
    </div>

</form>
<?php
require_once("md_lit_relatorio_reincidente_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>