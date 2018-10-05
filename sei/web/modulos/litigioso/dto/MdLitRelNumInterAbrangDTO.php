<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterAbrangDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_num_inter_abrang';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNumeroInteressado', 'id_md_lit_numero_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitAbrangencia', 'id_md_lit_abrangencia');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdParticipanteMdLitDadoInteressado', 'id_participante', 'md_lit_dado_interessado');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitAbrangencia', 'nome', 'md_lit_abrangencia');

    $this->configurarPK('IdMdLitNumeroInteressado',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitAbrangencia',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitNumeroInteressado', 'md_lit_numero_interessado', 'id_md_lit_numero_interessado');
    $this->configurarFK('IdMdLitAbrangencia', 'md_lit_abrangencia', 'id_md_lit_abrangencia');
  }
}
?>