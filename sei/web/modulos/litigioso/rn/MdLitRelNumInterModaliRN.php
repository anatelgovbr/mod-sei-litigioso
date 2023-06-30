<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterModaliRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitNumeroInteressado(MdLitRelNumInterModaliDTO $objMdLitRelNumInterModaliDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterModaliDTO->getNumIdMdLitNumeroInteressado())){
      $objInfraException->adicionarValidacao('interessado não informado.');
    }
  }

  private function validarNumIdMdLitModalidade(MdLitRelNumInterModaliDTO $objMdLitRelNumInterModaliDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterModaliDTO->getNumIdMdLitModalidade())){
      $objInfraException->adicionarValidacao('modalidade não informada.');
    }
  }

  protected function cadastrarControlado(MdLitRelNumInterModaliDTO $objMdLitRelNumInterModaliDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_modali_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterModaliDTO, $objInfraException);
      $this->validarNumIdMdLitModalidade($objMdLitRelNumInterModaliDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterModaliBD->cadastrar($objMdLitRelNumInterModaliDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelNumInterModaliDTO $objMdLitRelNumInterModaliDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_modali_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelNumInterModaliDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitDadoInteressado($objMdLitRelNumInterModaliDTO, $objInfraException);
      }
      if ($objMdLitRelNumInterModaliDTO->isSetNumIdMdLitModalidade()){
        $this->validarNumIdMdLitModalidade($objMdLitRelNumInterModaliDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      $objMdLitRelNumInterModaliBD->alterar($objMdLitRelNumInterModaliDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelNumInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_modali_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterModaliDTO);$i++){
        $objMdLitRelNumInterModaliBD->excluir($arrObjMdLitRelNumInterModaliDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelNumInterModaliDTO $objMdLitRelNumInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('mmd_lit_rel_num_inter_modali_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterModaliBD->consultar($objMdLitRelNumInterModaliDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelNumInterModaliDTO $objMdLitRelNumInterModaliDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_modali_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterModaliBD->listar($objMdLitRelNumInterModaliDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelNumInterModaliDTO $objMdLitRelNumInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_modali_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterModaliBD->contar($objMdLitRelNumInterModaliDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelNumInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_modali_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterModaliDTO);$i++){
        $objMdLitRelNumInterModaliBD->desativar($arrObjMdLitRelNumInterModaliDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelNumInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_modali_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterModaliDTO);$i++){
        $objMdLitRelNumInterModaliBD->reativar($arrObjMdLitRelNumInterModaliDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelNumInterModaliDTO $objMdLitRelNumInterModaliDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_modali_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterModaliBD = new MdLitRelNumInterModaliBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterModaliBD->bloquear($objMdLitRelNumInterModaliDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>