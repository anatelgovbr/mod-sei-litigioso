<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitIntegracaoRN extends InfraRN {

    public static $DADOS_COMPL_INTERESSADO_CONSULTA      = 1;
    public static $ARRECADACAO_LANCAMENTO_CREDITO        = 2;
    public static $ARRECADACAO_CONSULTAR_LANCAMENTO      = 3;
    public static $ARRECADACAO_CANCELAR_LANCAMENTO       = 4;
    public static $ARRECADACAO_RETIFICAR_LANCAMENTO      = 5;
    public static $ARRECADACAO_SUSPENDER_LANCAMENTO      = 6;
    public static $ARRECADACAO_DENEGAR_RECURSO           = 7;
    public static $ARRECADACAO_CANCELAR_RECURSO          = 8;
    public static $ARRECADACAO_LISTA_MOTIVO_CANCELAMENTO = 9;
    public static $GERAR_NUMERO_COMPLEMENTAR_ENTIDADE_NAO_OUTORGADA = 10;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitFuncionalidade(MdLitIntegracaoDTO $objMdLitIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade())){
      $objInfraException->adicionarValidacao('funcionalidade não informada.');
    }else{
        $objPesqMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objPesqMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($objMdLitIntegracaoDTO->getNumIdMdLitFuncionalidade());

        if($objMdLitIntegracaoDTO->isSetNumIdMdLitIntegracao() and $objMdLitIntegracaoDTO->getNumIdMdLitIntegracao() != null ){
            $objPesqMdLitIntegracaoDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao(), InfraDTO::$OPER_DIFERENTE);
        }
        $count = $this->contar($objPesqMdLitIntegracaoDTO);
        if($count > 0){
            $objInfraException->adicionarValidacao('Funcionalidade duplicada. Não é possível ter integração com a mesma funcionalidade.');
        }
    }
  }

  private function validarStrNome(MdLitIntegracaoDTO $objMdLitIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitIntegracaoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('nome não informado.');
    }else{
      $objMdLitIntegracaoDTO->setStrNome(trim($objMdLitIntegracaoDTO->getStrNome()));

      if (strlen($objMdLitIntegracaoDTO->getStrNome())>30){
        $objInfraException->adicionarValidacao('nome possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrEnderecoWsdl(MdLitIntegracaoDTO $objMdLitIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitIntegracaoDTO->getStrEnderecoWsdl())){
      $objInfraException->adicionarValidacao('Endereço wsdl não informado.');
    }else{
      $objMdLitIntegracaoDTO->setStrEnderecoWsdl(trim($objMdLitIntegracaoDTO->getStrEnderecoWsdl()));

      if (strlen($objMdLitIntegracaoDTO->getStrEnderecoWsdl())>100){
        $objInfraException->adicionarValidacao('Endereço wsdl possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrOperacaWsdl(MdLitIntegracaoDTO $objMdLitIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitIntegracaoDTO->getStrOperacaWsdl())){
      $objInfraException->adicionarValidacao('Operação não informada.');
    }else{
      $objMdLitIntegracaoDTO->setStrOperacaWsdl(trim($objMdLitIntegracaoDTO->getStrOperacaWsdl()));

      if (strlen($objMdLitIntegracaoDTO->getStrOperacaWsdl())>50){
        $objInfraException->adicionarValidacao('Operação possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdLitIntegracaoDTO $objMdLitIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitIntegracaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitIntegracaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

    private function validarArrObjMdLitMapearParamEntradaDTO(MdLitIntegracaoDTO $objMdLitIntegracaoDTO, InfraException $objInfraException){
        if (empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO())){
            $objInfraException->adicionarValidacao('Mapeamento do parametro de entrada não informado.');
        }
    }

    private function validarArrObjMdLitMapearParamSaidaDTO(MdLitIntegracaoDTO $objMdLitIntegracaoDTO, InfraException $objInfraException){
        if (empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO())){
            $objInfraException->adicionarValidacao('Mapeamento do parametro de saida não informado.');
        }
    }

  protected function cadastrarControlado(MdLitIntegracaoDTO $objMdLitIntegracaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_integracao_cadastrar', __METHOD__, $objMdLitIntegracaoDTO);

        $objMdLitIntegracaoDTO->setStrSinAtivo('S');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitFuncionalidade($objMdLitIntegracaoDTO, $objInfraException);
      $this->validarStrNome($objMdLitIntegracaoDTO, $objInfraException);
      $this->validarStrEnderecoWsdl($objMdLitIntegracaoDTO, $objInfraException);
      $this->validarStrOperacaWsdl($objMdLitIntegracaoDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdLitIntegracaoDTO, $objInfraException);
      #$this->validarArrObjMdLitMapearParamEntradaDTO($objMdLitIntegracaoDTO, $objInfraException);
      #$this->validarArrObjMdLitMapearParamSaidaDTO($objMdLitIntegracaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());

        $ret = $objMdLitIntegracaoBD->cadastrar($objMdLitIntegracaoDTO);

        $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
        $objMdLitMapearParamSaidaRN   = new MdLitMapearParamSaidaRN();
        $objMdLitMapeaParamValorRN    = new MdLitMapeaParamValorRN();

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO ){
            $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($ret->getNumIdMdLitIntegracao());

            $dto = $objMdLitMapearParamEntradaRN->cadastrar($objMdLitMapearParamEntradaDTO);

            //Campo Código Receita
            if ($objMdLitMapearParamEntradaDTO->isSetArrObjMdLitMapeaParamValorDTO()) {
                $objMdLitMapeaParamValorDTO2 = new MdLitMapeaParamValorDTO();
                $objMdLitMapeaParamValorDTO2->setNumIdMdLitMapearParamEntrada($dto->getNumIdMdLitMapearParamEntrada());
                $objMdLitMapeaParamValorDTO2->retNumIdMdLitMapeaParamValor();
                foreach ($objMdLitMapearParamEntradaDTO->getArrObjMdLitMapeaParamValorDTO() as $objMdLitMapeaParamValorDTO) {
                    $objMdLitMapeaParamValorDTO->setNumIdMdLitMapearParamEntrada($dto->getNumIdMdLitMapearParamEntrada());
                    $objMdLitMapeaParamValorRN->cadastrar($objMdLitMapeaParamValorDTO);
                }
            }

        }

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO ){
            $objMdLitMapearParamSaidaDTO->setNumIdMdLitIntegracao($ret->getNumIdMdLitIntegracao());

            $objMdLitMapearParamSaidaRN->cadastrar($objMdLitMapearParamSaidaDTO);
        }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Integração.',$e);
    }
  }

  protected function alterarControlado(MdLitIntegracaoDTO $objMdLitIntegracaoDTO){
    try {

      //Valida Permissao
       SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_integracao_alterar', __METHOD__, $objMdLitIntegracaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitIntegracaoDTO->isSetNumIdMdLitFuncionalidade()){
        $this->validarNumIdMdLitFuncionalidade($objMdLitIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitIntegracaoDTO->isSetStrNome()){
        $this->validarStrNome($objMdLitIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitIntegracaoDTO->isSetStrEnderecoWsdl()){
        $this->validarStrEnderecoWsdl($objMdLitIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitIntegracaoDTO->isSetStrOperacaWsdl()){
        $this->validarStrOperacaWsdl($objMdLitIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitIntegracaoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdLitIntegracaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());
      $objMdLitIntegracaoBD->alterar($objMdLitIntegracaoDTO);


        $objMdLitMapearParamEntradaRN  = new MdLitMapearParamEntradaRN();
        $objMdLitMapearParamSaidaRN    = new MdLitMapearParamSaidaRN();
        $objMdLitMapeaParamValorRN     = new MdLitMapeaParamValorRN();
        $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();
        $objMdLitMapearParamSaidaDTO   = new MdLitMapearParamSaidaDTO();

        $objMdLitMapearParamEntradaDTO->retNumIdMdLitMapearParamEntrada();
        $objMdLitMapearParamSaidaDTO->retNumIdMdLitMapearParamSaida();
        $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());
        $objMdLitMapearParamSaidaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());


        $arrMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);
        //Limpando a Tabela md_lit_mapea_param_valor
        foreach ($arrMdLitMapearParamEntradaDTO as $objMdLitMapearParamEntradaDTO) {
            $objMdLitMapeaParamValorDTO = new MdLitMapeaParamValorDTO();
            $objMdLitMapeaParamValorDTO->setNumIdMdLitMapearParamEntrada($objMdLitMapearParamEntradaDTO->getNumIdMdLitMapearParamEntrada());
            $objMdLitMapeaParamValorDTO->retNumIdMdLitMapeaParamValor();
            $objMdLitMapeaParamValorRN->excluir($objMdLitMapeaParamValorRN->listar($objMdLitMapeaParamValorDTO));
        }

        $objMdLitMapearParamEntradaRN->excluir($arrMdLitMapearParamEntradaDTO);
        $objMdLitMapearParamSaidaRN->excluir($objMdLitMapearParamSaidaRN->listar($objMdLitMapearParamSaidaDTO));

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO ){
            $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());

            $dto = $objMdLitMapearParamEntradaRN->cadastrar($objMdLitMapearParamEntradaDTO);

            //Campo Código Receita
            if ($objMdLitMapearParamEntradaDTO->isSetArrObjMdLitMapeaParamValorDTO()) {
                foreach ($objMdLitMapearParamEntradaDTO->getArrObjMdLitMapeaParamValorDTO() as $objMdLitMapeaParamValorDTO) {
                    $objMdLitMapeaParamValorDTO->setNumIdMdLitMapearParamEntrada($dto->getNumIdMdLitMapearParamEntrada());
                    $objMdLitMapeaParamValorRN->cadastrar($objMdLitMapeaParamValorDTO);
                }
            }

        }

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO ){
            $objMdLitMapearParamSaidaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());

            $objMdLitMapearParamSaidaRN->cadastrar($objMdLitMapearParamSaidaDTO);
        }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Integração.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitIntegracaoDTO){
    try {

      //Valida Permissao
       SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_integracao_excluir', __METHOD__, $arrObjMdLitIntegracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();


        $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
        $objMdLitMapearParamSaidaRN   = new MdLitMapearParamSaidaRN();
        $objMdLitMapeaParamValorRN    = new MdLitMapeaParamValorRN();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitIntegracaoDTO);$i++){
          $objMdLitMapearParamEntradaDTO  = new MdLitMapearParamEntradaDTO();
          $objMdLitMapearParamSaidaDTO    = new MdLitMapearParamSaidaDTO();
          $objMdLitMapeaParamValorDTO = new MdLitMapeaParamValorDTO();

          $objMdLitMapearParamEntradaDTO->retNumIdMdLitMapearParamEntrada();
          $objMdLitMapearParamSaidaDTO->retNumIdMdLitMapearParamSaida();
          $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($arrObjMdLitIntegracaoDTO[$i]->getNumIdMdLitIntegracao());
          $objMdLitMapearParamSaidaDTO->setNumIdMdLitIntegracao($arrObjMdLitIntegracaoDTO[$i]->getNumIdMdLitIntegracao());


          $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);
          foreach ($arrObjMdLitMapearParamEntradaDTO as $dto){

              //Excluir Campo Código Receita
              $objMdLitMapeaParamValorDTO->setNumIdMdLitMapearParamEntrada($dto->getNumIdMdLitMapearParamEntrada());
              $objMdLitMapeaParamValorDTO->retNumIdMdLitMapeaParamValor();
              $objMdLitMapeaParamValorRN->excluir($objMdLitMapeaParamValorRN->listar($objMdLitMapeaParamValorDTO));

          }

          $objMdLitMapearParamEntradaRN->excluir($arrObjMdLitMapearParamEntradaDTO);
          $objMdLitMapearParamSaidaRN->excluir($objMdLitMapearParamSaidaRN->listar($objMdLitMapearParamSaidaDTO));

          $objMdLitIntegracaoBD->excluir($arrObjMdLitIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Integração.',$e);
    }
  }

  protected function consultarConectado(MdLitIntegracaoDTO $objMdLitIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_integracao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitIntegracaoBD->consultar($objMdLitIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Integração.',$e);
    }
  }

  protected function listarConectado(MdLitIntegracaoDTO $objMdLitIntegracaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_integracao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitIntegracaoBD->listar($objMdLitIntegracaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Integrações.',$e);
    }
  }

  protected function consultaMapeaEntradaSaidaConectado(MdLitIntegracaoDTO $objMdLitIntegracaoDTO){
      $objMdLitIntegracaoDTO = $this->consultar($objMdLitIntegracaoDTO);
      if($objMdLitIntegracaoDTO){
          $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();
          $objMdLitMapearParamEntradaDTO->retTodos(false);
          $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());

          $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
          $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);

          $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();
          $objMdLitMapearParamSaidaDTO->retTodos(false);
          $objMdLitMapearParamSaidaDTO->setNumIdMdLitIntegracao($objMdLitIntegracaoDTO->getNumIdMdLitIntegracao());

          $objMdLitMapearParamSaidaRN = new MdLitMapearParamSaidaRN();
          $arrObjMdLitMapearParamSaidaDTO = $objMdLitMapearParamSaidaRN->listar($objMdLitMapearParamSaidaDTO);

          $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamSaidaDTO($arrObjMdLitMapearParamSaidaDTO);
          $objMdLitIntegracaoDTO->setArrObjMdLitMapearParamEntradaDTO($arrObjMdLitMapearParamEntradaDTO);
      }

      return $objMdLitIntegracaoDTO;
  }

  protected function contarConectado(MdLitIntegracaoDTO $objMdLitIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_integracao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitIntegracaoBD->contar($objMdLitIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Integrações.',$e);
    }
  }

  protected function desativarControlado($arrObjMdLitIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_integracao_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitIntegracaoDTO);$i++){
        $objMdLitIntegracaoBD->desativar($arrObjMdLitIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Integração.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_integracao_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitIntegracaoDTO);$i++){
        $objMdLitIntegracaoBD->reativar($arrObjMdLitIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Integração.',$e);
    }
  }

  protected function bloquearControlado(MdLitIntegracaoDTO $objMdLitIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_integracao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitIntegracaoBD = new MdLitIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitIntegracaoBD->bloquear($objMdLitIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Integração.',$e);
    }
  }

    public function retornarObjIntegracaoDTOPorFuncionalidade($idFuncionalidade){
        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoDTO->retTodos(true);
        $objMdLitIntegracaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($idFuncionalidade);

        $objMdLitIntegracaoRN  = new MdLitIntegracaoRN();
        $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultaMapeaEntradaSaida($objMdLitIntegracaoDTO);

        return $objMdLitIntegracaoDTO;
    }

    public function getFuncionalidadesIntegracaoArrecadacaoCadastradas()
    {
        //lista de funcionalidades obrigatorias para multa com integracao WS
        $arrFuncionalidadesIntegracao = [
            self::$ARRECADACAO_LANCAMENTO_CREDITO,
            self::$ARRECADACAO_CONSULTAR_LANCAMENTO,
            self::$ARRECADACAO_CANCELAR_LANCAMENTO,
            self::$ARRECADACAO_RETIFICAR_LANCAMENTO,
            self::$ARRECADACAO_SUSPENDER_LANCAMENTO,
            self::$ARRECADACAO_DENEGAR_RECURSO,
            self::$ARRECADACAO_CANCELAR_RECURSO,
            self::$ARRECADACAO_LISTA_MOTIVO_CANCELAMENTO,
        ];

        $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
        $objMdLitIntegracaoDTO->retTodos(false);
        $objMdLitIntegracaoDTO->retStrNomeMdLitFuncionalidade();
        $objMdLitIntegracaoDTO->setBolExclusaoLogica(false);
        $objMdLitIntegracaoDTO->setStrSinAtivo('S');
        $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($arrFuncionalidadesIntegracao, InfraDTO::$OPER_IN);

        //consulta se esta cadastrado todas as funcionalidades de integração de arrecadação
        return $arrObjMdLitIntegracao = $this->listar($objMdLitIntegracaoDTO);
    }

}