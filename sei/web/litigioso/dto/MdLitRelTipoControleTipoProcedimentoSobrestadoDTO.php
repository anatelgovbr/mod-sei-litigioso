<?
    /**
     * ANATEL
     *
     * 22/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleTipoProcedimentoSobrestadoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_tp_ctrl_proc_sobres';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControleLitigioso',
                                           'id_md_lit_tipo_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoProcedimento',
                                           'id_tipo_procedimento');

            $this->configurarPK('IdTipoControleLitigioso', InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK('IdTipoProcedimento', InfraDTO::$TIPO_PK_INFORMADO);

            $this->configurarFK('IdTipoProcedimento', 'id_tipo_procedimento', 'tipo_procedimento');
            $this->configurarFK('IdTipoControleLitigioso', 'id_md_lit_tipo_controle', 'md_lit_tipo_controle');

        }
    }
