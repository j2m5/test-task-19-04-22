<?php
require_once 'AbstractModel.php';

class User extends AbstractModel
{
    public function __construct($db) {
        parent::__construct("users", $db);
    }

    public function addUser($name, $login, $email, $password) {
        return $this->add(["name" => $name, "login" => $login, "email" => $email, "password" => $password]);
    }

    public function editUser($id, $name, $password) {
        return $this->edit($id, ["name" => $name, "password" => $password]);
    }

    public function checkUser($login, $password) {
        $user = $this->getUserOnLogin($login);
        if (!$user) return false;
        return $user["password"] === $password;
    }

    public function getUserOnLogin($login) {
        $id = $this->getField("id", "login", $login);
        return $this->get($id);
    }
}
