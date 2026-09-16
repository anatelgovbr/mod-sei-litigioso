<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdSelDTO extends InfraDTO
{

    private $numTipoFkEspecieDecisao = null;

    public function getStrNomeTabela()
    {
        return 'md_lit_campos_ad_sel';
    }

    public function __construct()
    {
        $this->numTipoFkEspecieDecisao = InfraDTO::$TIPO_FK_OPCIONAL;
        parent::__construct();
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAdSel', 'id_md_lit_campos_ad_sel');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAd', 'id_md_lit_campos_ad');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'IdMdLitCamposAd','id_md_lit_campos_ad','md_lit_campos_ad');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeCampo','nome','md_lit_campos_ad');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdMdLitTpInforAd','id_md_lit_tp_info_add','md_lit_campos_ad');

        $this->configurarPK('IdMdLitCamposAdSel', InfraDTO::$TIPO_PK_NATIVA);

        $this->configurarFK('IdMdLitCamposAd', 'md_lit_campos_ad', 'id_md_lit_campos_ad');
    }

}

?>