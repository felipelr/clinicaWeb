<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP CaixaAberturaFechamento
 * @author felip
 */
class CaixaAberturaFechamento extends AppModel {

    public $useTable = "caixa_abertura_fechamento";
    public $primaryKey = "idcaixaaberturafechamento";

    public function verificarCaixaAberto($idCaixa) {
        $dados = $this->query("SELECT * FROM caixa_abertura_fechamento WHERE aberto = 1 AND id_caixa_abertura = $idCaixa LIMIT 1");
        $count = $dados != null ? count($dados) : 0;

        if ($count > 0) {
            return $dados[0]["caixa_abertura_fechamento"]['aberto'] == 1;
        }
        return false;
    }

    public function abrirCaixa($idCaixa, $idUsuario, $saldoInicial) {
        $this->save(array(
            'id_caixa_abertura' => $idCaixa,
            'saldo_inicial' => $saldoInicial,
            'id_usuario_abertura' => $idUsuario,
            'data_abertura' => date('Y-m-d H:i:s'),
            'aberto' => 1
        ));
    }
    
    public function fecharCaixa($idCaixaAbertFec, $idCaixa, $idUsuario) {
        $this->id = $idCaixaAbertFec;
        $this->save(array(
            'id_caixa_fechamento' => $idCaixa,
            'id_usuario_fechamento' => $idUsuario,
            'data_fechamento' => date('Y-m-d H:i:s'),
            'aberto' => 0
        ));
    }

    public function retornarVisualizacaoGerenciamento($tipoData, $dataInicio, $dataFim){
        $dataFiltro = $tipoData == 1 ? 'caf.data_abertura' : 'caf.data_fechamento';
        
        $dados = $this->query("SELECT c.idcaixa, c.descricao, caf.data_abertura, caf.data_fechamento, caf.saldo_inicial, caf.aberto,
            SUM(case when cm.fechamento = 0 then IFNULL(cm.valor, 0) ELSE 0 END) AS valorMovimento, 
            SUM(case when cm.fechamento = 1 then IFNULL(cm.valor, 0) ELSE 0 END) AS valorDeclarado
            FROM caixa_abertura_fechamento AS caf 
            INNER JOIN caixa AS c ON (c.idcaixa = caf.id_caixa_abertura)
            LEFT JOIN caixa_movimento AS cm ON (cm.id_caixa = caf.id_caixa_abertura)
            WHERE CAST($dataFiltro AS DATE) BETWEEN CAST('$dataInicio' AS DATE) AND CAST('$dataFim' AS DATE)
            GROUP BY c.idcaixa, c.descricao, caf.data_abertura, caf.data_fechamento, caf.saldo_inicial, caf.aberto
            ORDER BY c.descricao");
        return $dados;
    }
}
