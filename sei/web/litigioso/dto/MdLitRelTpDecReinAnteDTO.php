<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/04/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelTpDecReinAnteDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_tp_dec_rein_ante';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdRelMdLitTipoDecisao', 'id_md_lit_tipo_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdRelMdLitReincidenAnteceden', 'id_md_lit_reinciden_anteceden');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeTipoDecisao', 'nome', 'md_lit_tipo_decisao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitReincidenAnteceden', 'id_md_lit_reinciden_anteceden', 'md_lit_reinciden_anteceden');

    $this->configurarPK('IdRelMdLitTipoDecisao',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdRelMdLitReincidenAnteceden',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdRelMdLitTipoDecisao', 'md_lit_tipo_decisao', 'id_md_lit_tipo_decisao');
    $this->configurarFK('IdRelMdLitReincidenAnteceden', 'md_lit_reinciden_anteceden', 'id_md_lit_reinciden_anteceden');
  }
}
