<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Favorecido', 'Model');
App::uses('PlanoContas', 'Model');
App::uses('Clinica', 'Model');
App::uses('TipoFinanceiro', 'Model');
App::uses('CaixaLoja', 'Model');
App::uses('Financeiro', 'Model');
App::uses('Acesso', 'Model');

/**
 * CakePHP ContasPagarController
 * @author Felipe
 */
class ContasPagarController extends AuthController {

    public function index() {
        $tipoFinanceiro = new TipoFinanceiro();
        $favorecido = new Favorecido();
        $plano = new PlanoContas();
        $clinica = new Clinica();
        $caixaLoja = new CaixaLoja();
        
        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;

        $financeiros_ = $tipoFinanceiro->retornarTodos();
        $financeirosPagaveis_ = $tipoFinanceiro->retornarTodosPagaveis();
        $favorecidos_ = $favorecido->retornarTodos();
        $planos_ = $plano->retornarTodosDespesas();
        $clinicas_ = $clinica->retornarTodos(" and c.idclinica = {$idClinica} ");
        $caixas_ = $caixaLoja->retornarTodos();
        

        $this->set('TipoFinanceiros', $financeiros_);
        $this->set('TipoFinanceirosPagaveis', $financeirosPagaveis_);
        $this->set('Favorecidos', $favorecidos_);
        $this->set("Planos", $planos_);
        $this->set("Clinicas", $clinicas_);
        $this->set('Caixas', $caixas_);

        $dataCadastroDE = date('d/m/Y');
        $dataCadastroATE = date('d/m/Y');
        $dataVencimentoDE = date('d/m/Y');
        $dataVencimentoATE = date('d/m/Y');
        $tipoFinanceiro_ = 0;
        $planoContas_ = 0;
        $favorecido_ = 0;
        $contas_pagas_ = 0;
        $clinica_ = 0;
        $radioDataCadastro_ = 1;
        $radioDataVencimento_ = 1;
        $nome_despesa_ = "";

        $this->set("dataCadastroDE", $dataCadastroDE);
        $this->set("dataCadastroATE", $dataCadastroATE);
        $this->set("dataVencimentoDE", $dataVencimentoDE);
        $this->set("dataVencimentoATE", $dataVencimentoATE);
        $this->set("tipoFinanceiro_", $tipoFinanceiro_);
        $this->set("planoContas_", $planoContas_);
        $this->set("favorecido_", $favorecido_);
        $this->set("contas_pagas_", $contas_pagas_);
        $this->set("clinica_", $clinica_);
        $this->set("radioDataCadastro_", $radioDataCadastro_);
        $this->set("radioDataVencimento_", $radioDataVencimento_);
        $this->set("nome_despesa_", $nome_despesa_);
    }

    public function pagar_parcela() {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;
            //executar pagamento
            $financeiro = new Financeiro();
            $financeiro->PagarParcela($data['iddespesa'], $this->_idDaSessao, $data['caixa_loja'], $data['idfinanceiro'], $data['tipo_financeiro'], "Pagamento de parcela");
        }
    }

    public function ajax_parcela_detalhes() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idfinanceiro = (isset($this->request->query["idfinanceiro"])) ? $this->request->query["idfinanceiro"] : null;
        if (isset($idfinanceiro)) {
            $financeiro = new Financeiro();
            $dados = $financeiro->retornarDetalhes($idfinanceiro);
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
        $favorecido_ = isset($this->request->data["favorecido"]) ? $this->request->data["favorecido"] : 0;
        $contas_pagas_ = isset($this->request->data["contas_pagas"]) ? $this->request->data["contas_pagas"] : 1;
        $clinica_ = isset($this->request->data["clinica"]) ? $this->request->data["clinica"] : 0;
        $nome_despesa_ = isset($this->request->data["nome_despesa"]) ? $this->request->data["nome_despesa"] : "";

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

        $arrayContas = $financeiro->relatorioContasPagar($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $tipoFinanceiro_, $planoContas_, $favorecido_, $contas_pagas_, $clinica_, $nome_despesa_);
        $contasSize = count($arrayContas);
        $dados = "";
        if ($contasSize > 0) {
            for ($i = 0; $i < $contasSize; $i++) {
                $dados .= "
                    <tr>
                        <td>{$arrayContas[$i]["despesa"]["descricao"]}</td>
                        <td>{$arrayContas[$i]["plano_contas"]["descricao"]}</td>
                        <td>{$arrayContas[$i]["centro_custos"]["descricao"]}</td>
                        <td>{$arrayContas[$i]["favorecido"]["nome"]}</td>
                        <td>{$arrayContas[$i]["clinica"]["fantasia"]}</td>
                        <td class='text-center'>" . date('d/m/Y', strtotime($arrayContas[$i][0]["min_data_vencimento"])) . "</td>
                        <td class='text-center'>{$arrayContas[$i]["financeiro"]["total_parcela"]}</td>
                        <td class='text-center'>
                            <button class='btn btn-warning btn-show-details' onclick='mostrarDetalhes({$arrayContas[$i]["despesa"]["iddespesa"]})' title='Visualizar Detalhes'><i class='fa fa-sliders'></i></button>
                            <a href='" . Router::url(array("controller" => "despesa", "action" => "gestao", $arrayContas[$i]["despesa"]['iddespesa'])) . "' class='btn btn-success' title='Gerenciar Despesa'><i class='fa fa-edit'></i></a>
                        </td>
                    </tr> 
                    <tr id='despesa-{$arrayContas[$i]["despesa"]["iddespesa"]}' style='display: none;'>
                        <td colspan='8' style='padding-left: 30px;'> 
                            <b>Detalhes</b>
                            <table class='table table-responsive table-bordered table-striped'>
                                <thead>
                                    <tr>
                                        <th class='text-center'>Parcela</th>
                                        <th class='text-center'>Valor</th>
                                        <th class='text-center'>Vencimento</th>
                                        <th class='text-center'>Vencida</th>                                            
                                        <th class='text-center'>Pago</th>
                                        <th class='text-center'>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>";

                $iddespesa = $arrayContas[$i]["despesa"]["iddespesa"];
                $subtotal = 0;
                for ($i = $i; $i < $contasSize; $i++) {
                    if ($iddespesa == $arrayContas[$i]["despesa"]["iddespesa"]) {
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
                                    <button class='btn btn-success $disable' onclick='showModalPagar({$arrayContas[$i]["financeiro"]["idfinanceiro"]})'>Pagar</button>
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
