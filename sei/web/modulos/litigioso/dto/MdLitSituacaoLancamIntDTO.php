<?
/**
* ANATEL
*
* 18/04/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitSituacaoLancamIntDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_situacao_lancam_int';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacaoLancamInt', 'id_md_lit_situacao_lancam_int');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeIntegracao', 'nome_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'EnderecoWsdl', 'endereco_wsdl');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'OperacaoWsdl', 'operacao_wsdl');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MapeamentoCodigo', 'mapeamento_codigo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MapeamentoDescricao', 'mapeamento_descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveUnica', 'chave_unica');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TipoClienteWs', 'tipo_cliente_ws');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'VersaoSoap', 'versao_soap');

    $this->configurarPK('IdMdLitSituacaoLancamInt',InfraDTO::$TIPO_PK_NATIVA);

      $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'SituacaoCancelamento');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'CoresSelecionados');
  }
}
