<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/04/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMotivoINT extends InfraINT {

  public static function montarSelectIdMdLitMotivo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdLitMotivoDTO = new MdLitMotivoDTO();
    $objMdLitMotivoDTO->retNumIdMdLitMotivo();
    $objMdLitMotivoDTO->retNumIdMdLitMotivo();

    if ($strValorItemSelecionado!=null){
      $objMdLitMotivoDTO->setBolExclusaoLogica(false);
      $objMdLitMotivoDTO->adicionarCriterio(array('SinAtivo','IdMdLitMotivo'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdLitMotivoDTO->setOrdNumIdMdLitMotivo(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitMotivoRN = new MdLitMotivoRN();
    $arrObjMdLitMotivoDTO = $objMdLitMotivoRN->listar($objMdLitMotivoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitMotivoDTO, 'IdMdLitMotivo', 'IdMdLitMotivo');
  }

  public static function consultarMotivoAjax($dados){


      $mdLitMotivoDTO = new MdLitMotivoDTO();
      $mdLitMotivoRN = new MdLitMotivoRN();

      // Retorna somentes os motivos relacionados com o tipo de controle solicitado
      if(isset($dados['idTipoControle'])&& $dados['idTipoControle'] >0){
          $idTipoControle = $dados['idTipoControle'];
          $mdLitRelTpControlMotiDTO = new MdLitRelTpControlMotiDTO();
          $mdLitRelTpControlMotiRN = new MdLitRelTpControlMotiRN();
          $mdLitRelTpControlMotiDTO->setNumIdMdLitTipoControle($idTipoControle);
          $mdLitRelTpControlMotiDTO->retTodos();
          $mdLitRelTpControlMoti = $mdLitRelTpControlMotiRN->listar($mdLitRelTpControlMotiDTO);

          if(count($mdLitRelTpControlMoti) == 0){
              return null;
          }
          for($i =0 ; $i < count($mdLitRelTpControlMoti) ; $i++){
              $arrIds[]=$mdLitRelTpControlMoti[$i]->getNumIdMdLitMotivo();
          }
          $mdLitMotivoDTO->setNumIdMdLitMotivo($arrIds,InfraDTO::$OPER_IN);
      }


      $mdLitMotivoDTO->setStrDescricao('%'.$dados['palavras_pesquisa'].'%',InfraDTO::$OPER_LIKE);
      $mdLitMotivoDTO->retTodos();
      $mdLitMotivo = $mdLitMotivoRN->listar($mdLitMotivoDTO);

      $xml='<itens>';
      foreach ($mdLitMotivo as $motivo){
          $xml.='<IdMotivo>'.$motivo->getNumIdMdLitMotivo().'</IdMotivo>';
          $xml.='<descricao>'.$motivo->getStrDescricao().'</descricao>';
      }
      $xml .='</itens>';
      return $mdLitMotivo;
  }
}
