<?php

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCampoIntegracaoINT extends InfraINT
{

    public static function montarSelectCampoIntergracao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $idMdLitFuncionalidade, $staParametro, $strValorItemSelecionado = 'null')
    {
        $objMdLitCampoIntegracaoDTO = new MdLitCampoIntegracaoDTO();
        $objMdLitCampoIntegracaoDTO->setNumIdMdLitFuncionalidade($idMdLitFuncionalidade);
        $objMdLitCampoIntegracaoDTO->setStrStaParametro($staParametro);
        $objMdLitCampoIntegracaoDTO->retNumIdMdLitCampoIntegracao();
        $objMdLitCampoIntegracaoDTO->retStrNomeCampo();

        $objMdLitCampoIntegracaoDTO->setOrdStrNomeCampo(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdLitCampoIntegracaoRN     = new MdLitCampoIntegracaoRN();
        $arrObjMdLitCampoIntegracaoDTO = $objMdLitCampoIntegracaoRN->listar($objMdLitCampoIntegracaoDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitCampoIntegracaoDTO, 'IdMdLitCampoIntegracao', 'NomeCampo');


    }

}