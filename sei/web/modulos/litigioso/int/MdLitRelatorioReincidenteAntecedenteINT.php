<?
    /**
     * ANATEL
     *
     * 03/12/2018 - criado por Ellyson de Jesus Silva
     *
     * Versão do Gerador de Código: 1.40.1
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitRelatorioReincidenteAntecedenteINT extends InfraINT
    {

        public static function dataAntigaInfracao($arrObjMdLitRelDispositivoNormativoCondutaControleDTO){
            $dataAntiga = null;

            foreach ($arrObjMdLitRelDispositivoNormativoCondutaControleDTO as $objInfracaoDTO){
                $dataAtual = $objInfracaoDTO->getDtaInfracaoEspecifica() ? $objInfracaoDTO->getDtaInfracaoEspecifica() :$objInfracaoDTO->getDtaInfracaoPeriodoInicial();
                if(!$dataAntiga){
                    $dataAntiga = $dataAtual;
                }elseif(InfraData::compararDatas($dataAntiga, $dataAtual) < 0){
                    $dataAntiga = $dataAtual;
                }

            }
            return $dataAntiga;
        }

        public static function associarDispositivoConduta($arrIdDispositivos,$arrIdConduta){
            $objMdLitRelDispositivoNormativoCondutaDTO = new MdLitRelDispositivoNormativoCondutaDTO();
            $objMdLitRelDispositivoNormativoCondutaDTO->retTodos();
            $objMdLitRelDispositivoNormativoCondutaDTO->setNumIdDispositivoNormativo($arrIdDispositivos, InfraDTO::$OPER_IN);
            $objMdLitRelDispositivoNormativoCondutaDTO->setNumIdConduta($arrIdConduta, InfraDTO::$OPER_IN);

            $objMdLitRelDispositivoNormativoCondutaRN = new MdLitRelDispositivoNormativoCondutaRN();
            $arrobjMdLitRelDispositivoNormativoConduta = $objMdLitRelDispositivoNormativoCondutaRN->listar($objMdLitRelDispositivoNormativoCondutaDTO);

            $arrAssociarDispositivoConduta = array();
            foreach ($arrIdDispositivos as $idDispositivo){
                $arrObjDispositivoConduta = InfraArray::filtrarArrInfraDTO($arrobjMdLitRelDispositivoNormativoConduta,'IdDispositivoNormativo', $idDispositivo);
                if(count($arrObjDispositivoConduta) == 0){
                    $objInfraException = new InfraException();
                    $objInfraException->adicionarValidacao('Nos filtros existe Dispositivo Normativo sem ter a seleção de pelo menos uma Conduta correspondente. Conforme as regras de definição de infração de mesma natureza, é necessário selecionar Dispositivo Normativo e Conduta correspondentes.');
                    $objInfraException->lancarValidacoes();
                }

                $arrAssociarDispositivoConduta = array_merge($arrAssociarDispositivoConduta, $arrObjDispositivoConduta);
            }

            return self::gerarXMLItensArrInfraDTO($arrAssociarDispositivoConduta);
        }

        public static function gerarXMLItensArrInfraDTO($arrDto){
            $xml = '';
            $xml .= '<itens>';
            if ($arrDto !== null ){
                foreach($arrDto as $dto){
                    $xml .= '<item id_dispositivo="'.$dto->get('IdDispositivoNormativo').'"';
                    $xml .= ' id_conduta="'.$dto->get('IdConduta').'"';

                    $xml .= '></item>';
                }
            }
            $xml .= '</itens>';
            return $xml;
        }

        public static function existeParametrizado($tipo){
            $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
            $objMdLitReincidenAntecedenDTO->retTodos();
            $objMdLitReincidenAntecedenDTO->setStrTipo($tipo);
            $objMdLitReincidenAntecedenDTO->setNumMaxRegistrosRetorno(1);

            $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
            $objMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->consultar($objMdLitReincidenAntecedenDTO);

            if($objMdLitReincidenAntecedenDTO){
                $objMdLitRelTpDecReinAnteDTO = new MdLitRelTpDecReinAnteDTO();
                $objMdLitRelTpDecReinAnteDTO->retTodos();
                $objMdLitRelTpDecReinAnteDTO->setNumIdRelMdLitReincidenAnteceden($objMdLitReincidenAntecedenDTO->getNumIdMdLitReincidenAnteceden());

                $objMdLitRelTpDecReinAnteRN = new MdLitRelTpDecReinAnteRN();
                $arrobjMdLitRelTpDecReinAnteRN = $objMdLitRelTpDecReinAnteRN->listar($objMdLitRelTpDecReinAnteDTO);

                if(count($arrobjMdLitRelTpDecReinAnteRN)){
                    return true;
                }

            }
            return false;
        }

    }
