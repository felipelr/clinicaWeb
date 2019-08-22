<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP CategoriaAula
 * @author felipe
 */
class CategoriaAula extends AppModel {

    public $useTable = "categoria_aula";
    public $primaryKey = "idcategoriaaula";

    public function retornarTodos() {
        $dados = $this->query("SELECT * FROM categoria_aula c where c.ativo = 1 order by c.descricao");
        return $dados;
    }

    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM categoria_aula WHERE idcategoriaaula = $id LIMIT 1");
        return $dados[0]["categoria_aula"];
    }

    public function excluir($id) {
        return $this->query("UPDATE categoria_aula SET ativo = 0 WHERE idcategoriaaula = $id");
    }

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE c.ativo = 1 " : " WHERE c.ativo = 1 and c.descricao LIKE '%{$search}%' ";
        return $this->query("SELECT c.* FROM categoria_aula c {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE c.ativo = 1 " : " WHERE c.ativo = 1 and c.descricao LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(c.idcategoriaaula) as totalRegistro FROM categoria_aula c {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idcategoriaaula) as totalRegistro FROM categoria_aula WHERE ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function inserirProfissionalCategoriaAula($dados, $idprofissional) {
        $sql = " delete from profissional_categoria_aula where id_profissional = $idprofissional; ";
        foreach ($dados as $value) {
            if ($value["porcentagem"] != null && $value["porcentagem"] != "") {
                $str_explode = explode(',', $value["porcentagem"]);
                $value["porcentagem"] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];
                
                $sql .= " insert into profissional_categoria_aula (id_profissional, id_categoria_aula, porcentagem, created, modified)
                    values ({$idprofissional}, {$value["idcategoriaaula"]}, {$value["porcentagem"]}, NOW(), NOW());  ";
            }
        }
        $this->query($sql);
    }
    
    public function retornarProfissionalCategoriaAula($idprofissional){
        $dados = $this->query("SELECT * FROM profissional_categoria_aula pc
                where pc.id_profissional = {$idprofissional} ");
        return $dados;
    }

}
