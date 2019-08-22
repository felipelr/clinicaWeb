<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('PlanoContas', 'Model');
App::uses('Acesso', 'Model');

/**
 * CakePHP PlanoContasController
 * @author BersaN & StarK
 */
class PlanoContasController extends AuthController {

    public function index() {     
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

        $content = new PlanoContas();
        $contents = $content->listarJQuery($search, $start, $length, $ordenacao);
        
        echo json_encode(
                array(
                    "draw" => $draw,
                    "recordsTotal" => (int) $content->totalRegistro(),
                    "recordsFiltered" => (int) $content->totalRegistroFiltrado($search),
                    "data" => $contents
                )
        );
    }

    public function cadastrar() {        
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $dataPlano = $data["Plano"];
            $dataPlano["data_cadastro"] = date("Y-m-d H:i:s");
            $plano = new PlanoContas();
            $plano->save($dataPlano);

            $this->Session->setFlash(__("Cadastrado com sucesso."), 'sucesso');
            return $this->redirect(array("controller" => "plano_contas", "action" => "index"));
        }
    }

    public function alterar($idplanocontas) {   
        $plano_contas = new PlanoContas();

        if ($this->request->is("get")) {
            if (isset($idplanocontas)) {
                $plano_contas = $plano_contas->listarPorId($idplanocontas);
                $this->set("Plano", $plano_contas);
            } else {
                return $this->redirect(array("controller" => "plano_contas", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;
            $dataPlano = $data["Plano"];
            $dataPlano["data_atualizacao"] = date("Y-m-d H:i:s");
            $dataPlano["observacao"] = (trim($dataPlano["observacao"]) == "") ? null : $dataPlano["observacao"];
            $plano = new PlanoContas();
            $plano->save(($dataPlano));

            $this->Session->setFlash(__("Atualizado com sucesso."), 'sucesso');
            return $this->redirect(array("controller" => "plano_contas", "action" => "index"));
        }
    }

    public function excluir() {   
        $idplanocontas = $this->request->data['idplanocontas'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idplanocontas)) {
                $plano = new PlanoContas();
                $plano->idplanocontas = $idplanocontas;
                $plano->excluir($idplanocontas);

                $this->Session->setFlash(__("Excluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "plano_contas", "action" => "index"));
    }

    public function listarDespesas($idplanocontas) {
        $this->layout = null;
        $this->autoRender = false;
        $columns = (isset($this->request->query["columns"])) ? $this->request->query["columns"] : null;
        $order = (isset($this->request->query["order"])) ? $this->request->query["order"] : null;
        $draw = (isset($this->request->query["draw"])) ? $this->request->query["draw"] : 0;
        $start = (isset($this->request->query["start"])) ? $this->request->query["start"] : 0;
        $length = (isset($this->request->query["length"])) ? $this->request->query["length"] : 0;
        $search = (isset($this->request->query["search"]["value"]) && !empty($this->request->query["search"]["value"])) ? $this->request->query["search"]["value"] : null;
        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $content = new PlanoContas();
        $contents = $content->listarJQueryDespesas($idplanocontas, $search, $start, $length, $ordenacao);
        $dados = array();
        if (isset($contents)) {
            foreach ($contents as $_content) {
                $dados[] = $_content;
            }
        }
        echo json_encode(
                array(
                    "draw" => $draw,
                    "recordsTotal" => (int) $content->totalRegistro(),
                    "recordsFiltered" => (int) $content->totalRegistroFiltrado($search),
                    "data" => $dados
                )
        );
    }

}
