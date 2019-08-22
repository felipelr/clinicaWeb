<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP CaixaUsuario
 * @author felip
 */
class CaixaUsuario extends AppModel {
    
    public $useTable = "caixa_usuario";
    public $primaryKey = "idcaixausuario";
    
    public function retornarTodosPorCaixa($idcaixa) {
        $dados = $this->query("SELECT c.*, cu.*, u.idusuario, u.nome, u.sobrenome 
            FROM usuario AS u 
            LEFT JOIN caixa_usuario AS cu ON (u.idusuario = cu.id_usuario AND cu.id_caixa = $idcaixa)
            LEFT JOIN caixa AS c ON (c.idcaixa = cu.id_caixa AND c.ativo = 1 AND c.tipo = 'DIARIO' AND c.idcaixa = $idcaixa)
            WHERE u.ativo = 1
            ORDER BY CONCAT(u.nome, ' ', u.sobrenome);");
        return $dados;
    }
}
