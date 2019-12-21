<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versгo do Gerador de Cуdigo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitProcessoSituacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_processo_situacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitProcessoSituacao', 'id_md_lit_processo_situacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacao', 'id_md_lit_situacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdDocumento', 'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitTipoControle', 'id_md_lit_tipo_controle');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinDepositoExtrajudicial', 'deposito_extrajudicial');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'ValorDepositoExtrajudicial', 'valor_deposito_extrajudicial');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'DtDepositoExtrajudicial', 'dta_deposito_extrajudicial');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Data', 'dta_data');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAlteraPrescricao', 'sin_altera_prescricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Intercorrente', 'dta_intecorrente');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Quinquenal', 'dta_quinquenal');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Inclusao', 'dth_inclusao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdLitProcessoSituacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdDocumento', 'documento d', 'd.id_documento');
    $this->configurarFK('IdProcedimento', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdMdLitTipoControle', 'md_lit_tipo_controle', 'id_md_lit_tipo_controle');
    $this->configurarFK('IdUsuario', 'usuario usu', 'usu.id_usuario');
    $this->configurarFK('IdUnidade', 'unidade uni', 'uni.id_unidade');

    $this->configurarExclusaoLogica('SinAtivo', 'N');

    //Get Dados Documento
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroDocumento','d.numero','documento d');

    //Get Numero Doc Formatado
    $this->configurarFK('IdProtocoloDocumento', 'protocolo pd', 'pd.id_protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProtocoloDocumento','d.id_documento','documento d');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoDocumento','pd.protocolo_formatado','protocolo pd');

    //Get Tipo do Documento (Sйrie)
    $this->configurarFK('IdSerie', 'serie s', 's.id_serie');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdSerie','d.id_serie','documento d');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 's.nome', 'serie s');

    //Get Dados Situaзгo
    $this->configurarFK('IdMdLitSituacao', 'md_lit_situacao ms','ms.id_md_lit_situacao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSituacao', 'ms.nome', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'OrdemParametrizarSit','ms.ordem','md_lit_situacao ms');

    //Get 'Sins' Situaзгo
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinInstauracaoSit', 'ms.sin_instauracao', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinIntimacaoSit', 'ms.sin_intimacao', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinDecisoriaSit', 'ms.sin_decisoria', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinDefesaSit', 'ms.sin_defesa', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinRecursalSit', 'ms.sin_recursal', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinConclusivaSit', 'ms.sin_conclusiva', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinOpcionalSit', 'ms.sin_opcional', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'PrazoSituacao', 'ms.prazo', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinObrigatoria', 'ms.sin_obrigatoria', 'md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAlegacoes', 'ms.sin_alegacoes', 'md_lit_situacao ms');

    //Get Dados Fases
    $this->configurarFK('IdMdLitFase', 'md_lit_fase mf','mf.id_md_lit_fase');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitFase','ms.id_md_lit_fase','md_lit_situacao ms');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeFase', 'mf.nome', 'md_lit_fase mf');
    
    //Get Dados Unidade
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade','uni.sigla','unidade uni');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade','uni.descricao','unidade uni');

    //Get Dados Usuario
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario','usu.nome','usuario usu');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario','usu.sigla','usuario usu');

      //GET tipo de controle litigioso
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaTipoControleLitigioso','sigla','md_lit_tipo_controle');


  }

  public function getStrTipoSituacao(){
      $dados = array();

      if($this->getStrSinInstauracaoSit() == 'S'){
          $dados['tipoSituacao'] = 'Instauracao';
          $dados['nomeLabel']    = 'da Instauraзгo';
          $dados['nome']         = 'Instauraзгo';
      }

      if($this->getStrSinIntimacaoSit() == 'S'){
          $dados['tipoSituacao'] = 'Intimacao';
          $dados['nomeLabel']    = 'da Intimaзгo';
          $dados['nome']         = 'Intimaзгo';
      }

      if($this->getStrSinDecisoriaSit() == 'S'){
          $dados['tipoSituacao'] = 'Decisoria';
          $dados['nomeLabel']    = 'da Decisгo';
          $dados['nome']         = 'Decisуria';
      }

      if($this->getStrSinDefesaSit() == 'S'){
          $dados['tipoSituacao'] = 'Defesa';
          $dados['nomeLabel']    = 'da Defesa';
          $dados['nome']         = 'Defesa';
      }

      if($this->getStrSinRecursalSit() == 'S'){
          $dados['tipoSituacao'] = 'Recursal';
          $dados['nomeLabel']    = 'do Recurso';
          $dados['nome']         = 'Recursal';
      }

      if($this->getStrSinConclusivaSit() == 'S'){
          $dados['tipoSituacao'] = 'Conclusiva';
          $dados['nomeLabel']    = 'do Trвnsito em Julgado';
          $dados['nome']         = 'Conclusiva';
      }

      if(!array_key_exists('tipoSituacao', $dados)){
          $dados['tipoSituacao'] = 'Livre';
          $dados['nomeLabel']    = '';
          $dados['nome']         = '';
      }

      return $dados;
  }
}
?>