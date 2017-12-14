<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitProcessoSituacaoINT extends InfraINT {

  public static function montarSelectIdMdLitProcessoSituacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitSituacao='', $dblIdDocumento='', $dblIdProcedimento='', $numIdMdLitTipoControle=''){
    $objMdLitProcessoSituacaoDTO = new MdLitProcessoSituacaoDTO();
    $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();
    $objMdLitProcessoSituacaoDTO->retNumIdMdLitProcessoSituacao();

    if ($numIdMdLitSituacao!==''){
      $objMdLitProcessoSituacaoDTO->setNumIdMdLitSituacao($numIdMdLitSituacao);
    }

    if ($dblIdDocumento!==''){
      $objMdLitProcessoSituacaoDTO->setDblIdDocumento($dblIdDocumento);
    }

    if ($dblIdProcedimento!==''){
      $objMdLitProcessoSituacaoDTO->setDblIdProcedimento($dblIdProcedimento);
    }

    if ($numIdMdLitTipoControle!==''){
      $objMdLitProcessoSituacaoDTO->setNumIdMdLitTipoControle($numIdMdLitTipoControle);
    }

    if ($strValorItemSelecionado!=null){
      $objMdLitProcessoSituacaoDTO->setBolExclusaoLogica(false);
      $objMdLitProcessoSituacaoDTO->adicionarCriterio(array('SinAtivo','IdMdLitProcessoSituacao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdLitProcessoSituacaoDTO->setOrdNumIdMdLitProcessoSituacao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
    $arrObjMdLitProcessoSituacaoDTO = $objMdLitProcessoSituacaoRN->listar($objMdLitProcessoSituacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitProcessoSituacaoDTO, 'IdMdLitProcessoSituacao', 'IdMdLitProcessoSituacao');
  }

  public static function diferencaEntreDias($data1, $data2 = null){
      if(!$data2)
          $data2 = InfraData::getStrDataAtual();

      $data1 = InfraData::getTimestamp($data1);
      $data2 = InfraData::getTimestamp($data2);

      return ( $data2 - $data1 ) / 86400;
  }

  public static function validarNumeroSei($numeroSei, $idTpControle, $idProcedimento, $idDocAlterar){
    $xml = '';
    $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
    $dados = $objMdLitProcessoSituacaoRN->buscarDadosDocumento(array(false, $numeroSei, $idTpControle, $idProcedimento, $idDocAlterar));
    $erro  = $dados['erro'] == false ? '0' : '1';

    $xml  = '<Dados>';
    $xml .= '<Erro>' . $erro. '</Erro>';
    $xml .= '<Msg>' . $dados['msg'] . '</Msg>';

    if ($dados['erro'] == false) {
      $xml .= '<NumeroSei>' . $dados['numeroSei'] . '</NumeroSei>';
      $xml .= '<DtDocumento>' . $dados['dtDocumento'] . '</DtDocumento>';
      $xml .= '<TipoDocumento>' . $dados['tipoDocumento'] . '</TipoDocumento>';
      $xml .= '<IdDocumentoNumeroSei>'.$dados['idDocumento'].'</IdDocumentoNumeroSei>';
      $xml .= '<IdSerieNumeroSei>'.$dados['idSerie'].'</IdSerieNumeroSei>';
      $xml .= '<UrlDocumento>'.htmlentities($dados['urlValidada']).'</UrlDocumento>';
      $xml .= '<TituloDoc>'.$dados['tituloDoc'].'</TituloDoc>';
    }

    $xml .= '</Dados>';

    return $xml;
  }
  
  /*
   * Realiza as validações convencionais e verifica os vinculos do documento inserido com a situação salva anteriormente
   * */
  public static function validarVinculoNumeroSeiSituacao($numeroSei, $idTpControle, $idProcedimento, $idSituacao)
  {

    $xml = '';
    $objMdLitProcessoSituacaoRN = new MdLitProcessoSituacaoRN();
    $dados = $objMdLitProcessoSituacaoRN->buscarDadosDocumento(array(false, $numeroSei, false, $idProcedimento, '0'));

    if ($dados['erro'] == false) {
      $existeVinculo = $objMdLitProcessoSituacaoRN->verificarVinculoSituacaoDocumento(array($idSituacao, $dados['idSerie']));

      if (!$existeVinculo) {
        $dados['erro'] = true;
        $dados['msg'] = 'Este documento não está vinculado a está Situação.';
      }
    }

    $erro = $dados['erro'] == false ? '0' : '1';

    $xml = '<Dados>';
    $xml .= '<Erro>' . $erro . '</Erro>';
    $xml .= '<Msg>' . $dados['msg'] . '</Msg>';

    if ($dados['erro'] == false) {
      $xml .= '<NumeroSei>' . $dados['numeroSei'] . '</NumeroSei>';
      $xml .= '<DtDocumento>' . $dados['dtDocumento'] . '</DtDocumento>';
      $xml .= '<TipoDocumento>' . $dados['tipoDocumento'] . '</TipoDocumento>';
      $xml .= '<IdDocumentoNumeroSei>' . $dados['idDocumento'] . '</IdDocumentoNumeroSei>';
      $xml .= '<IdSerieNumeroSei>' . $dados['idSerie'] . '</IdSerieNumeroSei>';
      $xml .= '<UrlDocumento>'.htmlentities($dados['urlValidada']).'</UrlDocumento>';
      $xml .= '<TituloDoc>'.$dados['tituloDoc'].'</TituloDoc>';
    }

    $xml .= '</Dados>';

    return $xml;

  }
}
?>