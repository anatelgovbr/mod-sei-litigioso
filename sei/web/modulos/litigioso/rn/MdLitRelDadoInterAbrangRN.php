<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterAbrangRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitDadoInteressado(MdLitRelDadoInterAbrangDTO $objMdLitRelDadoInterAbrangDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterAbrangDTO->getNumIdMdLitDadoInteressado())){
      $objInfraException->adicionarValidacao('interessado não informado.');
    }
  }

  private function validarNumIdMdLitAbrangencia(MdLitRelDadoInterAbrangDTO $objMdLitRelDadoInterAbrangDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterAbrangDTO->getNumIdMdLitAbrangencia())){
      $objInfraException->adicionarValidacao('Abrengência não informada.');
    }
  }

  protected function cadastrarControlado(MdLitRelDadoInterAbrangDTO $objMdLitRelDadoInterAbrangDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterAbrangDTO, $objInfraException);
      $this->validarNumIdMdLitAbrangencia($objMdLitRelDadoInterAbrangDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterAbrangBD->cadastrar($objMdLitRelDadoInterAbrangDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelDadoInterAbrangDTO $objMdLitRelDadoInterAbrangDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelDadoInterAbrangDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterAbrangDTO, $objInfraException);
      }
      if ($objMdLitRelDadoInterAbrangDTO->isSetNumIdMdLitAbrangencia()){
        $this->validarNumIdMdLitAbrangencia($objMdLitRelDadoInterAbrangDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      $objMdLitRelDadoInterAbrangBD->alterar($objMdLitRelDadoInterAbrangDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelDadoInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterAbrangDTO);$i++){
        $objMdLitRelDadoInterAbrangBD->excluir($arrObjMdLitRelDadoInterAbrangDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelDadoInterAbrangDTO $objMdLitRelDadoInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterAbrangBD->consultar($objMdLitRelDadoInterAbrangDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelDadoInterAbrangDTO $objMdLitRelDadoInterAbrangDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterAbrangBD->listar($objMdLitRelDadoInterAbrangDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelDadoInterAbrangDTO $objMdLitRelDadoInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterAbrangBD->contar($objMdLitRelDadoInterAbrangDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelDadoInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterAbrangDTO);$i++){
        $objMdLitRelDadoInterAbrangBD->desativar($arrObjMdLitRelDadoInterAbrangDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelDadoInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterAbrangDTO);$i++){
        $objMdLitRelDadoInterAbrangBD->reativar($arrObjMdLitRelDadoInterAbrangDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelDadoInterAbrangDTO $objMdLitRelDadoInterAbrangDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_abrang_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterAbrangBD = new MdLitRelDadoInterAbrangBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterAbrangBD->bloquear($objMdLitRelDadoInterAbrangDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>