<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
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
      $objInfraException->adicionarValidacao(' não informado a decisão.');
    }
  }

    private function validarNumIdMdLitLancamento(MdLitRelDecisLancamentDTO $objMdLitRelDecisLancamentDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdLitRelDecisLancamentDTO->getNumIdMdLitLancamento())){
            $objInfraException->adicionarValidacao(' não informado o Lançamento.');
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
      throw new InfraException('Erro cadastrando decisão do lançamento.',$e);
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
      throw new InfraException('Erro alterando decisão do lançamento.',$e);
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
      throw new InfraException('Erro excluindo decisão do lançamento.',$e);
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
      throw new InfraException('Erro consultando decisão do lançamento.',$e);
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
      throw new InfraException('Erro listando decisões do lançamento.',$e);
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
      throw new InfraException('Erro contando decisões do lançamento.',$e);
    }
  }

  public function vincularDecisaoComLancamento($arrIdDecisao, $objMdLitLancamentoDTO){
      
    //SE A DECISÃO ESTIVER VINCULADA A UM LANCAMENTO NÃO FAZ NADA COM ELA
    //NÃO PODE REFAZER O VINCULO POIS PODEM TER SIDO DIVIDIDO EM ALGUM MOMENTO ENTRE LANCAMENTO INICIAL E SECUNDARIO
    //SE A DECISÃO NÃO ESTIVER VINCULADA DEVE VINCULAR A UM LANCAMENTO INICIAL (NUNCA SECUNDARIO)

    if(!empty($arrIdDecisao) && $objMdLitLancamentoDTO){
        foreach ($arrIdDecisao as $idDecisao){
            $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
            $objMdLitRelDecisLancamentDTO->setNumIdMdLitDecisao((int) $idDecisao);
            $objMdLitRelDecisLancamentDTO->retTodos();

            $objMdLitRelDecisLancamentDTO = $this->consultar($objMdLitRelDecisLancamentDTO);

            if (!$objMdLitRelDecisLancamentDTO) {
                $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
                $objMdLitRelDecisLancamentDTO->setNumIdMdLitDecisao((int) $idDecisao);
                $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($this->consultarIdLancamentoParaVinculacao($objMdLitLancamentoDTO));
                $this->cadastrar($objMdLitRelDecisLancamentDTO);

            }
        }
    }
  }

  private function consultarIdLancamentoParaVinculacao($objMdLitLancamentoDTO)
  {
      //RETORNAR O ID DO LANCAMENTO INICIAL POIS SE FOR UM LANCAMENTO SECUNDARIO NÃO DEVE VINCULAR UMA DECISÃO A ELE.
      //SOMENTE NO FRACIONAMENTO DE MULTA É POSSIVEL TRANSFERIR A DECISAO PARA O BOLETO SECUNDÁRIO
      return ($objMdLitLancamentoDTO->isSetNumIdMdLitLancamentoInicial() && $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial()) ? $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial() : $objMdLitLancamentoDTO->getNumIdMdLitLancamento();
  }

  public function atualizarRelacionamentoDecisaoLancamento($idLancamento, $arrIdDecisoes)
  {
      $objMdLitRelDecisLancamentRN = new MdLitRelDecisLancamentRN();
      $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
      $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($idLancamento);
      $objMdLitRelDecisLancamentDTO->retTodos();
      $arrRelacoes = $objMdLitRelDecisLancamentRN->listar($objMdLitRelDecisLancamentDTO);
      if (!empty($arrRelacoes)) {
          $objMdLitRelDecisLancamentRN->excluir($arrRelacoes);
      }

      foreach ($arrIdDecisoes as $idDecisao){
          $objMdLitRelDecisLancamentDTO = new MdLitRelDecisLancamentDTO();
          $objMdLitRelDecisLancamentDTO->setNumIdMdLitDecisao((int) $idDecisao);
          $objMdLitRelDecisLancamentDTO->setNumIdMdLitLancamento($idLancamento);

          $objMdLitRelDecisLancamentRN->cadastrar($objMdLitRelDecisLancamentDTO);
      }
      
  }
}
?>