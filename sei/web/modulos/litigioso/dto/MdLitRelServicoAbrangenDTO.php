<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelServicoAbrangenDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_servico_abrangen';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitServico', 'id_md_lit_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitAbrangencia', 'id_md_lit_abrangencia');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaOrigemMdLitServico', 'sta_origem', 'md_lit_servico');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitAbrangencia', 'nome', 'md_lit_abrangencia');

    $this->configurarPK('IdMdLitServico',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitAbrangencia',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitServico', 'md_lit_servico', 'id_md_lit_servico');
    $this->configurarFK('IdMdLitAbrangencia', 'md_lit_abrangencia', 'id_md_lit_abrangencia');
  }
}
?>