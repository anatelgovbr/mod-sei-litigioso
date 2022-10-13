<?
require_once dirname(__FILE__) . '/../web/SEI.php';

class MdLitAtualizadorSeiRN extends InfraRN
{

    private $numSeg = 0;
    private $versaoAtualDesteModulo = '2.0.0';
    private $nomeDesteModulo = 'MÓDULO DE CONTROLE LITIGIOSO';
    private $nomeParametroModulo = 'VERSAO_MODULO_LITIGIOSO';
    private $historicoVersoes = array('0.0.1', '0.0.2', '0.0.3', '0.0.4', '1.0.0', '1.1.0', '1.2.0', '1.3.0', '1.4.0', '1.5.0', '1.6.0', '1.7.0', '1.8.0', '1.9.0', '1.10.0', '2.0.0');

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function inicializar($strTitulo)
    {
        session_start();
        SessaoSEI::getInstance(false);

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
            $this->inicializar('INICIANDO A INSTALAÇÃO/ATUALIZAÇÃO DO ' . $this->nomeDesteModulo . ' NO SEI VERSÃO ' . SEI_VERSAO);

            //checando BDs suportados
            if (!(BancoSEI::getInstance() instanceof InfraMySql) &&
                !(BancoSEI::getInstance() instanceof InfraSqlServer) &&
                !(BancoSEI::getInstance() instanceof InfraOracle)) {
                $this->finalizar('BANCO DE DADOS NÃO SUPORTADO: ' . get_parent_class(BancoSEI::getInstance()), true);
            }

            //testando versao do framework
            $numVersaoInfraRequerida = '1.603.5';
            $versaoInfraFormatada = (int)str_replace('.', '', VERSAO_INFRA);
            $versaoInfraReqFormatada = (int)str_replace('.', '', $numVersaoInfraRequerida);

            if ($versaoInfraFormatada < $versaoInfraReqFormatada) {
                $this->finalizar('VERSÃO DO FRAMEWORK PHP INCOMPATÍVEL (VERSÃO ATUAL ' . VERSAO_INFRA . ', SENDO REQUERIDA VERSÃO IGUAL OU SUPERIOR A ' . $numVersaoInfraRequerida . ')', true);
            }

            //checando permissoes na base de dados
            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

            if (count($objInfraMetaBD->obterTabelas('sei_teste')) == 0) {
                BancoSEI::getInstance()->executarSql('CREATE TABLE sei_teste (id ' . $objInfraMetaBD->tipoNumero() . ' null)');
            }

            BancoSEI::getInstance()->executarSql('DROP TABLE sei_teste');

            $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

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
                    break;

                default:
                    $this->finalizar('A VERSÃO MAIS ATUAL DO ' . $this->nomeDesteModulo . ' (v' . $this->versaoAtualDesteModulo . ') JÁ ESTÁ INSTALADA.');
                    break;

            }

            $this->finalizar('FIM');
            InfraDebug::getInstance()->setBolDebugInfra(true);
        } catch (Exception $e) {
            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(true);
            InfraDebug::getInstance()->setBolEcho(true);
            throw new InfraException('Erro instalando/atualizando versão.', $e);
        }
    }

    protected function instalarv001()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.1 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');


        $this->logar('ADICIONANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('INSERT INTO infra_parametro (valor, nome ) VALUES( \'0.0.1\',  \'' . $this->nomeParametroModulo . '\' )');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.1 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv002()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.2 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');


        $this->logar('CRIANDO A TABELA md_lit_tipo_controle');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_tipo_controle (
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                sigla ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                dta_corte ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_tipo_controle', 'pk_md_lit_tipo_controle', array('id_md_lit_tipo_controle'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_tipo_controle');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_tipo_controle', 1);


        $this->logar('CRIANDO A TABELA md_lit_fase');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_fase (
                id_md_lit_fase ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,    
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_fase', 'pk_md_lit_fase', array('id_md_lit_fase'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_fase');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_fase', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_tp_controle_usu');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_controle_usu (
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_controle_usu', 'pk_md_lit_rel_tp_ctrl_usu', array('id_md_lit_tipo_controle', 'id_usuario'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_controle_usu_01', 'md_lit_rel_tp_controle_usu', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_controle_usu_02', 'md_lit_rel_tp_controle_usu', array('id_usuario'), 'usuario', array('id_usuario'));


        $this->logar('CRIANDO A TABELA md_lit_rel_tp_controle_unid');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_controle_unid (
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_controle_unid', 'pk_md_lit_rel_tp_ctrl_unid', array('id_md_lit_tipo_controle', 'id_unidade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_controle_unid_01', 'md_lit_rel_tp_controle_unid', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_controle_unid_02', 'md_lit_rel_tp_controle_unid', array('id_unidade'), 'unidade', array('id_unidade'));


        $this->logar('CRIANDO A TABELA md_lit_rel_tp_controle_proced');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_controle_proced (
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                id_tipo_procedimento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_controle_proced', 'pk_md_rel_tp_controle_proced', array('id_md_lit_tipo_controle', 'id_tipo_procedimento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_ctrl_proc_01', 'md_lit_rel_tp_controle_proced', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_ctrl_proc_02', 'md_lit_rel_tp_controle_proced', array('id_tipo_procedimento'), 'tipo_procedimento', array('id_tipo_procedimento'));


        $this->logar('CRIANDO A TABELA md_lit_rel_tp_ctrl_proc_sobres');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_ctrl_proc_sobres (
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                id_tipo_procedimento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_ctrl_proc_sobres', 'pk_md_rel_tp_ctrl_proc_sobres', array('id_md_lit_tipo_controle', 'id_tipo_procedimento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_ctrl_pro_sobre_01', 'md_lit_rel_tp_ctrl_proc_sobres', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_ctrl_pro_sobre_02', 'md_lit_rel_tp_ctrl_proc_sobres', array('id_tipo_procedimento'), 'tipo_procedimento', array('id_tipo_procedimento'));


        $this->logar('CRIANDO A TABELA md_lit_situacao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_situacao (
                id_md_lit_situacao ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                sin_instauracao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                sin_conclusiva ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                sin_intimacao ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                sin_defesa ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                sin_recursal ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                prazo ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                ordem ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL, 
                id_md_lit_fase ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                sin_opcional ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_situacao', 'pk_md_lit_situacao', array('id_md_lit_situacao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_sit_tp_controle_01', 'md_lit_situacao', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_sit_fase_01', 'md_lit_situacao', array('id_md_lit_fase'), 'md_lit_fase', array('id_md_lit_fase'));


        $this->logar('CRIANDO A TABELA seq_md_lit_situacao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_situacao', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_sit_serie');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_sit_serie (
                id_md_lit_situacao ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                id_serie ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_sit_serie', 'pk_md_lit_rel_sit_serie', array('id_md_lit_situacao', 'id_serie'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_lit_rel_sit_serie_01', 'md_lit_rel_sit_serie', array('id_md_lit_situacao'), 'md_lit_situacao', array('id_md_lit_situacao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_lit_rel_sit_serie_02', 'md_lit_rel_sit_serie', array('id_serie'), 'serie', array('id_serie'));


        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'0.0.2\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.2 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv003()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.3 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');


        $this->logar('CRIANDO A TABELA md_lit_conduta');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_conduta (
                id_md_lit_conduta ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL )');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_conduta', 'pk_md_lit_conduta', array('id_md_lit_conduta'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_conduta');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_conduta', 1);


        $this->logar('CRIANDO A TABELA md_lit_conduta');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_disp_normat (
                id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                norma ' . $objInfraMetaBD->tipoTextoVariavel(150) . ' NOT NULL,
                url ' . $objInfraMetaBD->tipoTextoVariavel(2083) . ' NULL,
                dispositivo ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL, 
                descricao ' . $objInfraMetaBD->tipoTextoVariavel(2000) . ' NOT NULL,                                                                         
                sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
                sin_revogado ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL)');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_disp_normat', 'pk_md_lit_disp_normat', array('id_md_lit_disp_normat'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_disp_normat');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_disp_normat', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_disp_norm_conduta');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_disp_norm_conduta (
                id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                id_md_lit_conduta ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL )');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_disp_norm_conduta', 'pk_md_lit_rel_disp_norm_condut', array('id_md_lit_disp_normat', 'id_md_lit_conduta'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_dispositivo_norm_01', 'md_lit_rel_disp_norm_conduta', array('id_md_lit_disp_normat'), 'md_lit_disp_normat', array('id_md_lit_disp_normat'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_conduta_01', 'md_lit_rel_disp_norm_conduta', array('id_md_lit_conduta'), 'md_lit_conduta', array('id_md_lit_conduta'));


        $this->logar('CRIANDO A TABELA md_lit_rel_disp_norm_tipo_ctrl');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_disp_norm_tipo_ctrl (
                id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL )');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_disp_norm_tipo_ctrl', 'pk_md_lit_rel_disp_norm_tp_ctr', array('id_md_lit_disp_normat', 'id_md_lit_tipo_controle'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_dispositivo_norm_02', 'md_lit_rel_disp_norm_tipo_ctrl', array('id_md_lit_disp_normat'), 'md_lit_disp_normat', array('id_md_lit_disp_normat'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_tipo_controle_01', 'md_lit_rel_disp_norm_tipo_ctrl', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));


        $this->logar('CRIANDO A TABELA md_lit_assoc_disp_normat');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_assoc_disp_normat (
                id_md_lit_assoc_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL )');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_assoc_disp_normat', 'pk_md_lit_assoc_disp_normat', array('id_md_lit_assoc_disp_normat'));


        $this->logar('CRIANDO A TABELA seq_md_lit_assoc_disp_normat');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_assoc_disp_normat', 1);


        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'0.0.3\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.3 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv004()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.4 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');


        $this->logar('CRIANDO A TABELA md_lit_tipo_decisao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_tipo_decisao(
                  id_md_lit_tipo_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                  nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
                  descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL,
                  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_tipo_decisao', 'pk_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_tipo_decisao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_tipo_decisao', 1);


        $this->logar('CRIANDO A TABELA md_lit_especie_decisao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_especie_decisao (
                  id_md_lit_especie_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                  nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
                  sin_rd_gestao_multa ' . $objInfraMetaBD->tipoTextoFixo(1) . ' DEFAULT NULL,
                  sin_rd_indicacao_prazo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' DEFAULT NULL,
                  sin_rd_indicacao_obrigacoes ' . $objInfraMetaBD->tipoTextoFixo(1) . ' DEFAULT NULL,
                  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_especie_decisao', 'pk_lit_especie_decisao', array('id_md_lit_especie_decisao'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_tipo_decisao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_especie_decisao', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_tp_especie_dec');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_especie_dec (    
                  id_md_lit_tipo_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                  id_md_lit_especie_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_especie_dec', 'pk_rel_tp_esp_dec_01', array('id_md_lit_tipo_decisao', 'id_md_lit_especie_decisao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_especie_decisao_01', 'md_lit_rel_tp_especie_dec', array('id_md_lit_tipo_decisao'), 'md_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_especie_decisao_02', 'md_lit_rel_tp_especie_dec', array('id_md_lit_especie_decisao'), 'md_lit_especie_decisao', array('id_md_lit_especie_decisao'));


        $this->logar('CRIANDO A TABELA md_lit_obrigacao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_obrigacao (
                  id_md_lit_obrigacao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                  nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
                  descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
                  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_obrigacao', 'pk_lit_obrigacao', array('id_md_lit_obrigacao'));


        $this->logar('CRIANDO A TABELA md_lit_obrigacao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE  md_lit_rel_esp_decisao_obr (
                  id_md_lit_especie_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                  id_md_lit_obrigacao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria(' md_lit_rel_esp_decisao_obr', 'pk_rel_esp_dec_obr_01', array('id_md_lit_especie_decisao', 'id_md_lit_obrigacao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_esp_dec_obr_01', ' md_lit_rel_esp_decisao_obr', array('id_md_lit_especie_decisao'), 'md_lit_especie_decisao', array('id_md_lit_especie_decisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_esp_dec_obr_02', ' md_lit_rel_esp_decisao_obr', array('id_md_lit_obrigacao'), 'md_lit_obrigacao', array('id_md_lit_obrigacao'));

        $this->logar('CRIANDO A SEQUENCE md_lit_obrigacao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_obrigacao', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_tipo_ctrl_tipo_dec');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tipo_ctrl_tipo_dec (
                id_md_lit_tipo_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tipo_ctrl_tipo_dec', 'pk_lit_rel_tipo_ctrl_tipo_dec1', array('id_md_lit_tipo_controle', 'id_md_lit_tipo_decisao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_ctrl_tipo_dec_01', 'md_lit_rel_tipo_ctrl_tipo_dec', array('id_md_lit_tipo_decisao'), 'md_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_ctrl_tipo_dec_02', 'md_lit_rel_tipo_ctrl_tipo_dec', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));

        $this->logar('ADICIONANDO COLUNA sin_decisoria A TABELA md_lit_situacao');
        BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_situacao ADD sin_decisoria ' . $objInfraMetaBD->tipoTextoFixo(1));


        $this->logar('CRIANDO A TABELA md_lit_controle');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_controle (
                id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_procedimento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NULL,
                id_documento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NULL,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                dta_instauracao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_controle', 'pk_litcontrole', array('id_md_lit_controle'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_mdlitcontrole_documento', 'md_lit_controle', array('id_documento'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_mdlitcontrole_procedimento', 'md_lit_controle', array('id_procedimento'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_controle', 'md_lit_controle', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_controle');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_controle', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_dis_nor_con_ctr');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_dis_nor_con_ctr (
                id_md_lit_rel_dis_nor_con_ctr ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_conduta ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                dta_infracao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_dis_nor_con_ctr', 'pk_md_lit_rel_dis_nor_con_ctr', array('id_md_lit_rel_dis_nor_con_ctr'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_condu_litrelreldisnorconctr', 'md_lit_rel_dis_nor_con_ctr', array('id_md_lit_conduta'), 'md_lit_conduta', array('id_md_lit_conduta'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_dinor_litrelreldisnorconctr', 'md_lit_rel_dis_nor_con_ctr', array('id_md_lit_disp_normat'), 'md_lit_disp_normat', array('id_md_lit_disp_normat'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_contr_litrelreldisnorconctr', 'md_lit_rel_dis_nor_con_ctr', array('id_md_lit_controle'), 'md_lit_controle', array('id_md_lit_controle'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_rel_dis_nor_con_ctr');

        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_rel_dis_nor_con_ctr', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_protoco_protoco');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_protoco_protoco (
                id_md_lit_rel_protoco_protoco ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
                id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' ,
                id_protocolo_1 ' . $objInfraMetaBD->tipoNumeroGrande() . ' ,
                id_protocolo_2 ' . $objInfraMetaBD->tipoNumeroGrande() . ' ,
                id_documento ' . $objInfraMetaBD->tipoNumeroGrande() . ' ,
                sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ,
                dta_sobrestamento ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_protoco_protoco', 'pk_md_lit_rel_protoco_protoco', array('id_md_lit_rel_protoco_protoco'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_litrelprotprot_prot1', 'md_lit_rel_protoco_protoco', array('id_protocolo_1'), 'protocolo', array('id_protocolo'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_litrelprotprot_prot2', 'md_lit_rel_protoco_protoco', array('id_protocolo_2'), 'protocolo', array('id_protocolo'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_litrelprotoproto_documento', 'md_lit_rel_protoco_protoco', array('id_documento'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_litcontrole_litrelprotprot', 'md_lit_rel_protoco_protoco', array('id_md_lit_controle'), 'md_lit_controle', array('id_md_lit_controle'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_rel_protoco_protoco');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_rel_protoco_protoco', 1);


        $this->logar('CRIANDO A TABELA md_lit_modalidade');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_modalidade (
                id_md_lit_modalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_modalidade', 'pk_md_lit_modalidade', array('id_md_lit_modalidade'));

        $this->logar('CRIANDO A SEQUENCE md_lit_modalidade');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_modalidade', 1);


        $this->logar('CRIANDO A TABELA md_lit_abrangencia');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_abrangencia (
                id_md_lit_abrangencia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_abrangencia', 'pk_md_lit_abrangencia', array('id_md_lit_abrangencia'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_abrangencia');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_abrangencia', 1);


        $this->logar('CRIANDO A TABELA md_lit_servico_integracao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_servico_integracao (
                id_md_lit_servico_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome_integracao ' . $objInfraMetaBD->tipoTextoVariavel(30) . ' NOT NULL, 
                endereco_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                operacao_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NULL,
                mapeamento_codigo ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NULL,
                mapeamento_sigla ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NULL,
                mapeamento_descricao ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NULL,
                mapeamento_situacao ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NULL,
                chave_unica ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_servico_integracao', 'pk_md_lit_servico_integracao', array('id_md_lit_servico_integracao'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_servico_integracao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_servico_integracao', 1);


        $this->logar('CRIANDO A TABELA md_lit_servico');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_servico (
                id_md_lit_servico ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_servico_integracao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                sta_origem ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL,
                codigo ' . $objInfraMetaBD->tipoTextoVariavel(10) . ' NOT NULL,
                sigla ' . $objInfraMetaBD->tipoTextoVariavel(10) . ' NULL,
                descricao ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL,
                sin_ativo_integracao ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_servico', 'pk_md_lit_servico', array('id_md_lit_servico'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_servico_integracao', 'md_lit_servico', array('id_md_lit_servico_integracao'), 'md_lit_servico_integracao', array('id_md_lit_servico_integracao'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_servico');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_servico', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_servico_abrangen');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_servico_abrangen (
                id_md_lit_servico ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_abrangencia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_servico_abrangen', 'pk1_md_lit_rel_servico_abrange', array('id_md_lit_servico', 'id_md_lit_abrangencia'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_servico_abrange', 'md_lit_rel_servico_abrangen', array('id_md_lit_abrangencia'), 'md_lit_abrangencia', array('id_md_lit_abrangencia'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_servico_abrange', 'md_lit_rel_servico_abrangen', array('id_md_lit_servico'), 'md_lit_servico', array('id_md_lit_servico'));


        $this->logar('CRIANDO A TABELA md_lit_rel_servico_modalidade');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_servico_modalidade (
                id_md_lit_servico ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_modalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_servico_modalidade', 'pk1_md_lit_rel_servico_modalid', array('id_md_lit_servico', 'id_md_lit_modalidade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_servico_modalid', 'md_lit_rel_servico_modalidade', array('id_md_lit_modalidade'), 'md_lit_modalidade', array('id_md_lit_modalidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_servico_modalid', 'md_lit_rel_servico_modalidade', array('id_md_lit_servico'), 'md_lit_servico', array('id_md_lit_servico'));


        $this->logar('CRIANDO A TABELA md_lit_dado_interessado');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_dado_interessado (
                id_md_lit_dado_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_contato ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                sin_outorgado ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_dado_interessado', 'pk1_md_lit_dado_interessado', array('id_md_lit_dado_interessado'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_dado_interessado', 'md_lit_dado_interessado', array('id_md_lit_controle'), 'md_lit_controle', array('id_md_lit_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_dado_interessado', 'md_lit_dado_interessado', array('id_contato'), 'contato', array('id_contato'));


        $this->logar('CRIANDO A SEQUENCE md_lit_dado_interessado');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_dado_interessado', 1);

        $this->logar('Adicionando A coluna sin_param_modal_compl_interes na TABELA md_lit_tipo_controle');
        $coluna = $objInfraMetaBD->obterColunasTabela('md_lit_tipo_controle', 'sin_param_modal_compl_interes');

        if ($coluna == null || !is_array($coluna)) {
            $objInfraMetaBD->adicionarColuna('md_lit_tipo_controle', 'sin_param_modal_compl_interes', '' . $objInfraMetaBD->tipoTextoVariavel(1), 'NULL');
        }


        $this->logar('CRIANDO A TABELA md_lit_nome_funcional');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_nome_funcional (
                id_md_lit_nome_funcional ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_nome_funcional', 'pk1_md_lit_nome_funcional', array('id_md_lit_nome_funcional'));

        $this->logar('CRIANDO A TABELA seq_md_lit_nome_funcional');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_nome_funcional', 1);


        $this->logar('CRIANDO A TABELA md_lit_param_interessado');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_param_interessado (
                id_md_lit_param_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_nome_funcional ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                sin_exibe ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL,
                sin_obrigatorio ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                label_campo ' . $objInfraMetaBD->tipoTextoVariavel(25) . ' NULL,
                tamanho ' . $objInfraMetaBD->tipoNumero(3) . ' NULL,
                descricao_ajuda ' . $objInfraMetaBD->tipoTextoVariavel(150) . ' NULL,
                sin_campo_mapeado ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_param_interessado', 'pk1_md_lit_param_interessado', array('id_md_lit_param_interessado'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_param_interessado', 'md_lit_param_interessado', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_nome_funcional', 'md_lit_param_interessado', array('id_md_lit_nome_funcional'), 'md_lit_nome_funcional', array('id_md_lit_nome_funcional'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_param_interessado');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_param_interessado', 1);


        $this->logar('populando a tabela: md_lit_nome_funcional');
        $objMdLitNomeFuncionalRN = new MdLitNomeFuncionalRN();
        $arrNome = array('CNPJ/CPF', 'Outorga', 'Número', 'Serviço', 'Modalidade',
            'Abrangência', 'Estado', 'Cidade');
        foreach ($arrNome as $nome) {
            $objMdLitNomeFuncionalDTO = new MdLitNomeFuncionalDTO();
            $objMdLitNomeFuncionalDTO->setStrNome($nome);
            $objMdLitNomeFuncionalRN->cadastrar($objMdLitNomeFuncionalDTO);
        }


        $this->logar('CRIANDO A TABELA md_lit_funcionalidade');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_funcionalidade (
                id_md_lit_funcionalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_funcionalidade', 'pk1_md_lit_funcionalidade', array('id_md_lit_funcionalidade'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_funcionalidade');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_funcionalidade', 1);


        $this->logar('CRIANDO A TABELA md_lit_integracao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_integracao (
                id_md_lit_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_funcionalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(30) . ' NOT NULL,
                endereco_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                operaca_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_integracao', 'pk1_md_lit_integracao', array('id_md_lit_integracao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_integracao', 'md_lit_integracao', array('id_md_lit_funcionalidade'), 'md_lit_funcionalidade', array('id_md_lit_funcionalidade'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_integracao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_integracao', 1);


        $this->logar('CRIANDO A TABELA md_lit_mapea_param_saida');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_mapea_param_saida (
                id_md_lit_mapea_param_saida ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_nome_funcional ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                campo ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NOT NULL,
                chave_unica ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_mapea_param_saida', 'pk_md_lit_mapea_param_saida', array('id_md_lit_mapea_param_saida'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_mapea_param_saida', 'md_lit_mapea_param_saida', array('id_md_lit_integracao'), 'md_lit_integracao', array('id_md_lit_integracao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_mapea_param_saida', 'md_lit_mapea_param_saida', array('id_md_lit_nome_funcional'), 'md_lit_nome_funcional', array('id_md_lit_nome_funcional'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_mapea_param_saida');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_mapea_param_saida', 1);


        $this->logar('CRIANDO A TABELA md_lit_mapea_param_entrada');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_mapea_param_entrada (
                id_md_lit_mapea_param_entrada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_nome_funcional ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                campo ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NOT NULL,
                chave_unica ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_mapea_param_entrada', 'pk_md_lit_mapea_param_entrada', array('id_md_lit_mapea_param_entrada'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_mapea_param_entrada', 'md_lit_mapea_param_entrada', array('id_md_lit_integracao'), 'md_lit_integracao', array('id_md_lit_integracao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_mapea_param_entrada', 'md_lit_mapea_param_entrada', array('id_md_lit_nome_funcional'), 'md_lit_nome_funcional', array('id_md_lit_nome_funcional'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_mapea_param_entrada');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_mapea_param_entrada', 1);


        $this->logar('Populando a tabela: md_lit_funcionalidade');

        $objMdLitFuncionalidadeRN = new  MdLitFuncionalidadeRN();
        $objMdLitFuncionalidadeDTO = new MdLitFuncionalidadeDTO();
        $objMdLitFuncionalidadeDTO->setStrNome('Dados Complementares do Interessado - Validação');
        $objMdLitFuncionalidadeRN->cadastrar($objMdLitFuncionalidadeDTO);


        $this->logar('CRIANDO A TABELA md_lit_numero_interessado');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_numero_interessado (
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_dado_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                numero ' . $objInfraMetaBD->tipoTextoVariavel(999) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_numero_interessado', 'pk1_md_lit_numero_interessado', array('id_md_lit_numero_interessado'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_numero_interessado', 'md_lit_numero_interessado', array('id_md_lit_dado_interessado'), 'md_lit_dado_interessado', array('id_md_lit_dado_interessado'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_numero_interessado');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_numero_interessado', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_num_inter_modali');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_num_inter_modali (
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_modalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');


        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_num_inter_modali', 'pk1_md_lit_rel_num_inter_moda', array('id_md_lit_numero_interessado', 'id_md_lit_modalidade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_num_inter_moda', 'md_lit_rel_num_inter_modali', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_num_inter_moda', 'md_lit_rel_num_inter_modali', array('id_md_lit_modalidade'), 'md_lit_modalidade', array('id_md_lit_modalidade'));


        $this->logar('CRIANDO A TABELA md_lit_rel_num_inter_abrang');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_num_inter_abrang (
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_abrangencia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_num_inter_abrang', 'pk1_md_lit_rel_num_inter_abra', array('id_md_lit_numero_interessado', 'id_md_lit_abrangencia'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_num_inter_abra', 'md_lit_rel_num_inter_abrang', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_num_inter_abra', 'md_lit_rel_num_inter_abrang', array('id_md_lit_abrangencia'), 'md_lit_abrangencia', array('id_md_lit_abrangencia'));


        $this->logar('CRIANDO A TABELA md_lit_rel_num_inter_servico');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_num_inter_servico (
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_servico ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_num_inter_servico', 'pk1_md_lit_rel_num_inter_serv', array('id_md_lit_numero_interessado', 'id_md_lit_servico'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_num_inter_serv', 'md_lit_rel_num_inter_servico', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_num_inter_serv', 'md_lit_rel_num_inter_servico', array('id_md_lit_servico'), 'md_lit_servico', array('id_md_lit_servico'));


        $this->logar('CRIANDO A TABELA md_lit_rel_num_inter_cidade');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_num_inter_cidade (
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_cidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');


        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_num_inter_cidade', 'pk1_md_lit_rel_num_inter_cida', array('id_md_lit_numero_interessado', 'id_cidade'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_num_inter_cida', 'md_lit_rel_num_inter_cidade', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_num_inter_cida', 'md_lit_rel_num_inter_cidade', array('id_cidade'), 'cidade', array('id_cidade'));


        $this->logar('CRIANDO A TABELA md_lit_rel_num_inter_uf');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_num_inter_uf (
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_uf ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_num_inter_uf', 'pk1_md_lit_rel_num_inter_uf', array('id_md_lit_numero_interessado', 'id_uf'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_num_inter_uf', 'md_lit_rel_num_inter_uf', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_num_inter_uf', 'md_lit_rel_num_inter_uf', array('id_uf'), 'uf', array('id_uf'));


        $this->logar('CRIANDO A TABELA md_lit_campo_integracao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_campo_integracao (
                id_md_lit_campo_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_funcionalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome_campo ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NULL,
                sta_parametro ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_campo_integracao', 'pk1_md_lit_campo_integracao', array('id_md_lit_campo_integracao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_campo_integracao', 'md_lit_campo_integracao', array('id_md_lit_funcionalidade'), 'md_lit_funcionalidade', array('id_md_lit_funcionalidade'));

        $this->logar('Alterando A TABELA md_lit_mapea_param_entrada e md_lit_mapea_param_saida');

        BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_mapea_param_entrada ADD id_md_lit_campo_integracao ' . $objInfraMetaBD->tipoNumero());
        BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_mapea_param_entrada ADD constraint fk3_md_lit_mapea_param_entrada foreign key (id_md_lit_campo_integracao) references md_lit_campo_integracao (id_md_lit_campo_integracao) ');
        BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_mapea_param_saida ADD id_md_lit_campo_integracao ' . $objInfraMetaBD->tipoNumero());
        BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_mapea_param_saida ADD constraint fk3_md_lit_mapea_param_saida foreign key (id_md_lit_campo_integracao) references md_lit_campo_integracao (id_md_lit_campo_integracao) ');


        $this->logar('CRIANDO A TABELA md_lit_mapea_param_valor');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_mapea_param_valor (
                id_md_lit_mapea_param_valor ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_mapea_param_entrada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                valor_default ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_mapea_param_valor', 'pk1_md_lit_mapea_param_valor', array('id_md_lit_mapea_param_valor'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_mapea_param_valor', 'md_lit_mapea_param_valor', array('id_md_lit_mapea_param_entrada'), 'md_lit_mapea_param_entrada', array('id_md_lit_mapea_param_entrada'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_mapea_param_valor', 'md_lit_mapea_param_valor', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_mapea_param_valor');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_mapea_param_valor', 1);

        $this->logar('Populando a tabela: md_lit_funcionalidade');
        $arrDados = array(
            array('idMdLitFuncionalidade' => 2, 'nome' => 'Arrecadação Lançamento de Crédito'),
            array('idMdLitFuncionalidade' => 3, 'nome' => 'Arrecadação Consulta Lançamento'),
            array('idMdLitFuncionalidade' => 4, 'nome' => 'Arrecadação Cancelar Lançamento'),
            array('idMdLitFuncionalidade' => 5, 'nome' => 'Arrecadação Retificar Lançamento'),
            array('idMdLitFuncionalidade' => 6, 'nome' => 'Arrecadação Suspender Lançamento'),
            array('idMdLitFuncionalidade' => 7, 'nome' => 'Arrecadação Denegar Recurso'),
            array('idMdLitFuncionalidade' => 8, 'nome' => 'Arrecadação Cancelar Recurso'),
            array('idMdLitFuncionalidade' => 9, 'nome' => 'Arrecadação Listar Motivos de Cancelamento de Lançamento'),
            array('idMdLitFuncionalidade' => 10, 'nome' => 'Gerar Número do Interessado para Entidade não Outorgada')
        );

        $objMdLitFuncionalidadeRN = new MdLitFuncionalidadeRN();
        foreach ($arrDados as $dados) {
            $objMdLitFuncionalidadeDTO = new MdLitFuncionalidadeDTO();
            $objMdLitFuncionalidadeDTO->setNumIdMdLitFuncionalidade($dados['idMdLitFuncionalidade']);
            $objMdLitFuncionalidadeDTO->setStrNome($dados['nome']);
            $objMdLitFuncionalidadeRN->cadastrar($objMdLitFuncionalidadeDTO);
        }

        $this->logar('Populando a tabela: md_lit_campo_integracao');

        $arrDados = array(
            #Arrecadação Lançamento de Crédito
            //Entrada
            array('idMdLitCampoIntegracao' => 1, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 2, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Data de aplicação da Sanção', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 3, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Data de Vencimento', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 4, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Valor Total', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 5, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Código da Receita', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 6, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Número do Processo', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 7, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Justificativa do Lançamento', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 8, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Usuário de Inclusão', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 9, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Sistema de Origem', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 10, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Validar Majoração', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 78, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'CNPJ/CPF', 'staParametro' => 'E'),

            //Saida
            array('idMdLitCampoIntegracao' => 11, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Sequencial', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 12, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Link para Boleto', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 13, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Código da Receita', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 14, 'idMdLitFuncionalidade' => 2, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'S'),

            #Arrecadação Consulta Lançamento
            //Entrada
            array('idMdLitCampoIntegracao' => 15, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 16, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Sequencial', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 17, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Renúncia a Recurso', 'staParametro' => 'E'),

            //Saída
            array('idMdLitCampoIntegracao' => 18, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Situação Lançamento', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 19, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Desbloqueio Processo Vencido', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 20, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Vencido', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 21, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Data do Último Pagamento', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 22, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Valor do Último Pagamento', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 23, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Valor do Desconto', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 24, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Link para Boleto', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 25, 'idMdLitFuncionalidade' => 3, 'nomeCampo' => 'Valor Atualizado', 'staParametro' => 'S'),

            #Arrecadação Cancelar Lançamento
            //Entrada
            array('idMdLitCampoIntegracao' => 26, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 27, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Sequencial', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 28, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Motivo de Cancelamento', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 29, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Número do Processo', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 30, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Justificativa de Cancelamento', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 31, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Usuário de Inclusão', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 32, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Sistema de Origem', 'staParametro' => 'E'),

            //Saída
            array('idMdLitCampoIntegracao' => 33, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 34, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Sequencial', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 35, 'idMdLitFuncionalidade' => 4, 'nomeCampo' => 'Situação', 'staParametro' => 'S'),

            #Arrecadação Retificar Lançamento
            //Entrada
            array('idMdLitCampoIntegracao' => 36, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 37, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Data de Aplicação da Sanção', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 38, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Data de Vencimento', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 39, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Valor Total', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 40, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Código da Receita', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 41, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Data da Constituição', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 42, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Número do Processo', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 43, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Justificativa Lançamento', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 44, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Usuário de Inclusão', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 45, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Sistema de Origem', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 46, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Renúncia de Recurso', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 47, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Sequencial', 'staParametro' => 'E'),

            //Saída
            array('idMdLitCampoIntegracao' => 48, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Código da Receita', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 49, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Sequencial', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 50, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 51, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Link Boleto', 'staParametro' => 'S'),

            #Arrecadação Suspender Lançamento
            //Entrada
            array('idMdLitCampoIntegracao' => 52, 'idMdLitFuncionalidade' => 6, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 53, 'idMdLitFuncionalidade' => 6, 'nomeCampo' => 'Sequencial', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 54, 'idMdLitFuncionalidade' => 6, 'nomeCampo' => 'Observação', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 55, 'idMdLitFuncionalidade' => 6, 'nomeCampo' => 'Usuário de Inclusão', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 56, 'idMdLitFuncionalidade' => 6, 'nomeCampo' => 'Sistema de Origem', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 57, 'idMdLitFuncionalidade' => 6, 'nomeCampo' => 'Data da Suspensão', 'staParametro' => 'E'),

            //Saída
            array('idMdLitCampoIntegracao' => 58, 'idMdLitFuncionalidade' => 6, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 59, 'idMdLitFuncionalidade' => 6, 'nomeCampo' => 'Sequencial', 'staParametro' => 'S'),
            #Arrecadação Denegar Recurso
            //Entrada
            array('idMdLitCampoIntegracao' => 60, 'idMdLitFuncionalidade' => 7, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 61, 'idMdLitFuncionalidade' => 7, 'nomeCampo' => 'Sequencial', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 62, 'idMdLitFuncionalidade' => 7, 'nomeCampo' => 'Data Denegação', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 63, 'idMdLitFuncionalidade' => 7, 'nomeCampo' => 'Observação', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 64, 'idMdLitFuncionalidade' => 7, 'nomeCampo' => 'Sistema de Origem', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 65, 'idMdLitFuncionalidade' => 7, 'nomeCampo' => 'Usuário de Inclusão', 'staParametro' => 'E'),

            //Saída
            array('idMdLitCampoIntegracao' => 66, 'idMdLitFuncionalidade' => 7, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 67, 'idMdLitFuncionalidade' => 7, 'nomeCampo' => 'Sequencial', 'staParametro' => 'S'),

            #Arrecadação Cancelar Recurso
            //Entrada
            array('idMdLitCampoIntegracao' => 68, 'idMdLitFuncionalidade' => 8, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 69, 'idMdLitFuncionalidade' => 8, 'nomeCampo' => 'Sequencial', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 70, 'idMdLitFuncionalidade' => 8, 'nomeCampo' => 'Observação', 'staParametro' => 'E'),
            array('idMdLitCampoIntegracao' => 71, 'idMdLitFuncionalidade' => 8, 'nomeCampo' => 'Sistema de Origem', 'staParametro' => 'E'),

            //Saída
            array('idMdLitCampoIntegracao' => 72, 'idMdLitFuncionalidade' => 8, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 73, 'idMdLitFuncionalidade' => 8, 'nomeCampo' => 'Sequencial', 'staParametro' => 'S'),

            #Arrecadação Listar Motivos de Cancelamento de Lançamento
            //Saída
            array('idMdLitCampoIntegracao' => 74, 'idMdLitFuncionalidade' => 9, 'nomeCampo' => 'ID Motivo', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 75, 'idMdLitFuncionalidade' => 9, 'nomeCampo' => 'Descrição Motivo', 'staParametro' => 'S'),

            #Gerar Numero de interessado para Entidade não Outorgada
            //Entrada
            array('idMdLitCampoIntegracao' => 76, 'idMdLitFuncionalidade' => 10, 'nomeCampo' => 'Sistema de Origem', 'staParametro' => 'E'),

            //Saída
            array('idMdLitCampoIntegracao' => 77, 'idMdLitFuncionalidade' => 10, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'S'),
        );

        $objMdLitCampoIntegracaoRN = new MdLitCampoIntegracaoRN();
        foreach ($arrDados as $dados) {
            $objMdLitCampoIntegracaoDTO = new MdLitCampoIntegracaoDTO();
            $objMdLitCampoIntegracaoDTO->setNumIdMdLitCampoIntegracao($dados['idMdLitCampoIntegracao']);
            $objMdLitCampoIntegracaoDTO->setNumIdMdLitFuncionalidade($dados['idMdLitFuncionalidade']);
            $objMdLitCampoIntegracaoDTO->setStrNomeCampo($dados['nomeCampo']);
            $objMdLitCampoIntegracaoDTO->setStrStaParametro($dados['staParametro']);
            $objMdLitCampoIntegracaoRN->cadastrar($objMdLitCampoIntegracaoDTO);
        }


        $this->logar('CRIANDO A TABELA md_lit_mapea_param_valor');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_processo_situacao (
                id_md_lit_processo_situacao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_situacao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_documento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
                id_procedimento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
                id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                deposito_extrajudicial ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                valor_deposito_extrajudicial ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                dta_deposito_extrajudicial ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_data ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
                dta_intecorrente ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_quinquenal ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dth_inclusao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
                id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                sin_altera_prescricao ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                ) ');


        $objInfraMetaBD->adicionarChavePrimaria('md_lit_processo_situacao', 'pk1_md_lit_processo_situacao', array('id_md_lit_processo_situacao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_md_lit_situacao'), 'md_lit_situacao', array('id_md_lit_situacao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_documento'), 'documento', array('id_documento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_procedimento'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_unidade'), 'unidade', array('id_unidade'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_processo_situacao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_processo_situacao', 1);


        $this->logar('CRIANDO A TABELA md_lit_decisao');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_decisao (
                id_md_lit_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_processo_situacao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_rel_dis_nor_con_ctr ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_obrigacao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_md_lit_tipo_decisao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_md_lit_especie_decisao ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                multa ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                prazo ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                dth_inclusao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_decisao', 'pk1_md_lit_decisao', array('id_md_lit_decisao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_processo_situacao'), 'md_lit_processo_situacao', array('id_md_lit_processo_situacao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_rel_dis_nor_con_ctr'), 'md_lit_rel_dis_nor_con_ctr', array('id_md_lit_rel_dis_nor_con_ctr'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_obrigacao'), 'md_lit_obrigacao', array('id_md_lit_obrigacao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_tipo_decisao'), 'md_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_especie_decisao'), 'md_lit_especie_decisao', array('id_md_lit_especie_decisao'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_processo_situacao');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_decisao', 1);


        $this->logar('CRIANDO A TABELA md_lit_situacao_lancam_int');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_situacao_lancam_int (
                id_md_lit_situacao_lancam_int ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome_integracao ' . $objInfraMetaBD->tipoTextoVariavel(30) . 'NOT NULL,
                endereco_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                operacao_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                mapeamento_codigo ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                mapeamento_descricao ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL,
                chave_unica ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_situacao_lancam_int', 'pk1_md_lit_situacao_lancam_int', array('id_md_lit_situacao_lancam_int'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_situacao_lancam_int');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_situacao_lancam_int', 1);


        $this->logar('CRIANDO A TABELA md_lit_situacao_lancamento');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_situacao_lancamento (
                id_md_lit_situacao_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_situacao_lancam_int ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . 'NOT NULL,
                codigo ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                cor_situacao ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL,
                sin_ativo_integracao ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                sta_origem ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                sin_cancelamento ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_situacao_lancamento', 'pk1_md_lit_situacao_lancamento', array('id_md_lit_situacao_lancamento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_situacao_lancamento', 'md_lit_situacao_lancamento', array('id_md_lit_situacao_lancam_int'), 'md_lit_situacao_lancam_int', array('id_md_lit_situacao_lancam_int'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_situacao_lancamento');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_situacao_lancamento', 1);


        $this->logar('CRIANDO A TABELA md_lit_lancamento');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_lancamento (
                id_md_lit_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_situacao_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                tipo_lancamento ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                sequencial ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NOT NULL,
                dta_decisao ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_intimacao ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_vencimento ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_prazo_defesa ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_ultimo_pagamento ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                vlr_lancamento ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                vlr_desconto ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                vlr_pago ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                vlr_saldo_devedor ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                dth_inclusao ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                link_boleto ' . $objInfraMetaBD->tipoTextoVariavel(2083) . ' NULL,
                numero_interessado ' . $objInfraMetaBD->tipoTextoVariavel(999) . ' NULL,
                sin_constituicao_definitiva ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                sin_renuncia_recorrer ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                dta_intimacao_definitiva ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_constituicao_definitiva ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                justificativa ' . $objInfraMetaBD->tipoTextoVariavel(255) . ' NULL,
                codigo_receita ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL,
                id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_procedimento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
                sin_suspenso ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                id_md_lit_integracao ' . $objInfraMetaBD->tipoNumero() . ' NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_lancamento', 'pk1_md_lit_lancamento', array('id_md_lit_lancamento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_lancamento', 'md_lit_lancamento', array('id_md_lit_situacao_lancamento'), 'md_lit_situacao_lancamento', array('id_md_lit_situacao_lancamento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_lancamento', 'md_lit_lancamento', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_lancamento', 'md_lit_lancamento', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_lit_lancamento', 'md_lit_lancamento', array('id_procedimento'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_lit_lancamento', 'md_lit_lancamento', array('id_md_lit_integracao'), 'md_lit_integracao', array('id_md_lit_integracao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_lit_lancamento', 'md_lit_lancamento', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_lancamento');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_lancamento', 1);


        $this->logar('CRIANDO A TABELA md_lit_historic_lancamento');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_historic_lancamento (
                id_md_lit_historic_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_situacao_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_md_lit_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                tipo_lancamento ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                sequencial ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NOT NULL,
                dta_decisao ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_intimacao ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_vencimento ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_prazo_defesa ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_ultimo_pagamento ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                vlr_lancamento ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                vlr_desconto ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                vlr_pago ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                vlr_saldo_devedor ' . $objInfraMetaBD->tipoNumeroDecimal(19, 2) . ' NULL,
                dth_inclusao ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                link_boleto ' . $objInfraMetaBD->tipoTextoVariavel(2083) . ' NULL,
                numero_interessado ' . $objInfraMetaBD->tipoTextoVariavel(999) . ' NULL,
                sin_constituicao_definitiva ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                sin_renuncia_recorrer ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                dta_intimacao_definitiva ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                dta_constituicao_definitiva ' . $objInfraMetaBD->tipoDataHora() . ' NULL,
                justificativa ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL,
                codigo_receita ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL,
                id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                id_procedimento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
                sin_suspenso ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL,
                id_md_lit_integracao ' . $objInfraMetaBD->tipoNumero() . ' NULL
                ) ');


        $objInfraMetaBD->adicionarChavePrimaria('md_lit_historic_lancamento', 'pk1_md_lit_historic_lancamento', array('id_md_lit_historic_lancamento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_md_lit_situacao_lancamento'), 'md_lit_situacao_lancamento', array('id_md_lit_situacao_lancamento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_usuario'), 'usuario', array('id_usuario'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_unidade'), 'unidade', array('id_unidade'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_procedimento'), 'procedimento', array('id_procedimento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_md_lit_lancamento'), 'md_lit_lancamento', array('id_md_lit_lancamento'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_md_lit_integracao'), 'md_lit_integracao', array('id_md_lit_integracao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk7_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));


        $this->logar('CRIANDO A SEQUENCE seq_md_lit_historic_lancamento');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_historic_lancamento', 1);


        $this->logar('CRIANDO A TABELA md_lit_cancela_lancamento');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_cancela_lancamento (
                id_md_lit_cancela_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                motivo_cancelamento ' . $objInfraMetaBD->tipoTextoVariavel(150) . ' NOT NULL,
                justificativa ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_cancela_lancamento', 'pk1_md_lit_cancela_lancamento', array('id_md_lit_cancela_lancamento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_cancela_lancamento', 'md_lit_cancela_lancamento', array('id_md_lit_lancamento'), 'md_lit_lancamento', array('id_md_lit_lancamento'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_cancela_lancamento');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_cancela_lancamento', 1);

        $this->logar('CRIANDO A TABELA md_lit_rel_decis_lancament');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_decis_lancament (
                id_md_lit_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_decis_lancament', 'pk1_md_lit_rel_decis_lancament', array('id_md_lit_decisao', 'id_md_lit_lancamento'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_decis_lancament', 'md_lit_rel_decis_lancament', array('id_md_lit_decisao'), 'md_lit_decisao', array('id_md_lit_decisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_decis_lancament', 'md_lit_rel_decis_lancament', array('id_md_lit_lancamento'), 'md_lit_lancamento', array('id_md_lit_lancamento'));


        $this->logar('INICIANDO CADASTRO DE TIPO DE CONTROLE TESTE COM A PARAMETRIZAÇÃO DE SITUAÇÕES');
        $objMdLitTipoControleRN = new MdLitTipoControleRN();
        $objMdLitTipoControleRN->cadastrarExemplo();


        $this->logar('Cadastrando "cronjob" para Atualizar os lançamentos com o sistema de Arrecadação');

        $infraAgendamentoDTO = new InfraAgendamentoTarefaDTO();
        $infraAgendamentoDTO->retTodos();
        $infraAgendamentoDTO->setStrDescricao('Script para Atualizar os lançamentos com o sistema de Arrecadao');
        $infraAgendamentoDTO->setStrComando('MdLitAgendamentoAutomaticoRN::consultarLancamento');
        $infraAgendamentoDTO->setStrSinAtivo('S');
        $infraAgendamentoDTO->setStrStaPeriodicidadeExecucao(InfraAgendamentoTarefaRN::$PERIODICIDADE_EXECUCAO_HORA);
        $infraAgendamentoDTO->setStrPeriodicidadeComplemento(4);
        $infraAgendamentoDTO->setStrParametro(null);
        $infraAgendamentoDTO->setDthUltimaExecucao(null);
        $infraAgendamentoDTO->setDthUltimaConclusao(null);
        $infraAgendamentoDTO->setStrSinSucesso('S');
        $infraAgendamentoDTO->setStrEmailErro(null);

        $infraAgendamentoRN = new InfraAgendamentoTarefaRN();
        $infraAgendamentoDTO = $infraAgendamentoRN->cadastrar($infraAgendamentoDTO);


        $this->logar('CRIANDO A TABELA md_lit_rel_disp_norm_revogado');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_disp_norm_revogado (id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
                                                                                 id_md_lit_disp_normat_revogado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL )');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_disp_norm_revogado', 'pk_md_lit_rel_disp_norm_revoga', array('id_md_lit_disp_normat', 'id_md_lit_disp_normat_revogado'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_dispositivo_norm_revogad_01', 'md_lit_rel_disp_norm_revogado', array('id_md_lit_disp_normat'), 'md_lit_disp_normat', array('id_md_lit_disp_normat'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_dispositivo_norm_revogad_02', 'md_lit_rel_disp_norm_revogado', array('id_md_lit_disp_normat_revogado'), 'md_lit_disp_normat', array('id_md_lit_disp_normat'));


        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'0.0.4\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 0.0.4 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv100()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');


        $this->logar('CRIANDO A TABELA md_lit_reinciden_anteceden');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_reinciden_anteceden(
                id_md_lit_reinciden_anteceden ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                prazo ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                orientacao ' . $objInfraMetaBD->tipoTextoGrande() . ' NULL,
                tipo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_reinciden_anteceden', 'pk_md_lit_reinciden_anteceden', array('id_md_lit_reinciden_anteceden'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_reinciden_anteceden');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_reinciden_anteceden', 1);

        $this->logar('CRIANDO A TABELA md_lit_rel_tp_dec_rein_ante');


        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_dec_rein_ante(
                id_md_lit_tipo_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_reinciden_anteceden ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_tp_dec_rein_ant', 'md_lit_rel_tp_dec_rein_ante', array('id_md_lit_reinciden_anteceden'), 'md_lit_reinciden_anteceden', array('id_md_lit_reinciden_anteceden'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_tp_dec_rein_ant', 'md_lit_rel_tp_dec_rein_ante', array('id_md_lit_tipo_decisao'), 'md_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));


        $this->logar('CRIANDO A TABELA md_lit_motivo');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_motivo(
              id_md_lit_motivo ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
              descricao ' . $objInfraMetaBD->tipoTextoVariavel(150) . ' NOT NULL,
              sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_motivo', 'pk_md_lit_motivo', array('id_md_lit_motivo'));

        $this->logar('CRIANDO A TABELA seq_md_lit_motivo');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_motivo', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_tp_control_moti');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_control_moti(
              id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
              id_md_lit_motivo ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_control_moti', 'pk_md_lit_rel_tp_control_moti', array('id_md_lit_tipo_controle', 'id_md_lit_motivo'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_tp_control_moti', 'md_lit_rel_tp_control_moti', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_tp_control_moti', 'md_lit_rel_tp_control_moti', array('id_md_lit_motivo'), 'md_lit_motivo', array('id_md_lit_motivo'));


        $this->logar('CRIANDO A TABELA md_lit_rel_controle_motivo');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_controle_motivo(
              id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
              id_md_lit_motivo ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_controle_motivo', 'pk_md_lit_rel_controle_motivo', array('id_md_lit_controle', 'id_md_lit_motivo'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_controle_motivo', 'md_lit_rel_controle_motivo', array('id_md_lit_controle'), 'md_lit_controle', array('id_md_lit_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_controle_motivo', 'md_lit_rel_controle_motivo', array('id_md_lit_motivo'), 'md_lit_motivo', array('id_md_lit_motivo'));


        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.0.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.0.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv110()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.1.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $this->logar('ALTERANDO A TABELA md_lit_rel_dis_nor_con_ctr');
        $objInfraMetaBD->alterarColuna('md_lit_rel_dis_nor_con_ctr', 'dta_infracao', $objInfraMetaBD->tipoDataHora(), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_lit_rel_dis_nor_con_ctr', 'dta_infracao_periodo_inicial', $objInfraMetaBD->tipoDataHora(), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_lit_rel_dis_nor_con_ctr', 'dta_infracao_periodo_final', $objInfraMetaBD->tipoDataHora(), 'NULL');
        $objInfraMetaBD->adicionarColuna('md_lit_rel_dis_nor_con_ctr', 'sta_infracao_data', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');


        $this->logar('ATUALIZANDO REGISTRO DA TABELA md_lit_rel_dis_nor_con_ctr');
        $objMdLitRelDispositivoNormativoCondutaControleDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
        $objMdLitRelDispositivoNormativoCondutaControleDTO->retTodos(false);

        $objMdLitRelDispositivoNormativoCondutaControleRN = new MdLitRelDispositivoNormativoCondutaControleRN();
        $arrObjMdLitRelDispositivoNormativoCondutaControleDTO = $objMdLitRelDispositivoNormativoCondutaControleRN->listar($objMdLitRelDispositivoNormativoCondutaControleDTO);

        if (count($arrObjMdLitRelDispositivoNormativoCondutaControleDTO)) {
            foreach ($arrObjMdLitRelDispositivoNormativoCondutaControleDTO as $objMdLitRelDispositivoNormativoCondutaControleDTO) {
                $objMdLitRelDispositivoNormativoCondutaControleDTO->setStrStaInfracaoData(MdLitRelDispositivoNormativoCondutaControleRN::$TA_ESPECIFICA);
                $objMdLitRelDispositivoNormativoCondutaControleRN->alterar($objMdLitRelDispositivoNormativoCondutaControleDTO);
            }
        }

        $this->logar('ALTERANDO A TABELA md_lit_decisao');
        $objInfraMetaBD->adicionarColuna('md_lit_decisao', 'sta_localidade', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');


        $this->logar('CRIANDO A TABELA md_lit_rel_decisao_uf');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_decisao_uf(
              id_md_lit_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
              id_uf ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_decisao_uf', 'pk_md_lit_rel_decisao_uf', array('id_md_lit_decisao', 'id_uf'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_decisao_uf', 'md_lit_rel_decisao_uf', array('id_md_lit_decisao'), 'md_lit_decisao', array('id_md_lit_decisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_decisao_uf', 'md_lit_rel_decisao_uf', array('id_uf'), 'uf', array('id_uf'));


        $this->logar('ALTERANDO A TABELA md_lit_rel_tipo_ctrl_tipo_dec');
        $objInfraMetaBD->adicionarColuna('md_lit_rel_tipo_ctrl_tipo_dec', 'id_md_lit_especie_decisao', $objInfraMetaBD->tipoNumero(), 'NULL');


        $objInfraMetaBD->excluirChaveEstrangeira('md_lit_rel_tipo_ctrl_tipo_dec', 'fk_rel_tipo_ctrl_tipo_dec_01');
        $objInfraMetaBD->excluirChaveEstrangeira('md_lit_rel_tipo_ctrl_tipo_dec', 'fk_rel_tipo_ctrl_tipo_dec_02');
        $objInfraMetaBD->excluirChavePrimaria('md_lit_rel_tipo_ctrl_tipo_dec', 'pk_lit_rel_tipo_ctrl_tipo_dec1');

        $this->logar('MIGRANDO OS DADOS DA TABELA md_lit_rel_tipo_ctrl_tipo_dec');
        $objMdLitRelTipoControleTipoDecisaoDTO = new MdLitRelTipoControleTipoDecisaoDTO();
        $objMdLitRelTipoControleTipoDecisaoDTO->retNumIdTipoControleLitigioso();
        $objMdLitRelTipoControleTipoDecisaoDTO->retNumIdTipoDecisaoLitigioso();

        $objMdLitRelTipoControleTipoDecisaoRN = new MdLitRelTipoControleTipoDecisaoRN();
        $arrObjMdLitRelTipoControleTipoDecisaoDTO = $objMdLitRelTipoControleTipoDecisaoRN->listar($objMdLitRelTipoControleTipoDecisaoDTO);

        if (count($arrObjMdLitRelTipoControleTipoDecisaoDTO)) {


            $this->logar('APAGANDO OS DADOS DA TABELA md_lit_rel_tipo_ctrl_tipo_dec antigos');
            foreach ($arrObjMdLitRelTipoControleTipoDecisaoDTO as $objMdLitRelTipoControleTipoDecisaoDTO) {
                // necessita ser em um for separado por que dentro do cadastrar ele excluir todos os registros do md_lit_rel_tipo_ctrl_tipo_dec pelo id_md_lit_tipo_controle assim gera um erro
                BancoSEI::getInstance()->executarSql('delete from md_lit_rel_tipo_ctrl_tipo_dec where  id_md_lit_tipo_controle=' . $objMdLitRelTipoControleTipoDecisaoDTO->getNumIdTipoControleLitigioso() . ' and id_md_lit_tipo_decisao=' . $objMdLitRelTipoControleTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso());
            }


            $this->logar('INSERINDO OS DADOS DA TABELA md_lit_rel_tipo_ctrl_tipo_dec vinculado com id_md_lit_especie_decisao');
            $arrObjMdLitRelTipoControleTipoDecisaoDTOCadastro = array();
            foreach ($arrObjMdLitRelTipoControleTipoDecisaoDTO as $objMdLitRelTipoControleTipoDecisaoDTO) {
                $idTipoDecisaoLitigioso = $objMdLitRelTipoControleTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso();
                $objMdLitRelTipoEspecieDecisaoDTO = new MdLitRelTipoEspecieDecisaoDTO();
                $objMdLitRelTipoEspecieDecisaoDTO->retTodos(false);
                $objMdLitRelTipoEspecieDecisaoDTO->setNumIdTipoDecisaoLitigioso($idTipoDecisaoLitigioso);


                $objMdLitRelTipoEspecieDecisaoRN = new MdLitRelTipoEspecieDecisaoRN();
                $arrObjMdLitRelTipoEspecieDecisaoDTO = $objMdLitRelTipoEspecieDecisaoRN->listar($objMdLitRelTipoEspecieDecisaoDTO);
                foreach ($arrObjMdLitRelTipoEspecieDecisaoDTO as $objMdLitRelTipoEspecieDecisaoDTO) {
                    $objMdLitRelTipoControleTipoDecisaoDTONovo = new MdLitRelTipoControleTipoDecisaoDTO();
                    $objMdLitRelTipoControleTipoDecisaoDTONovo->setNumIdMdLitEspecieDecisao($objMdLitRelTipoEspecieDecisaoDTO->getNumIdEspecieDecisaoLitigioso());
                    $objMdLitRelTipoControleTipoDecisaoDTONovo->setNumIdTipoControleLitigioso($objMdLitRelTipoControleTipoDecisaoDTO->getNumIdTipoControleLitigioso());
                    $objMdLitRelTipoControleTipoDecisaoDTONovo->setNumIdTipoDecisaoLitigioso($objMdLitRelTipoControleTipoDecisaoDTO->getNumIdTipoDecisaoLitigioso());
                    $arrObjMdLitRelTipoControleTipoDecisaoDTOCadastro[] = $objMdLitRelTipoControleTipoDecisaoDTONovo;
                }
            }
            $objMdLitRelTipoControleTipoDecisaoRN->cadastrar($arrObjMdLitRelTipoControleTipoDecisaoDTOCadastro);
        }

        $objInfraMetaBD->alterarColuna('md_lit_rel_tipo_ctrl_tipo_dec', 'id_md_lit_especie_decisao', $objInfraMetaBD->tipoNumero(), 'NOT NULL');
        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tipo_ctrl_tipo_dec', 'pk_md_lit_rel_tipo_ctrl_tipo', array('id_md_lit_tipo_decisao', 'id_md_lit_tipo_controle', 'id_md_lit_especie_decisao'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_ctrl_tipo_dec_01', 'md_lit_rel_tipo_ctrl_tipo_dec', array('id_md_lit_tipo_decisao'), 'md_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_ctrl_tipo_dec_02', 'md_lit_rel_tipo_ctrl_tipo_dec', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_ctrl_tipo_dec_03', 'md_lit_rel_tipo_ctrl_tipo_dec', array('id_md_lit_especie_decisao'), 'md_lit_especie_decisao', array('id_md_lit_especie_decisao'));


        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.1.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.1.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv120()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.2.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $this->logar('ALTERANDO A TABELA md_lit_decisao');
        $objInfraMetaBD->adicionarColuna('md_lit_decisao', 'sin_cadastro_parcial', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');


        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.2.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.2.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv130()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.3.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');


        $this->logar('ALTERANDO A TABELA md_lit_reinciden_anteceden');
        $objInfraMetaBD->adicionarColuna('md_lit_reinciden_anteceden', 'tp_regra_reincidencia', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');


        $this->logar('EXECUTANDO A criação/edição da administração de Antecedentes');
        $objMdLitReincidenAntecedenCadastroDTO = new MdLitReincidenAntecedenDTO();
        $objMdLitReincidenAntecedenCadastroDTO->setNumPrazo(5);
        $objMdLitReincidenAntecedenCadastroDTO->setStrTipo(MdLitReincidenAntecedenRN::$TIPO_ANTECEDENTE);
        $objMdLitReincidenAntecedenCadastroDTO->setStrOrientacao("<p>Abaixo são listados os processos Transitados em Julgados dentro do período de @prazo_anos_antecedente@, contados da data mais antiga das infrações dispostas no processo de trabalho (pesquisa a partir de processo específico) ou da Data de Corte (se estiver utilizando o menu de Relatórios) e que conste decisão dos Tipos de Decisão @tipos_decisao_antecedente@.</p>");

        $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
        $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();
        $objMdLitReincidenAntecedenDTO->setStrTipo(MdLitReincidenAntecedenRN::$TIPO_ANTECEDENTE);

        $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
        $objMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->consultar($objMdLitReincidenAntecedenDTO);
        if ($objMdLitReincidenAntecedenDTO) {
            $objMdLitReincidenAntecedenCadastroDTO->setNumIdMdLitReincidenAnteceden($objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden());
            $objMdLitReincidenAntecedenRN->alterar($objMdLitReincidenAntecedenCadastroDTO);
        } else {
            $objMdLitReincidenAntecedenRN->cadastrar($objMdLitReincidenAntecedenCadastroDTO);
        }

        $this->logar('EXECUTANDO A criação/edição da administração de Reincidente');
        $objMdLitReincidenAntecedenCadastroDTO = new MdLitReincidenAntecedenDTO();
        $objMdLitReincidenAntecedenCadastroDTO->setNumPrazo(5);
        $objMdLitReincidenAntecedenCadastroDTO->setStrTipo(MdLitReincidenAntecedenRN::$TIPO_REINCIDENCIA);
        $objMdLitReincidenAntecedenCadastroDTO->setStrTipoRegraReincidencia(MdLitReincidenAntecedenRN::$CONDUTA);
        $objMdLitReincidenAntecedenCadastroDTO->setStrOrientacao("<p>Abaixo são listados os processos Transitados em Julgados dentro do período de @prazo_anos_reincidencia_especifica@, contados da data mais antiga das infrações dispostas no processo de trabalho (pesquisa a partir de processo específico) ou da Data de Corte (se estiver utilizando o menu de Relatórios), que conste decisão dos Tipos de Decisão @tipos_decisao_reincidencia_especifica@ e infração envolvendo @definicao_infracao_mesma_natureza_reincidencia_especifica@.</p>");

        $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
        $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();
        $objMdLitReincidenAntecedenDTO->setStrTipo(MdLitReincidenAntecedenRN::$TIPO_REINCIDENCIA);

        $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
        $objMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->consultar($objMdLitReincidenAntecedenDTO);
        if ($objMdLitReincidenAntecedenDTO) {
            $objMdLitReincidenAntecedenCadastroDTO->setNumIdMdLitReincidenAnteceden($objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden());
            $objMdLitReincidenAntecedenRN->alterar($objMdLitReincidenAntecedenCadastroDTO);
        } else {
            $objMdLitReincidenAntecedenRN->cadastrar($objMdLitReincidenAntecedenCadastroDTO);
        }


        $this->logar('ALTERANDO A TABELA md_lit_decisao');
        $objInfraMetaBD->adicionarColuna('md_lit_decisao', 'sin_ultima_decisao', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');

        $this->logar('ALTERANDO O registro da tabela md_lit_decisao sinalizando com a ultima decisao');
        $objMdLitDecisaoRN = new MdLitDecisaoRN();
        $arrObjMdLitDecisaoDTOUltima = $objMdLitDecisaoRN->migrarUltimaSituacaoDecisorio();
        BancoSEI::getInstance()->executarSql('UPDATE md_lit_decisao SET sin_ultima_decisao = \'N\' WHERE sin_ultima_decisao IS NULL');

        $this->logar('ALTERANDO O registro da tabela md_lit_rel_decis_lancament vinculando a decisão com o Lancamento');
        $objMdLitDecisaoRN = new MdLitRelDecisLancamentRN();
        $objMdLitDecisaoRN->vincularDecisaoComLancamento($arrObjMdLitDecisaoDTOUltima);

        $this->logar('ALTERANDO A TABELA md_lit_historic_lancamento');
        $objInfraMetaBD->alterarColuna('md_lit_historic_lancamento', 'justificativa', $objInfraMetaBD->tipoTextoVariavel(250), 'NULL');

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.3.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        if ($objInfraMetaBD->getObjInfraIBanco() instanceof InfraSqlServer){
            $this->logar('ALTERANDO o nome da PK da tabela md_lit_rel_disp_norm_conduta');
            $arrPk = $objInfraMetaBD->obterConstraints('md_lit_rel_disp_norm_conduta');
            foreach ($arrPk as $pk){
                if ($pk['constraint_name'] == 'pk_md_lit_rel_disp_norm_conduta') {
                    $objInfraMetaBD->excluirChavePrimaria('md_lit_rel_disp_norm_conduta', 'pk_md_lit_rel_disp_norm_conduta');
                    $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_disp_norm_conduta', 'pk_md_lit_rel_disp_norm_condut', array('id_md_lit_disp_normat', 'id_md_lit_conduta'));
                }
            }

            $this->logar('ALTERANDO o nome da PK da tabela md_lit_rel_disp_norm_tipo_ctrl');
            $arrPk = $objInfraMetaBD->obterConstraints('md_lit_rel_disp_norm_tipo_ctrl');
            foreach ($arrPk as $pk) {
                if ($pk['constraint_name'] == 'pk_md_lit_rel_disp_norm_tipo_ctrl') {
                    $objInfraMetaBD->excluirChavePrimaria('md_lit_rel_disp_norm_tipo_ctrl', 'pk_md_lit_rel_disp_norm_tipo_ctrl');
                    $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_disp_norm_tipo_ctrl', 'pk_md_lit_rel_disp_norm_tp_ctr', array('id_md_lit_disp_normat', 'id_md_lit_tipo_controle'));
                }
            }
        }

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.3.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv140()
    {

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.4.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');


        $this->logar('ALTERANDO A TABELA md_lit_integracao');
        $objInfraMetaBD->adicionarColuna('md_lit_integracao', 'sin_vincular_lancamento', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');

        $this->logar('Adicionando novos parametros na combo de saida da funcionalidade Arrecadação Consulta Lançamento ');

        $arrDados = array(
            #Arrecadação Lançamento de Crédito
            //Saida
            array('idMdLitCampoIntegracao' => 79, 'idMdLitFuncionalidade' => MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO, 'nomeCampo' => 'Valor da Receita Inicial', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 80, 'idMdLitFuncionalidade' => MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO, 'nomeCampo' => 'Data da Decisão de Aplicação da Multa', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 81, 'idMdLitFuncionalidade' => MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO, 'nomeCampo' => 'Data de Vencimento', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 82, 'idMdLitFuncionalidade' => MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO, 'nomeCampo' => 'Número de Complemento do Interessado', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 83, 'idMdLitFuncionalidade' => MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO, 'nomeCampo' => 'Identificação do Lançamento', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 84, 'idMdLitFuncionalidade' => MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO, 'nomeCampo' => 'Código da Receita', 'staParametro' => 'S'),
            array('idMdLitCampoIntegracao' => 85, 'idMdLitFuncionalidade' => MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO, 'nomeCampo' => 'Data Constituição Definitiva', 'staParametro' => 'S'),
        );

        $objMdLitCampoIntegracaoRN = new MdLitCampoIntegracaoRN();
        foreach ($arrDados as $dados) {
            $objMdLitCampoIntegracaoDTO = new MdLitCampoIntegracaoDTO();
            $objMdLitCampoIntegracaoDTO->setNumIdMdLitCampoIntegracao($dados['idMdLitCampoIntegracao']);
            $objMdLitCampoIntegracaoDTO->setNumIdMdLitFuncionalidade($dados['idMdLitFuncionalidade']);
            $objMdLitCampoIntegracaoDTO->setStrNomeCampo($dados['nomeCampo']);
            $objMdLitCampoIntegracaoDTO->setStrStaParametro($dados['staParametro']);
            $objMdLitCampoIntegracaoRN->cadastrar($objMdLitCampoIntegracaoDTO);
        }

        $this->logar('Removendo parametros na combo de saida da funcionalidade Arrecadação Consulta Lançamento ');
        $objMdLitCampoIntegracaoDTO = new MdLitCampoIntegracaoDTO();
        $objMdLitCampoIntegracaoDTO->retNumIdMdLitCampoIntegracao();
        $objMdLitCampoIntegracaoDTO->setNumIdMdLitFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);
        $objMdLitCampoIntegracaoDTO->setStrNomeCampo(array("Renúncia a Recurso", "Vencido", "Desbloqueio Processo Vencido"), InfraDTO::$OPER_IN);
        $arrObjMdLitCampoIntegracaoDTO = $objMdLitCampoIntegracaoRN->listar($objMdLitCampoIntegracaoDTO);

        if (count($arrObjMdLitCampoIntegracaoDTO)) {
            $arrIdMdLitCampoIntegracao = InfraArray::converterArrInfraDTO($arrObjMdLitCampoIntegracaoDTO, 'IdMdLitCampoIntegracao');
            $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();
            $objMdLitMapearParamEntradaDTO->retNumIdMdLitMapearParamEntrada();
            $objMdLitMapearParamEntradaDTO->setNumIdMdLitCampoIntegracao($arrIdMdLitCampoIntegracao, InfraDTO::$OPER_IN);

            $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
            $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);

            if (count($arrObjMdLitMapearParamEntradaDTO)) {
                $objMdLitMapearParamEntradaRN->excluir($arrObjMdLitMapearParamEntradaDTO);
            }

            $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();
            $objMdLitMapearParamSaidaDTO->retNumIdMdLitMapearParamSaida();
            $objMdLitMapearParamSaidaDTO->setNumIdMdLitCampoIntegracao($arrIdMdLitCampoIntegracao, InfraDTO::$OPER_IN);

            $objMdLitMapearParamSaidaRN = new MdLitMapearParamSaidaRN();
            $arrObjMdLitMapearParamSaidaDTO = $objMdLitMapearParamSaidaRN->listar($objMdLitMapearParamSaidaDTO);

            if (count($arrObjMdLitMapearParamSaidaDTO)) {
                $objMdLitMapearParamSaidaRN->excluir($arrObjMdLitMapearParamSaidaDTO);
            }

            $objMdLitCampoIntegracaoRN->excluir($arrObjMdLitCampoIntegracaoDTO);
        }


        $this->logar('Alterando o Nome do registro da combo de mapeamento de integração');
        $objMdLitCampoIntegracaoDTO = new MdLitCampoIntegracaoDTO();
        $objMdLitCampoIntegracaoDTO->retNumIdMdLitCampoIntegracao();
        $objMdLitCampoIntegracaoDTO->setNumIdMdLitFuncionalidade(MdLitIntegracaoRN::$ARRECADACAO_CONSULTAR_LANCAMENTO);
        $objMdLitCampoIntegracaoDTO->setStrNomeCampo('Valor do Último Pagamento');
        $objMdLitCampoIntegracaoDTO->setNumMaxRegistrosRetorno(1);

        $objMdLitCampoIntegracaoDTO = $objMdLitCampoIntegracaoRN->consultar($objMdLitCampoIntegracaoDTO);

        if ($objMdLitCampoIntegracaoDTO) {
            $objMdLitCampoIntegracaoDTO->setStrNomeCampo('Valor Total do Pagamento');
            $objMdLitCampoIntegracaoRN->alterar($objMdLitCampoIntegracaoDTO);
        }


        $this->logar('Apagando as tabelas de vinculo de modalidade e abrangencia');
        BancoSEI::getInstance()->executarSql('DROP TABLE md_lit_rel_num_inter_uf');
        BancoSEI::getInstance()->executarSql('DROP TABLE md_lit_rel_num_inter_cidade');
        BancoSEI::getInstance()->executarSql('DROP TABLE md_lit_rel_servico_modalidade');
        BancoSEI::getInstance()->executarSql('DROP TABLE md_lit_rel_servico_abrangen');
        BancoSEI::getInstance()->executarSql('DROP TABLE md_lit_rel_num_inter_abrang');
        BancoSEI::getInstance()->executarSql('DROP TABLE md_lit_rel_num_inter_modali');
        BancoSEI::getInstance()->executarSql('DROP TABLE md_lit_abrangencia');
        BancoSEI::getInstance()->executarSql('DROP TABLE md_lit_modalidade');


        if (BancoSEI::getInstance() instanceof InfraOracle) {
            BancoSEI::getInstance()->executarSql('DROP SEQUENCE seq_md_lit_modalidade');
            BancoSEI::getInstance()->executarSql('DROP SEQUENCE seq_md_lit_abrangencia');
        } else {
            BancoSEI::getInstance()->executarSql('DROP TABLE seq_md_lit_modalidade');
            BancoSEI::getInstance()->executarSql('DROP TABLE seq_md_lit_abrangencia');
        }


        //as tabelas de modalidade e abrangencia mudaram a concepção assim excluindo a abrangencia e criando a tipo de outorga
        $this->logar('CRIANDO A TABELA md_lit_adm_modalidad_outor');

        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_adm_modalidad_outor (
                id_md_lit_adm_modalidad_outor ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_adm_modalidad_outor', 'pk_md_lit_adm_modalidad_outor', array('id_md_lit_adm_modalidad_outor'));

        $this->logar('CRIANDO A SEQUENCE md_lit_adm_modalidad_outor');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_adm_modalidad_outor', 1);


        $this->logar('CRIANDO A TABELA md_lit_adm_tipo_outor');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_adm_tipo_outor (
                id_md_lit_adm_tipo_outor ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

        $objInfraMetaBD->adicionarChavePrimaria('md_lit_adm_tipo_outor', 'pk_md_lit_adm_tipo_outor', array('id_md_lit_adm_tipo_outor'));

        $this->logar('CRIANDO A SEQUENCE seq_md_lit_adm_tipo_outor');
        BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_adm_tipo_outor', 1);


        $this->logar('CRIANDO A TABELA md_lit_rel_num_inter_modali');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_num_inter_modali (
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_adm_modalidad_outor ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');


        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_num_inter_modali', 'pk1_md_lit_rel_num_inter_moda', array('id_md_lit_numero_interessado', 'id_md_lit_adm_modalidad_outor'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_num_inter_moda', 'md_lit_rel_num_inter_modali', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_num_inter_moda', 'md_lit_rel_num_inter_modali', array('id_md_lit_adm_modalidad_outor'), 'md_lit_adm_modalidad_outor', array('id_md_lit_adm_modalidad_outor'));


        $this->logar('CRIANDO A TABELA md_lit_rel_num_inter_tp_outor');
        BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_num_inter_tp_outor (
                id_md_lit_numero_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                id_md_lit_adm_tipo_outor ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                ) ');


        $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_num_inter_tp_outor', 'pk1_md_lit_rel_num_inter_tp_ou', array('id_md_lit_numero_interessado', 'id_md_lit_adm_tipo_outor'));

        $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_num_inter_tp_ou', 'md_lit_rel_num_inter_tp_outor', array('id_md_lit_numero_interessado'), 'md_lit_numero_interessado', array('id_md_lit_numero_interessado'));
        $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_num_inter_tp_ou', 'md_lit_rel_num_inter_tp_outor', array('id_md_lit_adm_tipo_outor'), 'md_lit_adm_tipo_outor', array('id_md_lit_adm_tipo_outor'));


        $this->logar('Removendo estado e cidade dos parametros já mapeado nos Dados Complementares');
        $objMdLitNomeFuncionalRN = new MdLitNomeFuncionalRN();
        $objMdLitNomeFuncionalDTO = new MdLitNomeFuncionalDTO();
        $objMdLitNomeFuncionalDTO->retNumIdMdLitNomeFuncional();
        $objMdLitNomeFuncionalDTO->setNumIdMdLitNomeFuncional(array(7, 8), InfraDTO::$OPER_IN);
        $arrObjMdLitNomeFuncionalDTO = $objMdLitNomeFuncionalRN->listar($objMdLitNomeFuncionalDTO);

        if (count($arrObjMdLitNomeFuncionalDTO)) {
            $arrIdMdLitNomeFuncionalDTO = InfraArray::converterArrInfraDTO($arrObjMdLitNomeFuncionalDTO, 'IdMdLitNomeFuncional');
            $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();
            $objMdLitMapearParamEntradaDTO->retNumIdMdLitMapearParamEntrada();
            $objMdLitMapearParamEntradaDTO->setNumIdFuncionalidadeMdLitIntegracao(MdLitIntegracaoRN::$DADOS_COMPL_INTERESSADO_CONSULTA);
            $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional($arrIdMdLitNomeFuncionalDTO, InfraDTO::$OPER_IN);

            $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
            $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);

            if (count($arrObjMdLitMapearParamEntradaDTO)) {
                $objMdLitMapearParamEntradaRN->excluir($arrObjMdLitMapearParamEntradaDTO);
            }

            $objMdLitMapearParamSaidaDTO = new MdLitMapearParamSaidaDTO();
            $objMdLitMapearParamSaidaDTO->retNumIdMdLitMapearParamSaida();
            $objMdLitMapearParamSaidaDTO->setNumIdFuncionalidadeMdLitIntegracao(MdLitIntegracaoRN::$DADOS_COMPL_INTERESSADO_CONSULTA);
            $objMdLitMapearParamSaidaDTO->setNumIdMdLitNomeFuncional($arrIdMdLitNomeFuncionalDTO, InfraDTO::$OPER_IN);

            $objMdLitMapearParamSaidaRN = new MdLitMapearParamSaidaRN();
            $arrObjMdLitMapearParamSaidaDTO = $objMdLitMapearParamSaidaRN->listar($objMdLitMapearParamSaidaDTO);

            if (count($arrObjMdLitMapearParamSaidaDTO)) {
                $objMdLitMapearParamSaidaRN->excluir($arrObjMdLitMapearParamSaidaDTO);
            }

            $objMdLitParametrizarInteressadoDTO = new MdLitParametrizarInteressadoDTO();
            $objMdLitParametrizarInteressadoDTO->retNumIdMdLitParamInteressado();
            $objMdLitParametrizarInteressadoDTO->setNumIdMdLitNomeFuncional($arrIdMdLitNomeFuncionalDTO, InfraDTO::$OPER_IN);

            $objMdLitParametrizarInteressadoRN = new MdLitParametrizarInteressadoRN();
            $arrObjMdLitParametrizarInteressadoDTO = $objMdLitParametrizarInteressadoRN->listar($objMdLitParametrizarInteressadoDTO);

            if (count($arrObjMdLitParametrizarInteressadoDTO)) {
                $objMdLitParametrizarInteressadoRN->excluir($arrObjMdLitParametrizarInteressadoDTO);
            }

            $objMdLitNomeFuncionalRN->excluir($arrObjMdLitNomeFuncionalDTO);
        }

        $this->logar('Renomeando os parametros dos Dados Complementares');
        $objMdLitNomeFuncionalDTO = new MdLitNomeFuncionalDTO();
        $objMdLitNomeFuncionalDTO->retNumIdMdLitNomeFuncional();
        $objMdLitNomeFuncionalDTO->setNumIdMdLitNomeFuncional(array(MdLitNomeFuncionalRN::$NUMERO, MdLitNomeFuncionalRN::$SERVICO, MdLitNomeFuncionalRN::$TIPO_OUTORGA, MdLitNomeFuncionalRN::$MODALIDADE), InfraDTO::$OPER_IN);
        $arrObjMdLitNomeFuncionalDTO = $objMdLitNomeFuncionalRN->listar($objMdLitNomeFuncionalDTO);

        if (count($arrObjMdLitNomeFuncionalDTO)) {
            foreach ($arrObjMdLitNomeFuncionalDTO as $objMdLitNomeFuncionalDTO) {
                switch ($objMdLitNomeFuncionalDTO->getNumIdMdLitNomeFuncional()) {
                    case MdLitNomeFuncionalRN::$NUMERO:
                        $objMdLitNomeFuncionalDTO->setStrNome('Número de Complemento do Interessado');
                        break;
                    case MdLitNomeFuncionalRN::$SERVICO:
                        $objMdLitNomeFuncionalDTO->setStrNome('Serviço Outorgado');
                        break;
                    case MdLitNomeFuncionalRN::$TIPO_OUTORGA:
                        //o tipo de outorga nas versões anteriores do Litigioso era entendido como abrangência
                        $objMdLitNomeFuncionalDTO->setStrNome('Tipo de Outorga');
                        break;
                    case MdLitNomeFuncionalRN::$MODALIDADE:
                        $objMdLitNomeFuncionalDTO->setStrNome('Modalidade de Outorga');
                        break;
                }
                $objMdLitNomeFuncionalRN->alterar($objMdLitNomeFuncionalDTO);

            }
        }

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.4.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.4.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv150()
    {
        $instanciaBanco = BancoSEI::getInstance();
        $objInfraMetaBD = new InfraMetaBD($instanciaBanco);
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.5.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $this->logar('Adicionanda a coluna de indicação de tipo de multa na na Espécie de decisão');
        $objInfraMetaBD->adicionarColuna('md_lit_especie_decisao', 'sta_tipo_indicacao_multa', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
        $instanciaBanco->executarSql("UPDATE md_lit_especie_decisao set sta_tipo_indicacao_multa = 1 WHERE sin_rd_gestao_multa = 'S' ");

        $this->logar('Adicionnda a coluna de indicação de ressarcimento de valor na Espécie de decisão');
        $objInfraMetaBD->adicionarColuna('md_lit_especie_decisao', 'sin_ressarcimento_valor', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
        $instanciaBanco->executarSql("UPDATE md_lit_especie_decisao set sin_ressarcimento_valor = 'N' ");

        $this->logar('Adicionada a coluna para inclusão de valor de ressarcimento na decisão');
        $objInfraMetaBD->adicionarColuna('md_lit_decisao', 'valor_ressarcimento', $objInfraMetaBD->tipoNumeroDecimal(19, 2), 'NULL');

        $this->logar('Adicionanda a coluna para inclusão de valor de ressarcimento na decisão');
        //adcição da coluna de valor de multa sem integracao(por idicação de valor) na tabela de decisão
        $objInfraMetaBD->adicionarColuna('md_lit_decisao', 'valor_multa_sem_integracao', $objInfraMetaBD->tipoNumeroDecimal(19, 2), 'NULL');

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        $instanciaBanco->executarSql('UPDATE infra_parametro SET valor = \'1.5.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.5.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    }


    protected function instalarv160()
    {
        $instanciaBanco = BancoSEI::getInstance();
        $objInfraMetaBD = new InfraMetaBD($instanciaBanco);

        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.6.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $this->logar('ADIÇÃO DA COLUNA DE OBRIGATORIEDADE NA PARAMETRIZAÇÃO DA "SITUAÇÃO".');
        $objInfraMetaBD->adicionarColuna('md_lit_situacao', 'sin_obrigatoria', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
        //$instanciaBanco->executarSql("UPDATE md_lit_situacao set sin_obrigatoria = 'N'; ");

        $this->logar('ADIÇÃO DA COLUNA DE "ALEGAÇÕES" NA PARAMETRIZAÇÃO DA "SITUAÇÃO".');
        $objInfraMetaBD->adicionarColuna('md_lit_situacao', 'sin_alegacoes', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
        //$instanciaBanco->executarSql("UPDATE md_lit_situacao set sin_alegacoes =  'N'; ");

        $objMdLitSituacaoRN = new MdLitSituacaoRN();
        $objMdLitSituacaoRN->atualizarSinsSituacao();

        $this->logar('REALIZANDO CARGA DA INFORMAÇÃO DA "DATA DA INTIMAÇÃO DA DECISÃO DE APLICAÇÃO DA MULTA".');

        $MdLitLancamentoDTO = new MdLitLancamentoDTO();
        $MdLitLancamentoDTO->ret('IdMdLitLancamento');
        $MdLitLancamentoDTO->ret('IdProcedimento');
        $MdLitLancamentoDTO->ret('Inclusao');
        $MdLitLancamentoDTO->set('Intimacao', null);
        $MdLitLancamentoDTO->setOrd('IdProcedimento', 'asc');

        $MdLitLancamentoRn = new MdLitLancamentoRN();
        //Lista todas os lancamentos com data de intimação nula
        $arrLancamento = $MdLitLancamentoRn->listar($MdLitLancamentoDTO);

        if ($arrLancamento) {
            $arrIdProcedimentosLancamentos = array_unique(InfraArray::simplificarArr(InfraArray::converterArrInfraDTO($arrLancamento)['MdLitLancamentoDTO'], 'IdProcedimento'));
            $MdLitProcessoSituacaoDto = new MdLitProcessoSituacaoDTO();
            $MdLitProcessoSituacaoDto->ret('Data');
            $MdLitProcessoSituacaoDto->ret('Inclusao');
            $MdLitProcessoSituacaoDto->ret('IdMdLitProcessoSituacao');
            $MdLitProcessoSituacaoDto->ret('IdProcedimento');
            $MdLitProcessoSituacaoDto->ret('IdMdLitFase');
            $MdLitProcessoSituacaoDto->ret('SinIntimacaoSit');
            $MdLitProcessoSituacaoDto->set('SinIntimacaoSit', 'S');
            $MdLitProcessoSituacaoDto->set('IdProcedimento', $arrIdProcedimentosLancamentos, InfraDTO::$OPER_IN);

            $MdLitProcessoSituacaoRn = new MdLitProcessoSituacaoRN();
            //Lista todas a intimações dos processos
            $arrProcessoSituacao = $MdLitProcessoSituacaoRn->listar($MdLitProcessoSituacaoDto);

            $MdLitProcessoSituacaoDtoExists = new MdLitProcessoSituacaoDTO();
            $MdLitProcessoSituacaoDtoExists->ret('IdMdLitProcessoSituacao');
            $MdLitProcessoSituacaoDtoExists->ret('IdProcedimento');
            $MdLitProcessoSituacaoDtoExists->ret('SinDecisoriaSit');
            $MdLitProcessoSituacaoDtoExists->ret('IdMdLitFase');
            $MdLitProcessoSituacaoDtoExists->ret('Data');
            $MdLitProcessoSituacaoDtoExists->set('SinDecisoriaSit', 'S');
            $MdLitProcessoSituacaoDtoExists->set('IdProcedimento', $arrIdProcedimentosLancamentos, InfraDTO::$OPER_IN);
            $MdLitProcessoSituacaoDtoExists->setOrd('IdProcedimento', InfraDTO::$TIPO_ORDENACAO_ASC);
            $MdLitProcessoSituacaoDtoExists->setOrd('IdMdLitProcessoSituacao', InfraDTO::$TIPO_ORDENACAO_ASC);
            //Lista todas a decisões dos processos
            $arrProcessoSituacaoDecisaoExists = $MdLitProcessoSituacaoRn->listar($MdLitProcessoSituacaoDtoExists);

            //se existir lancamento sem data de intimação e este lançamento não pode estar cancelado
            if (count($arrLancamento)) {
                $mdLitCancelaLancamentoDto = new MdLitCancelaLancamentoDTO();
                $mdLitCancelaLancamentoDto->ret('IdMdLitLancamento');

                $arrCancelamento = [];
                $mdLitCancelaLancamentoRn = new MdLitCancelaLancamentoRN();
                $cancelamento = $mdLitCancelaLancamentoRn->listar($mdLitCancelaLancamentoDto);
                if (count($cancelamento) > 0) {
                    $arrCancelamento = InfraArray::simplificarArr(InfraArray::converterArrInfraDTO($cancelamento)['MdLitCancelaLancamentoDTO'], 'IdMdLitLancamento');
                }
            }

            //verificar quais processos possuem intimação após a formalização da decisão para cada lancamento com data nula
            foreach ($arrLancamento as $keyLancamento => $lancamento) {
                //se o lancamento foi cancelado ignora
                if (in_array($lancamento->get('IdMdLitLancamento'), $arrCancelamento)) {
                    continue;
                }

                $idProcedimento = $lancamento->get('IdProcedimento');
                //verifica a intimação
                foreach ($arrProcessoSituacao as $keyIntimacao => $intimacao) {
                    //procedimento (processo) diferente pula
                    if ($idProcedimento != $intimacao->get('IdProcedimento')) {
                        continue;
                    }

                    //verifica se existe decisao anterior a intimacao
                    foreach ($arrProcessoSituacaoDecisaoExists as $keyDecisao => $decisao) {
                        $dataInclusaoLancamento = DateTime::createFromFormat('d/m/Y H:i:s', $lancamento->get('Inclusao'))->setTime(0, 0, 0);
                        $dataInclusaoIntimacao = DateTime::createFromFormat('d/m/Y H:i:s', $intimacao->get('Inclusao'))->setTime(0, 0, 0);
                        //se mesmo procedimento (processo) e a intimação posterior a decisao dentro da mesma fase, alimenta o array para update.
                        if (
                            $idProcedimento == $decisao->get('IdProcedimento') &&
                            $decisao->get('IdMdLitProcessoSituacao') < $intimacao->get('IdMdLitProcessoSituacao') &&
                            $decisao->get('IdMdLitFase') == $intimacao->get('IdMdLitFase') &&
                            $dataInclusaoLancamento <= $dataInclusaoIntimacao
                        ) {
                            //update da data de intimação de acordo com a intimação subsequente a decisao, conforme regra de negocio.
                            $result[] = $instanciaBanco->executarSql("UPDATE md_lit_lancamento set dta_intimacao = {$instanciaBanco->formatarGravacaoDth($intimacao->get('Data'))} where dta_intimacao is null and id_md_lit_lancamento = {$lancamento->get('IdMdLitLancamento')} and id_procedimento = {$idProcedimento}");
                            break(2);
                        }
                        continue;
                    }
                }
            }
        }

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        $instanciaBanco->executarSql('UPDATE infra_parametro SET valor = \'1.6.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.6.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv170()
    {
        $instanciaBanco = BancoSEI::getInstance();
        $objInfraMetaBD = new InfraMetaBD($instanciaBanco);
        try {
            $instanciaBanco->abrirTransacao();

            $this->logar('INCLUSÂO DE COLUNA NA TABELA md_lit_integracao PARA DEFINIÇÃO DOS TIPOS DE CLIENTE WS');
            $objInfraMetaBD->adicionarColuna('md_lit_integracao', 'tipo_cliente_ws', $objInfraMetaBD->tipoTextoFixo(4), 'NULL');
            $instanciaBanco->executarSql("UPDATE md_lit_integracao SET tipo_cliente_ws = 'SOAP' ");

            $this->logar('INCLUSÂO DE COLUNA NA TABELA md_lit_integracao PARA DEFINIÇÃO DAS VERSÕES DO SOAP');
            $objInfraMetaBD->adicionarColuna('md_lit_integracao', 'versao_soap', $objInfraMetaBD->tipoTextoFixo(5), 'NULL');
            $instanciaBanco->executarSql("UPDATE md_lit_integracao SET versao_soap = '1.2' ");

            $this->logar('INCLUSÂO DE COLUNA NA TABELA md_lit_servico_integracao PARA DEFINIÇÃO DOS TIPOS DE CLIENTE WS');
            $objInfraMetaBD->adicionarColuna('md_lit_servico_integracao', 'tipo_cliente_ws', $objInfraMetaBD->tipoTextoFixo(4), 'NULL');
            $instanciaBanco->executarSql("UPDATE md_lit_servico_integracao SET tipo_cliente_ws = 'SOAP' ");

            $this->logar('INCLUSÂO DE COLUNA NA TABELA md_lit_servico_integracao PARA DEFINIÇÃO DAS VERSÕES DO SOAP');
            $objInfraMetaBD->adicionarColuna('md_lit_servico_integracao', 'versao_soap', $objInfraMetaBD->tipoTextoFixo(5), 'NULL');
            $instanciaBanco->executarSql("UPDATE md_lit_servico_integracao SET versao_soap = '1.2' ");

            $this->logar('INCLUSÂO DE COLUNA NA TABELA md_lit_situacao_lancam_int PARA DEFINIÇÃO DOS TIPOS DE CLIENTE WS');
            $objInfraMetaBD->adicionarColuna('md_lit_situacao_lancam_int', 'tipo_cliente_ws', $objInfraMetaBD->tipoTextoFixo(4), 'NULL');
            $instanciaBanco->executarSql("UPDATE md_lit_situacao_lancam_int SET tipo_cliente_ws = 'SOAP' ");

            $this->logar('INCLUSÂO DE COLUNA NA TABELA md_lit_situacao_lancam_int PARA DEFINIÇÃO DAS VERSÕES DO SOAP');
            $objInfraMetaBD->adicionarColuna('md_lit_situacao_lancam_int', 'versao_soap', $objInfraMetaBD->tipoTextoFixo(5), 'NULL');
            $instanciaBanco->executarSql("UPDATE md_lit_situacao_lancam_int SET versao_soap = '1.2' ");

            $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
            $instanciaBanco->executarSql('UPDATE infra_parametro SET valor = \'1.7.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

            $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.7.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
            //efetiva as transações DML no banco de dados
            $instanciaBanco->confirmarTransacao();
        } catch (Exception $e) {
            //rollback DML
            $instanciaBanco->cancelarTransacao();
            $this->logar('ROLLBACK DA OPERAÇÃO');
            $objInfraMetaBD->excluirColuna('md_lit_integracao', 'tipo_cliente_ws');
            $objInfraMetaBD->excluirColuna('md_lit_integracao', 'versao_soap');
            $objInfraMetaBD->excluirColuna('md_lit_servico_integracao', 'tipo_cliente_ws');
            $objInfraMetaBD->excluirColuna('md_lit_servico_integracao', 'versao_soap');
            $objInfraMetaBD->excluirColuna('md_lit_situacao_lancam_int', 'tipo_cliente_ws');
            $objInfraMetaBD->excluirColuna('md_lit_situacao_lancam_int', 'versao_soap');
            throw new Exception($e->getMessage());
        }
    }

    protected function instalarv180()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.8.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');
		
        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $objInfraMetaBD->setBolValidarIdentificador(true);

        $arrTabelas = array('md_lit_adm_modalidad_outor', 'md_lit_adm_tipo_outor', 'md_lit_assoc_disp_normat', 'md_lit_campo_integracao', 'md_lit_cancela_lancamento', 'md_lit_conduta', 'md_lit_controle', 'md_lit_dado_interessado', 'md_lit_decisao', 'md_lit_disp_normat', 'md_lit_especie_decisao', 'md_lit_fase', 'md_lit_funcionalidade', 'md_lit_historic_lancamento', 'md_lit_integracao', 'md_lit_lancamento', 'md_lit_mapea_param_entrada', 'md_lit_mapea_param_saida', 'md_lit_mapea_param_valor', 'md_lit_motivo', 'md_lit_nome_funcional', 'md_lit_numero_interessado', 'md_lit_obrigacao', 'md_lit_param_interessado', 'md_lit_processo_situacao', 'md_lit_reinciden_anteceden', 'md_lit_rel_controle_motivo', 'md_lit_rel_decis_lancament', 'md_lit_rel_decisao_uf', 'md_lit_rel_dis_nor_con_ctr', 'md_lit_rel_disp_norm_conduta', 'md_lit_rel_disp_norm_revogado', 'md_lit_rel_disp_norm_tipo_ctrl', 'md_lit_rel_esp_decisao_obr', 'md_lit_rel_num_inter_modali', 'md_lit_rel_num_inter_servico', 'md_lit_rel_num_inter_tp_outor', 'md_lit_rel_protoco_protoco', 'md_lit_rel_sit_serie', 'md_lit_rel_tipo_ctrl_tipo_dec', 'md_lit_rel_tp_control_moti', 'md_lit_rel_tp_controle_proced', 'md_lit_rel_tp_controle_unid', 'md_lit_rel_tp_controle_usu', 'md_lit_rel_tp_ctrl_proc_sobres', 'md_lit_rel_tp_dec_rein_ante', 'md_lit_rel_tp_especie_dec', 'md_lit_servico', 'md_lit_servico_integracao', 'md_lit_situacao', 'md_lit_situacao_lancam_int', 'md_lit_situacao_lancamento', 'md_lit_tipo_controle', 'md_lit_tipo_decisao');
        
		$this->fixIndices($objInfraMetaBD, $arrTabelas);

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.8.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.8.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

    }

    protected function instalarv190()
    {
        $instanciaBanco = BancoSEI::getInstance();
        try {
            $instanciaBanco->abrirTransacao();
            $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.9.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

            $this->logar('CONSULTANDO O CAMPO "Data da Constituição" NA TABELA "md_lit_campo_integracao"');
            $arrDadosAlteracao = array('nomeCampo' => 'Data da Constituição Definitiva');
            $objMdLitCampoIntegracaoRN = new MdLitCampoIntegracaoRN();
            $objMdLitCampoIntegracaoDTO = new MdLitCampoIntegracaoDTO();
            $objMdLitCampoIntegracaoDTO->setStrNomeCampo('Data da Constituição');
            $objMdLitCampoIntegracaoDTO->retStrNomeCampo();
            $objMdLitCampoIntegracaoDTO->retNumIdMdLitCampoIntegracao();
            $objMdLitCampoIntegracaoDTO = $objMdLitCampoIntegracaoRN->consultar($objMdLitCampoIntegracaoDTO);
            if (!is_null($objMdLitCampoIntegracaoDTO)) {
                $this->logar('ATUALIZANDO O CAMPO "Data da Constituição" PARA "Data da Constituição Definitiva" NA TABELA "md_lit_campo_integracao"');
                $objMdLitCampoIntegracaoDTO->setNumIdMdLitCampoIntegracao($objMdLitCampoIntegracaoDTO->getNumIdMdLitCampoIntegracao());
                $objMdLitCampoIntegracaoDTO->setStrNomeCampo($arrDadosAlteracao['nomeCampo']);
                $objMdLitCampoIntegracaoRN->alterar($objMdLitCampoIntegracaoDTO);
            }

            $this->logar('INSERINDO O CAMPO "Data da Intimação da Decisão Definitiva" NA TABELA: md_lit_campo_integracao');
            $arrDadosInclusao = array('idMdLitCampoIntegracao' => 86, 'idMdLitFuncionalidade' => 5, 'nomeCampo' => 'Data da Intimação da Decisão Definitiva', 'staParametro' => 'E');
            $objMdLitCampoIntegracaoRN = new MdLitCampoIntegracaoRN();
            $objMdLitCampoIntegracaoDTO = new MdLitCampoIntegracaoDTO();
            $objMdLitCampoIntegracaoDTO->setNumIdMdLitCampoIntegracao($arrDadosInclusao['idMdLitCampoIntegracao']);
            $objMdLitCampoIntegracaoDTO->setNumIdMdLitFuncionalidade($arrDadosInclusao['idMdLitFuncionalidade']);
            $objMdLitCampoIntegracaoDTO->setStrNomeCampo($arrDadosInclusao['nomeCampo']);
            $objMdLitCampoIntegracaoDTO->setStrStaParametro($arrDadosInclusao['staParametro']);
            $objMdLitCampoIntegracaoRN->cadastrar($objMdLitCampoIntegracaoDTO);

            $this->logar('ATUALIZANDO O CAMPO "prazo" PARA O VALOR "10" QUANDO O CAMPO "sin_recursal" FOR IGUAL A "S" NA TABELA: md_lit_situacao');
            BancoSEI::getInstance()->executarSql('UPDATE md_lit_situacao SET prazo = \'10\' WHERE sin_recursal = \'S\' AND prazo IS NULL');

            $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
            BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.9.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

            $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.9.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

            $instanciaBanco->confirmarTransacao();

        } catch (Exception $e) {
            //rollback DML
            $this->logar('ROLLBACK DA OPERAÇÃO');
            $instanciaBanco->cancelarTransacao();
            throw new Exception($e->getMessage());
        }

    }

    protected function instalarv1100()
    {
        $instanciaBanco = BancoSEI::getInstance();
        try {
            $instanciaBanco->abrirTransacao();
            $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.10.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

            $objInfraMetaBD = new InfraMetaBD($instanciaBanco);
            $this->logar('ADICIONANDO A COLUNA dta_decurso_prazo_recurso NA TABELA md_lit_lancamento');
            $objInfraMetaBD->adicionarColuna('md_lit_lancamento', 'dta_decurso_prazo_recurso', $objInfraMetaBD->tipoDataHora(), 'NULL');

            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->retTodos();
            $objMdLitLancamentoRN = new MdLitLancamentoRN();
            $arrObjMdLitLancamentoDTO = $objMdLitLancamentoRN->listar($objMdLitLancamentoDTO);
            if ($objMdLitLancamentoDTO) {
                $this->logar('ATUALIZANDO A DATA DE DECURSO DO PRAZO PARA RECURSO PARA OS DADOS LEGADOS');
                foreach ($arrObjMdLitLancamentoDTO as $objMdLitLancamento) {
                    if (!is_null($objMdLitLancamento->getDtaIntimacao())) {

                        $idProcedimento = $objMdLitLancamento->getDblIdProcedimento();
                        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
                        $objMdLitProcessoSituacaoDTO->retNumPrazoSituacao();
                        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
                        $objMdLitProcessoSituacaoDTO->setStrSinRecursalSit('S');
                        $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_DESC);

                        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
                        $objMdLitProcessoSituacaoDTO = current($objMdLitProcessoSituacaoRN->listar($objMdLitProcessoSituacaoDTO));

                        if ($objMdLitProcessoSituacaoDTO) {
                            if ($objMdLitProcessoSituacaoDTO->getNumPrazoSituacao()) {
                                $dtDtDecursoPrazoRecurso = MdLitDecisaoINT::calcularDataPrazo($objMdLitProcessoSituacaoDTO->getNumPrazoSituacao(), $objMdLitLancamento->getDtaIntimacao());
                                $objMdLitLancamento->setDtaDecursoPrazoRecurso($dtDtDecursoPrazoRecurso);
                                $objMdLitLancamentoRN->alterar($objMdLitLancamento);
                            }
                        }
                    }
                }
                $this->logar('ATUALIZAÇÃO FINALIZADA');
            }

            $this->logar('ADICIONANDO A COLUNA cpf NA TABELA md_lit_dado_interessado');
            $objInfraMetaBD->adicionarColuna('md_lit_dado_interessado', 'cpf', $objInfraMetaBD->tipoNumeroGrande(), 'NULL');
            $this->logar('ADICIONANDO A COLUNA cnpj NA TABELA md_lit_dado_interessado');
            $objInfraMetaBD->adicionarColuna('md_lit_dado_interessado', 'cnpj', $objInfraMetaBD->tipoNumeroGrande(), 'NULL');

            $objMdLitDadoInteressadoDTO = new MdLitDadoInteressadoDTO();
            $objMdLitDadoInteressadoDTO->retTodos();
            $objMdLitDadoInteressadoRN = new MdLitDadoInteressadoRN();
            $arrObjMdLitDadoInteressadoDTO = $objMdLitDadoInteressadoRN->listar($objMdLitDadoInteressadoDTO);
            if ($objMdLitDadoInteressadoDTO) {
                $_SESSION['ignorarValidacaoOutorgado'] = true;
                $i = 0;
                $this->logar('ATUALIZANDO DADOS DE CPF/CNPJ PARA OS DADOS LEGADOS');
                foreach ($arrObjMdLitDadoInteressadoDTO as $objMdLitDadoInteressado) {
                    $this->logar('objMdLitDadoInteressado: ' . $objMdLitDadoInteressado->getNumIdContato());

                    $contatoDTO = new ContatoDTO();
                    $contatoDTO->retDblCnpj();
                    $contatoDTO->retDblCpf();
                    $contatoDTO->retStrStaNatureza();
                    $contatoDTO->setBolExclusaoLogica(false);
                    $contatoDTO->setNumIdContato($objMdLitDadoInteressado->getNumIdContato());
                    $contatoRN = new ContatoRN();
                    $contatoDTO = $contatoRN->consultarRN0324($contatoDTO);
                    if ($contatoDTO) {
                        try {
                            if ($contatoDTO->getStrStaNatureza() == "J") {
                                $objMdLitDadoInteressado->setDblCnpj($contatoDTO->getDblCnpj());
                            } else {
                                $objMdLitDadoInteressado->setDblCpf($contatoDTO->getDblCpf());
                            }
                            $objMdLitDadoInteressadoRN->alterar($objMdLitDadoInteressado);
                        } catch (Exception $e) {
                            $this->logar('ERRO: ' . $e);
                        }
                    }
                }
                unset($_SESSION['ignorarValidacaoOutorgado']);
                $this->logar('ATUALIZAÇÃO FINALIZADA');
            }

            $this->logar('RENOMEANDO coluna na tabela md_lit_especie_decisao de sin_ressarcimento_valor para sin_valor');
            $objInfraMetaBD->adicionarColuna('md_lit_especie_decisao', 'sin_valor', $objInfraMetaBD->tipoTextoFixo(1), 'NULL');
            BancoSEI::getInstance()->executarSql('UPDATE md_lit_especie_decisao set sin_valor=sin_ressarcimento_valor');
            $objInfraMetaBD->excluirColuna('md_lit_especie_decisao', 'sin_ressarcimento_valor');

            $this->logar('RENOMEANDO coluna na tabela md_lit_decisao de valor_ressarcimento para valor');
            $objInfraMetaBD->adicionarColuna('md_lit_decisao', 'valor', $objInfraMetaBD->tipoNumeroDecimal(19, 2), 'NULL');
            BancoSEI::getInstance()->executarSql('UPDATE md_lit_decisao set valor=valor_ressarcimento');
            $objInfraMetaBD->excluirColuna('md_lit_decisao', 'valor_ressarcimento');

            $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
            BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'1.10.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

            $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 1.10.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');

            $instanciaBanco->confirmarTransacao();

        } catch (Exception $e) {
            //rollback DML
            $this->logar('ROLLBACK DA OPERAÇÃO');
            $instanciaBanco->cancelarTransacao();
            throw new Exception($e->getMessage());
        }
    }

    protected function instalarv200()
    {
        $this->logar('EXECUTANDO A INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 2.0.0 DO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

        $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
        $objInfraMetaBD->setBolValidarIdentificador(true);
		
        $arrTabelas = array('md_lit_adm_modalidad_outor', 'md_lit_adm_tipo_outor', 'md_lit_assoc_disp_normat', 'md_lit_campo_integracao', 'md_lit_cancela_lancamento', 'md_lit_conduta', 'md_lit_controle', 'md_lit_dado_interessado', 'md_lit_decisao', 'md_lit_disp_normat', 'md_lit_especie_decisao', 'md_lit_fase', 'md_lit_funcionalidade', 'md_lit_historic_lancamento', 'md_lit_integracao', 'md_lit_lancamento', 'md_lit_mapea_param_entrada', 'md_lit_mapea_param_saida', 'md_lit_mapea_param_valor', 'md_lit_motivo', 'md_lit_nome_funcional', 'md_lit_numero_interessado', 'md_lit_obrigacao', 'md_lit_param_interessado', 'md_lit_processo_situacao', 'md_lit_reinciden_anteceden', 'md_lit_rel_controle_motivo', 'md_lit_rel_decis_lancament', 'md_lit_rel_decisao_uf', 'md_lit_rel_dis_nor_con_ctr', 'md_lit_rel_disp_norm_conduta', 'md_lit_rel_disp_norm_revogado', 'md_lit_rel_disp_norm_tipo_ctrl', 'md_lit_rel_esp_decisao_obr', 'md_lit_rel_num_inter_modali', 'md_lit_rel_num_inter_servico', 'md_lit_rel_num_inter_tp_outor', 'md_lit_rel_protoco_protoco', 'md_lit_rel_sit_serie', 'md_lit_rel_tipo_ctrl_tipo_dec', 'md_lit_rel_tp_control_moti', 'md_lit_rel_tp_controle_proced', 'md_lit_rel_tp_controle_unid', 'md_lit_rel_tp_controle_usu', 'md_lit_rel_tp_ctrl_proc_sobres', 'md_lit_rel_tp_dec_rein_ante', 'md_lit_rel_tp_especie_dec', 'md_lit_servico', 'md_lit_servico_integracao', 'md_lit_situacao', 'md_lit_situacao_lancam_int', 'md_lit_situacao_lancamento', 'md_lit_tipo_controle', 'md_lit_tipo_decisao');
        
		$this->fixIndices($objInfraMetaBD, $arrTabelas);

        $this->logar('ATUALIZANDO PARÂMETRO ' . $this->nomeParametroModulo . ' NA TABELA infra_parametro PARA CONTROLAR A VERSÃO DO MÓDULO');
        BancoSEI::getInstance()->executarSql('UPDATE infra_parametro SET valor = \'2.0.0\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

        $this->logar('INSTALAÇÃO/ATUALIZAÇÃO DA VERSÃO 2.0.0 DO ' . $this->nomeDesteModulo . ' REALIZADA COM SUCESSO NA BASE DO SEI');
    
	}

    protected function fixIndices(InfraMetaBD $objInfraMetaBD, $arrTabelas)
    {
        InfraDebug::getInstance()->setBolDebugInfra(true);
        
        $this->logar('ATUALIZANDO INDICES...');
		
		$objInfraMetaBD->processarIndicesChavesEstrangeiras($arrTabelas);
		
		InfraDebug::getInstance()->setBolDebugInfra(false);
    }

}

try {
    
	SessaoSEI::getInstance(false);
    BancoSEI::getInstance()->setBolScript(true);

    $configuracaoSEI = new ConfiguracaoSEI();
    $arrConfig = $configuracaoSEI->getInstance()->getArrConfiguracoes();

    if (!isset($arrConfig['SEI']['Modulos'])) {
        throw new InfraException('PARÂMETRO DE MÓDULOS NO CONFIGURAÇÃO DO SEI NÃO DECLARADO');
    } else {
        $arrModulos = $arrConfig['SEI']['Modulos'];
        if (!key_exists('LitigiosoIntegracao', $arrModulos)) {
            throw new InfraException('MÓDULO LITIGIOSO NÃO DECLARADO NO CONFIGURAÇÃO DO SEI');
        }
    }

    if (!class_exists('LitigiosoIntegracao')) {
        throw new InfraException('A CLASSE PRINCIPAL "LitigiosoIntegracao" DO MÓDULO NÃO FOI ENCONTRADA');
    }

    InfraScriptVersao::solicitarAutenticacao(BancoSei::getInstance());
    $objVersaoSeiRN = new MdLitAtualizadorSeiRN();
    $objVersaoSeiRN->atualizarVersao();
    exit;

} catch (Exception $e) {
    echo(InfraException::inspecionar($e));
    try {
        LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e) {
    }
    exit(1);
}
?>