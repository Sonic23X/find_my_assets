<?php

namespace App\Controllers;

class Auth extends BaseController
{

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
        echo $this->request->getVar( 'email' ) ;
      }
      catch (\Exception $e)
      {
        print_r ( $e );
      }
    }
    else
      return view( 'errors/cli/error_404' );

  }

}
