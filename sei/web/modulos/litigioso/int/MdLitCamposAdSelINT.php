<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdSelINT extends InfraINT
{
    public static function montarTabelaListagemOpcoesComboBox($idCampo)
    {
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAd($idCampo);
        $objMdLitCamposAdSelDTO->retTodos();
        $arrObjMdLitCamposAdSelDTO = (new MdLitCamposAdSelRN())->listar($objMdLitCamposAdSelDTO);

        $strResultado = '';
        foreach ($arrObjMdLitCamposAdSelDTO as $objMdLitCamposAdSelDTO) {
            $cssOpcaoDesativada = $objMdLitCamposAdSelDTO->getStrSinAtivo() != 'S' ? 'class="trVermelha"' : '';
            $strResultado .= '<tr ' . $cssOpcaoDesativada . '>';
                $strResultado .= '<td style="display: none">' . $objMdLitCamposAdSelDTO->getNumIdMdLitCamposAdSel() . '</td>';
                $strResultado .= '<td style="text-align: center">' . PaginaSEI::tratarHTML($objMdLitCamposAdSelDTO->getStrNome()) . '</td>';
                $strResultado .= '<td style="display: none">' . PaginaSEI::tratarHTML($objMdLitCamposAdSelDTO->getStrSinAtivo()) . '</td>';
                $strResultado .= '<td align="center">';

                    $strResultado .= '<img onclick="editarOpcaoSelect(this)" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg?'.Icone::VERSAO.'" title="Alterar Opção" alt="Alterar Opção" class="infraImg" />';

                    if($objMdLitCamposAdSelDTO->getStrSinAtivo() == 'S'){
                        //Ação desativar
                        $strResultado .= '<img onclick="desativarOpcaoComboBox(this)" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg?'.Icone::VERSAO.'" title="Desativar Opção do Combo Box para utilizar nos formulários" alt="Desativar Opção do Combo Box para utilizar nos formulários" class="infraImg" />';
                    } else {
                        //Ação ativar
                        $strResultado .= '<img onclick="ativarOpcaoComboBox(this)" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg?'.Icone::VERSAO.'" title="Ativar Opção do Combo Box para utilizar nos formulários" alt="Ativar Opção do Combo Box para utilizar nos formulários" class="infraImg" />';
                    }

                    //Ação Excluir
                    $strResultado .= '<img onclick="excluirOpcaoComboBox(this)" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg?'.Icone::VERSAO.'" title="Excluir Opção do Combo Box" alt="Excluir Opção do Combo Box" class="infraImg" />';

            $strResultado .= '</td></tr>';
        }

        return $strResultado;
    }

    public static function reMontarTabelaListagemOpcoesComboBox($arrOpcoes)
    {
        $dadosArray = self::parseToArray($arrOpcoes);
        $strResultado = '';
        foreach ($dadosArray as $dados) {
            $strResultado .= '<tr>';
            $strResultado .= '    <td style="display: none">null</td>';
            $strResultado .= '    <td style="text-align: center">' . PaginaSEI::tratarHTML($dados['nome']) . '</td>';
            $strResultado .= '    <td style="display: none">S</td>';
            $strResultado .= '    <td align="center">';
            $strResultado .= '      <img onclick="editarOpcaoSelect(this)" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg?'.Icone::VERSAO.'" title="Alterar Opção" alt="Alterar Opção" class="infraImg" />';
            $strResultado .= '      <img onclick="excluirOpcaoComboBox(this)" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg?'.Icone::VERSAO.'" title="Excluir Opção do Combo Box" alt="Excluir Opção do Combo Box" class="infraImg" />';
            $strResultado .= '    </td>';
            $strResultado .= '</tr>';
        }

        return $strResultado;
    }

    public static function cadastrarOpçoesSelect($objMdLitCamposAdDTO, $arrOpcoes)
    {
        // Caso tenha sido alterado o tipo de campo para um que não seja combo box é excluído todas opçoes do select
        if($objMdLitCamposAdDTO->getStrCampoTipo() !== MdLitCamposAdINT::$COMBO_BOX && $objMdLitCamposAdDTO->getStrCampoTipo() !== MdLitCamposAdINT::$MULTIPLA_SELECAO){
            self::limparOpcoes($objMdLitCamposAdDTO->getNumIdMdLitCamposAd());
            return;
        }

        self::atualizarOpcoes($objMdLitCamposAdDTO->getNumIdMdLitCamposAd(), $arrOpcoes);
    }

    private static function atualizarOpcoes($idCampoAd, $arrOpcoes)
    {
        $objMdLitCamposAdSelRN = new MdLitCamposAdSelRN();

        $dadosArray = self::parseToArray($arrOpcoes);

        foreach ($dadosArray as $dados) {
            $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
            $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAd($idCampoAd);
            $objMdLitCamposAdSelDTO->setStrNome($dados['nome']);

            // Cadastrar nova opção
            if($dados['id'] == 'null' ){
                $objMdLitCamposAdSelDTO->setStrSinAtivo('S');
                $objMdLitCamposAdSelRN->cadastrar($objMdLitCamposAdSelDTO);
                $arrIdsGrid[] = $objMdLitCamposAdSelDTO->getNumIdMdLitCamposAdSel();
            } else {
                // Atualizar opção existente
                $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAdSel($dados['id']);
                $objMdLitCamposAdSelDTO->setStrSinAtivo($dados['situacao']);
                $objMdLitCamposAdSelRN->alterar($objMdLitCamposAdSelDTO);
                $arrIdsGrid[] = $dados['id'];
            }

        }

        self::excluirGrid($idCampoAd, $arrIdsGrid);

    }

    private static function parseToArray($arrOpcoes)
    {
        //ENCODE NÃO PERMITE CARACTERES ESPECIAIS :(
        $arrOpcoes = str_replace(['[', ']', '"'], '', $arrOpcoes);
        $dadosArray = explode('},{', $arrOpcoes);
        $dadosArray[0] = ltrim($dadosArray[0], '{');
        $dadosArray[count($dadosArray) - 1] = rtrim($dadosArray[count($dadosArray) - 1], '}');

        foreach ($dadosArray as $dados){

            $retorno[] = self::recuperarValorObjeto($dados);
        }

        return $retorno;
    }

    private static function recuperarValorObjeto($dados)
    {
        $return = [];
        $partes = explode(',', $dados, 3);
        $return['id'] = explode(':', $partes[0], 2)[1];
        $return['nome'] = explode(':', $partes[1], 2)[1];
        $return['situacao'] = explode(':', $partes[2], 2)[1];
        return $return;
    }

    private static function excluirGrid($idCampoAd, $arrIdsGrid)
    {
        $arrIdsGrid = array_filter($arrIdsGrid, function($value) {
            return $value !== "null";
        });

        $objMdLitCamposAdSelRN = new MdLitCamposAdSelRN();
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAdSel($arrIdsGrid, InfraDTO::$OPER_NOT_IN);
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAd($idCampoAd);
        $objMdLitCamposAdSelDTO->retNumIdMdLitCamposAdSel();
        $arrObjMdLitCamposAdSelDTO = $objMdLitCamposAdSelRN->listar($objMdLitCamposAdSelDTO);

        $objMdLitCamposAdSelRN->excluir($arrObjMdLitCamposAdSelDTO);
    }

    private static function limparOpcoes($idCampoAd)
    {
        $objMdLitCamposAdSelRN = new MdLitCamposAdSelRN();
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAd($idCampoAd);
        $objMdLitCamposAdSelDTO->retNumIdMdLitCamposAdSel();
        $arrObjMdLitCamposAdSelDTO = $objMdLitCamposAdSelRN->listar($objMdLitCamposAdSelDTO);
        $objMdLitCamposAdSelRN->excluir($arrObjMdLitCamposAdSelDTO);

    }

    public static function montarHtmlCombobox($idCampo, $idSelected = null, $exibirPrimeira)
    {
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAd($idCampo);
        $objMdLitCamposAdSelDTO->adicionarCriterio(array('IdMdLitCamposAdSel','SinAtivo'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array($idSelected,'S'),InfraDTO::$OPER_LOGICO_OR);
        $objMdLitCamposAdSelDTO->retTodos();
        $arrObjMdLitCamposAdSelDTO = (new MdLitCamposAdSelRN())->listar($objMdLitCamposAdSelDTO);
        $htmlRetorno = '';
        if ($exibirPrimeira) {
            $htmlRetorno .= '<option></option>';
        }
        foreach ($arrObjMdLitCamposAdSelDTO as $objMdLitCamposAdSelDTO){
            $selected = $objMdLitCamposAdSelDTO->getNumIdMdLitCamposAdSel() == $idSelected ? 'selected' : '';
            $htmlRetorno .= '<option value="' . $objMdLitCamposAdSelDTO->getNumIdMdLitCamposAdSel() .'" '. $selected.'>' . $objMdLitCamposAdSelDTO->getStrNome() .'</option>';
        }

        return  $htmlRetorno;
    }

    public static function montarSelectDependencia($idMdLitTpInforAd , $objMdLitCamposAdDTO)
    {
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitTpInforAd($idMdLitTpInforAd);
        if($objMdLitCamposAdDTO->getNumIdMdLitCamposAd()){
            $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAd($objMdLitCamposAdDTO->getNumIdMdLitCamposAd(), InfraDTO::$OPER_DIFERENTE);
        }
        $objMdLitCamposAdSelDTO->retStrNomeCampo();
        $objMdLitCamposAdSelDTO->retTodos();
        $arrObjMdLitCamposAdSelDTO = (new MdLitCamposAdSelRN())->listar($objMdLitCamposAdSelDTO);

        $htmlRetorno = '<option></option>';

        foreach ($arrObjMdLitCamposAdSelDTO as $objMdLitCamposAdSelDTO) {
            $selected = $objMdLitCamposAdSelDTO->getNumIdMdLitCamposAdSel() == $objMdLitCamposAdDTO->getNumIdMdLitCamposAdSel() ? 'selected' : '';
            $htmlRetorno .= '<option value="' . $objMdLitCamposAdSelDTO->getNumIdMdLitCamposAdSel() .'" '. $selected.'>' . $objMdLitCamposAdSelDTO->getStrNomeCampo() . ' > ' . $objMdLitCamposAdSelDTO->getStrNome() .'</option>';
        }

        return $htmlRetorno;

    }

    public static function buscarDependenciaCampo($idMdLitCamposAdSel)
    {
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAdSel($idMdLitCamposAdSel);
        $objMdLitCamposAdSelDTO->retStrNomeCampo();
        $objMdLitCamposAdSelDTO->retTodos();
        $objMdLitCamposAdSelDTO = (new MdLitCamposAdSelRN())->consultar($objMdLitCamposAdSelDTO);

        return $objMdLitCamposAdSelDTO ? $objMdLitCamposAdSelDTO->getStrNomeCampo() . ' > ' . $objMdLitCamposAdSelDTO->getStrNome() : null;
    }

    public static function buscarOpcoesCampo($idMdLitCamposAd, $sinAtivo = null)
    {
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAd($idMdLitCamposAd);
        if ($sinAtivo){
            $objMdLitCamposAdSelDTO->setStrSinAtivo('S');
        }
        $objMdLitCamposAdSelDTO->retTodos();
        return (new MdLitCamposAdSelRN())->listar($objMdLitCamposAdSelDTO);
    }

    public static function desativarOpcoesCampo($arrOpcoesCampo)
    {
        $arrIdOpcoesCampo = InfraArray::converterArrInfraDTO($arrOpcoesCampo, 'IdMdLitCamposAdSel');
        $objMdLitCamposAdSelRN = new MdLitCamposAdSelRN();
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAdSel($arrIdOpcoesCampo, InfraDTO::$OPER_IN);
        $objMdLitCamposAdSelDTO->retTodos();
        $arrObjMdLitCamposAdSelDTO = $objMdLitCamposAdSelRN->listar($objMdLitCamposAdSelDTO);
        $objMdLitCamposAdSelRN->desativar($arrObjMdLitCamposAdSelDTO);
    }
}

?>