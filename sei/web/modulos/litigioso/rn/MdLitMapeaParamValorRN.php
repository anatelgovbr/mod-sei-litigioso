<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/07/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMapeaParamValorRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitMapearParamEntrada(MdLitMapeaParamValorDTO $objMdLitMapeaParamValorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapeaParamValorDTO->getNumIdMdLitMapearParamEntrada())){
      $objInfraException->adicionarValidacao('parâmetro de entrada não informado.');
    }
  }

  private function validarNumIdMdLitTipoControle(MdLitMapeaParamValorDTO $objMdLitMapeaParamValorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapeaParamValorDTO->getNumIdMdLitTipoControle())){
      $objInfraException->adicionarValidacao('tipo de controle não informado.');
    }
  }

  private function validarStrValorDefault(MdLitMapeaParamValorDTO $objMdLitMapeaParamValorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapeaParamValorDTO->getStrValorDefault())){
      $objMdLitMapeaParamValorDTO->setStrValorDefault(null);
    }else{
      $objMdLitMapeaParamValorDTO->setStrValorDefault(trim($objMdLitMapeaParamValorDTO->getStrValorDefault()));

      if (strlen($objMdLitMapeaParamValorDTO->getStrValorDefault())>100){
        $objInfraException->adicionarValidacao('valor padrão possui tamanho superior a 100 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitMapeaParamValorDTO $objMdLitMapeaParamValorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapea_param_valor_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitMapearParamEntrada($objMdLitMapeaParamValorDTO, $objInfraException);
      $this->validarNumIdMdLitTipoControle($objMdLitMapeaParamValorDTO, $objInfraException);
      $this->validarStrValorDefault($objMdLitMapeaParamValorDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitMapeaParamValorBD = new MdLitMapeaParamValorBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapeaParamValorBD->cadastrar($objMdLitMapeaParamValorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando valor do parâmetro.',$e);
    }
  }

  protected function alterarControlado(MdLitMapeaParamValorDTO $objMdLitMapeaParamValorDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_mapea_param_valor_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitMapeaParamValorDTO->isSetNumIdMdLitMapearParamEntrada()){
        $this->validarNumIdMdLitMapearParamEntrada($objMdLitMapeaParamValorDTO, $objInfraException);
      }
      if ($objMdLitMapeaParamValorDTO->isSetNumIdMdLitTipoControle()){
        $this->validarNumIdMdLitTipoControle($objMdLitMapeaParamValorDTO, $objInfraException);
      }
      if ($objMdLitMapeaParamValorDTO->isSetStrValorDefault()){
        $this->validarStrValorDefault($objMdLitMapeaParamValorDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitMapeaParamValorBD = new MdLitMapeaParamValorBD($this->getObjInfraIBanco());
      $objMdLitMapeaParamValorBD->alterar($objMdLitMapeaParamValorDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando valor do parâmetro.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitMapeaParamValorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapea_param_valor_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapeaParamValorBD = new MdLitMapeaParamValorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMapeaParamValorDTO);$i++){
        $objMdLitMapeaParamValorBD->excluir($arrObjMdLitMapeaParamValorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo valor do parâmetro.',$e);
    }
  }

  protected function consultarConectado(MdLitMapeaParamValorDTO $objMdLitMapeaParamValorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapea_param_valor_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapeaParamValorBD = new MdLitMapeaParamValorBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapeaParamValorBD->consultar($objMdLitMapeaParamValorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando valor do parâmetro.',$e);
    }
  }

  protected function listarConectado(MdLitMapeaParamValorDTO $objMdLitMapeaParamValorDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapea_param_valor_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapeaParamValorBD = new MdLitMapeaParamValorBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapeaParamValorBD->listar($objMdLitMapeaParamValorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores do parâmetro.',$e);
    }
  }

  protected function contarConectado(MdLitMapeaParamValorDTO $objMdLitMapeaParamValorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapea_param_valor_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapeaParamValorBD = new MdLitMapeaParamValorBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapeaParamValorBD->contar($objMdLitMapeaParamValorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando valores do parâmetro.',$e);
    }
  }

}