<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/03/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitParametrizarInteressadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitTipoControle(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitParametrizarInteressadoDTO->getNumIdMdLitTipoControle())){
      $objInfraException->adicionarValidacao('Tipo de controle não informado.');
    }
  }

  private function validarNumIdMdLitNomeFuncional(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional())){
      $objInfraException->adicionarValidacao('Nome funcional não informado.');
    }
  }

  private function validarStrSinExibe(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitParametrizarInteressadoDTO->getStrSinExibe())){
      $objInfraException->adicionarValidacao('Sinalizador de Exibe não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitParametrizarInteressadoDTO->getStrSinExibe())){
        $objInfraException->adicionarValidacao('Sinalizador de Exibe inválido.');
      }
    }
  }

  private function validarStrSinObrigatorio(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio())){
      $objInfraException->adicionarValidacao('Sinalizador de Obrigatório não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitParametrizarInteressadoDTO->getStrSinObrigatorio())){
        $objInfraException->adicionarValidacao('Sinalizador de Obrigatório inválido.');
      }
    }
  }

  private function validarStrLabelCampo(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitParametrizarInteressadoDTO->getStrLabelCampo())){
        $objMdLitParametrizarInteressadoDTO->setStrLabelCampo(null);
    }else{
      $objMdLitParametrizarInteressadoDTO->setStrLabelCampo(trim($objMdLitParametrizarInteressadoDTO->getStrLabelCampo()));

      if (strlen($objMdLitParametrizarInteressadoDTO->getStrLabelCampo())>25){
        $objInfraException->adicionarValidacao('Label do campo possui tamanho superior a 25 caracteres.');
      }
    }
  }

  private function validarNumTamanho(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitParametrizarInteressadoDTO->getNumTamanho())){
      $objMdLitParametrizarInteressadoDTO->setNumTamanho(null);
    }

    if (strlen($objMdLitParametrizarInteressadoDTO->getNumTamanho())>3){
      $objInfraException->adicionarValidacao('Tamanho possui tamanho superior a 3 caracteres.');
    }
  }

  private function validarStrDescricaoAjuda(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda())){
      $objMdLitParametrizarInteressadoDTO->setStrDescricaoAjuda(null);
    }else{
      $objMdLitParametrizarInteressadoDTO->setStrDescricaoAjuda(trim($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda()));

      if (strlen($objMdLitParametrizarInteressadoDTO->getStrDescricaoAjuda())>150){
        $objInfraException->adicionarValidacao('Descrição da ajuda possui tamanho superior a 150 caracteres.');
      }
    }
  }

  private function validarStrSinCampoMapeado(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado())){
      $objInfraException->adicionarValidacao('Sinalizador de Campo mapeado não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitParametrizarInteressadoDTO->getStrSinCampoMapeado())){
        $objInfraException->adicionarValidacao('Sinalizador de Campo mapeado inválido.');
      }
    }
  }

  protected function cadastrarMultiplosControlado($arrObjParametroSalvar) {
    try{
        $arrObjMdLitParametrizarInteressadoDTO = $arrObjParametroSalvar['MdLitParametrizarInteressadoDTO'];
        $objTipoControleLitigiosoDTO        = $arrObjParametroSalvar['MdLitTipoControleDTO'];

        //Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_parametrizar_interessado_cadastrar', __METHOD__, $arrObjMdLitParametrizarInteressadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO ){
          $this->validarNumIdMdLitTipoControle($objMdLitParametrizarInteressadoDTO, $objInfraException);
          $this->validarNumIdMdLitNomeFuncional($objMdLitParametrizarInteressadoDTO, $objInfraException);
          $this->validarStrSinExibe($objMdLitParametrizarInteressadoDTO, $objInfraException);
          $this->validarStrSinObrigatorio($objMdLitParametrizarInteressadoDTO, $objInfraException);
          $this->validarStrLabelCampo($objMdLitParametrizarInteressadoDTO, $objInfraException);
          $this->validarNumTamanho($objMdLitParametrizarInteressadoDTO, $objInfraException);
          $this->validarStrDescricaoAjuda($objMdLitParametrizarInteressadoDTO, $objInfraException);
          $this->validarStrSinCampoMapeado($objMdLitParametrizarInteressadoDTO, $objInfraException);
          $objInfraException->lancarValidacoes();

          if ($objMdLitParametrizarInteressadoDTO->getNumIdMdLitParamInteressado()==null){
              $objMdLitParametrizarInteressadoDTO->setNumIdMdLitParamInteressado($this->getObjInfraIBanco()->getValorSequencia('seq_md_lit_param_interessado'));
          }

      }

        $mdLitTipoControleRN = new MdLitTipoControleRN();
        $mdLitTipoControleRN->alterarSinParamModalComplInteressado($objTipoControleLitigiosoDTO);

       $objMdLitParametrizarInteressadoBD   = new MdLitParametrizarInteressadoBD($this->getObjInfraIBanco());

       $ret = $objMdLitParametrizarInteressadoBD->cadastrar($arrObjMdLitParametrizarInteressadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando interessado.',$e);
    }
  }

  protected function alterarMultiplosControlado($arrObjParametroSalvar){
    try {
        $arrObjMdLitParametrizarInteressadoDTO = $arrObjParametroSalvar['MdLitParametrizarInteressadoDTO'];
        $objTipoControleLitigiosoDTO        = $arrObjParametroSalvar['MdLitTipoControleDTO'];

        //Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_parametrizar_interessado_alterar', __METHOD__, $arrObjMdLitParametrizarInteressadoDTO);

        $mdLitTipoControleRN = new MdLitTipoControleRN();
        $mdLitTipoControleRN->alterarSinParamModalComplInteressado($objTipoControleLitigiosoDTO);

        $objInfraException = new InfraException();
        foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) {
            if ($objMdLitParametrizarInteressadoDTO->isSetNumIdMdLitTipoControle()) {
              $this->validarNumIdMdLitTipoControle($objMdLitParametrizarInteressadoDTO, $objInfraException);
            }
            if ($objMdLitParametrizarInteressadoDTO->isSetNumIdMdLitNomeFuncional()) {
              $this->validarNumIdMdLitNomeFuncional($objMdLitParametrizarInteressadoDTO, $objInfraException);
            }
            if ($objMdLitParametrizarInteressadoDTO->isSetStrSinExibe()) {
              $this->validarStrSinExibe($objMdLitParametrizarInteressadoDTO, $objInfraException);
            }
            if ($objMdLitParametrizarInteressadoDTO->isSetStrSinObrigatorio()) {
              $this->validarStrSinObrigatorio($objMdLitParametrizarInteressadoDTO, $objInfraException);
            }
            if ($objMdLitParametrizarInteressadoDTO->isSetStrLabelCampo()) {
              $this->validarStrLabelCampo($objMdLitParametrizarInteressadoDTO, $objInfraException);
            }
            if ($objMdLitParametrizarInteressadoDTO->isSetNumTamanho()) {
              $this->validarNumTamanho($objMdLitParametrizarInteressadoDTO, $objInfraException);
            }
            if ($objMdLitParametrizarInteressadoDTO->isSetStrDescricaoAjuda()) {
              $this->validarStrDescricaoAjuda($objMdLitParametrizarInteressadoDTO, $objInfraException);
            }
            if ($objMdLitParametrizarInteressadoDTO->isSetStrSinCampoMapeado()) {
              $this->validarStrSinCampoMapeado($objMdLitParametrizarInteressadoDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdLitParametrizarInteressadoBD = new MdLitParametrizarInteressadoBD($this->getObjInfraIBanco());
            $objMdLitParametrizarInteressadoBD->alterar($objMdLitParametrizarInteressadoDTO);
        }

    }catch(Exception $e){
      throw new InfraException('Erro alterando parametrizar dados complementares do interessado.',$e);
    }
  }

  protected function excluirMultiplosControlado($arrObjParametro){
    try {
        $arrObjMdLitParametrizarInteressadoDTO = $arrObjParametro['MdLitParametrizarInteressadoDTO'];
        $objTipoControleLitigiosoDTO        = $arrObjParametro['MdLitTipoControleDTO'];
        //Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_parametrizar_interessado_excluir', __METHOD__, $arrObjParametro);

        $mdLitTipoControleRN                = new MdLitTipoControleRN();
        $objMdLitParametrizarInteressadoBD  = new MdLitParametrizarInteressadoBD($this->getObjInfraIBanco());

        $mdLitTipoControleRN->alterarSinParamModalComplInteressado($objTipoControleLitigiosoDTO);

        foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) {
            $objMdLitParametrizarInteressadoBD->excluir($objMdLitParametrizarInteressadoDTO);
        }
        //Auditoria

    }catch(Exception $e){
        throw new InfraException('Erro excluindo interessado.',$e);
    }
  }

  protected function excluirPorTipoControleControlado(MdLitTipoControleDTO $objMdLitTipoControleDTO){
      try{
          $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();
          $objMdLitParametrizarInteressadoBD  = new MdLitParametrizarInteressadoBD($this->getObjInfraIBanco());
          $objMdLitParametrizarInteressadoDTO->retTodos();
          $objMdLitParametrizarInteressadoDTO->setNumIdMdLitTipoControle($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());

          $arrObjMdLitParametrizarInteressadoDTO = $this->listar($objMdLitParametrizarInteressadoDTO);

          if($arrObjMdLitParametrizarInteressadoDTO){
              foreach ($arrObjMdLitParametrizarInteressadoDTO as $objMdLitParametrizarInteressadoDTO) {
                  $objMdLitParametrizarInteressadoBD->excluir($objMdLitParametrizarInteressadoDTO);
              }
          }

      }catch(Exception $e){
        throw new InfraException('Erro excluindo interessado.',$e);
      }

  }

  protected function consultarConectado(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_parametrizar_interessado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitParamInteressadoBD = new MdLitParametrizarInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitParamInteressadoBD->consultar($objMdLitParametrizarInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando interessado.',$e);
    }
  }

  protected function listarConectado(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_parametrizar_interessado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitParametrizarInteressadoBD = new MdLitParametrizarInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitParametrizarInteressadoBD->listar($objMdLitParametrizarInteressadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando interessados.',$e);
    }
  }

  protected function listarPorTipoControleConectado($idMdLitTipoControle){
      try{

        SessaoSEI::getInstance()->validarPermissao('md_lit_parametrizar_interessado_listar');

        $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();
        $objMdLitParametrizarInteressadoBD = new MdLitParametrizarInteressadoBD($this->getObjInfraIBanco());

        $objMdLitParametrizarInteressadoDTO->retTodos(true);
        $objMdLitParametrizarInteressadoDTO->setNumIdMdLitTipoControle($idMdLitTipoControle);

        $ret = $objMdLitParametrizarInteressadoBD->listar($objMdLitParametrizarInteressadoDTO);

        if(empty($ret)){
            $objNomeFuncionalDTO = new MdLitNomeFuncionalDTO();
            $objNomeFuncionaRN = new MdLitNomeFuncionalRN();

            $objNomeFuncionalDTO->retTodos();
//                $objNomeFuncionalDTO->setOrdIdMdLitNomeFuncional(InfraDTO::$TIPO_ORDENACAO_ASC);

            $arrObjNomeFuncionalDTO = $objNomeFuncionaRN->listar($objNomeFuncionalDTO);

            foreach ($arrObjNomeFuncionalDTO as $objNomeFuncionalDTO) {
                $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();
                $objMdLitParametrizarInteressadoDTO->setStrNomeMdLitNomeFuncional($objNomeFuncionalDTO->getStrNome());
                $objMdLitParametrizarInteressadoDTO->setNumIdMdLitNomeFuncional($objNomeFuncionalDTO->getNumIdMdLitNomeFuncional());



                //populando o resto dos atributos para não dar erro de falta de valor
                $objMdLitParametrizarInteressadoDTO->setStrSinExibe(null);
                $objMdLitParametrizarInteressadoDTO->setStrSinObrigatorio(null);
                $objMdLitParametrizarInteressadoDTO->setStrLabelCampo(null);
                $objMdLitParametrizarInteressadoDTO->setNumTamanho(null);
                $objMdLitParametrizarInteressadoDTO->setStrDescricaoAjuda(null);
                $objMdLitParametrizarInteressadoDTO->setNumIdMdLitTipoControle($idMdLitTipoControle);
                $objMdLitParametrizarInteressadoDTO->setNumIdMdLitParamInteressado(null);

                $ret[] = $objMdLitParametrizarInteressadoDTO;
            }
        }

        $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
        foreach ($ret as $objMdLitParametrizarInteressadoDTO){
            //verificar se o campo está mapeado
            $sinMapeado = 'N';
            $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();
            $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional($objMdLitParametrizarInteressadoDTO->getNumIdMdLitNomeFuncional());
            $objMdLitMapearParamEntradaDTO->setNumIdFuncionalidadeMdLitIntegracao(MdLitIntegracaoRN::$DADOS_COMPL_INTERESSADO_CONSULTA);

            if($objMdLitMapearParamEntradaRN->contar($objMdLitMapearParamEntradaDTO) > 0 ){
                $sinMapeado = 'S';
            }

            $objMdLitParametrizarInteressadoDTO->setStrSinCampoMapeado($sinMapeado);
        }
        //Auditoria
        return $ret;

      }catch (Exception $e){
          throw new InfraException('Erro listando interessado ', $e);
      }
  }

  protected function contarConectado(MdLitParametrizarInteressadoDTO $objMdLitParametrizarInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_parametrizar_interessado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitParametrizarInteressadoBD = new MdLitParametrizarInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitParametrizarInteressadoBD->contar($objMdLitParametrizarInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando interessados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitParamInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_param_interessado_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitParamInteressadoBD = new MdLitParamInteressadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitParamInteressadoDTO);$i++){
        $objMdLitParamInteressadoBD->desativar($arrObjMdLitParamInteressadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitParamInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_param_interessado_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitParamInteressadoBD = new MdLitParamInteressadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitParamInteressadoDTO);$i++){
        $objMdLitParamInteressadoBD->reativar($arrObjMdLitParamInteressadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitParamInteressadoDTO $objMdLitParamInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_param_interessado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitParamInteressadoBD = new MdLitParamInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitParamInteressadoBD->bloquear($objMdLitParamInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando interessado.',$e);
    }
  }

 */
}
?>