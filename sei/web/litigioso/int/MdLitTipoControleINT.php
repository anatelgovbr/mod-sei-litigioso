<?php
    /**
     * ANATEL
     *
     * 28/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitTipoControleINT extends InfraINT
    {

        public static function autoCompletarUnidades($strPalavrasPesquisa, $bolTodas, $numIdOrgao = ''){

            $objUnidadeDTO = new UnidadeDTO();
            $objUnidadeDTO->retNumIdUnidade();
            $objUnidadeDTO->retStrSigla();
            $objUnidadeDTO->retStrDescricao();
            $objUnidadeDTO->setNumMaxRegistrosRetorno(50);
            $objUnidadeDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
    
        if ($strPalavrasPesquisa!=''){
          $objUnidadeDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
        }
    
            if ($numIdOrgao!= ""){
                $objUnidadeDTO->setNumIdOrgao(explode(',',$numIdOrgao),InfraDTO::$OPER_IN);
            }
    
            $objUnidadeRN = new UnidadeRN();
            if ($bolTodas){
                $arrObjUnidadeDTO = $objUnidadeRN->listarTodasComFiltro($objUnidadeDTO);
            }else{
                $arrObjUnidadeDTO = $objUnidadeRN->listarOutrasComFiltro($objUnidadeDTO);
            }
    
            foreach($arrObjUnidadeDTO as $objUnidadeDTO){
                $objUnidadeDTO->setStrSigla(UnidadeINT::formatarSiglaDescricao($objUnidadeDTO->getStrSigla(),$objUnidadeDTO->getStrDescricao()));
            }
    
        return $arrObjUnidadeDTO;
        }

        public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
        {

            $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
            $objTipoControleLitigiosoDTO->retNumIdTipoControleLitigioso();
            $objTipoControleLitigiosoDTO->retStrSigla();

            $objTipoControleLitigiosoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objTipoControleLitigiosoDTO->setStrSinAtivo('S');

            $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
            $arrObjTipoProcessoDTO      = $objTipoControleLitigiosoRN->listar($objTipoControleLitigiosoDTO);

            return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoProcessoDTO, 'IdTipoControleLitigioso', 'Sigla');
        }

        public static function autoCompletarTipoControleAtivos($strPalavrasPesquisa)
        {

            $obTipoControleDTO = new MdLitTipoControleDTO();
            $obTipoControleDTO->retTodos();

            $obTipoControleDTO->setStrSigla('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $obTipoControleDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
            $obTipoControleDTO->setStrSinAtivo('S');
            $obTipoControleDTO->setNumMaxRegistrosRetorno(50);
            $obTipoControleRN      = new MdLitTipoControleRN();
            $arrObjTipoControleDTO = $obTipoControleRN->listar($obTipoControleDTO);

            return $arrObjTipoControleDTO;
        }

        public static function autoCompletarTipoProcedimento($strPalavrasPesquisa){
            
            $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
            $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
            $objTipoProcedimentoDTO->retStrNome();
            $objTipoProcedimentoDTO->setStrNome('%' . $strPalavrasPesquisa . '%', InfraDTO::$OPER_LIKE);
            $objTipoProcedimentoDTO->setNumMaxRegistrosRetorno(50);
            $objTipoProcedimentoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
        
            $objTipoProcedimentoRN = new TipoProcedimentoRN();
           
            $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);
                
            
            return $arrObjTipoProcedimentoDTO;
          }

        public static function autoCompletarUsuarios($numIdOrgao, $strPalavrasPesquisa, $bolOutros, $bolExternos, $bolSiglaNome, $bolInativos){
    
            $objUsuarioDTO = new UsuarioDTO();
        
            if ($bolInativos){
              $objUsuarioDTO->setBolExclusaoLogica(false);
            }
        
            $objUsuarioDTO->retNumIdContato();
            $objUsuarioDTO->retNumIdUsuario();
            $objUsuarioDTO->retStrSigla();
            $objUsuarioDTO->retStrNome();
            
            $objUsuarioDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
        
                if (!InfraString::isBolVazia($numIdOrgao)){
                  $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
                }
                
                if ($bolOutros){
                    $objUsuarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario(),InfraDTO::$OPER_DIFERENTE);
                }
        
                if (!$bolExternos){
                  $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SIP);
                }else{
                  $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_EXTERNO);
                }
                
                $objUsuarioDTO->setNumMaxRegistrosRetorno(50);
                
            $objUsuarioDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
                
            $objUsuarioRN = new UsuarioRN();
            $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);
        
            if ($bolSiglaNome) {
              foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
                $objUsuarioDTO->setStrSigla($objUsuarioDTO->getStrNome() . ' (' . $objUsuarioDTO->getStrSigla() . ')');
              }
            }
          
            return $arrObjUsuarioDTO;
          }

    }
