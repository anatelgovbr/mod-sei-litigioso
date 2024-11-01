<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva
*
* Vers�o do Gerador de C�digo: 1.40.1
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

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Codigo', 'codigo');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

      //exclus�o logica da integra��o apaga o registro de toda lista por�m retorna na fk assim mant�m os arquivos j� cadastradosSE
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivoIntegracao', 'sin_ativo_integracao');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaOrigem', 'sta_origem');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinCancelamento', 'sin_cancelamento');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinUtilizarAgendamento', 'sin_utilizar_agendamento');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoLancInt', 'id_md_lit_situacao_lancam_int');

      $this->configurarPK('IdMdLitSituacaoLancamento',InfraDTO::$TIPO_PK_NATIVA);

      $this->configurarFK('IdMdLitSituacaoLancInt', 'md_lit_situacao_lancam_int', 'id_md_lit_situacao_lancam_int', InfraDTO::$TIPO_FK_OPCIONAL);

      //Atributos Relacionados
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeIntegracao', 'nome_integracao', 'md_lit_situacao_lancam_int');

      //atributo para verifica��o na RN se ser� auditado no metodo. Ex.: excluirControlado
      $this->adicionarAtributo(InfraDTO::$PREFIXO_BOL, 'IsAuditoria');

  }
}
?>