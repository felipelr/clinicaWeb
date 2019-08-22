<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP User
 * @author Felipe
 */
class User extends AppModel {

    public $useTable = "usuario";
    public $primaryKey = "idusuario";

    public function beforeSave($options = array()) {
        // Está salvando um usuário com senha
        if (isset($this->data[$this->alias]['senha'])) {
            // A senha não está vazia
            if (!empty($this->data[$this->alias]['senha'])) {
                // Gera o hash da senha
                $senha = $this->data[$this->alias]['senha'];
                $this->data[$this->alias]['senha'] = Security::hash($senha);
            } else {
                unset($this->data[$this->alias]['senha']);
            }
        }

        return parent::beforeSave($options);
    }

    public function isValidPassword($iduser, $senha) {
        $senha = Security::hash($senha);
        $this->query("SELECT idusuario FROM usuario WHERE senha = '$senha' and id = $iduser limit 1;");
        return $this->getNumRows() === 1;
    }

    public function validarEmail($email, $userid = null) {
        if (is_null($userid)) {
            $this->query("SELECT idusuario FROM usuario WHERE email = '$email' limit 1;");
            return $this->getNumRows() === 0;
        } else {
            $this->query("SELECT idusuario FROM usuario WHERE email = '$email' and idusuario <> $userid limit 1;");
            return $this->getNumRows() === 0;
        }
    }

    public function getDados($uid) {
        if (empty($uid) && !is_integer($uid) && is_null($uid)) {
            return null;
        }
        $db = $this->getDataSource();
        $dados = $db->fetchRow("SELECT * FROM usuario WHERE idusuario = $uid limit 1;");
        if ($dados) {
            return $dados["usuario"];
        } else {
            return null;
        }
    }

    public function validaLogin($email, $senha) {
        if (empty($email) && is_null($email)) {
            return null;
        }
        if (empty($senha) && is_null($senha)) {
            return null;
        }
        $db = $this->getDataSource();
        $dados = $db->fetchRow("SELECT * FROM usuario WHERE email = '$email' and senha = '$senha' limit 1;");
        return $dados["usuario"];
    }

    public function excluir($id) {
        return $this->query("UPDATE usuario SET ativo = 0 WHERE idusuario = $id");
    }

    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM usuario WHERE idusuario = $id LIMIT 1");
        return $dados[0]["usuario"];
    }
    public function retornarPorEmail($email) {
        $dados = $this->query("SELECT * FROM usuario WHERE email = '$email' LIMIT 1");
        return $dados[0]["usuario"];
    }
    public function retornarTodos() {
        $dados = $this->query("SELECT * FROM usuario u where u.ativo = 1 AND u.master = 0 order by u.nome");
        return $dados;
    }

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE u.ativo = 1 and u.master = 0 " : " WHERE u.ativo = 1 and u.master = 0 and (CONCAT(u.nome, ' ', u.sobrenome) LIKE '%{$search}%' OR u.email LIKE '%{$search}%') ";
        return $this->query("SELECT u.idusuario, u.nome, u.sobrenome, u.email FROM usuario u {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE u.ativo = 1 and u.master = 0 " : " WHERE u.ativo = 1 and u.master = 0 and (CONCAT(u.nome, ' ', u.sobrenome) LIKE '%{$search}%' OR u.email LIKE '%{$search}%') ";
        $dados = $this->query("SELECT count(u.idusuario) as totalRegistro FROM usuario u {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idusuario) as totalRegistro FROM usuario u WHERE u.ativo = 1 and u.master = 0");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    function gerarSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
        // Caracteres de cada tipo
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';

        // Variáveis internas
        $retorno = '';
        $caracteres = '';

        // Agrupamos todos os caracteres que poderão ser utilizados
        $caracteres .= $lmin;
        if ($maiusculas)
            $caracteres .= $lmai;
        if ($numeros)
            $caracteres .= $num;
        if ($simbolos)
            $caracteres .= $simb;

        // Calculamos o total de caracteres possíveis
        $len = strlen($caracteres);

        for ($n = 1; $n <= $tamanho; $n++) {
        // Criamos um número aleatório de 1 até $len para pegar um dos caracteres
            $rand = mt_rand(1, $len);
        // Concatenamos um dos caracteres na variável $retorno
            $retorno .= $caracteres[$rand - 1];
        }

        return $retorno;
    }

}
