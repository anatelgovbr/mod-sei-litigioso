<?php
    /**
     * ANATEL
     *
     * 25/05/2016 - criado por alan.campos@castgroup.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitEspecieDecisaoINT extends InfraINT
    {


        public static function autoCompletarEspeciesDecisaoAtivas($strPalavrasPesquisa)
        {

            $objEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
            $objEspecieDecisaoDTO->retTodos();
            $objEspecieDecisaoDTO->setNumMaxRegistrosRetorno(50);
            $objEspecieDecisaoDTO->setStrNome('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $objEspecieDecisaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objEspecieDecisaoDTO->setStrSinAtivo('S');

            $objEspecieDecisaoRN     = new MdLitEspecieDecisaoRN();
            $arrObjEspecieDecisaoDTO = $objEspecieDecisaoRN->listar($objEspecieDecisaoDTO);

            return $arrObjEspecieDecisaoDTO;
        }


        public static function montarSelectEspecieDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao,$idMdLitTipoDecisao, $strValorItemSelecionado=''){
            $objMdLitEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
            $objMdLitEspecieDecisaoDTO->retNumIdEspecieLitigioso();
            $objMdLitEspecieDecisaoDTO->retStrNome();
            $arrEspecies = array();

            if ($strValorItemSelecionado!=null){
                $objMdLitEspecieDecisaoDTO->setBolExclusaoLogica(false);
                $objMdLitEspecieDecisaoDTO->adicionarCriterio(array('SinAtivo','IdEspecieLitigioso'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
            }
            if($idMdLitTipoDecisao){

                $objRelEspecieDecisaoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                $objRelEspecieDecisaoDTO->retTodos();
                $objRelEspecieDecisaoDTO->retStrNomeEspecie();
                $objRelEspecieDecisaoDTO->setNumIdTipoDecisaoLitigioso($idMdLitTipoDecisao);

                $objRelTipoEspecieDecisaoLitigiosoRN = new MdLitRelTipoEspecieDecisaoRN();
                $arrEspecies                         = $objRelTipoEspecieDecisaoLitigiosoRN->listar($objRelEspecieDecisaoDTO);

                if(count($arrEspecies)){
                    $arrIdEspecieDecisaoLitigioso = InfraArray::converterArrInfraDTO($arrEspecies, 'IdEspecieDecisaoLitigioso');
                    $objMdLitEspecieDecisaoDTO->adicionarCriterio(array('IdEspecieLitigioso'),array(InfraDTO::$OPER_IN),array($arrIdEspecieDecisaoLitigioso));
                }
            }
            $objMdLitEspecieDecisaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            if(count($arrEspecies)){
                $objMdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
                $objMdLitEspecieDecisaoDTO = $objMdLitEspecieDecisaoRN->listar($objMdLitEspecieDecisaoDTO);
            }else{
                $objMdLitEspecieDecisaoDTO = array();
            }

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $objMdLitEspecieDecisaoDTO, 'IdEspecieLitigioso', 'Nome');

        }

        public static function carregarEspecieDecisao($idEspecieDecisao){
            $xml = '';
            $objMdLitEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
            $objMdLitEspecieDecisaoDTO->retTodos();
            $objMdLitEspecieDecisaoDTO->setNumIdEspecieLitigioso($idEspecieDecisao);

            $objMdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
            $objMdLitEspecieDecisaoDTO = $objMdLitEspecieDecisaoRN->consultar($objMdLitEspecieDecisaoDTO);
            $xml .= "<itens>";

            $xml .= '<IdEspecieLitigioso>'.$objMdLitEspecieDecisaoDTO->getNumIdEspecieLitigioso()."</IdEspecieLitigioso>\n";
            $xml .= '<Nome>'.$objMdLitEspecieDecisaoDTO->getStrNome()."</Nome>\n";
            $xml .= '<SinGestaoMulta>'.$objMdLitEspecieDecisaoDTO->getStrSinGestaoMulta()."</SinGestaoMulta>\n";
            $xml .= '<SinIndicacaoPrazo>'.$objMdLitEspecieDecisaoDTO->getStrSinIndicacaoPrazo()."</SinIndicacaoPrazo>\n";
            $xml .= '<SinIndicacaoObrigacoes>'.$objMdLitEspecieDecisaoDTO->getStrSinIndicacaoObrigacoes()."</SinIndicacaoObrigacoes>\n";
            $xml .= '<SinAtivo>'.$objMdLitEspecieDecisaoDTO->getStrSinIndicacaoObrigacoes()."</SinAtivo>\n";

            if($objMdLitEspecieDecisaoDTO->getStrSinIndicacaoObrigacoes() == 'S'){
                $objMdLitRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                $objMdLitRelEspecieDecisaoObrigacaoDTO->retTodos(true);
                $objMdLitRelEspecieDecisaoObrigacaoDTO->setNumIdEspecieDecisaoLitigioso($objMdLitEspecieDecisaoDTO->getNumIdEspecieLitigioso());

                $objMdLitRelEspecieDecisaoObrigacaoRN = new MdLitRelEspecieDecisaoObrigacaoRN();
                $arrObjMdLitRelEspecieDecisaoObrigacaoDTO = $objMdLitRelEspecieDecisaoObrigacaoRN->listar($objMdLitRelEspecieDecisaoObrigacaoDTO);

                if($arrObjMdLitRelEspecieDecisaoObrigacaoDTO){
                    $xml .= "<ObrigacoesLista>";
                    foreach ($arrObjMdLitRelEspecieDecisaoObrigacaoDTO as $objMdLitRelEspecieDecisaoObrigacaoDTO){
                        $xml .= "<obrigacao>\n";
                        $xml .= '<IdObrigacaoLitigioso>'.$objMdLitRelEspecieDecisaoObrigacaoDTO->getNumIdObrigacaoLitigioso()."</IdObrigacaoLitigioso>\n";
                        $xml .= '<NomeObrigacao>'.$objMdLitRelEspecieDecisaoObrigacaoDTO->getStrNomeObrigacao()."</NomeObrigacao>\n";
                        $xml .= "</obrigacao>\n";

                    }
                    $xml .= "</ObrigacoesLista>\n";
                }
            }
            $xml .= "</itens>";
            return $xml;
        }

        public static function montarSelectEspecieDecisaoPorTipoControle($strPrimeiroItemValor, $strPrimeiroItemDescricao,$idMdLitTipoDecisao, $strValorItemSelecionado='', $idTipoControle){

            $objMdLitRelTipoControleTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
            $objMdLitRelTipoControleTipoDecisaoDTO->retTodos(true);
            $objMdLitRelTipoControleTipoDecisaoDTO->setNumIdTipoControleLitigioso($idTipoControle);
            $objMdLitRelTipoControleTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($idMdLitTipoDecisao);
            $objMdLitRelTipoControleTipoDecisaoDTO->setStrSinAtivoDecisao('S');

            $objMdLitRelTipoControleTipoDecisaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitRelTipoControleTipoDecisaoRN = new MdLitRelTipoControleTipoDecisaoRN();
            $arrObjMdLitRelTipoControleTipoDecisaoDTO = $objMdLitRelTipoControleTipoDecisaoRN->listar($objMdLitRelTipoControleTipoDecisaoDTO);
            $arrObjMdLitRelTipoControleTipoDecisaoDTO = InfraArray::distinctArrInfraDTO($arrObjMdLitRelTipoControleTipoDecisaoDTO, 'IdMdLitEspecieDecisao');

            if($strValorItemSelecionado != '' && InfraArray::contarArrInfraDTO($arrObjMdLitRelTipoControleTipoDecisaoDTO, 'IdMdLitEspecieDecisao',$strValorItemSelecionado ) == 0){
                $objMdLitEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
                $objMdLitEspecieDecisaoDTO->retStrNome();
                $objMdLitEspecieDecisaoDTO->retNumIdEspecieLitigioso();
                $objMdLitEspecieDecisaoDTO->setNumIdEspecieLitigioso($strValorItemSelecionado);

                $objMdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
                $objMdLitEspecieDecisaoDTO = $objMdLitEspecieDecisaoRN->consultar($objMdLitEspecieDecisaoDTO);

                if($objMdLitEspecieDecisaoDTO){
                    $objMdLitRelTipoControleTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                    $objMdLitRelTipoControleTipoDecisaoDTO->setNumIdMdLitEspecieDecisao($objMdLitEspecieDecisaoDTO->getNumIdEspecieLitigioso());
                    $objMdLitRelTipoControleTipoDecisaoDTO->setStrNomeEspecieDecisao($objMdLitEspecieDecisaoDTO->getStrNome());

                    $arrObjMdLitRelTipoControleTipoDecisaoDTO[] = $objMdLitRelTipoControleTipoDecisaoDTO;
                }
            }

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelTipoControleTipoDecisaoDTO, 'IdMdLitEspecieDecisao', 'NomeEspecieDecisao');

        }
    }