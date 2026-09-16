<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitTpInfoAdDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_lit_tp_info_add ';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitTpInforAd', 'id_md_lit_tp_info_add');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitTpControle', 'id_md_lit_tipo_controle');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

        $this->configurarPK('IdMdLitTpInforAd', InfraDTO::$TIPO_PK_NATIVA);

    }
}

?>