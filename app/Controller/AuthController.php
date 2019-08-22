<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
App::uses('TipoUsuario', 'Model');
App::uses('Acesso', 'Model');
App::uses('Clinica', 'Model');

/**
 * CakePHP AuthController
 * @author Felipe
 */
class AuthController extends AppController {

    public $_idDaSessao = null;
    public $_dados = null;
    public $acesso = null;

    public function beforeFilter() {
        if (!$this->_logado()) {
            return $this->redirect(array("controller" => "login", "action" => "index"));
        } else {
            parent::beforeFilter();
            $this->layout = 'default_menu';
            $this->_idDaSessao = CakeSession::read($this->_prefixSession . "uid");
            $this->User = new User();
            $tipoUsuario = new TipoUsuario();
            $clinica = new Clinica();
            $this->_dados = $this->User->getDados($this->_idDaSessao);
            $this->_dados['tipo_usuario'] = $tipoUsuario->retornarPorId($this->_dados['id_tipo_usuario']);
            $this->_dados['clinica'] = $clinica->retornarPorId($this->_dados['id_clinica']);
            $this->set('_dados', $this->_dados);
            $this->set("iduser", $this->_idDaSessao);

            $this->verificarPermissao($this->request->params['controller'], $this->request->params['action'], $this->_dados['id_tipo_usuario']);
        }
    }

    public function verificarPermissao($controller, $action, $idtipousuario) {
        $isValido = true;
        $this->acesso = new Acesso();

        if ($controller == "agenda") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_AGENDA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_AGENDA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_AGENDA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "selecionada":
                    $idacesso = Acesso::$ACESSO_SELECIONAR_AGENDA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "ajax_evento_salvar":
                    $idacesso = Acesso::$ACESSO_ADICIONAR_EVENTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "ajax_evento_editar":
                    $idacesso = Acesso::$ACESSO_ATUALIZAR_EVENTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "ajax_evento_excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_EVENTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "ajax_evento_drop":
                    $idacesso = Acesso::$ACESSO_ATUALIZAR_EVENTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "caixa_loja") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_CAIXA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_CAIXA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_CAIXA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_CAIXA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "cargo") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_CARGO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_CARGO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_CARGO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_CARGO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "cargo") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_CARGO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_CARGO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_CARGO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_CARGO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "categoria_aula") {
            switch ($action) {
                // CRIAR EVENTOS
            }
        } else if ($controller == "centro_custos") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_CENTRO_CUSTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_CENTRO_CUSTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_CENTRO_CUSTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_CENTRO_CUSTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "conta_bancaria") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_CONTA_BANCARIA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_CONTA_BANCARIA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_CONTA_BANCARIA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_CONTA_BANCARIA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "contas_pagar") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_CONTAS_PAGAR;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "contas_receber") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_CONTAS_RECEBER;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "contas_receber") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_CONTAS_RECEBER;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "despesa") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_DESPESA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_DESPESA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_DESPESA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "gestao":
                    $idacesso = Acesso::$ACESSO_GESTAO_DESPESA;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "favorecido") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_FAVORECIDO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_FAVORECIDO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_FAVORECIDO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_FAVORECIDO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "paciente") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_PACIENTE;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_PACIENTE;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_FAVORECIDO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_FAVORECIDO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "disponibilidade_evento":
                    $idacesso = Acesso::$ACESSO_ALTERAR_EVENTOS_DISPONIVEIS;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "plano_contas") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_PLANO_CONTAS;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_PLANO_CONTAS;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_PLANO_CONTAS;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_PLANO_CONTAS;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "plano_contas") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_PLANO_SESSOES;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_PLANO_SESSAO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_PLANO_SESSOES;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "profissional") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_PROFISSIONAL;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_PROFISSIONAL;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_PROFISSIONAL;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_PROFISSIONAL;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "recebimento") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_RECEBIMENTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_RECEBIMENTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_RECEBIMENTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "gestao":
                    $idacesso = Acesso::$ACESSO_GESTAO_RECEBIMENTO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "relatorio") {
            switch ($action) {
                case "paciente":
                    $idacesso = Acesso::$ACESSO_RELATORIO_PACIENTES;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "despesa":
                    $idacesso = Acesso::$ACESSO_RELATORIO_DESPESAS;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "caixa":
                    $idacesso = Acesso::$ACESSO_RELATORIO_CAIXAS;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "recebimento":
                    $idacesso = Acesso::$ACESSO_RELATORIO_RECEBIMENTOS;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cheque":
                    $idacesso = Acesso::$ACESSO_RELATORIO_CHEQUES;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "comissao":
                    $idacesso = Acesso::$ACESSO_RELATORIO_COMISSAO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "tipo_financeiro") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_TIPO_FINANCEIRO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_TIPO_FINANCEIRO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_TIPO_FINANCEIRO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_TIPO_FINANCEIRO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "tipo_usuario") {
            switch ($action) {
                case "index":
                    $idacesso = Acesso::$ACESSO_LISTAR_TIPO_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_TIPO_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_TIPO_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_TIPO_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "acessos_usuario":
                    $idacesso = Acesso::$ACESSO_ACESSOS_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        } else if ($controller == "usuario") {
            switch ($action) {
                case "cadastrar":
                    $idacesso = Acesso::$ACESSO_CADASTRO_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "alterar":
                    $idacesso = Acesso::$ACESSO_ALTERAR_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "excluir":
                    $idacesso = Acesso::$ACESSO_EXCLUIR_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                case "acessos_usuario":
                    $idacesso = Acesso::$ACESSO_ACESSOS_USUARIO;
                    $isValido = $this->acesso->validarAcesso($idtipousuario, $idacesso);
                    break;
                default:
                    break;
            }
        }
        if (!$isValido) {
            return $this->redirect(array("controller" => "acesso", "action" => "sem_acesso"));
        }
    }

    public function validarAcesso($idtipousuario, $idacesso) {
        $acesso = new Acesso();
        $isValido = $acesso->validarAcesso($idtipousuario, $idacesso);
        if (!$isValido) {
            return $this->redirect(array("controller" => "acesso", "action" => "sem_acesso"));
        }
    }

    public function validarAcessoBoolean($idtipousuario, $idacesso) {
        $acesso = new Acesso();
        $isValido = $acesso->validarAcesso($idtipousuario, $idacesso);
        if (!$isValido) {
            return false;
        } else {
            return true;
        }
    }

}
