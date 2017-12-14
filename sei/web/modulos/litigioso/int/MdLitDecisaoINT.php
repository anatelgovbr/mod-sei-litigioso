<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitDecisaoINT extends InfraINT {

  public static function montarSelectIdMdLitDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdLitProcessoSituacao='', $numIdMdLitRelDisNorConCtr='', $numIdMdLitObrigacao='', $numIdMdLitTipoDecisao='', $numIdMdLitEspecieDecisao='', $numIdUsuario='', $numIdUnidade=''){
    $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
    $objMdLitDecisaoDTO->retNumIdMdLitDecisao();
    $objMdLitDecisaoDTO->retNumIdMdLitDecisao();

    if ($numIdMdLitProcessoSituacao!==''){
      $objMdLitDecisaoDTO->setNumIdMdLitProcessoSituacao($numIdMdLitProcessoSituacao);
    }

    if ($numIdMdLitRelDisNorConCtr!==''){
      $objMdLitDecisaoDTO->setNumIdMdLitRelDisNorConCtr($numIdMdLitRelDisNorConCtr);
    }

    if ($numIdMdLitObrigacao!==''){
      $objMdLitDecisaoDTO->setNumIdMdLitObrigacao($numIdMdLitObrigacao);
    }

    if ($numIdMdLitTipoDecisao!==''){
      $objMdLitDecisaoDTO->setNumIdMdLitTipoDecisao($numIdMdLitTipoDecisao);
    }

    if ($numIdMdLitEspecieDecisao!==''){
      $objMdLitDecisaoDTO->setNumIdMdLitEspecieDecisao($numIdMdLitEspecieDecisao);
    }

    if ($numIdUsuario!==''){
      $objMdLitDecisaoDTO->setNumIdUsuario($numIdUsuario);
    }

    if ($numIdUnidade!==''){
      $objMdLitDecisaoDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($strValorItemSelecionado!=null){
      $objMdLitDecisaoDTO->setBolExclusaoLogica(false);
      $objMdLitDecisaoDTO->adicionarCriterio(array('SinAtivo','IdMdLitDecisao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objMdLitDecisaoDTO->setOrdNumIdMdLitDecisao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdLitDecisaoRN = new MdLitDecisaoRN();
    $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listar($objMdLitDecisaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdLitDecisaoDTO, 'IdMdLitDecisao', 'IdMdLitDecisao');
  }

  public static function gerarItensTabelaDinamicaForm($decisaoPosts){
      $infracaoArr = array();
      $index = 0;
      foreach ($decisaoPosts as $key => $decisao){

          if(($decisao['id_md_lit_tipo_decisao'] == 'null' || empty($decisao['id_md_lit_tipo_decisao'])))
              continue;

          if($decisao['id_decisao'] != ''){
              $infracaoArr[$index][] = $decisao['id_decisao'];
          }else{
              $infracaoArr[$index][] = 'novo_'.$index;
          }
          $infracaoArr[$index][] = $decisao['id'];
          $infracaoArr[$index][] = $decisao['id_md_lit_tipo_decisao'];
          $infracaoArr[$index][] = $decisao['id_md_lit_especie_decisao'];
          $infracaoArr[$index][] = $decisao['multa'];
          $infracaoArr[$index][] = $decisao['id_md_lit_obrigacao'];
          $infracaoArr[$index][] = $decisao['prazo'];
          $infracaoArr[$index][] = $decisao['id_usuario'] != ''? $decisao['id_usuario'] : SessaoSEI::getInstance()->getNumIdUsuario();
          $infracaoArr[$index][] = $decisao['id_unidade'] != ''? $decisao['id_unidade'] : SessaoSEI::getInstance()->getNumIdUnidadeAtual();

          $objRelDispositivoNormativoCondutaControleLitigiosoDTO = new MdLitRelDispositivoNormativoCondutaControleDTO();
          $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retTodos();
          $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrDispositivo();
          $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrNorma();
          $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retNumIdCondutaLitigioso();
          $objRelDispositivoNormativoCondutaControleLitigiosoDTO->retStrConduta();
          $objRelDispositivoNormativoCondutaControleLitigiosoDTO->setNumIdDispositivoNormativoNormaCondutaControle($decisao['id']);

          $objRelDispositivoNormativoCondutaControleLitigiosoRN     = new MdLitRelDispositivoNormativoCondutaControleRN();
          $objRelDispositivoNormativoCondutaControleLitigiosoDTO = $objRelDispositivoNormativoCondutaControleLitigiosoRN->consultar($objRelDispositivoNormativoCondutaControleLitigiosoDTO);
          $infracaoArr[$index][] = $objRelDispositivoNormativoCondutaControleLitigiosoDTO->getStrInfracao();

          $objMdLitTipoDecisaoDTO = new MdLitTipoDecisaoDTO();
          $objMdLitTipoDecisaoDTO->retTodos();
          $objMdLitTipoDecisaoDTO->setNumIdTipoDecisaoLitigioso($decisao['id_md_lit_tipo_decisao']);

          $objMdLitTipoDecisaoRN = new MdLitTipoDecisaoRN();
          $objMdLitTipoDecisaoDTO = $objMdLitTipoDecisaoRN->consultar($objMdLitTipoDecisaoDTO);
          $infracaoArr[$index][] = $objMdLitTipoDecisaoDTO->getStrNome();

          $objMdLitEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
          $objMdLitEspecieDecisaoDTO->retTodos();
          $objMdLitEspecieDecisaoDTO->setNumIdEspecieLitigioso($decisao['id_md_lit_especie_decisao']);

          $objMdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
          $objMdLitEspecieDecisaoDTO = $objMdLitEspecieDecisaoRN->consultar($objMdLitEspecieDecisaoDTO);
          $infracaoArr[$index][] = $objMdLitEspecieDecisaoDTO ? $objMdLitEspecieDecisaoDTO->getStrNome() : null;

          $infracaoArr[$index][] = $decisao['data'] != ''? $decisao['data'] : InfraData::getStrDataHoraAtual();
          $infracaoArr[$index][] = $decisao['nome_usuario'] != ''? $decisao['nome_usuario'] : SessaoSEI::getInstance()->getStrNomeUsuario();
          $infracaoArr[$index][] = $decisao['sigla_unidade'] != ''? $decisao['sigla_unidade'] : SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();
          $index++;

      }
      return PaginaSEI::getInstance()->gerarItensTabelaDinamica($infracaoArr);
  }

  public static function existeInfracao($idMdLitRelDisNorConCtr){
      $objMdLitDecisaoDTO = new MdLitDecisaoDTO();
      $objMdLitDecisaoDTO->retTodos(false);
      $objMdLitDecisaoDTO->setNumIdMdLitRelDisNorConCtr($idMdLitRelDisNorConCtr);

      $objMdLitDecisaoRN = new MdLitDecisaoRN();
      $arrObjMdLitDecisaoDTO = $objMdLitDecisaoRN->listar($objMdLitDecisaoDTO);

      if(count($arrObjMdLitDecisaoDTO))
          return '<existeInfracao value="S" />';

      return '<existeInfracao value="N" />';


  }
}
?>