<!-- Fieldset Situações -->

<div class="grid grid_13">
    <fieldset class="infraFieldset">
        <legend class="infraLegend">Situações</legend>
        <div class="clear-margin-1"></div>

        <!-- Número Sei -->
        <div class="grid grid_2">
            <label class="infraLabelObrigatorio" id="lblNumeroSei" name="lblNumeroSei" for="txtNumeroSei">Número
                SEI:</label>
            <input class="campoFieldsetSituacao bloquearTelaProcesso" onkeyup="changeNumeroSei()" type="text" id="txtNumeroSei"
                   name="txtNumeroSei" onKeyPress="return enterValidarDocumento(event)"
                   value="<?php echo array_key_exists('numeroSei', $dadosSituacao) ? $dadosSituacao['numeroSei'] : '' ?>">
        </div>

        <!-- Validar Número Sei -->
        <div class="grid grid_1">
            <label>&nbsp;</label>
            <button accesskey="v" type="button" id="btnValidarNumeroSei" name="btnValidarNumeroSei" value="Validar"
                    class="infraButton campoFieldsetSituacao bloquearTelaProcesso" onclick="validarDocumento('0')"><span
                    class="infraTeclaAtalho">V</span>alidar
            </button>
        </div>

        <!-- Tipo de Documento -->
        <div class="grid grid_3">
            <label class="infraLabelObrigatorio" id="lblTipoDocumento" name="lblTipoDocumento" for="txtTipoDocumento">Tipo
                de Documento:</label>
            <input disabled="disabled" type="text" id="txtTipoDocumento" name="txtTipoDocumento"
                   value="<?php echo array_key_exists('tipoDocumento', $dadosSituacao) ? $dadosSituacao['tipoDocumento'] : '' ?>">
        </div>

        <!-- Data de Documento -->
        <div class="grid grid_2-5">
            <label class="infraLabelObrigatorio" id="lblDataDocumento" name="lblDataDocumento" for="txtDataDocumento">Data
                do Documento:</label>
            <input disabled="disabled" type="text" id="txtDataDocumento" name="txtDataDocumento"
                   value="<?php echo array_key_exists('dtDocumento', $dadosSituacao) ? $dadosSituacao['dtDocumento'] : '' ?>">
        </div>

        <div class="clear-margin-2"></div>

        <!-- Fases -->
        <div class="grid grid_3">
            <label class="infraLabelObrigatorio" id="lblFases" name="lblFases" for="selFases">Fases:</label>
            <div id="divSelFasesInicial">
                <select class="campoFieldsetSituacao bloquearTelaProcesso" type="text" id="selFases" name="selFases" value="">
                    <?php echo $strSelFases; ?>
                </select>
            </div>

            <div id="divSelFasesAlteracao" style="display: none">
                <select class="campoFieldsetSituacao bloquearTelaProcesso" type="text" id="selFasesAlteracao" name="selFasesAlteracao"
                        value="">

                </select>
            </div>
        </div>

        <!-- Situações -->
        <div class="grid grid_5-5">
            <label class="infraLabelObrigatorio" id="lblSituacoes" name="lblSituacoes"
                   for="selSituacoes">Situações:</label>
            <select class="campoFieldsetSituacao bloquearTelaProcesso" type="text" onchange="changeSituacoes();" id="selSituacoes"
                    name="selSituacoes" value="">

            </select>
        </div>


        <div class="clear-margin-2"></div>

        <!-- Deposito Extra Judicial -->
        <div id="divCamposExtraJudicial" style="display: none">
            <div class="grid grid_3" id="divCheckExtraJudicial">
                <br/>
                <input class="campoFieldsetSituacao" type="checkbox" name="chkDpExtraJudicial" id="chkDpExtraJudicial"
                       onchange="checkedDpExtraJudicial(this);" value="">
                <label class="infraLabelOpcional" id="lblDpExtraJudicial" name="lblDpExtraJudicial"
                       for="chkDpExtraJudicial">Depósito Extrajudicial?</label>
            </div>

            <!-- Valor -->
            <div class="grid grid_3" id="divValor" style="margin-top: 5px">
                <label class="infraLabelObrigatorio" id="lblValor" name="lblValor" for="txtValor">Valor:</label>
                <input class="campoFieldsetSituacao" type="text" id="txtValor" name="txtValor"
                       onkeypress="return infraMascaraDinheiro(this,event,2,12);" value=""/>
            </div>

            <!--Data do Depósito -->
            <div class="grid grid_3" id="divDtDeposito" style="margin-top: 5px">
                <label class="infraLabelObrigatorio" id="lblDtDeposito" name="lblDtDeposito" for="txtDtDeposito">Data do
                    Depósito:</label>
                <input class="campoFieldsetSituacao campoData" type="text" id="txtDtDeposito" name="txtDtDeposito"
                       onkeypress="return infraMascara(this, event, '##/##/####');" value=""/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                     id="imgDtDeposito"
                     title="Selecionar Data do Depósito" alt="Selecionar Data do Depósito" class="infraImg"
                     onclick="infraCalendario('txtDtDeposito',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
            </div>
        </div>

        <!--Data da Decisão/Intimação/Defesa/Recurso -->
        <div class="grid grid_6" id="divDataTipoSituacao" style="display: none;">
            <div class="grid grid_6">
                <label class="infraLabelObrigatorio" id="lblDtDecisao" name="lblDtDecisao" for="txtDtTipoSituacao">Data
                    <span id="lblDtaTipoSituacao"></span>:</label>
            </div>

            <?php $dtCalendTipoSituacao = array_key_exists('dtDocumento', $dadosSituacao) ? $dadosSituacao['dtDocumento'] : InfraData::getStrDataAtual() ?>
            <div class="grid grid_3">
                <input class="campoFieldsetSituacao campoData" type="text" id="txtDtTipoSituacao"
                       name="txtDtTipoSituacao" onchange="return validarFormatoData(this)"
                       onkeypress="return infraMascara(this, event, '##/##/####');"
                       value="<?php echo array_key_exists('dtDocumento', $dadosSituacao) ? $dadosSituacao['dtDocumento'] : '' ?>"/>
                <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                     title="Selecionar Data"
                     alt="Selecionar Data"
                     class="infraImg"
                     onclick="infraCalendario('txtDtTipoSituacao',this,false,'<?= $dtCalendTipoSituacao ?>');"/>
            </div>
        </div>


        <div class="clear-margin-2"></div>

        <!-- Texto Situação -->
        <div class="grid grid_12" style="display:none;text-align: justify;" id="divTxtSituacao">
            <label class="infraLabelOpcional" id="txtSituacao"> </label>
        </div>

        <div class="clear-margin-1"></div>

        <div id="divsDatasBdPrescricao">
            <?php if ($dtIntercorrente != '') { ?>
                <!-- Intercorrente -->
                <div class="grid grid_4">
                    <label class="infraLabelOpcional lblIntQuinq" name="lblIntercorrenteTxt"
                           id="lblIntercorrenteTxt">Intercorrente: </label>
                    <label class="infraLabelOpcional lblIntQuinq" name="lblIntercorrenteDes"
                           id="lblIntercorrenteDes"> <?php echo $dtIntercorrente; ?> </label>

                    <a href="#" onclick="abrirModalDtIntercorrente()">
                        <img src="imagens/documentos.gif" title="Histórico de Datas de Prescrição Intercorrente"
                             alt="Histórico de Datas de Prescrição Intercorrente" class="infraImg"/>
                    </a>
                </div>
            <? } ?>

            <?php if ($dtQuinquenal != '') { ?>
                <!-- Quinquenal -->
                <div class="grid grid_4">
                    <label class="infraLabelOpcional lblIntQuinq" name="lblQuinquenalTxt"
                           id="lblQuinquenalTxt">Quinquenal: </label>
                    <label class="infraLabelOpcional lblIntQuinq" name="lblDtQuinquenalDes"
                           id="lblDtQuinquenalDes"> <?php echo $dtQuinquenal; ?> </label>
                    <a href="#" onclick="abrirModalDtQuinquenal()">
                        <img src="imagens/documentos.gif" title="Histórico de Datas de Prescrição Quinquenal"
                             alt="Histórico de Datas de Prescrição Quinquenal" class="infraImg"/>
                    </a>
                </div>
            <?php } ?>

        </div>
        <div class="clear-margin-1"></div>

        <div id="divMainPrescricao" style="">
            <div id="divRdsPrescricao" style="margin-top: 5px;display:none;">
                <!-- Não Prescrição -->
                <div class="grid grid_3">
                    <input class="campoFieldsetSituacao" onchange="changePrescricao(this, false);" type="radio"
                           id="rdNaoAlteraPrescricao" name="prescricao[]" value="0">
                    <label class="infraLabelOpcional" name="lblNaoAlteraPrescricao" id="lblNaoAlteraPrescricao"
                           for="rdNaoAlteraPrescricao">Não altera a prescrição</label>
                </div>

                <!-- Sim Prescrição -->
                <div class="grid grid_3">
                    <input class="campoFieldsetSituacao" onchange="changePrescricao(this, false);" type="radio"
                           id="rdAlteraPrescricao" name="prescricao[]" value="1">
                    <label class="infraLabelOpcional" name="lblAlteraPrescricao" id="lblAlteraPrescricao"
                           for="rdAlteraPrescricao">Altera a prescrição</label>
                </div>
            </div>

            <!-- Adicionar -->
            <div class="grid grid_1" id="divBtnAdicionar" style="display: none">
                <button type="button" onclick="addSituacao()" accesskey="a" id="btnAdicionar" name="btnAdicionar"
                        value="Adicionar" class="infraButton"><span
                        class="infraTeclaAtalho">A</span>dicionar
                </button>
            </div>

            <div class="clear-margin-1"></div>

            <div id="divsDatasPrescricao" style="display: none">

                <!-- Data Intercorrente -->
                <div id="divDatasSelectPrescricao" style="display: none">
                    <div class="grid grid_7">
                        <div class="grid grid_2">
                            <label class="infraLabelOpcional" name="lblIntercorrente"
                                   id="lblIntercorrente">Intercorrente:</label>
                        </div>

                        <div class="clear"></div>


                        <div class="grid grid_3">
                            <?php $dtCalendariInt = $dtIntercorrente != '' ? $dtIntercorrente : InfraData::getStrDataAtual() ?>
                            <input onchange="return validarFormatoData(this)" class="campoData campoFieldsetSituacao" type="text" id="txtDtIntercorrente"
                                   name="txtDtIntercorrente"
                                   onkeypress="return infraMascara(this, event, '##/##/####');"
                                   value="<?php echo $dtCalendariInt; ?>"
                                   data-valor-antigo="<?php echo $dtCalendariInt; ?>" />
                            <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                                 title="Selecionar Data Intercorrente" alt="Selecionar Data Intercorrente"
                                 class="infraImg"
                                 onclick="infraCalendario('txtDtIntercorrente',this,false,'<?= $dtCalendariInt; ?>');"/>
                        </div>

                        <div class="grid grid_3">
                            <button type="button" id="btnMaisTresAnos" name="btnMaisTresAnos"
                                    value="Hoje mais três anos"
                                    class="btnMaisAnos infraButton" campoRef="txtDtIntercorrente"
                                    onclick="somarAnosData(this, '3')">Hoje mais três anos
                            </button>
                        </div>
                    </div>

                    <div class="clear-margin-2"></div>

                    <!-- Data Quinquenal -->
                    <div class="grid grid_7">
                        <div class="grid grid_2">
                            <label class="infraLabelOpcional" name="lblIntercorrente"
                                   id="lblIntercorrente">Quinquenal:</label>
                        </div>

                        <div class="clear"></div>


                        <div class="grid grid_3">
                            <?php $dtCalendariQuin = $dtQuinquenal != '' ? $dtQuinquenal : InfraData::getStrDataAtual() ?>
                            <input onchange="return validarFormatoData(this)" class="campoData campoFieldsetSituacao" type="text" id="txtDtQuinquenal"
                                   name="txtDtQuinquenal"
                                   onkeypress="return infraMascara(this, event, '##/##/####');"
                                   value="<?php echo $dtQuinquenal; ?>"
                                   data-valor-antigo="<?php echo $dtQuinquenal; ?>" />
                            <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif"
                                 id="imgDtQuinquenal"
                                 title="Selecionar Data Quinquenal" alt="Selecionar Data Quinquenal"
                                 class="infraImg"
                                 onclick="infraCalendario('txtDtQuinquenal',this,false,'<?= $dtCalendariQuin ?>');"/>
                        </div>

                        <div class="grid grid_3">
                            <button type="button" onclick="somarAnosData(this, '5')" campoRef="txtDtQuinquenal"
                                    id="btnMaisCincoAnos" name="btnMaisCincoAnos" value="Hoje mais cinco anos"
                                    class="btnMaisAnos infraButton">Hoje mais cinco anos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear-margin-2"></div>
        </div>


        <!-- Grid Situações -->
        <div class="grid grid_12" id="divTbSituacao"
             style="<?php echo ($strGridSituacao == '') ? 'display: none' : ''; ?>">
            <table width="99%" class="infraTable" summary="Situação" id="tbSituacao">


                <caption class="infraCaption">
                    <?= PaginaSEI::getInstance()->gerarCaptionTabela('Situações', 1) ?>
                </caption>


                <tr>
                    <th class="infraTh" style="display: none;">IdMdLitProcSit</th>
                    <th class="infraTh" style="display: none;">StatusInsercao</th>
                    <th class="infraTh" style="display: none;">IdProcedimento</th>
                    <th class="infraTh" style="display: none;">IdFase</th>
                    <th class="infraTh" style="display: none;">IdSituacao</th>
                    <th class="infraTh" style="display: none;">IdUsuario</th>
                    <th class="infraTh" style="display: none;">IdUnidade</th>
                    <th class="infraTh" style="display: none;">DataIntercorrente</th>
                    <th class="infraTh" style="display: none;">DataQuinquenal</th>
                    <th class="infraTh" align="center" width="10%">Documento</th>
                    <th class="infraTh" align="center" width="10%">Tipo</th>
                    <th class="infraTh" align="center" width="8%">Data</th>
                    <th class="infraTh" align="center" width="15%">Fase</th>
                    <th class="infraTh" align="center" width="15%">Situação</th>
                    <th class="infraTh" align="center" width="8%">Data da Operação</th>
                    <th class="infraTh" align="center" width="12%">Usuário</th>
                    <th class="infraTh" align="center" width="12%">Unidade</th>
                    <th class="infraTh" style="display: none;">IdTipoSituacao</th>
                    <th class="infraTh" style="display: none;">TxtValorRec</th>
                    <th class="infraTh" style="display: none;">DataDepositoRec</th>
                    <th class="infraTh" style="display: none;">IdProcessoSituacao</th>
                    <th class="infraTh" style="display: none;">Ordem</th>
                    <th class="infraTh" style="display: none;">SinPrescricao</th>
                    <th class="infraTh" style="display: none;">NomeSituacaoLabel</th>
                    <th class="infraTh" style="display: none;">IdDocumento</th>
                    <th class="infraTh" style="display: none;">IsInstauracao</th>
                    <th class="infraTh" style="display: none;">DocFormatado</th>
                    <th class="infraTh" style="display: none;">UrlAcessoDoc</th>
                    <th class="infraTh" style="display: none;">Deposito extrajudicial</th>
                    <th class="infraTh" align="center" width="8%">Ações</th>
                </tr>

            </table>
        </div>
        <!-- Hidden Relacionado à tabela -->
        <input type="hidden" name="hdnTbSituacoes" id="hdnTbSituacoes" value="<?= $strGridSituacao; ?>"/>
        <!--<input type="hidden" name="hdnTbSituacao" id="hdnTbSituacao" value=/>

        <!-- Erro da Situação -->
        <input type="hidden" name="hdnErroSituacao" id="hdnErroSituacao" value="1"/>

        <!--Tipo Situação -->
        <input type="hidden" name="hdnStrSituacao" id="hdnStrSituacao" value=""/>

        <!--Id Situação -->
        <input type="hidden" name="hdnIdProcessoSituacao" id="hdnIdProcessoSituacao" value="0"/>

        <!-- Data da Situação Conclusiva  -->
        <input type="hidden" name="hdnDtSituacaoConclusiva" id="hdnDtSituacaoConclusiva" value="<?php echo $dtUltimaSitConclusiva ?>"/>

        <!-- Usuario -->
        <input type="hidden" name="hdnIdUsuario" id="hdnIdUsuario" value="<?php echo $dadosUndUs['idUsuario']; ?>"/>
        <input type="hidden" name="hdnNomeUsuario" id="hdnNomeUsuario"
               value='<a alt="<?php echo $dadosUndUs['nomeUsuario']; ?>" title="<?php echo $dadosUndUs['nomeUsuario']; ?>" class="ancoraSigla"> <?php echo $dadosUndUs['siglaUsuario']; ?> </a>'/>

        <!-- Unidade -->
        <input type="hidden" name="hdnIdUnidade" id="hdnIdUnidade" value="<?php echo $dadosUndUs['idUnidade']; ?>"/>
        <input type="hidden" name="hdnNomeUnidade" id="hdnNomeUnidade"
               value='<a alt="<?php echo $dadosUndUs['nomeUnidade']; ?>" title="<?php echo $dadosUndUs['nomeUnidade']; ?>" class="ancoraSigla"> <?php echo $dadosUndUs['siglaUnidade']; ?> </a>'/>

        <!-- Para Situação Recursal -->
        <input type="hidden" name="hdnVlDepExtraJud" id="hdnVlDepExtraJud" value=""/>
        <input type="hidden" name="hdnDtDepExtraJud" id="hdnDtDepExtraJud" value=""/>

        <!-- Data da Situação Anterior -->
        <input type="hidden" name="hdnDtUltimaSituacao" id="hdnDtUltimaSituacao"
               value="<?php echo $dtUltimaSituacao; ?>"/>

        <!-- Data da Menor Data (Seja do histórico ou a cadastrada para primeira Situacao -->
        <input type="hidden" name="hdnDtPrimSitInter" id="hdnDtPrimSitInter"
               value="<?php echo $dtMenorIntercorret; ?>"/>
        <input type="hidden" name="hdnDtPrimSitQuiq" id="hdnDtPrimSitQuiq" value="<?php echo $dtMenorQuinquenal; ?>"/>

        <!-- Sin Instauração -->
        <input type="hidden" name="hdnSinInstauracao" id="hdnSinInstauracao"
               value="<?php echo $isIntInstauracao ? '1' : '0'; ?>"/>
        <input type="hidden" name="hdnSinInstauracaoAlt" id="hdnSinInstauracaoAlt" value=""/>

        <!-- Situações que possuem decisão -->
        <input type="hidden" name="hdnSituacaoIsDecisao" id="hdnSituacaoIsDecisao"
               value='<?php echo count($arrVincDecisaoSit) > 0 ? json_encode($arrVincDecisaoSit, JSON_FORCE_OBJECT) : "" ?>'/>

        <!-- Hidden para guardar a ordem que está sendo alterada -->
        <input type="hidden" name="hdnOrdemAtual" id="hdnOrdemAtual" value=''/>

        <!-- Id Main da Tabela -->
        <input type="hidden" name="hdnIdMainTabela" id="hdnIdMainTabela" value="0"/>

        <!-- Campo para guardar o id do documento que está sendo alterado no momento -->
        <input type="hidden" name="hdnIdDocumentoAlterado" id="hdnIdDocumentoAlterado" value=""/>

        <!-- Campo para guardar a URL do documento validada -->
        <input type="hidden" name="hdnUrlDocumento" id="hdnUrlDocumento" value="<?php echo array_key_exists('urlValidada', $dadosSituacao) ? $dadosSituacao['urlValidada'] : '' ?>"/>

        <!-- Campo para guardar o tipo de documento com o número (titulo do link no documento )-->
        <input type="hidden" name="hdnTituloDoc" id="hdnTituloDoc" value="<?php echo array_key_exists('tituloDoc', $dadosSituacao) ? $dadosSituacao['tituloDoc'] : '' ?>"/>

        <!-- Campo que guarda 0 para processos que não possuirem decisões e 1 para processos que possuirem -->
        <input type="hidden" name="hdnProcessoSitIsDecisao" id="hdnProcessoSitIsDecisao" value="<?php echo empty($arrVincDecisaoSit) ? '0' : '1'; ?>"/>

    </fieldset>


</div>