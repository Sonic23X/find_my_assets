<?php

namespace App\Controllers;

use App\Libraries\PHPMailerLib;

class User extends BaseController
{

    protected $session;
    protected $userModel;
    protected $email;
    protected $draftModel;
    protected $empresaModel;
    protected $db;

    function __construct()
    {
        $this->session = \Config\Services::session( );
        $this->userModel = model( 'App\Models\UserModel' );
        $this->draftModel = model( 'App\Models\DraftModel' );
        $this->empresaModel = model( 'App\Models\EmpresaModel' );
        $this->db = \Config\Database::connect( );
        $this->email = new PHPMailerLib( );
    }

    function Index( )
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
            echo view( 'backoffice/options/profile' );

            //Scripts y librerias
            $footer = array( 'js' => 'user' );
            echo view( 'backoffice/common/footer2', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
            return view( 'functions/redirect', $data );
    	}
	
    }

    public function Users(  )
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

    public function getUserData(  )
    {
        if ( $this->request->isAJAX( ) )
        {
            try
            {
                $usuarios = $this->userModel->where( 'deleted_at', null )->where( 'id_empresa', $this->session->empresa )->findAll( );

                echo json_encode( array( 'status' => 200, 'data' => $usuarios ) );
            }
            catch (\Exception $e)
            {
                echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
            }
        }
        else
            return view( 'errors/cli/error_404' );
    }

    public function GetOneUser( )
    {
        if ( $this->request->isAJAX( ) )
        {
            try
            {
                $user = $this->userModel->where( 'deleted_at', null )->where( 'id_usuario', $this->request->getVar( 'id' ) )->first( );

                echo json_encode( array( 'status' => 200, 'data' => $user ) );
            }
            catch (\Exception $e)
            {
                echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
            }
        }
        else
            return view( 'errors/cli/error_404' );
    }

    public function GenerateUrl( )
	{
		if ( $this->request->isAJAX( ) )
		{

			$user = $this->userModel->where( 'email', $this->request->getVar( 'email' ) )->where( 'deleted_at', null )
						 ->first( );

			//el usuario ya está registado
			if ( $user )
			{
				echo json_encode( array( 'status' => 201, 'msg' => 'El correo ya está registrado', 'url' => base_url( '/carga' ) . '/' . $user[ 'email_encriptado' ] ) );
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
				'verificacion' => 1,
				'email_encriptado' => $emailEncrypt,
                'patrocinador' => 'N/A',
                'envios' => 1,
                'id_empresa' => $this->session->empresa,
			];

			if ( $this->userModel->insert( $insert ) )
            {
                $user = $this->userModel->where( 'email', $this->request->getVar( 'email' ) )->first( );
                $SQL = "INSERT INTO user_empresa(id_usuario, id_empresa) VALUES ( ". $user[ 'id_usuario'] .", ". $this->session->empresa ." )";
                $builder = $this->db->query( $SQL );

                $viewData =
                [
                    'urlUsuario' => base_url( '/carga' ) . '/' . $emailEncrypt,
                    'nombre' => $this->request->getVar( 'nombre' ),
                    'activos' => null,
                    'empresa' => $this->empresaModel->find($this->session->empresa)['nombre'],
                ];

                $content = View( 'emails/accesoUsuario', $viewData );

                //cargamos la configuración del email
                $correo = $this->email->preparEmail( $this->request->getVar( 'email' ), 'Enlace de acceso', $content );

                if ( !$correo->send( ) )
                    echo json_encode( array( 'status' => 400, 'msg' => $correo->ErrorInfo ) );
                else
                    echo json_encode( array( 'status' => 200, 'msg' => 'Verifique la bandeja de entrada del correo ingresado' ) );
            }
            else
                echo json_encode( array( 'status' => 400, 'msg' => 'Error al registrarse, intente más tarde' ) );
        }
        else
            return view( 'errors/cli/error_404' );
    }
    
    public function Update( )
    {
        if ( $this->request->isAJAX( ) )
        {
            try
            {
                $update =
                [
                    'nombre' => $this->request->getVar( 'nombre' ),
                    'apellidos' => $this->request->getVar( 'apellidos' ),
                    'email' => $this->request->getVar( 'email' ),
                ];

                if ( $this->userModel->update( $this->request->getVar( 'id' ), $update ) )
                    echo json_encode( array( 'status' => 200, 'msg' => 'Actualización completada' ) );
                else
                    echo json_encode( array( 'status' => 400, 'msg' => 'No se pudo actualizar al usuario' ) );
            }
            catch (\Exception $e)
            {
                echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
            }
        }
        else
            return view( 'errors/cli/error_404' );
    }

    public function SendEmail( )
    {
        if ( $this->request->isAJAX( ) )
		{
            $user = $this->userModel->find( $this->request->getVar( 'id' ) );
            
            $viewData =
            [
                'urlUsuario' => base_url( '/carga' ) . '/' . $user[ 'email_encriptado' ],
                'nombre' => $user[ 'nombre' ],
                'activos' =>  $this->draftModel->select( 'ID_Activo, Nom_Activo' )->where( 'User_Inventario', $this->request->getVar( 'id' ) )->findAll( ),
                'empresa' => $this->empresaModel->find($this->session->empresa)['nombre'],
            ];

            $content = View( 'emails/accesoUsuario', $viewData );

            //cargamos la configuración del email
            $correo = $this->email->preparEmail( $user[ 'email' ], 'Enlace de acceso', $content );

            if ( !$correo->send( ) )
                echo json_encode( array( 'status' => 400, 'msg' => $correo->ErrorInfo ) );
            else
            {
                $update =
                [
                    'envios' => intval($user['envios']) + 1,
                ];

                $this->userModel->update( $user['id_usuario'], $update );

                echo json_encode( array( 'status' => 200, 'msg' => 'Verifique la bandeja de entrada del correo ingresado' ) );
            }
        }
        else
            return view( 'errors/cli/error_404' );
    }

    public function Delete( )
    {
        if ( $this->request->isAJAX( ) )
        {
            try
            {
                $update =
                [
                    'deleted_at' => date( 'Y' ) . '/' . date( 'm' ) . '/' . date( 'd' ),
                ];

                if ( $this->userModel->update( $this->request->getVar( 'id' ), $update ) )
                    echo json_encode( array( 'status' => 200, 'msg' => 'Usuario eliminado' ) );
                else
                    echo json_encode( array( 'status' => 400, 'msg' => 'No se pudo eliminar al usuario' ) );
            }
            catch (\Exception $e)
            {
                echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
            }
        }
        else
            return view( 'errors/cli/error_404' );
    }

    public function Macal()
    {
        $password = crypt( '12345678', '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$' );
		$emailEncrypt = md5( 'practica.contabilidad@macal.cl' );

        $insert =
        [
            'perfil' => 'user',
            'nombre' => 'Guillermo',
            'apellidos' => 'Bascur',
            'email' => 'practica.contabilidad@macal.cl',
            'password' => $password,
            'suscripcion' => 0,
            'verificacion' => 1,
            'email_encriptado' => $emailEncrypt,
            'patrocinador' => 'N/A',
            'id_empresa' => 2,
        ];

        $this->userModel->insert( $insert );
    }

}