<?php
    /**
     * ANATEL
     *
     * 04/02/2016 - criado por marcelo.bezerra@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitFaseBD extends InfraBD
    {

        public function __construct(InfraIBanco $objInfraIBanco)
        {
            parent::__construct($objInfraIBanco);
        }

    }
