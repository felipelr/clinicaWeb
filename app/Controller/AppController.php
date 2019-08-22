<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::uses('User', 'Model');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $User;
    public $_prefixSession = "clinica_adm_";
    public $components = array(
        "Session",
        "Cookie",
        "ImageCropResize.Image"
    );

    public function beforeFilter() {
        $this->User = new User();
        Security::setHash('md5');
    }

    protected function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    protected function _logar($email, $senha, $lembrar = false) {
        $this->User = new User();
        $dadosUser = $this->User->validaLogin($email, $senha);
        if (isset($dadosUser['idusuario'])) {
            CakeSession::write($this->_prefixSession . "uid", $dadosUser['idusuario']);
            CakeSession::write($this->_prefixSession . "logado", true);
            CakeSession::write($this->_prefixSession . "email", $email);
            CakeSession::delete('Message.flash');
            if ($this->_cookie) {
                $valor = sha1(join("#", array($email, $this->get_client_ip(), env("HTTP_USER_AGENT"))));
                $cookieTime = "12 months";
                $this->Cookie->write($this->_prefixSession . "token", $valor, true, $cookieTime);
            }
            if ($lembrar) {
                $this->_lembrarDados($email, $senha);
            }
            return true;
        } else {
            CakeSession::delete($this->_prefixSession . "uid");
            CakeSession::delete($this->_prefixSession . "email");
            CakeSession::write($this->_prefixSession . "logado", false);
            $this->Session->setFlash(__("Usuário inválido!"), 'erro');
            return false;
        }
    }

    protected function _logado($cookie = true) {
        if (!CakeSession::check($this->_prefixSession . "logado") && CakeSession::read($this->_prefixSession . "logado") === FALSE) {
            if ($cookie) {
                return $this->_dadosLembrados();
            } else {
                $this->Session->setFlash(__("Você não está logado!"), 'alerta');
                return false;
            }
        }
        if ($this->_cookie) {
            if ($this->Cookie->check($this->_prefixSession . "token") === FALSE) {
                $this->Session->setFlash(__("Você não está logado!"), 'alerta');
                return false;
            } else {
                $valor = sha1(join("#", array(CakeSession::read($this->_prefixSession . "email"), $this->get_client_ip(), env("HTTP_USER_AGENT"))));
                if ($this->Cookie->read($this->_prefixSession . "token") !== $valor) {
                    $this->Session->setFlash(__("Você não está logado!"), 'alerta');
                    return false;
                }
            }
        }
        return CakeSession::check($this->_prefixSession . "uid");
    }

    protected function _dadosLembrados() {
        if ($this->Cookie->check($this->_prefixSession . "login_email") && $this->Cookie->check($this->_prefixSession . "login_senha")) {
            $email = base64_decode(substr($this->Cookie->check($this->_prefixSession . "login_email"), 1));
            $senha = base64_decode(substr($this->Cookie->check($this->_prefixSession . "login_senha"), 1));
            return $this->_logar($email, $senha, true);
        }
        return false;
    }

    protected function _lembrarDados($email, $senha) {
        $tempo = strtotime("+7 day", time());
        $email = rand(1, 9) . base64_encode($email);
        $senha = rand(1, 9) . base64_encode($senha);
        $this->Cookie->write($this->_prefixSession . "login_email", $email, true, $tempo);
        $this->Cookie->write($this->_prefixSession . "login_senha", $senha, true, $tempo);
    }

    protected function _sair($cookie = true) {
        CakeSession::delete($this->_prefixSession . "email");
        CakeSession::delete($this->_prefixSession . "uid");
        CakeSession::write($this->_prefixSession . "logado", false);
        if ($this->_cookie && $this->Cookie->check($this->_prefixSession . "token")) {
            $this->Cookie->delete($this->_prefixSession . "token");
        }
        if ($cookie) {
            $this->_limparLembrados();
        }
        return true;
    }

    protected function _limparLembrados() {
        if ($this->Cookie->check($this->_prefixSession . "login_email")) {
            $this->Cookie->delete($this->_prefixSession . "login_email");
        }
        if ($this->Cookie->check($this->_prefixSession . "login_senha")) {
            $this->Cookie->delete($this->_prefixSession . "login_senha");
        }
    }

    protected function converteData($data) {
        return (preg_match('/\//', $data)) ? implode('-', array_reverse(explode('/', $data))) : implode('/', array_reverse(explode('-', $data)));
    }
}
