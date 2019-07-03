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

    if($objMdLitReincidenAntecedenDTO){
        //filtrando conforme a parametrização do módulo
        $filtroDataCorte = InfraData::getStrDataAtual();
        $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS,$filtroDataCorte);
        $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
        $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);

        $objMdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
        $objMdLitRelTpDecReinAnteDTO->retNumIdRelMdLitTipoDecisao();
        $objMdLitRelTpDecReinAnteDTO->retStrNomeTipoDecisao();
        $objMdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden());

        $objMdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();
        $arrObjMdLitRelTpDecReinAnteDTO = $objMdLitRelTpDecReinAnteRN->listar($objMdLitRelTpDecReinAnteDTO);

        if(count($arrObjMdLitRelTpDecReinAnteDTO)){
            $arrIdRelMdLitTipoDecisao = InfraArray::converterArrInfraDTO($arrObjMdLitRelTpDecReinAnteDTO, 'IdRelMdLitTipoDecisao');
            $objMdLitDecisaoDTO->setNumIdMdLitTipoDecisao($arrIdRelMdLitTipoDecisao, InfraDTO::$OPER_IN);
            $objMdLitReincidenAntecedenDTO->setArrObjMdLitRelTpDecReinAnteDTO($arrObjMdLitRelTpDecReinAnteDTO);
        }else{
            PaginaSEI::getInstance()->setStrMensagem('Cadastro incompleto no cadastro da Reincidência Específica na administração do módulo!',InfraPagina::$TIPO_MSG_AVISO);
        }
    }else{
        PaginaSEI::getInstance()->setStrMensagem('Não possui o cadastro da Reincidência Específica na administração do módulo!',InfraPagina::$TIPO_MSG_AVISO);
    }

    switch($_GET['acao']) {
        case 'md_lit_relatorio_reincidencia':

            $strTitulo = 'Reincidências Específicas';
            $arrComandos[] = '<button type="button" onclick="submitFormPesquisa()" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
            $arrComandos[] = '<button type="button" accesskey="X" id="btnExportarExcel" onclick="exportarExcel()" class="infraButton">E<span class="infraTeclaAtalho">x</span>portar Excel </button>';
            $arrComandos[] = '<button type="button" onclick="limparFiltros()" accesskey="L" id="btnLimpar" value="Limpar Critérios" class="infraButton"><span class="infraTeclaAtalho">L</span>impar Critérios</button>';
            $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $captionTabela = "Reincidências Específicas";

            if(count($_POST)){
                if(!empty($_POST['txtDtCorte'])){
                    $filtroDataCorte = $_POST['txtDtCorte'];
                    $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS,$filtroDataCorte);
                    $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
                    $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);
                }

                if(!empty($_POST['hdnInteressados'])){
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

                    if(count($arrCpfContato) && count($arrCnpjContato)){
                        $objMdLitDecisaoDTO->adicionarCriterio(array('CpfContato', 'CnpjContato'),array(InfraDTO::$OPER_IN,InfraDTO::$OPER_IN), array($arrCpfContato, $arrCnpjContato), InfraDTO::$OPER_LOGICO_OR);
                    }elseif(count($arrCpfContato)){
                        $objMdLitDecisaoDTO->setStrCpfContato($arrCpfContato, InfraDTO::$OPER_IN);
                    }elseif(count($arrCnpjContato)){
                        $objMdLitDecisaoDTO->setStrCnpjContato($arrCnpjContato, InfraDTO::$OPER_IN);
                    }

                    if(count($arrCpfContato) == 0 && count($arrCnpjContato) == 0){
                        $objMdLitDecisaoDTO->setStrCnpjContato(null);
                        $objMdLitDecisaoDTO->setStrCpfContato(null);
                        $msgInicializacao = "Os interessados selecionados não possui CPNJ/CPF";
                    }
                }
                if(!empty($_POST['hdnDispositivo']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO){
                    $hdnDispositivo = $_POST['hdnDispositivo'];
                    $arrDispositivos = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnDispositivo']);
                    $arrIdDispositivos = InfraArray::simplificarArr($arrDispositivos, 0);

                    $objMdLitDecisaoDTO->setNumIdDispositivoNormativoMdLitRelDisNorConCtr($arrIdDispositivos, InfraDTO::$OPER_IN);
                }

                if(!empty($_POST['hdnConduta']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$CONDUTA){
                    $hdnConduta = $_POST['hdnConduta'];
                    $arrConduta = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnConduta']);
                    $arrIdConduta = InfraArray::simplificarArr($arrConduta, 0);

                    $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($arrIdConduta, InfraDTO::$OPER_IN);
                }
                if(!empty($_POST['hdnDispositivoConduta']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){
                    $hdnConduta = $_POST['hdnConduta'];
                    $hdnDispositivo = $_POST['hdnDispositivo'];
                    $arrDispositivoConduta = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnDispositivoConduta']);

                    $arrNomeCriterioDispositivoNormativo = array();
                    foreach ($arrDispositivoConduta as $key=>$dispositivoConduta){
                        $nomeCriterio ='criterioDispositivoConduta'.$key;
                        $arrNomeCriterioDispositivoNormativo[] = $nomeCriterio;
                        $objMdLitDecisaoDTO->adicionarCriterio(array('IdCondutaMdLitRelDisNorConCtr', 'IdDispositivoNormativoMdLitRelDisNorConCtr'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL), array($dispositivoConduta[1], $dispositivoConduta[0]), InfraDTO::$OPER_LOGICO_AND, $nomeCriterio);
                    }
                    if(count($arrNomeCriterioDispositivoNormativo) > 1) {
                        $objMdLitDecisaoDTO->agruparCriterios($arrNomeCriterioDispositivoNormativo, array_fill(0, count($arrNomeCriterioDispositivoNormativo) - 1, InfraDTO::$OPER_LOGICO_OR));
                    }

                }

                if(!empty($_POST['selCondutaEspecifica']) && $_POST['selCondutaEspecifica'] !='null'){
                    $selConduta = $_POST['selCondutaEspecifica'];
                    $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($_POST['selCondutaEspecifica']);
                }

                if(!empty($_POST['selDispositivoEspecifica']) && $_POST['selDispositivoEspecifica'] !='null'){
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
            $arrComandos[] = '<button type="button" accesskey="C" name="sbmFechar" id="sbmFechar"  onclick="window.close();" value="Fechar" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

            //buscando o contato do processo
            $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
            $objMdLitDadoInteressadoDTO->retNumIdMdLitDadoInteressado();
            $objMdLitDadoInteressadoDTO->retDblCnpjContatoParticipante();
            $objMdLitDadoInteressadoDTO->retDblCpfContatoParticipante();
            $objMdLitDadoInteressadoDTO->setDblControleIdProcedimento($_GET['id_procedimento']);

            $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
            $arrObjMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->listar($objMdLitDadoInteressadoDTO);

            $arrCnpjContato = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'CnpjContatoParticipante'), null);
            $arrCpfContato = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'CpfContatoParticipante'), null);

            if(count($arrCpfContato) && count($arrCnpjContato)){
                $objMdLitDecisaoDTO->adicionarCriterio(array('CpfContato', 'CnpjContato'),array(InfraDTO::$OPER_IN,InfraDTO::$OPER_IN), array($arrCpfContato, $arrCnpjContato), InfraDTO::$OPER_LOGICO_OR);
            }elseif(count($arrCpfContato)){
                $objMdLitDecisaoDTO->setStrCpfContato($arrCpfContato, InfraDTO::$OPER_IN);
            }elseif(count($arrCnpjContato)){
                $objMdLitDecisaoDTO->setStrCnpjContato($arrCnpjContato, InfraDTO::$OPER_IN);
            }

            //buscando a conduta do controle litigioso
            $objMdLitRelDispositivoNormativoCondutaControleDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
            $objMdLitRelDispositivoNormativoCondutaControleDTO->retTodos(false);
            $objMdLitRelDispositivoNormativoCondutaControleDTO->setNumIdControleLitigioso($_GET['id_md_lit_controle']);

            $objMdLitRelDispositivoNormativoCondutaControleRN = new MdLitRelDispositivoNormativoCondutaControleRN();
            $arrObjMdLitRelDispositivoNormativoCondutaControleDTO = $objMdLitRelDispositivoNormativoCondutaControleRN->listar($objMdLitRelDispositivoNormativoCondutaControleDTO);

            $objMdLitReincidenAntecedenRN->adicionarFiltroInfracaoMesmaNatureza($objMdLitReincidenAntecedenDTO,$objMdLitDecisaoDTO, $arrObjMdLitRelDispositivoNormativoCondutaControleDTO);

            //adicionando os filtros
            $filtroDataCorte = MdLitRelatorioReincidenteAntecedenteINT::dataAntigaInfracao($arrObjMdLitRelDispositivoNormativoCondutaControleDTO);
            $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS,$filtroDataCorte);
            $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
            $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);

            if(!empty($_POST['selCondutaEspecifica']) && $_POST['selCondutaEspecifica'] !='null'){
                $selConduta = $_POST['selCondutaEspecifica'];
                $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($_POST['selCondutaEspecifica']);
            }

            if(!empty($_POST['selDispositivoEspecifica']) && $_POST['selDispositivoEspecifica'] !='null'){
                $selDispositivo = $_POST['selDispositivoEspecifica'];
                $objMdLitDecisaoDTO->setNumIdDispositivoNormativoMdLitRelDisNorConCtr($_POST['selDispositivoEspecifica']);
            }

            $mostrarFiltro = false;
            //colocando a pagina sem menu e titulo inicial
            PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
            $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS,$filtroDataCorte);
            $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
            $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);
            $objMdLitDecisaoRN = new MdLitDecisaoRN();
            $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listarRelatorio($objMdLitDecisaoDTO);

            $strUrlFormularioRelatorio = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&id_md_lit_controle='.$_GET['id_md_lit_controle']);
            break;
        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }
    //Fim teste
    $numRegistros = count($arrObjMdLitDecisaoDTO); // DTO Combo

    $strComboConduta = MdLitDecisaoINT::montarSelectCondutaPorArrMdLitDecisao('null', 'Todos', $selConduta, $arrObjMdLitDecisaoDTO);
    $strComboDispositivo = MdLitDecisaoINT::montarSelectDispositivoPorArrMdLitDecisao('null', 'Todos', $selDispositivo, $arrObjMdLitDecisaoDTO);
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}


PaginaSEI::getInstance()->processarPaginacao($objMdLitDecisaoDTO);
$strResultado = '';
//Tabela de resultado.
if ($numRegistros > 0) {
    $strResultado .= '<table width="99%" id="tableRelatorioReincidencia" class="infraTable" summary="Reincidência Específicas">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela($captionTabela, $numRegistros);
    $strResultado .= '</caption>';

    if($_GET['acao'] =='md_lit_modal_relatorio_reincidencia'){
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    }

    $strResultado .= '<th class="infraTh" style="width:130px;min-width: 130px" >';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Processo', 'ProtocoloFormatadoProcedimento', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh">';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Interessado', 'NomeContato', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" style="width:120px;min-width: 120px">';
    $strResultado .= 'CNPJ/CPF';
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" style="width: 20%" >';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Norma', 'Norma', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    $strResultado .= '<th class="infraTh" style="width:90px;min-width: 90px" >';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Dispositivo', 'Dispositivo', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';

    if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() != MdLitReincidenAntecedenRN::$DISPOSITIVO){
        $strResultado .= '<th class="infraTh" style="width: 20%" >';
        $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Conduta', 'Conduta', $arrObjMdLitDecisaoDTO);
        $strResultado .= '</th>';
    }

    $strResultado .= '<th class="infraTh" style="width:120px;min-width: 120px" >';
    $strResultado .= PaginaSEI::getInstance()->getThOrdenacao($objMdLitDecisaoDTO, 'Data do Trânsito em Julgado', 'TransitoJulgado', $arrObjMdLitDecisaoDTO);
    $strResultado .= '</th>';


    $strCssTr = '<tr class="infraTrEscura">';

    $arrNorma = array();


    for ($i = 0; $i < $numRegistros; $i++) {

        $strCssTr = $strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        if($_GET['acao'] =='md_lit_modal_relatorio_reincidencia') {
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
        if(!empty($arrObjMdLitDecisaoDTO[$i]->getStrCpfContato())){ //Mudar

            $strResultado .= PaginaSEI::tratarHTML(InfraUtil::formatarCpfCnpj($arrObjMdLitDecisaoDTO[$i]->getStrCpfContato()));//CNFJ/CPF

        }else{

            $strResultado .= PaginaSEI::tratarHTML(InfraUtil::formatarCpfCnpj($arrObjMdLitDecisaoDTO[$i]->getStrCnpjContato()));//CNFJ/CPF

        }
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrNorma());
        $strResultado .= '</td>';

        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrDispositivo());
        $strResultado .= '</td>';

        if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() != MdLitReincidenAntecedenRN::$DISPOSITIVO) {
            $strResultado .= '<td  style="word-break:break-all">';
            $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->getStrConduta());
            $strResultado .= '</td>';
        }


        $strResultado .= '<td  style="word-break:break-all">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitDecisaoDTO[$i]->isSetDtaTransitoJulgado()?$arrObjMdLitDecisaoDTO[$i]->getDtaTransitoJulgado():null); //Data do Transito em julgado
        $strResultado .= '</td>';
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

$strUrlExcel              = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_relatorio_reincidencia_exp_excel&acao_origem=' . $_GET['acao'].'&excel=1'));;

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

$objEditorRN = new EditorRN();
echo $objEditorRN->montarCssEditor(0);
?>
strong, b {font-weight: bold;}

/* interessados */
#lblInteressados {position:absolute;left:0%;top:0%;}
#txtInteressados {position:absolute;left:0%;top:14%;width:50%;}
#selInteressados {position:absolute;left:0%;top:33%;width:75%;}

div#divOpcoesInteressados {position:absolute;left:76%;top:33%;}

#lblDtCorte {position:absolute;left:0%;top:0%;width:50%;}
#txtDtCorte {position:absolute;left:0%;top:36%;width:10%;}
#imgCalDthCorte{position:absolute;left:11%;top:36%;}

/* dispositivo */
#lblDispositivo {position:absolute;left:0%;top:0%;}
#txtDispositivo {position:absolute;left:0%;top:14%;width:50%;}
#selDispositivo {position:absolute;left:0%;top:33%;width:75%;}
div#divOpcoesDispositivo {position:absolute;left:76%;top:33%;}

/* conduta */
#lblConduta {position:absolute;left:0%;top:0%;}
#txtConduta {position:absolute;left:0%;top:14%;width:50%;}
#selConduta {position:absolute;left:0%;top:33%;width:75%;}
div#divOpcoesConduta {position:absolute;left:76%;top:33%;}

/* conduta Especifica */
#lblCondutaEspecifica {position:absolute;left:0%;top:0%;}
#selCondutaEspecifica {position:absolute;left:0%;top:37%;width:75%;}
#divCondutaResultado {height:5em;width: 30%;display: inline-block;}

/* Dispositivo Especifica */
#lblDispositivoEspecifica {position:absolute;left:0%;top:0%;}
#selDispositivoEspecifica {position:absolute;left:0%;top:37%;width:75%;}
#divDispositivoResultado {height: 5em;width: 30%;display: inline-block;}

<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();?>
<?php if(0){ ?><script><?php } ?>

    var objLupaInteressados = null;
    var objAutoCompletarInteressados = null;

    var objLupaDispositivos = null;
    var objAutoCompletarDispositivos = null;

    var objLupaConduta = null;
    var objAutoCompletarConduta = null;

    function submitFormPesquisa(){

        if(validarPesquisaReincidencia()){
            document.getElementById('frmTipoControleLitigiosoLista').submit();
        }
    }
    function validarPesquisaReincidencia(){
        if(objLupaInteressados != null && objLupaInteressados.hdn.value == ''){
            alert("Informe ao menos um interessado.");
            return false;
        }

        if(document.getElementById('txtDtCorte') != null && document.getElementById('txtDtCorte').value == ''){
            alert("Informe a data de corte.");
            return  false;
        }

        if(document.getElementById('hdnDispositivo') != null && document.getElementById('hdnDispositivo').value == ''){
            alert("Informe o Dispositivo Normativo.");
            return  false;
        }

        if(document.getElementById('hdnConduta') != null && document.getElementById('hdnConduta').value == ''){
            alert("Informe a Conduta.");
            return  false;
        }
        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){ ?>
            verificarDispositivoConduta();
            if(document.getElementById('hdnDispositivoConduta') != null && document.getElementById('hdnDispositivoConduta').value == ''){
                alert("Nos filtros existe Dispositivo Normativo sem ter a seleção de pelo menos uma Conduta correspondente. Conforme as regras de definição de infração de mesma natureza, é necessário selecionar Dispositivo Normativo e Conduta correspondentes.");
                return  false;
            }
        <?php } ?>

        return true;
    }

    function inicializar(){
        <?php if($msgInicializacao){?>
        alert("<?=$msgInicializacao?>");
        <?php }?>
        <?php if($mostrarFiltro){ ?>
        objAutoCompletarInteressados = new infraAjaxAutoCompletar('hdnIdInteressados','txtInteressados','<?=$strLinkAjaxInteressados?>');
        objAutoCompletarInteressados.limparCampo = true;
        objAutoCompletarInteressados.tamanhoMinimo = 3;   
        objAutoCompletarInteressados.prepararExecucao = function(){
            return 'palavras_pesquisa='+document.getElementById('txtInteressados').value;
        };

        objAutoCompletarInteressados.processarResultado = function(id,descricao,complemento){
            if (id!=''){
                var options = document.getElementById('selInteressados').options;

                for(var i=0;i < options.length;i++){
                    if (options[i].value == id){
                        alert('Interessado já consta na lista.');
                        break;
                    }
                }

                if (i==options.length){

                    for(i=0;i < options.length;i++){
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selInteressados'), descricao ,id);

                    objLupaInteressados.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtInteressados').value = '';
                document.getElementById('txtInteressados').focus();

            }
        };
        objLupaInteressados = new infraLupaSelect('selInteressados','hdnInteressados','<?=$strLinkInteressados?>');

        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO || $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){ ?>
            //inicio da configuração do dispositivo
            objAutoCompletarDispositivos = new infraAjaxAutoCompletar('hdnIdDispositivo','txtDispositivo','<?=$strLinkAjaxDispositivos?>');
            objAutoCompletarDispositivos.limparCampo = false;

            objAutoCompletarDispositivos.prepararExecucao = function(){
                return 'palavras_pesquisa='+document.getElementById('txtDispositivo').value;
            };

            objAutoCompletarDispositivos.processarResultado = function(id,descricao,complemento){

                descricao = $("<pre>").html(descricao).text();
                if (id!=''){
                    var options = document.getElementById('selDispositivo').options;

                    for(var i=0;i < options.length;i++){
                        if (options[i].value == id){
                            alert('Dispositivo Normativo já consta na lista.');
                            break;
                        }
                    }

                    if (i==options.length){

                        for(i=0;i < options.length;i++){
                            options[i].selected = false;
                        }

                        opt = infraSelectAdicionarOption(document.getElementById('selDispositivo'), descricao ,id);

                        objLupaDispositivos.atualizar();

                        opt.selected = true;
                    }

                    document.getElementById('txtDispositivo').value = '';
                    document.getElementById('txtDispositivo').focus();

                }
            };

            objLupaDispositivos = new infraLupaSelect('selDispositivo','hdnDispositivo','<?=$strLinkDispositivos?>');
        <?php } ?>


        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$CONDUTA){ ?>
            //inicio da configuração da conduta
            objAutoCompletarConduta = new infraAjaxAutoCompletar('hdnIdConduta','txtConduta','<?=$strLinkAjaxConduta?>');
            objAutoCompletarConduta.limparCampo = false;
            objAutoCompletarConduta.tamanhoMinimo = 3;
            objAutoCompletarConduta.prepararExecucao = function(){
                return 'palavras_pesquisa='+document.getElementById('txtConduta').value;
            };

            objAutoCompletarConduta.processarResultado = function(id,descricao,complemento){

                descricao = $("<pre>").html(descricao).text();
                if (id!=''){
                    var options = document.getElementById('selConduta').options;

                    for(var i=0;i < options.length;i++){
                        if (options[i].value == id){
                            alert('Conduta já consta na lista.');
                            break;
                        }
                    }

                    if (i==options.length){

                        for(i=0;i < options.length;i++){
                            options[i].selected = false;
                        }

                        opt = infraSelectAdicionarOption(document.getElementById('selConduta'), descricao ,id);

                        objLupaConduta.atualizar();

                        opt.selected = true;
                    }

                    document.getElementById('txtConduta').value = '';
                    document.getElementById('txtConduta').focus();

                }
            };

            objLupaConduta = new infraLupaSelect('selConduta','hdnConduta','<?=$strLinkConduta?>');


        <?php }?>

        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){ ?>
        //inicio da configuração se estiver parametrizado conduta e dispositivo aonde a conduta depende dos dispositivos selecionados
        objAutoCompletarConduta = new infraAjaxAutoCompletar('hdnIdConduta','txtConduta','<?=$strLinkAjaxConduta?>');
        objAutoCompletarConduta.limparCampo = false;
        objAutoCompletarConduta.prepararExecucao = function(){
            if(document.getElementById('hdnDispositivo').value != ''){
                var arrSelectDispositivo = getSelectValues(document.getElementById('selDispositivo'));
                return 'palavras_pesquisa='+document.getElementById('txtConduta').value+'&'+parameterizeArray('id_dispositivo',arrSelectDispositivo);
            }
            alert('Informe ao menos um Dispositivo Normativo');
            return false;
        };

        objAutoCompletarConduta.processarResultado = function(id,descricao,complemento){

            descricao = $("<pre>").html(descricao).text();
            if (id!=''){
                var options = document.getElementById('selConduta').options;

                for(var i=0;i < options.length;i++){
                    if (options[i].value == id){
                        alert('Conduta já consta na lista.');
                        break;
                    }
                }

                if (i==options.length){

                    for(i=0;i < options.length;i++){
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selConduta'), descricao ,id);

                    objLupaConduta.atualizar();

                    opt.selected = true;
                }

                document.getElementById('txtConduta').value = '';
                document.getElementById('txtConduta').focus();

            }
        };

        objLupaConduta = new infraLupaSelect('selConduta','hdnConduta','<?=$strLinkConduta?>');
        objLupaConduta.validarSelecionar = function(){
            if(document.getElementById('hdnDispositivo').value != ''){
                objLupaConduta.id_dispositivo = getSelectValues(document.getElementById('selDispositivo'));
                return true;
            }
            alert('Informe ao menos um Dispositivo Normativo');
            return false;
        };
        objLupaDispositivos.processarRemocao = function(temp) {
            verificarDispositivoConduta();
            for(var i = 0; i < temp.length; i++) {
                var idDispositivo = temp[i].value+"±";
                if(document.getElementById('hdnDispositivoConduta').value.indexOf(idDispositivo) > -1) {
                    alert('Este Dispositivo Normativo possui Conduta selecionada no filtro abaixo. Remova primeiro a Conduta correspondente para poder remover o Dispositivo Normativo neste filtro.');
                    return false;
                }
            }
            return true;
        };


        <?php }?>

        <?php } ?>
    }
    function parameterizeArray(key, arr) {
        arr = arr.map(encodeURIComponent);
        return key+'[]=' + arr.join('&'+key+'[]=');
    }
    function getSelectValues(select) {
        var result = [];
        var options = select && select.options;
        var opt;

        for (var i=0, iLen=options.length; i<iLen; i++) {
            opt = options[i];
            result.push(opt.value || opt.text);
        }
        return result;
    }

    function limparFiltros(){
        objLupaInteressados.limpar();
        objLupaConduta.limpar();
        objLupaDispositivos.limpar();
        document.getElementById('txtDtCorte').value = '';
        document.getElementById('selCondutaEspecifica').value = 'null';
    }

    function validarFormatoData(obj) {
        var validar = infraValidarData(obj, false);
        if (!validar) {
            alert('Data Inválida!');
            obj.value = '';
        }
    }

    function exportarExcel(){
        if(validarPesquisaReincidencia()) {
            document.getElementById('divInfraAreaTela').style.height = '100%';
            var UrlAntiga = document.getElementById('frmTipoControleLitigiosoLista').action;
            var urlExcel = '<?= $strUrlExcel ?>';
            document.getElementById('frmTipoControleLitigiosoLista').action = urlExcel;
            document.getElementById('frmTipoControleLitigiosoLista').target = '_blank';
            document.getElementById('frmTipoControleLitigiosoLista').submit();


            document.getElementById('frmTipoControleLitigiosoLista').action = UrlAntiga;
            document.getElementById('frmTipoControleLitigiosoLista').removeAttribute('target');
        }
    }

    function verificarDispositivoConduta(){
        document.getElementById('hdnDispositivoConduta').value = '';
        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxAssociarDispositivoConduta ?>",
            //dataType: "json",
            dataType: "xml",
            async: false,
            data: {
                hdnDispositivo: document.getElementById('hdnDispositivo').value,
                hdnConduta: document.getElementById('hdnConduta').value
            },
            success: function (result) {
                var valDispositivoConduta = '';
                $.each($(result).find('item'), function(key, value) {
                    var idDispositivo = $(this).attr('id_dispositivo');
                    var idConduta = $(this).attr('id_conduta');

                    if(key > 0){
                        valDispositivoConduta += '¥';
                    }
                    valDispositivoConduta += idDispositivo+'±'+idConduta;
                });
                document.getElementById('hdnDispositivoConduta').value = valDispositivoConduta;
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                console.log(msgCommit);
            },
            complete: function (result) {
            }
        });
    }

    function resizeIFrameOrientacao(){
        var id = 'ifrOrientacaoHTML';
        var ifrm = document.getElementById(id);
        ifrm.style.visibility = 'hidden';
        ifrm.style.height = "10px";

        var doc = ifrm.contentDocument? ifrm.contentDocument : ifrm.contentWindow.document;
        doc = doc || document;
        var body = doc.body, html = doc.documentElement;

        var width = Math.max( body.scrollWidth, body.offsetWidth,
            html.clientWidth, html.scrollWidth, html.offsetWidth );
        ifrm.style.width='100%';

        var height = Math.max( body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight );
        ifrm.style.height=height+'px';

        ifrm.style.visibility = 'visible';
    }

<?php if(0){ ?></script><?php } ?>
<?php
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>



<form id="frmTipoControleLitigiosoLista" method="post" onsubmit="return validarPesquisaReincidencia();"
      action="<?= PaginaSEI::getInstance()->formatarXHTML($strUrlFormularioRelatorio) ?>">

    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

    ?>
    <?php if($mostrarFiltro){ ?>
    <!--  Interessados -->
    <div id="divInteressados" class="infraAreaDados" style="height:11.5em;">

        <label id="lblInteressados" for="selInteressados" accesskey="" for="txtInteressados" class="infraLabelObrigatorio">Interessado:
            <img align="top" style="height:16px; width:16px;" id="imgAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" name="ajuda" onmouseover="return infraTooltipMostrar('A pesquisa pode ser realizada digitando o nome ou CPF e CNPJ. Para que a pesquisa retorne Reincidência, é necessário que no cadastro do Interessado consta seu CPF ou CNPJ.\n \n Caso necessário, pode realizar uma pesquisa avançada clicando no ícone de lupa abaixo.');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
        </label>
        <input type="text" id="txtInteressados" name="txtInteressados" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        <select id="selInteressados" name="selInteressados" size="4" multiple="multiple" class="infraSelect">
        </select>
        <div id="divOpcoesInteressados">
            <img id="imgLupaInteressados" onclick="objLupaInteressados.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Interessado" title="Selecionar Interessado" class="infraImg" />
            <br>
            <img id="imgExcluirInteressados" onclick="objLupaInteressados.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Interessado Selecionado" title="Remover Interessado Selecionado" class="infraImg" />
        </div>
        <input type="hidden" name="hdnIdInteressados" id="hdnIdInteressados">
        <input type="hidden" name="hdnInteressados" id="hdnInteressados" value="<?= $hdnInteressado ?>">
    </div>

    <!-- Data de corte -->
    <div class="infraAreaDados" style="height: 4.7em">
        <label id="lblDtCorte" name="lblDtCorte" for="txtDtCorte" class="infraLabelObrigatorio">Data de Corte:
            <img align="top" style="height:16px; width:16px;" id="imgAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" name="ajuda" onmouseover="return infraTooltipMostrar('Quando a data de corte for informada e opção de pesquisa acionada, serão apresentados os processos com o CPF ou CNPJ dos interessados indicados e com situação conclusiva (Trânsito em Julgado).');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
        </label>
        <input type="text" id="txtDtCorte" name="txtDtCorte" onchange="return validarFormatoData(this)" onkeypress="return infraMascara(this, event,'##/##/####')"  class="infraText"
               value="<?= PaginaSEI::tratarHTML($filtroDataCorte);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal().'/calendario.gif'?>" id="imgCalDthCorte" title="Selecionar Data/Hora Inicial" alt="Selecionar Data de Corte" class="infraImg" onclick="infraCalendario('txtDtCorte',this,false,'<?=InfraData::getStrDataAtual()?>');" />
    </div>

        <!--  Dispositivo Normativo -->
        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO || $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){ ?>
            <div id="divDispositivo" class="infraAreaDados" style="height:11.5em;">

                <label id="lblDispositivo" accesskey="" for="rdFiltroDispositivo" class="infraLabelObrigatorio">Dispositivo Normativo:</label>
                <input type="text" id="txtDispositivo" name="txtDispositivo" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

                <select id="selDispositivo" name="selDispositivo" size="4" multiple="multiple" class="infraSelect">
                </select>
                <div id="divOpcoesDispositivo">
                    <img id="imgLupaDispositivo" onclick="objLupaDispositivos.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Dispositivo Normativo" title="Selecionar Dispositivo Normativo" class="infraImg" />
                    <br>
                    <img id="imgExcluirDispositivo" onclick="objLupaDispositivos.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Dispositivo Normativo Selecionado" title="Remover Dispositivo Normativo Selecionado" class="infraImg" />
                </div>

                <input type="hidden" name="hdnIdDispositivo" id="hdnIdDispositivo">
                <input type="hidden" name="hdnDispositivo" id="hdnDispositivo" value="<?= PaginaSEI::tratarHTML($hdnDispositivo) ?>">
            </div>
        <?php } ?>

        <!--  Conduta -->
        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$CONDUTA || $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){ ?>
            <div id="divConduta" class="infraAreaDados" style="height:11.5em;">

                <label id="lblConduta" accesskey="" for="rdFiltroConduta" class="infraLabelObrigatorio">Conduta:</label>
                <input type="text" id="txtConduta" name="txtConduta" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

                <select id="selConduta" name="selConduta" size="4" multiple="multiple" class="infraSelect">
                </select>
                <div id="divOpcoesConduta">
                    <img id="imgLupaConduta" onclick="objLupaConduta.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Conduta" title="Selecionar Conduta" class="infraImg" />
                    <br>
                    <img id="imgExcluirConduta" onclick="objLupaConduta.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Conduta Selecionado" title="Remover Conduta Selecionado" class="infraImg" />
                </div>
                <input type="hidden" name="hdnIdConduta" id="hdnIdConduta">
                <input type="hidden" name="hdnConduta" id="hdnConduta" value="<?= PaginaSEI::tratarHTML($hdnConduta) ?>">
            </div>
        <?php } ?>
    <?php } ?>
    <input type="hidden" name="hdnDispositivoConduta" id="hdnDispositivoConduta" value="">

    <div  id="divOrientacao">
        <iframe id=ifrOrientacaoHTML onload="resizeIFrameOrientacao()" name=ifrOrientacaoHTML style="height:100%;width:100%" frameborder="0" marginheight="0" marginwidth="0" src="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_antecedencia_reincidencia_orientacao&id_md_lit_reinciden_anteceden='.$objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden())?>"></iframe>
    </div>

    <? if ($strComboConduta){ ?>
        <div id="divDispositivoResultado" class="infraAreaDados" style="height:5em;" >
            <label id="lblDispositivoEspecifica" for="selDispositivoEspecifica" name="lblDispositivoEspecifica"
                   class="infraLabelOpcional">Dispositivo Normativo:</label>
            <select id="selDispositivoEspecifica" style="width:98%" name="selDispositivoEspecifica"
                    onchange="submitFormPesquisa()">
                <?php echo $strComboDispositivo ?>

            </select>
        </div>
        <?php if($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() != MdLitReincidenAntecedenRN::$DISPOSITIVO){ ?>
            <div id="divCondutaResultado" class="infraAreaDados" style="height:5em;" >
                <label id="lblCondutaEspecifica" for="selCondutaEspecifica" name="lblCondutaEspecifica"
                       class="infraLabelOpcional">Conduta:</label>
                <select id="selCondutaEspecifica" style="width:90%" name="selCondutaEspecifica"
                        onchange="submitFormPesquisa()">
                    <?php echo $strComboConduta ?>
                </select>
            </div>
        <?php } ?>
    <? } ?>

    <?

    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

</form>
<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>

<script>
</script>
