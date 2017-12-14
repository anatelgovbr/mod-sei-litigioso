<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelDadoInterServicoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_dado_inter_servico';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDadoInteressado', 'id_md_lit_dado_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitServico', 'id_md_lit_servico');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdParticipanteMdLitDadoInteressado', 'id_participante', 'md_lit_dado_interessado');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NumeroMdLitDadoInteressado', 'numero', 'md_lit_dado_interessado');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoMdLitServico', 'descricao', 'md_lit_servico');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'CodigoMdLitServico', 'codigo', 'md_lit_servico');

    $this->configurarPK('IdMdLitDadoInteressado',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitServico',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitDadoInteressado', 'md_lit_dado_interessado', 'id_md_lit_dado_interessado');
    $this->configurarFK('IdMdLitServico', 'md_lit_servico', 'id_md_lit_servico');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NumeroComServico');
  }

}
?>