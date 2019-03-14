<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitHistoricLancamentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitSituacaoLancamento(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getNumIdMdLitSituacaoLancamento())){
      $objMdLitHistoricLancamentoDTO->setNumIdMdLitSituacaoLancamento(null);
    }
  }

  private function validarNumIdMdLitLancamento(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getNumIdMdLitLancamento())){
      $objInfraException->adicionarValidacao('lançamento não informado.');
    }
  }

  private function validarStrTipoLancamento(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getStrTipoLancamento())){
      $objMdLitHistoricLancamentoDTO->setStrTipoLancamento(null);
    }else{
      $objMdLitHistoricLancamentoDTO->setStrTipoLancamento(trim($objMdLitHistoricLancamentoDTO->getStrTipoLancamento()));

      if (strlen($objMdLitHistoricLancamentoDTO->getStrTipoLancamento())>1){
        $objInfraException->adicionarValidacao('tipo do lançamento possui tamanho superior a 1 caracteres.');
      }
    }
  }

  private function validarStrSequencial(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getStrSequencial())){
      $objInfraException->adicionarValidacao('sequencial não informado.');
    }else{
      $objMdLitHistoricLancamentoDTO->setStrSequencial(trim($objMdLitHistoricLancamentoDTO->getStrSequencial()));

      if (strlen($objMdLitHistoricLancamentoDTO->getStrSequencial())>45){
        $objInfraException->adicionarValidacao('sequencial possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarDtaDecisao(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDtaDecisao())){
      $objMdLitHistoricLancamentoDTO->setDtaDecisao(null);
    }else{
      if (!InfraData::validarData($objMdLitHistoricLancamentoDTO->getDtaDecisao())){
        $objInfraException->adicionarValidacao('data da decisão inválida.');
      }
    }
  }

  private function validarDtaIntimacao(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDtaIntimacao())){
      $objMdLitHistoricLancamentoDTO->setDtaIntimacao(null);
    }else{
      if (!InfraData::validarData($objMdLitHistoricLancamentoDTO->getDtaIntimacao())){
        $objInfraException->adicionarValidacao('data da intimação inválida.');
      }
    }
  }

  private function validarDtaVencimento(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDtaVencimento())){
      $objMdLitHistoricLancamentoDTO->setDtaVencimento(null);
    }else{
      if (!InfraData::validarData($objMdLitHistoricLancamentoDTO->getDtaVencimento())){
        $objInfraException->adicionarValidacao('data do vencimento inválida.');
      }
    }
  }

  private function validarDtaPrazoDefesa(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDtaPrazoDefesa())){
      $objMdLitHistoricLancamentoDTO->setDtaPrazoDefesa(null);
    }else{
      if (!InfraData::validarData($objMdLitHistoricLancamentoDTO->getDtaPrazoDefesa())){
        $objInfraException->adicionarValidacao('data do prazo de defesa inválida.');
      }
    }
  }

  private function validarDtaUltimoPagamento(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDtaUltimoPagamento())){
      $objMdLitHistoricLancamentoDTO->setDtaUltimoPagamento(null);
    }else{
      if (!InfraData::validarData($objMdLitHistoricLancamentoDTO->getDtaUltimoPagamento())){
        $objInfraException->adicionarValidacao('data do ultimo pagamento inválida.');
      }
    }
  }

  private function validarDblVlrLancamento(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDblVlrLancamento())){
      $objMdLitHistoricLancamentoDTO->setDblVlrLancamento(null);
    }
  }

  private function validarDblVlrDesconto(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDblVlrDesconto())){
      $objMdLitHistoricLancamentoDTO->setDblVlrDesconto(null);
    }
  }

  private function validarDblVlrPago(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDblVlrPago())){
      $objMdLitHistoricLancamentoDTO->setDblVlrPago(null);
    }
  }

  private function validarDblVlrSaldoDevedor(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDblVlrSaldoDevedor())){
      $objMdLitHistoricLancamentoDTO->setDblVlrSaldoDevedor(null);
    }
  }

  private function validarDthInclusao(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDthInclusao())){
      $objMdLitHistoricLancamentoDTO->setDthInclusao(null);
    }else{
      if (!InfraData::validarDataHora($objMdLitHistoricLancamentoDTO->getDthInclusao())){
        $objInfraException->adicionarValidacao('data de inclusão inválida.');
      }
    }
  }

  private function validarStrLinkBoleto(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getStrLinkBoleto())){
      $objMdLitHistoricLancamentoDTO->setStrLinkBoleto(null);
    }else{
      $objMdLitHistoricLancamentoDTO->setStrLinkBoleto(trim($objMdLitHistoricLancamentoDTO->getStrLinkBoleto()));

      if (strlen($objMdLitHistoricLancamentoDTO->getStrLinkBoleto())>2083){
        $objInfraException->adicionarValidacao('link de boleto possui tamanho superior a 2083 caracteres.');
      }
    }
  }

  private function validarStrNumeroInteressado(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getStrNumeroInteressado())){
      $objMdLitHistoricLancamentoDTO->setStrNumeroInteressado(null);
    }else{
      $objMdLitHistoricLancamentoDTO->setStrNumeroInteressado(trim($objMdLitHistoricLancamentoDTO->getStrNumeroInteressado()));

      if (strlen($objMdLitHistoricLancamentoDTO->getStrNumeroInteressado())>999){
        $objInfraException->adicionarValidacao('O número de complemento do interessado possui tamanho superior a 999 caracteres.');
      }
    }
  }

  private function validarStrSinConstituicaoDefinitiva(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getStrSinConstituicaoDefinitiva())){
      $objInfraException->adicionarValidacao('Sinalizador de constituição definitiva não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitHistoricLancamentoDTO->getStrSinConstituicaoDefinitiva())){
        $objInfraException->adicionarValidacao('Sinalizador de constituição definitiva inválida.');
      }
    }
  }

  private function validarStrSinRenunciaRecorrer(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getStrSinRenunciaRecorrer())){
      $objInfraException->adicionarValidacao('Sinalizador de renuncia do recorrer não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitHistoricLancamentoDTO->getStrSinRenunciaRecorrer())){
        $objInfraException->adicionarValidacao('Sinalizador de renuncia do recorrer inválida.');
      }
    }
  }

  private function validarDtaIntimacaoDefinitiva(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDtaIntimacaoDefinitiva())){
      $objMdLitHistoricLancamentoDTO->setDtaIntimacaoDefinitiva(null);
    }else{
      if (!InfraData::validarData($objMdLitHistoricLancamentoDTO->getDtaIntimacaoDefinitiva())){
        $objInfraException->adicionarValidacao('data de intimação definitiva inválida.');
      }
    }
  }

  private function validarDtaConstituicaoDefinitiva(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getDtaConstituicaoDefinitiva())){
      $objMdLitHistoricLancamentoDTO->setDtaConstituicaoDefinitiva(null);
    }else{
      if (!InfraData::validarData($objMdLitHistoricLancamentoDTO->getDtaConstituicaoDefinitiva())){
        $objInfraException->adicionarValidacao('data da constituição definitiva inválida.');
      }
    }
  }

  private function validarStrJustificativa(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitHistoricLancamentoDTO->getStrJustificativa())){
      $objMdLitHistoricLancamentoDTO->setStrJustificativa(null);
    }else{
      $objMdLitHistoricLancamentoDTO->setStrJustificativa(trim($objMdLitHistoricLancamentoDTO->getStrJustificativa()));

      if (strlen($objMdLitHistoricLancamentoDTO->getStrJustificativa())>250){
        $objInfraException->adicionarValidacao('A justificativo do histórico do Lançamento possui tamanho superior a 250 caracteres.');
      }
    }
  }

    /**
     * @param MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO
     *
     */
  protected function prepararCadastrarControlado(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO){
      $objMdLitLancamentoDTOAntigo = new MdLitHistoricLancamentoDTO();
      $objMdLitLancamentoDTOAntigo->retTodos(false);
      $objMdLitLancamentoDTOAntigo->setNumMaxRegistrosRetorno(1);
      $objMdLitLancamentoDTOAntigo->setNumIdMdLitLancamento($objMdLitHistoricLancamentoDTO->getNumIdMdLitLancamento());
      $objMdLitLancamentoDTOAntigo->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $objMdLitLancamentoDTOAntigo = $this->consultar($objMdLitLancamentoDTOAntigo);
        if($objMdLitLancamentoDTOAntigo === null || (
            $objMdLitHistoricLancamentoDTO->getNumIdMdLitSituacaoLancamento() != $objMdLitLancamentoDTOAntigo->getNumIdMdLitSituacaoLancamento()
            || $objMdLitHistoricLancamentoDTO->getDblVlrDesconto() != $objMdLitLancamentoDTOAntigo->getDblVlrDesconto()
            || $objMdLitHistoricLancamentoDTO->getDtaUltimoPagamento() != $objMdLitLancamentoDTOAntigo->getDtaUltimoPagamento()
            || $objMdLitHistoricLancamentoDTO->getDblVlrPago() != $objMdLitLancamentoDTOAntigo->getDblVlrPago()
            || $objMdLitHistoricLancamentoDTO->getDblVlrLancamento() != $objMdLitLancamentoDTOAntigo->getDblVlrLancamento())){
               return $this->cadastrar($objMdLitHistoricLancamentoDTO);
        }elseif ($objMdLitLancamentoDTOAntigo &&  $objMdLitHistoricLancamentoDTO->getStrLinkBoleto() != $objMdLitLancamentoDTOAntigo->getStrLinkBoleto()){
            $objMdLitLancamentoDTOAntigo->setStrLinkBoleto($objMdLitHistoricLancamentoDTO->getStrLinkBoleto());
            return $this->alterar($objMdLitLancamentoDTOAntigo);
        }

  }

  protected function cadastrarControlado(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO) {
    try{

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitSituacaoLancamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarNumIdMdLitLancamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarStrTipoLancamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarStrSequencial($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarDtaVencimento($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarDtaPrazoDefesa($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarDblVlrLancamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarDblVlrSaldoDevedor($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarDthInclusao($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarStrLinkBoleto($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarStrNumeroInteressado($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarStrSinRenunciaRecorrer($objMdLitHistoricLancamentoDTO, $objInfraException);
      $this->validarStrJustificativa($objMdLitHistoricLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitHistoricLancamentoBD->cadastrar($objMdLitHistoricLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando histórico do lançamento.',$e);
    }
  }

  protected function alterarControlado(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO){
    try {

      //Valida Permissao
//  	   SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitHistoricLancamentoDTO->isSetNumIdMdLitSituacaoLancamento()){
        $this->validarNumIdMdLitSituacaoLancamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetNumIdMdLitLancamento()){
        $this->validarNumIdMdLitLancamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetStrTipoLancamento()){
        $this->validarStrTipoLancamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetStrSequencial()){
        $this->validarStrSequencial($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDtaDecisao()){
        $this->validarDtaDecisao($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDtaIntimacao()){
        $this->validarDtaIntimacao($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDtaVencimento()){
        $this->validarDtaVencimento($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDtaPrazoDefesa()){
        $this->validarDtaPrazoDefesa($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDtaUltimoPagamento()){
        $this->validarDtaUltimoPagamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDblVlrLancamento()){
        $this->validarDblVlrLancamento($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDblVlrDesconto()){
        $this->validarDblVlrDesconto($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDblVlrPago()){
        $this->validarDblVlrPago($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDblVlrSaldoDevedor()){
        $this->validarDblVlrSaldoDevedor($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDthInclusao()){
        $this->validarDthInclusao($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetStrLinkBoleto()){
        $this->validarStrLinkBoleto($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetStrNumeroInteressado()){
        $this->validarStrNumeroInteressado($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetStrSinConstituicaoDefinitiva()){
        $this->validarStrSinConstituicaoDefinitiva($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetStrSinRenunciaRecorrer()){
        $this->validarStrSinRenunciaRecorrer($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDtaIntimacaoDefinitiva()){
        $this->validarDtaIntimacaoDefinitiva($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetDtaConstituicaoDefinitiva()){
        $this->validarDtaConstituicaoDefinitiva($objMdLitHistoricLancamentoDTO, $objInfraException);
      }
      if ($objMdLitHistoricLancamentoDTO->isSetStrJustificativa()){
        $this->validarStrJustificativa($objMdLitHistoricLancamentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      $objMdLitHistoricLancamentoBD->alterar($objMdLitHistoricLancamentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando histórico do lançamento.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitHistoricLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitHistoricLancamentoDTO);$i++){
        $objMdLitHistoricLancamentoBD->excluir($arrObjMdLitHistoricLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo histórico do lançamento.',$e);
    }
  }

  protected function consultarConectado(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitHistoricLancamentoBD->consultar($objMdLitHistoricLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando histórico do lançamento.',$e);
    }
  }

  protected function listarConectado(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitHistoricLancamentoBD->listar($objMdLitHistoricLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando históricos do lançamento.',$e);
    }
  }

  protected function contarConectado(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitHistoricLancamentoBD->contar($objMdLitHistoricLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando históricos do lançamento.',$e);
    }
  }

  protected function getDadosHistoricoLancamentoConectado($params){

    $objMdLitHistLancamentoDTO = new MdLitHistoricLancamentoDTO();
    $objMdLitHistLancamentoDTO->setDblIdProcedimento($params['idProcedimento']);
    $objMdLitHistLancamentoDTO->retNumIdMdLitHistoricLancamento();
    $objMdLitHistLancamentoDTO->retStrTipoLancamento();
    $objMdLitHistLancamentoDTO->retStrNomeSituacao();
    $objMdLitHistLancamentoDTO->retStrSequencial();
    $objMdLitHistLancamentoDTO->retStrNomeSituacao();
    $objMdLitHistLancamentoDTO->retDthInclusao();
    $objMdLitHistLancamentoDTO->retStrLinkBoleto();
    $objMdLitHistLancamentoDTO->retStrNomeUsuario();
    $objMdLitHistLancamentoDTO->retStrSiglaUsuario();
    $objMdLitHistLancamentoDTO->retStrSiglaUnidade();
    $objMdLitHistLancamentoDTO->retStrDescricaoUnidade();
    $objMdLitHistLancamentoDTO->retStrSinSuspenso();
    $objMdLitHistLancamentoDTO->retDblVlrDesconto();
    $objMdLitHistLancamentoDTO->retDblVlrPago();

      if(isset($params['idLancamento']) && !empty($params['idLancamento'])){
          $objMdLitHistLancamentoDTO->setNumIdMdLitLancamento($params['idLancamento']);
      }

    $arrObjs = $this->listar($objMdLitHistLancamentoDTO);
    return array($objMdLitHistLancamentoDTO, $arrObjs);
  }

  public function formatarTipoLancamento($siglaTpLancamento){
    $retorno = '';
    switch ($siglaTpLancamento){
      case 'P' : $retorno = 'Principal';
            break;
      case 'R' : $retorno = 'Principal - Redução';
            break;
      case 'M' : $retorno = 'Majorado';
            break;
      default : $retorno = '';
            break;
      
    }
    
    return $retorno;
  }

  public function formatarSequencialLink($seq, $link){
    $linkForm = trim($link);
    $aHref = '<a style="font-size:12.4px" class="ancoraPadraoAzul" target="_blank" href="'.$linkForm.'" > '.$seq.' </a>';
    return $aHref;
  }

  public function incluirHistoricoCancelaLancamento($post){
    $objMdLitLancamentoRN      = new MdLitLancamentoRN();
    $objMdLitLancamentoDTO     = $objMdLitLancamentoRN->retornaObjLancAlteracaoConsultaLanc($post);

    $objMdLitHistLancamentoDTO = $this->copiarObjLancamentoObjHistorico($objMdLitLancamentoDTO);
    $this->cadastrar($objMdLitHistLancamentoDTO);
  }

  public function copiarObjLancamentoObjHistorico($objMdLitLancamentoDTO){
    $objMdLitHistLancDTO = new MdLitHistoricLancamentoDTO();
    $objMdLitHistLancDTO->setNumIdMdLitLancamento($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
    $objMdLitHistLancDTO->setNumIdMdLitSituacaoLancamento($objMdLitLancamentoDTO->getNumIdMdLitSituacaoLancamento());
    $objMdLitHistLancDTO->setStrTipoLancamento($objMdLitLancamentoDTO->getStrTipoLancamento());
    $objMdLitHistLancDTO->setStrSequencial($objMdLitLancamentoDTO->getStrSequencial());
    $objMdLitHistLancDTO->setDtaDecisao($objMdLitLancamentoDTO->getDtaDecisao());
    $objMdLitHistLancDTO->setDtaIntimacao($objMdLitLancamentoDTO->getDtaIntimacao());
    $objMdLitHistLancDTO->setDtaVencimento($objMdLitLancamentoDTO->getDtaVencimento());
    $objMdLitHistLancDTO->setDtaPrazoDefesa($objMdLitLancamentoDTO->getDtaPrazoDefesa());
    $objMdLitHistLancDTO->setDblVlrLancamento($objMdLitLancamentoDTO->getDblVlrLancamento());
    $objMdLitHistLancDTO->setDblVlrSaldoDevedor($objMdLitLancamentoDTO->getDblVlrSaldoDevedor());
    $objMdLitHistLancDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
    $objMdLitHistLancDTO->setStrLinkBoleto($objMdLitLancamentoDTO->getStrLinkBoleto());
    $objMdLitHistLancDTO->setStrNumeroInteressado($objMdLitLancamentoDTO->getStrNumeroInteressado());
    $objMdLitHistLancDTO->setStrSinConstituicaoDefinitiva($objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva());
    $objMdLitHistLancDTO->setStrSinRenunciaRecorrer($objMdLitLancamentoDTO->getStrSinRenunciaRecorrer());
    $objMdLitHistLancDTO->setStrJustificativa($objMdLitLancamentoDTO->getStrJustificativa());
    $objMdLitHistLancDTO->setNumIdUsuario($objMdLitLancamentoDTO->getNumIdUsuario());
    $objMdLitHistLancDTO->setNumIdUnidade($objMdLitLancamentoDTO->getNumIdUnidade());
    $objMdLitHistLancDTO->setStrCodigoReceita($objMdLitLancamentoDTO->getStrCodigoReceita());
    $objMdLitHistLancDTO->setDblIdProcedimento($objMdLitLancamentoDTO->getDblIdProcedimento());
    $objMdLitHistLancDTO->setStrSinSuspenso($objMdLitLancamentoDTO->getStrSinSuspenso());
    $objMdLitHistLancDTO->setNumIdMdLitNumeroInteressado($objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado());

    if($objMdLitLancamentoDTO->isSetNumIdMdLitIntegracao()){
        $objMdLitHistLancDTO->setNumIdMdLitIntegracao($objMdLitLancamentoDTO->getNumIdMdLitIntegracao());
    }

    if($objMdLitLancamentoDTO->isSetNumIntegracaoIdMdLitFuncionalidade() && $objMdLitLancamentoDTO->getNumIntegracaoIdMdLitFuncionalidade() == MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO){
        $objMdLitHistLancDTO->setNumIdUsuario(null);
        $objMdLitHistLancDTO->setNumIdUnidade(null);
    }

    if($objMdLitLancamentoDTO->isSetDtaUltimoPagamento()){
      $objMdLitHistLancDTO->setDtaUltimoPagamento($objMdLitLancamentoDTO->getDtaUltimoPagamento());
    }

    if($objMdLitLancamentoDTO->isSetDblVlrDesconto()){
      $objMdLitHistLancDTO->setDblVlrDesconto($objMdLitLancamentoDTO->getDblVlrDesconto());
    }

    if($objMdLitLancamentoDTO->isSetDblVlrPago()){
      $objMdLitHistLancDTO->setDblVlrPago($objMdLitLancamentoDTO->getDblVlrPago());
    }

    if($objMdLitLancamentoDTO->isSetDtaIntimacaoDefinitiva()) {
      $objMdLitHistLancDTO->setDtaIntimacaoDefinitiva($objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva());
    }

    if($objMdLitLancamentoDTO->isSetDtaConstituicaoDefinitiva()){
      $objMdLitHistLancDTO->setDtaConstituicaoDefinitiva($objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva());
    }

    return $objMdLitHistLancDTO;
  }

/* 
  protected function desativarControlado($arrObjMdLitHistoricLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitHistoricLancamentoDTO);$i++){
        $objMdLitHistoricLancamentoBD->desativar($arrObjMdLitHistoricLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando histórico do lançamento.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitHistoricLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitHistoricLancamentoDTO);$i++){
        $objMdLitHistoricLancamentoBD->reativar($arrObjMdLitHistoricLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando histórico do lançamento.',$e);
    }
  }

  protected function bloquearControlado(MdLitHistoricLancamentoDTO $objMdLitHistoricLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_historic_lancamento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitHistoricLancamentoBD = new MdLitHistoricLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitHistoricLancamentoBD->bloquear($objMdLitHistoricLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando histórico do lançamento.',$e);
    }
  }

 */
}
?>