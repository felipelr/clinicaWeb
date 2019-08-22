<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * CakePHP AcessoHelper
 * @author felipe
 */

App::uses('Acesso', 'Model');

class AcessoHelper extends AppHelper {

    public $helpers = array();

    public function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);
    }

    public function beforeRender($viewFile) {
        
    }

    public function afterRender($viewFile) {
        
    }

    public function beforeLayout($viewLayout) {
        
    }

    public function afterLayout($viewLayout) {
        
    }
    
    public function validarAcesso($idTipoUsuario, $idAcesso){
        $acesso = new Acesso();
        $isValid = $acesso->validarAcesso($idTipoUsuario, $idAcesso);
        return $isValid;
    }

}
