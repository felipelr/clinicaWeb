<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('CentroCustos', 'Model');
App::uses('PlanoContas', 'Model');

/**
 * CakePHP CentroCustoController
 * @author BersaN & StarK
 */
class CentroCustosController extends AuthController {

    public function index($id_plano_contas) {      
        if (isset($id_plano_contas)) {
            $Plano = new PlanoContas();
            $plano_contas = $Plano->listarPorId($id_plano_contas);
            $this->set("Plano", $plano_contas);
        } else {
            return $this->redirect(array("controller" => "plano_contas", "action" => "index"));
        }
    }

    public function ajax($id_plano_contas) {
        if (isset($id_plano_contas)) {
            $this->layout = null;
            $this->autoRender = false;
            $columns = (isset($this->request->data["columns"])) ? $this->request->data["columns"] : null;
            $order = (isset($this->request->data["order"])) ? $this->request->data["order"] : null;
            $draw = (isset($this->request->data["draw"])) ? $this->request->data["draw"] : 0;
            $start = (isset($this->request->data["start"])) ? $this->request->data["start"] : 0;
            $length = (isset($this->request->data["length"])) ? $this->request->data["length"] : 0;
            $search = (isset($this->request->data["search"]["value"]) && !empty($this->request->data["search"]["value"])) ? $this->request->data["search"]["value"] : null;
            $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

            $content = new CentroCustos();
            $contents = $content->listarJQuery($id_plano_contas, $search, $start, $length, $ordenacao);
            $dados = array();
            if (isset($contents)) {
                foreach ($contents as $_content) {
                    //$_content['p']["data_nascimento"] = date('d/m/Y', strtotime($_content['p']['data_nascimento']));
                    $_content['c']['valor_planejado'] = 'R$ ' . number_format($_content['c']['valor_planejado'], 2, ",", ".");
                    $dados[] = $_content;
                }
            }
            echo json_encode(
                    array(
                        "draw" => $draw,
                        "recordsTotal" => (int) $content->totalRegistro(),
                        "recordsFiltered" => (int) $content->totalRegistroFiltrado($id_plano_contas, $search),
                        "data" => $dados
                    )
            );
        } else {
            return $this->redirect(array("controller" => "plano_contas", "action" => "index"));
        }
    }

    public function cadastrar() {        
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $dataCentro = $data["Centro"];
            $dataCentro['id_plano_contas'] = (int) $dataCentro['id_plano_contas'];
            $dataCentro['ativo'] = $dataCentro['ativo'];
            $dataCentro["observacao"] = (trim($dataCentro["observacao"]) == "") ? null : $dataCentro["observacao"];
            if ($dataCentro['valor_planejado'] == null) {
                $dataCentro['valor_planejado'] = 0;
            } else {
                $str_explode = explode(',', $dataCentro['valor_planejado']);
                $dataCentro['valor_planejado'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];
            }
            
            $custo = new CentroCustos();
            $custo->save($dataCentro);

            $this->Session->setFlash(__("Cadastrado com sucesso."), 'sucesso');
            return $this->redirect(array("controller" => "centro_custos", "action" => "index", $dataCentro['id_plano_contas'] ));
        }
        if ($this->request->is("get")) {
            $plano = new PlanoContas();
            $planos = $plano->retornarTodos();
            $this->set("Planos", $planos);
        }
    }

    public function alterar($iddespesacusto) {             
        $centro_custo = new CentroCustos();
        $plano = new PlanoContas();
        $planos = $plano->retornarTodos();
        if ($this->request->is("get")) {
            if (isset($iddespesacusto)) {
                $centro_custo = $centro_custo->listarPorId($iddespesacusto);
                $plano = $plano->listarPorId(($centro_custo["id_plano_contas"]));
                $this->set("Centro", $centro_custo);
                $this->set("Planos", $planos);
            } else {
                return $this->redirect(array("controller" => "plano_contas", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;

            $dataCentro = $data["Centro"];
            $dataCentro['id_plano_contas'] = (int) $dataCentro['id_plano_contas'];
            $dataCentro['ativo'] = $dataCentro['ativo'];
            $dataCentro["observacao"] = (trim($dataCentro["observacao"]) == "") ? null : $dataCentro["observacao"];
            if ($dataCentro['valor_planejado'] == null) {
                $dataCentro['valor_planejado'] = 0;
            } else {
                $str_explode = explode(',', $dataCentro['valor_planejado']);
                $dataCentro['valor_planejado'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];                
            }
            $custo = new CentroCustos();
            $custo->save($dataCentro);

            $this->Session->setFlash(__("Atualizado com sucesso."), 'sucesso');
            return $this->redirect(array("controller" => "centro_custos", "action" => "index", $dataCentro['id_plano_contas']));
        }
    }

    public function excluir() {       
        $iddespesacusto = $this->request->data['iddespesacusto'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($iddespesacusto)) {
                $centro = new CentroCustos();
                $centro->iddespesacusto = $iddespesacusto;
                $centro->excluir($iddespesacusto);

                $this->Session->setFlash(__("Excluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "centro_custos", "action" => "index", $this->request->data['id_plano_contas'] ));
    }

}
