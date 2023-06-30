<?
/**
 * ANATEL
 *
 * 24/04/2017 - criado por Ellyson de Jesus Silva
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitMapearParamEntradaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'md_lit_mapea_param_entrada';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitMapearParamEntrada', 'id_md_lit_mapea_param_entrada');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitIntegracao', 'id_md_lit_integracao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNomeFuncional', 'id_md_lit_nome_funcional');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitCampoIntegracao', 'id_md_lit_campo_integracao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Campo', 'campo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveUnica', 'chave_unica');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitIntegracao', 'nome', 'md_lit_integracao');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdFuncionalidadeMdLitIntegracao', 'id_md_lit_funcionalidade', 'md_lit_integracao');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjMdLitMapeaParamValorDTO');

        $this->configurarPK('IdMdLitMapearParamEntrada', InfraDTO::$TIPO_PK_NATIVA);

        $this->configurarFK('IdMdLitIntegracao', 'md_lit_integracao', 'id_md_lit_integracao');
        $this->configurarFK('IdMdLitNomeFuncional', 'md_lit_nome_funcional', 'id_md_lit_nome_funcional', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdMdLitCampoIntegracao', 'md_lit_campo_integracao', 'id_md_lit_campo_integracao', InfraDTO::$TIPO_FK_OPCIONAL);
    }
}
