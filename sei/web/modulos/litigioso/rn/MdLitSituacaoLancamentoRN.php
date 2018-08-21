<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
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
      $objInfraException->adicionarValidacao('nome não informado.');
    }else{
      $objMdLitSituacaoLancamentoDTO->setStrNome(trim($objMdLitSituacaoLancamentoDTO->getStrNome()));

      if (strlen($objMdLitSituacaoLancamentoDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('nome possui tamanho superior a 100 caracteres.');
      }
    }
  }

    private function validarNumCodigo(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdLitSituacaoLancamentoDTO->getNumCodigo()) && is_numeric($objMdLitSituacaoLancamentoDTO->getNumCodigo())){
            $objInfraException->adicionarValidacao('Código não informado.');
        }else{
            $objMdLitSituacaoLancamentoDTO->setNumCodigo(trim($objMdLitSituacaoLancamentoDTO->getNumCodigo()));

            if (strlen($objMdLitSituacaoLancamentoDTO->getNumCodigo())>11){
                $objInfraException->adicionarValidacao('Código possui tamanho superior a 11 caracteres.');
            }

            if($this->verificarSituacaoLancamentoDuplicado($objMdLitSituacaoLancamentoDTO) > 0){
                $objInfraException->adicionarValidacao("Situação com o código '{$objMdLitSituacaoLancamentoDTO->getNumCodigo()}' já foi cadastrado!");
            }

        }
    }

    private function validarStrSinCancelamento(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdLitSituacaoLancamentoDTO->getStrSinCancelamento())){
            $objInfraException->adicionarValidacao('Selecione a situação de cancelamento não informado.');
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
                $objInfraException->adicionarValidacao("Situação com o código '{$objConsultaMdLitSituacaoLancamentoDTO->getNumCodigo()}' já foi cadastrado com a situação de cancelamento!");
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
          $objInfraException->adicionarValidacao('Possui lançamento vinculado com a situação do lançamento.');
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
      throw new InfraException('Erro cadastrando situação do lançamento.',$e);
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
      throw new InfraException('Erro alterando situação do lançamento.',$e);
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
      throw new InfraException('Erro excluindo situação do lançamento.',$e);
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
      throw new InfraException('Erro consultando situação do lançamento.',$e);
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
      throw new InfraException('Erro listando Situações do lançamento.',$e);
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
      throw new InfraException('Erro contando Situações do lançamento.',$e);
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
      throw new InfraException('Erro desativando situação do lançamento.',$e);
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
      throw new InfraException('Erro reativando situação do lançamento.',$e);
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
            throw new InfraException('Erro consultando situação do lançamento.',$e);
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
            throw new InfraException ('Erro excluindo Situação de lançamento.', $e);
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
      throw new InfraException('Erro bloqueando situação do lançamento.',$e);
    }
  }

 */
}
?>