<?php

class RegistroModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }
}