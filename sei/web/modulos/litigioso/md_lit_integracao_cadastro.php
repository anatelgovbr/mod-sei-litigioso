<?php

/**
 * @since  23/02/2017
 * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();

//////////////////////////////////////////////////////////////////////////////
//InfraDebug::getInstance()->setBolLigado(false);
//InfraDebug::getInstance()->setBolDebugInfra(true);
//InfraDebug::getInstance()->limpar();
//////////////////////////////////////////////////////////////////////////////

//TOLTIP
$strTolTipEnd = 'O Sistema correspondente ao WSDL indicado deve estar previamente cadastrado no menu Administração > Sistemas e com Serviço também cadastrado com os IPs pertinentes.';
$strTolTipOperacao = 'A seleção da Operação para poder Mapear os campos de Origem e Destino somente é possível depois que validar o Endereço WSDL acima.';

//URL Cancelar
$strUrlCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno()
    . '&id_integracao_litigioso=' . $_GET['id_integracao_litigioso'] . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_integracao_litigioso']));

//Botões de ação do topo
$arrComandos[] = '<button type="submit" accesskey="S" id="btnSalvar" class="infraButton"  name="sbmCadastrarIntegracao"  value="Salvar" >
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';
$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

//Toltips
$strTolTipLblInt = 'Caso exista sistema próprio de cadastro e controle de lista de Integraçãos outorgados pela instituição. Somente permitirá finalizar o cadastro após o Mapeamento dos campos.';


function montarLinkVoltar($id)
{
    return SessaoSEI::getInstance()->assinarLink(
        'controlador.php?&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_integracao_litigioso=' . $id . PaginaSEI::getInstance()->montarAncora($id));
}

switch ($_GET['acao']) {

    case 'md_lit_integracao_cadastrar':

        $strTitulo = 'Cadastro de Integrações';
        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();

        $objMdLitIntegracaoDTO->setStrNome($_POST['txtNome']);
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($_POST['selFuncionalidade']);
        $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
        $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
        $objMdLitIntegracaoDTO->setStrTipoClienteWs($_POST['tipoWs']);
        $objMdLitIntegracaoDTO->setStrVersaoSoap($_POST['tipoWs'] == 'SOAP' ? $_POST['versaoSoap'] : '');
        $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao(null);

        $objMdLitIntegracaoDTO->setStrSinVincularLancamento('N');
        if (isset($_POST['chkSinVincularLancamento'])) {
            $objMdLitIntegracaoDTO->setStrSinVincularLancamento('S');
        }
        if (isset($_POST['hdnAcaoIntegracao']) && $_POST['hdnAcaoIntegracao'] == 'mapear') {
            $objMdLitSoapClienteRN = new MdLitSoapClienteRN( $_POST['txtEnderecoWsdl'] , ['soap_version' => $_POST['versaoSoap']]);
        }
        if (isset($_POST['sbmCadastrarIntegracao']) && $_POST['sbmCadastrarIntegracao'] == 'Salvar') {
            try {
                $arrObjMdLitMapearParamSaidaDTO = array();
                $arrObjMdLitMapearParamEntradaDTO = array();
                $objMdLitIntegracaoRN = new MdLitIntegracaoRN();

                if (isset($_POST['nomeFuncionalDadosEntrada']) && !empty($_POST['nomeFuncionalDadosEntrada'])) {
                    foreach ($_POST['nomeFuncionalDadosEntrada'] as $idMdLitNomeFuncional => $campo) {
                        if ($campo != 'null' && $campo != null) {
                            $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();

                            if ($_POST['selFuncionalidade'] == 1) {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');

                            } else if ($_POST['selFuncionalidade'] == 2 && $idMdLitNomeFuncional == 5) {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                                //Selecionou Código da Receita
                                if (isset($_POST['tpControle']) && !empty($_POST['tpControle'])) {
                                    $arrObjMdLitMapeaParamValorDTO = array();
                                    foreach ($_POST['tpControle'] as $tpControle) {
                                        $objMdLitMapeaParamValorDTO = new MdLitMapeaParamValorDTO();
                                        $objMdLitMapeaParamValorDTO->setNumIdMdLitTipoControle($tpControle['idMdLitTipoControle']);
                                        $objMdLitMapeaParamValorDTO->setStrValorDefault($tpControle['valorDefault']);
                                        $arrObjMdLitMapeaParamValorDTO[] = $objMdLitMapeaParamValorDTO;
                                    }
                                    $objMdLitMapearParamEntradaDTO->setArrObjMdLitMapeaParamValorDTO($arrObjMdLitMapeaParamValorDTO);
                                }
                            } else {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                            }

                            $arrObjMdLitMapearParamEntradaDTO[] = $objMdLitMapearParamEntradaDTO;
                        }
                    }
                }

                if (isset($_POST['nomeFuncionalDadosSaida']) && !empty($_POST['nomeFuncionalDadosSaida'])) {
                    foreach ($_POST['nomeFuncionalDadosSaida'] as $idMdLitNomeFuncional => $campo) {
                        if ($campo != 'null' && $campo != null) {
                            $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();

                            if ($_POST['selFuncionalidade'] == 1) {
                                $objMdLitMapearParamSaidaDTO->setNumIdMdLitNomeFuncional($idMdLitNomeFuncional);
                                $objMdLitMapearParamSaidaDTO->setStrCampo($campo);
                                $objMdLitMapearParamSaidaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosSaida'] ? 'S' : 'N');
                            } else {
                                $objMdLitMapearParamSaidaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamSaidaDTO->setStrCampo($campo);
                                $objMdLitMapearParamSaidaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosSaida'] ? 'S' : 'N');
                            }

                            $arrObjMdLitMapearParamSaidaDTO[] = $objMdLitMapearParamSaidaDTO;
                        }
                    }
                }

                $objMdLitIntegracaoDTO->setStrSinVincularLancamento('N');
                if (isset($_POST['chkSinVincularLancamento'])) {
                    $objMdLitIntegracaoDTO->setStrSinVincularLancamento('S');
                }

                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamEntradaDTO($arrObjMdLitMapearParamEntradaDTO);
                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamSaidaDTO($arrObjMdLitMapearParamSaidaDTO);

                $objMdLitIntegracaoRN->cadastrar($objMdLitIntegracaoDTO);

                PaginaSEI::getInstance()->adicionarMensagem('Integração "' . $objMdLitIntegracaoDTO->getStrNome() . '" cadastrado com sucesso.');
                header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_md_lit_integracao=' . $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao() . PaginaSEI::getInstance()->montarAncora($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao())));
                die;

            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }
        break;

    case 'md_lit_integracao_alterar':
        $strTitulo = 'Alterar Integração';
        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();

        $objMdLitIntegracaoDTO->setStrNome($_POST['txtNome']);
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($_POST['selFuncionalidade']);
        $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
        $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
        $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_POST['hdnIdMdLitIntegracao']);
        $objMdLitIntegracaoDTO->setStrTipoClienteWs($_POST['tipoWs']);
        $objMdLitIntegracaoDTO->setStrVersaoSoap($_POST['tipoWs'] == 'SOAP' ? $_POST['versaoSoap'] : '');
        $objMdLitIntegracaoDTO->setStrSinVincularLancamento('N');
        if (isset($_POST['chkSinVincularLancamento'])) {
            $objMdLitIntegracaoDTO->setStrSinVincularLancamento('S');
        }


        if (isset($_GET['id_md_lit_integracao'])) {
            $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
            $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_GET['id_md_lit_integracao']);
            $objMdLitIntegracaoDTO->retTodos();
            $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultar($objMdLitIntegracaoDTO);

            if ($objMdLitIntegracaoDTO == null) {
                throw new InfraException("Registro não encontrado.");
            }
            if (!empty($_POST['txtEnderecoWsdl']) && !empty($_POST['selOperacao'])) {
                $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
            }

        } elseif (isset($_POST['sbmCadastrarIntegracao']) && $_POST['sbmCadastrarIntegracao'] == 'Salvar') {
            try {
                $objMdLitIntegracaoDTO->setStrNome($_POST['txtNome']);
                $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($_POST['selFuncionalidade']);
                $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
                $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_POST['hdnIdMdLitIntegracao']);

                $arrObjMdLitMapearParamSaidaDTO = array();
                $arrObjMdLitMapearParamEntradaDTO = array();
                $objMdLitIntegracaoRN = new MdLitIntegracaoRN();

                if (isset($_POST['nomeFuncionalDadosEntrada']) && !empty($_POST['nomeFuncionalDadosEntrada'])) {
                    foreach ($_POST['nomeFuncionalDadosEntrada'] as $idMdLitNomeFuncional => $campo) {
                        if ($campo != 'null' && $campo != null) {
                            $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();
                            if ($_POST['selFuncionalidade'] == 1) {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                            } else if ($_POST['selFuncionalidade'] == 2 && $idMdLitNomeFuncional == 5) {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                                //Selecionou Código da Receita
                                if (isset($_POST['tpControle']) && !empty($_POST['tpControle'])) {
                                    $arrObjMdLitMapeaParamValorDTO = array();
                                    foreach ($_POST['tpControle'] as $tpControle) {
                                        $objMdLitMapeaParamValorDTO = new MdLitMapeaParamValorDTO();
                                        $objMdLitMapeaParamValorDTO->setNumIdMdLitTipoControle($tpControle['idMdLitTipoControle']);
                                        $objMdLitMapeaParamValorDTO->setStrValorDefault($tpControle['valorDefault']);
                                        $arrObjMdLitMapeaParamValorDTO[] = $objMdLitMapeaParamValorDTO;
                                    }
                                    $objMdLitMapearParamEntradaDTO->setArrObjMdLitMapeaParamValorDTO($arrObjMdLitMapeaParamValorDTO);
                                }

                            } else {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                            }

                            $arrObjMdLitMapearParamEntradaDTO[] = $objMdLitMapearParamEntradaDTO;
                        }
                    }


                }

                if (isset($_POST['nomeFuncionalDadosSaida']) && !empty($_POST['nomeFuncionalDadosSaida'])) {
                    foreach ($_POST['nomeFuncionalDadosSaida'] as $idMdLitNomeFuncional => $campo) {
                        if ($campo != 'null' && $campo != null) {
                            $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();
                            if ($_POST['selFuncionalidade'] == 1) {
                                $objMdLitMapearParamSaidaDTO->setNumIdMdLitNomeFuncional($idMdLitNomeFuncional);
                                $objMdLitMapearParamSaidaDTO->setStrCampo($campo);
                                $objMdLitMapearParamSaidaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosSaida'] ? 'S' : 'N');
                            } else {
                                $objMdLitMapearParamSaidaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamSaidaDTO->setStrCampo($campo);
                                $objMdLitMapearParamSaidaDTO->setStrChaveUnica($idMdLitNomeFuncional == $_POST['chaveUnicaDadosSaida'] ? 'S' : 'N');
                            }

                            $arrObjMdLitMapearParamSaidaDTO[] = $objMdLitMapearParamSaidaDTO;
                        }
                    }
                }

                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamEntradaDTO($arrObjMdLitMapearParamEntradaDTO);
                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamSaidaDTO($arrObjMdLitMapearParamSaidaDTO);
                $objMdLitIntegracaoDTO->setStrSinVincularLancamento('N');
                if (isset($_POST['chkSinVincularLancamento'])) {
                    $objMdLitIntegracaoDTO->setStrSinVincularLancamento('S');
                }

                $objMdLitIntegracaoRN->alterar($objMdLitIntegracaoDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Os dados foram alterados com sucesso.');
                header('Location: ' . montarLinkVoltar($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao()));
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }

        break;

    case 'md_lit_integracao_consultar':
        $strTitulo = 'Consultar Integração';
        $arrComandos = array();
        $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_GET['id_md_lit_integracao']);
        $objMdLitIntegracaoDTO->retTodos();
        $objMdLitIntegracaoDTO->setBolExclusaoLogica(false);
        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultar($objMdLitIntegracaoDTO);

        if ($objMdLitIntegracaoDTO == null) {
            throw new InfraException("Registro não encontrado.");
        }
        break;

    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        break;
}
if (!empty($objMdLitIntegracaoDTO->getStrEnderecoWsdl()) && !empty($objMdLitIntegracaoDTO->getStrOperacaWsdl()) && !empty($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade())) {
    $objMdLitSoapClienteRN = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
    try {
        $arrMontarTabelaParamEntrada = MdLitMapearParamEntradaINT::montarTabelaParamEntrada($objMdLitSoapClienteRN, $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade(), $objMdLitIntegracaoDTO->getStrOperacaWsdl(), $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao(), $objMdLitIntegracaoDTO);
        $arrMontarTabelaParamSaida = MdLitMapearParamSaidaINT::montarTabelaParamSaida($objMdLitSoapClienteRN, $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade(), $objMdLitIntegracaoDTO->getStrOperacaWsdl(), $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao(), $objMdLitIntegracaoDTO);
    } catch (Exception $e) {
        $exception = new InfraException();
        $exception->adicionarValidacao('Não foi possível carregar o web-service. ' . $e);
        PaginaSEI::getInstance()->processarExcecao($exception);
    }
}

$comboFuncionalidade = MdLitFuncionalidadeINT::montarSelectNome('null', '', $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade(), $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
require_once("md_lit_integracao_cadastro_css.php");
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmIntegracaoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">
        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">
                        Nome:
                    </label>
                    <input type="text" id="txtNome" name="txtNome" class="infraText form-control"
                            onkeypress="return infraMascaraTexto(this,event,30);"
                            value="<?= PaginaSEI::tratarHTML($objMdLitIntegracaoDTO->getStrNome()); ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblFuncionalidade" for="selFuncionalidade" class="infraLabelObrigatorio">
                        Funcionalidade:
                    </label>
                    <select id="selFuncionalidade" name="selFuncionalidade" class="infraSelect form-select"
                            onchange="apagarOperacao()">
                        <?= $comboFuncionalidade ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-5 col-xl-4">
                <div class="form-group">
                    <label id="lbltipoWs" for="tipoWs" class="infraLabelObrigatorio">
                        Tipo Cliente WS:
                    </label>
                    <br/>
                    <label for="tipoWs" class="infraLabelCheckbox">
                        <input type="radio" name="tipoWs" class="infraRadio"
                                value="SOAP" <?= $objMdLitIntegracaoDTO->getStrTipoClienteWs() != 'REST' ? 'checked' : ''; ?> >
                        SOAP
                    </label>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-5 col-xl-5">
                <div class="form-group">
                    <label id="lbltipoWs" for="tipoWs" class="infraLabelObrigatorio">
                        Versão SOAP:
                    </label>
                    <select id="versaoSoap" name="versaoSoap" class="infraSelect form-select">
                        <option value="1.2" <?= PaginaSEI::tratarHTML($objMdLitIntegracaoDTO->getStrVersaoSoap()) == '1.2' ? 'selected' : ''; ?>>
                            1.2
                        </option>
                        <option value="1.1" <?= PaginaSEI::tratarHTML($objMdLitIntegracaoDTO->getStrVersaoSoap()) == '1.1' ? 'selected' : ''; ?>>
                            1.1
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblEnderecoWsdl" for="txtEnderecoWsdl" class="infraLabelObrigatorio">
                        Endereço WSDL:
                    </label>
                    <div class="input-group mb-3">
                        <input type="text" id="txtEnderecoWsdl"
                                onkeypress="return infraMascaraTexto(this,event,100);"
                                name="txtEnderecoWsdl" onchange="apagarOperacao()" class="infraText form-control"
                                value="<?= PaginaSEI::tratarHTML($objMdLitIntegracaoDTO->getStrEnderecoWsdl()); ?>">
                        <button class="infraButton" name="btnValidar" id="btnValidar" onclick="validarWsdl()"
                                type="button">
                            Validar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-9">
                <div class="form-group">
                    <label id="lblOperacao" for="selOperacao" class="infraLabelObrigatorio">
                        Operação:
                        <a id="btAjuda" <?= PaginaSEI::montarTitleTooltip($strTolTipOperacao, 'Ajuda') ?>
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <img border="0"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal(); ?>/ajuda.svg?<?= Icone::VERSAO ?>"
                                    class="infraImgModulo"/>
                        </a>
                    </label>
                    <div class="input-group mb-3">
                        <select type="text" id="selOperacao" name="selOperacao" class="infraSelect form-select"
                                onchange="apagarMapear()">
                        </select>
                        <button class="infraButton" name="btnMapear" id="btnMapear" type="button"
                                onclick="mapear()">
                            Mapear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid_11 soap" id="gridOperacao">
            <div style="margin-top: 13px;" class="grid grid_10-1">
                <div class="grid grid_2" sytle="margin-top: -38px;margin-left: 491px;">
                    <span class="tooltipAjuda tooltipAjudaInput"
                          id="tooltipOperacao"></span>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="grid grid_11 infraDivRadio"
             style="margin-top: 13px;display:<?php echo $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade() == MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO ? '' : 'none' ?>;"
             id="divSinVincularLancamento">
            <input type="checkbox" name="chkSinVincularLancamento" id="chkSinVincularLancamento" class="infraCheckbox"
                   value="S" <?php echo $objMdLitIntegracaoDTO && $objMdLitIntegracaoDTO->getStrSinVincularLancamento() == 'S' ? 'checked="checked"' : '' ?>>
            <span>
                <label id="lblSinVincularLancamento" for="chkSinVincularLancamento" class="infraLabelCheckbox">
                    Habilitar Funcionalidade de Vinculação de Lançamento Pré Existente
                </label>
            </span>
        </div>

        <?
        PaginaSEI::getInstance()->montarAreaTabela($arrMontarTabelaParamEntrada['strResultado'], $arrMontarTabelaParamEntrada['numRegistros']);
        ?>
        <?
        PaginaSEI::getInstance()->montarAreaTabela($arrMontarTabelaParamSaida['strResultado'], $arrMontarTabelaParamSaida['numRegistros']);
        ?>
        <input type="hidden" id="hdnIdMdLitIntegracao" name="hdnIdMdLitIntegracao"
               value="<?= $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao() ?>"/>
        <input type="hidden" id="hdnAcaoIntegracao" name="hdnAcaoIntegracao" value=""/>
    </form>
<?php
require_once("md_lit_integracao_cadastro_js.php");
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
