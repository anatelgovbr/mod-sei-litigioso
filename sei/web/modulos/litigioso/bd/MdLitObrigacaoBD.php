<?php
    /**
     * ANATEL
     *
     * 16/05/2016 - criado por CAST
     *
     * Vers�o do Gerador de C�digo:
     *
     * Vers�o no CVS:
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitObrigacaoBD extends InfraBD
    {

        public function __construct(InfraIBanco $objInfraIBanco)
        {
            parent::__construct($objInfraIBanco);
        }

    }
