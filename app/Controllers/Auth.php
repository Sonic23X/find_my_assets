<?php

namespace App\Controllers;

class Auth extends BaseController
{

  protected $session;
  protected $userModel;

  function __construct()
  {
    $this->session = \Config\Services::session( );
    $this->userModel = model('App\Models\UserModel');
  }

  function Login( )
  {

    if ( $this->session->has( 'isLoggin' ) )
    {
      $data = array( 'url' => base_url( '/dashboard' ) );
      return view( 'functions/redirect', $data );
    }
    else
      return view( 'auth/login' );
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function UserExist( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $user = $this->userModel->where( 'email', $this->request->getVar( 'email' ) )
                                ->first( );
        if ( $user )
        {

          $this->session->set( 'email', $user[ 'email' ] );

          $json = array( 'status' => 200, 'nombre' => $user[ 'nombre' ] );
        }
        else
          $json = array( 'status' => 401, 'msg' => 'Al parecer todavía no estás registrado' );

        echo json_encode( $json );
      }
      catch (\Exception $e)
      {
        echo json_encode( array( 'status' => 400, 'msg' => 'Error, intente más tarde' ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );

  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function Access( )
  {
    if ( $this->request->isAJAX( ) && $this->session->has( 'email' ) )
    {
      try
      {

        $user = $this->userModel->where( 'email', $this->session->email )
                                ->first( );

        $postPassword = crypt( $this->request->getVar( 'password' ), '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$' );

        if ( $postPassword == $user[ 'password' ] )
        {
          //validamos si el usuario ya validó su correo
          if ( $user[ 'verificacion' ] == 1 )
          {
            $this->session->set( 'isLoggin', true );
            $json = array( 'status' => 200, 'url' => base_url( '/dashboard' ) );
          }
          else
          {
            $json = array( 'status' => 401, 'msg' => 'El usuario no está validado' );
          }
        }
        else
        {
          $json = array( 'status' => 401, 'msg' => 'La contraseña no es correcta' );
        }

        echo json_encode( $json );
      }
      catch (\Exception $e)
      {
        echo json_encode( array( 'status' => 400, 'msg' => 'Error, intente más tarde' ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );

  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function RecoveryPassword( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {

        $user = $this->userModel->where( 'email', $this->request->getVar( 'email' ) )
                                ->first( );

        //ingreso un correo no registrado
        if ( !$user )
        {
          echo json_encode( array( 'status' => 401, 'msg' => 'El correo es incorrecto' ) );
          return;
        }

        //guardamos en la base de datos y procedemos a enviar el email
        if ( true )  //$this->userModel->insert( $insert ) )
        {

          $email = \Config\Services::email( );

          //$email->initialize( );

          $email->setFrom( 'omar.alfredo49@gmail.com', 'Your Name');
          $email->setTo( 'omar.alfredo49@gmail.com' );
          $email->setCC('omar.alfredo49@gmail.com');
          $email->setBCC('omar.alfredo49@gmail.com');

          $email->setSubject('Email Test');
          $email->setMessage('Testing the email class.');

          $email->send( false );
          $email->printDebugger( );

          echo "envie";
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al guardar, intente más tarde' ) );

      }
      catch (\Exception $e)
      {
        //echo json_encode( array( 'status' => 400, 'msg' => 'Error critico, intente más tarde' ) );
        echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );
  }

  function Register( )
  {
    if ( $this->session->has( 'isLoggin' ) )
    {
      $data = array( 'url' => base_url( '/dashboard' ) );
      return view( 'functions/redirect', $data );
    }
    else
      return view( 'auth/register' );
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function New( )
  {

    if ( $this->request->isAJAX( ) )
    {
      try
      {

        $user = $this->userModel->where( 'email', $this->request->getVar( 'email' ) )
                                ->first( );

        //el usuario ya está registado
        if ( $user )
        {
          echo json_encode( array( 'status' => 401, 'msg' => 'El correo ya está registrado' ) );
          return;
        }

        $password = crypt( $this->request->getVar( 'password' ), '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$' );
        $email = md5( $this->request->getVar( 'email' ) );

        $insert =
        [
          'perfil' => 'admin',
          'nombre' => $this->request->getVar( 'nombre' ),
          'apellidos' => $this->request->getVar( 'apellidos' ),
          'email' => $this->request->getVar( 'email' ),
          'password' => $password,
          'suscripcion' => 0,
          'verificacion' => 0,
          'email_encriptado' => $email,
          'patrocinador' => 'N/A',
        ];

        //guardamos en la base de datos y procedemos a enviar el email
        if ( true )  //$this->userModel->insert( $insert ) )
        {

          $email = \Config\Services::email( );

          //$email->initialize( );

          $email->setFrom( 'omar.alfredo49@gmail.com', 'Your Name');
          $email->setTo( 'omar.alfredo49@gmail.com' );
          $email->setCC('omar.alfredo49@gmail.com');
          $email->setBCC('omar.alfredo49@gmail.com');

          $email->setSubject('Email Test');
          $email->setMessage('Testing the email class.');

          $email->send( false );
          $email->printDebugger( );

          echo "envie";
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al guardar, intente más tarde' ) );

      }
      catch (\Exception $e)
      {
        //echo json_encode( array( 'status' => 400, 'msg' => 'Error critico, intente más tarde' ) );
        echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );
  }

}
