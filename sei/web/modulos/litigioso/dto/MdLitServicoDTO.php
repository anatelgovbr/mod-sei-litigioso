<?
    /**
     * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
     *
     * 03/04/2017 - criado por Ellyson de Jesus Silva
     *
     * Versão do Gerador de Código: 1.40.1
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitServicoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_servico';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitServico', 'id_md_lit_servico');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitServicoIntegracao', 'id_md_lit_servico_integracao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaOrigem', 'sta_origem');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Codigo', 'codigo');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sigla', 'sigla');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

            //exclusão logica da integração apaga o registro de toda lista porém retorna na fk assim mantém os arquivos já cadastradosSE
            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivoIntegracao', 'sin_ativo_integracao');

            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'Modalidade');

            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'Abrangencia');

            //atributo para verificação na RN se será auditado no metodo. Ex.: excluirControlado
            $this->adicionarAtributo(InfraDTO::$PREFIXO_BOL, 'IsAuditoria');

            $this->configurarPK('IdMdLitServico', InfraDTO::$TIPO_PK_NATIVA);

            $this->configurarFK('IdMdLitServicoIntegracao', 'md_lit_servico_integracao', 'id_md_lit_servico_integracao', InfraDTO::$TIPO_FK_OPCIONAL);

            //Atributos Relacionados
            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeIntegracao', 'nome_integracao', 'md_lit_servico_integracao');

        }
    }
