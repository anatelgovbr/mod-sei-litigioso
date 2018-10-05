<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 23/04/2018
 * Time: 18:20
 */

$objEditorDTO = new EditorDTO();
$objEditorRN = new EditorRN();
$objEditorDTO->setStrNomeCampo('txaConteudo');

$objEditorDTO->setStrSinSomenteLeitura('N');

$retEditor = $objEditorRN->montarSimples($objEditorDTO);

echo $retEditor->getStrInicializacao();

?>
<style type="text/css">

    #divOritentacao{
        width: 90%;
        margin-top: 207px;
        margin-left: 0px;


    }
    #txaConteudo{
        height: 6.5%;

    }



</style>
<div id="divOritentacao">
<table id="tbOrientacao">
    <td>
        <div id="divEditores" style="overflow: auto;">
            <textarea type="text" id="txaConteudo" rows="4" name="txtOrientacoes"
                 class="infraText" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?=$orientacaoRein; ?></textarea>
            <script type="text/javascript">
                <?=$retEditor->getStrEditores();?>
            </script>
        </div>
    </td>
</table>
</div>