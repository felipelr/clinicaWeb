<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('TipoUsuario', 'Model');
App::uses('Acesso', 'Model');

/**
 * CakePHP TipoUsuarioController
 * @author Felipe
 */
class TipoUsuarioController extends AuthController {

    public function index() {
    }

    public function cadastrar() {
        if ($this->request->is("get")) {
            
        }
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $tipo = new TipoUsuario();
            $tipo->save($data);
            $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "tipo_usuario", "action" => "index"));
        }
    }

    public function alterar($idtipousuario) {
        $tipo = new TipoUsuario();
        if ($this->request->is("get")) {
            if (isset($idtipousuario)) {
                $tipo_ = $tipo->retornarPorId($idtipousuario);
                $this->set("TipoUsuario", $tipo_);
            } else {
                return $this->redirect(array("controller" => "tipo_usuario", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;
            $tipo->save($data);
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "tipo_usuario", "action" => "index"));
        }
    }

    public function excluir() {
        $idtipousuario = $this->request->data['idtipousuario'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idtipousuario)) {
                $tipo = new TipoUsuario();
                $tipo->excluir($idtipousuario);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "tipo_usuario", "action" => "index"));
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

        $content = new TipoUsuario();
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

    public function acessos_usuario($idTipoUsuario) {
        $acesso = new Acesso();
        if ($this->request->is("get")) {
            $Acessos = $acesso->acessosUsuario($idTipoUsuario);
            $this->set("idTipoUsuario", $idTipoUsuario);
            $this->set("Acessos", $Acessos);
        }
        if ($this->request->is("post")) {
            $dados = isset($this->request->data['Acesso']) ? $this->request->data['Acesso'] : null;
            $idTipoUsuario = isset($this->request->query['idtipousuario']) ? $this->request->query['idtipousuario'] : null;
            if(isset($dados) && isset($idTipoUsuario)){
                $acesso->inserirUsuarioAcesso($dados, $idTipoUsuario, $this->_idDaSessao);
            }
            $this->Session->setFlash(__("Alteração realizada com sucesso"), 'sucesso');
            return $this->redirect(array("controller" => "tipo_usuario", "action" => "index"));
        }
    }

}
