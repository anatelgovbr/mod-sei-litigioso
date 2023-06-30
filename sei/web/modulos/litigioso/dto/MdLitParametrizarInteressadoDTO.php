<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/03/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitParametrizarInteressadoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_param_interessado';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitParamInteressado', 'id_md_lit_param_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitTipoControle', 'id_md_lit_tipo_controle');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNomeFuncional', 'id_md_lit_nome_funcional');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinExibe', 'sin_exibe');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinObrigatorio', 'sin_obrigatorio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'LabelCampo', 'label_campo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Tamanho', 'tamanho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'DescricaoAjuda', 'descricao_ajuda');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinCampoMapeado', 'sin_campo_mapeado');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitNomeFuncional', 'nome', 'md_lit_nome_funcional');

    $this->configurarPK('IdMdLitParamInteressado',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdLitTipoControle', 'md_lit_tipo_controle', 'id_md_lit_tipo_controle');
    $this->configurarFK('IdMdLitNomeFuncional', 'md_lit_nome_funcional', 'id_md_lit_nome_funcional');
  }
}
?>