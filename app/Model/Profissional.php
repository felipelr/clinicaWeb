<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Profissional
 * @author Felipe
 */
class Profissional extends AppModel {
    public $useTable = "profissional"; 
    public $primaryKey = "idprofissional";
    
    public function excluir($id) {
        return $this->query("UPDATE profissional SET ativo = 0 WHERE idprofissional = $id");
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM profissional WHERE idprofissional = $id LIMIT 1");
        return $dados[0]["profissional"];
    }
    
    public function retornarPorIdUsuario($id) {
        $dados = $this->query("SELECT * FROM profissional as p INNER JOIN usuario as u on(u.idusuario = p.id_usuario) WHERE u.idusuario = $id and u.ativo = 1 LIMIT 1");
        return $dados;
    }
    
    public function retornarTodos() {
        $dados = $this->query("SELECT * FROM profissional p WHERE ativo = 1 order by p.nome");
        return $dados;
    }

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE p.ativo = 1 " : " WHERE p.ativo = 1 and (CONCAT(p.nome, ' ', p.sobrenome) LIKE '%{$search}%' OR p.email LIKE '%{$search}%' OR p.cpf LIKE '%{$search}%') ";
        return $this->query("SELECT p.idprofissional, p.nome, p.sobrenome, p.email, p.cpf FROM profissional p {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE p.ativo = 1 " : " WHERE p.ativo = 1 and (CONCAT(p.nome, ' ', p.sobrenome) LIKE '%{$search}%' OR p.email LIKE '%{$search}%' OR p.cpf LIKE '%{$search}%') ";
        $dados = $this->query("SELECT count(p.idprofissional) as totalRegistro FROM profissional p {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idprofissional) as totalRegistro FROM profissional WHERE ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }
    
    public function salvarFoto($fotobytes, $fotonome, $fototipo, $id) {
        if (!isset($fotobytes) && !isset($fotonome) && !isset($fototipo)) {
            try {
                $dados = $this->query("UPDATE profissional SET foto_bytes=(NULL) , foto_nome=(NULL), foto_tipo=(NULL) WHERE idprofissional=$id");
                return $dados;
            } catch (Exception $exc) {
                return $exc;
            }
        } else {
            try {
                $dados = $this->query("UPDATE profissional SET foto_bytes='$fotobytes' , foto_nome='$fotonome', foto_tipo='$fototipo' WHERE idprofissional=$id");
                return $dados;
            } catch (Exception $exc) {
                return $exc;
            }
        }
    }
}
