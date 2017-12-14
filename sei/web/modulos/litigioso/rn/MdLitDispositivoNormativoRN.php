<?php
    /**
     * ANATEL
     *
     * 16/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */
    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitDispositivoNormativoRN extends InfraRN
    {

        public function __construct()
        {
            parent::__construct();
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function cadastrarControlado(MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO)
        {
            try {
                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_cadastrar', __METHOD__, $objDispositivoNormativoLitigiosoDTO);

                // Regras de Negocio
                $objInfraException = new InfraException();

                $this->_validacoes($objDispositivoNormativoLitigiosoDTO, $objInfraException);

                $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD($this->getObjInfraIBanco());

                $objDispositivoNormativoLitigiosoDTO->setStrNorma(trim($objDispositivoNormativoLitigiosoDTO->getStrNorma()));
                $objDispositivoNormativoLitigiosoDTO->setStrSinAtivo('S');

                $objRetorno = $objDispositivoNormativoLitigiosoBD->cadastrar($objDispositivoNormativoLitigiosoDTO);

                //Settar Relacionamentos
                $this->_salvarRelacionamentos($objRetorno);


                return $objRetorno;
            } catch (Exception $e) {
                throw new InfraException ('Erro cadastrando Dispositivo Normativo Litigiosa.', $e);
            }
        }

        /**
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param  $objDispositivoNormativoLitigiosoDTO
         * @param  $objInfraException
         * @return void
         */
        private function _validacoes($objDispositivoNormativoLitigiosoDTO, $objInfraException)
        {
            $this->_validarStrNorma($objDispositivoNormativoLitigiosoDTO, $objInfraException);
            $this->_validarStrURL($objDispositivoNormativoLitigiosoDTO, $objInfraException);
            $this->_validarStrDispositivo($objDispositivoNormativoLitigiosoDTO, $objInfraException);
            $this->_validarStrDescricaoDispositivo($objDispositivoNormativoLitigiosoDTO, $objInfraException);
            //$this->_validarArrObjRelDispositivoNormativoTipoControle($objDispositivoNormativoLitigiosoDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $this->_validarDuplicacao($objDispositivoNormativoLitigiosoDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

        }

        /**
         * Validate field "Norma - Dispositivo Normativo".
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO
         * @param InfraException               $objInfraException
         */
        private function _validarStrNorma(MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO, InfraException $objInfraException)
        {

            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (InfraString::isBolVazia($objDispositivoNormativoLitigiosoDTO->getStrNorma())) {
                $objInfraException->adicionarValidacao('Norma não informada.');
            }

            $objDispositivoNormativoLitigiosoDTO2 = new MdLitDispositivoNormativoDTO ();
            $nomeDispositivo                      = trim($objDispositivoNormativoLitigiosoDTO->getStrNorma());
            $objDispositivoNormativoLitigiosoDTO2->setStrNorma($nomeDispositivo);

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_consultar', __METHOD__, $objDispositivoNormativoLitigiosoDTO2);

            // Valida Quantidade de Caracteres
            if (strlen($objDispositivoNormativoLitigiosoDTO->getStrNorma()) > 150) {
                $objInfraException->adicionarValidacao('Norma possui tamanho superior a 150 caracteres.');
            }


        }

        /**
         * Validate field "Url - Dispositivo Normativo".
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO
         * @param InfraException               $objInfraException
         */
        private function _validarStrURL(MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO, InfraException $objInfraException)
        {

            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (!(InfraString::isBolVazia($objDispositivoNormativoLitigiosoDTO->getStrUrl()))) {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_consultar', __METHOD__, $objDispositivoNormativoLitigiosoDTO);

                // Valida Quantidade de Caracteres
                if (strlen($objDispositivoNormativoLitigiosoDTO->getStrUrl()) > 2083) {
                    $objInfraException->adicionarValidacao('Url possui tamanho superior a 2083 caracteres.');
                }

                $url = $objDispositivoNormativoLitigiosoDTO->getStrUrl();

                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    $objInfraException->adicionarValidacao('URL da Norma inválido.');
                }


            }
        }

        /**
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO
         * @param InfraException               $objInfraException
         */
        private function _validarStrDispositivo(MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO, InfraException $objInfraException)
        {

            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (InfraString::isBolVazia($objDispositivoNormativoLitigiosoDTO->getStrDispositivo())) {
                $objInfraException->adicionarValidacao('Dispositivo não informado.');
            }

            $objDispositivoNormativoLitigiosoDTO2 = new MdLitDispositivoNormativoDTO ();
            $nomeDispositivo                      = trim($objDispositivoNormativoLitigiosoDTO->getStrDispositivo());
            $objDispositivoNormativoLitigiosoDTO2->setStrDispositivo($nomeDispositivo);

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_consultar', __METHOD__, $objDispositivoNormativoLitigiosoDTO2);

            // Valida Quantidade de Caracteres
            if (strlen($objDispositivoNormativoLitigiosoDTO->getStrDispositivo()) > 100) {
                $objInfraException->adicionarValidacao('Dispositivo possui tamanho superior a 100 caracteres.');
            }


        }

        /**
         * Validate field "Descrição".
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $objDispositivoNormativoLitigiosoDTO
         * @param $objInfraException
         */
        private function _validarStrDescricaoDispositivo($objDispositivoNormativoLitigiosoDTO, $objInfraException)
        {
            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (InfraString::isBolVazia($objDispositivoNormativoLitigiosoDTO->getStrDescricao())) {
                $objInfraException->adicionarValidacao('Descrição do Dispositivo não informada.');
            }
            if (trim($objDispositivoNormativoLitigiosoDTO->getStrDescricao()) != '') {
                if (strlen($objDispositivoNormativoLitigiosoDTO->getStrDescricao()) > 500) {
                    $objInfraException->adicionarValidacao('Descrição do Dispositivo possui tamanho superior a 500 caracteres.');
                }
            }
        }

        /**
         * Validate field "Descrição".
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $objDispositivoNormativoLitigiosoDTO
         * @param $objInfraException
         */
        private function _validarArrObjRelDispositivoNormativoTipoControle($objDispositivoNormativoLitigiosoDTO, $objInfraException)
        {
            // VERIFICA SE O CAMPO FOI PREENCHIDO

            if (count($objDispositivoNormativoLitigiosoDTO->getArrObjRelDispositivoNormativoTipoControleDTO()) < 1) {
                $objInfraException->adicionarValidacao('Tipo de Controle não informado.');
            }

        }

        /**
         * Short description of method _validarDuplicacao
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param  $objDispositivoNormativoLitigiosoDTO
         * @param  $objInfraException
         * @return void
         */
        private function _validarDuplicacao($objDispositivoNormativoLitigiosoDTO, $objInfraException)
        {
            // VALIDA DUPLICAÇÃO
            // VALIDACAO A SER EXECUTADA NA INSERÇAO DE NOVOS REGISTROS
            $objDispositivoNormativoLitigiosoBD   = new MdLitDispositivoNormativoBD ($this->getObjInfraIBanco());
            $objDispositivoNormativoLitigiosoDTO2 = new MdLitDispositivoNormativoDTO ();
            $objDispositivoNormativoLitigiosoDTO2->setStrNorma(trim($objDispositivoNormativoLitigiosoDTO->getStrNorma()));
            $objDispositivoNormativoLitigiosoDTO2->setStrDispositivo(trim($objDispositivoNormativoLitigiosoDTO->getStrDispositivo()));

            if (!is_numeric($objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso())) {

                $ret = $objDispositivoNormativoLitigiosoBD->contar($objDispositivoNormativoLitigiosoDTO2);

                if ($ret > 0) {
                    $objInfraException->adicionarValidacao('Já existe Dispositivo Normativo cadastrado.');
                } // VALIDACAO A SER EXECUTADA QUANDO É FEITO UPDATE DE REGISTROS
            } else {
                //Obs: utilizado o objeto 2 no where pois está com o trim e utilizado o objeto padrão no ID.
                $dtoValidacao = new MdLitDispositivoNormativoDTO();
                $dtoValidacao->setStrNorma($objDispositivoNormativoLitigiosoDTO2->getStrNorma(), InfraDTO::$OPER_IGUAL);
                $dtoValidacao->setStrDispositivo($objDispositivoNormativoLitigiosoDTO2->getStrDispositivo(), InfraDTO::$OPER_IGUAL);
                $dtoValidacao->setNumIdDispositivoNormativoLitigioso($objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso(), InfraDTO::$OPER_DIFERENTE);

                $retTotal = $objDispositivoNormativoLitigiosoBD->contar($dtoValidacao);

                if ($retTotal) {
                    $objInfraException->adicionarValidacao('Já existe Dispositivo Normativo cadastrada.');
                }
            }
        }

        private function _salvarRelacionamentos($objRetorno)
        {
            //Conduta
            $arrCondutas = $objRetorno->getArrObjRelDispositivoNormativoCondutaDTO();

            $objRelDispositivoNormativoCondutaRN = new MdLitRelDispositivoNormativoCondutaRN();

            foreach ($arrCondutas as $objRelDispositivoNormativoCondutaDTO) {
                $objRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($objRetorno->getNumIdDispositivoNormativoLitigioso());
                $objRelDispositivoNormativoCondutaRN->cadastrar($objRelDispositivoNormativoCondutaDTO);
            }

            //Tipo Controle
            $arrTipoControle                          = $objRetorno->getArrObjRelDispositivoNormativoTipoControleDTO();
            $objRelDispositivoNormativoTipoControleRN = new MdLitRelDispositivoNormativoTipoControleRN();

            foreach ($arrTipoControle as $objRelDispositivoNormativoTipoControleDTO) {
                $objRelDispositivoNormativoTipoControleDTO->setNumIdDispositivoNormativo($objRetorno->getNumIdDispositivoNormativoLitigioso());
                $objRelDispositivoNormativoTipoControleRN->cadastrar($objRelDispositivoNormativoTipoControleDTO);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjDispositivoNormativoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function excluirControlado($arrObjDispositivoNormativoLitigiosoDTO)
        {
            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_excluir', __METHOD__, $arrObjDispositivoNormativoLitigiosoDTO);

                if (count($arrObjDispositivoNormativoLitigiosoDTO) > 0) {
                    $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjDispositivoNormativoLitigiosoDTO); $i++) {
                        $objDispositivoNormativoLitigiosoBD->excluir($arrObjDispositivoNormativoLitigiosoDTO[$i]);
                    }
                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Dispositivo Normativo Litigiosa.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $objDispositivoNormativoLitigiosoDTO
         * @throws InfraException
         */
        protected function alterarControlado($objDispositivoNormativoLitigiosoDTO)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_alterar', __METHOD__, $objDispositivoNormativoLitigiosoDTO);


                // Regras de Negocio
                $objInfraException = new InfraException ();

                $this->_validacoes($objDispositivoNormativoLitigiosoDTO, $objInfraException);

                $objDispositivoNormativoLitigiosoDTO->setStrNorma(trim($objDispositivoNormativoLitigiosoDTO->getStrNorma()));

                $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD ($this->getObjInfraIBanco());
                $objDispositivoNormativoLitigiosoBD->alterar($objDispositivoNormativoLitigiosoDTO);

                $this->_salvarRelacionamentos($objDispositivoNormativoLitigiosoDTO);

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro alterando Dispositivo Normativo Litigiosa.', $e);
            }
        }

        /**
         * @access   protected
         * @author   Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjDispositivoNormativoLitigiosoDTO
         * @throws InfraException
         */
        protected function reativarControlado($arrObjDispositivoNormativoLitigiosoDTO)
        {
            try {
                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_reativar');

                if (count($arrObjDispositivoNormativoLitigiosoDTO) > 0) {
                    $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjDispositivoNormativoLitigiosoDTO); $i++) {
                        $objDispositivoNormativoLitigiosoBD->reativar($arrObjDispositivoNormativoLitigiosoDTO[$i]);
                    }
                }
            } catch (Exception $e) {
                throw new InfraException('Erro reativando Conduta Litigiosa.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $objDispositivoNormativoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function consultarConectado($objDispositivoNormativoLitigiosoDTO)
        {
            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_consultar', __METHOD__, $objDispositivoNormativoLitigiosoDTO);

                $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD($this->getObjInfraIBanco());
                $ret                                = $objDispositivoNormativoLitigiosoBD->consultar($objDispositivoNormativoLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Dispositivo Normativo Litigiosa.', $e);
            }
        }

        /**
         * Consulta de vários registros
         * @access   protected
         * @author   CAST
         * @param $objDispositivoNormativoLitigiosoDTO
         * @return array
         * @throws InfraException
         * @internal param $objControleLitigiosoDTO
         */
        protected function listarPadraoConectado($objDispositivoNormativoLitigiosoDTO)
        {
            try {

                $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD($this->getObjInfraIBanco());
                $ret                                = $objDispositivoNormativoLitigiosoBD->listar($objDispositivoNormativoLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Dispositivo Normativo.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         */
        protected function listarConectado(MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO)
        {

            try {

                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_listar', __METHOD__, $objDispositivoNormativoLitigiosoDTO);

                if ($objDispositivoNormativoLitigiosoDTO->getIdTipoControleLitigiosoFiltro() == ""
                    && $objDispositivoNormativoLitigiosoDTO->getStrCondutaFiltro() == ""
                    && $objDispositivoNormativoLitigiosoDTO->getStrNorma() == ""
                    && $objDispositivoNormativoLitigiosoDTO->getStrDispositivo() == ""
                    && $objDispositivoNormativoLitigiosoDTO->getIdCondutaFiltro() == ""
                ) {
                    //sem os parametros conduta e tipo de controle litigioso, pode usar consulta simples sem SQL
                    $objDispositivoNormativoLitigiosoDTO = new MdLitDispositivoNormativoDTO();
                    $objDispositivoNormativoLitigiosoDTO->retTodos();
                    $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD($this->getObjInfraIBanco());
                    $ret                                = $objDispositivoNormativoLitigiosoBD->listar($objDispositivoNormativoLitigiosoDTO);

                    return $ret;

                } else {

                    $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD($this->getObjInfraIBanco());
                    $dtoValidacao                       = new MdLitDispositivoNormativoDTO();
                    $dtoValidacao->retTodos();

                    //CAMPO NORMA
                    if ($objDispositivoNormativoLitigiosoDTO->get('Norma') != "") {
                        $dtoValidacao->setStrNorma('%' . $objDispositivoNormativoLitigiosoDTO->getStrNorma() . '%', InfraDTO::$OPER_LIKE);
                    }

                    //CAMPO DISPOSITIVO
                    if ($objDispositivoNormativoLitigiosoDTO->get("Dispositivo") != "") {
                        $dtoValidacao->setStrDispositivo('%' . $objDispositivoNormativoLitigiosoDTO->getStrDispositivo() . '%', InfraDTO::$OPER_LIKE);
                    }

                    //CAMPO TIPO DE CONTROLE
                    //filtrando por tipo de controle md_lit_rel_disp_norm_tipo_ctrl ( id_md_lit_disp_normat e id_md_lit_tipo_cont )
                    if ($objDispositivoNormativoLitigiosoDTO->getIdTipoControleLitigiosoFiltro() != "") {

                        $dtoRelDispNormTipoControle = new MdLitRelDispositivoNormativoTipoControleDTO();
                        $dtoRelDispNormTipoControle->setNumIdTipoControle($objDispositivoNormativoLitigiosoDTO->getIdTipoControleLitigiosoFiltro(),
                                                                          InfraDTO::$OPER_IGUAL);
                        $dtoRelDispNormTipoControle->retTodos();
                        $arrIdDispNormativo         = array();
                        $arrRelDispNormTipoControle = $objDispositivoNormativoLitigiosoBD->listar($dtoRelDispNormTipoControle);

                        foreach ($arrRelDispNormTipoControle as $itemDispNormTipoControle) {
                            array_push($arrIdDispNormativo, $itemDispNormTipoControle->getNumIdDispositivoNormativo());
                        }

                        if (count($arrIdDispNormativo) > 0) {
                            $dtoValidacao->adicionarCriterio(array('IdDispositivoNormativoLitigioso'), array(InfraDTO::$OPER_IN), array($arrIdDispNormativo));
                        } else {
                            //se o registro selecionado na combo nao tiver vinculo algum , é porque vai retornar a lista vazia
                            return array();
                        }

                    }

                    //CAMPO CONDUTA
                    //filtrando por conduta md_lit_rel_disp_norm_conduta ( id_md_lit_disp_normat e id_md_lit_conduta)
                    if ($objDispositivoNormativoLitigiosoDTO->getStrCondutaFiltro() != "") {
                        $dtoRelDispConduta = new MdLitRelDispositivoNormativoCondutaDTO();
                        $dtoRelDispConduta->setStrNomeConduta('%' . $objDispositivoNormativoLitigiosoDTO->getStrCondutaFiltro() . '%', InfraDTO::$OPER_LIKE);

                        $dtoRelDispConduta->retTodos();
                        $arrRelDispNormConduta = $objDispositivoNormativoLitigiosoBD->listar($dtoRelDispConduta);

                        $arrIdDispNormativoConduta = array();
                        foreach ($arrRelDispNormConduta as $itemDispNormConduta) {
                            array_push($arrIdDispNormativoConduta, $itemDispNormConduta->getNumIdDispositivoNormativo());
                        }

                        if (count($arrIdDispNormativoConduta) > 0) {
                            $dtoValidacao->adicionarCriterio(array('IdDispositivoNormativoLitigioso'), array(InfraDTO::$OPER_IN), array($arrIdDispNormativoConduta));
                        } else {
                            $dtoValidacao->setNumIdDispositivoNormativoLitigioso(0);
                        }
                    }

                    //CAMPO CONDUTA VINDO DE OUTRA TELA
                    //filtrando por conduta md_lit_rel_disp_norm_conduta ( id_md_lit_disp_normat e id_md_lit_conduta)
                    if ($objDispositivoNormativoLitigiosoDTO->getIdCondutaFiltro() != "") {
                        $dtoRelDispConduta = new MdLitRelDispositivoNormativoCondutaDTO();
                        $dtoRelDispConduta->setNumIdConduta($objDispositivoNormativoLitigiosoDTO->getIdCondutaFiltro());
                        $dtoRelDispConduta->retTodos();
                        $arrRelDispNormConduta      = $objDispositivoNormativoLitigiosoBD->listar($dtoRelDispConduta);
                        $arrIdDispNormativoConduta2 = array();
                        foreach ($arrRelDispNormConduta as $itemDispNormConduta) {
                            array_push($arrIdDispNormativoConduta2, $itemDispNormConduta->getNumIdDispositivoNormativo());
                        }
                        if (count($arrIdDispNormativoConduta2) > 0) {
                            $dtoValidacao->adicionarCriterio(array('IdDispositivoNormativoLitigioso'), array(InfraDTO::$OPER_IN), array($arrIdDispNormativoConduta2));
                        } else {
                            $dtoValidacao->setNumIdDispositivoNormativoLitigioso(0);
                        }
                    }

                    $resultCheck = $objDispositivoNormativoLitigiosoBD->listar($dtoValidacao);

                    return $resultCheck;
                }


            } catch (Exception $e) {
                throw new InfraException ('Erro listando Dispositivo Normativo Litigiosos.', $e);
            }
        }

        /**
         * @access protected
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjDispositivoNormativoLitigiosoDTO
         * @throws InfraException
         */
        protected function desativarControlado($arrObjDispositivoNormativoLitigiosoDTO)
        {

            try {

                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_dispositivo_normativo_desativar');

                if (count($arrObjDispositivoNormativoLitigiosoDTO) > 0) {
                    $objDispositivoNormativoLitigiosoBD = new MdLitDispositivoNormativoBD ($this->getObjInfraIBanco());
                    for ($i = 0; $i < count($arrObjDispositivoNormativoLitigiosoDTO); $i++) {
                        $objDispositivoNormativoLitigiosoBD->desativar($arrObjDispositivoNormativoLitigiosoDTO[$i]);
                    }
                }

            } catch (Exception $e) {
                throw new InfraException ('Erro desativando Conduta Litigiosa.', $e);
            }
        }

        protected function removerRelacionamentosControlado(MdLitDispositivoNormativoDTO $objDispositivoNormativoLitigiosoDTO)
        {

            try {

                //Delete Conduta
                $idDispositivo         = $objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso();
                $bdDispositivoConduta  = new MdLitRelDispositivoNormativoCondutaBD($this->getObjInfraIBanco());
                $dtoDispositivoConduta = new MdLitRelDispositivoNormativoCondutaDTO();
                $dtoDispositivoConduta->retTodos();
                $dtoDispositivoConduta->setNumIdDispositivoNormativo($idDispositivo, InfraDTO::$OPER_IGUAL);
                $arrDispConduta = $bdDispositivoConduta->listar($dtoDispositivoConduta);

                foreach ($arrDispConduta as $DispConduta) {
                    $bdDispositivoConduta->excluir($DispConduta);
                }

                //delete tipo de controle
                $dtoDispositivoTipoControle = new MdLitRelDispositivoNormativoTipoControleDTO();
                $bdDispositivoTipoControle  = new MdLitRelDispositivoNormativoTipoControleBD($this->getObjInfraIBanco());
                $dtoDispositivoTipoControle->retTodos();
                $dtoDispositivoTipoControle->setNumIdDispositivoNormativo($idDispositivo, InfraDTO::$OPER_IGUAL);
                $arrDispTipoControle = $bdDispositivoTipoControle->listar($dtoDispositivoTipoControle);

                if ($arrDispTipoControle != null && count($arrDispTipoControle) > 0) {

                    for ($i = 0; $i < count($arrDispTipoControle); $i++) {
                        $bdDispositivoTipoControle->excluir($arrDispTipoControle[$i]);
                    }
                }


            } catch (Exception $e) {
                throw new InfraException('Erro alterando Dispositivos Normativos Litigiosos.', $e);
            }

        }


        //REMOVE TODOS OS RELACIONAMENTOS DE UM DISPOSITIVO NORMATIVO
        //USADO NA EDIÇÃO DE DISPOSITIVO NORMATIVO, APAGA TUDO PARA DEPOIS REINSERIR

        /**
         * Validate "Dispositivo Normativo" is associated with "Situação"
         *
         * @param $objDispositivoNormativoLitigiosoDTO            MdLitDispositivoNormativoDTO
         * @param $arrObjDispositivoNormativoLitigiosoDTOValidado Array
         * @param $validado                                       Boolean
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @return void
         */
        private function _validarCadastroAssociado($objDispositivoNormativoLitigiosoDTO, &$arrObjDispositivoNormativoLitigiosoDTOValidado, &$validado)
        {
            $objSituacaoLitigiosoBD = new MdLitSituacaoBD ($this->getObjInfraIBanco());
            $idDispositivoNormativo = $objDispositivoNormativoLitigiosoDTO->getNumIdDispositivoNormativoLitigioso();

            $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
            $objSituacaoLitigiosoDTO->setNumIdDispositivoNormativoLitigioso($idDispositivoNormativo);

            $countSitDispositivoNormativo = $objSituacaoLitigiosoBD->contar($objSituacaoLitigiosoDTO);

            if ($countSitDispositivoNormativo > 0) {
                $validado = false;
            } else {
                array_push($arrObjDispositivoNormativoLitigiosoDTOValidado, $objDispositivoNormativoLitigiosoDTO);
            }

        }

    }
