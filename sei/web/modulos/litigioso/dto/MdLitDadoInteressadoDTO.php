<?
    /**
     * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
     *
     * 20/04/2017 - criado por Ellyson de Jesus Silva
     *
     * Versão do Gerador de Código: 1.40.1
     */

    require_once dirname(__FILE__) . '/../../../SEI.php';

    class MdLitDadoInteressadoDTO extends InfraDTO
    {

        public function getStrNomeTabela()
        {
            return 'md_lit_dado_interessado';
        }

        public function montar()
        {

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDadoInteressado', 'id_md_lit_dado_interessado');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitControle', 'id_md_lit_controle');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdParticipante', 'id_participante');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Numero', 'numero');

            $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinOutorgado', 'sin_outorgado');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                'IdContatoParticipante',
                'id_contato',
                'participante');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                'NomeContatoParticipante',
                'nome',
                'contato');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                'CnpjContatoParticipante',
                'cnpj',
                'contato');

            $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                'CpfContatoParticipante',
                'cpf',
                'contato');

            $this->configurarPK('IdMdLitDadoInteressado', InfraDTO::$TIPO_PK_NATIVA);

            $this->configurarFK('IdMdLitControle', 'md_lit_controle', 'id_md_lit_controle');
            $this->configurarFK('IdParticipante', 'participante', 'id_participante');

            $this->configurarFK('IdContatoParticipante', 'contato', 'id_contato');
        }
    }