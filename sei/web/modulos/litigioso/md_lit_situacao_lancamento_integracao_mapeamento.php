<?php

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();

//Botões de ação do topo
$arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" onclick="salvar()" class="infraButton">
                                    <span class="infraTeclaAtalho">S</span>alvar
                              </button>';

switch ($_GET['acao']) {

//region Mapear
    case 'md_lit_situacao_lancamento_integracao_mapear':
        $strTitulo = 'Mapeamento de Campos';
        try {
            $objMdLitSoapClientRN = new MdLitSoapClienteRN($_POST['txtEnderecoWsdl']);
            $objMdLitSituacaoLancamIntDTO = null;
            if($_GET['id_md_lit_situacao_lancamento_integracao']){
                $objMdLitSituacaoLancamIntDTO = new MdLitSituacaoLancamIntDTO();
                $objMdLitSituacaoLancamIntDTO->retTodos();
                $objMdLitSituacaoLancamIntDTO->setNumIdMdLitSituacaoLancamInt($_GET['id_md_lit_situacao_lancamento_integracao']);

                $objMdLitSituacaoLancamIntRN = new MdLitSituacaoLancamIntRN();
                $objMdLitSituacaoLancamIntDTO = $objMdLitSituacaoLancamIntRN->consultar($objMdLitSituacaoLancamIntDTO);
            }

            $arrMontarTabelaParamSaida = MdLitSituacaoLancamIntINT::montarTabelaParamSaida($objMdLitSoapClientRN, $_POST['txtOperacao'], $objMdLitSituacaoLancamIntDTO );
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
    //removerDisable();
}

function cancelar(){
    window.close();
}
function validarCadastro(){
    var rdChaveUnicaDadosVazio = true;

    for(var i = 0; i < (document.getElementById('tableParametroSaida').rows.length-1); i++ ){
        if (document.getElementById('campoDestino_'+i).value == '' ) {
            alert('Faltou selecionar uma opção da combo na tabela de dados de saída.');
            document.getElementById('campoDestino_' + i).focus();
            return false;
        }

        if(document.getElementById('chaveUnicaDadosSaida_'+i).checked){
            rdChaveUnicaDadosVazio = false;
        }
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
                name: selectCampo.value,
                value:selectCampo.name.match(/\[(.*)\]/).pop(),
                chaveUnica:rdChaveUnica.checked
            });

        }
    }

    window.top.hdnMapeamentoJson.value = JSON.stringify(jsonArr);
    window.top.hdnSalvarSituacao.remove();
    window.top.frmSituacaoCadastro.submit();
    window.close();
}


function mudarcampoDestino(element){
    //foi adicionado o return abaixo de forma paliativa, pois poderar ser utilizado, novamente, no futuro o conteudo
    //abaixo
    return true;
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
<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
    <div class="clear">&nbsp;</div>

    <?
    PaginaSEI::getInstance()->montarAreaTabela($arrMontarTabelaParamSaida['strResultado'], $arrMontarTabelaParamSaida['numRegistros']);
    ?>

</div>

<?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
<?php PaginaSEI::getInstance()->fecharBody(); ?>
<?php PaginaSEI::getInstance()->fecharHtml(); ?>

