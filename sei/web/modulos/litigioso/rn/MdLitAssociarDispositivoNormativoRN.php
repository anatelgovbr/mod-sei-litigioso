<?php
    /**
     * ANATEL
     *
     * 16/03/2016 - criado por felipe.rodrigues@cast.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitAssociarDispositivoNormativoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        public function validarExclusao($arrDispositivosNormativosNovos, $tipoControle)
        {

            $objAssociarDispositivoNormativoLitigiosoDTO = new MdLitAssociarDispositivoNormativoDTO();
            $objAssociarDispositivoNormativoLitigiosoRN  = new MdLitAssociarDispositivoNormativoRN();
            $objInfraException                           = new InfraException();

            $objAssociarDispositivoNormativoLitigiosoDTO->retTodos();
            $objAssociarDispositivoNormativoLitigiosoDTO->setNumIdTipoControleLitigioso($tipoControle);
            $arrObjsAssociadosAnt = $this->listar($objAssociarDispositivoNormativoLitigiosoDTO);
            $arridsAssociados     = array();

            foreach ($arrObjsAssociadosAnt as $key => $itemAssociadoAnt) {
                array_push($arridsAssociados, $itemAssociadoAnt->getNumIdDispositivoNormativoLitigioso());
            }

            $dispExcluidos = array_values(array_diff($arridsAssociados, $arrDispositivosNormativosNovos));

            if (count($dispExcluidos) > 0) {
                foreach ($dispExcluidos as $key => $dispositivoNormativo) {

                    $objAssociarDispositivoNormativoLitigiosoDTO2 = new MdLitAssociarDispositivoNormativoDTO ();
                    $objAssociarDispositivoNormativoLitigiosoDTO2->retNumIdDispositivoNormativoLitigioso();
                    $objAssociarDispositivoNormativoLitigiosoDTO2->setNumIdDispositivoNormativoLitigioso($dispositivoNormativo);
                    $arrTiposControle = $objAssociarDispositivoNormativoLitigiosoRN->listar($objAssociarDispositivoNormativoLitigiosoDTO2);

                    if (!$arrTiposControle) {
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
         * @access   protected
         * @author   Felipe Rodrigues <felipe.rodrigues@cast.com.br>
         * @param $arrObjAssociarDispositivoNormativoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $objAssociarDispositivoNormativoLitigiosoDTO
         */
        protected function cadastrarControlado($arrObjAssociarDispositivoNormativoLitigiosoDTO)
        {

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_associar_dispositivo_normativo_cadastrar', __METHOD__, $arrObjAssociarDispositivoNormativoLitigiosoDTO);

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_associar_dispositivo_normativo_consultar', __METHOD__, $arrObjAssociarDispositivoNormativoLitigiosoDTO);

                if ($arrObjAssociarDispositivoNormativoLitigiosoDTO != null && count($arrObjAssociarDispositivoNormativoLitigiosoDTO) > 0) {

                    $objDispositivoNormativoLitigiosoBD = new MdLitAssociarDispositivoNormativoBD($this->getObjInfraIBanco());

                    foreach ($arrObjAssociarDispositivoNormativoLitigiosoDTO as $objAssociarDispositivoNormativoLitigiosoDTO) {

                        $objDispositivoNormativoLitigiosoBD->cadastrar($objAssociarDispositivoNormativoLitigiosoDTO);

                    }

                }

            } catch (Exception $e) {
                throw new InfraException('Erro ao cadastrar Associar Dispositivo Normativo Litigiosa.', $e);
            }

        }

        protected function excluirRelacionamentosControlado(MdLitRelDispositivoNormativoTipoControleDTO $relDispositivoNormativoTipoControleDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_associar_dispositivo_normativo_cadastrar', __METHOD__, $relDispositivoNormativoTipoControleDTO);

                $relDispositivoNormativoTipoControleConsultarRN = new MdLitRelDispositivoNormativoTipoControleRN();
                $relDispositivoNormativoTipoControleDTO->retTodos();

                $arrRelDispositivoNormativoTipoControleDTO = $relDispositivoNormativoTipoControleConsultarRN->listar($relDispositivoNormativoTipoControleDTO);

                $objMdLitRelDispositivoNormativoTipoControleBD = new MdLitRelDispositivoNormativoTipoControleBD($this->getObjInfraIBanco());
                foreach($arrRelDispositivoNormativoTipoControleDTO as $objRelDispositivoNormativo){
                    $objMdLitRelDispositivoNormativoTipoControleBD->excluir($objRelDispositivoNormativo);
                }

            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo MdLitRelDispositivoNormativoTipoControleDTO.', $e);
            }

        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitAssociarDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function consultarConectado(MdLitAssociarDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_associar_dispositivo_normativo_consultar', __METHOD__, $objDispositivoNormativoLitigiosoDTO);

                $objDispositivoNormativoLitigiosoBD = new MdLitAssociarDispositivoNormativoBD($this->getObjInfraIBanco());
                $ret                                = $objDispositivoNormativoLitigiosoBD->consultar($objDispositivoNormativoLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro consultando Associar Dispositivo Normativo Litigiosa.', $e);
            }

        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitAssociarDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado(MdLitAssociarDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO)
        {

            try {

                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_associar_dispositivo_normativo_consultar', __METHOD__, $objDispositivoNormativoLitigiosoDTO);


                $objDispositivoNormativoLitigiosoBD = new MdLitAssociarDispositivoNormativoBD($this->getObjInfraIBanco());
                $ret                                = $objDispositivoNormativoLitigiosoBD->listar($objDispositivoNormativoLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException ('Erro listando Associar Dispositivo Normativo Litigiosos.', $e);
            }
        }

    }
