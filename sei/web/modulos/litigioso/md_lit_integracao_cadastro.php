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
$strTolTipOperacao = 'a descrição do texto de ajuda será elaborado após o serviço estar disponível no barramento, pois teremos verificado todas as validações necessárias';

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
        $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao(null);

        $objMdLitIntegracaoDTO->setStrSinVincularLancamento('N');
        if(isset($_POST['chkSinVincularLancamento'])){
            $objMdLitIntegracaoDTO->setStrSinVincularLancamento('S');
        }
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

                $objMdLitIntegracaoDTO->setStrSinVincularLancamento('N');
                if(isset($_POST['chkSinVincularLancamento'])){
                    $objMdLitIntegracaoDTO->setStrSinVincularLancamento('S');
                }

                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamEntradaDTO($arrObjMdLitMapearParamEntradaDTO);
                $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamSaidaDTO($arrObjMdLitMapearParamSaidaDTO);

                $objMdLitIntegracaoRN->cadastrar($objMdLitIntegracaoDTO);

                PaginaSEI::getInstance()->adicionarMensagem('Integração "'.$objMdLitIntegracaoDTO->getStrNome().'" cadastrado com sucesso.');
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_md_lit_integracao='.$objMdLitIntegracaoDTO->getNumIdMdLitIntegracao().PaginaSEI::getInstance()->montarAncora($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao())));
                die;

            } catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
        }
        break;

    case 'md_lit_integracao_alterar':
        $strTitulo = 'Alterar Integração';
        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoRN  = new MdLitIntegracaoRN();

        $objMdLitIntegracaoDTO->setStrNome($_POST['txtNome']);
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($_POST['selFuncionalidade']);
        $objMdLitIntegracaoDTO->setStrEnderecoWsdl($_POST['txtEnderecoWsdl']);
        $objMdLitIntegracaoDTO->setStrOperacaWsdl($_POST['selOperacao']);
        $objMdLitIntegracaoDTO->setNumIdMdLitIntegracao($_POST['hdnIdMdLitIntegracao']);
        $objMdLitIntegracaoDTO->setStrSinVincularLancamento('N');
        if(isset($_POST['chkSinVincularLancamento'])){
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
                $objMdLitIntegracaoDTO->setStrSinVincularLancamento('N');
                if(isset($_POST['chkSinVincularLancamento'])){
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
        $strTitulo     = 'Consultar Integração';
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
            throw new InfraException("Registro não encontrado.");
        }
        break;

    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        break;
}
if(!empty($objMdLitIntegracaoDTO->getStrEnderecoWsdl()) && !empty($objMdLitIntegracaoDTO->getStrOperacaWsdl()) && !empty($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade())){
    $objMdLitSoapClienteRN = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(), 'wsdl');
    try {
        $arrMontarTabelaParamEntrada = MdLitMapearParamEntradaINT::montarTabelaParamEntrada($objMdLitSoapClienteRN, $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade(), $objMdLitIntegracaoDTO->getStrOperacaWsdl(), $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
        $arrMontarTabelaParamSaida = MdLitMapearParamSaidaINT::montarTabelaParamSaida($objMdLitSoapClienteRN, $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade(), $objMdLitIntegracaoDTO->getStrOperacaWsdl(), $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
    }catch (Exception $e){
        $exception = new InfraException();
        $exception->adicionarValidacao('Não foi possível carregar o web-service.');
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
            alert('Tamanho do campo excedido (máximo 30 caracteres).');
            document.getElementById('txtNome').focus();
            return false;
        }
        if (infraTrim(document.getElementById('selFuncionalidade').value) == 'null') {
            alert('Selecione a funcionalidade.');
            document.getElementById('selFuncionalidade').focus();
            return false;
        }
        if (infraTrim(document.getElementById('txtEnderecoWsdl').value) == '') {
            alert('Informe o endereço WSDL.');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }
        if (infraTrim(document.getElementById('selOperacao').value) == '') {
            alert('Selecione a operação.');
            document.getElementById('selOperacao').focus();
            return false;
        }
        if(document.getElementById('tableParametroEntrada') == null && document.getElementById('tableParametroSaida') == null){
            alert('Favor mapear a integração.');
            document.getElementById('btnMapear').focus();
            return false;
        }
        

        //Gerar Número Fistel
        if(document.getElementById('selFuncionalidade').value == 10){
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosEntrada.push(value.value)
                }
            });

            
            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if(!infraInArray(77,valoresDadosSaida)){
                msgSaida += "- Número de Complemento do Interessado\n";
            }

            

           //Saida
            if(msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }
           
             var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
            if (rdChaveUnicaDadosSaidaVazio) {
                alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }

            return true;
        }
        //Fim Gerar Número Fistel



        //Cancelar Lançamento
        if(document.getElementById('selFuncionalidade').value == 4){
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if(!infraInArray(28,valoresDadosEntrada)){
                msgEntrada += "- Motivo de Cancelamento\n";
            }
            if(!infraInArray(26,valoresDadosEntrada)){
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(27,valoresDadosEntrada)){
                msgEntrada += "- Sequencial\n";
            }
            
            
            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if(!infraInArray(33,valoresDadosSaida)){
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(34,valoresDadosSaida)){
                msgSaida += "- Sequencial\n";
            }

            //Entrada e Saída
            if(msgEntrada != '' && msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" +msgEntrada+" \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            //Entrada
            if(msgEntrada != ''){
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgEntrada);
                return false;
            }

           //Saida
            if(msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

             if(chaveIntegracaoValidacao() == false){
               return false;
           }

            return true;
        }
        //Fim Cancelar Lançamento



        //Cancelar Recurso
        if(document.getElementById('selFuncionalidade').value == 8){
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if(!infraInArray(68,valoresDadosEntrada)){
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(70,valoresDadosEntrada)){
                msgEntrada += "- Observação\n";
            }
            if(!infraInArray(69,valoresDadosEntrada)){
                msgEntrada += "- Sequencial\n";
            }
            
            
            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if(!infraInArray(72,valoresDadosSaida)){
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(73,valoresDadosSaida)){
                msgSaida += "- Sequencial\n";
            }
           
            //Entrada e Saída
            if(msgEntrada != '' && msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" +msgEntrada+" \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            //Entrada
            if(msgEntrada != ''){
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgEntrada);
                return false;
            }

           //Saida
            if(msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

             if(chaveIntegracaoValidacao() == false){
               return false;
           }

            return true;
        }
        //Fim Cancelar Recurso


        //Denegar Recurso
        if(document.getElementById('selFuncionalidade').value == 7){
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if(!infraInArray(62,valoresDadosEntrada)){
                msgEntrada += "- Data Denegação\n";
            }
            if(!infraInArray(60,valoresDadosEntrada)){
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(61,valoresDadosEntrada)){
                msgEntrada += "- Sequencial\n";
            }
            
           

            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if(!infraInArray(66,valoresDadosSaida)){
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(67,valoresDadosSaida)){
                msgSaida += "- Sequencial\n";
            }
           
            //Entrada e Saída
            if(msgEntrada != '' && msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" +msgEntrada+" \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            //Entrada
            if(msgEntrada != ''){
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgEntrada);
                return false;
            }

           //Saida
            if(msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

             if(chaveIntegracaoValidacao() == false){
               return false;
           }
           
            return true;
        }
        //Fim Denegar Recurso



        //Incluir Lançamento
        if(document.getElementById('selFuncionalidade').value == 2){
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if(!infraInArray(78,valoresDadosEntrada)){
                msgEntrada += "- CNPJ/CPF\n";
            }
            if(!infraInArray(2,valoresDadosEntrada)){
                msgEntrada += "- Data de aplicação da Sanção\n";
            }
            if(!infraInArray(3,valoresDadosEntrada)){
                msgEntrada += "- Data de Vencimento\n";
            }
            if(!infraInArray(1,valoresDadosEntrada)){
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(6,valoresDadosEntrada)){
                msgEntrada += "- Número do Processo\n";
            }
            if(!infraInArray(4,valoresDadosEntrada)){
                msgEntrada += "- Valor Total\n";
            }
            
           
            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if(!infraInArray(12,valoresDadosSaida)){
                msgSaida += "- Link para Boleto\n";
            }
            if(!infraInArray(14,valoresDadosSaida)){
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(11,valoresDadosSaida)){
                msgSaida += "- Sequencial\n";
            }

            //Entrada e Saída
            if(msgEntrada != '' && msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" +msgEntrada+" \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            //Entrada
            if(msgEntrada != ''){
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgEntrada);
                return false;
            }

           //Saida
            if(msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

         if(chaveIntegracaoValidacao() == false){
               return false;
           }
           
            return true;
        }
        //Fim Incluir Lançamento


        //Motivo Cancelamento
        if(document.getElementById('selFuncionalidade').value == 9){
            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msg = '';
            if(!infraInArray(75,valoresDadosSaida)){
                msg += "- Descrição Motivo\n";
            }
            if(!infraInArray(74,valoresDadosSaida)){
                msg += "- ID Motivo\n";
            }
            
            if(msg != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msg);
                return false;
            }

             var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
            if (rdChaveUnicaDadosSaidaVazio) {
                alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }

            return true;
        }
        //Fim Motivo Cancelamento


        
        //Retificação do Lançamento
        if(document.getElementById('selFuncionalidade').value == 5){
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if(!infraInArray(41,valoresDadosEntrada)){
                msgEntrada += "- Data da Constituição\n";
            }
            if(!infraInArray(37,valoresDadosEntrada)){
                msgEntrada += "- Data de Aplicação da Sanção\n";
            }
            if(!infraInArray(38,valoresDadosEntrada)){
                msgEntrada += "- Data de Vencimento\n";
            }
            if(!infraInArray(43,valoresDadosEntrada)){
                msgEntrada += "- Justificativa Lançamento\n";
            }
            if(!infraInArray(36,valoresDadosEntrada)){
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(42,valoresDadosEntrada)){
                msgEntrada += "- Número do Processo\n";
            }
            if(!infraInArray(47,valoresDadosEntrada)){
                msgEntrada += "- Sequencial\n";
            }
            if(!infraInArray(39,valoresDadosEntrada)){
                msgEntrada += "- Valor Total\n";
            }
            
            

            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if(!infraInArray(51,valoresDadosSaida)){
                msgSaida += "- Link para Boleto\n";
            }
            if(!infraInArray(50,valoresDadosSaida)){
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(49,valoresDadosSaida)){
                msgSaida += "- Sequencial\n";
            }
           
            //Entrada e Saída
            if(msgEntrada != '' && msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" +msgEntrada+" \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            //Entrada
            if(msgEntrada != ''){
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgEntrada);
                return false;
            }

           //Saida
            if(msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            if(chaveIntegracaoValidacao() == false){
               return false;
           }
            
            return true;
        }
        //Fim Retificação do Lançamento



        //suspender Lançamento
        if(document.getElementById('selFuncionalidade').value == 6){
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if(!infraInArray(57,valoresDadosEntrada)){
                msgEntrada += "- Data da Suspensão\n";
            }
            if(!infraInArray(52,valoresDadosEntrada)){
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(53,valoresDadosEntrada)){
                msgEntrada += "- Sequencial\n";
            }
            
            
            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if(!infraInArray(58,valoresDadosSaida)){
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(59,valoresDadosSaida)){
                msgSaida += "- Sequencial\n";
            }
            
            //Entrada e Saída
            if(msgEntrada != '' && msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" +msgEntrada+" \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            //Entrada
            if(msgEntrada != ''){
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgEntrada);
                return false;
            }

           //Saida
            if(msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

           if(chaveIntegracaoValidacao() == false){
               return false;
           }

            return true;
        }
        //Fim Suspender





        //Consultar Lançamento
        if(document.getElementById('selFuncionalidade').value == 3){
            var valoresDadosEntrada = [];
            $('[name^="nomeFuncionalDadosEntrada"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosEntrada.push(value.value)
                }
            });

            var msgEntrada = '';
            if(!infraInArray(15,valoresDadosEntrada)){
                msgEntrada += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(16,valoresDadosEntrada)){
                msgEntrada += "- Sequencial\n";
            }

            

            var valoresDadosSaida = [];
            $('[name^="nomeFuncionalDadosSaida"]').each(function(key,value){
                if(value.value !='null'){
                    valoresDadosSaida.push(value.value)
                }
            });

            var msgSaida = '';
            if(!infraInArray(84,valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked){
                msgSaida += "- Código da Receita\n";
            }
            if(!infraInArray(85,valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked){
                msgSaida += "- Data Constituição Definitiva\n";
            }
            if(!infraInArray(80,valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked){
                msgSaida += "- Data da Decisão de Aplicação da Multa\n";
            }
            if(!infraInArray(81,valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked){
                msgSaida += "- Data de Vencimento\n";
            }
            if(!infraInArray(21,valoresDadosSaida)){
                msgSaida += "- Data do Último Pagamento\n";
            }
            if(!infraInArray(83,valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked){
                msgSaida += "- Identificação do Lançamento\n";
            }
            if(!infraInArray(24,valoresDadosSaida)){
                msgSaida += "- Link para Boleto\n";
            }
            if(!infraInArray(82,valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked){
                msgSaida += "- Número de Complemento do Interessado\n";
            }
            if(!infraInArray(18,valoresDadosSaida)){
                msgSaida += "- Situação Lançamento\n";
            }
            if(!infraInArray(79,valoresDadosSaida) && document.getElementById('chkSinVincularLancamento').checked){
                msgSaida += "- Valor da Receita Inicial\n";
            }
            if(!infraInArray(25,valoresDadosSaida)){
                msgSaida += "- Valor Atualizado\n";
            }
            if(!infraInArray(23,valoresDadosSaida)){
                msgSaida += "- Valor do Desconto\n";
            }
            if(!infraInArray(22,valoresDadosSaida)){
                msgSaida += "- Valor Total do Pagamento\n";
            }

            //Entrada e Saída
            if(msgEntrada != '' && msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n" +msgEntrada+" \n O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            //Entrada
            if(msgEntrada != ''){
                alert("O campo de Origem no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgEntrada);
                return false;
            }

           //Saida
            if(msgSaida != ''){
                alert("O campo de Destino no SEI abaixo listados são de Mapeamento obrigatório : \n "+msgSaida);
                return false;
            }

            
           if(chaveIntegracaoValidacao() == false){
               return false;
           }

            return true;
        }else if(document.getElementById('selFuncionalidade').value == 1) {
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
                alert('Informe ao menos uma chave única da integração na tabela de dados de entrada.');
                document.getElementById('tableParametroEntrada').scrollIntoView()
                return false;
            }
            if (selectNomeFuncionalDadosSaidaVazio) {
                alert('Informe ao menos um campo de destino no SEI na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }
            if (rdChaveUnicaDadosSaidaVazio) {
                alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
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
                        alert('Todos os campos do parâmetro de entrada devem ser mapeados');
                        document.getElementById('tableParametroEntrada').scrollIntoView()
                        return false;
                    }
                }

                var rdChaveUnicaDadosEntradaVazio = $('[name="chaveUnicaDadosEntrada"]:checked').length == 0;
                if (rdChaveUnicaDadosEntradaVazio) {
                    alert('Informe ao menos uma chave única da integração na tabela de dados de entrada.');
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
                        alert('Todos os campos do parâmetro de saida devem ser mapeados');
                        document.getElementById('tableParametroSaida').scrollIntoView()
                        return false;
                    }
                }
                var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
                if (rdChaveUnicaDadosSaidaVazio) {
                    alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
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

    function chaveIntegracaoValidacao(){
        var total = $('[name^="nomeFuncionalDadosEntrada"]')[0].options.length - 1;

        var rdChaveUnicaDadosEntradaVazio = $('[name="chaveUnicaDadosEntrada"]:checked').length == 0;
        if (rdChaveUnicaDadosEntradaVazio) {
            alert('Informe ao menos uma chave única da integração na tabela de dados de entrada.');
            document.getElementById('tableParametroEntrada').scrollIntoView()
            return false;
        }

        var rdChaveUnicaDadosSaidaVazio = $('[name="chaveUnicaDadosSaida"]:checked').length == 0;
            if (rdChaveUnicaDadosSaidaVazio) {
                alert('Informe ao menos uma chave única da integração na tabela de dados de saida.');
                document.getElementById('tableParametroSaida').scrollIntoView()
                return false;
            }

    }

    function validarWsdl(){
        var enderecoWsdl = document.getElementById('txtEnderecoWsdl').value;
        if(enderecoWsdl == ''){
            alert('Preenche o campo Endereço WSDL.');
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
            alert('Informe o Endereço do WSDL.');
            document.getElementById('txtEnderecoWsdl').focus();
            return false;
        }

        if (infraTrim(document.getElementById('selFuncionalidade').value) == 'null') {
            alert('Informe a Funcionalidade.');
            document.getElementById('selFuncionalidade').focus();
            return false;
        }

        if (infraTrim(document.getElementById('selOperacao').value) == '') {
            alert('Informe a Operação.');
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
        document.getElementById('divSinVincularLancamento').style.display = 'none';
        document.getElementById('chkSinVincularLancamento').checked = false;

        if(document.getElementById('selFuncionalidade').value == '<?php echo MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO?>'){
            document.getElementById('divSinVincularLancamento').style.display = '';
        }
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
                Endereço WSDL:
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
                    Operação:
                </label>

                <select type="text" id="selOperacao" name="selOperacao" onchange="apagarMapear()">
                </select>

                <div class="grid grid_2" sytle="margin-top: -38px;margin-left: 491px;">
                    <span <?= PaginaSEI::montarTitleTooltip($strTolTipOperacao) ?> class="tooltipAjuda tooltipAjudaInput" id="tooltipOperacao"></span>
                    <button class="infraButton" name="btnMapear" id="btnMapear"  type="button" onclick="mapear()">Mapear </button>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="grid grid_11 infraDivRadio" style="margin-top: 13px;display:<?php echo $objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade() == MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO ? '' : 'none'?>;" id="divSinVincularLancamento" >
            <input type="checkbox" name="chkSinVincularLancamento" id="chkSinVincularLancamento" class="infraCheckbox" value="S" <?php echo $objMdLitIntegracaoDTO && $objMdLitIntegracaoDTO->getStrSinVincularLancamento() == 'S'? 'checked="checked"': '' ?>>
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
        <input type="hidden" id="hdnIdMdLitIntegracao" name="hdnIdMdLitIntegracao" value="<?= $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao() ?>"/>
        <input type="hidden" id="hdnAcaoIntegracao" name="hdnAcaoIntegracao" value=""/>

    </form>
<?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
<?php PaginaSEI::getInstance()->fecharBody(); ?>
<?php PaginaSEI::getInstance()->fecharHtml(); ?>