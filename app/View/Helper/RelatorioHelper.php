<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * CakePHP RelatorioPacienteHelper
 * @author Felipe
 */
App::uses('AppHelper', 'Helper');
App::uses('Paciente', 'Model');
App::uses('Financeiro', 'Model');
App::uses('Despesa', 'Model');

class RelatorioHelper extends AppHelper {

    public $helpers = array();
    public $paciente = null;
    public $financeiro = null;
    public $despesa = null;

    public function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);
        $this->paciente = new Paciente();   
        $this->financeiro = new Financeiro();
        $this->despesa = new Despesa();
    }

    public function pacientes($letraInicial, $nome, $dataDE, $dataATE) {       
        $arrayPaciente = array();
        if (isset($letraInicial) || isset($nome) || isset($dataDE) || isset($dataATE)) {
            $arrayPaciente = $this->paciente->relatorioPaciente($letraInicial, $nome, $dataDE, $dataATE);
        }
        return $arrayPaciente;
    }
    
    public function contasPagar($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $tipoFinanceiro_, $planoContas_, $favorecido_, $contas_pagas_, $clinica_, $nome_despesa_) {        
        $arrayContasPagar = array();
        if (isset($dataCadastroDE) && isset($dataCadastroATE) && isset($dataVencimentoDE) && isset($dataVencimentoATE) && isset($tipoFinanceiro_) && isset($planoContas_) && isset($favorecido_) && isset($contas_pagas_) && isset($clinica_)) {
            $arrayContasPagar = $this->financeiro->relatorioContasPagar($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $tipoFinanceiro_, $planoContas_, $favorecido_, $contas_pagas_, $clinica_, $nome_despesa_);
        }
        return $arrayContasPagar;
    }
    
    public function despesa(){        
        $arrayDespesa = $this->despesa->relatorioDespesa();
        return $arrayDespesa;
    }

}
