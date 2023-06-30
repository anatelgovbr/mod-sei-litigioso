<?php
    /**
     * ANATEL
     *
     * 29/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelProtocoloProtocoloRN extends InfraRN
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
         * Short description of method listarConectado
         *
         * @access protected
         * @author CAST
         * @param MdLitRelProtocoloProtocoloDTO $objRelProtocoloProtocoloLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado(MdLitRelProtocoloProtocoloDTO $objRelProtocoloProtocoloLitigiosoDTO)
        {
            try {
                // Valida Permissao
//			SessaoSEI::getInstance ()->validarAuditarPermissao ('md_lit_dispositivo_normativo_listar', __METHOD__, $objRelDispositivoNormativoCondutaDTO );

                $objRelProtocoloProtocoloLitigiosoBD = new MdLitRelProtocoloProtocoloBD($this->getObjInfraIBanco());
                $ret                                 = $objRelProtocoloProtocoloLitigiosoBD->listar($objRelProtocoloProtocoloLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Sobrestamento de Controle Ligioso.', $e);
            }
        }


        protected function cadastrarControlado(MdLitRelProtocoloProtocoloDTO $objRelProtocoloProtocoloLitigiosoDTO)
        {
            try {

                //Valida Permissao
//      SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_cadastrar',__METHOD__,$objRelDispositivoNormativoCondutaDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //Cria o relacionamento com Conduta
                $objRelProtocoloProtocoloLitigiosoBD = new MdLitRelProtocoloProtocoloBD($this->getObjInfraIBanco());
                // $objRelTipoControleLitigiosoTipoProcedimentoDTO->setStrSinAtivo('S');
                $ret = $objRelProtocoloProtocoloLitigiosoBD->cadastrar($objRelProtocoloProtocoloLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Sobrestamento de Controle Ligioso.', $e);
            }
        }

        protected function excluirControlado($arrObjRelProtocoloProtocoloLitigiosoDTO)
        {

            try {

                //Valida Permissao
                //SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_excluir',__METHOD__,$arrObjRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objRelProtocoloProtocoloLitigiosoBD = new MdLitRelProtocoloProtocoloBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjRelProtocoloProtocoloLitigiosoDTO); $i++) {
                    $objRelProtocoloProtocoloLitigiosoBD->excluir($arrObjRelProtocoloProtocoloLitigiosoDTO[$i]);
                }


                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo Sobrestamento de Controle Ligioso.', $e);
            }
        }


    }