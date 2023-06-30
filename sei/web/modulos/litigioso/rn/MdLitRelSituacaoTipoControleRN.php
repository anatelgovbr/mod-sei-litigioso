<?
    /**
     * ANATEL
     *
     * 17/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelSituacaoTipoControleRN extends InfraRN
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

                return null;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando.', $e);
            }
        }

        protected function alterarControlado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {

            try {

            } catch (Exception $e) {
                throw new InfraException('Erro alterando.', $e);
            }

        }

        protected function excluirControlado($arrObjRelTipoControleLitigiosoUsuarioDTO)
        {

            try {


            } catch (Exception $e) {
                throw new InfraException('Erro excluindo.', $e);
            }
        }

        protected function consultarConectado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {
            try {

                return null;

            } catch (Exception $e) {
                throw new InfraException('Erro consultando.', $e);
            }
        }

        protected function listarConectado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {
            try {

                return null;

            } catch (Exception $e) {
                throw new InfraException('Erro listando.', $e);
            }
        }

        protected function contarConectado(MdLitRelTipoControleUsuarioDTO $objRelTipoControleLitigiosoUsuarioDTO)
        {

            try {

                return null;

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
