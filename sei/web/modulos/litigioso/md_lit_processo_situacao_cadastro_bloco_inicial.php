
<div class="grid grid_13">
<!-- Tipo de Controle Litigioso -->
  <div class="grid grid_7" style="width: auto; margin-right: auto;">
        <label class="infraLabelObrigatorio" id="lblTipoCtrlTitulo" name="lblTipoCtrlTitulo">Tipo de Controle Litigioso:</label>
        <label class="infraLabelOpcional" id="lblTipoCtrlDetalhe" name="lblTipoCtrlDetalhe"> <?php echo !is_null($objMdLitTipoControleDTO) ? $objMdLitTipoControleDTO->getStrSigla() : '' ?>
        </label>
  </div>
    <img onclick="modalParametrizarSituacao()" src="modulos/litigioso/imagens/icone-parametrizar.png" title="Consultar Parametrização de Situações" style="position:relative; top:-4px; height: 24px; " />

    <!-- Tempo desde Intimação da Instauração -->
    <?php if($diferencaEntreDias): ?>
        <div class="grid grid_5" style="margin-right: 5px;float: right;">
            <label class="infraLabelObrigatorio" id="lblTipoCtrlTitulo" name="lblTipoCtrlTitulo">Tempo desde Intimação da Instauração:</label>
            <label class="infraLabelOpcional" id="lblTipoCtrlDetalhe" name="lblTipoCtrlDetalhe"> <?= $diferencaEntreDias ?> dias </label>
        </div>
    <?php endif; ?>

 <div class="clear"></div>

</div>

<div class="clear-margin-2"></div>

<!-- Hiddens -->
<input type="hidden" name="hdnIdDocumentoNumeroSei" id="hdnIdDocumentoNumeroSei" value="<?php echo $idDocNumeroSei; ?>"/>
<input type="hidden" name="hdnIdDocumento" id="hdnIdDocumento" value="<?php echo $idDocumento; ?>"/>
<input type="hidden" name="hdnIdTipoControle" id="hdnIdTipoControle" value="<?php echo $idTpControle; ?>"/>
<input type="hidden" name="hdnIdProcedimento" id="hdnIdProcedimento" value="<?php echo $idProcedimento; ?>"/>
<input type="hidden" name="hdnDtDocumento" id="hdnDtDocumento" value="<?php echo array_key_exists('dtDocumento', $dadosSituacao) ? $dadosSituacao['dtDocumento'] : '' ?>"/>
<input type="hidden" name="hdnIdSerieDocNumeroSei" id="hdnIdSerieDocNumeroSei" value="<?php echo array_key_exists('hdnIdSerieDocNumeroSei', $_POST) ? $_POST['hdnIdSerieDocNumeroSei'] : $idSerie; ?>"/>
<input type="hidden" name="hdnIsGestor" id="hdnIsGestor" value="<?php echo $strIsGestor ?>"/>
<input type="hidden" name="hdnOpenProcesso" id="hdnOpenProcesso" value="<?php echo $openProcesso  ?>"