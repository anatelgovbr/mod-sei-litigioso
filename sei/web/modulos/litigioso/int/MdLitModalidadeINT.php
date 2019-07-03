<?
    /**
     * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
     *
     * 03/04/2017 - criado por Ellyson de Jesus Silva
     *
     * Versão do Gerador de Código: 1.40.1
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitModalidadeINT extends InfraINT
    {

        public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
        {
            $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
            $objMdLitModalidadeDTO->retNumIdMdLitModalidade();
            $objMdLitModalidadeDTO->retStrNome();

            $objMdLitModalidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMdLitModalidadeRN     = new MdLitModalidadeRN();
            $arrObjMdLitModalidadeDTO = $objMdLitModalidadeRN->listar($objMdLitModalidadeDTO);

            if(!$strValorItemSelecionado)
                $strValorItemSelecionado = InfraArray::converterArrInfraDTO($arrObjMdLitModalidadeDTO, 'IdMdLitModalidade');

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitModalidadeDTO, 'IdMdLitModalidade', 'Nome');
        }

        public static function montarCheckboxModalidade2($arrIdModalidade = array())
        {
            $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
            $objMdLitModalidadeDTO->retTodos();
            $objMdLitModalidadeRN     = new MdLitModalidadeRN();
            $arrObjMdLitModalidadeDTO = $objMdLitModalidadeRN->listar($objMdLitModalidadeDTO);

            $strCheckBox = '';

            foreach ($arrObjMdLitModalidadeDTO as $objMdLitModalidadeDTO) {
                $strValue    = $objMdLitModalidadeDTO->getNumIdMdLitModalidade();
                $strNome     = htmlentities($objMdLitModalidadeDTO->getStrNome());
                $strChecked = in_array($objMdLitModalidadeDTO->getNumIdMdLitModalidade(), $arrIdModalidade) ? "checked='checked'" : '';
                $strCheckBox .= '<label class="infraLabelCheckbox checkbox-label" for="chkModalidade_' . $strValue . '">';
                $strCheckBox .= '<input type ="checkbox" name="chkModalidade[]" value="' . $strValue . '" class="infraCheckbox" id="chkModalidade_' . $strValue . '"' . $strChecked . '/>';
                $strCheckBox .= $strNome . '</label><br/>';
            }
echo $strCheckBox;
            return $strCheckBox;
        }



        public static function montarCheckboxModalidade($sinObrigatorio='N', $arrIdModalidade = array(), $funcaoJS = '' , $nameInput='rdoModalidade[]', $nomeId='chkTipoModalidade_')
        {
            $objMdLitModalidadeDTO = new MdLitModalidadeDTO();
            $objMdLitModalidadeDTO->retTodos();
            $objMdLitModalidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objMdLitModalidadeRN     = new MdLitModalidadeRN();
            $arrObjMdLitModalidadeDTO = $objMdLitModalidadeRN->listar($objMdLitModalidadeDTO);

            $strCheckBox = '';
            $classSinObrigatorio = $sinObrigatorio == 'S'? 'infraLabelObrigatorio': '';

            foreach ($arrObjMdLitModalidadeDTO as $objMdLitModalidadeDTO) {
                $strValue    = $objMdLitModalidadeDTO->getNumIdMdLitModalidade();
                $strNome     = htmlentities($objMdLitModalidadeDTO->getStrNome());
                $nomeId      .= $strValue;
                $strChecked  = in_array($objMdLitModalidadeDTO->getNumIdMdLitModalidade(), $arrIdModalidade) ? "checked='checked'" : '';
                $strCheckBox .= '<div class="infraDivCheckbox"><label class="infraLabelCheckbox checkbox-label '.$classSinObrigatorio.'" for="' . $nomeId . '">';
                $strCheckBox .= '<input type ="checkbox" name="'.$nameInput.'" value="' . $strValue . '" class="infraCheckbox" id="' . $nomeId . '"' . $strChecked . ' '.$funcaoJS.'/> ';
                $strCheckBox .= $strNome . '</label></div><br>';
            }
            return $strCheckBox;
        }


    }
