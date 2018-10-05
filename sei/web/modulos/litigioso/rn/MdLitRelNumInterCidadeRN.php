<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterCidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdCidade(MdLitRelNumInterCidadeDTO $objMdLitRelNumInterCidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterCidadeDTO->getNumIdCidade())){
      $objInfraException->adicionarValidacao('cidade não informada.');
    }
  }

  private function validarNumIdMdLitNumeroInteressado(MdLitRelNumInterCidadeDTO $objMdLitRelNumInterCidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterCidadeDTO->getNumIdMdLitNumeroInteressado())){
      $objInfraException->adicionarValidacao('Número do interessado não informado.');
    }
  }

  protected function cadastrarControlado(MdLitRelNumInterCidadeDTO $objMdLitRelNumInterCidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdCidade($objMdLitRelNumInterCidadeDTO, $objInfraException);
      $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterCidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterCidadeBD->cadastrar($objMdLitRelNumInterCidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelNumInterCidadeDTO $objMdLitRelNumInterCidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelNumInterCidadeDTO->isSetNumIdCidade()){
        $this->validarNumIdCidade($objMdLitRelNumInterCidadeDTO, $objInfraException);
      }
      if ($objMdLitRelNumInterCidadeDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterCidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      $objMdLitRelNumInterCidadeBD->alterar($objMdLitRelNumInterCidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelNumInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterCidadeDTO);$i++){
        $objMdLitRelNumInterCidadeBD->excluir($arrObjMdLitRelNumInterCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelNumInterCidadeDTO $objMdLitRelNumInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterCidadeBD->consultar($objMdLitRelNumInterCidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelNumInterCidadeDTO $objMdLitRelNumInterCidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterCidadeBD->listar($objMdLitRelNumInterCidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelNumInterCidadeDTO $objMdLitRelNumInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterCidadeBD->contar($objMdLitRelNumInterCidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelNumInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterCidadeDTO);$i++){
        $objMdLitRelNumInterCidadeBD->desativar($arrObjMdLitRelNumInterCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelNumInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterCidadeDTO);$i++){
        $objMdLitRelNumInterCidadeBD->reativar($arrObjMdLitRelNumInterCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelNumInterCidadeDTO $objMdLitRelNumInterCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_cidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterCidadeBD = new MdLitRelNumInterCidadeBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterCidadeBD->bloquear($objMdLitRelNumInterCidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>