<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP FinanceiroTipo
 * @author BersaN & StarK
 */
class TipoFinanceiro extends AppModel {

    public $useTable = "tipo_financeiro";
    public $primaryKey = "idfinanceirotipo";
    
    public static $CHEQUE = "cheque";
    public static $CARTAO = "cartao";
    public static $BOLETO = "boleto";
    public static $DINHEIRO = "dinheiro";

    public function retornarTodos(){
        $dados = $this->query("SELECT * FROM tipo_financeiro t where t.ativo = 1 order by t.tipo");
        return $dados;
    }
    
    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM tipo_financeiro WHERE idfinanceirotipo = $id and ativo = 1 LIMIT 1");
        return $dados[0]["tipo_financeiro"];
    }
    
    public function retornarTodosPagaveis(){
        $dados = $this->query("SELECT * FROM tipo_financeiro t where t.ativo = 1 and t.pagamento = 1 order by t.tipo");
        return $dados;
    }
    
    public function listarPorId($idtipofinanceiro) {
        $dados = $this->query("SELECT * FROM tipo_financeiro WHERE idfinanceirotipo = $idtipofinanceiro LIMIT 1");
        return $dados[0]["tipo_financeiro"];
    }

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE t.ativo = 1 " : " WHERE t.ativo = 1 and c.Tipo LIKE '%{$search}%'";
        return $this->query("SELECT t.idfinanceirotipo, t.tipo, t.gera_caixa FROM tipo_financeiro t {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? " WHERE t.ativo = 1 " : " WHERE t.ativo = 1 and c.Tipo LIKE '%{$search}%'";
        $dados = $this->query("SELECT count(t.idfinanceirotipo) as totalRegistro FROM tipo_financeiro t {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idfinanceirotipo) as totalRegistro FROM tipo_financeiro WHERE ativo = 1");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function excluir($id) {
        return $this->query("UPDATE tipo_financeiro SET Ativo = 0 WHERE idfinanceirotipo = $id");
    }

}
