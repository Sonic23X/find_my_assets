<?php

namespace App\Controllers;

use App\Libraries\PHPMailerLib;

class Auth extends BaseController
{

  protected $session;
  protected $userModel;
  protected $email;

  function __construct()
  {
    $this->session = \Config\Services::session( );
    $this->userModel = model( 'App\Models\UserModel' );
    $this->$email = new PHPMailerLib( );
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
            $this->session->set( 'name', $user[ 'nombre' ] );
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
        echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
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

        //generaremos la nueva contraseña
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $newPassword = substr( str_shuffle( $chars ), 0, 10 );
        $encryptNewPassword = crypt( $newPassword, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$' );

        //actualizamos al usuario con la nueva contraseña
        $data =
        [
            'password' => $encryptNewPassword,
        ];

        //si se actualiza la contraseña
        if ( $this->userModel->where( 'email', $this->request->getVar( 'email' ) )->set( $data )->update( ) )
        {
          $viewData =
    			[
    				'password' => $newPassword,
    			];

    			$content = View( 'emails/recuperarContraseña', $viewData );

          //cargamos la configuración del email
    			$correo = $this->$email->preparEmail( $this->request->getVar( 'email' ), 'Recuperar contraseña', $content );

          if ( !$correo->send( ) )
            echo json_encode( array( 'status' => 400, 'msg' => $correo->ErrorInfo ) );
    			else
            echo json_encode( array( 'status' => 200, 'msg' => 'Verifique su bandeja de entrada' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al generar un correo de recuperación, Intente más tarde' ) );

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
        $emailEncrypt = md5( $this->request->getVar( 'email' ) );

        $insert =
        [
          'perfil' => 'admin',
          'nombre' => $this->request->getVar( 'nombre' ),
          'apellidos' => $this->request->getVar( 'apellidos' ),
          'email' => $this->request->getVar( 'email' ),
          'password' => $password,
          'suscripcion' => 0,
          'verificacion' => 0,
          'email_encriptado' => $emailEncrypt,
          'patrocinador' => 'N/A',
        ];

        //guardamos en la base de datos y procedemos a enviar el email
        if ( $this->userModel->insert( $insert ) )
        {

          $viewData =
    			[
    				'llave' => $emailEncrypt,
    			];

          $content = View( 'emails/verificarCorreo', $viewData );

          //cargamos la configuración del email
    			$correo = $this->$email->preparEmail( $this->request->getVar( 'email' ), 'Activar cuenta en Find my assets', $content );

          if ( !$correo->send( ) )
            echo json_encode( array( 'status' => 400, 'msg' => $correo->ErrorInfo ) );
    			else
            echo json_encode( array( 'status' => 200, 'msg' => 'Verifique su bandeja de entrada en busqueda de un correo de confirmación' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al registrarse, intente más tarde' ) );

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

  function ValidateEmail( $emailEncrypt = null )
  {
    if ( $emailEncrypt != null )
    {

      $user = $this->userModel->where( 'email_encriptado', $emailEncrypt )
                              ->first( );

      if ( $user )
      {
        $userData =
        [
          'verificacion' => 1
        ];

        if ( $this->userModel->where( 'email_encriptado', $emailEncrypt )->set( $userData )->update( ) )
        {

          $data =
          [
            'icon'  => 'success',
            'title' => 'Felicidades',
            'text'  => 'Cuenta validada, Ahora puedes iniciar sesión',
            'url'   => base_url( '/ingreso' )
          ];

          return view( 'functions/validation', $data );
        }
        else
        {

          $data =
          [
            'icon'  => 'error',
            'title' => '¡Ups!',
            'text'  => 'No se pudo validar tu cuenta, intenta más tarde',
            'url'   => base_url( )
          ];

          return view( 'functions/validation', $data );
        }
      }
    }
    else
      return view( 'errors/cli/error_404' );
  }

  function Logout( )
  {
    //borramos todo
    $this->session->destroy();

    //redireccionamos al login
    $data = array( 'url' => base_url( '/ingreso' ) );
    return view( 'functions/redirect', $data );
  }

}
