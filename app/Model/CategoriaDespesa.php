<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP CategoriaDespesa
 * @author felip
 */
class CategoriaDespesa extends AppModel {
    
    public $useTable = "categoria_despesa";
    public $primaryKey = "idcategoriadespesa";
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM categoria_despesa WHERE idcategoriadespesa = $id and ativo = 1 LIMIT 1");
        return $dados[0]["categoria_despesa"];
    }
    
    public function excluir($id) {
        return $this->query("DELETE FROM categoria_despesa WHERE idcategoriadespesa = $id;");
    }
    
    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM categoria_despesa cd where cd.ativo = 1 order by cd.descricao");
        return $dados;
    }


    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null, $idClinica) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : " LIMIT $inicio, $totalRegistros ";
        $query = (is_null($search)) ? " WHERE d.ativo = 1 and d.id_clinica = {$idClinica} " : " WHERE d.ativo = 1 and d.id_clinica = {$idClinica} and d.descricao LIKE '%{$search}%' ";
        return $this->query("SELECT d.descricao, d.idcategoriadespesa, f.nome, d.quantidade_parcela
            FROM categoria_despesa d inner join favorecido f on f.idfavorecido = d.id_favorecido 
            {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null, $idClinica) {
        $query = (is_null($search)) ? " WHERE d.ativo = 1 and d.id_clinica = {$idClinica} " : " WHERE d.ativo = 1 and d.id_clinica = {$idClinica} and d.descricao LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(d.idcategoriadespesa) as totalRegistro FROM categoria_despesa d {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro($idClinica) {
        $dados = $this->query("SELECT count(d.idcategoriadespesa) as totalRegistro FROM categoria_despesa d WHERE d.ativo = 1 and d.id_clinica = {$idClinica} ");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
}
