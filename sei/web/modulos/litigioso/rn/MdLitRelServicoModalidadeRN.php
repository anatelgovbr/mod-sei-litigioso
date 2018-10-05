<?
    /**
     * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
     *
     * 03/04/2017 - criado por Ellyson de Jesus Silva
     *
     * Vers�o do Gerador de C�digo: 1.40.1
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelServicoModalidadeRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        private function validarNumIdMdLitServico(MdLitRelServicoModalidadeDTO $objMdLitRelServicoModalidadeDTO, InfraException $objInfraException)
        {
            if (InfraString::isBolVazia($objMdLitRelServicoModalidadeDTO->getNumIdMdLitServico())) {
                $objInfraException->adicionarValidacao('servi�o n�o informado.');
            }
        }

        private function validarNumIdMdLitModalidade(MdLitRelServicoModalidadeDTO $objMdLitRelServicoModalidadeDTO, InfraException $objInfraException)
        {
            if (InfraString::isBolVazia($objMdLitRelServicoModalidadeDTO->getNumIdMdLitModalidade())) {
                $objInfraException->adicionarValidacao('modalidade n�o informada.');
            }
        }

        protected function cadastrarControlado(MdLitRelServicoModalidadeDTO $objMdLitRelServicoModalidadeDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_modalidade_cadastrar');

                //Regras de Negocio
                $objInfraException = new InfraException();

                $this->validarNumIdMdLitServico($objMdLitRelServicoModalidadeDTO, $objInfraException);
                $this->validarNumIdMdLitModalidade($objMdLitRelServicoModalidadeDTO, $objInfraException);

                $objInfraException->lancarValidacoes();

                $objMdLitRelServicoModalidadeBD = new MdLitRelServicoModalidadeBD($this->getObjInfraIBanco());
                $ret                            = $objMdLitRelServicoModalidadeBD->cadastrar($objMdLitRelServicoModalidadeDTO);

                //Auditoria

                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro cadastrando servi�o da modalidade.', $e);
            }
        }

        protected function alterarControlado(MdLitRelServicoModalidadeDTO $objMdLitRelServicoModalidadeDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_modalidade_alterar');

                //Regras de Negocio
                $objInfraException = new InfraException();

                if ($objMdLitRelServicoModalidadeDTO->isSetNumIdMdLitServico()) {
                    $this->validarNumIdMdLitServico($objMdLitRelServicoModalidadeDTO, $objInfraException);
                }
                if ($objMdLitRelServicoModalidadeDTO->isSetNumIdMdLitModalidade()) {
                    $this->validarNumIdMdLitModalidade($objMdLitRelServicoModalidadeDTO, $objInfraException);
                }

                $objInfraException->lancarValidacoes();

                $objMdLitRelServicoModalidadeBD = new MdLitRelServicoModalidadeBD($this->getObjInfraIBanco());
                $objMdLitRelServicoModalidadeBD->alterar($objMdLitRelServicoModalidadeDTO);

                //Auditoria

            } catch (Exception $e) {
                throw new InfraException('Erro alterando servi�o da modalidade.', $e);
            }
        }

        protected function excluirControlado($arrObjMdLitRelServicoModalidadeDTO)
        {
            try {

                SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_modalidade_excluir');


                $objMdLitRelServicoModalidadeBD = new MdLitRelServicoModalidadeBD($this->getObjInfraIBanco());
                for ($i = 0; $i < count($arrObjMdLitRelServicoModalidadeDTO); $i++) {
                    $objMdLitRelServicoModalidadeBD->excluir($arrObjMdLitRelServicoModalidadeDTO[$i]);
                }

            } catch (Exception $e) {
                throw new InfraException('Erro excluindo servi�o da modalidade.', $e);
            }
        }

        protected function consultarConectado(MdLitRelServicoModalidadeDTO $objMdLitRelServicoModalidadeDTO)
        {
            try {

                SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_modalidade_consultar');


                $objMdLitRelServicoModalidadeBD = new MdLitRelServicoModalidadeBD($this->getObjInfraIBanco());
                $ret                            = $objMdLitRelServicoModalidadeBD->consultar($objMdLitRelServicoModalidadeDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando servi�o da modalidade.', $e);
            }
        }

        protected function listarConectado(MdLitRelServicoModalidadeDTO $objMdLitRelServicoModalidadeDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_modalidade_listar');

                $objMdLitRelServicoModalidadeBD = new MdLitRelServicoModalidadeBD($this->getObjInfraIBanco());
                $ret                            = $objMdLitRelServicoModalidadeBD->listar($objMdLitRelServicoModalidadeDTO);


                return $ret;

            } catch (Exception $e) {
                throw new InfraException('Erro listando servi�os da modalidade.', $e);
            }
        }

        protected function contarConectado(MdLitRelServicoModalidadeDTO $objMdLitRelServicoModalidadeDTO)
        {
            try {

                //Valida Permissao
                SessaoSEI::getInstance()->validarPermissao('md_lit_rel_servico_modalidade_listar');


                $objMdLitRelServicoModalidadeBD = new MdLitRelServicoModalidadeBD($this->getObjInfraIBanco());
                $ret                            = $objMdLitRelServicoModalidadeBD->contar($objMdLitRelServicoModalidadeDTO);


                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro contando servi�os da modalidade.', $e);
            }
        }

    }
