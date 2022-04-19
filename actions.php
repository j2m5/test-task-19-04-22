<?php
require_once 'db/Database.php';
require_once 'controllers/RegisterController.php';
require_once 'controllers/LoginController.php';
require_once 'controllers/HomeController.php';

$db = new Database();
$register = new RegisterController($db);
$login = new LoginController($db);
$home = new HomeController($db);

if (isset($_POST['register'])) {
    $result = $register->register();
} elseif (isset($_POST['login'])) {
    $result = $login->login();
} elseif (isset($_GET['logout'])) {
    $result = $login->logout();
} elseif (isset($_POST['edit'])) {
    $result = $home->editProfile();
}
else exit;
$register->redirect($result);
