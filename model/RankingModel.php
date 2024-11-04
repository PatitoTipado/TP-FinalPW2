<?php

class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerUsuarios()
    {

        $sql = "SELECT * FROM usuarios";
        $result = $this->database->execute($sql);

        $data = [];
        if ($result->num_rows == 0) {
            $data['result'] = false;
            return $data;
        }

        $data['result'] = true;
        $data['opciones'] = [];

        $numero = 1;
        while ($row = $result->fetch_assoc()) {
            $data['opciones'][] = [
		'id_usuario' => $row['id'],
                'numero' => $numero,
                'usuario' => $row['nombre_de_usuario'],
                'puntajeTotal' => $this->obtenerPuntajePorUsuario($row['id']),
            ];
            $numero++;
        }

        return $data;
    }

    public function obtenerPuntajePorUsuario($id_usuario)
    {
        $partidas = $this->database->execute("SELECT * FROM partidas WHERE usuario_id='$id_usuario'");

        $puntajeTotal = 0;
        while ($row = $partidas->fetch_assoc()) {
            $puntajeTotal += $row['puntaje_total'];
        }

        return $puntajeTotal;
    }
}
