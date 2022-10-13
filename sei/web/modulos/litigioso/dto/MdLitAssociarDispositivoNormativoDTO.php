<?php
    /**
     * ANATEL
     *
     * 22/03/2016 - criado por felipe.rodrigues@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitAssociarDispositivoNormativoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_disp_norm_tipo_ctrl';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControleLitigioso',
                                           'id_md_lit_tipo_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdDispositivoNormativoLitigioso',
                                           'id_md_lit_disp_normat');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Dispositivo', 'disp.dispositivo', 'md_lit_disp_normat disp');
            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Norma', 'disp.norma', 'md_lit_disp_normat disp');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoDispositivo', 'disp.sin_ativo', 'md_lit_disp_normat disp');

            $this->configurarFK('IdTipoControleLitigioso', 'md_lit_tipo_controle tipo', 'tipo.id_md_lit_tipo_controle');
            $this->configurarFK('IdDispositivoNormativoLitigioso', 'md_lit_disp_normat disp', 'disp.id_md_lit_disp_normat');

        }
    }
