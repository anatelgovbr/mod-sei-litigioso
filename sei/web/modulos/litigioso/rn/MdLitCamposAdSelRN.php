<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdSelRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdLitCamposAdSelDTO $objMdLitCamposAdSelDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_sel_cadastrar');

            $objMdLitCamposAdSelBD = new MdLitCamposAdSelBD($this->getObjInfraIBanco());
            $objMdLitCamposAdSelDTO = $objMdLitCamposAdSelBD->cadastrar($objMdLitCamposAdSelDTO);

            return $objMdLitCamposAdSelDTO;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Opção.', $e);
        }
    }

    protected function alterarControlado(MdLitCamposAdSelDTO $objMdLitCamposAdSelDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_sel_alterar');

            $objMdLitCamposAdSelBD = new MdLitCamposAdSelBD($this->getObjInfraIBanco());
            $objMdLitCamposAdSelBD->alterar($objMdLitCamposAdSelDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Opção.', $e);
        }
    }

    protected function desativarControlado($arrObjMdLitCamposAdSelDTO)
    {
        try {
            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_campo_add_sel_alterar');

            $objMdLitCamposAdSelBD = new MdLitCamposAdSelBD($this->getObjInfraIBanco());

            foreach ($arrObjMdLitCamposAdSelDTO as $objMdLitCamposAdSelDTO){
                $objMdLitCamposAdSelBD->desativar($objMdLitCamposAdSelDTO);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro desativando Opção Campo Adicional.', $e);
        }

    }

    protected function excluirControlado($arrObjMdLitCamposAdSelDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_sel_excluir');

            $objMdLitCamposAdSelBD = new MdLitCamposAdSelBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdLitCamposAdSelDTO); $i++) {
                $objMdLitCamposAdSelBD->excluir($arrObjMdLitCamposAdSelDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Opção.', $e);
        }
    }

    protected function consultarConectado(MdLitCamposAdSelDTO $objMdLitCamposAdSelDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_sel_consultar');

            $objMdLitCamposAdSelBD = new MdLitCamposAdSelBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdSelBD->consultar($objMdLitCamposAdSelDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Opção.', $e);
        }
    }

    protected function listarConectado(MdLitCamposAdSelDTO $objMdLitCamposAdSelDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_sel_listar');

            $objMdLitCamposAdSelBD = new MdLitCamposAdSelBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdSelBD->listar($objMdLitCamposAdSelDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando Opção.', $e);
        }
    }

    protected function contarConectado(MdLitCamposAdSelDTO $objMdLitCamposAdSelDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_sel_listar');

            $objMdLitCamposAdSelBD = new MdLitCamposAdSelBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdSelBD->contar($objMdLitCamposAdSelDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando Opção.', $e);
        }
    }

}

?>