<div class="row linha">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <fieldset class="infraFieldset form-control" id="fieldsetDecisao"
                  style="<?php echo $strGridDecisao == '' && !count($arrVincDecisaoSit) ? 'display:none' : '' ?>">
            <legend class="infraLegend">Decisões</legend>
            <div class="row linha">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                    <?php if (MdLitRelatorioReincidenteAntecedenteINT::existeParametrizado(MdLitReincidenAntecedenRN::$TIPO_REINCIDENCIA)) { ?>
                        <button id="btnRelatorioReincidencia" name="btnRelatorioReincidencia" type="button"
                                value="Reincidências Específicas"
                                class="infraButton" onclick="abrirModalReincidenciaEspecífica()">Reincidências
                            Específicas
                        </button>
                    <?php } ?>

                    <?php if (MdLitRelatorioReincidenteAntecedenteINT::existeParametrizado(MdLitReincidenAntecedenRN::$TIPO_ANTECEDENTE)) { ?>
                        <button id="btnRelatorioAntecedente" name="btnRelatorioAntecedente" type="button"
                                value="Reincidências Específicas"
                                class="infraButton" onclick="abrirModalAntecedente()">Antecedentes
                        </button>
                    <?php } ?>

                    <button id="btnCadastroDecisoes" name="btnCadastroDecisoes" type="button"
                            value="Cadastro de Decisões"
                            class="infraButton" onclick="abrirModalCadastarDecisao()">Cadastro de Decisões
                    </button>

                    <button id="btnHistoricoGeral" name="btnHistoricoGeral" type="button" value="Histórico Geral"
                            class="infraButton" onclick="abrirModalHistoricoDecisao()">Histórico Geral
                    </button>
                </div>
            </div>
            <div class="row linha" >
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <table width="99%" class="infraTable" summary="Decisão" id="tbDecisao">
                        <caption class="infraCaption">
                            <?= PaginaSEI::getInstance()->gerarCaptionTabela('Decisões cadastradas para este Processo', 0) ?>
                        </caption>
                        <tr>
                            <th class="infraTh" style="display: none">ID Infração</th>
                            <th class="infraTh" style="display: none">ID RelDispositivoNormativoCondutaControle</th>
                            <th class="infraTh" style="display: none">ID Tipo Decisao</th>
                            <th class="infraTh" style="display: none">ID Especie Decisao</th>
                            <th class="infraTh" style="display: none">multa</th>
                            <th class="infraTh" style="display: none">Valor Ressarcimento</th>
                            <th class="infraTh" style="display: none">id Obrigação</th>
                            <th class="infraTh" style="display: none">prazo</th>
                            <th class="infraTh" style="display: none">ID Usuário</th>
                            <th class="infraTh" style="display: none">ID Unidade</th>
                            <th class="infraTh">Infração</th>
                            <th class="infraTh">Última Decisão</th>
                            <th class="infraTh">Espécie de Decisão</th>
                            <th class="infraTh">Data da Operação</th>
                            <th class="infraTh" align="center">Usuário</th>
                            <th class="infraTh" align="center">Unidade</th>
                            <th class="infraTh" style="display: none">tipo localidade</th>
                            <th class="infraTh" style="display: none">Select UF</th>
                            <th class="infraTh" style="display: none">Cadastro Parcial</th>
                        </tr>

                    </table>
                    <!-- Hidden Relacionado à tabela -->
                    <input type="hidden" name="hdnTbDecisao" id="hdnTbDecisao" value="<?php echo $strGridDecisao ?>"/>
                </div>
            </div>
        </fieldset>
    </div>
</div>