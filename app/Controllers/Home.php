<?php

namespace App\Controllers;

use App\Libraries\PHPMailerLib;

class Home extends BaseController
{

	protected $session;
	protected $email;
	protected $userModel;
	protected $encrypter;
	protected $db;

	function __construct()
	{
		$config         = new \Config\Encryption( );
		$config->key    = 'aBigsecret_ofAtleast32Characters';
		$config->driver = 'OpenSSL';

		$this->session = \Config\Services::session( );
		$this->email = new PHPMailerLib( );
		$this->encrypter = \Config\Services::encrypter( $config );
		$this->userModel = model( 'App\Models\UserModel' );
		$this->db = \Config\Database::connect();
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
		if ( $this->session->has( 'isLoggin' ) && $this->session->has( 'tipo' ) )
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

	//metodo para mostrar la vista
	public function Url( $cifrado )
	{
		$user = $this->userModel->where( 'email_encriptado', $cifrado )
								->where( 'deleted_at', null )
								->first( );
								 
		if ( $user != null)
		{
			$this->session->set( 'isLoggin', true );
			$this->session->set( 'id', $user[ 'id_usuario' ] );
            $this->session->set( 'name', $user[ 'nombre' ] );
            $this->session->set( 'empresa', $user[ 'id_empresa' ] );
			
			//CSS, METAS y titulo
			$head = array( 'title' => 'Carga | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );

			$SQL = "SELECT empresas.id_empresa, empresas.nombre FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
			$builder = $this->db->query( $SQL );
			$empresas = $builder->getResult( );
			$sidebar = array( 'name' => $this->session->name, 'empresas' => $empresas, 'actual' => $this->session->empresa);
			echo view( 'backoffice/common/sidebar', $sidebar );

			//navbar
			echo view( 'backoffice/test/navbar' );

			//scanner
			echo view( 'backoffice/test/scanner' );

			//Scripts y librerias
			$footer = array( 'js' => 'scanner' );
			echo view( 'backoffice/test/footer', $footer );
		}
		else
			return view( 'errors/cli/error_404' );
	}

	public function OnlyScan( )
	{
		if ( $this->session->has( 'isLoggin' ) )
		{
			//CSS, METAS y titulo
			$head = array( 'title' => 'Carga | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );

			//navbar
			echo view( 'backoffice/test/navbar' );

			//scanner
			echo view( 'backoffice/test/scanner' );

			//Scripts y librerias
			$footer = array( 'js' => 'scanner' );
			echo view( 'backoffice/test/footer', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
			return view( 'functions/redirect', $data );
		}
	}

}
