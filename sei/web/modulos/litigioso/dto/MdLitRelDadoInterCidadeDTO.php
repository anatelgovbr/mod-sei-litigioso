<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterCidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_dado_inter_cidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCidade', 'id_cidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDadoInteressado', 'id_md_lit_dado_interessado');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdParticipanteMdLitDadoInteressado', 'id_participante', 'md_lit_dado_interessado');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeCidade', 'nome', 'cidade');

    $this->configurarPK('IdMdLitDadoInteressado',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdCidade',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdCidade', 'cidade', 'id_cidade');
    $this->configurarFK('IdMdLitDadoInteressado', 'md_lit_dado_interessado', 'id_md_lit_dado_interessado');
  }
}
?>