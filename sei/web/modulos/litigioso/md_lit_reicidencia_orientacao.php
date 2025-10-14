<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 23/04/2018
 * Time: 18:20
 */

$objReinciEditorDTO = new EditorDTO();
$objReinciEditorRN = new EditorRN();
$staReinciTipoEditor= EditorRN::obterTipoEditorSimples();

if($staReinciTipoEditor==EditorRN::$VE_CK5){
    $objReinciEditorDTO=new EditorDTO();
    $objReinciEditorDTO->setStrNomeCampo('txtOrientacoes');
    $objReinciEditorDTO->setNumTamanhoEditor(300);
    $objReinciEditorDTO->setStrSinSomenteLeitura('N');
    $objReinciEditorDTO->setStrIdElementoHtml('txtOrientacoes');
    $objReinciEditorDTO->setStrConteudoInicial(isset($orientacaoRein) ? $orientacaoRein : '');
    EditorCk5RN::montarSimples($objReinciEditorDTO);
} else {
    $objReinciEditorDTO->setStrNomeCampo('txtOrientacoes');
    $objReinciEditorDTO->setNumTamanhoEditor(300);
    $objReinciEditorDTO->setStrSinSomenteLeitura('N');
    $objReinciEditorDTO->setStrIdElementoHtml('txtOrientacoes');
    $objReinciEditorDTO = $objReinciEditorRN->montarSimples($objReinciEditorDTO);
}

if($staReinciTipoEditor==EditorRN::$VE_CK5){
    echo $objReinciEditorDTO->getStrCss();
    echo $objReinciEditorDTO->getStrJs();
} else {
    echo $objReinciEditorDTO->getStrInicializacao();
}
?>

<table id="tbOrientacao">
    <td>
         <?php
            if($staReinciTipoEditor==EditorRN::$VE_CK5){
                ?>
                    <div id="divEditoresReinci" class="infra-editor" style="visibility: visible;">
                        <?= $objReinciEditorDTO->getStrHtml(); ?>
                    </div>
                    <?php
            } else {
                ?>
                    <div id="divEditoresReinci" class="mb-0">
                        <textarea id="txaConteudo" name="txtOrientacoes" rows="10" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= isset($orientacaoRein) ? $orientacaoRein : '' ?></textarea>
                        <script type="text/javascript"> <?= $objReinciEditorDTO->getStrEditores(); ?> </script>
                    </div>
                <?php
            }
            ?>
    </td>
</table>