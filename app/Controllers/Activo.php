<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
	}
	  
	public function Index( )
	{
		if ( $this->session->has( 'isLoggin' ) && $this->session->has( 'tipo' ) && $this->session->tipo == 'admin')
		{
			//CSS, METAS y titulo
			$head = array( 'title' => 'Dashboard | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );

			//sidebar
			$sidebar = array( 'name' => $this->session->name );
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
			try
			{
				$tipos = $this->tipoModel->where( 'ID_Empresa', $this->session->empresa )->findAll( );
				$usuarios = $this->userModel->where( 'id_empresa', $this->session->empresa )->findAll( );
				$cc = $this->ccModel->where( 'id_empresa', $this->session->empresa )->findAll( );

				$SQL = "SELECT empresas.* FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
				$builder = $this->db->query( $SQL );
				$empresas = $builder->getResult( );

				$SQL = "SELECT * FROM sucursales WHERE ID_Empresa = " . $this->session->empresa;
				$builder = $this->db->query( $SQL );
				$sucursales = $builder->getResult( );

				$SQL = "SELECT * FROM areas WHERE id_empresa = " . $this->session->empresa;
				$builder = $this->db->query( $SQL );
				$areas = $builder->getResult( );

				if ( $tipos )
				$json = array( 'status' => 200, 'types' => $tipos, 'users' => $usuarios,
												'empresas' => $empresas, 'sucursales' => $sucursales,
												'cc' => $cc, 'areas' => $areas );
				else
				$json = array( 'status' => 401, 'msg' => 'No se pudo obtener la informacion del servidor' );

				echo json_encode( $json );
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
					'Pre_Compra', 'Fec_Expira', 'NSerie_Activo', 'ID_Tipo',
					'Des_Activo', 'ID_MetDepre', 'Vida_Activo', 'GPS', 'Fec_Inventario',
					'User_Inventario', 'Comentarios', 'User_Create', 'User_Update', 'User_Delete',
				];

				$SQL = "SELECT id_empresa FROM user_empresa WHERE id_usuario = ". $this->session->id;

				$SQLResult = $this->db->query( $SQL, [ $campos, $this->request->getVar( 'codigo' ) ] )->getResult( );

				$empresas = [ ];
				
				foreach( $SQLResult as $empresa )
				{
					array_push( $empresas, $empresa->id_empresa );
				}

				$activo = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
										   ->whereIn( 'ID_Company', $empresas )
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
					'User_Inventario' => $this->request->getVar( 'asignacion' ),
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
					'GPS' => $this->request->getVar( 'gps' ),
					'Vida_Activo' => $this->request->getVar( 'vida' ),
					'ID_Company' => $this->request->getVar( 'empresa' ),
					'ID_Sucursal' => $this->request->getVar( 'sucursal' ),
					'ID_Area' => $this->request->getVar( 'area' ),
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

				if ( $activo[ 'Ima_ActivoFront' ] != null )
				{
					$dataImage = 'data:image/jpeg;base64,'. base64_encode( $activo[ 'Ima_ActivoFront' ] );

					$imgFront = '<img id="front-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageFront( )">';
				}

				echo $imgFront;
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
	function GetImageRight( $codigo )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$activo = $this->draftModel->where( 'ID_Activo', $codigo )->select( [ 'Ima_ActivoRight' ] )->first( );

				$imgRight = null;

				if ( $activo[ 'Ima_ActivoRight' ] != null )
				{
					$dataImage = 'data:image/jpeg;base64,'. base64_encode( $activo[ 'Ima_ActivoRight' ] );

					$imgRight = '<img id="right-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageRight( )">';
				}

				echo $imgRight;
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
	function GetImageLeft( $codigo )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$activo = $this->draftModel->where( 'ID_Activo', $codigo )->select( [ 'Ima_ActivoLeft' ] )->first( );

				$imgLeft = null;

				if ( $activo[ 'Ima_ActivoLeft' ] != null )
				{
					$dataImage = 'data:image/jpeg;base64,'. base64_encode( $activo[ 'Ima_ActivoLeft' ] );

					$imgLeft = '<img id="left-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageLeft( )">';
				}

				echo $imgLeft;
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
	function SetImage( )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$update = [ ];

				$photo = $this->request->getFile( 'file' );

				$image = file_get_contents( $photo->getTempName( )  );

				switch ( $this->request->getVar( 'type' ) )
				{
					case 'front':
						$update =
						[
							'Ima_ActivoFront' => $image,
						];
						break;
					case 'right':
						$update =
						[
							'Ima_ActivoRight' => $image,
						];
						break;
					case 'left':
						$update =
						[
							'Ima_ActivoLeft' => $image,
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
		try 
		{
			$builder = $this->db->table( 'activos' );
			$builder->select( 'activos.Id, activos.ID_Activo, tipos.Desc as tipo, activos.Nom_Activo, cc.Desc as cc, usuarios.nombre, usuarios.apellidos, usuarios.email, 
							   empresas.nombre as empresa, sucursales.Desc as sucursal, areas.descripcion as area, activos.TS_Create, activos.TS_Update' );
			$builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
			$builder->join( 'cc', 'cc.ID_CC = activos.ID_CC' );
			$builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
			$builder->join( 'empresas', 'empresas.id_empresa = activos.ID_Company' );
			$builder->join( 'sucursales', 'sucursales.id = activos.ID_Sucursal' );
			$builder->join( 'areas', 'areas.id = activos.ID_Area' );
			$builder->where( 'activos.TS_Delete', null );
			$builder->where( 'activos.ID_Company', $this->session->empresa );
			$activos = $builder->get( )->getResult( );

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
			foreach( $activos as $activo )
			{
				$sheet->setCellValue( 'A' . $fila, $activo->ID_Activo );
				$sheet->setCellValue( 'B' . $fila, $activo->tipo );
				$sheet->setCellValue( 'C' . $fila, $activo->Nom_Activo );
				$sheet->setCellValue( 'D' . $fila, $activo->cc );
				$sheet->setCellValue( 'E' . $fila, $activo->nombre );
				$sheet->setCellValue( 'F' . $fila, $activo->apellidos );
				$sheet->setCellValue( 'G' . $fila, $activo->email );
				$sheet->setCellValue( 'H' . $fila, $activo->empresa );
				$sheet->setCellValue( 'I' . $fila, $activo->sucursal );
				$sheet->setCellValue( 'J' . $fila, $activo->area );
				$sheet->setCellValue( 'K' . $fila, $activo->TS_Create );
				$sheet->setCellValue( 'L' . $fila, $activo->TS_Update );

				$activo_imagenes = $this->activoModel->where('Id', $activo->Id)->select('activos.Ima_ActivoLeft, activos.Ima_ActivoRight, activos.Ima_ActivoFront')->first();
				
				//imagenes
				if ( $activo_imagenes['Ima_ActivoFront'] != NULL) 
				{
					$sheet->setCellValue( 'M' . $fila, base_url() . '/activos/photos/fp/' . $activo->Id );
					$sheet->getCell( 'M' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/fp/' . $activo->Id );
				}
				else
					$sheet->setCellValue( 'M' . $fila, 'Sin imagen' );

				if ( $activo_imagenes['Ima_ActivoRight'] != NULL) 
				{
					$sheet->setCellValue( 'N' . $fila, base_url() . '/activos/photos/rp/' . $activo->Id );
					$sheet->getCell( 'N' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/rp/' . $activo->Id );
				}
				else
					$sheet->setCellValue( 'N' . $fila, 'Sin imagen' );

				if ( $activo_imagenes['Ima_ActivoLeft'] != NULL) 
				{
					$sheet->setCellValue( 'O' . $fila, base_url() . '/activos/photos/lp/' . $activo->Id );
					$sheet->getCell( 'O' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/lp/' . $activo->Id );
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

			$nombre = $dia . '_' . $hora . '_Activos.xls';

			//response
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $nombre .'"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');	
		} 
		catch (\Throwable $th) 
		{
			echo $th->getMessage();
		}
	}

	public function ExcelDraft( )
	{
		$builder = $this->db->table( 'draft' );
		$builder->select( 'draft.Id, draft.ID_Activo, tipos.Desc as tipo, draft.Nom_Activo, cc.Desc as cc, usuarios.nombre, 
						   usuarios.apellidos, usuarios.email, empresas.nombre as empresa, sucursales.Desc as sucursal, 
						   areas.descripcion as area, draft.TS_Create, draft.TS_Update' );
		$builder->join( 'tipos', 'tipos.id = draft.ID_Tipo' );
		$builder->join( 'cc', 'cc.ID_CC = draft.ID_CC' );
		$builder->join( 'usuarios', 'usuarios.id_usuario = draft.User_Inventario' );
		$builder->join( 'empresas', 'empresas.id_empresa = draft.ID_Company' );
		$builder->join( 'sucursales', 'sucursales.id = draft.ID_Sucursal' );
		$builder->join( 'areas', 'areas.id = draft.ID_Area' );
		$builder->where( 'draft.TS_Delete', null );
		$builder->where( 'draft.status', 'nuevo' );
        $builder->where( 'draft.ID_Company', $this->session->empresa );
		$activos = $builder->get( )->getResult( );

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
		foreach( $activos as $activo )
		{
			$sheet->setCellValue( 'A' . $fila, $activo->ID_Activo );
			$sheet->setCellValue( 'B' . $fila, $activo->tipo );
			$sheet->setCellValue( 'C' . $fila, $activo->Nom_Activo );
			$sheet->setCellValue( 'D' . $fila, $activo->cc );
			$sheet->setCellValue( 'E' . $fila, $activo->nombre );
			$sheet->setCellValue( 'F' . $fila, $activo->apellidos );
			$sheet->setCellValue( 'G' . $fila, $activo->email );
			$sheet->setCellValue( 'H' . $fila, $activo->empresa );
			$sheet->setCellValue( 'I' . $fila, $activo->sucursal );
			$sheet->setCellValue( 'J' . $fila, $activo->area );
			$sheet->setCellValue( 'K' . $fila, $activo->TS_Create );
			$sheet->setCellValue( 'L' . $fila, $activo->TS_Update );
			
			$activo_imagenes = $this->draftModel->where('Id', $activo->Id)->select('Ima_ActivoLeft, Ima_ActivoRight, Ima_ActivoFront')->first();
				
			//imagenes
			if ( $activo_imagenes['Ima_ActivoFront'] != NULL) 
			{
				$sheet->setCellValue( 'M' . $fila, base_url() . '/activos/photos/fp/' . $activo->Id );
				$sheet->getCell( 'M' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/fp/' . $activo->Id );
			}
			else
				$sheet->setCellValue( 'M' . $fila, 'Sin imagen' );

			if ( $activo_imagenes['Ima_ActivoRight'] != NULL) 
			{
				$sheet->setCellValue( 'N' . $fila, base_url() . '/activos/photos/rp/' . $activo->Id );
				$sheet->getCell( 'N' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/rp/' . $activo->Id );
			}
			else
				$sheet->setCellValue( 'N' . $fila, 'Sin imagen' );

			if ( $activo_imagenes['Ima_ActivoLeft'] != NULL) 
			{
				$sheet->setCellValue( 'O' . $fila, base_url() . '/activos/photos/lp/' . $activo->Id );
				$sheet->getCell( 'O' . $fila)->getHyperlink()->setUrl( base_url() . '/activos/photos/lp/' . $activo->Id );
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
				$dataImage = 'data:image/jpeg;base64,'. base64_encode( $activo[ $select ] );
				echo '<img src="'. $dataImage .'" style="width: 25%">';
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
				$dataImage = 'data:image/jpeg;base64,'. base64_encode( $activo[ $select ] );
				echo '<img src="'. $dataImage .'" style="width: 25%">';
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
		if ( $this->session->has( 'isLoggin' ) && $this->session->has( 'tipo' ) && $this->session->tipo == 'admin')
		{
			//CSS, METAS y titulo
			$head = array( 'title' => 'Dashboard | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );

			//sidebar
			$sidebar = array( 'name' => $this->session->name );
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
			$cabezales = true;

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

			//sin usuario  =>  88
			$errores = [];	
			foreach ( $rows as $activo ) 
			{
				//Validación de activos
				$tipo = null;
				$cc = null;
				$user = null;
				$sucursal = null;
				$area = null;
				if($this->activoModel->where('ID_Activo', $activo[0])->first())
					array_push($errores, [ 'El activo ' . $activo[0] . ' ya está registrado en el sistema.' ]);
				else
				{
					$tipo = $this->tipoModel->where('Desc', $activo[1])->where('ID_Empresa', $this->session->empresa )->first();
					if($tipo == null)
					{
						array_push($errores, [ 'Activo '.$activo[0].': El tipo de activo "' . $activo[0] . '" no está registrado en el sistema.' ]);
						return;
					}

					$cc = $this->ccModel->where('Subcuenta', $activo[3])->where('id_empresa', $this->session->empresa )->first();
					if($cc == null)
					{
						array_push($errores, [ 'Activo '.$activo[0].': El centro de costos no está registrado en el sistema.' ]);
						return;
					}

					$user = $this->userModel->where('email', $activo[4])->first();
					if($user == null)
					{
						array_push($errores, [ 'Activo '.$activo[0].': El usuario no está registrado en el sistema, se registrará el activo sin usuario.' ]);
					}

					$sucursal = $this->sucursalModel->like('Desc', $activo[5])->first();
					if($sucursal == null)
					{
						array_push($errores, [ 'Activo '.$activo[0].': La sucursal no está registrada en el sistema.' ]);
						return;
					}

					$area = $this->areaModel->like('descripcion', $activo[6])->first();
					if($area == null)
					{
						array_push($errores, [ 'Activo '.$activo[0].': El area no está registrado en el sistema.' ]);
						return;
					}

					$draft =
					[
						'ID_Activo' => $activo[0],
						'Nom_Activo' => $activo[2],
						'ID_Company' => $this->session->empresa,
						'ID_Tipo' => ($tipo == null) ? 0 : $tipo['id'],
						'Des_Activo' => '-',
						'NSerie_Activo' => '-',
						'ID_CC' => ($cc == null) ? 0 : $tipo['id'],
						'User_Inventario' => ($user == null) ? 88 : $user['id_usuario'],
						'ID_Sucursal' => ($sucursal == null) ? 0 : $sucursal['id'],
						'ID_Area' => ($area == null) ? 0 : $area['id'],
						'TS_Create' => date( 'Y/n/j H:i:s' ),
						'status' => 'activado'
					];

					$activo =
					[
						'ID_Activo' => $activo[0],
						'Nom_Activo' => $activo[2],
						'ID_Company' => $this->session->empresa,
						'ID_Tipo' => ($tipo == null) ? 0 : $tipo['id'],
						'Des_Activo' => '-',
						'NSerie_Activo' => '-',
						'ID_CC' => ($cc == null) ? 0 : $tipo['id'],
						'User_Inventario' => ($user == null) ? 88 : $user['id_usuario'],
						'ID_Sucursal' => ($sucursal == null) ? 0 : $sucursal['id'],
						'ID_Area' => ($area == null) ? 0 : $area['id'],
						'TS_Create' => date( 'Y/n/j H:i:s' ),
					];

					$this->draftModel->insert($draft);
					$this->activoModel->insert($activo);
				}
			}
			echo json_encode( array( 'status' => 200, 'errores' => $errores ) );

		}
		else
			return view( 'errors/cli/error_404' );
	}

}
