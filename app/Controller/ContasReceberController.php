<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Paciente', 'Model');
App::uses('PlanoContas', 'Model');
App::uses('Clinica', 'Model');
App::uses('TipoFinanceiro', 'Model');
App::uses('CaixaLoja', 'Model');
App::uses('Financeiro', 'Model');
App::uses('Acesso', 'Model');

/**
 * CakePHP ContasReceberController
 * @author Felipe
 */
class ContasReceberController extends AuthController {

    public function index() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_CONTAS_RECEBER);
        
        $tipoFinanceiro = new TipoFinanceiro();
        $paciente = new Paciente();
        $plano = new PlanoContas();
        $clinica = new Clinica();
        $caixaLoja = new CaixaLoja();
        
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;

        $financeiros_ = $tipoFinanceiro->retornarTodos();
        $financeirosPagaveis_ = $tipoFinanceiro->retornarTodosPagaveis();
        $pacientes_ = $paciente->retornarTodos();
        $planos_ = $plano->retornarTodosReceitas();
        $clinicas_ = $clinica->retornarTodos(" and c.idclinica = {$idClinica} ");
        $caixas_ = $caixaLoja->retornarTodos();

        $this->set('TipoFinanceiros', $financeiros_);
        $this->set('TipoFinanceirosPagaveis', $financeirosPagaveis_);
        $this->set('Pacientes', $pacientes_);
        $this->set("Planos", $planos_);
        $this->set("Clinicas", $clinicas_);
        $this->set('Caixas', $caixas_);

        $dataCadastroDE = date('d/m/Y');
        $dataCadastroATE = date('d/m/Y');
        $dataVencimentoDE = date('d/m/Y');
        $dataVencimentoATE = date('d/m/Y');
        $tipoFinanceiro_ = 0;
        $planoContas_ = 0;
        $paciente_ = 0;
        $cheque_ = 0;
        $clinica_ = 0;
        $radioDataCadastro_ = 1;
        $radioDataVencimento_ = 1;
        $nome_recebimento = "";

        $this->set("dataCadastroDE", $dataCadastroDE);
        $this->set("dataCadastroATE", $dataCadastroATE);
        $this->set("dataVencimentoDE", $dataVencimentoDE);
        $this->set("dataVencimentoATE", $dataVencimentoATE);
        $this->set("tipoFinanceiro_", $tipoFinanceiro_);
        $this->set("planoContas_", $planoContas_);
        $this->set("paciente_", $paciente_);
        $this->set("cheque_", $cheque_);
        $this->set("clinica_", $clinica_);
        $this->set("radioDataCadastro_", $radioDataCadastro_);
        $this->set("radioDataVencimento_", $radioDataVencimento_);
        $this->set("nome_recebimento", $nome_recebimento);
    }
    
    public function receber_parcela() {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;
            //executar pagamento
            $financeiro = new Financeiro();
            $financeiro->receberParcela($data['idrecebimento'], $this->_idDaSessao, $data['caixa_loja'], $data['idfinanceiro'], $data['tipo_financeiro'], "Recebimento de parcela");
        }
    }

    public function ajax_parcela_detalhes() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idfinanceiro = (isset($this->request->query["idfinanceiro"])) ? $this->request->query["idfinanceiro"] : null;
        if (isset($idfinanceiro)) {
            $financeiro = new Financeiro();
            $dados = $financeiro->retornarDetalhesRecebimento($idfinanceiro);
            $financeiro_ = $dados[0];
            $financeiro_['financeiro']['valor'] = number_format($financeiro_['financeiro']['valor'], 2, ",", ".");
            $financeiro_['financeiro']['data_vencimento'] = date('d/m/Y', strtotime($financeiro_['financeiro']['data_vencimento']));
            $financeiro_['financeiro']['data_pagamento'] = date('d/m/Y', strtotime($financeiro_['financeiro']['data_pagamento']));
            $financeiro_['usuario']['nome'] = isset($financeiro_['usuario']['nome']) ? $financeiro_['usuario']['nome'] : "";
            $financeiro_['usuario']['sobrenome'] = isset($financeiro_['usuario']['sobrenome']) ? $financeiro_['usuario']['sobrenome'] : "";
            $this->response->body(json_encode($financeiro_));
        }
    }
    
    public function ajax_relatorio() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $financeiro = new Financeiro();
        $dataCadastroDE = isset($this->request->data["data_cadastro_de"]) ? $this->request->data["data_cadastro_de"] : date('d/m/Y');
        $dataCadastroATE = isset($this->request->data["data_cadastro_ate"]) ? $this->request->data["data_cadastro_ate"] : date('d/m/Y');
        $dataVencimentoDE = isset($this->request->data["data_vencimento_de"]) ? $this->request->data["data_vencimento_de"] : date('d/m/Y');
        $dataVencimentoATE = isset($this->request->data["data_vencimento_ate"]) ? $this->request->data["data_vencimento_ate"] : date('d/m/Y');
        $tipoFinanceiro_ = isset($this->request->data["tipo_financeiro"]) ? $this->request->data["tipo_financeiro"] : 0;
        $planoContas_ = isset($this->request->data["plano_contas"]) ? $this->request->data["plano_contas"] : 0;
        $paciente_ = isset($this->request->data["paciente"]) ? $this->request->data["paciente"] : 0;
        $cheque_ = isset($this->request->data["cheque"]) ? $this->request->data["cheque"] : 1;
        $clinica_ = isset($this->request->data["clinica"]) ? $this->request->data["clinica"] : 0;
        $nome_recebimento_ = isset($this->request->data["nome_recebimento"]) ? $this->request->data["nome_recebimento"] : "";

        if ($dataCadastroDE != null && $dataCadastroDE != "") {
            $dataCadastroDE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataCadastroDE);
        }
        if ($dataCadastroATE != null && $dataCadastroATE != "") {
            $dataCadastroATE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataCadastroATE);
        }
        if ($dataVencimentoDE != null && $dataVencimentoDE != "") {
            $dataVencimentoDE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataVencimentoDE);
        }
        if ($dataVencimentoATE != null && $dataVencimentoATE != "") {
            $dataVencimentoATE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataVencimentoATE);
        }

        $arrayContas = $financeiro->relatorioContasReceber($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $tipoFinanceiro_, $planoContas_, $paciente_, $cheque_, $clinica_, $nome_recebimento_);
        $contasSize = count($arrayContas);
        $dados = "";
        if ($contasSize > 0) {
            for ($i = 0; $i < $contasSize; $i++) {
                $dados .= "
                    <tr>
                        <td>{$arrayContas[$i]["recebimento"]["descricao"]}</td>
                        <td>{$arrayContas[$i]["plano_contas"]["descricao"]}</td>
                        <td>{$arrayContas[$i]["centro_custos"]["descricao"]}</td>
                        <td>" . $arrayContas[$i]["paciente"]["nome"] . " " . $arrayContas[$i]["paciente"]["sobrenome"] . "</td>
                        <td>{$arrayContas[$i]["clinica"]["fantasia"]}</td>
                        <td class='text-center'>" . date('d/m/Y', strtotime($arrayContas[$i][0]["min_data_vencimento"])) . "</td>
                        <td class='text-center'>{$arrayContas[$i]["financeiro"]["total_parcela"]}</td>
                        <td class='text-center'>
                            <button class='btn btn-warning btn-show-details' onclick='mostrarDetalhes({$arrayContas[$i]["recebimento"]["idrecebimento"]})' title='Visualizar Detalhes'><i class='fa fa-sliders'></i></button>
                            <a href='" . Router::url(array("controller" => "recebimento", "action" => "gestao", $arrayContas[$i]["recebimento"]['idrecebimento'])) . "' class='btn btn-success' title='Gerenciar Recebimento'><i class='fa fa-edit'></i></a>
                        </td>
                    </tr> 
                    <tr id='recebimento-{$arrayContas[$i]["recebimento"]["idrecebimento"]}' style='display: none;'>
                        <td colspan='8' style='padding-left: 30px;'> 
                            <b>Detalhes</b>
                            <table class='table table-responsive table-bordered table-striped'>
                                <thead>
                                    <tr>
                                        <th class='text-center'>Parcela</th>
                                        <th class='text-center'>Valor</th>
                                        <th class='text-center'>Vencimento</th>
                                        <th class='text-center'>Vencida</th>                                            
                                        <th class='text-center'>Recebido</th>
                                        <th class='text-center'>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>";

                $idrecebimento = $arrayContas[$i]["recebimento"]["idrecebimento"];
                $subtotal = 0;
                for ($i = $i; $i < $contasSize; $i++) {
                    if ($idrecebimento == $arrayContas[$i]["recebimento"]["idrecebimento"]) {
                        $subtotal = $subtotal + (double) $arrayContas[$i]["financeiro"]["valor"];
                        $date = date('Y-m-d 23:59:59', strtotime($arrayContas[$i]["financeiro"]["data_vencimento"]));
                        $vencida = 0;
                        if (strtotime($date) < strtotime('now') && $arrayContas[$i]["financeiro"]["pago"] == 0) {
                            $vencida = 1;
                        }
                        $codigocor = $vencida == 0 ? "#dff0d8" : "#f2dede";
                        $textvencida = $vencida == 0 ? "NÃO" : "SIM";
                        $textpago = $arrayContas[$i]["financeiro"]["pago"] == 0 ? "NÃO" : "SIM";
                        $disable = $arrayContas[$i]["financeiro"]["pago"] == 1 ? "disabled" : "";
                        $dados .= "
                            <tr style='background-color: $codigocor'>
                                <td class='text-center'>{$arrayContas[$i]["financeiro"]["parcela"]}</td>
                                <td class='text-center'>" . 'R$ ' . number_format($arrayContas[$i]["financeiro"]["valor"], 2, ",", ".") . "</td>
                                <td class='text-center'>" . date('d/m/Y', strtotime($arrayContas[$i]["financeiro"]["data_vencimento"])) . "</td>
                                <td class='text-center'>$textvencida</td>
                                <td class='text-center'>$textpago</td>
                                <td class='text-center'>
                                    <button class='btn btn-success $disable' onclick='showModalReceber({$arrayContas[$i]["financeiro"]["idfinanceiro"]})'>Receber</button>
                                </td>
                            </tr>";
                    } else {
                        break;
                    }
                }
                $i--;

                $dados .= "
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class='text-center'>Parcela</th>
                            <th class='text-center'>Valor</th>
                            <th class='text-center'>Vencimento</th>
                            <th class='text-center'>Vencida</th>                                           
                            <th class='text-center'>Pago</th>
                            <th class='text-center'>Ações</th>
                        </tr>
                    </tfoot>
                </table>
                <b>Subtotal: " . 'R$ ' . number_format($subtotal, 2, ",", ".") . "</b><br/>
            </td>
        </tr>
        <tr></tr>";
            }
        } else {
            $dados .= "
                <tr>
                    <td colspan='8' class='text-center'>Não há registros</td>                    
                </tr>";
        }

        $this->response->body($dados);
    }

}
