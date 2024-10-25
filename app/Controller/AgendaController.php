<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AuthController', 'Controller');
App::uses('Profissional', 'Model');
App::uses('Evento', 'Model');
App::uses('Agenda', 'Model');
App::uses('Paciente', 'Model');
App::uses('PlanoSessao', 'Model');
App::uses('EventoDisponibilidade', 'Model');
App::uses('Acesso', 'Model');
App::uses('Recebimento', 'Model');
App::uses('FichaFisioterapia', 'Model');
App::uses('AcompanhamentoFisioterapia', 'Model');
App::uses('AulaExperimental', 'Model');

/**
 * CakePHP AgendaController
 * @author Felipe
 */
class AgendaController extends AuthController
{

    public $azul = "#158cba";
    public $verde = "#28b62c";
    public $vermelho = "#ff4136";
    public $laranja = "#ff851b";
    public $roxo = "#9e00a6";
    public $azul_escuro = "#001f3f";
    public $cinza = "#cecece";
    public $cyan = "#607D8B";
    public $components = array('RequestHandler');

    public function index()
    {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_LISTAR_AGENDA);
    }

    public function cadastrar()
    {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_CADASTRO_AGENDA);
        if ($this->request->is("get")) {
            $profissional = new Profissional();
            $profissionais = $profissional->retornarTodos();
            $this->set("Profissionais", $profissionais);
        }
        if ($this->request->is("post")) {
            $this->layout = null;
            $this->autoRender = false;
            $data = $this->request->data;
            $agenda = new Agenda();
            $agenda->save($data);
            $this->Session->setFlash(__("Cadastro concluído com sucesso!"), 'sucesso');
            return $this->redirect(array("controller" => "agenda", "action" => "index"));
        }
    }

    public function excluir()
    {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_EXCLUIR_AGENDA);
        $idagenda = $this->request->data['idagenda'];
        $this->autoRender = false;
        $this->layout = null;
        if ($this->request->is("post")) {
            if (isset($idagenda)) {
                $agenda = new Agenda();
                $agenda->excluir($idagenda);
                $this->Session->setFlash(__("Exluído com sucesso!"), 'sucesso');
            }
        }
        return $this->redirect(array("controller" => "agenda", "action" => "index"));
    }

    public function selecionada($idagenda)
    {
        $this->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_SELECIONAR_AGENDA);
        $profissional = new Profissional();
        $agenda = new Agenda();
        $paciente = new Paciente();
        if ($this->request->is("get")) {
            if (isset($idagenda)) {
                date_default_timezone_set('America/Sao_Paulo');
                $data = date("d/m/Y");
                $agendaData = $agenda->retornarPorId($idagenda);
                $profissionalData = $profissional->retornarPorId($agendaData['id_profissional']);
                $pacienteData = $paciente->retornarTodos();
                $pacienteExcluirEventosData = $paciente->retornarTodosExcluirEvento();

                $this->set("DataAtual", $data);
                $this->set("Agenda", $agendaData);
                $this->set("Profissional", $profissionalData);
                $this->set("Pacientes", $pacienteData);
                $this->set("PacientesExcluirEventos", $pacienteExcluirEventosData);
            } else {
                return $this->redirect(array("controller" => "agenda", "action" => "index"));
            }
        }
    }

    //Eventos Ajax
    public function ajax_evento_salvar()
    {
        $acesso = new Acesso();

        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $eventoDisponibilidade = new EventoDisponibilidade();
        $dados = $this->request->data;
        $dataEvento = isset($dados['Evento']) ? $dados['Evento'] : null;
        $dataConfiguracao = isset($dados['Configuracao']) ? $dados['Configuracao'] : null;

        if (isset($dataEvento)) {
            $dataEvento['data_inicio'] = $dataEvento['data_inicio'] . ' ' . $dataEvento['hora_inicio'];
            $dataEvento['data_fim'] = $dataEvento['data_fim'] . ' ' . $dataEvento['hora_fim'];
            $dataEvento['data_fim_repetir'] = $dataEvento['data_fim_repetir'] . ' ' . $dataEvento['hora_fim_repetir'];

            unset($dataEvento['hora_inicio']);
            unset($dataEvento['hora_fim']);
            unset($dataEvento['hora_fim_repetir']);
        }

        if ($acesso->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_ADICIONAR_EVENTO)) {

            if (isset($dataEvento)) {
                unset($dataEvento['visao']);

                $dateTimeInicio = DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_inicio'] . ':00');
                $dateTimeFim = DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_fim'] . ':00');
                $dateTimeFimRepetir = isset($dataEvento['data_fim_repetir']) ? DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_fim_repetir'] . ':00') : null;

                $dataEvento['data_inicio'] = $dateTimeInicio->format('Y-m-d H:i:s');
                $dataEvento['data_fim'] = $dateTimeFim->format('Y-m-d H:i:s');
                $dataEvento['allday'] = 0;
                //$dataEvento['id_evento_status'] = $dataEvento['indisponivel'] == 0 ? Evento::$STATUS_AGUARDANDO : Evento::$STATUS_INDISPONIVEL;

                if (!isset($dataConfiguracao['configurarEvento'])) {
                    $dataConfiguracao['configurarEvento'] = 0;
                }

                if ($dataEvento['indisponivel'] == 0) {
                    if ($dataEvento['fixo'] == 0) {
                        $dataEvento['id_evento_status'] = Evento::$STATUS_AGUARDANDO;
                    } else {
                        $dataEvento['id_evento_status'] = Evento::$STATUS_COMPARECEU;
                    }
                } else {
                    $dataEvento['id_evento_status'] = Evento::$STATUS_INDISPONIVEL;
                }

                unset($dataEvento['indisponivel']);
                unset($dataEvento['fixo']);

                if ($dataEvento['id_paciente'] == 0) {
                    unset($dataEvento['id_paciente']);
                    unset($dataEvento['id_recebimento']);
                }

                if ((int) $dataEvento['repetir'] == 0) {
                    unset($dataEvento['data_fim_repetir']);
                    unset($dataEvento['tipo_repetir']);
                } elseif (isset($dateTimeFimRepetir)) {
                    if ($dateTimeFimRepetir > $dateTimeFim) {
                        $dataEvento['data_fim_repetir'] = $dateTimeFimRepetir->format('Y-m-d H:i:s');
                        //validar datas
                    } else {
                        $dataEvento['repetir'] = 0;
                        unset($dataEvento['data_fim_repetir']);
                        unset($dataEvento['tipo_repetir']);
                    }

                    if ($dataConfiguracao['configurarEvento'] == 1) {
                        $dataEvento['repetir'] = 0;
                        unset($dataEvento['data_fim_repetir']);
                        unset($dataEvento['tipo_repetir']);
                    }
                }

                if ($dateTimeFim > $dateTimeInicio) {
                    if ($dataConfiguracao['configurarEvento'] == 1) {
                        //gerar eventos na agenda      
                        $recebimento = new Recebimento();
                        $eventos = $dataConfiguracao['eventos'];
                        $size = count($eventos);
                        $size = $size - 1; //diminiu um por que o ultimo registro é sempre em branco
                        $conflito = "";
                        $totalCadastro = 0;
                        $tipoConflito = $dataConfiguracao['conflito'];
                        $dataInicio = $dataConfiguracao['data_inicio_eventos'];
                        $fixo = $dataConfiguracao['fixo'];
                        $idAgenda = $dataEvento['id_agenda'];
                        $idRecebimento = $dataEvento['id_recebimento'];
                        $idPaciente = $dataEvento['id_paciente'];

                        if ($size > 0 && $idAgenda > 0 && $dataInicio != "") {
                            $eventoDisponibilidade = new EventoDisponibilidade();
                            $dadosEventoDisponibilidade = $eventoDisponibilidade->retornarPorIdRecebimento($idRecebimento, $idPaciente);
                            $dadosRecebimento = $recebimento->retornarPorId($idRecebimento);

                            $daysofweek = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
                            $totalDisponivel = $dadosEventoDisponibilidade['total'];
                            $dateTimeInicio = DateTime::createFromFormat('d/m/Y', $dataInicio);
                            $qtde_sessoes = $dadosRecebimento['quantidade_sessoes'];
                            $diaAnterior = $daysofweek[date('w')];

                            $arrayEventos = array();

                            for ($k = 0; $k < $totalDisponivel;) {
                                for ($i = 0; $i < $size; $i++) {
                                    if ($k >= $totalDisponivel) {
                                        break;
                                    }
                                    $dataInicioHorario = DateTime::createFromFormat('d/m/Y', $dateTimeInicio->format('d/m/Y'));
                                    $dataFimHorario = DateTime::createFromFormat('d/m/Y', $dateTimeInicio->format('d/m/Y'));

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

                                    $strDayOfWeek = $daysofweek[date('w')];

                                    if (!($dateTimeInicio->format('Y-m-d') == date('Y-m-d') && $diaSemana == $strDayOfWeek)) {
                                        if ($diaAnterior != $diaSemana) {
                                            $dataInicioHorario->modify('next ' . $diaSemana);
                                            $dataFimHorario->modify('next ' . $diaSemana);
                                            $dateTimeInicio->modify('next ' . $diaSemana);
                                        }
                                    }

                                    $strDataInicio = $dataInicioHorario->format('Y-m-d') . ' ' . $eventos[$i]['horario_inicio'] . ':00';
                                    $strDataFim = $dataFimHorario->format('Y-m-d') . ' ' . $eventos[$i]['horario_termino'] . ':00';

                                    $newEvento = array();
                                    $newEvento['descricao'] = '';
                                    $newEvento['data_inicio'] = $strDataInicio;
                                    $newEvento['data_fim'] = $strDataFim;
                                    $newEvento['allDay'] = 0;
                                    $newEvento['id_agenda'] = $idAgenda;
                                    $newEvento['id_paciente'] = $idPaciente;
                                    $newEvento['id_evento_status'] = $fixo == 0 ? Evento::$STATUS_AGUARDANDO : Evento::$STATUS_COMPARECEU;
                                    $newEvento['id_recebimento'] = $idRecebimento;
                                    $newEvento['repetir'] = 0;
                                    if (isset($dataEvento['id_plano_sessao'])) {
                                        $newEvento['id_plano_sessao'] = $dataEvento['id_plano_sessao'];
                                    }

                                    $diaAnterior = $diaSemana;

                                    if ($tipoConflito == 1) {
                                        //Permitir eventos no mesmo horário
                                        $isValid = $evento->validarEvento($newEvento);
                                        if ($isValid == 'sucesso') { //validar horario que nao há nenhuma ativadade
                                            $evento->create();
                                            $evento->save($newEvento);
                                            $eventoDisponibilidade->decremetarTotal($newEvento['id_paciente'], $newEvento['id_recebimento']);
                                            $k++;
                                            $totalCadastro++;
                                        } else {
                                            $dataConflito = DateTime::createFromFormat('Y-m-d H:i:s', $newEvento['dataInicio']);
                                            $conflito .= $isValid . " em: " . $dataConflito->format('d/m/Y') . " ás " . $dataConflito->format('H:i') . "<br/>";
                                        }
                                    } else {
                                        //adiar em caso de conflito
                                        $isValid = $evento->validarEvento($newEvento);
                                        if ($isValid == 'sucesso') { //validar horario que nao há nenhuma ativadade
                                            $isValid = $evento->validarHorarioRepetido($newEvento); //validar se já existe evento nesse horario
                                            if ($isValid == 'sucesso') {
                                                $evento->create();
                                                $evento->save($newEvento);
                                                $eventoDisponibilidade->decremetarTotal($newEvento['id_paciente'], $newEvento['id_recebimento']);
                                                $k++;
                                                $totalCadastro++;
                                            } else {
                                                $dataConflito = DateTime::createFromFormat('Y-m-d H:i:s', $newEvento['data_inicio']);
                                                $conflito .= $isValid . " em: " . $dataConflito->format('d/m/Y') . " ás " . $dataConflito->format('H:i') . "<br/>";
                                            }
                                        } else {
                                            $dataConflito = DateTime::createFromFormat('Y-m-d H:i:s', $newEvento['data_inicio']);
                                            $conflito .= $isValid . " em: " . $dataConflito->format('d/m/Y') . " ás " . $dataConflito->format('H:i') . "<br/>";
                                        }
                                    }
                                }
                            }
                        }

                        if ($conflito == "") {
                            $dataEvento['repetir'] = 1;
                            $dataEvento['mensagem'] = "Foram gerados $totalCadastro registros no período selecionado.";
                            $dataEvento['result'] = true;
                        } else {
                            $dataEvento['repetir'] = 1;
                            $dataEvento['mensagem'] = "Foram gerados $totalCadastro registros no período selecionado. Porém alguns eventos foram adiados.";
                            $dataEvento['result'] = true;
                        }
                    } else if ((int) $dataEvento['repetir'] == 1) {
                        $dateTimeInicio = DateTime::createFromFormat('Y-m-d H:i:s', $dataEvento['data_inicio']);
                        $dateTimeFim = DateTime::createFromFormat('Y-m-d H:i:s', $dataEvento['data_fim']);
                        $dateTimeFimRepetir = DateTime::createFromFormat('Y-m-d H:i:s', $dataEvento['data_fim_repetir']);

                        $dadosEventoDisponibilidade = $eventoDisponibilidade->retornarPorIdRecebimento($dataEvento['id_recebimento'], $dataEvento['id_paciente']);
                        $totalDisponivel = $dadosEventoDisponibilidade['total'];
                        $totalCadastro = 0;
                        $conflito = "";

                        while ($dateTimeInicio <= $dateTimeFimRepetir) {
                            $totalCadastro++;
                            if ($totalCadastro > $totalDisponivel) {
                                break;
                            }
                            $dataEvento['data_inicio'] = $dateTimeInicio->format('Y-m-d H:i:s');
                            $dataEvento['data_fim'] = $dateTimeFim->format('Y-m-d H:i:s');
                            $isValid = $evento->validarEvento($dataEvento);
                            if ($isValid == 'sucesso') {
                                $evento->create();
                                $evento->save($dataEvento);
                                if ($dataEvento['id_evento_status'] != Evento::$STATUS_INDISPONIVEL) {
                                    $eventoDisponibilidade->decremetarTotal($dataEvento['id_paciente'], $dataEvento['id_recebimento']);
                                }

                                if ($dataEvento['tipo_repetir'] == 'dia') {
                                    $dateTimeInicio->modify('+1 day');
                                    $dateTimeFim->modify('+1 day');
                                } elseif ($dataEvento['tipo_repetir'] == 'semana') {
                                    $dateTimeInicio->modify('+1 week');
                                    $dateTimeFim->modify('+1 week');
                                } elseif ($dataEvento['tipo_repetir'] == 'mes') {
                                    $dateTimeInicio->modify('+1 month');
                                    $dateTimeFim->modify('+1 month');
                                } elseif ($dataEvento['tipo_repetir'] == 'ano') {
                                    $dateTimeInicio->modify('+1 year');
                                    $dateTimeFim->modify('+1 year');
                                }
                                $dataEvento['mensagem'] = "Foram gerados $totalCadastro registros no período selecionado.";
                                $dataEvento['result'] = true;
                            } else {
                                $totalCadastro--;
                                if ($dataEvento['tipo_repetir'] == 'dia') {
                                    $dateTimeInicio->modify('+1 day');
                                    $dateTimeFim->modify('+1 day');
                                } elseif ($dataEvento['tipo_repetir'] == 'semana') {
                                    $dateTimeInicio->modify('+1 week');
                                    $dateTimeFim->modify('+1 week');
                                } elseif ($dataEvento['tipo_repetir'] == 'mes') {
                                    $dateTimeInicio->modify('+1 month');
                                    $dateTimeFim->modify('+1 month');
                                } elseif ($dataEvento['tipo_repetir'] == 'ano') {
                                    $dateTimeInicio->modify('+1 year');
                                    $dateTimeFim->modify('+1 year');
                                }
                                $conflito .= $isValid . " em: " . $dateTimeInicio->format('d/m/Y') . " ás " . $dateTimeInicio->format('H:i') . "<br/>";
                            }
                        }
                        if (strlen($conflito) > 0) {
                            $dataEvento['conflito'] = $conflito;
                        }
                    } else {
                        $isValid = $evento->validarEvento($dataEvento);
                        if ($isValid == 'sucesso') {
                            $evento->create();
                            $evento->save($dataEvento);
                            if ($dataEvento['id_evento_status'] != Evento::$STATUS_INDISPONIVEL) {
                                $eventoDisponibilidade->decremetarTotal($dataEvento['id_paciente'], $dataEvento['id_recebimento']);
                            }

                            $dataEvento['idevento'] = $evento->id;
                            $dataEvento['data_inicio'] = str_replace(" ", "T", $dataEvento['data_inicio']);
                            $dataEvento['data_fim'] = str_replace(" ", "T", $dataEvento['data_fim']);
                            $dataEvento['textColor'] = '#ffffff';
                            if ($dataEvento['id_evento_status'] == Evento::$STATUS_AGUARDANDO) {
                                $dataEvento['color'] = $this->azul;
                            } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_NAO_COMPARECEU) {
                                $dataEvento['color'] = $this->vermelho;
                            } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_ADIADO) {
                                $dataEvento['color'] = $this->laranja;
                            } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_ADIADO_NOVAMENTE) {
                                $dataEvento['color'] = $this->roxo;
                            } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_COMPARECEU) {
                                $dataEvento['color'] = $this->verde;
                            } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_REPOSICAO) {
                                $dataEvento['color'] = $this->azul_escuro;
                            } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_INDISPONIVEL) {
                                $dataEvento['color'] = $this->cinza;
                            } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_EXPERIMENTAL) {
                                $dataEvento['color'] = $this->cyan;
                            }

                            if ($dataEvento['id_evento_status'] == Evento::$STATUS_INDISPONIVEL) {
                                $dataEvento['paciente']['nome'] = "";
                                $dataEvento['paciente']['sobrenome'] = "";
                                $dataEvento['description'] = "";
                            } else {
                                $paciente = new Paciente();
                                $dataEvento['paciente'] = $paciente->retornarPorIdSemFoto($dataEvento['id_paciente']);

                                if (isset($dataEvento['id_recebimento']) && $dataEvento['id_recebimento'] != null) {
                                    $recebimento = new Recebimento();
                                    $dataEvento['recebimento'] = $recebimento->retornarPorId($dataEvento['id_recebimento']);
                                }

                                $dataEvento['description'] = "<div style='padding-top: 5px; padding-left: 15px; padding-right: 15px;'> <p>Paciente: " . $dataEvento['paciente']['nome'] . " " . $dataEvento['paciente']['sobrenome'] . "</p>" .
                                    "<p>Email: " . $dataEvento['paciente']['email'] . "</p>" .
                                    "<p>Telefone: " . $dataEvento['paciente']['telefone_fixo'] . "</p>" .
                                    "<p>Celular: " . $dataEvento['paciente']['telefone_celular'] . "</p> " .
                                    "<p>Recebimento: " . (isset($dataEvento['recebimento']) ? ($dataEvento['recebimento']['idrecebimento'] . " - " . $dataEvento['recebimento']['descricao']) : "") . "</p> " .
                                    "</div>";
                            }
                            $dataEvento['result'] = true;
                        } else {
                            $dataEvento['result'] = false;
                            $dataEvento['error'] = $isValid;
                        }
                    }
                } else {
                    $dataEvento['result'] = false;
                    $dataEvento['error'] = 'A data de início é menor do que a data de fim.';
                }
            }
        } else {
            $dataEvento['result'] = false;
            $dataEvento['error'] = 'Você não tem permissão para adicionar eventos.';
        }

        $this->response->body(json_encode($dataEvento));
    }

    public function ajax_evento_editar()
    {
        $acesso = new Acesso();

        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $dados = $this->request->data;
        $dataEvento = isset($dados['Evento']) ? $dados['Evento'] : null;

        if (isset($dataEvento)) {
            $dataEvento['data_inicio'] = $dataEvento['data_inicio'] . ' ' . $dataEvento['hora_inicio'];
            $dataEvento['data_fim'] = $dataEvento['data_fim'] . ' ' . $dataEvento['hora_fim'];

            unset($dataEvento['hora_inicio']);
            unset($dataEvento['hora_fim']);
        }

        if ($acesso->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_ATUALIZAR_EVENTO)) {

            if (isset($dataEvento)) {
                $dateTimeInicio = DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_inicio'] . ':00');
                $dateTimeFim = DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_fim'] . ':00');

                $dataEvento['data_inicio'] = $dateTimeInicio->format('Y-m-d H:i:s');
                $dataEvento['data_fim'] = $dateTimeFim->format('Y-m-d H:i:s');

                if ($dateTimeFim > $dateTimeInicio) {
                    $isValid = $evento->validarEvento($dataEvento);
                    if ($isValid == 'sucesso') {
                        $evento->save($dataEvento);
                        $dataEvento['data_inicio'] = str_replace(" ", "T", $dataEvento['data_inicio']);
                        $dataEvento['data_fim'] = str_replace(" ", "T", $dataEvento['data_fim']);
                        $dataEvento['textColor'] = '#ffffff';
                        if ($dataEvento['id_evento_status'] == Evento::$STATUS_AGUARDANDO) {
                            $dataEvento['color'] = $this->azul;
                        } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_NAO_COMPARECEU) {
                            $dataEvento['color'] = $this->vermelho;
                        } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_ADIADO) {
                            $dataEvento['color'] = $this->laranja;
                        } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_ADIADO_NOVAMENTE) {
                            $dataEvento['color'] = $this->roxo;
                        } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_COMPARECEU) {
                            $dataEvento['color'] = $this->verde;
                        } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_REPOSICAO) {
                            $dataEvento['color'] = $this->azul_escuro;
                        } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_INDISPONIVEL) {
                            $dataEvento['color'] = $this->cinza;
                        } else if ($dataEvento['id_evento_status'] == Evento::$STATUS_EXPERIMENTAL) {
                            $dataEvento['color'] = $this->cyan;
                        }
                        if ($dataEvento['id_evento_status'] == Evento::$STATUS_INDISPONIVEL) {
                            $dataEvento['paciente']['nome'] = "";
                            $dataEvento['paciente']['sobrenome'] = "";
                            $dataEvento['description'] = "";
                        } else {
                            $paciente = new Paciente();
                            $dataEvento['paciente'] = $paciente->retornarPorIdSemFoto($dataEvento['id_paciente']);

                            if (isset($dataEvento['id_recebimento']) && $dataEvento['id_recebimento'] != null) {
                                $recebimento = new Recebimento();
                                $dataEvento['recebimento'] = $recebimento->retornarPorId($dataEvento['id_recebimento']);
                            }

                            $dataEvento['description'] = "<div style='padding-top: 5px; padding-left: 15px; padding-right: 15px;'> <p>Paciente: " . $dataEvento['paciente']['nome'] . " " . $dataEvento['paciente']['sobrenome'] . "</p>" .
                                "<p>Email: " . $dataEvento['paciente']['email'] . "</p>" .
                                "<p>Telefone: " . $dataEvento['paciente']['telefone_fixo'] . "</p>" .
                                "<p>Celular: " . $dataEvento['paciente']['telefone_celular'] . "</p> " .
                                "<p>Recebimento: " . (isset($dataEvento['recebimento']) ? ($dataEvento['recebimento']['idrecebimento'] . " - " . $dataEvento['recebimento']['descricao']) : "") . "</p> " .
                                "</div>";
                        }
                        $dataEvento['result'] = true;
                    } else {
                        $dataEvento['result'] = false;
                        $dataEvento['error'] = $isValid;
                    }
                } else {
                    $dataEvento['result'] = false;
                    $dataEvento['error'] = 'A data de início é menor do que a data de fim.';
                }
            }
        } else {
            $dataEvento['result'] = false;
            $dataEvento['error'] = 'Você não tem permissão para alterar eventos.';
        }
        $this->response->body(json_encode($dataEvento));
    }

    public function ajax_evento_excluir($idevento)
    {
        $acesso = new Acesso();

        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $eventoDisponibilidade = new EventoDisponibilidade();
        $dataEvento = array();

        if ($acesso->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_EXCLUIR_EVENTO)) {

            if (isset($idevento)) {
                $dadosEvento = $evento->retornarPorId($idevento);
                if ($dadosEvento['id_evento_status'] != Evento::$STATUS_INDISPONIVEL && $dadosEvento['id_evento_status'] != Evento::$STATUS_EXPERIMENTAL) {
                    $eventoDisponibilidade->incremetarTotal($dadosEvento['id_paciente'], $dadosEvento['id_recebimento']);
                }
                $evento->excluir($idevento);
                $dataEvento['result'] = true;
            } else {
                $dataEvento['result'] = false;
            }
        } else {
            $dataEvento['result'] = false;
            $dataEvento['error'] = 'Você não tem permissão para excluir eventos.';
        }
        $this->response->body(json_encode($dataEvento));
    }

    public function ajax_evento_retornar_id($idevento)
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $dataEvento = null;

        if (isset($idevento)) {
            $dataEvento = $evento->retornarPorId($idevento);
            $dataEvento['data_inicio'] = str_replace(" ", "T", $dataEvento['data_inicio']);
            $dataEvento['data_fim'] = str_replace(" ", "T", $dataEvento['data_fim']);
            $sli = strlen($dataEvento['data_inicio']) - 3;
            $slf = strlen($dataEvento['data_fim']) - 3;
            $dataEvento['data_inicio'] = substr($dataEvento['data_inicio'], 0, $sli);
            $dataEvento['data_fim'] = substr($dataEvento['data_fim'], 0, $slf);
            if (isset($dataEvento['id_paciente'])) {
                $paciente = new Paciente();
                $dataEvento['paciente'] = $paciente->retornarPorIdSemFoto($dataEvento['id_paciente']);
            } else {
                $dataEvento['paciente'] = 0;
            }

            if (isset($dataEvento['id_recebimento']) && $dataEvento['id_recebimento'] != null) {
                $recebimento = new Recebimento();
                $dataEvento['recebimento'] = $recebimento->retornarPorId($dataEvento['id_recebimento']);
            } else {
                $dataEvento['recebimento'] = null;
            }
        }
        $this->response->body(json_encode($dataEvento));
    }

    public function ajax_evento_drop()
    {
        $acesso = new Acesso();

        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $eventoData = array();
        $dados = $this->request->data;
        $idevento = isset($dados['idevento']) ? $dados['idevento'] : null;
        $start = isset($dados['start']) ? $dados['start'] : null;
        $view = isset($dados['view']) ? $dados['view'] : null;

        if ($acesso->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_ATUALIZAR_EVENTO)) {

            if (isset($start) && isset($idevento) && isset($view)) {
                try {
                    if ($view == 'month') {
                        date_default_timezone_set('America/Sao_Paulo');
                        $strsplit = str_split($start, 10);
                        $start = $strsplit[0];
                        $eventoData = $evento->retornarPorId($idevento);
                        $dataTimeS = new DateTime($eventoData['data_inicio']);
                        $dateTimeE = new DateTime($eventoData['data_fim']);
                        $dataTimeStart = new DateTime($start . ' ' . $dataTimeS->format('H:i:s'));

                        $interval = $dataTimeS->diff($dataTimeStart);
                        $dias = $interval->format('%R%a day');

                        $dataTimeS->modify($dias);
                        $dateTimeE->modify($dias);

                        $eventoData['data_inicio'] = $dataTimeS->format('Y-m-d H:i:s');
                        $eventoData['data_fim'] = $dateTimeE->format('Y-m-d H:i:s');
                        $isValid = $evento->validarEvento($eventoData);
                        if ($isValid == 'sucesso') {
                            $evento->save($eventoData);
                            $eventoData['result'] = true;
                        } else {
                            $eventoData['result'] = false;
                            $eventoData['error'] = $isValid;
                        }
                    } else {
                        date_default_timezone_set('America/Sao_Paulo');
                        $eventoData = $evento->retornarPorId($idevento);
                        $dataTimeS = new DateTime($eventoData['data_inicio']);
                        $dateTimeE = new DateTime($eventoData['data_fim']);
                        $dataTimeStart = new DateTime($start);

                        $interval = $dataTimeS->diff($dataTimeStart);
                        $dias = $interval->format('%R%a day');
                        $horas = $interval->format('%R%H hour');
                        $minutos = $interval->format('%R%i minute');

                        $dataTimeS->modify($dias);
                        $dataTimeS->modify($horas);
                        $dataTimeS->modify($minutos);

                        $dateTimeE->modify($dias);
                        $dateTimeE->modify($horas);
                        $dateTimeE->modify($minutos);

                        $eventoData['data_inicio'] = $dataTimeS->format('Y-m-d H:i:s');
                        $eventoData['data_fim'] = $dateTimeE->format('Y-m-d H:i:s');
                        $isValid = $evento->validarEvento($eventoData);
                        if ($isValid == 'sucesso') {
                            $evento->save($eventoData);
                            $eventoData['result'] = true;
                        } else {
                            $eventoData['result'] = false;
                            $eventoData['error'] = $isValid;
                        }
                    }
                } catch (Exception $exc) {
                    $eventoData['result'] = false;
                    $eventoData['error'] = 'Falha na leitura dos dados.';
                    CakeLog::error($exc->getMessage());
                }
            }
        } else {
            $eventoData['result'] = false;
            $eventoData['error'] = 'Você não tem permissão para alterar eventos.';
        }
        $this->response->body(json_encode($eventoData));
    }

    public function ajax_evento_compareceu_oneclick()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $idsEventos = isset($this->request->data['idsEventos']) ? $this->request->data['idsEventos'] : null;

        if (isset($idsEventos)) {
            $evento->updateCompareceu($idsEventos);
            $result = [
                'status' => 'OK',
                'message' => 'Eventos alterados com sucesso!',
            ];
            $this->response->body(json_encode($result));
        } else {
            $result = [
                'status' => 'ERROR',
                'message' => 'Nenhum evento informado para alteração.',
            ];
            $this->response->body(json_encode($result));
        }
    }

    public function ajax_eventos()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $dadosEventos = array();
        $dados = $this->request->data;
        $idagenda = isset($dados['idagenda']) ? $dados['idagenda'] : null;
        $start = isset($dados['start']) ? $dados['start'] : null;
        $end = isset($dados['end']) ? $dados['end'] : null;

        if (isset($start) && isset($end) && isset($idagenda)) {
            $eventosData = $evento->retornarPorData($start, $end, $idagenda);
            foreach ($eventosData as $_event) {
                $dtStart = DateTime::createFromFormat('Y-m-d H:i:s', $_event['e']['data_inicio']);
                $dtEnd = DateTime::createFromFormat('Y-m-d H:i:s', $_event['e']['data_fim']);

                $e['id'] = $_event['e']['idevento'];
                $e['start'] = $dtStart->format("c");
                $e['end'] = $dtEnd->format("c");
                $e['allDay'] = $_event['e']['allday'];
                $e['textColor'] = '#ffffff';
                if ((int) $_event['e']['id_evento_status'] == Evento::$STATUS_AGUARDANDO) {
                    $e['color'] = $this->azul;
                } else if ($_event['e']['id_evento_status'] == Evento::$STATUS_NAO_COMPARECEU) {
                    $e['color'] = $this->vermelho;
                } else if ($_event['e']['id_evento_status'] == Evento::$STATUS_ADIADO) {
                    $e['color'] = $this->laranja;
                } else if ($_event['e']['id_evento_status'] == Evento::$STATUS_ADIADO_NOVAMENTE) {
                    $e['color'] = $this->roxo;
                } else if ($_event['e']['id_evento_status'] == Evento::$STATUS_COMPARECEU) {
                    $e['color'] = $this->verde;
                } else if ($_event['e']['id_evento_status'] == Evento::$STATUS_REPOSICAO) {
                    $e['color'] = $this->azul_escuro;
                } else if ($_event['e']['id_evento_status'] == Evento::$STATUS_INDISPONIVEL) {
                    $e['color'] = $this->cinza;
                } else if ($_event['e']['id_evento_status'] == Evento::$STATUS_EXPERIMENTAL) {
                    $e['color'] = $this->cyan;
                }
                if ($_event['e']['id_evento_status'] == Evento::$STATUS_INDISPONIVEL) {
                    $e['title'] = $_event['e']['descricao'];
                    $e['description'] = "Não há paciente";
                } else if (isset($_event['a']['nome'])) {
                    $e['title'] = $_event['a']['nome'] . ". " . $_event['e']['descricao'];
                    $e['description'] = "<div style='padding-top: 5px; padding-left: 15px; padding-right: 15px;'> <p>Paciente: " . $_event['a']['nome'] . " " . $_event['a']['sobrenome'] . "</p>" .
                        "<p>Email: " . $_event['a']['email'] . "</p>" .
                        "<p>Telefone: " . $_event['a']['telefone_fixo'] . "</p>" .
                        "<p>Celular: " . $_event['a']['telefone_celular'] . "</p> </div>";
                } else {
                    $e['title'] = $_event['p']['nome'] . ". " . $_event['e']['descricao'];
                    $e['description'] = "<div style='padding-top: 5px; padding-left: 15px; padding-right: 15px;'> <p>Paciente: " . $_event['p']['nome'] . " " . $_event['p']['sobrenome'] . "</p>" .
                        "<p>Email: " . $_event['p']['email'] . "</p>" .
                        "<p>Telefone: " . $_event['p']['telefone_fixo'] . "</p>" .
                        "<p>Celular: " . $_event['p']['telefone_celular'] . "</p> " .
                        "<p>Recebimento: " . ($_event['r']['idrecebimento'] != null ? ($_event['r']['idrecebimento'] . " - " . $_event['r']['descricao']) : "") . "</p> " .
                        "</div>";
                }


                $dadosEventos[] = $e;
            }
        }
        $this->response->body(json_encode($dadosEventos));
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
        $ordenacao = $columns[$order[0]["column"]]["data"] . " " . strtoupper($order[0]["dir"]);

        $content = new Agenda();
        $contents = $content->listarJQuery($search, $start, $length, $ordenacao);

        $this->response->body(json_encode(
            array(
                "draw" => $draw,
                "recordsTotal" => (int) $content->totalRegistro(),
                "recordsFiltered" => (int) $content->totalRegistroFiltrado($search),
                "data" => $contents
            )
        ));
    }

    public function ajax_planos_validos()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idpaciente = (isset($this->request->query["idpaciente"])) ? $this->request->query["idpaciente"] : null;
        if (isset($idpaciente)) {
            $planoSessao = new PlanoSessao();
            $dataPlanos = $planoSessao->retornarValidosParaEventos($idpaciente);
            $html = "<option = value='0'>Selecione</option>";
            if (isset($dataPlanos)) {
                foreach ($dataPlanos as $plano) {
                    if (isset($plano['p']['idplanosessao'])) {
                        $html .= "<option value='{$plano['p']['idplanosessao']}'>{$plano['p']['descricao']} </option>";
                    } else {
                        $html .= "<option value='-1'>Plano Comum(Manual)</option>";
                    }
                }
                $this->response->body($html);
            } else {
                $this->response->body(null);
            }
        } else {
            $this->response->body(null);
        }
    }

    public function ajax_recebimentos_validos()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $idpaciente = (isset($this->request->query["idpaciente"])) ? $this->request->query["idpaciente"] : null;
        if (isset($idpaciente)) {
            $recebimento = new Recebimento();
            $data = $recebimento->retornarPorIdPaciente($idpaciente);
            $html = "<option = value='0'>Selecione</option>";
            if (isset($data)) {
                foreach ($data as $value) {
                    $html .= "<option value='{$value['r']['idrecebimento']}'> {$value['r']['descricao']} - {$value['ed']['total']}x </option>";
                }
                $this->response->body($html);
            } else {
                $this->response->body(null);
            }
        } else {
            $this->response->body(null);
        }
    }

    function ajax_evento_excluir_periodo()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $dataEvento = $this->request->data;

        if (isset($dataEvento)) {
            $dataEvento['data_inicio'] = $dataEvento['data_inicio'] . ' ' . $dataEvento['hora_inicio'];
            $dataEvento['data_fim'] = $dataEvento['data_fim'] . ' ' . $dataEvento['hora_fim'];

            unset($dataEvento['hora_inicio']);
            unset($dataEvento['hora_fim']);

            $dateTimeInicio = DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_inicio'] . ':00');
            $dateTimeFim = DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_fim'] . ':00');

            $dataEvento['data_inicio'] = $dateTimeInicio->format('Y-m-d H:i:s');
            $dataEvento['data_fim'] = $dateTimeFim->format('Y-m-d H:i:s');

            $evento = new Evento();
            $eventoDisponibilidade = new EventoDisponibilidade();
            $dados = array();
            if ($dataEvento['excluir_comparecido'] == 0) {
                $dados = $evento->retornarPorPeriodo($dataEvento['data_inicio'], $dataEvento['data_fim'], $dataEvento['id_agenda'], $dataEvento['id_paciente']);
            } else {
                $dados = $evento->retornarPorPeriodoComComparecidos($dataEvento['data_inicio'], $dataEvento['data_fim'], $dataEvento['id_agenda'], $dataEvento['id_paciente']);
            }


            $countExcluidos = 0;
            foreach ($dados as $value) {
                if ($value['evento']['id_evento_status'] != Evento::$STATUS_INDISPONIVEL) {
                    $eventoDisponibilidade->incremetarTotal($value['evento']['id_paciente'], $value['evento']['id_recebimento']);
                }
                $evento->excluir($value['evento']['idevento']);
                $countExcluidos++;
            }
            $result["status"] = true;
            $result["mensagem"] = $countExcluidos . " registros foram excluídos no período selecionado.";
            $this->response->body(json_encode($result));
        } else {
            $result["status"] = false;
            $result["mensagem"] = "Problemas ao excluir.";
            $this->response->body(json_encode($result));
        }
    }

    function ajax_evento_reposicao_aulas()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $dataEvento = $this->request->data;

        if (isset($dataEvento)) {

            $evento = new Evento();
            $dados = $evento->retornarEventosReposicao($dataEvento['id_agenda'], $dataEvento['id_paciente']);
            $size = count($dados);
            $result = "";
            $result .= "
                <table class='table table-striped'>
                    <thead>
                        <th class='text-left col-md-2'>Descrição</th>
                        <th class='text-center col-md-2'>Data Evento</th>
                        <th class='text-center col-md-2'>Agendar Para</th>
                        <th class='text-center col-md-2'>Horário Início</th>
                        <th class='text-center col-md-2'>Horário Término</th>
                        <th class='text-center col-md-1'>Confirmar</th>
                        <th class='text-center col-md-1'>Não Compareceu</th>
                    </thead>
                    <tbody>
                ";

            if ($size > 0) {
                for ($i = 0; $i < $size; $i++) {
                    $result .= "
                        <tr class='linha_inteira{$dados[$i]['e']["idevento"]}'>
                            <td class='text-left col-md-2'><input type='hidden' class='linha{$dados[$i]['e']["idevento"]}'  name='descricao' value='{$dados[$i]["e"]["descricao"]}' /><input type='hidden' class='linha{$dados[$i]['e']["idevento"]}'  name='idevento' value='{$dados[$i]["e"]["idevento"]}' />{$dados[$i]["e"]["descricao"]}</td> 
                            <td class='text-center col-md-2'><input type='hidden' class='linha{$dados[$i]['e']["idevento"]}' name='data_inicio' value='" . date('d/m/Y - H:i:s', strtotime($dados[$i]["e"]["data_inicio"])) . "' />" . date('d/m/Y - H:i:s', strtotime($dados[$i]["e"]["data_inicio"])) . " </td>
                            <td class='text-center col-md-2' ><input type='text' id='data_inicio{$dados[$i]['e']["idevento"]}' class='linha{$dados[$i]['e']["idevento"]} data' name='data_inicio_novo' size='10' /></td>
                            <td class='text-center col-md-2' ><input type='text' id='hora_inicio{$dados[$i]['e']["idevento"]}' class='linha{$dados[$i]['e']["idevento"]} hora' name='hora_inicio' size='5' /></td>
                            <td class='text-center col-md-2'><input type='text'  id='hora_termino{$dados[$i]['e']["idevento"]}' class='linha{$dados[$i]['e']["idevento"]} hora' name='hora_termino' size='5' /></td>
                            <td class='text-center col-md-1 linha{$dados[$i]['e']["idevento"]}' name='descricao'>
                                <button type='button' class='btn btn-success btn-show-events' onclick='gerarReposicao({$dados[$i]['e']["idevento"]})'><i class='fa fa-check fa-lg'></i></button>
                            </td>
                            <td class='text-center col-md-1 linha{$dados[$i]['e']["idevento"]}' name='descricao'>
                                <button type='button' class='btn btn-danger btn-show-events' onclick='naoCompareceu({$dados[$i]['e']["idevento"]})'><i class='fa fa-times fa-lg'></i></button>
                            </td>
                        </tr>";
                }
            } else {
                $result .= "
                        <tr>
                            <td colspan='7'><p class='text-center'> Não há registros</p></td>
                        </tr>";
            }
            $result .= "</tbody></table>";

            $this->response->body($result);
        } else {
            $result["status"] = false;
            $result["mensagem"] = "Problemas ao gerar reposição.";
            $this->response->body(json_encode($result));
        }
    }

    function ajax_evento_confirmar_reposicao()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $dataEvento = $this->request->data;

        if (isset($dataEvento)) {
            $data_inicio = $dataEvento['data_inicio_novo'] . ' ' . $dataEvento['hora_inicio'];
            $data_termino = $dataEvento['data_inicio_novo'] . ' ' . $dataEvento['hora_termino'];

            $datetime_inicio = DateTime::createFromFormat('d/m/Y H:i:s', $data_inicio . ':00');
            $datetime_termino = DateTime::createFromFormat('d/m/Y H:i:s', $data_termino . ':00');

            $data_inicio = $datetime_inicio->format('Y-m-d H:i:s');
            $data_termino = $datetime_termino->format('Y-m-d H:i:s');
            $idevento = $dataEvento['idevento'];

            $evento = new Evento();
            $dados = $evento->gerarEventoReposicao($data_inicio, $data_termino, $idevento);
            $this->response->body($dados);
        } else {
            $result["status"] = false;
            $result["mensagem"] = "Problemas ao gerar reposição.";
            $this->response->body(json_encode($result));
        }
    }

    function ajax_evento_nao_compareceu()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $dataEvento = $this->request->data;
        $result = array();

        if (isset($dataEvento)) {
            $idevento = $dataEvento['idevento'];
            $evento = new Evento();
            $evento->updateNaoCompareceu($idevento);
            $result["status"] = true;
            $result["mensagem"] = "Evento Alterado com sucesso.";
            $this->response->body(json_encode($result));
        } else {
            $result["status"] = false;
            $result["mensagem"] = "Problemas ao alterar evento.";
            $this->response->body(json_encode($result));
        }
    }

    public function ajax_retornar_fichas_fisioterapia()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $result = array();
        $idevento = isset($this->request->query['idevento']) ? $this->request->query['idevento'] : null;
        if (isset($idevento)) {
            $evento = new Evento();
            $dadosEvento = $evento->retornarPorId($idevento);
            if (isset($dadosEvento['id_paciente'])) {
                $idpaciente = $dadosEvento['id_paciente'];
                $datetime1 = DateTime::createFromFormat('Y-m-d H:i:s', $dadosEvento['data_inicio']);
                $datetime2 = DateTime::createFromFormat('Y-m-d H:i:s', $dadosEvento['data_fim']);
                $dadosEvento['data_inicio'] = $datetime1->format("d/m/Y H:i");
                $dadosEvento['data_fim'] = $datetime2->format("d/m/Y H:i");

                $fichaFisioterapia = new FichaFisioterapia();
                $paciente = new Paciente();
                $dadosPaciente = $paciente->retornarPorIdSemFoto($idpaciente);
                $fichas = $fichaFisioterapia->retornarPorIdPaciente($idpaciente);
                if (!isset($fichas)) {
                    $fichas = array();
                }

                $result["evento"] = $dadosEvento;
                $result["paciente"] = $dadosPaciente;
                $result["fichas"] = $fichas;
                $result["status"] = true;
                $result["mensagem"] = "Fichas retonardas com sucesso";
                $this->response->body(json_encode($result));
            } else {
                $result["status"] = false;
                $result["mensagem"] = "Não há paciente registrado neste evento.";
                $this->response->body(json_encode($result));
            }
        } else {
            $result["status"] = false;
            $result["mensagem"] = "Não foi possível localizar o evento.";
            $this->response->body(json_encode($result));
        }
    }

    public function ajax_retornar_acompanhamento_fisioterapia()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $result = array();
        $idfichafisioterapia = isset($this->request->query['idfichafisioterapia']) ? $this->request->query['idfichafisioterapia'] : null;
        $idevento = isset($this->request->query['idevento']) ? $this->request->query['idevento'] : null;
        if (isset($idfichafisioterapia) && isset($idevento)) {
            $acompanhamento = new AcompanhamentoFisioterapia();
            $dadosAcompanhamento = $acompanhamento->retornarParaAgenda($idfichafisioterapia, $idevento);
            if (isset($dadosAcompanhamento)) {
                $result["acompanhamento"] = $dadosAcompanhamento;
                $result["status"] = true;
                $result["mensagem"] = "Acompanhamento retornado com sucesso.";
                $this->response->body(json_encode($result));
            } else {
                $result["status"] = false;
                $result["mensagem"] = "Nenhum registro encontrado.";
                $this->response->body(json_encode($result));
            }
        } else {
            $result["status"] = false;
            $result["mensagem"] = "Problemas retornar acompanhamento.";
            $this->response->body(json_encode($result));
        }
    }

    public function ajax_salvar_acompanhamento_fisioterapia()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $result = array();
        $dados = $this->request->data;
        if (isset($dados['AcompanhamentoFisioterapia'])) {
            $acompanhamento = new AcompanhamentoFisioterapia();
            if ($dados['AcompanhamentoFisioterapia']['idacompanhamentofisioterapia'] == 0) {
                $acompanhamento->save($dados['AcompanhamentoFisioterapia']);
            } else {
                $acompanhamento->id = $dados['AcompanhamentoFisioterapia']['idacompanhamentofisioterapia'];
                $acompanhamento->save(array("descricao" => $dados['AcompanhamentoFisioterapia']['descricao']));
            }

            $result["status"] = true;
            $result["mensagem"] = "Registro salvo com sucesso.";
            $this->response->body(json_encode($result));
        } else {
            $result["status"] = false;
            $result["mensagem"] = "Problemas ao salvar registro de acompanhamento.";
            $this->response->body(json_encode($result));
        }
    }

    public function ajax_evento_salvar_experimental()
    {
        $acesso = new Acesso();

        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $aulaExperimental = new AulaExperimental();

        $dados = $this->request->data;
        $dataEvento = isset($dados['Evento']) ? $dados['Evento'] : null;
        $dataAulaExperimental = isset($dados['AulaExperimental']) ? $dados['AulaExperimental'] : null;


        if (isset($dataEvento)) {
            $dataEvento['data_inicio'] = $dataEvento['data_inicio'] . ' ' . $dataEvento['hora_inicio'];
            $dataEvento['data_fim'] = $dataEvento['data_fim'] . ' ' . $dataEvento['hora_fim'];

            unset($dataEvento['hora_inicio']);
            unset($dataEvento['hora_fim']);
        }

        if ($acesso->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ACESSO_ADICIONAR_EVENTO)) {
            if (isset($dataAulaExperimental) && isset($dataEvento)) {
                unset($dataEvento['visao']);

                $dateTimeNascimento = DateTime::createFromFormat('d/m/Y', $dataAulaExperimental['data_nascimento']);
                $dataAulaExperimental['data_nascimento'] = $dateTimeNascimento->format('Y-m-d');

                $dateTimeInicio = DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_inicio'] . ':00');
                $dateTimeFim = DateTime::createFromFormat('d/m/Y H:i:s', $dataEvento['data_fim'] . ':00');

                $dataEvento['data_inicio'] = $dateTimeInicio->format('Y-m-d H:i:s');
                $dataEvento['data_fim'] = $dateTimeFim->format('Y-m-d H:i:s');
                $dataEvento['allday'] = 0;
                $dataEvento['id_evento_status'] = Evento::$STATUS_EXPERIMENTAL;

                if ($dateTimeFim > $dateTimeInicio) {
                    $isValid = $evento->validarEvento($dataEvento);
                    if ($isValid == 'sucesso') {
                        $aulaExperimental->create();
                        $aulaExperimental->save($dataAulaExperimental);
                        $dataAulaExperimental['idaulaexperimental'] = $aulaExperimental->id;

                        $dataEvento['id_aula_experimental'] = $dataAulaExperimental['idaulaexperimental'];

                        $evento->create();
                        $evento->save($dataEvento);

                        $dataEvento['idevento'] = $evento->id;
                        $dataEvento['data_inicio'] = str_replace(" ", "T", $dataEvento['data_inicio']);
                        $dataEvento['data_fim'] = str_replace(" ", "T", $dataEvento['data_fim']);
                        $dataEvento['textColor'] = '#ffffff';
                        $dataEvento['color'] = $this->cyan;

                        //Aula experimental como paciente
                        $dataEvento['paciente'] = $dataAulaExperimental;
                        $dataEvento['description'] = "<div style='padding-top: 5px; padding-left: 15px; padding-right: 15px;'> <p>Paciente: " . $dataEvento['paciente']['nome'] . " " . $dataEvento['paciente']['sobrenome'] . "</p>" .
                            "<p>Email: " . $dataEvento['paciente']['email'] . "</p>" .
                            "<p>Telefone: " . $dataEvento['paciente']['telefone_fixo'] . "</p>" .
                            "<p>Celular: " . $dataEvento['paciente']['telefone_celular'] . "</p> </div>";
                        $dataEvento['result'] = true;
                    } else {
                        $dataEvento['result'] = false;
                        $dataEvento['error'] = $isValid;
                    }
                } else {
                    $dataEvento['result'] = false;
                    $dataEvento['error'] = 'A data de início é menor do que a data de fim.';
                }
            }
        } else {
            $dataEvento['result'] = false;
            $dataEvento['error'] = 'Você não tem permissão para adicionar eventos.';
        }

        $this->response->body(json_encode($dataEvento));
    }

    public function viewpdf($idpaciente, $idagenda, $statusEvento)
    {
        $this->layout = 'default';
        if ($this->request->is("get")) {
            if (isset($idpaciente) && isset($idagenda) && isset($statusEvento)) {
                $recebimentos = isset($this->request->query['recebimentos']) ? $this->request->query['recebimentos'] : '';

                ini_set('memory_limit', '512M');
                $arrayExplode = str_split($statusEvento);
                $status = "";
                $status .= $arrayExplode[0] == "1" ? (string) Evento::$STATUS_AGUARDANDO . "," : "";
                $status .= $arrayExplode[1] == "1" ? (string) Evento::$STATUS_NAO_COMPARECEU . "," : "";
                $status .= $arrayExplode[2] == "1" ? (string) Evento::$STATUS_ADIADO . "," : "";
                $status .= $arrayExplode[3] == "1" ? (string) Evento::$STATUS_ADIADO_NOVAMENTE . "," : "";
                $status .= $arrayExplode[4] == "1" ? (string) Evento::$STATUS_COMPARECEU . "," : "";
                $paciente = new Paciente();

                $dataPaciente = $paciente->relatorioPacientePDF($idagenda, $idpaciente, $status, $recebimentos);
                $dataRecebimentos = [];
                if ($recebimentos != '') {
                    $recebimentoModel = new Recebimento();
                    $dataRecebimentos = $recebimentoModel->retornarPorMultiplosIds($recebimentos);
                }

                $this->set("Eventos", $dataPaciente);
                $this->set("recebimentos", $recebimentos);
                $this->set("dataRecebimentos", $dataRecebimentos);
            } else {
                return $this->redirect(array("controller" => "agenda", "action" => "index"));
            }
        }
    }

    public function ajax_retornar_agenda()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $agenda = new Agenda();
        $dataAgendas = array();
        $idProfissional = isset($this->request->query['id_profissional']) ? $this->request->query['id_profissional'] : 0;
        $htmltexto = "";
        if (isset($idProfissional)) {
            $dataAgendas = $agenda->retornarPorIdProfissional($idProfissional);
            if ($dataAgendas != null) {
                $size = count($dataAgendas);
                for ($i = 0; $i < $size; $i++) {
                    $htmltexto .= "<option value='{$dataAgendas[$i]['agenda']['idagenda']}'>{$dataAgendas[$i]['agenda']['descricao']}</option>";
                }
            }
        }
        $this->response->body($htmltexto);
    }

    public function ajax_evento_adiar_selecionados()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();

        $idsEventos = isset($this->request->data['idsEventos']) ? $this->request->data['idsEventos'] : null;
        $observacao = isset($this->request->data['observacao']) ? $this->request->data['observacao'] : null;

        if (isset($idsEventos)) {
            $evento->updateAdiou($idsEventos, $observacao);

            $result = [
                'status' => 'OK',
                'message' => 'Eventos alterados com sucesso!',
            ];
            $this->response->body(json_encode($result));
        } else {
            $result = [
                'status' => 'ERROR',
                'message' => 'Nenhum evento informado para alteração.',
            ];
            $this->response->body(json_encode($result));
        }
    }

    public function ajax_validar_alteracao_eventos()
    {
        $this->layout = "ajax";
        $this->autoRender = false;
        $evento = new Evento();
        $idsEventos = isset($this->request->data['idsEventos']) ? $this->request->data['idsEventos'] : null;

        if (isset($idsEventos)) {
            if ($evento->existeEventosSemanaAnterior($idsEventos)) {
                $acesso = new Acesso();
                if (!$acesso->validarAcesso($this->_dados['id_tipo_usuario'], Acesso::$ALTERAR_EVENTOS_FORA_SEMANA)) {
                    $result = [
                        'status' => 'OK',
                        'message' => 'Usuário não possui permissão para alterar eventos da semana anterior.',
                        'requisitarPermissao' => true,
                    ];
                    $this->response->body(json_encode($result));
                    return;
                }
            }

            $result = [
                'status' => 'OK',
                'message' => 'Usuário pode alterar os eventos.',
                'requisitarPermissao' => false,
            ];
            $this->response->body(json_encode($result));
        } else {
            $result = [
                'status' => 'ERROR',
                'message' => 'Nenhum evento informado para alteração.',
            ];
            $this->response->body(json_encode($result));
        }
    }
}
