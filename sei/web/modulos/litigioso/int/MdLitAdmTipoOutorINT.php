<?
    /**
     * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
     *
     * 10/05/2019 - criado por Ellyson de Jesus Silva
     *
     * Versão do Gerador de Código: 1.40.1
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitAdmTipoOutorINT extends InfraINT
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

        public static function montarCheckboxTipoOutorga($sinObrigatorio='N', $arrIdTipoOutorga = array(), $funcaoJS = '' , $nameInput='rdoAbrangencia[]', $nomeId = 'chkTipoOutorga_')
        {
            $objMdLitAdmTipoOutorDTO = new MdLitAdmTipoOutorDTO();
            $objMdLitAdmTipoOutorDTO->retTodos();
            $objMdLitAdmTipoOutorDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objMdLitAdmTipoOutorRN     = new MdLitAdmTipoOutorRN();
            $arrObjMdLitAdmTipoOutorDTO = $objMdLitAdmTipoOutorRN->listar($objMdLitAdmTipoOutorDTO);
            $classSinObrigatorio = $sinObrigatorio == 'S'? 'infraLabelObrigatorio': '';

            $strCheckBox = '';

            foreach ($arrObjMdLitAdmTipoOutorDTO as $objMdLitAdmTipoOutorDTO) {
                $strValue    = $objMdLitAdmTipoOutorDTO->getNumIdMdLitAdmTipoOutor();
                $nomeId      .= $strValue;
                $strNome     = htmlentities($objMdLitAdmTipoOutorDTO->getStrNome());
                $strChecked = in_array($objMdLitAdmTipoOutorDTO->getNumIdMdLitAdmTipoOutor(), $arrIdTipoOutorga) ? "checked='checked'" : '';
                $strCheckBox .= '<div class="infraDivCheckbox"><label class="infraLabelCheckbox checkbox-label '.$classSinObrigatorio.'" for="' . $nomeId . '">';
                $strCheckBox .= '<input type ="checkbox" name="'.$nameInput.'" value="' . $strValue . '" class="infraCheckbox" id="' . $nomeId . '"' . $strChecked . ' '.$funcaoJS.'/> ';
                $strCheckBox .= $strNome . '</label></div>';
            }
            return $strCheckBox;
        }


    }
