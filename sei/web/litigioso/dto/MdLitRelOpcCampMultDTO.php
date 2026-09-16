<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelOpcCampMultDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_lit_rel_opc_camp_mult';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAdForm', 'id_md_lit_campos_ad_form');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAdSel', 'id_md_lit_campos_ad_sel');

        $this->configurarPK('IdMdLitCamposAdForm',InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('IdMdLitCamposAdSel',InfraDTO::$TIPO_PK_INFORMADO);

        $this->configurarFK('IdMdLitCamposAdForm', 'md_lit_campos_ad_form', 'id_md_lit_campos_ad_form');
        $this->configurarFK('IdMdLitCamposAdSel', 'md_lit_campos_ad_sel', 'id_md_lit_campos_ad_sel');
    }

}
?>