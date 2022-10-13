<?php
    /**
     * ANATEL
     *
     * 24/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoEspecieDecisaoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Short description of method excluirRelacionamentos
         *
         * @access   protected
         * @author   Alan Campos <alan.campos@castgroup.com.br>
         * @param $objRelEspecieDecisaoLitigiosoDTO
         * @return mixed
         * @internal param $objEspecieLitigiosoDTO
         */

        public function excluirRelacionamentos($objRelEspecieDecisaoLitigiosoDTO)
        {
            // Valida Permissao
            $objRelEspecieDecisaoLitigiosoBD = new MdLitRelTipoEspecieDecisaoBD($this->getObjInfraIBanco());

            $objRelEspecieDecisaoLitigiosoDTO->retTodos();
            $arrRelEspecieDecisaoLitigiosoDTO = array();
            $arrRelEspecieDecisaoLitigiosoDTO = $this->listar($objRelEspecieDecisaoLitigiosoDTO);


            if (count($arrRelEspecieDecisaoLitigiosoDTO) > 0) {
                $this->excluir($arrRelEspecieDecisaoLitigiosoDTO);
            }
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        /**
         * Short description of method listarConectado
         *
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $objRelTipoEspecieDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado($objRelTipoEspecieDecisaoLitigiosoDTO)
        {

            try {

                // Regras de Negocio
                $objRelTipoEspecieDecisaoLitigiosoBD = new MdLitRelTipoEspecieDecisaoBD($this->getObjInfraIBanco());
                $ret                                 = $objRelTipoEspecieDecisaoLitigiosoBD->listar($objRelTipoEspecieDecisaoLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException ('Erro listando Relacionamento de Espécie de Decisão e Tipo de Espécie Litigiosa.', $e);
            }
        }

        /**
         * Short description of method cadastrarControlado
         *
         * @access   protected
         * @author   Alan Campos <alan.campos@castgroup.com.br>
         * @param $objRelTipoEspecieDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $objEspecieLitigiosoDTO
         */
        protected function cadastrarControlado($objRelTipoEspecieDecisaoLitigiosoDTO)
        {
            try {

                // Regras de Negocio
                $objInfraException = new InfraException();

                $objInfraException->lancarValidacoes();

                $objRelEspecieDecisaoLitigiosoBD = new MdLitRelTipoEspecieDecisaoBD($this->getObjInfraIBanco());

                $objRetorno = $objRelEspecieDecisaoLitigiosoBD->cadastrar($objRelTipoEspecieDecisaoLitigiosoDTO);


                return $objRetorno;
            } catch (Exception $e) {
                throw new InfraException ('Erro cadastrando Espécie de Decisão.', $e);
            }
        }

        /**
         * Short description of method excluirControlado
         *
         * @access   protected
         * @author   Alan Campos <alan.campos@castgroup.com.br>
         * @param $arrObjRelEspecieDecisaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $RelEspecieDecisaoLitigiosoDTO
         */
        protected function excluirControlado($arrObjRelEspecieDecisaoLitigiosoDTO)
        {
            try {

                // Valida Permissao
                $objInfraException = new InfraException();

                $objRelEspecieDecisaoLitigiosoBD = new MdLitRelTipoEspecieDecisaoBD($this->getObjInfraIBanco());

                if (count($arrObjRelEspecieDecisaoLitigiosoDTO) > 0) {
                    for ($i = 0; $i < count($arrObjRelEspecieDecisaoLitigiosoDTO); $i++) {
                        $objRelEspecieDecisaoLitigiosoBD->excluir($arrObjRelEspecieDecisaoLitigiosoDTO[$i]);
                    }
                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Relacionamento de Espécie de Decisão e Obrigação Litigiosa.', $e);
            }
        }


    }
