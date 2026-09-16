<?
/**
* ANATEL
*
* 06/02/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitRelDispositivoNormativoRevogadoINT extends InfraINT {

  public static function montarSelectIdMdLitDispNormat($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitDispNormat='', $numIdMdLitDispNormatRevogado=''){
    $objMdLitRelDispNormRevogadoDTO = new MdLitRelDispNormRevogadoDTO();
    $objMdLitRelDispNormRevogadoDTO->retNumIdMdLitDispNormat();
    $objMdLitRelDispNormRevogadoDTO->retNumIdMdLitDispNormatRevogado();
    $objMdLitRelDispNormRevogadoDTO->retNumIdMdLitDispNormat();

    if ($numIdMdLitDispNormat!==''){
      $objMdLitRelDispNormRevogadoDTO->setNumIdMdLitDispNormat($numIdMdLitDispNormat);
    }

    if ($numIdMdLitDispNormatRevogado!==''){
      $objMdLitRelDispNormRevogadoDTO->setNumIdMdLitDispNormatRevogado($numIdMdLitDispNormatRevogado);
    }

    $objMdLitRelDispNormRevogadoDTO->setOrdNumIdMdLitDispNormat(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitRelDispNormRevogadoRN = new MdLitRelDispNormRevogadoRN();
    $arrObjMdLitRelDispNormRevogadoDTO = $objMdLitRelDispNormRevogadoRN->listar($objMdLitRelDispNormRevogadoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitRelDispNormRevogadoDTO, array('IdMdLitDispNormat','IdMdLitDispNormatRevogado'), 'IdMdLitDispNormat');
  }

  public static function montarItemSelecionado($idDispositivoNormativo){

      $objMdLitRelDispositivoNormativoRevogadoDTO = new MdLitRelDispositivoNormativoRevogadoDTO();
      $objMdLitRelDispositivoNormativoRevogadoDTO->retTodos(true);
      $objMdLitRelDispositivoNormativoRevogadoDTO->setNumIdMdLitDispositivoNormativo($idDispositivoNormativo);


      $objMdLitRelDispositivoNormativoRevogadoRN = new MdLitRelDispositivoNormativoRevogadoRN();
      $arrDispositoRevogado                      = $objMdLitRelDispositivoNormativoRevogadoRN->listar($objMdLitRelDispositivoNormativoRevogadoDTO);
      $strItensSelRevogarDispositivo = '';
      for ($x = 0; $x < count($arrDispositoRevogado); $x++) {

          $strItensSelRevogarDispositivo .= "<option value='" . $arrDispositoRevogado[$x]->getNumIdMdLitDispositivoNormativoRevogado() . "'>" . $arrDispositoRevogado[$x]->getStrNormaRevogado() . ' - '. $arrDispositoRevogado[$x]->getStrDispositivoRevogado() . "</option>";
      }
      return $strItensSelRevogarDispositivo;
  }

  public static function montarRevogadoPor($idDispositivoNormativo){

      $objMdLitRelDispositivoNormativoRevogadoDTO = new MdLitRelDispositivoNormativoRevogadoDTO();
      $objMdLitRelDispositivoNormativoRevogadoDTO->retTodos(true);
      $objMdLitRelDispositivoNormativoRevogadoDTO->setNumIdMdLitDispositivoNormativoRevogado($idDispositivoNormativo);


      $objMdLitRelDispositivoNormativoRevogadoRN = new MdLitRelDispositivoNormativoRevogadoRN();
      $arrDispositoRevogado                      = $objMdLitRelDispositivoNormativoRevogadoRN->listar($objMdLitRelDispositivoNormativoRevogadoDTO);
      $strItensSelRevogarDispositivo             = '';
      for ($x = 0; $x < count($arrDispositoRevogado); $x++) {
          $strItensSelRevogarDispositivo .= $arrDispositoRevogado[$x]->getStrNormaRevogado() . ' - '. $arrDispositoRevogado[$x]->getStrDispositivoRevogado() . " ";
      }
      return $strItensSelRevogarDispositivo;
  }
}
