<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 07/05/2018
 * Time: 15:14
 */

switch($_GET['acao']){
    case 'md_lit_motivo_excluir':
        try{

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdLitMotivoDTO = array();

            //Verifica se o motivo esta sendo utilizado por algum processo
            $objMdLitRelControleMotivoRN = new MdLitRelControleMotivoRN();
            $objMdLitRelControleMotivoRN->verificarVinculoMotivo($arrStrIds);

            $objMdLitRelTpControlMotiRN = new MdLitRelTpControlMotiRN();
            $objMdLitRelTpControlMotiRN->verificarVinculoMotivo($arrStrIds);


            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdLitMotivoDTO = new MdLitMotivoDTO();
                $objMdLitMotivoDTO->setNumIdMdLitMotivo($arrStrIds[$i]);
                $arrObjMdLitMotivoDTO[] = $objMdLitMotivoDTO;
            }

            $objMdLitMotivoRN = new MdLitMotivoRN();
            $objMdLitMotivoRN->excluir($arrObjMdLitMotivoDTO);
            PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

        }catch(Exception $e){
            PaginaSEI::getInstance()->processarExcecao($e);
        }

        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;


    case 'md_lit_motivo_desativar':
        try{
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $arrObjMdLitMotivoDTO = array();

            //Verifica se o motivo esta sendo utilizado por algum processo
//            $objMdLitRelControleMotivoRN = new MdLitRelControleMotivoRN();
//            $objMdLitRelControleMotivoRN->verificarVinculoMotivo($arrStrIds);
//
//            $objMdLitRelTpControlMotiRN = new MdLitRelTpControlMotiRN();
//            $objMdLitRelTpControlMotiRN->verificarVinculoMotivo($arrStrIds);

            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdLitMotivoDTO = new MdLitMotivoDTO();
                $objMdLitMotivoDTO->setNumIdMdLitMotivo($arrStrIds[$i]);
                $arrObjMdLitMotivoDTO[] = $objMdLitMotivoDTO;
            }

            $objMdLitMotivoRN = new MdLitMotivoRN();
            $objMdLitMotivoRN->desativar($arrObjMdLitMotivoDTO);
            PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

        }catch(Exception $e){
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;

    case 'md_lit_motivo_reativar':
        $strTitulo = 'Reativar motivos';
        if ($_GET['acao_confirmada']=='sim'){
            try{
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjMdLitMotivoDTO = array();
                for ($i=0;$i<count($arrStrIds);$i++){
                    $objMdLitMotivoDTO = new MdLitMotivoDTO();
                    $objMdLitMotivoDTO->setNumIdMdLitMotivo($arrStrIds[$i]);
                    $arrObjMdLitMotivoDTO[] = $objMdLitMotivoDTO;
                }
                $objMdLitMotivoRN = new MdLitMotivoRN();
                $objMdLitMotivoRN->reativar($arrObjMdLitMotivoDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
            die;
        }
        break;


    case 'md_lit_motivo_selecionar':
        $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Motivo para Instauração','Selecionar Motivos para Instauração');

        //Se cadastrou alguem
        if ($_GET['acao_origem']=='md_lit_motivo_cadastrar'){
            if (isset($_GET['id_md_lit_motivo'])){
                PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_md_lit_motivo']);
            }
        }
        break;

    case 'md_lit_motivo_listar':
        $strTitulo = 'Lista de Motivos para Instauração';
        $bolAcaoImprimir = true;
        break;

    default:
        throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
}



$arrComandos = array();

// Verifica se foi chamdo da tela controle litigioso
$idTipoControle= isset($_GET['idTipoControle'])?$_GET['idTipoControle']: $_POST['hdnTpControle'];
$arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" name="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
if ($_GET['acao'] == 'md_lit_motivo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
}
if ($_GET['acao'] == 'md_lit_motivo_listar' || ($_GET['acao'] == 'md_lit_motivo_selecionar' && $idTipoControle=='')){
    // verifica se esta sendo chamado a tela selecionar do controle litigioso

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_cadastrar');
    //$arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';
    if ($bolAcaoCadastrar){
        $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_motivo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
    if($bolAcaoImprimir){
        $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

}


$objMdLitMotivoDTO = new MdLitMotivoDTO();

$txtDescricao = PaginaSEI::getInstance()->recuperarCampo('txtDescricao');

if($txtDescricao != ''){
    $objMdLitMotivoDTO->setStrDescricao('%'.$txtDescricao.'%',InfraDTO::$OPER_LIKE);
}

$objMdLitMotivoDTO->retStrDescricao();
$objMdLitMotivoDTO->retNumIdMdLitMotivo();
//$objMdLitMotivoDTO->retStrDescricao();

if ($_GET['acao'] == 'md_lit_motivo_reativar'){
    //Lista somente inativos
    $objMdLitMotivoDTO->setBolExclusaoLogica(false);
    $objMdLitMotivoDTO->setStrSinAtivo('N');
}

PaginaSEI::getInstance()->prepararOrdenacao($objMdLitMotivoDTO, 'IdMdLitMotivo', InfraDTO::$TIPO_ORDENACAO_ASC);
//PaginaSEI::getInstance()->prepararPaginacao($objMdLitMotivoDTO);

$objMdLitMotivoRN = new MdLitMotivoRN();
$objMdLitMotivoDTO->retStrSinAtivo();

if($_GET['acao'] == 'md_lit_motivo_selecionar'){
    $objMdLitMotivoDTO->setBolExclusaoLogica(true);

    // Retorna somentes os motivos relacionados com o tipo de controle solicitado
    if($idTipoControle >0){

        $mdLitRelTpControlMotiDTO = new MdLitRelTpControlMotiDTO();
        $mdLitRelTpControlMotiRN = new MdLitRelTpControlMotiRN();
        $mdLitRelTpControlMotiDTO->setNumIdMdLitTipoControle($idTipoControle);
        $mdLitRelTpControlMotiDTO->retTodos();
        $mdLitRelTpControlMoti = $mdLitRelTpControlMotiRN->listar($mdLitRelTpControlMotiDTO);

        for($i =0 ; $i < count($mdLitRelTpControlMoti) ; $i++){
            $arrIds[]=$mdLitRelTpControlMoti[$i]->getNumIdMdLitMotivo();
        }
        if(count($arrIds)>0) {
            $objMdLitMotivoDTO->setNumIdMdLitMotivo($arrIds, InfraDTO::$OPER_IN);
        }else{
            $objMdLitMotivoDTO->setNumIdMdLitMotivo(0) ;
        }
    }

}else{
    $objMdLitMotivoDTO->setBolExclusaoLogica(false);
}
$arrObjMdLitMotivoDTO = $objMdLitMotivoRN->listar($objMdLitMotivoDTO);

//PaginaSEI::getInstance()->processarPaginacao($objMdLitMotivoDTO);
$numRegistros = count($arrObjMdLitMotivoDTO);

if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='md_lit_motivo_selecionar'){
        $bolAcaoReativar = false;
        $bolAcaoConsultar = false;
        $bolAcaoAlterar = false;
        $bolAcaoImprimir = false;
        $bolAcaoExcluir = false;
        $bolAcaoDesativar = false;
        $bolCheck = true;
        $bolAcaoSelecionar = true;
    }else if ($_GET['acao']=='md_lit_motivo_reativar'){
        $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_reativar');
        $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_consultar');
        $bolAcaoAlterar = false;
        $bolAcaoImprimir = true;
        //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_excluir');
        $bolAcaoDesativar = false;
    }else{

        $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_consultar');
        $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_alterar');
        $bolAcaoImprimir = true;
        //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_excluir');
        $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_desativar');
        $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_lit_motivo_reativar');

    }


    if ($bolAcaoDesativar){
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
        $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_motivo_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
        $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_motivo_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
        $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_motivo_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';
    $strCssTh='style="width:15%"';
    if ($_GET['acao']!='md_lit_motivo_reativar'){
        $strSumarioTabela = 'Tabela de motivos para Instauração.';
        $strCaptionTabela = 'motivos';
    }else{
        $strSumarioTabela = 'Tabela de motivos para Instauração Inativos.';
        $strCaptionTabela = 'motivos Inativos';
    }

    $strResultado .= '<table width="100%" class="infraTable" style="margin-top: 50px" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
        $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMdLitMotivoDTO,'Descrição','Descricao',$arrObjMdLitMotivoDTO).'</th>'."\n";
    if($bolAcaoSelecionar) {
        $strCssTh = 'style="width:10%"';
    }
    $strResultado .= '<th class="infraTh" '.$strCssTh.'>Ações</th>' . "\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){


        if ($arrObjMdLitMotivoDTO[$i]->getStrSinAtivo() == 'S') {
            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        } else {
            $strCssTr = '<tr class="trVermelha">';
        }
        $strResultado .= $strCssTr;

        if ($bolCheck){
            $strResultado .= '<td valign="top" style="vertical-align:middle;">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMdLitMotivoDTO[$i]->getNumIdMdLitMotivo(),$arrObjMdLitMotivoDTO[$i]->getStrDescricao()).'</td>';
        }
        $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMdLitMotivoDTO[$i]->getStrDescricao()).'</td>';
        $strResultado .= '<td align="center">';

        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMdLitMotivoDTO[$i]->getNumIdMdLitMotivo());

        if ($bolAcaoConsultar){
            $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_motivo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_lit_motivo='.$arrObjMdLitMotivoDTO[$i]->getNumIdMdLitMotivo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/consultar.svg?'.Icone::VERSAO.'" title="Consultar motivo" alt="Consultar motivo" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoAlterar){
            $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_motivo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_md_lit_motivo='.$arrObjMdLitMotivoDTO[$i]->getNumIdMdLitMotivo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/alterar.svg?'.Icone::VERSAO.'" title="Alterar motivo" alt="Alterar motivo" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
            $strId = $arrObjMdLitMotivoDTO[$i]->getNumIdMdLitMotivo();
            $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdLitMotivoDTO[$i]->getStrDescricao());
        }

        if ($bolAcaoDesativar && $arrObjMdLitMotivoDTO[$i]->getStrSinAtivo()=='S'){

            $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/desativar.svg?'.Icone::VERSAO.'" title="Desativar motivo" alt="Desativar motivo" class="infraImg" /></a>&nbsp;';
        }else if($bolAcaoReativar && $arrObjMdLitMotivoDTO[$i]->getStrSinAtivo()=='N'){
            $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/reativar.svg?'.Icone::VERSAO.'" title="Reativar motivo" alt="Reativar motivo" class="infraImg" /></a>&nbsp;';

        }



        if ($bolAcaoExcluir){
            $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgGlobal().'/excluir.svg?'.Icone::VERSAO.'" title="Excluir motivo" alt="Excluir motivo" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
}
if ($_GET['acao'] == 'md_lit_motivo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
}else{
    $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']).'\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
}
