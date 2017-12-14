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

  private function validarNumIdParticipante(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDadoInteressadoDTO->getNumIdParticipante())){
      $objInfraException->adicionarValidacao('participante não informado.');
    }
  }

  private function validarNumNumero(MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitDadoInteressadoDTO->getNumNumero())){
      $objMdLitDadoInteressadoDTO->setNumNumero(null);
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
     * @param $arrPostDadosComplementares[0] Número
     * @param $arrPostDadosComplementares[1] Serviço
     * @param $arrPostDadosComplementares[2] Modalidade
     * @param $arrPostDadosComplementares[3] Abrangências
     * @param $arrPostDadosComplementares[4] Estados
     * @param $arrPostDadosComplementares[5] Cidades
     * @param $arrPostDadosComplementares[6] IdServiços
     * @param $arrPostDadosComplementares[7] IdModalidades
     * @param $arrPostDadosComplementares[8] IdAbrangencias
     * @param $arrPostDadosComplementares[9] IdEstados
     * @param $arrPostDadosComplementares[10] IdCidades
     * @param $arrPostDadosComplementares[11] IdContato
     * @param $arrPostDadosComplementares[12] sin_outorga
     * @param $arrPostDadosComplementares[13] id_md_lit_dado_complementar
     * @param $arrPostDadosComplementares[id_procedimento] id_procedimento
     * @param $arrPostDadosComplementares[id_md_lit_controle] id_md_lit_controle
     *
     */
  protected function cadastrarControlado($arrPostDadosComplementares) {
    try{

      //Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dado_interessado_cadastrar', __METHOD__, $arrPostDadosComplementares);

        $idProcedimento = $arrPostDadosComplementares['id_procedimento'];
        $idMdLitControle = $arrPostDadosComplementares['id_md_lit_controle'];
        unset($arrPostDadosComplementares['id_procedimento']);
        unset($arrPostDadosComplementares['id_md_lit_controle']);

        $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());

        //deletando os dados interessados que não vieram por post
        $arrIdParticipante = array();
        $arrIdMdLitDadoInteressado = array();

        foreach ($arrPostDadosComplementares as $dadosComplementares){

            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->retNumIdParticipante();
            $objParticipanteDTO->retNumIdContato();
            $objParticipanteDTO->setDblIdProtocolo($idProcedimento);
            $objParticipanteDTO->setNumIdContato($dadosComplementares[11]);
            $objParticipanteDTO->setStrStaParticipacao(array(ParticipanteRN::$TP_INTERESSADO), InfraDTO::$OPER_IN);

            $objParticipanteRN     = new ParticipanteRN();
            $objParticipanteDTO    = $objParticipanteRN->consultarRN1008($objParticipanteDTO);
            $arrIdParticipante[]   = $objParticipanteDTO->getNumIdParticipante();
            if($dadosComplementares[13] != 'null')
                $arrIdMdLitDadoInteressado[] = $dadosComplementares[13];
        }

        $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
        if(count($arrIdMdLitDadoInteressado)) {
            $objMdLitDadoInteressadoDTO->setNumIdMdLitDadoInteressado($arrIdMdLitDadoInteressado, InfraDTO::$OPER_NOT_IN);

            $objMdLitDadoInteressadoDTO->retTodos(false);
            $objMdLitDadoInteressadoDTO->setNumIdParticipante($arrIdParticipante, InfraDTO::$OPER_IN);
            $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);

            $arrObjMdLitDadoInteressadoDTO = $this->listar($objMdLitDadoInteressadoDTO);

            if (count($arrObjMdLitDadoInteressadoDTO))
                $this->excluir($arrObjMdLitDadoInteressadoDTO);
        }

        foreach ($arrPostDadosComplementares as $dadosComplementares){

            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->retNumIdParticipante();
            $objParticipanteDTO->retNumIdContato();
            $objParticipanteDTO->setDblIdProtocolo($idProcedimento);
            $objParticipanteDTO->setNumIdContato($dadosComplementares[11]);
            $objParticipanteDTO->setStrStaParticipacao(array(ParticipanteRN::$TP_INTERESSADO), InfraDTO::$OPER_IN);

            $objParticipanteRN     = new ParticipanteRN();
            $objParticipanteDTO    = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

            $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
            $objMdLitDadoInteressadoDTO->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());
            $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);
            $objMdLitDadoInteressadoDTO->setStrNumero($dadosComplementares[0]);
            $objMdLitDadoInteressadoDTO->setStrSinOutorgado($dadosComplementares[12]);

            if($dadosComplementares[13] != 'null'){
                $objMdLitDadoInteressadoDTO->setNumIdMdLitDadoInteressado($dadosComplementares[13]);
                $objMdLitDadoInteressadoBD->alterar($objMdLitDadoInteressadoDTO);

                $this->excluirRel($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterServicoDTO(), new MdLitRelDadoInterServicoRN());
                $this->excluirRel($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterModaliDTO(), new MdLitRelDadoInterModaliRN());
                $this->excluirRel($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterAbrangDTO(), new MdLitRelDadoInterAbrangRN());
                $this->excluirRel($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterEstadoDTO(), new MdLitRelDadoInterEstadoRN());
                $this->excluirRel($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterCidadeDTO(), new MdLitRelDadoInterCidadeRN());

            }else{
                $objMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoBD->cadastrar($objMdLitDadoInteressadoDTO);
            }

            $arrIdServico = $dadosComplementares[6] != 'null' && !InfraString::isBolVazia($dadosComplementares[6]) ? explode(',€ ', $dadosComplementares[6]) : null;
            $arrIdModalidades = $dadosComplementares[7] != 'null' && !InfraString::isBolVazia($dadosComplementares[7]) ? explode(',€ ', $dadosComplementares[7]) : null;
            $arrIdAbrangencia = $dadosComplementares[8] != 'null' && !InfraString::isBolVazia($dadosComplementares[8]) ? explode(',€ ', $dadosComplementares[8]) : null;
            $arrIdEstados = $dadosComplementares[9] != 'null' && !InfraString::isBolVazia($dadosComplementares[9]) ? explode(',€ ', $dadosComplementares[9]) : null;
            $arrIdCidades = $dadosComplementares[10] != 'null' && !InfraString::isBolVazia($dadosComplementares[10]) ? explode(',€ ', $dadosComplementares[10]) : null;

            $this->cadastrarRel(new MdLitRelDadoInterServicoRN(), new MdLitRelDadoInterServicoDTO(), $arrIdServico, $objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(), 'IdMdLitServico');
            $this->cadastrarRel(new MdLitRelDadoInterModaliRN(), new MdLitRelDadoInterModaliDTO(), $arrIdModalidades, $objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(), 'IdMdLitModalidade');
            $this->cadastrarRel(new MdLitRelDadoInterAbrangRN(), new MdLitRelDadoInterAbrangDTO(), $arrIdAbrangencia, $objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(), 'IdMdLitAbrangencia');
            $this->cadastrarRel(new MdLitRelDadoInterEstadoRN(), new MdLitRelDadoInterEstadoDTO(), $arrIdEstados, $objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(), 'IdUf');
            $this->cadastrarRel(new MdLitRelDadoInterCidadeRN(), new MdLitRelDadoInterCidadeDTO(), $arrIdCidades, $objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado(), 'IdCidade');

        }

        return $objMdLitDadoInteressadoDTO;
    }catch(Exception $e){
      throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
    }
  }

    private function cadastrarRel(InfraRN $objRN, InfraDTO $objDTO, $arrId, $idMdLitDadoInteressado, $strAtributo){
        if($arrId){
            foreach ($arrId as $id){
                $objDTO->setNumIdMdLitDadoInteressado($idMdLitDadoInteressado);
                $objDTO->set($strAtributo, $id);

                $objRN->cadastrar($objDTO);
            }
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
      if ($objMdLitDadoInteressadoDTO->isSetNumIdParticipante()){
        $this->validarNumIdParticipante($objMdLitDadoInteressadoDTO, $objInfraException);
      }
      if ($objMdLitDadoInteressadoDTO->isSetNumNumero()){
        $this->validarNumNumero($objMdLitDadoInteressadoDTO, $objInfraException);
      }
      if ($objMdLitDadoInteressadoDTO->isSetStrSinOutorgado()){
        $this->validarStrSinOutorgado($objMdLitDadoInteressadoDTO, $objInfraException);
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
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitDadoInteressadoDTO);$i++){
          //servico
          $this->excluirRel($arrObjMdLitDadoInteressadoDTO[$i]->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterServicoDTO(), new MdLitRelDadoInterServicoRN());
          //modalidade
          $this->excluirRel($arrObjMdLitDadoInteressadoDTO[$i]->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterModaliDTO(), new MdLitRelDadoInterModaliRN());
          //abrangencia
          $this->excluirRel($arrObjMdLitDadoInteressadoDTO[$i]->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterAbrangDTO(), new MdLitRelDadoInterAbrangRN());
          //estado
          $this->excluirRel($arrObjMdLitDadoInteressadoDTO[$i]->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterEstadoDTO(), new MdLitRelDadoInterEstadoRN());
          //cidade
          $this->excluirRel($arrObjMdLitDadoInteressadoDTO[$i]->getNumIdMdLitDadoInteressado(),new MdLitRelDadoInterCidadeDTO(), new MdLitRelDadoInterCidadeRN());

        $objMdLitDadoInteressadoBD->excluir($arrObjMdLitDadoInteressadoDTO[$i]);
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

    protected function salvarDadoInteressadoControlado($arrParametros)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_cadastrar');

            $objMdLitDadoInteressadoBD = new MdLitDadoInteressadoBD($this->getObjInfraIBanco());

            $this->_excluirDadoInteressado($arrParametros['idMdLitControle']);
            foreach ($arrParametros['arrIdContato'] as $idContato) {
                $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
                $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($arrParametros['idMdLitControle']);
                $objMdLitDadoInteressadoDTO->setNumIdContato($idContato);
                $objMdLitDadoInteressadoBD->cadastrar($objMdLitDadoInteressadoDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrar dados complementares dos interessados.', $e);
        }

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

    public function retornaObjDadoInteressadoPorFistel($post)
    {
        $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
        $objMdLitDadoInteressadoDTO->retTodos(false);
        $objMdLitDadoInteressadoDTO->setNumIdMdLitDadoInteressado($post['selFistel']);

        $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
        $objMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->consultar($objMdLitDadoInteressadoDTO);

        if (!$objMdLitDadoInteressadoDTO) {
            throw new InfraException('O Fistel não foi selecionado.');
        }

        return $objMdLitDadoInteressadoDTO;
    }

/*
  protected function desativarControlado($arrObjMdLitDadoInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_dado_interessado_desativar');

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