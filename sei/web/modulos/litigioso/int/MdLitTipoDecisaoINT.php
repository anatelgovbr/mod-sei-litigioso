<?php
    /**
     * ANATEL
     *
     * 01/06/2016 - criado por alan.campos@castgroup.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitTipoDecisaoINT extends InfraINT
    {


        public static function autoCompletarDecisao($strPalavrasPesquisa)
        {

            $objDTO = new MdLitTipoDecisaoDTO();
            $objDTO->retNumIdTipoDecisaoLitigioso();
            $objDTO->retStrNome();

            $objDTO->setStrNome('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $objDTO->setStrSinAtivo('S');
            $objDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objRN     = new MdLitTipoDecisaoRN();
            $arrObjDTO = $objRN->listar($objDTO);

            return $arrObjDTO;
        }

        public static function montarSelectTipoDecisao($strPrimeiroItemValor ='null', $strPrimeiroItemDescricao='&nbsp;', $strValorItemSelecionado=''){
            $objMdLitTipoDecisaoDTO = new MdLitTipoDecisaoDTO();
            $objMdLitTipoDecisaoDTO->retNumIdTipoDecisaoLitigioso();
            $objMdLitTipoDecisaoDTO->retStrNome();

            if ($strValorItemSelecionado!=null){
                $objMdLitTipoDecisaoDTO->setBolExclusaoLogica(false);
                $objMdLitTipoDecisaoDTO->adicionarCriterio(array('SinAtivo','IdTipoDecisaoLitigioso'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
            }

            $objMdLitTipoDecisaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitTipoDecisaoRN = new MdLitTipoDecisaoRN();
            $objMdLitTipoDecisaoDTO = $objMdLitTipoDecisaoRN->listar($objMdLitTipoDecisaoDTO);

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $objMdLitTipoDecisaoDTO, 'IdTipoDecisaoLitigioso', 'Nome');
        }

        public static function montarSelectTipoDecisaoPorTipoControle($strPrimeiroItemValor ='null', $strPrimeiroItemDescricao='&nbsp;', $strValorItemSelecionado='', $idTipoControle){
            $objMdLitRelTipoControleTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
            $objMdLitRelTipoControleTipoDecisaoDTO->retTodos(true);
            $objMdLitRelTipoControleTipoDecisaoDTO->setNumIdTipoControleLitigioso($idTipoControle);
            $objMdLitRelTipoControleTipoDecisaoDTO->setStrSinAtivoDecisao('S');

            if ($strValorItemSelecionado!=null){
                $objMdLitTipoDecisaoDTO = new MdLitTipoDecisaoDTO();
                $objMdLitTipoDecisaoDTO->retStrNome();
                $objMdLitTipoDecisaoDTO->retNumIdTipoDecisaoLitigioso();
                $objMdLitTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($strValorItemSelecionado);
                $objMdLitTipoDecisaoDTO->setBolExclusaoLogica(false);

                $objMdLitTipoDecisaoRN = new MdLitTipoDecisaoRN();
                $objMdLitTipoDecisaoDTO = $objMdLitTipoDecisaoRN->consultar($objMdLitTipoDecisaoDTO);
            }

            $objMdLitRelTipoControleTipoDecisaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitRelTipoControleTipoDecisaoRN = new MdLitRelTipoControleTipoDecisaoRN();
            $arrObjMdLitRelTipoControleTipoDecisaoDTO = $objMdLitRelTipoControleTipoDecisaoRN->listar($objMdLitRelTipoControleTipoDecisaoDTO);

            //se o valor for parametrizado e não existir na parametrização faz uma nova consulta retornando o valor não parametrizado
            //e adicionando na combo
            if ($strValorItemSelecionado!=null && InfraArray::contarArrInfraDTO($arrObjMdLitRelTipoControleTipoDecisaoDTO, 'IdTipoDecisaoLitigioso',$strValorItemSelecionado ) == 0){
                $objMdLitTipoDecisaoDTO = new MdLitTipoDecisaoDTO();
                $objMdLitTipoDecisaoDTO->retStrNome();
                $objMdLitTipoDecisaoDTO->retNumIdTipoDecisaoLitigioso();
                $objMdLitTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($strValorItemSelecionado);
                $objMdLitTipoDecisaoDTO->setBolExclusaoLogica(false);

                $objMdLitTipoDecisaoRN = new MdLitTipoDecisaoRN();
                $objMdLitTipoDecisaoDTO = $objMdLitTipoDecisaoRN->consultar($objMdLitTipoDecisaoDTO);

                if($objMdLitTipoDecisaoDTO){
                    $objMdLitRelTipoControleTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                    $objMdLitRelTipoControleTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($objMdLitTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso());
                    $objMdLitRelTipoControleTipoDecisaoDTO->setStrNome($objMdLitTipoDecisaoDTO->getStrNome());

                    $arrObjMdLitRelTipoControleTipoDecisaoDTO[] = $objMdLitRelTipoControleTipoDecisaoDTO;
                }
            }

            $arrObjMdLitRelTipoControleTipoDecisaoDTO = InfraArray::distinctArrInfraDTO($arrObjMdLitRelTipoControleTipoDecisaoDTO, 'IdTipoDecisaoLitigioso');


            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelTipoControleTipoDecisaoDTO, 'IdTipoDecisaoLitigioso', 'Nome');
        }

    }