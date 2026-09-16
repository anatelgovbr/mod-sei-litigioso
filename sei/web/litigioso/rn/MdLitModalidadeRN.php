<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitModalidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdLitModalidadeDTO $objMdLitModalidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitModalidadeDTO->getStrNome())){
      $objInfraException->adicionarValidacao('modalidade não informada.');
    }else{
      $objMdLitModalidadeDTO->setStrNome(trim($objMdLitModalidadeDTO->getStrNome()));

      if (strlen($objMdLitModalidadeDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('modalidade possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitModalidadeDTO $objMdLitModalidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdLitModalidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitModalidadeBD->cadastrar($objMdLitModalidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Modalidade.',$e);
    }
  }

  protected function alterarControlado(MdLitModalidadeDTO $objMdLitModalidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitModalidadeDTO->isSetStrNome()){
        $this->validarStrNome($objMdLitModalidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      $objMdLitModalidadeBD->alterar($objMdLitModalidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Modalidade.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitModalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitModalidadeDTO);$i++){
        $objMdLitModalidadeBD->excluir($arrObjMdLitModalidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Modalidade.',$e);
    }
  }

  protected function consultarConectado(MdLitModalidadeDTO $objMdLitModalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitModalidadeBD->consultar($objMdLitModalidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Modalidade.',$e);
    }
  }

  protected function listarConectado(MdLitModalidadeDTO $objMdLitModalidadeDTO) {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitModalidadeBD->listar($objMdLitModalidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Modalidades.',$e);
    }
  }

  protected function contarConectado(MdLitModalidadeDTO $objMdLitModalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitModalidadeBD->contar($objMdLitModalidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Modalidades.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitModalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitModalidadeDTO);$i++){
        $objMdLitModalidadeBD->desativar($arrObjMdLitModalidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Modalidade.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitModalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitModalidadeDTO);$i++){
        $objMdLitModalidadeBD->reativar($arrObjMdLitModalidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Modalidade.',$e);
    }
  }

  protected function bloquearControlado(MdLitModalidadeDTO $objMdLitModalidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_modalidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitModalidadeBD = new MdLitModalidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitModalidadeBD->bloquear($objMdLitModalidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Modalidade.',$e);
    }
  }

 */
}
?>