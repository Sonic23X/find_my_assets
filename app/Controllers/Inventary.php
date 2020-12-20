<?php

namespace App\Controllers;

class Inventary extends BaseController
{
  //variables de la clase
  protected $session;
	protected $userModel;
	protected $tipoModel;
	protected $empresaModel;
	protected $draftModel;
  protected $activoModel;
	protected $sucursalModel;
  protected $depreciacionModel;
  protected $ccModel;
  protected $serieModel;
  protected $areaModel;
  protected $db;

  function __construct()
  {
    $this->session = \Config\Services::session( );
		$this->tipoModel = model( 'App\Models\TipoModel' );
		$this->userModel = model( 'App\Models\UserModel' );
		$this->empresaModel = model( 'App\Models\EmpresaModel' );
    $this->activoModel = model( 'App\Models\ActivoModel' );
		$this->draftModel = model( 'App\Models\DraftModel' );
		$this->sucursalModel = model( 'App\Models\SucursalModel' );
    $this->depreciacionModel = model( 'App\Models\DepreciacionModel' );
    $this->ccModel = model( 'App\Models\CCModel' );
    $this->serieModel = model( 'App\Models\SerieModel' );
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

			//content - inventario
			echo view( 'backoffice/sections/inventary' );

			//Scripts y librerias
			$footer = array( 'js' => 'inventario', 'dashboard' => false, 'carga' => false, 'inv' => true, 'bajas' => false );
			echo view( 'backoffice/common/footer', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
			return view( 'functions/redirect', $data );
		}
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function SearchItemList( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $builder = $this->db->table( 'draft' );
        $builder->select( 'draft.Id, draft.Nom_Activo, draft.ID_Activo, draft.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = draft.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = draft.User_Inventario' );
        $builder->where( 'draft.status', 'nuevo' );
        $builder->where( 'draft.TS_Delete', null );
        $builder->where( 'draft.ID_Company', $this->session->empresa );
        $activos = $builder->get( );

				if ( $activos == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
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
  function SearchItemInfo( $id )
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
					'Des_Activo', 'Fec_InicioDepre', 'ID_MetDepre',
			    'Vida_Activo', 'GPS', 'Fec_Inventario', 'User_Inventario', 'Comentarios',
					'User_Create', 'User_Update', '	User_Delete',
			    'TS_Create', 'TS_Update', 'TS_Delete'
				];

        $activo = $this->draftModel->where( 'Id', $id )
                                   ->where( 'ID_Company', $this->session->empresa )
																	 ->select( $campos )
                                   ->first( );

				if ( $activo == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
					return;
        }
        
        //buscamos si hay registro previo de numero de serie
        $serie = $this->serieModel->where( 'id_draft', $id )->orderBy( 'id', 'desc' )->first( );

        $tooltip = null;
        if ( $serie == null ) 
          $tooltip = 'Sin cambios';
        else
          $tooltip = 'Campo actualizado, valor anterior: ' . $serie[ 'num_serie' ];

        echo json_encode( array( 'status' => 200, 'activo' => $activo, 'tooltip' => $tooltip ) );

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
  function SearcItemDetails( $id )
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
					'Des_Activo', 'Fec_InicioDepre', 'ID_MetDepre',
			    'Vida_Activo', 'GPS', 'Fec_Inventario', 'User_Inventario', 'Comentarios',
					'User_Create', 'User_Update', '	User_Delete',
			    'TS_Create', 'TS_Update', 'TS_Delete'
				];

        $activo = $this->draftModel->where( 'ID_Activo', $id )
																	 ->select( $campos )
                                   ->first( );

				if ( $activo == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
					return;
				}

				$user = $this->userModel->where( 'id_usuario', $activo[ 'User_Inventario' ] )->first( );

				$tipo = $this->tipoModel->where( 'id', $activo[ 'ID_Tipo' ] )->first( );

        $similarData =
        [
          'ID_Sucursal' => $activo[ 'ID_Sucursal' ],
          'ID_Area' => $activo[ 'ID_Area' ],
          'ID_CC' => $activo[ 'ID_CC' ],
          'NSerie_Activo' => $activo[ 'NSerie_Activo' ],
          'ID_Tipo' => $activo[ 'ID_Tipo' ],
          'User_Inventario' => $activo[ 'User_Inventario' ],
        ];

        $builder = $this->db->table( 'activos' );

        $builder->select( 'activos.Id, activos.Nom_Activo, activos.ID_Activo, activos.ID_Sucursal, activos.ID_Area, activos.User_Inventario,
                          activos.ID_CC, activos.Fec_Compra, activos.Fec_Expira, activos.NSerie_Activo, activos.status,
                          activos.ID_Tipo, activos.ID_MetDepre, activos.Vida_Activo, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
        $builder->where( 'activos.status !=', 'eliminado' );
        $builder->where( 'activos.ID_Activo !=', $id );
        $builder->where( 'activos.ID_Company', $this->session->empresa );
        $builder->orlike( $similarData );
        $activos = $builder->get( );

        $num = 0;
        foreach ( $activos->getResult( ) as $row )
        {
          $porcentaje = 0;
          if ( $activo[ 'NSerie_Activo' ] != null || $activo[ 'NSerie_Activo' ] != '' )
          {
            if ( $row->NSerie_Activo == $activo[ 'NSerie_Activo' ] )
            {
              $porcentaje += 60;
            }
            if ( $row->ID_Sucursal == $activo[ 'ID_Sucursal' ] )
            {
              $porcentaje += 5;
            }
            if ( $row->ID_Tipo == $activo[ 'ID_Tipo' ] )
            {
              $porcentaje += 15;
            }
            if ( $row->ID_CC == $activo[ 'ID_CC' ] )
            {
              $porcentaje += 5;
            }
            if ( $row->User_Inventario == $activo[ 'User_Inventario' ] )
            {
              $porcentaje += 15;
            }
          }
          else
          {
            if ( $row->ID_Sucursal == $activo[ 'ID_Sucursal' ] )
            {
              $porcentaje += 15;
            }
            if ( $row->ID_Area == $activo[ 'ID_Area' ] )
            {
              $porcentaje += 5;
            }
            if ( $row->ID_Tipo == $activo[ 'ID_Tipo' ] )
            {
              $porcentaje += 30;
            }
            if ( $row->ID_CC == $activo[ 'ID_CC' ] )
            {
              $porcentaje += 20;
            }
            if ( $row->User_Inventario == $activo[ 'User_Inventario' ] )
            {
              $porcentaje += 30;
            }
          }

          if ( $row->status != 'eliminado' && $porcentaje > 40 )
          {
            $num++;
          }
        }

        if ( $num > 0 )
          echo json_encode( array( 'status' => 200, 'activo' => $activo, 'user' => $user, 'tipo' => $tipo, 'concilar' => 1 ) );
        else
          echo json_encode( array( 'status' => 200, 'activo' => $activo, 'user' => $user, 'tipo' => $tipo, 'concilar' => 0 ) );
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
  function SearcItemBuyDetails( $id )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
				$campos =
				[
					'ID_Activo', 'Fec_Compra', 'Pre_Compra', 'Fec_Expira',
					'Des_Activo', 'Fec_InicioDepre', 'ID_MetDepre',
			    'Vida_Activo', 'contabilizar'
				];

        $activo = $this->draftModel->where( 'ID_Activo', $id )
																	 ->select( $campos )
                                   ->first( );

				if ( $activo == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
					return;
				}

        echo json_encode( array( 'status' => 200, 'activo' => $activo ) );

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
  function SearchItemsConciliar( $id )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $activo = $this->draftModel->where( 'ID_Activo', $id )->first( );
        $similarData = null;
        $serie = true;

        if ( $activo[ 'NSerie_Activo' ] == '' || $activo[ 'NSerie_Activo' ] == null )
        {
          $similarData =
          [
            'ID_Sucursal' => $activo[ 'ID_Sucursal' ],
            'ID_Area' => $activo[ 'ID_Area' ],
            'ID_CC' => $activo[ 'ID_CC' ],
            'NSerie_Activo' => $activo[ 'NSerie_Activo' ],
            'ID_Tipo' => $activo[ 'ID_Tipo' ],
            'User_Inventario' => $activo[ 'User_Inventario' ],
          ];
          $serie = false;
        }
        else
        {
          $similarData =
          [
            'Nom_Activo' => $activo[ 'Nom_Activo' ],
            'ID_Sucursal' => $activo[ 'ID_Sucursal' ],
            'ID_Area' => $activo[ 'ID_Area' ],
            'ID_CC' => $activo[ 'ID_CC' ],
            'NSerie_Activo' => $activo[ 'NSerie_Activo' ],
            'ID_Tipo' => $activo[ 'ID_Tipo' ],
            'User_Inventario' => $activo[ 'User_Inventario' ],
          ];
        }

        $builder = $this->db->table( 'activos' );

        $builder->select( 'activos.Id, activos.Nom_Activo, activos.ID_Activo, activos.ID_Sucursal, activos.ID_Area, activos.User_Inventario,
                          activos.ID_CC, activos.Fec_Compra, activos.Fec_Expira, activos.NSerie_Activo, activos.status,
                          activos.ID_Tipo, activos.ID_MetDepre, activos.Vida_Activo, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
        $builder->where( 'activos.status !=', 'eliminado' );
        $builder->where( 'activos.ID_Activo !=', $id );
        $builder->where( 'activos.ID_Company', $this->session->empresa );
        $builder->orlike( $similarData );
        $activos = $builder->get( );

        $data = [ ];
        foreach ( $activos->getResult( ) as $row )
        {
          $porcentaje = 0;

          if ( $serie )
          {
            if ( $row->NSerie_Activo == $activo[ 'NSerie_Activo' ] )
            {
              $porcentaje += 60;
            }
            if ( $row->ID_Sucursal == $activo[ 'ID_Sucursal' ] )
            {
              $porcentaje += 5;
            }
            if ( $row->ID_Tipo == $activo[ 'ID_Tipo' ] )
            {
              $porcentaje += 15;
            }
            if ( $row->ID_CC == $activo[ 'ID_CC' ] )
            {
              $porcentaje += 5;
            }
            if ( $row->User_Inventario == $activo[ 'User_Inventario' ] )
            {
              $porcentaje += 15;
            }
          }
          else
          {
            if ( $row->ID_Sucursal == $activo[ 'ID_Sucursal' ] )
            {
              $porcentaje += 15;
            }
            if ( $row->ID_Area == $activo[ 'ID_Area' ] )
            {
              $porcentaje += 5;
            }
            if ( $row->ID_Tipo == $activo[ 'ID_Tipo' ] )
            {
              $porcentaje += 30;
            }
            if ( $row->ID_CC == $activo[ 'ID_CC' ] )
            {
              $porcentaje += 20;
            }
            if ( $row->User_Inventario == $activo[ 'User_Inventario' ] )
            {
              $porcentaje += 30;
            }
          }

          $json =
          [
            'id' => $row->Id,
            'tipo' => $row->Desc,
            'nombre' => $row->Nom_Activo,
            'usuario' => $row->nombre . $row->apellidos,
            'id_activo' => $row->ID_Activo,
            'porcentaje' => $porcentaje,
          ];

          if ( $row->status != 'eliminado' && $porcentaje > 50 )
          {
            array_push( $data, $json );
          }
        }

        echo json_encode( array( 'status' => 200, 'activos' => $data ) );
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
  function SearchDataConciliar( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $builder = $this->db->table( 'draft' );
        $builder->select( 'draft.Id, draft.NSerie_Activo, draft.ID_Activo, empresas.nombre as empresa, tipos.Desc, usuarios.nombre, usuarios.apellidos, cc.Desc as cc' );
        $builder->join( 'tipos', 'tipos.id = draft.ID_Tipo' );
        $builder->join( 'empresas', 'empresas.id_empresa = draft.ID_Company' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = draft.User_Inventario' );
        $builder->join( 'cc', 'cc.ID_CC = draft.ID_CC' );
        $builder->where( 'draft.ID_Activo', $this->request->getVar( 'actual' ) );
        $act = $builder->get( )->getResult( );

        $builder = $this->db->table( 'activos' );
        $builder->select( 'activos.Id, activos.NSerie_Activo, activos.ID_Activo, empresas.nombre as empresa, tipos.Desc, usuarios.nombre, usuarios.apellidos, cc.Desc as cc' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'empresas', 'empresas.id_empresa = activos.ID_Company' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
        $builder->join( 'cc', 'cc.ID_CC = activos.ID_CC' );
        $builder->where( 'activos.Id', $this->request->getVar( 'old' ) );
        $old = $builder->get( )->getResult( );

        echo json_encode( array( 'status' => 200, 'actual' => $act, 'old' => $old ) );
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
  function SearchDataConciliarConfirm( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $builder = $this->db->table( 'draft' );
        $builder->select( 'draft.Id, draft.Nom_Activo, draft.ID_Activo, draft.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = draft.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = draft.User_Inventario' );
        $builder->where( 'draft.ID_Activo', $this->request->getVar( 'actual' ) );
        $act = $builder->get( )->getResult( );

        $builder = $this->db->table( 'activos' );
        $builder->select( 'activos.Id, activos.Nom_Activo, activos.ID_Activo, activos.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
        $builder->where( 'activos.Id', $this->request->getVar( 'old' ) );
        $old = $builder->get( )->getResult( );

        echo json_encode( array( 'status' => 200, 'actual' => $act, 'old' => $old ) );
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
  function Conciliar(  )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {

        $draft = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'actual' ) )->first( );
        $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'actual' ) )->set( [ 'TS_Delete' => date( 'Y/n/j' ), 'status' => 'conciliado' ] )->update( );

        $activo = $this->activoModel->where( 'Id', $this->request->getVar( 'old' ) )->select( 'NSerie_Activo' )->first( );

        $builder = $this->db->table( 'activos' );
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
          'Fec_Compra' => $draft[ 'Fec_Compra' ],
          'Img_FacCompra' => $draft[ 'Img_FacCompra' ],
          'Pre_Compra' => $draft[ 'Pre_Compra' ],
          'Fec_Expira' => $draft[ 'Fec_Expira' ],
          'Img_Garantia' => $draft[ 'Img_Garantia' ],
          'NSerie_Activo' => $draft[ 'NSerie_Activo' ],
          'ID_Tipo' => $draft[ 'ID_Tipo' ],
          'Des_Activo' => $draft[ 'Des_Activo' ],
          'Fec_InicioDepre' => $draft[ 'Fec_InicioDepre' ],
          'ID_MetDepre' => $draft[ 'ID_MetDepre' ],
          'Vida_Activo' => $draft[ 'Vida_Activo' ],
          'GPS' => $draft[ 'GPS' ],
          'Fec_Inventario' => $draft[ 'Fec_Inventario' ],
          'User_Inventario' => $draft[ 'User_Inventario' ],
          'Comentarios' => $draft[ 'Comentarios' ],
          'User_Create' => $draft[ 'User_Create' ],
          'User_Update' => $draft[ 'User_Update' ],
          'User_Delete' => $draft[ 'User_Delete' ],
          'TS_Create' => $draft[ 'TS_Create' ],
          'TS_Update' => $draft[ 'TS_Update' ],
          'TS_Delete' => $draft[ 'TS_Delete' ],
        ];

        $builder->where( 'Id', $this->request->getVar( 'old' ) );
        $builder->update( $activoData );

        //creamos registro del cambio de numero de serie
        $this->serieModel->insert( [ 'id_activo' => $this->request->getVar( 'old' ), 'id_draft' => null, 'num_serie' => $activo[ 'NSerie_Activo' ] ] ); 

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

  //método que funciona exclusivamente con AJAX - JQUERY
  function SaveDraftBuyDetails( )
  {
    if ( $this->request->isAJAX( ) )
    {

      try
      {
        $update =
        [
          'Fec_Compra' => $this->request->getVar( 'fecha_compra' ),
          'Pre_Compra' => $this->request->getVar( 'clp' ),
          'Fec_Expira' => $this->request->getVar( 'fecha_garantia' ),
          'ID_MetDepre' => $this->request->getVar( 'metodo' ),
          'Fec_InicioDepre' => $this->request->getVar( 'fecha_metodo' ),
          'Vida_Activo' => $this->request->getVar( 'vida_util' ),
          'contabilizar' => $this->request->getVar( 'contabilizar' ),
          'TS_Update' => date( 'Y/n/j' ),
          'status' => 'editado',
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
  function draftToActivo( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $draft = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->first( );

        $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( [ 'TS_Delete' => date( 'Y/n/j' ), 'status' => 'activado' ] )->update( );

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
          'Fec_Compra' => $draft[ 'Fec_Compra' ],
          'Img_FacCompra' => $draft[ 'Img_FacCompra' ],
          'Pre_Compra' => $draft[ 'Pre_Compra' ],
          'Fec_Expira' => $draft[ 'Fec_Expira' ],
          'Img_Garantia' => $draft[ 'Img_Garantia' ],
          'NSerie_Activo' => $draft[ 'NSerie_Activo' ],
          'ID_Tipo' => $draft[ 'ID_Tipo' ],
          'Des_Activo' => $draft[ 'Des_Activo' ],
          'Fec_InicioDepre' => $draft[ 'Fec_InicioDepre' ],
          'ID_MetDepre' => $draft[ 'ID_MetDepre' ],
          'Vida_Activo' => $draft[ 'Vida_Activo' ],
          'GPS' => $draft[ 'GPS' ],
          'Fec_Inventario' => $draft[ 'Fec_Inventario' ],
          'User_Inventario' => $draft[ 'User_Inventario' ],
          'Comentarios' => $draft[ 'Comentarios' ],
          'Ima_Activo0' => $draft[ 'Ima_Activo0' ],
          'Ima_ActivoLeft' => $draft[ 'Ima_ActivoLeft' ],
          'Ima_ActivoRight' => $draft[ 'Ima_ActivoRight' ],
          'Ima_ActivoFront' => $draft[ 'Ima_ActivoFront' ],
          'Ima_ActivoBack' => $draft[ 'Ima_ActivoBack' ],
          'User_Create' => $draft[ 'User_Create' ],
          'User_Update' => $draft[ 'User_Update' ],
          'User_Delete' => $draft[ 'User_Delete' ],
          'TS_Create' => $draft[ 'TS_Create' ],
          'TS_Update' => $draft[ 'TS_Update' ],
          'TS_Delete' => $draft[ 'TS_Delete' ],
        ];

        if ( $this->activoModel->insert( $activoData ) )
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
  function draftDelete( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( [ 'TS_Delete' => date( 'Y/n/j' ), 'status' => 'eliminado' ] )->update( ) )
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
  function GetDataForm( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $usuarios = $this->userModel->where( 'id_empresa', $this->session->empresa )->findAll( );
        $depreciaciones = $this->depreciacionModel->findAll( );

        $SQL = "SELECT empresas.* FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
        $builder = $this->db->query( $SQL );
        $empresas = $builder->getResult( );

        $SQL = "SELECT * FROM sucursales WHERE ID_Empresa IN ( SELECT id_empresa FROM user_empresa WHERE id_usuario = ". $this->session->id .")";
        $builder = $this->db->query( $SQL );
        $sucursales = $builder->getResult( );

        $SQL = "SELECT * FROM areas WHERE id_empresa IN ( SELECT id_empresa FROM user_empresa WHERE id_usuario = ". $this->session->id .")";
        $builder = $this->db->query( $SQL );
        $areas = $builder->getResult( );

        $SQL = "SELECT * FROM cc WHERE id_empresa IN ( SELECT id_empresa FROM user_empresa WHERE id_usuario = ". $this->session->id .")";
        $builder = $this->db->query( $SQL );
        $cc = $builder->getResult( );

        $SQL = "SELECT * FROM tipos WHERE ID_Empresa IN ( SELECT id_empresa FROM user_empresa WHERE id_usuario = ". $this->session->id .")";
        $builder = $this->db->query( $SQL );
        $tipos = $builder->getResult( );
        
        if ( $tipos )
          $json = array( 'status' => 200, 'types' => $tipos, 'users' => $usuarios,
                         'empresas' => $empresas, 'sucursales' => $sucursales,
                         'depreciacion' => $depreciaciones,
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
	function SetFactura( )
	{
		if ( $this->request->isAJAX( ) )
    {
      try
      {
				$update = [ ];

				$file = $this->request->getFile( 'file' );

				$dataFile = file_get_contents( $file->getTempName( )  );

        $update =
        [
          'Img_FacCompra' => $dataFile,
        ];

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'activo' ) )->set( $update )->update( ) )
        {
          echo json_encode( array( 'status' => 200, 'msg' => '¡Factura cargada con exito!' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al cargar la factura del activo. Intente más tarde' ) );

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
	function SetGarantia( )
	{
		if ( $this->request->isAJAX( ) )
    {
      try
      {
				$update = [ ];

				$file = $this->request->getFile( 'file' );

				$dataFile = file_get_contents( $file->getTempName( )  );

        $update =
        [
          'Img_Garantia' => $dataFile,
        ];

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'activo' ) )->set( $update )->update( ) )
        {
          echo json_encode( array( 'status' => 200, 'msg' => '¡Garantia cargada con exito!' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al cargar la garantia del activo. Intente más tarde' ) );

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
  function ProcessList( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $builder = $this->db->table( 'draft' );
        $builder->select( 'draft.Id, draft.Nom_Activo, draft.ID_Activo, draft.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = draft.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = draft.User_Inventario' );
        $builder->where( 'draft.TS_Delete', null );
        $builder->where( 'draft.ID_Company', $this->session->empresa );
        $activos = $builder->where( 'draft.status', 'editado' )->get( );

        $builder->select( 'draft.Id, draft.Nom_Activo, draft.ID_Activo, draft.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = draft.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = draft.User_Inventario' );
        $builder->where( 'draft.TS_Delete', null );
        $builder->where( 'draft.ID_Company', $this->session->empresa );
        $nuevos = $builder->where( 'draft.status', 'nuevo' )->get( );

				if ( $activos == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activos no encontrado' ) );
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

        $data2 = [ ];
        $num2 = 0;
        foreach ( $nuevos->getResult( ) as $row )
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

          array_push( $data2, $json );
          $num2++;
        }

        echo json_encode( array( 'status' => 200, 'activos' => $data, 'nuevos' => $data2, 'number' => $num, 'number2' => $num2 ) );
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
  function SearchInventaryList( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $builder = $this->db->table( 'activos' );
        $builder->select( 'activos.Id, activos.Nom_Activo, activos.ID_Activo, activos.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
        $builder->where( 'activos.ID_Company', $this->session->empresa );
        $builder->where( 'activos.TS_Delete', null );
        $activos = $builder->get( );

				if ( $activos == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
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
  function SearchInventaryListFilter( )
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
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
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
  function SearchActiveInfo( $id )
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
          'Des_Activo', 'Fec_InicioDepre', 'ID_MetDepre',
          'Vida_Activo', 'GPS', 'Fec_Inventario', 'User_Inventario', 'Comentarios',
          'User_Create', 'User_Update', '	User_Delete',
          'TS_Create', 'TS_Update', 'TS_Delete'
        ];

        $activo = $this->activoModel->where( 'Id', $id )
                                   ->select( $campos )
                                   ->first( );

        
        if ( $activo == null )
        {
          echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
          return;
        }

        //buscamos si hay registro previo de numero de serie
        $serie = $this->serieModel->where( 'id_activo', $id )->orderBy( 'id', 'desc' )->first( );

        $tooltip = null;
        if ( $serie == null ) 
          $tooltip = 'Sin cambios';
        else
          $tooltip = 'Campo actualizado, valor anterior: ' . $serie[ 'num_serie' ];

        echo json_encode( array( 'status' => 200, 'activo' => $activo, 'tooltip' => $tooltip ) );

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
  function deleteNews( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $request = $this->request->getVar( 'items' );

        foreach ( $request as $item )
        {
          $this->draftModel->where( 'Id', $item )->delete( );
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
