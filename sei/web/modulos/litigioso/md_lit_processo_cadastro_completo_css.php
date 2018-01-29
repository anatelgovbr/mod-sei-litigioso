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
#selIDNCondutas          {width:98%}
#selICCondutas           {width:98%}
#txtICDispNormat         {width:85%}

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
<?if(0){?></style><?}?>
