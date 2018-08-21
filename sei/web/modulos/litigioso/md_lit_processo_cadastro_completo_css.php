<?
    /**
     * ANATEL
     *
     * 04/11/2016 - CAST
     *
     */
?>
<?if(0){?><style><?}?>
.NumeroSEIValidado {display:inherit !important;}
.NumeroSEINaoValidado {display:none !important;}

.NumeroSEIAdicionar {:disabled !important;}

#txtNumeroSei            {width:7em}
#txtDtInstauracao        {width:6em}

#txtIDNDispNormat        {width:85%}
#selIDNCondutas          {width:98%;position: relative;top: 3px;}
#selICCondutas           {width:98%}
#txtICDispNormat         {width:85%;position: relative;top: -3px;}

#txtNumeroSeiPS          {width:7em}
#txtNumeroSeiTipoPS      {width:93%}
#txtDtSobrestamentoPS    {width:6em}
#txtNumeroProcessoPS     {width:11em}
#txtNumeroProcessoTipoPS {width:93%}

.tooltipAjuda{
background: url('<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif') no-repeat;
height: 15px;
width: 20px;
display: inline-block;
margin-bottom: -3px;
}


#lblTipo{display: block}
input#txtTipo{width: 85%;}

    /* Motivos */
    #lblMotivos {position: absolute;left: 0%;top: 0%;}
    #txtMotivos {position: absolute;left: 0%;top: 13%;width: 50%;}
    #selMotivos {position: absolute;left: 0%; top: 33%;width: 75%;}

    #imgLupaMotivos {position: absolute;left: 76%;top: 33%;}
    #imgExcluirMotivos {position: absolute;left: 76%;top: 50%;}

    #divMotivos{left: 1%;}


    #fieldMotivo{margin-bottom: 12px;}
    img#imgLupaIDNDispNorma {position: relative;top: 3px;left: 3px;}
    img#imgDtaInfracaoPorDispositivo {position: relative;top: 3px;}
    img#imgLupaTipoProcesso {position: relative;top: 1px;}
    img#imgDtaInfracaoPorConduta {position: relative;top: 4px;}

<?if(0){?></style><?}?>
