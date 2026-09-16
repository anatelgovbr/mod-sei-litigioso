<?
    /**
     * ANATEL
     *
     * 16/05/2016 - criado por CAST
     *
     * Versão do Gerador de Código:
     *
     * Versão no CVS:
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitObrigacaoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_obrigacao';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdObrigacaoLitigioso',
                                           'id_md_lit_obrigacao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Nome',
                                           'nome');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Descricao',
                                           'descricao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');

            $this->configurarPK('IdObrigacaoLitigioso', InfraDTO::$TIPO_PK_NATIVA);

        }
    }
