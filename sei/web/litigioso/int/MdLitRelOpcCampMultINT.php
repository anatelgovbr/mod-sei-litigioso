<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelOpcCampMultINT extends InfraINT
{

    public static function recuperarArrIdsOpcoesSelecionadasPorCampo($idCampoForm)
    {
        $objMdLitRelOpcCampMultDTO = new MdLitRelOpcCampMultDTO();
        $objMdLitRelOpcCampMultDTO->setNumIdMdLitCamposAdForm($idCampoForm);
        $objMdLitRelOpcCampMultDTO->retTodos();
        $arrObjMdLitRelOpcCampMultDTO = (new MdLitRelOpcCampMultRN())->listar($objMdLitRelOpcCampMultDTO);

        return InfraArray::converterArrInfraDTO($arrObjMdLitRelOpcCampMultDTO, 'IdMdLitCamposAdSel');

    }

    public static function salvarMultiSelecaoCampo($objMdLitCamposAdFormDTO, $values)
    {
        foreach ($values as $value){
            $objMdLitRelOpcCampMultDTO = new MdLitRelOpcCampMultDTO();
            $objMdLitRelOpcCampMultDTO->setNumIdMdLitCamposAdForm($objMdLitCamposAdFormDTO->getNumIdMdLitCamposAdForm());
            $objMdLitRelOpcCampMultDTO->setNumIdMdLitCamposAdSel($value);
            (new MdLitRelOpcCampMultRN())->cadastrar($objMdLitRelOpcCampMultDTO);
        }
    }

    public static function excluirOpcoes($arrIdsCamposForm)
    {
        $objMdLitRelOpcCampMultRN = new MdLitRelOpcCampMultRN();
        $objMdLitRelOpcCampMultDTO = new MdLitRelOpcCampMultDTO();
        $objMdLitRelOpcCampMultDTO->setNumIdMdLitCamposAdForm($arrIdsCamposForm, InfraDTO::$OPER_IN);
        $objMdLitRelOpcCampMultDTO->retTodos();
        $arrObjMdLitRelOpcCampMultDTO = $objMdLitRelOpcCampMultRN->listar($objMdLitRelOpcCampMultDTO);
        $objMdLitRelOpcCampMultRN->excluir($arrObjMdLitRelOpcCampMultDTO);
    }

}

?>