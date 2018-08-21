<?
/**
* ANATEL
*
* 10/07/2017 - criado por ellyson.silva - CAST
*
* Versгo do Gerador de Cуdigo: 1.40.1
*/

require_once dirname(__FILE__). '/../../../SEI.php';

class MdLitDecisaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'md_lit_decisao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitDecisao', 'id_md_lit_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitProcessoSituacao', 'id_md_lit_processo_situacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitRelDisNorConCtr', 'id_md_lit_rel_dis_nor_con_ctr');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitObrigacao', 'id_md_lit_obrigacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitTipoDecisao', 'id_md_lit_tipo_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMdLitEspecieDecisao', 'id_md_lit_especie_decisao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'Multa', 'multa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Prazo', 'prazo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Inclusao', 'dth_inclusao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitProcessoSituacaoMdLitProcessoSituacao', 'mps.id_md_lit_processo_situacao', 'md_lit_processo_situacao mps');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProcedimentoMdLitProcessoSituacao', 'mps.id_procedimento', 'md_lit_processo_situacao mps');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdDocumentoMdLitProcessoSituacao', 'mps.id_documento', 'md_lit_processo_situacao mps');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitObrigacao', 'nome', 'md_lit_obrigacao');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitTipoDecisao', 'nome', 'md_lit_tipo_decisao');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeMdLitEspecieDecisao', 'nome', 'md_lit_especie_decisao');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario', 'nome', 'usuario');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario', 'sigla', 'usuario');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'descricao', 'unidade');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'sigla', 'unidade');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdDispositivoNormativoMdLitRelDisNorConCtr', 'rel.id_md_lit_disp_normat', 'md_lit_rel_dis_nor_con_ctr rel');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdCondutaMdLitRelDisNorConCtr', 'rel.id_md_lit_conduta', 'md_lit_rel_dis_nor_con_ctr rel');
      //retornar a infraзгo
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Dispositivo', 'disp.dispositivo', 'md_lit_disp_normat disp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Norma', 'disp.norma', 'md_lit_disp_normat disp');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Conduta', 'cond.nome', 'md_lit_conduta cond');


    $this->configurarPK('IdMdLitDecisao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMdLitProcessoSituacao', 'md_lit_processo_situacao mps', 'mps.id_md_lit_processo_situacao');
    $this->configurarFK('IdMdLitRelDisNorConCtr', 'md_lit_rel_dis_nor_con_ctr rel', 'rel.id_md_lit_rel_dis_nor_con_ctr');
    $this->configurarFK('IdDispositivoNormativoMdLitRelDisNorConCtr', 'md_lit_disp_normat disp', 'disp.id_md_lit_disp_normat');
    $this->configurarFK('IdCondutaMdLitRelDisNorConCtr', 'md_lit_conduta cond', 'cond.id_md_lit_conduta', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdMdLitObrigacao', 'md_lit_obrigacao', 'id_md_lit_obrigacao',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdMdLitTipoDecisao', 'md_lit_tipo_decisao', 'id_md_lit_tipo_decisao',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdMdLitEspecieDecisao', 'md_lit_especie_decisao', 'id_md_lit_especie_decisao',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdDocumentoMdLitProcessoSituacao', 'documento d', 'd.id_documento');
    $this->configurarFK('IdMdLitSituacao', 'md_lit_situacao ms', 'ms.id_md_lit_situacao');

      //Get Numero Doc Formatado
    $this->configurarFK('IdProtocoloDocumento', 'protocolo pd', 'pd.id_protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProtocoloDocumento','d.id_documento','documento d');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoDocumento','pd.protocolo_formatado','protocolo pd');
    $this->configurarExclusaoLogica('SinAtivo', 'N');

    //Situaзгo
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdMdLitSituacao', 'mps.id_md_lit_situacao', 'md_lit_situacao mps');

  }


    public function getStrInfracao(){
        $infracao = '';
        if($this->isSetStrDispositivo()){
            $infracao .= $this->getStrDispositivo();
        }
        if($this->isSetStrNorma() && $this->getStrNorma() != ''){
            $infracao .= '/'.$this->getStrNorma();
        }
        if($this->isSetStrConduta() && $this->getStrConduta() != ''){
            $infracao .= '/'.$this->getStrConduta();
        }

        return $infracao;
    }
}
?>