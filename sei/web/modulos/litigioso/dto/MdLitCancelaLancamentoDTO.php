<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitCancelaLancamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_cancela_lancamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCancelaLancamento', 'id_md_lit_cancela_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitLancamento', 'id_md_lit_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MotivoCancelamento', 'motivo_cancelamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Justificativa', 'justificativa');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitLancamentoMdLitLancamento', 'id_md_lit_lancamento', 'md_lit_lancamento');

    $this->configurarPK('IdMdLitCancelaLancamento',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdLitLancamento', 'md_lit_lancamento', 'id_md_lit_lancamento');
  }
}
?>