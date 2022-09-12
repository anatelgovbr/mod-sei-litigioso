<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versгo do Gerador de Cуdigo: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitFuncionalidadeINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $idMdLitIntegracao,$filtroDuplicidade = true){

      $objMdLitFuncionalidadeDTO = new MdLitFuncionalidadeDTO();

      $objMdLitFuncionalidadeDTO->retNumIdMdLitFuncionalidade();
      $objMdLitFuncionalidadeDTO->retStrNome();
      $objMdLitFuncionalidadeDTO->retNumIdMdLitIntegracao();
      $objMdLitFuncionalidadeDTO->retStrNomeMdLitIntegracao();

      $objMdLitFuncionalidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      //trazer apenas funcionalidades que ainda nao estejam em uso por nenhuma integraзao
      //se jб tiver Integraзгo para determinada funcionalidade, ela nгo deve mais ser exibida no cadastro de uma nova.
      if($filtroDuplicidade && !empty($idMdLitIntegracao)){
          $objMdLitFuncionalidadeDTO->adicionarCriterio(array('IdMdLitIntegracao','IdMdLitIntegracao'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array(null, $idMdLitIntegracao), InfraDTO::$OPER_LOGICO_OR);
      }elseif($filtroDuplicidade){
          $objMdLitFuncionalidadeDTO->adicionarCriterio(array('IdMdLitIntegracao'), array(InfraDTO::$OPER_IGUAL), array(null));
      }


    $objMdLitFuncionalidadeRN = new MdLitFuncionalidadeRN();
    $arrObjMdLitFuncionalidadeDTO = $objMdLitFuncionalidadeRN->listar($objMdLitFuncionalidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitFuncionalidadeDTO, 'IdMdLitFuncionalidade', 'Nome');
  }
}
?>