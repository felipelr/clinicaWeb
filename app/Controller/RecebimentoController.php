<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Recebimento', 'Model');
App::uses('Paciente', 'Model');
App::uses('Clinica', 'Model');
App::uses('Profissional', 'Model');
App::uses('PlanoSessao', 'Model');
App::uses('TipoFinanceiro', 'Model');
App::uses('ContaBancaria', 'Model');
App::uses('Banco', 'Model');
App::uses('CaixaLoja', 'Model');
App::uses('Financeiro', 'Model');
App::uses('CentroCustos', 'Model');
App::uses('Acesso', 'Model');
App::uses('CategoriaAula', 'Model');
App::uses('EventoDisponibilidade', 'Model');
App::uses('Evento', 'Model');
App::uses('Endereco', 'Model');

/**
 * CakePHP RecebimentoController
 * @author Felipe
 */
class RecebimentoController extends AuthController
{

    public $components = array('RequestHandler');

    public function index($id = null)
    {
        $search = CakeSession::read("textSearch");
        $recebimentoDados = null;

        if (isset($id)) {
            $recebimento = new Recebimento();
            $recebimentoDados = $recebimento->retornarPorIdComPaciente($id);
        }

        $this->set("textSearch", $search);
        $this->set("recebimentoDados", $recebimentoDados);
    }

    private function retornarDiaSemanaInglesPorArray($eventos, $index)
    {
        $diaSemana = '';
        switch ($eventos[$index]['dia']) {
            case "segunda":
                $diaSemana = "monday";
                break;
            case "terca":
                $diaSemana = "tuesday";
                break;
            case "quarta":
                $diaSemana = "wednesday";
                break;
            case "quinta":
                $diaSemana = "thursday";
                break;
            case "sexta":
                $diaSemana = "friday";
                break;
            case "sabado":
                $diaSemana = "saturday";
                break;
            case "domingo":
                $diaSemana = "sunday";
                break;
        }
        return $diaSemana;
    }

    private function retornarDiaSemanaIngles($nomePortugues)
    {
        $diaSemana = '';
        switch ($nomePortugues) {
            case "segunda":
                $diaSemana = "monday";
                break;
            case "terca":
                $diaSemana = "tuesday";
                break;
            case "quarta":
                $diaSemana = "wednesday";
                break;
            case "quinta":
                $diaSemana = "thursday";
                break;
            case "sexta":
                $diaSemana = "friday";
                break;
            case "sabado":
                $diaSemana = "saturday";
                break;
            case "domingo":
                $diaSemana = "sunday";
                break;
        }
        return $diaSemana;
    }

    private function retornarDiaSemanaInglesPorIndex($index)
    {
        $diaSemana = '';
        switch ($index) {
            case 1:
                $diaSemana = "monday";
                break;
            case 2:
                $diaSemana = "tuesday";
                break;
            case 3:
                $diaSemana = "wednesday";
                break;
            case 4:
                $diaSemana = "thursday";
                break;
            case 5:
                $diaSemana = "friday";
                break;
            case 6:
                $diaSemana = "saturday";
                break;
            case 0:
                $diaSemana = "sunday";
                break;
        }
        return $diaSemana;
    }

    private function retornarIndexDiaSemana($daysofweek, $diaSemana)
    {
        $index = 0;
        for ($i = 0; $i < 7; $i++) {
            if ($diaSemana == $daysofweek[$i]) {
                $index = $i;
                break;
            }
        }
        return $index;
    }

    public function cadastrar()
    {
        if ($this->request->is("post")) {
            date_default_timezone_set('America/Sao_Paulo');
            $this->layout = null;
            $this->autoRender = false;

            $data = $this->request->data;
            $recebimento = new Recebimento();

            $eventos = array();
            $dataInicio = "";
            $idAgenda = 0;
            $idRecebimento = 0;
            $tipoConflito = 0;
            $fixo = 0;

            if (isset($data['Recebimento']['eventos'])) {
                $eventos = $data['Recebimento']['eventos'];
                $dataInicio = $data['Recebimento']['data_inicio_eventos'];
                $idAgenda = $data['Recebimento']['id_agenda'];
                $tipoConflito = $data['Recebimento']['conflito'];
                $fixo = $data['Recebimento']['fixo'];
            }

            unset($data['Recebimento']['eventos']);
            unset($data['Recebimento']['data_inicio_eventos']);
            unset($data['Recebimento']['id_agenda']);
            unset($data['Recebimento']['conflito']);
            unset($data['Recebimento']['fixo']);

            //salvar
            if ($recebimento->cadastrar($data, $this->_idDaSessao, $idRecebimento)) {
                //gerar eventos na agenda                
                $countEventos = count($eventos);
                $countEventos = $countEventos - 1; //diminiu um por que o ultimo registro é sempre em branco
                $conflito = "";

                if ($countEventos > 0 && $idAgenda > 0 && $dataInicio != "") {
                    $eventoDisponibilidade = new EventoDisponibilidade();
                    $dadosEventoDisponibilidade = $eventoDisponibilidade->retornarPorIdRecebimento($idRecebimento, $data['Recebimento']['id_paciente']);
                    $dadosRecebimento = $recebimento->retornarPorId($idRecebimento);

                    $daysofweek = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
                    $totalDisponivel = $dadosEventoDisponibilidade['total'];
                    $dateTimeInicio = DateTime::createFromFormat('d/m/Y', $dataInicio);
                    $qtde_sessoes = $dadosRecebimento['quantidade_sessoes'];
                    $diaAnterior = $daysofweek[date('w')];

                    $arrayEventos = array();
                    $evento = new Evento();
                    $planoSessao = new PlanoSessao();
                    $planoSessaoSelecionado = null;
                    if (isset($dadosRecebimento['id_plano_sessao']) && $dadosRecebimento['id_plano_sessao'] != '') {
                        $planoSessaoSelecionado = $planoSessao->retornarPorId($dadosRecebimento['id_plano_sessao']);
                    }

                    $quantidadeSessoesMax = 1;

                    if (isset($planoSessaoSelecionado)) {
                        if ($planoSessaoSelecionado['tipo_quantidade_sessoes'] == 'MAX') {
                            //CALCULAR A QUANTIDADE DE SESSOES
                            $quantidadeMesesPlano = $planoSessaoSelecionado['quantidade_meses'];
                            $dateTimeInicioCalculo = DateTime::createFromFormat('d/m/Y', $dataInicio);
                            $dateTimeFimCalculo = DateTime::createFromFormat('d/m/Y', $dataInicio);
                            $dateTimeFimCalculo->add(DateInterval::createFromDateString("$quantidadeMesesPlano months"));
                            $todayDayOfWeek = $daysofweek[date('w')];
                            $firstRecord = true;
                            while ($dateTimeInicioCalculo <= $dateTimeFimCalculo) {
                                //PERCORRENDO OS DIAS DA SEMANA INFORMADA NO CADASTRO
                                for ($i = 0; $i < $countEventos; $i++) {

                                    $diaSemana = $this->retornarDiaSemanaInglesPorArray($eventos, $i);

                                    if (!$firstRecord) {
                                        $dateTimeInicioCalculo->modify('next ' . $diaSemana);
                                    }
                                    if ($dateTimeInicioCalculo > $dateTimeFimCalculo)
                                        break;

                                    if ($firstRecord) {
                                        $diaSemanaDataInicio__ = $dateTimeInicio->format('w'); //terca
                                        $indexDiaSemana__ = $this->retornarIndexDiaSemana($daysofweek, $diaSemana); //sexta
                                        $diaSemanaHoje = date('w'); //sexta
                                        if ($indexDiaSemana__ == $diaSemanaDataInicio__) {
                                            if ($diaSemanaHoje == $diaSemanaDataInicio__) {
                                                $primeiroHorario = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeInicioCalculo->format('Y-m-d') . ' ' . $eventos[$i]['horario_inicio'] . ':00');
                                                $horarioAtual = new DateTime('now');
                                                if ($primeiroHorario > $horarioAtual) {
                                                    $firstRecord = false;
                                                    $quantidadeSessoesMax++;
                                                } else if ($countEventos == 1) {
                                                    $firstRecord = false;
                                                }
                                            } else {
                                                //verificar se o dia da semana 
                                                $primeiroHorario = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeInicioCalculo->format('Y-m-d') . ' ' . $eventos[$i]['horario_inicio'] . ':00');
                                                $horarioAtual = new DateTime('now');

                                                if ($primeiroHorario > $horarioAtual) {
                                                    $firstRecord = false;
                                                    $quantidadeSessoesMax++;
                                                }
                                            }
                                        } else if ($indexDiaSemana__ > $diaSemanaDataInicio__) {
                                            if ($diaSemanaHoje == $indexDiaSemana__) {
                                                $horarioAtual = new DateTime('now');
                                                $primeiroHorario = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeInicioCalculo->format('Y-m-d') . ' ' . $eventos[$i]['horario_inicio'] . ':00');

                                                if ($primeiroHorario > $horarioAtual) {
                                                    $firstRecord = false;
                                                    $quantidadeSessoesMax++;
                                                }
                                            }
                                        } else if ($countEventos == 1) {
                                            $firstRecord = false;
                                        }
                                    } else {
                                        $firstRecord = false;
                                        $quantidadeSessoesMax++;
                                    }
                                }

                                if ($firstRecord) {
                                    $firstRecord = false;
                                }
                            }

                            $qtde_sessoes = $quantidadeSessoesMax;
                            $dadosRecebimento['quantidade_sessoes'] = $quantidadeSessoesMax;
                        }

                        $recebimento->save($dadosRecebimento);

                        if (isset($planoSessaoSelecionado)) {
                            $dadosEventoDisponibilidade = $eventoDisponibilidade->retornarPorIdRecebimento($dadosRecebimento['idrecebimento'], $dadosRecebimento['id_paciente']);
                            $dadosEventoDisponibilidade['total'] = $quantidadeSessoesMax;
                            $dadosEventoDisponibilidade['total_sessoes'] = $quantidadeSessoesMax;
                            $eventoDisponibilidade->save($dadosEventoDisponibilidade);
                        }
                    }

                    //GERANDO EVENTOS NA AGENDA
                    $firstRecord = true;
                    $diaSemanaInicio = $daysofweek[$dateTimeInicio->format('w')];
                    for ($k = 0; $k < $qtde_sessoes;) {
                        //PERCORRENDO OS DIAS DA SEMANA INFORMADA NO CADASTRO
                        for ($i = 0; $i < $countEventos; $i++) {
                            if ($k >= $qtde_sessoes) {
                                break;
                            }

                            $diaSemana = "";
                            switch ($eventos[$i]['dia']) {
                                case "segunda":
                                    $diaSemana = "monday";
                                    break;
                                case "terca":
                                    $diaSemana = "tuesday";
                                    break;
                                case "quarta":
                                    $diaSemana = "wednesday";
                                    break;
                                case "quinta":
                                    $diaSemana = "thursday";
                                    break;
                                case "sexta":
                                    $diaSemana = "friday";
                                    break;
                                case "sabado":
                                    $diaSemana = "saturday";
                                    break;
                                case "domingo":
                                    $diaSemana = "sunday";
                                    break;
                            }

                            if ($firstRecord) {
                                //verificar se o dia inicio é algum dos dias selecionado para registrar eventos
                                $diaInicioEstaNosDiasRequisitados = false;
                                for ($dia = 0; $dia < $countEventos; $dia++) {
                                    $diaSemana_ = $this->retornarDiaSemanaIngles($eventos[$dia]['dia']);
                                    if ($diaSemanaInicio == $diaSemana_) {
                                        $diaInicioEstaNosDiasRequisitados = true;
                                        break;
                                    }
                                }

                                if (!$diaInicioEstaNosDiasRequisitados) {
                                    //se nao for, já manda pro primeiro dia requisitado
                                    $dateTimeInicio->modify('next ' . $diaSemana);
                                    $diaSemanaInicio = $daysofweek[$dateTimeInicio->format('w')];
                                }
                            }

                            $dataInicioHorario = DateTime::createFromFormat('d/m/Y', $dateTimeInicio->format('d/m/Y'));
                            $dataFimHorario = DateTime::createFromFormat('d/m/Y', $dateTimeInicio->format('d/m/Y'));

                            if (!$firstRecord) {
                                if ($countEventos == 1 || $diaAnterior != $diaSemana) {
                                    $dataInicioHorario->modify('next ' . $diaSemana);
                                    $dataFimHorario->modify('next ' . $diaSemana);
                                    $dateTimeInicio->modify('next ' . $diaSemana);
                                }
                            } else {
                                if ($diaSemanaInicio != $diaSemana) {
                                    $indexDiaSemanaHoje = date('w'); //4

                                    //verificar hoje é algum dos dias selecionado para registrar eventos
                                    $hojeContemNosEventos = false;
                                    for ($dia = 0; $dia < $countEventos; $dia++) {
                                        $diaSemana_ = $this->retornarDiaSemanaIngles($eventos[$dia]['dia']);
                                        $indexDiaSemanaArray_ = $this->retornarIndexDiaSemana($daysofweek, $diaSemana_);
                                        if ($indexDiaSemanaArray_ == $indexDiaSemanaHoje) {
                                            $hojeContemNosEventos = true;
                                            break;
                                        }
                                    }

                                    if ($hojeContemNosEventos) {
                                        //se hoje é um dos dias da semana
                                        $diaSemana = $this->retornarDiaSemanaInglesPorIndex($indexDiaSemanaHoje);
                                    } else {
                                        //senao pega o primeiro dia configurado
                                        $diaSemana = $this->retornarDiaSemanaIngles($eventos[0]['dia']);
                                    }

                                    $primeiroHorario = DateTime::createFromFormat('Y-m-d H:i:s', $dataInicioHorario->format('Y-m-d') . ' ' . $eventos[$i]['horario_inicio'] . ':00');
                                    $horarioAtual = new DateTime('now');
                                    if ($horarioAtual > $primeiroHorario) {
                                        //verificar se existe algum dia da semana requisitado após o dia atual
                                        $existeDiaDaSemanaAposAtual = false;
                                        $diaSemanaExistente = $diaSemana;
                                        $indexDiaSemanaArray = $this->retornarIndexDiaSemana($daysofweek, $diaSemana);
                                        for ($dia = 0; $dia < $countEventos; $dia++) {
                                            $diaSemanaExistente = $this->retornarDiaSemanaIngles($eventos[$dia]['dia']);
                                            $indexDiaSemanaExistente = $this->retornarIndexDiaSemana($daysofweek, $diaSemanaExistente);
                                            if ($indexDiaSemanaExistente > $indexDiaSemanaArray) {
                                                $existeDiaDaSemanaAposAtual = true;
                                                break;
                                            }
                                        }
                                        if ($existeDiaDaSemanaAposAtual) {
                                            $diaSemana =  $diaSemanaExistente;
                                        }
                                        $dataInicioHorario->modify('next ' . $diaSemana);
                                        $dataFimHorario->modify('next ' . $diaSemana);
                                        $dateTimeInicio->modify('next ' . $diaSemana);
                                    } else {
                                        //mover o indice $i para a posicao do dia de inicio
                                        $diaSemana = $diaSemanaInicio;
                                        for ($dia = 0; $dia < $countEventos; $dia++) {
                                            $diaSemana_ = $this->retornarDiaSemanaIngles($eventos[$dia]['dia']);
                                            if ($diaSemanaInicio == $diaSemana_) {
                                                $i = $dia;
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $primeiroHorario = DateTime::createFromFormat('Y-m-d H:i:s', $dataInicioHorario->format('Y-m-d') . ' ' . $eventos[$i]['horario_inicio'] . ':00');
                                    $horarioAtual = new DateTime('now');
                                    if ($horarioAtual > $primeiroHorario) {
                                        //verificar se existe algum dia da semana requisitado após o dia atual
                                        $existeDiaDaSemanaAposAtual = false;
                                        $diaSemanaExistente = $diaSemana;
                                        $indexDiaSemanaArray = $this->retornarIndexDiaSemana($daysofweek, $diaSemana);
                                        for ($dia = 0; $dia < $countEventos; $dia++) {
                                            $diaSemanaExistente = $this->retornarDiaSemanaIngles($eventos[$dia]['dia']);
                                            $indexDiaSemanaExistente = $this->retornarIndexDiaSemana($daysofweek, $diaSemanaExistente);
                                            if ($indexDiaSemanaExistente > $indexDiaSemanaArray) {
                                                $existeDiaDaSemanaAposAtual = true;
                                                break;
                                            }
                                        }
                                        if ($existeDiaDaSemanaAposAtual) {
                                            $diaSemana =  $diaSemanaExistente;
                                        }
                                        $dataInicioHorario->modify('next ' . $diaSemana);
                                        $dataFimHorario->modify('next ' . $diaSemana);
                                        $dateTimeInicio->modify('next ' . $diaSemana);
                                    }
                                }
                            }

                            //gerar evento
                            $firstRecord = false;
                            $strDataInicio = $dataInicioHorario->format('Y-m-d') . ' ' . $eventos[$i]['horario_inicio'] . ':00';
                            $strDataFim = $dataFimHorario->format('Y-m-d') . ' ' . $eventos[$i]['horario_termino'] . ':00';

                            $dataEvento = array();
                            $dataEvento['descricao'] = '';
                            $dataEvento['data_inicio'] = $strDataInicio;
                            $dataEvento['data_fim'] = $strDataFim;
                            $dataEvento['allDay'] = 0;
                            $dataEvento['id_agenda'] = $idAgenda;
                            $dataEvento['id_paciente'] = $data['Recebimento']['id_paciente'];
                            $dataEvento['id_evento_status'] = $fixo == 0 ? Evento::$STATUS_AGUARDANDO : Evento::$STATUS_COMPARECEU;
                            $dataEvento['id_plano_sessao'] = isset($dadosRecebimento['id_plano_sessao']) ? $data['Recebimento']['id_plano_sessao'] : null;
                            $dataEvento['id_recebimento'] = $idRecebimento;
                            $dataEvento['repetir'] = 0;

                            $diaAnterior = $diaSemana;

                            if ($tipoConflito == 1) {
                                //Permitir eventos no mesmo horário
                                $isValid = $evento->validarEvento($dataEvento);
                                if ($isValid == 'sucesso') { //validar horario que nao há nenhuma ativadade
                                    $evento->create();
                                    $evento->save($dataEvento);
                                    CakeLog::info('Evento gerado pelo recebimento => ' . json_encode($dataEvento));

                                    $eventoDisponibilidade->decremetarTotal($dataEvento['id_paciente'], $dataEvento['id_recebimento']);
                                    $k++;
                                } else {
                                    $dataConflito = DateTime::createFromFormat('Y-m-d H:i:s', $dataEvento['dataInicio']);
                                    $conflito .= $isValid . " em: " . $dataConflito->format('d/m/Y') . " ás " . $dataConflito->format('H:i') . "<br/>";
                                }
                            } else {
                                //adiar em caso de conflito
                                $isValid = $evento->validarEvento($dataEvento);
                                if ($isValid == 'sucesso') { //validar horario que nao há nenhuma ativadade
                                    $isValid = $evento->validarHorarioRepetido($dataEvento); //validar se já existe evento nesse horario
                                    if ($isValid == 'sucesso') {
                                        $evento->create();
                                        $evento->save($dataEvento);
                                        CakeLog::info('Evento gerado pelo recebimento => ' . json_encode($dataEvento));

                                        $eventoDisponibilidade->decremetarTotal($dataEvento['id_paciente'], $dataEvento['id_recebimento']);
                                        $k++;
                                    } else {
                                        $dataConflito = DateTime::createFromFormat('Y-m-d H:i:s', $dataEvento['data_inicio']);
                                        $conflito .= $isValid . " em: " . $dataConflito->format('d/m/Y') . " ás " . $dataConflito->format('H:i') . "<br/>";
                                    }
                                } else {
                                    $dataConflito = DateTime::createFromFormat('Y-m-d H:i:s', $dataEvento['data_inicio']);
                                    $conflito .= $isValid . " em: " . $dataConflito->format('d/m/Y') . " ás " . $dataConflito->format('H:i') . "<br/>";
                                }
                            }
                        }
                    }
                }

                if ($conflito == "") {
                    $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
                } else {
                    $this->Session->setFlash(__("Recebimento cadastrado com sucesso. Porém alguns eventos foram adiados."), 'alerta');
                }

                return $this->redirect(array("controller" => "recebimento", "action" => "index", $idRecebimento));
            } else {
                $this->Session->setFlash(__("Não foi possivel completar o cadastro"), 'erro');
                return $this->redirect(array("controller" => "recebimento", "action" => "index"));
            }
        }
        if ($this->request->is("get")) {
            $centroCusto = new CentroCustos();
            $paciente = new Paciente();
            $clinica = new Clinica();
            $plano_sessao = new PlanoSessao();
            $banco = new Banco();
            $caixaLoja = new CaixaLoja();
            $profissional = new Profissional();
            $categoriaAula = new CategoriaAula();

            $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;

            $custos_ = $centroCusto->retornarTodosReceitas();
            $pacientes_ = $paciente->retornarTodos();
            $clinicas_ = $clinica->retornarTodos(" and c.idclinica = {$idClinica} ");
            $plano_sessoes_ = $plano_sessao->retornarTodos();
            $bancos_ = $banco->retornarTodos();
            $caixas_ = $caixaLoja->retornarTodos();
            $profissionais = $profissional->retornarTodos();
            $categorias = $categoriaAula->retornarTodos();

            $this->set('Pacientes', $pacientes_);
            $this->set('Clinicas', $clinicas_);
            $this->set('PlanoSessoes', $plano_sessoes_);
            $this->set('Bancos', $bancos_);
            $this->set('Caixas', $caixas_);
            $this->set('CentroCustos', $custos_);
            $this->set("Profissionais", $profissionais);
            $this->set("categorias", $categorias);
        }
    }

    public function alterar($idrecebimento)
    {
        if ($this->request->is("post")) {
            $data = $this->request->data;
            $recebimento = new Recebimento();

            //salvar            
            if ($recebimento->alterar($data['Recebimento'])) {
                return $this->redirect(array("controller" => "recebimento", "action" => "gestao", $data['Recebimento']['idrecebimento']));
            } else {
                $this->Session->setFlash(__("Não foi possivel completar o cadastro"), 'erro');
                return $this->redirect(array("controller" => "recebimento", "action" => "gestao", $data['Recebimento']['idrecebimento']));
            }
        }
        if ($this->request->is("get")) {
            $centroCusto = new CentroCustos();
            $profissional = new Profissional();
            $categoriaAula = new CategoriaAula();
            $recebimento = new Recebimento();

            $dadosRecebimento = $recebimento->retornarDetalhadoPorId($idrecebimento);
            $custos_ = $centroCusto->retornarTodos();
            $profissionais = $profissional->retornarTodos();
            $categorias = $categoriaAula->retornarTodos();

            $this->set('CentroCustos', $custos_);
            $this->set("Profissionais", $profissionais);
            $this->set("categorias", $categorias);
            $this->set("dadosRecebimento", $dadosRecebimento);
        }
    }

    public function excluir()
    {
        $idrecebimento = $this->request->data['idrecebimento'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idrecebimento)) {
                $recebimento = new Recebimento();
                $recebimento->excluir($idrecebimento, $this->_idDaSessao);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "recebimento", "action" => "index"));
    }

    public function gestao($idrecebimento)
    {
        if (isset($idrecebimento)) {
            $search = (isset($this->request->query["search"])) ? $this->request->query["search"] : "";
            CakeSession::write("textSearch", $search);

            $caixaLoja = new CaixaLoja();
            $tipoFinanceiro = new TipoFinanceiro();
            $recebimento = new Recebimento();

            $eventoDisponibilidade = new EventoDisponibilidade();

            $dadosRecebimento = $recebimento->retornarDetalhadoPorId($idrecebimento);
            $dadosDisponibilidade = $eventoDisponibilidade->retornarPorIdRecebimento($dadosRecebimento['r']['idrecebimento'], $dadosRecebimento['r']['id_paciente']);

            $caixas_ = $caixaLoja->retornarTodos();
            $tipoFinanceiros_ = $tipoFinanceiro->retornarTodosPagaveis();

            $this->set("idrecebimento", $idrecebimento);
            $this->set('Caixas', $caixas_);
            $this->set('TipoFinanceiros', $tipoFinanceiros_);
            $this->set('dadosRecebimento', $dadosRecebimento);
            $this->set('dadosDisponibilidade', $dadosDisponibilidade);
        } else {
            return $this->redirect(array("controller" => "recebimento", "action" => "index"));
        }
    }

    public function ajax_gerar_financeiro()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idpaciente = isset($this->request->data["idpaciente"]) ? $this->request->data["idpaciente"] : null;
        $idplanosessao = isset($this->request->data["idplanosessao"]) ? $this->request->data["idplanosessao"] : null;
        $datacompetencia = isset($this->request->data["datacompetencia"]) ? $this->request->data["datacompetencia"] : null;
        $recebimentotipo = isset($this->request->data["recebimentotipo"]) ? $this->request->data["recebimentotipo"] : null;
        $recebimentoValor = isset($this->request->data["recebimentovalor"]) ? $this->request->data["recebimentovalor"] : null;
        $recebimentoQtdParcela = isset($this->request->data["recebimentoqtdparcela"]) ? $this->request->data["recebimentoqtdparcela"] : null;
        $recebimentoValorReferente = isset($this->request->data["recebimentovalorreferente"]) ? $this->request->data["recebimentovalorreferente"] : null;

        if (isset($idplanosessao) && isset($datacompetencia)) {
            $planoSessao = new PlanoSessao();
            $tipoFinanceiro = new TipoFinanceiro();
            $contaBancaria = new ContaBancaria();

            $numeroParcelas = 0;
            $valor_total = 0;
            $valorParcela = 0;

            if ($recebimentoValor) {
                $str_explode = explode(',', $recebimentoValor);
                $recebimentoValor = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];
            }

            if ($recebimentotipo == 'PLANO') {
                $planoSessao_ = $planoSessao->retornarPorId($idplanosessao);
                $numeroParcelas = $planoSessao_['quantidade_meses'];
                $valor_total = $planoSessao_['valor'];
                $valorParcela = $valor_total / $numeroParcelas;
            } else if ($recebimentotipo == 'COMUM') {
                if ($recebimentoValorReferente == 'TOTAL') {
                    $numeroParcelas = $recebimentoQtdParcela;
                    $valor_total = $recebimentoValor;
                    $valorParcela = $valor_total / $numeroParcelas;
                } else if ($recebimentoValorReferente == 'PARCELA') {
                    $numeroParcelas = $recebimentoQtdParcela;
                    $valorParcela = $recebimentoValor;
                    $valor_total = $valorParcela * $numeroParcelas;
                }
            }

            $dateTimeCompetencia = DateTime::createFromFormat('d/m/Y', $datacompetencia);

            $dadosTabela = "";

            $financeiros_ = $tipoFinanceiro->retornarTodos();
            $contas = $contaBancaria->retornarPorIdPaciente($idpaciente);

            $dadosTabela .= "
                    <div>
                        <button class='btn btn-flat btn-warning' type='button' onclick='showModalCadastroConta()'>Cadastrar conta</button>
                        &nbsp;&nbsp;
                        <label>
                            <input type='checkbox' id='checkMostrarModal' checked/>
                            Perguntar se deseja replicar alterações no financeiro.
                        </label>
                        <br/><br/>
                    </div>
                    <table id='tabela-financeiros' class='table table-responsive table-bordered'>
                        <thead>
                            <tr>
                                <th class='text-center'>Parcela</th>
                                <th class='text-center'>Valor</th>
                                <th class='text-center'>Financeiro</th>
                                <th class='text-center'>Vencimento</th>
                                <th class='text-center' >Conta bancária</th>
                                <th class='text-center' >Nº cheque</th>
                                <th class='text-center' >N.S.U</th>
                            </tr>
                        </thead>
                        <tbody>";
            for ($i = 0; $i < $numeroParcelas; $i++) {
                $selectContas = "<select data-posicao='$i' name='Financeiro[$i][id_conta_bancaria]' disabled='true' class='form-control combos-contas-f d-conta-$i'>
                                    <option value='0'>Nenhuma</option>";
                $total_pl = count($contas);
                if ($total_pl > 0) {
                    for ($ip = 0; $ip < $total_pl; $ip++) {
                        $selectContas .= "<option value='" . $contas[$ip]["cb"]["idcontabancaria"] . "' >" . $contas[$ip]["b"]["descricao"] . ' - ' . $contas[$ip]["cb"]["agencia"] . '/' . $contas[$ip]["cb"]["conta"] . "</option>";
                    }
                }
                $selectContas .= "</select>";

                $selectFianceiros = "<select data-posicao='$i' name='Financeiro[$i][id_financeiro_tipo]' class='form-control combos-financeiro'>";
                $tc = count($financeiros_);
                if ($tc > 0) {
                    for ($ic = 0; $ic < $tc; $ic++) {
                        $selected = $financeiros_[$ic]["t"]["tipo_calculo"] == 'dinheiro' ? 'selected' : '';
                        $selectFianceiros .= "<option data-calculo='{$financeiros_[$ic]["t"]["tipo_calculo"]}' data-chave='$i' value='{$financeiros_[$ic]["t"]["idfinanceirotipo"]}' $selected> {$financeiros_[$ic]["t"]["tipo"]} </option>";
                    }
                }
                $selectFianceiros .= "</select>";

                $parcela = $i + 1;
                $dadosTabela .= "
                                <tr>                                    
                                    <td class='text-center'>$parcela <input type='hidden' value='$parcela' name='Financeiro[$i][parcela]' /></td>
                                    <td class='text-center'>R$ " . number_format($valorParcela, 2, ",", ".") . " <input type='hidden' value='$valorParcela' name='Financeiro[$i][valor]' /> </td>
                                    <td class='text-center'>" . $selectFianceiros . "</td>
                                    <td class='text-center'>{$dateTimeCompetencia->format('d/m/Y')} <input type='hidden' value='{$dateTimeCompetencia->format('Y-m-d H:i:s')}' name='Financeiro[$i][data_vencimento]' /></td>
                                    <td class='text-center'>" . $selectContas . "</td>
                                    <td class='text-center' ><input class='d-ncheque-$i input-cheques' data-posicao='$i' disabled='true' type='number' name='Financeiro[$i][num_cheque]'/> <input type='hidden' value='0' name='Financeiro[$i][cheque]' /></td>
                                    <td class='text-center' ><input class='d-nsu-$i norequired' disabled='true' type='number' name='Financeiro[$i][ndu]'/> </td>
                                </tr>";
                $dateTimeCompetencia->modify('+1 month');
            }
            $dadosTabela .= "
                        </tbody>
                    </table>";

            $this->response->body($dadosTabela);
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
        $situacaoRecebimento = (isset($this->request->data["situacao"])) ? $this->request->data["situacao"] : 'ABERTO';
        $parcelasFinalizar = (isset($this->request->data["parcelas_finalizar"])) ? $this->request->data["parcelas_finalizar"] : '';

        $filtros['situacao_recebimento'] = $situacaoRecebimento;
        $filtros['parcelas_finalizar'] = $parcelasFinalizar;

        $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
        $content = new Recebimento();
        $contents = $content->listarJQuery($search, $start, $length, $ordenacao, $idClinica, $filtros);

        $dados = array();
        if (isset($contents)) {
            foreach ($contents as $_content) {
                //$_content['p']["data_nascimento"] = date('d/m/Y', strtotime($_content['p']['data_nascimento']));                
                $_content['r']['valor'] = 'R$ ' . number_format($_content['r']['valor'], 2, ",", ".");
                $_content['r']['parcelas_pagas'] = $_content[0]['parcelas_pagas'];
                $_content['x']['data_vencimento'] = date('d/m/Y', strtotime($_content[0]['data_vencimento']));
                $dados[] = $_content;
            }
        }

        $this->response->body(json_encode(
            array(
                "draw" => $draw,
                "recordsTotal" => (int) $content->totalRegistro($idClinica),
                "recordsFiltered" => (int) $content->totalRegistroFiltrado($search, $idClinica, $filtros),
                "data" => $dados
            )
        ));
    }

    public function ajax_gestao()
    {
        $this->layout = null;
        $this->autoRender = false;
        $idrecebimento = (isset($this->request->query["idrecebimento"])) ? $this->request->query["idrecebimento"] : null;
        if (isset($idrecebimento)) {
            $financeiro = new Financeiro();
            $financeiros = $financeiro->listarGestaoRecebimentoJQuery($idrecebimento);

            $dados = array();
            if (isset($financeiros)) {
                foreach ($financeiros as $_content) {
                    $_content['financeiro']['valor'] = 'R$ ' . number_format($_content['financeiro']['valor'], 2, ",", ".");
                    $_content['financeiro']['data_vencimento'] = date('d/m/Y', strtotime($_content['financeiro']['data_vencimento']));
                    $dados[] = $_content;
                }
            }
            $this->response->body(json_encode(array("dados" => $dados)));
        }
    }

    public function ajax_gestao_totais()
    {
        $this->layout = null;
        $this->autoRender = false;
        $idrecebimento = (isset($this->request->query["idrecebimento"])) ? $this->request->query["idrecebimento"] : null;
        if (isset($idrecebimento)) {
            $financeiro = new Financeiro();
            $totalGeral = $financeiro->totalFinanceiroPorRecebimento($idrecebimento);
            $totalPago = $financeiro->totalFinanceiroPagoPorRecebimento($idrecebimento);
            $totalNaoPago = $financeiro->totalFinanceiroNaoPagoPorRecebimento($idrecebimento);
            $dados = array();
            $dados['total_geral'] = isset($totalGeral[0][0]['total']) ? 'R$ ' . number_format($totalGeral[0][0]['total'], 2, ",", ".") : 'R$ 0,00';
            $dados['total_pago'] = isset($totalPago[0][0]['total']) ? 'R$ ' . number_format($totalPago[0][0]['total'], 2, ",", ".") : 'R$ 0,00';
            $dados['total_nao_pago'] = isset($totalNaoPago[0][0]['total']) ? 'R$ ' . number_format($totalNaoPago[0][0]['total'], 2, ",", ".") : 'R$ 0,00';
            $this->response->body(json_encode(array("dados" => $dados)));
        }
    }

    public function ajax_parcela_detalhes()
    {
        $this->layout = null;
        $this->autoRender = false;
        $idfinanceiro = (isset($this->request->query["idfinanceiro"])) ? $this->request->query["idfinanceiro"] : null;
        if (isset($idfinanceiro)) {
            $financeiro = new Financeiro();
            $dados = $financeiro->retornarDetalhesRecebimento($idfinanceiro);

            $financeiro_ = $dados[0];
            $financeiro_['financeiro']['valor'] = number_format($financeiro_['financeiro']['valor'], 2, ",", ".");
            $financeiro_['financeiro']['data_vencimento'] = date('d/m/Y', strtotime($financeiro_['financeiro']['data_vencimento']));
            $financeiro_['financeiro']['data_pagamento'] = date('d/m/Y', strtotime($financeiro_['financeiro']['data_pagamento']));
            $financeiro_['usuario']['nome'] = isset($financeiro_['usuario']['nome']) ? $financeiro_['usuario']['nome'] : "";
            $financeiro_['usuario']['sobrenome'] = isset($financeiro_['usuario']['sobrenome']) ? $financeiro_['usuario']['sobrenome'] : "";
            $this->response->body(json_encode($financeiro_));
        }
    }

    public function receber_parcela()
    {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;
            //executar pagamento
            $financeiro = new Financeiro();
            $financeiro->receberParcela($data['idrecebimento'], $this->_idDaSessao, $data['caixa_loja'], $data['idfinanceiro'], $data['tipo_financeiro'], "Recebimento de parcela");
        }
    }

    public function alterar_parcela()
    {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;
            //executar alterar
            $str_explode = explode(',', $data['valor']);
            $data['valor'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];

            $str_explode_data = explode('/', $data['data_vencimento']);
            $data['data_vencimento'] = $str_explode_data[2] . '-' . $str_explode_data[1] . '-' . $str_explode_data[0];

            $financeiro = new Financeiro();
            $financeiro->alterarParcelaRecebimento($data['idrecebimento'], $data['idfinanceiro'], $data['valor'], $data['data_vencimento'], $data['motivo'], $this->_idDaSessao);
        }
    }

    public function excluir_parcela()
    {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;
            //executar excluir
            $financeiro = new Financeiro();
            $financeiro->excluirParcelaRecebimento($data['idrecebimento'], $data['idfinanceiro'], $data['motivo'], $this->_idDaSessao);
        }
    }

    public function inserir_parcela()
    {
        $this->layout = null;
        $this->autoRender = false;
        if (isset($this->request->data)) {
            $data = $this->request->data;

            $str_explode = explode(',', $data['valor']);
            $data['valor'] = str_replace('.', '', $str_explode[0]) . '.' . $str_explode[1];

            $str_explode_data = explode('/', $data['data_vencimento']);
            $data['data_vencimento'] = $str_explode_data[2] . '-' . $str_explode_data[1] . '-' . $str_explode_data[0];

            $recebimento = new Recebimento();
            $recebimentoArray = $recebimento->retornarPorId($data['idrecebimento']);

            $financeiro = new Financeiro();
            $financeiro->create();
            $dados['id_recebimento'] = $recebimentoArray['idrecebimento'];
            $dados['valor'] = $data['valor'];
            $dados['data_vencimento'] = $data['data_vencimento'];
            $dados['id_financeiro_tipo'] = $data['id_financeiro_tipo'];
            $dados['total_parcela'] = $recebimentoArray['quantidade_parcela'] + 1;
            $dados['parcela'] = $recebimentoArray['quantidade_parcela'] + 1;
            $dados['pago'] = 0;
            $dados['convertido'] = 0;
            $dados['id_caixa_loja'] = $recebimentoArray['id_caixa_loja'];
            $dados['id_usuario'] = $this->_idDaSessao;
            $financeiro->save($dados);


            $recebimentoArray['quantidade_sessoes'] = $recebimentoArray['quantidade_sessoes'] + $data['total_disponibilidade'];
            $recebimento->atualizarRecebimento($recebimentoArray['idrecebimento'], $recebimentoArray['quantidade_sessoes']);

            $eventoDisponibilidade = new EventoDisponibilidade();
            $eventoDisponibilidade->updateTotal($data['ideventodisponibilidade'], $data['total_disponibilidade']);

            $dadosDisponibilidade = $eventoDisponibilidade->retornarPorIdRecebimento($recebimentoArray['idrecebimento'], $recebimentoArray['id_paciente']);
            $this->response->body(json_encode($dadosDisponibilidade));
        }
    }

    public function viewpdf($idrecebimento)
    {
        $this->layout = 'default';
        if ($this->request->is("get")) {
            if (isset($idrecebimento)) {
                ini_set('memory_limit', '512M');
                $paciente = new Paciente();
                $dataPaciente = $paciente->relatorioPacientePorRecebimentoPDF($idrecebimento);
                $this->set("Eventos", $dataPaciente);
            } else {
                return $this->redirect(array("controller" => "recebimento", "action" => "index"));
            }
        }
    }

    public function ajax_recebimentos_paciente()
    {
        $this->layout = null;
        $this->autoRender = false;
        $id_paciente = (isset($this->request->query["id_paciente"])) ? $this->request->query["id_paciente"] : null;
        $page = (isset($this->request->query["page"])) ? $this->request->query["page"] : null;
        $search = (isset($this->request->query["search"])) ? $this->request->query["search"] : null;
        $page_size = 25;
        $current = ($page * $page_size) - $page_size;

        if (isset($id_paciente)) {
            $idClinica = (isset($this->_dados['id_clinica'])) ? $this->_dados['id_clinica'] : 1;
            $recebimento = new Recebimento();
            $recebimentos = $recebimento->listarRecebimentosPaciente($idClinica, $id_paciente, $search, $current, $page_size);

            $dados = array();
            if (isset($recebimentos)) {
                foreach ($recebimentos as $_content) {
                    $row['id'] = (int) $_content['r']['idrecebimento'];
                    $row['text'] = $_content['r']['idrecebimento'] . ' - ' . $_content['r']['descricao'];;
                    $dados[] = $row;
                }
            }
            $this->response->body(json_encode([
                "results" => $dados,
                "pagination" => ["more" => count($dados) < $page_size ? false : true]
            ]));
        } else {
            $this->response->body(json_encode([
                "results" => [],
                "pagination" => ["more" => false]
            ]));
        }
    }

    public function viewpdf_contrato($idrecebimento = null)
    {
        $this->layout = 'default';
        if ($this->request->is("get")) {
            if (isset($idrecebimento)) {
                ini_set('memory_limit', '512M');

                $recebimento = new Recebimento();
                $endereco = new Endereco();

                $dataRecebimento = $recebimento->retornarPorIdComPaciente($idrecebimento);
                $dataEndereco = $endereco->retornarPorPaciente($dataRecebimento['paciente']['idpaciente']);
                $this->set("recebimento", $dataRecebimento);
                $this->set("endereco", $dataEndereco);
            } else {
                return $this->redirect(array("controller" => "recebimento", "action" => "index"));
            }
        }
    }
}
