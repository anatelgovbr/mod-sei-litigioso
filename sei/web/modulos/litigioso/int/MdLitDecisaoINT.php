<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitDecisaoINT extends InfraINT
{

    public static function montarSelectIdMdLitDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitProcessoSituacao = '', $numIdMdLitRelDisNorConCtr = '', $numIdMdLitObrigacao = '', $numIdMdLitTipoDecisao = '', $numIdMdLitEspecieDecisao = '', $numIdUsuario = '', $numIdUnidade = '')
    {
        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        $objMdLitDecisaoDTO->retNumIdMdLitDecisao();
        $objMdLitDecisaoDTO->retNumIdMdLitDecisao();

        if ($numIdMdLitProcessoSituacao !== '') {
            $objMdLitDecisaoDTO->setNumIdMdLitProcessoSituacao($numIdMdLitProcessoSituacao);
        }

        if ($numIdMdLitRelDisNorConCtr !== '') {
            $objMdLitDecisaoDTO->setNumIdMdLitRelDisNorConCtr($numIdMdLitRelDisNorConCtr);
        }

        if ($numIdMdLitObrigacao !== '') {
            $objMdLitDecisaoDTO->setNumIdMdLitObrigacao($numIdMdLitObrigacao);
        }

        if ($numIdMdLitTipoDecisao !== '') {
            $objMdLitDecisaoDTO->setNumIdMdLitTipoDecisao($numIdMdLitTipoDecisao);
        }

        if ($numIdMdLitEspecieDecisao !== '') {
            $objMdLitDecisaoDTO->setNumIdMdLitEspecieDecisao($numIdMdLitEspecieDecisao);
        }

        if ($numIdUsuario !== '') {
            $objMdLitDecisaoDTO->setNumIdUsuario($numIdUsuario);
        }

        if ($numIdUnidade !== '') {
            $objMdLitDecisaoDTO->setNumIdUnidade($numIdUnidade);
        }

        if ($strValorItemSelecionado != null) {
            $objMdLitDecisaoDTO->setBolExclusaoLogica(false);
            $objMdLitDecisaoDTO->adicionarCriterio(array('SinAtivo', 'IdMdLitDecisao'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', $strValorItemSelecionado), InfraDTO::$OPER_LOGICO_OR);
        }

        $objMdLitDecisaoDTO->setOrdNumIdMdLitDecisao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdLitDecisaoRN = new MdLitDecisaoRN();
        $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listar($objMdLitDecisaoDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitDecisaoDTO, 'IdMdLitDecisao', 'IdMdLitDecisao');
    }

    public static function gerarItensTabelaDinamicaForm($decisaoPosts)
    {
        $infracaoArr = array();
        $index = 0;
        $localidade = null;
        $selectUf = null;


        foreach ($decisaoPosts as $key => $decisao) {

//          if(($decisao['id_md_lit_tipo_decisao'] == 'null' || empty($decisao['id_md_lit_tipo_decisao'])))
//              continue;

            if ($decisao['id_decisao'] != '') {
                $infracaoArr[$index][] = $decisao['id_decisao'];
            } else {
                $infracaoArr[$index][] = 'novo_' . $index;
            }
            $infracaoArr[$index][] = $decisao['id'];
            $infracaoArr[$index][] = $decisao['id_md_lit_tipo_decisao'];
            $infracaoArr[$index][] = $decisao['id_md_lit_especie_decisao'];
            $infracaoArr[$index][] = $decisao['multa'];
            $infracaoArr[$index][] = $decisao['valor_ressarcimento'];
            $infracaoArr[$index][] = $decisao['id_md_lit_obrigacao'];
            $infracaoArr[$index][] = $decisao['prazo'];
            $infracaoArr[$index][] = $decisao['id_usuario'] != '' ? $decisao['id_usuario'] : SessaoSEI::getInstance()->getNumIdUsuario();
            $infracaoArr[$index][] = $decisao['id_unidade'] != '' ? $decisao['id_unidade'] : SessaoSEI::getInstance()->getNumIdUnidadeAtual();

            $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retTodos();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrDispositivo();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrNorma();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retNumIdCondutaLitigioso();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrConduta();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdDispositivoNormativoNormaCondutaControle($decisao['id']);

            $objRelDispositivoNormativoCondutaControleLitigiosoRN = new MdLitRelDispositivoNormativoCondutaControleRN();
            $objRelDispositivoNormativoCondutaControleLitigiosoDTO = $objRelDispositivoNormativoCondutaControleLitigiosoRN->consultar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);
            $infracaoArr[$index][] = $objRelDispositivoNormativoCondutaControleLitigiosoDTO->getStrInfracao();

            $objMdLitTipoDecisaoDTO = new MdLitTipoDecisaoDTO();
            $objMdLitTipoDecisaoDTO->retTodos();
            $objMdLitTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($decisao['id_md_lit_tipo_decisao']);

            $objMdLitTipoDecisaoRN = new MdLitTipoDecisaoRN();
            $objMdLitTipoDecisaoDTO = $objMdLitTipoDecisaoRN->consultar($objMdLitTipoDecisaoDTO);
            if ($objMdLitTipoDecisaoDTO) {
                $infracaoArr[$index][] = $objMdLitTipoDecisaoDTO->getStrNome();
            } else {
                $infracaoArr[$index][] = null;
            }

            $objMdLitEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
            $objMdLitEspecieDecisaoDTO->retTodos();
            $objMdLitEspecieDecisaoDTO->setNumIdEspecieLitigioso($decisao['id_md_lit_especie_decisao']);

            $objMdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
            $objMdLitEspecieDecisaoDTO = $objMdLitEspecieDecisaoRN->consultar($objMdLitEspecieDecisaoDTO);
            $infracaoArr[$index][] = $objMdLitEspecieDecisaoDTO ? $objMdLitEspecieDecisaoDTO->getStrNome() : null;

            $infracaoArr[$index][] = $decisao['data'] != '' ? $decisao['data'] : InfraData::getStrDataAtual();
            $nomeUsuario = $decisao['nome_usuario'] != '' ? strip_tags($decisao['nome_usuario']) : SessaoSEI::getInstance()->getStrNomeUsuario();
            $siglaUsuario = $decisao['sigla_usuario'] != '' ? strip_tags($decisao['sigla_usuario']) : SessaoSEI::getInstance()->getStrSiglaUsuario();
            $infracaoArr[$index][] = $decisao['nome_usuario'] != '' ? $nomeUsuario : '<a alt="' . $nomeUsuario . '" title="' . $nomeUsuario . '" class="ancoraSigla"> ' . $siglaUsuario . ' </a>';
            $siglaUnidade = $decisao['sigla_unidade'] != '' ? strip_tags($decisao['sigla_unidade']) : SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();
            $descricaoUnidade = $decisao['descricao_unidade'] != '' ? strip_tags($decisao['descricao_unidade']) : SessaoSEI::getInstance()->getStrDescricaoUnidadeAtual();
            $infracaoArr[$index][] =$decisao['sigla_unidade'] != '' ? $siglaUnidade : '<a alt="' . $descricaoUnidade . '" title="' . $descricaoUnidade . '" class="ancoraSigla"> ' . $siglaUnidade . ' </a>';
            $localidade = $decisao['localidade'] == null ? $localidade : $decisao['localidade'];
            if ($localidade == MdLitDecisaoRN::$STA_LOCALIDADE_UF) {
                $selectUf = $decisao['id_uf'] ? implode("#", $decisao['id_uf']) : $selectUf;
                if ($selectUf == null) {
                    $decisao['sin_cadastro_parcial'] = 'S';
                }
            } else {
                $selectUf = '';
            }


            $infracaoArr[$index][] = $localidade;
            $infracaoArr[$index][] = $selectUf;
            $infracaoArr[$index][] = $decisao['sin_cadastro_parcial'];
            $index++;
        }
        return PaginaSEI::getInstance()->gerarItensTabelaDinamica($infracaoArr, false);
    }

    public static function existeInfracao($idMdLitRelDisNorConCtr)
    {
        $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
        $objMdLitDecisaoDTO->retTodos(false);
        $objMdLitDecisaoDTO->setNumIdMdLitRelDisNorConCtr($idMdLitRelDisNorConCtr);

        $objMdLitDecisaoRN = new MdLitDecisaoRN();
        $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listar($objMdLitDecisaoDTO);

        if ($arrObjMdLitDecisaoDTO)
            return '<existeInfracao value="S" />';

        return '<existeInfracao value="N" />';


    }

    public static function montarSelectCondutaPorArrMdLitDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arrObjMdLitDecisaoDTO)
    {
        if ($arrObjMdLitDecisaoDTO) {
            return null;
        }
        if ($arrObjMdLitDecisaoDTO)
            $arrObjMdLitDecisaoDTO = InfraArray::distinctArrInfraDTO($arrObjMdLitDecisaoDTO, 'IdCondutaMdLitRelDisNorConCtr');
        //retirando os dados da decisão que não possui conduta
        if ($arrObjMdLitDecisaoDTO)
            $arrObjMdLitDecisaoDTO = InfraArray::retirarElementoArrInfraDTO($arrObjMdLitDecisaoDTO, 'Conduta', null);
        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arrObjMdLitDecisaoDTO, 'IdCondutaMdLitRelDisNorConCtr', 'Conduta');
    }

    public static function montarSelectDispositivoPorArrMdLitDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arrObjMdLitDecisaoDTO)
    {
        if ($arrObjMdLitDecisaoDTO) {
            return null;
        }
        if ($arrObjMdLitDecisaoDTO)
            $arrObjMdLitDecisaoDTO = InfraArray::distinctArrInfraDTO($arrObjMdLitDecisaoDTO, 'IdDispositivoNormativoMdLitRelDisNorConCtr');
        //retirando os dados da decisão que não possui conduta
        if ($arrObjMdLitDecisaoDTO)
            $arrObjMdLitDecisaoDTO = InfraArray::retirarElementoArrInfraDTO($arrObjMdLitDecisaoDTO, 'Dispositivo', null);
        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arrObjMdLitDecisaoDTO, 'IdDispositivoNormativoMdLitRelDisNorConCtr', 'DispositivoNormativo');
    }


    public static function converterParaArrExcelInfraDTO($arrObjDTO, $inicioLinha = 1)
    {
        $arrAlfabeto = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $arrRetorno = array();
        //obs : a key não foi reutilizada, pois a pesquisa  não segue uma ordem correta .

        $arrCabecalho = array('Processo', 'Interessado', 'CNPJ/CPF', 'Dispositivo', 'Norma', 'Conduta', 'Data do Trânsito Julgado');

        //Cabecalho
        $contador = 0;
        foreach ($arrCabecalho as $label) {
            $arrRetorno[1][$arrAlfabeto[$contador]] = $label;
            $contador++;
        }

        //Dados
        $contador = 0;
        foreach ($arrObjDTO as $objDTO) {
            $linhaExcel = $contador + $inicioLinha + 1;
            $arrRetorno[$linhaExcel][$arrAlfabeto[0]] = $objDTO->getStrProtocoloFormatadoProcedimento();
            $arrRetorno[$linhaExcel][$arrAlfabeto[1]] = $objDTO->getStrNomeContato();

            if (!empty($objDTO->getStrCpfContato())) {
                $arrRetorno[$linhaExcel][$arrAlfabeto[2]] = InfraUtil::formatarCpfCnpj($objDTO->getStrCpfContato());
            } else {
                $arrRetorno[$linhaExcel][$arrAlfabeto[2]] = InfraUtil::formatarCpfCnpj($objDTO->getStrCnpjContato());
            }
            $arrRetorno[$linhaExcel][$arrAlfabeto[3]] = $objDTO->getStrDispositivo();
            $arrRetorno[$linhaExcel][$arrAlfabeto[4]] = $objDTO->getStrNorma();
            $arrRetorno[$linhaExcel][$arrAlfabeto[5]] = $objDTO->getStrConduta();
            $arrRetorno[$linhaExcel][$arrAlfabeto[6]] = $objDTO->getDtaTransitoJulgado();
            $contador++;
        }


        return $arrRetorno;
    }


    public static function converterParaArrExcelInfraDTOAntecedente($arrObjDTO, $inicioLinha = 1)
    {
        $arrAlfabeto = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $arrRetorno = array();
        //obs : a key não foi reutilizada, pois a pesquisa  não segue uma ordem correta .

        $arrCabecalho = array('Processo', 'Interessado', 'CNPJ/CPF', 'Tipo de Decisão', 'Espécie de Decisão', 'Data do Trânsito Julgado');

        //Cabecalho
        $contador = 0;
        foreach ($arrCabecalho as $label) {
            $arrRetorno[1][$arrAlfabeto[$contador]] = $label;
            $contador++;
        }

        //Dados
        $contador = 0;
        foreach ($arrObjDTO as $objDTO) {
            $linhaExcel = $contador + $inicioLinha + 1;
            $arrRetorno[$linhaExcel][$arrAlfabeto[0]] = $objDTO->getStrProtocoloFormatadoProcedimento();
            $arrRetorno[$linhaExcel][$arrAlfabeto[1]] = $objDTO->getStrNomeContato();

            if (!empty($objDTO->getStrCpfContato())) {
                $arrRetorno[$linhaExcel][$arrAlfabeto[2]] = InfraUtil::formatarCpfCnpj($objDTO->getStrCpfContato());
            } else {
                $arrRetorno[$linhaExcel][$arrAlfabeto[2]] = InfraUtil::formatarCpfCnpj($objDTO->getStrCnpjContato());
            }
            $arrRetorno[$linhaExcel][$arrAlfabeto[3]] = $objDTO->getStrNomeMdLitTipoDecisao();
            $arrRetorno[$linhaExcel][$arrAlfabeto[4]] = $objDTO->getStrNomeMdLitEspecieDecisao();
            $arrRetorno[$linhaExcel][$arrAlfabeto[5]] = $objDTO->getDtaTransitoJulgado();
            $contador++;
        }


        return $arrRetorno;
    }


    static public function somarDiaUtil($numQtde, $strData)
    {

        $strDataFinal = InfraData::calcularData(($numQtde + 365), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strData);

        self::_removerTimeDate($strData);
        $arrFeriados = self::_recuperarFeriados($strData, $strDataFinal);

        $count = 0;
        while ($count < $numQtde) {
            $strData = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strData);
            if (InfraData::obterDescricaoDiaSemana($strData) != 'sábado' &&
                InfraData::obterDescricaoDiaSemana($strData) != 'domingo' &&
                !in_array($strData, $arrFeriados)
            ) {
                $count++;
            }
        }

        return $strData;
    }

    static private function _removerTimeDate(&$strData)
    {
        $countDate = strlen($strData);
        $isDateTime = $countDate > 10 ? true : false;
        if ($isDateTime) {
            $arrData = explode(" ", $strData);
            $strData = $arrData[0];
        }
    }

    static private function _recuperarFeriados($strDataInicial, $strDataFinal)
    {
        $numIdOrgao = SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual();
        $numIdOrgao = is_null($numIdOrgao) ? SessaoSEIExterna::getInstance()->getNumIdOrgaoUnidadeAtual() : $numIdOrgao;

        if (is_null($numIdOrgao)) {
            $objOrgaoDTO = new OrgaoDTO();
            $objOrgaoDTO->retNumIdOrgao();
            $objOrgaoDTO->setBolExclusaoLogica(false);
            $objOrgaoDTO->adicionarCriterio(array('SinAtivo', 'Sigla'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', ConfiguracaoSEI::getInstance()->getValor('SessaoSEI', 'SiglaOrgaoSistema')), InfraDTO::$OPER_LOGICO_AND);

            $objOrgaoRN = new OrgaoRN();
            $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);
            $numIdOrgao = !is_null($arrObjOrgaoDTO) && count($arrObjOrgaoDTO) > 0 ? current($arrObjOrgaoDTO)->getNumIdOrgao() : null;
        }

        $arrFeriados = array();

        $objFeriadoRN = new FeriadoRN();
        $objFeriadoDTO = new FeriadoDTO();
        $objFeriadoDTO->retDtaFeriado();
        $objFeriadoDTO->retStrDescricao();

        if ($numIdOrgao != '') {
            $objFeriadoDTO->adicionarCriterio(array('IdOrgao', 'IdOrgao'),
                array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
                array(null, $numIdOrgao),
                array(InfraDTO::$OPER_LOGICO_OR));
        } else {
            $objFeriadoDTO->setNumIdOrgao(null);
        }

        $objFeriadoDTO->adicionarCriterio(array('Feriado', 'Feriado'),
            array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
            array($strDataInicial, $strDataFinal),
            array(InfraDTO::$OPER_LOGICO_AND));

        $objFeriadoDTO->setOrdDtaFeriado(InfraDTO::$TIPO_ORDENACAO_ASC);

        $count = $objFeriadoRN->contar($objFeriadoDTO);
        $arrObjFeriadoDTO = $objFeriadoRN->listar($objFeriadoDTO);

        if ($count > 0) {
            $arrFeriados = InfraArray::converterArrInfraDTO($arrObjFeriadoDTO, 'Feriado');
        }

        return $arrFeriados;
    }

    static public function calcularDataPrazo($prazoDias, $dataInicial = null)
    {
        // DATA INÍCIO
        if (is_null($dataInicial)) {
            $dataInicial = InfraData::getStrDataAtual();
        }

        if ($prazoDias > 1) {
            $dataInicial = self::somarDiaUtil(1, $dataInicial);
            if ($prazoDias > 2) {
                $data = DateTime::createFromFormat('d/m/Y', $dataInicial);
                $dtsSomar = 'P' . ($prazoDias - 2) . 'D';
                $data->add(new DateInterval($dtsSomar));
                $dataInicial = $data->format('d/m/Y');
            }
        }

        return self::somarDiaUtil(1, $dataInicial);
    }

    public static function calcularDataDecursoPrazoRecurso($idProcedimento, $idTipoControle, $strDtBase, $idSituacao)
    {
        $data = '';
        $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
        $objMdLitProcessoSituacaoDTO->retTodos();
        $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($idTipoControle);
        $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitSituacao(InfraDTO::$TIPO_ORDENACAO_DESC);

        // se for uma alteração da situação busca o prazo de recurso da situação correta
        if($idSituacao){
          $objMdLitProcessoSituacaoDTO->setNumIdMdLitProcessoSituacao($idSituacao, InfraDTO::$OPER_MENOR_IGUAL);
        }

        $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
        $objMdLitProcessoSituacaoDTO = current($objMdLitProcessoSituacaoRN->listar($objMdLitProcessoSituacaoDTO));

        $idNumIdSituacaoLitigiosoDesisorio = $objMdLitProcessoSituacaoDTO->getNumIdMdLitSituacao();

        $objSituacaoLitigiosoDTO = new MdLitSituacaoDTO();
        $objSituacaoLitigiosoDTO->retNumIdSituacaoLitigioso();
        $objSituacaoLitigiosoDTO->retStrSinDecisoria();
        $objSituacaoLitigiosoDTO->retNumOrdem();
        $objSituacaoLitigiosoDTO->retStrSinRecursal();
        $objSituacaoLitigiosoDTO->retStrNomeFase();
        $objSituacaoLitigiosoDTO->retStrNome();
        $objSituacaoLitigiosoDTO->retNumPrazo();

        $objSituacaoLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControle);
        $objSituacaoLitigiosoDTO->setStrSinAtivo('S');

        $objSituacaoLitigiosoRN = new MdLitSituacaoRN();

        $objSituacaoLitigiosoDTO->setNumIdSituacaoLitigioso($idNumIdSituacaoLitigiosoDesisorio);
        //$objSituacaoLitigiosoDTO->setStrSinDecisoria('S');
        $objSituacaoDecisoria = $objSituacaoLitigiosoRN->consultar($objSituacaoLitigiosoDTO);
        if ($objSituacaoDecisoria) {

            $objSituacaoLitigiosoDTO->unSetNumIdSituacaoLitigioso();
            $objSituacaoLitigiosoDTO->unSetStrSinDecisoria();
            $objSituacaoLitigiosoDTO->setNumOrdem($objSituacaoDecisoria->getNumOrdem(), InfraDTO::$OPER_MAIOR_IGUAL);
            $objSituacaoLitigiosoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

            $arrObjSituacaoLitigiosoDTO = $objSituacaoLitigiosoRN->listarComTipoDeControle($objSituacaoLitigiosoDTO, $idTipoControle);
            $objRecursoDTO = null;
            foreach ($arrObjSituacaoLitigiosoDTO as $dto) {
                if ($dto->getStrSinRecursal() == 'S') {
                    $objRecursoDTO = $dto;
                    break;
                }
            }

            if ($objRecursoDTO && $objRecursoDTO->getNumPrazo()) {
                $data = self::calcularDataPrazo($objRecursoDTO->getNumPrazo(), $strDtBase);
            }
        }
        return "<resultado>{$data}</resultado>";
    }
}

?>