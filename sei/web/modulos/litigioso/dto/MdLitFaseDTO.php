<?php
    /**
     * ANATEL
     *
     * 04/02/2016 - criado por marcelo.bezerra@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitFaseDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_fase';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdFaseLitigioso',
                                           'id_md_lit_fase');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Nome',
                                           'nome');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Descricao',
                                           'descricao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControleLitigioso',
                                           'id_md_lit_tipo_controle');

            $this->configurarPK('IdFaseLitigioso', InfraDTO::$TIPO_PK_NATIVA);
            $this->configurarFK('IdTipoControleLitigioso', 'id_md_lit_tipo_controle', 'md_lit_tipo_controle');

        }
    }
