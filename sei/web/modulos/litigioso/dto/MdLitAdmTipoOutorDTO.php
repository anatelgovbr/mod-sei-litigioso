<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/05/2019 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitAdmTipoOutorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_adm_tipo_outor';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitAdmTipoOutor', 'id_md_lit_adm_tipo_outor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

      $this->configurarExclusaoLogica('SinAtivo', 'N');

    $this->configurarPK('IdMdLitAdmTipoOutor',InfraDTO::$TIPO_PK_NATIVA);

  }
}
?>