<?php

class Router{

public function run(){

    $url=$_GET["url"]?? "user/index";
    $uplParts=explode("/",$url);
    $controllerName=ucfirst($uplParts[0])."Controller";
    if(!file_exists("controllers/$controllerName.php")){
        $controllerName="UserController";
    }
    $methodName=$uplParts[1]??"index";
    $params = array_slice($uplParts,2);
    require_once "controllers/$controllerName.php";
    $controller=new $controllerName();

    if(method_exists($controller,$methodName)){
        call_user_func_array([$controller,$methodName],$params);
    } else {
         echo "Метод $methodName не найден в контроллере $controllerName";
    }
}

}