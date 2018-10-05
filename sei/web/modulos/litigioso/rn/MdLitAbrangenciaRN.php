<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitAbrangenciaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdLitAbrangenciaDTO $objMdLitAbrangenciaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitAbrangenciaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('abrangencia não informada.');
    }else{
      $objMdLitAbrangenciaDTO->setStrNome(trim($objMdLitAbrangenciaDTO->getStrNome()));

      if (strlen($objMdLitAbrangenciaDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('abrangencia possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitAbrangenciaDTO $objMdLitAbrangenciaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdLitAbrangenciaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      $ret = $objMdLitAbrangenciaBD->cadastrar($objMdLitAbrangenciaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando abrangência.',$e);
    }
  }

  protected function alterarControlado(MdLitAbrangenciaDTO $objMdLitAbrangenciaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitAbrangenciaDTO->isSetStrNome()){
        $this->validarStrNome($objMdLitAbrangenciaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      $objMdLitAbrangenciaBD->alterar($objMdLitAbrangenciaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando abrangência.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitAbrangenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitAbrangenciaDTO);$i++){
        $objMdLitAbrangenciaBD->excluir($arrObjMdLitAbrangenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo abrangência.',$e);
    }
  }

  protected function consultarConectado(MdLitAbrangenciaDTO $objMdLitAbrangenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      $ret = $objMdLitAbrangenciaBD->consultar($objMdLitAbrangenciaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando abrangência.',$e);
    }
  }

  protected function listarConectado(MdLitAbrangenciaDTO $objMdLitAbrangenciaDTO) {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      $ret = $objMdLitAbrangenciaBD->listar($objMdLitAbrangenciaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando abrangências.',$e);
    }
  }

  protected function contarConectado(MdLitAbrangenciaDTO $objMdLitAbrangenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      $ret = $objMdLitAbrangenciaBD->contar($objMdLitAbrangenciaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando abrangências.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitAbrangenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitAbrangenciaDTO);$i++){
        $objMdLitAbrangenciaBD->desativar($arrObjMdLitAbrangenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando abrangência.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitAbrangenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitAbrangenciaDTO);$i++){
        $objMdLitAbrangenciaBD->reativar($arrObjMdLitAbrangenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando abrangência.',$e);
    }
  }

  protected function bloquearControlado(MdLitAbrangenciaDTO $objMdLitAbrangenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_abrangencia_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAbrangenciaBD = new MdLitAbrangenciaBD($this->getObjInfraIBanco());
      $ret = $objMdLitAbrangenciaBD->bloquear($objMdLitAbrangenciaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando abrangência.',$e);
    }
  }

 */
}
?>