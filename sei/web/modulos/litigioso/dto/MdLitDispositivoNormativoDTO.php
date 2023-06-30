<?
    /**
     * ANATEL
     *
     * 15/03/2016 - criado por jaqueline.mendes@cast.com.br - CAST
     *
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitDispositivoNormativoDTO extends InfraDTO
    {

        private $idTipoControleLitigiosoFiltro = "";

        //atributos nao mapeados no banco, serao usados somente para passar valores de filtro para dentro da RN
        private $strCondutaFiltro = "";
        private $idCondutaFiltro  = "";

        public function getStrNomeTabela()
        {
            return 'md_lit_disp_normat';
        }

        public function getIdTipoControleLitigiosoFiltro()
        {
            return $this->idTipoControleLitigiosoFiltro;
        }

        public function setIdTipoControleLitigiosoFiltro($valor)
        {
            $this->idTipoControleLitigiosoFiltro = $valor;
        }

        public function getStrCondutaFiltro()
        {
            return $this->strCondutaFiltro;
        }

        public function setStrCondutaFiltro($valor)
        {
            $this->strCondutaFiltro = $valor;
        }

        public function getIdCondutaFiltro()
        {
            return $this->idCondutaFiltro;
        }

        public function setIdCondutaFiltro($valor)
        {
            $this->idCondutaFiltro = $valor;
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                           'IdDispositivoNormativoLitigioso',
                                           'id_md_lit_disp_normat');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Norma',
                                           'norma');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Url',
                                           'url');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Dispositivo',
                                           'dispositivo');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'Descricao',
                                           'descricao');


            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                           'SinAtivo',
                                           'sin_ativo');


            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                            'SinRevogado',
                                            'sin_revogado');


            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelDispositivoNormativoCondutaDTO');
            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelDispositivoNormativoTipoControleDTO');
            $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelMdLitRelDispositivoNormativoRevogadoDTO');

            $this->configurarPK('IdDispositivoNormativoLitigioso', InfraDTO::$TIPO_PK_NATIVA);


        }
    }
