<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRelDecisLancamentRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitDecisao(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitRelDecisLancamentDTO->getNumIdMdLitDecisao())){
      $objInfraException->adicionarValidacao(' n�o informado a decis�o.');
    }
  }

    private function validarNumIdMdLitLancamento(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdLitRelDecisLancamentDTO->getNumIdMdLitLancamento())){
            $objInfraException->adicionarValidacao(' n�o informado o Lan�amento.');
        }
    }

  protected function cadastrarControlado(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO) {
    try{
      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_cadastrar');
        $objMdLitRelDecisLancamentDTO->retTodos();
        $ret = array();

        $arrObjMdLitRelDecisLancamentDTO = $this->listar($objMdLitRelDecisLancamentDTO);
        if(count($arrObjMdLitRelDecisLancamentDTO))
            $this->excluir($arrObjMdLitRelDecisLancamentDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdLitLancamento($objMdLitRelDecisLancamentDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      if($objMdLitRelDecisLancamentDTO->isSetArrIdMdLitDecisoes()){
          foreach ($objMdLitRelDecisLancamentDTO->getArrIdMdLitDecisoes() as $idMdLitDecisao){
              $objMdLitRelDecisLancamentDTONovo = new MdLitRelDecisLancamentDTO();
              $objMdLitRelDecisLancamentDTONovo->setNumIdMdLitDecisao($idMdLitDecisao);
              $objMdLitRelDecisLancamentDTONovo->setNumIdMdLitLancamento($objMdLitRelDecisLancamentDTO->getNumIdMdLitLancamento());

              $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
              $ret[] = $objMdLitRelDecisLancamentBD->cadastrar($objMdLitRelDecisLancamentDTONovo);
          }

          return $ret;
      }

        $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
        $ret = $objMdLitRelDecisLancamentBD->cadastrar($objMdLitRelDecisLancamentDTO);
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando decis�o do lan�amento.',$e);
    }
  }

  protected function alterarControlado(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitRelDecisLancamentDTO->isSetNumIdMdLitDecisao()){
        $this->validarNumIdMdLitDecisao($objMdLitRelDecisLancamentDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
      $objMdLitRelDecisLancamentBD->alterar($objMdLitRelDecisLancamentDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando decis�o do lan�amento.',$e);
    }
  }

  protected function excluirControlado($arrObjMdLitRelDecisLancamentDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDecisLancamentDTO);$i++){
        $objMdLitRelDecisLancamentBD->excluir($arrObjMdLitRelDecisLancamentDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo decis�o do lan�amento.',$e);
    }
  }

  protected function consultarConectado(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisLancamentBD->consultar($objMdLitRelDecisLancamentDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando decis�o do lan�amento.',$e);
    }
  }

  protected function listarConectado(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisLancamentBD->listar($objMdLitRelDecisLancamentDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando decis�es do lan�amento.',$e);
    }
  }

  protected function contarConectado(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisLancamentBD->contar($objMdLitRelDecisLancamentDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando decis�es do lan�amento.',$e);
    }
  }

  protected function vincularDecisaoComLancamentoControlado($arrObjMdLitDecisaoDTO){
      if(!is_null($arrObjMdLitDecisaoDTO)){
          $arrIdProcedimento = InfraArray::converterArrInfraDTO($arrObjMdLitDecisaoDTO, 'IdProcedimentoMdLitProcessoSituacao');
          $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
          $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
          $objMdLitLancamentoDTO->retDblIdProcedimento();
          $objMdLitLancamentoDTO->setDblIdProcedimento($arrIdProcedimento, InfraDTO::$OPER_IN);

          $objMdLitLancamentoRN = new MdLitLancamentoRN();
          $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);
          if(!count($arrObjMdLitLancamentoDTO))
              return null;

          foreach ($arrObjMdLitLancamentoDTO as $objMdLitLancamentoDTO){
              $arrObjMdLitDecisaoFiltradoDTO = InfraArray::filtrarArrInfraDTO($arrObjMdLitDecisaoDTO, 'IdProcedimentoMdLitProcessoSituacao', $objMdLitLancamentoDTO->getDblIdProcedimento());
              if(!count($arrObjMdLitDecisaoFiltradoDTO))
                  continue;

              foreach ($arrObjMdLitDecisaoFiltradoDTO as $objMdLitDecisaoFiltradoDTO){
                  $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
                  $objMdLitRelDecisLancamentDTO->setNumIdMdLitDecisao($objMdLitDecisaoFiltradoDTO->getNumIdMdLitDecisao());
                  $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($objMdLitLancamentoDTO->getNumIdMdLitLancamento());

                  $this->cadastrar($objMdLitRelDecisLancamentDTO);
              }
          }

      }

  }
/* 
  protected function desativarControlado($arrObjMdLitRelDecisLancamentDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDecisLancamentDTO);$i++){
        $objMdLitRelDecisLancamentBD->desativar($arrObjMdLitRelDecisLancamentDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando decis�o do lan�amento.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitRelDecisLancamentDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitRelDecisLancamentDTO);$i++){
        $objMdLitRelDecisLancamentBD->reativar($arrObjMdLitRelDecisLancamentDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando decis�o do lan�amento.',$e);
    }
  }

  protected function bloquearControlado(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_rel_decis_lancament_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitRelDecisLancamentBD = new MdLitRelDecisLancamentBD($this->getObjInfraIBanco());
      $ret = $objMdLitRelDecisLancamentBD->bloquear($objMdLitRelDecisLancamentDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando decis�o do lan�amento.',$e);
    }
  }

 */
}
?>