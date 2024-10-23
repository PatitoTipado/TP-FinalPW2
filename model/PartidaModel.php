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

    public function obtenerDataPartida($id_partida)
    {
        $pregunta= $this->obtenerPreguntaDePartidaNoRespondida($id_partida);

        //esto lo utilize para testear podriamos encapsular esto en un metodo y devolver error como valor
        if(!$pregunta){
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

        $id_pregunta= $pregunta['id'];

        $opciones = $this->obtenerOpcionesPorIdDePregunta($id_pregunta);

        $data['id_pregunta']=$pregunta['id'];
        $data['pregunta']=$pregunta['pregunta'];
        $data['opciones']=[
            ['opcion' => $opciones['opcion1']],
            ['opcion' => $opciones['opcion2']],
            ['opcion' => $opciones['opcion_correcta']],
            ['opcion' => $opciones['opcion3']]
        ];

        return $data;
    }

    public function validarRespuesta($respuesta,$id_pregunta,$id_jugador,$id_partida)
    {

        $opciones= $this->obtenerOpcionesPorIdDePregunta($id_pregunta);

        //hizo magia negra como no obtendre el id de pregunta me quiso romper el sistema le doy la negativa por boby
        if(!$opciones){
            return false;
        }

        if($respuesta==$opciones['opcion_correcta']){
            //le damos punbtos
            //podriamos ver de como resolver el tiempo

            $insert = "INSERT INTO pregunta_partida 
            (respuesta_usuario, pregunta_id, usuario_id, partida_id, respondio_correctamente)
            VALUES ('$respuesta', $id_pregunta, $id_jugador, $id_partida, 'bien')";

            $this->database->execute($insert);

            $update = "UPDATE partidas SET puntaje_total = puntaje_total + 1
            WHERE id = $id_partida";

            $this->database->execute($update);

            return true;
        }

        return false;
    }

    public function isPartidaValida($id_partida,$id_jugador)
    {
        //validamos que exista la partida y sea del jugador

        return true;

        //validamos que no perdio

    }

    private function obtenerOpcionesPorIdDePregunta($id_pregunta){

        $sql = "SELECT * FROM opciones
        WHERE pregunta_id = '$id_pregunta'";

        $result= $this->database->execute($sql);

        if($result->num_rows==0){
            return false;
        }

        return $result->fetch_assoc();
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

    private function obtenerPreguntaDePartidaNoRespondida($id_partida)
    {
        //valido que siga existiendo la partida por si mequiere romper el sistema en otra pestaña borrando la partida
        if(!$this->obtenerPartida($id_partida)){

            return false;
        }

        $nivel_del_jugador=$this->obtenerNivelJugadorDesdePartida($id_partida);

        if(!$nivel_del_jugador){

            return false;
        }

        //le paso por el nivel que contenga el jugador
        return $this->obtenerPreguntaNoRespondida($nivel_del_jugador,$id_partida);
    }

    private function obtenerNivelJugadorDesdePartida($id_partida) {

        $sql= "SELECT * FROM partidas where id= '$id_partida'";
        $result= $this->database->execute($sql);

        if($result->num_rows ==0){
            return false;
        }

        $partida= $result->fetch_assoc();

        $id_usuario=$partida['usuario_id'];

        $jugador = $this->obtenerJugador($id_usuario);

        if(!$jugador){
            return false;
        }

        return $jugador['nivel'];
    }


    private function obtenerPreguntaNoRespondida($nivel,$id_partida) {
        // validar que sea de la partida y de acuerdo al nivel del usuario
        $sql = "SELECT * FROM preguntas WHERE nivel='$nivel' AND id NOT IN (SELECT pregunta_id FROM pregunta_partida WHERE partida_id= '$id_partida') LIMIT 1";

        // Ejecutar la consulta
        $result = $this->database ->execute($sql);

        // Verificar si se encontró una pregunta
        if ($result->num_rows > 0) {
            // Retornar la pregunta
            return $result->fetch_assoc();
        } else {
            // Si no se encontró ninguna pregunta
            return false;
        }
    }


    private function obtenerPartida($id_partida)
    {

        $sql = "SELECT * FROM partidas WHERE id = '$id_partida' AND estado='en curso'";
        $result = $this->database->execute($sql);

        if ($result->num_rows == 0) {

            return false;
        }

        return true;
    }

    private function obtenerNivelJugador($id_jugador)
    {

        $jugador=$this->obtenerJugador($id_jugador);

        if(!$jugador){
            return false;
        }

        return $jugador['nivel'];

    }


}