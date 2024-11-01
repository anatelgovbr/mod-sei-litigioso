<style type="text/css">
    .linha{
        padding-top: 10px;
    }

    #fieldsetDocInstaurador{
        height: auto;
    }

    .NumeroSEIValidado {
        display: inherit !important;
    }

    .NumeroSEINaoValidado {
        display: none !important;
        margin-top: 5px;
        margin-left: 2px;
    }

    #sbmAdicionarNumeroSei {
        margin-top: 5px;
        margin-left: 2px;
    }

    #sbmICAdicionar {
        margin-left: 12px;
        margin-top: 2px;
    }

    #selMotivos{
        width: calc(100% - 75px);
    }

    .rowFieldSet fieldset {
        height: 100%;
    }

    #txtTipo {
        margin-top: 5px;
    }

    .sbmValidarNumeroSei {
        margin-left: 5px;
    }

    #imgDadoComplementar {
        width: 24px;
    }

    #txtDtaInfracaoPorDispositivo {
        width: 60%;
    }

    #sbmIDNAdicionar {
        margin-top: 2px
    }

    #txtDtaInfracaoInicialPorConduta {
        width:30%;
    }

    #txtDtaInfracaoFinalPorConduta {
        width:30%;
    }

    .mb-3 {
        margin-bottom: 0px !important;
    }

    .infraImgModulo {
        width: 20px;
    }

    .botoes {
        margin-left: 5px;
    }

    .tooltipAjuda {
        background: url(<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif) no-repeat;
        height: 15px;
        width: 20px;
        display: inline-block;
        margin-bottom: -3px;
    }

    .classDispositivoNormativo{
        margin-left: 40px;
    }

    .classCondutas{
        margin-left: 40px;
    }

    .ms-choice{
        border: none;
    }

    .ms-choice span {
        margin-top: 7px;
        margin-left: 19px;
    }

    .campos-info-add {
        padding-bottom: 10px;
    }

    .campo-adicional-container {
        display: flex;
        flex-wrap: wrap;
    }

    .campo-adicional {
        flex: 1 1 calc(33.33% - 10px);
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    .label-campo-adicional {
        margin-left: 16px;
        padding-bottom: 5px;
        padding-top: 5px;
    }

</style>
