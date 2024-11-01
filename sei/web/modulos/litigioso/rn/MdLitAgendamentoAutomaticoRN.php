<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4 REGIO
 *
 * 05/01/2018 - criado por ellyson.cast
 *
 * Verso do Gerador de Cdigo: 1.40.0
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitAgendamentoAutomaticoRN extends InfraRN {

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }


    protected function consultarLancamentoControlado(){

        try{
            ini_set('max_execution_time','0');
            ini_set('memory_limit','1024M');

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();
            InfraDebug::getInstance()->gravar('Atualizando os lan�amentos com o sistema de Arrecada��o');

            $numSeg = InfraUtil::verificarTempoProcessamento();

            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitConsultaLancamentoRN = new MdLitConsultarLancamentoRN();
            $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);

            if(!is_null($objMdLitIntegracaoDTO)) {

                if (empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO())) {
                    throw new InfraException('Os par�metros de entrada e sa�da n�o foram parametrizados. Contate o Gestor do Controle.');
                }

                $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                $objMdLitSituacaoLancamentoDTO->retNumIdMdLitSituacaoLancamento();
                $objMdLitSituacaoLancamentoDTO->setStrSinUtilizarAgendamento('S');

                $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
                $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->listar($objMdLitSituacaoLancamentoDTO);

                $arrIdSituacao = InfraArray::converterArrInfraDTO($objMdLitSituacaoLancamentoDTO, 'IdMdLitSituacaoLancamento');

                $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
                $objMdLitLancamentoDTO->retTodos(false);
                $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento($arrIdSituacao , InfraDTO::$OPER_IN);

                $objMdLitLancamentoRN = new MdLitLancamentoRN();
                $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

                if (count($arrObjMdLitLancamentoDTO)) {
                    foreach ($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO) {
                        try {
                            $params = array();
                            $params['numInteressado'] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                            $params['selCreditosProcesso'] = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
                            $params['chkReducaoRenuncia'] = $objMdLitLancamentoDTO->getStrSinRenunciaRecorrer();

                            $sucesso = $objMdLitConsultaLancamentoRN->verificarAtualizarSituacaoLancamento($objMdLitIntegracaoDTO, $params);


                            if ($sucesso === false) {
                                InfraDebug::getInstance()->gravar('N�mero de complemento do interessado: ' . $objMdLitLancamentoDTO->getStrNumeroInteressado());
                                InfraDebug::getInstance()->gravar('Sequencial: ' . $objMdLitLancamentoDTO->getStrSequencial());
                                InfraDebug::getInstance()->gravar('Erro ao atualizar o lan�amento!');
                                InfraDebug::getInstance()->gravar('------------------------------------------------------------------------------');
                            }

                        } catch (Exception $e) {

                            InfraDebug::getInstance()->gravar('N�mero de complemento do interessado: ' . $objMdLitLancamentoDTO->getStrNumeroInteressado());
                            InfraDebug::getInstance()->gravar('Sequencial: ' . $objMdLitLancamentoDTO->getStrSequencial());

                            if ($e instanceof InfraException) {
                                if ($e->contemValidacoes()) {
                                    InfraDebug::getInstance()->gravar('Exception valida��o: ' . $e->__toString());
                                    if ($e->getObjException()) {
                                        InfraDebug::getInstance()->gravar('Exception Soap: ' . $e->getObjException()->getMessage());
                                    }
                                }
                            } else {
                                InfraDebug::getInstance()->gravar('Erro ao atualizar o lan�amento ' . $e->getMessage());
                            }
                            InfraDebug::getInstance()->gravar('------------------------------------------------------------------------------');
                        }
                    }
                }
            }


            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
        }catch(Exception $e){
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

            throw new InfraException('Erro removendo dados tempor�rios de auditoria.',$e);
        }
    }
}
?>
