<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/04/2017 - criado por Ellyson de Jesus Silva
 *
 * Versão do Gerador de Código: 1.40.1
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdLitRelNumInterModaliDTO extends InfraDTO {

    public function getStrNomeTabela() {
        return 'md_lit_rel_num_inter_modali';
    }

    public function montar() {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitNumeroInteressado', 'id_md_lit_numero_interessado');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitModalidade', 'id_md_lit_modalidade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdParticipanteMdLitNumInteressado', 'id_participante', 'md_lit_numero_interessado');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitModalidade', 'nome', 'md_lit_modalidade');

        $this->configurarPK('IdMdLitModalidade',InfraDTO::$TIPO_PK_INFORMADO);

        $this->configurarFK('IdMdLitNumeroInteressado', 'md_lit_numero_interessado', 'id_md_lit_numero_interessado');
        $this->configurarFK('IdMdLitModalidade', 'md_lit_modalidade', 'id_md_lit_modalidade');
    }
}
?>