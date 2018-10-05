<?php
    /**
     * ANATEL
     *
     * 28/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitTipoControleINT extends InfraINT
    {

        public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
        {

            $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
            $objTipoControleLitigiosoDTO->retNumIdTipoControleLitigioso();
            $objTipoControleLitigiosoDTO->retStrSigla();

            $objTipoControleLitigiosoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objTipoControleLitigiosoDTO->setStrSinAtivo('S');

            $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
            $arrObjTipoProcessoDTO      = $objTipoControleLitigiosoRN->listar($objTipoControleLitigiosoDTO);

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoProcessoDTO, 'IdTipoControleLitigioso', 'Sigla');
        }

        public static function autoCompletarTipoControleAtivos($strPalavrasPesquisa)
        {

            $obTipoControleDTO = new MdLitTipoControleDTO();
            $obTipoControleDTO->retTodos();

            $obTipoControleDTO->setStrSigla('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $obTipoControleDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
            $obTipoControleDTO->setStrSinAtivo('S');

            $obTipoControleRN      = new MdLitTipoControleRN();
            $arrObjTipoControleDTO = $obTipoControleRN->listar($obTipoControleDTO);

            return $arrObjTipoControleDTO;
        }

    }
