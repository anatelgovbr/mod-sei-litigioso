<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 12/07/2018 - criado por ellyson.cast
*
* Vers�o do Gerador de C�digo: 1.41.0
*/

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitNumeroInteressadoINT extends InfraINT {

  public static function montarSelectIdMdLitNumeroInteressado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDadoInteressado=''){
    $objMdLitNumeroInteressadoDTO = new MdLitNumeroInteressadoDTO();
    $objMdLitNumeroInteressadoDTO->retNumIdMdLitNumeroInteressado();
    $objMdLitNumeroInteressadoDTO->retNumIdMdLitNumeroInteressado();

    if ($numIdMdLitDadoInteressado!==''){
      $objMdLitNumeroInteressadoDTO->setNumIdMdLitDadoInteressado($numIdMdLitDadoInteressado);
    }

    $objMdLitNumeroInteressadoDTO->setOrdNumIdMdLitNumeroInteressado(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitNumeroInteressadoRN = new MdLitNumeroInteressadoRN();
    $arrObjMdLitNumeroInteressadoDTO = $objMdLitNumeroInteressadoRN->listar($objMdLitNumeroInteressadoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitNumeroInteressadoDTO, 'IdMdLitNumeroInteressado', 'IdMdLitNumeroInteressado');
  }
}
