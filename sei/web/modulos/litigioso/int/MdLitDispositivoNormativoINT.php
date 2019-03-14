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


        public static function listarDispositivos($txtConduta = "", $txtNorma = "", $txtDispositivo = "", $selICCondutas = "", $filtro = "", $Ativo = "", $selTipoControleLitigioso = '', $optRevogado = '')
        {

            $objDispositivoNormativoLitigiosoDTO = new MdLitDispositivoNormativoDTO();
            $objDispositivoNormativoLitigiosoDTO->retTodos();


            if ($txtConduta != "") {
                $objDispositivoNormativoLitigiosoDTO->setStrCondutaFiltro($txtConduta);
            }

            if ($txtNorma != "") {
                $objDispositivoNormativoLitigiosoDTO->setStrNorma($txtNorma, InfraDTO::$OPER_LIKE);
            }

            if ($txtDispositivo != "") {
                $objDispositivoNormativoLitigiosoDTO->setStrDispositivo($txtDispositivo, InfraDTO::$OPER_LIKE);
            }

            //CAMPO CONDUTA - ID
            if ($selICCondutas != "") {
                $objDispositivoNormativoLitigiosoDTO->setIdCondutaFiltro($selICCondutas);
            }

            //se vier pela modal filtra pelo id do tipo controle litigioso que vem via GET
            if ($filtro != "") {
                $objDispositivoNormativoLitigiosoDTO->setIdTipoControleLitigiosoFiltro($filtro);
            }

            //se vier pelo filtro do pesquisar irá filtrar id do tipo controle litigioso que vem via POST
            //@todo refatorar fazer os dois o msm comportamento
            if($selTipoControleLitigioso != ''){
                $objDispositivoNormativoLitigiosoDTO->setIdTipoControleLitigiosoFiltro($selTipoControleLitigioso);
            }

            if ($Ativo != "") {
                $objDispositivoNormativoLitigiosoDTO->setStrSinAtivo($Ativo);
            }

            if($optRevogado != 'S')
                $objDispositivoNormativoLitigiosoDTO->setStrSinRevogado("N");

            return $objDispositivoNormativoLitigiosoDTO;

        }

        public static function consultaDispositivo($idMdLitDispositivoNormativo){
            $objMdLitDispositivoNormativoDTO = new MdLitDispositivoNormativoDTO();
            $objMdLitDispositivoNormativoDTO->retTodos();
            $objMdLitDispositivoNormativoDTO->setNumIdDispositivoNormativoLitigioso($idMdLitDispositivoNormativo);

            $objMdLitDispositivoNormativoRN = new MdLitDispositivoNormativoRN();
            $objMdLitDispositivoNormativoDTO = $objMdLitDispositivoNormativoRN->consultar($objMdLitDispositivoNormativoDTO);

            return $objMdLitDispositivoNormativoDTO;
        }


    }