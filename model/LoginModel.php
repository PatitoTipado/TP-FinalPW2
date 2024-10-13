<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function validarLogin()
    {
        //return $this->database->query("SELECT * FROM canciones");
    }

}