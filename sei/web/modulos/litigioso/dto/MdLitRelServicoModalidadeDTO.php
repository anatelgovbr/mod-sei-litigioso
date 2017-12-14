<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelServicoModalidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_servico_modalidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitServico', 'id_md_lit_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitModalidade', 'id_md_lit_modalidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaOrigemMdLitServico', 'sta_origem', 'md_lit_servico');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitModalidade', 'nome', 'md_lit_modalidade');

    $this->configurarPK('IdMdLitServico',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitModalidade',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitServico', 'md_lit_servico', 'id_md_lit_servico');
    $this->configurarFK('IdMdLitModalidade', 'md_lit_modalidade', 'id_md_lit_modalidade');
  }
}
?>