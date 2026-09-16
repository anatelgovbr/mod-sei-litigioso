<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterServicoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitNumeroInteressado(MdLitRelNumInterServicoDTO $objMdLitRelNumInterServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterServicoDTO->getNumIdMdLitNumeroInteressado())){
      $objInfraException->adicionarValidacao('Interessado não informado.');
    }
  }

  private function validarNumIdMdLitServico(MdLitRelNumInterServicoDTO $objMdLitRelNumInterServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterServicoDTO->getNumIdMdLitServico())){
      $objInfraException->adicionarValidacao('serviço não informado.');
    }
  }

  protected function cadastrarControlado(MdLitRelNumInterServicoDTO $objMdLitRelNumInterServicoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterServicoDTO, $objInfraException);
      $this->validarNumIdMdLitServico($objMdLitRelNumInterServicoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterServicoBD->cadastrar($objMdLitRelNumInterServicoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelNumInterServicoDTO $objMdLitRelNumInterServicoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelNumInterServicoDTO->isSetNumIdMdLitNumeroInteressado()){
        $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterServicoDTO, $objInfraException);
      }
      if ($objMdLitRelNumInterServicoDTO->isSetNumIdMdLitServico()){
        $this->validarNumIdMdLitServico($objMdLitRelNumInterServicoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      $objMdLitRelNumInterServicoBD->alterar($objMdLitRelNumInterServicoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelNumInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterServicoDTO);$i++){
        $objMdLitRelNumInterServicoBD->excluir($arrObjMdLitRelNumInterServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelNumInterServicoDTO $objMdLitRelNumInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterServicoBD->consultar($objMdLitRelNumInterServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelNumInterServicoDTO $objMdLitRelNumInterServicoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterServicoBD->listar($objMdLitRelNumInterServicoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelNumInterServicoDTO $objMdLitRelNumInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterServicoBD->contar($objMdLitRelNumInterServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelNumInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterServicoDTO);$i++){
        $objMdLitRelNumInterServicoBD->desativar($arrObjMdLitRelNumInterServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelNumInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterServicoDTO);$i++){
        $objMdLitRelNumInterServicoBD->reativar($arrObjMdLitRelNumInterServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelNumInterServicoDTO $objMdLitRelNumInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_servico_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterServicoBD = new MdLitRelNumInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterServicoBD->bloquear($objMdLitRelNumInterServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>