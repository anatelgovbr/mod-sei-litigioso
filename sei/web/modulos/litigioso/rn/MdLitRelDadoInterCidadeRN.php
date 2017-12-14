<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterCidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdCidade(MdLitRelDadoInterCidadeDTO $objMdLitRelDadoInterCidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterCidadeDTO->getNumIdCidade())){
      $objInfraException->adicionarValidacao('cidade não informada.');
    }
  }

  private function validarNumIdMdLitDadoInteressado(MdLitRelDadoInterCidadeDTO $objMdLitRelDadoInterCidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterCidadeDTO->getNumIdMdLitDadoInteressado())){
      $objInfraException->adicionarValidacao('interessado não informado.');
    }
  }

  protected function cadastrarControlado(MdLitRelDadoInterCidadeDTO $objMdLitRelDadoInterCidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdCidade($objMdLitRelDadoInterCidadeDTO, $objInfraException);
      $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterCidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterCidadeBD->cadastrar($objMdLitRelDadoInterCidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelDadoInterCidadeDTO $objMdLitRelDadoInterCidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelDadoInterCidadeDTO->isSetNumIdCidade()){
        $this->validarNumIdCidade($objMdLitRelDadoInterCidadeDTO, $objInfraException);
      }
      if ($objMdLitRelDadoInterCidadeDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterCidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      $objMdLitRelDadoInterCidadeBD->alterar($objMdLitRelDadoInterCidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelDadoInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterCidadeDTO);$i++){
        $objMdLitRelDadoInterCidadeBD->excluir($arrObjMdLitRelDadoInterCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelDadoInterCidadeDTO $objMdLitRelDadoInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterCidadeBD->consultar($objMdLitRelDadoInterCidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelDadoInterCidadeDTO $objMdLitRelDadoInterCidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterCidadeBD->listar($objMdLitRelDadoInterCidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelDadoInterCidadeDTO $objMdLitRelDadoInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterCidadeBD->contar($objMdLitRelDadoInterCidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelDadoInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterCidadeDTO);$i++){
        $objMdLitRelDadoInterCidadeBD->desativar($arrObjMdLitRelDadoInterCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelDadoInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterCidadeDTO);$i++){
        $objMdLitRelDadoInterCidadeBD->reativar($arrObjMdLitRelDadoInterCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelDadoInterCidadeDTO $objMdLitRelDadoInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_cidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterCidadeBD = new MdLitRelDadoInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterCidadeBD->bloquear($objMdLitRelDadoInterCidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>