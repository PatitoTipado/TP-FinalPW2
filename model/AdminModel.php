<?php

class AdminModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerJugadoresTotales()
    {
        // La consulta SQL
        $query = "SELECT estado, COUNT(*) AS cantidad FROM usuarios WHERE rol = 'jugador' GROUP BY estado";

        // Ejecutar la consulta
        $result = mysqli_query($this->database->getConn(), $query);

        // Comprobar si la consulta fue exitosa
        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($this->database->getConn()));
        }

        // Inicializar el arreglo donde se almacenarán los resultados
        $data = [];

        // Obtener los resultados como un arreglo asociativo
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row; // Añadir cada fila a $data
        }

        return $data; // Retornar los resultados como un arreglo de filas
    }

}