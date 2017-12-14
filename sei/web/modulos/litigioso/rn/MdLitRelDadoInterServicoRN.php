<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterServicoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitDadoInteressado(MdLitRelDadoInterServicoDTO $objMdLitRelDadoInterServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterServicoDTO->getNumIdMdLitDadoInteressado())){
      $objInfraException->adicionarValidacao('Interessado não informado.');
    }
  }

  private function validarNumIdMdLitServico(MdLitRelDadoInterServicoDTO $objMdLitRelDadoInterServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterServicoDTO->getNumIdMdLitServico())){
      $objInfraException->adicionarValidacao('serviço não informado.');
    }
  }

  protected function cadastrarControlado(MdLitRelDadoInterServicoDTO $objMdLitRelDadoInterServicoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterServicoDTO, $objInfraException);
      $this->validarNumIdMdLitServico($objMdLitRelDadoInterServicoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterServicoBD->cadastrar($objMdLitRelDadoInterServicoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelDadoInterServicoDTO $objMdLitRelDadoInterServicoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelDadoInterServicoDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterServicoDTO, $objInfraException);
      }
      if ($objMdLitRelDadoInterServicoDTO->isSetNumIdMdLitServico()){
        $this->validarNumIdMdLitServico($objMdLitRelDadoInterServicoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      $objMdLitRelDadoInterServicoBD->alterar($objMdLitRelDadoInterServicoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelDadoInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterServicoDTO);$i++){
        $objMdLitRelDadoInterServicoBD->excluir($arrObjMdLitRelDadoInterServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelDadoInterServicoDTO $objMdLitRelDadoInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterServicoBD->consultar($objMdLitRelDadoInterServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelDadoInterServicoDTO $objMdLitRelDadoInterServicoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterServicoBD->listar($objMdLitRelDadoInterServicoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelDadoInterServicoDTO $objMdLitRelDadoInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterServicoBD->contar($objMdLitRelDadoInterServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelDadoInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterServicoDTO);$i++){
        $objMdLitRelDadoInterServicoBD->desativar($arrObjMdLitRelDadoInterServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelDadoInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterServicoDTO);$i++){
        $objMdLitRelDadoInterServicoBD->reativar($arrObjMdLitRelDadoInterServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelDadoInterServicoDTO $objMdLitRelDadoInterServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_servico_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterServicoBD = new MdLitRelDadoInterServicoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterServicoBD->bloquear($objMdLitRelDadoInterServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */
}
?>