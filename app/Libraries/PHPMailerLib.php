<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerLib
{
    public function __construct()
    {
      // Include PHPMailer library files
      require_once APPPATH.'ThirdParty/PHPMailer/Exception.php';
      require_once APPPATH.'ThirdParty/PHPMailer/PHPMailer.php';
      require_once APPPATH.'ThirdParty/PHPMailer/SMTP.php';
    }

    public function load( )
    {
      try
      {
        $email = new PHPMailer();

        $email->isSMTP();

        $email->Host       = 'smtp.gmail.com';
        $email->SMTPAuth   = true;
        $email->Username   = 'correos.automatizador@gmail.com';
        $email->Password   = 'ygucwslgqxaqvsjt';
        $email->SMTPSecure = 'tls';
        $email->Port       = 587;

        $email->setFrom( 'contacto@findmy-assets.com', 'Find my assets' );
        $email->addReplyTo( 'contacto@findmy-assets.com', 'Find my assets' );

        $email->addAddress( 'omar.alfredo49@gmail.com' );

        // Email subject
        $email->Subject = 'Test from Findmy-assets';

        // Set email format to HTML
        $email->isHTML(true);

        // Eemail body content
        $emailContent = "<h1>Send HTML Email using SMTP in CodeIgniter</h1>
             <p>This is a test email sending using SMTP mail server with PHPMailer.</p>";
        $email->Body = $emailContent;

        return $email;


      } catch (\Exception $e)
      {
        echo $e->getMessage();
      }
    }
}
