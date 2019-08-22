<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Clinica', 'Model');

/**
 * CakePHP ClinicaController
 * @author felipe
 */
class ClinicaController extends AuthController {

    public function index() {
        $clinica = new Clinica();
        if ($this->request->is("get")) {
            $id = isset($this->request->query['id']) ? $this->request->query['id'] : null;
            if (isset($id)) {
                $dados = $clinica->retornarPorId($id);
                $this->set("Clinica", $dados);
            } else {
                return $this->redirect(array("controller" => "index", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;
            $clinica->save($data['Clinica']);

            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "index", "action" => "index"));
        }
    }

}
