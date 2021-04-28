<?php
/**
 * ANATEL
 *
 * 04/02/2016 - criado por marcelo.bezerra@cast.com.br - CAST
 *
 */
require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitFaseRN extends InfraRN
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
     * @param MdLitFaseDTO $objFaseLitigiosoDTO
     * @return mixed
     * @throws InfraException
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    protected function cadastrarControlado(MdLitFaseDTO $objFaseLitigiosoDTO)
    {
        try {
            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_fase_cadastrar', __METHOD__, $objFaseLitigiosoDTO);

            $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
            $idTipoControle = $objFaseLitigiosoDTO->getNumIdTipoControleLitigioso();
            $permissaoRN = new MdLitPermissaoLitigiosoRN();
            $isAdm = $permissaoRN->isAdm();

            $arrParam = array();
            array_push($arrParam, $idUsuario);
            array_push($arrParam, $idTipoControle);
            $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

            $objRetorno = null;

            if ($isAdm || $isGestor) {

                // Regras de Negocio
                $objInfraException = new InfraException();

                $this->_validarStrNomeFase($objFaseLitigiosoDTO, $objInfraException);
                $this->_validarStrDescricao($objFaseLitigiosoDTO, $objInfraException);

                $objInfraException->lancarValidacoes();

                $objFaseLitigiosoBD = new MdLitFaseBD($this->getObjInfraIBanco());

                $objFaseLitigiosoDTO->setStrNome(trim($objFaseLitigiosoDTO->getStrNome()));
                $objFaseLitigiosoDTO->setStrDescricao(trim($objFaseLitigiosoDTO->getStrDescricao()));
                $objFaseLitigiosoDTO->setStrSinAtivo('S');
                $objRetorno = $objFaseLitigiosoBD->cadastrar($objFaseLitigiosoDTO);

            }

            return $objRetorno;
        } catch (Exception $e) {
            throw new InfraException ('Erro cadastrando Fase Litigiosa.', $e);
        }
    }

    /**
     * @access private
     * @param $objFaseLitigiosoDTO
     * @param $objInfraException
     * @return void
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    private function _validarStrNomeFase(MdLitFaseDTO $objFaseLitigiosoDTO, InfraException $objInfraException)
    {

        // VERIFICA SE O CAMPO FOI PREENCHIDO
        if (InfraString::isBolVazia($objFaseLitigiosoDTO->getStrNome())) {
            $objInfraException->adicionarValidacao('Nome da Fase não informado.');
        }

        $objFaseLitigiosoDTO2 = new MdLitFaseDTO ();
        $nomeFase = trim($objFaseLitigiosoDTO->getStrNome());
        $objFaseLitigiosoDTO2->setStrNome($nomeFase);
        $objFaseLitigiosoDTO2->setNumIdTipoControleLitigioso($objFaseLitigiosoDTO->getNumIdTipoControleLitigioso());

        // Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_fase_consultar', __METHOD__, $objFaseLitigiosoDTO2);

        $objFaseLitigiosoBD = new MdLitFaseBD ($this->getObjInfraIBanco());

        // Valida Quantidade de Caracteres
        if (strlen($objFaseLitigiosoDTO->getStrNome()) > 100) {
            $objInfraException->adicionarValidacao('Nome da Fase possui tamanho superior a 100 caracteres.');
        }

        // VALIDA DUPLICAÇÃO
        // VALIDACAO A SER EXECUTADA NA INSERÇAO DE NOVOS REGISTROS
        if (!is_numeric($objFaseLitigiosoDTO->getNumIdFaseLitigioso())) {

            $ret = $objFaseLitigiosoBD->contar($objFaseLitigiosoDTO2);

            if ($ret > 0) {
                $objInfraException->adicionarValidacao('Já existe Fase para o presente Controle Litigioso com o mesmo Nome.');
            }
        } else {
            // VALIDACAO A SER EXECUTADA QUANDO É FEITO UPDATE DE REGISTROS
            $objFaseLitigiosoDTO2->retNumIdFaseLitigioso();

            $objFaseLitigiosoDTO3 = new MdLitFaseDTO();
            $objFaseLitigiosoDTO3->retTodos();
            $objFaseLitigiosoDTO3->setNumIdFaseLitigioso($objFaseLitigiosoDTO->getNumIdFaseLitigioso());
            $objFaseLitigiosoDTO3->setNumIdTipoControleLitigioso($objFaseLitigiosoDTO->getNumIdTipoControleLitigioso());
            $objComparacao = $objFaseLitigiosoBD->consultar($objFaseLitigiosoDTO3);

            if ($objComparacao->getStrNome() != $objFaseLitigiosoDTO->getStrNome()) {
                $objFaseLitigiosoDTO3->unSetNumIdFaseLitigioso();
                $objFaseLitigiosoDTO3->setStrNome($objFaseLitigiosoDTO->getStrNome(), InfraDTO::$OPER_IGUAL);

                $ret = $objFaseLitigiosoBD->contar($objFaseLitigiosoDTO2);

                if ($ret > 0) {
                    $objInfraException->adicionarValidacao('Já existe Fase para o presente Controle Litigioso com o mesmo Nome.');
                }

            }
        }
    }

    /**
     * Validate field "Descrição".
     *
     * @access private
     * @param $objFaseLitigiosoDTO
     * @param $objInfraException
     * @return void
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    private function _validarStrDescricao($objFaseLitigiosoDTO, $objInfraException)
    {
        // VERIFICA SE O CAMPO FOI PREENCHIDO
        if (InfraString::isBolVazia($objFaseLitigiosoDTO->getStrDescricao())) {
            $objInfraException->adicionarValidacao('Descrição não informada.');
        }
        if (trim($objFaseLitigiosoDTO->getStrDescricao()) != '') {
            if (strlen($objFaseLitigiosoDTO->getStrDescricao()) > 250) {
                $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 100 caracteres.');
            }
        }
    }

    /**
     *
     * @access protected
     * @param $arrObjFaseLitigiosoDTO
     * @return mixed
     * @throws InfraException
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    protected function excluirControlado($arrObjFaseLitigiosoDTO)
    {
        try {

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_fase_excluir', __METHOD__, $arrObjFaseLitigiosoDTO);

            if (count($arrObjFaseLitigiosoDTO) > 0) {

                $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
                $idTipoControle = $arrObjFaseLitigiosoDTO[0]->getNumIdTipoControleLitigioso();
                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm = $permissaoRN->isAdm();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);
                $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                if (count($arrObjFaseLitigiosoDTO) > 0 && ($isAdm || $isGestor)) {

                    $objInfraException = new InfraException();
                    $arrObjFaseLitigiosoDTOValidado = array();

                    $objFaseLitigiosoBD = new MdLitFaseBD ($this->getObjInfraIBanco());

                    $validado = true;
                    for ($i = 0; $i < count($arrObjFaseLitigiosoDTO); $i++) {
                        $this->_validarCadastroAssociado($arrObjFaseLitigiosoDTO[$i], $arrObjFaseLitigiosoDTOValidado, $validado);
                    }
                    if (count($arrObjFaseLitigiosoDTOValidado) > 0) {
                        for ($i = 0; $i < count($arrObjFaseLitigiosoDTOValidado); $i++) {
                            $objFaseLitigiosoBD->excluir($arrObjFaseLitigiosoDTOValidado[$i]);
                        }
                    }

                    if (!$validado) {
                        $objInfraException->adicionarValidacao('A exclusão da Fase não é permitida, pois já existe Situação vinculada à Fase.');
                        $objInfraException->lancarValidacoes();
                    }

                }

            }

            // Auditoria
        } catch (Exception $e) {
            throw new InfraException ('Erro excluindo Fase Litigiosa.', $e);
        }
    }


    /**
     * Validate "Fase" is associated with "Situação"
     *
     * @param $objFaseLitigiosoDTO            MdLitFaseDTO
     * @param $arrObjFaseLitigiosoDTOValidado Array
     * @param $validado                       Boolean
     * @access private
     * @return void
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    private function _validarCadastroAssociado($objFaseLitigiosoDTO, &$arrObjFaseLitigiosoDTOValidado, &$validado)
    {
        $objSituacaoLitigiosoBD = new MdLitSituacaoBD ($this->getObjInfraIBanco());
        $idFase = $objFaseLitigiosoDTO->getNumIdFaseLitigioso();

        $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
        $objSituacaoLitigiosoDTO->setNumIdFaseLitigioso($idFase);

        $countSitFase = $objSituacaoLitigiosoBD->contar($objSituacaoLitigiosoDTO);

        if ($countSitFase > 0) {
            $validado = false;
        } else {
            array_push($arrObjFaseLitigiosoDTOValidado, $objFaseLitigiosoDTO);
        }

    }

    /**
     * @access protected
     * @param $objFaseLitigiosoDTO
     * @throws InfraException
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    protected function alterarControlado($objFaseLitigiosoDTO)
    {

        try {

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_fase_alterar', __METHOD__, $objFaseLitigiosoDTO);

            $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
            $idTipoControle = $objFaseLitigiosoDTO->getNumIdTipoControleLitigioso();
            $permissaoRN = new MdLitPermissaoLitigiosoRN();
            $isAdm = $permissaoRN->isAdm();

            $arrParam = array();
            array_push($arrParam, $idUsuario);
            array_push($arrParam, $idTipoControle);
            $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

            if ($isAdm || $isGestor) {

                // Regras de Negocio
                $objInfraException = new InfraException ();

                if ($objFaseLitigiosoDTO->isSetStrNome()) {
                    $this->_validarStrNomeFase($objFaseLitigiosoDTO, $objInfraException);
                }

                if ($objFaseLitigiosoDTO->isSetStrDescricao()) {
                    $this->_validarStrDescricao($objFaseLitigiosoDTO, $objInfraException);
                }

                $objInfraException->lancarValidacoes();

                $objFaseLitigiosoDTO->setStrNome(trim($objFaseLitigiosoDTO->getStrNome()));
                $objFaseLitigiosoDTO->setStrDescricao(trim($objFaseLitigiosoDTO->getStrDescricao()));

                $objFaseLitigiosoBD = new MdLitFaseBD ($this->getObjInfraIBanco());
                $objFaseLitigiosoBD->alterar($objFaseLitigiosoDTO);
            }


            // Auditoria
        } catch (Exception $e) {
            throw new InfraException ('Erro alterando Fase Litigiosa.', $e);
        }
    }

    /**
     * @access protected
     * @param $arrObjFaseLitigiosoDTO
     * @throws InfraException
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    protected function reativarControlado($arrObjFaseLitigiosoDTO)
    {

        try {

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_fase_reativar');

            if (count($arrObjFaseLitigiosoDTO) > 0) {

                $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
                $idTipoControle = $arrObjFaseLitigiosoDTO[0]->getNumIdTipoControleLitigioso();
                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm = $permissaoRN->isAdm();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);
                $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                if (count($arrObjFaseLitigiosoDTO) > 0 && ($isAdm || $isGestor)) {
                    $objFaseLitigiosoBD = new MdLitFaseBD($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjFaseLitigiosoDTO); $i++) {
                        $objFaseLitigiosoBD->reativar($arrObjFaseLitigiosoDTO[$i]);
                    }

                }

            }

            // Auditoria
        } catch (Exception $e) {
            throw new InfraException ('Erro reativando Fase Litigiosa.', $e);
        }
    }

    /**
     * @access protected
     * @param $objFaseLitigiosoDTO
     * @return mixed
     * @throws InfraException
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    protected function consultarConectado($objFaseLitigiosoDTO)
    {
        try {

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_fase_consultar', __METHOD__, $objFaseLitigiosoDTO);

            $objFaseLitigiosoBD = new MdLitFaseBD($this->getObjInfraIBanco());
            $ret = $objFaseLitigiosoBD->consultar($objFaseLitigiosoDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Fase Litigiosa.', $e);
        }
    }

    /**
     * @access   protected
     * @param MdLitFaseDTO $objFaseLitigiosoDTO
     * @return mixed
     * @throws InfraException
     * @author   Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     * @internal param $ $objFaseLitigiosoDTO*            $objFaseLitigiosoDTO
     */
    protected function listarConectado(MdLitFaseDTO $objFaseLitigiosoDTO)
    {

        try {

            //print_r($objFaseLitigiosoDTO); die();

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_fase_listar', __METHOD__, $objFaseLitigiosoDTO);
            $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
            $idTipoControle = $objFaseLitigiosoDTO->getNumIdTipoControleLitigioso();

            $permissaoRN = new MdLitPermissaoLitigiosoRN();
            $isAdm = $permissaoRN->isAdm();

            $arrParam = array();
            array_push($arrParam, $idUsuario);
            array_push($arrParam, $idTipoControle);

            $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

            // Regras de Negocio
            if ($isAdm || $isGestor) {
                $objFaseLitigiosoBD = new MdLitFaseBD($this->getObjInfraIBanco());
                $ret = $objFaseLitigiosoBD->listar($objFaseLitigiosoDTO);
            } else {
                $ret = array();
            }

            return $ret;

        } catch (Exception $e) {
            throw new InfraException ('Erro listando Fase Litigiosoes.', $e);
        }
    }

    /**
     * @access protected
     * @param $arrObjFaseLitigiosoDTO
     * @throws InfraException
     * @author Jaqueline Mendes <jaqueline.mendes@cast.com.br>
     */
    protected function desativarControlado($arrObjFaseLitigiosoDTO)
    {

        try {

            // Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_lit_fase_desativar');

            if (count($arrObjFaseLitigiosoDTO) > 0) {

                $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
                $idTipoControle = $arrObjFaseLitigiosoDTO[0]->getNumIdTipoControleLitigioso();

                $permissaoRN = new MdLitPermissaoLitigiosoRN();
                $isAdm = $permissaoRN->isAdm();

                $arrParam = array();
                array_push($arrParam, $idUsuario);
                array_push($arrParam, $idTipoControle);

                $isGestor = $permissaoRN->isUsuarioGestorTipoControle($arrParam);

                if (count($arrObjFaseLitigiosoDTO) > 0 && ($isAdm || $isGestor)) {

                    $objFaseLitigiosoBD = new MdLitFaseBD ($this->getObjInfraIBanco());

                    for ($i = 0; $i < count($arrObjFaseLitigiosoDTO); $i++) {
                        $objFaseLitigiosoBD->desativar($arrObjFaseLitigiosoDTO[$i]);
                    }

                }

            }

            // Auditoria
        } catch (Exception $e) {
            throw new InfraException ('Erro desativando Fase Litigiosa.', $e);
        }
    }


    protected function controlarStatusSituacaoControlado($arrDados)
    {
        $arrObjFaseLitigiosoDTO = $arrDados[0];
        $idTipoControle = $arrDados[1];
        $desativar = $arrDados[2];

        $arrObjSituacaoLitigiosoDTO = array();
        if (count($arrObjFaseLitigiosoDTO) > 0) {
            for ($i = 0; $i < count($arrObjFaseLitigiosoDTO); $i++) {
                $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
                $objSituacaoLitigiosoRN = new MdLitSituacaoRN();
                $objSituacaoLitigiosoDTO->setNumIdFaseLitigioso($arrObjFaseLitigiosoDTO[$i]->getNumIdFaseLitigioso());
                $objSituacaoLitigiosoDTO->retNumIdSituacaoLitigioso();
                $countObjSitLitDTO = $objSituacaoLitigiosoRN->contar($objSituacaoLitigiosoDTO);

                if ($countObjSitLitDTO > 0) {
                    $arrObjSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->listarComTipoDeControle($objSituacaoLitigiosoDTO, $idTipoControle);

                    if ($desativar) {
                        $objSituacaoLitigiosoRN->desativarComTipoControle($arrObjSituacaoLitigiosoDTO, $idTipoControle);
                    } else {
                        $objSituacaoLitigiosoRN->reativarComTipoControle($arrObjSituacaoLitigiosoDTO, $idTipoControle);
                    }
                }

            }
        }
    }
}
