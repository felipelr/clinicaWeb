<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP AcompanhamentoFisioterapia
 * @author felipe
 */
class AcompanhamentoFisioterapia extends AppModel
{

    public $useTable = "acompanhamento_fisioterapia";
    public $primaryKey = "idacompanhamentofisioterapia";

    public function retornarPorId($id)
    {
        $dados = $this->query("SELECT * FROM acompanhamento_fisioterapia WHERE idacompanhamentofisioterapia = {$id} LIMIT 1");
        return $dados[0]["acompanhamento_fisioterapia"];
    }

    public function retornarPorIdFichaFisioterapia($id)
    {
        $dados = $this->query("SELECT * FROM acompanhamento_fisioterapia INNER JOIN evento ON (acompanhamento_fisioterapia.id_evento = evento.idevento) WHERE id_ficha_fisioterapia = {$id} ");
        return $dados;
    }

    public function retornarDetalhado($id)
    {
        $dados = $this->query("SELECT * FROM acompanhamento_fisioterapia 
            INNER JOIN evento on (evento.idevento = acompanhamento_fisioterapia.id_evento)            
            WHERE idacompanhamentofisioterapia = {$id} LIMIT 1");
        return $dados[0];
    }

    public function retornarParaAgenda($idfichafisioterapia, $idevento)
    {
        $dados = $this->query("SELECT * FROM acompanhamento_fisioterapia WHERE id_ficha_fisioterapia = {$idfichafisioterapia} and id_evento = {$idevento} and ativo = 1 LIMIT 1");
        return isset($dados[0]["acompanhamento_fisioterapia"]) ? $dados[0]["acompanhamento_fisioterapia"] : null;
    }

    public function listarJQuery($idfichafisioterapia, $search = null, $inicio = null, $totalRegistros = null, $ordenacao = null)
    {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE a.ativo = 1 and a.id_ficha_fisioterapia = $idfichafisioterapia " : " WHERE a.ativo = 1 and a.id_ficha_fisioterapia = $idfichafisioterapia and a.descricao LIKE '%{$search}%' ";

        return $this->query("SELECT a.* FROM acompanhamento_fisioterapia a {$query} {$order} {$sql}; ");
    }

    public function totalRegistroFiltrado($idfichafisioterapia, $search = null)
    {
        $query = (is_null($search)) ? " WHERE a.ativo = 1 and a.id_ficha_fisioterapia = $idfichafisioterapia " : " WHERE a.ativo = 1 and a.id_ficha_fisioterapia = $idfichafisioterapia and a.descricao LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(*) as totalRegistro FROM acompanhamento_fisioterapia a {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro($idfichafisioterapia)
    {
        $dados = $this->query("SELECT count(*) as totalRegistro FROM acompanhamento_fisioterapia a WHERE a.ativo = 1 and a.id_ficha_fisioterapia = $idfichafisioterapia; ");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function excluir($id)
    {
        return $this->query("UPDATE acompanhamento_fisioterapia SET ativo = 0 WHERE idacompanhamentofisioterapia = $id");
    }
}
