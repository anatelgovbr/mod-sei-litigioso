<?php
    /**
     * ANATEL
     *
     * 20/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitEspecieDecisaoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param MdLitEspecieDecisaoDTO $objEspecieLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function cadastrarControlado(MdLitEspecieDecisaoDTO $objEspecieLitigiosoDTO)
        {
            try {
                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_especie_decisao_cadastrar', __METHOD__, $objEspecieLitigiosoDTO);

                // Regras de Negocio
                $objInfraException = new InfraException();

                $this->_validarStrNomeEspecie($objEspecieLitigiosoDTO, $objInfraException);
//                $this->_validarResultadoDecisao($objEspecieLitigiosoDTO, $objInfraException);
                $this->_validarObrigacao($objEspecieLitigiosoDTO, $objInfraException);

                $objInfraException->lancarValidacoes();
                $objEspecieLitigiosoBD = new MdLitEspecieDecisaoBD($this->getObjInfraIBanco());
                $objEspecieLitigiosoDTO->setStrNome(trim($objEspecieLitigiosoDTO->getStrNome()));
                $objEspecieLitigiosoDTO->setStrSinAtivo('S');

                $objRetorno = $objEspecieLitigiosoBD->cadastrar($objEspecieLitigiosoDTO);

                $this->_salvarRelacionamentos($objRetorno);

                return $objRetorno;
            } catch (Exception $e) {
                throw new InfraException ('Erro cadastrando Esp�cie Litigiosa.', $e);
            }
        }

        /**
         * Validate field "Especie".
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @return void
         */
        private function _validarStrNomeEspecie(MdLitEspecieDecisaoDTO $objEspecieLitigiosoDTO, InfraException $objInfraException)
        {

            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (InfraString::isBolVazia($objEspecieLitigiosoDTO->getStrNome())) {
                $objInfraException->adicionarValidacao('Nome da Esp�cie n�o informado.');
            }

            $objEspecieLitigiosoDTO2 = new MdLitEspecieDecisaoDTO ();
            $objEspecieLitigiosoDTO2->setStrNome(trim($objEspecieLitigiosoDTO->getStrNome()), InfraDTO::$OPER_IGUAL);

            $objEspecieLitigiosoBD = new MdLitEspecieDecisaoBD ($this->getObjInfraIBanco());

            // Valida Quantidade de Caracteres
            if (strlen($objEspecieLitigiosoDTO->getStrNome()) > 50) {
                $objInfraException->adicionarValidacao('Esp�cie de Decis�o possui tamanho superior a 50 caracteres.');
            }

            is_numeric($objEspecieLitigiosoDTO->getNumIdEspecieLitigioso()) ? $objEspecieLitigiosoDTO2->setNumIdEspecieLitigioso($objEspecieLitigiosoDTO->getNumIdEspecieLitigioso(), InfraDTO::$OPER_DIFERENTE) : $objEspecieLitigiosoDTO->setNumIdEspecieLitigioso(null);

            $ret = $objEspecieLitigiosoBD->contar($objEspecieLitigiosoDTO2);

            if ($ret > 0) {
                $objInfraException->adicionarValidacao('J� existe Esp�cie de Decis�o cadastrada.');
            }
        }

        /**
         * Validate fields "Resultado de Decis�o".
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @return void
         */
        private function _validarResultadoDecisao($objEspecieLitigiosoDTO, $objInfraException)
        {
            $valido = false;

            if ($objEspecieLitigiosoDTO->getStrSinGestaoMulta() == 'S' || $objEspecieLitigiosoDTO->getStrSinIndicacaoPrazo() == 'S'
                || $objEspecieLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S'
            ) {
                $valido = true;
            }

            if (!$valido) {
                $objInfraException->adicionarValidacao('Resultado da Decis�o n�o informado.');
            }

        }

        private function _validarObrigacao($objEspecieLitigiosoDTO, $objInfraException)
        {
            if ($objEspecieLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                $possuiRelacionamentos = count($objEspecieLitigiosoDTO->getArrObjRelEspecieLitigiosoDTO()) > 0;

                if (!$possuiRelacionamentos) {
                    $objInfraException->adicionarValidacao('Obriga��es associadas n�o informadas.');
                }
            }

        }

        private function _salvarRelacionamentos($objEspecieLitigiosoDTO)
        {
            $arrEspecieDecisaoObrigacao = $objEspecieLitigiosoDTO->getArrObjRelEspecieLitigiosoDTO();

            if ($objEspecieLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                if (count($arrEspecieDecisaoObrigacao) > 0) {
                    foreach ($arrEspecieDecisaoObrigacao as $objRelacional) {
                        $objEspecieDecisaoObrigacaoRN = new MdLitRelEspecieDecisaoObrigacaoRN();
                        $objRelacional->setNumIdEspecieDecisaoLitigioso($objEspecieLitigiosoDTO->getNumIdEspecieLitigioso());
                        $objEspecieDecisaoObrigacaoRN->cadastrar($objRelacional);
                    }
                }
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $arrObjEspecieLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function excluirControlado($arrObjEspecieLitigiosoDTO)
        {
            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_especie_decisao_excluir', __METHOD__, $arrObjEspecieLitigiosoDTO);
                $objInfraException     = new InfraException();
                $objEspecieLitigiosoBD = new MdLitEspecieDecisaoBD ($this->getObjInfraIBanco());

                if (count($arrObjEspecieLitigiosoDTO) > 0) {

                    $valido = $this->_verificarVinculos($arrObjEspecieLitigiosoDTO);

                    if (!$valido) {
                        $objInfraException->adicionarValidacao('A exclus�o da Esp�cie de Decis�o n�o � permitida, pois j� existem registros vinculados.');
                    }

                    $objInfraException->lancarValidacoes();

                    for ($i = 0; $i < count($arrObjEspecieLitigiosoDTO); $i++) {

                        $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                        $objRelEspecieDecisaoObrigacaoDTO->setNumIdEspecieDecisaoLitigioso($arrObjEspecieLitigiosoDTO[$i]->getNumIdEspecieLitigioso());

                        $objRelEspecieDecisaoObrigacaoRN = new MdLitRelEspecieDecisaoObrigacaoRN();
                        $objRelEspecieDecisaoObrigacaoRN->excluirRelacionamentos($objRelEspecieDecisaoObrigacaoDTO);

                        $objEspecieLitigiosoBD->excluir($arrObjEspecieLitigiosoDTO[$i]);
                    }

                }


                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Especie Litigiosa.', $e);
            }
        }

        private function _verificarVinculos($arrObjEspecieLitigiosoDTO)
        {
            $valido = true;
            foreach ($arrObjEspecieLitigiosoDTO as $obj) {
                $objRelTipoEspecieDecisaoLitigiosoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                $objRelTipoEspecieDecisaoLitigiosoDTO->setNumIdEspecieDecisaoLitigioso($obj->getNumIdEspecieLitigioso());
                $objRelTipoEspecieDecisaoLitigiosoDTO->retTodos();

                $objRelTipoEspecieDecisaoLitigiosoRN  = new MdLitRelTipoEspecieDecisaoRN();
                $arrRelTipoEspecieDecisaoLitigiosoDTO = $objRelTipoEspecieDecisaoLitigiosoRN->listar($objRelTipoEspecieDecisaoLitigiosoDTO);

                if (count($arrRelTipoEspecieDecisaoLitigiosoDTO) > 0) {
                    $valido = false;
                }
            }

            return $valido;
        }


        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $objEspecieLitigiosoDTO
         * @throws InfraException
         */
        protected function alterarControlado($objEspecieLitigiosoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_especie_decisao_alterar', __METHOD__, $objEspecieLitigiosoDTO);

                // Regras de Negocio
                $objInfraException = new InfraException();

                $this->_validarStrNomeEspecie($objEspecieLitigiosoDTO, $objInfraException);
//                $this->_validarResultadoDecisao($objEspecieLitigiosoDTO, $objInfraException);
                $this->_validarObrigacao($objEspecieLitigiosoDTO, $objInfraException);

                $objInfraException->lancarValidacoes();

                $objEspecieLitigiosoDTO->setStrNome(trim($objEspecieLitigiosoDTO->getStrNome()));

                $objEspecieLitigiosoBD            = new MdLitEspecieDecisaoBD ($this->getObjInfraIBanco());
                $objRelEspecieDecisaoObrigacaoRN  = new MdLitRelEspecieDecisaoObrigacaoRN();
                $objRelEspecieDecisaoObrigacaoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                $objRelEspecieDecisaoObrigacaoDTO->setNumIdEspecieDecisaoLitigioso($objEspecieLitigiosoDTO->getNumIdEspecieLitigioso());

                $objRelEspecieDecisaoObrigacaoRN->excluirRelacionamentos($objRelEspecieDecisaoObrigacaoDTO);
                $this->_salvarRelacionamentos($objEspecieLitigiosoDTO);

                $objEspecieLitigiosoBD->alterar($objEspecieLitigiosoDTO);

                return $objEspecieLitigiosoDTO;
                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro alterando Esp�cie Litigiosa.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $arrObjEspecieLitigiosoDTO
         * @throws InfraException
         */
        protected function reativarControlado($arrObjEspecieLitigiosoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_especie_decisao_reativar');

                if (count($arrObjEspecieLitigiosoDTO) > 0) {

                    $objEspecieLitigiosoBD = new MdLitEspecieDecisaoBD($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjEspecieLitigiosoDTO); $i++) {
                        $objEspecieLitigiosoBD->reativar($arrObjEspecieLitigiosoDTO[$i]);
                    }
                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro reativando Esp�cie Litigiosa.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $objEspecieLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function consultarConectado($objEspecieLitigiosoDTO)
        {
            try {

                // Valida Permissao
                $objEspecieLitigiosoBD = new MdLitEspecieDecisaoBD($this->getObjInfraIBanco());
                $ret                   = $objEspecieLitigiosoBD->consultar($objEspecieLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Especie Litigiosa.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param MdLitEspecieDecisaoDTO $objEspecieLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado(MdLitEspecieDecisaoDTO $objEspecieLitigiosoDTO)
        {

            try {


                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_especie_decisao_listar', __METHOD__, $objEspecieLitigiosoDTO);

                // Regras de Negocio
                $objEspecieLitigiosoBD = new MdLitEspecieDecisaoBD($this->getObjInfraIBanco());
                $ret                   = $objEspecieLitigiosoBD->listar($objEspecieLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException ('Erro listando Especie Litigiosa.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $arrObjEspecieLitigiosoDTO
         * @throws InfraException
         */
        protected function desativarControlado($arrObjEspecieLitigiosoDTO)
        {

            try {
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_especie_decisao_desativar');

                $objEspecieLitigiosoBD = new MdLitEspecieDecisaoBD ($this->getObjInfraIBanco());

                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                for ($i = 0; $i < count($arrObjEspecieLitigiosoDTO); $i++) {
                    $objEspecieLitigiosoBD->desativar($arrObjEspecieLitigiosoDTO[$i]);
                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro desativando Especie Litigiosa.', $e);
            }
        }

        /**
         * Verifica se existe alguma decis�o cadastrada para o tipo gestao de multa
         */
        public function existeDecisaoCadastradaParaTipoMulta($data)
        {
                $decisao =  new MdLitDecisaoRN();

                $arrDecisoes = $decisao->recuperarDesicoesComMultaPorTipo([
                    'IdMdLitEspecieDecisao' => $data['hdnIdEspecieDecisaoLitigioso'],
                    'StaTipoIndicacaoMulta' =>$data['tipoMulta']
                    ]);
                if(count($arrDecisoes) == 0 ){
                    return 0;
                }
                return 1;
        }

        /**
         * Valida se os servi�os de integra��o com a multa est�o configurados
         */
        public function validarIntegracaoMulta($arrDados)
        {
            try {
                $objectResponse = new stdClass();
                $objectResponse->success = 1;
                $objectResponse->integracaoCompleta = [];
                $objectResponse->integracaoIncompleta = [];

                //ser o tipo de multa n�o for por integra��o ignora
                if ($arrDados['tipoMulta'] != MdLitEspecieDecisaoDTO::$TIPO_MULTA_INTEGRACAO) {
                    return $objectResponse;
                }

                //lista de funcionalidades obrigatorias para multa com integracao WS
                $arrFuncionalidadesIntegracao = [
                    MdLitIntegracaoRN::$ARRECADACAO_LANCAMENTO_CREDITO,
                    MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO,
                    MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_LANCAMENTO,
                    MdLitIntegracaoRN::$ARRECADACAO_RETIFICAR_LANCAMENTO,
                    MdLitIntegracaoRN::$ARRECADACAO_SUSPENDER_LANCAMENTO,
                    MdLitIntegracaoRN::$ARRECADACAO_DENEGAR_RECURSO,
                    MdLitIntegracaoRN::$ARRECADACAO_CANCELAR_RECURSO,
                    MdLitIntegracaoRN::$ARRECADACAO_LISTA_MOTIVO_CANCELAMENTO,
                ];

                $MdLitIntegracaoRN = new MdLitIntegracaoRN();
                $arrObjMdLitIntegracao = $MdLitIntegracaoRN->getFuncionalidadesIntegracaoArrecadacaoCadastradas();

                //se o total de funcionalidas cadastradas para integra��o for igual ao esta ok
                if (count($arrObjMdLitIntegracao) == count($arrFuncionalidadesIntegracao)) {
                    $objectResponse->success = 1;
                    $objectResponse->integracaoCompleta = $arrObjMdLitIntegracao;
                    return $objectResponse;
                }

                $arrExistsIntegracao = [];
                foreach ($arrObjMdLitIntegracao as $integracaoDto) {
                    if (in_array($integracaoDto->get('IdMdLitFuncionalidade'), $arrFuncionalidadesIntegracao)) {
                        $arrExistsIntegracao[] = $integracaoDto->get('IdMdLitFuncionalidade');
                    }
                }

                //recupera os ids da funcionalidades sem integracao
                $arrNotExistIntegracao = array_diff($arrFuncionalidadesIntegracao, $arrExistsIntegracao);

                $mdLitFucionalidadeDTO = new MdLitFuncionalidadeDTO();
                $mdLitFucionalidadeDTO->retTodos(false);
                $mdLitFucionalidadeDTO->ret('NomeMdLitIntegracao');
                $mdLitFucionalidadeDTO->setNumIdMdLitFuncionalidade($arrNotExistIntegracao, InfraDTO::$OPER_IN);

                $mdLitFuncionalidadeRN = new MdLitFuncionalidadeRN();
                $arrMdLitFucionalidadeDTO = $mdLitFuncionalidadeRN->listar($mdLitFucionalidadeDTO);

                $objectResponse->success = 0;
                $objectResponse->integracaoCompleta = $arrObjMdLitIntegracao;
                $objectResponse->integracaoIncompleta = $arrMdLitFucionalidadeDTO;
                return $objectResponse;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }

        }

        public function validarTipoDecisoesDirentes($data)
        {
            //consulta as especies de decis�o que ser�o vinculadas ao tipo de decisao
            $mdLitEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
            $mdLitEspecieDecisaoDTO->setDistinct(true);
            $mdLitEspecieDecisaoDTO->ret('StaTipoIndicacaoMulta');
            $mdLitEspecieDecisaoDTO->set('IdEspecieLitigioso', $data['arrEspeciesId'], InfraDTO::$OPER_IN);
            $mdLitEspecieDecisaoDTO->set('SinGestaoMulta', "S");

            $mdLitEspecieDecisaoRN =  new MdLitEspecieDecisaoRN();
            $result = $mdLitEspecieDecisaoRN->listar($mdLitEspecieDecisaoDTO);

            if(count($result)>1){
                return 0;
            }
             return 1;
        }

        public function getEspecieDecisoesById($data)
        {
            //consulta as especies por id
            $mdLitEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
            $mdLitEspecieDecisaoDTO->retTodos();
            $mdLitEspecieDecisaoDTO->set('IdEspecieLitigioso', $data['arrEspeciesId'], InfraDTO::$OPER_IN);

            $mdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
            $result = $mdLitEspecieDecisaoRN->listar($mdLitEspecieDecisaoDTO);

            return $result;
        }
    }
