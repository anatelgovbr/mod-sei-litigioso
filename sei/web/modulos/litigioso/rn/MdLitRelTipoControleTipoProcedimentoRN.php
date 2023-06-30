<?php
    /**
     * ANATEL
     *
     * 10/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleTipoProcedimentoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        protected function cadastrarControlado(MdLitRelTipoControleTipoProcedimentoDTO $objRelTipoControleLitigiosoTipoProcedimentoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_cadastrar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //insere o TipoControleLitigioso, e depois cria os relacionamentos com GESTORES, UNIDADES E TIPOS DE PROCESSOS
                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                // $objRelTipoControleLitigiosoTipoProcedimentoDTO->setStrSinAtivo('S');
                $ret = $objRelTipoControleLitigiosoUnidadeBD->cadastrar($objRelTipoControleLitigiosoTipoProcedimentoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function alterarControlado(MdLitRelTipoControleTipoProcedimentoDTO $objRelTipoControleLitigiosoTipoProcedimentoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_alterar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                if ($objRelTipoControleLitigiosoTipoProcedimentoDTO->isSetStrSigla()) {
                    $this->validarStrSigla($objRelTipoControleLitigiosoTipoProcedimentoDTO, $objInfraException);
                }

                $objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                $objRelTipoControleLitigiosoUnidadeBD->alterar($objRelTipoControleLitigiosoTipoProcedimentoDTO);

            } catch (Exception $e) {
                throw new InfraException('Erro alterando Tipo de Processo Litigioso.', $e);
            }

        }

        protected function excluirControlado($arrObjRelTipoControleLitigiosoTipoProcedimentoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_excluir', __METHOD__, $arrObjRelTipoControleLitigiosoTipoProcedimentoDTO);

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjRelTipoControleLitigiosoTipoProcedimentoDTO); $i++) {
                    $objRelTipoControleLitigiosoUnidadeBD->excluir($arrObjRelTipoControleLitigiosoTipoProcedimentoDTO[$i]);
                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo Tipo de Processo Litigioso.', $e);
            }
        }

        protected function consultarConectado(MdLitRelTipoControleTipoProcedimentoDTO $objRelTipoControleLitigiosoTipoProcedimentoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_consultar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUnidadeBD->consultar($objRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function listarConectado(MdLitRelTipoControleTipoProcedimentoDTO $objRelTipoControleLitigiosoTipoProcedimentoDTO)
        {
            try {

                //Valida Permissao
                //SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar',__METHOD__,$objRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUnidadeBD->listar($objRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro listando Tipo de Processo Litigiosoes.', $e);
            }
        }

        protected function contarConectado(MdLitRelTipoControleTipoProcedimentoDTO $objRelTipoControleLitigiosoTipoProcedimentoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUnidadeBD->contar($objRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro contando Tipo de Processo Litigiosoes.', $e);
            }
        }

    }
