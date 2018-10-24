<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 05/09/2018 - criado por ellyson.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRelDecisaoUfRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitDecisao(MdLitRelDecisaoUfDTO $objMdLitRelDecisaoUfDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDecisaoUfDTO->getNumIdMdLitDecisao())){
      $objInfraException->adicionarValidacao(' n�o informad.');
    }
  }

  private function validarNumIdUf(MdLitRelDecisaoUfDTO $objMdLitRelDecisaoUfDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDecisaoUfDTO->getNumIdUf())){
      $objInfraException->adicionarValidacao(' n�o informad.');
    }
  }

  protected function cadastrarControlado(MdLitRelDecisaoUfDTO $objMdLitRelDecisaoUfDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitDecisao($objMdLitRelDecisaoUfDTO, $objInfraException);
      $this->validarNumIdUf($objMdLitRelDecisaoUfDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisaoUfBD->cadastrar($objMdLitRelDecisaoUfDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Decis�o com estado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelDecisaoUfDTO $objMdLitRelDecisaoUfDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelDecisaoUfDTO->isSetNumIdMdLitDecisao()){
        $this->validarNumIdMdLitDecisao($objMdLitRelDecisaoUfDTO, $objInfraException);
      }
      if ($objMdLitRelDecisaoUfDTO->isSetNumIdUf()){
        $this->validarNumIdUf($objMdLitRelDecisaoUfDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      $objMdLitRelDecisaoUfBD->alterar($objMdLitRelDecisaoUfDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Decis�o com estado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelDecisaoUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDecisaoUfDTO);$i++){
        $objMdLitRelDecisaoUfBD->excluir($arrObjMdLitRelDecisaoUfDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Decis�o com estado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelDecisaoUfDTO $objMdLitRelDecisaoUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisaoUfBD->consultar($objMdLitRelDecisaoUfDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Decis�o com estado.',$e);
    }
  }

  protected function listarConectado(MdLitRelDecisaoUfDTO $objMdLitRelDecisaoUfDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisaoUfBD->listar($objMdLitRelDecisaoUfDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Decis�es com estados.',$e);
    }
  }

  protected function contarConectado(MdLitRelDecisaoUfDTO $objMdLitRelDecisaoUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisaoUfBD->contar($objMdLitRelDecisaoUfDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Decis�es com estados.',$e);
    }
  }

  protected function excluirPorDecisaoControlado($arrIdDecisao){

      $objMdLitRelDecisaoUfDTO = new MdLitRelDecisaoUfDTO();
      $objMdLitRelDecisaoUfDTO->retTodos(false);
      $objMdLitRelDecisaoUfDTO->setNumIdMdLitDecisao($arrIdDecisao, InfraDTO::$OPER_IN);

      $arrObjMdLitRelDecisaoUfDTO = $this->listar($objMdLitRelDecisaoUfDTO);

      if(count($arrObjMdLitRelDecisaoUfDTO)){
          $this->excluir($arrObjMdLitRelDecisaoUfDTO);
      }
  }

/* 
  protected function desativarControlado($arrObjMdLitRelDecisaoUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDecisaoUfDTO);$i++){
        $objMdLitRelDecisaoUfBD->desativar($arrObjMdLitRelDecisaoUfDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Decis�o com estado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelDecisaoUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDecisaoUfDTO);$i++){
        $objMdLitRelDecisaoUfBD->reativar($arrObjMdLitRelDecisaoUfDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Decis�o com estado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelDecisaoUfDTO $objMdLitRelDecisaoUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decisao_uf_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisaoUfBD = new MdLitRelDecisaoUfBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisaoUfBD->bloquear($objMdLitRelDecisaoUfDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Decis�o com estado.',$e);
    }
  }

 */
}
