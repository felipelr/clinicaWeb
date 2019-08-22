<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
App::uses('AuthController', 'Controller');
App::uses('Evento', 'Model');
App::uses('Endereco', 'Model');
App::uses('Financeiro', 'Model');
App::uses('Paciente', 'Model');
App::uses('Recebimento', 'Model');

/**
 * CakePHP IndexController
 * @author Felipe
 */
class IndexController extends AuthController {

    public function index() {
        $evento = new Evento();
        $paciente = new Paciente();
        $recebimento = new Recebimento();

        $quantidadeAniversariantes = $paciente->quantidadeAniversariantesMes();
        $consultas_hoje = $evento->retornarQuantidadeHoje();
        $consultas_amanhan = $evento->retornarQuantidadeAmanha();
        //$consultas_esta_semana = $evento->retornarQuantidadeEstaSemana();
        $contrato_renovacao = $recebimento->totalRegistroRenovacaoContrato();

        $this->set('quantidadeAniversariantes', $quantidadeAniversariantes);
        $this->set('consultasHoje', $consultas_hoje);
        $this->set('consultasAmanhan', $consultas_amanhan);
        //$this->set('consultasEstaSemana', $consultas_esta_semana);
        $this->set('contratoRenovacao', $contrato_renovacao);
    }

    public function mapa() {
        $this->layout = "default_mapa";
        $endereco = new Endereco();
        $listaLocalizacoes = $endereco->retornarGeoLocalizacoes();
        $this->set('geoLocalizacoes', $listaLocalizacoes);
    }

    public function pagar_parcela() {
        $iddespesa = isset($this->request->query['iddespesa']) ? $this->request->query['iddespesa'] : null;
        $idfinanceiro = isset($this->request->query['idfinanceiro']) ? $this->request->query['idfinanceiro'] : null;
        //pagar parcela
        return $this->redirect(array("controller" => "index", "action" => "index"));
    }

    public function ajax_consultas_hoje() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $eventosHoje = $evento->retornarHoje();
        $dados = array();
        $dadosEventos = array();
        if (count($eventosHoje) > 0) {
            foreach ($eventosHoje as $value) {
                $value['e']["data_inicio"] = date('d/m/Y H:i', strtotime($value['e']["data_inicio"]));
                $value['e']["data_fim"] = date('d/m/Y H:i', strtotime($value['e']["data_fim"]));
                $dadosEventos[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosEventos;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_consultas_amanha() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $eventosAmanha = $evento->retornarAmanha();
        $dados = array();
        $dadosEventos = array();
        if (count($eventosAmanha) > 0) {
            foreach ($eventosAmanha as $value) {
                $value['e']["data_inicio"] = date('d/m/Y H:i', strtotime($value['e']["data_inicio"]));
                $value['e']["data_fim"] = date('d/m/Y H:i', strtotime($value['e']["data_fim"]));
                $dadosEventos[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosEventos;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_consultas_essa_semana() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $eventosEssaSemana = $evento->retornarEssaSemana();
        $dados = array();
        $dadosEventos = array();
        if (count($eventosEssaSemana) > 0) {
            foreach ($eventosEssaSemana as $value) {
                $value['e']["data_inicio"] = date('d/m/Y H:i', strtotime($value['e']["data_inicio"]));
                $value['e']["data_fim"] = date('d/m/Y H:i', strtotime($value['e']["data_fim"]));
                $dadosEventos[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosEventos;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_financeiro_diario() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $financeiro = new Financeiro();
        $financeiros = $financeiro->relatorioDiario($idClinica);
        $dados = array();
        $dadosFinanceiro = array();
        $valorTotal = 0;
        if (count($financeiros) > 0) {
            foreach ($financeiros as $value) {
                $valorTotal += $value["financeiro"]["valor"];
                $date = date('Y-m-d 23:59:59', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["data_vencimento"] = date('d/m/Y', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["valor"] = 'R$ ' . number_format($value["financeiro"]["valor"], 2, ",", ".");
                $value['financeiro']["valor_total"] = 'R$ ' . number_format($valorTotal, 2, ",", ".");
                $value['financeiro']['vencida'] = strtotime($date) < strtotime('now') && $value["financeiro"]["pago"] == 0 ? 1 : 0;
                $dadosFinanceiro[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosFinanceiro;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_financeiro_semanal() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $financeiro = new Financeiro();
        $financeiros = $financeiro->relatorioSemanal($idClinica);
        $dados = array();
        $dadosFinanceiro = array();
        $valorTotal = 0;
        if (count($financeiros) > 0) {
            foreach ($financeiros as $value) {
                $valorTotal += $value["financeiro"]["valor"];
                $date = date('Y-m-d 23:59:59', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["data_vencimento"] = date('d/m/Y', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["valor"] = 'R$ ' . number_format($value["financeiro"]["valor"], 2, ",", ".");
                $value['financeiro']["valor_total"] = 'R$ ' . number_format($valorTotal, 2, ",", ".");
                $value['financeiro']['vencida'] = strtotime($date) < strtotime('now') && $value["financeiro"]["pago"] == 0 ? 1 : 0;
                $dadosFinanceiro[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosFinanceiro;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_financeiro_mensal() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $financeiro = new Financeiro();
        $financeiros = $financeiro->relatorioMensal($idClinica);
        $dados = array();
        $dadosFinanceiro = array();
        $valorTotal = 0;
        if (count($financeiros) > 0) {
            foreach ($financeiros as $value) {
                $valorTotal += $value["financeiro"]["valor"];
                $date = date('Y-m-d 23:59:59', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["data_vencimento"] = date('d/m/Y', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["valor"] = 'R$ ' . number_format($value["financeiro"]["valor"], 2, ",", ".");
                $value['financeiro']["valor_total"] = 'R$ ' . number_format($valorTotal, 2, ",", ".");
                $value['financeiro']['vencida'] = strtotime($date) < strtotime('now') && $value["financeiro"]["pago"] == 0 ? 1 : 0;
                $dadosFinanceiro[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosFinanceiro;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_financeiro_diario_recebimento() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $financeiro = new Financeiro();
        $financeiros = $financeiro->relatorioDiarioRecebimento($idClinica);
        $dados = array();
        $dadosFinanceiro = array();
        $valorTotal = 0;
        if (count($financeiros) > 0) {
            foreach ($financeiros as $value) {
                $valorTotal += $value["financeiro"]["valor"];
                $date = date('Y-m-d 23:59:59', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["data_vencimento"] = date('d/m/Y', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["valor"] = 'R$ ' . number_format($value["financeiro"]["valor"], 2, ",", ".");
                $value['financeiro']["valor_total"] = 'R$ ' . number_format($valorTotal, 2, ",", ".");
                $value['financeiro']['vencida'] = strtotime($date) < strtotime('now') && $value["financeiro"]["pago"] == 0 ? 1 : 0;
                $dadosFinanceiro[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosFinanceiro;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_financeiro_semanal_recebimento() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $financeiro = new Financeiro();
        $financeiros = $financeiro->relatorioSemanalRecebimento($idClinica);
        $dados = array();
        $dadosFinanceiro = array();
        $valorTotal = 0;
        if (count($financeiros) > 0) {
            foreach ($financeiros as $value) {
                $valorTotal += $value["financeiro"]["valor"];
                $date = date('Y-m-d 23:59:59', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["data_vencimento"] = date('d/m/Y', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["valor"] = 'R$ ' . number_format($value["financeiro"]["valor"], 2, ",", ".");
                $value['financeiro']["valor_total"] = 'R$ ' . number_format($valorTotal, 2, ",", ".");
                $value['financeiro']['vencida'] = strtotime($date) < strtotime('now') && $value["financeiro"]["pago"] == 0 ? 1 : 0;
                $dadosFinanceiro[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosFinanceiro;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_financeiro_mensal_recebimento() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $financeiro = new Financeiro();
        $financeiros = $financeiro->relatorioMensalRecebimento($idClinica);
        $dados = array();
        $dadosFinanceiro = array();
        $valorTotal = 0;
        if (count($financeiros) > 0) {
            foreach ($financeiros as $value) {
                $valorTotal += $value["financeiro"]["valor"];
                $date = date('Y-m-d 23:59:59', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["data_vencimento"] = date('d/m/Y', strtotime($value['financeiro']["data_vencimento"]));
                $value['financeiro']["valor"] = 'R$ ' . number_format($value["financeiro"]["valor"], 2, ",", ".");
                $value['financeiro']["valor_total"] = 'R$ ' . number_format($valorTotal, 2, ",", ".");
                $value['financeiro']['vencida'] = strtotime($date) < strtotime('now') && $value["financeiro"]["pago"] == 0 ? 1 : 0;
                $dadosFinanceiro[] = $value;
            }
            $dados['result'] = true;
            $dados['dados'] = $dadosFinanceiro;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_aniversariantes() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $paciente = new Paciente();
        $pacientes = $paciente->retornarAniversariantesDoMes();
        $dados = array();
        $dadosPaciente = array();
        if (count($pacientes) > 0) {
            foreach ($pacientes as $value) {
                $value['paciente']["data_nascimento"] = date('d/m', strtotime($value['paciente']["data_nascimento"]));
                $dadosPaciente[] = $value;
            }
            $dados['pacientes'] = $dadosPaciente;
            $dados['result'] = true;
        } else {
            $dados['result'] = false;
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_grafico_eventos() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $dados = array();

        $dadosCompareceu = $evento->retornarQuantidadeEventosCompareceu();
        $dadosNaoCompareceu = $evento->retornarQuantidadeEventosNaoCompareceu();
        $dadosAdiado = $evento->retornarQuantidadeEventosAdiado();

        $dados['compareceu'] = $dadosCompareceu;
        $dados['naocompareceu'] = $dadosNaoCompareceu;
        $dados['adiado'] = $dadosAdiado;
        $this->response->body(json_encode($dados));
    }

    public function ajax_grafico_financeiro() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $financeiro = new Financeiro();
        $dados = array();
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;

        $dadosRecebimentos = $financeiro->retornarValoresRecebimentosEfetivadosAnual($idClinica);
        $dadosDespesas = $financeiro->retornarValoresDespesasEfetivadasAnual($idClinica);

        $dados['recebimentos'] = $dadosRecebimentos;
        $dados['despesas'] = $dadosDespesas;

        $this->response->body(json_encode($dados));
    }

    public function ajax_grafico_contratos_efetivados() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $financeiro = new Financeiro();
        $dados = array();        
        
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        
        $dadosContratosEfetivados = $financeiro->retornarValoresContratosEfetivadosAnual($idClinica);
        $dadosContratosCancelados = $financeiro->retornarValoresContratosCanceladosAnual($idClinica);

        $dados['efetivados'] = $dadosContratosEfetivados;
        $dados['finalizados'] = $dadosContratosCancelados;

        $this->response->body(json_encode($dados));
    }

    public function ajax_timeline_consultas() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $dados = array();

        $start = isset($this->request->query['start']) ? $this->request->query['start'] : 0;

        $eventosTimeline = $evento->retornarTimelineEventos($start);

        if (isset($eventosTimeline)) {
            foreach ($eventosTimeline as $value) {
                $value['e']["data_timeline"] = date('d/m/Y', strtotime($value['e']["data_inicio"]));
                $value['e']["data_inicio"] = date('d/m/Y H:i', strtotime($value['e']["data_inicio"]));
                $value['e']["data_fim"] = date('d/m/Y H:i', strtotime($value['e']["data_fim"]));
                $dados[] = $value;
            }
        }

        $this->response->body(json_encode($dados));
    }
    
    public function ajax_grafico_plano_sessao() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $recebimento = new Recebimento();
        $dados = array();

        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        
        $dadosPlanos = $recebimento->quantidadeRecebimentosPorPlanoSessao($idClinica);

        $dados['planos'] = $dadosPlanos;

        $this->response->body(json_encode($dados));
    }

}
