<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 07/05/2018
 * Time: 15:36
 */
if(0){?><script type="text/javascript"><?}?>

    function inicializar(){
        if ('<?=$_GET['acao']?>'=='md_lit_motivo_selecionar'){
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        }else{
            document.getElementById('btnFechar').focus();
        }
        infraEfeitoTabelas(true);
    }

    <? if ($bolAcaoDesativar){ ?>
    function acaoDesativar(id,desc){
        if (confirm("Confirma desativa��o do motivo \""+desc+"\"?")){
            document.getElementById('hdnInfraItemId').value=id;
            document.getElementById('frmMdLitMotivoLista').action='<?=$strLinkDesativar?>';
            document.getElementById('frmMdLitMotivoLista').submit();



        }
    }

    function acaoDesativacaoMultipla(){
        if (document.getElementById('hdnInfraItensSelecionados').value==''){
            alert('Nenhum motivo selecionado.');
            return;
        }
        if (confirm("Confirma desativa��o dos motivos selecionados?")){
            document.getElementById('hdnInfraItemId').value='';
            document.getElementById('frmMdLitMotivoLista').action='<?=$strLinkDesativar?>';
            document.getElementById('frmMdLitMotivoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoReativar){ ?>
    function acaoReativar(id,desc){
        if (confirm("Confirma reativa��o do motivo \""+desc+"\"?")){
            document.getElementById('hdnInfraItemId').value=id;
            document.getElementById('frmMdLitMotivoLista').action='<?=$strLinkReativar?>';
            document.getElementById('frmMdLitMotivoLista').submit();
        }
    }

    function acaoReativacaoMultipla(){
        if (document.getElementById('hdnInfraItensSelecionados').value==''){
            alert('Nenhum motivo selecionado.');
            return;
        }
        if (confirm("Confirma reativa��o dos motivos selecionados?")){
            document.getElementById('hdnInfraItemId').value='';
            document.getElementById('frmMdLitMotivoLista').action='<?=$strLinkReativar?>';
            document.getElementById('frmMdLitMotivoLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id,desc){
        if (confirm("Confirma exclus�o do motivo \""+desc+"\"?")){
            document.getElementById('hdnInfraItemId').value=id;
            document.getElementById('frmMdLitMotivoLista').action='<?=$strLinkExcluir?>';
            document.getElementById('frmMdLitMotivoLista').submit();
        }
    }

    function acaoExclusaoMultipla(){
        if (document.getElementById('hdnInfraItensSelecionados').value==''){
            alert('Nenhum motivo selecionado.');
            return;
        }
        if (confirm("Confirma exclus�o dos motivos selecionados?")){
            document.getElementById('hdnInfraItemId').value='';
            document.getElementById('frmMdLitMotivoLista').action='<?=$strLinkExcluir?>';
            document.getElementById('frmMdLitMotivoLista').submit();
        }
    }
    <? } ?>

    <?if(0){?></script><?}?>