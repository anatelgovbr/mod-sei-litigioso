<?
/**
 * ANATEL
 *
 * 10/07/2017 - criado por ellyson.silva - CAST
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdLitCamposAdFormINT extends InfraINT
{
    public static function salvarInformacoesAdd($paramsPost)
    {
        $objMdLitCamposAdFormRN = new MdLitCamposAdFormRN();

        $arrayOrganizador = json_decode($paramsPost['campoOrganizador'], true);

        if ($arrayOrganizador) {
            foreach ($arrayOrganizador as $linha => $campos ){
                foreach ($campos as $campo) {
                    if (preg_match('/^campo_add_(\d+)_\w+$/', $campo, $matches)) {
                        $campoId = $matches[1];
                        $objMdLitCamposAdFormDTO = new MdLitCamposAdFormDTO();
                        $objMdLitCamposAdFormDTO->setDblIdProcedimento($paramsPost['idProcedimento']);
                        $objMdLitCamposAdFormDTO->setNumIdMdLitCamposAd($campoId);
                        $objMdLitCamposAdFormDTO->setNumLinha($linha);
                        $objMdLitCamposAdFormDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                        $objMdLitCamposAdFormDTO->setDthInclusao(InfraData::getStrDataHoraAtual());

                        // SE CASO FOR UM ARRAY SIGNIFICA QUE É UM CAMPO MULTISELECT E TEM QUE SALVAR AS OPÇOES EM OUTRA TABELA
                        if(!is_array($paramsPost[$campo])){
                            $objMdLitCamposAdFormDTO->setStrValor($paramsPost[$campo]);
                            $objMdLitCamposAdFormRN->cadastrar($objMdLitCamposAdFormDTO);
                        } else {
                            $objMdLitCamposAdFormDTO = $objMdLitCamposAdFormRN->cadastrar($objMdLitCamposAdFormDTO);
                            MdLitRelOpcCampMultINT::salvarMultiSelecaoCampo($objMdLitCamposAdFormDTO, $paramsPost[$campo]);
                        }
                    }
                }
            }
        }
    }

    public static function alterarInformacoesAdd($paramsPost)
    {
        $objMdLitCamposAdFormRN = new MdLitCamposAdFormRN();
        $objMdLitCamposAdFormDTO = new MdLitCamposAdFormDTO();
        $objMdLitCamposAdFormDTO->setDblIdProcedimento($paramsPost['idProcedimento']);
        $objMdLitCamposAdFormDTO->retNumIdMdLitCamposAdForm();
        $arrObjMdLitCamposAdFormDTO = $objMdLitCamposAdFormRN->listar($objMdLitCamposAdFormDTO);

        if($arrObjMdLitCamposAdFormDTO){
            MdLitRelOpcCampMultINT::excluirOpcoes(InfraArray::converterArrInfraDTO($arrObjMdLitCamposAdFormDTO, 'IdMdLitCamposAdForm'));
            $objMdLitCamposAdFormRN->excluir($arrObjMdLitCamposAdFormDTO);
        }

        self::salvarInformacoesAdd($paramsPost);
    }

    public static function recuperarCampoFormulario($idProcedimento)
    {
        $grid = '';
        $objMdLitCamposAdFormRN = new MdLitCamposAdFormRN();

        $count = 1;
        $repetir = true;

        while ($repetir == true) {
            $objMdLitCamposAdFormDTO = new MdLitCamposAdFormDTO();
            $objMdLitCamposAdFormDTO->setDblIdProcedimento($idProcedimento);
            $objMdLitCamposAdFormDTO->setNumLinha($count);
            $objMdLitCamposAdFormDTO->retTodos(true);
            $arrObjMdLitCamposAdFormDTO = $objMdLitCamposAdFormRN->listar($objMdLitCamposAdFormDTO);

            if(!empty($arrObjMdLitCamposAdFormDTO)){
                $nomeTipoInformacao = current($arrObjMdLitCamposAdFormDTO)->getStrNomeTipoInformacao();
                $grid .= '<tr>';
                $grid .=    '<td>';
                $grid .=    '<div class="label-campo-adicional"><h6>'. $nomeTipoInformacao  . '</h6></div>';
                $grid .=    '<div class="campo-adicional-container">';
                foreach ($arrObjMdLitCamposAdFormDTO as $objMdLitCamposAdFormDTO) {
                    $grid .= MdLitCamposAdINT::montarCampo($objMdLitCamposAdFormDTO, $objMdLitCamposAdFormDTO->getStrValor(), true);
                }
                $grid .=    '</div>';
                $grid .=    '</td>';
                $grid .=    '<td style="text-align: center">';
                $grid .=    '    <img src="/infra_css/svg/remover.svg" title="Excluir Informação Adicional" alt="Excluir Informação Adicional" class="infraImg" onclick="excluirCampo(this)" style="margin-top: 20px;">';
                $grid .=    '</td>';
                $grid .= '</tr>';
            }
            $count++;
            $repetir = !empty($arrObjMdLitCamposAdFormDTO);
        }

        return $grid;
    }

    public static function recuperarAtualizadoPor($idProcedimento)
    {
        $objMdLitCamposAdFormRN = new MdLitCamposAdFormRN();
        $objMdLitCamposAdFormDTO = new MdLitCamposAdFormDTO();
        $objMdLitCamposAdFormDTO->setDblIdProcedimento($idProcedimento);
        $objMdLitCamposAdFormDTO->retNumIdUsuario();
        $objMdLitCamposAdFormDTO->retDthInclusao();
        $objMdLitCamposAdFormDTO->retStrSiglaUsuario();
        $objMdLitCamposAdFormDTO->setNumMaxRegistrosRetorno(1);
        $objMdLitCamposAdFormDTO = $objMdLitCamposAdFormRN->consultar($objMdLitCamposAdFormDTO);

        return ($objMdLitCamposAdFormDTO && $objMdLitCamposAdFormDTO->getStrSiglaUsuario()) ?  $objMdLitCamposAdFormDTO->getStrSiglaUsuario() . ' em ' . $objMdLitCamposAdFormDTO->getDthInclusao() : '';
    }
}

?>



