<?
/**
* ANATEL
*
* 11/03/2026 - criado por michaelr.colab
*
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitIncluirLancamentoRN extends InfraRN {

    public function __construct(){
      parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
      return BancoSEI::getInstance();
    }

    public function incluirLancamento($objMdLitLancamentoDTO, $post)
    {
        
        //MONTAR OBJETO DE INTEGRACAO COM FINANCEIRO
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN( $objMdLitIntegracaoDTO->getStrEnderecoWsdl() , ['soap_version' => $objMdLitIntegracaoDTO->getStrVersaoSoap()] );
        $objInfraException      = new InfraException();
        $objInfraException      = (new MdLitLancamentoRN)->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);
        
        //MONTA OS PARAMENTROS DE ENTRADA PARA INCLUIR LANCAMENTO
        $montarParametroEntrada = $this->montarParametroEntradaIncluirLancamento($objMdLitIntegracaoDTO, $post['hdnIdTipoControle'], $objMdLitLancamentoDTO);
        
        //ENVIA PARA O FINANCEIRO
        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_LANCAMENTO);

        //APOS ENVIAR PARA FINANCEIRO DEVE ATUALIZAR O LANCAMENTO COM OS DADOS DO BOLETO
        $this->atualizarLancamentoComParametroSaidaLancamentoCredito($objMdLitLancamentoDTO, $arrResultado, $post['hdnIdMdLitFuncionalidade'],$post['hdnIdTipoControle']);
        
        //POR FINAL DEVE CONSULTAR NO FINANCEIRO PARA ATUALIZAR INFORMAЧеES QUE NУO SAO RETORNADAS AO INCLUIR LANCAMENTO
        (new MdLitConsultarLancamentoRN)->consultarLancamento($objMdLitLancamentoDTO);

    }

    private function montarParametroEntradaIncluirLancamento($objMdLitIntegracaoDTO, $idTipoControle, $objMdLitLancamentoDTO)
    {

        $montarParametroEntrada = array();
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['COD_RECEITA']:

                    $objMdLitMapeaParamValorDTO = new MdLitMapeaParamValorDTO();
                    $objMdLitMapeaParamValorDTO->retTodos(false);
                    $objMdLitMapeaParamValorDTO->setNumMaxRegistrosRetorno(1);
                    $objMdLitMapeaParamValorDTO->setNumIdMdLitMapearParamEntrada($objMdLitMapearParamEntradaDTO->getNumIdMdLitMapearParamEntrada());
                    $objMdLitMapeaParamValorDTO->setNumIdMdLitTipoControle($idTipoControle);

                    $objMdLitMapeaParamValorRN = new MdLitMapeaParamValorRN();
                    $objMdLitMapeaParamValorDTO = $objMdLitMapeaParamValorRN->consultar($objMdLitMapeaParamValorDTO);
                    if(!$objMdLitMapeaParamValorDTO)
                        throw new InfraException('O codigo da receita nуo estс parametrizado. Contate o Gestor do Controle.');

                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitMapeaParamValorDTO->getStrValorDefault();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['DTA_VENCIMENTO']:
                    $arrData = explode('/', $objMdLitLancamentoDTO->getDtaVencimento() );
                    $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['DTA_APLICACAO_SANCAO']:
                    $arrData = explode('/', $objMdLitLancamentoDTO->getDtaDecisao() );
                    $dtaFormatada = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaFormatada;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $this->retornaCpfCnpjInteressado($objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado());
                    if ($this->retornarExigeDadosComplementares($idTipoControle) == 'S') {
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    }
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['VALOR_RECEITA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getDblVlrLancamento();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['JUSTIFICATIVA_LANCAMENTO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrJustificativa();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['NUM_PROCESSO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = (new MdLitLancamentoRN)->retornaNumProcessoFormatado($objMdLitLancamentoDTO->getDblIdProcedimento());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['VALIDAR_MAJORACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = false;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['CNPJ_CPF']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $this->retornarCpfCnpjInteressadoFormatado($objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['DOCUMENTO_APLICACAO_DA_MULTA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = (new MdLitLancamentoRN)->retornaProtocoloFormatadoDocumentoPorSituacao($objMdLitLancamentoDTO->getNumIdSituacaoDecisao());
                    break;
            }
        }
        return $montarParametroEntrada;
    }

    private function retornarCpfCnpjInteressadoFormatado($idMdLitNumeroInteressado)
    {
        $objMdLitDadoInteressadoDTO = $this->retornaObjDadoInteressado($idMdLitNumeroInteressado);

        if($objMdLitDadoInteressadoDTO->getStrCnpj()){
            $strCnpjVal = $objMdLitDadoInteressadoDTO->getStrCnpj();
            return ctype_digit($strCnpjVal) ? str_pad($strCnpjVal, 14, '0', STR_PAD_LEFT) : strtoupper($strCnpjVal);
        }else{
            return str_pad($objMdLitDadoInteressadoDTO->getDblCpf(),  11, '0',STR_PAD_LEFT);
        }

    }

    public function retornaCpfCnpjInteressado($idNumeroInteressado)
    {
        $objMdLitDadoInteressadoDTO = $this->retornaObjDadoInteressado($idNumeroInteressado);
        $retorno = $objMdLitDadoInteressadoDTO->getStrCnpj();

        if (!$objMdLitDadoInteressadoDTO->getStrCnpj()){
            $retorno = $objMdLitDadoInteressadoDTO->getDblCpf();
        }

        return $retorno;
    }

    private function retornaObjDadoInteressado($idNumeroInteressado)
    {
        $objMdLitNumeroInteressadoDTO = (new MdLitNumeroInteressadoRN)->retornaObjNumeroInteressado($idNumeroInteressado);

        $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
        $objMdLitDadoInteressadoDTO->retDblCpf();
        $objMdLitDadoInteressadoDTO->retStrCnpj();
        $objMdLitDadoInteressadoDTO->setNumIdMdLitDadoInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitDadoInteressado());
        $objMdLitDadoInteressadoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitDadoInteressadoDTO = (new MdLitDadoInteressadoRN())->consultar($objMdLitDadoInteressadoDTO);

        return $objMdLitDadoInteressadoDTO;
    }

    public function atualizarLancamentoComParametroSaidaLancamentoCredito(MdLitLancamentoDTO $objMdLitLancamento, $arrResultado, $idMdLitFuncionalidade, $idTipoControle)
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
              throw new InfraException('Os parтmetros de entrada e saэda nуo foram parametrizado. Contate o Gestor do Controle.');

          foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO){
              switch ($objMdLitMapearParamSaidaDTO->getNumIdMdLitCampoIntegracao()){
                  case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['SEQUENCIAL']:
                      $objMdLitLancamento->setStrSequencial($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                      break;

                  case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['LINK_BOLETO']:
                      $objMdLitLancamento->setStrLinkBoleto($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                      break;

                  case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['COD_RECEITA']:
                      $objMdLitLancamento->setStrCodigoReceita($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                      break;

                  case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['NUMERO_INTERESSADO']:
                      if ($this->retornarExigeDadosComplementares($idTipoControle) == 'S') {
                          $objMdLitLancamento->setStrNumeroInteressado($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                      }
                      break;
              }
          }

          (new MdLitLancamentoRN)->alterar($objMdLitLancamento);
    }

    private function retornarExigeDadosComplementares($IdTipoControle)
    {
        $objMdLitTipoControleRN = new MdLitTipoControleRN();
        $objMdLitTipoControleDTO = new MdLitTipoControleDTO();
        $objMdLitTipoControleDTO->setNumIdTipoControleLitigioso($IdTipoControle);
        $objMdLitTipoControleDTO->retStrSinParamModalComplInteressado();
        $objMdLitTipoControle = $objMdLitTipoControleRN->consultar($objMdLitTipoControleDTO);

        return $objMdLitTipoControle->getStrSinParamModalComplInteressado();
    }
    
}
?>