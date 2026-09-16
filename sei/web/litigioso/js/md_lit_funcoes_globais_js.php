<script type="text/javascript">

    function apagarMapear() {
        if (document.getElementById('tableParametroEntrada') != null || document.getElementById('tableParametroSaida') != null) {
            document.getElementById('tableParametroEntrada').remove();
            document.getElementById('tableParametroSaida').remove();
        }
    }

    function changeOrigem() {
        var rdoIntegracao = document.getElementById('rdoIntegracao').checked;
        var divIntegracao = document.getElementById('divIntegracao');
        var divManual = document.getElementById('divManual');

        if (rdoIntegracao) {
            divIntegracao.style.display = 'block';
            divManual.style.display = 'none';
        } else {
            divIntegracao.style.display = 'none';
            divManual.style.display = 'block';
        }
    }
    
    function exibirAlert(msg,tipoMsg='danger'){
        let divMsg = '<div class="alert alert-'+ tipoMsg +' alert-dismissible fade show" style="font-size:.875rem; top:0.25rem; margin-bottom: 14px !important; width:100%; margin:0 auto;" role="alert">'
                        + msg +
                        '<button type="button" class="close media h-100" data-dismiss="alert" aria-label="Fechar Mensagem" aria-labelledby="divInfraMsg0">'+
                        '<span aria-hidden="true" class="align-self-center"><b>X</b></span>'+
                        '</button>'+
                    '</div>';

        $('#divInfraBarraComandosSuperior').after( divMsg );

        scrollTela('divInfraBarraLocalizacao');
    }

    function fechar() {
        location.href = "<?= $strUrlFechar ?>";
    }

    function moverAbaixo(el) {
        var tr = el.parentNode.parentNode;
        var tb = tr.parentNode;

        var proximaTr1 = retornaProximaTr(tr);
        var proximaTr2 = retornaProximaTr(proximaTr1)

        if (proximaTr2) {
            reordenarTr(tr, proximaTr1)
            tb.insertBefore(tr, proximaTr2);
        }
    }

    function moverAcima(el) {
        var tr = el.parentNode;
        while (tr != null) {
            if (tr.nodeName == 'TR') {
                break;
            }
            tr = tr.parentNode;
        }
        var tb = tr.parentNode;
        var trAnterior = retornaTrAnterior(tr);
        if (trAnterior) {
            reordenarTr(trAnterior, tr);
            tb.insertBefore(tr, trAnterior);
        }
    }

    function novo() {
        location.href = "<?= $strUrlNovo ?>";
    }

    function reordenarTr(tr, proximaTr) {
        var td1 = tr.getElementsByTagName('td');
        var ordem1 = td1[0].children[0];

        var td2 = proximaTr.getElementsByTagName('td');

        var ordem2 = td2[0].children[0];

        var ordemAux = ordem1.value;
        ordem1.value = ordem2.value;
        ordem2.value = ordemAux;

        salvarOrdenacao();
    }

    function resizeIFrameOrientacao() {
        var id = 'ifrOrientacaoHTML';
        var ifrm = document.getElementById(id);
        ifrm.style.visibility = 'hidden';
        ifrm.style.height = "10px";

        var doc = ifrm.contentDocument ? ifrm.contentDocument : ifrm.contentWindow.document;
        doc = doc || document;
        var body = doc.body, html = doc.documentElement;

        var width = Math.max(body.scrollWidth, body.offsetWidth,
            html.clientWidth, html.scrollWidth, html.offsetWidth);
        ifrm.style.width = '100%';

        var height = Math.max(body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight);
        ifrm.style.height = height + 'px';

        ifrm.style.visibility = 'visible';
    }

    function retornaProximaTr(tr) {
        if (tr == null) {
            return false;
        }
        var proximaTr = tr.nextSibling;
        while (proximaTr != null && proximaTr.nodeType != null && proximaTr.nodeType != 1) {
            proximaTr = proximaTr.nextSibling;
        }
        return proximaTr;
    }

    function retornarDate(hdnDtDefault) {
        var arrDtEntrada = hdnDtDefault.split('/');
        var data = new Date();

        data.setDate(arrDtEntrada[0]);
        data.setMonth(arrDtEntrada[1] - 1);
        data.setFullYear(arrDtEntrada[2]);

        return data;

    }
    
    function retornaTrAnterior(tr) {
        if (tr == null) {
            return false;
        }
        var trAnterior = tr.previousSibling;
        while (trAnterior != null && trAnterior.nodeType != null && trAnterior.nodeType != 1) {
            trAnterior = trAnterior.previousSibling;
        }
        return trAnterior;
    }

    function scrollTela(idEle , top = 80){
        // scroll barra de rolagem automatica
        var nivel = document.getElementById( idEle ).offsetTop + top;
        divInfraMoverTopo = document.getElementById("divInfraAreaTelaD");
        $( divInfraMoverTopo ).animate( { scrollTop: nivel } , 600 );
    }

    function SomenteNumero(e) {
        var tecla = (window.event) ? event.keyCode : e.which;
        if ((tecla > 47 && tecla < 58)) return true;
        else {
            if (tecla == 8 || tecla == 0) return true;
            else return false;
        }
    }

    /**
     * Validação para nao permitir que um tipo de decisão tenha associada especies com tipo de miltas diferentes, por integracao e por valor
     * @param arrEspecies
     * @returns {boolean}
     */
    function validarEspecieDecisaoGestaoMultasDiferentes(arrEspecies) {
        var valid = true;

        $.ajax({
            type: "POST",
            url: "<?php echo SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_especie_decisao');?>",
            dataType: "xml",
            async: false,
            data: {'arrEspeciesId': arrEspecies},
            success: function (data) {
                if ($(data).find('resultado').text() == '0') {
                    valid = false;
                }
            },
            error: function (msgError) {
                msgCommit = "Erro ao processar o validação do do SEI: " + msgError.responseText;
            },
            complete: function (result) {
                infraAvisoCancelar();
            }
        });
        return {'valid': valid, 'mensagem': "Não é possivel selecionar Espécies de Decisão com Indicação de Multa de tipos distintos (Gestão por Integração e Apenas Indicação de Valor) para o mesmo processo.\n" +
                "\nEscolha novamente as Espécies de Decisão para Indicação de Multa em conformidade com as novas parametrizações da Administração do SEI ou entre em contato com o Gestor do Controle Litigioso para dúvidas a respeito."};
    }

    function validarFormatoData(obj) {
        var validar = infraValidarData(obj, false);
        if (!validar) {
            alert('Data Inválida!');
            obj.value = '';
        }
    }

    function addEventoEnter() {
        document.addEventListener("keypress", function (evt) {
            var key_code = evt.keyCode ? evt.keyCode :
                evt.charCode ? evt.charCode :
                    evt.which ? evt.which : void 0;

            if (key_code == 13) {
                pesquisar();
            }

        });
    }

</script>
