<?
/**
 * ANATEL
 *
 * 3/07/2024 - criado por michaelr.colab - SPASSU
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitTpInfoAdRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validar(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdMdLitProcessoSituacao())) {
            $objInfraException->adicionarValidacao('situação não informada.');
        }
    }

    protected function cadastrarControlado(MdLitTpInfoAdDTO $objMdLitTpInfoAdDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_lit_tp_info_add_cadastrar');

            $objMdLitTpInfoAdBD = new MdLitTpInfoAdBD($this->getObjInfraIBanco());
            $objMdLitTpInfoAdDTO = $objMdLitTpInfoAdBD->cadastrar($objMdLitTpInfoAdDTO);

            return $objMdLitTpInfoAdDTO;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Tipo de Informação.', $e);
        }
    }

    protected function alterarControlado(MdLitTpInfoAdDTO $objMdLitTpInfoAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_tp_info_add_alterar');

            $objMdLitTpInfoAdBD = new MdLitTpInfoAdBD($this->getObjInfraIBanco());
            $objMdLitTpInfoAdBD->alterar($objMdLitTpInfoAdDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Tipo de Informação.', $e);
        }
    }

    protected function desativarControlado($arrObjMdLitTpInfoAdDTO)
    {

        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tp_info_add_alterar');

            $objMdLitTpInfoAdBD = new MdLitTpInfoAdBD($this->getObjInfraIBanco());

            foreach ($arrObjMdLitTpInfoAdDTO as $objMdLitTpInfoAdDTO){
                $objMdLitTpInfoAdBD->desativar($objMdLitTpInfoAdDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro desativando Tipo de Informação Adicional.', $e);
        }

    }

    protected function ativarControlado($arrObjMdLitTpInfoAdDTO)
    {

        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_tp_info_add_alterar');

            $objMdLitTpInfoAdBD = new MdLitTpInfoAdBD($this->getObjInfraIBanco());

            foreach ($arrObjMdLitTpInfoAdDTO as $objMdLitTpInfoAdDTO){
                $objMdLitTpInfoAdBD->reativar($objMdLitTpInfoAdDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro reativando Tipo de Informação Adicional.', $e);
        }

    }

    protected function excluirControlado($arrObjMdLitTpInfoAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_tp_info_add_excluir');

            $objMdLitTpInfoAdBD = new MdLitTpInfoAdBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdLitTpInfoAdDTO); $i++) {
                $objMdLitTpInfoAdBD->excluir($arrObjMdLitTpInfoAdDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Tipo de Informação.', $e);
        }
    }

    protected function consultarConectado(MdLitTpInfoAdDTO $objMdLitTpInfoAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_tp_info_add_consultar');

            $objMdLitTpInfoAdBD = new MdLitTpInfoAdBD($this->getObjInfraIBanco());
            $ret = $objMdLitTpInfoAdBD->consultar($objMdLitTpInfoAdDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Tipo de Informação.', $e);
        }
    }

    protected function listarConectado(MdLitTpInfoAdDTO $objMdLitTpInfoAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_tp_info_add_listar');

            $objMdLitTpInfoAdBD = new MdLitTpInfoAdBD($this->getObjInfraIBanco());
            $ret = $objMdLitTpInfoAdBD->listar($objMdLitTpInfoAdDTO);


            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando Tipo de Informação.', $e);
        }
    }

    protected function contarConectado(MdLitTpInfoAdDTO $objMdLitTpInfoAdDTO)
    {
        try {
            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_tp_info_add_contar');

            $objMdLitCamposAdBD = new MdLitTpInfoAdBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdBD->contar($objMdLitTpInfoAdDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando Tipo de Informação.', $e);
        }
    }
}

?>