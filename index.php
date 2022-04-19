<?php

mb_internal_encoding('UTF-8');
require_once 'db/Database.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/LoginController.php';
require_once 'controllers/RegisterController.php';
require_once 'controllers/NotFoundController.php';

$db = new DataBase();
$route = $_GET['route'];

switch ($route) {
    case '':
        $content = new HomeController($db);
        break;
    case 'login':
        $content = new LoginController($db);
        break;
    case 'register':
        $content = new RegisterController($db);
        break;
    default: $content = new NotFoundController($db);
}
echo $content->getContent();
