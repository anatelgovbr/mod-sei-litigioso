<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versгo do Gerador de Cуdigo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitHistoricLancamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_historic_lancamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitHistoricLancamento', 'id_md_lit_historic_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoLancamento', 'id_md_lit_situacao_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitLancamento', 'id_md_lit_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TipoLancamento', 'tipo_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sequencial', 'sequencial');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Decisao', 'dta_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Intimacao', 'dta_intimacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Vencimento', 'dta_vencimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'PrazoDefesa', 'dta_prazo_defesa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'UltimoPagamento', 'dta_ultimo_pagamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'VlrLancamento', 'vlr_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'VlrDesconto', 'vlr_desconto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'VlrPago', 'vlr_pago');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'VlrSaldoDevedor', 'vlr_saldo_devedor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Inclusao', 'dth_inclusao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'LinkBoleto', 'link_boleto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NumeroInteressado', 'numero_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinConstituicaoDefinitiva', 'sin_constituicao_definitiva');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinRenunciaRecorrer', 'sin_renuncia_recorrer');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'IntimacaoDefinitiva', 'dta_intimacao_definitiva');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'ConstituicaoDefinitiva', 'dta_constituicao_definitiva');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Justificativa', 'justificativa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'CodigoReceita', 'codigo_receita');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinSuspenso', 'sin_suspenso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitIntegracao', 'id_md_lit_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNumeroInteressado', 'id_md_lit_numero_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoDecisaoDefin', 'id_md_lit_sit_dec_def');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'DecisaoDefinitiva', 'dta_decisao_definitiva');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'ApresentacaoRecurso', 'dta_apresentacao_recurso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSituacaoDecisao', 'id_situacao_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSituacaoIntimacao', 'id_situacao_intimacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSituacaoRecisao', 'id_situacao_recurso');

    //Pk
    $this->configurarPK('IdMdLitHistoricLancamento',InfraDTO::$TIPO_PK_NATIVA);

    //Get Dados Procedimento
    $this->configurarFK('IdProcedimento', 'procedimento', 'id_procedimento');

    //Get dados Situaзгo
    $this->configurarFK('IdMdLitSituacaoLancamento', 'md_lit_situacao_lancamento', 'id_md_lit_situacao_lancamento',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoLancamentoMdLitSituacaoLancamento', 'id_md_lit_situacao_lancamento', 'md_lit_situacao_lancamento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSituacao', 'nome', 'md_lit_situacao_lancamento');

    //Get dados Lanзamento
    $this->configurarFK('IdMdLitLancamento', 'md_lit_lancamento', 'id_md_lit_lancamento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitLancamentoMdLitLancamento', 'id_md_lit_lancamento', 'md_lit_lancamento');

    //Get Dados Unidade
    $this->configurarFK('IdUnidade', 'unidade uni', 'uni.id_unidade',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade','uni.sigla','unidade uni');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade','uni.descricao','unidade uni');

    //Get Dados Usuario
    $this->configurarFK('IdUsuario', 'usuario usu', 'usu.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario','usu.nome','usuario usu');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario','usu.sigla','usuario usu');

    //relacionamento da tabela md_lit_integracao
    $this->configurarFK('IdMdLitIntegracao', 'md_lit_integracao', 'id_md_lit_integracao',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IntegracaoNome', 'nome', 'md_lit_integracao');

    $this->configurarFK('IdMdLitSituacaoDecisaoDefin', 'md_lit_processo_situacao', 'id_md_lit_processo_situacao');
  }
}
?>