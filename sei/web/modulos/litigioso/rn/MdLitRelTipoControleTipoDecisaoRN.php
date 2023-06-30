<?php
    /**
     * ANATEL
     *
     * 01/06/2016 - criado por alan.campos@castgroup.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleTipoDecisaoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        public function validarExclusao($arrTipoDecisaoNovos, $tipoControle)
        {

            $objAssociarTipoDecisaoLitigiosoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
            $objAssociarTipoDecisaoLitigiosoRN  = new MdLitRelTipoControleTipoDecisaoRN();
            $objInfraException                  = new InfraException();

            $objAssociarTipoDecisaoLitigiosoDTO->retTodos();
            $objAssociarTipoDecisaoLitigiosoDTO->setNumIdTipoControleLitigioso($tipoControle);
            $arrObjsAssociadosAnt = $this->listar($objAssociarTipoDecisaoLitigiosoDTO);
            $arridsAssociados     = array();

            foreach ($arrObjsAssociadosAnt as $key => $itemAssociadoAnt) {
                array_push($arridsAssociados, $itemAssociadoAnt->getNumIdTipoDecisaoLitigioso());
            }

            $dispExcluidos = array_values(array_diff($arridsAssociados, $arrTipoDecisaoNovos));

            if (count($dispExcluidos) > 0) {
                foreach ($dispExcluidos as $key => $TipoDecisao) {

                    $objAssociarTipoDecisaoLitigiosoDTO2 = new MdLitRelTipoControleTipoDecisaoDTO ();
                    $objAssociarTipoDecisaoLitigiosoDTO2->retNumIdTipoDecisaoLitigioso();
                    $objAssociarTipoDecisaoLitigiosoDTO2->setNumIdTipoDecisaoLitigioso($TipoDecisao);
                    $arrTiposControle = $objAssociarTipoDecisaoLitigiosoRN->listar($objAssociarTipoDecisaoLitigiosoDTO2);

                    if (count($arrTiposControle) == 1) {

                        $objInfraException->adicionarValidacao('Pelo menos um Tipo de Controle Litigioso deve ser associado a este Dispositivo Normativo.');
                    }
                }
            }

            $objInfraException->lancarValidacoes();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        /**
         * Short description of method cadastrarControlado
         *
         * @access   protected
         * @author   Alan Campos <alan.campos@castgroup.com.br>
         * @param $arrObjAssociarTipoDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $objAssociarTipoDecisaoLitigiosoDTO
         */
        protected function cadastrarControlado($arrObjAssociarTipoDecisaoLitigiosoDTO)
        {

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_tipo_decisao_cadastrar', __METHOD__, $arrObjAssociarTipoDecisaoLitigiosoDTO);

            try {
                if(count($arrObjAssociarTipoDecisaoLitigiosoDTO)){
                    $idTipoControle = $arrObjAssociarTipoDecisaoLitigiosoDTO[0]->getNumIdTipoControleLitigioso();


                    $relacionamentoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
                    $relacionamentoDTO->setNumIdTipoControleLitigioso($idTipoControle);
                    $this->excluirRelacionamentos($relacionamentoDTO);

                }

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_tipo_decisao_consultar', __METHOD__, $arrObjAssociarTipoDecisaoLitigiosoDTO);

                if ($arrObjAssociarTipoDecisaoLitigiosoDTO != null && count($arrObjAssociarTipoDecisaoLitigiosoDTO) > 0) {

                    $objTipoDecisaoLitigiosoBD = new MdLitRelTipoControleTipoDecisaoBD($this->getObjInfraIBanco());

                    foreach ($arrObjAssociarTipoDecisaoLitigiosoDTO as $objAssociarTipoDecisaoLitigiosoDTO) {

                        $objTipoDecisaoLitigiosoBD->cadastrar($objAssociarTipoDecisaoLitigiosoDTO);

                    }

                }

            } catch (Exception $e) {
                throw new InfraException('Erro ao cadastrar Associar Tipo de Decisão.', $e);
            }

        }

        protected function excluirRelacionamentosControlado(MdLitRelTipoControleTipoDecisaoDTO $relTipoDecisaoTipoControleDTO)
        {

            try {

                // Valida Permissao
                $relTipoDecisaoTipoControleDTO->retTodos();
                $arrObjMdLitRelTipoControleTipoDecisaoDTO = $this->listar($relTipoDecisaoTipoControleDTO);
                $objMdLitRelTipoControleTipoDecisaoBD = new MdLitRelTipoControleTipoDecisaoBD($this->getObjInfraIBanco());

                if(count($arrObjMdLitRelTipoControleTipoDecisaoDTO) > 0){
                    foreach ($arrObjMdLitRelTipoControleTipoDecisaoDTO as $objMdLitRelTipoControleTipoDecisaoDTO){
                        $objMdLitRelTipoControleTipoDecisaoBD->excluir($objMdLitRelTipoControleTipoDecisaoDTO);
                    }
                }

            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo os Tipos de Decisão.', $e);
            }

        }


        protected function excluirControlado($arrObjMdLitRelTipoControleTipoDecisaoDTO)
        {
            try {
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_tipo_decisao_excluir', __METHOD__, $arrObjMdLitRelTipoControleTipoDecisaoDTO);

                $objMdLitRelTipoControleTipoDecisaoBD = new MdLitRelTipoControleTipoDecisaoBD($this->getObjInfraIBanco());
                for($i = 0; $i < count($arrObjMdLitRelTipoControleTipoDecisaoDTO); $i ++) {
                    $objMdLitRelTipoControleTipoDecisaoBD->excluir($arrObjMdLitRelTipoControleTipoDecisaoDTO[$i]);
                }
            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo.', $e);
            }

        }

        /**
         * Short description of method consultarConectado
         *
         * @access protected
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @param MdLitRelTipoControleTipoDecisaoDTO $objTipoDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function consultarConectado(MdLitRelTipoControleTipoDecisaoDTO $objTipoDecisaoLitigiosoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_associar_dispositivo_normativo_consultar', __METHOD__, $objTipoDecisaoLitigiosoDTO);

                $objTipoDecisaoLitigiosoBD = new MdLitRelTipoControleTipoDecisaoBD($this->getObjInfraIBanco());
                $ret                       = $objTipoDecisaoLitigiosoBD->consultar($objTipoDecisaoLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro consultando Associar Dispositivo Normativo Litigiosa.', $e);
            }

        }

        /**
         * Short description of method listarConectado
         *
         * @access protected
         * @author Alan Campos <alan.campos@castgroup.com.br>
         * @param MdLitRelTipoControleTipoDecisaoDTO $objTipoDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado(MdLitRelTipoControleTipoDecisaoDTO $objTipoDecisaoLitigiosoDTO)
        {

            try {

                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_tipo_decisao_consultar', __METHOD__, $objTipoDecisaoLitigiosoDTO);


                $objTipoDecisaoLitigiosoBD = new MdLitRelTipoControleTipoDecisaoBD($this->getObjInfraIBanco());
                $ret                       = $objTipoDecisaoLitigiosoBD->listar($objTipoDecisaoLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException ('Erro listando Associar Dispositivo Normativo Litigiosos.', $e);
            }
        }

    }
