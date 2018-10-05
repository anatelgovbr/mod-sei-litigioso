<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterAbrangRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitNumeroInteressado(MdLitRelNumInterAbrangDTO $objMdLitRelNumInterAbrangDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterAbrangDTO->getNumIdMdLitNumeroInteressado())){
      $objInfraException->adicionarValidacao('interessado não informado.');
    }
  }

  private function validarNumIdMdLitAbrangencia(MdLitRelNumInterAbrangDTO $objMdLitRelNumInterAbrangDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterAbrangDTO->getNumIdMdLitAbrangencia())){
      $objInfraException->adicionarValidacao('Abrengência não informada.');
    }
  }

  protected function cadastrarControlado(MdLitRelNumInterAbrangDTO $objMdLitRelNumInterAbrangDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterAbrangDTO, $objInfraException);
      $this->validarNumIdMdLitAbrangencia($objMdLitRelNumInterAbrangDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterAbrangBD->cadastrar($objMdLitRelNumInterAbrangDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelNumInterAbrangDTO $objMdLitRelNumInterAbrangDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelNumInterAbrangDTO->isSetNumIdMdLitNumeroInteressado()){
        $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterAbrangDTO, $objInfraException);
      }
      if ($objMdLitRelNumInterAbrangDTO->isSetNumIdMdLitAbrangencia()){
        $this->validarNumIdMdLitAbrangencia($objMdLitRelNumInterAbrangDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      $objMdLitRelNumInterAbrangBD->alterar($objMdLitRelNumInterAbrangDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelNumInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterAbrangDTO);$i++){
        $objMdLitRelNumInterAbrangBD->excluir($arrObjMdLitRelNumInterAbrangDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelNumInterAbrangDTO $objMdLitRelNumInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterAbrangBD->consultar($objMdLitRelNumInterAbrangDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelNumInterAbrangDTO $objMdLitRelNumInterAbrangDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterAbrangBD->listar($objMdLitRelNumInterAbrangDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelNumInterAbrangDTO $objMdLitRelNumInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterAbrangBD->contar($objMdLitRelNumInterAbrangDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelNumInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterAbrangDTO);$i++){
        $objMdLitRelNumInterAbrangBD->desativar($arrObjMdLitRelNumInterAbrangDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelNumInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterAbrangDTO);$i++){
        $objMdLitRelNumInterAbrangBD->reativar($arrObjMdLitRelNumInterAbrangDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelNumInterAbrangDTO $objMdLitRelNumInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterAbrangBD = new MdLitRelNumInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterAbrangBD->bloquear($objMdLitRelNumInterAbrangDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>