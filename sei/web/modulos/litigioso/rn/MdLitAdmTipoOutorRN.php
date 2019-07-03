<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/05/2019 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitAdmTipoOutorRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdLitAdmTipoOutorDTO $objMdLitAdmTipoOutorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitAdmTipoOutorDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Tipo de Outorga não informada.');
    }else{
      $objMdLitAdmTipoOutorDTO->setStrNome(trim($objMdLitAdmTipoOutorDTO->getStrNome()));

      if (strlen($objMdLitAdmTipoOutorDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Tipo de Outorga possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitAdmTipoOutorDTO $objMdLitAdmTipoOutorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdLitAdmTipoOutorDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitAdmTipoOutorBD->cadastrar($objMdLitAdmTipoOutorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Outorga.',$e);
    }
  }

  protected function alterarControlado(MdLitAdmTipoOutorDTO $objMdLitAdmTipoOutorDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitAdmTipoOutorDTO->isSetStrNome()){
        $this->validarStrNome($objMdLitAdmTipoOutorDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      $objMdLitAdmTipoOutorBD->alterar($objMdLitAdmTipoOutorDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Outorga.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitAdmTipoOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitAdmTipoOutorDTO);$i++){
        $objMdLitAdmTipoOutorBD->excluir($arrObjMdLitAdmTipoOutorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Outorga.',$e);
    }
  }

  protected function consultarConectado(MdLitAdmTipoOutorDTO $objMdLitAdmTipoOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitAdmTipoOutorBD->consultar($objMdLitAdmTipoOutorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Outorga.',$e);
    }
  }

  protected function listarConectado(MdLitAdmTipoOutorDTO $objMdLitAdmTipoOutorDTO) {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitAdmTipoOutorBD->listar($objMdLitAdmTipoOutorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipo de Outorgas.',$e);
    }
  }

  protected function contarConectado(MdLitAdmTipoOutorDTO $objMdLitAdmTipoOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitAdmTipoOutorBD->contar($objMdLitAdmTipoOutorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tipo de Outorgas.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitAdmTipoOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitAdmTipoOutorDTO);$i++){
        $objMdLitAdmTipoOutorBD->desativar($arrObjMdLitAdmTipoOutorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tipo de Outorga.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitAdmTipoOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitAdmTipoOutorDTO);$i++){
        $objMdLitAdmTipoOutorBD->reativar($arrObjMdLitAdmTipoOutorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tipo de Outorga.',$e);
    }
  }

  protected function bloquearControlado(MdLitAdmTipoOutorDTO $objMdLitAdmTipoOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_adm_tipo_outor_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitAdmTipoOutorBD = new MdLitAdmTipoOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitAdmTipoOutorBD->bloquear($objMdLitAdmTipoOutorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Tipo de Outorga.',$e);
    }
  }

 */
}
?>