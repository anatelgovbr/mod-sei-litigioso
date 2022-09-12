<?
    /**
     * ANATEL
     *
     * 04/02/2016 - criado por marcelo.bezerra@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitCondutaDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_conduta';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdCondutaLitigioso',
                                           'id_md_lit_conduta');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Nome',
                                           'nome');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');


            $this->configurarPK('IdCondutaLitigioso', InfraDTO::$TIPO_PK_NATIVA);

        }
    }
