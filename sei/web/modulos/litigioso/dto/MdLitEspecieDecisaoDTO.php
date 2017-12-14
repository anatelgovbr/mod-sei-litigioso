<?php
    /**
     * ANATEL
     *
     * 20/05/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitEspecieDecisaoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_especie_decisao';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdEspecieLitigioso',
                                           'id_md_lit_especie_decisao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Nome',
                                           'nome');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinGestaoMulta',
                                           'sin_rd_gestao_multa');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinIndicacaoPrazo',
                                           'sin_rd_indicacao_prazo');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinIndicacaoObrigacoes',
                                           'sin_rd_indicacao_obrigacoes');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');


            $this->configurarPK('IdEspecieLitigioso', InfraDTO::$TIPO_PK_NATIVA);

            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelEspecieLitigiosoDTO');


        }
    }
