<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/07/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCampoIntegracaoRN extends InfraRN {

  public static $PARAMETRO_1 = 'A';
  public static $PARAMETRO_2 = 'B';
  public static $PARAMETRO_3 = 'C';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresParametro(){
    try {

      $arrObjParametroMdLitCampoIntegracaoDTO = array();

      $objParametroMdLitCampoIntegracaoDTO = new ParametroMdLitCampoIntegracaoDTO();
      $objParametroMdLitCampoIntegracaoDTO->setStrStaParametro(self::$PARAMETRO_1);
      $objParametroMdLitCampoIntegracaoDTO->setStrDescricao('Descrição Parametro 1');
      $arrObjParametroMdLitCampoIntegracaoDTO[] = $objParametroMdLitCampoIntegracaoDTO;

      $objParametroMdLitCampoIntegracaoDTO = new ParametroMdLitCampoIntegracaoDTO();
      $objParametroMdLitCampoIntegracaoDTO->setStrStaParametro(self::$PARAMETRO_2);
      $objParametroMdLitCampoIntegracaoDTO->setStrDescricao('Descrição Parametro 2');
      $arrObjParametroMdLitCampoIntegracaoDTO[] = $objParametroMdLitCampoIntegracaoDTO;

      $objParametroMdLitCampoIntegracaoDTO = new ParametroMdLitCampoIntegracaoDTO();
      $objParametroMdLitCampoIntegracaoDTO->setStrStaParametro(self::$PARAMETRO_3);
      $objParametroMdLitCampoIntegracaoDTO->setStrDescricao('Descrição Parametro 3');
      $arrObjParametroMdLitCampoIntegracaoDTO[] = $objParametroMdLitCampoIntegracaoDTO;

      return $arrObjParametroMdLitCampoIntegracaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Parametro.',$e);
    }
  }

  private function validarNumIdMdLitFuncionalidade(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitCampoIntegracaoDTO->getNumIdMdLitFuncionalidade())){
      $objInfraException->adicionarValidacao('funcionalidade não informada.');
    }
  }

  private function validarStrNomeCampo(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitCampoIntegracaoDTO->getStrNomeCampo())){
      $objMdLitCampoIntegracaoDTO->setStrNomeCampo(null);
    }else{
      $objMdLitCampoIntegracaoDTO->setStrNomeCampo(trim($objMdLitCampoIntegracaoDTO->getStrNomeCampo()));

      if (strlen($objMdLitCampoIntegracaoDTO->getStrNomeCampo())>50){
        $objInfraException->adicionarValidacao('campo possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrStaParametro(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitCampoIntegracaoDTO->getStrStaParametro())){
      $objMdLitCampoIntegracaoDTO->setStrStaParametro(null);
    }else{
      if (!in_array($objMdLitCampoIntegracaoDTO->getStrStaParametro(),InfraArray::converterArrInfraDTO($this->listarValoresParametro(),'StaParametro'))){
        $objInfraException->adicionarValidacao('parametro de entrada inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitFuncionalidade($objMdLitCampoIntegracaoDTO, $objInfraException);
      $this->validarStrNomeCampo($objMdLitCampoIntegracaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCampoIntegracaoBD->cadastrar($objMdLitCampoIntegracaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando campo de integração.',$e);
    }
  }

  protected function alterarControlado(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitCampoIntegracaoDTO->isSetNumIdMdLitFuncionalidade()){
        $this->validarNumIdMdLitFuncionalidade($objMdLitCampoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitCampoIntegracaoDTO->isSetStrNomeCampo()){
        $this->validarStrNomeCampo($objMdLitCampoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitCampoIntegracaoDTO->isSetStrStaParametro()){
        $this->validarStrStaParametro($objMdLitCampoIntegracaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      $objMdLitCampoIntegracaoBD->alterar($objMdLitCampoIntegracaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando campo de integração.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitCampoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitCampoIntegracaoDTO);$i++){
        $objMdLitCampoIntegracaoBD->excluir($arrObjMdLitCampoIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo campo de integração.',$e);
    }
  }

  protected function consultarConectado(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCampoIntegracaoBD->consultar($objMdLitCampoIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando campo de integração.',$e);
    }
  }

  protected function listarConectado(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO) {
    try {

      //Valida Permissao
      //SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCampoIntegracaoBD->listar($objMdLitCampoIntegracaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando campos de integração.',$e);
    }
  }

  protected function contarConectado(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCampoIntegracaoBD->contar($objMdLitCampoIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando campos de integração.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitCampoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitCampoIntegracaoDTO);$i++){
        $objMdLitCampoIntegracaoBD->desativar($arrObjMdLitCampoIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando campo de integração.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitCampoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitCampoIntegracaoDTO);$i++){
        $objMdLitCampoIntegracaoBD->reativar($arrObjMdLitCampoIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando campo de integração.',$e);
    }
  }

  protected function bloquearControlado(MdLitCampoIntegracaoDTO $objMdLitCampoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_campo_integracao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitCampoIntegracaoBD = new MdLitCampoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitCampoIntegracaoBD->bloquear($objMdLitCampoIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando campo de integração.',$e);
    }
  }

 */
}
?>