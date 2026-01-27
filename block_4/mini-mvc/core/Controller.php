<?php

class Controller{
    protected function view($viewName, $data=[]){
        extract($data);
        require_once "views/$viewName.php";
    }

    protected function redirect($url){
        header("Location:".BASE_URL."/$url");
        exit;
    }
}