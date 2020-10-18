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

    $this->email->SMTPDebug = 2;
    $this->email->isSMTP( );

    $this->email->Host       = 'mail.de2.mx';
    $this->email->SMTPAuth   = true;
    $this->email->Username   = 'contacto@de2.mx';
    $this->email->Password   = 'C0mpuvive';
    $this->email->SMTPSecure = 'tls';
    $this->email->Port       = 587;
    $this->email->CharSet    = 'UTF-8';
    $this->email->SMTPOptions = 
    array(
      'ssl' =>
      array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
      )
    );

    $this->email->setFrom( 'contacto@de2.mx', 'Find my assets' );
    $this->email->addReplyTo( 'contacto@de2.mx', 'Find my assets' );

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
