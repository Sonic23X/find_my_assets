<?php

namespace App\Controllers;

use App\Libraries\PHPMailerLib;

class Dashboard extends BaseController
{

	//variables de la clase
	protected $session;
	protected $tipoModel;
	protected $activoModel;
	protected $db;

	function __construct()
	{
		$this->session = \Config\Services::session( );
		$this->tipoModel = model( 'App\Models\TipoModel' );
		$this->activoModel = model( 'App\Models\ActivoModel' );
		$this->db = \Config\Database::connect();
	}

	function Index( )
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

			//content - inicio
			echo view( 'backoffice/sections/start' );

			//Scripts y librerias
			$footer = array( 'js' => 'dash', 'dashboard' => true, 'carga' => false, 'inv' => false, 'bajas' => false );
			echo view( 'backoffice/common/footer', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
			return view( 'functions/redirect', $data );
		}
	}

	function getData( )
	{
		$tipos = $this->tipoModel->where( 'ID_Empresa', $this->session->empresa )->findAll( );
		
		$table1 = [ ];
		$labels = [ ];
		$values = [ ];
		foreach( $tipos as $tipo )
		{
			$activos = $this->activoModel->where( 'ID_Tipo', $tipo['id'] )
										 ->where( 'TS_Delete', null )
										 ->select( 'Pre_Compra' )
										 ->findAll( );
			$monto = 0;
			$num = 0;
			foreach( $activos as $activo )
			{
				$monto = $monto + $activo[ 'Pre_Compra' ];
				$num++;
			}

			array_push( $table1, [ 'tipo' => $tipo[ 'Desc' ], 'monto' => $monto ] );
			array_push( $labels, $tipo[ 'Desc' ] );
			array_push( $values, $num );
		}

		$bajas = $this->activoModel->where( 'ID_Company', $this->session->empresa )->where( 'TS_Delete !=', null )
																				   ->select( 'TS_Delete, Nom_Activo, Pre_Compra' )
																				   ->orderBy('TS_Create', 'desc')
																				   ->findAll( 5 );

		$altas = $this->activoModel->where( 'ID_Company', $this->session->empresa )->where( 'TS_Delete', null )
																				   ->select( 'TS_Create, Nom_Activo, , Pre_Compra' )
																				   ->orderBy('TS_Create', 'desc')
																				   ->findAll( 5 );

		$builder = $this->db->table( 'activos' );
        $builder->select( 'activos.Nom_Activo, activos.GPS, tipos.Desc, usuarios.nombre' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
		$builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
		$builder->where( 'activos.ID_Company', $this->session->empresa );
        $builder->where( 'activos.TS_Delete', null );
        $points = $builder->get( )->getResult( );

		echo json_encode( array( 'status' => 200, 'montos' => $table1, 'graficaLabels' => $labels, 'graficaValues' => $values, 'bajas' => $bajas, 'altas' => $altas, 'points' => $points ) );
	}

}
