<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterTpOutorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_num_inter_tp_outor';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNumeroInteressado', 'id_md_lit_numero_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitAdmTipoOutor', 'id_md_lit_adm_tipo_outor');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdParticipanteMdLitDadoInteressado', 'id_participante', 'md_lit_dado_interessado');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeTipoOutorga', 'nome', 'md_lit_adm_tipo_outor');

    $this->configurarPK('IdMdLitNumeroInteressado',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitAdmTipoOutor',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitNumeroInteressado', 'md_lit_numero_interessado', 'id_md_lit_numero_interessado');
    $this->configurarFK('IdMdLitAdmTipoOutor', 'md_lit_adm_tipo_outor', 'id_md_lit_adm_tipo_outor');
  }
}
?>