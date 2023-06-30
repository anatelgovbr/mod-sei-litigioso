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
<table id="tbOrientacao">
    <td>
        <textarea type="text" id="txaConteudo" rows="4" name="txtOrientacoes"
                  class="infraText form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
            <?= PaginaSEI::tratarHTML($orientacaoRein); ?></textarea>
        <script type="text/javascript">
            <?=$retEditor->getStrEditores();?>
        </script>
    </td>
</table>