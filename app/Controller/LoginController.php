<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('Paciente', 'Model');
App::uses('CakeEmail', 'Network/Email');

/**
 * CakePHP LoginController
 * @author Felipe
 */
class LoginController extends AppController {

    public function esqueci_minha_senha() {
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is('post')) {
            $userModel = new User();
            $email = $this->request->data['User']['email'];
            $senha_nova = $userModel->gerarSenha(8, true, true);
            $user = $userModel->find('first', array('conditions' => array('User.email' => $email)));
            if (isset($user['User']['idusuario'])) {
                $userModel->id = $user['User']['idusuario'];

                $CakeEmail = new CakeEmail(array("template" => "esqueci_senha"));
                $CakeEmail->config('default');
                $CakeEmail->helpers(array('Html'));
                $CakeEmail->viewVars(array('senha' => $senha_nova, 'nome' => $user['User']['nome'] . ' ' . $user['User']['sobrenome']));
                $CakeEmail->emailFormat('html');
                $CakeEmail->subject("no-reply: Recuperar senha Conexao Viva+");
                $CakeEmail->from("contato@conexaovivamais.com", "Conexao Viva+");
                $CakeEmail->to($email, $user['User']['nome'] . ' ' . $user['User']['sobrenome']);
                try {
                    if ($CakeEmail->send()) {
                        $userModel->save(array("senha" => $senha_nova));
                        $this->Session->setFlash(__('Uma nova senha foi enviada para seu e-mail!'), "sucesso");
                    } else {
                        $this->Session->setFlash(__('Erro ao enviar e-mail!'), "erro");
                    }
                } catch (Exception $exc) {
                    CakeLog::debug($exc->getMessage());
                    $this->Session->setFlash(__('Erro ao enviar e-mail!'), "erro");
                }
            } else {
                $this->Session->setFlash(__('E-mail inválido!'), "erro");
            }
            return $this->redirect(array('controller' => 'login', 'action' => 'index'));
        }
    }

    public function enviar_email_diario_conexao() {
        CakeSession::write("database", $this->request->data["nome_data_base"]);
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is('get')) {
            $pacientes = new Paciente();
            $array = $pacientes->lembrar_paciente();
            $size = count($array);
            if (isset($array)) {
                for ($i = 0; $i < $size; $i++) {
                    $CakeEmail = new CakeEmail(array("template" => "lembrete_eventos"));
                    $CakeEmail->config('default');
                    $CakeEmail->helpers(array('Html'));
                    $CakeEmail->viewVars(array('paciente' => $array[$i]['p']['nome'], 'Aula' => $array[$i]['c']['Aula'], 'dia' => $array[$i][0]['Dia'], 'hora' => $array[$i][0]['Horario'], 'profissional' => $array[$i][0]['Profissional'], 'fantasia' => $array[$i]['cli']['fantasia']));
                    $CakeEmail->emailFormat('html');
                    $CakeEmail->subject("no-reply: Olá " . $array[$i]['p']['nome'] . ", Aviso Importante!...");
                    $CakeEmail->from("contato@conexaovivamais.com", "Conexao Viva+");
                    $CakeEmail->to($array[$i]['p']['email']);
                    try {
                        if ($CakeEmail->send()) {
                            $this->Session->setFlash(__('Uma nova senha foi enviada para seu e-mail!'), "sucesso");
                        } else {
                            $this->Session->setFlash(__('Erro ao enviar e-mail!'), "erro");
                        }
                    } catch (Exception $exc) {
                        CakeLog::debug($exc->getMessage());
                        $this->Session->setFlash(__('Erro ao enviar e-mail!'), "erro");
                    }
                }
            }
        }
    }

    public function enviar_email_diario() {
        CakeSession::write("database", $this->request->data["nome_data_base"]);
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is('get')) {
            $pacientes = new Paciente();
            $array = $pacientes->lembrar_paciente();
            $size = count($array);
            if (isset($array)) {
                for ($i = 0; $i < $size; $i++) {
                    $CakeEmail = new CakeEmail(array("template" => "lembrete_eventos"));
                    $CakeEmail->config('default');
                    $CakeEmail->helpers(array('Html'));
                    $CakeEmail->viewVars(array('paciente' => $array[$i]['p']['nome'], 'Aula' => $array[$i]['c']['Aula'], 'dia' => $array[$i][0]['Dia'], 'hora' => $array[$i][0]['Horario'], 'profissional' => $array[$i][0]['Profissional'], 'fantasia' => $array[$i]['cli']['fantasia']));
                    $CakeEmail->emailFormat('html');
                    $CakeEmail->subject("no-reply: Olá " . $array[$i]['p']['nome'] . ", Aviso Importante!...");
                    $CakeEmail->from("contato@conexaovivamais.com", "Conexao Viva+");
                    $CakeEmail->to($array[$i]['p']['email']);
                    try {
                        if ($CakeEmail->send()) {
                            $this->Session->setFlash(__('Uma nova senha foi enviada para seu e-mail!'), "sucesso");
                        } else {
                            $this->Session->setFlash(__('Erro ao enviar e-mail!'), "erro");
                        }
                    } catch (Exception $exc) {
                        CakeLog::debug($exc->getMessage());
                        $this->Session->setFlash(__('Erro ao enviar e-mail!'), "erro");
                    }
                }
            }
        }
    }

    public function index() {
        CakeSession::write("database", 'database_1');
        $this->layout = 'default_login';
        $this->set('title', 'Login');
        if ($this->request->is('post')) {
            $rememberMe = ($this->request->data['User']['rememberMe'] === 1) ? true : false;
            if (parent::_logar($this->request->data['User']['email'], Security::hash($this->request->data['User']['senha']), $rememberMe)) {
                return $this->redirect(array('controller' => 'index', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('E-mail ou senha incorretos!!'), 'erro');
            }
        }
    }

    public function conexao_corpo_mente() {
        CakeSession::write("database", 'conexao_corpo_mente');
        $this->layout = 'default_login';
        $this->set('title', 'Login');
        if ($this->request->is('post')) {
            $rememberMe = ($this->request->data['User']['rememberMe'] === 1) ? true : false;
            if (parent::_logar($this->request->data['User']['email'], Security::hash($this->request->data['User']['senha']), $rememberMe)) {
                return $this->redirect(array('controller' => 'index', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('E-mail ou senha incorretos!!'), 'erro');
            }
        }
    }

    public function pilates_mari_bia() {
        CakeSession::write("database", 'pilates_mari_bia');
        $this->layout = 'default_login';
        $this->set('title', 'Login');
        if ($this->request->is('post')) {
            $rememberMe = ($this->request->data['User']['rememberMe'] === 1) ? true : false;
            if (parent::_logar($this->request->data['User']['email'], Security::hash($this->request->data['User']['senha']), $rememberMe)) {
                return $this->redirect(array('controller' => 'index', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('E-mail ou senha incorretos!!'), 'erro');
            }
        }
    }

    public function aqua_fisio() {
        CakeSession::write("database", 'aqua_fisio');
        $this->layout = 'default_login';
        $this->set('title', 'Login');
        if ($this->request->is('post')) {
            $rememberMe = ($this->request->data['User']['rememberMe'] === 1) ? true : false;
            if (parent::_logar($this->request->data['User']['email'], Security::hash($this->request->data['User']['senha']), $rememberMe)) {
                return $this->redirect(array('controller' => 'index', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('E-mail ou senha incorretos!!'), 'erro');
            }
        }
    }
}
