<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP EventoDisponibilidade
 * @author felip
 */
class EventoDisponibilidade extends AppModel {

    public $useTable = "evento_disponibilidade";
    public $primaryKey = "ideventodisponibilidade";

    public function retornarPorIdPlanoSessao($idplanossessao, $idpaciente) {
        if (isset($idplanossessao)) {
            $dados = $this->query("select * from evento_disponibilidade where id_plano_sessao = $idplanossessao and id_paciente = $idpaciente limit 1");
            return $dados[0]["evento_disponibilidade"];
        } else {
            $dados = $this->query("select * from evento_disponibilidade where id_plano_sessao is null and id_paciente = $idpaciente limit 1");
            return $dados[0]["evento_disponibilidade"];
        }
    }
    
    public function retornarPorIdRecebimento($idrecebimento, $idpaciente) {
        $dados = $this->query("select * from evento_disponibilidade where id_recebimento = $idrecebimento and id_paciente = $idpaciente limit 1");
        return $dados[0]["evento_disponibilidade"];
    }

    public function decremetarTotal($idpaciente, $idrecebimento) {
        $this->query("update evento_disponibilidade set total = total - 1 where id_paciente = $idpaciente and id_recebimento = $idrecebimento;");
    }

    public function incremetarTotal($idpaciente, $idrecebimento) {
        $this->query("update evento_disponibilidade set total = total + 1 where id_paciente = $idpaciente and id_recebimento = $idrecebimento;");
    }
    
    public function updateTotal($ideventodisponibilidade, $total){
        $this->query("update evento_disponibilidade set total = $total where ideventodisponibilidade = $ideventodisponibilidade;");
    }

}
