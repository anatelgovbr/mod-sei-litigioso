<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitProcessoSituacaoRN extends InfraRN
{
    protected $isEsclusaoSituacao = false;
    public static $SITUACAO_TRANSITO_EM_JULGADO = 16;

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarNumIdMdLitSituacao(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getNumIdMdLitSituacao())) {
            $objInfraException->adicionarValidacao('situação não informada.');
        }
    }

    private function validarDblIdDocumento(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getDblIdDocumento())) {
            $objInfraException->adicionarValidacao('documento não informado.');
        }
    }

    private function validarDblIdProcedimento(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getDblIdProcedimento())) {
            $objInfraException->adicionarValidacao('processo não informado.');
        }
    }

    private function validarNumIdMdLitTipoControle(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getNumIdMdLitTipoControle())) {
            $objInfraException->adicionarValidacao('tipo de controle não informado.');
        }
    }

    private function validarStrDepositoExtrajudicial(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getStrSinDepositoExtrajudicial())) {
            $objMdLitProcessoSituacaoDTO->setStrSinDepositoExtrajudicial(null);
        } else {
            $objMdLitProcessoSituacaoDTO->setStrSinDepositoExtrajudicial(trim($objMdLitProcessoSituacaoDTO->getStrSinDepositoExtrajudicial()));

            if (strlen($objMdLitProcessoSituacaoDTO->getStrSinDepositoExtrajudicial()) > 1) {
                $objInfraException->adicionarValidacao('deposito extrajudicial possui tamanho superior a 1 caracteres.');
            }
        }
    }

    private function validarDblValorDepositoExtrajudicial(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getDblValorDepositoExtrajudicial())) {
            $objMdLitProcessoSituacaoDTO->setDblValorDepositoExtrajudicial(null);
        }
    }

    private function validarDtaDepositoExtrajudicial(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getDtaDtDepositoExtrajudicial())) {
            $objMdLitProcessoSituacaoDTO->setDtaDtDepositoExtrajudicial(null);
        } else {
            if (!InfraData::validarData($objMdLitProcessoSituacaoDTO->getDtaDtDepositoExtrajudicial())) {
                $objInfraException->adicionarValidacao('data do deposito extrajudicial inválida.');
            }
        }
    }

    private function validarDtaData(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getDtaData())) {
            $objInfraException->adicionarValidacao('data não informada.');
        } else {
            if (!InfraData::validarData($objMdLitProcessoSituacaoDTO->getDtaData())) {
                $objInfraException->adicionarValidacao('data inválida.');
            }
        }
    }

    private function validarDtaIntecorrente(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getDtaIntercorrente())) {
            $objMdLitProcessoSituacaoDTO->setDtaIntercorrente(null);
        } else {
            if (!InfraData::validarData($objMdLitProcessoSituacaoDTO->getDtaIntercorrente())) {
                $objInfraException->adicionarValidacao('Data intercorrente inválida.');
            }
        }
    }

    private function validarDtaQuinquenal(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getDtaQuinquenal())) {
            $objMdLitProcessoSituacaoDTO->setDtaQuinquenal(null);
        } else {
            if (!InfraData::validarData($objMdLitProcessoSituacaoDTO->getDtaQuinquenal())) {
                $objInfraException->adicionarValidacao('Data quinquenal inválida.');
            }
        }
    }

    private function validarDthInclusao(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getDthInclusao())) {
            $objInfraException->adicionarValidacao('data de inclusão não informada.');
        } else {
            if (!InfraData::validarDataHora($objMdLitProcessoSituacaoDTO->getDthInclusao())) {
                $objInfraException->adicionarValidacao('data de inclusão inválida.');
            }
        }
    }

    private function validarStrSinAtivo(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitProcessoSituacaoDTO->getStrSinAtivo())) {
            $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
        } else {
            if (!InfraUtil::isBolSinalizadorValido($objMdLitProcessoSituacaoDTO->getStrSinAtivo())) {
                $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
            }
        }
    }

    protected function cadastrarControlado(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_processo_situacao_cadastrar', __METHOD__, $objMdLitProcessoSituacaoDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumIdMdLitSituacao($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarDblIdDocumento($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarDblIdProcedimento($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarNumIdMdLitTipoControle($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarStrDepositoExtrajudicial($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarDblValorDepositoExtrajudicial($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarDtaDepositoExtrajudicial($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarDtaData($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarDtaIntecorrente($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarDtaQuinquenal($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarDthInclusao($objMdLitProcessoSituacaoDTO, $objInfraException);
            $this->validarStrSinAtivo($objMdLitProcessoSituacaoDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            $ret = $objMdLitProcessoSituacaoBD->cadastrar($objMdLitProcessoSituacaoDTO);

            //Auditoria

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Situação.', $e);
        }
    }

    protected function alterarControlado(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_processo_situacao_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objMdLitProcessoSituacaoDTO->isSetNumIdMdLitSituacao()) {
                $this->validarNumIdMdLitSituacao($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetDblIdDocumento()) {
                $this->validarDblIdDocumento($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetDblIdProcedimento()) {
                $this->validarDblIdProcedimento($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetNumIdMdLitTipoControle()) {
                $this->validarNumIdMdLitTipoControle($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetStrSinDepositoExtrajudicial()) {
                $this->validarStrDepositoExtrajudicial($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetDblValorDepositoExtrajudicial()) {
                $this->validarDblValorDepositoExtrajudicial($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetDtaDtDepositoExtrajudicial()) {
                $this->validarDtaDepositoExtrajudicial($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetDtaData()) {
                $this->validarDtaData($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetDtaIntercorrente()) {
                $this->validarDtaIntecorrente($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetDtaQuinquenal()) {
                $this->validarDtaQuinquenal($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetDthInclusao()) {
                $this->validarDthInclusao($objMdLitProcessoSituacaoDTO, $objInfraException);
            }
            if ($objMdLitProcessoSituacaoDTO->isSetStrSinAtivo()) {
                $this->validarStrSinAtivo($objMdLitProcessoSituacaoDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            $objMdLitProcessoSituacaoBD->alterar($objMdLitProcessoSituacaoDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Situação.', $e);
        }
    }

    protected function excluirControlado($arrObjMdLitProcessoSituacaoDTO)
    {

        try {
            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_processo_situacao_excluir');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $arrayAtributos = [
                'id_processo_situacao' => $arrObjMdLitProcessoSituacaoDTO[0]->get('IdMdLitProcessoSituacao'),
                'id_procedimento' => $arrObjMdLitProcessoSituacaoDTO[0]->get('IdProcedimento')
            ];

            //se existir lancamentos nao cancelados dispara a mensagem
            if (MdLitProcessoSituacaoINT::verificarDependenciasSituacaoComLancamentos($arrayAtributos)) {
                $objInfraException->adicionarValidacao('A situação ainda possui multa(s) não cancelada(s)');
                $objInfraException->lancarValidacoes();
                throw new InfraException('Dependências de multa devem ser canceladas antes da exlusão da situação.');
            }

            $objMdLitLancamentoCancelamento = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
            $objMdLitLancamento = new MdLitLancamentoBD($this->getObjInfraIBanco());
            $objMdLitDecisao = new MdLitDecisaoBD($this->getObjInfraIBanco());
            $objMdLitRelDecisaoLancamento = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
            $objMdLitHistoricoLancamento = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
            $objMdLitRelDecisaoUf = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());

            $arrLancamentos = $this->getLancamentosByIdSituacao($arrayAtributos);
            $arrDecisao = $this->getDecisaoByIdSituacao($arrayAtributos);

            foreach ($arrDecisao as $decisao) {
                //exclusao dos relacionamentos entre decisao e lancamento
                $mdLitReLDecisLancamentoDto = new MdLitRelDecisLancamentDTO();
                $mdLitReLDecisLancamentoDto->retTodos();
                $mdLitReLDecisLancamentoDto->set('IdMdLitDecisao', $decisao['id_md_lit_decisao']);

                $mdLitReLDecisLancamentoRn = new MdLitRelDecisLancamentRN();
                $arrRelDecisaoLancamento = $mdLitReLDecisLancamentoRn->listar($mdLitReLDecisLancamentoDto);

                foreach ($arrRelDecisaoLancamento as $relDecisaoLancamento) {
                    $objMdLitRelDecisaoLancamento->excluir($relDecisaoLancamento);
                }

                //exclusao dos relacionamentos entre decisao e UF
                $mdLitRelDecisaoUfDto = new MdLitRelDecisaoUfDTO();
                $mdLitRelDecisaoUfDto->retTodos();
                $mdLitRelDecisaoUfDto->set('IdMdLitDecisao', $decisao['id_md_lit_decisao']);

                $mdLitRelDecisaoUfRn = new MdLitRelDecisaoUfRN();
                $arrRelDecisaoUf = $mdLitRelDecisaoUfRn->listar($mdLitRelDecisaoUfDto);

                foreach ($arrRelDecisaoUf as $relDecisaoUf) {
                    $objMdLitRelDecisaoUf->excluir($relDecisaoUf);
                }

                //exclusao da decisao
                $mdLitDecisaoDto = new MdLitDecisaoDTO();
                $mdLitDecisaoDto->set('IdMdLitDecisao', $decisao['id_md_lit_decisao']);
                $objMdLitDecisao->excluir($mdLitDecisaoDto);
            }

            foreach ($arrLancamentos as $lancamento) {
                //exclusão dos cancelamentos de lancamento
                $mdLitCancelamentoLancamentoDto = new MdLitCancelaLancamentoDTO();
                $mdLitCancelamentoLancamentoDto->retTodos();
                $mdLitCancelamentoLancamentoDto->set('IdMdLitLancamento', $lancamento['id_md_lit_lancamento']);
                $mdLitLancamentoRn = new MdLitCancelaLancamentoRN();
                $arrCancelamentos = $mdLitLancamentoRn->listar($mdLitCancelamentoLancamentoDto);

                foreach ($arrCancelamentos as $cancelamento) {
                    $objMdLitLancamentoCancelamento->excluir($cancelamento);
                }

                //exclusão do historico dos lancamentos
                $mdLitHistoricoLancamentoDto = new MdLitHistoricLancamentoDTO();
                $mdLitHistoricoLancamentoDto->retTodos();
                $mdLitHistoricoLancamentoDto->set('IdMdLitLancamento', $lancamento['id_md_lit_lancamento']);

                $mdLitHistoricoLancamentoRn = new MdLitHistoricLancamentoRN();
                $arrHistoricoLancamento = $mdLitHistoricoLancamentoRn->listar($mdLitHistoricoLancamentoDto);
                foreach ($arrHistoricoLancamento as $historico) {
                    $objMdLitHistoricoLancamento->excluir($historico);
                }

                //exlusao do lancamento
                $mdLitLancamentoDto = new MdLitLancamentoDTO();
                $mdLitLancamentoDto->set('IdMdLitLancamento', $lancamento['id_md_lit_lancamento']);
                $objMdLitLancamento->excluir($mdLitLancamentoDto);
            }


            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdLitProcessoSituacaoDTO); $i++) {

                //se for a situação de instauração recupera
                $resultInstauracao = $this->getSituacaoInstauracao($arrayAtributos);

                // Caso não seja possivel excluir devido a algum relacionamento o registro é simplesmente inativado
                if($this->existeNoHistoricoOuLancamento($arrObjMdLitProcessoSituacaoDTO[$i]->getNumIdMdLitProcessoSituacao())){
                    $objMdLitProcessoSituacaoBD->desativar($arrObjMdLitProcessoSituacaoDTO[$i]);
                } else {
                    $objMdLitProcessoSituacaoBD->excluir($arrObjMdLitProcessoSituacaoDTO[$i]);
                }

                //se a situcação for a situação de instauração remove os dados do controle litigioso
                if ($resultInstauracao) {

                    //recupera o id do controle litigioso
                    $mdLitControleDto = new MdLitControleDTO();
                    $mdLitControleDto->set('IdProcedimento', $resultInstauracao[0]['id_procedimento']);
                    $mdLitControleDto->ret('IdControleLitigioso');
                    $mdLitControleRn = new MdLitControleRN();
                    $mdLitControleDto = $mdLitControleRn->listar($mdLitControleDto);
                    $idControle = $mdLitControleDto[0]->get('IdControleLitigioso');

                    //Exclusão dos dispositivos normativos vinculados ao controle litigioso
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
                    $objRelDispositivoNormativoCondutaControleLitigiosoRN = new MdLitRelDispositivoNormativoCondutaControleRN();
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->ret('IdControleLitigioso');
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->ret('IdDispositivoNormativoNormaCondutaControle');
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdControleLitigioso($idControle);
                    $objRelDispositivoNormativoCondutaControleLitigiosoDTO = $objRelDispositivoNormativoCondutaControleLitigiosoRN->listar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

                    $objRelDispositivoNormativoCondutaControleLitigiosoRN->excluir($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

                    //excluindo SOBRESTAMENTO - LITIGIOSO

                    $objRelProtocoloProtocoloLitigiosoDTO = new MdLitRelProtocoloProtocoloDTO();
                    $objRelProtocoloProtocoloLitigiosoRN = new MdLitRelProtocoloProtocoloRN();
                    $objRelProtocoloProtocoloLitigiosoDTO->ret('IdRelProtocoloProtocolo');
                    $objRelProtocoloProtocoloLitigiosoDTO->setNumIdControleLitigioso($idControle);
                    $objRelProtocoloProtocoloLitigiosoDTO = $objRelProtocoloProtocoloLitigiosoRN->listar($objRelProtocoloProtocoloLitigiosoDTO);

                    $objRelProtocoloProtocoloLitigiosoRN->excluir($objRelProtocoloProtocoloLitigiosoDTO);

                    //Excluindo Dados do interessado
                    $dadosInteressadoRN = new MdLitDadoInteressadoRN();

                    $mdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
                    $mdLitDadoInteressadoDTO->ret('IdProcedimentoMdLitTipoControle');
                    $mdLitDadoInteressadoDTO->ret('IdContato');
                    $mdLitDadoInteressadoDTO->ret('IdMdLitControle');
                    $mdLitDadoInteressadoDTO->ret('IdMdLitDadoInteressado');
                    $mdLitDadoInteressadoDTO->setNumIdMdLitControle($idControle);
                    $arrDadoInteressadoDTO = $dadosInteressadoRN->listar($mdLitDadoInteressadoDTO);
                    $dadosInteressadoRN->removerInteressadoLimparControleLitigioso($arrDadoInteressadoDTO);

                    $mdLitRelControleMotivoDTO = new MdLitRelControleMotivoDTO();
                    $mdLitRelControleMotivoRN = new MdLitRelControleMotivoRN();
                    $mdLitRelControleMotivoDTO->setNumIdMdLitControle($idControle);
                    $mdLitRelControleMotivoRN->excluirRelacionamentoExistente($mdLitRelControleMotivoDTO);

                    //Excluindo Controle litigioso
                    $mdLitControleDto = new MdLitControleDTO();
                    $mdLitControleDto->set('IdControleLitigioso', $idControle);

                    $controleLitigiosoRn = new MdLitControleRN();
                    $controleLitigiosoRn->excluir($mdLitControleDto);
                }
            }


            //aqui deve repor sin_ultima_decisao em decisoes anteriores relacionadas a outra situacao/decisao existente
            $this->_restabelecerSinUltimaDecisao($arrObjMdLitProcessoSituacaoDTO[0]->get('IdProcedimento'));

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Situação.', $e);
        }
    }

    public function existeNoHistoricoOuLancamento($idSituacaoCadastro)
    {
        $objLancamentoDTO = new MdLitLancamentoDTO();
        $objLancamentoDTO->retNumIdMdLitLancamento();
        $objLancamentoDTO->adicionarCriterio(
          array('IdMdLitSituacaoDecisaoDefin','IdSituacaoDecisao', 'IdSituacaoIntimacao', 'IdSituacaoRecurso'),
          array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
          array($idSituacaoCadastro,$idSituacaoCadastro,$idSituacaoCadastro,$idSituacaoCadastro),
          array(InfraDTO::$OPER_LOGICO_OR,InfraDTO::$OPER_LOGICO_OR,InfraDTO::$OPER_LOGICO_OR)
        );

        $objLancamentoDTO = (new MdLitLancamentoRN())->listar($objLancamentoDTO);

        $objHistLancamentoDTO = new MdLitHistoricLancamentoDTO();
        $objHistLancamentoDTO->retNumIdMdLitHistoricLancamento();
        $objHistLancamentoDTO->adicionarCriterio(
          array('IdMdLitSituacaoDecisaoDefin','IdSituacaoDecisao', 'IdSituacaoIntimacao'),
          array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
          array($idSituacaoCadastro,$idSituacaoCadastro,$idSituacaoCadastro),
          array(InfraDTO::$OPER_LOGICO_OR,InfraDTO::$OPER_LOGICO_OR)
        );
        $objHistLancamentoDTO = (new MdLitHistoricLancamentoRN())->listar($objHistLancamentoDTO);

        if ($objLancamentoDTO || $objHistLancamentoDTO) {
            return true;
        }

        return false;
    }

    public function getSituacaoInstauracao($data)
    {
        $sql = " select s.sin_instauracao, ps.* 
                from md_lit_situacao s
                inner join md_lit_processo_situacao ps on ps.id_md_lit_situacao = s.id_md_lit_situacao
                where s.sin_instauracao = 'S' and s.sin_ativo = 'S'
                and ps.id_md_lit_processo_situacao =  " . (int)$data['id_processo_situacao'];

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql($sql);
        return $arr;

    }

    public function getLancamentosByIdSituacao($data)
    {
        $sql = "select distinct
                    dl.id_md_lit_lancamento
                from md_lit_processo_situacao ps
                         inner join md_lit_decisao d on d.id_md_lit_processo_situacao = ps.id_md_lit_processo_situacao
                         inner join md_lit_rel_decis_lancament dl on dl.id_md_lit_decisao = d.id_md_lit_decisao
                         inner join md_lit_lancamento l on l.id_procedimento = ps.id_procedimento
                where ps.id_md_lit_processo_situacao =  " . (int)$data['id_processo_situacao'] . "
                    and ps.id_procedimento = " . (int)$data['id_procedimento'] . "
                    -- menos os que estao relacionados a outras decisoes no mesmo processo
                    and dl.id_md_lit_lancamento not in (
                        select distinct dl.id_md_lit_lancamento
                        from md_lit_processo_situacao ps
                                 inner join md_lit_decisao d on d.id_md_lit_processo_situacao = ps.id_md_lit_processo_situacao
                                 inner join md_lit_rel_decis_lancament dl on dl.id_md_lit_decisao = d.id_md_lit_decisao
                                 inner join md_lit_lancamento l on l.id_procedimento = ps.id_procedimento
                        where ps.id_md_lit_processo_situacao <> " . (int)$data['id_processo_situacao'] . "
                        and ps.id_procedimento = " . (int)$data['id_procedimento'] . "
                    )";

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql($sql);
        return $arr;
    }

    public function getDecisaoByIdSituacao($data)
    {
        $sql = "select distinct d.*
                from md_lit_processo_situacao ps
                         inner join md_lit_decisao d on d.id_md_lit_processo_situacao = ps.id_md_lit_processo_situacao
                where ps.id_md_lit_processo_situacao =" . (int)$data['id_processo_situacao'];

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql($sql);
        return $arr;
    }

    public function getRelacionamentodecisaoLancamento($data)
    {
        $sql = "select rdl.*
                    from md_lit_rel_decis_lancament rdl
                where rdl.id_md_lit_decisao  =" . (int)$data['id_md_lit_decisao'];

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql($sql);
        return $arr;
    }

    public function getRelacionamentoDecisaoUf($data)
    {
        $sql = "select rduf.*
                    from md_lit_rel_decisao_uf rduf
                where rduf.id_md_lit_decisao  =" . (int)$data['id_md_lit_decisao'] . ";";

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql($sql);
        return $arr;
    }

    public function getCancelamentosLancamento($data)
    {
        $sql = "select  c.*
                    from md_lit_cancela_lancamento c
                where c.id_md_lit_lancamento = " . (int)$data['id_md_lit_lancamento'] . ";";

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql($sql);
        return $arr;
    }

    public function getHistoricoLancamento($data)
    {
        $sql = "select hl.*
                    from md_lit_historic_lancamento hl
                where hl.id_md_lit_lancamento = " . (int)$data['id_md_lit_lancamento'] . ";";

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql($sql);
        return $arr;
    }

    public function getControleLitigioso($data)
    {
        $sql = "select * from md_lit_controle where id_procedimento = " . (int)$data . ";";

        $instanciaBanco = BancoSEI::getInstance();
        $arr = $instanciaBanco->consultarSql($sql);
        return $arr;
    }

    protected function consultarConectado(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_processo_situacao_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            $ret = $objMdLitProcessoSituacaoBD->consultar($objMdLitProcessoSituacaoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Situação.', $e);
        }
    }

    protected function listarConectado(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_processo_situacao_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            $ret = $objMdLitProcessoSituacaoBD->listar($objMdLitProcessoSituacaoDTO);

            //Auditoria

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando Situações.', $e);
        }
    }

    protected function contarConectado($objMdLitProcessoSituacaoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_processo_situacao_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            $ret = $objMdLitProcessoSituacaoBD->contar($objMdLitProcessoSituacaoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando Situações.', $e);
        }
    }

    protected function desativarControlado($arrObjMdLitProcessoSituacaoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_processo_situacao_desativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdLitProcessoSituacaoDTO); $i++) {
                $objMdLitProcessoSituacaoBD->desativar($arrObjMdLitProcessoSituacaoDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro desativando Situação.', $e);
        }
    }

    protected function reativarControlado($arrObjMdLitProcessoSituacaoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_processo_situacao_reativar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdLitProcessoSituacaoDTO); $i++) {
                $objMdLitProcessoSituacaoBD->reativar($arrObjMdLitProcessoSituacaoDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro reativando Situação.', $e);
        }
    }

    protected function bloquearControlado(MdLitProcessoSituacaoDTO $objMdLitProcessoSituacaoDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_processo_situacao_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
            $ret = $objMdLitProcessoSituacaoBD->bloquear($objMdLitProcessoSituacaoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro bloqueando Situação.', $e);
        }
    }

    protected function buscarDadosDocumentoConectado($arrParams)
    {
        $idDocumento = $arrParams[0];

        if (!is_null($idDocumento)) {
            $numeroSei = array_key_exists(1, $arrParams) ? $arrParams[1] : false;
            $numeroSei = trim($numeroSei);
            $idTpControle = array_key_exists(2, $arrParams) ? $arrParams[2] : false;
            $idProcedimento = array_key_exists(3, $arrParams) ? $arrParams[3] : false;
            $idDocAlterar = array_key_exists(4, $arrParams) ? $arrParams[4] : false;

            $dthDocumento = '';
            $objDocumentoDTO = new DocumentoDTO();

            if ($idDocumento) {
                $objDocumentoDTO->setDblIdDocumento($idDocumento);
            }

            if ($numeroSei) {
                $objDocumentoDTO->setStrProtocoloDocumentoFormatado($numeroSei);
            }

            $objDocumentoDTO->retDblIdDocumento();
            $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
            $objDocumentoDTO->retArrObjAssinaturaDTO();
            $objDocumentoDTO->retStrStaDocumento();
            $objDocumentoDTO->retDtaGeracaoProtocolo();
            $objDocumentoDTO->retStrNomeSerie();
            $objDocumentoDTO->retNumIdSerie();
            $objDocumentoDTO->retDblIdProcedimento();
            $objDocumentoDTO->retStrNumero();
            $objDocumentoDTO->setNumMaxRegistrosRetorno(1);

            $objDocumentoRN = new DocumentoRN();
            $objDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

            if (!is_null($objDocumentoDTO)) {
                $objDocumentoDTO = current($objDocumentoDTO);
                if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO) {
                    $arrAssinatura = $objDocumentoDTO->getArrObjAssinaturaDTO();
                    if ($arrAssinatura) {
                        $objAssinaturaDTO = new AssinaturaDTO();
                        $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
                        $objAssinaturaDTO->retDthAberturaAtividade();
                        $objAssinaturaDTO->setOrdDthAberturaAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);
                        $objAssinaturaRN = new AssinaturaRN();
                        $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);
                        $countAss = $objAssinaturaRN->contarRN1324($objAssinaturaDTO);
                        if ($countAss > 0) {
                            $dthDocumento = $arrObjAssinaturaDTO[0]->getDthAberturaAtividade();
                        }
                    }
                }

                if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO) {
                    $dthDocumento = $objDocumentoDTO->getDtaGeracaoProtocolo();
                }
            }

            return $this->_formatarObjDocumento($objDocumentoDTO, $dthDocumento, $idTpControle, $idProcedimento, $idDocAlterar);
        } else {
            return array('erro' => true);
        }
    }

    private function _formatarObjDocumento($objDocumentoDTO, $dthDocumento, $idTpControle, $idProcedimento, $idDocAlterar)
    {
        $xml = '';
        $dados = array();
        $dados['erro'] = true;
        $dados['msg'] = '';
        $isDtDocPreenchida = $dthDocumento != '';
        $docsPermt = false;

        if (!is_null($objDocumentoDTO)) {
            $docsPermt = $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO;
            if ($isDtDocPreenchida && $docsPermt) {
                $arrDt = explode(' ', $dthDocumento);
                $dados['erro'] = false;
                $dados['msg'] = '';
                $dados['numeroSei'] = $objDocumentoDTO->getStrProtocoloDocumentoFormatado();
                $dados['dtDocumento'] = $arrDt[0];
                $dados['tipoDocumento'] = $objDocumentoDTO->getStrNomeSerie();
                $dados['idSerie'] = $objDocumentoDTO->getNumIdSerie();
                $dados['idDocumento'] = $objDocumentoDTO->getDblIdDocumento();
                $dados['urlValidada'] = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_procedimento=' . $idProcedimento . '&id_documento=' . $objDocumentoDTO->getDblIdDocumento() . '&arvore=1');
                $dados['tituloDoc'] = $this->_retornaTitleDocumento($objDocumentoDTO->getStrNomeSerie(), $objDocumentoDTO->getStrNumero());

            }
        }

        if ($idDocAlterar == '0') {
            $this->_realizarValidacoesNumeroSei($dados, $objDocumentoDTO, $isDtDocPreenchida, $docsPermt, $idTpControle, $idProcedimento);
        }

        return $dados;
    }

    private function _realizarValidacoesNumeroSei(&$dados, $objDocumentoDTO, $isDtDocPreenchida, $docsPermt, $idTpControle, $idProcedimento)
    {
        if (is_null($objDocumentoDTO)) {
            $dados['msg'] = 'Número SEI Inválido.';
            return true;
        }

        //Verificando se documento pertence a esse processo
        if ($idProcedimento) {
            if ($idProcedimento != $objDocumentoDTO->getDblIdProcedimento()) {
                $dados['msg'] = 'Este documento não pertence a este processo.';
                $dados['erro'] = true;
                return true;
            }
        }

        if ((!$isDtDocPreenchida && $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO)) {
            $dados['msg'] = 'Documento Interno não possui assinatura.';
            return true;
        }

        if (!$docsPermt) {
            $dados['msg'] = 'Os documentos permitidos para realizar este cadastro devem ser Interno ou Externo.';
            return true;
        }

        //Verificando Vínculo de Tipo de Controle/Parametrização de Situação com este tipo de Documento
        if ($idTpControle) {
            $isValido = $this->_isDocVinculadoTpControle($idTpControle, $objDocumentoDTO->getNumIdSerie());

            if (!$isValido) {
                $dados['msg'] = 'Este documento não está vinculado ao Tipo de Controle deste processo.';
                $dados['erro'] = true;
                return true;
            }
        }

        $objMdLitControleRN = new MdLitControleRN();
        $existeVinculo = $objMdLitControleRN->existeCadastroControle($objDocumentoDTO->getDblIdDocumento());

        //Verificando se possui situação cadastrada
        if (!$existeVinculo) {
            $dados['msg'] = 'Este documento não possui Tipo de Controle Cadastrado.';
            $dados['erro'] = true;
            return true;
        }

    }

    private function _isDocVinculadoTpControle($idTpControle, $idSerie)
    {
        $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
        $objMdLitSituacaoRN = new MdLitSituacaoRN();
        $objMdLitSerieRN = new MdLitRelSituacaoSerieRN();

        $objMdLitSituacaoDTO->retNumIdSituacaoLitigioso();
        $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($idTpControle);

        $countIsSituacao = $objMdLitSituacaoRN->contar($objMdLitSituacaoDTO) > 0;

        //Busca todas as situações dos tipos de controle que possuem esse processo vinculado
        if ($countIsSituacao) {
            $idsSituacao = InfraArray::converterArrInfraDTO($objMdLitSituacaoRN->listar($objMdLitSituacaoDTO), 'IdSituacaoLitigioso');
            //Verifica se alguma das situações possui esse documento vinculado
            $objMdLitSerieDTO = new MdLitRelSituacaoSerieDTO();
            $objMdLitSerieDTO->setNumIdSituacaoLitigioso($idsSituacao, InfraDTO::$OPER_IN);
            $objMdLitSerieDTO->setNumIdSerie($idSerie);
            $isValido = $objMdLitSerieRN->contar($objMdLitSerieDTO) > 0;
        }

        return $isValido;

    }

    /* Método responsável por criar um array para identificar a partir de que momento o processo recebeu a instimação da instauração
     *  Necessário para controlar a exibição e o cadastro da instauração
     * */
    private function verificarExistenciaInstauracao($arrObjMdLitProcSitDTO, $idProcedimento, $idTipoControle)
    {
        $arrRetorno = array();

        $objMdProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        //Select
        $objMdProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        //Where
        $objMdProcessoSituacaoDTO->setNumIdMdLitTipoControle($idTipoControle);
        $objMdProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $objMdProcessoSituacaoDTO->setStrSinIntimacaoSit('S');
        //order By
        $objMdProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);
        //Limit
        $objMdProcessoSituacaoDTO->setNumMaxRegistrosRetorno('1');

        $objDTO = $this->consultar($objMdProcessoSituacaoDTO);

        $instauracao = '0';
        if (count($arrObjMdLitProcSitDTO) > 0) {
            if (!is_null($objDTO)) {
                $idProcSitDTOInitInsta = $objDTO->getNumIdMdLitProcessoSituacao();
            }
            foreach ($arrObjMdLitProcSitDTO as $objMdLitProcSitDTO) {

                $arrRetorno[$objMdLitProcSitDTO->getNumIdMdLitProcessoSituacao()] = $instauracao;
                if (!is_null($objDTO)) {
                    if ($idProcSitDTOInitInsta == $objMdLitProcSitDTO->getNumIdMdLitProcessoSituacao()) {
                        $instauracao = '1';
                    }
                }

            }
        }

        return $arrRetorno;
    }

    protected function retornaDadosSituacoesCadastradasConectado($arrParams)
    {
        $idProcedimento = array_key_exists('0', $arrParams) ? $arrParams[0] : '0';
        $idTpControle = array_key_exists('1', $arrParams) ? $arrParams[1] : '0';

        $objSituacaoLitRN = new MdLitSituacaoRN();

        $objMdLitSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $this->_addSelectDefaultProcessoSituacao($objMdLitSituacaoDTO);
        $objMdLitSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $count = $this->contar($objMdLitSituacaoDTO);
        $arrObjMdLitSituacaoDTO = $this->listar($objMdLitSituacaoDTO);

        $arrVinculos = $objSituacaoLitRN->getArrSituacaoTipoSituacao($arrObjMdLitSituacaoDTO);

        //Esse array verifica se existe instauração para exibir ou não o alterar prescrição
        $arrIsInstauracao = $this->verificarExistenciaInstauracao($arrObjMdLitSituacaoDTO, $idProcedimento, $idTpControle);
        $arrGrid = array();

        if ($count > 0) {
            $ordem = 0;
            foreach ($arrObjMdLitSituacaoDTO as $key => $objDTO) {
                //Formatar Data
                $dth = !is_null($objDTO->getDthInclusao()) ? explode(" ", $objDTO->getDthInclusao()) : '';
                $dth = count($dth) > 0 ? $dth[0] : $dth;

                $ordem++;
                $tipoSituacao = array_key_exists($objDTO->getNumIdMdLitSituacao(), $arrVinculos) && $arrVinculos[$objDTO->getNumIdMdLitSituacao()][0] != '' ? ' (' . $arrVinculos[$objDTO->getNumIdMdLitSituacao()][0] . ')' : '';
                $labelSituacao = array_key_exists($objDTO->getNumIdMdLitSituacao(), $arrVinculos) && $arrVinculos[$objDTO->getNumIdMdLitSituacao()][1] != '' ? $arrVinculos[$objDTO->getNumIdMdLitSituacao()][1] : '';

                $isInstauracao = array_key_exists($objDTO->getNumIdMdLitProcessoSituacao(), $arrIsInstauracao) && $arrIsInstauracao[$objDTO->getNumIdMdLitProcessoSituacao()] != '' ? $arrIsInstauracao[$objDTO->getNumIdMdLitProcessoSituacao()] : '';
                $descricaoPrazo = $objDTO->getNumPrazoSituacao() != '' ? ' - Prazo: '.$objDTO->getNumPrazoSituacao().' dias' : '';
                $nomeSit = $objDTO->getStrNomeSituacao() . $tipoSituacao . $descricaoPrazo;
                $valorDep = !is_null($objDTO->getDblValorDepositoExtrajudicial()) ? $this->_formatarValorExibicao($objDTO->getDblValorDepositoExtrajudicial()) : '';
                $dtDep = !is_null($objDTO->getDtaDtDepositoExtrajudicial()) ? $objDTO->getDtaDtDepositoExtrajudicial() : '';
                $dtIntercor = !is_null($objDTO->getDtaIntercorrente()) ? $objDTO->getDtaIntercorrente() : '';
                $dtQuinq = !is_null($objDTO->getDtaQuinquenal()) ? $objDTO->getDtaQuinquenal() : '';

                //Formatar Nome Unidade
                $rdAlteraPresc = $objDTO->getStrSinAlteraPrescricao() == 'S' ? '1' : '0';
                $nomeUnidade = '<a alt="' . $objDTO->getStrDescricaoUnidade() . '" title="' . $objDTO->getStrDescricaoUnidade() . '" class="ancoraSigla"> ' . $objDTO->getStrSiglaUnidade() . ' </a>';
                $nomeUsuario = '<a alt="' . $objDTO->getStrNomeUsuario() . '" title="' . $objDTO->getStrNomeUsuario() . '" class="ancoraSigla"> ' . $objDTO->getStrSiglaUsuario() . ' </a>';

                $mostrarMensagemRecursal = null;
                if($tipoSituacao == ' (Recursal)'){
                    $mostrarMensagemRecursal = "alert('A situação " . $nomeSit . " não pode ser excluída, pois está relacionada a uma Suspensão de Lançamento em Razão de Recurso. \nAntes de excluir esta Situação deve primeiro Cancelar ou Denegar o Recurso.');";
                }

                $linkDocFormt = $this->_retornaLinkDocFormatado($objDTO->getStrProtocoloFormatadoDocumento(), $objDTO->getDblIdProcedimento(), $objDTO->getDblIdDocumento(), $objDTO->getStrNomeSerie(), $objDTO->getStrNumeroDocumento());
                $urlLink = $this->_retornaLinkDocFormatado($objDTO->getStrProtocoloFormatadoDocumento(), $objDTO->getDblIdProcedimento(), $objDTO->getDblIdDocumento(), $objDTO->getStrNomeSerie(), $objDTO->getStrNumeroDocumento(), true , $mostrarMensagemRecursal);
                $sinDepositoExtrajudicial = $objDTO->getStrSinDepositoExtrajudicial();

                $arrGrid[] = array(
                  $objDTO->getNumIdMdLitProcessoSituacao(),
                  'B',
                  $objDTO->getDblIdProcedimento(),
                  $objDTO->getNumIdMdLitFase(),
                  $objDTO->getNumIdMdLitSituacao(),
                  $objDTO->getNumIdUsuario(),
                  $objDTO->getNumIdUnidade(),
                  $dtIntercor,
                  $dtQuinq,
                  $linkDocFormt,
                  $objDTO->getStrNomeSerie(),
                  $objDTO->getDtaData(),
                  $objDTO->getStrNomeFase(),
                  $nomeSit,
                  $dth,
                  $nomeUsuario,
                  $nomeUnidade,
                  $tipoSituacao,
                  $valorDep,
                  $dtDep,
                  $objDTO->getNumIdMdLitProcessoSituacao(),
                  $ordem,
                  $rdAlteraPresc,
                  $labelSituacao,
                  $objDTO->getDblIdDocumento(),
                  $isInstauracao,
                  $objDTO->getStrProtocoloFormatadoDocumento(),
                  $urlLink,
                  $sinDepositoExtrajudicial,
                  $objDTO->getStrSinRecursalSit(),
                  $objDTO->getNumPrazoSituacao()
                );
            }
        }

        return $arrGrid;
    }

    private function _retornaLinkDocFormatado($numDoc, $idProcedimento, $idDocumento, $tipoDoc, $numero, $returnUrl = false, $mostrarMensagem = null)
    {
        $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_procedimento=' . $idProcedimento . '&id_documento=' . $idDocumento . '&arvore=1');

        if ($returnUrl) {
            return $strLink;
        }

        if(is_null($mostrarMensagem)) {
            $js = 'window.open(\'' . $strLink . '\');';
        } else {
            $js = $mostrarMensagem;
        }
        $title = $this->_retornaTitleDocumento($tipoDoc, $numero);

        $conteudoHtml = '<a title="' . $title . '" class="ancoraPadraoAzul" onclick="' . $js . '">';
        $conteudoHtml .= $numDoc;
        $conteudoHtml .= '</a>';

        return $conteudoHtml;
    }

    private function _retornaTitleDocumento($tipoDoc, $numero)
    {
        $ret = $tipoDoc;

        if (!is_null($numero)) {
            $ret .= ' ' . $numero;
        }

        return $ret;
    }

    private function _formatarValorExibicao($vlBanco)
    {
        $vlBanco = str_replace(',', '.', $vlBanco);
        $vlBanco = number_format($vlBanco, 2, ',', '.');

        return $vlBanco;
    }

    private function _addSelectDefaultProcessoSituacao(&$objMdLitProcessoSitDTO)
    {
        $objMdLitProcessoSitDTO->retDblIdProcedimento();
        $objMdLitProcessoSitDTO->retDblIdDocumento();
        $objMdLitProcessoSitDTO->retNumIdSerie();
        $objMdLitProcessoSitDTO->retStrNomeSerie();
        $objMdLitProcessoSitDTO->retDtaData();
        $objMdLitProcessoSitDTO->retStrNomeSituacao();
        $objMdLitProcessoSitDTO->retNumIdMdLitFase();
        $objMdLitProcessoSitDTO->retStrNomeFase();
        $objMdLitProcessoSitDTO->retDthInclusao();
        $objMdLitProcessoSitDTO->retDblIdProtocoloDocumento();
        $objMdLitProcessoSitDTO->retStrProtocoloFormatadoDocumento();
        $objMdLitProcessoSitDTO->retNumIdUnidade();
        $objMdLitProcessoSitDTO->retStrSiglaUnidade();
        $objMdLitProcessoSitDTO->retStrDescricaoUnidade();
        $objMdLitProcessoSitDTO->retNumIdUsuario();
        $objMdLitProcessoSitDTO->retStrNomeUsuario();
        $objMdLitProcessoSitDTO->retStrSiglaUsuario();
        $objMdLitProcessoSitDTO->retNumIdMdLitSituacao();
        $objMdLitProcessoSitDTO->retDtaIntercorrente();
        $objMdLitProcessoSitDTO->retDtaQuinquenal();
        $objMdLitProcessoSitDTO->retStrSinAtivo();
        $objMdLitProcessoSitDTO->retDblValorDepositoExtrajudicial();
        $objMdLitProcessoSitDTO->retDtaDtDepositoExtrajudicial();
        $objMdLitProcessoSitDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSitDTO->retStrSinAlteraPrescricao();
        $objMdLitProcessoSitDTO->retStrNumeroDocumento();
        $objMdLitProcessoSitDTO->retStrSinDepositoExtrajudicial();
        $objMdLitProcessoSitDTO->retStrSinRecursalSit();
        $objMdLitProcessoSitDTO->retNumPrazoSituacao();
    }

    protected function verificaDadosSitAtualSitAnteriorConectado($arrParams)
    {

        $idProcedimento = $arrParams[0];
        $dadosSitAtual = $arrParams[1];
        $idTpControle = $arrParams[2];

        $objMdLitSitProcessoDTO = $this->_retornaDadosSituacaoAnteriorCadastrada($idProcedimento);

        if ($this->contar($objMdLitSitProcessoDTO) > 0) {
            $anteriorIsIntimacao = $objMdLitSitProcessoDTO->getStrSinIntimacaoSit() == 'S' ? true : false;
            $retorno = $this->_formatarRetornoDadosSituacaoAnterior($objMdLitSitProcessoDTO->getNumOrdemParametrizarSit(), $objMdLitSitProcessoDTO->getStrNomeFase() . ' / ' . $objMdLitSitProcessoDTO->getStrNomeSituacao(), $dadosSitAtual, $idTpControle, $idProcedimento, $anteriorIsIntimacao);
            return $retorno;
        }

        return array();
    }

    private function _formatarRetornoDadosSituacaoAnterior($ordemAnterior, $nomeSituacaoAnterior, $dadosSitAtual, $idTpControle, $idProcedimento, $anteriorIsIntimacao = false)
    {
        $objMdLitSituacaoRN = new MdLitSituacaoRN();
        $dadosRet = array();

        $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
        $objMdLitSituacaoDTO->retNumIdSituacaoLitigioso();
        $objMdLitSituacaoDTO->retStrNome();
        $objMdLitSituacaoDTO->retStrNomeFase();
        $objMdLitSituacaoDTO->retStrSinDefesa();
        $objMdLitSituacaoDTO->retNumOrdem();
        $objMdLitSituacaoDTO->retStrSinIntimacao();
        $objMdLitSituacaoDTO->retStrSinOpcional();
        $objMdLitSituacaoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitSituacaoDTO->setNumMaxRegistrosRetorno('1');
        $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($idTpControle);

        //Busca a próxima ordem de acordo com a ordem da situação anterior
        $objMdLitSituacaoDTO->adicionarCriterio(array('Ordem'),
            array(InfraDTO::$OPER_MAIOR),
            array($ordemAnterior));

        //Add diferente de Livre
        $objMdLitSituacaoDTO->adicionarCriterio(array('SinInstauracao', 'SinConclusiva', 'SinDecisoria', 'SinIntimacao', 'SinDefesa', 'SinRecursal', 'SinObrigatoria', 'SinAlegacoes'),
            array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
            array('S', 'S', 'S', 'S', 'S', 'S', 'S', 'S'),
            array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR));

        $objMdLitSituacaoDTO = $objMdLitSituacaoRN->consultar($objMdLitSituacaoDTO);

        if (!is_null($objMdLitSituacaoDTO)) {

            $idProxSituacao = $objMdLitSituacaoDTO->getNumIdSituacaoLitigioso();
            $isConclusiva = $dadosSitAtual['nome'] == 'Conclusiva';

            //Verifica se o id da próxima situação cadastrada é o id da situação que foi selecionado pelo usuário ou se é conclusiva e o anterior é intimação.
            if ($idProxSituacao == $dadosSitAtual['idSituacao'] || ($isConclusiva && $anteriorIsIntimacao)) {
                $dadosRet['msgExibicao'] = $this->_retornaMsgExibicao(false, $nomeSituacaoAnterior, $dadosSitAtual);
                $dadosRet['erro'] = false;

//Adicionado para verificar se é opcional. Caso seja opcional repassa as informações da situação atual, como anterior, e entra em recursividade até chegar no item que não seja opcional
            } elseif ($objMdLitSituacaoDTO->getStrSinOpcional() == 'S') {
                return $this->_formatarRetornoDadosSituacaoAnterior($objMdLitSituacaoDTO->getNumOrdem(), $nomeSituacaoAnterior, $dadosSitAtual, $idTpControle, $idProcedimento);

                /**  caso a situação Decisoria opcional seja adicionada em um processo, então a Intimação subsequente não marcada como
                 *   Opcional torna-se obrigatória, e caso a Decisão opcional não seja adicionada em um processo,
                 *    então a Intimação subsequente não marcada como Opcional torna-se opcional.
                 */
            } elseif ($objMdLitSituacaoDTO->getStrSinIntimacao() == 'S') {
                //
                $arrObjSituacaoIntimacaoProximoDecisorioOpcional = $this->buscarSituacaoIntimacaoProximoDecisorioOpcional($idProcedimento, $idTpControle);
                $arrObjSituacaoIntimacaoProximoDecisorioOpcional = InfraArray::converterArrInfraDTO($arrObjSituacaoIntimacaoProximoDecisorioOpcional, 'IdSituacaoLitigioso');

                if (count($arrObjSituacaoIntimacaoProximoDecisorioOpcional) > 0 && in_array($idProxSituacao, $arrObjSituacaoIntimacaoProximoDecisorioOpcional))
                    return $this->_formatarRetornoDadosSituacaoAnterior($objMdLitSituacaoDTO->getNumOrdem(), $nomeSituacaoAnterior, $dadosSitAtual, $idTpControle, $idProcedimento);
            }
        }


        if (count($dadosRet) == 0) {
            //Caso o arr não esteja preenchido, retorna com erro
            $dadosRet['msgExibicao'] = $this->_retornaMsgExibicao(true, $nomeSituacaoAnterior, $dadosSitAtual, $objMdLitSituacaoDTO);
            $dadosRet['erro'] = true;
        }

        return $dadosRet;
    }

    public function retornaDadosUltimaSituacaoCadastrada($idProcedimento)
    {
        return $this->_retornaDadosSituacaoAnteriorCadastrada($idProcedimento);
    }

    public function retornaMsgSituacaoObrigatoria($ultimaSituacaoCadastrada, $situacaoInformada, $situacaoObrigatoria)
    {
        return 'A situação ' . $situacaoInformada->getStrNomeFase() . ' / ' . $situacaoInformada->getStrNome() . ' selecionada acima estará associada à Situação ' . $ultimaSituacaoCadastrada->getStrNomeFase() . ' / ' . $ultimaSituacaoCadastrada->getStrNomeSituacao() . ', já adicionada na lista abaixo, para qual a Situação esperada seria ' . $situacaoObrigatoria->getStrNomeFase() . ' / ' . $situacaoObrigatoria->getStrNome() . '. Favor selecionar a Fase e Situação correta, de acordo com a ordem da parametrização do Tipo de Controle Litigioso acima.';
    }

    private function _retornaMsgExibicao($excecao, $nomeSituacaoUltima, $dadosSitAtual, $objMdLitSituacaoEsperadaDTO = null)
    {
        $msg = '';
        $objMdSitucaoRN = new MdLitSituacaoRN();
        $nomeSituacaoAtual = $dadosSitAtual['nomeLabel'];


        if ($excecao) {
            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->retNumIdSituacaoLitigioso();
            $objMdLitSituacaoDTO->retStrNome();
            $objMdLitSituacaoDTO->retStrNomeFase();
            $objMdLitSituacaoDTO->setNumIdSituacaoLitigioso($dadosSitAtual['idSituacao']);

            $objMdLitSituacaoDTO = $objMdSitucaoRN->consultar($objMdLitSituacaoDTO);
            $nomeSituacaoEsperada = $objMdLitSituacaoEsperadaDTO ? $objMdLitSituacaoEsperadaDTO->getStrNome() : '';
            $nomeFaseEsperada = $objMdLitSituacaoEsperadaDTO ? $objMdLitSituacaoEsperadaDTO->getStrNomeFase() : '';

//        'A situação <Fase / Situação> selecionada acima estará associada à Situação <Fase / Situação: última situação obrigatória adicionada na grid>, já adicionada na lista abaixo, para qual a Situação esperada seria <Fase / Situação: situação esperada, de acordo com a parametrização>. Favor selecionar a Fase e Situação correta, de acordo com a ordem da parametrização do Tipo de Controle Litigioso acima.';
            $msg = 'A situação ' . $objMdLitSituacaoDTO->getStrNomeFase() . ' / ' . $objMdLitSituacaoDTO->getStrNome() . ' selecionada acima estará associada à Situação ' . $nomeSituacaoUltima . ', já adicionada na lista abaixo, para qual a Situação esperada seria ' . $nomeFaseEsperada . ' / ' . $nomeSituacaoEsperada . '. Favor selecionar a Fase e Situação correta, de acordo com a ordem da parametrização do Tipo de Controle Litigioso acima.';
        } else {
            $msg = 'A Situação ' . $nomeSituacaoAtual . ' selecionada estará associada a situação ' . $nomeSituacaoUltima . ', em razão da ordem de parametrização. Verifique se está correto!';
        }

        return $msg;
    }

    /**
     * @param $idProcedimento
     * @return MdLitSituacaoDTO retorna
     */
    protected function buscarSituacaoIntimacaoProximoDecisorioOpcional($idProcedimento, $idTpControle)
    {
        $arrObjSituacaoIntimacaoProximoDecisorioOpcional = array();
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $this->_addSelectDefaultProcessoSituacao($objMdLitProcessoSituacaoDTO);
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitProcessoSituacaoDTO->retStrSinDecisoriaSit();
        $objMdLitProcessoSituacaoDTO->retStrSinOpcionalSit();
        $objMdLitProcessoSituacaoDTO->setOrd('Inclusao', InfraDTO::$TIPO_ORDENACAO_DESC);

        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
        $anteriorDecisorioOpcional = false;

        $objMdLitProcessoSituacaoDTO = $this->consultar($objMdLitProcessoSituacaoDTO);

        if ($objMdLitProcessoSituacaoDTO != null && ($objMdLitProcessoSituacaoDTO->getStrSinDecisoriaSit() != 'S' || $objMdLitProcessoSituacaoDTO->getStrSinOpcionalSit() != 'S')) {
            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->retNumIdSituacaoLitigioso();
            $objMdLitSituacaoDTO->retStrSinDecisoria();
            $objMdLitSituacaoDTO->retStrSinOpcional();
            $objMdLitSituacaoDTO->retStrSinIntimacao();
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($idTpControle);
            $objMdLitSituacaoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitSituacaoDTO->adicionarCriterio(array('SinInstauracao', 'SinConclusiva', 'SinDecisoria', 'SinIntimacao', 'SinDefesa', 'SinRecursal'),
                array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
                array('S', 'S', 'S', 'S', 'S', 'S'),
                array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR));

            $objMdLitSituacaoRN = new MdLitSituacaoRN();
            $arrObjMdLitSituacaoDTO = $objMdLitSituacaoRN->listar($objMdLitSituacaoDTO);

            foreach ($arrObjMdLitSituacaoDTO as $objMdLitSituacaoDTO) {
                if ($anteriorDecisorioOpcional) {
                    $arrObjSituacaoIntimacaoProximoDecisorioOpcional[] = $objMdLitSituacaoDTO;
                    $anteriorDecisorioOpcional = false;
                }
                if ($objMdLitSituacaoDTO->getStrSinDecisoria() == 'S' && $objMdLitSituacaoDTO->getStrSinOpcional() == 'S') {
                    $anteriorDecisorioOpcional = true;
                }

            }
        }

        return $arrObjSituacaoIntimacaoProximoDecisorioOpcional;

    }

    protected function processarCadastrarControlado($post)
    {
        $arrSituacao = PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbSituacoes']);

        $dadosSituacaoRecurso = [
            'idLancamento'             => $post['hdnCreditosProcesso'] ? $post['hdnCreditosProcesso'] : null,
            'hdnDtApresentacaoRecurso' => $post['hdnDtApresentacaoRecurso'] ? $post['hdnDtApresentacaoRecurso'] : null,
            'hdnDtIntimacaoAplMulta'   => $post['hdnDtIntimacaoAplMulta'] ? $post['hdnDtIntimacaoAplMulta'] : null
        ];

        $arrIdSituacao = $this->_prepararCadastroAlteracaoSituacao($arrSituacao, $dadosSituacaoRecurso, $post);

        //web-service com o sistema de faturamento
        $objMdLitLancamentoRN = new MdLitLancamentoRN();

        $idUltimaSituacao = intval(end($arrSituacao)[0]);
        $post['id_situacao'] =  !empty($arrIdSituacao) ? end($arrIdSituacao) : $idUltimaSituacao;
        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->prepararLancamento($post);

        //se nao for esclusao da situacao deve alterar/cadastrar
        if ($this->isEsclusaoSituacao) {
            return;
        }
        // o cadastrar situação colocar o $id_md_lit_processo_situacao cadastrado/alterado
        if($arrIdSituacao) {
            $arrDecisao['id_md_lit_processo_situacao'] = count($arrIdSituacao) > 0 ? end($arrIdSituacao) : null;
        } else {
            $arrDecisao['id_md_lit_processo_situacao'] = null;
        }
        $arrDecisao['id_procedimento'] = $post['hdnIdProcedimento'];
        $arrDecisao['valor_lancamento'] = $objMdLitLancamentoDTO ? $objMdLitLancamentoRN->valorLancadoPorProcedimento($objMdLitLancamentoDTO->getDblIdProcedimento()) : null;
        $arrDecisao['lista'] = PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbDecisao']);
        $objMdLitDecisaoRN = new MdLitDecisaoRN();

        $arrIdDecisao = $objMdLitDecisaoRN->cadastrar($arrDecisao);

        if ($arrIdDecisao) {
            $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
            $objMdLitDecisaoDTO->retTodos(false);
            $objMdLitDecisaoDTO->retDblIdProcedimentoMdLitProcessoSituacao();
            $objMdLitDecisaoDTO->setNumIdMdLitDecisao($arrIdDecisao, InfraDTO::$OPER_IN);

            $objMdLitDecisaoRN = new MdLitDecisaoRN();
            $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listar($objMdLitDecisaoDTO);


            $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();
            $objMdLitRelDecisLancamentRN->vincularDecisaoComLancamento($arrObjMdLitDecisaoDTO);
        }

    }

    private function _controlarExclusaoSituacoes($idProcedimento, $arrSituacao, $post)
    {
        $idsExcluidos = array();
        $objMdLitProceSitDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProceSitDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitProceSitDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProceSitDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $count = $this->contar($objMdLitProceSitDTO);
        if ($count > 0) {
            $arrObjMdLitSituacaoDTO = $this->listar($objMdLitProceSitDTO);

            //Ids salvos no banco até o momento
            $idsSalvosBd = InfraArray::converterArrInfraDTO($arrObjMdLitSituacaoDTO, 'IdMdLitProcessoSituacao');

            //Ids deixados na tabela pelo Usuário
            $idsTabelaAtual = InfraArray::simplificarArr($arrSituacao, '0');

            // Compara os arrays para verificar os ids que foram excluidos para realizar a exclusão
            $idsExcluidos = array_diff($idsSalvosBd, $idsTabelaAtual);



            if (count($idsExcluidos) > 0) {
              $this->_limparDadosLancamentoConclusao($idsExcluidos, $post);
              $this->_preparaRetificarLancamentoTransitoJulgado($idsExcluidos);
              $this->_prepararExclusao($idsExcluidos);
              $this->_limparDataRecrusoNoLancamento($idsExcluidos);
            }
        }

        return $idsExcluidos;
    }

    private function _limparDataRecrusoNoLancamento($idsExcluidos)
    {
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdSituacaoRecurso($idsExcluidos, InfraDTO::$OPER_IN);
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitLancamentoDTO->retTodos();
        $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);
        if ($objMdLitLancamento) {
            $objMdLitLancamento->setNumIdSituacaoRecurso(null);
            $objMdLitLancamento->setDtaApresentacaoRecurso(null);
            $objMdLitLancamentoRN->alterar($objMdLitLancamento);
        }
    }

    private function _limparDadosLancamentoConclusao($idsExcluidos, $post)
    {
        foreach ($idsExcluidos as $idExcluido) {
            $objMdLitProceSitDTO = new MdLitProcessoSituacaoDTO();
            $objMdLitProceSitDTO->setNumIdMdLitProcessoSituacao($idExcluido);
            $objMdLitProceSitDTO->setStrSinConclusivaSit("S");
            $objMdLitProceSitDTO->retDblIdProcedimento();
            $objMdLitProceSitDTO->retStrSinConclusivaSit();
            $objMdLitProceSit = $this->consultar($objMdLitProceSitDTO);

            if($objMdLitProceSit) {
                $objMdLitLancamentoRN = new MdLitLancamentoRN();
                $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
                $objMdLitLancamentoDTO->setDblIdProcedimento($objMdLitProceSit->getDblIdProcedimento());
                $objMdLitLancamentoDTO->setNumIdMdLitLancamento(array($post['hdnCreditosProcesso']) , InfraDTO::$OPER_NOT_IN);
                $objMdLitLancamentoDTO->retTodos();

                $arrObjMdLitLancamento = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);
                foreach ($arrObjMdLitLancamento as $objMdLitLancamento) {
                    $postNovo['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO;
                    $postNovo['hdnVlCreditoNaoLancado'] = $objMdLitLancamento->getDblVlrLancamento();
                    $postNovo['selCreditosProcesso'] = $objMdLitLancamento->getNumIdMdLitLancamento();
                    $postNovo['txtDtVencimento'] = $objMdLitLancamento->getDtaVencimento();
                    $postNovo['hdnDecisaoAplicacaoMulta'] = $objMdLitLancamento->getDtaDecisao();
                    $postNovo['hdnVlTotalMulta'] = $objMdLitLancamento->getDblVlrLancamento();
                    $postNovo['hdnJustificativaLancamento'] = "Multa retificada por cancelamento da situação de TRÂNSITO EM JULGADO";
                    $postNovo['hdnIdProcedimento'] = $objMdLitLancamento->getDblIdProcedimento();
                    $postNovo['hdnDtIntimacaoAplMulta'] =  $objMdLitLancamento->getDtaIntimacao();
                    $postNovo['hdnDtDecursoPrazoRecurso'] = $objMdLitLancamento->getDtaDecursoPrazoRecurso();
                    $postNovo['hdnDtApresentacaoRecurso'] = $objMdLitLancamento->getDtaApresentacaoRecurso();
                    $postNovo['hdnDtDecisaoDefinitiva'] = null;
                    $postNovo['chkHouveConstituicao'] = null;
                    $postNovo['txtDtConstituicao'] = null;
                    $postNovo['txtDtIntimacaoConstituicao'] = null;
                    $postNovo['chkReducaoRenuncia'] = null;

                    $objMdLitLancamento->setStrSinConstituicaoDefinitiva('N');
                    $objMdLitLancamento->setDtaConstituicaoDefinitiva(null);
                    $objMdLitLancamento->setDtaDecisaoDefinitiva(null);
                    $objMdLitLancamento->setDtaConstituicaoDefinitiva(null);
                    $objMdLitLancamento->setDtaIntimacaoDefinitiva(null);
                    $objMdLitLancamento->setStrSinRenunciaRecorrer('N');
                    $objMdLitLancamentoRN->alterar($objMdLitLancamento);

                    $objMdLitLancamentoRN->realizarRetificarCredito($postNovo);
                }
            }
            return true;
        }
    }


    private function _preparaRetificarLancamentoTransitoJulgado($idsExcluidos)
    {
        $post = $_POST;
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();

        if (count($idsExcluidos) > 0) {

            foreach ($idsExcluidos as $idExcluido) {
                $objMdLitProceSitDTO = new MdLitProcessoSituacaoDTO();
                $objMdLitProceSitDTO->setNumIdMdLitProcessoSituacao($idExcluido);
                $objMdLitProceSitDTO->retNumIdMdLitSituacao();
                $arrProcessoSit = $this->consultar($objMdLitProceSitDTO);
                $idSituacao = $arrProcessoSit->getNumIdMdLitSituacao();

                if ($idSituacao == self::$SITUACAO_TRANSITO_EM_JULGADO && ($post['selCreditosProcesso'] || $post['hdnCreditosProcesso'])) {
                    $post['hdnJustificativaLancamento'] = 'Multa retificada por cancelamento da situação de TRÂNSITO EM JULGADO';
                    $post['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO;
                    $post['txtDtIntimacaoConstituicao'] = null;
                    $post['sbmCadastrarSituacao'] = 'Salvar';
                    unset($post['chkHouveConstituicao']);
                    unset($post['chkReducaoRenuncia']);
                    $objMdLitLancamentoRN->prepararLancamento($post);

                    $objMdLitLancamentoDTO->setNumIdMdLitLancamento($post['selCreditosProcesso'] ?: $post['hdnCreditosProcesso']);
                    $objMdLitLancamentoDTO->retTodos();
                    $objMdLitLancamentoAlterarDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

                    $objMdLitLancamentoAlterarDTO->setDtaIntimacaoDefinitiva(null);
                    $objMdLitLancamentoAlterarDTO->setDtaConstituicaoDefinitiva(null);
                    $objMdLitLancamentoRN->alterar($objMdLitLancamentoAlterarDTO);
                }
            }
        }
    }

    private function _prepararExclusao($idsExcluidos)
    {
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->setNumIdMdLitProcessoSituacao($idsExcluidos, InfraDTO::$OPER_IN);
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->ret('IdProcedimento');

        if ($this->contar($objMdLitProcessoSituacaoDTO) > 0) {
            $this->excluir($this->listar($objMdLitProcessoSituacaoDTO));
        }
    }

//_prepararInsercaoSituacao
    private function _prepararCadastroAlteracaoSituacao($arrSituacao, $dadosSituacaoRecurso, $post)
    {
        $idProcedimento = $_POST['hdnIdProcedimento'];
        $arrIdsExcluidos = $this->_controlarExclusaoSituacoes($idProcedimento, $arrSituacao, $post);

        $arrIdSituacao = array();
        if (count($arrSituacao) > 0) {

            foreach ($arrSituacao as $arrDados) {
                $isInsercao = $arrDados[1] == 'N';
                $isAlteracao = $arrDados[1] == 'A';

                if ($isInsercao) {
                    $objMdLitProcessoSitDTO = $this->_realizaInsercaoSituacao($arrDados);
                    $this->atualizarDataLancamento($objMdLitProcessoSitDTO->getNumIdMdLitProcessoSituacao(), $dadosSituacaoRecurso);
                    $arrIdSituacaoCadastro[] = $objMdLitProcessoSitDTO->getNumIdMdLitProcessoSituacao();
                } elseif ($isAlteracao) {
                    $this->_realizaAlteracaoSituacao($arrDados);
                    $this->atualizarDataLancamento($arrDados[0], $dadosSituacaoRecurso);
                } elseif ($arrIdsExcluidos) {
                    $this->isEsclusaoSituacao = true;
                }
            }
        }
        return $arrIdSituacaoCadastro;
    }

    private function _realizaAlteracaoSituacao($arrDados)
    {

        $objMdLitSituacaoRN = new MdLitSituacaoRN();
        $dtaIntercorrente = null;
        $dtaQuinquenal = null;
        $depExtraJud = null;

        if ($arrDados[28] == 'S') {
            $depExtraJud = $arrDados[28];
            $vlFormt = str_replace('.', '', $arrDados[18]);
            $vlFormt = str_replace(',', '.', $vlFormt);
        } elseif ($arrDados[28] == 'N') {
            $depExtraJud = $arrDados[28];
        }

        $sinPrescricao = $arrDados[22] == '1' ? 'S' : 'N';
        $objMdLitProcessoSitDTOIntimacao = $this->consultarPrimeiraIntimacao($arrDados[2]);
        $objMdLitSituacaoDTO = $objMdLitSituacaoRN->getObjSituacaoPorId($arrDados[4]);

        if ($objMdLitProcessoSitDTOIntimacao != null && $objMdLitProcessoSitDTOIntimacao->getNumIdMdLitProcessoSituacao() == $arrDados[0] && $objMdLitSituacaoDTO->getStrSinIntimacao() == 'S') {
            $dtaIntercorrente = InfraData::calcularData(3, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $arrDados[11]);//Data da Intimação + 3 anos
            $dtaQuinquenal = InfraData::calcularData(5, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $arrDados[11]);//Data da Intimação + 5 anos
        } elseif ($sinPrescricao == 'S') {
            $dtaIntercorrente = $arrDados[7];
            $dtaQuinquenal = $arrDados[8];
        }

        $objMdLitProcessoSitDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSitDTO->setNumIdMdLitProcessoSituacao($arrDados[0]);
        $objMdLitProcessoSitDTO->setNumIdMdLitSituacao($arrDados[4]);
        $objMdLitProcessoSitDTO->setNumPrazoSituacao($arrDados[29]);
        $objMdLitProcessoSitDTO->setStrTipoPrazo($this->_recuperarTipoPrazo($arrDados[4]));
        $objMdLitProcessoSitDTO->setDblIdProcedimento($arrDados[2]);
        $objMdLitProcessoSitDTO->setDblIdDocumento($arrDados[24]);
        $objMdLitProcessoSitDTO->setNumIdMdLitTipoControle($_POST['hdnIdTipoControle']);
        $objMdLitProcessoSitDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
        $objMdLitProcessoSitDTO->setNumIdUnidade($_POST['hdnIdUnidade']);
        $objMdLitProcessoSitDTO->setStrSinDepositoExtrajudicial($depExtraJud);
        $objMdLitProcessoSitDTO->setDblValorDepositoExtrajudicial($vlFormt);
        $objMdLitProcessoSitDTO->setDtaDtDepositoExtrajudicial($arrDados[19]);
        $objMdLitProcessoSitDTO->setDtaData($arrDados[11]);
        $objMdLitProcessoSitDTO->setDtaIntercorrente($dtaIntercorrente);
        $objMdLitProcessoSitDTO->setDtaQuinquenal($dtaQuinquenal);
        $objMdLitProcessoSitDTO->setStrSinAtivo('S');
        $objMdLitProcessoSitDTO->setStrSinAlteraPrescricao($sinPrescricao);

        //Alterar data da decisao do lancamento caso exista
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdMdLitSituacaoDecisaoDefin($arrDados[0]);
        $objMdLitLancamentoDTO->retTodos();

        $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);
        if($objMdLitLancamento){
            $objMdLitLancamento->setDtaDecisaoDefinitiva($arrDados[11]);
            $objMdLitLancamentoRN->alterar($objMdLitLancamento);
        }

        $this->alterar($objMdLitProcessoSitDTO);

        if ($objMdLitSituacaoDTO->getStrSinInstauracao() == 'S') {
            $objMdLitControleRN = new MdLitControleRN();
            $objMdLitControleRN->alterarPorProcessoSituacao($objMdLitProcessoSitDTO);
        }
    }

    private function _realizaInsercaoSituacao($arrDados)
    {

        $depExtraJud = null;
        $vlFormt = '';
        $dtaIntercorrente = null;
        $dtaQuinquenal = null;
        $objMdLitSituacaoRN = new MdLitSituacaoRN();

        if ($arrDados[28] == 'S') {
            $depExtraJud = $arrDados[28];
            $vlFormt = str_replace('.', '', $arrDados[18]);
            $vlFormt = str_replace(',', '.', $vlFormt);
        } elseif ($arrDados[28] == 'N') {
            $depExtraJud = $arrDados[28];
        }

        $objMdLitProcessoSitDTOIntimacao = $this->consultarPrimeiraIntimacao($arrDados[2]);
        $objMdLitSituacaoDTO = $objMdLitSituacaoRN->getObjSituacaoPorId($arrDados[4]);
        $sinPrescricao = $arrDados[22] == '1' ? 'S' : 'N';

        if (($objMdLitProcessoSitDTOIntimacao == null && $objMdLitSituacaoDTO->getStrSinIntimacao() == 'S')) {//&& $arrDados[25] == '1'){
            $dtaIntercorrente = InfraData::calcularData(3, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $arrDados[11]);//Data da Intimação + 3 anos
            $dtaQuinquenal = InfraData::calcularData(5, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $arrDados[11]);//Data da Intimação + 5 anos
        } elseif ($sinPrescricao == 'S') {
            $objMdLitProcessoSitDTOUltima = new MdLitProcessoSituacaoDTO();
            $objMdLitProcessoSitDTOUltima->retDtaIntercorrente();
            $objMdLitProcessoSitDTOUltima->setDblIdProcedimento($arrDados[2]);
            $objMdLitProcessoSitDTOUltima->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
            $objMdLitProcessoSitDTOUltima->setDtaIntercorrente(null, InfraDTO::$OPER_DIFERENTE);
            $objMdLitProcessoSitDTOUltima->setNumMaxRegistrosRetorno(1);

            $objMdLitProcessoSitDTOUltima = $this->consultar($objMdLitProcessoSitDTOUltima);

            //salva a prescrição somente quanto a data for diferente da ultima data cadastrada
            if ($objMdLitProcessoSitDTOUltima && $objMdLitProcessoSitDTOUltima->getDtaIntercorrente() != $arrDados[7]) {
                $dtaIntercorrente = $arrDados[7];
            }

            $objMdLitProcessoSitDTOUltima = new MdLitProcessoSituacaoDTO();
            $objMdLitProcessoSitDTOUltima->retDtaQuinquenal();
            $objMdLitProcessoSitDTOUltima->setDblIdProcedimento($arrDados[2]);
            $objMdLitProcessoSitDTOUltima->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
            $objMdLitProcessoSitDTOUltima->setDtaQuinquenal(null, InfraDTO::$OPER_DIFERENTE);
            $objMdLitProcessoSitDTOUltima->setNumMaxRegistrosRetorno(1);

            $objMdLitProcessoSitDTOUltima = $this->consultar($objMdLitProcessoSitDTOUltima);

            //salva a prescrição somente quanto a data for diferente da ultima data cadastrada
            if ($objMdLitProcessoSitDTOUltima && $objMdLitProcessoSitDTOUltima->getDtaQuinquenal() != $arrDados[8]) {
                $dtaQuinquenal = $arrDados[8];
            }
        }

        $objMdLitProcessoSitDTO = new MdLitProcessoSituacaoDTO();

        $objMdLitProcessoSitDTO->setNumIdMdLitSituacao($arrDados[4]);
        $objMdLitProcessoSitDTO->setNumPrazoSituacao($arrDados[29]);
        $objMdLitProcessoSitDTO->setStrTipoPrazo($this->_recuperarTipoPrazo($arrDados[4]));
        $objMdLitProcessoSitDTO->setDblIdProcedimento($arrDados[2]);
        $objMdLitProcessoSitDTO->setDblIdDocumento($arrDados[24]);
        $objMdLitProcessoSitDTO->setNumIdMdLitTipoControle($_POST['hdnIdTipoControle']);
        $objMdLitProcessoSitDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
        $objMdLitProcessoSitDTO->setNumIdUnidade($_POST['hdnIdUnidade']);
        $objMdLitProcessoSitDTO->setStrSinDepositoExtrajudicial($depExtraJud);
        $objMdLitProcessoSitDTO->setDblValorDepositoExtrajudicial($vlFormt);
        $objMdLitProcessoSitDTO->setDtaDtDepositoExtrajudicial($arrDados[19]);
        $objMdLitProcessoSitDTO->setDtaData($arrDados[11]);
        $objMdLitProcessoSitDTO->setDtaIntercorrente($dtaIntercorrente);
        $objMdLitProcessoSitDTO->setDtaQuinquenal($dtaQuinquenal);
        $objMdLitProcessoSitDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
        $objMdLitProcessoSitDTO->setStrSinAtivo('S');
        $objMdLitProcessoSitDTO->setStrSinAlteraPrescricao($sinPrescricao);

        $this->cadastrar($objMdLitProcessoSitDTO);

        return $objMdLitProcessoSitDTO;
    }

    protected function atualizarDataLancamento($IdMdLitProcessoSituacao, $dadosSituacaoRecurso)
    {
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($dadosSituacaoRecurso['idLancamento']);
        $objMdLitLancamentoDTO->retTodos();
        $objMdLitLancamento = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        if(
            $dadosSituacaoRecurso['hdnDtApresentacaoRecurso'] &&
            $objMdLitLancamento &&
            (
                (empty($objMdLitLancamento->getNumIdSituacaoRecurso()) && empty($objMdLitLancamento->getDtaApresentacaoRecurso()) ||
                $IdMdLitProcessoSituacao == $objMdLitLancamento->getNumIdSituacaoRecurso()
                )
            )
        ){
            $objMdLitLancamento->setNumIdSituacaoRecurso($IdMdLitProcessoSituacao);
            $objMdLitLancamento->setDtaApresentacaoRecurso($dadosSituacaoRecurso['hdnDtApresentacaoRecurso']);
            $objMdLitLancamentoRN->alterar($objMdLitLancamento);
        }
    }

    //Se não existe instauração ainda, as datas intercorrente e quinquenal são settadas como null
    private function _retornaDtIntQuinq($dt)
    {
        $sinInstauracao = $_POST['hdnSinInstauracao'];

        if ($sinInstauracao == '1') {
            return $dt;
        } else {
            return null;
        }
    }


    protected function getDadosUnidadeUsuarioLogadoConectado()
    {

        $objUnidadeDTO = $this->_getObjUnidadePorId(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $dados = array();
        $dados['idUsuario'] = SessaoSEI::getInstance()->getNumIdUsuario();
        $dados['nomeUsuario'] = SessaoSEI::getInstance()->getStrNomeUsuario();
        $dados['siglaUsuario'] = SessaoSEI::getInstance()->getStrSiglaUsuario();
        $dados['idUnidade'] = SessaoSEI::getInstance()->getNumIdUnidadeAtual();

        if (!is_null($objUnidadeDTO)) {
            $dados['nomeUnidade'] = $objUnidadeDTO->getStrDescricao();
            $dados['siglaUnidade'] = $objUnidadeDTO->getStrSigla();
        } else {
            $dados['nomeUnidade'] = '';
            $dados['siglaUnidade'] = '';
        }

        return $dados;
    }

    private function _getObjUnidadePorId($idUnidade)
    {
        $objUnidadeRN = new UnidadeRN();

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setNumIdUnidade($idUnidade);
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrDescricao();
        return $objUnidadeRN->consultarRN0125($objUnidadeDTO);
    }

    private function _retornaDadosSituacaoAnteriorCadastrada($idProcedimento)
    {
        $objMdLitSitProcessoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitSitProcessoDTO->retNumIdMdLitSituacao();
        $objMdLitSitProcessoDTO->retNumOrdemParametrizarSit();
        $objMdLitSitProcessoDTO->retStrNomeSituacao();
        $objMdLitSitProcessoDTO->retStrNomeFase();
        $objMdLitSitProcessoDTO->retDblIdProcedimento();
        $objMdLitSitProcessoDTO->retDtaData();
        $objMdLitSitProcessoDTO->retStrSinIntimacaoSit();
        $objMdLitSitProcessoDTO->retStrSinObrigatoria();
        $objMdLitSitProcessoDTO->retStrSinAlegacoes();
        $objMdLitSitProcessoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitSitProcessoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitSitProcessoDTO->setNumMaxRegistrosRetorno('1');

        $this->_addDiferenteSinLivre($objMdLitSitProcessoDTO);

        return $this->consultar($objMdLitSitProcessoDTO);
    }

    private function _retornaDadosPrimeiraSituacaoCadastrada($idProcedimento)
    {
        $objMdLitSitProcessoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitSitProcessoDTO->retNumIdMdLitSituacao();
        $objMdLitSitProcessoDTO->retNumOrdemParametrizarSit();
        $objMdLitSitProcessoDTO->retStrNomeSituacao();
        $objMdLitSitProcessoDTO->retDblIdProcedimento();
        $objMdLitSitProcessoDTO->retDtaData();
        $objMdLitSitProcessoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitSitProcessoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitSitProcessoDTO->setNumMaxRegistrosRetorno('1');

        return $this->consultar($objMdLitSitProcessoDTO);
    }

    protected function getDtUltimaSituacaoConectado($idProcedimento)
    {

        $objMdLitSitProcessoDTO = $this->_retornaDadosSituacaoAnteriorCadastrada($idProcedimento);
        $dt = !is_null($objMdLitSitProcessoDTO) ? $objMdLitSitProcessoDTO->getDtaData() : '';
        return $dt;
    }

    protected function getDtMenorInterQuinquenalConectado($arrParams)
    {
        $idProcedimento = $arrParams[0];
        $isIntercorrente = $arrParams[1];
        $objMdLitSitProcessoDTO = $this->consultarPrimeiraIntimacao($idProcedimento);
        $dtInstaur = '';
        if (!is_null($objMdLitSitProcessoDTO)) {
            $dtInstaur = $isIntercorrente ? $objMdLitSitProcessoDTO->getDtaIntercorrente() : $objMdLitSitProcessoDTO->getDtaQuinquenal();
        }


        $dtMenorHistorico = $this->getDtIntercorrenteQuinquenal(array($idProcedimento, $isIntercorrente, InfraDTO::$TIPO_ORDENACAO_ASC));


        $bolDt = InfraData::compararDatasSimples($dtInstaur, $dtMenorHistorico);

        if ($bolDt == '-1' || $bolDt == '0') {
            $dtRet = $dtMenorHistorico;
        } else {
            $dtRet = $dtInstaur;
        }

        return $dtRet;
    }


    protected function getDtIntercorrenteQuinquenalConectado($arrParams)
    {
        $idProcedimento = $arrParams[0];
        $isIntercorrente = $arrParams[1];
        $ordemDatas = array_key_exists('2', $arrParams) ? $arrParams[2] : false;

        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);

        if (!$ordemDatas) {
            $objMdLitProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
        }

        if ($isIntercorrente) {
            $objMdLitProcessoSituacaoDTO->setDtaIntercorrente(null, InfraDTO::$OPER_DIFERENTE);
            $objMdLitProcessoSituacaoDTO->retDtaIntercorrente();

            if ($ordemDatas) {
                $objMdLitProcessoSituacaoDTO->setOrdDtaIntercorrente($ordemDatas);
            }

        } else {
            $objMdLitProcessoSituacaoDTO->setDtaQuinquenal(null, InfraDTO::$OPER_DIFERENTE);
            $objMdLitProcessoSituacaoDTO->retDtaQuinquenal();

            if ($ordemDatas) {
                $objMdLitProcessoSituacaoDTO->setOrdDtaQuinquenal($ordemDatas);
            }
        }

        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno('1');

        $objRet = $this->consultar($objMdLitProcessoSituacaoDTO);

        if (!is_null($objRet)) {
            if ($isIntercorrente) {
                return $objRet->getDtaIntercorrente();
            } else {
                return $objRet->getDtaQuinquenal();
            }
        }

        return '';
    }

    protected function getListaHistoricoIntQuinqConectado(InfraDTO $objDTO)
    {

        $this->_addSelectDefaultProcessoSituacao($objDTO);

        $arrObjMdLitProcSitDTO = $this->listar($objDTO);

        return $arrObjMdLitProcSitDTO;
    }

    protected function getIsInstauracaoIntimacaoPorTipoControleConectado($arrParams)
    {

        $idTipoControle = $arrParams[0];
        $idProcedimento = $arrParams[1];
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($idTipoControle);
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->setStrSinIntimacaoSit('S');
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);

        return $this->contar($objMdLitProcessoSituacaoDTO) > 0;
    }

    public function verificaIsSituacaoIntimacao($idProcedimento, $idTipoControle)
    {

        $isSituacaoIntimacao = false;
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($idTipoControle);
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->retStrSinIntimacaoSit();
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);

        $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitProcessoSituacaoDTO = $this->consultar($objMdLitProcessoSituacaoDTO);

        if ($objMdLitProcessoSituacaoDTO) {
            $isSituacaoIntimacao = $objMdLitProcessoSituacaoDTO->getStrSinIntimacaoSit() == 'S' ? true : false;
        }

        return $isSituacaoIntimacao;
    }

    public function verificaSeHouveDecisaoMulta($idProcedimento, $idTipoControle, $idLancamentoSelecionado)
    {

        $isHouveDecisaoMulta = false;
        $isSituacaoIntimacao = false;
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($idTipoControle);
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->retStrSinIntimacaoSit();
        $objMdLitProcessoSituacaoDTO->retStrSinDecisoriaSit();
        $objMdLitProcessoSituacaoDTO->retStrNomeFase();
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);

        $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_ASC);
        $arrObjMdLitProcessoSituacaoDTO = $this->listar($objMdLitProcessoSituacaoDTO);

        if ($arrObjMdLitProcessoSituacaoDTO) {
            $proximaPosicao = 0;
            foreach ($arrObjMdLitProcessoSituacaoDTO as $posicao => $objMdLitProcessoSituacaoDTO) {
                if($objMdLitProcessoSituacaoDTO->getStrSinDecisoriaSit() == 'S'){
                    $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
                    $objMdLitDecisaoDTO->setNumIdMdLitProcessoSituacao($objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao());
                    $objMdLitDecisaoDTO->retDblMulta();
                    $objMdLitDecisaoDTO->retNumIdMdLitDecisao();
                    $arrObjMdLitDecisaoDTO = (new MdLitDecisaoRN())->listar($objMdLitDecisaoDTO);
                    if($arrObjMdLitDecisaoDTO){
                        foreach($arrObjMdLitDecisaoDTO as $objMdLitDecisaoDTO) {
                            $objMdLitRelDecisaoLancamento = new MdLitRelDecisLancamentDTO();
                            $objMdLitRelDecisaoLancamento->setNumIdMdLitDecisao($objMdLitDecisaoDTO->getNumIdMdLitDecisao());
                            $objMdLitRelDecisaoLancamento->retNumIdMdLitLancamento();
                            $arroObjMdLitRelDecisaoLancamento = (new MdLitRelDecisLancamentRN())->listar($objMdLitRelDecisaoLancamento);
                            foreach ($arroObjMdLitRelDecisaoLancamento as $objMdLitRelDecisaoLancamento){
                                $objMdLitCancelaLancamentoDTO = new MdLitCancelaLancamentoDTO();
                                $objMdLitCancelaLancamentoDTO->retTodos();
                                $objMdLitCancelaLancamentoDTO->setNumIdMdLitLancamento($objMdLitRelDecisaoLancamento->getNumIdMdLitLancamento());
                                $objMdLitCancelaLancamentoDTO = (new MdLitCancelaLancamentoRN())->consultar($objMdLitCancelaLancamentoDTO);
                                if(is_null($objMdLitCancelaLancamentoDTO)){
                                    if (!is_null($objMdLitDecisaoDTO->getDblMulta()) && $objMdLitDecisaoDTO->getDblMulta() != '' && $idLancamentoSelecionado == $objMdLitRelDecisaoLancamento->getNumIdMdLitLancamento()) {
                                        if(!$isHouveDecisaoMulta)
                                            $proximaPosicao = ++$posicao;
                                        $isHouveDecisaoMulta = true;
                                    }
                                }
                            }
                        }
                    }
                }
                if($objMdLitProcessoSituacaoDTO->getStrSinIntimacaoSit() == 'S' && $posicao == $proximaPosicao) {
                    $isSituacaoIntimacao = true;
                }
            }
        }
        return $isHouveDecisaoMulta == true && $isSituacaoIntimacao == true;
    }


    private function _addDiferenteSinLivre(&$objMdLitSitProcessoDTO)
    {
        $campos = array('SinInstauracaoSit', 'SinIntimacaoSit', 'SinDecisoriaSit', 'SinDefesaSit', 'SinRecursalSit', 'SinConclusivaSit', 'SinObrigatoria', 'SinAlegacoes');
        $oper = array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL);
        $valor = array('S', 'S', 'S', 'S', 'S', 'S', 'S', 'S');
        $operLog = array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR);


        $objMdLitSitProcessoDTO->adicionarCriterio($campos, $oper, $valor, $operLog);
    }


    protected function verificarVinculoSituacaoConectado($idMdLitSituacao)
    {
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->setNumIdMdLitSituacao($idMdLitSituacao);
        $objMdLitProcessoSituacaoDTO->setStrSinAtivo('S');
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        return $this->contar($objMdLitProcessoSituacaoDTO) > 0;
    }

    protected function buscarDtSituacaoConclusivaConectado($idProcedimento)
    {

        $dtRetorno = '';
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retTodos(true);
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitProcessoSituacaoDTO->setStrSinConclusivaSit('S');
        $objMdLitProcessoSituacaoDTO->retDtaData();

        $objMdLitProcessoSituacaoDTO = $this->consultar($objMdLitProcessoSituacaoDTO);

        if (!is_null($objMdLitProcessoSituacaoDTO)) {
            $dtRetorno = $objMdLitProcessoSituacaoDTO->getDtaData();
        }

        return $dtRetorno;
    }

    protected function buscarUltimaSituacaoDecisoriaConectado($idProcedimento)
    {

        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retTodos(true);
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objMdLitProcessoSituacaoDTO = $this->consultar($objMdLitProcessoSituacaoDTO);

        if ($objMdLitProcessoSituacaoDTO && $objMdLitProcessoSituacaoDTO->getStrSinDecisoriaSit() == 'S')
            return $objMdLitProcessoSituacaoDTO;

        return null;
    }

    public function montarSelectDataDecursoPrazoDefesaConectado($dados)
    {

        $objMdLitProcessoSituacaoDTOPrimeiraIntimacao = $this->consultarPrimeiraIntimacao($dados['idProcedimento']);

        if ($objMdLitProcessoSituacaoDTOPrimeiraIntimacao) {
            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->retTodos(false);
            $objMdLitSituacaoDTO->setStrSinDefesa('S');
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitProcessoSituacaoDTOPrimeiraIntimacao->getNumIdMdLitTipoControle());
            $objMdLitSituacaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitSituacaoDTO = (new MdLitSituacaoRN())->consultar($objMdLitSituacaoDTO);

            $prazoSelected = $dados['objMdLitLancamentoDTO'] ? $dados['objMdLitLancamentoDTO']->getDtaPrazoDefesa() : '';
            $return['htmlOption'] = '';
            $return['hdnDtDecursoPrazo'] = $dados['objMdLitLancamentoDTO'] ? $dados['objMdLitLancamentoDTO']->getDtaPrazoDefesa() : null;

            if ($objMdLitSituacaoDTO && $objMdLitSituacaoDTO->getStrPrazo()) {
                $arrayPrazos = explode(',', $objMdLitSituacaoDTO->getStrPrazo());
                if(count($arrayPrazos) != 1) {
                    $return['htmlOption'] = '<option value=""></option>';
                }
                foreach ($arrayPrazos as $prazo) {

                    if ($objMdLitSituacaoDTO->getStrTipoPrazo() == MdLitSituacaoRN::$DIAS_CORRIDOS) {
                        $data = InfraData::calcularData($prazo, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $objMdLitProcessoSituacaoDTOPrimeiraIntimacao->getDtaData());
                        $option = $data . ' (prazo ' . $prazo . ' dias Corridos)';
                    }

                    if ($objMdLitSituacaoDTO->getStrTipoPrazo() == MdLitSituacaoRN::$DIAS_UTEIS) {
                        $data = $this->calcularPrazoDiasUteis($objMdLitProcessoSituacaoDTOPrimeiraIntimacao->getDtaData(), $prazo);
                        $option = $data . ' (prazo ' . $prazo . ' dias Úteis)';
                    }

                    $isSelected = ($prazoSelected == $data) ? 'selected' : '';
                    $return['htmlOption'] .= '<option value="' . $data . '" '.$isSelected.' prazo-defesa="'.$prazo.'" tp-prazo-defesa="'.$objMdLitSituacaoDTO->getStrTipoPrazo().'">' . $option . '</option>';
                }

                if(count($arrayPrazos) == 1) {
                    $return['hdnDtDecursoPrazo'] = $data;
                }

                if($dados['objMdLitLancamentoDTO'] && $dados['objMdLitLancamentoDTO']->getDtaPrazoDefesa()){
                    $return['hdnDtDecursoPrazo'] = $dados['objMdLitLancamentoDTO'] ? $dados['objMdLitLancamentoDTO']->getDtaPrazoDefesa() : $return['hdnDtDecursoPrazo'];
                }

                return $return;
            }

        }
        return null;
    }

    public function calcularPrazoDiasUteis($dtPrazoInicial, $diasPrazo)
    {
        $this->_removerTimeDate($dtPrazoInicial);

        $qtdDiasUteis = 0;

        $arrFeriados = MdLitProcessoSituacaoINT::recuperarFeriados($dtPrazoInicial);

        while ($qtdDiasUteis < $diasPrazo) {
            $dtPrazoInicial = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dtPrazoInicial);
            $diaSemana = InfraData::obterDescricaoDiaSemana($dtPrazoInicial);

            if ($diaSemana != 'sábado' && $diaSemana != 'domingo' && !in_array($dtPrazoInicial, $arrFeriados)) {
                $qtdDiasUteis++;
            }
        }

        return $dtPrazoInicial;
    }

    private function _removerTimeDate(&$strData){
        $countDate  = strlen($strData);
        $isDateTime = $countDate > 10 ? true : false;
        if($isDateTime){
            $arrData = explode(" ",$strData);
            $strData = $arrData[0];
        }
    }

    protected function verificarVinculoSituacaoDocumentoConectado($arrParams)
    {
        $objRN = new MdLitRelSituacaoSerieRN();
        $idSituacao = $arrParams[0];
        $idSerie = $arrParams[1];

        $objDTO = new MdLitRelSituacaoSerieDTO();
        $objDTO->setNumIdSituacaoLitigioso($idSituacao);
        $objDTO->setNumIdSerie($idSerie);

        return $objRN->contar($objDTO) > 0;
    }

    protected function verificarTipoControleConectado($arrParams)
    {
        $idTipoControle = null;
        $idProcedimento = array_key_exists('0', $arrParams) ? $arrParams[0] : null;
        $idTpControleRelac = array_key_exists('1', $arrParams) ? $arrParams[1] : null;

        $objMdLitProcessoSituacaoDTO = $this->_buscarPreCadastroProcSituacaoPorProcedimento($idProcedimento);

        if (!is_null($objMdLitProcessoSituacaoDTO)) {
            $objMdLitProcessoSituacaoDTO = $this->consultar($objMdLitProcessoSituacaoDTO);
            $idTpControleSalvoAntes = $objMdLitProcessoSituacaoDTO->getNumIdMdLitTipoControle();
            $idTipoControle = $idTpControleSalvoAntes;
        }

        if (is_null($idTipoControle)) {
            $idTipoControle = $idTpControleRelac;
        }

        return $idTipoControle;

    }

    private function _buscarPreCadastroProcSituacaoPorProcedimento($idProcedimento)
    {
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitTipoControle();
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno('1');
        $count = $this->contar($objMdLitProcessoSituacaoDTO);

        if ($count > 0) {
            return $objMdLitProcessoSituacaoDTO;
        } else {
            return null;
        }
    }

    protected function existePreCadastroConectado($idProcedimento)
    {
        $objMdLitProcessoSituacaoDTO = $this->_buscarPreCadastroProcSituacaoPorProcedimento($idProcedimento);

        if (!is_null($objMdLitProcessoSituacaoDTO)) {
            return true;
        }

        return false;
    }

    protected function retornaTipoControleConectado($objProcedimentoAPI)
    {
        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $objMdLitTpCtrlTpProcessoRN = new MdLitRelTipoControleTipoProcedimentoRN();
        //Verifica tipo de Processo
        $objMdLitTpCtrlTpProcessoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
        $objMdLitTpCtrlTpProcessoDTO->retNumIdTipoControleLitigioso();
        $objMdLitTpCtrlTpProcessoDTO->setNumIdTipoProcedimento($objProcedimentoAPI->getIdTipoProcedimento());


        $objMdLitTpCtrlTpProcessoDTO = $objMdLitTpCtrlTpProcessoRN->consultar($objMdLitTpCtrlTpProcessoDTO);
        $isTipoProcesso = $objMdLitTpCtrlTpProcessoDTO === null ? false : true;

        $possuirPreCadastro = $objMdLitProcessoSituacaoRN->existePreCadastro($objProcedimentoAPI->getIdProcedimento());
        //Busca todos os tipos de controle que possui esse tipo de processo vinculado


        if ($isTipoProcesso || $possuirPreCadastro) {
            $idTpControleRel = $isTipoProcesso ? $objMdLitTpCtrlTpProcessoDTO->getNumIdTipoControleLitigioso() : null;

            $idTpControle = $objMdLitProcessoSituacaoRN->verificarTipoControle(array($objProcedimentoAPI->getIdProcedimento(), $idTpControleRel));

            if ($idTpControle) {
                $objMdLitRelTipoControleUnidadeDTO = new MdLitRelTipoControleUnidadeDTO();
                $objMdLitRelTipoControleUnidadeDTO->retNumIdTipoControleLitigioso();
                $objMdLitRelTipoControleUnidadeDTO->setNumIdTipoControleLitigioso($idTpControle);
                $objMdLitRelTipoControleUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

                $objMdLitRelTipoControleUnidadeRN = new MdLitRelTipoControleUnidadeRN();
                $countObjMdLitRelTipoControleUnidade = $objMdLitRelTipoControleUnidadeRN->contar($objMdLitRelTipoControleUnidadeDTO);

                return $countObjMdLitRelTipoControleUnidade > 0 ? $idTpControle : null;
            }

        }

        return null;
    }

    public function addBotaoProcessoSituacao($idDocumento, $idTpControle, $idProcedimento)
    {

        $link = 'controlador.php?acao=md_lit_processo_situacao_cadastrar&arvore=1&id_tipo_controle=' . $idTpControle . '&id_procedimento=' . $idProcedimento;

        if ($idDocumento) {
            $link .= '&id_doc=' . $idDocumento;
        }

        $strLink = SessaoSEI::getInstance()->assinarLink($link);

        $imgIcone = "modulos/litigioso/imagens/svg/cadastro_situacao.svg?".Icone::VERSAO;
        $title = $idDocumento ? "Controle Litigioso - Cadastro de Situações" : 'Controle Litigioso - Consulta de Situações';

        $strBotao = '<a href="' . $strLink . '"class="botaoSEI">';
        $strBotao .= '<img class="infraCorBarraSistema" src="' . $imgIcone . '" alt="' . $title . '" title="' . $title . '">';
        $strBotao .= '</a>';

        return $strBotao;
    }

    public function consultarPrimeiraIntimacaoConectado($idProcedimento)
    {
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitSituacao();
        $objMdLitProcessoSituacaoDTO->retStrSinIntimacaoSit();
        $objMdLitProcessoSituacaoDTO->retDtaData();
        $objMdLitProcessoSituacaoDTO->retDtaIntercorrente();
        $objMdLitProcessoSituacaoDTO->retDtaQuinquenal();
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitTipoControle();
        $objMdLitProcessoSituacaoDTO->retNumPrazoSituacao();
        $objMdLitProcessoSituacaoDTO->retStrTipoPrazo();


        $objMdLitProcessoSituacaoDTO->setStrSinIntimacaoSit('S');
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);

        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
        return $this->consultar($objMdLitProcessoSituacaoDTO);
    }

    public function montarIconeProcesso($arrObjProcedimentoAPI)
    {
        $imgIcone = "modulos/litigioso/imagens/svg/balanca_azul_menor.svg?".Icone::VERSAO;

        foreach ($arrObjProcedimentoAPI as $objProcedimentoAPI) {
            $titulo = 'Controle Litigioso: ';
            $diferencaEntreDias = '';

            $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
            $objMdLitProcessoSituacaoDTO->retTodos(true);
            $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($objProcedimentoAPI->getIdProcedimento());
            $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_DESC);
            $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitProcessoSituacaoDTO = $this->consultar($objMdLitProcessoSituacaoDTO);
            if (!$objMdLitProcessoSituacaoDTO) {
                continue;
            }

            //buscando a intimação da instauração( a intimação da instauração será sempre a primeira situação de intimação depois da instauração)
            $objMdLitProcessoSituacaoIntimacaoInstauracao = $this->consultarPrimeiraIntimacao($objProcedimentoAPI->getIdProcedimento());

            if ($objMdLitProcessoSituacaoIntimacaoInstauracao) {
                $diferencaEntreDias = MdLitProcessoSituacaoINT::diferencaEntreDias($objMdLitProcessoSituacaoIntimacaoInstauracao->getDtaData());

                if ($diferencaEntreDias > 1) {
                    $diferencaEntreDias = ' \nTempo desde a Intimação: ' . $diferencaEntreDias . ' dias';
                } else {
                    $diferencaEntreDias = ' \nTempo desde a Intimação: ' . $diferencaEntreDias . ' dia';
                }
            }

            $titulo .= $objMdLitProcessoSituacaoDTO->getStrSiglaTipoControleLitigioso();
            $tipoSituacao = $objMdLitProcessoSituacaoDTO->getStrTipoSituacao();
            $tipoSituacao = $tipoSituacao['nome'] != '' ? " ({$tipoSituacao['nome']})" : '';
            $linhaDeBaixo = 'Fase: ' . $objMdLitProcessoSituacaoDTO->getStrNomeFase() . ' \nSituação: ' . $objMdLitProcessoSituacaoDTO->getStrNomeSituacao() . $tipoSituacao . ' ' . $diferencaEntreDias;
            $img = "<a href='javascript:void(0);' " . PaginaSEI::montarTitleTooltip($linhaDeBaixo, $titulo) . " ><img src='" . $imgIcone . "' class='imagemStatus' /></a>";
            $arrParam[$objProcedimentoAPI->getIdProcedimento()][] = $img;
        }
        return $arrParam;
    }

    protected function alterarPorControleLitigiosoControlado(MdLitControleDTO $objMdLitControleDTO)
    {
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retDtaData();
        $objMdLitProcessoSituacaoDTO->retDblIdDocumento();
        $objMdLitProcessoSituacaoDTO->retStrSinInstauracaoSit();
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($objMdLitControleDTO->getDblIdProcedimento());
        $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($objMdLitControleDTO->getNumIdMdLitTipoControle());
        $objMdLitProcessoSituacaoDTO->setStrSinInstauracaoSit('S');
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);

        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $objMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->consultar($objMdLitProcessoSituacaoDTO);

        $objMdLitProcessoSituacaoDTO->setDtaData($objMdLitControleDTO->getDtaDataInstauracao());
        $objMdLitProcessoSituacaoDTO->setDblIdDocumento($objMdLitControleDTO->getDblIdDocumento());

        $objMdLitProcessoSituacaoBD = new MdLitProcessoSituacaoBD($this->getObjInfraIBanco());
        $objMdLitProcessoSituacaoBD->alterar($objMdLitProcessoSituacaoDTO);
    }

    public function validarConclusaoProcessoAnexado($objProcedimentoAPIAnexado)
    {
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retStrSinConclusivaSit();
        $objMdLitProcessoSituacaoDTO->retStrSiglaTipoControleLitigioso();
        $objMdLitProcessoSituacaoDTO->retTodos(true);
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($objProcedimentoAPIAnexado->getIdProcedimento());
        $objMdLitProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);
        $arrObjMdLitProcessoSituacaoDTO = $this->listar($objMdLitProcessoSituacaoDTO);

        $retorno = array();
        $sigla = array();
        $siglas = '';
        if (count($arrObjMdLitProcessoSituacaoDTO) > 0) {
            foreach ($arrObjMdLitProcessoSituacaoDTO as $item) {
                if ($item->getStrSinConclusivaSit() == 'S') {
                    return $retorno = array(
                        'emAndamento' => false
                    );
                } else {
                    if (!in_array($item->getStrSiglaTipoControleLitigioso(), $sigla)) {
                        $sigla[] = $item->getStrSiglaTipoControleLitigioso();
                        $siglas .= $item->getStrSiglaTipoControleLitigioso() . '\n';
                    }
                    $retorno = array(
                        'emAndamento' => true,
                        'siglas' => $siglas
                    );
                }
            }
        }
        return $retorno;
    }

    public function getDadosPrimeiraSituacaoCadastrada($idProcedimento)
    {
        $objMdLitSitProcessoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitSitProcessoDTO->retNumIdMdLitSituacao();
        $objMdLitSitProcessoDTO->retNumIdUnidade();
        $objMdLitSitProcessoDTO->retNumOrdemParametrizarSit();
        $objMdLitSitProcessoDTO->retStrNomeSituacao();
        $objMdLitSitProcessoDTO->retDblIdProcedimento();
        $objMdLitSitProcessoDTO->retDtaData();
        $objMdLitSitProcessoDTO->retStrSiglaUnidade();
        $objMdLitSitProcessoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitSitProcessoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitSitProcessoDTO->setNumMaxRegistrosRetorno('1');

        return $this->consultar($objMdLitSitProcessoDTO);
    }

    private function _restabelecerSinUltimaDecisao($idProcedimento)
    {
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retTodos();
        $objMdLitProcessoSituacaoDTO->setStrSinDecisoriaSit('S');
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $objMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->consultar($objMdLitProcessoSituacaoDTO);

        if($objMdLitProcessoSituacaoDTO){
            $objMdLitDecisaoRN = new MdLitDecisaoRN();
            $objMdLitDecisaoDesativarDTO = new MdLitDecisaoDTO();
            $objMdLitDecisaoDesativarDTO->retTodos(false);
            $objMdLitDecisaoDesativarDTO->setNumIdMdLitProcessoSituacao($objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao());
            $arrObjMdLitDecisaoDesativarDTO = $objMdLitDecisaoRN->listar($objMdLitDecisaoDesativarDTO);

            foreach ($arrObjMdLitDecisaoDesativarDTO as $objMdLitDecisaoDesativarDTO) {
                $objMdLitDecisaoDesativarDTO->setStrSinUltimaDecisao('S');
                $objMdLitDecisaoRN->alterar($objMdLitDecisaoDesativarDTO);
            }
        }
    }

    private function _recuperarTipoPrazo($idMdLitSituacao)
    {
        $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
        $objMdLitSituacaoDTO->retNumIdSituacaoLitigioso();
        $objMdLitSituacaoDTO->retStrTipoPrazo();
        $objMdLitSituacaoDTO->setNumIdSituacaoLitigioso($idMdLitSituacao);

        $objMdLitSituacaoRN = new MdLitSituacaoRN();
        $objMdLitSituacaoDTO = $objMdLitSituacaoRN->consultar($objMdLitSituacaoDTO);

        return $objMdLitSituacaoDTO->getStrTipoPrazo();
    }
}

?>