<!-- Fieldset Situações -->
<div class="row linha">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <fieldset class="infraFieldset form-control">
            <legend class="infraLegend">Situações</legend>
            <div class="my-3"></div>
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label class="infraLabelObrigatorio" id="lblNumeroSei" name="lblNumeroSei" for="txtNumeroSei">Número
                        SEI:</label>
                    <div class="input-group mb-3">
                        <input class="campoFieldsetSituacao bloquearTelaProcesso infraText form-control"
                               onkeyup="changeNumeroSei()" type="text"
                               id="txtNumeroSei"
                               name="txtNumeroSei" onKeyPress="return enterValidarDocumento(event)"
                               value="<?php echo array_key_exists('numeroSei', $dadosSituacao) ? $dadosSituacao['numeroSei'] : '' ?>">
                        <button accesskey="v" type="button" id="btnValidarNumeroSei" name="btnValidarNumeroSei"
                                value="Validar"
                                class="infraButton campoFieldsetSituacao bloquearTelaProcesso"
                                onclick="validarDocumento('0')"><span
                                    class="infraTeclaAtalho">V</span>alidar
                        </button>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                    <label class="infraLabelObrigatorio" id="lblTipoDocumento" name="lblTipoDocumento"
                           for="txtTipoDocumento">Tipo
                        de Documento:</label>
                    <div class="input-group mb-3">
                        <input disabled="disabled" type="text" id="txtTipoDocumento" name="txtTipoDocumento"
                               class="infraText form-control"
                               value="<?php echo array_key_exists('tipoDocumento', $dadosSituacao) ? $dadosSituacao['tipoDocumento'] : '' ?>">
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <label class="infraLabelObrigatorio" id="lblDataDocumento" name="lblDataDocumento"
                           for="txtDataDocumento">Data do Documento:</label>
                    <div class="input-group mb-3">
                        <input disabled="disabled" type="text" id="txtDataDocumento" name="txtDataDocumento"
                            class="infraText form-control"
                            value="<?php echo array_key_exists('dtDocumento', $dadosSituacao) ? $dadosSituacao['dtDocumento'] : '' ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label class="infraLabelObrigatorio" id="lblFases" name="lblFases" for="selFases">Fases:</label>
                    <div class="input-group mb-3" id="divSelFasesInicial">
                        <select class="campoFieldsetSituacao bloquearTelaProcesso infraSelect form-control" type="text"
                                id="selFases" name="selFases"
                                value="">
                            <?php echo $strSelFases; ?>
                        </select>
                    </div>
                    <div class="input-group mb-3" id="divSelFasesAlteracao" style="display: none">
                        <select class="campoFieldsetSituacao bloquearTelaProcesso infraSelect form-control"
                                type="text" id="selFasesAlteracao"
                                name="selFasesAlteracao"
                                value="">
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
                    <label class="infraLabelObrigatorio" id="lblSituacoes" name="lblSituacoes"
                           for="selSituacoes">Situações:</label>
                    <select class="campoFieldsetSituacao bloquearTelaProcesso infraSelect form-control" type="text"
                            onchange="changeSituacoes();"
                            id="selSituacoes"
                            name="selSituacoes" value="">

                    </select>
                </div>
            </div>
            <!-- Deposito Extra Judicial -->
            <div class="row" id="divCamposExtraJudicial" style="display: none;">
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 my-4" id="divExtraJudicial">
                    <label class="infraLabelOpcional" id="lblDpExtraJudicial" name="lblDpExtraJudicial"
                           for="chkDpExtraJudicial">
                        <input class="campoFieldsetSituacao infraCheckbox" type="checkbox" name="chkDpExtraJudicial"
                               id="chkDpExtraJudicial"
                               onchange="checkedDpExtraJudicial(this);" value="">
                        Depósito Extrajudicial?
                    </label>
                </div>
                <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2" id="divValor">
                    <label class="infraLabelObrigatorio" id="lblValor" name="lblValor" for="txtValor">Valor:</label>
                    <div class="input-group mb-3">
                        <input class="campoFieldsetSituacao infraText form-control" type="text" id="txtValor"
                               name="txtValor"
                               onkeypress="return infraMascaraDinheiro(this,event,2,12);" value=""/>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3" id="divDtDeposito">
                    <label class="infraLabelObrigatorio" id="lblDtDeposito" name="lblDtDeposito" for="txtDtDeposito">Data
                        do Depósito:</label>
                    <div class="input-group mb-3">
                        <input class="campoFieldsetSituacao campoData infraText form-control" type="text"
                               id="txtDtDeposito" name="txtDtDeposito"
                               onkeypress="return infraMascara(this, event, '##/##/####');" value=""/>
                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                             id="imgDtDeposito"
                             title="Selecionar Data do Depósito" alt="Selecionar Data do Depósito" class="infraImg"
                             onclick="infraCalendario('txtDtDeposito',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                    </div>
                </div>
            </div>
            <div class="row" id="divDataTipoSituacao" style="display: none">
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label class="infraLabelObrigatorio" id="lblDtDecisao" name="lblDtDecisao" for="txtDtTipoSituacao">Data
                        <label id="lblDtaTipoSituacao" class="infraLabelObrigatorio"></label>:</label>
                    <?php $dtCalendTipoSituacao = array_key_exists('dtDocumento', $dadosSituacao) ? $dadosSituacao['dtDocumento'] : InfraData::getStrDataAtual() ?>
                    <div class="input-group mb-3">
                        <input class="campoFieldsetSituacao campoData infraText form-control" type="text"
                               id="txtDtTipoSituacao"
                               name="txtDtTipoSituacao" onchange="return validarFormatoData(this)"
                               onkeypress="return infraMascara(this, event, '##/##/####');"
                               value="<?php echo array_key_exists('dtDocumento', $dadosSituacao) ? $dadosSituacao['dtDocumento'] : '' ?>"/>
                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                             title="Selecionar Data"
                             alt="Selecionar Data"
                             class="infraImg"
                             onclick="infraCalendario('txtDtTipoSituacao',this,false,'<?= $dtCalendTipoSituacao ?>');"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divTxtSituacao" style="display: none;">
                    <label class="infraLabelOpcional" id="txtSituacao"> </label>
                </div>
            </div>
            <div class="row linha" id="divsDatasBdPrescricao">
                <?php if ($dtIntercorrente != '') { ?>
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <label class="infraLabelOpcional lblIntQuinq" name="lblIntercorrenteTxt"
                               id="lblIntercorrenteTxt">Intercorrente: </label>
                        <label class="infraLabelOpcional lblIntQuinq" name="lblIntercorrenteDes"
                               id="lblIntercorrenteDes"> <?php echo $dtIntercorrente; ?> </label>

                        <a href="#" onclick="abrirModalDtIntercorrente()">
                            <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgLocal(); ?>/documento_html.svg?<?= Icone::VERSAO ?>"
                                 title="Histórico de Datas de Prescrição Intercorrente"
                                 alt="Histórico de Datas de Prescrição Intercorrente" class="infraImg"/>
                        </a>
                    </div>
                <? } ?>
                <?php if ($dtQuinquenal != '') { ?>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label class="infraLabelOpcional lblIntQuinq" name="lblQuinquenalTxt"
                           id="lblQuinquenalTxt">Quinquenal: </label>
                    <label class="infraLabelOpcional lblIntQuinq" name="lblDtQuinquenalDes"
                           id="lblDtQuinquenalDes"> <?php echo $dtQuinquenal; ?> </label>
                    <a href="#" onclick="abrirModalDtQuinquenal()">
                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgLocal(); ?>/documento_html.svg?<?= Icone::VERSAO ?>"
                             title="Histórico de Datas de Prescrição Quinquenal"
                             alt="Histórico de Datas de Prescrição Quinquenal" class="infraImg"/>
                    </a>
                </div>
                <?php } ?>
            </div>
            <!-- Texto Situação -->
            <div id="divMainPrescricao">
                <div class="row linha" id="divRdsPrescricao" style="display: none">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label class="infraLabelOpcional" name="lblNaoAlteraPrescricao" id="lblNaoAlteraPrescricao"
                               for="rdNaoAlteraPrescricao">
                            <input class="campoFieldsetSituacao infraRadio form-control"
                                   onchange="changePrescricao(this, false);" type="radio"
                                   id="rdNaoAlteraPrescricao" name="prescricao[]" value="0">
                            Não altera a prescrição
                        </label><br/>
                        <label class="infraLabelOpcional" name="lblAlteraPrescricao" id="lblAlteraPrescricao"
                               for="rdAlteraPrescricao">
                            <input class="campoFieldsetSituacao infraRadio form-control"
                                   onchange="changePrescricao(this, false);" type="radio"
                                   id="rdAlteraPrescricao" name="prescricao[]" value="1">
                            Altera a prescrição
                        </label>
                    </div>
                </div>
                <!-- Data Intercorrente -->
                <div id="divsDatasPrescricao" style="display: none">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divDatasSelectPrescricao" style="display: none">
                            <div class="row linha">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label class="infraLabelOpcional" name="lblIntercorrente"
                                           id="lblIntercorrente">Intercorrente:</label>
                                    <div class="input-group mb-3">
                                        <?php $dtCalendariInt = $dtIntercorrente != '' ? $dtIntercorrente : InfraData::getStrDataAtual() ?>
                                        <input onchange="return validarFormatoData(this)"
                                               class="campoData campoFieldsetSituacao infraText" type="text"
                                               id="txtDtIntercorrente"
                                               name="txtDtIntercorrente"
                                               onkeypress="return infraMascara(this, event, '##/##/####');"
                                               value="<?php echo $dtCalendariInt; ?>"
                                               data-valor-antigo="<?php echo $dtCalendariInt; ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                             title="Selecionar Data Intercorrente" alt="Selecionar Data Intercorrente"
                                             class="infraImg"
                                             onclick="infraCalendario('txtDtIntercorrente',this,false,'<?= $dtCalendariInt; ?>');"/>
                                        <button type="button" id="btnMaisTresAnos" name="btnMaisTresAnos"
                                                value="Hoje mais três anos"
                                                class="btnMaisAnos infraButton" campoRef="txtDtIntercorrente"
                                                onclick="somarAnosData(this, '3')">Hoje mais três anos
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label class="infraLabelOpcional" name="lblIntercorrente"
                                           id="lblIntercorrente">Quinquenal:</label>
                                    <div class="input-group mb-3">
                                        <?php $dtCalendariQuin = $dtQuinquenal != '' ? $dtQuinquenal : InfraData::getStrDataAtual() ?>
                                        <input onchange="return validarFormatoData(this)"
                                               class="campoData campoFieldsetSituacao infraText" type="text"
                                               id="txtDtQuinquenal"
                                               name="txtDtQuinquenal"
                                               onkeypress="return infraMascara(this, event, '##/##/####');"
                                               value="<?php echo $dtQuinquenal; ?>"
                                               data-valor-antigo="<?php echo $dtQuinquenal; ?>"/>
                                        <img src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg?<?= Icone::VERSAO ?>"
                                             id="imgDtQuinquenal"
                                             title="Selecionar Data Quinquenal" alt="Selecionar Data Quinquenal"
                                             class="infraImg"
                                             onclick="infraCalendario('txtDtQuinquenal',this,false,'<?= $dtCalendariQuin ?>');"/>
                                        <button type="button" onclick="somarAnosData(this, '5')"
                                                campoRef="txtDtQuinquenal"
                                                id="btnMaisCincoAnos" name="btnMaisCincoAnos"
                                                value="Hoje mais cinco anos"
                                                class="btnMaisAnos infraButton">Hoje mais cinco anos
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="row linha" id="divBtnAdicionar" style="display: none">
                    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                        <button type="button" onclick="addSituacao()" accesskey="a" id="btnAdicionar"
                                name="btnAdicionar"
                                value="Adicionar" class="infraButton"><span
                                    class="infraTeclaAtalho">A</span>dicionar
                        </button>
                    </div>
                </div>
            </div>
            <div class="row linha"  id="divTbSituacao" style="display: <?php echo ($strGridSituacao == '') ? 'none' : ''; ?>">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
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
                            <th class="infraTh" style="display: none;">Situacao Recursal</th>
                            <th class="infraTh" style="display: none;">Prazo</th>
                            <th class="infraTh" align="center" width="8%">Ações</th>
                        </tr>

                    </table>
                </div>
            </div>
            <!-- Hidden Relacionado à tabela -->
            <input type="hidden" name="hdnTbSituacoes" id="hdnTbSituacoes" value="<?= $strGridSituacao; ?>"/>
            <!--<input type="hidden" name="hdnTbSituacao" id="hdnTbSituacao" value=/>

            <!-- Erro da Situação -->
            <input type="hidden" name="hdnErroSituacao" id="hdnErroSituacao" value="1"/>

            <!--Tipo Situação -->
            <input type="hidden" name="hdnStrSituacao" id="hdnStrSituacao" value=""/>

            <!--Situação conclusiva-->
            <input type="hidden" name="hdnSinConslusiva" id="hdnSinConslusiva" value=""/>

            <!--Intimação após decisão de multa -->
            <input type="hidden" name="hdnIntimacaoPosDecisao" id="hdnIntimacaoPosDecisao" value=""/>

            <div id="divDadosIntimacaoAplicaocaoMulta">
                <!-- Data da Intimação da Decisão de Aplicação da Multa alteradas -->
            </div>

            <!--Id Situação -->
            <input type="hidden" name="hdnIdProcessoSituacao" id="hdnIdProcessoSituacao" value="0"/>

            <!-- Data da Situação Conclusiva  -->
            <input type="hidden" name="hdnDtSituacaoConclusiva" id="hdnDtSituacaoConclusiva"
                   value="<?php echo $dtUltimaSitConclusiva ?>"/>

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
            <input type="hidden" name="hdnDtPrimSitQuiq" id="hdnDtPrimSitQuiq"
                   value="<?php echo $dtMenorQuinquenal; ?>"/>

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
            <input type="hidden" name="hdnUrlDocumento" id="hdnUrlDocumento"
                   value="<?php echo array_key_exists('urlValidada', $dadosSituacao) ? $dadosSituacao['urlValidada'] : '' ?>"/>

            <!-- Campo para guardar o tipo de documento com o número (titulo do link no documento )-->
            <input type="hidden" name="hdnTituloDoc" id="hdnTituloDoc"
                   value="<?php echo array_key_exists('tituloDoc', $dadosSituacao) ? $dadosSituacao['tituloDoc'] : '' ?>"/>

            <!-- Campo que guarda 0 para processos que não possuirem decisões e 1 para processos que possuirem -->
            <input type="hidden" name="hdnProcessoSitIsDecisao" id="hdnProcessoSitIsDecisao"
                   value="<?php echo empty($arrVincDecisaoSit) ? '0' : '1'; ?>"/>

        </fieldset>
    </div>
</div>