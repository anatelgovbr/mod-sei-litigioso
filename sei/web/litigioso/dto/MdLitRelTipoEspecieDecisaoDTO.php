<?php
    /**
     * ANATEL
     *
     * 24/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST GROUP
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoEspecieDecisaoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_tp_especie_dec';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoDecisaoLitigioso',
                                           'id_md_lit_tipo_decisao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdEspecieDecisaoLitigioso',
                                           'id_md_lit_especie_decisao');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                      'NomeEspecie',
                                                      'especie.nome', 'md_lit_especie_decisao especie');

            //necessario para viabilizar operação de exclusao via classe BD
            $this->configurarPK('IdEspecieDecisaoLitigioso', InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK('IdTipoDecisaoLitigioso', InfraDTO::$TIPO_PK_INFORMADO);

            $this->configurarFK('IdTipoDecisaoLitigioso', 'md_lit_tipo_decisao tp', 'tp.id_md_lit_tipo_decisao');
            $this->configurarFK('IdEspecieDecisaoLitigioso', 'md_lit_especie_decisao especie', 'especie.id_md_lit_especie_decisao');

        }
    }
