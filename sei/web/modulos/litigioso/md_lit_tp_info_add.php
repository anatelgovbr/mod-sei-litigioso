<?

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();
    
    $idTipoControle = $_GET['id_tipo_controle_litigioso'] ? $_GET['id_tipo_controle_litigioso'] : $_POST['hdnIdTpCtrlLitigioso'];

    $arrComandos = array();
    $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoInformacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
    $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_litigioso='.$idTipoControle).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

    switch($_GET['acao']){
        case 'md_lit_tipo_controle_info_adicionais_cadastrar':
            $strTitulo = 'Novo Tipo de Informação';
            $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
            $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd(null);
            $objMdLitTpInfoAdDTO->setStrNome('');
            $objMdLitTpInfoAdDTO->setStrDescricao('');

            if (isset($_POST['sbmCadastrarTipoInformacao'])) {
                try{
                    $objMdLitTpInfoAdDTO = MdLitTpInfoAdINT::salvarTipoInformacao($_POST);
                    PaginaSEI::getInstance()->adicionarMensagem('Tipo de Informação "'.$objMdLitTpInfoAdDTO->getStrNome().'" cadastrado com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_listar&acao_origem=md_lit_tipo_controle_listar&id_tipo_controle_litigioso='. $objMdLitTpInfoAdDTO->getNumIdMdLitTpControle()));
                    die;
                }catch(Exception $e){
                    $objMdLitTpInfoAdDTO->setStrNome($_POST['txtNome']);
                    $objMdLitTpInfoAdDTO->setStrDescricao($_POST['txaDescricao']);
                    (new InfraException())->lancarValidacao($e->getMessage());
                }
            }
            break;

        case 'md_lit_tipo_controle_info_adicionais_alterar':
            $strTitulo = 'Alterar Tipo de Informação';
            $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();

            if (isset($_POST['sbmCadastrarTipoInformacao'])) {
                try{
                    $objMdLitTpInfoAdDTO = MdLitTpInfoAdINT::alterarTipoInformacao($_POST);
                    PaginaSEI::getInstance()->adicionarMensagem('Tipo de Informação "'.$_POST['txtNome'].'" alterada com sucesso.');
                    header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_listar&acao_origem=md_lit_tipo_controle_listar&id_tipo_controle_litigioso='. $objMdLitTpInfoAdDTO->getNumIdMdLitTpControle()));
                    die;
                }catch(Exception $e){
                    $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($_POST['hdnIdMdLitTpInforAd']);
                    $objMdLitTpInfoAdDTO->setStrNome($_POST['txtNome']);
                    $objMdLitTpInfoAdDTO->setStrDescricao($_POST['txaDescricao']);
                    (new InfraException())->lancarValidacao($e->getMessage());
                }
            }

            if(isset($_GET['id_tp_info_ad'])){
                $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
                $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($_GET['id_tp_info_ad']);
                $objMdLitTpInfoAdDTO->retTodos();
                $objMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->consultar($objMdLitTpInfoAdDTO);

                if ($objMdLitTpInfoAdDTO==null){
                    throw new InfraException("Registro não encontrado.");
                }
            }

            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }


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
    #lblNome {position:absolute;left:0%;top:0%;width:50%;}
    #txtNome {position:absolute;left:0%;top:6%;width:50%;}

    #lblDescricao {position:absolute;left:0%;top:16%;width:70%;}
    #txaDescricao {position:absolute;left:0%;top:22%;width:70%;}

<?
    PaginaSEI::getInstance()->fecharStyle();
    PaginaSEI::getInstance()->montarJavaScript();
    PaginaSEI::getInstance()->abrirJavaScript();
?>
    function inicializar(){

    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value)=='') {
            alert('Informe o Nome.');
            document.getElementById('txtNome').focus();
            return false;
        }
        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

<?
    PaginaSEI::getInstance()->fecharJavaScript();
    PaginaSEI::getInstance()->fecharHead();
    PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmTipoInformacaoAdd" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
        <?
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            PaginaSEI::getInstance()->abrirAreaDados('30em');
        ?>
        <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
        <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdLitTpInfoAdDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
        <textarea id="txaDescricao" name="txaDescricao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'3':'4'?>" onkeypress="return infraLimitarTexto(this,event,250);" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdLitTpInfoAdDTO->getStrDescricao());?></textarea>


        <input type="hidden" id="hdnIdMdLitTpInforAd" name="hdnIdMdLitTpInforAd" value="<?=$objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd();?>" />
        <input type="hidden" id="hdnIdTpCtrlLitigioso" name="hdnIdTpCtrlLitigioso" value="<?= $idTipoControle ?>" />
        <?
            PaginaSEI::getInstance()->fecharAreaDados();
        ?>
    </form>
<?
    PaginaSEI::getInstance()->fecharBody();
    PaginaSEI::getInstance()->fecharHtml();
?>