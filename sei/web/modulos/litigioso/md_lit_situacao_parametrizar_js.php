<script type="text/javascript">
    <? if ($bolAcaoDesativar) { ?>
    function acaoDesativar(el, id, icone) {
        var descricao = $('#tbSituacao tr[data-linha="' + id + '"] td.situacao').text();

        if (confirm("Confirma desativa��o da Situa��o \"" + descricao + "\"?")) {

            var vitempreenchidodesativar = $('#vItemPreenchidoDesativar_' + id).val();
            if (vitempreenchidodesativar == '1') {
                alert('A desativa��o da Situa��o n�o � permitida, pois sobre ela ainda existem parametriza��es ativas.\n' +
                    '\n' +
                    'Antes, desmarque todas as parametriza��es sobre esta Situa��o e Salve a tela.\n' +
                    '\n' +
                    'Somente depois que salvar a Situa��o sem nenhum par�metro ativo ser� poss�vel Desativar a Situa��o.');
            } else {
                desativarTr(el, id, icone);
            }

        }
    }

    <? } ?>

    function acaoReativar(el, id, icone) {
        var descricao = $('#tbSituacao tr[data-linha="' + id + '"] td.situacao').text();
        ;
        if (confirm("Confirma reativa��o da Situa��o \"" + descricao + "\"?")) {
            ativarTr(el, id, icone);
        }
    }


    <? if ($bolAcaoExcluir) { ?>
    function acaoExcluir(el, id) {
        var descricao = $('#tbSituacao tr[data-linha="' + id + '"] td.situacao').text();
        if (confirm("Confirma exclus�o da Situa��o \"" + descricao + "\"?")) {
            $.ajax({
                url: '<?=$ajaxUrlVerificarVinculo?>',
                method: 'POST',
                data: {idMdLitSituacao: id},
                dataType: 'XML',
                success: function (r) {
                    var possuiVinculo = $(r).find('Vinculo').text();
                    if (possuiVinculo) {
                        alert('A exclus�o da situa��o n�o � permitida, pois existem registros vinculados');
                    } else {
                        // excluirTr(tr, id);
                        excluirTr(id);
                    }
                }
            });
        }
    }

    <? } ?>

    var arrIdExclusao = [];
    var arrIdDesativar = [];
    var arrIdReativar = [];


    function inicializar() {
        validarCampos();

        if ('<?= $_GET['acao'] ?>' == 'md_lit_situacao_visualizar_parametrizar') {
            //desabilitando somente os inputs da tabela, o infraDesabilitarCamposDiv remove a imagem de consultar situa��o
            var el, els, e = 0;
            els = document.getElementById('divInfraAreaTabela').getElementsByTagName('input');
            while (el = els.item(e++)) {
                if (el.type != 'hidden') {
                    if (INFRA_IE > 0) {
                        el.disabled = true;
                    } else {
                        if (el.type == 'checkbox' || el.type == 'radio') {
                            el.disabled = true;
                        } else {
                            el.readOnly = true;
                        }
                    }
                }
            }

        }
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value) == '') {
            alert('Informe o Nome da Fase.');
            document.getElementById('txtNome').focus();
            return false;
        }

        return true;
    }

    function preencherHddIds() {

        document.getElementById("hdnIdAlteracaoOrdem").value = '';
        document.getElementById("hdnOperacaoOrdem").value = '';
        var hdnIdExclusao = document.getElementById('hdnIdExclusao');
        var hdnIdDesativar = document.getElementById('hdnIdDesativar');
        var hdnIdReativar = document.getElementById('hdnIdReativar');

        hdnIdExclusao.value = arrIdExclusao;
        hdnIdDesativar.value = arrIdDesativar;
        hdnIdReativar.value = arrIdReativar;


        var objs = $("tr[id^='sitLitTable']");
        var arrayIds = new Array();


        for (i = 0; i < objs.length; i++) {
            idHtml = objs[i].id;
            idBanco = idHtml.split('_')[1];
            arrayIds.push(idBanco);
        }


        document.getElementById("hdnArraySitLitigioso").value = JSON.stringify(arrayIds);
    }

    function salvar() {
        var frm = document.getElementById('frmSituacaoLitigiosoLista')
        var hdnCadastrarSituacaoLitigioso = document.getElementById('hdnCadastrarSituacaoLitigioso');
        if (validarParametrizacao()) {
            preencherHddIds();
            hdnCadastrarSituacaoLitigioso.value = 'S';
            frm.submit();
        }
    }

    function SomenteNumero(e) {
        var tecla = (window.event) ? event.keyCode : e.which;
        if ((tecla > 47 && tecla < 58)) return true;
        else {
            if (tecla == 8 || tecla == 0) return true;
            else return false;
        }
    }

    function SomenteNumeroVirgula(e) {
        var tecla = (window.event) ? event.keyCode : e.which;
        if (tecla > 47 && tecla < 58) return true;
        if (tecla == 8 || tecla == 0 || tecla == 44) return true;
        return false;
    }

    function validarParametrizacao() {

        if ($('[name="tbSituacao"]').length == 0) {
            return false;
        }
        var instauracao = false, intimacao = false,
            defesa = false, decisoria = false,
            intimacaoDecisao = false, recurso = false,
            decisaoRecurso = false,
            conclusao = false, temIntimacaoDaConclusiva = false,
            prazoDefesa = false, prazoAlegacoes = false, prazoRecurso = false;

        var salvar = true;
        var posicao = 0;
        $('#tbSituacao tr[data-linha]').each(function (key, elementoTr) {
            var linha = $(elementoTr).data('linha');
            var idSituacao = elementoTr.getAttribute("data-linha");
            var linhaLivre = isSituacaoLivre(linha, idSituacao);

            if (!linhaLivre) {
                if (!instauracao) {
                    if ($('input.instauracao[data-linha="' + linha + '"]').prop('checked') == false) {
                        return false;
                    }

                    instauracao = true;
                    return true;
                }

                if ($('input.alegacoes[data-linha="' + linha + '"]').prop('checked') == true) {
                    //verifica se o prazo foi informado
                    if ($('input.prazo[data-linha="' + linha + '"]').val() == '') {
                        prazoAlegacoes = true;
                    }
                }
                if (!intimacao) {
                    if ($('input.intimacao[data-linha="' + linha + '"]').prop('checked') == false) {
                        return false;
                    }

                    intimacao = true;
                    return true;
                }
                if ($('input.defesa[data-linha="' + linha + '"]').prop('checked') == true) {
                    defesa = true;
                    if ($('input.prazo[data-linha="' + linha + '"]').val() == '') {
                        prazoDefesa = true;
                    }

                    return true;
                }

                //No fluxo, ap�s a defesa n�o deve ser obrigat�ria somente a decis�o, mas pode ser intima��o tamb�m pois a decis�o pode ser feita posteriormente
                if (!decisoria) {
                    if ($('input.intimacao[data-linha="' + linha + '"]').prop('checked') == true) {
                        return true;
                    }

                    if ($('input.decisoria[data-linha="' + linha + '"]').prop('checked') == false) {
                        return false;
                    } else {
                        decisoria = true;
                        return true;
                    }
                }

                if (!intimacaoDecisao) {
                    if ($('input.intimacao[data-linha="' + linha + '"]').prop('checked') == false) {
                        return false;
                    } else {
                        intimacaoDecisao = true;
                        recurso = false;
                        return true;
                    }
                }

                if (intimacaoDecisao) {
                    if (!recurso) {
                        if ($('input.recursal[data-linha="' + linha + '"]').prop('checked') == true) {
                            recurso = true;
                            decisaoRecurso = false;
                            if ($('input.prazo[data-linha="' + linha + '"]').val() == '') {
                                prazoRecurso = true;
                            }

                            return true;
                        }
                    }

                    if (!conclusao) {
                        if ($('input.conclusiva[data-linha="' + linha + '"]').prop('checked') == true) {
                            //No fluxo, a Situa��o de Conclus�o dever� ser antecedida SEMPRE por uma Intima��o, ou seja, n�o dever� existir uma conclus�o, sem que tenha existido uma Intima��o imediatamente anterior.
                            var linhaAnterior = $('#tbSituacao tr[data-linha="' + linha + '"]').prev().data('linha');
                            if (!isSituacaoLivre(linhaAnterior, linha) && $('input.intimacao[data-linha="' + linhaAnterior + '"]').prop('checked') == true) {
                                temIntimacaoDaConclusiva = true;
                            }

                            conclusao = true;
                            return true;
                        }
                    }
                }

                if ($('input.decisoria[data-linha="' + linha + '"]').prop('checked') == true) {
                    var linhaPosterior = $('#tbSituacao tr[data-linha="' + linha + '"]').next().data('linha');
                    if ($('input.intimacao[data-linha="' + linhaPosterior + '"]').prop('checked') == false) {
                        intimacaoDecisao = false;
                    }
                }

                //No fluxo, ap�s o recurso n�o deve ser obrigat�ria somente a decis�o, mas pode ser intima��o tamb�m pois a decis�o pode ser feita posteriormente.
                if (recurso) {
                    if ($('input.intimacao[data-linha="' + linha + '"]').prop('checked') == true) {
                        return true;
                    }
                    if ($('input.decisoria[data-linha="' + linha + '"]').prop('checked') == false) {
                        return false;
                    } else {
                        decisaoRecurso = true;
                        intimacaoDecisao = false;
                        return true;
                    }
                }

            }

        });

        //msgs
        if (!instauracao) {
            alert('A Situa��o marcada como Instaura��o dever� ser a primeira a acontecer. ');
            salvar = false;
            return;
        }

        if (instauracao && !intimacao) {
            alert('Ap�s a Instaura��o � obrigat�ria a exist�ncia de ao menos uma Situa��o de Intima��o. ' +
                'Para inserir Situa��es que antecedam a Intima��o, elas devem estar sem marca��o de par�metros especiais.');
            salvar = false;
            return;
        }

        if (!decisoria) {
            alert('Ap�s a Intima��o da Instaura��o e/ou a apresenta��o da Defesa � obrigat�ria a exist�ncia de ao menos uma ' +
                'Decis�o, mesmo que uma Intima��o a anteceda. Para inserir Situa��es que antecedam a Decis�o, elas devem estar sem marca��o.');
            salvar = false;
            return;
        }

        if (!intimacaoDecisao) {
            alert('Ap�s qualquer Decis�o � obrigat�ria a exist�ncia de ao menos uma Intima��o. ' +
                'Para inserir Situa��es que antecedam a Intima��o, elas devem estar sem marca��o de par�metros especiais.');
            salvar = false;
            return;
        }
       if ((!conclusao && !recurso) || !temIntimacaoDaConclusiva) {
            alert('A Conclus�o s� pode ser realizada caso tenha existido uma Intima��o imediatamente anterior a ela. Para inserir Situa��es entre a Conclus�o e a Intima��o, elas devem estar sem marca��o de par�metros especiais.');
            salvar = false;
            return;
        }

        if (recurso && !decisaoRecurso) {
            alert('Ap�s cada Recurso � obrigat�ria a exist�ncia de ao menos uma Decis�o, mesmo que uma Intima��o a anteceda. ' +
                'Para inserir Situa��es que antecedam a Decis�o, elas devem ser do tipo Intima��o, ou estar sem marca��o de par�metros especiais.');
            salvar = false;
            return;
        }

        if (prazoRecurso) {
            alert('O prazo � obrigat�rio para a situ��o marcada como Recurso.');
            salvar = false;
            return;
        }
        if (prazoDefesa) {
            alert('O prazo � obrigat�rio para a situ��o marcada como Defesa.');
            salvar = false;
            return;
        }

        if (prazoAlegacoes) {
            alert('O prazo � obrigat�rio para a situ��o marcada como Alega��es.');
            salvar = false;
            return;
        }

        return salvar;

    }

    function controlarRadios(element) {
        var linha = $(element).data('linha');

        //desabilitando o checkbox Opcional
        $('input.opcional[data-linha="' + linha + '"]').prop('checked', false);

        if ($('#tbSituacao tr[data-linha="' + linha + '"]').hasClass('trVermelha')) {
            limparLinha(linha);
            return;
        }

        var checkar = false;
        if ($(element).prop('checked')) {
            checkar = true;
        }

        if ($('input.alegacoes[data-linha="' + linha + '"]').prop('checked') == true && $('input.intimacao[data-linha="' + linha + '"]').prop('checked') == false) {
            $('input.intimacao[data-linha="' + linha + '"]').prop('checked', true);
            alert('N�o � poss�vel desmarcar a Intima��o desta Situa��o, pois ela est� parametrizada como Alega��es e por esse motivo a Intima��o deve obrigatoriamente estar parametrizada');
            return false;
        }

        //garante que s� vai checar 1 por linha
        $('input[data-linha="' + linha + '"]:checked').prop('checked', false);

        $(element).prop('checked', checkar);

        validarCampos();
    }

    function controlarImputPrazo() {
        if ($('[name="tbSituacao"]').length == 0) {
            return false;
        }

        $('#tbSituacao tr[data-linha]').each(function (key, elementoTr) {
            var linha = $(elementoTr).data('linha');

            if ($('input.defesa[data-linha="' + linha + '"]').prop('checked') == true
                || $('input.alegacoes[data-linha="' + linha + '"]').prop('checked') == true
                || $('input.recursal[data-linha="' + linha + '"]').prop('checked') == true) {
                $('input.prazo[data-linha="' + linha + '"]').prop('readonly', false).show();

                $('#div_dias_uteis_' + linha).show();
                $('#div_dias_corridos_' + linha).show();
            } else {
                $('input.prazo[data-linha="' + linha + '"]').prop('readonly', true).val('').hide();
                $('#div_dias_uteis_' + linha).hide();
                $('#div_dias_corridos_' + linha).hide();
            }
            if ($('#tbSituacao tr[data-linha="' + linha + '"]').hasClass('trVermelha')) {
                limparLinha(linha);
                habilitarDesabilitarLinha(linha, true);
            }
        });
    }

    function controlarCheckboxOpcional() {
        // var table = document.getElementById('tbSituacao');
        if ($('[name="tbSituacao"]').length == 0) {
            return false;
        }

        var primeiraIntimacao = true;
        $('#tbSituacao tr[data-linha]').each(function (key, elementoTr) {
            var linha = $(elementoTr).data('linha');

            //verificar se opcional pode ser checked
            //so pode se for defesa ou recursal ou decis�o ou a partir da segunda intima��o
            if ($('input.defesa[data-linha="' + linha + '"]').prop('checked') == true || $('input.recursal[data-linha="' + linha + '"]').prop('checked') == true) {
                $('input.opcional[data-linha="' + linha + '"]').prop('disabled', false).prop('checked', true);
                //alegacoes deve ter intima��o e n�o pode ser opcional
            } else if ($('input.alegacoes[data-linha="' + linha + '"]').prop('checked') == true) {
                $('input.opcional[data-linha="' + linha + '"]').prop('disabled', true).prop('checked', false);
            } else if (!primeiraIntimacao && $('input.intimacao[data-linha="' + linha + '"]').prop('checked') == true || $('input.decisoria[data-linha="' + linha + '"]').prop('checked') == true) {
                $('input.opcional[data-linha="' + linha + '"]').prop('disabled', false);
            } else {
                $('input.opcional[data-linha="' + linha + '"]').prop('disabled', true).prop('checked', false);
            }

            //passou da primeira intima��o
            if ($('input.intimacao[data-linha="' + linha + '"]').prop('checked') == true) {
                primeiraIntimacao = false;
            }

        });

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

    function reordenarTr(tr, proximaTr) {
        var td1 = tr.getElementsByTagName('td');
        var ordem1 = td1[0].children[0];

        var td2 = proximaTr.getElementsByTagName('td');
        var ordem2 = td2[0].children[0];

        var ordemAux = ordem1.value;
        ordem1.value = ordem2.value;
        ordem2.value = ordemAux;
    }

    function excluirTr(id) {
        arrIdExclusao.push(id);
        $('#tbSituacao tr[data-linha="' + id + '"]').remove();
        // tr.remove();
    }

    function desativarTr(element, id, icone) {
        var img = $(element).children();

        if (icone != 1) {
            $(element).click(function () {
                acaoReativar(element, id, icone);
            });
        }

        $('#imgDesativar_' + id).attr('src', '/infra_css/svg/reativar.svg');
        $('#imgDesativar_' + id).attr('title', 'Reativar Situa��o');
        $('#imgDesativar_' + id).attr('alt', 'Reativar Situa��o');
        $('#imgDesativar_' + id).attr('onclick', "acaoReativar(this,'" + id + "',1);");
        $('#imgDesativar_' + id).attr('id', 'imgReativar_' + id);

        $('#tbSituacao tr[data-linha="' + id + '"]').attr('class', 'trVermelha');

        limparLinha(id);
        habilitarDesabilitarLinha(id, true);

        //remover o id do array arrIdReativar
        var idx = arrIdReativar.indexOf(id);
        if (idx !== -1) {
            arrIdReativar.splice(idx, 1);
        }
        arrIdDesativar.push(id);

    }

    function ativarTr(element, id, icone) {
        var img = $(element).children();

        if (icone != 1) {
            $(element).click(function () {
                acaoDesativar(element, id, icone);
            });
        }

        $('#imgReativar_' + id).attr('src', '/infra_css/svg/desativar.svg');
        $('#imgReativar_' + id).attr('title', 'Desativar Situa��o');
        $('#imgReativar_' + id).attr('alt', 'Desativar Situa��o');
        $('#imgReativar_' + id).attr('onclick', "acaoDesativar(this,'" + id + "',1);");
        $('#imgReativar_' + id).attr('id', 'imgDesativar_' + id);

        $('#tbSituacao tr[data-linha="' + id + '"]').attr('class', 'infraTrClara');

        limparLinha(id);
        habilitarDesabilitarLinha(id, false);

        //remover o id do array arrIdReativar
        var idx = arrIdDesativar.indexOf(id);
        if (idx !== -1) {
            arrIdDesativar.splice(idx, 1);
        }
        arrIdReativar.push(id);

    }

    function limparLinha(idLinha) {
        $('input[data-linha="' + idLinha + '"]').prop('checked', false);
        $('input[data-linha="' + idLinha + '"][type="text"]').val('');
    }

    function habilitarDesabilitarLinha(linha, disabled) {
        $('input[data-linha="' + linha + '"]').prop('disabled', disabled);
        if (disabled == false) {
            $('input[name="opcional_' + linha + '"]').prop('disabled', true);
        }
    }

    function modalOrientacoes() {
        parent.infraAbrirJanelaModal('<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_lit_situacao_orientacao') ?>',
            780,
            580);
    }

    function selecionarOpcional(element) {
        var linha = $(element).data('linha');
        if ($('input.defesa[data-linha="' + linha + '"]').prop('checked')) {
            alert("A situa��o defesa e obrigat�rio ser opcional!");
            $(element).prop('checked', true);
        }
        if ($('input.recursal[data-linha="' + linha + '"]').prop('checked')) {
            alert("A situa��o Recurso deve obrigatoriamente estar marcada como Opcional!");
            $(element).prop('checked', true);
        }
        selecionarObrigatoria(linha);

        return true;
    }

    /**
     * Situa��o livre � caraterizado quando n�o ha nenhuma sinaliza��o de instru��o, Intima��o, defesa, decis�o, recurso ou conclus�o
     * @param idLinha
     * @returns {boolean}
     */
    function isSituacaoLivre(idLinha, posicao) {
        var qtdPrenchido = $('tr#sitLitTable_' + idLinha + '> td > input:checked').not('[name="obrigatoria_' + idLinha + '"], [type="hidden"]').length;
        if (qtdPrenchido > 0) {
            return false;
        }
        var quantidade = 0;
        if($('#instauracao_' + posicao).prop('checked')) {
            quantidade++;
        }
        if($('#intimacao_' + posicao).prop('checked')) {
            quantidade++;
        }
        if($('#defesa_' + posicao).prop('checked')) {
            quantidade++;
        }
        if($('#alegacoes_' + posicao).prop('checked')) {
            quantidade++;
        }
        if($('#decisoria_' + posicao).prop('checked')) {
            quantidade++;
        }
        if($('#recursal_' + posicao).prop('checked')) {
            quantidade++;
        }
        if($('#conclusiva_' + posicao).prop('checked')) {
            quantidade++;
        }
        if($('#opcional_' + posicao).prop('checked')) {
            quantidade++;
        }
        if($('#obrigatoria_' + posicao).prop('checked')) {
            quantidade++;
        }
        if(quantidade > 0){
            return false;
        }

        return true;
    }

    function selecionarObrigatoria(linha) {
        if (!isSituacaoLivre(linha)) {
            $('input.obrigatoria[data-linha="' + linha + '"]').prop('checked', false).prop('disabled', true);
        } else {
            $('input.obrigatoria[data-linha="' + linha + '"]').prop('disabled', false);
            if ($('input.obrigatoria[data-linha="' + linha + '"]').prop('checked')) {
                $('input[type="radio"][data-linha="' + linha + '"], input[type="checkbox"][data-linha="' + linha + '"]').not('input[data-linha="' + linha + '"].obrigatoria').prop('disabled', 'true');
            } else {
                $('input[type="radio"][data-linha="' + linha + '"], input[type="checkbox"][data-linha="' + linha + '"]').not('input[data-linha="' + linha + '"].obrigatoria, input[data-linha="' + linha + '"].opcional').prop('disabled', false)
            }
        }
    }

    function validarCheckboxObrigatoria() {
        $('[name^="obrigatoria_"]').each(function (key, element) {
            var linha = $(element).data('linha');
            selecionarObrigatoria(linha);
        });
    }

    function selecionarAlegacoes(linha) {
        //caso a alega��o esteja marcada marca a intima��o tambem
        if ($('input.alegacoes[data-linha="' + linha + '"]').prop('checked') == true) {
            //limpa a linha pra garantir
            limparLinha(linha);
            //marca a alega��o e a intima��o
            $('input.alegacoes[data-linha="' + linha + '"]').prop('checked', true);
            $('input.intimacao[data-linha="' + linha + '"]').prop('checked', true);
            $('input.opcional[data-linha="' + linha + '"]').prop('checked', false).prop('disabled', true);
        }

        validarCampos();

    }

    function validarCampos() {
        controlarCheckboxOpcional();

        //revalida o campo obrigatoria
        validarCheckboxObrigatoria();

        controlarImputPrazo();
    }

    function controlarRadiosTpPrazo(radio) {
        var linha = $(radio).data('linha');
        if (radio.id.startsWith('dias_uteis_')) {
            $('#dias_corridos_' + linha).prop('checked', false);
        } else if (radio.id.startsWith('dias_corridos_')) {
            $('#dias_uteis_' + linha).prop('checked', false);
        }
    }

    function limparCaracteresInvalidosPrazo(input) {
        let valores = input.value.split(',');
        valores = valores.filter(function(valor) {
            return valor.trim() !== '' && valor.trim() !== '0';
        });
        input.value = valores.join(',');
        if (input.value.endsWith(',')) {
            input.value = input.value.slice(0, -1);
        }
    }
</script>
