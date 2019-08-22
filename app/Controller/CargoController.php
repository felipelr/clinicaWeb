<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Cargo', 'Model');

/**
 * CakePHP CargoController
 * @author Felipe
 */
class CargoController extends AuthController {

    public function index() {
    }
    
    public function cadastrar() {
        if ($this->request->is("get")) {
        }
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $cargo = new Cargo();
            $cargo->save($data);
            $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "cargo", "action" => "index"));
        }
    }
    
    public function alterar($idcargo) {
        
        $cargo = new Cargo();
        if ($this->request->is("get")) {
            if (isset($idcargo)) {
                $cargo = $cargo->retornarPorId($idcargo);
                $this->set("Cargo", $cargo);
            } else {
                return $this->redirect(array("controller" => "cargo", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;            
            $cargo->save($data);            
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "cargo", "action" => "index"));
        }
    }
    
    public function excluir() {
        
        $idcargo = $this->request->data['idcargo'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idcargo)) {
                $cargo = new Cargo();
                $cargo->excluir($idcargo);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "cargo", "action" => "index"));
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

        $content = new Cargo();
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

}
