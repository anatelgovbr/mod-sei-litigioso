<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Vers?o do Gerador de C?digo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitLancamentoRN extends InfraRN {
    public static $SISTEMA_ORIGEM = 'SEI';

    public static $TIPO_LANCAMENTO_PRINCIPAL = 'P';
    public static $TIPO_LANCAMENTO_PRINCIPAL_REDUCAO = 'R';
    public static $TIPO_LANCAMENTO_MAJORADO = 'M';
  
  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitSituacaoLancamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getNumIdMdLitSituacaoLancamento())){
      $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento(null);
    }
  }

  private function validarStrTipoLancamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrTipoLancamento())){
      $objMdLitLancamentoDTO->setStrTipoLancamento(null);
    }else{
      $objMdLitLancamentoDTO->setStrTipoLancamento(trim($objMdLitLancamentoDTO->getStrTipoLancamento()));

      if (strlen($objMdLitLancamentoDTO->getStrTipoLancamento())>1){
        $objInfraException->adicionarValidacao('tipo do lan?amento possui tamanho superior a 1 caracteres.');
      }
    }
  }

  private function validarStrSequencial(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if ($objMdLitLancamentoDTO->isSetStrSequencial() && $objMdLitLancamentoDTO->getStrSequencial()){
      $objMdLitLancamentoDTO->setStrSequencial(trim($objMdLitLancamentoDTO->getStrSequencial()));
      if (strlen($objMdLitLancamentoDTO->getStrSequencial())>45){
        $objInfraException->adicionarValidacao('sequencial possui tamanho superior a 45 caracteres.');
      }
    }
  }

  private function validarDtaDecisao(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaDecisao())){
      $objMdLitLancamentoDTO->setDtaDecisao(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaDecisao())){
        $objInfraException->adicionarValidacao('data da decis?o inv?lida.');
      }
    }
  }

  private function validarDtaIntimacao(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaIntimacao())){
      $objMdLitLancamentoDTO->setDtaIntimacao(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaIntimacao())){
        $objInfraException->adicionarValidacao('data da intima??o inv?lida.');
      }
    }
  }

  private function validarDtaVencimento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaVencimento())){
      $objMdLitLancamentoDTO->setDtaVencimento(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaVencimento())){
        $objInfraException->adicionarValidacao('data do vencimento inv?lida.');
      }
    }
  }

  private function validarDtaPrazoDefesa(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaPrazoDefesa())){
      $objMdLitLancamentoDTO->setDtaPrazoDefesa(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaPrazoDefesa())){
        $objInfraException->adicionarValidacao('data do prazo de defesa inv?lida.');
      }
    }
  }

  private function validarDtaUltimoPagamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaUltimoPagamento())){
      $objMdLitLancamentoDTO->setDtaUltimoPagamento(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaUltimoPagamento())){
        $objInfraException->adicionarValidacao('data do ultimo pagamento inv?lida.');
      }
    }
  }

  private function validarDblVlrLancamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDblVlrLancamento())){
      $objMdLitLancamentoDTO->setDblVlrLancamento(null);
    }
  }

  private function validarDblVlrDesconto(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDblVlrDesconto())){
      $objMdLitLancamentoDTO->setDblVlrDesconto(null);
    }
  }

  private function validarDblVlrPago(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDblVlrPago())){
      $objMdLitLancamentoDTO->setDblVlrPago(null);
    }
  }

  private function validarDblVlrSaldoDevedor(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDblVlrSaldoDevedor())){
      $objMdLitLancamentoDTO->setDblVlrSaldoDevedor(null);
    }
  }

  private function validarDthInclusao(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDthInclusao())){
      $objMdLitLancamentoDTO->setDthInclusao(null);
    }else{
      if (!InfraData::validarDataHora($objMdLitLancamentoDTO->getDthInclusao())){
        $objInfraException->adicionarValidacao('data de inclus?o inv?lida.');
      }
    }
  }

  private function validarStrLinkBoleto(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if ($objMdLitLancamentoDTO->isSetStrLinkBoleto() && $objMdLitLancamentoDTO->getStrLinkBoleto()){
      $objMdLitLancamentoDTO->setStrLinkBoleto(trim($objMdLitLancamentoDTO->getStrLinkBoleto()));

      if (strlen($objMdLitLancamentoDTO->getStrLinkBoleto())>2083){
        $objInfraException->adicionarValidacao('link do boleto possui tamanho superior a 2083 caracteres.');
      }
    }
  }

  private function validarStrNumeroInteressado(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrNumeroInteressado())){
      $objMdLitLancamentoDTO->setStrNumeroInteressado(null);
    }else{
      $objMdLitLancamentoDTO->setStrNumeroInteressado(trim($objMdLitLancamentoDTO->getStrNumeroInteressado()));

      if (strlen($objMdLitLancamentoDTO->getStrNumeroInteressado())>999){
        $objInfraException->adicionarValidacao('N?mero de complemento do interessado possui tamanho superior a 999 caracteres.');
      }
    }
  }

  private function validarStrSinConstituicaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva())){
      $objInfraException->adicionarValidacao('Sinalizador de constitui??o definitiva n?o informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva())){
        $objInfraException->adicionarValidacao('Sinalizador de constitui??o definitiva inv?lida.');
      }
    }
  }

  private function validarStrSinRenunciaRecorrer(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrSinRenunciaRecorrer())){
      $objInfraException->adicionarValidacao('Sinalizador de renunciar recorrer n?o informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitLancamentoDTO->getStrSinRenunciaRecorrer())){
        $objInfraException->adicionarValidacao('Sinalizador de renunciar recorrer inv?lido.');
      }
    }
  }

  private function validarDtaIntimacaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva())){
      $objMdLitLancamentoDTO->setDtaIntimacaoDefinitiva(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva())){
        $objInfraException->adicionarValidacao('data da intima??o definitiva inv?lida.');
      }
    }
  }

  private function validarDtaConstituicaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva())){
      $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva())){
        $objInfraException->adicionarValidacao('data da constitui??o definitiva inv?lida.');
      }
    }
  }

  private function validarStrJustificativa(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrJustificativa())){
      $objMdLitLancamentoDTO->setStrJustificativa(null);
    }else{
      $objMdLitLancamentoDTO->setStrJustificativa(trim($objMdLitLancamentoDTO->getStrJustificativa()));

      if (strlen($objMdLitLancamentoDTO->getStrJustificativa())>255){
        $objInfraException->adicionarValidacao('justificativa possui tamanho superior a 255 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitLancamentoDTO $objMdLitLancamentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitSituacaoLancamento($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrTipoLancamento($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrSequencial($objMdLitLancamentoDTO, $objInfraException);
      $this->validarDtaVencimento($objMdLitLancamentoDTO, $objInfraException);
      $this->validarDtaPrazoDefesa($objMdLitLancamentoDTO, $objInfraException);

      $this->validarDblVlrLancamento($objMdLitLancamentoDTO, $objInfraException);
      $this->validarDthInclusao($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrLinkBoleto($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrNumeroInteressado($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrSinConstituicaoDefinitiva($objMdLitLancamentoDTO, $objInfraException);
      $this->validarStrSinRenunciaRecorrer($objMdLitLancamentoDTO, $objInfraException);
     $this->validarStrJustificativa($objMdLitLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->cadastrar($objMdLitLancamentoDTO);
      $this->prepararHistoricoLancamento($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando lan?amento.',$e);
    }
  }

  private function prepararHistoricoLancamento(MdLitLancamentoDTO $objMdLitLancamentoDTO){
      $objMdlitHistoricLancamentoRN  = new MdLitHistoricLancamentoRN();
      $objMdlitHistoricLancamentoDTO = $objMdlitHistoricLancamentoRN->copiarObjLancamentoObjHistorico($objMdLitLancamentoDTO);
      $objMdlitHistoricLancamentoRN->prepararCadastrar($objMdlitHistoricLancamentoDTO);
  }

  protected function alterarControlado(MdLitLancamentoDTO $objMdLitLancamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitLancamentoDTO->isSetNumIdMdLitSituacaoLancamento()){
        $this->validarNumIdMdLitSituacaoLancamento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrTipoLancamento()){
        $this->validarStrTipoLancamento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrSequencial()){
        $this->validarStrSequencial($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaDecisao()){
        $this->validarDtaDecisao($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaIntimacao()){
        $this->validarDtaIntimacao($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaVencimento()){
        $this->validarDtaVencimento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaPrazoDefesa()){
        $this->validarDtaPrazoDefesa($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaUltimoPagamento()){
        $this->validarDtaUltimoPagamento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDblVlrLancamento()){
        $this->validarDblVlrLancamento($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDblVlrDesconto()){
        $this->validarDblVlrDesconto($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDblVlrPago()){
        $this->validarDblVlrPago($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDblVlrSaldoDevedor()){
        $this->validarDblVlrSaldoDevedor($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDthInclusao()){
        $this->validarDthInclusao($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrLinkBoleto()){
        $this->validarStrLinkBoleto($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrNumeroInteressado()){
        $this->validarStrNumeroInteressado($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrSinConstituicaoDefinitiva()){
        $this->validarStrSinConstituicaoDefinitiva($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrSinRenunciaRecorrer()){
        $this->validarStrSinRenunciaRecorrer($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaIntimacaoDefinitiva()){
        $this->validarDtaIntimacaoDefinitiva($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetDtaConstituicaoDefinitiva()){
        $this->validarDtaConstituicaoDefinitiva($objMdLitLancamentoDTO, $objInfraException);
      }
      if ($objMdLitLancamentoDTO->isSetStrJustificativa()){
        $this->validarStrJustificativa($objMdLitLancamentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $objMdLitLancamentoBD->alterar($objMdLitLancamentoDTO);

      $this->prepararHistoricoLancamento($objMdLitLancamentoDTO);

      return $objMdLitLancamentoDTO;

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando lan?amento.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitLancamentoDTO);$i++){
        $objMdLitLancamentoBD->excluir($arrObjMdLitLancamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo lan?amento.',$e);
    }
  }

  protected function consultarConectado(MdLitLancamentoDTO $objMdLitLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->consultar($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando lan?amento.',$e);
    }
  }

  protected function listarConectado(MdLitLancamentoDTO $objMdLitLancamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->listar($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando lan?amentos.',$e);
    }
  }

  protected function contarConectado(MdLitLancamentoDTO $objMdLitLancamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_lancamento_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->contar($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando lan?amentos.',$e);
    }
  }

    public function salvarLancamento($post)
    {
        // SO DEVE SEGUIR SE EXISTIR OU SE FOR UMA INCLUSAO
        if($post['hdnIdMdLitFuncionalidade'] == '' && !$post['hdnCreditosProcesso']){
            return;
        }
        
        // CASO JA EXISTA UM LANCAMENTO E O USUARIO SOMENTE VINCULE
        if(!empty($post['hdnTbVincularLancamento'])){
            return $this->vincularLancamento($post);
        }

        $objMdLitLancamentoDTO  = new MdLitLancamentoDTO();
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();
        $idLancamento = $post['hdnCreditosProcesso'];
        if($idLancamento){
            $objMdLitLancamentoDTO->setNumIdMdLitLancamento($post['hdnCreditosProcesso']);
            $objMdLitLancamentoDTO->retTodos(false);
            $objMdLitLancamentoDTO->retDblCpfInteressado();
            $objMdLitLancamentoDTO->retStrCnpjInteressado();
            $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);
        }

        //POPULA TODOS OS CAMPOS DE ACORODO COM O POST
        $objMdLitLancamentoDTO = $this->popularLancamento($objMdLitLancamentoDTO, $post);

        // REALIZA A OPERA??O DE SALVAR OU ALTERAR
        $bolNovoLancamento = !($objMdLitLancamentoDTO->isSetNumIdMdLitLancamento() && $objMdLitLancamentoDTO->getNumIdMdLitLancamento());
        $objMdLitLancamentoDTO = $bolNovoLancamento ? $objMdLitLancamentoRN->cadastrar($objMdLitLancamentoDTO) : $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);

        //QUANDO UM NOVO MAJORADO NAO FRACIONADO E CRIADO, ELE DEVE ADOTAR OS FRACIONADOS
        //ORFAOS DE UMA RAIZ DE MAJORADO ANTERIOR JA CANCELADA, VIRANDO A NOVA RAIZ DA CADEIA
        if ($bolNovoLancamento) {
            $this->adotarFracionadosMajoradoOrfaos($objMdLitLancamentoDTO);
        }

        //CASO O LANCAMENTO SEJA INICIAL E TENHA O SECUNDARIO DEVE SINCRONIZAR TODAS INFORMACOES EXETO O VALOR QUE ? CALCULADO A PARTIR DAS DECISOES CONTIDA NO LANCAMENTO
        $this->atualizarLancamentoSecundario($objMdLitLancamentoDTO);

        return $objMdLitLancamentoDTO;

    }

    private function adotarFracionadosMajoradoOrfaos($objMdLitLancamentoDTO)
    {
        if ($objMdLitLancamentoDTO->getStrTipoLancamento() != self::$TIPO_LANCAMENTO_MAJORADO) {
            return;
        }

        if ($objMdLitLancamentoDTO->isSetNumIdMdLitLancamentoInicial() && $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial()) {
            return;
        }

        //A ADOCAO SO PODE OCORRER ENTRE LANCAMENTOS DO MESMO "SLOT" DE FRACIONAMENTO
        //(MESMA DECISAO RAIZ DA CADEIA), NUNCA ENTRE SLOTS DIFERENTES DO MESMO PROCEDIMENTO.
        //SEM ESSA CHECAGEM, UM MAJORADO NOVO DE UM SLOT (EX.: A DECISAO QUE FICOU NA RAIZ DO
        //FRACIONAMENTO) PODE ADOTAR ERRADAMENTE OS FRACIONADOS ORFAOS DE OUTRO SLOT (EX.: UMA
        //DECISAO QUE FOI FRACIONADA EM OUTRO LANCAMENTO), MISTURANDO RODADAS DE MAJORACAO
        //DIFERENTES NO MESMO NUMERO DE EXIBICAO.
        $idSlotNovoMajorado = $this->obterIdLancamentoFracionamentoOrigemPorDecisoes($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
        if (!$idSlotNovoMajorado) {
            return;
        }

        $objMdLitLancamentoOutrasRaizesDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoOutrasRaizesDTO->setDblIdProcedimento($objMdLitLancamentoDTO->getDblIdProcedimento());
        $objMdLitLancamentoOutrasRaizesDTO->setStrTipoLancamento(self::$TIPO_LANCAMENTO_MAJORADO);
        $objMdLitLancamentoOutrasRaizesDTO->setNumIdMdLitLancamentoInicial(null, InfraDTO::$OPER_IGUAL);
        $objMdLitLancamentoOutrasRaizesDTO->setNumIdMdLitLancamento($objMdLitLancamentoDTO->getNumIdMdLitLancamento(), InfraDTO::$OPER_DIFERENTE);
        $objMdLitLancamentoOutrasRaizesDTO->retNumIdMdLitLancamento();
        $arrObjOutrasRaizesDTO = $this->listar($objMdLitLancamentoOutrasRaizesDTO);

        foreach ($arrObjOutrasRaizesDTO as $objRaizAntigaDTO) {
            $idRaizAntiga = $objRaizAntigaDTO->getNumIdMdLitLancamento();

            $objMdLitCancelaLancDTO = new MdLitCancelaLancamentoDTO();
            $objMdLitCancelaLancDTO->setNumIdMdLitLancamento($idRaizAntiga);
            $objMdLitCancelaLancDTO->retNumIdMdLitCancelaLancamento();
            $objMdLitCancelaLancDTO = (new MdLitCancelaLancamentoRN)->consultar($objMdLitCancelaLancDTO);

            if (!$objMdLitCancelaLancDTO) {
                continue;
            }

            $objMdLitLancamentoFilhoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoFilhoDTO->setNumIdMdLitLancamentoInicial($idRaizAntiga);
            $objMdLitLancamentoFilhoDTO->retNumIdMdLitLancamento();
            $arrObjLancamentoFilhoDTO = $this->listar($objMdLitLancamentoFilhoDTO);

            foreach ($arrObjLancamentoFilhoDTO as $objLancamentoFilhoDTO) {
                $idSlotFilho = $this->obterIdLancamentoFracionamentoOrigemPorDecisoes($objLancamentoFilhoDTO->getNumIdMdLitLancamento());
                if (!$idSlotFilho || $idSlotFilho != $idSlotNovoMajorado) {
                    continue;
                }

                $objLancamentoFilhoAlterarDTO = $this->retornaObjLancamento($objLancamentoFilhoDTO->getNumIdMdLitLancamento());
                $objLancamentoFilhoAlterarDTO->setNumIdMdLitLancamentoInicial($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
                $this->alterar($objLancamentoFilhoAlterarDTO);
            }
        }
    }

    private function popularLancamento($objMdLitLancamentoDTO, $post)
    {

        //PARAMETROS NECESSARIO SOMENTE EM LANCAMENTO NOVO
        if(!$post['hdnCreditosProcesso']){
            $objMdLitLancamentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objMdLitLancamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
            $objMdLitLancamentoDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
            $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento(null);
            $objMdLitLancamentoDTO->setStrSinSuspenso('N');
            $objMdLitLancamentoDTO->setDblVlrPago(0);
            $objMdLitLancamentoDTO->setStrTipoLancamento($this->calcularTipoLancamento($post));
            $objMdLitLancamentoDTO->setNumIdMdLitNumeroInteressado($post['selNumeroInteressado']);
            $objMdLitLancamentoDTO->setStrNumeroInteressado($this->buscarNumeroInteressadoPorIdMdLitNumeroInteressado($post['selNumeroInteressado']));
        }

        $objMdLitLancamentoDTO->setDtaDecursoPrazoRecurso($post['hdnDtDecursoPrazoRecurso']);
        $objMdLitLancamentoDTO->setNumPrazoSituacaoDefesa($post['hdnPrazoDefesa']);
        $objMdLitLancamentoDTO->setStrTipoPrazoDefesa($post['hdnTpPrazoDefesa']);
        $objMdLitLancamentoDTO->setNumPrazoSituacaoRecurso($post['hdnPrazoRecurso']);
        $objMdLitLancamentoDTO->setStrTipoPrazoRecurso($post['hdnTpPrazoRecurso']);
        $dtVencimento = trim($post['txtDtVencimento']);
        if (empty($dtVencimento)) {
            $numPrazoDiasSugVenc = $this->_recuperarPrazoDiasSugVenc($post['hdnIdTipoControle']);
            $dtBaseVencimento = (string) (empty($post['hdnDecisaoAplicacaoMulta']) ? InfraData::getStrDataAtual() : $post['hdnDecisaoAplicacaoMulta']);
            $dtVencimento = InfraData::calcularData($numPrazoDiasSugVenc, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dtBaseVencimento);
        }
        $objMdLitLancamentoDTO->setDtaVencimento($dtVencimento);
        $vlTotalMulta = InfraUtil::prepararDin($post['hdnVlTotalMulta']);
        if (!$objMdLitLancamentoDTO->isSetDblVlrLancamento() || $vlTotalMulta != $objMdLitLancamentoDTO->getDblVlrLancamento()) {
            $objMdLitLancamentoDTO->setDblVlrSaldoDevedor($vlTotalMulta);
        }
        $objMdLitLancamentoDTO->setDblVlrLancamento($vlTotalMulta);
        $objMdLitLancamentoDTO->setStrJustificativa($post['hdnJustificativaLancamento']);
        $objMdLitLancamentoDTO->setDblIdProcedimento($post['hdnIdProcedimento']);
        $objMdLitLancamentoDTO->setStrSinConstituicaoDefinitiva(isset($post['chkHouveConstituicao'])? 'S': 'N');
        $objMdLitLancamentoDTO->setStrSinRenunciaRecorrer(isset($post['chkReducaoRenuncia'])? 'S': 'N');
        $objMdLitLancamentoDTO->setStrSinConstituicaoDefinitiva(isset($post['chkHouveConstituicao'])? 'S': 'N');
        $objMdLitLancamentoDTO->setDtaIntimacao($post['hdnDtIntimacaoAplMulta']);
        $objMdLitLancamentoDTO->setDtaIntimacaoDefinitiva($post['txtDtIntimacaoConstituicao']);
        $objMdLitLancamentoDTO->setDtaPrazoDefesa($post['hdnDtDecursoPrazo']);
        $objMdLitLancamentoDTO->setDtaDecursoPrazoRecurso($post['hdnDtDecursoPrazoRecurso']);
        

        if(isset($post['txtDtConstituicao']) && !empty($post['txtDtConstituicao']) && isset($post['chkHouveConstituicao']))
            $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva($post['txtDtConstituicao']);

        //ATUALIZA A DATA DA DECISAO DE APLICA??O DA MULTA E VINCULA A DECISAO CASO NAO TENHA SIDO VINCULADO
        //SE A DATA ESTIVER VAZIA SIGNIFICA QUE A SITUACAO FOI REMOVIDA E DEVE REMOVER O VINCULO TAMBEM
        $objMdLitLancamentoDTO->setDtaDecisao(trim($post['hdnDecisaoAplicacaoMulta']));
        if ($post['hdnDecisaoAplicacaoMulta'] && !$objMdLitLancamentoDTO->isSetNumIdSituacaoDecisao()) {
            $objMdLitProcessoSituacaoDTO = (new MdLitProcessoSituacaoRN)->retornarUltimaSituacaoCadastradaProcesso($post['hdnIdProcedimento']);
            $objMdLitLancamentoDTO->setNumIdSituacaoDecisao($objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao());
        }
        if(!$post['hdnDecisaoAplicacaoMulta']){
            $objMdLitLancamentoDTO->setNumIdSituacaoDecisao(null);
        }

        // ATUALIZA DATA DA INTIMACAO DA MULTA E CASO SEJA UMA DATA NOVA E N?O VINCULADA A UMA SITUACAO DE INTIMA??O J? ? FEITO O VINCULO
        //SE A DATA ESTIVER VAZIA SIGNIFICA QUE A SITUACAO FOI REMOVIDA E DEVE REMOVER O VINCULO TAMBEM
        $objMdLitLancamentoDTO->setNumIdSituacaoIntimacao($post['hdnDtIntimacaoAplMulta']);
        if ($post['hdnDtIntimacaoAplMulta'] && $objMdLitLancamentoDTO->isSetNumIdSituacaoIntimacao()) {
            $objMdLitProcessoSituacaoDTO = (new MdLitProcessoSituacaoRN)->retornarUltimaSituacaoCadastradaProcesso($post['hdnIdProcedimento']);
            $objMdLitLancamentoDTO->setNumIdSituacaoIntimacao($objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao());
        }
        if(!$post['hdnDtIntimacaoAplMulta']){
            $objMdLitLancamentoDTO->setNumIdSituacaoIntimacao(null);
        }

        //ATUALIZA A DATA DA APRESENTA??O DO RECUROS E CASO N?O ESTEJA VINCULADO A UMA SITUA??O RECURSAL FAZ O VINCULO
        //SE A DATA ESTIVER VAZIA SIGNIFICA QUE A SITUACAO FOI REMOVIDA E DEVE REMOVER O VINCULO TAMBEM
        $objMdLitLancamentoDTO->setDtaApresentacaoRecurso($post['hdnDtApresentacaoRecurso']);
        if ($post['hdnDtApresentacaoRecurso'] && $objMdLitLancamentoDTO->isSetNumIdSituacaoRecurso()) {
            $objMdLitProcessoSituacaoDTO = (new MdLitProcessoSituacaoRN)->retornarUltimaSituacaoCadastradaProcesso($post['hdnIdProcedimento']);
            $objMdLitLancamentoDTO->setNumIdSituacaoRecurso($objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao());
        } 
        if(!$post['hdnDtApresentacaoRecurso']){
            $objMdLitLancamentoDTO->setNumIdSituacaoRecurso(null);
        }

        //ATUALIZA A DATA DA DECISAO DEFINITIVA DA APLICA??O DA MULTA E CASO SEJA CRIA??O DO LANCAMENTO BUSCA O ID DA SITUA??O PARA VINCULAR A DECISAO
        //SE A DATA ESTIVER VAZIA SIGNIFICA QUE A SITUACAO FOI REMOVIDA E DEVE REMOVER O VINCULO TAMBEM
        $objMdLitLancamentoDTO->setDtaDecisaoDefinitiva(trim($post['hdnDtDecisaoDefinitiva']));
        if ($post['hdnDtDecisaoDefinitiva'] && !$objMdLitLancamentoDTO->isSetNumIdMdLitSituacaoDecisaoDefin()) {
            $objMdLitProcessoSituacaoDTO = (new MdLitProcessoSituacaoRN)->retornarUltimaSituacaoCadastradaProcesso($post['hdnIdProcedimento']);
            $objMdLitLancamentoDTO->setNumIdMdLitSituacaoDecisaoDefin($objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao());
        }
        if(!$post['hdnDtDecisaoDefinitiva']){
            $objMdLitLancamentoDTO->setNumIdMdLitSituacaoDecisaoDefin(null);
        }

        //SE HOUVER ALTERA??O NA DATA DO DECURSO DO PRAZO PARA DEFESA DEVE ALTERAR EM TODOS LANCAMENTOS
        $objMdLitLancamentoDTO->setDtaPrazoDefesa($post['hdnDtDecursoPrazo']);
        if($post['hdnDtDecursoPrazo']){
          $this->atualizarDataDecursoDefesaTodosLancamentos($objMdLitLancamentoDTO->getDblIdProcedimento(), $post['hdnDtDecursoPrazo'], $post['hdnCreditosProcesso']);
        }

        return $objMdLitLancamentoDTO;

    }


    private function _recuperarPrazoDiasSugVenc($idTipoControle)
    {
        $objMdLitTipoControleDTO = new MdLitTipoControleDTO();
        $objMdLitTipoControleDTO->retNumPrazoDiasSugVenc();
        $objMdLitTipoControleDTO->setNumIdTipoControleLitigioso($idTipoControle);

        $objMdLitTipoControleBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
        $objMdLitTipoControleDTO = $objMdLitTipoControleBD->consultar($objMdLitTipoControleDTO);

        if (!$objMdLitTipoControleDTO || InfraString::isBolVazia($objMdLitTipoControleDTO->getNumPrazoDiasSugVenc())) {
            throw new InfraException('Prazo em Dias de Sugestao de Data de Vencimento nao parametrizado no Tipo de Controle Litigioso.');
        }

        return $objMdLitTipoControleDTO->getNumPrazoDiasSugVenc();
    }

    private function calcularTipoLancamento($post)
    {
        $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
        $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
        $objMdLitSituacaoLancamentoDTO->retTodos(false);
        $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);

        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setDblIdProcedimento($post['hdnIdProcedimento']);
        $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);

        if($objMdLitSituacaoLancamentoDTO)
            $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));

        $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

        return count($arrObjMdLitLancamentoDTO) > 0? self::$TIPO_LANCAMENTO_MAJORADO:self::$TIPO_LANCAMENTO_PRINCIPAL;
    }

    private function vincularLancamento($post)
    {
        //vincular Lancamento do lado do SEI tem que salvar na tabela md_lit_lancamento
        $arrTbVincularLancamento = current(PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbVincularLancamento']));

        $arrTbVincularLancamento['selNumeroInteressado'] = $post['selNumeroInteressado'];
        $arrTbVincularLancamento['hdnIdProcedimento'] = $post['hdnIdProcedimento'];
        $arrTbVincularLancamento['hdnDtIntimacaoAplMulta'] = $post['hdnDtIntimacaoAplMulta'];
        $arrTbVincularLancamento['hdnDtDecursoPrazoRecurso'] = $post['hdnDtDecursoPrazoRecurso'];
        $arrTbVincularLancamento['txtDtDecursoPrazo'] = $post['hdnDtDecursoPrazo'];
        $arrTbVincularLancamento['hdnDtDecursoPrazo'] = $post['hdnDtDecursoPrazo'];
        $arrTbVincularLancamento['id_situacao'] = $post['id_situacao'];
        return $this->vincularLancamento($arrTbVincularLancamento);
    }

    private function buscarNumeroInteressadoPorIdMdLitNumeroInteressado($idMdLitNumeroInteressado)
    {
        $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
        $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
        $objMdLitNumeroInteressadoDTO->setNumIdMdLitNumeroInteressado($idMdLitNumeroInteressado);
        $objMdLitNumeroInteressadoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitNumeroInteressadoDTO->retStrNumero();
        $objMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoRN->consultar($objMdLitNumeroInteressadoDTO);
        return $objMdLitNumeroInteressadoDTO->getStrNumero();
    }

    public function enviarFinanceiro($post, $objMdLitLancamentoDTO)
    {
        switch ($post['hdnIdMdLitFuncionalidade']) {
            case MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO:
                (new MdLitIncluirLancamentoRN)->incluirLancamento($objMdLitLancamentoDTO, $post);
                if ($objMdLitLancamentoDTO->getStrSinRenunciaRecorrer() == 'S'){
                    $post['selCreditosProcesso'] = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
                    $post['hdnIdMdLitFuncionalidade'] = 5;
                    $this->realizarRetificarCredito($post);
                }
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO:
                (new MdLitCancelaLancamentoRN())->cancelarLancamento($objMdLitLancamentoDTO, $post);
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO:
                (new MdLitRetificarLancamentoRN)->retificarLancamento($objMdLitLancamentoDTO, $post);
                
                // CASO TENHA LANCAMENTO SECUNDARIO DEVERA RETIFICA-LO TAMBEM
                $arrObjMdLitLancamentoSecundarioDTO = $this->retornaArrObjLancamentoSecundario($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
                foreach($arrObjMdLitLancamentoSecundarioDTO as $objMdLitLancamentoSecundarioDTO){
                  (new MdLitRetificarLancamentoRN)->retificarLancamento($objMdLitLancamentoSecundarioDTO, $post);
                }
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO:
                (new MdLitRecursoLancamentoRN())->suspenderLancamento($objMdLitLancamentoDTO, $post);

                // CASO TENHA LANCAMENTO SECUNDARIO DEVERA SUSPENDER TAMBEM
                $arrObjMdLitLancamentoSecundarioDTO = $this->retornaArrObjLancamentoSecundario($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
                foreach($arrObjMdLitLancamentoSecundarioDTO as $objMdLitLancamentoSecundarioDTO){
                  (new MdLitRecursoLancamentoRN)->suspenderLancamento($objMdLitLancamentoSecundarioDTO, $post);
                }
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_DENEGAR_RECURSO:
                (new MdLitRecursoLancamentoRN())->denegarRecurso($objMdLitLancamentoDTO, $post);

                // CASO TENHA LANCAMENTO SECUNDARIO DEVERA DENEGAR TAMBEM
                $arrObjMdLitLancamentoSecundarioDTO = $this->retornaArrObjLancamentoSecundario($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
                foreach($arrObjMdLitLancamentoSecundarioDTO as $objMdLitLancamentoSecundarioDTO){
                  (new MdLitRecursoLancamentoRN)->denegarRecurso($objMdLitLancamentoSecundarioDTO, $post);
                }
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_RECURSO:
                (new MdLitRecursoLancamentoRN())->cancelarRecurso($objMdLitLancamentoDTO, $post);

                // CASO TENHA LANCAMENTO SECUNDARIO DEVERA CANCELAR TAMBEM
                $arrObjMdLitLancamentoSecundarioDTO = $this->retornaArrObjLancamentoSecundario($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
                foreach($arrObjMdLitLancamentoSecundarioDTO as $objMdLitLancamentoSecundarioDTO){
                  (new MdLitRecursoLancamentoRN)->cancelarRecurso($objMdLitLancamentoSecundarioDTO, $post);
                }
                break;
        }

    }

    private function atualizarDataDecursoDefesaTodosLancamentos($idProcedimento, $novaData, $lancamentoSelecionado)
    {
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento(array($lancamentoSelecionado) , InfraDTO::$OPER_NOT_IN);
        $objMdLitLancamentoDTO->retTodos();

        $arrObjMdLitLancamento = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);
        foreach ($arrObjMdLitLancamento as $objMdLitLancamento) {
          $objMdLitLancamento->setDtaPrazoDefesa($novaData);
          $objMdLitLancamentoRN->alterar($objMdLitLancamento);
        }
    }

    private function alterarData($idMdLitLancamento, $dtIntimacaoAplMulta, $dtDtDecursoPrazoRecurso = null)
    {
        $bolSalvarLancamento = false;
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->retTodos(false);

        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idMdLitLancamento);

        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        if($objMdLitLancamentoDTO) {
        
          if($objMdLitLancamentoDTO->getDtaIntimacao() != $dtIntimacaoAplMulta){
            $objMdLitLancamentoDTO->setDtaIntimacao($dtIntimacaoAplMulta);
            $bolSalvarLancamento = true;
          }

          if($objMdLitLancamentoDTO->getDtaDecursoPrazoRecurso() != $dtDtDecursoPrazoRecurso){
            $objMdLitLancamentoDTO->setDtaDecursoPrazoRecurso($dtDtDecursoPrazoRecurso);
            $bolSalvarLancamento = true;
          }

          if($bolSalvarLancamento){
            $this->alterar($objMdLitLancamentoDTO);
          }
        }

    }

    public function retornaObjLancamento($idLancamento)
    {
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->retDblVlrLancamento();
        $objMdLitLancamentoDTO->retDblVlrSaldoDevedor();
        $objMdLitLancamentoDTO->retNumIdMdLitLancamentoInicial();
        $objMdLitLancamentoDTO->retDblCpfInteressado();
        $objMdLitLancamentoDTO->retStrCnpjInteressado();
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        if (!$objMdLitLancamentoDTO) {
            throw new Exception('Lancamento principal nao encontrado.');
        }

        return $objMdLitLancamentoDTO;
    }

    public function retornaArrObjLancamentoSecundario($idMdLitLancamentoInicial)
    {
        $arrRetorno = [];

        $objMdLitLancamentoSecundarioDTO  = new MdLitLancamentoDTO();
        $objMdLitLancamentoSecundarioDTO->setNumIdMdLitLancamentoInicial($idMdLitLancamentoInicial);
        $objMdLitLancamentoSecundarioDTO->retTodos(false);
        $objMdLitLancamentoSecundarioDTO->retDblCpfInteressado();
        $objMdLitLancamentoSecundarioDTO->retStrCnpjInteressado();
        $arrObjMdLitLancamentoSecundarioDTO = $this->listar($objMdLitLancamentoSecundarioDTO);

        foreach($arrObjMdLitLancamentoSecundarioDTO as $objMdLitLancamentoSecundarioDTO){
            $objMdLitCancelaLancDTO = new MdLitCancelaLancamentoDTO();
            $objMdLitCancelaLancDTO->setNumIdMdLitLancamento($objMdLitLancamentoSecundarioDTO->getNumIdMdLitLancamento());
            $objMdLitCancelaLancDTO->retNumIdMdLitCancelaLancamento();
            $objMdLitCancelaLancDTO = (new MdLitCancelaLancamentoRN)->consultar($objMdLitCancelaLancDTO);

            if($objMdLitCancelaLancDTO)
              continue;

            $arrRetorno[] = $objMdLitLancamentoSecundarioDTO;
        }

        return $arrRetorno;
    }

    public function localizarIdLancamentoSecundarioPorDecisoes($idLancamentoRaiz, $arrIdsDecisoes)
    {
        if (empty($arrIdsDecisoes)) {
            return null;
        }

        $arrObjMdLitLancamentoSecundarioDTO = $this->retornaArrObjLancamentoSecundario($idLancamentoRaiz);
        if (empty($arrObjMdLitLancamentoSecundarioDTO)) {
            return null;
        }

        $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();

        foreach ($arrObjMdLitLancamentoSecundarioDTO as $objMdLitLancamentoSecundarioDTO) {
            $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
            $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($objMdLitLancamentoSecundarioDTO->getNumIdMdLitLancamento());
            $objMdLitRelDecisLancamentDTO->retNumIdMdLitDecisao();
            $arrRelacoes = $objMdLitRelDecisLancamentRN->listar($objMdLitRelDecisLancamentDTO);
            $arrIdsDecisoesSecundario = InfraArray::converterArrInfraDTO($arrRelacoes, 'IdMdLitDecisao');

            if (!empty(array_intersect($arrIdsDecisoes, $arrIdsDecisoesSecundario))) {
                return $objMdLitLancamentoSecundarioDTO->getNumIdMdLitLancamento();
            }
        }

        return null;
    }

    /*
     * O Obj infra exception deve ser tratado posteriormente para retornar as exce??es como alerta para o usu?rio.
     **/
    public function realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException)
    {

        if(is_null($objMdLitIntegracaoDTO)){
            //$objInfraException->lancarValidacao('? necess?rio realizar a integra??o com a funcionalidade de Arrecada??o Lan?amento para Realizar o mesmo.');
            throw new InfraException('? necess?rio realizar a integra??o com a funcionalidade desejada. Favor entrar em contato com o administrador.');
        }

        if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO())){
            throw new InfraException('Os par?metros de entrada e sa?da n?o foram parametrizados. Contate o Gestor do Controle.');
        }

        if(InfraUtil::prepararDin($post['hdnVlCreditoNaoLancado']) == 0){
            return false;
        }

        //$objInfraException->lancarValidacao();
        return $objInfraException;
    }

    public function vincularLancamentoControlado($arrTbVincularLancamento)
    {
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setStrSequencial($arrTbVincularLancamento[0]);
        $objMdLitLancamentoDTO->setStrCodigoReceita($arrTbVincularLancamento[1]);
        $objMdLitLancamentoDTO->setDtaVencimento($arrTbVincularLancamento[2]);
        $objMdLitLancamentoDTO->setDblVlrLancamento(InfraUtil::prepararDin($arrTbVincularLancamento[3]));
        $objMdLitLancamentoDTO->setDblVlrDesconto(InfraUtil::prepararDin($arrTbVincularLancamento[4]));
        $objMdLitLancamentoDTO->setDblVlrPago(InfraUtil::prepararDin($arrTbVincularLancamento[5]));
        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor(InfraUtil::prepararDin($arrTbVincularLancamento[6]));
        $objMdLitLancamentoDTO->setDtaDecisao($arrTbVincularLancamento[7] ? $arrTbVincularLancamento[7]: null);
        $objMdLitLancamentoDTO->setStrSinConstituicaoDefinitiva($arrTbVincularLancamento[9] ? 'S':'N');
        $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva($arrTbVincularLancamento[9] ? $arrTbVincularLancamento[9]:null);
        $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento($arrTbVincularLancamento[10]);
        $objMdLitLancamentoDTO->setStrLinkBoleto($arrTbVincularLancamento[11]);
        $objMdLitLancamentoDTO->setStrNumeroInteressado($arrTbVincularLancamento[12]);
        $objMdLitLancamentoDTO->setNumIdMdLitNumeroInteressado($arrTbVincularLancamento['selNumeroInteressado']);
        $objMdLitLancamentoDTO->setStrJustificativa(null);
        $objMdLitLancamentoDTO->setNumIdSituacaoDecisao($arrTbVincularLancamento['id_situacao']);

        //verificar se j? existe lancamento para o processo
        $arrObjMdLitLancamentoDTO = $this->listarLancamentoPorProcedimento($arrTbVincularLancamento['hdnIdProcedimento']);

        $objMdLitLancamentoDTO->setDblIdProcedimento($arrTbVincularLancamento['hdnIdProcedimento']);
        $objMdLitLancamentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdLitLancamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objMdLitLancamentoDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
        $objMdLitLancamentoDTO->setStrTipoLancamento(count($arrObjMdLitLancamentoDTO) > 0? self::$TIPO_LANCAMENTO_MAJORADO:self::$TIPO_LANCAMENTO_PRINCIPAL);
        $objMdLitLancamentoDTO->setDtaIntimacao($arrTbVincularLancamento['hdnDtIntimacaoAplMulta']);
        $objMdLitLancamentoDTO->setDtaDecursoPrazoRecurso($arrTbVincularLancamento['hdnDtDecursoPrazoRecurso']);
        $objMdLitLancamentoDTO->setDtaPrazoDefesa($arrTbVincularLancamento['txtDtDecursoPrazo']);
        $objMdLitLancamentoDTO->setStrSinRenunciaRecorrer('N');
        //$objMdLitLancamentoDTO->setDtaPrazoDefesa($arrTbVincularLancamento['hdnDtDecursoPrazo']);
        $objMdLitLancamentoDTO->setStrSinSuspenso('N');


        $objMdLitLancamentoDTO = $this->cadastrar($objMdLitLancamentoDTO);
        return $objMdLitLancamentoDTO;

    }

    public function realizarRetificarCredito($post){
        if ( $post['selCreditosProcesso'] || $post['hdnCreditosProcesso'] ) {
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoRN = new MdLitLancamentoRN();

            $objMdLitLancamentoDTO->retTodos(false);
            $objMdLitLancamentoDTO->retDblCpfInteressado();
            $objMdLitLancamentoDTO->retStrCnpjInteressado();
            $objMdLitLancamentoDTO->retNumIdSituacaoDecisao();
            $objMdLitLancamentoDTO->retDtaIntimacao();
            $objMdLitLancamentoDTO->retNumIdSituacaoIntimacao();
            $objMdLitLancamentoDTO->setNumIdMdLitLancamento($post['selCreditosProcesso'] ? $post['selCreditosProcesso'] : $post['hdnCreditosProcesso']);

            $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

            (new MdLitRetificarLancamentoRN())->retificarLancamento($objMdLitLancamentoDTO, $post);

            return $objMdLitLancamentoDTO;
        }
    }

    public function retornaProtocoloFormatadoDocumentoPorSituacao($idSituacao)
    {
      $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
      $objMdLitProcessoSituacaoDTO->setNumIdMdLitProcessoSituacao($idSituacao);
      $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
      $objMdLitProcessoSituacaoDTO->retStrProtocoloFormatadoDocumento();

      $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
      $objMdLitProcessoSituacao = $objMdLitProcessoSituacaoRN->consultar($objMdLitProcessoSituacaoDTO);

      return $objMdLitProcessoSituacao->getStrProtocoloFormatadoDocumento();
    }

    public function retornaNumProcessoFormatado($idProcedimento)
    {
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
        $objProtocoloDTO->retTodos(false);
        $objProtocoloDTO->setNumMaxRegistrosRetorno(1);

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
//        $numProcesso = substr(str_replace(array('.', '/', '-'), '',$objProtocoloDTO->getStrProtocoloFormatado()), 0, -2);
        $numProcesso = str_replace(array('.', '/', '-'), '',$objProtocoloDTO->getStrProtocoloFormatado());

        return $numProcesso;
    }

    public function montarJustificativaAutomatica($strAcao, $idProcedimento)
    {
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
        $objProtocoloDTO->retTodos(false);
        $objProtocoloDTO->setNumMaxRegistrosRetorno(1);

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

        return 'Multa ' . $strAcao . ' com base no Processo n� ' . $objProtocoloDTO->getStrProtocoloFormatado();
    }

    public function retornaObjLancAlteracaoConsultaLanc($post)
    {
        $idLancamento = array_key_exists('selCreditosProcesso', $post) ? $post['selCreditosProcesso'] : '';
        if (!array_key_exists('selCreditosProcesso', $post)) {
            $idLancamento = array_key_exists('hdnCreditosProcesso', $post) ? $post['hdnCreditosProcesso'] : '';
        }

        if ($idLancamento != '')
        {
            $objMdLitLancamentoRN  = new MdLitLancamentoRN();
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento);
            $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitLancamentoDTO->retTodos();
            $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

            return $objMdLitLancamentoDTO;
        }
    }


    /**
     * @param $idProcedimento
     * @param $idLancamento
     * @return MdLitLancamentoDTO|bool| Retorna o objeto de MdLitLancamentoDTO se a consulta de lan?amento ter certo, FALSE caso contr?rio.
     */
    public function atualizarLancamento($idProcedimento, $idLancamento)
    {
        $objMdLitConsultarLancRN = new MdLitConsultarLancamentoRN();
        $objMdLitIntegracaoRN     = new MdLitIntegracaoRN();
        $objMdLitLancamentoDTO      = false;

       $arrObjMdLitLancamentoDTOAntigo    = $this->_retornaObjLancamentoPorProcedimento($idProcedimento);

       if(count($arrObjMdLitLancamentoDTOAntigo)){
           foreach ($arrObjMdLitLancamentoDTOAntigo as $objMdLitLancamentoDTOAntigo){
               if($objMdLitLancamentoDTOAntigo && !is_null($idLancamento) && $objMdLitLancamentoDTOAntigo->getNumIdMdLitLancamento() == $idLancamento){
                   $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);

                   $post = array('selCreditosProcesso' => $objMdLitLancamentoDTOAntigo->getNumIdMdLitLancamento(),'numInteressado' => $objMdLitLancamentoDTOAntigo->getStrNumeroInteressado(), 'chkReducaoRenuncia' => $objMdLitLancamentoDTOAntigo->getStrSinRenunciaRecorrer());

                   $objMdLitLancamentoDTO = $objMdLitConsultarLancRN->verificarAtualizarSituacaoLancamento($objMdLitIntegracaoDTO, $post);
               }
           }
       }
        return $objMdLitLancamentoDTO;
    }

    private function _retornaObjLancamentoPorProcedimento($idProcedimento){
        $objMdLitLancamentoDTO = null;

        if (!empty($idProcedimento))
        {
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);

            $objMdLitLancamentoDTO->retTodos();
//            $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitLancamentoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitLancamentoDTO = $this->listar($objMdLitLancamentoDTO);
        }


        return $objMdLitLancamentoDTO;
    }

    protected function valorLancadoPorProcedimentoConectado($idProcedimento){
        $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
        $objMdLitSituacaoLancamentoDTO->retTodos(false);

        $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
        $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
        if($objMdLitSituacaoLancamentoDTO){
            $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
        }
        $totalCreditoLancado = 0;

        $arrObjMdLitLancamentoDTO = $this->listar($objMdLitLancamentoDTO);

        $mdLitCancelaLancamentoRN = new MdLitCancelaLancamentoRN();
        foreach ($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){

            $mdLitCancelaLancamentoDTO = new MdLitCancelaLancamentoDTO();
            $mdLitCancelaLancamentoDTO->retTodos(false);
            $mdLitCancelaLancamentoDTO->set('IdMdLitLancamento', $objMdLitLancamentoDTO->get('IdMdLitLancamento'));
            $lancamentoCancelado = $mdLitCancelaLancamentoRN->listar($mdLitCancelaLancamentoDTO);
            //tratamento para desconsiderar os lancamentos cancelados no calculo totalCreditoLancado
            if($lancamentoCancelado){
                continue;
            }

            //calculando o valor lan?ado e o n?o lan?ado e a multa aplicada
            $creditoLancado = InfraUtil::prepararDbl($objMdLitLancamentoDTO->getDblVlrLancamento());
            $totalCreditoLancado = bcadd($totalCreditoLancado, $creditoLancado, 2);
        }

        return $totalCreditoLancado;
    }

    public function existeLancamentoMajorado($idProcedimento){
        $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
        $objMdLitSituacaoLancamentoDTO->retTodos(false);

        $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
        $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);

        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitLancamentoDTO->setStrTipoLancamento(MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO);
        if($objMdLitSituacaoLancamentoDTO){
            $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
        }

        $existeLancamento = $this->contar($objMdLitLancamentoDTO);
        if($existeLancamento)
            return true;

        return false;
    }

    protected function listarLancamentoPorProcedimentoConectado($idProcedimento){
        $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
        $objMdLitSituacaoLancamentoDTO->retTodos(false);

        $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
        $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);

        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setDblIdProcedimento($idProcedimento);
        if($objMdLitSituacaoLancamentoDTO){
            $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
        }

        $arrObjMdLitSituacaoLancamentoDTO = $this->listar($objMdLitLancamentoDTO);

        return $arrObjMdLitSituacaoLancamentoDTO;
    }

    public function atualizarDataDeSituacoesRelacionadaLancamento($objMdLitProcessoSitDTO)
    {
        $this->atualizarDataIntimacaoLancamento($objMdLitProcessoSitDTO);
        $this->atualizarDataDecisaoLancamento($objMdLitProcessoSitDTO);
        $this->atualizarDataRecursoLancamento($objMdLitProcessoSitDTO);
        $this->atualizarDataDecisaoDefinitivaLancamento($objMdLitProcessoSitDTO);
    }

    private function atualizarDataIntimacaoLancamento($objMdLitProcessoSitDTO)
    {
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdSituacaoIntimacao($objMdLitProcessoSitDTO->getNumIdMdLitProcessoSituacao());
        $objMdLitLancamentoDTO->retTodos();

        $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

        foreach($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){
            $objMdLitLancamentoDTO->setDtaIntimacao($objMdLitProcessoSitDTO->getDtaData());
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);
        }

    }

    private function atualizarDataDecisaoLancamento($objMdLitProcessoSitDTO)
    {
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdSituacaoDecisao($objMdLitProcessoSitDTO->getNumIdMdLitProcessoSituacao());
        $objMdLitLancamentoDTO->retTodos();

        $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

        foreach($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){
            $objMdLitLancamentoDTO->setDtaDecisao($objMdLitProcessoSitDTO->getDtaData());
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);
        }

    }

    private function atualizarDataRecursoLancamento($objMdLitProcessoSitDTO)
    {
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdSituacaoRecurso($objMdLitProcessoSitDTO->getNumIdMdLitProcessoSituacao());
        $objMdLitLancamentoDTO->retTodos();

        $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

        foreach($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){
            $objMdLitLancamentoDTO->setDtaApresentacaoRecurso($objMdLitProcessoSitDTO->getDtaData());
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);
        }

    }

    private function atualizarDataDecisaoDefinitivaLancamento($objMdLitProcessoSitDTO)
    {
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO->setNumIdMdLitSituacaoDecisaoDefin($objMdLitProcessoSitDTO->getNumIdMdLitProcessoSituacao());
        $objMdLitLancamentoDTO->retTodos();

        $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

        foreach($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){
            $objMdLitLancamentoDTO->setDtaDecisaoDefinitiva($objMdLitProcessoSitDTO->getDtaData());
            $this->alterar($objMdLitLancamentoDTO);
        }

    }

    private function atualizarLancamentoSecundario($objMdLitLancamentoIncialDTO)
    {
        $arrObjMdLitLancamentoSecundarioDTO = $this->retornaArrObjLancamentoSecundario($objMdLitLancamentoIncialDTO->getNumIdMdLitLancamento());
        foreach($arrObjMdLitLancamentoSecundarioDTO as $objMdLitLancamentoSecundarioDTO) {
            $objMdLitLancamentoSecundarioDTO = $this->popularLancamentoSecundarioInformacoesComuns($objMdLitLancamentoIncialDTO, $objMdLitLancamentoSecundarioDTO);
            $this->alterar($objMdLitLancamentoSecundarioDTO);
        }
    }

    public function fracionarMultasControlado($dados)
    {
        $idLancamentoPrincipal = (int) $dados['id_lancamento_inicial'];
        $idLancamentoSecundario = !empty($dados['id_lancamento_secundario']) ? (int) $dados['id_lancamento_secundario'] : null;
        $arrInicial = $this->normalizarDecisoesParaCamadaLancamento($idLancamentoPrincipal, array_values($dados['inicial']));
        $arrSecundario = !empty($dados['secundario']) ? $this->normalizarDecisoesParaCamadaLancamento($idLancamentoPrincipal, array_values($dados['secundario'])) : array();

        $bolHouveRedistribuicao = $this->realizarfracionamentoMultas($idLancamentoPrincipal, $arrInicial, $idLancamentoSecundario, $arrSecundario);

        if (!$bolHouveRedistribuicao) {
            //AO FRACIONAR DEVE SER FRACIONADO NA MESMA DIVISAO ENTRE OS BOLETOS INICIAIS E SECUNDARIOS
            //(QUANDO O LADO INICIAL OU O SECUNDARIO FOI REDISTRIBUIDO PARA O LANCAMENTO ANTERIOR DA
            //FAMILIA, ESSA REPLICACAO NAO SE APLICA: criarLancamentoPrincipalRedistribuido() JA TRATOU A
            //CAMADA ANTERIOR DIRETAMENTE, E REPETIR A REPLICACAO GENERICA AQUI SOBRESCREVERIA ESSE
            //TRATAMENTO COM A LOGICA ANTIGA.)
            $this->fracionarOutrasCamadasOrigem($idLancamentoPrincipal, $arrInicial, $arrSecundario);
            $this->fracionarOutrasCamadasPosterior($idLancamentoPrincipal, $arrInicial, $arrSecundario);
        }

    }

    private function realizarfracionamentoMultas($idLancamentoPrincipal, $arrInicial, $idLancamentoSecundario,  $arrSecundario)
    {
        try {
            $totalInicial = $this->calcularValorTotalDecisoes($this->recuperarArrayDecisoes($arrInicial));
            $bolInicialRedistribuido = bccomp($this->normalizarValorDecisao($totalInicial), '0.00', 2) < 0;

            if ($bolInicialRedistribuido) {
                $this->processarFracionamentoComInicialRedistribuido($idLancamentoPrincipal, $arrInicial, $arrSecundario);
                return true;
            }

            $totalSecundario = !empty($arrSecundario) ? $this->calcularValorTotalDecisoes($this->recuperarArrayDecisoes($arrSecundario)) : '0.00';
            $bolSecundarioRedistribuido = !empty($arrSecundario) && bccomp($this->normalizarValorDecisao($totalSecundario), '0.00', 2) < 0;

            if ($bolSecundarioRedistribuido) {
                // O LADO SECUNDARIO FICARIA COM VALOR NEGATIVO (EX.: UMA UNICA DECISAO CUJO VALOR
                // CAIU NA MAJORACAO SENDO MOVIDA SOZINHA PARA O LADO SECUNDARIO): MESMO TRATAMENTO
                // JA USADO PARA O LADO INICIAL NEGATIVO - GANHA BOLETO PROPRIO PELO VALOR ATUAL, E
                // O LANCAMENTO ANTERIOR DA FAMILIA (NAO O QUE ESTA SENDO FRACIONADO) RECEBE DE VOLTA
                // O QUE SOBROU.
                $this->criarLancamentoPrincipalRedistribuido($idLancamentoPrincipal, $arrSecundario);
            }

            $objMdLitLancamentoSecundarioDTO = null;

            // ATUALIZAR O LANCAMENTO INICIAL  
            $objMdLitLancamentoInicialDTO = $this->atualizarValorLancamentoInicial($idLancamentoPrincipal, $arrInicial);

            //SE ARRAY SECUNDARIO ESTIVER VAZIO DEVE CANCELAR TUDO DO SECUNDARIO POIS O BOLETO FOI UNIFICADO EM SOMENTE UM, O INICIAL
            if ($bolSecundarioRedistribuido) {
                // RELACIONAMENTO E VALOR DO LADO SECUNDARIO JA FORAM TRATADOS ACIMA POR
                // criarLancamentoPrincipalRedistribuido(); NAO CRIAR NEM CANCELAR NADA AQUI PARA ESSE LADO.
            } elseif(empty($arrSecundario)){
                if($idLancamentoSecundario){
                    $objMdLitLancamentoSecundarioDTO = $this->retornaObjLancamento($idLancamentoSecundario);
                    $post['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO;
                    $post['hdnIdMotivoCancelamento'] = 15; // outro
                    $post['hdnTxtMotivoCancelamento'] = 'Unifica��o de Boletos';
                    $post['hdnJustificativaCancelamento'] = 'Valor do Boleto secund�rio foi inclu�do no Boleto inicial.';
                    (new MdLitCancelaLancamentoRN())->cancelarLancamento($objMdLitLancamentoSecundarioDTO, $post);
                }
            } else {

                // ATUALIZAR OU CRIAR O LANCAMENTO SECUNDARIO
                $objMdLitLancamentoSecundarioDTO = $this->criarLancamentoSecundario($objMdLitLancamentoInicialDTO, $idLancamentoSecundario, $arrSecundario);
            }

            // ATUALIZAR SO RELACIONAMENTO ENTRE LANCAMENTOS E DECISOES
            $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();
            $objMdLitRelDecisLancamentRN->atualizarRelacionamentoDecisaoLancamento($objMdLitLancamentoInicialDTO->getNumIdMdLitLancamento(), $arrInicial);
            if ($objMdLitLancamentoSecundarioDTO) {
                $objMdLitRelDecisLancamentRN->atualizarRelacionamentoDecisaoLancamento($objMdLitLancamentoSecundarioDTO->getNumIdMdLitLancamento(), $arrSecundario);
            }

            return $bolSecundarioRedistribuido;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function processarFracionamentoComInicialRedistribuido($idLancamentoPrincipal, $arrInicial, $arrSecundario)
    {
        // O LADO INICIAL FICARIA COM VALOR NEGATIVO: NAO PODE VIRAR/PERMANECER BOLETO PROPRIO.
        // AS DECISOES DESSE LADO GANHAM UM BOLETO PRINCIPAL NOVO PELO VALOR ATUAL DELAS (NAO A
        // DIFERENCA), E O LANCAMENTO ANTERIOR DA FAMILIA RECEBE DE VOLTA O QUE SOBROU.
        $this->criarLancamentoPrincipalRedistribuido($idLancamentoPrincipal, $arrInicial);

        if (empty($arrSecundario)) {
            // NENHUMA DECISAO FICOU DO LADO POSITIVO: NAO SOBRA NADA QUE JUSTIFIQUE MANTER O
            // LANCAMENTO QUE ESTAVA SENDO FRACIONADO, ENTAO ELE E CANCELADO.
            $this->cancelarLancamentoFracionadoRedistribuido($idLancamentoPrincipal);
            return;
        }

        // O LANCAMENTO QUE ESTAVA SENDO FRACIONADO (idLancamentoPrincipal) MANTEM A PROPRIA
        // IDENTIDADE (EX.: "MAJORADO 1") E PASSA A REPRESENTAR SO O LADO SECUNDARIO/POSITIVO -
        // NAO CRIA UM BOLETO FRACIONADO SEPARADO NEM CANCELA O ORIGINAL NESSE CASO.
        $this->atualizarValorLancamentoInicial($idLancamentoPrincipal, $arrSecundario);

        $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();
        $objMdLitRelDecisLancamentRN->atualizarRelacionamentoDecisaoLancamento($idLancamentoPrincipal, $arrSecundario);
    }

    private function criarLancamentoPrincipalRedistribuido($idLancamentoOriginal, $arrDecisoes)
    {
        $arrObjDecisoesDTO = $this->recuperarArrayDecisoes($arrDecisoes);

        // AS DECISOES DESTE LADO GANHAM UM BOLETO PRINCIPAL PROPRIO, PELO VALOR ATUAL DE CADA
        // UMA (NAO A DIFERENCA/DELTA) - ESSE BOLETO PASSA A SER A NOVA REFERENCIA DELAS, NAO UM
        // INCREMENTO SOBRE OUTRO LANCAMENTO.
        $totalNovoLancamento = '0.00';
        $idLancamentoAnterior = null;
        foreach ($arrObjDecisoesDTO as $objDecisaoDTO) {
            $valorAtual = $this->normalizarValorDecisao(str_replace(',', '.', $objDecisaoDTO->getDblMulta()));
            $totalNovoLancamento = bcadd($totalNovoLancamento, $valorAtual, 2);

            $idOrigem = $objDecisaoDTO->getNumIdMdLitDecisaoOrigem();
            $idLancamentoOrigemDecisao = $idOrigem ? $this->consultarIdLancamentoPorDecisao($idOrigem) : null;
            if (!$idLancamentoOrigemDecisao) {
                throw new Exception('Nao foi possivel identificar o lancamento anterior da decisao ' . $objDecisaoDTO->getNumIdMdLitDecisao() . ' para o fracionamento.');
            }
            if ($idLancamentoAnterior === null) {
                $idLancamentoAnterior = $idLancamentoOrigemDecisao;
            } elseif ($idLancamentoAnterior != $idLancamentoOrigemDecisao) {
                throw new Exception('As decisoes deste lado do fracionamento pertencem a lancamentos anteriores diferentes; nao e possivel redistribuir automaticamente.');
            }
        }

        $objMdLitLancamentoAnteriorDTO = $this->retornaObjLancamento($idLancamentoAnterior);
        $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();

        // O LANCAMENTO ANTERIOR DA FAMILIA (EX.: O PRINCIPAL) PERDE AS DECISOES QUE GANHARAM
        // BOLETO PROPRIO AGORA, E TEM SEU VALOR RECALCULADO PELO QUE SOBROU VINCULADO A ELE.
        $objMdLitRelDecisLancamentFiltroDTO = new MdLitRelDecisLancamentDTO();
        $objMdLitRelDecisLancamentFiltroDTO->setNumIdMdLitLancamento($idLancamentoAnterior);
        $objMdLitRelDecisLancamentFiltroDTO->retNumIdMdLitDecisao();
        $arrDecisoesAnteriorAtual = InfraArray::converterArrInfraDTO(
            $objMdLitRelDecisLancamentRN->listar($objMdLitRelDecisLancamentFiltroDTO),
            'IdMdLitDecisao'
        );
        $arrIdsOrigemRedistribuidas = InfraArray::converterArrInfraDTO($arrObjDecisoesDTO, 'IdMdLitDecisaoOrigem');
        $arrDecisoesAnteriorNovo = array_values(array_diff($arrDecisoesAnteriorAtual, $arrIdsOrigemRedistribuidas));

        $totalAnteriorNovo = $this->calcularValorTotalDecisoes($this->recuperarArrayDecisoes($arrDecisoesAnteriorNovo));
        if (bccomp($this->normalizarValorDecisao($totalAnteriorNovo), '0.00', 2) <= 0) {
            $this->cancelarLancamentoPorValorZerado($objMdLitLancamentoAnteriorDTO);
        } else {
            $objMdLitLancamentoAnteriorDTO->setDblVlrLancamento($totalAnteriorNovo);
            $objMdLitLancamentoAnteriorDTO->setDblVlrSaldoDevedor($totalAnteriorNovo);
            $this->alterar($objMdLitLancamentoAnteriorDTO);

            $dados = array();
            $dados['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO;
            (new MdLitRetificarLancamentoRN)->retificarLancamento($objMdLitLancamentoAnteriorDTO, $dados);
        }
        $objMdLitRelDecisLancamentRN->atualizarRelacionamentoDecisaoLancamento($idLancamentoAnterior, $arrDecisoesAnteriorNovo);

        // NOVO BOLETO PRINCIPAL PARA AS DECISOES REDISTRIBUIDAS, DUPLICANDO OS METADADOS DO
        // LANCAMENTO ANTERIOR DA FAMILIA (MESMO PADRAO DE criarLancamentoSecundario()).
        $objNovoLancamentoDTO = $this->duplicarLancamento($objMdLitLancamentoAnteriorDTO);
        $objNovoLancamentoDTO->setDblVlrLancamento($totalNovoLancamento);
        $objNovoLancamentoDTO->setDblVlrSaldoDevedor($totalNovoLancamento);
        $objNovoLancamentoDTO->setNumIdMdLitLancamentoInicial($idLancamentoAnterior);
        $objNovoLancamentoDTO = $this->cadastrar($objNovoLancamentoDTO);

        $dados = array();
        $dados['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO;
        $dados['hdnIdTipoControle'] = $this->buscarIdTipoControlePorLancamento($objMdLitLancamentoAnteriorDTO->getDblIdProcedimento());
        (new MdLitIncluirLancamentoRN)->incluirLancamento($objNovoLancamentoDTO, $dados);

        $objMdLitRelDecisLancamentRN->atualizarRelacionamentoDecisaoLancamento($objNovoLancamentoDTO->getNumIdMdLitLancamento(), $arrDecisoes);

        return $objMdLitLancamentoAnteriorDTO;
    }

    private function cancelarLancamentoFracionadoRedistribuido($idLancamentoPrincipal)
    {
        $objMdLitLancamentoDTO = $this->retornaObjLancamento($idLancamentoPrincipal);

        $post = array();
        $post['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO;
        $post['hdnIdMotivoCancelamento'] = 15; // outro
        $post['hdnTxtMotivoCancelamento'] = 'Fracionamento de Boletos';
        $post['hdnJustificativaCancelamento'] = 'Decisoes redistribuidas para o lancamento de origem porque o fracionamento resultaria em valor negativo.';
        (new MdLitCancelaLancamentoRN())->cancelarLancamento($objMdLitLancamentoDTO, $post);
    }

    public function fracionarOutrasCamadasOrigem($idLancamento, $arrInicial, $arrSecundario)
    {
        $arrDecisoesOrigemInicial = $this->recuperarIdsDecisoesOrigem($arrInicial);
        $arrDecisoesOrigemSecundario = $this->recuperarIdsDecisoesOrigem($arrSecundario);

        if (empty($arrDecisoesOrigemInicial) && empty($arrDecisoesOrigemSecundario)) {
            return;
        }

        $objMdLitLancamentoDTO = $this->retornaLancamentoPrincipalAnterior($idLancamento);
        if (!$objMdLitLancamentoDTO) {
            return;
        }

        $idLancamentoSecundario = $this->localizarIdLancamentoSecundarioPorDecisoes($objMdLitLancamentoDTO->getNumIdMdLitLancamento(), $arrDecisoesOrigemSecundario);

        $this->realizarfracionamentoMultas($objMdLitLancamentoDTO->getNumIdMdLitLancamento(), $arrDecisoesOrigemInicial, $idLancamentoSecundario, $arrDecisoesOrigemSecundario);
        $this->fracionarOutrasCamadasOrigem($objMdLitLancamentoDTO->getNumIdMdLitLancamento(), $arrDecisoesOrigemInicial, $arrDecisoesOrigemSecundario);
    }

    public function fracionarOutrasCamadasPosterior($idLancamento, $arrInicial, $arrSecundario)
    {
        $arrFonteInicial = $arrInicial;
        $arrFonteSecundario = $arrSecundario;
        $idLancamentoReferencia = $idLancamento;
        $arrLancamentosProcessados = array();

        while (true) {
            $objMdLitLancamentoDTO = $this->retornaProximoLancamentoPrincipal($idLancamentoReferencia);
            if (!$objMdLitLancamentoDTO) {
                break;
            }

            $idLancamentoPosterior = $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
            if (isset($arrLancamentosProcessados[$idLancamentoPosterior])) {
                break;
            }

            $arrLancamentosProcessados[$idLancamentoPosterior] = true;
            $arrDecisoesInicial = $this->recuperarIdsDecisoesFilhasComValorLancamento($arrFonteInicial, $idLancamentoPosterior);
            $arrDecisoesSecundario = $this->recuperarIdsDecisoesFilhasComValorLancamento($arrFonteSecundario, $idLancamentoPosterior);

            if (!empty($arrDecisoesInicial) || !empty($arrDecisoesSecundario)) {
                $idLancamentoSecundario = $this->localizarIdLancamentoSecundarioPorDecisoes($idLancamentoPosterior, $arrDecisoesSecundario);
                $this->realizarfracionamentoMultas($idLancamentoPosterior, $arrDecisoesInicial, $idLancamentoSecundario, $arrDecisoesSecundario);
            }

            $arrFonteInicial = $arrDecisoesInicial;
            $arrFonteSecundario = $arrDecisoesSecundario;
            $idLancamentoReferencia = $idLancamentoPosterior;
        }
    }

    private function normalizarDecisoesParaCamadaLancamento($idLancamento, $arrIdsDecisoes)
    {
        $nivelLancamento = $this->calcularNivelLancamentoPrincipal($idLancamento);
        $arrIdsNormalizados = array();

        foreach ($arrIdsDecisoes as $idDecisao) {
            $arrCadeia = $this->recuperarCadeiaDecisao($idDecisao);
            if (empty($arrCadeia)) {
                continue;
            }

            $indice = $nivelLancamento < count($arrCadeia) ? $nivelLancamento : count($arrCadeia) - 1;
            $arrIdsNormalizados[] = $arrCadeia[$indice];
        }

        return array_values(array_unique($arrIdsNormalizados));
    }

    private function calcularNivelLancamentoPrincipal($idLancamento)
    {
        $objMdLitLancamentoReferenciaDTO = $this->retornaObjLancamento($idLancamento);

        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setDblIdProcedimento($objMdLitLancamentoReferenciaDTO->getDblIdProcedimento());
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento, InfraDTO::$OPER_MENOR_IGUAL);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamentoInicial(null, InfraDTO::$OPER_IGUAL);
        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
        $arrObjMdLitLancamentoDTO = $this->listar($objMdLitLancamentoDTO);

        $nivel = 0;
        foreach ($arrObjMdLitLancamentoDTO as $objLancamentoDTO) {
            if ($objLancamentoDTO->getNumIdMdLitLancamento() == $idLancamento) {
                return $nivel;
            }
            $nivel++;
        }

        return $nivel;
    }

    private function recuperarCadeiaDecisao($idDecisao)
    {
        $arrCadeia = array();
        $arrVisitados = array();
        $idAtual = $idDecisao;

        while ($idAtual && !isset($arrVisitados[$idAtual])) {
            $arrVisitados[$idAtual] = true;
            array_unshift($arrCadeia, (int) $idAtual);

            $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
            $objMdLitDecisaoDTO->setNumIdMdLitDecisao($idAtual);
            $objMdLitDecisaoDTO->retNumIdMdLitDecisaoOrigem();
            $objMdLitDecisaoDTO = (new MdLitDecisaoRN())->consultar($objMdLitDecisaoDTO);

            if (!$objMdLitDecisaoDTO || !$objMdLitDecisaoDTO->getNumIdMdLitDecisaoOrigem()) {
                break;
            }

            $idAtual = $objMdLitDecisaoDTO->getNumIdMdLitDecisaoOrigem();
        }

        return $arrCadeia;
    }

    private function recuperarIdsDecisoesFilhasDiretas($arrIdsOrigem)
    {
        if (empty($arrIdsOrigem)) {
            return array();
        }

        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        $objMdLitDecisaoDTO->setNumIdMdLitDecisaoOrigem($arrIdsOrigem, InfraDTO::$OPER_IN);
        $objMdLitDecisaoDTO->setDblMulta(null, InfraDTO::$OPER_DIFERENTE);
        $objMdLitDecisaoDTO->setOrdNumIdMdLitDecisao(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitDecisaoDTO->retNumIdMdLitDecisao();
        $arrObjMdLitDecisaoDTO = (new MdLitDecisaoRN())->listar($objMdLitDecisaoDTO);

        return InfraArray::converterArrInfraDTO($arrObjMdLitDecisaoDTO, 'IdMdLitDecisao');
    }
    private function retornaProximoLancamentoPrincipal($idLancamentoReferencia)
    {
        $objMdLitLancamentoReferenciaDTO = $this->retornaObjLancamento($idLancamentoReferencia);

        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamentoReferencia, InfraDTO::$OPER_MAIOR);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamentoInicial(null, InfraDTO::$OPER_IGUAL);
        $objMdLitLancamentoDTO->setDblIdProcedimento($objMdLitLancamentoReferenciaDTO->getDblIdProcedimento());
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->retDblCpfInteressado();
        $objMdLitLancamentoDTO->retStrCnpjInteressado();

        return $this->consultar($objMdLitLancamentoDTO);
    }

    private function retornaLancamentoPrincipalAnterior($idLancamentoReferencia)
    {
        $objMdLitLancamentoReferenciaDTO = $this->retornaObjLancamento($idLancamentoReferencia);

        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamentoReferencia, InfraDTO::$OPER_MENOR);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamentoInicial(null, InfraDTO::$OPER_IGUAL);
        $objMdLitLancamentoDTO->setDblIdProcedimento($objMdLitLancamentoReferenciaDTO->getDblIdProcedimento());
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->retDblCpfInteressado();
        $objMdLitLancamentoDTO->retStrCnpjInteressado();

        return $this->consultar($objMdLitLancamentoDTO);
    }

    private function recuperarIdsDecisoesOrigem($arrIdsDecisoes)
    {
        $arrIdsOrigem = array();
        $arrObjMdLitDecisaoDTO = $this->recuperarArrayDecisoes($arrIdsDecisoes);

        foreach ($arrObjMdLitDecisaoDTO as $objMdLitDecisaoDTO) {
            if (!$objMdLitDecisaoDTO->getNumIdMdLitDecisaoOrigem()) {
                continue;
            }

            $arrIdsOrigem[] = $objMdLitDecisaoDTO->getNumIdMdLitDecisaoOrigem();
        }

        return array_values(array_unique($arrIdsOrigem));
    }

    private function recuperarIdsDecisoesFilhasComValorLancamento($arrIdsOrigem, $idLancamento)
    {
        if (empty($arrIdsOrigem)) {
            return array();
        }

        $arrOrigem = array_flip($arrIdsOrigem);
        $arrIdsDecisoes = array();

        $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
        $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitRelDecisLancamentDTO->retNumIdMdLitDecisao();
        $arrObjMdLitRelDecisLancamentDTO = (new MdLitRelDecisLancamentRN())->listar($objMdLitRelDecisLancamentDTO);
        $arrIdsDecisoesLancamento = InfraArray::converterArrInfraDTO($arrObjMdLitRelDecisLancamentDTO, 'IdMdLitDecisao');
        $arrIdsDecisoesLancamento = array_values(array_unique(array_merge(
            $arrIdsDecisoesLancamento,
            $this->recuperarIdsDecisoesFilhasDiretas($arrIdsOrigem)
        )));

        if (empty($arrIdsDecisoesLancamento)) {
            return array();
        }

        $arrObjMdLitDecisaoDTO = $this->recuperarArrayDecisoes($arrIdsDecisoesLancamento);

        foreach ($arrObjMdLitDecisaoDTO as $objMdLitDecisaoDTO) {
            $idOrigem = $objMdLitDecisaoDTO->getNumIdMdLitDecisaoOrigem();
            if (!$idOrigem || !isset($arrOrigem[$idOrigem])) {
                continue;
            }

            $valorDecisao = MdLitLancamentoINT::calcularValorDecisao($objMdLitDecisaoDTO);
            $valorDecisao = str_replace(',', '.', $valorDecisao);
            if (floatval($valorDecisao) == 0.0) {
                continue;
            }

            $arrIdsDecisoes[] = $objMdLitDecisaoDTO->getNumIdMdLitDecisao();
        }

        return array_values(array_unique($arrIdsDecisoes));
    }

    public function atualizarValorLancamentoInicial($idLancamentoPrincipal, $arrInicial)
    {
        $totalInicial = $this->calcularValorTotalDecisoes($this->recuperarArrayDecisoes($arrInicial));
        $objMdLitLancamentoDTO = $this->retornaObjLancamento($idLancamentoPrincipal);

        if (bccomp($this->normalizarValorDecisao($totalInicial), '0.00', 2) == 0) {
            $this->cancelarLancamentoPorValorZerado($objMdLitLancamentoDTO);

            return $objMdLitLancamentoDTO;
        }

        $objMdLitLancamentoDTO->setDblVlrLancamento($totalInicial);
        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor($totalInicial);
        $this->alterar($objMdLitLancamentoDTO);

        $dados['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO;
        (new MdLitRetificarLancamentoRN)->retificarLancamento($objMdLitLancamentoDTO, $dados);

        return $objMdLitLancamentoDTO;

    }

    public function cancelarLancamentoPorValorZerado($objMdLitLancamentoDTO, $strMotivoCancelamento = null, $strJustificativaCancelamento = null)
    {
        $objMdLitLancamentoDTO->setDblVlrLancamento(0);
        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor(0);
        $this->alterar($objMdLitLancamentoDTO);

        $post['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO;
        $post['hdnIdMotivoCancelamento'] = 15; // outro
        $post['hdnTxtMotivoCancelamento'] = $strMotivoCancelamento ? $strMotivoCancelamento : 'Fracionamento de Boletos';
        $post['hdnJustificativaCancelamento'] = $strJustificativaCancelamento ? $strJustificativaCancelamento : 'Todas as decis�es deste lan�amento foram fracionadas para outro boleto.';
        (new MdLitCancelaLancamentoRN())->cancelarLancamento($objMdLitLancamentoDTO, $post);
    }

    public function criarLancamentoSecundario($objMdLitLancamentoInicialDTO, $idLancamentoSecundario, $arrSecundario)
    {

        $totalSecundario = $this->calcularValorTotalDecisoes($this->recuperarArrayDecisoes($arrSecundario));
        $objLancamentoSecundarioDTO = $idLancamentoSecundario ? $this->retornaObjLancamento($idLancamentoSecundario) : null;

        //ATUALIZA OU CRIA LANCAMENTO SECUNDARIO E ENVIA AO FINANCEIRO 
        if($objLancamentoSecundarioDTO){
            if (bccomp($this->normalizarValorDecisao($totalSecundario), '0.00', 2) == 0) {
                $this->cancelarLancamentoPorValorZerado($objLancamentoSecundarioDTO);
            } else {
                $objLancamentoSecundarioDTO->setDblVlrLancamento($totalSecundario);
                $objLancamentoSecundarioDTO->setDblVlrSaldoDevedor($totalSecundario);
                $this->alterar($objLancamentoSecundarioDTO);
                $dados['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO;
                (new MdLitRetificarLancamentoRN)->retificarLancamento($objLancamentoSecundarioDTO, $dados);
            }
        } else {
            $objLancamentoSecundarioDTO = $this->duplicarLancamento($objMdLitLancamentoInicialDTO);
            $objLancamentoSecundarioDTO->setDblVlrLancamento($totalSecundario);
            $objLancamentoSecundarioDTO->setDblVlrSaldoDevedor($totalSecundario);
            $objLancamentoSecundarioDTO->setNumIdMdLitLancamentoInicial($objMdLitLancamentoInicialDTO->getNumIdMdLitLancamento());
            $objLancamentoSecundarioDTO = $this->cadastrar($objLancamentoSecundarioDTO);

            $dados['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO;
            $dados['hdnIdTipoControle'] = $this->buscarIdTipoControlePorLancamento($objMdLitLancamentoInicialDTO->getDblIdProcedimento());
            (new MdLitIncluirLancamentoRN)->incluirLancamento($objLancamentoSecundarioDTO, $dados);
        }

        return $objLancamentoSecundarioDTO;

    }

    private function buscarIdTipoControlePorLancamento($idProcedimento)
    {
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO;
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitTipoControle();
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitProcessoSituacaoDTO = (new MdLitProcessoSituacaoRN)->consultar($objMdLitProcessoSituacaoDTO);
        return $objMdLitProcessoSituacaoDTO->getNumIdMdLitTipoControle();
    }

    private function calcularValorTotalDecisoes($arrObjMdLitDecisaoDTO)
    {
        $totalSecundario = '0.00';
        foreach ($arrObjMdLitDecisaoDTO as $objDecisaoDTO) {
            $valorMultaAnterior = MdLitLancamentoINT::calcularValorDecisao($objDecisaoDTO);
            $valorMultaAnterior = $this->normalizarValorDecisao($valorMultaAnterior);

            $totalSecundario = bcadd($totalSecundario, $valorMultaAnterior, 2);
        }

        return $totalSecundario;
    }

    private function recuperarArrayDecisoes($arrIds)
    {
        if (empty($arrIds)) {
            return array();
        }

        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        $objMdLitDecisaoDTO->retNumIdMdLitDecisao();
        $objMdLitDecisaoDTO->retNumIdMdLitDecisaoOrigem();
        $objMdLitDecisaoDTO->retDblMulta();
        $objMdLitDecisaoDTO->setNumIdMdLitDecisao($arrIds, InfraDTO::$OPER_IN);

        $objMdLitDecisaoRN = new MdLitDecisaoRN();
        return  $objMdLitDecisaoRN->listar($objMdLitDecisaoDTO);
    }

    private function duplicarLancamento($objMdLitLancamentoDTO)
    {
        $objNovoLancamentoDTO = new MdLitLancamentoDTO();

        // $objNovoLancamentoDTO->setStrSequencial(($objMdLitLancamentoDTO->isSetStrSequencial() ? $objMdLitLancamentoDTO->getStrSequencial() : null));
        // $objNovoLancamentoDTO->setStrLinkBoleto(($objMdLitLancamentoDTO->isSetStrLinkBoleto() ? $objMdLitLancamentoDTO->getStrLinkBoleto() : null));
        // $objNovoLancamentoDTO->setStrCodigoReceita(($objMdLitLancamentoDTO->isSetStrCodigoReceita() ? $objMdLitLancamentoDTO->getStrCodigoReceita() : null));
        // $objNovoLancamentoDTO->setDblVlrLancamento($totalSecundario);
        $objNovoLancamentoDTO->setDblVlrDesconto(($objMdLitLancamentoDTO->isSetDblVlrDesconto() ? $objMdLitLancamentoDTO->getDblVlrDesconto() : null));
        $objNovoLancamentoDTO->setDblVlrPago(($objMdLitLancamentoDTO->isSetDblVlrPago() ? $objMdLitLancamentoDTO->getDblVlrPago() : null));
        $objNovoLancamentoDTO->setDblVlrSaldoDevedor(($objMdLitLancamentoDTO->isSetDblVlrSaldoDevedor() ? $objMdLitLancamentoDTO->getDblVlrSaldoDevedor() : null));
        $objNovoLancamentoDTO->setNumIdMdLitSituacaoLancamento(($objMdLitLancamentoDTO->isSetNumIdMdLitSituacaoLancamento() ? $objMdLitLancamentoDTO->getNumIdMdLitSituacaoLancamento() : null));
        $objNovoLancamentoDTO->setStrTipoLancamento(($objMdLitLancamentoDTO->isSetStrTipoLancamento() ? $objMdLitLancamentoDTO->getStrTipoLancamento() : null));
        $objNovoLancamentoDTO->setDtaDecisao(($objMdLitLancamentoDTO->isSetDtaDecisao() ? $objMdLitLancamentoDTO->getDtaDecisao() : null));
        $objNovoLancamentoDTO->setDtaIntimacao(($objMdLitLancamentoDTO->isSetDtaIntimacao() ? $objMdLitLancamentoDTO->getDtaIntimacao() : null));
        $objNovoLancamentoDTO->setDtaDecursoPrazoRecurso(($objMdLitLancamentoDTO->isSetDtaDecursoPrazoRecurso() ? $objMdLitLancamentoDTO->getDtaDecursoPrazoRecurso() : null));
        $objNovoLancamentoDTO->setDtaVencimento(($objMdLitLancamentoDTO->isSetDtaVencimento() ? $objMdLitLancamentoDTO->getDtaVencimento() : null));
        $objNovoLancamentoDTO->setDtaPrazoDefesa(($objMdLitLancamentoDTO->isSetDtaPrazoDefesa() ? $objMdLitLancamentoDTO->getDtaPrazoDefesa() : null));
        $objNovoLancamentoDTO->setDtaUltimoPagamento(($objMdLitLancamentoDTO->isSetDtaUltimoPagamento() ? $objMdLitLancamentoDTO->getDtaUltimoPagamento() : null));
        $objNovoLancamentoDTO->setDthInclusao(($objMdLitLancamentoDTO->isSetDthInclusao() ? $objMdLitLancamentoDTO->getDthInclusao() : null));
        $objNovoLancamentoDTO->setStrNumeroInteressado(($objMdLitLancamentoDTO->isSetStrNumeroInteressado() ? $objMdLitLancamentoDTO->getStrNumeroInteressado() : null));
        $objNovoLancamentoDTO->setStrSinConstituicaoDefinitiva(($objMdLitLancamentoDTO->isSetStrSinConstituicaoDefinitiva() ? $objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva() : null));
        $objNovoLancamentoDTO->setStrSinRenunciaRecorrer(($objMdLitLancamentoDTO->isSetStrSinRenunciaRecorrer() ? $objMdLitLancamentoDTO->getStrSinRenunciaRecorrer() : null));
        $objNovoLancamentoDTO->setDtaIntimacaoDefinitiva(($objMdLitLancamentoDTO->isSetDtaIntimacaoDefinitiva() ? $objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva() : null));
        $objNovoLancamentoDTO->setDtaConstituicaoDefinitiva(($objMdLitLancamentoDTO->isSetDtaConstituicaoDefinitiva() ? $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva() : null));
        $objNovoLancamentoDTO->setStrJustificativa(($objMdLitLancamentoDTO->isSetStrJustificativa() ? $objMdLitLancamentoDTO->getStrJustificativa() : null));
        $objNovoLancamentoDTO->setNumIdUsuario(($objMdLitLancamentoDTO->isSetNumIdUsuario() ? $objMdLitLancamentoDTO->getNumIdUsuario() : null));
        $objNovoLancamentoDTO->setNumIdUnidade(($objMdLitLancamentoDTO->isSetNumIdUnidade() ? $objMdLitLancamentoDTO->getNumIdUnidade() : null));
        $objNovoLancamentoDTO->setDblIdProcedimento(($objMdLitLancamentoDTO->isSetDblIdProcedimento() ? $objMdLitLancamentoDTO->getDblIdProcedimento() : null));
        $objNovoLancamentoDTO->setStrSinSuspenso(($objMdLitLancamentoDTO->isSetStrSinSuspenso() ? $objMdLitLancamentoDTO->getStrSinSuspenso() : null));
        $objNovoLancamentoDTO->setNumIdMdLitIntegracao(($objMdLitLancamentoDTO->isSetNumIdMdLitIntegracao() ? $objMdLitLancamentoDTO->getNumIdMdLitIntegracao() : null));
        $objNovoLancamentoDTO->setNumIdMdLitNumeroInteressado(($objMdLitLancamentoDTO->isSetNumIdMdLitNumeroInteressado() ? $objMdLitLancamentoDTO->getNumIdMdLitNumeroInteressado() : null));
        $objNovoLancamentoDTO->setNumIdMdLitSituacaoDecisaoDefin(($objMdLitLancamentoDTO->isSetNumIdMdLitSituacaoDecisaoDefin() ? $objMdLitLancamentoDTO->getNumIdMdLitSituacaoDecisaoDefin() : null));
        $objNovoLancamentoDTO->setDtaDecisaoDefinitiva(($objMdLitLancamentoDTO->isSetDtaDecisaoDefinitiva() ? $objMdLitLancamentoDTO->getDtaDecisaoDefinitiva() : null));
        $objNovoLancamentoDTO->setDtaApresentacaoRecurso(($objMdLitLancamentoDTO->isSetDtaApresentacaoRecurso() ? $objMdLitLancamentoDTO->getDtaApresentacaoRecurso() : null));
        $objNovoLancamentoDTO->setNumIdSituacaoDecisao(($objMdLitLancamentoDTO->isSetNumIdSituacaoDecisao() ? $objMdLitLancamentoDTO->getNumIdSituacaoDecisao() : null));
        $objNovoLancamentoDTO->setNumIdSituacaoIntimacao(($objMdLitLancamentoDTO->isSetNumIdSituacaoIntimacao() ? $objMdLitLancamentoDTO->getNumIdSituacaoIntimacao() : null));
        $objNovoLancamentoDTO->setNumIdSituacaoRecurso(($objMdLitLancamentoDTO->isSetNumIdSituacaoRecurso() ? $objMdLitLancamentoDTO->getNumIdSituacaoRecurso() : null));
        $objNovoLancamentoDTO->setNumPrazoSituacaoDefesa(($objMdLitLancamentoDTO->isSetNumPrazoSituacaoDefesa() ? $objMdLitLancamentoDTO->getNumPrazoSituacaoDefesa() : null));
        $objNovoLancamentoDTO->setStrTipoPrazoDefesa(($objMdLitLancamentoDTO->isSetStrTipoPrazoDefesa() ? $objMdLitLancamentoDTO->getStrTipoPrazoDefesa() : null));
        $objNovoLancamentoDTO->setNumPrazoSituacaoRecurso(($objMdLitLancamentoDTO->isSetNumPrazoSituacaoRecurso() ? $objMdLitLancamentoDTO->getNumPrazoSituacaoRecurso() : null));
        $objNovoLancamentoDTO->setStrTipoPrazoRecurso(($objMdLitLancamentoDTO->isSetStrTipoPrazoRecurso() ? $objMdLitLancamentoDTO->getStrTipoPrazoRecurso() : null));
        $objNovoLancamentoDTO->setStrCnpjInteressado(($objMdLitLancamentoDTO->isSetStrCnpjInteressado() ? $objMdLitLancamentoDTO->getStrCnpjInteressado() : null));
        $objNovoLancamentoDTO->setDblCpfInteressado(($objMdLitLancamentoDTO->isSetDblCpfInteressado() ? $objMdLitLancamentoDTO->getDblCpfInteressado() : null));

        return $objNovoLancamentoDTO;
         
    }

    private function popularLancamentoSecundarioInformacoesComuns($objMdLitLancamentoIncialDTO, $objMdLitLancamentoSecundarioDTO)
    {

        // $objMdLitLancamentoIncialDTO->setDblVlrLancamento($totalSecundario);
        // $objMdLitLancamentoIncialDTO->setDblVlrDesconto($objMdLitLancamentoDTO->getDblVlrDesconto());
        // $objMdLitLancamentoIncialDTO->setDblVlrPago($objMdLitLancamentoDTO->getDblVlrPago());
        // $objMdLitLancamentoIncialDTO->setDblVlrSaldoDevedor($objMdLitLancamentoDTO->getDblVlrSaldoDevedor());
        // $objMdLitLancamentoIncialDTO->setDthInclusao($objMdLitLancamentoDTO->getDthInclusao());
        // $objMdLitLancamentoIncialDTO->setStrSequencial($objMdLitLancamentoDTO->getStrSequencial());
        // $objMdLitLancamentoIncialDTO->setStrSinSuspenso($objMdLitLancamentoDTO->getStrSinSuspenso());
        // $objMdLitLancamentoIncialDTO->setStrTipoLancamento($objMdLitLancamentoDTO->getStrTipoLancamento());
        // $objMdLitLancamentoIncialDTO->setStrLinkBoleto($objMdLitLancamentoDTO->getStrLinkBoleto());
        // $objMdLitLancamentoSecundarioIncialDTO->setDtaVencimento(($objMdLitLancamentoIncialDTO->isSetDtaVencimento() ? $objMdLitLancamentoIncialDTO->getDtaVencimento() : null));
        // $objMdLitLancamentoSecundarioIncialDTO->setDtaUltimoPagamento(($objMdLitLancamentoIncialDTO->isSetDtaUltimoPagamento() ? $objMdLitLancamentoIncialDTO->getDtaUltimoPagamento() : null));

        $objMdLitLancamentoSecundarioDTO->setDtaDecisao(($objMdLitLancamentoIncialDTO->isSetDtaDecisao() ? $objMdLitLancamentoIncialDTO->getDtaDecisao() : null));
        $objMdLitLancamentoSecundarioDTO->setDtaIntimacao(($objMdLitLancamentoIncialDTO->isSetDtaIntimacao() ? $objMdLitLancamentoIncialDTO->getDtaIntimacao() : null));
        $objMdLitLancamentoSecundarioDTO->setDtaDecursoPrazoRecurso(($objMdLitLancamentoIncialDTO->isSetDtaDecursoPrazoRecurso() ? $objMdLitLancamentoIncialDTO->getDtaDecursoPrazoRecurso() : null));
        $objMdLitLancamentoSecundarioDTO->setDtaPrazoDefesa(($objMdLitLancamentoIncialDTO->isSetDtaPrazoDefesa() ? $objMdLitLancamentoIncialDTO->getDtaPrazoDefesa() : null));
        $objMdLitLancamentoSecundarioDTO->setStrNumeroInteressado(($objMdLitLancamentoIncialDTO->isSetStrNumeroInteressado() ? $objMdLitLancamentoIncialDTO->getStrNumeroInteressado() : null));
        $objMdLitLancamentoSecundarioDTO->setStrSinConstituicaoDefinitiva(($objMdLitLancamentoIncialDTO->isSetStrSinConstituicaoDefinitiva() ? $objMdLitLancamentoIncialDTO->getStrSinConstituicaoDefinitiva() : null));
        $objMdLitLancamentoSecundarioDTO->setStrSinRenunciaRecorrer(($objMdLitLancamentoIncialDTO->isSetStrSinRenunciaRecorrer() ? $objMdLitLancamentoIncialDTO->getStrSinRenunciaRecorrer() : null));
        $objMdLitLancamentoSecundarioDTO->setDtaIntimacaoDefinitiva(($objMdLitLancamentoIncialDTO->isSetDtaIntimacaoDefinitiva() ? $objMdLitLancamentoIncialDTO->getDtaIntimacaoDefinitiva() : null));
        $objMdLitLancamentoSecundarioDTO->setDtaConstituicaoDefinitiva(($objMdLitLancamentoIncialDTO->isSetDtaConstituicaoDefinitiva() ? $objMdLitLancamentoIncialDTO->getDtaConstituicaoDefinitiva() : null));
        $objMdLitLancamentoSecundarioDTO->setStrJustificativa(($objMdLitLancamentoIncialDTO->isSetStrJustificativa() ? $objMdLitLancamentoIncialDTO->getStrJustificativa() : null));
        $objMdLitLancamentoSecundarioDTO->setNumIdUsuario(($objMdLitLancamentoIncialDTO->isSetNumIdUsuario() ? $objMdLitLancamentoIncialDTO->getNumIdUsuario() : null));
        $objMdLitLancamentoSecundarioDTO->setStrCodigoReceita(($objMdLitLancamentoIncialDTO->isSetStrCodigoReceita() ? $objMdLitLancamentoIncialDTO->getStrCodigoReceita() : null));
        $objMdLitLancamentoSecundarioDTO->setDblIdProcedimento(($objMdLitLancamentoIncialDTO->isSetDblIdProcedimento() ? $objMdLitLancamentoIncialDTO->getDblIdProcedimento() : null));
        $objMdLitLancamentoSecundarioDTO->setNumIdMdLitIntegracao(($objMdLitLancamentoIncialDTO->isSetNumIdMdLitIntegracao() ? $objMdLitLancamentoIncialDTO->getNumIdMdLitIntegracao() : null));
        $objMdLitLancamentoSecundarioDTO->setNumIdMdLitNumeroInteressado(($objMdLitLancamentoIncialDTO->isSetNumIdMdLitNumeroInteressado() ? $objMdLitLancamentoIncialDTO->getNumIdMdLitNumeroInteressado() : null));
        $objMdLitLancamentoSecundarioDTO->setNumIdMdLitSituacaoDecisaoDefin(($objMdLitLancamentoIncialDTO->isSetNumIdMdLitSituacaoDecisaoDefin() ? $objMdLitLancamentoIncialDTO->getNumIdMdLitSituacaoDecisaoDefin() : null));
        $objMdLitLancamentoSecundarioDTO->setDtaDecisaoDefinitiva(($objMdLitLancamentoIncialDTO->isSetDtaDecisaoDefinitiva() ? $objMdLitLancamentoIncialDTO->getDtaDecisaoDefinitiva() : null));
        $objMdLitLancamentoSecundarioDTO->setDtaApresentacaoRecurso(($objMdLitLancamentoIncialDTO->isSetDtaApresentacaoRecurso() ? $objMdLitLancamentoIncialDTO->getDtaApresentacaoRecurso() : null));
        $objMdLitLancamentoSecundarioDTO->setNumIdSituacaoDecisao(($objMdLitLancamentoIncialDTO->isSetNumIdSituacaoDecisao() ? $objMdLitLancamentoIncialDTO->getNumIdSituacaoDecisao() : null));
        $objMdLitLancamentoSecundarioDTO->setNumIdSituacaoIntimacao(($objMdLitLancamentoIncialDTO->isSetNumIdSituacaoIntimacao() ? $objMdLitLancamentoIncialDTO->getNumIdSituacaoIntimacao() : null));
        $objMdLitLancamentoSecundarioDTO->setNumIdSituacaoRecurso(($objMdLitLancamentoIncialDTO->isSetNumIdSituacaoRecurso() ? $objMdLitLancamentoIncialDTO->getNumIdSituacaoRecurso() : null));
        $objMdLitLancamentoSecundarioDTO->setNumPrazoSituacaoDefesa(($objMdLitLancamentoIncialDTO->isSetNumPrazoSituacaoDefesa() ? $objMdLitLancamentoIncialDTO->getNumPrazoSituacaoDefesa() : null));
        $objMdLitLancamentoSecundarioDTO->setStrTipoPrazoDefesa(($objMdLitLancamentoIncialDTO->isSetStrTipoPrazoDefesa() ? $objMdLitLancamentoIncialDTO->getStrTipoPrazoDefesa() : null));
        $objMdLitLancamentoSecundarioDTO->setNumPrazoSituacaoRecurso(($objMdLitLancamentoIncialDTO->isSetNumPrazoSituacaoRecurso() ? $objMdLitLancamentoIncialDTO->getNumPrazoSituacaoRecurso() : null));
        $objMdLitLancamentoSecundarioDTO->setStrTipoPrazoRecurso(($objMdLitLancamentoIncialDTO->isSetStrTipoPrazoRecurso() ? $objMdLitLancamentoIncialDTO->getStrTipoPrazoRecurso() : null));
        $objMdLitLancamentoSecundarioDTO->setStrCnpjInteressado(($objMdLitLancamentoIncialDTO->isSetStrCnpjInteressado() ? $objMdLitLancamentoIncialDTO->getStrCnpjInteressado() : null));
        $objMdLitLancamentoSecundarioDTO->setDblCpfInteressado(($objMdLitLancamentoIncialDTO->isSetDblCpfInteressado() ? $objMdLitLancamentoIncialDTO->getDblCpfInteressado() : null));

        return $objMdLitLancamentoSecundarioDTO;
         
    }

    public function identificarLancamentosDecisoesAlteradas($post)
    {
        if (empty($post['hdnTbDecisao'])) {
            return array();
        }

        $arrDecisao = PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbDecisao']);
        $arrLancamentos = array();
        $arrDecisoesSemVinculo = array();
        $arrBoletosIniciaisSemAlteracao = array();
        $bolNovaSituacaoDecisaoPost = $this->verificarNovaSituacaoDecisaoPost($post);

        foreach ($arrDecisao as $decisao) {
            $idDecisaoComparacao = $this->obterIdDecisaoComparacao($decisao);
            if (!$idDecisaoComparacao) {
                //DECISAO SEM id_decisao E SEM id_decisao_origem: E UMA DECISAO GENUINAMENTE
                //NOVA. NAO EXISTE CONTROLE NA MODAL DE CADASTRO DE DECISOES PARA O USUARIO
                //ESCOLHER A QUAL BOLETO/LANCAMENTO ELA PERTENCE, ENTAO E RESOLVIDA DEPOIS,
                //JUNTO COM O BOLETO "PAI" JA IDENTIFICADO PELAS DEMAIS DECISOES DESTE POST.
                $arrDecisoesSemVinculo[] = $decisao;
                continue;
            }

            $idLancamento = $this->consultarIdLancamentoPorDecisao($idDecisaoComparacao);
            if (!$idLancamento) {
                $idLancamento = $this->obterIdLancamentoDecisaoPost($decisao);
            }

            if (!$idLancamento) {
                continue;
            }

            $valorNovo = $this->normalizarValorDecisao($decisao[4]);
            $valorAtual = $this->consultarValorDecisao($idDecisaoComparacao);
            $diferenca = bcsub($valorNovo, $valorAtual, 2);

            if (bccomp($diferenca, '0.00', 2) == 0) {
                //BOLETO "REAFIRMADO" SEM ALTERACAO DE VALOR NESTA RODADA. SE ESTE LANCAMENTO
                //FOR ELE MESMO O BOLETO INICIAL (A RAIZ DA FAMILIA DE FRACIONAMENTO), GUARDA
                //A REFERENCIA PARA SERVIR DE ANCORA DO GRUPO MAJORADO CASO OUTRAS DECISOES DA
                //MESMA FAMILIA SEJAM MAJORADAS NESTA MESMA RODADA (VER
                //ancorarBoletosIniciaisSemAlteracao) - SEM ISSO, UM BOLETO SECUNDARIO PODE
                //VIRAR "RAIZ" DO GRUPO POR ACIDENTE (ORDEM DE PROCESSAMENTO).
                if ($bolNovaSituacaoDecisaoPost && !isset($arrBoletosIniciaisSemAlteracao[$idLancamento])) {
                    $objMdLitLancamentoOrigemDTO = $this->retornaObjLancamento($idLancamento);
                    if ($objMdLitLancamentoOrigemDTO && $this->obterIdRaizLancamentoFracionado($objMdLitLancamentoOrigemDTO) == $idLancamento) {
                        $arrBoletosIniciaisSemAlteracao[$idLancamento] = true;
                    }
                }
                continue;
            }

            $bolNovaSituacaoDecisao = $bolNovaSituacaoDecisaoPost || (!is_numeric($decisao[0]) && isset($decisao[20]) && is_numeric($decisao[20]));
            $this->acumularDiferencaLancamento($arrLancamentos, $idLancamento, $idDecisaoComparacao, $diferenca, $bolNovaSituacaoDecisao);
        }

        if (!empty($arrBoletosIniciaisSemAlteracao)) {
            //RESOLVIDO ANTES DAS DECISOES SEM VINCULO DE PROPOSITO: PARA UMA DECISAO NOVA
            //PREFERIR O BOLETO INICIAL (MENOR ID) QUANDO ELE ESTIVER PARTICIPANDO DESTA
            //RODADA, O ANCORAMENTO PRECISA JA TER SIDO ADICIONADO A $arrLancamentos ANTES DE
            //resolverIdLancamentoPaiParaDecisoesNovas() CALCULAR O MENOR ID DISPONIVEL.
            $this->ancorarBoletosIniciaisSemAlteracao($arrLancamentos, $arrBoletosIniciaisSemAlteracao);
        }

        if (!empty($arrDecisoesSemVinculo)) {
            $idLancamentoPai = $this->resolverIdLancamentoPaiParaDecisoesNovas($arrLancamentos, $post);
            if ($idLancamentoPai) {
                foreach ($arrDecisoesSemVinculo as $decisao) {
                    $valorNovo = $this->normalizarValorDecisao($decisao[4]);
                    if (bccomp($valorNovo, '0.00', 2) == 0) {
                        continue;
                    }
                    $this->acumularDiferencaLancamento($arrLancamentos, $idLancamentoPai, null, $valorNovo, true);
                }
            }
        }

        return $arrLancamentos;
    }

    private function ancorarBoletosIniciaisSemAlteracao(&$arrLancamentos, $arrBoletosIniciaisSemAlteracao)
    {
        //PARA CADA FAMILIA DE FRACIONAMENTO QUE VAI RECEBER MAJORACAO NESTA RODADA (JA
        //PRESENTE EM $arrLancamentos), GARANTE QUE O BOLETO INICIAL DA FAMILIA TAMBEM ENTRE
        //NO AGRUPAMENTO COM DIFERENCA ZERO, PARA SEMPRE VIRAR A ANCORA/RAIZ DO GRUPO
        //"Majorado N" (processarLancamentosDecisoesAlteradas trata 'ancora_boleto_inicial'
        //como caso especial que cria o lancamento majorado mesmo com diferenca zero).
        $arrFamiliasComMajoracao = array();
        foreach (array_keys($arrLancamentos) as $idLancamentoAlterado) {
            $objMdLitLancamentoAlteradoDTO = $this->retornaObjLancamento($idLancamentoAlterado);
            if (!$objMdLitLancamentoAlteradoDTO) {
                continue;
            }
            $arrFamiliasComMajoracao[$this->obterIdRaizLancamentoFracionado($objMdLitLancamentoAlteradoDTO)] = true;
        }

        foreach (array_keys($arrBoletosIniciaisSemAlteracao) as $idLancamentoInicial) {
            $idLancamentoInicial = intval($idLancamentoInicial);
            if (isset($arrLancamentos[$idLancamentoInicial]) || !isset($arrFamiliasComMajoracao[$idLancamentoInicial])) {
                continue;
            }

            $arrLancamentos[$idLancamentoInicial] = array(
                'diferenca' => '0.00',
                'diferenca_positiva' => '0.00',
                'diferenca_negativa' => '0.00',
                'decisoes_alteradas' => array(),
                'decisoes_positivas' => array(),
                'decisoes_negativas' => array(),
                'nova_situacao_decisao' => true,
                'ancora_boleto_inicial' => true
            );
        }
    }

    private function acumularDiferencaLancamento(&$arrLancamentos, $idLancamento, $idDecisaoComparacao, $diferenca, $bolNovaSituacaoDecisao)
    {
        if (!isset($arrLancamentos[$idLancamento])) {
            $arrLancamentos[$idLancamento] = array(
                'diferenca' => '0.00',
                'diferenca_positiva' => '0.00',
                'diferenca_negativa' => '0.00',
                'decisoes_alteradas' => array(),
                'decisoes_positivas' => array(),
                'decisoes_negativas' => array(),
                'nova_situacao_decisao' => $bolNovaSituacaoDecisao
            );
        }

        $arrLancamentos[$idLancamento]['diferenca'] = bcadd($arrLancamentos[$idLancamento]['diferenca'], $diferenca, 2);
        if ($idDecisaoComparacao) {
            $arrLancamentos[$idLancamento]['decisoes_alteradas'][] = $idDecisaoComparacao;
        }
        if (bccomp($diferenca, '0.00', 2) > 0) {
            $arrLancamentos[$idLancamento]['diferenca_positiva'] = bcadd($arrLancamentos[$idLancamento]['diferenca_positiva'], $diferenca, 2);
            if ($idDecisaoComparacao) {
                $arrLancamentos[$idLancamento]['decisoes_positivas'][] = $idDecisaoComparacao;
            }
        } elseif (bccomp($diferenca, '0.00', 2) < 0) {
            $arrLancamentos[$idLancamento]['diferenca_negativa'] = bcadd($arrLancamentos[$idLancamento]['diferenca_negativa'], $diferenca, 2);
            if ($idDecisaoComparacao) {
                $arrLancamentos[$idLancamento]['decisoes_negativas'][] = $idDecisaoComparacao;
            }
        }
        if ($bolNovaSituacaoDecisao) {
            $arrLancamentos[$idLancamento]['nova_situacao_decisao'] = true;
        }
    }

    private function resolverIdLancamentoPaiParaDecisoesNovas($arrLancamentos, $post)
    {
        //PREFERE O BOLETO/LANCAMENTO JA IDENTIFICADO PELAS DEMAIS DECISOES ALTERADAS NESTE
        //MESMO POST: ENTRE OS AGRUPAMENTOS JA PRESENTES, O DE MENOR ID E SEMPRE O MAIS
        //ANTIGO DA FAMILIA DE FRACIONAMENTO (A RAIZ FOI SEMPRE CRIADA PRIMEIRO), OU SEJA,
        //O "BOLETO PAI"/MAJORADO ATUAL DAQUELA FAMILIA.
        $idLancamentoMaisAntigo = $this->obterMenorId(array_keys($arrLancamentos));
        if ($idLancamentoMaisAntigo) {
            return $idLancamentoMaisAntigo;
        }

        //SEM NENHUMA OUTRA DECISAO ALTERADA NO MESMO POST PARA SERVIR DE REFERENCIA, USA O
        //LANCAMENTO RAIZ ATIVO MAIS RECENTE DO PROCEDIMENTO COMO PADRAO ("BOLETO PAI").
        $idProcedimento = isset($post['hdnIdProcedimento']) ? $post['hdnIdProcedimento'] : null;
        if (!$idProcedimento) {
            return null;
        }

        return MdLitLancamentoINT::consultarUltimoLancamento($idProcedimento);
    }

    private function obterMenorId($arrIds)
    {
        $idMenor = null;
        foreach ($arrIds as $id) {
            if ($idMenor === null || $id < $idMenor) {
                $idMenor = $id;
            }
        }

        return $idMenor;
    }

    private function verificarNovaSituacaoDecisaoPost($post)
    {
        if (empty($post['hdnTbSituacoes'])) {
            return false;
        }

        $arrSituacoes = PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbSituacoes']);
        foreach ($arrSituacoes as $arrSituacao) {
            $tipoRegistro = isset($arrSituacao[1]) ? $arrSituacao[1] : '';
            $tipoSituacao = isset($arrSituacao[17]) ? trim(str_replace(array('(', ')'), '', $arrSituacao[17])) : '';
            if ($tipoRegistro == 'N' && $tipoSituacao == 'Decisoria') {
                return true;
            }
        }

        return false;
    }

    public function processarLancamentosDecisoesAlteradas($arrLancamentosAlterados, $arrIdDecisao, $post)
    {
        if (empty($arrLancamentosAlterados)) {
            return false;
        }

        $arrLancamentosAlterados = $this->ordenarLancamentosAlteradosPorFracionamento($arrLancamentosAlterados);
        $arrDecisoesPorLancamento = $this->agruparDecisoesSalvasPorLancamento($post, $arrIdDecisao, array_keys($arrLancamentosAlterados));
        $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();
        $arrMajoradosCriadosPorRaiz = array();
        $arrLancamentosProcessados = array();

        foreach ($arrLancamentosAlterados as $idLancamento => $dadosLancamento) {
            $arrLancamentosProcessados[$idLancamento] = true;
            $objMdLitLancamentoDTO = $this->retornaObjLancamento($idLancamento);
            if (!$objMdLitLancamentoDTO) {
                continue;
            }

            $arrDadosDecisoesLancamento = isset($arrDecisoesPorLancamento[$idLancamento]) ? $arrDecisoesPorLancamento[$idLancamento] : array('todos' => array(), 'por_origem' => array());
            $arrIdsDecisaoLancamento = isset($arrDadosDecisoesLancamento['todos']) ? $arrDadosDecisoesLancamento['todos'] : array();
            $diferenca = $this->normalizarValorDecisao(isset($dadosLancamento['diferenca']) ? $dadosLancamento['diferenca'] : '0.00');
            $bolNovaSituacaoDecisao = !empty($dadosLancamento['nova_situacao_decisao']);
            //ANCORA DO BOLETO INICIAL (ver ancorarBoletosIniciaisSemAlteracao): CRIA O
            //LANCAMENTO MAJORADO MESMO COM DIFERENCA ZERO, PARA SEMPRE VIRAR A RAIZ DO GRUPO.
            $bolAncoraBoletoInicial = !empty($dadosLancamento['ancora_boleto_inicial']);

            if (($bolAncoraBoletoInicial || bccomp($diferenca, '0.00', 2) > 0) && $bolNovaSituacaoDecisao) {
                $idRaizLancamento = $this->obterIdRaizLancamentoFracionado($objMdLitLancamentoDTO);
                $idLancamentoInicialMajorado = null;
                if (isset($arrMajoradosCriadosPorRaiz[$idRaizLancamento])) {
                    $idLancamentoInicialMajorado = $arrMajoradosCriadosPorRaiz[$idRaizLancamento];
                }

                $objLancamentoMajoradoDTO = $this->criarLancamentoMajoradoPorDecisoesAlteradas($objMdLitLancamentoDTO, $diferenca, $post, $idLancamentoInicialMajorado);
                if (!$idLancamentoInicialMajorado && !isset($arrMajoradosCriadosPorRaiz[$idRaizLancamento])) {
                    $arrMajoradosCriadosPorRaiz[$idRaizLancamento] = $objLancamentoMajoradoDTO->getNumIdMdLitLancamento();
                }
                if (!empty($arrIdsDecisaoLancamento)) {
                    $objMdLitRelDecisLancamentRN->atualizarRelacionamentoDecisaoLancamento($objLancamentoMajoradoDTO->getNumIdMdLitLancamento(), $arrIdsDecisaoLancamento);
                }
                continue;
            }

            if (!empty($arrIdsDecisaoLancamento)) {
                $objMdLitRelDecisLancamentRN->atualizarRelacionamentoDecisaoLancamento($idLancamento, $arrIdsDecisaoLancamento);
            }
            $valorRetificacao = $bolNovaSituacaoDecisao
                ? $this->calcularValorRetificacaoPorDiferenca($objMdLitLancamentoDTO, $diferenca)
                : $this->calcularValorRetificacaoLancamento($objMdLitLancamentoDTO, $arrIdsDecisaoLancamento, $diferenca);
            $this->retificarLancamentoPorValor($objMdLitLancamentoDTO, $valorRetificacao, $post);
        }

        //DECISOES SALVAS NA MESMA OPERACAO CUJO LANCAMENTO NAO TEVE DIFERENCA DE VALOR (EX.:
        //DECISAO REAFIRMADA SEM ALTERACAO NUMA NOVA SITUACAO DECISORIA) NAO PASSAM PELO LOOP
        //ACIMA (SO ITERA $arrLancamentosAlterados, QUE SO TEM LANCAMENTOS COM DIFERENCA != 0),
        //MAS PRECISAM TER O VINCULO decisao-lancamento ATUALIZADO DA MESMA FORMA. SEM ISSO A
        //DECISAO FICA ORFA EM md_lit_rel_decis_lancament E A CADEIA id_decisao_origem QUEBRA
        //PARA RODADAS FUTURAS, POIS A PROXIMA VERSAO DELA NAO CONSEGUE MAIS ENCONTRAR O
        //LANCAMENTO ATRAVES DE consultarIdLancamentoPorDecisao().
        foreach ($arrDecisoesPorLancamento as $idLancamento => $arrDadosDecisoesLancamento) {
            if (isset($arrLancamentosProcessados[$idLancamento])) {
                continue;
            }

            $arrIdsDecisaoLancamento = isset($arrDadosDecisoesLancamento['todos']) ? $arrDadosDecisoesLancamento['todos'] : array();
            if (empty($arrIdsDecisaoLancamento)) {
                continue;
            }

            $objMdLitRelDecisLancamentRN->atualizarRelacionamentoDecisaoLancamento($idLancamento, $arrIdsDecisaoLancamento);
        }

        return true;
    }

    private function ordenarLancamentosAlteradosPorFracionamento($arrLancamentosAlterados)
    {
        $arrChaves = array_keys($arrLancamentosAlterados);
        usort($arrChaves, array($this, 'compararLancamentoAlteradoPorFracionamento'));

        $arrOrdenado = array();
        foreach ($arrChaves as $idLancamento) {
            $idLancamento = intval($idLancamento);
            $arrOrdenado[$idLancamento] = $arrLancamentosAlterados[$idLancamento];
        }

        return $arrOrdenado;
    }

    private function compararLancamentoAlteradoPorFracionamento($idLancamentoA, $idLancamentoB)
    {
        $objLancamentoA = $this->retornaObjLancamento($idLancamentoA);
        $objLancamentoB = $this->retornaObjLancamento($idLancamentoB);
        $idRaizA = $this->obterIdRaizLancamentoFracionado($objLancamentoA);
        $idRaizB = $this->obterIdRaizLancamentoFracionado($objLancamentoB);

        if ($idRaizA == $idRaizB) {
            $ordemA = $objLancamentoA->getNumIdMdLitLancamentoInicial() ? 1 : 0;
            $ordemB = $objLancamentoB->getNumIdMdLitLancamentoInicial() ? 1 : 0;
            if ($ordemA == $ordemB) {
                return $idLancamentoA < $idLancamentoB ? -1 : 1;
            }
            return $ordemA < $ordemB ? -1 : 1;
        }

        return $idRaizA < $idRaizB ? -1 : 1;
    }

    private function obterIdRaizLancamentoFracionado($objMdLitLancamentoDTO)
    {
        if ($objMdLitLancamentoDTO->getStrTipoLancamento() == self::$TIPO_LANCAMENTO_MAJORADO) {
            $idRaizPorDecisoes = $this->obterIdRaizFracionamentoPorDecisoes($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
            if ($idRaizPorDecisoes) {
                return $idRaizPorDecisoes;
            }
        }

        return $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial()
            ? $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial()
            : $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
    }

    //RESOLVE A IDENTIDADE ESTAVEL DO FRACIONAMENTO ORIGINAL A PARTIR DAS DECISOES
    //VINCULADAS AO LANCAMENTO. NECESSARIO PORQUE UM LANCAMENTO MAJORADO TEM
    //id_md_lit_lancamento_inicial REINICIADO OU REAPONTADO A CADA RODADA
    //(criarLancamentoMajoradoPorDecisoesAlteradas / adotarFracionadosMajoradoOrfaos),
    //ENTAO ELE NAO PODE SER USADO SOZINHO PARA DECIDIR SE DUAS DECISOES PERTENCEM AO
    //MESMO FRACIONAMENTO ORIGINAL. AQUI A RESOLUCAO SEMPRE PARTE DAS DECISOES VINCULADAS,
    //MESMO QUANDO O LANCAMENTO JA TIVER UM id_md_lit_lancamento_inicial ATUAL.
    private function obterIdRaizFracionamentoPorDecisoes($idLancamento)
    {
        $idLancamentoOrigemFracionamento = $this->obterIdLancamentoFracionamentoOrigemPorDecisoes($idLancamento);
        if (!$idLancamentoOrigemFracionamento) {
            return null;
        }

        $objMdLitLancamentoOrigemFracionamentoDTO = $this->retornaObjLancamento($idLancamentoOrigemFracionamento);

        return $objMdLitLancamentoOrigemFracionamentoDTO->getNumIdMdLitLancamentoInicial()
            ? $objMdLitLancamentoOrigemFracionamentoDTO->getNumIdMdLitLancamentoInicial()
            : $idLancamentoOrigemFracionamento;
    }

    //RESOLVE O LANCAMENTO (PRINCIPAL RAIZ OU SECUNDARIO) QUE ORIGINALMENTE RECEBEU A
    //DECISAO RAIZ DA CADEIA VINCULADA A $idLancamento, SEM NORMALIZAR PARA A RAIZ FINAL
    //DO FRACIONAMENTO. USADO PARA DISTINGUIR "SLOTS" DE FRACIONAMENTO (RAIZ, FRACIONADO 1,
    //FRACIONADO 2, ...) MESMO QUANDO $idLancamento FOR UM MAJORADO. PREFERE O MAIOR ID
    //vinculado a decisao raiz (o mais recente) para evitar pegar um vinculo antigo/residual
    //de antes do fracionamento original.
    private function obterIdLancamentoFracionamentoOrigemPorDecisoes($idLancamento)
    {
        $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
        $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitRelDecisLancamentDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitRelDecisLancamentDTO->retNumIdMdLitDecisao();
        $arrObjMdLitRelDecisLancamentDTO = (new MdLitRelDecisLancamentRN())->listar($objMdLitRelDecisLancamentDTO);

        if (empty($arrObjMdLitRelDecisLancamentDTO)) {
            return null;
        }

        $arrCadeiaDecisao = $this->recuperarCadeiaDecisao(current($arrObjMdLitRelDecisLancamentDTO)->getNumIdMdLitDecisao());
        if (empty($arrCadeiaDecisao)) {
            return null;
        }

        $objMdLitRelDecisaoRaizDTO = new MdLitRelDecisLancamentDTO();
        $objMdLitRelDecisaoRaizDTO->setNumIdMdLitDecisao($arrCadeiaDecisao[0]);
        $objMdLitRelDecisaoRaizDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitRelDecisaoRaizDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitRelDecisaoRaizDTO->retNumIdMdLitLancamento();
        $arrObjRelDecisaoRaizDTO = (new MdLitRelDecisLancamentRN())->listar($objMdLitRelDecisaoRaizDTO);

        if (empty($arrObjRelDecisaoRaizDTO)) {
            return null;
        }

        return current($arrObjRelDecisaoRaizDTO)->getNumIdMdLitLancamento();
    }

    private function agruparDecisoesSalvasPorLancamento($post, $arrIdDecisao, $arrIdsLancamentosResolvidos = array())
    {
        $arrDecisoesPorLancamento = array();
        if (empty($post['hdnTbDecisao'])) {
            return $arrDecisoesPorLancamento;
        }

        $idLancamentoPai = null;

        $arrDecisao = PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbDecisao']);
        foreach ($arrDecisao as $decisao) {
            $chaveDecisao = $decisao[0];
            if (!array_key_exists($chaveDecisao, $arrIdDecisao)) {
                continue;
            }

            $idDecisaoSalva = (int) $arrIdDecisao[$chaveDecisao];
            if (!$idDecisaoSalva) {
                continue;
            }

            $idDecisaoComparacao = $this->obterIdDecisaoComparacao($decisao);
            $idLancamento = $idDecisaoComparacao ? $this->consultarIdLancamentoPorDecisao($idDecisaoComparacao) : null;
            if (!$idLancamento) {
                $idLancamento = $this->obterIdLancamentoDecisaoPost($decisao);
            }

            if (!$idLancamento && !$idDecisaoComparacao) {
                //DECISAO NOVA SEM VINCULO: USA O MESMO BOLETO "PAI" JA RESOLVIDO POR
                //identificarLancamentosDecisoesAlteradas() PARA MANTER CONSISTENCIA ENTRE
                //O CALCULO DA DIFERENCA E O VINCULO decisao-lancamento GRAVADO NO FINAL.
                if ($idLancamentoPai === null) {
                    $idLancamentoPai = $this->obterMenorId($arrIdsLancamentosResolvidos) ?: 0;
                }
                $idLancamento = $idLancamentoPai ?: null;
            }

            if (!$idLancamento) {
                continue;
            }

            if (!isset($arrDecisoesPorLancamento[$idLancamento])) {
                $arrDecisoesPorLancamento[$idLancamento] = array(
                    'todos' => array(),
                    'por_origem' => array()
                );
            }

            $arrDecisoesPorLancamento[$idLancamento]['todos'][] = $idDecisaoSalva;
            if ($idDecisaoComparacao) {
                $arrDecisoesPorLancamento[$idLancamento]['por_origem'][$idDecisaoComparacao] = $idDecisaoSalva;
            }
        }

        foreach ($arrDecisoesPorLancamento as $idLancamento => $arrDadosLancamento) {
            $arrDecisoesPorLancamento[$idLancamento]['todos'] = array_values(array_unique($arrDadosLancamento['todos']));
        }

        return $arrDecisoesPorLancamento;
    }

    private function obterIdsDecisoesSalvasPorOrigem($arrDadosDecisoesLancamento, $arrIdsDecisaoOrigem)
    {
        $arrIdsDecisaoSalvas = array();
        if (empty($arrIdsDecisaoOrigem) || empty($arrDadosDecisoesLancamento['por_origem'])) {
            return $arrIdsDecisaoSalvas;
        }

        foreach ($arrIdsDecisaoOrigem as $idDecisaoOrigem) {
            if (isset($arrDadosDecisoesLancamento['por_origem'][$idDecisaoOrigem])) {
                $arrIdsDecisaoSalvas[] = (int) $arrDadosDecisoesLancamento['por_origem'][$idDecisaoOrigem];
            }
        }

        return array_values(array_unique($arrIdsDecisaoSalvas));
    }

    private function removerIdsDecisao($arrIdsDecisao, $arrIdsRemover)
    {
        if (empty($arrIdsRemover)) {
            return $arrIdsDecisao;
        }

        return array_values(array_diff($arrIdsDecisao, $arrIdsRemover));
    }

    private function calcularValorRetificacaoLancamento($objMdLitLancamentoDTO, $arrIdsDecisaoLancamento, $diferenca)
    {
        if (!empty($arrIdsDecisaoLancamento)) {
            return $this->calcularValorTotalDecisoes($this->recuperarArrayDecisoes($arrIdsDecisaoLancamento));
        }

        return $this->calcularValorRetificacaoPorDiferenca($objMdLitLancamentoDTO, $diferenca);
    }

    private function calcularValorRetificacaoPorDiferenca($objMdLitLancamentoDTO, $diferenca)
    {
        $valorLancamentoAtual = $this->normalizarValorDecisao($objMdLitLancamentoDTO->getDblVlrLancamento());
        if (bccomp($valorLancamentoAtual, '0.00', 2) == 0 && $objMdLitLancamentoDTO->getNumIdMdLitLancamento()) {
            $valorLancamentoBanco = $this->consultarValorLancamentoAtual($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
            if ($valorLancamentoBanco !== null) {
                $valorLancamentoAtual = $valorLancamentoBanco;
            }
        }

        $diferenca = $this->normalizarValorDecisao($diferenca);
        $valorRetificacao = bcadd($valorLancamentoAtual, $diferenca, 2);

        if (bccomp($valorRetificacao, '0.00', 2) < 0) {
            $valorRetificacao = $valorLancamentoAtual;
        }

        return $valorRetificacao;
    }

    private function consultarValorLancamentoAtual($idLancamento)
    {
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitLancamentoDTO->retDblVlrLancamento();
        $objMdLitLancamentoDTO = $this->consultar($objMdLitLancamentoDTO);

        if (!$objMdLitLancamentoDTO) {
            return null;
        }

        return $this->normalizarValorDecisao($objMdLitLancamentoDTO->getDblVlrLancamento());
    }

    private function retificarLancamentoPorDecisoesSalvas($objMdLitLancamentoDTO, $arrIdsDecisaoLancamento, $post)
    {
        $valorLancamento = $this->calcularValorRetificacaoLancamento($objMdLitLancamentoDTO, $arrIdsDecisaoLancamento, '0.00');
        $this->retificarLancamentoPorValor($objMdLitLancamentoDTO, $valorLancamento, $post);
    }

    private function retificarLancamentoPorValor($objMdLitLancamentoDTO, $valorLancamento, $post)
    {
        //SE O VALOR FINAL DO AGRUPAMENTO CAIR A ZERO OU NEGATIVO (POR EXEMPLO, AO DESFAZER
        //DECISOES/SITUACOES E VOLTAR OS VALORES AO ESTADO ANTERIOR), O LANCAMENTO DEVE SER
        //CANCELADO, NUNCA RETIFICADO COM VALOR INVALIDO PARA O SIGEC (ERRO SOAP 90004).
        if (bccomp($this->normalizarValorDecisao($valorLancamento), '0.00', 2) <= 0) {
            $this->cancelarLancamentoPorValorZerado(
                $objMdLitLancamentoDTO,
                'Alteracao de decisoes',
                'Lancamento cancelado porque o valor das decisoes vinculadas foi reduzido a zero.'
            );

            return;
        }

        $postRetificacao = $post;
        $postRetificacao['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO;

        $objMdLitLancamentoDTO->setDblVlrLancamento($valorLancamento);
        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor($valorLancamento);
        $objMdLitLancamentoDTO->setDtaVencimento($post['txtDtVencimento']);
        if (!empty($post['hdnJustificativaLancamento'])) {
            $objMdLitLancamentoDTO->setStrJustificativa($post['hdnJustificativaLancamento']);
        } else {
            $objMdLitLancamentoDTO->setStrJustificativa($this->montarJustificativaAutomatica('retificada', $objMdLitLancamentoDTO->getDblIdProcedimento()));
        }

        $this->alterar($objMdLitLancamentoDTO);
        (new MdLitRetificarLancamentoRN)->retificarLancamento($objMdLitLancamentoDTO, $postRetificacao);
    }

    private function criarLancamentoMajoradoPorDecisoesAlteradas($objMdLitLancamentoOrigemDTO, $valorMajorado, $post, $idLancamentoInicialMajorado = null)
    {
        $objLancamentoMajoradoDTO = $this->duplicarLancamento($objMdLitLancamentoOrigemDTO);
        $objLancamentoMajoradoDTO->setStrTipoLancamento(self::$TIPO_LANCAMENTO_MAJORADO);
        $objLancamentoMajoradoDTO->setNumIdMdLitLancamentoInicial($idLancamentoInicialMajorado);
        $objLancamentoMajoradoDTO->setNumIdMdLitSituacaoLancamento(null);
        $objLancamentoMajoradoDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
        $objLancamentoMajoradoDTO->setDtaVencimento($post['txtDtVencimento']);
        $objLancamentoMajoradoDTO->setDblVlrLancamento($valorMajorado);
        $objLancamentoMajoradoDTO->setDblVlrSaldoDevedor($valorMajorado);
        $objLancamentoMajoradoDTO->setDblVlrPago(0);
        if (!empty($post['hdnJustificativaLancamento'])) {
            $objLancamentoMajoradoDTO->setStrJustificativa($post['hdnJustificativaLancamento']);
        } else {
            $objLancamentoMajoradoDTO->setStrJustificativa($this->montarJustificativaAutomatica('inclu�da', $objLancamentoMajoradoDTO->getDblIdProcedimento()));
        }

        $objLancamentoMajoradoDTO = $this->cadastrar($objLancamentoMajoradoDTO);

        //SE O VALOR FOR ZERO OU NEGATIVO (LANCAMENTO CRIADO APENAS PARA ANCORAR O BOLETO
        //INICIAL DE UM GRUPO MAJORADO QUANDO ELE NAO TEVE AUMENTO NESTA RODADA):
        //1) NAO ENVIAR AO SIGEC - A INTEGRACAO REJEITA VALOR <= 0 (ERRO SOAP "VALOR
        //   INFORMADO E INVALIDO");
        //2) NAO CHAMAR adotarFracionadosMajoradoOrfaos() - ESSA ADOCAO E DO FLUXO MANUAL DE
        //   FRACIONAMENTO/salvarLancamento(); SE FOSSE CHAMADA AQUI, UMA MAJORACAO DE RODADA
        //   FUTURA DA MESMA FAMILIA ADOTARIA OS SECUNDARIOS DESTA RODADA (JA CORRETAMENTE
        //   AGRUPADOS SOB ESTE Majorado N), MISTURANDO RODADAS DIFERENTES;
        //3) CANCELAR SOMENTE LOCALMENTE (SEM SIGEC), PARA O REGISTRO NAO SER CONFUNDIDO COM
        //   UM LANCAMENTO RAIZ ATIVO POR consultarUltimoLancamento()/OUTRAS CONSULTAS.
        if (bccomp($this->normalizarValorDecisao($valorMajorado), '0.00', 2) <= 0) {
            $this->cancelarLancamentoLocalSemSigec(
                $objLancamentoMajoradoDTO,
                'Ancora de boleto inicial sem aumento de valor',
                'Lancamento criado apenas como referencia estrutural do boleto inicial nesta rodada de majoracao; nao houve aumento de valor a cobrar.'
            );

            return $objLancamentoMajoradoDTO;
        }

        //QUANDO ESTE MAJORADO NASCE COMO RAIZ (SEM id_lancamento_inicial), DEVE ADOTAR
        //FRACIONADOS ORFAOS DE UMA RAIZ DE MAJORADO ANTERIOR JA CANCELADA (MESMA REGRA
        //APLICADA NA CRIACAO NORMAL DE LANCAMENTO, EM MdLitLancamentoRN::salvarLancamento).
        //SO SE APLICA QUANDO HOUVE VALOR REAL A MAJORAR NESTA RODADA (VER COMENTARIO ACIMA).
        $this->adotarFracionadosMajoradoOrfaos($objLancamentoMajoradoDTO);

        $postMajoracao = $post;
        $postMajoracao['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO;
        if (empty($postMajoracao['hdnIdTipoControle'])) {
            $postMajoracao['hdnIdTipoControle'] = $this->buscarIdTipoControlePorLancamento($objLancamentoMajoradoDTO->getDblIdProcedimento());
        }
        (new MdLitIncluirLancamentoRN)->incluirLancamento($objLancamentoMajoradoDTO, $postMajoracao);

        return $objLancamentoMajoradoDTO;
    }

    private function cancelarLancamentoLocalSemSigec($objMdLitLancamentoDTO, $strMotivoCancelamento, $strJustificativaCancelamento)
    {
        //CANCELAMENTO SOMENTE LOCAL (SEM CHAMAR O SIGEC): USADO PARA LANCAMENTOS QUE NUNCA
        //FORAM ENVIADOS AO FINANCEIRO (VALOR ZERO), ONDE NAO HA BOLETO REMOTO PARA CANCELAR.
        //MdLitCancelaLancamentoRN::cancelarLancamento() SEMPRE FAZ UMA CHAMADA SOAP DE
        //CANCELAMENTO E FALHARIA POR NAO EXISTIR SEQUENCIAL/REGISTRO NO SIGEC PARA ESTE CASO.
        $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
        $objMdLitSituacaoLancamentoDTO->retTodos(false);
        $objMdLitSituacaoLancamentoDTO = (new MdLitSituacaoLancamentoRN())->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);

        if ($objMdLitSituacaoLancamentoDTO) {
            $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento());
            $this->alterar($objMdLitLancamentoDTO);
        }

        $objMdLitCancelaLancDTO = new MdLitCancelaLancamentoDTO();
        $objMdLitCancelaLancDTO->setNumIdMdLitLancamento($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
        $objMdLitCancelaLancDTO->setStrMotivoCancelamento($strMotivoCancelamento);
        $objMdLitCancelaLancDTO->setStrJustificativa($strJustificativaCancelamento);
        (new MdLitCancelaLancamentoRN())->cadastrar($objMdLitCancelaLancDTO);
    }

    private function obterIdDecisaoComparacao($decisao)
    {
        if (!empty($decisao[0]) && is_numeric($decisao[0])) {
            return (int) $decisao[0];
        }

        if (isset($decisao[20]) && is_numeric($decisao[20])) {
            return (int) $decisao[20];
        }

        return null;
    }

    private function obterIdLancamentoDecisaoPost($decisao)
    {
        if (isset($decisao[19]) && $decisao[19] !== '' && $decisao[19] !== 'null' && $decisao[19] !== 'undefined') {
            return (int) $decisao[19];
        }

        return null;
    }

    private function consultarValorDecisao($idDecisao)
    {
        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        $objMdLitDecisaoDTO->setNumIdMdLitDecisao($idDecisao);
        $objMdLitDecisaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitDecisaoDTO->retDblMulta();
        $objMdLitDecisaoDTO->retDblValorMultaSemIntegracao();
        $arrObjMdLitDecisaoDTO = (new MdLitDecisaoRN())->listar($objMdLitDecisaoDTO);

        if (empty($arrObjMdLitDecisaoDTO)) {
            return '0.00';
        }

        $objMdLitDecisaoDTO = current($arrObjMdLitDecisaoDTO);
        $valorDecisao = $objMdLitDecisaoDTO->getDblMulta() !== null && $objMdLitDecisaoDTO->getDblMulta() !== '' ? $objMdLitDecisaoDTO->getDblMulta() : $objMdLitDecisaoDTO->getDblValorMultaSemIntegracao();
        return $this->normalizarValorDecisao($valorDecisao);
    }

    private function consultarIdLancamentoPorDecisao($idDecisao)
    {
        $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
        $objMdLitRelDecisLancamentDTO->setNumIdMdLitDecisao($idDecisao);
        $objMdLitRelDecisLancamentDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitRelDecisLancamentDTO->retNumIdMdLitLancamento();
        $arrObjMdLitRelDecisLancamentDTO = (new MdLitRelDecisLancamentRN())->listar($objMdLitRelDecisLancamentDTO);

        if (empty($arrObjMdLitRelDecisLancamentDTO)) {
            return null;
        }

        $objMdLitRelDecisLancamentDTO = current($arrObjMdLitRelDecisLancamentDTO);
        return $objMdLitRelDecisLancamentDTO->getNumIdMdLitLancamento();
    }

    private function normalizarValorDecisao($valor)
    {
        if ($valor === null || $valor === '') {
            return '0.00';
        }

        $valor = trim((string) $valor);
        if (strpos($valor, ',') !== false) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }

        return number_format((float) $valor, 2, '.', '');
    }

    public function atualizarDadosLancamentoInicialSecundario($objMdLitLancamentoDTO, $post)
    {
        $post['hdnIdMdLitFuncionalidade'] = MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO;
        
        //BUSCA O VALOR DO LANCAMENTO INICIAL PELAS DECISOES RELACIONADAS E ATUALIZA O VALOR
        $totalInicial = $this->calcularValorLancamentoPelasDecisoes($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
        $objMdLitLancamentoDTO->setDblVlrLancamento($totalInicial);
        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor($totalInicial);
        $this->alterar($objMdLitLancamentoDTO);
        $this->enviarFinanceiro($post, $objMdLitLancamentoDTO);

        //BUSCAR O LANCAMENTO SECUNDARIO
        //BUSCAR O VALOR TOTAL DE ACORDO COM AS DECISOES E ATUALIZAR OS DADOS
        $arrObjMdLitLancamentoSecundarioDTO = $this->retornaArrObjLancamentoSecundario($objMdLitLancamentoDTO->getNumIdMdLitLancamento());
        foreach($arrObjMdLitLancamentoSecundarioDTO as $objMdLitLancamentoSecundarioDTO){
            $objMdLitLancamentoSecundarioDTO = $this->popularLancamentoSecundarioInformacoesComuns($objMdLitLancamentoDTO, $objMdLitLancamentoSecundarioDTO);
            $totalSecundario = $this->calcularValorLancamentoPelasDecisoes($objMdLitLancamentoSecundarioDTO->getNumIdMdLitLancamento());
            $objMdLitLancamentoSecundarioDTO->setDblVlrLancamento($totalSecundario);
            $objMdLitLancamentoSecundarioDTO->setDblVlrSaldoDevedor($totalSecundario);
            $this->alterar($objMdLitLancamentoSecundarioDTO);
        
            //POR ULTIMO DEVE RETIFICAR O LANCAMENTO SECUNDARIO
            $this->enviarFinanceiro($post, $objMdLitLancamentoSecundarioDTO);
        }
            
        return $objMdLitLancamentoDTO;

    }

    private function calcularValorLancamentoPelasDecisoes($idLancamento)
    {
        $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
        $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitRelDecisLancamentDTO->retNumIdMdLitDecisao();
        $arrObjMdLitRelDecisLancamentDTO = (new MdLitRelDecisLancamentRN())->listar($objMdLitRelDecisLancamentDTO);
        
        $arrDecisoes = $this->recuperarArrayDecisoes(InfraArray::converterArrInfraDTO($arrObjMdLitRelDecisLancamentDTO, 'IdMdLitDecisao'));
        return $this->calcularValorTotalDecisoes($arrDecisoes);
    }

}
?>