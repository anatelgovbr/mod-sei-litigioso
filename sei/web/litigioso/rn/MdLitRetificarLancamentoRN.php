<?
/**
* ANATEL
*
* 11/03/2026 - criado por michaelr.colab
*
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRetificarLancamentoRN extends InfraRN {

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    public function retificarLancamento($objMdLitLancamentoDTO, $post)
    {
        // RECUPERAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
        $objInfraException = new InfraException();
        $objInfraException = (new MdLitLancamentoRN)->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        // MONTAR PARAMETROS DE RETIFICACAO PARA ENVIAR AO FINANCEIRO
        $montarParametroEntrada = $this->montarParametroEntradaRetificarCredito($objMdLitIntegracaoDTO, $objMdLitLancamentoDTO);

        // ENVIAR PARA O FINANCEIRO
        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_RETIF_LANCAMENTO);

        // APOS ENVIAR DEVE ATUALIZAR OS DADOS DO BOLETO DO LANCAMENTO COM OS RETORNADOS PELO FINANCEIRO 
        $this->atualizarLancamentoComParametroSaidaFinanceiro($objMdLitLancamentoDTO, $arrResultado, $post['hdnIdMdLitFuncionalidade']);

    }

    public function montarParametroEntradaRetificarCredito($objMdLitIntegracaoDTO, MdLitLancamentoDTO $objMdLitLancamentoDTO)
    {

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['COD_RECEITA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrCodigoReceita();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_VENCIMENTO']:
                    $arrData = explode('/', $objMdLitLancamentoDTO->getDtaVencimento() );
                    $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_APLICACAO_SANCAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                    if($objMdLitLancamentoDTO->isSetDtaDecisao() && $objMdLitLancamentoDTO->getDtaDecisao()){
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaDecisao() );
                        $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['VALOR_TOTAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getDblVlrLancamento();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['JUSTIFICATIVA_LANCAMENTO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrJustificativa();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['NUM_PROCESSO']:
                    $numProcesso = (new MdLitLancamentoRN)->retornaNumProcessoFormatado($objMdLitLancamentoDTO->getDblIdProcedimento());
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $numProcesso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['CNPJ_CPF']:
                    $val = $objMdLitLancamentoDTO->getDblCpfInteressado() ? $objMdLitLancamentoDTO->getDblCpfInteressado() : $objMdLitLancamentoDTO->getStrCnpjInteressado();
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $val;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DOC_DECISAO_APL_MULTA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $this->retornaProtocoloFormatadoDocumentoPorSituacao($objMdLitLancamentoDTO->getNumIdSituacaoDecisao());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_INT_DECISAO_APL_MULTA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                    if($objMdLitLancamentoDTO->isSetDtaIntimacao() && $objMdLitLancamentoDTO->getDtaIntimacao()){
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaIntimacao() );
                        $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_DECURSO_PRAZO_RECURSO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                    if($objMdLitLancamentoDTO->isSetDtaDecursoPrazoRecurso() && $objMdLitLancamentoDTO->getDtaDecursoPrazoRecurso()){
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaDecursoPrazoRecurso() );
                        $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_APRESENTACAO_RECURSO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                    if($objMdLitLancamentoDTO->isSetDtaApresentacaoRecurso() && $objMdLitLancamentoDTO->getDtaApresentacaoRecurso()){
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaApresentacaoRecurso());
                        $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_DECISAO_DEFINITIVA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                    if($objMdLitLancamentoDTO->isSetDtaDecisaoDefinitiva() && $objMdLitLancamentoDTO->getDtaDecisaoDefinitiva()){
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaDecisaoDefinitiva());
                        $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_CONSTITUICAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                    if($objMdLitLancamentoDTO->isSetDtaConstituicaoDefinitiva() && $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva()){
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva());
                        $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_INTIMACAO_DEFINITIVA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                    if($objMdLitLancamentoDTO->isSetDtaIntimacaoDefinitiva() && $objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva()){
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva());
                        $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['RENUNCIA_RECURSO']:
                  $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSinRenunciaRecorrer();
                  break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DOC_DECISAO_DEFINITIVA']:
                  $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                  if($objMdLitLancamentoDTO->isSetNumIdMdLitSituacaoDecisaoDefin() && $objMdLitLancamentoDTO->getNumIdMdLitSituacaoDecisaoDefin()){
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $this->retornaProtocoloFormatadoDocumentoPorSituacao($objMdLitLancamentoDTO->getNumIdMdLitSituacaoDecisaoDefin());
                  }
                  break;
            }

        }
        
        return $montarParametroEntrada;
    }

    public function atualizarLancamentoComParametroSaidaFinanceiro(MdLitLancamentoDTO $objMdLitLancamento, $arrResultado, $idMdLitFuncionalidade)
    {
        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoDTO->retTodos(true);
        $objMdLitIntegracaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($idMdLitFuncionalidade);

        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultaMapeaEntradaSaida($objMdLitIntegracaoDTO);

        if(empty($arrResultado))
            return null;

        if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO()))
            throw new InfraException('Os parâmetros de entrada e saída não foram parametrizado. Contate o Gestor do Controle.');

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO){
            switch ($objMdLitMapearParamSaidaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['SEQUENCIAL']:
                    $objMdLitLancamento->setStrSequencial($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['LINK_BOLETO']:
                    $objMdLitLancamento->setStrLinkBoleto($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['COD_RECEITA']:
                    $objMdLitLancamento->setStrCodigoReceita($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['NUMERO_INTERESSADO']:
                    $objMdLitLancamento->setStrNumeroInteressado($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    break;
            }
        }

        (new MdLitLancamentoRN)->alterar($objMdLitLancamento);
    }
  
}
?>