<?php

namespace App\Controllers;

class Down extends BaseController
{

  //variables de la clase
  protected $session;
  protected $sucursalModel;
  protected $activoModel;
  protected $areaModel;
  protected $db;

  function __construct()
  {
    $this->session = \Config\Services::session( );
    $this->sucursalModel = model( 'App\Models\SucursalModel' );
    $this->activoModel = model( 'App\Models\ActivoModel' );
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

			//content - bajas
			echo view( 'backoffice/sections/down' );

			//Scripts y librerias
			$footer = array( 'js' => 'bajas', 'dashboard' => false, 'carga' => false, 'inv' => false, 'bajas' => true );
			echo view( 'backoffice/common/footer', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
			return view( 'functions/redirect', $data );
		}
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function SearchList( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $builder = $this->db->table( 'activos' );
        $builder->select( 'activos.Id, activos.Nom_Activo, activos.ID_Activo, activos.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
        $builder->where( 'activos.TS_Delete', null );
        $builder->where( 'activos.ID_Company', $this->session->empresa );

        $activos = $builder->get( );

        if ( $activos == null )
        {
          echo json_encode( array( 'status' => 400, 'msg' => 'Activos no encontrados' ) );
          return;
        }

        $data = [ ];
        $num = 0;
        foreach ( $activos->getResult( ) as $row )
        {
          $fecha = explode( ' ', $row->TS_Create );

          $json =
          [
            'id' => $row->Id,
            'tipo' => $row->Desc,
            'nombre' => $row->Nom_Activo,
            'usuario' => $row->nombre . $row->apellidos,
            'fecha' => $fecha[ 0 ],
            'id_activo' => $row->ID_Activo,
          ];

          array_push( $data, $json );
          $num++;
        }

        echo json_encode( array( 'status' => 200, 'activos' => $data, 'number' => $num ) );
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
  function SearchListFilter( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $builder = $this->db->table( 'activos' );
        $builder->select( 'activos.Id, activos.Nom_Activo, activos.ID_Activo, activos.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
        $builder->where( 'activos.TS_Delete', null );

        if ( $this->request->getVar( 'tipo' ) != null )
        {
          $builder->where( 'activos.ID_Tipo', $this->request->getVar( 'tipo' ) );
        }
        if ( $this->request->getVar( 'cc' ) != null )
        {
          $builder->where( 'activos.ID_CC', $this->request->getVar( 'cc' ) );
        }
        if ( $this->request->getVar( 'empresa' ) != null )
        {
          $builder->where( 'activos.ID_Company', $this->request->getVar( 'empresa' ) );
        }
        if ( $this->request->getVar( 'sucursal' ) != null )
        {
          $builder->where( 'activos.ID_Sucursal', $this->request->getVar( 'sucursal' ) );
        }
        if ( $this->request->getVar( 'area' ) != null )
        {
          $builder->where( 'activos.ID_Area', $this->request->getVar( 'area' ) );
        }

        $activos = $builder->get( );

        if ( $activos == null )
        {
          echo json_encode( array( 'status' => 400, 'msg' => 'Activos no encontrados' ) );
          return;
        }

        $data = [ ];
        $num = 0;
        foreach ( $activos->getResult( ) as $row )
        {
          $fecha = explode( ' ', $row->TS_Create );

          $json =
          [
            'id' => $row->Id,
            'tipo' => $row->Desc,
            'nombre' => $row->Nom_Activo,
            'usuario' => $row->nombre . $row->apellidos,
            'fecha' => $fecha[ 0 ],
            'id_activo' => $row->ID_Activo,
          ];

          array_push( $data, $json );
          $num++;
        }

        echo json_encode( array( 'status' => 200, 'activos' => $data, 'number' => $num ) );
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
  function activosDelete( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $request = $this->request->getVar( 'items' );
        $items = explode( ',', $request );

        foreach ( $items as $item )
        {
          $this->activoModel->where( 'Id', $item )
                            ->set( [ 'TS_Delete' => date( 'Y/n/j' ),
                                      'status' => 'eliminado',
                                      'deleteReason' => $this->request->getVar( 'motivo' ),
                                      'deleteComent' => $this->request->getVar( 'desc' ) ] )
                            ->update( );
        }

        echo json_encode( array( 'status' => 200 ) );
      }
      catch (\Exception $e)
      {
        echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );
  }

  public function UpdateSucursal( )
	{
		if ( $this->request->isAJAX( ) )
		{
      $sucursales = null;
      $areas = null;
      if ( $this->request->getVar( 'empresa' ) != null ) 
      {
        $sucursales = $this->sucursalModel->where( 'ID_Empresa', $this->request->getVar( 'empresa' ))->findAll( );
			  $areas = $this->areaModel->where( 'id_empresa', $this->request->getVar( 'empresa' ))->findAll( );
      }
      else
      {
        $SQL = "SELECT * FROM sucursales WHERE ID_Empresa IN ( SELECT id_empresa FROM user_empresa WHERE id_usuario = ". $this->session->id .")";
        $builderSucursales = $this->db->query( $SQL );
        $sucursales = $builderSucursales->getResult( );

        $SQL = "SELECT * FROM areas WHERE id_empresa IN ( SELECT id_empresa FROM user_empresa WHERE id_usuario = ". $this->session->id .")";
        $builderAreas = $this->db->query( $SQL );
        $areas = $builderAreas->getResult( );
      }
			echo json_encode( array( 'status' => 200, 'sucursales' => $sucursales, 'areas' => $areas ) );
		}
		else
			return view( 'errors/cli/error_404' );
	}

}
