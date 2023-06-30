<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMapearParamSaidaRN extends InfraRN {

  public static $ID_PARAM_LANCAMENTO_CREDITO = array(
      'SEQUENCIAL'  => 11,
      'LINK_BOLETO' => 12,
      'COD_RECEITA' => 13,
      'NUMERO_INTERESSADO' => 14
  );

  public static $ID_PARAM_LISTAR_MOTIVOS_LANCAMENTO = array(
      'ID_MOTIVO' => 74,
      'DESCRICAO' => 75,
  );

  public static $ID_PARAM_CANCELAR_LANCAMENTO = array(
      'NUMERO_INTERESSADO' => 33,
      'SEQUENCIAL' => 34,
      'VENCIDO' => 35,
  );

  public static $ID_PARAM_CONSULTAR_LANCAMENTO = array(
      'CODIGO_SITUACAO' => 18,
      'DESBLOQUEIO_PROCESSO_VENCIDO' => 19,
      'VENCIDO' => 20,
      'DT_ULTIMO_PAGAMENTO' => 21,
      'VL_ULTIMO_PAGAMENTO' => 22,
      'VL_DESCONTO' => 23,
      'LINK_BOLETO' => 24,
      'VALOR_ATUALIZADO' => 25,
      'VALOR_RECEITA_INICIAL' => 79,
      'DTA_APLICACAO_MULTA' => 80,
      'DTA_VENCIMENTO' => 81,
      'NUM_COMPLEMENTAR_INTERESSADO' => 82,
      'IDENTIFICACAO_LANCAMENTO' => 83,
      'COD_RECEITA' => 84,
      'DTA_CONSTITUICAO_DEFINITIVA' => 85,
  );

    public static $ID_PARAM_RETIFICAR_LANCAMENTO = array(
        'COD_RECEITA'           => 48,
        'SEQUENCIAL'            => 49,
        'NUMERO_INTERESSADO'    => 50,
        'LINK_BOLETO'           => 51,
    );

    public static $ID_PARAM_SUSPENDER_LANCAMENTO = array(
        'NUMERO_INTERESSADO'           => 58,
        'SEQUENCIAL'       => 59,
    );

    public static $ID_PARAM_GERAR_NUMERO_NAO_OUTORGADA = array(
        'NUMERO_INTERESSADO'           => 77
    );

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitIntegracao(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapearParamSaidaDTO->getNumIdMdLitIntegracao())){
      $objInfraException->adicionarValidacao('integração não informada.');
    }
  }

  private function validarNumIdMdLitNomeFuncional(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapearParamSaidaDTO->getNumIdMdLitNomeFuncional())){
      $objMdLitMapearParamSaidaDTO->setNumIdMdLitNomeFuncional(null);
    }
  }

  private function validarStrCampo(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapearParamSaidaDTO->getStrCampo())){
      $objMdLitMapearParamSaidaDTO->setStrCampo(null);
    }else{
      $objMdLitMapearParamSaidaDTO->setStrCampo(trim($objMdLitMapearParamSaidaDTO->getStrCampo()));

      if (strlen($objMdLitMapearParamSaidaDTO->getStrCampo())>45){
        $objInfraException->adicionarValidacao('campo possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarStrChaveUnica(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapearParamSaidaDTO->getStrChaveUnica())){
      $objMdLitMapearParamSaidaDTO->setStrChaveUnica(null);
    }else{
      $objMdLitMapearParamSaidaDTO->setStrChaveUnica(trim($objMdLitMapearParamSaidaDTO->getStrChaveUnica()));

      if (strlen($objMdLitMapearParamSaidaDTO->getStrChaveUnica())>1){
        $objInfraException->adicionarValidacao('chave unica possui tamanho superior a 1 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitIntegracao($objMdLitMapearParamSaidaDTO, $objInfraException);
      //$this->validarNumIdMdLitNomeFuncional($objMdLitMapearParamSaidaDTO, $objInfraException);
      $this->validarStrCampo($objMdLitMapearParamSaidaDTO, $objInfraException);
      $this->validarStrChaveUnica($objMdLitMapearParamSaidaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamSaidaBD->cadastrar($objMdLitMapearParamSaidaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando parâmetro de saída.',$e);
    }
  }

  protected function alterarControlado(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitMapearParamSaidaDTO->isSetNumIdMdLitIntegracao()){
        $this->validarNumIdMdLitIntegracao($objMdLitMapearParamSaidaDTO, $objInfraException);
      }
      if ($objMdLitMapearParamSaidaDTO->isSetNumIdMdLitNomeFuncional()){
        $this->validarNumIdMdLitNomeFuncional($objMdLitMapearParamSaidaDTO, $objInfraException);
      }
      if ($objMdLitMapearParamSaidaDTO->isSetStrCampo()){
        $this->validarStrCampo($objMdLitMapearParamSaidaDTO, $objInfraException);
      }
      if ($objMdLitMapearParamSaidaDTO->isSetStrChaveUnica()){
        $this->validarStrChaveUnica($objMdLitMapearParamSaidaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      $objMdLitMapearParamSaidaBD->alterar($objMdLitMapearParamSaidaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando parâmetro de saída.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitMapearParamSaidaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMapearParamSaidaDTO);$i++){
        $objMdLitMapearParamSaidaBD->excluir($arrObjMdLitMapearParamSaidaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo parâmetro de saída.',$e);
    }
  }

  protected function consultarConectado(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamSaidaBD->consultar($objMdLitMapearParamSaidaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando parâmetro de saída.',$e);
    }
  }

  protected function listarConectado(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamSaidaBD->listar($objMdLitMapearParamSaidaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando parâmetros de saída.',$e);
    }
  }

  protected function contarConectado(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamSaidaBD->contar($objMdLitMapearParamSaidaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando parâmetros de saída.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitMapearParamSaidaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMapearParamSaidaDTO);$i++){
        $objMdLitMapearParamSaidaBD->desativar($arrObjMdLitMapearParamSaidaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando parâmetro de saída.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitMapearParamSaidaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMapearParamSaidaDTO);$i++){
        $objMdLitMapearParamSaidaBD->reativar($arrObjMdLitMapearParamSaidaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando parâmetro de saída.',$e);
    }
  }

  protected function bloquearControlado(MdLitMapearParamSaidaDTO $objMdLitMapearParamSaidaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_saida_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamSaidaBD = new MdLitMapearParamSaidaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamSaidaBD->bloquear($objMdLitMapearParamSaidaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando parâmetro de saída.',$e);
    }
  }

 */
}
?>