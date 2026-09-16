<?php
    /**
     * ANATEL
     *
     * 29/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelDispositivoNormativoCondutaRN extends InfraRN
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
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $objRelDispositivoNormativoCondutaDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado($objRelDispositivoNormativoCondutaDTO)
        {
            try {
                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_listar', __METHOD__, $objRelDispositivoNormativoCondutaDTO);

                $objRelDispositivoNormativoCondutaBD = new MdLitRelDispositivoNormativoCondutaBD($this->getObjInfraIBanco());
                $ret                                 = $objRelDispositivoNormativoCondutaBD->listar($objRelDispositivoNormativoCondutaDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Dispositivo Normativo Litigioso.', $e);
            }
        }


        protected function cadastrarControlado(MdLitRelDispositivoNormativoCondutaDTO $objRelDispositivoNormativoCondutaDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_cadastrar', __METHOD__, $objRelDispositivoNormativoCondutaDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //Cria o relacionamento com Conduta
                $objRelDispositivoNormativoCondutaBD = new MdLitRelDispositivoNormativoCondutaBD($this->getObjInfraIBanco());
                $ret                                 = $objRelDispositivoNormativoCondutaBD->cadastrar($objRelDispositivoNormativoCondutaDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Dispositivo Normativo Litigioso.', $e);
            }
        }

    }