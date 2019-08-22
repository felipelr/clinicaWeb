<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Caixa', 'Model');
App::uses('CaixaAberturaFechamento', 'Model');
App::uses('CaixaUsuario', 'Model');

/**
 * CakePHP CaixaController
 * @author felip
 */
class CaixaController extends AuthController {

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

        $content = new Caixa();
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

    public function cadastrar() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_CADASTRO_CAIXA);
        if ($this->request->is("get")) {
            $clinica = new Clinica();
            $clinicas_ = $clinica->retornarTodos();
            $this->set('Clinicas', $clinicas_);
        } else if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $data["Caixa"]["data_cadastro"] = date('Y-m-d H:i:s');
            $data["Caixa"]["data_atualizacao"] = date('Y-m-d H:i:s');
            $caixa = new Caixa();
            $caixa->save($data);
            $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "caixa", "action" => "index"));
        }
    }

    public function alterar($idcaixa) {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_ALTERAR_CAIXA);
        $caixa = new Caixa();
        if ($this->request->is("get")) {
            if (isset($idcaixa)) {
                $clinica = new Clinica();

                $dataCaixa = $caixa->retornarPorId($idcaixa);
                $clinicas_ = $clinica->retornarTodos();

                $this->set("Caixa", $dataCaixa);
                $this->set('Clinicas', $clinicas_);
            } else {
                return $this->redirect(array("controller" => "caixa", "action" => "index"));
            }
        } else if ($this->request->is("post")) {
            $data = $this->request->data;
            $data["Caixa"]["data_atualizacao"] = date('Y-m-d H:i:s');
            $caixa->save($data);
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "caixa", "action" => "index"));
        }
    }

    public function excluir() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_EXCLUIR_CAIXA);
        $idcaixa = $this->request->data['idcaixa'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idcaixa)) {
                $caixa = new Caixa();
                $caixa->id = $idcaixa;
                $caixa->save(array("ativo" => 0, "data_atualizacao" => date('Y-m-d H:i:s')));
                $this->Session->setFlash(__("Excluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "caixa", "action" => "index"));
    }

    public function liberar_acesso($idcaixa) {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_ALTERAR_CAIXA);

        if ($this->request->is("get")) {
            if (isset($idcaixa)) {
                $caixa = new Caixa();
                $caixaUsuario = new CaixaUsuario();

                $acessosUsuarios = $caixaUsuario->retornarTodosPorCaixa($idcaixa);
                $dataCaixa = $caixa->retornarPorId($idcaixa);

                if ($acessosUsuarios == null)
                    $acessosUsuarios = array();

                $this->set("Caixa", $dataCaixa);
                $this->set("AcessosUsuarios", $acessosUsuarios);
            } else {
                return $this->redirect(array("controller" => "caixa", "action" => "index"));
            }
        } else if ($this->request->is("post")) {
            $caixaUsuario = new CaixaUsuario();
            $data = $this->request->data;

            $idCaixa = $data["Caixa"]["idcaixa"];
            $Usuarios = $data["Usuarios"];

            $count = count($Usuarios);
            for ($i = 0; $i < $count; $i++) {
                if ($Usuarios[$i]["idcaixausuario"] == 0 && isset($Usuarios[$i]["liberado"])) {
                    $caixaUsuario->create();
                    $caixaUsuario->save(array(
                        "id_caixa" => $idCaixa,
                        "id_usuario" => $Usuarios[$i]["id_usuario"],
                        "data_cadastro" => date('Y-m-d H:i:s')
                    ));
                } else if ($Usuarios[$i]["idcaixausuario"] != 0 && !isset($Usuarios[$i]["liberado"])) {
                    $caixaUsuario->delete($Usuarios[$i]["idcaixausuario"]);
                }
            }

            $this->Session->setFlash(__("Alteração concluída com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "caixa", "action" => "index"));
        }
    }

    public function abertura() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_ABRIR_CAIXA);
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $dataCaixa = $data["Caixa"];

            $caixaAberturaFechamento = new CaixaAberturaFechamento();

            if ($caixaAberturaFechamento->verificarCaixaAberto($dataCaixa['id_caixa_abertura'])) {
                $this->Session->setFlash(__("O caixa já está aberto."), 'alerta');
                return $this->redirect(array("controller" => "caixa", "action" => "abertura"));
            }

            $caixaAberturaFechamento->abrirCaixa($dataCaixa['id_caixa_abertura'], $this->_idDaSessao, $dataCaixa['saldo_inicial']);

            $this->Session->setFlash(__("Caixa aberto com sucesso."), 'sucesso');
            return $this->redirect(array("controller" => "index", "action" => "index"));
        } else if ($this->request->is("get")) {
            $caixa = new Caixa();
            $caixas_ = $caixa->retornarTodosPorUsuario($this->_idDaSessao);
            $this->set('Caixas', $caixas_);
        }
    }

    public function gerenciar() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_LISTAR_CAIXA);
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;

            $tipoData = $data['radio_data_filtro'];
            $splitDe = explode("/", $data['data_filtro_de']);
            $splitAte = explode("/", $data['data_filtro_ate']);

            $strDe = $splitDe[2] . "-" . $splitDe[1] . "-" . $splitDe[0];
            $strAte = $splitAte[2] . "-" . $splitAte[1] . "-" . $splitAte[0];

            $caixaAberturaFechamento = new CaixaAberturaFechamento();

            $caixas = $caixaAberturaFechamento->retornarVisualizacaoGerenciamento($tipoData, $strDe, $strAte);

            $count = count($caixas);
            $caixasPeriodo['caixas'] = $count > 0 ? $caixas : array();

            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    $caixasPeriodo['caixas'][$i]['cm'] = $caixasPeriodo['caixas'][$i][0];
                    unset($caixasPeriodo['caixas'][$i][0]);
                }
            }

            $this->response->body(json_encode($caixasPeriodo));
        } else if ($this->request->is("get")) {
            
        }
    }

    public function fechamento() {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_FECHAR_CAIXA);
    }

}
