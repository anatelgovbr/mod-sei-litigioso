<?
/**
 * ANATEL
 *
 * 25/04/2017 - criado por Ellyson de Jesus Silva
 *
 * Versão do Gerador de Código: 1.32.1
 *
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitNomeFuncionalINT extends InfraINT {

    public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
        $objMdLitNomeFuncionalDTO = new MdLitNomeFuncionalDTO();
        $objMdLitNomeFuncionalDTO->retNumIdMdLitNomeFuncional();
        $objMdLitNomeFuncionalDTO->retStrNome();

        $objMdLitNomeFuncionalDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdlitNomeFuncionalRN = new MdLitNomeFuncionalRN();
        $arrObjMdLitNomeFuncionalDTO = $objMdlitNomeFuncionalRN->listar($objMdLitNomeFuncionalDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitNomeFuncionalDTO, 'IdMdLitNomeFuncional', 'Nome');
  }
}
?>