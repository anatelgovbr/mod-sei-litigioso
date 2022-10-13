<?
/**
* ANATEL
*
* 06/02/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitRelDispositivoNormativoRevogadoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_rel_disp_norm_revogado';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDispositivoNormativo', 'id_md_lit_disp_normat');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDispositivoNormativoRevogado', 'id_md_lit_disp_normat_revogado');

    $this->configurarPK('IdMdLitDispositivoNormativo',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMdLitDispositivoNormativoRevogado',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdMdLitDispositivoNormativo', 'md_lit_disp_normat', 'id_md_lit_disp_normat');
      $this->configurarFK('IdMdLitDispositivoNormativoRevogado', 'md_lit_disp_normat disp_revogado', 'disp_revogado.id_md_lit_disp_normat');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'NormaRevogado',
          'disp_revogado.norma',
          'md_lit_disp_normat disp_revogado');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'DispositivoRevogado',
          'disp_revogado.dispositivo',
          'md_lit_disp_normat disp_revogado');
  }
}
