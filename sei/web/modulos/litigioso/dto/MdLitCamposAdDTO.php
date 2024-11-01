<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdDTO extends InfraDTO
{
    public function getStrNomeTabela()
    {
        return 'md_lit_campos_ad';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAd', 'id_md_lit_campos_ad');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CampoTipo', 'tipo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Ajuda', 'ajuda');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Ordem', 'ordem');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitTpInforAd', 'id_md_lit_tp_info_add');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAdSel', 'id_md_lit_campos_ad_sel');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinObrigatorio','sin_obrigatorio');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'ValorMinimo','valor_minimo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'ValorMaximo','valor_maximo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'Tamanho','tamanho');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinAtivo','sin_ativo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinDocExterno','sin_doc_externo');

        $this->configurarPK('IdMdLitCamposAd', InfraDTO::$TIPO_PK_NATIVA);

        $this->configurarFK('IdMdLitTpInforAd', 'md_lit_tp_info_add', 'id_md_lit_tp_info_add',InfraDTO::$TIPO_FK_OBRIGATORIA);

    }
}

?>