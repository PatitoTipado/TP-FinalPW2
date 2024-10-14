<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function validarLogin($username, $password)
    {
        // Obtener la conexión a la base de datos
        $conn = $this->database->getConn();

        // Sanitizar el nombre de usuario
        $username = mysqli_real_escape_string($conn, $username);

        // Consulta para obtener el usuario
        $query = "SELECT contrasena FROM usuarios WHERE nombre_de_usuario = '$username'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($conn));
        }

        // Verificar si el usuario existe
        if (mysqli_num_rows($result) > 0) {
            // Verificar la contraseña
            return true; // Usuario y contraseña válidos

        } else {
            return false; // Contraseña incorrecta
        }

    }

}