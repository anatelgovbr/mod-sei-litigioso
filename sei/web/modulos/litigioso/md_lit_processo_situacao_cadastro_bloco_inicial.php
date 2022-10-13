<div class="row linha">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <label class="infraLabelObrigatorio" id="lblTipoCtrlTitulo" name="lblTipoCtrlTitulo">Tipo de Controle
            Litigioso:</label>
        <label class="infraLabelOpcional" id="lblTipoCtrlDetalhe"
               name="lblTipoCtrlDetalhe"> <?php echo !is_null($objMdLitTipoControleDTO) ? $objMdLitTipoControleDTO->getStrSigla() : '' ?>

            <img onclick="modalParametrizarSituacao()" src="modulos/litigioso/imagens/svg/icone_parametrizar.svg?<?= Icone::VERSAO ?>"
                 title="Consultar Parametrização de Situações" class="infraImgEngrenagem"/>
        </label>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 text-right">
        <?php if ($diferencaEntreDias): ?>
            <div class="grid grid_5" style="margin-right: 5px;float: right; width: 350px; margin-top: 9px">
                <label class="infraLabelOpcional" id="lblTipoCtrlDetalhe" name="lblTipoCtrlDetalhe"
                       style="float: right; margin-left: 3px"> <?= $diferencaEntreDias ?> dias </label>
                <label class="infraLabelObrigatorio" id="lblTipoCtrlTitulo" name="lblTipoCtrlTitulo" style="float: right">Tempo
                    desde Intimação da Instauração:</label>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Hiddens -->
<input type="hidden" name="hdnIdDocumentoNumeroSei" id="hdnIdDocumentoNumeroSei"
       value="<?php echo $idDocNumeroSei; ?>"/>
<input type="hidden" name="hdnIdDocumento" id="hdnIdDocumento" value="<?php echo $idDocumento; ?>"/>
<input type="hidden" name="hdnIdTipoControle" id="hdnIdTipoControle" value="<?php echo $idTpControle; ?>"/>
<input type="hidden" name="hdnIdProcedimento" id="hdnIdProcedimento" value="<?php echo $idProcedimento; ?>"/>
<input type="hidden" name="hdnDtDocumento" id="hdnDtDocumento"
       value="<?php echo array_key_exists('dtDocumento', $dadosSituacao) ? $dadosSituacao['dtDocumento'] : '' ?>"/>
<input type="hidden" name="hdnIdSerieDocNumeroSei" id="hdnIdSerieDocNumeroSei"
       value="<?php echo array_key_exists('hdnIdSerieDocNumeroSei', $_POST) ? $_POST['hdnIdSerieDocNumeroSei'] : $idSerie; ?>"/>
<input type="hidden" name="hdnIsGestor" id="hdnIsGestor" value="<?php echo $strIsGestor ?>"/>
<input type="hidden" name="hdnOpenProcesso" id="hdnOpenProcesso" value="<?php echo $openProcesso ?>"