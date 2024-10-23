<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function iniciarNuevaPartida($id_jugador)
    {

        if($this->isJugadorValido($id_jugador)) {
            $nivel = $this->obtenerNivelJugador($id_jugador);
            $fecha = $this->obtenerFechaRegistro();
            $sql = "INSERT INTO partidas(usuario_id,fecha_de_partida,nivel,estado) 
            VALUES ('$id_jugador','$fecha','$nivel','en curso')";
            if ($this->database->execute($sql)) {
                return $this->obtenerElIdDePartida($id_jugador, $fecha);
            }
        }
        return false;
    }

    public function obtenerDataPartida()
    {
        $pregunta= $this->obtenerPreguntaDePartida();

        $data['id_pregunta']=0;
        $data['pregunta']=1;
        $data['opciones']=[
            ['opcion' => 0],
            ['opcion' => 1],
            ['opcion' => 2],
            ['opcion' => 3]
        ];

        return $data;
    }

    private function isJugadorValido($id_jugador)
    {

        $usuario = $this->obtenerJugador($id_jugador);

        if(!$usuario){
            return false;
        }

        if($usuario['estado']!='activo'){
            return false;
        }

        if($usuario['rol']!='jugador'){
            return false;
        }

        return true;
    }

    private function obtenerJugador($id_jugador)
    {

        $sql = "SELECT * FROM usuarios WHERE id = '$id_jugador'";
        $result = $this->database->execute($sql);

        if ($result->num_rows == 0) {
            return false;
        }
        return $result->fetch_assoc();
    }

    private function obtenerNivelJugador($id_jugador)
    {

        $usuario = $this->obtenerJugador($id_jugador);

        if(!$usuario){
            return false;
        }

        return $usuario['nivel'];

    }

    private function obtenerFechaRegistro()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha_actual = new DateTime();

        return $fecha_actual->format('Y-m-d H:i:s');
    }

    private function obtenerElIdDePartida($id_jugador, $fecha)
    {

        $sql = "SELECT id FROM partidas WHERE usuario_id = '$id_jugador' AND fecha_de_partida='$fecha'";
        $result = $this->database->execute($sql);

        if ($result->num_rows == 0) {
            return false;
        }
        $row=$result->fetch_assoc();

        return $row['id'];
    }

    private function obtenerPreguntaDePartida()
    {
    }
}