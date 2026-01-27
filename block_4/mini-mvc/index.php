<?php

require_once 'config.php';

spl_autoload_register(function($className) {
    if (file_exists("core/$className.php")) {
        require_once "core/$className.php";
    } elseif (file_exists("controllers/$className.php")) {
        require_once "controllers/$className.php";
    } elseif (file_exists("models/$className.php")) {
        require_once "models/$className.php";
    }
});

$router = new Router();
$router->run();
?>