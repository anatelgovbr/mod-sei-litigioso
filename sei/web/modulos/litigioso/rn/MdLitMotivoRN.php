<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/04/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMotivoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrDescricao(MdLitMotivoDTO $objMdLitMotivoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMotivoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('descrição não informada.');
    }else{
      $objMdLitMotivoDTO->setStrDescricao(trim($objMdLitMotivoDTO->getStrDescricao()));

      if (strlen($objMdLitMotivoDTO->getStrDescricao())>150){
        $objInfraException->adicionarValidacao('descrição possui tamanho superior a 150 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdLitMotivoDTO $objMdLitMotivoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMotivoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitMotivoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdLitMotivoDTO $objMdLitMotivoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrDescricao($objMdLitMotivoDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdLitMotivoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitMotivoBD->cadastrar($objMdLitMotivoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando motivo.',$e);
    }
  }

  protected function alterarControlado(MdLitMotivoDTO $objMdLitMotivoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitMotivoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdLitMotivoDTO, $objInfraException);
      }
      if ($objMdLitMotivoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdLitMotivoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      $objMdLitMotivoBD->alterar($objMdLitMotivoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando motivo.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMotivoDTO);$i++){
        $objMdLitMotivoBD->excluir($arrObjMdLitMotivoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo motivo.',$e);
    }
  }

  protected function consultarConectado(MdLitMotivoDTO $objMdLitMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitMotivoBD->consultar($objMdLitMotivoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando motivo.',$e);
    }
  }

  protected function listarConectado(MdLitMotivoDTO $objMdLitMotivoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitMotivoBD->listar($objMdLitMotivoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando motivos.',$e);
    }
  }

  protected function contarConectado(MdLitMotivoDTO $objMdLitMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitMotivoBD->contar($objMdLitMotivoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando motivos.',$e);
    }
  }

  protected function desativarControlado($arrObjMdLitMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMotivoDTO);$i++){
        $objMdLitMotivoBD->desativar($arrObjMdLitMotivoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando motivo.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMotivoDTO);$i++){
        $objMdLitMotivoBD->reativar($arrObjMdLitMotivoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando motivo.',$e);
    }
  }

  protected function bloquearControlado(MdLitMotivoDTO $objMdLitMotivoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_motivo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMotivoBD = new MdLitMotivoBD($this->getObjInfraIBanco());
      $ret = $objMdLitMotivoBD->bloquear($objMdLitMotivoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando motivo.',$e);
    }
  }


}
