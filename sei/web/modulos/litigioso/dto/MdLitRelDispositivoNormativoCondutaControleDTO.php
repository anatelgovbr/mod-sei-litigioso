<?php
    /**
     * ANATEL
     *
     * 10/10/2016 - criado - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelDispositivoNormativoCondutaControleDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_dis_nor_con_ctr';
        }

        public function montar()
        {


            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdDispositivoNormativoNormaCondutaControle',
                                           'id_md_lit_rel_dis_nor_con_ctr');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdDispositivoNormativoLitigioso',
                                           'id_md_lit_disp_normat');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdCondutaLitigioso',
                                           'id_md_lit_conduta');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdControleLitigioso',
                                           'id_md_lit_controle');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Dispositivo', 'dispositivo', 'md_lit_disp_normat');
            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Norma', 'norma', 'md_lit_disp_normat');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Conduta', 'nome', 'md_lit_conduta');

            $this->configurarPK('IdDispositivoNormativoNormaCondutaControle', InfraDTO::$TIPO_PK_NATIVA);
            $this->configurarFK('IdDispositivoNormativoLitigioso', 'md_lit_disp_normat', 'id_md_lit_disp_normat');
            $this->configurarFK('IdCondutaLitigioso', 'md_lit_conduta', 'id_md_lit_conduta', InfraDTO::$TIPO_FK_OPCIONAL);


            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjMdLitDecisao');


        }

        public function getStrInfracao(){
            $infracao = '';
            if($this->isSetStrDispositivo()){
                $infracao .= $this->getStrDispositivo();
            }
            if($this->isSetStrNorma() && $this->getStrNorma() != ''){
                $infracao .= '/'.$this->getStrNorma();
            }
            if($this->isSetStrConduta() && $this->getStrConduta() != ''){
                $infracao .= '/'.$this->getStrConduta();
            }

            return $infracao;
        }

    }
