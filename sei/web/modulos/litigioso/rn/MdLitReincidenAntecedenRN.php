<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 24/04/2018 - criado por ellyson.silva
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';


class MdLitReincidenAntecedenRN extends InfraRN {

    public static $TIPO_REINCIDENCIA    = 'R';
    public static $TIPO_ANTECEDENTE     = 'A';

    //Regra para considerar Reincid�ncia Espec�fica nos filtros
    public static $CONDUTA      = 1;
    public static $DISPOSITIVO  = 2;
    public static $DISPOSITIVO_CONDUTA = 3;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumPrazo(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitReincidenAntecedenDTO->getNumPrazo())){
      $objInfraException->adicionarValidacao('Prazo em ano n�o informado.');
    }
  }

  private function validarStrOrientacao(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitReincidenAntecedenDTO->getStrOrientacao())){
      $objInfraException->adicionarValidacao('Orienta��o n�o informada.');
    }else{
      $objMdLitReincidenAntecedenDTO->setStrOrientacao(trim($objMdLitReincidenAntecedenDTO->getStrOrientacao()));
    }
  }

  private function validarStrTipo(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitReincidenAntecedenDTO->getStrTipo())){
      $objInfraException->adicionarValidacao('tipo n�o informado.');
    }else{
      $objMdLitReincidenAntecedenDTO->setStrTipo(trim($objMdLitReincidenAntecedenDTO->getStrTipo()));

      if (strlen($objMdLitReincidenAntecedenDTO->getStrTipo())>1){
        $objInfraException->adicionarValidacao('tipo possui tamanho superior a 1 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumPrazo($objMdLitReincidenAntecedenDTO, $objInfraException);
      $this->validarStrOrientacao($objMdLitReincidenAntecedenDTO, $objInfraException);
      $this->validarStrTipo($objMdLitReincidenAntecedenDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      $ret = $objMdLitReincidenAntecedenBD->cadastrar($objMdLitReincidenAntecedenDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Reincid�ncia Espec�fica e antecedente.',$e);
    }
  }

  protected function alterarControlado(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitReincidenAntecedenDTO->isSetNumPrazo()){
        $this->validarNumPrazo($objMdLitReincidenAntecedenDTO, $objInfraException);
      }
      if ($objMdLitReincidenAntecedenDTO->isSetStrOrientacao()){
        $this->validarStrOrientacao($objMdLitReincidenAntecedenDTO, $objInfraException);
      }
      if ($objMdLitReincidenAntecedenDTO->isSetStrTipo()){
        $this->validarStrTipo($objMdLitReincidenAntecedenDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      $objMdLitReincidenAntecedenBD->alterar($objMdLitReincidenAntecedenDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Reincid�ncia Espec�fica e antecedente.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitReincidenAntecedenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitReincidenAntecedenDTO);$i++){
        $objMdLitReincidenAntecedenBD->excluir($arrObjMdLitReincidenAntecedenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Reincid�ncia Espec�fica e antecedente.',$e);
    }
  }

  protected function consultarConectado(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      $ret = $objMdLitReincidenAntecedenBD->consultar($objMdLitReincidenAntecedenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Reincid�ncia Espec�fica e antecedente.',$e);
    }
  }

  protected function listarConectado(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      $ret = $objMdLitReincidenAntecedenBD->listar($objMdLitReincidenAntecedenDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Reincid�ncias Espec�ficas e Antecedentes.',$e);
    }
  }

  protected function contarConectado(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      $ret = $objMdLitReincidenAntecedenBD->contar($objMdLitReincidenAntecedenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Reincid�ncias Espec�ficas e Antecedentes.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdLitReincidenAntecedenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitReincidenAntecedenDTO);$i++){
        $objMdLitReincidenAntecedenBD->desativar($arrObjMdLitReincidenAntecedenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Reincid�ncia Espec�fica e antecedente.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitReincidenAntecedenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitReincidenAntecedenDTO);$i++){
        $objMdLitReincidenAntecedenBD->reativar($arrObjMdLitReincidenAntecedenDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Reincid�ncia Espec�fica e antecedente.',$e);
    }
  }

  protected function bloquearControlado(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_reinciden_anteceden_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitReincidenAntecedenBD = new MdLitReincidenAntecedenBD($this->getObjInfraIBanco());
      $ret = $objMdLitReincidenAntecedenBD->bloquear($objMdLitReincidenAntecedenDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Reincid�ncia Espec�fica e antecedente.',$e);
    }
  }

 */

    protected function cadastrarReincidenciaAntecedentesControlado($dados){

        $mdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();

        //Inserindo dados reincidencia
        $mdLitReincidenAntecedenDTO->setNumPrazo($dados['txtPrazoRein']);
        $mdLitReincidenAntecedenDTO->setStrOrientacao($dados['txtOrientacoes']);
        $mdLitReincidenAntecedenDTO->setStrTipo(self::$TIPO_REINCIDENCIA);
        $mdLitReincidenAntecedenDTO->setStrTipoRegraReincidencia($dados['rd_regra_reincidencia']);
        $mdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();

        $mdLitReincidenAnteceden = $this->cadastrar($mdLitReincidenAntecedenDTO);
        $idReincidencia = $mdLitReincidenAnteceden->getNumIdMdLitReincidenAnteceden();

        $arrTipoDecisaoReinc = PaginaSEI::getInstance()->getArrItensTabelaDinamica($dados['hdnTipoDeciRein']);

        //Ids decisao da reincidencia
        $arrIdDecisao[$idReincidencia]= null;

        foreach ($arrTipoDecisaoReinc as $tpDecisao){
            $arrIdDecisao[$idReincidencia][] = $tpDecisao[0];
        }

        $mdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();

        //Inserindo dados antecedente
        $mdLitReincidenAntecedenDTO->setNumPrazo($dados['txtPrazoAntec']);
        $mdLitReincidenAntecedenDTO->setStrOrientacao($dados['txtAnteOrientacoes']);
        $mdLitReincidenAntecedenDTO->setStrTipo(self::$TIPO_ANTECEDENTE);
        $mdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();

        $mdLitReincidenAnteceden = $this->cadastrar($mdLitReincidenAntecedenDTO);
        $idAntecedente = $mdLitReincidenAnteceden->getNumIdMdLitReincidenAnteceden();

        $arrTipoDecisaoReinc = PaginaSEI::getInstance()->getArrItensTabelaDinamica($dados['hdnTipoDeciAnte']);


        //Ids decisao do antecedente

        foreach ($arrTipoDecisaoReinc as $tpDecisao){
            $arrIdDecisao[$idAntecedente][] = $tpDecisao[0];
        }

        $mdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
        $mdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();

        //Insere IdsDecisao relacionado a reincidencia
        $mdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($idReincidencia);
        $numRegistro = count($arrIdDecisao[$idReincidencia]);
        for($i= 0 ; $i < $numRegistro ; $i++){
            $mdLitRelTpDecReinAnteDTO->setNumIdRelMdLitTipoDecisao($arrIdDecisao[$idReincidencia][$i]);
            $mdLitRelTpDecReinAnteRN->cadastrar($mdLitRelTpDecReinAnteDTO);
        }

        //Insere IdsDecisao relacionado ao antecedente
        $mdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($idAntecedente);
        $numRegistro = count($arrIdDecisao[$idAntecedente]);
        for($i= 0 ; $i < $numRegistro ; $i++){
            $mdLitRelTpDecReinAnteDTO->setNumIdRelMdLitTipoDecisao($arrIdDecisao[$idAntecedente][$i]);
            $mdLitRelTpDecReinAnteRN->cadastrar($mdLitRelTpDecReinAnteDTO);
        }

        return true;
    }


    protected function alterarReincidenciaAntecedentesControlado($dados){

        $mdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();

        //Inserindo dados reincidencia
        $mdLitReincidenAntecedenDTO->setNumIdMdLitReincidenAnteceden($dados['idReincidencia']);
        $mdLitReincidenAntecedenDTO->setNumPrazo($dados['txtPrazoRein']);
        $mdLitReincidenAntecedenDTO->setStrOrientacao($dados['txtOrientacoes']);
        $mdLitReincidenAntecedenDTO->setStrTipoRegraReincidencia($dados['rd_regra_reincidencia']);
        $mdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();

        $mdLitReincidenAnteceden = $this->alterar($mdLitReincidenAntecedenDTO);
        $idReincidencia = $dados['idReincidencia'];

        $arrTipoDecisaoReinc = PaginaSEI::getInstance()->getArrItensTabelaDinamica($dados['hdnTipoDeciRein']);

        //Ids decisao da reincidencia
        $arrIdDecisao[$idReincidencia]= null;

        foreach ($arrTipoDecisaoReinc as $tpDecisao){
            $arrIdDecisao[$idReincidencia][] = $tpDecisao[0];
        }

        $mdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();

        //Inserindo dados antecedente
        $mdLitReincidenAntecedenDTO->setNumIdMdLitReincidenAnteceden($dados['idAntecedencia']);
        $mdLitReincidenAntecedenDTO->setNumPrazo($dados['txtPrazoAntec']);
        $mdLitReincidenAntecedenDTO->setStrOrientacao($dados['txtAnteOrientacoes']);
        $mdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();

        $mdLitReincidenAnteceden = $this->alterar($mdLitReincidenAntecedenDTO);
        $idAntecedente = $dados['idAntecedencia'];

        $arrTipoDecisaoReinc = PaginaSEI::getInstance()->getArrItensTabelaDinamica($dados['hdnTipoDeciAnte']);


        //Ids decisao do antecedente

        foreach ($arrTipoDecisaoReinc as $tpDecisao){
            $arrIdDecisao[$idAntecedente][] = $tpDecisao[0];
        }

        $mdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
        $mdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();

        $mdLitRelTpDecReinAnteDTO->retTodos();
        $arrmdLitRelTpDecReinAnteDTO = $mdLitRelTpDecReinAnteRN->listar($mdLitRelTpDecReinAnteDTO);
        $mdLitRelTpDecReinAnteRN->excluir($arrmdLitRelTpDecReinAnteDTO);

        //Insere IdsDecisao relacionado a reincidencia
        $mdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
        $mdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($idReincidencia);

        $numRegistro = count($arrIdDecisao[$idReincidencia]);

        for($i= 0 ; $i < $numRegistro ; $i++){
            $mdLitRelTpDecReinAnteDTO->setNumIdRelMdLitTipoDecisao($arrIdDecisao[$idReincidencia][$i]);
            $mdLitRelTpDecReinAnteRN->cadastrar($mdLitRelTpDecReinAnteDTO);
        }


        //Insere IdsDecisao relacionado ao antecedente
        $mdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
        $mdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($idAntecedente);

        $numRegistro = count($arrIdDecisao[$idAntecedente]);
        for($i= 0 ; $i < $numRegistro ; $i++){
            $mdLitRelTpDecReinAnteDTO->setNumIdRelMdLitTipoDecisao($arrIdDecisao[$idAntecedente][$i]);
            $mdLitRelTpDecReinAnteRN->cadastrar($mdLitRelTpDecReinAnteDTO);
        }

        return true;
    }

    public function adicionarFiltroInfracaoMesmaNatureza(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO, MdLitDecisaoDTO $objMdLitDecisaoDTO, $arrObjMdLitRelDispositivoNormativoCondutaControleDTO){

        switch ($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia()){
            case MdLitReincidenAntecedenRN::$CONDUTA:
                $arrIdConduta = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjMdLitRelDispositivoNormativoCondutaControleDTO, 'IdCondutaLitigioso'), null);

//                //Que cometeu Infra��es utilizando as mesmas Condutas do Processo sob o qual est� trabalhando.
                if(count($arrIdConduta)){
                    $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($arrIdConduta, InfraDTO::$OPER_IN);
                }else{
                    $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr(null);
                }
                break;
            case MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA:

                $arrNomeCriterioDispositivoNormativo = array();
                foreach ($arrObjMdLitRelDispositivoNormativoCondutaControleDTO as $key=>$objMdLitRelDispositivoNormativoCondutaControleDTO){
                    $nomeCriterio ='criterioDispositivoConduta'.$key;
                    $arrNomeCriterioDispositivoNormativo[] = $nomeCriterio;
                    $objMdLitDecisaoDTO->adicionarCriterio(array('IdCondutaMdLitRelDisNorConCtr', 'IdDispositivoNormativoMdLitRelDisNorConCtr'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL), array($objMdLitRelDispositivoNormativoCondutaControleDTO->getNumIdCondutaLitigioso(), $objMdLitRelDispositivoNormativoCondutaControleDTO->getNumIdDispositivoNormativoLitigioso()), InfraDTO::$OPER_LOGICO_AND, $nomeCriterio);
                }
                if(count($arrNomeCriterioDispositivoNormativo) > 1) {
                    $objMdLitDecisaoDTO->agruparCriterios($arrNomeCriterioDispositivoNormativo, array_fill(0, count($arrNomeCriterioDispositivoNormativo) - 1, InfraDTO::$OPER_LOGICO_OR));
                }
                break;

            case MdLitReincidenAntecedenRN::$DISPOSITIVO:
                $arrIdDispositivo = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjMdLitRelDispositivoNormativoCondutaControleDTO, 'IdDispositivoNormativoLitigioso'), null);

//                //Que cometeu Infra��es utilizando os mesmo dispositivo normativo do Processo sob o qual est� trabalhando.
                if(count($arrIdDispositivo)){
                    $objMdLitDecisaoDTO->setNumIdDispositivoNormativoMdLitRelDisNorConCtr($arrIdDispositivo, InfraDTO::$OPER_IN);
                }else{
                    $objMdLitDecisaoDTO->setNumIdDispositivoNormativoMdLitRelDisNorConCtr(null);
                }
                break;
        }

        return $objMdLitDecisaoDTO;

    }

}
