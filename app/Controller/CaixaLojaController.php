<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('CaixaLoja', 'Model');
App::uses('User', 'Model');
App::uses('Clinica', 'Model');
App::uses('Financeiro', 'Model');


/**
 * CakePHP CaixaLojaController
 * @author BersaN & StarK
 */
class CaixaLojaController extends AuthController {

    public function index() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_LISTAR_CAIXA);
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

        $content = new CaixaLoja();
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

    public function alterar($idcaixaloja) {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_ALTERAR_CAIXA);
        $caixa_loja = new CaixaLoja();
        $usuario = new User();
        $clinica = new Clinica();

        $usuarios = $usuario->retornarTodos();
        $clinicas_ = $clinica->retornarTodos();

        $this->set('Clinicas', $clinicas_);
        $this->set("Usuarios", $usuarios);

        if ($this->request->is("get")) {
            if (isset($idcaixaloja)) {
                $caixa_loja = $caixa_loja->listarPorId($idcaixaloja);
                $this->set("Caixa", $caixa_loja);
            } else {
                return $this->redirect(array("controller" => "caixa_loja", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;
            $dataCaixa = $data["Caixa"];
            $dataCaixa["data_atualizacao"] = date("Y-m-d H:i:s");
            $dataCaixa["id_clinica"] = (int) $dataCaixa['id_clinica'];
            $dataCaixa['id_usuario'] = (int) $dataCaixa['id_usuario'];

            $caixa = new CaixaLoja();
            $caixa->save(($dataCaixa));

            $this->Session->setFlash(__("Atualizado com sucesso."), 'sucesso');
            return $this->redirect(array("controller" => "caixa_loja", "action" => "index"));
        }
    }

    public function cadastrar() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_CADASTRO_CAIXA);
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $dataCaixa = $data["Caixa"];
            $dataCaixa["id_clinica"] = (int) $dataCaixa['id_clinica'];
            $dataCaixa['id_usuario'] = (int) $dataCaixa['id_usuario'];

            $caixa = new CaixaLoja();
            $caixa->save($dataCaixa);

            $this->Session->setFlash(__("Cadastrado com sucesso."), 'sucesso');
            return $this->redirect(array("controller" => "caixa_loja", "action" => "index"));
        }
        if ($this->request->is("get")) {
            $usuario = new User();
            $clinica = new Clinica();

            $clinicas_ = $clinica->retornarTodos();
            $usuarios = $usuario->retornarTodos();

            $this->set('Clinicas', $clinicas_);
            $this->set("Usuarios", $usuarios);
        }
    }

    public function excluir() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_EXCLUIR_CAIXA);
        $idcaixaloja = $this->request->data['idcaixaloja'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idcaixaloja)) {
                $caixa = new CaixaLoja();
                $caixa->idcaixaloja = $idcaixaloja;
                $caixa->excluir($idcaixaloja);

                $this->Session->setFlash(__("Excluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "caixa_loja", "action" => "index"));
    }

}
