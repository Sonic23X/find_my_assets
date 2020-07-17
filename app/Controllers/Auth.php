<?php

namespace App\Controllers;

class Auth extends BaseController
{

  protected $session;

  function __construct()
  {
    $this->session = \Config\Services::session( );
  }

  function Login( )
  {
    return view( 'auth/login' );
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function UserExist( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $userModel = model('App\Models\UserModel');

        $user = $userModel->where( 'email', $this->request->getVar( 'email' ) )
                          ->first( );
        if ( $user )
        {

          $this->session->set( 'email', $user[ 'email' ] );

          $json = array( 'status' => 200, 'nombre' => $user[ 'nombre' ] );
        }
        else
          $json = array( 'status' => 401, 'msg' => 'El correo no está registrado' );

        echo json_encode( $json );
      }
      catch (\Exception $e)
      {
        echo json_encode( array( 'status' => 402, 'msg' => 'Error, intente más tarde' ) );
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
        $userModel = model('App\Models\UserModel');

        $user = $userModel->where( 'email', $this->session->email )
                          ->first( );

        $postPassword = crypt( $this->request->getVar( 'password' ), '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$' );

        if ( $postPassword == $user[ 'password' ] )
        {
          //validamos si el usuario ya validó su correo
          if ( $user[ 'verificacion' ] == 1 )
          {
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
        echo json_encode( array( 'status' => 402, 'msg' => 'Error, intente más tarde' ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );

  }

  function Register( )
  {
    
  }

}
