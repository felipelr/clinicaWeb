<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Favorecido', 'Model');
App::uses('Endereco', 'Model');

/**
 * CakePHP FavorecidoController
 * @author BersaN & StarK
 */
class FavorecidoController extends AuthController {

    public function index() {
    }

    public function cadastrar() {
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $datafavorecido["Favorecido"] = $data["Favorecido"];
            $favorecido = new Favorecido();
            $favorecido->save($datafavorecido);

            $dataendereco["Endereco"] = $data["Endereco"];
            $dataendereco["Endereco"]["id_favorecido"] = (int) $favorecido->id;

            $url = "http://maps.googleapis.com/maps/api/geocode/json?address={$dataendereco["Endereco"]["logradouro"]}+{$dataendereco["Endereco"]["numero"]}+{$dataendereco["Endereco"]["bairro"]}+{$dataendereco["Endereco"]["cidade"]}+{$dataendereco["Endereco"]["uf"]}&sensor=false";
            $url = str_replace(' ', '%20', $url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $result = json_decode($output, true);
            curl_close($ch);
            $dataendereco["Endereco"]["latitude"] = isset($result['results'][0]['geometry']['location']['lat']) ? $result['results'][0]['geometry']['location']['lat'] : 0;
            $dataendereco["Endereco"]["longitude"] = isset($result['results'][0]['geometry']['location']['lng']) ? $result['results'][0]['geometry']['location']['lng'] : 0;

            $endeco = new Endereco();
            $endeco->save($dataendereco);

            $layout = isset($this->request->query['layout']) ? $this->request->query['layout'] : null;
            if (isset($layout)) {
                $this->response->body(json_encode($favorecido->retornarPorId($favorecido->id)));
            } else {
                $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
                return $this->redirect(array("controller" => "favorecido", "action" => "index"));
            }
        }
        if ($this->request->is("get")) {
            $layout = isset($this->request->query['layout']) ? $this->request->query['layout'] : null;
            if (isset($layout)) {
                $this->layout = "ajax";
            }
        }
    }

    public function alterar($idfavorecido) {
        $favorecido = new Favorecido();
        $endereco = new Endereco();
        if ($this->request->is("get")) {
            if (isset($idfavorecido)) {
                $favorecido = $favorecido->retornarPorId($idfavorecido);
                $endereco = $endereco->retornarPorFavorecido($idfavorecido);
                $this->set("Favorecido", $favorecido);
                $this->set("Endereco", $endereco);
            } else {
                return $this->redirect(array("controller" => "favorecido", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;
            $favorecido->save($data["Favorecido"]);
            $url = "http://maps.googleapis.com/maps/api/geocode/json?address={$data["Endereco"]["logradouro"]}+{$data["Endereco"]["numero"]}+{$data["Endereco"]["bairro"]}+{$data["Endereco"]["cidade"]}+{$data["Endereco"]["uf"]}&sensor=false";
            $url = str_replace(' ', '%20', $url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $result = json_decode($output, true);
            curl_close($ch);
            $data["Endereco"]["latitude"] = isset($result['results'][0]['geometry']['location']['lat']) ? $result['results'][0]['geometry']['location']['lat'] : 0;
            $data["Endereco"]["longitude"] = isset($result['results'][0]['geometry']['location']['lng']) ? $result['results'][0]['geometry']['location']['lng'] : 0;
            $endereco->save($data['Endereco']);
            $this->Session->setFlash(__("Cadastro Atualizado com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "favorecido", "action" => "index"));
        }
    }

    public function excluir() {
        $idfavorecido = $this->request->data['idfavorecido'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idfavorecido)) {
                $favorecido = new Favorecido();
                $favorecido->excluir($idfavorecido);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "favorecido", "action" => "index"));
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

        $content = new Favorecido();
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

    public function ajax_cep() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $cep = isset($this->request->query['cep']) ? $this->request->query['cep'] : null;
        if (isset($cep)) {
            $url = "http://cep.republicavirtual.com.br/web_cep.php?formato=xml&cep=" . $cep;
            $result = Xml::build($url);

            $dados['sucesso'] = (int) $result->resultado;
            $dados['rua'] = (string) $result->tipo_logradouro . ' ' . $result->logradouro;
            $dados['bairro'] = (string) $result->bairro;
            $dados['cidade'] = (string) $result->cidade;
            $dados['estado'] = (string) $result->uf;

            $this->response->body(json_encode($dados));
        }
    }

}
