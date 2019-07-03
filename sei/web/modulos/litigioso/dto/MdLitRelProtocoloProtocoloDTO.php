<?
    /**
     * ANATEL
     *
     * 25/10/2016 - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelProtocoloProtocoloDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_protoco_protoco';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                           'IdRelProtocoloProtocolo',
                                           'id_md_lit_rel_protoco_protoco');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdControleLitigioso',
                                           'id_md_lit_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                           'IdProtocolo1',
                                           'id_protocolo_1');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                           'IdProtocolo2',
                                           'id_protocolo_2');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                           'IdDocumento',
                                           'id_documento');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                           'DtaSobrestamento',
                                           'dta_sobrestamento');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                      'ProtocoloFormatadoDocumento',
                                                      'protocolo_doc.protocolo_formatado',
                                                      'protocolo protocolo_doc');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                      'ProtocoloFormatadoProcedimento2',
                                                      'protocolo_proc.protocolo_formatado',
                                                      'protocolo protocolo_proc');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                                      'IdTipoProcedimento2',
                                                      'id_tipo_procedimento',
                                                      'procedimento');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                      'TipoProcedimento2',
                                                      'nome',
                                                      'tipo_procedimento');


            $this->configurarPK('IdRelProtocoloProtocolo', InfraDTO::$TIPO_PK_NATIVA);

            $this->configurarFK('IdDocumento', 'protocolo protocolo_doc', 'protocolo_doc.id_protocolo');

            $this->configurarFK('IdProtocolo2', 'protocolo protocolo_proc', 'protocolo_proc.id_protocolo');
            $this->configurarFK('IdProtocolo2', 'procedimento', 'id_procedimento');

            $this->configurarFK('IdTipoProcedimento2', 'tipo_procedimento', 'id_tipo_procedimento');


        }
    }
