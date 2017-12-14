<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterModaliDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_dado_inter_modali';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDadoInteressado', 'id_md_lit_dado_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitModalidade', 'id_md_lit_modalidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdParticipanteMdLitDadoInteressado', 'id_participante', 'md_lit_dado_interessado');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitModalidade', 'nome', 'md_lit_modalidade');

    $this->configurarPK('IdMdLitDadoInteressado',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitModalidade',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitDadoInteressado', 'md_lit_dado_interessado', 'id_md_lit_dado_interessado');
    $this->configurarFK('IdMdLitModalidade', 'md_lit_modalidade', 'id_md_lit_modalidade');
  }
}
?>