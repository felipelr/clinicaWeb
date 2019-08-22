<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('User', 'Model');
App::uses('TipoUsuario', 'Model');
App::uses('Clinica', 'Model');

/**
 * CakePHP UsuarioController
 * @author Felipe
 */
class UsuarioController extends AuthController {

    public function index() {
        
    }

    public function cadastrar() {
        
        if ($this->request->is("get")) {
            $tipoUsuario = new TipoUsuario();
            $clinica = new Clinica();

            $tipos = $tipoUsuario->retornarTodos();
            $clinicas_ = $clinica->retornarTodos();

            $this->set("Tipos", $tipos);
            $this->set('Clinicas', $clinicas_);
        }
        if ($this->request->is("post")) {
            $user = new User();
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;

            if (!$user->validarEmail($data['User']['email'])) {
                $this->Session->setFlash(__("E-mail já cadastrado!"), 'erro');
                return $this->redirect(array("controller" => "usuario", "action" => "cadastrar"));
            }

            if ($data['User']['senha'] == $data['User']['confirmar_senha']) {
                unset($data['User']['confirmar_senha']);
                $user->save($data);
                $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
                return $this->redirect(array("controller" => "usuario", "action" => "index"));
            } else {
                $this->Session->setFlash(__("Senhas não são iguais!"), 'erro');
                return $this->redirect(array("controller" => "usuario", "action" => "cadastrar"));
            }
        }
    }

    public function alterar($idusuario) {
        $user = new User();
        if ($this->request->is("get")) {
            if (isset($idusuario)) {
                $user = $user->retornarPorId($idusuario);
                $tipoUsuario = new TipoUsuario();
                $clinica = new Clinica();

                $tipos = $tipoUsuario->retornarTodos();
                $clinicas_ = $clinica->retornarTodos();

                $this->set("Tipos", $tipos);
                $this->set("User", $user);
                $this->set('Clinicas', $clinicas_);
            } else {
                return $this->redirect(array("controller" => "usuario", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            if (!$user->validarEmail($data['User']['email'], $data['User']['idusuario'])) {
                $this->Session->setFlash(__("E-mail já cadastrado!"), 'erro');
                return $this->redirect(array("controller" => "usuario", "action" => "alterar", $data['User']['idusuario']));
            }
            $user->save($data);
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "usuario", "action" => "index"));
        }
    }

    public function excluir() {
        $idusuario = $this->request->data['idusuario'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idusuario)) {
                $usuario = new User();
                $usuario->excluir($idusuario);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "usuario", "action" => "index"));
    }

    public function perfil($idusuario) {
        $user = new User();
        if ($this->request->is("get")) {
            if (isset($idusuario)) {
                $user = $user->retornarPorId($idusuario);
                $tipoUsuario = new TipoUsuario();
                $tipos = $tipoUsuario->retornarTodos();
                $this->set("Tipos", $tipos);
                $this->set("User", $user);
            }
        }
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            if (!$user->validarEmail($data['User']['email'], $data['User']['idusuario'])) {
                $this->Session->setFlash(__("E-mail já cadastrado!"), 'erro');
                return $this->redirect(array("controller" => "usuario", "action" => "perfil", $data['User']['idusuario']));
            }
            $user->save($data);
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "usuario", "action" => "perfil", $data['User']['idusuario']));
        }
    }

    public function alterar_senha() {
        if ($this->request->is("post")) {
            $user = new User();
            $data = $this->request->data;
            $userOld = $user->retornarPorId($data['User']['idusuario']);
            if ($userOld['senha'] == Security::hash($data['User']['senha'])) {
                if ($data['User']['nova_senha'] == $data['User']['confirmar_nova_senha']) {
                    $data['User']['senha'] = $data['User']['nova_senha'];
                    unset($data['User']['nova_senha']);
                    unset($data['User']['confirmar_nova_senha']);
                    $user->save($data);
                    $this->Session->setFlash(__("Senha alterada com sucesso!"), 'sucesso');
                } else {
                    $this->Session->setFlash(__("Nova senha não é igual a confirmação!"), 'erro');
                }
            } else {
                $this->Session->setFlash(__("Senha atual incorreta!"), 'erro');
            }
            return $this->redirect(array("controller" => "usuario", "action" => "perfil", $data['User']['idusuario']));
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

        $content = new User();
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
