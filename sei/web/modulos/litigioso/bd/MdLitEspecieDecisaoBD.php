<?php
    /**
     * ANATEL
     *
     * 20/05/2016 - criado por jaqueline.mendes@castgroup.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitEspecieDecisaoBD extends InfraBD
    {

        public function __construct(InfraIBanco $objInfraIBanco)
        {
            parent::__construct($objInfraIBanco);
        }

    }
