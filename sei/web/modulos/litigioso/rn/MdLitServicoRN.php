<?php
    /**
     * ANATEL
     *
     * @since  18/04/2017
     * @author Andr� Luiz <andre.luiz@castgroup.com.br>
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitServicoRN extends InfraRN
    {

        public static $STA_ORIGEM_MANUAL     = 'M';
        public static $STA_ORIGEM_INTEGRACAO = 'I';

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
                throw new InfraException ('Erro cadastrando Servi�o.', $e);
            }
        }

        protected function excluirControlado($arrObjServicoDTO)
        {
            try {

                if($arrObjServicoDTO[0]->isSetBolIsAuditoria() && $arrObjServicoDTO[0]->getBolIsAuditoria() !== false){
                    SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_excluir', __METHOD__, $arrObjServicoDTO);
                }

                $objServicoBD = new MdLitServicoBD ($this->getObjInfraIBanco());


                for ($i = 0; $i < count($arrObjServicoDTO); $i++) {
                    $this->_excluirModalidade($arrObjServicoDTO[$i]->getNumIdMdLitServico());
                    $this->_excluirAbrangencia($arrObjServicoDTO[$i]->getNumIdMdLitServico());
                    $objServicoBD->excluir($arrObjServicoDTO[$i]);
                }

            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Servi�o.', $e);
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
                throw new InfraException ('Erro excluindo Servi�o.', $e);
            }
        }


        protected function alterarControlado($objServicoDTO)
        {

            try {

                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_alterar', __METHOD__, $objServicoDTO);

                $this->cadastrar($objServicoDTO);

                return $objServicoDTO;
            } catch (Exception $e) {
                throw new InfraException ('Erro alterando Servi�o.', $e);
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
                throw new InfraException ('Erro reativando Servi�o.', $e);
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
                throw new InfraException('Erro consultando Servi�o.', $e);
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
                throw new InfraException ('Erro listando Servi�o.', $e);
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
                throw new InfraException ('Erro desativando Servi�o.', $e);
            }
        }


        protected function contarConectado($objMdLitServicoDTO)
        {

            try {
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_servico_consultar');

                $objServicoBD = new MdLitServicoBD ($this->getObjInfraIBanco());

                return $objServicoBD->contar($objMdLitServicoDTO);

            } catch (Exception $e) {
                throw new InfraException ('Erro contagem Servi�o.', $e);
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

            $objMdLitRelServicoModalidadeRN = new MdLitRelServicoModalidadeRN();
            $this->_excluirModalidade($objMdLitServicoDTO->getNumIdMdLitServico());
            foreach ($objMdLitServicoDTO->getArrModalidade() as $idModalidade) {
                $objMdLitRelServicoModalidadeDTO = new MdLitRelServicoModalidadeDTO();
                $objMdLitRelServicoModalidadeDTO->setNumIdMdLitServico($objMdLitServicoDTO->getNumIdMdLitServico());
                $objMdLitRelServicoModalidadeDTO->setNumIdMdLitModalidade($idModalidade);
                $objMdLitRelServicoModalidadeRN->cadastrar($objMdLitRelServicoModalidadeDTO);
            }

            $objMdLitRelServicoAbrangenRN = new MdLitRelServicoAbrangenRN();
            $this->_excluirAbrangencia($objMdLitServicoDTO->getNumIdMdLitServico());
            foreach ($objMdLitServicoDTO->getArrAbrangencia() as $idAbrangencia) {
                $objMdLitRelServicoAbrangenDTO = new MdLitRelServicoAbrangenDTO();
                $objMdLitRelServicoAbrangenDTO->setNumIdMdLitServico($objMdLitServicoDTO->getNumIdMdLitServico());
                $objMdLitRelServicoAbrangenDTO->setNumIdMdLitAbrangencia($idAbrangencia);
                $objMdLitRelServicoAbrangenRN->cadastrar($objMdLitRelServicoAbrangenDTO);
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

            $objMdLitRelServicoModalidadeRN = new MdLitRelServicoModalidadeRN();
            $this->_excluirModalidade($objMdLitServicoDTO->getNumIdMdLitServico());
            foreach ($objMdLitServicoDTO->getArrModalidade() as $idModalidade) {
                $objMdLitRelServicoModalidadeDTO = new MdLitRelServicoModalidadeDTO();
                $objMdLitRelServicoModalidadeDTO->setNumIdMdLitServico($objMdLitServicoDTO->getNumIdMdLitServico());
                $objMdLitRelServicoModalidadeDTO->setNumIdMdLitModalidade($idModalidade);
                $objMdLitRelServicoModalidadeRN->cadastrar($objMdLitRelServicoModalidadeDTO);
            }

            $objMdLitRelServicoAbrangenRN = new MdLitRelServicoAbrangenRN();
            $this->_excluirAbrangencia($objMdLitServicoDTO->getNumIdMdLitServico());
            foreach ($objMdLitServicoDTO->getArrAbrangencia() as $idAbrangencia) {
                $objMdLitRelServicoAbrangenDTO = new MdLitRelServicoAbrangenDTO();
                $objMdLitRelServicoAbrangenDTO->setNumIdMdLitServico($objMdLitServicoDTO->getNumIdMdLitServico());
                $objMdLitRelServicoAbrangenDTO->setNumIdMdLitAbrangencia($idAbrangencia);
                $objMdLitRelServicoAbrangenRN->cadastrar($objMdLitRelServicoAbrangenDTO);
            }

            return $objMdLitServicoDTO;
        }

        private function _validarServicoIntegracao($objInfraException, $objMdLitServicoDTO)
        {

            if (InfraString::isBolVazia($objMdLitServicoDTO->getStrCodigo())) {
                $objInfraException->adicionarValidacao('Informe o campo C�digo!');
            }

            if (!$objMdLitServicoDTO->isSetArrModalidade()) {
                $objInfraException->adicionarValidacao('Informe ao menos uma Modalidade!');
            }

            if (!$objMdLitServicoDTO->isSetArrAbrangencia()) {
                $objInfraException->adicionarValidacao('Informe ao menos uma Abrang�ncia!');
            }

            if($this->_verificarServicoDuplicado($objMdLitServicoDTO) > 0){
                $objInfraException->adicionarValidacao("Servi�o com o c�digo '{$objMdLitServicoDTO->getStrCodigo()}' j� cadastrado!");
            }

        }

        private function _validarServicoManual($objInfraException, $objMdLitServicoDTO)
        {

            if (InfraString::isBolVazia($objMdLitServicoDTO->getStrCodigo())) {
                $objInfraException->adicionarValidacao('Informe o campo C�digo!');
            }

            if (InfraString::isBolVazia($objMdLitServicoDTO->getStrSigla())) {
                $objInfraException->adicionarValidacao('Informe o campo Sigla!');
            }

            if (InfraString::isBolVazia($objMdLitServicoDTO->getStrDescricao())) {
                $objInfraException->adicionarValidacao('Informe o campo Descri��o!');
            }

            if (!$objMdLitServicoDTO->isSetArrModalidade()) {
                $objInfraException->adicionarValidacao('Informe ao menos uma Modalidade!');
            }

            if (!$objMdLitServicoDTO->isSetArrAbrangencia()) {
                $objInfraException->adicionarValidacao('Informe ao menos uma Abrang�ncia!');
            }

            if($this->_verificarServicoDuplicado($objMdLitServicoDTO) > 0){
                $objInfraException->adicionarValidacao('Servi�o com o c�digo j� cadastrado!');
            }

        }

        private function _excluirModalidade($idMdLitServico)
        {
            $objMdLitRelServicoModalidadeRN  = new MdLitRelServicoModalidadeRN();
            $objMdLitRelServicoModalidadeDTO = new MdLitRelServicoModalidadeDTO();
            $objMdLitRelServicoModalidadeDTO->setNumIdMdLitServico($idMdLitServico);
            $objMdLitRelServicoModalidadeDTO->retTodos();
            $arrObjMdLitRelServicoModalidadeDTO = $objMdLitRelServicoModalidadeRN->listar($objMdLitRelServicoModalidadeDTO);
            $objMdLitRelServicoModalidadeRN->excluir($arrObjMdLitRelServicoModalidadeDTO);
        }

        private function _excluirAbrangencia($idMdLitServico)
        {
            $objMdLitRelServicoAbrangenDTO = new MdLitRelServicoAbrangenDTO();
            $objMdLitRelServicoAbrangenRN  = new MdLitRelServicoAbrangenRN();
            $objMdLitRelServicoAbrangenDTO->setNumIdMdLitServico($idMdLitServico);
            $objMdLitRelServicoAbrangenDTO->retTodos();
            $arrObjMdLitRelServicoAbrangenDTO = $objMdLitRelServicoAbrangenRN->listar($objMdLitRelServicoAbrangenDTO);
            $objMdLitRelServicoAbrangenRN->excluir($arrObjMdLitRelServicoAbrangenDTO);
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