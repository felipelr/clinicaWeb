<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Recebimento', 'Model');
App::uses('TipoFinanceiro', 'Model');
App::uses('EventoDisponibilidade', 'Model');

/**
 * CakePHP RenovacaoContratoController
 * @author felip
 */
class RenovacaoContratoController extends AuthController {

    public function index() {
        $tipoFinanceiro = new TipoFinanceiro();
        $tipoFinanceiros_ = $tipoFinanceiro->retornarTodosPagaveis();

        $this->set('TipoFinanceiros', $tipoFinanceiros_);
    }

    public function ajax_teste(){
        $this->layout = null;
        $this->autoRender = false;

        $columns = (isset($this->request->data["columns"])) ? $this->request->data["columns"] : null;
        $order = (isset($this->request->data["order"])) ? $this->request->data["order"] : null;
        $draw = (isset($this->request->data["draw"])) ? $this->request->data["draw"] : 0;
        $start = (isset($this->request->data["start"])) ? $this->request->data["start"] : 0;
        $length = (isset($this->request->data["length"])) ? $this->request->data["length"] : 0;
        $search = (isset($this->request->data["search"]["value"]) && !empty($this->request->data["search"]["value"])) ? $this->request->data["search"]["value"] : null;
        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $strVencimentoDe = (isset($this->request->data["vencimento_de"])) ? $this->request->data["vencimento_de"] : null;
        $strVencimentoAte = (isset($this->request->data["vencimento_ate"])) ? $this->request->data["vencimento_ate"] : null;

        $arrayDe = explode('/', $strVencimentoDe);
        $arrayAte = explode('/', $strVencimentoAte);
        $isDataDe = false;
        $isDataAte = false;

        if (count($arrayDe) == 3) {
            $dia = (int) $arrayDe[0];
            $mes = (int) $arrayDe[1];
            $ano = (int) $arrayDe[2];

            if (checkdate($mes, $dia, $ano)) {
                $isDataDe = true;
                $strVencimentoDe = $ano . '/' . $mes . '/' . $dia;
            }
        }

        if (count($arrayAte) == 3) {
            $dia = (int) $arrayAte[0];
            $mes = (int) $arrayAte[1];
            $ano = (int) $arrayAte[2];

            if (checkdate($mes, $dia, $ano)) {
                $isDataAte = true;
                $strVencimentoAte = $ano . '/' . $mes . '/' . $dia;
            }
        }

        $vencimentoDe = $isDataDe ? $strVencimentoDe : null;
        $vencimentoAte = $isDataAte ? $strVencimentoAte : null;

        $content = new Recebimento();

        $this->response->body(json_encode(
            array(
                "message" => "teste",
            )
));
    }

    public function ajax_renovacao() {
        $this->layout = null;
        $this->autoRender = false;
        $columns = (isset($this->request->data["columns"])) ? $this->request->data["columns"] : null;
        $order = (isset($this->request->data["order"])) ? $this->request->data["order"] : null;
        $draw = (isset($this->request->data["draw"])) ? $this->request->data["draw"] : 0;
        $start = (isset($this->request->data["start"])) ? $this->request->data["start"] : 0;
        $length = (isset($this->request->data["length"])) ? $this->request->data["length"] : 0;
        $search = (isset($this->request->data["search"]["value"]) && !empty($this->request->data["search"]["value"])) ? $this->request->data["search"]["value"] : null;
        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $strVencimentoDe = (isset($this->request->data["vencimento_de"])) ? $this->request->data["vencimento_de"] : null;
        $strVencimentoAte = (isset($this->request->data["vencimento_ate"])) ? $this->request->data["vencimento_ate"] : null;

        $arrayDe = explode('/', $strVencimentoDe);
        $arrayAte = explode('/', $strVencimentoAte);
        $isDataDe = false;
        $isDataAte = false;

        if (count($arrayDe) == 3) {
            $dia = (int) $arrayDe[0];
            $mes = (int) $arrayDe[1];
            $ano = (int) $arrayDe[2];

            if (checkdate($mes, $dia, $ano)) {
                $isDataDe = true;
                $strVencimentoDe = $ano . '/' . $mes . '/' . $dia;
            }
        }

        if (count($arrayAte) == 3) {
            $dia = (int) $arrayAte[0];
            $mes = (int) $arrayAte[1];
            $ano = (int) $arrayAte[2];

            if (checkdate($mes, $dia, $ano)) {
                $isDataAte = true;
                $strVencimentoAte = $ano . '/' . $mes . '/' . $dia;
            }
        }

        $vencimentoDe = $isDataDe ? $strVencimentoDe : null;
        $vencimentoAte = $isDataAte ? $strVencimentoAte : null;

        $content = new Recebimento();
        $contents = $content->listarRenovacaoContratoJQuery($search, $start, $length, $ordenacao, $vencimentoDe, $vencimentoAte);

        $dados = array();
        if (isset($contents)) {
            foreach ($contents as $_content) {
                //$_content['p']["data_nascimento"] = date('d/m/Y', strtotime($_content['p']['data_nascimento']));                
                $_content['r']['valor'] = 'R$ ' . number_format($_content['r']['valor'], 2, ",", ".");
                $_content['r']['data_vencimento'] = date('d/m/Y', strtotime($_content['x']['UltimaAula']));
                $dados[] = $_content;
            }
        }

        $this->response->body(json_encode(
                        array(
                            "draw" => $draw,
                            "recordsTotal" => (int) $content->totalRegistroRenovacaoContrato($vencimentoDe, $vencimentoAte),
                            "recordsFiltered" => (int) $content->totalRegistroRenovacaoContratoFiltrado($search, $vencimentoDe, $vencimentoAte),
                            "data" => $dados
                        )
        ));
    }

    public function ajax_recebimento_detalhes() {
        $this->layout = null;
        $this->autoRender = false;
        $idrecebimento = (isset($this->request->query["idrecebimento"])) ? $this->request->query["idrecebimento"] : null;
        if (isset($idrecebimento)) {
            $recebimento = new Recebimento();
            $dados = $recebimento->retornarDetalhesRecebimento($idrecebimento);

            $recebimento_ = $dados[0];
            $recebimento_['recebimento']['valor'] = number_format($recebimento_['recebimento']['valor'], 2, ",", ".");
            $recebimento_['recebimento']['data_competencia'] = date('d/m/Y', strtotime($recebimento_['recebimento']['data_competencia']));
            $recebimento_['financeiro']['data_vencimento'] = date('d/m/Y', strtotime($recebimento_['financeiro']['data_vencimento']));
            $recebimento_['recebimento']['data_competencia_nova'] = date('d/m/Y', strtotime($recebimento_[0]['data_competencia_nova']));

            $this->response->body(json_encode($recebimento_));
        }
    }

    public function renovar() {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;

            try {
                $recebimento = new Recebimento();
                $recebimentoArray = $recebimento->retornarPorId($data['idrecebimento']);
                $str_explode = explode(',', $data['valor']);
                $data['valor'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];

                $dataVencimento = DateTime::createFromFormat('d/m/Y H:i:s', $data['data_competencia'] . ' 00:00:00');

                $financeiro = new Financeiro();
                $eventoDisponibilidade = new EventoDisponibilidade();

                if ($data['valor_referente'] == 'TOTAL') {
                    $data['valor'] = $data['valor'] / $data['quantidade_parcela'];
                }

                for ($i = 0; $i < $data['quantidade_parcela']; $i++) {
                    $financeiro->create();
                    $dadosF = array();
                    $dadosF['id_recebimento'] = $data['idrecebimento'];
                    $dadosF['valor'] = $data['valor'];
                    $dadosF['data_vencimento'] = $dataVencimento->format('Y-m-d H:i:s');
                    $dadosF['id_financeiro_tipo'] = $data['id_financeiro_tipo'];
                    $dadosF['total_parcela'] = $recebimentoArray['quantidade_parcela'] + $data['quantidade_parcela'];
                    $dadosF['parcela'] = $recebimentoArray['quantidade_parcela'] + ($i + 1);
                    $dadosF['pago'] = 0;
                    $dadosF['convertido'] = 0;
                    $dadosF['id_caixa_loja'] = $recebimentoArray['id_caixa_loja'];
                    $dadosF['id_usuario'] = $this->_idDaSessao;
                    $financeiro->save($dadosF);

                    //add 1 month
                    $dataVencimento->add(new DateInterval('P1M'));
                }

                $recebimento->atualizarRecebimento($data['idrecebimento'], $recebimentoArray['quantidade_sessoes']);

                $recebimento->atualizarSessoesRecebimento($data['idrecebimento'], ($data['quantidade_sessoes_mes'] * $data['quantidade_parcela']));

                $eventoDisponibilidade->create();
                $eventoDisponibilidade->save(array(
                    'id_paciente' => $recebimentoArray['id_paciente'],
                    'total' => ($data['quantidade_sessoes_mes'] * $data['quantidade_parcela']),
                    'total_sessoes' => ($data['quantidade_sessoes_mes'] * $data['quantidade_parcela']),
                    'id_recebimento' => $data['idrecebimento']));

                $result = array();
                $result['status'] = 'OK';
                $result['status'] = 'Sucesso!';
                $this->response->body(json_encode($result));
            } catch (Exception $ex) {
                $result = array();
                $result['status'] = 'ERRO';
                $result['status'] = $ex->getMessage();
                $this->response->body(json_encode($result));
            }
        }
    }

}
