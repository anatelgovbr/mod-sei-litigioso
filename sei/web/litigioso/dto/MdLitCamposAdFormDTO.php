<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdFormDTO extends InfraDTO
{
    public function getStrNomeTabela()
    {
        return 'md_lit_campos_ad_form';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAdForm', 'id_md_lit_campos_ad_form');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'id_procedimento');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAd', 'id_md_lit_campos_ad');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Valor', 'valor');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Linha', 'num_linha');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Inclusao', 'dth_inclusao');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Nome', 'nome', 'md_lit_campos_ad');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Ajuda', 'ajuda', 'md_lit_campos_ad');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CampoTipo', 'tipo', 'md_lit_campos_ad');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitCamposAdSel', 'id_md_lit_campos_ad_sel', 'md_lit_campos_ad');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'ProtocoloFormatadoProtocolo','protocolo_formatado','protocolo');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdProcedimentoProcedimento','id_procedimento','procedimento');
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinObrigatorio','sin_obrigatorio','md_lit_campos_ad' );
        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaUsuario','sigla','usuario');

        $this->configurarFK('IdMdLitCamposAd', 'md_lit_campos_ad', 'id_md_lit_campos_ad',InfraDTO::$TIPO_FK_OBRIGATORIA);

        $this->configurarFK('IdProcedimento','procedimento','id_procedimento');
        $this->configurarFK('IdProcedimentoProcedimento','protocolo','id_protocolo');
        $this->configurarFK('IdUsuario','usuario','id_usuario');

        $this->configurarPK('IdMdLitCamposAdForm', InfraDTO::$TIPO_PK_NATIVA);

    }

    public function getStrNomeTipoInformacao()
    {
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($this->getNumIdMdLitCamposAd());
        $objMdLitCamposAdDTO->retNumIdMdLitTpInforAd();
        $objMdLitCamposAdDTO = (new MdLitCamposAdRN)->consultar($objMdLitCamposAdDTO);
        return MdLitTpInfoAdINT::recuperarNome($objMdLitCamposAdDTO->getNumIdMdLitTpInforAd());
    }
}

?>
