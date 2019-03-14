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
$strTolTipEnd = 'O Sistema correspondente ao WSDL indicado deve estar previamente cadastrado no menu Administra��o > Sistemas e com Servi�o tamb�m cadastrado com os IPs pertinentes.';
$strTolTipOperacao = 'a descri��o do texto de ajuda ser� elaborado ap�s o servi�o estar dispon�vel no barramento, pois teremos verificado todas as valida��es necess�rias';

//URL Cancelar
$strUrlCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno()
    . '&id_integracao_litigioso=' . $_GET['id_integracao_litigioso'] . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($_GET['id_integracao_litigioso']));

//Bot�es de a��o do topo
$arrComandos[] = '<button type="submit" accesskey="S" id="btnSalvar" class="infraButton"  name="sbmCadastrarIntegracao"  value="Salvar" >
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';
$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';

//Toltips
$strTolTipLblInt = 'Caso exista sistema pr�prio de cadastro e controle de lista de Integra��os outorgados pela institui��o. Somente permitir� finalizar o cadastro ap�s o Mapeamento dos campos.';


function montarLinkVoltar($id)
{
    return SessaoSEI::getInstance()->assinarLink(
        'controlador.php?&acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_integracao_litigioso=' . $id . PaginaSEI::getInstance()->montarAncora($id));
}

switch ($_GET['acao']) {

    case 'md_lit_integracao_cadastrar':

        $strTitulo = 'Cadastro de Integra��es';
        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();

        $objMdLitIntegracaoDTO->setStrNome($_POST['txtNome']);
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($_POST['selFuncionalidade']);
        $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
        $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
        $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao(null);
        if (isset($_POST['hdnAcaoIntegracao']) && $_POST['hdnAcaoIntegracao'] == 'mapear' ) {
            $objMdLitSoapClienteRN = new MdLitSoapClienteRN($_POST['txtEnderecoWsdl'], 'wsdl');
        }
        if(isset($_POST['sbmCadastrarIntegracao']) && $_POST['sbmCadastrarIntegracao'] == 'Salvar'){
            try{
                $arrObjMdLitMapearParamSaidaDTO = array();
                $arrObjMdLitMapearParamEntradaDTO = array();
                $objMdLitIntegracaoRN = new MdLitIntegracaoRN();

                if(isset($_POST['nomeFuncionalDadosEntrada']) && !empty($_POST['nomeFuncionalDadosEntrada'])) {
                    foreach ($_POST['nomeFuncionalDadosEntrada'] as $campo => $idMdLitNomeFuncional) {
                        if ($idMdLitNomeFuncional != 'null' && $idMdLitNomeFuncional != null) {
                            $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();

                            if ($_POST['selFuncionalidade'] == 1) {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');

                            } else if ($_POST['selFuncionalidade'] == 2 && $idMdLitNomeFuncional == 5) {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                                //Selecionou C�digo da Receita
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
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                            }

                            $arrObjMdLitMapearParamEntradaDTO[] = $objMdLitMapearParamEntradaDTO;
                        }
                    }
                }

                if(isset($_POST['nomeFuncionalDadosSaida']) && !empty($_POST['nomeFuncionalDadosSaida'])) {
                    foreach ($_POST['nomeFuncionalDadosSaida'] as $campo => $idMdLitNomeFuncional) {
                        if ($idMdLitNomeFuncional != 'null' && $idMdLitNomeFuncional != null) {
                            $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();

                            if ($_POST['selFuncionalidade'] == 1) {
                                $objMdLitMapearParamSaidaDTO->setNumIdMdLitNomeFuncional($idMdLitNomeFuncional);
                                $objMdLitMapearParamSaidaDTO->setStrCampo($campo);
                                $objMdLitMapearParamSaidaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosSaida'] ? 'S' : 'N');
                            }else{
                                $objMdLitMapearParamSaidaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamSaidaDTO->setStrCampo($campo);
                                $objMdLitMapearParamSaidaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosSaida'] ? 'S' : 'N');
                            }

                            $arrObjMdLitMapearParamSaidaDTO[] = $objMdLitMapearParamSaidaDTO;
                        }
                    }
                }

                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamEntradaDTO($arrObjMdLitMapearParamEntradaDTO);
                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamSaidaDTO($arrObjMdLitMapearParamSaidaDTO);

                $objMdLitIntegracaoRN->cadastrar($objMdLitIntegracaoDTO);

                PaginaSEI::getInstance()->adicionarMensagem('Integra��o "'.$objMdLitIntegracaoDTO->getStrNome().'" cadastrado com sucesso.');
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_md_lit_integracao='.$objMdLitIntegracaoDTO->getNumIdMdLitIntegracao().PaginaSEI::getInstance()->montarAncora($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao())));
                die;

            } catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }
        break;

    case 'md_lit_integracao_alterar':
        $strTitulo = 'Alterar Integra��o';
        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoRN  = new MdLitIntegracaoRN();

        $objMdLitIntegracaoDTO->setStrNome($_POST['txtNome']);
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($_POST['selFuncionalidade']);
        $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
        $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
        $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_POST['hdnIdMdLitIntegracao']);

        if (isset($_GET['id_md_lit_integracao'])) {
            $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
            $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_GET['id_md_lit_integracao']);
            $objMdLitIntegracaoDTO->retTodos();
            $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultar($objMdLitIntegracaoDTO);

            if ($objMdLitIntegracaoDTO == null) {
                throw new InfraException("Registro n�o encontrado.");
            }
            if(!empty($_POST['txtEnderecoWsdl']) && !empty($_POST['selOperacao'])){
                $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
            }

        } elseif(isset($_POST['sbmCadastrarIntegracao']) && $_POST['sbmCadastrarIntegracao'] == 'Salvar') {
            try {
                $objMdLitIntegracaoDTO->setStrNome($_POST['txtNome']);
                $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($_POST['selFuncionalidade']);
                $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
                $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
                $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_POST['hdnIdMdLitIntegracao']);

                $arrObjMdLitMapearParamSaidaDTO = array();
                $arrObjMdLitMapearParamEntradaDTO = array();
                $objMdLitIntegracaoRN = new MdLitIntegracaoRN();

                if(isset($_POST['nomeFuncionalDadosEntrada']) && !empty($_POST['nomeFuncionalDadosEntrada'])) {
                    foreach ($_POST['nomeFuncionalDadosEntrada'] as $campo => $idMdLitNomeFuncional) {
                        if ($idMdLitNomeFuncional != 'null' && $idMdLitNomeFuncional != null) {
                            $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();

                            if ($_POST['selFuncionalidade'] == 1) {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                            }else if($_POST['selFuncionalidade'] == 2 && $idMdLitNomeFuncional == 5) {
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                                //Selecionou C�digo da Receita
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

                            }else{
                                $objMdLitMapearParamEntradaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamEntradaDTO->setStrCampo($campo);
                                $objMdLitMapearParamEntradaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosEntrada'] ? 'S' : 'N');
                            }

                            $arrObjMdLitMapearParamEntradaDTO[] = $objMdLitMapearParamEntradaDTO;
                        }
                    }



                }

                if(isset($_POST['nomeFuncionalDadosSaida']) && !empty($_POST['nomeFuncionalDadosSaida'])) {
                    foreach ($_POST['nomeFuncionalDadosSaida'] as $campo => $idMdLitNomeFuncional) {
                        if ($idMdLitNomeFuncional != 'null' && $idMdLitNomeFuncional != null) {
                            $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();

                            if ($_POST['selFuncionalidade'] == 1) {
                                $objMdLitMapearParamSaidaDTO->setNumIdMdLitNomeFuncional($idMdLitNomeFuncional);
                                $objMdLitMapearParamSaidaDTO->setStrCampo($campo);
                                $objMdLitMapearParamSaidaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosSaida'] ? 'S' : 'N');
                            }else{
                                $objMdLitMapearParamSaidaDTO->setNumIdMdLitCampoIntegracao($idMdLitNomeFuncional);
                                $objMdLitMapearParamSaidaDTO->setStrCampo($campo);
                                $objMdLitMapearParamSaidaDTO->setStrChaveUnica($campo == $_POST['chaveUnicaDadosSaida'] ? 'S' : 'N');
                            }

                            $arrObjMdLitMapearParamSaidaDTO[] = $objMdLitMapearParamSaidaDTO;
                        }
                    }
                }

                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamEntradaDTO($arrObjMdLitMapearParamEntradaDTO);
                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamSaidaDTO($arrObjMdLitMapearParamSaidaDTO);

                $objMdLitIntegracaoRN->alterar($objMdLitIntegracaoDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Os dados foram alterados com sucesso.');
                header('Location: ' . montarLinkVoltar($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao()));
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }

        break;

    case 'md_lit_integracao_consultar':
        $strTitulo     = 'Consultar Integra��o';
        $arrComandos   = array();
        $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';

        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_GET['id_md_lit_integracao']);
        $objMdLitIntegracaoDTO->retTodos();
        $objMdLitIntegracaoDTO->setBolExclusaoLogica(false);
        $objMdLitIntegracaoRN  = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultar($objMdLitIntegracaoDTO);

        if ($objMdLitIntegracaoDTO == null) {
            throw new InfraException("Registro n�o encontrado.");
        }
        break;

    default:
        throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
        break;
}
if(!empty($objMdLitIntegracaoDTO->getStrEnderecoWsdl()) && !empty($objMdLitIntegracaoDTO->getStrOperacaWsdl()) && !empty($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade())){
    $objMdLitSoapClienteRN = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(), 'wsdl');
    try {
        $arrMontarTabelaParamEntrada = MdLitMapearParamEntradaINT::montarTabelaParamEntrada($objMdLitSoapClienteRN, $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade(), $objMdLitIntegracaoDTO->getStrOperacaWsdl(), $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
        $arrMontarTabelaParamSaida = MdLitMapearParamSaidaINT::montarTabelaParamSaida($objMdLitSoapClienteRN, $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade(), $objMdLitIntegracaoDTO->getStrOperacaWsdl(), $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
    }catch (Exception $e){
        $exception = new InfraException();
        $exception->adicionarValidacao('N�o foi poss�vel carregar o web-service.');
        PaginaSEI::getInstance()->processarExcecao($exception);
    }
}

$comboFuncionalidade = MdLitFuncionalidadeINT::montarSelectNome('null', '', $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade(),$objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

require_once 'md_lit_css_geral.php';

?>
<?if(0){?><style><?}?>
#lblEnderecoWsdl{width: 100%;float: left;}
#txtEnderecoWsdl{width: 70%;float: left;}
.grid.grid_2{float: left;}
#tooltipEnderecoWsdl{margin: 2px 0 0 10px;float: left;}
#btnValidar{float: left;}
.grid_10-1{width: 650px;}
#selOperacao{width: 71%;float: left;}
#lblOperacao{width: 100%;float: left;}
#tooltipOperacao{margin: 2px 0 0 10px;float: left;}
#gridOperacao{display:none}
#btnMapear{float:left;}
div.infraAreaTabela{margin-top:20px;float:left;}
table td select{font-size: 1.0em;}
<?php if($_GET['acao'] == 'md_lit_integracao_consultar'){  ?>
    #btnValidar, #btnMapear{display: none}
<?php } ?>
<?if(0){?></style><?}?>
<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

$strLinkAjaxValidarWsdl = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_integracao_busca_operacao_wsdl');
$strLinkAjaxBuscarTipoControle = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_integracao_buscar_tipo_controle');

?>

<?if(0){?><script><?}?>

    function inicializar() {
        if ('<?= $_GET['acao'] ?>'=='md_lit_integracao_cadastrar')
        {
        document.getElementById('txtNome').focus();
        } else if ('<?= $_GET['acao'] ?>'=='md_lit_integracao_consultar'){
        infraDesabilitarCamposAreaDados();
        }else{
        document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas();
        if(document.getElementById('txtEnderecoWsdl').value != '')
            validarWsdl();
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome.');
            document.getElementById('txtNome').focus();
            return false;
        }else if(document.getElementById('txtNome').value.length > 30){
            alert('Tamanho do campo excedido (m�ximo 30 caracteres).');
            document.getElementById('txtNome').focus();
            return false;
        }
        if (infraTrim(document.getElementById('selFuncionalidade').value) == 'null') {
            alert('Selecione a funcionalidade.');
            document.getElementById('selFuncionalidade').focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtEnderecoWsdl').value) == '') {
            alert('Informe o endere�o WSDL.');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }
        if (infraTrim(document.getElementById('selOperacao').value) == '') {
            alert('Selecione a opera��o.');
            document.getElementById('selOperacao').focus();
            return false;
        }
        if(document.getElementById('tableParametroEntrada') == null && document.getElementById('tableParametroSaida') == null){
            alert('Favor mapear a integra��o.');
            document.getElementById('btnMapear').focus();
            return false;
        }

        if(document.getElementById('selFuncionalidade').value == 1) {
            var selectNomeFuncionalDadosEntradaVazio = true;
            var rdChaveUnicaDadosEntradaVazio = true;
            var selectNomeFuncionalDadosSaidaVazio = true;
            var rdChaveUnicaDadosSaidaVazio = true;

            for (var i = 0; i < (document.getElementById('tableParametroEntrada').rows.length - 1); i++) {

                if (infraTrim(document.getElementById('nomeFuncionalDadosEntrada_' + i).value) != 'null') {
                    selectNomeFuncionalDadosEntradaVazio = false;
                }
                if (document.getElementById('chaveUnicaDadosEntrada_' + i).checked) {
                    rdChaveUnicaDadosEntradaVazio = false;
                }

            }
            for (var i = 0; i < (document.getElementById('tableParametroSaida').rows.length - 1); i++) {

                if (infraTrim(document.getElementById('nomeFuncionalDadosSaida_' + i).value) != 'null') {
                    selectNomeFuncionalDadosSaidaVazio = false;
                }
                if (document.getElementById('chaveUnicaDadosSaida_' + i).checked) {
                    rdChaveUnicaDadosSaidaVazio = false;
                }
            }

            if (selectNomeFuncionalDadosEntradaVazio) {
                alert('Informe ao menos um campo de destino no SEI na tabela de dados de entrada.');
                document.getElementById('tableParametroEntrada').scrollIntoView()
                return false;
            }
            if (rdChaveUnicaDadosEntradaVazio) {
                alert('Informe ao menos uma chave �nica da integra��o na tabela de dados de entrada.');
                document.getElementById('tableParametroEntrada').scrollIntoView()
                return false;
            }
            if (selectNomeFuncionalDadosSaidaVazio) {
                alert('Informe ao menos um campo de destino no SEI na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }
            if (rdChaveUnicaDadosSaidaVazio) {
                alert('Informe ao menos uma chave �nica da integra��o na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }
        }else{

            //Dados Entrada
            if(document.getElementById('tableParametroEntrada') != null) {
                var qtdOptionEntrada = $('[name^="nomeFuncionalDadosEntrada"]')[0].options.length - 1;
                var qtdOptionEntradaSelecionado = $('[name^="nomeFuncionalDadosEntrada"] option[value!="null"]:selected').length;
                if (qtdOptionEntrada > 0) {
                    if (qtdOptionEntrada != qtdOptionEntradaSelecionado) {
                        alert('Todos os campos do par�metro de entrada devem ser mapeados');
                        document.getElementById('tableParametroEntrada').scrollIntoView()
                        return false;
                    }
                }

                var rdChaveUnicaDadosEntradaVazio = $('[name="chaveUnicaDadosEntrada"]:checked').length == 0;
                if (rdChaveUnicaDadosEntradaVazio) {
                    alert('Informe ao menos uma chave �nica da integra��o na tabela de dados de entrada.');
                    document.getElementById('tableParametroEntrada').scrollIntoView()
                    return false;
                }
            }

            if (document.getElementById('tableParametroSaida') != null) {
                //Dados Saida
                var qtdOptionSaida = $('[name^="nomeFuncionalDadosSaida"]')[0].options.length - 1;
                var qtdOptionSaidaSelecionado = $('[name^="nomeFuncionalDadosSaida"] option[value!="null"]:selected').length;
                if (qtdOptionSaida > 0) {
                    if (qtdOptionSaida != qtdOptionSaidaSelecionado) {
                        alert('Todos os campos do par�metro de saida devem ser mapeados');
                        document.getElementById('tableParametroSaida').scrollIntoView()
                        return false;
                    }
                }
                var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
                if (rdChaveUnicaDadosSaidaVazio) {
                    alert('Informe ao menos uma chave �nica da integra��o na tabela de dados de saida.');
                    document.getElementById('tableParametroSaida').scrollIntoView()
                    return false;
                }
            }

        }
        document.getElementById('frmIntegracaoCadastro').submit();
    }

    function cancelar(){
        location.href="<?= $strUrlCancelar ?>";
    }

    function validarWsdl(){
        var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        if(enderecoWsdl == ''){
            alert('Preenche o campo Endere�o WSDL.');
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?= $strLinkAjaxValidarWsdl ?>",
            dataType: "xml",
            data: {
            endereco_wsdl: enderecoWsdl,
            },
            beforeSend: function(){
                infraExibirAviso(false);
            },
            success: function (result) {
                var select = document.getElementById('selOperacao');
                //limpar todos os options
                select.options.length = 0;

                if($(result).find('success').text() == 'true'){
                    var opt = document.createElement('option');
                    opt.value = '';
                    opt.innerHTML = '';
                    select.appendChild(opt);
                    var selectedValor = '<?= PaginaSEI::tratarHTML( $objMdLitIntegracaoDTO->getStrOperacaWsdl() );?>';

                    $.each($(result).find('operacao'), function(key, value){
                        var opt = document.createElement('option');
                        opt.value = $(value).text();
                        opt.innerHTML = $(value).text();
                        if($(value).text() == selectedValor )
                            opt.selected = true;
                        select.appendChild(opt);
                    });

                    document.getElementById('gridOperacao').style.display = "block";
                }else{
                    alert($(result).find('msg').text());
                    document.getElementById('gridOperacao').style.display = "none";
                }
            },
            error: function (msgError) {
            msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            console.log(msgCommit);
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });

    }

    function mapear(){
        if (infraTrim(document.getElementById('txtEnderecoWsdl').value) == '') {
            alert('Informe o Endere�o do WSDL.');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }

        if (infraTrim(document.getElementById('selFuncionalidade').value) == 'null') {
            alert('Informe a Funcionalidade.');
            document.getElementById('selFuncionalidade').focus();
            return false;
        }

        if (infraTrim(document.getElementById('selOperacao').value) == '') {
            alert('Informe a Opera��o.');
            document.getElementById('selOperacao').focus();
            return false;
        }
        document.getElementById('hdnAcaoIntegracao').value = 'mapear';
        document.getElementById('frmIntegracaoCadastro').submit();
    }

    function mudarNomeFuncionalDadosEntrada(element){
        limparTabelaCodigoReceita(element);
        if(element.value != 'null'){
            var row = document.getElementById('tableParametroEntrada').rows[element.parentNode.parentNode.rowIndex];
            row.cells[2].children[0].disabled = false;
            for (var i = 0; i < document.getElementById('tableParametroEntrada').rows.length; i++){
                if(i != 0 && i != element.parentNode.parentNode.rowIndex){
                    var select = document.getElementById('nomeFuncionalDadosEntrada_'+(i-1));
                    for(var j=0;j<select.options.length;j++ ){
                        if(select.value == element.value){
                            select.value = 'null';
                            mudarNomeFuncionalDadosEntrada(select);
                            break;
                        }
                    }
                }
            }

        }else{
            var row = document.getElementById('tableParametroEntrada').rows[element.parentNode.parentNode.rowIndex];
            row.cells[2].children[0].disabled = true;
            row.cells[2].children[0].checked = false;
        }
    }

    function mudarNomeFuncionalDadosSaida(element){
        if(element.value != 'null'){
            var row = document.getElementById('tableParametroSaida').rows[element.parentNode.parentNode.rowIndex];
            row.cells[2].children[0].disabled = false;
            for (var i = 0; i < document.getElementById('tableParametroSaida').rows.length; i++){
                if(i != 0 && i != element.parentNode.parentNode.rowIndex){
                    var select = document.getElementById('nomeFuncionalDadosSaida_'+(i-1));
                    for(var j=0;j<select.options.length;j++ ){
                        if(select.value == element.value){
                            select.value = 'null';
                            mudarNomeFuncionalDadosSaida(select);
                            break;
                        }
                    }
                }
            }

        }else{
            var row = document.getElementById('tableParametroSaida').rows[element.parentNode.parentNode.rowIndex];
            row.cells[2].children[0].disabled = true;
            row.cells[2].children[0].checked = false;
        }
    }

    function OnSubmitForm() {
        ret = validarCadastro();
        return ret;
    }

    function apagarMapear() {
        if (document.getElementById('tableParametroEntrada') != null) {
            document.getElementById('tableParametroEntrada').remove();
        }
        if (document.getElementById('tableParametroSaida') != null) {
            document.getElementById('tableParametroSaida').remove();
        }
    }

    function apagarOperacao(){
        var select = document.getElementById('selOperacao');
        select.options.length = 0;
        document.getElementById('gridOperacao').style.display = "none";
        apagarMapear();
    }

    function verificarCodigoReceita(selCampoDestino) {
        var selFuncionalidade = document.getElementById('selFuncionalidade');
        var td = selCampoDestino.parentNode;
        limparTabelaCodigoReceita(selCampoDestino);
        if (selFuncionalidade.value == 2) {
            if (selCampoDestino.value == 5) {
                montarTabelaCodigoReceita(td);
            }
        }
    }

    function montarTabelaCodigoReceita(td) {
        $.ajax({
            url: '<?=$strLinkAjaxBuscarTipoControle?>',
            dataType: 'HTML',
            type: 'POST',
            success: function (r) {
                td.insertAdjacentHTML('beforeend', r);
            }
        });
    }

    function limparTabelaCodigoReceita(el) {
        var td = el.parentNode;
        var div = td.getElementsByTagName('div')[0];
        if (div != null) {
            div.remove();
        }
    }

<?if(0){?></script><?}?>
<?php PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<?php PaginaSEI::getInstance()->abrirAreaDados(); ?>
    <form id="frmIntegracaoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>


        <div class="grid grid_7-6">
            <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">
                Nome:
            </label>
            <input type="text" id="txtNome" name="txtNome" onkeypress="return infraMascaraTexto(this,event,30);" value="<?= PaginaSEI::tratarHTML( $objMdLitIntegracaoDTO->getStrNome() );?>">
        </div>

        <div class="clear-margin-2"></div>

        <div class="grid grid_7-6">
            <label id="lblFuncionalidade" for="selFuncionalidade" class="infraLabelObrigatorio">
                Funcionalidade:
            </label>
            <select id="selFuncionalidade" name="selFuncionalidade" onchange="apagarOperacao()">
                <?= $comboFuncionalidade?>
            </select>
        </div>
        <div class="clear"></div>
        <div style="margin-top: 13px;"  class="grid grid_10-1">
            <label id="lblEnderecoWsdl" for="txtEnderecoWsdl" class="infraLabelObrigatorio">
                Endere�o WSDL:
            </label>

            <input type="text" id="txtEnderecoWsdl" onkeypress="return infraMascaraTexto(this,event,100);" name="txtEnderecoWsdl" onchange="apagarOperacao()" value="<?= PaginaSEI::tratarHTML( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() );?>">

            <div class="grid grid_2" style="margin-left: 31px;">
                <button class="infraButton" name="btnValidar" id="btnValidar" onclick="validarWsdl()" type="button">Validar </button>
            </div>
        </div>
        <div class="clear"></div>

        <div class="grid grid_11" id="gridOperacao">
            <div style="margin-top: 13px;"  class="grid grid_10-1">
                <label id="lblOperacao" for="selOperacao" class="infraLabelObrigatorio">
                    Opera��o:
                </label>

                <select type="text" id="selOperacao" name="selOperacao" onchange="apagarMapear()">
                </select>

                <div class="grid grid_2" sytle="margin-top: -38px;margin-left: 491px;">
                    <span <?= PaginaSEI::montarTitleTooltip($strTolTipOperacao) ?> class="tooltipAjuda tooltipAjudaInput" id="tooltipOperacao"></span>
                    <button class="infraButton" name="btnMapear" id="btnMapear"  type="button" onclick="mapear()">Mapear </button>
                </div>
            </div>
        </div>


        <?
        PaginaSEI::getInstance()->montarAreaTabela($arrMontarTabelaParamEntrada['strResultado'], $arrMontarTabelaParamEntrada['numRegistros']);
        ?>

        <?
        PaginaSEI::getInstance()->montarAreaTabela($arrMontarTabelaParamSaida['strResultado'], $arrMontarTabelaParamSaida['numRegistros']);
        ?>
        <input type="hidden" id="hdnIdMdLitIntegracao" name="hdnIdMdLitIntegracao" value="<?= $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao() ?>"/>
        <input type="hidden" id="hdnAcaoIntegracao" name="hdnAcaoIntegracao" value=""/>

    </form>
<?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
<?php PaginaSEI::getInstance()->fecharBody(); ?>
<?php PaginaSEI::getInstance()->fecharHtml(); ?>