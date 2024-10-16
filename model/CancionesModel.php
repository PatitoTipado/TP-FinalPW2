<?php

class CancionesModel
{
    private $database;

    public function __construct(\MysqlDatabase $database)
    {
        $this->database = $database;
    }

    public function getCanciones()
    {
        return $this->database->query("SELECT * FROM canciones");
    }
}