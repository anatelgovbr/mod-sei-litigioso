<?php
    /**
     * ANATEL
     *
     * 23/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST GROUP
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelEspecieDecisaoObrigacaoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_esp_decisao_obr';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdObrigacaoLitigioso',
                                           'id_md_lit_obrigacao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdEspecieDecisaoLitigioso',
                                           'id_md_lit_especie_decisao');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                      'NomeObrigacao',
                                                      'obrigacao.nome', 'md_lit_obrigacao obrigacao');

            //necessario para viabilizar operação de exclusao via classe BD
            $this->configurarPK('IdEspecieDecisaoLitigioso', InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK('IdObrigacaoLitigioso', InfraDTO::$TIPO_PK_INFORMADO);

            $this->configurarFK('IdObrigacaoLitigioso', 'md_lit_obrigacao obrigacao', 'obrigacao.id_md_lit_obrigacao');
            $this->configurarFK('IdEspecieDecisaoLitigioso', 'md_lit_especie_decisao especie', 'especie.id_md_lit_especie_decisao');

        }
    }
