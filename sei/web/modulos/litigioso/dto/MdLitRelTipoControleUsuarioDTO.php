<?php
    /**
     * ANATEL
     *
     * 10/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelTipoControleUsuarioDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_rel_tp_controle_usu';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdTipoControleLitigioso',
                                           'id_md_lit_tipo_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdUsuario',
                                           'id_usuario');

            $this->configurarPK('IdTipoControleLitigioso', InfraDTO::$TIPO_PK_INFORMADO);
            $this->configurarPK('IdUsuario', InfraDTO::$TIPO_PK_INFORMADO);

            $this->configurarFK('IdUsuario', 'id_usuario', 'usuario');
            $this->configurarFK('IdTipoControleLitigioso', 'id_md_lit_tipo_controle', 'md_lit_tipo_controle');

        }
    }
