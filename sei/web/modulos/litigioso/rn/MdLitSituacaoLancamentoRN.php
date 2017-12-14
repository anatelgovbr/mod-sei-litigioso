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

    public static $DEVEDOR = 0;
    public static $QUITADO = 1;
    public static $CANCELADO = 2;
    public static $PARCIAL = 3;
    public static $PAGO_A_MAIOR = 4;
    public static $RESTITUIDO = 7;
    public static $COMPENSADO = 8;
    public static $ESTORNADO = 9;
    public static $REPOSICIONADO = 10;
    public static $RESTITUIDO_BB = 11;
    public static $CONSOLIDADO_DEVEDOR = 12;
    public static $CONSOLIDADO_QUITADO = 13;
    public static $CHEQUE_DEVOLVIDO = 14;
    public static $REDIRECIONADO = 15;
    public static $SEM_MOVIMENTO = 16;
    public static $REPASSADO_PARA_AGU = 17;
    public static $PRESCRITO = 18;

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

  protected function cadastrarControlado(MdLitSituacaoLancamentoDTO $objMdLitSituacaoLancamentoDTO) {
    try{

      //Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_lancamento_cadastrar', __METHOD__, $objMdLitSituacaoLancamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdLitSituacaoLancamentoDTO, $objInfraException);
      $this->validarStrCorSituacao($objMdLitSituacaoLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

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
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

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

  public function verificaSituacaoExiste($idSituacao){
    $objMdLitSitLancamentoRN  = new MdLitSituacaoLancamentoRN();
    $objMdLitSitLancamentoDTO = new MdLitSituacaoLancamentoDTO();
    $objMdLitSitLancamentoDTO->setNumIdMdLitSituacaoLancamento($idSituacao);
    $objMdLitSitLancamentoDTO->retTodos();
    $countSit = $objMdLitSitLancamentoRN->contar($objMdLitSitLancamentoDTO);

    /*if($countSit <= 0){
        new InfraException('A Situa��o do Cancelamento n�o est� cadastrada. Contate o Administrador.');
    }*/

    return $countSit > 0;
  }
/* 
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