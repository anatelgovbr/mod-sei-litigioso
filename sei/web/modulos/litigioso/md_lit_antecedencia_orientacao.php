<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 24/04/2018
 * Time: 11:23
 */

$objEditorDTO = new EditorDTO();
$objEditorRN = new EditorRN();
$objEditorDTO->setStrNomeCampo('txaConteudoAntecedente');
$objEditorDTO->setNumTamanhoEditor(300);
$objEditorDTO->setStrSinSomenteLeitura('N');

$retEditor = $objEditorRN->montarSimples($objEditorDTO);

echo $retEditor->getStrInicializacao();

?>
<style type="text/css">

    #ConteudoAntecedente{
    position: absolute;
        width: 100%;
        top:5%

    }
    #txaConteudoAntecedente{
        height: 6.5%;

    }

</style>

<div id="ConteudoAntecedente">
<table id="tbConteudoAntecedente">
    <td>
        <div id="divEditores" style="overflow: auto;">
            <textarea type="text" id="txaConteudoAntecedente" rows="4" name="txtAnteOrientacoes" class="infraText"
                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?=$orientacaoAnte; ?>
            </textarea>
            <script type="text/javascript">
                <?=$retEditor->getStrEditores();?>
            </script>
        </div>
    </td>
</table>
</div>
