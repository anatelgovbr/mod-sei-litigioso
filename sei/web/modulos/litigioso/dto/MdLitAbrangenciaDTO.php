<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitAbrangenciaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_abrangencia';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitAbrangencia', 'id_md_lit_abrangencia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->configurarPK('IdMdLitAbrangencia',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
?>