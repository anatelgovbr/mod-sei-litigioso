<?php
    /**
     * ANATEL
     *
     * 20/05/2016 - criado por alan.campos@castgroup.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitTipoDecisaoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_tipo_decisao';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoDecisaoLitigioso',
                                           'id_md_lit_tipo_decisao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Nome',
                                           'nome');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Descricao',
                                           'descricao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');

            $this->configurarPK('IdTipoDecisaoLitigioso', InfraDTO::$TIPO_PK_NATIVA);

            $this->configurarExclusaoLogica('SinAtivo','N');

            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelEspecieLitigiosoDTO');

        }
    }
