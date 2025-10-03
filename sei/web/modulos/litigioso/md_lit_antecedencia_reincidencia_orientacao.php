<?
/**
 * ANATEL
 *
 * 26/12/2018 - criado por ellyson.silva@cast.com.br - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(false);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
    $objMdLitReincidenAntecedenDTO->setNumIdMdLitReincidenAnteceden($_GET['id_md_lit_reinciden_anteceden']);
    $objMdLitReincidenAntecedenDTO->retTodos();

    $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
    $objMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->consultar($objMdLitReincidenAntecedenDTO);

    $objMdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
    $objMdLitRelTpDecReinAnteDTO->retNumIdRelMdLitTipoDecisao();
    $objMdLitRelTpDecReinAnteDTO->retStrNomeTipoDecisao();
    $objMdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden());

    $objMdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();
    $arrObjMdLitRelTpDecReinAnteDTO = $objMdLitRelTpDecReinAnteRN->listar($objMdLitRelTpDecReinAnteDTO);

    if (count($arrObjMdLitRelTpDecReinAnteDTO)) {
        $objMdLitReincidenAntecedenDTO->setArrObjMdLitRelTpDecReinAnteDTO($arrObjMdLitRelTpDecReinAnteDTO);
    }

    $txtOrientacoes = '';
    if ($objMdLitReincidenAntecedenDTO->getStrTipo() == MdLitReincidenAntecedenRN::$TIPO_REINCIDENCIA) {
        $txtOrientacoes = MdLitReincidenAntecedenINT::tratarOrientacaoReincidencia($objMdLitReincidenAntecedenDTO);
    } elseif ($objMdLitReincidenAntecedenDTO->getStrTipo() == MdLitReincidenAntecedenRN::$TIPO_ANTECEDENTE) {
        $txtOrientacoes = MdLitReincidenAntecedenINT::tratarOrientacaoAntecendente($objMdLitReincidenAntecedenDTO);
    }


    $objEditorRN = new EditorRN();

    PaginaSEI::getInstance()->abrirStyle();
    echo $objEditorRN->montarCssEditor(null);
    PaginaSEI::getInstance()->fecharStyle();
    echo '<div id="dv_orient">' . $txtOrientacoes . '</div>';  
    ?>
    <script type="text/javascript">
        var itPai = document.getElementById('dv_orient');
        var it = itPai.getElementsByTagName('p');
        for( i in it ){
            if ( i < it.length ) it[i].style.fontSize = ".875rem";
        }
    </script>
    
    <?php 
        die();

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
$objEditorRN = new EditorRN();
echo $objEditorRN->montarCssEditor(null);
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
?>
    <style type="text/css">
        #field1 {
            height: auto;
            width: 96%;
            margin-bottom: 11px;
        }

        .sizeFieldset {
            height: auto;
            width: 86%;
        }

        .fieldsetClear {
            border: none !important;
        }
    </style>
<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>