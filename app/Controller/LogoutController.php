<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');

/**
 * CakePHP LogoutController
 * @author Felipe
 */
class LogoutController extends AppController {

    public function beforeFilter() {
        if ($this->_logado() === false) {
            $database = CakeSession::read("database");
            CakeLog::debug("logout_before " . $database);
            $paginaLogin = 'index';
            if ($database == 'database_1') {
                $paginaLogin = 'index';
            } elseif ($database == 'conexao_corpo_mente') {
                $paginaLogin = 'conexao_corpo_mente';
            } elseif ($database == 'pilates_mari_bia') {
                $paginaLogin = 'pilates_mari_bia';
            } elseif ($database == 'aqua_fisio') {
                $paginaLogin = 'aqua_fisio';
            }
            return $this->redirect(array('controller' => 'login', 'action' => "$paginaLogin"));
        }
    }

    public function index() {
        $database = CakeSession::read("database");
        CakeSession::delete("database");
        CakeLog::debug("logout_index " . $database);
        $paginaLogin = 'index';
        if ($database == 'database_1') {
            $paginaLogin = 'index';
        } elseif ($database == 'conexao_corpo_mente') {
            $paginaLogin = 'conexao_corpo_mente';
        } elseif ($database == 'pilates_mari_bia') {
            $paginaLogin = 'pilates_mari_bia';
        } elseif ($database == 'aqua_fisio') {
            $paginaLogin = 'aqua_fisio';
        }
        $this->_sair();
        return $this->redirect(array('controller' => 'login', 'action' => "$paginaLogin"));
    }

}