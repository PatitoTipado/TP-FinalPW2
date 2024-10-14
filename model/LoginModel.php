<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function validarLogin($username,$password)
    {
        if($username=="123" && $password=="1234"){
            return true;
        }
        return false;
    }

}