<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 15/03/2017 - criado por Ellyson de Jesus Silva
*
* Vers�o do Gerador de C�digo: 1.40.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitNomeFuncionalDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_nome_funcional';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNomeFuncional', 'id_md_lit_nome_funcional');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->configurarPK('IdMdLitNomeFuncional',InfraDTO::$TIPO_PK_NATIVA);

  }
}
?>