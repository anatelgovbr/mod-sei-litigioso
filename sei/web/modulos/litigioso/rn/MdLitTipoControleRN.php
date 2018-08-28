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
                $arrMotivos       = $ret->getArrObjRelTipoControleLitigiosoMotivoDTO();
                $arrTipoProcessos = $ret->getArrObjRelTipoControleLitigiosoTipoProcedimentoDTO();
                $arrUnidades      = $ret->getArrObjRelTipoControleLitigiosoUnidadeDTO();
                $arrSobrestado    = $ret->getArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO();

                $objRelTipoControleLitigiosoUsuarioRN                    = new MdLitRelTipoControleUsuarioRN();
                $objRelTipoControleLitigiosoMotivoRN                     = new MdLitRelTpControlMotiRN();
                $objRelTipoControleLitigiosoTipoProcedimentoRN           = new MdLitRelTipoControleTipoProcedimentoRN();
                $objRelTipoControleLitigiosoUnidadeRN                    = new MdLitRelTipoControleUnidadeRN();
                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();

                //salvar os gestores
                foreach ($arrGestores as $objRelTipoControleLitigiosoUsuarioDTO) {
                    $objRelTipoControleLitigiosoUsuarioDTO->setNumIdTipoControleLitigioso($ret->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoUsuarioRN->cadastrar($objRelTipoControleLitigiosoUsuarioDTO);
                }

                //salvar os motivos
                foreach ($arrMotivos as $objRelTpControlMotiDTO){
                    $objRelTpControlMotiDTO->setNumIdMdLitTipoControle($ret->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoMotivoRN->cadastrar($objRelTpControlMotiDTO);
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
                $arrMotivos       = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoMotivoDTO();
                $arrTipoProcessos = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoTipoProcedimentoDTO();
                $arrUnidades      = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoUnidadeDTO();
                $arrSobrestado    = $objTipoControleLitigiosoDTO->getArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO();

                $objRelTipoControleLitigiosoUsuarioRN                    = new MdLitRelTipoControleUsuarioRN();
                $objRelTipoControleLitigiosoMotivoRN                     = new MdLitRelTpControlMotiRN();
                $objRelTipoControleLitigiosoTipoProcedimentoRN           = new MdLitRelTipoControleTipoProcedimentoRN();
                $objRelTipoControleLitigiosoUnidadeRN                    = new MdLitRelTipoControleUnidadeRN();
                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();

                //salvar os gestores
                foreach ($arrGestores as $objRelTipoControleLitigiosoUsuarioDTO) {
                    $objRelTipoControleLitigiosoUsuarioDTO->setNumIdTipoControleLitigioso($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoUsuarioRN->cadastrar($objRelTipoControleLitigiosoUsuarioDTO);
                }


                // salvar os motivos
                foreach ($arrMotivos as $objRelTipoControleLitigiosoMotivoDTO){
                    $objRelTipoControleLitigiosoMotivoDTO->setNumIdMdLitTipoControle($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());
                    $objRelTipoControleLitigiosoMotivoRN->cadastrar($objRelTipoControleLitigiosoMotivoDTO);
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

                //apagando Motivos
                $objMdLitRelTipoControleMotivoDTO = new MdLitRelTpControlMotiDTO();
                $objMdLitRelTipoControleMotivoDTO->retTodos();
                $objMdLitRelTipoControleMotivoDTO->setNumIdMdLitTipoControle($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso());

                $objMdLitRelTipoControleMotivoRN = new MdLitRelTpControlMotiRN();
                $arrObjMdLitRelTipoControleMotivoDTO = $objMdLitRelTipoControleMotivoRN->listar($objMdLitRelTipoControleMotivoDTO);
                $objMdLitRelTipoControleMotivoRN->excluir($arrObjMdLitRelTipoControleMotivoDTO);


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
                $objMdLitTipoControleDTO->retStrSinParamModalComplInteressado();

                return $this->consultar($objMdLitTipoControleDTO);

            }catch (Exception $e){
                throw new InfraException('Erro ao buscar o objeto do Tipo de Controle.', $e);
            }
        }



        protected function cadastrarExemploControlado(){
            //não retirar o validarauditar, dar erro no script de instalação do modulo sem a referencia do banco
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_cadastrar',__METHOD__);

            $objTipoControleLitigiosoBD = new MdLitTipoControleBD($this->getObjInfraIBanco());
            $objMdLitFaseBD             = new MdLitFaseBD($this->getObjInfraIBanco());
            $objMdLitSituacaoBD         = new MdLitSituacaoBD($this->getObjInfraIBanco());

            //verificando se já existe algum tipo de controle cadastrado
            $objMdLitTipoControleDTO = new MdLitTipoControleDTO();
            $objMdLitTipoControleDTO->retStrSigla();
            $arrObjMdLitTipoControleDTO = $objTipoControleLitigiosoBD->listar($objMdLitTipoControleDTO);
            //se existe para a execução do metodo
            if(count($arrObjMdLitTipoControleDTO) > 0)
                return;

            //cadastrando tipo de controle litigioso exemplo
            $objMdLitTipoControleDTO = new MdLitTipoControleDTO();
            $objMdLitTipoControleDTO->setStrSigla('CONTROLE DE EXEMPLO');
            $objMdLitTipoControleDTO->setStrDescricao('Controle de Exemplo');
            $objMdLitTipoControleDTO->setStrSinAtivo('S');
            $objMdLitTipoControleDTO->setDtaDtaCorte(InfraData::getStrDataAtual());

            $objMdLitTipoControleDTO = $objTipoControleLitigiosoBD->cadastrar($objMdLitTipoControleDTO);

            //cadastrando fase instauração
            $objMdLitFaseInstauracaoDTO = new MdLitFaseDTO();
            $objMdLitFaseInstauracaoDTO->setStrNome('Instauração');
            $objMdLitFaseInstauracaoDTO->setStrDescricao('Agrupa situações afetas às formalidades da instauração do Procedimento');
            $objMdLitFaseInstauracaoDTO->setStrSinAtivo('S');
            $objMdLitFaseInstauracaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());

            //cadastrando fase instrução
            $objMdLitFaseInstrucaoDTO = new MdLitFaseDTO();
            $objMdLitFaseInstrucaoDTO->setStrNome('Instrução');
            $objMdLitFaseInstrucaoDTO->setStrDescricao('Agrupa situações afetas às formalidades da instrução do Procedimento');
            $objMdLitFaseInstrucaoDTO->setStrSinAtivo('S');
            $objMdLitFaseInstrucaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());

            //cadastrando fase Recursal
            $objMdLitFaseRecursalDTO = new MdLitFaseDTO();
            $objMdLitFaseRecursalDTO->setStrNome('Recursal');
            $objMdLitFaseRecursalDTO->setStrDescricao('Agrupa situações afetas às formalidades da fase recursal do Procedimento');
            $objMdLitFaseRecursalDTO->setStrSinAtivo('S');
            $objMdLitFaseRecursalDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());

            //cadastrando fase Conclusiva
            $objMdLitFaseConclusivaDTO = new MdLitFaseDTO();
            $objMdLitFaseConclusivaDTO->setStrNome('Conclusiva');
            $objMdLitFaseConclusivaDTO->setStrDescricao('Agrupa situações afetas às formalidades da conclusão definitiva do Procedimento');
            $objMdLitFaseConclusivaDTO->setStrSinAtivo('S');
            $objMdLitFaseConclusivaDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());

            $objMdLitFaseInstauracaoDTO     = $objMdLitFaseBD->cadastrar($objMdLitFaseInstauracaoDTO);
            $objMdLitFaseInstrucaoDTO       = $objMdLitFaseBD->cadastrar($objMdLitFaseInstrucaoDTO);
            $objMdLitFaseRecursalDTO        = $objMdLitFaseBD->cadastrar($objMdLitFaseRecursalDTO);
            $objMdLitFaseConclusivaDTO      = $objMdLitFaseBD->cadastrar($objMdLitFaseConclusivaDTO);

            //cadastrando situação
            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstauracaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Formalização do Documento de Instauração');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('S');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(1);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstauracaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Expedição do Ofício de Instauração');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(2);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstauracaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Cumprimento da Intimação de Instauração');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(3);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('S');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Apresentação da Defesa');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(15);
            $objMdLitSituacaoDTO->setNumOrdem(4);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('S');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('S');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Diligência Requerida');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(5);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Diligência Cumprida');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(6);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Expedição de Ofício para Alegações Finais');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(7);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Cumprimento da Intimação de Alegações Finais');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(8);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('S');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Apresentação das Alegações Finais');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(9);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Consulta à Procuradoria');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(10);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Retorno da Consulta à Procuradoria');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(11);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Informe de Instrução');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(12);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Formalização da Decisão');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(13);
            $objMdLitSituacaoDTO->setStrSinDecisoria('S');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Expedição do Ofício da Decisão');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(14);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Cumprimento da Intimação da Decisão');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(15);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('S');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Apresentação de Renúncia ao Direito de Recorrer');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(16);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Expedição do Ofício sobre a Renúncia ao Direito de Recorrer');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(17);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseInstrucaoDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Cumprimento da Intimação sobre a Renúncia ao Direito de Recorrer');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(18);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('S');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Apresentação de Recurso');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(19);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('S');
            $objMdLitSituacaoDTO->setStrSinOpcional('S');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Diligência Requerida');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(20);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Diligência Cumprida');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(21);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Decisão de Admissibilidade de Recurso');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(22);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Decisão de Inadmissibilidade de Recurso');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(23);
            $objMdLitSituacaoDTO->setStrSinDecisoria('S');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Expedição do Ofício da Inadmissibilidade de Recurso');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(24);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Cumprimento da Intimação da Inadmissibilidade de Recurso');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(25);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('S');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Decisão de Admissibilidade de Recurso com Retratação');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(26);
            $objMdLitSituacaoDTO->setStrSinDecisoria('S');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Expedição do Ofício da Admissibilidade de Recurso com Retratação');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(27);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Cumprimento da Intimação da Admissibilidade de Recurso com Retratação');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(28);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('S');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Consulta à Procuradoria');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(29);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Retorno da Consulta à Procuradoria');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(30);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Informe Recursal');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(31);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Formalização da Decisão Recursal');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(32);
            $objMdLitSituacaoDTO->setStrSinDecisoria('S');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Expedição do Ofício da Decisão Recursal');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(33);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Cumprimento da Intimação da Decisão Recursal');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('N');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(34);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('S');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->setNumIdFaseLitigioso($objMdLitFaseRecursalDTO->getNumIdFaseLitigioso());
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($objMdLitTipoControleDTO->getNumIdTipoControleLitigioso());
            $objMdLitSituacaoDTO->setStrNome('Formalização do Trânsito em Julgado Administrativo');
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setStrSinInstauracao('N');
            $objMdLitSituacaoDTO->setStrSinConclusiva('S');
            $objMdLitSituacaoDTO->setNumPrazo(null);
            $objMdLitSituacaoDTO->setNumOrdem(35);
            $objMdLitSituacaoDTO->setStrSinDecisoria('N');
            $objMdLitSituacaoDTO->setStrSinIntimacao('N');
            $objMdLitSituacaoDTO->setStrSinDefesa('N');
            $objMdLitSituacaoDTO->setStrSinRecursal('N');
            $objMdLitSituacaoDTO->setStrSinOpcional('N');
            $objMdLitSituacaoBD->cadastrar($objMdLitSituacaoDTO);
        }

        protected function consultarTipoControlePorProcedimentoConectado($objProcedimentoAPI){
            $idUnidade            = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
            //MdLitRelTipoControleTipoProcedimentoDTO
            $ObjRelTipoControleLitigiosoTipoProcedimentoRN  = new MdLitRelTipoControleTipoProcedimentoRN();
            $ObjRelTipoControleLitigiosoTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
            $ObjRelTipoControleLitigiosoTipoProcedimentoDTO->retTodos();
            $ObjRelTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoAPI->getTipoProcedimento()->getIdTipoProcedimento());
            $ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO = $ObjRelTipoControleLitigiosoTipoProcedimentoRN->listar($ObjRelTipoControleLitigiosoTipoProcedimentoDTO);

            if(count($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO) > 0){
                $arrIdTipoControleLitigioso = InfraArray::converterArrInfraDTO($ArrObjRelTipoControleLitigiosoTipoProcedimentoDTO, 'IdTipoControleLitigioso');

                //MdLitRelTipoControleUnidadeDTO
                $ObjRelTipoControleLitigiosoUnidadeRN  = new MdLitRelTipoControleUnidadeRN();
                $ObjRelTipoControleLitigiosoUnidadeDTO = new MdLitRelTipoControleUnidadeDTO();
                $ObjRelTipoControleLitigiosoUnidadeDTO->retTodos();
                $ObjRelTipoControleLitigiosoUnidadeDTO->setNumIdUnidade($idUnidade);
                $ObjRelTipoControleLitigiosoUnidadeDTO->setNumIdTipoControleLitigioso($arrIdTipoControleLitigioso, InfraDTO::$OPER_IN);
                $ArrObjRelTipoControleLitigiosoUnidadeDTO = $ObjRelTipoControleLitigiosoUnidadeRN->listar($ObjRelTipoControleLitigiosoUnidadeDTO);

                if(count($ArrObjRelTipoControleLitigiosoUnidadeDTO) == 0)
                    return null;

                $arrIdTipoControleLitigioso = InfraArray::converterArrInfraDTO($ArrObjRelTipoControleLitigiosoUnidadeDTO, 'IdTipoControleLitigioso');

                $objMdLitTipoControleDTO = new MdLitTipoControleDTO();
                $objMdLitTipoControleDTO->retTodos();
                $objMdLitTipoControleDTO->setNumIdTipoControleLitigioso($arrIdTipoControleLitigioso, InfraDTO::$OPER_IN);
                $objMdLitTipoControleDTO->setNumMaxRegistrosRetorno(1);
                $objMdLitTipoControleDTO = $this->consultar($objMdLitTipoControleDTO);

                return $objMdLitTipoControleDTO;
            }

        }

        public function montarIconeControleProcessoSinalizarPendencia($arrObjProcedimentoAPI,$arrIcone){
            $imgIcone             = "modulos/litigioso/imagens/Balanca_vermelha.png";

            foreach ($arrObjProcedimentoAPI as $objProcedimentoAPI) {
                $titulo               = 'Controle Litigioso: ';

                //pula todos os processo que já possui icones pois ele não estarão pendentes
                if(count($arrIcone[$objProcedimentoAPI->getIdProcedimento()]) > 0){
                    continue;
                }

                $objSeiRN = new SeiRN();
                $objEntradaConsultarProcedimentoAPI = new EntradaConsultarProcedimentoAPI();
                $objEntradaConsultarProcedimentoAPI->setIdProcedimento( $objProcedimentoAPI->getIdProcedimento());

                SessaoSEI::getInstance()->setBolHabilitada(false);
                $objSaidaConsultarProcedimentoAPI = $objSeiRN->consultarProcedimento( $objEntradaConsultarProcedimentoAPI );
                SessaoSEI::getInstance()->setBolHabilitada(true);
                $objMdLitTipoControleDTO = $this->consultarTipoControlePorProcedimento($objSaidaConsultarProcedimentoAPI);

                if(!$objMdLitTipoControleDTO)
                    continue;

                $diferencaEntreDias = MdLitProcessoSituacaoINT::diferencaEntreDias($objMdLitTipoControleDTO->getDtaDtaCorte(),$objSaidaConsultarProcedimentoAPI->getDataAutuacao());

                if($diferencaEntreDias > 0){

                    $diferencaEntreDiasTexto = MdLitProcessoSituacaoINT::diferencaEntreDias($objSaidaConsultarProcedimentoAPI->getDataAutuacao());
                    if($diferencaEntreDiasTexto > 1){
                        $diferencaEntreDiasTexto = ' (' . $diferencaEntreDiasTexto . ' dias)';
                    }elseif($diferencaEntreDiasTexto == 1){
                        $diferencaEntreDiasTexto = ' (' . $diferencaEntreDiasTexto . ' dia)';
                    }else{
                        $diferencaEntreDiasTexto = '';
                    }

                    $titulo               .= $objMdLitTipoControleDTO->getStrSigla().'\n';
                    $linhaDeBaixo         = 'Cadastro Pendente no Controle Litigioso desde o dia '. $objSaidaConsultarProcedimentoAPI->getDataAutuacao().'.\n '.$diferencaEntreDiasTexto;
                    $img = "<a href='javascript:void(0);' ".PaginaSEI::montarTitleTooltip($linhaDeBaixo,$titulo)." ><img src='".$imgIcone."' class='imagemStatus' /></a>";
                    $arrIcone[$objProcedimentoAPI->getIdProcedimento()][] = $img;
                }
            }

            return $arrIcone;

        }

        public function montarIconeProcessoSinalizarPendencia(ProcedimentoAPI $objProcedimentoAPI, $arrObjArvoreAcaoItemAPI){
            $imgIcone             = "modulos/litigioso/imagens/Balanca_vermelha.png";
            $titulo               = 'Controle Litigioso: ';

            $objSeiRN = new SeiRN();
            $objEntradaConsultarProcedimentoAPI = new EntradaConsultarProcedimentoAPI();
            $objEntradaConsultarProcedimentoAPI->setIdProcedimento( $objProcedimentoAPI->getIdProcedimento());

            $objSaidaConsultarProcedimentoAPI = $objSeiRN->consultarProcedimento( $objEntradaConsultarProcedimentoAPI );
            $objMdLitTipoControleDTO = $this->consultarTipoControlePorProcedimento($objSaidaConsultarProcedimentoAPI);

            if(!$objMdLitTipoControleDTO)
                return $arrObjArvoreAcaoItemAPI;

            $diferencaEntreDias = MdLitProcessoSituacaoINT::diferencaEntreDias($objMdLitTipoControleDTO->getDtaDtaCorte(),$objSaidaConsultarProcedimentoAPI->getDataAutuacao());

            if($diferencaEntreDias > 0){

                $diferencaEntreDiasTexto = MdLitProcessoSituacaoINT::diferencaEntreDias($objSaidaConsultarProcedimentoAPI->getDataAutuacao());
                if($diferencaEntreDiasTexto > 1){
                    $diferencaEntreDiasTexto = ' (' . $diferencaEntreDiasTexto . ' dias)';
                }elseif($diferencaEntreDiasTexto == 1){
                    $diferencaEntreDiasTexto = ' (' . $diferencaEntreDiasTexto . ' dia)';
                }else{
                    $diferencaEntreDiasTexto = '';
                }
                $titulo .= $objMdLitTipoControleDTO->getStrSigla().'\n';
                $titulo         .= 'Cadastro Pendente no Controle Litigioso desde o dia '. $objSaidaConsultarProcedimentoAPI->getDataAutuacao().'. '.$diferencaEntreDiasTexto;
                $objArvoreAcaoItemAPI = new ArvoreAcaoItemAPI();
                $objArvoreAcaoItemAPI->setTipo('MD_LIT_PROCESSO');
                $objArvoreAcaoItemAPI->setId('MD_LIT_PROC_' . $objProcedimentoAPI->getIdProcedimento());
                $objArvoreAcaoItemAPI->setIdPai($objProcedimentoAPI->getIdProcedimento());
                $objArvoreAcaoItemAPI->setTitle($titulo);
                $objArvoreAcaoItemAPI->setIcone($imgIcone);

                $objArvoreAcaoItemAPI->setTarget(null);
                $objArvoreAcaoItemAPI->setHref('#');

                $objArvoreAcaoItemAPI->setSinHabilitado('S');

                $arrObjArvoreAcaoItemAPI[] = $objArvoreAcaoItemAPI;
            }
            return $arrObjArvoreAcaoItemAPI;

        }

    }
