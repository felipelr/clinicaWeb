<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('FichaFisioterapia', 'Model');
App::uses('AcompanhamentoFisioterapia', 'Model');

/**
 * CakePHP AcompanhamentoFisioterapiaController
 * @author felipe
 */
class AcompanhamentoFisioterapiaController extends AuthController {

    public function registros($idfichafisioterapia) {
        $fichaFisioterapia = new FichaFisioterapia();
        if ($this->request->is("get")) {
            if (isset($idfichafisioterapia)) {
                $dataFicha = $fichaFisioterapia->retornarPorId($idfichafisioterapia);
                $this->set("FichaFisioterapia", $dataFicha);
            } else {
                return $this->redirect(array("controller" => "paciente", "action" => "index"));
            }
        } else if ($this->request->is("post")) {
            
        }
    }

    public function excluir() {
        $idacompanhamentofisioterapia = isset($this->request->data['idacompanhamentofisioterapia']) ? $this->request->data['idacompanhamentofisioterapia'] : null;
        $idfichafisioterapia = isset($this->request->data['idfichafisioterapia']) ? $this->request->data['idfichafisioterapia'] : null;
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idacompanhamentofisioterapia)) {
                $acompanhamentoFisioterapia = new AcompanhamentoFisioterapia();
                $acompanhamentoFisioterapia->excluir($idacompanhamentofisioterapia);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "acompanhamento_fisioterapia", "action" => "registros", $idfichafisioterapia));
    }

    public function retornar_detalhes() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idacompanhamentofisioterapia = isset($this->request->query['idacompanhamentofisioterapia']) ? $this->request->query['idacompanhamentofisioterapia'] : null;
        $result = array();
        if (isset($idacompanhamentofisioterapia)) {
            $acompanhamanetoFisioterapia = new AcompanhamentoFisioterapia();
            $dados = $acompanhamanetoFisioterapia->retornarDetalhado($idacompanhamentofisioterapia);
            if (isset($dados)) {
                $datetime1 = DateTime::createFromFormat('Y-m-d H:i:s', $dados['evento']['data_inicio']);
                $datetime2 = DateTime::createFromFormat('Y-m-d H:i:s', $dados['acompanhamento_fisioterapia']['created']);
                $dados['evento']['data_inicio'] = $datetime1->format('d/m/Y H:i');
                $dados['acompanhamento_fisioterapia']['created'] = $datetime2->format('d/m/Y');
                $result['status'] = true;
                $result['mensagem'] = 'Retornado com sucesso';
                $result['dados'] = $dados;
            } else {
                $result['status'] = false;
                $result['mensagem'] = 'Problemas ao retonar detalhes';
            }
        } else {
            $result['status'] = false;
            $result['mensagem'] = 'Problemas ao retonar detalhes';
        }
        $this->response->body(json_encode($result));
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
        $idfichafisioterapia = (isset($this->request->query["idfichafisioterapia"])) ? $this->request->query["idfichafisioterapia"] : 0;

        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $content = new AcompanhamentoFisioterapia();
        $contents = $content->listarJQuery($idfichafisioterapia, $search, $start, $length, $ordenacao);
        $dados = array();

        foreach ($contents as $value) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $value['a']['created']);
            $value['a']['created'] = $date->format('d/m/Y');
            $value['a']['descricao'] = strip_tags($value['a']['descricao'],'<br>');
            $dados[] = $value;
        }

        echo json_encode(
                array(
                    "draw" => $draw,
                    "recordsTotal" => (int) $content->totalRegistro($idfichafisioterapia),
                    "recordsFiltered" => (int) $content->totalRegistroFiltrado($idfichafisioterapia, $search),
                    "data" => $dados
                )
        );
    }

}
