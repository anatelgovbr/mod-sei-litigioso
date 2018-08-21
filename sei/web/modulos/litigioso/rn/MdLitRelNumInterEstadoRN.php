<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterEstadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUf(MdLitRelNumInterEstadoDTO $objMdLitRelNumInterEstadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterEstadoDTO->getNumIdUf())){
      $objInfraException->adicionarValidacao('estado não informada.');
    }
  }

  private function validarNumIdMdLitNumeroInteressado(MdLitRelNumInterEstadoDTO $objMdLitRelNumInterEstadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelNumInterEstadoDTO->getNumIdMdLitNumeroInteressado())){
      $objInfraException->adicionarValidacao('interessado não informado.');
    }
  }

  protected function cadastrarControlado(MdLitRelNumInterEstadoDTO $objMdLitRelNumInterEstadoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_estado_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUf($objMdLitRelNumInterEstadoDTO, $objInfraException);
      $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterEstadoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInteEstadoBD = new MdLitRelNumInterEstadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInteEstadoBD->cadastrar($objMdLitRelNumInterEstadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelNumInterEstadoDTO $objMdLitRelNumInterEstadoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_estado_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelNumInterEstadoDTO->isSetNumIdUf()){
        $this->validarNumIdUf($objMdLitRelNumInterEstadoDTO, $objInfraException);
      }
      if ($objMdLitRelNumInterEstadoDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitNumeroInteressado($objMdLitRelNumInterEstadoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelNumInterEstadoBD = new MdLitRelNumInterEstadoBD($this->getObjInfraIBanco());
        $objMdLitRelNumInterEstadoBD->alterar($objMdLitRelNumInterEstadoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelNumInterEstadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_estado_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterEstadoBD = new MdLitRelNumInterEstadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelNumInterEstadoDTO);$i++){
          $objMdLitRelNumInterEstadoBD->excluir($arrObjMdLitRelNumInterEstadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelNumInterEstadoDTO $objMdLitRelNumInterEstadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_estado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterEstadoBD = new MdLitRelNumInterEstadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterEstadoBD->consultar($objMdLitRelNumInterEstadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelNumInterEstadoDTO $objMdLitRelNumInterEstadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_estado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterEstadoBD = new MdLitRelNumInterEstadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterEstadoBD->listar($objMdLitRelNumInterEstadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelNumInterEstadoDTO $objMdLitRelNumInterEstadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_num_inter_estado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelNumInterEstadoBD = new MdLitRelNumInterEstadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelNumInterEstadoBD->contar($objMdLitRelNumInterEstadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
}
?>