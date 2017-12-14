<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/03/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.0
*
* Versão no SVN: $Id$
*/

try {
  require_once dirname(__FILE__).'/../../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('md_lit_param_interessado_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selMdLitTipoControle','selMdLitNomeFuncional'));

  $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'md_lit_parametrizar_interessado_cadastrar':
      $strTitulo = 'Parametrizar Dados Complementares do Interessado';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdLitParamInteressado" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      //chamando o objeto do MdLitTipoControleRN
      $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
      $objTipoControleLitigiosoRN  = new MdLitTipoControleRN();

      //consulta do tipo de controle
      $objTipoControleLitigiosoDTO->retTodos();
      $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
      $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);

      if (isset($_POST['sbmCadastrarMdLitParamInteressado'])) {

        $objTipoControleLitigiosoDTO->setStrSinParamModalComplInteressado($_POST['rdoSinParamModalComplInteres']);

        $arrObjParametrizarInteressadoDTOSalvar = array();
        $bolEditar = false;

          //populando o $arrObjParametrizarInteressadoDTOSalvar que foram salvo
          foreach ($_POST['hdnArrayIdMdLitNomeFuncional'] as $IdMdLitNomeFuncional ){
              $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();

              $objMdLitParametrizarInteressadoDTO->setStrSinExibe(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinExibe_'.$IdMdLitNomeFuncional]));
              $objMdLitParametrizarInteressadoDTO->setStrSinObrigatorio(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinObrigatorio_'.$IdMdLitNomeFuncional]));
              $objMdLitParametrizarInteressadoDTO->setStrLabelCampo($_POST['txtLabelCampo_'.$IdMdLitNomeFuncional]);
              $objMdLitParametrizarInteressadoDTO->setNumTamanho($_POST['txtTamanho_'.$IdMdLitNomeFuncional]);
              $objMdLitParametrizarInteressadoDTO->setStrDescricaoAjuda($_POST['txtDescricaoAjuda_'.$IdMdLitNomeFuncional]);
              $objMdLitParametrizarInteressadoDTO->setStrSinCampoMapeado($_POST['hdnSinCampoMapeado_'.$IdMdLitNomeFuncional]);
              $objMdLitParametrizarInteressadoDTO->setNumIdMdLitNomeFuncional($IdMdLitNomeFuncional);
              $objMdLitParametrizarInteressadoDTO->setNumIdMdLitTipoControle($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
              $objMdLitParametrizarInteressadoDTO->setNumIdMdLitParamInteressado($_POST['IdMdLitParamInteressado_'.$IdMdLitNomeFuncional]);
              if(!empty($_POST['IdMdLitParamInteressado_'.$IdMdLitNomeFuncional]))
                  $bolEditar = true;

              $arrObjParametroSalvar['MdLitParametrizarInteressadoDTO'][] = $objMdLitParametrizarInteressadoDTO;
          }

        try{

          //populando o array com o tipo de processo
          $arrObjParametroSalvar['MdLitTipoControleDTO'] = $objTipoControleLitigiosoDTO;
          $objMdLitParametrizarInteressadoRN = new MdLitParametrizarInteressadoRN();

          if($bolEditar && $_POST['rdoSinParamModalComplInteres'] == 'N'){
              // se modificar o SinParamModalComplInteres para Não os registro do ParametrizarInteressado será excluido
              $objMdLitParametrizarInteressadoRN->excluirMultiplos($arrObjParametroSalvar);
          }elseif($bolEditar){
              $objMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->alterarMultiplos($arrObjParametroSalvar);
          }else{
              $objMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->cadastrarMultiplos($arrObjParametroSalvar);
          }

          PaginaSEI::getInstance()->adicionarMensagem('Parametrizar dados complementares do interessado cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_parametrizar_interessado_cadastrar&acao_origem='.$_GET['acao'].'&id_tipo_processo_litigioso='.$objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }

      }

      $sigla = $objTipoControleLitigiosoDTO->getStrSigla() ? $objTipoControleLitigiosoDTO->getStrSigla() : '';

      $strSubTitulo = 'Tipo de Controle Litigioso: ' . PaginaSEI::tratarHTML($sigla);
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

    //começo da tabela
    $objMdLitParametrizarInteressadoRN = new MdLitParametrizarInteressadoRN();
    $arrObjMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->listarPorTipoControle($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());


    $objNomeFuncionaRN = new MdLitNomeFuncionalRN();

    $numRegistros = count($arrObjMdLitParametrizarInteressadoDTO);

    if ($numRegistros > 0) {

        $bolCheck = false;

        $strResultado = '';

        $strCaptionTabela = 'Situações';

        $strResultado .= '<table width="99%" id="tableDadosComplementarInteressado" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';

        $strResultado .= '<th class="infraTh" width="20%">&nbsp;Nome funcional&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">&nbsp;Exibe?&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">&nbsp;Obrigatório?&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">&nbsp;Label do campo&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">&nbsp;Tamanho&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="25%">&nbsp;Descrição da ajuda&nbsp;</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="5%">&nbsp;Campo mapeado&nbsp;</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            $idLinha = $arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitNomeFuncional();

            if ($_GET['id_md_lit_nome_funcional'] == $idLinha) {
                $strCssTr = ($strCssTr == '<tr id="paramInteressadoTable_' . $idLinha . '" name="paramInteressadoTable_' . $idLinha . '" class="infraTrAcessada">') ? '<tr id="paramInteressadoTable_' . $idLinha . '" name="paramInteressadoTable_' . $idLinha . '" class="infraTrEscura">' : '<tr id="paramInteressadoTable_' . $idLinha . '" name="paramInteressadoTable_' . $idLinha . '" class="infraTrAcessada">';
            } else {
                $strCssTr = '<tr id="paramInteressadoTable_' . $idLinha . '" name="paramInteressadoTable_' . $idLinha . '" class="infraTrClara">';
            }

            $totalRegistros        = count($arrObjMdLitParametrizarInteressadoDTO);

            $exibe                      = $arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinExibe() === 'S' ? 'checked="checked"' : '';
            $obrigatorio                = $arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinObrigatorio() === 'S' ? 'checked="checked"' : '';
            $disabled                   = $arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinExibe() !== 'S' ? 'disabled="disabled"' : '';
            $numIdMdLitNomeFuncional    = $arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitNomeFuncional();
            $sinCampoMapeado            = $arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinCampoMapeado() == 'S'? 'Sim': 'Não' ;

            $strResultado .= $strCssTr;
            $strResultado .= "<td id='nome_funcional_{$numIdMdLitNomeFuncional}'>";
            $strResultado .= "<input type='hidden' name='hdnArrayIdMdLitNomeFuncional[]' value='{$numIdMdLitNomeFuncional}' /> <input type='hidden' name='IdMdLitParamInteressado_{$numIdMdLitNomeFuncional}' value='{$arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitParamInteressado()}' /> ";
            $strResultado .= PaginaSEI::tratarHTML($arrObjMdLitParametrizarInteressadoDTO[$i]->getStrNomeMdLitNomeFuncional());
            $strResultado .= "</td>";
            $strResultado .= "<td align='center'><input type='checkbox' onchange='campoTabelaDisabilidado(this)' name='chkSinExibe_{$numIdMdLitNomeFuncional}' class='exibe'  id='chkSinExibe_{$numIdMdLitNomeFuncional}' {$exibe}> </input></td>";
            $strResultado .= "<td align='center'><input type='checkbox' {$disabled} name='chkSinObrigatorio_{$numIdMdLitNomeFuncional}' class='obrigatorio'  id='chkSinObrigatorio_{$numIdMdLitNomeFuncional}' $obrigatorio > </input></td>";
            $strResultado .= "<td align='center'> <input type='text' {$disabled} maxlength='25' name='txtLabelCampo_{$numIdMdLitNomeFuncional}'  id='txtLabelCampo_{$numIdMdLitNomeFuncional}' value='".PaginaSEI::tratarHTML($arrObjMdLitParametrizarInteressadoDTO[$i]->getStrLabelCampo())."'  /> </td>";
            $strResultado .= $arrObjMdLitParametrizarInteressadoDTO[$i]->getNumIdMdLitNomeFuncional() == MdLitNomeFuncionalRN::$NUMERO?"<td align='center'> <input style='width: 50%;' {$disabled} type='text' maxlength='3' onkeypress='return SomenteNumero(event)' name='txtTamanho_{$numIdMdLitNomeFuncional}' id='txtTamanho_{$numIdMdLitNomeFuncional}' value='".PaginaSEI::tratarHTML($arrObjMdLitParametrizarInteressadoDTO[$i]->getNumTamanho())."' /> </td>": "<td></td>";
            $strResultado .= "<td align='center'> <input type='text' maxlength='150' name='txtDescricaoAjuda_{$numIdMdLitNomeFuncional}' id='txtDescricaoAjuda_{$numIdMdLitNomeFuncional}' {$disabled} style='width: 95%;'  value='".PaginaSEI::tratarHTML($arrObjMdLitParametrizarInteressadoDTO[$i]->getStrDescricaoAjuda())."'  /> </td>";
            $strResultado .= "<td align='center'> <input type='hidden' name='hdnSinCampoMapeado_{$numIdMdLitNomeFuncional}' id='chkSinCampoMapeado_{$numIdMdLitNomeFuncional}' value='{$arrObjMdLitParametrizarInteressadoDTO[$i]->getStrSinCampoMapeado()}' /> {$sinCampoMapeado} </td>";

            $strResultado .= '</tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    //fim da tabela

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?if(0){?><style><?}?>
#lblSinParamModalComplInteres{position:absolute;left:0%;top:0%;}
#imgSinParamModalComplInteres{position:absolute;left:0%;top:0%;left:355px;}
#divSinParamModalComplInteresSim{position:absolute;left:0%;top:40%;width:50%;}
#divSinParamModalComplInteresNao{position:absolute;left:5%;top:40%;width:50%;}
#divInfraAreaTabela{visibility: hidden;}

/*#btAjuda>img{width: 16px;height: 16px;}*/

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_lit_parametrizar_interessado_cadastrar'){
    document.getElementById('optSinParamModalComplInteresSim').focus();
  } else if ('<?=$_GET['acao']?>'=='md_lit_param_interessado_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  infraEfeitoTabelas();
    configurarTabelaDados();
}

function validarCadastro() {
  if (!document.getElementById('optSinParamModalComplInteresSim').checked && !document.getElementById('optSinParamModalComplInteresNao').checked) {
    alert('Escolha uma opção apresenta modal de dados complementares do interessado.');
    document.getElementById('optSinParamModalComplInteresSim').focus();
    return false;
  }

  if (document.getElementById('optSinParamModalComplInteresSim').checked ) {
    var elementExibeChecked = false;
    for(var i = 1; i < 10; i++){
        var elementExibe = document.getElementById('chkSinExibe_'+i);
        if(elementExibe == null){
            break;
        }
        if(elementExibe.checked){
            elementExibeChecked = true;
            var nomeFuncional = infraTrim(document.getElementById('nome_funcional_'+i).textContent);
            if(document.getElementById('txtLabelCampo_'+i) != null && document.getElementById('txtLabelCampo_'+i).value == ''){
                alert('O label do campo da linha "'+nomeFuncional+'" é obrigatório');
                return false;
            }
            if(document.getElementById('txtTamanho_'+i) != null && document.getElementById('txtTamanho_'+i).value == '') {
                alert('O tamanho da linha "'+nomeFuncional+'" é obrigatório');
                return false;
            }else if(document.getElementById('txtTamanho_'+i) != null && parseInt(document.getElementById('txtTamanho_'+i).value) == 0){
                alert('O tamanho da linha "'+nomeFuncional+'" precisa ser maior que zero!');
                return false;

            }
        }
    }
    if(document.getElementById('chkSinExibe_8').checked  && !document.getElementById('chkSinExibe_7').checked ){
        alert("O campo Estado é obrigatório se o campo Cidade for marcado!");
        return false;
    }

    if(!elementExibeChecked){
        alert('Ao menos um campo deve ser marcado como Exibe.');
        return false;
    }

  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}
function configurarTabelaDados(){
    document.getElementById('divInfraAreaTabela').style.visibility = 'hidden';
    if (document.getElementById('optSinParamModalComplInteresSim').checked){
        document.getElementById('divInfraAreaTabela').style.visibility = 'visible';
    }else if (document.getElementById('optSinParamModalComplInteresNao').checked){
        document.getElementById('divInfraAreaTabela').style.visibility = 'hidden';
    }
}

//função responsavel por limpar e disabilitar os inputs da linha da tabela
// Element e o checkbox dentro da td da table
function campoTabelaDisabilidado(element){
    if(!element.checked){
        var row = document.getElementById('tableDadosComplementarInteressado').rows[element.parentNode.parentNode.rowIndex];

        //td "Obrigatório?"
        row.cells[2].children[0].disabled = true;
        row.cells[2].children[0].checked = false;

        //td "label do campo"
        row.cells[3].children[0].disabled = true;
        row.cells[3].children[0].value = '';

        //td "Tamanho"
        if(row.cells[4].children[0]) {
            row.cells[4].children[0].disabled = true;
            row.cells[4].children[0].value = '';
        }

        //td "Descrição da ajuda"
        row.cells[5].children[0].disabled = true;
        row.cells[5].children[0].value = '';
    }else{
        var row = document.getElementById('tableDadosComplementarInteressado').rows[element.parentNode.parentNode.rowIndex];

        //td "Obrigatório?"
        row.cells[2].children[0].disabled = false;

        //td "label do campo"
        row.cells[3].children[0].disabled = false;

        //td "Tamanho"
        if(row.cells[4].children[0]){
            row.cells[4].children[0].disabled = false;
        }

        //td "Descrição da ajuda"
        row.cells[5].children[0].disabled = false;

        //se o combo de cidade for checado irá automaticamente checar o da cidade
        if(element.id == 'chkSinExibe_8'){
            document.getElementById('chkSinExibe_7').checked = true;
            campoTabelaDisabilidado(document.getElementById('chkSinExibe_7'));
        }
    }
}


    function SomenteNumero(e) {
        var tecla = (window.event) ? event.keyCode : e.which;
        if ((tecla > 47 && tecla < 58)) return true;
        else {
            if (tecla == 8 || tecla == 0) return true;
            else  return false;
        }
    }

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmMdLitParamInteressadoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao']. '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem='.$_GET['acao'])?>">
<?

PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
    <p><label class="infraLabel"><?= $strSubTitulo ?></label></p>
<?
    PaginaSEI::getInstance()->abrirAreaDados('4em');
?>
    <div id="divSinParamModalComplInteres" class="infraDivRadio">

        <label id="lblSinParamModalComplInteres" for="lblSinParamModalComplInteres" accesskey="" class="infraLabelObrigatorio">Apresenta Modal de Dados Complementares do Interessado:</label>
        <img src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" name="ajuda" id="imgSinParamModalComplInteres" <?= PaginaSEI::montarTitleTooltip('Este parâmetro define se a modal de Dados Complementares de Interessado será apresentada para os Usuários no cadastro dos processos no Controle Litigioso.') ?> class="infraImg"/>
        <div id="divSinParamModalComplInteresSim" class="infraDivRadio">
            <input type="radio" name="rdoSinParamModalComplInteres" onclick="configurarTabelaDados();" id="optSinParamModalComplInteresSim" <?=PaginaSEI::getInstance()->setRadio($objTipoControleLitigiosoDTO->getStrSinParamModalComplInteressado(), 'S')?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <span id="spnSinParamModalComplInteresSim"><label id="lblSinParamModalComplInteresSim" for="optSinParamModalComplInteresSim" class="infraLabelRadio">Sim</label></span>
        </div>
        <div id="divSinParamModalComplInteresNao" class="infraDivRadio">
            <input type="radio" name="rdoSinParamModalComplInteres" onclick="configurarTabelaDados();" id="optSinParamModalComplInteresNao" <?=PaginaSEI::getInstance()->setRadio($objTipoControleLitigiosoDTO->getStrSinParamModalComplInteressado(),'N')?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <span id="spnSinParamModalComplInteresNao"><label id="lblSinParamModalComplInteresNao" for="optSinParamModalComplInteresNao" class="infraLabelRadio">Não</label></span>
        </div>
    </div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>

<?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
?>
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>