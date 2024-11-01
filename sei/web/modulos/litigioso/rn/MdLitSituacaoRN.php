<?
    /**
     * ANATEL
     *
     * 05/02/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitSituacaoRN extends InfraRN
    {
        public static $DIAS_CORRIDOS = 'C';
        public static $DIAS_UTEIS = 'U';

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Short description of method cadastrarControlado
         *
         * @access protected
         * @author firstname and lastname of author, <author@example.org>
         * @param $arrParams['objSituacaoLitigiosoDTO']
         * @param $arrParams['idTipoControle']
         * @return mixed
         * @throws InfraException
         */
        public function cadastrarComTipoControleControlado($arrParams)
        {
            try {
                $objDTO = $arrParams['objSituacaoLitigiosoDTO'];
                $idTipoControle = $arrParams['idTipoControle'];

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_cadastrar', __METHOD__, $objDTO);

                $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
                //$idTipoControle = $objDTO->getNumIdTipoControleLitigioso();
                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm       = $permissaoRN->isAdm();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);
                $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                $objRetorno = null;


                if ($isAdm || $isGestor) {

                    // Regras de Negocio
                    $objInfraException = new InfraException();
                    $this->_validarStrFase($objDTO, $objInfraException);
                    $valido = $this->_validarStrNomeSituacao($objDTO, $objInfraException);

                    $objInfraException->lancarValidacoes();

                    $objBD = new MdLitSituacaoBD($this->getObjInfraIBanco());
                    $objDTO->setStrSinAtivo('S');
                    $objRetorno = $objBD->cadastrar($objDTO);

                    $arrTiposDocumentos = $objRetorno->getArrObjRelSituacaoLitigiosoSerieDTO();

                    $objRelSituacaoLitigiosoSerieRN = new MdLitRelSituacaoSerieRN();

                    //salvar as series
                    foreach ($arrTiposDocumentos as $objTipoDocDTO) {
                        $objTipoDocDTO->setNumIdSituacaoLitigioso($objRetorno->getNumIdSituacaoLitigioso());
                        $objRelSituacaoLitigiosoSerieRN->cadastrar($objTipoDocDTO);
                    }

                }

                return $objRetorno;

            } catch (Exception $e) {
                throw new InfraException ('Erro cadastrando Situação Litigiosa.', $e);
            }

        }

        private function _validarStrFase($objSituacaoLitigiosoDTO, $objInfraException)
        {
            // VERIFICA SE O CAMPO FOI PREENCHIDO
            $valido = true;
            if (is_null($objSituacaoLitigiosoDTO->getNumIdFaseLitigioso())) {
                $valido = false;
                $objInfraException->adicionarValidacao('Fase não informada.');
            }

            return $valido;
        }

        /**
         * Validate field "Nome da Situação".
         *
         * @access private
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @return void
         */
        private function _validarStrNomeSituacao(MdLitSituacaoDTO $objSituacaoLitigiosoDTO, InfraException $objInfraException)
        {

            $valido = true;
            // VERIFICA SE O CAMPO FOI PREENCHIDO
            if (InfraString::isBolVazia($objSituacaoLitigiosoDTO->getStrNome())) {
                $valido = false;
                $objInfraException->adicionarValidacao('Nome da Situação não informada.');
            }

            $objSituacaoLitigiosoDTO2 = new MdLitSituacaoDTO ();
            $nomeSituacao             = trim($objSituacaoLitigiosoDTO->getStrNome());
            $objSituacaoLitigiosoDTO2->setStrNome($nomeSituacao);
            $objSituacaoLitigiosoDTO2->retNumIdSituacaoLitigioso();


            $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());

            // Valida Quantidade de Caracteres
            if (strlen($objSituacaoLitigiosoDTO2->getStrNome()) > 100) {
                $valido = false;
                $objInfraException->adicionarValidacao('Nome da Situação possui tamanho superior a 100 caracteres.');
            }

            // VALIDA DUPLICAÇÃO
            // VALIDACAO A SER EXECUTADA NA INSERÇAO DE NOVOS REGISTROS

            if ($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso() == null || !is_numeric($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso())) {

                $objSituacaoLitigiosoDTO2->setNumIdFaseLitigioso($objSituacaoLitigiosoDTO->getNumIdFaseLitigioso());
                $objSituacaoLitigiosoDTO2->setNumIdTipoControleLitigioso($objSituacaoLitigiosoDTO->getNumIdTipoControleLitigioso());
                $ret = $objSituacaoLitigiosoBD->contar($objSituacaoLitigiosoDTO2);

                if ($ret > 0) {
                    $valido = false;
                    $objInfraException->adicionarValidacao('Já existe Situação para o presente Controle Litigioso com o mesmo Nome.');
                } // VALIDACAO A SER EXECUTADA QUANDO É FEITO UPDATE DE REGISTROS
            } else {
                $objSituacaoLitigiosoDTO2->setNumIdTipoControleLitigioso($objSituacaoLitigiosoDTO->getNumIdTipoControleLitigioso());
                $objSituacaoLitigiosoDTO2->setNumIdSituacaoLitigioso($objSituacaoLitigiosoDTO->getNumIdSituacaoLitigioso(), InfraDTO::$OPER_DIFERENTE);
                $objSituacaoLitigiosoDTO2->setNumIdFaseLitigioso($objSituacaoLitigiosoDTO->getNumIdFaseLitigioso());

                $qtd = $this->contar($objSituacaoLitigiosoDTO2);

                if ($qtd > 0) {
                    $objInfraException->adicionarValidacao('Já existe Situação para o presente Controle Litigioso com o mesmo Nome.');
                }
            }

            return $valido;
        }

        /**
         * Short description of method parametrizar
         *
         * @access   protected
         * @author   firstname and lastname of author, <author@example.org>
         * @param $arrObjSituacaoLitigiosoDTOSalvar
         * @param $idTipoControle
         * @return mixed
         * @throws InfraException
         * @internal param $objDTO
         */
        public function parametrizarComTipoDeControle($arrObjSituacaoLitigiosoDTOSalvar, $idTipoControle)

        {
            try {
                // Regras de Negocio
                $idUsuario   = SessaoSEI::getInstance()->getNumIdUsuario();
                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm       = $permissaoRN->isAdm();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);
                $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                if (count($arrObjSituacaoLitigiosoDTOSalvar) > 0 && ($isAdm || $isGestor)) {
                    foreach ($arrObjSituacaoLitigiosoDTOSalvar as $objSituacaoLitigiosoDTO) {
                        $objSituacaoLitigiosoBD = new MdLitSituacaoBD ($this->getObjInfraIBanco());
                        $objSituacaoLitigiosoBD->alterar($objSituacaoLitigiosoDTO);
                    }
                }
            } catch (Exception $e) {
                throw new InfraException ('Erro alterando Fase Litigiosa.', $e);
            }
        }

        /**
         * Short description of method excluirControlado
         *
         * @access public
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjSituacaoLitigiosoDTO
         * @param $idTipoControle
         * @return mixed
         * @throws InfraException
         */
        public function excluirComTipoControle($arrObjSituacaoLitigiosoDTO, $idTipoControle)
        {

            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_excluir', __METHOD__, $arrObjSituacaoLitigiosoDTO);

                if (is_array($arrObjSituacaoLitigiosoDTO) && count($arrObjSituacaoLitigiosoDTO) > 0) {

                    $objSituacaoLitigiosoBD = new MdLitSituacaoBD ($this->getObjInfraIBanco());

                    $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                    $objSituacaoLitigiosoDTO->retTodos();
                    $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($arrObjSituacaoLitigiosoDTO[0]->getNumIdSituacaoLitigioso());
                    $objSituacaoLitigiosoDTO = $this->consultar($objSituacaoLitigiosoDTO);

                    // Regras de Negocio
                    $idUsuario   = SessaoSEI::getInstance()->getNumIdUsuario();
                    $permissaoRN = new MdLitPermissaoLitigiosoRN();
                    $isAdm       = $permissaoRN->isAdm();

                    $arrParam = array();
                    array_push($arrParam, $idUsuario);
                    array_push($arrParam, $idTipoControle);
                    $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                    $situacaoSerieRN = new MdLitRelSituacaoSerieRN();

                    if (count($arrObjSituacaoLitigiosoDTO) > 0 && ($isAdm || $isGestor)) {

                        for ($i = 0; $i < count($arrObjSituacaoLitigiosoDTO); $i++) {

                            $objSituacaoLitigiosoSerieDTO = new MdLitRelSituacaoSerieDTO();
                            $objSituacaoLitigiosoSerieDTO->retTodos();
                            $objSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($arrObjSituacaoLitigiosoDTO[$i]->getNumIdSituacaoLitigioso());

                            //consultar os relacionamentos situacao -> serie
                            $arrSituacaoSerieDTO = $situacaoSerieRN->listar($objSituacaoLitigiosoSerieDTO);

                            //antes de excluir a situaçao, manda excluir as series vinculadas
                            $situacaoSerieRN->excluir($arrSituacaoSerieDTO);
                            $objSituacaoLitigiosoBD->excluir($arrObjSituacaoLitigiosoDTO[$i]);
                        }

                    }

                }

                // Auditoria

            } catch (Exception $e) {
                throw new InfraException ('Erro excluindo Situação Litigiosa.', $e);
            }
        }

        /**
         * Short description of method alterarComTipoDeControle
         *
         * @access public
         * @author firstname and lastname of author, <author@example.org>
         * @param $arrParams['objSituacaoLitigiosoDTO']
         * @param $arrParams['idTipoControle']
         * @return mixed
         * @throws InfraException
         */
        public function alterarComTipoControleControlado($arrParams)
        {

            try {
                $objDTO             = $arrParams['objSituacaoLitigiosoDTO'];
                $idTipoControle     = $arrParams['idTipoControle'];

                //Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_alterar', __METHOD__, $objDTO);

                //so pode executar se for administrador ou gestor do tipo de controle
                $idUsuario   = SessaoSEI::getInstance()->getNumIdUsuario();
                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm       = $permissaoRN->isAdm();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);
                $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                if ($isAdm || $isGestor) {

                    //Regras de Negocio
                    $objInfraException = new InfraException();
                    $this->_validarStrFase($objDTO, $objInfraException);
                    $this->_validarStrNomeSituacao($objDTO, $objInfraException);

                    $objInfraException->lancarValidacoes();

                    $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());
                    $objSituacaoLitigiosoBD->alterar($objDTO);
                    $arrObj = $objDTO->getArrObjRelSituacaoLitigiosoSerieDTO();
                    $relRN  = new MdLitRelSituacaoSerieRN();

                    //readicionando os relaciomentos
                    for ($x = 0; $x < count($arrObj); $x++) {
                        $objRelSituacaoLitigiosoSerieDTO = $arrObj[$x];
                        $objRelSituacaoLitigiosoSerieDTO->setNumIdSituacaoLitigioso($objDTO->getNumIdSituacaoLitigioso());
                        $relRN->cadastrar($objRelSituacaoLitigiosoSerieDTO);
                    }

                }

            } catch (Exception $e) {
                throw new InfraException('Erro alterando.', $e);
            }

        }

        public function validarCamposConectado($objDTO)
        {
            $valido = true;

            try {
                $objInfraException = new InfraException();

                $valido = $this->_validarStrFase($objDTO, $objInfraException);
                $valido = $this->_validarStrNomeSituacao($objDTO, $objInfraException);

                $objInfraException->lancarValidacoes();

            } catch (Exception $e) {
                throw new InfraException('Erro alterando.', $e);
            }

            return $valido;
        }

        /**
         * Short description of method desativarComTipoControle
         *
         * @access   public
         * @author   Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjSituacaoLitigiosoDTO
         * @param $idTipoControle
         * @throws InfraException
         * @internal param $arrObjFaseLitigiosoDTO
         */
        public function desativarComTipoControle($arrObjSituacaoLitigiosoDTO, $idTipoControle)
        {
            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_desativar');

                // Regras de Negocio
                $idUsuario   = SessaoSEI::getInstance()->getNumIdUsuario();
                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm       = $permissaoRN->isAdm();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);
                $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                if (count($arrObjSituacaoLitigiosoDTO) > 0 && ($isAdm || $isGestor)) {
                    $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());
                    for ($i = 0; $i < count($arrObjSituacaoLitigiosoDTO); $i++) {
                        $objSituacaoLitigiosoBD->desativar($arrObjSituacaoLitigiosoDTO[$i]);
                    }
                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro desativando Situação.', $e);
            }
        }

        /**
         * Short description of method reativarComTipoControle
         *
         * @access public
         * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
         * @param $arrObjSituacaoLitigiosoDTO
         * @param $idTipoControle
         * @throws InfraException
         */
        public function reativarComTipoControle($arrObjSituacaoLitigiosoDTO, $idTipoControle)
        {
            try {

                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_reativar');

                // Regras de Negocio
                $idUsuario   = SessaoSEI::getInstance()->getNumIdUsuario();
                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm       = $permissaoRN->isAdm();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);
                $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());

                if (count($arrObjSituacaoLitigiosoDTO) > 0 && ($isAdm || $isGestor)) {
                    for ($i = 0; $i < count($arrObjSituacaoLitigiosoDTO); $i++) {
                        $objSituacaoLitigiosoBD->reativar($arrObjSituacaoLitigiosoDTO[$i]);
                    }
                }

                // Auditoria
            } catch (Exception $e) {
                throw new InfraException ('Erro reativando Situação Litigiosa.', $e);
            }
        }

        /**
         * Consultar registros com checagem de permissao
         *
         * @access   public
         * @author   firstname and lastname of author, <author@example.org>
         * @param $objSituacaoLitigiosoDTO
         * @param $idTipoControle
         * @return mixed
         * @throws InfraException
         * @internal param $objDTO **
         */
        public function listarComTipoDeControle($objSituacaoLitigiosoDTO, $idTipoControle)

        {

            try {
                // Valida Permissao
                SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_situacao_parametrizar', __METHOD__, $objSituacaoLitigiosoDTO);

                // Regras de Negocio
                $idUsuario   = SessaoSEI::getInstance()->getNumIdUsuario();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);

                $ret      = null;

                //so executa a consulta se o usuario for ou Administrador OU gestor do tipo de controle informado
                $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());
                $ret                    = $objSituacaoLitigiosoBD->listar($objSituacaoLitigiosoDTO);

                return $ret;

            } catch (Exception $e) {
                throw new InfraException ('Erro listando Situação Litigioso.', $e);
            }
        }

        protected function inicializarObjInfraIBanco()
        {
            return BancoSEI::getInstance();
        }


        //REMOVE TODOS OS RELACIONAMENTOS DE UMA SITUACAO
        //USADO NA EDIÇÃO DE SITUACAO, APAGA TUDO PARA DEPOIS REINSERIR

        protected function validar(SituacsoLitigiosoDTO $objSituacsoLitigiosoDTO, InfraException $objInfraException)
        {

        }

        /**
         * Short description of method consultarConectado
         *
         * @access   protected
         * @author   firstname and lastname of author, <author@example.org>
         * @param $objSituacaoLitigiosoDTO
         * @return mixed
         * @throws InfraException
         * @internal param $objDTO
         */
        protected function consultarConectado($objSituacaoLitigiosoDTO)
        {
            try {


                $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());
                $ret                    = $objSituacaoLitigiosoBD->consultar($objSituacaoLitigiosoDTO);

                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro consultando Situação Litigiosa.', $e);
            }
        }


        protected function removerRelacionamentosControlado(MdLitSituacaoDTO $objDTO)
        {

            try {
                $objMdLitRelSituacaoSerieDTO = new MdLitRelSituacaoSerieDTO();
                $objMdLitRelSituacaoSerieDTO->retTodos();
                $objMdLitRelSituacaoSerieDTO->setNumIdSituacaoLitigioso($objDTO->getNumIdSituacaoLitigioso());

                $objMdLitRelSituacaoSerieRN = new MdLitRelSituacaoSerieRN();
                $arrObjMdLitRelSituacaoSerieDTO = $objMdLitRelSituacaoSerieRN->listar($objMdLitRelSituacaoSerieDTO);
                $objMdLitRelSituacaoSerieRN->excluir($arrObjMdLitRelSituacaoSerieDTO);

            } catch (Exception $e) {
                throw new InfraException('Erro removendo relacionamentos.', $e);
            }

        }

        protected function contarConectado(MdLitSituacaoDTO $objDTO)
        {
            try {

                $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());
                $ret                    = $objSituacaoLitigiosoBD->contar($objDTO);
                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro contando situações.', $e);
            }

        }

        protected function listarConectado(MdLitSituacaoDTO $objDTO)
        {
            try {

                $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());
                $ret                    = $objSituacaoLitigiosoBD->listar($objDTO);
                return $ret;
            } catch (Exception $e) {
                throw new InfraException('Erro listando situações.', $e);
            }

        }
        
        protected function getObjSituacaoPorIdConectado($idSituacao){
            try {
                $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
                $objMdLitSituacaoDTO->setNumIdSituacaoLitigioso($idSituacao);
                $objMdLitSituacaoDTO->retTodos();
                $objMdLitSituacaoDTO->ret('NomeFase');
                $objMdLitSituacaoDTO->setNumMaxRegistrosRetorno('1');
                
                return $this->consultar($objMdLitSituacaoDTO);
                
            } catch (Exception $e) {
                throw new InfraException('Erro listando situações.', $e);
            }
        }

        public function retornaArrTiposSituacao($objMdLitSituacaoDTO){
            $dados = array();
            $dados['idSituacao']    = $objMdLitSituacaoDTO->getNumIdSituacaoLitigioso();
            $dados['ordemSituacao'] = $objMdLitSituacaoDTO->getNumOrdem();

            if($objMdLitSituacaoDTO->getStrSinInstauracao() == 'S'){
                $dados['tipoSituacao'] = 'Instauracao';
                $dados['nomeLabel']    = 'da Instauração';
                $dados['nome']         = 'Instauração';
            }

            if($objMdLitSituacaoDTO->getStrSinIntimacao() == 'S'){
                $dados['tipoSituacao'] = 'Intimacao';
                $dados['nomeLabel']    = 'da Intimação';
                $dados['nome']         = 'Intimação';
            }

            if($objMdLitSituacaoDTO->getStrSinDecisoria() == 'S'){
                $dados['tipoSituacao'] = 'Decisoria';
                $dados['nomeLabel']    = 'da Decisão';
                $dados['nome']         = 'Decisória';
            }

            if($objMdLitSituacaoDTO->getStrSinDefesa() == 'S'){
                $dados['tipoSituacao'] = 'Defesa';
                $dados['nomeLabel']    = 'da Defesa';
                $dados['nome']         = 'Defesa';
            }

            if($objMdLitSituacaoDTO->getStrSinRecursal() == 'S'){
                $dados['tipoSituacao'] = 'Recursal';
                $dados['nomeLabel']    = 'do Recurso';
                $dados['nome']         = 'Recursal';
            }

            if($objMdLitSituacaoDTO->getStrSinConclusiva() == 'S'){
                $dados['tipoSituacao'] = 'Conclusiva';
                $dados['nomeLabel']    = 'do Trânsito em Julgado';
                $dados['nome']         = 'Conclusiva';
            }

            if($objMdLitSituacaoDTO->getStrSinObrigatoria() == 'S'){
                $dados['tipoSituacao'] = 'Obrigatoria';
                $dados['nomeLabel']    = 'Obrigatória';
                $dados['nome']         = 'Obrigatória';
            }
            if($objMdLitSituacaoDTO->getStrSinAlegacoes() == 'S'){
                $dados['tipoSituacao'] = 'Alegacoes';
                $dados['nomeLabel']    = 'Alegações';
                $dados['nome']         = 'Intimação para Alegações';
            }
            
            if(!array_key_exists('tipoSituacao', $dados)){
                $dados['tipoSituacao'] = 'Livre';
                $dados['nomeLabel']    = '';
                $dados['nome']         = '';
            }
            
            return $dados;
        }

        public function getArrSituacaoTipoSituacao($arrObjSituacaoProcessoDTO){
            $dados = array();
          if(count($arrObjSituacaoProcessoDTO) > 0){
            $objSituacaoRN = new MdLitSituacaoRN();
            $idsSituacao = InfraArray::converterArrInfraDTO($arrObjSituacaoProcessoDTO, 'IdMdLitSituacao');

            $objSituacaoDTO = new MdLitSituacaoDTO();
            $objSituacaoDTO->setNumIdSituacaoLitigioso($idsSituacao, InfraDTO::$OPER_IN);
            $objSituacaoDTO->retTodos();

            $arrObjSituacaoDTO = $objSituacaoRN->listar($objSituacaoDTO);

            foreach ($arrObjSituacaoDTO as $objDTO){
                $dadosTipoSit = $this->retornaArrTiposSituacao($objDTO);
                $dados[$objDTO->getNumIdSituacaoLitigioso()] = array($dadosTipoSit['nome'], $dadosTipoSit['nomeLabel']);

            }
          }

            return $dados;
        }

        public function verificaSeSituacaoLivre($objMdLitSituacaoDTO){
            $isLivre = true;

            //Instauração
            if($objMdLitSituacaoDTO->getStrSinInstauracao() == 'S'){
                $isLivre = false;
            }

            if($objMdLitSituacaoDTO->getStrSinIntimacao() == 'S'){
                $isLivre = false;
            }

            if($objMdLitSituacaoDTO->getStrSinDecisoria() == 'S'){
                $isLivre = false;
            }

            if($objMdLitSituacaoDTO->getStrSinDefesa() == 'S'){
                $isLivre = false;
            }

            if($objMdLitSituacaoDTO->getStrSinRecursal() == 'S'){
                $isLivre = false;
            }

            if($objMdLitSituacaoDTO->getStrSinConclusiva() == 'S'){
                $isLivre = false;
            }
            if($objMdLitSituacaoDTO->getStrSinAlegacoes() == 'S'){
                $isLivre = false;
            }

            if($objMdLitSituacaoDTO->getStrSinObrigatoria() == 'S'){
                $isLivre = false;
            }

            return $isLivre;
        }


        protected function atualizarSinsSituacaoControlado(){
            $objSituacaoLitigiosoBD = new MdLitSituacaoBD($this->getObjInfraIBanco());
            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->retNumIdSituacaoLitigioso();
            $objMdLitSituacaoDTO->retStrSinObrigatoria();
            $objMdLitSituacaoDTO->retStrSinAlegacoes();

            if($this->contar($objMdLitSituacaoDTO) > 0){
                $arrObjs = $this->listar($objMdLitSituacaoDTO);

                foreach ($arrObjs as $objDTO) {
                    $objDTO->setStrSinObrigatoria('N');
                    $objDTO->setStrSinAlegacoes('N');

                    $objSituacaoLitigiosoBD->alterar($objDTO);
                }

            }


        }

     


    }
