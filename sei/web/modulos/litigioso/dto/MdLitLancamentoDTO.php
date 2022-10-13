<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versгo do Gerador de Cуdigo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitLancamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_lancamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitLancamento', 'id_md_lit_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoLancamento', 'id_md_lit_situacao_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TipoLancamento', 'tipo_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sequencial', 'sequencial');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Decisao', 'dta_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Intimacao', 'dta_intimacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'DecursoPrazoRecurso', 'dta_decurso_prazo_recurso');

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

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoLancamentoMdLitSituacaoLancamento', 'id_md_lit_situacao_lancamento', 'md_lit_situacao_lancamento');

    $this->configurarPK('IdMdLitLancamento',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdLitSituacaoLancamento', 'md_lit_situacao_lancamento', 'id_md_lit_situacao_lancamento',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');

      //relacionamento da tabela md_lit_integracao
      $this->configurarFK('IdMdLitIntegracao', 'md_lit_integracao', 'id_md_lit_integracao',InfraDTO::$TIPO_FK_OPCIONAL);
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IntegracaoIdMdLitFuncionalidade', 'id_md_lit_funcionalidade', 'md_lit_integracao');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IntegracaoNome', 'nome', 'md_lit_integracao');



      //Get dados Situaзгo
      $this->configurarFK('IdMdLitSituacaoLancamento', 'md_lit_situacao_lancamento', 'id_md_lit_situacao_lancamento',InfraDTO::$TIPO_FK_OPCIONAL);
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoLancamentoMdLitSituacaoLancamento', 'id_md_lit_situacao_lancamento', 'md_lit_situacao_lancamento');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSituacao', 'nome', 'md_lit_situacao_lancamento');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CorSituacao', 'cor_situacao', 'md_lit_situacao_lancamento');

    //Get Dados Procedimento
    $this->configurarFK('IdProcedimento', 'protocolo', 'id_protocolo');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloProcedimentoFormatado', 'protocolo_formatado', 'protocolo');

    //GET numero Interessado
//    $this->configurarFK('IdMdLitNumeroInteressado', 'md_lit_numero_interessado', 'id_md_lit_numero_interessado',InfraDTO::$TIPO_FK_OPCIONAL);
//    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitDadoInteressadoMdLitNumero', 'id_md_lit_dado_interessado', 'md_lit_numero_interessado');
//      $this->configurarFK('IdMdLitDadoInteressadoMdLitNumero', 'md_lit_dado_interessado', 'id_md_lit_dado_interessado',InfraDTO::$TIPO_FK_OPCIONAL);
//      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdContatoMdLitDadoInteressado', 'id_contato', 'md_lit_dado_interessado');

  }




    public function getStrNomeTipoLancamentoComValor($index = null){
        $retorno = '';
        switch ($this->getStrTipoLancamento()){
            case MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL : $retorno = 'Principal (R$ '.InfraUtil::formatarDin(InfraUtil::prepararDbl($this->getDblVlrLancamento())).')';
                break;
            case MdLitLancamentoRN::$TIPO_LANCAMENTO_PRINCIPAL_REDUCAO : $retorno = 'Principal - Reduзгo (R$ '.InfraUtil::formatarDin(InfraUtil::prepararDbl($this->getDblVlrLancamento())).')';
                break;
            case MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO : $retorno = 'Majorado '.$index.' (R$ '.InfraUtil::formatarDin(InfraUtil::prepararDbl($this->getDblVlrLancamento())).')';
                break;
        }
        return $retorno;
    }
}
?>