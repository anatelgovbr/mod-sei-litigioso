<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitLancamentoRN extends InfraRN {
    public static $SISTEMA_ORIGEM = 'SEI';

    public static $TIPO_LANCAMENTO_PRINCIPAL = 'P';
    public static $TIPO_LANCAMENTO_PRINCIPAL_REDUCAO = 'R';
    public static $TIPO_LANCAMENTO_MAJORADO = 'M';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitSituacaoLancamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getNumIdMdLitSituacaoLancamento())){
      $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento(null);
    }
  }

  private function validarStrTipoLancamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrTipoLancamento())){
      $objMdLitLancamentoDTO->setStrTipoLancamento(null);
    }else{
      $objMdLitLancamentoDTO->setStrTipoLancamento(trim($objMdLitLancamentoDTO->getStrTipoLancamento()));

      if (strlen($objMdLitLancamentoDTO->getStrTipoLancamento())>1){
        $objInfraException->adicionarValidacao('tipo do lan�amento possui tamanho superior a 1 caracteres.');
      }
    }
  }

  private function validarStrSequencial(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrSequencial())){
      $objInfraException->adicionarValidacao('sequencial n�o informado.');
    }else{
      $objMdLitLancamentoDTO->setStrSequencial(trim($objMdLitLancamentoDTO->getStrSequencial()));

      if (strlen($objMdLitLancamentoDTO->getStrSequencial())>45){
        $objInfraException->adicionarValidacao('sequencial possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarDtaDecisao(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaDecisao())){
      $objMdLitLancamentoDTO->setDtaDecisao(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaDecisao())){
        $objInfraException->adicionarValidacao('data da decis�o inv�lida.');
      }
    }
  }

  private function validarDtaIntimacao(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaIntimacao())){
      $objMdLitLancamentoDTO->setDtaIntimacao(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaIntimacao())){
        $objInfraException->adicionarValidacao('data da intima��o inv�lida.');
      }
    }
  }

  private function validarDtaVencimento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaVencimento())){
      $objMdLitLancamentoDTO->setDtaVencimento(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaVencimento())){
        $objInfraException->adicionarValidacao('data do vencimento inv�lida.');
      }
    }
  }

  private function validarDtaPrazoDefesa(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaPrazoDefesa())){
      $objMdLitLancamentoDTO->setDtaPrazoDefesa(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaPrazoDefesa())){
        $objInfraException->adicionarValidacao('data do prazo de defesa inv�lida.');
      }
    }
  }

  private function validarDtaUltimoPagamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaUltimoPagamento())){
      $objMdLitLancamentoDTO->setDtaUltimoPagamento(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaUltimoPagamento())){
        $objInfraException->adicionarValidacao('data do ultimo pagamento inv�lida.');
      }
    }
  }

  private function validarDblVlrLancamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDblVlrLancamento())){
      $objMdLitLancamentoDTO->setDblVlrLancamento(null);
    }
  }

  private function validarDblVlrDesconto(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDblVlrDesconto())){
      $objMdLitLancamentoDTO->setDblVlrDesconto(null);
    }
  }

  private function validarDblVlrPago(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDblVlrPago())){
      $objMdLitLancamentoDTO->setDblVlrPago(null);
    }
  }

  private function validarDblVlrSaldoDevedor(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDblVlrSaldoDevedor())){
      $objMdLitLancamentoDTO->setDblVlrSaldoDevedor(null);
    }
  }

  private function validarDthInclusao(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDthInclusao())){
      $objMdLitLancamentoDTO->setDthInclusao(null);
    }else{
      if (!InfraData::validarDataHora($objMdLitLancamentoDTO->getDthInclusao())){
        $objInfraException->adicionarValidacao('data de inclus�o inv�lida.');
      }
    }
  }

  private function validarStrLinkBoleto(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrLinkBoleto())){
      $objMdLitLancamentoDTO->setStrLinkBoleto(null);
    }else{
      $objMdLitLancamentoDTO->setStrLinkBoleto(trim($objMdLitLancamentoDTO->getStrLinkBoleto()));

      if (strlen($objMdLitLancamentoDTO->getStrLinkBoleto())>2083){
        $objInfraException->adicionarValidacao('link do boleto possui tamanho superior a 2083 caracteres.');
      }
    }
  }

  private function validarStrFistel(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrFistel())){
      $objMdLitLancamentoDTO->setStrFistel(null);
    }else{
      $objMdLitLancamentoDTO->setStrFistel(trim($objMdLitLancamentoDTO->getStrFistel()));

      if (strlen($objMdLitLancamentoDTO->getStrFistel())>999){
        $objInfraException->adicionarValidacao('fistel possui tamanho superior a 999 caracteres.');
      }
    }
  }

  private function validarStrSinConstituicaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva())){
      $objInfraException->adicionarValidacao('Sinalizador de constitui��o definitiva n�o informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva())){
        $objInfraException->adicionarValidacao('Sinalizador de constitui��o definitiva inv�lida.');
      }
    }
  }

  private function validarStrSinRenunciaRecorrer(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrSinRenunciaRecorrer())){
      $objInfraException->adicionarValidacao('Sinalizador de renunciar recorrer n�o informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitLancamentoDTO->getStrSinRenunciaRecorrer())){
        $objInfraException->adicionarValidacao('Sinalizador de renunciar recorrer inv�lido.');
      }
    }
  }

  private function validarDtaIntimacaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva())){
      $objMdLitLancamentoDTO->setDtaIntimacaoDefinitiva(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva())){
        $objInfraException->adicionarValidacao('data da intima��o definitiva inv�lida.');
      }
    }
  }

  private function validarDtaConstituicaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva())){
      $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva())){
        $objInfraException->adicionarValidacao('data da constitui��o definitiva inv�lida.');
      }
    }
  }

  private function validarStrJustificativa(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrJustificativa())){
      $objMdLitLancamentoDTO->setStrJustificativa(null);
    }else{
      $objMdLitLancamentoDTO->setStrJustificativa(trim($objMdLitLancamentoDTO->getStrJustificativa()));

      if (strlen($objMdLitLancamentoDTO->getStrJustificativa())>100){
        $objInfraException->adicionarValidacao('justificativa possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarNumIdUsuario(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('usuario n�o informado.');
    }
  }

  private function validarNumIdUnidade(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('unidade n�o informada.');
    }
  }

  protected function cadastrarControlado(MdLitLancamentoDTO $objMdLitLancamentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitSituacaoLancamento($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrTipoLancamento($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrSequencial($objMdLitLancamentoDTO, $objInfraException);
      $this->validarDtaVencimento($objMdLitLancamentoDTO, $objInfraException);
      $this->validarDtaPrazoDefesa($objMdLitLancamentoDTO, $objInfraException);

      $this->validarDblVlrLancamento($objMdLitLancamentoDTO, $objInfraException);
      $this->validarDthInclusao($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrLinkBoleto($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrFistel($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrSinConstituicaoDefinitiva($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrSinRenunciaRecorrer($objMdLitLancamentoDTO, $objInfraException);
//      $this->validarStrJustificativa($objMdLitLancamentoDTO, $objInfraException);
      $this->validarNumIdUsuario($objMdLitLancamentoDTO, $objInfraException);
      $this->validarNumIdUnidade($objMdLitLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->cadastrar($objMdLitLancamentoDTO);
      $this->prepararHistoricoLancamento($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando lan�amento.',$e);
    }
  }

  private function prepararHistoricoLancamento(MdLitLancamentoDTO $objMdLitLancamentoDTO){
      $objMdlitHistoricLancamentoRN  = new MdLitHistoricLancamentoRN();
      $objMdlitHistoricLancamentoDTO = $objMdlitHistoricLancamentoRN->copiarObjLancamentoObjHistorico($objMdLitLancamentoDTO);
      $objMdlitHistoricLancamentoRN->cadastrar($objMdlitHistoricLancamentoDTO);
  }

  protected function alterarControlado(MdLitLancamentoDTO $objMdLitLancamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitLancamentoDTO->isSetNumIdMdLitSituacaoLancamento()){
        $this->validarNumIdMdLitSituacaoLancamento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrTipoLancamento()){
        $this->validarStrTipoLancamento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrSequencial()){
        $this->validarStrSequencial($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaDecisao()){
        $this->validarDtaDecisao($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaIntimacao()){
        $this->validarDtaIntimacao($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaVencimento()){
        $this->validarDtaVencimento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaPrazoDefesa()){
        $this->validarDtaPrazoDefesa($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaUltimoPagamento()){
        $this->validarDtaUltimoPagamento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDblVlrLancamento()){
        $this->validarDblVlrLancamento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDblVlrDesconto()){
        $this->validarDblVlrDesconto($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDblVlrPago()){
        $this->validarDblVlrPago($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDblVlrSaldoDevedor()){
        $this->validarDblVlrSaldoDevedor($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDthInclusao()){
        $this->validarDthInclusao($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrLinkBoleto()){
        $this->validarStrLinkBoleto($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrFistel()){
        $this->validarStrFistel($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrSinConstituicaoDefinitiva()){
        $this->validarStrSinConstituicaoDefinitiva($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrSinRenunciaRecorrer()){
        $this->validarStrSinRenunciaRecorrer($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaIntimacaoDefinitiva()){
        $this->validarDtaIntimacaoDefinitiva($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaConstituicaoDefinitiva()){
        $this->validarDtaConstituicaoDefinitiva($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrJustificativa()){
        $this->validarStrJustificativa($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objMdLitLancamentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $objMdLitLancamentoBD->alterar($objMdLitLancamentoDTO);

        $this->prepararHistoricoLancamento($objMdLitLancamentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando lan�amento.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitLancamentoDTO);$i++){
        $objMdLitLancamentoBD->excluir($arrObjMdLitLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo lan�amento.',$e);
    }
  }

  protected function consultarConectado(MdLitLancamentoDTO $objMdLitLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->consultar($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando lan�amento.',$e);
    }
  }

  protected function listarConectado(MdLitLancamentoDTO $objMdLitLancamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->listar($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando lan�amentos.',$e);
    }
  }

  protected function contarConectado(MdLitLancamentoDTO $objMdLitLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->contar($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando lan�amentos.',$e);
    }
  }

    /**
     * @param $post
     * @return MdLitLancamentoDTO retorna o objeto se for o lan�amento de um novo cr�dito ao contrario n�o h� retorno
     */
    public function prepararLancamento($post){
        switch ($post['hdnIdMdLitFuncionalidade']){
            case MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO:
                $objMdLitLancamento = $this->_realizarLancamentoCredito($post);
                return $objMdLitLancamento;
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO:
                $objMdLitCancelarRN = new MdLitCancelaLancamentoRN();
                $objMdLitCancelarRN->realizarCancelamentoCredito($post);
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO:
                $this->realizarRetificarCredito($post);
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO:
                $objMdLitRecursoLancamentoRN = new MdLitRecursoLancamentoRN();
                $objMdLitRecursoLancamentoRN->suspenderLancamento($post);
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_DENEGAR_RECURSO:
                $objMdLitRecursoLancamentoRN = new MdLitRecursoLancamentoRN();
                $objMdLitRecursoLancamentoRN->denegarRecurso($post);
                break;


            case MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_RECURSO:
                $objMdLitRecursoLancamentoRN = new MdLitRecursoLancamentoRN();
                $objMdLitRecursoLancamentoRN->cancelarRecurso($post);
                break;
        }
    }


    public function retornaObjLancamento($idLancamento){
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitLancamentoDTO->retTodos();
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);

        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        return $objMdLitLancamentoDTO;
    }

    /*
     * O Obj infra exception deve ser tratado posteriormente para retornar as exce��es como alerta para o usu�rio.
     **/
    public function realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException){

        if(is_null($objMdLitIntegracaoDTO)){
            //$objInfraException->lancarValidacao('� necess�rio realizar a integra��o com a funcionalidade de Arrecada��o Lan�amento para Realizar o mesmo.');
            throw new InfraException('� necess�rio realizar a integra��o com a funcionalidade desejada. Favor entrar em contato com o administrador.');
        }

        if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO())){
            throw new InfraException('Os par�metros de entrada e sa�da n�o foram parametrizados. Contate o Gestor do Controle.');
        }

        if(InfraUtil::prepararDin($post['hdnVlCreditoNaoLancado']) == 0){
            return false;
        }

        //$objInfraException->lancarValidacao();
        return $objInfraException;
    }

    private function _realizarLancamentoCredito($post){
        $objInfraException      = new InfraException();
        $objMdLitLancamentoNovo = new MdLitLancamentoDTO();
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
      
        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');
        $objInfraException      = $this->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        $montarParametroEntrada = $this->_montarParametroEntradaLancamentoCredito($objMdLitIntegracaoDTO, $post, $objMdLitLancamentoNovo);

        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_LANCAMENTO);

        if($arrResultado) {
            $objMdLitLancamentoNovo = $this->_montarParametroSaidaLancamentoCredito($objMdLitLancamentoNovo, $arrResultado, $post['hdnIdMdLitFuncionalidade']);
            $this->_prepararObjLancamentoInclusao($objMdLitLancamentoNovo, $post);

            return $objMdLitLancamentoNovo;
        }
    }

    private function realizarRetificarCredito($post){
        $objInfraException      = new InfraException();
        $objMdLitLancamentoDTO  = new MdLitLancamentoDTO();
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();

        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');
        $objInfraException      = $this->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($post['selCreditosProcesso']);

        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        $montarParametroEntrada = $this->montarParametroEntradaRetificarCredito($objMdLitIntegracaoDTO, $post, $objMdLitLancamentoDTO);

        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_RETIF_LANCAMENTO);


        if($arrResultado) {
            $this->montarParametroSaidaRetificarCredito($objMdLitLancamentoDTO, $arrResultado, $post['hdnIdMdLitFuncionalidade']);
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);


            return $objMdLitLancamentoDTO;
        }

    }

    private function _prepararObjLancamentoInclusao($objMdLitLancamentoNovo, $post)
    {
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
        $objMdLitLancamentoDTO->retTodos(false);

        $objMdLitLancamentoDTO->setDblIdProcedimento($post['hdnIdProcedimento']);

        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento(MdLitSituacaoLancamentoRN::$CANCELADO, InfraDTO::$OPER_DIFERENTE);

        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

        $objMdLitLancamentoNovo->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdLitLancamentoNovo->setDthInclusao(InfraData::getStrDataHoraAtual());
        $objMdLitLancamentoNovo->setNumIdMdLitSituacaoLancamento(MdLitSituacaoLancamentoRN::$DEVEDOR);
        $objMdLitLancamentoNovo->setStrTipoLancamento(count($arrObjMdLitLancamentoDTO) > 0? self::$TIPO_LANCAMENTO_MAJORADO:self::$TIPO_LANCAMENTO_PRINCIPAL);
        $objMdLitLancamentoNovo->setDtaDecisao($post['txtDecisaoAplicacaoMulta'] ? $post['txtDecisaoAplicacaoMulta'] : InfraData::getStrDataAtual());
        $objMdLitLancamentoNovo->setDtaIntimacao($post['txtDtIntimacaoAplMulta']);
        $objMdLitLancamentoNovo->setDtaPrazoDefesa($post['txtDtDecursoPrazo']);
        $objMdLitLancamentoNovo->setStrSinRenunciaRecorrer('N');
        $objMdLitLancamentoNovo->setStrSinConstituicaoDefinitiva('N');
        $objMdLitLancamentoNovo->setDblVlrSaldoDevedor($objMdLitLancamentoNovo->getDblVlrLancamento());
        $objMdLitLancamentoNovo->setStrSinSuspenso('N');

        $objMdLitLancamentoNovo = $this->cadastrar($objMdLitLancamentoNovo);
    }


    private function _montarParametroEntradaLancamentoCredito($objMdLitIntegracaoDTO, $post, &$objMdLitLancamentoNovo){
        $montarParametroEntrada = array();
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['COD_RECEITA']:
                    $objMdLitMapeaParamValorDTO = new MdLitMapeaParamValorDTO();
                    $objMdLitMapeaParamValorDTO->retTodos(false);
                    $objMdLitMapeaParamValorDTO->setNumMaxRegistrosRetorno(1);
                    $objMdLitMapeaParamValorDTO->setNumIdMdLitMapearParamEntrada($objMdLitMapearParamEntradaDTO->getNumIdMdLitMapearParamEntrada());
                    $objMdLitMapeaParamValorDTO->setNumIdMdLitTipoControle($post['hdnIdTipoControle']);

                    $objMdLitMapeaParamValorRN = new MdLitMapeaParamValorRN();
                    $objMdLitMapeaParamValorDTO = $objMdLitMapeaParamValorRN->consultar($objMdLitMapeaParamValorDTO);
                    if(!$objMdLitMapeaParamValorDTO)
                        throw new InfraException('O codigo da receita n�o est� parametrizado. Contate o Gestor do Controle.');

                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitMapeaParamValorDTO->getStrValorDefault();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['DTA_VENCIMENTO']:

                    $dtVencimento = trim($post['txtDtVencimento']);
                    if(empty($dtVencimento)){
                        $dtVencimento = InfraData::calcularData(40, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE);
                        $post['txtDtVencimento'] = $dtVencimento;
                    }
                    $arrData = explode('/', $dtVencimento );
                    $dtVencimento = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtVencimento;
                    $objMdLitLancamentoNovo->setDtaVencimento($post['txtDtVencimento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['DTA_APLICACAO_SANCAO']:
                    $txtDecisaoAplicacaoMulta = empty($post['txtDecisaoAplicacaoMulta'])? InfraData::getStrDataAtual():$post['txtDecisaoAplicacaoMulta'];
                    $dtDecisaoAplicacaoMulta = trim($txtDecisaoAplicacaoMulta);
                    $arrData = explode('/', $dtDecisaoAplicacaoMulta );
                    $dtDecisaoAplicacaoMulta = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtDecisaoAplicacaoMulta;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['FISTEL']:
                    if(empty($post['hdnNumFistel'])){
                        $objMdLitDadosInteressadoRN = new MdLitDadoInteressadoRN();
                        $objMdLitDadoInteressadoDTO = $objMdLitDadosInteressadoRN->retornaObjDadoInteressadoPorFistel($post);
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitDadoInteressadoDTO->getStrNumero();
                    }else{
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnNumFistel'];
                    }

                    /**
                     * @todo no SIGEC possui uma valida��o aonde e obrigatorio o numero do servi�o
                     * foi criado uma demanda para evoluir a procedure, assim ele ter� que pegar pelo numero de fistel o servi�o no SITARWEB
                     * assim que a demanda for concluido retirar esse bloco de codigo paliativo
                     */
                    $objMdLitRelDadoInterServicoDTO = new MdLitRelDadoInterServicoDTO();
                    $objMdLitRelDadoInterServicoDTO->retTodos(true);

                    if(!empty($post['hdnNumFistel']))
                        $objMdLitRelDadoInterServicoDTO->setStrNumeroMdLitDadoInteressado($post['hdnNumFistel']);

                    if(!empty($post['selFistel']))
                        $objMdLitRelDadoInterServicoDTO->setNumIdMdLitDadoInteressado($post['selFistel']);


                    $objMdLitRelDadoInterServicoDTO->setNumMaxRegistrosRetorno(1);

                    $objMdLitRelDadoInterServicoRN = new MdLitRelDadoInterServicoRN();
                    $objMdLitRelDadoInterServicoDTO = $objMdLitRelDadoInterServicoRN->consultar($objMdLitRelDadoInterServicoDTO);
                    if(!$objMdLitRelDadoInterServicoDTO){
                        throw new InfraException('O servi�o n�o foi selecionado. Erro paliativo aguardando evolu��o do SIGEC');
                    }

                    $montarParametroEntrada['numeroServico'] = $objMdLitRelDadoInterServicoDTO->getStrCodigoMdLitServico();

                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['VALOR_RECEITA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = InfraUtil::prepararDin($post['hdnVlCreditoNaoLancado']);
                    $objMdLitLancamentoNovo->setDblVlrLancamento(str_replace('.', '', $post['hdnVlCreditoNaoLancado']));
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['JUSTIFICATIVA_LANCAMENTO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnJustificativaLancamento'];
                    $objMdLitLancamentoNovo->setStrJustificativa($post['hdnJustificativaLancamento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['NUM_PROCESSO']:
                    $objMdLitLancamentoNovo->setDblIdProcedimento($post['hdnIdProcedimento']);
                    $numProcesso = $this->retornaNumProcessoFormatado($post);
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $numProcesso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    $objMdLitLancamentoNovo->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = self::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['VALIDAR_MAJORACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = false;
                    break;

            }

        }

        return $montarParametroEntrada;
    }


    private function montarParametroEntradaRetificarCredito($objMdLitIntegracaoDTO, $post, MdLitLancamentoDTO $objMdLitLancamentoDTO){

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['COD_RECEITA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrCodigoReceita();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_VENCIMENTO']:
                    $objMdLitLancamentoDTO->setDtaVencimento($post['txtDtVencimento']);
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getDtaVencimento();
                    $arrData = explode('/', $objMdLitLancamentoDTO->getDtaVencimento() );
                    $dtaVencimento = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaVencimento;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_APLICACAO_SANCAO']:
                    $dtDecisaoAplicacaoMulta = trim($post['txtDecisaoAplicacaoMulta']);

                    $objMdLitLancamentoDTO->setDtaVencimento($dtDecisaoAplicacaoMulta);
                    $arrData = explode('/', $dtDecisaoAplicacaoMulta );
                    $dtDecisaoAplicacaoMulta = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtDecisaoAplicacaoMulta;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['FISTEL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrFistel();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['VALOR_TOTAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = InfraUtil::prepararDin($post['hdnVlTotalMulta']);
                    $objMdLitLancamentoDTO->setDblVlrLancamento(str_replace('.', '', $post['hdnVlTotalMulta']));
                    $objMdLitLancamentoDTO->setDblVlrSaldoDevedor(str_replace('.', '', $post['hdnVlTotalMulta']));
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['JUSTIFICATIVA_LANCAMENTO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnJustificativaLancamento'];
                    $objMdLitLancamentoDTO->setStrJustificativa($post['hdnJustificativaLancamento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['NUM_PROCESSO']:
                    $objMdLitLancamentoDTO->setDblIdProcedimento($post['hdnIdProcedimento']);
                    $numProcesso = $this->retornaNumProcessoFormatado($post);
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $numProcesso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    $objMdLitLancamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = self::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['REN�NCIA_RECURSO']:
                    $sinReducaoRenuncia = isset($post['chkReducaoRenuncia'])? 'S': 'N';
                    $sinHouveConstituicao = isset($post['chkHouveConstituicao'])? 'S': 'N';

                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $sinReducaoRenuncia;
                    $objMdLitLancamentoDTO->setStrSinRenunciaRecorrer($sinReducaoRenuncia);
                    $objMdLitLancamentoDTO->setStrSinConstituicaoDefinitiva($sinHouveConstituicao);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_CONSTITUICAO']:
                    if(isset($post['txtDtConstituicao']) && !empty($post['txtDtConstituicao'])){
                        $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva($post['txtDtConstituicao']);
                        $objMdLitLancamentoDTO->setDtaIntimacaoDefinitiva($post['txtDtIntimacaoConstituicao']);
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva() );
                        $dtConstituica = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtConstituica;
                    }
                    break;

            }

        }
        return $montarParametroEntrada;
    }

    private function montarParametroSaidaRetificarCredito(MdLitLancamentoDTO $objMdLitLancamento, $arrResultado, $idMdLitFuncionalidade){
        if($idMdLitFuncionalidade == MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO){
            $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
            $objMdLitIntegracaoDTO->retTodos(true);
            $objMdLitIntegracaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($idMdLitFuncionalidade);

            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultaMapeaEntradaSaida($objMdLitIntegracaoDTO);

            if(empty($arrResultado))
                return null;

            if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO()))
                throw new InfraException('Os par�metros de entrada e sa�da n�o foram parametrizado. Contate o Gestor do Controle.');

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

                    case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['FISTEL']:
                        $objMdLitLancamento->setStrFistel($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;
                }
            }

            return $objMdLitLancamento;
        }
    }

    public function retornaNumProcessoFormatado($post){
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo($post['hdnIdProcedimento']);
        $objProtocoloDTO->retTodos(false);
        $objProtocoloDTO->setNumMaxRegistrosRetorno(1);

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
        $numProcesso = substr(str_replace(array('.', '/', '-'), '',$objProtocoloDTO->getStrProtocoloFormatado()), 0, -2);

        return $numProcesso;
    }

    private function _montarParametroSaidaLancamentoCredito(MdLitLancamentoDTO $objMdLitLancamento, $arrResultado, $idMdLitFuncionalidade){
        if($idMdLitFuncionalidade == MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO){
            $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
            $objMdLitIntegracaoDTO->retTodos(true);
            $objMdLitIntegracaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($idMdLitFuncionalidade);

            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultaMapeaEntradaSaida($objMdLitIntegracaoDTO);

            if(empty($arrResultado))
                return null;

            if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO()))
                throw new InfraException('Os par�metros de entrada e sa�da n�o foram parametrizado. Contate o Gestor do Controle.');

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

                    case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['FISTEL']:
                        $objMdLitLancamento->setStrFistel($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;
                }
            }
            return $objMdLitLancamento;
        }
    }

    public function retornaObjLancAlteracaoConsultaLanc($post)
    {
        $idLancamento = array_key_exists('selCreditosProcesso', $post) && $post['selCreditosProcesso'] != '' ? trim($post['selCreditosProcesso']) : '';

        if ($idLancamento != '')
        {
            $objMdLitLancamentoRN  = new MdLitLancamentoRN();
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento);
            $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitLancamentoDTO->retTodos();
            $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

            return $objMdLitLancamentoDTO;
        }
    }


    /**
     * @param $idProcedimento
     * @param $idLancamento
     * @return MdLitLancamentoDTO|bool| Retorna o objeto de MdLitLancamentoDTO se a consulta de lan�amento ter certo, FALSE caso contr�rio.
     */
    public function atualizarLancamento($idProcedimento, $idLancamento)
    {
        $objMdLitConsultarLancRN = new MdLitConsultarLancamentoRN();
        $objMdLitIntegracaoRN     = new MdLitIntegracaoRN();
        $objMdLitLancamentoDTO      = false;

       $arrObjMdLitLancamentoDTOAntigo    = $this->_retornaObjLancamentoPorProcedimento($idProcedimento, $idLancamento);

        foreach ($arrObjMdLitLancamentoDTOAntigo as $objMdLitLancamentoDTOAntigo){
            if($objMdLitLancamentoDTOAntigo){
                $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);

                $post = array('selCreditosProcesso' => $objMdLitLancamentoDTOAntigo->getNumIdMdLitLancamento(),'numFistel' => $objMdLitLancamentoDTOAntigo->getStrFistel(), 'chkReducaoRenuncia' => $objMdLitLancamentoDTOAntigo->getStrSinRenunciaRecorrer());

                $objMdLitLancamentoDTO = $objMdLitConsultarLancRN->verificarAtualizarSituacaoLancamentoSIGEC($objMdLitIntegracaoDTO, $post);
            }
        }
        return $objMdLitLancamentoDTO;
    }

    private function _retornaObjLancamentoPorProcedimento($idProcedimento){
        $objMdLitLancamentoDTO = null;

        if (!empty($idProcedimento))
        {
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);

            $objMdLitLancamentoDTO->retTodos();
//            $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitLancamentoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitLancamentoDTO = $this->listar($objMdLitLancamentoDTO);
        }


        return $objMdLitLancamentoDTO;
    }

    protected function valorLancadoPorProcedimentoConectado($idProcedimento){
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento(MdLitSituacaoLancamentoRN::$CANCELADO, InfraDTO::$OPER_DIFERENTE);
        $totalCreditoLancado = 0;

        $arrObjMdLitLancamentoDTO = $this->listar($objMdLitLancamentoDTO);

        foreach ($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){

            //calculando o valor lan�ado e o n�o lan�ado e a multa aplicada
            $creditoLancado = InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento());
            $totalCreditoLancado = bcadd($totalCreditoLancado, $creditoLancado, 2);
        }

        return $totalCreditoLancado;
    }


}
?>