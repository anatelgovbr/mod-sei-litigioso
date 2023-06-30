<?
    /**
     * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
     *
     * 20/04/2017 - criado por Ellyson de Jesus Silva
     *
     * Versão do Gerador de Código: 1.40.1
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitDadoInteressadoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_dado_interessado';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                'IdMdLitDadoInteressado',
                'id_md_lit_dado_interessado');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                'IdMdLitControle',
                'id_md_lit_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                'IdContato',
                'id_contato');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                'SinOutorgado',
                'sin_outorgado');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                'SinAtivo',
                'sin_ativo');


            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                'NomeContatoParticipante',
                'nome',
                'contato');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                'CnpjContatoParticipante',
                'cnpj',
                'contato');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                'CpfContatoParticipante',
                'cpf',
                'contato');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                'ControleIdProcedimento',
                'id_procedimento',
                'md_lit_controle');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                'ControleIdMdLitTipoControle',
                'id_md_lit_tipo_controle',
                'md_lit_controle');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                'IdProcedimentoMdLitTipoControle',
                'id_procedimento',
                'md_lit_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                'Cpf',
                'cpf');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                'Cnpj',
                'cnpj');


            $this->configurarPK('IdMdLitDadoInteressado', InfraDTO::$TIPO_PK_NATIVA);

            $this->configurarFK('IdMdLitControle', 'md_lit_controle', 'id_md_lit_controle');

            $this->configurarFK('IdContato', 'contato', 'id_contato');

            $this->configurarExclusaoLogica('SinAtivo', 'N');
        }
    }