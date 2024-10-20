<?php

class FileEmailSender
{
    private $filePath;

    public function __construct()
    {
        $this->filePath = __DIR__ . "/emails/correos.json";

        if (!file_exists(__DIR__ . "/emails")) {
            mkdir(__DIR__ . "/emails", 0777, true);
        }

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    public function sendEmail($to, $subject, $message)
    {
        $emails = json_decode(file_get_contents($this->filePath), true);

        $emails[] = [
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s') // Fecha y hora del "envío"
        ];

        file_put_contents($this->filePath, json_encode($emails, JSON_PRETTY_PRINT));
    }

}