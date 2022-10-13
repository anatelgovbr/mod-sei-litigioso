<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/04/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelControleMotivoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_controle_motivo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitControle', 'id_md_lit_controle');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitMotivo', 'id_md_lit_motivo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitTipoControleMdLitTipoControle', 'id_md_lit_tipo_controle', 'md_lit_tipo_controle');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitMotivoMdLitMotivo', 'id_md_lit_motivo', 'md_lit_motivo');

    $this->configurarPK('IdMdLitControle',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitMotivo',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitMotivo', 'md_lit_motivo', 'id_md_lit_motivo');
  }
}
