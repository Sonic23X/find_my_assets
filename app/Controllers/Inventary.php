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
    $this->db = \Config\Database::connect();
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

        echo json_encode( array( 'status' => 200, 'activo' => $activo, 'user' => $user, 'tipo' => $tipo ) );

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
			    'Vida_Activo', 'Img_Garantia', 'Img_FacCompra', 'contabilizar'
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

				$similarData =
        [
          'Nom_Activo' => $activo[ 'Nom_Activo' ],
          'ID_Sucursal' => $activo[ 'ID_Sucursal' ],
          'ID_Area' => $activo[ 'ID_Area' ],
          'ID_CC' => $activo[ 'ID_CC' ],
          'Fec_Compra' => $activo[ 'Fec_Compra' ],
          'Fec_Expira' => $activo[ 'Fec_Expira' ],
          'NSerie_Activo' => $activo[ 'NSerie_Activo' ],
          'ID_Tipo' => $activo[ 'ID_Tipo' ],
          'ID_MetDepre' => $activo[ 'ID_MetDepre' ],
          'Vida_Activo' => $activo[ 'Vida_Activo' ],
        ];

        $builder = $this->db->table( 'activos' );

        $builder->select( 'activos.Id, activos.Nom_Activo, activos.ID_Activo, activos.ID_Sucursal, activos.ID_Area,
                          activos.ID_CC, activos.Fec_Compra, activos.Fec_Expira, activos.NSerie_Activo,
                          activos.ID_Tipo, activos.ID_MetDepre, activos.Vida_Activo, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = activos.User_Inventario' );
        $builder->where( 'activos.ID_Activo !=', $id );
        $builder->orlike( $similarData );
        $builder->where( 'activos.TS_Delete', null );
        $activos = $builder->get( );

        $data = [ ];
        foreach ( $activos->getResult( ) as $row )
        {
          $porcentaje = 0;
          if ( $row->Nom_Activo == $activo[ 'Nom_Activo' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->ID_Sucursal == $activo[ 'ID_Sucursal' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->ID_Area == $activo[ 'ID_Area' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->ID_CC == $activo[ 'ID_CC' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->Fec_Compra == $activo[ 'Fec_Compra' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->Fec_Expira == $activo[ 'Fec_Expira' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->NSerie_Activo == $activo[ 'NSerie_Activo' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->ID_Tipo == $activo[ 'ID_Tipo' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->ID_MetDepre == $activo[ 'ID_MetDepre' ] )
          {
            $porcentaje += 10;
          }
          if ( $row->Vida_Activo == $activo[ 'Vida_Activo' ] )
          {
            $porcentaje += 10;
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

          array_push( $data, $json );
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
        $builder->select( 'draft.Id, draft.NSerie_Activo, draft.ID_Activo, empresas.nombre as empresa, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = draft.ID_Tipo' );
        $builder->join( 'empresas', 'empresas.id_empresa = draft.ID_Company' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = draft.User_Inventario' );
        $builder->where( 'draft.ID_Activo', $this->request->getVar( 'actual' ) );
        $act = $builder->get( )->getResult( );

        $builder = $this->db->table( 'activos' );
        $builder->select( 'activos.Id, activos.NSerie_Activo, activos.ID_Activo, empresas.nombre as empresa, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = activos.ID_Tipo' );
        $builder->join( 'empresas', 'empresas.id_empresa = activos.ID_Company' );
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
        $builder->replace( $activoData );

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
        $tipos = $this->tipoModel->findAll( );
        $usuarios = $this->userModel->findAll( );
        $empresas = $this->empresaModel->findAll( );
        $sucursales = $this->sucursalModel->findAll( );
        $depreciaciones = $this->depreciacionModel->findAll( );

        if ( $tipos )
          $json = array( 'status' => 200, 'types' => $tipos, 'users' => $usuarios,
                         'empresas' => $empresas, 'sucursales' => $sucursales,
                         'depreciacion' => $depreciaciones );
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
        $activos = $builder->where( 'draft.status', 'editado' )->get( );

        $builder->select( 'draft.Id, draft.Nom_Activo, draft.ID_Activo, draft.TS_Create, tipos.Desc, usuarios.nombre, usuarios.apellidos' );
        $builder->join( 'tipos', 'tipos.id = draft.ID_Tipo' );
        $builder->join( 'usuarios', 'usuarios.id_usuario = draft.User_Inventario' );
        $builder->where( 'draft.TS_Delete', null );
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

}
