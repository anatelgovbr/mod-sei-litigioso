<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterModaliRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitDadoInteressado(MdLitRelDadoInterModaliDTO $objMdLitRelDadoInterModaliDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterModaliDTO->getNumIdMdLitDadoInteressado())){
      $objInfraException->adicionarValidacao('interessado não informado.');
    }
  }

  private function validarNumIdMdLitModalidade(MdLitRelDadoInterModaliDTO $objMdLitRelDadoInterModaliDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterModaliDTO->getNumIdMdLitModalidade())){
      $objInfraException->adicionarValidacao('modalidade não informada.');
    }
  }

  protected function cadastrarControlado(MdLitRelDadoInterModaliDTO $objMdLitRelDadoInterModaliDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterModaliDTO, $objInfraException);
      $this->validarNumIdMdLitModalidade($objMdLitRelDadoInterModaliDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterModaliBD->cadastrar($objMdLitRelDadoInterModaliDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelDadoInterModaliDTO $objMdLitRelDadoInterModaliDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelDadoInterModaliDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterModaliDTO, $objInfraException);
      }
      if ($objMdLitRelDadoInterModaliDTO->isSetNumIdMdLitModalidade()){
        $this->validarNumIdMdLitModalidade($objMdLitRelDadoInterModaliDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      $objMdLitRelDadoInterModaliBD->alterar($objMdLitRelDadoInterModaliDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelDadoInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterModaliDTO);$i++){
        $objMdLitRelDadoInterModaliBD->excluir($arrObjMdLitRelDadoInterModaliDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelDadoInterModaliDTO $objMdLitRelDadoInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterModaliBD->consultar($objMdLitRelDadoInterModaliDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelDadoInterModaliDTO $objMdLitRelDadoInterModaliDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterModaliBD->listar($objMdLitRelDadoInterModaliDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelDadoInterModaliDTO $objMdLitRelDadoInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterModaliBD->contar($objMdLitRelDadoInterModaliDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelDadoInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterModaliDTO);$i++){
        $objMdLitRelDadoInterModaliBD->desativar($arrObjMdLitRelDadoInterModaliDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelDadoInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterModaliDTO);$i++){
        $objMdLitRelDadoInterModaliBD->reativar($arrObjMdLitRelDadoInterModaliDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelDadoInterModaliDTO $objMdLitRelDadoInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_modali_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterModaliBD = new MdLitRelDadoInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterModaliBD->bloquear($objMdLitRelDadoInterModaliDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>