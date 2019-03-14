<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitCancelaLancamentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitLancamento(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitCancelaLancamentoDTO->getNumIdMdLitLancamento())){
      $objInfraException->adicionarValidacao('lançamento não informado.');
    }
  }

  private function validarStrMotivoCancelamento(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitCancelaLancamentoDTO->getStrMotivoCancelamento())){
      $objInfraException->adicionarValidacao('motivo não informado.');
    }else{
      $objMdLitCancelaLancamentoDTO->setStrMotivoCancelamento(trim($objMdLitCancelaLancamentoDTO->getStrMotivoCancelamento()));

      if (strlen($objMdLitCancelaLancamentoDTO->getStrMotivoCancelamento())>500){
        $objInfraException->adicionarValidacao('motivo possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarStrJustificativa(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitCancelaLancamentoDTO->getStrJustificativa())){
      $objInfraException->adicionarValidacao('justificativa não informada.');
    }else{
      $objMdLitCancelaLancamentoDTO->setStrJustificativa(trim($objMdLitCancelaLancamentoDTO->getStrJustificativa()));

      if (strlen($objMdLitCancelaLancamentoDTO->getStrJustificativa())>500){
        $objInfraException->adicionarValidacao('justificativa possui tamanho superior a 500 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitLancamento($objMdLitCancelaLancamentoDTO, $objInfraException);
      $this->validarStrMotivoCancelamento($objMdLitCancelaLancamentoDTO, $objInfraException);
      $this->validarStrJustificativa($objMdLitCancelaLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCancelaLancamentoBD->cadastrar($objMdLitCancelaLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando cancelamento do lançamento.',$e);
    }
  }

  protected function alterarControlado(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitCancelaLancamentoDTO->isSetNumIdMdLitLancamento()){
        $this->validarNumIdMdLitLancamento($objMdLitCancelaLancamentoDTO, $objInfraException);
      }
      if ($objMdLitCancelaLancamentoDTO->isSetStrMotivoCancelamento()){
        $this->validarStrMotivoCancelamento($objMdLitCancelaLancamentoDTO, $objInfraException);
      }
      if ($objMdLitCancelaLancamentoDTO->isSetStrJustificativa()){
        $this->validarStrJustificativa($objMdLitCancelaLancamentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
      $objMdLitCancelaLancamentoBD->alterar($objMdLitCancelaLancamentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando cancelamento do lançamento.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitCancelaLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitCancelaLancamentoDTO);$i++){
        $objMdLitCancelaLancamentoBD->excluir($arrObjMdLitCancelaLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo cancelamento do lançamento.',$e);
    }
  }

  protected function consultarConectado(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCancelaLancamentoBD->consultar($objMdLitCancelaLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando cancelamento do lançamento.',$e);
    }
  }

  protected function listarConectado(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCancelaLancamentoBD->listar($objMdLitCancelaLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando cancelamentos do lançamento.',$e);
    }
  }

  protected function contarConectado(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCancelaLancamentoBD->contar($objMdLitCancelaLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando cancelamentos do lançamento.',$e);
    }
  }

  public function montarMotivoCancelamento(){
      $arrMotivoCancelamento = array();
      $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
      $objMdLitIntegracaoDTO->retTodos(true);
      $objMdLitIntegracaoDTO->setNumMaxRegistrosRetorno(1);
      $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_LISTA_MOTIVO_CANCELAMENTO);

      $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
      $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultaMapeaEntradaSaida($objMdLitIntegracaoDTO);

      if(!$objMdLitIntegracaoDTO){
          throw new InfraException('Não possui Integração parametrizado. Favor entrar em contato com o administrador do módulo.');
      }

      try{
          $objMdlitSoapClient = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');

          $err = $objMdlitSoapClient->getError();
          if($err)
              throw new InfraException($err);

          $objMdlitSoapClient->soap_defencoding = 'UTF-8';
          $objMdlitSoapClient->decode_utf8 = false;

          //@todo retirar quanto verificar a configuração do wso2 da anatel
          $objMdlitSoapClient->forceEndpoint = 'http://sistemasnetds/services/financeiroService.financeiroServiceHttpsSoap11Endpoint';
          $arrResultado = $objMdlitSoapClient->call($objMdLitIntegracaoDTO->getStrOperacaWsdl(), array());

          $err = $objMdlitSoapClient->getError();

          if($err)
              throw new InfraException('Ocorreu erro ao conectar com a operação('.$objMdLitIntegracaoDTO->getStrOperacaWsdl().').'.$err);

      }catch (Exception $e){
          throw new InfraException('Ocorreu erro ao executar o serviço de lançamento. ', $e );
      }

      if(empty($arrResultado))
          return null;

      if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()))
          throw new InfraException('Os parâmetros saída não foram parametrizado. Contate o Gestor do Controle.');

      foreach ($arrResultado['return'] as $key => $resultado){
          foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO){
              switch ($objMdLitMapearParamSaidaDTO->getNumIdMdLitCampoIntegracao()){
                  case MdLitMapearParamSaidaRN::$ID_PARAM_LISTAR_MOTIVOS_LANCAMENTO['ID_MOTIVO']:
                      $arrMotivoCancelamento[$key]['id_motivo'] = $resultado[$objMdLitMapearParamSaidaDTO->getStrCampo()];
                      break;

                  case MdLitMapearParamSaidaRN::$ID_PARAM_LISTAR_MOTIVOS_LANCAMENTO['DESCRICAO']:
                      $arrMotivoCancelamento[$key]['descricao'] = utf8_decode($resultado[$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                      break;
              }
          }
      }
      return $arrMotivoCancelamento;

  }


    public function realizarCancelamentoCredito($post){
        $objInfraException        = new InfraException();
        $objMdLitLancamentoNovo   = new MdLitLancamentoDTO();
        
        //Rns
        $objMdLitConsultarLancRN  = new MdLitConsultarLancamentoRN();
        $objMdLitLancamentoRN     = new MdLitLancamentoRN();
        $objMdLitIntegracaoRN     = new MdLitIntegracaoRN();
        $objMdLitHistLancRN       = new MdLitHistoricLancamentoRN();
        
        $objMdLitIntegracaoDTO    = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitIntegracaoDTOSit = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);

        if(is_null($objMdLitIntegracaoDTOSit)){
            throw new InfraException('É necessário realizar a integração com a funcionalidade de Consultar Lançamento.');
        }

        if(empty($objMdLitIntegracaoDTOSit->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTOSit->getArrObjMdLitMapearParamEntradaDTO())){
            throw new InfraException('Os parâmetros de entrada e saída não foram parametrizados. Contate o Gestor do Controle.');
        }

        try {

            $objMdLitSoapClienteRN = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(), 'wsdl');

            $objInfraException = $objMdLitLancamentoRN->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);
            $montarParametroEntrada = $this->_montarParametroEntradaCancelamentoCredito($objMdLitIntegracaoDTO, $post);

            $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_CANC_LANCAMENTO);
            $sucesso = $objMdLitConsultarLancRN->verificarAtualizarSituacaoLancamento($objMdLitIntegracaoDTOSit, $post);

            if ($sucesso !== false) {
                $this->_prepararObjCancelaLancamento($post);
                $objMdLitHistLancRN->incluirHistoricoCancelaLancamento($post);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro realizando o cancelamento.', $e);
        }
    }

    private function _prepararObjCancelaLancamento($post){
        $idLancamento = array_key_exists('selCreditosProcesso', $post) ? $post['selCreditosProcesso'] : '';
        $motCancel    = array_key_exists('hdnTxtMotivoCancelamento', $post) ?  $post['hdnTxtMotivoCancelamento'] : '';
        $justCancel   = array_key_exists('hdnJustificativaCancelamento', $post) ?  $post['hdnJustificativaCancelamento'] : '';

        $objMdLitCancelaLancDTO = new MdLitCancelaLancamentoDTO();
        $objMdLitCancelaLancDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitCancelaLancDTO->setStrMotivoCancelamento($motCancel);
        $objMdLitCancelaLancDTO->setStrJustificativa($justCancel);

        $this->cadastrarControlado($objMdLitCancelaLancDTO);
    }

    private function _montarParametroEntradaCancelamentoCredito($objMdLitIntegracaoDTO, $post){
        $idLancamento = $post['selCreditosProcesso'];
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $montarParametroEntrada = array();
        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->retornaObjLancamento($idLancamento);
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO) {
            $idIntegracao = $objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao();
            switch ($idIntegracao) {

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_LANCAMENTO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_LANCAMENTO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_LANCAMENTO['ID_MOTIVO_CANCELAMENTO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnIdMotivoCancelamento'];
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_LANCAMENTO['NUM_PROCESSO']:
                    $numProcesso = $objMdLitLancamentoRN->retornaNumProcessoFormatado($post);
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $numProcesso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_LANCAMENTO['JUSTIFICATIVA_CANCELAMENTO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnJustificativaCancelamento'];
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_LANCAMENTO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_CANCELAR_LANCAMENTO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;
            }
        }

        return $montarParametroEntrada;

    }

    public function existeCancelamentoLancamento($idLancamento){
        $objMdLitCancelarLancDTO = new MdLitCancelaLancamentoDTO();
        $objMdLitCancelarLancDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitCancelarLancDTO->retNumIdMdLitCancelaLancamento();

        return $this->contar($objMdLitCancelarLancDTO) > 0;
    }


    /*
      protected function desativarControlado($arrObjMdLitCancelaLancamentoDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_desativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdLitCancelaLancamentoDTO);$i++){
            $objMdLitCancelaLancamentoBD->desativar($arrObjMdLitCancelaLancamentoDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro desativando cancelamento do lançamento.',$e);
        }
      }

      protected function reativarControlado($arrObjMdLitCancelaLancamentoDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_reativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjMdLitCancelaLancamentoDTO);$i++){
            $objMdLitCancelaLancamentoBD->reativar($arrObjMdLitCancelaLancamentoDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro reativando cancelamento do lançamento.',$e);
        }
      }

      protected function bloquearControlado(MdLitCancelaLancamentoDTO $objMdLitCancelaLancamentoDTO){
        try {

          //Valida Permissao
          SessaoSEI::getInstance()->validarPermissao('md_lit_cancela_lancamento_consultar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objMdLitCancelaLancamentoBD = new MdLitCancelaLancamentoBD($this->getObjInfraIBanco());
          $ret = $objMdLitCancelaLancamentoBD->bloquear($objMdLitCancelaLancamentoDTO);

          //Auditoria

          return $ret;
        }catch(Exception $e){
          throw new InfraException('Erro bloqueando cancelamento do lançamento.',$e);
        }
      }

     */
}
?>