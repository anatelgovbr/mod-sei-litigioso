<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitSituacaoLancamentoRN extends InfraRN {
    public static $STA_ORIGEM_MANUAL     = 'M';
    public static $STA_ORIGEM_INTEGRACAO = 'I';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamentoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('nome n�o informado.');
    }else{
      $objMdLitSituacaoLancamentoDTO->setStrNome(trim($objMdLitSituacaoLancamentoDTO->getStrNome()));

      if (strlen($objMdLitSituacaoLancamentoDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('nome possui tamanho superior a 100 caracteres.');
      }
    }
  }

    private function validarNumCodigo(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdLitSituacaoLancamentoDTO->getNumCodigo()) && is_numeric($objMdLitSituacaoLancamentoDTO->getNumCodigo())){
            $objInfraException->adicionarValidacao('C�digo n�o informado.');
        }else{
            $objMdLitSituacaoLancamentoDTO->setNumCodigo(trim($objMdLitSituacaoLancamentoDTO->getNumCodigo()));

            if (strlen($objMdLitSituacaoLancamentoDTO->getNumCodigo())>11){
                $objInfraException->adicionarValidacao('C�digo possui tamanho superior a 11 caracteres.');
            }

            if($this->verificarSituacaoLancamentoDuplicado($objMdLitSituacaoLancamentoDTO) > 0){
                $objInfraException->adicionarValidacao("Situa��o com o c�digo '{$objMdLitSituacaoLancamentoDTO->getNumCodigo()}' j� foi cadastrado!");
            }

        }
    }

    private function validarStrSinCancelamento(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdLitSituacaoLancamentoDTO->getStrSinCancelamento())){
            $objInfraException->adicionarValidacao('Selecione a situa��o de cancelamento n�o informado.');
        }elseif($objMdLitSituacaoLancamentoDTO->getStrSinCancelamento() == 'S'){
            $objMdLitSituacaoLancamentoDTO->setStrSinCancelamento(trim($objMdLitSituacaoLancamentoDTO->getStrSinCancelamento()));

            $objConsultaMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
            $objConsultaMdLitSituacaoLancamentoDTO->retNumIdMdLitSituacaoLancamento();
            $objConsultaMdLitSituacaoLancamentoDTO->retNumCodigo();
            $objConsultaMdLitSituacaoLancamentoDTO->setStrSinCancelamento('S');
            $objConsultaMdLitSituacaoLancamentoDTO->setStrSinAtivoIntegracao('S');
            if ($objMdLitSituacaoLancamentoDTO->isSetNumIdMdLitSituacaoLancamento()) {
                $objConsultaMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), InfraDTO::$OPER_DIFERENTE);
            }
            $objConsultaMdLitSituacaoLancamentoDTO = $this->consultar($objConsultaMdLitSituacaoLancamentoDTO);

            if($objConsultaMdLitSituacaoLancamentoDTO){
                $objInfraException->adicionarValidacao("Situa��o com o c�digo '{$objConsultaMdLitSituacaoLancamentoDTO->getNumCodigo()}' j� foi cadastrado com a situa��o de cancelamento!");
            }

        }
    }

    private function verificarSituacaoLancamentoDuplicado($objMdLitSituacaoLancamentoDTO)
    {
        $objConsultaMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
        $objConsultaMdLitSituacaoLancamentoDTO->retNumIdMdLitSituacaoLancamento();
        $objConsultaMdLitSituacaoLancamentoDTO->setNumCodigo($objMdLitSituacaoLancamentoDTO->getNumCodigo());
        $objConsultaMdLitSituacaoLancamentoDTO->setStrSinAtivoIntegracao('S');
        if ($objMdLitSituacaoLancamentoDTO->isSetNumIdMdLitSituacaoLancamento()) {
            $objConsultaMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), InfraDTO::$OPER_DIFERENTE);
        }

        return $this->contar($objConsultaMdLitSituacaoLancamentoDTO);

    }

  private function validarStrCorSituacao(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamentoDTO->getStrCorSituacao())){
      $objMdLitSituacaoLancamentoDTO->setStrCorSituacao(null);
    }else{
      $objMdLitSituacaoLancamentoDTO->setStrCorSituacao(trim($objMdLitSituacaoLancamentoDTO->getStrCorSituacao()));

      if (strlen($objMdLitSituacaoLancamentoDTO->getStrCorSituacao())>50){
        $objInfraException->adicionarValidacao('cor possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarLancamentoComSituacao($arrObjMdLitSituacaoLancamentoDTO, InfraException $objInfraException){
      $arrIdSituacaoLancamento = InfraArray::converterArrInfraDTO($arrObjMdLitSituacaoLancamentoDTO, 'IdMdLitSituacaoLancamento');
      $objMdlitLancamentoDTO = new MdLitLancamentoDTO();
      $objMdlitLancamentoDTO->retTodos(false);
      $objMdlitLancamentoDTO->setNumIdMdLitSituacaoLancamento($arrIdSituacaoLancamento, InfraDTO::$OPER_IN);

      $objMdLitLancamentoRN = new MdLitLancamentoRN();
      $countObjMdlitLancamentoDTO = $objMdLitLancamentoRN->contar($objMdlitLancamentoDTO);

      if($countObjMdlitLancamentoDTO > 0){
          $objInfraException->adicionarValidacao('Possui lan�amento vinculado com a situa��o do lan�amento.');
      }
  }

  protected function cadastrarControlado(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO) {
    try{

      //Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_lancamento_cadastrar', __METHOD__, $objMdLitSituacaoLancamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdLitSituacaoLancamentoDTO, $objInfraException);
      $this->validarStrCorSituacao($objMdLitSituacaoLancamentoDTO, $objInfraException);
        $this->validarNumCodigo($objMdLitSituacaoLancamentoDTO, $objInfraException);
        $this->validarStrSinCancelamento($objMdLitSituacaoLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

        if(!$objMdLitSituacaoLancamentoDTO->isSetStrSinCancelamento()){
            $objMdLitSituacaoLancamentoDTO->setStrSinCancelamento('N');
        }

        if(!$objMdLitSituacaoLancamentoDTO->isSetStrSinAtivoIntegracao()){
            $objMdLitSituacaoLancamentoDTO->setStrSinAtivoIntegracao('S');
        }

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamentoBD->cadastrar($objMdLitSituacaoLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando situa��o do lan�amento.',$e);
    }
  }

  protected function alterarControlado(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancamento_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitSituacaoLancamentoDTO->isSetStrNome()){
        $this->validarStrNome($objMdLitSituacaoLancamentoDTO, $objInfraException);
      }
      if ($objMdLitSituacaoLancamentoDTO->isSetStrCorSituacao()){
        $this->validarStrCorSituacao($objMdLitSituacaoLancamentoDTO, $objInfraException);
      }
        if ($objMdLitSituacaoLancamentoDTO->isSetStrSinCancelamento()){
            $this->validarStrSinCancelamento($objMdLitSituacaoLancamentoDTO, $objInfraException);
        }

      $this->validarNumCodigo($objMdLitSituacaoLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      $objMdLitSituacaoLancamentoBD->alterar($objMdLitSituacaoLancamentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando situa��o do lan�amento.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitSituacaoLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancamento_excluir');

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarLancamentoComSituacao($arrObjMdLitSituacaoLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitSituacaoLancamentoDTO);$i++){
        $objMdLitSituacaoLancamentoBD->excluir($arrObjMdLitSituacaoLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo situa��o do lan�amento.',$e);
    }
  }

  protected function consultarConectado(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancamento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamentoBD->consultar($objMdLitSituacaoLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando situa��o do lan�amento.',$e);
    }
  }

  protected function listarConectado(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamentoBD->listar($objMdLitSituacaoLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Situa��es do lan�amento.',$e);
    }
  }

  protected function contarConectado(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamentoBD->contar($objMdLitSituacaoLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Situa��es do lan�amento.',$e);
    }
  }

  public function verificaSituacaoExiste($codigoSituacao){
    $objMdLitSitLancamentoDTO = new MdLitSituacaoLancamentoDTO();
    $objMdLitSitLancamentoDTO->setNumCodigo($codigoSituacao);
    $objMdLitSitLancamentoDTO->setStrSinAtivoIntegracao('S');
    $objMdLitSitLancamentoDTO->setNumMaxRegistrosRetorno(1);
    $objMdLitSitLancamentoDTO->retTodos();

    $objMdLitSitLancamentoRN  = new MdLitSituacaoLancamentoRN();
    $objMdLitSitLancamentoDTO = $objMdLitSitLancamentoRN->consultar($objMdLitSitLancamentoDTO);

    return $objMdLitSitLancamentoDTO;
  }

  protected function desativarControlado($arrObjMdLitSituacaoLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancamento_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitSituacaoLancamentoDTO);$i++){
        $objMdLitSituacaoLancamentoBD->desativar($arrObjMdLitSituacaoLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando situa��o do lan�amento.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitSituacaoLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancamento_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitSituacaoLancamentoDTO);$i++){
        $objMdLitSituacaoLancamentoBD->reativar($arrObjMdLitSituacaoLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando situa��o do lan�amento.',$e);
    }
  }
    protected function consultarSituacaoCancelamentoConectado(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO){
        try {
            $objMdLitSituacaoLancamentoDTO->setStrSinCancelamento('S');
            $objMdLitSituacaoLancamentoDTO->setStrSinAtivoIntegracao('S');
            $objMdLitSituacaoLancamentoDTO->setNumMaxRegistrosRetorno(1);

            $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
            $ret = $objMdLitSituacaoLancamentoBD->consultar($objMdLitSituacaoLancamentoDTO);

            //Auditoria

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro consultando situa��o do lan�amento.',$e);
        }
    }

    protected function excluirIntegracaoControlado($arrObjMdLitSituacaoLancamentoDTO)
    {
        try {
            $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD ($this->getObjInfraIBanco());

            for ($i = 0; $i < count($arrObjMdLitSituacaoLancamentoDTO); $i++) {
                $arrObjMdLitSituacaoLancamentoDTO[$i]->setStrSinAtivoIntegracao('N');
                $objMdLitSituacaoLancamentoBD->alterar($arrObjMdLitSituacaoLancamentoDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException ('Erro excluindo Situa��o de lan�amento.', $e);
        }
    }

/*
  protected function bloquearControlado(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancamento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamentoBD = new MdLitSituacaoLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamentoBD->bloquear($objMdLitSituacaoLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando situa��o do lan�amento.',$e);
    }
  }

 */
}
?>