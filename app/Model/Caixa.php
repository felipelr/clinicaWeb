<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Caixa
 * @author felip
 */
class Caixa extends AppModel {
    
    public $useTable = "caixa";
    public $primaryKey = "idcaixa";    
    
    public function retornarTodosPorUsuario($idUsuario) {
        $dados = $this->query("SELECT * FROM caixa AS c 
            INNER JOIN caixa_usuario AS cu ON (c.idcaixa = cu.id_caixa)
            WHERE c.ativo = 1 AND c.tipo = 'DIARIO' AND cu.id_usuario = $idUsuario;");
        return $dados;
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM caixa WHERE idcaixa = $id LIMIT 1");
        return $dados[0]["caixa"];
    }
    
    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? "" : " AND c.descricao LIKE '%{$search}%'";
        return $this->query("SELECT c.idcaixa, c.descricao, c.tipo, cli.fantasia FROM caixa c inner join clinica cli on(cli.idclinica = c.id_clinica) WHERE c.ativo = 1 {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? "" : " AND c.descricao LIKE '%{$search}%'";
        $dados = $this->query("SELECT count(c.idcaixa) as totalRegistro FROM caixa c WHERE c.ativo = 1 {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(c.idcaixa) as totalRegistro FROM caixa c WHERE c.ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
}
