<?php
    /**
     * ANATEL
     *
     * 23/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelEspecieDecisaoObrigacaoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        public function excluirRelacionamentos($objRelEspecieDecisaoObrigacaoDTO)
        {
            // Valida Permissao
            $objRelEspecieDecisaoObrigacaoBD = new MdLitRelEspecieDecisaoObrigacaoBD($this->getObjInfraIBanco());

            $objRelEspecieDecisaoObrigacaoDTO->retTodos();
            $arrObjRelEspecieDecisaoObrigacaoDTO = array();
            $arrObjRelEspecieDecisaoObrigacaoDTO = $this->listar($objRelEspecieDecisaoObrigacaoDTO);

            if (count($arrObjRelEspecieDecisaoObrigacaoDTO) > 0) {
                $this->excluir($arrObjRelEspecieDecisaoObrigacaoDTO);
            }
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        /**
         * Short description of method cadastrarControlado
         *
         * @access   protected
         * @author   Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $objRelEspecieDecisaoObrigacaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $objEspecieLitigiosoDTO
         */
        protected function cadastrarControlado($objRelEspecieDecisaoObrigacaoLitigiosoDTO)
        {
            try {
                // Valida Permissao

                // Regras de Negocio
                $objInfraException = new InfraException();

                $objInfraException->lancarValidacoes();

                $objRelEspecieDecisaoObrigacaoBD = new MdLitRelEspecieDecisaoObrigacaoBD($this->getObjInfraIBanco());

                $objRetorno = $objRelEspecieDecisaoObrigacaoBD->cadastrar($objRelEspecieDecisaoObrigacaoLitigiosoDTO);


                return $objRetorno;
            } catch (Exception $e) {
                throw new InfraException ('Erro cadastrando Espécie Litigiosa.', $e);
            }
        }

        /**
         * Short description of method listarConectado
         *
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $objRelEspecieDecisaoObrigacaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado($objRelEspecieDecisaoObrigacaoLitigiosoDTO)
        {

            try {
                // Regras de Negocio
                $objRelEspecieDecisaoObrigacaoBD = new MdLitRelEspecieDecisaoObrigacaoBD($this->getObjInfraIBanco());
                $ret                             = $objRelEspecieDecisaoObrigacaoBD->listar($objRelEspecieDecisaoObrigacaoLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException ('Erro listando Relacionamento de Espécie de Decisão e Obrigação Litigiosa.', $e);
            }
        }

        /**
         * Short description of method excluirControlado
         *
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
         * @param $arrObjRelEspecieDecisaoObrigacaoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function excluirControlado($arrObjRelEspecieDecisaoObrigacaoDTO)
        {
            try {

                // Valida Permissao

                $objInfraException = new InfraException();

                $objRelEspecieDecisaoObrigacaoBD = new MdLitRelEspecieDecisaoObrigacaoBD($this->getObjInfraIBanco());

                if (count($arrObjRelEspecieDecisaoObrigacaoDTO) > 0) {
                    for ($i = 0; $i < count($arrObjRelEspecieDecisaoObrigacaoDTO); $i++) {
                        $objRelEspecieDecisaoObrigacaoBD->excluir($arrObjRelEspecieDecisaoObrigacaoDTO[$i]);
                    }
                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Relacionamento de Espécie de Decisão e Obrigação Litigiosa.', $e);
            }
        }

    }
