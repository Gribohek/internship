<?php

 class User extends Model{

    public function __construct(){
        parent::__construct("users");
    }
    public function getAll(){
        return parent::getAll();
    }
    public function create($data){
        return parent::create($data);
    }
    public function delete($id){
        return parent::delete($id);
    }
 }