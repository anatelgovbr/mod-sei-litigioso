<?
    /**
     * ANATEL
     *
     * 19/01/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitTipoControleDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_tipo_controle';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControleLitigioso',
                                           'id_md_lit_tipo_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Sigla',
                                           'sigla');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Descricao',
                                           'descricao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                                    'DtaCorte',
                                                    'dta_corte');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                'SinParamModalComplInteressado',
                'sin_param_modal_compl_interes');

            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleLitigiosoUsuarioDTO');
            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleLitigiosoMotivoDTO');
            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleLitigiosoTipoProcedimentoDTO');
            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleLitigiosoUnidadeDTO');
            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO');



            $this->configurarFK('IdTipoControleLitigioso','md_lit_mapea_param_valor','id_md_lit_tipo_controle');


            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                                      'IdMdLitMapearParamEntrada',
                                                      'id_md_lit_mapea_param_entrada',
                                                      'md_lit_mapea_param_valor');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                                      'IdMdLitMapeaParamValor',
                                                      'id_md_lit_mapea_param_valor',
                                                      'md_lit_mapea_param_valor');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                      'ValorDefault',
                                                      'valor_default',
                                                      'md_lit_mapea_param_valor');


            $this->configurarPK('IdTipoControleLitigioso', InfraDTO::$TIPO_PK_NATIVA);

        }
    }
