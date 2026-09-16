<?php
    /**
     * ANATEL
     *
     * 29/03/2016 - criado por jaqueline.mendes - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelDispositivoNormativoCondutaDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_disp_norm_conduta';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdDispositivoNormativo',
                                           'id_md_lit_disp_normat');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdConduta',
                                           'id_md_lit_conduta');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                      'NomeConduta',
                                                      'conduta.nome', 'md_lit_conduta conduta');

            $this->configurarPK("IdDispositivoNormativo", InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK("IdConduta", InfraDTO::$TIPO_PK_INFORMADO);

            $this->configurarFK('IdDispositivoNormativo', 'md_lit_disp_normat disp', 'disp.id_md_lit_disp_normat');
            $this->configurarFK('IdConduta', 'md_lit_conduta conduta', 'conduta.id_md_lit_conduta');

        }
    }
