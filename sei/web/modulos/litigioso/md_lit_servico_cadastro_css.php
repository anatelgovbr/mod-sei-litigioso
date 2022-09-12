<style type="text/css">

    #fieldSetOrigem {
        height: auto;
    }

    .infraImgModulo {
        width: 20px;
    }

    .linha{
        padding-top: 10px;
    }

    #btnValidar {
        margin-left: 5px;
    }

    #btnMapeamento{
         margin-left: 5px;
     }

    #gridOperacao {
        display: none
    }

    <?php if ($_GET['acao'] == 'md_lit_servico_consultar') { ?>
    #btnValidar, #btnMapeamento {
        display: none
    }

    <?php } ?>
</style>
