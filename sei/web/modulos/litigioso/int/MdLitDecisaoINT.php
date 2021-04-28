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
      $localidade = null;
      $selectUf = null;


      foreach ($decisaoPosts as $key => $decisao){

//          if(($decisao['id_md_lit_tipo_decisao'] == 'null' || empty($decisao['id_md_lit_tipo_decisao'])))
//              continue;

          if($decisao['id_decisao'] != ''){
              $infracaoArr[$index][] = $decisao['id_decisao'];
          }else{
              $infracaoArr[$index][] = 'novo_'.$index;
          }
          $infracaoArr[$index][] = $decisao['id'];
          $infracaoArr[$index][] = $decisao['id_md_lit_tipo_decisao'];
          $infracaoArr[$index][] = $decisao['id_md_lit_especie_decisao'];
          $infracaoArr[$index][] = $decisao['multa'];
          $infracaoArr[$index][] = $decisao['valor_ressarcimento'];
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
          if($objMdLitTipoDecisaoDTO){
              $infracaoArr[$index][] = $objMdLitTipoDecisaoDTO->getStrNome();
          }else{
              $infracaoArr[$index][] = null;
          }

          $objMdLitEspecieDecisaoDTO = new MdLitEspecieDecisaoDTO();
          $objMdLitEspecieDecisaoDTO->retTodos();
          $objMdLitEspecieDecisaoDTO->setNumIdEspecieLitigioso($decisao['id_md_lit_especie_decisao']);

          $objMdLitEspecieDecisaoRN = new MdLitEspecieDecisaoRN();
          $objMdLitEspecieDecisaoDTO = $objMdLitEspecieDecisaoRN->consultar($objMdLitEspecieDecisaoDTO);
          $infracaoArr[$index][] = $objMdLitEspecieDecisaoDTO ? $objMdLitEspecieDecisaoDTO->getStrNome() : null;

          $infracaoArr[$index][] = $decisao['data'] != ''? $decisao['data'] : InfraData::getStrDataAtual();
          $nomeUsuario = $decisao['nome_usuario'] != ''? strip_tags($decisao['nome_usuario']) : SessaoSEI::getInstance()->getStrNomeUsuario();
          $siglaUsuario = $decisao['sigla_usuario'] != ''? strip_tags($decisao['sigla_usuario']) : SessaoSEI::getInstance()->getStrSiglaUsuario();
          $infracaoArr[$index][] = '<a alt="'.$nomeUsuario.'" title="'.$nomeUsuario.'" class="ancoraSigla"> '.$siglaUsuario.' </a>';
          $siglaUnidade = $decisao['sigla_unidade'] != ''? strip_tags($decisao['sigla_unidade']) : SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();
          $descricaoUnidade = $decisao['descricao_unidade'] != ''? strip_tags($decisao['descricao_unidade']) : SessaoSEI::getInstance()->getStrDescricaoUnidadeAtual();
          $infracaoArr[$index][] = '<a alt="'.$descricaoUnidade.'" title="'.$descricaoUnidade.'" class="ancoraSigla"> '.$siglaUnidade.' </a>';
          $localidade = $decisao['localidade'] == null ? $localidade : $decisao['localidade'] ;
          if($localidade == MdLitDecisaoRN::$STA_LOCALIDADE_UF){
              $selectUf = $decisao['id_uf'] ? implode("#",$decisao['id_uf']) : $selectUf;
              if($selectUf == null){
                  $decisao['sin_cadastro_parcial'] = 'S';
              }
          }else{
              $selectUf = '';
          }



          $infracaoArr[$index][] = $localidade;
          $infracaoArr[$index][] = $selectUf;
          $infracaoArr[$index][] = $decisao['sin_cadastro_parcial'];
          $index++;
      }
      return PaginaSEI::getInstance()->gerarItensTabelaDinamica($infracaoArr, false);
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

  public static function montarSelectCondutaPorArrMdLitDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arrObjMdLitDecisaoDTO){
      if(count($arrObjMdLitDecisaoDTO) == 0){
          return null;
      }
      $arrObjMdLitDecisaoDTO = InfraArray::distinctArrInfraDTO($arrObjMdLitDecisaoDTO,'IdCondutaMdLitRelDisNorConCtr');
      //retirando os dados da decisão que não possui conduta
      $arrObjMdLitDecisaoDTO = InfraArray::retirarElementoArrInfraDTO($arrObjMdLitDecisaoDTO, 'Conduta',null);
      return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arrObjMdLitDecisaoDTO, 'IdCondutaMdLitRelDisNorConCtr', 'Conduta');
  }

    public static function montarSelectDispositivoPorArrMdLitDecisao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arrObjMdLitDecisaoDTO){
        if(count($arrObjMdLitDecisaoDTO) == 0){
            return null;
        }
        $arrObjMdLitDecisaoDTO = InfraArray::distinctArrInfraDTO($arrObjMdLitDecisaoDTO,'IdDispositivoNormativoMdLitRelDisNorConCtr');
        //retirando os dados da decisão que não possui conduta
        $arrObjMdLitDecisaoDTO = InfraArray::retirarElementoArrInfraDTO($arrObjMdLitDecisaoDTO, 'Dispositivo',null);
        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $varValorItemSelecionado, $arrObjMdLitDecisaoDTO, 'IdDispositivoNormativoMdLitRelDisNorConCtr', 'DispositivoNormativo');
    }


    public static function converterParaArrExcelInfraDTO($arrObjDTO, $inicioLinha = 1){
        $arrAlfabeto = array('A','B','C','D','E','F','G', 'H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $arrRetorno = array();
        //obs : a key não foi reutilizada, pois a pesquisa  não segue uma ordem correta .

        $arrCabecalho = array('Processo', 'Interessado', 'CNPJ/CPF', 'Dispositivo', 'Norma', 'Conduta', 'Data do Trânsito Julgado');

        //Cabecalho
        $contador = 0;
        foreach($arrCabecalho as $label){
            $arrRetorno[1][$arrAlfabeto[$contador]] = $label;
            $contador++;
        }

        //Dados
        $contador = 0;
        foreach($arrObjDTO as $objDTO){
            $linhaExcel = $contador + $inicioLinha + 1;
            $arrRetorno[$linhaExcel][$arrAlfabeto[0]] = $objDTO->getStrProtocoloFormatadoProcedimento();
            $arrRetorno[$linhaExcel][$arrAlfabeto[1]] = $objDTO->getStrNomeContato();

            if(!empty($objDTO->getStrCpfContato())){
                $arrRetorno[$linhaExcel][$arrAlfabeto[2]] = InfraUtil::formatarCpfCnpj($objDTO->getStrCpfContato());
            }else{
                $arrRetorno[$linhaExcel][$arrAlfabeto[2]] = InfraUtil::formatarCpfCnpj($objDTO->getStrCnpjContato());
            }
            $arrRetorno[$linhaExcel][$arrAlfabeto[3]] = $objDTO->getStrDispositivo();
            $arrRetorno[$linhaExcel][$arrAlfabeto[4]] = $objDTO->getStrNorma();
            $arrRetorno[$linhaExcel][$arrAlfabeto[5]] = $objDTO->getStrConduta();
            $arrRetorno[$linhaExcel][$arrAlfabeto[6]] = $objDTO->getDtaTransitoJulgado();
            $contador++;
        }


        return $arrRetorno;
    }


    public static function converterParaArrExcelInfraDTOAntecedente($arrObjDTO, $inicioLinha = 1){
        $arrAlfabeto = array('A','B','C','D','E','F','G', 'H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $arrRetorno = array();
        //obs : a key não foi reutilizada, pois a pesquisa  não segue uma ordem correta .

        $arrCabecalho = array('Processo', 'Interessado', 'CNPJ/CPF', 'Tipo de Decisão', 'Espécie de Decisão', 'Data do Trânsito Julgado');

        //Cabecalho
        $contador = 0;
        foreach($arrCabecalho as $label){
            $arrRetorno[1][$arrAlfabeto[$contador]] = $label;
            $contador++;
        }

        //Dados
        $contador = 0;
        foreach($arrObjDTO as $objDTO){
            $linhaExcel = $contador + $inicioLinha + 1;
            $arrRetorno[$linhaExcel][$arrAlfabeto[0]] = $objDTO->getStrProtocoloFormatadoProcedimento();
            $arrRetorno[$linhaExcel][$arrAlfabeto[1]] = $objDTO->getStrNomeContato();

            if(!empty($objDTO->getStrCpfContato())){
                $arrRetorno[$linhaExcel][$arrAlfabeto[2]] = InfraUtil::formatarCpfCnpj($objDTO->getStrCpfContato());
            }else{
                $arrRetorno[$linhaExcel][$arrAlfabeto[2]] = InfraUtil::formatarCpfCnpj($objDTO->getStrCnpjContato());
            }
            $arrRetorno[$linhaExcel][$arrAlfabeto[3]] = $objDTO->getStrNomeMdLitTipoDecisao();
            $arrRetorno[$linhaExcel][$arrAlfabeto[4]] = $objDTO->getStrNomeMdLitEspecieDecisao();
            $arrRetorno[$linhaExcel][$arrAlfabeto[5]] = $objDTO->getDtaTransitoJulgado();
            $contador++;
        }


        return $arrRetorno;
    }
}
?>