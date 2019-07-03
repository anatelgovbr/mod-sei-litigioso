<?php
    /**
     * ANATEL
     *
     * 10/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleUsuarioRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        protected function cadastrarControlado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_cadastrar', __METHOD__, $objRelTipoControleLitigiosoUsuarioDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //insere o TipoControleLitigioso, e depois cria os relacionamentos com GESTORES, UNIDADES E TIPOS DE PROCESSOS
                $objRelTipoControleLitigiosoUsuarioBD = new MdLitRelTipoControleUsuarioBD($this->getObjInfraIBanco());
                //$objRelTipoControleLitigiosoUsuarioDTO->setStrSinAtivo('S');
                $ret = $objRelTipoControleLitigiosoUsuarioBD->cadastrar($objRelTipoControleLitigiosoUsuarioDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function alterarControlado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_alterar', __METHOD__, $objRelTipoControleLitigiosoUsuarioDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                if ($objRelTipoControleLitigiosoUsuarioDTO->isSetStrSigla()) {
                    $this->validarStrSigla($objRelTipoControleLitigiosoUsuarioDTO, $objInfraException);
                }

                $objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUsuarioBD = new MdLitRelTipoControleUsuarioBD($this->getObjInfraIBanco());
                $objRelTipoControleLitigiosoUsuarioBD->alterar($objRelTipoControleLitigiosoUsuarioDTO);

            } catch (Exception $e) {
                throw new InfraException('Erro alterando Tipo de Processo Litigioso.', $e);
            }

        }

        protected function excluirControlado($arrObjRelTipoControleLitigiosoUsuarioDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_excluir', __METHOD__, $arrObjRelTipoControleLitigiosoUsuarioDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUsuarioBD = new MdLitRelTipoControleUsuarioBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjRelTipoControleLitigiosoUsuarioDTO); $i++) {
                    $objRelTipoControleLitigiosoUsuarioBD->excluir($arrObjRelTipoControleLitigiosoUsuarioDTO[$i]);
                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo Tipo de Processo Litigioso.', $e);
            }
        }

        protected function consultarConectado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_consultar', __METHOD__, $objRelTipoControleLitigiosoUsuarioDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUsuarioBD = new MdLitRelTipoControleUsuarioBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUsuarioBD->consultar($objRelTipoControleLitigiosoUsuarioDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function listarConectado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar', __METHOD__, $objRelTipoControleLitigiosoUsuarioDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUsuarioBD = new MdLitRelTipoControleUsuarioBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUsuarioBD->listar($objRelTipoControleLitigiosoUsuarioDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro listando Tipo de Processo Litigiosoes.', $e);
            }
        }

        protected function contarConectado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar', __METHOD__, $objRelTipoControleLitigiosoUsuarioDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUsuarioBD = new MdLitRelTipoControleUsuarioBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUsuarioBD->contar($objRelTipoControleLitigiosoUsuarioDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro contando Tipo de Processo Litigiosoes.', $e);
            }
        }

    }
