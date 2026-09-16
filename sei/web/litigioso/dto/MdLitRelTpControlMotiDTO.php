<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/04/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelTpControlMotiDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_tp_control_moti';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitTipoControle', 'id_md_lit_tipo_controle');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitMotivo', 'id_md_lit_motivo');

    $this->configurarPK('IdMdLitTipoControle',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitMotivo',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitTipoControle', 'md_lit_tipo_controle', 'id_md_lit_tipo_controle');
    $this->configurarFK('IdMdLitMotivo', 'md_lit_motivo', 'id_md_lit_motivo');
  }
}
