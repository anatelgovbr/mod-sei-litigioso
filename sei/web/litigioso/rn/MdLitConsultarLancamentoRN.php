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

    public function consultarLancamento($objMdLitLancamentoDTO)
    {
        //MONTAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
        $objInfraException      = new InfraException();
        
        //MONTA OS PARAMENTROS DE ENTRADA PARA INCLUIR LANCAMENTO
        $montarParametroEntrada = $this->montarParametrosEntradaConsultarLancamentoNOVO($objMdLitIntegracaoDTO, $objMdLitLancamentoDTO);
        
        //ENVIA PARA O FINANCEIRO
        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada);

        //APOS ENVIAR PARA FINANCEIRO DEVE ATUALIZAR O LANCAMENTO COM OS DADOS DO BOLETO
        $post['selCreditosProcesso'] = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
        if(!empty($arrResultado))
            $this->montarParametrosSaidaConsultarLancamento($objMdLitIntegracaoDTO, $arrResultado, $post);
    }

    //TODO O OUTRO METODO SERÁ REFATORADO FUTURAMENTE
    private function montarParametrosEntradaConsultarLancamentoNOVO($objMdLitIntegracaoDTO, $objMdLitLancamentoDTO)
    {
        $montarParametroEntrada = array();
        
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO) {
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()) {
                case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['RENUNCIA_RECURSO']:
                    $renunciaRecurso = $objMdLitLancamentoDTO->getStrSinRenunciaRecorrer();
                    /**
                     * Retirar o desconto caso o debito não seja pago. Considerar o vencimento so apos de 72horas pra não dar erro de processamento do pagamento de boleto.
                     * O serviço de arrecadação necessita dos Sinalizador se estiver com os sinalizador estiver errado retornar nenhum lançamento.
                     */
                    $dtaVencimento = InfraData::calcularData(3, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE,$objMdLitLancamentoDTO->getDtaVencimento());
                    if($renunciaRecurso == 'S' && InfraData::compararDatas($dtaVencimento, InfraData::getStrDataAtual()) >= 0 ){
                        $renunciaRecurso = 'N';
                    }
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $renunciaRecurso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['CNPJ/CPF']:
                    $val = $objMdLitLancamentoDTO->getDblCpfInteressado() ? $objMdLitLancamentoDTO->getDblCpfInteressado() : $objMdLitLancamentoDTO->getStrCnpjInteressado();
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $val;
            }
        }


        return $montarParametroEntrada;
    }


    /**
     * @param $objMdLitIntegracaoDTO
     * @param $post
     * @return MdLitLancamentoDTO|bool| Retorna o objeto de MdLitLancamentoDTO se a consulta de lançamento ter certo, FALSE caso contrário.
     */
    public function verificarAtualizarSituacaoLancamento($objMdLitIntegracaoDTO, $post){
        try {
            $sucesso = false;
            $objMdLitSoapClienteRN   = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
            $montarParametrosEntrada = $this->montarParametrosEntradaConsultarLancamento($objMdLitIntegracaoDTO, $post, 'S');
            $arrResultado            = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametrosEntrada);

            if(empty($arrResultado)){
                //@todo solução paliativa retirar quanto a consultarLancamento web-service estiver pronto
                $montarParametrosEntrada = $this->montarParametrosEntradaConsultarLancamento($objMdLitIntegracaoDTO, $post, 'N');
                $arrResultado            = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametrosEntrada);
            }
            return $this->montarParametrosSaidaConsultarLancamento($objMdLitIntegracaoDTO, $arrResultado, $post);

        } catch ( Exception $e ) {
            //( new InfraException() )->lancarValidacao( $e->getMessage() );
            return false;
        }
    }

    private function montarParametrosSaidaConsultarLancamento($objMdLitIntegracaoDTO, $arrResultado, $post)
    {
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO  = $objMdLitLancamentoRN->retornaObjLancAlteracaoConsultaLanc($post);
        $objMdLitLancamentoDTOAntigo  = $objMdLitLancamentoRN->retornaObjLancAlteracaoConsultaLanc($post);
        $objMdLitCancelaLancDTO = new MdLitCancelaLancamentoDTO();

        //se não existir o lançamento no banco cria o objeto vazio, funcionalidade vincular lancamento
        if(!$objMdLitLancamentoDTO){
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        }

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
                    $objMdLitSitLancamentoDTO = $objMdLitSitLancamentoRN->verificaSituacaoExiste($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    if(!is_null($objMdLitLancamentoDTO) && $objMdLitSitLancamentoDTO) {
                        $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento($objMdLitSitLancamentoDTO->getNumIdMdLitSituacaoLancamento());
                        $objMdLitLancamentoDTO->setStrNomeSituacao($objMdLitSitLancamentoDTO->getStrNome());
                    }

                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['LINK_BOLETO']:
                    if(!is_null($objMdLitLancamentoDTO)) {
                        $objMdLitLancamentoDTO->setStrLinkBoleto($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                    }

                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['VENCIDO']:
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['VL_DESCONTO']:
                    $vlrDesconto = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;
                    if($vlrDesconto){
                        $objMdLitLancamentoDTO->setDblVlrDesconto($vlrDesconto);
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['DT_ULTIMO_PAGAMENTO']:
                    if(isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) && trim($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()])){
                        $dtaUltimoPagamento =  $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()];
                        $objMdLitLancamentoDTO->setDtaUltimoPagamento(date('d/m/Y', strtotime(str_replace('/', '-', $dtaUltimoPagamento))));
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['VL_ULTIMO_PAGAMENTO']:
                    $vlrUltimoPagamento = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if($vlrUltimoPagamento){
                        $objMdLitLancamentoDTO->setDblVlrPago($vlrUltimoPagamento);
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['VALOR_ATUALIZADO']:
                    $vlrAtualizado = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if($vlrAtualizado){
                        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor($vlrAtualizado);
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['VALOR_RECEITA_INICIAL']:
                    $vlrReceitaInicial = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if(!$objMdLitLancamentoDTO->isSetDblVlrLancamento() && $vlrReceitaInicial){
                        $objMdLitLancamentoDTO->setDblVlrLancamento($vlrReceitaInicial);
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['DTA_VENCIMENTO']:
                    $dtaVencimento = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if(!$objMdLitLancamentoDTO->isSetDtaVencimento() && $dtaVencimento){
                        $objMdLitLancamentoDTO->setDtaVencimento(date('d/m/Y', strtotime(str_replace('/', '-', $dtaVencimento))));
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['NUM_COMPLEMENTAR_INTERESSADO']:
                    $numComplementarUsuario = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if(!$objMdLitLancamentoDTO->isSetStrNumeroInteressado() && $numComplementarUsuario){
                        $objMdLitLancamentoDTO->setStrNumeroInteressado($numComplementarUsuario);
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['IDENTIFICACAO_LANCAMENTO']:
                    $numIdentificacaoLancamento = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if(!$objMdLitLancamentoDTO->isSetStrSequencial() && $numIdentificacaoLancamento){
                        $objMdLitLancamentoDTO->setStrSequencial($numIdentificacaoLancamento);
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['COD_RECEITA']:
                    $codigoReceita = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if(!$objMdLitLancamentoDTO->isSetStrCodigoReceita() && $codigoReceita){
                        $objMdLitLancamentoDTO->setStrCodigoReceita($codigoReceita);
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['DTA_CONSTITUICAO_DEFINITIVA']:
                    $dtaConstituicaoDefinitiva = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if(!$objMdLitLancamentoDTO->isSetDtaConstituicaoDefinitiva() && $dtaConstituicaoDefinitiva){
                        $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva(date('d/m/Y', strtotime(str_replace('/', '-', $dtaConstituicaoDefinitiva))));
                        $objMdLitLancamentoDTO->setStrSinConstituicaoDefinitiva('S');
                    }
                    break;

                case MdLitMapearParamSaidaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['DTA_APLICACAO_MULTA']:
                    $dtaDecisao = isset($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]) ? $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()] : null;

                    if(!$objMdLitLancamentoDTO->isSetDtaDecisao() && $dtaDecisao){
                        $objMdLitLancamentoDTO->setDtaDecisao(date('d/m/Y', strtotime(str_replace('/', '-', $dtaDecisao))));
                    }
                    break;

            }
        }

        $objMdLitLancamentoDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
        $objMdLitLancamentoDTO->setNumIntegracaoIdMdLitFuncionalidade($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade());
        //verifica se o que vem do web-service e diferente do banco e atualiza o banco de dados
        if($objMdLitLancamentoDTO !== false && $objMdLitLancamentoDTO->isSetNumIdMdLitLancamento()){
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);
        }

        return $objMdLitLancamentoDTO;
    }

    private function montarParametrosEntradaConsultarLancamento($objMdLitIntegracaoDTO, $dadosEntrada, $sinRenunciaRecurso= null)
    {
        $montarParametroEntrada = array();
        $idLancamento = array_key_exists('selCreditosProcesso', $dadosEntrada) ? $dadosEntrada['selCreditosProcesso'] : '';
        if (!array_key_exists('selCreditosProcesso', $dadosEntrada)) {
            $idLancamento = array_key_exists('hdnCreditosProcesso', $dadosEntrada) ? $dadosEntrada['hdnCreditosProcesso'] : '';
        }

        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->retornaObjLancamento($idLancamento);

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO) {
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()) {
                case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['NUMERO_INTERESSADO']:
                        if(array_key_exists('numInteressado',$dadosEntrada)){
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dadosEntrada['numInteressado'];
                            break;
                        }

                        if(empty($dadosEntrada['hdnNumInteressado'])){
                            $objMdLitDadosInteressadoRN = new MdLitDadoInteressadoRN();
                            $objMdLitDadoInteressadoDTO = $objMdLitDadosInteressadoRN->retornaObjDadoInteressadoPorNumeroInteressado($dadosEntrada);
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitDadoInteressadoDTO->getStrNumero();
                        }else{
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dadosEntrada['hdnNumInteressado'];
                        }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['SEQUENCIAL']:
                    if($objMdLitLancamentoDTO){
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    }else{
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dadosEntrada['numIdentificacaoLancamento'];
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['RENUNCIA_RECURSO']:
                    if(!empty($sinRenunciaRecurso)){
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $sinRenunciaRecurso;
                        break;
                    }


                    $renunciaRecurso = array_key_exists('chkReducaoRenuncia', $dadosEntrada) && $dadosEntrada['chkReducaoRenuncia'] == 'S' ? 'S' : 'N';

                    /**
                     * Retirar o desconto caso o debito não seja pago. Considerar o vencimento so apos de 72horas pra não dar erro de processamento do pagamento de boleto.
                     * O serviço de arrecadação necessita dos Sinalizador se estiver com os sinalizador estiver errado retornar nenhum lançamento.
                     */
                    $dtaVencimento = InfraData::calcularData(3, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE,$objMdLitLancamentoDTO->getDtaVencimento());
                    if($renunciaRecurso == 'S' && InfraData::compararDatas($dtaVencimento, InfraData::getStrDataAtual()) >= 0 ){
                        $renunciaRecurso = 'N';
                    }
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $renunciaRecurso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['CNPJ/CPF']:
                    $val = $objMdLitLancamentoDTO->getDblCpfInteressado() ? $objMdLitLancamentoDTO->getDblCpfInteressado() : $objMdLitLancamentoDTO->getStrCnpjInteressado();
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $val;
            }
        }


        return $montarParametroEntrada;
    }

    public function vincularLancamento($numeroInteressado, $numIdentificacaoLancamento)
    {
        $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);

        $dadosEntrada = array();
        $objMdLitSoapClienteRN = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl(), ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
        $dadosEntrada['hdnNumInteressado'] = $numeroInteressado;
        $dadosEntrada['numIdentificacaoLancamento'] = $numIdentificacaoLancamento;
        $montarParametrosEntrada = $this->montarParametrosEntradaConsultarLancamento($objMdLitIntegracaoDTO, $dadosEntrada);

        try {
            $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametrosEntrada);
            $objMdLitLancamentoDTO = $this->montarParametrosSaidaConsultarLancamento($objMdLitIntegracaoDTO, $arrResultado, $dadosEntrada);
            return $objMdLitLancamentoDTO;
        }catch (Exception $e){
            ( new InfraException() )->lancarValidacao("Erro ao consultar o web-service do sistema de arrecadação: ".$e->getMessage() );
        }
    }

}