<?
    /**
     * ANATEL
     *
     * 29/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelDispositivoNormativoTipoControleRN extends InfraRN
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
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $objRelDispositivoNormativoTipoControleDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado($objRelDispositivoNormativoTipoControleDTO)
        {
            try {
                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_listar', __METHOD__, $objRelDispositivoNormativoTipoControleDTO);

                $objRelDispositivoNormativoTipoControleBD = new MdLitRelDispositivoNormativoTipoControleBD($this->getObjInfraIBanco());
                $ret                                      = $objRelDispositivoNormativoTipoControleBD->listar($objRelDispositivoNormativoTipoControleDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Dispositivo Normativo Litigioso.', $e);
            }
        }


        protected function cadastrarControlado(MdLitRelDispositivoNormativoTipoControleDTO $objRelDispositivoNormativoTipoControleDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_cadastrar', __METHOD__, $objRelDispositivoNormativoTipoControleDTO);

                //Regras de Negocio
                $objInfraException = new InfraException();
                $objInfraException->lancarValidacoes();

                //Cria o relacionamento com TipoControle
                $objRelDispositivoNormativoTipoControleBD = new MdLitRelDispositivoNormativoTipoControleBD($this->getObjInfraIBanco());
                $ret                                      = $objRelDispositivoNormativoTipoControleBD->cadastrar($objRelDispositivoNormativoTipoControleDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando Dispositivo Normativo Litigioso.', $e);
            }
        }


    }