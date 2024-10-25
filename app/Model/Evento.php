<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Evento
 * @author Felipe
 */
class Evento extends AppModel
{

    public $useTable = "evento";
    public $primaryKey = "idevento";
    public static $STATUS_AGUARDANDO = 1;
    public static $STATUS_NAO_COMPARECEU = 2;
    public static $STATUS_ADIADO = 3;
    public static $STATUS_ADIADO_NOVAMENTE = 4;
    public static $STATUS_COMPARECEU = 5;
    public static $STATUS_INDISPONIVEL = 6;
    public static $STATUS_REPOSICAO = 7;
    public static $STATUS_EXPERIMENTAL = 8;

    public function excluir($id)
    {
        return $this->query("DELETE FROM evento WHERE idevento = $id; ");
    }

    public function retornarPorId($id)
    {
        $dados = $this->query("SELECT * FROM evento WHERE idevento = $id LIMIT 1");
        return $dados[0]["evento"];
    }

    public function validarEvento($evento)
    {
        $statusIndisponivel = Evento::$STATUS_INDISPONIVEL;
        $statusExperimental = Evento::$STATUS_EXPERIMENTAL;
        if ($evento['id_evento_status'] != $statusIndisponivel && $evento['id_evento_status'] != $statusExperimental && !isset($evento['id_paciente'])) {
            return 'Nenhum paciente selecionado';
        }
        if (isset($evento['idevento'])) {
            $dados = $this->query("SELECT * FROM evento e WHERE (e.data_inicio >= '{$evento['data_inicio']}' and e.data_inicio < '{$evento['data_fim']}' or e.data_fim > '{$evento['data_inicio']}' and e.data_fim <= '{$evento['data_fim']}') and e.id_agenda={$evento['id_agenda']} and e.id_evento_status = $statusIndisponivel and e.idevento <> {$evento['idevento']}");
            if (isset($dados[0])) {
                return 'Horário reservado';
            }
        } else {
            $dados = $this->query("SELECT * FROM evento e WHERE (e.data_inicio >= '{$evento['data_inicio']}' and e.data_inicio < '{$evento['data_fim']}' or e.data_fim > '{$evento['data_inicio']}' and e.data_fim <= '{$evento['data_fim']}') and e.id_agenda={$evento['id_agenda']} and e.id_evento_status = $statusIndisponivel");
            if (isset($dados[0])) {
                return 'Horário reservado';
            }
        }
        return 'sucesso';
    }

    public function validarHorarioRepetido($evento)
    {
        $dados = $this->query("SELECT * FROM evento e WHERE (e.data_inicio >= '{$evento['data_inicio']}' and e.data_inicio < '{$evento['data_fim']}' or e.data_fim > '{$evento['data_inicio']}' and e.data_fim <= '{$evento['data_fim']}') and e.id_agenda={$evento['id_agenda']}");
        if (isset($dados[0])) {
            return 'Horário reservado';
        }
        return 'sucesso';
    }

    public function retornarPorData($datastart, $dataend, $idagenda)
    {
        $dados = $this->query("SELECT e.*, p.idpaciente, p.nome, p.sobrenome, p.email, p.telefone_fixo, p.telefone_celular,
                a.idaulaexperimental, a.nome, a.sobrenome, a.email, a.telefone_fixo, a.telefone_celular, r.idrecebimento, r.descricao
                FROM evento e 
                LEFT JOIN paciente p on (e.id_paciente = p.idpaciente) 
                LEFT JOIN aula_experimental a on (e.id_aula_experimental = a.idaulaexperimental)
                LEFT JOIN recebimento r on (e.id_recebimento = r.idrecebimento)
                WHERE (e.data_inicio between '$datastart' and '$dataend' or (e.repetir = 1 AND e.data_fim_repetir between '$datastart' and '$dataend'))
                and e.id_agenda = $idagenda ");
        return $dados;
    }

    public function retornarQuantidadeHoje()
    {
        $statusIndisponivel = Evento::$STATUS_INDISPONIVEL;
        $dados = $this->query("SELECT count(idevento) as total FROM evento WHERE CAST(data_inicio AS DATE) = CURDATE() and id_evento_status <> $statusIndisponivel; ");
        return $dados[0][0]['total'];
    }

    public function retornarQuantidadeAmanha()
    {
        $statusIndisponivel = Evento::$STATUS_INDISPONIVEL;
        $dados = $this->query("SELECT count(idevento) as total FROM evento WHERE CAST(data_inicio AS DATE) = (CURDATE() + INTERVAL 1 DAY) and id_evento_status <> $statusIndisponivel; ");
        return $dados[0][0]['total'];
    }

    public function retornarQuantidadeEstaSemana()
    {
        $statusIndisponivel = Evento::$STATUS_INDISPONIVEL;
        $dataInicio = date("w") == 0 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("last Sunday")));
        $dataFim = date("w") == 6 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("next Saturday")));
        $dados = $this->query("SELECT count(idevento) as total FROM evento WHERE CAST(data_inicio as DATE) between CAST('$dataInicio' as DATE) and CAST('$dataFim' as DATE) and id_evento_status <> $statusIndisponivel;");
        return $dados[0][0]['total'];
    }

    public function retornarTimelineEventos($start = 0)
    {
        $dados = $this->query("SELECT e.*, p.nome, p.sobrenome, p.email, po.nome, po.sobrenome 
            FROM evento e 
            INNER JOIN paciente p on (e.id_paciente = p.idpaciente) 
            INNER JOIN agenda a on (e.id_agenda = a.idagenda) 
            INNER JOIN profissional po on (a.id_profissional = po.idprofissional) 
            WHERE e.id_evento_status = 5
            order by e.data_inicio desc 
            limit $start,10");
        return $dados;
    }

    public function retornarHoje()
    {
        $dados = $this->query("SELECT e.*, p.nome, p.sobrenome, p.email, po.nome, po.sobrenome,
                ae.nome, ae.sobrenome, ae.email
                FROM evento e 
                LEFT JOIN paciente p on (e.id_paciente = p.idpaciente)  
                LEFT JOIN aula_experimental ae on (e.id_aula_experimental = ae.idaulaexperimental)
                INNER JOIN agenda a on (e.id_agenda = a.idagenda) 
                INNER JOIN profissional po on (a.id_profissional = po.idprofissional) 
                WHERE CAST(e.data_inicio AS DATE) = CURDATE() ");
        return $dados;
    }

    public function retornarAmanha()
    {
        $dados = $this->query("SELECT e.*, p.nome, p.sobrenome, p.email, po.nome, po.sobrenome,
                ae.nome, ae.sobrenome, ae.email
                FROM evento e 
                LEFT JOIN paciente p on (e.id_paciente = p.idpaciente)   
                LEFT JOIN aula_experimental ae on (e.id_aula_experimental = ae.idaulaexperimental)
                INNER JOIN agenda a on (e.id_agenda = a.idagenda) 
                INNER JOIN profissional po on (a.id_profissional = po.idprofissional) 
                WHERE CAST(e.data_inicio AS DATE) = (CURDATE() + INTERVAL 1 DAY) ");
        return $dados;
    }

    public function retornarEssaSemana()
    {
        $dataInicio = date("w") == 0 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("last Sunday")));
        $dataFim = date("w") == 6 ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', (strtotime("next Saturday")));
        $dados = $this->query("SELECT e.*, p.nome, p.sobrenome, p.email, po.nome, po.sobrenome,
                ae.nome, ae.sobrenome, ae.email
                FROM evento e 
                LEFT JOIN paciente p on (e.id_paciente = p.idpaciente)   
                LEFT JOIN aula_experimental ae on (e.id_aula_experimental = ae.idaulaexperimental)
                INNER JOIN agenda a on (e.id_agenda = a.idagenda) 
                INNER JOIN profissional po on (a.id_profissional = po.idprofissional) 
                WHERE CAST(data_inicio as DATE) between CAST('$dataInicio' as DATE) and CAST('$dataFim' as DATE) ");
        return $dados;
    }

    public function updateCompareceu($dados)
    {
        $status = Evento::$STATUS_COMPARECEU;
        $this->query("UPDATE evento set id_evento_status = {$status} WHERE idevento in ({$dados})");
    }

    public function updateNaoCompareceu($dados)
    {
        $status = Evento::$STATUS_NAO_COMPARECEU;
        $this->query("UPDATE evento set id_evento_status = {$status} WHERE idevento in ({$dados})");
    }

    public function updateAdiou($dados, $obs)
    {
        $statusAdiado = Evento::$STATUS_ADIADO;
        $statusAdiadoNovamente = Evento::$STATUS_ADIADO_NOVAMENTE;
        $this->query("UPDATE evento SET id_evento_status = CASE WHEN id_evento_status = $statusAdiado THEN $statusAdiadoNovamente ELSE $statusAdiado END, observacao = '$obs' WHERE idevento IN ({$dados})");
    }

    public function retornarQuantidadeEventosAguardando()
    {
        $status = Evento::$STATUS_AGUARDANDO;
        $array = array();
        $dados = $this->query("select 
            sum(case when MONTH(evento.data_inicio) = 1 then 1 else 0 end) as janeiro,
            sum(case when MONTH(evento.data_inicio) = 2 then 1 else 0 end) as fevereiro,
            sum(case when MONTH(evento.data_inicio) = 3 then 1 else 0 end) as marco,
            sum(case when MONTH(evento.data_inicio) = 4 then 1 else 0 end) as abril,
            sum(case when MONTH(evento.data_inicio) = 5 then 1 else 0 end) as maio,
            sum(case when MONTH(evento.data_inicio) = 6 then 1 else 0 end) as junho,
            sum(case when MONTH(evento.data_inicio) = 7 then 1 else 0 end) as julho,
            sum(case when MONTH(evento.data_inicio) = 8 then 1 else 0 end) as agosto,
            sum(case when MONTH(evento.data_inicio) = 9 then 1 else 0 end) as setembro,
            sum(case when MONTH(evento.data_inicio) = 10 then 1 else 0 end) as outubro,
            sum(case when MONTH(evento.data_inicio) = 11 then 1 else 0 end) as novembro,
            sum(case when MONTH(evento.data_inicio) = 12 then 1 else 0 end) as dezembro
            from evento
            where YEAR(evento.data_inicio) = YEAR(NOW()) and evento.id_evento_status = $status;");
        if (isset($dados[0][0]['janeiro'])) {
            $array[] = (int) $dados[0][0]['janeiro'];
            $array[] = (int) $dados[0][0]['fevereiro'];
            $array[] = (int) $dados[0][0]['marco'];
            $array[] = (int) $dados[0][0]['abril'];
            $array[] = (int) $dados[0][0]['maio'];
            $array[] = (int) $dados[0][0]['junho'];
            $array[] = (int) $dados[0][0]['julho'];
            $array[] = (int) $dados[0][0]['agosto'];
            $array[] = (int) $dados[0][0]['setembro'];
            $array[] = (int) $dados[0][0]['outubro'];
            $array[] = (int) $dados[0][0]['novembro'];
            $array[] = (int) $dados[0][0]['dezembro'];
        } else {
            for ($i = 0; $i < 12; $i++) {
                $array[] = 0;
            }
        }
        return $array;
    }

    public function retornarQuantidadeEventosCompareceu()
    {
        $status = Evento::$STATUS_COMPARECEU;
        $array = array();
        $dados = $this->query("select 
            sum(case when MONTH(evento.data_inicio) = 1 then 1 else 0 end) as janeiro,
            sum(case when MONTH(evento.data_inicio) = 2 then 1 else 0 end) as fevereiro,
            sum(case when MONTH(evento.data_inicio) = 3 then 1 else 0 end) as marco,
            sum(case when MONTH(evento.data_inicio) = 4 then 1 else 0 end) as abril,
            sum(case when MONTH(evento.data_inicio) = 5 then 1 else 0 end) as maio,
            sum(case when MONTH(evento.data_inicio) = 6 then 1 else 0 end) as junho,
            sum(case when MONTH(evento.data_inicio) = 7 then 1 else 0 end) as julho,
            sum(case when MONTH(evento.data_inicio) = 8 then 1 else 0 end) as agosto,
            sum(case when MONTH(evento.data_inicio) = 9 then 1 else 0 end) as setembro,
            sum(case when MONTH(evento.data_inicio) = 10 then 1 else 0 end) as outubro,
            sum(case when MONTH(evento.data_inicio) = 11 then 1 else 0 end) as novembro,
            sum(case when MONTH(evento.data_inicio) = 12 then 1 else 0 end) as dezembro
            from evento
            where YEAR(evento.data_inicio) = YEAR(NOW()) and evento.id_evento_status = $status;");
        if (isset($dados[0][0]['janeiro'])) {
            $array[] = (int) $dados[0][0]['janeiro'];
            $array[] = (int) $dados[0][0]['fevereiro'];
            $array[] = (int) $dados[0][0]['marco'];
            $array[] = (int) $dados[0][0]['abril'];
            $array[] = (int) $dados[0][0]['maio'];
            $array[] = (int) $dados[0][0]['junho'];
            $array[] = (int) $dados[0][0]['julho'];
            $array[] = (int) $dados[0][0]['agosto'];
            $array[] = (int) $dados[0][0]['setembro'];
            $array[] = (int) $dados[0][0]['outubro'];
            $array[] = (int) $dados[0][0]['novembro'];
            $array[] = (int) $dados[0][0]['dezembro'];
        } else {
            for ($i = 0; $i < 12; $i++) {
                $array[] = 0;
            }
        }
        return $array;
    }

    public function retornarQuantidadeEventosNaoCompareceu()
    {
        $status = Evento::$STATUS_NAO_COMPARECEU;
        $array = array();
        $dados = $this->query("select 
            sum(case when MONTH(evento.data_inicio) = 1 then 1 else 0 end) as janeiro,
            sum(case when MONTH(evento.data_inicio) = 2 then 1 else 0 end) as fevereiro,
            sum(case when MONTH(evento.data_inicio) = 3 then 1 else 0 end) as marco,
            sum(case when MONTH(evento.data_inicio) = 4 then 1 else 0 end) as abril,
            sum(case when MONTH(evento.data_inicio) = 5 then 1 else 0 end) as maio,
            sum(case when MONTH(evento.data_inicio) = 6 then 1 else 0 end) as junho,
            sum(case when MONTH(evento.data_inicio) = 7 then 1 else 0 end) as julho,
            sum(case when MONTH(evento.data_inicio) = 8 then 1 else 0 end) as agosto,
            sum(case when MONTH(evento.data_inicio) = 9 then 1 else 0 end) as setembro,
            sum(case when MONTH(evento.data_inicio) = 10 then 1 else 0 end) as outubro,
            sum(case when MONTH(evento.data_inicio) = 11 then 1 else 0 end) as novembro,
            sum(case when MONTH(evento.data_inicio) = 12 then 1 else 0 end) as dezembro
            from evento
            where YEAR(evento.data_inicio) = YEAR(NOW()) and evento.id_evento_status = $status;");

        if (isset($dados[0][0]['janeiro'])) {
            $array[] = (int) $dados[0][0]['janeiro'];
            $array[] = (int) $dados[0][0]['fevereiro'];
            $array[] = (int) $dados[0][0]['marco'];
            $array[] = (int) $dados[0][0]['abril'];
            $array[] = (int) $dados[0][0]['maio'];
            $array[] = (int) $dados[0][0]['junho'];
            $array[] = (int) $dados[0][0]['julho'];
            $array[] = (int) $dados[0][0]['agosto'];
            $array[] = (int) $dados[0][0]['setembro'];
            $array[] = (int) $dados[0][0]['outubro'];
            $array[] = (int) $dados[0][0]['novembro'];
            $array[] = (int) $dados[0][0]['dezembro'];
        } else {
            for ($i = 0; $i < 12; $i++) {
                $array[] = 0;
            }
        }
        return $array;
    }

    public function retornarQuantidadeEventosAdiado()
    {
        $status1 = Evento::$STATUS_ADIADO;
        $status2 = Evento::$STATUS_ADIADO_NOVAMENTE;
        $array = array();
        $dados = $this->query("select 
            sum(case when MONTH(evento.data_inicio) = 1 then 1 else 0 end) as janeiro,
            sum(case when MONTH(evento.data_inicio) = 2 then 1 else 0 end) as fevereiro,
            sum(case when MONTH(evento.data_inicio) = 3 then 1 else 0 end) as marco,
            sum(case when MONTH(evento.data_inicio) = 4 then 1 else 0 end) as abril,
            sum(case when MONTH(evento.data_inicio) = 5 then 1 else 0 end) as maio,
            sum(case when MONTH(evento.data_inicio) = 6 then 1 else 0 end) as junho,
            sum(case when MONTH(evento.data_inicio) = 7 then 1 else 0 end) as julho,
            sum(case when MONTH(evento.data_inicio) = 8 then 1 else 0 end) as agosto,
            sum(case when MONTH(evento.data_inicio) = 9 then 1 else 0 end) as setembro,
            sum(case when MONTH(evento.data_inicio) = 10 then 1 else 0 end) as outubro,
            sum(case when MONTH(evento.data_inicio) = 11 then 1 else 0 end) as novembro,
            sum(case when MONTH(evento.data_inicio) = 12 then 1 else 0 end) as dezembro
            from evento
            where YEAR(evento.data_inicio) = YEAR(NOW()) and evento.id_evento_status in ($status1,$status2);");

        if (isset($dados[0][0]['janeiro'])) {
            $array[] = (int) $dados[0][0]['janeiro'];
            $array[] = (int) $dados[0][0]['fevereiro'];
            $array[] = (int) $dados[0][0]['marco'];
            $array[] = (int) $dados[0][0]['abril'];
            $array[] = (int) $dados[0][0]['maio'];
            $array[] = (int) $dados[0][0]['junho'];
            $array[] = (int) $dados[0][0]['julho'];
            $array[] = (int) $dados[0][0]['agosto'];
            $array[] = (int) $dados[0][0]['setembro'];
            $array[] = (int) $dados[0][0]['outubro'];
            $array[] = (int) $dados[0][0]['novembro'];
            $array[] = (int) $dados[0][0]['dezembro'];
        } else {
            for ($i = 0; $i < 12; $i++) {
                $array[] = 0;
            }
        }
        return $array;
    }

    public function retornarPorPeriodo($dataInicio, $dataFim, $idagenda, $idpaciente)
    {
        $status = Evento::$STATUS_AGUARDANDO;
        return $this->query("SELECT * FROM evento WHERE id_agenda = {$idagenda} and id_paciente = {$idpaciente} and data_inicio between '{$dataInicio}' and '{$dataFim}' and id_evento_status = {$status}; ");
    }

    public function retornarPorPeriodoComComparecidos($dataInicio, $dataFim, $idagenda, $idpaciente)
    {
        $status = Evento::$STATUS_AGUARDANDO;
        $status2 = Evento::$STATUS_COMPARECEU;
        return $this->query("SELECT * FROM evento WHERE id_agenda = {$idagenda} and id_paciente = {$idpaciente} and data_inicio between '{$dataInicio}' and '{$dataFim}' and id_evento_status in ({$status}, {$status2}); ");
    }

    public function retornarEventosReposicao($idagenda, $idpaciente)
    {
        return $this->query("select e.idevento, e.descricao, IFNULL(e.observacao,'') AS observacao, e.data_inicio   from evento e where id_agenda = $idagenda and id_paciente = $idpaciente and (id_evento_status = 3 or id_evento_status = 4 ) and gerou_reposicao = 0 and ifnull(reposicao_novamente,0) = 0 ");
    }

    public function gerarEventoReposicao($data_inicio, $data_fim, $idevento)
    {
        $this->query("insert into evento(descricao, data_inicio, data_fim, id_agenda, id_paciente, id_evento_status, id_plano_sessao, reposicao, gerou_reposicao, observacao, reposicao_novamente, id_recebimento)
                                select e.descricao, '$data_inicio', '$data_fim', e.id_agenda, e.id_paciente, 7 as evento_status, e.id_plano_sessao, 1 as reposicao, 0 as gerou_reposicao, 
                                    CONCAT('Reposição criada a partir do evento',' ', e.descricao, ', na data:', DATE_FORMAT(e.data_inicio, '%d/%m/%Y')) as observacao, CASE WHEN e.id_evento_status = 4 then 1 else 0 end, id_recebimento
                                from evento e where idevento = $idevento ");
        return $this->query("update evento set gerou_reposicao = 1, reposicao = CASE WHEN evento.id_evento_status = 4 then 1 else 0 end where idevento = $idevento");
    }

    public function changeAulaExperimentalToPaciente($idaulaexperimental, $idpaciente)
    {
        $this->query("UPDATE evento set id_paciente = $idpaciente, id_aula_experimental = (NULL) WHERE id_aula_experimental = $idaulaexperimental");
    }

    public function retornarIndisponiveisPorData($datastart, $dataend, $idagenda)
    {
        $status = Evento::$STATUS_INDISPONIVEL;
        $dados = $this->query("SELECT e.*, p.idpaciente, p.nome, p.sobrenome, p.email, p.telefone_fixo, p.telefone_celular,
                a.idaulaexperimental, a.nome, a.sobrenome, a.email, a.telefone_fixo, a.telefone_celular
                FROM evento e 
                LEFT JOIN paciente p on (e.id_paciente = p.idpaciente) 
                LEFT JOIN aula_experimental a on (e.id_aula_experimental = a.idaulaexperimental)
                WHERE (e.data_inicio between '$datastart' 
                and '$dataend' or e.repetir = 1) 
                and e.id_agenda = $idagenda
                and e.id_evento_status = $status ");
        return $dados;
    }

    public function existeEventosSemanaAnterior($ids)
    {
        $firstdayweek = date('Y-m-d', strtotime("sunday -1 week"));
        $result = $this->query("SELECT * FROM evento WHERE idevento in ({$ids}) AND CAST(data_inicio as DATE) < CAST('$firstdayweek' as DATE)");
        return count($result) > 0;
    }
}
