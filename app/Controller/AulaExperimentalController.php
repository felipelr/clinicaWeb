<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('AulaExperimental', 'Model');
App::uses('Paciente', 'Model');
App::uses('Endereco', 'Model');
App::uses('Evento', 'Model');

/**
 * CakePHP AulaExperimentalController
 * @author felip
 */
class AulaExperimentalController extends AuthController {

    public function index() {
        $aulaExperimental = new AulaExperimental();
        $totalRegistros = $aulaExperimental->totalRegistros();
        $this->set("totalRegistros", $totalRegistros);
    }

    public function ajax_retornar_todos() {
        $this->layout = null;
        $this->autoRender = false;
        $length = isset($this->request->data['length']) ? $this->request->data['length'] : 0;
        $start = isset($this->request->data['start']) ? $this->request->data['start'] : 0;

        $aulaExperimental = new AulaExperimental();
        $contents = $aulaExperimental->retornarTodosPaging($length, $start);
        $dados = array();
        if (isset($contents)) {
            foreach ($contents as $_content) {
                $_content['a']["data_nascimento"] = date('d/m/Y', strtotime($_content['a']['data_nascimento']));
                $dados[] = $_content;
            }
        }
        $this->response->body(json_encode($dados));
    }

    public function ajax_importar_pacientes() {
        $this->layout = null;
        $this->autoRender = false;
        $ids = isset($this->request->data['ids']) ? $this->request->data['ids'] : null;
        if (isset($ids)) {
            if (strlen($ids) > 0) {
                //$arrayIds = explode(",", $ids);
                $aulaExperimental = new AulaExperimental();
                $contents = $aulaExperimental->retornarPorListaIds($ids);
                if (isset($contents)) {
                    $paciente = new Paciente();
                    $endeco = new Endereco();
                    $evento = new Evento();
                    foreach ($contents as $c) {
                        $paciente->create();
                        $paciente->save(array(
                            "nome" => $c['a']['nome'],
                            "sobrenome" => $c['a']['sobrenome'],
                            "email" => $c['a']['email'],
                            "data_nascimento" => $c['a']['data_nascimento'],
                            "telefone_fixo" => $c['a']['telefone_fixo'],
                            "telefone_celular" => $c['a']['telefone_celular'],
                            "estado_civil" => $c['a']['estado_civil'],
                            "sexo" => $c['a']['sexo']
                        ));
                        $endeco->create();
                        $endeco->save(array("id_paciente" => $paciente->id));
                        $evento->changeAulaExperimentalToPaciente($c['a']['idaulaexperimental'], $paciente->id);
                    }
                    $aulaExperimental->excluirPorListaIds($ids);
                }
            }
        }
    }

}
