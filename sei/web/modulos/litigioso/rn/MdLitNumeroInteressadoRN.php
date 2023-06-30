<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/07/2018 - criado por ellyson.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitNumeroInteressadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdLitDadoInteressado(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitNumeroInteressadoDTO->getNumIdMdLitDadoInteressado())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrNumero(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdLitNumeroInteressadoDTO->getStrNumero())){
      $objMdLitNumeroInteressadoDTO->setStrNumero(null);
    }else{
      $objMdLitNumeroInteressadoDTO->setStrNumero(trim($objMdLitNumeroInteressadoDTO->getStrNumero()));

      if (strlen($objMdLitNumeroInteressadoDTO->getStrNumero())>999){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 999 caracteres.');
      }
    }
  }

    /**
     * @param $arrPostNumeroInteressados[0] Número de complemento do interessado
     * @param $arrPostNumeroInteressados[1] Serviço
     * @param $arrPostNumeroInteressados[2] Modalidade
     * @param $arrPostNumeroInteressados[3] Abrangências
     * @param $arrPostNumeroInteressados[4] Estados
     * @param $arrPostNumeroInteressados[5] Cidades
     * @param $arrPostNumeroInteressados[6] IdServiços
     * @param $arrPostNumeroInteressados[7] IdModalidades
     * @param $arrPostNumeroInteressados[8] IdAbrangencias
     * @param $arrPostNumeroInteressados[9] IdEstados
     * @param $arrPostNumeroInteressados[10] IdCidades
     * @param $arrPostNumeroInteressados[11] IdContato
     * @param $arrPostNumeroInteressados[12] sin_outorga
     * @param $arrPostNumeroInteressados[13] id_md_lit_dado_complementar
     * @param $arrPostNumeroInteressados[id_procedimento] id_procedimento
     * @param $arrPostNumeroInteressados[id_md_lit_controle] id_md_lit_controle
     *
     */
    protected function cadastrarControlado($arrPostNumeroInteressados) {
        try{

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_numero_interessado_cadastrar', __METHOD__, $arrPostNumeroInteressados);

            $idProcedimento = $arrPostNumeroInteressados['id_procedimento'];
            $idMdLitControle = $arrPostNumeroInteressados['id_md_lit_controle'];
            $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
            unset($arrPostNumeroInteressados['id_procedimento']);
            unset($arrPostNumeroInteressados['id_md_lit_controle']);

            $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());

            //deletando os dados interessados que não vieram por post
            $arrIdContato = array();
            $arrIdMdLitNumeroInteressado = array();

            foreach ($arrPostNumeroInteressados as $dadosComplementares){
                $arrIdContato[]   = $dadosComplementares[11];
                if($dadosComplementares[13] != 'null')
                    $arrIdMdLitNumeroInteressado[] = $dadosComplementares[13];
            }


            $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();

            if(count($arrIdMdLitNumeroInteressado)) {
                $objMdLitNumeroInteressadoDTO->setNumIdMdLitNumeroInteressado($arrIdMdLitNumeroInteressado, InfraDTO::$OPER_NOT_IN);
            }
            $objMdLitNumeroInteressadoDTO->setNumIdContatoMdLitDadoInteressado($arrIdContato, InfraDTO::$OPER_IN);
            $objMdLitNumeroInteressadoDTO->setNumIdMdLitControleMdLitDadoInteressado($idMdLitControle);
            $objMdLitNumeroInteressadoDTO->retTodos(false);

            $arrObjMdLitNumeroInteressadoDTO = $this->listar($objMdLitNumeroInteressadoDTO);

            if (count($arrObjMdLitNumeroInteressadoDTO))
                $this->excluir($arrObjMdLitNumeroInteressadoDTO);

            foreach ($arrPostNumeroInteressados as $dadosComplementares){

                //buscando o dado do interessado do numero filtrando pelo id do contato e do id_md_lit_controle aonde só pode haver 1 registro
                $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
                $objMdLitDadoInteressadoDTO->retTodos(false);
                $objMdLitDadoInteressadoDTO->setNumIdContato($dadosComplementares[11]);
                $objMdLitDadoInteressadoDTO->setNumIdMdLitControle($idMdLitControle);
                $objMdLitDadoInteressadoDTO->setNumMaxRegistrosRetorno(1);

                $objMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->consultar($objMdLitDadoInteressadoDTO);
                //adicionando o Sinalizador do outorgado.
                $objMdLitDadoInteressadoDTO->setStrSinOutorgado($dadosComplementares[12]);
                $objMdLitDadoInteressadoRN->alterar($objMdLitDadoInteressadoDTO);

                //populando o objeto
                $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
                $objMdLitNumeroInteressadoDTO->setStrNumero($dadosComplementares[0]);
                $objMdLitNumeroInteressadoDTO->setNumIdMdLitDadoInteressado($objMdLitDadoInteressadoDTO->getNumIdMdLitDadoInteressado());

                if($dadosComplementares[13] != 'null' && !empty($dadosComplementares[13])){
                    $objMdLitNumeroInteressadoDTO->setNumIdMdLitNumeroInteressado($dadosComplementares[13]);
                    $objMdLitNumeroInteressadoBD->alterar($objMdLitNumeroInteressadoDTO);

                    $this->excluirRel($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado(),new MdLitRelNumInterServicoDTO(), new MdLitRelNumInterServicoRN());
                    $this->excluirRel($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado(),new MdLitRelNumInterModaliDTO(), new MdLitRelNumInterModaliRN());
                    $this->excluirRel($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado(),new MdLitRelNumInterTpOutorDTO(), new MdLitRelNumInterTpOutorRN());

                }else{
                    $objMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoBD->cadastrar($objMdLitNumeroInteressadoDTO);
                }

                $arrIdServico = $dadosComplementares[6] != 'null' && !InfraString::isBolVazia($dadosComplementares[6]) ? explode(',€ ', $dadosComplementares[6]) : null;
                $arrIdModalidades = $dadosComplementares[7] != 'null' && !InfraString::isBolVazia($dadosComplementares[7]) ? explode(',€ ', $dadosComplementares[7]) : null;
                $arrIdAbrangencia = $dadosComplementares[8] != 'null' && !InfraString::isBolVazia($dadosComplementares[8]) ? explode(',€ ', $dadosComplementares[8]) : null;
                $arrIdEstados = $dadosComplementares[9] != 'null' && !InfraString::isBolVazia($dadosComplementares[9]) ? explode(',€ ', $dadosComplementares[9]) : null;
                $arrIdCidades = $dadosComplementares[10] != 'null' && !InfraString::isBolVazia($dadosComplementares[10]) ? explode(',€ ', $dadosComplementares[10]) : null;

                $this->cadastrarRel(new MdLitRelNumInterServicoRN(), new MdLitRelNumInterServicoDTO(), $arrIdServico, $objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado(), 'IdMdLitServico');
                $this->cadastrarRel(new MdLitRelNumInterModaliRN(), new MdLitRelNumInterModaliDTO(), $arrIdModalidades, $objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado(), 'IdMdLitModalidade');
                $this->cadastrarRel(new MdLitRelNumInterTpOutorRN(), new MdLitRelNumInterTpOutorDTO(), $arrIdAbrangencia, $objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado(), 'IdMdLitAdmTipoOutor');

            }

            return $objMdLitNumeroInteressadoDTO;
        }catch(Exception $e){
            throw new InfraException('Erro cadastrando dado complementar do interessado.',$e);
        }
    }

    public function gerarNumeroComplementar(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO){
        $numeroComplementar = null;
        $objMdLitIntegracaoRN   = new MdLitIntegracaoRN();

        $objMdLitIntegracaoDTO  = $objMdLitIntegracaoRN->retornarObjIntegracaoDTOPorFuncionalidade(MdLitIntegracaoRN::$GERAR_NUMERO_COMPLEMENTAR_ENTIDADE_NAO_OUTORGADA);

        if(!$objMdLitIntegracaoDTO){
            $objInfraException = new InfraException();
            $objInfraException->adicionarValidacao('O Número Complementar do Interessado é obrigatório para a inclusão do lançamento. Antes, é necessário que a Administração do SEI parametrize o Mapeamento da Integração correspondente à funcionalidade "Gerar Número do Interessado para Entidade não Outorgada".');
            $objInfraException->lancarValidacoes();
        }

        $objMdLitSoapClienteRN  = new MdLitSoapClienteRN($objMdLitIntegracaoDTO->getStrEnderecoWsdl(),'wsdl');
        $objMdLitSoapClienteRN->setSoapVersion($objMdLitIntegracaoDTO->getStrVersaoSoap());

        //monta os parametros de entrada do web-service
        $montarParametroEntrada = array();
        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamEntradaDTO() as $objMdLitMapearParamEntradaDTO){
            switch ($objMdLitMapearParamEntradaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamEntradaRN::$ID_PARAM_GERAR_NUMERO_NAO_OUTORGADA['SISTEMA_ORIGEM']:
                    $montarParametroEntrada[$objMdLitMapearParamEntradaDTO->getStrCampo()] = MdLitLancamentoRN::$SISTEMA_ORIGEM;
                    break;
            }

        }

        $arrResultado = $objMdLitSoapClienteRN->enviarDadosSigecLancamento($objMdLitIntegracaoDTO, $montarParametroEntrada, MdLitMapearParamEntradaRN::$PARAM_PRINCIPAL_FILTROS);


        foreach ($objMdLitIntegracaoDTO->getArrObjMdLitMapearParamSaidaDTO() as $objMdLitMapearParamSaidaDTO){
            switch ($objMdLitMapearParamSaidaDTO->getNumIdMdLitCampoIntegracao()){
                case MdLitMapearParamSaidaRN::$ID_PARAM_GERAR_NUMERO_NAO_OUTORGADA['NUMERO_INTERESSADO']:
                    $numeroComplementar = $arrResultado['return'][$objMdLitMapearParamSaidaDTO->getStrCampo()];
                    break;
            }
        }
        $objMdLitNumeroInteressadoDTO->setStrNumero($numeroComplementar);
        $this->alterar($objMdLitNumeroInteressadoDTO);
        return $objMdLitNumeroInteressadoDTO;

    }

    private function cadastrarRel(InfraRN $objRN, InfraDTO $objDTO, $arrId, $idMdLitNumeroInteressado, $strAtributo){
        if($arrId){
            foreach ($arrId as $id){
                $objDTO->setNumIdMdLitNumeroInteressado($idMdLitNumeroInteressado);
                $objDTO->set($strAtributo, $id);

                $objRN->cadastrar($objDTO);
            }
        }
    }

  protected function alterarControlado(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarPermissao('md_lit_numero_interessado_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdLitNumeroInteressadoDTO->isSetNumIdMdLitDadoInteressado()){
        $this->validarNumIdMdLitDadoInteressado($objMdLitNumeroInteressadoDTO, $objInfraException);
      }
      if ($objMdLitNumeroInteressadoDTO->isSetStrNumero()){
        $this->validarStrNumero($objMdLitNumeroInteressadoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
      $objMdLitNumeroInteressadoBD->alterar($objMdLitNumeroInteressadoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Número de interessado.',$e);
    }
  }


    protected function excluirControlado($arrObjMdLitNumeroInteressadoDTO){
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_numero_interessado_excluir', __METHOD__, $arrObjMdLitNumeroInteressadoDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();
            $this->validarExclusaoNumeroInteressado($arrObjMdLitNumeroInteressadoDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdLitNumeroInteressadoDTO);$i++){
                //servico
                $this->excluirRel($arrObjMdLitNumeroInteressadoDTO[$i]->getNumIdMdLitNumeroInteressado(),new MdLitRelNumInterServicoDTO(), new MdLitRelNumInterServicoRN());
                //modalidade
                $this->excluirRel($arrObjMdLitNumeroInteressadoDTO[$i]->getNumIdMdLitNumeroInteressado(),new MdLitRelNumInterModaliDTO(), new MdLitRelNumInterModaliRN());
                //tipo Outorga
                $this->excluirRel($arrObjMdLitNumeroInteressadoDTO[$i]->getNumIdMdLitNumeroInteressado(),new MdLitRelNumInterTpOutorDTO(), new MdLitRelNumInterTpOutorRN());

                $objMdLitNumeroInteressadoBD->excluir($arrObjMdLitNumeroInteressadoDTO[$i]);
            }

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro excluindo nu\mero do interessado.',$e);
        }
    }

    public function retornaObjNumeroInteressado($idMdLitNumeroInteressado)
    {
        $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
        $objMdLitNumeroInteressadoDTO->retTodos(true);
        $objMdLitNumeroInteressadoDTO->setNumIdMdLitNumeroInteressado($idMdLitNumeroInteressado);

        $objMdLitNumeroInteressadoDTO = $this->consultar($objMdLitNumeroInteressadoDTO);

        if (!$objMdLitNumeroInteressadoDTO) {
            throw new InfraException('O Número de Complemento do Interessado não foi selecionado.');
        }

        return $objMdLitNumeroInteressadoDTO;
    }

    private function excluirRel($idMdLitDadoInteressado, $objDTO, $objRN){
        $objDTO->retTodos(false);
        $objDTO->setNumIdMdLitNumeroInteressado($idMdLitDadoInteressado);

        $arrObjDTO = $objRN->listar($objDTO);
        $objRN->excluir($arrObjDTO);
    }

    /**
     * @param MdLitDadoInteressadoDTO $objMdLitDadoInteressadoDTO
     * @param InfraException $objInfraException,
     * o numero do interessado não pode ser repetido e se tiver vinculo com o lançamento lança uma exçeção
     */
    private function validarExclusaoNumeroInteressado($arrObjMdLitNumeroInteressadoDTO, InfraException $objInfraException){

        foreach ($arrObjMdLitNumeroInteressadoDTO as $objMdLitNumeroInteressadoDTO){
            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->retTodos();
            $objMdLitLancamentoDTO->setNumIdMdLitNumeroInteressado($objMdLitNumeroInteressadoDTO->getNumIdMdLitNumeroInteressado());

            $objMdLitLancamentoRN = new MdLitLancamentoRN();
            $objMdLitLancamentoDTOContar = $objMdLitLancamentoRN->contar($objMdLitLancamentoDTO);

            if($objMdLitLancamentoDTOContar){
                $objInfraException->adicionarValidacao('O número '.$objMdLitNumeroInteressadoDTO->getStrNumero().' está vinculado há uma multa.');
            }

        }
    }

  protected function consultarConectado(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_numero_interessado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitNumeroInteressadoBD->consultar($objMdLitNumeroInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Número de interessado.',$e);
    }
  }

  protected function listarConectado(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_numero_interessado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitNumeroInteressadoBD->listar($objMdLitNumeroInteressadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Número de interessado.',$e);
    }
  }

  protected function contarConectado(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_numero_interessado_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitNumeroInteressadoBD->contar($objMdLitNumeroInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Número de interessado.',$e);
    }
  }

  protected function contarLancamentoConectado(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO){
      $objMdLitNumeroInteressadoDTO->retNumIdMdLitNumeroInteressado();
        $arrObjMdLitNumeroInteressado = $this->listar($objMdLitNumeroInteressadoDTO);

        if(count($arrObjMdLitNumeroInteressado)){
            $arrIdMdLitNumeroInteressado =InfraArray::converterArrInfraDTO($arrObjMdLitNumeroInteressado, 'IdMdLitNumeroInteressado');

            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->retTodos(false);
            $objMdLitLancamentoDTO->setNumIdMdLitNumeroInteressado($arrIdMdLitNumeroInteressado, InfraDTO::$OPER_IN);

            $objMdLitLancamentoRN = new MdLitLancamentoRN();
            $contarLancamento = $objMdLitLancamentoRN->contar($objMdLitLancamentoDTO);

            return $contarLancamento;
        }

        return 0;
  }
/* 
  protected function desativarControlado($arrObjMdLitNumeroInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_numero_interessado_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitNumeroInteressadoDTO);$i++){
        $objMdLitNumeroInteressadoBD->desativar($arrObjMdLitNumeroInteressadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Número de interessado.',$e);
    }
  }

  protected function reativarControlado($arrObjMdLitNumeroInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_numero_interessado_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdLitNumeroInteressadoDTO);$i++){
        $objMdLitNumeroInteressadoBD->reativar($arrObjMdLitNumeroInteressadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Número de interessado.',$e);
    }
  }

  protected function bloquearControlado(MdLitNumeroInteressadoDTO $objMdLitNumeroInteressadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_lit_numero_interessado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdLitNumeroInteressadoBD = new MdLitNumeroInteressadoBD($this->getObjInfraIBanco());
      $ret = $objMdLitNumeroInteressadoBD->bloquear($objMdLitNumeroInteressadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Número de interessado.',$e);
    }
  }

 */
}
