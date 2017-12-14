<?php
    /**
     * ANATEL
     *
     * 23/02/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitFaseINT extends InfraINT
    {

        public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $idTipoControle)
        {

            $objFaseLitigiosoDTO = new MdLitFaseDTO ();
            $objFaseLitigiosoDTO->retNumIdFaseLitigioso();
            $objFaseLitigiosoDTO->retStrNome();

            $objFaseLitigiosoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objFaseLitigiosoDTO->setNumIdTipoControleLitigioso($idTipoControle);
            $objFaseLitigiosoDTO->setStrSinAtivo('S');

            $objFaseLitigiosoRN = new MdLitFaseRN();
            $arrObjFaseDTO      = $objFaseLitigiosoRN->listar($objFaseLitigiosoDTO);

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjFaseDTO, 'IdFaseLitigioso', 'Nome');
        }

        public static function montarSelectNomeDisplayNone($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $idTipoControle)
        {

            $objMdLitSituacaoDTO = new MdLitSituacaoDTO();
            $objMdLitSituacaoDTO->retNumIdFaseLitigioso();
            $objMdLitSituacaoDTO->retStrNomeFase();

            $objMdLitSituacaoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objMdLitSituacaoDTO->setNumIdTipoControleLitigioso($idTipoControle);
            $objMdLitSituacaoDTO->setStrSinAtivo('S');
            $objMdLitSituacaoDTO->setDistinct(true);

            $objMdLitSituacaoRN = new MdLitSituacaoRN();
            $arrObjMdLitSituacaoDTO      = $objMdLitSituacaoRN->listar($objMdLitSituacaoDTO);

            $html = '<option class="optSelFase" style="display:none" selected="selected" value=""> </option>';
            foreach($arrObjMdLitSituacaoDTO as $objMdLitSituacaoDTO){
                $html .= '<option class="optSelFase" style="display:none" ';
                $html .= ' value="'.$objMdLitSituacaoDTO->getNumIdFaseLitigioso().'">';
                $html .= $objMdLitSituacaoDTO->getStrNomeFase().' </option>';
            }

            return $html;
         }

    }
