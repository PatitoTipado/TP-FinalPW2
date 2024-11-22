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
        return $this->database->query("SELECT p.id id_pregunta, p.pregunta, o.opcion1, o.opcion2, o.opcion3, o.opcion_correcta, p.estado FROM preguntas p
        JOIN opciones o ON p.id = o.pregunta_id  
        WHERE p.estado = 'aprobada'                
        GROUP BY p.id");
    }

    public function obtenerPregunta($id)
    {
        return $this->database->query("SELECT p.id id_pregunta, p.pregunta, o.opcion1, o.opcion2, o.opcion3, o.opcion_correcta FROM preguntas p
        JOIN opciones o ON p.id = o.pregunta_id WHERE p.id = '$id'");
    }

    public function obtenerReportes()
    {
        return $this->database->query("SELECT p.id id_pregunta, p.pregunta, o.opcion1, o.opcion2, o.opcion3, o.opcion_correcta, p.estado FROM preguntas p
        JOIN opciones o ON p.id = o.pregunta_id JOIN reportes r ON r.pregunta_id = p.id            
        GROUP BY p.id");
    }

    public function obtenerPreguntasSugeridas()
    {
        return $this->database->query("SELECT p.id id_pregunta, p.pregunta, p.tipo_pregunta, o.opcion1, o.opcion2, o.opcion3, o.opcion_correcta, p.estado FROM preguntas p
        JOIN opciones o ON p.id = o.pregunta_id  
        WHERE p.tipo_pregunta = 'sugerida' AND p.estado = 'pendiente'                
        GROUP BY p.id");
    }

    public function agregarPregunta($pregunta, $nivel)
    {
        $sql = "SELECT COUNT(*) AS cantidad FROM preguntas WHERE pregunta = '$pregunta'";
        $result = $this->database->execute($sql);
        $row = $result->fetch_assoc();
        $cantidad = $row['cantidad'];

        if ($cantidad > 0) {
            return "La pregunta ya existe.";
        }

        $sql = "INSERT INTO preguntas (pregunta, nivel, categoria_id, usuario_id, estado, fecha_creacion) 
            VALUES ('$pregunta', '$nivel', 1, 1, 'aprobada', NOW())";

        $this->database->execute($sql);

        $idPregunta = $this->database->getLastInsertId();

        return $idPregunta;
    }

    public function agregarOpciones($idPregunta, $opcion1, $opcion2, $opcion3, $opcionCorrecta)
    {
        $sql = "INSERT INTO opciones (pregunta_id, opcion1, opcion2, opcion3, opcion_correcta) 
                    VALUES ($idPregunta, '$opcion1', '$opcion2', '$opcion3', '$opcionCorrecta')";

        $this->database->execute($sql);
    }

    public function agregarPreguntaConOpciones($pregunta, $nivel, $opcion1, $opcion2, $opcion3, $opcionCorrecta)
    {
        $idPregunta = $this->agregarPregunta($pregunta, $nivel);

        if (is_string($idPregunta)) {
            return "La pregunta ya existe";
        }

        $this->agregarOpciones($idPregunta, $opcion1, $opcion2, $opcion3, $opcionCorrecta);
    }

    public function modificarPregunta($id, $pregunta)
    {
        if ($pregunta !== "") {
            $sql = "UPDATE preguntas SET pregunta = '$pregunta' WHERE id = '$id'";
            $this->database->execute($sql);
        }
    }

    public function modificarOpciones($idPregunta, $opcion1, $opcion2, $opcion3, $opcionCorrecta)
    {
        if ($opcion1 !== "") {
            $sql = "UPDATE opciones
            SET opcion1 = '$opcion1'
            WHERE pregunta_id = '$idPregunta'";

            $this->database->execute($sql);
        }

        if ($opcion2 !== "") {
            $sql = "UPDATE opciones
            SET opcion2 = '$opcion2'
            WHERE pregunta_id = '$idPregunta'";

            $this->database->execute($sql);
        }

        if ($opcion3 !== "") {
            $sql = "UPDATE opciones
            SET opcion3 = '$opcion3'
            WHERE pregunta_id = '$idPregunta'";

            $this->database->execute($sql);
        }

        if ($opcionCorrecta !== "") {
            $sql = "UPDATE opciones
            SET opcion_correcta = '$opcionCorrecta'
            WHERE pregunta_id = '$idPregunta'";

            $this->database->execute($sql);
        }
    }

    public function modificarPreguntaConOpciones($idPregunta, $pregunta, $opcion1, $opcion2, $opcion3, $opcionCorrecta)
    {
        $this->modificarPregunta($idPregunta, $pregunta);

        $this->modificarOpciones($idPregunta, $opcion1, $opcion2, $opcion3, $opcionCorrecta);
    }

    public function eliminarPregunta($id)
    {
        $sql = "DELETE FROM preguntas WHERE id = $id";
        $this->database->execute($sql);
    }

    public function eliminarOpciones($idPregunta)
    {
        $sql = "DELETE FROM opciones WHERE pregunta_id = $idPregunta";
        $this->database->execute($sql);
    }

    public function eliminarPreguntaConOpciones($idPregunta)
    {
        $this->eliminarOpciones($idPregunta);
        $this->eliminarPregunta($idPregunta);
    }

    public function aprobarPreguntaReportada($id) {}

    public function aprobarPreguntaSugerida($id)
    {
        $sql = "UPDATE preguntas SET estado = 'aprobada' WHERE id = '$id'";
        $this->database->execute($sql);
    }

    public function eliminarPreguntaReportada($id) {
        
    }

    public function rechazarPreguntaSugerida($id)
    {
        $sql = "DELETE FROM opciones WHERE pregunta_id = $id";
        $this->database->execute($sql);

        $sql = "DELETE FROM preguntas WHERE id = $id";
        $this->database->execute($sql);
    }
}
