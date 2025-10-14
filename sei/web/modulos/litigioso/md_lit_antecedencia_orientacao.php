<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 24/04/2018
 * Time: 11:23
 */

$staTipoEditor= EditorRN::obterTipoEditorSimples();

$objEditorDTO = new EditorDTO();
$objEditorRN = new EditorRN();

if($staTipoEditor==EditorRN::$VE_CK5){
    $objEditorDTO=new EditorDTO();
    $objEditorDTO->setStrNomeCampo('txtAnteOrientacoes');
    $objEditorDTO->setNumTamanhoEditor(300);
    $objEditorDTO->setStrSinSomenteLeitura('N');
    $objEditorDTO->setStrIdElementoHtml('txaConteudoAntecedente');
    
    $objEditorDTO->setStrConteudoInicial(isset($orientacaoAnte) ? $orientacaoAnte : '');
    
    EditorCk5RN::montarSimples($objEditorDTO);
} else {
    $objEditorDTO->setStrNomeCampo('txtAnteOrientacoes');
    $objEditorDTO->setNumTamanhoEditor(300);
    $objEditorDTO->setStrIdElementoHtml('txtAnteOrientacoes');
    $objEditorDTO->setStrSinSomenteLeitura('N');
    $objEditorDTO = $objEditorRN->montarSimples($objEditorDTO);
}

if($staTipoEditor==EditorRN::$VE_CK5){
    echo $objEditorDTO->getStrCss();
    //echo $objEditorDTO->getStrJs();
} else {
    echo $objEditorDTO->getStrInicializacao();
}
?>

<table id="tbConteudoAntecedente">
    <td>
         <?php
            if($staTipoEditor==EditorRN::$VE_CK5){
                ?>
                    <div id="divEditores" class="infra-editor" style="visibility: visible;">
                        <?= $objEditorDTO->getStrHtml(); ?>
                    </div>
                    <?php
            } else {
                ?>
                    <div id="divEditores" class="mb-0">
                        <textarea id="txaConteudoAntecedente" name="txtAnteOrientacoes" rows="10" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= isset($orientacaoAnte) ? $orientacaoAnte : '' ?></textarea>
                        <script type="text/javascript"> <?= $objEditorDTO->getStrEditores(); ?> </script>
                    </div>
                <?php
            }
            ?>
    </td>
</table>

