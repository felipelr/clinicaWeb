<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP Paciente
 * @author Felipe
 */
class Paciente extends AppModel
{

    public $useTable = "paciente";
    public $primaryKey = "idpaciente";

    public function retornarTodos()
    {
        return $this->query("SELECT idpaciente, nome, sobrenome FROM paciente p WHERE p.ativo = 1 order by p.nome");
    }

    public function retornarPorId($id)
    {
        $dados = $this->query("SELECT * FROM paciente WHERE idpaciente = $id LIMIT 1");
        return $dados[0]["paciente"];
    }

    public function retornarPorIdSemFoto($id)
    {
        $dados = $this->query("SELECT idpaciente, nome, sobrenome, email, telefone_fixo, telefone_celular FROM paciente WHERE idpaciente = $id LIMIT 1");
        return $dados[0]["paciente"];
    }

    public function retornarTodosExcluirEvento()
    {
        return $this->query("SELECT idpaciente, nome, sobrenome FROM paciente p order by p.nome");
    }

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null, $clientStatus = 1)
    {
        $filterStatus = $clientStatus == -1 ? "" : " AND p.ativo = $clientStatus ";
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? " WHERE 1 = 1 $filterStatus " : " WHERE 1 = 1 $filterStatus and (CONCAT(TRIM(p.nome), ' ', TRIM(p.sobrenome)) LIKE '%{$search}%' OR p.email LIKE '%{$search}%' OR p.cpf LIKE '%{$search}%') ";
        return $this->query("SELECT p.idpaciente, p.nome, p.sobrenome, p.email, p.cpf, p.telefone_fixo, p.telefone_celular FROM paciente p {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null, $clientStatus = 1)
    {
        $filterStatus = $clientStatus == -1 ? "" : " AND p.ativo = $clientStatus ";
        $query = (is_null($search)) ? " WHERE 1 = 1 $filterStatus " : " WHERE 1 = 1 $filterStatus AND (CONCAT(p.nome, ' ', p.sobrenome) LIKE '%{$search}%' OR p.email LIKE '%{$search}%' OR p.cpf LIKE '%{$search}%') ";
        $dados = $this->query("SELECT count(p.idpaciente) as totalRegistro FROM paciente p {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro()
    {
        $dados = $this->query("SELECT count(idpaciente) as totalRegistro FROM paciente ");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function excluir($id)
    {
        return $this->query("UPDATE paciente SET ativo = 0 WHERE idpaciente = $id");
    }

    public function salvarFoto($fotobytes, $fotonome, $fototipo, $id)
    {
        if (!isset($fotobytes) && !isset($fotonome) && !isset($fototipo)) {
            try {
                $dados = $this->query("UPDATE paciente SET foto_bytes=(NULL) , foto_nome=(NULL), foto_tipo=(NULL) WHERE idpaciente=$id");
                return $dados;
            } catch (Exception $exc) {
                return $exc;
            }
        } else {
            try {
                $dados = $this->query("UPDATE paciente SET foto_bytes='$fotobytes' , foto_nome='$fotonome', foto_tipo='$fototipo' WHERE idpaciente=$id");
                return $dados;
            } catch (Exception $exc) {
                return $exc;
            }
        }
    }

    public function relatorioPaciente($letraInicial, $nome, $dataDE, $dataATE, $dataInicioDE, $dataInicioATE)
    {
        $query = $letraInicial == "TODOS" ? " WHERE 1 = 1 " : " WHERE p.nome LIKE '$letraInicial%' ";
        $queryDatas = "";
        if (!$nome == null) {
            $query = " WHERE p.nome LIKE '%$nome%' ";
        }
        if (isset($dataDE) && isset($dataATE)) {
            $ddde = new DateTime($dataDE);
            $ddate = new DateTime($dataATE);
            if ($ddate >= $ddde) {
                $queryDatas .= " AND CAST(e.created as DATE) between '$dataDE' AND '$dataATE' ";
            }
        } elseif (isset($dataDE)) {
            $queryDatas .= " AND CAST(e.created as DATE) > '$dataDE' ";
        } elseif (isset($dataATE)) {
            $queryDatas .= " AND CAST(e.created as DATE) < '$dataATE' ";
        }

        if (isset($dataInicioDE) && isset($dataInicioATE)) {
            $ddde = new DateTime($dataInicioDE);
            $ddate = new DateTime($dataInicioATE);
            if ($ddate >= $ddde) {
                $queryDatas .= " AND CAST(e.data_inicio as DATE) between '$dataInicioDE' AND '$dataInicioATE' ";
            }
        } elseif (isset($dataInicioDE)) {
            $queryDatas .= " AND CAST(e.data_inicio as DATE) > '$dataInicioDE' ";
        } elseif (isset($dataInicioATE)) {
            $queryDatas .= " AND CAST(e.data_inicio as DATE) < '$dataInicioATE' ";
        }

        $dados = $this->query("SELECT p.idpaciente, p.nome, p.sobrenome, p.email, p.cpf, pro.nome, pro.sobrenome ,e.* 
                FROM paciente p 
                LEFT JOIN evento e ON (p.idpaciente = e.id_paciente $queryDatas) 
                LEFT JOIN agenda a ON (e.id_agenda = a.idagenda) 
                LEFT JOIN profissional pro ON (a.id_profissional = pro.idprofissional) $query 
                ORDER BY p.nome, p.sobrenome, e.data_inicio");
        return $dados;
    }

    public function quantidadeAniversariantesMes()
    {
        $dados = $this->query("select count(idpaciente) as total from paciente where MONTH(data_nascimento) = MONTH(NOW()) AND ativo = 1 ;");
        return isset($dados[0][0]['total']) ? $dados[0][0]['total'] : 0;
    }

    public function retornarAniversariantesDoMes()
    {
        return $this->query("select nome, sobrenome, data_nascimento from paciente where MONTH(data_nascimento) = MONTH(NOW()) AND ativo = 1 order by DAY(data_nascimento);");
    }

    public function lembrar_paciente()
    {
        return $this->query("              
            SELECT 
                CASE CAST(e.data_inicio as DATE) 
                WHEN CAST(NOW() as DATE) THEN 
                                'Hoje'
                WHEN CAST(ADDDATE(NOW(),1) as DATE) THEN 
                                'Amanhã'
                WHEN CAST(ADDDATE(NOW(),2) as DATE) THEN 
                                'Em 2 dias'	
                END	as Dia, p.email, p.nome, DATE_FORMAT(e.data_inicio,'%H:%i') as Horario, Concat(pro.nome,' ', pro.sobrenome) as Profissional, 
                                 c.descricao as Aula, cli.fantasia
            FROM evento e  
            INNER JOIN paciente p ON(e.id_paciente = p.idpaciente)
            INNER JOIN agenda a ON(a.idagenda = e.id_agenda)
            INNER JOIN profissional pro ON(pro.idprofissional = a.id_profissional)
            INNER JOIN recebimento r ON(r.idrecebimento = e.id_recebimento)
            INNER JOIN categoria_aula c ON(c.idcategoriaaula = r.id_categoria_aula)
            INNER JOIN clinica cli ON(cli.idclinica = r.id_clinica)
            WHERE 
            CAST(e.data_inicio as DATE) between CAST(NOW() as DATE) and CAST(ADDDATE(NOW(),2) as DATE) 
            and p.email is not null and p.email <> ''
            GROUP By p.idpaciente
        ");
    }

    public function relatorioPacientePDF($idagenda, $idpaciente, $status, $recebimentos)
    {

        $where = is_null($recebimentos) || $recebimentos == "" ? "" : " AND e.id_recebimento IN ($recebimentos) ";

        $dados = $this->query("SELECT p.idpaciente, p.nome, p.sobrenome, p.email, p.cpf, pro.nome, pro.sobrenome ,e.*, es.descricao 
                FROM paciente p 
                LEFT JOIN evento e ON (p.idpaciente = e.id_paciente)
                LEFT JOIN evento_status es ON (e.id_evento_status = es.ideventostatus)
                LEFT JOIN agenda a ON (e.id_agenda = a.idagenda) 
                LEFT JOIN profissional pro ON (a.id_profissional = pro.idprofissional) 
                WHERE p.idpaciente = $idpaciente AND a.idagenda = $idagenda
                AND e.id_evento_status in ( " . substr($status, 0, -1) . ")
                $where
                ORDER BY p.nome, p.sobrenome, e.data_inicio");
        return $dados;
    }

    public function relatorioPacientePorRecebimentoPDF($idrecebimento)
    {

        $dados = $this->query("SELECT p.idpaciente, p.nome, p.sobrenome, p.email, p.cpf, pro.nome, pro.sobrenome ,e.*, es.descricao 
                FROM paciente p 
                LEFT JOIN evento e ON (p.idpaciente = e.id_paciente)
                LEFT JOIN evento_status es ON (e.id_evento_status = es.ideventostatus)
                LEFT JOIN agenda a ON (e.id_agenda = a.idagenda) 
                LEFT JOIN profissional pro ON (a.id_profissional = pro.idprofissional) 
                WHERE e.id_recebimento = $idrecebimento
                ORDER BY p.nome, p.sobrenome, e.data_inicio");
        return $dados;
    }
}
