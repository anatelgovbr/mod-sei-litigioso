<?
    /**
     * ANATEL
     *
     * 16/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitCondutaRN extends InfraRN
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
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitCondutaDTO $objCondutaLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function cadastrarControlado(MdLitCondutaDTO $objCondutaLitigiosoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_conduta_cadastrar', __METHOD__, $objCondutaLitigiosoDTO);

                // Regras de Negocio
                $objInfraException = new InfraException();

                $this->_validarStrNomeConduta($objCondutaLitigiosoDTO, $objInfraException);

                $objInfraException->lancarValidacoes();

                $objCondutaLitigiosoBD = new MdLitCondutaBD($this->getObjInfraIBanco());

                $objCondutaLitigiosoDTO->setStrNome(trim($objCondutaLitigiosoDTO->getStrNome()));
                $objCondutaLitigiosoDTO->setStrSinAtivo('S');
                $objRetorno = $objCondutaLitigiosoBD->cadastrar($objCondutaLitigiosoDTO);

                return $objRetorno;

            } catch (Exception $e) {
                throw new InfraException ('Erro cadastrando Fase Litigiosa.', $e);
            }
        }

        /**
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitCondutaDTO $objCondutaLitigiosoDTO
         * @param InfraException  $objInfraException
         * @throws InfraException
         */
        private function _validarStrNomeConduta(MdLitCondutaDTO $objCondutaLitigiosoDTO, InfraException $objInfraException)
        {

            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (InfraString::isBolVazia($objCondutaLitigiosoDTO->getStrNome())) {
                $objInfraException->adicionarValidacao('Conduta não informada.');
            }

            $objCondutaLitigiosoDTO2 = new MdLitCondutaDTO ();
            $nomeFase                = trim($objCondutaLitigiosoDTO->getStrNome());
            $objCondutaLitigiosoDTO2->setStrNome($nomeFase);

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_conduta_consultar', __METHOD__, $objCondutaLitigiosoDTO2);

            $objCondutaLitigiosoBD = new MdLitCondutaBD ($this->getObjInfraIBanco());

            // Valida Quantidade de Caracteres
            if (strlen($objCondutaLitigiosoDTO->getStrNome()) > 500) {
                $objInfraException->adicionarValidacao('Conduta possui tamanho superior a 100 caracteres.');
            }

            // VALIDA DUPLICAÇÃO
            // VALIDACAO A SER EXECUTADA NA INSERÇAO DE NOVOS REGISTROS
            if (!is_numeric($objCondutaLitigiosoDTO->getNumIdCondutaLitigioso())) {

                $ret = $objCondutaLitigiosoBD->contar($objCondutaLitigiosoDTO2);

                if ($ret > 0) {
                    $objInfraException->adicionarValidacao('Já existe Conduta cadastrada.');
                } // VALIDACAO A SER EXECUTADA QUANDO É FEITO UPDATE DE REGISTROS

            } else {

                $dtoValidacao = new MdLitCondutaDTO();
                $dtoValidacao->setStrNome(trim($objCondutaLitigiosoDTO->getStrNome()), InfraDTO::$OPER_IGUAL);
                $dtoValidacao->setNumIdCondutaLitigioso($objCondutaLitigiosoDTO->getNumIdCondutaLitigioso(), InfraDTO::$OPER_DIFERENTE);

                $retDuplicidade = $objCondutaLitigiosoBD->contar($dtoValidacao);

                if ($retDuplicidade > 0) {
                    $objInfraException->adicionarValidacao('Já existe Conduta cadastrada.');
                }
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjCondutaLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function excluirControlado($arrObjCondutaLitigiosoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_conduta_excluir', __METHOD__, $arrObjCondutaLitigiosoDTO);

                $listaIds = array();

                for ($i = 0; $i < count($arrObjCondutaLitigiosoDTO); $i++) {

                    array_push($listaIds, $arrObjCondutaLitigiosoDTO[$i]->getNumIdCondutaLitigioso());

                }

                //nao permitir a exclusao de Conduta se a mesma já estiver vinculada a um dispositivo normativo
                $bdRelDispositivoNormativoConduta = new MdLitRelDispositivoNormativoCondutaBD($this->getObjInfraIBanco());
                $dtoValidacao                     = new MdLitRelDispositivoNormativoCondutaDTO();
                $dtoValidacao->setNumIdConduta($listaIds, InfraDTO::$OPER_IN);
                $retTotal = $bdRelDispositivoNormativoConduta->contar($dtoValidacao);

                if ($retTotal > 0) {

                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao('A exclusão da conduta não é permitida, pois já existem registros vinculados.');
                    $objInfraException->lancarValidacoes();

                } else if (count($arrObjCondutaLitigiosoDTO) > 0) {

                    $objCondutaLitigiosoBD = new MdLitCondutaBD($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjCondutaLitigiosoDTO); $i++) {
                        $objCondutaLitigiosoBD->excluir($arrObjCondutaLitigiosoDTO[$i]);
                    }

                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Fase Litigiosa.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $objCondutaLitigiosoDTO
         * @throws InfraException
         */
        protected function alterarControlado($objCondutaLitigiosoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_conduta_alterar', __METHOD__, $objCondutaLitigiosoDTO);


                // Regras de Negocio
                $objInfraException = new InfraException ();

                $this->_validarStrNomeConduta($objCondutaLitigiosoDTO, $objInfraException);

                $objInfraException->lancarValidacoes();

                $objCondutaLitigiosoDTO->setStrNome(trim($objCondutaLitigiosoDTO->getStrNome()));

                $objCondutaLitigiosoBD = new MdLitCondutaBD ($this->getObjInfraIBanco());
                $objCondutaLitigiosoBD->alterar($objCondutaLitigiosoDTO);


                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro alterando Fase Litigiosa.', $e);
            }
        }

        /**
         * @access   protected
         * @author   Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjCondutaLitigiosoDTO
         * @throws InfraException
         * @internal param $
         */
        protected function reativarControlado($arrObjCondutaLitigiosoDTO)
        {
            try {
                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_conduta_reativar');

                if (count($arrObjCondutaLitigiosoDTO) > 0) {
                    $objCondutaLitigiosoBD = new MdLitCondutaBD($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjCondutaLitigiosoDTO); $i++) {
                        $objCondutaLitigiosoBD->reativar($arrObjCondutaLitigiosoDTO[$i]);
                    }
                }
            } catch (Exception $e) {
                throw new InfraException('Erro reativando Conduta Litigiosa.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $objCondutaLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function consultarConectado($objCondutaLitigiosoDTO)
        {
            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_conduta_consultar', __METHOD__, $objCondutaLitigiosoDTO);

                $objCondutaLitigiosoBD = new MdLitCondutaBD($this->getObjInfraIBanco());
                $ret                   = $objCondutaLitigiosoBD->consultar($objCondutaLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Conduta Litigiosa.', $e);
            }
        }

        /**
         * @access   protected
         * @author   Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitCondutaDTO $objCondutaLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $
         */
        protected function listarConectado(MdLitCondutaDTO $objCondutaLitigiosoDTO)
        {

            try {

                $objCondutaLitigiosoBD = new MdLitCondutaBD($this->getObjInfraIBanco());
                $ret                   = $objCondutaLitigiosoBD->listar($objCondutaLitigiosoDTO);


                return $ret;

            } catch (Exception $e) {
                throw new InfraException ('Erro listando Fase Litigiosoes.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjCondutaLitigiosoDTO
         * @throws InfraException
         */
        protected function desativarControlado($arrObjCondutaLitigiosoDTO)
        {

            try {

                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_conduta_desativar');

                $listaIds = array();

                for ($i = 0; $i < count($arrObjCondutaLitigiosoDTO); $i++) {

                    array_push($listaIds, $arrObjCondutaLitigiosoDTO[$i]->getNumIdCondutaLitigioso());

                }

                //nao permitir a exclusao de Conduta se a mesma já estiver vinculada a um dispositivo normativo
                $bdRelDispositivoNormativoConduta = new MdLitRelDispositivoNormativoCondutaBD($this->getObjInfraIBanco());
                $dtoValidacao                     = new MdLitRelDispositivoNormativoCondutaDTO();
                $dtoValidacao->setNumIdConduta($listaIds, InfraDTO::$OPER_IN);
                $retTotal = $bdRelDispositivoNormativoConduta->contar($dtoValidacao);

                if ($retTotal > 0) {

                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao('A desativação da Conduta não é permitida, pois já existem registros vinculados.');
                    $objInfraException->lancarValidacoes();

                } else if (count($arrObjCondutaLitigiosoDTO) > 0) {
                    $objCondutaLitigiosoBD = new MdLitCondutaBD ($this->getObjInfraIBanco());
                    for ($i = 0; $i < count($arrObjCondutaLitigiosoDTO); $i++) {
                        $objCondutaLitigiosoBD->desativar($arrObjCondutaLitigiosoDTO[$i]);
                    }
                }

            } catch (Exception $e) {
                throw new InfraException ('Erro desativando Conduta Litigiosa.', $e);
            }
        }

        /**
         * Validate field "Descrição".
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @return void
         */
        private function _validarStrDescricao($objCondutaLitigiosoDTO, $objInfraException)
        {
            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (InfraString::isBolVazia($objCondutaLitigiosoDTO->getStrDescricao())) {
                $objInfraException->adicionarValidacao('Descrição não informada.');
            }
            if (trim($objCondutaLitigiosoDTO->getStrDescricao()) != '') {
                if (strlen($objCondutaLitigiosoDTO->getStrDescricao()) > 250) {
                    $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 100 caracteres.');
                }
            }
        }

        /**
         * Validate "Fase" is associated with "Situação"
         *
         * @param $objCondutaLitigiosoDTO            MdLitCondutaDTO
         * @param $arrObjCondutaLitigiosoDTOValidado Array
         * @param $validado                          Boolean
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @return void
         */
        private function _validarCadastroAssociado($objCondutaLitigiosoDTO, &$arrObjCondutaLitigiosoDTOValidado, &$validado)
        {
            $objSituacaoLitigiosoBD = new MdLitSituacaoBD ($this->getObjInfraIBanco());
            $idFase                 = $objCondutaLitigiosoDTO->getNumIdFaseLitigioso();

            $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
            $objSituacaoLitigiosoDTO->setNumIdFaseLitigioso($idFase);

            $countSitFase = $objSituacaoLitigiosoBD->contar($objSituacaoLitigiosoDTO);

            if ($countSitFase > 0) {
                $validado = false;
            } else {
                array_push($arrObjCondutaLitigiosoDTOValidado, $objCondutaLitigiosoDTO);
            }

        }
    }
