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
                $objMdLitRelTipoControleTipoDecisaoDTO->setBolExclusaoLogica(false);
                $objMdLitRelTipoControleTipoDecisaoDTO->adicionarCriterio(array('IdTipoDecisaoLitigioso'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array($strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
            }

            $objMdLitRelTipoControleTipoDecisaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitRelTipoControleTipoDecisaoRN = new MdLitRelTipoControleTipoDecisaoRN();
            $arrObjMdLitRelTipoControleTipoDecisaoDTO = $objMdLitRelTipoControleTipoDecisaoRN->listar($objMdLitRelTipoControleTipoDecisaoDTO);

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelTipoControleTipoDecisaoDTO, 'IdTipoDecisaoLitigioso', 'Nome');
        }

    }