<?php
    /**
     * ANATEL
     *
     * 29/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelDispositivoNormativoCondutaControleRN extends InfraRN
    {
        public static $TA_ESPECIFICA = 'E';
        public static $TA_PERIODO = 'P';

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
         * @access   protected
         * @author   CAST
         * @param $objRelDispositivoNormativoCondutaControleLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $objelDispositivoNormativoCondutaProcedimentoDTO
         */
        protected function listarConectado($objRelDispositivoNormativoCondutaControleLitigiosoDTO)
        {
            try {
                // Valida Permissao
//			SessaoSEI::getInstance ()->validarAuditarPermissao ('md_lit_dispositivo_normativo_listar', __METHOD__, $objRelDispositivoNormativoCondutaDTO );

                $objRelDispositivoNormativoCondutaControleLitigiosoBD = new MdLitRelDispositivoNormativoCondutaControleBD($this->getObjInfraIBanco());
                $ret                                                  = $objRelDispositivoNormativoCondutaControleLitigiosoBD->listar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Dispositivo Normativo Conduta Procedimento Litigioso.', $e);
            }
        }


        protected function cadastrarControlado(MdLitRelDispositivoNormativoCondutaControleDTO $objRelDispositivoNormativoCondutaControleLitigiosoDTO)
        {
            try {


                //Valida Permissao
//      SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_cadastrar',__METHOD__,$objRelDispositivoNormativoCondutaDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //Cria o relacionamento com Conduta
                $objRelDispositivoNormativoCondutaControleLitigiosoBD = new MdLitRelDispositivoNormativoCondutaControleBD($this->getObjInfraIBanco());
                // $objRelTipoControleLitigiosoTipoProcedimentoDTO->setStrSinAtivo('S');
                $ret = $objRelDispositivoNormativoCondutaControleLitigiosoBD->cadastrar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Dispositivo Normativo Conduta Procedimento Litigioso.', $e);
            }
        }

        protected function alterarControlado(MdLitRelDispositivoNormativoCondutaControleDTO $objRelDispositivoNormativoCondutaControleLitigiosoDTO){
            try {

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //Cria o relacionamento com Conduta
                $objRelDispositivoNormativoCondutaControleLitigiosoBD = new MdLitRelDispositivoNormativoCondutaControleBD($this->getObjInfraIBanco());
                // $objRelTipoControleLitigiosoTipoProcedimentoDTO->setStrSinAtivo('S');
                $ret = $objRelDispositivoNormativoCondutaControleLitigiosoBD->alterar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Dispositivo Normativo Conduta Procedimento Litigioso.', $e);
            }

        }


        protected function excluirControlado($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO)
        {

            try {

                //Valida Permissao
                //SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tipo_controle_excluir',__METHOD__,$arrObjRelTipoControleLitigiosoTipoProcedimentoDTO);

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();


                $objRelDispositivoNormativoCondutaControleLitigiosoBD = new MdLitRelDispositivoNormativoCondutaControleBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO); $i++) {
                    $objRelDispositivoNormativoCondutaControleLitigiosoBD->excluir($arrObjRelDispositivoNormativoCondutaControleLitigiosoDTO[$i]);
                }


                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo  Dispositivo Normativo Conduta Procedimento Litigioso.', $e);
            }
        }


        protected function consultarConectado(MdLitRelDispositivoNormativoCondutaControleDTO $objMdLitRelDispositivoNormativoCondutaControleDTO){
            try {

                //Valida Permissao
//                SessaoSEI::getInstance()->validarPermissao('md_lit_dispositivo_normativo_consultar');

                //Regras de Negocio
                //$objInfraException = new InfraException();

                //$objInfraException->lancarValidacoes();

                $objMdLitRelDispositivoNormativoCondutaControleBD = new MdLitRelDispositivoNormativoCondutaControleBD($this->getObjInfraIBanco());
                $ret = $objMdLitRelDispositivoNormativoCondutaControleBD->consultar($objMdLitRelDispositivoNormativoCondutaControleDTO);

                //Auditoria

                return $ret;
            }catch(Exception $e){
                throw new InfraException('Erro consultando  Dispositivo Normativo Conduta Procedimento Litigioso.',$e);
            }
        }
    }