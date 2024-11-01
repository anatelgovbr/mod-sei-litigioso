<div class="row linha">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <fieldset class="infraFieldset form-control" id="fieldsetDecisao"
                  style="<?php echo $strGridDecisao == '' && !count($arrVincDecisaoSit) ? 'display:none' : '' ?>">
            <legend class="infraLegend">Decis�es</legend>
            <div class="row linha">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                    <?php if (MdLitRelatorioReincidenteAntecedenteINT::existeParametrizado(MdLitReincidenAntecedenRN::$TIPO_REINCIDENCIA)) { ?>
                        <button id="btnRelatorioReincidencia" name="btnRelatorioReincidencia" type="button"
                                value="Reincid�ncias Espec�ficas"
                                class="infraButton" onclick="abrirModalReincidenciaEspec�fica()">Reincid�ncias
                            Espec�ficas
                        </button>
                    <?php } ?>

                    <?php if (MdLitRelatorioReincidenteAntecedenteINT::existeParametrizado(MdLitReincidenAntecedenRN::$TIPO_ANTECEDENTE)) { ?>
                        <button id="btnRelatorioAntecedente" name="btnRelatorioAntecedente" type="button"
                                value="Reincid�ncias Espec�ficas"
                                class="infraButton" onclick="abrirModalAntecedente()">Antecedentes
                        </button>
                    <?php } ?>

                    <button id="btnCadastroDecisoes" name="btnCadastroDecisoes" type="button"
                            value="Cadastro de Decis�es"
                            class="infraButton" onclick="abrirModalCadastarDecisao()">Cadastro de Decis�es
                    </button>

                    <button id="btnHistoricoGeral" name="btnHistoricoGeral" type="button" value="Hist�rico Geral"
                            class="infraButton" onclick="abrirModalHistoricoDecisao()">Hist�rico Geral
                    </button>
                </div>
            </div>
            <div class="row linha" >
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <table width="99%" class="infraTable" summary="Decis�o" id="tbDecisao">
                        <caption class="infraCaption">
                            <?= PaginaSEI::getInstance()->gerarCaptionTabela('Decis�es cadastradas para este Processo', 0) ?>
                        </caption>
                        <tr>
                            <th class="infraTh" style="display: none">ID Infra��o</th>
                            <th class="infraTh" style="display: none">ID RelDispositivoNormativoCondutaControle</th>
                            <th class="infraTh" style="display: none">ID Tipo Decisao</th>
                            <th class="infraTh" style="display: none">ID Especie Decisao</th>
                            <th class="infraTh" style="display: none">multa</th>
                            <th class="infraTh" style="display: none">Valor Ressarcimento</th>
                            <th class="infraTh" style="display: none">id Obriga��o</th>
                            <th class="infraTh" style="display: none">prazo</th>
                            <th class="infraTh" style="display: none">ID Usu�rio</th>
                            <th class="infraTh" style="display: none">ID Unidade</th>
                            <th class="infraTh">Infra��o</th>
                            <th class="infraTh">�ltima Decis�o</th>
                            <th class="infraTh">Esp�cie de Decis�o</th>
                            <th class="infraTh">Data da Opera��o</th>
                            <th class="infraTh" style="display: none" align="center">Usu�rio</th>
                            <th class="infraTh" align="center">Unidade</th>
                            <th class="infraTh" style="display: none">tipo localidade</th>
                            <th class="infraTh" style="display: none">Select UF</th>
                            <th class="infraTh" style="display: none">Cadastro Parcial</th>
                        </tr>

                    </table>
                    <!-- Hidden Relacionado � tabela -->
                    <input type="hidden" name="hdnTbDecisao" id="hdnTbDecisao" value="<?php echo $strGridDecisao ?>"/>
                </div>
            </div>
        </fieldset>
    </div>
</div>