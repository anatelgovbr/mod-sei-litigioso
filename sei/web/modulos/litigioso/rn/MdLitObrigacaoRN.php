<?php
    /**
     * ANATEL
     *
     * 16/05/2016 - criado por CAST
     *
     * Versão do Gerador de Código:
     *
     * Versão no CVS:
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitObrigacaoRN extends InfraRN
    {

        var $funcionalidade = "Obrigação";

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        protected function cadastrarControlado(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_cadastrar', __METHOD__, $objObrigacaoLitigiosoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                $this->validarStrNome($objObrigacaoLitigiosoDTO, $objInfraException);
                $this->validarStrDescricao($objObrigacaoLitigiosoDTO, $objInfraException);
                $this->validarStrSinAtivo($objObrigacaoLitigiosoDTO, $objInfraException);

                if ($objObrigacaoLitigiosoDTO->isSetStrDescricao()) {

                    // [RN3] - Não deve ser permitida a inclusão/alteração de registros com o mesmo nome de Obrigação.
                    $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
                    $numDuplicadas           = $objObrigacaoLitigiosoRN->_validarDuplicidadeNome($objObrigacaoLitigiosoDTO, $objInfraException);

                    if ($numDuplicadas) {
                        if ($numDuplicadas > 0) {
                            $objInfraException->adicionarValidacao('Já existe ' . $this->funcionalidade . ' cadastrada.');
                        }
                    }

                }

                $objInfraException->lancarValidacoes();

                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                $ret                     = $objObrigacaoLitigiosoBD->cadastrar($objObrigacaoLitigiosoDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando ' . $this->funcionalidade . '.', $e);
            }
        }

        private function validarStrNome(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO, InfraException $objInfraException)
        {
            if (InfraString::isBolVazia($objObrigacaoLitigiosoDTO->getStrNome())) {
                $objInfraException->adicionarValidacao('Nome não informado.');
            } else {
                $objObrigacaoLitigiosoDTO->setStrNome(trim($objObrigacaoLitigiosoDTO->getStrNome()));

                if (strlen($objObrigacaoLitigiosoDTO->getStrNome()) > 50) {
                    $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
                }
            }
        }

        private function validarStrDescricao(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO, InfraException $objInfraException)
        {
            if (InfraString::isBolVazia($objObrigacaoLitigiosoDTO->getStrDescricao())) {
                $objInfraException->adicionarValidacao('Descrição não informada.');
            } else {
                $objObrigacaoLitigiosoDTO->setStrDescricao(trim($objObrigacaoLitigiosoDTO->getStrDescricao()));

                if (strlen($objObrigacaoLitigiosoDTO->getStrDescricao()) > 250) {
                    $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
                }
            }
        }

        private function validarStrSinAtivo(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO, InfraException $objInfraException)
        {
            if (InfraString::isBolVazia($objObrigacaoLitigiosoDTO->getStrSinAtivo())) {
                $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
            } else {
                if (!InfraUtil::isBolSinalizadorValido($objObrigacaoLitigiosoDTO->getStrSinAtivo())) {
                    $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
                }
            }
        }

        private function _validarDuplicidadeNome(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO)
        { // EU3476-RN3
            try {

                //VALIDACAO DE DUPLICIDADE DA SIGLA
                $objObrigacaoLitigiosoDTO2 = new MdLitObrigacaoDTO();
                $objObrigacaoLitigiosoDTO2->setStrNome($objObrigacaoLitigiosoDTO->getStrNome());

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_consultar', __METHOD__, $objObrigacaoLitigiosoDTO2);

                $objObrigacaoLitigiosoBD2 = new MdLitObrigacaoBD($this->getObjInfraIBanco());

                //echo $objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso(); die();

                //VALIDACAO A SER EXECUTADA NA INSERÇAO DE NOVOS REGISTROS
                if (!is_numeric($objObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso())) {
                    $ret = $objObrigacaoLitigiosoBD2->contar($objObrigacaoLitigiosoDTO2);
                } //VALIDACAO A SER EXECUTADA QUANDO É FEITO UPDATE DE REGISTROS
                else {
                    $objObrigacaoFiltroDTO2 = new MdLitObrigacaoDTO();
                    $objObrigacaoFiltroDTO2->retTodos();
                    $objObrigacaoFiltroDTO2->setNumIdObrigacaoLitigioso($objObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso(), InfraDTO::$OPER_DIFERENTE);
                    $objObrigacaoFiltroDTO2->setStrNome($objObrigacaoLitigiosoDTO->getStrNome(), InfraDTO::$OPER_IGUAL);

                    $ret = $objObrigacaoLitigiosoBD2->consultar($objObrigacaoFiltroDTO2);
                    if (!$ret) {
                        $ret = 0;
                    } else {
                        $ret = ($objObrigacaoLitigiosoBD2->contar($ret));
                    }
                }

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro realizando verificação duplicidade.', $e);
            }
        }

        protected function alterarControlado(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_alterar', __METHOD__, $objObrigacaoLitigiosoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                if ($objObrigacaoLitigiosoDTO->isSetStrNome()) {
                    $this->validarStrNome($objObrigacaoLitigiosoDTO, $objInfraException);
                }
                if ($objObrigacaoLitigiosoDTO->isSetStrDescricao()) {
                    $this->validarStrDescricao($objObrigacaoLitigiosoDTO, $objInfraException);
                }
                if ($objObrigacaoLitigiosoDTO->isSetStrSinAtivo()) {
                    $this->validarStrSinAtivo($objObrigacaoLitigiosoDTO, $objInfraException);
                }

                if ($objObrigacaoLitigiosoDTO->isSetStrDescricao()) {

                    // [RN3] - Não deve ser permitida a inclusão/alteração de registros com o mesmo nome de Obrigação.
                    $objObrigacaoLitigiosoRN = new MdLitObrigacaoRN();
                    $numDuplicadas           = $objObrigacaoLitigiosoRN->_validarDuplicidadeNome($objObrigacaoLitigiosoDTO, $objInfraException);

                    if ($numDuplicadas) {
                        if ($numDuplicadas > 0) {
                            $objInfraException->adicionarValidacao('Já existe ' . $this->funcionalidade . ' cadastrada.');
                        }
                    }

                }

                $objInfraException->lancarValidacoes();

                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                $objObrigacaoLitigiosoBD->alterar($objObrigacaoLitigiosoDTO);

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro alterando Obrigação.', $e);
            }
        }

        protected function excluirControlado($arrObjObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_excluir', __METHOD__, $arrObjObrigacaoLitigiosoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjObrigacaoLitigiosoDTO); $i++) {
                    // RN1 - já associada a uma espécie de decisão
                    //       já utilizada em um processo. ???
                    $objObrigacaoLitigiosoRN  = new MdLitObrigacaoRN();
                    $objObrigacaoLitigiosoDTO = new MdLitObrigacaoDTO();
                    $objObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso($arrObjObrigacaoLitigiosoDTO[$i]->getNumIdObrigacaoLitigioso());

                    $numDependenciaEspecie = $objObrigacaoLitigiosoRN->_validarDependenciaEspecie($objObrigacaoLitigiosoDTO);
                    if ($numDependenciaEspecie == 0) {
                        $objObrigacaoLitigiosoBD->excluir($arrObjObrigacaoLitigiosoDTO[$i]);
                    } else {
                        $objInfraException->adicionarValidacao('A exclusão da obrigação não é permitida, pois já existem registros vinculados.');
                        $objInfraException->lancarValidacoes();
                    }

                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro ' . $this->funcionalidade . '.', $e);
            }
        }

        public function _validarDependenciaEspecie(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO)
        { // EU3476-RN1
            try {
                //VALIDACAO DE DUPLICIDADE DA SIGLA
                $objRelEspecieDecisaoObrigacaoLitigiosoDTO = new MdLitRelEspecieDecisaoObrigacaoDTO();
                $objRelEspecieDecisaoObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso($objObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso());
                //$objRelEspecieDecisaoObrigacaoLitigiosoDTO->setNumIdObrigacaoLitigioso(2);

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_consultar', __METHOD__, $objRelEspecieDecisaoObrigacaoLitigiosoDTO);

                $objRelEspecieDecisaoObrigacaoLitigiosoBD = new MdLitRelEspecieDecisaoObrigacaoBD($this->getObjInfraIBanco());

                if (is_numeric($objRelEspecieDecisaoObrigacaoLitigiosoDTO->getNumIdObrigacaoLitigioso())) {
                    $ret = $objRelEspecieDecisaoObrigacaoLitigiosoBD->contar($objRelEspecieDecisaoObrigacaoLitigiosoDTO);
                }

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro realizando validar dependência espécie.', $e);
            }
        }

        protected function consultarConectado(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_consultar', __METHOD__, $objObrigacaoLitigiosoDTO);


                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                $ret                     = $objObrigacaoLitigiosoBD->consultar($objObrigacaoLitigiosoDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando ' . $this->funcionalidade . '.', $e);
            }
        }

        protected function listarConectado(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_listar', __METHOD__, $objObrigacaoLitigiosoDTO);

                //Regras de Negocio

                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                $ret                     = $objObrigacaoLitigiosoBD->listar($objObrigacaoLitigiosoDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro listando ' . $this->funcionalidade . '.', $e);
            }
        }

        protected function contarConectado(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_listar', __METHOD__, $objObrigacaoLitigiosoDTO);

                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                $ret                     = $objObrigacaoLitigiosoBD->contar($objObrigacaoLitigiosoDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro contando ' . $this->funcionalidade . '.', $e);
            }
        }

        protected function desativarControlado($arrObjObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_desativar', __METHOD__, $arrObjObrigacaoLitigiosoDTO);

                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjObrigacaoLitigiosoDTO); $i++) {
                    $objObrigacaoLitigiosoBD->desativar($arrObjObrigacaoLitigiosoDTO[$i]);
                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro desativando ' . $this->funcionalidade . '.', $e);
            }
        }


        protected function reativarControlado($arrObjObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_reativar', __METHOD__, $arrObjObrigacaoLitigiosoDTO);

                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjObrigacaoLitigiosoDTO); $i++) {
                    $objObrigacaoLitigiosoBD->reativar($arrObjObrigacaoLitigiosoDTO[$i]);
                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro reativando ' . $this->funcionalidade . '.', $e);
            }
        }

        protected function bloquearControlado(MdLitObrigacaoDTO $objObrigacaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_obrigacao_consultar', __METHOD__, $objObrigacaoLitigiosoDTO);

                $objObrigacaoLitigiosoBD = new MdLitObrigacaoBD($this->getObjInfraIBanco());
                $ret                     = $objObrigacaoLitigiosoBD->bloquear($objObrigacaoLitigiosoDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro bloqueando ' . $this->funcionalidade . '.', $e);
            }
        }


    }
