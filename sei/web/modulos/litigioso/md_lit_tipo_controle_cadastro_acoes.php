<?

  $objTipoControleLitigiosoDTO = new MdLitTipoControleDTO();
  $objTipoControleLitigiosoDTO->retTodos();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    
  	case 'md_lit_tipo_controle_cadastrar':
    	      	
      $strTitulo = 'Novo Tipo de Controle Litigioso';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoControleLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso(null);
      $objTipoControleLitigiosoDTO->setStrSigla($_POST['txtSigla']);
	  $objTipoControleLitigiosoDTO->setStrDescricao($_POST['txtDescricao']);
	  $objTipoControleLitigiosoDTO->setDtaDtaCorte($_POST['txtDtCorte']);
      if (isset($_POST['sbmCadastrarTipoControleLitigioso'])) {
              	
      	try{
                    	
        	//GESTORES
        	$arrObjTipoControleLitigiosoGestorDTO = array();
        	$arrGestores = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnGestores']);
        	//print_r( $arrGestores ); die();
        	
        	for($x = 0; $x < count($arrGestores); $x++){
        		//echo $arrGestores[$x]; die();
        		$objTipoControleLitigiosoUsuarioDTO = new MdLitRelTipoControleUsuarioDTO();
        		$objTipoControleLitigiosoUsuarioDTO->setNumIdUsuario($arrGestores[$x]);
        		//$objTipoControleLitigiosoUnidadeDTO->setNumSequencia($x);
        		array_push( $arrObjTipoControleLitigiosoGestorDTO, $objTipoControleLitigiosoUsuarioDTO );
        	}

        	//print_r( $arrObjTipoControleLitigiosoGestorDTO ); die();
        	$objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUsuarioDTO($arrObjTipoControleLitigiosoGestorDTO);
        	//$objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUsuarioDTO($arrObjTipoControleLitigiosoGestorDTO);

            //MOTIVOS
            $arrObjTipoControleLitigiosoMotivosDTO = array();
            $arrMotivos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnMotivos']);

            for($x = 0; $x < count($arrMotivos);$x++){
                $objTipoControleLitigiosoMotivosDTO = new MdLitRelTpControlMotiDTO();
                $objTipoControleLitigiosoMotivosDTO->setNumIdMdLitMotivo($arrMotivos[$x]);

                array_push($arrObjTipoControleLitigiosoMotivosDTO ,$objTipoControleLitigiosoMotivosDTO );
            }

            $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoMotivoDTO($arrObjTipoControleLitigiosoMotivosDTO);

        	//TIPOS DE PROCESSOS ASSOCIADOS
        	$arrObjTipoControleLitigiosoTipoProcedimentoDTO = array();
        	$arrTipoProcessos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoProcessos']);
        	
        	for($x = 0;$x<count($arrTipoProcessos);$x++){
        		$objTipoControleLitigiosoTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
        		$objTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoProcedimento($arrTipoProcessos[$x]);
        		//$objTipoControleLitigiosoUnidadeDTO->setNumSequencia($x);
        		array_push( $arrObjTipoControleLitigiosoTipoProcedimentoDTO, $objTipoControleLitigiosoTipoProcedimentoDTO );
        	}
        	
        	$objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoDTO($arrObjTipoControleLitigiosoTipoProcedimentoDTO);
        	
        	//UNIDADES
        	$arrObjTipoControleLitigiosoUnidadeDTO = array();
        	$arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
        	
        	for($x = 0;$x<count($arrUnidades);$x++){
        		$objTipoControleLitigiosoUnidadeDTO = new MdLitRelTipoControleUnidadeDTO();
        		$objTipoControleLitigiosoUnidadeDTO->setNumIdUnidade($arrUnidades[$x]);
        		//$objTipoControleLitigiosoUnidadeDTO->setNumSequencia($x);
        		array_push( $arrObjTipoControleLitigiosoUnidadeDTO, $objTipoControleLitigiosoUnidadeDTO );
        	}
        	
        	$objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUnidadeDTO($arrObjTipoControleLitigiosoUnidadeDTO);
        	
        	//TIPOS DE PROCESSOS SOBRESTADOS
        	$arrObjTipoControleLitigiosoTipoProcedimentoSobrestadoDTO = array();
        	$arrTipoProcessosSobrestados = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoProcessosSobrestados']);
        	 
        	for($x = 0;$x<count( $arrTipoProcessosSobrestados );$x++){
        		
        		$objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO = new MdLitRelTipoControleTipoProcedimentoSobrestadoDTO();
        		$objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoProcedimento($arrTipoProcessosSobrestados[$x]);
        		array_push( $arrObjTipoControleLitigiosoTipoProcedimentoSobrestadoDTO, $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO );
        	
        	}
        	 
          $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO($arrObjTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);        	 
        	
          $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
          $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->cadastrar($objTipoControleLitigiosoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tipo de Processo Litigioso "'.$objTipoControleLitigiosoDTO->getStrSigla().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_processo_litigioso='.$objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso().PaginaSEI::getInstance()->montarAncora($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso())));
          die;
          
        } catch(Exception $e){
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        
      }
      
      break;

    case 'md_lit_tipo_controle_alterar':
      
      $strTitulo = 'Alterar Tipo de Controle Litigioso';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoControleLitigioso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tipo_processo_litigioso'])){
        
      	$objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
        $objTipoControleLitigiosoDTO->retTodos();
        $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
        $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);
        
        if ($objTipoControleLitigiosoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
        
        //============= INICIO OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================
        
        //consultar as unidades relacionadas
        $objTipoControleLitigiosoUnidadeDTO = new MdLitRelTipoControleUnidadeDTO();
        $objTipoControleLitigiosoUnidadeDTO->retTodos();
        $objTipoControleLitigiosoUnidadeDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
        
        $objRelTipoControleLitigiosoUnidadeRN = new MdLitRelTipoControleUnidadeRN();
        $arrUnidades = $objRelTipoControleLitigiosoUnidadeRN->listar( $objTipoControleLitigiosoUnidadeDTO );
        $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUnidadeDTO( $arrUnidades );
                
        $strItensSelUnidades = "";
        $objUnidadeRN = new UnidadeRN();
        
        for($x = 0;$x<count($arrUnidades);$x++){
        	
        	$objUnidadeDTO = new UnidadeDTO();
        	$objUnidadeDTO->retNumIdUnidade();
        	$objUnidadeDTO->retStrSigla();
        	
        	$objUnidadeDTO->setNumIdUnidade($arrUnidades[$x]->getNumIdUnidade());
        	$objUnidadeDTO = $objUnidadeRN->consultarRN0125( $objUnidadeDTO );
        	        	
        	$strItensSelUnidades .= "<option value='" . $objUnidadeDTO->getNumIdUnidade() .  "'>" . $objUnidadeDTO->getStrSigla() . "</option>";
        }
        
        //consultar os gestores relacionados
        $objTipoControleLitigiosoUsuarioDTO = new MdLitRelTipoControleUsuarioDTO();
        $objTipoControleLitigiosoUsuarioDTO->retTodos();
        $objTipoControleLitigiosoUsuarioDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
        
        $objRelTipoControleLitigiosoUsuarioRN = new MdLitRelTipoControleUsuarioRN();
        $arrGestores = $objRelTipoControleLitigiosoUsuarioRN->listar( $objTipoControleLitigiosoUsuarioDTO );
        $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUsuarioDTO( $arrGestores );
        
        $strItensSelGestores = "";
        $objUsuarioRN = new UsuarioRN();
        
        for($x = 0;$x<count($arrGestores);$x++){
        	 
        	$objUsuarioDTO = new UsuarioDTO();
        	$objUsuarioDTO->retNumIdUsuario();
        	$objUsuarioDTO->retStrNome();
        	 
        	$objUsuarioDTO->setNumIdUsuario($arrGestores[$x]->getNumIdUsuario());
        	$objUsuarioDTO = $objUsuarioRN->consultarRN0489( $objUsuarioDTO );

        	if( $objUsuarioDTO != null && is_object( $objUsuarioDTO ) ) {
        	  $strItensSelGestores .= "<option value='" . $objUsuarioDTO->getNumIdUsuario() .  "'>" . $objUsuarioDTO->getStrNome() . "</option>";
        	}
        }

        //consultar os motivos
          $objTipoControleLitigiosoMotivosDTO = new MdLitRelTpControlMotiDTO();
          $objTipoControleLitigiosoMotivosDTO->retTodos();
          $objTipoControleLitigiosoMotivosDTO->setNumIdMdLitTipoControle($_GET['id_tipo_processo_litigioso']);

          $objTipoControleLitigiosoMotivosRN = new MdLitRelTpControlMotiRN();
          $arrMotivos = $objTipoControleLitigiosoMotivosRN->listar( $objTipoControleLitigiosoMotivosDTO );
          $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoMotivoDTO( $arrMotivos );

          $strItensSelMotivos = "";
          $objMotivoRN = new MdLitMotivoRN();

          for($x = 0;$x<count($arrMotivos);$x++){

              $objMotivoDTO = new MdLitMotivoDTO();
              $objMotivoDTO->retNumIdMdLitMotivo();
              $objMotivoDTO->retStrDescricao();

              $objMotivoDTO->setNumIdMdLitMotivo($arrMotivos[$x]->getNumIdMdLitMotivo());
              $objMotivoDTO = $objMotivoRN->consultar( $objMotivoDTO );

              if( $objMotivoDTO != null && is_object( $objMotivoDTO ) ) {
                  $strItensSelMotivos .= "<option value='" . $objMotivoDTO->getNumIdMdLitMotivo() .  "'>" . PaginaSEI::tratarHTML($objMotivoDTO->getStrDescricao()) . "</option>";
              }
          }

          //consultar os tipos de processos relacionados
        $objTipoControleLitigiosoTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
        $objTipoControleLitigiosoTipoProcedimentoDTO->retTodos();
        $objTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
        
        $objRelTipoControleLitigiosoTipoProcedimentoRN = new MdLitRelTipoControleTipoProcedimentoRN();
        $arrTipoProcedimentos = $objRelTipoControleLitigiosoTipoProcedimentoRN->listar( $objTipoControleLitigiosoTipoProcedimentoDTO );
        $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoDTO( $arrTipoProcedimentos );
        
        $strItensSelTipoProcessos = "";
        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        
        for($x = 0;$x<count($arrTipoProcedimentos);$x++){
            
        	$objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        	$objTipoProcedimentoDTO->retNumIdTipoProcedimento();
        	$objTipoProcedimentoDTO->retStrNome();
        	
        	$objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrTipoProcedimentos[$x]->getNumIdTipoProcedimento());
        	$objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267( $objTipoProcedimentoDTO );
        	
        	$strItensSelTipoProcessos .= "<option value='" . $objTipoProcedimentoDTO->getNumIdTipoProcedimento() .  "'>" . $objTipoProcedimentoDTO->getStrNome() . "</option>";
        }
        
        //tipos de processos sobrestados relacionados
        $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO = new MdLitRelTipoControleTipoProcedimentoSobrestadoDTO();
        $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->retTodos();
        $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
        
        $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();
        $arrTipoProcedimentosSobrestados = $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN->listar( $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO );
        $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO( $arrTipoProcedimentosSobrestados );
        
        $strItensSelTipoProcessosSobrestados = "";
        
        for($x = 0;$x<count($arrTipoProcedimentosSobrestados);$x++){
        
        	$objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        	$objTipoProcedimentoDTO->retNumIdTipoProcedimento();
        	$objTipoProcedimentoDTO->retStrNome();
        	 
        	$objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrTipoProcedimentosSobrestados[$x]->getNumIdTipoProcedimento());
        	$objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267( $objTipoProcedimentoDTO );
        	 
        	$strItensSelTipoProcessosSobrestados .= "<option value='" . $objTipoProcedimentoDTO->getNumIdTipoProcedimento() .  "'>" . $objTipoProcedimentoDTO->getStrNome() . "</option>";
        }
        
        //============= FIM OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================
        
        
      } else {
        $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_POST['hdnIdTipoControleLitigioso']);
        $objTipoControleLitigiosoDTO->setStrSigla($_POST['txtSigla']);
		$objTipoControleLitigiosoDTO->setStrDescricao($_POST['txtDescricao']);
		$objTipoControleLitigiosoDTO->setDtaDtaCorte($_POST['txtDtCorte']);
		
		$objTipoControleLitigiosoRN = new MdLitTipoControleRN();
		
		//REMOVER TODOS OS RELACIONAMENTOS
		//$objTipoControleLitigiosoRN->removerRelacionamentos($objTipoControleLitigiosoDTO);
		
		//READICIONAR TODOS OS RELACIONAMENTOS
		//GESTORES
		$arrObjTipoControleLitigiosoGestorDTO = array();
		$arrGestores = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnGestores']);
		//print_r( $arrGestores ); die();
		 
		for($x = 0; $x < count($arrGestores); $x++){
			//echo $arrGestores[$x]; die();
			$objTipoControleLitigiosoUsuarioDTO = new MdLitRelTipoControleUsuarioDTO();
			$objTipoControleLitigiosoUsuarioDTO->setNumIdUsuario($arrGestores[$x]);
			//$objTipoControleLitigiosoUnidadeDTO->setNumSequencia($x);
			array_push( $arrObjTipoControleLitigiosoGestorDTO, $objTipoControleLitigiosoUsuarioDTO );
		}
		
		//print_r( $arrObjTipoControleLitigiosoGestorDTO ); die();
		$objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUsuarioDTO($arrObjTipoControleLitigiosoGestorDTO);

          //MOTIVOS
          $arrObjTipoControleLitigiosoMotivoDTO = array();
          $arrMotivos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnMotivos']);


          for($x = 0; $x < count($arrMotivos); $x++){
              //echo $arrGestores[$x]; die();
              $objTipoControleLitigiosoMotivoDTO = new MdLitRelTpControlMotiDTO();
              $objTipoControleLitigiosoMotivoDTO->setNumIdMdLitMotivo($arrMotivos[$x]);

              array_push( $arrObjTipoControleLitigiosoMotivoDTO, $objTipoControleLitigiosoMotivoDTO );
          }

          $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoMotivoDTO($arrObjTipoControleLitigiosoMotivoDTO);



          //TIPOS DE PROCESSOS ASSOCIADOS
		$arrObjTipoControleLitigiosoTipoProcedimentoDTO = array();
		$arrTipoProcessos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoProcessos']);
		 
		for($x = 0;$x<count($arrTipoProcessos);$x++){
			$objTipoControleLitigiosoTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
			$objTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoProcedimento($arrTipoProcessos[$x]);
			//$objTipoControleLitigiosoUnidadeDTO->setNumSequencia($x);
			array_push( $arrObjTipoControleLitigiosoTipoProcedimentoDTO, $objTipoControleLitigiosoTipoProcedimentoDTO );
		}
		 
		$objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoDTO($arrObjTipoControleLitigiosoTipoProcedimentoDTO);
		 
		//UNIDADES
		$arrObjTipoControleLitigiosoUnidadeDTO = array();
		$arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
		 
		for($x = 0;$x<count($arrUnidades);$x++){
			$objTipoControleLitigiosoUnidadeDTO = new MdLitRelTipoControleUnidadeDTO();
			$objTipoControleLitigiosoUnidadeDTO->setNumIdUnidade($arrUnidades[$x]);
			//$objTipoControleLitigiosoUnidadeDTO->setNumSequencia($x);
			array_push( $arrObjTipoControleLitigiosoUnidadeDTO, $objTipoControleLitigiosoUnidadeDTO );
		}
		 
		$objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUnidadeDTO($arrObjTipoControleLitigiosoUnidadeDTO);
		
		//TIPOS DE PROCESSOS SOBRESTADOS
		$arrObjTipoControleLitigiosoTipoProcedimentoSobrestadoDTO = array();
		$arrTipoProcessosSobrestados = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTipoProcessosSobrestados']);
			
		for($x = 0;$x<count($arrTipoProcessosSobrestados);$x++){
			$objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO = new MdLitRelTipoControleTipoProcedimentoSobrestadoDTO();
			$objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoProcedimento($arrTipoProcessosSobrestados[$x]);
			array_push( $arrObjTipoControleLitigiosoTipoProcedimentoSobrestadoDTO, $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO );
		}
			
		$objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO($arrObjTipoControleLitigiosoTipoProcedimentoSobrestadoDTO);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso()))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTipoControleLitigioso'])) {
        try{
          $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
          $objTipoControleLitigiosoRN->alterar($objTipoControleLitigiosoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tipo de Processo Litigioso "'.$objTipoControleLitigiosoDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoControleLitigiosoDTO->getNumIdTipoControleLitigioso())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_lit_tipo_controle_consultar':
      $strTitulo = 'Consultar Tipo de Controle Litigioso';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tipo_processo_litigioso']))).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
      $objTipoControleLitigiosoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
      $objTipoControleLitigiosoDTO->setBolExclusaoLogica(false);
      $objTipoControleLitigiosoDTO->retTodos();
      $objTipoControleLitigiosoRN = new MdLitTipoControleRN();
      $objTipoControleLitigiosoDTO = $objTipoControleLitigiosoRN->consultar($objTipoControleLitigiosoDTO);
      
      if ($objTipoControleLitigiosoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      
      //============= INICIO OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================
      
      //consultar as unidades relacionadas
      $objTipoControleLitigiosoUnidadeDTO = new MdLitRelTipoControleUnidadeDTO();
      $objTipoControleLitigiosoUnidadeDTO->retTodos();
      $objTipoControleLitigiosoUnidadeDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
      
      $objRelTipoControleLitigiosoUnidadeRN = new MdLitRelTipoControleUnidadeRN();
      $arrUnidades = $objRelTipoControleLitigiosoUnidadeRN->listar( $objTipoControleLitigiosoUnidadeDTO );
      $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUnidadeDTO( $arrUnidades );
      
      $strItensSelUnidades = "";
      $objUnidadeRN = new UnidadeRN();
      
      for($x = 0;$x<count($arrUnidades);$x++){
      	 
      	$objUnidadeDTO = new UnidadeDTO();
      	$objUnidadeDTO->retNumIdUnidade();
      	$objUnidadeDTO->retStrSigla();
      	 
      	$objUnidadeDTO->setNumIdUnidade($arrUnidades[$x]->getNumIdUnidade());
      	$objUnidadeDTO = $objUnidadeRN->consultarRN0125( $objUnidadeDTO );
      
      	$strItensSelUnidades .= "<option value='" . $objUnidadeDTO->getNumIdUnidade() .  "'>" . $objUnidadeDTO->getStrSigla() . "</option>";
      }
      
      //consultar os gestores relacionados
      $objTipoControleLitigiosoUsuarioDTO = new MdLitRelTipoControleUsuarioDTO();
      $objTipoControleLitigiosoUsuarioDTO->retTodos();
      $objTipoControleLitigiosoUsuarioDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
      
      $objRelTipoControleLitigiosoUsuarioRN = new MdLitRelTipoControleUsuarioRN();
      $arrGestores = $objRelTipoControleLitigiosoUsuarioRN->listar( $objTipoControleLitigiosoUsuarioDTO );
      $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoUsuarioDTO( $arrGestores );
      
      $strItensSelGestores = "";
      $objUsuarioRN = new UsuarioRN();
      
      for($x = 0;$x<count($arrGestores);$x++){
      
      	$objUsuarioDTO = new UsuarioDTO();
      	$objUsuarioDTO->retNumIdUsuario();
      	$objUsuarioDTO->retStrNome();
      
      	$objUsuarioDTO->setNumIdUsuario($arrGestores[$x]->getNumIdUsuario());
      	$objUsuarioDTO = $objUsuarioRN->consultarRN0489( $objUsuarioDTO );

      	if($objUsuarioDTO)
      	    $strItensSelGestores .= "<option value='" . $objUsuarioDTO->getNumIdUsuario() .  "'>" . $objUsuarioDTO->getStrNome() . "</option>";
      }

        //consultar os motivos
        $objTipoControleLitigiosoMotivosDTO = new MdLitRelTpControlMotiDTO();
        $objTipoControleLitigiosoMotivosDTO->retTodos();
        $objTipoControleLitigiosoMotivosDTO->setNumIdMdLitTipoControle($_GET['id_tipo_processo_litigioso']);

        $objTipoControleLitigiosoMotivosRN = new MdLitRelTpControlMotiRN();
        $arrMotivos = $objTipoControleLitigiosoMotivosRN->listar( $objTipoControleLitigiosoMotivosDTO );
        $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoMotivoDTO( $arrMotivos );

        $strItensSelMotivos = "";
        $objMotivoRN = new MdLitMotivoRN();

        for($x = 0;$x<count($arrMotivos);$x++){

            $objMotivoDTO = new MdLitMotivoDTO();
            $objMotivoDTO->retNumIdMdLitMotivo();
            $objMotivoDTO->retStrDescricao();

            $objMotivoDTO->setNumIdMdLitMotivo($arrMotivos[$x]->getNumIdMdLitMotivo());
            $objMotivoDTO = $objMotivoRN->consultar( $objMotivoDTO );

            if( $objMotivoDTO != null && is_object( $objMotivoDTO ) ) {
                $strItensSelMotivos .= "<option value='" . $objMotivoDTO->getNumIdMdLitMotivo() .  "'>" . PaginaSEI::tratarHTML($objMotivoDTO->getStrDescricao()) . "</option>";
            }
        }
      
      //consultar os tipos de processos relacionados
      $objTipoControleLitigiosoTipoProcedimentoDTO = new MdLitRelTipoControleTipoProcedimentoDTO();
      $objTipoControleLitigiosoTipoProcedimentoDTO->retTodos();
      $objTipoControleLitigiosoTipoProcedimentoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
      
      $objRelTipoControleLitigiosoTipoProcedimentoRN = new MdLitRelTipoControleTipoProcedimentoRN();
      $arrTipoProcedimentos = $objRelTipoControleLitigiosoTipoProcedimentoRN->listar( $objTipoControleLitigiosoTipoProcedimentoDTO );
      $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoDTO( $arrTipoProcedimentos );
      
      $strItensSelTipoProcessos = "";
      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      
      for($x = 0;$x<count($arrTipoProcedimentos);$x++){
      
      	$objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      	$objTipoProcedimentoDTO->retNumIdTipoProcedimento();
      	$objTipoProcedimentoDTO->retStrNome();
      	 
      	$objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrTipoProcedimentos[$x]->getNumIdTipoProcedimento());
      	$objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267( $objTipoProcedimentoDTO );
      	 
      	$strItensSelTipoProcessos .= "<option value='" . $objTipoProcedimentoDTO->getNumIdTipoProcedimento() .  "'>" . $objTipoProcedimentoDTO->getStrNome() . "</option>";
      }
      
      //tipos de processos sobrestados relacionados
      $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO = new MdLitRelTipoControleTipoProcedimentoSobrestadoDTO();
      $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->retTodos();
      $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO->setNumIdTipoControleLitigioso($_GET['id_tipo_processo_litigioso']);
      
      $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN = new MdLitRelTipoControleTipoProcedimentoSobrestadoRN();
      $arrTipoProcedimentosSobrestados = $objRelTipoControleLitigiosoTipoProcedimentoSobrestadoRN->listar( $objTipoControleLitigiosoTipoProcedimentoSobrestadoDTO );
      $objTipoControleLitigiosoDTO->setArrObjRelTipoControleLitigiosoTipoProcedimentoSobrestadoDTO( $arrTipoProcedimentosSobrestados );
      
      $strItensSelTipoProcessosSobrestados = "";
      
      for($x = 0;$x<count($arrTipoProcedimentosSobrestados);$x++){
      
      	$objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      	$objTipoProcedimentoDTO->retNumIdTipoProcedimento();
      	$objTipoProcedimentoDTO->retStrNome();
      
      	$objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrTipoProcedimentosSobrestados[$x]->getNumIdTipoProcedimento());
      	$objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267( $objTipoProcedimentoDTO );
      
      	$strItensSelTipoProcessosSobrestados .= "<option value='" . $objTipoProcedimentoDTO->getNumIdTipoProcedimento() .  "'>" . $objTipoProcedimentoDTO->getStrNome() . "</option>";
      }
      
      //============= FIM OBTER REGISTROS RELACIONADOS PARA EDIÇAO ===============================
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

?>