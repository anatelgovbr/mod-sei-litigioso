<?
/**
 * ANATEL
 *
 * 05/09/2017 - criado por jaqueline.mendes - CAST
 *
 */

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitConsultarLancamentoRN extends InfraRN
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
     * @param $objMdLitIntegracaoDTO
     * @param $post
     * @return MdLitLancamentoDTO|bool| Retorna o objeto de MdLitLancamentoDTO se a consulta de lançamento ter certo, FALSE caso contrário.
     */
    public function verificarAtualizarSituacaoLancamentoSIGEC($objMdLitIntegracaoDTO, $post){

        $sucesso = false;
        $objMdLitSoapClienteRN   = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');
        $montarParametrosEntrada = $this->_montarParametrosEntradaConsultarLancamento($objMdLitIntegracaoDTO, $post);

        /**MOC **/
        $arrResultado            = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametrosEntrada);
        $err                     = $objMdLitSoapClienteRN->getError();

        $dadosSaida = '';
        if(empty($err)){
            $objMdLitLancamentoDTO = $this->_montarParametrosSaidaConsultarLancamento($objMdLitIntegracaoDTO, $arrResultado, $post);
            return $objMdLitLancamentoDTO;
        }
  

        return $sucesso;

    }

    private function _montarParametrosSaidaConsultarLancamento($objMdLitIntegracaoDTO, $arrResultado, $post)
    {
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO  = $objMdLitLancamentoRN->retornaObjLancAlteracaoConsultaLanc($post);
        $objMdLitLancamentoDTOAntigo  = $objMdLitLancamentoRN->retornaObjLancAlteracaoConsultaLanc($post);
        $objMdLitCancelaLancDTO = new MdLitCancelaLancamentoDTO();

        if (empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO())) {
            throw new InfraException('Os parâmetros de entrada e saída não foram parametrizado. Contate o Gestor do Controle.');
        }
        if(empty($arrResultado)){
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('Não foi possível a comunicação com o Webservice da Arrecadação. Contate o Gestor do Controle.');
            $objInfraException->lancarValidacoes();
            return false;
        }

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO) {
            switch ($objMdLitMapearParamSaidaDTO->getNumIdMdLitCampoIntegracao()) {
                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['CODIGO_SITUACAO']:
                    $objMdLitSitLancamentoRN = new MdLitSituacaoLancamentoRN();
                    $sitExiste = $objMdLitSitLancamentoRN->verificaSituacaoExiste($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    if(!is_null($objMdLitLancamentoDTO) && $sitExiste) {
                        $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    }

                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['LINK_BOLETO']:
                    if(!is_null($objMdLitLancamentoDTO)) {
                        $objMdLitLancamentoDTO->setStrLinkBoleto($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    }

                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['VENCIDO']:
//                    $objMdLitLancamento->setStrCodigoReceita($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['VL_DESCONTO']:
                    $vlrDesconto = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;
                    if(InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrDesconto()) != $vlrDesconto){
                        $objMdLitLancamentoDTO->setDblVlrDesconto($vlrDesconto);
                        $vlrSaldoDevedor = InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrSaldoDevedor());
                        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor(bcsub($vlrSaldoDevedor, $vlrDesconto, 2));
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['DT_ULTIMO_PAGAMENTO']:
                    if(isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()])){
                        $dtaUltimoPagamento =  $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()];
                        $objMdLitLancamentoDTO->setDtaUltimoPagamento(date('d/m/Y', strtotime($dtaUltimoPagamento)));
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['VL_ULTIMO_PAGAMENTO']:
                    $vlrUltimoPagamento = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if($vlrUltimoPagamento && InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrPago()) != $vlrUltimoPagamento){

                        $objMdLitLancamentoDTO->setDblVlrPago($vlrUltimoPagamento);
                        $vlrLancamento = InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento());
                        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor(bcsub($vlrLancamento, $vlrUltimoPagamento, 2));
                    }
                    break;


            }
        }

        //verifica se o que vem do web-service e diferente do banco e atualiza o banco de dados
        if($objMdLitLancamentoDTO !== false && (
                $objMdLitLancamentoDTO->getNumIdMdLitSituacaoLancamento() != $objMdLitLancamentoDTOAntigo->getNumIdMdLitSituacaoLancamento()
                || $objMdLitLancamentoDTO->getStrLinkBoleto() != $objMdLitLancamentoDTOAntigo->getStrLinkBoleto()
                || $objMdLitLancamentoDTO->getDblVlrDesconto() != $objMdLitLancamentoDTOAntigo->getDblVlrDesconto()
                || $objMdLitLancamentoDTO->getDtaUltimoPagamento() != $objMdLitLancamentoDTOAntigo->getDtaUltimoPagamento()
                || $objMdLitLancamentoDTO->getDblVlrPago() != $objMdLitLancamentoDTOAntigo->getDblVlrPago()) ){
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);
        }

        return $objMdLitLancamentoDTO;
    }

    private function _montarParametrosEntradaConsultarLancamento($objMdLitIntegracaoDTO, $post){
        $montarParametroEntrada = array();
        $idLancamento = array_key_exists('selCreditosProcesso', $post) ? $post['selCreditosProcesso'] : '';
        $objMdLitLancamentoRN = new MdLitLancamentoRN();

        if ($idLancamento != '') {
            foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO) {
                switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()) {
                    case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['FISTEL']:
                        if(array_key_exists('numFistel',$post)){
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['numFistel'];
                            break;
                        }

                        if(empty($post['hdnNumFistel'])){
                            $objMdLitDadosInteressadoRN = new MdLitDadoInteressadoRN();
                            $objMdLitDadoInteressadoDTO = $objMdLitDadosInteressadoRN->retornaObjDadoInteressadoPorFistel($post);
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitDadoInteressadoDTO->getStrNumero();
                        }else{
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnNumFistel'];
                        }
                        break;

                    case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['SEQUENCIAL']:
                        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->retornaObjLancamento($idLancamento);
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                        break;

                    case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['RENUNCIA_RECURSO']:
                        $renunciaRecurso = array_key_exists('chkReducaoRenuncia', $post) && $post['chkReducaoRenuncia'] == 'S' ? 'S' : 'N';
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $renunciaRecurso;
                        break;
                }
            }
        }


        return $montarParametroEntrada;
    }

    public function consultarAgendamento(){

        try{
            ini_set('max_execution_time','0');
            ini_set('memory_limit','1024M');

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();
            InfraDebug::getInstance()->gravar('Atualizando os lançamentos com o sistema de Arrecadação');

            $numSeg = InfraUtil::verificarTempoProcessamento();

            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);

            if(is_null($objMdLitIntegracaoDTO)){
                throw new InfraException('É necessário realizar a integração com a funcionalidade de Consultar Lançamento.');
            }

            if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO())){
                throw new InfraException('Os parâmetros de entrada e saída não foram parametrizados. Contate o Gestor do Controle.');
            }

            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->retTodos(false);
            $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento(array(MdLitSituacaoLancamentoRN::$CANCELADO,MdLitSituacaoLancamentoRN::$QUITADO), InfraDTO::$OPER_NOT_IN);

            $objMdLitLancamentoRN = new MdLitLancamentoRN();
            $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

            if(count($arrObjMdLitLancamentoDTO)){
                foreach ($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){
                    try{
                        $params = array();
                        $params['numFistel']            = $objMdLitLancamentoDTO->getStrFistel();
                        $params['selCreditosProcesso']  = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
                        $params['chkReducaoRenuncia']   = $objMdLitLancamentoDTO->getStrSinRenunciaRecorrer();

                        $sucesso = $this->verificarAtualizarSituacaoLancamentoSIGEC($objMdLitIntegracaoDTO, $params);


                        if ($sucesso === false) {
                            InfraDebug::getInstance()->gravar('Fistel: ' . $objMdLitLancamentoDTO->getStrFistel());
                            InfraDebug::getInstance()->gravar('Sequencial: ' . $objMdLitLancamentoDTO->getStrSequencial());
                            InfraDebug::getInstance()->gravar('Erro ao atualizar o lançamento!');
                            InfraDebug::getInstance()->gravar('------------------------------------------------------------------------------');
                        }

                    }catch(Exception $e){

                        InfraDebug::getInstance()->gravar('Fistel: '.$objMdLitLancamentoDTO->getStrFistel());
                        InfraDebug::getInstance()->gravar('Sequencial: '.$objMdLitLancamentoDTO->getStrSequencial());

                        if ( $e instanceof InfraException ) {
                            if ($e->contemValidacoes()) {
                                InfraDebug::getInstance()->gravar('Exception validação: '.$e->__toString());
                                if($e->getObjException()){
                                    InfraDebug::getInstance()->gravar('Exception Soap: '.$e->getObjException()->getMessage());
                                }
                            }
                        }else{
                            InfraDebug::getInstance()->gravar('Erro ao atualizar o lançamento '.$e->getMessage());
                        }
                        InfraDebug::getInstance()->gravar('------------------------------------------------------------------------------');
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

            throw new InfraException('Erro removendo dados temporários de auditoria.',$e);
        }
    }

}