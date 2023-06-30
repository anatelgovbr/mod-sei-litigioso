<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/07/2017 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitMapeaParamValorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_mapea_param_valor';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitMapeaParamValor', 'id_md_lit_mapea_param_valor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitMapearParamEntrada', 'id_md_lit_mapea_param_entrada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitTipoControle', 'id_md_lit_tipo_controle');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ValorDefault', 'valor_default');

    $this->configurarPK('IdMdLitMapeaParamValor',InfraDTO::$TIPO_PK_NATIVA);

  }
}
?>