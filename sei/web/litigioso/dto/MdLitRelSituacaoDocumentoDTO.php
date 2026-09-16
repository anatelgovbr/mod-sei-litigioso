<?php
    /**
     * ANATEL
     *
     * 29/01/2016 - criado por jaqueline.mendes - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelSituacaoDocumentoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'mod_rel_situacao_litigioso_documento';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdsituacaoLitigiosoDocumento',
                                           'id_mod_rel_situacao_litigioso_documento');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdsituacaoLitigioso',
                                           'id_mod_situacao_litigioso');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdDocumento',
                                           'id_documento');

            $this->configurarFK('IdDocumento', 'id_documento', 'documento');
            $this->configurarFK('IdsituacaoLitigioso', 'id_mod_situacao_litigioso', 'mod_situacao_litigioso');


        }
    }
