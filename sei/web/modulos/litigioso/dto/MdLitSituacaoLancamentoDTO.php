<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitSituacaoLancamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_situacao_lancamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoLancamento', 'id_md_lit_situacao_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CorSituacao', 'cor_situacao');

    $this->configurarPK('IdMdLitSituacaoLancamento',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
?>