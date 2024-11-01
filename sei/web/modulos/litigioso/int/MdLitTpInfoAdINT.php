<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitTpInfoAdINT extends InfraINT
{

    public static function salvarTipoInformacao($post)
    {
        if(self::validarNome($post)){
            throw new InfraException("Não é possível cadastrar Tipo de Informação com o nome " . $post['txtNome'] . ", pois ele já está sendo utilizado neste Tipo de Controle.");
        }
        $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();
        $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
        $objMdLitTpInfoAdDTO->setStrNome($post['txtNome']);
        $objMdLitTpInfoAdDTO->setStrDescricao($post['txaDescricao']);
        $objMdLitTpInfoAdDTO->setNumIdMdLitTpControle($post['hdnIdTpCtrlLitigioso']);
        $objMdLitTpInfoAdDTO->setStrSinAtivo('N');
        $objMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->cadastrar($objMdLitTpInfoAdDTO);
        return $objMdLitTpInfoAdDTO;
    }

    private function validarNome($post, $IdMdLitTpInforAd = null)
    {
        $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();
        $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
        $objMdLitTpInfoAdDTO->setStrNome($post['txtNome']);

        if($IdMdLitTpInforAd){
            $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($IdMdLitTpInforAd , InfraDTO::$OPER_DIFERENTE);
        }
        $objMdLitTpInfoAdDTO->retNumIdMdLitTpInforAd();
        $objMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->consultar($objMdLitTpInfoAdDTO);
        return $objMdLitTpInfoAdDTO ? true : false;
    }

    public static function alterarTipoInformacao($post)
    {
        if(self::validarNome($post, $post['hdnIdMdLitTpInforAd'])){
            throw new InfraException("Não é possível alterar o Tipo de Informação para o nome " . $post['txtNome'] . ", pois ele já está sendo utilizado neste Tipo de Controle.");
        }

        $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();
        $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
        $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($post['hdnIdMdLitTpInforAd']);
        $objMdLitTpInfoAdDTO->retTodos();
        $objMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->consultar($objMdLitTpInfoAdDTO);

        $objMdLitTpInfoAdDTO->setStrNome($post['txtNome']);
        $objMdLitTpInfoAdDTO->setStrDescricao($post['txaDescricao']);
        $objMdLitTpInfoAdRN->alterar($objMdLitTpInfoAdDTO);
        return $objMdLitTpInfoAdDTO;
    }

    public static function consultarTpInfoAd($IdTipoInformacao)
    {
        $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
        $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($IdTipoInformacao);
        $objMdLitTpInfoAdDTO->retNumIdMdLitTpInforAd();
        $objMdLitTpInfoAdDTO->retStrNome();
        return  (new MdLitTpInfoAdRN())->consultar($objMdLitTpInfoAdDTO);
    }

    public static function montarTabelaListagemTipoInformacao($arrObjMdLitTpInfoAdDTO)
    {

        $strResultado = '';
        $toggleClass = true;

        if ($arrObjMdLitTpInfoAdDTO) {

            $strSumarioTabela = 'Tabela de Informações Adicionais.';
            $strCaptionTabela = 'Informações Adicionais';

            $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, count($arrObjMdLitTpInfoAdDTO)) . '</caption>';
            $strResultado .= '<tr>';
            $strResultado .= '    <th class="infraTh" width="5%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            $strResultado .= '    <th class="infraTh"width="30%"> Tipo de Informação </th>' . "\n";
            $strResultado .= '    <th class="infraTh"width="50%"> Descrição </th>' . "\n";
            $strResultado .= '    <th class="infraTh" width="15%">Ações</th>' . "\n";
            $strResultado .= '</tr>' . "\n";
            $count = 0;

            foreach ($arrObjMdLitTpInfoAdDTO as $objMdLitTpInfoAdDTO) {

                //Ação ativar-desativa
                $ativarDesativar = '<img onclick="acaoAtivar(\'' . $objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd() . '\',\'' . $objMdLitTpInfoAdDTO->getStrNome() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg?'.Icone::VERSAO.'" title="Ativar Tipo de Informação" alt="Ativar Tipo Informação" class="infraImg" />';
                if ($objMdLitTpInfoAdDTO->getStrSinAtivo() == 'S') {
                    $ativarDesativar = '        <img onclick="acaoDesativar(\'' . $objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd() . '\',\'' . $objMdLitTpInfoAdDTO->getStrNome() . '\');" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg?'.Icone::VERSAO.'" title="Desativar Tipo de Informação" alt="Desativar Tipo Informação" class="infraImg" />';
                }

                // Alterna as classes infraTrEscura e infraTrClara
                if ($objMdLitTpInfoAdDTO->getStrSinAtivo() == 'S') {
                    $strResultado .= $toggleClass ? '<tr class="infraTrClara">' : '<tr class="infraTrEscura">';
                    $toggleClass = !$toggleClass; // Alterna o valor de toggleClass
                } else {
                    $strResultado .= '<tr class="trVermelha">';
                }
                $strResultado .= '    <td valign="middle" style="text-align: center">' . PaginaSEI::getInstance()->getTrCheck($count, $objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd(), PaginaSEI::tratarHTML($objMdLitTpInfoAdDTO->getStrNome())) . '</td>';
                $strResultado .= '    <td style="text-align: left">' . PaginaSEI::tratarHTML($objMdLitTpInfoAdDTO->getStrNome()) . '</td>';
                $strResultado .= '    <td style="text-align: left">' . PaginaSEI::tratarHTML($objMdLitTpInfoAdDTO->getStrDescricao()) . '</td>';

                $strResultado .= '    <td style="text-align: center;">';
                $strResultado .= '        <a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] .'&id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso'] . '&id_tp_info_ad=' . $objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . Icone::VALORES . '" title="Alterar Informação Adicional" alt="Alterar Informação Adicional" class="infraImg" /></a>&nbsp;';
                $strResultado .= '        <a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_tipo_controle_info_adicionais_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] .'&id_tipo_controle_litigioso='. $_GET['id_tipo_controle_litigioso'] . '&id_tp_info_ad=' . $objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd())) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg?'.Icone::VERSAO.'" title="Alterar Informação Adicional" alt="Alterar Informação Adicional" class="infraImg" /></a>&nbsp;';
                $strResultado .= $ativarDesativar;

                //Ação Excluir
                $strResultado .= '        <img onclick="excluirTipoInformacao('. $objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd(). ')" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg?'.Icone::VERSAO.'" title="Excluir Tipo de Informação" alt="Excluir Tipo Informação" class="infraImg" />';

                $strResultado .= '    </td>' . "\n";
                $strResultado .= '</tr>' . "\n";
                $count ++;
            }
            $strResultado .= '</table>';
        }

        $return['qtd'] = count($arrObjMdLitTpInfoAdDTO);
        $return['table'] = $strResultado;

        return $return;
    }

    public static function desativarTipoInformacao($arrIds)
    {
        $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();

        $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
        $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($arrIds, InfraDTO::$OPER_IN);
        $objMdLitTpInfoAdDTO->retNumIdMdLitTpInforAd();
        $objMdLitTpInfoAdDTO->retStrSinAtivo();
        $arrObjMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->listar($objMdLitTpInfoAdDTO);

        $objMdLitTpInfoAdRN->desativar($arrObjMdLitTpInfoAdDTO);

        MdLitCamposAdINT::desativarCamposDependentes($arrObjMdLitTpInfoAdDTO);

    }
    public static function ativarTipoInformacao($arrIds)
    {
        $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();

        $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
        $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($arrIds, InfraDTO::$OPER_IN);
        $objMdLitTpInfoAdDTO->retNumIdMdLitTpInforAd();
        $objMdLitTpInfoAdDTO->retStrSinAtivo();
        $arrObjMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->listar($objMdLitTpInfoAdDTO);

        if(empty(MdLitCamposAdINT::consultarCampoAtivoNoTipoInformacao($arrObjMdLitTpInfoAdDTO))){
            throw new InfraException("Não é possível ativar Tipo de Informação sem que tenha pelo menos um campo cadastrado e ativo.");
        }

        $objMdLitTpInfoAdRN->ativar($arrObjMdLitTpInfoAdDTO);

    }


    public static function excluirTipoInformacao($arrIdMdLitTpInforAd)
    {
        $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();

        foreach ($arrIdMdLitTpInforAd as $IdMdLitTpInforAd){
            $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
            $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($IdMdLitTpInforAd);
            $objMdLitTpInfoAdDTO->retNumIdMdLitTpInforAd();
            $objMdLitTpInfoAdDTO->retStrSinAtivo();
            $objMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->consultar($objMdLitTpInfoAdDTO);

            MdLitCamposAdINT::excluirCampoInfoAdd(array($objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd()));
            $objMdLitTpInfoAdRN->excluir(array($objMdLitTpInfoAdDTO));
        }

        return;
    }

    public static function recuperarOpcoesTipoInformacoes($idTpControle)
    {
        $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();

        $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
        $objMdLitTpInfoAdDTO->setNumIdMdLitTpControle($idTpControle);
        $objMdLitTpInfoAdDTO->setStrSinAtivo('S');
        $objMdLitTpInfoAdDTO->retNumIdMdLitTpInforAd();
        $objMdLitTpInfoAdDTO->retStrSinAtivo();
        $objMdLitTpInfoAdDTO->retStrNome();
        $arrObjMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->listar($objMdLitTpInfoAdDTO);

        $strResultado = '';
        $strResultado .= '<option value=""></option>';
        foreach ($arrObjMdLitTpInfoAdDTO as $objMdLitTpInfoAdDTO) {
            $strResultado .= '<option id="" value="'. $objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd() .'">' . $objMdLitTpInfoAdDTO->getStrNome() . '</option>';
        }

        return $strResultado;
    }

    public static function recuperarNome($idTpInfoAdDTO)
    {
        $objMdLitTpInfoAdRN = new MdLitTpInfoAdRN();

        $objMdLitTpInfoAdDTO = new MdLitTpInfoAdDTO();
        $objMdLitTpInfoAdDTO->setNumIdMdLitTpInforAd($idTpInfoAdDTO);
        $objMdLitTpInfoAdDTO->retStrNome();
        $objMdLitTpInfoAdDTO = $objMdLitTpInfoAdRN->consultar($objMdLitTpInfoAdDTO);
        return $objMdLitTpInfoAdDTO->getStrNome();
    }
}

?>