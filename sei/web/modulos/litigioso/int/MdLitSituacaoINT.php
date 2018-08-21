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
                } else {
                    $xml .= '<' . $dadosSit['tipoSituacao'] . '> S </' . $dadosSit['tipoSituacao'] . '>';
                    $xml .= '<IdSituacao> ' . $dadosSit['idSituacao'] . ' </IdSituacao>';
                    $xml .= '<TipoSituacao> ' . $dadosSit['tipoSituacao'] . ' </TipoSituacao>';
                    $xml .= '<MsgExibicao> ' . $dadosSitAnt['msgExibicao'] . ' </MsgExibicao>';
                    $xml .= '<NomeLabel> ' . $dadosSit['nomeLabel'] . ' </NomeLabel>';
                    $xml .= '<Erro> ' . $erro . ' </Erro>';
                    $xml .= '<SinPrimeiraIntimacao>'.$sinPrimeiraIntimacao.'</SinPrimeiraIntimacao>';
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