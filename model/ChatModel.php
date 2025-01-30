<?php

class ChatModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerTodaLaConversacion($id_usuario, $id_usuarioAChatear)
    {
        $sql= "SELECT contenido, id_user, id_otroUser,remitente FROM Mensaje 
         where (id_user='$id_usuario' AND id_otroUser= '$id_usuarioAChatear')
         OR (id_user='$id_usuarioAChatear' AND id_otroUser= '$id_usuario') ORDER BY fecha";

        return $this->database->query($sql);
    }

    public function enviarMensaje($id_usuario,$id_usuarioEnviar, $contenido,$remitente)
    {
        $sql= "INSERT INTO mensaje 
                (contenido, id_user, id_otroUser, remitente) VALUES
                ('$contenido','$id_usuario', '$id_usuarioEnviar','$remitente')";

        return $this->database->execute($sql);

    }
}