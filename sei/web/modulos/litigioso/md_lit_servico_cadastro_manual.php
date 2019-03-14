<div class="clear-margin-3"></div>
<?php $strTolTipEnd = 'O Sistema correspondente ao WSDL indicado deve estar previamente cadastrado no menu Administração > Sistemas e com Serviço também cadastrado com os IPs pertinentes.' ?>
<div class="grid grid_7-8">
    <div class="grid grid_4">
        <label class="infraLabelObrigatorio" id="lblCodigo" for="txtCodigo">Código:</label>
        <input type="text" id="txtCodigo" name="txtCodigo"/>
    </div>
    <div class="clear-margin-2"></div>
    <div class="grid grid_4">
            <label class="infraLabelObrigatorio" id="lblSigla" for="txtSigla">Sigla:</label>
            <input type="text" id="txtSigla" name="txtSigla"/>
    </div>
    <div class="clear-margin-2"></div>
    <div class="grid grid_7-8">
        <label class="infraLabelObrigatorio" id="lblDescricao" for="txtDescricao">Descrição:</label>
        <input type="text" id="txtDescricao" name="txtDescricao"/>
    </div>
    <div class="clear-margin-2"></div>
    <div class="grid grid_8">
    <div class="grid grid_4-5">
        <fieldset class="infraFieldset" style="height: 79px">
            <legend class="infraLegend">Modalidades de Outorga</legend>
            <div style="padding-top: 9px;">
            <!-- Modalidades de Outorga -->
            <div>
            <input type="checkbox" name="modOutorga[]" id="chkAutorizacao" class="infraCheckbox">
            <label class="infraLabelOpcional" id="lblAutorizacao" for="chkAutorizacao">Autorização</label>
            </div>
            <!-- Consessão -->
            <div>
            <input type="checkbox" id="chkConsessao" name="modOutorga[]" class="infraCheckbox">
            <label class="infraLabelOpcional" id="lblConsessao" for="chkConsessao">Consessão</label>
            </div>
            </div>
        </fieldset>
    </div>

    <div class="grid grid_4-5">
        <fieldset class="infraFieldset">
            <legend class="infraLegend">Abragência</legend>

            <!-- Nacional -->
            <div>
                <input type="checkbox" id="chkNacional" name="modAbrangencia[]" class="infraCheckbox">
                <label class="infraLabelOpcional" id="lblNacional" for="chkNacional">Nacional</label>
            </div>

            <!-- Regional -->
            <div>
                <input type="checkbox" id="chkRegional" name="modAbrangencia[]" class="infraCheckbox">
                <label class="infraLabelOpcional" id="lblRegional" for="chkRegional">Regional</label>
            </div>

            <!-- Estadual -->
            <div>
                <input type="checkbox" id="chkEstadual" name="modAbrangencia[]" class="infraCheckbox">
                <label class="infraLabelOpcional" id="lblEstadual" for="chkEstadual">Estadual</label>
            </div>

        </fieldset>
    </div>
    </div>

</div>