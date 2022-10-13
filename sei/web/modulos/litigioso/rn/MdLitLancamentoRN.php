<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
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
        $objInfraException->adicionarValidacao('tipo do lançamento possui tamanho superior a 1 caracteres.');
      }
    }
  }

  private function validarStrSequencial(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrSequencial())){
      $objInfraException->adicionarValidacao('sequencial não informado.');
    }else{
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
        $objInfraException->adicionarValidacao('data da decisão inválida.');
      }
    }
  }

  private function validarDtaIntimacao(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaIntimacao())){
      $objMdLitLancamentoDTO->setDtaIntimacao(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaIntimacao())){
        $objInfraException->adicionarValidacao('data da intimação inválida.');
      }
    }
  }

  private function validarDtaVencimento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaVencimento())){
      $objMdLitLancamentoDTO->setDtaVencimento(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaVencimento())){
        $objInfraException->adicionarValidacao('data do vencimento inválida.');
      }
    }
  }

  private function validarDtaPrazoDefesa(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaPrazoDefesa())){
      $objMdLitLancamentoDTO->setDtaPrazoDefesa(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaPrazoDefesa())){
        $objInfraException->adicionarValidacao('data do prazo de defesa inválida.');
      }
    }
  }

  private function validarDtaUltimoPagamento(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaUltimoPagamento())){
      $objMdLitLancamentoDTO->setDtaUltimoPagamento(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaUltimoPagamento())){
        $objInfraException->adicionarValidacao('data do ultimo pagamento inválida.');
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
        $objInfraException->adicionarValidacao('data de inclusão inválida.');
      }
    }
  }

  private function validarStrLinkBoleto(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrLinkBoleto())){
      $objMdLitLancamentoDTO->setStrLinkBoleto(null);
    }else{
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
        $objInfraException->adicionarValidacao('Número de complemento do interessado possui tamanho superior a 999 caracteres.');
      }
    }
  }

  private function validarStrSinConstituicaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva())){
      $objInfraException->adicionarValidacao('Sinalizador de constituição definitiva não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitLancamentoDTO->getStrSinConstituicaoDefinitiva())){
        $objInfraException->adicionarValidacao('Sinalizador de constituição definitiva inválida.');
      }
    }
  }

  private function validarStrSinRenunciaRecorrer(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getStrSinRenunciaRecorrer())){
      $objInfraException->adicionarValidacao('Sinalizador de renunciar recorrer não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitLancamentoDTO->getStrSinRenunciaRecorrer())){
        $objInfraException->adicionarValidacao('Sinalizador de renunciar recorrer inválido.');
      }
    }
  }

  private function validarDtaIntimacaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva())){
      $objMdLitLancamentoDTO->setDtaIntimacaoDefinitiva(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva())){
        $objInfraException->adicionarValidacao('data da intimação definitiva inválida.');
      }
    }
  }

  private function validarDtaConstituicaoDefinitiva(MdLitLancamentoDTO $objMdLitLancamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva())){
      $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva(null);
    }else{
      if (!InfraData::validarData($objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva())){
        $objInfraException->adicionarValidacao('data da constituição definitiva inválida.');
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
//      $this->validarStrJustificativa($objMdLitLancamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitLancamentoBD = new MdLitLancamentoBD($this->getObjInfraIBanco());
      $ret = $objMdLitLancamentoBD->cadastrar($objMdLitLancamentoDTO);
      $this->prepararHistoricoLancamento($objMdLitLancamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando lançamento.',$e);
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

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando lançamento.',$e);
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
      throw new InfraException('Erro excluindo lançamento.',$e);
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
      throw new InfraException('Erro consultando lançamento.',$e);
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
      throw new InfraException('Erro listando lançamentos.',$e);
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
      throw new InfraException('Erro contando lançamentos.',$e);
    }
  }

    /**
     * @param $post
     * @return MdLitLancamentoDTO retorna o objeto se for o lançamento de um novo crédito ao contrario não há retorno
     */
    public function prepararLancamento($post){
      
        if(!empty($post['hdnTbVincularLancamento'])){
            //vincular Lancamento do lado do SEI tem que salvar na tabela md_lit_lancamento
            $arrTbVincularLancamento = current(PaginaSEI::getInstance()->getArrItensTabelaDinamica($post['hdnTbVincularLancamento']));

            $arrTbVincularLancamento['selNumeroInteressado'] = $post['selNumeroInteressado'];
            $arrTbVincularLancamento['hdnIdProcedimento'] = $post['hdnIdProcedimento'];
            $arrTbVincularLancamento['txtDtIntimacaoAplMulta'] = $post['txtDtIntimacaoAplMulta'];
            $arrTbVincularLancamento['txtDtDecursoPrazoRecurso'] = $post['txtDtDecursoPrazoRecurso'];
            $arrTbVincularLancamento['txtDtDecursoPrazo'] = $post['txtDtDecursoPrazo'];
            $arrTbVincularLancamento['hdnDtDecursoPrazo'] = $post['hdnDtDecursoPrazo'];
            $objMdLitLancamentoDTO = $this->vincularLancamento($arrTbVincularLancamento);
            return $objMdLitLancamentoDTO;
        }
        
        switch ($post['hdnIdMdLitFuncionalidade']) {
            case MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO:
                $objMdLitLancamento = $this->_realizarLancamentoCredito($post);
                return $objMdLitLancamento;
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO:
                $objMdLitCancelarRN = new MdLitCancelaLancamentoRN();
                $objMdLitCancelarRN->realizarCancelamentoCredito($post);
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO:
                $this->realizarRetificarCredito($post);
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO:
                $objMdLitRecursoLancamentoRN = new MdLitRecursoLancamentoRN();
                $objMdLitRecursoLancamentoRN->suspenderLancamento($post);
                break;

            case MdLitIntegracaoRN::$ARRECADACAO_DENEGAR_RECURSO:
                $objMdLitRecursoLancamentoRN = new MdLitRecursoLancamentoRN();
                $objMdLitRecursoLancamentoRN->denegarRecurso($post);
                break;


            case MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_RECURSO:
                $objMdLitRecursoLancamentoRN = new MdLitRecursoLancamentoRN();
                $objMdLitRecursoLancamentoRN->cancelarRecurso($post);
                break;
        }

        if($post['hdnIdMdLitFuncionalidade'] == ''){
          
            $this->alterarData($post['selCreditosProcesso'], $post['txtDtIntimacaoAplMulta'], $post['txtDtDecursoPrazoRecurso']);

            //se houver alteração da Data da Intimação da Decisão de Aplicação da Multa e NÃO seja a situacao de intimação posterior a decisao
            if(!$post['hdnIntimacaoPosDecisao'] && isset($post['hdnDtaIntimacaoDecisaoMulta'])){
                foreach ($post['hdnDtaIntimacaoDecisaoMulta'] as $key => $value) {
                    if($key != $post['selCreditosProcesso']) {
                        $this->alterarData($key, $value);
                    }
                }
            }
        }

        //Se for intimação apos a decisao que aplicou multa o campo hdnIntimacaoPosDecisao vem preechido com a data da intimação
        if($post['hdnIntimacaoPosDecisao']){
            $MdLitLancamentoDTO = new MdLitLancamentoDTO();
            $MdLitLancamentoDTO->ret('IdMdLitLancamento');
            $MdLitLancamentoDTO->ret('IdProcedimento');
            $MdLitLancamentoDTO->set('Intimacao', null);
            $MdLitLancamentoDTO->set('IdProcedimento', $post['hdnIdProcedimento']);
            $MdLitLancamentoDTO->setOrd('IdProcedimento', 'asc');

            $MdLitLancamentoRn = new MdLitLancamentoRN();
            //Lista todos os lancamentos com data de intimação nula para o processo
            $arrLancamentoSemDataIntimacao = $MdLitLancamentoRn->listar($MdLitLancamentoDTO);

            $cancelamento = [];
            //se existir lancamento sem data de intimação este lançamento não pode estar cancelado
            if(count($arrLancamentoSemDataIntimacao)){
                $arrMdLitLancamento = InfraArray::simplificarArr(InfraArray::converterArrInfraDTO($arrLancamentoSemDataIntimacao)['MdLitLancamentoDTO'],'IdMdLitLancamento');
                $mdLitCancelaLancamentoDto =  new MdLitCancelaLancamentoDTO();
                $mdLitCancelaLancamentoDto->ret('IdMdLitLancamento');
                $mdLitCancelaLancamentoDto->set('IdMdLitLancamento', $arrMdLitLancamento, InfraDTO::$OPER_IN);

                $mdLitCancelaLancamentoRn = new MdLitCancelaLancamentoRN();
                $cancelamento = $mdLitCancelaLancamentoRn->listar($mdLitCancelaLancamentoDto);
            }

            //itera os lancamentos com data nula
            foreach ($arrLancamentoSemDataIntimacao as $lancamento){
                //se o lancamento estiver cancelado ignora
                if(in_array($lancamento->get('IdMdLitLancamento'), $cancelamento)){
                    continue;
                }

                //se a data foi informada utiliza a data
                if(isset($post['hdnDtaIntimacaoDecisaoMulta'][$lancamento->get('IdMdLitLancamento')])){
                    $this->alterarData($lancamento->get('IdMdLitLancamento'), $post['hdnDtaIntimacaoDecisaoMulta'][$lancamento->get('IdMdLitLancamento')]);
                } else {
                    //se a data nao foi informada para algum lancamento isere a data da intimação logo apos a decisao 
                    $this->alterarData($lancamento->get('IdMdLitLancamento'), $post['hdnIntimacaoPosDecisao']);
                }
            }
        }
    }


    private function alterarData($idMdLitLancamento, $dtIntimacaoAplMulta, $dtDtDecursoPrazoRecurso = null){
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

    public function retornaObjLancamento($idLancamento){
        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento);
        $objMdLitLancamentoDTO->retTodos();
        $objMdLitLancamentoDTO->setNumMaxRegistrosRetorno(1);

        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        return $objMdLitLancamentoDTO;
    }

    /*
     * O Obj infra exception deve ser tratado posteriormente para retornar as exceções como alerta para o usuário.
     **/
    public function realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException){

        if(is_null($objMdLitIntegracaoDTO)){
            //$objInfraException->lancarValidacao('É necessário realizar a integração com a funcionalidade de Arrecadação Lançamento para Realizar o mesmo.');
            throw new InfraException('É necessário realizar a integração com a funcionalidade desejada. Favor entrar em contato com o administrador.');
        }

        if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO())){
            throw new InfraException('Os parâmetros de entrada e saída não foram parametrizados. Contate o Gestor do Controle.');
        }

        if(InfraUtil::prepararDin($post['hdnVlCreditoNaoLancado']) == 0){
            return false;
        }

        //$objInfraException->lancarValidacao();
        return $objInfraException;
    }

    private function _realizarLancamentoCredito($post){
        $objInfraException      = new InfraException();
        $objMdLitLancamentoNovo = new MdLitLancamentoDTO();
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
      
        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');
        $objMdLitSoapClienteRN->setSoapVersion($objMdLitIntegracaoDTO->getStrVersaoSoap());
        $objInfraException      = $this->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        $montarParametroEntrada = $this->_montarParametroEntradaLancamentoCredito($objMdLitIntegracaoDTO, $post, $objMdLitLancamentoNovo);

        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_LANCAMENTO);

        if($arrResultado) {
            $objMdLitLancamentoNovo = $this->_montarParametroSaidaLancamentoCredito($objMdLitLancamentoNovo, $arrResultado, $post['hdnIdMdLitFuncionalidade']);
            $this->_prepararObjLancamentoInclusao($objMdLitLancamentoNovo, $post);

            return $objMdLitLancamentoNovo;
        }
    }

    public function vincularLancamentoControlado($arrTbVincularLancamento){
        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->setStrSequencial($arrTbVincularLancamento[0]);
        $objMdLitLancamentoDTO->setStrCodigoReceita($arrTbVincularLancamento[1]);
        $objMdLitLancamentoDTO->setDtaVencimento($arrTbVincularLancamento[2]);
        $objMdLitLancamentoDTO->setDblVlrLancamento(str_replace('.', '', $arrTbVincularLancamento[3]));
        $objMdLitLancamentoDTO->setDblVlrDesconto(str_replace('.', '',$arrTbVincularLancamento[4]));
        $objMdLitLancamentoDTO->setDblVlrPago(str_replace('.', '',$arrTbVincularLancamento[5]));
        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor(str_replace('.', '',$arrTbVincularLancamento[6]));
        $objMdLitLancamentoDTO->setDtaDecisao($arrTbVincularLancamento[7] ? $arrTbVincularLancamento[7]: null);
        $objMdLitLancamentoDTO->setStrSinConstituicaoDefinitiva($arrTbVincularLancamento[9] ? 'S':'N');
        $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva($arrTbVincularLancamento[9] ? $arrTbVincularLancamento[9]:null);
        $objMdLitLancamentoDTO->setNumIdMdLitSituacaoLancamento($arrTbVincularLancamento[10]);
        $objMdLitLancamentoDTO->setStrLinkBoleto($arrTbVincularLancamento[11]);
        $objMdLitLancamentoDTO->setStrNumeroInteressado($arrTbVincularLancamento[12]);
        $objMdLitLancamentoDTO->setNumIdMdLitNumeroInteressado($arrTbVincularLancamento['selNumeroInteressado']);
        $objMdLitLancamentoDTO->setStrJustificativa(null);

        //verificar se já existe lancamento para o processo
        $arrObjMdLitLancamentoDTO = $this->listarLancamentoPorProcedimento($arrTbVincularLancamento['hdnIdProcedimento']);

        $objMdLitLancamentoDTO->setDblIdProcedimento($arrTbVincularLancamento['hdnIdProcedimento']);
        $objMdLitLancamentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdLitLancamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objMdLitLancamentoDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
        $objMdLitLancamentoDTO->setStrTipoLancamento(count($arrObjMdLitLancamentoDTO) > 0? self::$TIPO_LANCAMENTO_MAJORADO:self::$TIPO_LANCAMENTO_PRINCIPAL);
        $objMdLitLancamentoDTO->setDtaIntimacao($arrTbVincularLancamento['txtDtIntimacaoAplMulta']);
        $objMdLitLancamentoDTO->setDtaDecursoPrazoRecurso($arrTbVincularLancamento['txtDtDecursoPrazoRecurso']);
        $objMdLitLancamentoDTO->setDtaPrazoDefesa($arrTbVincularLancamento['txtDtDecursoPrazo']);
        $objMdLitLancamentoDTO->setStrSinRenunciaRecorrer('N');
        //$objMdLitLancamentoDTO->setDtaPrazoDefesa($arrTbVincularLancamento['hdnDtDecursoPrazo']);
        $objMdLitLancamentoDTO->setStrSinSuspenso('N');


        $objMdLitLancamentoDTO = $this->cadastrar($objMdLitLancamentoDTO);
        return $objMdLitLancamentoDTO;

    }

    private function realizarRetificarCredito($post){
        $objInfraException      = new InfraException();
        $objMdLitLancamentoDTO  = new MdLitLancamentoDTO();
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();
        $objMdLitLancamentoRN   = new MdLitLancamentoRN();

        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade($post['hdnIdMdLitFuncionalidade']);
        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');
        $objMdLitSoapClienteRN->setSoapVersion($objMdLitIntegracaoDTO->getStrVersaoSoap());
        $objInfraException      = $this->realizarValidacoesGerais($objMdLitIntegracaoDTO, $post, $objInfraException);

        $objMdLitLancamentoDTO->retTodos(false);
        $objMdLitLancamentoDTO->setNumIdMdLitLancamento($post['selCreditosProcesso'] ? $post['selCreditosProcesso'] : $post['hdnCreditosProcesso']);

        $objMdLitLancamentoDTO = $objMdLitLancamentoRN->consultar($objMdLitLancamentoDTO);

        $montarParametroEntrada = $this->montarParametroEntradaRetificarCredito($objMdLitIntegracaoDTO, $post, $objMdLitLancamentoDTO);

        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_SIGEC_RETIF_LANCAMENTO);


        if($arrResultado) {
            $this->montarParametroSaidaRetificarCredito($objMdLitLancamentoDTO, $arrResultado, $post['hdnIdMdLitFuncionalidade']);
            $objMdLitLancamentoDTO->setDtaDecursoPrazoRecurso($post['txtDtDecursoPrazoRecurso']);
            $objMdLitLancamentoRN->alterar($objMdLitLancamentoDTO);


            return $objMdLitLancamentoDTO;
        }

    }

    private function _prepararObjLancamentoInclusao($objMdLitLancamentoNovo, $post)
    {
        $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
        $objMdLitSituacaoLancamentoDTO->retTodos(false);

        $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
        $objMdLitSituacaoLancamentoDTO = $objMdLitSituacaoLancamentoRN->consultarSituacaoCancelamento($objMdLitSituacaoLancamentoDTO);

        $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
        $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
        $objMdLitLancamentoDTO->retTodos(false);

        $objMdLitLancamentoDTO->setDblIdProcedimento($post['hdnIdProcedimento']);

        $objMdLitLancamentoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);

        if($objMdLitSituacaoLancamentoDTO){
            $objMdLitLancamentoDTO->adicionarCriterio(array('IdMdLitSituacaoLancamento', 'IdMdLitSituacaoLancamento'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL), array($objMdLitSituacaoLancamentoDTO->getNumIdMdLitSituacaoLancamento(), null), array(InfraDTO::$OPER_LOGICO_OR));
        }

        $objMdLitLancamentoRN = new MdLitLancamentoRN();
        $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);

        $objMdLitLancamentoNovo->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdLitLancamentoNovo->setDthInclusao(InfraData::getStrDataHoraAtual());
        $objMdLitLancamentoNovo->setNumIdMdLitSituacaoLancamento(null);
        $objMdLitLancamentoNovo->setStrTipoLancamento(count($arrObjMdLitLancamentoDTO) > 0? self::$TIPO_LANCAMENTO_MAJORADO:self::$TIPO_LANCAMENTO_PRINCIPAL);
        $objMdLitLancamentoNovo->setDtaDecisao($post['txtDecisaoAplicacaoMulta'] ? $post['txtDecisaoAplicacaoMulta'] : InfraData::getStrDataAtual());
        $objMdLitLancamentoNovo->setDtaIntimacao($post['txtDtIntimacaoAplMulta']);
        $objMdLitLancamentoNovo->setDtaDecursoPrazoRecurso($post['txtDtDecursoPrazoRecurso']);
        $objMdLitLancamentoNovo->setDtaPrazoDefesa($post['txtDtDecursoPrazo']);
        $objMdLitLancamentoNovo->setStrSinRenunciaRecorrer('N');
        $objMdLitLancamentoNovo->setStrSinConstituicaoDefinitiva('N');
        //$objMdLitLancamentoNovo->setDtaPrazoDefesa($post['hdnDtDecursoPrazo']);
        $objMdLitLancamentoNovo->setDblVlrSaldoDevedor($objMdLitLancamentoNovo->getDblVlrLancamento());
        $objMdLitLancamentoNovo->setStrSinSuspenso('N');
        $objMdLitLancamentoNovo->setDblVlrPago(0);

        $objMdLitLancamentoNovo = $this->cadastrar($objMdLitLancamentoNovo);
    }


    private function _montarParametroEntradaLancamentoCredito($objMdLitIntegracaoDTO, $post, &$objMdLitLancamentoNovo){
        $montarParametroEntrada = array();
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['COD_RECEITA']:
                    $objMdLitMapeaParamValorDTO = new MdLitMapeaParamValorDTO();
                    $objMdLitMapeaParamValorDTO->retTodos(false);
                    $objMdLitMapeaParamValorDTO->setNumMaxRegistrosRetorno(1);
                    $objMdLitMapeaParamValorDTO->setNumIdMdLitMapearParamEntrada($objMdLitMapearParamEntradaDTO->getNumIdMdLitMapearParamEntrada());
                    $objMdLitMapeaParamValorDTO->setNumIdMdLitTipoControle($post['hdnIdTipoControle']);

                    $objMdLitMapeaParamValorRN = new MdLitMapeaParamValorRN();
                    $objMdLitMapeaParamValorDTO = $objMdLitMapeaParamValorRN->consultar($objMdLitMapeaParamValorDTO);
                    if(!$objMdLitMapeaParamValorDTO)
                        throw new InfraException('O codigo da receita não está parametrizado. Contate o Gestor do Controle.');

                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitMapeaParamValorDTO->getStrValorDefault();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['DTA_VENCIMENTO']:

                    $dtVencimento = trim($post['txtDtVencimento']);
                    if(empty($dtVencimento)){
                        $dtVencimento = InfraData::calcularData(40, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE);
                        $post['txtDtVencimento'] = $dtVencimento;
                    }
                    $arrData = explode('/', $dtVencimento );
                    $dtVencimento = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtVencimento;
                    $objMdLitLancamentoNovo->setDtaVencimento($post['txtDtVencimento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['DTA_APLICACAO_SANCAO']:
                    $txtDecisaoAplicacaoMulta = empty($post['txtDecisaoAplicacaoMulta'])? InfraData::getStrDataAtual():$post['txtDecisaoAplicacaoMulta'];
                    $dtDecisaoAplicacaoMulta = trim($txtDecisaoAplicacaoMulta);
                    $arrData = explode('/', $dtDecisaoAplicacaoMulta );
                    $dtDecisaoAplicacaoMulta = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtDecisaoAplicacaoMulta;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['NUMERO_INTERESSADO']:

                    $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
                    $objMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoRN->retornaObjNumeroInteressado($post['selNumeroInteressado']);

                    if(!$objMdLitNumeroInteressadoDTO)
                        throw new InfraException('Selecione o Numero do interessado.');

                    //se o numero de complemento do interessado não estiver vazio e o sin_outorga estiver como N(ão)
                    if(!$objMdLitNumeroInteressadoDTO->getStrNumero()){
                        $objMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoRN->gerarNumeroComplementar($objMdLitNumeroInteressadoDTO);
                    }

                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitNumeroInteressadoDTO->getStrNumero();
                    $objMdLitLancamentoNovo->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['VALOR_RECEITA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = InfraUtil::prepararDin($post['hdnVlCreditoNaoLancado']);
                    $objMdLitLancamentoNovo->setDblVlrLancamento(str_replace('.', '', $post['hdnVlCreditoNaoLancado']));
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['JUSTIFICATIVA_LANCAMENTO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnJustificativaLancamento'];
                    $objMdLitLancamentoNovo->setStrJustificativa($post['hdnJustificativaLancamento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['NUM_PROCESSO']:
                    $objMdLitLancamentoNovo->setDblIdProcedimento($post['hdnIdProcedimento']);
                    $numProcesso = $this->retornaNumProcessoFormatado($post);
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $numProcesso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    $objMdLitLancamentoNovo->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = self::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['VALIDAR_MAJORACAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = false;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_LANCAMENTO_CREDITO['CNPJ_CPF']:
                    if(isset($post['selNumeroInteressado'])){
                        $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
                        $objMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoRN->retornaObjNumeroInteressado($post['selNumeroInteressado']);
                        if($objMdLitNumeroInteressadoDTO && $objMdLitNumeroInteressadoDTO->getDblCnpjContatoParticipante()){
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = str_pad($objMdLitNumeroInteressadoDTO->getDblCnpjContatoParticipante(),  14, '0',STR_PAD_LEFT);
                        }elseif($objMdLitNumeroInteressadoDTO && $objMdLitNumeroInteressadoDTO->getDblCpfContatoParticipante()){
                            $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] =  str_pad($objMdLitNumeroInteressadoDTO->getDblCpfContatoParticipante(),  11, '0',STR_PAD_LEFT);
                        }
                    }elseif(isset($post['hdnNumInteressado']) && !empty($post['hdnNumInteressado'])){
                        //@todo o não outorgado necessita do cpf do numero do interessado para lançar a multa, bug no majorado
                        $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
                        $objMdLitDadoInteressadoDTO->retTodos(true);
                        $objMdLitDadoInteressadoDTO->setStrNumero($post['hdnNumInteressado']);
                        $objMdLitDadoInteressadoDTO->setDblControleIdProcedimento($post['hdnIdProcedimento']);
                        $objMdLitDadoInteressadoDTO->setNumControleIdMdLitTipoControle($post['hdnIdTipoControle']);
                        $objMdLitDadoInteressadoDTO->setNumMaxRegistrosRetorno(1);

                        $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
                        $objMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->consultar($objMdLitDadoInteressadoDTO);

                        if($objMdLitDadoInteressadoDTO && $objMdLitDadoInteressadoDTO->getStrSinOutorgado() == 'N' ){
                            if($objMdLitDadoInteressadoDTO->getDblCnpjContatoParticipante()){
                                $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = str_pad($objMdLitDadoInteressadoDTO->getDblCnpjContatoParticipante(),  14, '0',STR_PAD_LEFT);
                            }elseif($objMdLitDadoInteressadoDTO->getDblCpfContatoParticipante()){
                                $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = str_pad($objMdLitDadoInteressadoDTO->getDblCpfContatoParticipante(),  11, '0',STR_PAD_LEFT);
                            }
                        }
                    }
                    break;
            }

        }
        return $montarParametroEntrada;
    }


    private function montarParametroEntradaRetificarCredito($objMdLitIntegracaoDTO, $post, MdLitLancamentoDTO $objMdLitLancamentoDTO){

        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['COD_RECEITA']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrCodigoReceita();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_VENCIMENTO']:
                    $objMdLitLancamentoDTO->setDtaVencimento($post['txtDtVencimento']);
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getDtaVencimento();
                    $arrData = explode('/', $objMdLitLancamentoDTO->getDtaVencimento() );
                    $dtaVencimento = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtaVencimento;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_APLICACAO_SANCAO']:
                    $dtDecisaoAplicacaoMulta = trim($post['txtDecisaoAplicacaoMulta']);

                    $objMdLitLancamentoDTO->setDtaDecisao($dtDecisaoAplicacaoMulta);
                    $arrData = explode('/', $dtDecisaoAplicacaoMulta );
                    $dtDecisaoAplicacaoMulta = $arrData[2].'-'.$arrData[1].'-'.$arrData[0]; //formato aaaa-mm-dd
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtDecisaoAplicacaoMulta;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['NUMERO_INTERESSADO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrNumeroInteressado();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['VALOR_TOTAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = InfraUtil::prepararDin($post['hdnVlTotalMulta']);
                    $vlTotalMulta = str_replace('.', '', $post['hdnVlTotalMulta']);

                    if($vlTotalMulta != $objMdLitLancamentoDTO->getDblVlrLancamento()){
                        $objMdLitLancamentoDTO->setDblVlrSaldoDevedor($vlTotalMulta);
                    }
                    $objMdLitLancamentoDTO->setDblVlrLancamento($vlTotalMulta);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['JUSTIFICATIVA_LANCAMENTO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $post['hdnJustificativaLancamento'];
                    $objMdLitLancamentoDTO->setStrJustificativa($post['hdnJustificativaLancamento']);
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['NUM_PROCESSO']:
                    $objMdLitLancamentoDTO->setDblIdProcedimento($post['hdnIdProcedimento']);
                    $numProcesso = $this->retornaNumProcessoFormatado($post);
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $numProcesso;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['USUARIO_INCLUSAO']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = SessaoSEI::getInstance()->getStrSiglaUsuario();
                    $objMdLitLancamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = self::$SISTEMA_ORIGEM;
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['RENUNCIA_RECURSO']:
                    $sinReducaoRenuncia = isset($post['chkReducaoRenuncia'])? 'S': 'N';

                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $sinReducaoRenuncia;
                    $objMdLitLancamentoDTO->setStrSinRenunciaRecorrer($sinReducaoRenuncia);

                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['SEQUENCIAL']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $objMdLitLancamentoDTO->getStrSequencial();
                    break;

                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_CONSTITUICAO']:
                    $sinHouveConstituicao = isset($post['chkHouveConstituicao'])? 'S': 'N';
                    $objMdLitLancamentoDTO->setStrSinConstituicaoDefinitiva($sinHouveConstituicao);

                    if(isset($post['txtDtConstituicao']) && !empty($post['txtDtConstituicao']) && isset($post['chkHouveConstituicao'])){
                        $objMdLitLancamentoDTO->setDtaConstituicaoDefinitiva($post['txtDtConstituicao']);
                        $objMdLitLancamentoDTO->setDtaIntimacaoDefinitiva($post['txtDtIntimacaoConstituicao']);
                        $arrData = explode('/', $objMdLitLancamentoDTO->getDtaConstituicaoDefinitiva() );
                        $dtConstituica = $arrData[2].'-'.$arrData[1].'-'.$arrData[0];//formato aaaa-mm-dd
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtConstituica;
                    }else{
                        $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = null;
                    }
                    break;
                case MdLitMapearParamEntradaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['DTA_INTIMACAO_DEFINITIVA']:
                    $objMdLitLancamentoDTO->setDtaIntimacaoDefinitiva($post['txtDtIntimacaoConstituicao']);
                    $dtIntimacaoDefinitiva = implode('-', array_reverse(  explode('/', $objMdLitLancamentoDTO->getDtaIntimacaoDefinitiva() ) ));
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = $dtIntimacaoDefinitiva;
                    break;
            }

        }

        //a Data do Decurso do Prazo para Defesa nao será enviado para o web-service de arrecadação é o preenchimento não e obrigatório
        $dtDecursoPrazo = trim($post['txtDtDecursoPrazo']);
        if($dtDecursoPrazo)
            $objMdLitLancamentoDTO->setDtaPrazoDefesa($dtDecursoPrazo);

        //a Data da Intimação da Decisão de Aplicação da Multa nao será enviado para o web-service de arrecadação é o preenchimento não e obrigatório
        $dtIntimacaoAplMulta = trim($post['txtDtIntimacaoAplMulta']);
        if($dtIntimacaoAplMulta)
            $objMdLitLancamentoDTO->setDtaIntimacao($dtIntimacaoAplMulta);

        //a Data da Decurso do prazo de recurso nao será enviado para o web-service de arrecadação é o preenchimento não e obrigatório
        $dtDecursoPrazoRecurso = trim($post['txtDtDecursoPrazoRecurso']);
        if($dtDecursoPrazoRecurso)
            $objMdLitLancamentoDTO->setDtaDecursoPrazoRecurso($dtDecursoPrazoRecurso);

        return $montarParametroEntrada;
    }

    private function montarParametroSaidaRetificarCredito(MdLitLancamentoDTO $objMdLitLancamento, $arrResultado, $idMdLitFuncionalidade){
        if($idMdLitFuncionalidade == MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO){
            $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
            $objMdLitIntegracaoDTO->retTodos(true);
            $objMdLitIntegracaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($idMdLitFuncionalidade);

            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultaMapeaEntradaSaida($objMdLitIntegracaoDTO);

            if(empty($arrResultado))
                return null;

            if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO()))
                throw new InfraException('Os parâmetros de entrada e saída não foram parametrizado. Contate o Gestor do Controle.');

            foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO){
                switch ($objMdLitMapearParamSaidaDTO->getNumIdMdLitCampoIntegracao()){
                    case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['SEQUENCIAL']:
                        $objMdLitLancamento->setStrSequencial($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;

                    case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['LINK_BOLETO']:
                        $objMdLitLancamento->setStrLinkBoleto($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;

                    case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['COD_RECEITA']:
                        $objMdLitLancamento->setStrCodigoReceita($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;

                    case MdLitMapearParamSaidaRN::$ID_PARAM_RETIFICAR_LANCAMENTO['NUMERO_INTERESSADO']:
                        $objMdLitLancamento->setStrNumeroInteressado($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;
                }
            }

            return $objMdLitLancamento;
        }
    }

    public function retornaNumProcessoFormatado($post){
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->setDblIdProtocolo($post['hdnIdProcedimento']);
        $objProtocoloDTO->retTodos(false);
        $objProtocoloDTO->setNumMaxRegistrosRetorno(1);

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
//        $numProcesso = substr(str_replace(array('.', '/', '-'), '',$objProtocoloDTO->getStrProtocoloFormatado()), 0, -2);
        $numProcesso = str_replace(array('.', '/', '-'), '',$objProtocoloDTO->getStrProtocoloFormatado());

        return $numProcesso;
    }

    private function _montarParametroSaidaLancamentoCredito(MdLitLancamentoDTO $objMdLitLancamento, $arrResultado, $idMdLitFuncionalidade){
        if($idMdLitFuncionalidade == MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO){
            $objMdLitIntegracaoDTO = new MdLitIntegracaoDTO();
            $objMdLitIntegracaoDTO->retTodos(true);
            $objMdLitIntegracaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdLitIntegracaoDTO->setNumIdMdLitFuncionalidade($idMdLitFuncionalidade);

            $objMdLitIntegracaoRN = new MdLitIntegracaoRN();
            $objMdLitIntegracaoDTO = $objMdLitIntegracaoRN->consultaMapeaEntradaSaida($objMdLitIntegracaoDTO);

            if(empty($arrResultado))
                return null;

            if(empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO()) && empty($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO()))
                throw new InfraException('Os parâmetros de entrada e saída não foram parametrizado. Contate o Gestor do Controle.');

            foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO){
                switch ($objMdLitMapearParamSaidaDTO->getNumIdMdLitCampoIntegracao()){
                    case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['SEQUENCIAL']:
                        $objMdLitLancamento->setStrSequencial($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;

                    case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['LINK_BOLETO']:
                        $objMdLitLancamento->setStrLinkBoleto($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;

                    case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['COD_RECEITA']:
                        $objMdLitLancamento->setStrCodigoReceita($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;

                    case MdLitMapearParamSaidaRN::$ID_PARAM_LANCAMENTO_CREDITO['NUMERO_INTERESSADO']:
                        $objMdLitLancamento->setStrNumeroInteressado($arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()]);
                        break;
                }
            }
            return $objMdLitLancamento;
        }
    }

    public function retornaObjLancAlteracaoConsultaLanc($post)
    {
        $idLancamento = array_key_exists('selCreditosProcesso', $post) && $post['selCreditosProcesso'] != '' ? trim($post['selCreditosProcesso']) : '';

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
     * @return MdLitLancamentoDTO|bool| Retorna o objeto de MdLitLancamentoDTO se a consulta de lançamento ter certo, FALSE caso contrário.
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

            //calculando o valor lançado e o não lançado e a multa aplicada
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


}
?>