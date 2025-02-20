<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdINT extends InfraINT
{
    //Opções de tipo de informação que o campo receberá
    public static $TEXTO      = 'text';
    public static $MONETARIO  = 'monetario';
    public static $INTEIRO    = 'inteiro';
    public static $COMBO_BOX  = 'combo_box';
    public static $MULTIPLA_SELECAO  = 'multipla_selecao';
    public static $DATA       = 'data';
    public static $DOCUMENTOSEI = 'documento_sei';

    public static function montarTabelaListagemCamposAdicionais($idMdLitTpInforAd, $idTipoControle)
    {
        $arrNomesTipoCampo = self::recuperarNomeTipoCampo();

        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitTpInforAd($idMdLitTpInforAd);
        $objMdLitCamposAdDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitCamposAdDTO->retTodos();
        $arrObjMdLitCamposAdDTO = (new MdLitCamposAdRN())->listar($objMdLitCamposAdDTO);
        $strResultado = '';

        if (count($arrObjMdLitCamposAdDTO) > 0) {

            $strSumarioTabela = 'Tabela de campos adicionais.';
            $strCaptionTabela = 'Campos adicionais';
            $count = 0;

            $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
            $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, count($arrObjMdLitCamposAdDTO)) . '</caption>';
            $strResultado .= '<tr>';
            $strResultado .= '    <th class="infraTh" style="display: none"> Ordem </th>' . "\n";
            $strResultado .= '    <th class="infraTh" width="5%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
            $strResultado .= '    <th class="infraTh" width="10%"> Nome </th>' . "\n";
            $strResultado .= '    <th class="infraTh" width="10%"> Tipo do campo </th>' . "\n";
            $strResultado .= '    <th class="infraTh" width="10%"> Ordem </th>' . "\n";
            $strResultado .= '    <th class="infraTh" width="15%"> Dependente de opção </th>' . "\n";
            $strResultado .= '    <th class="infraTh" width="35%"> Ajuda </th>' . "\n";
            $strResultado .= '    <th class="infraTh" width="15%">Ações</th>' . "\n";
            $strResultado .= '</tr>' . "\n";

            foreach ($arrObjMdLitCamposAdDTO as $objMdLitCamposAdDTO) {

                $dependencia = $objMdLitCamposAdDTO->getNumIdMdLitCamposAdSel() ? MdLitCamposAdSelINT::buscarDependenciaCampo($objMdLitCamposAdDTO->getNumIdMdLitCamposAdSel()) : ' - ' ;

                //Ação ativar-desativa
                $ativarDesativar = '        <img onclick="acaoAtivar(\'' . $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() . '\',\'' . $objMdLitCamposAdDTO->getStrNome() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg?'.Icone::VERSAO.'" title="Ativar Tipo de Informação" alt="Ativar Tipo Informação" class="infraImg" />';
                if ($objMdLitCamposAdDTO->getStrSinAtivo() == 'S') {
                    $ativarDesativar = '        <img onclick="acaoDesativar(\'' . $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() . '\',\'' . $objMdLitCamposAdDTO->getStrNome() . '\');" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg?'.Icone::VERSAO.'" title="Desativar Tipo de Informação" alt="Desativar Tipo Informação" class="infraImg" />';
                }

                // Alterna as classes infraTrEscura e infraTrClara
                if ($objMdLitCamposAdDTO->getStrSinAtivo() == 'S') {
                    $strResultado .= $toggleClass ? '<tr class="infraTrClara">' : '<tr class="infraTrEscura">';
                    $toggleClass = !$toggleClass; // Alterna o valor de toggleClass
                } else {
                    $strResultado .= '<tr class="trVermelha">';
                }

                $strImagem = '<a onclick="moverAcima(this)"><img src="'
                  . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/mover_acima.svg?'.Icone::VERSAO.'" style="margin: 2px -7px 12px 2px;" /></a>';

                $strImagem .= '<a onclick="moverAbaixo(this)"><img src="'
                  . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/mover_abaixo.svg?'.Icone::VERSAO.'" /> </a>';

                $strResultado .= '    <td style="display: none"><input type="hidden" value="' . ($count + 1) . '" name="ordem_' . $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() . '"></td>';
                $strResultado .= '    <td valign="middle" style="text-align: center">' . PaginaSEI::getInstance()->getTrCheck($count, $objMdLitCamposAdDTO->getNumIdMdLitCamposAd(), PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrNome())) . '</td>';
                $strResultado .= '    <td style="text-align: center">' . PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrNome()) . '</td>';
                $strResultado .= '    <td style="text-align: center">' . $arrNomesTipoCampo[$objMdLitCamposAdDTO->getStrCampoTipo()] . '</td>';
                $strResultado .= '    <td align="center">' . $strImagem . '</td>';
                $strResultado .= '    <td style="text-align: center">' . PaginaSEI::tratarHTML($dependencia) . '</td>';
                $strResultado .= '    <td style="text-align: left">' . PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrAjuda()) . '</td>';

                $strResultado .= '    <td style="text-align: center;">';
                $strResultado .= '        <a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_campos_add_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] .'&id_tp_info_ad='. $idMdLitTpInforAd .'&id_campo_adicional=' . $objMdLitCamposAdDTO->getNumIdMdLitCamposAd().'&id_tipo_controle_litigioso=' . $idTipoControle)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg?'.Icone::VERSAO.'" title="Alterar Informação Adicional" alt="Alterar Informação Adicional" class="infraImg" /></a>&nbsp;';
                $strResultado .= $ativarDesativar;
                $strResultado .= '        <img onclick="excluirCampo('. $objMdLitCamposAdDTO->getNumIdMdLitCamposAd(). ')" src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg?'.Icone::VERSAO.'" title="Excluir Informação Adicional" alt="Excluir Informação Adicional" class="infraImg" />';

                $strResultado .= '    </td>' . "\n";
                $strResultado .= '</tr>' . "\n";
                $count ++;
            }
            $strResultado .= '</table>';
        }

        $return['qtd'] = count($arrObjMdLitCamposAdDTO);
        $return['table'] = $strResultado;

        return $return;
    }

    public static function montarSelectTipoInput($selected = null)
    {
        $options  = '';
        $options .= '<option value="" ' . ($selected === null ? 'selected' : '') . '></option>';
        $options .= '<option value="'. MdLitCamposAdINT::$COMBO_BOX .'" ' . ($selected === MdLitCamposAdINT::$COMBO_BOX ? 'selected' : '') . '>Combo box</option>';
        $options .= '<option value="'. MdLitCamposAdINT::$DATA .'" ' . ($selected === MdLitCamposAdINT::$DATA ? 'selected' : '') . '>Data</option>';
        $options .= '<option value="'. MdLitCamposAdINT::$DOCUMENTOSEI .'" ' . ($selected === MdLitCamposAdINT::$DOCUMENTOSEI ? 'selected' : '') . '>Documento Sei</option>';
        $options .= '<option value="'. MdLitCamposAdINT::$INTEIRO .'" ' . ($selected === MdLitCamposAdINT::$INTEIRO ? 'selected' : '') . '>Número Inteiro</option>';
        $options .= '<option value="'. MdLitCamposAdINT::$MONETARIO .'" ' . ($selected === MdLitCamposAdINT::$MONETARIO ? 'selected' : '') . '>Valor Monetário</option>';
        $options .= '<option value="'. MdLitCamposAdINT::$MULTIPLA_SELECAO .'" ' . ($selected === MdLitCamposAdINT::$MULTIPLA_SELECAO ? 'selected' : '') . '>Multipla Seleção</option>';
        $options .= '<option value="'. MdLitCamposAdINT::$TEXTO .'" ' . ($selected === MdLitCamposAdINT::$TEXTO ? 'selected' : '') . '>Texto</option>';

        return $options;
    }

    public static function desativarCampoAdd($arrIds)
    {
        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($arrIds, InfraDTO::$OPER_IN);
        $objMdLitCamposAdDTO->retTodos();
        $arrObjMdLitCamposAdDTO = $objMdLitCamposAdRN->listar($objMdLitCamposAdDTO);
        $objMdLitCamposAdRN->desativar($arrObjMdLitCamposAdDTO);

        self::desativarCamposRelacionados($arrObjMdLitCamposAdDTO);
    }

    private static function desativarCamposRelacionados($arrObjMdLitCamposAdDTO)
    {
        foreach ($arrObjMdLitCamposAdDTO as $objMdLitCamposAdDTO) {
            if($objMdLitCamposAdDTO->getStrCampoTipo() == MdLitCamposAdINT::$COMBO_BOX) {
                $arrOpcoesCampo = MdLitCamposAdSelINT::buscarOpcoesCampo($objMdLitCamposAdDTO->getNumIdMdLitCamposAd());
                MdLitCamposAdSelINT::desativarOpcoesCampo($arrOpcoesCampo);
                if(!empty($arrOpcoesCampo)){
                    self::buscarDesativarCamposDependenteOpcao($arrOpcoesCampo);
                }
            }
        }
    }

    private static function buscarDesativarCamposDependenteOpcao($arrOpcoesCampo)
    {
        $arrIdOpcoesCampo = InfraArray::converterArrInfraDTO($arrOpcoesCampo, 'IdMdLitCamposAdSel');

        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAdSel($arrIdOpcoesCampo, InfraDTO::$OPER_IN);
        $objMdLitCamposAdDTO->retTodos();
        $arrObjMdLitCamposAdDTO = $objMdLitCamposAdRN->listar($objMdLitCamposAdDTO);

        if(!empty($arrObjMdLitCamposAdDTO)){
            $arrIdsCampos = InfraArray::converterArrInfraDTO($arrObjMdLitCamposAdDTO, 'IdMdLitCamposAd');

            self::desativarCampoAdd($arrIdsCampos);
        }
    }

    public static function ativarCampoAdd($arrIdCampoAdd)
    {
        foreach($arrIdCampoAdd as $idCampo){

            $objMdLitCamposAdRN = new MdLitCamposAdRN();
            $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
            $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($idCampo);
            $objMdLitCamposAdDTO->retTodos();
            $objMdLitCamposAdDTO = $objMdLitCamposAdRN->consultar($objMdLitCamposAdDTO);

            if($objMdLitCamposAdDTO->getStrCampoTipo() == MdLitCamposAdINT::$COMBO_BOX && empty(MdLitCamposAdSelINT::buscarOpcoesCampo($idCampo, true))){
                throw new InfraException("Não é possível ativar um campo do tipo Combobox sem ao menos uma opção ativa.");
            }

            $objMdLitCamposAdRN->ativar(array($objMdLitCamposAdDTO));
        }

    }

    public static function salvarOrdem($arrOrdem)
    {
        foreach($arrOrdem as $k => $ordem) {
            $objMdLitCamposAdRN = new MdLitCamposAdRN();
            $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
            $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($ordem);
            $objMdLitCamposAdDTO->retNumIdMdLitCamposAd();
            $objMdLitCamposAdDTO = $objMdLitCamposAdRN->consultar($objMdLitCamposAdDTO);
            $objMdLitCamposAdDTO->setNumOrdem($k);
            $objMdLitCamposAdRN->alterar($objMdLitCamposAdDTO);
        }
    }

    public static function montarCampo($objMdLitCamposAdDTO, $value = null, $isFormularioSalvo = false)
    {
        $htmRetorno = '';

        $uniqid = uniqid();
        $campoId = $objMdLitCamposAdDTO->getNumIdMdLitCamposAd();
        $uniqueId = $campoId . '_' . $uniqid;
        $htmlAjuda = '';
        $obrigatorio = $objMdLitCamposAdDTO->getStrSinObrigatorio() == 'S' ? 'infraLabelObrigatorio':'';

        if($objMdLitCamposAdDTO->getStrAjuda() != '') {
            $textoAjuda = str_replace("\n", '\\n', PaginaSEI::tratarHTML($objMdLitCamposAdDTO->getStrAjuda()));
            $htmlAjuda .= '    <a id="btAjuda" onmouseover="return infraTooltipMostrar(\''. $textoAjuda .'\',\'Ajuda\');" onmouseout="return infraTooltipOcultar();" tabindex="501">';
            $htmlAjuda .= '        <img border="0" src="/infra_css/svg/ajuda.svg?11" class="infraImgModulo"/>';
            $htmlAjuda .= '    </a>';
        }

        switch ($objMdLitCamposAdDTO->getStrCampoTipo()){

            case 'combo_box':
                $htmlOption = MdLitCamposAdSelINT::montarHtmlCombobox($objMdLitCamposAdDTO->getNumIdMdLitCamposAd(), $value, $exibirPrimeira = true);
                $htmRetorno .= '<div data-id-campo-tp-controle="'. $uniqueId .'" class="campos-info-add col-12 col-sm-12 col-md-8 col-lg-6 col-xl-4">';
                $htmRetorno .= '    <label for="'. $uniqueId .'" class="'.$obrigatorio.'">' . $objMdLitCamposAdDTO->getStrNome() .':</label>';
                $htmRetorno .= $htmlAjuda;
                $htmRetorno .= '    <select style="white-space: nowrap;" onChange="mudarOpcaoComboBox(this)" id="campo_add_'. $uniqueId .'" name="campo_add_'. $uniqueId .'" data-label="'. $objMdLitCamposAdDTO->getStrNome() .'" data-id-campo-add="'. $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() .'" class="infraSelect form-control" data-old-value="'. $value .'" value="'. $value .'">';
                $htmRetorno .= $htmlOption;
                $htmRetorno .= '    </select>';
                $htmRetorno .= '</div>';
                break;

            case 'data':
                $htmRetorno .= '<div data-id-campo-tp-controle="'. $uniqueId .'" class="campos-info-add col-12 col-sm-12 col-md-8 col-lg-6 col-xl-4">';
                $htmRetorno .= '    <label for="'. $uniqueId .'" class="'.$obrigatorio.'">' . $objMdLitCamposAdDTO->getStrNome() .':</label>';
                $htmRetorno .= $htmlAjuda;
                $htmRetorno .= '    <div class="input-group mb-3">';
                $htmRetorno .= '        <input onkeypress="return infraMascaraData(this, event);" onblur="validaData(this);" type="text" id="campo_add_'. $uniqueId .'" name="campo_add_'. $uniqueId .'" data-label="'. $objMdLitCamposAdDTO->getStrNome() .'" data-id-campo-add="'. $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() .'" class="infraText form-control" value="'. $value .'" />';
                $htmRetorno .= '        <img id="imgDtInstauracao" class="infraImg" onclick="infraCalendario(\'campo_add_'. $uniqueId .'\',this,false,null);" alt="Selecionar Data da Instauração" title="Selecionar Data da Instauração" src="'. PaginaSEI::getInstance()->getDiretorioSvgGlobal() .'/calendario.svg?'. Icone::VERSAO .'"/>';
                $htmRetorno .= '    </div>';
                $htmRetorno .= '</div>';
                break;

            case 'inteiro':
                $htmRetorno .= '<div data-id-campo-tp-controle="'. $uniqueId .'" class="campos-info-add col-12 col-sm-12 col-md-8 col-lg-6 col-xl-4">';
                $htmRetorno .= '    <label for="'. $uniqueId .'" class="'.$obrigatorio.'">' . $objMdLitCamposAdDTO->getStrNome() .':</label>';
                $htmRetorno .= $htmlAjuda;
                $htmRetorno .= '    <input type="text" onkeypress="return infraMascaraNumero(this, event, 5)" id="campo_add_'. $uniqueId .'" name="campo_add_'. $uniqueId .'" data-label="'. $objMdLitCamposAdDTO->getStrNome() .'" data-id-campo-add="'. $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() .'" class="infraText form-control" value="'. $value .'" />';
                $htmRetorno .= '</div>';
                break;

            case 'monetario':
                $htmRetorno .= '<div data-id-campo-tp-controle="'. $uniqueId .'" class="campos-info-add col-12 col-sm-12 col-md-8 col-lg-6 col-xl-4">';
                $htmRetorno .= '    <label for="'. $uniqueId .'" class="'.$obrigatorio.'">' . $objMdLitCamposAdDTO->getStrNome() .':</label>';
                $htmRetorno .= $htmlAjuda;
                $htmRetorno .= '    <input onkeypress="return infraMascaraDinheiro(this,event,2,12);" type="text" id="campo_add_'. $uniqueId .'" name="campo_add_'. $uniqueId .'" data-label="'. $objMdLitCamposAdDTO->getStrNome() .'" data-id-campo-add="'. $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() .'" class="infraText form-control" value="'. $value .'" />';
                $htmRetorno .= '</div>';
                break;

            case 'multipla_selecao':
                $idMdLitCamposAdForm = $isFormularioSalvo ? $objMdLitCamposAdDTO->getNumIdMdLitCamposAdForm() : null;
                $htmRetorno .= '<div data-id-campo-tp-controle="'. $uniqueId .'" class="campos-info-add col-12 col-sm-12 col-md-8 col-lg-6 col-xl-4">';
                $htmRetorno .= '    <label for="'. $uniqueId .'" class="'.$obrigatorio.'">' . $objMdLitCamposAdDTO->getStrNome() .':</label>';
                $htmRetorno .= $htmlAjuda;
                $htmRetorno .=      '<select id="campo_add_'. $uniqueId .'" name="campo_add_'. $uniqueId .'[]" multiple="multiple" class="infraSelect multipleSelect form-control" data-label="'. $objMdLitCamposAdDTO->getStrNome() .'" data-id-campo-add="'. $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() .'" style="width: 100%;">';
                $htmRetorno .=           self::montarSelectMultiSelect(null, null, $objMdLitCamposAdDTO->getNumIdMdLitCamposAd(), $idMdLitCamposAdForm);
                $htmRetorno .=      '</select>';
                $htmRetorno .= '</div>';
                break;

            case 'text':
                $htmRetorno .= '<div data-id-campo-tp-controle="'. $uniqueId .'" class="campos-info-add col-12 col-sm-12 col-md-8 col-lg-6 col-xl-4">';
                $htmRetorno .= '    <label for="'. $uniqueId .'" class="'.$obrigatorio.'">' . $objMdLitCamposAdDTO->getStrNome() .':</label>';
                $htmRetorno .= $htmlAjuda;
                $htmRetorno .= '    <input type="text" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" id="campo_add_'. $uniqueId .'" name="campo_add_'. $uniqueId .'" data-id-campo-add="'. $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() .'" class="infraText form-control" value="'. $value .'" />';
                $htmRetorno .= '</div>';
                break;

            case 'documento_sei':
                $htmRetorno .= '<div data-id-campo-tp-controle="'. $uniqueId .'" class="campos-info-add col-12 col-sm-12 col-md-8 col-lg-6 col-xl-4">';
                $htmRetorno .= '    <label for="'. $uniqueId .'" class="'.$obrigatorio.'">' . $objMdLitCamposAdDTO->getStrNome() .':</label>';
                $htmRetorno .= $htmlAjuda;
                $htmRetorno .= '    <input type="text" onkeypress="return infraMascaraNumero(this,event,7);" maxlength="100" id="campo_add_'. $uniqueId .'" name="campo_add_'. $uniqueId .'" data-id-campo-add="'. $objMdLitCamposAdDTO->getNumIdMdLitCamposAd() .'" class="infraText form-control" value="'. $value .'" />';
                $htmRetorno .= '</div>';
                break;

        }

        return $htmRetorno;
    }

    public static function montarSelectMultiSelect($strPrimeiroItemValor, $strPrimeiroItemDescricao, $idCampo, $idCampoForm){
        $objMdLitCamposAdSelDTO = new MdLitCamposAdSelDTO();
        $objMdLitCamposAdSelDTO->setNumIdMdLitCamposAd($idCampo);
        $objMdLitCamposAdSelDTO->setStrSinAtivo('S');
        $objMdLitCamposAdSelDTO->retTodos();
        $arrObjMdLitCamposAdSelDTO = (new MdLitCamposAdSelRN())->listar($objMdLitCamposAdSelDTO);

        $arrIdOpcoesSelecionadas = [];
        if($idCampoForm){
            $arrIdOpcoesSelecionadas = MdLitRelOpcCampMultINT::recuperarArrIdsOpcoesSelecionadasPorCampo($idCampoForm);
        }
        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $arrIdOpcoesSelecionadas, $arrObjMdLitCamposAdSelDTO, 'IdMdLitCamposAdSel', 'Nome');
    }

    public static function salvarRegistro($post)
    {
        if(self::validarNome($post)){
            throw new InfraException("Não é possível cadastrar Campo de Informação Adicional com o nome " . $post['txtNome'] . ", pois ele já está sendo utilizado neste Tipo de Controle.");
        }

        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setStrNome($post['txtNome']);
        $objMdLitCamposAdDTO->setStrAjuda(trim($post['txaAjuda']));
        $objMdLitCamposAdDTO->setStrCampoTipo($post['selTipo']);
        $objMdLitCamposAdDTO->setNumIdMdLitTpInforAd($post['hdnIdMdLitTpInforAd']);
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAdSel($post['selRelelacionamento']);
        $objMdLitCamposAdDTO->setNumOrdem(self::recuperarProximaNaOrdem($post['hdnIdMdLitTpInforAd']));
        $objMdLitCamposAdDTO->setStrSinObrigatorio(PaginaSEI::getInstance()->getCheckbox($post['chkSinObrigatorio']));
        $objMdLitCamposAdDTO->setStrSinDocExterno(PaginaSEI::getInstance()->getCheckbox($post['chkSinDocExterno']));
        $objMdLitCamposAdDTO->setStrValorMinimo($post['txtValorMinimo']);
        $objMdLitCamposAdDTO->setStrValorMaximo($post['txtValorMaximo']);
        $objMdLitCamposAdDTO->setNumTamanho($post['txtTamanho']);

        if($post['selTipo'] == MdLitCamposAdINT::$DATA){
            $objMdLitCamposAdDTO->setStrValorMinimo($post['txtDataInicial']);
            $objMdLitCamposAdDTO->setStrValorMaximo($post['txtDataFinal']);
        }

        $objMdLitCamposAdDTO = (new MdLitCamposAdRN())->cadastrar($objMdLitCamposAdDTO);
        MdLitCamposAdSelINT::cadastrarOpçoesSelect($objMdLitCamposAdDTO, $post['opcoesSelect']);

        return $objMdLitCamposAdDTO;
    }

    private static function validarNome($post)
    {
        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setStrNome($post['txtNome']);
        $objMdLitCamposAdDTO->setNumIdMdLitTpInforAd($post['hdnIdMdLitTpInforAd']);
        if($post['hdnIdMdLitCamposAd']){
            $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($post['hdnIdMdLitCamposAd'], InfraDTO::$OPER_DIFERENTE);
        }
        $objMdLitCamposAdDTO->retNumIdMdLitCamposAd();

        $objMdLitCamposAdDTO = $objMdLitCamposAdRN->consultar($objMdLitCamposAdDTO);

        return $objMdLitCamposAdDTO ? true : false;
    }

    private static function recuperarProximaNaOrdem($idMdLitTpInforAd)
    {
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitTpInforAd($idMdLitTpInforAd);
        $objMdLitCamposAdDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdLitCamposAdDTO->retNumIdMdLitCamposAd();
        $objMdLitCamposAdDTO->retNumOrdem();
        $objMdLitCamposAdDTO->setNumMaxRegistrosRetorno(1);

        $objMdLitCamposAdDTO = (new MdLitCamposAdRN())->consultar($objMdLitCamposAdDTO);
        return $objMdLitCamposAdDTO ? $objMdLitCamposAdDTO->getNumOrdem() + 1 : 1;
    }

    public static function alterarRegistro($post)
    {

        if(self::validarNome($post)){
            throw new InfraException("Não é possível cadastrar Tipo de Informação com o nome " . $post['txtNome'] . ", pois ele já está sendo utilizado neste Tipo de Controle.");
        }

        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($post['hdnIdMdLitCamposAd']);
        $objMdLitCamposAdDTO->retNumIdMdLitCamposAd();
        $objMdLitCamposAdDTO->retStrCampoTipo();
        $objMdLitCamposAdDTO = $objMdLitCamposAdRN->consultar($objMdLitCamposAdDTO);

        $objMdLitCamposAdDTO->setStrNome($post['txtNome']);
        $objMdLitCamposAdDTO->setStrAjuda(trim($post['txaAjuda']));
        $objMdLitCamposAdDTO->setStrCampoTipo($post['selTipo']);
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAdSel($post['selRelelacionamento']);
        $objMdLitCamposAdDTO->setStrSinObrigatorio(PaginaSEI::getInstance()->getCheckbox($post['chkSinObrigatorio']));
        $objMdLitCamposAdDTO->setStrSinDocExterno(PaginaSEI::getInstance()->getCheckbox($post['chkSinDocExterno']));
        $objMdLitCamposAdDTO->setStrValorMinimo($post['txtValorMinimo']);
        $objMdLitCamposAdDTO->setStrValorMaximo($post['txtValorMaximo']);
        $objMdLitCamposAdDTO->setNumTamanho($post['txtTamanho']);

        if($post['selTipo'] == MdLitCamposAdINT::$DATA){
            $objMdLitCamposAdDTO->setStrValorMinimo($post['txtDataInicial']);
            $objMdLitCamposAdDTO->setStrValorMaximo($post['txtDataFinal']);
        }

        $objMdLitCamposAdRN->alterar($objMdLitCamposAdDTO);
        MdLitCamposAdSelINT::cadastrarOpçoesSelect($objMdLitCamposAdDTO, $post['opcoesSelect']);

        return $objMdLitCamposAdDTO;
    }

    public static function recuperarNomeTipoCampo()
    {
        $arrNomes = [];

        $arrNomes[MdLitCamposAdINT::$TEXTO] = 'Texto';
        $arrNomes[MdLitCamposAdINT::$MONETARIO] = 'Monetário';
        $arrNomes[MdLitCamposAdINT::$INTEIRO] = 'Inteiro';
        $arrNomes[MdLitCamposAdINT::$COMBO_BOX] = 'Combo box';
        $arrNomes[MdLitCamposAdINT::$DATA] = 'Data';
        $arrNomes[MdLitCamposAdINT::$MULTIPLA_SELECAO] = 'Múltipla seleção';
        $arrNomes[MdLitCamposAdINT::$DOCUMENTOSEI] = 'Documento SEI';

        return $arrNomes;
    }

    public static function excluirCampoInfoAdd($arrIds)
    {
        if(self::validarCampoUtilizado($arrIds)){
            $msg = "Não é possível excluir o campo pois está sendo utilizado.";
            if(count($arrIds) > 1){
                $msg = "Não é possível  excluir os campos selecionados pois pelo menos um está sendo utilizado.";
            }
            throw new InfraException($msg);
        }
        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($arrIds, InfraDTO::$OPER_IN);
        $objMdLitCamposAdDTO->retTodos();
        $arrObjMdLitCamposAdDTO = $objMdLitCamposAdRN->listar($objMdLitCamposAdDTO);
        $objMdLitCamposAdRN->excluir($arrObjMdLitCamposAdDTO);
    }

    private static function validarCampoUtilizado($arrIds)
    {
        $objMdLitCamposAdForm = new MdLitCamposAdFormRN();
        $objMdLitCamposAdFormDTO = new MdLitCamposAdFormDTO();
        $objMdLitCamposAdFormDTO->setNumIdMdLitCamposAd($arrIds, InfraDTO::$OPER_IN);
        $objMdLitCamposAdFormDTO->retTodos();
        $arrObjMdLitCamposAdFormDTO = $objMdLitCamposAdForm->listar($objMdLitCamposAdFormDTO);

        return !empty($arrObjMdLitCamposAdFormDTO);
    }

    public static function recuperarCampo($idCampo)
    {
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($idCampo);
        $objMdLitCamposAdDTO->retNumIdMdLitCamposAd();
        $objMdLitCamposAdDTO->retStrNome();
        $objMdLitCamposAdDTO->retStrAjuda();
        $objMdLitCamposAdDTO->retStrCampoTipo();
        $objMdLitCamposAdDTO->retNumIdMdLitCamposAdSel();
        $objMdLitCamposAdDTO->retStrSinObrigatorio();
        $objMdLitCamposAdDTO->retStrValorMinimo();
        $objMdLitCamposAdDTO->retStrValorMaximo();
        $objMdLitCamposAdDTO->retNumTamanho();
        $objMdLitCamposAdDTO->retStrSinDocExterno();

        return (new MdLitCamposAdRN())->consultar($objMdLitCamposAdDTO);
    }

    public static function consultarCampo($idTipoInformacao)
    {
        $htmlRetorno = '';
        $xml = '';

        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitTpInforAd($idTipoInformacao);
        $objMdLitCamposAdDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAdSel(null);
        $objMdLitCamposAdDTO->setStrSinAtivo('S');
        $objMdLitCamposAdDTO->retTodos();
        $arrObjMdLitCamposAdDTO = $objMdLitCamposAdRN->listar($objMdLitCamposAdDTO);

        foreach ($arrObjMdLitCamposAdDTO as $objMdLitCamposAdDTO) {
            $htmlRetorno .= self::montarCampo($objMdLitCamposAdDTO);
        }

        $htmlTipoInformacao = self::montarLabelNomeTipoInformacao($idTipoInformacao);

        //MONTA O XML DE RETORNO
        $xml  .= "<HtmlTipoInformacao>".$htmlTipoInformacao."</HtmlTipoInformacao>\n";
        $xml  .= "<HtmlCampos>".$htmlRetorno."</HtmlCampos>\n";

        return $xml;
    }

    private static function montarLabelNomeTipoInformacao($idTipoInformacao)
    {
        $nome = MdLitTpInfoAdINT::recuperarNome($idTipoInformacao);
        return '<h6>'.$nome.'</h6>';
    }

    public static function consultarCampoDependente($idOpção)
    {
        $htmlCampos = '';
        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAdSel($idOpção);
        $objMdLitCamposAdDTO->setStrSinAtivo('S');
        $objMdLitCamposAdDTO->retTodos();
        $arrOjMdLitCamposAdDTO = $objMdLitCamposAdRN->listar($objMdLitCamposAdDTO);

        foreach ($arrOjMdLitCamposAdDTO as $objMdLitCamposAdDTO) {
            $htmlCampos .= self::montarCampo($objMdLitCamposAdDTO);
        }

        return $htmlCampos;
    }

    public static function consultarCampoDependenteParaRemover($idOpção)
    {
        $xml = '';
        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAdSel($idOpção);
        $objMdLitCamposAdDTO->retNumIdMdLitCamposAd();
        $arrObjMdLitCamposAdDTO = $objMdLitCamposAdRN->listar($objMdLitCamposAdDTO);

        $arrIdsCampos = InfraArray::converterArrInfraDTO($arrObjMdLitCamposAdDTO, 'IdMdLitCamposAd');

        foreach ($arrIdsCampos as $id) {
            $xml .= "<idCampoAdd>".$id."</idCampoAdd>\n";
        }
        return $xml;
    }

    public static function desativarCamposDependentes($arrObjMdLitTpInfoAdDTO)
    {
        foreach ($arrObjMdLitTpInfoAdDTO as $objMdLitTpInfoAdDTO) {
            $objMdLitCamposAdRN = new MdLitCamposAdRN();
            $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
            $objMdLitCamposAdDTO->setNumIdMdLitTpInforAd($objMdLitTpInfoAdDTO->getNumIdMdLitTpInforAd());
            $objMdLitCamposAdDTO->retTodos();
            $arrObjMdLitCamposAdDTO = $objMdLitCamposAdRN->listar($objMdLitCamposAdDTO);

            $arrIdCampos = InfraArray::converterArrInfraDTO($arrObjMdLitCamposAdDTO, 'IdMdLitCamposAd');
        }
        self::desativarCampoAdd($arrIdCampos);
    }

    public static function consultarCampoAtivoNoTipoInformacao($arrObjMdLitTpInfoAdDTO)
    {
        $arrIdTipoInformacao = InfraArray::converterArrInfraDTO($arrObjMdLitTpInfoAdDTO, 'IdMdLitTpInforAd');
        $objMdLitCamposAdRN = new MdLitCamposAdRN();
        $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
        $objMdLitCamposAdDTO->setNumIdMdLitTpInforAd($arrIdTipoInformacao, InfraDTO::$OPER_IN);
        $objMdLitCamposAdDTO->setNumIdMdLitCamposAdSel(null);
        $objMdLitCamposAdDTO->setStrSinAtivo('S');
        $objMdLitCamposAdDTO->retTodos();
        return $objMdLitCamposAdRN->listar($objMdLitCamposAdDTO);
    }

    public static function validarPreenchimentoCampos($params)
    {
        $msg = '';

        foreach($params['campos'] as $campo => $valor){
            preg_match('/^campo_add_(\d+)_/', $campo, $matches);
            $todosCampos[] = rtrim($campo, '[]');
            $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
            $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($matches[1]);
            $objMdLitCamposAdDTO->retTodos();
            $objMdLitCamposAdDTO = (new MdLitCamposAdRN())->consultar($objMdLitCamposAdDTO);

            switch ($objMdLitCamposAdDTO->getStrCampoTipo()) {
                case MdLitCamposAdINT::$DATA:
                    $msg  .= self::validarCampoTipoData($objMdLitCamposAdDTO, $valor);
                    break;

                case MdLitCamposAdINT::$TEXTO:
                    $msg .= self::validarCampoTipoTexto($objMdLitCamposAdDTO, $valor);
                    break;

                case MdLitCamposAdINT::$MONETARIO:
                case MdLitCamposAdINT::$INTEIRO:
                    $msg .= self::validarCampoTipoMonetarioInteiro($objMdLitCamposAdDTO, $valor);
                    break;

                case MdLitCamposAdINT::$COMBO_BOX:
                    $msg .= self::validarCampoCombobox($objMdLitCamposAdDTO, $valor);
                    break;
                case MdLitCamposAdINT::$DOCUMENTOSEI:
                    $msg .= self::validarCampoTipoDocumentoSei($objMdLitCamposAdDTO, $valor, $params['id_procedimento']);
                    break;
            }
        }

        // Validação de campos de Multipla Seleção
        foreach (json_decode($params['campoOrganizador'], true) as $linha => $campos ){
            if(!empty($campos)){
                preg_match('/^campo_add_(\d+)_/', current($campos), $matches);
                $objMdLitCamposAdDTO = new MdLitCamposAdDTO();
                $objMdLitCamposAdDTO->setNumIdMdLitCamposAd($matches[1]);
                $objMdLitCamposAdDTO->retTodos();
                $objMdLitCamposAdDTO = (new MdLitCamposAdRN())->consultar($objMdLitCamposAdDTO);

                if ($objMdLitCamposAdDTO->getStrCampoTipo() == MdLitCamposAdINT::$MULTIPLA_SELECAO && $objMdLitCamposAdDTO->getStrSinObrigatorio() == 'S') {
                    if(!in_array(current($campos), $todosCampos)){
                        $msg .= "O campo " . $objMdLitCamposAdDTO->getStrNome() . " é obrigatório. \n";
                    }
                }
            }
        }

         return '<msg>' . $msg . '</msg>';
    }

    private static function validarCampoTipoData($objMdLitCamposAdDTO, $valor)
    {
        $msg = '';

        if($valor == "" && $objMdLitCamposAdDTO->getStrSinObrigatorio() == 'S'){
            $msg .= "O campo " . $objMdLitCamposAdDTO->getStrNome() . "é obrigatório. \n";
        }

        if ($objMdLitCamposAdDTO->getStrValorMinimo() != null && $objMdLitCamposAdDTO->getStrValorMaximo() != null) {

            if ($objMdLitCamposAdDTO->getStrValorMinimo() == '@HOJE@' && $objMdLitCamposAdDTO->getStrValorMaximo() == '@FUTURO@') {

                if (InfraData::compararDatas($valor, InfraData::getStrDataAtual()) > 0) {
                    $msg .= "O valor para o campo " . $objMdLitCamposAdDTO->getStrNome() . " deve ser igual ou superior a " . InfraData::getStrDataAtual() . ".\n";
                }

            } else if ($objMdLitCamposAdDTO->getStrValorMinimo() == '@PASSADO@' && $objMdLitCamposAdDTO->getStrValorMaximo() == '@HOJE@') {

                if (InfraData::compararDatas($valor, InfraData::getStrDataAtual()) < 0) {
                    $msg .= "O valor para o campo " . $objMdLitCamposAdDTO->getStrNome() . " deve ser igual ou inferior a " . InfraData::getStrDataAtual() . ".\n";
                }

            } else if ($objMdLitCamposAdDTO->getStrValorMinimo() == '@AMANHA@' && $objMdLitCamposAdDTO->getStrValorMaximo() == '@FUTURO@') {

                if (InfraData::compararDatas($valor, InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE)) > 0) {
                    $msg .= "O valor para o campo " . $objMdLitCamposAdDTO->getStrNome() . " deve ser superior a " . InfraData::getStrDataAtual() . ".\n";
                }

            } else if ($objMdLitCamposAdDTO->getStrValorMinimo() == '@PASSADO@' && $objMdLitCamposAdDTO->getStrValorMaximo() == '@ONTEM@') {

                if (InfraData::compararDatas($valor, InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS)) < 0) {
                    $msg .= "O valor para o campo " . $objMdLitCamposAdDTO->getStrNome() . " deve ser inferior a " . InfraData::getStrDataAtual() . ".\n";
                }

            } else {

                if (InfraData::compararDatas($valor, $objMdLitCamposAdDTO->getStrValorMinimo()) > 0 ||
                  InfraData::compararDatas($valor, $objMdLitCamposAdDTO->getStrValorMaximo()) < 0
                ) {
                    $msg .= "O valor para o campo " . $objMdLitCamposAdDTO->getStrNome() . " deve estar entre " . $objMdLitCamposAdDTO->getStrValorMinimo() . " e " . $objMdLitCamposAdDTO->getStrValorMaximo() . ".\n";
                }
            }
        }

        return $msg;
    }

    private static function validarCampoTipoTexto($objMdLitCamposAdDTO, $valor)
    {
        $msg = '';

        if($valor == "" && $objMdLitCamposAdDTO->getStrSinObrigatorio() == 'S'){
            $msg .= "O campo " . $objMdLitCamposAdDTO->getStrNome() . " é obrigatório. \n";
        }
        if(!empty($objMdLitCamposAdDTO->getNumTamanho()) && strlen($valor) > $objMdLitCamposAdDTO->getNumTamanho()){
            $msg .= "O campo " . $objMdLitCamposAdDTO->getStrNome() . " não pode ter uma quantidade de caracteres maior que " . $objMdLitCamposAdDTO->getNumTamanho() . ". \n";
        }

        return $msg;
    }

    private static function validarCampoTipoMonetarioInteiro($objMdLitCamposAdDTO, $valor)
    {
        $msg = '';

        if($valor == "" && $objMdLitCamposAdDTO->getStrSinObrigatorio() == 'S'){
            $msg .= "O campo " . $objMdLitCamposAdDTO->getStrNome() . " é obrigatório. \n";
        }
        if($valor != "" && !empty($objMdLitCamposAdDTO->getStrValorMinimo()) &&  $valor < $objMdLitCamposAdDTO->getStrValorMinimo()){
            $msg .= "O campo " . $objMdLitCamposAdDTO->getStrNome() . " não pode ter um valor menor que: " . $objMdLitCamposAdDTO->getStrValorMinimo() . "\n";
        }
        if($valor != "" && !empty($objMdLitCamposAdDTO->getStrValorMaximo()) && $valor > $objMdLitCamposAdDTO->getStrValorMaximo()){
            $msg .= "O campo " . $objMdLitCamposAdDTO->getStrNome() . " não pode ter um valor maior que: " . $objMdLitCamposAdDTO->getStrValorMaximo(). "\n";
        }

        return $msg;
    }

    private static function validarCampoCombobox($objMdLitCamposAdDTO, $valor)
    {
        $msg = '';

        if($valor == "" && $objMdLitCamposAdDTO->getStrSinObrigatorio() == 'S'){
            $msg .= "O campo " . $objMdLitCamposAdDTO->getStrNome() . " é obrigatório. \n";
        }

        return $msg;
    }

    private static function validarCampoTipoDocumentoSei($objMdLitCamposAdDTO, $valor, $idProcedimento)
    {
        $msg = '';

        if($valor == "" && $objMdLitCamposAdDTO->getStrSinObrigatorio() == 'S'){
            $msg = "O campo " . $objMdLitCamposAdDTO->getStrNome() . " é obrigatório. \n";
        }

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setStrProtocoloDocumentoFormatado($valor);
        $objDocumentoDTO->retDblIdDocumento();
        if($objMdLitCamposAdDTO->getStrSinDocExterno() == 'N'){
            $objDocumentoDTO->setDblIdProcedimento($idProcedimento);
        }

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        if (!$objDocumentoDTO && $valor != "") {
            $msg = "O campo " . $objMdLitCamposAdDTO->getStrNome() . " precisa ser preenchido com um número de um documento existente. \n";
            if($objMdLitCamposAdDTO->getStrSinDocExterno() == 'N') {
                $msg = "O campo " . $objMdLitCamposAdDTO->getStrNome() . " precisa ser preenchido com um número de um documento existente dentro do mesmo processo. \n";
            }
        }

        return $msg;
    }

}

?>