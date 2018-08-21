<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterCidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_num_inter_cidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCidade', 'id_cidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNumeroInteressado', 'id_md_lit_numero_interessado');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdParticipanteMdLitNumeroInteressado', 'id_participante', 'id_md_lit_numero_interessado');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeCidade', 'nome', 'cidade');

    $this->configurarPK('IdMdLitNumeroInteressado',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdCidade',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdCidade', 'cidade', 'id_cidade');
    $this->configurarFK('IdMdLitNumeroInteressado', 'md_lit_numero_interessado', 'id_md_lit_numero_interessado');
  }
}
?>