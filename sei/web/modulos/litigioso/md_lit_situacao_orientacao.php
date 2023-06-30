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
            throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
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
    <h3 style="margin: 0">Orienta��es</h3>
    <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

    <label  style="line-height: 1.5;text-align: justify;">
        <ol>
            <li> A Parametriza��o das Situa��es dever� seguir a Ordem Cronol�gica obedecendo as seguintes obrigatoriedades:
                <ol>
                    <li> Entre a Instaura��o e outros tipos de situa��o dever� existir pelo menos uma Situa��o de Intima��o.
                        <ol>
                            <li> A Instaura��o � uma Situa��o �nica que identifica a Instaura��o do Processo. � obrigatoriamente a primeira Situa��o a acontecer. Ap�s a Instaura��o dever� existir uma Decis�o, independente da apresenta��o da Defesa.</li>
                            <li> A Situa��o da Intima��o registra a intima��o devidamente cumprida, ou seja, do documento com o devido registro da data da intima��o efetivada e n�o do documento de comunica��o em si.</li>
                            <li class="sem-numero">Exemplo: do Aviso de Recebimento com sucesso ou da Certid�o de Intima��o Cumprida; n�o devendo indicar a formaliza��o do Of�cio ou sua expedi��o.</li>
                        </ol>
                    </li>
                    <li> Ap�s  a Situa��o da Intima��o da Instaura��o, poder� existir Situa��o de Intima��o referente a alega��es finais.</li>
                    <li> Ap�s a Instaura��o do Processo, poder� ou n�o existir a apresenta��o da Defesa. Esta Situa��o tamb�m poder� ser utilizada no caso de Impugna��o.
                        <ol>
                            <li> Para a Situa��o de Defesa, o prazo informado ser� controlado. Ap�s a Intima��o o prazo ser� iniciado.</li>
                        </ol>
                    </li>
                    <li> Entre uma Situa��o Decis�ria e outros tipos de Situa��o dever� existir pelo menos uma Situa��o de Intima��o.
                        <ol>
                            <li> Dever�o existir Decis�es ap�s as seguintes Situa��es:
                                <ol>
                                    <li>Ap�s a Instaura��o (independente da apresenta��o da Defesa);</li>
                                    <li>Ap�s Situa��o Recursal, caso existam.</li>
                                </ol>
                            </li>
                        </ol>
                    </li>
                    <li>Ap�s a Situa��o de Intima��o da Decis�o, que � obrigat�ria, poder� existir Situa��o de Intima��o referente a ren�ncia do direito de recorrer.</li>
                    <li>Ap�s Situa��o Recursal poder� existir uma Situa��o de Intima��o, antes que aconte�a a decis�o.
                        <ol>
                            <li>Ap�s a Intima��o referente a Decis�o da Instaura��o, poder�o existir Situa��es Recursais. Para cada Recurso ser� obrigat�rio indicar uma Situa��o de Decis�o e outra de Intima��o.</li>
                        </ol>
                    </li>
                    <li>A Situa��o de Conclus�o dever� ser antecedida SEMPRE por uma Intima��o, ou seja, n�o dever� existir uma conclus�o, sem que tenha existido uma Intima��o imediatamente anterior.</li>
                    <li>
                        Se a Situa��o for parametrizada como Obrigat�ria ou Alega��es, esta Situa��o obrigatoriamente deve ser marcada pelo Usu�rio no momento cronol�gico em que foi parametrizada.
                    </li>
                </ol>
            </li>
        </ol>
        Exemplo: Instaura��o >> Intima��o >> Defesa >> Intima��o de Alegais Finais >> Decis�ria >> Intima��o >>  Intima��o sobre o
        direito de recorrer >> Recursal >> Decis�ria >> Intima��o >> Conclus�o</label>
    <br /><br />
    <label  style="line-height: 1.5;text-align: justify;">
        Existem Situa��es que poder�o ser opcionais, estas definem os documentos que n�o ser�o obrigat�rios no cadastro de Situa��es(no Processo). Estas Situa��es dever�o ser do tipo recursal, defesa e intima��o, sendo que a intima��o ser� apenas a partir da segunda cadastrada.
    </label>

    <?php  PaginaSEI::getInstance()->fecharAreaDados(); ?>

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>