<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelServicoAbrangenRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitServico(MdLitRelServicoAbrangenDTO $objMdLitRelServicoAbrangenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelServicoAbrangenDTO->getNumIdMdLitServico())){
      $objInfraException->adicionarValidacao('serviço não informado.');
    }
  }

  private function validarNumIdMdLitAbrangencia(MdLitRelServicoAbrangenDTO $objMdLitRelServicoAbrangenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelServicoAbrangenDTO->getNumIdMdLitAbrangencia())){
      $objInfraException->adicionarValidacao('abrangência não informada.');
    }
  }

  protected function cadastrarControlado(MdLitRelServicoAbrangenDTO $objMdLitRelServicoAbrangenDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitServico($objMdLitRelServicoAbrangenDTO, $objInfraException);
      $this->validarNumIdMdLitAbrangencia($objMdLitRelServicoAbrangenDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelServicoAbrangenBD->cadastrar($objMdLitRelServicoAbrangenDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando serviço de abrangência.',$e);
    }
  }

  protected function alterarControlado(MdLitRelServicoAbrangenDTO $objMdLitRelServicoAbrangenDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelServicoAbrangenDTO->isSetNumIdMdLitServico()){
        $this->validarNumIdMdLitServico($objMdLitRelServicoAbrangenDTO, $objInfraException);
      }
      if ($objMdLitRelServicoAbrangenDTO->isSetNumIdMdLitAbrangencia()){
        $this->validarNumIdMdLitAbrangencia($objMdLitRelServicoAbrangenDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      $objMdLitRelServicoAbrangenBD->alterar($objMdLitRelServicoAbrangenDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando serviço de abrangência.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelServicoAbrangenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelServicoAbrangenDTO);$i++){
        $objMdLitRelServicoAbrangenBD->excluir($arrObjMdLitRelServicoAbrangenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo serviço de abrangência.',$e);
    }
  }

  protected function consultarConectado(MdLitRelServicoAbrangenDTO $objMdLitRelServicoAbrangenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelServicoAbrangenBD->consultar($objMdLitRelServicoAbrangenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando serviço de abrangência.',$e);
    }
  }

  protected function listarConectado(MdLitRelServicoAbrangenDTO $objMdLitRelServicoAbrangenDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelServicoAbrangenBD->listar($objMdLitRelServicoAbrangenDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando serviços de abrangência.',$e);
    }
  }

  protected function contarConectado(MdLitRelServicoAbrangenDTO $objMdLitRelServicoAbrangenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelServicoAbrangenBD->contar($objMdLitRelServicoAbrangenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando serviços de abrangência.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelServicoAbrangenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelServicoAbrangenDTO);$i++){
        $objMdLitRelServicoAbrangenBD->desativar($arrObjMdLitRelServicoAbrangenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando serviço de abrangência.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelServicoAbrangenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelServicoAbrangenDTO);$i++){
        $objMdLitRelServicoAbrangenBD->reativar($arrObjMdLitRelServicoAbrangenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando serviço de abrangência.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelServicoAbrangenDTO $objMdLitRelServicoAbrangenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_abrangen_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelServicoAbrangenBD = new MdLitRelServicoAbrangenBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelServicoAbrangenBD->bloquear($objMdLitRelServicoAbrangenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando serviço de abrangência.',$e);
    }
  }

 */
}
?>