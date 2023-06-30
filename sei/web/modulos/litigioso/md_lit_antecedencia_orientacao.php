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
<table id="tbConteudoAntecedente">
    <td>
        <textarea type="text" id="txaConteudoAntecedente" rows="4" name="txtAnteOrientacoes" class="infraText"
                  tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
            <?= $orientacaoAnte; ?>
        </textarea>
        <script type="text/javascript">
            <?=$retEditor->getStrEditores();?>
        </script>
    </td>
</table>

