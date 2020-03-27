<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitDecisaoRN extends InfraRN {

    public static $STA_LOCALIDADE_NACIONAL = 'N';
    public static $STA_LOCALIDADE_UF = 'U';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitProcessoSituacao(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdMdLitProcessoSituacao())){
      $objInfraException->adicionarValidacao('situação não informada.');
    }
  }

  private function validarNumIdMdLitRelDisNorConCtr(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdMdLitRelDisNorConCtr())){
      $objInfraException->adicionarValidacao('infração não informada.');
    }
  }

  private function validarNumIdMdLitObrigacao(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdMdLitObrigacao())){
      $objMdLitDecisaoDTO->setNumIdMdLitObrigacao(null);
    }
  }

  private function validarNumIdMdLitTipoDecisao(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdMdLitTipoDecisao())){
      $objMdLitDecisaoDTO->setNumIdMdLitTipoDecisao(null);
    }
  }

  private function validarNumIdMdLitEspecieDecisao(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdMdLitEspecieDecisao())){
      $objMdLitDecisaoDTO->setNumIdMdLitEspecieDecisao(null);
    }
  }

  private function validarDblMulta(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getDblMulta())){
      $objMdLitDecisaoDTO->setDblMulta(null);
    }
  }

  private function validarNumPrazo(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumPrazo())){
      $objMdLitDecisaoDTO->setNumPrazo(null);
    }
  }

  private function validarStrSinAtivo(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitDecisaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarNumIdUsuario(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('usuario não informado.');
    }
  }

  private function validarNumIdUnidade(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('unidade não informada.');
    }
  }

  protected function cadastrarControlado($arrDecisao) {
    try{
        //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_cadastrar');

        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retTodos();
        $objMdLitProcessoSituacaoDTO->setStrSinDecisoriaSit('S');
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($arrDecisao['id_procedimento']);
        $objMdLitProcessoSituacaoDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitProcessoSituacaoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $objMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->consultar($objMdLitProcessoSituacaoDTO);

        if(!$objMdLitProcessoSituacaoDTO)
            return null;



        $idMdLitProcessoSituacao = $objMdLitProcessoSituacaoDTO->getNumIdMdLitProcessoSituacao();

        //Excluindo as decisões vinculado a UF
        $objMdLitRelDecisaoUfRN = new MdLitRelDecisaoUfRN();
        $arrDecisaoPorProcessoSitucacao = $this->listarPorProcessoSituacao($idMdLitProcessoSituacao);
        if(count($arrDecisaoPorProcessoSitucacao)){
            $objMdLitRelDecisaoUfRN->excluirPorDecisao(InfraArray::converterArrInfraDTO($arrDecisaoPorProcessoSitucacao,'IdMdLitDecisao'));
        }
        $valorRestanteDecisao = $this->calcularValorRestanteDecisao($arrDecisao['lista'], $arrDecisao['valor_lancamento']);

        $ret = array();
        foreach ($arrDecisao['lista'] as $decisao){
            $decisao[4] = str_replace('.', '',$decisao[4]);
            $decisao[5] = str_replace('.', '',$decisao[5]);
            $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
            $objMdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();

            //se decisão nao informada
            if($decisao[3] == "null"){
                $tipoDecisao = null;
            } else {
                $arrIdEspecieDesicao['arrEspeciesId'][0] = $decisao[3];
                $mdLitEspecieDecisaoDTO = $objMdLitEspecieDecisaoRN->getEspecieDecisoesById($arrIdEspecieDesicao)[0];
                $tipoDecisao = $mdLitEspecieDecisaoDTO->getStrStaTipoIndicacaoMulta();
            }

            //salvar o valor da multa de acordo com o tipo, conforme definicão do gestor
            switch ($tipoDecisao) {
                case MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO:
                    $objMdLitDecisaoDTO->setDblMulta($decisao[4]);
                    $objMdLitDecisaoDTO->setDblValorMultaSemIntegracao('');
                    break;
                case MdLitEspecieDecisaoDTO::$TIPO_MULTA_INDICACAO_VALOR:
                    $objMdLitDecisaoDTO->setDblValorMultaSemIntegracao($decisao[4]);
                    $objMdLitDecisaoDTO->setDblMulta('');
                    break;
                default:
                    $objMdLitDecisaoDTO->setDblValorMultaSemIntegracao('');
                    $objMdLitDecisaoDTO->setDblMulta('');
            }

            $objMdLitDecisaoDTO->setNumIdMdLitRelDisNorConCtr($decisao[1]);
            $objMdLitDecisaoDTO->setNumIdMdLitTipoDecisao($decisao[2]);
            $objMdLitDecisaoDTO->setNumIdMdLitEspecieDecisao($decisao[3]);
            $objMdLitDecisaoDTO->setDblValorRessarcimento($decisao[5]);
            $objMdLitDecisaoDTO->setNumIdMdLitObrigacao($decisao[6]);
            $objMdLitDecisaoDTO->setNumPrazo($decisao[7]);
            $objMdLitDecisaoDTO->setNumIdUsuario($decisao[8]);
            $objMdLitDecisaoDTO->setNumIdUnidade($decisao[9]);
            $objMdLitDecisaoDTO->setStrSinAtivo('S');
            $objMdLitDecisaoDTO->setDtaInclusao($decisao[13]);
            $objMdLitDecisaoDTO->setStrStaLocalidade($decisao[16]);
            $objMdLitDecisaoDTO->setStrSinCadastroParcial($decisao[18]);

            if(preg_match('/^novo_/', $decisao[0])){
                $objMdLitDecisaoDTO->setStrSinUltimaDecisao('S');
                $objMdLitDecisaoDTO->setNumIdMdLitProcessoSituacao($idMdLitProcessoSituacao);

                //cenario que so vai ocorrer na funcionalidade de vincular lançamento aonde o valor do lançamento no sistema de arrecadação
                //pode estar maior ou menor do que o usuario cadastrou na decisão assim o sistema irá alterar a primeira decisao com multa
                //com o valor restante.
                if($valorRestanteDecisao != 0 && $objMdLitDecisaoDTO->getDblMulta() > 0){
                    $valorMulta = str_replace(',','.',$objMdLitDecisaoDTO->getDblMulta());
                    $valorMulta = floatval($valorMulta);
                    $objMdLitDecisaoDTO->setDblMulta(bcadd($valorMulta, $valorRestanteDecisao,2));
                    $valorRestanteDecisao = 0;
                }

                $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
                $objMdLitDecisaoDTO = $objMdLitDecisaoBD->cadastrar($objMdLitDecisaoDTO);
                $ret[$decisao[0]] = $objMdLitDecisaoDTO->getNumIdMdLitDecisao();
            }else{
                $objMdLitDecisaoDTO->setNumIdMdLitDecisao($decisao[0]);
                $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
                $objMdLitDecisaoBD->alterar($objMdLitDecisaoDTO);
                $ret[$decisao[0]] = $objMdLitDecisaoDTO->getNumIdMdLitDecisao();
            }

            if($objMdLitDecisaoDTO->getStrStaLocalidade() == MdLitDecisaoRN::$STA_LOCALIDADE_UF){
                if(!empty($decisao[17])){
                    $arrUf = explode('#',$decisao[17]);
                    foreach ($arrUf as $idUf){
                        $objMdLitRelDecisaoUfDTO = new MdLitRelDecisaoUfDTO();
                        $objMdLitRelDecisaoUfDTO->setNumIdUf($idUf);
                        $objMdLitRelDecisaoUfDTO->setNumIdMdLitDecisao($objMdLitDecisaoDTO->getNumIdMdLitDecisao());

                        $objMdLitRelDecisaoUfRN->cadastrar($objMdLitRelDecisaoUfDTO);
                    }
                }
            }


        }

        if(count($ret)> 0) {
            $objMdLitDecisaoDesativarDTO = new MdLitDecisaoDTO();
            $objMdLitDecisaoDesativarDTO->retTodos(false);
            $objMdLitDecisaoDesativarDTO->setNumIdMdLitDecisao($ret, InfraDTO::$OPER_NOT_IN);
            $objMdLitDecisaoDesativarDTO->setNumIdMdLitProcessoSituacao($idMdLitProcessoSituacao);

            $arrObjMdLitDecisaoDesativarDTO = $this->listar($objMdLitDecisaoDesativarDTO);
            if (count($arrObjMdLitDecisaoDesativarDTO)) {
                $this->desativar($arrObjMdLitDecisaoDesativarDTO);
            }

            // adicionando as decisões anteriores como não sendo a ultima cadastrada
            $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
            $objMdLitDecisaoDTO->retTodos(false);
            $objMdLitDecisaoDTO->setNumIdMdLitDecisao($ret, InfraDTO::$OPER_NOT_IN);
            $objMdLitDecisaoDTO->setNumIdMdLitProcessoSituacao($idMdLitProcessoSituacao, InfraDTO::$OPER_DIFERENTE);
            $objMdLitDecisaoDTO->setDblIdProcedimentoMdLitProcessoSituacao($objMdLitProcessoSituacaoDTO->getDblIdProcedimento());

            $arrObjMdLitDecisaoDTO = $this->listar($objMdLitDecisaoDTO);
            if (count($arrObjMdLitDecisaoDTO)) {
                foreach ($arrObjMdLitDecisaoDTO as $objMdLitDecisaoDTO){
                    $objMdLitDecisaoDTO->setStrSinUltimaDecisao('N');
                    $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
                    $objMdLitDecisaoBD->alterar($objMdLitDecisaoDTO);
                }
            }


        }

        return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Decisão.',$e);
    }
  }

  private function calcularValorRestanteDecisao($arrDecisao, $valorTotalLancamento){
      if(empty(trim($valorTotalLancamento))){
          return 0;
      }

      $vlTotalDecisao = 0;
      $valorTotalLancamento = floatval($valorTotalLancamento);
      foreach ($arrDecisao as $decisao){
          $vlDecisao = str_replace(',','.',str_replace('.', '',$decisao[4]));
          $vlDecisao = floatval($vlDecisao);
          $vlTotalDecisao = bcadd($vlTotalDecisao, $vlDecisao, 2);
      }
      switch (bccomp($valorTotalLancamento, $vlDecisao, 2)){
          case 0:
              return 0;
              break;
          case 1:
          case -1:
              return bcsub($valorTotalLancamento, $vlTotalDecisao,2);
              break;
      }
  }
  protected  function listarPorProcessoSituacaoConectado($idMdLitProcessoSituacao){
      $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
      $objMdLitDecisaoDTO->retTodos(false);
      $objMdLitDecisaoDTO->setNumIdMdLitProcessoSituacao($idMdLitProcessoSituacao);

      $arrObjMdLitDecisaoDTO = $this->listar($objMdLitDecisaoDTO);

      return $arrObjMdLitDecisaoDTO;
  }

  protected function alterarControlado(MdLitDecisaoDTO $objMdLitDecisaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitDecisaoDTO->isSetNumIdMdLitProcessoSituacao()){
        $this->validarNumIdMdLitProcessoSituacao($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetNumIdMdLitRelDisNorConCtr()){
        $this->validarNumIdMdLitRelDisNorConCtr($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetNumIdMdLitObrigacao()){
        $this->validarNumIdMdLitObrigacao($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetNumIdMdLitTipoDecisao()){
        $this->validarNumIdMdLitTipoDecisao($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetNumIdMdLitEspecieDecisao()){
        $this->validarNumIdMdLitEspecieDecisao($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetDblMulta()){
        $this->validarDblMulta($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetNumPrazo()){
        $this->validarNumPrazo($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objMdLitDecisaoDTO, $objInfraException);
      }
      if ($objMdLitDecisaoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objMdLitDecisaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
      $objMdLitDecisaoBD->alterar($objMdLitDecisaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Decisão.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitDecisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitDecisaoDTO);$i++){
        $objMdLitDecisaoBD->excluir($arrObjMdLitDecisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Decisão.',$e);
    }
  }

  protected function consultarConectado(MdLitDecisaoDTO $objMdLitDecisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitDecisaoBD->consultar($objMdLitDecisaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Decisão.',$e);
    }
  }

  protected function listarConectado(MdLitDecisaoDTO $objMdLitDecisaoDTO) {
    try {

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitDecisaoBD->listar($objMdLitDecisaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Decisões.',$e);
    }
  }

  protected function contarConectado(MdLitDecisaoDTO $objMdLitDecisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitDecisaoBD->contar($objMdLitDecisaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Decisões.',$e);
    }
  }

  protected function desativarControlado($arrObjMdLitDecisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitDecisaoDTO);$i++){
        $objMdLitDecisaoBD->desativar($arrObjMdLitDecisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Decisão.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitDecisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitDecisaoDTO);$i++){
        $objMdLitDecisaoBD->reativar($arrObjMdLitDecisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Decisão.',$e);
    }
  }

  protected function bloquearControlado(MdLitDecisaoDTO $objMdLitDecisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_decisao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
      $ret = $objMdLitDecisaoBD->bloquear($objMdLitDecisaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Decisão.',$e);
    }
  }

  protected function retornaDadosDecisaoCadastradasConectado($idProcedimento){

      $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();

      $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
      $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
      $objMdLitProcessoSituacaoDTO->retTodos(true);
      $objMdLitProcessoSituacaoDTO->setStrSinDecisoriaSit('S');
      $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $arrObjMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->listar($objMdLitProcessoSituacaoDTO);
      $infracaoArr = array();
      $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
      $objMdLitDecisaoDTO->retTodos(true);
      $objMdLitDecisaoDTO->setDblIdProcedimentoMdLitProcessoSituacao($idProcedimento);
      if(count($arrObjMdLitProcessoSituacaoDTO))
          $objMdLitDecisaoDTO->setNumIdMdLitProcessoSituacao($arrObjMdLitProcessoSituacaoDTO[0]->getNumIdMdLitProcessoSituacao());

      $arrObjMdLitDecisaoDTO = $this->listar($objMdLitDecisaoDTO);

      foreach ($arrObjMdLitDecisaoDTO as $key => $objMdLitDecisaoDTO){

          //atendendo conforme soliticação para valores em colunas diferentes
          $valorMulta = $objMdLitDecisaoDTO->getDblMulta()?: $objMdLitDecisaoDTO->getDblValorMultaSemIntegracao();
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getNumIdMdLitDecisao();
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getNumIdMdLitRelDisNorConCtr();
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getNumIdMdLitTipoDecisao();
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getNumIdMdLitEspecieDecisao();
          $infracaoArr[$key][] = InfraUtil::formatarDin(InfraUtil::prepararDbl($valorMulta));
          $infracaoArr[$key][] = InfraUtil::formatarDin(InfraUtil::prepararDbl($objMdLitDecisaoDTO->getDblValorRessarcimento()));
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getNumIdMdLitObrigacao();
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getNumPrazo();
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getNumIdUsuario();
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getNumIdUnidade();
          $infracaoArr[$key][] = htmlspecialchars($objMdLitDecisaoDTO->getStrInfracao());
          $infracaoArr[$key][] = htmlspecialchars($objMdLitDecisaoDTO->getStrNomeMdLitTipoDecisao());
          $infracaoArr[$key][] = htmlspecialchars($objMdLitDecisaoDTO->getStrNomeMdLitEspecieDecisao());

          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getDtaInclusao();
          $infracaoArr[$key][] = '<a alt="'.$objMdLitDecisaoDTO->getStrNomeUsuario().'" title="'.$objMdLitDecisaoDTO->getStrNomeUsuario().'" class="ancoraSigla"> '.$objMdLitDecisaoDTO->getStrSiglaUsuario().' </a>';
          $infracaoArr[$key][] = '<a alt="'.$objMdLitDecisaoDTO->getStrDescricaoUnidade().'" title="'.$objMdLitDecisaoDTO->getStrDescricaoUnidade().'" class="ancoraSigla"> '.$objMdLitDecisaoDTO->getStrSiglaUnidade().' </a>';
          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getStrStaLocalidade();

          //adicionando as UF's nas decisões separados por # para popular o componente de infraTabelaDinamica
          if($objMdLitDecisaoDTO->getStrStaLocalidade() == MdLitDecisaoRN::$STA_LOCALIDADE_UF){
              $objMdLitRelDecisaoUfDTO = new MdLitRelDecisaoUfDTO();
              $objMdLitRelDecisaoUfDTO->retTodos(false);
              $objMdLitRelDecisaoUfDTO->setNumIdMdLitDecisao($objMdLitDecisaoDTO->getNumIdMdLitDecisao());

              $objMdLitRelDecisaoUfRN = new MdLitRelDecisaoUfRN();
              $arrObjMdLitRelDecisaoUfDTO = $objMdLitRelDecisaoUfRN->listar($objMdLitRelDecisaoUfDTO);
              if(count($arrObjMdLitRelDecisaoUfDTO)){
                  $arrIdUf = InfraArray::converterArrInfraDTO($arrObjMdLitRelDecisaoUfDTO, 'IdUf');
                  $infracaoArr[$key][] = implode('#',$arrIdUf);
              }

          }else{
              $infracaoArr[$key][] = '';
          }

          $infracaoArr[$key][] = $objMdLitDecisaoDTO->getStrSinCadastroParcial();
      }
      if(count($infracaoArr) > 0)
          return $infracaoArr;

      return '';
  }

    protected function verificarVinculoSituacaoDecisaoConectado($idProcedimento)
    {
        $arrSinAtivo = array('S', 'N');
        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        $objMdLitDecisaoDTO->setDblIdProcedimentoMdLitProcessoSituacao($idProcedimento);
        $objMdLitDecisaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitDecisaoDTO->retNumIdMdLitSituacao();
        $objMdLitDecisaoDTO->setStrSinAtivo($arrSinAtivo, InfraDTO::$OPER_IN);
        $count = $this->contar($objMdLitDecisaoDTO);
        $arrRet = $this->listar($objMdLitDecisaoDTO);

        $idsSituacao = array();

        if ($count > 0) {
            $idsSituacao = InfraArray::converterArrInfraDTO($arrRet, 'IdMdLitProcessoSituacao');

        }
        return $idsSituacao;
    }

    protected function listarRelatorioConectado(MdLitDecisaoDTO $objMdLitDecisaoDTO) {
        try {
            $arrObjMdLitProcessoSituacaoConclusivaDTO = $this->filtroProcessoSituacaoConclusiva($objMdLitDecisaoDTO);
            if(count($arrObjMdLitProcessoSituacaoConclusivaDTO) == 0){
                return null;
            }
            $arrIdProcedimento = InfraArray::converterArrInfraDTO($arrObjMdLitProcessoSituacaoConclusivaDTO, 'IdProcedimento');
            $objMdLitDecisaoDTO->setDblIdProcedimentoMdLitProcessoSituacao($arrIdProcedimento, InfraDTO::$OPER_IN);
            $objMdLitDecisaoDTO->setDistinct(true);
            //retorno
            $objMdLitDecisaoDTO->retNumIdMdLitProcessoSituacao();
            $objMdLitDecisaoDTO->retDblIdProcedimentoMdLitProcessoSituacao();
            $objMdLitDecisaoDTO->retStrProtocoloFormatadoProcedimento();
            $objMdLitDecisaoDTO->retStrProtocoloFormatadoProcedimento();
            $objMdLitDecisaoDTO->retStrDispositivo();
            $objMdLitDecisaoDTO->retStrNorma();
            $objMdLitDecisaoDTO->retStrConduta();
            $objMdLitDecisaoDTO->retNumIdContato();
            $objMdLitDecisaoDTO->retStrNomeContato();
            $objMdLitDecisaoDTO->retStrCpfContato();
            $objMdLitDecisaoDTO->retStrCnpjContato();
            $objMdLitDecisaoDTO->retNumIdCondutaMdLitRelDisNorConCtr();
            $objMdLitDecisaoDTO->retNumIdDispositivoNormativoMdLitRelDisNorConCtr();
            $objMdLitDecisaoDTO->setStrSinUltimaDecisao('S');

            //filtro

            $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
            $ret = $objMdLitDecisaoBD->listar($objMdLitDecisaoDTO);
//            $ret = $objMdLitDecisaoBD->listar($objMdLitDecisaoDTO, true);
//            echo $ret; exit;

            $this->inserindoDataTransitoJulgado($ret,$arrObjMdLitProcessoSituacaoConclusivaDTO);
            //Auditoria
            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro listando Decisões.',$e);
        }
    }

    private function filtroProcessoSituacaoConclusiva(MdLitDecisaoDTO $objMdLitDecisaoDTO){
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retDblIdProcedimento();
        $objMdLitProcessoSituacaoDTO->retDtaData();
        $objMdLitProcessoSituacaoDTO->setStrSinConclusivaSit('S');
        if($objMdLitDecisaoDTO->isSetNumIdMdLitTipoControleMdLitProcessoSituacao()){
            $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($objMdLitDecisaoDTO->getNumIdMdLitTipoControleMdLitProcessoSituacao());
        }
        if($objMdLitDecisaoDTO->isSetDtaTransitoJulgado()){
            $objMdLitProcessoSituacaoDTO->adicionarCriterio(array('Data','Data'),
                array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
                array($objMdLitDecisaoDTO->getDtaTransitoJulgado(),$objMdLitDecisaoDTO->getDtaCorte()),
                array(InfraDTO::$OPER_LOGICO_AND));
        }


        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        return $objMdLitProcessoSituacaoRN->listar($objMdLitProcessoSituacaoDTO);
    }

    private function inserindoDataTransitoJulgado($arrObjMdLitDecisaoDTO, $arrObjMdLitProcessoSituacaoDTO){
      foreach ($arrObjMdLitDecisaoDTO as $objMdLitDecisaoDTO){
          $arrData = InfraArray::filtrarArrInfraDTO($arrObjMdLitProcessoSituacaoDTO, 'IdProcedimento', $objMdLitDecisaoDTO->getDblIdProcedimentoMdLitProcessoSituacao());
          $objMdLitDecisaoDTO->setDtaTransitoJulgado(count($arrData) ? $arrData[0]->getDtaData(): null);
      }

      return $arrObjMdLitDecisaoDTO;
    }

    private function filtroProcessoSituacaoDecisaoUltima(MdLitDecisaoDTO $objMdLitDecisaoDTO){
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retDblIdProcedimento();
        $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
        $objMdLitProcessoSituacaoDTO->retDtaData();
        $objMdLitProcessoSituacaoDTO->setStrSinDecisoriaSit('S');
        $objMdLitProcessoSituacaoDTO->setOrdDblIdProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_ASC);
        if($objMdLitDecisaoDTO->isSetNumIdMdLitTipoControleMdLitProcessoSituacao()){
            $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($objMdLitDecisaoDTO->getNumIdMdLitTipoControleMdLitProcessoSituacao());
        }

        if($objMdLitDecisaoDTO->isSetDblIdProcedimentoMdLitProcessoSituacao()){
            $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($objMdLitDecisaoDTO->getDblIdProcedimentoMdLitProcessoSituacao(), InfraDTO::$OPER_IN);
        }

        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $arrObjMdLitProcessoSituacaoDTO =  $objMdLitProcessoSituacaoRN->listar($objMdLitProcessoSituacaoDTO);
        $arrObjMdLitProcessoSituacaoResultado = array();
        $idUltimoProcedimento = null;

        foreach ($arrObjMdLitProcessoSituacaoDTO as $objMdLitProcessoSituacaoDTO){
            $arrObjMdLitProcessoSituacaoResultado[$objMdLitProcessoSituacaoDTO->getDblIdProcedimento()] = $objMdLitProcessoSituacaoDTO;
        }

        return $arrObjMdLitProcessoSituacaoResultado;
    }

    public function migrarUltimaSituacaoDecisorioControlado(){
        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        $arrObjMdLitProcessoSituacaoDecisivaDTO = $this->filtroProcessoSituacaoDecisaoUltima($objMdLitDecisaoDTO);
        if(count($arrObjMdLitProcessoSituacaoDecisivaDTO)){
            $arrIdMdLitProcessoSituacao = InfraArray::converterArrInfraDTO($arrObjMdLitProcessoSituacaoDecisivaDTO, 'IdMdLitProcessoSituacao');

            $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
            $objMdLitDecisaoDTO->retNumIdMdLitDecisao();
            $objMdLitDecisaoDTO->retDblIdProcedimentoMdLitProcessoSituacao();
            $objMdLitDecisaoDTO->setNumIdMdLitProcessoSituacao($arrIdMdLitProcessoSituacao, InfraDTO::$OPER_IN);

            $objMdLitDecisaoBD = new MdLitDecisaoBD($this->getObjInfraIBanco());
            $arrMdLitDecisaoDTO = $objMdLitDecisaoBD->listar($objMdLitDecisaoDTO);

            if(count($arrMdLitDecisaoDTO)){
                foreach ($arrMdLitDecisaoDTO as $objMdLitDecisaoDTO){
                    $objMdLitDecisaoDTO->setStrSinUltimaDecisao('S');
                    $this->alterar($objMdLitDecisaoDTO);
                }
            }
            return $arrMdLitDecisaoDTO;
        }
    }

    public function recuperarDesicoesComMultaPorTipo($data)
    {
        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        //inner/left join
        $objMdLitDecisaoDTO->ret('IdMdLitProcessoSituacao');
        $objMdLitDecisaoDTO->ret('IdMdLitDecisao');
        $objMdLitDecisaoDTO->ret('IdDocumentoMdLitProcessoSituacao');
        $objMdLitDecisaoDTO->ret('StaTipoIndicacaoMulta');
        $objMdLitDecisaoDTO->setNumTipoFkEspecieDecisao(InfraDTO::$TIPO_FK_OBRIGATORIA);
        //where
        $objMdLitDecisaoDTO->set('IdMdLitEspecieDecisao', $data['IdMdLitEspecieDecisao']);
        $objMdLitDecisaoDTO->set('StaTipoIndicacaoMulta', $data['StaTipoIndicacaoMulta']);
        $objMdLitDecisaoDTO->set('SinGestaoMulta', 'S');

        //recupera as decisoes cadastradas por tipo
        $arrObjMdLitDecisao = $this->listar($objMdLitDecisaoDTO);
        return $arrObjMdLitDecisao;
    }

}
?>