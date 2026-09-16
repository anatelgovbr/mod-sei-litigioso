<?php
    /**
     * ANATEL
     *
     * 26/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleTipoProcedimentoSobrestadoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        protected function cadastrarControlado(MdLitRelTipoControleTipoProcedimentoSobrestadoDTO $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_cadastrar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //insere o TipoControleLitigioso, e depois cria os relacionamentos com GESTORES, UNIDADES E TIPOS DE PROCESSOS
                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD = new MdLitRelTipoControleTipoProcedimentoSobrestadoBD($this->getObjInfraIBanco());
                // $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setStrSinAtivo('S');
                $ret = $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD->cadastrar($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function alterarControlado(MdLitRelTipoControleTipoProcedimentoSobrestadoDTO $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_alterar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                if ($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->isSetStrSigla()) {
                    $this->validarStrSigla($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO, $objInfraException);
                }

                $objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD = new MdLitRelTipoControleTipoProcedimentoSobrestadoBD($this->getObjInfraIBanco());
                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD->alterar($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

            } catch (Exception $e) {
                throw new InfraException('Erro alterando Tipo de Processo Litigioso.', $e);
            }

        }

        protected function excluirControlado($arrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_excluir', __METHOD__, $arrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD = new MdLitRelTipoControleTipoProcedimentoSobrestadoBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO); $i++) {
                    $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD->excluir($arrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO[$i]);
                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo Tipo de Processo Litigioso.', $e);
            }
        }

        protected function consultarConectado(MdLitRelTipoControleTipoProcedimentoSobrestadoDTO $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_consultar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD = new MdLitRelTipoControleTipoProcedimentoSobrestadoBD($this->getObjInfraIBanco());
                $ret                                                     = $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD->consultar($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function listarConectado(MdLitRelTipoControleTipoProcedimentoSobrestadoDTO $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD = new MdLitRelTipoControleTipoProcedimentoSobrestadoBD($this->getObjInfraIBanco());
                $ret                                                     = $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD->listar($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro listando Tipo de Processo Litigiosoes.', $e);
            }
        }

        protected function contarConectado(MdLitRelTipoControleTipoProcedimentoSobrestadoDTO $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar', __METHOD__, $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD = new MdLitRelTipoControleTipoProcedimentoSobrestadoBD($this->getObjInfraIBanco());
                $ret                                                     = $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoBD->contar($objRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro contando Tipo de Processo Litigiosoes.', $e);
            }
        }

    }
