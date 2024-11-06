<?php

class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    // public function obtenerUsuarios()
    // {

    //     $sql = "SELECT * FROM usuarios";
    //     $result = $this->database->execute($sql);

    //     $data = [];
    //     if ($result->num_rows == 0) {
    //         $data['result'] = false;
    //         return $data;
    //     }

    //     $data['result'] = true;
    //     $data['opciones'] = [];

    //     $numero = 1;
    //     while ($row = $result->fetch_assoc()) {
    //         $data['opciones'][] = [
    //             'id_usuario' => $row['id'],
    //             'numero' => $numero,
    //             'usuario' => $row['nombre_de_usuario'],
    //             'puntajeTotal' => $this->obtenerPuntajePorUsuario($row['id']),
    //         ];
    //         $numero++;
    //     }

    //     return $data;
    // }

    // public function obtenerPuntajePorUsuario($id_usuario)
    // {
    //     $partidas = $this->database->execute("SELECT * FROM partidas WHERE usuario_id='$id_usuario'");

    //     $puntajeTotal = 0;
    //     while ($row = $partidas->fetch_assoc()) {
    //         $puntajeTotal += $row['puntaje_total'];
    //     }

    //     return $puntajeTotal;
    // }

    public function getNameAndScoreByPositionOfUsers()
    {
        return $this->database->query('SELECT u.id AS id_usuario, u.nombre_de_usuario, MAX(p.puntaje_total) AS mejor_puntaje,  
RANK() OVER (ORDER BY MAX(p.puntaje_total) DESC) AS posicion FROM usuarios u                       
JOIN partidas p ON u.id = p.usuario_id WHERE p.estado = "finalizada"                               
GROUP BY u.id                                                                                      
ORDER BY mejor_puntaje DESC
LIMIT 50');
    }

    public function obtenerUsuario($id)
    {

        $sql = "SELECT * FROM usuarios WHERE id='$id'";
        $result = $this->database->execute($sql);

        $data = [];
        if ($result->num_rows == 0) {
            $data['result'] = false;
            return $data;
        }

        $data['result'] = true;
        $data['usuario'] = [];

        while ($row = $result->fetch_assoc()) {
            $data['usuario'][] = [
                'nombre_usuario' => $row['nombre_de_usuario'],
                'email' => $row['email'],
                'nombre' => $row['nombre'],
                'pais' => $row['pais'],
                'ciudad' => $row['ciudad'],
                'sexo' => $row['sexo'],
                'foto' => $row['imagen_url'],
            ];
        }

        return $data;
    }
}
