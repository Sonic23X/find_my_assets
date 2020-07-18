<?php

namespace App\Controllers;

class Home extends BaseController
{

	protected $session;

  function __construct()
  {
    $this->session = \Config\Services::session( );
  }

	//función que regresa la landing page
	public function Index()
	{
		//CSS, METAS y titulo
		echo view( 'landing/head' );

		//loader
		echo view( 'landing/loader' );

		//Vistas que componen la landing page
		echo view( 'landing/navbar' );

		//Vista del carousel
		echo view( 'landing/header' );

		//Vista de los pasos
		echo view( 'landing/pasos' );

		//Vista de los planes
		echo view( 'landing/planes' );

		//Vista de nosotros
		echo view( 'landing/nosotros' );

		//Vista del formulario de contacto
		echo view( 'landing/contacto' );

		//Vista de tarjetas de blog
		echo view( 'landing/blog' );

		//Scripts y librerias
		echo view( 'landing/footer' );
	}

	//función que regresa la primera pagina del backoffice
	public function Start()
	{
		if ( $this->session->has( 'isLoggin' ) )
		{
			$assets = array( 'page' => 'dashboard' );

			//CSS, METAS y titulo
			echo view( 'backoffice/common/head', $assets );

			//loader
			echo view( 'backoffice/common/loader' );


			//Scripts y librerias
			echo view( 'backoffice/common/footer', $assets );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
      return view( 'functions/redirect', $data );
		}
	}

}
