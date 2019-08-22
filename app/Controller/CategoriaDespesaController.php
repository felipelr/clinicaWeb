<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Despesa', 'Model');
App::uses('CategoriaDespesa', 'Model');
App::uses('CentroCustos', 'Model');
App::uses('Financeiro', 'Model');
App::uses('Favorecido', 'Model');
App::uses('Clinica', 'Model');
App::uses('TipoFinanceiro', 'Model');
App::uses('CaixaLoja', 'Model');
App::uses('Acesso', 'Model');

/**
 * CakePHP CategoriaDespesaController
 * @author felip
 */
class CategoriaDespesaController extends AuthController {

    public function index() {
        
    }

    public function cadastrar() {
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;

            $data = $this->request->data;
            $categoria_despesa = new CategoriaDespesa();

            $data['CategoriaDespesa']['id_usuario'] = $this->_idDaSessao;

            $array_lojas = $data['CategoriaDespesa']['id_clinica'];
            foreach ($array_lojas as $loja) {
                $data['CategoriaDespesa']['id_clinica'] = (int) $loja;
                $categoria_despesa->save($data);
            }

            $layout = isset($this->request->query['layout']) ? $this->request->query['layout'] : null;
            if (isset($layout)) {
                $this->response->body(json_encode($categoria_despesa->retornarPorId($categoria_despesa->id)));
            } else {
                $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
                return $this->redirect(array("controller" => "categoria_despesa", "action" => "index"));
            }
        }

        if ($this->request->is("get")) {
            $layout = isset($this->request->query['layout']) ? $this->request->query['layout'] : null;
            if (isset($layout)) {
                $this->layout = "ajax";
            }

            $centroCusto = new CentroCustos();
            $caixaLoja = new CaixaLoja();
            $tipoFinanceiro = new TipoFinanceiro();
            $clinica = new Clinica();
            $favorecido = new Favorecido();

            $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;

            $custos_ = $centroCusto->retornarTodosDespesas();
            $caixas_ = $caixaLoja->retornarTodos();
            $financeiros_ = $tipoFinanceiro->retornarTodos();
            $clinicas_ = $clinica->retornarTodos(" and c.idclinica = {$idClinica} ");
            $favorecidos_ = $favorecido->retornarTodos();

            $this->set('CentroCustos', $custos_);
            $this->set('Caixas', $caixas_);
            $this->set('TipoFinanceiros', $financeiros_);
            $this->set('Clinicas', $clinicas_);
            $this->set('Favorecidos', $favorecidos_);
        }
    }

    public function alterar($idcategoriadespesa) {
        $categoria_despesa = new CategoriaDespesa();

        if ($this->request->is("get")) {
            if (isset($idcategoriadespesa)) {
                $centroCusto = new CentroCustos();
                $caixaLoja = new CaixaLoja();
                $tipoFinanceiro = new TipoFinanceiro();
                $clinica = new Clinica();
                $favorecido = new Favorecido();

                $dados = $categoria_despesa->retornarPorId($idcategoriadespesa);

                $custos_ = $centroCusto->retornarTodosDespesas();
                $caixas_ = $caixaLoja->retornarTodos();
                $financeiros_ = $tipoFinanceiro->retornarTodos();
                $clinicas_ = $clinica->retornarTodos();
                $favorecidos_ = $favorecido->retornarTodos();

                $this->set('CentroCustos', $custos_);
                $this->set('Caixas', $caixas_);
                $this->set('TipoFinanceiros', $financeiros_);
                $this->set('Clinicas', $clinicas_);
                $this->set("CategoriaDespesa", $dados);
                $this->set('Favorecidos', $favorecidos_);
            } else {
                return $this->redirect(array("controller" => "categoria_despesa", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;

            $categoria_despesa->save($data);
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "categoria_despesa", "action" => "index"));
        }
    }

    public function excluir() {
        $idcategoriadespesa = $this->request->data['idcategoriadespesa'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idcategoriadespesa)) {
                $categoria_despesa = new CategoriaDespesa();
                $categoria_despesa->excluir($idcategoriadespesa);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "categoria_despesa", "action" => "index"));
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

        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $content = new CategoriaDespesa();
        $contents = $content->listarJQuery($search, $start, $length, $ordenacao, $idClinica);

        $dados = array();
        if (isset($contents)) {
            foreach ($contents as $_content) {
                //$_content['p']["data_nascimento"] = date('d/m/Y', strtotime($_content['p']['data_nascimento']));
                $dados[] = $_content;
            }
        }

        $this->response->body(json_encode(
                        array(
                            "draw" => $draw,
                            "recordsTotal" => (int) $content->totalRegistro($idClinica),
                            "recordsFiltered" => (int) $content->totalRegistroFiltrado($search, $idClinica),
                            "data" => $dados
                        )
        ));
    }

}
