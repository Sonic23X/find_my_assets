<?php

namespace App\Controllers;

use App\Libraries\PHPMailerLib;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xls as ReadXls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class User extends BaseController
{

    protected $session;
    protected $userModel;
    protected $email;
    protected $draftModel;
    protected $empresaModel;
    protected $ccModel;
    protected $db;

    function __construct()
    {
        $this->session = \Config\Services::session( );
        $this->userModel = model( 'App\Models\UserModel' );
        $this->draftModel = model( 'App\Models\DraftModel' );
        $this->empresaModel = model( 'App\Models\EmpresaModel' );
        $this->ccModel = model( 'App\Models\CCModel' );
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
			$SQL = "SELECT empresas.id_empresa, empresas.nombre FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
			$builder = $this->db->query( $SQL );
			$empresas = $builder->getResult( );
			$sidebar = array( 'name' => $this->session->name, 'empresas' => $empresas, 'actual' => $this->session->empresa);
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
            $SQL = "SELECT empresas.id_empresa, empresas.nombre FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
			$builder = $this->db->query( $SQL );
			$empresas = $builder->getResult( );
			$sidebar = array( 'name' => $this->session->name, 'empresas' => $empresas, 'actual' => $this->session->empresa);
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

    public function GetCCs()
    {
        if ( $this->request->isAJAX( ) )
        {
            try
            {
                $cc = $this->ccModel->where( 'id_empresa', $this->session->empresa )->findAll( );

                echo json_encode( array( 'status' => 200, 'data' => $cc ) );
            }
            catch (\Exception $e)
            {
                echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
            }
        }
        else
            return view( 'errors/cli/error_404' );
    }

    public function GetMyCC()
    {
        if ( $this->request->isAJAX( ) )
        {
            try
            {
                $cc = $this->userModel->where( 'id_usuario', $this->request->getVar('id') )->first( );

                echo json_encode( array( 'status' => 200, 'data' => $cc ) );
            }
            catch (\Exception $e)
            {
                echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
            }
        }
        else
            return view( 'errors/cli/error_404' );
    }

    public function getUserData(  )
    {
        if ( $this->request->isAJAX( ) )
        {
            try
            {
                $usuarios = $this->userModel->where( 'deleted_at', null )
                                            ->where( 'id_empresa', $this->session->empresa )
                                            ->where( 'perfil !=', 'superadmin' )
                                            ->findAll( );

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

            $insert = null;

            if ($this->request->getVar( 'sendMail' ) == 'true') 
            {
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
                    'id_cc' => $this->request->getVar( 'cc' ),
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
            {
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
                    'envios' => 0,
                    'id_cc' => $this->request->getVar( 'cc' ),
                    'id_empresa' => $this->session->empresa,
                ];

                $this->userModel->insert( $insert );
                
                echo json_encode( array( 'status' => 200, 'msg' => '¡Usuario creado con exito!' ) );
            }

			
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
                    'id_cc' => $this->request->getVar( 'cc' ),
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

    public function InitialExcel()
    {
        if ( $this->session->has( 'isLoggin' ) )
		{
            //CSS, METAS y titulo
            $head = array( 'title' => 'Perfil | Find my assets', 'css' => 'dashboard' );
            echo view( 'backoffice/common/head', $head );

            //sidebar
			$SQL = "SELECT empresas.id_empresa, empresas.nombre FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
			$builder = $this->db->query( $SQL );
			$empresas = $builder->getResult( );
			$sidebar = array( 'name' => $this->session->name, 'empresas' => $empresas, 'actual' => $this->session->empresa);
			echo view( 'backoffice/common/sidebar', $sidebar );

            //navbar
            echo view( 'backoffice/common/navbar' );

            //content - inicio
            echo view( 'backoffice/sections/load-users' );

            //Scripts y librerias
            $footer = array( 'js' => 'load-users' );
            echo view( 'backoffice/common/footer2', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
            return view( 'functions/redirect', $data );
    	}
    }

    public function DownloadExcelExample( )
	{
		$spreadsheet = new Spreadsheet();
		$cargaSheet = $spreadsheet->getActiveSheet();

		//iniciamos configuración inicial
		$cargaSheet->getColumnDimension('A')->setWidth(50);
		$cargaSheet->getColumnDimension('B')->setWidth(50);
		$cargaSheet->getColumnDimension('C')->setWidth(50);
		$cargaSheet->getColumnDimension('D')->setWidth(50);

		//iniciamos tabla 
		$cargaSheet->setCellValue( 'A1', 'Nombre' );
		$cargaSheet->setCellValue( 'B1', 'Apellidos' );
		$cargaSheet->setCellValue( 'C1', 'Correo electrónico' );
		$cargaSheet->setCellValue( 'D1', 'Contraseña' );

		$styleHeadArray = 
		[
			'font' => [
				'bold' => true,
				'color' => [ 'argb' => '00FFFFFF' ],
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['argb' => '00000000'],
				],
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color' => [ 'argb' => '00BFBFBF' ]
			],
		];
			
		$cargaSheet->getStyle('A1:D1')->applyFromArray($styleHeadArray);
		

		$spreadsheet->setActiveSheetIndex(0);
		$writer = new Xls($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Carga.xls"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

    public function ReadExcel()
	{
		if ( $this->request->isAJAX( ) )
		{
			$file = $this->request->getFile('excel');
			$nameFile = explode('.', $file->getName());
			$cabezales = true;

			$reader = null;

			if ($nameFile[1] == 'xls') 
				$reader = new ReadXls();				
			else
				$reader = new Xlsx();				

			$reader->setReadDataOnly( TRUE );

			$spreadsheet = $reader->load($file)->getActiveSheet( );

			$rows = [ ];
			foreach ( $spreadsheet->getRowIterator( ) as $row )
			{
				if ($cabezales) 
					$cabezales = false;
				else
				{
					$cellIterator = $row->getCellIterator( );
					$cells = [ ];
					
					foreach ( $cellIterator as $cell ) 
					{
						$cells[ ] = $cell->getValue( ); 
					}
					
					$rows[ ] = $cells;
				}
			}

			$errores = [];	
			$subidos = 0;
			$linea = 1;
			$usuarios_subidos = [];
            $user = null;
			foreach ( $rows as $usuario ) 
			{
				//Validación de usuarios
				$linea++;
                $error = false;

				if ($usuario[0] == null) 
				{
					array_push($errores, [ 'usuario' => 'Linea ' . $linea, 'problema' => 'La linea no contiene el nombre del usuario' ]);
                    $error = true;
				}
                if ($usuario[1] == null) 
                {
                    array_push($errores, [ 'usuario' => 'Linea ' . $linea, 'problema' => 'La linea no contiene los apellidos del usuario' ]);
                    $error = true;
                }
                if ($usuario[2] == null) 
                {
                    array_push($errores, [ 'usuario' => 'Linea ' . $linea, 'problema' => 'La linea no contiene el email del usuario' ]);
                    $error = true;
                }
                else
                {
                    $user = $this->userModel->where('email', $usuario[2])->where('id_empresa', $this->session->empresa)->first();

                    if($user != null && ($user['deleted_at'] == '' && $user['deleted_at'] == null))
                    {
                        array_push($errores, [ 'usuario' => 'Linea ' . $linea, 'problema' => 'El usuario ya está registrado en el sistema.' ]);
                        $error = true;
                    }
                }
                if ($usuario[3] == null) 
                {
                    array_push($errores, [ 'usuario' => 'Linea ' . $linea, 'problema' => 'La linea no contiene la contraseña del usuario' ]);
                    $error = true;
                }
				
                if (!$error) 
                {
                    $password = crypt( $usuario[3], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$' );
			        $emailEncrypt = md5( $usuario[2] );

                    if ($this->request->getVar( 'sendEmail' ) == 'true') 
                    {

                        if ($user != null) 
                        {
                            if ($user['deleted_at'] != '' || $user['deleted_at'] != null)
                            {
                                $SQL = "UPDATE usuarios SET deleted_at=NULL WHERE id_usuario = ". $user['id_usuario'];
                                $this->db->query( $SQL );  
                            }

                            $user = $this->userModel->where( 'email', $usuario[2] )->where('id_empresa', $this->session->empresa)->first( );
                            $SQL = "INSERT INTO user_empresa(id_usuario, id_empresa) VALUES ( ". $user[ 'id_usuario'] .", ". $this->session->empresa ." )";
                            $builder = $this->db->query( $SQL );  
                            
                            $viewData =
                            [
                                'urlUsuario' => base_url( '/carga' ) . '/' . $emailEncrypt,
                                'nombre' => $usuario[0],
                                'activos' => null,
                                'empresa' => $this->empresaModel->find($this->session->empresa)['nombre'],
                            ];

                            $content = View( 'emails/accesoUsuario', $viewData );

                            //cargamos la configuración del email
                            $correo = $this->email->preparEmail( $usuario[2], 'Enlace de acceso', $content );

                            $correo->send( );
                        }
                        else
                        {
                            $insert =
                            [
                                'perfil' => 'user',
                                'nombre' => $usuario[0],
                                'apellidos' => $usuario[1],
                                'email' => $usuario[2],
                                'password' => $password,
                                'suscripcion' => 0,
                                'verificacion' => 1,
                                'email_encriptado' => $emailEncrypt,
                                'patrocinador' => 'N/A',
                                'envios' => 0,
                                'id_empresa' => $this->session->empresa,
                            ];

                            if ( $this->userModel->insert( $insert ) )
                            {
                                $user = $this->userModel->where( 'email', $usuario[2] )->where('id_empresa', $this->session->empresa)->first( );
                                $SQL = "INSERT INTO user_empresa(id_usuario, id_empresa) VALUES ( ". $user[ 'id_usuario'] .", ". $this->session->empresa ." )";
                                $builder = $this->db->query( $SQL );  
                                
                                $viewData =
                                [
                                    'urlUsuario' => base_url( '/carga' ) . '/' . $emailEncrypt,
                                    'nombre' => $usuario[0],
                                    'activos' => null,
                                    'empresa' => $this->empresaModel->find($this->session->empresa)['nombre'],
                                ];

                                $content = View( 'emails/accesoUsuario', $viewData );

                                //cargamos la configuración del email
                                $correo = $this->email->preparEmail( $usuario[2], 'Enlace de acceso', $content );

                                $correo->send( );
                            }
                            else
                                array_push($errores, [ 'usuario' => 'Linea ' . $linea, 'problema' => 'No pudo crearse el usuario, intente más tarde' ]);
                        }
                    }
                    else
                    {
                        if ($user == null) 
                        {
                            $insert =
                            [
                                'perfil' => 'user',
                                'nombre' => $usuario[0],
                                'apellidos' => $usuario[1],
                                'email' => $usuario[2],
                                'password' => $password,
                                'suscripcion' => 0,
                                'verificacion' => 1,
                                'email_encriptado' => $emailEncrypt,
                                'patrocinador' => 'N/A',
                                'envios' => 0,
                                'id_empresa' => $this->session->empresa,
                            ];

                            $this->userModel->insert( $insert );
                        }
                        
                        if ($user['deleted_at'] != '' || $user['deleted_at'] != null)
                        {
                            $SQL = "UPDATE usuarios SET deleted_at=NULL WHERE id_usuario = ". $user['id_usuario'];
                            $this->db->query( $SQL );  
                        }

                        $user = $this->userModel->where( 'email', $usuario[2] )->first( );
                        $SQL = "INSERT INTO user_empresa(id_usuario, id_empresa) VALUES ( ". $user[ 'id_usuario'] .", ". $this->session->empresa ." )";
                        $builder = $this->db->query( $SQL );
                        
                    }

                    $subidos++;

                    $json =
                    [
                        'nombre' => $usuario[0],
                        'correo' => $usuario[2],
                    ];

                    array_push( $usuarios_subidos, $json );
                }
                $user = null;
			}
			echo json_encode( array( 'status' => 200, 'errores' => $errores, 'subidos' => $subidos, 'usuarios' => $usuarios_subidos ) );
		}
		else
			return view( 'errors/cli/error_404' );
	}

}