<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		//CSS, METAS y titulo
		echo view( 'landing/head' );

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

}
