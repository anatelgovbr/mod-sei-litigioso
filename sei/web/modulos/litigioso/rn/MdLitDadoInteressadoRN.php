<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitDadoInteressadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitControle(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDadoInteressadoDTO->getNumIdMdLitControle())){
      $objInfraException->adicionarValidacao('controle não informado.');
    }
  }

  private function validarNumIdContato(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDadoInteressadoDTO->getNumIdContato())){
      $objInfraException->adicionarValidacao('contato não informado.');
    }
  }

  private function validarStrSinOutorgado(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDadoInteressadoDTO->getStrSinOutorgado())){
      $objInfraException->adicionarValidacao('Sinalizador de Outorgado não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdLitDadoInteressadoDTO->getStrSinOutorgado())){
        $objInfraException->adicionarValidacao('Sinalizador de Outorgado inválido.');
      }
    }
  }

    /**
     * @param MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO
     * @param InfraException $objInfraException,
     * o numero do interessado não pode ser repetido e se tiver vinculo com o lançamento lança uma exçeção
     */
    private function validarExclusaoNumeroInteressado($arrObjMdLitDadoInteressadoDTO, InfraException $objInfraException){

        foreach ($arrObjMdLitDadoInteressadoDTO as $objMdLitDadoInteressadoDTO){
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->retTodos();
            $objMdLitLancamentoDTO->setStrNumeroInteressado($objMdLitDadoInteressadoDTO->getStrNumero());
            $objMdLitLancamentoDTO->setDblIdProcedimento($objMdLitDadoInteressadoDTO->getDblControleIdProcedimento());

            $objMdLitLancamentoRN = new MdLitLancamentoRN();
            $objMdLitLancamentoDTOContar = $objMdLitLancamentoRN->contar($objMdLitLancamentoDTO);

            if($objMdLitLancamentoDTOContar){
                $objInfraException->adicionarValidacao('O número '.$objMdLitDadoInteressadoDTO->getStrNumero().' está vinculado há uma multa.');
            }

        }
    }

    protected function cadastrarControlado(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO) {
        try{

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $objInfraException->lancarValidacoes();

            $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
            $ret = $objMdLitNumeroInteressadoBD->cadastrar($objMdLitDadoInteressadoDTO);

            //Auditoria

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro cadastrando Número de interessado.',$e);
        }
    }

  protected function alterarControlado(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitDadoInteressadoDTO->isSetNumIdMdLitControle()){
        $this->validarNumIdMdLitControle($objMdLitDadoInteressadoDTO, $objInfraException);
      }
      if ($objMdLitDadoInteressadoDTO->isSetNumIdContato()){
        $this->validarNumIdContato($objMdLitDadoInteressadoDTO, $objInfraException);
      }
      if(!isset($_SESSION['ignorarValidacaoOutorgado'])) {
          if ($objMdLitDadoInteressadoDTO->isSetStrSinOutorgado()) {
              $this->validarStrSinOutorgado($objMdLitDadoInteressadoDTO, $objInfraException);
          }
      }

      $objInfraException->lancarValidacoes();

      $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
      $objMdLitDadoInteressadoBD->alterar($objMdLitDadoInteressadoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando dado complementar do interessado.',$e);
    }
  }

    protected function excluirControlado($arrObjMdLitDadoInteressadoDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dado_interessado_excluir', __METHOD__, $arrObjMdLitDadoInteressadoDTO);

            //Regras de Negocio
//      $objInfraException = new InfraException();
//      $objInfraException->lancarValidacoes();

            $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
            $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
            for($i=0;$i<count($arrObjMdLitDadoInteressadoDTO);$i++){
                $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
                $objMdLitNumeroInteressadoDTO->retTodos(false);
                $objMdLitNumeroInteressadoDTO->setNumIdMdLitDadoInteressado($arrObjMdLitDadoInteressadoDTO[$i]->getNumIdMdLitDadoInteressado());

                $arrObjMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoRN->listar($objMdLitNumeroInteressadoDTO);

                if(count($arrObjMdLitNumeroInteressadoDTO)){
                    foreach($arrObjMdLitNumeroInteressadoDTO as $itemObjMdLitNumeroInteressadoDTO){

                        //Excluir servios relacionados ao numero interessado
                        $objMdLitRelNumInterServicoDTO = new MdLitRelNumInterServicoDTO();
                        $objMdLitRelNumInterServicoDTO->retTodos();
                        $objMdLitRelNumInterServicoDTO->setNumIdMdLitNumeroInteressado($itemObjMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());
                        $objMdLitRelNumInterServicoRN = new MdLitRelNumInterServicoRN();
                        $arrObjMdLitRelNumInterServicoRN = $objMdLitRelNumInterServicoRN->listar($objMdLitRelNumInterServicoDTO);
                        $countArrObjMdLitRelNumInterServicoRN = is_array($arrObjMdLitRelNumInterServicoRN) ? count($arrObjMdLitRelNumInterServicoRN) : 0;
                        if($countArrObjMdLitRelNumInterServicoRN > 0){
                            $objMdLitRelNumInterServicoRN->excluir($arrObjMdLitRelNumInterServicoRN);
                        }

                        //Excluir modalidades relacionadas ao numero interessado
                        $objMdLitRelNumInterModaliDTO = new MdLitRelNumInterModaliDTO();
                        $objMdLitRelNumInterModaliDTO->retTodos();
                        $objMdLitRelNumInterModaliDTO->setNumIdMdLitNumeroInteressado($itemObjMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());
                        $objMdLitRelNumInterModaliRN = new MdLitRelNumInterModaliRN();
                        $arrObjMdLitRelNumInterModaliRN = $objMdLitRelNumInterModaliRN->listar($objMdLitRelNumInterModaliDTO);
                        $countArrObjMdLitRelNumInterModaliRN = is_array($arrObjMdLitRelNumInterModaliRN) ? count($arrObjMdLitRelNumInterModaliRN) : 0;
                        if($countArrObjMdLitRelNumInterModaliRN > 0){
                            $objMdLitRelNumInterModaliRN->excluir($arrObjMdLitRelNumInterModaliRN);
                        }

                        //Excluir tipo de outorga relacionadas ao numero interessado
                        $objMdLitRelNumInterTpOutorDTO = new MdLitRelNumInterTpOutorDTO();
                        $objMdLitRelNumInterTpOutorDTO->retTodos();
                        $objMdLitRelNumInterTpOutorDTO->setNumIdMdLitNumeroInteressado($itemObjMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());
                        $objMdLitRelNumInterTpOutorRN = new MdLitRelNumInterTpOutorRN();
                        $arrObjMdLitRelNumInterTpOutorRN = $objMdLitRelNumInterTpOutorRN->listar($objMdLitRelNumInterTpOutorDTO);
                        $countArrObjMdLitRelNumInterTpOutorRN = is_array($arrObjMdLitRelNumInterTpOutorRN) ? count($arrObjMdLitRelNumInterTpOutorRN) : 0;
                        if($countArrObjMdLitRelNumInterTpOutorRN > 0){
                            $objMdLitRelNumInterTpOutorRN->excluir($arrObjMdLitRelNumInterTpOutorRN);
                        }

                        //Excluir Numero Interessado
                        $objMdLitDadoInteressadoBD->excluir($itemObjMdLitNumeroInteressadoDTO);

                        //Excluir Dado Interessado
                        $objMdLitDadoInteressadoBD->excluir($arrObjMdLitDadoInteressadoDTO[$i]);
                    }
                }else {
                    $objMdLitDadoInteressadoBD->excluir($arrObjMdLitDadoInteressadoDTO[$i]);
                }
            }

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro excluindo dado complementar do interessado.',$e);
        }
    }


    private function excluirRel($idMdLitDadoInteressado, $objDTO, $objRN){
      $objDTO->retTodos(false);
      $objDTO->setNumIdMdLitDadoInteressado($idMdLitDadoInteressado);

      $arrObjDTO = $objRN->listar($objDTO);
      $objRN->excluir($arrObjDTO);
  }

  protected function consultarConectado(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitDadoInteressadoBD->consultar($objMdLitDadoInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando dado complementar do interessado.',$e);
    }
  }

  protected function listarConectado(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO) {
    try {

      //Valida Permissao
      #SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitDadoInteressadoBD->listar($objMdLitDadoInteressadoDTO);
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando dados complementares dos interessados.',$e);
    }
  }

  protected function contarConectado(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitDadoInteressadoBD->contar($objMdLitDadoInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando dados complementares dos interessados.',$e);
    }
  }

    protected function salvarDadoInteressadoControlado($arrObjmdLitDadoInteressadoDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_cadastrar');

//            $this->removerInteressado($arrObjmdLitDadoInteressadoDTO);

            $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());

            foreach ($arrObjmdLitDadoInteressadoDTO as $objMdLitDadoInteressadoDTO) {
                $objMdLitDadoInteressadoDTO->retTodos(false);
                $objMdLitDadoInteressadoDTO->setNumMaxRegistrosRetorno(1);

                $objMdLitDadoInteressadoDTOConsulta = $this->consultar($objMdLitDadoInteressadoDTO);

                //verifica se já existe registro cadastrado se não ele cadastra o registro
                if(!$objMdLitDadoInteressadoDTOConsulta){
                    $objMdLitDadoInteressadoBD->cadastrar($objMdLitDadoInteressadoDTO);
                }
            }
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrar dados complementares dos interessados.', $e);
        }

    }


    protected function removerInteressadoControlado($arrObjMdLitDadoInteressadoDTO, $boolRemoverTodosInteressados = false)
    {
        $arrIdProcedimento = InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'IdProcedimentoMdLitTipoControle');
        $arrIdContato = InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'IdContato');
        $arrIdMdLitControle = InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'IdMdLitControle');
        $arrIdMdLitDadoInteressado = InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'IdMdLitDadoInteressado');
//        $idMdLitDadoInteressado = $arrObjMdLitDadoInteressadoDTO[0]->get('IdMdLitDadoInteressado');

        //excluindo o número interessado do processo
        $mdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
        $mdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
        $mdLitNumeroInteressadoDTO->retNumIdMdLitNumeroInteressado();
        $mdLitNumeroInteressadoDTO->setNumIdMdLitDadoInteressado($idMdLitDadoInteressado);
        $mdLitNumeroInteressadoDTO = $mdLitNumeroInteressadoRN->listar($mdLitNumeroInteressadoDTO);
        $mdLitNumeroInteressadoRN->excluir($mdLitNumeroInteressadoDTO);

//        //excluindo o participante do processo
//        $objParticipanteDTO = new ParticipanteDTO();
//        $objParticipanteDTO->retNumIdParticipante();
//        $objParticipanteDTO->retNumIdContato();
//        $objParticipanteDTO->setDblIdProtocolo($arrIdProcedimento,InfraDTO::$OPER_IN);
//        $objParticipanteDTO->setNumIdContato($arrIdContato, InfraDTO::$OPER_NOT_IN);
//        $objParticipanteDTO->setStrStaParticipacao(array(ParticipanteRN::$TP_INTERESSADO), InfraDTO::$OPER_IN);
//
//        $objParticipanteRN     = new ParticipanteRN();
//        $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);
//
//        $arrIdContato = InfraArray::converterArrInfraDTO($arrObjParticipanteDTO, 'IdContato');
//
//        $objMdLitControleRN = new MdLitControleRN();
//        $arrInteressado = $objMdLitControleRN->listarInteressadoProcesso(array('arrIdContato' => $arrIdContato, 'arrIdProcedimento'=>$arrIdProcedimento[0]));
//
//        if(count($arrInteressado) && count($arrObjParticipanteDTO) ){
//            $arrInteressado = InfraArray::converterArrInfraDTO($arrInteressado, 'IdContato');
//            $ret = array();
//            foreach($arrObjParticipanteDTO as $objParticipanteDTO){
//                if(in_array($objParticipanteDTO->get('IdContato'), $arrInteressado)) {
//                    $ret[] = $objParticipanteDTO;
//                }
//            }
//            $objParticipanteRN->excluirRN0223($ret);
//        }
        $arrIdContato = InfraArray::converterArrInfraDTO($arrObjMdLitDadoInteressadoDTO, 'IdContato');
        $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
        $objMdLitDadoInteressadoDTO->retTodos(false);
        $objMdLitDadoInteressadoDTO->setNumIdMdLitDadoInteressado($arrIdMdLitDadoInteressado, InfraDTO::$OPER_IN);
        $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($arrIdMdLitControle,InfraDTO::$OPER_IN);

        $arrObjMdLitDadoInteressadoDTOConsulta = $this->listar($objMdLitDadoInteressadoDTO);

        if(count($arrObjMdLitDadoInteressadoDTOConsulta)){
            $this->excluir($arrObjMdLitDadoInteressadoDTOConsulta);
        }
    }

    /**
     * Remove o interessado e suas dependencias inclusive o interessado obrigatorio do processo para retornar o processo ao estagio inicial
     * @param $arrObjMdLitDadoInteressadoDTO
     */
    protected function removerInteressadoLimparControleLitigiosoControlado($arrObjMdLitDadoInteressadoDTO){
        $this->removerInteressadoControlado($arrObjMdLitDadoInteressadoDTO, true);
    }

    private function _excluirDadoInteressado($idMdLitControle)
    {
        SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_excluir');

        try {
            $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());

            $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
            $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);
            $objMdLitDadoInteressadoDTO->retNumIdMdLitDadoInteressado();
            $arrObjMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoBD->listar($objMdLitDadoInteressadoDTO);
            foreach ($arrObjMdLitDadoInteressadoDTO as $objMdLitDadoInteressadoDTO) {
                $objMdLitDadoInteressadoBD->excluir($objMdLitDadoInteressadoDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro excluir dados complementares dos interessados.', $e);
        }
    }

    public function retornaObjDadoInteressadoPorNumeroInteressado($post)
    {
        $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
        $objMdLitDadoInteressadoDTO->retTodos(true);
        $objMdLitDadoInteressadoDTO->setNumIdMdLitDadoInteressado($post['selNumeroInteressado']);

        $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
        $objMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->consultar($objMdLitDadoInteressadoDTO);

        if (!$objMdLitDadoInteressadoDTO) {
            throw new InfraException('O Número de Complemento do Interessado não foi selecionado.');
        }

        return $objMdLitDadoInteressadoDTO;
    }


  protected function desativarControlado($arrObjMdLitDadoInteressadoDTO){
    try {

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitDadoInteressadoDTO);$i++){
        $objMdLitDadoInteressadoBD->desativar($arrObjMdLitDadoInteressadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando dado complementar do interessado.',$e);
    }
  }
/*
  protected function reativarControlado($arrObjMdLitDadoInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitDadoInteressadoDTO);$i++){
        $objMdLitDadoInteressadoBD->reativar($arrObjMdLitDadoInteressadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando dado complementar do interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitDadoInteressadoBD->bloquear($objMdLitDadoInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando dado complementar do interessado.',$e);
    }
  }

 */

}
?>