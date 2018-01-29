<?
    /**
     * ANATEL
     *
     * 11/03/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../web/Sip.php';

    class MdLitAtualizadorSipRN extends InfraRN
    {

        private $numSeg                           = 0;
        private $versaoAtualDesteModulo           = '0.0.4';
        private $nomeParametroModulo              = 'VERSAO_MODULO_LITIGIOSO';
        private $nomeGestorControleLitigioso      = "Gestor de Controle Litigioso";
        private $descricaoGestorControleLitigioso = "Acesso aos recursos específicos de Gestor de Controle Litigioso do módulo Litigioso do SEI.";

        public function __construct()
        {
            parent::__construct();
            $this->inicializar(' SIP - INICIALIZAR ');
        }

        private function inicializar($strTitulo)
        {

            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '-1');

            try {
                @ini_set('zlib.output_compression', '0');
                @ini_set('implicit_flush', '1');
            } catch (Exception $e) {
            }

            ob_implicit_flush();

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(true);
            InfraDebug::getInstance()->setBolEcho(true);
            InfraDebug::getInstance()->limpar();

            $this->numSeg = InfraUtil::verificarTempoProcessamento();

            $this->logar($strTitulo);
        }

        private function logar($strMsg)
        {
            InfraDebug::getInstance()->gravar($strMsg);
            flush();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSip::getInstance();
        }

	protected function atualizarVersaoConectado(){
		
		try {
            $this->inicializar('INICIANDO ATUALIZACAO DO MODULO '. $this->nomeDesteModulo .' NO SIP VERSAO '.SIP_VERSAO);

            //testando versao do framework
            $numVersaoInfraRequerida = '1.385';
            $versaoInfraFormatada = (int) str_replace('.','', VERSAO_INFRA);
            $versaoInfraReqFormatada = (int) str_replace('.','', $numVersaoInfraRequerida);
            
            if ($versaoInfraFormatada < $versaoInfraReqFormatada){
            	$this->finalizar('VERSAO DO FRAMEWORK PHP INCOMPATIVEL (VERSAO ATUAL '.VERSAO_INFRA.', SENDO REQUERIDA VERSAO IGUAL OU SUPERIOR A '.$numVersaoInfraRequerida.')',true);
            }

                if ($versaoInfraFormatada < $versaoInfraReqFormatada){

                    $this->finalizar('VERSAO DO FRAMEWORK PHP INCOMPATIVEL (VERSAO ATUAL '.VERSAO_INFRA.', SENDO REQUERIDA VERSAO IGUAL OU SUPERIOR A '.$numVersaoInfraRequerida.')',true);
                } 

                //checando BDs suportados
                if (!(BancoSip::getInstance() instanceof InfraMySql) &&
                    !(BancoSip::getInstance() instanceof InfraSqlServer) &&
                    !(BancoSip::getInstance() instanceof InfraOracle)
                ) {
                    $this->finalizar('BANCO DE DADOS NAO SUPORTADO: ' . get_parent_class(BancoSip::getInstance()), true);
                }

                //checando permissoes na base de dados
                $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());

                if (count($objInfraMetaBD->obterTabelas('sip_teste')) == 0) {
                    BancoSip::getInstance()->executarSql('CREATE TABLE sip_teste (id ' . $objInfraMetaBD->tipoNumero() . ' null)');
                }

                BancoSip::getInstance()->executarSql('DROP TABLE sip_teste');

                //checando qual versao instalar
                $objInfraParametro = new InfraParametro(BancoSip::getInstance());

                $strVersaoModuloLitigioso = $objInfraParametro->getValor($this->nomeParametroModulo, false);

                if (InfraString::isBolVazia($strVersaoModuloLitigioso)) {

                    //aplica atualizaçoes da versao 001, 002 e 003
                    $this->instalarv001();
                    $this->instalarv002();
                    $this->instalarv003();
                    $this->instalarv004();

                    //adicionando parametro para controlar versao do modulo
                    BancoSip::getInstance()->executarSql('insert into infra_parametro (valor, nome ) VALUES( \'' . $this->versaoAtualDesteModulo . '\',  \'' . $this->nomeParametroModulo . '\' )');
                    $this->logar('ATUALIZAÇÔES DO MÓDULO LITIGIOSO NA BASE DO SIP REALIZADAS COM SUCESSO');
                    //$this->finalizar('SIP - MÓDULO LITIGIOSO JÁ INSTALADO',true);

                } else if ($strVersaoModuloLitigioso == '0.0.1') {

                    //instalar apenas a v.002 e a v003
                    $this->instalarv002();
                    $this->instalarv003();
                    $this->instalarv004();

                    //atualizando versao do parametro para controlar versao do modulo
                    BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'' . $this->versaoAtualDesteModulo . '\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

                    $this->logar('ATUALIZAÇÔES DO MÓDULO LITIGIOSO NA BASE DO SIP REALIZADAS COM SUCESSO');

                } else if ($strVersaoModuloLitigioso == '0.0.2') {

                    //instalar apenas a v003 e v004
                    $this->instalarv003();
                    $this->instalarv004();

                    //atualizando versao do parametro para controlar versao do modulo
                    BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'' . $this->versaoAtualDesteModulo . '\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

                    $this->logar('ATUALIZAÇÔES DO MÓDULO LITIGIOSO NA BASE DO SIP REALIZADAS COM SUCESSO');

                } else if ($strVersaoModuloLitigioso == '0.0.3') {

                    //instalar apenas a v004
                    $this->instalarv004();

                    //atualizando versao do parametro para controlar versao do modulo
                    BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'' . $this->versaoAtualDesteModulo . '\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

                    $this->logar('ATUALIZAÇÔES DO MÓDULO LITIGIOSO NA BASE DO SIP REALIZADAS COM SUCESSO');

                } else {

                    //$this->logar('MÓDULO LITIGIOSO JÁ INSTALADO');
                    $this->logar('SIP - MÓDULO LITIGIOSO v0.0.4 JÁ INSTALADO');
                    $this->finalizar('FIM', true);
                }

                //BancoSip::getInstance()->executarSql('update infra_parametro set valor=\''.SIP_VERSAO.'\' where nome=\'SIP_VERSAO\'');
                //$this->logar("SIP - FIM");
                $this->finalizar('FIM', false);

            } catch (Exception $e) {

                InfraDebug::getInstance()->setBolLigado(false);
                InfraDebug::getInstance()->setBolDebugInfra(false);
                InfraDebug::getInstance()->setBolEcho(false);
                throw new InfraException('Erro atualizando versão.', $e);

            }

        }

        /* Contem atualizaçoes da versao 0.0.1 do modulo */

        private function finalizar($strMsg = null, $bolErro)
        {

            if (!$bolErro) {
                $this->numSeg = InfraUtil::verificarTempoProcessamento($this->numSeg);
                $this->logar('TEMPO TOTAL DE EXECUÇÃO: ' . $this->numSeg . ' s');
            } else {
                $strMsg = 'ERRO: ' . $strMsg;
            }

            if ($strMsg != null) {
                $this->logar($strMsg);
            }

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            $this->numSeg = 0;
            die;
        }

        /* Contem atualizaçoes da versao 0.0.2 do modulo */

        protected function instalarv001()
        {

            $objSistemaRN  = new SistemaRN();
            $objPerfilRN   = new PerfilRN();
            $objMenuRN     = new MenuRN();
            $objItemMenuRN = new ItemMenuRN();
            $objRecursoRN  = new RecursoRN();

            $objSistemaDTO = new SistemaDTO();
            $objSistemaDTO->retNumIdSistema();
            $objSistemaDTO->setStrSigla('SEI');

            $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

            if ($objSistemaDTO == null) {
                throw new InfraException('Sistema SEI não encontrado.');
            }

            $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

            //cadastrando o novo perfil do gestor de controle litigioso
            $objPerfilDTOGestorLitigioso = new PerfilDTO();
            $objPerfilDTOGestorLitigioso->retNumIdPerfil();
            $objPerfilDTOGestorLitigioso->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTOGestorLitigioso->setStrNome($this->nomeGestorControleLitigioso);
            $objPerfilDTOGestorLitigioso->setStrDescricao($this->descricaoGestorControleLitigioso);
            $objPerfilDTOGestorLitigioso->setStrSinCoordenado('N');
            $objPerfilDTOGestorLitigioso->setStrSinAtivo('S');
            $objPerfilDTOGestorLitigioso = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome($this->nomeGestorControleLitigioso);
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Gestor de Controle Litigioso do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiGestorLitigioso = $objPerfilDTO->getNumIdPerfil();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Administrador');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Administrador do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Básico');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Informática');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Informática do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiInformatica = $objPerfilDTO->getNumIdPerfil();

            $objMenuDTO = new MenuDTO();
            $objMenuDTO->retNumIdMenu();
            $objMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objMenuDTO->setStrNome('Principal');
            $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

            if ($objMenuDTO == null) {
                throw new InfraException('Menu do sistema SEI não encontrado.');
            }

            $numIdMenuSei = $objMenuDTO->getNumIdMenu();

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Administração');
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {
                throw new InfraException('Item de menu Administração do sistema SEI não encontrado.');
            }

            $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Usuários');
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {
                throw new InfraException('Item de menu Administração/Usuários do sistema SEI não encontrado.');
            }

            $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

            //SEI ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO MÓDULO LITIGIOSO NA BASE DO SIP...');

            //criando os recursos e vinculando-os aos perfil Administrador
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_situacao_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_processo_situacao_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_processo_situacao_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_situacao_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_situacao_listar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_decisao_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_decisao_cadastrar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_lancamento_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_lancamento_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_lancamento_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_lancamento_alterar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_multa_justificativa');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_multa_cancelar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_cancela_lancamento_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_cancela_lancamento_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_cancela_lancamento_listar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_historic_lancamento_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_historic_lancamento_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_historic_lancamento_consultar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decis_lancament_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decis_lancament_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decis_lancament_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decis_lancament_excluir');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_modalidade_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_lancamento_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_lancamento_consultar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_excluir');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_listar');

            //criando Administração -> Controle de Processos Litigiosos
            $objItemMenuDTOControleProcesso       = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);
            $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);

            //criando Administração -> Controle de Processos Litigiosos -> Tipos de Controles Litigiosos
            $this->adicionarItemMenu($numIdSistemaSei,
                                     $numIdPerfilSeiAdministrador,
                                     $numIdMenuSei,
                                     $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
                                     $objRecursoDTO->getNumIdRecurso(),
                                     'Tipos de Controles Litigiosos',
                                     10);

            $this->adicionarItemMenu($numIdSistemaSei,
                                     $numIdPerfilSeiGestorLitigioso,
                                     $numIdMenuSei,
                                     $objItemMenuDTOControleProcessoBasico->getNumIdItemMenu(),
                                     $objRecursoDTO->getNumIdRecurso(),
                                     'Tipos de Controles Litigiosos',
                                     10);

            //$this->removerRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiInformatica, 'atributo_consultar');

            //$this->adicionarRecursoPerfil($numIdSistemaSei,$numIdPerfilSeiAdministrador,'usuario_alterar');

            //$this->removerRecurso($numIdSistemaSei,'rel_unidade_serie_alterar');

            $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
            $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
            $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
            $objRegraAuditoriaDTO->setStrDescricao('Geral');

            $objRegraAuditoriaRN  = new RegraAuditoriaRN();
            $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);

            $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema=' . $numIdSistemaSei . ' and nome in (
       \'md_lit_tipo_processo_alterar\',
      \'md_lit_tipo_processo_cadastrar\',
      \'md_lit_tipo_processo_desativar\',
      \'md_lit_tipo_processo_reativar\',
      \'md_lit_tipo_processo_excluir\')'
            );


            //CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS RECEM ADICIONADOS
            foreach ($rs as $recurso) {
                BancoSip::getInstance()->executarSql('insert into rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
            }

            $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
            $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
            $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

            $objSistemaRN = new SistemaRN();
            $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        }

        /* Contem atualizaçoes da versao 0.0.3 do modulo */

        private function adicionarRecursoPerfil($numIdSistema, $numIdPerfil, $strNome, $strCaminho = null)
        {

            $objRecursoDTO = new RecursoDTO();
            $objRecursoDTO->retNumIdRecurso();
            $objRecursoDTO->setNumIdSistema($numIdSistema);
            $objRecursoDTO->setStrNome($strNome);

            $objRecursoRN  = new RecursoRN();
            $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

            if ($objRecursoDTO == null) {

                $objRecursoDTO = new RecursoDTO();
                $objRecursoDTO->setNumIdRecurso(null);
                $objRecursoDTO->setNumIdSistema($numIdSistema);
                $objRecursoDTO->setStrNome($strNome);
                $objRecursoDTO->setStrDescricao(null);

                if ($strCaminho == null) {
                    $objRecursoDTO->setStrCaminho('controlador.php?acao=' . $strNome);
                } else {
                    $objRecursoDTO->setStrCaminho($strCaminho);
                }

                $objRecursoDTO->setStrSinAtivo('S');
                $objRecursoDTO = $objRecursoRN->cadastrar($objRecursoDTO);
            }

            if ($numIdPerfil != null) {
                $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
                $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
                $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

                $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

                if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO) == 0) {
                    $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
                }
            }

            return $objRecursoDTO;
        }

        //v004 - INICIO

        /* Contem atualizaçoes da versao 0.0.4 do modulo */

        private function adicionarItemMenu($numIdSistema, $numIdPerfil, $numIdMenu, $numIdItemMenuPai, $numIdRecurso, $strRotulo, $numSequencia)
        {

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdMenu($numIdMenu);

            if ($numIdItemMenuPai == null) {
                $objItemMenuDTO->setNumIdMenuPai(null);
                $objItemMenuDTO->setNumIdItemMenuPai(null);
            } else {
                $objItemMenuDTO->setNumIdMenuPai($numIdMenu);
                $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
            }

            $objItemMenuDTO->setNumIdSistema($numIdSistema);
            $objItemMenuDTO->setNumIdRecurso($numIdRecurso);
            $objItemMenuDTO->setStrRotulo($strRotulo);

            $objItemMenuRN  = new ItemMenuRN();
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {

                $objItemMenuDTO = new ItemMenuDTO();
                $objItemMenuDTO->setNumIdItemMenu(null);
                $objItemMenuDTO->setNumIdMenu($numIdMenu);

                if ($numIdItemMenuPai == null) {
                    $objItemMenuDTO->setNumIdMenuPai(null);
                    $objItemMenuDTO->setNumIdItemMenuPai(null);
                } else {
                    $objItemMenuDTO->setNumIdMenuPai($numIdMenu);
                    $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
                }

                $objItemMenuDTO->setNumIdSistema($numIdSistema);
                $objItemMenuDTO->setNumIdRecurso($numIdRecurso);
                $objItemMenuDTO->setStrRotulo($strRotulo);
                $objItemMenuDTO->setStrDescricao(null);
                $objItemMenuDTO->setNumSequencia($numSequencia);
                $objItemMenuDTO->setStrSinNovaJanela('N');
                $objItemMenuDTO->setStrSinAtivo('S');
                $objItemMenuDTO = $objItemMenuRN->cadastrar($objItemMenuDTO);
            }


            if ($numIdPerfil != null && $numIdRecurso != null) {

                $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
                $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
                $objRelPerfilRecursoDTO->setNumIdRecurso($numIdRecurso);

                $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

                if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO) == 0) {
                    $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
                }

                $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
                $objRelPerfilItemMenuDTO->setNumIdPerfil($numIdPerfil);
                $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilItemMenuDTO->setNumIdRecurso($numIdRecurso);
                $objRelPerfilItemMenuDTO->setNumIdMenu($numIdMenu);
                $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

                $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();

                if ($objRelPerfilItemMenuRN->contar($objRelPerfilItemMenuDTO) == 0) {
                    $objRelPerfilItemMenuRN->cadastrar($objRelPerfilItemMenuDTO);
                }
            }

            return $objItemMenuDTO;
        }

        //v004 - Fim

        protected function instalarv002()
        {

            $objSistemaRN  = new SistemaRN();
            $objPerfilRN   = new PerfilRN();
            $objMenuRN     = new MenuRN();
            $objItemMenuRN = new ItemMenuRN();
            $objRecursoRN  = new RecursoRN();

            $objSistemaDTO = new SistemaDTO();
            $objSistemaDTO->retNumIdSistema();
            $objSistemaDTO->setStrSigla('SEI');

            $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

            if ($objSistemaDTO == null) {
                throw new InfraException('Sistema SEI não encontrado.');
            }

            $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Administrador');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Administrador do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Básico');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome($this->nomeGestorControleLitigioso);
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {

                //cadastra o novo perfil
                $objPerfilDTOGestorLitigioso = new PerfilDTO();

                $objPerfilDTOGestorLitigioso->retNumIdPerfil();
                $objPerfilDTOGestorLitigioso->retNumIdSistema();
                $objPerfilDTOGestorLitigioso->retStrNome();
                $objPerfilDTOGestorLitigioso->retStrSinAtivo();
                $objPerfilDTOGestorLitigioso->retStrSinCoordenado();

                $objPerfilDTOGestorLitigioso->setNumIdSistema($numIdSistemaSei);
                $objPerfilDTOGestorLitigioso->setStrNome($this->nomeGestorControleLitigioso);
                $objPerfilDTOGestorLitigioso->setStrDescricao($this->descricaoGestorControleLitigioso);
                $objPerfilDTOGestorLitigioso->setStrSinCoordenado('N');
                $objPerfilDTOGestorLitigioso->setStrSinAtivo('S');

                $objPerfilDTOGestorLitigioso   = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);
                $numIdPerfilSeiGestorLitigioso = $objPerfilDTOGestorLitigioso->getNumIdPerfil();

            } else {

                $numIdPerfilSeiGestorLitigioso = $objPerfilDTO->getNumIdPerfil();
            }

            $objMenuDTO = new MenuDTO();
            $objMenuDTO->retNumIdMenu();
            $objMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objMenuDTO->setStrNome('Principal');
            $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

            if ($objMenuDTO == null) {
                throw new InfraException('Menu do sistema SEI não encontrado.');
            }

            $numIdMenuSei = $objMenuDTO->getNumIdMenu();

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Administração');
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {
                throw new InfraException('Item de menu Administração do sistema SEI não encontrado.');
            }

            $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Usuários');
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {
                throw new InfraException('Item de menu Administração/Usuários do sistema SEI não encontrado.');
            }

            $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

            //SEI ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO MÓDULO LITIGIOSO NA BASE DO SIP... v0.0.2');

            //======================================================================
            //criando os recursos e vinculando-os aos perfil Administrador e basico
            //======================================================================
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_parametrizar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_situacao_listar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_fase_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_fase_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_fase_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_fase_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_fase_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_fase_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_fase_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_fase_listar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_controle_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_controle_consultar');
            $objRecursoDTOTipoControleGestor = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_controle_listar');

            //======================================================================
            //adicionando os mesmos recursos ao perfil administrador
            //======================================================================
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_parametrizar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_listar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_fase_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_fase_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_fase_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_fase_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_fase_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_fase_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_fase_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_fase_listar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_excluir');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_controle_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_controle_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_controle_consultar');
            $objRecursoDTOTipoControle = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_listar');

            //criando Administração -> Controle de Processos Litigiosos
            $objItemMenuDTOControleProcesso       = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);
            $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);

            //criando Administração -> Controle de Processos Litigiosos -> Tipos de Controles Litigiosos
            $this->adicionarItemMenu($numIdSistemaSei,
                                     $numIdPerfilSeiAdministrador,
                                     $numIdMenuSei,
                                     $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
                                     $objRecursoDTOTipoControle->getNumIdRecurso(),
                                     'Tipos de Controles Litigiosos',
                                     10);

            $this->adicionarItemMenu($numIdSistemaSei,
                                     $numIdPerfilSeiGestorLitigioso,
                                     $numIdMenuSei,
                                     $objItemMenuDTOControleProcessoBasico->getNumIdItemMenu(),
                                     $objRecursoDTOTipoControleGestor->getNumIdRecurso(),
                                     'Tipos de Controles Litigiosos',
                                     10);

            $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
            $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
            $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
            $objRegraAuditoriaDTO->setStrDescricao('Geral');

            $objRegraAuditoriaRN  = new RegraAuditoriaRN();
            $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);


            $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema=' . $numIdSistemaSei . ' and nome in (

  	    \'md_lit_situacao_parametrizar\',
  	
  	    \'md_lit_situacao_alterar\',
        \'md_lit_situacao_cadastrar\',
        \'md_lit_situacao_desativar\',
        \'md_lit_situacao_reativar\',
        \'md_lit_situacao_excluir\',
  	
	    \'md_lit_tipo_controle_alterar\',
	    \'md_lit_tipo_controle_cadastrar\',
	    \'md_lit_tipo_controle_desativar\',
	    \'md_lit_tipo_controle_reativar\',
	    \'md_lit_tipo_controle_excluir\',
	
  	    \'md_lit_fase_alterar\',
        \'md_lit_fase_cadastrar\',
        \'md_lit_fase_desativar\',
        \'md_lit_fase_reativar\',
        \'md_lit_fase_excluir\')'

            );

            //CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS RECEM ADICIONADOS
            foreach ($rs as $recurso) {
                BancoSip::getInstance()->executarSql('insert into rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
            }

            $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
            $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
            $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

            $objSistemaRN = new SistemaRN();
            $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

            //por ultimo, excluir do SIP os recurso tipo_processo_litigioso*
            $rsRecursosTipoProcesso = BancoSip::getInstance()->consultarSql("select id_recurso from recurso where id_sistema = " . $numIdSistemaSei . " 
	  		                                       and nome like '%md_lit_tipo_processo_%'");

            if ($rsRecursosTipoProcesso != null && count($rsRecursosTipoProcesso) > 0) {

                foreach ($rsRecursosTipoProcesso as $recurso) {

                    //1- remover os menus
                    BancoSip::getInstance()->executarSql('delete from rel_perfil_item_menu 
		  		                                 WHERE id_sistema = ' . $numIdSistemaSei . ' 
		  		                                 AND id_recurso = ' . $recurso['id_recurso']);

                    BancoSip::getInstance()->executarSql('delete from item_menu
		  		                                 WHERE id_sistema = ' . $numIdSistemaSei . '
		  		                                 AND id_recurso = ' . $recurso['id_recurso']);

                    //excluir o recurso das regras de auditoria
                    BancoSip::getInstance()->executarSql('delete from rel_regra_auditoria_recurso
		  		                                 WHERE id_sistema = ' . $numIdSistemaSei . '
		  		                                 AND id_recurso = ' . $recurso['id_recurso']);


                    BancoSip::getInstance()->executarSql('delete from recurso_vinculado
		  	 		WHERE id_sistema = ' . $numIdSistemaSei . '
		  	 		AND id_recurso = ' . $recurso['id_recurso']);

                    BancoSip::getInstance()->executarSql('delete from rel_perfil_recurso
		  	 		WHERE id_sistema = ' . $numIdSistemaSei . '
		  	 		AND id_recurso = ' . $recurso['id_recurso']);

                    //excluir de fato o recurso
                    BancoSip::getInstance()->executarSql('delete from recurso
		  		                                 WHERE id_sistema = ' . $numIdSistemaSei . '
		  		                                 AND id_recurso = ' . $recurso['id_recurso']);
                }
            }

        }

        protected function instalarv003()
        {

            $objSistemaRN  = new SistemaRN();
            $objPerfilRN   = new PerfilRN();
            $objMenuRN     = new MenuRN();
            $objItemMenuRN = new ItemMenuRN();
            $objRecursoRN  = new RecursoRN();

            $objSistemaDTO = new SistemaDTO();
            $objSistemaDTO->retNumIdSistema();
            $objSistemaDTO->setStrSigla('SEI');

            $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

            if ($objSistemaDTO == null) {
                throw new InfraException('Sistema SEI não encontrado.');
            }

            $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Administrador');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Administrador do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();


            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Básico');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome($this->nomeGestorControleLitigioso);
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {

                //cadastra o novo perfil
                $objPerfilDTOGestorLitigioso = new PerfilDTO();

                $objPerfilDTOGestorLitigioso->retNumIdPerfil();
                $objPerfilDTOGestorLitigioso->retNumIdSistema();
                $objPerfilDTOGestorLitigioso->retStrNome();
                $objPerfilDTOGestorLitigioso->retStrSinAtivo();
                $objPerfilDTOGestorLitigioso->retStrSinCoordenado();

                $objPerfilDTOGestorLitigioso->setNumIdSistema($numIdSistemaSei);
                $objPerfilDTOGestorLitigioso->setStrNome($this->nomeGestorControleLitigioso);
                $objPerfilDTOGestorLitigioso->setStrDescricao($this->descricaoGestorControleLitigioso);
                $objPerfilDTOGestorLitigioso->setStrSinCoordenado('N');
                $objPerfilDTOGestorLitigioso->setStrSinAtivo('S');

                $objPerfilDTOGestorLitigioso   = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);
                $numIdPerfilSeiGestorLitigioso = $objPerfilDTOGestorLitigioso->getNumIdPerfil();

            } else {

                $numIdPerfilSeiGestorLitigioso = $objPerfilDTO->getNumIdPerfil();
            }

            $objMenuDTO = new MenuDTO();
            $objMenuDTO->retNumIdMenu();
            $objMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objMenuDTO->setStrNome('Principal');
            $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

            if ($objMenuDTO == null) {
                throw new InfraException('Menu do sistema SEI não encontrado.');
            }

            $numIdMenuSei = $objMenuDTO->getNumIdMenu();

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Administração');
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {
                throw new InfraException('Item de menu Administração do sistema SEI não encontrado.');
            }

            $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Usuários');
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {
                throw new InfraException('Item de menu Administração/Usuários do sistema SEI não encontrado.');
            }

            $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

            //SEI ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO MÓDULO LITIGIOSO NA BASE DO SIP... v0.0.3');

            //======================================================================
            //criando os recursos e vinculando-os aos perfil Administrador e basico
            //======================================================================

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_associar_dispositivo_normativo_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_associar_dispositivo_normativo_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_associar_dispositivo_normativo_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_associar_dispositivo_normativo_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_associar_dispositivo_normativo_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_associar_dispositivo_normativo_listar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_dispositivo_normativo_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_dispositivo_normativo_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_dispositivo_normativo_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_dispositivo_normativo_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_dispositivo_normativo_cadastrar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_conduta_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_conduta_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_conduta_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_conduta_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_conduta_desativar');

            //======================================================================
            //adicionando os mesmos recursos ao perfil administrador
            //======================================================================
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_associar_dispositivo_normativo_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_associar_dispositivo_normativo_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_associar_dispositivo_normativo_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_associar_dispositivo_normativo_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_associar_dispositivo_normativo_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_associar_dispositivo_normativo_cadastrar');


            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_listar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_selecionar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_consultar');
            $objRecursoDTODispositivosADM = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_listar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_reativar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_desativar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_excluir');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_alterar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_cadastrar');

            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_selecionar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_consultar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_listar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_cadastrar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_alterar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_excluir');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_reativar');
            $objRecursoDTO                = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_desativar');

            //criando Administração -> Controle de Processos Litigiosos
            $objItemMenuDTOControleProcesso       = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 20);
            $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 20);

            //criando Administração -> Controle de Processos Litigiosos -> Dispostivos Normativos
            $this->adicionarItemMenu($numIdSistemaSei,
                                     $numIdPerfilSeiAdministrador,
                                     $numIdMenuSei,
                                     $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
                                     $objRecursoDTODispositivosADM->getNumIdRecurso(),
                                     'Dispositivos Normativos',
                                     20);

            $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
            $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
            $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
            $objRegraAuditoriaDTO->setStrDescricao('Geral');

            $objRegraAuditoriaRN  = new RegraAuditoriaRN();
            $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);

            $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema=' . $numIdSistemaSei . ' and nome in (
		
  	    \'md_lit_associar_dispositivo_normativo_consultar\',
  
  	    \'md_lit_associar_dispositivo_normativo_listar\',
        \'md_lit_associar_dispositivo_normativo_reativar\',
        \'md_lit_associar_dispositivo_normativo_desativar\',
        \'md_lit_associar_dispositivo_normativo_excluir\',
        \'md_lit_associar_dispositivo_normativo_cadastrar\',
  
	    \'md_lit_dispositivo_normativo_selecionar\',
	    \'md_lit_dispositivo_normativo_listar\',
	    \'md_lit_dispositivo_normativo_reativar\',
	    \'md_lit_dispositivo_normativo_desativar\',
	    \'md_lit_dispositivo_normativo_excluir\',
		\'md_lit_dispositivo_normativo_consultar\',
		\'md_lit_dispositivo_normativo_alterar\',
		\'md_lit_dispositivo_normativo_cadastrar\',
				
  	    \'md_lit_conduta_selecionar\',
		\'md_lit_conduta_listar\',
        \'md_lit_conduta_cadastrar\',
		\'md_lit_conduta_alterar\',
        \'md_lit_conduta_consultar\',
		\'md_lit_conduta_reativar\',
		\'md_lit_conduta_desativar\',
		\'md_lit_conduta_excluir\')'

            );

            //CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS RECEM ADICIONADOS
            foreach ($rs as $recurso) {
                BancoSip::getInstance()->executarSql('insert into rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
            }

            $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
            $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
            $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

            $objSistemaRN = new SistemaRN();
            $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        }

        protected function instalarv004()
        {

            $objSistemaRN  = new SistemaRN();
            $objPerfilRN   = new PerfilRN();
            $objMenuRN     = new MenuRN();
            $objItemMenuRN = new ItemMenuRN();
            $objRecursoRN  = new RecursoRN();

            $objSistemaDTO = new SistemaDTO();
            $objSistemaDTO->retNumIdSistema();
            $objSistemaDTO->setStrSigla('SEI');

            $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

            if ($objSistemaDTO == null) {
                throw new InfraException('Sistema SEI não encontrado.');
            }

            $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Administrador');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Administrador do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();


            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome('Básico');
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {
                throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
            }

            $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTO->setStrNome($this->nomeGestorControleLitigioso);
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO == null) {

                //cadastra o novo perfil
                $objPerfilDTOGestorLitigioso = new PerfilDTO();

                $objPerfilDTOGestorLitigioso->retNumIdPerfil();
                $objPerfilDTOGestorLitigioso->retNumIdSistema();
                $objPerfilDTOGestorLitigioso->retStrNome();
                $objPerfilDTOGestorLitigioso->retStrSinAtivo();
                $objPerfilDTOGestorLitigioso->retStrSinCoordenado();

                $objPerfilDTOGestorLitigioso->setNumIdSistema($numIdSistemaSei);
                $objPerfilDTOGestorLitigioso->setStrNome($this->nomeGestorControleLitigioso);
                $objPerfilDTOGestorLitigioso->setStrDescricao($this->descricaoGestorControleLitigioso);
                $objPerfilDTOGestorLitigioso->setStrSinCoordenado('N');
                $objPerfilDTOGestorLitigioso->setStrSinAtivo('S');

                $objPerfilDTOGestorLitigioso   = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);
                $numIdPerfilSeiGestorLitigioso = $objPerfilDTOGestorLitigioso->getNumIdPerfil();

            } else {

                $numIdPerfilSeiGestorLitigioso = $objPerfilDTO->getNumIdPerfil();
            }

            $objMenuDTO = new MenuDTO();
            $objMenuDTO->retNumIdMenu();
            $objMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objMenuDTO->setStrNome('Principal');
            $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

            if ($objMenuDTO == null) {
                throw new InfraException('Menu do sistema SEI não encontrado.');
            }

            $numIdMenuSei = $objMenuDTO->getNumIdMenu();

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Administração');
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {
                throw new InfraException('Item de menu Administração do sistema SEI não encontrado.');
            }

            $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Usuários');
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO == null) {
                throw new InfraException('Item de menu Administração/Usuários do sistema SEI não encontrado.');
            }

            $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

            //SEI ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO MÓDULO LITIGIOSO NA BASE DO SIP... v0.0.4');

            //===========================================================================
            //criando os recursos e vinculando-os ao perfil Gestor de Controle Litigioso
            //==========================================================================

            /*
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_associar_dispositivo_normativo_consultar');
            */

            //md_lit_tipo_controle_tipo_decisao_consultar
            $objRecursoDTO      = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_controle_tipo_decisao_consultar');
            $objRecursoDTO      = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_controle_tipo_decisao_cadastrar');

            //md_lit_tipo_decisao_selecionar
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_decisao_selecionar');

            //parametrizar interessado
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_parametrizar_interessado_cadastrar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_parametrizar_interessado_listar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_parametrizar_interessado_excluir');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_parametrizar_interessado_alterar');

            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_mapear_param_entrada_listar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_mapear_param_saida_listar');


            //======================================================================
            //adicionando os mesmos recursos ao perfil administrador
            //======================================================================
            $objRecursoTipoDecisaoListarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_decisao_listar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_decisao_selecionar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_decisao_consultar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_excluir');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_desativar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_reativar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_cadastrar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_alterar');

            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_especie_decisao_listar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_especie_decisao_selecionar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_especie_decisao_consultar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_excluir');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_desativar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_reativar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_cadastrar');
            $objRecursoDTO                  = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_alterar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_controle_tipo_decisao_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_controle_tipo_decisao_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_tipo_decisao_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_tipo_decisao_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_tipo_decisao_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_tipo_decisao_cadastrar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_obrigacao_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_obrigacao_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_obrigacao_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_obrigacao_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_obrigacao_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_obrigacao_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_obrigacao_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_obrigacao_alterar');

            //Serviços:
            $objRecursoServicoListarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_listar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_selecionar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_integracao_consultar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_consultar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_listar');
            $objRecursoServicoListarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_listar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_excluir');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_desativar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_reativar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_cadastrar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_alterar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_integracao_cadastrar');
            $objRecursoDTO              = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_integracao_alterar');

            //Serviços Modalidade:
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_servico_modalidade_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_servico_modalidade_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_servico_modalidade_excluir');

            //Serviços Abrangência:
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_servico_abrangen_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_servico_abrangen_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_servico_abrangen_excluir');

            //Integração:
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_listar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_selecionar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_consultar');
            $objRecursoIntegracaoListarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_listar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_excluir');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_desativar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_reativar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_cadastrar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_alterar');


            //parametrizar interessado
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_parametrizar_interessado_listar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_parametrizar_interessado_cadastrar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_parametrizar_interessado_excluir');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_parametrizar_interessado_alterar');
            
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_funcionalidade_listar');
            
            //ações de mapeamento de parametros entrada e saida
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_mapear_param_entrada_listar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_mapear_param_saida_listar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_saida_cadastrar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_entrada_cadastrar');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_entrada_excluir');
            $objRecursoDTO                 = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_saida_excluir');


            //======================================================================
            //adicionando os mesmos recursos ao perfil básico
            //======================================================================
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_validar_numero_sei');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_cadastro_completo');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_cadastro_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_controle_consultar');


            //Dado Interessado
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_desativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_reativar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_selecionar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_alterar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_consultar');

            //Dado Interessado e modalidade
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_modali_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_modali_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_modali_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_modali_listar');

            //Dado Interessado e abrangencia
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_abrang_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_abrang_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_abrang_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_abrang_listar');

            //Dado Interessado e servico
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_servico_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_servico_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_servico_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_servico_listar');

            //Dado Interessado e cidade
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_cidade_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_cidade_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_cidade_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_cidade_listar');

            //Dado Interessado e estado
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_estado_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_estado_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_estado_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_dado_inter_estado_listar');

            //integração
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_consultar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_listar');

            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_mapea_param_valor_listar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapea_param_valor_excluir');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapea_param_valor_cadastrar');
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_mapea_param_valor_consultar');


            //dispositivo normativo
            $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_listar');

            //obtendo o menu Administração -> Controle de Processos Litigiosos
            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
            $objItemMenuDTO->setStrRotulo('Controle de Processos Litigiosos');
            $objItemMenuDTOControleProcesso = $objItemMenuRN->consultar($objItemMenuDTO);

            //criando Administração > Controle de Processos Litigiosos > Tipos de Decisão
            $this->adicionarItemMenu($numIdSistemaSei,
                                     $numIdPerfilSeiAdministrador,
                                     $numIdMenuSei,
                                     $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
                                     $objRecursoTipoDecisaoListarDTO->getNumIdRecurso(),
                                     'Tipos de Decisão',
                                     20);

            //criando Administração > Controle de Processos Litigiosos > Serviços
            $this->adicionarItemMenu($numIdSistemaSei,
                                     $numIdPerfilSeiAdministrador,
                                     $numIdMenuSei,
                                     $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
                                     $objRecursoServicoListarDTO->getNumIdRecurso(),
                                     'Lista de Serviços Outorgados',
                                     30);

            //criando Administração > Controle de Processos Litigiosos > Integrações
            $this->adicionarItemMenu($numIdSistemaSei,
                                     $numIdPerfilSeiAdministrador,
                                     $numIdMenuSei,
                                     $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
                                     $objRecursoIntegracaoListarDTO->getNumIdRecurso(),
                                     'Mapeamento das Integrações',
                                     40);

            $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
            $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
            $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
            $objRegraAuditoriaDTO->setStrDescricao('Geral');

            $objRegraAuditoriaRN  = new RegraAuditoriaRN();
            $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);

            $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema=' . $numIdSistemaSei . ' and nome in (
		
  	    \'md_lit_tipo_decisao_excluir\',
		\'md_lit_tipo_decisao_desativar\',
		\'md_lit_tipo_decisao_reativar\',
								
		\'md_lit_tipo_decisao_cadastrar\',
		\'md_lit_tipo_decisao_alterar\',					
		\'md_lit_especie_decisao_excluir\',
		\'md_lit_especie_decisao_desativar\',
		\'md_lit_especie_decisao_reativar\',
								
		\'md_lit_especie_decisao_cadastrar\',
		\'md_lit_especie_decisao_alterar\',
		
		\'md_lit_tipo_controle_tipo_decisao_reativar\',
		\'md_lit_tipo_controle_tipo_decisao_desativar\',
		\'md_lit_tipo_controle_tipo_decisao_excluir\',
		\'md_lit_tipo_controle_tipo_decisao_cadastrar\',
		
		\'md_lit_obrigacao_excluir\',
		\'md_lit_obrigacao_desativar\',
		\'md_lit_obrigacao_reativar\',
		\'md_lit_obrigacao_cadastrar\',
		\'md_lit_obrigacao_alterar\',
		
		\'md_lit_processo_validar_numero_sei\',
		\'md_lit_processo_cadastro_completo\',

	    \'md_lit_parametrizar_interessado_cadastrar\',
	    \'md_lit_parametrizar_interessado_excluir\',
	    \'md_lit_integracao_cadastrar\',
	    \'md_lit_integracao_alterar\',
	    \'md_lit_integracao_excluir\',
	    \'md_lit_integracao_reativar\',
	    \'md_lit_integracao_desativar\',

		\'md_lit_servico_cadastrar\',
		\'md_lit_servico_excluir\',
		\'md_lit_servico_reativar\',
		\'md_lit_servico_desativar\',
		
		\'md_lit_servico_integracao_cadastrar\',
		\'md_lit_servico_integracao_alterar\',

		\'md_lit_dado_interessado_excluir\',
		\'md_lit_dado_interessado_cadastrar\',
		\'md_lit_dado_interessado_alterar\',

		\'md_lit_parametrizar_interessado_alterar\',

		\'md_lit_processo_cadastro_cadastrar\',
		
		\'md_lit_dado_interessado_cadastrar\',
		\'md_lit_dado_interessado_excluir\',
		\'md_lit_mapea_param_valor_cadastrar\',
		\'md_lit_mapea_param_valor_excluir\')'

            );

            //CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS RECEM ADICIONADOS
            foreach ($rs as $recurso) {
                BancoSip::getInstance()->executarSql('insert into rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
            }

            $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
            $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
            $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

            $objSistemaRN = new SistemaRN();
            $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        }

        private function removerRecursoPerfil($numIdSistema, $strNome, $numIdPerfil)
        {

            $objRecursoDTO = new RecursoDTO();
            $objRecursoDTO->setBolExclusaoLogica(false);
            $objRecursoDTO->retNumIdRecurso();
            $objRecursoDTO->setNumIdSistema($numIdSistema);
            $objRecursoDTO->setStrNome($strNome);

            $objRecursoRN  = new RecursoRN();
            $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

            if ($objRecursoDTO != null) {
                $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
                $objRelPerfilRecursoDTO->retTodos();
                $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
                $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);

                $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
                $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

                $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
                $objRelPerfilItemMenuDTO->retTodos();
                $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilItemMenuDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
                $objRelPerfilItemMenuDTO->setNumIdPerfil($numIdPerfil);

                $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
                $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
            }
        }

        private function desativarRecurso($numIdSistema, $strNome)
        {
            $objRecursoDTO = new RecursoDTO();
            $objRecursoDTO->retNumIdRecurso();
            $objRecursoDTO->setNumIdSistema($numIdSistema);
            $objRecursoDTO->setStrNome($strNome);

            $objRecursoRN  = new RecursoRN();
            $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

            if ($objRecursoDTO != null) {
                $objRecursoRN->desativar(array($objRecursoDTO));
            }
        }

        private function removerRecurso($numIdSistema, $strNome)
        {

            $objRecursoDTO = new RecursoDTO();
            $objRecursoDTO->setBolExclusaoLogica(false);
            $objRecursoDTO->retNumIdRecurso();
            $objRecursoDTO->setNumIdSistema($numIdSistema);
            $objRecursoDTO->setStrNome($strNome);

            $objRecursoRN  = new RecursoRN();
            $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);

            if ($objRecursoDTO != null) {
                $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
                $objRelPerfilRecursoDTO->retTodos();
                $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

                $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
                $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

                $objItemMenuDTO = new ItemMenuDTO();
                $objItemMenuDTO->retNumIdMenu();
                $objItemMenuDTO->retNumIdItemMenu();
                $objItemMenuDTO->setNumIdSistema($numIdSistema);
                $objItemMenuDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());

                $objItemMenuRN     = new ItemMenuRN();
                $arrObjItemMenuDTO = $objItemMenuRN->listar($objItemMenuDTO);

                $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();

                foreach ($arrObjItemMenuDTO as $objItemMenuDTO) {
                    $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
                    $objRelPerfilItemMenuDTO->retTodos();
                    $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
                    $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

                    $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
                }

                $objItemMenuRN->excluir($arrObjItemMenuDTO);
                $objRecursoRN->excluir(array($objRecursoDTO));
            }
        }

        private function removerItemMenu($numIdSistema, $numIdMenu, $numIdItemMenu)
        {

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuDTO->retNumIdMenu();
            $objItemMenuDTO->retNumIdItemMenu();
            $objItemMenuDTO->setNumIdSistema($numIdSistema);
            $objItemMenuDTO->setNumIdMenu($numIdMenu);
            $objItemMenuDTO->setNumIdItemMenu($numIdItemMenu);

            $objItemMenuRN  = new ItemMenuRN();
            $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

            if ($objItemMenuDTO != null) {

                $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
                $objRelPerfilItemMenuDTO->retTodos();
                $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilItemMenuDTO->setNumIdMenu($objItemMenuDTO->getNumIdMenu());
                $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());

                $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
                $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

                $objItemMenuRN->excluir(array($objItemMenuDTO));
            }
        }

        private function removerPerfil($numIdSistema, $strNome)
        {

            $objPerfilDTO = new PerfilDTO();
            $objPerfilDTO->retNumIdPerfil();
            $objPerfilDTO->setNumIdSistema($numIdSistema);
            $objPerfilDTO->setStrNome($strNome);

            $objPerfilRN  = new PerfilRN();
            $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

            if ($objPerfilDTO != null) {

                $objPermissaoDTO = new PermissaoDTO();
                $objPermissaoDTO->retNumIdSistema();
                $objPermissaoDTO->retNumIdUsuario();
                $objPermissaoDTO->retNumIdPerfil();
                $objPermissaoDTO->retNumIdUnidade();
                $objPermissaoDTO->setNumIdSistema($numIdSistema);
                $objPermissaoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

                $objPermissaoRN = new PermissaoRN();
                $objPermissaoRN->excluir($objPermissaoRN->listar($objPermissaoDTO));

                $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
                $objRelPerfilItemMenuDTO->retTodos();
                $objRelPerfilItemMenuDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilItemMenuDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

                $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
                $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

                $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
                $objRelPerfilRecursoDTO->retTodos();
                $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
                $objRelPerfilRecursoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

                $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
                $objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));

                $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
                $objCoordenadorPerfilDTO->retTodos();
                $objCoordenadorPerfilDTO->setNumIdSistema($numIdSistema);
                $objCoordenadorPerfilDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

                $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
                $objCoordenadorPerfilRN->excluir($objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO));

                $objPerfilRN->excluir(array($objPerfilDTO));
            }
        }

    }

    //========================= INICIO SCRIPT EXECUÇAO =============

    try {

        session_start();

        SessaoSip::getInstance(false);

        $objVersaoRN = new MdLitAtualizadorSipRN();
        $objVersaoRN->atualizarVersao();

    } catch (Exception $e) {
        echo(nl2br(InfraException::inspecionar($e)));
        try {
            LogSip::getInstance()->gravar(InfraException::inspecionar($e));
        } catch (Exception $e) {
        }
    }

    //========================== FIM SCRIPT EXECUÇÂO ====================