<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		echo view( 'landing/head' );

		//vistas que componen la landing page
		echo view( 'landing/navbar' );
		echo view( 'landing/header' );

		echo view( 'landing/nosotros' );



		echo view( 'landing/footer' );
	}

}
