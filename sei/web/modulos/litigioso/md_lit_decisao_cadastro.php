<?
/**
 * ANATEL
 *
 * 11/07/2017 - criado por ellyson.silva - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();
    SessaoSEI::getInstance()->validarLink();

    $strLinkAjaxComboEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_especie_decisao_montar_select');
    $strLinkAjaxEspecieDecisao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_carregar_especie_decisao');
//    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    //////////////////////////////////////////////////////////////////////////////
//    InfraDebug::getInstance()->setBolLigado(false);
//    InfraDebug::getInstance()->setBolDebugInfra(false);
//    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    $strParametros = '';
    if (isset($_GET['arvore'])) {
        PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
        $strParametros .= '&arvore=' . $_GET['arvore'];
    }

    $arrComandos        = array();
    $idMdLitControle    = null;
    $bolCadastro        = false;
    $arrTabela          = array();
    $bolHouveMudanca    = true;
    $idUltimaSituacaoDecisoria = null;

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    switch ($_GET['acao']) {
        case 'md_lit_decisao_cadastrar':
            $strTitulo = 'Cadastro de decisões';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarDecisao" id="sbmCadastrarDecisao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar decisões</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
            $idMdLitControle = $_GET['id_md_lit_controle'];

            $objMdLitControleDTO = new MdLitControleDTO();
            $objMdLitControleDTO->retTodos(false);
            $objMdLitControleDTO->setNumIdControleLitigioso($idMdLitControle);

            $objMdLitControleRN = new MdLitControleRN();
            $objMdLitControleDTO = $objMdLitControleRN->consultar($objMdLitControleDTO);


            $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retTodos();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrDispositivo();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrNorma();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retNumIdCondutaLitigioso();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrConduta();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdControleLitigioso($idMdLitControle);

            $objRelDispositivoNormativoCondutaControleLitigiosoRN     = new MdLitRelDispositivoNormativoCondutaControleRN();
            $arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO = $objRelDispositivoNormativoCondutaControleLitigiosoRN->listar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

            if(count($_POST)){
                $arrTabela = MdLitDecisaoINT::gerarItensTabelaDinamicaForm($_POST['decisao']);
                $hdnTbDecisaoAntigo = $_POST['hdnTbDecisaoAntigo'];
                $bolCadastro = true;
                if($hdnTbDecisaoAntigo != '' ){
                    $arrTbDecisaoAntigo = PaginaSEI::getInstance()->getArrItensTabelaDinamica($hdnTbDecisaoAntigo);
                    $arrTbDecisaoNovo = PaginaSEI::getInstance()->getArrItensTabelaDinamica($arrTabela);

                    if(count($arrTbDecisaoAntigo) == count($arrTbDecisaoNovo))
                        $bolHouveMudanca = false;

                    if(!$bolHouveMudanca){
                        for($i = 0; $i < count($arrTbDecisaoAntigo); $i++){
                            if($arrTbDecisaoAntigo[$i][1] != $arrTbDecisaoNovo[$i][1] ||
                                $arrTbDecisaoAntigo[$i][2] != $arrTbDecisaoNovo[$i][2] ||
                                $arrTbDecisaoAntigo[$i][3] != $arrTbDecisaoNovo[$i][3] ||
                                $arrTbDecisaoAntigo[$i][4] != $arrTbDecisaoNovo[$i][4] ||
                                $arrTbDecisaoAntigo[$i][5] != $arrTbDecisaoNovo[$i][5] ||
                                $arrTbDecisaoAntigo[$i][6] != $arrTbDecisaoNovo[$i][6])
                                $bolHouveMudanca = true;
                        }
                    }
                }
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}
$strTbCadastroDecisao = '';
$numRegistros = count($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO);
if($numRegistros > 0){

    $strCaptionTabela = 'Lista de Infrações selecionadas';

    $strTbCadastroDecisao .= '<table width="100%" id="tableDadosComplementarInteressado" class="infraTable" summary="' . $strCaptionTabela . '">' . "\n";
    $strTbCadastroDecisao .= '<caption class="infraCaption" style="display: none">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strTbCadastroDecisao .= '<tr>';

    $strTbCadastroDecisao .= '<th class="infraTh" width="20%">&nbsp;Infração&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh" width="20%">&nbsp;Tipo de decisão&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh" width="20%">&nbsp;Espécie de decisão&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh" width="10%">&nbsp;Multa&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh" width="20%">&nbsp;Obrigação&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '<th class="infraTh" width="5%">&nbsp;Prazo&nbsp;</th>' . "\n";
    $strTbCadastroDecisao .= '</tr>' . "\n";
    $strComboTipoDecosao = MdLitTipoDecisaoINT::montarSelectTipoDecisaoPorTipoControle('null', '&nbsp;', '', $objMdLitControleDTO->getNumIdMdLitTipoControle());

    foreach ($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO as $idLinha =>$objRelDispositivoNormativoCondutaControleLitigiosoDTO){

        $idDispositivoNormativoNormaCondutaControle = $objRelDispositivoNormativoCondutaControleLitigiosoDTO->getNumIdDispositivoNormativoNormaCondutaControle();
        $strCssTr = '<tr id="CadastroDecisaoTable_' . $idLinha . '" class="infraTrClara">';
        $strTbCadastroDecisao .= $strCssTr;
        $strTbCadastroDecisao .= "<td id='td-infracao{$idLinha}'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][id]' value='{$idDispositivoNormativoNormaCondutaControle}' /> ";
        $strTbCadastroDecisao .= PaginaSEI::tratarHTML($objRelDispositivoNormativoCondutaControleLitigiosoDTO->getStrInfracao());
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][id_decisao]' value='' /> ";
        $strTbCadastroDecisao .= '<img id="img_mais_'.$idLinha.'"  value="'.$idDispositivoNormativoNormaCondutaControle.'"  nome-linha="idDispositivoNormativo_'.$idLinha.'" src="/infra_css/imagens/mais.gif" title="adicionar Item" class="infraImg" style="width: 14px; height: 14px;" onclick="incluirLinha(this)">';
        $strTbCadastroDecisao .= '<img id="img__menos_'.$idLinha.'" src="/infra_css/imagens/menos.gif" title="remover Item" class="infraImg" style="width: 14px; height: 14px;display:none" onclick="removerLinha(this)">';
        $strTbCadastroDecisao .= "</td>";
        $strTbCadastroDecisao .= "<td align='center'>";
        $strTbCadastroDecisao .= "<select id='tipo_decisao_{$idLinha}' name='decisao[idDispositivoNormativo_{$idLinha}][id_md_lit_tipo_decisao]' style='width: 100%;' onchange='carregarComboEspecieDecisao(this)'>";
        $strTbCadastroDecisao .= $strComboTipoDecosao;
        $strTbCadastroDecisao .= "</select></td>";
        $strTbCadastroDecisao .= "<td align='center'><select id='id_md_lit_especie_decisao_{$idLinha}' name='decisao[idDispositivoNormativo_{$idLinha}][id_md_lit_especie_decisao]' onchange='carregarEspecieDecisao(this)' style='width: 100%;display: none'></select></td>";
        $strTbCadastroDecisao .= "<td align='center'><input id='multa_{$idLinha}' onkeypress='return infraMascaraDinheiro(this,event,2,12);' type='text' name='decisao[idDispositivoNormativo_{$idLinha}][multa]' style='width: 90%;display: none'></td>";
        $strTbCadastroDecisao .= "<td align='center'><select id='id_md_lit_obrigacao_{$idLinha}' name='decisao[idDispositivoNormativo_{$idLinha}][id_md_lit_obrigacao]' style='width: 100%;display: none'></select></td>";
        $strTbCadastroDecisao .= "<td align='center'>";
        $strTbCadastroDecisao .= "<input id='prazo_{$idLinha}' type='text'  onkeypress='return infraMascaraNumero(this,event,16);' name='decisao[idDispositivoNormativo_{$idLinha}][prazo]' style='width: 90%;display: none'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][id_usuario]'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][id_unidade]'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][data]'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][nome_usuario]'>";
        $strTbCadastroDecisao .= "<input type='hidden' name='decisao[idDispositivoNormativo_{$idLinha}][sigla_unidade]'>";
        $strTbCadastroDecisao .= "</td>";

        $strTbCadastroDecisao .= "</tr> \n";

    }

    $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
    $objMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->buscarUltimaSituacaoDecisoria($_GET['id_procedimento']);

    if($objMdLitProcessoSituacaoDTO)
        $idUltimaSituacaoDecisoria = $objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao();

}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
//require_once ('md_lit_css_geral.php');
?>
p.bloco-orientacao{
    color: #000;
    margin: 0;
    line-height: 1.5em;
    font-size: 1.2em;
}
.margem-bottom10{margin-bottom: 10px !important;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if(0){?><script><?}?>
    var hdnTbDecisao = '';
    function inicializar(){

        <?if ($bolCadastro){ ?>

            var isMudanca = "<?= $bolHouveMudanca ?>";
            var valueNovo = '<?=$arrTabela?>';
            var obj = window.opener.document.getElementById('hdnTbDecisao');
            if(obj.value != ''){
                if(!isMudanca){
                    if(!confirm('Não ocorreu alteração das Decisões anteriores. A Situação Decisória atual mantém as Decisões anterior?')){
                        window.location = window.location.href;
                        return;
                    }
                }else{
                    if(!confirm('Ocorreram alterações das Decisões anteriores. Confirma alteração?')){
                        window.location = window.location.href;
                        return;
                    }
                }

            }

            obj.value = valueNovo;
            window.opener.objTabelaDinamicaDecisao.recarregar();
            window.opener.document.getElementById('tbDecisao').parentNode.style.display = '';
            window.opener.removerOptionVazio(window.opener.document.getElementById('selCreditosProcesso'));
            window.opener.consultarExtratoMulta();
            window.close();
            return;

        <?}?>
        hdnTbDecisao = window.opener.document.getElementById('hdnTbDecisao');
        if(hdnTbDecisao.value != ''){
            montaResultado();
        }

        if(document.getElementById('hdnIdUltimaSituacaoDecisoria').value == '' && (window.opener.document.getElementById('hdnErroSituacao').value == 1 || !window.opener.isTpSitDecisoria)){
            document.getElementById('sbmCadastrarDecisao').style.display = 'none';
            infraDesabilitarCamposDiv(document.getElementById('divInfraAreaGlobal'));
        }
    }

    function verificarSituacaoDecisaoNovo(){
        var arrSituacaoItens = window.opener.objTabelaDinamicaSituacao.obterItens();
        if(arrSituacaoItens.length > 0){
            for(var i = 0; i < arrSituacaoItens.length; i++ ){
                if(arrSituacaoItens[i][1] == 'N')
                    return true;
            }
        }
        return false;
    }

    function montaResultado(){
        var arrItens = window.opener.objTabelaDinamicaDecisao.obterItens();
        if(arrItens.length > 0){
            var idAnterior = 0;
            var isSituacaoDecisaoNovo = verificarSituacaoDecisaoNovo();
            document.getElementById('hdnTbDecisaoAntigo').value = hdnTbDecisao.value;
            for(var i = 0; i < arrItens.length; i++ ){
                var tamanhoTR = document.getElementById('tableDadosComplementarInteressado').rows.length;
                for (var j = 1;j < tamanhoTR; j++ ){
                    var table = document.getElementById('tableDadosComplementarInteressado');
                    if(arrItens[i][1] == table.rows[j].children[0].getElementsByTagName('input')[0].value){
                        if(idAnterior == arrItens[i][1]){

                            j = incluirLinha(table.rows[j].children[0].children[2]);
                            //combo tipo decisao
                            var selectTipoDecisao = table.rows[j].children[0].children[0];
                            selectTipoDecisao.value = arrItens[i][2];
                            // combo especie decisao
                            carregarComboEspecieDecisao(selectTipoDecisao, arrItens[i][3]);
                            //input multa
                            table.rows[j].children[2].children[0].value = arrItens[i][4];
                            //input prazo
                            table.rows[j].children[4].children[0].value = arrItens[i][6];
                            //combo obrigacao
                            table.rows[j].children[3].children[0].value = arrItens[i][5];
                            if(!isSituacaoDecisaoNovo){
                                //id_decisao hidden
                                table.rows[j].children[0].children[2].value = arrItens[i][0];
                                //input id_usuario
                                table.rows[j].children[4].children[1].value = arrItens[i][7];
                                //input id_unidade
                                table.rows[j].children[4].children[2].value = arrItens[i][8];
                                //input data
                                table.rows[j].children[4].children[3].value = arrItens[i][12];
                                //input nome_usuario
                                table.rows[j].children[4].children[4].value = arrItens[i][13];
                                //input sigla_unidade
                                table.rows[j].children[4].children[5].value = arrItens[i][14];
                            }
                        }else{
                            //combo tipo decisao
                            var selectTipoDecisao = table.rows[j].children[1].children[0];
                            selectTipoDecisao.value = arrItens[i][2];
                            // combo especie decisao
                            carregarComboEspecieDecisao(selectTipoDecisao, arrItens[i][3]);
                            //input multa
                            table.rows[j].children[3].children[0].value = arrItens[i][4];
                            //input prazo
                            table.rows[j].children[5].children[0].value = arrItens[i][6];
                            //combo obrigacao
                            table.rows[j].children[4].children[0].value = arrItens[i][5];
                            if(!isSituacaoDecisaoNovo){
                                //id_decisao hidden
                                table.rows[j].children[0].children[1].value = arrItens[i][0];
                                //input id_usuario
                                table.rows[j].children[5].children[1].value = arrItens[i][7];
                                //input id_unidade
                                table.rows[j].children[5].children[2].value = arrItens[i][8];
                                //input data
                                table.rows[j].children[5].children[3].value = arrItens[i][12];
                                //input nome_usuario
                                table.rows[j].children[5].children[4].value = arrItens[i][13];
                                //input sigla_unidade
                                table.rows[j].children[5].children[5].value = arrItens[i][14];
                            }
                        }
                        idAnterior = arrItens[i][1];
                        break;
                    }
                }

            }
        }
    }

    function carregarComboEspecieDecisao(element, valorSelecionado){
        var objSel, objMulta, objObrigacaoSelect, objPrazo;
        if(element.parentNode.parentNode.childNodes.length == 5){
            objSel              = element.parentNode.parentNode.childNodes[1].childNodes[0];
            objMulta            = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[4].childNodes[0];
        }else if(element.parentNode.parentNode.childNodes.length == 6){
            objSel              = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objMulta            = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[5].childNodes[0];
        }
        infraSelectLimpar(objObrigacaoSelect);
        infraSelectLimpar(objSel);
        objMulta.value = '';
        objPrazo.value = '';
        objMulta.style.display = 'none';
        objPrazo.style.display = 'none';
        objObrigacaoSelect.style.display = 'none';
        objSel.style.display = 'none';
        if(element.value != 'null'){
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxComboEspecieDecisao ?>",
                async: false,
                data: {id_md_lit_tipo_decisao: element.value},
                success: function (result) {
                    if($(result).find('erro').length > 0){
                        alert($(result).find('erro').attr('descricao'));
                        return;
                    }

                    infraSelectLimpar(objSel);
                    $.each($(result).find('option'), function(key, value){

                        var id = $(value).val();
                        var texto = $(value).text();
                        infraSelectAdicionarOption(objSel,texto,id);
                        if($(value).attr("selected")=="selected"){
                            infraSelectSelecionarItem(objSel,id);
                            carregarEspecieDecisao(objSel);
                        }else if(valorSelecionado == id){
                            infraSelectSelecionarItem(objSel,id);
                            carregarEspecieDecisao(objSel);
                        }

                    });
                    if(objSel.options.length > 0)
                        objSel.style.display = '';

                }
            });
        }else{
            infraSelectLimpar(objSel);
            objSel.style.display = 'none';
        }
    }

    function carregarEspecieDecisao(element){
        var objMulta            = null;
        var objObrigacaoSelect  = null;
        var objPrazo            = null;

        if(element.parentNode.parentNode.childNodes.length == 5){
            objMulta            = element.parentNode.parentNode.childNodes[2].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[4].childNodes[0];
        }else if(element.parentNode.parentNode.childNodes.length == 6){
            objMulta            = element.parentNode.parentNode.childNodes[3].childNodes[0];
            objObrigacaoSelect  = element.parentNode.parentNode.childNodes[4].childNodes[0];
            objPrazo            = element.parentNode.parentNode.childNodes[5].childNodes[0];
        }

        objMulta.style.display = 'none';
        objPrazo.style.display = 'none';
        objObrigacaoSelect.style.display = 'none';
        infraSelectLimpar(objObrigacaoSelect);
        if(element.value != 'null'){
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxEspecieDecisao ?>",
                dataType: "xml",
                async: false,
                data: {id_md_lit_especie_decisao: element.value},
                success: function (result) {
                    if($(result).find('erro').length > 0){
                        alert($(result).find('erro').attr('descricao'));
                        return;
                    }
                    if($(result).find('SinGestaoMulta').text() == 'S'){
                        objMulta.style.display = '';
                    }

                    if($(result).find('SinIndicacaoObrigacoes').text() == 'S'){
                        objObrigacaoSelect.style.display = '';
                    }
                    infraSelectLimpar(objObrigacaoSelect);
                    if($(result).find('SinIndicacaoPrazo').text() == 'S'){
                        infraSelectAdicionarOption(objObrigacaoSelect,'','null');
                        objPrazo.style.display = '';
                    }

                    $.each($(result).find('obrigacao'), function(key, value){

                        var id = $(this).find('IdObrigacaoLitigioso').text();
                        var texto = $(value).find('NomeObrigacao').text();
                        infraSelectAdicionarOption(objObrigacaoSelect,texto,id);
                        if($(value).attr("selected")=="selected"){
                            infraSelectSelecionarItem(objObrigacaoSelect,id);
                        }
                    });
                }
            });
        }
    }

    function incluirLinha(element){
        var table = document.getElementById("tableDadosComplementarInteressado");
        var nomeLinha = element.getAttribute('nome-linha');
        var valueId   = element.getAttribute('value');
        element.nextSibling.style.display = '';

        var row = table.insertRow(element.parentNode.parentNode.rowIndex + element.parentNode.rowSpan);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);

        element.parentNode.rowSpan += 1;
        row.className = 'infraTrClara';
        cell1.style.textAlign = 'center';
        cell2.style.textAlign = 'center';
        cell3.style.textAlign = 'center';
        cell4.style.textAlign = 'center';
        cell5.style.textAlign = 'center';
        nomeLinha += '.'+element.parentNode.rowSpan;

        var optionTipoDecisao = document.getElementById("tipo_decisao_0").innerHTML;
        var cell1Html = '';
        cell1Html += '<select name="decisao['+nomeLinha+'][id_md_lit_tipo_decisao]" onchange="carregarComboEspecieDecisao(this)" style="width: 100%">';
        cell1Html += optionTipoDecisao;

        cell1.innerHTML = cell1Html+'</select>'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id]" value="'+valueId+'">'+
            '<input type="hidden" name="decisao['+nomeLinha+'][id_decisao]" value="" /> ';
        cell2.innerHTML = '<select name="decisao['+nomeLinha+'][id_md_lit_especie_decisao]" onchange="carregarEspecieDecisao(this)" style="width: 100%;display: none"></select>';
        cell3.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][multa]" onkeypress="return infraMascaraDinheiro(this,event,2,12);" style="width: 90%;display: none">';
        cell4.innerHTML = '<select name="decisao['+nomeLinha+'][id_md_lit_obrigacao]" style="width: 100%;display: none"></select>';
        cell5.innerHTML = '<input type="text" name="decisao['+nomeLinha+'][prazo]" style="width: 90%;display: none" onkeypress="return infraMascaraNumero(this,event,16);">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][id_usuario]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][id_unidade]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][data]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][nome_usuario]">'+
                            '<input type="hidden" name="decisao['+nomeLinha+'][sigla_unidade]">';

        return row.rowIndex;
    }

    function removerLinha(element){
        var table = document.getElementById("tableDadosComplementarInteressado");
        var indexUltimaLinha = element.parentNode.rowSpan + element.parentNode.parentNode.rowIndex - 1;
        table.deleteRow(indexUltimaLinha);
        element.parentNode.rowSpan -= 1;
        if(element.parentNode.rowSpan < 2){
            element.style.display = 'none';
        }
    }

    function atualizarHdn(){

        var numRows=document.getElementById("tableDadosComplementarInteressado").rows.length;
        var cells,str=[],strRow, i,i2,numColunas=document.getElementById("tableDadosComplementarInteressado").rows[0].cells.length;
        for (i=1;i<numRows;i++){
            cells=document.getElementById("tableDadosComplementarInteressado").rows[i].getElementsByTagName('td');
            strRow=[];
            for (i2=0;i2<numColunas;i2++) {
                var input = cells[i2].getElementsByTagName('input').length > 0 ? cells[i2].getElementsByTagName('input') : cells[i2].getElementsByTagName('select');
                strRow.push(input[0].value);
            }
            strRow=strRow.join('±');
            str.push(strRow);
        }
    }

    function validar(){
        var numRows=document.getElementById("tableDadosComplementarInteressado").rows.length;
        var table = document.getElementById("tableDadosComplementarInteressado");
        var msg = '',infracao='', isPreenchido = false;

        for (var i=1;i<numRows;i++){
//             verificar se a linha da tabela foi mesclado e se é a primeira opção entra no 1°if.
            if(table.rows[i].cells.length == 6){
                infracao            = table.rows[i].cells[0].childNodes[1].textContent;
                var tipoDecisao     = table.rows[i].cells[1].childNodes[0];
                var especieDecisao  = table.rows[i].cells[2].childNodes[0];
                var multa           = table.rows[i].cells[3].childNodes[0];
                var obrigacao       = table.rows[i].cells[4].childNodes[0];
                var prazo           = table.rows[i].cells[5].childNodes[0];
            }else if(table.rows[i].cells.length == 5){
                var tipoDecisao     = table.rows[i].cells[0].childNodes[0];
                var especieDecisao  = table.rows[i].cells[1].childNodes[0];
                var multa           = table.rows[i].cells[2].childNodes[0];
                var obrigacao       = table.rows[i].cells[3].childNodes[0];
                var prazo           = table.rows[i].cells[4].childNodes[0];
            }
            if(tipoDecisao.value != 'null' && tipoDecisao.style.display == ''){

                if((especieDecisao.value == 'null' || especieDecisao.value == '') && especieDecisao.style.display == ''){
                    msg += "-  O especie decisão\n";
                }

                if((multa.value == 'null' || multa.value == '') && multa.style.display == ''){
                    msg += "-  A multa\n";
                }

                if((obrigacao.value == 'null' || obrigacao.value == '')  && obrigacao.style.display == ''){
                    msg += "-  A obrigação\n";
                }

                if((prazo.value == 'null' || prazo.value == '')  && prazo.style.display == ''){
                    msg += "-  O prazo\n";
                }
                if(msg == ''){
                    isPreenchido = true;
                }
            }

            if(msg != ''){
                msg = "Os campos da infração '"+infracao+"' são obrigatório:\n\n"+msg;
                alert(msg);
                return false;
            }

        }
        if(!isPreenchido){
            alert("Preencha ao menos uma infração.");
            return false;
        }

        return true;
    }


    function OnSubmitForm(){
        if(!validar())
            return false;

    }
<? if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>
<form id="frmCadastroDecisao" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . '&id_md_lit_controle='.$_GET['id_md_lit_controle'].'&id_procedimento='.$_GET['id_procedimento'] ) ?>">
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);?>
    <?php PaginaSEI::getInstance()->abrirAreaDados(null); ?>
    <input type="hidden" id="hdnTbDecisaoAntigo" name="hdnTbDecisaoAntigo" value="">

    <div class="grid grid_11">
        <p class="bloco-orientacao">Orientações:</p>
        <p class="bloco-orientacao margem-bottom10"> Alterações devem estar relacionadas a Situação Decisória. </p>
    </div>
    <?
    PaginaSEI::getInstance()->montarAreaTabela($strTbCadastroDecisao, $numRegistros);
    ?>
    <input type="hidden" name="hdnIdUltimaSituacaoDecisoria" id="hdnIdUltimaSituacaoDecisoria" value="<?= $idUltimaSituacaoDecisoria ?>" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
