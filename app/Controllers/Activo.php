<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xls as ReadXls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Activo extends BaseController
{

	protected $session;
	protected $userModel;
	protected $tipoModel;
	protected $empresaModel;
	protected $draftModel;
	protected $activoModel;
	protected $sucursalModel;
	protected $serieModel;
	protected $ccModel;
	protected $areaModel;
	protected $db;

	function __construct()
	{
    	$this->session = \Config\Services::session( );
		$this->tipoModel = model( 'App\Models\TipoModel' );
		$this->userModel = model( 'App\Models\UserModel' );
		$this->empresaModel = model( 'App\Models\EmpresaModel' );
		$this->draftModel = model( 'App\Models\DraftModel' );
		$this->activoModel = model( 'App\Models\ActivoModel' );
		$this->sucursalModel = model( 'App\Models\SucursalModel' );
		$this->serieModel = model( 'App\Models\SerieModel' );
		$this->ccModel = model( 'App\Models\CCModel' );
		$this->areaModel = model( 'App\Models\AreaModel' );
		$this->db = \Config\Database::connect();

		helper('filesystem');
	}
	  
	public function Index( )
	{
		if ( $this->session->has( 'isLoggin' ) && $this->session->has( 'tipo' ) && ( $this->session->tipo == 'admin' || $this->session->tipo == 'superadmin'))
		{
			//CSS, METAS y titulo
			$head = array( 'title' => 'Dashboard | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );

			//sidebar
			$SQL = "SELECT empresas.id_empresa, empresas.nombre FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
			$builder = $this->db->query( $SQL );
			$empresas = $builder->getResult( );
			$sidebar = array( 'name' => $this->session->name, 'empresas' => $empresas, 'actual' => $this->session->empresa);
			echo view( 'backoffice/common/sidebar', $sidebar );

			//navbar
			echo view( 'backoffice/common/navbar' );

			//content - scanner
			echo view( 'backoffice/sections/scanner' );

			//Scripts y librerias
			$footer = array( 'js' => 'scanner', 'dashboard' => false, 'carga' => true, 'inv' => false, 'bajas' => false );
			echo view( 'backoffice/common/footer', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
			return view( 'functions/redirect', $data );
		}
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function GetDataForm( )
	{
		if ( $this->request->isAJAX( ) )
		{
			
				$tipos = $this->tipoModel->where( 'ID_Empresa', $this->session->empresa )->findAll( );
				$cc = $this->ccModel->where( 'id_empresa', $this->session->empresa )->findAll( );
				$usuarios = $this->userModel->where( 'id_empresa', $this->session->empresa )
											->where('perfil !=', 'superadmin')
											->where('deleted_at', null)
											->orderBy('nombre', 'ASC')
											->findAll( );

				$SQL = "SELECT empresas.id_empresa, empresas.nombre FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
				$builder = $this->db->query( $SQL );
				$empresas = $builder->getResult( );

				$SQL = "SELECT * FROM sucursales WHERE ID_Empresa = " . $this->session->empresa;
				$builder = $this->db->query( $SQL );
				$sucursales = $builder->getResult( );

				$SQL = "SELECT * FROM areas WHERE id_empresa = " . $this->session->empresa;
				$builder = $this->db->query( $SQL );
				$areas = $builder->getResult( );

				$json = null;
				if ( $tipos )
				$json = array( 'status' => 200, 'types' => $tipos, 'users' => $usuarios,
												'empresas' => $empresas, 'sucursales' => $sucursales,
												'cc' => $cc, 'areas' => $areas );
				else
				$json = array( 'status' => 401, 'msg' => 'No se pudo obtener la informacion del servidor' );

				echo json_encode( $json );
			
		}
		else
		return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function SearchActivo( )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$campos =
				[
					'ID_Activo', 'Nom_Activo', 'BC_Activo', 'ID_Company', 'ID_Sucursal',
					'ID_Area', 'ID_CC', 'ID_Asignado', 'ID_Proceso', 'ID_Status', 'Fec_Compra',
					'Pre_Compra', 'Fec_Expira', 'NSerie_Activo', 'ID_Tipo', 'status',
					'Des_Activo', 'ID_MetDepre', 'Vida_Activo', 'GPS', 'Fec_Inventario',
					'User_Inventario', 'Comentarios', 'User_Create', 'User_Update', 'User_Delete',
				];

				$activo = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
										   ->where( 'ID_Company', $this->session->empresa )
											->select( $campos )
											->first( );

				if ( $activo == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
					return;
				}

				$user = $this->userModel->where( 'id_usuario', $activo[ 'User_Inventario' ] )->first( );

				$tipo = $this->tipoModel->where( 'id', $activo[ 'ID_Tipo' ] )->first( );

				$SQL = "SELECT * FROM sucursales WHERE ID_Empresa = " . $activo[ 'ID_Company' ];
				$builder = $this->db->query( $SQL );
				$sucursales = $builder->getResult( );

				$SQL = "SELECT * FROM areas WHERE id_empresa = " . $activo[ 'ID_Company' ];
				$builder = $this->db->query( $SQL );
				$areas = $builder->getResult( );

				echo json_encode( array( 'status' => 200, 'activo' => $activo, 'user' => $user, 'tipo' => $tipo, 'sucursal' => $sucursales, 'areas' => $areas ) );

			}
			catch (\Exception $e)
			{
				echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function ValidateActivo( )
	{
		if ( $this->request->isAJAX( ) )
		{
			$already = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
										->where('ID_Company', $this->session->empresa)
										->first( );

			if ( $already )
				echo json_encode( array( 'status' => 400, 'msg' => 'Ya existe un activo con este ID' ) );
			else
				echo json_encode( array( 'status' => 200 ) );
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function NewActivo( )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$insert =
				[
					'ID_Activo' => $this->request->getVar( 'codigo' ),
					'Nom_Activo' => $this->request->getVar( 'nombre' ),
					'ID_Company' => $this->session->empresa,
					'ID_Tipo' => $this->request->getVar( 'tipo' ),
					'Des_Activo' => $this->request->getVar( 'descripcion' ),
					'NSerie_Activo' => $this->request->getVar( 'no_serie' ),
					'ID_CC' => $this->request->getVar( 'centro_costo' ),
					'User_Inventario' => $this->request->getVar( 'asignacion' ),
					'TS_Create' => date( 'Y/n/j H:i:s' ),
				];

				if ( $this->draftModel->insert( $insert ) )
				{
					echo json_encode( array( 'status' => 200, 'msg' => '¡Activo registrado!' ) );
				}
				else
					echo json_encode( array( 'status' => 400, 'msg' => 'Error al registrar el activo. Intente más tarde' ) );
			}
			catch (\Exception $e)
			{
				echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function UpdateInfoActivo( )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				//obtenemos el numero de serie previo
				$draftItem = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->first( );

				$update =
				[
					'Nom_Activo' => $this->request->getVar( 'nombre' ),
					'ID_Tipo' => $this->request->getVar( 'tipo' ),
					'Des_Activo' => $this->request->getVar( 'descripcion' ),
					'NSerie_Activo' => $this->request->getVar( 'no_serie' ),
					'ID_CC' => $this->request->getVar( 'centro_costo' ),
					'User_Inventario' => ($this->request->getVar( 'asignacion' ) != 0) ? $this->request->getVar( 'asignacion' ) : 88,
				];

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( $update )->update( ) )
				{
					//Creamos registro en el historial
					$this->serieModel->insert( [ 'id_activo' => null, 'id_draft' => $draftItem[ 'Id' ], 'num_serie' => $draftItem[ 'NSerie_Activo' ] ] );
					echo json_encode( array( 'status' => 200, 'msg' => '¡Activo registrado!' ) );
				}
				else
					echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar el activo. Intente más tarde' ) );

			}
			catch (\Exception $e)
			{
				echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function SetCoordenadas( )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$update =
				[
					'Vida_Activo' => $this->request->getVar( 'vida' ),
					'ID_Sucursal' => $this->request->getVar( 'sucursal' ),
					'ID_Area' => $this->request->getVar( 'area' ),
				];

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( $update )->update( ) )
				{
					$activo = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
										   	->where( 'ID_Company', $this->session->empresa )
											->select( [ 'ID_Activo', 'status' ] )
											->first( );
					if ($activo['status'] == 'activado') 
						$this->activoModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
											->where('ID_Company', $this->session->empresa)
											->set([ 'depreActual' => $this->request->getVar('vidaActual') ])
											->update();
					
					echo json_encode( array( 'status' => 200 ) );
				}
				else
					echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar el activo. Intente más tarde' ) );

			}
			catch (\Exception $e)
			{
			echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function UpdateCoordenadas( )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$update =
				[
					'Fec_Inventario' => date( 'Y/n/j H:i:s' ),
					'GPS' => $this->request->getVar( 'gps' ),
				];

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( $update )->update( ) )
					echo json_encode( array( 'status' => 200 ) );
				else
					echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar el activo. Intente más tarde' ) );

			}
			catch (\Exception $e)
			{
			echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function GetImageFront( $codigo )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$activo = $this->draftModel->where( 'ID_Activo', $codigo )->select( [ 'Ima_ActivoFront' ] )->first( );

				$imgFront = null;

				if ( !empty($activo[ 'Ima_ActivoFront' ]) && strlen($activo[ 'Ima_ActivoFront' ]) > 1 )
				{
					$file = WRITEPATH . 'uploads/' . $activo[ 'Ima_ActivoFront' ];
					$data = file_get_contents($file);
					$dataImage = 'data:image/png;base64,' . base64_encode($data);

					$imgFront = '<img id="front-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageFront( )">';
				}

				echo $imgFront;
			}
			catch (\Exception $e)
			{
				echo $e->getMessage();
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function GetImageRight( $codigo )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$activo = $this->draftModel->where( 'ID_Activo', $codigo )->select( [ 'Ima_ActivoRight' ] )->first( );

				$imgRight = null;

				if ( !empty($activo[ 'Ima_ActivoRight' ]) && strlen($activo[ 'Ima_ActivoRight' ]) > 1 )
				{
					$file = WRITEPATH . 'uploads/' . $activo[ 'Ima_ActivoRight' ];
					$data = file_get_contents($file);
					$dataImage = 'data:image/png;base64,' . base64_encode($data);

					$imgRight = '<img id="right-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageRight( )">';
				}

				echo $imgRight;
			}
			catch (\Exception $e)
			{
				echo $e->getMessage();
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function GetImageLeft( $codigo )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$activo = $this->draftModel->where( 'ID_Activo', $codigo )->select( [ 'Ima_ActivoLeft' ] )->first( );

				$imgLeft = null;

				if ( !empty($activo[ 'Ima_ActivoLeft' ]) && strlen($activo[ 'Ima_ActivoLeft' ]) > 1 )
				{
					$file = WRITEPATH . 'uploads/' . $activo[ 'Ima_ActivoLeft' ];
					$data = file_get_contents($file);
					$dataImage = 'data:image/png;base64,' . base64_encode($data);

					$imgLeft = '<img id="left-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageLeft( )">';
				}

				echo $imgLeft;
			}
			catch (\Exception $e)
			{
				echo $e->getMessage();
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function SetImage( )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$update = [ ];
				$filename = $this->session->empresa.'/activos/'.$this->request->getVar( 'activo' ).'/' . $this->request->getVar( 'type' ).'.jpg';
				if (file_exists(WRITEPATH.'uploads/' . $filename))
					unlink(WRITEPATH . 'uploads/' . $filename);

				$photo = $this->request->getFile( 'file' )->store($this->session->empresa.'/activos/'.$this->request->getVar( 'activo' ).'/', $this->request->getVar( 'type' ).'.jpg');
				
				switch ( $this->request->getVar( 'type' ) )
				{
					case 'front':
						$update =
						[
							'Fec_Inventario' => date( 'Y/n/j H:i:s' ),
							'Ima_ActivoFront' => $photo,
						];
						break;
					case 'right':
						$update =
						[
							'Fec_Inventario' => date( 'Y/n/j H:i:s' ),
							'Ima_ActivoRight' => $photo,
						];
						break;
					case 'left':
						$update =
						[
							'Fec_Inventario' => date( 'Y/n/j H:i:s' ),
							'Ima_ActivoLeft' => $photo,
						];
						break;
				}

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'activo' ) )->set( $update )->update( ) )
				{
					echo json_encode( array( 'status' => 200, 'msg' => '¡Imagen actualizada!' ) );
				}
				else
					echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar la imagen del activo. Intente más tarde' ) );
			}
			catch (\Exception $e)
			{
				echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function DeleteImage( )
	{
		if ( $this->request->isAJAX( ) )
   		{
      		try
      		{
				$activo = $this->request->getVar( 'codigo' );
				$tipo;

				switch ( $this->request->getVar( 'type' ) )
				{
					case 'front':
						$tipo = 'Ima_ActivoFront';
						break;
					case 'right':
						$tipo = 'Ima_ActivoRight';
						break;
					case 'left':
						$tipo = 'Ima_ActivoLeft';
						break;
				}

				$update =
				[
					$tipo => null
				];

				if ( $this->draftModel->where( 'ID_Activo', $activo )->set( $update )->update( ) )
				{
					echo json_encode( array( 'status' => 200, 'msg' => '¡Imagen eliminada!' ) );
				}
				else
					echo json_encode( array( 'status' => 400, 'msg' => 'Error al eliminar la imagen del activo. Intente más tarde' ) );

			}
			catch (\Exception $e)
			{
				echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	//método que funciona exclusivamente con AJAX - JQUERY
	function UpdateActivoFromDraft( )
	{
		if ( $this->request->isAJAX( ) )
   		{
			$campos =
			[
				'ID_Activo', 'Nom_Activo', 'BC_Activo', 'ID_Company', 'ID_Sucursal',
				'ID_Area', 'ID_CC', 'ID_Asignado', 'ID_Proceso', 'ID_Status', 'NSerie_Activo', 'ID_Tipo',
				'Des_Activo', 'ID_MetDepre', 'Vida_Activo', 'GPS', 'Fec_Inventario',
				'User_Inventario', 'Comentarios', 'status', 'Ima_ActivoLeft', 'Ima_ActivoRight', 'Ima_ActivoFront',
			];

			$SQL = "SELECT id_empresa FROM user_empresa WHERE id_usuario = ". $this->session->id;
			$SQLResult = $this->db->query( $SQL )->getResult( );

			$empresas = [ ];
			
			foreach( $SQLResult as $empresa )
			{
				array_push( $empresas, $empresa->id_empresa );
			}
			$draftBuilder = $this->db->table( 'draft' );
			$draftBuilder->where( 'ID_Activo', $this->request->getVar( 'activo' ) );

			if ($this->request->getVar('inventariar') == 'true')
				$draftBuilder->update([ 'TS_Update' => date( 'Y/n/j H:i:s' ), 'Fec_Inventario' => date( 'Y/n/j H:i:s' )]);
			else			
				$draftBuilder->update([ 'TS_Update' => date( 'Y/n/j H:i:s' ) ]);
			
			$draft = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'activo' ) )
									   ->whereIn( 'ID_Company', $empresas )
									   ->select( $campos )
									   ->first( );

			if ( $draft[ 'status' ] == 'activado' )
			{
				$builder = $this->db->table( 'activos' );
				$activoData =
				[
					'TS_Update' => date( 'Y/n/j H:i:s' ),
					'Nom_Activo' => $draft[ 'Nom_Activo' ],
					'BC_Activo' => $draft[ 'BC_Activo' ],
					'ID_Company' => $draft[ 'ID_Company' ],
					'ID_Sucursal' => $draft[ 'ID_Sucursal' ],
					'ID_Area' => $draft[ 'ID_Area' ],
					'ID_CC' => $draft[ 'ID_CC' ],
					'ID_Asignado' => $draft[ 'ID_Asignado' ],
					'ID_Proceso' => $draft[ 'ID_Proceso' ],
					'ID_Status' => $draft[ 'ID_Status' ],
					'NSerie_Activo' => $draft[ 'NSerie_Activo' ],
					'ID_Tipo' => $draft[ 'ID_Tipo' ],
					'Des_Activo' => $draft[ 'Des_Activo' ],
					'Vida_Activo' => $draft[ 'Vida_Activo' ],
					'GPS' => $draft[ 'GPS' ],
					'Fec_Inventario' => $draft[ 'Fec_Inventario' ],
					'User_Inventario' => $draft[ 'User_Inventario' ],
					'Comentarios' => $draft[ 'Comentarios' ],
					'Ima_ActivoFront' => $draft[ 'Ima_ActivoFront' ],
					'Ima_ActivoRight' => $draft[ 'Ima_ActivoRight' ],
					'Ima_ActivoLeft' => $draft[ 'Ima_ActivoLeft' ],
				];
				$builder->where( 'ID_Activo', $this->request->getVar( 'activo' ) );
				$builder->update( $activoData );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	public function UpdateSucursal( )
	{
		if ( $this->request->isAJAX( ) )
		{
			$sucursales = $this->sucursalModel->where( 'ID_Empresa', $this->request->getVar( 'empresa' ))->findAll( );
			$areas = $this->areaModel->where( 'id_empresa', $this->request->getVar( 'empresa' ))->findAll( );
			echo json_encode( array( 'status' => 200, 'sucursales' => $sucursales, 'areas' => $areas ) );
		}
		else
			return view( 'errors/cli/error_404' );
	}

	public function ExcelActivos( )
	{
		$activos = $this->activoModel->where( 'ID_Company', $this->session->empresa )
									->where( 'TS_Delete', null )
									->select('Id, ID_Activo, Nom_Activo, User_Inventario, ID_Area, ID_Sucursal, Fec_Inventario, ID_CC, ID_Tipo, TS_Create, TS_Update, Ima_ActivoLeft, Ima_ActivoRight, Ima_ActivoFront')
									->findAll();

		$SQL = "SELECT * FROM empresa_periodo WHERE id_empresa = " . $this->session->empresa . " AND status = 1";
		$builderPeriodo = $this->db->query( $SQL );
		$periodo = $builderPeriodo->getResult( );

		$spreadsheet = new Spreadsheet( );
		$sheet = $spreadsheet->getActiveSheet();

		//iniciamos configuración inicial
		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(30);
		$sheet->getColumnDimension('G')->setWidth(30);
		$sheet->getColumnDimension('H')->setWidth(20);
		$sheet->getColumnDimension('I')->setWidth(20);
		$sheet->getColumnDimension('J')->setWidth(20);
		$sheet->getColumnDimension('K')->setWidth(20);
		$sheet->getColumnDimension('L')->setWidth(20);
		$sheet->getColumnDimension('M')->setWidth(20);
		$sheet->getColumnDimension('N')->setWidth(50);
		$sheet->getColumnDimension('O')->setWidth(50);
		$sheet->getColumnDimension('P')->setWidth(50);

		//iniciamos tabla 
		$sheet->setCellValue( 'A1', 'Número de activo' );
		$sheet->setCellValue( 'B1', 'Tipo de activo' );
		$sheet->setCellValue( 'C1', 'Nombre bien' );
		$sheet->setCellValue( 'D1', 'Centro de costos' );
		$sheet->setCellValue( 'E1', 'Nombre usuario asignado' );
		$sheet->setCellValue( 'F1', 'Apellido usuario asignado' );
		$sheet->setCellValue( 'G1', 'Correo usuario asignado' );
		$sheet->setCellValue( 'H1', 'Empresa' );
		$sheet->setCellValue( 'I1', 'Sucursal' );
		$sheet->setCellValue( 'J1', 'Área' );
		$sheet->setCellValue( 'K1', 'Fecha de inventario' );
		$sheet->setCellValue( 'L1', 'Ultima actualización' );
		$sheet->setCellValue( 'M1', 'Status inventario' );
		$sheet->setCellValue( 'N1', 'Foto Frontal' );
		$sheet->setCellValue( 'O1', 'Foto Lat. Derecha' );
		$sheet->setCellValue( 'P1', 'Foto Lat. Izquierda' );

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
			
		$sheet->getStyle('A1:P1')->applyFromArray($styleHeadArray);

		$fila = 2;
		$empresa = $this->empresaModel->find($this->session->empresa);
		foreach( $activos as $activo )
		{
			$area = $this->areaModel->find($activo['ID_Area']);
			$sucursal = $this->sucursalModel->find($activo['ID_Sucursal']);
			$usuario = $this->userModel->find($activo['User_Inventario']);
			$cc = $this->ccModel->find($activo['ID_CC']);
			$tipo = $this->tipoModel->find($activo['ID_Tipo']);

			$inventario = false;
			if ($periodo != null) 
			{
				$fecha1 = explode('-', explode(' ', $activo['Fec_Inventario'])[0]);
				$fechaInicio = explode('-', $periodo[0]->fecha_inicio);
				$fechaFin = explode('-', $periodo[0]->fecha_fin);

				$fecha1Unix = strtotime($fecha1[2]."-".$fecha1[1]."-".$fecha1[0]." 00:00:00");
				$fechaInicioUnix = strtotime($fechaInicio[2]."-".$fechaInicio[1]."-".$fechaInicio[0]." 00:00:00");
				$fechaFinUnix = strtotime($fechaFin[2]."-".$fechaFin[1]."-".$fechaFin[0]." 00:00:00");
				
				if($fecha1Unix >= $fechaInicioUnix && $fecha1Unix <= $fechaFinUnix)
				$inventario = true;
			}

			$sheet->setCellValue( 'A' . $fila, $activo['ID_Activo'] );
			$sheet->setCellValue( 'B' . $fila, ( $tipo != null ) ? $tipo['Desc'] : 'Tipo no encontrado' );
			$sheet->setCellValue( 'C' . $fila, $activo['Nom_Activo'] );
			$sheet->setCellValue( 'D' . $fila, ( $cc != null ) ? $cc['Desc'] : 'Centro de costo no encontrado' );
			$sheet->setCellValue( 'E' . $fila, $usuario['nombre'] );
			$sheet->setCellValue( 'F' . $fila, $usuario['apellidos'] );
			$sheet->setCellValue( 'G' . $fila, $usuario['email'] );
			$sheet->setCellValue( 'H' . $fila, $empresa['nombre'] );
			$sheet->setCellValue( 'I' . $fila, ( $sucursal != null ) ? $sucursal['Desc'] : 'Sin sucursal' );
			$sheet->setCellValue( 'J' . $fila, ( $area != null ) ? $area['descripcion'] : 'Sin area' );
			$sheet->setCellValue( 'K' . $fila, $activo['TS_Create'] );
			$sheet->setCellValue( 'L' . $fila, $activo['TS_Update'] );
			$sheet->setCellValue( 'M' . $fila, ($inventario == true) ? 'Inventariado' : 'Pendiente' );
				
			//imagenes
			if ( $activo['Ima_ActivoFront'] != null) 
			{
				$sheet->setCellValue( 'N' . $fila, base_url() . '/activo/photos/fp/' . $activo['Id'] );
				$sheet->getCell( 'N' . $fila)->getHyperlink()->setUrl( base_url() . '/activo/photos/fp/' . $activo['Id'] );
			}
			else
				$sheet->setCellValue( 'N' . $fila, 'Sin imagen' );

			if ( $activo['Ima_ActivoRight'] != null) 
			{
				$sheet->setCellValue( 'O' . $fila, base_url() . '/activo/photos/rp/' . $activo['Id'] );
				$sheet->getCell( 'O' . $fila)->getHyperlink()->setUrl( base_url() . '/activo/photos/rp/' . $activo['Id'] );
			}
			else
				$sheet->setCellValue( 'O' . $fila, 'Sin imagen' );

			if ( $activo['Ima_ActivoLeft'] != null) 
			{
				$sheet->setCellValue( 'P' . $fila, base_url() . '/activo/photos/lp/' . $activo['Id'] );
				$sheet->getCell( 'P' . $fila)->getHyperlink()->setUrl( base_url() . '/activo/photos/lp/' . $activo['Id'] );
			}
			else
				$sheet->setCellValue( 'P' . $fila, 'Sin imagen' );

			$fila++;
		}

		$styleBodyArray = 
		[
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['argb' => '00000000'],
				],
			],
		];
		
		$sheet->getStyle('A2:P'.($fila - 1))->applyFromArray($styleBodyArray);
		
		$writer = new Xls($spreadsheet);

		$dia = date('Y/m/d');
		$hora = date('h:i');

		$nombre = $dia . '_' . $hora . '_Activos.xls';

		//response
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $nombre .'"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		
	}

	public function ExcelDraft( )
	{		
		$activos = $this->draftModel->where( 'ID_Company', $this->session->empresa )
									->where( 'status !=', 'activado' )
									->where( 'status !=', 'conciliado' )
									->where( 'status !=', 'eliminado' )
									->where( 'TS_Delete', null )
									->select('Id, ID_Activo, Nom_Activo, User_Inventario, ID_Area, ID_Sucursal, ID_CC, ID_Tipo, TS_Create, TS_Update, Ima_ActivoLeft, Ima_ActivoRight, Ima_ActivoFront')
									->findAll();

		$spreadsheet = new Spreadsheet( );
		$sheet = $spreadsheet->getActiveSheet();

		//iniciamos configuración inicial
		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(30);
		$sheet->getColumnDimension('G')->setWidth(30);
		$sheet->getColumnDimension('H')->setWidth(20);
		$sheet->getColumnDimension('I')->setWidth(20);
		$sheet->getColumnDimension('J')->setWidth(20);
		$sheet->getColumnDimension('K')->setWidth(20);
		$sheet->getColumnDimension('L')->setWidth(20);
		$sheet->getColumnDimension('M')->setWidth(50);
		$sheet->getColumnDimension('N')->setWidth(50);
		$sheet->getColumnDimension('O')->setWidth(50);

		//iniciamos tabla 
		$sheet->setCellValue( 'A1', 'Número de activo' );
		$sheet->setCellValue( 'B1', 'Tipo de activo' );
		$sheet->setCellValue( 'C1', 'Nombre bien' );
		$sheet->setCellValue( 'D1', 'Centro de costos' );
		$sheet->setCellValue( 'E1', 'Nombre usuario asignado' );
		$sheet->setCellValue( 'F1', 'Apellido usuario asignado' );
		$sheet->setCellValue( 'G1', 'Correo usuario asignado' );
		$sheet->setCellValue( 'H1', 'Empresa' );
		$sheet->setCellValue( 'I1', 'Sucursal' );
		$sheet->setCellValue( 'J1', 'Área' );
		$sheet->setCellValue( 'K1', 'Fecha de inventario' );
		$sheet->setCellValue( 'L1', 'Ultima actualización' );
		$sheet->setCellValue( 'M1', 'Foto Frontal' );
		$sheet->setCellValue( 'N1', 'Foto Lat. Derecha' );
		$sheet->setCellValue( 'O1', 'Foto Lat. Izquierda' );

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
		
		$sheet->getStyle('A1:O1')->applyFromArray($styleHeadArray);

		$fila = 2;

		$empresa = $this->empresaModel->find($this->session->empresa);
		foreach( $activos as $activo )
		{
			$area = $this->areaModel->find($activo['ID_Area']);
			$sucursal = $this->sucursalModel->find($activo['ID_Sucursal']);
			$usuario = $this->userModel->find($activo['User_Inventario']);
			$cc = $this->ccModel->find($activo['ID_CC']);
			$tipo = $this->tipoModel->find($activo['ID_Tipo']);

			$sheet->setCellValue( 'A' . $fila, $activo['ID_Activo'] );
			$sheet->setCellValue( 'B' . $fila, ( $tipo != null ) ? $tipo['Desc'] : 'Tipo no encontrado' );
			$sheet->setCellValue( 'C' . $fila, $activo['Nom_Activo'] );
			$sheet->setCellValue( 'D' . $fila, ( $cc != null ) ? $cc['Desc'] : 'Centro de costo no encontrado' );
			$sheet->setCellValue( 'E' . $fila, $usuario['nombre'] );
			$sheet->setCellValue( 'F' . $fila, $usuario['apellidos'] );
			$sheet->setCellValue( 'G' . $fila, $usuario['email'] );
			$sheet->setCellValue( 'H' . $fila, $empresa['nombre'] );
			$sheet->setCellValue( 'I' . $fila, ( $sucursal != null ) ? $sucursal['Desc'] : 'Sin sucursal' );
			$sheet->setCellValue( 'J' . $fila, ( $area != null ) ? $area['descripcion'] : 'Sin area' );
			$sheet->setCellValue( 'K' . $fila, $activo['TS_Create'] );
			$sheet->setCellValue( 'L' . $fila, $activo['TS_Update'] );
							
			//imagenes
			if ( $activo['Ima_ActivoFront'] != null) 
			{
				$sheet->setCellValue( 'M' . $fila, base_url() . '/activos/photos/fp/' . $activo['Id'] );
				$sheet->getCell( 'M' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/fp/' . $activo['Id'] );
			}
			else
				$sheet->setCellValue( 'M' . $fila, 'Sin imagen' );

			if ( $activo['Ima_ActivoRight'] != null) 
			{
				$sheet->setCellValue( 'N' . $fila, base_url() . '/activos/photos/rp/' . $activo['Id'] );
				$sheet->getCell( 'N' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/rp/' . $activo['Id'] );
			}
			else
				$sheet->setCellValue( 'N' . $fila, 'Sin imagen' );

			if ( $activo['Ima_ActivoLeft'] != null) 
			{
				$sheet->setCellValue( 'O' . $fila, base_url() . '/activos/photos/lp/' . $activo['Id'] );
				$sheet->getCell( 'O' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/lp/' . $activo['Id'] );
			}
			else
				$sheet->setCellValue( 'O' . $fila, 'Sin imagen' );

			$fila++;
		}

		$styleBodyArray = 
		[
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['argb' => '00000000'],
				],
			],
		];
		
		$sheet->getStyle('A2:O'.($fila - 1))->applyFromArray($styleBodyArray);
		
		$writer = new Xls($spreadsheet);

		$dia = date('Y/m/d');
		$hora = date('h:i');

		$nombre = $dia . '_' . $hora . '_Draft.xls';

		//response
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $nombre .'"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function DraftImage( $type, $id )
	{
		try
		{
			$select = null;
			switch ($type) 
			{
				case 'fp':
					$select = 'Ima_ActivoFront';
					break;
				case 'rp':
					$select = 'Ima_ActivoRight';
					break;
				case 'lp':
					$select = 'Ima_ActivoLeft';
					break;
			}

			$activo = $this->draftModel->where( 'Id', $id )->select( [ $select ] )->first( );

			if ( $activo != null && $activo[ $select ] != null )
			{
				$file = WRITEPATH . 'uploads\\' . $activo[ $select ];
				$fp = fopen($file, 'rb');

				header("Content-Type: image/png");
				header("Content-Length: " . filesize($file));

				fpassthru($fp);
				exit;
			}
			else
				echo "Sin imagen";
		}
		catch (\Exception $e)
		{
			echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
		}
	}

	public function ActivoImage( $type, $id )
	{
		try
		{
			$select = null;
			switch ($type) 
			{
				case 'fp':
					$select = 'Ima_ActivoFront';
					break;
				case 'rp':
					$select = 'Ima_ActivoRight';
					break;
				case 'lp':
					$select = 'Ima_ActivoLeft';
					break;
			}

			$activo = $this->activoModel->where( 'Id', $id )->select( [ $select ] )->first( );

			if ( $activo != null && $activo[ $select ] != null )
			{
				$file = WRITEPATH . 'uploads\\' . $activo[ $select ];
				$fp = fopen($file, 'rb');

				header("Content-Type: image/png");
				header("Content-Length: " . filesize($file));

				fpassthru($fp);
				exit;
			}
			else
				echo "Sin imagen";
		}
		catch (\Exception $e)
		{
			echo "Sin imagen";
		}
	}

	public function LoadActivos()
	{
		if ( $this->session->has( 'isLoggin' ) && $this->session->has( 'tipo' ) && ( $this->session->tipo == 'admin' || $this->session->tipo == 'superadmin'))
		{
			//CSS, METAS y titulo
			$head = array( 'title' => 'Carga masiva | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );

			//sidebar
			$SQL = "SELECT empresas.id_empresa, empresas.nombre FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
			$builder = $this->db->query( $SQL );
			$empresas = $builder->getResult( );
			$sidebar = array( 'name' => $this->session->name, 'empresas' => $empresas, 'actual' => $this->session->empresa);
			echo view( 'backoffice/common/sidebar', $sidebar );

			//navbar
			echo view( 'backoffice/common/navbar' );

			//content - scanner
			echo view( 'backoffice/sections/load-activos' );

			//Scripts y librerias
			$footer = array( 'js' => 'load');
			echo view( 'backoffice/common/footer2', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
			return view( 'functions/redirect', $data );
		}
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
			$activos_subidos = [];
			foreach ( $rows as $activo ) 
			{
				//Validación de activos
				$linea++;
				$tipo = null;
				$cc = null;
				$user = null;
				$sucursal = null;
				$area = null;
				$error = false;
				$toInv1 = false;
				$toInv2 = false;

				if ($activo[0] == null) 
				{
					array_push($errores, [ 'activo' => 'Linea ' . $linea, 'problema' => 'La linea no contiene un id de activo' ]);
				}
				else
				{
					if($this->draftModel->where('ID_Activo', $activo[0])->where('ID_Company', $this->session->empresa)->first() == null)
					{

						if ($activo[1] == null) 
						{
							array_push($errores, [ 'activo' => 'Linea ' . $linea, 'problema' => 'La linea no contiene un tipo de activo' ]);
							$error = true;
						}
						else
						{
							$tipo = $this->tipoModel->like('Desc', $activo[1])->where('ID_Empresa', $this->session->empresa )->first();
							if($tipo == null)
							{
								array_push($errores, [ 'activo' => $activo[0], 'problema' => 'El tipo de activo  no está registrado en el sistema.' ]);
								$error = true;
							}
						}

						if ($activo[2] == null) 
						{
							array_push($errores, [ 'activo' => 'Linea ' . $linea, 'problema' => 'La linea no contiene un nombre de activo' ]);
							$error = true;
						}

						if ($activo[3] == null) 
						{
							array_push($errores, [ 'activo' => 'Linea ' . $linea, 'problema' => 'La linea no contiene un centro de costos' ]);
							$error = true;
						}
						else
						{
							$cc = $this->ccModel->like('Subcuenta', $activo[3])->where('id_empresa', $this->session->empresa )->first();
							if($cc == null)
							{
								array_push($errores, [ 'activo' => $activo[0], 'problema' => 'El centro de costos no está registrado en el sistema.' ]);
								$error = true;
							}
						}

						if ($activo[4] == null) 
						{
							array_push($errores, [ 'activo' => 'Linea ' . $linea, 'problema' => 'La linea no contiene el email del usuario' ]);
							$error = true;
						}
						else
						{
							$user = $this->userModel->where('email', $activo[4])->first();
							if($user == null)
							{
								array_push($errores, [ 'activo' => $activo[0], 'problema' => 'El usuario no está registrado en el sistema, se registró el activo sin usuario.' ]);
							}
						}

						if ($activo[5] == null) 
						{
							array_push($errores, [ 'activo' => 'Linea ' . $linea, 'problema' => 'La linea no contiene la sucursal' ]);
							$error = true;
						}
						else
						{
							$sucursal = $this->sucursalModel->like('Desc', $activo[5])->first();
							if($sucursal == null)
							{
								array_push($errores, [ 'activo' => $activo[0], 'problema' => 'La sucursal no está registrada en el sistema.' ]);
								$error = true;
							}
						}

						if ($activo[6] == null) 
						{
							array_push($errores, [ 'activo' => 'Linea ' . $linea, 'problema' => 'La linea no contiene el area' ]);
							$error = true;
						}
						else
						{
							$area = $this->areaModel->like('descripcion', $activo[6])->first();
							if($area == null)
							{
								array_push($errores, [ 'activo' => $activo[0], 'problema' => 'El área no está registrada en el sistema.' ]);
								$error = true;
							}
						}

						if ($activo[8] != null) 
						{
							$toInv1 = true;
						}

						if ($activo[9] != null) 
						{
							$toInv2 = true;
						}
						

						if (!$error) 
						{
							if ($toInv1 && $toInv2) 
							{
								$draft =
								[
									'ID_Activo' => $activo[0],
									'Nom_Activo' => $activo[2],
									'ID_Company' => $this->session->empresa,
									'ID_Tipo' => ($tipo == null) ? 0 : $tipo['id'],
									'Des_Activo' => ($activo[7] != null) ? $activo[7] : '-',
									'NSerie_Activo' => '-',
									'GPS' => $this->request->getVar('gps'),
									'ID_CC' => ($cc == null) ? 0 : $cc['id'],
									'User_Inventario' => ($user == null) ? 88 : $user['id_usuario'],
									'ID_Sucursal' => ($sucursal == null) ? 0 : $sucursal['id'],
									'ID_Area' => ($area == null) ? 0 : $area['id'],
									'TS_Create' => date( 'Y/n/j H:i:s' ),
									'status' => 'activado'
								];

								$load_activo = $this->draftModel->insert($draft);

								$nuevo_activo =
								[
									'ID_Activo' => $activo[0],
									'Nom_Activo' => $activo[2],
									'ID_Company' => $this->session->empresa,
									'ID_Tipo' => ($tipo == null) ? 0 : $tipo['id'],
									'Des_Activo' => '-',
									'NSerie_Activo' => '-',
									'ID_CC' => ($cc == null) ? 0 : $cc['id'],
									'User_Inventario' => ($user == null) ? 88 : $user['id_usuario'],
									'ID_Sucursal' => ($sucursal == null) ? 0 : $sucursal['id'],
									'ID_Area' => ($area == null) ? 0 : $area['id'],
									'Pre_Compra' => $activo[8],
									'Fec_Compra' => $activo[9],
									'TS_Create' => date( 'Y/n/j H:i:s' ),
								];

								$load_activo = $this->activoModel->insert($nuevo_activo);
							}
							else
							{
								$draft =
								[
									'ID_Activo' => $activo[0],
									'Nom_Activo' => $activo[2],
									'ID_Company' => $this->session->empresa,
									'ID_Tipo' => ($tipo == null) ? 0 : $tipo['id'],
									'Des_Activo' => ($activo[7] != null) ? $activo[7] : '-',
									'NSerie_Activo' => '-',
									'GPS' => $this->request->getVar('gps'),
									'ID_CC' => ($cc == null) ? 0 : $cc['id'],
									'User_Inventario' => ($user == null) ? 88 : $user['id_usuario'],
									'ID_Sucursal' => ($sucursal == null) ? 0 : $sucursal['id'],
									'ID_Area' => ($area == null) ? 0 : $area['id'],
									'TS_Create' => date( 'Y/n/j H:i:s' ),
									'status' => 'nuevo'
								];

								$load_activo = $this->draftModel->insert($draft);
							}

							$subidos++;

							$json =
							[
								'id' => $load_activo,
								'tipo' => $tipo['Desc'],
								'nombre' => $activo[2],
								'usuario' => $user['nombre'] . ' ' . $user['apellidos'],
								'fecha' => date( 'Y/n/j'),
								'id_activo' => $activo[0],
							];

							array_push( $activos_subidos, $json );
						}
					}
					else
					{
						array_push($errores, [ 'activo' => $activo[0], 'problema' => 'El activo ya está registrado' ]);
					}
				}
			}
			echo json_encode( array( 'status' => 200, 'errores' => $errores, 'subidos' => $subidos, 'activos' => $activos_subidos ) );
		}
		else
			return view( 'errors/cli/error_404' );
	}

	public function DownloadExcelExample( )
	{
		$spreadsheet = new Spreadsheet();
		$cargaSheet = $spreadsheet->getActiveSheet();

		//iniciamos configuración inicial
		$cargaSheet->getColumnDimension('A')->setWidth(20);
		$cargaSheet->getColumnDimension('B')->setWidth(30);
		$cargaSheet->getColumnDimension('C')->setWidth(30);
		$cargaSheet->getColumnDimension('D')->setWidth(30);
		$cargaSheet->getColumnDimension('E')->setWidth(30);
		$cargaSheet->getColumnDimension('F')->setWidth(30);
		$cargaSheet->getColumnDimension('G')->setWidth(30);
		$cargaSheet->getColumnDimension('H')->setWidth(30);
		$cargaSheet->getColumnDimension('I')->setWidth(30);
		$cargaSheet->getColumnDimension('J')->setWidth(30);

		//iniciamos tabla 
		$cargaSheet->setCellValue( 'A1', 'Número de activo' );
		$cargaSheet->setCellValue( 'B1', 'Tipo de activo' );
		$cargaSheet->setCellValue( 'C1', 'Nombre' );
		$cargaSheet->setCellValue( 'D1', 'Codigo de centro de costo' );
		$cargaSheet->setCellValue( 'E1', 'Email del usuario' );
		$cargaSheet->setCellValue( 'F1', 'Sucursal' );
		$cargaSheet->setCellValue( 'G1', 'Área' );
		$cargaSheet->setCellValue( 'H1', 'Descripción' );
		$cargaSheet->setCellValue( 'I1', 'Precio de compra' );
		$cargaSheet->setCellValue( 'J1', 'Fecha de compra' );

		$tiposSheet = new Worksheet($spreadsheet, 'Tipos');
		$spreadsheet->addSheet($tiposSheet, 1);

		$tiposSheet->getColumnDimension('A')->setWidth(50);
		$tiposSheet->setCellValue( 'A1', 'Tipos' );

		$tipos = $this->tipoModel->where('ID_Empresa', $this->session->empresa)->findAll();
		$contador = 2;
		
		foreach ($tipos as $tipo) 
		{
			$tiposSheet->setCellValue( 'A' . $contador, $tipo['Desc'] );
			$contador++;
		}

		$ccSheet = new Worksheet($spreadsheet, 'Centro de costos');
		$spreadsheet->addSheet($ccSheet, 2);

		$ccSheet->getColumnDimension('A')->setWidth(50);
		$ccSheet->setCellValue( 'A1', 'Centro de costo' );
		$ccSheet->setCellValue( 'B1', 'Numero' );

		$ccs = $this->ccModel->where('id_empresa', $this->session->empresa)->where('Subcuenta !=', null)->findAll();
		$contador = 2;
		
		foreach ($ccs as $cc) 
		{
			$ccSheet->setCellValue( 'A' . $contador, $cc['Desc'] );
			$ccSheet->setCellValue( 'B' . $contador, $cc['Subcuenta'] );
			$contador++;
		}

		$userSheet = new Worksheet($spreadsheet, 'Usuarios');
		$spreadsheet->addSheet($userSheet, 3);

		$userSheet->getColumnDimension('A')->setWidth(40);
		$userSheet->getColumnDimension('B')->setWidth(40);
		$userSheet->getColumnDimension('C')->setWidth(40);
		$userSheet->setCellValue( 'A1', 'Nombre' );
		$userSheet->setCellValue( 'B1', 'Apellidos' );
		$userSheet->setCellValue( 'C1', 'Correo' );

		$SQL = "SELECT usuarios.nombre, usuarios.apellidos, usuarios.email FROM usuarios, user_empresa WHERE user_empresa.id_usuario = usuarios.id_usuario AND usuarios.perfil <> 'superadmin' AND usuarios.deleted_at IS NULL AND user_empresa.id_empresa = " . $this->session->empresa;
		$builder = $this->db->query( $SQL );
		$users = $builder->getResult( );
		$contador = 2;
		
		foreach ($users as $user) 
		{
			$userSheet->setCellValue( 'A' . $contador, $user->nombre );
			$userSheet->setCellValue( 'B' . $contador, $user->apellidos );
			$userSheet->setCellValue( 'C' . $contador, $user->email );
			$contador++;
		}

		$sucursalSheet = new Worksheet($spreadsheet, 'Sucursales');
		$spreadsheet->addSheet($sucursalSheet, 4);

		$sucursalSheet->getColumnDimension('A')->setWidth(40);
		$sucursalSheet->setCellValue( 'A1', 'Nombre' );

		$sucursales = $this->sucursalModel->where('ID_Empresa', $this->session->empresa)->findAll();
		$contador = 2;

		foreach ($sucursales as $sucursal) 
		{
			$sucursalSheet->setCellValue( 'A' . $contador, $sucursal['Desc'] );
			$contador++;
		}

		$areaSheet = new Worksheet($spreadsheet, 'Areas');
		$spreadsheet->addSheet($areaSheet, 5);

		$areaSheet->getColumnDimension('A')->setWidth(40);
		$areaSheet->setCellValue( 'A1', 'Nombre' );

		$areas = $this->areaModel->where('id_empresa', $this->session->empresa)->findAll();
		$contador = 2;

		foreach ($areas as $area) 
		{
			$areaSheet->setCellValue( 'A' . $contador, $area['descripcion'] );
			$contador++;
		}

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
			
		$cargaSheet->getStyle('A1:H1')->applyFromArray($styleHeadArray);
		$tiposSheet->getStyle('A1:A1')->applyFromArray($styleHeadArray);
		$ccSheet->getStyle('A1:B1')->applyFromArray($styleHeadArray);
		$userSheet->getStyle('A1:C1')->applyFromArray($styleHeadArray);
		$sucursalSheet->getStyle('A1:A1')->applyFromArray($styleHeadArray);
		$areaSheet->getStyle('A1:A1')->applyFromArray($styleHeadArray);

		$spreadsheet->setActiveSheetIndex(0);
		$writer = new Xls($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Carga.xls"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function ReponseActivos( )
	{
		$drafts = $this->draftModel->findAll();

		foreach( $drafts as $draft )
		{
			if($draft['status'] != 'nuevo')
			{
				
				$validateActivo = $this->activoModel->where('ID_Activo', $draft['ID_Activo'])->findAll();

				if ($validateActivo == null) 
				{
					$activoData =
					[
						'ID_Activo' => $draft[ 'ID_Activo' ],
						'Nom_Activo' => $draft[ 'Nom_Activo' ],
						'BC_Activo' => $draft[ 'BC_Activo' ],
						'ID_Company' => $draft[ 'ID_Company' ],
						'ID_Sucursal' => $draft[ 'ID_Sucursal' ],
						'ID_Area' => $draft[ 'ID_Area' ],
						'ID_CC' => $draft[ 'ID_CC' ],
						'ID_Asignado' => $draft[ 'ID_Asignado' ],
						'ID_Proceso' => $draft[ 'ID_Proceso' ],
						'ID_Status' => $draft[ 'ID_Status' ],
						'NSerie_Activo' => $draft[ 'NSerie_Activo' ],
						'ID_Tipo' => $draft[ 'ID_Tipo' ],
						'Des_Activo' => $draft[ 'Des_Activo' ],
						'Vida_Activo' => $draft[ 'Vida_Activo' ],
						'GPS' => $draft[ 'GPS' ],
						'Fec_Inventario' => $draft[ 'Fec_Inventario' ],
						'User_Inventario' => $draft[ 'User_Inventario' ],
						'Comentarios' => $draft[ 'Comentarios' ],
						'Ima_ActivoFront' => $draft[ 'Ima_ActivoFront' ],
						'Ima_ActivoRight' => $draft[ 'Ima_ActivoRight' ],
						'Ima_ActivoLeft' => $draft[ 'Ima_ActivoLeft' ],
						'TS_Create' => $draft[ 'TS_Create' ],
						'TS_Update' => $draft[ 'TS_Update' ],
					];

					$this->activoModel->insert($activoData);
				}
			}
		}
	}

	public function Depreciacion()
	{
		if ( $this->session->has( 'isLoggin' ) && $this->session->has( 'tipo' ) && ( $this->session->tipo == 'admin' || $this->session->tipo == 'superadmin'))
		{
			//CSS, METAS y titulo
			$head = array( 'title' => 'Dashboard | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );
			

			//content - scanner
			echo view( 'backoffice/test/depre' );

			//Scripts y librerias
			$footer = array( 'js' => 'depre',  );
			echo view( 'backoffice/common/footer2', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
			return view( 'functions/redirect', $data );
		}
	}

}
