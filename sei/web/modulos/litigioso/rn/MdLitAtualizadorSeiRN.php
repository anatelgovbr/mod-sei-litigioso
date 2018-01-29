<?
    /**
     * ANATEL
     *
     * 11/03/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitAtualizadorSeiRN extends InfraRN
    {

        private $numSeg                 = 0;
        private $versaoAtualDesteModulo = '0.0.4';
        private $nomeDesteModulo        = 'LITIGIOSO';
        private $nomeParametroModulo    = 'VERSAO_MODULO_LITIGIOSO';
        private $historicoVersoes       = array('0.0.1', '0.0.2', '0.0.3', '0.0.4');

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

    protected function atualizarVersaoConectado(){

        try {
            $this->inicializar('INICIANDO ATUALIZACAO DO MODULO ' . $this->nomeDesteModulo . ' NO SEI VERSAO ' . SEI_VERSAO);

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

                //testando se esta usando BDs suportados
                if (!(BancoSEI::getInstance() instanceof InfraMySql) &&
                    !(BancoSEI::getInstance() instanceof InfraSqlServer) &&
                    !(BancoSEI::getInstance() instanceof InfraOracle)
                ) {

                    $this->finalizar('BANCO DE DADOS NAO SUPORTADO: ' . get_parent_class(BancoSEI::getInstance()), true);

                }

                //testando permissoes de criaçoes de tabelas
                $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

                if (count($objInfraMetaBD->obterTabelas('sei_teste')) == 0) {
                    BancoSEI::getInstance()->executarSql('CREATE TABLE sei_teste (id ' . $objInfraMetaBD->tipoNumero() . ' null)');
                }

                BancoSEI::getInstance()->executarSql('DROP TABLE sei_teste');

                $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

                //$strVersaoAtual = $objInfraParametro->getValor('SEI_VERSAO', false);
                $strVersaoModuloLitigioso = $objInfraParametro->getValor($this->nomeParametroModulo, false);

                //VERIFICANDO QUAL VERSAO DEVE SER INSTALADA NESTA EXECUCAO
                if (InfraString::isBolVazia($strVersaoModuloLitigioso)) {

                    //nao tem nenhuma versao ainda, instalar todas
                    $this->instalarv001();
                    $this->instalarv002();
                    $this->instalarv003();
                    $this->instalarv004();
                    $this->logar('======================================================================================================');
                    //$this->logar('INICIANDO EXECUCAO DE ATUALIZACOES DO MODULO LITIGIOSO NA BASE DO SIP');
                    //$this->logar('ATUALIZACOES DO MODULO LITIGIOSO NA BASE DO SIP REALIZADAS COM SUCESSO');
                    //$this->finalizar('FIM', false);
                } else if ($strVersaoModuloLitigioso == '0.0.1') {
                    $this->instalarv002();
                    $this->instalarv003();
                    $this->logar('======================================================================================================');
                    //$this->logar('INICIANDO EXECUCAO DE ATUALIZACOES DO MODULO LITIGIOSO NA BASE DO SIP');
                    //$this->logar('ATUALIZACOES DO MODULO LITIGIOSO NA BASE DO SIP REALIZADAS COM SUCESSO');
                    //$this->finalizar('FIM', false);
                } else if ($strVersaoModuloLitigioso == '0.0.2') {
                    $this->instalarv003();
                    $this->logar('======================================================================================================');
                    //$this->logar('INICIANDO EXECUCAO DE ATUALIZACOES DO MODULO LITIGIOSO NA BASE DO SIP');
                    //$this->logar('ATUALIZACOES DO MODULO LITIGIOSO NA BASE DO SIP REALIZADAS COM SUCESSO');
                    //$this->finalizar('FIM', false);
                } else if ($strVersaoModuloLitigioso == '0.0.3') {
                    $this->instalarv004();
                    $this->logar('======================================================================================================');
                    //$this->finalizar('FIM', true);
                } else if ($strVersaoModuloLitigioso == '0.0.4') {
                    $this->logar(' A VERSAO MAIS ATUAL DO MODULO ' . $this->nomeDesteModulo . ' (v ' . $this->versaoAtualDesteModulo . ') JA ESTA INSTALADA. ');
                    //$this->finalizar('FIM', true);
                }

                InfraDebug::getInstance()->setBolDebugInfra(true);

            } catch (Exception $e) {

                InfraDebug::getInstance()->setBolLigado(false);
                InfraDebug::getInstance()->setBolDebugInfra(false);
                InfraDebug::getInstance()->setBolEcho(false);
                throw new InfraException('Erro atualizando VERSAO.', $e);
            }
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

        /* Contem atualizações da versao 0.0.1 do modulo */

        private function finalizar($strMsg = null, $bolErro)
        {

            if (!$bolErro) {
                $this->numSeg = InfraUtil::verificarTempoProcessamento($this->numSeg);
                $this->logar('TEMPO TOTAL DE EXECUCAO: ' . $this->numSeg . ' s');
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

        /* Contem ATUALIZACOES da versao 0.0.2 do MODULO */

        protected function instalarv001()
        {

            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

            //$this->finalizar(' EXECUTANDO v001', true);
            $this->logar(' EXECUTANDO A INSTALACAO DA VERSAO 0.0.1 DO MODULO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

            //adicionando parametro para controlar versao do modulo
            BancoSEI::getInstance()->executarSql('insert into infra_parametro (valor, nome ) VALUES( \'' . $this->versaoAtualDesteModulo . '\',  \'' . $this->nomeParametroModulo . '\' )');

            $this->logar(' EXECUTADA A INSTALACAO DA VERSAO 0.0.1 DO MODULO ' . $this->nomeDesteModulo . ' NO SEI COM SUCESSO');

        }

        /* Contem ATUALIZACOES da versao 0.0.3 do MODULO */

        protected function instalarv002()
        {

            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

            $this->logar(' INICIANDO OPERACOES DA INSTALACAO DA VERSAO 0.0.2 DO MODULO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

            //============================= alteraï¿½oes na tabela mod_tipo_processo_litigioso e na sua PK ============================

            //recriar a tabela e a sua sequence
            $this->logar(' CRIANDO A TABELA md_lit_tipo_controle E SUA sequence');

            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_tipo_controle (id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
		                                                                             sigla ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
  																					 descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
																		  			 sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL )');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_tipo_controle', 'pk_md_lit_tipo_controle', array('id_md_lit_tipo_controle'));

            //criando a sequence para a tabela md_lit_fase
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_tipo_controle (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_tipo_controle (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_tipo_controle', 1);
            }

            //============================= INICIO FASE =============================================================================

            $this->logar(' CRIANDO A TABELA md_lit_fase E SUA sequence');

            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_fase (id_md_lit_fase ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
		                                                                             nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
  																					 descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
																		  			 sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL,
																		  			 id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_fase', 'pk_md_lit_fase', array('id_md_lit_fase'));

            //criando a sequence para a tabela md_lit_fase
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_fase (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_fase (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_fase', 1);
            }

            //============================= FIM FASE ================================================================================

            //============================= criando relacionamentos com a tabela md_lit_tipo_controle ===============================

            //**********************************************************************************
            //RELACIONAMENTO 1 - Tipo de controle x Usuario Gestor - md_lit_rel_tp_controle_usu
            //**********************************************************************************
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_controle_usu (id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
																		  			 id_usuario ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_controle_usu', 'pk_md_lit_rel_tp_ctrl_usu', array('id_md_lit_tipo_controle', 'id_usuario'));

            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_controle_usu_01', 'md_lit_rel_tp_controle_usu', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_controle_usu_02', 'md_lit_rel_tp_controle_usu', array('id_usuario'), 'usuario', array('id_usuario'));

            //*******************************************************************************
            //RELACIONAMENTO 2 - Tipo de Controle X Unidades -> md_lit_rel_tp_controle_unid
            //******************************************************************************
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_controle_unid (id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
																		  			 id_unidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_controle_unid', 'pk_md_lit_rel_tp_ctrl_unid', array('id_md_lit_tipo_controle', 'id_unidade'));

            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_controle_unid_01', 'md_lit_rel_tp_controle_unid', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_controle_unid_02', 'md_lit_rel_tp_controle_unid', array('id_unidade'), 'unidade', array('id_unidade'));

            //********************************************************************************************
            //RELACIONAMENTO 3 - Tipo de Controle X Tipo de Procedimento (Tipo de Processos) -> md_lit_rel_tp_controle_proced
            //********************************************************************************************
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_controle_proced (id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
																		  			 id_tipo_procedimento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_controle_proced', 'pk_md_rel_tp_controle_proced', array('id_md_lit_tipo_controle', 'id_tipo_procedimento'));

            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_ctrl_proc_01', 'md_lit_rel_tp_controle_proced', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_ctrl_proc_02', 'md_lit_rel_tp_controle_proced', array('id_tipo_procedimento'), 'tipo_procedimento', array('id_tipo_procedimento'));

            //********************************************************************************************
            //RELACIONAMENTO 4 - Tipo de Controle X Tipo de Processos sobrestados -> md_lit_rel_tp_ctrl_proc_sobres
            //********************************************************************************************
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_ctrl_proc_sobres (id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
																		  			 id_tipo_procedimento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_ctrl_proc_sobres', 'pk_md_rel_tp_ctrl_proc_sobres', array('id_md_lit_tipo_controle', 'id_tipo_procedimento'));

            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_ctrl_pro_sobre_01', 'md_lit_rel_tp_ctrl_proc_sobres', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_rel_tp_ctrl_pro_sobre_02', 'md_lit_rel_tp_ctrl_proc_sobres', array('id_tipo_procedimento'), 'tipo_procedimento', array('id_tipo_procedimento'));


            //============================= INICIO SITUACAO =========================================================================

            $this->logar(' CRIANDO A TABELA md_lit_situacao E SUA sequence');

            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_situacao (id_md_lit_situacao ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
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

            //criando a sequence para a tabela md_lit_situacao
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_situacao (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_situacao (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_situacao', 1);
            }

            //********************************************************************************************
            //RELACIONAMENTO 1 - Situacao X Serie -> md_lit_rel_sit_serie
            //********************************************************************************************
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_sit_serie (id_md_lit_situacao ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
																		  	 id_serie ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL)');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_sit_serie', 'pk_md_lit_rel_sit_serie', array('id_md_lit_situacao', 'id_serie'));

            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_lit_rel_sit_serie_01', 'md_lit_rel_sit_serie', array('id_md_lit_situacao'), 'md_lit_situacao', array('id_md_lit_situacao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_md_lit_rel_sit_serie_02', 'md_lit_rel_sit_serie', array('id_serie'), 'serie', array('id_serie'));

            //============================= FIM SITUACAO ============================================================================

            //atualizando parametro para controlar versao do modulo
            BancoSEI::getInstance()->executarSql('update infra_parametro SET valor = \'' . $this->versaoAtualDesteModulo . '\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

            $this->logar(' EXECUTADA A INSTALAÇÃO DA VERSAO 0.0.2 DO MODULO ' . $this->nomeDesteModulo . ' NO SEI COM SUCESSO');

        }

        /* Contem ATUALIZACOES da versao 0.0.4 do MODULO */

        protected function instalarv003()
        {

            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
            $this->logar(' INICIANDO OPERACOES DA INSTALACAO DA VERSAO 0.0.3 DO MODULO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

            $this->logar(' INICIANDO OPERACOES DA INSTALACAO DA VERSAO 0.0.3 DO MODULO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

            //=========================================== Tabelas Conduta

            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_conduta (id_md_lit_conduta ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
		                                                                             nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
																		  			 sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL )');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_conduta', 'pk_md_lit_conduta', array('id_md_lit_conduta'));

            //criando a sequence para a tabela md_lit_situacao
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_conduta (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_conduta (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_conduta', 1);
            }

            //========================================= Tabelas Dispositivos Normativos
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_disp_normat (id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
		                                                                             norma ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
  																					 url ' . $objInfraMetaBD->tipoTextoVariavel(2083) . ' NULL,
																		  			 dispositivo ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL, 
  																					descricao ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL,																		 
  																					sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL )');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_disp_normat', 'pk_md_lit_disp_normat', array('id_md_lit_disp_normat'));

            //criando a sequence para a tabela md_lit_situacao
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_disp_normat (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_disp_normat (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_disp_normat', 1);
            }

            //=================== Tabelas Relacionais Dispositivos X Tipo de Controle e Dispositivos X Condutas ===============================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_disp_norm_conduta (id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
		                                                                             id_md_lit_conduta ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL )');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_disp_norm_conduta', 'pk_md_lit_rel_disp_norm_conduta', array('id_md_lit_disp_normat', 'id_md_lit_conduta'));

            $objInfraMetaBD->adicionarChaveEstrangeira('fk_dispositivo_norm_01', 'md_lit_rel_disp_norm_conduta', array('id_md_lit_disp_normat'), 'md_lit_disp_normat', array('id_md_lit_disp_normat'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_conduta_01', 'md_lit_rel_disp_norm_conduta', array('id_md_lit_conduta'), 'md_lit_conduta', array('id_md_lit_conduta'));

            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_disp_norm_tipo_ctrl (id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
		                                                                             id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL )');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_disp_norm_tipo_ctrl', 'pk_md_lit_rel_disp_norm_tipo_ctrl', array('id_md_lit_disp_normat', 'id_md_lit_tipo_controle'));

            $objInfraMetaBD->adicionarChaveEstrangeira('fk_dispositivo_norm_02', 'md_lit_rel_disp_norm_tipo_ctrl', array('id_md_lit_disp_normat'), 'md_lit_disp_normat', array('id_md_lit_disp_normat'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_tipo_controle_01', 'md_lit_rel_disp_norm_tipo_ctrl', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));

            //================================ Tabelas Associar ===============================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_assoc_disp_normat (id_md_lit_assoc_disp_normat ' . $objInfraMetaBD->tipoNumero() . '  NOT NULL ,
		                                                                             id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
  																					 id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL )');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_assoc_disp_normat', 'pk_md_lit_assoc_disp_normat', array('id_md_lit_assoc_disp_normat'));

            //criando a sequence para a tabela md_lit_situacao
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_assoc_disp_normat (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_assoc_disp_normat (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_assoc_disp_normat', 1);
            }

            //============================== atualizando parametro para controlar versao do modulo
            BancoSEI::getInstance()->executarSql('update infra_parametro SET valor = \'' . $this->versaoAtualDesteModulo . '\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');

            $this->logar(' EXECUTADA A INSTALAÇÂO DA VERSAO 0.0.3 DO MODULO ' . $this->nomeDesteModulo . ' NO SEI COM SUCESSO');

        }

        protected function instalarv004()
        {

            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());
            $this->logar(' INICIANDO OPERACOES DA INSTALACAO DA VERSAO 0.0.4 DO MODULO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

            $objInfraMetaBD = new InfraMetaBD(BancoSEI::getInstance());

            $this->logar(' INICIANDO OPERACOES DA INSTALACAO DA VERSAO 0.0.4 DO MODULO ' . $this->nomeDesteModulo . ' NA BASE DO SEI');

            //=========================================== Tabelas md_lit_tipo_decisao

            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_tipo_decisao(
		  id_md_lit_tipo_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
		  nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
		  descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NULL,
		  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_tipo_decisao', 'pk_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));

            //criando a sequence para a tabela md_lit_situacao
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_tipo_decisao (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_tipo_decisao (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_tipo_decisao', 1);
            }

            //	-- ==============================================================
            // -- Table: md_lit_especie_decisao
            //	-- ==============================================================

            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_especie_decisao (
		  id_md_lit_especie_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
		  nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
		  sin_rd_gestao_multa ' . $objInfraMetaBD->tipoTextoFixo(1) . ' DEFAULT NULL,
		  sin_rd_indicacao_prazo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' DEFAULT NULL,
		  sin_rd_indicacao_obrigacoes ' . $objInfraMetaBD->tipoTextoFixo(1) . ' DEFAULT NULL,
		  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_especie_decisao', 'pk_lit_especie_decisao', array('id_md_lit_especie_decisao'));

            // -- ==============================================================
            // --  Table: seq_md_lit_especie_decisao
            // -- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_especie_decisao (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_especie_decisao (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_especie_decisao', 1);
            }

            //	-- ==============================================================
            //	--  Table: md_lit_rel_tp_especie_dec
            //	-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tp_especie_dec (	  
	  id_md_lit_tipo_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
	  id_md_lit_especie_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tp_especie_dec', 'pk_rel_tp_esp_dec_01', array('id_md_lit_tipo_decisao', 'id_md_lit_especie_decisao'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_especie_decisao_01', 'md_lit_rel_tp_especie_dec', array('id_md_lit_tipo_decisao'), 'md_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_especie_decisao_02', 'md_lit_rel_tp_especie_dec', array('id_md_lit_especie_decisao'), 'md_lit_especie_decisao', array('id_md_lit_especie_decisao'));


            //	-- ==============================================================
            //	--  Table: md_lit_obrigacao
            //	-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_obrigacao (
      id_md_lit_obrigacao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
      nome ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
      descricao ' . $objInfraMetaBD->tipoTextoVariavel(250) . ' NOT NULL,
      sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ) ');

            $objInfraMetaBD->adicionarChavePrimaria('md_lit_obrigacao', 'pk_lit_obrigacao', array('id_md_lit_obrigacao'));


            //	-- ==============================================================
            // -- Table:  md_lit_rel_esp_decisao_obr
            //	-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE  md_lit_rel_esp_decisao_obr (
	  id_md_lit_especie_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
	  id_md_lit_obrigacao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria(' md_lit_rel_esp_decisao_obr', 'pk_rel_esp_dec_obr_01', array('id_md_lit_especie_decisao', 'id_md_lit_obrigacao'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_esp_dec_obr_01', ' md_lit_rel_esp_decisao_obr', array('id_md_lit_especie_decisao'), 'md_lit_especie_decisao', array('id_md_lit_especie_decisao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_esp_dec_obr_02', ' md_lit_rel_esp_decisao_obr', array('id_md_lit_obrigacao'), 'md_lit_obrigacao', array('id_md_lit_obrigacao'));

            //	-- ==============================================================
            //	--  Table: seq_md_lit_obrigacao
            //	-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_obrigacao (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_obrigacao (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_obrigacao', 1);
            }

            //	-- ==============================================================
            //	--  Table: md_lit_rel_tipo_ctrl_tipo_dec
            //	-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_tipo_ctrl_tipo_dec (
	  id_md_lit_tipo_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
	  id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL ) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_tipo_ctrl_tipo_dec', 'pk_lit_rel_tipo_ctrl_tipo_dec1', array('id_md_lit_tipo_controle', 'id_md_lit_tipo_decisao'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_ctrl_tipo_dec_01', 'md_lit_rel_tipo_ctrl_tipo_dec', array('id_md_lit_tipo_decisao'), 'md_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_rel_tipo_ctrl_tipo_dec_02', 'md_lit_rel_tipo_ctrl_tipo_dec', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));

            //	-- ALTER Table: md_lit_situacao
            BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_situacao ADD sin_decisoria ' . $objInfraMetaBD->tipoTextoFixo(1));

            //-- ==============================================================
            //--
            //--  Table: md_lit_controle
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_controle (
	  id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
	  id_procedimento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NULL,
	  id_documento ' . $objInfraMetaBD->tipoNumeroGrande() . ' NULL,
	  id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NULL,
	  dta_instauracao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_controle', 'pk_litcontrole', array('id_md_lit_controle'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_mdlitcontrole_documento', 'md_lit_controle', array('id_documento'), 'documento', array('id_documento'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_mdlitcontrole_procedimento', 'md_lit_controle', array('id_procedimento'), 'procedimento', array('id_procedimento'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_controle', 'md_lit_controle', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));

            //-- ==============================================================
            //--
            //--  Table: seq_md_lit_controle
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_controle (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_controle (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_controle', 1);
            }

            //-- ==============================================================
            //--
            //--  Table: md_lit_rel_dis_nor_con_ctr
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_dis_nor_con_ctr (
	  id_md_lit_rel_dis_nor_con_ctr ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
	  id_md_lit_disp_normat ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
	  id_md_lit_conduta ' . $objInfraMetaBD->tipoNumero() . ' NULL,
	  id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
	  dta_infracao ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL ) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_dis_nor_con_ctr', 'pk_md_lit_rel_dis_nor_con_ctr', array('id_md_lit_rel_dis_nor_con_ctr'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_condu_litrelreldisnorconctr', 'md_lit_rel_dis_nor_con_ctr', array('id_md_lit_conduta'), 'md_lit_conduta', array('id_md_lit_conduta'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_dinor_litrelreldisnorconctr', 'md_lit_rel_dis_nor_con_ctr', array('id_md_lit_disp_normat'), 'md_lit_disp_normat', array('id_md_lit_disp_normat'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_contr_litrelreldisnorconctr', 'md_lit_rel_dis_nor_con_ctr', array('id_md_lit_controle'), 'md_lit_controle', array('id_md_lit_controle'));


            //-- ==============================================================
            //--
            //--  Table: seq_md_lit_rel_dis_nor_con_ctr
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_rel_dis_nor_con_ctr (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_rel_dis_nor_con_ctr (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_rel_dis_nor_con_ctr', 1);
            }

            //-- ==============================================================
            //--
            //--  Table: md_lit_rel_protoco_protoco
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_protoco_protoco (
	  id_md_lit_rel_protoco_protoco ' . $objInfraMetaBD->tipoNumeroGrande() . ' NOT NULL,
	  id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' ,
	  id_protocolo_1 ' . $objInfraMetaBD->tipoNumeroGrande() . ' ,
	  id_protocolo_2 ' . $objInfraMetaBD->tipoNumeroGrande() . ' ,
	  id_documento ' . $objInfraMetaBD->tipoNumeroGrande() . ' ,
	  sin_ativo ' . $objInfraMetaBD->tipoTextoFixo(1) . ' NOT NULL ,
	  dta_sobrestamento ' . $objInfraMetaBD->tipoDataHora() . ' NOT NULL ) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_protoco_protoco', 'pk_md_lit_rel_protoco_protoco', array('id_md_lit_rel_protoco_protoco'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_litrelprotprot_prot1', 'md_lit_rel_protoco_protoco', array('id_protocolo_1'), 'protocolo', array('id_protocolo'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_litrelprotprot_prot2', 'md_lit_rel_protoco_protoco', array('id_protocolo_2'), 'protocolo', array('id_protocolo'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_litrelprotoproto_documento', 'md_lit_rel_protoco_protoco', array('id_documento'), 'documento', array('id_documento'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk_litcontrole_litrelprotprot', 'md_lit_rel_protoco_protoco', array('id_md_lit_controle'), 'md_lit_controle', array('id_md_lit_controle'));


            //-- ==============================================================
            //--
            //--  Table: seq_md_lit_rel_protoco_protoco
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_rel_protoco_protoco (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_rel_protoco_protoco (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_rel_protoco_protoco', 1);
            }

            //-- ==============================================================
            //-- Table: md_lit_modalidade
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_modalidade (
                    id_md_lit_modalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL ) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_modalidade', 'pk_md_lit_modalidade', array('id_md_lit_modalidade'));

            //-- ==============================================================
            //--  Table: seq_md_lit_modalidade
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_modalidade (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_modalidade (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_modalidade', 1);
            }

            //-- ==============================================================
            //-- populando a tabela md_lit_modalidade
            //-- ==============================================================
            $arrNome              = array(1 => 'Autorização', 2 => 'Concessão');
            $objMdLitModalidadeRN = new MdLitModalidadeRN();
            foreach ($arrNome as $codigo => $nome) {
                $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
                $objMdLitModalidadeDTO->setNumIdMdLitModalidade($codigo);
                $objMdLitModalidadeDTO->setStrNome($nome);
                $objMdLitModalidadeRN->cadastrar($objMdLitModalidadeDTO);
            }

            //-- ==============================================================
            //-- Table: md_lit_abrangencia
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_abrangencia (
                    id_md_lit_abrangencia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL ) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_abrangencia', 'pk_md_lit_abrangencia', array('id_md_lit_abrangencia'));

            //-- ==============================================================
            //--  Table: seq_md_lit_abrangencia
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_abrangencia (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_abrangencia (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_abrangencia', 1);
            }

            //-- ==============================================================
            //-- populando a tabela: md_lit_abrangencia
            //-- ==============================================================
            $arrNome               = array(1 => 'Nacional', 2 => 'Regional', 3 => 'Estadual');
            $objMdLitAbrangenciaRN = new MdLitAbrangenciaRN();
            foreach ($arrNome as $codigo => $nome) {
                $objMdLitAbrangenciaDTO = new MdLitAbrangenciaDTO();
                $objMdLitAbrangenciaDTO->setNumIdMdLitAbrangencia($codigo);
                $objMdLitAbrangenciaDTO->setStrNome($nome);
                $objMdLitAbrangenciaRN->cadastrar($objMdLitAbrangenciaDTO);
            }

            //-- ==============================================================
            //-- Table: md_lit_servico_integracao
            //-- ==============================================================
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


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_servico_integracao', 'pk_md_lit_servico_integracao', array('id_md_lit_servico_integracao'));

            //-- ==============================================================
            //--  Table: seq_md_lit_servico_integracao
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_servico_integracao (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_servico_integracao (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_servico_integracao', 1);
            }

            //-- ==============================================================
            //-- Table: md_lit_servico
            //-- ==============================================================
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


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_servico', 'pk_md_lit_servico', array('id_md_lit_servico'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_servico_integracao', 'md_lit_servico', array('id_md_lit_servico_integracao'), 'md_lit_servico_integracao', array('id_md_lit_servico_integracao'));

            //-- ==============================================================
            //--  Table: seq_md_lit_servico
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_servico (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_servico (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_servico', 1);
            }

            //-- ==============================================================
            //-- Table: md_lit_rel_servico_abrangen
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_servico_abrangen (
                    id_md_lit_servico ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_abrangencia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_servico_abrangen', 'pk1_md_lit_rel_servico_abrange', array('id_md_lit_servico', 'id_md_lit_abrangencia'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_servico_abrange', 'md_lit_rel_servico_abrangen', array('id_md_lit_abrangencia'), 'md_lit_abrangencia', array('id_md_lit_abrangencia'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_servico_abrange', 'md_lit_rel_servico_abrangen', array('id_md_lit_servico'), 'md_lit_servico', array('id_md_lit_servico'));


            //-- ==============================================================
            //-- Table: md_lit_rel_servico_modalidade
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_servico_modalidade (
                    id_md_lit_servico ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_modalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_servico_modalidade', 'pk1_md_lit_rel_servico_modalid', array('id_md_lit_servico', 'id_md_lit_modalidade'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_servico_modalid', 'md_lit_rel_servico_modalidade', array('id_md_lit_modalidade'), 'md_lit_modalidade', array('id_md_lit_modalidade'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_servico_modalid', 'md_lit_rel_servico_modalidade', array('id_md_lit_servico'), 'md_lit_servico', array('id_md_lit_servico'));

            //-- ==============================================================
            //-- Table: md_lit_dado_interessado
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_dado_interessado (
                    id_md_lit_dado_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_participante ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    numero ' . $objInfraMetaBD->tipoTextoVariavel(999) . ' NULL,
                    sin_outorgado ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL
                    ) ');

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_dado_interessado', 'pk1_md_lit_dado_interessado', array('id_md_lit_dado_interessado'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_dado_interessado', 'md_lit_dado_interessado', array('id_md_lit_controle'), 'md_lit_controle', array('id_md_lit_controle'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_dado_interessado', 'md_lit_dado_interessado', array('id_participante'), 'participante', array('id_participante'));

            //-- ==============================================================
            //--  Table: seq_md_lit_dado_interessado
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_dado_interessado (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_dado_interessado (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_dado_interessado', 1);
            }

            //-- ===============================================================
            //-- Column: sin_param_modal_compl_interes
            //-- ===============================================================
            $coluna = $objInfraMetaBD->obterColunasTabela('md_lit_tipo_controle', 'sin_param_modal_compl_interes');

            if ($coluna == null || !is_array($coluna)) {
                $objInfraMetaBD->adicionarColuna('md_lit_tipo_controle', 'sin_param_modal_compl_interes', '' . $objInfraMetaBD->tipoTextoVariavel(1), 'NULL');
            }

            //-- ===============================================================
            //-- Table: md_lit_nome_funcional
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_nome_funcional (
                    id_md_lit_nome_funcional ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_nome_funcional', 'pk1_md_lit_nome_funcional', array('id_md_lit_nome_funcional'));

            //-- ==============================================================
            //--  Table: seq_md_lit_nome_funcional
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_nome_funcional (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_nome_funcional (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_nome_funcional', 1);
            }

            //-- ===============================================================
            //-- Table: md_lit_param_interessado
            //-- ===============================================================
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

            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_param_interessado', 'pk1_md_lit_param_interessado', array('id_md_lit_param_interessado'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_param_interessado', 'md_lit_param_interessado', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_nome_funcional', 'md_lit_param_interessado', array('id_md_lit_nome_funcional'), 'md_lit_nome_funcional', array('id_md_lit_nome_funcional'));

            //-- ==============================================================
            //--  Table: seq_md_lit_param_interessado
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_param_interessado (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_param_interessado (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_param_interessado', 1);
            }


            //-- ==============================================================
            //--  populando a tabela: md_lit_nome_funcional
            //-- ==============================================================
            $objMdLitNomeFuncionalRN = new MdLitNomeFuncionalRN();
            $arrNome                 = array('CNPJ/CPF', 'Outorga', 'Número', 'Serviço', 'Modalidade',
                                             'Abrangência', 'Estado', 'Cidade');
            foreach ($arrNome as $nome) {
                $objMdLitNomeFuncionalDTO = new MdLitNomeFuncionalDTO();
                $objMdLitNomeFuncionalDTO->setStrNome($nome);
                $objMdLitNomeFuncionalRN->cadastrar($objMdLitNomeFuncionalDTO);
            }


            //-- ===============================================================
            //-- Table: md_lit_funcionalidade
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_funcionalidade (
                    id_md_lit_funcionalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_funcionalidade', 'pk1_md_lit_funcionalidade', array('id_md_lit_funcionalidade'));


            //-- ==============================================================
            //--  Table: seq_md_lit_funcionalidade
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_funcionalidade (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_funcionalidade (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_param_interessado', 1);
            }


            //-- ===============================================================
            //-- Table: md_lit_integracao
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_integracao (
                    id_md_lit_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_funcionalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    nome ' . $objInfraMetaBD->tipoTextoVariavel(30) . ' NOT NULL,
                    endereco_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NOT NULL,
                    operaca_wsdl ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL,
                    sin_ativo ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_integracao', 'pk1_md_lit_integracao', array('id_md_lit_integracao'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_integracao', 'md_lit_integracao', array('id_md_lit_funcionalidade'), 'md_lit_funcionalidade', array('id_md_lit_funcionalidade'));

            //-- ==============================================================
            //--  Table: seq_md_lit_integracao
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_integracao (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_integracao (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_integracao', 1);
            }


            //-- ===============================================================
            //-- Table: md_lit_mapea_param_saida
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_mapea_param_saida (
                    id_md_lit_mapea_param_saida ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_nome_funcional ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                    campo ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NOT NULL,
                    chave_unica ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_mapea_param_saida', 'pk_md_lit_mapea_param_saida', array('id_md_lit_mapea_param_saida'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_mapea_param_saida', 'md_lit_mapea_param_saida', array('id_md_lit_integracao'), 'md_lit_integracao', array('id_md_lit_integracao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_mapea_param_saida', 'md_lit_mapea_param_saida', array('id_md_lit_nome_funcional'), 'md_lit_nome_funcional', array('id_md_lit_nome_funcional'));

            //-- ==============================================================
            //--  Table: seq_md_lit_mapea_param_saida
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_mapea_param_saida (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_mapea_param_saida (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_mapea_param_saida', 1);
            }


            //-- ===============================================================
            //-- Table: md_lit_mapea_param_entrada
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_mapea_param_entrada (
                    id_md_lit_mapea_param_entrada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_nome_funcional ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                    campo ' . $objInfraMetaBD->tipoTextoVariavel(45) . ' NOT NULL,
                    chave_unica ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_mapea_param_entrada', 'pk_md_lit_mapea_param_entrada', array('id_md_lit_mapea_param_entrada'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_mapea_param_entrada', 'md_lit_mapea_param_entrada', array('id_md_lit_integracao'), 'md_lit_integracao', array('id_md_lit_integracao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_mapea_param_entrada', 'md_lit_mapea_param_entrada', array('id_md_lit_nome_funcional'), 'md_lit_nome_funcional', array('id_md_lit_nome_funcional'));


            //-- ==============================================================
            //--  Table: seq_md_lit_mapea_param_entrada
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_mapea_param_entrada (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_mapea_param_entrada (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_mapea_param_entrada', 1);
            }


            //-- ==============================================================
            //--  Populando a tabela: md_lit_funcionalidade
            //-- ==============================================================
            $objMdLitFuncionalidadeRN  = new  MdLitFuncionalidadeRN();
            $objMdLitFuncionalidadeDTO = new MdLitFuncionalidadeDTO();
            $objMdLitFuncionalidadeDTO->setStrNome('Dados Complementares do Interessado - Validação');
            $objMdLitFuncionalidadeRN->cadastrar($objMdLitFuncionalidadeDTO);

            //-- ==============================================================
            //-- Table: md_lit_rel_dado_inter_modali
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_dado_inter_modali (
                    id_md_lit_dado_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_modalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_dado_inter_modali', 'pk1_md_lit_rel_dado_inter_moda', array('id_md_lit_dado_interessado', 'id_md_lit_modalidade'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_dado_inter_moda', 'md_lit_rel_dado_inter_modali', array('id_md_lit_dado_interessado'), 'md_lit_dado_interessado', array('id_md_lit_dado_interessado'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_dado_inter_moda', 'md_lit_rel_dado_inter_modali', array('id_md_lit_modalidade'), 'md_lit_modalidade', array('id_md_lit_modalidade'));

            //-- ==============================================================
            //-- Table: md_lit_rel_dado_inter_abrang
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_dado_inter_abrang (
                    id_md_lit_dado_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_abrangencia ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_dado_inter_abrang', 'pk1_md_lit_rel_dado_inter_abra', array('id_md_lit_dado_interessado', 'id_md_lit_abrangencia'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_dado_inter_abra', 'md_lit_rel_dado_inter_abrang', array('id_md_lit_dado_interessado'), 'md_lit_dado_interessado', array('id_md_lit_dado_interessado'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_dado_inter_abra', 'md_lit_rel_dado_inter_abrang', array('id_md_lit_abrangencia'), 'md_lit_abrangencia', array('id_md_lit_abrangencia'));

            //-- ==============================================================
            //-- Table: md_lit_rel_dado_inter_servico
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_dado_inter_servico (
                    id_md_lit_dado_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_servico ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_dado_inter_servico', 'pk1_md_lit_rel_dado_inter_serv', array('id_md_lit_dado_interessado', 'id_md_lit_servico'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_dado_inter_serv', 'md_lit_rel_dado_inter_servico', array('id_md_lit_dado_interessado'), 'md_lit_dado_interessado', array('id_md_lit_dado_interessado'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_dado_inter_serv', 'md_lit_rel_dado_inter_servico', array('id_md_lit_servico'), 'md_lit_servico', array('id_md_lit_servico'));

            //-- ==============================================================
            //-- Table: md_lit_rel_dado_inter_cidade
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_dado_inter_cidade (
                    id_md_lit_dado_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_cidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_dado_inter_cidade', 'pk1_md_lit_rel_dado_inter_cida', array('id_md_lit_dado_interessado', 'id_cidade'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_dado_inter_cida', 'md_lit_rel_dado_inter_cidade', array('id_md_lit_dado_interessado'), 'md_lit_dado_interessado', array('id_md_lit_dado_interessado'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_dado_inter_cida', 'md_lit_rel_dado_inter_cidade', array('id_cidade'), 'cidade', array('id_cidade'));

            //-- ==============================================================
            //-- Table: md_lit_rel_dado_inter_uf
            //-- ==============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_dado_inter_uf (
                    id_md_lit_dado_interessado ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_uf ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_dado_inter_uf', 'pk1_md_lit_rel_dado_inter_uf', array('id_md_lit_dado_interessado', 'id_uf'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_dado_inter_uf', 'md_lit_rel_dado_inter_uf', array('id_md_lit_dado_interessado'), 'md_lit_dado_interessado', array('id_md_lit_dado_interessado'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_dado_inter_uf', 'md_lit_rel_dado_inter_uf', array('id_uf'), 'uf', array('id_uf'));


            //EU9383
            //md_lit_campo_integracao
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_campo_integracao (
                    id_md_lit_campo_integracao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_funcionalidade ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    nome_campo ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NULL,
                    sta_parametro ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL
                    ) ');

            //PK
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_campo_integracao', 'pk1_md_lit_campo_integracao', array('id_md_lit_campo_integracao'));

            //FK
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_campo_integracao', 'md_lit_campo_integracao', array('id_md_lit_funcionalidade'), 'md_lit_funcionalidade', array('id_md_lit_funcionalidade'));

            //md_lit_mapea_param_entrada
            //md_lit_mapea_param_saida
            BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_mapea_param_entrada ADD id_md_lit_campo_integracao ' . $objInfraMetaBD->tipoNumero());
            BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_mapea_param_entrada ADD constraint fk3_md_lit_mapea_param_entrada foreign key (id_md_lit_campo_integracao) references md_lit_campo_integracao (id_md_lit_campo_integracao) ');
            BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_mapea_param_saida ADD id_md_lit_campo_integracao ' . $objInfraMetaBD->tipoNumero());
            BancoSEI::getInstance()->executarSql('ALTER TABLE md_lit_mapea_param_saida ADD constraint fk3_md_lit_mapea_param_saida foreign key (id_md_lit_campo_integracao) references md_lit_campo_integracao (id_md_lit_campo_integracao) ');

            //md_lit_mapea_param_valor
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_mapea_param_valor (
                    id_md_lit_mapea_param_valor ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_mapea_param_entrada ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_tipo_controle ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    valor_default ' . $objInfraMetaBD->tipoTextoVariavel(100) . ' NULL
                    ) ');


            //PK
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_mapea_param_valor', 'pk1_md_lit_mapea_param_valor', array('id_md_lit_mapea_param_valor'));

            //FK
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_mapea_param_valor', 'md_lit_mapea_param_valor', array('id_md_lit_mapea_param_entrada'), 'md_lit_mapea_param_entrada', array('id_md_lit_mapea_param_entrada'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_mapea_param_valor', 'md_lit_mapea_param_valor', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));

            //criando a sequence para a tabela md_lit_mapea_param_valor
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_mapea_param_valor (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_mapea_param_valor (id bigint identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_mapea_param_valor', 1);
            }

            //Populando a tabela: md_lit_funcionalidade
            $arrDados = array(
                array('idMdLitFuncionalidade' => 2, 'nome' => 'Arrecadação Lançamento de Crédito'),
                array('idMdLitFuncionalidade' => 3, 'nome' => 'Arrecadação Consulta Lançamento'),
                array('idMdLitFuncionalidade' => 4, 'nome' => 'Arrecadação Cancelar Lançamento'),
                array('idMdLitFuncionalidade' => 5, 'nome' => 'Arrecadação Retificar Lançamento'),
                array('idMdLitFuncionalidade' => 6, 'nome' => 'Arrecadação Suspender Lançamento'),
                array('idMdLitFuncionalidade' => 7, 'nome' => 'Arrecadação Denegar Recurso'),
                array('idMdLitFuncionalidade' => 8, 'nome' => 'Arrecadação Cancelar Recurso'),
                array('idMdLitFuncionalidade' => 9, 'nome' => 'Arrecadação Listar Motivos de Cancelamento de Lançamento'),
                array('idMdLitFuncionalidade' => 10, 'nome' => 'Gerar Número do interessado para Entidade não Outorgada')
            );

            $objMdLitFuncionalidadeRN = new MdLitFuncionalidadeRN();
            foreach ($arrDados as $dados){
                $objMdLitFuncionalidadeDTO = new MdLitFuncionalidadeDTO();
                $objMdLitFuncionalidadeDTO->setNumIdMdLitFuncionalidade($dados['idMdLitFuncionalidade']);
                $objMdLitFuncionalidadeDTO->setStrNome($dados['nome']);
                $objMdLitFuncionalidadeRN->cadastrar($objMdLitFuncionalidadeDTO);
            }

            //Populando a tabela: md_lit_campo_integracao
            $arrDados = array(
                #Arrecadação Lançamento de Crédito
                  //Entrada
                    array('idMdLitCampoIntegracao' => 1, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Número de Complemento do Interessado', 'staParametro' => 'E'),
                    array('idMdLitCampoIntegracao' => 2, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Data de aplicação da Sanção', 'staParametro' => 'E'),
                    array('idMdLitCampoIntegracao' => 3, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Data de Vencimento', 'staParametro' => 'E'),
                    array('idMdLitCampoIntegracao' => 4, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Valor Total', 'staParametro' => 'E'),
                    array('idMdLitCampoIntegracao' => 5, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Código da Receita', 'staParametro' => 'E'),
                    array('idMdLitCampoIntegracao' => 6, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Número do Processo', 'staParametro' => 'E'),
                    array('idMdLitCampoIntegracao' => 7, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Justificativa do Lançamento', 'staParametro' => 'E'),
                    array('idMdLitCampoIntegracao' => 8, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Usuário de Inclusão', 'staParametro' => 'E'),
                    array('idMdLitCampoIntegracao' => 9, 'idMdLitFuncionalidade' => 2, 'nomeCampo'  => 'Sistema de Origem', 'staParametro' => 'E'),
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
            foreach ($arrDados as $dados){
                $objMdLitCampoIntegracaoDTO = new MdLitCampoIntegracaoDTO();
                $objMdLitCampoIntegracaoDTO->setNumIdMdLitCampoIntegracao($dados['idMdLitCampoIntegracao']);
                $objMdLitCampoIntegracaoDTO->setNumIdMdLitFuncionalidade($dados['idMdLitFuncionalidade']);
                $objMdLitCampoIntegracaoDTO->setStrNomeCampo($dados['nomeCampo']);
                $objMdLitCampoIntegracaoDTO->setStrStaParametro($dados['staParametro']);
                $objMdLitCampoIntegracaoRN->cadastrar($objMdLitCampoIntegracaoDTO);
            }
            //Fim EU9383

            //Init EU9382

            //-- ===============================================================
            //-- Table: md_lit_processo_situacao
            //-- ===============================================================
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


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_processo_situacao', 'pk1_md_lit_processo_situacao', array('id_md_lit_processo_situacao'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_md_lit_situacao'), 'md_lit_situacao', array('id_md_lit_situacao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_documento'), 'documento', array('id_documento'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_procedimento'), 'procedimento', array('id_procedimento'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_md_lit_tipo_controle'), 'md_lit_tipo_controle', array('id_md_lit_tipo_controle'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_usuario'), 'usuario', array('id_usuario'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk6_md_lit_processo_situacao', 'md_lit_processo_situacao', array('id_unidade'), 'unidade', array('id_unidade'));

            //-- ==============================================================
            //--  Table: seq_md_lit_processo_situacao
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_processo_situacao (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_processo_situacao (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_processo_situacao', 1);
            }


            //-- ===============================================================
            //-- Table: md_lit_processo_situacao
            //-- ===============================================================
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


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_decisao', 'pk1_md_lit_decisao', array('id_md_lit_decisao'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_processo_situacao'), 'md_lit_processo_situacao', array('id_md_lit_processo_situacao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_rel_dis_nor_con_ctr'), 'md_lit_rel_dis_nor_con_ctr', array('id_md_lit_rel_dis_nor_con_ctr'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_obrigacao'), 'md_lit_obrigacao', array('id_md_lit_obrigacao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_tipo_decisao'), 'md_lit_tipo_decisao', array('id_md_lit_tipo_decisao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_lit_decisao', 'md_lit_decisao', array('id_md_lit_especie_decisao'), 'md_lit_especie_decisao', array('id_md_lit_especie_decisao'));

            //-- ==============================================================
            //--  Table: seq_md_lit_decisao
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_decisao (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_decisao (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_decisao', 1);
            }

            //-- ===============================================================
            //-- Table: md_lit_situacao_lancamento
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_situacao_lancamento (
                    id_md_lit_situacao_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    nome ' . $objInfraMetaBD->tipoTextoVariavel(100) . 'NOT NULL,
                    cor_situacao ' . $objInfraMetaBD->tipoTextoVariavel(50) . ' NOT NULL
                    ) ');

            //Preenchendo os valores padrões de MdLitSituacaoLancamento
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_situacao_lancamento', 'pk1_md_lit_situacao_lancamento', array('id_md_lit_situacao_lancamento'));
            $arrParams = array(
                 array('id'=> 0, 'nome' => 'Devedor', 'cor'=> 'red'),
                 array('id'=> 1, 'nome' => 'Quitado', 'cor'=> 'blue'),
                 array('id'=> 2, 'nome' => 'Cancelado', 'cor'=> 'black'),
                 array('id'=> 3, 'nome' => 'Parcial', 'cor'=> 'green'),
                 array('id'=> 4, 'nome' => 'Pago a Maior', 'cor'=> 'black'),
                 array('id'=> 5, 'nome' => 'Cancelado', 'cor'=> 'black'),
                 array('id'=> 6, 'nome' => 'Cancelado', 'cor'=> 'black'),
                 array('id'=> 7, 'nome' => 'Restituído', 'cor'=> 'black'),
                 array('id'=> 8, 'nome' => 'Compensado', 'cor'=> 'black'),
                 array('id'=> 9, 'nome' => 'Estornado', 'cor'=> 'black'),
                 array('id'=> 10, 'nome' => 'Reposicionado', 'cor'=> 'black'),
                 array('id'=> 11, 'nome' => 'Restituído BB', 'cor'=> 'black'),
                 array('id'=> 12, 'nome' => 'Consolidado Devedor', 'cor'=> 'black'),
                 array('id'=> 13, 'nome' => 'Consolidado Quitado', 'cor'=> 'black'),
                 array('id'=> 14, 'nome' => 'Cheque Devolvido', 'cor'=> 'black'),
                 array('id'=> 15, 'nome' => 'Redirecionado', 'cor'=> 'black'),
                 array('id'=> 16, 'nome' => 'Sem Movimento', 'cor'=> 'black'),
                 array('id'=> 17, 'nome' => 'Repassado para AGU', 'cor'=> 'black'),
                 array('id'=> 18, 'nome' => 'Prescrito', 'cor'=> 'black'),
            );

            $objMdLitSituacaoLancamentoRN = new MdLitSituacaoLancamentoRN();
            foreach ($arrParams as $dados){
                $objMdLitSituacaoLancamentoDTO = new MdLitSituacaoLancamentoDTO();
                $objMdLitSituacaoLancamentoDTO->setNumIdMdLitSituacaoLancamento($dados['id']);
                $objMdLitSituacaoLancamentoDTO->setStrNome($dados['nome']);
                $objMdLitSituacaoLancamentoDTO->setStrCorSituacao($dados['cor']);
                $objMdLitSituacaoLancamentoRN->cadastrar($objMdLitSituacaoLancamentoDTO);
            }


            //-- ===============================================================
            //-- Table: md_lit_lancamento
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_lancamento (
                    id_md_lit_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_situacao_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NULL,
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
                    sin_suspenso ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_lancamento', 'pk1_md_lit_lancamento', array('id_md_lit_lancamento'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_lancamento', 'md_lit_lancamento', array('id_md_lit_situacao_lancamento'), 'md_lit_situacao_lancamento', array('id_md_lit_situacao_lancamento'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_lancamento', 'md_lit_lancamento', array('id_usuario'), 'usuario', array('id_usuario'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_lancamento', 'md_lit_lancamento', array('id_unidade'), 'unidade', array('id_unidade'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_lit_lancamento', 'md_lit_lancamento', array('id_procedimento'), 'procedimento', array('id_procedimento'));

            //-- ==============================================================
            //--  Table: seq_md_lit_lancamento
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_lancamento (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_lancamento (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_lancamento', 1);
            }


            //-- ===============================================================
            //-- Table: md_lit_historic_lancamento
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_historic_lancamento (
                    id_md_lit_historic_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_situacao_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NULL,
                    id_md_lit_lancamento ' .$objInfraMetaBD->tipoNumero() . ' NOT NULL,
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
                    sin_suspenso ' . $objInfraMetaBD->tipoTextoVariavel(1) . ' NULL
                    ) ');


            //PKs
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_historic_lancamento', 'pk1_md_lit_historic_lancamento', array('id_md_lit_historic_lancamento'));

            //FKs
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_md_lit_situacao_lancamento'), 'md_lit_situacao_lancamento', array('id_md_lit_situacao_lancamento'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_usuario'), 'usuario', array('id_usuario'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk3_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_unidade'), 'unidade', array('id_unidade'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk4_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_procedimento'), 'procedimento', array('id_procedimento'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk5_md_lit_historic_lancamento', 'md_lit_historic_lancamento', array('id_md_lit_lancamento'), 'md_lit_lancamento', array('id_md_lit_lancamento'));

            //-- ==============================================================
            //--  Table: seq_md_lit_lancamento
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_historic_lancamento (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_historic_lancamento (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_historic_lancamento', 1);
            }


            //-- ===============================================================
            //-- Table: md_lit_cancela_lancamento
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_cancela_lancamento (
                    id_md_lit_cancela_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    motivo_cancelamento ' . $objInfraMetaBD->tipoTextoVariavel(150) . ' NOT NULL,
                    justificativa ' . $objInfraMetaBD->tipoTextoVariavel(500) . ' NOT NULL
                    ) ');

            //PK
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_cancela_lancamento', 'pk1_md_lit_cancela_lancamento', array('id_md_lit_cancela_lancamento'));

            //FK
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_cancela_lancamento', 'md_lit_cancela_lancamento', array('id_md_lit_lancamento'), 'md_lit_lancamento', array('id_md_lit_lancamento'));

            //-- ==============================================================
            //--  Table: seq_md_lit_cancela_lancamento
            //-- ==============================================================
            if (BancoSEI::getInstance() instanceof InfraMySql) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_cancela_lancamento (id int(11) not null primary key AUTO_INCREMENT, campo char(1)) AUTO_INCREMENT = 1');
            } else if (BancoSEI::getInstance() instanceof InfraSqlServer) {
                BancoSEI::getInstance()->executarSql('create table seq_md_lit_cancela_lancamento (id int identity(1,1), campo char(1) null)');
            } else if (BancoSEI::getInstance() instanceof InfraOracle) {
                BancoSEI::getInstance()->criarSequencialNativa('seq_md_lit_cancela_lancamento', 1);
            }

            //-- ===============================================================
            //-- Table: md_lit_rel_decis_lancament
            //-- ===============================================================
            BancoSEI::getInstance()->executarSql('CREATE TABLE md_lit_rel_decis_lancament (
                    id_md_lit_decisao ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL,
                    id_md_lit_lancamento ' . $objInfraMetaBD->tipoNumero() . ' NOT NULL
                    ) ');

            //PK
            $objInfraMetaBD->adicionarChavePrimaria('md_lit_rel_decis_lancament', 'pk1_md_lit_rel_decis_lancament', array('id_md_lit_decisao', 'id_md_lit_lancamento'));

            //FK
            $objInfraMetaBD->adicionarChaveEstrangeira('fk1_md_lit_rel_decis_lancament', 'md_lit_rel_decis_lancament', array('id_md_lit_decisao'), 'md_lit_decisao', array('id_md_lit_decisao'));
            $objInfraMetaBD->adicionarChaveEstrangeira('fk2_md_lit_rel_decis_lancament', 'md_lit_rel_decis_lancament', array('id_md_lit_lancamento'), 'md_lit_lancamento', array('id_md_lit_lancamento'));

            // FIM EU9382

            //===========================================
            //INICIO - Cadastrar "cronjob" para Atualizar os lançamentos com o sistema de Arrecadação
            //===========================================

            $infraAgendamentoDTO = new InfraAgendamentoTarefaDTO();
            $infraAgendamentoDTO->retTodos();
            $infraAgendamentoDTO->setStrDescricao('Script para Atualizar os lançamentos com o sistema de Arrecadao');

            $infraAgendamentoDTO->setStrComando('MdLitAgendamentoAutomaticoRN::consultarLancamento');

            $infraAgendamentoDTO->setStrSinAtivo('S');
            $infraAgendamentoDTO->setStrStaPeriodicidadeExecucao( InfraAgendamentoTarefaRN::$PERIODICIDADEEXECUCAO_DIA );
            $infraAgendamentoDTO->setStrPeriodicidadeComplemento( 4 );
            $infraAgendamentoDTO->setStrParametro( null );
            $infraAgendamentoDTO->setDthUltimaExecucao( null );
            $infraAgendamentoDTO->setDthUltimaConclusao( null );
            $infraAgendamentoDTO->setStrSinSucesso( 'S' );
            $infraAgendamentoDTO->setStrEmailErro( null );

            $infraAgendamentoRN = new InfraAgendamentoTarefaRN();
            $infraAgendamentoDTO = $infraAgendamentoRN->cadastrar( $infraAgendamentoDTO );

            //===========================================
            //FIM - Cadastrar "cronjob" para Atualizar os lanamentos com o sistema de Arrecadao
            //===========================================

            //===========================================
            //INICIO - INICIANDO CADASTRO DE TIPO DE CONTROLE TESTE COM A PARAMETRIZAÇÃO DE SITUAÇÕES
            //===========================================
            $this->logar(' INICIANDO CADASTRO DE TIPO DE CONTROLE TESTE COM A PARAMETRIZAÇÃO DE SITUAÇÕES');

            $objMdLitTipoControleRN = new MdLitTipoControleRN();
            $objMdLitTipoControleRN->cadastrarExemplo();



            //===========================================
            //FIM - INICIANDO CADASTRO DE TIPO DE CONTROLE TESTE COM A PARAMETRIZAÇÃO DE SITUAÇÕES
            //===========================================

            //============================== atualizando parametro para controlar versao do modulo
            BancoSEI::getInstance()->executarSql('update infra_parametro SET valor = \'' . $this->versaoAtualDesteModulo . '\' WHERE nome = \'' . $this->nomeParametroModulo . '\' ');
            $this->logar(' EXECUTADA A INSTALAÇÂO DA VERSAO 0.0.4 DO MODULO ' . $this->nomeDesteModulo . ' NO SEI COM SUCESSO');

        }
    }
