<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/03/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitNomeFuncionalRN extends InfraRN {

    public static $CNPJ_CPF     = '1';
    public static $OUTORGA      = '2';
    public static $NUMERO       = '3';
    public static $SERVICO      = '4';
    public static $MODALIDADE   = '5';
    public static $TIPO_OUTORGA  = '6';
    public static $ESTADO       = '7';
    public static $CIDADE       = '8';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdLitNomeFuncionalDTO $objMdLitNomeFuncionalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitNomeFuncionalDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome funcional não informado.');
    }else{
      $objMdLitNomeFuncionalDTO->setStrNome(trim($objMdLitNomeFuncionalDTO->getStrNome()));

      if (strlen($objMdLitNomeFuncionalDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Nome funcional possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitNomeFuncionalDTO $objMdLitNomeFuncionalDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdLitNomeFuncionalDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      $ret = $objMdLitNomeFuncionalBD->cadastrar($objMdLitNomeFuncionalDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando nome funcional.',$e);
    }
  }

  protected function alterarControlado(MdLitNomeFuncionalDTO $objMdLitNomeFuncionalDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitNomeFuncionalDTO->isSetStrNome()){
        $this->validarStrNome($objMdLitNomeFuncionalDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      $objMdLitNomeFuncionalBD->alterar($objMdLitNomeFuncionalDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando nome funcional.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitNomeFuncionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitNomeFuncionalDTO);$i++){
        $objMdLitNomeFuncionalBD->excluir($arrObjMdLitNomeFuncionalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo nome funcional.',$e);
    }
  }

  protected function consultarConectado(MdLitNomeFuncionalDTO $objMdLitNomeFuncionalDTO){
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      $ret = $objMdLitNomeFuncionalBD->consultar($objMdLitNomeFuncionalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando nome funcional.',$e);
    }
  }

  protected function listarConectado(MdLitNomeFuncionalDTO $objMdLitNomeFuncionalDTO) {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      $ret = $objMdLitNomeFuncionalBD->listar($objMdLitNomeFuncionalDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando nomes funcionais.',$e);
    }
  }

  protected function contarConectado(MdLitNomeFuncionalDTO $objMdLitNomeFuncionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      $ret = $objMdLitNomeFuncionalBD->contar($objMdLitNomeFuncionalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando nomes funcionais.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitNomeFuncionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitNomeFuncionalDTO);$i++){
        $objMdLitNomeFuncionalBD->desativar($arrObjMdLitNomeFuncionalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando nome funcional.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitNomeFuncionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitNomeFuncionalDTO);$i++){
        $objMdLitNomeFuncionalBD->reativar($arrObjMdLitNomeFuncionalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando nome funcional.',$e);
    }
  }

  protected function bloquearControlado(MdLitNomeFuncionalDTO $objMdLitNomeFuncionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_nome_funcional_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNomeFuncionalBD = new MdLitNomeFuncionalBD($this->getObjInfraIBanco());
      $ret = $objMdLitNomeFuncionalBD->bloquear($objMdLitNomeFuncionalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando nome funcional.',$e);
    }
  }

 */
}
?>