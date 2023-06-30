<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitFuncionalidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_funcionalidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitFuncionalidade', 'id_md_lit_funcionalidade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->configurarPK('IdMdLitFuncionalidade',InfraDTO::$TIPO_PK_NATIVA);

    //Configuração necessária por causa do relacionamento ser 1 para 1, assim não vai existir registro duplicados.
    //Não existe outra forma de acessar a tabela mãe(md_lit_integracao) a partir da tabela filha(md_lit_funcionalidade)
    $this->configurarFK('IdMdLitFuncionalidade', 'md_lit_integracao m2', 'm2.id_md_lit_funcionalidade', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitIntegracao', 'm2.nome', 'md_lit_integracao m2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitIntegracao', 'm2.id_md_lit_integracao', 'md_lit_integracao m2');

  }
}
?>