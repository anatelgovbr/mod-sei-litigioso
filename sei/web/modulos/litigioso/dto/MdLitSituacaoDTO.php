<?php
    /**
     * ANATEL
     *
     * 05/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitSituacaoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_situacao';
        }


        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdSituacaoLitigioso',
                                           'id_md_lit_situacao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Nome',
                                           'nome');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinInstauracao',
                                           'sin_instauracao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinConclusiva',
                                           'sin_conclusiva');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinDecisoria',
                                           'sin_decisoria');

           /* $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinSuspensiva',
                                           'sin_suspensiva');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinLivre',
                                           'sin_livre');*/

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'Prazo',
                                           'prazo');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'Ordem',
                                           'ordem');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdFaseLitigioso',
                                           'id_md_lit_fase');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControleLitigioso',
                                           'id_md_lit_tipo_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinIntimacao',
                                           'sin_intimacao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinDefesa',
                                           'sin_defesa');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinRecursal',
                                           'sin_recursal');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                            'SinOpcional',
                                            'sin_opcional');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeFase', 'nome', 'md_lit_fase');
            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoFase', 'sin_ativo', 'md_lit_fase');

            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelSituacaoLitigiosoSerieDTO');

            $this->configurarPK('IdSituacaoLitigioso', InfraDTO::$TIPO_PK_NATIVA);
            $this->configurarFK('IdFaseLitigioso', 'md_lit_fase', 'id_md_lit_fase');
            $this->configurarFK('IdTipoControleLitigioso', 'md_lit_tipo_controle', 'id_md_lit_tipo_controle');


        }


    }
