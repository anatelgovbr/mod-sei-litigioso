<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/04/2018 - criado por ellyson.silva
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitReincidenAntecedenINT extends InfraINT {

  public static function montarSelectIdMdLitReincidenAnteceden($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMdLitReincidenAntecedenDTO = new MdLitReincidenAntecedenDTO();
    $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();
    $objMdLitReincidenAntecedenDTO->retNumIdMdLitReincidenAnteceden();

    $objMdLitReincidenAntecedenDTO->setOrdNumIdMdLitReincidenAnteceden(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitReincidenAntecedenRN = new MdLitReincidenAntecedenRN();
    $arrObjMdLitReincidenAntecedenDTO = $objMdLitReincidenAntecedenRN->listar($objMdLitReincidenAntecedenDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitReincidenAntecedenDTO, 'IdMdLitReincidenAnteceden', 'IdMdLitReincidenAnteceden');
  }

  public static function tratarOrientacaoReincidencia(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO){
      if(!$objMdLitReincidenAntecedenDTO)
          return null;

      $orientacao = $objMdLitReincidenAntecedenDTO->getStrOrientacao();
      $nomeTipoDecisao = '';
      $definicaoInfracao = '';
      $prazoAnos = $objMdLitReincidenAntecedenDTO->getNumPrazo();

      $prazoAnos .= $objMdLitReincidenAntecedenDTO->getNumPrazo() > 1 ?' anos' :' ano';
      switch ($objMdLitReincidenAntecedenDTO->getStrTipoRegraReincidencia() ){
          case MdLitReincidenAntecedenRN::$CONDUTA:
              $definicaoInfracao = 'mesma Conduta';
              break;
          case MdLitReincidenAntecedenRN::$DISPOSITIVO:
              $definicaoInfracao = 'mesmo Dispositivo Normativo';
              break;
          case MdLitReincidenAntecedenRN::$DISPOSITIVO_CONDUTA:
              $definicaoInfracao = 'mesmo Dispositivo Normativo e Conduta';
              break;
      }

      if($objMdLitReincidenAntecedenDTO->isSetArrObjMdLitRelTpDecReinAnteDTO()){
          $arrNomeTipoDecisao = InfraArray::converterArrInfraDTO($objMdLitReincidenAntecedenDTO->getArrObjMdLitRelTpDecReinAnteDTO(), 'NomeTipoDecisao');
          $nomeTipoDecisao = InfraString::formatarArray($arrNomeTipoDecisao,', ', ' ou ');
      }

      $orientacao = str_replace('@prazo_anos_reincidencia_especifica@', $prazoAnos,$orientacao);
      $orientacao = str_replace('@definicao_infracao_mesma_natureza_reincidencia_especifica@', $definicaoInfracao,$orientacao);
      $orientacao = str_replace('@tipos_decisao_reincidencia_especifica@', $nomeTipoDecisao,$orientacao);
      return $orientacao;
  }

    public static function tratarOrientacaoAntecendente(MdLitReincidenAntecedenDTO $objMdLitReincidenAntecedenDTO){
        if(!$objMdLitReincidenAntecedenDTO)
            return null;

        $orientacao = $objMdLitReincidenAntecedenDTO->getStrOrientacao();
        $nomeTipoDecisao = '';
        $prazoAnos = $objMdLitReincidenAntecedenDTO->getNumPrazo();
        $prazoAnos .= $objMdLitReincidenAntecedenDTO->getNumPrazo() > 1 ?' anos' :' ano';

        if($objMdLitReincidenAntecedenDTO->isSetArrObjMdLitRelTpDecReinAnteDTO()){
            $arrNomeTipoDecisao = InfraArray::converterArrInfraDTO($objMdLitReincidenAntecedenDTO->getArrObjMdLitRelTpDecReinAnteDTO(), 'NomeTipoDecisao');
            $nomeTipoDecisao = InfraString::formatarArray($arrNomeTipoDecisao,', ', ' ou ');
        }

        $orientacao = str_replace('@prazo_anos_antecedente@', $prazoAnos,$orientacao);
        $orientacao = str_replace('@tipos_decisao_antecedente@', $nomeTipoDecisao,$orientacao);
        return $orientacao;
    }
}
