<?php

namespace App\Controllers;

class Auth extends BaseController
{

  function Login( )
  {
    return view( 'auth/login' );
  }

}
