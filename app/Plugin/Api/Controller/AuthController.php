<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');

/**
 * CakePHP AuthController
 * @author felip
 */
class AuthController extends AppController {

    public function index() {
        if (CakeRequest::header('token_validador') != 'abc123') {
            echo json_encode(array("msg" => "Não Autorizado"));
            return;
        }

        if ($this->request->is("post")) {
            $jsonData = $this->request->input('json_decode');

            echo json_encode(
                    $jsonData
            );
        } else {
            echo json_encode(
                    'GET NÃO IMPLEMENTADO'
            );
        }
    }

}
