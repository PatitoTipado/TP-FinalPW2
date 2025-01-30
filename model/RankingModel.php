<?php

class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getNameAndScoreByPositionOfUsers()
    {
        return $this->database->query('SELECT u.id AS id_usuario, u.nombre_de_usuario, 
        u.puntaje_maximo AS mejor_puntaje,RANK() OVER (ORDER BY u.puntaje_maximo DESC) AS posicion 
        FROM usuarios u WHERE u.puntaje_maximo > 0 LIMIT 10;');
    }

    public function obtenerUsuario($id)
    {
        return $this->database->query("SELECT u.nombre_de_usuario, u.email, MAX(p.puntaje_total) AS mejor_puntaje, u.latitud, u.longitud, u.nombre, u.imagen_url,
        CASE 
           WHEN u.sexo = 'F' THEN 'Femenino'
           WHEN u.sexo = 'M' THEN 'Masculino'
        END AS sexo FROM usuarios u
        JOIN partidas p ON u.id = p.usuario_id WHERE p.estado = 'finalizada' AND u.id = '$id'");
    }
}
