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
                throw new InfraException ('Erro cadastrando Espécie Litigiosa.', $e);
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
                $objInfraException->adicionarValidacao('Nome da Espécie não informado.');
            }

            $objEspecieLitigiosoDTO2 = new MdLitEspecieDecisaoDTO ();
            $objEspecieLitigiosoDTO2->setStrNome(trim($objEspecieLitigiosoDTO->getStrNome()), InfraDTO::$OPER_IGUAL);

            $objEspecieLitigiosoBD = new MdLitEspecieDecisaoBD ($this->getObjInfraIBanco());

            // Valida Quantidade de Caracteres
            if (strlen($objEspecieLitigiosoDTO->getStrNome()) > 50) {
                $objInfraException->adicionarValidacao('Espécie de Decisão possui tamanho superior a 50 caracteres.');
            }

            is_numeric($objEspecieLitigiosoDTO->getNumIdEspecieLitigioso()) ? $objEspecieLitigiosoDTO2->setNumIdEspecieLitigioso($objEspecieLitigiosoDTO->getNumIdEspecieLitigioso(), InfraDTO::$OPER_DIFERENTE) : $objEspecieLitigiosoDTO->setNumIdEspecieLitigioso(null);

            $ret = $objEspecieLitigiosoBD->contar($objEspecieLitigiosoDTO2);

            if ($ret > 0) {
                $objInfraException->adicionarValidacao('Já existe Espécie de Decisão cadastrada.');
            }
        }

        /**
         * Validate fields "Resultado de Decisão".
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
                $objInfraException->adicionarValidacao('Resultado da Decisão não informado.');
            }

        }

        private function _validarObrigacao($objEspecieLitigiosoDTO, $objInfraException)
        {
            if ($objEspecieLitigiosoDTO->getStrSinIndicacaoObrigacoes() == 'S') {
                $possuiRelacionamentos = count($objEspecieLitigiosoDTO->getArrObjRelEspecieLitigiosoDTO()) > 0;

                if (!$possuiRelacionamentos) {
                    $objInfraException->adicionarValidacao('Obrigações associadas não informadas.');
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
                        $objInfraException->adicionarValidacao('A exclusão da Espécie de Decisão não é permitida, pois já existem registros vinculados.');
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
                throw new InfraException ('Erro alterando Espécie Litigiosa.', $e);
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
                throw new InfraException ('Erro reativando Espécie Litigiosa.', $e);
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
    }
