<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP CaixaLoja
 * @author BersaN & StarK
 */
class CaixaLoja extends AppModel {

    public $useTable = "caixa_loja";
    public $primaryKey = "idcaixaloja";

    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM caixa_loja c where c.ativo = 1 order by c.nome_caixa");
        return $dados;
    }
    
    public function listarPorId($id) {
        $dados = $this->query("SELECT * FROM caixa_loja WHERE idcaixaloja = $id LIMIT 1");
        return $dados[0]["caixa_loja"];
    }

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? "" : " WHERE c.nome_caixa LIKE '%{$search}%'";
        return $this->query("SELECT c.idcaixaloja, c.nome_caixa,u.nome, u.sobrenome, cli.fantasia FROM caixa_loja c inner join usuario u on(u.idusuario = c.id_usuario) inner join clinica cli on(cli.idclinica = c.id_clinica) {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? "" : " WHERE c.nome_caixa LIKE '%{$search}%'";
        $dados = $this->query("SELECT count(c.idcaixaloja) as totalRegistro FROM caixa_loja c {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idcaixaloja) as totalRegistro FROM caixa_loja");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function excluir($id) {
        return $this->query("UPDATE caixa_loja SET Ativo = 0 WHERE idcaixaloja = $id");
    }

}
