<?
/**
 * ANATEL
 *
 * 31/10/2016 - CAST
 *
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitControleINT extends InfraINT
{


    public static function montarXMLDocumentoProcedimentoDetalhe($numeroSEI, $idTipoControleLitigioso, $tipo)
    {

        if ($tipo == 'd') {
            //DOCUMENTO
            $objDocumentoDTO = new DocumentoDTO();
            $objDocumentoDTO->setStrProtocoloDocumentoFormatado($numeroSEI);
            $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
            $objDocumentoDTO->retDblIdDocumento();
            $objDocumentoDTO->retStrNumero();
            $objDocumentoDTO->retStrSiglaUnidadeGeradoraProtocolo();
            $objDocumentoDTO->retStrDescricaoUnidadeGeradoraProtocolo();
            $objDocumentoDTO->retNumIdSerie();
            $objDocumentoDTO->retStrNomeSerie();
            $objDocumentoDTO->retStrStaProtocoloProtocolo();
            $objDocumentoDTO->retDtaGeracaoProtocolo();
            $objDocumentoDTO->retArrObjAssinaturaDTO();
            $objDocumentoRN  = new DocumentoRN();
            $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

            $xml = "<documento>\n";
            $xml .= "<NumeroSei>" . $numeroSEI . "</NumeroSei>\n";

            // NUMERO SEI - EXISTE
            if (count($objDocumentoDTO) > 0) {

                //DOCUMENTO
                $arrAtributos = $objDocumentoDTO->getArrAtributos();
                foreach ($arrAtributos as $atributo) {
                    $xml .= "<" . $atributo[InfraDTO::$POS_ATRIBUTO_NOME] . ">";
                    $xml .= $atributo[InfraDTO::$POS_ATRIBUTO_VALOR];
                    $xml .= "</" . $atributo[InfraDTO::$POS_ATRIBUTO_NOME] . ">\n";
                }

                //SERIE - G-Interno ou Externo
                $xml .= "<SinInterno>";
                if ($objDocumentoDTO->getStrStaProtocoloProtocolo() == 'G') {
                    $xml .= "S";
                } else {
                    $xml .= "N";
                }
                $xml .= "</SinInterno>\n";

                //ASSINATURA
                $arrAssinatura = $objDocumentoDTO->getArrObjAssinaturaDTO();
                $xml           .= "<Assinatura>";
                if (count($arrAssinatura) > 0) {
                    $objAssinaturaDTO = new AssinaturaDTO();
                    $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
                    $objAssinaturaDTO->retDthAberturaAtividade();
                    $objAssinaturaRN     = new AssinaturaRN();
                    $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);
                    if (count($arrObjAssinaturaDTO) > 0) {
                        $xml .= $arrObjAssinaturaDTO[0]->getDthAberturaAtividade();
                    }
                }
                $xml .= "</Assinatura>\n";

            }
            $xml .= "</documento>";

        } else if ($tipo == 'p') {

            $numerosei = $numeroSEI;
            $numerosei = str_replace('.', '', $numerosei);
            $numerosei = str_replace('/', '', $numerosei);
            $numerosei = str_replace('-', '', $numerosei);

            //PROCESSO
            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->setStrProtocoloProcedimentoFormatadoPesquisa($numerosei);
            $objProcedimentoDTO->retTodos();
            $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();
            $objProcedimentoDTO->retStrNomeTipoProcedimento();
            $objProcedimentoRN  = new ProcedimentoRN();
            $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

            $xml = "<processo>\n";
            $xml .= "<NumeroSei>" . $numeroSEI . "</NumeroSei>\n";

            // NUMERO SEI - EXISTE
            if (count($objProcedimentoDTO) > 0) {

                // TIPO DE CONTROLE LITIGIOSO - GERAR SOBRESTADOS
                // TELA : Alterar Tipo de Controle Litigioso
                // OPO: Pode sobrestar a tramitao de outros processos
                // [RN16] ... validar nmeros de processos apenas se forem dos tipos indicados sendo passiveis de sobrestamento.

                //tipos de processos sobrestados relacionados
                $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO = new MdLitRelTipoControleTipoProcedimentoSobrestadoDTO();
                $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
                $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoControleLitigioso($idTipoControleLitigioso);
                $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->retTodos();

                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();
                $arrTipoProcedimentosSobrestados                         = $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN->listar($objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                // 0 - No  "tipos indicados sendo passiveis de sobrestamento"
                if (count($arrTipoProcedimentosSobrestados) > 0) {
                    //PROCESSO
                    $arrAtributos = $objProcedimentoDTO->getArrAtributos();
                    foreach ($arrAtributos as $atributo) {
                        $xml .= "<" . $atributo[InfraDTO::$POS_ATRIBUTO_NOME] . ">";
                        $xml .= $atributo[InfraDTO::$POS_ATRIBUTO_VALOR];
                        $xml .= "</" . $atributo[InfraDTO::$POS_ATRIBUTO_NOME] . ">\n";
                    }
                }
            }

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDistinct(true);
            $objAtividadeDTO->retStrSiglaUnidade();
            $objAtividadeDTO->retStrDescricaoUnidade();
            $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
            $objAtividadeDTO->setDthConclusao(null);

            $objAtividadeRN     = new AtividadeRN();
            $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

            foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
                $xml .= "<unidade>";
                $xml .= $objAtividadeDTO->getStrSiglaUnidade();
                $xml .= "</unidade>\n";
            }

            $xml .= "</processo>";

        }

        return $xml;

    }


    public static function montarXMLDocumentoVerificarSituacao($numeroSEI, $idTipoControleLitigioso)
    {

        //DOCUMENTO
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setStrProtocoloDocumentoFormatado($numeroSEI);
        $objDocumentoDTO->retNumIdSerie();
        $objDocumentoRN  = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        $xml = "<documento>\n";
        $xml .= "<NumeroSei>" . $numeroSEI . "</NumeroSei>\n";

        // NUMERO SEI - EXISTE
        if (count($objDocumentoDTO) > 0) {

            // SITUAO - SOMENTE UMA INSTAURAO
            $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
            $objSituacaoLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControleLitigioso);
            $objSituacaoLitigiosoDTO->setStrSinInstauracao('S');
            $objSituacaoLitigiosoDTO->retTodos();
            $objSituacaoLitigiosoRN  = new MdLitSituacaoRN();
            $objSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO);

            // SITUAO - SOMENTE UMA INSTAURAO - EXISTE
            if (count($objSituacaoLitigiosoDTO) > 0) {
                $xml .= "<StrSinInstauracao>S</StrSinInstauracao>\n";
                //$xml .= "<SinDataIntimacao>" . $objSituacaoLitigiosoDTO->getStrSinDataIntimacao() . "</SinDataIntimacao>\n";

                $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
                $objSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso());
                $objSituacaoLitigiosoSerieDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
                $objSituacaoLitigiosoSerieDTO->retTodos();

                $objRelSituacaoLitigiosoSerieRN  = new MdLitRelSituacaoSerieRN();
                $objRelSituacaoLitigiosoSerieDTO = $objRelSituacaoLitigiosoSerieRN->consultar($objSituacaoLitigiosoSerieDTO);

                // RELAO SITUAO X TIPO DOCUMENTO - EXISTE
                if (count($objRelSituacaoLitigiosoSerieDTO) > 0) {
                    // s retorna campos situao se estiver presente no tipo documento
                    $xml .= "<IdSerie>" . $objRelSituacaoLitigiosoSerieDTO->getNumIdSerie() . "</IdSerie>\n";
                }
            }

        }

        $xml .= "</documento>";

        return $xml;

    }

    public static function montarXMLRemoverSobrestamento($id_procedimento)
    {

        if ($id_procedimento != '') {
            // Copiado de:
            // arvore_visualizar.php - procedimento_remover_sobrestamento

            //SOBRESTAMENTO - C0RE
            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloRN  = new RelProtocoloProtocoloRN();
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($id_procedimento);
            $objRelProtocoloProtocoloDTO->retTodos();
            $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

            //ainda existe
            if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
                $objProcedimentoRN = new ProcedimentoRN();
                $objProcedimentoRN->removerSobrestamentoRN1017(array($objRelProtocoloProtocoloDTO));
            }

            //SOBRESTAMENTO - LITIGIOSO
            $objRelProtocoloProtocoloLitigiosoDTO = new MdLitRelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloLitigiosoRN  = new MdLitRelProtocoloProtocoloRN();
            $objRelProtocoloProtocoloLitigiosoDTO->retTodos();
            $objRelProtocoloProtocoloLitigiosoDTO->setDblIdProtocolo2($id_procedimento);
            $objRelProtocoloProtocoloLitigiosoDTO = $objRelProtocoloProtocoloLitigiosoRN->listar($objRelProtocoloProtocoloLitigiosoDTO);

            $objRelProtocoloProtocoloLitigiosoRN = new MdLitRelProtocoloProtocoloRN();
            $objRelProtocoloProtocoloLitigiosoRN->excluir($objRelProtocoloProtocoloLitigiosoDTO);

            $xml = "<documento>";
            $xml .= '<mensagemtipo>sucesso</mensagemtipo>';
            $xml .= '<mensagem>Remoo de sobrestamento realizada com sucesso.</mensagem>';
            $xml .= '</documento>';
        }

        return $xml;
    }

    public static function montarXmlInteressadoProcesso($idProcedimento, $idMdLitControle, $idMdLitTipoControle, $arrIdContato= array())
    {
        $objMdLitControleRN               = new MdLitControleRN();
        $arrParametros['idProcedimento']  = $idProcedimento;
        $arrParametros['idMdLitControle'] = $idMdLitControle;

        $objMdlItDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
        $objMdlItDadoInteressadoDTO->retTodos(false);
        $objMdlItDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);

        $objMdlItDadoInteressadoRN = new MdLitDadoInteressadoRN();
        $arrObjMdlItDadoInteressadoDTO = $objMdlItDadoInteressadoRN->listar($objMdlItDadoInteressadoDTO);
        $arrIdContatoDadoInteressado   = InfraArray::converterArrInfraDTO($arrObjMdlItDadoInteressadoDTO, 'IdContato');

        $arrParametros['arrIdContato'] = array_merge($arrIdContatoDadoInteressado, $arrIdContato);

        $arrObjContatoDTO = $objMdLitControleRN->listarInteressadoProcesso($arrParametros);

        $objMdLitTipoControleDTO = new MdLitTipoControleDTO();
        $objMdLitTipoControleDTO->setNumIdTipoControleLitigioso($idMdLitTipoControle);
        $objMdLitTipoControleDTO->retStrSinParamModalComplInteressado();
        $objMdLitTipoControleRN = new MdLitTipoControleRN();
        $objMdLitTipoControleDTO =  $objMdLitTipoControleRN->consultar($objMdLitTipoControleDTO);


        $xml = "<Interessado>\n";
        $xml .= '<SinParamModal>';
        $xml .= $objMdLitTipoControleDTO->isSetStrSinParamModalComplInteressado() ? $objMdLitTipoControleDTO->getStrSinParamModalComplInteressado() : 'N';
        $xml .= "</SinParamModal>\n";
        if ($arrObjContatoDTO) {

            foreach ($arrObjContatoDTO as $objContatoDTO) {

                $endereco   = $objContatoDTO->isSetStrEndereco() ? $objContatoDTO->getStrEndereco() : '';
                $bairro     = $objContatoDTO->isSetStrBairro() ? $objContatoDTO->getStrBairro() : '';
                $idCidade   = $objContatoDTO->isSetNumIdCidade() ? $objContatoDTO->getNumIdCidade() : '';
                $idUf       = $objContatoDTO->isSetNumIdUf() ? $objContatoDTO->getNumIdUf() : '';
                $cep       = $objContatoDTO->isSetStrCep() ? $objContatoDTO->getStrCep() : '';
                $contarLancamento = 0;

                if($objContatoDTO->isSetNumIdTipoContatoAssociado() && $objContatoDTO->getNumIdTipoContatoAssociado() != '' && $objContatoDTO->getStrSinEnderecoAssociado() == 'S'){

                    $endereco   = $objContatoDTO->isSetStrEnderecoContatoAssociado() ? $objContatoDTO->getStrEnderecoContatoAssociado() : '';
                    $bairro     = $objContatoDTO->isSetStrBairroContatoAssociado() ? $objContatoDTO->getStrBairroContatoAssociado() : '';
                    $idCidade   = $objContatoDTO->isSetNumIdCidadeContatoAssociado() ? $objContatoDTO->getNumIdCidadeContatoAssociado() : '';
                    $idUf       = $objContatoDTO->isSetNumIdUfContatoAssociado() ? $objContatoDTO->getNumIdUfContatoAssociado() : '';
                    $cep      = $objContatoDTO->isSetStrCepContatoAssociado() ? $objContatoDTO->getStrCepContatoAssociado() : '';
                }

                if($idMdLitControle){
                    $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
                    $objMdLitNumeroInteressadoDTO->setNumIdContatoMdLitDadoInteressado($objContatoDTO->getNumIdContato());
                    $objMdLitNumeroInteressadoDTO->setNumIdMdLitControleMdLitDadoInteressado($idMdLitControle);
                    $objMdLitNumeroInteressadoDTO->retTodos(false);

                    $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
                    $contarLancamento = $objMdLitNumeroInteressadoRN->contarLancamento($objMdLitNumeroInteressadoDTO);
                }

                $contatoPossuiDadoComplementar = in_array($objContatoDTO->getNumIdContato(), $arrIdContatoDadoInteressado) ? 1 : 0;

                $xml .= "<Contato>\n";

                $xml .= '<UrlAlterar>';
                $xml .= htmlentities(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_alterar&id_contato=' . $objContatoDTO->getNumIdContato()));
                $xml .= "</UrlAlterar>\n";

                $xml .= '<UrlDadosComplementares>';
                $xml .= htmlentities(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_dados_complementares_cadastrar&id_tp_controle='.$idMdLitTipoControle.'&id_contato=' . $objContatoDTO->getNumIdContato().'&id_procedimento='.$idProcedimento.'&id_md_lit_controle='.$idMdLitControle));
                $xml .= "</UrlDadosComplementares>\n";

                $xml .= '<IdContato>';
                $xml .= $objContatoDTO->getNumIdContato();
                $xml .= "</IdContato>\n";

                $xml .= '<NomeTipoContato>';
                $xml .= $objContatoDTO->getStrNomeTipoContato();
                $xml .= "</NomeTipoContato>\n";

                $xml .= '<NomeContato>';
                $xml .= str_replace('&', '&amp;', $objContatoDTO->getStrNome());
                $xml .= "</NomeContato>\n";

                $xml .= '<Cpf>';
                $xml .= $objContatoDTO->isSetDblCpf() ? $objContatoDTO->getDblCpf() : '';
                $xml .= "</Cpf>\n";

                $xml .= '<Cnpj>';
                $xml .= $objContatoDTO->isSetDblCnpj() ? $objContatoDTO->getDblCnpj() : '';
                $xml .= "</Cnpj>\n";

                $xml .= '<StaNatureza>';
                $xml .= $objContatoDTO->isSetStrStaNatureza() ? $objContatoDTO->getStrStaNatureza() : '';
                $xml .= "</StaNatureza>\n";

                $xml .= '<Endereco>';
                $xml .= $endereco;
                $xml .= "</Endereco>\n";

                $xml .= '<Bairro>';
                $xml .= $bairro;
                $xml .= "</Bairro>\n";

                $xml .= '<IdCidade>';
                $xml .=  $idCidade;
                $xml .= "</IdCidade>\n";

                $xml .= '<IdUf>';
                $xml .= $idUf;
                $xml .= "</IdUf>\n";

                $xml .= '<ContarLancamento>';
                $xml .= $contarLancamento;
                $xml .= "</ContarLancamento>\n";

                $xml .= '<ContatoPossuiDadoComplementar>';
                $xml .= $contatoPossuiDadoComplementar;
                $xml .= "</ContatoPossuiDadoComplementar>\n";

                $xml .= '<cep>';
                $xml .= $cep;
                $xml .= "</cep>\n";

                $xml .= "</Contato>\n";

            }

        }
        $xml .= '</Interessado>';

        return $xml;
    }

}
