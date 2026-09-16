<?
/**
* ANATEL
*
* 06/02/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelDispositivoNormativoRevogadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitDispositivoNormativo(MdLitRelDispositivoNormativoRevogadoDTO $objMdLitRelDispositivoNormativoRevogadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDispositivoNormativoRevogadoDTO->getNumIdMdLitDispositivoNormativo())){
      $objInfraException->adicionarValidacao('dispositivo normativo não informado.');
    }
  }

  private function validarNumIdMdLitDispositivoNormativoRevogado(MdLitRelDispositivoNormativoRevogadoDTO $objMdLitRelDispositivoNormativoRevogadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDispositivoNormativoRevogadoDTO->getNumIdMdLitDispositivoNormativoRevogado())){
      $objInfraException->adicionarValidacao('dispositivo revogado não informado.');
    }
  }

  protected function cadastrarControlado(MdLitRelDispositivoNormativoRevogadoDTO $objMdLitRelDispositivoNormativoRevogadoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitDispositivoNormativo($objMdLitRelDispositivoNormativoRevogadoDTO, $objInfraException);
      $this->validarNumIdMdLitDispositivoNormativoRevogado($objMdLitRelDispositivoNormativoRevogadoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDispositivoNormativoRevogadoBD->cadastrar($objMdLitRelDispositivoNormativoRevogadoDTO);

        //revogando os dispositivo normativos
        $objMdLitDispositivoNormativoRN = new MdLitDispositivoNormativoRN();
        $objMdLitDispositivoNormativoRN->revogar($ret->getNumIdMdLitDispositivoNormativoRevogado());

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dispositivo revogado.',$e);
    }
  }

  protected function alterarControlado(MdLitRelDispositivoNormativoRevogadoDTO $objMdLitRelDispositivoNormativoRevogadoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelDispositivoNormativoRevogadoDTO->isSetNumIdMdLitDispositivoNormativo()){
        $this->validarNumIdMdLitDispositivoNormativo($objMdLitRelDispositivoNormativoRevogadoDTO, $objInfraException);
      }
      if ($objMdLitRelDispositivoNormativoRevogadoDTO->isSetNumIdMdLitDispositivoNormativoRevogado()){
        $this->validarNumIdMdLitDispositivoNormativoRevogado($objMdLitRelDispositivoNormativoRevogadoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      $objMdLitRelDispositivoNormativoRevogadoBD->alterar($objMdLitRelDispositivoNormativoRevogadoDTO);

        //revogando os dispositivo normativos
        $objMdLitDispositivoNormativoRN = new MdLitDispositivoNormativoRN();
        $objMdLitDispositivoNormativoRN->revogar($objMdLitRelDispositivoNormativoRevogadoDTO->getNumIdMdLitDispositivoNormativoRevogado());

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dispositivo revogado.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelDispositivoNormativoRevogadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDispositivoNormativoRevogadoDTO);$i++){
        $objMdLitRelDispositivoNormativoRevogadoBD->excluir($arrObjMdLitRelDispositivoNormativoRevogadoDTO[$i]);

          //revogando os dispositivo normativos
          $objMdLitDispositivoNormativoRN = new MdLitDispositivoNormativoRN();
          $objMdLitDispositivoNormativoRN->desfazerRevogar($arrObjMdLitRelDispositivoNormativoRevogadoDTO[$i]->getNumIdMdLitDispositivoNormativoRevogado());
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo dispositivo revogado.',$e);
    }
  }

  protected function consultarConectado(MdLitRelDispositivoNormativoRevogadoDTO $objMdLitRelDispositivoNormativoRevogadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDispositivoNormativoRevogadoBD->consultar($objMdLitRelDispositivoNormativoRevogadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dispositivo revogado.',$e);
    }
  }

  protected function listarConectado(MdLitRelDispositivoNormativoRevogadoDTO $objMdLitRelDispositivoNormativoRevogadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDispositivoNormativoRevogadoBD->listar($objMdLitRelDispositivoNormativoRevogadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dispositivos revogados.',$e);
    }
  }

  protected function contarConectado(MdLitRelDispositivoNormativoRevogadoDTO $objMdLitRelDispositivoNormativoRevogadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDispositivoNormativoRevogadoBD->contar($objMdLitRelDispositivoNormativoRevogadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dispositivos revogados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelDispositivoNormativoRevogadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDispositivoNormativoRevogadoDTO);$i++){
        $objMdLitRelDispositivoNormativoRevogadoBD->desativar($arrObjMdLitRelDispositivoNormativoRevogadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dispositivo revogado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelDispositivoNormativoRevogadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDispositivoNormativoRevogadoDTO);$i++){
        $objMdLitRelDispositivoNormativoRevogadoBD->reativar($arrObjMdLitRelDispositivoNormativoRevogadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dispositivo revogado.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelDispositivoNormativoRevogadoDTO $objMdLitRelDispositivoNormativoRevogadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_disp_norm_revogado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDispositivoNormativoRevogadoBD = new MdLitRelDispositivoNormativoRevogadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDispositivoNormativoRevogadoBD->bloquear($objMdLitRelDispositivoNormativoRevogadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dispositivo revogado.',$e);
    }
  }

 */
}
