<?php
    /**
     * ANATEL
     *
     * 10/10/2016 - criado - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitControleDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_controle';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdControleLitigioso',
                                           'id_md_lit_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                           'IdProcedimento',
                                           'id_procedimento');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                           'IdDocumento',
                                           'id_documento');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                            'IdMdLitTipoControle',
                                            'id_md_lit_tipo_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                           'DataInstauracao',
                                           'dta_instauracao');


            $this->configurarPK('IdControleLitigioso', InfraDTO::$TIPO_PK_NATIVA);

            $this->configurarFK('IdMdLitTipoControle', 'md_lit_tipo_controle', 'id_md_lit_controle');

            $this->configurarFK('IdProcedimento', 'procedimento', 'id_procedimento');

            $this->configurarFK('IdDocumento', 'documento', 'id_documento');

        }
    }
