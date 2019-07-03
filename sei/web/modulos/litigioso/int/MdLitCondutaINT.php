<?php
    /**
     * ANATEL
     *
     * 30/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitCondutaINT extends InfraINT
    {


        public static function autoCompletarCondutasAtivas($strPalavrasPesquisa, $arrIdDispositivo=null)
        {

            $objCondutaDTO = new MdLitCondutaDTO();
            $objCondutaDTO->retTodos();

            $objCondutaDTO->setStrNome('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $objCondutaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objCondutaDTO->setStrSinAtivo('S');
            $objCondutaDTO->setNumMaxRegistrosRetorno(50);
            if(count($arrIdDispositivo)){
                $objRelDispositivoNormativoCondutaRN  = new MdLitRelDispositivoNormativoCondutaRN ();
                $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO ();

                $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($arrIdDispositivo, InfraDTO::$OPER_IN);
                $objRelDispositivoNormativoCondutaDTO->retNumIdConduta();

                $arrObjRelDispositivoNormativoCondutaDTO = $objRelDispositivoNormativoCondutaRN->listar($objRelDispositivoNormativoCondutaDTO);
                if(count($arrObjRelDispositivoNormativoCondutaDTO)){
                    $arrIdConduta = InfraArray::converterArrInfraDTO($arrObjRelDispositivoNormativoCondutaDTO, 'IdConduta' );

                    $objCondutaDTO->setNumIdCondutaLitigioso($arrIdConduta, InfraDTO::$OPER_IN);
                }

            }

            $objCondutaRN     = new MdLitCondutaRN();
            $arrObjCondutaDTO = $objCondutaRN->listar($objCondutaDTO);

            return $arrObjCondutaDTO;
        }


        public static function montarSelectConduta($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $idDispositivoNormativo = false, $idTipoControle = false)
        {


            $stringFim = '<option value=""> </option>';


            if ($idTipoControle && !$idDispositivoNormativo) {
                $objRelDispositivoNormativoTipoControleRN  = new MdLitRelDispositivoNormativoTipoControleRN();
                $objRelDispositivoNormativoTipoControleDTO = new MdLitRelDispositivoNormativoTipoControleDTO();
                $objRelDispositivoNormativoTipoControleDTO->setNumIdTipoControle($idTipoControle);
                $objRelDispositivoNormativoTipoControleDTO->retTodos();

                $arrObjRelDispositivoNormativoTipoControleDTO = $objRelDispositivoNormativoTipoControleRN->listar($objRelDispositivoNormativoTipoControleDTO);

                $idDispositivoNormativo = array();
                foreach ($arrObjRelDispositivoNormativoTipoControleDTO as $objRelDispositivoNormativoTipoControle) {
                    $idDispositivoNormativo[] = $objRelDispositivoNormativoTipoControle->getNumIdDispositivoNormativo();
                }

            }

            //Verifica vinculo de Dispositivo Normativo ou de Tipo de Controle
            //Verifica Vinculo com Dispositivo Normativo
            $objRelDispositivoNormativoCondutaRN  = new MdLitRelDispositivoNormativoCondutaRN ();
            $objRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO ();

            $objRelDispositivoNormativoCondutaDTO->retNumIdConduta();

            if (is_array($idDispositivoNormativo)) {
                if (count($idDispositivoNormativo) > 0) {
                    $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($idDispositivoNormativo, InfraDTO::$OPER_IN);
                } else {
                    // setando para não trazer nada
                    $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo(null);
                }
            } else {
                $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($idDispositivoNormativo);
            }

            $arrObjsIdsCondutas = $objRelDispositivoNormativoCondutaRN->listar($objRelDispositivoNormativoCondutaDTO);
            $arrIdsCondutas     = array();

            foreach ($arrObjsIdsCondutas as $objIdConduta) {
                $arrIdsCondutas [] = $objIdConduta->getNumIdConduta();
            }


            if (count($arrIdsCondutas) > 0) {
                $objCondutaDTO = new MdLitCondutaDTO();
                $objCondutaDTO->retTodos();

                $objCondutaDTO->setNumIdCondutaLitigioso($arrIdsCondutas, InfraDTO::$OPER_IN);
                $objCondutaDTO->setStrSinAtivo('S');
                $objCondutaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
                $objCondutaRN = new MdLitCondutaRN();

                $arrObjCondutasDTO = $objCondutaRN->listar($objCondutaDTO);

                foreach ($arrObjCondutasDTO as $obj) {

                    if (!is_null($strValorItemSelecionado) && $strValorItemSelecionado == $obj->getNumIdCondutaLitigioso()) {
                        $stringFim .= '<option value="' . $obj->getNumIdCondutaLitigioso() . '" selected="selected">' . $obj->getStrNome();
                    } else {
                        $stringFim .= '<option value="' . $obj->getNumIdCondutaLitigioso() . '">' . $obj->getStrNome();
                    }
                    $stringFim .= '</option>';

                }
            }

            return $stringFim;
        }

    }