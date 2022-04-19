<?php
require_once 'AbstractController.php';
require_once 'models/User.php';

class RegisterController extends AbstractController
{
    protected $user;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->user = new User($db);
    }

    protected function getTitle()
    {
        return 'Регистрация';
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
        return $this->getTemplate('register');
    }

    public function register()
    {
        $redirect = $_SERVER['HTTP_REFERER'];
        $name = $this->data['name'];
        $login = $this->data['login'];
        $email = $this->data['email'];
        $password = $this->data['password'];
        $password_confirmation = $this->data['password_confirmation'];
        if (!preg_match("/^[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i", $email)) return $this->getMessage('Не валидный Email', $redirect);
        if ($name === '') return $this->getMessage('Имя не должно быть пустым', $redirect);
        if ($email === '') return $this->getMessage('Email не должен быть пустым', $redirect);
        if ($login === '') return $this->getMessage('Логин не должен быть пустым', $redirect);
        if ($password === '') return $this->getMessage('Пароль не должен быть пустым', $redirect);
        if ($password !== $password_confirmation) return $this->getMessage('Пароли не совпадают', $redirect);
        $password = md5($password);
        $result = $this->user->addUser($name, $login, $email, $password);
        if ($result) {
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            $this->redirect(Config::SITE_ADDRESS);
        }
    }
}
