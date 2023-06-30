<?php
    /**
     * ANATEL
     *
     * 10/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleUnidadeRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        protected function cadastrarControlado(MdLitRelTipoControleUnidadeDTO $objRelTipoControleLitigiosoUnidadeDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_cadastrar', __METHOD__, $objRelTipoControleLitigiosoUnidadeDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //insere o TipoControleLitigioso, e depois cria os relacionamentos com GESTORES, UNIDADES E TIPOS DE PROCESSOS
                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                //$objRelTipoControleLitigiosoUnidadeDTO->setStrSinAtivo('S');
                $ret = $objRelTipoControleLitigiosoUnidadeBD->cadastrar($objRelTipoControleLitigiosoUnidadeDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function alterarControlado(MdLitRelTipoControleUnidadeDTO $objRelTipoControleLitigiosoUnidadeDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_alterar', __METHOD__, $objRelTipoControleLitigiosoUnidadeDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();

                if ($objRelTipoControleLitigiosoUnidadeDTO->isSetStrSigla()) {
                    $this->validarStrSigla($objRelTipoControleLitigiosoUnidadeDTO, $objInfraException);
                }

                $objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                $objRelTipoControleLitigiosoUnidadeBD->alterar($objRelTipoControleLitigiosoUnidadeDTO);

            } catch (Exception $e) {
                throw new InfraException('Erro alterando Tipo de Processo Litigioso.', $e);
            }

        }

        protected function excluirControlado($arrObjRelTipoControleLitigiosoUnidadeDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_excluir', __METHOD__, $arrObjRelTipoControleLitigiosoUnidadeDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjRelTipoControleLitigiosoUnidadeDTO); $i++) {
                    $objRelTipoControleLitigiosoUnidadeBD->excluir($arrObjRelTipoControleLitigiosoUnidadeDTO[$i]);
                }

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo Tipo de Processo Litigioso.', $e);
            }
        }

        protected function consultarConectado(MdLitRelTipoControleUnidadeDTO $objRelTipoControleLitigiosoUnidadeDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_consultar', __METHOD__, $objRelTipoControleLitigiosoUnidadeDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUnidadeBD->consultar($objRelTipoControleLitigiosoUnidadeDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Tipo de Processo Litigioso.', $e);
            }
        }

        protected function listarConectado(MdLitRelTipoControleUnidadeDTO $objRelTipoControleLitigiosoUnidadeDTO)
        {
            try {

                //Valida Permissao
                //SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar',__METHOD__,$objRelTipoControleLitigiosoUnidadeDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUnidadeBD->listar($objRelTipoControleLitigiosoUnidadeDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro listando Tipo de Processo Litigiosoes.', $e);
            }
        }

        protected function contarConectado(MdLitRelTipoControleUnidadeDTO $objRelTipoControleLitigiosoUnidadeDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_listar', __METHOD__, $objRelTipoControleLitigiosoUnidadeDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelTipoControleLitigiosoUnidadeBD = new MdLitRelTipoControleUnidadeBD($this->getObjInfraIBanco());
                $ret                                  = $objRelTipoControleLitigiosoUnidadeBD->contar($objRelTipoControleLitigiosoUnidadeDTO);

                //Auditoria

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro contando Tipo de Processo Litigiosoes.', $e);
            }
        }

    }
