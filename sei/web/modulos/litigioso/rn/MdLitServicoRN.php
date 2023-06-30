<?php
    /**
     * ANATEL
     *
     * @since  18/04/2017
     * @author André Luiz <andre.luiz@castgroup.com.br>
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitServicoRN extends InfraRN
    {

        public static $STA_ORIGEM_MANUAL     = 'M';
        public static $STA_ORIGEM_INTEGRACAO = 'I';
        public static $SIN_ATIVO             = 'S';
        public static $SIN_INATIVO           = 'N';

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        protected function cadastrarControlado(MdLitServicoDTO $objMdLitServicoDTO)
        {
            try {
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_cadastrar', __METHOD__, $objMdLitServicoDTO);

                $objInfraException = new InfraException();

                if ($objMdLitServicoDTO->getStrStaOrigem() == self::$STA_ORIGEM_INTEGRACAO) {
                    $this->_validarServicoIntegracao($objInfraException, $objMdLitServicoDTO);
                    $objInfraException->lancarValidacoes();
                    $objMdLitServicoDTO = $this->_salvarServicoIntegracao($objMdLitServicoDTO);

                } else {
                    $this->_validarServicoManual($objInfraException, $objMdLitServicoDTO);
                    $objInfraException->lancarValidacoes();
                    $objMdLitServicoDTO = $this->_salvarServicoManual($objMdLitServicoDTO);
                }

                return $objMdLitServicoDTO;

            } catch (Exception $e) {
                throw new InfraException ('Erro cadastrando Serviço.', $e);
            }
        }

        protected function excluirControlado($arrObjServicoDTO)
        {
            try {

                if($arrObjServicoDTO[0]->isSetBolIsAuditoria() && $arrObjServicoDTO[0]->getBolIsAuditoria() !== false){
                    SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_excluir', __METHOD__, $arrObjServicoDTO);
                }

                $objServicoBD = new MdLitServicoBD ($this->getObjInfraIBanco());

            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Serviço.', $e);
            }
        }

        protected function excluirIntegracaoControlado($arrObjServicoDTO)
        {
            try {

                SessaoSEI::getInstance()->validarPermissao('md_lit_servico_excluir');

                $objServicoBD = new MdLitServicoBD ($this->getObjInfraIBanco());

                for ($i = 0; $i < count($arrObjServicoDTO); $i++) {
                    $arrObjServicoDTO[$i]->setStrSinAtivoIntegracao('N');
                    $objServicoBD->alterar($arrObjServicoDTO[$i]);
                }

            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Serviço.', $e);
            }
        }


        protected function alterarControlado($objServicoDTO)
        {

            try {

                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_alterar', __METHOD__, $objServicoDTO);

                $this->cadastrar($objServicoDTO);

                return $objServicoDTO;
            } catch (Exception $e) {
                throw new InfraException ('Erro alterando Serviço.', $e);
            }
        }

        protected function reativarControlado($arrObjServicoDTO)
        {

            try {

                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_reativar');

                if (count($arrObjServicoDTO) > 0) {

                    $objServicoBD = new MdLitServicoBD($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjServicoDTO); $i++) {
                        $objServicoBD->reativar($arrObjServicoDTO[$i]);
                    }
                }

            } catch (Exception $e) {
                throw new InfraException ('Erro reativando Serviço.', $e);
            }
        }

        protected function consultarConectado($objServicoDTO)
        {
            try {
                if($objServicoDTO->isSetStrSinAtivoIntegracao() === false){
                    $objServicoDTO->setStrSinAtivoIntegracao('S');
                }

                $objServicoBD = new MdLitServicoBD($this->getObjInfraIBanco());
                $ret          = $objServicoBD->consultar($objServicoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Serviço.', $e);
            }
        }

        protected function listarConectado(MdLitServicoDTO $objServicoDTO)
        {

            try {
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_listar', __METHOD__, $objServicoDTO);

                if($objServicoDTO->isSetStrSinAtivoIntegracao() === false){
                    $objServicoDTO->setStrSinAtivoIntegracao('S');
                }

                $objServicoBD = new MdLitServicoBD($this->getObjInfraIBanco());
                $ret          = $objServicoBD->listar($objServicoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException ('Erro listando Serviço.', $e);
            }
        }

        protected function desativarControlado($arrObjServicoDTO)
        {

            try {
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_desativar');

                $objServicoBD = new MdLitServicoBD ($this->getObjInfraIBanco());

                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                for ($i = 0; $i < count($arrObjServicoDTO); $i++) {
                    $objServicoBD->desativar($arrObjServicoDTO[$i]);
                }

            } catch (Exception $e) {
                throw new InfraException ('Erro desativando Serviço.', $e);
            }
        }


        protected function contarConectado($objMdLitServicoDTO)
        {

            try {
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_consultar');

                $objServicoBD = new MdLitServicoBD ($this->getObjInfraIBanco());

                return $objServicoBD->contar($objMdLitServicoDTO);

            } catch (Exception $e) {
                throw new InfraException ('Erro contagem Serviço.', $e);
            }
        }


        private function _salvarServicoIntegracao($objMdLitServicoDTO)
        {
            $objMdLitServicoBD = new MdLitServicoBD($this->getObjInfraIBanco());
            if ($objMdLitServicoDTO->isSetNumIdMdLitServico()) {
                $objMdLitServicoBD->alterar($objMdLitServicoDTO);
            } else {
                $objMdLitServicoDTO->setStrSinAtivoIntegracao('S');
                $objMdLitServicoDTO = $objMdLitServicoBD->cadastrar($objMdLitServicoDTO);
            }

            return $objMdLitServicoDTO;

        }

        private function _salvarServicoManual($objMdLitServicoDTO)
        {
            $objMdLitServicoBD = new MdLitServicoBD($this->getObjInfraIBanco());
            if ($objMdLitServicoDTO->isSetNumIdMdLitServico()) {
                $objMdLitServicoBD->alterar($objMdLitServicoDTO);
            } else {
                $objMdLitServicoDTO->setStrSinAtivoIntegracao('S');
                $objMdLitServicoDTO = $objMdLitServicoBD->cadastrar($objMdLitServicoDTO);
            }

            return $objMdLitServicoDTO;
        }

        private function _validarServicoIntegracao($objInfraException, $objMdLitServicoDTO)
        {

            if (InfraString::isBolVazia($objMdLitServicoDTO->getStrCodigo())) {
                $objInfraException->adicionarValidacao('Informe o campo Código!');
            }

            if($this->_verificarServicoDuplicado($objMdLitServicoDTO) > 0){
                $objInfraException->adicionarValidacao("Serviço com o código '{$objMdLitServicoDTO->getStrCodigo()}' já cadastrado!");
            }

        }

        private function _validarServicoManual($objInfraException, $objMdLitServicoDTO)
        {

            if (InfraString::isBolVazia($objMdLitServicoDTO->getStrCodigo())) {
                $objInfraException->adicionarValidacao('Informe o campo Código!');
            }

            if (InfraString::isBolVazia($objMdLitServicoDTO->getStrSigla())) {
                $objInfraException->adicionarValidacao('Informe o campo Sigla!');
            }

            if (InfraString::isBolVazia($objMdLitServicoDTO->getStrDescricao())) {
                $objInfraException->adicionarValidacao('Informe o campo Descrição!');
            }

            if($this->_verificarServicoDuplicado($objMdLitServicoDTO) > 0){
                $objInfraException->adicionarValidacao('Serviço com o código já cadastrado!');
            }

        }

        private function _verificarServicoDuplicado($objMdLitServicoDTO)
        {
            $objConsultaMdLitServicoDTO = new MdLitServicoDTO();
            $objConsultaMdLitServicoDTO->retNumIdMdLitServico();
            $objConsultaMdLitServicoDTO->setStrCodigo($objMdLitServicoDTO->getStrCodigo());
            $objConsultaMdLitServicoDTO->setStrSinAtivoIntegracao('S');
            if ($objMdLitServicoDTO->isSetNumIdMdLitServico()) {
                $objConsultaMdLitServicoDTO->setNumIdMdLitServico($objMdLitServicoDTO->getNumIdMdLitServico(), InfraDTO::$OPER_DIFERENTE);
            }

            return $this->contar($objConsultaMdLitServicoDTO);

        }

    }
