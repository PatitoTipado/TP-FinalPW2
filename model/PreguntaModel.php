<?php

class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntas()
    {
        return $this->database->query("SELECT p.id id_pregunta, p.pregunta, o.opcion1, o.opcion2, o.opcion3, o.opcion_correcta FROM preguntas p
        JOIN opciones o ON p.id = o.pregunta_id                  
        GROUP BY p.id");
    }

    public function obtenerPregunta($id)
    {
        return $this->database->query("SELECT p.pregunta, o.opcion1, o.opcion2, o.opcion3, o.opcion_correcta FROM preguntas p
        JOIN opciones o ON p.id = o.pregunta_id WHERE p.id = '$id'");
    }
}
