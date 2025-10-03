<?
    /**
     * ANATEL
     *
     * 20/05/2016 - criado por alan.campos@castgroup.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitTipoDecisaoRN extends InfraRN
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
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @param MdLitTipoDecisaoDTO $objTipoDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado(MdLitTipoDecisaoDTO $objTipoDecisaoLitigiosoDTO)
        {
            try {

                $objTipoDecisaoLitigiosoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());
                $ret                       = $objTipoDecisaoLitigiosoBD->listar($objTipoDecisaoLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro listando Tipos de Decisão.', $e);
            }
        }

        /**
         * @access protected
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @param $arrObjTipoDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function excluirControlado($arrObjTipoDecisaoLitigiosoDTO)
        {
            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_decisao_excluir', __METHOD__, $arrObjTipoDecisaoLitigiosoDTO);

                //nao permite excluir tipo de controle se ele ja estiver vinculado a um dispositivo
                $listaIds = "";
                for ($i = 0; $i < count($arrObjTipoDecisaoLitigiosoDTO); $i++) {

                    if ($listaIds == "") {
                        $listaIds = $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso();
                    } else {
                        $listaIds .= "," . $arrObjTipoDecisaoLitigiosoDTO[$i]->getNumIdTipoDecisaoLitigioso();
                    }
                }

                //nao permitir a exclusao de Conduta se a mesma já estiver vinculada a um dispositivo normativo
                $sql  = " SELECT * FROM md_lit_rel_tp_especie_dec WHERE id_md_lit_tipo_decisao IN ( " . $listaIds . ")";
                $sql1 = " SELECT * FROM md_lit_rel_tipo_ctrl_tipo_dec WHERE id_md_lit_tipo_decisao IN ( " . $listaIds . ")";

                $rs  = $this->getObjInfraIBanco()->executarSql($sql);
                $rs1 = $this->getObjInfraIBanco()->executarSql($sql1);
                
                
                $rs = is_array($rs) ? count($rs) : (is_int($rs) ? $rs : null);
                if (($rs != null && $rs > 0) || ($rs1 != null && $rs1 > 0)) {
                    
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao('A exclusão do tipo de decisao não é permitida, pois já existem registros vinculados.');
                    $objInfraException->lancarValidacoes();
                    
                } else if (count($arrObjTipoDecisaoLitigiosoDTO) > 0) {
                    
                    $objTipoDecisaoLitigiosoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjTipoDecisaoLitigiosoDTO); $i++) {
                        $objTipoDecisaoLitigiosoBD->excluir($arrObjTipoDecisaoLitigiosoDTO[$i]);
                    }
                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Tipo de Decisão.', $e);
            }
        }

        /**
         * @access protected
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @param $arrObjTipoDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function desativarControlado($arrObjTipoDecisaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_decisao_desativar', __METHOD__, $arrObjTipoDecisaoLitigiosoDTO);

                $objTipoDecisaoLitigiosoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjTipoDecisaoLitigiosoDTO); $i++) {
                    $objTipoDecisaoLitigiosoBD->desativar($arrObjTipoDecisaoLitigiosoDTO[$i]);
                }

            } catch (Exception $e) {
                throw new InfraException('Erro desativando Tipo de Decisão.', $e);
            }
        }

        /**
         * @access protected
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @param $arrObjTipoDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function reativarControlado($arrObjTipoDecisaoLitigiosoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_decisao_reativar', __METHOD__, $arrObjTipoDecisaoLitigiosoDTO);


                $objTipoDecisaoLitigiosoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjTipoDecisaoLitigiosoDTO); $i++) {
                    $objTipoDecisaoLitigiosoBD->reativar($arrObjTipoDecisaoLitigiosoDTO[$i]);
                }


            } catch (Exception $e) {
                throw new InfraException('Erro reativando Tipos de Processo.', $e);
            }
        }


        /**
         * @access protected
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @param MdLitTipoDecisaoDTO $objTipoDecisaoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function cadastrarControlado(MdLitTipoDecisaoDTO $objTipoDecisaoDTO)
        {
            try {
                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_decisao_cadastrar', __METHOD__, $objTipoDecisaoDTO);

                // Regras de Negocio
                $objInfraException = new InfraException();

                $this->_validarStrNome($objTipoDecisaoDTO, $objInfraException);
                $this->_validarStrDescricao($objTipoDecisaoDTO, $objInfraException);

                $objInfraException->lancarValidacoes();
                $objTipoDecisaoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());
                $objTipoDecisaoDTO->setStrNome(trim($objTipoDecisaoDTO->getStrNome()));
                $objTipoDecisaoDTO->setStrSinAtivo('S');

                $objRetorno = $objTipoDecisaoBD->cadastrar($objTipoDecisaoDTO);

                $this->_salvarRelacionamentos($objRetorno);

                return $objRetorno;
            } catch (Exception $e) {
                throw new InfraException ('Erro cadastrando Espécie Litigiosa.', $e);
            }
        }

        /**
         * @access private
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @return void
         */
        private function _validarStrNome(MdLitTipoDecisaoDTO $objTipoDecisaoDTO, InfraException $objInfraException)
        {

            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (InfraString::isBolVazia($objTipoDecisaoDTO->getStrNome())) {
                $objInfraException->adicionarValidacao('Nome do Tipo de Decisão não informado.');
            }

            $objTipoDecisaoDTO2 = new $objTipoDecisaoDTO();
            $objTipoDecisaoDTO2->setStrNome(trim($objTipoDecisaoDTO->getStrNome()), InfraDTO::$OPER_IGUAL);

            $objTipoDecisaoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());

            // Valida Quantidade de Caracteres
            if (strlen($objTipoDecisaoDTO->getStrNome()) > 50) {
                $objInfraException->adicionarValidacao('Tipo de Decisão possui tamanho superior a 50 caracteres.');
            }

            // Verifica se já esxiste o no banco
            is_numeric($objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso()) ? $objTipoDecisaoDTO2->setNumIdTipoDecisaoLitigioso($objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso(), InfraDTO::$OPER_DIFERENTE) : $objTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso(null);

            $ret = $objTipoDecisaoBD->contar($objTipoDecisaoDTO2);

            if ($ret > 0) {
                $objInfraException->adicionarValidacao('Já existe Tipo de Decisão cadastrado.');
            }
        }

        /**
         * Validate field "Descrição".
         *
         * @access private
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @return void
         */
        private function _validarStrDescricao(MdLitTipoDecisaoDTO $objTipoDecisaoDTO, $objInfraException)
        {
            if (trim($objTipoDecisaoDTO->getStrDescricao()) != '') {
                if (strlen($objTipoDecisaoDTO->getStrDescricao()) > 250) {
                    $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
                }
            }
        }

        private function _salvarRelacionamentos($objTipoDecisaoDTO)
        {
            $arrTipoDecisaoEspecie = $objTipoDecisaoDTO->getArrObjRelEspecieLitigiosoDTO();

            if (count($arrTipoDecisaoEspecie) > 0) {

                foreach ($arrTipoDecisaoEspecie as $objRelacional) {

                    $objRelTipoEspecieDecisaoLitigiosoRN = new MdLitRelTipoEspecieDecisaoRN();
                    $objRelacional->setNumIdTipoDecisaoLitigioso($objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso());
                    $objRelTipoEspecieDecisaoLitigiosoRN->cadastrar($objRelacional);
                }
            }
        }

        /**
         * @access protected
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @param MdLitTipoDecisaoDTO $objTipoDecisaoDTO
         * @return MdLitTipoDecisaoDTO
         * @throws InfraException
         */
        protected function alterarControlado(MdLitTipoDecisaoDTO $objTipoDecisaoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_decisao_alterar', __METHOD__, $objTipoDecisaoDTO);

                // Regras de Negocio
                $objInfraException = new InfraException();


                $this->_validarStrNome($objTipoDecisaoDTO, $objInfraException);
                $this->_validarStrDescricao($objTipoDecisaoDTO, $objInfraException);

                $objInfraException->lancarValidacoes();

                $objTipoDecisaoDTO->setStrNome(trim($objTipoDecisaoDTO->getStrNome()));

                $objTipoDecisaoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());

                $objRelTipoEspecieDecisaoLitigiosoRN  = new MdLitRelTipoEspecieDecisaoRN();
                $objRelTipoEspecieDecisaoLitigiosoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                $objRelTipoEspecieDecisaoLitigiosoDTO->setNumIdTipoDecisaoLitigioso($objTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso());

                $objRelTipoEspecieDecisaoLitigiosoRN->excluirRelacionamentos($objRelTipoEspecieDecisaoLitigiosoDTO);

                $this->_salvarRelacionamentos($objTipoDecisaoDTO);

                $objTipoDecisaoBD->alterar($objTipoDecisaoDTO);

                return $objTipoDecisaoDTO;
                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro alterando Espécie Litigiosa.', $e);
            }
        }

        /**
         * @access   protected
         * @author   Alan Campos <alan.campos@castgroup.com.br>
         * @param $objTipoDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $objTipoDecisaoDTO
         */
        protected function consultarConectado($objTipoDecisaoLitigiosoDTO)
        {
            try {

                // Valida Permissao

                $objTipoDecisaoLitigiosoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());
                $ret                       = $objTipoDecisaoLitigiosoBD->consultar($objTipoDecisaoLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Especie Litigiosa.', $e);
            }
        }

    }
