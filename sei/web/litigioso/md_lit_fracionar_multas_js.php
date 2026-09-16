<script>
    function moverMultasParaDireita() {
        moverItensSelect(document.getElementById('selMultasDisponiveis'), document.getElementById('selMultasSelecionadas'));
    }

    function moverMultasParaEsquerda() {
        moverItensSelect(document.getElementById('selMultasSelecionadas'), document.getElementById('selMultasDisponiveis'));
    }
    function moverItensSelect(origem, destino) {
        for (var i = origem.options.length - 1; i >= 0; i--) {
            if (origem.options[i].selected) {
                destino.appendChild(origem.options[i]);
            }
        }
        atualizarTotaisFracionamento();
    }

    function extrairValorOpcao(opcao) {
        if (!opcao) {
            return 0;
        }
        var valorAttr = opcao.getAttribute('data-valor');
        if (!valorAttr) {
            return 0;
        }
        var numero = parseFloat(valorAttr);
        return isNaN(numero) ? 0 : numero;
    }

    function calcularTotalSelect(selectEl) {
        if (!selectEl || !selectEl.options) {
            return 0;
        }
        var total = 0;
        for (var i = 0; i < selectEl.options.length; i++) {
            total += extrairValorOpcao(selectEl.options[i]);
        }
        return total;
    }

    function formatarMoeda(valor) {
        var numero = isNaN(valor) ? 0 : valor;
        return 'R$ ' + numero
            .toFixed(2)
            .replace('.', ',')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function atualizarTotaisFracionamento() {
        var selectInicial = document.getElementById('selMultasDisponiveis');
        var selectSecundario = document.getElementById('selMultasSelecionadas');
        var totalInicial = calcularTotalSelect(selectInicial);
        var totalSecundario = calcularTotalSelect(selectSecundario);

        var lblInicial = document.getElementById('lblTotalLancamentoInicial');
        if (lblInicial) {
            lblInicial.textContent = '(' + formatarMoeda(totalInicial) + ')';
        }

        var lblSecundario = document.getElementById('lblTotalLancamentoSecundario');
        if (lblSecundario) {
            lblSecundario.textContent = '(' + formatarMoeda(totalSecundario) + ')';
        }
    }

    window.addEventListener('load', atualizarTotaisFracionamento);

    function salvarFracionamentoMultas() {
        var selectEsquerda = document.getElementById('selMultasDisponiveis');
        if (!selectEsquerda || selectEsquerda.options.length === 0) {
            alert('A primeira coluna não pode ficar vazia.');
            return false;
        }

        if (!confirm('Atenção: o fracionamento de multas impacta diretamente os lançamentos e o histórico do processo. Deseja salvar o fracionamento?')) {
            return false;
        }

        var selectDireita = document.getElementById('selMultasSelecionadas');

        var idsEsquerda = [];
        for (var i = 0; i < selectEsquerda.options.length; i++) {
            idsEsquerda.push(selectEsquerda.options[i].value);
        }

        var idsDireita = [];
        for (var i = 0; i < selectDireita.options.length; i++) {
            idsDireita.push(selectDireita.options[i].value);
        }

        var idLancamentoInicial = document.getElementById('hdnIdLancamentoInicial').value;
        var idLancamentoSecundario = document.getElementById('hdnIdLancamentoSecundario').value;

        $.ajax({
            type: 'POST',
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_fracionar_multas_modal') ?>",
            async: false,
            data: {
                id_lancamento_inicial : idLancamentoInicial,
                id_lancamento_secundario : idLancamentoSecundario,
                inicial : idsEsquerda,
                secundario : idsDireita
            },
            success: function (result) {
                var topWin = window.top || window;
                topWin._mdLitFracionarMultasSalvo = true;
                infraFecharJanelaModal();
            },
            error: function () {
                alert('Erro ao salvar fracionamento.');
            }
        });
        return false;
    }
</script>
