<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/07/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCampoIntegracaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_campo_integracao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCampoIntegracao', 'id_md_lit_campo_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitFuncionalidade', 'id_md_lit_funcionalidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeCampo', 'nome_campo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaParametro', 'sta_parametro');

    $this->configurarPK('IdMdLitCampoIntegracao',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
?>