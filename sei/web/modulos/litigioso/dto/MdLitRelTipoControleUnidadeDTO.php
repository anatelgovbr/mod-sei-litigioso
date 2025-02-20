<?php
    /**
     * ANATEL
     *
     * 10/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleUnidadeDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_tp_controle_unid';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControleLitigioso',
                                           'id_md_lit_tipo_controle');
    
            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdUnidade',
                                           'id_unidade');

            $this->configurarPK('IdTipoControleLitigioso', InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK('IdUnidade', InfraDTO::$TIPO_PK_INFORMADO);

            $this->configurarFK('IdUnidade', 'id_unidade', 'unidade');
            $this->configurarFK('IdTipoControleLitigioso', 'md_lit_tipo_controle', 'id_md_lit_tipo_controle');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaTipoControleLitigioso','sigla','md_lit_tipo_controle');

        }
    }
