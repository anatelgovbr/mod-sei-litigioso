<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/04/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelTpControlMotiRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitTipoControle(MdLitRelTpControlMotiDTO $objMdLitRelTpControlMotiDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelTpControlMotiDTO->getNumIdMdLitTipoControle())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdMdLitMotivo(MdLitRelTpControlMotiDTO $objMdLitRelTpControlMotiDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelTpControlMotiDTO->getNumIdMdLitMotivo())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(MdLitRelTpControlMotiDTO $objMdLitRelTpControlMotiDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$this->validarNumIdMdLitTipoControle($objMdLitRelTpControlMotiDTO, $objInfraException);
      //$this->validarNumIdMdLitMotivo($objMdLitRelTpControlMotiDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpControlMotiBD->cadastrar($objMdLitRelTpControlMotiDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdLitRelTpControlMotiDTO $objMdLitRelTpControlMotiDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelTpControlMotiDTO->isSetNumIdMdLitTipoControle()){
        $this->validarNumIdMdLitTipoControle($objMdLitRelTpControlMotiDTO, $objInfraException);
      }
      if ($objMdLitRelTpControlMotiDTO->isSetNumIdMdLitMotivo()){
        $this->validarNumIdMdLitMotivo($objMdLitRelTpControlMotiDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      $objMdLitRelTpControlMotiBD->alterar($objMdLitRelTpControlMotiDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelTpControlMotiDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelTpControlMotiDTO);$i++){
        $objMdLitRelTpControlMotiBD->excluir($arrObjMdLitRelTpControlMotiDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdLitRelTpControlMotiDTO $objMdLitRelTpControlMotiDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpControlMotiBD->consultar($objMdLitRelTpControlMotiDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdLitRelTpControlMotiDTO $objMdLitRelTpControlMotiDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpControlMotiBD->listar($objMdLitRelTpControlMotiDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdLitRelTpControlMotiDTO $objMdLitRelTpControlMotiDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpControlMotiBD->contar($objMdLitRelTpControlMotiDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function verificarVinculoMotivoControlado($arrStrIds){

      $objInfraException= new InfraException();

      $mdLitRelTpControlMotiDTO = new MdLitRelTpControlMotiDTO();
      $mdLitRelTpControlMotiDTO->setNumIdMdLitMotivo($arrStrIds,InfraDTO::$OPER_IN);
      $mdLitRelTpControlMotiDTO->retTodos();
      $arrMdLitRelTpControlMotiDTO = $this->listarConectado($mdLitRelTpControlMotiDTO);

      if(count($arrMdLitRelTpControlMotiDTO)>0){
          $objInfraException->adicionarValidacao('A exclusão do Motivo não é permitida, pois já existem registros vinculados no Tipo de Controle Litigioso.');

      }
      $objInfraException->lancarValidacoes();

  }
/* 
  protected function desativarControlado($arrObjMdLitRelTpControlMotiDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelTpControlMotiDTO);$i++){
        $objMdLitRelTpControlMotiBD->desativar($arrObjMdLitRelTpControlMotiDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelTpControlMotiDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelTpControlMotiDTO);$i++){
        $objMdLitRelTpControlMotiBD->reativar($arrObjMdLitRelTpControlMotiDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdLitRelTpControlMotiDTO $objMdLitRelTpControlMotiDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_control_moti_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpControlMotiBD = new MdLitRelTpControlMotiBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpControlMotiBD->bloquear($objMdLitRelTpControlMotiDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
