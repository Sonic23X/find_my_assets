<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerLib
{

  protected $email;

  public function __construct( )
  {
    // Include PHPMailer library files
    require_once APPPATH.'ThirdParty/PHPMailer/Exception.php';
    require_once APPPATH.'ThirdParty/PHPMailer/PHPMailer.php';
    require_once APPPATH.'ThirdParty/PHPMailer/SMTP.php';

    $this->email = new PHPMailer( );

    $this->email->isSMTP( );

    $this->email->Host       = 'smtp.gmail.com';
    $this->email->SMTPAuth   = true;
    $this->email->Username   = 'correos.automatizador@gmail.com';
    $this->email->Password   = '12Febrero70';
    $this->email->SMTPSecure = 'tls';
    $this->email->Port       = 587;

    $this->email->setFrom( 'contacto@findmy-assets.com', 'Find my assets' );
    $this->email->addReplyTo( 'contacto@findmy-assets.com', 'Find my assets' );

    $this->email->isHTML( TRUE );
  }

  public function preparEmail( $correo, $subject, $content )
  {
    try
    {
      $this->email->addAddress( $correo );

      $this->email->Subject = $subject;

      $this->email->Body = $content;

      return $this->email;

    }
    catch (\Exception $e)
    {
      echo $e->getMessage();
    }
  }

  public function contact( $subject, $content )
  {
    try
    {
      $this->email->addAddress( 'pedro@findmy-assets.com' );
      $this->email->addAddress( 'cristian@findmy-assets.com' );
      $this->email->addAddress( 'hector@findmy-assets.com' );

      $this->email->Subject = $subject;

      $this->email->Body = $content;

      return $this->email;
    }
    catch (\Exception $e)
    {
      echo $e->getMessage();
    }
  }

}
