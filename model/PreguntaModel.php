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
        return $this->database->query("SELECT * FROM preguntas");
        

        // $data['result'] = true;
        // $data['opciones'] = [];

        // $preguntas = $result->fetch_all(MYSQLI_ASSOC);
        // while ($row = $result->fetch_all(MYSQLI_ASSOC)) {
        //     $id = $row['id'];
        //     $opciones = $this->obtenerOpcionesPorIdDePregunta($id);
        //     $data['pregunta'] = $row['pregunta'];
        //     $data['opciones'] = [
        //         ['opcion' => $opciones['opcion1']],
        //         ['opcion' => $opciones['opcion2']],
        //         ['opcion' => $opciones['opcion_correcta']],
        //         ['opcion' => $opciones['opcion3']]
        //     ];
        //     return $data;
        // }


           
        

       // return $data;
    }

    public function opciones() {
        $preguntas = $this->obtenerPreguntas();

    }

    public function obtenerOpcionesPorIdDePregunta($id)
    {
        return $this->database->query("SELECT * FROM opciones WHERE pregunta_id = " . $id);
        // $result = $this->database->execute($sql);

        // if ($result->num_rows == 0) {

        //     return false;
        // }

        // return $result->fetch_assoc();
    }
}
