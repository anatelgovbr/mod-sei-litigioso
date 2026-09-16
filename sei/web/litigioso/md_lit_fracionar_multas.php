<?php
/**
 * ANATEL
 *
 */
require_once dirname(__FILE__) . '/../../SEI.php';
SessaoSEI::getInstance()->validarLink();
PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);


$arrComandos = array();


switch ($_GET['acao']) {
        case 'md_lit_fracionar_multas':
            $strTitulo = 'Fracionar multas';
            $idLancamento = $_GET['id_lancamento'];
            $arrDecisaoProcesso = MdLitLancamentoINT::consultarDecisoesProcesso($idLancamento);

            $objMdLitLancamentoDTO = new MdLitLancamentoDTO();
            $objMdLitLancamentoDTO->setNumIdMdLitLancamento($idLancamento);
            $objMdLitLancamentoDTO->retNumIdMdLitLancamento();
            $objMdLitLancamentoDTO->retNumIdMdLitLancamentoInicial();
            $objMdLitLancamentoDTO->retStrTipoLancamento();
            $objMdLitLancamentoDTO->retDblIdProcedimento();
            $objMdLitLancamentoDTO = (new MdLitLancamentoRN())->consultar($objMdLitLancamentoDTO);

            $idLancamentoBase = $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial() ? $objMdLitLancamentoDTO->getNumIdMdLitLancamentoInicial() : $idLancamento;

            $objLancamentoBaseDTO = new MdLitLancamentoDTO();
            $objLancamentoBaseDTO->setNumIdMdLitLancamento($idLancamentoBase);
            $objLancamentoBaseDTO->retStrTipoLancamento();
            $objLancamentoBaseDTO = (new MdLitLancamentoRN())->consultar($objLancamentoBaseDTO);

            $strTituloBase = 'Lançamento Principal';
            if ($objLancamentoBaseDTO->getStrTipoLancamento() == MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO) {
                $objListaMajoradoDTO = new MdLitLancamentoDTO();
                $objListaMajoradoDTO->setDblIdProcedimento($objMdLitLancamentoDTO->getDblIdProcedimento());
                $objListaMajoradoDTO->setNumIdMdLitLancamentoInicial(null, InfraDTO::$OPER_IGUAL);
                $objListaMajoradoDTO->setStrTipoLancamento(MdLitLancamentoRN::$TIPO_LANCAMENTO_MAJORADO);
                $objListaMajoradoDTO->retNumIdMdLitLancamento();
                $objListaMajoradoDTO->setOrdNumIdMdLitLancamento(InfraDTO::$TIPO_ORDENACAO_ASC);
                $arrMajorados = (new MdLitLancamentoRN())->listar($objListaMajoradoDTO);

                $idxMajorado = 0;
                foreach ($arrMajorados as $objMaj) {
                    $idxMajorado++;
                    if ($objMaj->getNumIdMdLitLancamento() == $idLancamentoBase) {
                        break;
                    }
                }

                $strTituloBase = "Lançamento Majorado " . $idxMajorado;
            }

            $strTituloLancamentoInicial = $strTituloBase;
            list($strPrefixoLancamentoBase) = explode(' ', $strTituloBase, 2);
            $strTituloLancamentoSecundario = $strPrefixoLancamentoBase . ' Fracionado';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo);
?>


<form method="post" id="frmMdLitFracionarMultas" name="frmMdLitFracionarMultas" action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_fracionar_multa_salvar&acao_origem=' . $_GET['acao'])) ?>">
<input type="hidden" id="hdnIdLancamentoInicial" name="hdnIdLancamentoInicial" value="<?= $arrDecisaoProcesso['id_lancamento_inicial'] ?>">
<input type="hidden" id="hdnIdLancamentoSecundario" name="hdnIdLancamentoSecundario" value="<?= $arrDecisaoProcesso['id_lancamento_secundario'] ?>">

<?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
<?php PaginaSEI::getInstance()->abrirAreaDados('') ?>

<div class="row">
    <div class="col-sm-12">
        <div id="divAvisoFracionamento" style="display: none; color: #b00; margin-bottom: 8px;"></div>
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        <label for="selMultasDisponiveis"><h5><?= $strTituloLancamentoInicial ?> <span id="lblTotalLancamentoInicial" style="font-weight: inherit; font-size: inherit;">(R$ 0,00)</span></h5></label>
        <select id="selMultasDisponiveis" name="selMultasDisponiveis" class="infraSelect form-select" size="10" multiple="multiple"><?= $arrDecisaoProcesso['inicial'] ?></select>
    </div>
    <div class="col-sm-2 text-center" style="margin-top: 60px;">
        <button type="button" class="infraButton" onclick="moverMultasParaDireita()" style="margin-bottom: 15px;">&gt;</button>
        <br>
        <button type="button" class="infraButton" onclick="moverMultasParaEsquerda()">&lt;</button>
    </div>
    <div class="col-sm-5">
        <label for="selMultasSelecionadas"><h5><?= $strTituloLancamentoSecundario ?> <span id="lblTotalLancamentoSecundario" style="font-weight: inherit; font-size: inherit;">(R$ 0,00)</span></h5></label>
        <select id="selMultasSelecionadas" name="selMultasSelecionadas" class="infraSelect form-select" size="10" multiple="multiple"><?= $arrDecisaoProcesso['secundario'] ?></select>
    </div>
</div>

<div class="row" style="margin-top: 12px;">
    <div class="col-sm-12">
        <div class="alert alert-warning" role="alert" style="font-size: calc(1em + 3px);">
            <strong>Atenção:</strong> Ao salvar o fracionamento, os lançamentos vinculados podem ser alterados, retificados ou até cancelados.
        </div>
    </div>
</div>

<div class="row" style="margin-top: 12px;">
    <div class="col-sm-12 text-right">
        <button type="submit" class="infraButton" onclick="return salvarFracionamentoMultas()">Salvar Fracionamento</button>
    </div>
</div>

<?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
<?php PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos); ?>

</form>



<?php
require_once('md_lit_fracionar_multas_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
