<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPartidas($id_usuario)
    {
        $sql="SELECT * FROM partidas WHERE usuario_id='$id_usuario'";
        $result= $this->database->execute($sql);

        $data = [];
        if ($result->num_rows == 0) {
            $data['result']=false;
            return $data;
        }

        $data['result'] = true;
        $data['opciones'] = [];

        while ($row = $result->fetch_assoc()) {
            $data['opciones'][] = [
                'id_partida' => $row['id'],
                'puntaje' => $row['puntaje_total'],
                'nivel' => $row['nivel'],
                'estado' => $row['estado'],
                'fecha' => $row['fecha_de_partida'],
            ];
        }

        return $data;
    }

    public function iniciarNuevaPartida($id_jugador)
    {

        if($this->isJugadorValido($id_jugador)) {
            $nivel = $this->obtenerNivelJugador($id_jugador);
            $fecha = $this->obtenerFechaActual();
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

        //esto lo utilize para testear cuando no encuentre la pregunta se vera esto
        if(!$pregunta){
            $data['id_pregunta']=0;
            $data['pregunta']=1;
            $data['opciones']=[
                ['opcion' => 0],
                ['opcion' => 1],
                ['opcion' => 2],
                ['opcion_correcta' => 3]
            ];
            return $data;
        }

        $id_pregunta= $pregunta['id'];

        $opciones = $this->obtenerOpcionesPorIdDePregunta($id_pregunta);

        $data['id_pregunta']=$pregunta['id'];
        $data['pregunta']=$pregunta['pregunta'];
        $data['id_partida']=$id_partida;
        $data['opciones']=[
            ['opcion' => $opciones['opcion1']],
            ['opcion' => $opciones['opcion2']],
            ['opcion' => $opciones['opcion_correcta']],
            ['opcion' => $opciones['opcion3']]
        ];

        shuffle($data['opciones']);

        $this->crearPreguntaPartida($id_partida,$id_pregunta);

        return $data;
    }

    public function validarRespuesta($respuesta, $id_pregunta, $id_jugador, $id_partida)
    {

        $isValida= $this->validarQueLaPreguntaNoSeRespondioTodaviaEnLaPartidaActual($id_pregunta,$id_partida);
        $opciones = $this->obtenerOpcionesPorIdDePregunta($id_pregunta);

        if($isValida || !$opciones){
            return "error";
        }

        $this->actualizarLaCantidadDeAparicionesDeUnaPreguntaEnUno($id_pregunta);

        $this->actualizarLaCantidadDeVecesQueRespondioUnaPreguntaUnUsuarioEnUno($id_jugador);

        $tiempo = $this->calcularTiempoValido($id_partida, $id_pregunta);

        if ($respuesta == $opciones['opcion_correcta'] && $tiempo) {

            $this->insertarRespuestaPregunta_partida($respuesta, $id_jugador, $id_partida, $id_pregunta, 'bien');

            $this->actualizarLaCantidadDeVecesRespondidasCorrectamenteDeUnaPregunta($id_pregunta);

            $this->actualizarElPuntajeTotalDeLaPartidaEnUno($id_partida);

            $this->actualizarLaCantidadDeVecesRespondidasBienEnUnUsuario($id_jugador);

            $this->actualizarDificultadUsuario($id_jugador);

            return "ganador";
        }

        $this->insertarRespuestaPregunta_partida($respuesta, $id_jugador, $id_partida, $id_pregunta, 'mal');

        $this->actualizarDificultadUsuario($id_jugador);

        $this->actualizarNivelPartida($id_partida, $id_jugador);

        $this->cambiarEstadoDePartidaAFinalizada($id_partida);

        $this->actualizarFechaDeFinalizacionDePartida($id_partida);

        $this->actualizarPuntajeMasAltoDelJugado($id_jugador, $id_partida);

        return "perdedor";
    }

    public function isPartidaValida($id_partida,$id_jugador)
    {
        $sql="SELECT * FROM partidas WHERE id='$id_partida' AND usuario_id='$id_jugador'";
        $result= $this->database->execute($sql);

        if($result->num_rows==0){
            return false;
        }
        $partida=$result->fetch_assoc();

        if($partida['estado']!='en curso'){
            return false;
        }


        return true;
    }

    public function obtenerRespuestaCorrecta($id_pregunta){

        $opciones=$this->obtenerOpcionesPorIdDePregunta($id_pregunta);

        return $opciones['opcion_correcta'];
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

    private function obtenerFechaActual()
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
        if(!$this->obtenerPartida($id_partida)){

            return false;
        }

        $nivel_del_jugador=$this->obtenerNivelJugadorDesdePartida($id_partida);

        if(!$nivel_del_jugador){

            return false;
        }

        $pregunta = $this->verificarSiTeniaPreguntaEnCursoSinRespuestaDelUsuario($id_partida);

        if($pregunta){

            return $this->obtenerPreguntaSegunSuId($pregunta['pregunta_id']);
        }

        return $this->obtenerPreguntaNoRespondidaSegunNivelDeDificultad($nivel_del_jugador,$id_partida);
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


    private function obtenerPreguntaNoRespondidaSegunNivelDeDificultad($nivel, $id_partida) {

        $sql = "SELECT * FROM preguntas 
            WHERE nivel = '$nivel' AND estado = 'aprobada' 
              AND id NOT IN 
                  (SELECT pregunta_id FROM pregunta_partida WHERE partida_id = '$id_partida') 
            ORDER BY RAND() 
            LIMIT 1";

        $result = $this->database ->execute($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return $this->obtenerPreguntaDePartidaAlAzarConCiertoNivel($nivel);
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

    private function obtenerPreguntaDePartidaAlAzarConCiertoNivel($nivel)
    {
        $sql = "SELECT * FROM preguntas WHERE nivel='$nivel' AND estado='aprobada' ORDER BY RAND() LIMIT 1 ";

        $result = $this->database->execute($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }

    private function actualizarDificultadUsuario($id_jugador)
    {

        $jugador= $this->obtenerJugador($id_jugador);

        if(!$jugador){
            return;
        }

        if ($jugador['cantidad_preguntas_respondidas'] <= 10) {
            return;
        }

        $cantidaRespondidas = $jugador['cantidad_preguntas_respondidas'];
        $cantidadCorrectas = $jugador['cantidad_respuestas_correctas'];
        $ratioRespuestasCorrectas = $this->obtenerRatioRespuestasCorrectas($cantidadCorrectas,$cantidaRespondidas);

        if ($ratioRespuestasCorrectas > 0.7) {
            $nivelDificultad = 'dificil';
        } elseif ($ratioRespuestasCorrectas < 0.3) {
            $nivelDificultad = 'facil';
        } else {
            $nivelDificultad = 'normal';
        }

        $update = "UPDATE usuarios SET nivel = '$nivelDificultad'
            WHERE id = $id_jugador";

        $this->database->execute($update);
    }

    private function actualizarNivelPartida($id_partida,$id_jugador)
    {
        $nivel= $this->obtenerNivelJugador($id_jugador);

        if (!$nivel) {
            return;
        }

        $sql="UPDATE partidas SET nivel = '$nivel'
            WHERE id = '$id_partida' ";

        $this->database->execute($sql);
    }

    private function actualizarPuntajeMasAltoDelJugado($id_jugador,$id_partida)
    {

        $partida= $this->obtenerPartida($id_partida);
        $jugador=$this->obtenerJugador($id_jugador);

        if(!$partida || !$jugador){
            return;
        }

        $puntaje_partida=$partida['puntaje_total'];

        if($puntaje_partida>$jugador['puntaje_maximo']){
            $sql="UPDATE usuarios SET puntaje_maximo = '$puntaje_partida'
            WHERE id = $id_jugador ";
            $this->database->execute($sql);
        }

    }

    private function calcularTiempoValido($id_partida, $id_pregunta)
    {
        $actual = new DateTime($this->obtenerFechaActual());

        $sql= "SELECT * FROM pregunta_partida WHERE partida_id= '$id_partida' AND pregunta_id='$id_pregunta'";

        $result=$this->database->execute($sql);

        if($result->num_rows == 0){
            return false;
        }

        $pregunta_partida = $result->fetch_assoc();

        $inicio = new DateTime($pregunta_partida['fecha_inicio']);

        $diferencia_en_segundos = $actual->getTimestamp() - $inicio->getTimestamp();

        if ($diferencia_en_segundos >= 30) {
            return false;
        }

        return true;
    }



    private function obtenerRatioRespuestasCorrectas($cantidadCorrectas, $cantidaRespondidas)
    {
        if ($cantidaRespondidas === 0) {
            return 0;
        }

        return $cantidadCorrectas / $cantidaRespondidas;
    }

    private function crearPreguntaPartida($id_partida, $id_pregunta)
    {
        $fecha_actual=$this->obtenerFechaActual();
        $sql = "INSERT INTO pregunta_partida 
        (pregunta_id, partida_id,fecha_inicio) 
        VALUES 
        ('$id_pregunta', '$id_partida','$fecha_actual')";

        $this->database->execute($sql);
    }

    private function verificarSiTeniaPreguntaEnCursoSinRespuestaDelUsuario($id_partida)
    {
        $sql = "SELECT * FROM pregunta_partida 
            WHERE partida_id = '$id_partida' AND respuesta_usuario IS NULL
            LIMIT 1";

        $result = $this->database->execute($sql);

        if ($result->num_rows == 0) {
            return false;
        }

        return $result->fetch_assoc();
    }

    private function obtenerPreguntaSegunSuId($pregunta_id)
    {

        $sql = "SELECT * FROM preguntas WHERE id='$pregunta_id' AND estado='aprobada' ";

        $result = $this->database->execute($sql);

        if ($result->num_rows > 0) {

            return $result->fetch_assoc();
        }

        return false;
    }

    public function insertarRespuestaPregunta_partida($respuesta, $id_jugador, $id_partida, $id_pregunta,$respondioCorrectamente)
    {
        $update = "UPDATE pregunta_partida 
           SET respuesta_usuario = '$respuesta', 
               usuario_id = '$id_jugador', 
               respondio_correctamente = '$respondioCorrectamente' 
           WHERE partida_id = '$id_partida' AND pregunta_id = '$id_pregunta'";

        !$result= $this->database->execute($update);

        if(!$result){
            die(); //para testear luego borrate esta linea
        }
    }

    public function cambiarEstadoDePartidaAFinalizada($id_partida)
    {
        $update = "UPDATE partidas SET estado = 'finalizada'
            WHERE id = '$id_partida'";

        $this->database->execute($update);
    }

    public function actualizarFechaDeFinalizacionDePartida($id_partida)
    {
        $fecha_actual = $this->obtenerFechaActual();

        $update = "UPDATE partidas SET fecha_de_finalizacion = '$fecha_actual'
            WHERE id = '$id_partida'";

        $this->database->execute($update);
    }

    private function validarQueLaPreguntaNoSeRespondioTodaviaEnLaPartidaActual($id_pregunta, $id_partida)
    {
        $sql = "SELECT * FROM pregunta_partida 
            WHERE partida_id = '$id_partida' 
            AND pregunta_id = '$id_pregunta' 
            AND respuesta_usuario IS NOT NULL 
            AND respuesta_usuario != ''";

        $result = $this->database->execute($sql);

        return $result->num_rows == 0;
    }

    public function actualizarLaCantidadDeVecesRespondidasBienEnUnUsuario($id_jugador)
    {
        $update = "UPDATE usuarios SET cantidad_respuestas_correctas = cantidad_respuestas_correctas + 1
            WHERE id = '$id_jugador'";

        $this->database->execute($update);
    }

    public function actualizarElPuntajeTotalDeLaPartidaEnUno($id_partida)
    {
        $update = "UPDATE partidas SET puntaje_total = puntaje_total + 1
            WHERE id = '$id_partida'";

        $this->database->execute($update);
    }

    public function actualizarLaCantidadDeVecesRespondidasCorrectamenteDeUnaPregunta($id_pregunta)
    {
        $update = "UPDATE preguntas SET cantidad_veces_respondidas = cantidad_veces_respondidas + 1
            WHERE id = '$id_pregunta'";

        $this->database->execute($update);
    }

    public function actualizarLaCantidadDeVecesQueRespondioUnaPreguntaUnUsuarioEnUno($id_jugador)
    {
        $update = "UPDATE usuarios SET cantidad_preguntas_respondidas = cantidad_preguntas_respondidas + 1
            WHERE id = '$id_jugador'";

        $this->database->execute($update);
    }

    public function actualizarLaCantidadDeAparicionesDeUnaPreguntaEnUno($id_pregunta)
    {
        $update = "UPDATE preguntas SET cantidad_apariciones = cantidad_apariciones + 1
            WHERE id = '$id_pregunta'";

        $this->database->execute($update);
    }


}