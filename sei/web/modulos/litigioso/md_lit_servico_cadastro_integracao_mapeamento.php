<?php

/**
 * @since  01/03/2017
 * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();


//Botões de ação do topo
$arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" onclick="salvar()" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';
$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" onclick="cancelar()" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                              </button>';


switch ($_GET['acao']) {

//region Mapear
    case 'md_lit_servico_integracao_mapear':
        $strTitulo = 'Mapeamento de Campos';
        try {
            $objMdLitSoapClientRN = new MdLitSoapClienteRN($_POST['txtEnderecoWsdl'], 'wsdl');
            $objMdLitSoapClientRN->setSoapVersion($_POST['versaoSoap']);
            $objMdLitServicoIntegracaoDTO = null;
            if($_GET['id_md_lit_servico_integracao']){
                $objMdLitServicoIntegracaoDTO = new MdLitServicoIntegracaoDTO();
                $objMdLitServicoIntegracaoDTO->retTodos();
                $objMdLitServicoIntegracaoDTO->setNumIdMdLitServicoIntegracao($_GET['id_md_lit_servico_integracao']);

                $objMdLitServicoIntegracaoRN = new MdLitServicoIntegracaoRN();
                $objMdLitServicoIntegracaoDTO = $objMdLitServicoIntegracaoRN->consultar($objMdLitServicoIntegracaoDTO);
            }

            $arrMontarTabelaParamSaida = MdLitServicoIntegracaoINT::montarTabelaParamSaida($objMdLitSoapClientRN, $_POST['txtOperacao'], $objMdLitServicoIntegracaoDTO );
        }catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }

        break;
    //endregion


    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
        break;
    //endregion
}

PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

require_once 'md_lit_css_geral.php';?>
table td select{font-size: 1.0em;}
<?php
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script><?}?>
function inicializar(){
    removerDisable();
}

function cancelar(){
    window.close();
}
function validarCadastro(){
    var selectcampoDestinoVazio = true;
    var rdChaveUnicaDadosVazio = true;

    for(var i = 0; i < (document.getElementById('tableParametroSaida').rows.length-1); i++ ){
        if(document.getElementById('campoDestino_'+i).value != ''){
            selectcampoDestinoVazio = false;
        }
        if(document.getElementById('chaveUnicaDadosSaida_'+i).checked){
            rdChaveUnicaDadosVazio = false;
        }
    }

    if(selectcampoDestinoVazio){
        alert('Informe ao menos um campo de destino no SEI na tabela de dados de saída.');
        document.getElementById('tableParametroSaida').scrollIntoView()
        return false;
    }
    if(rdChaveUnicaDadosVazio){
        alert('Informe ao menos uma chave única da integração na tabela de dados de saída.');
        document.getElementById('tableParametroSaida').scrollIntoView()
        return false;
    }
    return true;
}
function salvar(){

    ret = validarCadastro();
    if(!ret)
        return ret;

    var jsonArr = [];
    for (var i = 0; i < document.getElementById('tableParametroSaida').rows.length -1; i++){
        var selectCampo = document.getElementById('campoDestino_'+(i));
        if(selectCampo.value != ''){
            var rdChaveUnica = document.getElementById('chaveUnicaDadosSaida_'+i);
            jsonArr.push({
                name: selectCampo.name.match(/\[(.*)\]/).pop(),
                value:selectCampo.value,
                chaveUnica:rdChaveUnica.checked
            });

        }
    }
    window.opener.hdnMapeamentoJson.value = JSON.stringify(jsonArr);
    window.opener.hdnSalvarServico.remove();
    window.opener.frmServicoCadastro.submit();
    window.close();
}


function mudarcampoDestino(element){
    if(element.value != ''){
        var row = document.getElementById('tableParametroSaida').rows[element.parentNode.parentNode.rowIndex];
        row.cells[2].children[0].children[0].disabled = false;
        for (var i = 0; i < document.getElementById('tableParametroSaida').rows.length; i++){
            if(i != 0 && i != element.parentNode.parentNode.rowIndex){
                var select = document.getElementById('campoDestino_'+(i-1));
                for(var j=0;j<select.options.length;j++ ){
                    if(select.value == element.value){
                        select.value = '';
                        mudarcampoDestino(select);
                        break;
                    }
                }
            }
        }

    }else{
        var row = document.getElementById('tableParametroSaida').rows[element.parentNode.parentNode.rowIndex];
        row.cells[2].children[0].children[0].disabled = true;
        row.cells[2].children[0].children[0].checked = false;
    }
}

function removerDisable(){
    for (var i = 0; i < document.getElementById('tableParametroSaida').rows.length-1; i++){
        var select = document.getElementById('campoDestino_'+(i));
        if(select.value != ''){
            document.getElementById('chaveUnicaDadosSaida_'+(i)).disabled = false;
        }
    }
}
<?if(0){?></script><?}?>
<?php
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

PaginaSEI::getInstance()->abrirAreaDados('');
?>
<div class="clear-margin-3"></div>
<?php $strTolTipEnd = 'O Sistema correspondente ao WSDL indicado deve estar previamente cadastrado no menu Administração > Sistemas e com Serviço também cadastrado com os IPs pertinentes.' ?>
<?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
<div class="clear">&nbsp;</div>

<?
PaginaSEI::getInstance()->montarAreaTabela($arrMontarTabelaParamSaida['strResultado'], $arrMontarTabelaParamSaida['numRegistros']);
?>


<?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
<?php PaginaSEI::getInstance()->fecharBody(); ?>
<?php PaginaSEI::getInstance()->fecharHtml(); ?>

