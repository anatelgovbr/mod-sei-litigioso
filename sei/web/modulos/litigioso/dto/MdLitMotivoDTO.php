<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 30/04/2018 - criado por jhon.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMotivoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_motivo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitMotivo', 'id_md_lit_motivo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdMdLitMotivo',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
