<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 26/04/2018
 * Time: 15:34
 */
?>
<script type="text/javascript">
    var objLupaDecisaoAntecedente = null;
    var objLupaDecisaoReicidencia = null;
    var objAutoCompletarDecisaoRein = null;
    var objAutoCompletarDecisaoAnte = null;

    function inicializar() {



        //Carregando Componentes Lupa e AutoCompletar
        objLupaDecisaoReicidencia = new infraLupaSelect('selDecRein', 'hdnTipoDeciRein', '<?=$strLinkDecisaoSelecaoReicidencia?>');
        objLupaDecisaoAntecedente = new infraLupaSelect('selDecAntec', 'hdnTipoDeciAnte', '<?=$strLinkDecisaoSelecaoAntecedente?>');

        objAutoCompletarDecisaoAnte = new infraAjaxAutoCompletar('hdnIdTipoDeciAnte', 'txtDecAntec', '<?php echo $strLinkAjaxAutoComplDecisao?>');
        objAutoCompletarDecisaoRein = new infraAjaxAutoCompletar('hdnIdTipoDeciRein', 'txtDecRein', '<?=$strLinkAjaxAutoComplDecisao?>');
        objAutoCompletarDecisaoRein.tamanhoMinimo = 3;
        objAutoCompletarDecisaoAnte.tamanhoMinimo = 3;
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        objAutoCompletarDecisaoAnte.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtDecAntec').value;
        };

        objAutoCompletarDecisaoAnte.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
                var desc =  $("<pre>").html(descricao).text();

                objLupaDecisaoAntecedente.adicionar(id, desc, document.getElementById('txtDecAntec'));
            }
        };

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        objAutoCompletarDecisaoRein.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtDecRein').value;
        };

        objAutoCompletarDecisaoRein.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
              var desc =  $("<pre>").html(descricao).text();
                objLupaDecisaoReicidencia.adicionar(id, desc, document.getElementById('txtDecRein'));

            }
        };

    }

    function validarAntecedentes() {

        var prazoAntecedencia = null;
        var orientacaoAnte = null;
        var idDecisaoAnte = null;

        prazoAntecedencia = document.getElementById('txtPrazoAntec').value;
        orientacaoAnte = infraTrim(CKEDITOR.instances['txaConteudoAntecedente'].getData());
        idDecisaoAnte = document.getElementById('hdnTipoDeciAnte').value;

        if (prazoAntecedencia == '' || prazoAntecedencia == null || prazoAntecedencia == 0) {
            alert('Informe o Prazo em anos dos Antecedentes.');
            return false;
        }
        if (prazoAntecedencia > 99) {
            alert('Informe o Prazo em anos dos Antecedentes de até 99 anos.');
            return false;
        }
        if(orientacaoAnte == '' || orientacaoAnte == null){
            alert('Informe as Orientações dos Antecedentes.');
            return false
        }
        if (idDecisaoAnte == '' || idDecisaoAnte == null) {
            alert('Informe os Tipos de Decisão dos Antecedentes.');
            return false;
        }
        return true;

    }

    function validarReicidencias() {

        var prazoReicidencia = null;
        var orientacaoRein = null;
        var idDecisaoRein = null;

        prazoReicidencia = document.getElementById('txtPrazoRein').value;
        orientacaoRein = infraTrim(CKEDITOR.instances['txaConteudo'].getData())
        idDecisaoRein = document.getElementById('hdnTipoDeciRein').value;

        if (prazoReicidencia == '' || prazoReicidencia == null || prazoReicidencia == 0) {
            alert('Informe o Prazo em anos das Reincidências.');
            return false;
        }
        if (prazoReicidencia > 99) {
            alert('Informe o Prazo em anos das Reincidências de até 99 anos.');
            return false;
        }

        if(orientacaoRein == '' || orientacaoRein == null){
            alert('Informe as Orientações das Reincidências.');
            return false
        }
        if (idDecisaoRein == '' || idDecisaoRein == null) {
            alert('Informe os Tipos de Decisão das Reincidências.');
            return false;
        }
        return true;

    }

    function onSubmit() {
        if (validarReicidencias()) {
            if (validarAntecedentes()) {
                document.getElementById('frmReincidenciaAntecedenteCadastro').submit();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
</script>
