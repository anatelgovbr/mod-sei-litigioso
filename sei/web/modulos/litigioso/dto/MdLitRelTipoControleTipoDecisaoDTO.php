<?php
    /**
     * ANATEL
     *
     * 01/06/2016 - criado por alan.campos@castrgroup.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleTipoDecisaoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_tipo_ctrl_tipo_dec';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControleLitigioso',
                                           'id_md_lit_tipo_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoDecisaoLitigioso',
                                           'id_md_lit_tipo_decisao');

            $this->configurarPK("IdTipoDecisaoLitigioso", InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK("IdTipoControleLitigioso", InfraDTO::$TIPO_PK_INFORMADO);

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Nome', 'decisao.nome', 'md_lit_tipo_decisao decisao');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoDecisao', 'decisao.sin_ativo', 'md_lit_tipo_decisao decisao');

            $this->configurarFK('IdTipoControleLitigioso', 'md_lit_tipo_controle controle', 'controle.id_md_lit_tipo_controle');
            $this->configurarFK('IdTipoDecisaoLitigioso', 'md_lit_tipo_decisao decisao', 'decisao.id_md_lit_tipo_decisao');

        }
    }
