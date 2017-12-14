<?
/**
* ANATEL
*
* 24/04/2017 - criado por Ellyson de Jesus Silva
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitMapearParamEntradaINT extends InfraINT {

  public static function montarSelectIdMdLitIntegracao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitIntegracao='', $numIdMdLitNomeFuncional=''){
    $objMdLitMapearParamEntradaDTO = new MdLitMapearParamEntradaDTO();
    $objMdLitMapearParamEntradaDTO->retNumIdMdLitMapearParamEntrada();
    $objMdLitMapearParamEntradaDTO->retNumIdMdLitIntegracao();

    if ($numIdMdLitIntegracao!==''){
      $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($numIdMdLitIntegracao);
    }

    if ($numIdMdLitNomeFuncional!==''){
      $objMdLitMapearParamEntradaDTO->setNumIdMdLitNomeFuncional($numIdMdLitNomeFuncional);
    }

    $objMdLitMapearParamEntradaDTO->setOrdNumIdMdLitIntegracao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
    $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitMapearParamEntradaDTO, 'IdMdLitMapearParamEntrada', 'IdMdLitIntegracao');
  }

  public static function montarTabelaParamEntrada($objMdLitSoapClienteRN, $idMdLitFuncionalidade, $operacao, $idMdLitIntegracao = null){
      $objMdLitMapearParamEntradaDTO    = new MdLitMapearParamEntradaDTO();
      $arrParametroEntrada              = $objMdLitSoapClienteRN->getParamsInput($operacao);
      $strResultadoParamEntrada         = '';
      $arrObjMdLitMapearParamEntradaDTO = array();

      if(!empty($idMdLitIntegracao)){
          $objMdLitMapearParamEntradaRN = new MdLitMapearParamEntradaRN();
          $objMdLitMapearParamEntradaDTO->retTodos();

          $objMdLitMapearParamEntradaDTO->setNumIdMdLitIntegracao($idMdLitIntegracao);
          $objMdLitMapearParamEntradaDTO->setOrdStrCampo(InfraDTO::$TIPO_ORDENACAO_ASC);

          $arrObjMdLitMapearParamEntradaDTO = $objMdLitMapearParamEntradaRN->listar($objMdLitMapearParamEntradaDTO);
      }

      $numRegistrosParametroEntrada = count($arrParametroEntrada);
      if($numRegistrosParametroEntrada > 0){
          $strSumarioTabela = 'Tabela de configuração dos dados de entrada do web-service.';
          $strCaptionTabela = 'Dados de entrada';

          $strResultadoParamEntrada .= '<table width="90%" id="tableParametroEntrada" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
          $strResultadoParamEntrada .= '<tr>';

          $strResultadoParamEntrada .= '<th class="infraTh" width="20%">&nbsp;Dados de Entrada&nbsp;</th>' . "\n";
          $strResultadoParamEntrada .= '<th class="infraTh" width="20%">&nbsp;Campo de Origem no SEI&nbsp;</th>' . "\n";
          $strResultadoParamEntrada .= '<th class="infraTh" width="5%">&nbsp;Chave Única da Integração&nbsp;</th>' . "\n";
          $strResultadoParamEntrada .= '</tr>' . "\n";
          $strCssTr = '';
          for ($i = 0; $i < $numRegistrosParametroEntrada; $i++) {
              if($idMdLitFuncionalidade == 1) {
                  $strItensSelNomeFuncional = MdLitNomeFuncionalINT::montarSelectNome('null', '&nbsp;', null);
              }else{
                  $strItensSelNomeFuncional = MdLitCampoIntegracaoINT::montarSelectCampoIntergracao('null', '&nbsp;', $idMdLitFuncionalidade, 'E');
              }

              $idLinha = $i;

              $strCssTr = '<tr id="paramEntradaTable_' . $idLinha . '" class="infraTrClara">';
              $disable = 'disabled="disabled"';
              $checked = '';
              $tabelaCodigoReceita = '';

              if(count($arrObjMdLitMapearParamEntradaDTO)> 0){
                  for ($j = 0; $j < count($arrObjMdLitMapearParamEntradaDTO); $j++){
                      if($arrObjMdLitMapearParamEntradaDTO[$j]->getStrCampo() == $arrParametroEntrada[$i] ){
                          $disable = '';
                          if($idMdLitFuncionalidade == 1){
                            $strItensSelNomeFuncional = MdLitNomeFuncionalINT::montarSelectNome('null','&nbsp;',$arrObjMdLitMapearParamEntradaDTO[$j]->getNumIdMdLitNomeFuncional());
                          }else{
                              $strItensSelNomeFuncional = MdLitCampoIntegracaoINT::montarSelectCampoIntergracao('null', '&nbsp;', $idMdLitFuncionalidade, 'E', $arrObjMdLitMapearParamEntradaDTO[$j]->getNumIdMdLitCampoIntegracao());
                          }
                          $checked = $arrObjMdLitMapearParamEntradaDTO[$j]->getStrChaveUnica() == 'S'?'checked="checked"': '';

                          if($arrObjMdLitMapearParamEntradaDTO[$j]->getNumIdMdLitCampoIntegracao() == 5){
                              $tabelaCodigoReceita = MdLitIntegracaoINT::montarTabelaCodigoReceita($arrObjMdLitMapearParamEntradaDTO[$j]->getNumIdMdLitMapearParamEntrada());
                          }

                      }
                  }
              }

              $strResultadoParamEntrada .= $strCssTr;
              $strResultadoParamEntrada .= "<td id='campo_$idLinha>";
              $strResultadoParamEntrada .= "<input type='hidden' name='hdnArrayDadosEntrada[$i]' value='{$arrParametroEntrada[$i]}' />";
              $strResultadoParamEntrada .= PaginaSEI::tratarHTML($arrParametroEntrada[$i]);
              $strResultadoParamEntrada .= "</td>";
              $strResultadoParamEntrada .= "<td align='center'><select id='nomeFuncionalDadosEntrada_$i' class='infraSelect' name='nomeFuncionalDadosEntrada[$arrParametroEntrada[$i]]' onchange='mudarNomeFuncionalDadosEntrada(this), verificarCodigoReceita(this)' style='width: 80%;'>{$strItensSelNomeFuncional}</select>";
              $strResultadoParamEntrada.= $tabelaCodigoReceita."</td>";
              $strResultadoParamEntrada .= "<td align='center'><input type='radio'name='chaveUnicaDadosEntrada' value='$arrParametroEntrada[$i]' id='chaveUnicaDadosEntrada_{$idLinha}' $disable $checked> </td>";

              $strResultadoParamEntrada .= '</tr>' . "\n";
          }
          $strResultadoParamEntrada .= '</table>';
      }

      return array('strResultado' => $strResultadoParamEntrada,'numRegistros'=> $numRegistrosParametroEntrada );
  }

}
?>