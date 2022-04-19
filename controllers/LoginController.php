<?php
require_once 'AbstractController.php';
require_once 'models/User.php';

class LoginController extends AbstractController
{
    protected $user;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->user = new User($db);
    }

    protected function getTitle()
    {
        return 'Авторизация';
    }

    protected function getDescription()
    {
        return '';
    }

    protected function getKeyWords()
    {
        return '';
    }

    protected function getMain()
    {
        return $this->getTemplate('login');
    }

    public function login() {
        $login = $this->data["login"];
        $password = $this->data["password"];
        $password = md5($password);
        if ($this->user->checkUser($login, $password)) {
            $_SESSION["login"] = $login;
            $_SESSION["password"] = $password;
            $this->redirect('/');
        } else {
            return $this->getMessage('Неверный логин или пароль', $_SERVER['HTTP_REFERER']);
        }
    }

    public function logout() {
        unset($_SESSION["login"]);
        unset($_SESSION["password"]);
        $this->redirect('/');
    }
}
