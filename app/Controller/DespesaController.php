<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Despesa', 'Model');
App::uses('CentroCustos', 'Model');
App::uses('Financeiro', 'Model');
App::uses('Favorecido', 'Model');
App::uses('Clinica', 'Model');
App::uses('TipoFinanceiro', 'Model');
App::uses('CaixaLoja', 'Model');
App::uses('Acesso', 'Model');
App::uses('CategoriaDespesa', 'Model');

/**
 * CakePHP DespesaController
 * @author BersaN & StarK
 */
class DespesaController extends AuthController {

    //public $components = array('DebugKit.Toolbar');

    public function index() {
        
    }

    public function cadastrar() {
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;

            $data = $this->request->data;
            $despesa = new Despesa();

            if ($despesa->cadastrar($data, $this->_idDaSessao)) {
                $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
                return $this->redirect(array("controller" => "despesa", "action" => "index"));
            } else {
                $this->Session->setFlash(__("Não foi possivel completar o cadastro"), 'erro');
                return $this->redirect(array("controller" => "despesa", "action" => "index"));
            }
        }
        if ($this->request->is("get")) {
            $centroCusto = new CentroCustos();
            $caixaLoja = new CaixaLoja();
            $tipoFinanceiro = new TipoFinanceiro();
            $clinica = new Clinica();
            $favorecido = new Favorecido();

            $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;

            $custos_ = $centroCusto->retornarTodosDespesas();
            $caixas_ = $caixaLoja->retornarTodos();
            $financeiros_ = $tipoFinanceiro->retornarTodos();
            $clinicas_ = $clinica->retornarTodos(" and c.idclinica = {$idClinica} ");
            $favorecidos_ = $favorecido->retornarTodos();

            $this->set('CentroCustos', $custos_);
            $this->set('Caixas', $caixas_);
            $this->set('TipoFinanceiros', $financeiros_);
            $this->set('Clinicas', $clinicas_);
            $this->set('Favorecidos', $favorecidos_);
        }
    }

    public function alterar($iddespesa) {
        $despesa = new Despesa();
        if ($this->request->is("get")) {
            if (isset($iddespesa)) {
                $centroCusto = new CentroCustos();
                $caixaLoja = new CaixaLoja();
                $tipoFinanceiro = new TipoFinanceiro();
                $clinica = new Clinica();
                $favorecido = new Favorecido();

                $despesa_ = $despesa->retornarPorId($iddespesa);
                $despesa_['valor'] = number_format($despesa_['valor'], 2, ",", ".");

                $custos_ = $centroCusto->retornarTodosDespesas();
                $caixas_ = $caixaLoja->retornarTodos();
                $financeiros_ = $tipoFinanceiro->retornarTodos();
                $clinicas_ = $clinica->retornarTodos();
                $favorecidos_ = $favorecido->retornarTodos();

                $this->set('CentroCustos', $custos_);
                $this->set('Caixas', $caixas_);
                $this->set('TipoFinanceiros', $financeiros_);
                $this->set('Clinicas', $clinicas_);
                $this->set("Despesa", $despesa_);
                $this->set('Favorecidos', $favorecidos_);
            } else {
                return $this->redirect(array("controller" => "despesa", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;

            if (isset($data['Despesa']['valor'])) {
                $str_explode = explode(',', $data['Despesa']['valor']);
                $data['Despesa']['valor'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];
            }

            $despesa->save($data);
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "despesa", "action" => "index"));
        }
    }

    public function excluir() {
        $iddespesa = $this->request->data['iddespesa'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($iddespesa)) {
                $despesa = new Despesa();
                $despesa->excluir($iddespesa, $this->_idDaSessao);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "despesa", "action" => "index"));
    }

    public function simplificado() {
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;

            $data = $this->request->data;

            if (isset($data['Despesa']['id_categoria_despesa'])) {
                $categoria_despesa = new CategoriaDespesa();
                $dadosCategoriaDespesa = $categoria_despesa->retornarPorId($data['Despesa']['id_categoria_despesa']);

                unset($data['Despesa']['id_categoria_despesa']);

                $data['Despesa']['id_favorecido'] = $dadosCategoriaDespesa['id_favorecido'];
                $data['Despesa']['valor_referente'] = $dadosCategoriaDespesa['valor_referente'];
                $data['repetir'] = $dadosCategoriaDespesa['repetir'];
                $data['tipo_intervalo'] = $dadosCategoriaDespesa['tipo_intervalo'] == 'ANO' ? 'year' : $dadosCategoriaDespesa['tipo_intervalo'] == 'MES' ? 'month' : 'day';
                $data['DespesaRepeticao'] = $dadosCategoriaDespesa['quantidade_repetir'];
                $data['Despesa']['prazo'] = $dadosCategoriaDespesa['prazo_repetir'];
                $data['Despesa']['id_caixa_loja'] = $dadosCategoriaDespesa['id_caixa_loja'];
                $data['Despesa']['id_financeiro_tipo'] = $dadosCategoriaDespesa['id_financeiro_tipo'];
                $data['Despesa']['quantidade_parcela'] = $dadosCategoriaDespesa['quantidade_parcela'];
                $data['Despesa']['id_despesa_custo'] = $dadosCategoriaDespesa['id_despesa_custo'];
                $data['Despesa']['observacao'] = $dadosCategoriaDespesa['observacao'];
                $data['Despesa']['id_clinica'] = array(0 => $dadosCategoriaDespesa['id_clinica']);

                $despesa = new Despesa();

                if ($despesa->cadastrar($data, $this->_idDaSessao)) {
                    $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
                    return $this->redirect(array("controller" => "despesa", "action" => "index"));
                } else {
                    $this->Session->setFlash(__("Não foi possivel completar o cadastro"), 'erro');
                    return $this->redirect(array("controller" => "despesa", "action" => "index"));
                }
            } else {
                return $this->redirect(array("controller" => "despesa", "action" => "index"));
            }
        }

        if ($this->request->is("get")) {
            $categoria_despesa = new CategoriaDespesa();

            $array_categoria_despesa = $categoria_despesa->retornarTodos();

            $this->set('ArrayCategoriaDespesa', $array_categoria_despesa);
        }
    }

    public function ajax() {
        $this->layout = null;
        $this->autoRender = false;
        $columns = (isset($this->request->data["columns"])) ? $this->request->data["columns"] : null;
        $order = (isset($this->request->data["order"])) ? $this->request->data["order"] : null;
        $draw = (isset($this->request->data["draw"])) ? $this->request->data["draw"] : 0;
        $start = (isset($this->request->data["start"])) ? $this->request->data["start"] : 0;
        $length = (isset($this->request->data["length"])) ? $this->request->data["length"] : 0;
        $search = (isset($this->request->data["search"]["value"]) && !empty($this->request->data["search"]["value"])) ? $this->request->data["search"]["value"] : null;
        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $content = new Despesa();
        $contents = $content->listarJQuery($search, $start, $length, $ordenacao, $idClinica);

        $dados = array();
        if (isset($contents)) {
            foreach ($contents as $_content) {
                //$_content['p']["data_nascimento"] = date('d/m/Y', strtotime($_content['p']['data_nascimento']));
                $_content['d']['valor'] = 'R$ ' . number_format($_content['d']['valor'], 2, ",", ".");
                $_content['d']['parcelas_pagas'] = $_content[0]['parcelas_pagas'];
                $_content['d']['data_vencimento'] = date('d/m/Y', strtotime($_content['x']['data_vencimento']));
                $dados[] = $_content;
            }
        }

        $this->response->body(json_encode(
                        array(
                            "draw" => $draw,
                            "recordsTotal" => (int) $content->totalRegistro($idClinica),
                            "recordsFiltered" => (int) $content->totalRegistroFiltrado($search, $idClinica),
                            "data" => $dados
                        )
        ));
    }

    public function gestao($iddespesa) {
        if (isset($iddespesa)) {
            $caixaLoja = new CaixaLoja();
            $tipoFinanceiro = new TipoFinanceiro();

            $caixas_ = $caixaLoja->retornarTodos();
            $tipoFinanceiros_ = $tipoFinanceiro->retornarTodosPagaveis();

            $this->set("iddespesa", $iddespesa);
            $this->set('Caixas', $caixas_);
            $this->set('TipoFinanceiros', $tipoFinanceiros_);
        } else {
            return $this->redirect(array("controller" => "despesa", "action" => "index"));
        }
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

    public function alterar_parcela() {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;
            //executar alterar
            $str_explode = explode(',', $data['valor']);
            $data['valor'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];

            $str_explode_data = explode('/', $data['data_vencimento']);
            $data['data_vencimento'] = $str_explode_data[2] . '-' . $str_explode_data[1] . '-' . $str_explode_data[0];

            $financeiro = new Financeiro();
            $financeiro->alterarParcela($data['iddespesa'], $data['idfinanceiro'], $data['valor'], $data['data_vencimento'], $data['motivo'], $this->_idDaSessao);
        }
    }

    public function excluir_parcela() {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;
            //executar excluir
            $financeiro = new Financeiro();
            $financeiro->excluirParcela($data['iddespesa'], $data['idfinanceiro'], $data['motivo'], $this->_idDaSessao);
        }
    }

    public function ajax_gestao() {
        $this->layout = null;
        $this->autoRender = false;
        $iddespesa = (isset($this->request->query["iddespesa"])) ? $this->request->query["iddespesa"] : null;
        if (isset($iddespesa)) {
            $financeiro = new Financeiro();
            $financeiros = $financeiro->listarGestaoJQuery($iddespesa);

            $dados = array();
            if (isset($financeiros)) {
                foreach ($financeiros as $_content) {
                    $_content['financeiro']['valor'] = 'R$ ' . number_format($_content['financeiro']['valor'], 2, ",", ".");
                    $_content['financeiro']['data_vencimento'] = date('d/m/Y', strtotime($_content['financeiro']['data_vencimento']));
                    $dados[] = $_content;
                }
            }
            $this->response->body(json_encode(array("dados" => $dados)));
        }
    }

    public function ajax_gestao_totais() {
        $this->layout = null;
        $this->autoRender = false;
        $iddespesa = (isset($this->request->query["iddespesa"])) ? $this->request->query["iddespesa"] : null;
        if (isset($iddespesa)) {
            $financeiro = new Financeiro();
            $totalGeral = $financeiro->totalFinanceiroPorDespesa($iddespesa);
            $totalPago = $financeiro->totalFinanceiroPagoPorDespesa($iddespesa);
            $totalNaoPago = $financeiro->totalFinanceiroNaoPagoPorDespesa($iddespesa);
            $dados = array();
            $dados['total_geral'] = isset($totalGeral[0][0]['total']) ? 'R$ ' . number_format($totalGeral[0][0]['total'], 2, ",", ".") : 'R$ 0,00';
            $dados['total_pago'] = isset($totalPago[0][0]['total']) ? 'R$ ' . number_format($totalPago[0][0]['total'], 2, ",", ".") : 'R$ 0,00';
            $dados['total_nao_pago'] = isset($totalNaoPago[0][0]['total']) ? 'R$ ' . number_format($totalNaoPago[0][0]['total'], 2, ",", ".") : 'R$ 0,00';
            $this->response->body(json_encode(array("dados" => $dados)));
        }
    }

    public function ajax_parcela_detalhes() {
        $this->layout = null;
        $this->autoRender = false;
        $idfinanceiro = (isset($this->request->query["idfinanceiro"])) ? $this->request->query["idfinanceiro"] : null;
        if (isset($idfinanceiro)) {
            $financeiro = new Financeiro();
            $dados = $financeiro->retornarDetalhes($idfinanceiro);
            $financeiro_ = $dados[0];

//            ob_start();
//            var_dump($idfinanceiro);
//            $result = ob_get_clean();
//            CakeLog::debug('teste $data = ' . $result);

            $financeiro_['financeiro']['valor'] = number_format($financeiro_['financeiro']['valor'], 2, ",", ".");
            $financeiro_['financeiro']['data_vencimento'] = date('d/m/Y', strtotime($financeiro_['financeiro']['data_vencimento']));
            $financeiro_['financeiro']['data_pagamento'] = date('d/m/Y', strtotime($financeiro_['financeiro']['data_pagamento']));
            $financeiro_['usuario']['nome'] = isset($financeiro_['usuario']['nome']) ? $financeiro_['usuario']['nome'] : "";
            $financeiro_['usuario']['sobrenome'] = isset($financeiro_['usuario']['sobrenome']) ? $financeiro_['usuario']['sobrenome'] : "";
            $this->response->body(json_encode($financeiro_));
        }
    }

    public function inserir_parcela() {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;

            $str_explode = explode(',', $data['valor']);
            $data['valor'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];

            $str_explode_data = explode('/', $data['data_vencimento']);
            $data['data_vencimento'] = $str_explode_data[2] . '-' . $str_explode_data[1] . '-' . $str_explode_data[0];

            $despesa = new Despesa();
            $despesaArray = $despesa->retornarPorId($data['iddespesa']);

            $financeiro = new Financeiro();
            $financeiro->create();
            $dados['id_despesa'] = $despesaArray['iddespesa'];
            $dados['valor'] = $data['valor'];
            $dados['data_vencimento'] = $data['data_vencimento'];
            $dados['id_financeiro_tipo'] = $despesaArray['id_financeiro_tipo'];
            $dados['total_parcela'] = $despesaArray['quantidade_parcela'] + 1;
            $dados['parcela'] = $despesaArray['quantidade_parcela'] + 1;
            $dados['pago'] = 0;
            $dados['convertido'] = 0;
            $dados['id_caixa_loja'] = $despesaArray['id_caixa_loja'];
            $financeiro->save($dados);

            $despesa->atualizarDespesa($despesaArray['iddespesa']);
        }
    }

}
