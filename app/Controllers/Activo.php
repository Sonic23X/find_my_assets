<?php

namespace App\Controllers;

class Activo extends BaseController
{

	protected $session;
  protected $activoModel;
	protected $userModel;
	protected $tipoModel;
	protected $empresaModel;
	protected $draftModel;

  function __construct()
  {
    $this->session = \Config\Services::session( );
    $this->activoModel = model( 'App\Models\ActivoModel' );
		$this->tipoModel = model( 'App\Models\TipoModel' );
		$this->userModel = model( 'App\Models\UserModel' );
		$this->empresaModel = model( 'App\Models\EmpresaModel' );
		$this->draftModel = model( 'App\Models\DraftModel' );
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

        if ( $tipos )
          $json = array( 'status' => 200, 'types' => $tipos, 'users' => $usuarios, 'empresas' => $empresas );
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
        $activo = $this->activoModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
                                    ->first( );

				$user = $this->userModel->where( 'id_usuario', $activo[ 'User_Inventario' ] )->first( );

				$tipo = $this->tipoModel->where( 'id', $activo[ 'ID_Tipo' ] )->first( );

        if ( $activo )
          $json = array( 'status' => 200, 'activo' => $activo, 'user' => $user, 'tipo' => $tipo );
        else
          $json = array( 'status' => 401, 'msg' => 'El activo no se ha encontrado en el sistema' );

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
          'ID_Tipo' => $this->request->getVar( 'tipo' ),
          'Des_Activo' => $this->request->getVar( 'descripcion' ),
          'NSerie_Activo' => $this->request->getVar( 'no_serie' ),
          'ID_CC' => $this->request->getVar( 'centro_costo' ),
          'User_Inventario' => $this->request->getVar( 'asignacion' ),
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
	function GetImages( )
	{
		if ( $this->request->isAJAX( ) )
    {
      try
      {
				$activo = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
																		->select( 'Ima_ActivoLeft', 'Ima_ActivoRight', 'Ima_ActivoFront' )
                                    ->first( );

        if ( $activo )
					echo json_encode( array( 'status' => 200, 'activo' => $activo ) );
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al buscar las imagenes del activo. Intente más tarde' ) );
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
				// TODO: Conseguir imagen anterior, y si existe, borrarla
				// TODO: Conseguir el id de la empresa
				$name_photo = '';
				$update = [ ];

				$activo = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'activo' ) )
													->first( );

				switch ( $this->request->getVar( 'type' ) )
				{
					case 'front':
						$name_photo = 'Ima_ActivoFront.jpg';
						$update =
						[
							'id' => $activo[ 'Id' ],
							'Ima_ActivoFront' => '1/' . $name_photo,
						];
						break;
					case 'right':
						$name_photo = 'Ima_ActivoRight.jpg';
						$update =
						[
							'id' => $activo[ 'Id' ],
							'Ima_ActivoFront' => '1/' . $name_photo,
						];
						break;
					case 'left':
						$name_photo = 'Ima_ActivoLeft.jpg';
						$update =
						[
							'id' => $activo[ 'Id' ],
							'Ima_ActivoFront' => '1/' . $name_photo,
						];
						break;
				}

				$image =  $this->request->getFile( 'file' )->store( '1/', $name_photo );

				$this->activoModel->save( $update );

				/*if (  )
        {
          echo json_encode( array( 'status' => 200, 'msg' => '¡Imagen actualizada!' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar la imagen del activo. Intente más tarde' ) );
					*/
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
				$activo = $this->request->getVar( 'activo' );
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

}
