<?php
session_start();
require_once 'models/User.php';
require_once 'models/Order.php';
require_once 'db/Config.php';

abstract class AbstractController
{
    protected $user;
    protected $order;
    protected $auth_user;
    protected $data;

    public function __construct($db)
    {
        $this->user = new User($db);
        $this->order = new Order($db);
        $this->auth_user = $this->getUser();
        $this->data = $this->secureData(array_merge($_POST, $_GET));
    }

    private function secureData($data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) $this->secureData($value);
            else $data[$key] = htmlspecialchars($value);
        }
        return $data;
    }

    protected function getUser() {
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        if ($this->user->checkUser($login, $password)) return $this->user->getUserOnLogin($login);
        else return false;
    }

    public function getContent() {
        $vars['title'] = $this->getTitle();
        $vars['meta_desc'] = $this->getDescription();
        $vars['meta_key'] = $this->getKeyWords();
        $vars['header'] = $this->getHeader();
        $vars['notification'] = $this->getNotification();
        $vars['main'] = $this->getMain();
        $vars['footer'] = $this->getFooter();
        return $this->getTemplateWithVars($vars, 'index');
    }

    abstract protected function getTitle();
    abstract protected function getDescription();
    abstract protected function getKeyWords();
    abstract protected function getMain();

    protected function getHeader() {
        $vars['login'] = $this->auth_user ? $this->auth_user['login'] : '';
        $template = $this->auth_user ? 'header_logged' : 'header';
        return $this->getTemplateWithVars($vars, $template);
    }

    protected function getNotification() {
        if (isset($_SESSION['message'])) {
            $vars['message'] = $_SESSION['message'];
            unset($_SESSION['message']);
            return $this->getTemplateWithVars($vars, 'message');
        }
        return '';
    }

    protected function getFooter() {
        return '';
    }

    protected function getTemplate($name) {
        return file_get_contents('views/'.$name.'.html');
    }

    protected function getTemplateWithVars($vars, $template) {
        return $this->replaceContent($vars, $this->getTemplate($template));
    }

    private function replaceContent($vars, $content) {
        $search = [];
        $replace = [];
        $i = 0;
        foreach ($vars as $key => $value) {
            $search[$i] = '{{'.$key.'}}';
            $replace[$i] = $value;
            $i++;
        }
        return str_replace($search, $replace, $content);
    }

    public function getMessage($message, $redirect) {
        $_SESSION['message'] = $message;
        return $redirect;
    }

    public function redirect($link) {
        header("Location: $link");
        exit;
    }
}
