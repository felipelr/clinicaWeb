<?php



/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */



App::uses('AuthController', 'Controller');

App::uses('Paciente', 'Model');

App::uses('Favorecido', 'Model');

App::uses('PlanoContas', 'Model');

App::uses('Clinica', 'Model');

App::uses('TipoFinanceiro', 'Model');

App::uses('CaixaLoja', 'Model');

App::uses('Financeiro', 'Model');

App::uses('Recebimento', 'Model');

App::uses('Despesa', 'Model');

App::uses('CaixaMovimentacao', 'Model');

App::uses('Acesso', 'Model');

App::uses('Profissional', 'Model');



/**

 * CakePHP RelatorioController

 * @author Felipe

 */

class RelatorioController extends AuthController {



    public function index() {

        

    }



    public function paciente() {

        $letraInicial = "TODOS";

        $nome = "";

        $dataDE = date('01/m/Y');

        $dataATE = date('d/m/Y');



        $this->set("letraInicial", $letraInicial);

        $this->set("nome", $nome);

        $this->set("dataDE", $dataDE);

        $this->set("dataATE", $dataATE);

        $this->set("dataInicioDE", $dataDE);

        $this->set("dataInicioATE", $dataATE);

    }



    public function ajax_relatorio_paciente() {

        $this->layout = "ajax";

        $this->autoRender = false;



        $letraInicial = isset($this->request->data["letra_inicial"]) ? $this->request->data["letra_inicial"] : "TODOS";

        $nome = isset($this->request->data["nome"]) ? $this->request->data["nome"] : "";

        $dataDE = isset($this->request->data["data_de"]) ? $this->request->data["data_de"] : date('01/m/Y');

        $dataATE = isset($this->request->data["data_ate"]) ? $this->request->data["data_ate"] : date('d/m/Y');

        $dataInicioDE = isset($this->request->data["data_inicio_de"]) ? $this->request->data["data_inicio_de"] : date('01/m/Y');

        $dataInicioATE = isset($this->request->data["data_inicio_ate"]) ? $this->request->data["data_inicio_ate"] : date('d/m/Y');



        if ($dataDE != null && $dataDE != "") {

            $dataDE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataDE);

        }

        if ($dataATE != null && $dataATE != "") {

            $dataATE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataATE);

        }



        if ($dataInicioDE != null && $dataInicioDE != "") {

            $dataInicioDE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataInicioDE);

        }

        if ($dataInicioATE != null && $dataInicioATE != "") {

            $dataInicioATE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataInicioATE);

        }



        $paciente = new Paciente();

        $arrayPacientes = $paciente->relatorioPaciente($letraInicial, $nome, $dataDE, $dataATE, $dataInicioDE, $dataInicioATE);

        $size = count($arrayPacientes);

        $dados = "";
 
 

        if ($size > 0) {

            for ($i = 0; $i < $size; $i++) {

                $dados .= "

                <tr>

                    <td>{$arrayPacientes[$i]["p"]["nome"]} {$arrayPacientes[$i]["p"]["sobrenome"]}</td> 

                    <td>{$arrayPacientes[$i]["p"]["email"]}</td> 

                    <td class='text-center'>{$arrayPacientes[$i]["p"]["cpf"]}</td>

                    <td class='text-center'>

                        <div class='btn-group'> 

                            <button type='button' class='btn btn-success btn-show-events' onclick='mostrarEventos({$arrayPacientes[$i]["p"]["idpaciente"]})'><i class='fa fa-search-plus fa-lg'></i></button>                             

                        </div>

                    </td>

                </tr>

                <tr id='paciente-{$arrayPacientes[$i]["p"]["idpaciente"]}' style='display: none;'>

                    <td colspan='4' style='background-color: #d9edf7;'> 

                            <div class='col-xs-2 col-md-1'>

                                <i class='fa fa-arrow-right fa-2x pull-right'></i>

                            </div>

                            <div class='col-xs-10 col-md-11'>

                                <table class='table table-responsive table-bordered table-striped'>

                                <thead>

                                    <tr>

                                        <th>Profissional</th>
                                        
                                        <th>Evento</th>

                                        <th class='text-center'>Data de Início</th>

                                        <th class='text-center'>Data de Término</th>

                                    </tr>

                                </thead>

                                <tbody>";

                if (isset($arrayPacientes[$i]["0"]["Inicio"])) {

                    $idpaciente = isset($arrayPacientes[$i]["p"]["idpaciente"]) ? $arrayPacientes[$i]["p"]["idpaciente"] : -1;

                    for ($i = $i; $i < $size; $i++) {

                        if ($idpaciente == $arrayPacientes[$i]["p"]["idpaciente"]) {

                            $dados .= "

                                <tr>

                                    <td>{$arrayPacientes[$i]["pro"]["nome"]} {$arrayPacientes[$i]["pro"]["sobrenome"]}</td>

                                    <td>{$arrayPacientes[$i]["r"]["descricao"]}</td>

                                    <td class='text-center'>" . date('d/m/Y - H:i:s', strtotime($arrayPacientes[$i]["0"]["Inicio"])) . "</td>

                                    <td class='text-center'>" . date('d/m/Y - H:i:s', strtotime($arrayPacientes[$i]["0"]["Fim"])) . "</td>

                                </tr>";

                        } else {

                            break;

                        }

                    }

                    $i--;

                } else {

                    $dados .= "

                        <tr>

                            <td colspan='4'><p class='text-center'> Não há registros</p></td>

                        </tr>";

                }

                $dados .= "

                        </tbody>

                        <tfoot>

                            <tr>

                                <th>Profissional</th>

                                <th>Evento</th>

                                <th class='text-center'>Data de Início</th>

                                <th class='text-center'>Data de Término</th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </td>

        </tr>

        <tr></tr>";

            }

        } else {

            $dados .= "

            <tr>

                <td colspan='4'><p class='text-center'> Não há registros</p></td>

            </tr>";

        }



        $this->response->body($dados);

    }



    public function despesa() {

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

        $dataPagamentoDE = date('d/m/Y');

        $dataPagamentoATE = date('d/m/Y');

        $tipoFinanceiro_ = 0;

        $planoContas_ = 0;

        $favorecido_ = 0;

        $contas_pagas_ = 1;

        $clinica_ = 0;

        $radioDataCadastro_ = 1;

        $radioDataVencimento_ = 1;

        $nome_despesa_ = "";

        



        $this->set("dataCadastroDE", $dataCadastroDE);

        $this->set("dataCadastroATE", $dataCadastroATE);

        $this->set("dataVencimentoDE", $dataVencimentoDE);

        $this->set("dataVencimentoATE", $dataVencimentoATE);

        $this->set("dataPagamentoDE", $dataPagamentoDE);

        $this->set("dataPagamentoATE", $dataPagamentoATE);

        $this->set("tipoFinanceiro_", $tipoFinanceiro_);

        $this->set("planoContas_", $planoContas_);

        $this->set("favorecido_", $favorecido_);

        $this->set("contas_pagas_", $contas_pagas_);

        $this->set("clinica_", $clinica_);

        $this->set("radioDataCadastro_", $radioDataCadastro_);

        $this->set("radioDataVencimento_", $radioDataVencimento_);

        $this->set("nome_despesa_", $nome_despesa_);

    }



    public function ajax_relatorio_despesa() {

        $this->layout = "ajax";

        $this->autoRender = false;

        $despesa = new Despesa();

        $dataCadastroDE = isset($this->request->data["data_cadastro_de"]) ? $this->request->data["data_cadastro_de"] : date('d/m/Y');

        $dataCadastroATE = isset($this->request->data["data_cadastro_ate"]) ? $this->request->data["data_cadastro_ate"] : date('d/m/Y');

        $dataVencimentoDE = isset($this->request->data["data_vencimento_de"]) ? $this->request->data["data_vencimento_de"] : date('d/m/Y');

        $dataVencimentoATE = isset($this->request->data["data_vencimento_ate"]) ? $this->request->data["data_vencimento_ate"] : date('d/m/Y');

        $dataPagamentoDE = isset($this->request->data["data_pagamento_de"]) ? $this->request->data["data_pagamento_de"] : date('d/m/Y');

        $dataPagamentoATE = isset($this->request->data["data_pagamento_ate"]) ? $this->request->data["data_pagamento_ate"] : date('d/m/Y');

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



        if ($dataPagamentoDE != null && $dataPagamentoDE != "") {

            $dataPagamentoDE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataPagamentoDE);

        }

        if ($dataPagamentoATE != null && $dataPagamentoATE != "") {

            $dataPagamentoATE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataPagamentoATE);

        }



        $id_clinica = 0;

        $id_plano = 0;



        $valor_total_plano = 0;

        $valor_restante_plano = 0;

        $valor_pago_plano = 0;



        $valor_total_clinica = 0;

        $valor_restante_clinica = 0;

        $valor_pago_clinica = 0;



        $valor_total_geral = 0;

        $valor_restante_geral = 0;

        $valor_pago_geral = 0;



        $nome_clinica = "";

        $nome_plano = "";



        $arrayDespesa = $despesa->relatorioDespesa($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $dataPagamentoDE, $dataPagamentoATE, $tipoFinanceiro_, $planoContas_, $favorecido_, $contas_pagas_, $clinica_, $nome_despesa_);

        $size = count($arrayDespesa);

        $dados = "";



        for ($i = 0; $i < $size; $i++) {

            if ($id_clinica == 0) {

                $dados .= "

                <tr class='bg-primary'>

                    <td colspan='9'><b>Clinica: {$arrayDespesa[$i]['clinica']['fantasia']}</b></td>

                </tr>

                <tr class='bg-info'>

                    <td colspan='9'><b>Plano de Conta: {$arrayDespesa[$i]['plano_contas']['descricao']}</b></td>

                </tr>";

            }



            if ($id_plano != $arrayDespesa[$i]['plano_contas']['idplanocontas'] && $id_clinica != 0 && $id_clinica == $arrayDespesa[$i]['clinica']['idclinica']) {

                $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Plano de contas ($nome_plano) :</b> 

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_plano, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_plano, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_plano, 2, ",", ".") . "</b></td>

                </tr>";



                $valor_total_plano = 0;

                $valor_pago_plano = 0;

                $valor_restante_plano = 0;



                $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>	

                <tr class='bg-info'>

                    <td colspan='9'><b>Plano de Conta: {$arrayDespesa[$i]['plano_contas']['descricao']}</b></td>

                </tr>";

            }



            if ($id_clinica != $arrayDespesa[$i]['clinica']['idclinica'] && $id_clinica != 0) {

                $dados .= "

                    <tr>

                        <td colspan='9'></td>

                    </tr>				

                    <tr class='bg-ligth-gray'>

                        <td colspan='6'>

                            <b>Subtotal Plano de contas ($nome_plano) : </b>

                        </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_plano, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_plano, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_plano, 2, ",", ".") . "</b></td>

                    </tr>";



                $valor_total_plano = 0;

                $valor_pago_plano = 0;

                $valor_restante_plano = 0;



                $dados .= "

                    <tr>

                        <td colspan='9'></td>

                    </tr>	

                    <tr class='bg-ligth-gray'>

                        <td colspan='6'>

                            <b>Subtotal Clinica ($nome_clinica) : </b>

                        </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_clinica, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_clinica, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_clinica, 2, ",", ".") . "</b></td>

                    </tr>";



                $valor_total_clinica = 0;

                $valor_pago_clinica = 0;

                $valor_restante_clinica = 0;



                $dados .= "

                    <tr class='bg-primary'>

                        <td colspan='9'><b>Clinica: {$arrayDespesa[$i]['clinica']['fantasia']}</b></td>

                    </tr>

                    <tr>

                        <td colspan='9'></td>

                    </tr>	

                    <tr class='bg-info'>

                        <td colspan='9'><b>Plano de Conta: {$arrayDespesa[$i]['plano_contas']['descricao']}</b></td>

                    </tr>";

            }



            $dataVencimento = isset($arrayDespesa[$i]['financeiro']['data_vencimento']) ? date('d/m/Y', strtotime($arrayDespesa[$i]['financeiro']['data_vencimento'])) : "";

            $dataPagamento = isset($arrayDespesa[$i]['financeiro']['data_pagamento']) ? date('d/m/Y', strtotime($arrayDespesa[$i]['financeiro']['data_pagamento'])) : "";



            $dados .= "

                <tr>

                    <td>{$arrayDespesa[$i]['despesa']['descricao']}</td>

                    <td>{$arrayDespesa[$i]['centro_custos']['descricao']}</td>

                    <td>{$arrayDespesa[$i]['tipo_financeiro']['tipo']}</td>

                    <td>{$arrayDespesa[$i]['favorecido']['nome']}</td>

                    <td class='text-center'><b>" . $dataVencimento . "</b></td>

                    <td class='text-center'><b>" . $dataPagamento . "</b></td>

                    <td class='text-center'><b>R$ " . number_format($arrayDespesa[$i][0]['valor_total'], 2, ",", ".") . "</b></td>

                    <td class='text-center'><b>R$ " . number_format($arrayDespesa[$i][0]['total_pago'], 2, ",", ".") . "</b></td>

                    <td class='text-center'><b>R$ " . number_format($arrayDespesa[$i][0]['total_restante'], 2, ",", ".") . "</b></td>

                </tr>";



            $nome_clinica = $arrayDespesa[$i]['clinica']['fantasia'];

            $id_clinica = $arrayDespesa[$i]['clinica']['idclinica'];



            $nome_plano = $arrayDespesa[$i]['plano_contas']['descricao'];

            $id_plano = $arrayDespesa[$i]['plano_contas']['idplanocontas'];



            $valor_total_clinica += $arrayDespesa[$i][0]['valor_total'];

            $valor_pago_clinica += $arrayDespesa[$i][0]['total_pago'];

            $valor_restante_clinica += $arrayDespesa[$i][0]['total_restante'];



            $valor_total_plano += $arrayDespesa[$i][0]['valor_total'];

            $valor_pago_plano += $arrayDespesa[$i][0]['total_pago'];

            $valor_restante_plano += $arrayDespesa[$i][0]['total_restante'];



            $valor_total_geral += $arrayDespesa[$i][0]['valor_total'];

            $valor_pago_geral += $arrayDespesa[$i][0]['total_pago'];

            $valor_restante_geral += $arrayDespesa[$i][0]['total_restante'];

        }

        if ($size > 0) {

            $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Plano de contas ($nome_plano) : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_plano, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_plano, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_plano, 2, ",", ".") . "</b></td>

                </tr>";



            $valor_total_plano = 0;

            $valor_pago_plano = 0;

            $valor_restante_plano = 0;



            $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>	

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Clinica ($nome_clinica) : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_clinica, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_clinica, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_clinica, 2, ",", ".") . "</b></td>

                </tr>";



            $valor_total_clinica = 0;

            $valor_pago_clinica = 0;

            $valor_restante_clinica = 0;



            $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>	

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Geral : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_geral, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_geral, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_geral, 2, ",", ".") . "</b></td>

                </tr>";

        } else {

            $dados .= "

                <tr>

                    <td colspan='9' class='text-center'>Não há registros</td>                    

                </tr>";

        }





        $this->response->body($dados);

    }



    public function caixa() {

        $caixaLoja = new CaixaLoja();

        $tipoFinanceiro = new TipoFinanceiro();

        $clinica = new Clinica();        

        

        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;



        $financeiros_ = $tipoFinanceiro->retornarTodos();

        $financeirosPagaveis_ = $tipoFinanceiro->retornarTodosPagaveis();

        $clinicas_ = $clinica->retornarTodos(" and c.idclinica = {$idClinica} ");

        $caixas_ = $caixaLoja->retornarTodos();



        $this->set('TipoFinanceirosRelatorio', $financeiros_);

        $this->set('TipoFinanceiros', $financeirosPagaveis_);

        $this->set("Clinicas", $clinicas_);

        $this->set('Caixas', $caixas_);



        $data_movimentacaoDE = date('d/m/Y');

        $data_movimentacaoATE = date('d/m/Y');



        $tipoFinanceiro_ = 0;

        $clinica_ = 0;

        $radioDataCadastro_ = 1;



        $this->set("data_movimentacaoDE", $data_movimentacaoDE);

        $this->set("data_movimentacaoATE", $data_movimentacaoATE);

        $this->set("tipoFinanceiro_", $tipoFinanceiro_);

        $this->set("clinica_", $clinica_);

        $this->set("radioDataCadastro_", $radioDataCadastro_);

        $this->set('Caixas', $caixas_);

    }



    public function ajax_relatorio_caixa() {

        $this->layout = "ajax";

        $this->autoRender = false;



        $caixa_movimentacao = new CaixaMovimentacao();



        $data_movimentacaoDE = isset($this->request->data["data_movimentacao_de"]) ? $this->request->data["data_movimentacao_de"] : date('d/m/Y');

        $data_movimentacaoATE = isset($this->request->data["data_movimentacao_ate"]) ? $this->request->data["data_movimentacao_ate"] : date('d/m/Y');



        $tipoFinanceiro_ = isset($this->request->data["tipo_financeiro"]) ? $this->request->data["tipo_financeiro"] : 0;

        $clinica_ = isset($this->request->data["clinica"]) ? $this->request->data["clinica"] : 0;

        $caixa_ = isset($this->request->data["Caixa"]) ? $this->request->data["Caixa"] : 0;



        if ($data_movimentacaoDE != null && $data_movimentacaoDE != "") {

            $data_movimentacaoDE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $data_movimentacaoDE);

        }

        if ($data_movimentacaoATE != null && $data_movimentacaoATE != "") {

            $data_movimentacaoATE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $data_movimentacaoATE);

        }



        $id_clinica = 0;

        $id_tipo_financeiro = 0;



        $valor_financeiro = 0;

        $valor__clinica = 0;

        $saldo_caixa = 0;



        $nome_clinica = "";

        $nome_financeiro = "";



        $arrayCaixa = $caixa_movimentacao->relatorioCaixa($data_movimentacaoDE, $data_movimentacaoATE, $tipoFinanceiro_, $clinica_, $caixa_);

        $size = count($arrayCaixa);

        $dados = "";



        for ($i = 0; $i < $size; $i++) {

            if ($id_clinica == 0) {

                $dados .= "

                <tr class='bg-primary'>

                    <td colspan='7'><b>Clinica: {$arrayCaixa[$i]['cli']['fantasia']}</b></td>

                </tr>

                <tr class='bg-info'>

                    <td colspan='7'><b>Tipo de Financeiro: {$arrayCaixa[$i]['t']['tipo']}</b</td>

                </tr>";

            }



            if ($id_tipo_financeiro != $arrayCaixa[$i]['c']['id_tipo_financeiro'] && $id_clinica != 0 && $id_clinica == $arrayCaixa[$i]['c']['id_clinica']) {

                $dados .= "

                <tr>

                    <td colspan='7'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='5'>

                        <b>Subtotal Tipo de Financeiro ($nome_financeiro) :</b> 

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_financeiro, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'></td>

                </tr>";



                $valor_financeiro = 0;

                $saldo_caixa = 0;

                $dados .= "

                <tr>

                    <td colspan='7'></td>

                </tr>	

                <tr class='bg-info'>

                    <td colspan='7'><b>Tipo de Financeiro: {$arrayCaixa[$i]['t']['tipo']}</b></td>

                </tr>";

            }



            if ($id_clinica != $arrayCaixa[$i]['c']['id_clinica'] && $id_clinica != 0) {

                $dados .= "

                    <tr>

                        <td colspan='7'></td>

                    </tr>				

                    <tr class='bg-ligth-gray'>

                        <td colspan='5'>

                            <b>Subtotal Tipo de Financeiro ($nome_financeiro) : </b>

                        </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_financeiro, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'></td>

                    </tr>";



                $valor_financeiro = 0;

                $saldo_caixa = 0;



                $dados .= "

                    <tr>

                        <td colspan='7'></td>

                    </tr>	

                    <tr class='bg-ligth-gray'>

                        <td colspan='5'>

                            <b>Subtotal Clinica ($nome_clinica) : </b>

                        </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor__clinica, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($saldo_caixa, 2, ",", ".") . "</b></td>

                    </tr>";



                $valor__clinica = 0;

                $saldo_caixa = 0;



                $dados .= "

                    <tr class='bg-primary'>

                        <td colspan='7'> <b>Clinica: {$arrayCaixa[$i]['cli']['fantasia']} </b></td>

                    </tr>

                    <tr>

                        <td colspan='7'></td>

                    </tr>	

                    <tr class='bg-info'>

                        <td colspan='7'> <b>Tipo de Financeiro: {$arrayCaixa[$i]['t']['tipo']}</b> </td>

                    </tr>";

            }



            $data = date('d/m/Y H:i:s', strtotime($arrayCaixa[$i]['c']['data_pagamento']));

            $dados .= "

                <tr>

                    <td>{$data}</td>

                    <td>{$arrayCaixa[$i][0]['Tipo_movimentacao']}</td>

                    <td>{$arrayCaixa[$i]['c']['descricao']}</td>

                    <td>{$arrayCaixa[$i]['c']['parcela']}/{$arrayCaixa[$i]['c']['total_parcelas']}</td>

                    <td>{$arrayCaixa[$i]['u']['nome']}</td>

                    <td class='text-center'><b>R$ " . number_format($arrayCaixa[$i]['c']['valor'], 2, ",", ".") . "</b></td>

                    <td class='text-center'><b>R$ " . number_format(($saldo_caixa + $arrayCaixa[$i]['c']['valor']), 2, ",", ".") . "</b></td>

                </tr>";



            $nome_clinica = $arrayCaixa[$i]['cli']['fantasia'];

            $id_clinica = $arrayCaixa[$i]['c']['id_clinica'];



            $nome_financeiro = $arrayCaixa[$i]['t']['tipo'];

            $id_tipo_financeiro = $arrayCaixa[$i]['c']['id_tipo_financeiro'];



            $valor_financeiro += $arrayCaixa[$i]['c']['valor'];

            $valor__clinica += $arrayCaixa[$i]['c']['valor'];

            $saldo_caixa += $arrayCaixa[$i]['c']['valor'];

        }

        if ($size > 0) {

            $dados .= "

                <tr>

                    <td colspan='7'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='5'>

                        <b>Subtotal Tipo de Financeiro ($nome_financeiro) : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_financeiro, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($saldo_caixa, 2, ",", ".") . "</b></td>

                </tr>";



            $dados .= "

                <tr>

                    <td colspan='7'></td>

                </tr>	

                <tr class='bg-ligth-gray'>

                    <td colspan='5'>

                        <b>Subtotal Clinica ($nome_clinica) : </b>

                    </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor__clinica, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'></td>

                </tr>";

        } else {

            $dados .= "

                <tr>

                    <td colspan='7' class='text-center'>Não há registros</td>                    

                </tr>";

        }





        $this->response->body($dados);

    }



    public function tabela_movimentacoes($id_caixa) {

        $this->layout = "ajax";

        $this->autoRender = false;



        $caixa_movimentacao = new CaixaMovimentacao();

        $arrayCaixaMovto = $caixa_movimentacao->retornarFinanceiros($id_caixa);

        $size = count($arrayCaixaMovto);



        $dados = "";

        $dados .= "

            <thead>

                <tr class='info'>

                    <th>

                        Tipo de Financeiro

                    </th>

                    <th>

                        Valor

                    </th>

                </tr>

            </thead>";



        for ($i = 0; $i < $size; $i++) {

            $dados .= "

            <thead>

                <tr>

                    <td>

                        <b>{$arrayCaixaMovto[$i]['t']['Tipo_movimentacao']}</b>

                    </td>

                    <td>

                        <b>R$ " . number_format($arrayCaixaMovto[$i][0]['Valor'], 2, ",", ".") . "</b>

                    </td>

                </tr>

            </thead>";

        }

        $this->response->body($dados);

    }



    public function inserir_movimentacao() {

        $data = $this->request->data;

        $caixa_movimentacao = new CaixaMovimentacao();



        $str_explode = explode(',', $data['valor_movto']);

        $data['valor_movto'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];



        if ((int) $data['rbtMovimentacao'] == 1) {

            $caixa_movimentacao->GeraEntradaCaixa(0, $data['Caixa'], $this->_idDaSessao, 0, $data['tipo_financeiro'], $data['observacao'], $data['valor_movto']);

        } else if ((int) $data['rbtMovimentacao'] == 0) {

            $caixa_movimentacao->GeraSaidaCaixa(0, $data['Caixa'], $this->_idDaSessao, 0, $data['tipo_financeiro'], $data['observacao'], $data['valor_movto']);

        } else {

            //Transferencia !!

            $caixa_movimentacao->Transferencia($data['Caixa'], $data['caixa_destino'], $this->_idDaSessao, $data['tipo_financeiro'], $data['observacao'], $data['valor_movto']);

        }



        $this->response->body("");

    }



    public function fechar_caixa() {

        $data = $this->request->data;

        $caixa_movimentacao = new CaixaMovimentacao();



        $caixa_movimentacao->fechamentoCaixa($data['Caixa'], $this->_idDaSessao);



        $this->response->body("");

    }



    public function recebimento() {



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

        $dataPagamentoDE = date('d/m/Y');

        $dataPagamentoATE = date('d/m/Y');

        $tipoFinanceiro_ = 0;

        $planoContas_ = 0;

        $paciente_ = 0;

        $cheque_ = 1;

        $pago_ = 1;

        $clinica_ = 0;

        $radioDataCadastro_ = 1;

        $radioDataVencimento_ = 1;

        $nome_recebimento = "";



        $this->set("dataCadastroDE", $dataCadastroDE);

        $this->set("dataCadastroATE", $dataCadastroATE);

        $this->set("dataVencimentoDE", $dataVencimentoDE);

        $this->set("dataVencimentoATE", $dataVencimentoATE);

        $this->set("dataPagamentoDE", $dataPagamentoDE);

        $this->set("dataPagamentoATE", $dataPagamentoATE);

        $this->set("tipoFinanceiro_", $tipoFinanceiro_);

        $this->set("planoContas_", $planoContas_);

        $this->set("paciente_", $paciente_);

        $this->set("cheque_", $cheque_);

        $this->set("pago_", $pago_);

        $this->set("clinica_", $clinica_);

        $this->set("radioDataCadastro_", $radioDataCadastro_);

        $this->set("radioDataVencimento_", $radioDataVencimento_);

        $this->set("nome_recebimento", $nome_recebimento);

    }



    public function ajax_relatorio_recebimento() {

        $this->layout = "ajax";

        $this->autoRender = false;

        $recebimento = new Recebimento();

        $dataCadastroDE = isset($this->request->data["data_cadastro_de"]) ? $this->request->data["data_cadastro_de"] : date('d/m/Y');

        $dataCadastroATE = isset($this->request->data["data_cadastro_ate"]) ? $this->request->data["data_cadastro_ate"] : date('d/m/Y');

        $dataVencimentoDE = isset($this->request->data["data_vencimento_de"]) ? $this->request->data["data_vencimento_de"] : date('d/m/Y');

        $dataVencimentoATE = isset($this->request->data["data_vencimento_ate"]) ? $this->request->data["data_vencimento_ate"] : date('d/m/Y');

        $dataPagamentoDE = isset($this->request->data["data_pagamento_de"]) ? $this->request->data["data_pagamento_de"] : date('d/m/Y');

        $dataPagamentoATE = isset($this->request->data["data_pagamento_ate"]) ? $this->request->data["data_pagamento_ate"] : date('d/m/Y');

        $tipoFinanceiro_ = isset($this->request->data["tipo_financeiro"]) ? $this->request->data["tipo_financeiro"] : 0;

        $planoContas_ = isset($this->request->data["plano_contas"]) ? $this->request->data["plano_contas"] : 0;

        $paciente_ = isset($this->request->data["paciente"]) ? $this->request->data["paciente"] : 0;

        $cheque_ = isset($this->request->data["cheque"]) ? $this->request->data["cheque"] : 1;

        $pago_ = isset($this->request->data["pago"]) ? $this->request->data["pago"] : 1;

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

        if ($dataPagamentoDE != null && $dataPagamentoDE != "") {

            $dataPagamentoDE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataPagamentoDE);

        }

        if ($dataPagamentoATE != null && $dataPagamentoATE != "") {

            $dataPagamentoATE = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataPagamentoATE);

        }





        $id_clinica = 0;

        $id_plano = 0;



        $valor_total_plano = 0;

        $valor_restante_plano = 0;

        $valor_pago_plano = 0;



        $valor_total_clinica = 0;

        $valor_restante_clinica = 0;

        $valor_pago_clinica = 0;



        $valor_total_geral = 0;

        $valor_restante_geral = 0;

        $valor_pago_geral = 0;



        $nome_clinica = "";

        $nome_plano = "";



        $arrayRecebimento = $recebimento->relatorio_recebimentos($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $dataPagamentoDE, $dataPagamentoATE, $tipoFinanceiro_, $planoContas_, $paciente_, $cheque_, $clinica_, $nome_recebimento_, $pago_);

        $size = count($arrayRecebimento);

        $dados = "";



        for ($i = 0; $i < $size; $i++) {

            if ($id_clinica == 0) {

                $dados .= "

                <tr class='bg-primary'>

                    <td colspan='9'><b>Clinica: {$arrayRecebimento[$i]['clinica']['fantasia']}</b></td>

                </tr>

                <tr class='bg-info'>

                    <td colspan='9'><b>Plano de Conta: {$arrayRecebimento[$i]['p']['descricao']}</b></td>

                </tr>";

            }



            if ($id_plano != $arrayRecebimento[$i]['p']['idplanocontas'] && $id_clinica != 0 && $id_clinica == $arrayRecebimento[$i]['clinica']['idclinica']) {

                $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Plano de contas ($nome_plano) :</b> 

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_plano, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_plano, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_plano, 2, ",", ".") . "</b></td>

                </tr>";



                $valor_total_plano = 0;

                $valor_pago_plano = 0;

                $valor_restante_plano = 0;



                $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>	

                <tr class='bg-info'>

                    <td colspan='9'><b>Plano de Conta: {$arrayRecebimento[$i]['p']['descricao']}</b></td>

                </tr>";

            }



            if ($id_clinica != $arrayRecebimento[$i]['clinica']['idclinica'] && $id_clinica != 0) {

                $dados .= "

                    <tr>

                        <td colspan='9'></td>

                    </tr>				

                    <tr class='bg-ligth-gray'>

                        <td colspan='6'>

                            <b>Subtotal Plano de contas ($nome_plano) : </b>

                        </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_plano, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_plano, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_plano, 2, ",", ".") . "</b></td>

                    </tr>";



                $valor_total_plano = 0;

                $valor_pago_plano = 0;

                $valor_restante_plano = 0;



                $dados .= "

                    <tr>

                        <td colspan='9'></td>

                    </tr>	

                    <tr class='bg-ligth-gray'>

                        <td colspan='6'>

                            <b>Subtotal Clinica ($nome_clinica) : </b>

                        </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_clinica, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_clinica, 2, ",", ".") . "</b></td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_clinica, 2, ",", ".") . "</b></td>

                    </tr>";



                $valor_total_clinica = 0;

                $valor_pago_clinica = 0;

                $valor_restante_clinica = 0;



                $dados .= "

                    <tr class='bg-primary'>

                        <td colspan='9'><b>Clinica: {$arrayRecebimento[$i]['clinica']['fantasia']}</b></td>

                    </tr>

                    <tr>

                        <td colspan='9'></td>

                    </tr>	

                    <tr class='bg-info'>

                        <td colspan='9'><b>Plano de Conta: {$arrayRecebimento[$i]['p']['descricao']}</b></td>

                    </tr>";

            }



            $dataVencimento = isset($arrayRecebimento[$i]['financeiro']['data_vencimento']) ? date('d/m/Y', strtotime($arrayRecebimento[$i]['financeiro']['data_vencimento'])) : "";

            $dataPagamento = isset($arrayRecebimento[$i]['financeiro']['data_pagamento']) ? date('d/m/Y', strtotime($arrayRecebimento[$i]['financeiro']['data_pagamento'])) : "";



            $dados .= "

                <tr>

                    <td nowrap='nowrap'>{$arrayRecebimento[$i]['r']['descricao']}</td>

                    <td>{$arrayRecebimento[$i]['c']['descricao']}</td>

                    <td>{$arrayRecebimento[$i]['tipo_financeiro']['tipo']}</td>

                    <td>{$arrayRecebimento[$i]['pc']['nome']} {$arrayRecebimento[$i]['pc']['sobrenome']}</td>

                    <td class='text-center'><b>" . $dataVencimento . "</b></td>

                    <td class='text-center'><b>" . $dataPagamento . "</b></td>

                    <td class='text-center'><b>R$ " . number_format($arrayRecebimento[$i][0]['valor_total'], 2, ",", ".") . "</b></td>

                    <td class='text-center'><b>R$ " . number_format($arrayRecebimento[$i][0]['total_pago'], 2, ",", ".") . "</b></td>

                    <td class='text-center'><b>R$ " . number_format($arrayRecebimento[$i][0]['total_restante'], 2, ",", ".") . "</b></td>

                </tr>";



            $nome_clinica = $arrayRecebimento[$i]['clinica']['fantasia'];

            $id_clinica = $arrayRecebimento[$i]['clinica']['idclinica'];



            $nome_plano = $arrayRecebimento[$i]['p']['descricao'];

            $id_plano = $arrayRecebimento[$i]['p']['idplanocontas'];



            $valor_total_clinica += $arrayRecebimento[$i][0]['valor_total'];

            $valor_pago_clinica += $arrayRecebimento[$i][0]['total_pago'];

            $valor_restante_clinica += $arrayRecebimento[$i][0]['total_restante'];



            $valor_total_plano += $arrayRecebimento[$i][0]['valor_total'];

            $valor_pago_plano += $arrayRecebimento[$i][0]['total_pago'];

            $valor_restante_plano += $arrayRecebimento[$i][0]['total_restante'];



            $valor_total_geral += $arrayRecebimento[$i][0]['valor_total'];

            $valor_pago_geral += $arrayRecebimento[$i][0]['total_pago'];

            $valor_restante_geral += $arrayRecebimento[$i][0]['total_restante'];

        }

        if ($size > 0) {

            $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Plano de contas ($nome_plano) : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_plano, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_plano, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_plano, 2, ",", ".") . "</b></td>

                </tr>";



            $valor_total_plano = 0;

            $valor_pago_plano = 0;

            $valor_restante_plano = 0;



            $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>	

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Clinica ($nome_clinica) : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_clinica, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_clinica, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_clinica, 2, ",", ".") . "</b></td>

                </tr>";



            $valor_total_clinica = 0;

            $valor_pago_clinica = 0;

            $valor_restante_clinica = 0;



            $dados .= "

                <tr>

                    <td colspan='9'></td>

                </tr>	

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Geral : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_total_geral, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_pago_geral, 2, ",", ".") . "</b></td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_restante_geral, 2, ",", ".") . "</b></td>

                </tr>";

        } else {

            $dados .= "

                <tr>

                    <td colspan='9' class='text-center'>Não há registros</td>                    

                </tr>";

        }





        $this->response->body($dados);

    }



    public function cheque() {

        $tipoFinanceiro = new TipoFinanceiro();

        $paciente = new Paciente();

        $plano = new PlanoContas();

        $clinica = new Clinica();

        $caixaLoja = new CaixaLoja();



        $financeiros_ = $tipoFinanceiro->retornarTodos();

        $financeirosPagaveis_ = $tipoFinanceiro->retornarTodosPagaveis();

        $pacientes_ = $paciente->retornarTodos();

        $planos_ = $plano->retornarTodosReceitas();

        $clinicas_ = $clinica->retornarTodos();

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

        $clinica_ = 0;

        $radioDataCadastro_ = 1;

        $radioDataVencimento_ = 1;

        $numero_cheque = "";

        $compensado_ = 1;



        $this->set("dataCadastroDE", $dataCadastroDE);

        $this->set("dataCadastroATE", $dataCadastroATE);

        $this->set("dataVencimentoDE", $dataVencimentoDE);

        $this->set("dataVencimentoATE", $dataVencimentoATE);

        $this->set("tipoFinanceiro_", $tipoFinanceiro_);

        $this->set("planoContas_", $planoContas_);

        $this->set("paciente_", $paciente_);

        $this->set("clinica_", $clinica_);

        $this->set("radioDataCadastro_", $radioDataCadastro_);

        $this->set("radioDataVencimento_", $radioDataVencimento_);

        $this->set("numero_cheque", $numero_cheque);

        $this->set("compensado_", $compensado_);

    }



    public function ajax_relatorio_cheque() {

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

        $clinica_ = isset($this->request->data["clinica"]) ? $this->request->data["clinica"] : 0;

        $numero_cheque_ = isset($this->request->data["numero_cheque"]) ? $this->request->data["numero_cheque"] : "";

        $compensado_ = isset($this->request->data["compensado"]) ? $this->request->data["compensado"] : 1;



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





        $id_clinica = 0;

        $mes = "";



        $valor_mes = 0;

        $valor_clinica = 0;

        $valor_geral = 0;



        $nome_clinica = "";

        $nome_plano = "";



        $arrayCheques = $financeiro->relatorio_cheques($dataCadastroDE, $dataCadastroATE, $dataVencimentoDE, $dataVencimentoATE, $planoContas_, $paciente_, $clinica_, $numero_cheque_, $tipoFinanceiro_, $compensado_);

        $size = count($arrayCheques);

        $dados = "";



        for ($i = 0; $i < $size; $i++) {

            if ($id_clinica == 0) {

                $dados .= "

                <tr class='bg-primary'>

                    <td colspan='10'><b>Clinica: {$arrayCheques[$i]['c']['fantasia']}</b></td>

                </tr>

                <tr class='bg-info'>

                    <td colspan='10'><b>Mês: {$arrayCheques[$i][0]['Data_ordem']}</b></td>

                </tr>";

            }



            if ($mes != $arrayCheques[$i][0]['Data_ordem'] && $id_clinica != 0 && $id_clinica == $arrayCheques[$i]['c']['idclinica']) {

                $dados .= "

                <tr>

                    <td colspan='10'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal mês ($mes) :</b> 

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_mes, 2, ",", ".") . "</b></td>

                    <td colspan='3'></td>

                </tr>";



                $valor_mes = 0;



                $dados .= "

                <tr>

                    <td colspan='10'></td>

                </tr>	

                <tr class='bg-info'>

                    <td colspan='10'><b>Mês: {$arrayCheques[$i][0]['Data_ordem']}</b></td>

                </tr>";

            }



            if ($id_clinica != $arrayCheques[$i]['c']['idclinica'] && $id_clinica != 0) {

                $dados .= "

                    <tr>

                        <td colspan='10'></td>

                    </tr>				

                    <tr class='bg-ligth-gray'>

                        <td colspan='6'>

                            <b>Subtotal Mês ($mes) : </b>

                        </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_mes, 2, ",", ".") . "</b></td>

                        <td colspan='2'></td>

                    </tr>";



                $valor_mes = 0;

                $dados .= "

                    <tr>

                        <td colspan='9'></td>

                    </tr>	

                    <tr class='bg-ligth-gray'>

                        <td colspan='6'>

                            <b>Subtotal Clinica ($nome_clinica) : </b>

                        </td>

                        <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_clinica, 2, ",", ".") . "</b></td>

                        <td colspan='3'></td>

                    </tr>";



                $valor_clinica = 0;



                $dados .= "

                    <tr class='bg-primary'>

                        <td colspan='10'><b>Clinica: {$arrayCheques[$i]['c']['fantasia']}</b></td>

                    </tr>

                    <tr>

                        <td colspan='10'></td>

                    </tr>	

                    <tr class='bg-info'>

                        <td colspan='10'><b>Mês: {$arrayCheques[$i][0]['Data_ordem']}</b></td>

                    </tr>";

            }



            if ($arrayCheques[$i]['f']['data_vencimento'] != '') {

                $data_vencimento = date('d/m/Y H:i:s', strtotime($arrayCheques[$i]['f']['data_vencimento']));

            } else {

                $data_vencimento = "--";

            }



            if ($arrayCheques[$i][0]['Data_compensacao'] != '') {

                $data_compensacao = date('d/m/Y H:i:s', strtotime($arrayCheques[$i][0]['Data_compensacao']));

            } else {

                $data_compensacao = "--";

            }



            if ($arrayCheques[$i][0]['Status'] == "Pendente") {

                $status = "<td nowrap='nowrap'><font color='red'>{$arrayCheques[$i][0]['Status']}</font>  <span class='btn btn-success' title='Compensar o cheque.' data-toggle='tooltip' data-placement='bottom' onclick='receber_cheque({$arrayCheques[$i]['f']['idfinanceiro']})'><i class='fa fa-check green'></i></span></td>";

            } else {

                $status = "<td><font color='green'>{$arrayCheques[$i][0]['Status']}</font></td>";

            }



            $valor_aux = $arrayCheques[$i][0]['valor']; //Para um resultado correto deve-se considerar o limite de 11 casas após a vírgula na dízima) 

            $valor_aux = intval(strval($valor_aux * 100)) / 100;



            $dados .= "

                <tr>

                    <td>{$arrayCheques[$i][0]['Pessoa']}</td>

                    <td>{$arrayCheques[$i]['f']['num_cheque']}</td>

                    <td>{$arrayCheques[$i][0]['Descricao']}</td>

                    <td>{$arrayCheques[$i]['cx']['nome_caixa']}</td>

                    <td>{$arrayCheques[$i]['pc']['descricao']}</td>

                    <td>{$data_vencimento}</td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_aux, 2, ",", ".") . "</b></td>

                    $status

                    <td>{$data_compensacao}</td>

                    <td>{$arrayCheques[$i][0]['Usuario']}</td>

                </tr>";



            $nome_clinica = $arrayCheques[$i]['c']['fantasia'];

            $id_clinica = $arrayCheques[$i]['c']['idclinica'];



            $mes = $arrayCheques[$i][0]['Data_ordem'];

            $valor_mes += $arrayCheques[$i][0]['valor'];

            $valor_clinica += $arrayCheques[$i][0]['valor'];

            $valor_geral += $arrayCheques[$i][0]['valor'];

        }

        if ($size > 0) {

            $dados .= "

                <tr>

                    <td colspan='10'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal mês ($mes) : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_mes, 2, ",", ".") . "</b></td>

                    <td colspan='3'></td>

                </tr>";



            $dados .= "

                <tr>

                    <td colspan='10'></td>

                </tr>	

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Clinica ($nome_clinica) : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_clinica, 2, ",", ".") . "</b></td>

                    <td colspan='3'></td>

                </tr>";



            $dados .= "

                <tr>

                    <td colspan='10'></td>

                </tr>	

                <tr class='bg-ligth-gray'>

                    <td colspan='6'>

                        <b>Subtotal Geral : </b>

                    </td>

                    <td class='text-center' nowrap='nowrap'><b>R$ " . number_format($valor_geral, 2, ",", ".") . "</b></td>

                    <td colspan='3'></td>

                </tr>";

        } else {

            $dados .= "

                <tr>

                    <td colspan='10' class='text-center'>Não há registros</td>                    

                </tr>";

        }





        $this->response->body($dados);

    }



    public function compensar_cheque() {

        $this->layout = "ajax";

        $this->autoRender = false;

        $financeiro = new Financeiro();

        $financeiro->compensar_cheque_financeiro($this->request->query["idfinanceiro"], $this->_idDaSessao);

    }



    public function comissao() {



        if ($this->request->is("get")) {

            $profissional = new Profissional();



            if ($this->validarAcessoBoolean($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_RELATORIO_VALOR_TOTAL_COMISSAO)) {

                $profissionais = $profissional->retornarTodos();

            } else {

                $profissionais = $profissional->retornarPorIdUsuario($this->_idDaSessao);

            }



            $dataRelatorio = date('m-Y');



            $this->set("Profissionais", $profissionais);

            $this->set("dataRelatorio", $dataRelatorio);

        }

    }



    public function ajax_relatorio_comissao() {

        $this->layout = "ajax";

        $this->autoRender = false;

        $financeiro = new Financeiro();



        $dataRelatorio = isset($this->request->data["data-relatorio"]) ? $this->request->data["data-relatorio"] : date('m-Y');

        $profissional = isset($this->request->data["profissional"]) ? $this->request->data["profissional"] : 0;





        $array = $financeiro->relatorio_comissao($dataRelatorio, $profissional);

        $size = count($array);

        $dados = "";

        $id_profissional = 0;

        $id_paciente = 0;



        $nome_paciente = "";

        $nome_profissional = "";



        $Valor_comissao_paciente = 0;

        $Valor_geral_paciente = 0;



        $Valor_comissao_profissional = 0;

        $Valor_geral_profissional = 0;



        $Valor_comissao_geral = 0;

        $Valor_geral_geral = 0;

        $colspan = 9;

        $colspan_sub = 7;

        $exibe = $this->validarAcessoBoolean($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_RELATORIO_VALOR_TOTAL_COMISSAO);



        if (!$exibe) {

            $colspan = 8;

            $colspan_sub = 7;

        }



        for ($i = 0; $i < $size; $i++) {

            if ($id_profissional == 0) {

                $dados .= "

                <tr class='bg-primary'>

                    <td colspan='$colspan'><b>Profissional: {$array[$i]['X']['Profissional']}</b></td>

                </tr>

                <tr class='bg-info'>

                    <td colspan='$colspan'><b>Paciente: {$array[$i]['X']['Paciente']}</b</td>

                </tr>";

            }



            if ($id_paciente != $array[$i]['X']['idpaciente'] && $id_profissional != 0 && $id_profissional == $array[$i]['X']['idprofissional']) {

                $dados .= "

                <tr>

                    <td colspan='$colspan'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='$colspan_sub'>

                        <b>Subtotal Paciente ($nome_paciente) :</b> 

                    </td>

                    <td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_comissao_paciente, 2, ",", ".") . "</b></td>";

                if ($exibe) {

                    $dados .= "<td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_geral_paciente, 2, ",", ".") . "</b></td>";

                }

                $dados .= "</tr>";





                $dados .= "

                <tr>

                    <td colspan='$colspan'></td>

                </tr>	

                <tr class='bg-info'>

                    <td colspan='$colspan'><b>Paciente: {$array[$i]['X']['Paciente']}</b></td>

                </tr>";

                $Valor_comissao_paciente = 0;

                $Valor_geral_paciente = 0;

            }



            if ($id_profissional != $array[$i]['X']['idprofissional'] && $id_profissional != 0) {

                $dados .= "

                    <tr>

                        <td colspan='$colspan'></td>

                    </tr>				

                    <tr class='bg-ligth-gray'>

                        <td colspan='$colspan_sub'>

                            <b>Subtotal Paciente ($nome_paciente) : </b>

                        </td>

                    <td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_comissao_paciente, 2, ",", ".") . "</b></td>";

                if ($exibe) {

                    $dados .= "<td class='text-right'>" . number_format($Valor_geral_geral, 2, ",", ".") . "</td>";

                }



                $dados .= "</tr>";



                $Valor_comissao_paciente = 0;

                $Valor_geral_geral = 0;

                $dados .= "

                    <tr>

                        <td colspan='$colspan'></td>

                    </tr>	

                    <tr class='bg-ligth-gray'>

                        <td colspan='$colspan_sub'>

                            <b>Subtotal Profissional ($nome_profissional) : </b>

                        </td>

                        <td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_comissao_profissional, 2, ",", ".") . "</b></td>";

                if ($exibe) {

                    $dados .= "<td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_geral_profissional, 2, ",", ".") . "</b></td>";

                }

                $dados .= "</tr>";



                $Valor_comissao_profissional = 0;

                $Valor_geral_profissional = 0;

                $dados .= "

                    <tr class='bg-primary'>

                        <td colspan='$colspan'> <b>Profissional: {$array[$i]['X']['Profissional']} </b></td>

                    </tr>

                    <tr>

                        <td colspan='$colspan'></td>

                    </tr>	

                    <tr class='bg-info'>

                        <td colspan='$colspan'> <b>Paciente: {{$array[$i]['X']['Paciente']}}</b> </td>

                    </tr>";

            }

            $dados .= "

                <tr>

                    <td class='text-left'>{$array[$i]['X']['descricao']}</td>

                    <td class='text-left'>{$array[$i]['X']['descricao_evento']}</td>

                    <td class='text-left'>{$array[$i]['X']['Categoria_aula']}</td>

                    <td class='text-center'>{$array[$i][0]['Total_eventos']}</td>

                    <td class='text-center'>{$array[$i][0]['Efetivados']}</td>

                    <td class='text-center'>" . number_format($array[$i]['X']['Porcentagem'], 2, ",", ".") . "%</td>    

                    <td class='text-right'>" . number_format($array[$i]['X']['Valor_sessao'], 2, ",", ".") . "</td> 

                    <td class='text-right'>" . number_format($array[$i][0]['Comissao'], 2, ",", ".") . "</td>

                  "; 

            
            /* $dados .= "

                <tr>

                    <td class='text-left'>{$array[$i]['X']['descricao']}</td>

                    <td class='text-left'>{$array[$i]['X']['descricao_evento']}</td>

                    <td class='text-left'>{$array[$i]['X']['Categoria_aula']}</td>

                    <td class='text-center'>{$array[$i][0]['Total_eventos']}</td>

                    <td class='text-center'>{$array[$i][0]['Efetivados']}</td>

                    <td class='text-center'>" . number_format($array[$i]['X']['Porcentagem'], 2, ",", ".") . "%</td>    

                    <td class='text-right'>" . number_format($array[$i][0]['Valor_sessao'], 2, ",", ".") . "</td> 

                    <td class='text-right'>" . number_format($array[$i][0]['Comissao'], 2, ",", ".") . "</td>

                  "; */

            if ($exibe) {

                $dados .= "<td class='text-right'>" . number_format($array[$i][0]['Total_geral'], 2, ",", ".") . "</td>";

            }



            $dados .= "</tr>";



            $nome_profissional = $array[$i]['X']['Profissional'];

            $id_profissional = $array[$i]['X']['idprofissional'];



            $Valor_comissao_paciente += $array[$i][0]['Comissao'];

            $Valor_geral_paciente += $array[$i][0]['Total_geral'];



            $Valor_comissao_profissional += $array[$i][0]['Comissao'];

            $Valor_geral_profissional += $array[$i][0]['Total_geral'];



            $Valor_comissao_geral += $array[$i][0]['Comissao'];

            $Valor_geral_geral += $array[$i][0]['Total_geral'];



            $nome_paciente = $array[$i]['X']['Paciente'];

            $id_paciente = $array[$i]['X']['idpaciente'];

        }

        if ($size > 0) {

            $dados .= "

                <tr>

                    <td colspan='$colspan'></td>

                </tr>				

                <tr class='bg-ligth-gray'>

                    <td colspan='$colspan_sub'>

                        <b>Subtotal Paciente ($nome_paciente) : </b>

                    </td>

                    <td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_comissao_paciente, 2, ",", ".") . "</b></td>

               ";

            if ($exibe) {

                $dados .= "<td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_geral_paciente, 2, ",", ".") . "</b></td>";

            }



            $dados .= "</tr>";



            $dados .= "

                <tr>

                    <td colspan='$colspan'></td>

                </tr>	

                <tr class='bg-ligth-gray'>

                    <td colspan='$colspan_sub'>

                        <b>Subtotal Profissional ($nome_profissional) : </b>

                    </td>

                        <td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_comissao_profissional, 2, ",", ".") . "</b></td>";

            if ($exibe) {

                $dados .= "<td class='text-right' nowrap='nowrap'><b>R$ " . number_format($Valor_geral_profissional, 2, ",", ".") . "</b></td>";

            }



            $dados .= "</tr>";

        } else {

            $dados .= "

                <tr>

                    <td colspan='$colspan' class='text-center'>Não há registros</td>                    

                </tr>";

        }

        $this->response->body($dados);

    }



}

