<?php

namespace App\Controllers;

use App\Libraries\PHPMailerLib;

class Dashboard extends BaseController
{

  protected $session;

  function __construct()
  {
    $this->session = \Config\Services::session( );
  }

  function Index( )
  {
    if ( $this->session->has( 'isLoggin' ) )
		{
			//CSS, METAS y titulo
      $head = array( 'title' => 'Dashboard | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );

			//sidebar
			$sidebar = array( 'name' => $this->session->name );
			echo view( 'backoffice/common/sidebar', $sidebar );

			//navbar
			echo view( 'backoffice/common/navbar' );

			//content - inicio
			echo view( 'backoffice/sections/start' );

      //content - scanner
			echo view( 'backoffice/sections/scanner' );

      //content - bajar
			echo view( 'backoffice/sections/down' );

      //content - mantener
			echo view( 'backoffice/sections/keep' );

      //content - Inventario
			echo view( 'backoffice/sections/inventary' );

			//Scripts y librerias
      $footer = array( 'js' => 'dashboard' );
			echo view( 'backoffice/common/footer', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
      return view( 'functions/redirect', $data );
		}
  }

}
