<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarNumIdMdLitProcessoSituacao(MdLitDecisaoDTO $objMdLitDecisaoDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objMdLitDecisaoDTO->getNumIdMdLitProcessoSituacao())) {
            $objInfraException->adicionarValidacao('situação não informada.');
        }
    }

    protected function cadastrarControlado(MdLitCamposAdDTO $objMdLitCamposAdDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_cadastrar');

            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());
            $objMdLitCamposAdDTO = $objMdLitCamposAdBD->cadastrar($objMdLitCamposAdDTO);

            return $objMdLitCamposAdDTO;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Decisão.', $e);
        }
    }

    protected function alterarControlado(MdLitCamposAdDTO $objMdLitCamposAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_alterar');


            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());
            $objMdLitCamposAdBD->alterar($objMdLitCamposAdDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Campo.', $e);
        }
    }

    protected function desativarControlado($arrobjMdLitCamposAdDTO)
    {
        try {
            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_campo_add_alterar');

            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());

            foreach ($arrobjMdLitCamposAdDTO as $objMdLitCamposAdDTO){
                $objMdLitCamposAdBD->desativar($objMdLitCamposAdDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro desativando Campo Adicional.', $e);
        }

    }

    protected function ativarControlado($arrobjMdLitCamposAdDTO)
    {

        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_campo_add_alterar');

            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());

            foreach ($arrobjMdLitCamposAdDTO as $objMdLitCamposAdDTO){
                $objMdLitCamposAdBD->reativar($objMdLitCamposAdDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro ativando Campo Adicional.', $e);
        }

    }

    protected function excluirControlado($arrobjMdLitCamposAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_excluir');

            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrobjMdLitCamposAdDTO); $i++) {
                $objMdLitCamposAdBD->excluir($arrobjMdLitCamposAdDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Campo.', $e);
        }
    }

    protected function consultarConectado(MdLitCamposAdDTO $objMdLitCamposAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_consultar');

            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdBD->consultar($objMdLitCamposAdDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Campo.', $e);
        }
    }

    protected function listarConectado(MdLitCamposAdDTO $objMdLitCamposAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_listar');

            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdBD->listar($objMdLitCamposAdDTO);


            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando Campos.', $e);
        }
    }

    protected function contarConectado(MdLitCamposAdDTO $objMdLitCamposAdDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_listar');

            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdBD->contar($objMdLitCamposAdDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando Campos.', $e);
        }
    }

}

?>