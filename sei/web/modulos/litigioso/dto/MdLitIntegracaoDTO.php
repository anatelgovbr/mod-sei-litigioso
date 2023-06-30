<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitIntegracaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_integracao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitIntegracao', 'id_md_lit_integracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitFuncionalidade', 'id_md_lit_funcionalidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'EnderecoWsdl', 'endereco_wsdl');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'OperacaWsdl', 'operaca_wsdl');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinVincularLancamento', 'sin_vincular_lancamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TipoClienteWs', 'tipo_cliente_ws');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'VersaoSoap', 'versao_soap');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitFuncionalidade', 'nome', 'md_lit_funcionalidade');

    $this->configurarPK('IdMdLitIntegracao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdLitFuncionalidade', 'md_lit_funcionalidade', 'id_md_lit_funcionalidade', InfraDTO::$TIPO_FK_OBRIGATORIA);

      $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjMdLitMapearParamSaidaDTO');
      $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjMdLitMapearParamEntradaDTO');

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>