<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/04/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitReincidenAntecedenDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_reinciden_anteceden';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitReincidenAnteceden', 'id_md_lit_reinciden_anteceden');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Prazo', 'prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Orientacao', 'orientacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Tipo', 'tipo');

    $this->configurarPK('IdMdLitReincidenAnteceden',InfraDTO::$TIPO_PK_NATIVA);


  }
}
