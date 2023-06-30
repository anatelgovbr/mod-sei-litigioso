<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitFuncionalidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdLitFuncionalidadeDTO $objMdLitFuncionalidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitFuncionalidadeDTO->getStrNome())){
      $objInfraException->adicionarValidacao('nome não informado.');
    }else{
      $objMdLitFuncionalidadeDTO->setStrNome(trim($objMdLitFuncionalidadeDTO->getStrNome()));

      if (strlen($objMdLitFuncionalidadeDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('nome possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitFuncionalidadeDTO $objMdLitFuncionalidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdLitFuncionalidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitFuncionalidadeBD->cadastrar($objMdLitFuncionalidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Funcionalidade.',$e);
    }
  }

  protected function alterarControlado(MdLitFuncionalidadeDTO $objMdLitFuncionalidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitFuncionalidadeDTO->isSetStrNome()){
        $this->validarStrNome($objMdLitFuncionalidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      $objMdLitFuncionalidadeBD->alterar($objMdLitFuncionalidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Funcionalidade.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitFuncionalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitFuncionalidadeDTO);$i++){
        $objMdLitFuncionalidadeBD->excluir($arrObjMdLitFuncionalidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Funcionalidade.',$e);
    }
  }

  protected function consultarConectado(MdLitFuncionalidadeDTO $objMdLitFuncionalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitFuncionalidadeBD->consultar($objMdLitFuncionalidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Funcionalidade.',$e);
    }
  }

  protected function listarConectado(MdLitFuncionalidadeDTO $objMdLitFuncionalidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitFuncionalidadeBD->listar($objMdLitFuncionalidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Funcionalidades.',$e);
    }
  }

  protected function contarConectado(MdLitFuncionalidadeDTO $objMdLitFuncionalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitFuncionalidadeBD->contar($objMdLitFuncionalidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Funcionalidades.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitFuncionalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitFuncionalidadeDTO);$i++){
        $objMdLitFuncionalidadeBD->desativar($arrObjMdLitFuncionalidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Funcionalidade.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitFuncionalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitFuncionalidadeDTO);$i++){
        $objMdLitFuncionalidadeBD->reativar($arrObjMdLitFuncionalidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Funcionalidade.',$e);
    }
  }

  protected function bloquearControlado(MdLitFuncionalidadeDTO $objMdLitFuncionalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_funcionalidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitFuncionalidadeBD = new MdLitFuncionalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitFuncionalidadeBD->bloquear($objMdLitFuncionalidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Funcionalidade.',$e);
    }
  }

 */
}
?>