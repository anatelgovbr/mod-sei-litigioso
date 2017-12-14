<?php
    /**
     * ANATEL
     *
     * 30/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitDispositivoNormativoINT extends InfraINT
    {


        public static function autoCompletarDispositivos($strPalavrasPesquisa)
        {
            $objDTO = new MdLitDispositivoNormativoDTO();
            $objDTO->retTodos();

            $objDTO->setStrDispositivo('');
            $objDTO->setStrNorma('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $objDTO->setStrSinAtivo('S');
            $objDTO->setOrdStrNorma(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objRN     = new MdLitDispositivoNormativoRN();
            $arrObjDTO = $objRN->listar($objDTO);

            return $arrObjDTO;
        }


        public static function listarDispositivos($txtConduta = "", $txtNorma = "", $txtDispositivo = "", $selICCondutas = "", $filtro = "", $Ativo = "")
        {

            $objDispositivoNormativoLitigiosoDTO = new MdLitDispositivoNormativoDTO();
            $objDispositivoNormativoLitigiosoDTO->retTodos();


            if ($txtConduta != "") {
                $objDispositivoNormativoLitigiosoDTO->setStrCondutaFiltro($txtConduta);
            } else {
                $objDispositivoNormativoLitigiosoDTO->setStrCondutaFiltro("");
            }

            if ($txtNorma != "") {
                $objDispositivoNormativoLitigiosoDTO->setStrNorma($txtNorma, InfraDTO::$OPER_LIKE);
            } else {
                $objDispositivoNormativoLitigiosoDTO->setStrNorma("");
            }

            if ($txtDispositivo != "") {
                $objDispositivoNormativoLitigiosoDTO->setStrDispositivo($txtDispositivo, InfraDTO::$OPER_LIKE);
            } else {
                $objDispositivoNormativoLitigiosoDTO->setStrDispositivo("");
            }

            //CAMPO CONDUTA - ID
            if ($selICCondutas != "") {
                $objDispositivoNormativoLitigiosoDTO->setIdCondutaFiltro($selICCondutas);
            } else {
                $objDispositivoNormativoLitigiosoDTO->setIdCondutaFiltro("");
            }

            if ($filtro != "") {
                $objDispositivoNormativoLitigiosoDTO->setIdTipoControleLitigiosoFiltro($filtro);
            } else {
                $objDispositivoNormativoLitigiosoDTO->setIdTipoControleLitigiosoFiltro("");
            }

            if ($Ativo != "") {
                $objDispositivoNormativoLitigiosoDTO->setStrSinAtivo($Ativo);
            } else {
                $objDispositivoNormativoLitigiosoDTO->setStrSinAtivo("");
            }

            return $objDispositivoNormativoLitigiosoDTO;

        }


    }