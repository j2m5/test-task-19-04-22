<?php
require_once 'AbstractController.php';

class HomeController extends AbstractController
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    protected function getTitle()
    {
        return 'Главная страница';
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
        if (!$this->auth_user) $this->redirect('?route=login');
        $usersWithoutOrders = $this->user->getUsersWithoutOrders();
        $item = null;
        for ($i = 0; $i < count($usersWithoutOrders); $i++) {
            $vars['login'] = $usersWithoutOrders[$i]['login'];
            $item .= $this->getTemplateWithVars($vars, 'user');
        }
        $result['loginsWithoutOrders'] = $item;
        $usersWithOrders = $this->user->getUsersWithOrders();
        $item = null;
        for ($i = 0; $i < count($usersWithOrders); $i++) {
            $vars['login'] = $usersWithOrders[$i]['login'];
            $item .= $this->getTemplateWithVars($vars, 'user');
        }
        $result['loginsWithOrders'] = $item;
        $item = null;
        $emailDuplicates = $this->user->getEmailDuplicates();
        for ($i = 0; $i < count($emailDuplicates); $i++) {
            $vars['login'] = $emailDuplicates[$i]['email'];
            $item .= $this->getTemplateWithVars($vars, 'user');
        }
        $result['emailDuplicates'] = $item;
        $result['currentName'] = $this->auth_user['name'];
        return $this->getTemplateWithVars($result, 'home');
    }

    public function editProfile()
    {
        $redirect = $_SERVER['HTTP_REFERER'];
        $name = $this->data['name'];
        $old_password = md5($this->data['old_password']);
        $new_password = $this->data['password'];
        $session_password = $_SESSION['password'];
        if ($name === '') return $this->getMessage('Имя не должно быть пустым', $redirect);
        if ($old_password === '') return $this->getMessage('Укажите старый пароль', $redirect);
        if ($new_password === '') return $this->getMessage('Укажите новый пароль', $redirect);
        if ($session_password !== $old_password) return $this->getMessage('Старый пароль указан неверно', $redirect);
        $result = $this->user->editUser($this->auth_user['id'], $name, md5($new_password));
        if ($result) {
            $_SESSION['password'] = md5($new_password);
            return $this->getMessage('Настройки профиля изменены', $redirect);
        }
    }
}
