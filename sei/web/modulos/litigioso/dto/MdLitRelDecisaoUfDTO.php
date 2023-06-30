<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/09/2018 - criado por ellyson.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRelDecisaoUfDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_decisao_uf';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDecisao', 'id_md_lit_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUf', 'id_uf');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitDecisaoMdLitDecisao', 'id_md_lit_decisao', 'md_lit_decisao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUfUf', 'id_uf', 'uf');

    $this->configurarPK('IdMdLitDecisao',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUf',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitDecisao', 'md_lit_decisao', 'id_md_lit_decisao');
    $this->configurarFK('IdUf', 'uf', 'id_uf');
  }
}
