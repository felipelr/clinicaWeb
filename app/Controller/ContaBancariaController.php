<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Banco', 'Model');
App::uses('Paciente', 'Model');
App::uses('ContaBancaria', 'Model');
/**
 * CakePHP ContaBancariaController
 * @author Felipe
 */
class ContaBancariaController extends AuthController {

    public function index() {
    }

    public function cadastrar() {
        if ($this->request->is('get')) {
            $banco = new Banco();
            $paciente = new Paciente();

            $pacientes = $paciente->retornarTodos();
            $bancos = $banco->retornarTodos();

            $this->set('Bancos', $bancos);
            $this->set("Pacientes", $pacientes);
        }
        if ($this->request->is('post')) {
            $this->layout = null;
            $this->autoRender = false;
            $dados = $this->request->data;
            if ($dados['ContaBancaria']['id_paciente'] == '-1') {
                $dados['ContaBancaria']['id_paciente'] = null;
            }
            $contaBancaria = new ContaBancaria();
            $contaBancaria->save($dados);
            $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "conta_bancaria", "action" => "index"));
        }
    }

    public function alterar($idcontabancaria) {
        $contaBancaria = new ContaBancaria();
        if ($this->request->is("get")) {
            if (isset($idcontabancaria)) {
                $contaBancariaDados = $contaBancaria->retornarPorId($idcontabancaria);
                $banco = new Banco();
                $paciente = new Paciente();

                $pacientes = $paciente->retornarTodos();
                $bancos = $banco->retornarTodos();

                $this->set('Bancos', $bancos);
                $this->set("Pacientes", $pacientes);
                $this->set("ContaBancaria", $contaBancariaDados);
            } else {
                return $this->redirect(array("controller" => "conta_bancaria", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $dados = $this->request->data;
            if ($dados['ContaBancaria']['id_paciente'] == '-1') {
                $dados['ContaBancaria']['id_paciente'] = null;
            }
            $contaBancaria = new ContaBancaria();
            $contaBancaria->save($dados);
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "conta_bancaria", "action" => "index"));
        }
    }

    public function excluir() {
        $idcontabancaria = $this->request->data['idcontabancaria'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idcontabancaria)) {
                $contaBancaria = new ContaBancaria();
                $contaBancaria->excluir($idcontabancaria);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "conta_bancaria", "action" => "index"));
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

        $content = new ContaBancaria();
        $contents = $content->listarJQuery($search, $start, $length, $ordenacao);
        
        $this->response->body(json_encode(
                array(
                    "draw" => $draw,
                    "recordsTotal" => (int) $content->totalRegistro(),
                    "recordsFiltered" => (int) $content->totalRegistroFiltrado($search),
                    "data" => $contents
                )
        ));
    }

    public function cadastrar_ajax() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $dados = $this->request->data;
        if ($dados['ContaBancaria']['id_paciente'] == '-1') {
            $dados['ContaBancaria']['id_paciente'] = null;
        }
        $contaBancaria = new ContaBancaria();
        $contaBancaria->save($dados);
        
        $contaBancaria_ = $contaBancaria->retornarPorIdComBanco($contaBancaria->id);
        $this->response->body(json_encode($contaBancaria_));
    }

}
