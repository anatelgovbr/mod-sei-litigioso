<?php
    /**
     * ANATEL
     *
     * 23/05/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitObrigacaoINT extends InfraINT
    {

        public static function autoCompletarObrigacoesAtivas($strPalavrasPesquisa)
        {

            $objObrigacaoDTO = new MdLitObrigacaoDTO();
            $objObrigacaoDTO->retTodos();
            $objObrigacaoDTO->setNumMaxRegistrosRetorno(50);
            $objObrigacaoDTO->setStrNome('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $objObrigacaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objObrigacaoDTO->setStrSinAtivo('S');

            $objObrigacaoRN     = new MdLitObrigacaoRN();
            $arrObjObrigacaoDTO = $objObrigacaoRN->listar($objObrigacaoDTO);

            return $arrObjObrigacaoDTO;
        }

    }