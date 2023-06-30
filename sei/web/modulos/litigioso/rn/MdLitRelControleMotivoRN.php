<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/04/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelControleMotivoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitControle(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelControleMotivoDTO->getNumIdMdLitControle())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdMdLitMotivo(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelControleMotivoDTO->getNumIdMdLitMotivo())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$this->validarNumIdMdLitControle($objMdLitRelControleMotivoDTO, $objInfraException);
      //$this->validarNumIdMdLitMotivo($objMdLitRelControleMotivoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelControleMotivoBD->cadastrar($objMdLitRelControleMotivoDTO);

       //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function excluirRelacionamentoExistenteControlado(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO){

      $objMdLitRelControleMotivoDTO->retTodos();
      $arrMdLitRelControleMotivoDTO = $this->listar($objMdLitRelControleMotivoDTO);

      $numRegistro= count($arrMdLitRelControleMotivoDTO);

      if($numRegistro >0){
          $this->excluir($arrMdLitRelControleMotivoDTO);
      }
  }

  protected function alterarControlado(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelControleMotivoDTO->isSetNumIdMdLitControle()){
        $this->validarNumIdMdLitControle($objMdLitRelControleMotivoDTO, $objInfraException);
      }
      if ($objMdLitRelControleMotivoDTO->isSetNumIdMdLitMotivo()){
        $this->validarNumIdMdLitMotivo($objMdLitRelControleMotivoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      $objMdLitRelControleMotivoBD->alterar($objMdLitRelControleMotivoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelControleMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelControleMotivoDTO);$i++){
         $objMdLitRelControleMotivoBD->excluir($arrObjMdLitRelControleMotivoDTO[$i]);

      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelControleMotivoBD->consultar($objMdLitRelControleMotivoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelControleMotivoBD->listar($objMdLitRelControleMotivoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelControleMotivoBD->contar($objMdLitRelControleMotivoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function  verificarVinculoMotivoControlado($arrStrIds){

      $objInfraException     = new InfraException();
      $objMdLitRelControleMotivoDTO = new MdLitRelControleMotivoDTO();
      $objMdLitRelControleMotivoDTO->setNumIdMdLitMotivo($arrStrIds,InfraDTO::$OPER_IN);
      $objMdLitRelControleMotivoDTO->retTodos();
      $arrObjMdLitRelControleMotivo= $this->listar($objMdLitRelControleMotivoDTO);

      if(count($arrObjMdLitRelControleMotivo)>0){
          $objInfraException->adicionarValidacao('A exclusão do Motivo não é permitida, pois já existem registros vinculados no Controle Litigioso.');
      }
      $objInfraException->lancarValidacoes();

  }
/* 
  protected function desativarControlado($arrObjMdLitRelControleMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelControleMotivoDTO);$i++){
        $objMdLitRelControleMotivoBD->desativar($arrObjMdLitRelControleMotivoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelControleMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelControleMotivoDTO);$i++){
        $objMdLitRelControleMotivoBD->reativar($arrObjMdLitRelControleMotivoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdLitRelControleMotivoDTO $objMdLitRelControleMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_controle_motivo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelControleMotivoBD = new MdLitRelControleMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelControleMotivoBD->bloquear($objMdLitRelControleMotivoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
