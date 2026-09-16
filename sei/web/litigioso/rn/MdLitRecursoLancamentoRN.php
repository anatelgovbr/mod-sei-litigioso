<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRecursoLancamentoRN extends InfraRN {

    public function __construct(){
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

    public function suspenderLancamento($objMdLitLancamentoDTO, $post)
    {
        // RECUPERAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();
        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();

        // RECUPERAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
        $objInfraException = new InfraException();
        $objInfraException = $objMdLitLancamentoRN->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);
        
        // MONTAR PARAMETROS DE SUSPENSAO PARA ENVIAR AO FINANCEIRO
        $montarParametroEntrada = $this->montarParametroEntradaSuspenderLancamento($objMdLitIntegracaoDTO, $post, $objMdLitLancamentoDTO);
        
        // REALIZAR O ENVIO PARA FINANCEIRO
        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_SUSP_LANCAMENTO);
        
        // COM SUCESSO DA SUSPENSAO DEVE ATUALIZAR O LANCAMENTO PARA SUSPENSO
        if($arrResultado) {
            $objMdLitLancamentoDTO->setStrSinSuspenso('S');

            // SE HOUVE SUSPENSAO POR RECURSO ADMINISTRATIVO, O INTERESSADO PERDE O DIREITO AO DESCONTO
            // DE 25% DA RENUNCIA AO DIREITO DE RECORRER (SO CABE PARA QUEM NAO RECORRE)
            $sinTinhaDescontoRenuncia = $objMdLitLancamentoDTO->getStrSinRenunciaRecorrer() == 'S';
            if ($sinTinhaDescontoRenuncia) {
                $objMdLitLancamentoDTO->setStrSinRenunciaRecorrer('N');
                $objMdLitLancamentoDTO->setStrJustificativa('Desconto decorrente da renúncia ao direito de recorrer removido em razão de suspensão do lançamento por Recurso Administrativo.');
            }

            (new MdLitLancamentoRN())->alterar($objMdLitLancamentoDTO);

            if ($sinTinhaDescontoRenuncia) {
                $dados['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO;
                $dados['hdnVlCreditoNaoLancado'] = $objMdLitLancamentoDTO->getDblVlrLancamento();
                (new MdLitRetificarLancamentoRN())->retificarLancamento($objMdLitLancamentoDTO, $dados);
            }
        }
    }

    private function montarParametroEntradaSuspenderLancamento($objMdLitIntegracaoDTO, $post, MdLitLancamentoDTO $objMdLitLancamentoDTO)
    {
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['OBSERVACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrJustificativa();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    $objMdLitLancamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['DTA_PROCESSO']:
                    $objMdLitProcessoSituacaoDTO = (new MdLitProcessoSituacaoRN)->retornarUltimaSituacaoCadastradaProcesso($objMdLitLancamentoDTO->getDblIdProcedimento());
                    $arrData = explode('/', $objMdLitProcessoSituacaoDTO->getDtaData());
                    $dtaProcesso = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaProcesso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['CNPJ_CPF']:
                    $val = $objMdLitLancamentoDTO->getDblCpfInteressado() ? $objMdLitLancamentoDTO->getDblCpfInteressado() : $objMdLitLancamentoDTO->getStrCnpjInteressado();
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $val;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_SUSPENDER_LANCAMENTO['DTA_APRESENTACAO_RECURSO']:
                    $arrData = explode('/', $objMdLitLancamentoDTO->getDtaApresentacaoRecurso() );
                    $data = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $data;
                    break;

            }
        }

        return $montarParametroEntrada;
    }

    public function denegarRecurso($objMdLitLancamentoDTO, $post)
    {
        // RECUPERAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();
        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();

        // RECUPERAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
        $objInfraException = new InfraException();
        $objInfraException = $objMdLitLancamentoRN->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        // MONTAR PARAMETROS DE SUSPENSAO PARA ENVIAR AO FINANCEIRO
        $montarParametroEntrada = $this->montarParametroEntradaDenegarRecurso($objMdLitIntegracaoDTO, $objMdLitLancamentoDTO);

        // REALIZAR O ENVIO PARA FINANCEIRO
        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_DENEGAR_RECURSO);

        // COM SUCESSO DA SUSPENSAO DEVE ATUALIZAR O LANCAMENTO PARA NÃO SUSPENSO
        if($arrResultado) {
            $objMdLitLancamentoDTO->setStrSinSuspenso('N');
            (new MdLitLancamentoRN())->alterar($objMdLitLancamentoDTO);
        }
    }

    private function montarParametroEntradaDenegarRecurso($objMdLitIntegracaoDTO, MdLitLancamentoDTO $objMdLitLancamentoDTO)
    {
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['DATA_DENEGACAO']:
                    $objMdLitProcessoSituacaoDTO = (new MdLitProcessoSituacaoRN)->retornarUltimaSituacaoCadastradaProcesso($objMdLitLancamentoDTO->getDblIdProcedimento());
                    $arrData = explode('/', $objMdLitProcessoSituacaoDTO->getDtaData());
                    $dtaDenegacao = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaDenegacao;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['OBSERVACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrJustificativa();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_DENEGAR_RECURSO['CNPJ_CPF']:
                    $val = $objMdLitLancamentoDTO->getDblCpfInteressado() ? $objMdLitLancamentoDTO->getDblCpfInteressado() : $objMdLitLancamentoDTO->getStrCnpjInteressado();
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $val;
                    break;
            }

        }

        return $montarParametroEntrada;
    }

    public function cancelarRecurso($objMdLitLancamentoDTO, $post){
        // RECUPERAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();
        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();

        // RECUPERAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
        $objInfraException = new InfraException();
        $objInfraException = $objMdLitLancamentoRN->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        // MONTAR PARAMETROS DE CANCELAMENTO DO RECUROS PARA ENVIAR AO FINANCEIRO
        $montarParametroEntrada = $this->montarParametroEntradaCancelarRecurso($objMdLitIntegracaoDTO, $objMdLitLancamentoDTO);

        // REALIZAR O ENVIO PARA FINANCEIRO
        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_CANC_RECURSO);

        // COM SUCESSO DA SUSPENSAO DEVE ATUALIZAR O LANCAMENTO PARA NÃO SUSPENSO
        if($arrResultado) {
            $objMdLitLancamentoDTO->setStrSinSuspenso('N');
            (new MdLitLancamentoRN())->alterar($objMdLitLancamentoDTO);
        }
    }

    private function montarParametroEntradaCancelarRecurso($objMdLitIntegracaoDTO, MdLitLancamentoDTO $objMdLitLancamentoDTO){
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['OBSERVACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrJustificativa();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_RECURSO['CNPJ_CPF']:
                    $val = $objMdLitLancamentoDTO->getDblCpfInteressado() ? $objMdLitLancamentoDTO->getDblCpfInteressado() : $objMdLitLancamentoDTO->getStrCnpjInteressado();
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $val;
                    break;
            }

        }

        return $montarParametroEntrada;
    }
    
}
?>