<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/05/2019 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterTpOutorRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitNumeroInteressado(MdLitRelNumInterTpOutorDTO $objMdLitRelNumInterTpOutorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterTpOutorDTO->getNumIdMdLitNumeroInteressado())){
      $objInfraException->adicionarValidacao('interessado não informado.');
    }
  }

  private function validarNumIdMdLitAdmTipoOutor(MdLitRelNumInterTpOutorDTO $objMdLitRelNumInterTpOutorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterTpOutorDTO->getNumIdMdLitAdmTipoOutor())){
      $objInfraException->adicionarValidacao('Tipo de Outorga não informada.');
    }
  }

  protected function cadastrarControlado(MdLitRelNumInterTpOutorDTO $objMdLitRelNumInterTpOutorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_tp_outor_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterTpOutorDTO, $objInfraException);
      $this->validarNumIdMdLitAdmTipoOutor($objMdLitRelNumInterTpOutorDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterTpOutorBD->cadastrar($objMdLitRelNumInterTpOutorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelNumInterTpOutorDTO $objMdLitRelNumInterTpOutorDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_tp_outor_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelNumInterTpOutorDTO->isSetNumIdMdLitNumeroInteressado()){
        $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterTpOutorDTO, $objInfraException);
      }
      if ($objMdLitRelNumInterTpOutorDTO->isSetNumIdMdLitAdmTipoOutor()){
        $this->validarNumIdMdLitAdmTipoOutor($objMdLitRelNumInterTpOutorDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      $objMdLitRelNumInterTpOutorBD->alterar($objMdLitRelNumInterTpOutorDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelNumInterTpOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_tp_outor_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterTpOutorDTO);$i++){
        $objMdLitRelNumInterTpOutorBD->excluir($arrObjMdLitRelNumInterTpOutorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelNumInterTpOutorDTO $objMdLitRelNumInterTpOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_tp_outor_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterTpOutorBD->consultar($objMdLitRelNumInterTpOutorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelNumInterTpOutorDTO $objMdLitRelNumInterTpOutorDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_tp_outor_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterTpOutorBD->listar($objMdLitRelNumInterTpOutorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelNumInterTpOutorDTO $objMdLitRelNumInterTpOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_tp_outor_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterTpOutorBD->contar($objMdLitRelNumInterTpOutorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/*
  protected function desativarControlado($arrObjMdLitRelNumInterTpOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterTpOutorDTO);$i++){
        $objMdLitRelNumInterTpOutorBD->desativar($arrObjMdLitRelNumInterTpOutorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelNumInterTpOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterTpOutorDTO);$i++){
        $objMdLitRelNumInterTpOutorBD->reativar($arrObjMdLitRelNumInterTpOutorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelNumInterTpOutorDTO $objMdLitRelNumInterTpOutorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_abrang_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterTpOutorBD = new MdLitRelNumInterTpOutorBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterTpOutorBD->bloquear($objMdLitRelNumInterTpOutorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>