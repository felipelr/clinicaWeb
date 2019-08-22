<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppModel', 'Model');

/**
 * CakePHP FinanceiroTipo
 * @author BersaN & StarK
 */
class Acesso extends AppModel {

    public $useTable = "acesso";
    public $primaryKey = "idAcesso";
    //CADASTROS
    public static $ACESSO_CADASTRO_PACIENTE = 2;
    public static $ACESSO_CADASTRO_CARGO = 3;
    public static $ACESSO_CADASTRO_CONTA_BANCARIA = 4;
    public static $ACESSO_CADASTRO_FAVORECIDO = 5;
    public static $ACESSO_CADASTRO_PROFISSIONAL = 6;
    public static $ACESSO_CADASTRO_USUARIO = 7;
    public static $ACESSO_CADASTRO_TIPO_USUARIO = 8;
    public static $ACESSO_ALTERAR_TIPO_USUARIO = 26;
    public static $ACESSO_ALTERAR_USUARIO = 27;
    public static $ACESSO_ALTERAR_PROFISSIONAL = 28;
    public static $ACESSO_ALTERAR_FAVORECIDO = 29;
    public static $ACESSO_ALTERAR_CONTA_BANCARIA = 30;
    public static $ACESSO_ALTERAR_CARGO = 31;
    public static $ACESSO_EXCLUIR_PACIENTE = 32;
    public static $ACESSO_EXCLUIR_TIPO_USUARIO = 33;
    public static $ACESSO_EXCLUIR_USUARIO = 34;
    public static $ACESSO_EXCLUIR_PROFISSIONAL = 35;
    public static $ACESSO_EXCLUIR_FAVORECIDO = 36;
    public static $ACESSO_EXCLUIR_CONTA_BANCARIA = 37;
    public static $ACESSO_EXCLUIR_CARGO = 38;
    public static $ACESSO_LISTAR_PACIENTE = 39;
    public static $ACESSO_LISTAR_TIPO_USUARIO = 40;
    public static $ACESSO_LISTAR_USUARIO = 41;
    public static $ACESSO_LISTAR_PROFISSIONAL = 42;
    public static $ACESSO_LISTAR_FAVORECIDO = 43;
    public static $ACESSO_LISTAR_CONTA_BANCARIA = 44;
    public static $ACESSO_LISTAR_CARGO = 45;
    public static $ACESSO_ACESSOS_USUARIO = 62;
    public static $ACESSO_ALTERAR_EVENTOS_DISPONIVEIS = 80;
    //FINANÇAS
    public static $ACESSO_CADASTRO_DESPESA = 9;
    public static $ACESSO_CADASTRO_RECEBIMENTO = 10;
    public static $ACESSO_CADASTRO_PLANO_CONTAS = 11;
    public static $ACESSO_CADASTRO_CENTRO_CUSTO = 12;
    public static $ACESSO_CONTAS_PAGAR = 13;
    public static $ACESSO_CONTAS_RECEBER = 14;
    public static $ACESSO_EXCLUIR_DESPESA = 23;
    public static $ACESSO_EXCLUIR_RECEBIMENTO = 24;
    public static $ACESSO_LISTAR_DESPESA = 46;
    public static $ACESSO_LISTAR_RECEBIMENTO = 47;
    public static $ACESSO_GESTAO_DESPESA = 48;
    public static $ACESSO_GESTAO_RECEBIMENTO = 49;
    public static $ACESSO_ALTERAR_CENTRO_CUSTO = 59;
    public static $ACESSO_EXCLUIR_CENTRO_CUSTO = 60;
    public static $ACESSO_LISTAR_CENTRO_CUSTO = 61;
    public static $ACESSO_LISTAR_PLANO_CONTAS = 63;
    public static $ACESSO_ALTERAR_PLANO_CONTAS = 64;
    public static $ACESSO_EXCLUIR_PLANO_CONTAS = 65;
    //RELATORIOS
    public static $ACESSO_RELATORIO_PACIENTES = 15;
    public static $ACESSO_RELATORIO_DESPESAS = 16;
    public static $ACESSO_RELATORIO_RECEBIMENTOS = 17;
    public static $ACESSO_RELATORIO_CAIXAS = 18;
    public static $ACESSO_RELATORIO_CHEQUES = 19;
    public static $ACESSO_RELATORIO_COMISSAO = 78;
    public static $ACESSO_RELATORIO_VALOR_TOTAL_COMISSAO = 79;
    //CONFIGURAÇÕES
    public static $ACESSO_CADASTRO_CAIXA = 20;
    public static $ACESSO_CADASTRO_PLANO_SESSAO = 21;
    public static $ACESSO_CADASTRO_TIPO_FINANCEIRO = 22;
    public static $ACESSO_LISTAR_CAIXA = 66;
    public static $ACESSO_ALTERAR_CAIXA = 67;
    public static $ACESSO_EXCLUIR_CAIXA = 68;
    public static $ACESSO_LISTAR_TIPO_FINANCEIRO = 69;
    public static $ACESSO_EXCLUIR_TIPO_FINANCEIRO = 70;
    public static $ACESSO_ALTERAR_TIPO_FINANCEIRO = 71;
    public static $ACESSO_ALTERAR_PLANO_SESSOES = 72;
    public static $ACESSO_EXCLUIR_PLANO_SESSOES = 73;
    public static $ACESSO_LISTAR_PLANO_SESSOES = 74;
    //AGENDA
    public static $ACESSO_LISTAR_AGENDA = 50;
    public static $ACESSO_SELECIONAR_AGENDA = 51;
    public static $ACESSO_CADASTRO_AGENDA = 52;
    public static $ACESSO_EXCLUIR_AGENDA = 57;
    public static $ACESSO_ADICIONAR_EVENTO = 53;
    public static $ACESSO_ATUALIZAR_EVENTO = 54;
    public static $ACESSO_EXCLUIR_EVENTO = 58;
    //DASHBOARD
    public static $ACESSO_CONTAS_PAGAR_DASHBOARD = 55;
    public static $ACESSO_CONTAS_RECEBER_DASHBOARD = 56;
    public static $ACESSO_GRAFICOS_DASHBOARD = 75;
    public static $ACESSO_CONTAS_RECEBER_MES = 76;
    public static $ACESSO_CONTAS_PAGAR_MES = 77;

    public function listarJQuery($search = null, $inicio = null, $totalRegistros = null, $ordenacao = null) {
        $order = (is_null($ordenacao)) ? "" : "ORDER BY $ordenacao";
        $sql = (is_null($inicio) || is_null($totalRegistros)) ? "" : "LIMIT $inicio,$totalRegistros";
        $query = (is_null($search)) ? "" : " WHERE a.descricao LIKE '%{$search}%' ";
        return $this->query("SELECT a.* FROM acesso a {$query} {$order} {$sql};");
    }

    public function totalRegistroFiltrado($search = null) {
        $query = (is_null($search)) ? "" : " WHERE a.descricao LIKE '%{$search}%' ";
        $dados = $this->query("SELECT count(a.idAcesso) as totalRegistro FROM acesso a {$query}");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function totalRegistro() {
        $dados = $this->query("SELECT count(idAcesso) as totalRegistro FROM acesso");
        return isset($dados[0][0]['totalRegistro']) ? $dados[0][0]['totalRegistro'] : 0;
    }

    public function excluir($idAcesso) {
        $this->query("delete from acesso where idAcesso = $idAcesso");
    }

    public function retornarPorId($id) {
        $dados = $this->query("SELECT * FROM acesso WHERE idAcesso = $id  LIMIT 1");
        return $dados[0]["acesso"];
    }

    public function acessosUsuario($idTipoUsuario) {
        return $this->query("SELECT a.*, IFNULL(u.id_tipo_usuario,0) as id_tipo_usuario FROM acesso a LEFT JOIN usuario_acesso u ON(a.idAcesso = u.id_acesso AND u.id_tipo_usuario = $idTipoUsuario) ORDER BY categoria, descricao DESC ");
    }

    public function inserirUsuarioAcesso($Acessos, $idTipoUsuario, $idUser) {
        $query = " DELETE FROM usuario_acesso WHERE id_tipo_usuario = $idTipoUsuario; ";

        foreach ($Acessos as $value) {
            $query .= " INSERT INTO usuario_acesso (id_tipo_usuario, id_acesso, id_usuario_permissao, created, modified) VALUES ($idTipoUsuario, $value, $idUser, NOW(), NOW()); ";
        }

        $this->query($query);
    }

    public function validarAcesso($idtipousuario, $idacesso) {
        $dados = $this->query("SELECT id_tipo_usuario FROM usuario_acesso WHERE id_tipo_usuario = $idtipousuario AND id_acesso = $idacesso; ");
        return isset($dados[0]['usuario_acesso']);
    }

}
