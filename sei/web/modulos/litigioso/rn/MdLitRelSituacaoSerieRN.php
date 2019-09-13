<?php
    /**
     * ANATEL
     *
     * 17/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelSituacaoSerieRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        protected function cadastrarControlado(MdLitRelSituacaoSerieDTO $objDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_cadastrar', __METHOD__, $objDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //insere o SituacaoLitigioso, e depois cria os relacionamentos com GESTORES, UNIDADES E TIPOS DE PROCESSOS
                $objRelSituacaoLitigiosoSerieBD = new MdLitRelSituacaoSerieBD($this->getObjInfraIBanco());
                //$objRelSituacaoLitigiosoSerieDTO->setStrSinAtivo('S');
                $ret = $objRelSituacaoLitigiosoSerieBD->cadastrar($objDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando.', $e);
            }
        }

        protected function alterarControlado(RelSituacaoLitigiosoUsuarioDTO $objRelSituacaoLitigiosoUsuarioDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_alterar', __METHOD__, $objRelSituacaoLitigiosoUsuarioDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //insere o SituacaoLitigioso, e depois cria os relacionamentos com SERIE (Tipo de documento)
                $objRelSituacaoLitigiosoSerieBD = new MdLitRelSituacaoSerieBD($this->getObjInfraIBanco());
                //$objRelSituacaoLitigiosoSerieDTO->setStrSinAtivo('S');
                $ret = $objRelSituacaoLitigiosoSerieBD->alterar($objRelSituacaoLitigiosoUsuarioDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro alterando.', $e);
            }

        }

        protected function excluirControlado($arrObjRelSituacaoLitigiosoSerieDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_excluir', __METHOD__, $arrObjRelSituacaoLitigiosoSerieDTO);
                $objRelSituacaoLitigiosoSerieBD = new MdLitRelSituacaoSerieBD($this->getObjInfraIBanco());

                if (is_array($arrObjRelSituacaoLitigiosoSerieDTO) && count($arrObjRelSituacaoLitigiosoSerieDTO) > 0) {

                    for ($i = 0; $i < count($arrObjRelSituacaoLitigiosoSerieDTO); $i++) {
                        $objRelSituacaoLitigiosoSerieBD->excluir($arrObjRelSituacaoLitigiosoSerieDTO[$i]);
                    }

                }

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo.', $e);
            }
        }

        protected function consultarConectado(MdLitRelSituacaoSerieDTO $objDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_consultar', __METHOD__, $objDTO);
                $objRelSituacaoLitigiosoSerieBD = new MdLitRelSituacaoSerieBD($this->getObjInfraIBanco());
                $ret                            = $objRelSituacaoLitigiosoSerieBD->consultar($objDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro consultando.', $e);
            }
        }

        protected function listarConectado(MdLitRelSituacaoSerieDTO $objRelSituacaoLitigiosoSerieDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_listar', __METHOD__, $objRelSituacaoLitigiosoSerieDTO);
                $objRelSituacaoLitigiosoSerieBD = new MdLitRelSituacaoSerieBD($this->getObjInfraIBanco());
                $ret                            = $objRelSituacaoLitigiosoSerieBD->listar($objRelSituacaoLitigiosoSerieDTO);

                return $ret;

            } catch (Exception $e) {

                //print_r($e); die();
                throw new InfraException('Erro listando.', $e);
            }
        }

        protected function contarConectado(MdLitRelSituacaoSerieDTO $objRelSituacaoLitigiosoSerieDTO)
        {

            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_listar', __METHOD__, $objRelSituacaoLitigiosoSerieDTO);
                $objRelSituacaoLitigiosoSerieBD = new MdLitRelSituacaoSerieBD($this->getObjInfraIBanco());
                $ret                            = $objRelSituacaoLitigiosoSerieBD->contar($objRelSituacaoLitigiosoSerieDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro contando.', $e);
            }
        }

        protected function desativarControlado($objDTO)
        {

        }

        protected function reativarControlado($objDTO)
        {

        }


    }
