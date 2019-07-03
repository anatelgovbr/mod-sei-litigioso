<?php
    /**
     * ANATEL
     *
     * 29/03/2016 - criado por jaqueline.mendes - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelDispositivoNormativoTipoControleDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_disp_norm_tipo_ctrl';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdDispositivoNormativo',
                                           'id_md_lit_disp_normat');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControle',
                                           'id_md_lit_tipo_controle');

            //necessario para viabilizar operação de exclusao via classe BD (ver classe MdLitAssociarDispositivoNormativoRN e ver método excluirRelacionamentosControlado)   
            $this->configurarPK("IdDispositivoNormativo", InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK("IdTipoControle", InfraDTO::$TIPO_PK_INFORMADO);

            $this->configurarFK('IdDispositivoNormativo', 'IdDispositivoNormativo', 'md_lit_disp_normat');
            $this->configurarFK('IdTipoControle', 'id_md_lit_tipo_controle', 'md_lit_tipo_controle');


        }
    }
