<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versгo do Gerador de Cуdigo: 1.40.1
 */

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRecursoLancamentoRN extends InfraRN {

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    public function suspenderLancamento($post){
        $objInfraException      = new InfraException();
        $objMdLitLancamentoDTO  = new MdLitLancamentoDTO();
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();

        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');
        $objInfraException      = $objMdLitLancamentoRN->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($post['selCreditosProcesso']);

        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        $montarParametroEntrada = $this->montarParametroEntradaSuspenderLancamento($objMdLitIntegracaoDTO, $post, $objMdLitLancamentoDTO);

        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_SUSP_LANCAMENTO);

        if($arrResultado) {
            $objMdLitLancamentoDTO->setStrSinSuspenso('S');
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);

            return $objMdLitLancamentoDTO;
        }
    }

    private function montarParametroEntradaSuspenderLancamento($objMdLitIntegracaoDTO, $post, MdLitLancamentoDTO $objMdLitLancamentoDTO){
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['OBSERVACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnJustificativaLancamento'];
                    $objMdLitLancamentoDTO->setStrJustificativa($post['hdnJustificativaLancamento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    $objMdLitLancamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['DTA_PROCESSO']:
                    $arrSituacaoUltimaSituacao     = PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbSituacoes']);
                    $arrSituacaoUltimaSituacao = end($arrSituacaoUltimaSituacao);

                    //verificar se o ultima situaзгo e do tipo recursal
                    if($arrSituacaoUltimaSituacao['17'] != 'Recursal'){
                        $infraException = new InfraException();
                        $infraException->adicionarValidacao('Nгo й um tipo recursal!');
                        $infraException->lancarValidacoes();
                    }

                    //pegar a Data do Recurso
                    $arrData = explode('/', $arrSituacaoUltimaSituacao['11']);
                    $dtaProcesso = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaProcesso;
                    break;
            }

        }

        return $montarParametroEntrada;
    }

    public function cancelarRecurso($post){
        $objInfraException      = new InfraException();
        $objMdLitLancamentoDTO  = new MdLitLancamentoDTO();
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();

        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');
        $objInfraException      = $objMdLitLancamentoRN->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($post['selCreditosProcesso']);

        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        $montarParametroEntrada = $this->montarParametroEntradaCancelarRecurso($objMdLitIntegracaoDTO, $post, $objMdLitLancamentoDTO);

        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_CANC_RECURSO);

        if($arrResultado) {
            $objMdLitLancamentoDTO->setStrSinSuspenso('N');
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);

            return $objMdLitLancamentoDTO;
        }
    }

    private function montarParametroEntradaCancelarRecurso($objMdLitIntegracaoDTO, $post, MdLitLancamentoDTO $objMdLitLancamentoDTO){
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['OBSERVACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnJustificativaLancamento'];
                    $objMdLitLancamentoDTO->setStrJustificativa($post['hdnJustificativaLancamento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;
            }

        }

        return $montarParametroEntrada;
    }

    public function denegarRecurso($post){
        $objInfraException      = new InfraException();
        $objMdLitLancamentoDTO  = new MdLitLancamentoDTO();
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();

        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);

        $objInfraException      = $objMdLitLancamentoRN->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');

        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($post['selCreditosProcesso']);

        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        $montarParametroEntrada = $this->montarParametroEntradaDenegarRecurso($objMdLitIntegracaoDTO, $post, $objMdLitLancamentoDTO);

        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_DENEGAR_RECURSO);

        if($arrResultado) {
            $objMdLitLancamentoDTO->setStrSinSuspenso('N');
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);

            return $objMdLitLancamentoDTO;
        }
    }

    private function montarParametroEntradaDenegarRecurso($objMdLitIntegracaoDTO, $post, MdLitLancamentoDTO $objMdLitLancamentoDTO){
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['DATA_DENEGACAO']:
                    $arrSituacaoUltimaSituacao     = PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbSituacoes']);
                    $arrSituacaoUltimaSituacao = end($arrSituacaoUltimaSituacao);

                    //pegar a Data do Recurso
                    $arrData = explode('/', $arrSituacaoUltimaSituacao['11']);
                    $dtaDenegacao = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaDenegacao;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['OBSERVACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnJustificativaLancamento'];
                    $objMdLitLancamentoDTO->setStrJustificativa($post['hdnJustificativaLancamento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    break;
            }

        }

        return $montarParametroEntrada;
    }

}
?>