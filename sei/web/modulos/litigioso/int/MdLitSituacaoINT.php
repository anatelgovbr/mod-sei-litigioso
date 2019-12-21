<?
    /**
     * ANATEL
     *
     * 01/07/2016 - criado por jaqueline.mendes@castgrouo.com.br - CAST GROUP
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitSituacaoINT extends InfraINT
    {

        public static function montarSelectSituacaoPorFase($idFase, $idSerie){
            $html = '<option selected="selected" value=""> </option>';

            $objRelSituacaoSerieRN = new MdLitRelSituacaoSerieRN();

            $objRelSituacaoSerieDTO = new MdLitRelSituacaoSerieDTO();
            $objRelSituacaoSerieDTO->setNumIdSerie($idSerie);
            $objRelSituacaoSerieDTO->retNumIdSituacaoLitigioso();
            $count = $objRelSituacaoSerieRN->contar($objRelSituacaoSerieDTO);

            $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();

            if ($count > 0) {
                $arrDTO = $objRelSituacaoSerieRN->listar($objRelSituacaoSerieDTO);
                $arrIdsSituacao = InfraArray::converterArrInfraDTO($arrDTO, 'IdSituacaoLitigioso');
                $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrIdsSituacao, InfraDTO::$OPER_IN);


                $objSituacaoLitigiosoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
                $objSituacaoLitigiosoDTO->setNumIdFaseLitigioso($idFase);
                $objSituacaoLitigiosoDTO->setStrSinAtivo('S');
                $objSituacaoLitigiosoDTO->retTodos();

                $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                $countSit = $objSituacaoLitigiosoRN->contar($objSituacaoLitigiosoDTO);
                $arrObjSituacaoDTO = $objSituacaoLitigiosoRN->listar($objSituacaoLitigiosoDTO);

                if ($countSit > 0) {
                    foreach ($arrObjSituacaoDTO as $objSituacaoDTO) {
                        $dados = $objSituacaoLitigiosoRN->retornaArrTiposSituacao($objSituacaoDTO);
                        $tipoSit = $dados['nome'] != '' ? ' (' . $dados['nome'] . ')' : '';
                        $html .= '<option';
                        $html .= ' value="' . $objSituacaoDTO->getNumIdSituacaoLitigioso() . '">';
                        $html .= $objSituacaoDTO->getStrNome() . $tipoSit . '</option>';
                    }
                }
            }

           return $html;
        }

        /**
         * @param $idSituacao
         * @param $idProcedimento
         * @return string
         */
        public static function carregarDadosSituacao($idSituacao, $idProcedimento, $idTipoControle){
            $objMdLitSituacaoRN    = new MdLitSituacaoRN();
            $objMdLitProcessoSitRN = new MdLitProcessoSituacaoRN();

            if($idSituacao == ''){
                $xml = '<Dados>';
                $xml .= '<IdSituacao></IdSituacao>';
                $xml .= '<TipoSituacao></TipoSituacao>';
                $xml .= '<MsgExibicao></MsgExibicao>';
                $xml .= '<NomeLabel></NomeLabel>';
                $xml .= '<Erro>1</Erro>';
                $xml .= '<SinPrimeiraIntimacao>N</SinPrimeiraIntimacao>';
                $xml .= '</Dados>';
                return $xml;
            }
            $objMdLitSituacaoDTO   = $objMdLitSituacaoRN->getObjSituacaoPorId($idSituacao);
            $isLivre               = $objMdLitSituacaoRN->verificaSeSituacaoLivre($objMdLitSituacaoDTO);
            $dadosSit              = $objMdLitSituacaoRN->retornaArrTiposSituacao($objMdLitSituacaoDTO);
            $dadosSitAnt           = $objMdLitProcessoSitRN->verificaDadosSitAtualSitAnterior(array($idProcedimento, $dadosSit, $idTipoControle));
            $erro                  = $dadosSitAnt['erro'] ? '1' : '0';
            $objMdLitProcessoSitDTOIntimacao = $objMdLitProcessoSitRN->consultarPrimeiraIntimacao($idProcedimento);
            $sinPrimeiraIntimacao = $objMdLitProcessoSitDTOIntimacao == null && $objMdLitSituacaoDTO->getStrSinIntimacao() == 'S' ? 'S' : 'N';

            $MdLitLancamentoDTO = new MdLitLancamentoDTO();
            $MdLitLancamentoDTO->ret('IdMdLitLancamento');
            $MdLitLancamentoDTO->ret('IdProcedimento');
            $MdLitLancamentoDTO->set('Intimacao', null);
            $MdLitLancamentoDTO->set('IdProcedimento', $idProcedimento);
            $MdLitLancamentoDTO->setOrd('IdProcedimento', 'asc');

            $MdLitLancamentoRn = new MdLitLancamentoRN();
            //Lista todos os lancamentos com data de intimação nula
            $arrLancamentoSemDataIntimacao = $MdLitLancamentoRn->listar($MdLitLancamentoDTO);

            $MdLitProcessoSituacaoRn = new MdLitProcessoSituacaoRN();

            $MdLitProcessoSituacaoDtoExists = new MdLitProcessoSituacaoDTO();
            $MdLitProcessoSituacaoDtoExists->ret('IdMdLitProcessoSituacao');
            $MdLitProcessoSituacaoDtoExists->ret('IdProcedimento');
            $MdLitProcessoSituacaoDtoExists->ret('SinDecisoriaSit');
            $MdLitProcessoSituacaoDtoExists->ret('IdMdLitFase');
            $MdLitProcessoSituacaoDtoExists->set('SinDecisoriaSit','S');
            $MdLitProcessoSituacaoDtoExists->set('IdProcedimento',$idProcedimento);
            $MdLitProcessoSituacaoDtoExists->setOrd('IdProcedimento', InfraDTO::$TIPO_ORDENACAO_ASC);
            $MdLitProcessoSituacaoDtoExists->setOrd('IdMdLitProcessoSituacao', InfraDTO::$TIPO_ORDENACAO_ASC);
            //Lista todas a decisões do processo
            $arrProcessoSituacaoDecisao = $MdLitProcessoSituacaoRn->listar($MdLitProcessoSituacaoDtoExists);

            $cancelamento = [];
            //se existir lancamento sem data de intimação este lançamento não pode estar cancelado
            if(count($arrLancamentoSemDataIntimacao)){
                $arrMdLitLancamento = InfraArray::simplificarArr(InfraArray::converterArrInfraDTO($arrLancamentoSemDataIntimacao)['MdLitLancamentoDTO'],'IdMdLitLancamento');
                $mdLitCancelaLancamentoDto =  new MdLitCancelaLancamentoDTO();
                $mdLitCancelaLancamentoDto->ret('IdMdLitLancamento');
                $mdLitCancelaLancamentoDto->set('IdMdLitLancamento', $arrMdLitLancamento, InfraDTO::$OPER_IN);

                $mdLitCancelaLancamentoRn = new MdLitCancelaLancamentoRN();
                $cancelamento = $mdLitCancelaLancamentoRn->listar($mdLitCancelaLancamentoDto);
            }

            //se houver lancamento nao cancelados sem data de intimação e ja houver decisao e a proxima situação for intimação, faz a validação
            if(count($arrProcessoSituacaoDecisao) > 0 &&
                $objMdLitSituacaoDTO->get('SinIntimacao') == 'S' &&
                (!$cancelamento && !$arrLancamentoSemDataIntimacao || count($cancelamento) < count($arrLancamentoSemDataIntimacao))
            ){
                $validar = 1;
            } else {
                $validar = 0;
            }

            $xml = '<Dados>';
            if (!is_null($objMdLitSituacaoDTO)) {
                if ($isLivre) {
                    $xml .= '<Livre> S </Livre>';
                    $xml .= '<IdSituacao> S </IdSituacao>';
                    $xml .= '<TipoSituacao> Livre </TipoSituacao>';
                    $xml .= '<MsgExibicao>  </MsgExibicao>';
                    $xml .= '<NomeLabel>  </NomeLabel>';
                    $xml .= '<Erro>  0 </Erro>';
                    $xml .= '<SinPrimeiraIntimacao>'.$sinPrimeiraIntimacao.'</SinPrimeiraIntimacao>';
                    $xml .= "<ValidarDataIntimacaoMulta>0</ValidarDataIntimacaoMulta>";
                } else {
                    $xml .= '<' . $dadosSit['tipoSituacao'] . '> S </' . $dadosSit['tipoSituacao'] . '>';
                    $xml .= '<IdSituacao> ' . $dadosSit['idSituacao'] . ' </IdSituacao>';
                    $xml .= '<TipoSituacao> ' . $dadosSit['tipoSituacao'] . ' </TipoSituacao>';
                    $xml .= '<MsgExibicao> ' . $dadosSitAnt['msgExibicao'] . ' </MsgExibicao>';
                    $xml .= '<NomeLabel> ' . $dadosSit['nomeLabel'] . ' </NomeLabel>';
                    $xml .= '<Erro> ' . $erro . ' </Erro>';
                    $xml .= '<SinPrimeiraIntimacao>'.$sinPrimeiraIntimacao.'</SinPrimeiraIntimacao>';
                    $xml .= "<ValidarDataIntimacaoMulta>$validar</ValidarDataIntimacaoMulta>";
                }
            }
            
            $xml .= '</Dados>';

            return $xml;
        }


        public static function verificarVinculo($idMdLitSituacao)
        {
            $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
            $possuiVinculo              = $objMdLitProcessoSituacaoRN->verificarVinculoSituacao($idMdLitSituacao);
            return '<Vinculo>' . $possuiVinculo . '</Vinculo>';
        }


    }