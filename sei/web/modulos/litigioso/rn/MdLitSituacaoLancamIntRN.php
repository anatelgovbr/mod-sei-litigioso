<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 18/04/2018 - criado por ellyson.silva
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitSituacaoLancamIntRN extends InfraRN {

    public static $CODIGO                = 'mapeamento_codigo';
    public static $DESCRICAO             = 'mapeamento_descricao';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitSituacaoLancamInt(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamIntDTO->getNumIdMdLitSituacaoLancamInt())){
      $objInfraException->adicionarValidacao(' n�o informad.');
    }
  }

  private function validarStrNomeIntegracao(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamIntDTO->getStrNomeIntegracao())){
      $objInfraException->adicionarValidacao(' n�o informado o nome da integra��o.');
    }else{
      $objMdLitSituacaoLancamIntDTO->setStrNomeIntegracao(trim($objMdLitSituacaoLancamIntDTO->getStrNomeIntegracao()));

      if (strlen($objMdLitSituacaoLancamIntDTO->getStrNomeIntegracao())>30){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrEnderecoWsdl(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamIntDTO->getStrEnderecoWsdl())){
      $objInfraException->adicionarValidacao(' n�o informado o endere�o do WSDL.');
    }else{
      $objMdLitSituacaoLancamIntDTO->setStrEnderecoWsdl(trim($objMdLitSituacaoLancamIntDTO->getStrEnderecoWsdl()));

      if (strlen($objMdLitSituacaoLancamIntDTO->getStrEnderecoWsdl())>100){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrOperacaoWsdl(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamIntDTO->getStrOperacaoWsdl())){
      $objMdLitSituacaoLancamIntDTO->setStrOperacaoWsdl(null);
    }else{
      $objMdLitSituacaoLancamIntDTO->setStrOperacaoWsdl(trim($objMdLitSituacaoLancamIntDTO->getStrOperacaoWsdl()));

      if (strlen($objMdLitSituacaoLancamIntDTO->getStrOperacaoWsdl())>50){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrMapeamentoCodigo(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamIntDTO->getStrMapeamentoCodigo())){
      $objMdLitSituacaoLancamIntDTO->setStrMapeamentoCodigo(null);
    }else{
      $objMdLitSituacaoLancamIntDTO->setStrMapeamentoCodigo(trim($objMdLitSituacaoLancamIntDTO->getStrMapeamentoCodigo()));

      if (strlen($objMdLitSituacaoLancamIntDTO->getStrMapeamentoCodigo())>45){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarStrMapeamentoDescricao(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamIntDTO->getStrMapeamentoDescricao())){
      $objMdLitSituacaoLancamIntDTO->setStrMapeamentoDescricao(null);
    }else{
      $objMdLitSituacaoLancamIntDTO->setStrMapeamentoDescricao(trim($objMdLitSituacaoLancamIntDTO->getStrMapeamentoDescricao()));

      if (strlen($objMdLitSituacaoLancamIntDTO->getStrMapeamentoDescricao())>45){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarStrChaveUnica(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitSituacaoLancamIntDTO->getStrChaveUnica())){
      $objInfraException->adicionarValidacao(' n�o informad.');
    }else{
      $objMdLitSituacaoLancamIntDTO->setStrChaveUnica(trim($objMdLitSituacaoLancamIntDTO->getStrChaveUnica()));

      if (strlen($objMdLitSituacaoLancamIntDTO->getStrChaveUnica())>45){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 45 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNomeIntegracao($objMdLitSituacaoLancamIntDTO, $objInfraException);
      $this->validarStrEnderecoWsdl($objMdLitSituacaoLancamIntDTO, $objInfraException);
      $this->validarStrOperacaoWsdl($objMdLitSituacaoLancamIntDTO, $objInfraException);
      $this->validarStrMapeamentoCodigo($objMdLitSituacaoLancamIntDTO, $objInfraException);
      $this->validarStrMapeamentoDescricao($objMdLitSituacaoLancamIntDTO, $objInfraException);
      $this->validarStrChaveUnica($objMdLitSituacaoLancamIntDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamIntBD = new MdLitSituacaoLancamIntBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamIntBD->cadastrar($objMdLitSituacaoLancamIntDTO);
        $this->cadastrarSituacaoPorIntegracao($ret);
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando integra��o da situa��o do lan�amento.',$e);
    }
  }
    private function cadastrarSituacaoPorIntegracao(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO){
        //cadastrando o servico pela integra��o
        $objMdLitSoapClientRN = new MdLitSoapClienteRN($objMdLitSituacaoLancamIntDTO->getStrEnderecoWsdl(), 'wsdl');
        $arrResultadoWebService = $objMdLitSoapClientRN->enviarDados($objMdLitSituacaoLancamIntDTO->getStrOperacaoWsdl(), array());

        $arrResultadoWebService = $arrResultadoWebService['return'];
        //Tabela de resultado do web-service de listar servi�o
        $numRegistros = count($arrResultadoWebService);
        $objMdLitSituacaoLancamentoRN  = new MdLitSituacaoLancamentoRN();
        $objInfraException = new InfraException();
        $this->validarArrChaveUnica($objMdLitSituacaoLancamIntDTO, $arrResultadoWebService, $objInfraException);
        $objInfraException->lancarValidacoes();
        $coresSelecionados = $objMdLitSituacaoLancamIntDTO->getArrCoresSelecionados();

        if($numRegistros > 0){
            for ($i = 0; $i < $numRegistros; $i++) {
                $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                $objMdLitSituacaoLancamentoDTO->setStrStaOrigem(MdLitSituacaoLancamentoRN::$STA_ORIGEM_INTEGRACAO);
                $objMdLitSituacaoLancamentoDTO->setNumCodigo(trim($arrResultadoWebService[$i][$objMdLitSituacaoLancamIntDTO->getStrMapeamentoCodigo()]));
                $objMdLitSituacaoLancamentoDTO->setStrNome(trim($arrResultadoWebService[$i][$objMdLitSituacaoLancamIntDTO->getStrMapeamentoDescricao()]));
                $objMdLitSituacaoLancamentoDTO->setStrSinAtivo('S');
                $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancInt($objMdLitSituacaoLancamIntDTO->getNumIdMdLitSituacaoLancamInt());
                $objMdLitSituacaoLancamentoDTO->setStrSinCancelamento('N');

                if(trim($objMdLitSituacaoLancamIntDTO->getNumSituacaoCancelamento()) == $objMdLitSituacaoLancamentoDTO->getNumCodigo()){
                    $objMdLitSituacaoLancamentoDTO->setStrSinCancelamento('S');
                }

                if(isset($coresSelecionados[$objMdLitSituacaoLancamentoDTO->getNumCodigo()])){
                    $objMdLitSituacaoLancamentoDTO->setStrCorSituacao($coresSelecionados[$objMdLitSituacaoLancamentoDTO->getNumCodigo()]);
                }

                $objMdLitSituacaoLancamentoRN->cadastrar($objMdLitSituacaoLancamentoDTO);
            }
        }
    }

    private function validarArrChaveUnica(MdLitSituacaoLancamIntDTO $objMdLitServicoIntegracaoDTO, $arrResultadoWebService,InfraException $objInfraException){
        foreach ($arrResultadoWebService as $key1 => $resultado){
            foreach ($arrResultadoWebService as $key2 => $resultado2){
                if($key1 != $key2 && $resultado[$objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()] == $resultado2[$objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()]){
                    $objInfraException->adicionarValidacao('O c�digo '.$resultado[$objMdLitServicoIntegracaoDTO->getStrMapeamentoCodigo()].' est� duplicado no resultado do web-service. Favor verifique o mapeamento novamente.' );
                    return;
                }
            }
        }
    }

  protected function alterarControlado(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitSituacaoLancamIntDTO->isSetNumIdMdLitSituacaoLancamInt()){
        $this->validarNumIdMdLitSituacaoLancamInt($objMdLitSituacaoLancamIntDTO, $objInfraException);
      }
      if ($objMdLitSituacaoLancamIntDTO->isSetStrNomeIntegracao()){
        $this->validarStrNomeIntegracao($objMdLitSituacaoLancamIntDTO, $objInfraException);
      }
      if ($objMdLitSituacaoLancamIntDTO->isSetStrEnderecoWsdl()){
        $this->validarStrEnderecoWsdl($objMdLitSituacaoLancamIntDTO, $objInfraException);
      }
      if ($objMdLitSituacaoLancamIntDTO->isSetStrOperacaoWsdl()){
        $this->validarStrOperacaoWsdl($objMdLitSituacaoLancamIntDTO, $objInfraException);
      }
      if ($objMdLitSituacaoLancamIntDTO->isSetStrMapeamentoCodigo()){
        $this->validarStrMapeamentoCodigo($objMdLitSituacaoLancamIntDTO, $objInfraException);
      }
      if ($objMdLitSituacaoLancamIntDTO->isSetStrMapeamentoDescricao()){
        $this->validarStrMapeamentoDescricao($objMdLitSituacaoLancamIntDTO, $objInfraException);
      }
      if ($objMdLitSituacaoLancamIntDTO->isSetStrChaveUnica()){
        $this->validarStrChaveUnica($objMdLitSituacaoLancamIntDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

        $objMdLitSituacaoLancamentoDTO     = new MdLitSituacaoLancamentoDTO();
        $objMdLitSituacaoLancamentoRN      = new MdLitSituacaoLancamentoRN();
        $objMdLitSituacaoLancamentoDTO->retTodos(true);
        $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancInt($objMdLitSituacaoLancamIntDTO->getNumIdMdLitSituacaoLancamInt());

        $arrObjMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->listar($objMdLitSituacaoLancamentoDTO);

        if($arrObjMdLitSituacaoLancamentoDTO){
            $arrObjMdLitSituacaoLancamentoDTO[0]->setBolIsAuditoria(false);
            $objMdLitSituacaoLancamentoRN->excluirIntegracao($arrObjMdLitSituacaoLancamentoDTO);
        }

        $objMdLitServicoIntegracaoBD = new MdLitServicoIntegracaoBD($this->getObjInfraIBanco());
        $this->cadastrarSituacaoPorIntegracao($objMdLitSituacaoLancamIntDTO);
        $objMdLitServicoIntegracaoBD->alterar($objMdLitSituacaoLancamIntDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando integra��o da situa��o do lan�amento.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitSituacaoLancamIntDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamIntBD = new MdLitSituacaoLancamIntBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitSituacaoLancamIntDTO);$i++){
        $objMdLitSituacaoLancamIntBD->excluir($arrObjMdLitSituacaoLancamIntDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo integra��o da situa��o do lan�amento.',$e);
    }
  }

  protected function consultarConectado(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamIntBD = new MdLitSituacaoLancamIntBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamIntBD->consultar($objMdLitSituacaoLancamIntDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando integra��o da situa��o do lan�amento.',$e);
    }
  }

  protected function listarConectado(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamIntBD = new MdLitSituacaoLancamIntBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamIntBD->listar($objMdLitSituacaoLancamIntDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando integra��es das situa��es do lan�amento.',$e);
    }
  }

  protected function contarConectado(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamIntBD = new MdLitSituacaoLancamIntBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamIntBD->contar($objMdLitSituacaoLancamIntDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando integra��es das situa��es do lan�amento.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitSituacaoLancamIntDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamIntBD = new MdLitSituacaoLancamIntBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitSituacaoLancamIntDTO);$i++){
        $objMdLitSituacaoLancamIntBD->desativar($arrObjMdLitSituacaoLancamIntDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando integra��o da situa��o do lan�amento.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitSituacaoLancamIntDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamIntBD = new MdLitSituacaoLancamIntBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitSituacaoLancamIntDTO);$i++){
        $objMdLitSituacaoLancamIntBD->reativar($arrObjMdLitSituacaoLancamIntDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando integra��o da situa��o do lan�amento.',$e);
    }
  }

  protected function bloquearControlado(MdLitSituacaoLancamIntDTO $objMdLitSituacaoLancamIntDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_situacao_lancam_int_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitSituacaoLancamIntBD = new MdLitSituacaoLancamIntBD($this->getObjInfraIBanco());
      $ret = $objMdLitSituacaoLancamIntBD->bloquear($objMdLitSituacaoLancamIntDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando integra��o da situa��o do lan�amento.',$e);
    }
  }

 */
}
