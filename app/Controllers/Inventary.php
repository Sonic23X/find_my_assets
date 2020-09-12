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
	protected $sucursalModel;

  function __construct()
  {
    $this->session = \Config\Services::session( );
		$this->tipoModel = model( 'App\Models\TipoModel' );
		$this->userModel = model( 'App\Models\UserModel' );
		$this->empresaModel = model( 'App\Models\EmpresaModel' );
		$this->draftModel = model( 'App\Models\DraftModel' );
		$this->sucursalModel = model( 'App\Models\SucursalModel' );
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function SearchItemList( )
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

        $activos = $this->draftModel->select( $campos )->get( );

				if ( $activo == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
					return;
				}

				print_r( $activos );

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
  function SearchItemInfo( )
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

        $activo = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
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

        if ( $tipos )
          $json = array( 'status' => 200, 'types' => $tipos, 'users' => $usuarios,
                         'empresas' => $empresas, 'sucursales' => $sucursales );
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
}
