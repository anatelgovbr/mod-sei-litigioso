<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelOpcCampMultRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdLitRelOpcCampMultDTO $objMdLitRelOpcCampMultDTO)
    {
        try {

            SessaoSEI::getInstance()->validarPermissao('md_lit_rel_opc_camp_mult_cadastrar');

            $objMdLitRelOpcCampMultBD = new MdLitRelOpcCampMultBD($this->getObjInfraIBanco());
            $objMdLitRelOpcCampMultDTO = $objMdLitRelOpcCampMultBD->cadastrar($objMdLitRelOpcCampMultDTO);

            return $objMdLitRelOpcCampMultDTO;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Opção.', $e);
        }
    }

    protected function excluirControlado($arrObjMdLitRelOpcCampMultDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_rel_opc_camp_mult_excluir');

            $objMdLitRelOpcCampMultBD = new MdLitRelOpcCampMultBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdLitRelOpcCampMultDTO); $i++) {
                $objMdLitRelOpcCampMultBD->excluir($arrObjMdLitRelOpcCampMultDTO[$i]);
            }

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Campo.', $e);
        }
    }

    protected function listarConectado(MdLitRelOpcCampMultDTO $objMdLitRelOpcCampMultDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_lit_rel_opc_camp_mult_listar');

            $objMdLitCamposAdBD = new MdLitCamposAdBD($this->getObjInfraIBanco());
            $ret = $objMdLitCamposAdBD->listar($objMdLitRelOpcCampMultDTO);


            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro listando Campos.', $e);
        }
    }

}

?>