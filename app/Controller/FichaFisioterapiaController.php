<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('FichaFisioterapia', 'Model');
App::uses('Profissional', 'Model');
App::uses('Paciente', 'Model');
App::uses('AcompanhamentoFisioterapia', 'Model');
App::uses('Endereco', 'Model');

/**
 * CakePHP FichaFisioterapiaController
 * @author felipe
 */
class FichaFisioterapiaController extends AuthController {

    public $components = array('RequestHandler');

    public function prontuario($idpaciente) {
        $paciente = new Paciente();
        if ($this->request->is("get")) {
            if (isset($idpaciente)) {
                $paciente = $paciente->retornarPorId($idpaciente);
                if (isset($paciente["data_nascimento"])) {
                    $date = DateTime::createFromFormat('Y-m-d', $paciente["data_nascimento"]);
                    $paciente["data_nascimento"] = $date->format('d/m/Y');
                }
                $this->set("Paciente", $paciente);
            } else {
                return $this->redirect(array("controller" => "paciente", "action" => "index"));
            }
        }
    }

    public function ajax() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $columns = (isset($this->request->data["columns"])) ? $this->request->data["columns"] : null;
        $order = (isset($this->request->data["order"])) ? $this->request->data["order"] : null;
        $draw = (isset($this->request->data["draw"])) ? $this->request->data["draw"] : 0;
        $start = (isset($this->request->data["start"])) ? $this->request->data["start"] : 0;
        $length = (isset($this->request->data["length"])) ? $this->request->data["length"] : 0;
        $search = (isset($this->request->data["search"]["value"]) && !empty($this->request->data["search"]["value"])) ? $this->request->data["search"]["value"] : null;
        $idpaciente = (isset($this->request->query["idpaciente"])) ? $this->request->query["idpaciente"] : 0;

        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $content = new FichaFisioterapia();
        $contents = $content->listarJQuery($idpaciente, $search, $start, $length, $ordenacao);

        $dados = array();
        if (isset($contents)) {
            foreach ($contents as $_content) {
                $c = array();
                $c['f']['idfichafisioterapia'] = $_content['f']['idfichafisioterapia'];
                $c['f']['id_paciente'] = $_content['f']['id_paciente'];
                $c['f']['descricao'] = $_content['f']['descricao'];
                $c['pac']['nome'] = $_content['pac']['nome'];
                $c['pac']['sobrenome'] = $_content['pac']['sobrenome'];
                $c['pro']['nome'] = $_content['pro']['nome'];
                $c['pro']['sobrenome'] = $_content['pro']['sobrenome'];
                $dados[] = $c;
            }
        }

        $this->response->body(json_encode(
                        array(
                            "draw" => $draw,
                            "recordsTotal" => (int) $content->totalRegistro($idpaciente),
                            "recordsFiltered" => (int) $content->totalRegistroFiltrado($idpaciente, $search),
                            "data" => $dados
                        )
        ));
    }

    public function cadastrar($idpaciente) {
        $paciente = new Paciente();
        $profissional = new Profissional();
        $endereco = new Endereco();
        if ($this->request->is("get")) {
            if (isset($idpaciente)) {
                $paciente = $paciente->retornarPorId($idpaciente);
                $endereco = $endereco->retornarPorPaciente($idpaciente);
                $profissionais = $profissional->retornarTodos();
                if (isset($paciente["data_nascimento"])) {
                    $date = DateTime::createFromFormat('Y-m-d', $paciente["data_nascimento"]);
                    $paciente["data_nascimento"] = $date->format('d/m/Y');
                }
                $this->set("Paciente", $paciente);
                $this->set("Profissionais", $profissionais);
                $this->set("Endereco", $endereco);
            } else {
                return $this->redirect(array("controller" => "paciente", "action" => "index"));
            }
        } else if ($this->request->is("post")) {
            $dataFichaFisioterapia = $this->request->data['FichaFisioterapia'];

            $fichaFisioterapia = new FichaFisioterapia();
            $fichaFisioterapia->create();
            $fichaFisioterapia->save($dataFichaFisioterapia);
            return $this->redirect(array("controller" => "ficha_fisioterapia", "action" => "prontuario", $dataFichaFisioterapia['id_paciente']));
        }
    }

    public function alterar($id) {
        $fichaFisioterapia = new FichaFisioterapia();
        $profissional = new Profissional();
        if ($this->request->is("get")) {
            if (isset($id)) {
                $dataFicha = $fichaFisioterapia->retornarPorId($id);
                $profissionais = $profissional->retornarTodos();
                $this->set("FichaFisioterapia", $dataFicha);
                $this->set("Profissionais", $profissionais);
            } else {
                return $this->redirect(array("controller" => "paciente", "action" => "index"));
            }
        } else if ($this->request->is("post")) {
            $dataFichaFisioterapia = $this->request->data['FichaFisioterapia'];
            $fichaFisioterapia->save($dataFichaFisioterapia);
            return $this->redirect(array("controller" => "ficha_fisioterapia", "action" => "prontuario", $dataFichaFisioterapia['id_paciente']));
        }
    }

    public function excluir() {
        $idfichafisioterapia = $this->request->data['idfichafisioterapia'];
        $idpaciente = $this->request->data['idpaciente'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idfichafisioterapia)) {
                $fichaFisioterapia = new FichaFisioterapia();
                $fichaFisioterapia->excluir($idfichafisioterapia);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "ficha_fisioterapia", "action" => "prontuario", $idpaciente));
    }

    public function viewpdf($id) {
        $this->layout = 'default';
        if ($this->request->is("get")) {
            if (isset($id)) {
                ini_set('memory_limit', '512M');
                $fichaFisioterapia = new FichaFisioterapia();
                $profissional = new Profissional();
                $acompanhamentoFisioterapia = new AcompanhamentoFisioterapia();
                $paciente = new Paciente();

                $dataFicha = $fichaFisioterapia->retornarPorId($id);
                $dataProfissional = $profissional->retornarPorId($dataFicha['id_profissional']);
                $dataAcompanhamentos = $acompanhamentoFisioterapia->retornarPorIdFichaFisioterapia($id);
                $datapaciente = $paciente->retornarPorId($dataFicha['id_paciente']);

                $this->set("FichaFisioterapia", $dataFicha);
                $this->set("Profissional", $dataProfissional);
                $this->set("Acompanhamentos", $dataAcompanhamentos);
                $this->set("Paciente", $datapaciente);
            } else {
                return $this->redirect(array("controller" => "paciente", "action" => "index"));
            }
        }
    }

}
