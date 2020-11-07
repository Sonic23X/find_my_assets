<?php

namespace App\Controllers;

use App\Libraries\PHPMailerLib;

class Home extends BaseController
{

	protected $session;
	protected $email;
	protected $userModel;
	protected $encrypter;

	function __construct()
	{
		$config         = new \Config\Encryption( );
		$config->key    = 'aBigsecret_ofAtleast32Characters';
		$config->driver = 'OpenSSL';

		$this->session = \Config\Services::session( );
		$this->email = new PHPMailerLib( );
		$this->encrypter = \Config\Services::encrypter( $config );
		$this->userModel = model( 'App\Models\UserModel' );
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
			$correo = $this->email->contact( 'Nuevo contacto desde el formulario', $content );

			if ( !$correo->send( ) )
        echo json_encode( array( 'status' => 400, 'msg' => $correo->ErrorInfo ) );
			else
        echo json_encode( array( 'status' => 200, 'msg' => 'Correo enviado' ) );

		}
		else
			return view( 'errors/cli/error_404' );
	}

	public function Users( )
	{
		if ( $this->session->has( 'isLoggin' ) )
		{
            //CSS, METAS y titulo
            $head = array( 'title' => 'Perfil | Find my assets', 'css' => 'dashboard' );
            echo view( 'backoffice/common/head', $head );

            //sidebar
            $sidebar = array( 'name' => $this->session->name );
            echo view( 'backoffice/common/sidebar', $sidebar );

            //navbar
            echo view( 'backoffice/common/navbar' );

            //content - inicio
            echo view( 'backoffice/sections/users-email' );

            //Scripts y librerias
            $footer = array( 'js' => 'test' );
            echo view( 'backoffice/common/footer2', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
            return view( 'functions/redirect', $data );
    	}
	}

	public function GenerateUrl( )
	{
		if ( $this->request->isAJAX( ) )
		{

			$requestEmail = $this->request->getVar( 'email' );
			$requestPassword = $this->request->getVar( 'password' );

			//aqui cifras
			$cifrado = crypt( $requestPassword, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$' );

			echo json_encode( array( 'status' => 200, 'url' => base_url( ) . '/url/' . $cifrado  ) );
		}
	}

	//metodo para mostrar la vista
	public function Url( $cifrado )
	{
		return view( 'auth/invited' );
	}

	//crear usuario ligado a empresa y redirigir a dashboard
	public function Invitado( )
	{
		if ( $this->request->isAJAX( ) )
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
				'perfil' => 'user',
				'nombre' => $this->request->getVar( 'nombre' ),
				'apellidos' => $this->request->getVar( 'apellidos' ),
				'email' => $this->request->getVar( 'email' ),
				'password' => $password,
				'suscripcion' => 0,
				'verificacion' => 0,
				'email_encriptado' => $emailEncrypt,
				'patrocinador' => 'N/A',
			];

			$this->userModel->insert( $insert );

			$this->session->set( 'isLoggin', true );
            $this->session->set( 'name', $this->request->getVar( 'nombre' ) );
			
			echo json_encode( array( 'status' => 200, 'url' => base_url( '/dashboard' ) ) );

		}
	}
}
