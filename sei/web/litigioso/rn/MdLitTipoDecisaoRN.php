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

                $this->validarUtilizacaoDecisao($arrObjTipoDecisaoLitigiosoDTO);
                $this->validarUtilizacaoTpControle($arrObjTipoDecisaoLitigiosoDTO);
                $this->removerRelTpEspecies($arrObjTipoDecisaoLitigiosoDTO);
                $this->removerTpDecisao($arrObjTipoDecisaoLitigiosoDTO);

            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Tipo de Decisão.', $e);
            }
        }

        private function validarUtilizacaoTpControle($arrObjTipoDecisaoLitigiosoDTO)
        {
            foreach ($arrObjTipoDecisaoLitigiosoDTO as $objTipoDecisaoLitigiosoDTO) {
                $MdLitRelTipoControleTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                $MdLitRelTipoControleTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($objTipoDecisaoLitigiosoDTO->getNumIdTipoDecisaoLitigioso());
                $MdLitRelTipoControleTipoDecisaoDTO->retTodos();
                $arrMdLitRelTipoControleTipoDecisaoDTO = (new MdLitRelTipoControleTipoDecisaoRN)->listar($MdLitRelTipoControleTipoDecisaoDTO);
                if(!empty($arrMdLitRelTipoControleTipoDecisaoDTO)){

                    //Buscar Tipos de Controles vinculados ao Tipo de Decisao
                    $arrMdLitTipoControleDTO = $this->bucarTiposControlePorTipoDecisaoVinculado($arrMdLitRelTipoControleTipoDecisaoDTO);

                    //Coletar nomes dos Tipos de Controle
                    $nomesTipoControle = $this->organizarNomesTpControle($arrMdLitTipoControleDTO);

                    //Organizar mensagem de retorno
                    $msgRetorno = $this->organizarMensagemRetorno($objTipoDecisaoLitigiosoDTO, $arrMdLitTipoControleDTO, $nomesTipoControle);

                    //Lancar Validações
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao($msgRetorno);
                    $objInfraException->lancarValidacoes();
                }
            }

        }

        private function validarUtilizacaoDecisao($arrObjTipoDecisaoLitigiosoDTO)
        {
            foreach ($arrObjTipoDecisaoLitigiosoDTO as $objTipoDecisaoLitigiosoDTO) {
                $MdLitDecisaoDTO = new MdLitDecisaoDTO();
                $MdLitDecisaoDTO->setNumIdMdLitTipoDecisao($objTipoDecisaoLitigiosoDTO->getNumIdTipoDecisaoLitigioso());
                $MdLitDecisaoDTO->retNumIdMdLitDecisao();
                $MdLitDecisaoDTO->setNumMaxRegistrosRetorno(1);
                $MdLitDecisaoDTO = (new MdLitDecisaoRN)->consultar($MdLitDecisaoDTO);

                if($MdLitDecisaoDTO){
                    $nomeTipoDecisao = $this->recuperarNomeTipoDecisao($objTipoDecisaoLitigiosoDTO->getNumIdTipoDecisaoLitigioso());
                    $msgRetorno = 'A exclusão do Tipo de Decisao ' . $nomeTipoDecisao . ' não é permitida, pois está sendo utilizado em alguma decisão.';
                    
                    //Lancar Validações
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao($msgRetorno);
                    $objInfraException->lancarValidacoes();
                }
            }
        }

        private function bucarTiposControlePorTipoDecisaoVinculado($arrMdLitRelTipoControleTipoDecisaoDTO)
        {
            $arrIdsTpControle = InfraArray::converterArrInfraDTO($arrMdLitRelTipoControleTipoDecisaoDTO, 'IdTipoControleLitigioso');
            $MdLitTipoControleDTO = new MdLitTipoControleDTO();
            $MdLitTipoControleDTO->setNumIdTipoControleLitigioso($arrIdsTpControle , InfraDTO::$OPER_IN);
            $MdLitTipoControleDTO->retStrSigla();
            return (new MdLitTipoControleRN())->listar($MdLitTipoControleDTO);
        }

        private function organizarNomesTpControle($arrMdLitTipoControleDTO)
        {
            $nomesTipoControle = '';
            foreach($arrMdLitTipoControleDTO as $MdLitTipoControleDTO){
                $nomesTipoControle .= ' - ' . $MdLitTipoControleDTO->getStrSigla() . '\n';
            }
            return $nomesTipoControle;
        }

        private function recuperarNomeTipoDecisao($IdTipoDecisaoLitigioso)
        {
            $MdLitTipoDecisaoDTO = new MdLitTipoDecisaoDTO();
            $MdLitTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($IdTipoDecisaoLitigioso);
            $MdLitTipoDecisaoDTO->retStrNome();
            $MdLitTipoDecisaoDTO = (new MdLitTipoDecisaoRN())->consultar($MdLitTipoDecisaoDTO);
            return $MdLitTipoDecisaoDTO->getStrNome();
        }

        private function organizarMensagemRetorno($objTipoDecisaoLitigiosoDTO,$arrMdLitTipoControleDTO, $nomesTipoControle)
        {
            $nomeTipoDecisao = $this->recuperarNomeTipoDecisao($objTipoDecisaoLitigiosoDTO->getNumIdTipoDecisaoLitigioso());
            $msgRetorno = 'A exclusão do Tipo de Decisao ' . $nomeTipoDecisao . ' não é permitida, pois está vinculado ao seguinte Tipo de Controle:\n\n';
            if (count($arrMdLitTipoControleDTO) > 1) $msgRetorno = 'A exclusão do Tipo de Decisao ' . $nomeTipoDecisao . ' não é permitida, pois está vinculado aos seguintes Tipos de Controle:\n\n';
            $msgRetorno .= $nomesTipoControle;
            return $msgRetorno;
        }

        private function removerRelTpEspecies($arrObjTipoDecisaoLitigiosoDTO)
        {
            $arrIdMdLitRelTipoEspecieDecisao = InfraArray::converterArrInfraDTO($arrObjTipoDecisaoLitigiosoDTO, 'IdTipoDecisaoLitigioso');
            $MdLitRelTipoEspecieDecisaoRN = new MdLitRelTipoEspecieDecisaoRN();
            $MdLitRelTipoEspecieDecisaoDTO = new MdLitRelTipoEspecieDecisaoDTO();
            $MdLitRelTipoEspecieDecisaoDTO->setNumIdTipoDecisaoLitigioso($arrIdMdLitRelTipoEspecieDecisao, InfraDTO::$OPER_IN);
            $MdLitRelTipoEspecieDecisaoDTO->retTodos();
            $arrMdLitRelTipoEspecieDecisaoDTO = $MdLitRelTipoEspecieDecisaoRN->listar($MdLitRelTipoEspecieDecisaoDTO);
            $MdLitRelTipoEspecieDecisaoRN->excluir($arrMdLitRelTipoEspecieDecisaoDTO);
        }

        private function removerTpDecisao($arrObjTipoDecisaoLitigiosoDTO)
        {
            $objTipoDecisaoLitigiosoBD = new MdLitTipoDecisaoBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjTipoDecisaoLitigiosoDTO); $i++) {
                $objTipoDecisaoLitigiosoBD->excluir($arrObjTipoDecisaoLitigiosoDTO[$i]);
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
                throw new InfraException ('Erro cadastrando Tipo De Decisão.', $e);
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
                throw new InfraException('Erro consultando Espécie Litigiosa.', $e);
            }
        }

    }
