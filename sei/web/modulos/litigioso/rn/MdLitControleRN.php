<?
    /**
     * ANATEL
     *
     * 29/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitControleRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }


        /**
         * Consulta de registro único
         *
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $objControleLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function consultarConectado($objControleLitigiosoDTO)
        {
            try {

                // Valida Permissao
                $objControleLitigiosoBD = new MdLitControleBD($this->getObjInfraIBanco());
                $ret                    = $objControleLitigiosoBD->consultar($objControleLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Controle Litigioso.', $e);
            }
        }

        /**
         * Consulta de vários registros
         *
         * @access protected
         * @author CAST
         * @param $objControleLitigiosoDTO
         * @return array
         * @throws InfraException
         */
        protected function listarConectado($objControleLitigiosoDTO)
        {
            try {

                $objControleLitigiosoBD = new MdLitControleBD($this->getObjInfraIBanco());
                $ret                    = $objControleLitigiosoBD->listar($objControleLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Controle Litigioso.', $e);
            }
        }


        protected function cadastrarControlado(MdLitControleDTO $objControleLitigiosoDTO)
        {
            try {
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_processo_cadastro_cadastrar', __METHOD__, $objControleLitigiosoDTO);
                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //Cria o relacionamento com Conduta
                $objControleLitigiosoBD = new MdLitControleBD($this->getObjInfraIBanco());
                $ret                    = $objControleLitigiosoBD->cadastrar($objControleLitigiosoDTO);


                //cadastrando o processo da situação
                $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
                $objMdLitSituacaoDTO->retNumIdSituacaoLitigioso();
                $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($ret->getNumIdMdLitTipoControle());
                $objMdLitSituacaoDTO->setStrSinInstauracao('S');

                $objMdLitSituacaoRN = new MdLitSituacaoRN();
                $objMdLitSituacaoDTO = $objMdLitSituacaoRN->consultar($objMdLitSituacaoDTO);

                $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
                $objMdLitProcessoSituacaoDTO->setNumIdMdLitSituacao($objMdLitSituacaoDTO->getNumIdSituacaoLitigioso());
                $objMdLitProcessoSituacaoDTO->setDblIdDocumento($ret->getDblIdDocumento());
                $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($ret->getDblIdProcedimento());
                $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($ret->getNumIdMdLitTipoControle());
                $objMdLitProcessoSituacaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                $objMdLitProcessoSituacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $objMdLitProcessoSituacaoDTO->setDtaData($ret->getDtaDataInstauracao());
                $objMdLitProcessoSituacaoDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
                $objMdLitProcessoSituacaoDTO->setStrSinDepositoExtrajudicial('N');
                $objMdLitProcessoSituacaoDTO->setDblValorDepositoExtrajudicial(null);
                $objMdLitProcessoSituacaoDTO->setDtaDtDepositoExtrajudicial(null);
                $objMdLitProcessoSituacaoDTO->setDtaIntercorrente(null);
                $objMdLitProcessoSituacaoDTO->setDtaQuinquenal(null);
                $objMdLitProcessoSituacaoDTO->setStrSinAtivo('S');
                $objMdLitProcessoSituacaoDTO->setStrSinAlteraPrescricao('N');

                $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
                $objMdLitProcessoSituacaoRN->cadastrar($objMdLitProcessoSituacaoDTO);


                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Controle Litigioso.', $e);
            }
        }

        /**
         * Contar dados
         *
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $objControleLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function contarConectado($objControleLitigiosoDTO)
        {
            try {

                // Valida Permissao
                $objControleLitigiosoBD = new MdLitControleBD($this->getObjInfraIBanco());
                $ret                    = $objControleLitigiosoBD->contar($objControleLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Controle Litigioso.', $e);
            }
        }


        protected function sobrestarControlado($arrObjRelProtocoloProtocoloLitigiosoDTO)
        {
            try {

                //Valida Permissao
                //SessaoSEI::getInstance()->validarAuditarPermissao('procedimento_sobrestar',__METHOD__,$arrObjRelProtocoloProtocoloRecebidoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                /*
                  $objProtocoloRN = new ProtocoloRN();
                $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
                $objAtividadeRN = new AtividadeRN();
                */

                foreach ($arrObjRelProtocoloProtocoloLitigiosoDTO as $objRelProtocoloProtocoloLitigiosoDTO) {


                    //Criar associação entre os processos
                    $objRelProtocoloProtocoloLitigioso = new MdLitRelProtocoloProtocoloDTO();
                    $objRelProtocoloProtocoloLitigioso->setDblIdRelProtocoloProtocolo(null);
                    $objRelProtocoloProtocoloLitigioso->setNumIdControleLitigioso($objRelProtocoloProtocoloLitigiosoDTO->getNumIdControleLitigioso());
                    $objRelProtocoloProtocoloLitigioso->setDblIdProtocolo1($objRelProtocoloProtocoloLitigiosoDTO->getDblIdProtocolo1());
                    $objRelProtocoloProtocoloLitigioso->setDblIdProtocolo2($objRelProtocoloProtocoloLitigiosoDTO->getDblIdProtocolo2());
                    $objRelProtocoloProtocoloLitigioso->setDblIdDocumento($objRelProtocoloProtocoloLitigiosoDTO->getDblIdDocumento());
                    $objRelProtocoloProtocoloLitigioso->setStrSinAtivo($objRelProtocoloProtocoloLitigiosoDTO->getStrSinAtivo());
                    $objRelProtocoloProtocoloLitigioso->setDthDtaSobrestamento($objRelProtocoloProtocoloLitigiosoDTO->getDthDtaSobrestamento());

                    $objRelProtocoloProtocoloLitigiosoRN = new MdLitRelProtocoloProtocoloRN();
                    $objRelProtocoloProtocoloLitigiosoRN->cadastrar($objRelProtocoloProtocoloLitigioso);

                    /*
                    if (InfraString::isBolVazia($objRelProtocoloProtocoloRecebidoDTO->getStrMotivo())){
                      $objInfraException->lancarValidacao('Motivo não informado.');
                    }

                    $objProtocoloDTOAtual = new ProtocoloDTO();
                    $objProtocoloDTOAtual->retDblIdProtocolo();
                    $objProtocoloDTOAtual->retStrStaProtocolo();
                    $objProtocoloDTOAtual->retStrStaEstado();
                    $objProtocoloDTOAtual->retStrStaNivelAcessoGlobal();
                    $objProtocoloDTOAtual->retStrProtocoloFormatado();
                    $objProtocoloDTOAtual->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());

                    $objProtocoloDTOAtual = $objProtocoloRN->consultarRN0186($objProtocoloDTOAtual);

                        if($objProtocoloDTOAtual->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO){
                            $objInfraException->lancarValidacao('Protocolo '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' não é um processo.');
                        }

                        if($objProtocoloDTOAtual->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO){
                          $objInfraException->lancarValidacao('Processo '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' não pode estar anexado.');
                        }

                        if($objProtocoloDTOAtual->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO){
                            $objInfraException->lancarValidacao('Processo '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' já está sobrestado.');
                        }

                        if($objProtocoloDTOAtual->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO){
                            $objInfraException->lancarValidacao('Processo sigiloso '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' não pode ser sobrestado.');
                        }

                        // tramitação unificada
                        $objProcedimentoDTO = new ProcedimentoDTO();
                        $objProcedimentoDTO->setDblIdProcedimento($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo2());
                        $this->validarTramitacaoUnificada($objProcedimentoDTO, $objInfraException);

                        //processo atual não pode estar sobrestado a outro processo
                          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
                          $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo2();
                      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProtocoloDTOAtual->getDblIdProtocolo());
                      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);

                      $arrObjRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);
                      if (count($arrObjRelProtocoloProtocoloDTO)>0){
                          $objInfraException->lancarValidacao('Os processos abaixo estão sobrestados com vinculação ao processo '.$objProtocoloDTOAtual->getStrProtocoloFormatado().':'."\\n".implode("\\n",InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO,'ProtocoloFormatadoProtocolo2')));
                        }

                    $objProtocoloDTODestino = null;

                    if (!InfraString::isBolVazia($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1())){

                      $objProtocoloDTODestino = new ProtocoloDTO();
                      $objProtocoloDTODestino->retDblIdProtocolo();
                      $objProtocoloDTODestino->retStrStaProtocolo();
                      $objProtocoloDTODestino->retStrStaEstado();
                      $objProtocoloDTODestino->retStrProtocoloFormatado();
                      $objProtocoloDTODestino->setDblIdProtocolo($objRelProtocoloProtocoloRecebidoDTO->getDblIdProtocolo1());

                      $objProtocoloDTODestino = $objProtocoloRN->consultarRN0186($objProtocoloDTODestino);

                            if($objProtocoloDTODestino->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO){
                                $objInfraException->lancarValidacao('Protocolo '.$objProtocoloDTODestino->getStrProtocoloFormatado().' não é um processo.');
                            }

                        if ($objProtocoloDTOAtual->getDblIdProtocolo() == $objProtocoloDTODestino->getDblIdProtocolo()){
                          $objInfraException->lancarValidacao('Processo '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' não pode estar sobrestado com vinculação a ele mesmo.');
                        }

                        //processo destino não pode estar anexado
                        if($objProtocoloDTODestino->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO){
                          $objInfraException->lancarValidacao('Processo de destino da vinculação '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' não pode estar anexado.');
                        }

                        //processo destino não pode estar sobrestado
                          if($objProtocoloDTODestino->getStrStaEstado() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO){
                              $objInfraException->lancarValidacao('Processo de destino da vinculação '.$objProtocoloDTOAtual->getStrProtocoloFormatado().' não pode estar sobrestado.');
                          }

                    }

                        //muda estado do protocolo
                        $objProtocoloDTO = new ProtocoloDTO();
                      $objProtocoloDTO->setStrStaEstado(ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO);
                      $objProtocoloDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
                      $objProtocoloRN->alterarRN0203($objProtocoloDTO);

                      //se não tem processo associado
                        if ($objProtocoloDTODestino==null){

                      $arrObjAtributoAndamentoDTO = array();

                      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                      $objAtributoAndamentoDTO->setStrNome('MOTIVO');
                      $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloRecebidoDTO->getStrMotivo());
                      $objAtributoAndamentoDTO->setStrIdOrigem(null);
                      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

                        //Lançar andamento de sobrestamento
                        $objAtividadeDTO = new AtividadeDTO();
                        $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
                        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                           $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_SOBRESTAMENTO);
                        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

                        $objAtividadeRN = new AtividadeRN();
                        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

                        }else{
                            //Criar associação entre os processos
                            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
                            $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
                        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objProtocoloDTODestino->getDblIdProtocolo());
                        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProtocoloDTOAtual->getDblIdProtocolo());
                        $objRelProtocoloProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                        $objRelProtocoloProtocoloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                        $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_SOBRESTADO);
                        $objRelProtocoloProtocoloDTO->setNumSequencia(0);
                        $objRelProtocoloProtocoloDTO->setDthAssociacao(InfraData::getStrDataHoraAtual());

                        $objRelProtocoloProtocoloRN->cadastrarRN0839($objRelProtocoloProtocoloDTO);

                      $arrObjAtributoAndamentoDTO = array();
                      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
                      $objAtributoAndamentoDTO->setStrValor($objProtocoloDTOAtual->getStrProtocoloFormatado());
                      $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTOAtual->getDblIdProtocolo());
                      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

                      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                      $objAtributoAndamentoDTO->setStrNome('MOTIVO');
                      $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloRecebidoDTO->getStrMotivo());
                      $objAtributoAndamentoDTO->setStrIdOrigem(null);
                      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

                        //Gerar atividade de vinculação de procedimento
                        $objAtividadeDTO = new AtividadeDTO();
                        $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTODestino->getDblIdProtocolo());
                        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                           $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_SOBRESTANDO_PROCESSO);
                            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

                        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

                      $arrObjAtributoAndamentoDTO = array();
                      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                      $objAtributoAndamentoDTO->setStrNome('PROCESSO');
                      $objAtributoAndamentoDTO->setStrValor($objProtocoloDTODestino->getStrProtocoloFormatado());
                      $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTODestino->getDblIdProtocolo());
                      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

                      $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                      $objAtributoAndamentoDTO->setStrNome('MOTIVO');
                      $objAtributoAndamentoDTO->setStrValor($objRelProtocoloProtocoloRecebidoDTO->getStrMotivo());
                      $objAtributoAndamentoDTO->setStrIdOrigem(null);
                      $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

                        //Gerar atividade de vinculação de procedimento
                        $objAtividadeDTO = new AtividadeDTO();
                        $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTOAtual->getDblIdProtocolo());
                        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                           $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_SOBRESTADO_AO_PROCESSO);
                            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

                        $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

                        }
                        */
                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro sobrestando processo.', $e);
            }

        }

        protected function listarInteressadoProcessoConectado($arrParametros)
        {
            $arrIdContato = $arrParametros['arrIdContato'];
            $arrIdContatoParticipante = array();

            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->retNumIdContato();
            $objParticipanteDTO->setDblIdProtocolo($arrParametros['idProcedimento']);
            $objParticipanteDTO->setStrStaParticipacao(array(ParticipanteRN::$TP_INTERESSADO), InfraDTO::$OPER_IN);

            $objParticipanteRN     = new ParticipanteRN();
            $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);
            if ($arrObjParticipanteDTO) {
                $arrIdContatoParticipante = InfraArray::converterArrInfraDTO($arrObjParticipanteDTO, 'IdContato');
            }

            $arrIdContato = array_merge($arrIdContato, $arrIdContatoParticipante);

            $arrObjContatoDTO = array();
            if ($arrIdContato) {

                $objUnidadeDTO = new UnidadeDTO();
                $objUnidadeDTO->retNumIdContato();
                $objUnidadeRN  = new UnidadeRN();
                $arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);
                $arrIdContatoPorUnidade = InfraArray::converterArrInfraDTO($arrObjUnidadeDTO, 'IdContato');

                $arrNomeParametro = array('ID_TIPO_CONTATO_ORGAOS', 'ID_TIPO_CONTATO_SISTEMAS',
                                          'ID_TIPO_CONTATO_TEMPORARIO');

                $objInfraParametroDTO = new InfraParametroDTO();
                $objInfraParametroDTO->retStrValor();
                $objInfraParametroDTO->setStrNome($arrNomeParametro, InfraDTO::$OPER_IN);
                $objInfraParametroRN     = new InfraParametroRN();
                $arrObjInfraParametroDTO = $objInfraParametroRN->listar($objInfraParametroDTO);
                $arrIdTipoContato        = InfraArray::converterArrInfraDTO($arrObjInfraParametroDTO, 'Valor');

                $objContatoDTO = new ContatoDTO();
                $objContatoDTO->setNumIdContato($arrIdContato, InfraDTO::$OPER_IN);
                $objContatoDTO->setNumIdTipoContato($arrIdTipoContato, InfraDTO::$OPER_NOT_IN);
                $objContatoDTO->retNumIdContato();
                $objContatoDTO->retStrNome();
                $objContatoDTO->retStrNomeTipoContato();
                $objContatoDTO->retDblCpf();
                $objContatoDTO->retDblCnpj();
                $objContatoDTO->retStrStaNatureza();
                $objContatoDTO->retStrEndereco();
                $objContatoDTO->retStrBairro();
                $objContatoDTO->retStrBairro();
                $objContatoDTO->retNumIdCidade();
                $objContatoDTO->retNumIdUf();
                $objContatoDTO->retNumIdTipoContatoAssociado();
                $objContatoDTO->retStrEnderecoContatoAssociado();
                $objContatoDTO->retStrBairroContatoAssociado();
                $objContatoDTO->retNumIdCidadeContatoAssociado();
                $objContatoDTO->retNumIdUfContatoAssociado();
                $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_DESC);
                //filtrando os contatos não pode aparecer os contatos de unidades
                $objContatoDTO->adicionarCriterio(array('IdContato'),array(InfraDTO::$OPER_NOT_IN),array($arrIdContatoPorUnidade));

                $objContatoRN     = new ContatoRN();
                $arrObjContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);
            }
            return $arrObjContatoDTO;

        }

        protected function alterarControlado(MdLitControleDTO $objControleLitigiosoDTO)
        {
            try {

                $objControleLitigiosoBD = new MdLitRelDispositivoNormativoCondutaControleBD($this->getObjInfraIBanco());
                $objControleLitigiosoBD->alterar($objControleLitigiosoDTO);

                $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
                $objMdLitProcessoSituacaoRN->alterarPorControleLitigioso($objControleLitigiosoDTO);

                return $objControleLitigiosoDTO;
            } catch (Exception $e) {
                throw new InfraException ('Erro alterando controle litigioso.', $e);
            }
        }

        protected function cadastrarCompletoControlado($paramsPost)
        {
            try {
                $idProcedimento = $paramsPost['idProcedimento'];


                // DOCUMENTO INSTAURADOR
                if ($paramsPost['hdnListaDocInstauradores'] != '') {
                    $arrdocInstaurador = PaginaSEI::getInstance()->getArrItensTabelaDinamica($paramsPost['hdnListaDocInstauradores']);
                    $arrdocInstaurador = current($arrdocInstaurador);

                    if (count($arrdocInstaurador) == 8) {
                        // Somente 1 Doc Instaurador
                        //$qtddocInstaurador = count($arrdocInstaurador);

                        // Verificando se é I ou A
                        $objControleLitigiosoDTO = new MdLitControleDTO();
                        $objControleLitigiosoRN  = new MdLitControleRN();
                        $objControleLitigiosoDTO->retTodos();
                        $objControleLitigiosoDTO->setDblIdProcedimento($idProcedimento);
                        $objControleLitigiosoDTO = $objControleLitigiosoRN->consultar($objControleLitigiosoDTO);

                        // I - Inclusão
                        if (count($objControleLitigiosoDTO) == 0) {
                            $objControleLitigiosoDTO = new MdLitControleDTO();
                            $objControleLitigiosoDTO->setDblIdProcedimento($idProcedimento);
                            $objControleLitigiosoDTO->setDblIdDocumento($arrdocInstaurador[1]);
                            $objControleLitigiosoDTO->setDtaDataInstauracao($paramsPost['txtDtInstauracao']);
                            $objControleLitigiosoDTO->setNumIdMdLitTipoControle($paramsPost['hdnIdMdLitTipoControle']);
                            $objControleLitigioso = $this->cadastrar($objControleLitigiosoDTO);
                            // A - Alteração
                        } else {
                            $objControleLitigiosoDTO->setDblIdProcedimento($idProcedimento);
                            $objControleLitigiosoDTO->setDblIdDocumento($arrdocInstaurador[1]);
                            $objControleLitigiosoDTO->setDtaDataInstauracao($paramsPost['txtDtInstauracao']);
                            $objControleLitigiosoDTO->setNumIdMdLitTipoControle($paramsPost['hdnIdMdLitTipoControle']);
                            $objControleLitigioso = $this->alterar($objControleLitigiosoDTO);
                            $bolOperacao = 'a';
                        }
                    }
                } else {
                    $objInfraException = new InfraException();
                    $objInfraException->lancarValidacao('Informe o valor para Documento Instaurador do Procedimento Litigioso.');
                }


                if ($paramsPost['hdnListaDIIndicados'] != '') {
                    $arrDI = PaginaSEI::getInstance()->getArrItensTabelaDinamica($paramsPost['hdnListaDIIndicados']);

                    $arrChaveDI = array();
                    foreach($arrDI as $di){
                        if(is_numeric($di[0]))
                            $arrChaveDI[] = $di[0];
                    }
                    // DOCUMENTOS INFRIGIDOS
                    // Excluindo
                    try {
                        $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
                        $objRelDispositivoNormativoCondutaControleLitigiosoRN = new MdLitRelDispositivoNormativoCondutaControleRN();
                        $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retTodos();
                        $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdControleLitigioso($objControleLitigiosoDTO->getNumIdControleLitigioso());
                        if(count($arrChaveDI) > 0)
                            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdDispositivoNormativoNormaCondutaControle($arrChaveDI, InfraDTO::$OPER_NOT_IN);

                        $objRelDispositivoNormativoCondutaControleLitigiosoDTO = $objRelDispositivoNormativoCondutaControleLitigiosoRN->listar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

                        $objRelDispositivoNormativoCondutaControleLitigiosoRN = new MdLitRelDispositivoNormativoCondutaControleRN();
                        $objRelDispositivoNormativoCondutaControleLitigiosoRN->excluir($objRelDispositivoNormativoCondutaControleLitigiosoDTO);
                    }catch (Exception $e){
                    }

                    if (count($arrDI) > 0) {
                        $qtdDI = count($arrDI);
                        for ($i = 0; $i < $qtdDI; $i++) {
                            // Campos
                            $DI = $arrDI[$i];
                            if (count($DI) > 0) {
                                // id do documento infrigido
                                $id_documento_infrigido = $DI[1];
                                $id_documento_infrigido = explode('-', $id_documento_infrigido);
                                if (count($id_documento_infrigido) > 0) {
                                    $id_md_lit_disp_normat = $id_documento_infrigido[0];
                                    $id_md_lit_conduta     = $id_documento_infrigido[1];
                                    $dtaInfracao           = $DI[7];

                                    // CONTROLE LITIGIOSO - DOCUMENTO INSTAURADOR
                                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
                                    $objRelDispositivoNormativoCondutaControleLitigiosoRN  = new MdLitRelDispositivoNormativoCondutaControleRN();
                                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($id_md_lit_disp_normat);
                                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdCondutaLitigioso($id_md_lit_conduta);
                                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdControleLitigioso($objControleLitigiosoDTO->getNumIdControleLitigioso());
                                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setDtaInfracao($dtaInfracao);

                                    if((preg_match('/^novo_/', $DI[0]))){
                                        $objRelDispositivoNormativoCondutaControleLitigiosoRN->cadastrar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);
                                    }else{
                                        $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdDispositivoNormativoNormaCondutaControle($DI[0]);
                                        $objRelDispositivoNormativoCondutaControleLitigiosoRN->alterar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);
                                    }
                                }
                            }
                        }
                    }
                }


                // PROCESSOS A SEREM SOBRESTADOS
                if ($paramsPost['hdnListaPSIndicados'] != '') {
                    $arrObjRelProtocoloProtocoloDTO       = array();
                    $arrRelProtocoloProtocoloLitigiosoDTO = array();

                    $arrPS = PaginaSEI::getInstance()->getArrItensTabelaDinamica($paramsPost['hdnListaPSIndicados']);
                    if (count($arrPS) > 0) {
                        $qtdPS = count($arrPS);
                        for ($j = 0; $j < $qtdPS; $j++) {
                            $PS = $arrPS[$j];
                            if (count($PS) > 0) {

                                //SOBRESTAMENTO - C0RE
                                // Verificando Sobrestamento já existentes
                                $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
                                $objRelProtocoloProtocoloRN  = new RelProtocoloProtocoloRN();
                                $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($paramsPost['idProcedimento']);
                                $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($PS[1]);
                                $objRelProtocoloProtocoloDTO->retTodos();
                                $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO);

                                if (count($objRelProtocoloProtocoloDTO) == 0) {
                                    $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
                                    $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($paramsPost['idProcedimento']);
                                    $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($PS[1]);
                                    $objRelProtocoloProtocoloDTO->setStrMotivo($PS[3] . ' (SEI nº ' . $PS[2] . ') formalizou o sobrestamento via módulo de Controle de Processos Litigiosos');
                                    $arrObjRelProtocoloProtocoloDTO[] = $objRelProtocoloProtocoloDTO;
                                }

                                //SOBRESTAMENTO - LITIGIOSO
                                $objRelProtocoloProtocoloLitigiosoDTO = new MdLitRelProtocoloProtocoloDTO();
                                $objRelProtocoloProtocoloLitigiosoDTO->setNumIdControleLitigioso($objControleLitigiosoDTO->getNumIdControleLitigioso());
                                $objRelProtocoloProtocoloLitigiosoDTO->setDblIdProtocolo1($paramsPost['idProcedimento']);
                                $objRelProtocoloProtocoloLitigiosoDTO->setDblIdProtocolo2($PS[1]);
                                $objRelProtocoloProtocoloLitigiosoDTO->setDblIdDocumento($PS[6]);
                                $objRelProtocoloProtocoloLitigiosoDTO->setStrSinAtivo('S');
                                $objRelProtocoloProtocoloLitigiosoDTO->setDthDtaSobrestamento($PS[4]);
                                $arrRelProtocoloProtocoloLitigiosoDTO[] = $objRelProtocoloProtocoloLitigiosoDTO;
                            }
                        }
                    }


                    //SOBRESTAMENTO - C0RE
                    if (count($arrObjRelProtocoloProtocoloDTO) > 0) {
                        //try {
                        $objProcedimentoRN = new ProcedimentoRN();
                        $objProcedimentoRN->sobrestarRN1014($arrObjRelProtocoloProtocoloDTO);
                        //} catch(Exception $e){
                        //	PaginaSEI::getInstance()->processarExcecao($e);
                        //}
                    }

                    //SOBRESTAMENTO - LITIGIOSO
                    //try {
                    // Excluindo
                    $objRelProtocoloProtocoloLitigiosoDTO = new MdLitRelProtocoloProtocoloDTO();
                    $objRelProtocoloProtocoloLitigiosoRN  = new MdLitRelProtocoloProtocoloRN();
                    $objRelProtocoloProtocoloLitigiosoDTO->retTodos();
                    $objRelProtocoloProtocoloLitigiosoDTO->setNumIdControleLitigioso($objControleLitigiosoDTO->getNumIdControleLitigioso());
                    $objRelProtocoloProtocoloLitigiosoDTO = $objRelProtocoloProtocoloLitigiosoRN->listar($objRelProtocoloProtocoloLitigiosoDTO);

                    $objRelProtocoloProtocoloLitigiosoRN = new MdLitRelProtocoloProtocoloRN();
                    $objRelProtocoloProtocoloLitigiosoRN->excluir($objRelProtocoloProtocoloLitigiosoDTO);

                    // Incluindo
                    $objControleLitigiosoRN = new MdLitControleRN();
                    $objControleLitigiosoRN->sobrestar($arrRelProtocoloProtocoloLitigiosoDTO);
                    //} catch(Exception $e){
                    //	PaginaSEI::getInstance()->processarExcecao($e);
                    //}

                    //			if (stripos(strtolower($_SERVER['HTTP_REFERER']),"id_procedimento=".$_POST['idProcedimento'])>0){
                    //				header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=procedimento_controlar&acao_retorno=procedimento_controlar&id_procedimento=22248&infra_sistema=100000100&infra_unidade_atual=110001198'));
                    //			}

                }

                //INTERESSADOS
                if ($paramsPost['hdnTbInteressado'] != '') {
                    $arrLinhasTbInteressado = PaginaSEI::getInstance()->getArrItensTabelaDinamica($paramsPost['hdnTbInteressado']);
                    $arrObjmdLitDadoInteressadoDTO = array();
                    foreach ($arrLinhasTbInteressado as $key => $linha) {
                        $mdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
                        $mdLitDadoInteressadoDTO->setNumIdContato($linha[0]);
                        $mdLitDadoInteressadoDTO->setNumIdMdLitControle($objControleLitigiosoDTO->getNumIdControleLitigioso());
                        $mdLitDadoInteressadoDTO->setStrSinAtivo('S');
                        $mdLitDadoInteressadoDTO->setDblIdProcedimentoMdLitTipoControle($paramsPost['idProcedimento']);
                        $arrObjmdLitDadoInteressadoDTO[] = $mdLitDadoInteressadoDTO;
                    }

//                    $arrParametros['idProcedimento'] = $paramsPost['idProcedimento'];
//                    $objMdLitControleRN = new MdLitControleRN();
//                    $objMdLitControleRN->removerInteressado($arrParametros);
                    $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
                    $objMdLitDadoInteressadoRN->salvarDadoInteressado($arrObjmdLitDadoInteressadoDTO);

                    if($paramsPost['hdnTbDadoInteressado']){
                        $arrDadosInteressados = array();
                        $hdnTbDadoInteressado = $paramsPost['hdnTbDadoInteressado'];
                        foreach ($hdnTbDadoInteressado as $key => $tbDadosInteressado){
                            $arrDadosInteressados = PaginaSEI::getInstance()->getArrItensTabelaDinamica($paramsPost['hdnTbDadoInteressado'][$key]);
                        }
                        $arrDadosInteressados['id_procedimento']    = $paramsPost['idProcedimento'];
                        $arrDadosInteressados['id_md_lit_controle'] = $objControleLitigiosoDTO->getNumIdControleLitigioso();

                        $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
                        $objMdLitNumeroInteressadoRN->cadastrar($arrDadosInteressados);

                    }
                    //FIM INTERESSADOS;
                }

                //CADASTRAR MOTIVOS

                $arrIdsMotivo = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnMotivos']);
                $mdLitRelControleMotivoDTO = new MdLitRelControleMotivoDTO();
                $mdLitRelControleMotivoRN = new MdLitRelControleMotivoRN();


                $mdLitRelControleMotivoDTO->setNumIdMdLitControle($objControleLitigiosoDTO->getNumIdControleLitigioso());

                $mdLitRelControleMotivoRN->excluirRelacionamentoExistente($mdLitRelControleMotivoDTO);

                for($i = 0 ; $i < count($arrIdsMotivo); $i++) {
                    $mdLitRelControleMotivoDTO->setNumIdMdLitMotivo($arrIdsMotivo[$i]);
                    $mdLitRelControleMotivoRN->cadastrar($mdLitRelControleMotivoDTO);
                }

                return $objControleLitigiosoDTO;
            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Controle Litigioso.', $e);
            }
        }

        protected function existeCadastroControleConectado($idDocumento)
        {
            $objDocumentoDTO = new DocumentoDTO();
            $objDocumentoRN = new DocumentoRN();
            $objDocumentoDTO->setDblIdDocumento($idDocumento);
            $objDocumentoDTO->retDblIdProcedimento();
            $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

            if (!is_null($objDocumentoDTO)) {
                $objMdLitControleDTO = new MdLitControleDTO();
                $objMdLitControleDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
                $objMdLitControleDTO->retDblIdDocumento();

                return $this->contar($objMdLitControleDTO) > 0;
            }

            return false;
        }

        protected function alterarPorProcessoSituacaoControlado(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO){
            $objMdLitControleDTO = new MdLitControleDTO();
            $objMdLitControleDTO->retTodos();
            $objMdLitControleDTO->setDblIdProcedimento($objMdLitProcessoSituacaoDTO->getDblIdProcedimento());
            $objMdLitControleDTO->setNumIdMdLitTipoControle($objMdLitProcessoSituacaoDTO->getNumIdMdLitTipoControle());
            $objMdLitControleDTO->setNumMaxRegistrosRetorno(1);

            $objMdLitControleRN = new MdLitControleRN();
            $objMdLitControleDTO = $objMdLitControleRN->consultar($objMdLitControleDTO);

            $objMdLitControleDTO->setDblIdDocumento($objMdLitProcessoSituacaoDTO->getDblIdDocumento());
            $objMdLitControleDTO->setDtaDataInstauracao($objMdLitProcessoSituacaoDTO->getDtaData());

            $objMdLitControleBD = new MdLitControleBD($this->getObjInfraIBanco());
            $objMdLitControleBD->alterar($objMdLitControleDTO);
        }


    }