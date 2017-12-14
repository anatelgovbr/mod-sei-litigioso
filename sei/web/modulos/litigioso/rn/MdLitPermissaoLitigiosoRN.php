<?php
    /**
     *  ANATEL
     *
     *  25/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitPermissaoLitigiosoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        protected function isAdmConectado()
        {
            //a partir do id do usuario consultar se ele é administrador ou nao
            $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();

            $objInfraSip  = new InfraSip(SessaoSEI::getInstance());

            $arrPerfisSip = $objInfraSip->carregarPerfis(SessaoSEI::getInstance()->getNumIdSistema(), $idUsuario, null);

            for ($i = 0; $i < count($arrPerfisSip); $i++) {

                if ($arrPerfisSip[$i][1] == 'Administrador') {
                    return true;
                }

            }

            return false;
        }

        /* Verifica se um determinado usuário foi atribuido como gestor de um determinado tipo de controle  */
        protected function isUsuarioGestorTipoControleConectado($arrParam)
        {

            $idUsuario      = $arrParam[0];
            $idTipoControle = $arrParam[1];

            //consulta APENAS os tipos de processos litigiosos que o usuario logado tenha sido vinculado como gestor
            $sql = "";
            $sql .= " SELECT * FROM md_lit_tipo_controle ";
            $sql .= " WHERE id_md_lit_tipo_controle IN (  ";
            $sql .= "  SELECT id_md_lit_tipo_controle FROM md_lit_rel_tp_controle_usu  ";
            $sql .= "   WHERE id_md_lit_tipo_controle = " . $idTipoControle;
            $sql .= "   AND id_usuario = " . $idUsuario . " )";

            $rs = $this->getObjInfraIBanco()->consultarSql($sql);

            $isGestor = false;

            if (count($rs) > 0) {
                $isGestor = true;
            }

            return $isGestor;

        }
        
        protected function isUsuarioGestorTipoControleDTOConectado($arrParam){
            $objMdTipoControleRN = new MdLitRelTipoControleUsuarioRN();
            $idUsuario           = $arrParam[0];
            $idTipoControle      = $arrParam[1];
            
            $objMdTipoControleDTO = new MdLitRelTipoControleUsuarioDTO();
            $objMdTipoControleDTO->retNumIdTipoControleLitigioso();
            $objMdTipoControleDTO->setNumIdTipoControleLitigioso($idTipoControle);
            $objMdTipoControleDTO->setNumIdUsuario($idUsuario);

            $count = $objMdTipoControleRN->contar($objMdTipoControleDTO);

            return $count > 0;
        }


    }
