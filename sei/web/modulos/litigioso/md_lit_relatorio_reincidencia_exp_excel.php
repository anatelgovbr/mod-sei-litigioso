<?php

try{
    $objMdLitDecisaoDTO = new MdLitDecisaoDTO();

    // recuperando a parametrização da reincidência
    $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
    $objMdLitReincidenAntecedenDTO->retNumPrazo();
    $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();
    $objMdLitReincidenAntecedenDTO->retStrOrientacao();
    $objMdLitReincidenAntecedenDTO->retStrTipoRegraReincidencia();
    $objMdLitReincidenAntecedenDTO->setStrTipo(MdLitReincidenAntecedenRN::$TIPO_REINCIDENCIA);

    $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
    $objMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->consultar($objMdLitReincidenAntecedenDTO);


    PaginaSEI::getInstance()->prepararOrdenacao($objMdLitDecisaoDTO, 'Norma', InfraDTO::$TIPO_ORDENACAO_ASC, true);
    $objMdLitDecisaoDTO->setOrdStrDispositivo(InfraDTO::$TIPO_ORDENACAO_ASC);

    if($objMdLitReincidenAntecedenDTO){
        //filtrando conforme a parametrização do módulo
        $filtroDataCorte = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS);
        $objMdLitDecisaoDTO->setDtaTransitoJulgado($filtroDataCorte);

        $objMdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
        $objMdLitRelTpDecReinAnteDTO->retNumIdRelMdLitTipoDecisao();
        $objMdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden());

        $objMdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();
        $arrObjMdLitRelTpDecReinAnteDTO = $objMdLitRelTpDecReinAnteRN->listar($objMdLitRelTpDecReinAnteDTO);

        if(count($arrObjMdLitRelTpDecReinAnteDTO)){
            $arrIdRelMdLitTipoDecisao = InfraArray::converterArrInfraDTO($arrObjMdLitRelTpDecReinAnteDTO, 'IdRelMdLitTipoDecisao');
            $objMdLitDecisaoDTO->setNumIdMdLitTipoDecisao($arrIdRelMdLitTipoDecisao, InfraDTO::$OPER_IN);
        }
    }

    if(!empty($_POST['txtDtCorte'])){
        $filtroDataCorte = $_POST['txtDtCorte'];
        $data = InfraData::calcularData($objMdLitReincidenAntecedenDTO->getNumPrazo(), InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ATRAS,$filtroDataCorte);
        $objMdLitDecisaoDTO->setDtaTransitoJulgado($data);
        $objMdLitDecisaoDTO->setDtaCorte($filtroDataCorte);
    }

    if(!empty($_POST['hdnInteressados'])){
        $hdnInteressado = $_POST['hdnInteressados'];
        $arrInteressados = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnInteressados']);
        $arrIdContato = InfraArray::simplificarArr($arrInteressados, 0);

        $objEntradaListarContatosAPI = new ContatoDTO();
        $objEntradaListarContatosAPI->retNumIdContato();
        $objEntradaListarContatosAPI->retDblCpf();
        $objEntradaListarContatosAPI->retDblCnpj();
        $objEntradaListarContatosAPI->setNumIdContato($arrIdContato, InfraDTO::$OPER_IN);

        $objSeiRN = new ContatoRN();
        $arrObjContato = $objSeiRN->listarRN0325($objEntradaListarContatosAPI);
        $arrCnpjContato = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjContato, 'Cnpj'), null);
        $arrCpfContato = InfraArray::retirarElementoArray(InfraArray::converterArrInfraDTO($arrObjContato, 'Cpf'), null);

        if(count($arrCpfContato) && count($arrCnpjContato)){
            $objMdLitDecisaoDTO->adicionarCriterio(array('CpfContato', 'CnpjContato'),array(InfraDTO::$OPER_IN,InfraDTO::$OPER_IN), array($arrCpfContato, $arrCnpjContato), InfraDTO::$OPER_LOGICO_OR);
        }elseif(count($arrCpfContato)){
            $objMdLitDecisaoDTO->setStrCpfContato($arrCpfContato, InfraDTO::$OPER_IN);
        }elseif(count($arrCnpjContato)){
            $objMdLitDecisaoDTO->setStrCnpjContato($arrCnpjContato, InfraDTO::$OPER_IN);
        }

        if(count($arrCpfContato) == 0 && count($arrCnpjContato) == 0){
            $objMdLitDecisaoDTO->setStrCnpjContato(null);
            $objMdLitDecisaoDTO->setStrCpfContato(null);
            $msgInicialização = "Os interessados selecionados não possui CPNJ/CPF";
        }
    }
    if(!empty($_POST['hdnDispositivo']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO){
        $hdnDispositivo = $_POST['hdnDispositivo'];
        $arrDispositivos = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnDispositivo']);
        $arrIdDispositivos = InfraArray::simplificarArr($arrDispositivos, 0);

        $objMdLitDecisaoDTO->setNumIdDispositivoNormativoMdLitRelDisNorConCtr($arrIdDispositivos, InfraDTO::$OPER_IN);
    }
    if(!empty($_POST['hdnConduta']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$CONDUTA){
        $hdnConduta = $_POST['hdnConduta'];
        $arrConduta = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnConduta']);
        $arrIdConduta = InfraArray::simplificarArr($arrConduta, 0);

        $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($arrIdConduta, InfraDTO::$OPER_IN);
    }
    if(!empty($_POST['hdnDispositivoConduta']) && $objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() == MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA){
        $hdnConduta = $_POST['hdnConduta'];
        $hdnDispositivo = $_POST['hdnDispositivo'];
        $arrDispositivoConduta = PaginaSEI::getInstance()->getArrOptionsSelect($_POST['hdnDispositivoConduta']);

        $arrNomeCriterioDispositivoNormativo = array();
        foreach ($arrDispositivoConduta as $key=>$dispositivoConduta){
            $nomeCriterio ='criterioDispositivoConduta'.$key;
            $arrNomeCriterioDispositivoNormativo[] = $nomeCriterio;
            $objMdLitDecisaoDTO->adicionarCriterio(array('IdCondutaMdLitRelDisNorConCtr', 'IdDispositivoNormativoMdLitRelDisNorConCtr'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL), array($dispositivoConduta[1], $dispositivoConduta[0]), InfraDTO::$OPER_LOGICO_AND, $nomeCriterio);
        }
        if(count($arrNomeCriterioDispositivoNormativo) > 1) {
            $objMdLitDecisaoDTO->agruparCriterios($arrNomeCriterioDispositivoNormativo, array_fill(0, count($arrNomeCriterioDispositivoNormativo) - 1, InfraDTO::$OPER_LOGICO_OR));
        }

    }

    if(!empty($_POST['selCondutaEspecifica']) && $_POST['selCondutaEspecifica'] !='null'){
        $selConduta = $_POST['selCondutaEspecifica'];
        $objMdLitDecisaoDTO->setNumIdCondutaMdLitRelDisNorConCtr($_POST['selCondutaEspecifica']);
    }

    $objMdLitDecisaoRN = new MdLitDecisaoRN();
    $arrObjTipoControleLitigiosoDTO = $objMdLitDecisaoRN->listarRelatorio($objMdLitDecisaoDTO);
    $arrDadosPlanilha = MdLitDecisaoINT::converterParaArrExcelInfraDTO($arrObjTipoControleLitigiosoDTO);

    $primeiraPosition    = '';
    $objPHPExcel         = new PHPExcel();
    $ultimaPosition      = '';
    $getPrimeiraPosition = false;
    $primeiraLinha       = 0;
    $colunaAtual         = '';
    $dtHrAtual   = InfraData::getStrDataHoraAtual();
    $nomeArquivo = 'SEI - Litigioso Reincidências Específicas  - '.$dtHrAtual;

    foreach($arrDadosPlanilha as $linha => $dadosLinha){
        foreach($dadosLinha as $coluna => $dado){
            $colunaAtual  = $coluna;
            $positionDado = $coluna.$linha;
            $qtdLinhas    = count($arrDadosPlanilha);

            if(!$getPrimeiraPosition){
                $primeiraPosition    = $positionDado;
                $primeiraLinha       = $linha;
                $getPrimeiraPosition = true;
            }

            $ultimaLinha    = $linha;
            $ultimaPosition = $positionDado;

            $value = utf8_encode($dado);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($positionDado, $value);
            $objPHPExcel->getActiveSheet()->getColumnDimension($coluna)->setAutoSize(true);

            //Borda Colunas
            $bordaColuna = $colunaAtual.$primeiraLinha.':'.$colunaAtual.$qtdLinhas;

            $objPHPExcel->getActiveSheet()->getStyle($bordaColuna)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        }
    }

    $bordaCompleta = $primeiraPosition.':'.$ultimaPosition;
    $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()
        ->getStyle('A1:G1')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('E4E4E4');

    $objPHPExcel->getActiveSheet()->getStyle($bordaCompleta)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    if(empty($arrObjTipoControleLitigiosoDTO)){
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Nenhum registro encontrado.');
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }

// Indicação da criação do ficheiro
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// Encaminhar o ficheiro resultante para abrir no browser ou fazer download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$nomeArquivo.'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output');
}catch (Exception $e){
    echo $e->getMessage();
}

?>