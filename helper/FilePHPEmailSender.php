<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/PHPMailer/src/Exception.php';
require './vendor/PHPMailer/src/PHPMailer.php';
require './vendor/PHPMailer/src/SMTP.php';

class FilePHPEmailSender
{

    public function __construct($destinatario,$hash,$nombre_de_usuario)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'mauricionahueldominguez@gmail.com';                     //SMTP username
            $mail->Password   = 'rsvj awbh cicf pqei';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $mail->setFrom('mauricionahueldominguez@gmail.com', 'admin');
            $mail->addAddress($destinatario, 'jugador'); // Destinatario

            $mail->isHTML(true);
            $mail->Subject = $nombre_de_usuario;
            $mail->Body    = 'validacion correo tu codigo hash es ' .  $hash;
            $mail->AltBody = "tu codigo hash es '$hash'";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}