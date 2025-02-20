<?
require_once dirname(__FILE__) . '/../web/Sip.php';

class MdLitAtualizadorSipRN extends InfraRN
{

    private $numSeg = 0;
    private $versaoAtualDesteModulo = '2.3.0';
    private $nomeDesteModulo = 'M�DULO DE CONTROLE LITIGIOSO';
    private $nomeParametroModulo = 'VERSAO_MODULO_LITIGIOSO';
    private $historicoVersoes = array('0.0.1', '0.0.2', '0.0.3', '0.0.4', '1.0.0', '1.1.0', '1.2.0', '1.3.0', '1.4.0', '1.5.0', '1.6.0', '1.7.0', '1.8.0', '1.9.0', '1.10.0', '2.0.0', '2.1.0', '2.2.0', '2.3.0');

    private $nomeGestorControleLitigioso = "Gestor de Controle Litigioso";
    private $nomePerfilAdministrador = "Administrador";
    private $nomePerfilBasico = "B�sico";
    private $descricaoGestorControleLitigioso = "Acesso aos recursos espec�ficos de Gestor de Controle Litigioso do m�dulo Litigioso do SEI.";

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSip::getInstance();
    }

    protected function inicializar($strTitulo)
    {
        session_start();
        SessaoSip::getInstance(false);

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        @ini_set('implicit_flush', '1');
        ob_implicit_flush();

        InfraDebug::getInstance()->setBolLigado(true);
        InfraDebug::getInstance()->setBolDebugInfra(true);
        InfraDebug::getInstance()->setBolEcho(true);
        InfraDebug::getInstance()->limpar();

        $this->numSeg = InfraUtil::verificarTempoProcessamento();

        $this->logar($strTitulo);
    }

    protected function logar($strMsg)
    {
        InfraDebug::getInstance()->gravar($strMsg);
        flush();
    }

    protected function finalizar($strMsg = null, $bolErro = false)
    {
        if (!$bolErro) {
            $this->numSeg = InfraUtil::verificarTempoProcessamento($this->numSeg);
            $this->logar('TEMPO TOTAL DE EXECU��O: ' . $this->numSeg . ' s');
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

    protected function normalizaVersao($versao)
    {
        $ultimoPonto = strrpos($versao, '.');
        if ($ultimoPonto !== false) {
            $versao = substr($versao, 0, $ultimoPonto) . substr($versao, $ultimoPonto + 1);
        }
        return $versao;
    }

    protected function atualizarVersaoConectado()
    {

        try {
            $this->inicializar('INICIANDO A INSTALA��O/ATUALIZA��O DO ' . $this->nomeDesteModulo . ' NO SIP VERS�O ' . SIP_VERSAO);

            //checando BDs suportados
            if (!(BancoSip::getInstance() instanceof InfraMySql) &&
                !(BancoSip::getInstance() instanceof InfraSqlServer) &&
                !(BancoSip::getInstance() instanceof InfraOracle)) {
                $this->finalizar('BANCO DE DADOS N�O SUPORTADO: ' . get_parent_class(BancoSip::getInstance()), true);
            }

            //testando versao do framework
            $numVersaoInfraRequerida = '2.0.18';
            if ($this->normalizaVersao(VERSAO_INFRA) < $this->normalizaVersao($numVersaoInfraRequerida)) {
                $this->finalizar('VERSO DO FRAMEWORK PHP INCOMPATVEL (VERSO ATUAL ' . VERSAO_INFRA . ', SENDO REQUERIDA VERSO IGUAL OU SUPERIOR A ' . $numVersaoInfraRequerida . ')', true);
            }

            //checando permissoes na base de dados
            $objInfraMetaBD = new InfraMetaBD(BancoSip::getInstance());

            if (count($objInfraMetaBD->obterTabelas('sip_teste')) == 0) {
                BancoSip::getInstance()->executarSql('CREATE TABLE sip_teste (id ' . $objInfraMetaBD->tipoNumero() . ' null)');
            }

            BancoSip::getInstance()->executarSql('DROP TABLE sip_teste');

            $objInfraParametro = new InfraParametro(BancoSip::getInstance());

            $strVersaoModuloLitigioso = $objInfraParametro->getValor($this->nomeParametroModulo, false);

            switch ($strVersaoModuloLitigioso) {
                case '':
                    $this->instalarv001();
                case '0.0.1':
                    $this->instalarv002();
                case '0.0.2':
                    $this->instalarv003();
                case '0.0.3':
                    $this->instalarv004();
                case '0.0.4':
                    $this->instalarv100();
                case '1.0.0':
                    $this->instalarv110();
                case '1.1.0':
                    $this->instalarv120();
                case '1.2.0':
                    $this->instalarv130();
                case '1.3.0':
                    $this->instalarv140();
                case '1.4.0':
                    $this->instalarv150();
                case '1.5.0':
                    $this->instalarv160();
                case '1.6.0':
                    $this->instalarv170();
                case '1.7.0':
                    $this->instalarv180();
                case '1.8.0':
                    $this->instalarv190();
                case '1.9.0':
                    $this->instalarv1100();
                case '1.10.0':
                    $this->instalarv200();
                case '2.0.0':
                    $this->instalarv210();
                case '2.1.0':
                    $this->instalarv220();
                case '2.2.0':
                    $this->instalarv230();
                case '2.3.0':
                    $this->instalarv240();
                    break;

                default:
                    $this->finalizar('A VERS�O MAIS ATUAL DO ' . $this->nomeDesteModulo . ' (v' . $this->versaoAtualDesteModulo . ') J� EST� INSTALADA.');
                    break;

            }

            $this->finalizar('FIM');
            InfraDebug::getInstance()->setBolDebugInfra(true);
        } catch (Exception $e) {
            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(true);
            InfraDebug::getInstance()->setBolEcho(true);
            throw new InfraException('Erro instalando/atualizando vers�o.', $e);
        }

    }

    protected function instalarv001()
    {

        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O 0.0.1 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();
        $objRecursoRN = new RecursoRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

        //cadastrando o novo perfil do gestor de controle litigioso

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome($this->nomeGestorControleLitigioso);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            $objPerfilDTOGestorLitigioso = new PerfilDTO();
            $objPerfilDTOGestorLitigioso->retNumIdPerfil();
            $objPerfilDTOGestorLitigioso->setNumIdSistema($numIdSistemaSei);
            $objPerfilDTOGestorLitigioso->setStrNome($this->nomeGestorControleLitigioso);
            $objPerfilDTOGestorLitigioso->setStrDescricao($this->descricaoGestorControleLitigioso);
            $objPerfilDTOGestorLitigioso->setStrSinCoordenado('N');
            $objPerfilDTOGestorLitigioso->setStrSinAtivo('S');
            $objPerfilDTOGestorLitigioso->setStrSin2Fatores('N');

            $objPerfilDTOGestorLitigioso = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);

        }

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome($this->nomeGestorControleLitigioso);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Gestor de Controle Litigioso do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiGestorLitigioso = $objPerfilDTO->getNumIdPerfil();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Administrador');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Administrador do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Inform�tica');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Inform�tica do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiInformatica = $objPerfilDTO->getNumIdPerfil();

        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objMenuDTO->setStrNome('Principal');
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }

        $numIdMenuSei = $objMenuDTO->getNumIdMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Administra��o');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Usu�rios');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o/Usu�rios do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP...');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - situa��o do processo EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_situacao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_processo_situacao_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_processo_situacao_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_situacao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_situacao_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_situacao_cadastro_hist_int_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_situacao_cadastro_hist_quin_listar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_decisao_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_decisao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_decisao_cadastrar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_lancamento_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_lancamento_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_lancamento_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_lancamento_alterar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_multa_justificativa');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_multa_cancelar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_cancela_lancamento_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_cancela_lancamento_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_cancela_lancamento_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_cancela_lancamento_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_cancela_lancamento_consultar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_historic_lancamento_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_historic_lancamento_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_historic_lancamento_consultar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decis_lancament_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decis_lancament_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decis_lancament_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decis_lancament_excluir');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_modalidade_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_abrangencia_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_lancamento_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_lancamento_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_lancamento_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_integracao_mapear');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancam_int_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancam_int_alterar');
        $objRecursoListarSituacaoLancamentoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_visualizar_parametrizar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_excluir');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_processo_listar');


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);
        $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos -> Tipos de Controles Litigiosos');
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
            20);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL  Administra��o -> Controle de Processos Litigiosos -> Situa��es do Lan�amento de Cr�dito');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcessoBasico->getNumIdItemMenu(),
            $objRecursoListarSituacaoLancamentoDTO->getNumIdRecurso(),
            'Situa��es do Lan�amento de Cr�dito',
            70);

        $this->logar('CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS');

        $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
        $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
        $objRegraAuditoriaDTO->setNumIdRegraAuditoria(null);
        $objRegraAuditoriaDTO->setStrSinAtivo('S');
        $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
        $objRegraAuditoriaDTO->setArrObjRelRegraAuditoriaRecursoDTO(array());
        $objRegraAuditoriaDTO->setStrDescricao('Modulo_Controle_Litigioso');

        $objRegraAuditoriaRN = new RegraAuditoriaRN();
        $objRegraAuditoriaDTO = $objRegraAuditoriaRN->cadastrar($objRegraAuditoriaDTO);

        $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema=' . $numIdSistemaSei . ' and nome in (
       \'md_lit_tipo_processo_alterar\',
      \'md_lit_tipo_processo_cadastrar\',
      \'md_lit_tipo_processo_desativar\',
      \'md_lit_tipo_processo_reativar\',
      \'md_lit_tipo_processo_excluir\',
      \'md_lit_processo_situacao_cadastrar\',
      \'md_lit_situacao_lancamento_cadastrar\')'
        );

        foreach ($rs as $recurso) {
            BancoSip::getInstance()->executarSql('INSERT INTO rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
        }

        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        $objSistemaRN = new SistemaRN();
        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        //adicionando parametro para controlar versao do modulo
        $this->logar('ADICIONANDO PAR�METRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERS�O DO M�DULO');
        BancoSip::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome ) VALUES( \'0.0.1\',  \'' . $this->nomeParametroModulo . '\' )');

        $this->logar('INSTALA��O/ATUALIZA��O DA VERS�O 0.0.1 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');

    }

    protected function instalarv002()
    {
        $nmVersao = '0.0.2';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();
        $objRecursoRN = new RecursoRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Administrador');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Administrador do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
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
            $objPerfilDTOGestorLitigioso->setStrSin2Fatores('N');

            $objPerfilDTOGestorLitigioso = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);

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
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }

        $numIdMenuSei = $objMenuDTO->getNumIdMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Administra��o');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Usu�rios');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o/Usu�rios do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO M�DULO LITIGIOSO NA BASE DO SIP... v0.0.2');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - situa��o EM gestor do controle Litigioso');
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

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - situa��o EM B�sico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_parametrizar');

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


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);
        $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos -> Tipos de Controles Litigiosos');
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
        $objRegraAuditoriaDTO->setStrDescricao('Modulo_Controle_Litigioso');

        $objRegraAuditoriaRN = new RegraAuditoriaRN();
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


        $this->logar('CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS');
        foreach ($rs as $recurso) {
            BancoSip::getInstance()->executarSql('INSERT INTO rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
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

        //adicionando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarv003()
    {
        $nmVersao = '0.0.3';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();
        $objRecursoRN = new RecursoRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Administrador');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Administrador do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();


        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
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
            $objPerfilDTOGestorLitigioso->setStrSin2Fatores('N');


            $objPerfilDTOGestorLitigioso = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);

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
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }

        $numIdMenuSei = $objMenuDTO->getNumIdMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Administra��o');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Usu�rios');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o/Usu�rios do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO M�DULO LITIGIOSO NA BASE DO SIP...');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - dispositivos normativos EM Gestor do controle litigioso e basico');
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


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - dispositivos normativos EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_associar_dispositivo_normativo_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_associar_dispositivo_normativo_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_associar_dispositivo_normativo_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_associar_dispositivo_normativo_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_associar_dispositivo_normativo_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_associar_dispositivo_normativo_cadastrar');


        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_consultar');
        $objRecursoDTODispositivosADM = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dispositivo_normativo_cadastrar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_conduta_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_conduta_desativar');


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 20);
        $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 20);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos -> Dispostivos Normativos');
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
        $objRegraAuditoriaDTO->setStrDescricao('Modulo_Controle_Litigioso');

        $objRegraAuditoriaRN = new RegraAuditoriaRN();
        $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);

        $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema=' . $numIdSistemaSei . ' and nome in (
		
  	    \'md_lit_associar_dispositivo_normativo_consultar\',
  
        \'md_lit_associar_dispositivo_normativo_reativar\',
        \'md_lit_associar_dispositivo_normativo_desativar\',
        \'md_lit_associar_dispositivo_normativo_excluir\',
        \'md_lit_associar_dispositivo_normativo_cadastrar\',
  
	    \'md_lit_dispositivo_normativo_selecionar\',
	    \'md_lit_dispositivo_normativo_reativar\',
	    \'md_lit_dispositivo_normativo_desativar\',
	    \'md_lit_dispositivo_normativo_excluir\',
		\'md_lit_dispositivo_normativo_consultar\',
		\'md_lit_dispositivo_normativo_alterar\',
		\'md_lit_dispositivo_normativo_cadastrar\',
				
  	    \'md_lit_conduta_selecionar\',
        \'md_lit_conduta_cadastrar\',
		\'md_lit_conduta_alterar\',
        \'md_lit_conduta_consultar\',
		\'md_lit_conduta_reativar\',
		\'md_lit_conduta_desativar\',
		\'md_lit_conduta_excluir\')'

        );


        $this->logar('CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS');

        foreach ($rs as $recurso) {
            BancoSip::getInstance()->executarSql('INSERT INTO rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
        }

        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        $objSistemaRN = new SistemaRN();
        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarv004()
    {
        $nmVersao = '0.0.4';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();
        $objRecursoRN = new RecursoRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Administrador');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Administrador do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();


        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
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
            $objPerfilDTOGestorLitigioso->setStrSin2Fatores('N');

            $objPerfilDTOGestorLitigioso = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);


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
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }

        $numIdMenuSei = $objMenuDTO->getNumIdMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Administra��o');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Usu�rios');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o/Usu�rios do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO M�DULO LITIGIOSO NA BASE DO SIP... v0.0.4');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - tipo de controle EM Gestor do controle litigioso');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_controle_tipo_decisao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_controle_tipo_decisao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tipo_decisao_selecionar');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - parametrizar interessado EM Gestor do controle litigioso');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_parametrizar_interessado_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_parametrizar_interessado_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_parametrizar_interessado_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_parametrizar_interessado_alterar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_mapear_param_entrada_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_mapear_param_saida_listar');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - tipo de decis�o EM basico e administrador');
        $objRecursoTipoDecisaoListarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_decisao_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_decisao_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_decisao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_alterar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_especie_decisao_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_especie_decisao_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_especie_decisao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_especie_decisao_alterar');

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

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - servi�o EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_integracao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_servico_listar');
        $objRecursoServicoListarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_integracao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_servico_integracao_alterar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - modalidade EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_servico_modalidade_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_servico_modalidade_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_servico_modalidade_excluir');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - abrangencia EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_servico_abrangen_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_servico_abrangen_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_servico_abrangen_excluir');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - Integra��o EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_consultar');
        $objRecursoIntegracaoListarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_integracao_alterar');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - parametrizar interessado EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_parametrizar_interessado_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_parametrizar_interessado_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_parametrizar_interessado_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_parametrizar_interessado_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_funcionalidade_listar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - mapeamento de parametros entrada e saida EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_mapear_param_entrada_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_mapear_param_saida_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_saida_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_entrada_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_entrada_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_saida_excluir');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - processo litigioso EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_validar_numero_sei');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_cadastro_completo');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_processo_cadastro_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tipo_controle_consultar');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - dado interessado EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_reativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dado_interessado_consultar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - Dado Interessado e modalidade EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_modali_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_modali_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_modali_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_modali_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_modali_alterar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - Dado Interessado e abrangencia EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_abrang_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_abrang_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_abrang_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_abrang_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_abrang_alterar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - Dado Interessado e servico EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_servico_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_servico_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_servico_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_servico_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_servico_alterar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - Dado Interessado e cidade EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_cidade_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_cidade_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_cidade_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_cidade_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_cidade_alterar');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - Dado Interessado e estado EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_estado_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_estado_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_estado_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_estado_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_estado_alterar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - Dado Interessado e estado EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_numero_interessado_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_numero_interessado_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_numero_interessado_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_numero_interessado_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_numero_interessado_alterar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - integra��o EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_integracao_listar');

        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_mapea_param_valor_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapea_param_valor_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapea_param_valor_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_mapea_param_valor_consultar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - dispositivo revogado EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_disp_norm_revogado_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_disp_norm_revogado_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_disp_norm_revogado_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_disp_norm_revogado_listar');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - /dispositivo normativo EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_dispositivo_normativo_listar');

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos');
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $objItemMenuRN->consultar($objItemMenuDTO);

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o > Controle de Processos Litigiosos > Tipos de Decis�o');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoTipoDecisaoListarDTO->getNumIdRecurso(),
            'Tipos de Decis�o',
            20);

        //criando Administra��o > Controle de Processos Litigiosos > Servi�os
        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o > Controle de Processos Litigiosos > Servi�os');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoServicoListarDTO->getNumIdRecurso(),
            'Lista de Servi�os Outorgados',
            30);

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o > Controle de Processos Litigiosos > Integra��es');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoIntegracaoListarDTO->getNumIdRecurso(),
            'Mapeamento das Integra��es',
            40);

        $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
        $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
        $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
        $objRegraAuditoriaDTO->setStrDescricao('Modulo_Controle_Litigioso');

        $objRegraAuditoriaRN = new RegraAuditoriaRN();
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


        $this->logar('CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS');

        foreach ($rs as $recurso) {
            BancoSip::getInstance()->executarSql('INSERT INTO rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
        }

        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        $objSistemaRN = new SistemaRN();
        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarv100()
    {
        $nmVersao = '1.0.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();
        $objRecursoRN = new RecursoRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Administrador');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Administrador do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();


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
            $objPerfilDTOGestorLitigioso->setStrSin2Fatores('N');


            $objPerfilDTOGestorLitigioso = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);


            $numIdPerfilSeiGestorLitigioso = $objPerfilDTOGestorLitigioso->getNumIdPerfil();

        } else {

            $numIdPerfilSeiGestorLitigioso = $objPerfilDTO->getNumIdPerfil();
        }

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();

        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objMenuDTO->setStrNome('Principal');
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }

        $numIdMenuSei = $objMenuDTO->getNumIdMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Administra��o');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Usu�rios');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o/Usu�rios do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiUsuarios = $objItemMenuDTO->getNumIdItemMenu();

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO M�DULO LITIGIOSO NA BASE DO SIP... v1.0.0');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - reincidencia e antecedencia EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_reinciden_anteceden_alterar');
        $objRecursoReinAnteCadastrarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_reinciden_anteceden_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_reinciden_anteceden_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_reinciden_anteceden_listar ');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - tipo de decisao da reincidencia EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_tp_dec_rein_ante_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_tp_dec_rein_ante_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_tp_dec_rein_ante_listar');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - motivo EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_motivo_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_motivo_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_motivo_desativar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_motivo_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_motivo_reativar');
        $objRecursoMotivoListarDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_motivo_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_motivo_selecionar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_motivo_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_motivo_consultar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - tipo de controle litigioso com motivo EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_tp_control_moti_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_tp_control_moti_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_tp_control_moti_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_tp_control_moti_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_tp_control_moti_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_tp_control_moti_selecionar');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - controle litigioso com motivo EM basico e administrador');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_controle_motivo_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_controle_motivo_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_controle_motivo_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_controle_motivo_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_controle_motivo_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_controle_motivo_selecionar');


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos');
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $objItemMenuRN->consultar($objItemMenuDTO);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o > Controle de Processos Litigiosos > Reincid�ncias Espec�ficas e Antecedentes');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoReinAnteCadastrarDTO->getNumIdRecurso(),
            'Reincid�ncias Espec�ficas e Antecedentes',
            50);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o > Controle de Processos Litigiosos > Motivos para Instaura��o');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoMotivoListarDTO->getNumIdRecurso(),
            'Motivos para Instaura��o',
            60);

        $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
        $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
        $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);
        $objRegraAuditoriaDTO->setStrDescricao('Modulo_Controle_Litigioso');

        $objRegraAuditoriaRN = new RegraAuditoriaRN();
        $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);

        $rs = BancoSip::getInstance()->consultarSql('select id_recurso from recurso where id_sistema=' . $numIdSistemaSei . ' and nome in (
		
  	    \'md_lit_reinciden_anteceden_cadastrar\',
  	    \'md_lit_reinciden_anteceden_alterar\')'

        );


        $this->logar('CRIANDO REGRA DE AUDITORIA PARA NOVOS RECURSOS');
        foreach ($rs as $recurso) {
            BancoSip::getInstance()->executarSql('INSERT INTO rel_regra_auditoria_recurso (id_regra_auditoria, id_sistema, id_recurso) values (' . $objRegraAuditoriaDTO->getNumIdRegraAuditoria() . ', ' . $numIdSistemaSei . ', ' . $recurso['id_recurso'] . ')');
        }

        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        $objSistemaRN = new SistemaRN();
        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV110()
    {
        $nmVersao = '1.1.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();
        $objRecursoRN = new RecursoRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();


        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();


        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Administrador');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Administrador do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome($this->nomeGestorControleLitigioso);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Gestor do Controle Litigioso do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiGestorControle = $objPerfilDTO->getNumIdPerfil();


        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objMenuDTO->setStrNome('Principal');
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }

        $numIdMenuSei = $objMenuDTO->getNumIdMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Administra��o');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Administra��o do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiAdministracao = $objItemMenuDTO->getNumIdItemMenu();


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - motivo EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_listar');

        $this->logar('Atualizando e VINCULANDO RECURSO A PERFIL - modal de situa��o EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_visualizar_parametrizar');
        $objRecursoDTO = $this->removerRecursoPerfil($numIdSistemaSei, 'md_lit_situacao_visualizar_parametrizar', $numIdPerfilSeiAdministrador);

        //recuperando o recurso para vincular com o menu
        $objRecursoListarSituacaoLancamentoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_listar');


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administra��o -> Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL  Administra��o -> Controle de Processos Litigiosos -> Situa��es do Lan�amento de Cr�dito');
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retTodos(false);
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Situa��es do Lan�amento de Cr�dito');
        $objItemMenuDTO->setNumIdMenuPai($numIdMenuSei);
        $objItemMenuDTO->setNumIdItemMenuPai($objItemMenuDTOControleProcesso->getNumIdItemMenu());
        $objItemMenuDTO->setNumIdRecurso($objRecursoListarSituacaoLancamentoDTO->getNumIdRecurso());

        $objItemMenuRN = new ItemMenuRN();
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO != null) {
            $objItemMenuDTO->setNumSequencia(70);
            $objItemMenuRN->alterar($objItemMenuDTO);
        }


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - dispositivo revogado EM Gestor do Controle Litgioso');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorControle, 'md_lit_rel_disp_norm_revogado_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorControle, 'md_lit_rel_disp_norm_revogado_excluir');


        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV120()
    {
        $nmVersao = '1.2.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');


        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();


        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();


        $this->logar('CRIANDO E VINCULANDO AO PERFIL B�SICO O RECURSO DESATIVAR DECIS�O');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_decisao_desativar');

        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV130()
    {
        $nmVersao = '1.3.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');


        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();


        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();


        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objMenuDTO->setStrNome('Principal');
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }

        $numIdMenuSei = $objMenuDTO->getNumIdMenu();

        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Relat�rios');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Relat�rios do sistema SEI n�o encontrado.');
        }

        $numIdItemMenuSeiRelatorio = $objItemMenuDTO->getNumIdItemMenu();


        $this->logar('CRIANDO E VINCULANDO AO PERFIL B�SICO O RECURSO de relatorio do Antecedente');
        $objRecursoRelatorioAntecedenteDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_relatorio_antecedente');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_modal_relatorio_antecedente');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_relatorio_antecedente_exp_excel');


        $this->logar('CRIANDO E VINCULANDO AO PERFIL B�SICO O RECURSO de relatorio do Reincidente');
        $objRecursoRelatorioReincidenteDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_relatorio_reincidencia');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_modal_relatorio_reincidencia');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_relatorio_antecedente_exp_excel');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_reinciden_anteceden_consultar');


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Relat�rios -> Processos Litigiosos');
        $objItemMenuDTORelatorioLitigiosos = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiBasico, $numIdMenuSei, $numIdItemMenuSeiRelatorio, null, 'Processos Litigiosos', 30);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL   Relat�rios -> Processos Litigiosos -> Antecedentes');
        $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiBasico, $numIdMenuSei, $objItemMenuDTORelatorioLitigiosos->getNumIdItemMenu(), $objRecursoRelatorioAntecedenteDTO->getNumIdRecurso(), 'Antecendentes', 10);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL   Relat�rios -> Processos Litigiosos -> Antecedentes');
        $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiBasico, $numIdMenuSei, $objItemMenuDTORelatorioLitigiosos->getNumIdItemMenu(), $objRecursoRelatorioReincidenteDTO->getNumIdRecurso(), 'Reincid�ncias Espec�ficas', 20);

        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV140()
    {
        $nmVersao = '1.4.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');


        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();


        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();


        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objMenuDTO->setStrNome('Principal');
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }


        $this->logar('CRIANDO E VINCULANDO AO PERFIL B�SICO O RECURSO de vincular lancamento');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_vincular_lancamento');

        $this->logar('CRIANDO E VINCULANDO AO PERFIL B�SICO O RECURSO de situa��o de lan�amento por integra��o');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_lancam_int_consultar');

        $this->logar('CRIANDO E VINCULANDO AO PERFIL B�SICO O RECURSO de tipo de outorga e numero de interessado');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_adm_tipo_outor_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_modalidade_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_consultar');


        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV150()
    {
        $nmVersao = '1.5.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');

        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();


        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('B�sico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil B�sico do sistema SEI n�o encontrado.');
        }

        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objMenuDTO->setStrNome('Principal');
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema SEI n�o encontrado.');
        }

        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV160()
    {
        $nmVersao = '1.6.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV170()
    {
        $nmVersao = '1.7.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV180()
    {
        $nmVersao = '1.8.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV190()
    {
        $nmVersao = '1.9.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV1100()
    {
        $nmVersao = '1.10.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV200()
    {
        $nmVersao = '2.0.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV210()
    {
        $nmVersao = '2.1.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV220()
    {
        $nmVersao = '2.2.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $numIdSistemaSei = $this->getIdSistemaSei();
        $numIdPerfilSeiBasico = $this->getIdPerfil($this->nomePerfilBasico);
        $numIdPerfilSeiGestorLitigioso = $this->getIdPerfil($this->nomeGestorControleLitigioso);

        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_decisao_alterar');

        $this->logar('ADICINANDO RECURSO PARA GESTOR LIGITIOSO PARA CADASTRAR CAMPOS ADICIONAIS');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_sel_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_sel_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_sel_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_sel_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_campo_add_sel_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tp_info_add_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tp_info_add_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tp_info_add_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, 'md_lit_tp_info_add_contar');

        $this->logar('ADICINANDO RECURSO PARA USUARIO BASICO INCLUIR INFORMA��ES ADICIONAIS ');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_form_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_form_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_form_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_form_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_form_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_form_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_opc_camp_mult_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_opc_camp_mult_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_opc_camp_mult_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tp_info_add_listar');

        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV230()
    {
        $nmVersao = '2.3.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $numIdSistemaSei = $this->getIdSistemaSei();
        $numIdPerfilSeiBasico = $this->getIdPerfil($this->nomePerfilBasico);
        $numIdPerfilSeiGestorLitigioso = $this->getIdPerfil($this->nomeGestorControleLitigioso);
        $numIdPerfilSeiAdministrador = $this->getIdPerfil($this->nomePerfilAdministrador);

        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_campo_add_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_campo_add_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_campo_add_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_campo_add_sel_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_campo_add_sel_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_campo_add_sel_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_campo_add_sel_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_campo_add_sel_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tp_info_add_cadastrar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tp_info_add_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tp_info_add_contar');

        $this->logar('ADICINANDO RECURSO LITIGIOSO PARA ADMINISTRADOR PARA CADASTRAR CAMPOS ADICIONAIS');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_dado_interessado_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_fase_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_fase_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_fase_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_entrada_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_mapear_param_saida_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_processo_situacao_alterar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_processo_situacao_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_rel_num_inter_tp_outor_excluir');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_parametrizar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_selecionar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_controle_tipo_decisao_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_tipo_decisao_selecionar');

        $this->logar('REMOVENTO RECURSOS INDEVIDOS DE GESTOR DE CONTROLE LITIGIOSO');
        $this->removerRecursoPerfil($numIdSistemaSei, 'md_lit_campo_add_listar', $numIdPerfilSeiGestorLitigioso);
        $this->removerRecursoPerfil($numIdSistemaSei, 'md_lit_campo_add_consultar', $numIdPerfilSeiGestorLitigioso);
        $this->removerRecursoPerfil($numIdSistemaSei, 'md_lit_tp_info_add_consultar', $numIdPerfilSeiGestorLitigioso);

        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_listar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_consultar');
        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_tp_info_add_consultar');

        $this->atualizarNumeroVersao($nmVersao);
    }

    protected function instalarV240()
    {
        $nmVersao = '2.4.0';
        $this->logar('EXECUTANDO A INSTALA��O/ATUALIZA��O DA VERS�O '. $nmVersao .' DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $numIdSistemaSei = $this->getIdSistemaSei();
        $numIdPerfilSeiBasico = $this->getIdPerfil($this->nomePerfilBasico);
        $numIdPerfilSeiGestorLitigioso = $this->getIdPerfil($this->nomeGestorControleLitigioso);
        $numIdPerfilSeiAdministrador = $this->getIdPerfil($this->nomePerfilAdministrador);

        $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_campo_add_sel_listar');
        $this->removerRecursoPerfil($numIdSistemaSei, 'md_lit_campo_add_sel_listar', $numIdPerfilSeiAdministrador);
        $this->removerRecursoPerfil($numIdSistemaSei, 'md_lit_campo_add_sel_listar', $numIdPerfilSeiGestorLitigioso);

        $this->atualizarNumeroVersao($nmVersao);
    }

    private function getIdSistemaSei()
    {
        $objSistemaRN = new SistemaRN();
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setStrSigla('SEI');
        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

        if ($objSistemaDTO == null) {
            throw new InfraException('Sistema SEI n�o encontrado.');
        }

        return $objSistemaDTO->getNumIdSistema();
    }

    private function getIdPerfil($nomePerfil)
    {
        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($this->getIdSistemaSei());
        $objPerfilDTO->setStrNome($nomePerfil);
        $objPerfilDTO = (new PerfilRN())->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil' .  $nomePerfil . ' do sistema SEI n�o encontrado.');
        }

        return $objPerfilDTO->getNumIdPerfil();
    }

    private function adicionarRecursoPerfil($numIdSistema, $numIdPerfil, $strNome, $strCaminho = null)
    {

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
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

        $objItemMenuRN = new ItemMenuRN();
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
            $objItemMenuDTO->setStrIcone(null);

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


    private function removerRecursoPerfil($numIdSistema, $strNome, $numIdPerfil)
    {

        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->setBolExclusaoLogica(false);
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->setNumIdSistema($numIdSistema);
        $objRecursoDTO->setStrNome($strNome);

        $objRecursoRN = new RecursoRN();
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

        $objRecursoRN = new RecursoRN();
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

        $objRecursoRN = new RecursoRN();
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

            $objItemMenuRN = new ItemMenuRN();
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

        $objItemMenuRN = new ItemMenuRN();
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

        $objPerfilRN = new PerfilRN();
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

    /**
     * Atualiza o nmero de vers�o do m�dulo nas tabelas de par�metro do sistema
     *
     * @param string $parStrNumeroVersao
     * @return void
     */
    private function atualizarNumeroVersao($parStrNumeroVersao)
    {
        $this->logar('ATUALIZANDO PAR�METRO '. $this->nomeParametroModulo .' NA TABELA infra_parametro PARA CONTROLAR A VERS�O DO M�DULO');

        $objInfraParametroDTO = new InfraParametroDTO();
        $objInfraParametroDTO->setStrNome($this->nomeParametroModulo);
        $objInfraParametroDTO->retTodos();
        $objInfraParametroBD = new InfraParametroBD(BancoSIP::getInstance());
        $arrObjInfraParametroDTO = $objInfraParametroBD->listar($objInfraParametroDTO);
        foreach ($arrObjInfraParametroDTO as $objInfraParametroDTO) {
            $objInfraParametroDTO->setStrValor($parStrNumeroVersao);
            $objInfraParametroBD->alterar($objInfraParametroDTO);
        }

        $this->logar('INSTALA��O/ATUALIZA��O DA VERS�O '. $parStrNumeroVersao .' DO '. $this->nomeDesteModulo .' REALIZADA COM SUCESSO NA BASE DO SIP');

    }

}

try {

    SessaoSip::getInstance(false);
    BancoSip::getInstance()->setBolScript(true);

    InfraScriptVersao::solicitarAutenticacao(BancoSip::getInstance());
    $objVersaoSipRN = new MdLitAtualizadorSipRN();
    $objVersaoSipRN->atualizarVersao();
    exit;

} catch (Exception $e) {
    echo(InfraException::inspecionar($e));
    try {
        LogSip::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
    }
    exit(1);
}