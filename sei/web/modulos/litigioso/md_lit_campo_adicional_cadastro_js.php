<script type="text/javascript">

    let editMode = false;
    let editRow = null;

    function inicializar() {
        if(document.getElementById('hdnIdMdLitCamposAd').value){
            selecionarTipo(true);
            atualizarHiddemTabelaOpcoes();
        }
    }

    function OnSubmitForm() {
        return validarCampos();
    }

    function validarCampos() {

        if (!document.getElementById('txtNome').value) {
            $("#divMsg > div > label").html('O campo Nome � obrigat�rio');
            $("#divMsg").show();
            return false;
        }

        if (!document.getElementById('selTipo').value) {
            $("#divMsg > div > label").html('O campo Tipo de Campo � obrigat�rio');
            $("#divMsg").show();
            return false;
        }

        if (validacaoBackEnd()) {
            return false;
        }

        return true;

    }

    function validacaoBackEnd() {
        var isInvalido = false
        var hdnIdMdLitCamposAd = document.getElementById('hdnIdMdLitCamposAd').value;
        var selTipo = document.getElementById('selTipo').value;
        var txtNome = document.getElementById('txtNome').value;
        var hdnIdMdLitTpInforAd = document.getElementById('hdnIdMdLitTpInforAd').value;

        $.ajax({
            type: "POST",
            url: "<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_lit_validar_cadastro_campos_info_add') ?>",
            dataType: "xml",
            async: false,
            data: {
                'hdnIdMdLitCamposAd': hdnIdMdLitCamposAd,
                'selTipo': selTipo,
                'txtNome': txtNome,
                'hdnIdMdLitTpInforAd': hdnIdMdLitTpInforAd
            },
            success: function (data) {
                if ($(data).find('isValido').text() != 1) {
                    $("#divMsg > div > label").html($(data).find('msg').text());
                    $("#divMsg").show();
                    isInvalido = true
                }
            }
        });

        return isInvalido;
    }

    function adicionarOpcao() {
        const nome = document.getElementById('txtNomeSelect').value.trim();
        if (nome === '') return;

        if (editMode) {
            // Update existing row
            editRow.cells[1].innerText = nome;
            editMode = false;
            editRow = null;
            document.getElementById('sbmAdicionarOpcao').innerText = 'Adicionar';
        } else {
            // Add new row
            const table = document.getElementById('tbOpcoesSelect').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);
            const cell4 = newRow.insertCell(3);

            cell1.style.display = 'none';
            cell2.style.textAlign = 'center';
            cell2.innerText = nome;
            cell3.style.display = 'none';
            cell3.innerText = 'S';
            cell4.style.textAlign = 'center';
            cell4.innerHTML = `
            <a href="#" onclick="editarOpcaoSelect(this);" tabindex="1001">
                <img src="/infra_css/svg/alterar.svg?11" title="Alterar nome da op��o" alt="Alterar nome da op��o" class="infraImg">
            </a>
            <a href="#" onclick="excluirOpcaoComboBox(this);" tabindex="1001">
                <img src="/infra_css/svg/excluir.svg?11" title="Excluir Op��o do Combo Box" alt="Excluir Op��o do Combo Box" class="infraImg">
            </a>`;

            // Quando a lista deixar de ser vazia deve remover a tr que diz nao ter op��o cadastrada
            var trNenhumaOpcao = document.getElementById('nenhumaOpcao');
            if (trNenhumaOpcao) {
                trNenhumaOpcao.parentNode.removeChild(trNenhumaOpcao);
            }
        }
        atualizarHiddemTabelaOpcoes();
        document.getElementById('txtNomeSelect').value = '';
    }

    function editarOpcaoSelect(link) {
        const row = link.closest('tr');
        const nome = row.cells[1].innerText;

        document.getElementById('txtNomeSelect').value = nome;
        editMode = true;
        editRow = row;
        document.getElementById('sbmAdicionarOpcao').innerText = 'Atualizar';
    }

    function excluirOpcaoComboBox(link) {
        const row = link.closest('tr');
        row.parentNode.removeChild(row);
    }

    function infraMascaraTexto(input, event, maxLength) {
        return input.value.length < maxLength;
    }

    // FUN��O DE EXCLUIR OS REGISTROS DA GRID
    function excluirOpcaoComboBox(icon){

        if (confirm('Tem certeza que deseja excluir esta op��o?\nClique em Ok para confirmar a exclus�o')) {
            var row = icon.closest('tr');
            row.parentNode.removeChild(row);
            atualizarHiddemTabelaOpcoes();
            adicionarTrListaVazia();
        }
    }

    // FUN��O DE EXCLUIR OS REGISTROS DA GRID
    function desativarOpcaoComboBox(icon){

        if (confirm('Tem certeza que deseja desativar esta op��o?\nClique em Ok para confirmar a exclus�o')) {

            var row = icon.closest('tr');

            row.classList.add('trVermelha');

            // Atualiza o �cone para "ativar"
            icon.setAttribute('onclick', 'ativarOpcaoComboBox(this)');
            icon.setAttribute('src', '<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/reativar.svg?<?= Icone::VERSAO ?>');
            icon.setAttribute('title', 'Ativar Op��o do Combo Box para utilizar nos formul�rios');
            icon.setAttribute('alt', 'Ativar Op��o do Combo Box para utilizar nos formul�rios');

            var tdElements = row.getElementsByTagName('td');
            tdElements[2].innerText = 'N';
            atualizarHiddemTabelaOpcoes();
        }
    }

    // FUN��O DE EXCLUIR OS REGISTROS DA GRID
    function ativarOpcaoComboBox(icon){

        if (confirm('Tem certeza que deseja ativar esta op��o?\nClique em Ok para confirmar a ativa��o')) {

            var row = icon.closest('tr');
            row.classList.remove('trVermelha');
            icon.setAttribute('onclick', 'desativarOpcaoComboBox(this)');
            icon.setAttribute('src', '<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/desativar.svg?<?= Icone::VERSAO ?>');
            icon.setAttribute('title', 'Ativar Op��o do Combo Box para utilizar nos formul�rios');
            icon.setAttribute('alt', 'Ativar Op��o do Combo Box para utilizar nos formul�rios');

            var tdElements = row.getElementsByTagName('td');
            tdElements[2].innerText = 'S';
            atualizarHiddemTabelaOpcoes();

        }
    }

    function informarExclusaoOpcoes(){
        var selectElement = document.getElementById('selTipo');
        var dataValorAntigo = selectElement.getAttribute('data-valor-antigo');

        var selector = `#nenhumaOpcao`;
        if($(selector).length == 0 && dataValorAntigo == '<? echo MdLitCamposAdINT::$COMBO_BOX ?>' && selectElement.value != '<? echo MdLitCamposAdINT::$COMBO_BOX;?>' && selectElement.value != '<? echo MdLitCamposAdINT::$MULTIPLA_SELECAO;?>'){
            return confirm('Ao alterar de Combo Box para outro tipo as op��es ser�o apagadas.\nClique em Ok para confirmar a exclus�o');
        }
        if($(selector).length == 0 && dataValorAntigo == '<? echo MdLitCamposAdINT::$MULTIPLA_SELECAO ?>' && selectElement.value != '<? echo MdLitCamposAdINT::$COMBO_BOX;?>' && selectElement.value != '<? echo MdLitCamposAdINT::$MULTIPLA_SELECAO;?>'){
            return confirm('Ao alterar de Multipla Sele��o para outro tipo as op��es ser�o apagadas.\nClique em Ok para confirmar a exclus�o');
        }
        return true;
    }

    // AO ATUALIZAR A GRID DE OP��ES DO SELECT ATUALIZA O HIDDEN PARA SALVAR NO BACK-END
    function atualizarHiddemTabelaOpcoes(){
       var tabela = document.getElementById('tbOpcoesSelect');
       var linhas = tabela.querySelectorAll('tr');
       var arrayOpcoes = [];

       // Iterar sobre as linhas da tabela
       linhas.forEach(function(linha) {
           var colunas = linha.querySelectorAll('td');

           // Verificar se h� colunas
           if (colunas.length >= 2) {

               // Verificar se a primeira c�lula est� vazia ou n�o
               var id = colunas[0].textContent.trim() || null;

               // Obter o nome da op��o da segunda c�lula
               var nomeOpcao = colunas[1].textContent.trim();

               // Obter o nome da op��o da segunda c�lula
               var situacao = colunas[2].textContent.trim();

               // Adicionar a op��o ao array
               arrayOpcoes.push({ id: id, nome: nomeOpcao, situacao: situacao });
           }
       });

       document.getElementById('opcoesSelect').value = JSON.stringify(arrayOpcoes);
    }

    // AO EXCLUIR O ULTIMO REGISTRO DA GRID ADICIONA A LINHA NENHUMA OP��O CADASTRADA
    function adicionarTrListaVazia(){
       var tabela = document.getElementById('tbOpcoesSelect');
       var linhas = tabela.querySelectorAll('tr');
       if (linhas.length == 1) {

           // Criar uma nova linha para a tabela
           var newRow = document.createElement('tr');
           newRow.id = 'nenhumaOpcao';

           // Criar a c�lula para o nome da op��o
           var cellNome = document.createElement('td');
           cellNome.textContent = 'Nenhuma op��o cadastrada';
           cellNome.style.textAlign = 'center';

           // Adicionar as c�lulas � nova linha
           newRow.appendChild(cellNome);

           // Adicionar a nova linha � tabela
           tabela.appendChild(newRow);
       }
    }

    // FUNCIONALIADE QUE FAZ EXIBIR OU N�O O COMPLEMENTO DE CAMPO QUE NO CASO S�O AS OP��ES DO SELECT
    function selecionarTipo(inicializando){

        if(!informarExclusaoOpcoes()){
            document.getElementById('selTipo').value = document.getElementById('selTipo').getAttribute('data-valor-antigo')
            return;
        }

        var selectTipo = document.getElementById('selTipo');
        document.getElementById('divOpcoesCombo').style.display = 'none';

        //NO FINAL DO CLICLO DA FUN��O DEVE ATUALIZAR O VALOR ANTIGO PARA UMA NOVA COMPARA��O NO FUTURO
        document.getElementById('selTipo').setAttribute('data-valor-antigo', document.getElementById('selTipo').value);


        var tipo = document.getElementById('selTipo').value;
        var divTamanho = document.getElementById('divTamanho');
        var divMinMax = document.getElementById('vlrMaxMin');
        var divData = document.getElementById('fieldSetData');
        var divDocExterno = document.getElementById('divDocExterno');

        var txtTamanho = document.getElementById('txtTamanho');
        var txtValorMinimo = document.getElementById('txtValorMinimo');
        var txtValorMaximo = document.getElementById('txtValorMaximo');

        if (document.getElementById('selTipo') != 'null') {
            if (!inicializando) {
                txtValorMinimo.value = '';
                txtValorMaximo.value = '';
            }
            divData.style.display = 'none';
            divTamanho.style.display = 'none';
            divMinMax.style.display = 'none';
            divDocExterno.style.display = 'none';

            switch (tipo) {

                case '<?=MdLitCamposAdINT::$DATA?>':
                    divData.style.display = 'block';
                    tratarValidacaoData(inicializando);
                    break;
                case '<?=MdLitCamposAdINT::$DOCUMENTOSEI?>':
                    divDocExterno.style.display = '';
                    break;

                case '<?=MdLitCamposAdINT::$COMBO_BOX?>':
                case '<?=MdLitCamposAdINT::$MULTIPLA_SELECAO?>':
                    document.getElementById('divOpcoesCombo').style.display = 'block';
                    break;

                case '<?=MdLitCamposAdINT::$INTEIRO?>':
                    txtTamanho.onkeypress = mascaraTamanhoNumero;
                    divMinMax.style.display = 'flex';
                    txtValorMinimo.onkeypress = mascaraMinimoNumero;
                    txtValorMaximo.onkeypress = mascaraMaximoNumero;
                    break;

                case '<?=MdLitCamposAdINT::$MONETARIO?>':
                    divMinMax.style.display = 'flex';
                    txtValorMinimo.onkeypress = mascaraMinimoDinheiro;
                    txtValorMaximo.onkeypress = mascaraMaximoDinheiro;
                    break;

                case '<?=MdLitCamposAdINT::$TEXTO?>':
                    divTamanho.style.display = 'block';
                    txtTamanho.onkeypress = mascaraTamanhoTexto;
                    break;

                default:
                    alert('Tipo do campo n�o mapeado para visualiza��o.');
            }
        }
    }

    function mascaraTamanhoNumero(event){
        return infraMascaraNumero(this,event,2);
    }

    function mascaraMinimoNumero(event){
        return infraMascaraNumero(document.getElementById('txtValorMinimo'), event, 19);
    }

    function mascaraMaximoNumero(event){
        return infraMascaraNumero(document.getElementById('txtValorMaximo'), event, 19);
    }

    function mascaraMinimoDinheiro(event){
        return infraMascaraDinheiro(document.getElementById('txtValorMinimo'), event, 2, 12);
    }

    function mascaraMaximoDinheiro(event){
        return infraMascaraDinheiro(document.getElementById('txtValorMaximo'), event, 2, 12);
    }

    function mascaraTamanhoTexto(event){
        return infraMascaraNumero(this,event,4);
    }

    function tratarValidacaoData(inicializando){

        if (!inicializando) {
            document.getElementById('txtDataInicial').value = '';
            document.getElementById('txtDataFinal').value = '';
        }

        if (document.getElementById('optDataIntervalo').checked){
            document.getElementById('divDtIniFim').style.display = '';
        }else {
            document.getElementById('divDtIniFim').style.display = 'none';
            if (document.getElementById('optDataAtualFuturo').checked){
                document.getElementById('txtDataInicial').value = '@HOJE@';
                document.getElementById('txtDataFinal').value = '@FUTURO@';
            }else if (document.getElementById('optDataAtualPassado').checked){
                document.getElementById('txtDataInicial').value = '@PASSADO@';
                document.getElementById('txtDataFinal').value = '@HOJE@';
            }else if (document.getElementById('optDataFuturo').checked){
                document.getElementById('txtDataInicial').value = '@AMANHA@';
                document.getElementById('txtDataFinal').value = '@FUTURO@';
            }else if (document.getElementById('optDataPassado').checked){
                document.getElementById('txtDataInicial').value = '@PASSADO@';
                document.getElementById('txtDataFinal').value = '@ONTEM@';
            }
        }
    }


</script>
