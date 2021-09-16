<?
require_once dirname(__FILE__) . '/../web/Sip.php';

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../../infra/infra_php'),
    get_include_path(),
)));


class MdLitAtualizadorSipRN extends InfraRN
{

    private $nomeGestorControleLitigioso = "Gestor de Controle Litigioso";
    private $descricaoGestorControleLitigioso = "Acesso aos recursos específicos de Gestor de Controle Litigioso do módulo Litigioso do SEI.";
    private $nomeDesteModulo = 'MÓDULO DE CONTROLE LITIGIOSO';
    private $nomeParametroModulo = 'VERSAO_MODULO_LITIGIOSO';
    private $versaoAtualDesteModulo = '1.10.0';
    private $historicoVersoes = array('0.0.1', '0.0.2', '0.0.3', '0.0.4', '1.0.0', '1.1.0', '1.2.0', '1.3.0', '1.4.0', '1.5.0', '1.6.0', '1.7.0', '1.8.0', '1.9.0', '1.10.0');
    private $numSeg = 0;

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSip::getInstance();
    }

    private function inicializar($strTitulo)
    {
        session_start();
        SessaoSip::getInstance(false);

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        @ini_set('zlib.output_compression', '0');
        @ini_set('implicit_flush', '1');
        ob_implicit_flush();

        $this->objDebug = InfraDebug::getInstance();
        $this->objDebug->setBolLigado(true);
        $this->objDebug->setBolDebugInfra(true);
        $this->objDebug->setBolEcho(true);
        $this->objDebug->limpar();

        $this->numSeg = InfraUtil::verificarTempoProcessamento();
        $this->logar($strTitulo);
    }

    private function logar($strMsg)
    {
        $this->objDebug->gravar($strMsg);
        flush();
    }

    private function finalizar($strMsg = null, $bolErro = false)
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

    protected function atualizarVersaoConectado()
    {
        try {
            $this->inicializar('INICIANDO A INSTALAÇÃO/ATUALIZAÇÃO DO ' . $this->nomeDesteModulo . ' NO SIP VERSÃO ' . SIP_VERSAO);

            //checando BDs suportados
            if (!(BancoSip::getInstance() instanceof InfraMySql) &&
                !(BancoSip::getInstance() instanceof InfraSqlServer) &&
                !(BancoSip::getInstance() instanceof InfraOracle)) {

                $this->finalizar('BANCO DE DADOS NÃO SUPORTADO: ' . get_parent_class(BancoSip::getInstance()), true);
            }

            //testando versao do framework
            $numVersaoInfraRequerida = '1.532.1';
            $versaoInfraFormatada = (int)str_replace('.', '', VERSAO_INFRA);
            $versaoInfraReqFormatada = (int)str_replace('.', '', $numVersaoInfraRequerida);

            if ($versaoInfraFormatada < $versaoInfraReqFormatada) {
                $this->finalizar('VERSÃO DO FRAMEWORK PHP INCOMPATÍVEL (VERSÃO ATUAL ' . VERSAO_INFRA . ', SENDO REQUERIDA VERSÃO IGUAL OU SUPERIOR A ' . $numVersaoInfraRequerida . ')', true);
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
                    break;

                default:
                    $this->finalizar('A VERSÃO MAIS ATUAL DO ' . $this->nomeDesteModulo . ' (v' . $this->versaoAtualDesteModulo . ') JÁ ESTÁ INSTALADA.');
                    break;

            }

            $this->finalizar('FIM');
            InfraDebug::getInstance()->setBolDebugInfra(true);
        } catch (Exception $e) {
            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando versão.', $e);
        }

    }

    protected function instalarv001()
    {

        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.1 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

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
            throw new InfraException('Sistema SEI não encontrado.');
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

            $objPerfilDTOGestorLitigioso = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);

        }

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

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP...');


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - situação do processo EM basico');
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


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);
        $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos -> Tipos de Controles Litigiosos');
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


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL  Administração -> Controle de Processos Litigiosos -> Situações do Lançamento de Crédito');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcessoBasico->getNumIdItemMenu(),
            $objRecursoListarSituacaoLancamentoDTO->getNumIdRecurso(),
            'Situações do Lançamento de Crédito',
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
        $this->logar('ADICIONANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome ) VALUES( \'0.0.1\',  \'' . $this->nomeParametroModulo . '\' )');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.1 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');

    }

    protected function instalarv002()
    {

        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.2 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

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

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO MÓDULO LITIGIOSO NA BASE DO SIP... v0.0.2');

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - situação EM gestor do controle Litigioso');
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

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - situação EM Básico e administrador');
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


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);
        $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos -> Tipos de Controles Litigiosos');
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
        $this->logar('ADICIONANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'0.0.2\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.2 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarv003()
    {

        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.3 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

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

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO MÓDULO LITIGIOSO NA BASE DO SIP...');


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


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 20);
        $objItemMenuDTOControleProcessoBasico = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiGestorLitigioso, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 20);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos -> Dispostivos Normativos');
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
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'0.0.3\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.3 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');

    }

    protected function instalarv004()
    {

        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.4 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

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

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO MÓDULO LITIGIOSO NA BASE DO SIP... v0.0.4');


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


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - tipo de decisão EM basico e administrador');
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

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - serviço EM basico e administrador');
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


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - Integração EM basico e administrador');
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

        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - integração EM basico');
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

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos');
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $objItemMenuRN->consultar($objItemMenuDTO);

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração > Controle de Processos Litigiosos > Tipos de Decisão');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoTipoDecisaoListarDTO->getNumIdRecurso(),
            'Tipos de Decisão',
            20);

        //criando Administração > Controle de Processos Litigiosos > Serviços
        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração > Controle de Processos Litigiosos > Serviços');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoServicoListarDTO->getNumIdRecurso(),
            'Lista de Serviços Outorgados',
            30);

        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração > Controle de Processos Litigiosos > Integrações');
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
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'0.0.4\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.4 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');

    }

    protected function instalarv100()
    {

        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

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


            $objPerfilDTOGestorLitigioso = $objPerfilRN->cadastrar($objPerfilDTOGestorLitigioso);


            $numIdPerfilSeiGestorLitigioso = $objPerfilDTOGestorLitigioso->getNumIdPerfil();

        } else {

            $numIdPerfilSeiGestorLitigioso = $objPerfilDTO->getNumIdPerfil();
        }

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome('Básico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();

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

        $this->logar('ATUALIZANDO RECURSOS, MENUS E PERFIS DO MÓDULO LITIGIOSO NA BASE DO SIP... v1.0.0');


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


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos');
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retNumIdItemMenu();
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $objItemMenuRN->consultar($objItemMenuDTO);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração > Controle de Processos Litigiosos > Reincidências Específicas e Antecedentes');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoReinAnteCadastrarDTO->getNumIdRecurso(),
            'Reincidências Específicas e Antecedentes',
            50);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração > Controle de Processos Litigiosos > Motivos para Instauração');
        $this->adicionarItemMenu($numIdSistemaSei,
            $numIdPerfilSeiAdministrador,
            $numIdMenuSei,
            $objItemMenuDTOControleProcesso->getNumIdItemMenu(),
            $objRecursoMotivoListarDTO->getNumIdRecurso(),
            'Motivos para Instauração',
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
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.0.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV110()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.1.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

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
            throw new InfraException('Sistema SEI não encontrado.');
        }

        $numIdSistemaSei = $objSistemaDTO->getNumIdSistema();


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
        $objPerfilDTO->setStrNome('Administrador');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Administrador do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiAdministrador = $objPerfilDTO->getNumIdPerfil();

        $objPerfilDTO = new PerfilDTO();
        $objPerfilDTO->retNumIdPerfil();
        $objPerfilDTO->setNumIdSistema($numIdSistemaSei);
        $objPerfilDTO->setStrNome($this->nomeGestorControleLitigioso);
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Gestor do Controle Litigioso do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiGestorControle = $objPerfilDTO->getNumIdPerfil();


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


        $this->logar('CRIANDO e VINCULANDO RECURSO A PERFIL - motivo EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_consultar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_decisao_uf_listar');

        $this->logar('Atualizando e VINCULANDO RECURSO A PERFIL - modal de situação EM basico');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_visualizar_parametrizar');
        $objRecursoDTO = $this->removerRecursoPerfil($numIdSistemaSei, 'md_lit_situacao_visualizar_parametrizar', $numIdPerfilSeiAdministrador);

        //recuperando o recurso para vincular com o menu
        $objRecursoListarSituacaoLancamentoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiAdministrador, 'md_lit_situacao_lancamento_listar');


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Administração -> Controle de Processos Litigiosos');
        $objItemMenuDTOControleProcesso = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiAdministrador, $numIdMenuSei, $numIdItemMenuSeiAdministracao, null, 'Controle de Processos Litigiosos', 0);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL  Administração -> Controle de Processos Litigiosos -> Situações do Lançamento de Crédito');
        $objItemMenuDTO = new ItemMenuDTO();
        $objItemMenuDTO->retTodos(false);
        $objItemMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objItemMenuDTO->setStrRotulo('Situações do Lançamento de Crédito');
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
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.1.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.1.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV120()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.2.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');


        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();

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
        $objPerfilDTO->setStrNome('Básico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();


        $this->logar('CRIANDO E VINCULANDO AO PERFIL BÁSICO O RECURSO DESATIVAR DECISÃO');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_decisao_desativar');

        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.2.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.2.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV130()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.3.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');


        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();

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
        $objPerfilDTO->setStrNome('Básico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();


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
        $objItemMenuDTO->setStrRotulo('Relatórios');
        $objItemMenuDTO = $objItemMenuRN->consultar($objItemMenuDTO);

        if ($objItemMenuDTO == null) {
            throw new InfraException('Item de menu Relatórios do sistema SEI não encontrado.');
        }

        $numIdItemMenuSeiRelatorio = $objItemMenuDTO->getNumIdItemMenu();


        $this->logar('CRIANDO E VINCULANDO AO PERFIL BÁSICO O RECURSO de relatorio do Antecedente');
        $objRecursoRelatorioAntecedenteDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_relatorio_antecedente');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_modal_relatorio_antecedente');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_relatorio_antecedente_exp_excel');


        $this->logar('CRIANDO E VINCULANDO AO PERFIL BÁSICO O RECURSO de relatorio do Reincidente');
        $objRecursoRelatorioReincidenteDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_relatorio_reincidencia');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_modal_relatorio_reincidencia');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_relatorio_antecedente_exp_excel');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_reinciden_anteceden_consultar');


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL Relatórios -> Processos Litigiosos');
        $objItemMenuDTORelatorioLitigiosos = $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiBasico, $numIdMenuSei, $numIdItemMenuSeiRelatorio, null, 'Processos Litigiosos', 30);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL   Relatórios -> Processos Litigiosos -> Antecedentes');
        $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiBasico, $numIdMenuSei, $objItemMenuDTORelatorioLitigiosos->getNumIdItemMenu(), $objRecursoRelatorioAntecedenteDTO->getNumIdRecurso(), 'Antecendentes', 10);


        $this->logar('CRIANDO e VINCULANDO ITEM MENU A PERFIL   Relatórios -> Processos Litigiosos -> Antecedentes');
        $this->adicionarItemMenu($numIdSistemaSei, $numIdPerfilSeiBasico, $numIdMenuSei, $objItemMenuDTORelatorioLitigiosos->getNumIdItemMenu(), $objRecursoRelatorioReincidenteDTO->getNumIdRecurso(), 'Reincidências Específicas', 20);

        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.3.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.3.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV140()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.4.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');


        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();

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
        $objPerfilDTO->setStrNome('Básico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
        }

        $numIdPerfilSeiBasico = $objPerfilDTO->getNumIdPerfil();


        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objMenuDTO->setStrNome('Principal');
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema SEI não encontrado.');
        }


        $this->logar('CRIANDO E VINCULANDO AO PERFIL BÁSICO O RECURSO de vincular lancamento');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_vincular_lancamento');

        $this->logar('CRIANDO E VINCULANDO AO PERFIL BÁSICO O RECURSO de situação de lançamento por integração');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_situacao_lancam_int_consultar');

        $this->logar('CRIANDO E VINCULANDO AO PERFIL BÁSICO O RECURSO de tipo de outorga e numero de interessado');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_adm_tipo_outor_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_modalidade_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_listar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_cadastrar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_alterar');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_excluir');
        $objRecursoDTO = $this->adicionarRecursoPerfil($numIdSistemaSei, $numIdPerfilSeiBasico, 'md_lit_rel_num_inter_tp_outor_consultar');


        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.4.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.4.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV150()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.5.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');

        $objSistemaRN = new SistemaRN();
        $objPerfilRN = new PerfilRN();
        $objMenuRN = new MenuRN();
        $objItemMenuRN = new ItemMenuRN();

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
        $objPerfilDTO->setStrNome('Básico');
        $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

        if ($objPerfilDTO == null) {
            throw new InfraException('Perfil Básico do sistema SEI não encontrado.');
        }

        $objMenuDTO = new MenuDTO();
        $objMenuDTO->retNumIdMenu();
        $objMenuDTO->setNumIdSistema($numIdSistemaSei);
        $objMenuDTO->setStrNome('Principal');
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);

        if ($objMenuDTO == null) {
            throw new InfraException('Menu do sistema SEI não encontrado.');
        }

        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.5.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.5.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV160()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.6.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.6.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.6.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV170()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.7.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.7.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.7.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV180()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.8.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.8.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.8.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV190()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.9.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.9.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.9.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
    }

    protected function instalarV1100()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.10.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SIP');
        //Atualizando parametro para controlar versao do modulo
        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSip::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.10.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.10.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SIP');
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

}

//========================= INICIO SCRIPT EXECUÇAO =============

try {

    session_start();
    SessaoSip::getInstance(false);
    BancoSip::getInstance()->setBolScript(true);

    if (!ConfiguracaoSip::getInstance()->isSetValor('BancoSip', 'UsuarioScript')) {
        throw new InfraException('Chave BancoSip/UsuarioScript não encontrada.');
    }

    if (InfraString::isBolVazia(ConfiguracaoSip::getInstance()->getValor('BancoSip', 'UsuarioScript'))) {
        throw new InfraException('Chave BancoSip/UsuarioScript não possui valor.');
    }

    if (!ConfiguracaoSip::getInstance()->isSetValor('BancoSip', 'SenhaScript')) {
        throw new InfraException('Chave BancoSip/SenhaScript não encontrada.');
    }

    if (InfraString::isBolVazia(ConfiguracaoSip::getInstance()->getValor('BancoSip', 'SenhaScript'))) {
        throw new InfraException('Chave BancoSip/SenhaScript não possui valor.');
    }

    $objVersaoRN = new MdLitAtualizadorSipRN();
    $objVersaoRN->atualizarVersao();

} catch (Exception $e) {
    echo(InfraException::inspecionar($e));
    try {
        LogSip::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
    }
    exit(1);
}

print PHP_EOL;