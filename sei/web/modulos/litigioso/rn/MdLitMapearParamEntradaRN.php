<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMapearParamEntradaRN extends InfraRN {

    public static $PARAM_PRINCIPAL_SIGEC_LANCAMENTO       = 'debitos';
    public static $PARAM_PRINCIPAL_SIGEC_CANC_LANCAMENTO  = 'lancamentoCancelar';
    public static $PARAM_PRINCIPAL_SIGEC_CANC_RECURSO     = 'cancelarRecursosSIGEC';
    public static $PARAM_PRINCIPAL_SIGEC_DENEGAR_RECURSO  = 'denegarRecursosSIGEC';
    public static $PARAM_PRINCIPAL_SIGEC_RETIF_LANCAMENTO = 'lancamentoRetificar';
    public static $PARAM_PRINCIPAL_SIGEC_SUSP_LANCAMENTO  = 'suspenderRecursosSIGEC';
    public static $PARAM_PRINCIPAL_FILTROS  = 'filtro';

    public static $ID_PARAM_LANCAMENTO_CREDITO = array(
        'COD_RECEITA'                  => 5,
        'DTA_VENCIMENTO'               => 3,
        'DTA_APLICACAO_SANCAO'         => 2,
        'NUMERO_INTERESSADO'           => 1,
        'VALOR_RECEITA'                => 4,
        'JUSTIFICATIVA_LANCAMENTO'     => 7,
        'NUM_PROCESSO'                 => 6,
        'USUARIO_INCLUSAO'             => 8,
        'SISTEMA_ORIGEM'               => 9,
        'VALIDAR_MAJORACAO'            => 10,
        'CNPJ_CPF'                     => 78,
        'DOCUMENTO_APLICACAO_DA_MULTA' =>100
    );

    public static $ID_PARAM_CANCELAR_LANCAMENTO = array(
        'NUMERO_INTERESSADO'        => 26,
        'SEQUENCIAL'                => 27,
        'ID_MOTIVO_CANCELAMENTO'    => 28,
        'NUM_PROCESSO'              => 29,
        'JUSTIFICATIVA_CANCELAMENTO'=> 30,
        'USUARIO_INCLUSAO'          => 31,
        'SISTEMA_ORIGEM'            => 32,
        'CNPJ_CPF'                  => 89
    );

    public static $ID_PARAM_CONSULTAR_LANCAMENTO = array(
        'NUMERO_INTERESSADO' => 15,
        'SEQUENCIAL' => 16,
        'RENUNCIA_RECURSO' => 17,
        'CNPJ/CPF' => 87
    );

    public static $ID_PARAM_RETIFICAR_LANCAMENTO = array(
        'NUMERO_INTERESSADO'        => 36,
        'DTA_APLICACAO_SANCAO'      => 37,
        'DTA_VENCIMENTO'            => 38,
        'VALOR_TOTAL'               => 39,
        'COD_RECEITA'               => 40,
        'DTA_CONSTITUICAO'          => 41,
        'NUM_PROCESSO'              => 42,
        'JUSTIFICATIVA_LANCAMENTO'  => 43,
        'USUARIO_INCLUSAO'          => 44,
        'SISTEMA_ORIGEM'            => 45,
        'RENUNCIA_RECURSO'          => 46,
        'SEQUENCIAL'                => 47,
        'DTA_INTIMACAO_DEFINITIVA'  => 86,
        'CNPJ_CPF'                  => 91,
        'DOC_DECISAO_APL_MULTA'     => 101,
        'DTA_INT_DECISAO_APL_MULTA' => 102,
        'DTA_DECURSO_PRAZO_RECURSO' => 103,
        'DTA_APRESENTACAO_RECURSO'  => 104,
        'DTA_DECISAO_DEFINITIVA'    => 105,
        'DOC_DECISAO_DEFINITIVA'    => 106
    );

    public static $ID_PARAM_SUSPENDER_LANCAMENTO = array(
        'NUMERO_INTERESSADO'        => 52,
        'SEQUENCIAL'                => 53,
        'OBSERVACAO'                => 54,
        'USUARIO_INCLUSAO'          => 55,
        'SISTEMA_ORIGEM'            => 56,
        'DTA_PROCESSO'              => 57,
        'CNPJ_CPF'                  => 93,
        'DTA_APRESENTACAO_RECURSO'  => 106
    );

    public static $ID_PARAM_DENEGAR_RECURSO = array(
        'NUMERO_INTERESSADO'        => 60,
        'SEQUENCIAL'                => 61,
        'DATA_DENEGACAO'            => 62,
        'OBSERVACAO'                => 63,
        'SISTEMA_ORIGEM'            => 64,
        'USUARIO_INCLUSAO'          => 65,
        'CNPJ_CPF'                  => 95
    );

    public static $ID_PARAM_CANCELAR_RECURSO = array(
        'NUMERO_INTERESSADO'        => 68,
        'SEQUENCIAL'                => 69,
        'OBSERVACAO'                => 70,
        'SISTEMA_ORIGEM'            => 71,
        'CNPJ_CPF'                  => 97
    );

  public static $ID_PARAM_LANCAMENTO_CREDITO_CAMPOS_OBRIGRATORIOS = array(
      'NUMERO_INTERESSADO'           => 1,
      'DTA_APLICACAO_SANCAO'         => 2,
      'DTA_VENCIMENTO'               => 3,
      'VALOR_RECEITA'                => 4,
      'NUM_PROCESSO'                 => 6,
      'CNPJ_CPF'                     => 78,
      'LINK_BOLETO'                     => 11,
      'SEQUENCIAL'                     => 12
  );

    public static $ID_PARAM_GERAR_NUMERO_NAO_OUTORGADA = array(
        'SISTEMA_ORIGEM' => 76
    );

    public static $ID_PARAM_INTEGRACAO = array(
        'RETIFICAR_LANCAMENTO' => 3
    );

    public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitIntegracao(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapearParamEntradaDTO->getNumIdMdLitIntegracao())){
      $objInfraException->adicionarValidacao('integração não informada.');
    }
  }

  private function validarNumIdMdLitNomeFuncional(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapearParamEntradaDTO->getNumIdMdLitNomeFuncional())){
      $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional(null);
    }
  }

  private function validarStrCampo(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapearParamEntradaDTO->getStrCampo())){
      $objMdLitMapearParamEntradaDTO->setStrCampo(null);
    }else{
      $objMdLitMapearParamEntradaDTO->setStrCampo(trim($objMdLitMapearParamEntradaDTO->getStrCampo()));

      if (strlen($objMdLitMapearParamEntradaDTO->getStrCampo())>45){
        $objInfraException->adicionarValidacao('campo possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarStrChaveUnica(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitMapearParamEntradaDTO->getStrChaveUnica())){
      $objMdLitMapearParamEntradaDTO->setStrChaveUnica(null);
    }else{
      $objMdLitMapearParamEntradaDTO->setStrChaveUnica(trim($objMdLitMapearParamEntradaDTO->getStrChaveUnica()));

      if (strlen($objMdLitMapearParamEntradaDTO->getStrChaveUnica())>45){
        $objInfraException->adicionarValidacao('chave única possui tamanho superior a 45 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitIntegracao($objMdLitMapearParamEntradaDTO, $objInfraException);
      //$this->validarNumIdMdLitNomeFuncional($objMdLitMapearParamEntradaDTO, $objInfraException);
      $this->validarStrCampo($objMdLitMapearParamEntradaDTO, $objInfraException);
      $this->validarStrChaveUnica($objMdLitMapearParamEntradaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamEntradaBD->cadastrar($objMdLitMapearParamEntradaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Parâmetro de entrada.',$e);
    }
  }

  protected function alterarControlado(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitMapearParamEntradaDTO->isSetNumIdMdLitIntegracao()){
        $this->validarNumIdMdLitIntegracao($objMdLitMapearParamEntradaDTO, $objInfraException);
      }
      if ($objMdLitMapearParamEntradaDTO->isSetNumIdMdLitNomeFuncional()){
        $this->validarNumIdMdLitNomeFuncional($objMdLitMapearParamEntradaDTO, $objInfraException);
      }
      if ($objMdLitMapearParamEntradaDTO->isSetStrCampo()){
        $this->validarStrCampo($objMdLitMapearParamEntradaDTO, $objInfraException);
      }
      if ($objMdLitMapearParamEntradaDTO->isSetStrChaveUnica()){
        $this->validarStrChaveUnica($objMdLitMapearParamEntradaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      $objMdLitMapearParamEntradaBD->alterar($objMdLitMapearParamEntradaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Parâmetro de entrada.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitMapearParamEntradaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMapearParamEntradaDTO);$i++){
        $objMdLitMapearParamEntradaBD->excluir($arrObjMdLitMapearParamEntradaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Parâmetro de entrada.',$e);
    }
  }

  protected function consultarConectado(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamEntradaBD->consultar($objMdLitMapearParamEntradaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Parâmetro de entrada.',$e);
    }
  }

  protected function listarConectado(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamEntradaBD->listar($objMdLitMapearParamEntradaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Parâmetros de entrada.',$e);
    }
  }

  protected function contarConectado(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamEntradaBD->contar($objMdLitMapearParamEntradaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Parâmetros de entrada.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitMapearParamEntradaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMapearParamEntradaDTO);$i++){
        $objMdLitMapearParamEntradaBD->desativar($arrObjMdLitMapearParamEntradaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Parâmetro de entrada.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitMapearParamEntradaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitMapearParamEntradaDTO);$i++){
        $objMdLitMapearParamEntradaBD->reativar($arrObjMdLitMapearParamEntradaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Parâmetro de entrada.',$e);
    }
  }

  protected function bloquearControlado(MdLitMapearParamEntradaDTO $objMdLitMapearParamEntradaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_mapear_param_entrada_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitMapearParamEntradaBD = new MdLitMapearParamEntradaBD($this->getObjInfraIBanco());
      $ret = $objMdLitMapearParamEntradaBD->bloquear($objMdLitMapearParamEntradaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Parâmetro de entrada.',$e);
    }
  }

 */
}
?>