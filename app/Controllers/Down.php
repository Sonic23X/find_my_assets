<?php

namespace App\Controllers;

class Down extends BaseController
{

  //variables de la clase
  protected $session;
  protected $activoModel;
  protected $db;

  function __construct()
  {
    $this->session = \Config\Services::session( );
    $this->activoModel = model( 'App\Models\ActivoModel' );
    $this->db = \Config\Database::connect();
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

}
