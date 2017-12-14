<?
    /**
     * ANATEL
     *
     * 19/01/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';
    require_once 'MdLitRelTipoControleUsuarioRN.php';
    require_once 'MdLitSituacaoRN.php';

    class MdLitTipoControleRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        //validar para nao permitir o mesmo tipo de processo a mais de um tipo de controle litigioso

        protected function cadastrarControlado(MdLitTipoControleDTO $objTipoControleLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_cadastrar', __METHOD__, $objTipoControleLitigiosoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                $this->validarStrSigla($objTipoControleLitigiosoDTO, $objInfraException);
                $this->validarStrDescricao($objTipoControleLitigiosoDTO, $objInfraException);

                $arrTipoProcessoValidacao = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoTipoProcedimentoDTO();
                $this->validarTipoProcesso($objTipoControleLitigiosoDTO, $objInfraException, $arrTipoProcessoValidacao);
                $objInfraException->lancarValidacoes();

                //insere o TipoControleLitigioso, e depois cria os relacionamentos com GESTORES, UNIDADES E TIPOS DE PROCESSOS
                $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                $objTipoControleLitigiosoDTO->setStrSinAtivo('S');

                //so pode executar se for administrador
                //só pode executar se for administrador - checagem feita via SIP
                $ret = $objTipoControleLitigiosoBD->cadastrar($objTipoControleLitigiosoDTO);

                //criando os relacionamentos
                $arrGestores      = $ret->getArrObjRelTipoControleLitigiosoUsuarioDTO();
                $arrTipoProcessos = $ret->getArrObjRelTipoControleLitigiosoTipoProcedimentoDTO();
                $arrUnidades      = $ret->getArrObjRelTipoControleLitigiosoUnidadeDTO();
                $arrSobrestado    = $ret->getArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO();

                $objRelTipoControleLitigiosoUsuarioRN                    = new MdLitRelTipoControleUsuarioRN();
                $objRelTipoControleLitigiosoTipoProcedimentoRN           = new MdLitRelTipoControleTipoProcedimentoRN();
                $objRelTipoControleLitigiosoUnidadeRN                    = new MdLitRelTipoControleUnidadeRN();
                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();

                //salvar os gestores
                foreach ($arrGestores as $objRelTipoControleLitigiosoUsuarioDTO) {
                    $objRelTipoControleLitigiosoUsuarioDTO->setNumIdTipoControleLitigioso($ret->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoUsuarioRN->cadastrar($objRelTipoControleLitigiosoUsuarioDTO);
                }

                //salvar os tipos de processos
                foreach ($arrTipoProcessos as $objRelTipoControleLitigiosoTipoProcedimentoDTO) {
                    $objRelTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoControleLitigioso($ret->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoTipoProcedimentoRN->cadastrar($objRelTipoControleLitigiosoTipoProcedimentoDTO);
                }

                //salvar as unidades associadas
                foreach ($arrUnidades as $objRelTipoControleLitigiosoUnidadeDTO) {
                    $objRelTipoControleLitigiosoUnidadeDTO->setNumIdTipoControleLitigioso($ret->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoUnidadeRN->cadastrar($objRelTipoControleLitigiosoUnidadeDTO);
                }

                //salvar os tipos de processos sobrestados
                foreach ($arrSobrestado as $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO) {
                    $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoControleLitigioso($ret->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN->cadastrar($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);
                }

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Tipo de Processo Litigioso.', $e);
            }
        }

        private function validarStrSigla(MdLitTipoControleDTO $objTipoControleLitigiosoDTO, InfraException $objInfraException)
        {

            if (InfraString::isBolVazia($objTipoControleLitigiosoDTO->getStrSigla())) {
                $objInfraException->adicionarValidacao('Sigla não informada.');
            } else {

                $objTipoControleLitigiosoDTO->setStrSigla(trim($objTipoControleLitigiosoDTO->getStrSigla()));

                if (strlen($objTipoControleLitigiosoDTO->getStrSigla()) > 50) {
                    $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 50 caracteres.');
                } else {
                    //VALIDACAO DE DUPLICIDADE DA SIGLA
                    $objTipoControleLitigiosoDTO2 = new MdLitTipoControleDTO();
                    $objTipoControleLitigiosoDTO2->setStrSigla($objTipoControleLitigiosoDTO->getStrSigla());

                    //Valida Permissao
                    SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_consultar', __METHOD__, $objTipoControleLitigiosoDTO2);

                    $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());


                    //VALIDACAO A SER EXECUTADA NA INSERÇAO DE NOVOS REGISTROS
                    if (!is_numeric($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso())) {

                        $ret = $objTipoControleLitigiosoBD->contar($objTipoControleLitigiosoDTO2);

                        if ($ret > 0) {
                            $objInfraException->adicionarValidacao('Sigla duplicada. Não é possível ter tipos de processos litigiosos com a mesma Sigla.');
                        }

                    } //VALIDACAO A SER EXECUTADA QUANDO É FEITO UPDATE DE REGISTROS
                    else {

                        $objTipoControleLitigiosoFiltrDTO = new MdLitTipoControleDTO();
                        $objTipoControleLitigiosoFiltrDTO->retTodos();
                        $objTipoControleLitigiosoFiltrDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso(), InfraDTO::$OPER_DIFERENTE);
                        $objTipoControleLitigiosoFiltrDTO->setStrSigla($objTipoControleLitigiosoDTO->getStrSigla(), InfraDTO::$OPER_IGUAL);

                        $ret = $objTipoControleLitigiosoBD->consultar($objTipoControleLitigiosoFiltrDTO);

                        if (count($ret) > 0) {
                            $objInfraException->adicionarValidacao('Sigla duplicada. Não é possível ter tipos de processos litigiosos com a mesma Sigla.');
                        }

                    }
                }

            }
        }

        private function validarStrDescricao(MdLitTipoControleDTO $objTipoControleLitigiosoDTO, InfraException $objInfraException)
        {

            if (InfraString::isBolVazia($objTipoControleLitigiosoDTO->getStrDescricao())) {
                $objInfraException->adicionarValidacao('Descrição não informada.');
            } else {

                $objTipoControleLitigiosoDTO->setStrDescricao(trim($objTipoControleLitigiosoDTO->getStrDescricao()));

                if (strlen($objTipoControleLitigiosoDTO->getStrDescricao()) > 250) {
                    $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
                }

            }
        }

        private function validarTipoProcesso(MdLitTipoControleDTO $objTipoControleLitigiosoDTO, InfraException $objInfraException, $arrTipoProcessos)
        {

            $totalVinculos           = 0;
            $arrayIdTipoProcedimento = array();
            $relTipoProcessoDTO      = new MdLitRelTipoControleTipoProcedimentoDTO();
            $relTipoProcessoDTO->retTodos();
            $relTipoProcessoRN = new MdLitRelTipoControleTipoProcedimentoRN();

            if (count($arrTipoProcessos) > 0) {

                foreach ($arrTipoProcessos as $itemTipoProcesso) {
                    array_push($arrayIdTipoProcedimento, $itemTipoProcesso->getNumIdTipoProcedimento());
                }

                $relTipoProcessoDTO->setNumIdTipoProcedimento($arrayIdTipoProcedimento, InfraDTO::$OPER_IN);

                //checar se é inserçao de novo tipo de controle ou se é atualização
                if ($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso() > 0) {

                    //UPDATE
                    $relTipoProcessoDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso(), InfraDTO::$OPER_DIFERENTE);

                }

                $arrItens = $relTipoProcessoRN->listar($relTipoProcessoDTO);

                $totalVinculos = count($arrItens);
            }

            if ($totalVinculos > 0) {
                $objInfraException->adicionarValidacao('Não é possível vincular um tipo de processo a mais de um tipo de controle litigioso.');
            }
        }

        protected function alterarControlado(MdLitTipoControleDTO $objTipoControleLitigiosoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_alterar', __METHOD__, $objTipoControleLitigiosoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                if ($objTipoControleLitigiosoDTO->isSetStrSigla()) {
                    $this->validarStrSigla($objTipoControleLitigiosoDTO, $objInfraException);
                }

                $arrTipoProcessoValidacao = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoTipoProcedimentoDTO();
                $this->validarTipoProcesso($objTipoControleLitigiosoDTO, $objInfraException, $arrTipoProcessoValidacao);
                $objInfraException->lancarValidacoes();

                $this->removerRelacionamentosControlado($objTipoControleLitigiosoDTO);

                //so pode executar se for administrador - verificaçao feita via SIP
                $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                $objTipoControleLitigiosoBD->alterar($objTipoControleLitigiosoDTO);

                //============ inserir todos os relacionamentos =================
                //criando os relacionamentos
                $arrGestores      = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoUsuarioDTO();
                $arrTipoProcessos = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoTipoProcedimentoDTO();
                $arrUnidades      = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoUnidadeDTO();
                $arrSobrestado    = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO();

                $objRelTipoControleLitigiosoUsuarioRN                    = new MdLitRelTipoControleUsuarioRN();
                $objRelTipoControleLitigiosoTipoProcedimentoRN           = new MdLitRelTipoControleTipoProcedimentoRN();
                $objRelTipoControleLitigiosoUnidadeRN                    = new MdLitRelTipoControleUnidadeRN();
                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();

                //salvar os gestores
                foreach ($arrGestores as $objRelTipoControleLitigiosoUsuarioDTO) {
                    $objRelTipoControleLitigiosoUsuarioDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoUsuarioRN->cadastrar($objRelTipoControleLitigiosoUsuarioDTO);
                }

                //salvar os tipos de processos
                foreach ($arrTipoProcessos as $objRelTipoControleLitigiosoTipoProcedimentoDTO) {
                    $objRelTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoTipoProcedimentoRN->cadastrar($objRelTipoControleLitigiosoTipoProcedimentoDTO);
                }

                //salvar as unidades associadas
                foreach ($arrUnidades as $objRelTipoControleLitigiosoUnidadeDTO) {
                    $objRelTipoControleLitigiosoUnidadeDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoUnidadeRN->cadastrar($objRelTipoControleLitigiosoUnidadeDTO);
                }

                //salvar os tipos de processos sobrestados
                foreach ($arrSobrestado as $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO) {
                    $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN->cadastrar($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);
                }

            } catch (Exception $e) {
                throw new InfraException('Erro alterando Tipo de Processo Litigioso.', $e);
            }

        }

        protected function removerRelacionamentosControlado(MdLitTipoControleDTO $objTipoControleLitigiosoDTO)
        {

            try {
                //apagando Tipos de Processos
                $objMdLitRelTipoControleTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
                $objMdLitRelTipoControleTipoProcedimentoDTO->retTodos();
                $objMdLitRelTipoControleTipoProcedimentoDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());

                $objMdLitRelTipoControleTipoProcedimentoRN = new MdLitRelTipoControleTipoProcedimentoRN();
                $arrObjMdLitRelTipoControleTipoProcedimentoDTO = $objMdLitRelTipoControleTipoProcedimentoRN->listar($objMdLitRelTipoControleTipoProcedimentoDTO);
                $objMdLitRelTipoControleTipoProcedimentoRN->excluir($arrObjMdLitRelTipoControleTipoProcedimentoDTO);

                //apagando Unidades
                $objMdLitRelTipoControleUnidadeDTO = new MdLitRelTipoControleUnidadeDTO();
                $objMdLitRelTipoControleUnidadeDTO->retTodos();
                $objMdLitRelTipoControleUnidadeDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());

                $objMdLitRelTipoControleUnidadeRN = new MdLitRelTipoControleUnidadeRN();
                $arrObjMdLitRelTipoControleUnidadeDTO = $objMdLitRelTipoControleUnidadeRN->listar($objMdLitRelTipoControleUnidadeDTO);
                $objMdLitRelTipoControleUnidadeRN->excluir($arrObjMdLitRelTipoControleUnidadeDTO);

                //apagando Gestores
                $objMdLitRelTipoControleUsuarioDTO = new MdLitRelTipoControleUsuarioDTO();
                $objMdLitRelTipoControleUsuarioDTO->retTodos();
                $objMdLitRelTipoControleUsuarioDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());

                $objMdLitRelTipoControleUsuarioRN = new MdLitRelTipoControleUsuarioRN();
                $arrObjMdLitRelTipoControleUsuarioDTO = $objMdLitRelTipoControleUsuarioRN->listar($objMdLitRelTipoControleUsuarioDTO);
                $objMdLitRelTipoControleUsuarioRN->excluir($arrObjMdLitRelTipoControleUsuarioDTO);

                //apagando Tipos de Processos que podem ser sobrestados
                $objMdLitRelTipoControleTipoProcedimentoSobrestadoDTO = new MdLitRelTipoControleTipoProcedimentoSobrestadoDTO();
                $objMdLitRelTipoControleTipoProcedimentoSobrestadoDTO->retTodos();
                $objMdLitRelTipoControleTipoProcedimentoSobrestadoDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());

                $objMdLitRelTipoControleTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();
                $arrObjMdLitRelTipoControleTipoProcedimentoSobrestadoDTO = $objMdLitRelTipoControleTipoProcedimentoSobrestadoRN->listar($objMdLitRelTipoControleTipoProcedimentoSobrestadoDTO);
                $objMdLitRelTipoControleTipoProcedimentoSobrestadoRN->excluir($arrObjMdLitRelTipoControleTipoProcedimentoSobrestadoDTO);

            } catch (Exception $e) {
                throw new InfraException('Erro listando Tipo de Processo Litigiosoes.', $e);
            }

        }

        protected function excluirControlado($arrObjTipoControleLitigiosoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_excluir', __METHOD__, $arrObjTipoControleLitigiosoDTO);

                //nao permite excluir tipo de controle se ele ja estiver vinculado a um dispositivo
                $listaIds = "";

                for ($i = 0; $i < count($arrObjTipoControleLitigiosoDTO); $i++) {

                    if ($listaIds == "") {
                        $listaIds = $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso();
                    } else {
                        $listaIds .= "," . $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso();
                    }
                }

                //nao permitir a exclusao de Conduta se a mesma já estiver vinculada a um dispositivo normativo
                $sql = " SELECT * FROM md_lit_rel_disp_norm_tipo_ctrl WHERE id_md_lit_tipo_controle IN ( " . $listaIds . ")";
                $rs  = $this->getObjInfraIBanco()->executarSql($sql);

                if ($rs != null && count($rs) > 0) {

                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao('A exclusão do tipo de controle não é permitida, pois já existem registros vinculados.');
                    $objInfraException->lancarValidacoes();

                }

                //Regras de Negocio
                //so pode executar se for administrador - verificaçao feita via SIP
                else if (count($arrObjTipoControleLitigiosoDTO) > 0) {

                    $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());

                    $rnFase                      = new MdLitFaseRN();
                    $rnSituacao                  = new MdLitSituacaoRN();
                    $rnTipoProcedimentoAssociado = new MdLitRelTipoControleTipoProcedimentoRN();
                    $rnUnidadeAssociado          = new MdLitRelTipoControleUnidadeRN();
                    $rnGestorAssociado           = new MdLitRelTipoControleUsuarioRN();
                    $rnSobrestadoAssociado       = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();

                    for ($i = 0; $i < count($arrObjTipoControleLitigiosoDTO); $i++) {

                        //excluir situacoes
                        $dtoSituacao = new MdLitSituacaoDTO();
                        $dtoSituacao->retTodos();
                        $dtoSituacao->setNumIdTipoControleLitigioso($arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());
                        $arrSituacoes = $rnSituacao->listarComTipoDeControle($dtoSituacao, $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());
                        $rnSituacao->excluirComTipoControle($arrSituacoes, $arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());

                        //excluir parametrização
                        $objMdLitParametrizarInteressadoRN = new MdLitParametrizarInteressadoRN();
                        $objMdLitParametrizarInteressadoRN->excluirPorTipoControle($arrObjTipoControleLitigiosoDTO[$i]);

                        //excluir fases
                        $dtoFases = new MdLitFaseDTO();
                        $dtoFases->retTodos();
                        $dtoFases->setNumIdTipoControleLitigioso($arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());
                        $arrFases = $rnFase->listar($dtoFases);
                        $rnFase->excluir($arrFases);

                        //excluir registros associados - flihos
                        $dto1 = new MdLitRelTipoControleTipoProcedimentoDTO();
                        $dto1->retTodos();
                        $dto1->setNumIdTipoControleLitigioso($arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());
                        $arr1 = $rnTipoProcedimentoAssociado->listar($dto1);

                        $rnTipoProcedimentoAssociado->excluir($arr1);

                        $dto2 = new MdLitRelTipoControleUnidadeDTO();
                        $dto2->retTodos();
                        $dto2->setNumIdTipoControleLitigioso($arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());
                        $arr2 = $rnUnidadeAssociado->listar($dto2);
                        $rnUnidadeAssociado->excluir($arr2);

                        $dto3 = new MdLitRelTipoControleUsuarioDTO();
                        $dto3->retTodos();
                        $dto3->setNumIdTipoControleLitigioso($arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());
                        $arr3 = $rnGestorAssociado->listar($dto3);
                        $rnGestorAssociado->excluir($arr3);

                        $dto4 = new MdLitRelTipoControleTipoProcedimentoSobrestadoDTO();
                        $dto4->retTodos();
                        $dto4->setNumIdTipoControleLitigioso($arrObjTipoControleLitigiosoDTO[$i]->getNumIdTipoControleLitigioso());
                        $arr4 = $rnSobrestadoAssociado->listar($dto4);
                        $rnSobrestadoAssociado->excluir($arr4);

                        //excluir registro pai - tipo de controle
                        $objTipoControleLitigiosoBD->excluir($arrObjTipoControleLitigiosoDTO[$i]);

                    }

                }

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo Tipo de Processo Litigioso.', $e);
            }

        }

        //REMOVE TODOS OS RELACIONAMENTOS DE UM TIPO DE CONTROLE
        //USADO NA EDIÇÃO DE TIPO DE CONTROLE, APAGA TUDO PARA DEPOIS REINSERIR

        protected function consultarConectado(MdLitTipoControleDTO $objTipoControleLitigiosoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_consultar', __METHOD__, $objTipoControleLitigiosoDTO);

                //Regras de Negocio
                $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                $ret                        = $objTipoControleLitigiosoBD->consultar($objTipoControleLitigiosoDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function listarConectado(MdLitTipoControleDTO $objTipoControleLitigiosoDTO)
        {

            try {

                //Valida Permissao do SIP
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar', __METHOD__, $objTipoControleLitigiosoDTO);

                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm       = $permissaoRN->isAdm();

                if ($isAdm) {

                    //consulta todos os tipos de processos litigiosos
                    $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                    $ret                        = $objTipoControleLitigiosoBD->listar($objTipoControleLitigiosoDTO);

                } else {

                    $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();

                    $objMdLitRelTipoControleUsuarioDTO = new MdLitRelTipoControleUsuarioDTO();
                    $objMdLitRelTipoControleUsuarioDTO->retTodos();
                    $objMdLitRelTipoControleUsuarioDTO->setNumIdUsuario($idUsuario);

                    $objMdLitRelTipoControleUsuarioRN = new MdLitRelTipoControleUsuarioRN();
                    $arrObjMdLitRelTipoControleUsuarioDTO = $objMdLitRelTipoControleUsuarioRN->listar($objMdLitRelTipoControleUsuarioDTO);

                    if(count($arrObjMdLitRelTipoControleUsuarioDTO) == 0)
                        return array();

                    $arrIdTipoControleLitigioso = InfraArray::converterArrInfraDTO($arrObjMdLitRelTipoControleUsuarioDTO, 'IdTipoControleLitigioso' );

                    $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($arrIdTipoControleLitigioso, InfraDTO::$OPER_IN);



                    $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                    $ret                        = $objTipoControleLitigiosoBD->listar($objTipoControleLitigiosoDTO);
                }

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro listando Tipo de Processo Litigiosoes.', $e);
            }
        }

        protected function contarConectado(MdLitTipoControleDTO $objTipoControleLitigiosoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar', __METHOD__, $objTipoControleLitigiosoDTO);

                //Regras de Negocio
                $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                $ret                        = $objTipoControleLitigiosoBD->contar($objTipoControleLitigiosoDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro contando Tipo de Processo Litigiosoes.', $e);
            }
        }

        protected function desativarControlado($arrObjTipoControleLitigiosoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_desativar');

                $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());

                for ($i = 0; $i < count($arrObjTipoControleLitigiosoDTO); $i++) {
                    $objTipoControleLitigiosoBD->desativar($arrObjTipoControleLitigiosoDTO[$i]);
                }

            } catch (Exception $e) {
                throw new InfraException('Erro desativando Tipo de Processo Litigioso.', $e);
            }

        }

        protected function reativarControlado($arrObjTipoControleLitigiosoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_reativar');

                //Regras de Negocio
                //so pode executar se for administrador - verificaçao feita via SIP
                $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjTipoControleLitigiosoDTO); $i++) {
                    $objTipoControleLitigiosoBD->reativar($arrObjTipoControleLitigiosoDTO[$i]);
                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro reativando Tipo de Processo Litigioso.', $e);
            }

        }

        protected function bloquearControlado(MdLitTipoControleDTO $objTipoControleLitigiosoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_consultar');

                //Regras de Negocio
                $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                $ret                        = $objTipoControleLitigiosoBD->bloquear($objTipoControleLitigiosoDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro bloqueando Tipo de Processo litigioso.', $e);
            }

        }

        protected function alterarSinParamModalComplInteressadoControlado(MdLitTipoControleDTO $objTipoControleLitigiosoDTO){
            try{
                $objMdLitTipoControleBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
                $objMdLitTipoControleBD->alterar($objTipoControleLitigiosoDTO);

            }catch (Exception $e){
                throw new InfraException('Erro ao alterar o sinalizador dos dados complementares do interessado.', $e);
            }
        }

        protected function getObjTipoControlePorIdConectado($idTipoControle){
            try{

                $objMdLitTipoControleDTO = new MdLitTipoControleDTO();
                $objMdLitTipoControleDTO->setNumIdTipoControleLitigioso($idTipoControle);
                $objMdLitTipoControleDTO->retStrSigla();

                return $this->consultar($objMdLitTipoControleDTO);

            }catch (Exception $e){
                throw new InfraException('Erro ao buscar o objeto do Tipo de Controle.', $e);
            }
        }

    }
