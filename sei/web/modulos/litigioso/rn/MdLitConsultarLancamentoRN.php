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
                    case MdLitMapearParamEntradaRN::$ID_PARAM_CONSULTAR_LANCAMENTO['NUMERO_INTERESSADO']:
                        if(array_key_exists('numInteressado',$post)){
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['numInteressado'];
                            break;
                        }

                        if(empty($post['hdnNumInteressado'])){
                            $objMdLitDadosInteressadoRN = new MdLitDadoInteressadoRN();
                            $objMdLitDadoInteressadoDTO = $objMdLitDadosInteressadoRN->retornaObjDadoInteressadoPorNumeroInteressado($post);
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitDadoInteressadoDTO->getStrNumero();
                        }else{
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnNumInteressado'];
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

}