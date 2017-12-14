<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterEstadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUf(MdLitRelDadoInterEstadoDTO $objMdLitRelDadoInterEstadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterEstadoDTO->getNumIdUf())){
      $objInfraException->adicionarValidacao('estado não informada.');
    }
  }

  private function validarNumIdMdLitDadoInteressado(MdLitRelDadoInterEstadoDTO $objMdLitRelDadoInterEstadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDadoInterEstadoDTO->getNumIdMdLitDadoInteressado())){
      $objInfraException->adicionarValidacao('interessado não informado.');
    }
  }

  protected function cadastrarControlado(MdLitRelDadoInterEstadoDTO $objMdLitRelDadoInterEstadoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_estado_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUf($objMdLitRelDadoInterEstadoDTO, $objInfraException);
      $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterEstadoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInteEstadoBD = new MdLitRelDadoInterEstadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInteEstadoBD->cadastrar($objMdLitRelDadoInterEstadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelDadoInterEstadoDTO $objMdLitRelDadoInterEstadoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_estado_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelDadoInterEstadoDTO->isSetNumIdUf()){
        $this->validarNumIdUf($objMdLitRelDadoInterEstadoDTO, $objInfraException);
      }
      if ($objMdLitRelDadoInterEstadoDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitDadoInteressado($objMdLitRelDadoInterEstadoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterEstadoBD = new MdLitRelDadoInterEstadoBD($this->getObjInfraIBanco());
        $objMdLitRelDadoInterEstadoBD->alterar($objMdLitRelDadoInterEstadoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelDadoInterEstadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_estado_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterEstadoBD = new MdLitRelDadoInterEstadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDadoInterEstadoDTO);$i++){
          $objMdLitRelDadoInterEstadoBD->excluir($arrObjMdLitRelDadoInterEstadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelDadoInterEstadoDTO $objMdLitRelDadoInterEstadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_estado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterEstadoBD = new MdLitRelDadoInterEstadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterEstadoBD->consultar($objMdLitRelDadoInterEstadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitRelDadoInterEstadoDTO $objMdLitRelDadoInterEstadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_estado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterEstadoBD = new MdLitRelDadoInterEstadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterEstadoBD->listar($objMdLitRelDadoInterEstadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitRelDadoInterEstadoDTO $objMdLitRelDadoInterEstadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_dado_inter_estado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDadoInterEstadoBD = new MdLitRelDadoInterEstadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDadoInterEstadoBD->contar($objMdLitRelDadoInterEstadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }
}
?>