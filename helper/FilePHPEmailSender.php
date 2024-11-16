<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/PHPMailer/src/Exception.php';
require './vendor/PHPMailer/src/PHPMailer.php';
require './vendor/PHPMailer/src/SMTP.php';

class FilePHPEmailSender
{

    private $mailSender;
    private $mailOwner;
    private $password;
    private $host;
    private $port;

    public function __construct($port, $host, $password, $username)
    {
        $this->mailSender = new PHPMailer(true);
        $this->mailOwner= $username;
        $this->password=$password;
        $this->host=$host;
        $this->port=$port;

    }

    public function sendEmail($destinatario,$hash,$nombre_de_usuario)
    {
        try {
            //Server settings
            $this->mailSender->SMTPDebug = 0;                      //Enable verbose debug output
            $this->mailSender->isSMTP();                                            //Send using SMTP
            $this->mailSender->Host       = $this->host;                     //Set the SMTP server to send through
            $this->mailSender->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mailSender->Username   = $this->mailOwner;                     //SMTP username
            $this->mailSender->Password   = $this->password;                               //SMTP password
            $this->mailSender->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
            $this->mailSender->Port       = $this->port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $this->mailSender->setFrom($this->mailOwner, 'admin');
            $this->mailSender->addAddress($destinatario, 'jugador'); // Destinatario

            $this->mailSender->isHTML(true);
            $this->mailSender->Subject = $nombre_de_usuario;
            $this->mailSender->Body    = 'validacion correo tu codigo hash es ' .  $hash;
            $this->mailSender->AltBody = "tu codigo hash es '$hash'";

            $this->mailSender->send();
        } catch (Exception $e) {
            die($this->mailSender->ErrorInfo);
        }
    }
}