<?
    /**
     * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
     *
     * 03/04/2017 - criado por Ellyson de Jesus Silva
     *
     * Versão do Gerador de Código: 1.40.1
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitAbrangenciaINT extends InfraINT
    {

        public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
        {
            $objMdLitAbrangenciaDTO = new MdLitAbrangenciaDTO();
            $objMdLitAbrangenciaDTO->retNumIdMdLitAbrangencia();
            $objMdLitAbrangenciaDTO->retStrNome();

            $objMdLitAbrangenciaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitAbrangenciaRN     = new MdLitAbrangenciaRN();
            $arrObjMdLitAbrangenciaDTO = $objMdLitAbrangenciaRN->listar($objMdLitAbrangenciaDTO);

            if(!$strValorItemSelecionado)
                $strValorItemSelecionado = InfraArray::converterArrInfraDTO($arrObjMdLitAbrangenciaDTO, 'IdMdLitAbrangencia');

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitAbrangenciaDTO, 'IdMdLitAbrangencia', 'Nome');
        }

        public static function montarCheckboxAbrangencia($arrIdAbrangencia)
        {
            $objMdLitAbrangenciaDTO = new MdLitAbrangenciaDTO();
            $objMdLitAbrangenciaDTO->retTodos();
            $objMdLitAbrangenciaRN     = new MdLitAbrangenciaRN();
            $arrObjMdLitAbrangenciaDTO = $objMdLitAbrangenciaRN->listar($objMdLitAbrangenciaDTO);

            $strCheckBox = '';

            foreach ($arrObjMdLitAbrangenciaDTO as $objMdLitAbrangenciaDTO) {
                $strValue    = $objMdLitAbrangenciaDTO->getNumIdMdLitAbrangencia();
                $strNome     = htmlentities($objMdLitAbrangenciaDTO->getStrNome());
                $strChecked  = in_array($objMdLitAbrangenciaDTO->getNumIdMdLitAbrangencia(), $arrIdAbrangencia) ? "checked='checked'" : '';
                $strCheckBox .= '<label class="infraLabelCheckbox checkbox-label" for="chkAbrangencia_' . $strValue . '">';
                $strCheckBox .= '<input type ="checkbox" name="chkAbrangencia[]" value="' . $strValue . '" class="infraCheckbox" id="chkAbrangencia_' . $strValue . '" ' . $strChecked . '/>';
                $strCheckBox .= $strNome . '</label><br/>';
            }

            return $strCheckBox;

        }
    }
