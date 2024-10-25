<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses("AuthController", "Controller");
App::uses("Paciente", "Model");
App::uses("Endereco", "Model");
App::uses("Profissional", "Model");
App::uses("Recebimento", "Model");
App::uses("EventoDisponibilidade", "Model");

/**
 * CakePHP PacienteController
 * @author Felipe
 */
class PacienteController extends AuthController
{

    public $components = array("RequestHandler");

    public function index()
    {
    }

    public function paciente_recebimentos_viewpdf($idpaciente)
    {
        $this->layout = "default";
        if ($this->request->is("get")) {
            if (isset($idpaciente)) {
                ini_set("memory_limit", "512M");
                $paciente = new Paciente();
                $endereco = new Endereco();
                $recebimento = new Recebimento();

                $dataPaciente = $paciente->retornarPorId($idpaciente);
                if (isset($dataPaciente["data_nascimento"])) {
                    $date = DateTime::createFromFormat("Y-m-d", $dataPaciente["data_nascimento"]);
                    $dataPaciente["data_nascimento"] = $date->format("d/m/Y");
                }

                if (isset($dataPaciente["data_inicio_atendimento"])) {
                    $date = DateTime::createFromFormat("Y-m-d", $dataPaciente["data_inicio_atendimento"]);
                    $dataPaciente["data_inicio_atendimento"] = $date->format("d/m/Y");
                }

                $dataEndereco = $endereco->retornarPorPaciente($idpaciente);

                $dataRecebimento = $recebimento->recebimentosPorPaciente($idpaciente);

                if (!isset($dataRecebimento)) {
                    $dataRecebimento = array();
                }

                $this->set("Endereco", $dataEndereco);
                $this->set("Paciente", $dataPaciente);
                $this->set("Recebimentos", $dataRecebimento);
            } else {
                return $this->redirect(array("controller" => "relatorio", "action" => "paciente"));
            }
        }
    }

    public function ajax()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $columns = (isset($this->request->data["columns"])) ? $this->request->data["columns"] : null;
        $order = (isset($this->request->data["order"])) ? $this->request->data["order"] : null;
        $draw = (isset($this->request->data["draw"])) ? $this->request->data["draw"] : 0;
        $start = (isset($this->request->data["start"])) ? $this->request->data["start"] : 0;
        $length = (isset($this->request->data["length"])) ? $this->request->data["length"] : 0;
        $search = (isset($this->request->data["search"]["value"]) && !empty($this->request->data["search"]["value"])) ? $this->request->data["search"]["value"] : null;
        $clientStatus = isset($this->request->query["clientStatus"]) ? $this->request->query["clientStatus"] : -1;
        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $content = new Paciente();
        $contents = $content->listarJQuery($search, $start, $length, $ordenacao, $clientStatus);

        echo json_encode(
            array(
                "draw" => $draw,
                "recordsTotal" => (int) $content->totalRegistro(),
                "recordsFiltered" => (int) $content->totalRegistroFiltrado($search, $clientStatus),
                "data" => $contents
            )
        );
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

    public function cadastrar()
    {
        if ($this->request->is("post")) {
            $this->layout = "ajax";
            $this->autoRender = false;
            $foto = isset($_FILES["foto"]) ? $_FILES["foto"] : null;
            $data = $this->request->data;
            $datapaciente["Paciente"] = $data["Paciente"];
            $dataendereco["Endereco"] = $data["Endereco"];
            $datapaciente["Paciente"]["data_nascimento"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $datapaciente["Paciente"]["data_nascimento"]);

            if (isset($datapaciente["Paciente"]["data_inicio_atendimento"])) {
                $datapaciente["Paciente"]["data_inicio_atendimento"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $datapaciente["Paciente"]["data_inicio_atendimento"]);
            }

            $paciente = new Paciente();
            $paciente->save($datapaciente);

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
                        $paciente->salvarFoto($foto_bytes, $foto_nome, $foto_tipo, $paciente->id);
                    }
                }
            }

            $dataendereco["Endereco"]["id_paciente"] = (int) $paciente->id;

            if (isset($dataendereco["Endereco"]["logradouro"]) && $dataendereco["Endereco"]["logradouro"] != "") {
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
            }

            $endeco = new Endereco();
            $endeco->save($dataendereco);

            $layout = isset($this->request->query["layout"]) ? $this->request->query["layout"] : null;
            if (isset($layout)) {
                $this->response->body(json_encode($paciente->retornarPorId($paciente->id)));
            } else {
                $this->Session->setFlash(__("Cadastro concluído com sucesso!"), "sucesso");
                return $this->redirect(array("controller" => "paciente", "action" => "index"));
            }
        }
        if ($this->request->is("get")) {
            $layout = isset($this->request->query["layout"]) ? $this->request->query["layout"] : null;
            if (isset($layout)) {
                $this->layout = "ajax";
            }
        }
    }

    public function alterar($idpaciente)
    {
        $paciente = new Paciente();
        $endereco = new Endereco();
        if ($this->request->is("get")) {
            if (isset($idpaciente)) {
                $paciente = $paciente->retornarPorId($idpaciente);
                $endereco = $endereco->retornarPorPaciente($idpaciente);
                if (isset($paciente["data_nascimento"])) {
                    $date = DateTime::createFromFormat("Y-m-d", $paciente["data_nascimento"]);
                    $paciente["data_nascimento"] = $date->format("d/m/Y");
                }
                if (isset($paciente["data_inicio_atendimento"])) {
                    $date = DateTime::createFromFormat("Y-m-d", $paciente["data_inicio_atendimento"]);
                    $paciente["data_inicio_atendimento"] = $date->format("d/m/Y");
                }
                $this->set("Paciente", $paciente);
                $this->set("Endereco", $endereco);
            } else {
                return $this->redirect(array("controller" => "paciente", "action" => "index"));
            }
        }
        if ($this->request->is("post")) {
            $data = $this->request->data;
            $data["Paciente"]["data_nascimento"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $data["Paciente"]["data_nascimento"]);
            if (isset($data["Paciente"]["data_inicio_atendimento"])) {
                $data["Paciente"]["data_inicio_atendimento"] = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $data["Paciente"]["data_inicio_atendimento"]);
            }
            $paciente->save($data["Paciente"]);
            $foto = $_FILES["foto"];
            $idpaciente = (int) $data["Paciente"]["idpaciente"];
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
                        $paciente->salvarFoto($foto_bytes, $foto_nome, $foto_tipo, $idpaciente);
                    }
                }
            } else {
                $paciente->salvarFoto(NULL, NULL, NULL, $idpaciente);
            }

            if (isset($data["Endereco"]["logradouro"]) && $data["Endereco"]["logradouro"] != "") {
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
            }
            $endereco->save($data["Endereco"]);

            $this->Session->setFlash(__("Alteração concluída com sucesso!"), "sucesso");
            return $this->redirect(array("controller" => "paciente", "action" => "index"));
        }
    }

    public function excluir()
    {
        $idpaciente = $this->request->data["idpaciente"];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idpaciente)) {
                $paciente = new Paciente();
                $paciente->excluir($idpaciente);
                $this->Session->setFlash(__("Exluído com sucesso!"), "sucesso");
            }
        }
        return $this->redirect(array("controller" => "paciente", "action" => "index"));
    }

    public function disponibilidade_evento($idpaciente)
    {
        $this->set("idpaciente", $idpaciente);
        if ($this->request->is("get")) {
            if (isset($idpaciente)) {
                $paciente = new Paciente();
                $recebimento = new Recebimento();

                $dataRecebimentos = $recebimento->retornarPorIdPaciente($idpaciente);
                $dataPaciente = $paciente->retornarPorId($idpaciente);

                $date = DateTime::createFromFormat("Y-m-d", $dataPaciente["data_nascimento"]);
                $dataPaciente["data_nascimento"] = $date->format("d/m/Y");

                $this->set("Paciente", $dataPaciente);
                $this->set("Recebimentos", $dataRecebimentos);
            } else {
                return $this->redirect(array("controller" => "paciente", "action" => "index"));
            }
        }
    }

    public function alterar_disponibilidade_ajax($idpaciente)
    {
        $this->autoRender = false;
        $eventos = isset($this->request->data["eventos"]) ? $this->request->data["eventos"] : null;
        $result = false;
        if (isset($eventos)) {
            $size = count($eventos);
            if ($size > 0) {
                $eventoDisponibilidade = new EventoDisponibilidade();
                for ($i = 0; $i < $size; $i++) {
                    $eventoDisponibilidade->updateTotal($eventos[$i]["ideventodisponibilidade"], $eventos[$i]["total"]);
                }
                $result = true;
            }
        }
        if ($result) {
            $this->Session->setFlash(__("Alteração concluída com sucesso!"), "sucesso");
        } else {
            $this->Session->setFlash(__("Não foi possível alterar!"), "erro");
        }
        return $this->redirect(array("controller" => "paciente", "action" => "disponibilidade_evento", $idpaciente));
    }
}
