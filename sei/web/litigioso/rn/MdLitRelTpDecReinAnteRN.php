<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/04/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelTpDecReinAnteRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitTipoDecisao(MdLitRelTpDecReinAnteDTO $objMdLitRelTpDecReinAnteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelTpDecReinAnteDTO->getNumIdRelMdLitTipoDecisao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdMdLitReincidenAnteceden(MdLitRelTpDecReinAnteDTO $objMdLitRelTpDecReinAnteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelTpDecReinAnteDTO->getNumIdRelMdLitReincidenAnteceden())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(MdLitRelTpDecReinAnteDTO $objMdLitRelTpDecReinAnteDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitTipoDecisao($objMdLitRelTpDecReinAnteDTO, $objInfraException);
      $this->validarNumIdMdLitReincidenAnteceden($objMdLitRelTpDecReinAnteDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpDecReinAnteBD->cadastrar($objMdLitRelTpDecReinAnteDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado($objMdLitRelTpDecReinAnteDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelTpDecReinAnteDTO->isSetNumIdRelMdLitTipoDecisao()){
        $this->validarNumIdMdLitTipoDecisao($objMdLitRelTpDecReinAnteDTO, $objInfraException);
      }
      if ($objMdLitRelTpDecReinAnteDTO->isSetNumIdRelMdLitReincidenAnteceden()){
        $this->validarNumIdMdLitReincidenAnteceden($objMdLitRelTpDecReinAnteDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());

      $objMdLitRelTpDecReinAnteBD->alterar($objMdLitRelTpDecReinAnteDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelTpDecReinAnteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelTpDecReinAnteDTO);$i++){
        $objMdLitRelTpDecReinAnteBD->excluir($arrObjMdLitRelTpDecReinAnteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdLitRelTpDecReinAnteDTO $objMdLitRelTpDecReinAnteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpDecReinAnteBD->consultar($objMdLitRelTpDecReinAnteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdLitRelTpDecReinAnteDTO $objMdLitRelTpDecReinAnteDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpDecReinAnteBD->listar($objMdLitRelTpDecReinAnteDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdLitRelTpDecReinAnteDTO $objMdLitRelTpDecReinAnteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpDecReinAnteBD->contar($objMdLitRelTpDecReinAnteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitRelTpDecReinAnteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelTpDecReinAnteDTO);$i++){
        $objMdLitRelTpDecReinAnteBD->desativar($arrObjMdLitRelTpDecReinAnteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelTpDecReinAnteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelTpDecReinAnteDTO);$i++){
        $objMdLitRelTpDecReinAnteBD->reativar($arrObjMdLitRelTpDecReinAnteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdLitRelTpDecReinAnteDTO $objMdLitRelTpDecReinAnteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_tp_dec_rein_ante_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelTpDecReinAnteBD = new MdLitRelTpDecReinAnteBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelTpDecReinAnteBD->bloquear($objMdLitRelTpDecReinAnteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
