<?
/**
 * ANATEL
 *
 * 02/02/2016 - criado por jaqueline.mendes - CAST
 *
 */

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();

    //colocando a pagina sem menu e titulo inicial
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    switch ($_GET['acao']) {
        case 'md_lit_situacao_orientacao':
            break;
        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }


    $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="infraFecharJanelaModal();" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';

} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
#frmSituacaoLitigiosoLista ol { counter-reset: item; padding-left: 10px;line-height: 1.8; }
#frmSituacaoLitigiosoLista li{ display: block }
ol {counter-reset: item;}
li {display: block;}
li:before {content: counters(item, ".") " ";counter-increment: item;}
li.sem-numero:before{counter-increment: unset;content: " ";}
li.sem-numero {padding: 10px 0;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
PaginaSEI::getInstance()->abrirAreaDados();
?>
<form id="frmSituacaoLitigiosoLista" method="post"
      action="<?= PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&id_tipo_processo_litigioso=' . $_GET['id_tipo_processo_litigioso'] . '&acao_origem=' . $_GET['acao'])) ?>">
    <h3 style="margin: 0">Orientações</h3>
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

    <label  style="line-height: 1.5;text-align: justify;">
        <ol>
            <li> A Parametrização das Situações deverá seguir a Ordem Cronológica obedecendo as seguintes obrigatoriedades:
                <ol>
                    <li> Entre a Instauração e outros tipos de situação deverá existir pelo menos uma Situação de Intimação.
                        <ol>
                            <li> A Instauração é uma Situação única que identifica a Instauração do Processo. É obrigatoriamente a primeira Situação a acontecer. Após a Instauração deverá existir uma Decisão, independente da apresentação da Defesa.</li>
                            <li> A Situação da Intimação registra a intimação devidamente cumprida, ou seja, do documento com o devido registro da data da intimação efetivada e não do documento de comunicação em si.</li>
                            <li class="sem-numero">Exemplo: do Aviso de Recebimento com sucesso ou da Certidão de Intimação Cumprida; não devendo indicar a formalização do Ofício ou sua expedição.</li>
                        </ol>
                    </li>
                    <li> Após  a Situação da Intimação da Instauração, poderá existir Situação de Intimação referente a alegações finais.</li>
                    <li> Após a Instauração do Processo, poderá ou não existir a apresentação da Defesa. Esta Situação também poderá ser utilizada no caso de Impugnação.
                        <ol>
                            <li> Para a Situação de Defesa, o prazo informado será controlado. Após a Intimação o prazo será iniciado.</li>
                        </ol>
                    </li>
                    <li> Entre uma Situação Decisória e outros tipos de Situação deverá existir pelo menos uma Situação de Intimação.
                        <ol>
                            <li> Deverão existir Decisões após as seguintes Situações:
                                <ol>
                                    <li>Após a Instauração (independente da apresentação da Defesa);</li>
                                    <li>Após Situação Recursal, caso existam.</li>
                                </ol>
                            </li>
                        </ol>
                    </li>
                    <li>Após a Situação de Intimação da Decisão, que é obrigatória, poderá existir Situação de Intimação referente a renúncia do direito de recorrer.</li>
                    <li>Após Situação Recursal poderá existir uma Situação de Intimação, antes que aconteça a decisão.
                        <ol>
                            <li>Após a Intimação referente a Decisão da Instauração, poderão existir Situações Recursais. Para cada Recurso será obrigatório indicar uma Situação de Decisão e outra de Intimação.</li>
                        </ol>
                    </li>
                    <li>A Situação de Conclusão deverá ser antecedida SEMPRE por uma Intimação, ou seja, não deverá existir uma conclusão, sem que tenha existido uma Intimação imediatamente anterior.</li>
                    <li>
                        Se a Situação for parametrizada como Obrigatória ou Alegações, esta Situação obrigatoriamente deve ser marcada pelo Usuário no momento cronológico em que foi parametrizada.
                    </li>
                </ol>
            </li>
        </ol>
        Exemplo: Instauração >> Intimação >> Defesa >> Intimação de Alegais Finais >> Decisória >> Intimação >>  Intimação sobre o
        direito de recorrer >> Recursal >> Decisória >> Intimação >> Conclusão</label>
    <br /><br />
    <label  style="line-height: 1.5;text-align: justify;">
        Existem Situações que poderão ser opcionais, estas definem os documentos que não serão obrigatórios no cadastro de Situações(no Processo). Estas Situações deverão ser do tipo recursal, defesa e intimação, sendo que a intimação será apenas a partir da segunda cadastrada.
    </label>

    <?php  PaginaSEI::getInstance()->fecharAreaDados(); ?>

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>