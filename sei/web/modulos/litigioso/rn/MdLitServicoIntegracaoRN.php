<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitServicoIntegracaoRN extends InfraRN {

    public static $CODIGO                = 'mapeamento_codigo';
    public static $DESCRICAO             = 'mapeamento_descricao';
    public static $SIGLA                 = 'mapeamento_sigla';
    public static $SITUACAO              = 'mapeamento_situacao';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNomeIntegracao(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitServicoIntegracaoDTO->getStrNomeIntegracao())){
      $objInfraException->adicionarValidacao('nome da integra��o n�o informado.');
    }else{
      $objMdLitServicoIntegracaoDTO->setStrNomeIntegracao(trim($objMdLitServicoIntegracaoDTO->getStrNomeIntegracao()));

      if (strlen($objMdLitServicoIntegracaoDTO->getStrNomeIntegracao())>30){
        $objInfraException->adicionarValidacao('nome da integra��o possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrEnderecoWsdl(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl())){
      $objInfraException->adicionarValidacao('endere�o de WSDL n�o informado.');
    }else{
      $objMdLitServicoIntegracaoDTO->setStrEnderecoWsdl(trim($objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl()));

      if (strlen($objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl())>100){
        $objInfraException->adicionarValidacao('endere�o de WSDL possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrOperacaoWsdl(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl())){
      $objInfraException->adicionarValidacao('Opera��o de WSDL n�o informado.');
    }else{
      $objMdLitServicoIntegracaoDTO->setStrOperacaoWsdl(trim($objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl()));

      if (strlen($objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl())>100){
        $objInfraException->adicionarValidacao('Opera��o de WSDL possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrMapeamentoCodigo(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo())){
      $objMdLitServicoIntegracaoDTO->setStrMapeamentoCodigo(null);
    }else{
      $objMdLitServicoIntegracaoDTO->setStrMapeamentoCodigo(trim($objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()));

      if (strlen($objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo())>45){
        $objInfraException->adicionarValidacao('mapeamento do c�digo possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarStrMapeamentoSigla(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitServicoIntegracaoDTO->getStrMapeamentoSigla())){
      $objMdLitServicoIntegracaoDTO->setStrMapeamentoSigla(null);
    }else{
      $objMdLitServicoIntegracaoDTO->setStrMapeamentoSigla(trim($objMdLitServicoIntegracaoDTO->getStrMapeamentoSigla()));

      if (strlen($objMdLitServicoIntegracaoDTO->getStrMapeamentoSigla())>45){
        $objInfraException->adicionarValidacao('mapeamento da sigla possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarStrMapeamentoDescricao(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitServicoIntegracaoDTO->getStrMapeamentoDescricao())){
      $objMdLitServicoIntegracaoDTO->setStrMapeamentoDescricao(null);
    }else{
      $objMdLitServicoIntegracaoDTO->setStrMapeamentoDescricao(trim($objMdLitServicoIntegracaoDTO->getStrMapeamentoDescricao()));

      if (strlen($objMdLitServicoIntegracaoDTO->getStrMapeamentoDescricao())>45){
        $objInfraException->adicionarValidacao('mapeamento da descri��o possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarStrMapeamentoSituacao(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitServicoIntegracaoDTO->getStrMapeamentoSituacao())){
      $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao(null);
    }else{
      $objMdLitServicoIntegracaoDTO->setStrMapeamentoSituacao(trim($objMdLitServicoIntegracaoDTO->getStrMapeamentoSituacao()));

      if (strlen($objMdLitServicoIntegracaoDTO->getStrMapeamentoSituacao())>45){
        $objInfraException->adicionarValidacao('mapeamento da situa��o possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarStrChaveUnica(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitServicoIntegracaoDTO->getStrChaveUnica())){
      $objInfraException->adicionarValidacao('chave �nica n�o informada.');
    }else{
      $objMdLitServicoIntegracaoDTO->setStrChaveUnica(trim($objMdLitServicoIntegracaoDTO->getStrChaveUnica()));

      if (strlen($objMdLitServicoIntegracaoDTO->getStrChaveUnica())>45){
        $objInfraException->adicionarValidacao('chave �nica possui tamanho superior a 45 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNomeIntegracao($objMdLitServicoIntegracaoDTO, $objInfraException);
      $this->validarStrEnderecoWsdl($objMdLitServicoIntegracaoDTO, $objInfraException);
      $this->validarStrOperacaoWsdl($objMdLitServicoIntegracaoDTO, $objInfraException);
      $this->validarStrMapeamentoCodigo($objMdLitServicoIntegracaoDTO, $objInfraException);
      $this->validarStrMapeamentoSigla($objMdLitServicoIntegracaoDTO, $objInfraException);
      $this->validarStrMapeamentoDescricao($objMdLitServicoIntegracaoDTO, $objInfraException);
      $this->validarStrMapeamentoSituacao($objMdLitServicoIntegracaoDTO, $objInfraException);
      $this->validarStrChaveUnica($objMdLitServicoIntegracaoDTO, $objInfraException);
        $arrModalidade = $objMdLitServicoIntegracaoDTO->getArrModalidade();
        $arrAbrangencia = $objMdLitServicoIntegracaoDTO->getArrAbrangencia();

      $objInfraException->lancarValidacoes();

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitServicoIntegracaoBD->cadastrar($objMdLitServicoIntegracaoDTO);
      //Auditoria

        $this->cadastrarServicoPorIntegracao($ret,$arrModalidade,$arrAbrangencia);
      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando servi�o de integra��o.',$e);
    }
  }

  private function cadastrarServicoPorIntegracao(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, $arrModalidade, $arrAbrangencia){
      //cadastrando o servico pela integra��o
      $objMdLitSoapClientRN = new MdLitSoapClienteRN($objMdLitServicoIntegracaoDTO->getStrEnderecoWsdl(), 'wsdl');
      $arrResultadoWebService = $objMdLitSoapClientRN->call($objMdLitServicoIntegracaoDTO->getStrOperacaoWsdl(), array());

      $arrResultadoWebService = $arrResultadoWebService['listaTipoServico'];
      //Tabela de resultado do web-service de listar servi�o
      $numRegistros = count($arrResultadoWebService);
      $objMdLitServicoRN  = new MdLitServicoRN();
      $objInfraException = new InfraException();
      $this->validarArrChaveUnica($objMdLitServicoIntegracaoDTO, $arrResultadoWebService, $objInfraException);
      $objInfraException->lancarValidacoes();

      if($numRegistros > 0){
          for ($i = 0; $i < $numRegistros; $i++) {
              $objMdLitServicoDTO = new MdLitServicoDTO();
              $objMdLitServicoDTO->setStrStaOrigem(MdLitServicoRN::$STA_ORIGEM_INTEGRACAO);
              $objMdLitServicoDTO->setStrCodigo($arrResultadoWebService[$i][$objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()]);
              $objMdLitServicoDTO->setStrSigla($arrResultadoWebService[$i][$objMdLitServicoIntegracaoDTO->getStrMapeamentoSigla()]);
              $objMdLitServicoDTO->setStrDescricao($arrResultadoWebService[$i][$objMdLitServicoIntegracaoDTO->getStrMapeamentoDescricao()]);
              $objMdLitServicoDTO->setStrSinAtivo('S');
              $objMdLitServicoDTO->setNumIdMdLitServicoIntegracao($objMdLitServicoIntegracaoDTO->getNumIdMdLitServicoIntegracao());
              $objMdLitServicoDTO->setArrModalidade($arrModalidade[$arrResultadoWebService[$i][$objMdLitServicoIntegracaoDTO->getStrChaveUnica()]]);
              $objMdLitServicoDTO->setArrAbrangencia($arrAbrangencia[$arrResultadoWebService[$i][$objMdLitServicoIntegracaoDTO->getStrChaveUnica()]]);

              $objMdLitServicoRN->cadastrar($objMdLitServicoDTO);
          }
      }
  }

  private function validarArrChaveUnica(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO, $arrResultadoWebService,InfraException $objInfraException){
      foreach ($arrResultadoWebService as $key1 => $resultado){
          foreach ($arrResultadoWebService as $key2 => $resultado2){
              if($key1 != $key2 && $resultado[$objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()] == $resultado2[$objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()]){
                  $objInfraException->adicionarValidacao('O c�digo '.$resultado[$objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()].' est� duplicado no resultado do web-service. Favor verifique o mapeamento novamente.' );
                  return;
              }
          }
      }
  }

  protected function alterarControlado(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitServicoIntegracaoDTO->isSetStrNomeIntegracao()){
        $this->validarStrNomeIntegracao($objMdLitServicoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitServicoIntegracaoDTO->isSetStrEnderecoWsdl()){
        $this->validarStrEnderecoWsdl($objMdLitServicoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitServicoIntegracaoDTO->isSetStrOperacaoWsdl()){
        $this->validarStrOperacaoWsdl($objMdLitServicoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitServicoIntegracaoDTO->isSetStrMapeamentoCodigo()){
        $this->validarStrMapeamentoCodigo($objMdLitServicoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitServicoIntegracaoDTO->isSetStrMapeamentoSigla()){
        $this->validarStrMapeamentoSigla($objMdLitServicoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitServicoIntegracaoDTO->isSetStrMapeamentoDescricao()){
        $this->validarStrMapeamentoDescricao($objMdLitServicoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitServicoIntegracaoDTO->isSetStrMapeamentoSituacao()){
        $this->validarStrMapeamentoSituacao($objMdLitServicoIntegracaoDTO, $objInfraException);
      }
      if ($objMdLitServicoIntegracaoDTO->isSetStrChaveUnica()){
        $this->validarStrChaveUnica($objMdLitServicoIntegracaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

        $arrModalidade = $objMdLitServicoIntegracaoDTO->getArrModalidade();
        $arrAbrangencia = $objMdLitServicoIntegracaoDTO->getArrAbrangencia();
        $objMdLitServicoDTO     = new MdLitServicoDTO();
        $objMdLitServicoRN      = new MdLitServicoRN();
        $objMdLitServicoDTO->retTodos(true);
        $objMdLitServicoDTO->setNumIdMdLitServicoIntegracao($objMdLitServicoIntegracaoDTO->getNumIdMdLitServicoIntegracao());

        $arrObjMdLitServicoDTO = $objMdLitServicoRN->listar($objMdLitServicoDTO);

        if($arrObjMdLitServicoDTO){
            $arrObjMdLitServicoDTO[0]->setBolIsAuditoria(false);
            $objMdLitServicoRN->excluirIntegracao($arrObjMdLitServicoDTO);
        }

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
        $this->cadastrarServicoPorIntegracao($objMdLitServicoIntegracaoDTO, $arrModalidade, $arrAbrangencia);
      $objMdLitServicoIntegracaoBD->alterar($objMdLitServicoIntegracaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando servi�o de integra��o.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitServicoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitServicoIntegracaoDTO);$i++){
        $objMdLitServicoIntegracaoBD->excluir($arrObjMdLitServicoIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo servi�o de integra��o.',$e);
    }
  }

  protected function consultarConectado(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitServicoIntegracaoBD->consultar($objMdLitServicoIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando servi�o de integra��o.',$e);
    }
  }

  protected function listarConectado(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitServicoIntegracaoBD->listar($objMdLitServicoIntegracaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando servi�os de integra��es.',$e);
    }
  }

  protected function contarConectado(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitServicoIntegracaoBD->contar($objMdLitServicoIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando servi�os de integra��es.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitServicoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitServicoIntegracaoDTO);$i++){
        $objMdLitServicoIntegracaoBD->desativar($arrObjMdLitServicoIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando servi�o de integra��o.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitServicoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitServicoIntegracaoDTO);$i++){
        $objMdLitServicoIntegracaoBD->reativar($arrObjMdLitServicoIntegracaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando servi�o de integra��o.',$e);
    }
  }

  protected function bloquearControlado(MdLitServicoIntegracaoDTO $objMdLitServicoIntegracaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_servico_integracao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitServicoIntegracaoBD->bloquear($objMdLitServicoIntegracaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando servi�o de integra��o.',$e);
    }
  }

 */
}
?>