<?php
    /**
     * ANATEL
     *
     * 17/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelSituacaoSerieDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_sit_serie';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdSituacaoLitigioso',
                                           'id_md_lit_situacao');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdSerie',
                                           'id_serie');

            $this->configurarPK("IdSituacaoLitigioso", InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK("IdSerie", InfraDTO::$TIPO_PK_INFORMADO);

            $this->configurarFK('IdSerie', 'id_serie', 'serie');
            $this->configurarFK('IdSituacaoLitigioso', 'id_md_lit_situacao', 'md_lit_situacao');


        }
    }
