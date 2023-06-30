<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRelDecisLancamentDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_decis_lancament';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDecisao', 'id_md_lit_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitLancamento', 'id_md_lit_lancamento');

//    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitDecisaoMdLitDecisao', 'id_md_lit_decisao', 'md_lit_decisao');

    $this->configurarPK('IdMdLitDecisao',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitLancamento',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitDecisao', 'md_lit_decisao', 'id_md_lit_decisao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'IdMdLitDecisoes');
  }
}
?>