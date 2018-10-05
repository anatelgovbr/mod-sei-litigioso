<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterServicoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_num_inter_servico';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNumeroInteressado', 'id_md_lit_numero_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitServico', 'id_md_lit_servico');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoMdLitServico', 'descricao', 'md_lit_servico');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CodigoMdLitServico', 'codigo', 'md_lit_servico');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdContatoMdLitDadoInteressado', 'id_contato', 'md_lit_dado_interessado');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroMdLitNumeroInteressado', 'numero', 'md_lit_numero_interessado');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitDadoInteressadoMdLitNumeroInteressado', 'id_md_lit_dado_interessado', 'md_lit_numero_interessado');

    $this->configurarPK('IdMdLitNumeroInteressado',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitServico',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitNumeroInteressado', 'md_lit_numero_interessado', 'id_md_lit_numero_interessado');
    $this->configurarFK('IdMdLitServico', 'md_lit_servico', 'id_md_lit_servico');

     $this->configurarFK('IdHipoteseLegalProtocolo', 'hipotese_legal', 'id_hipotese_legal', InfraDTO::$TIPO_FK_OPCIONAL);
      $this->configurarFK('IdMdLitDadoInteressadoMdLitNumeroInteressado', 'md_lit_dado_interessado', 'id_md_lit_dado_interessado');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NumeroComServico');
  }

}
?>