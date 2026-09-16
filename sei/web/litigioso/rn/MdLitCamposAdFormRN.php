<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdFormRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdLitCamposAdFormDTO $objMdLitCamposAdFormDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_form_cadastrar');

            $objMdLitCamposAdFormBD = new MdLitCamposAdFormBD($this->getObjInfraIBanco());
            $objMdLitCamposAdFormDTO = $objMdLitCamposAdFormBD->cadastrar($objMdLitCamposAdFormDTO);

            return $objMdLitCamposAdFormDTO;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando campos do formulário.', $e);
        }
    }

    protected function alterarControlado(MdLitCamposAdFormDTO $objMdLitCamposAdFormDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_form_alterar');


            $objMdLitCamposAdFormBD = new MdLitCamposAdFormBD($this->getObjInfraIBanco());
            $objMdLitCamposAdFormBD->alterar($objMdLitCamposAdFormDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando campos do formulário.', $e);
        }
    }

    protected function excluirControlado($arrObjMdLitCamposAdFormDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_form_excluir');

            $objMdLitCamposAdFormBD = new MdLitCamposAdFormBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdLitCamposAdFormDTO); $i++) {
                $objMdLitCamposAdFormBD->excluir($arrObjMdLitCamposAdFormDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo campos do formulário.', $e);
        }
    }

    protected function consultarConectado(MdLitCamposAdFormDTO $objMdLitCamposAdFormDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_form_consultar');

            $objMdLitCamposAdFormBD = new MdLitCamposAdFormBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdFormBD->consultar($objMdLitCamposAdFormDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando campo do formulário.', $e);
        }
    }

    protected function listarConectado(MdLitCamposAdFormDTO $objMdLitCamposAdFormDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_form_listar');

            $objMdLitCamposAdFormBD = new MdLitCamposAdFormBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdFormBD->listar($objMdLitCamposAdFormDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando campos do formulário.', $e);
        }
    }

    protected function contarConectado(MdLitCamposAdFormDTO $objMdLitCamposAdFormDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_campo_add_form_listar');

            $objMdLitCamposAdFormBD = new MdLitCamposAdFormBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdFormBD->contar($objMdLitCamposAdFormDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando campos do formulário.', $e);
        }
    }

}

?>