<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/07/2018 - criado por ellyson.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitNumeroInteressadoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_numero_interessado';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNumeroInteressado', 'id_md_lit_numero_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDadoInteressado', 'id_md_lit_dado_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Numero', 'numero');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitDadoInteressadoMdLitDadoInteressado', 'id_md_lit_dado_interessado', 'md_lit_dado_interessado');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdContatoMdLitDadoInteressado', 'id_contato', 'md_lit_dado_interessado');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitControleMdLitDadoInteressado', 'id_md_lit_controle', 'md_lit_dado_interessado');

    $this->configurarPK('IdMdLitNumeroInteressado',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdLitDadoInteressado', 'md_lit_dado_interessado', 'id_md_lit_dado_interessado');

    //contato
      $this->configurarFK('IdContatoMdLitDadoInteressado', 'contato', 'id_contato');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'CnpjContatoParticipante', 'cnpj', 'contato');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'CpfContatoParticipante', 'cpf', 'contato');

  }
}
