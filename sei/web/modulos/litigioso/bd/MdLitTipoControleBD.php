<?
    /**
     * ANATEL
     *
     * 19/01/2016 - criado por marcelo.bezerra - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitTipoControleBD extends InfraBD
    {

        public function __construct(InfraIBanco $objInfraIBanco)
        {
            parent::__construct($objInfraIBanco);
        }

    }
