<?php

namespace App\Controllers;

use App\Libraries\PHPMailerLib;

class Home extends BaseController
{

	protected $session;
	protected $email;

  function __construct()
  {
    $this->session = \Config\Services::session( );
		$this->$email = new PHPMailerLib( );
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

	//método que funciona exclusivamente con AJAX - JQUERY
	public function Contact( )
	{
		if ( $this->request->isAJAX( ) )
    {

			$requestEmail = $this->request->getVar( 'email' );
			$requestName = $this->request->getVar( 'nombre' );
			$requestPhone = $this->request->getVar( 'phone' );
			$requestMessage = $this->request->getVar( 'comentario' );

			$data =
			[
				'nombre' => $requestName,
				'correo' => $requestEmail,
				'telefono' => $requestPhone,
				'mensaje' => $requestMessage
			];

			$content = View( 'emails/formulario', $data );

			//cargamos la configuración del email
			$correo = $this->$email->contact( 'Nuevo contacto desde el formulario', $content );

			if ( !$correo->send( ) )
        echo json_encode( array( 'status' => 400, 'msg' => $correo->ErrorInfo ) );
			else
        echo json_encode( array( 'status' => 200, 'msg' => 'Correo enviado' ) );

		}
		else
			return view( 'errors/cli/error_404' );
	}

}
