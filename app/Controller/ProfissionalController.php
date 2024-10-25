<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses("AuthController", "Controller");
App::uses("Profissional", "Model");
App::uses("Endereco", "Model");
App::uses("Cargo", "Model");
App::uses("User", "Model");
App::uses("CategoriaAula", "Model");

/**
 * CakePHP ProfissionalController
 * @author Felipe
 */
class ProfissionalController extends AuthController
{

    public function index()
    {
    }

    public function cadastrar()
    {
        if ($this->request->is("get")) {
            $cargo = new Cargo();
            $usuario = new User();

            $categoriaAula = new CategoriaAula();
            $cargos = $cargo->retornarTodos();
            $categorias = $categoriaAula->retornarTodos();
            $usuarios = $usuario->retornarTodos();

            $this->set("cargos", $cargos);
            $this->set("categorias", $categorias);
            $this->set("Usuarios", $usuarios);
        }
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $foto = $_FILES["foto"];
            $data = $this->request->data;
            $dataprofissional["Profissional"] = $data["Profissional"];
            $dataendereco["Endereco"] = $data["Endereco"];
            $dataprofissional["Profissional"]["data_nascimento"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataprofissional["Profissional"]["data_nascimento"]);

            if (isset($dataprofissional["Profissional"]["data_admissao"])) {
                $dataprofissional["Profissional"]["data_admissao"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataprofissional["Profissional"]["data_admissao"]);
            }
            if (isset($dataprofissional["Profissional"]["data_demissao"])) {
                $dataprofissional["Profissional"]["data_demissao"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $dataprofissional["Profissional"]["data_demissao"]);
            }

            $profissional = new Profissional();
            $profissional->save($dataprofissional);
            $idprofissional = $profissional->id;
            if (isset($foto)) {
                $options = array(
                    "width" => 200, //Width of the new Image, Default is Original Width
                    "height" => 200, //Height of the new Image, Default is Original Height
                    "aspect" => true, //Keep aspect ratio
                    "crop" => true, //Crop the Image
                    "cropvars" => array(), //How to crop the image, array($startx, $starty, $endx, $endy);
                    "autocrop" => true, //Auto crop the image, calculate the crop according to the size and crop from the middle
                    "htmlAttributes" => array(), //Html attributes for the image tag
                    "quality" => 90, //Quality of the image
                    "urlOnly" => true    //Return only the URL or return the Image tag
                );
                $explode = explode(".", $foto["name"]);
                if (isset($explode[1])) {
                    $uploadfile = WWW_ROOT . "img/image." . $explode[1];
                    if (move_uploaded_file($foto["tmp_name"], $uploadfile)) {
                        $fotopath = $this->Image->resize($uploadfile, $options);
                        $imgData = base64_encode(file_get_contents($fotopath));
                        $sizeData = getimagesize($fotopath);
                        $foto_bytes = $imgData;
                        $foto_tipo = $sizeData["mime"];
                        $foto_nome = $foto["name"];
                        $profissional->salvarFoto($foto_bytes, $foto_nome, $foto_tipo, $idprofissional);
                    }
                }
            }

            $dataendereco["Endereco"]["id_profissional"] = (int) $profissional->id;

            $url = "http://maps.googleapis.com/maps/api/geocode/json?address={$dataendereco["Endereco"]["logradouro"]}+{$dataendereco["Endereco"]["numero"]}+{$dataendereco["Endereco"]["bairro"]}+{$dataendereco["Endereco"]["cidade"]}+{$dataendereco["Endereco"]["uf"]}&sensor=false";
            $url = str_replace(" ", "%20", $url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $result = json_decode($output, true);
            curl_close($ch);
            $dataendereco["Endereco"]["latitude"] = isset($result["results"][0]["geometry"]["location"]["lat"]) ? $result["results"][0]["geometry"]["location"]["lat"] : 0;
            $dataendereco["Endereco"]["longitude"] = isset($result["results"][0]["geometry"]["location"]["lng"]) ? $result["results"][0]["geometry"]["location"]["lng"] : 0;

            $endeco = new Endereco();
            $endeco->save($dataendereco);

            $categoriaAula = new CategoriaAula();
            $categoriaAula->inserirProfissionalCategoriaAula($data["CategoriaAula"], (int) $profissional->id);

            $this->Session->setFlash(__("Cadastro concluído com sucesso!"), "sucesso");
            return $this->redirect(array("controller" => "profissional", "action" => "index"));
        }
    }

    public function alterar($idprofissional)
    {
        $profissional = new Profissional();
        $endereco = new Endereco();
        $cargo = new Cargo();
        $categoriaAula = new CategoriaAula();
        $usuario = new User();

        if ($this->request->is("get")) {
            if (isset($idprofissional)) {
                $profissional = $profissional->retornarPorId($idprofissional);
                $usuarios = $usuario->retornarTodos();
                $endereco = $endereco->retornarPorProfissional($idprofissional);
                $date = DateTime::createFromFormat("Y-m-d", $profissional["data_nascimento"]);
                $profissional["data_nascimento"] = $date->format("d/m/Y");

                if (isset($profissional["data_admissao"])) {
                    $dateAdmissao = DateTime::createFromFormat("Y-m-d", $profissional["data_admissao"]);
                    $profissional["data_admissao"] = $dateAdmissao->format("d/m/Y");
                }
                if (isset($profissional["data_demissao"])) {
                    $dateDemissao = DateTime::createFromFormat("Y-m-d", $profissional["data_demissao"]);
                    $profissional["data_demissao"] = $dateDemissao->format("d/m/Y");
                }


                $cargos = $cargo->retornarTodos();
                $categorias = $categoriaAula->retornarTodos();
                $profissionalCategorias = $categoriaAula->retornarProfissionalCategoriaAula($idprofissional);
                $profissionalCategorias_ = array();
                foreach ($profissionalCategorias as $value) {
                    $str_explode = explode(".", $value["pc"]["porcentagem"]);
                    $value["pc"]["porcentagem"] = str_replace(",", "", $str_explode[0]) . "," . $str_explode[1];
                    $profissionalCategorias_[] = $value;
                }

                $this->set("Profissional", $profissional);
                $this->set("Endereco", $endereco);
                $this->set("Usuarios", $usuarios);
                $this->set("cargos", $cargos);
                $this->set("categorias", $categorias);
                $this->set("profissionalCategorias", $profissionalCategorias_);
            } else {
                return $this->redirect(array("controller" => "profissional", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;
            if (isset($data["Profissional"]["data_nascimento"])) {
                $data["Profissional"]["data_nascimento"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $data["Profissional"]["data_nascimento"]);
            }
            if (isset($data["Profissional"]["data_admissao"])) {
                $data["Profissional"]["data_admissao"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $data["Profissional"]["data_admissao"]);
            }
            if (isset($data["Profissional"]["data_demissao"])) {
                $data["Profissional"]["data_demissao"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $data["Profissional"]["data_demissao"]);
            }

            $profissional->save($data["Profissional"]);
            $foto = $_FILES["foto"];
            $idprofissional = (int) $data["Profissional"]["idprofissional"];
            if (isset($foto)) {
                $options = array(
                    "width" => 200, //Width of the new Image, Default is Original Width
                    "height" => 200, //Height of the new Image, Default is Original Height
                    "aspect" => true, //Keep aspect ratio
                    "crop" => true, //Crop the Image
                    "cropvars" => array(), //How to crop the image, array($startx, $starty, $endx, $endy);
                    "autocrop" => true, //Auto crop the image, calculate the crop according to the size and crop from the middle
                    "htmlAttributes" => array(), //Html attributes for the image tag
                    "quality" => 90, //Quality of the image
                    "urlOnly" => true    //Return only the URL or return the Image tag
                );
                $explode = explode(".", $foto["name"]);
                if (isset($explode[1])) {
                    $uploadfile = WWW_ROOT . "img/image." . $explode[1];
                    if (move_uploaded_file($foto["tmp_name"], $uploadfile)) {
                        $fotopath = $this->Image->resize($uploadfile, $options);
                        $imgData = base64_encode(file_get_contents($fotopath));
                        $sizeData = getimagesize($fotopath);
                        $foto_bytes = $imgData;
                        $foto_tipo = $sizeData["mime"];
                        $foto_nome = $foto["name"];
                        $profissional->salvarFoto($foto_bytes, $foto_nome, $foto_tipo, $idprofissional);
                    }
                }
            } else {
                $profissional->salvarFoto(NULL, NULL, NULL, $idprofissional);
            }

            $url = "http://maps.googleapis.com/maps/api/geocode/json?address={$data["Endereco"]["logradouro"]}+{$data["Endereco"]["numero"]}+{$data["Endereco"]["bairro"]}+{$data["Endereco"]["cidade"]}+{$data["Endereco"]["uf"]}&sensor=false";
            $url = str_replace(" ", "%20", $url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $result = json_decode($output, true);
            curl_close($ch);
            $data["Endereco"]["latitude"] = isset($result["results"][0]["geometry"]["location"]["lat"]) ? $result["results"][0]["geometry"]["location"]["lat"] : 0;
            $data["Endereco"]["longitude"] = isset($result["results"][0]["geometry"]["location"]["lng"]) ? $result["results"][0]["geometry"]["location"]["lng"] : 0;

            $endereco->save($data["Endereco"]);

            $categoriaAula = new CategoriaAula();
            $categoriaAula->inserirProfissionalCategoriaAula($data["CategoriaAula"], (int) $data["Profissional"]["idprofissional"]);

            $this->Session->setFlash(__("Alteração concluída com sucesso!"), "sucesso");
            return $this->redirect(array("controller" => "profissional", "action" => "index"));
        }
    }

    public function excluir()
    {
        $idprofissional = $this->request->data["idprofissional"];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idprofissional)) {
                $profissional = new Profissional();
                $profissional->excluir($idprofissional);
                $this->Session->setFlash(__("Exluído com sucesso!"), "sucesso");
            }
        }
        return $this->redirect(array("controller" => "profissional", "action" => "index"));
    }

    public function ajax_cep()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $cep = isset($this->request->query["cep"]) ? $this->request->query["cep"] : null;
        if (isset($cep)) {
            $url = "http://cep.republicavirtual.com.br/web_cep.php?formato=xml&cep=" . $cep;
            $result = Xml::build($url);

            $dados["sucesso"] = (int) $result->resultado;
            $dados["rua"] = (string) $result->tipo_logradouro . " " . $result->logradouro;
            $dados["bairro"] = (string) $result->bairro;
            $dados["cidade"] = (string) $result->cidade;
            $dados["estado"] = (string) $result->uf;

            $this->response->body(json_encode($dados));
        }
    }

    public function ajax()
    {
        $this->layout = null;
        $this->autoRender = false;
        $columns = (isset($this->request->data["columns"])) ? $this->request->data["columns"] : null;
        $order = (isset($this->request->data["order"])) ? $this->request->data["order"] : null;
        $draw = (isset($this->request->data["draw"])) ? $this->request->data["draw"] : 0;
        $start = (isset($this->request->data["start"])) ? $this->request->data["start"] : 0;
        $length = (isset($this->request->data["length"])) ? $this->request->data["length"] : 0;
        $search = (isset($this->request->data["search"]["value"]) && !empty($this->request->data["search"]["value"])) ? $this->request->data["search"]["value"] : null;
        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $content = new Profissional();
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

    public function foto($id)
    {
        $this->layout = null;
        $this->autoRender = false;
        $profissional = new Profissional();
        $profissional_ = $profissional->retornarPorId($id);
        if (isset($profissional_["foto_bytes"])) {
            $base = base64_decode($profissional_["foto_bytes"], true);
            $type = $profissional_["foto_tipo"];
            $img = imagecreatefromstring($base);
            header("Content-type: $type");
            if ($type == "image/jpeg") {
                imagejpeg($img);
            } else {
                imagepng($img);
            }
            imagedestroy($img);
        } else {
            $this->header("Content-type: image/gif");
            $img = imagecreatefromgif(WWW_ROOT . "img/no-image.gif");
            imagegif($img);
            imagedestroy($img);
        }
    }
}
