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
        public static $TIPO_MULTA_INTEGRACAO = 1;
        public static $TIPO_MULTA_INDICACAO_VALOR = 2;

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

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'StaTipoIndicacaoMulta',
                                           'sta_tipo_indicacao_multa');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinIndicacaoValor',
                                           'sin_valor');


            $this->configurarPK('IdEspecieLitigioso', InfraDTO::$TIPO_PK_NATIVA);

            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelEspecieLitigiosoDTO');


        }
    }
