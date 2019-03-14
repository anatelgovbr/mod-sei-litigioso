<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitServicoIntegracaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_servico_integracao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitServicoIntegracao', 'id_md_lit_servico_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'NomeIntegracao', 'nome_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'EnderecoWsdl', 'endereco_wsdl');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'OperacaoWsdl', 'operacao_wsdl');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MapeamentoCodigo', 'mapeamento_codigo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MapeamentoSigla', 'mapeamento_sigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MapeamentoDescricao', 'mapeamento_descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MapeamentoSituacao', 'mapeamento_situacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveUnica', 'chave_unica');

      $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'Modalidade');

      $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'Abrangencia');

    $this->configurarPK('IdMdLitServicoIntegracao',InfraDTO::$TIPO_PK_NATIVA);

  }
}
?>